//** Main
$(function(){
    $("#button_guardar_entrada").on("click",guardarEntrada);
    $("#button_guardar_salida").on("click",guardarSalida);
    
    //Seleccion de productos al cambiar categorias
    $("#select_id_categoria").on("change",function(){
        var val=this.value;
        selectProductosByCategoria(val);
    });
    //**
    $("#select_id_bodega").on("change",existenciaByProducto);
    
});

function selectProductosByCategoria(idcategoria){
     $.ajax({
            data: "idcategoria="+idcategoria,
            type: "POST",
            dataType: "json",
            url: "app.php/Producto/findProductoByCategoria",
            success: function(datos){
                ProductosSegunCategoria(datos);    
            },
            error: function(){
                alert("Error en consulta ajax, selectProductosByCategoria");
            }
        });
}

function ProductosSegunCategoria(datos){
    var options="";
    var first=false;
    $('#input_salida').val('');
    if($('#inputExistencia').length>0){
         $('#inputExistencia').val('');   
         first = true;
    }
    if(datos.length===0)options="<option></option>";
    $.each(datos, function(index,val){
        options+="<option value='"+val['id']+"'>"+val['descripcion']+"</option>";
    });
    $("#select_id_producto").empty();
    $("#select_id_producto").append(options);
    //** Para buscar con el primer producto que se muestre 
    existenciaByProducto();
}

function existenciaByProducto(){
    var obj = {};
    obj.id_producto    = $("#select_id_producto").val();
    obj.id_bodega      = $("#select_id_bodega").val();
    obj.id_transaccion = $("#input_id_transaccion").val();
    
    log("product y bodega");
    log(obj);
    
    var existencia  = getResponse("get","Producto/findExistencia",obj);
    log(existencia);
    $('#inputExistencia').val(existencia['existencia']);
}

function guardarEntrada(){
    var query = $("#form-entrada").serialize();
    var response = getResponse("post","Movimiento/guardarEntradaAx",query);
    if(response){
        $("div.msj-visto").remove();
        for(var i in response['msjs']){
            mostrar_mensaje(response['msjs'][i]['class'],response['msjs'][i]['msj']);
        }
    }
    else mostrar_mensaje('danger','Error en repuesta del servidor.');
}

function guardarSalida(){
    var request = $("#form-salida").serialize();
    var response = getResponse("post","Movimiento/guardarSalidaAx",request);
    if(response){
        $("div.msj-visto").remove();
        for(var i in response['msjs']){
            mostrar_mensaje(response['msjs'][i]['class'],response['msjs'][i]['msj']);
        }
        if(response.stored) 
            $('#inputExistencia').val(response.existencia);
    }
    else mostrar_mensaje('danger','Error en repuesta del servidor.');
    log(response);
}


