<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/Admin_Controller.php';

class Admin extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cal_image_model');
		$this->load->model('venue_model');
		$this->load->model('venue_type_model');
		$this->load->model('venue_detail_model');
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

		$this->page_data['venues'] = $this->db
			->select('venues.*, venue_types.name as venue_type_name')
			->join('venue_types', 'venue_types.id = venues.venue_type_id', 'left')
			->order_by('venues.name', 'ASC')
			->get('venues')
			->result();

		$this->load->view('admin/venues', $this->page_data);
	}

	public function venue_edit($id = null)
	{
		$this->page_data['page']->title = $id ? 'Edit Venue' : 'Add Venue';
		$this->page_data['page']->menu = 'venues';

		$this->page_data['venue'] = $id ? $this->venue_model->getById($id) : null;
		$this->page_data['venue_types'] = $this->venue_type_model->get_active();
		$this->page_data['templates'] = $this->template_model->get_all_with_assets();
		$this->page_data['venue_details'] = $id ? $this->venue_detail_model->get_by_venue($id) : null;
		$this->page_data['venue_template_ids'] = $id ? $this->venue_model->get_venue_template_ids($id) : [];

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
			'venue_type_id' => $this->input->post('venue_type_id') ?: null,
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

		// Sync venue-specific templates
		$template_ids = $this->input->post('template_ids') ?: [];
		$this->venue_model->sync_templates($id, $template_ids);

		// Save venue details
		$details = [
			'drive_time_mins'      => $this->input->post('drive_time_mins') ?: null,
			'setup_time_mins'      => $this->input->post('setup_time_mins') ?: null,
			'default_start_time'   => $this->input->post('default_start_time') ?: null,
			'default_length_mins'  => $this->input->post('default_length_mins') ?: null,
			'special_requirements' => $this->input->post('special_requirements'),
			'address'              => $this->input->post('address'),
			'city'                 => $this->input->post('city'),
			'state'                => $this->input->post('state'),
		];
		$this->venue_detail_model->save_for_venue($id, $details);

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

	// --- Venue Types ---

	public function venue_types()
	{
		$this->page_data['page']->title = 'Venue Types';
		$this->page_data['page']->menu = 'venue_types';

		$types = $this->db
			->order_by("CASE WHEN slug = 'general' THEN 0 ELSE 1 END", '', FALSE)
			->order_by('name', 'ASC')
			->get('venue_types')
			->result();

		foreach ($types as &$vt)
		{
			$vt->template_count = $this->venue_type_model->get_template_count($vt->id);
		}

		$this->page_data['venue_types'] = $types;

		$this->load->view('admin/venue_types', $this->page_data);
	}

	public function venue_type_edit($id = null)
	{
		$this->page_data['page']->title = $id ? 'Edit Venue Type' : 'Add Venue Type';
		$this->page_data['page']->menu = 'venue_types';

		$this->page_data['venue_type'] = $id ? $this->venue_type_model->getById($id) : null;
		$this->page_data['templates'] = $this->template_model->get_all_with_assets();
		$this->page_data['assigned_template_ids'] = $id ? $this->venue_type_model->get_template_ids($id) : [];

		$this->load->view('admin/venue_type_edit', $this->page_data);
	}

	public function venue_type_save()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', 'Name', 'trim|required');

		if ($this->form_validation->run() == FALSE)
		{
			$id = $this->input->post('id');
			$this->venue_type_edit($id);
			return;
		}

		$name = $this->input->post('name');

		$data = [
			'name'       => $name,
			'slug'       => url_title($name, '-', TRUE),
			'sort_order' => (int) $this->input->post('sort_order'),
			'is_active'  => $this->input->post('is_active') ? 1 : 0,
		];

		$id = $this->input->post('id');

		if ($id)
		{
			$this->venue_type_model->update($id, $data);
		}
		else
		{
			$id = $this->venue_type_model->create($data);
		}

		// Sync templates
		$template_ids = $this->input->post('template_ids') ?: [];
		$this->venue_type_model->sync_templates($id, $template_ids);

		$this->session->set_flashdata('alert', 'Venue type saved successfully.');
		$this->session->set_flashdata('alert-type', 'success');
		redirect('admin/venue_types', 'refresh');
	}

	public function venue_type_delete($id)
	{
		$this->venue_type_model->delete($id);

		$this->session->set_flashdata('alert', 'Venue type deleted.');
		$this->session->set_flashdata('alert-type', 'success');
		redirect('admin/venue_types', 'refresh');
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
		$config['max_size']      = 20480;
		$config['encrypt_name']  = TRUE;

		$this->load->library('upload', $config);

		$is_ajax = $this->input->is_ajax_request();

		if ( ! $this->upload->do_upload('image_file'))
		{
			if ($is_ajax)
			{
				$this->output->set_content_type('application/json')
					->set_output(json_encode(['status' => 'error', 'message' => $this->upload->display_errors('', '')]));
				return;
			}
			$this->session->set_flashdata('alert', $this->upload->display_errors('', ''));
			$this->session->set_flashdata('alert-type', 'danger');
		}
		else
		{
			$upload = $this->upload->data();
			$file_path = $upload['full_path'];
			$orig_width = $upload['image_width'];
			$orig_height = $upload['image_height'];

			// Resize/crop to exactly 1200x630
			$resize_info = $this->_resize_background($file_path, 1200, 630);
			$width = $resize_info ? $resize_info['width'] : $orig_width;
			$height = $resize_info ? $resize_info['height'] : $orig_height;
			$resize_action = $resize_info ? $resize_info['action'] : 'none';

			$bg_id = $this->template_background_model->create([
				'filename'      => $upload['file_name'],
				'original_name' => 'bg-temp',
				'width'         => $width,
				'height'        => $height,
			]);

			// Set default name using the ID
			$bg_name = 'bg-' . $bg_id;
			$this->template_background_model->update($bg_id, ['original_name' => $bg_name]);

			if ($is_ajax)
			{
				$this->output->set_content_type('application/json')
					->set_output(json_encode([
						'status'          => 'ok',
						'id'              => $bg_id,
						'filename'        => $upload['file_name'],
						'original_name'   => $bg_name,
						'width'           => $width,
						'height'          => $height,
						'original_width'  => $orig_width,
						'original_height' => $orig_height,
						'resize_action'   => $resize_action,
					]));
				return;
			}

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
			$this->template_model->orphan_by_background($id);

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

		$bg = $this->template_background_model->getById($id);
		$first_time = $bg && ! $bg->has_defaults;

		$data['has_defaults'] = 1;
		$this->template_background_model->update($id, $data);

		if ($first_time)
		{
			// First time setting defaults — now generate templates with correct values
			$this->template_model->generate_for_background($id);
		}
		else
		{
			$this->template_model->unready_by_background($id);
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(['status' => 'ok', 'first_defaults' => $first_time]));
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

	// --- Artist Photos ---

	public function template_photos()
	{
		$this->page_data['page']->title = 'Artist Photos';
		$this->page_data['page']->menu = 'template_photos';

		$this->page_data['photos'] = $this->template_photo_model->get();

		$this->load->view('admin/template_photos', $this->page_data);
	}

	public function upload_template_photo()
	{
		$config['upload_path']   = FCPATH . 'imgs/template-photos/';
		$config['allowed_types'] = 'png';
		$config['max_size']      = 20480;
		$config['encrypt_name']  = TRUE;

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('image_file'))
		{
			$this->output->set_content_type('application/json')
				->set_output(json_encode([
					'status'  => 'error',
					'message' => $this->upload->display_errors('', ''),
				]));
			return;
		}

		$upload = $this->upload->data();
		$file_path = $upload['full_path'];

		// Auto-trim transparent pixels
		$trimmed = $this->_trim_transparent($file_path);
		$width  = $trimmed ? $trimmed['width'] : $upload['image_width'];
		$height = $trimmed ? $trimmed['height'] : $upload['image_height'];

		$photo_id = $this->template_photo_model->create([
			'filename'      => $upload['file_name'],
			'original_name' => 'photo-temp',
			'width'         => $width,
			'height'        => $height,
		]);

		// Set default name using the ID
		$photo_name = 'photo-' . $photo_id;
		$this->template_photo_model->update($photo_id, ['original_name' => $photo_name]);

		$this->output->set_content_type('application/json')
			->set_output(json_encode([
				'status'        => 'ok',
				'id'            => $photo_id,
				'filename'      => $upload['file_name'],
				'original_name' => $photo_name,
				'width'         => $width,
				'height'        => $height,
			]));
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

	public function rename_template_photo()
	{
		$id = $this->input->post('id');
		$name = trim($this->input->post('name'));

		if ( ! $id || $name === '')
		{
			$this->output->set_content_type('application/json')
				->set_output(json_encode(['status' => 'error']));
			return;
		}

		$this->template_photo_model->update($id, ['original_name' => $name]);

		$this->output->set_content_type('application/json')
			->set_output(json_encode(['status' => 'ok', 'name' => $name]));
	}

	public function rename_template_background()
	{
		$id = $this->input->post('id');
		$name = trim($this->input->post('name'));

		if ( ! $id || $name === '')
		{
			$this->output->set_content_type('application/json')
				->set_output(json_encode(['status' => 'error']));
			return;
		}

		$this->template_background_model->update($id, ['original_name' => $name]);

		$this->output->set_content_type('application/json')
			->set_output(json_encode(['status' => 'ok', 'name' => $name]));
	}

	public function rename_template()
	{
		$id = $this->input->post('id');
		$name = trim($this->input->post('name'));

		if ( ! $id || $name === '')
		{
			$this->output->set_content_type('application/json')
				->set_output(json_encode(['status' => 'error']));
			return;
		}

		$this->template_model->update($id, ['name' => $name]);

		$this->output->set_content_type('application/json')
			->set_output(json_encode(['status' => 'ok', 'name' => $name]));
	}

	public function get_bg_text_preset($bg_id)
	{
		$bg = $this->template_background_model->getById($bg_id);

		if ( ! $bg)
		{
			$this->output->set_content_type('application/json')
				->set_output(json_encode(['status' => 'error']));
			return;
		}

		$fields = ['text_offset', 'summary_margin_top', 'summary_font_size',
			'date_font_size', 'date_margin_top', 'time_font_size', 'time_margin_top',
			'location_font_size', 'location_margin_top', 'font_color',
			'glow_radius', 'glow_color', 'shadow_offset', 'stroke_width', 'stroke_color'];

		$data = ['status' => 'ok'];
		foreach ($fields as $f)
		{
			$data[$f] = $bg->$f;
		}

		$this->output->set_content_type('application/json')
			->set_output(json_encode($data));
	}

	public function save_bg_text_preset()
	{
		$bg_id = $this->input->post('background_id');

		if ( ! $bg_id)
		{
			$this->output->set_content_type('application/json')
				->set_output(json_encode(['status' => 'error']));
			return;
		}

		$fields = ['text_offset', 'summary_margin_top', 'summary_font_size',
			'date_font_size', 'date_margin_top', 'time_font_size', 'time_margin_top',
			'location_font_size', 'location_margin_top', 'font_color',
			'glow_radius', 'glow_color', 'shadow_offset', 'stroke_width', 'stroke_color'];

		$data = [];
		foreach ($fields as $f)
		{
			if ($this->input->post($f) !== null)
			{
				$data[$f] = $this->input->post($f);
			}
		}

		$this->template_background_model->update($bg_id, $data);

		$this->output->set_content_type('application/json')
			->set_output(json_encode(['status' => 'ok']));
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
			$this->template_model->orphan_by_photo($id);

			$this->session->set_flashdata('alert', 'Photo deleted.');
			$this->session->set_flashdata('alert-type', 'success');
		}

		redirect('admin/template_photos', 'refresh');
	}

	public function artist_photo_editor($id)
	{
		$photo = $this->template_photo_model->getById($id);

		if ( ! $photo)
		{
			redirect('admin/template_photos', 'refresh');
		}

		$this->page_data['page']->title = 'Edit Artist Photo';
		$this->page_data['page']->menu = 'template_photos';
		$this->page_data['photo'] = $photo;

		$this->load->view('admin/artist_photo_editor', $this->page_data);
	}

	public function save_artist_photo($id)
	{
		$photo = $this->template_photo_model->getById($id);

		if ( ! $photo)
		{
			$this->output->set_content_type('application/json')
				->set_output(json_encode(['status' => 'error', 'message' => 'Photo not found']));
			return;
		}

		$image_data = $this->input->post('image_data');

		if ( ! $image_data)
		{
			$this->output->set_content_type('application/json')
				->set_output(json_encode(['status' => 'error', 'message' => 'No image data']));
			return;
		}

		// Decode base64 PNG
		$image_data = preg_replace('#^data:image/png;base64,#', '', $image_data);
		$decoded = base64_decode($image_data);

		if ( ! $decoded)
		{
			$this->output->set_content_type('application/json')
				->set_output(json_encode(['status' => 'error', 'message' => 'Invalid image data']));
			return;
		}

		$file_path = FCPATH . 'imgs/template-photos/' . $photo->filename;
		file_put_contents($file_path, $decoded);

		// Auto-trim
		$trimmed = $this->_trim_transparent($file_path);

		// Get new dimensions
		$info = getimagesize($file_path);
		$width = $info[0];
		$height = $info[1];

		$this->template_photo_model->update($id, [
			'width'  => $width,
			'height' => $height,
		]);

		// Mark templates using this photo as not ready
		$this->template_model->unready_by_photo($id);

		// Bust cached previews for templates using this photo
		$templates = $this->db->where('photo_id', $id)->get('templates')->result();
		foreach ($templates as $tpl)
		{
			$cache_file = FCPATH . 'imgs/template-cache/preview-' . $tpl->id . '.png';
			if (file_exists($cache_file))
			{
				unlink($cache_file);
			}
		}

		$this->output->set_content_type('application/json')
			->set_output(json_encode([
				'status' => 'ok',
				'width'  => $width,
				'height' => $height,
			]));
	}

	public function template_photo_defaults($id)
	{
		$photo = $this->template_photo_model->getById($id);

		if ( ! $photo)
		{
			redirect('admin/template_photos', 'refresh');
		}

		$this->page_data['page']->title = 'Artist Photo Defaults';
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

		$int_fields = [
			'photo_x', 'photo_y', 'photo_scale', 'photo_glow_radius',
			'brightness', 'contrast', 'saturation', 'sharpen', 'blur', 'opacity',
			'sepia', 'grayscale', 'hue_rotate', 'tint_amount',
			'text_offset', 'summary_margin_top', 'summary_font_size',
			'date_font_size', 'date_margin_top', 'time_font_size', 'time_margin_top',
			'location_font_size', 'location_margin_top',
			'glow_radius', 'shadow_offset', 'stroke_width',
		];
		$str_fields = [
			'photo_glow_color', 'tint_color', 'font_color',
			'glow_color', 'stroke_color',
		];

		$data = [];
		foreach ($int_fields as $f)
		{
			if ($this->input->post($f) !== NULL)
			{
				$data[$f] = (int) $this->input->post($f);
			}
		}
		foreach ($str_fields as $f)
		{
			if ($this->input->post($f) !== NULL)
			{
				$data[$f] = $this->input->post($f);
			}
		}

		if ( ! empty($data))
		{
			$photo = $this->template_photo_model->getById($id);
			$first_time = $photo && ! $photo->has_defaults;

			$data['has_defaults'] = 1;
			$this->template_photo_model->update($id, $data);

			if ($first_time)
			{
				// First time setting defaults — now generate templates with correct values
				$this->template_model->generate_for_photo($id);
			}
			else
			{
				$this->template_model->unready_by_photo($id);
			}
		}

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

		// Use a background for preview — prefer 1200x627 (most common size) for consistent positioning
		$bg_id = $this->input->get('bg_id');
		$bg = $bg_id ? $this->template_background_model->getById($bg_id) : null;

		if ( ! $bg)
		{
			$bg = $this->db->where('is_active', 1)->where('width', 1200)->limit(1)->get('template_backgrounds')->row();
		}
		if ( ! $bg)
		{
			$bg = $this->db->where('is_active', 1)->order_by('width', 'DESC')->limit(1)->get('template_backgrounds')->row();
		}
		if ( ! $bg)
		{
			$bg = $this->db->order_by('width', 'DESC')->limit(1)->get('template_backgrounds')->row();
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
			'brightness'        => $this->input->get('brightness') !== null ? (int) $this->input->get('brightness') : (int) $photo->brightness,
			'contrast'          => $this->input->get('contrast') !== null ? (int) $this->input->get('contrast') : (int) $photo->contrast,
			'saturation'        => $this->input->get('saturation') !== null ? (int) $this->input->get('saturation') : (int) $photo->saturation,
			'sharpen'           => $this->input->get('sharpen') !== null ? (int) $this->input->get('sharpen') : (int) $photo->sharpen,
			'blur'              => $this->input->get('blur') !== null ? (int) $this->input->get('blur') : (int) $photo->blur,
			'opacity'           => $this->input->get('opacity') !== null ? (int) $this->input->get('opacity') : (int) $photo->opacity,
			'sepia'             => $this->input->get('sepia') !== null ? (int) $this->input->get('sepia') : (int) $photo->sepia,
			'grayscale'         => $this->input->get('grayscale') !== null ? (int) $this->input->get('grayscale') : (int) $photo->grayscale,
			'hue_rotate'        => $this->input->get('hue_rotate') !== null ? (int) $this->input->get('hue_rotate') : (int) $photo->hue_rotate,
			'tint_color'        => $this->input->get('tint_color') !== null ? $this->input->get('tint_color') : $photo->tint_color,
			'tint_amount'       => $this->input->get('tint_amount') !== null ? (int) $this->input->get('tint_amount') : (int) $photo->tint_amount,
			// Use photo text defaults
			'text_offset'        => $this->input->get('text_offset') !== null ? (int) $this->input->get('text_offset') : (int) $photo->text_offset,
			'summary_margin_top' => $this->input->get('summary_margin_top') !== null ? (int) $this->input->get('summary_margin_top') : (int) $photo->summary_margin_top,
			'summary_font_size'  => $this->input->get('summary_font_size') !== null ? (int) $this->input->get('summary_font_size') : (int) $photo->summary_font_size,
			'date_font_size'     => $this->input->get('date_font_size') !== null ? (int) $this->input->get('date_font_size') : (int) $photo->date_font_size,
			'date_margin_top'    => $this->input->get('date_margin_top') !== null ? (int) $this->input->get('date_margin_top') : (int) $photo->date_margin_top,
			'time_font_size'     => $this->input->get('time_font_size') !== null ? (int) $this->input->get('time_font_size') : (int) $photo->time_font_size,
			'time_margin_top'    => $this->input->get('time_margin_top') !== null ? (int) $this->input->get('time_margin_top') : (int) $photo->time_margin_top,
			'location_font_size' => $this->input->get('location_font_size') !== null ? (int) $this->input->get('location_font_size') : (int) $photo->location_font_size,
			'location_margin_top'=> $this->input->get('location_margin_top') !== null ? (int) $this->input->get('location_margin_top') : (int) $photo->location_margin_top,
			'font_color'         => $this->input->get('font_color') !== null ? $this->input->get('font_color') : $photo->font_color,
			'glow_radius'        => $this->input->get('glow_radius') !== null ? (int) $this->input->get('glow_radius') : (int) $photo->glow_radius,
			'glow_color'         => $this->input->get('glow_color') !== null ? $this->input->get('glow_color') : $photo->glow_color,
			'shadow_offset'      => $this->input->get('shadow_offset') !== null ? (int) $this->input->get('shadow_offset') : (int) $photo->shadow_offset,
			'stroke_width'       => $this->input->get('stroke_width') !== null ? (int) $this->input->get('stroke_width') : (int) $photo->stroke_width,
			'stroke_color'       => $this->input->get('stroke_color') !== null ? $this->input->get('stroke_color') : $photo->stroke_color,
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

		$templates = $this->template_model->get_all_with_assets();

		// Attach venue type and venue assignment labels
		foreach ($templates as &$tpl)
		{
			$tpl->venue_types = $this->template_model->get_venue_types_for_template($tpl->id);
			$tpl->venues = $this->template_model->get_venues_for_template($tpl->id);
		}

		$this->page_data['templates'] = $templates;

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
		$this->page_data['photo_defaults'] = $this->template_photo_model->getById($template->photo_id);

		// Venue type and venue assignment data
		$this->page_data['venue_types'] = $this->venue_type_model->get_active();
		$this->page_data['venues'] = $this->venue_model->getByWhere(['is_active' => 1], ['order' => ['name', 'ASC']]);
		$this->page_data['assigned_type_ids'] = $this->template_model->get_venue_type_ids($id);
		$this->page_data['assigned_venue_ids'] = $this->template_model->get_venue_ids($id);

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

		// Bust cached preview
		$cache_file = FCPATH . 'imgs/template-cache/preview-' . $id . '.png';
		if (file_exists($cache_file)) unlink($cache_file);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(['status' => 'ok']));
	}

	public function save_template_assignments()
	{
		$id = $this->input->post('template_id');

		$type_id = $this->input->post('venue_type_id');
		$venue_id = $this->input->post('venue_id');

		$this->template_model->sync_venue_types($id, $type_id ? [$type_id] : []);
		$this->template_model->sync_venues($id, $venue_id ? [$venue_id] : []);

		$this->session->set_flashdata('alert', 'Template assignment saved.');
		$this->session->set_flashdata('alert-type', 'success');
		redirect('admin/template_editor/' . $id, 'refresh');
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

	public function toggle_template_ready($id)
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

		$new_state = $template->is_ready ? 0 : 1;
		$this->template_model->update($id, ['is_ready' => $new_state]);

		if ($this->input->is_ajax_request())
		{
			$this->output->set_content_type('application/json')
				->set_output(json_encode(['status' => 'ok', 'is_ready' => $new_state]));
			return;
		}

		redirect('admin/templates', 'refresh');
	}

	public function delete_template($id)
	{
		$template = $this->template_model->getById($id);

		if ($template && $template->is_orphaned)
		{
			// Clean up junction table rows
			$this->template_model->sync_venue_types($id, []);
			$this->template_model->sync_venues($id, []);

			$this->template_model->delete($id);

			$cache_file = FCPATH . 'imgs/template-cache/preview-' . $id . '.png';
			if (file_exists($cache_file))
			{
				unlink($cache_file);
			}

			$this->session->set_flashdata('alert', 'Template deleted.');
			$this->session->set_flashdata('alert-type', 'success');
		}

		redirect('admin/templates', 'refresh');
	}

	public function delete_orphaned_templates()
	{
		$count = $this->template_model->delete_orphaned();

		$this->session->set_flashdata('alert', $count . ' orphaned template(s) deleted.');
		$this->session->set_flashdata('alert-type', 'success');
		redirect('admin/templates', 'refresh');
	}

	public function preview_template($id)
	{
		$template = $this->template_model->get_with_assets($id);

		if ( ! $template)
		{
			show_404();
		}

		// Serve cached preview if no query string overrides (e.g. templates list)
		$has_overrides = ! empty($_SERVER['QUERY_STRING']);
		$cache_file = FCPATH . 'imgs/template-cache/preview-' . $id . '.png';

		if ( ! $has_overrides && file_exists($cache_file))
		{
			header('Content-Type: image/png');
			readfile($cache_file);
			return;
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

		// Cache when serving without overrides
		if ( ! $has_overrides)
		{
			imagepng($im, $cache_file);
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

	/**
	 * Resize a background image if it exceeds max dimensions.
	 * Returns ['width' => int, 'height' => int] or FALSE if no resize needed.
	 */
	private function _resize_background($file_path, $target_w = 1200, $target_h = 630)
	{
		$info = getimagesize($file_path);
		if ( ! $info) return FALSE;

		$w = $info[0];
		$h = $info[1];

		// Already exact size
		if ($w === $target_w && $h === $target_h)
		{
			return ['width' => $w, 'height' => $h, 'action' => 'none', 'original_width' => $w, 'original_height' => $h];
		}

		// Reject images that would need more than 10% upscale
		$scale_needed = max($target_w / $w, $target_h / $h);
		if ($scale_needed > 1.1)
		{
			return ['width' => $w, 'height' => $h, 'action' => 'too_small', 'original_width' => $w, 'original_height' => $h];
		}

		$type = $info[2];
		if ($type === IMAGETYPE_JPEG)
		{
			$src = imagecreatefromjpeg($file_path);
		}
		else
		{
			$src = imagecreatefrompng($file_path);
		}

		if ( ! $src) return FALSE;

		// Cover-crop: scale to fill target, then center-crop
		$scale = max($target_w / $w, $target_h / $h);
		$action = $scale > 1.0 ? 'upscaled' : 'downsized';

		// Scale then crop in one step via source offsets
		$src_w = (int) round($target_w / $scale);
		$src_h = (int) round($target_h / $scale);
		$src_x = (int) round(($w - $src_w) / 2);
		$src_y = (int) round(($h - $src_h) / 2);

		$dst = imagecreatetruecolor($target_w, $target_h);

		if ($type === IMAGETYPE_PNG)
		{
			imagesavealpha($dst, TRUE);
			imagealphablending($dst, FALSE);
			$transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
			imagefill($dst, 0, 0, $transparent);
		}

		imagecopyresampled($dst, $src, 0, 0, $src_x, $src_y, $target_w, $target_h, $src_w, $src_h);

		if ($type === IMAGETYPE_JPEG)
		{
			imagejpeg($dst, $file_path, 90);
		}
		else
		{
			imagepng($dst, $file_path);
		}

		imagedestroy($src);
		imagedestroy($dst);

		return [
			'width'           => $target_w,
			'height'          => $target_h,
			'action'          => $action,
			'original_width'  => $w,
			'original_height' => $h,
		];
	}

	/**
	 * Trim transparent/white pixels from a PNG and resize to max dimension.
	 * Overwrites the file in place and returns ['width' => int, 'height' => int]
	 * or FALSE if processing failed.
	 */
	private function _trim_transparent($file_path, $max_dim = 1024)
	{
		$src = imagecreatefrompng($file_path);

		if ( ! $src)
		{
			return FALSE;
		}

		$w = imagesx($src);
		$h = imagesy($src);

		// Find bounding box of content pixels (not transparent, not white/near-white)
		$top = $h;
		$bottom = 0;
		$left = $w;
		$right = 0;

		for ($y = 0; $y < $h; $y++)
		{
			for ($x = 0; $x < $w; $x++)
			{
				$rgba = imagecolorat($src, $x, $y);
				$r = ($rgba >> 16) & 0xFF;
				$g = ($rgba >> 8) & 0xFF;
				$b = $rgba & 0xFF;
				$alpha = ($rgba >> 24) & 0x7F;

				$is_content = ($alpha < 120) && !($r > 240 && $g > 240 && $b > 240);

				if ($is_content)
				{
					if ($y < $top) $top = $y;
					if ($y > $bottom) $bottom = $y;
					if ($x < $left) $left = $x;
					if ($x > $right) $right = $x;
				}
			}
		}

		// Fully transparent — nothing to do
		if ($top > $bottom || $left > $right)
		{
			imagedestroy($src);
			return FALSE;
		}

		$crop_w = $right - $left + 1;
		$crop_h = $bottom - $top + 1;
		$needs_trim = !($top === 0 && $left === 0 && $right === $w - 1 && $bottom === $h - 1);

		// Trim if needed
		if ($needs_trim)
		{
			$trimmed = imagecreatetruecolor($crop_w, $crop_h);
			imagesavealpha($trimmed, TRUE);
			imagealphablending($trimmed, FALSE);
			$transparent = imagecolorallocatealpha($trimmed, 0, 0, 0, 127);
			imagefill($trimmed, 0, 0, $transparent);
			imagecopy($trimmed, $src, 0, 0, $left, $top, $crop_w, $crop_h);
			imagedestroy($src);
			$src = $trimmed;
			$w = $crop_w;
			$h = $crop_h;
		}

		// Resize if either dimension exceeds max
		if ($w > $max_dim || $h > $max_dim)
		{
			$scale = min($max_dim / $w, $max_dim / $h);
			$new_w = (int) round($w * $scale);
			$new_h = (int) round($h * $scale);

			$resized = imagecreatetruecolor($new_w, $new_h);
			imagesavealpha($resized, TRUE);
			imagealphablending($resized, FALSE);
			$transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
			imagefill($resized, 0, 0, $transparent);
			imagecopyresampled($resized, $src, 0, 0, 0, 0, $new_w, $new_h, $w, $h);
			imagedestroy($src);
			$src = $resized;
			$w = $new_w;
			$h = $new_h;
		}

		imagepng($src, $file_path);
		imagedestroy($src);

		return ['width' => $w, 'height' => $h];
	}

	// ---------------------------------------------------------------
	// Share Link Cleanup
	// ---------------------------------------------------------------

	public function share_cleanup()
	{
		$this->page_data['page']->menu = 'share_cleanup';
		$this->page_data['page']->title = 'Share Link Cleanup';

		// Template readiness
		$ready = $this->template_model->get_active_with_assets();
		$this->page_data['templates_ready'] = ! empty($ready);
		$this->page_data['ready_template_count'] = count($ready);

		// Cal-Event-*.jpg files on disk
		$cal_event_files = glob(FCPATH . 'imgs/Cal-Event-*.jpg');
		// Also check for Cal-Event.jpg, Cal-Event-large.jpg, Cal-Event-small.jpg
		foreach (['Cal-Event.jpg', 'Cal-Event-large.jpg', 'Cal-Event-small.jpg'] as $f)
		{
			if (file_exists(FCPATH . 'imgs/' . $f))
			{
				$cal_event_files[] = FCPATH . 'imgs/' . $f;
			}
		}
		$this->page_data['cal_event_count'] = count($cal_event_files);

		// cal_images table
		$this->page_data['cal_images_count'] = $this->db->count_all_results('cal_images');

		// cal_image_layouts table
		$this->page_data['cal_layouts_count'] = $this->db->count_all_results('cal_image_layouts');

		// venue_images table
		$this->page_data['venue_images_count'] = $this->db->count_all_results('venue_images');

		// Legacy gcal scripts
		$gcal_legacy = [
			'cal_image.php', 'cal_image-small.php', 'fb_image_config.php',
			'center.php', 'img.php', 'social_page.php',
			'gcal-core-old.php', 'gcal-old.php', 'index-old.php',
			'index-direct-load.php', 'example.php', 'leslie.php',
			'gcal-bennett.php', 'ww_test.php', 'test.php',
		];
		$gcal_legacy_found = [];
		foreach ($gcal_legacy as $f)
		{
			if (file_exists(FCPATH . 'gcal/' . $f))
			{
				$gcal_legacy_found[] = $f;
			}
		}
		$this->page_data['gcal_legacy_count'] = count($gcal_legacy_found);
		$this->page_data['gcal_legacy_files'] = $gcal_legacy_found;

		// share_images table
		$this->load->model('share_image_model');
		$this->page_data['share_images_count'] = $this->db->count_all_results('share_images');
		$this->page_data['expired_share_count'] = $this->db
			->where('start_date <', time())
			->count_all_results('share_images');

		$this->load->view('admin/share_cleanup', $this->page_data);
	}

	public function run_share_cleanup()
	{
		if ($this->input->method() !== 'post')
		{
			redirect('admin/share_cleanup');
			return;
		}

		$deleted = [];

		// 1. Delete Cal-Event-*.jpg files
		$cal_files = glob(FCPATH . 'imgs/Cal-Event-*.jpg');
		foreach (['Cal-Event.jpg', 'Cal-Event-large.jpg', 'Cal-Event-small.jpg'] as $f)
		{
			$path = FCPATH . 'imgs/' . $f;
			if (file_exists($path)) $cal_files[] = $path;
		}
		foreach ($cal_files as $f)
		{
			@unlink($f);
		}
		if ( ! empty($cal_files))
		{
			$deleted[] = count($cal_files) . ' Cal-Event image(s)';
		}

		// 2. Truncate cal_images and cal_image_layouts
		$ci_count = $this->db->count_all_results('cal_images');
		$cl_count = $this->db->count_all_results('cal_image_layouts');
		if ($ci_count > 0)
		{
			$this->db->truncate('cal_image_layouts');
			$this->db->truncate('cal_images');
			$deleted[] = $ci_count . ' cal_images + ' . $cl_count . ' layout row(s)';
		}

		// 3. Truncate venue_images
		$vi_count = $this->db->count_all_results('venue_images');
		if ($vi_count > 0)
		{
			$this->db->truncate('venue_images');
			$deleted[] = $vi_count . ' venue_images row(s)';
		}

		// 4. Delete legacy gcal scripts
		$gcal_legacy = [
			'cal_image.php', 'cal_image-small.php', 'fb_image_config.php',
			'center.php', 'img.php', 'social_page.php',
			'gcal-core-old.php', 'gcal-old.php', 'index-old.php',
			'index-direct-load.php', 'example.php', 'leslie.php',
			'gcal-bennett.php', 'ww_test.php', 'test.php',
		];
		$gcal_deleted = 0;
		foreach ($gcal_legacy as $f)
		{
			$path = FCPATH . 'gcal/' . $f;
			if (file_exists($path))
			{
				@unlink($path);
				$gcal_deleted++;
			}
		}
		// Also clean up junk files
		foreach (['fbid.png', 'temp.png', 'php-error.log'] as $f)
		{
			$path = FCPATH . 'gcal/' . $f;
			if (file_exists($path))
			{
				@unlink($path);
				$gcal_deleted++;
			}
		}
		if ($gcal_deleted > 0)
		{
			$deleted[] = $gcal_deleted . ' legacy gcal file(s)';
		}

		// Also delete 640-480.jpg if it exists
		if (file_exists(FCPATH . 'imgs/640-480.jpg'))
		{
			@unlink(FCPATH . 'imgs/640-480.jpg');
			$deleted[] = '640-480.jpg';
		}

		if (empty($deleted))
		{
			$this->session->set_flashdata('cleanup_success', 'Nothing to clean up — already clean.');
		}
		else
		{
			$this->session->set_flashdata('cleanup_success', 'Cleanup complete: ' . implode(', ', $deleted));
		}

		redirect('admin/share_cleanup');
	}

	public function prune_expired_shares()
	{
		if ($this->input->method() !== 'post')
		{
			redirect('admin/share_cleanup');
			return;
		}

		$count = $this->db->where('start_date <', time())->count_all_results('share_images');
		$this->db->where('start_date <', time())->delete('share_images');

		$this->session->set_flashdata('cleanup_success', 'Pruned ' . $count . ' expired share link(s).');
		redirect('admin/share_cleanup');
	}
}
