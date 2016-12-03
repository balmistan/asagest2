$(document).ready(function () {
//    var qty_not_present = false;
    var modifiable = true;        //click su icone prodotti abilitato
    var save_enabled = true;
    var color1 = "gray";          //colore per viveri Agea
    var color2 = "#dceff5";           //colore per viveri Banco

    var debug = 0;     //solo risposte con errore
    var debug2 = 0;   // risposte anche non di errore

    //  var arr_giacze = getGiacze(); //Prelevo le giacenze Agea dall' allegato 8

    var start_qty_agea = getQtyAgea();

    var start_qty_banco = getQtyBanco();

    var body_height = $("body").height();

    $("body").css("height", "1000");

    //Verifico se è il caso di mostrare il pulsante rimuovi distribuzione.

    if ($("#msg").html() != "") { //Se sto mostrando l' ultima distribuzione effettuata per una certa famiglia
        $("#print").show();
        modifiable = 0;  //rendo la distr non modificabile
        //Verifico che la distribuzione che la distribuzione che sto visualizzando è l' ultima effettuata tra tutte.
        var lastSheetId = getSetConfig('get_config', 'lastsheetid');
        if (lastSheetId == $("#sheetId").val()) {
            $("#removedistr").show();
        }
    }
    
  $("#print").click(function(){
      myprint();
  })
  

     function myprint () {  //intercetto click su link stampa   
      var myurl = document.URL.split('/').slice(0, -1).join('/') + "/Stampa?id=" + $("#sheetId").val();
         
         //alert(myurl)
         var opn = window.open(myurl);
       if(opn){
           opn.focus();
       }
    }


    ///////////////  Codice per impedire modifiche //////////////////
    if ($("#sheetId").val() != "" && !modifiable) {
        $(".X").remove();  //rimuovo i pulsanti di cancellazione
        $("#Salva").hide();
        $(".clearButton").hide();
        $("#label_date").hide();
        $("#date").hide();
        $("#check").hide();
    }
    ////////////////////////////////////////////////////////////////
    //nascondo tutte le righe dell' output table che hanno qtytot=0

    $(".out_qtytot[value='0']").parent().parent().hide();   //Nascondo le righe che hanno qty = 0

    init_scroll();                                          //Imposto dimensioni scroll sulla pagina.

    //al caricamento della pagina scorro l' elenco icone e visualizzo la corrispondente riga nei prodotti distribuiti se qtytot !=0
    $(".item").each(function () {
        var product_id = $(this).attr('product_id');
        var obj = $(".out_qtytot[product_id='" + product_id + "']");
        if ($(obj).val() != 0)
            $(obj).parent().parent().show();
    });

    //al click sull' icona aggiorno l' elenco in tableout
    $(".item").click(function () {
        if (modifiable) {
            var qty_disp = true;
            //$("#Salva").removeAttr("disabled");
            var product_id = $(this).attr('product_id');
            var qtyforunity = parseFloat($(this).attr('qtyforunity'), 10);

            var qtytot = parseFloat($(".out_qtytot[product_id='" + product_id + "']").val(), 10);
            /*    if ($(this).parent().parent().hasClass("b_agea")) {
             var giacza = parseFloat(arr_giacze[product_id], 10);
             if ((giacza - qtytot - qtyforunity) < 0)
             qty_disp = false
             }*/
            if (qty_disp || 1) {  //Rimoss test temporaneamente
                qtytot += parseFloat($(this).attr('qtyforunity'), 10);
                $(".out_qtytot[product_id='" + product_id + "']").val(qtytot.toFixed(2) * 1);
                $(".out_qtytot[product_id='" + product_id + "']").parent().parent().show();
                highlights($(".out_qtytot[product_id='" + product_id + "']").parent().parent());
            }
            else
                alert('Quantità non disponibile!')
        } else {
            alert("Modifica non permessa!")
        }
    });

    //cancellazione riga
    $(".X").bind("click", "", function () {
        // $("#Salva").removeAttr("disabled");
        $(this).parent().parent().find(".out_qtytot").val("0");
        $(this).parent().parent().find(".redcolor").removeClass("redcolor");
        $(this).parent().parent().hide();
    });

    //salvataggio
    $('#blockform').submit(function (e) {

        // e.preventDefault();

        // alert(JSON.stringify(URLToArray($(this).serialize())));

        $("#img_wait").show();    //Mostro clessidra

        //Effettuo verifica data solo se si tratta di nuova pagina blocchetto
        if ($("#sheetId").val() == "") {                                               //se nuovo inserimento

            if ((getQtyBanco() + getQtyAgea()) == 0) {
                $("#img_wait").hide();
                alert("Nessun prodotto selezionato!")
                return false;
            }

            var date = $("#date").val();
            /*   if (date === undefined) {
             var now = new Date();
             var day = ((now.getDate() < 10) ? "0" : "") + now.getDate();
             var mounth = (((1 + now.getMonth()) < 10) ? "0" : "") + (1 + now.getMonth());
             var year = ((now.getYear() < 1000) ? (1900 + now.getYear()) : now.getYear());
             date = day + "/" + mounth + "/" + year;
             }
             */
            var result_check = check("check_date", date);

            if (result_check == -5) {
                $("#img_wait").hide();
                alert("Non è possibile salvare in quanto si sta tentando di salvare con data precedente a quella dell' ultima operazione riportata nei registri.");
                return false;
            }

            if (save_enabled) {

                if ($(".redcolor").length) {
                    $("#img_wait").hide();
                    alert("Alcuni prodotti selezionati non sono disponibili!");
                    return false;
                }

                if ($("#date") != undefined && $("#date").val() == "") {
                    $("#img_wait").hide();
                    alert("Occorre indicare la data");
                    return false;
                }

                else {
                    //eseguo un ulteriore verifica:
                    //Verifico che non sia stata effettuata una distrib nella medesima data solo se si tratta di nuova distribuzione.
                    var arr_ass = {'idfamily': $('#num_scheda').val(), 'date': $('#date').val()};
                    // if(check("check_distr_exists", "['idfamily':"+$('#num_scheda').val()+", 'date':"+$('#date').val()+"]")){
                    if (check("check_distr_exists", arr_ass) != 0) {
                        $("#img_wait").hide();
                        alert("Non si possono effettuare due distribuzioni alla stessa famiglia nella medesima data. Se è l' ultima distribuzione effettuata nella giornata cliccare su visualizza ultima distribuzione per modificarla");
                        return false;
                    }
//alert($(this).serialize())
                    //  console.log(URLToArray($(this).serialize()));
                    //alert(JSON.stringify(URLToArray($(this).serialize())));
                    //Posso adesso inviare al server
                    var res = send($(this).serialize());      //invio al server
                 
                    $("#sheetId").val(res['sheetId']);
                    $("#numrec").html(res['numrec']);
                    $("#otherinfo").html("[" + res['sheetId'] + "]");

                    if (res['xxx'] != undefined) {
                        $("#otherinfo").append("&nbsp;&nbsp;&nbsp;" + res['xxx']['date']);
                        $("#otherinfo").append("&nbsp;&nbsp;&nbsp;N° Comp: " + res['xxx']['numcomponents']);
                        $("#label_date").remove();  //il campo data alla fine viene rimosso, al fine di non permettere la modifica della data.
                        $("#date").remove();
                        $("#msg").html("Ultima distribuzione effettuata"); //Mostro il messaggio ultima distribuzione effettuata.
                        $("#gotolast").hide();
                        $("#removedistr").show(); //Mostro il pulsante rimuovi distribuzione.
                    }

                    modifiable = false; //Rimuovo funzione click su icone per aggiunta prodotto
                    $(".X").remove();  //rimuovo i pulsanti di cancellazione
                    $("#Salva").remove();  //rimuovo il pulsante Salva
                    $(".clearButton").remove();
                    $("#label_date").remove();
                    $("#date").remove();
                    $("#check").remove();
                    $("#saved_msg").show();
                    $("#saved_msg").css("visibility", "visible").fadeIn().fadeOut(5000);
                    //$("#saved_msg").css("visibility", "visible").fadeIn();
                    $("#print").show();
                   

                }//close else
            }//close if(save_enabled)
            $("#img_wait").hide();
            return false;
        }
        e.preventDefault()
    });
    $("#sw_agea").click(function () {
        $(this).parent().removeClass("active");
        $("#sw_banco").parent().addClass("active");
        $(".item").css("border", "2px solid " + color1);
        $(".b_banco").hide();
        $(".b_agea").show();

        init_scroll();
    });

    $("#sw_banco").click(function () {
        $(this).parent().removeClass("active");
        $("#sw_agea").parent().addClass("active");
        $(".item").css("border", "2px solid " + color2);
        $(".b_agea").hide();
        $(".b_banco").show();

        init_scroll();
    });

    $("#mem1, #mem2").click(function () {
        var id_mem = $(this).attr("id");
        var str_out = "{";
        $(".tout_agea, .tout_banco").find(".out_qtytot").each(function () {
            var id_product = $(this).attr("product_id");
            var qtytot = $(this).val();
            if (qtytot != 0)
                str_out += '"' + id_product + '":' + qtytot + ",";
        });

        //chiudo la stringa json
        if (str_out.length == 1)
            str_out += "}";
        else
            str_out = str_out.substring(0, str_out.length - 1) + "}";

        listmemory("set_memory_list", id_mem, str_out);
    })


    $(".btnlista1, .btnlista2, .scorsa").click(function () {
        if (modifiable) {
            qty_not_present = false;
            if ($(this).hasClass("scorsa")) {
                var arr_list = JSON.parse(listmemory("get_memory_scorsa", $("#num_scheda").val(), ""));

            } else {
                var id_mem = "mem1";
                if ($(this).hasClass("btnlista2"))
                    id_mem = "mem2";
                var arr_list = JSON.parse(listmemory("get_memory_list", id_mem, ""));
            }

            $(".out_qtytot").each(function () {

                $(this).parent().parent().show();
                var id = $(this).attr("product_id");
                if (arr_list[id] == undefined) {
                    $(this).val("0")
                }
                else {
                    $(this).val(arr_list[id] * 1);
                    $(this).parent().parent().show();
                    /*   if (arr_giacze[id] != undefined && (arr_giacze[id] - arr_list[id]) < 0) {
                     $(this).parent().parent().find(":input").addClass("redcolor");
                     qty_not_present = true;
                     }*/
                }
                if ($(this).val() == "0")
                    $(this).parent().parent().hide();
            });
            // if (qty_not_present)
            //     alert("Le voci in rosso indicano quantità non presenti in giacenza. Correggere i valori prima di salvare!");

        } else {
            alert("Modifica non permessa!");
        }

    });


    function getGiacze() {
        return getAjax({
            "req": "get_giacze_agea"
        }, "../Ajax/ajax.block_generic.php");   //Prelevo le giacenze Agea dall' allegato 8
    }


    function listmemory(req, id_mem, jsonstr) {
        return getAjax({
            'req': req,
            'id_mem': id_mem,
            'jsonstr': jsonstr
        }, "../Ajax/ajax.block_generic.php");
    }

    function getSetConfig(req, key, str) {
        return getAjax({
            'req': req,
            'key': key,
            'str': str
        }, "../Ajax/ajax.block_generic.php");
    }

    function check(checktype, data) {
        return getAjax({
            'req': checktype,
            'datacheck': data
        }, "../Ajax/ajax.block_generic.php");
    }


    function removeDistr(sheetId) {
        return getAjax({
            'req': 'remove_distr',
            'sheetId': sheetId
        }, "../Ajax/ajax.block_generic.php");
    }

    function send(dataform) {
        return getAjax(dataform, "../Ajax/ajax.block.php");
    }

    var date = getSetConfig('get_config', 'default_date_blocksheet');
    var arr_date = date.split("/");
    $("#date").val(arr_date[0] + "/" + arr_date[1] + "/" + arr_date[2])
            .datepicker({
                //'maxDate': 0,
                //    'defaultDate': new Date(arr_date[2], arr_date[1] - 1, arr_date[0]),
                onSelect: function (dateText, inst) {
                    getSetConfig('set_config', 'default_date_blocksheet', dateText);
                }
            });

    function init_scroll() {

        $(window).scroll(function () {       /*Permette lo scorrimento verticale del div bloccato in alto nella pagina*/

            $('#blocktableout').css('top', -$(window).scrollTop());
        });
    }

    function getQtyBanco() {
        var count = 0;
        $(".tout_banco").find(".out_qtytot").each(function () {
            if ($(this).val() != 0)
                count++;
        })
        return count;
    }

    function getQtyAgea() {
        var count = 0;
        $(".tout_agea").find(".out_qtytot").each(function () {
            if ($(this).val() != 0)
                count++;
        })
        return count;
    }


    $("#removedistr").click(function () {
        var resp = confirm("Proseguire con la cancallazione?")
        if (resp === true) {
            var res = removeDistr($("#sheetId").val());

            if (res) {
                $(".rowp").hide(); //nascondo righe prodotti 
                alert("Distribuzione cancellata!");
                location.reload();
            }
        }
    });


    $("body").css("opacity", 1);

    function getAjax(options, page) {
        var res = "";
        $.ajax({
            type: 'POST',
            data: options,
            url: page,
            async: false,
            success: function (resp) {
                //alert(resp)
                res = JSON.parse(resp); //In caso di messaggio d' errore JSON.parse darebbe NULL e quindi getAjax restituirà NULL
            },
            error: function (ts) {
                //alert(ts.responseText);
            }
        });
        return res;
    }

    function highlights(obj) {
        $(".rowp input:text").css("font-weight", "normal");
        $(obj).find("input:text").css("font-weight", "bold");
    }

    function URLToArray(url) {

        var request = {};
        url = decodeURIComponent(url);
        var pairs = url.substring(url.indexOf('?') + 1).split('&');
        var pair_a = Array();
        var pair_b = Array();
        var result = Array();

        var i = 0;
        while (i < pairs.length) {
            // alert(JSON.stringify(pairs[i]))
            var pair_a = pairs[i].split('=');
            if (pair_a[0] == "product_id[]") {
                var pair_b = pairs[i + 1].split('=');
                result[pair_a[1] + ""] = pair_b[1];
                i += 2;
            } else {
                result[pair_a[0] + ""] = pair_a[1];
                i++;
            }

        }

        return result;

        for (var i = 0; i < pairs.length; i++) {
            pair[i] = pairs[i].split('=');
            request[decodeURIComponent(pair[i][0])] = decodeURIComponent(pair[i][1]);
        }
        return request;
    }

    function ArrayToURL(array) {
        var pairs = [];
        for (var key in array)
            if (array.hasOwnProperty(key))
                pairs.push(encodeURIComponent(key) + '=' + encodeURIComponent(array[key]));
        return pairs.join('&');
    }

});