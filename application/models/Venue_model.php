<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Venue_model extends MY_Model {

	public $table = 'venues';

	public function get_venue_images($venue_id)
	{
		return $this->db
			->select('cal_images.*, venue_images.sort_order as vi_sort')
			->join('cal_images', 'cal_images.id = venue_images.cal_image_id')
			->where('venue_images.venue_id', $venue_id)
			->order_by('venue_images.sort_order', 'ASC')
			->get('venue_images')
			->result();
	}

	public function sync_images($venue_id, $image_ids = [])
	{
		// Remove existing assignments
		$this->db->where('venue_id', $venue_id)->delete('venue_images');

		// Insert new assignments
		if ( ! empty($image_ids))
		{
			$batch = [];
			foreach ($image_ids as $sort => $image_id)
			{
				$batch[] = [
					'venue_id'     => $venue_id,
					'cal_image_id' => $image_id,
					'sort_order'   => $sort,
				];
			}
			$this->db->insert_batch('venue_images', $batch);
		}
	}

	public function get_active_with_images()
	{
		$venues = $this->db
			->where('is_active', 1)
			->get($this->table)
			->result();

		foreach ($venues as &$venue)
		{
			$venue->images = $this->get_venue_images($venue->id);
		}

		return $venues;
	}
}
