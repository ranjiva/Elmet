$(document).ready(function(){
            $("form").submit(function(e){
            
                if (submitButton == "save_new") {
                
                    var price = $("input[name='price_new']").val(); 
                    
                    if ((price == "") || (isNaN(price) == true)) {
                        
                        increaseTableWidth();
                        $("td[id='error_new']").show();
                        e.preventDefault();
                    }
                    
                } else {
                    
                    if (submitButton == "submit_all") {
                    
                        $("tr[id*='editAll_']").each(function() {
                        
                            $(this).find("input[name*='all:price_']").each(function () { 
                               
                                var price = $(this).val();
								var pos = $(this).attr("name").indexOf('_');
                                var id = $(this).attr("name").substr(pos+1);
                                
                                if ((price == "") || (isNaN(price) == true)) {
                                
                                    increaseTableWidth();
              
                                    $(this).parents("tr[id*='editAll_']").find("td[id='error_" + id + "']").show();
                                    e.preventDefault();
                                }
								else {
									$(this).parents("tr[id*='editAll_']").find("td[id='error_" + id + "']").hide();
								}
                            });
                    
                        });

                    } else {
                    
                        var pos = submitButton.indexOf('_');
                        var id = submitButton.substr(pos+1);
                       
                        var price = $("tr[id='edit_" + id + "']").find("input[name='single:price_" + id + "']").val();
                    
                        if ((price == "") || (isNaN(price) == true)) {

                            increaseTableWidth();
                        
                            $("tr[id='edit_" + id + "']").find("td[id='error_" + id + "']").show();
                            e.preventDefault();
                        }
                        
                    }
                   
                }
            });
        });