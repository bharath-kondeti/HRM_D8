<?php

namespace Drupal\ms_registration_config\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Employee Designation entities.
 *
 * @ingroup ms_registration_config
 */
interface EmpDesignationEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Employee Designation name.
   *
   * @return string
   *   Name of the Employee Designation.
   */
  public function getName();

  /**
   * Sets the Employee Designation name.
   *
   * @param string $name
   *   The Employee Designation name.
   *
   * @return \Drupal\ms_registration_config\Entity\EmpDesignationEntityInterface
   *   The called Employee Designation entity.
   */
  public function setName($name);

  /**
   * Gets the Employee Designation creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Employee Designation.
   */
  public function getCreatedTime();

  /**
   * Sets the Employee Designation creation timestamp.
   *
   * @param int $timestamp
   *   The Employee Designation creation timestamp.
   *
   * @return \Drupal\ms_registration_config\Entity\EmpDesignationEntityInterface
   *   The called Employee Designation entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Employee Designation revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Employee Designation revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\ms_registration_config\Entity\EmpDesignationEntityInterface
   *   The called Employee Designation entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Employee Designation revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Employee Designation revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\ms_registration_config\Entity\EmpDesignationEntityInterface
   *   The called Employee Designation entity.
   */
  public function setRevisionUserId($uid);

}
