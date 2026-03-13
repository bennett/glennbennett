<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($this->session->flashdata('alert')):
    $time = time();
    $alert_message = $this->session->flashdata('alert');
    $alert_type = $this->session->flashdata('alert-type');
?>
    <section style="padding: 15px;">
        <div class="alert alert-<?php echo $alert_type; ?>" id="alert-<?php echo $time ?>"
             style="font-size: 18px; padding: 20px; opacity: 0.9;">
            <p style="margin: 0;"><?php echo $alert_message; ?></p>
        </div>
    </section>

    <script>
        setTimeout(function() {
            $('#alert-<?php echo $time ?>').fadeOut(3000, function() { $(this).remove(); });
        }, 4000)
    </script>
<?php endif ?>
