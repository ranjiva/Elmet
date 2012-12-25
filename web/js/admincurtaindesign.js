$(document).ready(function() {
            
                var urlNames = "";
                
                 $("input[name*='urlname_']").each(function() {

                        urlNames = urlNames + ',' + $(this).val();
                 });
            
                 $("form").submit(function(e){
                 
                    $("td[id='error_fabricwidth']").hide();
                    $("td[id='error_patternrepeatlength']").hide();
                    $("td[id='error_shortname']").hide();
                    $("td[id='error_name']").hide();
                    $("td[id='error_materials']").hide();
                 
                    var error = false;
                    var fabricWidth = $("input[id='fabricwidth']").val();
                       
                    if ((fabricWidth == "") || (isNaN(fabricWidth) == true)) {
                        
                        $("td[id='error_fabricwidth']").show();
                        error = true;
                    }
                    
                    var patternRepeatLength = $("input[id='patternrepeatlength']").val();
                                        
                    if ((patternRepeatLength == "") || (isNaN(patternRepeatLength) == true)) {
                        
                        $("td[id='error_patternrepeatlength']").show();
                        error = true;
                    }
                    
                    var name = $("textarea[id='name']").val();
                    
                    if (name == "") {
                        
                        $("td[id='error_name']").show();
                        error = true;
                    }
                    
                    var materials = $("textarea[id='materials']").val();
                    
                    if (materials == "") {
                        
                        $("td[id='error_materials']").show();
                        error = true;
                    }
                 
                    var urlName = $("input[id='shortname']").val();
                    var oldUrlName = $("input[name='oldUrlName']").val();
                 
                    if (urlName != oldUrlName) {
                        
                        if ((urlName == "") || (urlNames.indexOf(urlName) != -1)) {
                            
                            $("td[id='error_shortname']").show();
                            error = true;
                        }
                    }
                    
                    if (error == true) {
                    
                        e.preventDefault();
                    }
                 });
            });