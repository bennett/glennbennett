<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tracks_model extends MY_Model {

	public $table = 'tracks';

	public function __construct()
	{
		parent::__construct();
        $this->load->library('grocery_CRUD');
        $this->load->library('MP3File');
        $this->load->config('globals');
	}

    public function get_tracks_bySong($song_id)
    {
       return $this->getByWhere([
		'song_id' => $song_id
		]);
    }


}

/* End of file Roles_model.php */
/* Location: ./application/models/Roles_model.php */