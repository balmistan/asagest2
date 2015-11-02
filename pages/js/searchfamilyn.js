
/* Custom filtering function*/

var num_col_id = 6;  //indice colonna relativo al campo contenente l' id da inviare sul redirect quando si clicca su una riga


$.fn.dataTableExt.afnFiltering.push(
    function( oSettings, aData, iDataIndex ) {
        
        //Valori impostati sui radiobutton
           var block1 = document.getElementsByName("radio_block1");
            var block2 = document.getElementsByName("radio_block2");

            var checked1 = "";

            var checked2 = "";

            for (var i = 0; i < block1.length; i++) {
                if (block1[i].checked)
                    checked1 = block1[i].value;
            }

            for (var i = 0; i < block2.length; i++) {
                if (block2[i].checked)
                    checked2 = block2[i].value;
            }

            var statoscheda = aData[6];

            var rr = aData[7];
            
            //console.log(checked1)

            if (checked1 == 1 && rr == "off")
                return false;

            if (checked2 == 1 && statoscheda == "deleted")
                return false;

            if (checked2 == 2 && statoscheda != "deleted")
                return false;

            return true;

        });



$(document).ready(function () {

    var default_radio_block1_opt_sel = "1";  //Valore di default

    var default_radio_block2_opt_sel = "1";  //Valore di default

    var opt = Array();
    //Ottiene i valori dalla sessione. Ma se la variabile di sessione non è inizializzata, la inizializza con i valori di default passati come argomento.
    var opt = get_radio_option_new({
        "radio_block1_opt_sel": default_radio_block1_opt_sel,
        "radio_block2_opt_sel": default_radio_block2_opt_sel
    });

    var opt1 = opt["radio_block1_opt_sel"];
    var opt2 = opt["radio_block2_opt_sel"];

    set_radio_option();

    $(".radiobtn").buttonset();
    
    
    // FUNZIONI
    function get_radio_option_new(variable) { //Legge da sessione i valori da usare ed eventualmente aggiorna le variabili opt_sel
        return ajax_send("GETVAL", variable, "");
    }

    function set_radio_option_new(variable) {
        return ajax_send("SETVAL", variable, "");
    }

    function set_radio_option() { //Aggiorna l' html in base a opt_sel e setta in sessione con i valori opt_sel

        $("[name='radio_block1']").filter('[value=' + opt1 + ']').attr('checked', true);
        $("[name='radio_block2']").filter('[value=' + opt2 + ']').attr('checked', true);

    }

    function set_session_radio_btn() {
        var opt1 = $("[name='radio_block1']").parent().find("input:checked").val();
        var opt2 = $("[name='radio_block2']").parent().find("input:checked").val();

        set_radio_option_new({
            "radio_block1_opt_sel": opt1,
            "radio_block2_opt_sel": opt2
        });

    }

    function ajax_send(opt, variable, value) {
        var res = "";
        $.ajax({
            type: 'POST',
            data: {
                "SESS_OPTION": opt,
                "SESS_OPTION_NAME": variable
                        // "SESS_OPTION_VALUE": value
            },
            url: "../Ajax/ajax.session.php",
            async: false,
            cache: false,
            success: function (responce) {
                res = JSON.parse(responce);
                //alert(res);
            },
            error: function (ts) {
                //alert(ts.responseText);
            }
        });
        return res;
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
    
    ////////////////////////////////////////////////////////////

//parametri per datatable
var datatableParam = {
    "dom": '<if<t>>',
    "fnServerData": function (sUrl, aoData, fnCallback, oSettings) {
        oSettings.jqXHR = $.ajax({
            "url": sUrl,
            "data": aoData,
            "success": function (json) {
                if (json.sError) {
                    oSettings.oApi._fnLog(oSettings, 0, json.sError);
                }
                $(oSettings.oInstance).trigger('xhr', [oSettings, json]);
                fnCallback(json);
            },
            "dataType": "json",
            "cache": false,
            "type": oSettings.sServerMethod,
            "error": function (xhr, error, thrown) {
                if (error == "parsererror") {
                    oSettings.oApi._fnLog(oSettings, 0, "DataTables warning: JSON data from " +
                            "server could not be parsed. This is caused by a JSON formatting error.");
                }
            }
        });
    },
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
            
            fnRowCallback: function( nRow, aData, iDisplayIndex) {
                handleCallback( nRow, aData, iDisplayIndex);
                return nRow;
            },
     "bJQueryUI": false,
    "num_col_id": num_col_id, //colonna contenente l' id da inviare con get quando si clicca su una riga.
    "sScrollY": 1500,
    "bScrollCollapse": 1,
    "bPaginate": 0,
    "bRetrieve": 1,
    "bDestroy": 1,
    "iDisplayLength": 1000000,
    "bProcessing": true,
    "sAjaxSource": '../Ajax/ajax_searchfamilyn.php',
    "aoColumns": [
        {
            "sTitle": "Cognome",
            //"sWidth": "70px",
        },
        {
            "sTitle": "Nome"
        },
        {
            "sTitle": "Data di Nascita",
         //   "sWidth": "70px",
            "bSortable": false,
            "bSearchable": false,
            "targets": 2
        },
        {
            "sTitle": "Indirizzo",
           // "sWidth": "70px",
            "bSortable": false,
            "bSearchable": false
        },
        {
            "sTitle": "Comune",
            "sWidth": "70px",
            "bSearchable": false
        },
        {
            "sTitle": "N° Scheda",
       //     "sWidth": "30px",
        },
        {
            "sTitle": "statoscheda",
            "bVisible": false,
           // "bSearchable": false
        },
        {
            "sTitle": "searchactive",
            "bVisible": false,
         //   "bSearchable": false
        },
    ]
};

//////////////////////////////////////////////////////////////////////////////



   var oTable = $("#DTable").dataTable(datatableParam);
   

    $("#DTable_filter").find(":text").focus();

    $(":radio").bind("click", function () {
        
        set_session_radio_btn();

        oTable.fnDraw();

    });

    



   

});