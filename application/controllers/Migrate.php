<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('migration_runner');
    }

    public function index()
    {
        $data = array(
            'migrations' => $this->migration_runner->status(),
            'success' => $this->session->flashdata('migration_success'),
            'error' => $this->session->flashdata('migration_error'),
        );

        $this->load->view('migrate/index', $data);
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
