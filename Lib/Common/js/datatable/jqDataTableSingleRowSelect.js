/*

var oTable;
var num_col_id;

(function($){  
    $.fn.jqDataTable = function(options) {  
     
        var defaults = { 
            num_col_id:5,    //lo uso per fornire la variabile get ai link
            bJQueryUI: true,
            sPaginationType: "full_numbers",
            oLanguage: {
                "sLengthMenu": "Mostra _MENU_ risultati per pagina",
                "sZeroRecords": "",
                //"sInfo": "Risultati da _START_ a _END_ di _TOTAL_ totali",
                "sInfo": "Risultati totali: _TOTAL_ ",
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
            //"sDom": 'T<"clear">lfrtip',
            "sDom": '<"H"Tfr>t<"F"ip>',
            "oTableTools": {
                "sSwfPath": "swf/copy_csv_xls_pdf.swf",
        
                "aButtons": [
                "csv","pdf"
                ]
            },
            fnRowCallback: function( nRow, aData, iDisplayIndex) {
                handleCallback( nRow, aData, iDisplayIndex);
                return nRow;
            }
        }
 
        var options = $.extend(defaults, options);  
  
        //alert(defaults)
 
        return this.each(function() { 
            var obj=$(this);
            oTable = $(obj).dataTable(options);  
            num_col_id= options["num_col_id"];       
        });
    }
 
 
    function handleCallback(nRow, aData, iDisplayIndex){ //viene lanciata ogni volta che ....
 
        if($(nRow).data("initialized")==undefined){
            $(nRow).data("initialized", true);
        
            $(nRow).delegate('td', 'click', function(){
            
                var par=$(this).parent();	

                strFID=$(par).find("td:nth-child("+num_col_id+")").html();                 //num_col_id è la coloonna che contiene l' id da inviare con get
          //      if($(this).html() != strFID)         //così non funziona il click sull' ultima colonna idfamily
                    location.replace('viewfamily?fid='+strFID);
			
            }); //close $(nRow).delegate('td', 'click', function()
		
        }// close if($(nRow).data("initialized")==undefined)	
	
        return;
    }
    
    
    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

 
    $(".dataTables_filter input:text").on("keyup", function(){
        if($(this).val().length <= 1){
            if(isNumeric($(this).val())){
                
                oTable.fnSort( [[4,'asc']] );
            }else{
               oTable.fnSort( [[0,'asc']] );
            }
        }
    });
  
 
})(jQuery);

*/