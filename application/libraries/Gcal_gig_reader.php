<?php

//echo FCPATH;
require FCPATH . 'gcal/libs/' . 'class.iCalReader.php';

class Gcal_gig_reader
{

    private $cals;
    private $offsetHours;
    
    public function __construct()
    {
        
//        date_default_timezone_set('America/Los_Angeles');
        
    }
    
    public function set_cals($cals)
    {
        $this->cals = $cals;
    }
    
    /********************************
     *  get_events
     *  
     *  Gets formated event Data
     *  
     */                       
    public function get_events($start_date, $end_date)
    {
        return $this->get_cal_data($start_date, $end_date);
    }

    function displayReadableDateInPDT($icalDate) {
        // Check if the date string ends with 'Z'
        $isUTC = substr($icalDate, -1) === 'Z';
        
        // Create a DateTime object from the iCal date string
        if ($isUTC) {
            $dateTime = DateTime::createFromFormat('Ymd\THis\Z', $icalDate, new DateTimeZone('UTC'));
        } else {
            $dateTime = DateTime::createFromFormat('Ymd\THis', $icalDate, new DateTimeZone('UTC'));
        }
        
        // Check if the date was parsed correctly
        if ($dateTime === false) {
            // Get the last errors
            $errors = DateTime::getLastErrors();
            return "Invalid iCal date format. Errors: " . implode(', ', $errors['errors']);
        }
        
        // Convert the time to the default time zone (Los Angeles)
        $dateTime->setTimezone(new DateTimeZone(date_default_timezone_get()));
        
        // Format the date in a more readable format
        return $dateTime->format('F j, Y, g:i A T');
    }
    
    private function get_cal_data($start_date, $end_date)
    {
        
        $complete_events = array();
        
        foreach($this->cals as $cal)
        {
            // This does not really do anything
            $s_date = new DateTime("@$start_date");
            $e_date = new DateTime("@$end_date");
            $cal_url = $cal['url'] . "?timeMin=" . $s_date->format('Y-m-d\TH:i:s\Z') . "&timeMax=" . $e_date->format('Y-m-d\TH:i:s\Z');
//            echo $cal_url . "<br>";
            $cal['ical'] = new ICal($cal_url);
            $events = $cal['ical']->eventsFromRange($start_date, $end_date);
//            var_dump($events);
            $parsed_events = $this->parse_events($events, $cal);
            $complete_events = array_merge($complete_events, $parsed_events);
        }

/*        
        foreach($complete_events as $event)
        {
 //           var_dump($event);
            echo $event['summary'];
            echo "<br>";
            echo $event['DTSTART'];
//            echo $this->displayReadableDateInPDT('20240803T101500');
            echo "<br>";
            echo $this->displayReadableDateInPDT($event['DTSTART']);
            echo "<br>";
            echo $event['DTEND'];
            echo "<br>";
            echo $this->displayReadableDateInPDT($event['DTSTART']);            
            echo "<hr>";
        }
*/        
        usort($complete_events, array('Gcal_gig_reader','date_compare') );
        
//        var_dump($complete_events); 
        
        return $complete_events;
    }

    /********************************
     *  Utility Functions 
     ********************************/                       


    /*********************************
     *  parse_events
     *  
     *  Convert cal data to php data
     *  
     */
                         
    function parse_events($events, $cal)
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
//                break;
            }
            
            $uids[] = $event['UID'];
            
            if(!isset($event['SUMMARY'])) $event['SUMMARY'] = '';
            if(!isset($event['DESCRIPTION'])) $event['DESCRIPTION'] = '';
            
            $summary_full = $this->string_cleaner($event['SUMMARY']);
            $summary = str_replace("(", "<br />(", $summary_full);
            
            
            $description = $this->string_cleaner($event['DESCRIPTION']);
 
          
            $lines = explode("<br />",$description);
          
            $status='';
            foreach ($lines as $line) {
//                echo $line . "<br>";
                if (preg_match('/^Status:\s*(.*)$/', $line, $matches)) { // Use regex to match the line starting with "Status:"
                    $status = trim($matches[1]); // Get the string after "Status:" with any surrounding spaces
                    break; // Stop the loop after finding the first matching line
                }
                
               
            }
            $status = $this->truncateString($status);
//            echo $status . "<br>";
            $formatted_event['status'] = $status;
            
            
            if($cal['name'] == 'canceled')
            {
                $formatted_event['status'] = 'Canceled';
            }
            
    //        echo "summary: " . $summary . "<br>";
            
    //        $formatted_event = get_fields($description, $fields);
            
            $formatted_event['summary'] = $summary;
            $formatted_event['description'] = $description;
            
            // Set start and end dates
            $UTC =  substr($event['DTSTART'], 15, 16);
            
            $start_date = $cal['ical']->iCalDateToUnixTimestamp($event['DTSTART']);
            $end_date = $cal['ical']->iCalDateToUnixTimestamp($event['DTEND']);
            
            if($UTC == 'Z')
            {
                
                if(date('I',$start_date))
                {
                    $start_date -= 60 * 60 * 7;
                } 
                else
                {
                    $start_date -= 60 * 60 * 8;
                }
                
                if(date('I',$end_date))
                {
                    $end_date -= 60 * 60 * 7;
                } 
                else
                {
                    $end_date -= 60 * 60 * 8;
                }
                
            }
            
            
            $display_date = date("D M j", $start_date);
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
            
            $formatted_event['DTSTART']      = $event['DTSTART'];
            $formatted_event['DTEND']        = $event['DTEND'];
            $formatted_event['UID']          = $event['UID'];
            $formatted_event['start_date']   = $start_date;
            $formatted_event['end_date']     = $end_date;
            $formatted_event['cal_name']     = $cal['name'];
            $formatted_event['display_date'] = $display_date;
            $formatted_event['display_date_time'] = $display_date_time;

            $formatted_event['uid']          = substr($event['UID'], 0, strpos($event['UID'], "@"));
            
            // setup location
            
            // Set Default
            $formatted_event['location'] = "";
            $formatted_event['driving_link'] = "";
            $add_to_cal_link = "";
            
            if(isset($event['LOCATION']))
            {
              $location = $this->string_cleaner($event['LOCATION']);
            
              $driving_link = "http://maps.google.com/maps?daddr=" . urlencode($location);
            
              $formatted_event['location']     = $location;
              $formatted_event['driving_link'] = $driving_link;
              $add_to_cal_link = "https://calendar.google.com/calendar/r/eventedit";
              $add_to_cal_link .= "?text=" . urlencode("Glenn Bennett @ " . $summary);
              $add_to_cal_link .= "&dates=" . $event['DTSTART'];
              $add_to_cal_link .= "/" . $event['DTEND'];
              //     $add_to_cal_link .= "&details=" . urlencode($display_date_time);
              $add_to_cal_link .= "&location=" . urlencode($location);
            }
            
            $formatted_event['add_to_cal_link'] = $add_to_cal_link;
            
            $formatted_events[] = $formatted_event;
          
    
          
        }
    //    echo "<hr>";
    //    echo "Formatted: " . count($formatted_events);
    //    echo "<hr>";
        return  $formatted_events;
    }

    function string_cleaner($str)
    {
        $str = str_replace("\\n", "<br />", $str);
        $str = preg_replace('/\\\\/', '', $str);
        $str = str_replace('&lt;', '<', $str);
        $str = str_replace('&gt;', '>', $str);
        
        
        return  $str;
    }

    
    private static function date_compare($a, $b)
    {
        $t1 = $a['start_date'];
        $t2 = $b['start_date'];
        return $t1 - $t2;
    
       return ($t1 < $t2) ? -1 : 1;
    }
    
    
    function truncateString($string) {
        $parts = preg_split('/\s/u', $string, -1, PREG_SPLIT_OFFSET_CAPTURE);
//        var_dump($parts);
        if (count($parts) > 1) {
            $firstWhiteSpaceOffset = $parts[1][1];
            return mb_substr($string, 0, $firstWhiteSpaceOffset);
        }
        return $string;
    }
    

}
