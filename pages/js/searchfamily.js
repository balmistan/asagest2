$(function() {
    var address = "searchfamily.php?opt=1";
    $("#rr_state, #inactivecheckbox").click(function() {
        $("#rr_state, #inactivecheckbox").each(function() {
            var checkboxname = $(this).attr("name");
            if ($(this).is(':checked'))
                address += "&" + checkboxname + "=on";
            else
                address += "&" + checkboxname + "=off";
            window.location = address;
        });
    });
}); // close $(function ()


////// Premendo il tasto invio effettuo redirect prima riga tabella

(function($) {
    $('body').live("keyup", function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            var id = $("#datatable tbody").find("tr:first").find("td:last").html()
            window.location.href = "viewfamily.php?fid="+id;
        }
    });
})(jQuery);