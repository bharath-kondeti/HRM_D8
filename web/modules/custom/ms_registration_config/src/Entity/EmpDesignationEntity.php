<?php

namespace Drupal\ms_registration_config\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EditorialContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Employee Designation entity.
 *
 * @ingroup ms_registration_config
 *
 * @ContentEntityType(
 *   id = "emp_designation_entity",
 *   label = @Translation("Employee Designation"),
 *   handlers = {
 *     "storage" = "Drupal\ms_registration_config\EmpDesignationEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\ms_registration_config\EmpDesignationEntityListBuilder",
 *     "views_data" = "Drupal\ms_registration_config\Entity\EmpDesignationEntityViewsData",
 *     "translation" = "Drupal\ms_registration_config\EmpDesignationEntityTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\ms_registration_config\Form\EmpDesignationEntityForm",
 *       "add" = "Drupal\ms_registration_config\Form\EmpDesignationEntityForm",
 *       "edit" = "Drupal\ms_registration_config\Form\EmpDesignationEntityForm",
 *       "delete" = "Drupal\ms_registration_config\Form\EmpDesignationEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\ms_registration_config\EmpDesignationEntityHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\ms_registration_config\EmpDesignationEntityAccessControlHandler",
 *   },
 *   base_table = "emp_designation_entity",
 *   data_table = "emp_designation_entity_field_data",
 *   revision_table = "emp_designation_entity_revision",
 *   revision_data_table = "emp_designation_entity_field_revision",
 *   translatable = TRUE,
 *   admin_permission = "administer employee designation entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/emp_designation_entity/{emp_designation_entity}",
 *     "add-form" = "/admin/structure/emp_designation_entity/add",
 *     "edit-form" = "/admin/structure/emp_designation_entity/{emp_designation_entity}/edit",
 *     "delete-form" = "/admin/structure/emp_designation_entity/{emp_designation_entity}/delete",
 *     "version-history" = "/admin/structure/emp_designation_entity/{emp_designation_entity}/revisions",
 *     "revision" = "/admin/structure/emp_designation_entity/{emp_designation_entity}/revisions/{emp_designation_entity_revision}/view",
 *     "revision_revert" = "/admin/structure/emp_designation_entity/{emp_designation_entity}/revisions/{emp_designation_entity_revision}/revert",
 *     "revision_delete" = "/admin/structure/emp_designation_entity/{emp_designation_entity}/revisions/{emp_designation_entity_revision}/delete",
 *     "translation_revert" = "/admin/structure/emp_designation_entity/{emp_designation_entity}/revisions/{emp_designation_entity_revision}/revert/{langcode}",
 *     "collection" = "/admin/structure/emp_designation_entity",
 *   },
 *   field_ui_base_route = "emp_designation_entity.settings"
 * )
 */
class EmpDesignationEntity extends EditorialContentEntityBase implements EmpDesignationEntityInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function urlRouteParameters($rel) {
    $uri_route_parameters = parent::urlRouteParameters($rel);

    if ($rel === 'revision_revert' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }
    elseif ($rel === 'revision_delete' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }

    return $uri_route_parameters;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly,
    // make the emp_designation_entity owner the revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
  }

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
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Employee Designation entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Employee Designation entity.'))
      ->setRevisionable(TRUE)
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
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
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['status']->setDescription(t('A boolean indicating whether the Employee Designation is published.'))
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

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    return $fields;
  }

}
