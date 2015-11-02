var color=Array("red","green","yellow","orange","violet","blue","pink","gray","brown","purple","#7FFFD4","#FFD700","#7DF9FF",
 "#EDC9AF","#F88379","#4B0082","#FF00FF","#40826D","#9CDEAF","#83F879","#004B82","#00FFFF","#82406D",
 "#EDAFC9","#F87980","#4B8200","#FFAF00","#40826D","#9CDEAF","#83F879","#004B82","#00FFCC","#824080");
 

 
 function rand_color(options){
   var num = Math.round((options.length)*Math.random());
   return options[num];
 }

 MySimpleWidget = function(element_id,jsonvalues,btnclass) {
   this.init(element_id,jsonvalues,btnclass);
}

$.extend(MySimpleWidget.prototype, {
   // object variables
  
   debug:false,
   rowindexcount:0,   //contatore indice righe
   buttonclass:'',    //eventuale attributo class per pulsanti
   objrow:null,       //oggetto per indicizzazione riga
   element_id:'',     //id dell' elemento da cui si è partiti da anteporre al nome degli attributi class creati dalla classe jquery
   jsonvalues:'',
   stringcode:'',
   arr:null,
   
   useDebug:function(){
     this.debug=true
   },

 
 init: function(element_id,jsonvalues,btnclass) {
   	
   	if(btnclass!="") this.buttonclass=" "+btnclass
   	
   	this.arr=new Array()
   	
   	this.element_id=element_id
   	
   	this.jsonvalues=jsonvalues
   	
   	this.objrow=$('#'+this.element_id).closest("tr")
    
    $(this.objrow).find('td').each(function() {
    $(this).removeAttr('id');
    $(this).children().removeAttr('id');
    });
    
    this.stringcode=$(this.objrow).html()
 
    
   	$(this.objrow).append('<td><input type="button" class="'+this.element_id+'btnremove'+this.buttonclass+'" id="'+this.element_id+'btnremove_0" value="X" title="Rimuovi riga" /></td>'+
   		'<td><input type="button" class="'+this.element_id+'btndown'+this.buttonclass+'" id="'+this.element_id+'btndown_0" value="&#9661" title="Aggiungi" /></td>')
  
    this.arr.push((this.objrow).closest("tr"))
    
    this.listen(this);               //attiva handle eventi
    
    this.addRemoveButton()
    
    this.setValues(this, this.jsonvalues)
    
   },  //close init
   
 listen: function(obj){
   $('.'+this.element_id+'btndown').live("click",function(){
    	
    	obj.addRow($(this).attr("id"))
    
    	
    })
    
     $('.'+this.element_id+'btnremove').live("click",function(){
    	obj.removeRow($(this).attr("id"))
    	
    })
   
 }, //close listen
 
removeRow: function(textid){
	var id=textid.substring((textid.lastIndexOf("_"))+1)
	$(this.arr[id]).remove()
},
 
 addRow: function(textid){
 	
 	this.rowindexcount++
 	
 	var id=textid.substring((textid.lastIndexOf("_"))+1)

 	$(this.arr[id]).after('<tr>'+this.stringcode+
 	'<td><input type="button" class="'+this.element_id+'btnremove'+this.buttonclass+'" id="'+this.element_id+'btnremove_'+this.rowindexcount+'" value="X" title="Rimuovi riga" /></td>'+
    '<td><input type="button" class="'+this.element_id+'btndown'+this.buttonclass+'" id="'+this.element_id+'btndown_'+this.rowindexcount+'" value="&#9661" title="Aggiungi" /></td>'+ 
 	'</tr>')
 	
 	 
 	this.arr.push((this.arr[id]).next("tr"))
 	
 	this.addRemoveButton()
 },
 
 addRemoveButton: function(){
 	 var count=-1
 	 var last=null
 	 
     $("."+this.element_id+"btnremove").each(function(){
       $(this).removeAttr("disabled");
       count++;
     });    
     if(count==0)  $("."+this.element_id+"btnremove").attr("disabled","disabled");  
     
     $("."+this.element_id+"btndown").each(function(){
     	last=$(this)
       $(this).hide();
     })
      
     $(last).show()
      	
 },
 
 setValues: function(obj,jsonv){         //in fase di inizializzazione aggiunge righe popolandole con i valori
 	
 	var v_arr=JSON.parse(jsonv)
 	var total_rows=0               //numero di righe da aggiungere automaticamente
 	
 	if(v_arr[0]!=undefined) total_rows=v_arr[0].length
 	
 	for(var i=0; i<total_rows-1; i++){            //total_rows-1 perchè una riga è già presente. Ne va aggiunta quindi una in meno
 		this.addRow(this.element_id+'btndown_'+i)     //equivale a click su pulsante aggiungi 
 	  }
 	
 	//scorro l' array degli oggetti riga cercando su ciascun elemento eventuali attributi name
 	
 	for(var i=0; i<obj.arr.length; i++){      //for 2
 		var c=0;
 		$(obj.arr[i]).find("input[name]").each(function(){     //prendo ogni elemento con tag input che ha un attributo name
 		    if(v_arr[c][i]!=undefined) $(this).val(v_arr[c++][i])
 		  })
      }//close for 2
 }// close setValues
    
});

  
 