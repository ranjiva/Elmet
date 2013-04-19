$(document).ready(function(){
            $("form").submit(function(e){
                 
                if (submitButton == "submit_all") {

                    $("tr[id*='editAll_']").each(function() {

                        $(this).find("input[name*='all:stock_']").each(function () { 

                            var stock = $(this).val();
                            var pos = $(this).attr("name").indexOf('_');
                            var id = $(this).attr("name").substr(pos+1);

                            if ((stock != "") && (isNaN(stock) == true)) {

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

                    var stock = $("tr[id='edit_" + id + "']").find("input[name='single:stock_" + id + "']").val();

                    if ((stock != "") && (isNaN(stock) == true)) {

                        increaseTableWidth();

                        $("tr[id='edit_" + id + "']").find("td[id='error_" + id + "']").show();
                        e.preventDefault();
                    }

                }
            });
        });