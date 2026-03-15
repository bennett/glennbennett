<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'core/Admin_Controller.php';

class Admin_dup extends Admin_Controller {

	private $perform_calendar = [
		[
			'url'  => 'https://calendar.google.com/calendar/ical/c_8oqt9e7bms1sefskr0fl01r7tg%40group.calendar.google.com/public/basic.ics',
			'name' => 'perform',
		]
	];

	private $all_calendars = [
		[
			'url'  => 'https://calendar.google.com/calendar/ical/gbennett%40tsgdev.com/private-61ab22cc7e10980c9bd2cab69790fd1a/basic.ics',
			'name' => 'my-cal',
		],
		[
			'url'  => 'https://calendar.google.com/calendar/ical/c_8oqt9e7bms1sefskr0fl01r7tg%40group.calendar.google.com/public/basic.ics',
			'name' => 'perform',
		]
	];

	/**
	 * List past performances with "Duplicate" buttons
	 */
	public function index()
	{
		$this->page_data['page']->title = 'Duplicate Events';
		$this->page_data['page']->menu = 'dup_events';

		require_once FCPATH . 'gcal/libs/gcal_reader.php';

		$gcal_reader = new gcal_reader($this->perform_calendar);

		$start_date = new DateTime('-360 days');
		$end_date = new DateTime();

		$events = $gcal_reader->get_events(
			$start_date->getTimestamp(),
			$end_date->getTimestamp()
		);

		$events = array_reverse($events);

		$this->page_data['events'] = $events;

		$this->load->view('admin/dup_events_list', $this->page_data);
	}

	/**
	 * Show events for a specific date, ready to duplicate
	 */
	public function day()
	{
		$this->page_data['page']->title = 'Duplicate Events';
		$this->page_data['page']->menu = 'dup_events';

		$timestamp = $this->input->get('date') ? (int) $this->input->get('date') : time();
		$date = new DateTime("@$timestamp");
		$date->setTimezone(new DateTimeZone('America/Los_Angeles'));

		require_once FCPATH . 'gcal/libs/CalendarLibrary.php';

		$dateStr = $date->format('Y-m-d');
		$calendarLibrary = new CalendarLibrary($this->all_calendars, $dateStr, $dateStr);
		$events = $calendarLibrary->getEvents();

		$this->page_data['events'] = $events;
		$this->page_data['date'] = $date;
		$this->page_data['next_same_day'] = $this->_get_next_same_day($date->format('Y-m-d'));

		$this->load->view('admin/dup_events', $this->page_data);
	}

	/**
	 * Generate CSV from selected events
	 */
	public function generate_csv()
	{
		if ($this->input->method() !== 'post')
		{
			redirect('admin/dup_events', 'refresh');
		}

		$events = $this->input->post('events');
		$newEventDate = $this->input->post('newEventDate');

		if ( ! $events || ! $newEventDate)
		{
			$this->session->set_flashdata('alert', 'No events selected.');
			$this->session->set_flashdata('alert-type', 'warning');
			redirect('admin/dup_events', 'refresh');
		}

		$csvData = [
			['Subject', 'Start Date', 'Start Time', 'End Date', 'End Time', 'Description', 'Location']
		];

		foreach ($events as $event)
		{
			if ( ! isset($event['selected'])) continue;

			$csvData[] = [
				$event['Subject'],
				$newEventDate,
				$event['Start_Time'],
				$newEventDate,
				$event['End_Time'],
				$event['Description'],
				stripslashes($event['Location']),
			];
		}

		if (count($csvData) < 2)
		{
			$this->session->set_flashdata('alert', 'No events selected.');
			$this->session->set_flashdata('alert-type', 'warning');
			redirect('admin/dup_events', 'refresh');
		}

		$date = new DateTime($newEventDate);

		$this->page_data['page']->title = 'CSV Preview';
		$this->page_data['page']->menu = 'dup_events';
		$this->page_data['csv_data'] = $csvData;
		$this->page_data['new_date'] = $date;

		// Write CSV file
		$filename = 'events.csv';
		$filepath = FCPATH . $filename;
		$file = fopen($filepath, 'w');
		foreach ($csvData as $row)
		{
			fputcsv($file, $row);
		}
		fclose($file);

		$this->page_data['csv_filename'] = $filename;

		$this->load->view('admin/dup_events_csv', $this->page_data);
	}

	private function _get_next_same_day($date)
	{
		$dateTime = new DateTime($date);
		$dayOfWeek = $dateTime->format('l');
		$today = new DateTime();

		while ($today->format('l') !== $dayOfWeek)
		{
			$today->modify('+1 day');
		}

		return $today->format('Y-m-d');
	}
}
