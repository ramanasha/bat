<?php

/**
 * @file
 * Class AbstractEvent
 */

namespace Roomify\Bat\Event;

use Roomify\Bat\Event\EventInterface;
use Roomify\Bat\Store\Store;

abstract class AbstractEvent implements EventInterface {

  const BAT_DAY = 'bat_day';
  const BAT_HOUR = 'bat_hour';
  const BAT_MINUTE = 'bat_minute';
  const BAT_HOURLY = 'bat_hourly';
  const BAT_DAILY = 'bat_daily';

  /**
   * The booking unit the event is relevant to
   * @var int
   */
  protected $unit_id;

  /**
   * The unit the event is relevant to
   */
  protected $unit;

  /**
   * The start date for the event.
   *
   * @var \DateTime
   */
  protected $start_date;

  /**
   * The end date for the event.
   *
   * @var \DateTime
   */
  protected $end_date;

  /**
   * The value associated with this event.
   * This can represent an availability state or a pricing value
   *
   * @var int
   */
  protected $value;

  /**
   * Returns the value.
   *
   * @return int
   */
  public function getValue() {
    return $this->value;
  }

  /**
   * Set the value.
   *
   * @param int $value
   */
  public function setValue($value) {
    $this->value = $value;
  }

  /**
   * Returns the unit id.
   *
   * @return int
   */
  public function getUnitId() {
    return $this->unit_id;
  }

  /**
   * Set the unit id.
   *
   * @param int $unit_id
   */
  public function setUnitId($unit_id) {
    $this->unit_id = $unit_id;
  }

  /**
   * Returns the start date.
   *
   * @return DateTime
   */
  public function getStartDate() {
    return clone($this->start_date);
  }

  /**
   * Utility function to always give us a standard format for viewing the start date.
   * @return mixed
   */
  public function startDateToString($format = 'Y-m-d H:i') {
    return $this->start_date->format($format);
  }

  /**
   * Set the start date.
   *
   * @param DateTime $start_date
   */
  public function setStartDate(\DateTime $start_date) {
    $this->start_date = clone($start_date);
  }

  /**
   * Returns the end date.
   *
   * @return DateTime
   */
  public function getEndDate() {
    return clone($this->end_date);
  }

  /**
   * Utility function to always give us a standard format for viewing the end date.
   * @return mixed
   */
  public function endDateToString($format = 'Y-m-d H:i') {
    return $this->end_date->format($format);
  }

  /**
   * Set the end date.
   *
   * @param DateTime $end_date
   */
  public function setEndDate(\DateTime $end_date) {
    $this->end_date = clone($end_date);
  }

  /**
   * {@inheritdoc}
   */
  public function startDay($format = 'j') {
    return $this->start_date->format($format);
  }

  /**
   * {@inheritdoc}
   */
  public function endDay($format = 'j') {
    return $this->end_date->format($format);
  }

  /**
   * {@inheritdoc}
   */
  public function startMonth($format = 'n') {
    return $this->start_date->format($format);
  }

  /**
   * {@inheritdoc}
   */
  public function endMonth($format = 'n') {
    return $this->end_date->format($format);
  }

  /**
   *{@inheritdoc)
   */
  public function endMonthDate(\DateTime $date) {
    // The time is added so that the end date is included
    $date_format = $date->format('Y-n-t 23:59:59');
    return new \DateTime($date_format);
  }

  /**
   * {@inheritdoc}
   */
  public function startYear($format = 'Y') {
    return $this->start_date->format($format);
  }

  /**
   * {@inheritdoc}
   */
  public function endYear($format = 'Y') {
    return $this->end_date->format($format);
  }

  /**
   * {@inheritdoc}
   */
  public function startWeek($format = 'W') {
    return $this->start_date->format($format);
  }

  /**
   * {@inheritdoc}
   */
  public function endWeek($format = 'W') {
    return $this->end_date->format($format);
  }

  /**
   * {@inheritdoc}
   */
  public function startHour($format = 'G') {
    return $this->start_date->format($format);
  }

  /**
   * {@inheritdoc}
   */
  public function endHour($format = 'G') {
    return $this->end_date->format($format);
  }

  /**
   * {@inheritdoc}
   */
  public function startMinute($format = 'i') {
    return $this->start_date->format($format);
  }

  /**
   * {@inheritdoc}
   */
  public function endMinute($format = 'i') {
    return $this->end_date->format($format);
  }

  /**
   * {@inheritdoc}
   */
  public function isFirstMonth($date) {
    if ($date->format("n") == $this->startMonth() && $date->format("Y") == $this->startYear()) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function isLastMonth($date) {
    if ($date->format("n") == $this->endMonth() && $date->format("Y") == $this->endYear()) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function isFirstDay($date) {
    if (($date->format('j') == $this->startDay()) && ($this->isFirstMonth($date))) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function isFirstHour($date) {
    if ($date->format('G') == $this->startHour() && $this->isFirstDay($date)) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function isSameYear() {
    if ($this->startYear() == $this->endYear()) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function isSameMonth() {
    if (($this->startMonth() == $this->endMonth()) && $this->isSameYear()) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function isSameDay() {
    if (($this->startDay() == $this->endDay()) && $this->isSameMonth()) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function isSameHour() {
    if (($this->startHour() == $this->endHour()) && $this->isSameDay()) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function diff() {
    $interval = $this->start_date->diff($this->end_date);
    return $interval;
  }

  /**
   * Returns true if the event overlaps at all with the time period within
   * the start and end time.
   *
   * @param \DateTime $start
   * @param \DateTime $end
   * @return bool
   */
  public function overlaps(\DateTime $start, \DateTime $end) {
    $overlaps = FALSE;

    if ($this->dateIsEarlier($start) &&
      ($this->dateIsInRange($end) || $this->dateIsLater($end))) {
      $overlaps = TRUE;
    }
    elseif ($this->dateIsInRange($start) &&
      ($this->dateIsInRange($end) || $this->dateIsLater($end))) {
      $overlaps = TRUE;
    }

    return $overlaps;
  }

  /**
   * Checks if date supplied is in range of event
   *
   * @param \DateTime $date
   * @return bool
   */
  public function dateIsInRange(\DateTime $date) {
    $dateInRange = FALSE;

    $t1 = $this->start_date->getTimeStamp();
    $t2 = $this->end_date->getTimeStamp();

    $t3 = $date->getTimeStamp();

    if (($t3 >= $t1) && ($t3 <= $t2)) {
      $dateInRange = TRUE;
    }

    return $dateInRange;
  }

  /**
   * Checks if the date supplied starts earlier than our event
   * @param \DateTime $date
   * @return bool
   */
  public function dateIsEarlier(\DateTime $date) {
    $dateEarlier = FALSE;

    $t1 = $this->start_date->getTimeStamp();

    $t3 = $date->getTimeStamp();

    if ($t3 < $t1) {
      $dateEarlier = TRUE;
    }

    return $dateEarlier;
  }

  /**
   * Checks if the date supplied ends after our event ends
   * @param \DateTime $date
   * @return bool
   */
  public function dateIsLater(\DateTime $date) {
    $dateLater = FALSE;

    $t2 = $this->end_date->getTimeStamp();

    $t4 = $date->getTimestamp();

    if ($t2 < $t4) {
      $dateLater = TRUE;
    }

    return $dateLater;
  }

  /**
   * Checks if our event ends after the date supplied
   * @param \DateTime $date
   * @return bool
   */
  public function endsLater(\DateTime $date) {
    $later = FALSE;

    $t2 = $this->end_date->getTimeStamp();

    $t4 = $date->getTimestamp();

    if ($t2 > $t4) {
      $later = TRUE;
    }

    return $later;
  }

  /**
   * Checks if our event starts earlier than the date supplied
   * @param \DateTime $date
   * @return bool
   */
  public function startsEarlier(\DateTime $date) {
    $earlier = FALSE;

    $t1 = $this->start_date->getTimeStamp();

    $t3 = $date->getTimestamp();

    if ($t1 < $t3) {
      $earlier = TRUE;
    }

    return $earlier;
  }

  /**
   * Based on the start and end dates of the event it creates the appropriate granular events
   * and adds them to an array suitable for manipulating easily or storing in the database.
   *
   * @param array $itemized
   * @return array
   */
  public function createDayGranural($itemized = array()) {
    $interval = new \DateInterval('PT1M');

    $sy = $this->start_date->format('Y');
    $sm = $this->start_date->format('n');
    $sd = $this->start_date->format('j');

    $ey = $this->end_date->format('Y');
    $em = $this->end_date->format('n');
    $ed = $this->end_date->format('j');

    // Clone the dates otherwise changes will change the event itself
    $start_date = clone($this->start_date);
    $end_date = clone($this->end_date);

    if ($this->isSameDay()) {
      if (!($this->end_date->format('H:i') == '23:59')) {
        $period = new \DatePeriod($start_date, $interval, $end_date->add(new \DateInterval('PT1M')));
        $itemized_same_day = $this->createHourlyGranular($period, $start_date);
        $itemized[AbstractEvent::BAT_DAY][$sy][$sm]['d' . $sd] = -1;
        $itemized[AbstractEvent::BAT_HOUR][$sy][$sm]['d' . $sd] = $itemized_same_day[AbstractEvent::BAT_HOUR][$sy][$sm]['d' . $sd];
        $itemized[AbstractEvent::BAT_MINUTE][$sy][$sm]['d' . $sd] = $itemized_same_day[AbstractEvent::BAT_MINUTE][$sy][$sm]['d' . $sd];
      }
    }
    else {
      // Deal with the start day unless it starts on midnight precisely at which point the whole day is booked
      if (!($this->start_date->format('H:i') == '00:00')) {
        $start_period = new \DatePeriod($start_date, $interval, new \DateTime($start_date->format("Y-n-j 23:59:59")));
        $itemized_start = $this->createHourlyGranular($start_period, $start_date);
        $itemized[AbstractEvent::BAT_DAY][$sy][$sm]['d' . $sd] = -1;
        $itemized[AbstractEvent::BAT_HOUR][$sy][$sm]['d' . $sd] = $itemized_start[AbstractEvent::BAT_HOUR][$sy][$sm]['d' . $sd];
        $itemized[AbstractEvent::BAT_MINUTE][$sy][$sm]['d' . $sd] = $itemized_start[AbstractEvent::BAT_MINUTE][$sy][$sm]['d' . $sd];
      }
      else {
        // Just set an empty hour and minute
        $itemized[AbstractEvent::BAT_HOUR][$sy][$sm]['d' . $sd] = array();
        $itemized[AbstractEvent::BAT_MINUTE][$sy][$sm]['d' . $sd] = array();
      }

      // Deal with the end date unless it ends just before midnight at which point we don't need to go further
      if ($this->end_date->format('H:i') == '23:59' ) {
        $itemized[AbstractEvent::BAT_HOUR][$ey][$em]['d' . $ed] = array();
        $itemized[AbstractEvent::BAT_MINUTE][$ey][$em]['d' . $ed] = array();
      } else {
        $end_period = new \DatePeriod(new \DateTime($end_date->format("Y-n-j 00:00:00")), $interval, $end_date->add(new \DateInterval('PT1M')));
        $itemized_end = $this->createHourlyGranular($end_period, new \DateTime($end_date->format("Y-n-j 00:00:00")));
        $itemized[AbstractEvent::BAT_DAY][$ey][$em]['d' . $ed] = -1;
        $itemized[AbstractEvent::BAT_HOUR][$ey][$em]['d' . $ed] = $itemized_end[AbstractEvent::BAT_HOUR][$ey][$em]['d' . $ed];
        $itemized[AbstractEvent::BAT_MINUTE][$ey][$em]['d' . $ed] = $itemized_end[AbstractEvent::BAT_MINUTE][$ey][$em]['d' . $ed];
      }
    }

    return $itemized;
  }

  /**
   * Given a DatePeriod it transforms it in hours and minutes. Used to break the first and
   * last days of an event into more granular events.
   *
   * @param \DatePeriod $period
   * @return array
   */
  public function createHourlyGranular(\DatePeriod $period, \DateTime $period_start) {
    $itemized = array();

    $counter = (int)$period_start->format('i');
    $start_minute = $counter;
    foreach($period as $minute) {
      // Doing minutes so set the values in the minute array
      $itemized[AbstractEvent::BAT_MINUTE][$minute->format('Y')][$minute->format('n')]['d'. $minute->format('j')]['h'. $minute->format('G')]['m' .$minute->format('i')] = $this->getValue();
      // Let the hours know that it cannot determine availability
      $itemized[AbstractEvent::BAT_HOUR][$minute->format('Y')][$minute->format('n')]['d'. $minute->format('j')]['h'. $minute->format('G')] = -1;
      $counter++;

      if ($counter == 60 && $start_minute!==0) {
        // Not a real hour - leave as is and move on
        $counter = 0;
        $start_minute = 0;
      }
      elseif ($counter == 60 && $start_minute == 0) {
        // Did a real whole hour so initialize the hour
        $itemized[AbstractEvent::BAT_HOUR][$minute->format('Y')][$minute->format('n')]['d' . $minute->format('j')]['h' . $minute->format('G')] = $this->getValue();
        // We have a whole hour so get rid of the minute info
        unset($itemized[AbstractEvent::BAT_MINUTE][$minute->format('Y')][$minute->format('n')]['d'. $minute->format('j')]['h'. $minute->format('G')]);
        $counter = 0;
        $start_minute = 0;
      }
    }

    return $itemized;
  }

  /**
   * Transforms the event in a breakdown of days, hours and minutes with associated states.
   *
   * @return array
   */
  public function itemizeEvent($granularity = AbstractEvent::BAT_HOURLY) {
    // The largest interval we deal with are months (a row in the *_state/*_event tables)
    $interval = new \DateInterval('P1M');

    // Set the end date to the last day of the month so that we are sure to get that last month
    $adjusted_end_day = new \DateTime($this->end_date->format('Y-n-t'));

    $daterange = new \DatePeriod($this->start_date, $interval ,$adjusted_end_day);

    $itemized = array();

    // Cycle through each month
    foreach($daterange as $date) {

      $year = $date->format("Y");
      $dayinterval = new \DateInterval('P1D');

      // Handle the first month
      if ($this->isFirstMonth($date)) {
        // If we are in the same month the end date is the end date of the event
        if ($this->isSameMonth()) {
          $dayrange = new \DatePeriod($this->start_date, $dayinterval, new \DateTime($this->end_date->format("Y-n-j 23:59:59")));
        }
        else { // alternatively it is the last day of the start month
          $dayrange = new \DatePeriod($this->start_date, $dayinterval, $this->endMonthDate($this->start_date));
        }
        foreach ($dayrange as $day) {
          $itemized[AbstractEvent::BAT_DAY][$year][$day->format('n')]['d' . $day->format('j')] = $this->getValue();
        }
      }

      // Handle the last month (will be skipped if event is same month)
      elseif ($this->isLastMonth($date)) {
        $dayrange = new \DatePeriod(new \DateTime($date->format("Y-n-1")), $dayinterval, $this->end_date);
        foreach ($dayrange as $day) {
          $itemized[AbstractEvent::BAT_DAY][$year][$day->format('n')]['d' . $day->format('j')] = $this->getValue();
        }
      }

      // We are in an in-between month - just cycle through and set dates (time on end date set to ensure it is included)
      else {
        $dayrange = new \DatePeriod(new \DateTime($date->format("Y-n-1")), $dayinterval, new \DateTime($date->format("Y-n-t 23:59:59")));
        foreach ($dayrange as $day) {
          $itemized[AbstractEvent::BAT_DAY][$year][$day->format('n')]['d' . $day->format('j')] = $this->getValue();
        }
      }
    }

    if ($granularity == AbstractEvent::BAT_HOURLY) {
      // Add granural info in
      $itemized = $this->createDayGranural($itemized);
    }

    return $itemized;
  }

  /**
   * Saves an event to whatever Drupal tables are defined in the store array
   *
   * @param \ROomify\Bat\\Store\Store $store
   * @param string $granularity
   *
   * @throws \Exception
   * @throws \InvalidMergeQueryException
   */
  public function saveEvent(Store $store, $granularity = AbstractEvent::BAT_HOURLY) {
    return $store->storeEvent($this, $granularity);
  }

}
