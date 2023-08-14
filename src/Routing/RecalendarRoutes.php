<?php

namespace Drupal\recalendar\Routing;

use Drupal\recalendar\Controller\RecalendarController;
use Symfony\Component\Routing\Route;

/**
 * Provides route for recalendar that reflects admin-configured path alias.
 */
class RecalendarRoutes {

  /**
   * Build the routes.
   *
   * @return array
   *   The routes.
   */
  public function buildRoutes(): array {
    // Get the currently configured Recalendar alias.
    $config = \Drupal::config('recalendar.recalendarsettings');
    $setting = $config->get('recalendar_alias');

    // If recalendar alias hasn't been set.
    $alias = $setting === '' ? 'recalendar' : $setting;

    return [
      'recalendar.events' => new Route(
        path: $alias,
        defaults: [
          '_controller' => RecalendarController::class . '::events',
          '_title' => $alias,
        ],
        requirements: [
          '_permission'  => 'access content',
        ]
      )
    ];
  }

}
