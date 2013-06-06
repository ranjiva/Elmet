$(document).ready(function(){
    
    new datepickr('datepick', {'dateFormat': 'd/m/y'});
    
    $("tr[id*='order_']").dblclick(function(e) {
        
        var pos = $(this).attr("id").indexOf('_');
        var id = $(this).attr("id").substr(pos+1);

        if ($("input[name='order_" + id + "']").val() == "notselected") {
            $("input[name='order_" + id + "']").val("selected");
            $(this).css("background-color", "#B7B2ED");
        } else {
            $("input[name='order_" + id + "']").val("notselected");
            $(this).css("background-color", "");
        }
        
    });
    
    $("form").submit(function(e){
    
        if (submitButton == "dispatch") {
            
            $("td[id='error_date']").hide();
            var strDate = $("input[name='dispatch_date']").val();
            
            if (strDate == "") {
                $("td[id='error_date']").show();
                e.preventDefault();
            }
        }
        
        if (submitButton == "load") {
            
            $("td[id='error_file']").hide();
            var fileName = $("input[name='tracking_file']").val();
            
            if (fileName == "") {
                $("td[id='error_file']").show();
                e.preventDefault();
            }
        }
    
    });
    
    $("a[class='confirm']").click(function(e) {
        
        if (confirm('Are you sure that you wish to cancel this order?')) {
            // Do nothing
        } else {
            e.preventDefault();
        }
        
    });
    

});


