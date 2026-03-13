<?php
// Redirect to CodeIgniter controller for calendar image generation
$params = $_SERVER['QUERY_STRING'];
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
$base = rtrim(dirname($base), '/'); // go up from /gcal/ to root
header('Location: ' . $base . '/cal-image?' . $params, true, 302);
exit;
?>
