<?php

namespace Drupal\recalendar\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\taxonomy\Entity\Term;

/**
 * Provides route responses for the Recalendar module.
 */
class RecalendarController extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function events() {

    // Get options from Event Type taxonomy vocabulary.
    $type_query = \Drupal::entityQuery('taxonomy_term');
    $type_query->condition('vid', "rec_event_type");
    $type_tids = $type_query->execute();

    // Create an array in which to store our term names.
    $type_term_names = [];
    foreach ($type_tids as $type_tid) {

      // Loads taxonomy term using tid.
      $type_term = Term::load($type_tid);

      // We ultimately need option value to equal term tid, but Drupal will
      // convert any integer we provide as an option into a sequential integer
      // starting with 0. So we make it a string by prefixing it with term,
      // which we strip on page load using jQuery replace.
      $type_filter_key = 'term' . $type_tid;

      // Gets taxonomy term name.
      $type_filter_name = $type_term->getName();

      // Add name to array.
      $type_term_names[$type_filter_key] = $type_filter_name;
    }
    // Add All option to start of array.
    array_unshift($type_term_names, "All Events");

    $element = [
      // Select list for js filtering of events by type.
      $form['event_type'] = [
        '#type' => 'select',
        '#prefix' => '<div class="grid-x grid-margin-x calendar-filters"><span class="filters-wrapper">',
        '#multiple' => FALSE,
        '#options' => $type_term_names,
        '#attributes' => [
          'id' => 'event_type',
          'class' => [
            'cell',
            'small-12',
            'medium-4',
          ],
        ],
      ],

      // Select list for js filtering of events by month and year.
      // JS will provide the options.
      $form['event_date'] = [
        '#type' => 'select',
        '#multiple' => FALSE,
        '#attributes' => [
          'id' => 'event_date',
          'class' => [
            'cell',
            'small-12',
            'medium-4',
          ],
        ],
        '#suffix' => '</span></div>',
      ],
      $form['meeting_calendar'] = [
        '#markup' => '<div id="calendar">&nbsp;</div>',
      ],
    ];

    return $element;
  }

}
