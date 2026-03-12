<?php

date_default_timezone_set('America/Los_Angeles');

$week_day = "";
$date = "";
$time= "";
$title = "";
$location = "";
$st = 0;
$et = 0


foreach ($_GET as $key => $value) 
{
    $$key = $value; 
}
if( $st != 0 && $et != 0)
{
    //$week_day = "Monday";
    $week_day = date( "l", $st) ;
    //$date = "Monday - July 11, 2022";
    $date = date( "l - F d", $st );
    //$time = "5:30 pm - 8:00 pm";
    $time = date("g:i a", $st) . " - " . date("g:i a", $et);
}

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<meta property="og:title" content="Glenn Bennett Performs"/>
<meta property="og:image" content="https://glennbennett.com/gcal/cal-img.php?<?php echo $_SERVER['QUERY_STRING']; ?>"/>
<meta property="og:url" content="https://glennbennett.com/gcal/cal-page.php"/>
<meta property="og:type" content="website"/>
<meta property="og:description" content="<?php echo $title; ?>"/>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Glenn Bennett | Event</title>
  </head>
  <body>
    <h1>Hello, world!</h1>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>



z