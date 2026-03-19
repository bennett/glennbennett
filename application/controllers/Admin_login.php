<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->load->library('google_auth');
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
			'assets'         => base_url('assets/admin/'),
			'google_enabled' => $this->google_auth->is_configured(),
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

	public function google()
	{
		if ( ! $this->google_auth->is_configured()) {
			redirect('admin/login', 'refresh');
		}

		redirect($this->google_auth->get_auth_url());
	}

	public function google_callback()
	{
		$code  = $this->input->get('code');
		$state = $this->input->get('state');

		if (empty($code)) {
			$this->session->set_flashdata('alert', 'Google login was cancelled.');
			$this->session->set_flashdata('alert-type', 'danger');
			redirect('admin/login', 'refresh');
			return;
		}

		$access_token = $this->google_auth->handle_callback($code, $state);
		if ( ! $access_token) {
			$this->session->set_flashdata('alert', 'Google authentication failed.');
			$this->session->set_flashdata('alert-type', 'danger');
			redirect('admin/login', 'refresh');
			return;
		}

		$user_info = $this->google_auth->get_user_info($access_token);
		if ( ! $user_info) {
			$this->session->set_flashdata('alert', 'Could not retrieve Google account info.');
			$this->session->set_flashdata('alert-type', 'danger');
			redirect('admin/login', 'refresh');
			return;
		}

		if ( ! $this->google_auth->is_allowed_email($user_info['email'])) {
			$this->session->set_flashdata('alert', 'Access denied. That Google account is not authorized.');
			$this->session->set_flashdata('alert-type', 'danger');
			redirect('admin/login', 'refresh');
			return;
		}

		$user = $this->admin_user_model->getById(1);
		if ($user) {
			$this->admin_user_model->login($user);
			redirect('admin', 'refresh');
		} else {
			$this->session->set_flashdata('alert', 'No admin user found.');
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
