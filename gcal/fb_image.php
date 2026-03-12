<?php
date_default_timezone_set('America/Los_Angeles');
//Set the Content Type
header('Content-type: image/jpeg');

// Get parameters directly from URL
$start_date = $_GET['start_date'] ?? null;
$end_date = $_GET['end_date'] ?? null;
$summary = isset($_GET['summary']) ? urldecode($_GET['summary']) : null;

$location = isset($_GET['location']) ? urldecode($_GET['location']) : null;
$forced_image = isset($_GET['image_no']) ? (int)$_GET['image_no'] : null;

if (!$start_date || !$end_date || !$summary) {
    die('Missing parameters');
}

// The text to draw
$week_day = date("l", $start_date);
$date = date("l - M d", $start_date);
$time = date("g:i a", $start_date) . " - " . date("g:i a", $end_date);

$font = '../fonts/GEORGIAB.TTF';

// Current Number of background images
$no_of_bg_images = 25;

// Use forced image number if provided, otherwise use date calculation
if ($forced_image !== null) {
    $image_no = max(0, min($forced_image, $no_of_bg_images - 1));
} else {
    $image_no = date("j", $start_date);
    $image_no = ($image_no % ($no_of_bg_images-1)) - 1;
    if ($image_no < 0) $image_no = 0;
}

$img_file = '../imgs/Cal-Event-' . $image_no . '.jpg';

$im = @imagecreatefromjpeg($img_file);
if (!$im) {
    die('Could not load background image: ' . $img_file);
}

// Create some colors
$white = imagecolorallocate($im, 255, 255, 255);
$grey = imagecolorallocate($im, 128, 128, 128);
$black = imagecolorallocate($im, 0, 0, 0);

// Setup top section
$msg_text_main = [
    [
        'text'      => $summary,
        'font'      => $font,
        'font_size' => 36
    ],    
    [
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
/*
foreach($msg_text_main as $msg) {
    print_line($msg, -200, $black);
}
*/
foreach($msg_text_main as $msg) {
    $text = $msg['text'];
    if (strlen($text) > 30) {
        $middle = strlen($text) / 2;
        $spaces = [];
        
        // Find all space positions
        for ($i = 0; $i < strlen($text); $i++) {
            if ($text[$i] == ' ') {
                $spaces[] = $i;
            }
        }
        
        if (!empty($spaces)) {
            // Find space closest to middle
            $best_space = $spaces[0];
            foreach ($spaces as $space_pos) {
                if (abs($space_pos - $middle) < abs($best_space - $middle)) {
                    $best_space = $space_pos;
                }
            }
            
            // Split at the best space
            $first_half = substr($text, 0, $best_space);
            $second_half = substr($text, $best_space + 1);
            
            $msg_copy1 = $msg;
            $msg_copy1['text'] = $first_half;
            print_line($msg_copy1, -200, $black);
            
            $msg_copy2 = $msg;
            $msg_copy2['text'] = $second_half;
            print_line($msg_copy2, -200, $black);
        } else {
            // No spaces found, print as is
            print_line($msg, -200, $black);
        }
    } else {
        // Short enough, print as is
        print_line($msg, -200, $black);
    }
}
$y = $y + 20;

if($location) {
    $msg_text = [    
        'text'      => $location,
        'font'      => '../fonts/GEORGIA.TTF',
        'font_size' => 24
    ];
    print_line($msg_text, -200, $black);
}

function print_line($msg, $offset, $color) {
    $lines = get_lines($msg['text']);
    foreach($lines as $line) {
        $msg["text"] = $line;
        set_type($msg, $offset, $color);
    }
}

function get_lines($text) {
    $pos = strpos($text, ",");
    if ($pos === false) {
        $lines[] = $text;
    } else {
        $lines[] = substr($text, 0, $pos);
        $lines[] = substr($text, $pos+1);
    }
    return $lines;
}

function set_type($msg, $offset, $color) {
    global $im, $x, $y, $image_width, $image_height, $text_box;
    
    $text = $msg['text'];
    $font = $msg['font'];
    $font_size = $msg['font_size'];

    // Get Bounding Box Size
    $text_box = imagettfbbox($font_size, 0, $font, $text);

    // Get your Text Width and Height
    $text_width = $text_box[2]-$text_box[0];
    $text_height = $text_box[7]-$text_box[1];

    // Calculate coordinates of the text
    $x = ($image_width/2) - ($text_width/2);
    $x = $x + $offset;
    $y = $y - $text_height + 10;

    // Add the text
    imagettftext($im, $font_size, 0, $x, $y, $color, $font, $text);    
}

// Output the image
imagepng($im);
imagedestroy($im);
?>
