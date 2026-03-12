<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends CI_Controller {

    private $contact_email = 'gbennett@tsgdev.com';
    private $from_name = 'Glenn Bennett Website';
    private $subject_prefix = 'Booking Request: ';

    public function __construct()
    {
        parent::__construct();
        $this->load->config('globals');
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('form_validation', 'email', 'session'));
        $this->load->config('recaptcha');
    }

    public function index()
    {
        $data['title'] = "BOOKING";
        $data['sub_title'] = "Glenn Bennett Booking";
        $data['recaptcha_site_key'] = $this->config->item('recaptcha_site_key');

        $this->layout_view('booking_form', $data);
    }

    public function submit()
    {
        // Duplicate submission prevention
        $submission_key = md5(
            $this->input->post('contactName') .
            $this->input->post('contactEmail') .
            $this->input->post('eventDate') .
            date('Y-m-d H:i')
        );

        if ($this->session->userdata('last_booking_submission') === $submission_key) {
            $this->session->set_flashdata('success', 'Your booking request has already been sent.');
            redirect('booking');
            return;
        }

        // Validation rules
        $this->form_validation->set_rules('contactName', 'Contact Name', 'required|trim|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('contactEmail', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('contactPhone', 'Phone', 'required|trim|max_length[20]');
        $this->form_validation->set_rules('eventDate', 'Event Date', 'required|trim');
        $this->form_validation->set_rules('startTime', 'Start Time', 'required|trim|callback_valid_time');
        $this->form_validation->set_rules('eventType', 'Event Type', 'required|trim|max_length[200]');
        $this->form_validation->set_rules('duration', 'Duration', 'required|trim|max_length[50]');
        $this->form_validation->set_rules('venue', 'Venue', 'required|trim|max_length[300]');
        $this->form_validation->set_rules('audienceSize', 'Audience Size', 'trim|max_length[50]');
        $this->form_validation->set_rules('musicStyle', 'Music Style', 'trim|max_length[200]');
        $this->form_validation->set_rules('budget', 'Budget', 'trim|max_length[100]');
        $this->form_validation->set_rules('songRequests', 'Song Requests', 'trim|max_length[2000]');
        $this->form_validation->set_rules('additionalComments', 'Additional Comments', 'trim|max_length[2000]');
        $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = "BOOKING";
            $data['sub_title'] = "Glenn Bennett Booking";
            $data['recaptcha_site_key'] = $this->config->item('recaptcha_site_key');
            $data['validation_errors'] = validation_errors();

            $this->layout_view('booking_form', $data);
            return;
        }

        // Verify reCAPTCHA
        $recaptcha_response = $this->input->post('g-recaptcha-response');
        if (!$this->verify_recaptcha($recaptcha_response)) {
            $this->session->set_flashdata('error', 'Please complete the reCAPTCHA verification.');
            redirect('booking');
            return;
        }

        // Collect sanitized form data
        $form = array(
            'contactName'        => $this->input->post('contactName', TRUE),
            'contactEmail'       => $this->input->post('contactEmail', TRUE),
            'contactPhone'       => $this->input->post('contactPhone', TRUE),
            'eventDate'          => $this->input->post('eventDate', TRUE),
            'startTime'          => $this->input->post('startTime', TRUE),
            'eventType'          => $this->input->post('eventType', TRUE),
            'duration'           => $this->input->post('duration', TRUE),
            'venue'              => $this->input->post('venue', TRUE),
            'audienceSize'       => $this->input->post('audienceSize', TRUE),
            'musicStyle'         => $this->input->post('musicStyle', TRUE),
            'budget'             => $this->input->post('budget', TRUE),
            'songRequests'       => $this->input->post('songRequests', TRUE),
            'additionalComments' => $this->input->post('additionalComments', TRUE),
        );

        // Prevent duplicates
        $this->session->set_userdata('last_booking_submission', $submission_key);

        // Send email
        if ($this->send_booking_email($form)) {
            $this->session->set_flashdata('success', 'Thank you! Your booking request has been sent successfully.');
        } else {
            $this->session->set_flashdata('error', 'Sorry, there was an error sending your request. Please try again.');
        }

        redirect('booking');
    }

    public function valid_time($str)
    {
        // Accepts formats like: 7:00 PM, 7:00PM, 7pm, 7 PM, 19:00, 7:30pm, 12:00 AM
        $pattern = '/^(1[0-2]|0?[1-9])(:[0-5][0-9])?\s*(am|pm|AM|PM)$|^([01]?[0-9]|2[0-3]):[0-5][0-9]$/';

        if (!preg_match($pattern, trim($str))) {
            $this->form_validation->set_message('valid_time', 'The {field} field must be a valid time (e.g. 7:00 PM, 7pm, 19:00).');
            return FALSE;
        }

        return TRUE;
    }

    private function verify_recaptcha($response)
    {
        $secret_key = $this->config->item('recaptcha_secret_key');

        if (empty($response)) {
            return false;
        }

        $verify_url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array(
            'secret'   => $secret_key,
            'response' => $response,
            'remoteip' => $this->input->ip_address()
        );

        $options = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query($data)
            )
        );

        $context = stream_context_create($options);
        $verify = file_get_contents($verify_url, false, $context);

        if ($verify === FALSE) {
            return false;
        }

        $captcha_success = json_decode($verify);

        if (isset($captcha_success->success) && $captcha_success->success === true) {
            $score = isset($captcha_success->score) ? $captcha_success->score : 0;
            return $score >= 0.5;
        }

        return false;
    }

    private function send_booking_email($form)
    {
        $this->email->from($this->contact_email, $this->from_name);
        $this->email->reply_to($form['contactEmail'], $form['contactName']);
        $this->email->to($this->contact_email);
        $this->email->subject($this->subject_prefix . $form['eventType'] . ' - ' . $form['eventDate']);

        $email_message = $this->create_email_template($form);
        $this->email->message($email_message);

        $result = $this->email->send();

        if (!$result) {
            log_message('error', 'Booking form email failed to send: ' . $this->email->print_debugger());
        }

        return $result;
    }

    private function create_email_template($form)
    {
        $site_name = parse_url(base_url(), PHP_URL_HOST);

        $fields = array(
            'Contact Name'       => $form['contactName'],
            'Email'              => '<a href="mailto:' . htmlspecialchars($form['contactEmail']) . '">' . htmlspecialchars($form['contactEmail']) . '</a>',
            'Phone'              => $form['contactPhone'],
            'Event Date'         => $form['eventDate'],
            'Start Time'         => $form['startTime'],
            'Event Type'         => $form['eventType'],
            'Duration'           => $form['duration'],
            'Venue'              => $form['venue'],
            'Audience Size'      => $form['audienceSize'],
            'Music Style'        => $form['musicStyle'],
            'Budget'             => $form['budget'],
            'Song Requests'      => $form['songRequests'],
            'Additional Comments'=> $form['additionalComments'],
        );

        $rows = '';
        foreach ($fields as $label => $value) {
            $display = !empty($value) ? $value : '<em>Not provided</em>';
            // Don't double-escape the email link
            if ($label !== 'Email') {
                $display = !empty($value) ? htmlspecialchars($value) : '<em>Not provided</em>';
            }
            $rows .= "
                    <div class='field'>
                        <strong>{$label}:</strong> {$display}
                    </div>";
        }

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <title>Booking Request From {$site_name}</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #f4f4f4; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
                .content { background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
                .field { margin-bottom: 15px; }
                .field strong { display: inline-block; width: 150px; }
                .footer { margin-top: 20px; font-size: 12px; color: #666; text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>New Booking Request From {$site_name}</h2>
                </div>
                <div class='content'>{$rows}
                </div>
                <div class='footer'>
                    <p>This message was sent from the booking form at <a href='" . base_url() . "'>" . base_url() . "</a></p>
                    <p>Sent on: " . date('Y-m-d H:i:s') . "</p>
                </div>
            </div>
        </body>
        </html>";
    }

    function layout_view($view, $data)
    {
        $layout = $this->load->view('layouts/canvas-white.php', $data, true);
        $nav = $this->load->view('partials/nav.php', '', true);
        $content = $this->load->view($view, $data, true);

        $js = "";
        $css = "";
        if (is_file(APPPATH."views/js/$view.php"))
        {
            $js = $this->load->view("js/$view.php", '', true);
        }
        if (is_file(APPPATH."views/css/$view.php"))
        {
            $css = $this->load->view("css/$view.php", '', true);
        }

        $layout = str_replace('[nav]', $nav, $layout);
        $layout = str_replace('[javascript]', $js, $layout);
        $layout = str_replace('[css]', $css, $layout);
        $page = str_replace('[content]', $content, $layout);

        echo $page;
    }
}
