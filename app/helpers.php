<?php

/**
 * Get an array of days in a week.
 * 
 * @return array
 */
function getDaysOfWeek()
{
	return [
		'Sunday' => 'อาทิตย์',
		'Monday' => 'จันทร์',
		'Tuseday' => 'อังคาร',
		'Wednesday' => 'พุธ',
		'Thursday' => 'พฤหัสบดี',
		'Friday' => 'ศุกร์',
		'Saturday' => 'เสาร์',
	];
}

/**
 * Create an array of times in given range with any given interval.
 * @source: http://stackoverflow.com/a/21896310
 * 
 * @param  integer $lower  The start of range. (ie. 08:00 = 800)
 * @param  integer $upper  The end of range. (ie. 16:00 = 1600)
 * @param  integer $step   Interval between each time (in minutes).
 * @param  string  $format Preferred output time format.
 * @return array
 */
function hoursRange($lower = 800, $upper = 1600, $step = 30, $format = '') {
    $times = [];

    if (empty($format)) {
        $format = 'H:i';
    }

    // For better readability.
    $lower = ($lower/100) * 3600;
    $upper = ($upper/100) * 3600;
    $step  = $step * 60;

    foreach (range($lower, $upper, $step) as $increment) {
        $time = gmdate('H:i', $increment);
        list($hour, $minutes) = explode(':', $time);
        $date = new DateTime($hour . ':' . $minutes);
        // Convert the formatted time to make it easier to check for overlap time
        $times[(int) str_replace(':', '', $date->format($format))] = $date->format($format);
    }

    return $times;
}