var tiempoSesion;
var intervalTemporizador = null;  // guardar el ID del intervalo

function temporizador() {
    if (intervalTemporizador !== null) {
        clearInterval(intervalTemporizador);
        intervalTemporizador = null;
    }
    
    var elTiempo = document.getElementById("tiempo");
    if (!elTiempo) return;
    
    tiempoSesion = $('#tiempoInactividad').val() * 60;
    tiempoSesion = tiempoSesion - 1;
    
    intervalTemporizador = setInterval(function () {
        var minutos = parseInt(tiempoSesion / 60, 10);
        var resto = tiempoSesion % 60;
        var segundos = resto;
        if (segundos < 10) {
            segundos = "0" + segundos;
        }
        tiempoSesion = tiempoSesion - 1;
        
        if (tiempoSesion <= -2) {
            clearInterval(intervalTemporizador);
            intervalTemporizador = null;
            modalFormulario_noCabeceraIdentificacion('', raiz + '/assets/libs/identificacion_hjnc/modal/modal_identificacion.php', {}, "#modal_iniciar_sesion", "modal-md", "", "fas fa-plus");
            return;
        }
        elTiempo.innerHTML = minutos + ":" + segundos;
    }, 1000);
}

document.onmousemove = function () {
    if (tiempoSesion > 0) {
        tiempoSesion = $('#tiempoInactividad').val() * 60;
    }
};

$(document).ready(function () {
    temporizador();
});