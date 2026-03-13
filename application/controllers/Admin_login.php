<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->load->helper(['url', 'form', 'admin']);
		$this->load->model('admin_user_model');
	}

	public function index()
	{
		if (is_admin_logged())
		{
			redirect('admin', 'refresh');
		}

		$data = [
			'assets' => base_url('assets/admin/')
		];

		$this->load->view('admin/account/login', $data);
	}

	public function check()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->index();
			return;
		}

		$username = $this->input->post('username');
		$password = $this->input->post('password');

		$user = $this->admin_user_model->attempt($username, $password);

		if ($user)
		{
			$this->admin_user_model->login($user);
			redirect('admin', 'refresh');
		}
		else
		{
			$this->session->set_flashdata('alert', 'Invalid username or password.');
			$this->session->set_flashdata('alert-type', 'danger');
			redirect('admin/login', 'refresh');
		}
	}

	public function logout()
	{
		$this->load->model('admin_user_model');
		$this->admin_user_model->logout();
		redirect('admin/login', 'refresh');
	}
}
