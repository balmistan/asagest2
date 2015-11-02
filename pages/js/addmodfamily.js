$(function() {
    $(".mtf_textfield").live("keydown", function(e) {
        var code = (e.keyCode ? e.keyCode : e.which)
        if (code == 220) {  //tasto \
            jAlert("Carattere non ammesso!");
            e.preventDefault();
            e.stopPropagation();
        }
    });
});


/////// FUNZIONI DI VALIDAZIONE /////////

$(function() {

    var ErrMsg = ""

//cliccando sul pulsante Salva verranno effettuate tutte le verifiche. Se tutto è ok, i dati verranno inviati in POST

    $("#Salva").click(function() {
        if (validateform())
            $("form").submit();
    });

    $("#Annulla").click(function() {
        window.location.href = "addmodfamily.php?fid=" + $("#idf").val();
    });



    function validateform() {
        var valid = true;

        //verifico se i campi cognome  tutti compilati    
        $(".lastname_tbox").each(function() {

            if ($(this).val() == "") {
                jAlert('Campi cognome non compilati.');
                //alert("Attenzione! Campi cognome non compilati.");
                valid = false;
                return false;        // esce semplicemente dall' each
            }
        })

        //verifico settaggio checkbox ris. ric
        var rr_checked = 0
        $(".rr_check:checked").each(function() {
            rr_checked++;
        });
        if (!rr_checked) {
            jAlert('Attenzione! Selezionare flag in corrispondenza della persona che viene a ritirare');
            valid = false;
        }

        if (valid) {
            //verifico se i campi cf  sono corretti 
            $(".cf_tbox").each(function() {

                $(this).focus(function() {                //rimuovo eventuale colorazione di errore.
                    $(this).removeClass("textboxerror");
                })

                var cf = $(this).val();
                if (cf != "") {

                    if (!validate_cf(cf)) {
                        $(this).addClass("textboxerror");
                        jAlert('Codice fiscale non corretto!');
                        //alert("Attenzione! Errore codice fiscale.");
                        valid = false;
                        return false;
                    }
                    else { //verifico la presenza di altre schede contenenti lo stesso codice fiscale

                        //catturo l' id family dal campo hidden del form
                        var idf = $("#idf").val();
                        //alert(idf)
                        $.ajax({
                            type: "POST",
                            url: "../Ajax/ajax_search_multiple_cf_presence_on_dbtable.php",
                            data: "familyid=" + idf + "&cf=" + cf,
                            async: false,
                            success: function(data) {
                                var arr = JSON.parse(data);
                                if (arr["familyid"] != 0) {
                                    valid = false;
                                    jAlert('Il codice fiscale: ' + cf + ' risulta presente nella scheda numero ' + arr["familyid"])
                                }
                            },
                            error: function(xhr, err) {
                                //jAlert(err + ": " + xhr.status, 'Errore!');
                            }
                        }); //close $.ajax
                    }//close else
                } // if(cf != "")
            }) // close  $(".cf_tbox").each(function()  
        }//close if(valid) 

        if (valid) {
            //verifico se i campi data di nascita sono corretti 
            $(".borndate_tbox").each(function() {
                $(this).focus(function() {                //rimuovo eventuale colorazione di errore.
                    $(this).removeClass("textboxerror");
                });

                if ($(this).val() != "" && !validate_borndate($(this))) {
                    $(this).addClass("textboxerror");
                    jAlert(ErrMsg, 'Errore!');
                    valid = false;
                    return false;
                }
            });
        }//close if(valid) 
        return valid;
    }


    function validate_borndate(obj_textbox) {
        var data_ins = $(obj_textbox).val();
        var cf_ins = $(obj_textbox).parent().parent().find(".cf_tbox").val();   //sulla stessa riga c' è un solo campo cf

        //verifico correttezza del formato data
        if (!controllo_data(data_ins)) {
            ErrMsg = "Attenzione! Formato data non corretto.";
            return false;    //formato data non corretto
        }

        //ottengo la data odierna
        var dateod = new Date();
        var dd = dateod.getDate();
        if (dd < 10)
            dd = "0" + dd;
        var mm = dateod.getMonth() + 1;
        if (mm < 10)
            mm = "0" + mm;
        var yyyy = dateod.getFullYear();
        var today = dd + "/" + mm + "/" + yyyy;

        //verifico che la data inserita sia minore o uguale alla data odierna
        if (confronta_data(today, data_ins) > 0) {
            ErrMsg = "Attenzione! Data di nascita inserita maggiore della data odierna.";
            return false;  //data di nascita maggiore della data attuale
        }

        //se è stato compilato il campo CF faccio il match della data di nascita con quest' ultimo
        if (cf_ins != "") {
            if (!matchCfData(cf_ins, data_ins)) {
                ErrMsg = "Attenzione! Data di nascita e CF incompatibili";
                return false;
            }
        }
        return true;
    }

    function matchCfData(cf, strdata) {
        var arr_month = {"A": "01", "B": "02", "C": "03", "D": "04", "E": "05", "H": "06", "L": "07", "M": "08", "P": "09", "R": "10", "S": "11", "T": "12"};

        var cf_gg = cf.substr(9, 2);
        if (cf_gg > 40)
            cf_gg -= 40;   //se si tratta di una donna il giorno di nascita risulta aumentato di 40
        var cf_mm = arr_month[cf.substr(8, 1).toUpperCase()];
        var cf_aa = cf.substr(6, 2);

        //eseguo il confronto:
        if (cf_gg != strdata.substr(0, 2) || cf_mm != strdata.substr(3, 2) || cf_aa != strdata.substr(8, 2))
            return false;
        return true;
    }

    function controllo_data(stringa) {
        var espressione = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
        if (!espressione.test(stringa))
        {
            return false;
        } else {
            anno = parseInt(stringa.substr(6), 10);
            mese = parseInt(stringa.substr(3, 2), 10);
            giorno = parseInt(stringa.substr(0, 2), 10);

            var data = new Date(anno, mese - 1, giorno);
            if (data.getFullYear() == anno && data.getMonth() + 1 == mese && data.getDate() == giorno) {
                return true;
            } else {
                return false;
            }
        }
    }



    function confronta_data(data1, data2) {

        //trasformo le date nel formato aaaammgg (es. 20081103)
        data1str = data1.substr(6) + data1.substr(3, 2) + data1.substr(0, 2);
        data2str = data2.substr(6) + data2.substr(3, 2) + data2.substr(0, 2);
        //restituisco differenza date
        if (data2str == data1str)
            return 0;
        if (data2str > data1str)
            return 1;
        return -1;
    }


    function validate_cf(cf)
    {
        cf = cf.toUpperCase();   //converto stringa c.f. in maiuscolo
        if (cf.length == 0)
            return true;
        var cffilter = /^[A-Z]{6}[\d]{2}[A-Z][\d]{2}[A-Z][\d]{3}[A-Z]$/;

        if (!cffilter.test(cf))
            return false;

        //eseguo la validazione
        var set1 = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        var set2 = "ABCDEFGHIJABCDEFGHIJKLMNOPQRSTUVWXYZ";
        var setpari = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        var setdisp = "BAKPLCQDREVOSFTGUHMINJWZYX";
        var s = 0;
        for (i = 1; i <= 13; i += 2)
            s += setpari.indexOf(set2.charAt(set1.indexOf(cf.charAt(i))));
        for (i = 0; i <= 14; i += 2)
            s += setdisp.indexOf(set2.charAt(set1.indexOf(cf.charAt(i))));

        if (s % 26 != cf.charCodeAt(15) - 'A'.charCodeAt(0)) {   //C.F. non corretto
            return false;
        }
        return true;
    }

}); //close $(function () {



