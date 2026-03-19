<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cal_image_renderer {

    private $im;
    private $image_width;
    private $image_height;
    private $x;
    private $y;
    private $glow_radius;
    private $glow_color;
    private $shadow_offset;
    private $stroke_width;
    private $stroke_color_rgb;

    /**
     * Render text onto a background image (original method)
     */
    public function render($img_file, $texts, $layout, $font_dir)
    {
        // Load image (try JPEG first, then PNG)
        $this->im = @imagecreatefromjpeg($img_file);
        if (!$this->im) {
            $this->im = @imagecreatefrompng($img_file);
        }
        if (!$this->im) {
            return false;
        }

        $this->image_width = imagesx($this->im);
        $this->image_height = imagesy($this->im);

        $this->render_text($texts, $layout, $font_dir);

        return $this->im;
    }

    /**
     * Render a template: background + photo composite + text
     */
    public function render_template($bg_file, $photo_file, $texts, $layout, $font_dir)
    {
        // Load background
        $this->im = @imagecreatefromjpeg($bg_file);
        if (!$this->im) {
            $this->im = @imagecreatefrompng($bg_file);
        }
        if (!$this->im) {
            return false;
        }

        $this->image_width = imagesx($this->im);
        $this->image_height = imagesy($this->im);

        imagealphablending($this->im, true);

        // Load photo PNG
        $photo = @imagecreatefrompng($photo_file);
        if ($photo) {
            imagealphablending($photo, true);
            imagesavealpha($photo, true);

            $photo_w = imagesx($photo);
            $photo_h = imagesy($photo);

            $scale = isset($layout['photo_scale']) ? (int) $layout['photo_scale'] : 100;
            $new_w = (int) ($photo_w * $scale / 100);
            $new_h = (int) ($photo_h * $scale / 100);

            $photo_x = isset($layout['photo_x']) ? (int) $layout['photo_x'] : 0;
            $photo_y = isset($layout['photo_y']) ? (int) $layout['photo_y'] : 0;

            // Photo glow (semi-transparent copies behind)
            $photo_glow_radius = isset($layout['photo_glow_radius']) ? (int) $layout['photo_glow_radius'] : 0;
            if ($photo_glow_radius > 0) {
                $glow_hex = isset($layout['photo_glow_color']) ? ltrim($layout['photo_glow_color'], '#') : '000000';
                $gr = $photo_glow_radius;

                // Create a solid-color version of the photo for glow
                $glow_img = imagecreatetruecolor($new_w, $new_h);
                imagealphablending($glow_img, false);
                imagesavealpha($glow_img, true);
                $transparent = imagecolorallocatealpha($glow_img, 0, 0, 0, 127);
                imagefill($glow_img, 0, 0, $transparent);

                // Scale photo to temp
                $scaled_temp = imagecreatetruecolor($new_w, $new_h);
                imagealphablending($scaled_temp, false);
                imagesavealpha($scaled_temp, true);
                imagefill($scaled_temp, 0, 0, $transparent);
                imagecopyresampled($scaled_temp, $photo, 0, 0, 0, 0, $new_w, $new_h, $photo_w, $photo_h);

                // Build glow from photo alpha
                $glow_r = hexdec(substr($glow_hex, 0, 2));
                $glow_g = hexdec(substr($glow_hex, 2, 2));
                $glow_b = hexdec(substr($glow_hex, 4, 2));

                for ($px = 0; $px < $new_w; $px++) {
                    for ($py = 0; $py < $new_h; $py++) {
                        $rgba = imagecolorat($scaled_temp, $px, $py);
                        $alpha = ($rgba >> 24) & 0x7F;
                        if ($alpha < 127) {
                            $glow_alpha = min(127, $alpha + 60);
                            $c = imagecolorallocatealpha($glow_img, $glow_r, $glow_g, $glow_b, $glow_alpha);
                            imagesetpixel($glow_img, $px, $py, $c);
                        }
                    }
                }

                // Render glow at offsets
                for ($ox = -$gr; $ox <= $gr; $ox += max(1, (int)($gr / 3))) {
                    for ($oy = -$gr; $oy <= $gr; $oy += max(1, (int)($gr / 3))) {
                        imagecopy($this->im, $glow_img, $photo_x + $ox, $photo_y + $oy, 0, 0, $new_w, $new_h);
                    }
                }

                imagedestroy($glow_img);
                imagedestroy($scaled_temp);
            }

            // Composite photo onto background
            imagecopyresampled($this->im, $photo, $photo_x, $photo_y, 0, 0, $new_w, $new_h, $photo_w, $photo_h);
            imagedestroy($photo);
        }

        $this->render_text($texts, $layout, $font_dir);

        return $this->im;
    }

    /**
     * Render event text onto the current GD image
     */
    private function render_text($texts, $layout, $font_dir)
    {
        $font_bold = $font_dir . 'GEORGIAB.TTF';
        $font_regular = $font_dir . 'GEORGIA.TTF';

        $hex = isset($layout['font_color']) ? ltrim($layout['font_color'], '#') : '000000';
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $text_color = imagecolorallocate($this->im, $r, $g, $b);

        $text_offset = isset($layout['text_offset']) ? (int) $layout['text_offset'] : -200;
        $summary_font_size = isset($layout['summary_font_size']) ? (int) $layout['summary_font_size'] : 36;
        $date_font_size = isset($layout['date_font_size']) ? (int) $layout['date_font_size'] : 24;
        $time_font_size = isset($layout['time_font_size']) ? (int) $layout['time_font_size'] : 36;
        $location_font_size = isset($layout['location_font_size']) ? (int) $layout['location_font_size'] : 24;
        $summary_margin_top = isset($layout['summary_margin_top']) ? (int) $layout['summary_margin_top'] : 260;
        $date_margin_top = isset($layout['date_margin_top']) ? (int) $layout['date_margin_top'] : 25;
        $time_margin_top = isset($layout['time_margin_top']) ? (int) $layout['time_margin_top'] : 25;
        $location_margin_top = isset($layout['location_margin_top']) ? (int) $layout['location_margin_top'] : 25;
        $this->glow_radius = isset($layout['glow_radius']) ? (int) $layout['glow_radius'] : 0;
        $this->shadow_offset = isset($layout['shadow_offset']) ? (int) $layout['shadow_offset'] : 0;

        $glow_hex = isset($layout['glow_color']) ? ltrim($layout['glow_color'], '#') : 'ffffff';
        $this->glow_color = [hexdec(substr($glow_hex, 0, 2)), hexdec(substr($glow_hex, 2, 2)), hexdec(substr($glow_hex, 4, 2))];

        $this->stroke_width = isset($layout['stroke_width']) ? (int) $layout['stroke_width'] : 0;
        $stroke_hex = isset($layout['stroke_color']) ? ltrim($layout['stroke_color'], '#') : '000000';
        $this->stroke_color_rgb = [hexdec(substr($stroke_hex, 0, 2)), hexdec(substr($stroke_hex, 2, 2)), hexdec(substr($stroke_hex, 4, 2))];

        $summary = isset($texts['summary']) ? $texts['summary'] : '';
        $date = isset($texts['date']) ? $texts['date'] : '';
        $time = isset($texts['time']) ? $texts['time'] : '';
        $location = isset($texts['location']) ? $texts['location'] : '';

        // Start entire text block at Vertical Position
        $this->y = $summary_margin_top;

        // Title header: "Glenn Bennett" / "Performs" in Aladdin font
        $font_title = $font_dir . 'Aladin-Regular.ttf';
        $title_font_size = isset($layout['title_font_size']) ? (int) $layout['title_font_size'] : 72;
        $subtitle_font_size = isset($layout['subtitle_font_size']) ? (int) $layout['subtitle_font_size'] : 48;

        $this->print_line(['text' => 'Glenn Bennett', 'font' => $font_title, 'font_size' => $title_font_size], $text_offset, $text_color);
        $this->print_line(['text' => 'Performs', 'font' => $font_title, 'font_size' => $subtitle_font_size], $text_offset, $text_color);
        $this->y += 30;

        // Build main text entries
        $msg_text_main = [
            ['text' => $summary, 'font' => $font_bold, 'font_size' => $summary_font_size],
            ['text' => $date,    'font' => $font_bold, 'font_size' => $date_font_size],
            ['text' => ' ',      'font' => $font_bold, 'font_size' => 18],
            ['text' => $time,    'font' => $font_bold, 'font_size' => $time_font_size],
        ];

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
                    $this->print_line($msg_copy1, $text_offset, $text_color);

                    $msg_copy2 = $msg;
                    $msg_copy2['text'] = $second_half;
                    $this->print_line($msg_copy2, $text_offset, $text_color);
                } else {
                    $this->print_line($msg, $text_offset, $text_color);
                }
            } else {
                $this->print_line($msg, $text_offset, $text_color);
            }
        }

        $this->y += 20;

        // Location
        if ($location) {
            $msg_text = [
                'text'      => $location,
                'font'      => $font_bold,
                'font_size' => $location_font_size
            ];
            $this->print_line($msg_text, $text_offset, $text_color);
        }

        // Footer: website URL
        $this->y += 20;
        $this->print_line(['text' => 'Keep up to date: GlennBennett.com/cal', 'font' => $font_bold, 'font_size' => 22], $text_offset, $text_color);
    }

    /**
     * Render an "event has passed" image using stored event data
     */
    public function render_expired($bg_file, $photo_file, $texts, $layout, $font_dir)
    {
        // Reuse template rendering for the background + photo composite
        $this->im = @imagecreatefromjpeg($bg_file);
        if (!$this->im) {
            $this->im = @imagecreatefrompng($bg_file);
        }
        if (!$this->im) {
            return false;
        }

        $this->image_width = imagesx($this->im);
        $this->image_height = imagesy($this->im);

        imagealphablending($this->im, true);

        // Load photo PNG
        $photo = @imagecreatefrompng($photo_file);
        if ($photo) {
            imagealphablending($photo, true);
            imagesavealpha($photo, true);

            $photo_w = imagesx($photo);
            $photo_h = imagesy($photo);

            $scale = isset($layout['photo_scale']) ? (int) $layout['photo_scale'] : 100;
            $new_w = (int) ($photo_w * $scale / 100);
            $new_h = (int) ($photo_h * $scale / 100);

            $photo_x = isset($layout['photo_x']) ? (int) $layout['photo_x'] : 0;
            $photo_y = isset($layout['photo_y']) ? (int) $layout['photo_y'] : 0;

            imagecopyresampled($this->im, $photo, $photo_x, $photo_y, 0, 0, $new_w, $new_h, $photo_w, $photo_h);
            imagedestroy($photo);
        }

        // Set up text rendering properties from layout
        $hex = isset($layout['font_color']) ? ltrim($layout['font_color'], '#') : '000000';
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $text_color = imagecolorallocate($this->im, $r, $g, $b);

        $text_offset = isset($layout['text_offset']) ? (int) $layout['text_offset'] : -200;
        $this->glow_radius = isset($layout['glow_radius']) ? (int) $layout['glow_radius'] : 0;
        $this->shadow_offset = isset($layout['shadow_offset']) ? (int) $layout['shadow_offset'] : 0;

        $glow_hex = isset($layout['glow_color']) ? ltrim($layout['glow_color'], '#') : 'ffffff';
        $this->glow_color = [hexdec(substr($glow_hex, 0, 2)), hexdec(substr($glow_hex, 2, 2)), hexdec(substr($glow_hex, 4, 2))];

        $this->stroke_width = isset($layout['stroke_width']) ? (int) $layout['stroke_width'] : 0;
        $stroke_hex = isset($layout['stroke_color']) ? ltrim($layout['stroke_color'], '#') : '000000';
        $this->stroke_color_rgb = [hexdec(substr($stroke_hex, 0, 2)), hexdec(substr($stroke_hex, 2, 2)), hexdec(substr($stroke_hex, 4, 2))];

        $font_bold = $font_dir . 'GEORGIAB.TTF';
        $font_title = $font_dir . 'Aladin-Regular.ttf';

        $summary_font_size = isset($layout['summary_font_size']) ? (int) $layout['summary_font_size'] : 36;

        // Start text block
        $this->y = isset($layout['summary_margin_top']) ? (int) $layout['summary_margin_top'] : 260;

        // "Glenn Bennett" / "Performs" header
        $this->print_line(['text' => 'Glenn Bennett', 'font' => $font_title, 'font_size' => 72], $text_offset, $text_color);
        $this->print_line(['text' => 'Performs', 'font' => $font_title, 'font_size' => 48], $text_offset, $text_color);
        $this->y += 30;

        // Event summary
        $this->print_line(['text' => $texts['summary'], 'font' => $font_bold, 'font_size' => $summary_font_size], $text_offset, $text_color);

        // Event date
        $this->print_line(['text' => $texts['date'], 'font' => $font_bold, 'font_size' => 24], $text_offset, $text_color);
        $this->y += 30;

        // "This event has passed" message
        $this->print_line(['text' => 'This event has passed', 'font' => $font_bold, 'font_size' => 32], $text_offset, $text_color);
        $this->y += 30;

        // Footer
        $this->print_line(['text' => 'Keep up to date: GlennBennett.com/cal', 'font' => $font_bold, 'font_size' => 22], $text_offset, $text_color);

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

        // Glow behind text
        if ($this->glow_radius > 0) {
            $gr = $this->glow_radius;
            for ($r = $gr; $r >= 1; $r--) {
                $alpha = 90 + (int)(($r / $gr) * 30);
                $glow = imagecolorallocatealpha($this->im, $this->glow_color[0], $this->glow_color[1], $this->glow_color[2], $alpha);
                for ($ox = -$r; $ox <= $r; $ox += max(1, $r - 1)) {
                    for ($oy = -$r; $oy <= $r; $oy += max(1, $r - 1)) {
                        imagettftext($this->im, $font_size, 0, $this->x + $ox, $this->y + $oy, $glow, $font, $text);
                    }
                }
            }
        }

        // Drop shadow
        if ($this->shadow_offset > 0) {
            $shadow = imagecolorallocatealpha($this->im, 0, 0, 0, 80);
            imagettftext($this->im, $font_size, 0, $this->x + $this->shadow_offset, $this->y + $this->shadow_offset, $shadow, $font, $text);
        }

        // Stroke outline
        if ($this->stroke_width > 0) {
            $stroke = imagecolorallocate($this->im, $this->stroke_color_rgb[0], $this->stroke_color_rgb[1], $this->stroke_color_rgb[2]);
            $sw = $this->stroke_width;
            for ($ox = -$sw; $ox <= $sw; $ox++) {
                for ($oy = -$sw; $oy <= $sw; $oy++) {
                    if ($ox == 0 && $oy == 0) continue;
                    imagettftext($this->im, $font_size, 0, $this->x + $ox, $this->y + $oy, $stroke, $font, $text);
                }
            }
        }

        // Sharp text on top
        imagettftext($this->im, $font_size, 0, $this->x, $this->y, $color, $font, $text);
    }
}
