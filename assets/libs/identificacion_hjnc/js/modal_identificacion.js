$(document).ready(function () {
    var buffer      = '';  // guarda los caracteres que envía el lector
    var lastTime    = 0;

    $(document).on('keypress', function (e) {
        if (!$('#modal_iniciar_sesion').hasClass('show')) {
            return; // Permite ENTER normal en los textarea
        }
        if ($(e.target).is('textarea, input[type="text"], input[type="password"]')) {
            return; 
        }
        const char          = String.fromCharCode(e.which);
        const currentTime   = new Date().getTime();

        if (currentTime - lastTime > 100) {
            buffer          = '';
        }
        buffer              += char;
        lastTime            = currentTime;

        if (e.which === 13) {
            e.preventDefault();
            const codigo    = buffer.trim();
            buffer          = '';

            if (codigo.length > 0) {
                $('#codigoBarra').val(codigo);
                $('#btnModalIdentificacion').click();
            }
        }
    });
    $("#btnModalIdentificacion").click(function () {
        const user          = $("#identificacion_usuario").val();
        const pass          = $("#identificacion_password").val();
        const login         = user.replace(/\./g, '').replace(/-/g, '');
        const cleanUser     = login.substring(0, login.length - 1); // Quitar dígito verificador
        var formulario      = $("#form_modal_identificacion").serialize();
        ajaxRequest(raiz    + "/assets/libs/identificacion_hjnc/controller/main_controller.php", formulario + "&accion=iniciar_sesion", "POST", "JSON", 1, "",async function (response) {
            switch (response.status) {
                case "successIguales":
                    var formData = new URLSearchParams();
                    formData.append('username', cleanUser); 
                    formData.append('password', pass);     

                    $.ajax({
                        type: 'POST',
                        url: 'http://10.6.21.33:8001/acceso/login',
                        data: formData.toString(),
                        contentType: 'application/x-www-form-urlencoded',
                        dataType: 'json'
                    }).done(function (data) {
                        if (data.access_token || data.token) {
                            localStorage.setItem('hjnc_session', 'true');
                            localStorage.setItem('hjnc_user', JSON.stringify({
                                id: data.id_usuario || data.id || '',
                                username: cleanUser,
                                email: data.email || `${cleanUser}@hjnc.cl`,
                                role: data.role || data.rol || 'Usuario',
                                roles_usuario_id: data.roles_usuario || '',
                                token: data.access_token || data.token
                            }));
                        }
                    }).fail(function (jqXHR) {
                        // alert("ocurrio error, ni idea");
                        localStorage.removeItem('hjnc_session');
                        localStorage.removeItem('hjnc_user');
                    }).always(function () {
                        $(".modal").modal("hide");
                        $('#tiempoInactividad').val(response.SESSION_TIMEOUT);
                        temporizador();
                    });
                break;
                case "success":
                    var formData = new URLSearchParams();
                    formData.append('username', cleanUser); 
                    formData.append('password', pass);      

                    $.ajax({
                        type: 'POST',
                        url: 'http://10.6.21.33:8001/acceso/login',
                        data: formData.toString(),
                        contentType: 'application/x-www-form-urlencoded',
                        dataType: 'json'
                    }).done(function (data) {
                        if (data.access_token || data.token) {
                            localStorage.setItem('hjnc_session', 'true');
                            localStorage.setItem('hjnc_user', JSON.stringify({
                                id: data.id_usuario || data.id || '',
                                username: cleanUser,
                                email: data.email || `${cleanUser}@hjnc.cl`,
                                role: data.role || data.rol || 'Usuario',
                                roles_usuario_id: data.roles_usuario || '',
                                token: data.access_token || data.token
                            }));
                        }
                    }).fail(function (jqXHR) {
                        // alert("ocurrio error, ni idea");
                        localStorage.removeItem('hjnc_session');
                        localStorage.removeItem('hjnc_user');
                    }).always(function () {
                        $(".modal").modal("hide");
                        location.reload();
                    });
                break;
                case "another_user":
                    
                    modalMensaje("Error", "El Usuario ingresado debe iniciar su sesión a través de acceso.", "#modal_identificacion_error", "", "danger");
                break;
                case "not_found":

                    modalMensaje("Error", "El Usuario y contraseña no coinciden, intente nuevamente.", "#modal_identificacion_error", "", "danger");
                break;
                default:

                    modalMensaje("Error", "Su sesión no se pudo iniciar, intente nuevamente.", "#modal_identificacion_error", "", "danger");
                break;
            }
        });
    });

    $('.rut').Rut({
        on_error: function () { },
        format_on: 'keyup'
    });
});