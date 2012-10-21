			function edit(id,focus_field) {
            
                element = document.getElementById("view_"+id);
                element.style.display = 'none';
                
                element = document.getElementById("edit_"+id);
                element.style.display = '';
                
                element = document.getElementById(focus_field+"_"+id);
                element.focus();
            }
            
            function undo(id) {
            
                element = document.getElementById("view_"+id);
                element.style.display = '';
                
                element = document.getElementById("edit_"+id);
                element.style.display = 'none';
            }
            
            function create()
            {
                element = document.getElementById("new");
                element.style.display = '';
            }
            
            function undoCreate()
            {
                element = document.getElementById("new");
                element.style.display = 'none';
            }
			
			var submitButton;
        
        function increaseTableWidth() {
        
            $("form").width('550px');
            $("table").width('490px');
        
        }
        
        function editAll() {
        
            $("tr[id*='view']").hide();
            $("tr[id*='editAll']").show();
            $("span[id='editAll']").hide();
            $("span[id='undoEditAll']").show();
            $("input[id='submitAll']").show();
        }
        
        function undoEditAll() {
        
            $("tr[id*='editAll']").hide();
            $("tr[id*='view']").show();
            $("span[id='editAll']").show();
            $("span[id='undoEditAll']").hide();
            $("input[id='submitAll']").hide();
        }
        
        $(document).ready(function () { 
         
            $("input[type='submit']").click(function(){ 
                
                submitButton = $(this).attr("name");
           
            }); 
        });
        