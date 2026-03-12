<!-- Content
============================================= -->
<section id="content">
    <div class="content-wrap">
        <div class="container">
            <div class="row gutter-40 col-mb-80">
                <!-- Postcontent
                ============================================= -->
                <div class="postcontent col-lg-12">

                    <h3>Send us an Email</h3>

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
                        
                        <form action="<?php echo site_url('contact/send'); ?>" method="POST" class="mb-0" id="contact-form-working">
                        
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="name">Name <small>*</small></label>
                                    <input type="text" name="name" id="name" value="<?php echo set_value('name'); ?>" class="sm-form-control required" maxlength="100" />
                                    <?php echo form_error('name', '<small class="text-danger">', '</small>'); ?>
                                </div>

                                <div class="col-md-4 form-group">
                                    <label for="email">Email <small>*</small></label>
                                    <input type="email" name="email" id="email" value="<?php echo set_value('email'); ?>" class="required email sm-form-control" maxlength="100" />
                                    <?php echo form_error('email', '<small class="text-danger">', '</small>'); ?>
                                </div>

                                <div class="col-md-4 form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" id="phone" value="<?php echo set_value('phone'); ?>" class="sm-form-control" maxlength="20" />
                                    <?php echo form_error('phone', '<small class="text-danger">', '</small>'); ?>
                                </div>

                                <div class="w-100"></div>

                                <div class="col-md-12 form-group">
                                    <label for="subject">Subject <small>*</small></label>
                                    <input type="text" name="subject" id="subject" value="<?php echo set_value('subject'); ?>" class="required sm-form-control" maxlength="200" />
                                    <?php echo form_error('subject', '<small class="text-danger">', '</small>'); ?>
                                </div>

                                <div class="w-100"></div>

                                <div class="col-12 form-group">
                                    <label for="message">Message <small>*</small></label>
                                    <textarea name="message" id="message" class="required sm-form-control" rows="6" cols="30" maxlength="2000"><?php echo set_value('message'); ?></textarea>
                                    <?php echo form_error('message', '<small class="text-danger">', '</small>'); ?>
                                </div>

                                <!-- reCAPTCHA v3 (invisible) -->
                                <div class="col-12 form-group">
                                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                                    <?php echo form_error('g-recaptcha-response', '<small class="text-danger">', '</small>'); ?>
                                </div>

                                <!-- Honeypot field for spam protection -->
                                <div class="col-12 form-group d-none">
                                    <input type="text" name="botcheck" id="botcheck" value="" class="sm-form-control" style="display: none;" tabindex="-1" autocomplete="off" />
                                </div>

                                <div class="col-12 form-group">
                                    <input type="submit" name="submit" id="submit" value="Send Message" class="button button-3d m-0" />
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
<script src="https://www.google.com/recaptcha/api.js?render=6LeTxdMrAAAAAOK7Ld_C1n-Nx4N7cqXqThXVf-N-"></script>

<!-- Bulletproof Contact Form Solution -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contact-form-working');
    const submitButton = form.querySelector('input[type="submit"]');
    const recaptchaField = document.getElementById('g-recaptcha-response');
    let isProcessing = false;
    
    console.log('Bulletproof contact form initialized');
    
    // Store the original action URL in case it gets modified
    const originalAction = form.action;
    
    // Completely replace the form's submit behavior
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (isProcessing) {
            console.log('Already processing, ignoring...');
            return false;
        }
        
        console.log('Starting form processing...');
        isProcessing = true;
        
        // Disable button immediately
        submitButton.disabled = true;
        const originalButtonText = submitButton.value;
        submitButton.value = 'Verifying...';
        
        // Get all form values NOW, before any async operations
        const formData = {
            name: form.querySelector('[name="name"]').value.trim(),
            email: form.querySelector('[name="email"]').value.trim(),
            phone: form.querySelector('[name="phone"]').value.trim(),
            subject: form.querySelector('[name="subject"]').value.trim(),
            message: form.querySelector('[name="message"]').value.trim(),
            botcheck: form.querySelector('[name="botcheck"]').value
        };
        
        console.log('Captured form data:', formData);
        
        // Validate required fields on client side
        const requiredFields = ['name', 'email', 'subject', 'message'];
        const emptyFields = requiredFields.filter(field => !formData[field]);
        
        if (emptyFields.length > 0) {
            alert('Please fill in all required fields: ' + emptyFields.join(', '));
            resetButton(originalButtonText);
            return false;
        }
        
        // Get reCAPTCHA token
        if (typeof grecaptcha === 'undefined') {
            alert('reCAPTCHA failed to load. Please refresh the page and try again.');
            resetButton(originalButtonText);
            return false;
        }
        
        grecaptcha.ready(function() {
            console.log('Getting reCAPTCHA token...');
            
            grecaptcha.execute('6LeTxdMrAAAAAOK7Ld_C1n-Nx4N7cqXqThXVf-N-', {action: 'contact_form'})
                .then(function(token) {
                    console.log('reCAPTCHA token received, length:', token.length);
                    
                    submitButton.value = 'Sending...';
                    
                    // Method 1: Try direct window navigation (most reliable)
                    const params = new URLSearchParams();
                    params.append('name', formData.name);
                    params.append('email', formData.email);
                    params.append('phone', formData.phone);
                    params.append('subject', formData.subject);
                    params.append('message', formData.message);
                    params.append('botcheck', formData.botcheck);
                    params.append('g-recaptcha-response', token);
                    params.append('submit', 'Send Message');
                    
                    console.log('Submitting via window location...');
                    
                    // Create a temporary form using document.createElement
                    const tempForm = document.createElement('form');
                    tempForm.method = 'POST';
                    tempForm.action = originalAction;
                    
                    // Add all parameters as hidden inputs
                    for (const [key, value] of params.entries()) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = value;
                        tempForm.appendChild(input);
                    }
                    
                    // Add form to document temporarily
                    tempForm.style.display = 'none';
                    document.body.appendChild(tempForm);
                    
                    // Try multiple submission methods
                    try {
                        // Method 1: Direct submission
                        tempForm.submit();
                    } catch (error) {
                        console.log('Method 1 failed, trying method 2...');
                        
                        try {
                            // Method 2: Trigger click on submit button
                            const submitBtn = document.createElement('input');
                            submitBtn.type = 'submit';
                            submitBtn.style.display = 'none';
                            tempForm.appendChild(submitBtn);
                            submitBtn.click();
                        } catch (error2) {
                            console.log('Method 2 failed, trying method 3...');
                            
                            // Method 3: Manual POST using fetch as fallback
                            const formDataObj = new FormData();
                            for (const [key, value] of params.entries()) {
                                formDataObj.append(key, value);
                            }
                            
                            fetch(originalAction, {
                                method: 'POST',
                                body: formDataObj,
                                redirect: 'follow'
                            })
                            .then(response => {
                                if (response.redirected) {
                                    window.location.href = response.url;
                                } else {
                                    return response.text();
                                }
                            })
                            .then(html => {
                                if (html) {
                                    document.open();
                                    document.write(html);
                                    document.close();
                                }
                            })
                            .catch(fetchError => {
                                console.error('All methods failed:', fetchError);
                                alert('Form submission failed. Please try refreshing the page.');
                                resetButton(originalButtonText);
                            });
                        }
                    }
                    
                    // Clean up
                    setTimeout(() => {
                        if (tempForm.parentNode) {
                            tempForm.parentNode.removeChild(tempForm);
                        }
                    }, 1000);
                })
                .catch(function(error) {
                    console.error('reCAPTCHA error:', error);
                    alert('reCAPTCHA verification failed: ' + error.message);
                    resetButton(originalButtonText);
                });
        });
        
        return false;
    });
    
    function resetButton(originalText) {
        submitButton.disabled = false;
        submitButton.value = originalText || 'Send Message';
        isProcessing = false;
    }
    
    // Prevent any other scripts from interfering
    form.onsubmit = null;
    
    console.log('Contact form ready with bulletproof submission handling');
});
</script>
