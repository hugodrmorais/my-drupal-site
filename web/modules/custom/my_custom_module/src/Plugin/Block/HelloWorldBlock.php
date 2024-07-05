<?php

declare(strict_types=1);

namespace Drupal\my_custom_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a hello world block block.
 *
 * @Block(
 *   id = "hello_world_block",
 *   admin_label = @Translation("Hello World Block"),
 *   category = @Translation("Custom"),
 * )
 */
final class HelloWorldBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $build['content'] = [
      '#markup' => $this->t('Hello, World!'),
    ];
    return $build;
  }

}
