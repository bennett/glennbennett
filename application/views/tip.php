<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Send Tips To Glenn Bennett</title>
    <style>
        h1, h2, h3, h4, h5, h6 {
            text-align: center;
        }

.h-divider {
  margin: auto;
  margin-top: 80px;
  width: 80%;
  position: relative;
}

.h-divider .shadow {
  overflow: hidden;
  height: 20px;
}

.h-divider .shadow:after {
  content: '';
  display: block;
  margin: -25px auto 0;
  width: 100%;
  height: 25px;
  border-radius: 125px/12px;
  box-shadow: 0 0 8px black;
}

.h-divider .text {
  width: 100px;
  height: 45px;
  padding: 10px;
  position: absolute;
  bottom: 100%;
  margin-bottom: -20px;
  left: 50%;
  margin-left: -60px;
  border-radius: 100%;
  box-shadow: 0 2px 4px #999;
  background: white;
}

.h-divider .text i {
  position: absolute;
  top: 4px;
  bottom: 4px;
  left: 4px;
  right: 4px;
  border-radius: 100%;
  border: 1px dashed #aaa;
  text-align: center;
  line-height: 50px;
  font-style: normal;
  color: #999;
}

.h-divider .text2 {
  width: 70px;
  height: 70px;
  position: absolute;
  bottom: 100%;
  margin-bottom: -35px;
  left: 50%;
  margin-left: -25px;
  border-radius: 100%;
  box-shadow: 0 2px 4px #999;
  background: white;
}

.h-divider img {
  position: absolute;
  margin: 4px;
  max-width: 60px;
  border-radius: 100%;
  border: 1px dashed #aaa;
}
    </style>
  </head>
  <body>

<div class="mt-2 container">
  <img src="/imgs/sepia.jpg" style="max-width: 100px; max-height: 100px; width: 100%; height: auto; object-fit: cover;" class="img-fluid rounded-circle d-block mx-auto" alt="Profile Picture">
</div>

<div class="container">
    <h2>Send a Tip To <br>Glenn Bennett</h2>
    <div id="smart-button-container">


            
        <div class="container">
          <div class="row">
            <div class="mt-2 col-sm-6  offset-md-3">
            <div class="card">
            <div class="card-body">
                <a href="https://venmo.com/u/glennbennett">
                    <button type="button" 
                        class="btn mb-2 btn-lg btn-block" 
                        style="background-color: #3D95CE; color: white;">
                    <h2>Via Venmo</h2>
                    </button>
                    <img src="/imgs/card_icons/Venmo.png" alt="ApplyPay" class="img-fluid">
                </a>
                
                Venmo now requires to you to enter the last four digits of my phone number:
                <h4>5512</h4>
            </div>
            </div>
            </div>


            <div class="mt-3 col-sm-6  offset-md-3">
            <div class="card">
            <div class="card-body">
                <div class="mb-2">
                    <a href="https://buy.stripe.com/bIY3e6eOAdk9fok6op">
                        <button  type="button" 
                            class="btn btn-lg btn-block" 
                            style="background-color: #3D95CE; color: white;">
                        <h2>Other Methods</h2>
                        </button>
                         
                    </a> 
                </div>  
                <img src="/imgs/card_icons/other.png" alt="ApplyPay" class="img-fluid">
                <h4 class="text-center font-italic">and more</h4>

            </div>
            </div>
            </div>
            
            <?php if($back_link != "")
            {
            ?>
            <div class="mt-3 col-sm-6  offset-md-3">
            <div class="card">
            <div class="card-body">
                <div class="mb-2">
                    <a href="<?php echo htmlspecialchars($back_link); ?>">
                        <button  type="button" 
                            class="btn btn-lg btn-block" 
                            style="background-color: white; color: #3D95CE; border-color: #3D95CE;">
                        Return
                        </button>
                         
                    </a> 
                </div>  
        
            </div>
            </div>
            </div>            
          </div>
          <?php
          }
            ?>
             
        </div>            

  </div>


</div>  


  
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>
