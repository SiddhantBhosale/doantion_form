<?php

namespace Drupal\donation_form\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'donation_form_donatevoteUS' block.
 *
 * @Block(
 *   id = "donation_form_donatevoteUS_block",
 *   admin_label = @Translation("doantevoteUS block"),
 * )
 */
class NewFormBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('Drupal\donation_form\Form\DonateVoteUS');
    return $form;
  }

}
