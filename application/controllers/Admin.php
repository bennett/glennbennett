<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/Admin_Controller.php';

class Admin extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cal_image_model');
		$this->load->model('venue_model');
	}

	public function index()
	{
		$this->page_data['page']->title = 'Dashboard';
		$this->page_data['page']->menu = 'dashboard';

		$this->page_data['image_count'] = $this->cal_image_model->countAll();
		$this->page_data['active_image_count'] = $this->cal_image_model->countActive();
		$this->page_data['venue_count'] = $this->venue_model->countAll();

		$this->load->view('admin/dashboard', $this->page_data);
	}

	// --- Image Management ---

	public function images()
	{
		$this->page_data['page']->title = 'Calendar Images';
		$this->page_data['page']->menu = 'images';

		$this->page_data['images'] = $this->cal_image_model->get_all_with_layouts();

		$this->load->view('admin/images', $this->page_data);
	}

	public function upload_image()
	{
		$config['upload_path']   = FCPATH . 'imgs/cal-backgrounds/';
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['max_size']      = 5120; // 5MB
		$config['encrypt_name']  = TRUE;

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('image_file'))
		{
			$this->session->set_flashdata('alert', $this->upload->display_errors('', ''));
			$this->session->set_flashdata('alert-type', 'danger');
		}
		else
		{
			$upload = $this->upload->data();

			$image_data = [
				'filename'      => $upload['file_name'],
				'image_path'    => 'imgs/cal-backgrounds/',
				'original_name' => $upload['orig_name'],
				'width'         => $upload['image_width'],
				'height'        => $upload['image_height'],
			];

			$image_id = $this->cal_image_model->create($image_data);

			// Create default layout
			$this->cal_image_model->create_layout($image_id);

			$this->session->set_flashdata('alert', 'Image uploaded successfully.');
			$this->session->set_flashdata('alert-type', 'success');
		}

		redirect('admin/images', 'refresh');
	}

	public function toggle_image($id)
	{
		$image = $this->cal_image_model->getById($id);

		if ($image)
		{
			$this->cal_image_model->update($id, [
				'is_active' => $image->is_active ? 0 : 1
			]);

			$status = $image->is_active ? 'deactivated' : 'activated';
			$this->session->set_flashdata('alert', "Image {$status}.");
			$this->session->set_flashdata('alert-type', 'success');
		}

		redirect('admin/images', 'refresh');
	}

	public function delete_image($id)
	{
		$image = $this->cal_image_model->getById($id);

		if ($image)
		{
			$file_path = FCPATH . $image->image_path . $image->filename;
			if (file_exists($file_path))
			{
				unlink($file_path);
			}

			$this->cal_image_model->delete($id);

			$this->session->set_flashdata('alert', 'Image deleted.');
			$this->session->set_flashdata('alert-type', 'success');
		}

		redirect('admin/images', 'refresh');
	}

	// --- Layout Editor ---

	public function image_layout($id)
	{
		$image = $this->cal_image_model->getById($id);

		if ( ! $image)
		{
			redirect('admin/images', 'refresh');
		}

		$this->page_data['page']->title = 'Text Layout Editor';
		$this->page_data['page']->menu = 'images';

		$this->page_data['image'] = $image;
		$this->page_data['layout'] = $this->cal_image_model->get_layout($id);

		$this->load->view('admin/image_layout', $this->page_data);
	}

	public function save_layout()
	{
		$image_id = $this->input->post('cal_image_id');

		$layout_data = [
			'text_offset'         => (int) $this->input->post('text_offset'),
			'summary_font_size'   => (int) $this->input->post('summary_font_size'),
			'summary_margin_top'  => (int) $this->input->post('summary_margin_top'),
			'date_font_size'      => (int) $this->input->post('date_font_size'),
			'date_margin_top'     => (int) $this->input->post('date_margin_top'),
			'time_font_size'      => (int) $this->input->post('time_font_size'),
			'time_margin_top'     => (int) $this->input->post('time_margin_top'),
			'location_font_size'  => (int) $this->input->post('location_font_size'),
			'location_margin_top' => (int) $this->input->post('location_margin_top'),
		];

		$this->cal_image_model->save_layout($image_id, $layout_data);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(['status' => 'ok']));
	}

	public function preview_image($id)
	{
		$image = $this->cal_image_model->getById($id);
		$layout = $this->cal_image_model->get_layout($id);

		if ( ! $image || ! $layout)
		{
			show_404();
		}

		$img_file = FCPATH . $image->image_path . $image->filename;

		if ( ! file_exists($img_file))
		{
			show_404();
		}

		$this->load->library('cal_image_renderer');

		$im = $this->cal_image_renderer->render($img_file, [
			'summary'  => 'Sample Venue Name',
			'date'     => 'Saturday - Mar 14',
			'time'     => '10:00 am - 1:00 pm',
			'location' => '123 Main St, Anytown, CA',
		], [
			'text_offset'         => (int) $layout->text_offset,
			'summary_font_size'   => (int) $layout->summary_font_size,
			'summary_margin_top'  => (int) $layout->summary_margin_top,
			'date_font_size'      => (int) $layout->date_font_size,
			'date_margin_top'     => (int) $layout->date_margin_top,
			'time_font_size'      => (int) $layout->time_font_size,
			'time_margin_top'     => (int) $layout->time_margin_top,
			'location_font_size'  => (int) $layout->location_font_size,
			'location_margin_top' => (int) $layout->location_margin_top,
		], FCPATH . 'fonts/');

		if ( ! $im)
		{
			show_404();
		}

		header('Content-Type: image/png');
		imagepng($im);
		imagedestroy($im);
	}

	// --- Account ---

	public function change_password()
	{
		$this->page_data['page']->title = 'Change Password';
		$this->page_data['page']->menu = '';

		if ($this->input->method() === 'post')
		{
			$this->load->library('form_validation');
			$this->load->model('admin_user_model');

			$this->form_validation->set_rules('current_password', 'Current Password', 'required');
			$this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[4]');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');

			if ($this->form_validation->run() !== FALSE)
			{
				$user_id = admin_logged('id');
				$user = $this->db->get_where('admin_users', ['id' => $user_id])->row();

				if (password_verify($this->input->post('current_password'), $user->password_hash))
				{
					$this->admin_user_model->change_password($user_id, $this->input->post('new_password'));

					$this->session->set_flashdata('alert', 'Password changed successfully.');
					$this->session->set_flashdata('alert-type', 'success');
					redirect('admin', 'refresh');
				}
				else
				{
					$this->session->set_flashdata('alert', 'Current password is incorrect.');
					$this->session->set_flashdata('alert-type', 'danger');
				}
			}
		}

		$this->load->view('admin/change_password', $this->page_data);
	}

	// --- Venue Management ---

	public function venues()
	{
		$this->page_data['page']->title = 'Venues';
		$this->page_data['page']->menu = 'venues';

		$this->page_data['venues'] = $this->venue_model->get();

		$this->load->view('admin/venues', $this->page_data);
	}

	public function venue_edit($id = null)
	{
		$this->page_data['page']->title = $id ? 'Edit Venue' : 'Add Venue';
		$this->page_data['page']->menu = 'venues';

		$this->page_data['venue'] = $id ? $this->venue_model->getById($id) : null;

		$this->load->view('admin/venue_edit', $this->page_data);
	}

	public function venue_save()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('match_pattern', 'Match Pattern', 'trim|required');

		if ($this->form_validation->run() == FALSE)
		{
			$id = $this->input->post('id');
			$this->venue_edit($id);
			return;
		}

		$data = [
			'name'          => $this->input->post('name'),
			'match_pattern' => $this->input->post('match_pattern'),
			'match_type'    => $this->input->post('match_type'),
			'is_active'     => $this->input->post('is_active') ? 1 : 0,
		];

		// Handle venue logo upload
		if ( ! empty($_FILES['venue_logo']['name']))
		{
			$config['upload_path']   = FCPATH . 'imgs/cal/';
			$config['allowed_types'] = 'jpg|jpeg|png';
			$config['max_size']      = 2048;
			$config['encrypt_name']  = TRUE;

			$this->load->library('upload', $config);

			if ($this->upload->do_upload('venue_logo'))
			{
				$upload = $this->upload->data();
				$data['venue_logo'] = '/imgs/cal/' . $upload['file_name'];
			}
		}

		$id = $this->input->post('id');

		if ($id)
		{
			$this->venue_model->update($id, $data);
		}
		else
		{
			$id = $this->venue_model->create($data);
		}

		$this->session->set_flashdata('alert', 'Venue saved successfully.');
		$this->session->set_flashdata('alert-type', 'success');
		redirect('admin/venues', 'refresh');
	}

	public function venue_delete($id)
	{
		$this->venue_model->delete($id);

		$this->session->set_flashdata('alert', 'Venue deleted.');
		$this->session->set_flashdata('alert-type', 'success');
		redirect('admin/venues', 'refresh');
	}
}
