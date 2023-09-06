<?php

// This file is loaded with HelperServiceProvider

use Carbon\Carbon;
use App\Models\User;
use Carbon\CarbonPeriod;
use Jenssegers\Date\Date;
use App\Models\Timezone;

if (!function_exists("getCarbonTodayForUser")) {
    function getCarbonTodayForUser()
    {
        $fromTimezone = !empty($_SESSION['timezone_name']) ? $_SESSION['timezone_name'] : 'UTC';
        $toTimezone = 'UTC';
        $today = Carbon::today($fromTimezone);
        $today->setTimezone($toTimezone);
        return $today;
    }
}

if (!function_exists("getCarbonTodayEndDateTimeForUser")) {
    function getCarbonTodayEndDateTimeForUser()
    {
        $fromTimezone = !empty($_SESSION['timezone_name']) ? $_SESSION['timezone_name'] : 'UTC';
        $toTimezone = 'UTC';
        $today = Carbon::today($fromTimezone)->addDays(1);
        $today->setTimezone($toTimezone);
        return $today;
    }
}

if (!function_exists("getCarbonNowForUser")) {
    function getCarbonNowForUser()
    {
        $fromTimezone = !empty($_SESSION['timezone_name']) ? $_SESSION['timezone_name'] : 'UTC';
        $toTimezone = 'UTC';
        $now = Carbon::now($fromTimezone);
        $now->setTimezone($toTimezone);
        return $now;
    }
}

if (!function_exists("carbonCreateFromFormatForUser")) {
    function carbonCreateFromFormatForUser($format, $datetime)
    {
        $fromTimezone = !empty($_SESSION['timezone_name']) ? $_SESSION['timezone_name'] : 'UTC';
        $toTimezone = 'UTC';
        $datetime = Carbon::createFromFormat($format, $datetime, $fromTimezone);
        $datetime->setTimezone($toTimezone);
        return $datetime;
    }
}

if (!function_exists("formatPercentage")) {

    function formatPercentage($value)
    {
        return number_format($value, 2);
    }
}

if (!function_exists("formatPercentageMax100")) {

	function formatPercentageMax100($value)
	{
		return round(formatPercentage(min(100, $value)));
	}
}

if (!function_exists("getContactStatsTotalCounts")) {

    function getContactStatsTotalCounts($contactStats)
    {
        return $statsTotalCountArr = [
            'MESSAGE_SENT' => $contactStats['MESSAGE_SENT'],
            'MESSAGE_ANSWERED' => $contactStats['MESSAGE_ANSWERED'],
            'ZOOM_INVITE_SENT' => $contactStats['ZOOM_INVITE_SENT'],
            'CONFIRMED_FOR_ZOOM' => $contactStats['CONFIRMED_FOR_ZOOM'],
            'ATTENDED_THE_ZOOM' => $contactStats['ATTENDED_THE_ZOOM'],
            'NEW_DISTRIBUTOR' => $contactStats['NEW_DISTRIBUTOR'],
            'NEW_CLIENT' => $contactStats['NEW_CLIENT'],
            'FOLLOWUP' => $contactStats['FOLLOWUP'],
            'NOT_INTERESTED' => $contactStats['NOT_INTERESTED'],
        ];

        $statsTotalCountArr = [
            'MESSAGE_SENT' => $contactStats['MESSAGE_SENT'] + $contactStats['MESSAGE_ANSWERED'] + $contactStats['ZOOM_INVITE_SENT'] + $contactStats['CONFIRMED_FOR_ZOOM'] + $contactStats['ATTENDED_THE_ZOOM'] + $contactStats['NEW_DISTRIBUTOR'] + $contactStats['NEW_CLIENT'] + $contactStats['FOLLOWUP'] + $contactStats['NOT_INTERESTED'],
            'MESSAGE_ANSWERED' => $contactStats['MESSAGE_ANSWERED'] + $contactStats['ZOOM_INVITE_SENT'] + $contactStats['CONFIRMED_FOR_ZOOM'] + $contactStats['ATTENDED_THE_ZOOM'] + $contactStats['NEW_DISTRIBUTOR'] + $contactStats['NEW_CLIENT'] + $contactStats['FOLLOWUP'] + $contactStats['NOT_INTERESTED'],
            'ZOOM_INVITE_SENT' => $contactStats['ZOOM_INVITE_SENT'] + $contactStats['CONFIRMED_FOR_ZOOM'] + $contactStats['ATTENDED_THE_ZOOM'] + $contactStats['NEW_DISTRIBUTOR'] + $contactStats['NEW_CLIENT'] + $contactStats['FOLLOWUP'] + $contactStats['NOT_INTERESTED'],
            'CONFIRMED_FOR_ZOOM' => $contactStats['CONFIRMED_FOR_ZOOM'] + $contactStats['ATTENDED_THE_ZOOM'] + $contactStats['NEW_DISTRIBUTOR'] + $contactStats['NEW_CLIENT'] + $contactStats['FOLLOWUP'] + $contactStats['NOT_INTERESTED'],
            'ATTENDED_THE_ZOOM' => $contactStats['ATTENDED_THE_ZOOM'] + $contactStats['NEW_DISTRIBUTOR'] + $contactStats['NEW_CLIENT'] + $contactStats['FOLLOWUP'] + $contactStats['NOT_INTERESTED'],
            'NEW_DISTRIBUTOR' => $contactStats['NEW_DISTRIBUTOR'],
            'NEW_CLIENT' => $contactStats['NEW_CLIENT'],
            'FOLLOWUP' => $contactStats['FOLLOWUP'],
            'NOT_INTERESTED' => $contactStats['NOT_INTERESTED'],
        ];

    }
}

if (!function_exists("getWeeksOfMonth")) {

    function getWeeksOfMonth($now)
    {
        //format string
        $format = 'Y-m-d';

        //if you want to record time as well, then replace today() with now()
        //and remove startOfDay()
        $date = $now->copy()->firstOfMonth()->startOfDay();
        $eom = $now->copy()->endOfMonth()->startOfDay();

        $dates = [];

        for ($i = 1; $date->lte($eom); $i++) {

            //record start date
            $startDate = $date->copy();

            //loop to end of the week while not crossing the last date of month
            while ($date->dayOfWeek != Carbon::SUNDAY && $date->lte($eom)) {
                $date->addDay();
            }

            $dates[$i] = array(
                'start' => $startDate->format($format),
                'end' => $date->format($format),
            );
            $date->addDay();
        }
        return $dates;
    }
}

if (!function_exists("getAgeGroups")) {

    function getAgeGroups()
    {
        return [
            '18 - 24' => array('count' => 0),
            '25 - 34' => array('count' => 0),
            '35 - 44' => array('count' => 0),
            '45 - 54' => array('count' => 0),
        ];

    }
}

if (!function_exists("getDatesFromRange")) {
    function getDatesFromRange($date_time_from, $date_time_to)
    {

        // cut hours, because not getting last day when hours of time to is less than hours of time_from
        // see while loop
        $start = Carbon::createFromFormat('Y-m-d', substr($date_time_from, 0, 10));
        $end = Carbon::createFromFormat('Y-m-d', substr($date_time_to, 0, 10));

        $dates = [];

        while ($start->lte($end)) {

            $dates[] = $start->copy()->format('Y-m-d');

            $start->addDay();
        }

        return $dates;
    }
}

if (!function_exists("getDateRange")) {
    function getDateRange($startFrom,$endTo)
    {
        $dates = [];
        $period = CarbonPeriod::create(convertDateFormatWithTimezone($startFrom, 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM'), convertDateFormatWithTimezone($endTo, 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM'));
        // Iterate over the period
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d H:i:s') . "<br>";
        }
        $dates[] = convertDateFormatWithTimezone($endTo, 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');

        return $dates;
    }
}

if (!function_exists("getStartEndDate")) {
    function getStartEndDate($date)
    {

        $data['start'] = convertDateFormatWithTimezone(Carbon::parse($date), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');
        $data['end'] = convertDateFormatWithTimezone(Carbon::parse($date)->addDays(1), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'FRONT-TO-CRM');
        return $data;
    }
}

if (!function_exists("getDatesFromRangeForWeek")) {
    function getDatesFromRangeForWeek($date_time_from, $date_time_to)
    {

        // cut hours, because not getting last day when hours of time to is less than hours of time_from
        // see while loop
        $start = Carbon::createFromFormat('Y-m-d', substr($date_time_to, 0, 10));
        $end = Carbon::createFromFormat('Y-m-d', substr($date_time_from, 0, 10));

        $dates = [];

        while ($start->lte($end)) {

            $dates[] = $start->copy()->format('Y-m-d');

            $start->addDay();
        }

        return $dates;
    }
}

if (!function_exists("getDownlineBoards")) {
    function getDownlineBoards($downlineIds)
    {
        $users = User::whereRaw('id IN ('.getDownlinesStr(implode(',', $downlineIds)).')')->get();
        $board = [];
        foreach ($users as $user) {
            $board[] = $user->board->id ?? null;
        }
        return $board;
    }
}

if (!function_exists("turnUrlIntoHyperlink")) {
    function turnUrlIntoHyperlink($string)
    {
        //The Regular Expression filter
        $reg_exUrl = "/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'\'.,<>?«»'']))/";

        $replace = '';
        // Check if there is a url in the text
        if(preg_match_all($reg_exUrl, $string, $url)) {

            // Loop through all matches
            foreach($url[0] as $key => $newLinks){

                if(strstr( $newLinks, ":" ) === false){
                    $url = 'https://'.$newLinks;
                }else{
                    $url = $newLinks;
                }

                // Create Search and Replace strings
                $replace .= '<a href="'.$url.'" title="'.$url.'" target="_blank"  class="text-primary">'.$url.'</a>,';
                $newLinks = '/'.preg_quote($newLinks, '/').'/';
                $string = preg_replace($newLinks, '{'.$key.'}', $string, 1);

            }
            $arr_replace = explode(',', $replace);
            foreach ($arr_replace as $key => $link) {
                $string = str_replace('{'.$key.'}', $link, $string);
            }
        }
        //Return result
        return $string;
    }
}

if (!function_exists("isCheckInMessageLinkOrNot")) {
    function isCheckInMessageLinkOrNot($string) {
        //The Regular Expression filter
        $reg_exUrl = "/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:'\'.,<>?«»'']))/";

        $replace = '';
        // Check if there is a url in the text
        if(preg_match_all($reg_exUrl, $string, $url)) {
            return true;
        } else {
            return false;
        }
    }
}


if (!function_exists("checkIfEventIsPastCurrentTime")) {
    function checkIfEventIsPastCurrentTime($event)
    {
        if(isset($event->meeting_date) && !empty($event->meeting_date)){
            $to = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $event->meeting_date . ' ' . $event->meeting_time);
            $from = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', getCarbonNowForUser());

            if (strtotime($from) > strtotime($to)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
        
    }
}

if (!function_exists("getTodayDayForUser")) {
    function getTodayDayForUser()
    {
        return strtolower(date('l', strtotime( convertDateFormatWithTimezone(getCarbonTodayForUser()->format('Y-m-d H:i:s'), 'Y-m-d H:i:s', 'Y-m-d H:i:s', 'CRM-TO-FRONT')) ));
    }
}

if (!function_exists("getBadgeIndicatorClass")) {
    function getBadgeIndicatorClass($percentage = 100, $range = '100-85', $invitationText = "on your invitation") {
	    $rangeArr = explode('-', $range);
         if($percentage >= $rangeArr[0]) {
            return [
            	'class' => 'blue-up',
	            'tooltip' => __('Congratulation! You are above average!').' 🥳',
	            'color' => 'blue'
            ];
         }elseif ($percentage >= $rangeArr[1]) {
            return [
	            'class' => 'green-dot',
	            'tooltip' => __('Great! You are average!').' 😎',
	            'color' => 'green'
            ];
         }else {
            if($invitationText == ContactBoardStatus::NEW_DISTRIBUTOR) {
                return [
                    'class' => 'red-down',
                    'tooltip' => __('below average').' 😕 </br>'.__('The average is').' '. $rangeArr[1].'%'.'</br><a class="tooltip-down" target="_blank" href="https://calendly.com/thomaspoulin/coaching-rankup">'. __("Please contact your coach to work on your closing rate").'</a>',
                    'color' => 'red'
                ];
            } else if($invitationText == ContactBoardStatus::ATTENDED_THE_ZOOM){
                return [
                    'class' => 'red-down',
                    'tooltip' => __('below average').' 😕 </br><a class="tooltip-down" target="_blank" href="https://calendly.com/thomaspoulin/coaching-rankup">'. __("Please contact your coach to work on your attendance rate").'</a> <br> '.__('The average is').' '. $rangeArr[1].'%',
                    'color' => 'red'
                ]; 
            } else {
                return [
                    'class' => 'red-down',
                    'tooltip' => __('below average').' 😕 </br><a class="tooltip-down" target="_blank" href="https://calendly.com/thomaspoulin/coaching-rankup">'. __("Please contact your coach to work ".$invitationText).'</a> <br> '.__('The average is').' '. $rangeArr[1].'%',
                    'color' => 'red'
                ]; 
            }
         }
    }
}

if (!function_exists("dateDiffInDays")) {
	function dateDiffInDays( $date1, $date2 ) {
		$diff = strtotime( $date2 ) - strtotime( $date1 );
		return abs( round( $diff / 86400 ) );
	}
}

if (!function_exists("getTotalDaysOfCurrentMonth")) {
    function getTotalDaysOfCurrentMonth()
    {
        $timezone = !empty($_SESSION['timezone_name']) ? $_SESSION['timezone_name'] : 'UTC';
        $now = Carbon::now($timezone);
        return $now->daysInMonth;
    }
}


if (!function_exists("graphToImage")) {
    function graphToImage($title, $count, $label) {
        $title = json_encode($title);
        $count = json_encode($count);
        $label = json_encode($label);

        $chart = '{
            "type": "line",
            "data": {
                "labels":'.$title.',
                "datasets": [{
                    "label": '.$label.',
                    "data": '.$count.',
                    fill: false,
                    lineTension: 0.4,
                    radius: 5,
                    borderColor: [
                        "#56B2FF",
                    ],
                    color: [
                        "#56B2FF",
                    ],
                    backgroundColor: [
                        "#56B2FF",
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function(value) {if (value % 1 === 0) {return value;}}
                        }
                    }]
                }
            },
        }';
          
        $encoded = urlencode($chart);

        $imageUrl = "https://quickchart.io/chart?c=" . $encoded;

        return $imageUrl;
    }
}


if (!function_exists("getTodayDateRange")) {
    function getTodayDateRange($startDateTime, $endDateTime, $addHour = 1) {
        $returnArr = [];
        $startTime    = strtotime($startDateTime);
        $endTime      = strtotime($endDateTime);
        $returnArr[] = $startDateTime->format('Y-m-d H:i:s');
        while (strtotime($startDateTime) < strtotime($endDateTime)) {
            $returnArr[] = $startDateTime->addHours($addHour)->format('Y-m-d H:i:s');
        }
        return $returnArr;
    }   
}

if (!function_exists("getEncrypted")) {
    /**
     * Encryption Id
     * @param int $id
     *
     * @return string
     */
    function getEncrypted($id) {
        $encrypted_string=openssl_encrypt($id,config('services.encryption.type'),config('services.encryption.secret'));
        return base64_encode($encrypted_string);
    }
}

if (!function_exists("getDecrypted")) {
    /**
     * Decryption Id
     * @param int $id
     *
     * @return string
     */
    function getDecrypted($id) {
        $string=openssl_decrypt(base64_decode($id),config('services.encryption.type'),config('services.encryption.secret'));
        return $string;
    }
}


if (!function_exists("getDayName")) {
    function getDayName($val) {
        $arr = [];
        switch (trim($val)) {
            case "S":
                $arr = [
                    "en" => "S",
                    "fr" => "D",
                    "es" => "D",
                    "cs" => "N"
                ];
                break;
            case "M":
                $arr = [
                    "en" => "M",
                    "fr" => "L",
                    "es" => "L",
                    "cs" => "Po"
                ];
                break;
            case "T":
                $arr = [
                    "en" => "T",
                    "fr" => "M",
                    "es" => "Ma",
                    "cs" => "Ú"
                ];
                break;
            case "W":
                $arr = [
                    "en" => "W",
                    "fr" => "M",
                    "es" => "Mi",
                    "cs" => "St"
                ];
                break;
            case "t":
                $arr = [
                    "en" => "T",
                    "fr" => "J",
                    "es" => "J",
                    "cs" => "Č"
                ];
                break;
            case "F":
                $arr = [
                    "en" => "F",
                    "fr" => "V",
                    "es" => "V",
                    "cs" => "Pá"
                ];
                break;
            case "s":
                $arr = [
                    "en" => "S",
                    "fr" => "S",
                    "es" => "S",
                    "cs" => "So"
                ];
                break;
            default:
                $arr = [
                    "en" => "S",
                    "fr" => "S",
                    "es" => "S",
                    "cs" => "S"
                ];
                break;
        }
        return $arr;
    }
}

if (!function_exists("dbConnections")) {
    function dbConnections() {
        $connections = [
            'rankup' => 'rankup', 
            'ibuumerang_rankup' => 'ibuumerang_rankup'
        ];

        return $connections;
    }
}

/** Get the video id from youtube video link */
if (!function_exists("getYoutubeVideoId")) {
    function getYoutubeVideoId($url) {

        // Regular expression to match the video ID from various YouTube URL formats
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/';
        
        if (preg_match($pattern, $url, $matches)) {
            $videoId = $matches[1]; // Extracted video ID
            return $videoId;
        } else {
            return NULL;
        }
    }
}

if (!function_exists("timezoneOption")) {
    function timezoneOption() {
        $options = ['EDT' => 'Quebec', 'CST' => 'Mexico', 'CET' => 'Czech republic'];
        return $options;
    }
}

if (!function_exists("getDownlinesStr")) {
    function getDownlinesStr($data) {
        return (empty($data) ? "''" : $data);
    }
}

if (!function_exists("convertDateFormatWithTimezone")) {

    /**
     * @param $date string - datetime
     * @param $from string - From Format
     * @param $to string - To Format
     * @param $type string - CRM-TO-FRONT / FRONT-TO-CRM
     *
     * @return false|string
     */
    function convertDateFormatWithTimezone($date, $from, $to, $type = 'CRM-TO-FRONT', $timzone = null)
    {
        Date::setLocale(app()->getLocale());
        if (empty($date)) {
            return '';
        }
        if ($type == 'CRM-TO-FRONT') {
            $fromTimezone = 'UTC';
            $toTimezone = !empty($_SESSION['timezone_name']) ? $_SESSION['timezone_name'] : 'UTC';
        } else {
            if($timzone != null) {
                $fromTimezone = $timzone;
            } else {
                $fromTimezone = !empty($_SESSION['timezone_name']) ? $_SESSION['timezone_name'] : 'UTC';
            }
            $toTimezone = 'UTC';
        }
        $dateObj = Date::createFromFormat($from, $date, new DateTimeZone($fromTimezone));
        $dateObj->setTimezone(new DateTimeZone($toTimezone));
        return $dateObj->format($to);
    }
}

if (!function_exists("getTimeZoneiList")) {
    function getTimeZoneiList() {
        $timezones = Timezone::select('code','name')->pluck('name','code')->toArray();
        return $timezones;
    }
}
