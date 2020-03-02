<?php

namespace Drupal\ms_registration_config\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Employee Designation entities.
 */
class EmpDesignationEntityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
