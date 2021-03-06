urlbase = "app.php/";

$(function() {
    //Active the dataTable
//    $('#listaProductos').dataTable({
////	"sDom": 'C<"clear">R<"top">tr<"bottom"lp><"clear">',
//        "oLanguage": {
//            "sUrl": "resources/datatables/dataTables.spanish.txt"
//        },
////	"fnInitComplete": function() {
////	    this.closest('div').find('select').addClass("form-control input-sm");
////        },
//        "fnInitComplete": function() {
//	    new FixedHeader( this );
//	    this.closest('div').find('select').addClass("form-control input-sm");
//        },
//	"oColVis": {
//	    "activate": "mouseover",
//            "buttonText": "Mostrar/Ocultar Columnas",
//	    "aiExclude": [ 0 ]
//	},
//    });
    
    $('#listaProductos').dataTable({
	"sDom": 'f<"clear">R<"top">tr<"bottom"lp><"clear">',
        "oLanguage": {
            "sUrl": "resources/datatables/dataTables.spanish.txt"
        },
	"fnInitComplete": function() {
	    this.closest('div').find('select').addClass("form-control input-sm");
            //Se formatea los estilos del input de busqueda para que adopte los estilos de bootstrap
	    this.closest('div').find("label").find('input').addClass("form-control input-sm").attr("placeholder","Buscar...").wrap("<div class='row' id='busqueda'></div>");
            var divBusqueda= $("#busqueda").clone(true);//true para clonars el elemento con eventos
            $(this).closest('div').find("label").eq(0).empty().append(divBusqueda);
        }
    });
    
    
    //Asignacion Eventos
    
    var idtab = document.getElementById("idtab").value;
    $("#"+idtab).addClass("active");
    //Asignar opcion al boton guardar formulario de edicion
    $("#buttonGuardarForm").on("click",guardarFormulario);
    
    
    
    console.debug("inpu "+$('#inputExistencia').length);
    //***Ventana de salidas
    if($('#inputExistencia').length>0) {
        console.debug("si inputExistencia sss");
        $("#select_id_producto").change(function() {
            console.debug("por la funcion select");
            idpro=this.value;
            existenciaByProducto(idpro);
        });
    }
    
    //Active DatePicket()
    $('.date').datepicker({
        format: "yyyy-mm-dd",
        language: "es",
        autoclose: true,
        keyboardNavigation: true
    });
    
});

function mostrar_mensaje(tipo, mensaje) {
    var t = '.alert-'+tipo+':first';
    var alert = $(t).clone();
    alert.addClass("msj-visto");
    alert.find('.mensaje-body').html(mensaje);
    alert.show();
    $('.mensajes').append(alert);
}

function editarProducto(id,control) {
    $.ajax({
            data: "id="+id,
            type: "POST",
            dataType: "json",
            url: "app.php/"+control+"/editarAjax",
            success: function(datos) {
                console.dir(datos);
                llenarFormulario(datos);
                console.debug("Datos llenados");
                for(i in datos) {
                    console.debug(i+" : "+datos[i]);
                }
            },
            error: function() {
                alert("Error en consulta ajax");
            }
        });
}

function editEntity(id,control) {
    $.ajax({
            data: "id="+id,
            type: "POST",
            dataType: "json",
            url: "app.php/"+control+"/editarAjax",
            success: function(datos) {
                console.debug("Datos editarAjax:");
                console.dir(datos);
                llenarFormulario(datos);
                
//                for(i in datos) {
//                    console.debug(i+" : "+datos[i]);
//                }
            },
            error: function() {
                alert("Error en consulta ajax");
            }
        });
}

function llenarFormulario(datos) {
    var entity = datos.producto;
    
    for(item in entity) {
        $("#input_"+item).val(entity[item]);
    }
    
    //** Select Categorias
    if(datos.categorias) {
        var categorias = datos.categorias;
        $("#select_id_categoria").empty();
        var options = "<option></option>";
        for(var categoria in categorias) {
            if(entity['id_categoria']===categorias[categoria]['id'])
                options+="<option value='"+categorias[categoria]['id']+"' selected='selected'>";
            else
                options+="<option value='"+categorias[categoria]['id']+"'>";
            options+=categorias[categoria]['descripcion'];
            options+="</option>";
        }
        $("#select_id_categoria").append(options);
    }
    //** Select Terceros
    if(datos.categorias) {
        var terceros = datos.terceros;
        $("#select_id_tercero").empty();
        var options = "<option></option>";
        for(var tercero in terceros) {
            if(entity['id_tercero']===terceros[tercero]['id'])
                options+="<option value='"+terceros[tercero]['id']+"' selected='selected'>";
            else
                options+="<option value='"+terceros[tercero]['id']+"'>";
            options+=terceros[tercero]['descripcion'];
            options+="</option>";
        }
        console.debug("categorias");
        console.debug(options);
        $("#select_id_tercero").append(options);
    }
  
    $('#ModalForm').modal('show');
}

function guardarFormulario() {
    var entity = $("#name_entity").val();
    var form_datos=$('#form_edit').serialize();
    console.dir(form_datos);
    $.ajax({
            data: form_datos,
            type: "POST",
            dataType: "json",
            url: "app.php/"+entity+"/guardarAjax",
            success: function(datos) {
                actualizarNotificarRegistroEnTabla(datos);
                console.debug("datos: ");
                console.dir(datos);
            },
            error: function() {
                mostrar_mensaje("danger","Error en el servidor!");
            }
        });
}

function actualizarNotificarRegistroEnTabla(datos) {
    var name_entity = $("#name_entity").val();
    var entity = datos['objEnt'][0];
    var id = entity['id'];
    var fila = $("#"+name_entity+"_"+id);
    for(item in entity) {
        fila.find(".td_"+item).html(entity[item]);
    }    
    mostrar_mensaje(datos['tipo'],datos['msj']);
}



function eliminarEntity(id,control) {
    $(".idEntity").html(id);
    $("#buttonEliminarReg").off();
    $("#buttonEliminarReg").on("click",function() {
            $.ajax({
            data: "id="+id,
            type: "POST",
            dataType: "json",
            url: "app.php/"+control+"/inactivarRegistro",
            success: function(datos) {
//                actualizarNotificarRegistroEnTabla(datos);
                console.debug("respuesta del servidor");
                console.dir(datos);
                mostrar_mensaje(datos['tipo'],datos['msj']);
                eliminarFilaTable(id);
            },
            error: function() {
                mostrar_mensaje("danger","Error en el servidor!");
            }
        }); 
    });
    $("#ModalDeleteProd").modal('show');
    console.debug("Metodo para eliminar el producto "+id);
}

function eliminarEntity2(id,control) {
    $(".idproducto").html(id);
    $("#buttonEliminarReg").off();
    $("#buttonEliminarReg").on("click",function() {
            $.ajax({
            data: "id="+id,
            type: "POST",
            dataType: "json",
            url: "app.php/Producto/inactivarRegistro",
            success: function(datos) {
//                actualizarNotificarRegistroEnTabla(datos);
                console.debug("respuesta del servidor");
                console.dir(datos);
                mostrar_mensaje(datos['tipo'],datos['msj']);
                eliminarFilaTable(id);
            },
            error: function() {
                mostrar_mensaje("danger","Error en el servidor!");
            }
        }); 
    });
    $("#ModalDeleteProd").modal('show');
    console.debug("Metodo para eliminar el producto "+id);
}


function eliminarFilaTable(id) {
    var name_entity = $("#name_entity").val();
    $("#"+name_entity+"_"+id).delay(100);
    $("#"+name_entity+"_"+id).fadeOut(700, function () {
         $(this).remove();
     });
}

function trim (myString) {
    return myString.replace(/^\s+/g,'').replace(/\s+$/g,'')
}


function consultar(id) {
    $.ajax({
            data: "id="+id,
            type: "POST",
            dataType: "json",
            url: "app.php/Producto/consultar",
            success: function(datos) {
                console.debug("datos consultados");
                console.dir(datos);
            },
            error: function() {
                alert("Error en consulta ajax");
            }
        });
}




function log(data) {
    if (window.console)
	console.log(data);
} 

function opt2url(opt) {
	var url = "";
	if(opt) $.each(opt,function(key,value) {
	    var dup = key + "=" + value + "&";
	    url += dup;
	});
	return url;
}

function getResponse(method, url, data) {
	$.ajax({
	    type: method,
	    url: urlbase+url,
	    dataType: 'json',
	    cache : false,
	    async: false,
	    data: data
	}).done(function( msg ) {
	    ret = msg;
	}).error(function(jqXHR, text) {
            console.debug("Revisar Error en: "+url);
	    ret = false;
	});
	return ret;
}