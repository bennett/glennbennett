<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Music_model
 * 
 * Reads songs and albums from the Bennett Music Player SQLite database.
 * This allows GlennBennett.com to display the same music library.
 */
class Music_model extends CI_Model {
    
    private $db_music = null;
    private $cdn_url = '';
    private $cover_url = '';
    private $music_path = '';
    
    public function __construct() {
        parent::__construct();
        $this->load->config('music_db');
        
        $this->cdn_url = rtrim($this->config->item('music_cdn_url'), '/');
        $this->cover_url = rtrim($this->config->item('cover_art_url'), '/');
        $this->music_path = rtrim($this->config->item('music_origin_path'), '/');
        
        // Connect to the music SQLite database
        $db_path = $this->config->item('music_db_path');
        if (file_exists($db_path)) {
            $this->db_music = new SQLite3($db_path, SQLITE3_OPEN_READONLY);
        }
    }
    
    /**
     * Get all original songs (for homepage display)
     * Returns songs in format compatible with existing views
     */
    public function get_originals() {
        if (!$this->db_music) return [];
        
        $songs = [];
        
        // Get all songs with their album info
        $sql = "SELECT s.*, a.title as album_title, a.cover_filename as album_cover
                FROM songs s
                LEFT JOIN album_songs als ON als.song_id = s.id
                LEFT JOIN albums a ON a.id = als.album_id
                ORDER BY s.title ASC";
        
        $result = $this->db_music->query($sql);
        
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $songs[] = $this->format_song($row);
        }
        
        return $songs;
    }
    
    /**
     * Get a single song by ID
     */
    public function get_song($song_id) {
        if (!$this->db_music) return null;
        
        $stmt = $this->db_music->prepare("
            SELECT s.*, a.title as album_title, a.cover_filename as album_cover
            FROM songs s
            LEFT JOIN album_songs als ON als.song_id = s.id
            LEFT JOIN albums a ON a.id = als.album_id
            WHERE s.id = :id
            LIMIT 1
        ");
        $stmt->bindValue(':id', $song_id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        
        $row = $result->fetchArray(SQLITE3_ASSOC);
        if ($row) {
            return $this->format_song($row);
        }
        return null;
    }
    
    /**
     * Get featured song (if any)
     */
    public function get_featured() {
        if (!$this->db_music) return null;
        
        $result = @$this->db_music->query("
            SELECT s.*, a.title as album_title, a.cover_filename as album_cover
            FROM songs s
            LEFT JOIN album_songs als ON als.song_id = s.id
            LEFT JOIN albums a ON a.id = als.album_id
            WHERE s.featured = 1
            LIMIT 1
        ");

        if (!$result) return null;
        $row = $result->fetchArray(SQLITE3_ASSOC);
        if ($row) {
            return $this->format_song($row);
        }
        return null;
    }
    
    /**
     * Get all albums
     */
    public function get_albums() {
        if (!$this->db_music) return [];
        
        $albums = [];
        $result = $this->db_music->query("
            SELECT * FROM albums ORDER BY year DESC, title ASC
        ");
        
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $album = new stdClass();
            $album->id = $row['id'];
            $album->title = $row['title'];
            $album->artist = $row['artist'] ?? 'Glenn Bennett';
            $album->year = $row['year'];
            $album->cover_url = $this->get_album_cover_url($row['cover_filename'], $row['title']);
            $albums[] = $album;
        }
        
        return $albums;
    }
    
    /**
     * Format a database row into the song object format expected by views
     */
    private function format_song($row) {
        $song = new stdClass();
        $song->id = $row['id'];
        $song->title = $row['title'];
        $song->artist = $row['artist'] ?? 'Glenn Bennett';
        $song->cover = 0; // Treat all as originals
        $song->featured = $row['featured'] ?? 0;
        
        // Get cover art URL
        $song->art = $this->get_song_cover_url($row);
        
        // Create featured_track object (for compatibility with existing views)
        $song->featured_track = new stdClass();
        $song->featured_track->audio_file = $this->get_stream_url($row['filename'], $row['file_hash'] ?? null);
        $song->featured_track->duration = $this->format_duration($row['duration'] ?? 0);
        $song->featured_track->blurb = $row['blurb'] ?? '';
        
        return $song;
    }
    
    /**
     * Get streaming URL for a song
     */
    private function get_stream_url($filename, $file_hash = null) {
        $url = $this->cdn_url . '/' . ltrim($filename, '/');
        if ($file_hash) {
            $url .= '?h=' . substr($file_hash, 0, 10);
        }
        return $url;
    }
    
    /**
     * Get cover art URL for a song
     */
    private function get_song_cover_url($row) {
        // First try song's own cover
        if (!empty($row['cover_filename'])) {
            $hash = !empty($row['file_hash']) ? '?h=' . substr($row['file_hash'], 0, 8) : '';
            return $this->cover_url . '/' . $row['cover_filename'] . $hash;
        }
        
        // Then try album cover
        if (!empty($row['album_title'])) {
            $album_cover = $this->get_album_cover_url($row['album_cover'] ?? null, $row['album_title']);
            if ($album_cover) {
                return $album_cover;
            }
        }
        
        // Default cover
        return $this->cover_url . '/albums/default.jpg';
    }
    
    /**
     * Get album cover URL - checks imgs/albums/ directory first
     */
    private function get_album_cover_url($cover_filename, $album_title = null) {
        // First check imgs/albums/ directory for a matching file
        if ($album_title) {
            $imgs_dir = $this->music_path . '/imgs/albums';
            if (is_dir($imgs_dir)) {
                $album_normalized = strtolower(str_replace(['_', ' ', '-'], '', $album_title));
                $exts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                
                $files = @scandir($imgs_dir);
                if ($files) {
                    foreach ($files as $file) {
                        if ($file === '.' || $file === '..') continue;
                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        if (!in_array($ext, $exts)) continue;
                        
                        $file_basename = pathinfo($file, PATHINFO_FILENAME);
                        $file_normalized = strtolower(str_replace(['_', ' ', '-'], '', $file_basename));
                        
                        if ($file_normalized === $album_normalized) {
                            return $this->cdn_url . '/imgs/albums/' . $file;
                        }
                    }
                }
            }
        }
        
        // Fall back to database cover filename
        if ($cover_filename) {
            return $this->cover_url . '/' . $cover_filename;
        }
        
        return null;
    }
    
    /**
     * Format seconds to MM:SS
     */
    private function format_duration($seconds) {
        $mins = floor($seconds / 60);
        $secs = $seconds % 60;
        return sprintf('%02d:%02d', $mins, $secs);
    }
    
    public function __destruct() {
        if ($this->db_music) {
            $this->db_music->close();
        }
    }
}
