<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Remote_data
{
    protected $ci;

    public function __construct()
    {
        $this->ci =& get_instance();
    }

    public function fetch_data($url)
    {
        $curl = curl_init();

        // Set cURL options
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        // Execute the cURL request
        $response = curl_exec($curl);

        // Check for errors
        if ($response === false) {
            $error_message = curl_error($curl);
            curl_close($curl);
            log_message('error', 'cURL Error: '.$error_message);
            return '';
        }

        // Close cURL handle
        curl_close($curl);

        return $response;
    }
}
