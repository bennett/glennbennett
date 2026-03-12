<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Weather_lib {
    protected $CI;
    protected $api_key = 'c2e558f6a07057081eaf0f3dee8318df';
    
    // City configurations
    protected $cities = [
        'moorpark' => [
            'city' => 'Moorpark',
            'state' => 'CA',
            'country' => 'US',
            'display' => 'Moorpark'
        ],
        'simi_valley' => [
            'city' => 'Simi%20Valley',
            'state' => 'CA',
            'country' => 'US',
            'display' => 'Simi Valley'
        ],
        'santa_paula' => [
            'city' => 'Santa%20Paula',
            'state' => 'CA',
            'country' => 'US',
            'display' => 'Santa Paula'
        ]
    ];

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->helper('url');
        date_default_timezone_set('America/Los_Angeles');
        log_message('debug', 'Weather Library Initialized');
    }

    public function get_weather() {
        $results = [];

        foreach ($this->cities as $key => $city_data) {
            $url = "https://api.openweathermap.org/data/2.5/weather?q={$city_data['city']},{$city_data['state']},{$city_data['country']}&units=imperial&appid=" . $this->api_key;
            
            $opts = [
                'http' => [
                    'method' => 'GET',
                    'header' => "User-Agent: Mozilla/5.0\r\n",
                    'timeout' => 10
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ]
            ];
            
            try {
                $response = @file_get_contents($url, false, stream_context_create($opts));
//                var_dump($response);
                if ($response === false) {
                    log_message('error', 'Weather API failed for ' . $city_data['city']);
                    log_message('debug', 'API URL: ' . $url);
                    $results[$key] = [
                        'city_name' => $city_data['display'],
                        'High_Temp' => 'N/A',
                        'Wind_Speed' => 'N/A',
                        'Rain_Chance' => 'N/A'
                    ];
                    continue;
                }
                
                $data = json_decode($response, true);
                if (!isset($data['main']) || !isset($data['wind'])) {
                    log_message('error', 'Invalid data structure for ' . $city_data['city']);
                    log_message('debug', 'API Response: ' . $response);
                    $results[$key] = [
                        'city_name' => $city_data['display'],
                        'High_Temp' => 'N/A',
                        'Wind_Speed' => 'N/A',
                        'Rain_Chance' => 'N/A'
                    ];
                    continue;
                }
                
                $results[$key] = [
                    'city_name' => $city_data['display'],
                    'High_Temp' => round($data['main']['temp_max']) . '&#176;F',
                    'Wind_Speed' => round($data['wind']['speed']) . ' mph',
                    'Rain_Chance' => isset($data['rain']['1h']) ? round($data['rain']['1h'] * 100) . '%' : '0%'
                ];
                
            } catch (Exception $e) {
                log_message('error', 'Exception fetching weather for ' . $city_data['city'] . ': ' . $e->getMessage());
                log_message('debug', 'API URL: ' . $url);
                $results[$key] = [
                    'city_name' => $city_data['display'],
                    'High_Temp' => 'N/A',
                    'Wind_Speed' => 'N/A',
                    'Rain_Chance' => 'N/A'
                ];
            }
        }

        return $results;
    }
}

