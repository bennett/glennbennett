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

	/**
	 * Match an event summary to a venue using match_pattern/match_type rules.
	 * Returns the matched venue row or null.
	 */
	public function match_venue($summary)
	{
		$venues = $this->db
			->where('is_active', 1)
			->get($this->table)
			->result();

		$alpha_summary = strtoupper(preg_replace('/[^a-zA-Z]/', '', $summary));

		foreach ($venues as $venue)
		{
			if ($venue->match_type === 'exact' && $summary === $venue->match_pattern)
			{
				return $venue;
			}
			elseif ($venue->match_type === 'contains' && strpos($summary, $venue->match_pattern) !== false)
			{
				return $venue;
			}
			elseif ($venue->match_type === 'alpha_only')
			{
				$alpha_pattern = strtoupper(preg_replace('/[^a-zA-Z]/', '', $venue->match_pattern));
				if (strpos($alpha_summary, $alpha_pattern) !== false)
				{
					return $venue;
				}
			}
		}

		return null;
	}

	public function get_venue_template_ids($venue_id)
	{
		$rows = $this->db
			->select('template_id')
			->where('venue_id', $venue_id)
			->get('venue_templates')
			->result();

		return array_map(function($r) { return $r->template_id; }, $rows);
	}

	public function get_venue_templates_with_assets($venue_id)
	{
		return $this->db
			->select('templates.*, template_backgrounds.filename as bg_filename, template_photos.filename as photo_filename')
			->join('templates', 'templates.id = venue_templates.template_id')
			->join('template_backgrounds', 'template_backgrounds.id = templates.background_id')
			->join('template_photos', 'template_photos.id = templates.photo_id')
			->where('venue_templates.venue_id', $venue_id)
			->where('templates.is_active', 1)
			->get('venue_templates')
			->result();
	}

	public function sync_templates($venue_id, $template_ids = [])
	{
		$this->db->where('venue_id', $venue_id)->delete('venue_templates');

		if ( ! empty($template_ids))
		{
			$batch = [];
			foreach ($template_ids as $tid)
			{
				$batch[] = [
					'venue_id'    => $venue_id,
					'template_id' => $tid,
				];
			}
			$this->db->insert_batch('venue_templates', $batch);
		}
	}
}
