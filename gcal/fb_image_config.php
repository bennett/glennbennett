<?php
/**
 * FB Image Generator Configuration
 * Complete configuration for all 25 background images
 */

// Global Settings
$config['no_of_bg_images'] = 25;
$config['bg_image_path'] = '../imgs/Cal-Event-';
$config['bg_image_ext'] = '.jpg';
$config['title_font'] = '../fonts/GEORGIAB.TTF';
$config['subtitle_font'] = '../fonts/GEORGIA.TTF';

// Image Layouts
$config['image_layouts'] = [
    // Image 0: Right-aligned, large text
    0 => [
        'main_text_layout' => [
            [
                'type'       => 'summary',
                'font_size'  => 42,
                'margin_top' => 180
            ],
            [
                'type'       => 'date',
                'font_size'  => 32,
                'margin_top' => 30
            ],
            [
                'type'       => 'time',
                'font_size'  => 38,
                'margin_top' => 25
            ]
        ],
        'location_text' => [
            'font_size'  => 28,
            'margin_top' => 30
        ],
        'text_offset' => 300  // Offset to right
    ],
    
    // Image 1: Left-aligned, dramatic spacing
    1 => [
        'main_text_layout' => [
            [
                'type'       => 'summary',
                'font_size'  => 48,
                'margin_top' => 150
            ],
            [
                'type'       => 'date',
                'font_size'  => 36,
                'margin_top' => 40
            ],
            [
                'type'       => 'time',
                'font_size'  => 42,
                'margin_top' => 30
            ]
        ],
        'location_text' => [
            'font_size'  => 32,
            'margin_top' => 40
        ],
        'text_offset' => -350  // Offset to left
    ],
    
    // Image 2: Top-aligned, compact
    2 => [
        'main_text_layout' => [
            [
                'type'       => 'summary',
                'font_size'  => 36,
                'margin_top' => 120
            ],
            [
                'type'       => 'date',
                'font_size'  => 28,
                'margin_top' => 20
            ],
            [
                'type'       => 'time',
                'font_size'  => 32,
                'margin_top' => 20
            ]
        ],
        'location_text' => [
            'font_size'  => 24,
            'margin_top' => 25
        ],
        'text_offset' => -200
    ],
    
    // Image 3: Bottom-heavy layout
    3 => [
        'main_text_layout' => [
            [
                'type'       => 'summary',
                'font_size'  => 40,
                'margin_top' => 380
            ],
            [
                'type'       => 'date',
                'font_size'  => 30,
                'margin_top' => 25
            ],
            [
                'type'       => 'time',
                'font_size'  => 34,
                'margin_top' => 20
            ]
        ],
        'location_text' => [
            'font_size'  => 26,
            'margin_top' => 25
        ],
        'text_offset' => -180
    ],
    
    // Image 4: Centered, large format
    4 => [
        'main_text_layout' => [
            [
                'type'       => 'summary',
                'font_size'  => 46,
                'margin_top' => 200
            ],
            [
                'type'       => 'date',
                'font_size'  => 34,
                'margin_top' => 35
            ],
            [
                'type'       => 'time',
                'font_size'  => 38,
                'margin_top' => 30
            ]
        ],
        'location_text' => [
            'font_size'  => 30,
            'margin_top' => 35
        ],
        'text_offset' => 0  // Centered
    ],
    
    // Image 5: Top-right layout
    5 => [
        'main_text_layout' => [
            [
                'type'       => 'summary',
                'font_size'  => 38,
                'margin_top' => 140
            ],
            [
                'type'       => 'date',
                'font_size'  => 30,
                'margin_top' => 25
            ],
            [
                'type'       => 'time',
                'font_size'  => 34,
                'margin_top' => 20
            ]
        ],
        'location_text' => [
            'font_size'  => 26,
            'margin_top' => 30
        ],
        'text_offset' => 200
    ],
    
    // Image 6: Bottom-left, compact
    6 => [
        'main_text_layout' => [
            [
                'type'       => 'summary',
                'font_size'  => 36,
                'margin_top' => 400
            ],
            [
                'type'       => 'date',
                'font_size'  => 28,
                'margin_top' => 20
            ],
            [
                'type'       => 'time',
                'font_size'  => 32,
                'margin_top' => 20
            ]
        ],
        'location_text' => [
            'font_size'  => 24,
            'margin_top' => 25
        ],
        'text_offset' => -250
    ],
    
    // Image 7: Center-high layout
    7 => [
        'main_text_layout' => [
            [
                'type'       => 'summary',
                'font_size'  => 44,
                'margin_top' => 160
            ],
            [
                'type'       => 'date',
                'font_size'  => 32,
                'margin_top' => 30
            ],
            [
                'type'       => 'time',
                'font_size'  => 36,
                'margin_top' => 25
            ]
        ],
        'location_text' => [
            'font_size'  => 28,
            'margin_top' => 30
        ],
        'text_offset' => -100
    ],
    
    // Image 8: Mid-right layout
    8 => [
        'main_text_layout' => [
            [
                'type'       => 'summary',
                'font_size'  => 40,
                'margin_top' => 250
            ],
            [
                'type'       => 'date',
                'font_size'  => 30,
                'margin_top' => 25
            ],
            [
                'type'       => 'time',
                'font_size'  => 34,
                'margin_top' => 20
            ]
        ],
        'location_text' => [
            'font_size'  => 26,
            'margin_top' => 30
        ],
        'text_offset' => 180
    ],
    
    // Image 9: High-contrast layout
    9 => [
        'main_text_layout' => [
            [
                'type'       => 'summary',
                'font_size'  => 46,
                'margin_top' => 220
            ],
            [
                'type'       => 'date',
                'font_size'  => 36,
                'margin_top' => 35
            ],
            [
                'type'       => 'time',
                'font_size'  => 40,
                'margin_top' => 30
            ]
        ],
        'location_text' => [
            'font_size'  => 32,
            'margin_top' => 35
        ],
        'text_offset' => -150
    ],
    
    // Image 10: Balanced mid layout
    10 => [
        'main_text_layout' => [
            [
                'type'       => 'summary',
                'font_size'  => 42,
                'margin_top' => 230
            ],
            [
                'type'       => 'date',
                'font_size'  => 32,
                'margin_top' => 30
            ],
            [
                'type'       => 'time',
                'font_size'  => 36,
                'margin_top' => 25
            ]
        ],
        'location_text' => [
            'font_size'  => 28,
            'margin_top' => 30
        ],
        'text_offset' => 0
    ],
    
    // Continue with remaining images (11-24)
    // Using variations of the above layouts with different positions and sizes
    11 => [
        'main_text_layout' => [
            [
                'type'       => 'summary',
                'font_size'  => 38,
                'margin_top' => 280
            ],
            [
                'type'       => 'date',
                'font_size'  => 28,
                'margin_top' => 25
            ],
            [
                'type'       => 'time',
                'font_size'  => 32,
                'margin_top' => 20
            ]
        ],
        'location_text' => [
            'font_size'  => 24,
            'margin_top' => 25
        ],
        'text_offset' => -220
    ],
    
    // ... Continue with remaining images
];

// Fill in remaining layouts with variations
for ($i = 12; $i < 25; $i++) {
    // Calculate variations based on image number
    $margin_top = 180 + (($i % 5) * 40);  // Vary top margin
    $font_size = 36 + (($i % 3) * 4);     // Vary font size
    $text_offset = -200 + (($i % 7) * 50); // Vary text offset
    
    $config['image_layouts'][$i] = [
        'main_text_layout' => [
            [
                'type'       => 'summary',
                'font_size'  => $font_size,
                'margin_top' => $margin_top
            ],
            [
                'type'       => 'date',
                'font_size'  => max(24, $font_size - 8),
                'margin_top' => 25 + ($i % 3) * 5
            ],
            [
                'type'       => 'time',
                'font_size'  => max(28, $font_size - 4),
                'margin_top' => 20 + ($i % 3) * 5
            ]
        ],
        'location_text' => [
            'font_size'  => max(24, $font_size - 12),
            'margin_top' => 25 + ($i % 3) * 5
        ],
        'text_offset' => $text_offset
    ];
}

// Set default layout (used as fallback)
$config['default_layout'] = $config['image_layouts'][0];
?>
