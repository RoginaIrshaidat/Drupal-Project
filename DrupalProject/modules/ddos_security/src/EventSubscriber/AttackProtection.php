<?php

namespace Drupal\ddos_security\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Drupal\mysql\Driver\Database\mysql\Connection;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Session\SessionManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Path\CurrentPathStack;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\Core\Language\LanguageManagerInterface;

/**
 * DDoS Attack Protection.
 */
class AttackProtection implements EventSubscriberInterface {

  /**
   * The client IP address.
   *
   * @var string
   */
  protected $clientIp;

  /**
   * The Config storage.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;

  /**
   * The Logger factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * Drupal\Core\Database\Driver\mysql\Connection definition.
   *
   * @var \Drupal\Core\Database\Driver\mysql\Connection
   */
  protected $database;

  /**
   * The account.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $account;

  /**
   * Current path.
   *
   * @var \Drupal\Core\Path\CurrentPathStack
   */
  protected $currentPath;

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * The language manager service.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(RequestStack $request_stack,
                              ConfigFactoryInterface $config,
                              LoggerChannelFactoryInterface $logger_factory,
                              Connection $database,
                              AccountProxyInterface $account,
                              SessionManagerInterface $session_manager,
                              CurrentPathStack $current_path,
                              DateFormatterInterface $date_formatter,
                              LanguageManagerInterface $language_manager) {
    $this->clientIp = $request_stack->getCurrentRequest()->getClientIp();
    $this->config = $config;
    $this->loggerFactory = $logger_factory;
    $this->database = $database;
    $this->currentUser = $account;
    $this->sessionManager = $session_manager;
    $this->currentPath = $current_path;
    $this->dateFormatter = $date_formatter;
    $this->languageManager = $language_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['ddosSecurity', 300];
    return $events;
  }

  /**
   * Call this method whenever the KernelEvents::TERMINATE event is dispatched.
   */
  public function ddosSecurity(RequestEvent $event) {
    $ddosConfig = $this->config->get('ddos_security.settings');
    $current_path = $this->currentPath->getPath();
    $languageCode = $this->languageManager->getCurrentLanguage()->getId();

    $prefix = $languageCode == 'en' ? $ddosConfig->get('redirect_url') : '/' . $languageCode . $ddosConfig->get('redirect_url');
    $url = URL::fromUserInput($prefix)->toString();
    $ip_status = !empty(ddos_security_user_access_status()) ? ddos_security_user_access_status() : '';

    if ($this->currentUser->isAnonymous() && $ddosConfig->get('enable_ddos') == 1) {

      if ($ip_status != 'blocked' && strpos($current_path, $ddosConfig->get('redirect_url')) !== FALSE) {
        $prefix = $languageCode == 'en' ? '/' : '/' . $languageCode . '/';
        $url = URL::fromUserInput($prefix)->toString();
        $response = new RedirectResponse($url);
        $response->send();
        return;
      }

      if (!empty($ddosConfig->get('enable_malicious_requests')) && $ddosConfig->get('enable_malicious_requests') == 1) {

        // Prevent DDOS attack via HTTP_USER_AGENT.
        if(!empty($_SERVER['HTTP_USER_AGENT'])) {
          $user_agent = $_SERVER['HTTP_USER_AGENT'];

          if(strpos($user_agent, 'bot') !== FALSE || strpos($user_agent, 'crawler') !== FALSE || strpos($user_agent, 'spider') !== FALSE) {
            // Deny access.
            $this->denyAccess();
          }
        }

        // Parse the request URL.
        $request = $_SERVER['REQUEST_URI'];

        // Prevent DDOS attack from a URL request.
        $this->detectDdosAttackUrl($request) ? $this->denyAccess() : '';

      }

      if ($ip_status != 'blocked' || strpos($current_path, '/user/') !== FALSE || strpos($current_path, $ddosConfig->get('redirect_url')) !== FALSE) {
        $t_time = date('ymdHis', time());
        $frm_time = date('ymdHis', strtotime("-1 minutes"));
        $created_date = date(time());

        $ip_count = $this->ipHitCount($frm_time, $t_time);

        if ($ip_count < $ddosConfig->get('total_hits')) {
          $query = $this->database->select('ddos_security');
          $query->condition('ip_address', $this->clientIp, '=');
          $query->addExpression('MAX(sno)');
          $max_sno = $query->execute()->fetchField();

          if ($max_sno < $t_time) {
            try {

              $allowed_ips = !empty($ddosConfig->get('whitelisted_ip_addresses')) ? explode("\r\n", trim($ddosConfig->get('whitelisted_ip_addresses'))) : '';
              $allowed_pages = !empty($ddosConfig->get('whitelisted_pages')) ? explode("\r\n", trim($ddosConfig->get('whitelisted_pages'))) : '';

              $ddos_insert = $this->database->insert('ddos_security')
              ->fields(['sno', 'ip_address', 'status', 'created_date'])
              ->values([
                'sno' => $t_time,
                'ip_address' => mb_substr($this->clientIp, 0, 128),
                'status' => 'allowed',
                'created_date' => $created_date,
              ]);

              switch (TRUE) {
                case (strpos($current_path, $ddosConfig->get('redirect_url')) !== FALSE):
                  break;
                case (!empty($allowed_ips) && in_array($this->clientIp, $allowed_ips) == FALSE):
                  $ddos_insert->execute();
                  break;
                case (!empty($allowed_pages) && in_array('/' . basename($current_path), $allowed_pages) == FALSE):
                  $ddos_insert->execute();
                  break;
                default:
                  $ddos_insert->execute();
                  break;
              }

            }
            catch (\Exception $e) {
              $this->loggerFactory->get('ddos_security')
                ->notice('<pre><code>Exception MESSAGE @error </code></pre>',
                [
                  '@error' => print_r($e, TRUE),
                ]);
            }
          }
        }

        else {
          try {
            $ddos_update = $this->database->update('ddos_security')
              ->fields(["status" => "blocked"])
              ->condition('ip_address', $this->clientIp, '=');
            $ddos_update->execute();
          }
          catch (\Exception $e) {
            $this->loggerFactory->get('ddos_security')
              ->notice('<pre><code>Exception MESSAGE @error </code></pre>',
              [
                '@error' => print_r($e, TRUE),
              ]);
          }
          $response = new RedirectResponse($url);
          $response->send();
          return;
        }
      }

      else {
        $response = new RedirectResponse($url);
        $response->send();
        return;
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function ipHitCount($frm_time = NULL, $t_time = NULL) {
    $ip_count = '';

    $query = $this->database->select("ddos_security", "ds");
    $query->condition('ds.ip_address', $this->clientIp, '=');
    $query->condition('ds.sno', $frm_time, '>=');
    $query->condition('ds.sno', $t_time, '<=');
    $query->condition('ds.status', 'allowed', '=');
    $query->addExpression('COUNT(*)');
    $ip_count = $query->execute()->fetchField();

    return $ip_count;
  }

  /**
   * {@inheritdoc}
   */
  public function denyAccess() {
    $pre_html_403_message = '<!DOCTYPE html>
    <html>
        <head>
            <title>'.t('Error 403: Forbidden').'</title>
        </head>
        <body>
          <center>
            <h1>'.t('Error 403: Forbidden').'</h1>
            <p>'.t('You do not have permission to access this site.').'</p>
          </center>
        </body>
    </html>';

    $config_403_message = !empty($this->config->get('ddos_security.settings')->get('403_message')) ? $this->config->get('ddos_security.settings')->get('403_message')['value'] : $pre_html_403_message;

    $html_403_message = '<!DOCTYPE html>
    <html>
        <head>
            <title>'.t('Error 403: Forbidden').'</title>
        </head>
        <body>
          <center>
            '.$config_403_message.'
          </center>
        </body>
    </html>';

    // Print the html string on the page.
    print_r($html_403_message);
    die;
  }

  /**
   * {@inheritdoc}
   */
  public function detectDdosAttackUrl($url) {
    // Parse the URL into its components.
    $url_components = parse_url($url);

    // Define a blacklist of malicious requests.
    $pre_malicious_request = '%3Cscript,%3Cscript%3E,<script,<script>,%3C?php,<?php,%3C?,<?';
    $blacklisted_values = !empty($this->config->get('ddos_security.settings')->get('malicious_requests_list')) ? $this->config->get('ddos_security.settings')->get('malicious_requests_list') : $pre_malicious_request;
    $malicious_requests = explode(',', $blacklisted_values);

    // Iterate through the blacklist.
    foreach ($malicious_requests as $malicious) {
      // If the request contains a malicious item, exit the script.
      if (strpos($url, $malicious) !== false) {
        // Deny access.
        return true;
      }
    }

    // Get the query string from the URL.
    $query_string = !empty($url_components['query']) ? $url_components['query'] : '';

    // If the query string is not empty, extract all parameters.
    if (!empty($query_string)) {
      parse_str($query_string, $params);
      // Check if any of the parameters have a suspicious value.
      foreach ($params as $param => $value) {
        if (strlen($value) > 1024) {
          // Suspicious value detected.
          return true;
        }
      }
    }

    // No suspicious values detected.
    return false;
  }
}
