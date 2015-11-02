$(document).ready(function(){
  
    
    $("#submit_btn").click(function(){
        var nocomplete=false;
            
        var permitted=true;
            
        $("input:text").each(function(){
               
            if($(this).val()=='') nocomplete=true;
        })
            
        $("select").each(function(){
               
            if($(this).val()=='selezionare') nocomplete=true;
        })
            
        $(".qta_tbox").each(function(){
            if(! IsNumeric($(this).val())) permitted=false;
               
        })
            
        function IsNumeric(input){
            var RE =  /^\d*\.{0,1}\d+$/;
            return (RE.test(input));
        }
      
        if(nocomplete || !permitted){
            jAlert('Ci sono campi non compilati o valori non ammessi.');
            return false;
        }else{
            $(".mtf_textfield").removeAttr("disabled");
            $('form').submit();
        }
    })
    
    $("#Annulla").click(function(){
        window.location.href = 'products'
    });
    
    $("#addrow").click(function(){
         addrow();
        if($(this).hasClass("bproducts")){  //Se siamo nella pagina prodotti banco il campo visualizza sui registri va rimosso.
          $(".rr_check2").parent().html("")
        }
    })
    
    $(".rem").live("click", function(){
        $(this).parent().parent().remove();
    });
        
    var arr_options = JSON.parse($("#encodedoptions").val());
    
    
    
    var code='<tr>'+
    '<td><input type="hidden" name="modifiable[]" class="mtf_textfield hfield_modifiable" value="1" />'+
    '<input type="hidden" name="product_id[]" id="product_id" value="" class = "mtf_textfield hfield_product_id" />'+
    '<div class="photodiv"><img src="../styles/page/images/nophoto.png" alt="" /></div>'+
    '<input type="hidden" name="imagelink[]" value="" class = "mtf_textfield hfield_imglink" />'+   
    '</td>'+
    '<td><label>Prodotto<span class="red">*</span>:</label><br /><input type="text" name="product[]" class = "mtf_textfield product_name_tbox" value = "" id = "product" /></td>'+
    '<td><label>Qtà/unità<span class="red">*</span>:</label><br /><input type="text" name="qtyforunity[]" class = "mtf_textfield qta_tbox" value = "" id = "qtyforunity" /></td>'+
    '<td><label>Qtà/unità<span class="red">*</span>:</label><br /><input type="text" name="qtyforunity2[]" class = "mtf_textfield qta_tbox" value = "" id = "qtyforunity2" /></td>'+
    '<td><label>Qtà/unità<span class="red">*</span>:</label><br /><input type="text" name="qtyforunity3[]" class = "mtf_textfield qta_tbox" value = "" id = "qtyforunity3" /></td>'+
    '<td><label>Unità di mis.<span class="red">*</span>:</label><br /><select name="measureunity[]" tabindex="4" class="mtf_textfield unity_tbox"><option value="selezionare">selezionare</option>';
            
    for (var key in arr_options) {  
        code += '<option value="' + key +'">' + arr_options[key] + '</option>';
    }        
            
            
    code += '</select></td>'+
    '<td class="rr1">'+
    '<center>'+
    '<label>Vis. Bl.&nbsp;</label><br />'+
    '<input type="hidden" name="rr[]" class="rr" value="off" />         <!-- importante che sia il primo nodo di center -->'+
    '<input type="checkbox" class="rr_check" title="Indica se visualizzare il prodotto sul blocchetto consegne" />'+             
    '</center>'+
    '</td>'+
    '<td class="rr1">'+
    '<center>'+
    '<label>Vis. Reg.&nbsp;</label><br />'+
    '<input type="hidden" name="rr2[]" class="rr2" value="off" />         <!-- importante che sia il primo nodo di center -->'+
    '<input type="checkbox" class="rr_check2" title="Indica se visualizzare il prodotto nei registri AGEA" />'+             
    '</center>'+
    '</td>'+
    '<td>'+
    '<br /><input type="button" class="rem" value="X" />'+
    '</td>'+
    '</tr>';
 
       
    function addrow(){
        $("#refdiv").parent().parent().before(code);
    }
    
    $.mywebcam({
        'save_folder': '../Personal/PhotoProducts', 
        'nophoto':  $('#nophoto').val(),
        'title': 'Foto al prodotto'
    });
    
    //checkbox vis. bl.
    
    $(".rr_check").live("click", function(){
        if($(this).is(":checked"))
            $(this).parent().find(".rr").val("on")
        else
            $(this).parent().find(".rr").val("off")
    });
    
    //checkbox vis. reg.
    
    $(".rr_check2").live("click", function(){
        if($(this).is(":checked"))
            $(this).parent().find(".rr2").val("on")
        else
            $(this).parent().find(".rr2").val("off")
    })
  
    $("#addrow, #submit_btn, #Annulla").button();
    
})
