<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * This example demonstrates how the Ics-Parser should be used.
 *
 * PHP Version 5
 *
 * @category Example
 * @package  Ics-parser
 * @author   Martin Thoma <info@martin-thoma.de>
 * @license  http://www.opensource.org/licenses/mit-license.php  MIT License
 * @version  SVN: <svn_id>
 * @link     http://code.google.com/p/ics-parser/
 * @example  $ical = new ical('MyCal.ics');
 *           print_r( $ical->get_event_array() );
 */

$notice = "";

$display_week = 0;

$nineHours   = 60 * 60 * 9;
$eightHours  = 60 * 60 * 8;
$sevenHours  = 60 * 60 * 7;
$sixHours    = 60 * 60 * 6;

$offsetHours = $eightHours;

if(isset($_GET['week'])) {
    $display_week = $_GET['week'];
}

date_default_timezone_set('America/Los_Angeles');

$fields =  array( "Cat", "Status", "Featured", "Image", "Signup" );

require 'libs/venues.php';
require 'libs/utils.php';
require 'libs/cal.php';
require 'libs/field_handler.php';

require 'class.iCalReader.php';

$my_ical = new ICal('https://calendar.google.com/calendar/ical/gbennett%40tsgdev.com/private-61ab22cc7e10980c9bd2cab69790fd1a/basic.ics');
$perform_ical = new ICal('https://calendar.google.com/calendar/ical/c_8oqt9e7bms1sefskr0fl01r7tg%40group.calendar.google.com/public/basic.ics');

$start_str = "";
if($display_week != 0)
{
    $start_str = "+" . ($display_week * 7) . " days";
}    

$end_str = "+" . (($display_week * 7) + 7) . " days";


$now      = new DateTime($start_str);
$nextWeek = new DateTime($end_str);

$start = $now->getTimestamp();
$end   = $nextWeek->getTimestamp();

if(date('I'))
{
    $offsetHours = $sevenHours;
}


display_event_head("Calendar " . date("M j", $start) . " - " . date("M j", $end));

$icals = array($my_ical, $perform_ical);
$complete_events = array();

foreach($icals as $ical)
{
    $cal_events_array = get_date_range($ical, $start, $end, 7);
    $i=0;
    foreach($cal_events_array as $cal_events)
    {
        $p_events = parse_events($cal_events, $ical, $offsetHours);
        
//        display_parsed_events($p_events);
        
        $complete_events = array_merge($complete_events, $p_events);
        
//        echo "Events round: " . $i++ . ": events " . count($p_events) . ": total events: " . count($complete_events)  . " Events<br>";
        
    }
}



usort($complete_events, 'date_compare');

//display varified
display_parsed_events($complete_events);

/***********************************
 * Untiliy Classes
 *
 */  
 
function get_date_range($ical, $start, $end, $days)
{
    $show_on_date = new \DateTime();
    $i_start_date = new \DateTime();
    $i_end_date = new \DateTime();
    $range_events = array();
    
    $i_start = $start;
    
    $i_start_date->setTimestamp($start)->setTime(0, 0, 0);
    $i_start = $i_start_date->getTimestamp();
    
    $i_end_date->setTimestamp($start)->modify('+1 day')->setTime(0, 0, 0);
    $i_end = $i_end_date->getTimestamp();

 
    
    for($i=1; $i <= $days; $i++)
    {
        $show_on_date->setTimestamp($i_start);
        $show_on = $show_on_date->getTimestamp();
        
        $events = $ical->eventsFromRange($i_start, $i_end); 
//        echo "get_date_range " . date("D M j", $show_on) . "<br> Start: " . date("D M j h:i:s a", $i_start) . "  - End: " . date("D M j h:i:s a", $i_end) . " - " . count($events)  . " Events<br>";
//        
        $i_end_date->setTimestamp($i_end)->modify('+1 day')->setTime(0, 0, 0);
        $i_end = $i_end_date->getTimestamp();
        
        $i_start_date->setTimestamp($i_start)->modify('+1 day')->setTime(0, 0, 1);
        $i_start = $i_start_date->getTimestamp();
        
        $new_events = array();
        
        foreach($events as $event)
        {
            $event['show_on'] = $show_on;
            
            $dtstart = $ical->iCalDateToUnixTimestamp($event['DTSTART']);
            if( $dtstart > $show_on + (24 * 3600) - 2 )
            {
//                echo "miss match";
            }
            else
            {
                $new_events[] = $event;
            }
            
/*            
            echo $show_on;
            echo " - ";
            echo $dtstart;
            echo " - ";
            echo $event['SUMMARY'];
            echo " - ";
            echo $event['DTSTART'];
            echo " - ";
            echo $event['DTEND'];
            echo "<br>";
*/
        }
        
        $range_events[] = $new_events;
//        var_dump($new_events);
    }
    
//    echo "Total: " . count($range_events)  . " Events<br>";
    return $range_events;
    
}

function date_compare($a, $b)
{
    $t1 = $a['start_date'];
    $t2 = $b['start_date'];
    return $t1 - $t2;

   return ($t1 < $t2) ? -1 : 1;
}



function get_ical($file_name, $ical_url) 
{
  $cache_file = 'cache' . DIRECTORY_SEPARATOR . $file_name;

  if (file_exists($cache_file) && (filemtime($cache_file) > (time() - 60 * 4 ))) 
  {
     // Cache file is less than five minutes old. 
     // Don't bother refreshing, just use the file as-is.
     $string_data = file_get_contents($cache_file);
     $content = unserialize($string_data);
  } 
  else 
  {
     // Our cache is out-of-date, so load the data from our remote server,
     // and also save it over our cache for next time.
     $content = new ICal($ical_url);
     $string_data = serialize($content);
     file_put_contents($cache_file, $string_data, LOCK_EX);
  }
  
  return $content; 
}



?>
