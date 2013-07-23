function edit() {

    $("input[id='name']").removeAttr('readonly');
    $("input[id='address1']").removeAttr('readonly');
    $("input[id='address2']").removeAttr('readonly');
    $("input[id='town']").removeAttr('readonly');
    $("input[id='postcode']").removeAttr('readonly');                
    $("span[id='undoEdit']").show();
    $("span[id='edit']").hide();
    $("input[id='submit']").show();
}

    function undoEdit() {

    $("input[id='name']").attr('readonly', 'true');
    $("input[id='address1']").attr('readonly', 'true');
    $("input[id='address2']").attr('readonly', 'true');
    $("input[id='town']").attr('readonly', 'true');
    $("input[id='postcode']").attr('readonly', 'true');
    $("span[id='undoEdit']").hide();
    $("span[id='edit']").show();
    $("input[id='submit']").hide();
}

$(document).ready(function(){
    
    $("form").submit(function(e){

        $("td[id='error_name']").hide();
        $("td[id='error_address1']").hide();
        $("td[id='error_town']").hide();
        $("td[id='error_postcode']").hide();

        var name = $("input[name='name']").val();
        var address1 = $("input[name='address1']").val();
        var address2 = $("input[name='address2']").val();
        var town = $("input[name='town']").val();
        var postcode = $("input[name='postcode']").val();

        if (name == "") {

            $("td[id='error_name']").show();
            e.preventDefault();
        }

        if (address1 == "") {

            $("td[id='error_address1']").show();
            e.preventDefault();
        }
        
        if (town == "") {

            $("td[id='error_town']").show();
            e.preventDefault();
        }
        
        if (postcode == "") {

            $("td[id='error_postcode']").show();
            e.preventDefault();
        }
        
    });

});
