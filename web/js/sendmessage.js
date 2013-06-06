$(document).ready(function(){
    
    $("form").submit(function(e){

        $("td[id='error_subject']").hide();
        $("td[id='error_message']").hide();

        var subject = $("input[name='subject']").val();
        var message = $("textarea[name='message']").val();

        if (subject == "") {

            $("td[id='error_subject']").show();
            e.preventDefault();
        }

        if (message == "") {

            $("td[id='error_message']").show();
            e.preventDefault();
        }
    });

});
