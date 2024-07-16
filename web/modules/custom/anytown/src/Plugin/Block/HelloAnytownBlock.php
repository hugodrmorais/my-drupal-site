<?php

declare(strict_types=1);

namespace Drupal\anytown\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a hello world block block.
 *
 * @Block(
 *   id = "anytown_block",
 *   admin_label = @Translation("Hello Anytown Block"),
 *   category = @Translation("Custom"),
 * )
 */
final class HelloAnytownBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $build['content'] = [
      '#markup' => $this->t('Hello, Anytown'),
    ];
    return $build;
  }

}
