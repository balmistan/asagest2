

function validate_piva(p_iva)
{
  
   if(p_iva.length==0) return true;
   
   var pivafilter= /^[0-9]{11}$/;

	if(!pivafilter.test(p_iva))  return false;

    var s = 0;
    var c=0;
    
    for( i = 0; i <= 9; i += 2 )
        s += p_iva.charCodeAt(i) - '0'.charCodeAt(0);
    for( i = 1; i <= 9; i += 2 ){
        c = 2*( p_iva.charCodeAt(i) - '0'.charCodeAt(0) );
        if( c > 9 )  c = c - 9;
        s += c;
    }
    if(  (p_iva=="00000000000") ||( ( 10 - s%10 )%10 != p_iva.charCodeAt(10) - '0'.charCodeAt(0) ) ){ //p. iva non corretta
    	
    	return false;
    }
    
    return true;
        
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////


function validate_cf(cf)
{
	
	cf=cf.toUpperCase();   //converto stringa c.f. in maiuscolo
	
	if(cf.length==0) return true;

	var cffilter= /^[A-Z]{6}[\d]{2}[A-Z][\d]{2}[A-Z][\d]{3}[A-Z]$/;

	if(!cffilter.test(cf))  return false;
	
	
   //eseguo la validazione
   var set1 = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
   var set2 = "ABCDEFGHIJABCDEFGHIJKLMNOPQRSTUVWXYZ";
   var setpari = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
   var setdisp = "BAKPLCQDREVOSFTGUHMINJWZYX";
   var s = 0;
   for( i = 1; i <= 13; i += 2 )
       s += setpari.indexOf( set2.charAt( set1.indexOf( cf.charAt(i) )));
   for( i = 0; i <= 14; i += 2 )
       s += setdisp.indexOf( set2.charAt( set1.indexOf( cf.charAt(i) )));
		
   if( s%26 != cf.charCodeAt(15)-'A'.charCodeAt(0) ) {   //C.F. non corretto
	   
	   return false;
	   
   }

   return true;
}










