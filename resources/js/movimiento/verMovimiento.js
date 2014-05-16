
//var m_rest = new Rest(urlbase+'mantenimientos/m_api/');

$(function(){
//    $("#btn_buscar_cuentas").on("click",function(){
//        tabla_cuentas.fnReloadAjax();
//    });
    var prodBodPrincipal   = parseInt($("#input_principal").val());
    var prodBodTercerizado = parseInt($("#input_tercerizado").val());
    if(prodBodPrincipal)   getDataTable(prodBodPrincipal  ,$("#tablaMovBod1"));
    if(prodBodTercerizado) getDataTable(prodBodTercerizado,$("#tablaMovBod2"));
});

function test(){
    //** TEST
    opt={};
    opt.offset = "40";
    opt.limit = "10";
    opt.dir = "ASC";
    opt.col = "04";
    var movimientos = getResponse("get","Movimiento/BPrincipal",opt);
    log("Movimients:");
    log(movimientos);
}


function getDataTable(prodBodega,$Table){
    var oTable = $Table.dataTable({
	//"sDom": 'f<"clear">R<"top"l>tr<"bottom"ip><"clear">',
        "sDom": '<"clear">R<"top">tr<"bottom"lp><"clear">',
        "oLanguage": {
            "sUrl": "resources/datatables/dataTables.spanish.txt"
        },
        "iDisplayLength":10,
        "aLengthMenu": [[10, 30, 50, 100, -1], [10, 30, 50, 100,"Todos"]],
        "aoColumns": [
                    { "mData": "fecha"},
                    { "mData": "documento", sClass:"descripcion"},
                    { "mData": "transaccion", sClass:"numero" }, 
                    { "mData": "cantidad", sClass:"numero" }, 
                    { "mData": "usuario", sClass:"numero" }, 
//                    { "mData": null, sClass:"text-center", 
//                           "fnCreatedCell":function(nTd, sData, oData, iRow, iCol){
//                            var edit="<a class='fa fa-pencil-square-o fa-2x' onclick='editar("+oData.id+")'></a>&nbsp;&nbsp;";
//                            $(nTd).html(edit+del);
//                        } 
//                    },
                ],
        "bProcessing": true,
        "bServerSide": true,
        //"sAjaxSource": urlbase+"mantenimientos/getCuentasPucAx",
        "aoColumnDefs":[
            {
               "mRender": function ( data, type, row ) {
                    
                    return data.substring(0,10);
                },
               "aTargets": [ 0 ]
            },
         ],
        "fnServerData": function(sSource, aoData, fnCallback){
	    var opt = {};
	    //opt.table = true;
	    
            var obj = {};
	    $.each(aoData,function(index, value){
		obj[value.name] = value.value;
	    });
	    
	    opt.offset = obj.iDisplayStart;//Registro inicio consulta
	    opt.limit = obj.iDisplayLength;//Longitud de los registros
	    opt.dir = obj.sSortDir_0;//Des or Asc
	    opt.col = obj.iSortCol_0;//Num col para ordenar
	    opt.bodega = 1;
	    opt.prodBodega = prodBodega;
            //console.debug("Obj: ")
            log(opt);
            
            var movimientos = getResponse("get","Movimiento/BPrincipal",opt);
            
            log(movimientos);
            
	    fnCallback(movimientos);
	},
	"fnInitComplete": function() {
	    this.closest('div').find('select').addClass("form-control input-sm");
            //Se formatea los estilos del input de busqueda para que adopte los estilos de bootstrap
//	    this.closest('div').find("label").find('input').addClass("form-control input-sm").attr("placeholder","Buscar...").wrap("<div class='row' id='busqueda'></div>");
//            var divBusqueda= $("#busqueda").clone(true);//true para clonars el elemento con eventos
//            $(this).closest('div').find("label").eq(0).empty().append(divBusqueda);
        },
//        "fnRowCallback": function( nRow, aData, iDisplayIndex ) {
//                            nRow.className = "trDetalle_"+aData.id;
//                            return nRow;
//         }
    });
    return oTable;
}


//function editar(idcuenta){
//    //** Get
//    var datosCuenta = m_rest.listar('cuentapucs',idcuenta,{});
//    $("#modalCuentasPuc").modal("show");
//    $("#codigo").val(idcuenta);
//    $("#cuentaForm_descripcion").val(datosCuenta.descripcion);
//    $("#cuentaForm_numero").val(datosCuenta.numero);
//    console.dir(datosCuenta);
//    
//    $("#guardar_cuenta_puc").off();
//    $("#guardar_cuenta_puc").on("click",function(){
//        guardar(idcuenta,'editar');
//    });
//    
//}

//function nuevo(){
//    //** Post
//    $("#modalCuentasPuc").modal("show");
//    $("#codigo").val('');
//    $("#cuentaForm_descripcion").val('');
//    $("#cuentaForm_numero").val('');
//    
//    $("#guardar_cuenta_puc").off();
//    $("#guardar_cuenta_puc").on("click",function(){
//        guardar(0,'nuevo');
//    });
//}

//function eliminar(idcuenta){
//    //** Delete
//    $("#ModalConfirmarEliminarReg").modal("show");
//    $("#reg").html(idcuenta);
//    
//    
//    $("#buttonDeleteReg").off();
//    $("#buttonDeleteReg").on("click",function(){
//        guardar(idcuenta,'eliminar');
//    });
//    
//}

//function guardar(idcuenta,accion){
//    var data = $("form.cuenta_puc").serializeObject();
//    log(data);
//    
//    //** Put
//    if(accion === 'editar'){
//        data.id = idcuenta;
//        var cuenta=m_rest.editar('cuentapucs',idcuenta,data,{});
//        //** Se mustra la fila actualizada con el nuevo valor
//        var fila = $(".trDetalle_"+idcuenta);
//        fila.find(".descripcion").html(cuenta.descripcion);
//        fila.find(".numero").html(cuenta.numero);
//        fila.addClass("success");
//    }
//    //** Post
//    else if(accion === 'nuevo'){
//        var cuenta = m_rest.nuevo('cuentapucs',data,{});
//        mostrar_mensaje("success",cuenta.msj);
//    }
//    //** Delete
//    else if(accion === 'eliminar'){
//        var cuenta = m_rest.borrar('cuentapucs',idcuenta,{});
//        //** Se borra la fila del dataTable
//        var fila = $(".trDetalle_"+idcuenta);
//        fila.delay(100);
//        fila.fadeOut(700, function () {
//            $(this).remove();
//        });
//        mostrar_mensaje("danger","Se ha eliminado el registro:  "+idcuenta);
//    }
//}


