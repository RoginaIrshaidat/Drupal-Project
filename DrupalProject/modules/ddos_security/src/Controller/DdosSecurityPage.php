<?php

namespace Drupal\ddos_security\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Language\LanguageManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Class DdosSSecurityPage.
 */
class DdosSecurityPage extends ControllerBase {

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
      $container->get('config.factory'),
      $container->get('language_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function displayAlertMessage() {
    $user_access_status = !empty(ddos_security_user_access_status()) ? ddos_security_user_access_status() : '';
    $ddos_security_config = $this->configfactory->get('ddos_security.settings');
    $html_meaasage = (!empty($user_access_status) && $user_access_status == 'blocked') ? $ddos_security_config->get('message')['value'] : $ddos_security_config->get('403_message')['value'];

    return [
      '#theme' => 'ddos_alert_message',
    // Set cache for 0 seconds.
      '#cache' => ['max-age' => 0],
      '#html_data' => $html_meaasage,
    ];
  }

}
