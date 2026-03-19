<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/Admin_Controller.php';

class Admin extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cal_image_model');
		$this->load->model('venue_model');
		$this->load->model('template_background_model');
		$this->load->model('template_photo_model');
		$this->load->model('template_model');
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

		if ( ! $image)
		{
			if ($this->input->is_ajax_request())
			{
				$this->output->set_content_type('application/json')
					->set_output(json_encode(['status' => 'error']));
				return;
			}
			redirect('admin/images', 'refresh');
		}

		$new_state = $image->is_active ? 0 : 1;
		$this->cal_image_model->update($id, ['is_active' => $new_state]);

		if ($this->input->is_ajax_request())
		{
			$this->output->set_content_type('application/json')
				->set_output(json_encode(['status' => 'ok', 'is_active' => $new_state]));
			return;
		}

		$status = $image->is_active ? 'deactivated' : 'activated';
		$this->session->set_flashdata('alert', "Image {$status}.");
		$this->session->set_flashdata('alert-type', 'success');
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
			'glow_radius'        => (int) $this->input->post('glow_radius'),
			'shadow_offset'      => (int) $this->input->post('shadow_offset'),
			'font_color'         => $this->input->post('font_color'),
			'glow_color'         => $this->input->post('glow_color'),
			'stroke_width'       => (int) $this->input->post('stroke_width'),
			'stroke_color'       => $this->input->post('stroke_color'),
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

		// Allow query string overrides for live preview
		$text_offset = $this->input->get('text_offset') !== null
			? (int) $this->input->get('text_offset')
			: (int) $layout->text_offset;
		$summary_margin_top = $this->input->get('summary_margin_top') !== null
			? (int) $this->input->get('summary_margin_top')
			: (int) $layout->summary_margin_top;
		$glow_radius = $this->input->get('glow_radius') !== null
			? (int) $this->input->get('glow_radius')
			: (int) $layout->glow_radius;
		$shadow_offset = $this->input->get('shadow_offset') !== null
			? (int) $this->input->get('shadow_offset')
			: (int) $layout->shadow_offset;
		$font_color = $this->input->get('font_color') !== null
			? $this->input->get('font_color')
			: $layout->font_color;
		$glow_color = $this->input->get('glow_color') !== null
			? $this->input->get('glow_color')
			: $layout->glow_color;
		$stroke_width = $this->input->get('stroke_width') !== null
			? (int) $this->input->get('stroke_width')
			: (int) $layout->stroke_width;
		$stroke_color = $this->input->get('stroke_color') !== null
			? $this->input->get('stroke_color')
			: $layout->stroke_color;

		$im = $this->cal_image_renderer->render($img_file, [
			'summary'  => 'Sample Venue Name',
			'date'     => 'Saturday - Mar 14',
			'time'     => '10:00 am - 1:00 pm',
			'location' => '123 Main St, Anytown, CA',
		], [
			'text_offset'         => $text_offset,
			'summary_font_size'   => (int) $layout->summary_font_size,
			'summary_margin_top'  => $summary_margin_top,
			'date_font_size'      => (int) $layout->date_font_size,
			'date_margin_top'     => (int) $layout->date_margin_top,
			'time_font_size'      => (int) $layout->time_font_size,
			'time_margin_top'     => (int) $layout->time_margin_top,
			'location_font_size'  => (int) $layout->location_font_size,
			'location_margin_top' => (int) $layout->location_margin_top,
			'glow_radius'        => $glow_radius,
			'shadow_offset'      => $shadow_offset,
			'font_color'         => $font_color,
			'glow_color'         => $glow_color,
			'stroke_width'       => $stroke_width,
			'stroke_color'       => $stroke_color,
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

	// --- Template Backgrounds ---

	public function template_backgrounds()
	{
		$this->page_data['page']->title = 'Template Backgrounds';
		$this->page_data['page']->menu = 'template_backgrounds';

		$this->page_data['backgrounds'] = $this->template_background_model->get();

		$this->load->view('admin/template_backgrounds', $this->page_data);
	}

	public function upload_template_background()
	{
		$config['upload_path']   = FCPATH . 'imgs/template-backgrounds/';
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['max_size']      = 5120;
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

			$bg_id = $this->template_background_model->create([
				'filename'      => $upload['file_name'],
				'original_name' => $upload['orig_name'],
				'width'         => $upload['image_width'],
				'height'        => $upload['image_height'],
			]);

			$this->template_model->generate_for_background($bg_id);

			$this->session->set_flashdata('alert', 'Background uploaded successfully.');
			$this->session->set_flashdata('alert-type', 'success');
		}

		redirect('admin/template_backgrounds', 'refresh');
	}

	public function toggle_template_background($id)
	{
		$bg = $this->template_background_model->getById($id);

		if ( ! $bg)
		{
			if ($this->input->is_ajax_request())
			{
				$this->output->set_content_type('application/json')
					->set_output(json_encode(['status' => 'error']));
				return;
			}
			redirect('admin/template_backgrounds', 'refresh');
		}

		$new_state = $bg->is_active ? 0 : 1;
		$this->template_background_model->update($id, ['is_active' => $new_state]);

		if ($this->input->is_ajax_request())
		{
			$this->output->set_content_type('application/json')
				->set_output(json_encode(['status' => 'ok', 'is_active' => $new_state]));
			return;
		}

		redirect('admin/template_backgrounds', 'refresh');
	}

	public function delete_template_background($id)
	{
		$bg = $this->template_background_model->getById($id);

		if ($bg)
		{
			$file_path = FCPATH . 'imgs/template-backgrounds/' . $bg->filename;
			if (file_exists($file_path))
			{
				unlink($file_path);
			}
			$this->template_background_model->delete($id);

			$this->session->set_flashdata('alert', 'Background deleted.');
			$this->session->set_flashdata('alert-type', 'success');
		}

		redirect('admin/template_backgrounds', 'refresh');
	}

	public function template_background_defaults($id)
	{
		$bg = $this->template_background_model->getById($id);

		if ( ! $bg)
		{
			redirect('admin/template_backgrounds', 'refresh');
		}

		$this->page_data['page']->title = 'Background Text Defaults';
		$this->page_data['page']->menu = 'template_backgrounds';
		$this->page_data['background'] = $bg;

		$this->load->view('admin/template_background_defaults', $this->page_data);
	}

	public function save_template_background_defaults()
	{
		$id = $this->input->post('background_id');

		$data = [
			'text_offset'         => (int) $this->input->post('text_offset'),
			'summary_margin_top'  => (int) $this->input->post('summary_margin_top'),
			'summary_font_size'   => (int) $this->input->post('summary_font_size'),
			'date_font_size'      => (int) $this->input->post('date_font_size'),
			'date_margin_top'     => (int) $this->input->post('date_margin_top'),
			'time_font_size'      => (int) $this->input->post('time_font_size'),
			'time_margin_top'     => (int) $this->input->post('time_margin_top'),
			'location_font_size'  => (int) $this->input->post('location_font_size'),
			'location_margin_top' => (int) $this->input->post('location_margin_top'),
			'font_color'          => $this->input->post('font_color'),
			'glow_radius'         => (int) $this->input->post('glow_radius'),
			'glow_color'          => $this->input->post('glow_color'),
			'shadow_offset'       => (int) $this->input->post('shadow_offset'),
			'stroke_width'        => (int) $this->input->post('stroke_width'),
			'stroke_color'        => $this->input->post('stroke_color'),
		];

		$this->template_background_model->update($id, $data);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(['status' => 'ok']));
	}

	public function preview_template_background($id)
	{
		$bg = $this->template_background_model->getById($id);

		if ( ! $bg)
		{
			show_404();
		}

		$bg_file = FCPATH . 'imgs/template-backgrounds/' . $bg->filename;

		if ( ! file_exists($bg_file))
		{
			show_404();
		}

		$this->load->library('cal_image_renderer');

		$fields = [
			'text_offset', 'summary_margin_top', 'summary_font_size',
			'date_font_size', 'date_margin_top', 'time_font_size', 'time_margin_top',
			'location_font_size', 'location_margin_top', 'glow_radius', 'shadow_offset', 'stroke_width'
		];

		$layout = [];
		foreach ($fields as $f)
		{
			$layout[$f] = $this->input->get($f) !== null
				? (int) $this->input->get($f)
				: (int) $bg->$f;
		}

		$color_fields = ['font_color', 'glow_color', 'stroke_color'];
		foreach ($color_fields as $f)
		{
			$layout[$f] = $this->input->get($f) !== null
				? $this->input->get($f)
				: $bg->$f;
		}

		$im = $this->cal_image_renderer->render($bg_file, [
			'summary'  => 'Sample Venue Name',
			'date'     => 'Saturday - Mar 14',
			'time'     => '10:00 am - 1:00 pm',
			'location' => '123 Main St, Anytown, CA',
		], $layout, FCPATH . 'fonts/');

		if ( ! $im)
		{
			show_404();
		}

		header('Content-Type: image/png');
		imagepng($im);
		imagedestroy($im);
	}

	// --- Template Photos ---

	public function template_photos()
	{
		$this->page_data['page']->title = 'Template Photos';
		$this->page_data['page']->menu = 'template_photos';

		$this->page_data['photos'] = $this->template_photo_model->get();

		$this->load->view('admin/template_photos', $this->page_data);
	}

	public function upload_template_photo()
	{
		$config['upload_path']   = FCPATH . 'imgs/template-photos/';
		$config['allowed_types'] = 'png';
		$config['max_size']      = 5120;
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

			$photo_id = $this->template_photo_model->create([
				'filename'      => $upload['file_name'],
				'original_name' => $upload['orig_name'],
				'width'         => $upload['image_width'],
				'height'        => $upload['image_height'],
			]);

			$this->template_model->generate_for_photo($photo_id);

			$this->session->set_flashdata('alert', 'Photo uploaded successfully.');
			$this->session->set_flashdata('alert-type', 'success');
		}

		redirect('admin/template_photos', 'refresh');
	}

	public function toggle_template_photo($id)
	{
		$photo = $this->template_photo_model->getById($id);

		if ( ! $photo)
		{
			if ($this->input->is_ajax_request())
			{
				$this->output->set_content_type('application/json')
					->set_output(json_encode(['status' => 'error']));
				return;
			}
			redirect('admin/template_photos', 'refresh');
		}

		$new_state = $photo->is_active ? 0 : 1;
		$this->template_photo_model->update($id, ['is_active' => $new_state]);

		if ($this->input->is_ajax_request())
		{
			$this->output->set_content_type('application/json')
				->set_output(json_encode(['status' => 'ok', 'is_active' => $new_state]));
			return;
		}

		redirect('admin/template_photos', 'refresh');
	}

	public function delete_template_photo($id)
	{
		$photo = $this->template_photo_model->getById($id);

		if ($photo)
		{
			$file_path = FCPATH . 'imgs/template-photos/' . $photo->filename;
			if (file_exists($file_path))
			{
				unlink($file_path);
			}
			$this->template_photo_model->delete($id);

			$this->session->set_flashdata('alert', 'Photo deleted.');
			$this->session->set_flashdata('alert-type', 'success');
		}

		redirect('admin/template_photos', 'refresh');
	}

	public function template_photo_defaults($id)
	{
		$photo = $this->template_photo_model->getById($id);

		if ( ! $photo)
		{
			redirect('admin/template_photos', 'refresh');
		}

		$this->page_data['page']->title = 'Photo Position Defaults';
		$this->page_data['page']->menu = 'template_photos';
		$this->page_data['photo'] = $photo;

		// Get a background for preview (first active, or first available)
		$this->page_data['preview_bg'] = $this->db->where('is_active', 1)
			->limit(1)->get('template_backgrounds')->row();

		if ( ! $this->page_data['preview_bg'])
		{
			$this->page_data['preview_bg'] = $this->db->limit(1)->get('template_backgrounds')->row();
		}

		$this->load->view('admin/template_photo_defaults', $this->page_data);
	}

	public function save_template_photo_defaults()
	{
		$id = $this->input->post('photo_id');

		$data = [
			'photo_x'           => (int) $this->input->post('photo_x'),
			'photo_y'           => (int) $this->input->post('photo_y'),
			'photo_scale'       => (int) $this->input->post('photo_scale'),
			'photo_glow_radius' => (int) $this->input->post('photo_glow_radius'),
			'photo_glow_color'  => $this->input->post('photo_glow_color'),
		];

		$this->template_photo_model->update($id, $data);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(['status' => 'ok']));
	}

	public function preview_template_photo($id)
	{
		$photo = $this->template_photo_model->getById($id);

		if ( ! $photo)
		{
			show_404();
		}

		$photo_file = FCPATH . 'imgs/template-photos/' . $photo->filename;

		if ( ! file_exists($photo_file))
		{
			show_404();
		}

		// Use a background for preview
		$bg_id = $this->input->get('bg_id');
		$bg = $bg_id ? $this->template_background_model->getById($bg_id) : null;

		if ( ! $bg)
		{
			$bg = $this->db->where('is_active', 1)->limit(1)->get('template_backgrounds')->row();
		}
		if ( ! $bg)
		{
			$bg = $this->db->limit(1)->get('template_backgrounds')->row();
		}

		if ( ! $bg)
		{
			show_404();
		}

		$bg_file = FCPATH . 'imgs/template-backgrounds/' . $bg->filename;

		if ( ! file_exists($bg_file))
		{
			show_404();
		}

		$this->load->library('cal_image_renderer');

		$layout = [
			'photo_x'           => $this->input->get('photo_x') !== null ? (int) $this->input->get('photo_x') : (int) $photo->photo_x,
			'photo_y'           => $this->input->get('photo_y') !== null ? (int) $this->input->get('photo_y') : (int) $photo->photo_y,
			'photo_scale'       => $this->input->get('photo_scale') !== null ? (int) $this->input->get('photo_scale') : (int) $photo->photo_scale,
			'photo_glow_radius' => $this->input->get('photo_glow_radius') !== null ? (int) $this->input->get('photo_glow_radius') : (int) $photo->photo_glow_radius,
			'photo_glow_color'  => $this->input->get('photo_glow_color') !== null ? $this->input->get('photo_glow_color') : $photo->photo_glow_color,
			// Use background text defaults
			'text_offset'        => (int) $bg->text_offset,
			'summary_margin_top' => (int) $bg->summary_margin_top,
			'summary_font_size'  => (int) $bg->summary_font_size,
			'date_font_size'     => (int) $bg->date_font_size,
			'date_margin_top'    => (int) $bg->date_margin_top,
			'time_font_size'     => (int) $bg->time_font_size,
			'time_margin_top'    => (int) $bg->time_margin_top,
			'location_font_size' => (int) $bg->location_font_size,
			'location_margin_top'=> (int) $bg->location_margin_top,
			'font_color'         => $bg->font_color,
			'glow_radius'        => (int) $bg->glow_radius,
			'glow_color'         => $bg->glow_color,
			'shadow_offset'      => (int) $bg->shadow_offset,
			'stroke_width'       => (int) $bg->stroke_width,
			'stroke_color'       => $bg->stroke_color,
		];

		$im = $this->cal_image_renderer->render_template($bg_file, $photo_file, [
			'summary'  => 'Sample Venue Name',
			'date'     => 'Saturday - Mar 14',
			'time'     => '10:00 am - 1:00 pm',
			'location' => '123 Main St, Anytown, CA',
		], $layout, FCPATH . 'fonts/');

		if ( ! $im)
		{
			show_404();
		}

		header('Content-Type: image/png');
		imagepng($im);
		imagedestroy($im);
	}

	// --- Templates ---

	public function templates()
	{
		$this->page_data['page']->title = 'Share Templates';
		$this->page_data['page']->menu = 'templates';

		$this->page_data['templates'] = $this->template_model->get_all_with_assets();

		$this->load->view('admin/templates', $this->page_data);
	}

	public function template_editor($id)
	{
		$template = $this->template_model->get_with_assets($id);

		if ( ! $template)
		{
			redirect('admin/templates', 'refresh');
		}

		$this->page_data['page']->title = 'Template Editor';
		$this->page_data['page']->menu = 'templates';

		$this->page_data['template'] = $template;

		$this->load->view('admin/template_editor', $this->page_data);
	}

	public function save_template_layout()
	{
		$id = $this->input->post('template_id');

		$layout_data = [
			'photo_x'             => (int) $this->input->post('photo_x'),
			'photo_y'             => (int) $this->input->post('photo_y'),
			'photo_scale'         => (int) $this->input->post('photo_scale'),
			'photo_glow_radius'   => (int) $this->input->post('photo_glow_radius'),
			'photo_glow_color'    => $this->input->post('photo_glow_color'),
			'text_offset'         => (int) $this->input->post('text_offset'),
			'summary_margin_top'  => (int) $this->input->post('summary_margin_top'),
			'summary_font_size'   => (int) $this->input->post('summary_font_size'),
			'date_font_size'      => (int) $this->input->post('date_font_size'),
			'date_margin_top'     => (int) $this->input->post('date_margin_top'),
			'time_font_size'      => (int) $this->input->post('time_font_size'),
			'time_margin_top'     => (int) $this->input->post('time_margin_top'),
			'location_font_size'  => (int) $this->input->post('location_font_size'),
			'location_margin_top' => (int) $this->input->post('location_margin_top'),
			'font_color'          => $this->input->post('font_color'),
			'glow_radius'         => (int) $this->input->post('glow_radius'),
			'glow_color'          => $this->input->post('glow_color'),
			'shadow_offset'       => (int) $this->input->post('shadow_offset'),
			'stroke_width'        => (int) $this->input->post('stroke_width'),
			'stroke_color'        => $this->input->post('stroke_color'),
		];

		$this->template_model->save_layout($id, $layout_data);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(['status' => 'ok']));
	}

	public function toggle_template($id)
	{
		$template = $this->template_model->getById($id);

		if ( ! $template)
		{
			if ($this->input->is_ajax_request())
			{
				$this->output->set_content_type('application/json')
					->set_output(json_encode(['status' => 'error']));
				return;
			}
			redirect('admin/templates', 'refresh');
		}

		$new_state = $template->is_active ? 0 : 1;
		$this->template_model->update($id, ['is_active' => $new_state]);

		if ($this->input->is_ajax_request())
		{
			$this->output->set_content_type('application/json')
				->set_output(json_encode(['status' => 'ok', 'is_active' => $new_state]));
			return;
		}

		redirect('admin/templates', 'refresh');
	}

	public function preview_template($id)
	{
		$template = $this->template_model->get_with_assets($id);

		if ( ! $template)
		{
			show_404();
		}

		$bg_file = FCPATH . 'imgs/template-backgrounds/' . $template->bg_filename;
		$photo_file = FCPATH . 'imgs/template-photos/' . $template->photo_filename;

		if ( ! file_exists($bg_file) || ! file_exists($photo_file))
		{
			show_404();
		}

		$this->load->library('cal_image_renderer');

		// Build layout from DB, with query string overrides for live preview
		$fields = [
			'photo_x', 'photo_y', 'photo_scale', 'photo_glow_radius',
			'text_offset', 'summary_margin_top', 'summary_font_size',
			'date_font_size', 'date_margin_top', 'time_font_size', 'time_margin_top',
			'location_font_size', 'location_margin_top',
			'glow_radius', 'shadow_offset', 'stroke_width'
		];

		$layout = [];
		foreach ($fields as $f)
		{
			$layout[$f] = $this->input->get($f) !== null
				? (int) $this->input->get($f)
				: (int) $template->$f;
		}

		$color_fields = ['photo_glow_color', 'font_color', 'glow_color', 'stroke_color'];
		foreach ($color_fields as $f)
		{
			$layout[$f] = $this->input->get($f) !== null
				? $this->input->get($f)
				: $template->$f;
		}

		$im = $this->cal_image_renderer->render_template($bg_file, $photo_file, [
			'summary'  => 'Sample Venue Name',
			'date'     => 'Saturday - Mar 14',
			'time'     => '10:00 am - 1:00 pm',
			'location' => '123 Main St, Anytown, CA',
		], $layout, FCPATH . 'fonts/');

		if ( ! $im)
		{
			show_404();
		}

		header('Content-Type: image/png');
		imagepng($im);
		imagedestroy($im);
	}

	// --- Test Email ---

	public function test_email()
	{
		$this->page_data['page']->title = 'Test Email';
		$this->page_data['page']->menu = 'test_email';

		$this->load->library('ses_email');
		$this->page_data['ses_available'] = $this->ses_email->is_available();

		$this->load->view('admin/test_email', $this->page_data);
	}

	public function send_test_email()
	{
		$to = $this->input->post('to_email');
		if (!$to || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
			$this->session->set_flashdata('alert', 'Please enter a valid email address.');
			$this->session->set_flashdata('alert-type', 'danger');
			redirect('admin/test_email', 'refresh');
		}

		$this->load->library('ses_email');

		if (!$this->ses_email->is_available()) {
			$this->session->set_flashdata('alert', 'SES is not configured — check AWS credentials in .env');
			$this->session->set_flashdata('alert-type', 'danger');
			redirect('admin/test_email', 'refresh');
		}

		$domain = 'glennbennett.com';
		$from = 'gbennett@tsgdev.com';
		$timestamp = date('M j, Y g:i:s A');

		$html = '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">'
			. '<h2 style="color: #333;">Test Email from glennbennett.com</h2>'
			. '<p>This is a test email sent via <strong>Amazon SES</strong> from the admin panel.</p>'
			. '<table style="border-collapse: collapse; margin: 20px 0;">'
			. '<tr><td style="padding: 6px 12px; border: 1px solid #ddd; font-weight: bold;">From</td>'
			. '<td style="padding: 6px 12px; border: 1px solid #ddd;">' . htmlspecialchars($from) . '</td></tr>'
			. '<tr><td style="padding: 6px 12px; border: 1px solid #ddd; font-weight: bold;">To</td>'
			. '<td style="padding: 6px 12px; border: 1px solid #ddd;">' . htmlspecialchars($to) . '</td></tr>'
			. '<tr><td style="padding: 6px 12px; border: 1px solid #ddd; font-weight: bold;">Domain</td>'
			. '<td style="padding: 6px 12px; border: 1px solid #ddd;">' . $domain . '</td></tr>'
			. '<tr><td style="padding: 6px 12px; border: 1px solid #ddd; font-weight: bold;">Sent at</td>'
			. '<td style="padding: 6px 12px; border: 1px solid #ddd;">' . $timestamp . '</td></tr>'
			. '</table>'
			. '<p style="color: #666; font-size: 13px;">Check the email headers for SPF, DKIM, and DMARC results.</p>'
			. '</div>';

		$result = $this->ses_email
			->from($from, 'Glenn Bennett Website')
			->to($to)
			->subject('Test Email — ' . $domain . ' — ' . $timestamp)
			->message($html)
			->send();

		if ($result) {
			$debug = $this->ses_email->print_debugger();
			$this->session->set_flashdata('alert', 'Test email sent to <strong>' . htmlspecialchars($to) . '</strong>. ' . htmlspecialchars($debug));
			$this->session->set_flashdata('alert-type', 'success');
		} else {
			$debug = $this->ses_email->print_debugger();
			$this->session->set_flashdata('alert', 'Failed to send: ' . htmlspecialchars($debug));
			$this->session->set_flashdata('alert-type', 'danger');
		}

		redirect('admin/test_email', 'refresh');
	}
}
