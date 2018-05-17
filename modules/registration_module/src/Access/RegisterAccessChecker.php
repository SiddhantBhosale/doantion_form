<?php

namespace Drupal\registration_module\Access;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Class for custom access check.
 */
class RegisteraccessChecker implements AccessInterface {

  /**
   * Method to check if the user is Authenticated.
   */
  public function access(AccountInterface $account) {

      if ($account->isAnonymous() || $account->isAuthenticated()) {
      return AccessResult::allowed();
    }
    else {
      return AccessResult::forbidden();
    }
  }

}
