<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");

require 'libs/gcal_reader.php';


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

$gcal_reader = new gcal_reader($cals);

$display_week = 0;

if(isset($_GET['week'])) {
    $display_week = $_GET['week'];
}

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

$complete_events = $gcal_reader->get_events($start, $end);

//var_dump($complete_events);

display_event_head(date("M j", $start) . " - " . date("M j", $end));

display_parsed_events($complete_events, $start, $end);

display_event_footer();

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
            if ($included == false && $timestamp > $rangeStart && $timestamp < $rangeEnd)
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
    echo "<a class='btn btn-primary btn-sm' href='" . "?week=";
    echo $next_week;
    echo "'>Next Week</a>";
    echo '<div>';

}

/*
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
*/

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
          <h3 ><?php echo $title; ?></h3>
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
