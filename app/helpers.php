<?php

function day($value)
{
    $days = ['','อาทิตย์','จันทร์','อังคาร','พุธ','พฤหัสบดี','ศุกร์','เสาร์'];
    return $days[$value];
}

function getSemester($semester)
{
    switch ($semester) 
    {
        case '1':
            return 'ภาคต้น';
        case '2':
            return 'ภาคปลาย';
        case '3':
            return 'ภาคฤดูร้อน';
    }
}

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

function semesterValue($value)
{
    switch ($value) {
        case '1':
            return 'ภาคต้น';
        case '2':
            return 'ภาคปลาย';
        case '3':
            return 'ภาคฤดูร้อน';
    }
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

function convertThaiDateToYmd($string)
{
    $months = [
        '', "ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค."
    ];

    $stringArray = explode(' ', $string);

    $Y = $stringArray[2];
    $m = str_pad(array_search($stringArray[1], $months), 2, "0", STR_PAD_LEFT);
    $d = str_pad($stringArray[0], 2, "0", STR_PAD_LEFT);

    return "$Y-$m-$d";
}

function checkCurrentTimeInrange($start_date, $end_date, $input_time)
{
    // Convert to timestamp
    $start_ts = strtotime($start_date);
    $end_ts = strtotime($end_date);
    $user_ts = strtotime($input_time);

    // Check that user date is between start & end
    return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
}

if (! function_exists('current_user')) {
    /**
     * Get the current user instance.
     * 
     * @return Mixed
     */
    function current_user()
    {
        return \Illuminate\Support\Facades\Auth::user();
    }
}
