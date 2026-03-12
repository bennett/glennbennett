<?php

function get_fields($description, $fields)
{ 
    $new_description = '';
    
    $new_formated_event = $fields;
    
    $lines = explode("<br />",$description); 
    
    foreach($lines as $line) 
    {
      $trim_line = trim($line);

      $status = find_field($trim_line, $fields);
      
      if($status['found'] == true)
      {
        $new_formated_event[$status['field_name']] = $status['field_value'];
      }
      else
      {
        $new_description .= $trim_line . "<br />" ;
      }
    }
    
    $new_formated_event['description'] = $new_description;
    

    
    return $new_formated_event;
}

/**********************************************
 *
 *Check to see if the line is a field entry
 *
 */   
function find_field($line, $fields)
{
    $status['found'] = false;
    
    foreach($fields as $field)
    {
        $field_name = $field . ":";
        $field_name_len = strlen($field_name);
        $field_data = ""; 
        
        if(substr($line, 0, $field_name_len) == $field_name ) 
        {
          $field_data = trim(substr($line, $field_name_len ));
          
          $status['found']       = true;
          $status['field_name']  = $field;
          $status['field_value'] = $field_data;
          break;  //we found one      
        }
        
    }
    
    return $status;

}

