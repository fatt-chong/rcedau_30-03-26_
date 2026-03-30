$(document).ready(function(){

	let dau_id              = $('#dau_id').val(),
	tipoMapa 				= $('#tipoMapa').val(),
    rce_id                  = $('#rce_id').val(),
    idPaciente              = $('#id_paciente').val(),
    tipoAtencion            = $("#tipoAtencion").val(),
    idctacte            	= $("#idctacte").val(),
    estadoDau            	= $("#estadoDau").val(),
    actualizarSeguimiento   = false;
	banderapiso             = 'RCE';
	cd                      = 1;
	async function cargarSeccionPaciente(dau_id, rce_id,idPaciente) {
		let c = await ajaxContentFast('/RCEDAU/views/modules/rce/medico/diagnostico.php','dau_id='+dau_id+'&rce_id='+rce_id+'&idPaciente='+idPaciente+'&idctacte='+idctacte,'#div_diagnostico');
        // if( $('#estadoDau').val() != 8 ){

        // }
		// let d =  ajaxContentFast('/RCEDAU/views/modules/rce/medico/bitacora.php','dau_id='+dau_id+'&rce_id='+rce_id+'&idPaciente='+idPaciente,'#div_bitacora');

		// let e =  ajaxContent('/RCEDAU/views/modules/rce/medico/indicacion.php','dau_id='+dau_id+'&rce_id='+rce_id+'&idPaciente='+idPaciente,'#div_indicacion');
		if($('#inicioAtencion').val() == 0){

			let f = await ajaxContentFast('/RCEDAU/views/modules/rce/rce/inicioAtencion.php','dau_id='+dau_id+'&rce_id='+rce_id+'&idPaciente='+idPaciente+'&rce=1','#div_inicioAtencion');
		}else{
            let d =  ajaxContentFast('/RCEDAU/views/modules/rce/medico/bitacora.php','dau_id='+dau_id+'&rce_id='+rce_id+'&idPaciente='+idPaciente,'#div_bitacora');

            let e =  ajaxContentFast('/RCEDAU/views/modules/rce/medico/indicacion.php','dau_id='+dau_id+'&rce_id='+rce_id+'&idPaciente='+idPaciente,'#div_indicacion');
        }
	}
	cargarSeccionPaciente(dau_id, rce_id,idPaciente);
	$("#btn_historial_link").click(function(){

		modalFormulario('<label class="mifuente text-primary">Historial Clinico</label>',raiz+"/views/modules/rce/rce/historial_clinico.php",`paciente_id=${idPaciente}`,'#modal_historial','modal-lg','', 'fas fa-laptop-medical text-primary','');
	});
	$(".volverWorklist_detalle").click(function(){
        if ( perfilUsuario == 'full' ) {
            ajaxContentSlideLeft(raiz+'/views/modules/mapa_piso_full/detalle_dau/detalle_dau.php', 'dau_id='+dau_id+'&tipoMapa='+tipoMapa+'&banderapiso'+banderapiso+'&perfilUsuario'+perfilUsuario, '#contenido');
        }else{
        	ajaxContentSlideLeft(raiz+localStorage.getItem('urlAtras'),localStorage.getItem('parametrosAtras'), '#contenido');
        }
    });
    $(".btnDau").click(function(){

		modalFormulario_noCabecera('', raiz+"/views/modules/rce/rce/dau_detalle.php", 'dau_id='+dau_id+'&btn=N', "#modalDetalleCategorizacion", "modal-lg", "", "fas fa-plus");
	});	
	$('.agregarAnt').click(function(event){
		if ( perfilUsuario === 'administrativo') {
            return;
        }
        var idAntecedente  = parseInt($(this).attr('id').replace('agrAntecedente',''));
        var confirmarAntecedentes = function(){
            $.validity.start();
            if($("#frm_fecha_inicio").val() == ""){
                $('#frm_fecha_inicio').assert(false,"Debe indicar la fecha.");

            }
            if($("#frm_fecha_termino").val() == ""){
                $('#frm_fecha_termino').assert(false,"Debe indicar la fecha.");
            }
            result = $.validity.end();
            if(result.valid==false){
                return false;
            }
            removerValidity();
                var respAntecedentes = function(response){
                    switch(response.status){
                        case "success":
                        	ajaxContentFast(`${raiz}/views/modules/rce/medico/rce.php`,'tipoMapa='+tipoMapa+`&dau_id=${dau_id}`, '#contenido');
                        break;
                        case "error":
                            modalMensajNoCabecera('','',  "#modal", "modal-md", "success");
                        break;
                        default:
                            modalMensajNoCabecera('','',  "#modal", "modal-md", "success");
                        break;
                    }
                    $('#modalIngresarAntecedentes').modal( 'hide' ).data( 'bs.modal', null );
                };
            ajaxRequest(raiz+'/controllers/server/medico/main_controller.php',$('#frm_ingreso_antecedente').serialize()+'&idctacte='+idctacte+'&paciente_id='+idPaciente+'&rce_id='+rce_id+'&accion=guardarAntecedentes', 'POST', 'JSON', 1,'Cargando...',respAntecedentes);
        }
        var botones =   [
                            { id: 'btn_add_antecedente', value: ' Agregar Antecedentes', function: confirmarAntecedentes, class: 'btn btn-primary' }
                        ];
        modalFormulario("<label class='mifuente'>Ingresar Antecedentes  </label>",raiz+'/views/modules/rce/medico/ingresar_antecendetes.php','idAntecedente='+idAntecedente+'&paciente_id='+idPaciente+'&rce_id='+rce_id+'&idctacte='+idctacte,'#modalIngresarAntecedentes',"modal-md","primary","fas fa-folder-plus",botones);
    })
    $('.btn_add_diagnostico').click(async function () {
    	const estadoValido = await validarEstadoPaciente($('#dau_id').val());
	    if (estadoValido) {
			var btn_agregar_diag = function(){
		        $.validity.start();
		        if($("#hidden_frm_diagnostico").val() == ""){
		            $('#frm_diagnostico').assert(false,"Debe indicar el Diagnostico.");
		        }
		        let rs_consultar_cie10_TABLA = consultar_cie10_TABLA($("#hidden_frm_diagnostico").val());
		        if (rs_consultar_cie10_TABLA) {
		            $(".btn_add_diagnostico").assert(false,'Cie10 Existente');
		            $("#hidden_frm_diagnostico_descrip").val('');
		            $("#hidden_frm_diagnostico").val('');
		            $("#frm_diagnostico").val('');
		            $("#frm_diagnostico").attr("readonly", false); 
		            $("#frm_diagnostico").focus();
		            const myTimeout = setTimeout(removerAviso, 1500);
		        }
		        result = $.validity.end();
		        if(result.valid==false){
		            return false;
		        }
		        var respDiagnostico = function(response){
	                $("#modal_btn_add_diagnostico").modal( 'hide' ).data( 'bs.modal', null );
					ajaxContent(raiz+'/views/modules/rce/medico/rce.php','tipoMapa='+tipoMapa+`&dau_id=${dau_id}`,'#contenido','', true);
		        };
		        ajaxRequest(raiz+'/controllers/server/medico/main_controller.php','hidden_frm_diagnostico='+$('#hidden_frm_diagnostico').val()+'&rce_id='+$('#rce_id').val()+'&dau_id='+$('#dau_id').val()+'&rce_evolucion_id='+$('#rce_evolucion_id').val()+'&hidden_frm_diagnostico_descrip='+$('#hidden_frm_diagnostico_descrip').val()+'&accion=guardarDiagnostico&cta_cte='+idctacte, 'POST', 'JSON', 1,'Cargando...',respDiagnostico);                          
	        };
			var botones =   [
	                            { id: 'btn_agregar_diag', value: ' Guardar', function: btn_agregar_diag, class: 'btn btn-success' }
	                        ];
			modalFormulario('<label class="mifuente text-primary">Agregar Diagnóstico</label>',raiz+"/views/modules/rce/medico/agregar_diagnostico.php",'rce_id='+$('#rce_id').val()+'&rce_evolucion_id='+$('#rce_evolucion_id').val(),'#modal_btn_add_diagnostico','modal-md','', 'fas fa-align-justify text-primary',botones);
	    }
	});
	$('#btn_agregar_evolucion').click(async function () {
	    const estadoValido = await validarEstadoPaciente($('#dau_id').val());
	    if (estadoValido) {
	    	const estadoPermiso = await validarPermisoUsuario('btn_rce_guardar');
	    	if (estadoPermiso) {
	        	$.validity.start();
		        if($("#frm_historia_clinica").val() == ""){
		            $('#frm_historia_clinica').assert(false,"Debe escribir la historia clínica.");
		        }
		        result = $.validity.end();
		        if(result.valid==false){
		            return false;
		        }
		        var respServidor = function(response){
					ajaxContent(raiz+'/views/modules/rce/medico/rce.php','tipoMapa='+tipoMapa+`&dau_id=${dau_id}`,'#contenido','', true);
		        };
		        ajaxRequest(raiz+'/controllers/server/medico/main_controller.php',$('#formulario_rce_medico').serialize()+'&accion=ingresarSolicitudEvolucion', 'POST', 'JSON', 1,'Cargando...',respServidor);
			}
	    }
	});
	$("#btnAgregarIndicaciones").click(async function () {
		if ( perfilUsuario === 'administrativo') {
            return;
        }
        ajaxRequest(`${raiz}/controllers/server/rce/indicaciones/main_controller.php`, 'accion=DestruirSesionSecciones', 'POST', 'JSON', 1);
        let botones =   [
                            { id: 'btn_Agregar_Examenes', value: 'Guardar', class: 'btn btn-primary' , function: guardarIndicaciones}
                        ];

        modalFormulario("<label class='mifuente ml-2'>Añadir indicaciones</label>", `${raiz}/views/modules/rce/indicaciones/cargar_indicaciones_modal.php`, `dau_id=${dau_id}&rce_id=${rce_id}&banderaRCE=1&tipoMapa=${tipoMapa}`, "#agregarExamen", "modal-lg", "light",'', botones);
	});
	$("#btnAgregarEspecialista").click(function(){
        if ( perfilUsuario === 'administrativo') {
            return;

        }
        let botones =   [
                            { id: 'btnGuardarEspecialista', value: 'Guardar Solicitud', class: 'btn btn-primary' }
                        ];
        modalFormulario("<label class='mifuente ml-2'>Elección de Especialista</label>", `${raiz}/views/modules/rce/especialista/especialista.php`, `dau_id=${dau_id}&paciente_id=${idPaciente}&banderaRCE=1&tipoMapa=${tipoMapa}`, "#modalEspecialista", "modal-lg", "light",'', botones);
    });
    $("#btnAgregarEspecialistaOtro").click(function(){
        if ( perfilUsuario === 'administrativo') {
            return;

        }
        let botones =   [
                            { id: 'btnGuardarEspecialistaOtro', value: 'Guardar Solicitud', class: 'btn btn-primary' }
                        ];
        modalFormulario("<label class='mifuente ml-2'>Otro Especialista</label>", `${raiz}/views/modules/rce/especialista/OtroEspecialista.php`, `dau_id=${dau_id}&paciente_id=${idPaciente}&banderaRCE=1&tipoMapa=${tipoMapa}`, "#modalEspecialista", "modal-lg", "light",'', botones);
    });
    $(".formulariosEnfermeriaMenu").click(function(){
        modalFormulario_noCabecera('', raiz+"/views/modules/formularios/contenido_formularios.php", `dau_id=${dau_id}`, "#modalFormulariosEnfermeriaMenu", "modal-lgg", "", "fas fa-plus",'');
    });
    
    $("#btnTransfusiones").click(function(){
        modalFormulario_noCabecera('', raiz+"/views/modules/rce/rce/transfusiones.php", `id_paciente=${idPaciente}&dau_id=${dau_id}&tipoMapa=${tipoMapa}&rce_id=${rce_id}`, "#modalTransfusiones", "modal-lgg", "", "fas fa-tint",'');
    });

    $("#btnAltaUrgencia").click(function(){
        if ( perfilUsuario === 'administrativo') {
            return;
        }
        if ( ! verificarSignosVitales() ) {
            return;
        }
        if ( pacienteEgresado() ) {
        	texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error </h4>  <hr>  <p class="mb-0">Este Paciente ya fue dado de Alta (Posiblemente por Otra Persona o DAU Automático), no se puede indicar egreso.</p></div>';
            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
        	ajaxContentSlideLeft(`${raiz}/views/modules/mapa_piso_full/mapa_piso_full.php`,'tipoMapa='+tipoMapa, '#contenido');
            return;
        }
        let botones =   [
                            { id: 'btnGrabarEgreso', value: 'Indicar Egreso', class: 'btn btn-primary' }
                        ];
        modalFormulario("<label class='mifuente ml-2'>Datos de Atención de Urgencia</label>", `${raiz}/views/modules/rce/alta_urgencia/alta_urgencia.php`, `dau_id=${dau_id}&paciente_id=${idPaciente}&banderaRCE=1&tipoMapa=${tipoMapa}`, "#modalAltaUrgencia", "modal-lg", "light",'', botones);
    });
    $(".verHospitalAmigo").on("click", function() {
        const idDau = $(this).attr("id");
        if (
            idDau === null
            || idDau === undefined
            || idDau === ""
            || idDau === "0"
        ) {
            console.error("verHospitalAmigo: No existe idDau");
            return;
        }

        const titulo = (!pacienteEgresado())
            ? "Ingresar Acompañante"
            : "Acompañante";
        const botones = (!pacienteEgresado())
            ?   [{
                    id: "btnIngresarAcompaniante",
                    value: 'Ingresar Acompañante',
                    class: 'btn btn-primary'
                }]
            :   [];

        modalFormulario("<label class='mifuente ml-2'>"+titulo+"</label>", `${raiz}/views/modules/rce/rce/acompaniante.php`, `idDau=${dau_id}`, "#acompaniante", "modal-lg", "light",'', botones);
    });
    function pacienteEgresado ( ) {
        const parametros 		   =  {idDau : dau_id, accion : 'pacienteEgresado'};
        const respuestaAjaxRequest = ajaxRequest(raiz+'/controllers/server/medico/main_controller.php', parametros, 'POST', 'JSON', 1);
        if ( respuestaAjaxRequest.status == 'success' ) {
            return true;
        }
	    return false;
    }
    function verificarSignosVitales ( ) {
        if ( tipoAtencion != 1 ) {
            return true;
        }
        let banderaError = false;
        let camposFaltantes = "";
        const parametrosAEnviar = { 'idRCE' : rce_id , 'accion' : 'buscarIngresoSignosVitalesPrioritarios' };
        const respuestaAjaxRequest = ajaxRequest( `${raiz}/controllers/server/medico/main_controller.php`, parametrosAEnviar, 'POST', 'JSON', 1, '');
        if ( respuestaAjaxRequest.totalSignoVitalPulso == 0 ) {
            camposFaltantes += "<br> - Pulso";
            banderaError = true;
        }
        if ( respuestaAjaxRequest.totalSignoVitalSistolica == 0 && respuestaAjaxRequest.totalSignoVitalDiastolica) {
            camposFaltantes += "<br> - Presión Arterial";
            banderaError = true;
        }
        if ( respuestaAjaxRequest.totalSignoVitalTemperatura == 0 ) {
            camposFaltantes += "<br> - Temperatura";
            banderaError = true;
        }
        if ( respuestaAjaxRequest.totalSignoVitalFR == 0 ) {
            camposFaltantes += "<br> - Frecuencia Respiratoria";
            banderaError = true;
        }
        if ( respuestaAjaxRequest.totalSignoVitalSaturacion == 0 ) {
            camposFaltantes += "<br> - Saturación";
            banderaError = true;
        }
        if ( respuestaAjaxRequest.totalSignoVitalEVA == 0 ) {
            camposFaltantes += "<br> - EVA";
            banderaError = true;
        }
        if ( respuestaAjaxRequest.totalSignoVitalGlasgow == 0 ) {
            camposFaltantes += "<br> - Glasgow";
            banderaError = true;
        }
        if ( banderaError === true ) {
        	texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en Indicar Alta Urgencia </h4>  <hr>  <p class="mb-0">Faltan Signos Vitales Prioritarios que se deben tomar al paciente, los cuales son: <br>'+camposFaltantes+'.</p></div>';
            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
			return false;
		}
        return true;
    }
	async  function guardarIndicaciones ( ) {
	 	const estadoValido = await validarEstadoPaciente($('#dau_id').val());
	    if (estadoValido) {
	    	 if ( typeof ingresoExamenCovid !== 'undefined' && ingresoExamenCovid == true ) {
	    	 	 texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Alerta en Registrar Seguimiento </h4>  <hr>  <p class="mb-0">Alerta en resgistrar seguimiento enfermedad respiratoria: <b>Paciente ya cuenta con un registro de seguimiento</b></p></div>';
          		modalConfirmacionNoExit("<label class='mifuente'>Advertencia</label>", texto, "primary", desplegarModalSeguimientoEnfermedadRespiratoria);
            	return;
        	}
        	if ( ! verificarDatosIndicaciones() ) {
	            return;
	        }
	        obtenerCarroIndicaciones();
	        const estadoPermiso = await validarPermisoUsuario('btn_rce_guardar');
	    	if (estadoPermiso) {
	    		texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Alerta en Registrar Seguimiento </h4>  <hr>  <p class="mb-0">ATENCIÓN, se procedera a guardar los Examenes, <b>¿Desea continuar?</b></b></p></div>';
                 modalConfirmacionNuevo("Advertencia", "ATENCIÓN, se procedera a guardar los Examenes, <b>¿Desea continuar?</b>", "primary", confirmarGuardarIndicaciones);
                 
          		// modalConfirmacionNoExit("<label class='mifuente'>ATENCIÓN</label>", texto, "primary", confirmarGuardarIndicaciones);
	    	}
	    }
    }
    function realizarAplicacionEgreso ( ) {
        if( ! pacienteTieneIndicacionesNoSuperfluas() ) {
            return;
        }
        if ( ! verificarDatosAplicacionEgreso() ) {
            return;
        }
        const postIndicacionEgreso = $("#postIndicacionEgreso").val();
        if ( ! postIndicacionEgreso && ! tiempoPermitidoTranscurridoDesdeIndicacionEgreso(dau_id,tipoMapa) ) {
            $('#modalIndicacionAplica').modal( 'hide' ).data( 'bs.modal', null );
            return;
        }
        modalConfirmacionNuevo("Advertencia", "ATENCIÓN, se procedera a registrar la indicación de egreso, <b>¿Desea continuar?</b>", "primary", registrarAplicacionIndicacion);
    }
    async function registrarAplicacionIndicacion( ) {
        const estadoValido = await pacienteYaConNEA($('#dau_id').val(),tipoMapa);
        if (estadoValido) {
            if ( pacienteEgresado() ) {
                texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error </h4>  <hr>  <p class="mb-0">Este Paciente ya fue dado de Alta (Posiblemente por Otra Persona o DAU Automático), no se puede indicar egreso.</p></div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                ajaxContentSlideLeft(`${raiz}/views/modules/mapa_piso_full/mapa_piso_full.php`,'tipoMapa='+tipoMapa, '#contenido');
                $('#modalIndicacionAplica').modal( 'hide' ).data( 'bs.modal', null );
                return;
            }
            const estadoPermiso = await validarPermisoUsuario('btn_indicacionAplica');
            if (estadoPermiso) {
                // let funcion = function ( ) {
                    const respuestaAjaxRequest = ajaxRequest( `${raiz}/controllers/server/dau/main_controller.php`, $("#frmIndicacionAplica").serialize()+`&dau_id=${dau_id}&paciente_id=${idPaciente}&accion=registrarIndicacionAplica`, 'POST', 'JSON', 1,'Cerrando DAU...');
                    switch ( respuestaAjaxRequest.status ) {
                        case "success":
                            if ( respuestaAjaxRequest.message != '' ) {
                                if ( respuestaAjaxRequest.typeMessage != '' ) {
                                    var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> ATENCIÓN! </h4>  <hr>  <p class="mb-0">'+respuestaAjaxRequest.message+'</p></div>';
                                    modalConfirmacionNoExit("<label class='mifuente'>Advertencia</label>", texto, "primary", '');
                                } else {
                                    var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> ATENCIÓN! </h4>  <hr>  <p class="mb-0">'+respuestaAjaxRequest.message+'</p></div>';
                                    modalConfirmacionNoExit("<label class='mifuente'>Advertencia</label>", texto, "primary", '');

                                }
                            }
                            $('#modalIndicacionAplica').modal( 'hide' ).data( 'bs.modal', null );
                            refrescarRCE();
                            imprimirRCE();
                        break;
                        case "warning":
                            texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> ADVERTENCIA </h4>  <hr>  <p class="mb-0">'+respuestaAjaxRequest.message+'</p></div>';
                            modalConfirmacionNoExit("<label class='mifuente'>Advertencia</label>", texto, "primary", '');
                            $('#modalIndicacionAplica').modal( 'hide' ).data( 'bs.modal', null );
                            refrescarRCE();
                        break;
                        case "error":
                            texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en el proceso </h4>  <hr>  <p class="mb-0">Error en registrar Aplicar Indicación de Egreso:<br><br>'+respuestaAjaxRequest.message+'.</p></div>';
                            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success")
                        break;
                        default:
                            ErrorSistemaDefecto();
                        break;
                    }
                // }
            }
        }else{
            $('#modalIndicacionAplica').modal( 'hide' ).data( 'bs.modal', null );
        }
    }
    function refrescarRCE ( ) {

        ajaxContentSlideLeft(`${raiz}/views/modules/mapa_piso_full/mapa_piso_full.php`,'tipoMapa='+tipoMapa, '#contenido');
    }
    function imprimirRCE ( ) {

        modalFormulario_noCabecera('', raiz+"/views/modules/rce/rce/ver_rce.php", "rce_id="+rce_id+'&dau_id='+dau_id+'&banderaLlamada=altaUrgencia', "#detalle_rce_pdf", "modal-lg", "", "fas fa-plus");
    }
    function verificarDatosAplicacionEgreso ( ) {
        $.validity.start();
        if ( $('#frm_fecha_date').val() == "" ) {
            $('#frm_fecha_date').assert(false,'Debe Indicar la fecha de egreso');
            $.validity.end();
            return false;
        }
        if ( $('#frm_hora_date').val() == "" ) {
            $('#frm_hora_date').assert(false,'Debe Indicar la hora de egreso');
            $.validity.end();
            return false;
        }
        result = $.validity.end();
        if ( result.valid == false ) {
            return false;
        }
        return true;
    }
    function pacienteTieneIndicacionesNoSuperfluas () {
        const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/medico/main_controller.php`, `idDau=${dau_id}&accion=pacienteTieneIndicacionesNoSuperfluas`, 'POST', 'JSON', 1);
        switch ( respuestaAjaxRequest.status ) {
            case "success":
                if ( respuestaAjaxRequest.solicitudesAplicadas == 1 ) {
                    return true;
                } else {
                    texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error al Aplicar Egreso </h4>  <hr>  <p class="mb-0">El paciente presenta solicitudes de Procedimiento, Tratamientos y/u Otros aún no aplicados<br><br>.</p></div>';
                    modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                }
            break;
            case "error":
                texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en el proceso </h4>  <hr>  <p class="mb-0">Error en verificar si paciente presenta solicitudes aún no aplicadas:<br><br>'+respuestaAjaxRequest.message+'.</p></div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
            break;
            default:
                ErrorSistemaDefecto();
            break;
        }
        return false;
    }
    function confirmarGuardarIndicaciones ( ) {
        const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/indicaciones/main_controller.php`, $('#frm_contenido_indicaciones').serialize()+'&'+$('#frm_des_ima').serialize()+'&accion=insertarIndicaciones', 'POST', 'JSON', 1,'Guardando indicaciones...');
        switch(respuestaAjaxRequest.status){
            case "success":
                $('#agregarExamen').modal( 'hide' ).data( 'bs.modal', null );
                ajaxContentFast(`${raiz}/views/modules/rce/medico/rce.php`,'tipoMapa='+tipoMapa+`&dau_id=${dau_id}`, '#contenido');
            break;
            case "error":
				ErrorSistemaDefecto();
            break;
            default:
				ErrorSistemaDefecto();
            break;
        }
    }
    function verificarDatosIndicaciones ( ) {
        const tablaImagenologia   = $('#tablaContenido >tbody >tr').length;
        const tablaTratamiento    = $('#table_Tratamiento >tbody >tr').length;
        const tablaOtros          = $('#table_Otros >tbody >tr').length;
        const tablaProcedimiento  = $('#table_tratamientoNuevo >tbody >tr').length;
        const checkboxlaboratorio = $("[name='frm_laboratorio']").filter(':checked').length;
        if ( tablaImagenologia == 0 && tablaTratamiento == 0 && checkboxlaboratorio == 0 && tablaOtros == 0 && tablaProcedimiento == 0 ) {
        	texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> ATENCIÓN! </h4>  <hr>  <p class="mb-0">Debe agregar algún examen para poder guardar.</p></div>';
            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
            return false;
        }
        if ( $('#frm_diagnostico').val() == '' && tablaImagenologia != 0 ) {
        	texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> ATENCIÓN! </h4>  <hr>  <p class="mb-0">Debe agregar algún examen para poder guardar.</p></div>';
            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
            return false;
        }
        return true;
    }
    function obtenerCarroIndicaciones ( ) {
        let arrayLaboratorio = [], arrayLaboratorioComplejo = [], arrayImagenologia = [], arrayOtros = [], arrayProcedimientos = [], arrayTratamientos = [], arrayTratamientosTexto = [];
        obtenerCarroImagenologia(arrayImagenologia);
        obtenerCarroTratamientos(arrayTratamientos);
        obtenerCarroLaboratorio(arrayLaboratorio);
        obtenerCarroLaboratorioComplejo(arrayLaboratorioComplejo);
        obtenerCarroProcedimientos(arrayProcedimientos, arrayTratamientosTexto);
        obtenerCarroOtros(arrayOtros);
    }
    function obtenerCarroImagenologia ( arrayImagenologia ) {
        $("#contenidoRayo tr").each(function(element){
            let imagenologiaA = [];
            imagenologiaA[0] = $(this).find("td.ima_valorExamen").text().trim();
            imagenologiaA[1] = $(this).find("td.ima_valorTipoExamen").text().trim();
            imagenologiaA[2] = $(this).find("td.ima_valorLateralidad").text().trim();
            imagenologiaA[3] = $(this).find("td.ima_valorContrastes").text().trim();
            imagenologiaA[4] = $(this).find("td.ima_valorObservacion").text().trim();
            imagenologiaA[5] = $(this).find("td.ima_valorIdPrestacion").text().trim();
            imagenologiaA[6] = $(this).find("td.ima_valorPrestaciones").text().trim();
            arrayImagenologia.push(imagenologiaA);
        });
        arrayImagenologia = JSON.stringify(arrayImagenologia);
        $('#carroIma').val(arrayImagenologia);
    }
    function obtenerCarroTratamientos ( arrayTratamientos ) {
        $("#contenidoTratamientoNuevo tr").each(function(element){
            let traNue           = [3];
            traNue[0]            = $(this).find("td.frm_tratamientoNuevo_nombre").html();
            traNue[1]            = $(this).find("td.frm_idClasificacion").text();
            traNue[2]            = $(this).find("td.frm_clasificacionTratamiento").text();
            arrayTratamientos.push(traNue);
        });
        arrayTratamientos = arrayTratamientos.filter(
            function(a){return !this[a] ? this[a] = true : false;}, {}
        );
        arrayTratamientos = JSON.stringify(arrayTratamientos);
        $('#carroTratamiento').val(arrayTratamientos);
    }
    function obtenerCarroLaboratorio ( arrayLaboratorio ) {
        $("#frm_laboratorio_master2").find("input:checked").each(function(element) {
             console.log($(this).val())
            let allCheck        = [2];
            allCheck[0]         = $(this).val();
            allCheck[1]         = $('#lab'+allCheck[0]).val();
            allCheck[2]         = $('#cod'+allCheck[0]).val();
            allCheck[3]         = $('#tip'+allCheck[0]).val();
            console.log(allCheck[1])
            arrayLaboratorio.push(allCheck);
        });
        arrayLaboratorio = arrayLaboratorio.filter(
            function(a){return !this[a] ? this[a] = true : false;}, {}
        );
        arrayLaboratorio = JSON.stringify(arrayLaboratorio);
        $('#carroLab2').val(arrayLaboratorio);
    }
    function obtenerCarroLaboratorioComplejo ( arrayLaboratorioComplejo ) {
        $("#frm_laboratorio_master").find("input:checked").each(function(element) {
            let allCheck                = [2];
            allCheck[0]                 = $(this).val();
            allCheck[1]                 = $('#lab'+allCheck[0]).val();
            console.log(allCheck[1])
            arrayLaboratorioComplejo.push(allCheck);
        });
        arrayLaboratorioComplejo = arrayLaboratorioComplejo.filter(
            function(a){return !this[a] ? this[a] = true : false;}, {}
        );
        arrayLaboratorioComplejo = JSON.stringify(arrayLaboratorioComplejo);
        $('#carroLab').val(arrayLaboratorioComplejo);
    }
    function estadoAtencionDau ( estadoDau ) {
        if ( estadoDau == 4 || estadoDau == 5 ){
            return 'Documento RCE (Cerrado)';
        }
        if ( estadoDau == 6 ) {
            return 'Documento RCE (Anulado)';
        }
        if ( estadoDau == 7 ) {
            return 'Documento RCE (N.E.A.)';
        }
        return 'Documento RCE (Abierto)';
    }
    /////////////////////// COCHO MODIFICACION ///////////////////////////////
    function obtenerCarroProcedimientos ( arrayProcedimientos , arrayTratamientosTexto ) {
        $("#contenidoTratamiento tr").each(function(element){
            if($(this).find("td.trata_codigo").text()){
                let tratamientoA   = [2];
                tratamientoA[0]         = $(this).find("td.trata_codigo").text();
                tratamientoA[1]         = $(this).find("td.trata_nombre").text();
                checkBox = "texto"+tratamientoA[0];
                if ($("#"+checkBox).is(':checked')){
                    let tratamientoATexto   = [3];
                    tratamientoATexto[0] = tratamientoA[0];
                    tratamientoATexto[1] = $("#Areatexto"+tratamientoA[0]).val();
                    tratamientoATexto[2] = tratamientoA[1];
                    arrayTratamientosTexto.push(tratamientoATexto);
                }
                arrayProcedimientos.push(tratamientoA);
            }
        });
        arrayProcedimientos = arrayProcedimientos.filter(
            function(a){return !this[a] ? this[a] = true : false;}, {}
        );
        arrayProcedimientosTexto    = JSON.stringify(arrayTratamientosTexto);
        arrayProcedimientos         = JSON.stringify(arrayProcedimientos);
        $('#carroTra').val(arrayProcedimientos);
        $('#carroTraTexto').val(arrayProcedimientosTexto);
    }
    /////////////////////// COCHO MODIFICACION FIN ///////////////////////////////
    function obtenerCarroOtros ( arrayOtros ) {
        $("#contenidoOtro tr").each(function(element){
            let otrosA      = [1];
            otrosA[0]       = $(this).find("td.otro_nombre").html();
            arrayOtros.push(otrosA);
        });
        arrayOtros = arrayOtros.filter(
            function(a){return !this[a] ? this[a] = true : false;}, {}
        );
        arrayOtros = JSON.stringify(arrayOtros);
        $('#carroOtr').val(arrayOtros);
    }
    function desplegarModalSeguimientoEnfermedadRespiratoria ( ) {
        const parametros = { 'idPaciente' : $idPaciente.val() , 'accion' : 'verificarSeguimientoEnfermedadRespiratoria' }
      	const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/medico/main_controller.php`, parametros, 'POST', 'JSON', 1, '');
        if ( respuestaAjaxRequest != null && respuestaAjaxRequest.length != 0 ) {
            actualizarSeguimiento = true;
        }
        modalSeguimientoEnfermedadRespiratoria();
    }
    function modalSeguimientoEnfermedadRespiratoria ( ) {
        let botones =   [
                            { id: 'btnGuardarSeguimiento', value: '<i class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></i> Guardar', class: 'btn btn-primary btnPrint' }
                        ];
        if ( actualizarSeguimiento == true ) {

            botones =   [
                            { id: 'btnActualizarSeguimiento', value: '<i class="glyphicon glyphicon-refresh" aria-hidden="true"></i> Actualizar', class: 'btn btn-primary btnPrint' }
                        ];

        }
        modalFormulario("<label class='mifuente ml-2'>Formulario Seguimiento Enfermedad Respiratoria</label>", `${raiz}/views/modules/rce/indicaciones/seguimientoEnfermedadRespiratoria.php`, `idPaciente=${$idPaciente.val()}&idDau=${$idDau.val()}`, "#formularioSeguimiento3", "modal-lg", "primary",'', botones);
    }
	$("#btnSignosVitales").click(function(){
		
		modalFormulario_noCabecera('', raiz+"/views/modules/rce/signos_vitales/signos_vitales.php", `dau_id=${dau_id}&estadoDau=${estadoDau}&banderaRCE=1&tipoMapa=${tipoMapa}`, "#modalSignosVitales", "modal-lgg", "", "fas fa-plus",'');
	});
	$("#btnTiemposAtencion").click(function(){
		
		modalFormulario_noCabecera('', raiz+"/views/modules/rce/medico/linea_tiempo.php", `dau_id=${dau_id}&estadoDau=${estadoDau}&banderaRCE=1&tipoMapa=${tipoMapa}`, "#modalSignosVitales", "modal-lg", "", "fas fa-plus",'');
	});
	$("#aplicarNEA").click(function(){

		modalFormulario_noCabecera('', raiz+"/views/modules/rce/nea/modalAplicarNEA.php", 'banderaRCE=1&dau_id='+dau_id+'&tipoMapa='+tipoMapa, "#modalNEA", "modal-md", "", "fas fa-plus",'');
	});
	$('.verDetalleDau').on('click', function(){
		
		modalFormulario_noCabecera('', raiz+"/views/modules/rce/rce/ver_detalleDau.php", 'idDau='+dau_id, "#ver_detalleDau", "modal-lg", "", "fas fa-plus");
	});
	$('#btnVerRCE').on('click', function(){
        let banderaImpresionRCECompleto = true;

        let estadoDau = $('#estadoDau').val();

        let tituloModal = estadoAtencionDau(estadoDau);

        let alternar = function(){

            if ( banderaImpresionRCECompleto ) {

                ajaxContent(`${raiz}/views/modules/rce/rce/ver_rce.php`, `rce_id=${rce_id}&dau_id=${dau_id}&banderaLlamada=altaUrgenciaCompleto`, '#modalPDFRCEdiv', '', true);

                banderaImpresionRCECompleto = false;

            } else {

                ajaxContent(`${raiz}/views/modules/rce/rce/ver_rce.php`, `rce_id=${rce_id}&dau_id=${dau_id}`, '#modalPDFRCEdiv', '', true);

                banderaImpresionRCECompleto = true;

            }
        }

        let botones =   [
                            { id: 'btnAlternar', value: '<i class="glyphicon glyphicon-transfer" aria-hidden="true"></i> Alternar', function: alternar, class: 'btn btn-primary' }
                        ];
        modalFormulario("<label class='mifuente ml-2'>"+tituloModal+"</label>", raiz+"/views/modules/rce/rce/ver_rce.php", "rce_id="+rce_id+'&dau_id='+dau_id, "#detalle_rce_pdf", "modal-lg", "primary",'', botones);
    });
    $('#btnVerRCEIncompleto').on('click', function(){
        // alert()
        let banderaImpresionRCECompleto = true;
        let estadoDau = $('#estadoDau').val();
        let tituloModal = estadoAtencionDau(estadoDau);
        let alternar = function(){
            if ( banderaImpresionRCECompleto ) {
                ajaxContent(raiz+"/views/modules/rce/rce/ver_rce.php", "rce_id="+rce_id+'&dau_id='+dau_id+'&banderaLlamada=altaUrgenciaCompleto','#modalPDFRCEdiv','', true);
                banderaImpresionRCECompleto = false;
            } else {
                ajaxContent(raiz+"/views/modules/rce/rce/ver_rce.php", "rce_id="+rce_id+'&dau_id='+dau_id+'&banderaLlamada=altaUrgenciaIncompleto','#modalPDFRCEdiv','', true);
                banderaImpresionRCECompleto = true;
            }
        }
        let botones =   [
                            { id: 'btnAlternar', value: '<i class="glyphicon glyphicon-transfer" aria-hidden="true"></i> Alternar', function: alternar, class: 'btn btn-primary' }
                        ];

        modalFormulario("<label class='mifuente ml-2'>"+tituloModal+"</label>", raiz+"/views/modules/rce/rce/ver_rce.php", 'idPaciente='+idPaciente+'&dau_id='+dau_id, "#detalle_rce_pdf", "modal-lg", "primary",'', botones);


        // modalFormulario_noCabecera('', raiz+"/views/modules/rce/rce/ver_rce.php", "rce_id="+rce_id+'&dau_id='+dau_id+'&banderaLlamada=altaUrgenciaIncompleto', "#ver_detalleRCE", "modal-lg", "", "fas fa-plus");
    });
    $('#btnAplicarEgreso').on('click' , function(){
        if ( perfilUsuario === 'administrativo' || perfilUsuario === '' || perfilUsuario === undefined ) {
            return;
        }
        if ( pacienteEgresado() ) {
            texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error </h4>  <hr>  <p class="mb-0">Este Paciente ya fue dado de Alta (Posiblemente por Otra Persona o DAU Automático), no se puede indicar egreso.</p></div>';
            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
            ajaxContentSlideLeft(`${raiz}/views/modules/mapa_piso_full/mapa_piso_full.php`,'tipoMapa='+tipoMapa, '#contenido');
            return;
        }
        let botones =   [
                            { id: 'btnGuardar', value: '<i class="fa fa-save"  aria-hidden="true"></i> Registrar', function: realizarAplicacionEgreso, class: 'btn btn-primary' }
                        ];
        modalFormulario("<label class='mifuente ml-2'>Aplicar Indicación Egreso</label>", `${raiz}/views/modules/mapa_piso_full/detalle_dau/modalIndicacionAplica.php`, $("#frmIndicacionAplica").serialize()+`&dau_id=${dau_id}`, "#modalIndicacionAplica", "modal-lg", "light",'', botones);
    });
	$(".verHojaHospitalizacion").on("click", function(){

        let botones =   [
                            {
                                id      : 'btnGuardarHojaHospitalizacion',
                                value   : '<i class="glyphicon glyphicon-print" aria-hidden="true"></i> Imprimir',
                                class   : 'btn btn-primary btnPrint'
                            }

                        ]
        modalFormulario("<label class='mifuente ml-2'>Hoja Hospitalización</label>", `${raiz}/views/modules/rce/rce/hojaHospitalizacion.php`, `idPaciente=${idPaciente}&idRCE=${rce_id}&idDAU=${dau_id}`, "#hoja_hospitalizacion", "modal-lg", "light",'', botones);
    })
    $('#btnEntregaTurno').click(function(){
        if ( perfilUsuario === 'administrativo') {
            return;
        }
        ajaxRequest(raiz+'/controllers/server/rce/cambioTurno/main_controller.php', 'accion=sesionEntregaTurno', 'POST', 'JSON', 1);
        let rutMedicoTratanteEntrega    = $("#rutMedicoTratanteEntrega").val();
        let codigoMedicoTratanteEntrega = $("#codigoMedicoTratanteEntrega").val();
        let botones =   [
                            { id: 'btnEntregarTurno', value: 'Entregar Turno', class: 'btn btn-success' }
                        ];

        modalFormulario("<label class='mifuente ml-2'>Entrega de Turno</label>", `${raiz}/views/modules/rce/rce/cambioTurno.php`, `dau_id=${dau_id}&rutMedicoTratanteEntrega=${rutMedicoTratanteEntrega}&banderaTipoTurno=entregarTurno&codigoMedicoTratanteEntrega=${codigoMedicoTratanteEntrega}`, "#modalEntregaTurno", "modal-lg", "light",'', botones);
    });
    $(".verRecetaGES").on("click", function(){
        const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/recetaGES/main_controller.php`,'idDau='+dau_id+'&accion=obtenerDetalleRecetaGES','POST','JSON',1,'');

        console.log(respuestaAjaxRequest);
        if (respuestaAjaxRequest !== undefined && respuestaAjaxRequest !== null){
            recetaGES = respuestaAjaxRequest;
        }else{
            recetaGES = [];
        }
        console.log(recetaGES);
        // const recetaGES = obtenerDetalleRecetaGES(dau_id);
        const idRecetaGES = recetaGES[0]?.idRecetaGES ?? 0;
        if (pacienteEgresado()) {
            modalImprimirRecetaGES(dau_id, idRecetaGES);
            return;
        }

        const botones = [{
            id: 'btnIngresarRecetaGES',
            value: '<i class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></i> Guardar',
            class: 'btn btn-primary'
        }];

        const existeRecetaGES = idRecetaGES !== undefined && idRecetaGES !== null && idRecetaGES !== 0;
        if (existeRecetaGES) {
            botones.push({
                id: 'btnDesplegarPDFRecetaGES',
                value: '<i class="glyphicon glyphicon-file" aria-hidden="true"></i> PDF',
                function: () => modalImprimirRecetaGES(dau_id, idRecetaGES),
                class: 'btn btn-primary'
            });
        }
        modalFormulario("<label class='mifuente ml-2'>Receta GES</label>", `${raiz}/views/modules/rce/rce/recetaGES.php`, `idPaciente=${idPaciente}&idRCE=${rce_id}&idDau=${dau_id}`, "#recetaGES", "modal-lg", "light",'', botones);


        // modalFormulario(
        //     "Receta GES",
        //     `${raiz}/views/modules/mapa_piso/detalle_dau/recetaGES/recetaGES.php`,
        //     `idPaciente=${idPaciente}&idRCE=${rce_id}&idDau=${idDau}`,
        //     "#recetaGES",
        //     "66%",
        //     "100%",
        //     botones
        // );

        // modalIngresarRecetaGES(dau_id, idRecetaGES);
    });
    function consultar_cie10_TABLA(id_cie10) {
        let z = 0;
        let arrFun = new Array;

        $("#contenido_diagnostico tr").each(function (element) {
            let id_cie10 = $(this).find("td.td_id_cie10_TABLA").text();
            arrFun[z] = id_cie10;
            z++;
        });

        let encontrado = arrFun.find(function (element) {
            if (element == id_cie10) {
                return true;
            } else {
                return false;
            }
        });
        return encontrado;
    }
    function modalImprimirRecetaGES(idDau, idRecetaGES) {
        if (idRecetaGES === undefined || idRecetaGES === null || idRecetaGES === 0) {
            console.error("idRecetaGES no existe");
        }

        $('#recetaGES').modal( 'hide' ).data( 'bs.modal', null );

        const botones =   [{
          id: 'btnImprimir',
          value: '<i class="glyphicon glyphicon-print" aria-hidden="true"></i> Imprimir',
          function: () => imprimir(),
          class: 'btn btn-primary btnPrint'
        }];

        modalFormulario("<label class='mifuente ml-2'>Receta GES Urgencia, DAU N°"+idDau+"</label>", `${raiz}/views/modules/rce/rce/pdfRecetaGES.php`, {idRecetaGES}, "#modalPDFRecetaGES", "modal-lg", "light",'', botones);

        // modalFormulario(
        //   `Receta GES Urgencia, DAU N° ${idDau}`,
        //   raiz+"/views/modules/mapa_piso/detalle_dau/recetaGES/pdfRecetaGES.php",
        //   {idRecetaGES},
        //   "#modalPDFRecetaGES",
        //   "66%",
        //   "100%",
        //   botones
        // );

        function imprimir() {
            $('#pdfRecetaGES').get(0).contentWindow.focus();
            $("#pdfRecetaGES").get(0).contentWindow.print();
        }
    }
});