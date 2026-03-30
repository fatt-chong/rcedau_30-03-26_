$(document).ready(function(){
	$(".infoTooltip" ).tooltip();

	localStorage.setItem('urlAtras', '/views/modules/consulta/consulta.php');
	localStorage.setItem('parametrosAtras', '');

	let $frm_numero_dau        	  = $("#frm_numero_dau"),
		$frm_fecha_admision_desde = $("#frm_fecha_admision_desde"),
		$frm_fecha_admision_hasta = $("#frm_fecha_admision_hasta"),
		$frm_nroDocumento         = $("#frm_nroDocumento"),
		$frm_nombreCompleto       = $("#frm_nombreCompleto"),
		$frm_tipo_atencion        = $("#frm_tipo_atencion"),
		$frm_cuentaCorriente      = $("#frm_cuentaCorriente"),
		idDau					  = '';
	banderapiso 			      = 'CONSULTA';

	validar("#frm_numero_dau"     		,"numero");
	validar("#frm_nombreCompleto"     	,"letras");
	validar("#nombreSocial"     	,"letras");
	validar("#frm_cuentaCorriente"     	,"numero");
	validar("#frm_fecha_admision_desde" ,"fecha");
	validar("#frm_fecha_admision_hasta" ,"fecha");

	enlaceBoton();
 	tabla3("#table_consulta_dau");

	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
		$('[data-toggle="tooltip"]').on('shown.bs.tooltip', function () {
        	$('.tooltip').addClass('animated tada');
        });
	});

	$.fn.datepicker.dates['es'] = {
		days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
		daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
		daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
		months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
		monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
		today: "Hoy",
		monthsTitle: "Meses",
		clear: "Borrar",
		weekStart: 1,
		format: "dd-mm-yyyy"
	};

	$frm_fecha_admision_desde.datepicker({
		todayBtn: "linked",
		todayHighlight: true,
		autoclose: true,
		container: $("#date_fecha_desde"),
		format: 'dd/mm/yyyy',
		clearBtn: true,
		language: 'es',
	}).on('changeDate', function(e){
		$frm_fecha_admision_hasta.datepicker({
			autoclose: true
		}).datepicker('setStartDate', e.date);
		$frm_fecha_admision_hasta.focus();
	});

	$frm_fecha_admision_hasta.datepicker({
		todayBtn: "linked",
		todayHighlight: true,
		autoclose: true,
		format: 'dd/mm/yyyy',
		clearBtn: true,
		container: $("#date_fecha_hasta"),
		language: 'es'
	}).on('changeDate', function(e){
		$frm_fecha_admision_desde.datepicker({
			autoclose: true
		}).datepicker('setEndDate', e.date);
	});

	$frm_fecha_admision_hasta.datepicker('setStartDate', $frm_fecha_admision_desde.val());

	$('#documento').change(function(){
		if ( $('#documento option:selected').val() ==1 ) {
			$frm_nroDocumento.val("");
			$frm_nroDocumento.off();
			validar("#frm_nroDocumento", "rut");
			$frm_nroDocumento.Rut({
				on_error: function(){
					return false;
				},
				format_on: 'keyup'
			});
		} else {
			$frm_nroDocumento.val("");
			$frm_nroDocumento.off();
			validar("#frm_nroDocumento","numero");
		}
	});
	$frm_nroDocumento.Rut({
		on_error: function(){
			return false;
		},
		format_on: 'keyup'
	});
	$("#btnEliminarFiltrosPa").click(function(){
		$frm_nroDocumento.val("");
		$frm_numero_dau.val("");
		unsetSesion();
		ajaxContent(`${raiz}/views/modules/consulta/consulta.php`, '', '#contenido', 'Cargando...', true);
	});
	$('#checkHistorico').on( 'click', function() {
		if( $(this).is(':checked') ){
			$('#checkSinCategorizacionCerrados').prop('checked', false);
		}
	});
	$('#checkSinCategorizacionCerrados').on( 'click', function() {
		if( $(this).is(':checked') ){
			$('#checkHistorico').prop('checked', false);
		}
	});

	$('#btnBuscarPaciente').on('click', function(){
		if ( ! verificarDatosBusquedaPaciente() ) {
			return;
		}
		if ( $('#documento option:selected').val() == 2  ) {
			ajaxContent(`${raiz}/views/modules/consulta/consulta.php`, $("#frm_consulta").serialize()+`&frm_extranjero=${$('#frm_nroDocumento').val()}`, '#contenido', 'Cargando...', true);
			return;
		}
		let rut = $.Rut.quitarFormato($frm_nroDocumento.val());
		rut     = rut.substring(0, rut.length-1);
		ajaxContent(`${raiz}/views/modules/consulta/consulta.php`, $("#frm_consulta").serialize()+`&frm_rut=${rut}`, '#contenido', 'Cargando...', true);
	});
	$("#table_consulta_dau").on("click", ".aplicarNEA", function() {
		idDau = $(this).attr('id');
		modalFormulario_noCabecera('', raiz+"/views/modules/rce/nea/modalAplicarNEA.php", 'banderaRCE=1&dau_id='+idDau, "#modalNEA", "modal-md", "", "fas fa-plus",'');
	});
	$("#table_consulta_dau").on("click", ".cierreDAU", function() {
	// $(".cierreDAU").click(function(){
		$('.tooltip').tooltip('hide');
		idDau = $(this).attr('id');
		let botones = 	[
							{ id: 'btnCerrarDAU', value: '<i class="glyphicon glyphicon-pencil" aria-hidden="true"></i> Procesar Cierre DAU', class: 'btn btn-primary' }
						];

		modalFormulario("<label class='mifuente ml-2'>Cierre DAU</label>", `${raiz}/views/modules/consulta/cierre_dau.php`, $("#frm_consulta").serialize()+`&Iddau=${idDau}`, '#cierreDAU', "modal-lg", "light",'', botones);
	});
	$("#table_consulta_dau").on("click", ".verCierreDAU", function() {
	// $(".verCierreDAU").click(function(){
		idDau = $(this).attr('id');
		let botones = 	[
		  					{ id: 'btnVerCierreDAU', value: 'Guardar', class: 'btn btn-primary' }
				  		];
		modalFormulario("<label class='mifuente ml-2'>Detalle cierre DAU</label>", `${raiz}/views/modules/consulta/ver_cierre_dau.php`, $("#frm_consulta").serialize()+`&Iddau=${idDau}`, "#verCierreDAU", "modal-lg", "light",'', botones);
	});
	$("#table_consulta_dau").on("click", ".verDetalle", function() {
	// $(".verDetalle").click(function(){
		$('.tooltip').tooltip('hide')
		idDau = $(this).attr('id');
		ajaxContent('views/modules/mapa_piso_full/detalle_dau/detalle_dau.php', `dau_id=${idDau}`, '#contenido', 'Cargando...', true);
	});
	$("#table_consulta_dau").on("click", ".addCategorizacionDAU", function() {
	// $(".addCategorizacionDAU").click(function(){
		$('.tooltip').tooltip('hide')
		idDau = $(this).attr('id');
		let botones = 	[
							{ id: 'btnAgregarCategorizacion', value: '<i class="glyphicon glyphicon-pencil" aria-hidden="true"></i> Registrar', class: 'btn btn-primary' }
						];
		modalFormulario(`Agregar Categorización DAU: ${idDau}`, `${raiz}/views/modules/consulta/agregar_categorizacion_dau.php`, `dau_id=${idDau}`, '#agreCategDAU', '30%', '30%', botones);
	});
	$("#table_consulta_dau").on("click", ".verRegistroMedico", function() {
	// $(".verRegistroMedico").click(function(){
		$('.tooltip').tooltip('hide');
		idDau = $(this).attr('id');
		let parametros = {'dau_id': idDau, 'accion': 'consultaCIE10DAU'};
		let responseCIE10 = ajaxRequest(`${raiz}/controllers/server/consulta/main_controller.php`, parametros, 'POST', 'JSON', 1);
		let botones = [];
		if ( responseCIE10.cie == 'no' ) {
			botones = 	[
							{ id: 'btnAgregarCIE', value: '<i class="glyphicon glyphicon-pencil" aria-hidden="true"></i> Registrar', class: 'btn btn-primary' }
						];
		}
		modalFormulario("<label class='mifuente ml-2'>Registro Médico</label>", `${raiz}/views/modules/consulta/registro_medico.php`, $("#frm_registro_medico").serialize()+`&Iddau=${idDau}`, "#registroMedico", "modal-lg", "light",'', botones);
	});
	$("#table_consulta_dau").on("click", ".verDauRCE", function() {
	// $(".verDauRCE").click(function(){
		let banderaImpresionRCECompleto = true;
        let dau_id 						= $(this).attr('id');
		let rce_id 						= $(`#rce_id_${dau_id}`).val();
		let estadoDau 					=  $(`#estadoDau_${dau_id}`).val();
        let tituloModal = estadoAtencionDau(estadoDau);
        let alternar = function(){
            if ( banderaImpresionRCECompleto ) {
                ajaxContent(`${raiz}/views/modules/rce/rce/ver_rce.php`, `rce_id=${rce_id}&dau_id=${dau_id}&banderaLlamada=altaUrgenciaCompleto`, '#modalPDFRCEdiv', '', true);
                banderaImpresionRCECompleto = false;
            } else {
                ajaxContent(`${raiz}/views/modules/rce/rce/ver_rce.php`, `rce_id=${rce_id}&dau_id=${dau_id}&banderaLlamada=altaUrgenciaIncompleto`, '#modalPDFRCEdiv', '', true);
                banderaImpresionRCECompleto = true;
            }
        }
        let botones =   [
                            { id: 'btnAlternar', value: '<i class="glyphicon glyphicon-transfer" aria-hidden="true"></i> Alternar', function: alternar, class: 'btn btn-primary' }
                        ];
        modalFormulario("<label class='mifuente ml-2'>"+tituloModal+"</label>", raiz+"/views/modules/rce/rce/ver_rce.php", "rce_id="+rce_id+'&dau_id='+dau_id, "#detalle_rce_pdf", "modal-lg", "light",'', botones);
	});
	$("#table_consulta_dau").on("click", ".verDAU", function() {
	// $(".verDAU").click(function(){
		let arregloDau = $(this).attr('id');
		let  myarr     = arregloDau.split("/");
		let  dau_id    = myarr[0], fechaA = myarr[1], tipo = myarr[2];
        modalFormulario("<label class='mifuente ml-2'>PDFDAU</label>", raiz+"/views/modules/rce/rce/generaPDFDAU.php", `Iddau=${dau_id}&fechaA=${fechaA}&tipo=${tipo}`, "#detalle_rce_pdf", "modal-lg", "light",'', '');
	});
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
		return 'Documento RCE (Aún en Atención)';
	}
	$("#table_consulta_dau").on("click", ".verDetalleDau", function() {
	// $('.verDetalleDau').on('click', function(){
		modalFormulario_noCabecera('', raiz+"/views/modules/rce/rce/ver_detalleDau.php", 'idDau='+$(this).attr('id'), "#ver_detalleDau", "modal-lg", "", "fas fa-plus");
	});
	//Botón ver receta GES
	$("#table_consulta_dau").on("click", ".verRecetaGES", function() {
		idDau = $(this).attr('id');
		if (idDau === undefined || idDau === null || idDau === "") {
			return;
		}
		detalleReceta = obtenerDetalleRecetaGES(idDau);
		if (detalleReceta.length === 0) {
			return;
		}
		imprimirRecetaGES(idDau, detalleReceta[0].idRecetaGES);
	});
	function obtenerDetalleRecetaGES(idDau) {
		const respuestaAjaxRequest = ajaxRequest(
			`${raiz}/controllers/server/rce/recetaGES/main_controller.php`,
			{
				idDau,
				accion: "obtenerDetalleRecetaGES"
			},
			'POST',
			'JSON',
			1,
			''
		);
		return (respuestaAjaxRequest !== undefined && respuestaAjaxRequest !== null)
			? respuestaAjaxRequest
			: [];
	}
	function imprimirRecetaGES(idDau, idRecetaGES) {
		modalFormulario_noCabecera('', raiz+"/views/modules/rce/rce/pdfRecetaGES.php", {idRecetaGES}, "#modalPDFRecetaGES", "modal-lg", "", "fas fa-plus");
	}
	function verificarDatosBusquedaPaciente ( ) {
		$.validity.start();
		if ( $frm_nroDocumento.val() != "" && $('#documento option:selected').val() == 1 && $.Rut.validar($frm_nroDocumento.val()) == false ) {
			$frm_nroDocumento.assert(false,'El Run Ingresado no es válido');
		}
		if ( $('#nombreSocial').val() != "" ) {
			$frm_tipo_atencion.require("Seleccione una opción");
			if ( $('#nombreSocial').val().length < 3 ) {
				$('#nombreSocial').assert(false,"Debe Ingresar Mínimo 3 Caracteres");
			}
		}
		if ( $frm_nombreCompleto.val() != "" ) {
			$frm_tipo_atencion.require("Seleccione una opción");
			if ( $frm_nombreCompleto.val().length < 3 ) {
				$frm_nombreCompleto.assert(false,"Debe Ingresar Mínimo 3 Caracteres");
			}
		}
		if ( $frm_cuentaCorriente.val() == "0" ) {
			$frm_cuentaCorriente.assert(false,"La ctacte Ingresada,no es valida");
		}
		if ( ! ( ($frm_numero_dau.val()!='' && $frm_numero_dau.val()!=0) || $('#frm_nroDocumento').val()!=''  || $('#frm_nombreCompleto').val()!='' || $('#nombreSocial').val()!='' || $frm_cuentaCorriente.val()!=""  || $('#checkSinCategorizacionCerrados').is(':checked')==true ) ) {
			$frm_fecha_admision_desde.require("Ingrese fecha inicio");
			$frm_fecha_admision_hasta.require("Ingrese fecha fin");
			$frm_tipo_atencion.require("Seleccione una opcion");
			$('#frm_motivo').require("Seleccione una opcion");
		}
		$('#frm_nombreCompleto').val(quitarEspacio($('#frm_nombreCompleto').val()));
		$('#nombreSocial').val(quitarEspacio($('#nombreSocial').val()));
		result = $.validity.end();
		if ( result.valid == false ) {
			return false;
		}
		return true;
	}
	function confirmarAnularDau ( ) {
		modalConfirmacion("Advertencia", "ATENCIÓN, se procedera a dejar al Paciente como NULO, <b>¿Desea continuar?</b>", anularDau);
	}
	function anularDau ( ) {
		const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/consulta/main_controller.php`, $('#frm_anula').serialize()+'&accion=pacienteNulo', 'POST', 'JSON', 1,'Actualizando DAU...');
		switch(respuestaAjaxRequest.status){
			case "success":
				$('#PacienteNuloModal').modal( 'hide' ).data( 'bs.modal', null );
				ajaxContent(`${raiz}/views/modules/consulta/consulta.php`, `frm_numero_dau=${idDau}`, '#contenido', '', true);
			break;
			case "error":
				modalMensaje("Error en el proceso", `Error en agregar NULO:<br><br>${respuestaAjaxRequest.message}`, "error_actualizar_NULO", 500, 300);
			break;
			default:
				modalMensaje("Error generico", respuestaAjaxRequest, "indicaciones_agregada_incorrectamente", 400, 300);
			break;
		}
	}
	$(".reemplazarDatosPacienteNN").on("click", function() {
		const [idDau, idPacienteNN, ctaCte] = $(this).attr("id").split("-");
		modalFormulario(
			`Reemplazar Datos Paciente NN, DAU: ${idDau}` ,
			`${raiz}/views/modules/consulta/reemplazarDatosPacienteNN.php`,
			{
				idDau,
				idPacienteNN,
				ctaCte
			},
			'#reemplazarDatosPacienteNN',
			'40%',
			'auto',
			[{
				id: "btnReemplazarDatosPacienteNN",
				value: '<i class="fa fa-refresh" aria-hidden="true"></i> Reemplazar',
				class: "btn btn-primary"
			}]
		);
	});
});