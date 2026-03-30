$(document).ready(function() {

    var user = JSON.parse(localStorage.getItem("hjnc_user"));
    console.log(user);

    if (!user) {
        $('#iframeHemocontrol').hide();
        $('#iframeHemocontrol_expirada').show();
        return;
    }

    $('#iframeHemocontrol').show();
    $('#iframeHemocontrol_expirada').hide();

    // Registrar listener SOLO UNA VEZ
    if (!window.__hemocontrolListenerRegistrado) {

        window.__hemocontrolListenerRegistrado = true;

        window.addEventListener("message", function(event) {

            console.log("📨 mensaje recibido:", event.origin, event.data);

            if (event.origin !== 'http://10.6.21.19') return;

            // ================================
            // IFRAME LISTO
            // ================================
            if (event.data?.type === "hemocontrol_ready") {

                console.log("✅ Hemocontrol listo, enviando token");

                // Obtener valores ACTUALES
                var id_paciente     = $('#id_paciente').val();
                var dau_id          = $('#dau_id').val();
                var usuarioNombre   = $('#usuarioNombre').val();
                var rce_id          = $('#rce_id').val();
                var usuario         = $('#usuario').val();

                // Obtener iframe actual
                var iframeHemocontrol = document.getElementById('iframeHemocontrol');

                if (!iframeHemocontrol) {
                    console.warn("Iframe hemocontrol no encontrado");
                    return;
                }

                iframeHemocontrol.contentWindow.postMessage(
                    {
                        type: 'AUTH_TOKEN',
                        email: user.email,
                        id: user.id,
                        role: user.role,
                        roles_usuario_id: user.roles_usuario_id,
                        token: user.token,
                        username: user.username,

                        RCE_DAU_origen: 'RCEDAU',
                        RCE_DAU_id_paciente: id_paciente,
                        RCE_DAU_dau_id: dau_id,
                        RCE_DAU_usuarioNombreDau: usuarioNombre,
                        SISTEMA_RCE: rce_id,
                        RCE_DAU_usuarioDau: usuario
                    },
                    'http://10.6.21.19'
                );
            }

            // ================================
            // CERRAR IFRAME
            // ================================
            if (event.data?.type === "cerrar_iframe_hemocontrol") {

                // Obtener valores actuales
                var id_paciente     = $('#id_paciente').val();
                var dau_id          = $('#dau_id').val();
                var rce_id          = $('#rce_id').val();
                var tipoMapa        = $('#tipoMapa').val();

                if (event.data.success === true) {

                    // Candado anti-duplicación
                    if (window.__hemocontrolProcesando) return;
                    window.__hemocontrolProcesando = true;

                    var respuestaAjaxRequest = ajaxRequest(
                        `${raiz}/controllers/server/rce/indicaciones/main_controller.php`,
                        'id_solicitudTransfusion=' + event.data.id_solicitud +
                        '&dau_id=' + dau_id +
                        '&rce_id=' + rce_id +
                        '&pacId=' + id_paciente +
                        '&accion=insertarIndicaciones',
                        'POST',
                        'JSON',
                        1,
                        'Guardando indicaciones...'
                    );

                    switch (respuestaAjaxRequest.status) {

                        case "success":

                            $('#modalTransfusiones')
                                .modal('hide')
                                .data('bs.modal', null);

                            ajaxContentFast(
                                `${raiz}/views/modules/rce/medico/rce.php`,
                                'tipoMapa=' + tipoMapa + '&dau_id=' + dau_id,
                                '#contenido'
                            );

                            var texto = `
                                <div class="alert alert-light" role="alert">
                                    <h4 class="alert-heading">
                                        <i class="fas fa-check-double throb2 text-success" style="font-size:29px"></i>
                                        Éxito
                                    </h4>
                                    <hr>
                                    <p class="mb-0">
                                        La solicitud de transfusión fue registrada
                                        <b>correctamente</b>.
                                    </p>
                                </div>
                            `;

                            modalMensajNoCabecera(
                                'Éxito',
                                texto,
                                "#modal",
                                "modal-md",
                                "success"
                            );

                        break;

                        case "error":
                        default:

                            ErrorSistemaDefecto();

                        break;
                    }

                    window.__hemocontrolProcesando = false;

                    $('#modalTransfusiones')
                        .modal('hide')
                        .data('bs.modal', null);

                } else {

                    ErrorSistemaDefecto();

                }

            }

        });

    }

});