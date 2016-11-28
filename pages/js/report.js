
$(document).ready(function () {

    $("input:button").button();
    $("#tabs").tabs();
    var datamin = $("#start_date").val();
    var datamax = $("#end_date").val();
    var familyid = $("#family_id").val();
    var tab_selected = $("#default_selected_tabs").val(); //inizializzo con l' id link del tab attivo di default al caricamento della pagina.
    $("#" + tab_selected).trigger("click");
    var sheetid = ""; // Cod. consegna


    var availableDates = getdate();
    $("#start_date").datepicker({
        "beforeShowDay": available,
        "onSelect": function (selected, evnt) {
            datamin = $(this).val();
            sessionhandle("reportdatamin", "SETVAL", datamin)
            validate();
            update();
        }
    });
    $("#end_date").datepicker({
        beforeShowDay: available,
        onSelect: function (selected, evnt) {
            datamax = $(this).val();
            sessionhandle("reportdatamax", "SETVAL", datamax)
            validate();
            update();
        }
    });


    //init:

    $("#comune").parent().hide();
    $("#family_id").parent().hide();
    validate();
    update();



    $("#family_id").keyup(function () {
        familyid = $(this).val();
        update();
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


    $("#comune").change(function () {
        //sessionhandle("reportidcomune", "SETVAL", $(this).val());
        update();
    });




    var options = {
        bJQueryUI: false,
        // sPaginationType: "full_numbers",
        "sScrollY": 300,
        "bScrollCollapse": 1,
        "bPaginate": 0,
        "bRetrieve": 1,
        "bDestroy": 1,
        "iDisplayLength": 1000000,
        oLanguage: {
            "sLengthMenu": "Mostra _MENU_ risultati per pagina",
            "sZeroRecords": "Nessun risultato!",
            "processing": true,
            //"sInfo": "Risultati da _START_ a _END_ di _TOTAL_ totali",
            "sInfo": "Risultati totali: _TOTAL_ ",
            "sInfoEmpty": "Risultati 0",
            "sInfoFiltered": "(filtrati da un totale di _MAX_)",
            "sInfoPostFix": "",
            "sSearch": "Cerca:",
            "oLanguage": {
                "sProcessing": "Elaborazione in corso..."
            },
            "oPaginate": {
                "sFirst": "<<",
                "sPrevious": "Prec",
                "sNext": "Succ",
                "sLast": ">>"
            }
        },
        //"sDom": 'T<"clear">lfrtip',
        //"sDom": '<"H"Tfr>t<"F"ip>',
        "oTableTools": {
            "sSwfPath": "swf/copy_csv_xls_pdf.swf",
            "aButtons": [
                "csv", "pdf"
            ]
        },
        "aaSorting": [[1, 'desc']],
        "bRetrieve": 1,
                "aoColumns": [
                    {
                        "sTitle": "Data"
                    },
                    {
                        "sTitle": "[N° Progressivo]",
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
        }
    };
    $("#DTable1").dataTable(options);
    function update() {

        $.ajax({
            type: "POST",
            url: "../Ajax/ajax_report.php",
            cache: false,
            data: {
                'datamin': datamin,
                'datamax': datamax,
                'familyid': familyid,
                'tabselected': tab_selected,
                'sheetid': sheetid,
                'comune': $("#comune").val()
            }
        }).done(function (msg) {

            var res = JSON.parse(msg);
            updateTable(res)
        });
    }

//Aggiornamento contenuto tabella:

    function updateTable(res) {

        $("#tabs-1").html();
        switch (tab_selected) {
            case 'tabs-1':        //Per famiglia
                $("#family_id").parent().show();
                $('#DTable1').show();
                $("#comune").parent().hide();
                $('#DTable1').dataTable().fnDestroy();
                options['aaData'] = res;
                $("#DTable1").dataTable(options);
                $("#DTable1").width("100%");
                $("#distr3").hide();
                break;
            case 'tabs-2':         //Generale
                $("#family_id").parent().hide();
                $('#DTable1').show();
                $("#distr3").hide();
                $("#comune").parent().hide();
                //$("#comune").parent().show();
                $('#DTable1').dataTable().fnDestroy();
                options['aaData'] = res;
                $("#DTable1").dataTable(options);
                $("#DTable1").width("100%");
                break;
            case 'request_bis':

                break;
            case 'tabs-3':        //Prodotti Distribuiti
                $("#family_id").parent().hide();
                $('#DTable1').dataTable().fnDestroy(); //rimuove codice datatable
                $('#DTable1').hide(); //nasconde tabella
                $("#distr3").show();
                $("#comune").parent().show();
                report3(res);
                break;
            default:
                break;
        }// switch close

        $("#DTable1_last").click(); //Seleziono come default l' ultima pagina
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
        var num_col_id = 2; //indice colonna contenente l' id
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
                sheetID = $(par).find("td:nth-child(" + num_col_id + ")").html(); //num_col_id è la coloonna che contiene l' id da inviare con get

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

    function sessionhandle(name, option, value) {
        var arr_ret = Array("");
        $.ajax({
            type: "POST",
            url: "../Ajax/ajax.session.php",
            cache: false,
            async: false,
            data: {
                'SESS_OPTION_NAME': name,
                'SESS_OPTION': option,
                'SESS_OPTION_VALUE': value
            }
        }).done(function (msg) {

            arr_ret = JSON.parse(msg);
        });
        return arr_ret[0];
    }

});