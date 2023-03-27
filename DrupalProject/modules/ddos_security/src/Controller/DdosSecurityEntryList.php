<?php

namespace Drupal\ddos_security\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormState;
use Drupal\Core\Url;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\ddos_security\Services\DdosCrypt;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Datetime\DateFormatterInterface;

/**
 * DDoS Security Entry List Statistics.
 *
 * @package Drupal\ddos_security\Controller
 */
class DdosSecurityEntryList extends ControllerBase {

  /**
   * Entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Encryption param.
   *
   * @var \Drupal\ddos_security\Services\DdosCrypt
   */
  protected $ddosCrypt;

  /**
   * The request stack object.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $request;

  /**
   * Db connection service.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * AltrRegisterDiamondDetails construct function.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   EntityTypeManagerInterface definition call.
   * @param \Drupal\ddos_security\Services\DdosCrypt $ddos_crypt
   *   Encryption service.
   * @param Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   Request service.
   * @param \Drupal\Core\Database\Connection $connection
   *   DB connection object.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter service.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, DdosCrypt $ddos_crypt, RequestStack $request_stack, Connection $connection, DateFormatterInterface $date_formatter) {
    $this->entityTypeManager = $entityTypeManager;
    $this->ddosCrypt = $ddos_crypt;
    $this->request = $request_stack->getCurrentRequest();
    $this->connection = $connection;
    $this->dateFormatter = $date_formatter;
  }

  /**
   * Container create.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   Container create.
   *
   * @return \Drupal\Core\Form\FormBase|static
   *   returns container.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('ddos_security.crypt'),
      $container->get('request_stack'),
      $container->get('database'),
      $container->get('date.formatter')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function entryList($search_keyword = NULL) {
    $itemPerPage = 20;
    // Get current page number.
    $getpagenumber = $this->request->get('page');
    $pageNumber = !empty($getpagenumber) ? $getpagenumber : 0;
    $i = ($pageNumber * $itemPerPage) + 1;

    $content = [];
    $headers = [];
    $rows    = [];

    $headers = [
      ['data' => $this->t('S.no')],
      ['data' => $this->t('Security Number'), 'field' => 'sno'],
      ['data' => $this->t('Last Access'), 'field' => 'created_date'],
      ['data' => $this->t('IP Address'), 'field' => 'ip_address'],
      ['data' => $this->t('IP Hit Count'), 'field' => 'count'],
      [
        'data' => $this->t('Status'),
        'field' => 'status',
        'sort' => 'desc',
      ],
      ['data' => $this->t('Action')],
    ];

    $select = $this->connection->select('ddos_security', 'ds');
    $select->fields('ds', [
      'ip_address',
      'status',
    ]);
    $select->addExpression('MAX(ds.sno)', 'sno');
    $select->addExpression('MAX(ds.created_date)', 'created_date');
    $select->addExpression('COUNT(ds.sid)', 'count');
    $select->groupBy('ds.ip_address');
    $select->groupBy('ds.status');

    if ($search_keyword != NULL) {
      $search_keyword = $this->ddosCrypt->decryptString($search_keyword);
      $select->where(
        "ds.ip_address LIKE '%" . $search_keyword . "%' or
        ds.status LIKE '%" . $search_keyword . "%'
        ");
    }

    $result = $select
      ->extend('Drupal\Core\Database\Query\TableSortExtender')
      ->orderByHeader($headers);

    $result = $result->extend('Drupal\Core\Database\Query\PagerSelectExtender')
      ->limit($itemPerPage)->execute()->fetchAll();

    foreach ($result as $entry) {
      $action = ($entry->status == 'allowed') ? 'block' : 'unblock';
      $update = Url::fromUserInput('/admin/config/ddos-security-action/' . $this->ddosCrypt->encryptString($action) . '/' . $this->ddosCrypt->encryptString($entry->ip_address));
      $delete = Url::fromUserInput('/admin/config/ddos-security-action/' . $this->ddosCrypt->encryptString('delete') . '/' . $this->ddosCrypt->encryptString($entry->ip_address));

      $rows[] = [
        ['data' => $i++],
        ['data' => $entry->sno],
        ['data' => $this->dateFormatter->format($entry->created_date, 'short')],
        ['data' => $entry->ip_address],
        ['data' => $entry->count],
        ['data' => $entry->status],
        [
          'data' => $this->t('<a href="@update" title="Update Entry">Update</a> /
          <a href="@delete" title="Delete Entry">Delete</a>', [
            '@update' => $update->toString(),
            '@delete' => $delete->toString(),
          ]),
        ],
      ];
    }

    $form_state = new FormState();
    $form_state->setMethod('get');
    $form_state->setAlwaysProcess(TRUE);
    $form_state->setRebuild();
    $search_keyword = !empty($search_keyword) ? $this->ddosCrypt->encryptString($search_keyword) : '';

    // Get DDoS Security Entry List search form.
    $content['form'] = $this->formBuilder()
      ->getForm('Drupal\ddos_security\Form\DdosSecurityEntrySearch', $search_keyword, $form_state);

    $content['table'] = [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#empty' => $this->t('No entries available.'),
    ];

    // For pagination.
    $content['pager'] = [
      '#type' => 'pager',
    ];

    // Don't cache this page.
    $content['#cache']['max-age'] = 0;

    return $content;
  }

}
