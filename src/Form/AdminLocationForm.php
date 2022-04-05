<?php

namespace Drupal\admin_config_form\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Config Form for Admin Location Form.
 */
class AdminLocationForm extends ConfigFormBase {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Construct configuration form for home page.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritDoc}
   */
  protected function getEditableConfigNames() {
    return ['admin_config_form.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'admin_config_form';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Get the parent build form object.
    $form = parent::buildForm($form, $form_state);
    // Getting the configuration of the admin config form fr the default values.
    $config = $this->configFactory->getEditable('admin_config_form.settings');

    // Form structure.
    $form['#tree'] = TRUE;
    $form['admin_location_block'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Location Block Configuration'),
      '#group' => 'location_block',
    ];
    $form['admin_location_block']['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country Name'),
      '#description' => $this->t('The country name you want to set for the location block.'),
      '#default_value' => $config->get('location_block.country'),
    ];
    $form['admin_location_block']['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City Name'),
      '#description' => $this->t('The city name you want to set for the location block.'),
      '#default_value' => $config->get('location_block.city'),
    ];

    $form['admin_location_block']['timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Timezones'),
      '#description' => $this->t('Select the timezone.'),
      '#options' => $this->getTimezoneList(),
      '#default_value' => $config->get('location_block.timezone'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->configFactory->getEditable('admin_config_form.settings');
    $config->clear('location_block');
    $config->set(
      'location_block.country',
      $form_state->getValue([
        'admin_location_block',
        'country',
      ]),
    );
    $config->set(
      'location_block.city',
      $form_state->getValue([
        'admin_location_block',
        'city',
      ]),
    );
    $config->set(
      'location_block.timezone',
      $form_state->getValue([
        'admin_location_block',
        'timezone',
      ]),
    );

    $config->save();
    return parent::submitForm($form, $form_state);
  }

  /**
   * Get the timezone list.
   *
   * @return array
   *   The timezone list.
   */
  private function getTimezoneList() {
    return [
      '' => '--none--',
      'America/Chicago' => 'America/Chicago',
      'America/New_York' => 'America/New_York',
      'Asia/Tokyo' => 'Asia/Tokyo',
      'Asia/Dubai' => 'Asia/Dubai',
      'Asia/Kolkata' => 'Asia/Kolkata',
      'Europe/Amsterdam' => 'Europe/Amsterdam',
      'Europe/Oslo' => 'Europe/Oslo',
      'Europe/London' => 'Europe/London',
    ];
  }

}
