//** Main
$(function(){
    $("#button_guardar_entrada").on("click",guardarEntrada);
});

function guardarEntrada(){
    var query = $("#form-entrada").serialize();
    var response = getResponse("post","Movimiento/guardarEntradaAx",query);
    if(response){
        $("div.msj-visto").remove();
        for(var i in response['msjs']){
            log("note");
            log(i);
            mostrar_mensaje(response['msjs'][i]['class'],response['msjs'][i]['msj']);
        }
    }
    else mostrar_mensaje('danger','Error en repuesta del servidor.');
    log("Response")
    log(response);
}


