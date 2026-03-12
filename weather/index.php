<?php
// Set your Visual Crossing Weather API key
$api_key = 'RMRVJEQCLJMHF2AFDQEWBXLSA';


// Set the location (e.g., city name or coordinates)
$location = 'Moorpark%2C%20CA%2C%20US';

// Set the date and timeframe (start and end times)
$date = '2024-04-13';
$start_time = '12:00:00';
$end_time = '18:00:00';

// Construct the API request URL
$request_url = "https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/weatherdata/history?location=$location&date=$date&startDateTime=$start_time&endDateTime=$end_time&unitGroup=metric&key=$api_key";


$request_url = "https://weather.visualcrossing.com/VisualCrossingWebServices/rest/services/timeline/$location/$date?unitGroup=us&key=RMRVJEQCLJMHF2AFDQEWBXLSA&contentType=json";

// Make the API request
$response = file_get_contents($request_url);

// Parse the JSON response
$data = json_decode($response, true);


//var_dump($data['days'][0]['hours']);

$hours = $data['days'][0]['hours'];

foreach($hours as $hour)
{
    echo 'datetime: ' . $hour['datetime'] .  '<br>';
    echo 'datetimeEpoch: ' . $hour['datetimeEpoch'] .  '<br>';
    echo 'precipprob: ' . $hour['precipprob'] .  '<br>';
    echo 'windspeed: ' . $hour['windspeed'] .  '<hr>';
}

// Display the weather data
echo "Weather data for $location on $date from $start_time to $end_time:\n";
echo "Temperature: {$data['locations'][$location]['values'][0]['temp']}°C\n";
echo "Conditions: {$data['locations'][$location]['values'][0]['conditions']}\n";
echo "Humidity: {$data['locations'][$location]['values'][0]['humidity']}%\n";
echo "Chance of Rain: {$data['locations'][$location]['values'][0]['precip']}%\n";
echo "Wind Speed: {$data['locations'][$location]['values'][0]['wspd']} km/h\n";
?>

