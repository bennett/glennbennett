<?php
// Error logging configuration
ini_set('log_errors', 'On');
ini_set('error_log', __DIR__ . '/error_log.txt');
error_reporting(E_ALL);

// Include the required libraries
require 'ICal.php';
require 'Event.php';

use ICal\ICal;

$icsUrls = [
    'https://calendar.google.com/calendar/ical/gbennett%40tsgdev.com/private-61ab22cc7e10980c9bd2cab69790fd1a/basic.ics',
    'https://calendar.google.com/calendar/ical/c_8oqt9e7bms1sefskr0fl01r7tg%40group.calendar.google.com/public/basic.ics',
    'https://calendar.google.com/calendar/ical/c_4nlj0ar1kfsohjpaf6fbmti160%40group.calendar.google.com/private-a10312d2b47cd2c3f1be907aa979ecd5/basic.ics',
    // Add more ICS feed URLs as needed
];

$colors = [
    '#ff9999', // Color for the first calendar
    '#66b3ff', // Color for the second calendar
    '#99ff99', // Color for the third calendar
    // Add more colors as needed
];

$timezone = new DateTimeZone('America/Los_Angeles');

$events = [];
foreach ($icsUrls as $url) {
    try {
        $ical = new ICal($url);
        // Ensure this is the correct method based on library documentation
        $icalEvents = $ical->eventsFromRange(
            (new DateTime())->format('Y-m-d'), // Start date (today)
            (new DateTime('+7 days'))->format('Y-m-d') // End date (7 days from today)
        );

        foreach ($icalEvents as $icalEvent) {
            try {
                $start = new DateTime($icalEvent['dtstart']);
                $start->setTimezone($timezone);

                $end = new DateTime($icalEvent['dtend']);
                $end->setTimezone($timezone);

                $events[] = [
                    'summary' => $icalEvent['summary'],
                    'start' => $start,
                    'end' => $end,
                    'color' => $colors[array_rand($colors)] // Random color for simplicity
                ];
            } catch (Exception $e) {
                error_log("Error processing event from URL $url: " . $e->getMessage());
            }
        }
    } catch (Exception $e) {
        error_log("Error loading ICS feed from URL $url: " . $e->getMessage());
    }
}

// Group events by date
$groupedEvents = [];
foreach ($events as $event) {
    $eventDate = $event['start']->format('Y-m-d');
    if (!isset($groupedEvents[$eventDate])) {
        $groupedEvents[$eventDate] = [];
    }
    $groupedEvents[$eventDate][] = $event;
}

$startDate = isset($_GET['start_date']) ? new DateTime($_GET['start_date']) : new DateTime();
$endDate = (clone $startDate)->modify("+7 days");
$nextStartDate = (clone $endDate)->modify('+1 day');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Calendar Events</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .event-bar {
            width: 5px;
            height: 100%;
            position: absolute;
            left: 0;
            top: 0;
            background-color: #cccccc; /* Default color */
        }
        .event-card {
            position: relative;
            padding-left: 10px; /* Increased padding to fit the color bar */
            margin-bottom: 10px;
        }
        .event-card .card-body {
            padding-left: 10px; /* Align with padding of the card */
        }
        .event-date {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .event-time {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="my-4">Google Calendar Events (Los Angeles Time)</h1>
    <?php if (empty($events)): ?>
        <div class="alert alert-info">No events found for the next 7 days.</div>
    <?php else: ?>
        <?php foreach ($groupedEvents as $date => $eventsOnDate): ?>
            <?php
            $dateTime = new DateTime($date);
            ?>
            <div class="event-date"><?= $dateTime->format('l, M j') ?></div>
            <?php foreach ($eventsOnDate as $event): ?>
                <div class="card event-card">
                    <div class="event-bar" style="background-color: <?= htmlspecialchars($event['color']) ?>;"></div>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($event['summary']) ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted event-time">
                            <?= $event['start']->format('g:ia') ?> - <?= $event['end']->format('g:ia') ?>
                        </h6>
                        <?php if (!empty($event['location'])): ?>
                            <p class="card-text"><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($event['description'])): ?>
                            <p class="card-text"><?= nl2br(htmlspecialchars($event['description'])) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
        <div class="text-center mt-4">
            <a href="?start_date=<?= $nextStartDate->format('Y-m-d') ?>" class="btn btn-primary">Next 7 Days</a>
        </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

