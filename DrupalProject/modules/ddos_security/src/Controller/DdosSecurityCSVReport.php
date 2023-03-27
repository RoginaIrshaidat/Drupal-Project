<?php

namespace Drupal\ddos_security\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * DDoS Security CSV Report Generator.
 *
 * @package Drupal\ddos_security\Controller
 */
class DdosSecurityCSVReport extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Db connection service.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * DdosSecurityCSVReport constructor.
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
     $container->get('database')
    );
  }

  /**
   * Export a CSV of data.
   */
  public function build() {
    // Start using file handler functions to create a tmp file.
    $handle = fopen('php://temp', 'w+');

    // Setup the header that will be displayed as the 1st line of the CSV file.
    // Blank strings are used for multi-cell values where there is a count of
    // the "keys" and a list of the keys with the count of their usage.
    $header = [
      'S.no',
      'Security Number',
      'IP Address',
      'IP Hit Count',
      'Status',
    ];
    // Add the header as the first line of the CSV.
    fputcsv($handle, $header);
    // Find and load all of the Article nodes we are going to include.
    $select = $this->connection->select('ddos_security', 'ds');
    $select->fields('ds', [
      'ip_address',
      'status',
    ]);

    $select->addExpression('MAX(ds.sno)', 'sno');
    $select->addExpression('COUNT(ds.sid)', 'count');
    $select->groupBy('ds.ip_address');
    $select->groupBy('ds.status');

    $result = $select->execute()->fetchAll();

    $i = 1;

    // Iterate through the data. We want one row in the CSV per Data.
    foreach ($result as $entry) {
      // Build the array for putting the row data together.
      $data = [
        'sno' => $i++,
        'security_number' => $entry->sno,
        'ip_address' => $entry->ip_address,
        'count' => $entry->count,
        'status' => $entry->status,
      ];

      // Add the data we exported to the next line of the CSV.
      fputcsv($handle, array_values($data));
    }
    // Reset where we are in the CSV.
    rewind($handle);

    // Retrieve the data from the file handler.
    $csv_data = stream_get_contents($handle);
    // Close the file handler since we don't need it anymore. We are not storing
    // this file anywhere in the filesystem.
    fclose($handle);

    // This is the "magic" part of the code.  Once the data is built, we can
    // return it as a response.
    $response = new Response();

    // By setting these 2 header options, the browser will see the URL
    // used by this Controller to return a CSV file called
    // "ddos-report-<today-date>.csv".
    $response->headers->set('Content-Type', 'text/csv');
    $response->headers->set('Content-Disposition', 'attachment; filename="ddos-report-' . date("Y-m-d") . '.csv"');

    // This line physically adds the CSV data we created.
    $response->setContent($csv_data);

    return $response;
  }

}
