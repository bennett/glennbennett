<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body>
<div class="container">
  <h1>Cortez Tasks</h1>


<?php
foreach($gc_output->css_files as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" >

<?php endforeach; ?>
<?php foreach($gc_output->js_files as $file): ?>

    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>
    <small><?php echo $sub_title; ?></small>
  </h1>
</section>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="box">
    <div class="box-header with-border">

      <h3 class="box-title"><?php echo $sub_title; ?></h3>



    </div>
    <div class="box-body">

<?php echo $gc_output->output; ?>



    </div>

    <div class="box-tools pull-right">
        <div class="btn-group">

        <a href="/cortez" class="btn btn-primary"><i class="fa fa-dashboard"></i> Dashboard</a>

        </div>

        <!-- button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                title="Collapse">
          <i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
          <i class="fa fa-times"></i></button -->
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
     </div>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->

</section>
<!-- /.content -->

</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>

<script>
  $('#dataTable1').DataTable()
</script>