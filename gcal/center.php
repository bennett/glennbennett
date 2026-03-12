<?php
//Set the Content Type
header('Content-type: image/jpeg');
$im = @imagecreatefromjpeg('../imgs/Cal-Event.jpg');

// Create some colors
$white = imagecolorallocate($im, 255, 255, 255);
$grey = imagecolorallocate($im, 128, 128, 128);
$black = imagecolorallocate($im, 0, 0, 0);
//imagefilledrectangle($im, 0, 0, 399, 29, $white);

// The text to draw
$week_day = "Monday";
$date = "Monday - July 11, 2022";
$time= "5:30 pm - 8:00 pm";
$venue = "THE ALLEY @ ENEGREN BREWERY, MOORPARK";
$location = "The Alley, 444 Zachary St #120, Moorpark, CA 93021";


$msg_text_main = [
    [
      'text'      => $week_day,
      'font'      => '../fonts/GEORGIA.TTF',
      'font_size' => 48
    ],
    [
      'text'      => $time,
      'font'      => '../fonts/GEORGIA.TTF',
      'font_size' => 24
    ],
    [
      'text'      => $venue,
      'font'      => '../fonts/GEORGIA.TTF',
      'font_size' => 36
    ],
];

$msg_text_sub = [
    [
      'text'     => $date,
      'font'      => '../fonts/GEORGIA.TTF',
      'font_size' => 26
    ],
    [
      'text'      => $time,
      'font'      => '../fonts/GEORGIA.TTF',
      'font_size' => 24
    ],
    [
      'text'      => $location,
      'font'      => '../fonts/GEORGIA.TTF',
      'font_size' => 18
    ]
];

$msg_text_tag = [
    [
      'text'      => "Keep up to date: GlennBennett.com/cal",
      'font'      => '../fonts/GEORGIA.TTF',
      'font_size' => 14
    ]
];



// Get image Width and Height
$image_width = imagesx($im);
$image_height = imagesy($im);

$x = 400;
$y = 120;

foreach( $msg_text_main as $msg )
{

    print_line($msg, 130, $black);

}

$y = 420;

foreach( $msg_text_sub as $msg )
{

    set_type($msg, 255, $black);

}

$y = 580;

foreach( $msg_text_tag as $msg )
{

    set_type($msg, 255, $black);

}

function print_line($msg, $offset, $black)
{
    $lines = get_lines( $msg['text'] );
    
    foreach($lines as $line)
    {
        $msg["text"] = $line;
        set_type($msg, $offset, $black);
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
    $font_size = $msg['font_size'];;

    // Get Bounding Box Size
    $text_box = imagettfbbox($font_size,0,$font,$text);

    // Get your Text Width and Height
    $text_width = $text_box[2]-$text_box[0];
    $text_height = $text_box[7]-$text_box[1];

    // Calculate coordinates of the text
    $x = ($image_width/2) - ($text_width/2);
//    $y = ($image_height/2) - ($text_height/2);

    $x = $x + $offset;
    $y = $y - $text_height + 15;

    // Add some shadow to the text
    //imagettftext($im, $font_size, 0, $x, $y+1, $grey, $font, $text);

    // Add the text
    imagettftext($im, $font_size, 0, $x, $y, $color, $font, $text);    
    
}
?>