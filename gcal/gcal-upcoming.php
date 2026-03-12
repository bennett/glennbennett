<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");


require __DIR__ . '/libs/gcal_reader.php';


$cals = [
    [
        'url'  => 'https://calendar.google.com/calendar/ical/c_8oqt9e7bms1sefskr0fl01r7tg%40group.calendar.google.com/public/basic.ics',    
        'name' => 'perform',
    ]    
];

$gcal_reader = new gcal_reader($cals);

$start_str = "";
$end_str = "+14 days";



$start_date = new DateTime($start_str);
$end_date   = new DateTime($end_str);

$start = $start_date->getTimestamp();
$end   = $end_date->getTimestamp();

$complete_events = $gcal_reader->get_events($start, $end);

display_event_head();

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
        foreach($formatted_events as $formatted_event)
        {


            $included = false;  
            $timestamp = $formatted_event['start_date'];

            if ($timestamp >= $rangeStart && $timestamp < $rangeEnd)
            {
                $day_events[] = $formatted_event;
                $included = true;
            }

            $timestamp = $formatted_event['end_date'];

            if ($included == false && $timestamp >= $rangeStart && $timestamp < $rangeEnd)
            {
                $day_events[] = $formatted_event;
            }
            
        }
        
        if( count($day_events) > 0 )
        {            
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
    
}


function display_formated_listing($formatted_event)
{

    $summary    = $formatted_event['summary'];
    $day        = date( "l", $formatted_event['start_date']);
    $mon_day    = date( "F d", $formatted_event['start_date']);
    $day_date   = date( "l - F d", $formatted_event['start_date']);
    $date       = date( "l - F d, Y", $formatted_event['start_date']);
    $short_day  = substr($formatted_event['display_date'], 0, 3);
    $time       = $formatted_event['display_date_time']; 
?>
<!-- Start -->

<div class="oc-item">
    <div class="ievent clearfix">
        <div class="entry-c">
          <div class="entry-title">   
          <a href="/cal">                         
          <button type="button" class="btn btn-secondary w-100 btn-lg">
          <i class="icon-calendar3"></i>
          <?php echo "<strong>" . $day . "</strong> - <small>" . $mon_day . "</small>"?>
          </button>
          </a>
          </div>
          
          <div class="dark">
          <h3><?php echo $summary; ?></h3>
          
          
          <ul class="entry-meta clearfix">
          <li><i class="icon-calendar"></i> <?php echo $date; ?></li>
          <li><i class="icon-time"></i> <?php echo $time; ?></li>
          
          </ul>

          <a href="/cal" class="button button-border button-dark button-rounded button-mini">More Information</a>
          </div>
        </div>
    </div>
</div>
                                           
<!-- End -->

<?php
}


function display_event_head()
{
    global $notice;
  ?>
	<div class="title-center dark title-dotted-border ">
		<h3>Upcoming Events</h3>
	</div>

	<div id="oc-events" class="owl-carousel events-carousel carousel-widget" data-margin="20" data-nav="true" data-pagi="false" data-items-md="1" data-items-lg="2" data-items-xl="2">


    <?php
    
}



function display_event_footer()
{

  ?>
  
    </div>

    <?php
}