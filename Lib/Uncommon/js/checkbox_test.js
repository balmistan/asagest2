/*Gestisce la checkbox ris. ric. in abbinamento al metodo della classe form::addRisultSearch1()*/


$(function () {
    
    $(".rr_check").live("click", function(){
        
    var strchecked = 'off';
    if($(this).is(':checked')) strchecked = 'on';
    else strchecked = 'off';
    
    var oH=$(this).parent().find("input:hidden");
    
    $(oH).val(strchecked)
        
    //alert(oH.val())

    });  
    
});
