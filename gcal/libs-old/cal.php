<?php

function parse_events($events, $ical, $hoursOffset)
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
        
        $start_date = $ical->iCalDateToUnixTimestamp($event['DTSTART']);
        $end_date = $ical->iCalDateToUnixTimestamp($event['DTEND']);
        
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


function display_parsed_events($formatted_events)
{
    
    $current_date = "";
    foreach ($formatted_events as $formatted_event)
    {
      
        if($formatted_event['show_on'] != $current_date)
        {
            echo "<hr><h4>";
            echo $formatted_event['show_on'];
            echo "</h4>";     
            $current_date = $formatted_event['show_on']; 
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
    echo "  <div class=\"entry-header\">";
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

function get_image($summary, $images, $sub_dir)
{
    $img_name = "";

//    $max_image = 4;
    // find location

//    $found = false;
    foreach($images as $img)
    {
        $pos = strpos($summary, $img['search']);
        if($pos !== false)
        {
            if($sub_dir == '')
            {
                $img_name = $img['dir'] . "/" . rand(1,$img['count']) . ".jpg";
            }
            else
            {
                $img_name = $sub_dir . "/" . $img['dir'] . "/" .  rand(1,$img['count']) . ".jpg";
            }

//            $found = true;
            break;
        }
    }

//    echo $img_name;
/*
    if(!$found)
    {
        $img_name = "default/" . rand(1,$img['count']) . ".jpg";
    }
*/
    return  $img_name;
}

function get_info($summary, $images, $sub_dir)
{
    $info = "";
    $iPath = "";

    foreach($images as $img)
    {
        $pos = strpos($summary, $img['search']);
        if($pos !== false)
        {
            if($sub_dir == '')
            {
                $iPath = $img['dir'] ;
            }
            else
            {
                $iPath = $sub_dir . "/" . $img['dir'];
            }

            break;
        }
    }

    $infoPath = "/home/tsgsites/public_html/micnights/images/" . $iPath . "/info.html" ;
 
    if( $iPath != '' && file_exists($infoPath) )
    {
        $info = file_get_contents($infoPath);
    }

    return  $info;
}
