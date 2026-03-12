<?php
require 'libs/ICal.php';
require 'libs/Event.php';

use ICal\ICal;

class GCalReader {
    private $icsUrls;
    private $targetTimezone;

    public function __construct($icsUrls, $targetTimezone = 'America/Los_Angeles') {
        $this->icsUrls = $icsUrls;
        $this->targetTimezone = $targetTimezone;
    }

    public function getEvents($startDate, $endDate) {
        $allEvents = [];

        foreach ($this->icsUrls as $icsUrl) {
            try {
                $ical = new ICal($icsUrl);
                $events = $ical->eventsFromRange($startDate, $endDate);

                foreach ($events as $event) {
                    $start = new DateTime($event->dtstart, new DateTimeZone($ical->calendarTimeZone()));
                    $start->setTimezone(new DateTimeZone($this->targetTimezone));

                    $end = new DateTime($event->dtend, new DateTimeZone($ical->calendarTimeZone()));
                    $end->setTimezone(new DateTimeZone($this->targetTimezone));

                    $allEvents[] = [
                        'summary' => $event->summary,
                        'start' => $start,
                        'end' => $end,
                        'location' => $event->location,
                        'description' => $event->description,
                        'url' => $icsUrl
                    ];
                }
            } catch (\Exception $e) {
                echo "Error fetching calendar from $icsUrl: " . $e->getMessage() . "\n";
            }
        }

        usort($allEvents, function($a, $b) {
            return $a['start'] <=> $b['start'];
        });

        return $allEvents;
    }
}
?>

