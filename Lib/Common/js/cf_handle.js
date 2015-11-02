var debug=false;


var consonanti =  "bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ";
var numeri = "0123456789";
var arrMese=Array(
Array("A","1"),
Array("A","01"),
Array("B","2"),
Array("B","02"),
Array("C","3"),
Array("C","03"),
Array("D","4"),
Array("D","04"),
Array("E","5"),
Array("E","05"),
Array("H","6"),
Array("H","06"),
Array("L","7"),
Array("L","07"),
Array("M","8"),
Array("M","08"),
Array("P","9"),
Array("P","09"),
Array("R","10"),
Array("S","11"),
Array("T","12"));

function CalcolaCodiceFiscaleCompleto(Cognome,Nome,GiornoNascita,MeseNascita,AnnoNascita,Sesso,Luogo)
    {
   if (Cognome != "" && Nome != "" && Luogo != "")
   {
      
      rc = CalcolaCodiceFiscaleCognome(Cognome);
      rn = CalcolaCodiceFiscaleNome(Nome)
      rN = CalcolaNascita(GiornoNascita, MeseNascita, AnnoNascita, Sesso);

      var cf = rc+rn+rN+Luogo;
      
      cf +=CalcolaK(rc+rn+rN+Luogo);
      
      return cf;
   }
}

function CalcolaCodiceFiscaleCognome(Cognome)
{
   var code = "";
   code = GetConsonanti(Cognome);
   if (code.length >= 3)
      code = code.substring(0, 3);
   else
   {
      code += GetVocali(Cognome).substring(0, 3 - code.length)
      if (code.length < 3)
         for (i = code.length; i < 3; i++)
            code += "X";
   }
   return code;
}

function CalcolaCodiceFiscaleNome(Nome)
{
   var code = "";
   cons = GetConsonanti(Nome);
   if (cons.length > 3)
      code = cons.substring(0, 1) + cons.substring(2, 3) + cons.substring(3, 4);
   else if (cons.length == 3)
      code = cons;
   else
   {
      code = cons + GetVocali(Nome).substring(0, 3 - cons.length);
      if (code.length < 3)
         for (i = code.length; i < 3; i++)
            code += "X";
   }
   return code;
}


function getEta(borndate)                //    gg/mm/aaaa
{
	var currentTime = new Date()
	
	var curr_day=currentTime.getDate();
	var curr_month=currentTime.getMonth() + 1;
	var curr_year=currentTime.getFullYear();
	
	var arr_borndate=borndate.split("/");
	
	var age=parseInt(curr_year, 10) - parseInt(arr_borndate[2], 10);
	if ( curr_month < (arr_borndate[1]) ) age --;
	if (((arr_borndate[1]) == curr_month) && (curr_day < arr_borndate[0])) age--;
	
	if(age==0)age="<1"
	
	return age;
	
}

function getSexByCf(cf){
    var bornday=cf.substr(9, 2);
    if (bornday>"40") return 'F';
    return 'M';
}

// SNG DLM 78 E 15 A 638 O

function getSuggDateByCF(cf){
    cf=cf.toUpperCase();
    /*FACCIO ALCUNE VERIFICHE SUL CF INSERITO PRIMA DI PROSEGUIRE*/
    if(cf.length!=16) {
        if(debug) alert("lunghezza cf non corretta");
        return null;
    }
    
    var year_2 = cf.substr(6, 2);
    
    var arr_mounth={"A":"01", "B":"02", "C":"03", "D":"04", "E":"05", "H":"06", "L":"07", "M":"08", "P":"09", "R":"10", "S":"11", "T":"12"};

    var mounth=arr_mounth[cf.substr(8, 1)];
    
    if(mounth==undefined){
        if(debug) alert("mese indicato nel cf non corretto");
        return null;
    }
    
    var day=parseInt(cf.substr(9, 2), 10);
   
    
    if(day<1 || day>71){
        if(debug) alert("giorno indicato nel cf non corretto");
        return null;
    }
    
    if (day>40) day-=40;
    
    var currentTime=new Date();
   var curr_year=currentTime.getFullYear(); 
    
   var retval_year="";
   
   if(("20"+year_2)>curr_year) retval_year= "19"+year_2;
   else if(parseInt(curr_year, 10) - parseInt("19"+year_2, 10) >=100) retval_year="20"+year_2;
   else retval_year="19"+year_2;
   
   if(day<10)day="0"+day;
   if(debug) alert(day + "/" + mounth + "/" + retval_year);
   return day + "/" + mounth + "/" + retval_year;
    
}






