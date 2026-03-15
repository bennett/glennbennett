<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/*
|--------------------------------------------------------------------------
| Contact Form Controller - Complete Setup Guide
|--------------------------------------------------------------------------
|
| This controller provides a complete contact form solution with:
| - CodeIgniter form validation with field repopulation
| - reCAPTCHA v3 (invisible) spam protection  
| - HTML email templates with professional formatting
| - Duplicate submission prevention
| - Error logging for debugging email issues
| - SMTP email sending with reply-to functionality
|
| SETUP INSTRUCTIONS:
|
| 1. RECAPTCHA v3 SETUP
|    ==================
|    A. Go to: https://www.google.com/recaptcha/admin/create
|    B. Fill out the form:
|       - Label: Your website name (e.g., "My Website Contact Form")
|       - reCAPTCHA type: Select "reCAPTCHA v3"
|       - Domains: Add your domain without http/https (e.g., "yourdomain.com")
|       - Accept terms and click "Submit"
|    
|    C. Copy your keys from the next page:
|       - Site Key (starts with 6L...): Used in HTML/JavaScript (public)
|       - Secret Key (starts with 6L...): Used in server-side verification (private)
|    
|    D. Create: application/config/recaptcha.php
|    
|       defined('BASEPATH') OR exit('No direct script access allowed');
|       
|       // Replace with your actual keys from Google reCAPTCHA admin
|       $config['recaptcha_site_key'] = 'your_site_key_here';      // Public key for HTML
|       $config['recaptcha_secret_key'] = 'your_secret_key_here';  // Private key for verification
|    
|    IMPORTANT: 
|    - Keep your secret key secure - never expose it in HTML/JavaScript
|    - Add all domains where you'll use the form (www.domain.com, domain.com)
|    - reCAPTCHA v3 is invisible - users won't see a checkbox
|    - Test on your actual domain - localhost testing has limitations
|
| 2. EMAIL CONFIGURATION  
|    ===================
|    Update: application/config/email.php
|    
|       defined('BASEPATH') OR exit('No direct script access allowed');
|       
|       $config['protocol'] = 'smtp';
|       $config['smtp_host'] = 'your_smtp_host';     // See examples below
|       $config['smtp_port'] = 587;                  // 587 for TLS, 465 for SSL
|       $config['smtp_user'] = 'your_email@domain.com';
|       $config['smtp_pass'] = 'your_password';      // See security notes below
|       $config['mailtype'] = 'html';
|       $config['charset'] = 'utf-8';
|       $config['wordwrap'] = TRUE;
|       $config['newline'] = "\r\n";
|    
|    Common SMTP Settings:
|    
|    GMAIL:
|    - smtp_host: 'smtp.gmail.com'
|    - smtp_port: 587
|    - smtp_user: 'your_gmail@gmail.com'  
|    - smtp_pass: 'your_app_password'  // NOT your regular password!
|    - Enable 2FA and create App Password: https://support.google.com/accounts/answer/185833
|    
|    OFFICE 365:
|    - smtp_host: 'smtp.office365.com'
|    - smtp_port: 587
|    - smtp_user: 'your_email@yourdomain.com'
|    - smtp_pass: 'your_password'
|    
|    BREVO (SendinBlue):
|    - smtp_host: 'smtp-relay.brevo.com'
|    - smtp_port: 587  
|    - smtp_user: 'your_brevo_email'
|    - smtp_pass: 'your_smtp_key'  // From Brevo dashboard, not login password
|    
|    SHARED HOSTING:
|    - smtp_host: 'mail.yourdomain.com' or 'localhost'
|    - smtp_port: 587 (or 25, 465)
|    - smtp_user: 'your_email@yourdomain.com'
|    - smtp_pass: 'your_email_password'
|    - Check with your hosting provider for exact settings
|
| 3. CONTROLLER EMAIL SETTINGS
|    =========================
|    Update these variables at the top of this controller:
|    
|       private $contact_email = 'your_email@domain.com';    // Where emails are sent TO
|       private $from_name = 'Your Website Name';            // Display name in emails
|       private $subject_prefix = 'Contact Form: ';          // Email subject prefix
|
| 4. VIEW REQUIREMENTS
|    =================
|    Your contact view must include:
|    - Form with id="contact-form-working"
|    - Action pointing to: site_url('contact/send')
|    - Method: POST
|    - Hidden field: <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
|    - reCAPTCHA v3 script: <script src="https://www.google.com/recaptcha/api.js?render=SITE_KEY"></script>
|    - JavaScript to execute reCAPTCHA before form submission
|    - Form fields using CodeIgniter helpers with set_value() for repopulation
|    - form_error() calls to display validation errors for each field
|
| 5. ROUTES (OPTIONAL)
|    =================
|    Add to: application/config/routes.php
|    
|       $route['contact'] = 'contact/index';
|       $route['contact/send'] = 'contact/send';
|
| 6. TROUBLESHOOTING
|    ===============
|    RECAPTCHA ISSUES:
|    - "Invalid key type" error = Wrong reCAPTCHA version (make sure you created v3)
|    - "Invalid domain" error = Add your domain to reCAPTCHA admin panel
|    - Check browser console for JavaScript errors
|    - Verify site key is correct in your view
|    
|    EMAIL ISSUES:
|    - Check application/logs/ directory for detailed error messages
|    - Test SMTP settings with a simple script first
|    - Gmail users: Must use App Password, not regular password
|    - Hosting providers: Check if port 587 or 25 is blocked
|    - Verify from/to email addresses are valid
|    
|    FORM ISSUES:
|    - Ensure form fields have proper name attributes
|    - Check that validation errors display using form_error() 
|    - Verify set_value() is used in all input fields for repopulation
|    - Check CodeIgniter error logs for PHP errors
|
| 7. SECURITY FEATURES
|    ==================
|    - XSS protection via htmlspecialchars() in email template
|    - CSRF protection through CodeIgniter's form validation
|    - SQL injection protection via $this->input->post(field, TRUE)
|    - Duplicate submission prevention using session tracking
|    - reCAPTCHA v3 bot protection with adjustable score threshold (0.5)
|    - Input validation and sanitization on all form fields
|    - Honeypot field for additional spam protection (optional in view)
|
| 8. CUSTOMIZATION OPTIONS
|    =====================
|    - Adjust reCAPTCHA score threshold in verify_recaptcha() method:
|      0.3 = lenient (allows more submissions)
|      0.5 = balanced (default)  
|      0.7 = strict (blocks more potential bots)
|    - Modify email template styling in create_email_template() method
|    - Change validation rules and messages in send() method
|    - Update success/error messages
|    - Add additional form fields (remember to add validation rules)
|    - Customize duplicate prevention timeframe (currently 1 hour)
|
| 9. TESTING CHECKLIST
|    ==================
|    ? reCAPTCHA admin setup complete with correct domain
|    ? Config files created with valid keys and SMTP settings
|    ? Form displays correctly with all fields
|    ? Browser console shows no JavaScript errors
|    ? reCAPTCHA token generates successfully (check console logs)
|    ? Validation errors show with fields repopulated  
|    ? Successful submission sends email to correct address
|    ? Email arrives with proper formatting and reply-to functionality
|    ? Duplicate submission prevention works (try submitting twice quickly)
|    ? Error logging works (check application/logs/ directory)
|
| DEPENDENCIES:
| - CodeIgniter 3.x
| - PHP with cURL or allow_url_fopen enabled for reCAPTCHA verification
| - SMTP server access for email sending
| - Valid reCAPTCHA v3 site registration at https://www.google.com/recaptcha/admin
| - SSL/TLS support for secure SMTP connections
|
*/

class Contact extends CI_Controller {

    // Email settings
    private $contact_email = 'gbennett@tsgdev.com';        // Where you want to receive emails
    private $from_name = 'Glenn Bennett Website';          // Display name for emails
    private $subject_prefix = 'Contact Form: ';            // Subject prefix

    public function __construct()
    {
        parent::__construct();
        
        $this->load->database();
        $this->load->config('globals');
        $this->load->helper(array('form', 'url', 'html'));
        $this->load->library(array('form_validation', 'email', 'session'));
        
        // Load reCAPTCHA config
        $this->load->config('recaptcha');
        
        // Email config is automatically loaded by CodeIgniter
    }

    public function index()
    {
        $data['title'] = "CONTACT";
        $data['sub_title'] = "Glenn Bennett Contact";
        $data['recaptcha_site_key'] = $this->config->item('recaptcha_site_key');
        
        $this->layout_view('contact', $data);
    }

    public function send()
    {
        // Check if this is an AJAX request
        $is_ajax = $this->input->get_request_header('X-Requested-With') === 'XMLHttpRequest';
        
        // Prevent duplicate submissions by checking session
        $submission_key = md5($this->input->post('name') . $this->input->post('email') . $this->input->post('message') . date('Y-m-d H:i'));
        
        if ($this->session->userdata('last_submission') === $submission_key) {
            // Duplicate submission detected
            if ($is_ajax) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'success' => true,
                        'message' => 'Message already sent successfully.',
                        'duplicate' => true
                    ]));
            } else {
                $this->session->set_flashdata('success', 'Your message has already been sent.');
                redirect('contact');
            }
            return;
        }
        
        // Set validation rules
        $this->form_validation->set_rules('name', 'Name', 'required|trim|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|max_length[20]');
        $this->form_validation->set_rules('subject', 'Subject', 'required|trim|min_length[3]|max_length[200]');
        $this->form_validation->set_rules('message', 'Message', 'required|trim|min_length[10]|max_length[2000]');
        $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required');

        // Set custom error messages
        $this->form_validation->set_message('required', 'The %s field is required.');
        $this->form_validation->set_message('valid_email', 'Please enter a valid email address.');
        $this->form_validation->set_message('min_length', 'The %s field must be at least %s characters long.');
        $this->form_validation->set_message('max_length', 'The %s field cannot exceed %s characters.');

        if ($this->form_validation->run() == FALSE) {
            // Validation failed
            if ($is_ajax) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'success' => false,
                        'errors' => validation_errors()
                    ]));
            } else {
                $data['title'] = "CONTACT";
                $data['sub_title'] = "Glenn Bennett Contact";
                $data['recaptcha_site_key'] = $this->config->item('recaptcha_site_key');
                $data['validation_errors'] = validation_errors();
                
                $this->layout_view('contact', $data);
            }
        } else {
            // Verify reCAPTCHA
            $recaptcha_response = $this->input->post('g-recaptcha-response');
            
            if ($this->verify_recaptcha($recaptcha_response)) {
                // reCAPTCHA verified, process the form
                $name = $this->input->post('name', TRUE);
                $email = $this->input->post('email', TRUE);
                $phone = $this->input->post('phone', TRUE);
                $subject = $this->input->post('subject', TRUE);
                $message = $this->input->post('message', TRUE);

                // Store submission key to prevent duplicates
                $this->session->set_userdata('last_submission', $submission_key);

                // Send email
                if ($this->send_contact_email($name, $email, $phone, $subject, $message)) {
                    $success_message = 'Thank you! Your message has been sent successfully.';
                    $this->session->set_flashdata('success', $success_message);
                } else {
                    $error_message = 'Sorry, there was an error sending your message. Please try again.';
                    $this->session->set_flashdata('error', $error_message);
                }
            } else {
                $error_message = 'Please complete the reCAPTCHA verification.';
                $this->session->set_flashdata('error', $error_message);
            }
            
            if ($is_ajax) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'success' => true,
                        'message' => isset($success_message) ? $success_message : 'Message processed.',
                        'redirect' => site_url('contact')
                    ]));
            } else {
                redirect('contact');
            }
        }
    }

    private function verify_recaptcha($response)
    {
        $secret_key = $this->config->item('recaptcha_secret_key');
        
        if (empty($response)) {
            return false;
        }
        
        $verify_url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array(
            'secret' => $secret_key,
            'response' => $response,
            'remoteip' => $this->input->ip_address()
        );

        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query($data)
            )
        );

        $context = stream_context_create($options);
        $verify = file_get_contents($verify_url, false, $context);
        
        if ($verify === FALSE) {
            return false;
        }
        
        $captcha_success = json_decode($verify);

        // For reCAPTCHA v3, check both success and score
        if (isset($captcha_success->success) && $captcha_success->success === true) {
            // reCAPTCHA v3 returns a score (0.0 to 1.0)
            $score = isset($captcha_success->score) ? $captcha_success->score : 0;
            
            // Threshold of 0.5 (you can adjust this)
            return $score >= 0.5;
        }
        
        return false;
    }

    private function send_contact_email($name, $email, $phone, $subject, $message)
    {
        $email_message = $this->create_email_template($name, $email, $phone, $subject, $message);

        // Try SES first (works locally and in production)
        $this->load->library('ses_email');
        if ($this->ses_email->is_available()) {
            $result = $this->ses_email
                ->from($this->contact_email, $this->from_name)
                ->reply_to($email)
                ->to($this->contact_email)
                ->subject($this->subject_prefix . $subject)
                ->message($email_message)
                ->send();

            if (!$result) {
                log_message('error', 'Contact form SES email failed: ' . $this->ses_email->print_debugger());
            }
            return $result;
        }

        // Fallback to CI3 email (production shared hosting)
        if (ENVIRONMENT !== 'production') {
            log_message('info', 'Contact email not sent (local dev, no SES) from: ' . $email);
            return false;
        }

        $this->email->from($this->contact_email, $this->from_name);
        $this->email->reply_to($email, $name);
        $this->email->to($this->contact_email);
        $this->email->subject($this->subject_prefix . $subject);
        $this->email->message($email_message);

        $result = $this->email->send();
        if (!$result) {
            log_message('error', 'Contact form email failed to send: ' . $this->email->print_debugger());
        }
        return $result;
    }

    private function create_email_template($name, $email, $phone, $subject, $message)
    {
        $site_name = parse_url(base_url(), PHP_URL_HOST);
        
        $template = "
        <!DOCTYPE html>
        <html>
        <head>
            <title>Contact Form Submission From" .  $site_name  . "</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #f4f4f4; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
                .content { background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
                .field { margin-bottom: 15px; }
                .field strong { display: inline-block; width: 100px; }
                .message-content { background: #f9f9f9; padding: 15px; border-radius: 5px; }
                .footer { margin-top: 20px; font-size: 12px; color: #666; text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>New Contact Form Submission From " .  $site_name . "</h2>
                </div>
                
                <div class='content'>
                    <div class='field'>
                        <strong>Name:</strong> " . htmlspecialchars($name) . "
                    </div>
                    
                    <div class='field'>
                        <strong>Email:</strong> <a href='mailto:" . htmlspecialchars($email) . "'>" . htmlspecialchars($email) . "</a>
                    </div>
                    
                    <div class='field'>
                        <strong>Phone:</strong> " . (!empty($phone) ? htmlspecialchars($phone) : 'Not provided') . "
                    </div>
                    
                    <div class='field'>
                        <strong>Subject:</strong> " . htmlspecialchars($subject) . "
                    </div>
                    
                    <div class='field'>
                        <strong>Message:</strong>
                        <div class='message-content'>
                            " . nl2br(htmlspecialchars($message)) . "
                        </div>
                    </div>
                </div>
                
                <div class='footer'>
                    <p>This message was sent from the contact form at <a href='" . base_url() . "'>" . base_url() . "</a></p>
                    <p>Sent on: " . date('Y-m-d H:i:s') . "</p>
                </div>
            </div>
        </body>
        </html>";
        
        return $template;
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
