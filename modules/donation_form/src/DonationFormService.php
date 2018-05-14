<?php

 namespace Drupal\donation_form;

/**Doc
 * @file
 */
use Drupal\Core\Database\Connection;

/**
 * This is Service class.
 */
class DonationFormService {

  protected $connection;

  /**
   * This is Service constructor.
   */
  public function __construct(Connection $connection) {
    $this->connection = $connection;

  }

  /**
   * Insert in the database function.
   */
  public function insert($candidate_or_issue_name,
                       $user_name,
                       $email,
                       $billing_address,
                       $city,
                       $state,
                       $billing_ZIP,
                       $occupation,
                       $employer,
                       $designate_contribution,
                       $amount) {

    $result = $this->connection->insert('user_details')
      ->fields([
        'candidate_or_issue_name' => $candidate_or_issue_name,
        'user_name' => $user_name,
        'email' => $email,
        'billing_address' => $billing_address,
        'city' => $city,
        'state' => $state,
        'billing_ZIP' => $billing_ZIP,
        'occupation' => $occupation,
        'employer' => $employer,
        'designate_contribution' => $designate_contribution,
        'amount' => $amount,
      ])->execute();

  }

}
