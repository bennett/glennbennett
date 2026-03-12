<?php
//Set the Content Type
header('Content-type: image/jpeg');

// Create Image From Existing File
$jpg_image = imagecreatefromjpeg('../imgs/Cal-Event.jpg');

// Allocate A Color For The Text
$white = imagecolorallocate($jpg_image, 255, 255, 255);
$black = imagecolorallocate($jpg_image, 0, 0, 0);

// Set Path to Font File
$font_path = '../GEORGIA.TTF';

// Set Text to Be Printed On Image
$text = "This is a sunset!";

// Print Text On Image
imagettftext($jpg_image, 36, 0, 475, 300, $black, $font_path, $text);

// Send Image to Browser
imagejpeg($jpg_image);

// Clear Memory
imagedestroy($jpg_image);
?>