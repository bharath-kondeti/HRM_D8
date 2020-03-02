<?php

namespace Drupal\ms_registration_config;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Employee Designation entities.
 *
 * @ingroup ms_registration_config
 */
class EmpDesignationEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Employee Designation ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\ms_registration_config\Entity\EmpDesignationEntity $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.emp_designation_entity.edit_form',
      ['emp_designation_entity' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
