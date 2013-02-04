 $(document).ready(function(){
            
                $("form").submit(function(e){
            
                    var names = "";

                    $("input[name*='old:name_']").each(function() {

                        names = names + ',' + $(this).val();
                    });

                    if (submitButton == "save_new") {

                        var name = $("input[name='name_new']").val(); 
                        
                        if ((name == "") || (names.indexOf(name) != -1)) {

                            increaseTableWidth();
                            $("td[id='error_new']").show();
                            e.preventDefault();
                        }

                    } else {

                        if (submitButton == "submit_all") {

                            $("tr[id*='editAll_']").each(function() {

                                $(this).find("input[name*='all:name_']").each(function () { 

                                    var name = $(this).val();
                                    
                                    if ((name == "") || (names.indexOf(name) != -1)) {
                                        
                                        var pos = $(this).attr("name").indexOf('_');
                                        var id = $(this).attr("name").substr(pos+1);
                                        
                                        var oldName = $("input[name*='old:name_" + id + "']").val();
                                      
                                        if((oldName != name) || (name == "")) {
                                        
                                            $(this).parents("tr[id*='editAll_']").find("td[id='error_" + id + "']").show();
                                            e.preventDefault();
                                            increaseTableWidth();
                                        }
                                        else {
                                            $(this).parents("tr[id*='editAll_']").find("td[id='error_" + id + "']").hide();
                                        }
                                    }
                                });

                            });

                        } else {

                            var pos = submitButton.indexOf('_');
                            var id = submitButton.substr(pos+1);

                            var name = $("tr[id='edit_" + id + "']").find("input[name='single:name_" + id + "']").val();
                                       
                            if ((name == "") || (names.indexOf(name) != -1)) {

                                var oldName = $("input[name*='old:name_" + id + "']").val();
                                
                                if ((oldName != name) || (name == "")) {

                                    $("tr[id='edit_" + id + "']").find("td[id='error_" + id + "']").show();
                                    e.preventDefault();
                                    increaseTableWidth();
                                }
                            }

                        }
                    }    
                });
            });