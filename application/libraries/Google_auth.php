<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Google OAuth 2.0 Authentication Library
 *
 * Self-contained library using raw cURL. No Composer dependencies.
 * Reads credentials from .env via getenv() (same pattern as Ses_email).
 * Can be copied to any CI3 site — just add the four GOOGLE_AUTH_* env vars.
 */
class Google_auth
{
    private $client_id;
    private $client_secret;
    private $redirect_uri;
    private $allowed_email;
    private $CI;

    private $auth_url = 'https://accounts.google.com/o/oauth2/v2/auth';
    private $token_url = 'https://oauth2.googleapis.com/token';
    private $userinfo_url = 'https://www.googleapis.com/oauth2/v3/userinfo';

    public function __construct()
    {
        $this->client_id     = getenv('GOOGLE_AUTH_CLIENT_ID') ?: $_ENV['GOOGLE_AUTH_CLIENT_ID'] ?? '';
        $this->client_secret = getenv('GOOGLE_AUTH_CLIENT_SECRET') ?: $_ENV['GOOGLE_AUTH_CLIENT_SECRET'] ?? '';
        $this->redirect_uri  = getenv('GOOGLE_AUTH_REDIRECT_URI') ?: $_ENV['GOOGLE_AUTH_REDIRECT_URI'] ?? '';
        $this->allowed_email = getenv('GOOGLE_AUTH_ALLOWED_EMAIL') ?: $_ENV['GOOGLE_AUTH_ALLOWED_EMAIL'] ?? '';
        $this->CI =& get_instance();
    }

    /**
     * Returns true if Google OAuth credentials are configured.
     */
    public function is_configured()
    {
        return ! empty($this->client_id) && ! empty($this->client_secret);
    }

    /**
     * Builds the Google authorization URL and stores a CSRF state token in session.
     */
    public function get_auth_url()
    {
        $state = bin2hex(random_bytes(16));
        $this->CI->session->set_userdata('google_auth_state', $state);

        $params = [
            'client_id'     => $this->client_id,
            'redirect_uri'  => $this->redirect_uri,
            'response_type' => 'code',
            'scope'         => 'email profile',
            'state'         => $state,
            'access_type'   => 'online',
            'prompt'        => 'select_account',
        ];

        return $this->auth_url . '?' . http_build_query($params);
    }

    /**
     * Validates CSRF state and exchanges the auth code for an access token.
     *
     * @param string $code  Authorization code from Google
     * @param string $state CSRF state token from query string
     * @return string|false Access token on success, false on failure
     */
    public function handle_callback($code, $state)
    {
        $saved_state = $this->CI->session->userdata('google_auth_state');
        $this->CI->session->unset_userdata('google_auth_state');

        if (empty($state) || $state !== $saved_state) {
            return false;
        }

        $response = $this->_curl_post($this->token_url, [
            'code'          => $code,
            'client_id'     => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri'  => $this->redirect_uri,
            'grant_type'    => 'authorization_code',
        ]);

        if ( ! $response || empty($response['access_token'])) {
            return false;
        }

        return $response['access_token'];
    }

    /**
     * Fetches user info from Google using the access token.
     *
     * @param string $access_token
     * @return array|false User info array with 'email', 'name', etc. or false
     */
    public function get_user_info($access_token)
    {
        $response = $this->_curl_get($this->userinfo_url, $access_token);

        if ( ! $response || empty($response['email'])) {
            return false;
        }

        return $response;
    }

    /**
     * Case-insensitive check of email against the allowed email.
     *
     * @param string $email
     * @return bool
     */
    public function is_allowed_email($email)
    {
        return strcasecmp(trim($email), trim($this->allowed_email)) === 0;
    }

    /**
     * cURL POST helper. Returns decoded JSON response.
     */
    private function _curl_post($url, $fields)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($fields),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_TIMEOUT        => 15,
        ]);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result ? json_decode($result, true) : false;
    }

    /**
     * cURL GET helper with Bearer token. Returns decoded JSON response.
     */
    private function _curl_get($url, $access_token)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $access_token],
            CURLOPT_TIMEOUT        => 15,
        ]);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result ? json_decode($result, true) : false;
    }
}
