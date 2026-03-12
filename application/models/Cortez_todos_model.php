<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cortez_todos_model extends MY_Model {

	public $table = 'cortez_todos';

	public function __construct()
	{
		parent::__construct();
//        $this->load->library('grocery_CRUD');
//        $this->load->library('MP3File');
//        $this->load->config('globals');
	}





    public function get_todos($priority, $status)
    {
        return  $this->getByWhere([
			'priority' => $priority,
            'status' => $status,
		]);

    }
}