<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template_background_model extends MY_Model {

	public $table = 'template_backgrounds';

	public function get_active()
	{
		return $this->db->where('is_active', 1)->get($this->table)->result();
	}
}
