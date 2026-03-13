<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Controller extends CI_Controller {

	public $page_data;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->load->helper(['url', 'admin']);

		if ( ! is_admin_logged())
		{
			redirect('admin/login', 'refresh');
		}

		$this->page_data['url'] = (object) [
			'assets' => base_url('assets/admin/')
		];

		$this->page_data['app'] = (object) [
			'site_title' => 'Glenn Bennett Admin'
		];

		$this->page_data['page'] = (object) [
			'title'   => 'Dashboard',
			'menu'    => 'dashboard',
			'submenu' => '',
		];
	}
}
