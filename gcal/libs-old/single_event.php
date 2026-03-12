<?php

function display_single_event($formatted_events, $id)
{

    $eventno = 0; // used by html 'display_formated_listing'

    // Display Verified
    foreach ($formatted_events as $formatted_event)
    {;
        
        if($formatted_event['uid'] == $id)
        {
            display_rich($formatted_event);
            display_single_listing($formatted_event, $eventno, false);
        }

        $eventno++;

    }

    echo "  </div>";
    echo "</div>";


}

function display_rich($formatted_event)
{
    extract($formatted_event);

    if($formatted_event['Status'] == "Dark" )
    {
        $image = "dark.jpg";
        $summary = "Canceled - " . $formatted_event['summary'];
    }
    else
    {
        $image = $formatted_event['image'];
        $summary = $formatted_event['summary'];
    }

    

    $summary = addslashes(strip_tags($summary));
    $description = addslashes(strip_tags($description));
    
    $StartDate = date( "Y-m-dTh:i", $start_date);
    $EndDate = date( "Y-m-dTh:i", $end_date);
    $location_array = explode(",", $location);
    $location_name = $location_array[0];
    $streetAddress = $location_array[1];
    $addressLocality = $location_array[2];
    $StateZip =  explode(" ", trim($location_array[3]));
    $postalCode = $StateZip[1];
    $addressRegion = $StateZip[0];
    

    echo "<script type=\"application/ld+json\">
    {
      \"@context\": \"https://schema.org\",
      \"@type\": \"Event\",
      \"name\": \"$summary\",
      \"startDate\": \"$StartDate\",
      \"endDate\": \"$EndDate\",
      \"location\": {
        \"@type\": \"Place\",
        \"name\": \"$location_name\",
        \"address\": {
          \"@type\": \"PostalAddress\",
          \"streetAddress\": \"$streetAddress\",
          \"addressLocality\": \"$addressLocality\",
          \"postalCode\": \"$postalCode\",
          \"addressRegion\": \"$addressRegion\",
          \"addressCountry\": \"US\"
        }
      },
      \"image\": [
        \"http://micnights.com/images/$image\"
       ],
      \"description\": \"$description\"
      
    }
    </script>";

}

