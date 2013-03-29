function tooltip(target_items, name, offsetX, offsetY){
    
    $(target_items).each(function(i){
        
        $("body").append("<div class='"+name+"' id='"+name+i+"'><p>"+$(this).attr('title')+"</p></div>");
	
            var my_tooltip = $("#"+name+i);

            $(this).removeAttr("title").mouseover(function(){
                my_tooltip.css({opacity:0.8, display:"none"}).toggle();
            }).mousemove(function(kmouse){
		my_tooltip.css({left:kmouse.pageX+offsetX, top:kmouse.pageY+offsetY});
            }).mouseout(function(){
		my_tooltip.toggle();
            });
    });
}


