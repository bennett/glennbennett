<?php

require 'class.iCalReader.php';

class CalendarLibrary {
    private $calendars;
    private $startDate;
    private $endDate;
    private $timezone;

    public function __construct(array $calendars, $startDate, $endDate, $timezone = 'America/Los_Angeles') {
        $this->calendars = $calendars;
        $this->startDate = new DateTime($startDate, new DateTimeZone($timezone));
        $this->endDate = new DateTime($endDate, new DateTimeZone($timezone));
        $this->timezone = $timezone;
    }

    public function getEvents() {
        $events = [];
        
        foreach ($this->calendars as $calendar) {
            
            $ical = new ical($calendar['url']);
            
//            file_put_contents($calendar['name'] . ".log", print_r($ical->events(), true));
            
            foreach ($ical->events() as $event) {
                $eventStart = new DateTime($event['DTSTART']);
                $eventEnd = isset($event['DTEND']) ? new DateTime($event['DTEND']) : $eventStart; // Use event start time if end time is not set

                // Convert event times to the specified timezone
                $eventStart->setTimezone(new DateTimeZone($this->timezone));
                $eventEnd->setTimezone(new DateTimeZone($this->timezone));

                // Check if the event starts or ends on the specified date
                if ($eventStart->format('Y-m-d') == $this->startDate->format('Y-m-d') || $eventEnd->format('Y-m-d') == $this->endDate->format('Y-m-d')) {
                    $subject = $event['SUMMARY'];
                    $description = $event['DESCRIPTION'] ?? '';
                    $location = $event['LOCATION'] ?? '';

                    $events[] = [
                        'Subject' => $subject,
                        'Start_Date' => $eventStart->format('Y-m-d'),
                        'Start_Time' => $eventStart->format('H:i:s'),
                        'End_Date' => $eventEnd->format('Y-m-d'),
                        'End_Time' => $eventEnd->format('H:i:s'),
                        'Description' => $description,
                        'Location' => $location,
                        'Calendar_Name' => $calendar['name'],
                        'HTML_Subject' => $this->formatHtml($subject),
                        'HTML_Description' => $this->formatHtml($description),
                        'HTML_Location' => $this->formatHtml($location),
                        'ASCII_Subject' => $this->formatAscii($subject),
                        'ASCII_Description' => $this->formatAscii($description),
                        'GCAL_Description' => $this->formatForGoogleCalendarCSV($description),
                        
                        'ASCII_Location' => $this->formatAscii($location),
                        'Start_DateTime' => $eventStart,
                        'End_DateTime' => $eventEnd
                    ];
                }
            }
        }

        usort($events, function($a, $b) {
            return $a['Start_DateTime'] <=> $b['Start_DateTime'];
        });

        return $events;
    }
    
    private function formatForGoogleCalendarCSV($input) {
        // Replace \\n with actual newlines
        $formatted = str_replace("\\n", "**n", $input);
        
        $formatted = str_replace('\\', '', $formatted);
        
        $formatted = str_replace("**n", "\n", $formatted);
        
        // Escape double quotes by doubling them
        $formatted = str_replace('"', '""', $formatted);
        
        // Enclose the field in double quotes
//        $formatted = '"' . $formatted . '"';
        
        return $formatted;
    }    

    private function formatHtml($text) {
    
        // First, escape special HTML characters
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
            
        // Explicitly replace \\n with <br>
        $text = str_replace("\\n", "<br>", $text);
        
        // Explicitly replace \n with <br>
        $text = str_replace("\n", "<br>", $text);
        


        $text = str_replace('\\', '', $text);
        

        
        return $text;
    }

    private function formatAscii($text) {
        
        $ascii_newline = ord("\n");
        $text = str_replace("\\n", $ascii_newline, $text);
        $text = str_replace("\n", $ascii_newline, $text);
        
        $text = htmlspecialchars_decode($text, ENT_QUOTES);
        $text = str_replace('\\', '', $text); // Remove backslashes
        return $text;
    }
}

