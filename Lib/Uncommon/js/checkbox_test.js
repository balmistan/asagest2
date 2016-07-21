/*Gestisce la checkbox ris. ric. in abbinamento al metodo della classe form::addRisultSearch1()*/


$(function () {
    
    $("tr").on("click", ".rr_check", function(){
        
    var strchecked = 'off';
    if($(this).is(':checked')) strchecked = 'on';
    else strchecked = 'off';
    
    var oH=$(this).parent().find("input:hidden");
    
    $(oH).val(strchecked)
        
    //alert(oH.val())

    });  
    
});
