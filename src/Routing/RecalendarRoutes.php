<?php

namespace Drupal\recalendar\Routing;
use Symfony\Component\Routing\Route;

/**
 * Provides route for recalendar that reflects admin-configured path alias (/admin/config/recalendar).
 */
class RecalendarRoutes {

  /**
   * {@inheritdoc}
   */
  public function routes() {

    $routes = [];

    // Get the currently configured Recalendar alias.
    $config = \Drupal::config('recalendar.recalendarsettings');
    $setting = $config->get('recalendar_alias');

    // If recalendar alias hasn't been set.
    if ($setting === '') {
        $alias = 'recalendar';
    }
    else {
        $alias = $setting;
    }
    $routes = [];

    // Returns an array of Route objects.
    $routes['recalendar.events'] = new Route(

      // Path to attach this route to:
      $alias,

      // Route defaults:
      [
        '_controller' => '\Drupal\recalendar\Controller\RecalendarController::Events',
        '_title' => $alias
      ],

      // Route requirements:
      [
        '_permission'  => 'access content',
      ]
    );

    return $routes;
  }

}