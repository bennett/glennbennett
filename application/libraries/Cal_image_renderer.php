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

            // Apply image adjustments to photo
            $photo = $this->apply_photo_adjustments($photo, $layout);

            $photo_w = imagesx($photo);
            $photo_h = imagesy($photo);

            // Scale relative to canvas height so % is consistent across all photos
            $scale = isset($layout['photo_scale']) ? (int) $layout['photo_scale'] : 100;
            $new_h = (int) ($this->image_height * $scale / 100);
            $new_w = (int) ($new_h * ($photo_w / $photo_h));

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

        // Print summary with long-text wrapping
        $this->_print_wrapped($summary, $font_bold, $summary_font_size, $text_offset, $text_color);

        // Date
        $this->y += $date_margin_top;
        $this->_print_wrapped($date, $font_bold, $date_font_size, $text_offset, $text_color);

        // Time
        $this->y += $time_margin_top;
        $this->_print_wrapped($time, $font_bold, $time_font_size, $text_offset, $text_color);

        // Location
        $this->y += $location_margin_top;
        if ($location) {
            $this->_print_wrapped($location, $font_bold, $location_font_size, $text_offset, $text_color);
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

            $photo = $this->apply_photo_adjustments($photo, $layout);

            $photo_w = imagesx($photo);
            $photo_h = imagesy($photo);

            // Scale relative to canvas height so % is consistent across all photos
            $scale = isset($layout['photo_scale']) ? (int) $layout['photo_scale'] : 100;
            $new_h = (int) ($this->image_height * $scale / 100);
            $new_w = (int) ($new_h * ($photo_w / $photo_h));

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

    /**
     * Apply image adjustments (brightness, contrast, etc.) to a photo resource
     */
    private function apply_photo_adjustments($photo, $layout)
    {
        $w = imagesx($photo);
        $h = imagesy($photo);

        // Grayscale
        if ( ! empty($layout['grayscale'])) {
            imagefilter($photo, IMG_FILTER_GRAYSCALE);
        }

        // Sepia (grayscale + colorize)
        if ( ! empty($layout['sepia'])) {
            imagefilter($photo, IMG_FILTER_GRAYSCALE);
            imagefilter($photo, IMG_FILTER_COLORIZE, 90, 60, 30);
        }

        // Brightness (-100 to 100 in layout, maps to -255 to 255 for GD)
        $brightness = isset($layout['brightness']) ? (int) $layout['brightness'] : 0;
        if ($brightness !== 0) {
            imagefilter($photo, IMG_FILTER_BRIGHTNESS, (int) ($brightness * 2.55));
        }

        // Contrast (-100 to 100 in layout; GD uses inverted scale: -100=max contrast, 100=min)
        $contrast = isset($layout['contrast']) ? (int) $layout['contrast'] : 0;
        if ($contrast !== 0) {
            imagefilter($photo, IMG_FILTER_CONTRAST, -$contrast);
        }

        // Saturation (simulate via colorize toward gray)
        $saturation = isset($layout['saturation']) ? (int) $layout['saturation'] : 0;
        if ($saturation < 0) {
            // Desaturate: blend with grayscale version
            $gray = imagecreatetruecolor($w, $h);
            imagealphablending($gray, false);
            imagesavealpha($gray, true);
            imagecopy($gray, $photo, 0, 0, 0, 0, $w, $h);
            imagefilter($gray, IMG_FILTER_GRAYSCALE);
            $amount = abs($saturation) / 100;
            imagecopymerge($photo, $gray, 0, 0, 0, 0, $w, $h, (int) ($amount * 100));
            imagedestroy($gray);
        } elseif ($saturation > 0) {
            // Boost: increase color intensity
            imagefilter($photo, IMG_FILTER_COLORIZE, 0, 0, 0);
            // Multiple small contrast bumps to simulate saturation boost
            $steps = min(3, (int) ($saturation / 25));
            for ($i = 0; $i < $steps; $i++) {
                imagefilter($photo, IMG_FILTER_CONTRAST, -10);
            }
        }

        // Sharpen
        $sharpen = isset($layout['sharpen']) ? (int) $layout['sharpen'] : 0;
        if ($sharpen > 0) {
            $amount = min($sharpen, 5);
            for ($i = 0; $i < $amount; $i++) {
                imagefilter($photo, IMG_FILTER_MEAN_REMOVAL);
            }
        }

        // Blur
        $blur = isset($layout['blur']) ? (int) $layout['blur'] : 0;
        if ($blur > 0) {
            $passes = min($blur, 10);
            for ($i = 0; $i < $passes; $i++) {
                imagefilter($photo, IMG_FILTER_GAUSSIAN_BLUR);
            }
        }

        // Hue rotation (pixel-level HSL manipulation)
        $hue_rotate = isset($layout['hue_rotate']) ? (int) $layout['hue_rotate'] : 0;
        if ($hue_rotate !== 0) {
            $photo = $this->rotate_hue($photo, $hue_rotate);
        }

        // Tint (colorize with specified color and amount)
        $tint_amount = isset($layout['tint_amount']) ? (int) $layout['tint_amount'] : 0;
        $tint_color = isset($layout['tint_color']) ? $layout['tint_color'] : null;
        if ($tint_amount > 0 && $tint_color) {
            $hex = ltrim($tint_color, '#');
            $tr = hexdec(substr($hex, 0, 2));
            $tg = hexdec(substr($hex, 2, 2));
            $tb = hexdec(substr($hex, 4, 2));
            // Scale colorize effect by tint_amount (0-100)
            $scale = $tint_amount / 100;
            imagefilter($photo, IMG_FILTER_COLORIZE,
                (int) (($tr - 128) * $scale),
                (int) (($tg - 128) * $scale),
                (int) (($tb - 128) * $scale)
            );
        }

        // Opacity (apply to alpha channel)
        $opacity = isset($layout['opacity']) ? (int) $layout['opacity'] : 100;
        if ($opacity < 100 && $opacity >= 0) {
            for ($px = 0; $px < $w; $px++) {
                for ($py = 0; $py < $h; $py++) {
                    $rgba = imagecolorat($photo, $px, $py);
                    $a = ($rgba >> 24) & 0x7F;
                    if ($a < 127) {
                        $new_a = min(127, (int) ($a + (127 - $a) * (1 - $opacity / 100)));
                        $c = imagecolorallocatealpha($photo,
                            ($rgba >> 16) & 0xFF,
                            ($rgba >> 8) & 0xFF,
                            $rgba & 0xFF,
                            $new_a
                        );
                        imagesetpixel($photo, $px, $py, $c);
                    }
                }
            }
        }

        return $photo;
    }

    /**
     * Rotate hue of an image by degrees
     */
    private function rotate_hue($img, $degrees)
    {
        $w = imagesx($img);
        $h = imagesy($img);

        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgba = imagecolorat($img, $x, $y);
                $a = ($rgba >> 24) & 0x7F;
                $r = ($rgba >> 16) & 0xFF;
                $g = ($rgba >> 8) & 0xFF;
                $b = $rgba & 0xFF;

                // RGB to HSL
                $r1 = $r / 255; $g1 = $g / 255; $b1 = $b / 255;
                $max = max($r1, $g1, $b1);
                $min = min($r1, $g1, $b1);
                $l = ($max + $min) / 2;

                if ($max == $min) {
                    $h2 = $s = 0;
                } else {
                    $d = $max - $min;
                    $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
                    if ($max == $r1) $h2 = ($g1 - $b1) / $d + ($g1 < $b1 ? 6 : 0);
                    elseif ($max == $g1) $h2 = ($b1 - $r1) / $d + 2;
                    else $h2 = ($r1 - $g1) / $d + 4;
                    $h2 /= 6;
                }

                // Rotate hue
                $h2 = fmod($h2 + $degrees / 360, 1.0);
                if ($h2 < 0) $h2 += 1;

                // HSL to RGB
                if ($s == 0) {
                    $r2 = $g2 = $b2 = $l;
                } else {
                    $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
                    $p = 2 * $l - $q;
                    $r2 = $this->hue_to_rgb($p, $q, $h2 + 1/3);
                    $g2 = $this->hue_to_rgb($p, $q, $h2);
                    $b2 = $this->hue_to_rgb($p, $q, $h2 - 1/3);
                }

                $c = imagecolorallocatealpha($img,
                    (int) round($r2 * 255),
                    (int) round($g2 * 255),
                    (int) round($b2 * 255),
                    $a
                );
                imagesetpixel($img, $x, $y, $c);
            }
        }

        return $img;
    }

    private function hue_to_rgb($p, $q, $t)
    {
        if ($t < 0) $t += 1;
        if ($t > 1) $t -= 1;
        if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
        if ($t < 1/2) return $q;
        if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;
        return $p;
    }

    /**
     * Print text with automatic wrapping for long strings (> 30 chars)
     */
    private function _print_wrapped($text, $font, $font_size, $offset, $color)
    {
        $msg = ['text' => $text, 'font' => $font, 'font_size' => $font_size];

        if (strlen($text) > 30) {
            $middle = strlen($text) / 2;
            $spaces = [];
            for ($i = 0; $i < strlen($text); $i++) {
                if ($text[$i] == ' ') $spaces[] = $i;
            }
            if (!empty($spaces)) {
                $best_space = $spaces[0];
                foreach ($spaces as $sp) {
                    if (abs($sp - $middle) < abs($best_space - $middle)) $best_space = $sp;
                }
                $this->print_line(['text' => substr($text, 0, $best_space), 'font' => $font, 'font_size' => $font_size], $offset, $color);
                $this->print_line(['text' => substr($text, $best_space + 1), 'font' => $font, 'font_size' => $font_size], $offset, $color);
                return;
            }
        }
        $this->print_line($msg, $offset, $color);
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
