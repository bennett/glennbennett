<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cortez_edit extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

    public $priorities = array("High", "Medium", "Low");
    public $statuses = array("Not started", "In progress", "Completed");

	public function __construct()
	{
		parent::__construct();

		$this->load->database();

        $this->load->model('cortez_todos_model');

		$this->load->helper('url');

		$this->load->library('grocery_CRUD');
	}


	public function _example_output($output = null)
	{
		$this->load->view('cortez/crud',(array)$output);
	}

	public function index()
	{


		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('bootstrap-v4');
			$crud->set_table('cortez_todos');
			$crud->set_subject('Todos');
            $crud->unset_clone();
            $crud->unset_back_to_list();
            $crud->required_fields('task','priority','status');
            $crud->field_type('updated', 'hidden');
            $crud->field_type('created', 'hidden');
            $crud->callback_before_insert(array($this,'insert_callback'));
            $crud->callback_before_update(array($this,'update_callback'));

			$output = $crud->render();

			$this->_example_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}


    }
    
    public function delete_todo($id)
	{
        $this->cortez_todos_model->delete($id);
        redirect('/cortez', 'refresh');
    }
    
    function insert_callback($post_array) {

        $post_array['created'] = date('Y-m-d H:i:s');
        $post_array['updated'] = date('Y-m-d H:i:s');
     
        return $post_array;
    }    
    
    function update_callback($post_array) {
    
        $post_array['updated'] = date('Y-m-d H:i:s');
     
        return $post_array;
    }      
    
}