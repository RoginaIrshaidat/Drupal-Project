<?php

namespace Drupal\ddos_security\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * DDos security admin settings form.
 *
 * @package Drupal\ddos_security\Form
 */
class DdosSecurityConfigSettingsForm extends ConfigFormBase implements ContainerInjectionInterface {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'ddos_security.settings';

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * The Config storage.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configfactory;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory, LanguageManagerInterface $language_manager) {
    $this->configfactory = $config_factory;
    $this->languageManager = $language_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
       $container->get('config.factory'), $container->get('language_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ddos_security_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['ddos_security'] = [
      '#type' => 'details',
      '#title' => $this->t('DDoS Security Settings'),
      '#open' => FALSE,
      '#weight' => 1,
    ];

    $form['ddos_security']['enable_ddos'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable DDoS Security'),
      '#default_value' => $config->get('enable_ddos'),
      '#weight' => -10,
      '#description' => $this->t('Enable protection against DDoS attacks.'),
    ];

    $form['ddos_security']['redirect_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Redirect URL at DDoS attack'),
      '#disabled' => TRUE,
      '#default_value' => !empty($config->get('redirect_url')) ? $config->get('redirect_url') : '/ddos-alert-message',
      '#size' => 40,
      '#weight' => -9,
      '#description' => $this->t('Send attackers to this internal page when they try DDoS attack.'),
      '#required' => TRUE,
    ];

    $form['ddos_security']['message'] = [
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#title' => $this->t('Message to display to the user after DDoS attack'),
      '#description' => $this->t('This message is displayed after the user was redirect due to DDoS attack. You can leave this blank to show no message to the user.'),
      '#default_value' => !empty($config->get('message')) ? $config->get('message')['value'] : '',
    ];

    $form['ddos_security']['403_message'] = [
      '#type' => 'text_format',
      '#format' => 'full_html',
      '#title' => $this->t('Message to display to the user before DDoS attack'),
      '#description' => $this->t('This message is displayed when the anonymous user access this page.'),
      '#default_value' => !empty($config->get('403_message')) ? $config->get('403_message')['value'] : '',
    ];

    $form['ddos_security']['whitelisted_ip_addresses'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Whitelisted ip addresses'),
      '#default_value' => $config->get('whitelisted_ip_addresses'),
      '#size' => 40,
      '#description' => $this->t('Users from these IP addresses will not be logged. Enter one IP address per line. Ex. (127.0.0.1)'),
    ];

    $form['ddos_security']['whitelisted_pages'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Whitelisted pages'),
      '#default_value' => $config->get('whitelisted_pages'),
      '#size' => 40,
      '#description' => $this->t("Specify pages by using their paths. Enter one path per line. Users access for these pages will not be logged. An example path is '/page' path.", [
        '%upath' => '/example',
      ]),
    ];

    $form['ddos_security']['enable_mail_log'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable DDoS Security Mail Log Notification'),
      '#default_value' => $config->get('enable_mail_log'),
      '#description' => $this->t('Send DDoS attackers report log notification to below mail id.'),
    ];

    $form['ddos_security']['log_mail_id'] = [
      '#type' => 'email',
      '#title' => $this->t('DDoS Security - Report Mail Id'),
      '#default_value' => $config->get('log_mail_id'),
      '#size' => 50,
      '#description' => $this->t('Send DDoS attackers report log link to this mail id.'),
    ];

    $form['ddos_security']['total_hits'] = [
      '#type' => 'number',
      '#title' => $this->t('Total hits allowed per second'),
      '#description' => $this->t('The total number of hits per page should allow the user’s to access.'),
      '#maxlength' => 255,
      '#size' => 64,
      '#default_value' => $config->get('total_hits'),
    ];

    $form['ddos_security']['enable_malicious_requests'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable to prevent ddos attack in malicious requests from a URL'),
      '#default_value' => $config->get('enable_malicious_requests'),
      '#description' => $this->t('An automated defense system that can detect and block malicious requests.'),
    ];

    $form['ddos_security']['malicious_requests_list'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Malicious requests list'),
      '#default_value' => $config->get('malicious_requests_list'),
      '#size' => 40,
      '#description' => $this->t('Add malicious requests list separated by comma(,). Malicious requests like local.com/%values% are blocked in server-side by filtering malicious characters in the URL and rejecting requests that contain them.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    $redirect_url = $values['redirect_url'];
    $log_mail_id = $values['log_mail_id'];

    // Validate redirect url.
    if (strpos($redirect_url, '/') !== 0) {
      $form_state->setErrorByName('redirect_url', $this->t("Redirect URL at DDoS attack :redirect_url must begin with a '/'", [':redirect_url' => $redirect_url]));
    }
    // Validate ip address list.
    $whitelisted_ip_addresses_list = explode("\r\n", trim($values['whitelisted_ip_addresses']));

    foreach ($whitelisted_ip_addresses_list as $ip_address) {
      if (!empty($ip_address) && !filter_var(trim($ip_address), FILTER_VALIDATE_IP)) {
        $form_state->setErrorByName(
             'whitelisted_ip_addresses',
                $this->t('Whitlelisted IP address list should contain only valid IP addresses, one per row')
              );
      }
    }

    // Validate pages list.
    $whitelisted_pages_list = explode("\r\n", trim($values['whitelisted_pages']));

    foreach ($whitelisted_pages_list as $pages_list) {
      if (!empty($pages_list) && strpos($pages_list, '/') !== 0) {
        $form_state->setErrorByName(
             'whitelisted_pages',
                $this->t("whitelisted pages list must be begin with a '/', one per row")
              );
      }
    }

    // Validate email address.
    if (!empty($log_mail_id) && !filter_var(trim($log_mail_id), FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName(
           'log_mail_id',
              $this->t('Please enter valid e-Mail address')
            );
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $ddos_settings = $this->config(static::SETTINGS);

    $ddos_settings->set('enable_ddos', $values['enable_ddos'])
      ->set('redirect_url', $values['redirect_url'])
      ->set('message', $values['message'])
      ->set('403_message', $values['403_message'])
      ->set('whitelisted_ip_addresses', $values['whitelisted_ip_addresses'])
      ->set('whitelisted_pages', $values['whitelisted_pages'])
      ->set('enable_mail_log', $values['enable_mail_log'])
      ->set('log_mail_id', $values['log_mail_id'])
      ->set('total_hits', $values['total_hits'])
      ->set('enable_malicious_requests', $values['enable_malicious_requests'])
      ->set('malicious_requests_list', $values['malicious_requests_list'])
      ->save();

    parent::submitForm($form, $form_state);
  }

}
