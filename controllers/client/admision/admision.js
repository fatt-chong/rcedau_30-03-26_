function updateCharCount(textarea) {
	const textarea1 = document.getElementById(textarea);
	const charCount = document.getElementById('charCount'+textarea);
	const remaining = 500 - textarea1.value.length;
	charCount.textContent = `${remaining} caracteres restantes`;
}
function obtenerValorCiudad(ciudad){
	$('#frm_ciudad').prop("disabled", false);
	$("#divSeleccionCiudades").show();
	var regId  = $('#frm_region').val();
	if(regId !== null && regId != '' && regId != '99'){
		var response = ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','regId='+regId+'&accion=cargarCiudades', 'POST', 'JSON', 1);
		if(ciudad == "999" || ciudad === null || ciudad == ''){
			$("#divSeleccionComunasIndicePaciente").hide();
			$('#frm_ciudad').append('<option value="" selected> Seleccione Ciudad </option>');
		}
		else{
			$("#divSeleccionComunas").show();
			$('#frm_ciudad').append('<option value=""> Seleccione Ciudad </option>');
		}
		for (var i=0; i<response.length; i++) {
			if(ciudad == response[i].CIU_Id ){
				$('#frm_ciudad').append('<option value="' + response[i].CIU_Id + '" selected = "selected">' + response[i].CIU_Descripcion + '</option>');
			}
			else{
				$('#frm_ciudad').append('<option value="' + response[i].CIU_Id + '">' + response[i].CIU_Descripcion + '</option>');
			}
		}
	}
	else{
		$("#divSeleccionCiudades").hide();
		$("#divSeleccionComunas").hide();
	}
}
function obtenerValorComuna(comuna){
	$('#frm_comuna').prop("disabled", false);
	var ciuId  = $('#frm_ciudad').val();
	var response = ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','ciuId='+ciuId+'&accion=cargarComunas', 'POST', 'JSON', 1);
	if(comuna == 349 || comuna === null){
		$('#frm_comuna').append('<option value="" selected> Seleccione Comuna </option>');
	}
	else{
		$('#frm_comuna').append('<option value=""> Seleccione Comuna </option>');
	}
	for (var i=0; i<response.length; i++) {
		if(comuna === response[i].id ){
			$('#frm_comuna').append('<option value="' + response[i].id + '" selected = "selected">' + response[i].comuna + '</option>');
		}
		else{
			$('#frm_comuna').append('<option value="' + response[i].id+ '">' + response[i].comuna + '</option>');
		}
	}
}
function desmarcarCheckBoxes ( ) {
	$('input[name="frm_manifestaciones"]').prop("checked", false);
	$('input[name="frm_constatacionLesiones"]').prop("checked", false);
	$('input[name="frm_dolorGarganta"]').prop("checked", false);
	$('input[name="frm_tos"]').prop("checked", false);
	$('input[name="frm_dificultadRespiratoria"]').prop("checked", false);
}
function verificarDatosPacienteDerivado ( ) {
	if ( $("#slc_derivado").val() == 'N' ) {
			if ( $("#slc_pacienteCritico").val() == '' || $("#slc_pacienteCritico").val() == 0 || $("#slc_pacienteCritico").val() == undefined || $("#slc_pacienteCritico").val() == null ) {
			$("#slc_pacienteCritico").assert(false, "Debe Seleccionar si Paciente es Crítico");
			return false;
		}
		return true;
	}
	if ( $("#slc_derivado").val() == '' || $("#slc_derivado").val() == 0 || $("#slc_derivado").val() == undefined || $("#slc_derivado").val() == null ) {
		$("#slc_derivado").assert(false, "Debe Seleccionar si Paciente es Derivado o No");
		return false;
	}
	if ( $("#frm_establecimientosRedSalud").val() == '' || $("#frm_establecimientosRedSalud").val() == undefined || $("#frm_establecimientosRedSalud").val() == null) {
		$("#frm_establecimientosRedSalud").assert(false, "Debe Seleccionar Establecimiento Red de Salud");
		return false;
	}
	if ( $("#frm_nombreOtrosEstablecimientos").is(":visible") && ($("#frm_nombreOtrosEstablecimientos").val() == '' || $("#frm_nombreOtrosEstablecimientos").val() == 0 || $("#frm_nombreOtrosEstablecimientos").val() == undefined || $("#frm_nombreOtrosEstablecimientos").val() == null) ) {
		$("#frm_nombreOtrosEstablecimientos").assert(false, "Debe Ingresar Nombre Establecimiento");
		return false;
	}
	if ( $("#slc_pacienteCritico").val() == '' || $("#slc_pacienteCritico").val() == 0 || $("#slc_pacienteCritico").val() == undefined || $("#slc_pacienteCritico").val() == null ) {
		$("#slc_pacienteCritico").assert(false, "Debe Seleccionar si Paciente es Crítico");
		return false;
	}
	return true;
}
function camposDisable(){
	$("#frm_direccion").prop('disabled', false);
	$("#frm_fechaNac").prop('disabled', false);
	$("#frm_rut1").prop('disabled', false);
	$("#frm_prevision").prop('disabled', false);
	$("#frm_Naciemito").prop('disabled', false);
}
function consultarFonasa(){
	

	$('#frm_registro_paciente')[0].reset();
	$('#frm_prevision').prop('selectedIndex',0);
	$('#frm_Nacionalidad').prop('selectedIndex',0);
	$('#frm_pais_nacimiento').prop('selectedIndex',0);
	$('#frm_afrodescendiente').prop('selectedIndex',0);
	$('#divSeleccionCiudades').hide();
	$('#divSeleccionComunas').hide();
	$('#frm_sectorDomicilio').prop('selectedIndex',0);
	$('#frm_sexo').prop('selectedIndex',0);
	$('#frm_formaPago').prop('selectedIndex',0);
	$('#frm_centroAtencion').prop('selectedIndex',0);
	$('#frm_etnia').prop('selectedIndex',0);
	$("#divTipoAccidente").hide();
	$("#DivInstitucion").hide();
	$("#DivCampoMotivoAgresionManifestaciones").hide();
	$("#DivCampoMotivoAgresionConstatacionLesiones").hide();
	$("#DivConstatacionLesionesManifestacion").hide();
	$("#DivCampoMotivoLesiones").hide();
	$("#DivTransitoTipoManifestacion").hide();
	$("#divTipo_choque").hide();
	$("#DivN").hide();
	$("#DivNombre").hide();
	$("#DivMutualidad").hide();
	$("#DivTransitoTipo").hide();
	$("#DivHogar").hide();
	$("#DivLugarPublico").hide();
	$("#DivCampoMotivo").hide();
	$("#DivCampoMotivo2").hide();
	$("#DivCampoMotivoAgresion").hide();
	$("#DivCampoMotivoAgresion2").hide();
	$("#DivCampoMotivoLesiones").hide();
	$("#DivCampoMotivoAlcoholemia").hide();
	$("#frm_prevision").prop('disabled', false);
	document.getElementById("tipoDocumentoDau").innerHTML = "RUN";

	 // var fn_cerrarMod = function miFuncion(){
		 $('#modal_indicePacienteExterno').modal( 'hide' ).data( 'bs.modal', null );
	   // }C:\inetpub\wwwroot\RCEDAU\views\modules\admision\indice_paciente.php
	   modalFormulario_noCabecera('', raiz+"/views/modules/admision/indice_paciente.php", 'sistemaExterno=DAU&fonasa=1&nombre=frm_nombres_dau&run=frm_rut1&ApellidoPaterno=frm_AP_dau&ApellidoMaterno=frm_AM_dau&fechaNac=frm_Naciemito&calcularEdad=labelEdad&sexo=frm_sexo&etnia=frm_etnia&ctp=frm_centroAtencion&nac=frm_Nacionalidad&domicilio=frm_direccion&correo=frm_correo&telefonoCelular=frm_telefonoCelular&telefonoFijo=frm_telefonoFijo&prevision=frm_prevision&formaPago=frm_formaPago&idPaciente=idPacienteDau&direccionOculta=direccionOculta&pacienteFall=pacienteFallDau&tipoDocumentoLabel=tipoDocumentoDau&doc_documento=id_doc_documentoDau&paisNacimiento=frm_pais_nacimiento&PACafro=frm_afrodescendiente&region=frm_region&ciudad=frm_ciudad&comuna=frm_comuna&calle=frm_nombreCalle&numero=frm_numeroDireccion&sector=frm_sectorDomicilio&prais=frm_prais', "#modal_indicePacienteExterno", "modal-lgg", "", "fas fa-plus");
	 // modalFormulario_noCabecera('', host+"/indice_paciente_2017/views/modules/pacientes/moduloPaciente.php", 'sistemaExterno=DAU&fonasa=1&nombre=frm_nombres_dau&run=frm_rut1&ApellidoPaterno=frm_AP_dau&ApellidoMaterno=frm_AM_dau&fechaNac=frm_Naciemito&calcularEdad=labelEdad&sexo=frm_sexo&etnia=frm_etnia&ctp=frm_centroAtencion&nac=frm_Nacionalidad&domicilio=frm_direccion&correo=frm_correo&telefonoCelular=frm_telefonoCelular&telefonoFijo=frm_telefonoFijo&prevision=frm_prevision&formaPago=frm_formaPago&idPaciente=idPacienteDau&direccionOculta=direccionOculta&pacienteFall=pacienteFallDau&tipoDocumentoLabel=tipoDocumentoDau&doc_documento=id_doc_documentoDau&paisNacimiento=frm_pais_nacimiento&PACafro=frm_afrodescendiente&region=frm_region&ciudad=frm_ciudad&comuna=frm_comuna&calle=frm_nombreCalle&numero=frm_numeroDireccion&sector=frm_sectorDomicilio&prais=frm_prais', "#modal_indicePacienteExterno", "modal-lgg", "", "fas fa-plus");
	 $('body').on('shown.bs.modal', '.modal', function () {
		$('[id$=frm_documentoExterno]').focus();
	  });
}
function checkLocalStorage() {
    const idPacienteDau = localStorage.getItem('idPacienteDau');
    const cerrarModal 	= localStorage.getItem('cerrarModal');
    const pacienteNN 	= localStorage.getItem('pacienteNN');
    const CrearPacienteDau 	= localStorage.getItem('CrearPacienteDau');
    if (cerrarModal !== null) {
    	console.log('idPacienteDau encontrado:', idPacienteDau);
    	console.log('cerrarModal encontrado:', cerrarModal);
        localStorage.removeItem('idPacienteDau');
        localStorage.removeItem('cerrarModal');
        localStorage.removeItem('pacienteNN');
        localStorage.removeItem('CrearPacienteDau');
        $.ajax({
            url  : raiz+'/controllers/server/admision/main_controller.php',
            type : 'POST',
            data : 'accion=buscarPaciente&idPacienteDau='+idPacienteDau,
            dataType : 'JSON',
            async: true
        }).done(function(retorno){
        	
        	if(cerrarModal == 'S'){
        		if(CrearPacienteDau == 'S'){
        			texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-user-plus mr-1" style="color: #5a8dc5; font-size: 26px"></i>Paciente Ingresado exitosamente. </h4><p>Se ha generado exitosamente el registro del paciente.</p>  <hr>  <p class="mb-0">Paciente '+retorno[0].tipoDocumentoLabel+' : <strong> '+retorno[0].run+' </strong> - NOMBRE : <strong>'+retorno[0].nombres+' '+retorno[0].apellidopat+' '+retorno[0].apellidomat+'</strong></p></div>';
					modalMensajNoCabecera('Exito',texto,  "#modal", "modal-md", "success");
        		}
	        	$('#modal_indicePacienteExterno').modal( 'hide' ).data( 'bs.modal', null );
	        }
	        if(pacienteNN == 'S'){
	        	$('#pacienteNN').val('S');
	        }
            $('#frm_nombres_dau').val(retorno[0].nombres);  
            $('#frm_rut1').val(retorno[0].run); 
            $('#frm_AP_dau').val(retorno[0].apellidopat);   
            $('#frm_AM_dau').val(retorno[0].apellidomat);   
            $('#frm_Naciemito').val(retorno[0].fechanacimiento);   
            $('#labelEdad').text(retorno[0].calcularEdad);   
            $('#frm_sexo').val(retorno[0].sexo);    
            $('#frm_etnia').val(retorno[0].etnia);
            $('#frm_religion').val(retorno[0].religion);  
            $('#frm_centroAtencion').val(retorno[0].centroatencionprimaria);   
            if(retorno[0].nacionalidad > 0 ){
            	$('#frm_Nacionalidad').val(retorno[0].nacionalidad);
            } 
            $('#frm_direccion').val(retorno[0].restodedireccion);  
            $('#frm_correo').val(retorno[0].email);    
            $('#frm_telefonoCelular').val(retorno[0].fono1); 
            $('#frm_telefonoCelular2').val(retorno[0].fono2); 
            $('#frm_telefonoCelular3').val(retorno[0].fono3);     
            $('#frm_prevision').val(retorno[0].prevision);
            if(retorno[0].conveniopago > 0 ){
            	$('#frm_formaPago').val(retorno[0].conveniopago);
            }   
            $('#idPacienteDau').val(retorno[0].id); 
            $('#direccionOculta').val(retorno[0].direccionOculta);  
            $('#pacienteFallDau').val(retorno[0].fallecido); 
            $('#tipoDocumentoDau').text(retorno[0].tipoDocumentoLabel);  
            $('#id_doc_documentoDau').val(retorno[0].id_doc_extranjero);    
            $('#frm_pais_nacimiento').val(retorno[0].paisNacimiento);   
            $('#frm_afrodescendiente').val(retorno[0].PACafro); 
            if(retorno[0].region != 99 ){
            	$('#frm_region').val(retorno[0].region).change(); 
            } else{
            	$('#frm_region').val(retorno[0].region);
            }   
            $('#frm_ciudad').val(retorno[0].ciudad).change(); 
            if(retorno[0].idcomuna > 0 ){
            	$('#frm_comuna').val(retorno[0].idcomuna);
            }        
            $('#frm_nombreCalle').val(retorno[0].calle);    
            $('#frm_numeroDireccion').val(retorno[0].numero);   
            $('#frm_sectorDomicilio').val(retorno[0].sector_domicilio);   
            $('#frm_prais').val(retorno[0].prais);  
        })
    } 

}
// function loadScript(url, callback) {
//     const script = document.createElement('script');
//     script.type = 'text/javascript';
//     script.src = url;

//     // Llama al callback cuando el script se ha cargado y ejecutado
//     script.onload = callback;

//     // Manejo de errores de carga
//     script.onerror = function() {
//         console.error(`Error al cargar el script: ${url}`);
//     };

//     // Agrega el script al documento
//     document.head.appendChild(script);
// }
$(document).ready(function(){
	$("#div_nombre_legal").hide();
	$("#div_identidadGenero").hide();
	$("#div_frm_nombreSocial").hide();
	$("#frm_transexual").on("change", function(){
		if($(this).val() == 0){
			$("#div_nombre_legal").hide();
			$("#div_identidadGenero").hide();
			$("#div_nombreSocial").hide();
			$("#div_frm_nombreSocial").hide();
		}else if($(this).val() == 1){
			$("#div_nombre_legal").show();
			$("#div_identidadGenero").show();
			$("#div_frm_nombreSocial").show();
		}
	});
	$(document).keydown(function(e) {
		if (e.keyCode == 9){
			if ($('#frm_otrosTelefonos').is(":focus")) {
				if ($('#frm_prevision').val() == '0' || $('#frm_prevision').val() == '1' || $('#frm_prevision').val() == '2' || $('#frm_prevision').val() == '3') {
					$('#frm_prevision').focus();
				}
			}
		}
	});
	validar('#frm_nombres_dau'			, "letras");
	validar('#frm_AP_dau'				, "letras");
	validar('#frm_AM_dau'				, "letras");
	validar("#frm_Naciemito"			, "fecha");
	validar("#frm_numero"          	   	, "numero");
	validar("#frm_nombre2" 	           	, "letras_numeros");
	validar("#frm_motivoText" 	        , "letras_numeros_caracteres");
	validar("#frm_motivoText2" 	        , "letras_numeros_caracteres");
	validar("#frm_motivoAgresion"      	, "letras_numeros_caracteres");
	validar("#frm_motivoLesiones"      	, "letras_numeros_caracteres");
	validar("#frm_motivoAlcoholemia"   	, "letras_numeros_caracteres");
	validar("#frm_direccion"           	, "letras_numeros");
	validar("#frm_numeroDireccion"      , "numero");
	validar("#frm_nombreCalle"			, "letras_numero");
	validar("#frm_numeroDireccion"		, "letras_numero");
	validar("#frm_telefonoCelular"		, "numero");
	validar("#frm_telefonoFijo"			, "numero");

	$('#frm_registro_paciente')[0].reset();
	$('#frm_prevision').prop('selectedIndex',0);
	$('#frm_Nacionalidad').prop('selectedIndex',0);
	$('#frm_pais_nacimiento').prop('selectedIndex',0);
	$('#frm_afrodescendiente').prop('selectedIndex',0);
	$('#frm_religion').prop('selectedIndex', 0);
	$('#divSeleccionCiudades').hide();
	$('#divSeleccionComunas').hide();
	$('#frm_sectorDomicilio').prop('selectedIndex',0);
	$('#frm_sexo').prop('selectedIndex',0);
	$('#frm_formaPago').prop('selectedIndex',0);
	$('#frm_centroAtencion').prop('selectedIndex',0);
	$('#frm_etnia').prop('selectedIndex',0);
	$("#divTipoAccidente").hide();
	$("#DivInstitucion").hide();
	$("#DivCampoMotivoAgresionManifestaciones").hide();
	$("#DivCampoMotivoAgresionConstatacionLesiones").hide();
	$("#DivConstatacionLesionesManifestacion").hide();
	$("#DivCampoMotivoLesiones").hide();
	$("#DivTransitoTipoManifestacion").hide();
	$("#divTipo_choque").hide();
	$("#DivN").hide();
	$("#DivNombre").hide();
	$("#DivMutualidad").hide();
	$("#DivTransitoTipo").hide();
	$("#DivHogar").hide();
	$("#DivLugarPublico").hide();
	$("#DivCampoMotivo").hide();
	$("#DivCampoMotivo2").hide();
	$("#DivCampoMotivoAgresion").hide();
	$("#DivCampoMotivoAgresion2").hide();
	$("#DivCampoMotivoLesiones").hide();
	$("#DivCampoMotivoAlcoholemia").hide();
	$("#frm_prevision").prop('disabled', false);
	$('#div_frm_tipo_mordedura').hide();
	$("#fechaAdmision").hide();
	$(".DivEnfermedadesRespiratorias").hide();
	let fecha 		=  $("#frm_Naciemito").val();
	let clickpila 	= false;
	$('#frm_correo').change(function(){
		let correcto = validarEmail($('#frm_correo').val());
		if(!correcto){
			$('#frm_correo').assert(false,'Debe ingresar formato válido de correo');
		}
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
	$('#frm_Naciemito').datepicker({
		todayBtn: "linked",
		todayHighlight: true,
		autoclose: true,
		container: $("#fecha"),
		format: 'dd/mm/yyyy',
		clearBtn: false,
		language: 'es'
	});
	$("#frm_Naciemito").change(function(){
		if ($("#frm_Naciemito").val() == '') {
			$("#frm_Naciemito").datepicker('setStartDate', fecha);
		}
	});
	$('#frm_fechaAdmision').datetimepicker();
	$('#btn_pilaFechAdm').on('click', function(){
		if (clickpila == false) {
			$("#fechaAdmision").show();
			clickpila = true;
		}
		else{
			$("#fechaAdmision").hide();
			$("#frm_fechaAdmision").val("");
			clickpila = false;
		}
	});
	// $("#verificarPrevision").click(function(){
	// 	let id = $("#idPacienteDau").val();
	// 	let response = ajaxRequest(host+'/indice_paciente_2017/controllers/server/Pacientes/main_controller.php','id='+id+'&accion=calcularHoraDif', 'POST', 'JSON', 1);
	// 	switch(response.fonasa){
	// 		case "successPrevisionRegistrada":
	// 			var funcion = function(response){
	// 				switch(response.status){
	// 					case "success":
	// 						var fn_cerrar = function(){
	// 							$('#pacienteFonasaCertificado').modal( 'hide' ).data( 'bs.modal', null );
	// 						};
	// 						var botones = [{ id: 'btnCerrar', value: 'Cerrar', function: fn_cerrar, class: 'btn btn-default'}];
	// 						modalFormularioSinCancelar('Datos Fonasa',host+'/indice_paciente_2017/views/modules/Fonasa/fonasaDetallePacientes.php','datosFonasa='+response.datosFonasa+'&id='+id+'&llamada=DAU','#pacienteFonasaCertificado','80%','auto',botones);
	// 					break;
	// 					case "noContetar":
	// 						modalMensaje2("Error de Conexión Fonasa", 'El servicio de <b>Fonasa no responde</b>, intentelo de nuevo mas tarde.', '', 550, 300, 'danger', 'remove');
	// 					break;
	// 					default:    modalMensaje("Error generico", response, "error_generico", 400, 300);
	// 				}
	// 			}
	// 			ajaxRequest(host+'/indice_paciente_2017/controllers/server/Fonasa/main_controller.php','id='+id+'&accion=confirmarPacienteFonasaDetalle', 'POST', 'JSON', 1,'Verificando Paciente en Fonasa ...', funcion);
	// 		break;
	// 	}
	// });
	$('#frm_tipoAccidente').change(function(){
		desmarcarCheckBoxes();
		$('#frm_institucion').prop("disabled", false);
		if($('#frm_tipoAccidente option:selected').val()==1){
			$("#DivInstitucion").show();
			$("#DivN").show();
			$("#DivNombre").show();
			var cod  = $('#frm_tipoAccidente option:selected').val();
			var response = ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','codigoTipoAccidente='+cod+'&accion=cargarParametros2', 'POST', 'JSON', 1);
			$('#frm_institucion').empty();
			$('#frm_institucion').append('<option value="">Seleccione</option>');
			for (var i=0; i<response.length; i++) {
				$('#frm_institucion').append('<option value="' + response[i].ins_id + '">' + response[i].ins_descripcion + '</option>');
			}
		}else{
			if($('#frm_tipoAccidente option:selected').val()!=1){
				$("#DivInstitucion").hide();
				$("#DivN").hide();
				$("#DivNombre").hide();
				$("#frm_numero").val("");
				$("#frm_nombre2").val("");
			}
		}
		if($('#frm_tipoAccidente option:selected').val()==2){
			$("#DivMutualidad").show();
			var cod  = $('#frm_tipoAccidente option:selected').val();
			var response = ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','codigoTipoAccidente='+cod+'&accion=cargarParametros2', 'POST', 'JSON', 1);
			$('#frm_mutualidad').empty();
			$('#frm_mutualidad').append('<option value="">Seleccione</option>');
			for (var i=0; i<response.length; i++) {
				$('#frm_mutualidad').append('<option value="' + response[i].ins_id + '">' + response[i].ins_descripcion + '</option>');
			}
		}else{
			if($('#frm_tipoAccidente option:selected').val()!=2){
				$("#DivMutualidad").hide();
			}
		}
		if($('#frm_tipoAccidente option:selected').val()==3){
			$("#DivTransitoTipo").show();
			$("#DivTransitoTipoManifestacion").show();
		}else{
			if($('#frm_tipoAccidente option:selected').val()!=3){
				$("#DivTransitoTipo").hide();
				$("#DivTransitoTipoManifestacion").hide();
				$('#frm_transitoTipo').prop('selectedIndex',0);
			}
		}
		if($('#frm_tipoAccidente option:selected').val()==4){
			$("#DivHogar").show();
			var cod  = $('#frm_tipoAccidente option:selected').val();
			var response = ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','codigoTipoAccidente='+cod+'&accion=cargarParametros2', 'POST', 'JSON', 1);
			$('#frm_hogar').empty();
			$('#frm_hogar').append('<option value="">Seleccione</option>');
			for (var i=0; i<response.length; i++) {
				$('#frm_hogar').append('<option value="' + response[i].ins_id + '">' + response[i].ins_descripcion + '</option>');
			}
		}else{
			if($('#frm_tipoAccidente option:selected').val()!=4){
				$("#DivHogar").hide();
			}
		}
		if($('#frm_tipoAccidente option:selected').val()==5){
			$("#DivLugarPublico").show();
			$("#DivTransitoTipoManifestacion").show();
			var cod  = $('#frm_tipoAccidente option:selected').val();
			var response = ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','codigoTipoAccidente='+cod+'&accion=cargarParametros2', 'POST', 'JSON', 1);
			$('#frm_lugarPublico').empty();
			$('#frm_lugarPublico').append('<option value="">Seleccione</option>');
			for (var i=0; i<response.length; i++) {
				$('#frm_lugarPublico').append('<option value="' + response[i].ins_id + '">' + response[i].ins_descripcion + '</option>');
			}
		}else{
			if($('#frm_tipoAccidente option:selected').val()!=5){
				//$("#DivTransitoTipoManifestacion").hide();
				$("#DivLugarPublico").hide();
			}
		}
	});
	$('#frm_mordedura').change(function(){
		if($('#frm_mordedura').val() > 0){
			$('#frm_tipo_mordedura').val(1);
			$('#div_frm_tipo_mordedura').show();
		}else{
			$('#frm_tipo_mordedura').val(0);
			$('#frm_tipo_mordedura').attr('disabled',true);
			$('#div_frm_tipo_mordedura').hide();
		}
	});
	$('#frm_transitoTipo').change(function(){

		if($('#frm_transitoTipo option:selected').val()==4){
			$("#divTipo_choque").show();

		}else{
			$("#divTipo_choque").hide();
			$('#frm_tipo_choque').prop('selectedIndex',0);
		}
	});
	$('#frm_motivoConsulta').change(function(){
		desmarcarCheckBoxes();
		$(".DivEnfermedadesRespiratorias").hide(100);
		if($('#frm_motivoConsulta option:selected').val()==""){
			$("#frm_numero").val("");
			$("#frm_nombre2").val("");
			$( "#frm_vif" ).prop( "checked", false );
			$( "#frm_colision" ).prop( "checked", false );
			$( "#frm_volcamiento" ).prop( "checked", false );
		}
		if($('#frm_motivoConsulta option:selected').val()==1){
			$("#divTipoAccidente").show()
			$("#DivMutualidad").hide();
			var cod  = $('#frm_motivoConsulta option:selected').val();
			var response = ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','codigoAccidente='+cod+'&accion=cargarParametros', 'POST', 'JSON', 1);
			$('#frm_tipoAccidente').empty();
			$('#frm_tipoAccidente').append('<option value="">Seleccione</option>');
			$('#frm_institucion').append('<option value="">Seleccione</option>');
			for (var i=0; i<response.length; i++) {
				$('#frm_tipoAccidente').append('<option value="' + response[i].sub_mot_id + '">' + response[i].sub_mot_descripcion + '</option>');
			}
		}else{
			if($('#frm_motivoConsulta option:selected').val()!=1){
				$("#divTipoAccidente").hide()
				$("#DivInstitucion").hide()
				$("#DivN").hide()
				$("#DivNombre").hide()
				$("#DivMutualidad").hide()
				$("#DivAtropelladoPor").hide()
				$("#DivChoqueCon").hide()
				$("#DivCheckBoxColisionVulcamiento").hide()
				$("#DivHogar").hide()
				$("#DivLugarPublico").hide()
				$("#frm_numero").val("");
				$("#frm_nombre2").val("");
				$("#DivTransitoTipo").hide()
				$("#DivTransitoTipoManifestacion").hide()
			}
		}
		if($('#frm_motivoConsulta option:selected').val()==2){
			$("#DivCampoMotivo").show();
			$("#frm_motivoText").val("");
			$("#frm_motivoText2").val("");
			$('#frm_motivo').attr("placeholder","Motivo de la enfermedad");
			$("#DivCampoMotivo").show();
			$("#DivCampoMotivo2").hide();
			$(".DivEnfermedadesRespiratorias").show(100);
		}else if($('#frm_motivoConsulta option:selected').val()==1){
			$("#frm_motivoText").val("");
			$("#frm_motivoText2").val("");
			$('#frm_motivo').attr("placeholder","Descripción del accidente");
			$("#DivCampoMotivo").hide();
			$("#DivCampoMotivo2").show();
		}else{
			if($('#frm_motivoConsulta option:selected').val()!=2){
				$("#DivCampoMotivo").hide()
				$("#DivCampoMotivo2").hide()
				$("#frm_motivoText2").val("");
				$("#frm_motivoText").val("");
				$("#DivTransitoTipo").hide()
				$("#DivTransitoTipoManifestacion").hide()
			}
		}
		if($('#frm_motivoConsulta option:selected').val()==3){
			$("#DivCampoMotivoAgresion").show()
			$("#DivCampoMotivoAgresion2").show()
			$("#DivCampoMotivoAgresionManifestaciones").show();
			$("#DivCampoMotivoAgresionConstatacionLesiones").show();
			$("#frm_vif").show()
			$("#labelVIF").show()
		}else{
			if($('#frm_motivoConsulta option:selected').val()!=3){
				$("#DivCampoMotivoAgresion").hide()
				$("#DivCampoMotivoAgresion2").hide()
				$("#DivCampoMotivoAgresionManifestaciones").hide();
				$("#DivCampoMotivoAgresionConstatacionLesiones").hide();
				$("#frm_motivoAgresion").val("")
				$("#frm_vif").hide()
				$("#labelVIF").hide()
				$( "#frm_vif" ).prop( "checked", false );
			}
		}
		if($('#frm_motivoConsulta option:selected').val()==4){
			$("#DivCampoMotivoLesiones").show()
			$("#DivConstatacionLesionesManifestacion").show()
		}else{
			if($('#frm_motivoConsulta option:selected').val()!=4){
				$("#DivCampoMotivoLesiones").hide()
				$("#frm_motivoLesiones").val("")
				$("#DivConstatacionLesionesManifestacion").hide()
			}
		}
		if($('#frm_motivoConsulta option:selected').val()==5){
			$("#DivCampoMotivoAlcoholemia").show();
		}else{
			if($('#frm_motivoConsulta option:selected').val()!=5){
				$("#DivCampoMotivoAlcoholemia").hide()
				$("#frm_motivoAlcoholemia").val("")
			}
		}
	});
	$('#frm_region').change(function(){
		if($('#frm_region').val() != "" && $('#frm_region').val() != "99"){
			$('#frm_ciudad').prop("disabled", false);
			$("#divSeleccionCiudades").show();
			var regId  = $('#frm_region option:selected').val();
			var response = ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','regId='+regId+'&accion=cargarCiudades', 'POST', 'JSON', 1);
			$('#frm_ciudad').empty();
			$('#frm_comuna').empty();
			$("#divSeleccionComunas").hide();
			$('#frm_ciudad').append('<option value="">Seleccione Ciudad</option>');
				for (var i=0; i<response.length; i++) {
					$('#frm_ciudad').append('<option value="' + response[i].CIU_Id + '">' + response[i].CIU_Descripcion + '</option>');
				}
		}
		else{
			$('#frm_ciudad').empty();
			$('#frm_region').val("99");
			$("#divSeleccionCiudades").hide();
		}
	});
	$('#frm_ciudad').change(function(){
		if($('#frm_ciudad').val() != "" && $('#frm_ciudad').val() != "999"){
			$('#frm_comuna').prop("disabled", false);
			$("#divSeleccionComunas").show();
			var ciuId  = $('#frm_ciudad option:selected').val();
			var response = ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','ciuId='+ciuId+'&accion=cargarComunas', 'POST', 'JSON', 1);
			$('#frm_comuna').empty();
			$('#frm_comuna').append('<option value="">Seleccione Comuna</option>');
				for (var i=0; i<response.length; i++) {
					$('#frm_comuna').append('<option value="' + response[i].id + '">' + response[i].comuna + '</option>');
				}
		}
		else{
			$('#frm_ciudad').empty();
			$('#frm_comuna').empty();
			$('#frm_region').val("99");
			$("#divSeleccionCiudades").hide();
			$("#divSeleccionComunas").hide();
		}
	});
	$('#frm_comuna').change(function(){
		if($('#frm_comuna').val() == ""){
			$('#frm_ciudad').empty();
			$('#frm_comuna').empty();
			$('#frm_region').val("99");
			$("#divSeleccionCiudades").hide();
			$("#divSeleccionComunas").hide();
		}
	});

	$('#consFonasa').on('click', function(){
		consultarFonasa();
	});
	setTimeout(consultarFonasa, 100);
	validar("#frm_nombreOtrosEstablecimientos", "letras");
	$(".pacienteEsDerivado").hide();
	$("#slc_derivado").on("change", function(){
		if ( $("#slc_derivado").val() == 'N' ) {
			$(".pacienteEsDerivado").find('select').val('');
			$(".pacienteEsDerivado").find('input').val('');
			$(".pacienteEsDerivado").hide(100);
			return;
		}
		$(".establecimientosRedSalud").show(100);
	});
	$(".establecimientosRedSalud").on("change", () => {
		if ( $("#frm_establecimientosRedSalud").val() == 35 || $("#frm_establecimientosRedSalud").val() == 36 || $("#frm_establecimientosRedSalud").val() == 37 ) {
			$("#frm_nombreOtrosEstablecimientos").val("");
			$(".otrosEstablecimientos").show(100);
			return;
		}
		$(".otrosEstablecimientos").hide(100);
	});
	$('input[name="frm_manifestaciones"]').on('change', function(){
		if($(this).is(':checked')) {
			$(this).val('S');
		} else {
			$(this).val('N');
		}
	});
	$('input[name="frm_constatacionLesiones"]').on('change', function(){
		if($(this).is(':checked')) {
			$(this).val('S');
		} else {
			$(this).val('N');
		}
	});
	$('input[name="frm_dolorGarganta"]').on('change', function(){
		if($(this).is(':checked')) {
			$(this).val('S');
		} else {
			$(this).val('N');
		}
	});
	$('input[name="frm_tos"]').on('change', function(){
		if($(this).is(':checked')) {
			$(this).val('S');
		} else {
			$(this).val('N');
		}
	});
	$('input[name="frm_dificultadRespiratoria"]').on('change', function(){
		if($(this).is(':checked')) {
			$(this).val('S');
		} else {
			$(this).val('N');
		}
	});
	validar("#frm_observacionesEpidemiologia", "letras_numero");
	$("#divPaisEpidemiologia").hide();
	$('#divObservacionesEpidemiologia').hide();
	$("#frm_viajeEpidemiologico").on("change", function(){
		$("#frm_paisEpidemiologia").val("");
		$("#frm_observacionEpidemiologica").val("");
		if ( String($("#frm_viajeEpidemiologico").val()) === "S" ) {
			$("#divPaisEpidemiologia").show(100);
			$("#divObservacionesEpidemiologia").show(100);
			return;
		}
		$("#divPaisEpidemiologia").hide();
		$("#divObservacionesEpidemiologia").hide();
	});
	// <i class="fas fa-exclamation-circle"></i>
	$("#registrar_paciente").click(function(){
		
		$.validity.start();
		//Sección epidemiológica
		if($("#frm_transexual").val() == 1 && $("#frm_nombreSocial").val() == "" && $("#frm_nombre_legal").val() == 0){
			$('#frm_nombreSocial').assert(false,'Debe Ingresar Un Nombre Social');
			$.validity.end();
			return false;
		}
		if($("#idPacienteDau").val()==""){
			$('#consFonasa').assert(false,'Debe buscar un paciente');
			$.validity.end();
			return false;
		}
		if ( $("#frm_viajeEpidemiologico").val() === null || String($("#frm_viajeEpidemiologico").val()) === "" ) {
			$('#frm_viajeEpidemiologico').assert(false,'Debe Seleccionar Opción');
			$.validity.end();
			return false;
		}
		if ( $("#frm_viajeEpidemiologico").val() !== null && String($("#frm_viajeEpidemiologico").val()) !== "" && String($("#frm_viajeEpidemiologico").val()) === "S" ) {
			if ( $("#frm_paisEpidemiologia").val() === null || String($("#frm_paisEpidemiologia")) === "" ) {
				$('#frm_paisEpidemiologia').assert(false,'Debe Seleccionar País');
				$.validity.end();
				return false;
			}
		}
		//END Sección epidemiológica
		if($('#frm_prevision option:selected').val()==""){
			$('#frm_prevision').assert(false,'Debe Seleccionar un tipo de Previsión');
			$.validity.end();
			return false;
		}
		if($('#frm_religion option:selected').val()==""){
			$('#frm_religion').assert(false,'Debe Seleccionar una religión');
			$.validity.end();
			return false;
		}
		if($('#frm_formaPago option:selected').val()==""){
			$('#frm_formaPago').assert(false,'Debe Seleccionar el Tipo de Forma de Pago');
			$.validity.end();
			return false;
		}
		if ($('#frm_correo').val() != '') {
			var correcto = validarEmail($('#frm_correo').val());
			if(!correcto){
				$('#frm_correo').assert(false,'Debe ingresar formato válido de correo');
				$.validity.end();
				return false;
			}
		}
		if($('#frm_tipoDomicilio option:selected').val()==""){
			$('#frm_tipoDomicilio').assert(false,'Debe Seleccionar el Tipo de Domicilio');
			$.validity.end();
			return false;
		}
		if ( ! verificarDatosPacienteDerivado() ) {
			$.validity.end();
			return false;
		}
		if($('#frm_atencion_admision option:selected').val()==""){
			$('#frm_atencion_admision').assert(false,'Debe Seleccionar el Tipo de Atención');
			$.validity.end();
			return false;
		}
		if($('#frm_formallegada option:selected').val()==""){
			$('#frm_formallegada').assert(false,'Debe Seleccionar la Forma de Llegada');
			$.validity.end();
			return false;
		}
		if($('#frm_motivoConsulta option:selected').val()==""){
			$('#frm_motivoConsulta').assert(false,'Debe Seleccionar algún Motivo de Consulta');
			$.validity.end();
			return false;
		}
		if($('#frm_motivoConsulta option:selected').val()==2 && ( $("#frm_motivoText").val()=="" ) ){
			$('#frm_motivoText').assert(false,'Debe ingresar un Motivo');
			$.validity.end();
			return false;
		}
		if($('#frm_motivoConsulta option:selected').val()==1 && $('#frm_tipoAccidente option:selected').val()==""){
			$('#frm_tipoAccidente').assert(false,'Debe Seleccionar Tipo de Accidente');
			$.validity.end();
			return false;
		}
		if($('#frm_motivoConsulta option:selected').val()==1 && $('#frm_tipoAccidente option:selected').val()==1 && $('#frm_institucion option:selected').val()==""){
			$('#frm_institucion').assert(false,'Debe Seleccionar Institución');
			$.validity.end();
			return false;
		}
		if($('#frm_motivoConsulta option:selected').val()==1 && $('#frm_tipoAccidente option:selected').val()==2 && $('#frm_mutualidad option:selected').val()==""){
			$('#frm_mutualidad').assert(false,'Debe Seleccionar Mutualidad');
			$.validity.end();
			return false;
		}
		if($('#frm_motivoConsulta option:selected').val()==1 && $('#frm_tipoAccidente option:selected').val()==3 && $('#frm_transitoTipo option:selected').val()==4 && $('#frm_tipo_choque option:selected').val()==""){
			$('#frm_tipo_choque').assert(false,'Debe Seleccionar el tipo de choque');
			$.validity.end();
			return false;
		}
		if($('#frm_region').val() == ""){
			$('#frm_region').val("99");
		}
		if($('#frm_tipoAccidente option:selected').val()=="3" && $('#frm_transitoTipo option:selected').val()==""){
			$('#frm_transitoTipo').assert(false,'Debe Seleccionar algún Tipo de Tránsito');
			$.validity.end();
			return false;
		}
		if($('#frm_tipoAccidente option:selected').val()=="4" && $('#frm_hogar option:selected').val()==""){
			$('#frm_hogar').assert(false,'Debe Seleccionar Dónde se Produjo');
			$.validity.end();
			return false;
		}

		result = $.validity.end();
		if(result.valid==false){
			return false;
		}
		cadena = $("#frm_rut1").val();
		if(cadena.indexOf('.') != -1){
			numeroDocumento     = $("#frm_rut1").val();
			numeroDocumento     = $.Rut.quitarFormato(numeroDocumento);
			numeroDocumento     = numeroDocumento.substring(0, numeroDocumento.length-1);
		}else{
			numeroDocumento     = $("#frm_rut1").val();
		}
		var idPaciente      = $("#idPacienteDau").val();
		var  grabarPacienteAdmision = function(){
			cadena = $("#frm_rut1").val();
			if(cadena.indexOf('.') != -1){
				numeroDocumento     = $("#frm_rut1").val();
				numeroDocumento     = $.Rut.quitarFormato(numeroDocumento);
				numeroDocumento     = numeroDocumento.substring(0, numeroDocumento.length-1);
			}else{
				if (cadena == '0-') {
					numeroDocumento = 0;
				}
				else{
					numeroDocumento     = $("#frm_rut1").val();
				}
			}
			camposDisable();
			result = $.validity.end();
			if(result.valid==false){
				return false;
			}
			var rutPaciente      = $("#rut_hidden").val();
			var nombrePaciente   = $("#frm_nombres_dau").val();
			var APpaciente       = $("#frm_AP_dau").val();
			var AMpaciente       = $("#frm_AM_dau").val();
			var idPaciente       = $("#idPacienteDau").val();
			var grabar = function(response){
				$("#frm_prevision").prop('disabled', false);
				switch(response.status){
					case "success":
						var id = '';
						var id = response.ultimoID;
						var ctactePaciente = response.ultimaCtaCte
						// if ( $('#frm_atencion_admision').val() == 1 || $('#frm_atencion_admision').val() == 2 ) {
						// 	let imprimir = function () {
						// 		$('#iframePDFDAU').get(0).contentWindow.focus();
						// 		$("#iframePDFDAU").get(0).contentWindow.print();
						// 	}
						// 	let botones = 	[
						// 						{ id: 'btnImprimir', value: '<i class="glyphicon glyphicon-print" aria-hidden="true"></i> Imprimir', function: imprimir, class: 'btn btn-primary btnPrint' }
						// 					]
						// 	modalFormulario("<label class='mifuente darkcolor-barra2'>&nbsp;&nbsp;Detalle Admisión PDF</label>",raiz+'/views/modules/admision/admisionDetalleVoucherTermico.php','id='+id,'#detalleAdmisionVoucherTermico',"modal-lg","","fas fa-user-plus darkcolor-barra2",botones);
						// } else if ( $('#frm_atencion_admision').val() == 3 ) {
						// 	var funcionImprimir = function(){
						// 		$("#contendido2").print();
						// 	}
						// 	var botones = [
						// 	{ id: 'btnImprimir', value: '<i class="glyphicon glyphicon-print" aria-hidden="true"></i> Imprimir', function: funcionImprimir, class: 'btn btn-primary2' }
						// 	]

						// 	modalFormulario("<label class='mifuente darkcolor-barra2'>&nbsp;&nbsp;Detalle Admisión</label>",raiz+'/views/modules/admision/admisionDetalle.php','id='+id,'#detalleAdmision',"modal-lg","","fas fa-user-plus darkcolor-barra2",botones);
						// }
						var pyxis      = function(response){
							switch(response.status){
								case "success":
									texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-user-plus mr-2" style="color: #5a8dc5; font-size: 26px"></i>Admisión exitosa. </h4><hr><p>¡Felicidades! Parece que has logrado una admisión exitosa de un paciente en el sistema y se registró en Pyxis.</p> </div>';
									modalMensajNoCabecera('Exito',texto,  "#modal", "modal-md", "success");
								break;
								default:        
									texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-user-plus mr-2" style="color: #5a8dc5; font-size: 26px"></i>Admisión exitosa. </h4><hr><p>¡Felicidades! Parece que has logrado una admisión exitosa de un paciente en el sistema, sin embargo <b>NO</b> se registró en Pyxis.</p> </div>';
									modalMensajNoCabecera('Exito',texto,  "#modal", "modal-md", "success");
								break;
							}
							ajaxContent(raiz+'/views/modules/admision/busquedaAdmision.php','','#contenido','', true);
						}
						ajaxRequest(raiz+'/controllers/server/pyxis/main_controller.php',
									'accion=pyxis'+
									'&rutPaciente='+numeroDocumento+
									'&nombrePaciente='+nombrePaciente+
									'&APpaciente='+APpaciente+
									'&AMpaciente='+AMpaciente+
									'&idPaciente='+idPaciente+
									'&ctactePaciente='+ctactePaciente+
									'&dau_id='+id ,
									'POST',
									'JSON',
									1,
									'Enviando Pyxis...',
									pyxis
						);
						id='';
					break;
					case "fallecido":
						texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-times mr-2" style="color: #ff6354; font-size: 26px"></i>Error. </h4><hr><p>El Paciente que desea admisionar, se encuentra fallecido en nuestro registro de HJNC.</p> </div>';
						modalMensajNoCabecera('Exito',texto,  "#modal", "modal-md", "success");
					break;
					default:        
						modalMensajNoCabecera('Error Defecto','',  "#modal", "modal-md", "success");
					break;
				}

	        	$('#modal_admision').modal( 'hide' ).data( 'bs.modal', null );
			}
			ajaxRequest(raiz+'/controllers/server/admision/main_controller.php',$("#frm_registro_paciente").serialize()+'&accion=grabarPacienteAdmision&numeroDocumento='+numeroDocumento, 'POST', 'JSON', 1,'Guardando Paciente en Admisión...', grabar);

		}
		var validarDocumento = function(response){
			result = $.validity.end();
			if(result.valid==false){
				return false;
			}
			if(response>0){
				texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Advertencia </h4>  <hr>  <p class="mb-0">Este Paciente ya se Encuentra en Admisión</p></div>';
				modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
			}else{
				texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> ATENCIÓN </h4>  <hr>  <p class="mb-0 text-center">Se procedera a crear un Nuevo Paciente en Admision, <b>¿Desea continuar?</b></p></div>';
        	modalConfirmacion("<label class='mifuente'>Advertencia</label>", texto, "primary", grabarPacienteAdmision);
			}
		}
        ajaxRequest(raiz+"/controllers/server/admision/main_controller.php","accion=verificaAdmision&idPaciente="+idPaciente, 'POST', 'JSON', 1, '', validarDocumento);

		
	});
	setInterval(checkLocalStorage, 1000);
});
