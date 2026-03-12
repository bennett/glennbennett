<?php

date_default_timezone_set('America/Los_Angeles');
//Set the Content Type
header('Content-type: image/jpeg');


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

/*
echo $start_date . "<br>";
echo $end_date . "<br>";
echo $location . "<br>";
echo $summary . "<br>";
*/
//$week_day = "Monday";
$week_day = date( "l", $start_date) ;

// Current Number of background images
$no_of_bg_images = 25;
//$image_no = "1";
$image_no = date( "j", $start_date) ;
$image_no = ($image_no % ($no_of_bg_images-1)) - 1;

//$date = "Monday - July 11, 2022";
$date = date( "l - M d", $start_date );
//$time = "5:30 pm - 8:00 pm";
$time = date("g:i a", $start_date) . " - " . date("g:i a", $end_date);

$font = '../fonts/GEORGIAB.TTF';

$img_file = '../imgs/Cal-Event-' . $image_no . '.jpg';

//echo $img_file;



$im = @imagecreatefromjpeg($img_file);


//$im = @imagecreatefromjpeg('../imgs/Cal-Event.jpg');

// Create some colors
$white = imagecolorallocate($im, 255, 255, 255);
$grey = imagecolorallocate($im, 128, 128, 128);
$black = imagecolorallocate($im, 0, 0, 0);
//imagefilledrectangle($im, 0, 0, 399, 29, $white);

// setup top section
$msg_text_main = [
    [
      'text'      => $summary,
      'font'      => $font,
      'font_size' => 36
    ],    [
      'text'      => $date,
      'font'      => $font,
      'font_size' => 24
    ],
    [
      'text'      => ' ',
      'font'      => $font,
      'font_size' => 18
    ],
    [
      'text'      => $time,
      'font'      => $font,
      'font_size' => 36
    ]

];



// Get image Width and Height
$image_width = imagesx($im);
$image_height = imagesy($im);

$y = 260; // from top

// Print Main Text
foreach( $msg_text_main as $msg )
{
    print_line($msg, -200, $black);
}

$y = $y + 20;

//$str_arr = explode (",", $location); 


    $msg_text = [    
      'text'      => $location,
      'font'      => '../fonts/GEORGIA.TTF',
      'font_size' => 24
    ];
    print_line($msg_text, -200, $black);



//$y = $y + 10;

$msg_text = [    
  'text'      => $date,
  'font'      => '../fonts/GEORGIA.TTF',
  'font_size' => 24
];

//print_line($msg_text, -200, $black);

function print_line($msg, $offset, $color)
{
    $lines = get_lines( $msg['text'] );
    
    foreach($lines as $line)
    {
        $msg["text"] = $line;
        set_type($msg, $offset, $color);
    }
    
}

function get_lines( $text )
{
    $pos = strpos($text, ",");
    if ($pos === false) {
        $lines[] = $text;
    } else {
        $lines[] = substr($text,0,$pos);
        $lines[] = substr($text,$pos+1);
    }
    if($text)
    
    
    return $lines;
}



//$uploads_dir = 'uploaded_files/';
// Replace path by your own font path


// Using imagepng() results in clearer text compared with imagejpeg()
//imagepng($im);
imagepng($im,$name,9);
imagedestroy($im);


function set_type($msg, $offset, $color)
{
    global $im, $x, $y, $image_width, $image_height, $text_box;
    
    $text = $msg['text'];
    $font = $msg['font'];
    $font_size = $msg['font_size'];

    // Get Bounding Box Size
    $text_box = imagettfbbox($font_size,0,$font,$text);

    // Get your Text Width and Height
    $text_width = $text_box[2]-$text_box[0];
    $text_height = $text_box[7]-$text_box[1];

    // Calculate coordinates of the text
    $x = ($image_width/2) - ($text_width/2);
//    $y = ($image_height/2) - ($text_height/2);

    $x = $x + $offset;
    $y = $y - $text_height + 10;

    // Add some shadow to the text
    //imagettftext($im, $font_size, 0, $x, $y+1, $grey, $font, $text);

    // Add the text
    imagettftext($im, $font_size, 0, $x, $y, $color, $font, $text);    
    
}
?>
