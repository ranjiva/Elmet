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

			decreaseTableWidth();
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
			decreaseTableWidth();
            }
			
			var submitButton;
			var originalFormWidth;
			var originalTableWidth; 
        
        function increaseTableWidth() {
        
             var formWidth = originalFormWidth + 50;
		   $("form").width(formWidth +'px');
			
		  var tableWidth = originalTableWidth + 50;             
		  $("table").width(tableWidth + 'px');
        
        }
	
	   function decreaseTableWidth() {
       
		  $("form").width(originalFormWidth +'px');
		             
		  $("table").width(originalTableWidth + 'px');
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

		 originalFormWidth = $("form").width();
 		 originalTableWidth = $("table").width();
        });
        