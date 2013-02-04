            function edit() {

                $("input[id='name']").removeAttr('readonly');
                $("input[id='display']").removeAttr('readonly');
                $("input[id='thumbnail']").removeAttr('readonly');
                $("input[id='swatch']").removeAttr('readonly');                
                $("select[id='instock']").removeAttr('disabled');
                $("select[id='buynow']").removeAttr('disabled');
                $("span[id='undoEdit']").show();
                $("span[id='edit']").hide();
                $("input[id='submit']").show();
            }

            function undoEdit() {

                $("input[id='name']").attr('readonly', 'true');
                $("input[id='display']").attr('readonly', 'true');
                $("input[id='thumbnail']").attr('readonly', 'true');
                $("input[id='swatch']").attr('readonly', 'true');                
                $("select[id='instock']").attr('disabled', 'true');
                $("select[id='buynow']").attr('disabled', 'true');
                $("span[id='undoEdit']").hide();
                $("span[id='edit']").show();
                $("input[id='submit']").hide();
            }
            
            $(document).ready(function() {
                
                $("form").submit(function(e){
                
                    $("td[id='error_name']").hide();
                    
                    name = $("input[id='name']").val();
                    
                    if (name == "") {
                        
                        $("td[id='error_name']").show();
                        e.preventDefault();
                    }
                });
            });