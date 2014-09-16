            function edit() {

                $("input[id='name']").removeAttr('readonly');
                $("input[id='display']").removeAttr('readonly');
                $("input[id='thumbnail']").removeAttr('readonly');
                $("input[id='stock']").removeAttr('readonly');
                $("input[id='swatch']").removeAttr('readonly');                
                $("select[id='instock']").removeAttr('disabled');
                $("select[id='buynow']").removeAttr('disabled');
                $("select[id='onoffer']").removeAttr('disabled');
                $("span[id='undoEdit']").show();
                $("span[id='edit']").hide();
                $("input[id='submit']").show();
                
                if ($("select[id='onoffer']").val() == 1)
                    $("input[id='discount']").removeAttr('readonly');
            }

            function undoEdit() {

                $("input[id='name']").attr('readonly', 'true');
                $("input[id='display']").attr('readonly', 'true');
                $("input[id='thumbnail']").attr('readonly', 'true');
                $("input[id='swatch']").attr('readonly', 'true');
                $("input[id='stock']").attr('readonly', 'true');
                $("select[id='instock']").attr('disabled', 'true');
                $("select[id='buynow']").attr('disabled', 'true');
                $("select[id='onoffer']").attr('disabled', 'true');
                $("input[id='discount']").attr('readonly','true');
                $("span[id='undoEdit']").hide();
                $("span[id='edit']").show();
                $("input[id='submit']").hide();
               
            }
            
            $(document).ready(function() {
                
                $("form").submit(function(e){
                
                    $("td[id='error_name']").hide();
                    $("td[id='error_availablestock']").hide();
                    $("td[id='error_discount']").hide();
                    
                    var error = false;
                    var name = $("input[id='name']").val();
                    var availableStock = $("input[id='stock']").val();

                    if (name == "") {
                        
                        $("td[id='error_name']").show();
                        error = true;
                    }
                                           
                    if ((availableStock != "") && (isNaN(availableStock) == true)) {
                        
                        $("td[id='error_available']").show();
                        error = true;
                    }
                    
                    if ($("select[id='onoffer']").val() == 1) {
                        
                        var discount = $("input[id='discount']").val();
                        
                        if ((discount != "") && (isNaN(discount) == true)) {
                        
                            $("td[id='error_discount']").show();
                            error = true;
                        }
                        
                        if ((discount == "") || (discount <= 0)) {
                        
                            $("td[id='error_discount']").show();
                            error = true;
                        }
                        
                    }
                            
                    if (error == true) {
                    
                        e.preventDefault();
                    }
                });
            
                $("select[id='onoffer']").change(function() { 
                   
                   if ($(this).val() == 1)
                    $("input[id='discount']").removeAttr('readonly');
                   else {
                    $("input[id='discount']").val("");
                    $("input[id='discount']").attr('readonly','true');
                   }
                       
                });
            });