<?php
date_default_timezone_set('America/Los_Angeles');

// Get the test script directory path
$script_dir = __DIR__;
$gcal_dir = dirname($script_dir) . '/gcal';

// Check that gcal directory exists
$gcal_dir_exists = is_dir($gcal_dir);

// Config path should be in gcal directory
$config_path = $gcal_dir . '/fb_image_config.php';
$config_exists = file_exists($config_path);

// Image and font directories are relative to gcal
$image_dir = dirname($gcal_dir) . '/imgs';
$fonts_dir = dirname($gcal_dir) . '/fonts';

// Check directories exist
$image_dir_exists = is_dir($image_dir);
$fonts_dir_exists = is_dir($fonts_dir);

// FB image path should be relative to gcal directory
$fb_image_path = '../gcal/fb_image.php';

// Debug paths array for display
$paths = [
    'Script Directory' => $script_dir,
    'GCAL Directory' => $gcal_dir,
    'Config File' => $config_path,
    'Images Directory' => $image_dir,
    'Fonts Directory' => $fonts_dir,
    'FB Image Path' => $fb_image_path
];

// Sample events for variety
$events = [
    [
        'summary' => 'Santa Paula Farmers Market',
        'location' => '801 E Main St, Santa Paula, CA 93060'
    ],
    [
        'summary' => 'Simi Valley Farmers Market',
        'location' => '2929 Tapo Canyon Road, Simi Valley, CA 93063'
    ],
    [
        'summary' => 'Coffee A La Mode',
        'location' => '4227 Tierra Rejada Rd, Moorpark, CA 93021'
    ],
    [
        'summary' => 'Moorpark Beer Festival',
        'location' => '699 Moorpark Ave, Moorpark, CA 93021'
    ],
    [
        'summary' => 'High Street Arts Center',
        'location' => '45 E High St, Moorpark, CA 93021'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Image Test</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            padding: 20px 0;
        }
        .fb-preview {
            background: white;
            border: 1px solid #ddd;
            margin-bottom: 30px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0,0,0,0.2);
            max-width: 500px !important;
            margin-left: auto;
            margin-right: auto;
        }
        .fb-preview .card-header {
            background-color: #f5f6f7;
            border-bottom: 1px solid #ddd;
            padding: 12px;
            font-size: 14px;
        }
        .fb-image-wrapper {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }
        .fb-image-container {
            position: relative;
            width: 100%;
            padding-top: 52.5%;
            overflow: hidden;
            background: #f0f2f5;
        }
        .fb-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }
        .fb-meta, .debug-info {
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            padding: 12px 16px;
        }
        .fb-meta {
            border-top: 1px solid #ddd;
        }
        .fb-meta h5 {
            margin-bottom: 4px;
            color: #1c1e21;
            font-weight: 600;
            font-size: 16px;
        }
        .fb-meta p {
            color: #606770;
            margin-bottom: 4px;
            font-size: 14px;
            line-height: 1.2857;
        }
        .debug-info {
            background: #f5f6f7;
            border-top: 1px solid #ddd;
            font-family: monospace;
            font-size: 12px;
        }
        .preview-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 15px;
        }
        .col-md-6, .col-xl-4 {
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="preview-container">
            <div class="alert alert-info mb-4">
                <h4 class="alert-heading mb-2">Path Information</h4>
                <div class="small">
                    <?php foreach($paths as $label => $path): ?>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong><?php echo $label; ?>:</strong>
                        <span><?php echo htmlspecialchars($path); ?></span>
                        <span class="badge <?php echo file_exists($path) ? 'badge-success' : 'badge-danger'; ?>">
                            <?php echo file_exists($path) ? '?' : '?'; ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <h2 class="mb-4">Event Image Test - Background Images 0-24</h2>
            
            <div class="row">
                <?php 
                // Set base date for all events but vary the times
                $base_date = strtotime('2024-01-25 19:00:00');
                
                for ($i = 0; $i < 25; $i++): 
                    // Cycle through events and vary the times
                    $event = $events[$i % count($events)];
                    $event['start_date'] = strtotime(sprintf('+%d hours', $i * 2), $base_date);
                    $event['end_date'] = strtotime('+3 hours', $event['start_date']);
                    
                    $image_filename = 'Cal-Event-' . $i . '.jpg';
                    $image_path = $image_dir . '/' . $image_filename;
                    $image_exists = file_exists($image_path);
                ?>
                <div class="col-md-6 col-xl-4">
                    <div class="fb-preview">
                        <div class="card-header">
                            <strong>Background Image <?php echo $i; ?></strong>
                        </div>
                        <div class="fb-image-container">
                            <img src="<?php echo $fb_image_path; ?>?start_date=<?php echo $event['start_date']; ?>&end_date=<?php echo $event['end_date']; ?>&summary=<?php echo urlencode($event['summary']); ?>&location=<?php echo urlencode($event['location']); ?>&image_no=<?php echo $i; ?>" 
                                 class="fb-image" 
                                 alt="Event Image <?php echo $i; ?>">
                        </div>
                        <div class="fb-meta">
                            <h5><?php echo htmlspecialchars($event['summary']); ?></h5>
                            <p><?php echo date('l, F j, Y', $event['start_date']); ?> at <?php echo date('g:i A', $event['start_date']); ?></p>
                            <p><?php echo htmlspecialchars($event['location']); ?></p>
                        </div>
                        <div class="debug-info">
                            <pre style="margin-bottom: 8px;"><?php
                            echo "Image Info:\n";
                            echo "? File: {$image_filename}\n";
                            echo "? Status: " . ($image_exists ? '? Found' : '? Missing') . "\n";
                            echo "? Path: " . $image_path . "\n\n";

                            echo "Text Settings:\n";
                            echo "? Summary: 36px Georgia Bold\n";
                            echo "? Date: 24px Georgia Bold\n";
                            echo "? Time: 36px Georgia Bold\n";
                            echo "? Location: 24px Georgia Regular\n\n";

                            echo "Positioning:\n";
                            echo "? Y Start: 260px\n";
                            echo "? X Offset: -200px from center";
                            ?></pre>
                        </div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
