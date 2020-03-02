<?php

/**
 * @file
 * Contains emp_designation_entity.page.inc.
 *
 * Page callback for Employee Designation entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Employee Designation templates.
 *
 * Default template: emp_designation_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_emp_designation_entity(array &$variables) {
  // Fetch EmpDesignationEntity Entity Object.
  $emp_designation_entity = $variables['elements']['#emp_designation_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
