<?php
// Convert photo_scale from photo-relative to canvas-relative (% of 630px canvas height)
// This makes scale values consistent regardless of photo native dimensions.

return array(
    'up' => function($ci) {
        // Get all photos with their heights
        $photos = $ci->db->get('template_photos')->result();

        foreach ($photos as $photo) {
            if ($photo->height <= 0) continue;

            // Convert photo default scale
            $new_scale = round($photo->photo_scale * $photo->height / 630);
            $ci->db->where('id', $photo->id)
                   ->update('template_photos', array('photo_scale' => $new_scale));

            // Convert all templates using this photo
            $ci->db->where('photo_id', $photo->id)
                   ->where('photo_scale >', 0)
                   ->set('photo_scale', "ROUND(photo_scale * {$photo->height} / 630)", FALSE)
                   ->update('templates');
        }
    },
    'down' => function($ci) {
        // Reverse: canvas-relative back to photo-relative
        $photos = $ci->db->get('template_photos')->result();

        foreach ($photos as $photo) {
            if ($photo->height <= 0) continue;

            $new_scale = round($photo->photo_scale * 630 / $photo->height);
            $ci->db->where('id', $photo->id)
                   ->update('template_photos', array('photo_scale' => $new_scale));

            $ci->db->where('photo_id', $photo->id)
                   ->where('photo_scale >', 0)
                   ->set('photo_scale', "ROUND(photo_scale * 630 / {$photo->height})", FALSE)
                   ->update('templates');
        }
    },
);
