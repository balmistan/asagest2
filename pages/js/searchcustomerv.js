$(function () {   
    var address="searchcustomer.php?opt=1";
    $("#rr_state, #inactivecheckbox").click(function(){   
        $("#rr_state, #inactivecheckbox").each(function(){
            var checkboxname=$(this).attr("name");      
            if($(this).is(':checked')) address+="&"+checkboxname+"=on";
            else address+="&"+checkboxname+"=off";  
            window.location = address;
        });  
    });   
}); // close $(function ()
