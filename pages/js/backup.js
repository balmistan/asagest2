$(document).ready(function(){
    $(".link_icon_delete").live("click", function(e){      
       e.preventDefault();
       e.stopPropagation();
       e.stopImmediatePropagation();
        //chiedo conferma prima di cancellare
        var confirmed = confirm("Rimuovere il backup?");
           
        if(confirmed){ 
            
              $(location).attr('href', $(this).attr("href"));         
        }
        
    });
    
    $("#link_deleteall").live("click", function(e){      
       e.preventDefault();
       e.stopPropagation();
       e.stopImmediatePropagation();
        //chiedo conferma prima di cancellare
        var confirmed = confirm("Rimuovere tutti i backup?");
           
        if(confirmed){ 
           
              $(location).attr('href', $(this).attr("href"));         
        }
        
    });
    
});