$(document).ready(function(){  
    
    var availableDates = getdate();
    
    $("#date").datepicker({ 
        beforeShowDay: available,
        onClose: function(date){
            $(window.location).attr('href', '?date='+encodeURIComponent($("#date").val()));
        }
    });
    
 
    function getdate(){
       var arr_ret=Array();
        $.ajax({
            type: "POST",
            url: "../Ajax/ajax.distributiondate.php",
            cache:false,
            async:false,
            data: {
                'requestfrom': 'all9'          
            }
        }).done(function( msg ) {
             arr_ret = JSON.parse(msg);
            
        });
        return arr_ret;
    }
    
    
    function available(date) {
     
     var year = date.getFullYear();
     var month = date.getMonth()+1;
     if(month<10) month = "0"+month;
     var day = date.getDate();
     if(day<10) day = "0"+day;
    
  var dmy = year + "-" + month + "-" +day;
 
  if ($.inArray(dmy, availableDates) != -1) {
    return [true, "","Available"];
  } else {
    return [false,"","unAvailable"];
  }
}
   
    
 
});