$(document).ready(function(){
    
    $("a[class='popup']").click(function(e) {
        
        var href = $(this).attr('href');
        var title = $(this).attr('title');
        window.open(href,title,'height=610, width=780, left=300, top=100,resizable=yes, scrollbars=yes, toolbar=no, menubar=no, location=no, directories=no');
        
        e.preventDefault();

    });

});


