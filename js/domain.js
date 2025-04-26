var urlweb = "http://localhost/IntranetGuzz/";
var ruta_web = "http://localhost/IntranetGuzz/";
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
function validar_correo(id) {
    var text = document.getElementById(id).value;
    var expreg = new RegExp(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
    if(expreg.test(text)){
        return true;
    } else {
        error("Formato de Correo Inválido");
        document.getElementById(id).value = '';
        return false;
    }
}
function validar_solo_texto(id) {
    var text = document.getElementById(id).value;
    var expreg = new RegExp(/^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/);
    if(expreg.test(text)){
        return true;
    } else {
        error("El texto contiene carácteres no válidos.");
        document.getElementById(id).value = '';
        return false;
    }
}
function mayuscula(id) {
    var texto = document.getElementById(id).value;
    document.getElementById(id).value = texto.toUpperCase();
}
function validar_numeros_decimales_dos(id) {
    var text = document.getElementById(id).value;
    var expreg = new RegExp(/^[+-]?[0-9]*$/);
    var expreg2 = new RegExp(/^[+-]?[0-9]+([.]+)?$/);
    var expreg3 = new RegExp(/^[+-]?[0-9]+([.][0-9]{1,3})?$/);
    if(expreg.test(text)){
        return true;
    } else {
        if(expreg2.test(text)){
            return true;
        } else {
            if (expreg3.test(text)){
                return true;
            } else {
                var re = /[a-zA-ZñáéíóúÁÉÍÓÚ´,*+?^$&!¡¿#%/{}()='|[\]\\"]/g;
                document.getElementById(id).value = text.replace(re, '');
                text = document.getElementById(id).value;
                var long1 = text.length;
                var count = 1;
                if(long1 !== 0){
                    while (!expreg3.test(text)){
                        if(count !== 5){
                            var long = text.length;
                            var text_to_extract = long - 1;
                            document.getElementById(id).value = text.substring(0, text_to_extract);
                            text = document.getElementById(id).value;
                            count++;
                        } else {
                            document.getElementById(id).value = '0';
                            return false;
                        }
                    }
                }
                return false;
            }
        }

    }
}
function validar_numeros(id) {
    var text = document.getElementById(id).value;
    var expreg = new RegExp(/^[0-9]*$/);
    if(expreg.test(text)){
        return true;
    } else {
        var re = /[a-zA-ZñáéíóúÁÉÍÓÚ´.*+?^$&!¡¿#%/{}()='|[\]\\"]/g;
        document.getElementById(id).value = text.replace(re, '');
        return false;
    }
}
function validar_campo_vacio(campo, valor, estado) {
    var objeto = document.getElementById(campo);
    if(valor == ""){
        respuesta('El campo resaltado no puede estar vacío', 'error');
        objeto.style.border = 'solid #ff4d4d';
        estado = false;
        console.log('Campo vacio: ' + campo + " Valor: " + valor);
    } else {
        objeto.style.border = '';
    }
    return estado;
}
function validar_parametro_vacio(valor, estado) {
    if(valor === ""){
        estado = false;
        respuesta('Parametro vacío', 'error');
    }
    return estado;
}
function redondear (numero, decimales = 2, usarComa = false) {
    //Esta respuesta
    var opciones = {
        maximumFractionDigits: decimales,
        useGrouping: false
    };
    return new Intl.NumberFormat((usarComa ? "es" : "en"), opciones).format(numero);
}
function cambiar_color_estado(id) {
    var select_pe = $("#" + id).val();
    if (select_pe !== ""){
        switch (select_pe) {
            case '1':
                $("#" + id).css('color','white');
                $("#" + id).css('background','#17a673');
                break;
            case '0':
                $("#" + id).css('color','white');
                $("#" + id).css('background','#e74a3b');
                break;
        }
    }
}
function cambiar_texto_formulario(id, texto){
    $("#" + id).html(texto);
}
function cambiar_estado_boton(id, texto, deshabilitado){
    $("#" + id).html(texto);
    $("#" + id).attr("disabled", deshabilitado);
}
function colocar_estado_texto(estado, elemento, texto_si, texto_no){
    if(estado == 1){
        $('#' + elemento).removeClass('texto-deshabilitado');
        $('#' + elemento).addClass('texto-habilitado');
        $('#' + elemento).html(texto_si);
    } else {
        $('#' + elemento).removeClass('texto-habilitado');
        $('#' + elemento).addClass('texto-deshabilitado');
        $('#' + elemento).html(texto_no);
    }
}
$.ajaxSetup({
    beforeSend: function () {
        $("<div class='loader' id='loading'></div>").appendTo("body");
    },complete:function() {
        $("#loading").remove();
    }
});