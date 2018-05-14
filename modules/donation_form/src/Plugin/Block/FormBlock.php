<?php

namespace Drupal\donation_form\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'donation_form_userdetails' block.
 *
 * @Block(
 *   id = "donation_form_userdetails_block",
 *   admin_label = @Translation("userdetails block"),
 * )
 */
class FormBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('Drupal\donation_form\Form\UserDetails');
    return $form;
  }

}
