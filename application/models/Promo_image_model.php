<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promo_image_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->table = 'promo_images';
	}

	public function get_by_category($category)
	{
		return $this->db->get_where($this->table, ['category' => $category, 'is_active' => 1])->result();
	}

	public function get_active()
	{
		return $this->db->get_where($this->table, ['is_active' => 1])->result();
	}
}
