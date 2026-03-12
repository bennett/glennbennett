<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Songs_model extends MY_Model {

	public $table = 'songs';

	public function __construct()
	{
		parent::__construct();
//        $this->load->library('grocery_CRUD');
//        $this->load->library('MP3File');
//        $this->load->config('globals');
	}

    public function get_song($song_id)
    {
        $song =  $this->getById($song_id);

        return $this->_add_tracks_to_song($song);
    }

    // Return covers that are valid
    // Have track files
    public function get_covers()
    {
        return $this->get_songs('1');
    }

    public function get_originals()
    {
        return $this->get_songs('0');;
    }



    public function get_songs($song_type)
    {

        $songs =  $this->getByWhere([
			'cover' => $song_type
		]);

        $new_songs = array();

        foreach($songs as $song)
        {

            $new_songs[] = $this->_add_tracks_to_song($song);
        }

        return $new_songs;
    }

    public function _add_tracks_to_song($song)
    {
        $tracks = $this->tracks_model->get_tracks_bySong($song->id);

        if(count($tracks) > 0)
        {

            $featured = $tracks[0];  // Default featured track
            foreach($tracks as $track)
            {
                if($track->featured == '1' )
                {
                    $featured = $track;
                }
            }
            $song->featured_track = $featured;
            $song->tracks = $tracks;
        }

        return $song;
    }





}

/* End of file Roles_model.php */
/* Location: ./application/models/Roles_model.php */