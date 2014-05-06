function guardarSalidaExistencia(){
    var idproducto=$("#select_id_producto").val();
    var salida=$("#input_salida").val();
     $.ajax({
            data: {"id_producto":idproducto,"salida":salida},//"idproducto="+idproducto,
            type: "POST",
            dataType: "json",
            url: "app.php/Movimiento/guardarSalidaProducto",
            success: function(datos){
                if(datos['class']==="success") $('#inputExistencia').val(datos['response']['existencia']);
                
                mostrar_mensaje(datos['class'],datos['msj']);
                console.debug("guardarSalidaExistencia :");
                console.dir(datos);
            },
            error: function(){
                alert("Error en consulta ajax ");
            }
        });
        
}

