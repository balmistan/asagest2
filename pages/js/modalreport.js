$(document).ready(function(){
    
    var sig = $("#signature").val()
    
    sig = decodeURIComponent(sig);
    
   // alert(sig)
    
   $('.sigWrapper').signaturePad({
        displayOnly:true,
        lineTop:45,
        validateFields : false
    }).regenerate(sig);
    
});