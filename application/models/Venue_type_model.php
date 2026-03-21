<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Venue_type_model extends MY_Model {

	public $table = 'venue_types';

	public function get_active()
	{
		return $this->db
			->where('is_active', 1)
			->order_by("CASE WHEN slug = 'general' THEN 0 ELSE 1 END", '', FALSE)
			->order_by('name', 'ASC')
			->get($this->table)
			->result();
	}

	public function sync_templates($type_id, $template_ids = [])
	{
		$this->db->where('venue_type_id', $type_id)->delete('venue_type_templates');

		if ( ! empty($template_ids))
		{
			$batch = [];
			foreach ($template_ids as $tid)
			{
				$batch[] = [
					'venue_type_id' => $type_id,
					'template_id'   => $tid,
				];
			}
			$this->db->insert_batch('venue_type_templates', $batch);
		}
	}

	public function get_template_ids($type_id)
	{
		$rows = $this->db
			->select('template_id')
			->where('venue_type_id', $type_id)
			->get('venue_type_templates')
			->result();

		return array_map(function($r) { return $r->template_id; }, $rows);
	}

	public function get_templates_with_assets($type_id)
	{
		return $this->db
			->select('templates.*, template_backgrounds.filename as bg_filename, template_photos.filename as photo_filename')
			->join('templates', 'templates.id = venue_type_templates.template_id')
			->join('template_backgrounds', 'template_backgrounds.id = templates.background_id')
			->join('template_photos', 'template_photos.id = templates.photo_id')
			->where('venue_type_templates.venue_type_id', $type_id)
			->where('templates.is_active', 1)
			->get('venue_type_templates')
			->result();
	}

	public function get_template_count($type_id)
	{
		return $this->db
			->where('venue_type_id', $type_id)
			->count_all_results('venue_type_templates');
	}
}
