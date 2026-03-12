<?php
date_default_timezone_set('America/Los_Angeles');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");
error_log( "Hello, errors!" );


define('CHARSET', 'utf-8');

require 'libs/weather_warnings.php';

echo "<hr>";
// Set the location (e.g., city name or coordinates)
$location = 'Santa Paula, CA 93060, United States';

// Set the date and timeframe (start and end times)
$date = '2024-04-13';

get_warnings($location, $date, "11:30:00", "13:00:00");

echo "<hr>";
// Set the location (e.g., city name or coordinates)
$location = '93021';

// Set the date and timeframe (start and end times)
$date = '2024-07-12';

get_warnings($location, $date, "11:30:00", "13:00:00");

echo "<hr>";

// Set the location (e.g., city name or coordinates)
$location = '888 New Los Angeles Ave, Moorpark, CA 93021, USA';

// Set the date and timeframe (start and end times)
$date = '2024-07-04';

get_warnings($location, $date, "11:30:00", "14:00:00");



function get_warnings($location, $date, $start_time, $end_time )
{

    // format the dates
    $start = strtotime($date . $start_time);
    $end = strtotime($date . $end_time);
    

    $the_date = $date;
    
    $location = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '&apos;', $location);
    echo $location;
 
 
    echo "<br>";


    echo date('D, F d, Y h:i:s a', $start);
    echo " - ";
    echo date('h:i:s a', $end);
    echo "<hr>";
    
    
    

    $weather_warnings = new weather_warnings($location, $the_date);
    
//    var_dump($weather_warnings);
    

    echo $weather_warnings->get_warnings($start, $end);
    
    echo "<hr>";
    
}






