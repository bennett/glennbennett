<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/Admin_Controller.php';

class Admin_promo extends Admin_Controller {

	private $perform_calendar = [
		[
			'url'  => 'https://calendar.google.com/calendar/ical/c_8oqt9e7bms1sefskr0fl01r7tg%40group.calendar.google.com/public/basic.ics',
			'name' => 'perform',
		]
	];

	public function __construct()
	{
		parent::__construct();
		$this->load->model('promo_image_model');
	}

	/**
	 * Main promo builder page
	 */
	public function index()
	{
		$this->page_data['page']->title = 'AI Promo Builder';
		$this->page_data['page']->menu = 'promo_builder';

		$this->page_data['artist_images'] = $this->promo_image_model->get_by_category('artist');
		$this->page_data['venue_images'] = $this->promo_image_model->get_by_category('venue');
		$this->page_data['generic_images'] = $this->promo_image_model->get_by_category('generic');

		$this->load->view('admin/promo_builder', $this->page_data);
	}

	/**
	 * Image library management page
	 */
	public function images()
	{
		$this->page_data['page']->title = 'Promo Image Library';
		$this->page_data['page']->menu = 'promo_images';

		$this->page_data['images'] = $this->promo_image_model->get();

		$this->load->view('admin/promo_images', $this->page_data);
	}

	/**
	 * AJAX upload endpoint
	 */
	public function upload_image()
	{
		$config['upload_path']   = FCPATH . 'imgs/promo/';
		$config['allowed_types'] = 'jpg|jpeg|png|webp';
		$config['max_size']      = 10240; // 10MB
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

		$category = $this->input->post('category') ?: 'generic';
		if ( ! in_array($category, ['artist', 'venue', 'generic']))
		{
			$category = 'generic';
		}

		$image_data = [
			'filename'      => $upload['file_name'],
			'original_name' => $upload['orig_name'],
			'category'      => $category,
			'label'         => pathinfo($upload['orig_name'], PATHINFO_FILENAME),
			'width'         => $upload['image_width'],
			'height'        => $upload['image_height'],
		];

		$id = $this->promo_image_model->create($image_data);

		$this->output->set_content_type('application/json')
			->set_output(json_encode([
				'status'        => 'ok',
				'id'            => $id,
				'filename'      => $upload['file_name'],
				'original_name' => $upload['orig_name'],
				'category'      => $category,
				'label'         => $image_data['label'],
				'width'         => $upload['image_width'],
				'height'        => $upload['image_height'],
			]));
	}

	/**
	 * Delete image from library and disk
	 */
	public function delete_image($id)
	{
		$image = $this->promo_image_model->getById($id);

		if ($image)
		{
			$filepath = FCPATH . 'imgs/promo/' . $image->filename;
			if (file_exists($filepath))
			{
				unlink($filepath);
			}
			$this->promo_image_model->delete($id);

			if ($this->input->is_ajax_request())
			{
				$this->output->set_content_type('application/json')
					->set_output(json_encode(['status' => 'ok']));
				return;
			}

			$this->session->set_flashdata('alert', 'Image deleted.');
			$this->session->set_flashdata('alert-type', 'success');
		}

		redirect('admin/promo/images', 'refresh');
	}

	/**
	 * Update label/category via AJAX
	 */
	public function update_image($id)
	{
		$image = $this->promo_image_model->getById($id);

		if ( ! $image)
		{
			$this->output->set_content_type('application/json')
				->set_output(json_encode(['status' => 'error']));
			return;
		}

		$data = [];

		if ($this->input->post('label') !== NULL)
		{
			$data['label'] = trim($this->input->post('label'));
		}

		if ($this->input->post('category') !== NULL)
		{
			$cat = $this->input->post('category');
			if (in_array($cat, ['artist', 'venue', 'generic']))
			{
				$data['category'] = $cat;
			}
		}

		if ( ! empty($data))
		{
			$this->promo_image_model->update($id, $data);
		}

		$updated = $this->promo_image_model->getById($id);

		$this->output->set_content_type('application/json')
			->set_output(json_encode([
				'status'   => 'ok',
				'label'    => $updated->label,
				'category' => $updated->category,
			]));
	}

	/**
	 * AJAX: fetch upcoming events from Google Calendar
	 */
	public function fetch_events()
	{
		require_once FCPATH . 'gcal/libs/gcal_reader.php';

		$gcal_reader = new gcal_reader($this->perform_calendar);

		$start_date = new DateTime('now', new DateTimeZone('America/Los_Angeles'));
		$end_date = new DateTime('+90 days', new DateTimeZone('America/Los_Angeles'));

		$events = $gcal_reader->get_events(
			$start_date->getTimestamp(),
			$end_date->getTimestamp()
		);

		$result = [];
		foreach ($events as $event)
		{
			// Strip HTML from summary (gcal_reader adds <br /> tags)
			$summary = isset($event['summary']) ? strip_tags($event['summary']) : '';

			$result[] = [
				'summary'     => $summary,
				'description' => isset($event['description']) ? strip_tags($event['description']) : '',
				'date'        => isset($event['start_date']) ? date('Y-m-d', $event['start_date']) : '',
				'date_display' => isset($event['display_date']) ? $event['display_date'] : '',
				'time'        => isset($event['display_date_time']) ? $event['display_date_time'] : '',
				'location'    => isset($event['location']) ? strip_tags($event['location']) : '',
			];
		}

		$this->output->set_content_type('application/json')
			->set_output(json_encode($result));
	}

	/**
	 * POST: package selected images + prompt.txt into zip download
	 */
	public function generate_zip()
	{
		if ($this->input->method() !== 'post')
		{
			redirect('admin/promo', 'refresh');
		}

		$image_ids = $this->input->post('image_ids');
		$prompt = $this->input->post('prompt');

		if (empty($image_ids) || empty($prompt))
		{
			$this->session->set_flashdata('alert', 'Please select at least one image and provide a prompt.');
			$this->session->set_flashdata('alert-type', 'warning');
			redirect('admin/promo', 'refresh');
		}

		$this->load->library('zip');

		// Add selected images
		foreach ($image_ids as $id)
		{
			$image = $this->promo_image_model->getById($id);
			if ($image)
			{
				$filepath = FCPATH . 'imgs/promo/' . $image->filename;
				if (file_exists($filepath))
				{
					// Use label or original name for the filename in the zip
					$ext = pathinfo($image->filename, PATHINFO_EXTENSION);
					$name = $image->label ? $image->label . '.' . $ext : $image->original_name;
					$this->zip->read_file($filepath, $name);
				}
			}
		}

		// Add prompt.txt
		$this->zip->add_data('prompt.txt', $prompt);

		// Generate venue name for zip filename
		$venue = $this->input->post('venue_name') ?: 'promo';
		$date = $this->input->post('event_date') ?: date('Y-m-d');
		$zip_name = 'promo-' . url_title($venue, '-', TRUE) . '-' . $date . '.zip';

		$this->zip->download($zip_name);
	}
}
