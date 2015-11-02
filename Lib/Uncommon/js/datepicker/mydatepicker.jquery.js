(function($) {
    $.fn.mydatepicker = function(options) {
        // valori di default
        var config = {
            dateFormat: 'dd/mm/yy',
               firstDay: 1,
               showButtonPanel: true,
               showAnim: 'fadeIn',
               showSpeed: 'slow',
               beforeShow: function( input ) {
                                  setTimeout(function() {
                                           var buttonPane = $(input).datepicker('widget').find('.ui-datepicker-buttonpane');
          
                                           $('<img>', {                                                    
                                                     src: '".LIBSSDIR."icons/clr.png',
                                                     text: 'Azzera data',
                                                     click: function() {
                                                                $.datepicker._clearDate( input ); //cancello data e comunico al server
                                                        }
                                                     }).appendTo( buttonPane );
                                        }, 1 );
                                   }
        };
 
        if (options) $.extend(config, options);
 
        this.each(function() {
            $(this).attr('readonly','readonly');
            $(this).datepicker(config);
        });
 
            return this;
 
    }
})(jQuery);



jQuery.sendtoserver = function(strn,ajaxpage,debug) {
    //alert(strn);
       $.ajax({                               
         type: 'POST',
         url: ajaxpage,
         data: strn,
         cache: false,
         async:false,
         success: function(msg){
             if(debug) alert(msg);
         } //close success
       });//close $.ajax
};




