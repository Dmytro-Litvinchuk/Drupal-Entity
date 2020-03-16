<?php

namespace Drupal\smile_entity\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RoleSmileBlockDeriver extends DeriverBase implements ContainerDeriverInterface {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityManager;

  /**
   * RoleSmileBlockDeriver constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityManager
   */
  public function __construct(EntityTypeManagerInterface $entityManager) {
    $this->entityManager = $entityManager;
  }

  /**
   * @inheritDoc
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * @inheritDoc
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    // All user roles.
    $role_types = $this->entityManager->getStorage('user_role')->loadMultiple();
    foreach ($role_types as $role) {
      $this->derivatives[$role->id()] = $base_plugin_definition;
      // Just translate for interface.
      $admin_label = new TranslatableMarkup('Last Smile entities for "@role_label"', ['@role_label' => $role->label()]);
      // It using instead annotation block.
      $this->derivatives[$role->id()]['admin_label'] = $admin_label;
    }
    return $this->derivatives;
  }

}
