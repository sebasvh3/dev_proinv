/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(function(){
    //Active the dataTable
    $('#listaProductos').dataTable({
//	"sDom": 'C<"clear">R<"top">tr<"bottom"lp><"clear">',
        "oLanguage": {
            "sUrl": "resources/datatables/dataTables.spanish.txt"
        },
	"fnInitComplete": function() {
	    this.closest('div').find('select').addClass("form-control input-sm");
        },
	"oColVis": {
	    "activate": "mouseover",
            "buttonText": "Mostrar/Ocultar Columnas",
	    "aiExclude": [ 0 ]
	},
    });
    
    var idtab = document.getElementById("idtab").value;
    $("#"+idtab).addClass("active");
    //Asignar opcion al boton guardar formulario de edicion
    $("#buttonGuardarForm").on("click",guardarFormulario);
    
});

function mostrar_mensaje(tipo, mensaje){
    var t = '.alert-'+tipo+':first';
    var alert = $(t).clone();
    alert.find('.mensaje-body').html(mensaje);
    alert.show();
    $('.mensajes').append(alert);
}

function editarProducto(id,control){
    $.ajax({
            data: "id="+id,
            type: "POST",
            dataType: "json",
            url: "app.php/"+control+"/editarAjax",
            success: function(datos){
                console.dir(datos);
                llenarFormulario(datos);
                console.debug("Datos llenados");
                for(i in datos){
                    console.debug(i+" : "+datos[i]);
                }
            },
            error: function(){
                alert("Error en consulta ajax");
            }
        });
}

function editEntity(id,control){
    $.ajax({
            data: "id="+id,
            type: "POST",
            dataType: "json",
            url: "app.php/"+control+"/editarAjax",
            success: function(datos){
                console.debug("Datos editarAjax:");
                console.dir(datos);
                llenarFormulario(datos);
                
                for(i in datos){
                    console.debug(i+" : "+datos[i]);
                }
            },
            error: function(){
                alert("Error en consulta ajax");
            }
        });
}

function llenarFormulario(datos){
    var entity = datos[0];
    
    for(item in entity){
        $("#input_"+item).val(entity[item]);
    }
    
    if(datos.length === 2){
        var categorias = datos[1];
//        var select = $("#id_categoria");
        var options = "";
        for(cat in categorias){
            options+="<option value='"+categorias[cat]['id']+"'>";
            options+=categorias[cat]['descripcion'];
            options+="</option>";
            console.debug("----");
            console.dir(categorias[cat]);
        }
        $("#select_id_categoria").append(options);
    }
    
//    console.debug("Los datos fueron llenados");
//    $("#input_id").val(datos[0]['id']);
//    $("#input_descripcion").val(datos[0]['descripcion']);
//    $("#input_cantidad_gr").val(datos[0]['cantidad_gr']);
//    $("#input_codigo").val(datos[0]['codigo']);
//    $("#input_tercero").val(datos[0]['tercero']);
    //Asignar 
//    $("#buttonGuardarForm").off();
    
    
    $('#ModalForm').modal('show');
}

function guardarFormulario(){
    var entity = $("#name_entity").val();
    var form_datos=$('#form_edit').serialize();
    console.dir(form_datos);
    $.ajax({
            data: form_datos,
            type: "POST",
            dataType: "json",
            url: "app.php/"+entity+"/guardarAjax",
            success: function(datos){
                actualizarNotificarRegistroEnTabla(datos);
            },
            error: function(){
                mostrar_mensaje("danger","Error en el servidor!");
            }
        });
}

function actualizarNotificarRegistroEnTabla(datos){
    var name_entity = $("#name_entity").val();
    var entity = datos['objEnt'][0];
    var id = entity['id'];
    var fila = $("#"+name_entity+"_"+id);
    for(item in entity){
        fila.find(".td_"+item).html(entity[item]);
    }    
    mostrar_mensaje(datos['tipo'],datos['msj']);
}



function eliminarEntity(id,control){
    $(".idEntity").html(id);
    $("#buttonEliminarReg").off();
    $("#buttonEliminarReg").on("click",function(){
            $.ajax({
            data: "id="+id,
            type: "POST",
            dataType: "json",
            url: "app.php/"+control+"/inactivarRegistro",
            success: function(datos){
//                actualizarNotificarRegistroEnTabla(datos);
                console.debug("respuesta del servidor");
                console.dir(datos);
                mostrar_mensaje(datos['tipo'],datos['msj']);
                eliminarFilaTable(id);
            },
            error: function(){
                mostrar_mensaje("danger","Error en el servidor!");
            }
        }); 
    });
    $("#ModalDeleteProd").modal('show');
    console.debug("Metodo para eliminar el producto "+id);
}

function eliminarEntity2(id,control){
    $(".idproducto").html(id);
    $("#buttonEliminarReg").off();
    $("#buttonEliminarReg").on("click",function(){
            $.ajax({
            data: "id="+id,
            type: "POST",
            dataType: "json",
            url: "app.php/Producto/inactivarRegistro",
            success: function(datos){
//                actualizarNotificarRegistroEnTabla(datos);
                console.debug("respuesta del servidor");
                console.dir(datos);
                mostrar_mensaje(datos['tipo'],datos['msj']);
                eliminarFilaTable(id);
            },
            error: function(){
                mostrar_mensaje("danger","Error en el servidor!");
            }
        }); 
    });
    $("#ModalDeleteProd").modal('show');
    console.debug("Metodo para eliminar el producto "+id);
}


function eliminarFilaTable(id){
    var name_entity = $("#name_entity").val();
    $("#"+name_entity+"_"+id).delay(100);
    $("#"+name_entity+"_"+id).fadeOut(700, function () {
         $(this).remove();
     });
}

function trim (myString){
    return myString.replace(/^\s+/g,'').replace(/\s+$/g,'')
}


function consultar(id){
    $.ajax({
            data: "id="+id,
            type: "POST",
            dataType: "json",
            url: "app.php/Producto/consultar",
            success: function(datos){
                console.debug("datos consultados");
                console.dir(datos);
            },
            error: function(){
                alert("Error en consulta ajax");
            }
        });
}

function probarGet(){
    $.ajax({
            data: "id=233&var=sebas",
            type: "GET",
            dataType: "json",
            url: "app.php/Categoria/probarGet",
            success: function(datos){
                console.debug("datos consultados del get");
                console.dir(datos);
            },
            error: function(){
                alert("Error en consulta ajax");
            }
        });
}