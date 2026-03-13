  

    var scroll_flag = false;
    var view_area;
    
    // requires a Go button
      
    $('#go').click(function() 
    {
        console.log("Go");
        
        if($(this).text().trim() == 'Start')
        {
            scroll_flag = true; 
            
            view_area = $(window).height() / $(document).height();
            console.log(view_area);
            
            $(this).text('Stop');
            $(this).removeClass('btn-success');
            $(this).addClass('btn-danger');
               
            pageScroll();   
            
            /*   
            $("html, body").animate({ 
            scrollTop: $(document).height() 
            }, 4000);
           */
        
        
        }
        else
        {
            scroll_flag = false;
              
            $(this).text('Start');
            $(this).removeClass('btn-danger');
            $(this).addClass('btn-success');  
            
        }
    
    });
      
      
    function pageScroll() {
    console.log("pageScroll");
        if(scroll_flag)
        {
            window.scrollBy(0,1);
            scrolldelay = setTimeout(pageScroll,180 * view_area);
        }
        
    }  


    // Keystroke handling
    
    var sections = document.querySelectorAll('.section');
    var current_seection = 0;
    var sec = sections[current_seection];
    var h_hight = document.getElementById("top_nav").offsetHeight;
    
    $(document).ready(function () {
            
      document.onkeydown = function(e) {
            
          switch(e.which) {
              case 37: // left
              break;
      
              case 38: // up
              break;
      
              case 39: // right
              break;
      
              case 40: // down
                next_section();
              break;
      
              default: return; // exit this handler for other keys
          }
          e.preventDefault(); // prevent the default action (scroll / move caret)
      };
    
    
    
    });
    
    function next_section()
    {
    sec.style.backgroundColor =  "AliceBlue";
    sec.style.borderColor = "AliceBlue";
    
    if(current_seection == sections.length) // at the end
    {
        reset_section();
    }
    else // next one
    {
        sec = sections[current_seection];
        var spos = sec.offsetTop;
        sec.style.backgroundColor = "white";
        sec.style.borderColor = "blue";
        sec.style.borderStyle = "solid";
        sec.style.borderWidth = "thin";
        $("html, body").animate({scrollTop: spos - h_hight}, "slow");
        current_seection++;
    }
    } 
    
    function reset_section()
    {
        current_seection = 0;
        $("html, body").animate({scrollTop: 0}, "slow");
    }
    
    
    


