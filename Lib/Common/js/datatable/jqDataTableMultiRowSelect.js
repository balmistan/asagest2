var oTable;

(function($){  
 $.fn.jqDataTable = function(options) {  
  var defaults = {  
	bJQueryUI: true,
	sPaginationType: "full_numbers",
	oLanguage: {
			"sLengthMenu": "Mostra _MENU_ risultati per pagina",
			"sZeroRecords": "Nessun risultato!",
			"sInfo": "Risultati da _START_ a _END_ di _TOTAL_ totali",
			"sInfoEmpty": "Risultati 0",
			"sInfoFiltered": "(filtrati da un totale di _MAX_)",
			"sInfoPostFix":  "",
	        "sSearch": "Cerca:",
	        "oPaginate": {
				"sFirst":    "<<",
				"sPrevious": "Prec",
				"sNext":     "Succ",
				"sLast":     ">>"
			}
	},
	fnRowCallback: function( nRow, aData, iDisplayIndex ) {
			handleCallback( nRow, aData, iDisplayIndex );
			return nRow;
	}
   }
 
  var options = $.extend(defaults, options);  
  
    return this.each(function() { 
    	var obj=$(this);
    	oTable = $(obj).dataTable(options);    
 
    	$(obj).width("70%")
    	$(".fg-toolbar").width(parseInt($(obj).width()) - 12)	
    	$(".fg-toolbar").css("margin-left", Math.round($(obj).position().left));
    	
    });
 }
 
 
function handleCallback(nRow, aData, iDisplayIndex){ //viene lanciata ogni volta che ....
if($(nRow).data("initialized")==undefined){
	$(nRow).data("initialized", true)
	$(nRow).delegate('td', 'click', function(){

    		var par=$(this).parent();	

				/* Check if we are adding or removing a row */

				// Change the color based on selection 
				if (!$(par).hasClass("row_selected")) {
					$(par).css("background-color","#aa36dd")
					$(par).addClass("row_selected")
					$("#debug").append("Selecteted: "+aData+"<br />")
				}
				else {
					$(par).css("background-color","");
					$(par).removeClass("row_selected")
					$("#debug").append("Deselected: "+aData+"<br />")
				}
					   
		}); //close $(nRow).delegate('td', 'click', function()
		
		}// close if($(nRow).data("initialized")==undefined)	
	
		return;
	}
 

 
})(jQuery);

