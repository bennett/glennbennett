<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cortez extends CI_Controller {

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

//		$this->load->helper('url');

		$this->load->library('grocery_CRUD');
	}


	public function index()
	{
        $todo_count = 0;
        $complete_count = 0;
        foreach($this->priorities as $priority)
        {
            foreach($this->statuses as $status)
            {

                $todos[$status] = $this->cortez_todos_model->get_todos($priority, $status);
                if($status == "Completed")
                {
                    $complete_count += count($todos[$status]);
                }
                else
                {
                    $todo_count += count($todos[$status]);
                }
                
            }
            $all_todos[$priority] = $todos;




        }
//            var_dump($all_totos);
        $data['all_todos'] = $all_todos;
        $data['todo_count'] = $todo_count;
        $data['complete_count'] = $complete_count;
        $data['priorities'] = $this->priorities;
        $data['statuses'] = $this->statuses;
        $this->load->view('cortez/todos', $data);

	}


}
