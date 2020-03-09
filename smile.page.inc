<?php

/**
 * @file
 * Contains smile.page.inc.
 *
 * Page callback for title entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for title templates.
 *
 * Default template: smile.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_smile(array &$variables) {
  // Fetch SmileEntity Entity Object.
  $smile = $variables['elements']['#smile'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
