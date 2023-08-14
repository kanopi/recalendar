<?php

namespace Drupal\recalendar\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class recalendarsettings.
 *
 * @package Drupal\recalendar\Form
 */
class RecalendarSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return [
      'recalendar.recalendarsettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'recalendar_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config('recalendar.recalendarsettings');

    $form['recalendar_alias'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Recalendar Alias'),
      '#description' => $this->t('Enter the alias for your calendar (e.g., events). This will determine both the title and path of the calendar.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('recalendar_alias'),
      '#required' => FALSE,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    parent::submitForm($form, $form_state);

    $this->config('recalendar.recalendarsettings')
      ->set('recalendar_alias', $form_state->getValue('recalendar_alias'))
      ->save();
  }

}
