$(document).ready(function(){
    
    new datepickr('datepick', {'dateFormat': 'd/m/y'});
    
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!

    var yyyy = today.getFullYear();
    if(dd<10){
        dd='0'+dd
    } 
    if(mm<10){
        mm='0'+mm
    } 
    var currentDate = dd+'/'+mm+'/'+yyyy.toString().substr(2);
  
    $("input[id='datepick']").val(currentDate);
    
    $("form").submit(function(e){
  
        $("td[id='error_trackingnumber']").hide();

        var trackingNumber = $("input[name='trackingnumber']").val();

        if (trackingNumber == "") {

            $("td[id='error_trackingnumber']").show();
            e.preventDefault();
        }        
        
    });

});
