$(document).ready(function(){
    
    $("a[class='confirm']").click(function(e) {
        
        if (confirm('Are you sure that you wish to cancel this order?')) {
            // Do nothing
        } else {
            e.preventDefault();
        }
        
    });
   
});


