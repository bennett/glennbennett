<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Venue_detail_model extends MY_Model {

	public $table = 'venue_details';

	public function get_by_venue($venue_id)
	{
		return $this->db
			->where('venue_id', $venue_id)
			->get($this->table)
			->row();
	}

	public function save_for_venue($venue_id, $data)
	{
		$existing = $this->get_by_venue($venue_id);

		$data['venue_id'] = $venue_id;

		if ($existing)
		{
			$this->db->where('venue_id', $venue_id)->update($this->table, $data);
		}
		else
		{
			$this->db->insert($this->table, $data);
		}
	}
}
