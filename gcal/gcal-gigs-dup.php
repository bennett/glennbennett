<?php
date_default_timezone_set('America/Los_Angeles');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");
error_log( "Hello, errors!" );


define('CHARSET', 'utf-8');

require 'libs/gcal_reader.php';


$cals = [
    [
        'url'  => 'https://calendar.google.com/calendar/ical/c_8oqt9e7bms1sefskr0fl01r7tg%40group.calendar.google.com/public/basic.ics',    
        'name' => 'perform',
    ]    
];

$gcal_reader = new gcal_reader($cals);

$start_str = "-360 days";
$end_str = "";



$start_date = new DateTime($start_str);
$end_date   = new DateTime($end_str);

$start = $start_date->getTimestamp();
$end   = $end_date->getTimestamp();

$complete_events = $gcal_reader->get_events($start, $end);

//var_dump($complete_events);

//display_event_head(date("M j", $start) . " - " . date("M j", $end));

$complete_events = array_reverse($complete_events);

echo "<h4>Past " . count($complete_events) . " performances</h4>";

foreach($complete_events as $complete_event)
{
    display_formated_listing($complete_event);
}


display_event_footer();

function display_parsed_events($formatted_events, $start, $end)
{
 
    $start_date = new \DateTime();
    $start_date->setTimestamp($start)->setTime(0, 0, 0);
    $rangeStart = $start_date->getTimestamp();

    $end_date = new \DateTime();

    $end_date->setTimestamp($start)->modify('+1 day')->setTime(0, 0, 0);
    $rangeEnd = $end_date->getTimestamp();
    
    $displayed_events = 0; // Used so we can check if nothing is displayed

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
//            echo "<hr><h4>";
//            echo date("D M j", $rangeStart);
//            echo "</h4>"; 
            
            foreach($day_events as $day_event)
            {           
                display_formated_listing($day_event);
                $displayed_events++;
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
    
    return $displayed_events;
}

function display_parsed_events_day($formatted_events)
{
    
    $current_date = "";
    foreach ($formatted_events as $formatted_event)
    {
      
        if($formatted_event['display_date'] != $current_date)
        {
            echo '<button type="button" class="btn btn-lg btn-primary disabled">';
            echo "<h4>xxx";
            echo $formatted_event['display_date'];
            echo "</h4>"; 
            echo '</button>';   
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

    $summary    = $formatted_event['summary'];
    $description = $formatted_event['description'];
    $day        = date( "l", $formatted_event['start_date']);
    $mon_day    = date( "F d, Y", $formatted_event['start_date']);
    $mon_day_year = date( "F d, Y", $formatted_event['start_date']);
    $day_date   = date( "l - F d", $formatted_event['start_date']);
    $date       = date( "l - F d, Y", $formatted_event['start_date']);
    $short_day  = substr($formatted_event['display_date'], 0, 3);
    $time       = $formatted_event['display_date_time']; 
?>
<!-- Start -->

<div class="row">
<div class="entry event col-12">
<div class="grid-inner row align-items-center g-0 p-4">

<div class="col-md-12">
<div class="entry-title title-xs">
<h2><?php echo $summary; ?></h2>
</div>

<ul class="mb-0">
<li><i class="icon-calendar"></i> <?php echo $date; ?></li>
<li><i class="icon-time"></i> <?php echo $time; ?></li>
<?php
    

    if($formatted_event['location'] != '')
    { 
        echo '<li>';
        
        echo '    <i class="icon-map-marker2"></i> ';
        echo $formatted_event['location'];
        
        echo '</li>';
    }



?>


</ul>

<a target="_blank" href="/dup_event?date=<?php echo $formatted_event['start_date']; ?>"><button type="button" class="btn btn-primary float-right">Duplicate</button></a>
 

</div>
</div>
</div>


</div>
<!-- End -->

<?php
}



function display_event_head($title)
{
    global $notice;
  ?>
  <section id="events">
    <div class="container">
      <div class="row">
        <div class="heading text-center wow fadeInUp" data-wow-duration="1200ms" data-wow-delay="300ms">
          <h3><?php echo $title; ?></h3>
        <?php echo $notice; ?>
          <p></p>
        </div>
      </div>
      <div class="gigs">

        <div class="row">
    <?php
}



function display_event_footer()
{

  ?>
  
  
  
        <!--/div>
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
