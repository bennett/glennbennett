<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$lines = file('performance.txt');

$i = 0;
$a = 0;
foreach($lines as $line) {
//  echo $line . "<br>";
  $new_line = substr($line, 4);
  $by_pos = strrpos($new_line, " by ");
  $in_pos = strrpos($new_line, " in ");
//  echo "strlen:    " .strlen($new_line) . "<br>";
//  echo "By pos:    " . $by_pos . "<br>";
//  echo "Title pos: " . (strlen($new_line) - $by_pos) . "<br>";
  $title = substr($new_line,  0, -1 * (strlen($new_line) - $by_pos) );
  $artists = substr($new_line,  $by_pos + 3, -1 * (strlen($new_line) - $in_pos) );
//  echo $new_line . "<br>";
//  echo $title . "<br>";
//  echo $artist . "<br>";
  $songs[$i]['line'] = $new_line;
  $songs[$i]['title'] = $title;
  $songs[$i]['artists'] = explode(";", $artists); 
  foreach($songs[$i]['artists'] as $artist)
  {
    $artist_name = trim($artist);
    $artists_list[$artist_name]['songs'][] = $title;
  }
  $i++;
}


?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Genn Bennett - Performance List</title>
    
<style>    
    @media print {
      hr {page-break-after: always;}
    }
</style>

  </head>
  <body>
  <div class="container">

<div class="row align-items-start">
<div class="col">
<?php
$i = 0;
$c = 0;
foreach($songs as $song)
{
    echo "<strong>" . $song['title'] . "</strong><br><small>"; 
    foreach($song['artists'] as $artist)
    {
        echo  $artist . ";";
    }
    echo "</small><br>";

    if( $i == 16)
    {
        echo '</div>';
        $c++;
        if($c == 3)
        {
            echo '</div><hr><div class="row align-items-start">';
            $c = 0;
        }
        echo '<div class="col">';

        $i=0;

    }
    $i++;
}


?>

</div>
</div>
<hr>
<div class="row align-items-start">
<div class="col">
<?php

$line_count = 0;
$col_count = 0;
$a_list = ksort($artists_list );
foreach($artists_list as $artist => $songs)
{
    display_line( "<strong>" . $artist . "</strong><br>", false );
    $songs_list =  $songs['songs'];
    
    $col_length = 36;
    
    foreach($songs['songs'] as $song)
    {
        display_line( "<small>" . $song . "</small><br>", true );        
    }

}


function display_line($line, $break)
{
    global $line_count;
    global $col_count;
    
    echo $line;
    if($break)
    {
        if( $line_count > 20)
        {
            echo '</div>';
            $col_count++;
            if($col_count == 3)
            {
                echo '</div><hr><div class="row align-items-start">';
                $col_count = 0;
            }
            echo '<div class="col">';
    
            $line_count=0;
    
        }
        $line_count++;
    }
    
}    
?>
</div>
</div>

    </div>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>