<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template_model extends MY_Model {

	public $table = 'templates';

	private $bg_default_fields = [
		'text_offset', 'summary_margin_top', 'summary_font_size',
		'date_font_size', 'date_margin_top', 'time_font_size', 'time_margin_top',
		'location_font_size', 'location_margin_top', 'font_color',
		'glow_radius', 'glow_color', 'shadow_offset', 'stroke_width', 'stroke_color',
	];

	private $photo_default_fields = [
		'photo_x', 'photo_y', 'photo_scale', 'photo_glow_radius', 'photo_glow_color',
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

				// Inherit text defaults from background
				foreach ($this->bg_default_fields as $f)
				{
					$data[$f] = $bg->$f;
				}

				// Inherit position defaults from photo
				foreach ($this->photo_default_fields as $f)
				{
					$data[$f] = $photo->$f;
				}

				$this->db->insert($this->table, $data);
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

				// Inherit text defaults from background
				foreach ($this->bg_default_fields as $f)
				{
					$data[$f] = $bg->$f;
				}

				// Inherit position defaults from photo
				foreach ($this->photo_default_fields as $f)
				{
					$data[$f] = $photo->$f;
				}

				$this->db->insert($this->table, $data);
			}
		}
	}

	public function get_all_with_assets()
	{
		return $this->db
			->select('templates.*, template_backgrounds.filename as bg_filename, template_backgrounds.original_name as bg_name, template_photos.filename as photo_filename, template_photos.original_name as photo_name')
			->join('template_backgrounds', 'template_backgrounds.id = templates.background_id')
			->join('template_photos', 'template_photos.id = templates.photo_id')
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

	public function save_layout($id, $data)
	{
		$this->db->where('id', $id)->update($this->table, $data);
	}
}
