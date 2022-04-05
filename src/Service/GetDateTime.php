<?php

namespace Drupal\admin_config_form\Service;

use Drupal\Core\Datetime\DrupalDateTime;

/**
 * The Class for the service to get date and time.
 */
class GetDateTime {

  /**
   * Constructor of the class.
   */
  public function __construct() {}

  /**
   * Get the current Date and time from the timezone.
   *
   * @param string $timezone
   *   The timezone.
   *
   * @return dateTime
   *   The date and time for the timezone.
   */
  public function getDateTime($timezone) {
    if (!is_null($timezone)) {
      $date_time = new DrupalDateTime($timezone);
      $timestamp = $date_time->getTimestamp();
      return date('jS M Y - g:i a', $timestamp);
    }
    else {
      return NULL;
    }
  }

}
