<?php

  namespace Drupal\donation_form\Form;

use Drupal\node\NodeInterface;
use Drupal\Core\Form\FormBase;
use Drupal\donation_form\DonationFormService;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\node\Entity\Node;


/**
 * UserDetail class which contains the form for accepting user details.
 */
class UserDetails extends Formbase {


  protected $donationformservice;

  /**
   * Function to get the FormId.
   */
  public function getFormId() {
    return 'mycustomform_userdetails';
  }

  /**
   * Initilaze the service object.
   */
  public function __construct(DonationFormService $donationformservice) {
    $this->donationformservice = $donationformservice;
  }

  /**
   * DI of our service.
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('donation_form.service'));
  }

  /**
   * Build the form for user details.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['title'] = [
      '#type' => 'markup',
      '#markup' => 'Your Information:',
    ];

    $form['user_name'] = [
      '#type' => 'textfield',
      '#title' => 'Your Name',
      '#maxlength' => 20,
      '#required' => TRUE,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => 'Email',
      '#required' => TRUE,
    ];

    $form['billing_address'] = [
      '#type' => 'textfield',
      '#title' => 'Billing Address',
      '#required' => TRUE,
    ];

    $form['city'] = [
      '#type' => 'textfield',
      '#title' => 'City',
      '#maxlength' => 20,
      '#required' => TRUE,
    ];

    $form['state'] = [
      '#type' => 'select',
      '#title' => 'State',
      '#options' => [
        'ALASKA'  => 'ALASKA',
        'ARKANS' => 'ARKANS',
        'CALIFORNIA'  => 'CALIFORNIA',
        'COLORADO' => 'COLORADO',
        'CONNECTICUT' => 'CONNECTICUT',
        'DELAWARE' => 'DELAWARE',
        'FLORIDA' => 'FLORIDA',
        'GEORGIA' => 'GEORGIA',
        'HAWAII' => 'HAWAII',
        'IDAHO' => 'IDAHO',
        'ILLINOIS' => 'ILLINOIS',
        'INDIANA' => 'INDIANA',
        'IOWA' => 'IOWA',
        'KANSAS' => 'KANSAS',
        'KENTUCKY' => 'KENTUCKY',
        'LOUISIANA' => 'LOUISIANA',
        'MAINE' => 'MMAINEE',
        'MARYLAND' => 'MARYLAND',
        'MASSACHUSETTS' => 'MMASSACHUSETTSA',
        'MICHIGAN' => 'MICHIGAN',
        'MINNESOTA' => 'MINNESOTA',
        'MISSISSIPPI' => 'MISSISSIPPI',
        'MISSOURI' => 'MISSOURI',
        'MONTANA' => 'MONTANA',
        'NEBRASKA' => 'NEBRASKA',
        'NEVADA' => 'NEVADA',
        'NEW HAMPSHIRE' => 'NEW HAMPSHIRE',
        'NEW JERSEY' => 'NEW JERSEY',
        'NEW MEXICO' => 'NEW MEXICO',
        'NEW YORK'  => 'NEW YORK',
        'NORTH CAROLIN' => 'NORTH CAROLIN',
        'NORTH DAKOTA' => 'NORTH DAKOTA',
        'OHIO' => 'OHIO',
        'OKLAHOMA' => 'OKLAHOMA',
        'OREGON'  => 'OREGON',
        'PENNSYLVANIA' => 'PENNSYLVANIA',
        'RHODE ISLAND' => 'RHODE ISLAND',
        'SOUTH CAROLINA' => 'SOUTH CAROLINA',
        'SOUTH DAKOTA'  => 'SOUTH DAKOTA',
        'TENNESSEE' => 'TENNESSEE',
        'TEXAS' => 'TEXAS',
        'UTAH' => 'UTAH',
        'VERMONT' => 'VERMONT',
        'VIRGINIA' => 'VIRGINIA',
        'WASHINGTON' => 'WASHINGTON',
        'WEST VIRGINIA' => 'WEST VIRGINIA',
        'WISCONSIN' => 'WISCONSIN',
        'WYOMING' => 'WYOMING',
      ],
      '#required' => TRUE,
      '#default_value' => pg_field_is_null(result, row, field),
    ];

    $form['billing_ZIP'] = [
      '#type' => 'textfield',
      '#title' => 'Billing ZIP Name',
      '#required' => TRUE,
    ];

    $form['occupation'] = [
      '#type' => 'textfield',
      '#title' => 'Occupation',
      '#required' => TRUE,
    ];

    $form['employer'] = [
      '#type' => 'textfield',
      '#title' => 'Employer',
      '#required' => TRUE,
    ];

    $form['designate'] = [
      '#type' => 'checkbox',
      '#title' => 'Select This Box to Designate Election',
    ];

    $form['contribution'] = [
      '#type' => 'radios',
      '#title' => 'Contribution',
      '#options' => [
        'General' => $this->t('General'),
        'Primary' => $this->t('Primary'),
        'Next_Election' => $this->t('Next  Election'),
        'Other' => $this->t('Other'),
        '#default_value' => 0,
      ],
      '#states' => [
        'visible' => [
          ':input[name=designate]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['amount'] = [
      '#type' => 'radios',
      '#title' => 'Select An Amount',
      '#options' => [
        '10' => $this->t('$10'),
        '25' => $this->t('$25'),
        '50' => $this->t('$50'),
        '100' => $this->t('$100'),
        '250' => $this->t('$250'),
        '500' => $this->t('$500'),
        '1000' => $this->t('$1000'),
        '2700' => $this->t('$2700'),
        'other' => $this->t('Other'),
      ],
      '#default_value' => 0,
    ];

    $form['other_amount'] = [
      '#type' => 'textfield',
      '#title' => 'Other $',
      '#states' => [
        'visible' => [
          ':input[name="amount"]' => ['value' => 'other'],
        ],
      ],
      '#default_value' => 0,
    ];

    $form['save'] = [
      '#type' => 'submit',
      '#value' => 'Donate',
    ];

    return $form;
  }

  /**
   * Validate for few feilds in the form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    /*
     * If designate is selected then check if contribution is selected..
     */
    if (($form_state->getValue('designate') == 1) && ($form_state->getValue('contribution') == NULL)) {
      $form_state->setErrorByName('contribution', $this->t('Please select one of the Contribution.'));
    }
    /*
     * For validatng the amount.
     */
    if (($form_state->getValue('amount') == 'other') && ($form_state->getValue('other_amount') == '0')) {
      if (!(is_numeric($form_state->getValue('other_amount')))) {
        $form_state->setErrorByName('amount', $this->t('Please input amount in numbers.'));
      }
      else {
        $form_state->setErrorByName('other_amount', $this->t('Please input the amount.'));
      }
    }
    /*
     * For validatng the billing_ZIP.
     */
    if (!(is_numeric($form_state->getValue('billing_ZIP')))) {
      $form_state->setErrorByName('billing_ZIP', $this->t('Please correct the billing_ZIP.'));
    }
  }

  /**
   * Add the values to database in submitForm function.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $user_name = $form_state->getValue('user_name');
    $email = $form_state->getValue('email');
    $billing_address = $form_state->getValue('billing_address');
    $city = $form_state->getValue('city');
    $state = $form_state->getValue('state');
    $billing_ZIP = $form_state->getValue('billing_ZIP');
    $occupation = $form_state->getValue('occupation');
    $employer = $form_state->getValue('employer');
    $designate_contribution = $form_state->getValue('contribution');

    /*
    check if other amount is selected.
     */
    $amount = $form_state->getValue('amount');
    if ($amount === 'other') {

      $amount = $form_state->getValue('other_amount');

    }

    elseif ($amount != 0) {

      $amount = $form_state->getValue('amount');

    }

    if ($designate_contribution == '') {
      $designate_contribution = 'Null';
    }

    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node instanceof NodeInterface) {
      $candidate_or_issue_name = $node->title->value;
    }
    $result = $this->donationformservice->insert($candidate_or_issue_name,
                       $user_name, $email,
                       $billing_address, $city,
                       $state,
                       $billing_ZIP,
                       $occupation,
                       $employer,
                       $designate_contribution,
                       $amount);
  drupal_set_message(t('Successfull.....!!!!!.'));
  }



}
