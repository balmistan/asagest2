$(document).ready(function(){
    
    var value="";
    
    $(".input_product").bind("focus", function(){
        value=$(this).val();
        $(this).val("");
    });
    
    $(".input_product").bind("blur", function(){
        if($(this).val() == "")
            $(this).val(value);
        
        var valid=true;
        $(".input_product").each(function(){
            var str=$(this).val()
            if(str.indexOf(",")!=-1 || !IsNumeric(str)) valid=false;
        })
        if(!valid){
            jAlert("Valore inserito non valido!");
        }
    });
    
    
    function IsNumeric(val) {
        if (isNaN(parseFloat(val))) {
            return false;
        }
        return true;
    }
    
});