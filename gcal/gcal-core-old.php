<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");
error_log( "Hello, errors!" );

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
//require 'libs/venues.php';
//require 'libs/utils.php';
//require 'libs/cal.php';
//require 'libs/field_handler.php';

require 'class.iCalReader.php';

//$my_ical = new ICal('https://calendar.google.com/calendar/ical/gbennett%40tsgdev.com/private-61ab22cc7e10980c9bd2cab69790fd1a/basic.ics');
//$perform_ical = new ICal('https://calendar.google.com/calendar/ical/c_8oqt9e7bms1sefskr0fl01r7tg%40group.calendar.google.com/public/basic.ics');

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

$cals = [
    [
        'url'  => 'https://calendar.google.com/calendar/ical/gbennett%40tsgdev.com/private-61ab22cc7e10980c9bd2cab69790fd1a/basic.ics',    
        'name' => 'my-cal',
    ],
    [
        'url'  => 'https://calendar.google.com/calendar/ical/c_8oqt9e7bms1sefskr0fl01r7tg%40group.calendar.google.com/public/basic.ics',    
        'name' => 'perform',
    ],
    [
        'url'  => 'https://calendar.google.com/calendar/ical/c_4nlj0ar1kfsohjpaf6fbmti160%40group.calendar.google.com/private-a10312d2b47cd2c3f1be907aa979ecd5/basic.ics',    
        'name' => 'leslie',
    ],    
];



//echo date("D M j - g:i a", $start) ." - ";
//echo date("D M j - g:i a", $end) . "<br>\n";

//$my_ical_events = $my_ical->eventsFromRange($start, $end);

//$perform_ical_events = $perform_ical->eventsFromRange($start, $end);



$complete_events = array();

foreach($cals as $cal)
{
    $cal['ical'] = new ICal($cal['url']);
    $events = $cal['ical']->eventsFromRange($start, $end);
    $parsed_events = parse_events($events, $cal, $offsetHours);
    $complete_events = array_merge($complete_events, $parsed_events);
}

usort($complete_events, 'date_compare');


display_event_head("Calendar " . date("M j", $start) . " - " . date("M j", $end));

    
display_parsed_events($complete_events, $start, $end);

/********************
 * Main Functions
 *
 */

function parse_events($events, $cal, $hoursOffset)
{
    global $venues;
    global $performer_images;
    global $fields;
    
    $uids = array();
    
//    echo "Events: " . count($events) . "<br>";;
    //  var_dump($events);
    
    $formatted_events = array();
    
    foreach ($events as $event)
    {
        if( in_array($event['UID'],$uids ) )
        {
            break;
        }
        
        $uids[] = $event['UID'];
        
        if(!isset($event['SUMMARY'])) $event['SUMMARY'] = '';
        if(!isset($event['DESCRIPTION'])) $event['DESCRIPTION'] = '';
        
        $summary_full = string_cleaner($event['SUMMARY']);
        $summary = str_replace("(", "<br />(", $summary_full);
        
        
        $description = string_cleaner($event['DESCRIPTION']);
        
//        echo "summary: " . $summary . "<br>";
        
//        $formatted_event = get_fields($description, $fields);
        
        $formatted_event['summary'] = $summary;
        
        // Set start and end dates
        $UTC =  substr($event['DTSTART'], 15, 16);
        
        $start_date = $cal['ical']->iCalDateToUnixTimestamp($event['DTSTART']);
        $end_date = $cal['ical']->iCalDateToUnixTimestamp($event['DTEND']);
        
        if($UTC == 'Z')
        {
            $start_date -= $hoursOffset;
            $end_date   -= $hoursOffset;
        }
        
        $display_date = date("D M j", $start_date);
//        $show_on = date("D M j", $event['show_on']);
        $display_date_time = "";
        
        //add time
        if( ($end_date - $start_date) != 86400  )
        {
            $display_date_time = date("g:i a", $start_date)." - ".
                date("g:i a", $end_date);
        }
        
        if( date("D M j", $start_date) != date("D M j", $end_date))
        {
                        $display_date_time = date("g:i a", $start_date)." - ".
                date("D M j g:i a", $end_date);
        }
        
        $formatted_event['start_date']   = $start_date;
        $formatted_event['end_date']     = $end_date;
        $formatted_event['cal_name']         = $cal['name'];
//        $formatted_event['show_on']     = $show_on;
        $formatted_event['display_date'] = $display_date;
        $formatted_event['display_date_time'] = $display_date_time;
        $formatted_event['uid']          = substr($event['UID'], 0, strpos($event['UID'], "@"));
        
        // setup location
        
        // Set Default
        $formatted_event['location'] = "";
        $formatted_event['driving_link'] = "";
        
        if(isset($event['LOCATION']))
        {
          $location = string_cleaner($event['LOCATION']);
        
          $driving_link = "http://maps.google.com/maps?daddr=" . urlencode($location);
        
          $formatted_event['location']     = $location;
          $formatted_event['driving_link'] = $driving_link;
        }
        
        
        $formatted_events[] = $formatted_event;
      

      
    }
//    echo "<hr>";
//    echo "Formatted: " . count($formatted_events);
//    echo "<hr>";
    return  $formatted_events;
}

function display_parsed_events($formatted_events, $start, $end)
{
 
    $start_date = new \DateTime();
    $start_date->setTimestamp($start)->setTime(0, 0, 0);
    $rangeStart = $start_date->getTimestamp();

    $end_date = new \DateTime();

    $end_date->setTimestamp($start)->modify('+1 day')->setTime(0, 0, 0);
    $rangeEnd = $end_date->getTimestamp();

    while( $rangeStart < $end )
    {
    
        $day_events = array();    
        
//        echo "rangeStart: " . date("D M j h:i:s a", $rangeStart) . "<br>rangeEnd: " . date("D M j h:i:s a", $rangeEnd) . "<hr>";
        foreach($formatted_events as $formatted_event)
        {

/*            echo $formatted_event['summary'];
            echo "<br>";
            echo date("D M j h:i:s a", $formatted_event['start_date']);
            echo "<br>";
*/          $included = false;  
            $timestamp = $formatted_event['start_date'];
//            if ($timestamp >= $rangeStart && $timestamp < $rangeEnd)
            if ($timestamp >= $rangeStart && $timestamp < $rangeEnd)
            {
                $day_events[] = $formatted_event;
                $included = true;
//                echo "Included<br>";
            }
//            echo "<hr>";

            $timestamp = $formatted_event['end_date'];
//            if ($timestamp >= $rangeStart && $timestamp < $rangeEnd)
            if ($included == false && $timestamp >= $rangeStart && $timestamp < $rangeEnd)
            {
                $day_events[] = $formatted_event;
//                echo "Included<br>";
            }
//            echo "<hr>";
            
        }
        
        if( count($day_events) > 0 )
        {
            echo "<hr><h4>";
            echo date("D M j", $rangeStart);
            echo "</h4>"; 
            
            foreach($day_events as $day_event)
            {           
                display_formated_listing($day_event);
            }           
        }        
     

        $start_date->setTimestamp($rangeStart)->modify('+1 day');
        $rangeStart = $start_date->getTimestamp();

        $end_date->setTimestamp($rangeEnd)->modify('+1 day');
        $rangeEnd = $end_date->getTimestamp();

    }
    
    
    $display_week = 0;
    
    if(isset($_GET['week'])) {
        $display_week = $_GET['week'];
    }    
    
    $next_week = $display_week + 1;
    echo '<hr><div class="text-center wow fadeInUp">';
    echo "<a class='btn btn-primary btn-sm' href='/gcal/?week=";
    echo $next_week;
    echo "'>Next Week</a>";
    echo '<div>';

    display_event_footer();
}

function display_parsed_events_day($formatted_events)
{
    
    $current_date = "";
    foreach ($formatted_events as $formatted_event)
    {
      
        if($formatted_event['display_date'] != $current_date)
        {
            echo "<hr><h4>";
            echo $formatted_event['display_date'];
            echo "</h4>";     
            $current_date = $formatted_event['display_date']; 
        }

        display_formated_listing($formatted_event);

    }
    
    $display_week = 0;
    
    if(isset($_GET['week'])) {
        $display_week = $_GET['week'];
    }    
    
    $next_week = $display_week + 1;
    echo '<hr><div class="text-center wow fadeInUp">';
    echo "<a class='btn btn-primary btn-sm' href='/gcal/?week=";
    echo $next_week;
    echo "'>Next Week</a>";
    echo '<div>';

}


function display_formated_listing($formatted_event)
{

    $summary = $formatted_event['summary'];
    $day = substr($formatted_event['display_date'], 0, 3); 
  
    echo "<div class=\"alisting col-sm-4 wow fadeInUp\" data-wow-duration=\"1000ms\" data-wow-delay=\"400ms\">";
    echo "  <div class=\"post-thumb\">";
    echo "  </div>";
    echo "  </div>";
    echo "  <div class=\"". $formatted_event['cal_name'] . " entry-header\">";
    echo "    <h6>". $summary . "<small>";
    if($formatted_event['display_date_time'] != "")
    {
        echo " - " . $formatted_event['display_date_time'];
    }
    
    echo "    </small></h6>";
    
//    echo "</span>";
    echo "  </div>";
    echo "  <div class=\"location-content text-center\"><small>";

//    echo "<p>";
//    echo "  <a href=\"event.php?event=" . urlencode($formatted_event['uid']) . "\" class=\"btn-sm btn-primary\" role=\"button\">More Info</a>";
//    echo "</p>";
    
    if($formatted_event['location'] != '')
    {
    echo "Location: ". $formatted_event['location'] . "<br /> \n";
    }
//    echo $formatted_event['driving_link'] ;
//    echo "<a class=\"btn btn-info\" href=\"" . $formatted_event['driving_link'] . "\" target=\"_blank\">Get Directions</a>\n";
    echo "  </small></div>";

//    echo "</div>";
}


function display_event_head($title)
{
    global $notice;
  ?>
  <section id="events">
    <div class="container">
      <div class="row">
        <div class="heading text-center wow fadeInUp" data-wow-duration="1200ms" data-wow-delay="300ms">
          <h1 class="display-6"><?php echo $title; ?></h1>
        <?php echo $notice; ?>
          <p></p>
        </div>
      </div>
      <div class="blog-posts">

        <div class="row">
    <?php
}



function display_event_footer()
{

  ?>
  
  
  
        </div>
      </div>
    </div>
  </section><!--/#events-->
<script>
$(document).ready(function(){
    $("#spinner").hide();
    
});

</script> 
    <?php
}

function string_cleaner($str)
{
    $str = str_replace("\\n", "<br />", $str);
    $str = preg_replace('/\\\\/', '', $str);
    $str = str_replace('&lt;', '<', $str);
    $str = str_replace('&gt;', '>', $str);
    
    
    return  $str;
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

