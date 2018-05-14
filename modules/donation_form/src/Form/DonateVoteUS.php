<?php

namespace Drupal\donation_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * DoanteVoteUs class for form selecting the candidate or group.
 */
class DonateVoteUS extends Formbase {

  /**
   * Function to get the FormId.
   */
  public function getFormId() {
    return 'mycustomform_donateus';
  }

  /**
   * Build the form to select the candidate or issue group.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['content_type'] = [
      '#type' => 'radios',
      '#title' => 'Donate To',
      '#options' => [
        'candidate' => $this->t('Candidate'),
        'pac' => $this->t('PAC'),
        'super_pac' => $this->t('Super PAC'),
        '527_organization' => $this->t('527 Organization'),
        'political_party_group' => $this->t('Political Party Group'),
      ],
    ];

    $form['name_Candidate'] = [
      '#type' => 'entity_autocomplete',
      '#title' => 'Name',
      '#target_type' => 'node',
      '#selection_handler' => 'default',
      '#selection_settings' => [
        'target_bundles' => ['Candidate'],
      ],
      '#states' => [
        'visible' => [
          ':input[name="content_type"]' => ['value' => 'candidate'],
        ],
      ],
    ];

    $form['name_PAC'] = [
      '#type' => 'entity_autocomplete',
      '#title' => 'Name',
      '#target_type' => 'node',
      '#selection_handler' => 'default',
      '#selection_settings' => [
        'target_bundles' => ['PAC'],
      ],
      '#states' => [
        'visible' => [
          ':input[name="content_type"]' => ['value' => 'pac'],
        ],
      ],
    ];

    $form['name_Super_PAC'] = [
      '#type' => 'entity_autocomplete',
      '#title' => 'Name',
      '#target_type' => 'node',
      '#selection_handler' => 'default',
      '#selection_settings' => [
        'target_bundles' => ['Super_PAC'],
      ],
      '#states' => [
        'visible' => [
          ':input[name="content_type"]' => ['value' => 'super_pac'],
        ],
      ],
    ];

    $form['name_527_Organization'] = [
      '#type' => 'entity_autocomplete',
      '#title' => 'Name',
      '#target_type' => 'node',
      '#selection_handler' => 'default',
      '#selection_settings' => [
        'target_bundles' => ['527_Organization'],
      ],
      '#states' => [
        'visible' => [
          ':input[name="content_type"]' => ['value' => '527_organization'],
        ],
      ],
    ];

    $form['name_Political_Party_Group'] = [
      '#type' => 'entity_autocomplete',
      '#title' => 'Name',
      '#target_type' => 'node',
      '#selection_handler' => 'default',
      '#selection_settings' => [
        'target_bundles' => ['Political_Party_Group'],
      ],
      '#states' => [
        'visible' => [
          ':input[name="content_type"]' => ['value' => 'political_party_group'],
        ],
      ],
    ];

    $form['save'] = [
      '#type' => 'submit',
      '#value' => 'Proceed',
    ];

    return $form;
  }

  /**
   * Submit the form to redirect to next panel page.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    if (!empty($temp = $form_state->getValue('name_Candidate'))) {
      $nid = $temp;
    }
    elseif (!empty($temp = $form_state->getValue('name_PAC'))) {
      $nid = $temp;
    }
    elseif (!empty($temp = $form_state->getValue('name_Super_PAC'))) {
      $nid = $temp;
    }
    elseif (!empty($temp = $form_state->getValue('name_527_Organization'))) {
      $nid = $temp;
    }
    elseif (!empty($temp = $form_state->getValue('name_Political_Party_Group'))) {
      $nid = $temp;
    }

    $url = Url::fromUri('internal:/donateus/' . $nid);

    $form_state->setRedirectUrl($url);

  }

}
