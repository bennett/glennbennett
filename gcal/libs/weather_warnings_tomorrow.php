<?php

class weather_warnings
{
    private $hours = null;
    // Set your Tomorrow.io API key
    private $api_key = '1EhdNQcqYmjBrMYNUccszVqWNLUK8EFO';

    public function __construct($location, $date)
    {
        $loc = urlencode($location);
        $timestamp = strtotime($date);
        $request_url = "https://api.tomorrow.io/v4/timelines?" .
            "location=" . $loc .
            "&fields=temperature,windSpeed,windGust,precipitationProbability,precipitationType" .
            "&units=imperial" .
            "&timesteps=1h" .
            "&startTime=" . 'now' .
            "&endTime=" . 'nowPlus24h' . // +1 day in seconds
            "&apikey=" . $this->api_key;

        try {
            echo $request_url . "<br>";
            
            $response = @file_get_contents($request_url);
            if ($response === FALSE) {
                throw new Exception('Unable to fetch weather data');
            }
        } catch (Exception $e) {
            echo 'Error: ',  $e->getMessage(), "\n";
        }

        $data = json_decode($response, true);
        
        var_dump($data);

        if (isset($data['data']['timelines'][0]['intervals'])) {
            $this->hours = $data['data']['timelines'][0]['intervals'];
        }
    }

    public function get_warnings($start_time, $end_time)
    {
        if (!isset($this->hours)) {
            return null;
        }

        $conditions = "";
        $preciptype = "";
        $precipprob = 0;
        $windspeed = 0;
        $windgust = 0;

        $ret_string = "";

        foreach ($this->hours as $interval) {
            $hour = $interval['startTime'];
            $hour_data = $interval['values'];

            if (strtotime($hour) >= $start_time && strtotime($hour) <= $end_time) {
                $conditions = $hour_data['temperature'];

                if ($hour_data['precipitationType'] == "rain") {
                    $preciptype = $hour_data['precipitationType'];

                    if ($hour_data['precipitationProbability'] > $precipprob) {
                        $precipprob = $hour_data['precipitationProbability'];
                    }
                }

                if ($hour_data['windSpeed'] > $windspeed) {
                    $windspeed = $hour_data['windSpeed'];
                }

                if ($hour_data['windGust'] > $windgust) {
                    $windgust = $hour_data['windGust'];
                }
            }
        }

        if ($precipprob > 10 || $windspeed > 13) {
            $ret_string = "Temperature: " . $conditions . "°F\n";
            if ($preciptype == 'rain') {
                $ret_string .= "Rain Probability: " . $precipprob . "%\n";
            }

            $ret_string .= "Wind Speed: " . $windspeed . " mph\n";
            $ret_string .= "Wind Gust: " . $windgust . " mph\n";
        }

        return $ret_string;
    }
}

