jQuery.mywebcam = function(options) {   
    var smart_url="";          //nome del file immagine
    var full_url="";           //nome del file immagine comprensivo di path
    // valori di default
    var config = {
        'save_folder': 'tmp1',
        'nophoto':'',
        'title':''
    };                                          
 
    if (options) $.extend(config, options); 
    webcam.set_api_url( 'webcam.php?save_folder='+options['save_folder'] );
    webcam.set_swf_url( '../Plugin/jpegcam/webcam.swf' );
    webcam.set_quality( 90 ); // JPEG quality (1 - 100)
    webcam.set_shutter_sound( false, '../Plugin/jpegcam/shutter.mp3' ); // play shutter click sound        
    webcam.set_stealth( false );
    webcam.set_hook( 'onComplete', function(msg){
        my_completion_handler(msg);
    });
                
    var freezed=false; 
    var image_url='';
    var obj_thumb=null;
    var obj_thumb_link=null;    //l' oggetto hidefield contenente il link all' icona.   
    var jcrop_api, boundx, boundy, w, h, x, y;
    var btns1=[
    {
        text: "No Foto",
        click: function() { 
            nophoto();
        }
    },
    {
        text: "Setup",
        click: function() { 
            webcam.configure();
        }
    },  
    {
        text: "OK",
        click: function() { 
            $(this).dialog("close"); 
        }
    },                     
    {
        text: "Scatta",
        click: function() { 
            cheese();
        }
    }
    ];
                
    var btns2=[
    {
        text: "No Foto",
        click: function() { 
            nophoto();
        }
    },
    {
        text: "Setup",
        click: function() { 
            webcam.configure();
        }
    },                     
    {
        text: "OK",
        click: function() { 
            $(this).dialog("close"); 
        }
    },                  
    {
        text: "Riprova",
        click: function() { 
            cheese();
        }
    }
    ];
                                     
    $("#content_webcam").dialog({
        width:'auto',
        position: "top",
        resizable: false,
     /*   autoOpen: true,
        
        create: function(event, ui) {
                setInterval(function(){
                    $('#content_webcam').dialog("close");
                    //alert('2')
            },5000) 
            },   */
        
        show: {
            effect: 'drop', 
            direction: "up"
        },
        autoOpen: false,
        title: options['title'],
        buttons: btns1,
                   
        beforeClose: function(event, ui) { 
            $("#webcam").remove();           //useful for webcam turn off
            $("#content_webcam").dialog( "option", "buttons", btns1); 
            saveResized(); //save resized image 
            freezed=false; 
        },
        open: function(event, ui) { 
            turn_on();
            $('.ui-dialog-buttonpane button:eq(3)').focus();
        }
                                      
    });
                                           
    function cheese(){
        if(!freezed){ 
            webcam.snap();
            $("#content_webcam").dialog( "option", "buttons", btns2);
        } else {
            turn_on();
            $("#content_webcam").dialog( "option", "buttons", btns1);
        }
        freezed=!freezed;
    }
                
    function turn_on(){
        $("#content_webcam").html('');                
        $("#content_webcam").append("<div id=\"webcam\"></div>");             
        $("#webcam").html( webcam.get_html(320, 240));                   
    }
               		
    function my_completion_handler(msg) {       
        // extract URL out of PHP output
        if (msg.match(/(http\:\/\/\S+)/)) {
            image_url = RegExp.$1;       
            smart_url=image_url.substring(1+image_url.lastIndexOf("/"));
            full_url=options['save_folder']+"/"+smart_url;
            $("#webcam").remove();
            $("#content_webcam").append("<div id=\"image_cover\"></div>");
            $('#image_cover')
            .Jcrop({
                onChange: updatePreview,
                onSelect: updatePreview,
                aspectRatio: 1,
                minSize: [64, 64]
            },function(){
                // Use the API to get the real image size
                var bounds = this.getBounds();
                boundx = bounds[0];
                boundy = bounds[1];
                // Store the API in the jcrop_api variable
                jcrop_api = this;
                jcrop_api.setSelect([50, 10, 220, 220]);
            }).css("background-image", "url(image_wrapper.php?url="+full_url+")");

            $(obj_thumb).attr("src", "image_wrapper.php?url="+full_url);
        }
        else alert("PHP Error: " + msg);
    }
                           
    function updatePreview(c){
                
        if (parseInt(c.w) > 0)
        {
            var rx = 64 / c.w;
            var ry = 64 / c.h;
            w = c.w;
            h = c.h;
            x = c.x;
            y = c.y;

            $(obj_thumb).css({
                width: Math.round(rx * boundx) + 'px', 
                height: Math.round(ry * boundy) + 'px',
                marginLeft: -Math.round(rx * c.x) + 'px',
                marginTop: -Math.round(ry * c.y) + 'px'
            });
        }
    }
                
    function saveResized(){
        if(image_url==undefined) return;
        $.ajax({
            type: 'POST',
            url: "cropimage.php",
            data: {
                'save_folder': options['save_folder'], 
                'imagename': smart_url, 
                'x':x, 
                'y':y, 
                'w':w, 
                'h':h
            },
            success: function(resp){
                if(resp=="NOLOGGED") window.location.href = "index.php?logout=1";
                $(obj_thumb_link).val(resp); //setto il campo hidden col link all' immagine
            }
        });
    }
                
    $(".photodiv").live("click", function(){
        obj_thumb=$(this).find('img');     //l' oggetto icona su cui ho effettuato il click.
        image_url=$(this).attr('src');       
        obj_thumb_link=$(this).parent().find('.hfield_imglink');
        $("#content_webcam").dialog('open');            
    });
                
    //setto i link alla thumb in base al valore del campo hidden
                
    $(".hfield_imglink").each(function(){
        var hval=$(this).val();
        if(hval!=""){
            //setto il link sull' immagine
            $(this).parent().find(".photodiv img").attr("src", "image_wrapper.php?url="+options['save_folder']+"/"+hval)
        }
    }) 
               
    function nophoto(){
        $(obj_thumb).attr("src", options['nophoto'])
        .css({
            width:  '64px', 
            height: '64px',
            marginLeft: 0,
            marginTop: 0
        });
        $(obj_thumb_link).val("");
    }  
    
    
    $(".ui-widget-overlay").live("click", function() { 
        $("#out").dialog("close"); } );
    
    
}    


