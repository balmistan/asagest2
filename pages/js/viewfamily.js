$(document).ready(function(){
    
    $("#distr_btn").focus().click(function(){
        var pid = $(".pid").first().val();
        window.location.href = "block?pid="+pid;
    });
            
    
    $(".cf_tbox").each(function(){ // per ogni riga leggo il cf e la data di nascita inserite
        var cf = $(this).val();
        var objbdate = getElementObjByCfObj($(this), "borndate_tbox");
        var born_date = $(objbdate).val();
        //ottengo l' oggetto div con class sexeta
        var sexeta_obj=getElementObjByCfObj($(this), "sexeta");
        //ottengo l' oggetto label corrispondente
        var sexeta_label_obj=$(sexeta_obj).parent().find("label");

        if(cf!=''){
            var sex=getSexByCf(cf);
            
            if(sex=='M'){
                $(sexeta_obj).addClass('bck_blue')
            }else if(sex=='F'){
                $(sexeta_obj).addClass('bck_pink')
            }
            $(sexeta_label_obj).html("Età");
        }//close if(cf!='')
         
        if(born_date!=''){
            var eta=getEta(born_date);
            $(sexeta_obj).html(eta);
        }//close if(borndate!='')
        else{
            $(sexeta_obj).html('&nbsp;&nbsp;');
            } //in modo da garantire la distanza a destra in ogni caso per lo scroll da tablet
    }); //close $(\".cf_tbox\").each(function()
    
    function getElementObjByCfObj(cf_obj, class_of_element_searched){
        var parent_elem=$(cf_obj).parent().parent();  
        var objelem=null;
        $(parent_elem).find("input,div").each(function(){     
            if($(this).hasClass(class_of_element_searched)) {
                objelem = $(this); 
            }      
        }); //close $(parent_elem).find(\"input\").each(function()
        return objelem;
    }

    //$("#change, #report").button();
    
    $("#change").click(function(){    
        $(location).attr('href','addmodfamily?fid='+$("#idfamily").val());
    });
    
    $("#report").click(function(){    
        $(location).attr('href','report?mode=1');
    });
    
    $(".lk").click(function(){
        $(location).attr('href','block?pid='+$(this).parent("tr").find(".pid").val());
    });
    
    
    $(".lk").mouseover(function(){
        $(this).parent().addClass("blue");
    });
    
    $(".lk").mouseout(function(){
        $(this).parent().removeClass("blue");
    });
    
    var val_comp="";
    
    $(".topb").click(function(){
        var keyv=$(this).attr("keyv");
        if(keyv=="canc"){
            val_comp="";
        }else if(keyv=="invio"){
            if(val_comp!="")
                $(location).attr('href', "viewfamily?fid="+val_comp);
        }else{
            var cifra = keyv.substring(keyv.lastIndexOf("_"));
            val_comp += cifra;
        }
        
        $("#vsel").html(val_comp);
    });
    
});
