<?php

namespace Drupal\smile_entity;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of title entities.
 *
 * @ingroup smile_entity
 */
class SmileEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('title ID');
    $header['name'] = $this->t('title');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\smile_entity\Entity\SmileEntity $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.smile.canonical',
      ['smile' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
