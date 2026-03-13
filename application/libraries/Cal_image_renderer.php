<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cal_image_renderer {

    private $im;
    private $image_width;
    private $image_height;
    private $x;
    private $y;

    /**
     * Render text onto a background image
     *
     * @param string $img_file  Absolute path to background image
     * @param array  $texts     ['summary', 'date', 'time', 'location']
     * @param array  $layout    ['text_offset', 'summary_font_size', 'summary_margin_top',
     *                           'date_font_size', 'date_margin_top', 'time_font_size',
     *                           'time_margin_top', 'location_font_size', 'location_margin_top']
     * @param string $font_dir  Absolute path to fonts directory (with trailing slash)
     * @return resource|false   GD image resource, or false on failure
     */
    public function render($img_file, $texts, $layout, $font_dir)
    {
        $font_bold = $font_dir . 'GEORGIAB.TTF';
        $font_regular = $font_dir . 'GEORGIA.TTF';

        // Load image (try JPEG first, then PNG)
        $this->im = @imagecreatefromjpeg($img_file);
        if (!$this->im) {
            $this->im = @imagecreatefrompng($img_file);
        }
        if (!$this->im) {
            return false;
        }

        $black = imagecolorallocate($this->im, 0, 0, 0);

        $this->image_width = imagesx($this->im);
        $this->image_height = imagesy($this->im);

        $text_offset = isset($layout['text_offset']) ? (int) $layout['text_offset'] : -200;
        $summary_font_size = isset($layout['summary_font_size']) ? (int) $layout['summary_font_size'] : 36;
        $date_font_size = isset($layout['date_font_size']) ? (int) $layout['date_font_size'] : 24;
        $time_font_size = isset($layout['time_font_size']) ? (int) $layout['time_font_size'] : 36;
        $location_font_size = isset($layout['location_font_size']) ? (int) $layout['location_font_size'] : 24;
        $summary_margin_top = isset($layout['summary_margin_top']) ? (int) $layout['summary_margin_top'] : 260;
        $date_margin_top = isset($layout['date_margin_top']) ? (int) $layout['date_margin_top'] : 25;
        $time_margin_top = isset($layout['time_margin_top']) ? (int) $layout['time_margin_top'] : 25;
        $location_margin_top = isset($layout['location_margin_top']) ? (int) $layout['location_margin_top'] : 25;

        $summary = isset($texts['summary']) ? $texts['summary'] : '';
        $date = isset($texts['date']) ? $texts['date'] : '';
        $time = isset($texts['time']) ? $texts['time'] : '';
        $location = isset($texts['location']) ? $texts['location'] : '';

        // Build main text entries
        $msg_text_main = [
            ['text' => $summary, 'font' => $font_bold, 'font_size' => $summary_font_size],
            ['text' => $date,    'font' => $font_bold, 'font_size' => $date_font_size],
            ['text' => ' ',      'font' => $font_bold, 'font_size' => 18],
            ['text' => $time,    'font' => $font_bold, 'font_size' => $time_font_size],
        ];

        $this->y = $summary_margin_top;

        // Print main text with long-text wrapping
        foreach ($msg_text_main as $idx => $msg) {
            $text = $msg['text'];

            // Long text wrapping: split at middle space if > 30 chars
            if (strlen($text) > 30) {
                $middle = strlen($text) / 2;
                $spaces = [];

                for ($i = 0; $i < strlen($text); $i++) {
                    if ($text[$i] == ' ') {
                        $spaces[] = $i;
                    }
                }

                if (!empty($spaces)) {
                    $best_space = $spaces[0];
                    foreach ($spaces as $space_pos) {
                        if (abs($space_pos - $middle) < abs($best_space - $middle)) {
                            $best_space = $space_pos;
                        }
                    }

                    $first_half = substr($text, 0, $best_space);
                    $second_half = substr($text, $best_space + 1);

                    $msg_copy1 = $msg;
                    $msg_copy1['text'] = $first_half;
                    $this->print_line($msg_copy1, $text_offset, $black);

                    $msg_copy2 = $msg;
                    $msg_copy2['text'] = $second_half;
                    $this->print_line($msg_copy2, $text_offset, $black);
                } else {
                    $this->print_line($msg, $text_offset, $black);
                }
            } else {
                $this->print_line($msg, $text_offset, $black);
            }
        }

        $this->y += 20;

        // Location
        if ($location) {
            $msg_text = [
                'text'      => $location,
                'font'      => $font_regular,
                'font_size' => $location_font_size
            ];
            $this->print_line($msg_text, $text_offset, $black);
        }

        return $this->im;
    }

    private function print_line($msg, $offset, $color)
    {
        $lines = $this->get_lines($msg['text']);
        foreach ($lines as $line) {
            $msg['text'] = $line;
            $this->set_type($msg, $offset, $color);
        }
    }

    private function get_lines($text)
    {
        $pos = strpos($text, ",");
        if ($pos === false) {
            $lines[] = $text;
        } else {
            $lines[] = substr($text, 0, $pos);
            $lines[] = substr($text, $pos + 1);
        }
        return $lines;
    }

    private function set_type($msg, $offset, $color)
    {
        $text = $msg['text'];
        $font = $msg['font'];
        $font_size = $msg['font_size'];

        $text_box = imagettfbbox($font_size, 0, $font, $text);

        $text_width = $text_box[2] - $text_box[0];
        $text_height = $text_box[7] - $text_box[1];

        $this->x = ($this->image_width / 2) - ($text_width / 2) + $offset;
        $this->y = $this->y - $text_height + 10;

        imagettftext($this->im, $font_size, 0, $this->x, $this->y, $color, $font, $text);
    }
}
