<?php

namespace Drupal\anytown\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Anytown routes.
 */
class TempoController extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function content() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Bem-vindo à página de tempo!'),
    ];
  }

}
