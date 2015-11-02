$(document).ready(function(){
    addSeparator();
   
    $( ".blockicons" ).sortable({
        stop: function( event, ui ){
            var sortedIDs = $( this ).sortable( "toArray" );     
            //alert(JSON.stringify(sortedIDs))
            update(JSON.stringify(sortedIDs)) 
            addSeparator()
        }
    });
    
    function addSeparator(){
        var i=1;
        $( ".blockicons li .item" ).removeClass("limit");
        $( ".blockicons li .item" ).each(function(){
            if(!(i%4)) $(this).addClass("limit");
            i++;
        })
    }
    
    function update(str){
       
        $.ajax({
            type: "POST",
            url: "../Ajax/ajax.sortproducts.php",
            cache:false,
            data: {
                'str': str
            }
        }).done(function( msg ) {
         
        });
    }
    
    
    
    
    
})