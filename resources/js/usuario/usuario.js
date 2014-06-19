$(function(){
    $("#button_guardar_usuario").on("click",function(e){
        e.preventDefault();
        guardarUsuario();
    });
})

function guardarUsuario() {
    opt={};
    opt.offset = "40";
    opt.limit = "10";
    opt.dir = "ASC";
    opt.col = "04";
    var usuario = getResponse("get","Usuario/guardarUsuario",{});
    log("Movimients:");
    log(usuario);
}

