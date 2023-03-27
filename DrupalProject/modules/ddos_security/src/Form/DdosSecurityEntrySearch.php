<?php

namespace Drupal\ddos_security\Form;

use Drupal\Core\Url;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\ddos_security\Services\DdosCrypt;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * DDoS Security Entry Search Form.
 *
 * @package Drupal\ddos_security\Form
 */
class DdosSecurityEntrySearch extends FormBase {

  /**
   * Encryption param.
   *
   * @var \Drupal\ddos_security\Services\DdosCrypt
   */
  protected $ddosCrypt;

  /**
   * {@inheritdoc}
   */
  public function __construct(DdosCrypt $ddos_crypt) {
    $this->ddosCrypt = $ddos_crypt;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('ddos_security.crypt')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ddos_security_entry_search';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $search_keyword = NULL) {

    $form['keyword'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Search Keyword'),
      '#description' => $this->t('#Search Keyword (Min. 3 Char.): keyword finds match in IP Address, Status'),
      '#default_value' => !empty($search_keyword) ? $this->ddosCrypt->decryptString($search_keyword) : '',
      '#required' => TRUE,
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Search'),
      '#button_type' => 'primary',
    ];

    $form['actions']['reset'] = [
      '#type' => 'submit',
      '#value' => $this->t('Reset'),
      '#limit_validation_errors' => [],
      '#submit' => ['::newResetSubmissionHandler'],
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * Custom reset submission handler for redirect.
   */
  public function newResetSubmissionHandler(array &$form, FormStateInterface $form_state) {
    // Redirect to the route.
    $route_name = 'ddos_security.ddos_security_entry_search';
    $form_state->setRedirect($route_name);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $keyword = $form_state->getValue('keyword');
    if (strlen($keyword) < 3) {
      $form_state->setErrorByName('keyword', $this->t('Search keyword is too short.'));
    }
    if (!empty($keyword) && preg_match("/[^A-Za-z0-9.]/", trim($keyword))) {
      $form_state->setErrorByName('keyword', $this->t('Special characters are not allowded.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $keyword = $form_state->getValue('keyword');
    $keyword = !empty($keyword) ? $this->ddosCrypt->encryptString($keyword) : '';

    $path = Url::fromRoute('ddos_security.ddos_security_entry_search',
     ['search_keyword' => $keyword])->toString();
    $response = new RedirectResponse($path);
    $response->send();
  }

}
