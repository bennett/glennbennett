<?php


class weather_warnings
{


    private $hours = null;
    // Set your Visual Crossing Weather API key
    
    private $api_key = 'RMRVJEQCLJMHF2AFDQEWBXLSA';    
    
    public function __construct($location, $date)
    {
        $loc = urlencode($location);
        
        $request_url = "https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline/" .
        $loc .
        "/$date?unitGroup=us&key=" . 
        $this->api_key . 
        "&contentType=json";

        // Make the API request
//        $response = file_get_contents($request_url);
        

        try {
            $response = @file_get_contents($request_url);
            if($response === FALSE) {
                throw new Exception('Unable to fetch weather dat');
            }
        } catch (Exception $e) {
            echo 'Unable to fetch weather data: ',  $e->getMessage(), "\n";
        }

      

        // Parse the JSON response
        $data = $response ? json_decode($response, true) : null;

        if( $data && isset($data['days'][0]['hours']) )
        {
            $this->hours = $data['days'][0]['hours'];
        }
        
                
    }
    
    public function get_warnings($start_time, $end_time)
    {
        if (!isset($this->hours)) 
        {
            return null;
        }


        $conditions = "";
        $preciptype = "";
        $precipprob = 0;
        $windspeed = 0;
        $windgust = 0;
        $temp =0;
        
        $ret_string =  "";
        
        foreach($this->hours as $hour)
        {
//            echo $hour['datetimeEpoch'] . "<br>";

            
            if( ($hour['datetimeEpoch'] >= $start_time) && ($hour['datetimeEpoch'] <= $end_time) )
            {
                $conditions = $hour['conditions'];

                if( isset($hour['preciptype']) && is_array($hour['preciptype']) && $hour['preciptype'][0] == "rain")
                {
                    $preciptype = $hour['preciptype'][0];
                    
                    if($hour['precipprob'] > $precipprob)
                    {
                        $precipprob = $hour['precipprob'];
                    }
                }
                       
                if($hour['windspeed'] > $windspeed)
                {
                    $windspeed = $hour['windspeed'];
                }

                if($hour['windgust'] > $windgust)
                {
                    $windgust = $hour['windgust'];
                }                
 
                if($hour['temp'] > $temp)
                {
                    $temp = $hour['temp'];
                }                  
                 
//                echo "<br>bingo<pre>";
//                var_dump($hour);
//                echo "</pre>";
            }
            

  
 
        }
        
        if($precipprob > 10 || $windspeed > 13 || $temp > 95)
        {
                $ret_string = $conditions;
                $ret_string .= "<br>";
                if($preciptype == 'rain')
                {
                    $ret_string .= "Rain Probability ";
                    $ret_string .= $precipprob  . "%";;
                    $ret_string .= "<br>";
                }
                
                $ret_string .= "Wind Speed: " . $windspeed;  
                $ret_string .= "<br>";
                $ret_string .= "Wind Gust: " . $windgust;  
                $ret_string .= "<br>";
                $ret_string .= "temp: " . $temp;  
                $ret_string .= "<br>";
        }      
        
        return  $ret_string;
        
    }
}
