<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<style>
table tr td:last-child {
    white-space: nowrap;
    width: 1px;
}

.badge-sm {
    min-width: 1.8em;
    padding: .25em !important;
    margin-left: .1em;
    margin-right: .1em;
    color: white !important;
    cursor: pointer;
}

</style>

    <title>Cortez Tasks</title>
  </head>
  <body>
<div class="container">
    <!-- Jumbotron -->

    <div class="row align-items-end p-4 shadow-4 rounded-3" style="background-color: hsl(0, 0%, 94%);">
      <div class="col-sm-6">
          <? echo "Priority"; ?>
              <h1>Cortez Tasks</h1>
      </div>
      <div class="text-right col-sm-6">
      
          <? echo "<h5>To do: " . $todo_count . "<br>Completed: " . $complete_count . "</h5>"; ?>

      </div>
        

    </div>
    <!-- Jumbotron -->

    <?php foreach($priorities as $priority)
    {
        $count = 0;
        $complete_count = 0;
        foreach($statuses as $status)
        {
            if($status != "Completed")
            {
                $count = $count + count( $all_todos[$priority][$status] );
            }
            else
            {
                $complete_count = $complete_count + count( $all_todos[$priority][$status] );
            }
        }

    ?>
    <hr>
    <div style="background-color:#34a1eb;" class="row align-items-end mx-1 pt-2  text-white">
        <div class="col-sm">
        <? echo "Priority"; ?>
            <h3><? echo $priority ?></h3>
        </div>
        <div class="text-right col-sm">
        <? echo "To do: " . $count . "<br>Completed: " . $complete_count; ?>
        </div>
    </div>
    <table class="table table-hover">
      <thead class="thead-dark">
        <tr>
          <th>Task/Status</th>
          <th>Notes</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>

    <?php foreach($statuses as $status)
    {
    //var_dump($all_todos);
    ?>

        <?php foreach($all_todos[$priority][$status] as $todo)
        {
        ?>

        <?php
        if($status == "Completed")
        {
        ?>
        <tr class="table-secondary">
        <?php
        }
        else
        {
        ?>
        <tr>
        <?php
        }
        ?>
          <td scope="row"><h5><? echo $todo->task; ?></h5>
          <br>
         <?php
            switch($status) {
                case "Completed";
                    $color = "badge-success";
                    break;
                case "Not started";
                    $color = "badge-secondary";
                    break;
                case "In progress";
                    $color = "badge-primary";
                    break;

            }
            ?>
            <a class="badge <?php echo $color; ?> badge-sm" data-scale="hour" data-value="1"><?php echo $status; ?></a>
            <?php

            $curtime = time();
            
            // calcuate days since created
            $created_time = strtotime($todo->created);
                        
            $created_seconds_ago =  $curtime - $created_time;
            
            $created_days_ago = intval( $created_seconds_ago / (24 * 3600) );
            
            $updated_time = strtotime($todo->updated);
            
            // calcuate days since updated
            $updated_time = strtotime($todo->updated);
                        
            $updated_seconds_ago =  $curtime - $updated_time;
            
            $updated_days_ago = intval( $updated_seconds_ago / (24 * 3600) );
            
            
            if($created_days_ago < 7)  //Days
            {
            ?>
                <a class="badge badge-success badge-sm" data-scale="hour" data-value="1">New</a>
            <?php
            }
            
            // give a few days to tweek it before we show it as updated
            $diff_days = $created_days_ago - $updated_days_ago;
            
//            echo "$created_days_ago_ago - $updated_days -  = $diff_days" ;
             
            if( ($diff_days > 2) && ($updated_days_ago < 7) )  //Days
            {
              ?>
                  <a class="badge badge-primary badge-sm" data-scale="hour" data-value="2">Updated</a>
              <?php
            }

          ?>


          </td>
          <td><? echo $todo->notes; ?></td>
          <td>
          <a href="/index.php/cortez_edit/index/edit/<?php echo $todo->id?>"  >
          <button class = "float-right btn btn-primary btn-sm"> Edit </button>
          </a>
          </td>

        </tr>
        <?php
        }
        ?>
    <?php
    }
    ?>

      </tbody>
    </table>



    <?php
    }
    ?>
    <footer class="bg-light text-center">
      <!-- Footer Elements -->

        <div class="pt-4 pb-4 container">
       
          <a href="/index.php/cortez_edit/index/add/"  >
          <button class = "btn btn-primary btn-sm"> Add Task </button>
          </a>
        </div>
    </footer>

</div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>