<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cal_image_model extends MY_Model {

	public $table = 'cal_images';

	public function countActive()
	{
		return $this->db->where('is_active', 1)->count_all_results($this->table);
	}

	public function get_all_with_layouts()
	{
		return $this->db
			->select('cal_images.*, cal_image_layouts.id as layout_id')
			->join('cal_image_layouts', 'cal_image_layouts.cal_image_id = cal_images.id', 'left')
			->order_by('cal_images.sort_order', 'ASC')
			->get($this->table)
			->result();
	}

	public function get_active()
	{
		return $this->db
			->where('is_active', 1)
			->order_by('sort_order', 'ASC')
			->get($this->table)
			->result();
	}

	public function get_layout($image_id)
	{
		$layout = $this->db->get_where('cal_image_layouts', ['cal_image_id' => $image_id])->row();

		if ( ! $layout)
		{
			$this->create_layout($image_id);
			$layout = $this->db->get_where('cal_image_layouts', ['cal_image_id' => $image_id])->row();
		}

		return $layout;
	}

	public function create_layout($image_id, $data = [])
	{
		$defaults = [
			'cal_image_id'        => $image_id,
			'text_offset'         => 0,
			'summary_font_size'   => 36,
			'summary_margin_top'  => 180,
			'date_font_size'      => 24,
			'date_margin_top'     => 25,
			'time_font_size'      => 36,
			'time_margin_top'     => 25,
			'location_font_size'  => 24,
			'location_margin_top' => 25,
			'glow_radius'         => 0,
			'shadow_offset'       => 0,
			'font_color'          => '#000000',
			'glow_color'          => '#ffffff',
			'stroke_width'        => 0,
			'stroke_color'        => '#000000',
		];

		$this->db->insert('cal_image_layouts', array_merge($defaults, $data));
		return $this->db->insert_id();
	}

	public function save_layout($image_id, $data)
	{
		$existing = $this->db->get_where('cal_image_layouts', ['cal_image_id' => $image_id])->row();

		if ($existing)
		{
			$this->db->where('cal_image_id', $image_id);
			$this->db->update('cal_image_layouts', $data);
		}
		else
		{
			$data['cal_image_id'] = $image_id;
			$this->db->insert('cal_image_layouts', $data);
		}
	}
}
