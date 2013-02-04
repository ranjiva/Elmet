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

    function edit() {

                $("select[id='priceband']").removeAttr('disabled');
                $("input[id='shortname']").removeAttr('readonly');
                $("textarea[id='name']").removeAttr('readonly');
                $("textarea[id='materials']").removeAttr('readonly');
                $("select[id='tapesize']").removeAttr('disabled');
                $("select[id='lined']").removeAttr('disabled');
                $("select[id='eyelets']").removeAttr('disabled');
                $("input[id='fabricwidth']").removeAttr('readonly');
                $("input[id='patternrepeatlength']").removeAttr('readonly');
                $("select[id='cushionfinish']").removeAttr('disabled');
                $("select[id='curtainfinish']").removeAttr('disabled');
                $("td[id='new_colour']").show();
                $("td[id='remove_colour']").show();
                $("select[id='new']").removeAttr('disabled');
                $("span[id='undoEdit']").show();
                $("span[id='edit']").hide();
                $("input[id='submit']").show();
            }

            function undoEdit() {

                $("select[id='priceband']").attr('disabled', 'true');
                $("input[id='shortname']").attr('readonly', 'true');
                $("textarea[id='name']").attr('readonly', 'true');
                $("textarea[id='materials']").attr('readonly', 'true');
                $("select[id='tapesize']").attr('disabled', 'true');
                $("select[id='lined']").attr('disabled', 'true');
                $("select[id='eyelets']").attr('disabled', 'true');
                $("input[id='fabricwidth']").attr('readonly', 'true');
                $("input[id='patternrepeatlength']").attr('readonly', 'true');
                $("select[id='cushionfinish']").attr('disabled', 'true');
                $("select[id='curtainfinish']").attr('disabled', 'true');
                $("select[id='new']").attr('disabled', 'true');
                $("td[id='new_colour']").hide();
                $("td[id='remove_colour']").hide();
                $("span[id='undoEdit']").hide();
                $("span[id='edit']").show();
                $("input[id='submit']").hide();
                $("td[id='error_fabricwidth']").hide();
                $("td[id='error_patternrepeatlength']").hide();
                $("td[id='error_shortname']").hide();
                $("td[id='error_name']").hide();
                $("td[id='error_materials']").hide();
            }