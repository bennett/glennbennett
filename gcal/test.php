<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");
error_log( "Hello, errors!" );

require 'libs/CalendarLibrary.php';

// Example usage:
$cals = [
    [
        'url'  => 'https://calendar.google.com/calendar/ical/gbennett%40tsgdev.com/private-61ab22cc7e10980c9bd2cab69790fd1a/basic.ics',    
        'name' => 'my-cal',
    ],
    [
        'url'  => 'https://calendar.google.com/calendar/ical/c_8oqt9e7bms1sefskr0fl01r7tg%40group.calendar.google.com/public/basic.ics',    
        'name' => 'perform',
    ]   
];

$calendarLibrary = new CalendarLibrary($cals, '2024-08-30', '2024-08-30');
$events = $calendarLibrary->getEvents();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Calendar Events</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Calendar Events</h2>
        <div class="row">
            <?php foreach ($events as $event): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= $event['HTML_Subject'] ?></h5>
                            <p class="card-text"><strong>Start:</strong> <?= htmlspecialchars($event['Start_Date']) ?> <?= htmlspecialchars($event['Start_Time']) ?></p>
                            <p class="card-text"><strong>End:</strong> <?= htmlspecialchars($event['End_Date']) ?> <?= htmlspecialchars($event['End_Time']) ?></p>
                            <p class="card-text"><strong>Description:</strong> <?= $event['HTML_Description'] ?></p>
                            <p class="card-text"><strong>Location:</strong> <?= $event['HTML_Location'] ?></p>
                            <p class="card-text"><strong>Calendar:</strong> <?= htmlspecialchars($event['Calendar_Name']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
