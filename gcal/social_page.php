<?php

date_default_timezone_set('America/Los_Angeles');
//Set the Content Type

$im = @imagecreatefromjpeg('../imgs/Cal-Event.jpg');

// Create some colors
$white = imagecolorallocate($im, 255, 255, 255);
$grey = imagecolorallocate($im, 128, 128, 128);
$black = imagecolorallocate($im, 0, 0, 0);
//imagefilledrectangle($im, 0, 0, 399, 29, $white);

// The text to draw
$week_day = "";
$date = "";
$time= "";
$title = "";
$location = "";


$a = $_GET['a'];

$json = base64_decode($a);

$values = json_decode($json);
//var_dump($values);

foreach ($values as $key => $value)
{
    $$key = $value;
}

//$week_day = "Monday";
$week_day = date( "l", $start_date) ;
//$date = "Monday - July 11, 2022";
$date = date( "l - F d", $start_date );
//$time = "5:30 pm - 8:00 pm";
$time = date("g:i a", $start_date) . " - " . date("g:i a", $end_date);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<!--meta property="fb:app_id"             content="501470923386393" />
<meta property="og:url"                content="http://www.glennbennett.com/cal" />
<meta property="og:type"               content="article" />
<meta property="og:title"              content="Performing @ <?php echo $summary; ?>" />
<meta property="og:description"        content="<?php echo $date; ?> - <?php echo $time; ?>" /-->
<meta property="og:image"              content="https://glennbennett.com/gcal/cal_image.php?a=<?php echo $a; ?>" /> 
  
    
    
    <title><?php echo $summary; ?></title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://cdn.jsdelivr.net/npm/html5shiv@3.7.3/dist/html5shiv.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/respond.js@1.4.2/dest/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <h1><?php echo $summary; ?></h1>
    <h4><?php echo $date; ?> - <?php echo $time; ?></h4>
    <img width="500" height="500" src="https://glennbennett.com/gcal/cal_image.php?a=<?php echo $a; ?>" alt="Italian Trulli">

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>