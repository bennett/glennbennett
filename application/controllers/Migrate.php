<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/Admin_Controller.php';

class Migrate extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('migration_runner');
    }

    public function index()
    {
        $this->page_data['page']->menu = 'migrate';
        $this->page_data['page']->title = 'Migrations';
        $this->page_data['migrations'] = $this->migration_runner->status();
        $this->page_data['success'] = $this->session->flashdata('migration_success');
        $this->page_data['error'] = $this->session->flashdata('migration_error');

        $this->load->view('admin/migrate', $this->page_data);
    }

    public function run()
    {
        if ($this->input->method() !== 'post') {
            redirect('migrate');
            return;
        }

        $completed = $this->migration_runner->migrate();

        if (empty($completed)) {
            $this->session->set_flashdata('migration_success', 'Nothing to migrate — all migrations have already run.');
        } else {
            $this->session->set_flashdata('migration_success', 'Ran ' . count($completed) . ' migration(s): ' . implode(', ', $completed));
        }

        redirect('migrate');
    }

    public function rollback()
    {
        if ($this->input->method() !== 'post') {
            redirect('migrate');
            return;
        }

        $rolled_back = $this->migration_runner->rollback();

        if (empty($rolled_back)) {
            $this->session->set_flashdata('migration_success', 'Nothing to roll back.');
        } else {
            $this->session->set_flashdata('migration_success', 'Rolled back ' . count($rolled_back) . ' migration(s): ' . implode(', ', $rolled_back));
        }

        redirect('migrate');
    }
}
