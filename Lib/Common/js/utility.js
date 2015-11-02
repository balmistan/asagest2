/* converte il primo carattere di ogni parola maiuscolo mentre il resto della parola in minuscolo.
* va abbinato alla textbox così:
* 
*/	
	
	function first_upper_case(event, object)
	  {
       
	    var mystr=object.value;
	    
	    
	    var str_arr=Array();
	    
	    if(mystr=="") return 0;
	    
	    str_arr=mystr.split(" ");
	    
	    var i=0;
	    
	    mystr="";
	    
	    for(i=0; i<str_arr.length; i++){
	    	
	    	/*mystr += str_arr[i].substr(0,1).toUpperCase()+ str_arr[i].substring(1).toLowerCase(); */
	    	mystr += str_arr[i].substr(0,1).toUpperCase()+ str_arr[i].substring(1);
	    	if(i!=str_arr.length-1)mystr +=" ";  /*alla fine non aggiungo lo spazio*/
	    }
	    
	    object.value=mystr;
	    return 0;
	    
	    
	    var ric=mystr.lastIndexOf(" ");
	    if(object.value.lenght!=0) lastchar=object.value.substr(object.value.lenght-1, 1);
	    
	    if (mystr.length==1) mystr=object.value.substr(0,1).toUpperCase();
	    else if (mystr.length>1) {
	                          if(ric==-1) mystr= ((object.value.substr(0,1).toUpperCase()) + object.value.slice(1).toLowerCase());

	                          else mystr=object.value.substr(0,ric+1)+ object.value.substr(ric+1,1).toUpperCase() + object.value.slice(ric+2).toLowerCase();
                      }

	    object.value=mystr;  
	    
	    return 0;
 }   
	
	
	
	
//funzioni per cambiare style css via js//////////		
	function setStyle(obj,style,value){
		getRef(obj).style[style]= value;
		}

		function getRef(obj){
		return (typeof obj == "string") ?
		document.getElementById(obj) : obj;
		}	

		
		
function dataconvert(data){ //Converte la data da formato yyyy-mm--dd  a dd/mm/aaaa //e viceversa
	if(data.indexOf("-"!=-1)) return data.substr(8,2)+"/"+ data.substr(5,2)+"/"+data.substr(0,4);
       // alert(data.substr(6,4)+"-"+ data.substr(4,2)+"-"+data.substr(0,2));
}	



function rnd(link){                                 
	var casuale=Math.floor(Math.random()*100+1);
	
	return link+"?rand="+casuale;
	}

//verifica se nella pagina esiste un elemento con l' id specificato

function elementExists(id) {
	var el = document.getElementById(id);
 
	if (el != null) {
		return true;
	}
 
	return false;
}


