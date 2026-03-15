<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SES Email Library
 *
 * Sends email via Amazon SES API (us-west-1).
 * Falls back to CI3's built-in email library if SES credentials aren't configured.
 */
class Ses_email
{
    private $client;
    private $from_email;
    private $from_name;
    private $to;
    private $reply_to;
    private $subject;
    private $body;
    private $debug_info = '';
    private $available = false;

    public function __construct()
    {
        $key = getenv('AWS_ACCESS_KEY_ID') ?: $_ENV['AWS_ACCESS_KEY_ID'] ?? '';
        $secret = getenv('AWS_SECRET_ACCESS_KEY') ?: $_ENV['AWS_SECRET_ACCESS_KEY'] ?? '';
        $region = getenv('AWS_DEFAULT_REGION') ?: $_ENV['AWS_DEFAULT_REGION'] ?? 'us-west-1';

        if ($key && $secret) {
            $this->client = new Aws\Ses\SesClient([
                'version' => 'latest',
                'region' => $region,
                'credentials' => [
                    'key' => $key,
                    'secret' => $secret,
                ],
            ]);
            $this->available = true;
        }
    }

    public function is_available(): bool
    {
        return $this->available;
    }

    public function from(string $email, string $name = ''): self
    {
        $this->from_email = $email;
        $this->from_name = $name;
        return $this;
    }

    public function to(string $email): self
    {
        $this->to = $email;
        return $this;
    }

    public function reply_to(string $email): self
    {
        $this->reply_to = $email;
        return $this;
    }

    public function subject(string $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    public function message(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    public function send(): bool
    {
        if (!$this->available) {
            $this->debug_info = 'SES not configured — AWS credentials missing from .env';
            return false;
        }

        $from = $this->from_name
            ? "{$this->from_name} <{$this->from_email}>"
            : $this->from_email;

        $params = [
            'Source' => $from,
            'Destination' => [
                'ToAddresses' => [$this->to],
            ],
            'Message' => [
                'Subject' => [
                    'Data' => $this->subject,
                    'Charset' => 'UTF-8',
                ],
                'Body' => [
                    'Html' => [
                        'Data' => $this->body,
                        'Charset' => 'UTF-8',
                    ],
                ],
            ],
        ];

        if ($this->reply_to) {
            $params['ReplyToAddresses'] = [$this->reply_to];
        }

        try {
            $result = $this->client->sendEmail($params);
            $this->debug_info = 'SES MessageId: ' . $result['MessageId'];
            return true;
        } catch (\Exception $e) {
            $this->debug_info = 'SES Error: ' . $e->getMessage();
            log_message('error', 'SES email failed: ' . $e->getMessage());
            return false;
        }
    }

    public function print_debugger(): string
    {
        return $this->debug_info;
    }
}
