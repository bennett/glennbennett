<?php

function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
}


/*

TODO: Colapse these functions in to one

*/

function get_category($description)
{
    return get_field($description, "Cat");    
}

function strip_category($description)
{
  
    return strip_field($description, "Cat");
}

function get_status($description)
{
    return get_field($description, "Status");
}

function strip_status($description)
{
    return strip_field($description, "Status");
}

function get_image_field($description)
{
    return get_field($description, "Image");
}

function strip_image_field($description)
{
    return strip_field($description, "Image");
}

function get_field($description, $field_name)
{
    $f_name = $field_name . ":";
    $f_name_len = strlen($f_name);
    $field_data = "";    
    
    
    $lines = explode("<br />",$description); 
    
    foreach($lines as $line) {
      $trim_line = trim($line);
 //     echo $f_name_len , " Len " .$f_name , " Lines: " . substr($trim_line, 0, $f_name_len) . "<br>";
 //     var_dump(substr($line, 0, $f_name_len));
      if(substr($trim_line, 0, $f_name_len) == $f_name ) 
      {
        $field_data = substr($trim_line, $f_name_len );
//        echo "Bingo: " . $field_name . ": " . $field_data . "<br>"; 
        break;        
      }
    }
    

//    echo "Description: " . substr($description, 0, 30 ) . "<br>";
//    echo $field_name . ": " . $field_data . "<hr>";

    return trim($field_data);
}


function strip_field($description, $field_name)
{
    $f_name = $field_name . ":";
    $f_name_len = strlen($f_name);
    
//    var_dump($description);
    
    $lines = explode("<br />",$description);
    
//    var_dump($lines);
    
//    echo "<hr>";
    
    foreach($lines as $line) {
/*
      echo "line:" . $line;
      echo "<hr>";
      echo "line:" . substr($line, 0, $f_name_len);
      echo "<hr>";
      echo "f_name_len:" . $f_name_len;
      echo "<hr>";
      echo "f_name:" . $f_name;
      echo "<hr>";          
*/
      if(substr($line, 0, $f_name_len) != $f_name ) 
      {
          $output[] = $line . "<br />";
      }
/*
      else
      {
          echo "line:" . $line;
          echo "<hr>";
      }
*/
    }
    
    $out = implode("\n",$output);
    
    return $out;
}




?>
