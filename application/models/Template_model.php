<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template_model extends MY_Model {

	public $table = 'templates';

	private $photo_default_fields = [
		'photo_x', 'photo_y', 'photo_scale', 'photo_glow_radius', 'photo_glow_color',
		'text_offset', 'summary_margin_top', 'summary_font_size',
		'date_font_size', 'date_margin_top', 'time_font_size', 'time_margin_top',
		'location_font_size', 'location_margin_top', 'font_color',
		'glow_radius', 'glow_color', 'shadow_offset', 'stroke_width', 'stroke_color',
	];

	public function generate_for_background($bg_id)
	{
		$CI =& get_instance();
		$CI->load->model('template_photo_model');
		$CI->load->model('template_background_model');

		$bg = $CI->template_background_model->getById($bg_id);
		$photos = $CI->template_photo_model->get_active();

		foreach ($photos as $photo)
		{
			$exists = $this->db->where(['background_id' => $bg_id, 'photo_id' => $photo->id])
				->count_all_results($this->table);

			if ( ! $exists)
			{
				$data = [
					'background_id' => $bg_id,
					'photo_id'      => $photo->id,
				];

				// Inherit all defaults from photo
				foreach ($this->photo_default_fields as $f)
				{
					$data[$f] = $photo->$f;
				}

				$this->db->insert($this->table, $data);

				// Set default name using bg + photo names
				$template_id = $this->db->insert_id();
				$this->db->where('id', $template_id)
					->update($this->table, ['name' => $bg->original_name . '_' . $photo->original_name]);
			}
		}
	}

	public function generate_for_photo($photo_id)
	{
		$CI =& get_instance();
		$CI->load->model('template_background_model');
		$CI->load->model('template_photo_model');

		$photo = $CI->template_photo_model->getById($photo_id);
		$bgs = $CI->template_background_model->get_active();

		foreach ($bgs as $bg)
		{
			$exists = $this->db->where(['background_id' => $bg->id, 'photo_id' => $photo_id])
				->count_all_results($this->table);

			if ( ! $exists)
			{
				$data = [
					'background_id' => $bg->id,
					'photo_id'      => $photo_id,
				];

				// Inherit all defaults from photo
				foreach ($this->photo_default_fields as $f)
				{
					$data[$f] = $photo->$f;
				}

				$this->db->insert($this->table, $data);

				// Set default name using bg + photo names
				$template_id = $this->db->insert_id();
				$this->db->where('id', $template_id)
					->update($this->table, ['name' => $bg->original_name . '_' . $photo->original_name]);
			}
		}
	}

	public function get_active_with_assets()
	{
		return $this->db
			->select('templates.*, template_backgrounds.filename as bg_filename, template_photos.filename as photo_filename')
			->join('template_backgrounds', 'template_backgrounds.id = templates.background_id')
			->join('template_photos', 'template_photos.id = templates.photo_id')
			->where('templates.is_active', 1)
			->where('templates.is_ready', 1)
			->order_by('templates.id', 'ASC')
			->get($this->table)
			->result();
	}

	public function get_all_with_assets()
	{
		return $this->db
			->select('templates.*, template_backgrounds.filename as bg_filename, template_backgrounds.original_name as bg_name, template_photos.filename as photo_filename, template_photos.original_name as photo_name')
			->join('template_backgrounds', 'template_backgrounds.id = templates.background_id', 'left')
			->join('template_photos', 'template_photos.id = templates.photo_id', 'left')
			->order_by('templates.is_orphaned', 'DESC')
			->order_by('templates.is_ready', 'ASC')
			->order_by('templates.id', 'ASC')
			->get($this->table)
			->result();
	}

	public function get_with_assets($id)
	{
		return $this->db
			->select('templates.*, template_backgrounds.filename as bg_filename, template_backgrounds.original_name as bg_name, template_photos.filename as photo_filename, template_photos.original_name as photo_name')
			->join('template_backgrounds', 'template_backgrounds.id = templates.background_id')
			->join('template_photos', 'template_photos.id = templates.photo_id')
			->where('templates.id', $id)
			->get($this->table)
			->row();
	}

	/**
	 * Mark all templates using a given background as not ready.
	 */
	public function unready_by_background($background_id)
	{
		$this->db->where('background_id', $background_id)
			->where('is_ready', 1)
			->update($this->table, ['is_ready' => 0]);
	}

	/**
	 * Mark all templates using a given photo as not ready.
	 */
	public function unready_by_photo($photo_id)
	{
		$this->db->where('photo_id', $photo_id)
			->where('is_ready', 1)
			->update($this->table, ['is_ready' => 0]);
	}

	/**
	 * Mark all templates using a given background as orphaned.
	 */
	public function orphan_by_background($background_id)
	{
		$this->db->where('background_id', $background_id)
			->update($this->table, ['is_orphaned' => 1, 'is_ready' => 0]);
	}

	/**
	 * Mark all templates using a given photo as orphaned.
	 */
	public function orphan_by_photo($photo_id)
	{
		$this->db->where('photo_id', $photo_id)
			->update($this->table, ['is_orphaned' => 1, 'is_ready' => 0]);
	}

	/**
	 * Delete all orphaned templates.
	 */
	public function delete_orphaned()
	{
		$this->db->where('is_orphaned', 1)->delete($this->table);
		return $this->db->affected_rows();
	}

	public function save_layout($id, $data)
	{
		$this->db->where('id', $id)->update($this->table, $data);
	}

	/**
	 * Get venue type names assigned to a template.
	 */
	public function get_venue_types_for_template($template_id)
	{
		return $this->db
			->select('venue_types.id, venue_types.name, venue_types.slug')
			->join('venue_types', 'venue_types.id = venue_type_templates.venue_type_id')
			->where('venue_type_templates.template_id', $template_id)
			->order_by("CASE WHEN venue_types.slug = 'general' THEN 0 ELSE 1 END", '', FALSE)
			->order_by('venue_types.name', 'ASC')
			->get('venue_type_templates')
			->result();
	}

	/**
	 * Get venue names assigned to a template.
	 */
	public function get_venues_for_template($template_id)
	{
		return $this->db
			->select('venues.id, venues.name')
			->join('venues', 'venues.id = venue_templates.venue_id')
			->where('venue_templates.template_id', $template_id)
			->order_by('venues.name', 'ASC')
			->get('venue_templates')
			->result();
	}

	/**
	 * Get venue_type_id array for a template.
	 */
	public function get_venue_type_ids($template_id)
	{
		$rows = $this->db
			->select('venue_type_id')
			->where('template_id', $template_id)
			->get('venue_type_templates')
			->result();

		return array_map(function($r) { return $r->venue_type_id; }, $rows);
	}

	/**
	 * Get venue_id array for a template.
	 */
	public function get_venue_ids($template_id)
	{
		$rows = $this->db
			->select('venue_id')
			->where('template_id', $template_id)
			->get('venue_templates')
			->result();

		return array_map(function($r) { return $r->venue_id; }, $rows);
	}

	/**
	 * Sync venue type assignments for a template.
	 */
	public function sync_venue_types($template_id, $type_ids = [])
	{
		$this->db->where('template_id', $template_id)->delete('venue_type_templates');

		if ( ! empty($type_ids))
		{
			$batch = [];
			foreach ($type_ids as $tid)
			{
				$batch[] = [
					'template_id'   => $template_id,
					'venue_type_id' => $tid,
				];
			}
			$this->db->insert_batch('venue_type_templates', $batch);
		}
	}

	/**
	 * Sync venue assignments for a template.
	 */
	public function sync_venues($template_id, $venue_ids = [])
	{
		$this->db->where('template_id', $template_id)->delete('venue_templates');

		if ( ! empty($venue_ids))
		{
			$batch = [];
			foreach ($venue_ids as $vid)
			{
				$batch[] = [
					'template_id' => $template_id,
					'venue_id'    => $vid,
				];
			}
			$this->db->insert_batch('venue_templates', $batch);
		}
	}
}
