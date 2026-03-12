<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>

</head>
<body>
  <div class="row">
    <div id="top_button" style="display: none;" class="col-md-8 offset-md-2">
        <a href="/cortez">
        <button type="button"  class="btn  btn-primary">Return to list</button>
        </a>
    </div>
  </div>  
  <div class="row">

    
    <div class="col-md-8 offset-md-2">

      	<div style='height:20px;'></div>

          <div style="padding: 10px">
      		<?php echo $output; ?>
          </div>

    </div>
  </div>
 
  <div class="row">
    <div id="bottom_button" style="display: none;" class="col-md-8 offset-md-2">
        <a href="/cortez">
        <button type="button"  class="btn  btn-primary">Return to list</button>
        </a>
    </div>
  </div> 
    
    <div class="row">
        <div  class="col-md-8 offset-md-2">
            <a href="/cortez">
            Return to list
            </a>
        </div>
    </div>    
    <div class="row">
    <div  class="col-md-8 offset-md-2">
        <?php
            $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $id = basename($actual_link); 
        ?>
       
        
        <a onclick="return confirm('Are you sure?')" href="/index.php/cortez_edit/delete_todo/<?php echo $id; ?>" class="btn btn-sm btn-default" title="" data-toggle="tooltip" data-original-title="Delete Track">
        <button type="button"  class="btn pull-right btn-danger">delete</button>
        </a>
        
    </div>
  </div> 


<?php foreach($js_files as $file): ?>
  <script src="<?php echo $file; ?>"></script>
  
<?php endforeach; ?>
  
<script>

$('#form-button-save').click(function() {
    document.getElementById("bottom_button").style.display = "block";
    document.getElementById("top_button").style.display = "block";
  
});

document.getElementById("field-notes").height = 100;


</script>  
</body>
</html>