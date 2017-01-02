$(document).ready(function () {
    var is_new_load = false;


    $(".input_product").focus(function () {
        if ($(this).val() == '0')
            $(this).val("");
    });

    $(".input_product").blur(function () {
        var str = $(this).val();
        if (str == "")
            $(this).val("0");
    });

    popola($("#date").val());

    $("#date").datepicker({
        //  maxDate: '0',
        onClose: function (date) {
            popola(date);
        }
    });


    $("#menu li a").click(function () {
        $("#date").val($(this).html());
        popola($(this).html());
    });


    $("#Rimuovi").click( function () {
        var r = confirm("Si sta per rimuovere il carico del " + $("#date").val() + "\n Proseguire?");
        if (r) {

            $.ajax({
                type: 'POST',
                url: "../Ajax/ajax_loadremove.php",
                data: {
                    idload: $("#insert_id").val()
                },
                success: function (resp) {

                    $(location).attr('href', 'load');
                }
            });

        }
    });




    function popola(date) {

        var res;
        $.ajax({
            type: 'POST',
            url: "../Ajax/ajax.load.php",
            data: {
                date: date
            },
            success: function (resp) {

                res = JSON.parse(resp);
                $("#insert_id").val("");
                $("#numrif").val("");
                if (res.length == 0) {
                    $(".input_product").val("0");    //resetto le input text
                    $("#msg").html("<br /><br />Nessun carico salvato con questa data");
                    $("#msg").addClass("icon_new");
                    $("#Rimuovi").hide();
                    $("#Salva").show();
                    is_new_load = true;           //si tratta di nuovo carico e non modifica di uno preesistente.
                } else {
                    $("#msg").html("");
                    $("#msg").removeClass("icon_new");
                    $("#Rimuovi").show();
                    $(".input_product").val("0");    //resetto le input text           
                    $("#insert_id").val(res['id_insert']);
                    $("#numrif").val(res['numrif'])

                    for (var i = 0; i < (res['products'].length); i++) {
                        //alert(res['products'][i]['id_product']+" ----> "+res['products'][i]['carico'])
                        $("[name='load_" + res['products'][i]['id_product'] + "']").val(res['products'][i]['carico']);
                    }
                }
            }
        });
    }

    $('#inputform').submit(function (e) {

        var checkval = true;
        //blocco il submit se la data con cui si sta salvando è inferiore a l' ultima inserita sui registri (solo se non si tratta di modifica)
        /*      if ($("#insert_id").val() == "") { //Se si tratta di nuovo salvataggio, non di modifica
         
         $.ajax({
         type: 'POST',
         url: "../Ajax/ajax_allegaticheck.php",
         async: false,
         data: {
         'checktype': 'check_date',
         'datecheck': $("#date").val()
         },
         success: function (resp) {
         
         if (resp < 0) {
         e.preventDefault();
         alert("Non è possibile salvare in quanto sono state già effettuate operazioni con data superiore a quella indicata sul carico.", "ATTENZIONE!");    
         }
         
         }
         });
         }
         */
        if (!checkval)
            return false;

        var valid = true;
        $(".input_product").each(function () {
            var str = $(this).val()
            if (str.indexOf(",") != -1 || !IsNumeric(str))
                valid = false;
        })
        if (!valid) {
            alert("Ci sono campi non validi. Correggere e riprovare!");
            return false;
        }


        //return confirm('Verificare che i campi siano compilati correttamente perchè non è possibile modificare dopo aver salvato. Cliccare su OK per completare il salvataggio.')
        return true;
    })


    function IsNumeric(val) {
        if (isNaN(parseFloat(val))) {
            return false;
        }
        return true;
    }




})
