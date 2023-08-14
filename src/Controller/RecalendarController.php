<?php

namespace Drupal\recalendar\Controller;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides route responses for the Recalendar module.
 */
class RecalendarController implements ContainerInjectionInterface {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static($container->get('entity_type.manager'));
  }

  /**
   * Constructor for RecalendarController
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   */
  public function __construct(
    private EntityTypeManagerInterface $entityTypeManager
  ) {}

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function events() {
    $storage = $this->entityTypeManager->getStorage('taxonomy_term');

    // Get options from Event Type taxonomy vocabulary.
    $type_query = $storage->getQuery()->accessCheck(TRUE);
    $type_query->condition('vid', "rec_event_type");
    $type_tids = $type_query->execute();

    // Create an array in which to store our term names.
    $type_term_names = [];
    foreach ($type_tids as $type_tid) {

      /** @var \Drupal\taxonomy\TermInterface */
      $type_term = $storage->load($type_tid);

      /*
       * We need option value to equal term tid, but Drupal will convert any
       * integer we provide as an option into a sequential integer starting with
       * 0. So we make it a string by prefixing it with term, which we strip on
       * page load using jQuery replace.
       */
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
