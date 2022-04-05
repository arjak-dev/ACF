<?php

namespace Drupal\admin_config_form\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\admin_config_form\Service\GetDateTime;

/**
 * Provides a Block For Showing the location and the time of the site.
 *
 * @Block(
 *   id = "location_block",
 *   admin_label = @Translation("Location Block")
 * )
 */
class LocationBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Date Time Service Object.
   *
   * @var \Drupal\admin_config_form\Service\GetDateTime
   */
  protected $dateTime;

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('admin_config_form.date_time_service'),
    );
  }

  /**
   * LocationBlock constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config Factory.
   * @param \Drupal\admin_config_form\Service\GetDateTime $date_time
   *   Data and time Service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory, GetDateTime $date_time) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
    $this->dateTime = $date_time;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->configFactory->get('admin_config_form.settings');

    $build = [
      'country' => $config->get('location_block.country'),
      'city' => $config->get('location_block.city'),
      'date_time' => $this->dateTime->getDateTime($config->get('location_block.timezone')),
    ];
    return [
      '#theme' => 'location-block',
      '#content' => $build,
      '#cache' => [
        'tags' => [
          'config:admin_config_form.settings',
        ],
        'max-age' => '60',
      ],
    ];
  }

}
