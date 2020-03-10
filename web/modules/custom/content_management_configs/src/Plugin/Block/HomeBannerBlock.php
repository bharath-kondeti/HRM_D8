<?php

namespace Drupal\content_management_configs\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Home Banner' Block.
 *
 * @Block(
 *   id = "home_banner",
 *   admin_label = @Translation("Home Banner"),
 *   category = @Translation("Home Banner"),
 * )
 */
class HomeBannerBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => $this->t('Hello, World!'),
    ];
  }

}
