<?php

namespace Drupal\smile_entity\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining title entities.
 *
 * @ingroup smile_entity
 */
interface SmileEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the title name.
   *
   * @return string
   *   Name of the title.
   */
  public function getName();

  /**
   * Sets the title name.
   *
   * @param string $name
   *   The title name.
   *
   * @return \Drupal\smile_entity\Entity\SmileEntityInterface
   *   The called title entity.
   */
  public function setName($name);

  /**
   * Gets the title creation timestamp.
   *
   * @return int
   *   Creation timestamp of the title.
   */
  public function getCreatedTime();

  /**
   * Sets the title creation timestamp.
   *
   * @param int $timestamp
   *   The title creation timestamp.
   *
   * @return \Drupal\smile_entity\Entity\SmileEntityInterface
   *   The called title entity.
   */
  public function setCreatedTime($timestamp);

}
