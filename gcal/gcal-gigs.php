<?php
date_default_timezone_set('America/Los_Angeles');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");

define('CHARSET', 'utf-8');

require 'libs/gcal_reader.php';
require 'libs/weather_warnings.php';


$cals = [
    [
        'url'  => 'https://calendar.google.com/calendar/ical/c_8oqt9e7bms1sefskr0fl01r7tg%40group.calendar.google.com/public/basic.ics',    
        'name' => 'perform',
    ],
    [
        'url'  => 'https://calendar.google.com/calendar/ical/c_60458ee36250676533587bd3a2b92e3bedc52796d6a2b5b76fca9bd60ccba33d%40group.calendar.google.com/public/basic.ics',    
        'name' => 'canceled',
    ]
];

$descriptions = [];

$gcal_reader = new gcal_reader($cals);

$start_str = "";
$end_str = "+32 days";


$start_date = new DateTime($start_str);
$end_date   = new DateTime($end_str);

$start = $start_date->getTimestamp();
$end   = $end_date->getTimestamp();

$complete_events = $gcal_reader->get_events($start, $end);

//var_dump($complete_events);

//display_event_head(date("M j", $start) . " - " . date("M j", $end));

$displayed_events = display_parsed_events($complete_events, $start, $end);

if($displayed_events == 0)
{
    echo "<h4>Sorry Nothing Scheduled</h4>";
    echo "<p>Sign up for the newsletter to get updates</p>";
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
            $start_timestamp = $formatted_event['start_date'];
//            if ($timestamp >= $rangeStart && $timestamp < $rangeEnd)
            if ($start_timestamp >= $rangeStart && $start_timestamp < $rangeEnd)
            {
                $day_events[] = $formatted_event;
                $included = true;
//                echo "Included<br>";
            }
//            echo "<hr>";

            $end_timestamp = $formatted_event['end_date'];
//            if ($timestamp >= $rangeStart && $timestamp < $rangeEnd)
            if ($included == false && $end_timestamp >= $rangeStart && $end_timestamp < $rangeEnd)
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
    
    /*
    $next_week = $display_week + 1;
    
    echo '<hr><div class="text-center wow fadeInUp">';
    echo "<a class='btn btn-primary btn-sm' href='" . $_SERVER['PHP_SELF'] . "?week=";
    echo $next_week;
    echo "'>Next Week</a>";
    echo '<div>';
    */
    
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
    $mon_day    = date( "F d", $formatted_event['start_date']);
    $day_date   = date( "l - F d", $formatted_event['start_date']);
    $date       = date( "l - F d, Y", $formatted_event['start_date']);
    $short_day  = substr($formatted_event['display_date'], 0, 3);
    $time       = $formatted_event['display_date_time']; 
 
 
    // --- Try DB for venue images, fallback to hardcoded arrays ---
    $generic_venue_imgs = array();
    $venue_imgs = array();
    $db_venues_loaded = false;

    try {
        $vdb = new mysqli('localhost', 'tsgimh_glb', '2276midi', 'tsgimh_glb1');
        if (!$vdb->connect_error) {
            $vresult = $vdb->query("SELECT * FROM venues WHERE is_active = 1 ORDER BY id ASC");
            if ($vresult && $vresult->num_rows > 0) {
                $db_venues_loaded = true;
                while ($vrow = $vresult->fetch_assoc()) {
                    $entry = array(
                        'name' => $vrow['match_pattern'],
                        'img_url' => $vrow['venue_logo'],
                        'match_type' => $vrow['match_type']
                    );
                    if ($vrow['match_type'] === 'alpha_only') {
                        $generic_venue_imgs[] = $entry;
                    } else {
                        $venue_imgs[] = $entry;
                    }
                }
            }
            $vdb->close();
        }
    } catch (Exception $e) {
        $db_venues_loaded = false;
    }

    // Fallback to hardcoded arrays if DB unavailable
    if (!$db_venues_loaded) {
        $generic_venue_imgs = array(
            array('name' => 'Farmers Market', 'img_url' => '/imgs/cal/FarmersMarket.jpg', 'match_type' => 'alpha_only')
        );

        $venue_imgs = array(
            array('name' => 'Santa Paula Farmers Market', 'img_url' => '/imgs/cal/SantaPaulaFarmersMarket.png', 'match_type' => 'exact'),
            array('name' => 'Coffee A La Mode', 'img_url' => '/imgs/cal/Coffeelogo.jpg', 'match_type' => 'exact'),
            array('name' => 'Adventist Health Simi Valley Farmers Market', 'img_url' => '/imgs/cal/AHSV-FarmersMarket-Logo.png', 'match_type' => 'exact'),
            array('name' => 'Adventist Health Farmers Market', 'img_url' => '/imgs/cal/AHSV-FarmersMarket-Logo.png', 'match_type' => 'exact'),
            array('name' => 'Simi Valley Farmers Market', 'img_url' => '/imgs/cal/SimiValleyFarmersMarket.jpg', 'match_type' => 'exact')
        );
    }

    $cal_image= "/imgs/cal/gig-stool.png";  // set default image

    // set generic images (alpha_only matching)
    foreach($generic_venue_imgs as $venue_img)
    {
        if(matchAlphaOnly($summary, $venue_img['name']) )
        {
            $cal_image = $venue_img['img_url'];
        }
    }

    // Now set a specific one if available (exact or contains matching)
    foreach($venue_imgs as $venue_img)
    {
        $match_type = isset($venue_img['match_type']) ? $venue_img['match_type'] : 'exact';
        if ($match_type === 'contains' && strpos($summary, $venue_img['name']) !== false) {
            $cal_image = $venue_img['img_url'];
        } elseif ($summary == $venue_img['name']) {
            $cal_image = $venue_img['img_url'];
        }
    }
    
  
    $found = false; // Flag to indicate if the summary is found

    global $descriptions;

    $found = false;

    foreach ($descriptions as $past_description) {
        
        if ($description === $past_description) {
            $found = true;
            break; // Exit the loop once the description is found
        }
    }

    // Figure out if we need to display the whole thing
    if ($found) {
        $short = true;
    } else {
        $short = false;
        $descriptions[] = $description;
    }
    
    // weather warning date
    $w_date     = date( "Y-m-d", $formatted_event['start_date']);
//    echo $formatted_event['start_date'];
    $given_date = $w_date; // Example given date (format: YYYY-MM-DD)
    
//    start at begining of today
    $current_date = new DateTime(); // Current date (today)
    $current_date->setTime(0, 0, 0); // Set to start of the day


    $given_date_obj = new DateTime();
    $given_date_obj->setTimestamp($formatted_event['start_date']);
//    $given_date_obj->setTime(0, 0, 0); // Set to start of the day

    // Calculate the difference in days
    $interval = $current_date->diff($given_date_obj);
    
    
    
    $diff_days = $interval->days; // Get the total number of days as an integer
    
//    $days_away = $diff_days . " away";
    
    $days_away = daysToWeeksAndDays($diff_days)  . " away";
    
    $past = false;
    if($diff_days == 0)
    {
        $days_away = "Today";
       
//        $end_date = date( "Y-m-d H:i:s", $formatted_event['end_date']);
//        $now_date = date( "Y-m-d H:i:s", time()); 
//        echo $end_date . " > " . $now_date;
//        echo date('m/d/Y h:i:s A');
//        echo "<br>";
//        echo date('m/d/Y h:i:s A', $formatted_event['end_date']);
//        echo "<br>";
//        echo time() . " > " . $formatted_event['end_date'];
        

        if ( time() > $formatted_event['end_date'] ) {
              $days_away = $days_away . ' - This event is probably over';
              $past = true;
        } 
        else if ( time() > $formatted_event['start_date'] ) {
            $days_away = $days_away . ' - This event is ending soon';
        }
    }
    
    if($diff_days == 1)
    {
        $days_away = "Tomorrow";
    }
    


    // Check if the difference is less than 5 days
    $weather_warning = "";
    if ($diff_days < 3) 
    {
        $weather_warnings = new weather_warnings($formatted_event['location'], $w_date);

        $weather_warning = $weather_warnings->get_warnings($formatted_event['start_date'], $formatted_event['end_date']);
        
        if($weather_warning == null)
        {
            $weather_warning = "";
        }
           
//        echo $weather_warning;
    } 
    
 
    
    $overylay = "";
    $canceled = false;
    
    if($formatted_event['status'] == 'Canceled')
    {
        $overylay = "overlay position-relative canceled-event";
        $canceled = true;
    }
?>
<!-- Start -->

<div class="row">
<div class="entry event col-12">
<div class="grid-inner row align-items-center g-0 p-4">
<?php
  $b_color = "btn-primary";
  if($past)
  {
    $b_color = "btn-warning";
  }
?>
<button type="button" class="btn <?php echo $b_color; ?> disabled btn-lg w-100 mb-3">
<i class="icon-calendar3"></i>
<?php echo "<strong>" . $day . "</strong> - <small>" . $mon_day . "<br>" . $days_away . "</small>"?></button>


<?php


        
        
if($canceled)
{
    echo "<div ><h4 class='text-danger  mb-0'>" . $summary . "</h4><h2 class='text-danger  mb-0'>Canceled</h2></div>";
}
?>
<div class="col-md-12 <?php echo $overylay; ?>">


<?php
    if($weather_warning != "")
    {
        echo  '<div class="card text-dark mb-3 bg-warning">';
        echo  '  <div class="card-header"><h3 class="alert-heading mb-0">Weather Warning</h3></div>';
        echo  '  <div class="card-body">';
//        echo  '    <h5 class="card-title">Warning card title</h5>';
        

        echo  '    <p class="card-text">';
        echo $weather_warning;
        echo  '</p>';
        echo  '  </div>';
        echo  '</div>';        
    }
?>        

<!--
<div class="entry-title title-xs">
<h2><?php echo $summary; ?></h2>
<?php
if($short)
{
?>


</div>

<?php
}
else
{
?>
 <div class="row">
    <div class="col-md-8">
    
      
      <p>
<?php echo $description; ?>
</p>
    </div>
    <div class="col-md-4">
      
      <img src="<?php echo $cal_image; ?>" class="float-end w-100 mb-2" alt="Descriptive text">
    </div>
  </div>
</div>

<?php
}
?>
-->
<?php
$imageOrientation = getImageOrientation($cal_image);

// Start title container
echo '<div class="entry-title title-xs">';

// 1. Show the Title First
echo '<h2>' . $summary . '</h2>';

if ($short) {
    // If it's a short/duplicate description, just close the title div
    echo '</div>'; 
} else {
    // 2. Open the Magazine Body Container
    // Increased margin-bottom to 40px for better separation from the Performance list
    echo '<div style="display: block !important; clear: both !important; width: 100%; margin-bottom: 40px; margin-top: 15px;">';
    
    if ($imageOrientation == 'horizontal') {
        // Landscape Frame: Wide view on top of the text
        echo '<div style="border: 1px solid #ddd; padding: 5px; background: #fff; box-shadow: 2px 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px;">';
        echo '<img src="' . $cal_image . '" class="w-100" style="display: block;" alt="Event Image">';
        echo '</div>';
    } else {
        // Portrait/Square Frame: Floated to the right with a "Polaroid" tilt
        echo '<style>.cal-portrait-frame { float: right; margin-left: 20px; margin-bottom: 15px; width: 170px; border: 1px solid #ccc; padding: 5px; background: #fff; box-shadow: 4px 4px 12px rgba(0,0,0,0.15); transform: rotate(1.5deg); } @media (max-width: 767px) { .cal-portrait-frame { width: 120px; } }</style>';
        echo '<div class="cal-portrait-frame">'; 
        echo '<img src="' . $cal_image . '" style="width: 100%; height: auto; display: block;" alt="Event Image">';
        echo '</div>';
    }
    
    // 3. Prepare Text: Remove <br> and trim leading spaces
    $clean_description = ltrim(str_replace(['<br>', '<br />', '<br/>'], ' ', $description));
    
    // 4. Drop Cap Logic (using mb_substr for better character support)
    $first_letter = mb_substr($clean_description, 0, 1);
    $remaining_text = mb_substr($clean_description, 1);
    
    echo '<p style="text-align: left; line-height: 1.6; margin: 0; font-size: 16px; color: #444;">';
    // The Drop Cap Span (Primary Blue color)
    echo '<span style="float: left; font-size: 55px; line-height: 45px; padding-top: 4px; padding-right: 8px; font-family: Georgia, serif; font-weight: bold; color: #007bff;">' . $first_letter . '</span>';
    echo $remaining_text;
    echo '</p>';
    
    // 5. Clean up the float so nothing else wraps into the description
    echo '<div style="clear: both;"></div>'; 
    echo '</div>'; // End magazine body
    echo '</div>'; // End entry-title
}
?>


<?php
    
    if(!$canceled)
    {
?>    
<div class="list-group mb-3">
    <div class="list-group-item list-group-item-primary bg-primary text-white h5 mb-0 py-2">
        <strong>Performance</strong>
    </div>
    <li class="list-group-item"><i class="icon-calendar"></i> <?php echo $date; ?></li>
    <li class="list-group-item"><i class="icon-time"></i> <?php echo $time; ?></li>
    <li class="list-group-item">
    <?php
        echo '<a target="_blank" href="' . $formatted_event['add_to_cal_link'] . '">';
        echo '<button type="button" class="btn btn-info btn-sm float-right"><i class="icon-calendar3"></i> Add to Google Calendar</button>';
        echo '</a><br>';
    ?>
    </li>
</div>


<ul class="list-group mb-3">
<?php
    
//    if(!$canceled)
//    {
    
        if($formatted_event['location'] != '' )
        { 
            echo '<li class="list-group-item">';
            
            echo '    <i class="icon-map-marker2"></i> ';
            echo $formatted_event['location'];
            echo '</li>';
            
            echo '<li class="list-group-item">';
            echo '<a target="_blank" href="' . $formatted_event['driving_link'] . '">';
            echo '<button type="button" class="btn btn-primary btn-sm float-right">Get Directions</button>';
            echo '</a>';
            echo '</li>';
            echo '</ul>';
               
        }
        

        $fb_url = "http://www.facebook.com/share.php?u=";
 
        /* New - 11/30/2025 */
        $fb_param = "/facebook?event_id=" . $formatted_event["UID"] .
            "&event_date=" . $formatted_event["start_date"]; 
            
        $share_url = "http://glennbennett.com/facebook?event_id=" . $formatted_event["UID"] . 
                     "&event_date=" . $formatted_event["start_date"];

        // Encode the URL for Facebook
        $fb_link = $fb_url . urlencode($share_url);


        // Use HTTPS for Facebook share URL
        $fb_url = "https://www.facebook.com/sharer.php?u=";

        // Build the full URL to be shared
        $share_url = "https://glennbennett.com" . $fb_param;

        // Create the complete Facebook share link with proper encoding
        $fb_link = $fb_url . urlencode($share_url);

        // Share text and links for social/messaging platforms
        $share_text = "Glenn Bennett live at " . $formatted_event["summary"] . " - " . $date . ", " . $time;
        $twitter_link = "https://twitter.com/intent/tweet?url=" . urlencode($share_url) . "&text=" . urlencode($share_text);
        $whatsapp_link = "https://api.whatsapp.com/send?text=" . urlencode($share_text . " " . $share_url);
        $sms_link = "sms:?&body=" . rawurlencode($share_text . " " . $share_url);
        $email_subject = "Glenn Bennett performing @ " . $formatted_event["summary"];
        $email_body = $share_text . "\n\n" . $share_url;
        $email_link = "mailto:?subject=" . rawurlencode($email_subject) . "&body=" . rawurlencode($email_body);

    }
    
    if(!$canceled)
    {
        
?> 

<div class="entry-title title-xs">

 <div class="d-flex flex-wrap align-items-start" style="gap: 12px;">

    <div class="text-center">
        <a target="_blank" href="<?php echo $fb_link; ?>" class="social-icon si-dark si-colored si-facebook mb-0">
            <i class="icon-facebook"></i>
            <i class="icon-facebook"></i>
        </a>
        <small style="display: block; margin-top: 3px;"><strong>Facebook</strong></small>
    </div>

    <div class="text-center">
        <a target="_blank" href="<?php echo $twitter_link; ?>" class="social-icon si-dark si-colored si-twitter mb-0">
            <i class="icon-twitter"></i>
            <i class="icon-twitter"></i>
        </a>
        <small style="display: block; margin-top: 3px;"><strong>Twitter</strong></small>
    </div>

    <div class="text-center">
        <a target="_blank" href="<?php echo $whatsapp_link; ?>" class="social-icon si-dark si-colored si-whatsapp mb-0">
            <i class="icon-whatsapp"></i>
            <i class="icon-whatsapp"></i>
        </a>
        <small style="display: block; margin-top: 3px;"><strong>WhatsApp</strong></small>
    </div>

    <div class="text-center">
        <a href="<?php echo $sms_link; ?>" class="social-icon si-dark si-colored si-email3 mb-0">
            <i class="icon-phone"></i>
            <i class="icon-phone"></i>
        </a>
        <small style="display: block; margin-top: 3px;"><strong>Text</strong></small>
    </div>

    <div class="text-center">
        <a href="<?php echo $email_link; ?>" class="social-icon si-dark si-colored si-email3 mb-0">
            <i class="icon-envelope"></i>
            <i class="icon-envelope"></i>
        </a>
        <small style="display: block; margin-top: 3px;"><strong>Email</strong></small>
    </div>

    <div class="text-center">
        <a href="javascript:void(0);" onclick="var b=this.parentElement.querySelector('small strong');navigator.clipboard.writeText('<?php echo $share_url; ?>').then(function(){b.textContent='Copied!';setTimeout(function(){b.textContent='Copy Link';},2000);});" class="social-icon si-dark mb-0">
            <i class="icon-link"></i>
            <i class="icon-link"></i>
        </a>
        <small style="display: block; margin-top: 3px;"><strong>Copy Link</strong></small>
    </div>

    <div class="text-center">
        <a target="_blank" href="<?php echo $fb_param; ?>" class="social-icon si-dark si-colored si-print mb-0">
            <i class="icon-print"></i>
            <i class="icon-print"></i>
        </a>
        <small style="display: block; margin-top: 3px;"><strong>Print</strong></small>
    </div>

  </div>
</div>
<?php
    }

?>

</div>
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


function matchAlphaOnly($string, $substring) {
    // Remove everything except alphabetic characters from both strings
    $alphaString = strtoupper(preg_replace('/[^a-zA-Z]/', '', $string));
    $alphaSubstring = strtoupper(preg_replace('/[^a-zA-Z]/', '', $substring) );

    // Check if the cleaned string contains the cleaned substring
    if (strpos($alphaString, $alphaSubstring) !== false) {
        return true;
    } else {
        return false;
    }
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


function daysToWeeksAndDays($days) {
    $weeks = floor($days / 7);
    $remainingDays = $days % 7;

    $result = '';
    if ($weeks > 0) {
        $result .= "$weeks week" . ($weeks > 1 ? 's' : '');
    }
    if ($remainingDays > 0) {
        if ($weeks > 0) {
            $result .= ' and ';
        }
        $result .= "$remainingDays day" . ($remainingDays > 1 ? 's' : '');
    }

    return $result;
}


function getImageOrientation($relativePath) {
    // Get the current directory path
    $basePath = $_SERVER['DOCUMENT_ROOT'];
    
    // Combine the base path with the relative path
    $fullPath = rtrim($basePath, '/') . '/' . ltrim($relativePath, '/');
    
    // Check if file exists
    if (!file_exists($fullPath)) {
        return 'Error: Image file not found';
    }
    
    // Get image dimensions
    $dimensions = getimagesize($fullPath);
    if ($dimensions === false) {
        return 'Error: Unable to get image dimensions';
    }
    
    $width = $dimensions[0];
    $height = $dimensions[1];
    
    // Allow for slight variations (within 1% difference)
    $tolerance = 0.01;
    $ratio = abs(1 - ($width / $height));
    
    if ($ratio <= $tolerance) {
        return 'square';
    } elseif ($width > $height) {
        return 'horizontal';
    } else {
        return 'vertical';
    }
}

// Example usage:
// $orientation = getImageOrientation('images/photo.jpg');
// echo $orientation; // Will output: 'square', 'horizontal', or 'vertical'



