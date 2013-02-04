            $(document).ready(function() {
                  
                var num = $("tr[id*='view_']").length
                var curtainTypes = [];
                
                var i = 0;
                               
                $("input[name*='curtaintype_']").each(function() {
                        
                    curtainTypes[i] = $(this).val();
                    i++;
                 });
                
                if (num == 0) {
                    $("tr[id='new']").show();
                    $("td[id='undo_new']").hide();
                    $("span[id='editAll']").hide();
                }
                
                $("select[id='cepb_new']").change(function(e){
                    
                    var selected = $("select[id='cepb_new'] option:selected").text();
                     
                    $("select[id='type_new']").empty(); 
                    
                    for(i=0;i < curtainTypes.length;i++) {
                        
                        if (selected != "") {
                            
                            if (curtainTypes[i] == "HomeWindow") {
                                                         
                                $("select[id='type_new']").append($('<option></option>').val(curtainTypes[i]).html(curtainTypes[i]));
                            }
                            
                        } else {
                            
                            if (curtainTypes[i] != "HomeWindow") {
                                
                                $("select[id='type_new']").append($('<option></option>').val(curtainTypes[i]).html(curtainTypes[i]));
                            }
                        }
                    }
                     
                });
             
            });