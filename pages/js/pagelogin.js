$(document).ready(function(){     
    $(".sheet").css("cursor","handle");	
    $(".sheet").draggable({
        containment: "window"
    });
		
    $("#username").focus()   //focus iniziale su username     
    $("input:submit").button();
	
    $(document).keydown(function(event) {
        if(event.keyCode>=37  && event.keyCode<=40){
            switch(event.keyCode){
                case 38:                           //button up
                    $("#username").focus()
                    break;
                case 40:                          //button down
                    $("#password").focus()
                    break;  
                default:
                    break;
            }// close switch
       	  	
            event.preventDefault();  //se siamo nell' if è necessariamente uno dei 4 casi
    
        }// close if(last_obj_moved, ecc)

    })// close $('body').keydown(function(event)


    // GESTIONE GUIDA PDF

    var width =  $(window).width() * 0.8;  //80% in larghezza
    var height = $(window).height();
    var fname = "";

    //$('.gdocsviewer').dialog({ autoOpen: false });


    $('a.embed').bind('click',function(e){
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        fname = $(this).html();
   
        $(this).gdocsViewer({
            'height': height,
            'width' : width
        });

        $('.gdocsviewer').css('opacity', 0.3);
        var code = $('.gdocsviewer').html();            
        $('.gdocsviewer').remove();         
        $('#gdocsviewer_out').html(code)
        .css('overflow', 'hidden')
        .dialog({
            'title' : fname,
            'height': height -20,
            'width' : width +20,
            'resizable' : false
        });
    })
})