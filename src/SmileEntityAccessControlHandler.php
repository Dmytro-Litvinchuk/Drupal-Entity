<?php

namespace Drupal\smile_entity;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the title entity.
 *
 * @see \Drupal\smile_entity\Entity\SmileEntity.
 */
class SmileEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\smile_entity\Entity\SmileEntityInterface $entity */

    // Check entity role before get access.
    if (in_array($entity->get('role')->getString(), $account->getRoles())) {
      switch ($operation) {

        case 'view':

          if (!$entity->isPublished()) {
            return AccessResult::allowedIfHasPermission($account, 'view unpublished title entities');
          }

          return AccessResult::allowedIfHasPermission($account, 'view published title entities');

        case 'update':

          return AccessResult::allowedIfHasPermission($account, 'edit title entities');

        case 'delete':

          return AccessResult::allowedIfHasPermission($account, 'delete title entities');
      }
      // Unknown operation, no opinion.
      return AccessResult::neutral();
    }
    // User have not access for the entity.
    return AccessResult::forbidden();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add title entities');
  }


}
