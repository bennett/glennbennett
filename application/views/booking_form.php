<!-- Content
============================================= -->
<section id="content">
    <div class="content-wrap">
        <div class="container">
            <div class="row gutter-40 col-mb-80">
                <!-- Postcontent
                ============================================= -->
                <div class="postcontent col-lg-12">

                    <h3>Book Glenn Bennett for Your Event</h3>

                    <!-- Display Flash Messages -->
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Display Validation Errors -->
                    <?php if (isset($validation_errors) && !empty($validation_errors)): ?>
                        <div class="alert alert-danger">
                            <?php echo $validation_errors; ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-widget">

                        <form action="<?php echo site_url('booking/submit'); ?>" method="POST" class="mb-0" id="booking-form">

                            <div class="row">
                                <!-- Row 1: Contact Name | Email | Phone -->
                                <div class="col-md-4 form-group">
                                    <label for="contactName">Contact Name <small>*</small></label>
                                    <input type="text" name="contactName" id="contactName" value="<?php echo set_value('contactName'); ?>" class="sm-form-control required" maxlength="100" />
                                    <?php echo form_error('contactName', '<small class="text-danger">', '</small>'); ?>
                                </div>

                                <div class="col-md-4 form-group">
                                    <label for="contactEmail">Email <small>*</small></label>
                                    <input type="email" name="contactEmail" id="contactEmail" value="<?php echo set_value('contactEmail'); ?>" class="required email sm-form-control" maxlength="100" />
                                    <?php echo form_error('contactEmail', '<small class="text-danger">', '</small>'); ?>
                                </div>

                                <div class="col-md-4 form-group">
                                    <label for="contactPhone">Phone <small>*</small></label>
                                    <input type="tel" name="contactPhone" id="contactPhone" value="<?php echo set_value('contactPhone'); ?>" class="sm-form-control required" maxlength="20" />
                                    <?php echo form_error('contactPhone', '<small class="text-danger">', '</small>'); ?>
                                </div>

                                <div class="w-100"></div>

                                <!-- Row 2: Event Date | Start Time | Duration | Event Type -->
                                <div class="col-md-3 form-group">
                                    <label for="eventDate">Event Date <small>*</small></label>
                                    <input type="date" name="eventDate" id="eventDate" value="<?php echo set_value('eventDate'); ?>" class="sm-form-control required" />
                                    <?php echo form_error('eventDate', '<small class="text-danger">', '</small>'); ?>
                                </div>

                                <div class="col-md-3 form-group">
                                    <label for="startTime">Start Time <small>*</small></label>
                                    <input type="text" name="startTime" id="startTime" value="<?php echo set_value('startTime'); ?>" class="sm-form-control required" maxlength="20" placeholder="e.g. 7:00 PM" />
                                    <?php echo form_error('startTime', '<small class="text-danger">', '</small>'); ?>
                                </div>

                                <div class="col-md-3 form-group">
                                    <label for="duration">Duration <small>*</small></label>
                                    <input type="text" name="duration" id="duration" value="<?php echo set_value('duration'); ?>" class="sm-form-control required" maxlength="50" placeholder="e.g. 2 hours, 3 sets" />
                                    <?php echo form_error('duration', '<small class="text-danger">', '</small>'); ?>
                                </div>

                                <div class="col-md-3 form-group">
                                    <label for="eventType">Event Type <small>*</small></label>
                                    <input type="text" name="eventType" id="eventType" value="<?php echo set_value('eventType'); ?>" class="sm-form-control required" maxlength="200" placeholder="e.g. Wedding, Corporate, Private Party" />
                                    <?php echo form_error('eventType', '<small class="text-danger">', '</small>'); ?>
                                </div>

                                <div class="w-100"></div>

                                <!-- Row 3: Venue | Audience Size -->
                                <div class="col-md-8 form-group">
                                    <label for="venue">Venue Name &amp; Address <small>*</small></label>
                                    <input type="text" name="venue" id="venue" value="<?php echo set_value('venue'); ?>" class="sm-form-control required" maxlength="300" />
                                    <?php echo form_error('venue', '<small class="text-danger">', '</small>'); ?>
                                </div>

                                <div class="col-md-4 form-group">
                                    <label for="audienceSize">Audience Size</label>
                                    <input type="text" name="audienceSize" id="audienceSize" value="<?php echo set_value('audienceSize'); ?>" class="sm-form-control" maxlength="50" placeholder="e.g. 50-100" />
                                    <?php echo form_error('audienceSize', '<small class="text-danger">', '</small>'); ?>
                                </div>

                                <div class="w-100"></div>

                                <!-- Row 4: Music Style | Budget -->
                                <div class="col-md-6 form-group">
                                    <label for="musicStyle">Preferred Music Style</label>
                                    <input type="text" name="musicStyle" id="musicStyle" value="<?php echo set_value('musicStyle'); ?>" class="sm-form-control" maxlength="200" placeholder="e.g. Jazz, Acoustic, Classic Rock" />
                                    <?php echo form_error('musicStyle', '<small class="text-danger">', '</small>'); ?>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="budget">Budget Range</label>
                                    <input type="text" name="budget" id="budget" value="<?php echo set_value('budget'); ?>" class="sm-form-control" maxlength="100" />
                                    <?php echo form_error('budget', '<small class="text-danger">', '</small>'); ?>
                                </div>

                                <div class="w-100"></div>

                                <!-- Row 5: Song Requests -->
                                <div class="col-12 form-group">
                                    <label for="songRequests">Specific Song Requests</label>
                                    <textarea name="songRequests" id="songRequests" class="sm-form-control" rows="3" maxlength="2000"><?php echo set_value('songRequests'); ?></textarea>
                                    <?php echo form_error('songRequests', '<small class="text-danger">', '</small>'); ?>
                                </div>

                                <!-- Row 6: Additional Comments -->
                                <div class="col-12 form-group">
                                    <label for="additionalComments">Additional Comments</label>
                                    <textarea name="additionalComments" id="additionalComments" class="sm-form-control" rows="3" maxlength="2000"><?php echo set_value('additionalComments'); ?></textarea>
                                    <?php echo form_error('additionalComments', '<small class="text-danger">', '</small>'); ?>
                                </div>

                                <!-- reCAPTCHA v3 (invisible) -->
                                <div class="col-12 form-group">
                                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                                    <?php echo form_error('g-recaptcha-response', '<small class="text-danger">', '</small>'); ?>
                                </div>

                                <div class="col-12 form-group">
                                    <input type="submit" name="submit" id="submit" value="Submit Booking Request" class="button button-3d m-0" />
                                </div>
                            </div>

                        </form>
                    </div>

                </div><!-- .postcontent end -->
            </div>
        </div>
    </div>
</section><!-- #content end -->

<!-- Include Google reCAPTCHA v3 API -->
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $recaptcha_site_key; ?>"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('booking-form');
    const submitButton = form.querySelector('input[type="submit"]');
    const recaptchaField = document.getElementById('g-recaptcha-response');
    const startTimeField = document.getElementById('startTime');
    const timePattern = /^(1[0-2]|0?[1-9])(:[0-5][0-9])?\s*(am|pm|AM|PM)$|^([01]?[0-9]|2[0-3]):[0-5][0-9]$/;
    let isProcessing = false;

    const originalAction = form.action;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();

        if (isProcessing) return false;

        // Client-side time validation
        clearTimeError();
        if (startTimeField.value.trim() && !timePattern.test(startTimeField.value.trim())) {
            startTimeField.focus();
            showTimeError('Please enter a valid time (e.g. 7:00 PM, 7pm, 19:00).');
            return false;
        }

        isProcessing = true;
        submitButton.disabled = true;
        const originalButtonText = submitButton.value;
        submitButton.value = 'Verifying...';

        if (typeof grecaptcha === 'undefined') {
            alert('reCAPTCHA failed to load. Please refresh the page and try again.');
            resetButton(originalButtonText);
            return false;
        }

        grecaptcha.ready(function() {
            grecaptcha.execute('<?php echo $recaptcha_site_key; ?>', {action: 'booking_form'})
                .then(function(token) {
                    submitButton.value = 'Sending...';
                    recaptchaField.value = token;

                    // Create a temporary form to submit
                    const tempForm = document.createElement('form');
                    tempForm.method = 'POST';
                    tempForm.action = originalAction;
                    tempForm.style.display = 'none';

                    // Copy all form fields
                    const formData = new FormData(form);
                    for (const [key, value] of formData.entries()) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = value;
                        tempForm.appendChild(input);
                    }

                    document.body.appendChild(tempForm);
                    tempForm.submit();
                })
                .catch(function(error) {
                    console.error('reCAPTCHA error:', error);
                    alert('reCAPTCHA verification failed. Please try again.');
                    resetButton(originalButtonText);
                });
        });

        return false;
    });

    function resetButton(originalText) {
        submitButton.disabled = false;
        submitButton.value = originalText || 'Submit Booking Request';
        isProcessing = false;
    }

    function showTimeError(message) {
        var error = document.createElement('small');
        error.className = 'text-danger';
        error.id = 'startTime-client-error';
        error.textContent = message;
        startTimeField.parentNode.appendChild(error);
    }

    function clearTimeError() {
        var existing = document.getElementById('startTime-client-error');
        if (existing) existing.remove();
    }

    startTimeField.addEventListener('input', clearTimeError);
});
</script>
