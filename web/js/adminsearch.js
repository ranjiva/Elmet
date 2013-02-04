$(document).ready(function() {
                
                $("form").submit(function(e){
                
                    var error = false;
                
                    $("td[id='error_custom']").hide();
               
                    var custom = $("input[id='custom']").val();
                
                    if (custom != "") {
                       
                       if (isNaN(custom)) {
                           error = true;
                       } else {
                         
                           if (custom %1 != 0) {
                               error = true;
                           }
                       }
                       
                        if (error == true) {
                       
                            $("td[id='error_custom']").show();
                            e.preventDefault();
                        }
                   }                   
                });
            });