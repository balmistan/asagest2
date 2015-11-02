$(document).ready(function () {
    var availableDates = getdate();
    $("#start_date").datepicker({beforeShowDay: available});
    $("#end_date").datepicker({beforeShowDay: available});
    $("input:button").button();
    $("#tabs").tabs();



    var datamin = $("#start_date").val();
    var datamax = $("#end_date").val();
    var familyid = $("#family_id").val();
    var tab_selected = $("#default_selected_tabs").val();   //inizializzo con l' id link del tab attivo di default al caricamento della pagina.
    $("#" + tab_selected).trigger("click");
    var sheetid = "";    // Cod. consegna




    var options = {
        bJQueryUI: true,
        sPaginationType: "full_numbers",
        oLanguage: {
            "sLengthMenu": "Mostra _MENU_ risultati per pagina",
            "sZeroRecords": "Nessun risultato!",
            //"sInfo": "Risultati da _START_ a _END_ di _TOTAL_ totali",
            "sInfo": "Risultati totali: _TOTAL_ ",
            "sInfoEmpty": "Risultati 0",
            "sInfoFiltered": "(filtrati da un totale di _MAX_)",
            "sInfoPostFix": "",
            "sSearch": "Cerca:",
            "oPaginate": {
                "sFirst": "<<",
                "sPrevious": "Prec",
                "sNext": "Succ",
                "sLast": ">>"
            }
        },
        //"sDom": 'T<"clear">lfrtip',
        "sDom": '<"H"Tfr>t<"F"ip>',
        "oTableTools": {
            "sSwfPath": "swf/copy_csv_xls_pdf.swf",
            "aButtons": [
                "csv", "pdf"
            ]
        },
        "aaSorting": [[1, 'asc']],
        "bRetrieve": 1,
        "aoColumns": [
            {
                "sTitle": "Data"
            },
            {
                "sTitle": "Cod. consegna",
                "sClass": "sheetid"
            },
            {
                "sTitle": "Cognome e nome"
            },
            {
                "sTitle": "N° Scheda",
                "sClass": "center"
            }
        ],
        fnRowCallback: function (nRow, aData, iDisplayIndex) {
            handleCallback(nRow, aData, iDisplayIndex);
            return nRow;
        },
        sAjaxSource: '../Ajax/ajax_report.php',
        "fnServerData": function (sSource, aoData, fnCallback) {


            /* Add some extra data to the sender */
            aoData.push({"name": "datamin", "value": datamin});
            aoData.push({"name": "datamax", "value": datamax});
            aoData.push({"name": "familyid", "value": familyid});
            aoData.push({"name": "tabselected", "value": tab_selected});
            aoData.push({"name": "sheetid", "value": sheetid});
            aoData.push({"name": "comune", "value": $("#comune").val()});

            $.getJSON(sSource, aoData, function () {


            }); //close $.getJSON

        },
    };

    //var oTable = $("#DTable1").jqDataTable(options);
    $("#DTable1").width("100%");
    var oTable = $("#DTable1").dataTable(options);








    //validate();
    //update();

    $("#start_date").change(function () {
        datamin = $(this).val();
    });

    $("#end_date").change(function () {
        datamax = $(this).val();
    });

    $("#family_id").keyup(function () {
        familyid = $(this).val();
    });

    $("#update_btn").click(function () {
        validate();
        update();
    });

    $(".tab_link").click(function () {
        tab_selected = $(this).attr("id");
        validate();
        update();
    });




//alert(oTable)


    function update() {
        var showtable = false;
        switch (tab_selected) {
            case 'tabs-1':        //Per famiglia
                $("#family_id").parent().show();
                showtable = true;
                //$('#DTable1').show();
                $("#comune").parent().hide();
                //$("#distr3").hide();
                break;
            case 'tabs-2':         //Generale
                $("#family_id").parent().hide();
                showtable = true;
                //$('#DTable1').show();
                // $("#distr3").hide();
                break;
            case 'request_bis':
                break;
            case 'tabs-3':        //Prodotti Distribuiti
                $("#family_id").parent().hide();
                //$('#DTable1').hide();  //nasconde tabella
                // $("#distr3").show();
                $("#comune").parent().show();
                //report3(res);
                break;

            default:
                break;
        }
        if (showtable) {
            $('#DTable1').show();
            $("#distr3").hide();
            oTable.fnDraw();
            $("#DTable1_last").click();   //Seleziono come default l' ultima pagina
        } else { //Siamo nel caso report
            $('#DTable1').hide();  //nasconde tabella
            $("#distr3").show();
            report3(res);
        }
    }

    //Aggiornamento contenuto tabella:

    function updateTable(res) {
        /*
         $("#tabs-1").html();
         switch (tab_selected) {
         case 'tabs-1':        //Per famiglia
         $("#family_id").parent().show();
         $('#DTable1').show();
         $("#comune").parent().hide();
         //     $('#DTable1').dataTable().fnDestroy();
         //     options['aaData'] = res;
         //     otable = $("#DTable1").dataTable(options);
         $("#DTable1").width("100%");
         $("#distr3").hide();
         break;
         case 'tabs-2':         //Generale
         $("#family_id").parent().hide();
         $('#DTable1').show();
         $("#distr3").hide();
         // $("#comune").parent().hide();
         //  $('#DTable1').dataTable().fnDestroy();
         //  options['aaData'] = res;
         $("#DTable1").dataTable(options);
         $("#DTable1").width("100%");
         break;
         case 'request_bis':
         
         break;
         case 'tabs-3':        //Prodotti Distribuiti
         $("#family_id").parent().hide();
         $('#DTable1').dataTable().fnDestroy(); //rimuove codice datatable
         $('#DTable1').hide();  //nasconde tabella
         $("#distr3").show();
         $("#comune").parent().show();
         report3(res);
         break;
         
         default:
         break;
         }// switch close
         
         $("#DTable1_last").click();   //Seleziono come default l' ultima pagina
         
         */
    }

    //validazioni

    function validate() {

        if (familyid != "" && isNaN(parseInt(familyid, 10))) {
            familyid = "";
            $("#family_id").addClass("error");
        } else
            $("#family_id").removeClass("error");

        var pattern = /^\d{1,2}\/\d{1,2}\/\d{4}$/;

        if (!pattern.test(datamin)) {
            datamin = "";
            $("#start_date").addClass("error");
        } else
            $("#start_date").removeClass("error");

        if (!pattern.test(datamax)) {
            datamax = "";
            $("#end_date").addClass("error");
        } else
            $("#end_date").removeClass("error");

    }


    function handleCallback(nRow, aData, iDisplayIndex) { //viene lanciata ogni volta che ....
        return;
        var num_col_id = 2;   //indice colonna contenente l' id
        if ($(nRow).data("initialized") == undefined) {
            $(nRow).data("initialized", true);

            var s_id = $(nRow).find(".sheetid").html();

            var new_s_id = parseInt(s_id) + parseInt($("#config_start_blocksheet").val()) - 1;

            //Riassegno:
            $(nRow).find(".sheetid").html(new_s_id);


            $(nRow).delegate('td', 'click', function () {

                var par = $(this).parent();

                $(nRow).parent().find('tr').removeClass('row_selected2')
                $(par).addClass('row_selected2');

                sheetID = $(par).find("td:nth-child(" + num_col_id + ")").html();                 //num_col_id è la coloonna che contiene l' id da inviare con get

                //Riottengo quello effettivo salvato sul db:

                sheetID = parseInt(sheetID) - parseInt($("#config_start_blocksheet").val()) + 1;


                //questo if viene eseguido quando clicco su una riga della tabella (non ultima colonna)
                // sheetID è l' id scheda corrispondente alla riga.
                //A questo punto faccio una richiesta Ajax con id=request_bis
                /*  tab_selected = "request_bis";
                 sheetid = sheetID;
                 update();*/
                $("#dialog").html('<iframe width="350" height="400" frameBorder="0" src="modalreport.php?sid=' + sheetID + '" />')
                        .dialog({
                            'width': 360,
                            'resizable': false,
                            'modal': true
                        });
            }); //close $(nRow).delegate('td', 'click', function()

        }// close if($(nRow).data("initialized")==undefined)	

        return;
    }

    function report3(res) {

        var comselout = (res['agea']['nomeComuneSelezionato'] == "") ? "" : "Comune: " + res['agea']['nomeComuneSelezionato'];
        var HTML = "<h2>" + comselout + "</h2>";
        HTML += "<h2>Distribuzione effettuata dal " + datamin + " al " + datamax + "</h2>";
        HTML += "<h3>Distribuzioni n° " + res['agea']['total_distr'] + "; ";
        HTML += "famiglie servite n° " + res['agea']['serv_family'] + "; ";
        HTML += "indigenti n° " + res['agea']['serv_indigenti'] + "</h3>";
        HTML += "<h2>Viveri Agea</h2>";
        HTML += "<table><tr><th>Qt&agrave;</th><th>Unit&agrave; Mis.</th><th>Nome Prodotto</th></tr>";
        for (var i in res['agea']['products'])
        {
            HTML += "<tr>";
            HTML += "<td>" + parseFloat(res['agea']['products'][i]['qty'], 10) + "</td><td>" + res['agea']['products'][i]['measureunity'] + "</td><td>" + res['agea']['products'][i]['name_product'] + "</td>";
            HTML += "</tr>"
        }
        HTML += "</table>";

        HTML += "<h2>Viveri Da Donazioni</h2>";
        HTML += "<table><tr><th>Qt&agrave;</th><th>Unit&agrave; Mis.</th><th>Nome Prodotto</th></tr>";
        for (var i in res['banco']['products'])
        {
            HTML += "<tr>";
            HTML += "<td>" + parseFloat(res['banco']['products'][i]['qty'], 10) + "</td><td>" + res['banco']['products'][i]['measureunity'] + "</td><td>" + res['banco']['products'][i]['name_product'] + "</td>";
            HTML += "</tr>"
        }
        HTML += "</table>";

        $("#distr3").html(HTML);
    }



    function getdate() {
        var arr_ret = Array();
        $.ajax({
            type: "POST",
            url: "../Ajax/ajax.distributiondate.php",
            cache: false,
            async: false,
            data: {
                'requestfrom': 'report'
            }
        }).done(function (msg) {

            arr_ret = JSON.parse(msg);

        });

        return arr_ret;
    }


    function available(date) {

        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        if (month < 10)
            month = "0" + month;
        var day = date.getDate();
        if (day < 10)
            day = "0" + day;

        var dmy = year + "-" + month + "-" + day;

        if ($.inArray(dmy, availableDates) != -1) {
            return [true, "", "Available"];
        } else {
            return [false, "", "unAvailable"];
        }
    }


});