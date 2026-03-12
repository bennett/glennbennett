<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Music Player Database & CDN Configuration
|--------------------------------------------------------------------------
| 
| This config points to the SQLite database used by the Bennett Music Player
| and the CDN URLs for streaming audio and cover art.
|
*/

// Path to the SQLite database file (same one used by music.glennbennett.com)
$config['music_db_path'] = '/home/tsgimh/music.glennbennett.com/database/music.db';

// CDN URL for streaming audio files
$config['music_cdn_url'] = 'https://glb-songs.b-cdn.net/songs';

// CDN URL for cover art images
$config['cover_art_url'] = 'https://glb-songs.b-cdn.net/songs/imgs';

// Local path to music files (for scanning cover art in imgs/albums/)
$config['music_origin_path'] = '/home/tsgimh/glennbennett.com/songs';
