<?php

namespace AttendCheck\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TestController extends Controller
{
    protected $holidays = [
        //
    ];

    /**
     * This function generates a class periods based on the array that has
     * the day of the week, class's start time and end time. This will
     * return an array that has an exact date of each periods.
     */
    public function create()
    {
        $times = [
            ['day' => 'Monday', 'start_time' => '9:00', 'end_time' => '12:00'],
        ];

        $i = 0;
        $week = 1;

        // The day that class starts its first period.
        $startDate = Carbon::createFromFormat('d-m-Y', '5-12-2016');
        $weekStart = $startDate->startOfWeek();

        $periods = [];

        // 10 is a number of week for each semester.
        while ($week <= 10) {

            foreach ($times as $time) {
                // If the day is Monday, then just convert the start of the
                // week into dateTime string.
                if ($time['day'] == 'Monday') {
                    $periods[$i]['date'] = $weekStart->toDateTimeString();
                } else {
                    // If it's not, find the date and convert it.
                    $date = $weekStart->next($this->getDay($time['day']));
                    $periods[$i]['date'] = $date->toDateTimeString();
                }
                $periods[$i]['start_time'] = $time['start_time'];
                $periods[$i]['end_time'] = $time['end_time'];

                $i++;
            }
            // Do this for every week.
            $week++;

            // Reset the cursor back to start of week
            $weekStart = $weekStart->next()->startOfWeek();
        }

        return $periods;
    }

    public function getDay($day)
    {
        switch ($day) {
            case 'Monday':
                return 1;
            case 'Tuesday':
                return 2;
            case 'Wednesday':
                return 3;
            case 'Thursday':
                return 4;
            case 'Friday':
                return 5;
            case 'Saturnday':
                return 6;
            case 'Sunday':
                return 0;
        }
    }

    /**
     * Load the name from excel file and then randomly pick one person
     * from the list based on 'random' index. The lower the 'random'
     * index of the item is, the higher chance of it getting
     * picked. This will probably be convert to JS later.
     */
    public function testExcel()
    {
        // First, we load the excel file.
        $result = Excel::load('lists.xlsx')->get();
        $lists = [];

        $result->each(function ($cell) use (&$lists) {
            $lists[] = [
                'username'  => $cell->id,
                'title'     => $cell->title,
                'name'      => $cell->name,
                'lastname'  => $cell->lastname,
                'random'    => rand(0,10)
            ];
        });

        // And then sort it based on item's 'random' index.
        // 
        // Note: Actually, I want to use <=> operator here,
        // but that's PHP 7 only so...
        usort($lists, function ($item1, $item2) {
            if ($item1['random'] == $item2['random']) {
                return 0;
            }
            return ($item1['random'] > $item2['random']) ? 1 : -1;
        });

        // After that, we divide it down into 3 partions.
        // First partition will be consists of persons that never / rarely get picked.
        // Second partition will be consists of persons that get picked sometimes.
        // Last partition will be consists of persons that get picked often.
        $partitioned = $this->partition($lists, 3);

        // And then we will pick the random partition will the following probability:
        // First partition : 75%
        // Second partition : 20%
        // Last partition : 5%
        // Maybe I will put this in the config file or something.
        $pickedPartition = $this->randWithBias();

        // Finally, we pick the random item from that partition and return it.
        $pickedItem = array_rand($partitioned[$pickedPartition]);
        return $partitioned[$pickedPartition][$pickedItem];
    }

    /**
    * Split array into equal sized partitions
    * 
    * @param Array $list
    * @param int $p number of partitions
    * @return Array
    * @link http://www.php.net/manual/en/function.array-chunk.php#75022
    */
    public function partition(Array $array, $p)
    {
        $listlen = count($array);
        $partlen = floor($listlen / $p);
        $partrem = $listlen % $p;
        $partition = [];
        $mark = 0;

        for($px = 0; $px < $p; $px ++) {
            $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
            $partition[$px] = array_slice($array, $mark, $incr);
            $mark += $incr;
        }
        return $partition;
    }

    /**
    * Credit goes to: 
    * Brad from Stackoverflow <http://stackoverflow.com/users/362536/brad>
    * 
    * Utility function for getting random values with weighting.
    * Pass in an associative array, such as array('A'=>5, 'B'=>45, 'C'=>50)
    * An array like this means that "A" has a 5% chance of being selected, "B" 45%, and "C" 50%.
    * The return value is the array key, A, B, or C in this case.  Note that the values assigned
    * do not have to be percentages.  The values are simply relative to each other.  If one value
    * weight was 2, and the other weight of 1, the value with the weight of 2 has about a 66%
    * chance of being selected.  Also note that weights should be integers.
    * 
    * @param array $weightedValues
    * @return mixed an item that get selected
    */
    public function randWithBias()
    {
        $list = [0 => 75, 1 => 20, 2 => 5,];

        $rand = mt_rand(1, (int) array_sum($list));

        foreach ($list as $key => $value) {
            $rand -= $value;
            if ($rand <= 0) {
                return $key;
            }
        }
    }

}
