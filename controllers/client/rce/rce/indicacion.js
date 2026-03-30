$(document).ready(function() {
	//Variables
	var tipoMapa 				= $('#tipoMapa').val();
	let chk 					= 0;
	let inicioAtencionRealizado = $("#inicioAtencion").val();
	let dau_id 		   			= $('#frm_dau_id').val();
	let id_rce 		   			= $('#frm_rce_id').val();
	const idPaciente 			= $("#frm_paciente_id").val();
	const tipoAtencion 			= $("#tipoAtencion").val();
	let indicacion_id  			= '';
	let mensajeConfirmacion 	= '';
	let funcionALlamar 			= function () {};
	banderapiso             	= 'RCE',
	atencionAdulto 			 	= 1,
	atencionPediatrico 			= 2,
	atencionGinecologico 		= 3;
	
    ajaxContent('/RCEDAU/views/modules/enfermera/despliegueIndicacionesEnfermeriaMuestraMedica.php','dau_id='+dau_id+'&tipoMapa='+tipoMapa+'&regId='+rce_id+'&banderaDetalleDau=1','#div_indicacion_enfermeria');  
	 $("#frm_estados").change(function(){
        var frm_estados = $(this).val();
		ajaxContent(`${raiz}/views/modules/rce/medico/indicacion.php`,'rce_id='+id_rce+'&idPaciente='+idPaciente+`&dau_id=${dau_id}&frm_estados=${frm_estados}`, '#div_indicacion');

        // ajaxContent(`${raiz}/views/modules/Enfermera/detalleIndicaciones.php`, `dau_id=${dau_id}&regId=${id_rce}&frm_aplicados=${frm_aplicados}`, `#contenidoImagenologia_${dau_id}`, '', true);
    });
	$('.btnVerPdfTransfusion').on('click', function() {
	    var pdfUrl = $(this).data('pdf-url');
	    if (!pdfUrl) return;

	    var urlModal = raiz + '/views/modules/rce/medico/modal_ver_pdf_solicitud.php';
	    var params = 'pdf_url=' + encodeURIComponent(pdfUrl);

	    modalFormulario(
	        "<label class='mifuente ml-2'>PDF Solicitud Transfusión</label>",
	        urlModal,
	        params,
	        "#modalVerPdfSolicitud",
	        "modal-lgg",
	        "light",
	        '',
	        ''
	    );
	});
	$('.btnVerSolicitudEspecialistaOtros').on("click", function(){
		let idSolicitudEspecialista 	= parseInt($(this).attr('id').replace('btnVerSolicitudEspecialista',''));
		let estadoSolicitudEspecialista = $(`#estadoSolicitudEspecialista${idSolicitudEspecialista}`).val();
		let botones						= [];
        modalFormulario("<label class='mifuente ml-2'>Elección de Especialista</label>", `${raiz}/views/modules/rce/especialista/OtroEspecialista.php`, `idSolicitudEspecialista=${idSolicitudEspecialista}&dau_id=${dau_id}&rce_id=${id_rce}&paciente_id=${idPaciente}&banderaRCE=1&tipoMapa=${tipoMapa}&tipoAtencion=${tipoAtencion}&tipoFormulario=aprobacionEspecialista&bandera=${$("#bandera").val()}`, "#modalVerEspecialista", "modal-lg", "light",'', botones);
	});
	$('.btnVerSolicitudEspecialista').on("click", function(){
		let idSolicitudEspecialista 	= parseInt($(this).attr('id').replace('btnVerSolicitudEspecialista',''));
		let estadoSolicitudEspecialista = $(`#estadoSolicitudEspecialista${idSolicitudEspecialista}`).val();
		let botones						= [];
		if ( estadoSolicitudEspecialista != 4 && estadoSolicitudEspecialista != 6 && perfilUsuario !== 'administrativo' ) {
			if ( Number(tipoAtencion) === Number(atencionGinecologico) ) {
				botones.push({ id: 'btnTrasladoAdulto', value: 'Traslado Adulto', class: 'btn btn-primary' });
				botones.push({ id: 'btnTrasladoPediatrico', value: 'Traslado Pediátrico', class: 'btn btn-primary' });
			}
			if ( Number(tipoAtencion) === Number(atencionAdulto) || Number(tipoAtencion) === Number(atencionPediatrico)) {
				botones.push({ id: 'btnTrasladoGinecologico', value: 'Traslado Ginecológico', class: 'btn btn-primary' });
			}
			adjuntarPronosticoFormulario();
		}

        modalFormulario("<label class='mifuente ml-2'>Elección de Especialista</label>", `${raiz}/views/modules/rce/especialista/especialista.php`, `idSolicitudEspecialista=${idSolicitudEspecialista}&dau_id=${dau_id}&rce_id=${id_rce}&paciente_id=${idPaciente}&banderaRCE=1&tipoMapa=${tipoMapa}&tipoAtencion=${tipoAtencion}&tipoFormulario=aprobacionEspecialista&bandera=${$("#bandera").val()}`, "#modalVerEspecialista", "modal-lg", "light",'', botones);
		if ( estadoSolicitudEspecialista != 4 && estadoSolicitudEspecialista != 6 && perfilUsuario !== 'administrativo' ) {
			adjuntarPronosticoFormulario();

		}

	});
	$('.btnVerSolicitudInicioAtención').on("click", function(){
		if ( perfilUsuario === 'administrativo' ) {
			return;
		}
		let botones = 	[
							{ id: 'btnGuardarModificacionSolicitudInicioAtencion', value: 'Guardar', class: 'btn btn-primary' }
						];
		modalFormulario("<label class='mifuente ml-2'>Solicitud Inicio Atención</label>",`${raiz}/views/modules/rce/rce/inicioAtencion.php`, `dau_id=${dau_id}&rce=2`, "#modalInicioAtencion", "modal-lgg", "light",'', botones);
	});
	function adjuntarPronosticoFormulario ( ) {
		const pronosticos = ajaxRequest(raiz+'/controllers/server/rce/especialista/main_controller.php',"&accion=obtenerPronosticosAltaUrgencia", 'POST', 'JSON', 1,'');
		let html = `<div class="col-md-4">
					<select class="form-control form-control-sm mifuente" id="frm_pronostico" name="frm_pronostico">
					<option value="" disabled selected>Pronóstico</option>
					`;
		for ( let pronostico of pronosticos ) {
			html += `<option value="${pronostico.PRONcodigo}">${pronostico.PRONdescripcion}</option>`;
		}
		html += `</select>
				</div>
				`;
		$("#modalVerEspecialista .modal-footer").prepend(html );
	}
	$('.btnVerSolicitudEvolucion').on("click", function(){

		let idSolicitudEvolucion = parseInt($(this).attr('id').replace('btnVerSolicitudEvolucion',''));

		modalFormulario("<label class='mifuente ml-2'>Solicitud de Evolución</label>",`${raiz}/views/modules/rce/rce/evolucion.php`, `idSolicitudEvolucion=${idSolicitudEvolucion}&dau_id=${dau_id}&tipoFormulario=verSolicitudEvolucion`, "#modalVerSolicitudEvolucion", "modal-lg", "light",'', '');
	});
	$(".verModalDetalleIndicacionLog").on('click',function(){
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
	$(".verModalDetalleIndicacion").on('click',function(){
		let indicacion_id = $(this).attr('id');
		const [idIndicacion, servicio] = indicacion_id.split('-');
		let	nom_servicio = "";
		switch ( servicio ) {
			case '1':
				nom_servicio = "Imagenologia";
			break;
			case '2':
				nom_servicio="Tratamiento";
			break;
			case '3':
				nom_servicio="Laboratorio";
			break;
			case '4':
				nom_servicio="Otros";
			break;
		}
		if ( servicio != 1 ) {
			modalFormulario("<label class='mifuente ml-2'>Detalle Solicitud Indicaciones "+nom_servicio+"</label>",`${raiz}/views/modules/rce/indicaciones/modal_detalle_indicacion.php`, $("#frm_modal_detalle_aplica").serialize()+`&sol_id=${indicacion_id}`, "#modalDetalleIndicacion", "modal-lg", "light",'', '');
			return;
		}
		let imprimir = function(){
			$('#iframeSolicitudImagenologia').get(0).contentWindow.focus();
			$("#iframeSolicitudImagenologia").get(0).contentWindow.print();
		}
		let botones =   [
							{ id: 'btnImprimir', value: '<i class="glyphicon glyphicon-print" aria-hidden="true"></i> Imprimir', function: imprimir, class: 'btn btn-primary btnPrint' }
						];
		modalFormulario("<label class='mifuente ml-2'>Solicitud Imagenología</label>",`${raiz}/views/modules/rce/rce/hojaImagenologia.php`, `idIndicacion=${idIndicacion}`, "#PDFRegistroExamen", "modal-lg", "light",'', botones);
	});
	$(".verPDFSolicitudImagenologiaDalca").on("click", function(){
		const idSolicitudDalca = $(this).attr("id");
		if (idSolicitudDalca === undefined || idSolicitudDalca === null) {
			return;
		}
		// let imprimir = function(){
 	// 		$('#iframeSolicitudImagenologiaDalca').get(0).contentWindow.focus();
		// 	$("#iframeSolicitudImagenologiaDalca").get(0).contentWindow.print();
		// }
		// let botones =   [{
		// 	id: 'btnImprimir',
		// 	value: '<i class="glyphicon glyphicon-print" aria-hidden="true"></i> Imprimir',
		// 	function: imprimir,
		// 	class: 'btn btn-primary btnPrint'
		// }];
		modalFormulario("<label class='mifuente ml-2'>Solicitud Imagenología</label>",`${raiz}/views/modules/rce/rce/hojaImagenologiaDalca.php`, `idSolicitudDalca=${idSolicitudDalca}`, "#PDFSolicitudImagenologiaDalca", "modal-lg", "light",'', '');
	});
	$(".verInformeSolicitudImagenologiaDalca").on("click", function(){
		const idSolicitudDalca = $(this).attr("id");
		ajaxRequest(`${raiz}/controllers/server/rce/indicaciones/main_controller.php`, `idSolicitudDalca=${idSolicitudDalca}&accion=obtenerInformeSolicitudDalca`, 'POST', 'JSON', 1,'Obteniendo Informe Integración DALCA...', funcionCallback);
		function funcionCallback(respuestaAjaxRequest) {
			if (respuestaAjaxRequest === undefined || respuestaAjaxRequest === null || respuestaAjaxRequest === "") {
				texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error al obtener Informe </h4>  <hr>  <p class="mb-0">El informe de la solicitud de imagenología de la integración DALCA aún no se encuentra realizado, favor vuelva a intentar más rato.</p></div>';
            	modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
				return;
			}
			// let imprimir = function(){
			// 	$('#iframeSolicitudImagenologiaDalca').get(0).contentWindow.focus();
			// 	$("#iframeSolicitudImagenologiaDalca").get(0).contentWindow.print();
			// }
			// let botones =   [{
			// 	id: 'btnImprimir',
			// 	value: '<i class="glyphicon glyphicon-print" aria-hidden="true"></i> Imprimir',
			// 	function: imprimir,
			// 	class: 'btn btn-primary btnPrint'
			// }];

		modalFormulario("<label class='mifuente ml-2'>Informe Solicitud Imagenología</label>",`${raiz}/views/modules/rce/rce/informeImagenologiaDalca.php`, `informeDalca=${encodeURIComponent(respuestaAjaxRequest)}`, "#InformeSolicitudImagenologiaDalca", "modal-lg", "light",'', '');
		}
	});
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
	$(".verURLResultado").on('click',function(){
		const urlResultado = $(this).attr("id");
		showFile(urlResultado, 800, 800);
	});
	$(".eliminarSolicitudEvolucion").on('click', function() {
		if ( perfilUsuario === 'administrativo') {
			return;
		}
		indicacion_id  		= $(this).attr('id');

		modalConfirmacionNuevo("Advertencia", "ATENCIÓN, se procedera a eliminar la solicitud de evolución, <b>¿Desea continuar?</b>", "primary", eliminarIndicacionEvolucion);
	});
	function eliminarIndicacionEvolucion ( ) {
		const respuestaAjaxRequest = ajaxRequest(raiz+'/controllers/server/rce/indicaciones/main_controller.php','indicacion_id='+indicacion_id+'&accion=eliminarSolicitudEvolucion', 'POST', 'JSON', 1,'');
		switch(respuestaAjaxRequest.status) {
			case "success":
                ajaxContentFast(`${raiz}/views/modules/rce/medico/rce.php`,'tipoMapa='+$('#tipoMapa').val()+`&dau_id=${dau_id}`, '#contenido');
			break;
			case "errorUsuario":
				texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en eliminar indicación de evolución </h4>  <hr>  <p class="mb-0">Error en eliminar indicación:<br><br>'+respuestaAjaxRequest.message+'.</p></div>';
            	modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
            	$('#modalObservacion').modal( 'hide' ).data( 'bs.modal', null );
				$('#modalNombrePlantilla').modal( 'hide' ).data( 'bs.modal', null );
			break;
			default:
				ErrorSistemaDefecto();
			break;

		}
	}
	$(".anularIndicacion").on('click', function() {
		if ( perfilUsuario === 'administrativo' || perfilUsuario === '' || perfilUsuario === undefined ) {
			return;
		}
		indicacion_id  		= $(this).attr('id');
		var confirmarAccion = function(){
			modalConfirmacionNuevo("Advertencia", "ATENCIÓN, se procedera a anular la solicitud de Indicación, <b>¿Desea continuar?</b>", "primary", anularIndicacion);
		}
		let botones    		= [
						    	{ id: 'agregarObservacion', value: 'Anular Indiación', function: confirmarAccion, class: 'btn btn-primary' }
						 	  ];
		modalFormulario("<label class='mifuente ml-2'>Observación Anular Indicación</label>",`${raiz}/views/modules/rce/indicaciones/modal_observacion.php`, $("#frm_modal_observacion").serialize()+'&ind_id=', "#modalObservacion", "modal-lg", "light",'', botones);
	});
	function anularIndicacion () {
		const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/indicaciones/main_controller.php`, $("#frm_modal_observacion").serialize()+`&indicacion_id=${indicacion_id}&rce_id=${id_rce}&dau_id=${dau_id}&accion=anularIndicacion`, 'POST', 'JSON', 1,'');
		switch(respuestaAjaxRequest.status) {
			case "success":
                ajaxContent(`${raiz}/views/modules/rce/medico/indicacion.php`,'rce_id='+id_rce+'&idPaciente='+idPaciente+`&dau_id=${dau_id}`, '#div_indicacion');
            	$('#modalObservacion').modal( 'hide' ).data( 'bs.modal', null );
			break;
			case "error":
				texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en anular indicacion </h4>  <hr>  <p class="mb-0">Error en anular indicación:<br><br>'+respuestaAjaxRequest.message+'.</p></div>';
            	modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
            	$('#modalObservacion').modal( 'hide' ).data( 'bs.modal', null );
			break;
			default:
				ErrorSistemaDefecto();
			break;
		}
	}
	$(".btnVerSolicitudAltaUrgencia").on('click',function(){

		let SAUid = $(this).attr('id');
		modalFormulario("<label class='mifuente ml-2'>Detalle Solicitud Alta Urgencia</label>",`${raiz}/views/modules/rce/indicaciones/modal_detalle_alta_urgencia.php`, `SAUid=${SAUid}`, "#modalObservacion", "modal-lg", "light",'', '');
	});

	$(".ampliarAll").on('click', function () {
	    let trId = $(this).attr('id'); 
	    let rows = $(".tr" + trId); 
	    if ($(this).hasClass('ampliarAll')) {
	        rows.show();
	        $(this).removeClass('ampliarAll').addClass('encogerAll');
	        $(this).attr('title', 'Encoger').html('<i class="fa fa-minus mifuente13"></i>');
	    } else {
	        rows.hide();
	        $(this).removeClass('encogerAll').addClass('ampliarAll');
	        $(this).attr('title', 'Ampliar').html('<i class="fa fa-plus mifuente13"></i>');
	    }
	});

	$(".eliminarIndicacion").on('click', function() {
		if ( perfilUsuario === 'administrativo') {
			return;
		}
		indicacion_id  		= $(this).attr('id');
		var confirmarAccion = function(){
			modalConfirmacionNuevo("Advertencia", "ATENCIÓN, se procedera a eliminar la solicitud de Indicación, <b>¿Desea continuar?</b>", "primary", eliminarIndicacion);
		}
		let botones    		= [
						    	{ id: 'agregarObservacion', value: 'Anular Indiación', function: confirmarAccion, class: 'btn btn-primary' }
						 	  ];
		
		modalFormulario("<label class='mifuente ml-2'>Observación Eliminar Indicación</label>",`${raiz}/views/modules/rce/indicaciones/modal_observacion.php`, $("#frm_modal_observacion").serialize()+'&ind_id=', "#modalObservacion", "modal-lg", "light",'', botones);
	});
	function eliminarIndicacion () {
		const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/indicaciones/main_controller.php`, $("#frm_modal_observacion").serialize()+`&indicacion_id=${indicacion_id}&rce_id=${id_rce}&dau_id=${dau_id}&accion=borrarIndicacion`, 'POST', 'JSON', 1,'');
		switch(respuestaAjaxRequest.status) {
			case "success":
                ajaxContent(`${raiz}/views/modules/rce/medico/indicacion.php`,'rce_id='+id_rce+'&idPaciente='+idPaciente+`&dau_id=${dau_id}`, '#div_indicacion');
                // ajaxContentFast(`${raiz}/views/modules/rce/medico/rce.php`,'tipoMapa='+$('#tipoMapa').val()+`&dau_id=${dau_id}`, '#contenido');
            	$('#modalObservacion').modal( 'hide' ).data( 'bs.modal', null );
			break;
			case "error":
				texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en eliminar indicacion </h4>  <hr>  <p class="mb-0">Error en anular indicación:<br><br>'+respuestaAjaxRequest.message+'.</p></div>';
            	modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
            	$('#modalObservacion').modal( 'hide' ).data( 'bs.modal', null );
			break;
			default:
				ErrorSistemaDefecto();
			break;
		}
	}
	$(".anularIndicacionAll").on('click', function() {
		if ( perfilUsuario === 'administrativo' || perfilUsuario === '' || perfilUsuario === undefined ) {
			return;
		}
		indicacion_id  		= $(this).attr('id');
		var confirmarAccion = function(){
			modalConfirmacionNuevo("Advertencia", "ATENCIÓN, se procedera a anular la solicitud de Indicación, <b>¿Desea continuar?</b>", "primary", anularIndicacionAll);
		}
		let botones    		= [
						    	{ id: 'agregarObservacion', value: 'Anular Indiación', function: confirmarAccion, class: 'btn btn-primary' }
						 	  ];
		modalFormulario("<label class='mifuente ml-2'>Observación Anular Indicación</label>",`${raiz}/views/modules/rce/indicaciones/modal_observacion.php`, $("#frm_modal_observacion").serialize()+'&ind_id=', "#modalObservacion", "modal-lg", "light",'', botones);
	});
	function anularIndicacionAll () {
		const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/indicaciones/main_controller.php`, $("#frm_modal_observacion").serialize()+`&indicacion_id=${indicacion_id}&rce_id=${id_rce}&dau_id=${dau_id}&accion=anularIndicacionAll`, 'POST', 'JSON', 1,'');
		switch(respuestaAjaxRequest.status) {
			case "success":
                ajaxContent(`${raiz}/views/modules/rce/medico/indicacion.php`,'rce_id='+id_rce+'&idPaciente='+idPaciente+`&dau_id=${dau_id}`, '#div_indicacion');
            	$('#modalObservacion').modal( 'hide' ).data( 'bs.modal', null );
			break;
			case "error":
				texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en anular indicacion </h4>  <hr>  <p class="mb-0">Error en anular indicación:<br><br>'+respuestaAjaxRequest.message+'.</p></div>';
            	modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
            	$('#modalObservacion').modal( 'hide' ).data( 'bs.modal', null );
			break;
			default:
				ErrorSistemaDefecto();
			break;
		}
	}

	$(".eliminarIndicacionAll").on('click', function() {
		if ( perfilUsuario === 'administrativo') {
			return;
		}
		indicacion_id  		= $(this).attr('id');
		var confirmarAccion = function(){
			modalConfirmacionNuevo("Advertencia", "ATENCIÓN, se procedera a eliminar la solicitud de Indicación, <b>¿Desea continuar?</b>", "primary", eliminarIndicacionAll);
		}
		let botones    		= [
						    	{ id: 'agregarObservacion', value: 'Anular Indiación', function: confirmarAccion, class: 'btn btn-primary' }
						 	  ];
		
		modalFormulario("<label class='mifuente ml-2'>Observación Eliminar Indicación</label>",`${raiz}/views/modules/rce/indicaciones/modal_observacion.php`, $("#frm_modal_observacion").serialize()+'&ind_id=', "#modalObservacion", "modal-lg", "light",'', botones);
	});
	function eliminarIndicacionAll () {
		const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/indicaciones/main_controller.php`, $("#frm_modal_observacion").serialize()+`&indicacion_id=${indicacion_id}&rce_id=${id_rce}&dau_id=${dau_id}&accion=borrarIndicacionAll`, 'POST', 'JSON', 1,'');
		switch(respuestaAjaxRequest.status) {
			case "success":
                ajaxContent(`${raiz}/views/modules/rce/medico/indicacion.php`,'rce_id='+id_rce+'&idPaciente='+idPaciente+`&dau_id=${dau_id}`, '#div_indicacion');
                // ajaxContentFast(`${raiz}/views/modules/rce/medico/rce.php`,'tipoMapa='+$('#tipoMapa').val()+`&dau_id=${dau_id}`, '#contenido');
            	$('#modalObservacion').modal( 'hide' ).data( 'bs.modal', null );
			break;
			case "error":
				texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en eliminar indicacion </h4>  <hr>  <p class="mb-0">Error en anular indicación:<br><br>'+respuestaAjaxRequest.message+'.</p></div>';
            	modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
            	$('#modalObservacion').modal( 'hide' ).data( 'bs.modal', null );
			break;
			default:
				ErrorSistemaDefecto();
			break;
		}
	}
});