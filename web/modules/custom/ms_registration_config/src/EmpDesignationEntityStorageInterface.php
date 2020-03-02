<?php

namespace Drupal\ms_registration_config;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\ms_registration_config\Entity\EmpDesignationEntityInterface;

/**
 * Defines the storage handler class for Employee Designation entities.
 *
 * This extends the base storage class, adding required special handling for
 * Employee Designation entities.
 *
 * @ingroup ms_registration_config
 */
interface EmpDesignationEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Employee Designation revision IDs for a specific Employee Designation.
   *
   * @param \Drupal\ms_registration_config\Entity\EmpDesignationEntityInterface $entity
   *   The Employee Designation entity.
   *
   * @return int[]
   *   Employee Designation revision IDs (in ascending order).
   */
  public function revisionIds(EmpDesignationEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Employee Designation author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Employee Designation revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\ms_registration_config\Entity\EmpDesignationEntityInterface $entity
   *   The Employee Designation entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(EmpDesignationEntityInterface $entity);

  /**
   * Unsets the language for all Employee Designation with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
