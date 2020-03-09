<?php

namespace Drupal\smile_entity\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the title entity.
 *
 * @ingroup smile_entity
 *
 * @ContentEntityType(
 *   id = "smile",
 *   label = @Translation("title"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\smile_entity\SmileEntityListBuilder",
 *     "views_data" = "Drupal\smile_entity\Entity\SmileEntityViewsData",
 *     "translation" = "Drupal\smile_entity\SmileEntityTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\smile_entity\Form\SmileEntityForm",
 *       "add" = "Drupal\smile_entity\Form\SmileEntityForm",
 *       "edit" = "Drupal\smile_entity\Form\SmileEntityForm",
 *       "delete" = "Drupal\smile_entity\Form\SmileEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\smile_entity\SmileEntityHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\smile_entity\SmileEntityAccessControlHandler",
 *   },
 *   base_table = "smile",
 *   data_table = "smile_field_data",
 *   translatable = TRUE,
 *   admin_permission = "administer title entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "body" = "body",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/smile/{smile}",
 *     "add-form" = "/smile/add",
 *     "edit-form" = "/smile/{smile}/edit",
 *     "delete-form" = "/smile/{smile}/delete",
 *     "collection" = "/smile",
 *   },
 *   field_ui_base_route = "smile.settings"
 * )
 */
class SmileEntity extends ContentEntityBase implements SmileEntityInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDescription(t('The name of the title entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'default_value' => '',
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['body'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Body'))
      ->setDescription(t('The body of entity'))
      ->setSettings([
        'default_value' => '',
        'max_length' => 1000,
        'text_processing' => 0,
      ])
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status']->setDescription(t('A boolean indicating whether the title is published.'))
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
