$(document).ready(function(){
    //Variables
    let dau_id                      = $('#dau_id').val(),
        id_rce                      = $('#id_rce').val(),
        idPaciente                  = $('#idPaciente').val(),
        ind_id                      = '',
        table_reference             = tablaSimple3("#tablaContenidoIndicacionesEnfermera");
    let parametrosIndicaciones      = { arregloFilasSeleccionadas : [] , claseIndicaciones : '' , tipoAccion : '' , accessRequest : '' , accion : '' };
    //Botón agregar examen
    // $('#btnAgregarExamen').click(function(){
    //     let botones =   [
    //                         { id: 'btn_Agregar_Examenes', value: 'Guardar', class: 'btn btn-primary' }
    //                     ];
    //     modalFormulario('Añadir Examenes', `${raiz}/views/modules/rce/indicaciones/indicaciones_modal.php`, `dau_id=${dau_id}&rce_id=${id_rce}`, '#agregarExamen', '90%', '90%', botones);
    // });
    $("#frm_aplicados").change(function(){
        var frm_aplicados = $(this).val();
        ajaxContent(`${raiz}/views/modules/Enfermera/detalleIndicaciones.php`, `dau_id=${dau_id}&regId=${id_rce}&frm_aplicados=${frm_aplicados}`, `#contenidoImagenologia_${dau_id}`, '', true);
    });
    // $("#miSelectAplicado").change(function(){
        // var select = document.getElementById("miSelectAplicado");
        // var valorSeleccionado = select.value;
        // ajaxContent(`${raiz}/views/modules/Enfermera/detalleIndicaciones.php`, `dau_id=${dau_id}&regId=${id_rce}`, `#contenidoImagenologia_${dau_id}`, '', true);
        // alert("Seleccionaste: " + valorSeleccionado);
    // }
    $("#contenidoIndicacionesEnfermera").selectable({
        filter: "tr.seleccionable",
        stop: function() {
            $(".ui-selected").each(function() {
                info = table_reference.rows('.ui-selected').data();
            });
        }
    });
    $("#contenidoIndicacionesEnfermera").on('click','.verModalDetalleIndicacion',function(){
        let indicacion_id       = $(this).attr('id');
        let arreglo             = indicacion_id.split('-');
        console.log(arreglo)
        let servicio            = arreglo[1];
        let nom_servicio        = '';
        console.log(indicacion_id)
        switch ( servicio ) {
            case '1':
                nom_servicio = 'Imagenología';
            break;
            case '2':
                nom_servicio = 'Tratamiento';
            break;
            case '3':
                nom_servicio = 'Laboratorio';
            break;
            case '4':
                nom_servicio = 'Otros';
            break;
        }
        modalFormulario('<label class="mifuente text-primary">Detalle Solicitud Indicaciones '+nom_servicio+'</label>',raiz+"/views/modules/rce/indicaciones/modal_detalle_indicacion.php",$("#frm_modal_detalle_aplica").serialize()+`&sol_id=${indicacion_id}`,'#modal_btn_add_diagnostico','modal-lg','', 'fas fa-align-justify text-primary','');
    });
    $("#contenidoIndicacionesEnfermera").on('click','.verModalDetalleIndicacion2',function(){
        let indicacion_id = $(this).attr('id');
        modalFormulario_noCabecera('', `${raiz}/views/modules/rce/especialista/especialista.php`, `dau_id=${dau_id}&idSolicitudEspecialista=${indicacion_id}&tipoFormulario=verDetalle`,'#detalleIndicacion2', "modal-lg", "", "fas fa-plus");
    });
     $("#contenidoIndicacionesEnfermera").on('click','.verModalDetalleIndicacion2Otros',function(){
        let indicacion_id = $(this).attr('id');

        modalFormulario_noCabecera('',`${raiz}/views/modules/rce/especialista/OtroEspecialista.php`, `dau_id=${dau_id}&idSolicitudEspecialista=${indicacion_id}&tipoFormulario=verDetalle`,'#detalleIndicacion2', "modal-lg", "", "fas fa-plus");
    });
    $("#contenidoIndicacionesEnfermera").on('click','.verURLResultado',function(){
        const urlResultado = $(this).attr("id");
		showFile(urlResultado, 800, 800);
    });

    //Botón aplicar indicación
    $("#contenidoIndicacionesEnfermera").on('click','.gestionRealizada', async function() {
        let indicacion_id = $(this).attr('id');
        parametrosIndicaciones.accion       = 'gestionRealizada';
        parametrosIndicaciones.tipoAccion   = "realizar gestión";
        parametrosIndicaciones.funcion      = aplicarGestionRealizada;
        const estadoPermiso = await validarPermisoUsuario('btn_anular_indicaciones');
        if (estadoPermiso) {
            let botones =   [
                            { id: 'btnGestionRealizada', value: 'Gestión Realizada', function: confirmarAccionEnIndicacion, class: 'btn btn-primary' }
                        ];
            modalFormulario('<label class="mifuente text-primary">Detalle Indicación</label>',`${raiz}/views/modules/rce/especialista/especialista.php`,`dau_id=${dau_id}&idSolicitudEspecialista=${indicacion_id}&tipoFormulario=gestionRealizada`,'#gestionRealizadaIndicacion','modal-lg','', 'fas fa-align-justify text-primary',botones);
        }
    });
    //Botón aplicar indicación
    $("#contenidoIndicacionesEnfermera").on('click','.aplicarIndicacion', async function() {
        if ( perfilUsuario == 'administrativo' ) {
            return;
        }
        ind_id = $(this).attr('id');
        parametrosIndicaciones.accion       = 'aplicarIndicacion';
        parametrosIndicaciones.funcion      = aplicarAccionIndicacion;
        const estadoPermiso = await validarPermisoUsuario('btn_aplicar_indicaciones');
        if (estadoPermiso) {
            let botones =   [
                                { id: 'agregarObservacion', value: ' Aplicar Indicación', function: confirmarAccionEnIndicacion, class: 'btn btn-primary' }
                            ];
            modalFormulario('<label class="mifuente text-primary">Observación Aplicar Indicación</label>', `${raiz}/views/modules/rce/indicaciones/modal_observacion.php`,$("#frm_modal_observacion").serialize()+`&ind_id=${ind_id}`,'#modalObservacion','modal-lg','', 'fas fa-align-justify text-primary',botones);
        }
    });
    //Botón anular indicación
    $("#contenidoIndicacionesEnfermera").on('click','.anularIndicacionesAplicadas', async function() {
        if ( perfilUsuario == 'administrativo' ) {
            return;
        }
        ind_id = $(this).attr('id');
        parametrosIndicaciones.accion       = 'anularIndicacionEnf';
        parametrosIndicaciones.funcion      = aplicarAccionIndicacion;
        const estadoPermiso = await validarPermisoUsuario('btn_anular_indicaciones');
        if (estadoPermiso) {
            let botones =   [
                            { id: 'agregarObservacion', value: ' Anular Indicación', function: confirmarAccionEnIndicacion, class: 'btn btn-primary' }
                        ];
            modalFormulario('<label class="mifuente text-primary">Observación Anular Indicación</label>', `${raiz}/views/modules/rce/indicaciones/modal_observacion.php`,$("#frm_modal_observacion").serialize()+`&ind_id=${ind_id}`,'#modalObservacion','modal-lg','', 'fas fa-align-justify text-primary',botones);
        }

    });
    //Botón tomar muetra indicación
    $("#contenidoIndicacionesEnfermera").on('click','.tomaMuestra',async function() {
        if ( perfilUsuario == 'administrativo' ) {
            return;
        }
        ind_id = $(this).attr('id');
        parametrosIndicaciones.accion           = 'tomaMuestra';
        parametrosIndicaciones.funcion          = aplicarAccionIndicacion;
        const estadoPermiso = await validarPermisoUsuario('btn_anular_indicaciones');
        if (estadoPermiso) {
            let botones =   [
                                { id: 'agregarObservacion', value: ' Tomar Muestra Indicación', function: confirmarAccionEnIndicacion, class: 'btn btn-primary' }
                            ];

            modalFormulario('<label class="mifuente text-primary">Observación Tomar Muestra Indicación</label>', `${raiz}/views/modules/rce/indicaciones/modal_observacion.php`,$("#frm_modal_observacion").serialize()+`&ind_id=${ind_id}`,'#modalObservacion','modal-lg','', 'fas fa-align-justify text-primary',botones);
        }
    })
    //Botón inciar indicación
    $("#contenidoIndicacionesEnfermera").on('click','.iniciarIndicaciones',async function() {
        if ( perfilUsuario == 'administrativo' ) {
            return;
        }
        ind_id = $(this).attr('id');
        parametrosIndicaciones.accion           = 'iniciarIndicacion';
        parametrosIndicaciones.funcion          = aplicarAccionIndicacion;
        const estadoPermiso = await validarPermisoUsuario('btn_anular_indicaciones');
        if (estadoPermiso) {
            let botones =   [
                            { id: 'agregarObservacion', value: ' Iniciar Indicación', function: confirmarAccionEnIndicacion, class: 'btn btn-primary' }
                        ];

            modalFormulario('<label class="mifuente text-primary">Observación Iniciar Indicación</label>', `${raiz}/views/modules/rce/indicaciones/modal_observacion.php`,$("#frm_modal_observacion").serialize()+`&ind_id=${ind_id}`,'#modalObservacion','modal-lg','', 'fas fa-align-justify text-primary',botones);
        }

    });
    //Botón aplicar varias indicaciones
    $('.aplicarVariasIndicaciones').click(function(){
        if ( perfilUsuario == 'administrativo' ) {
            return;
        }
        parametrosIndicaciones.claseIndicaciones = '.aplicarIndicacion';
        if ( ! verificarIndicacionesSeleccionadas() ) {
            llamarModalMensajeBtnExit();
            return;
        }
        parametrosIndicaciones.tipoAccion                   = 'aplicar';
        parametrosIndicaciones.accessRequest                = 'btn_aplicar_indicaciones';
        parametrosIndicaciones.accion                       = 'aplicarMultiplesFilas';
        parametrosIndicaciones.arregloFilasSeleccionadas    = JSON.stringify(parametrosIndicaciones.arregloFilasSeleccionadas);
        desplegarModalConfirmacion ();
    });
    //Botón anular varias indicaciones
    $('.anularVariasIndicaciones').click(function(){
        if ( perfilUsuario == 'administrativo' ) {
            return;
        }
        parametrosIndicaciones.claseIndicaciones = '.anularIndicacionesAplicadas';
        if ( ! verificarIndicacionesSeleccionadas() ) {
            llamarModalMensajeBtnExit();
            return;
        }
        parametrosIndicaciones.tipoAccion                   = 'anular';
        parametrosIndicaciones.accessRequest                = 'btn_aplicar_indicaciones';
        parametrosIndicaciones.accion                       = 'anularMultiplesFilas';
        parametrosIndicaciones.arregloFilasSeleccionadas    = JSON.stringify(parametrosIndicaciones.arregloFilasSeleccionadas);
        desplegarModalConfirmacion ();
    });
    //Botón iniciar varias indicaciones
    $('.iniciarVariasIndicaciones').click(function(){
        if ( perfilUsuario == 'administrativo' ) {
            return;
        }
        parametrosIndicaciones.claseIndicaciones = '.iniciarIndicaciones';
        if ( ! verificarIndicacionesSeleccionadas() ) {
            llamarModalMensajeBtnExit();
            return;
        }
        parametrosIndicaciones.tipoAccion                   = 'iniciar';
        parametrosIndicaciones.accessRequest                = 'btn_aplicar_indicaciones';
        parametrosIndicaciones.accion                       = 'iniciarIndicacionesMultiplesFilas';
        parametrosIndicaciones.arregloFilasSeleccionadas    = JSON.stringify(parametrosIndicaciones.arregloFilasSeleccionadas);
        desplegarModalConfirmacion ();
    });
    //Botón tomar muestra varias indicaciones
    $('.tomaMuestraVariasIndicaciones').click(function(){
        if ( perfilUsuario == 'administrativo' ) {
            return;
        }
        parametrosIndicaciones.claseIndicaciones = '.tomaMuestra';
        if ( ! verificarIndicacionesSeleccionadas() ) {
            llamarModalMensajeBtnExit();
            return;
        }
        parametrosIndicaciones.tipoAccion                   = 'tomar muestras de';
        parametrosIndicaciones.accessRequest                = 'btn_aplicar_indicaciones';
        parametrosIndicaciones.accion                       = 'tomarMuestrasIndicacionesMultiplesFilas';
        parametrosIndicaciones.arregloFilasSeleccionadas    = JSON.stringify(parametrosIndicaciones.arregloFilasSeleccionadas);
        desplegarModalConfirmacion ();
    });
    //Botón ver hoja imagenología
    $(".verHojaImagenologia").on("click", function(){
        const idIndicacion = $(this).attr("id").split("-").at(0);
        if (idIndicacion === undefined || idIndicacion === null) {
            return;
        }
        const imprimir = function(){
    		$('#iframeSolicitudImagenologia').get(0).contentWindow.focus();
			$("#iframeSolicitudImagenologia").get(0).contentWindow.print();
		}
        const botones =   [{
            id: 'btnImprimir',
            value: '<i class="glyphicon glyphicon-print" aria-hidden="true"></i> Imprimir',
            function: imprimir,
            class: 'btn btn-primary btnPrint'
        }];
        modalFormulario("<label class='mifuente ml-2'>Solicitud Imagenología</label>",`${raiz}/views/modules/rce/rce/hojaImagenologia.php`, `idIndicacion=${idIndicacion}`, "#PDFRegistroExamen", "modal-lg", "light",'', botones);
    });
     //Botón ver hoja imagenología Dalca
     $(".verHojaImagenologiaDalca").on("click", function(){
        const idSolicitudDalca = $(this).attr("id").split("-").at(0);
        if (idSolicitudDalca === undefined || idSolicitudDalca === null) {
            return;
        }
        modalFormulario("<label class='mifuente ml-2'>Solicitud Imagenología</label>",`${raiz}/views/modules/rce/rce/hojaImagenologiaDalca.php`, `idSolicitudDalca=${idSolicitudDalca}`, "#PDFSolicitudImagenologiaDalca", "modal-lg", "light",'', '');
    });
    //Botón ver solicitud imagenología Dalca
    $(".verInformeSolicitudImagenologiaDalca").on("click", function(){
        const idSolicitudDalca = $(this).attr("id");
        ajaxRequest(`${raiz}/controllers/server/rce/indicaciones/main_controller.php`, `idSolicitudDalca=${idSolicitudDalca}&accion=obtenerInformeSolicitudDalca`, 'POST', 'JSON', 1,'Obteniendo Informe Integración DALCA...', funcionCallback);
        function funcionCallback(respuestaAjaxRequest) {
            if (respuestaAjaxRequest === undefined || respuestaAjaxRequest === null || respuestaAjaxRequest === "") {
                texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error al obtener Informe </h4>  <hr>  <p class="mb-0">El informe de la solicitud de imagenología de la integración DALCA aún no se encuentra realizado, favor vuelva a intentar más rato.</p></div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                return;
            }
        modalFormulario("<label class='mifuente ml-2'>Informe Solicitud Imagenología</label>",`${raiz}/views/modules/rce/rce/informeImagenologiaDalca.php`, `informeDalca=${encodeURIComponent(respuestaAjaxRequest)}`, "#InformeSolicitudImagenologiaDalca", "modal-lg", "light",'', '');
        }
	});
    //Botón ver imágenes solicitud imagenología Dalca
	$(".verImagenSolicitudImagenologiaDalca").on("click", function(){
        const idSolicitudDalca = $(this).attr("id");
        ajaxRequest(`${raiz}/controllers/server/rce/indicaciones/main_controller.php`, `idSolicitudDalca=${idSolicitudDalca}&accion=obtenerImagenSolicitudDalca`, 'POST', 'JSON', 1,'Obteniendo Imágenes Integración DALCA...', funcionCallback);
        function funcionCallback(respuestaAjaxRequest) {
            if (respuestaAjaxRequest === undefined || respuestaAjaxRequest === null || respuestaAjaxRequest === "") {
                texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error al obtener Imágenes </h4>  <hr>  <p class="mb-0">Las imágenes de la solicitud de imagenología de la integración DALCA aún no se encuentra disponibles, favor vuelva a intentar más rato.</p></div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                return;
            }
            urlImagen = respuestaAjaxRequest.attending_doctor_to_study_link;
            window.open(urlImagen, "_blank", "width=1000,height=1000").focus();
        }
	});
    function confirmarAccionEnIndicacion ( ) {
        const tipoFormulario = $("#tipoFormulario").val();
        if ( tipoFormulario == "gestionRealizada" ) {
            if ( ! seHaIngresadoDatosGestionRealizada() ) {
                return;
            }
        }
        modalConfirmacionNuevo("Advertencia", `ATENCIÓN, se procederá a ${parametrosIndicaciones.tipoAccion} el Examen, <b>¿Desea continuar?</b>`, "primary", parametrosIndicaciones.funcion);
    }
    function aplicarAccionIndicacion ( ) {
        const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/indicaciones/main_controller.php`, $("#frm_modal_observacion").serialize()+`&indicacion_id=${ind_id}&dau_id=${dau_id}&rce_id=${id_rce}&idPaciente=${idPaciente}&accion=${parametrosIndicaciones.accion}`, 'POST', 'JSON', 1,'Aplicando Acción en Indicación...');
        switchRespuestaAjaxRequest( respuestaAjaxRequest );
    }
    function aplicarGestionRealizada ( ) {
        const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/indicaciones/main_controller.php`, $("#frm_ingresarSolicitudEspecialista").serialize()+`&accion=${parametrosIndicaciones.accion}`, 'POST', 'JSON', 1,'Aplicando Acción en Indicación...');
        switchRespuestaAjaxRequest( respuestaAjaxRequest );
    }
    function desplegarModalConfirmacion ( ) {
        modalConfirmacionNuevo("Advertencia", `ATENCIÓN, se procederá a ${parametrosIndicaciones.tipoAccion} los Exámanes, <b>¿Desea continuar?</b>`, "primary", confirmarAccionEnIndicaciones);
    }
    function verificarIndicacionesSeleccionadas ( ) {
        let bandera = true;
        parametrosIndicaciones.arregloFilasSeleccionadas = [];
        table_reference.rows('.ui-selected').every( function ( rowIdx, tableLoop, rowLoop ) {
            let  arr_filas = [];
            arr_filas[0] = table_reference.row(rowIdx).data();
            arr_filas[0]['DT_RowId'];
            if ( $(`#${arr_filas[0]['DT_RowId']} ${parametrosIndicaciones.claseIndicaciones}`).is(':hidden') ) {
                bandera = false;
                return false;
            }
            parametrosIndicaciones.arregloFilasSeleccionadas.push(arr_filas[0]['DT_RowId']);
        });
        return bandera;
    }
    async function confirmarAccionEnIndicaciones ( ) {
        const estadoPermiso = await validarPermisoUsuario('btn_aplicar_indicaciones');
        if (estadoPermiso) {
            aplicarAccionIndicaciones();
        }
    }
    function aplicarAccionIndicaciones ( ) {
        const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/indicaciones/main_controller.php`, $("#frm_modal_observacion").serialize()+`&arregloTablaRow=${parametrosIndicaciones.arregloFilasSeleccionadas}&dau_id=${dau_id}&rce_id=${id_rce}&idPaciente=${idPaciente}&accion=${parametrosIndicaciones.accion}`, 'POST', 'JSON', 1,'Aplicando Acciones a Indicaciones...');
        switchRespuestaAjaxRequest(respuestaAjaxRequest);
    }
    function llamarModalMensajeBtnExit () {
        texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> ATENCIÓN! </h4>  <hr>  <p class="mb-0">Ha ocurrido un error con las indicaciones, favor de <b>Revisar los Estados Correspondientes</b> a los tipos de las indicaciones.</p></div>';
            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
    }
    function switchRespuestaAjaxRequest ( respuestaAjaxRequest ) {
        switch ( respuestaAjaxRequest.status ) {
            case "success":
                refrescarVentanas();
            break;
            case "error":
                if ( parametrosIndicaciones.accion === 'aplicarIndicacion'){
                    mensaje = 'Error en aplicar indicación:<br><br>'+respuestaAjaxRequest.message;
                }
                if ( parametrosIndicaciones.accion === 'anularIndicacion'){
                    mensaje = 'Error en anular indicación:<br><br>'+respuestaAjaxRequest.message;
                }
                if ( parametrosIndicaciones.accion === 'tomaMuestra'){
                    mensaje = 'Error en tomar muestra indicación:<br><br>'+respuestaAjaxRequest.message;
                }
                if ( parametrosIndicaciones.accion === 'iniciarIndicacion'){
                    mensaje = 'Error en iniciar indicación:<br><br>'+respuestaAjaxRequest.message;
                }
                if ( parametrosIndicaciones.accion === 'aplicarMultiplesFilas' ){
                    mensaje = 'Error en aplicar indicaciones:<br><br>'+respuestaAjaxRequest.message;
                }
                if ( parametrosIndicaciones.accion === 'anularMultiplesFilas'){
                    mensaje = 'Error en anular indicaciones:<br><br>'+respuestaAjaxRequest.message;
                }
                if ( parametrosIndicaciones.accion === 'tomarMuestrasIndicaciones'){
                    mensaje = 'Error en tomar muestras de las indicaciones:<br><br>'+respuestaAjaxRequest.message;
                }
                if ( parametrosIndicaciones.accion === 'iniciarIndicaciones'){
                    mensaje = 'Error en iniciar indicaciones:<br><br>'+respuestaAjaxRequest.message;
                }
                var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en el proceso </h4>  <hr>  <p class="mb-0">'+mensaje+'.</p></div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
            break;
            default:
                ErrorSistemaDefecto();
            break;
        }
    }



    function refrescarVentanas () {
        ajaxContent(`${raiz}/views/modules/Enfermera/detalleIndicaciones.php`, `dau_id=${dau_id}&regId=${id_rce}`, `#contenidoImagenologia_${dau_id}`, '', true);
        if ( banderapiso == 'INDICACIONESENF' ) {
                ajaxContent(`${raiz}/views/modules/enfermera/enf_indicaciones.php`, 'frm_numero_dau='+$("#frm_numero_dau_session").val()+'&frm_tipoCategorizacion='+$("#frm_tipoCategorizacion_session").val()+'&frm_nombrePaciente='+$("#frm_nombrePaciente_session").val()+'&frm_rut='+$("#frm_rut_session").val(), '#contenidoDAU', '', true);

            }
        $('#modalObservacion').modal( 'hide' ).data( 'bs.modal', null );
        $('#gestionRealizadaIndicacion').modal( 'hide' ).data( 'bs.modal', null );
    }



    function seHaIngresadoDatosGestionRealizada ( ) {

        if ( $("#frm_ingresarSolicitudEspecialista #frm_especialistaDeLlamado").is(":checked") && ! $("#frm_ingresarSolicitudEspecialista #frm_gestionRealizada").is(":checked") ) {

            $checkGestionRealizada.assert(false, "Debe Marcar Opción");

            return false;

        }

        if ( $("#frm_ingresarSolicitudEspecialista #frm_gestionRealizada").is(":checked") && $("#frm_ingresarSolicitudEspecialista #frm_medicoEspecialista").val() == null ) {

            $("#frm_ingresarSolicitudEspecialista #frm_medicoEspecialista").assert(false, "Debe Seleccionar Médico");

            return false;

        }

        return true;

    }

});
