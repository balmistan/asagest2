(function ($) {
    var fast_search_html = "<div id=\"fastsearchdiv\"><input type=\"text\" id=\"searchtext\" value=\"\" /></div>";
    $(document).bind('keydown', '', function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        //alert(code)
        switch (code) {
            case 17:              //Ctrl
                if (!$("#fastsearchdiv").length) {
                    $("body").append(fast_search_html);
                    $("#fastsearchdiv #searchtext").focus();
                    setTimeout(function () {
                        var digvalue = parseInt($("#fastsearchdiv #searchtext").val());
                        if (!isNaN(digvalue)) {
                            window.location.href = "viewfamily.php?fid=" + digvalue;
                        }
                        $("#fastsearchdiv").remove();

                    }, 3000);
                }
                break;
            default:
                break;
        }
    });
})(jQuery);
