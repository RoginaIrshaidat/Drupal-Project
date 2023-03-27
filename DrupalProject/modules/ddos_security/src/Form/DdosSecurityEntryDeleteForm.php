<?php

namespace Drupal\ddos_security\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\ConfirmFormBase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\ddos_security\Services\DdosCrypt;

/**
 * Class DDoS Security Entry Delete Form.
 *
 * @package Drupal\ddos_security\Form
 */
class DdosSecurityEntryDeleteForm extends ConfirmFormBase implements ContainerInjectionInterface {

  /**
   * Action of the item to update/delete.
   *
   * @var string
   */
  public $action;

  /**
   * IP of the item to delete.
   *
   * @var int
   */
  public $ip;

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Encryption param.
   *
   * @var \Drupal\ddos_security\Services\DdosCrypt
   */
  protected $ddosCrypt;

  /**
   * {@inheritdoc}
   */
  public function __construct(MessengerInterface $messenger, DdosCrypt $ddos_crypt) {
    $this->messenger = $messenger;
    $this->ddosCrypt = $ddos_crypt;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger'),
      $container->get('ddos_security.crypt')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $action = NULL, $ip = NULL) {
    if (empty($action) || empty($ip)) {
      return new RedirectResponse(Url::fromRoute('ddos_security.ddos_security_entry_search')->toString());
    }

    $this->req_action = !empty($action) ? $this->ddosCrypt->decryptString($action) : '';
    $this->req_ip = !empty($ip) ? $this->ddosCrypt->decryptString($ip) : '';

    if (!empty(trim($this->ddosCrypt->decryptString($this->req_ip))) && !filter_var(trim($this->ddosCrypt->decryptString($this->req_ip)), FILTER_VALIDATE_IP)) {
      $form_state->setRedirect('ddos_security.ddos_security_entry_search');
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $ddos_security_qry = Database::getConnection();

    switch (!empty($this->req_action)) {

      case ($this->req_action == "block" || $this->req_action == "unblock"):
        $user_status = ($this->req_action == 'block') ? 'blocked' : 'allowed';
        $ddos_security_qry->update('ddos_security')
          ->fields(["status" => $user_status])
          ->condition('ip_address', $this->req_ip, '=')
          ->execute();
        break;

      case ($this->req_action == "delete"):
        $ddos_security_qry->delete('ddos_security')
          ->condition('ip_address', $this->req_ip)
          ->execute();
        break;
    }

    $status_message = ($this->req_action == 'delete') ? 'deleted' : 'updated';

    if ($ddos_security_qry == TRUE) {
      $this->messenger->addStatus($this->t('Record @status successfully.', ['@status' => $status_message]));
    }

    $form_state->setRedirect('ddos_security.ddos_security_entry_search');
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ddos_security_delete_from';
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('ddos_security.ddos_security_entry_search');
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Do you want to @action this user record ?', ['@action' => $this->req_action]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {

    $confirm_text = !empty($this->req_action) ? ucfirst($this->req_action) : '';

    return $this->t('@action it!', ['@action' => $confirm_text]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelText() {
    return $this->t('Cancel');
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

}
