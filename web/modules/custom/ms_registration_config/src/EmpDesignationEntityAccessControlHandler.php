<?php

namespace Drupal\ms_registration_config;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Employee Designation entity.
 *
 * @see \Drupal\ms_registration_config\Entity\EmpDesignationEntity.
 */
class EmpDesignationEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\ms_registration_config\Entity\EmpDesignationEntityInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished employee designation entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published employee designation entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit employee designation entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete employee designation entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add employee designation entities');
  }


}
