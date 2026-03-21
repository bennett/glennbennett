<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
        $this->load->library('MP3File');
        $this->load->config('globals');
        $this->load->library('date_diff');
        $this->load->library('remote_data');
        $this->load->library('gcal_gig_reader');
//		$this->load->helper('url');

//		$this->load->library('grocery_CRUD');

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
        $data = $this->build_data();
        $cal = '';
        /* Not working
        $remote_url = 'http://glennbennett.com/gcal/gcal-upcoming.php';
        $cal = $this->remote_data->fetch_data($remote_url);
//        $cal = file_get_contents('http://glennbennett.com/gcal/gcal-upcoming.php');
        */
//        echo getcwd();
        
        ob_start();
        include FCPATH . 'gcal/gcal-upcoming.php';
        $cal = ob_get_clean();        
        
        $data['gcal'] = $cal;

        // Structured event data for hero section
        $start = time();
        $end = $start + (14 * 86400);
        $events = $this->gcal_gig_reader->get_events($start, $end);
        $hero_events = [];
        if (!empty($events)) {
            foreach ($events as $evt) {
                if (!empty($evt['status']) && stripos($evt['status'], 'Cancel') !== false) continue;
                if ($evt['start_date'] < time()) continue;
                $hero_events[] = $evt;
                if (count($hero_events) >= 2) break;
            }
        }
        $data['hero_events'] = $hero_events;

		$this->load->view('home', $data);
	}
    
    public function home()
	{
        $data = $this->build_data(7);
        
        $cal = file_get_contents('https://glennbennett.com/gcal/gcal-upcoming.php');
        $data['gcal'] = $cal;       
		$this->load->view('home-test', $data);
	}
    

    public function song($song_id)
    {

        $featured = $this->songs_model->get_song($song_id);

        $data = $this->build_data($featured);
               
		$this->load->view('home', $data);
        
    }
    
    public function captcha()
	{
        $data['title'] = "Mailing List";
        $data['sub_title'] = "Glenn Bennett Mailing List";
        $this->layout_view('captcha', $data);
	}    
    

    public function fbook()
    {
    
        echo "event_date: ";
        echo $_GET['event_id'];
        echo "<br>";
        echo 'event_date: ';
        echo $_GET['event_date'];
        echo "<br>";
        
        $event = null;
        
        $event_id = $_GET['event_id'];
        
        $events = $this->gcal_gig_reader->get_events($_GET['event_date'], $_GET['event_date']   + 86400 );
//        var_dump($events);
        
        foreach($events as $tevent)
        {
            if($tevent['UID'] == $event_id)
            {
                $event = $tevent;
                echo "<br>";
                echo "bingo";
                echo "<br>";
            }
        }
        
        if($event != null)
        {
            var_dump($event);
        }
        
//        die;    

//        echo "Bingo:";
//        echo $_GET['a'];
//        die;
        date_default_timezone_set('America/Los_Angeles');
    
        $a = "temp";
        
        $start_date = $event['start_date'];
        $end_date = $event['end_date'];
        $summary = $event['summary'];
        $description = $event['description'];
        $location = $event['location'];
        
         //$week_day = "Monday";
        $week_day = date( "l", $start_date) ;
        //$date = "Monday - July 11, 2022";
        $date = date( "l - F d", $start_date );
        //$time = "5:30 pm - 8:00 pm";
        $time = date("g:i a", $start_date) . " - " . date("g:i a", $end_date);
        
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $today = date( "Y-m-d");
        $event_day = date( "Y-m-d", $start_date);
        
        $data['a'] = $a;
        $data['actual_link'] = $actual_link;
        $data['summary'] = $summary;
        $data['description'] = $description;
        $data['date'] = $date;
        $data['time'] = $time;
        $data['location'] = $location;
        
        $data['title'] = "$summary";
        $data['sub_title'] = "$date - $time";
        $this->date_diff->days_diff($today, $event_day);
        $data['date_diff'] = $this->date_diff->days_diff_str( $today, $event_day );
        
        $data['og'] = $this->load->view("partials/og.php", $data, true);
        
        $this->layout_view('fb', $data);
    }

    
    public function fb()
    {
//        echo "Bingo:";
//        echo $_GET['a'];
//        die;
        date_default_timezone_set('America/Los_Angeles');
        if(isset($_GET['a']) )
        {
            $a = $_GET['a'];

            $a = urldecode($a);


            $json = base64_decode($a);


            // Check if the decoding was successful
            if ($json === false) {
                // Handle the error, the data is not valid base64
                echo 'The provided string is not valid base64 encoded data.';
            }
//            ECHO $json;

            $values = json_decode($json);


//            var_dump($values);
            if($values == null)
            {
                redirect('/cal', 'refresh');
            }


            foreach ($values as $key => $value)
            {
                $$key = $value;
            }

            //$week_day = "Monday";
            $week_day = date( "l", $start_date) ;
            //$date = "Monday - July 11, 2022";
            $date = date( "l - F d", $start_date );
            //$time = "5:30 pm - 8:00 pm";
            $time = date("g:i a", $start_date) . " - " . date("g:i a", $end_date);

            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

            $today = date( "Y-m-d");
            $event_day = date( "Y-m-d", $start_date);

            // Generate short URL hash for share image
            $this->load->model('share_image_model');
            $share = $this->share_image_model->find_or_create($summary, $location, $start_date, $end_date);

            $data['a'] = $a;
            $data['share_hash'] = $share->hash;
            $data['actual_link'] = $actual_link;
            $data['summary'] = $summary;
            $data['description'] = $description;
            $data['date'] = $date;
            $data['time'] = $time;
            $data['location'] = $location;

            $data['title'] = "$summary";
            $data['sub_title'] = "$date - $time";
            $this->date_diff->days_diff($today, $event_day);
            $data['date_diff'] = $this->date_diff->days_diff_str( $today, $event_day );

            $data['og'] = $this->load->view("partials/og.php", $data, true);

            $this->layout_view('fb', $data);
        }
        else
        {
            redirect('/cal', 'refresh');
        }
    }
    
    
public function build_data($featured_id = null)
{
    // 1. Fetch songs from API
    $album_data = $this->get_album_data(1);
    $originals = $album_data['songs'] ?: [];
    $album_title = $album_data['title'] ?: 'Album';
    $covers    = $this->get_misc_songs() ?: [];

    // 2. Process "Popular" selection (Randomized from Album 1)
    $popular = $originals;
    if (!empty($popular)) {
        shuffle($popular);
        $popular = array_slice($popular, 0, 4);
    }
    
    $popular = $this->_fetch_api_data("https://music.glennbennett.com/api/popular?limit=4");
//    var_dump($popular);

    // 3. Determine Featured Song
    // If a specific ID was passed, find it; otherwise look for "Find Out For Myself"
    if ($featured_id && !empty($originals)) {
        $featured = current(array_filter($originals, function($s) use ($featured_id) {
            return $s->id == $featured_id;
        })) ?: $originals[0];
    } else {
        // Default featured: "I've Got To Find Out For Myself"
        $featured = null;
        if (!empty($originals)) {
            foreach ($originals as $s) {
                if (stripos($s->title, "Find Out For Myself") !== false) {
                    $featured = $s;
                    break;
                }
            }
            if (!$featured) $featured = $originals[0];
        }
    }

    // 4. Handle Quotes from file
    $new_quotes = [];
    $quotes_path = FCPATH . "quotes.txt";
    
    if (file_exists($quotes_path)) {
        $quotes_raw = file($quotes_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!empty($quotes_raw)) {
            shuffle($quotes_raw);
            $new_quotes = array_slice($quotes_raw, 0, 5); // Get up to 5 random quotes
        }
    }

    // 5. Build Final Data Array
    $data = [
        'site_title'     => "Glenn Bennett",
        'site_sub_title' => "Original Music",
        'album_title'    => $album_title,
        'originals'      => $originals,
        'covers'         => $covers,
        'popular'        => $popular,
        'featured'       => $featured,
        'quotes'         => $new_quotes,
        'audio_url'      => "", // Full URLs provided by API
        'image_url'      => ""  // Full URLs provided by API
    ];

    return $data;
}
 

/**
 * DATA FETCHING LOGIC
 */

    public function get_album_songs($album_id = 1) {
        return $this->_fetch_api_data("https://music.glennbennett.com/api/album/" . $album_id);
    }

    public function get_album_data($album_id = 1) {
        $url = "https://music.glennbennett.com/api/album/" . $album_id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'GlennBennett-WebClient');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $decoded = json_decode($response);
        curl_close($ch);

        $title = '';
        $songs = [];
        if ($decoded && isset($decoded->success) && $decoded->success && isset($decoded->album)) {
            $title = $decoded->album->title ?? '';
            $raw_songs = $decoded->album->songs ?? [];
            foreach ($raw_songs as $song) {
                $song->audio_url = $song->stream_url ?? $song->url ?? '';
                $song->cover_url = $song->cover_url ?? $song->art ?? '/imgs/logo.png';
            }
            $songs = $raw_songs;
        }
        return ['title' => $title, 'songs' => $songs];
    }

    public function get_misc_songs() {
        return $this->_fetch_api_data("https://music.glennbennett.com/api/misc");
    }

    private function _fetch_api_data($url) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_USERAGENT, 'GlennBennett-WebClient');
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      
      $response = curl_exec($ch);
      $decoded = json_decode($response);
      curl_close($ch);

      if (!$decoded || !isset($decoded->success) || !$decoded->success) {
          return [];
      }

      // NEW LOGIC: Look for 'songs' directly (Popular API) 
      // OR inside 'album->songs' (Older Album API)
      if (isset($decoded->songs)) {
          $raw_songs = $decoded->songs;
      } elseif (isset($decoded->album->songs)) {
          $raw_songs = $decoded->album->songs;
      } else {
          return [];
      }

      // Map properties for the view
      foreach($raw_songs as $song) {
          $song->audio_url = $song->stream_url ?? $song->url ?? '';
          $song->cover_url = $song->cover_url ?? $song->art ?? '/imgs/logo.png';
      }

      return $raw_songs;
  }

    private function _map_properties($songs) {
        foreach ($songs as $song) {
            // Map the music URL
            // If audio_url is missing, try stream_url or url
            if (!isset($song->audio_url)) {
                $song->audio_url = $song->stream_url ?? $song->url ?? '';
            }

            // Map the cover art
            if (!isset($song->cover_url)) {
                $song->cover_url = $song->art ?? $song->cover ?? '/imgs/logo.png';
            }
        }
        return $songs;
    }
 
    // https://www.youtube.com/@horsewhisperingmusic/playlists
    // Gigs comes from https://www.youtube.com/@GlennBennettIRL
    
    public function vids($set = 0, $page_partial = "")
    {
        $playlist_id = 'PL986m4OTTx_y8LzmVoOzxZ3dNuQEHfRbU';
        $title = 'Music Videos';
        $sub_title = "All";
        $vid_title = 'Music Videos';
        $vid_sub_title = '';

        switch ($set) {
            case 1:
                $playlist_id = 'PL986m4OTTx_xEQZrKCnKAkTk0YcVAM9Tw';
                $sub_title = "Original Songs";
                break;
            case 2:
                $playlist_id = 'PL986m4OTTx_zJzY-NFdn5EsgNr4I31D0L';
                $sub_title = "Cover Songs";
                break;
            case 3:
                $title = 'My Information';
                $sub_title = "Performing";
                $vid_title = 'Samples';
                $playlist_id = 'PL645bCGj_F2CT_FJX30RmHUjRTjrtVpLO';
                $vid_sub_title = "Live Performance Videos";
                break;
        }

        $now = time();
        $lock_file_name = FCPATH . "/video_data/" . $playlist_id . ".txt";
        $frequency = 120 * 60;

        if (is_file($lock_file_name)) {
            $file_last_modified = filemtime($lock_file_name);
        } else {
            $file_last_modified = 0;
        }

      if (($now - $file_last_modified) > $frequency) {
          $api_key = 'AIzaSyBLe-U8s9z5zXQ8nJNr2B_PXdDvF9o9oKc';
          $url = 'https://www.googleapis.com/youtube/v3/playlistItems'
               . '?part=snippet'
               . '&maxResults=50'
               . '&playlistId=' . urlencode($playlist_id)
               . '&key=' . $api_key;

          $response = @file_get_contents($url);
          $data_yt  = $response ? json_decode($response) : null;
          $videos   = ($data_yt && isset($data_yt->items)) ? $data_yt->items : [];
          if (!empty($videos)) {
              file_put_contents($lock_file_name, serialize($videos));
          }
      } else {
          $videos = @unserialize(file_get_contents($lock_file_name)) ?: [];
      }

        $data['videos']       = $videos;
        $data['title']        = $title;
        $data['sub_title']    = $sub_title;
        $data['vid_title']    = $vid_title;
        $data['vid_sub_title'] = $vid_sub_title;
        $data['page_partial']  = $page_partial;

        $this->layout_view('vids', $data);
    }    
    
    public function info()
    {
        // just go to the old place for now - work on new page
        $this->samples();
    }
    public function samples()
    {
//        $this->index(); // just for now
        $page_partial = $this->load->view('about', '', true);
        $page_partial .= $this->load->view("page_partials/" . 'gig_info', '', true);
        
        $this->vids(3, $page_partial);
    }
    
    public function mlist()
    {
        $data['title'] = "Newsletter";
        $data['sub_title'] = "Glenn Bennett Newsletter";

        $start = time();
        $end = $start + (14 * 86400);
        $events = $this->gcal_gig_reader->get_events($start, $end);
        $upcoming = [];
        foreach ($events as $evt) {
            $upcoming[] = [
                'summary'  => $evt['summary'],
                'date'     => date('D M j', $evt['start_date']),
                'time'     => date('g:i a', $evt['start_date']) . ' - ' . date('g:i a', $evt['end_date']),
                'location' => $evt['location'],
            ];
            if (count($upcoming) >= 5) break;
        }
        $data['upcoming_events'] = $upcoming;

        $this->layout_view('mlist', $data);
    }
    
    public function about()
    {
        $data['title'] = "About";
        $data['sub_title'] = "About Glenn Bennett";
        $this->layout_view('about', $data);
    }

    public function tip()
    {
        $data['title'] = "Tip";
        $data['sub_title'] = "Glenn Bennett Tip Jar";
        
        $return_url = "";
        
        if(isset($_GET['link']) )
        {
            $return_url = htmlspecialchars($_GET['link']);
        }
        
        
        $data['back_link'] = $return_url;
        

        
//        $this->layout_view('tip', $data);

        $content = $this->load->view('tip', $data, true);

        echo $content;
    }
    
    public function qr()
    {
        $data['title'] = "Access";
        $data['sub_title'] = "Glenn Bennett Access Section";
        
        $return_url = "";
        
        if(isset($_GET['link']) )
        {
            $return_url = htmlspecialchars($_GET['link']);
        }
        
        
        $data['back_link'] = $return_url;
        
 
        $navs = [
            ['url' => '/request/', 'icon' => 'fa-hand-paper', 'text' => 'Request System'],
            ['url' => '/tip/', 'icon' => 'fa-coffee', 'text' => 'Online Tipping'],
            ['url' => '/newsletter/', 'icon' => 'fa-envelope', 'text' => 'Newsletter']
        ];
        
        $links = [
            ['url' => '/', 'icon' => 'fa-hand-paper', 'text' => 'Site Home'],
            ['url' => '/cal', 'icon' => 'fa-coffee', 'text' => 'Calendar'],
            ['url' => '/about', 'icon' => 'fa-link', 'text' => 'Contact']
        ];
        
        // Create a new array.
//        $links = array(); 

            
        $data['navs'] = $navs;  
        $data['links'] = $links;        
        
//        $this->layout_view('tip', $data);

        $content = $this->load->view('qr', $data, true);

        echo $content;
    }    

    public function cal()
    {
        $data['title'] = "Calendar";
        $data['sub_title'] = "Glenn Bennett Calendar";
        

        // Weather - does not give the right data
        
        $this->load->library('weather_lib');

        $data['weather_results'] = $this->weather_lib->get_weather();
        
//        var_dump($data['weather_results']);
        
        // Load the sidebar view
        $data['last_updated'] = date('g:i a');
        $data['current_date'] = date('l, F j');
        
        
        
        $this->layout_view('cal', $data);
    }
    
    public function past()
    {
        $data['title'] = "Calendar";
        $data['sub_title'] = "Glenn Bennett Calendar";
        $this->layout_view('cal_past', $data);
    }
    
    public function dup()
    {
        redirect('admin/dup_events');
    }


    public function links()
    {

        /* to find icon look here
        /css/font-icons.css
        */
        $data['title'] = "Links";
        $data['sub_title'] = "Quick Links";
        
        // Array of links with titles, descriptions, and URLs
        $data['links'] = [
            [
                'title' => 'Request System',
                'description' => 'Main Request link. Redirect to init',
                'url' => '/r',
                'icon' => 'icon-hand-up'
            ],
            [
                'title' => 'Request System admin',
                'description' => 'The admin system',
                'url' => 'https://app.songperformer.com/reqman',
                'icon' => 'icon-toolbox'
            ],            
            [
                'title' => 'Customer Info',
                'description' => 'My info for customer',
                'url' => '/info',
                'icon' => 'icon-info-sign'
            ],
            [
                'title' => 'Duplicate a Gig Date',
                'description' => 'Lets me quickly add a new gig',
                'url' => '/dup',
                'icon' => 'icon-copy'
            ], 
           [
                'title' => 'My admin',
                'description' => 'Newsletter, Ventues, etc.',
                'url' => 'https://admin.glennbennett.com/',
                'icon' => 'icon-cog'
            ],                       

        ];
        
        $this->layout_view('links', $data);
    }

    
    public function tcal()
    {
        $data['title'] = "Calendar";
        $data['sub_title'] = "Glenn Bennett Calendar";
        $this->layout_view('tcal', $data);
    }
    
    public function request()
    {
        redirect('https://app.songperformer.com/requests/init', 'refresh');
    } 
    
    public function r()
    {
        $this->request();
    }     

    public function gm()
    {
        $this->album();
    }  

    public function album($album = 0)
    {
        $tracks = array(
            array(
                "name" => "Live At The Guitar Merchant",
                "file" => "1-Intro.mp3"
            ),
            array(                               
                "name" => "Show Dancin'",
                "file" => "2-Slow Dancin.mp3"
            ),
            array( 
                "name" => "Horse Whispering Music Talk",
                "file" => "3-Horse Whistering Music Talk.mp3"
            ),
            array(                                                                                           
                "name" => "The One You Love",
                "file" => "4-The One Who Love You.mp3"
            ),
            array(
                "name" => "Jack Tempchin Talk",
                "file" => "5-Jack Tempchin talk.mp3"
            ),
            array(
                "name" => "Loves First Lesson",
                "file" => "6-Loves First Lesson.mp3"
            ),
            array(
                "name" => "I've got a cold talk",
                "file" => "7-Cold.mp3"
            ),
            array(
                "name" => "Room to Run",
                "file" => "8-Room To Run.mp3"
            ),
            array(
                "name" => "Wood On The Fire talk",
                "file" => "9-Wood on the Fire Talk.mp3"
            ),
            array(
                "name" => "Wood On The Fire",
                "file" => "10-Wood on the Fire.mp3"
            ),
            array(
                "name" => "Old Words",
                "file" => "11-Old Words.mp3"
            ),
            array(
                "name" => "Light it up in the Bedroom Talk",
                "file" => "12-Light is up in the bedroom Talk.mp3"
            ),
            array(
                "name" => "Light it up in the Bedroom",
                "file" => "13-Light is up in the bedroom.mp3"
            )
        );                                
             
        
        
        $data['title'] = "2016 Live Album";
        $data['tracks'] = $tracks;
        $data['sub_title'] = "Glenn Bennett Live at The Guitar Mechant 2016";
        $data['player_path'] = "/player/";
        $this->layout_view('album', $data);
    }


    function layout_view($view, $data)
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
    
    function generateBacklink() 
    {
       // Check if there was a previous page
       if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
          // Generate the backlink using the referer URL
          $backlink = '<a href="'.$_SERVER['HTTP_REFERER'].'">Go back</a>';
          return $backlink;
       } else {
          // No previous page, return empty string
          return '';
       }
    }

	// --- Calendar Event Image Generator ---

	public function cal_image($hash = null)
	{
		date_default_timezone_set('America/Los_Angeles');

		// --- Hash-based lookup from share_images table ---
		if ($hash)
		{
			$this->load->model('share_image_model');
			$this->load->model('template_model');
			$this->load->model('venue_model');
			$this->load->model('venue_type_model');
			$this->load->library('cal_image_renderer');

			$share = $this->share_image_model->get_by_hash($hash);
			if ( ! $share)
			{
				show_404();
				return;
			}

			$start_date = $share->start_date;
			$end_date = $share->end_date;
			$summary = $share->summary;
			$location = $share->location;
			$date = date("l - M d", $start_date);
			$time = date("g:i a", $start_date) . " - " . date("g:i a", $end_date);
			$font_dir = FCPATH . 'fonts/';
			$is_past = ($start_date < time());

			// Resolve templates using 3-tier priority
			$templates = $this->_resolve_templates($summary);

			if ( ! empty($templates))
			{
				$day = (int) date("j", $start_date);
				$idx = $day % count($templates);
				$tpl = $templates[$idx];

				$bg_file = FCPATH . 'imgs/template-backgrounds/' . $tpl->bg_filename;
				$photo_file = FCPATH . 'imgs/template-photos/' . $tpl->photo_filename;

				$layout_values = $this->_template_layout_values($tpl);

				$texts = [
					'summary'  => $summary,
					'date'     => $date,
					'time'     => $time,
					'location' => $location,
				];

				if ($is_past && file_exists($bg_file) && file_exists($photo_file))
				{
					$im = $this->cal_image_renderer->render_expired($bg_file, $photo_file, $texts, $layout_values, $font_dir);
				}
				elseif (file_exists($bg_file) && file_exists($photo_file))
				{
					$im = $this->cal_image_renderer->render_template($bg_file, $photo_file, $texts, $layout_values, $font_dir);
				}
			}

			// Fallback to old cal_images system if no template rendered
			if ( ! isset($im) || ! $im)
			{
				$this->load->model('cal_image_model');
				$images = $this->cal_image_model->get_active();

				if ( ! empty($images))
				{
					$selected_image = null;

					// Check for venue-specific images (legacy cal_images)
					$venues = $this->venue_model->get_active_with_images();
					$alpha_summary = strtoupper(preg_replace('/[^a-zA-Z]/', '', $summary));
					$venue_image_ids = [];

					foreach ($venues as $venue)
					{
						$match = false;
						if ($venue->match_type === 'exact' && $summary === $venue->match_pattern) $match = true;
						elseif ($venue->match_type === 'contains' && strpos($summary, $venue->match_pattern) !== false) $match = true;
						elseif ($venue->match_type === 'alpha_only')
						{
							$alpha_pattern = strtoupper(preg_replace('/[^a-zA-Z]/', '', $venue->match_pattern));
							if (strpos($alpha_summary, $alpha_pattern) !== false) $match = true;
						}
						if ($match && ! empty($venue->images))
						{
							foreach ($venue->images as $vi) $venue_image_ids[] = $vi->id;
						}
					}

					if ( ! empty($venue_image_ids))
					{
						$day = (int) date("j", $start_date);
						$idx = $day % count($venue_image_ids);
						$selected_image = $this->cal_image_model->getById($venue_image_ids[$idx]);
					}

					if ( ! $selected_image)
					{
						$day = (int) date("j", $start_date);
						$idx = $day % count($images);
						$selected_image = $images[$idx];
					}

					$img_file = FCPATH . $selected_image->image_path . $selected_image->filename;
					$db_layout = $this->cal_image_model->get_layout($selected_image->id);
					$layout_values = [
						'text_offset'         => (int) $db_layout->text_offset,
						'summary_font_size'   => (int) $db_layout->summary_font_size,
						'summary_margin_top'  => (int) $db_layout->summary_margin_top,
						'date_font_size'      => (int) $db_layout->date_font_size,
						'date_margin_top'     => (int) $db_layout->date_margin_top,
						'time_font_size'      => (int) $db_layout->time_font_size,
						'time_margin_top'     => (int) $db_layout->time_margin_top,
						'location_font_size'  => (int) $db_layout->location_font_size,
						'location_margin_top' => (int) $db_layout->location_margin_top,
					];
					$texts = [
						'summary'  => $summary,
						'date'     => $date,
						'time'     => $time,
						'location' => $location,
					];
					$im = $this->cal_image_renderer->render($img_file, $texts, $layout_values, $font_dir);
				}
				else
				{
					// Last resort: original static Cal-Event-N.jpg files
					$no_of_bg_images = 25;
					$image_no = (int) date("j", $start_date);
					$image_no = ($image_no % ($no_of_bg_images - 1)) - 1;
					if ($image_no < 0) $image_no = 0;

					$img_file = FCPATH . 'imgs/Cal-Event-' . $image_no . '.jpg';
					$layout_values = [
						'text_offset' => -200, 'summary_font_size' => 36, 'summary_margin_top' => 260,
						'date_font_size' => 24, 'date_margin_top' => 25, 'time_font_size' => 36,
						'time_margin_top' => 25, 'location_font_size' => 24, 'location_margin_top' => 25,
					];
					$texts = [
						'summary'  => $summary,
						'date'     => $date,
						'time'     => $time,
						'location' => $location,
					];
					$im = $this->cal_image_renderer->render($img_file, $texts, $layout_values, $font_dir);
				}
			}

			if ( ! isset($im) || ! $im)
			{
				$im = $this->_fallback_image($summary, $font_dir);
			}

			header('Content-type: image/png');
			imagepng($im);
			imagedestroy($im);
			return;
		}

		// --- Legacy query string behavior ---
		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');
		$summary = $this->input->get('summary');
		$location = $this->input->get('location');
		$forced_image = $this->input->get('image_no') !== null ? (int) $this->input->get('image_no') : null;

		if ( ! $start_date || ! $end_date || ! $summary)
		{
			show_error('Missing parameters', 400);
			return;
		}

		$date = date("l - M d", $start_date);
		$time = date("g:i a", $start_date) . " - " . date("g:i a", $end_date);

		// --- Resolve image + layout from DB, fallback to config ---
		$this->load->model('cal_image_model');
		$this->load->model('venue_model');
		$this->load->model('venue_type_model');
		$this->load->model('template_model');
		$this->load->library('cal_image_renderer');

		$texts = [
			'summary'  => $summary,
			'date'     => $date,
			'time'     => $time,
			'location' => $location,
		];
		$font_dir = FCPATH . 'fonts/';
		$im = null;

		// --- Priority 1-3: Templates with venue-aware selection ---
		$templates = $this->_resolve_templates($summary);

		if ( ! empty($templates))
		{
			if ($forced_image !== null)
			{
				$idx = max(0, min($forced_image, count($templates) - 1));
			}
			else
			{
				$day = (int) date("j", $start_date);
				$idx = $day % count($templates);
			}
			$tpl = $templates[$idx];

			$bg_file = FCPATH . 'imgs/template-backgrounds/' . $tpl->bg_filename;
			$photo_file = FCPATH . 'imgs/template-photos/' . $tpl->photo_filename;

			if (file_exists($bg_file) && file_exists($photo_file))
			{
				$layout_values = $this->_template_layout_values($tpl);
				$im = $this->cal_image_renderer->render_template($bg_file, $photo_file, $texts, $layout_values, $font_dir);
			}
		}

		// --- Fallback: Old cal_images system ---
		if ( ! $im)
		{
			$img_file = null;
			$layout_values = null;
			$images = $this->cal_image_model->get_active();

			if ( ! empty($images))
			{
				$selected_image = null;

				// Check for venue-specific images (legacy cal_images)
				if ($summary)
				{
					$venues = $this->venue_model->get_active_with_images();
					$alpha_summary = strtoupper(preg_replace('/[^a-zA-Z]/', '', $summary));
					$venue_image_ids = [];

					foreach ($venues as $venue)
					{
						$match = false;

						if ($venue->match_type === 'exact' && $summary === $venue->match_pattern)
						{
							$match = true;
						}
						elseif ($venue->match_type === 'contains' && strpos($summary, $venue->match_pattern) !== false)
						{
							$match = true;
						}
						elseif ($venue->match_type === 'alpha_only')
						{
							$alpha_pattern = strtoupper(preg_replace('/[^a-zA-Z]/', '', $venue->match_pattern));
							if (strpos($alpha_summary, $alpha_pattern) !== false)
							{
								$match = true;
							}
						}

						if ($match && ! empty($venue->images))
						{
							foreach ($venue->images as $vi)
							{
								$venue_image_ids[] = $vi->id;
							}
						}
					}

					if ( ! empty($venue_image_ids))
					{
						$day = (int) date("j", $start_date);
						$idx = $day % count($venue_image_ids);
						$selected_image = $this->cal_image_model->getById($venue_image_ids[$idx]);
					}
				}

				// No venue match — use global pool
				if ( ! $selected_image)
				{
					if ($forced_image !== null)
					{
						$idx = max(0, min($forced_image, count($images) - 1));
					}
					else
					{
						$day = (int) date("j", $start_date);
						$idx = $day % count($images);
					}
					$selected_image = $images[$idx];
				}

				$img_file = FCPATH . $selected_image->image_path . $selected_image->filename;
				$db_layout = $this->cal_image_model->get_layout($selected_image->id);

				$layout_values = [
					'text_offset'         => (int) $db_layout->text_offset,
					'summary_font_size'   => (int) $db_layout->summary_font_size,
					'summary_margin_top'  => (int) $db_layout->summary_margin_top,
					'date_font_size'      => (int) $db_layout->date_font_size,
					'date_margin_top'     => (int) $db_layout->date_margin_top,
					'time_font_size'      => (int) $db_layout->time_font_size,
					'time_margin_top'     => (int) $db_layout->time_margin_top,
					'location_font_size'  => (int) $db_layout->location_font_size,
					'location_margin_top' => (int) $db_layout->location_margin_top,
				];
			}
			else
			{
				// Fallback to original config-file behavior
				$no_of_bg_images = 25;

				if ($forced_image !== null)
				{
					$image_no = max(0, min($forced_image, $no_of_bg_images - 1));
				}
				else
				{
					$image_no = date("j", $start_date);
					$image_no = ($image_no % ($no_of_bg_images - 1)) - 1;
					if ($image_no < 0) $image_no = 0;
				}

				$img_file = FCPATH . 'imgs/Cal-Event-' . $image_no . '.jpg';
				$layout_values = [
					'text_offset' => -200, 'summary_font_size' => 36, 'summary_margin_top' => 260,
					'date_font_size' => 24, 'date_margin_top' => 25, 'time_font_size' => 36,
					'time_margin_top' => 25, 'location_font_size' => 24, 'location_margin_top' => 25,
				];
			}

			$im = $this->cal_image_renderer->render($img_file, $texts, $layout_values, $font_dir);
		}

		if ( ! $im)
		{
			$im = $this->_fallback_image($summary, $font_dir);
		}

		header('Content-type: image/png');
		imagepng($im);
		imagedestroy($im);
	}

	/**
	 * Resolve templates using 3-tier priority:
	 * 1. Venue-specific templates (venue_templates)
	 * 2. Venue type templates (venue_type_templates)
	 * 3. All active templates (fallback)
	 */
	private function _resolve_templates($summary)
	{
		$this->load->model('venue_model');
		$this->load->model('venue_type_model');
		$this->load->model('template_model');

		// Try to match venue from summary
		$venue = $this->venue_model->match_venue($summary);

		if ($venue)
		{
			// Priority 1: Venue-specific templates
			$templates = $this->venue_model->get_venue_templates_with_assets($venue->id);
			if ( ! empty($templates))
			{
				return $templates;
			}

			// Priority 2: Venue type templates
			if ($venue->venue_type_id)
			{
				$templates = $this->venue_type_model->get_templates_with_assets($venue->venue_type_id);
				if ( ! empty($templates))
				{
					return $templates;
				}
			}
		}

		// Priority 3: All active templates
		return $this->template_model->get_active_with_assets();
	}

	/**
	 * Extract layout values array from a template row.
	 */
	private function _template_layout_values($tpl)
	{
		return [
			'photo_x'            => (int) $tpl->photo_x,
			'photo_y'            => (int) $tpl->photo_y,
			'photo_scale'        => (int) $tpl->photo_scale,
			'photo_glow_radius'  => (int) $tpl->photo_glow_radius,
			'photo_glow_color'   => $tpl->photo_glow_color,
			'text_offset'        => (int) $tpl->text_offset,
			'summary_font_size'  => (int) $tpl->summary_font_size,
			'summary_margin_top' => (int) $tpl->summary_margin_top,
			'date_font_size'     => (int) $tpl->date_font_size,
			'date_margin_top'    => (int) $tpl->date_margin_top,
			'time_font_size'     => (int) $tpl->time_font_size,
			'time_margin_top'    => (int) $tpl->time_margin_top,
			'location_font_size' => (int) $tpl->location_font_size,
			'location_margin_top'=> (int) $tpl->location_margin_top,
			'font_color'         => $tpl->font_color,
			'glow_radius'        => (int) $tpl->glow_radius,
			'glow_color'         => $tpl->glow_color,
			'shadow_offset'      => (int) $tpl->shadow_offset,
			'stroke_width'       => (int) $tpl->stroke_width,
			'stroke_color'       => $tpl->stroke_color,
			'text_bg_opacity'    => (int) $tpl->text_bg_opacity,
			'text_bg_color'      => $tpl->text_bg_color,
		];
	}

	/**
	 * Generate a simple fallback image when no template or legacy image is available.
	 * Shows event name on a dark background so share links never 500.
	 */
	private function _fallback_image($summary, $font_dir)
	{
		$w = 1200;
		$h = 630;
		$im = imagecreatetruecolor($w, $h);

		// Dark background
		$bg = imagecolorallocate($im, 30, 30, 30);
		imagefill($im, 0, 0, $bg);

		$white = imagecolorallocate($im, 255, 255, 255);
		$gray = imagecolorallocate($im, 160, 160, 160);
		$font_title = $font_dir . 'Aladin-Regular.ttf';
		$font_bold = $font_dir . 'GEORGIAB.TTF';

		// "Glenn Bennett" centered
		$box = imagettfbbox(60, 0, $font_title, 'Glenn Bennett');
		$tx = ($w - ($box[2] - $box[0])) / 2;
		imagettftext($im, 60, 0, (int) $tx, 220, $white, $font_title, 'Glenn Bennett');

		// "Performs" centered
		$box = imagettfbbox(36, 0, $font_title, 'Performs');
		$tx = ($w - ($box[2] - $box[0])) / 2;
		imagettftext($im, 36, 0, (int) $tx, 280, $white, $font_title, 'Performs');

		// Event summary centered
		if ($summary)
		{
			$summary = strip_tags($summary);
			$box = imagettfbbox(28, 0, $font_bold, $summary);
			$tx = ($w - ($box[2] - $box[0])) / 2;
			imagettftext($im, 28, 0, (int) $tx, 360, $gray, $font_bold, $summary);
		}

		// Footer
		$url = 'GlennBennett.com/cal';
		$box = imagettfbbox(18, 0, $font_bold, $url);
		$tx = ($w - ($box[2] - $box[0])) / 2;
		imagettftext($im, 18, 0, (int) $tx, 540, $gray, $font_bold, $url);

		return $im;
	}

}
