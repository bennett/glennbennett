<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facebook extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->database();
        $this->load->library('date_diff');
        $this->load->library('gcal_gig_reader');
        $this->load->config('globals');
        
        // Set up calendar sources
        $cals = [
            [
                'url'  => 'https://calendar.google.com/calendar/ical/c_8oqt9e7bms1sefskr0fl01r7tg%40group.calendar.google.com/public/basic.ics',    
                'name' => 'perform',
            ],
            [
                'url'  => 'https://calendar.google.com/calendar/ical/c_60458ee36250676533587bd3a2b92e3bedc52796d6a2b5b76fca9bd60ccba33d%40group.calendar.google.com/public/basic.ics',    
                'name' => 'canceled',
            ]
        ];

        $this->gcal_gig_reader->set_cals($cals);
    }
    
    public function index()
    {
        date_default_timezone_set('America/Los_Angeles');
        
        $event = null;
        $event_id = $this->input->get('event_id');
        $event_date = $this->input->get('event_date');
        
        if (!$event_id || !$event_date) {
            redirect('/cal', 'refresh');
        }
        
        // Get events for the specified date range (one day)
        $events = $this->gcal_gig_reader->get_events($event_date, $event_date + 86400);
        
        // Find the specific event
        foreach($events as $tevent) {
            if($tevent['UID'] == $event_id) {
                $event = $tevent;
                break;
            }
        }
        
        $this->load->model('share_image_model');

        if($event == null) {
            // Fallback: try loading from share_images DB
            $stored = $this->share_image_model->get_by_uid($event_id);
            if (!$stored) {
                redirect('/cal', 'refresh');
            }

            $start_date = $stored->start_date;
            $end_date = $stored->end_date;
            $summary = $stored->summary;
            $description = $stored->description ?: '';
            $location = $stored->location;
            $share_hash = $stored->hash;
        } else {
            $start_date = $event['start_date'];
            $end_date = $event['end_date'];
            $summary = $this->string_cleaner($event['summary']);
            $description = $this->string_cleaner($event['description']);
            $location = $event['location'];

            $share = $this->share_image_model->find_or_create($summary, $location, $start_date, $end_date, $event['UID'], $description);
            $share_hash = $share->hash;
        }

        // Format dates for display
        $week_day = date("l", $start_date);
        $date = date("l - F d", $start_date);
        $time = date("g:i a", $start_date) . " - " . date("g:i a", $end_date);

        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $today = date("Y-m-d");
        $event_day = date("Y-m-d", $start_date);

        // Build data array for view
        $data = array(
            'event_id' => $event_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'actual_link' => $actual_link,
            'summary' => $summary,
            'description' => $description,
            'date' => $date,
            'time' => $time,
            'location' => $location,
            'title' => $summary,
            'sub_title' => "$date - $time"
        );

        // Calculate date difference
        $this->date_diff->days_diff($today, $event_day);
        $data['date_diff'] = $this->date_diff->days_diff_str($today, $event_day);

        $data['share_hash'] = $share_hash;

        // Add OG metadata using og partial with short URL
        $data['og'] = $this->load->view("partials/og.php", $data, true);

        // Use the new fb1 view
        $this->layout_view('fb1', $data);
    }
    
    private function layout_view($view, $data)
    {
        $layout = $this->load->view('layouts/canvas-white.php', $data, true);
        $nav = $this->load->view('partials/nav.php', '', true);
        $content = $this->load->view($view, $data, true);
        
        $js = "";
        $css = "";
        if (is_file(APPPATH."views/js/$view.php"))
        {
            $js = $this->load->view("js/$view.php", '', true);
        }
        if (is_file(APPPATH."views/css/$view.php"))
        {
            $css = $this->load->view("css/$view.php", '', true);
        }

        $layout = str_replace('[nav]', $nav, $layout);
        $layout = str_replace('[javascript]', $js, $layout);
        $layout = str_replace('[css]', $css, $layout);
        $page = str_replace('[content]', $content, $layout);

        echo $page;
    }
    
    function string_cleaner($str)
    {
        $str = str_replace("\\n", "<br />", $str);
        $str = preg_replace('/\\\\/', '', $str);
        $str = str_replace('&lt;', '<', $str);
        $str = str_replace('&gt;', '>', $str);
        $str = str_replace('&amp;', '&', $str);
        
        return  $str;
    }
}
