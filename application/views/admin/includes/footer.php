<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <?php echo date('F j, Y g:i a'); ?>
        </div>
        <strong>Copyright &copy; <?php echo date('Y') ?> Glenn Bennett.</strong> All rights reserved.
    </footer>
</div>
<!-- ./wrapper -->

<script src="<?php echo $url->assets ?>js/adminlte.min.js"></script>

<script>
$(document).ready(function () {
    $('.sidebar-menu').tree()
})
</script>
</body>
</html>
