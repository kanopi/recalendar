<?php

namespace Drupal\recalendar\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * Provides endpoints for events and meetings data (consumed by calendar).
 */
class RecalendarEndpointController {

  /**
   * Returns a simple page from a static JSON file.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   Json data.
   */
  public function eventsEndpoint(): Response {
    $data = file_get_contents('public://data/data_export_events.json');
    $response = new Response($data);
    $response->headers->set('Content-type', 'application/json');

    return $response;
  }

}
