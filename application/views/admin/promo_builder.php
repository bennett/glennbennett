<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include VIEWPATH . 'admin/includes/header.php'; ?>

<section class="content-header">
    <h1>AI Promo Builder <small>Package images and prompt for Banana</small></h1>
</section>

<section class="content">
    <form id="promoForm" action="<?php echo site_url('admin/promo/generate_zip') ?>" method="post">

        <!-- Panel 1: Event Details -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-calendar"></i> Event Details</h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label>Select from upcoming events</label>
                    <select id="eventSelect" class="form-control">
                        <option value="">-- Loading events... --</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Venue Name</label>
                            <input type="text" name="venue_name" id="venueName" class="form-control" placeholder="e.g. Borderline Brewing">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" name="event_date" id="eventDate" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Time</label>
                            <input type="text" name="event_time" id="eventTime" class="form-control" placeholder="e.g. 3 - 5 pm">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Location <span class="text-muted" style="font-weight: normal; font-size: 12px;">(street + city only for the promo)</span></label>
                    <input type="text" name="event_location" id="eventLocation" class="form-control" placeholder="e.g. 365 Santa Clara Ave, Fillmore">
                </div>
                <div class="form-group" id="descriptionGroup" style="display: none;">
                    <label>Event Description</label>
                    <div id="eventDescription" class="well well-sm" style="margin-bottom: 0; white-space: pre-line; max-height: 150px; overflow-y: auto; font-size: 13px;"></div>
                </div>
                <input type="hidden" id="eventDateDisplay" value="">
            </div>
        </div>

        <!-- Panel 2: Select Images -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-images"></i> Select Images</h3>
                <div class="box-tools">
                    <span id="selectedCount" class="label label-primary">0 selected</span>
                </div>
            </div>
            <div class="box-body">
                <?php
                $tabs = [
                    'artist'  => ['label' => 'Artist Photos', 'icon' => 'fa-user', 'images' => $artist_images],
                    'venue'   => ['label' => 'Venue Photos', 'icon' => 'fa-building', 'images' => $venue_images],
                    'generic' => ['label' => 'Generic / Theme', 'icon' => 'fa-palette', 'images' => $generic_images],
                ];
                $total_images = count($artist_images) + count($venue_images) + count($generic_images);
                ?>

                <?php if ($total_images === 0): ?>
                    <p class="text-muted">No images in the library. <a href="<?php echo site_url('admin/promo/images') ?>">Upload some first</a>.</p>
                <?php else: ?>
                    <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                        <?php $first = true; foreach ($tabs as $key => $tab): ?>
                            <li <?php echo $first ? 'class="active"' : '' ?>>
                                <a href="#tab-<?php echo $key ?>" data-toggle="tab">
                                    <i class="fa <?php echo $tab['icon'] ?>"></i>
                                    <?php echo $tab['label'] ?>
                                    <span class="badge"><?php echo count($tab['images']) ?></span>
                                </a>
                            </li>
                        <?php $first = false; endforeach; ?>
                    </ul>

                    <div class="tab-content">
                        <?php $first = true; foreach ($tabs as $key => $tab): ?>
                            <div class="tab-pane <?php echo $first ? 'active' : '' ?>" id="tab-<?php echo $key ?>">
                                <?php if (empty($tab['images'])): ?>
                                    <p class="text-muted">No <?php echo strtolower($tab['label']) ?> uploaded. <a href="<?php echo site_url('admin/promo/images') ?>">Upload some</a>.</p>
                                <?php else: ?>
                                    <div class="row">
                                        <?php foreach ($tab['images'] as $img): ?>
                                            <div class="col-md-2 col-sm-3 col-xs-4" style="margin-bottom: 15px;">
                                                <div class="promo-thumb" data-id="<?php echo $img->id ?>" style="cursor: pointer; border: 3px solid transparent; border-radius: 6px; padding: 3px; transition: all 0.15s; position: relative;">
                                                    <div style="background: #f5f5f5; height: 120px; display: flex; align-items: center; justify-content: center; border-radius: 4px; overflow: hidden;">
                                                        <img loading="lazy"
                                                             src="<?php echo base_url('imgs/promo/' . $img->filename) ?>"
                                                             alt="<?php echo htmlspecialchars($img->label ?: $img->original_name) ?>"
                                                             style="max-width: 100%; max-height: 120px;">
                                                    </div>
                                                    <div class="check-overlay" style="display: none; position: absolute; top: 8px; right: 8px; background: #3c8dbc; color: #fff; border-radius: 50%; width: 24px; height: 24px; text-align: center; line-height: 24px; font-size: 12px;">
                                                        <i class="fa fa-check"></i>
                                                    </div>
                                                    <p class="text-center text-muted" style="font-size: 11px; margin: 4px 0 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                        <?php echo htmlspecialchars($img->label ?: $img->original_name) ?>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php $first = false; endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Panel 3: Image Sizes -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-crop"></i> Image Sizes</h3>
            </div>
            <div class="box-body">
                <p class="text-muted" style="margin-bottom: 10px;">Select which sizes to generate. These are included in the prompt.</p>
                <div class="row">
                    <div class="col-md-4">
                        <label class="checkbox-inline" style="display: block; margin-bottom: 8px;">
                            <input type="checkbox" class="size-check" value="1200x630" checked> <strong>Facebook Post</strong> <span class="text-muted">(1200x630)</span>
                        </label>
                        <label class="checkbox-inline" style="display: block; margin-bottom: 8px;">
                            <input type="checkbox" class="size-check" value="1080x1080"> <strong>Instagram Square</strong> <span class="text-muted">(1080x1080)</span>
                        </label>
                        <label class="checkbox-inline" style="display: block; margin-bottom: 8px;">
                            <input type="checkbox" class="size-check" value="1080x1920"> <strong>Instagram Story</strong> <span class="text-muted">(1080x1920)</span>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label class="checkbox-inline" style="display: block; margin-bottom: 8px;">
                            <input type="checkbox" class="size-check" value="1500x500"> <strong>Twitter/X Header</strong> <span class="text-muted">(1500x500)</span>
                        </label>
                        <label class="checkbox-inline" style="display: block; margin-bottom: 8px;">
                            <input type="checkbox" class="size-check" value="1920x1080"> <strong>HD Landscape</strong> <span class="text-muted">(1920x1080)</span>
                        </label>
                        <label class="checkbox-inline" style="display: block; margin-bottom: 8px;">
                            <input type="checkbox" class="size-check" value="2550x3300"> <strong>Print Flyer</strong> <span class="text-muted">(8.5x11 @ 300dpi)</span>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label class="checkbox-inline" style="display: block; margin-bottom: 8px;">
                            <input type="checkbox" class="size-check" value="820x312"> <strong>Facebook Cover</strong> <span class="text-muted">(820x312)</span>
                        </label>
                        <label class="checkbox-inline" style="display: block; margin-bottom: 8px;">
                            <input type="checkbox" class="size-check" value="1080x1350"> <strong>Instagram Portrait</strong> <span class="text-muted">(1080x1350)</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel 4: Prompt -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-file-text"></i> Prompt</h3>
                <div class="box-tools">
                    <button type="button" class="btn btn-sm btn-default" id="resetPrompt" title="Reset to template"><i class="fa fa-refresh"></i> Reset</button>
                </div>
            </div>
            <div class="box-body">
                <textarea name="prompt" id="promptText" class="form-control" rows="14" style="font-family: monospace; font-size: 13px;"></textarea>
            </div>
        </div>

        <!-- Hidden image_ids inputs inserted by JS -->
        <div id="hiddenImageIds"></div>

        <button type="submit" class="btn btn-lg btn-primary" id="generateBtn" style="margin-bottom: 30px;">
            <i class="fa fa-download"></i> Download Zip for Banana
        </button>

    </form>
</section>

<style>
.promo-thumb.selected {
    border-color: #3c8dbc !important;
    background: #e8f4fc;
}
.promo-thumb:hover {
    border-color: #97c5e0;
}
</style>

<script>
$(document).ready(function() {
    var selectedImages = {};
    var eventsData = [];

    var dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    var monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    function formatDateDisplay() {
        var dateVal = $('#eventDate').val();
        var timeVal = $('#eventTime').val();
        if (!dateVal) {
            $('#eventDateDisplay').val('');
            return;
        }
        var parts = dateVal.split('-');
        var d = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
        var display = dayNames[d.getDay()] + ' - ' + monthNames[d.getMonth()] + ' ' + d.getDate();
        if (timeVal) {
            display += ' - ' + timeVal;
        }
        $('#eventDateDisplay').val(display);
    }

    function shortenLocation(loc) {
        if (!loc) return '';
        // Strip state abbreviation, zip, and country from end
        // "365 Santa Clara Ave, Fillmore, CA 93015, USA" -> "365 Santa Clara Ave, Fillmore"
        var parts = loc.split(',').map(function(s) { return s.trim(); });
        if (parts.length >= 2) {
            // Keep only first two parts (street, city)
            return parts[0] + ', ' + parts[1];
        }
        return loc;
    }

    function getSelectedSizes() {
        var sizes = [];
        $('.size-check:checked').each(function() {
            var label = $(this).parent().text().trim();
            var val = $(this).val();
            sizes.push(label.replace(/\s*\(.*\)/, '') + ' (' + val + ')');
        });
        return sizes;
    }

    function buildPrompt() {
        var sizes = getSelectedSizes();
        var sizeText = sizes.length > 0
            ? 'Generate the following sizes:\n' + sizes.map(function(s) { return '- ' + s; }).join('\n')
            : '';

        var text =
            'Create a promotional image for a live music performance.\n' +
            'Artist: Glenn Bennett\n' +
            'Venue: ' + ($('#venueName').val() || '') + '\n' +
            'Date & Time: ' + ($('#eventDateDisplay').val() || '') + '\n' +
            'Location: ' + ($('#eventLocation').val() || '') + '\n' +
            '\n' +
            'Text on the image should read:\n' +
            'Glenn Bennett Performing @ ' + ($('#venueName').val() || '') + '\n' +
            ($('#eventDateDisplay').val() || '') + '\n' +
            ($('#eventLocation').val() || '') + '\n' +
            '\n' +
            'Style: Eye-catching concert/event promotional flyer\n' +
            'Use an informal, friendly tone. Show the date as day of week + month + day (e.g. "Sunday - March 29 - 3 - 5 pm"), not a numeric date.\n' +
            'For the location, use just the street address and city name (e.g. "365 Santa Clara Ave, Fillmore"), not the full address with state/zip.\n' +
            'Include the artist name, venue, date, and time prominently.\n' +
            'Use the provided reference images for visual style and venue context.\n' +
            '\n' + sizeText;

        $('#promptText').val(text);
    }

    // Initialize prompt
    buildPrompt();

    // Auto-fill prompt when fields or sizes change
    $('#venueName, #eventDate, #eventTime, #eventLocation').on('input change', function() {
        formatDateDisplay();
        buildPrompt();
    });
    $(document).on('change', '.size-check', function() {
        buildPrompt();
    });

    // Reset prompt button
    $('#resetPrompt').on('click', function() {
        buildPrompt();
    });

    // Fetch events from Google Calendar
    $.getJSON('<?php echo site_url("admin/promo/fetch_events") ?>', function(events) {
        eventsData = events;
        var $select = $('#eventSelect');
        $select.empty().append('<option value="">-- Select an event (or enter manually) --</option>');

        if (events.length === 0) {
            $select.append('<option value="" disabled>No upcoming events found</option>');
            return;
        }

        $.each(events, function(i, ev) {
            var label = ev.date_display + ' — ' + ev.summary;
            if (ev.location) label += ' (' + ev.location.split(',')[0] + ')';
            $select.append('<option value="' + i + '">' + $('<span>').text(label).html() + '</option>');
        });
    }).fail(function() {
        $('#eventSelect').empty().append('<option value="">-- Could not load events (enter manually) --</option>');
    });

    // Event selection auto-fills fields
    $('#eventSelect').on('change', function() {
        var idx = $(this).val();
        if (idx === '' || !eventsData[idx]) return;

        var ev = eventsData[idx];

        // Parse venue from summary (typically "Artist @ Venue" or just venue name)
        var venue = ev.summary || '';
        if (venue.indexOf(' @ ') !== -1) {
            venue = venue.split(' @ ')[1];
        } else if (venue.indexOf(' at ') !== -1) {
            venue = venue.split(' at ')[1];
        }

        $('#venueName').val(venue);
        $('#eventDate').val(ev.date);
        $('#eventTime').val(ev.time || '');
        $('#eventLocation').val(shortenLocation(ev.location || ''));

        if (ev.description) {
            $('#eventDescription').text(ev.description);
            $('#descriptionGroup').show();
        } else {
            $('#descriptionGroup').hide();
        }

        formatDateDisplay();
        buildPrompt();
    });

    // Image selection
    $(document).on('click', '.promo-thumb', function() {
        var id = $(this).data('id');
        if (selectedImages[id]) {
            delete selectedImages[id];
            $(this).removeClass('selected');
            $(this).find('.check-overlay').hide();
        } else {
            selectedImages[id] = true;
            $(this).addClass('selected');
            $(this).find('.check-overlay').show();
        }
        updateSelectedCount();
    });

    function updateSelectedCount() {
        var count = Object.keys(selectedImages).length;
        $('#selectedCount').text(count + ' selected');
    }

    // Form submission — inject hidden inputs for selected image IDs
    $('#promoForm').on('submit', function() {
        var ids = Object.keys(selectedImages);
        if (ids.length === 0) {
            alert('Please select at least one image.');
            return false;
        }
        if (!$.trim($('#promptText').val())) {
            alert('Please provide a prompt.');
            return false;
        }

        $('#hiddenImageIds').empty();
        $.each(ids, function(i, id) {
            $('#hiddenImageIds').append('<input type="hidden" name="image_ids[]" value="' + id + '">');
        });

        return true;
    });
});
</script>

<?php include VIEWPATH . 'admin/includes/footer.php'; ?>
