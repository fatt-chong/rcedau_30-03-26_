$(document).ready(function(){

	//04-07-24 - ocultar o mostrar contenedores de la info trans
	//$("#div_transexual").hide();
	// $("#div_nombre_legal").hide();
	// $("#div_identidadGenero").hide();
	$("#frm_transexual").prop("disabled",true);
	$("#frm_nombre_legal").prop("disabled",true);
	$("#frm_identidadGenero").prop("disabled",true);


	$("#DivCampoMotivoAgresion2").hide();
	$("#frm_vif").hide();
	$("#labelVIF").hide();
	$("#DivCampoMotivoAgresionManifestaciones").hide();
	$("#DivCampoMotivoAgresionConstatacionLesiones").hide();
	$("#divTipoAccidente").hide();
	$("#DivInstitucion").hide();
	$("#DivN").hide();
	$("#DivNombre").hide();
	$("#DivMutualidad").hide();
	$("#DivTransitoTipo").hide();
	$("#DivTransitoTipoManifestacion").hide();
	$("#divTipo_choque").hide();
	$("#DivHogar").hide();
	$("#DivLugarPublico").hide();
	//04-07-24 - ocultar o mostrar contenedores de la info trans

	validar("#frm_numero"          	   ,"numero");
	validar("#frm_nombre2" 	           ,"letras_numeros");
	validar("#frm_motivo" 	           ,"letras_numeros");
	validar("#frm_motivoAgresion"      ,"letras_numeros");
	validar("#frm_motivoLesiones"      ,"letras_numeros");
	validar("#frm_motivoAlcoholemia"   ,"letras_numeros");
	validar("#frm_direccion"           ,"letras_numeros");


	$(".DivEnfermedadesRespiratorias").hide();


	$("#btnVolver").click(function(){
		ajaxContent(raiz+'/views/modules/admision/busquedaAdmision.php','','#contenido','', true);
	});



	var modified;
	$("input, select").change(function () {
		modified = true;
	});



	$("#recuperarInformacionAdmision").click(function(){
		if(modified == true){
			var id = $("#FOLIO").val();
			// $('html, body').animate({
			// 	scrollTop: $("#DivAdmision").offset().top
			// }, 2000);
			ajaxContent('views/modules/admision/admisionUpdateDetalle.php','id='+id,'#contenido', 'Cargando Información prevía de la Admisión N° '+id, true);
		}else{
			var id = $("#FOLIO").val();
			message("warning", "No se Detectaron Cambios en la Admisión N° <b>"+id+"</b>.", '', "#admisionAlerta", "dangerMensaje", true, '');
		}

	});



	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
		$('[data-toggle="tooltip"]').on('shown.bs.tooltip', function () {
        	$('.tooltip').addClass('animated tada');
        })
	});



	$('#frm_motivoConsulta').change(function(){

		$('input[name="frm_dolorGarganta"]').prop("checked", false);

		$('input[name="frm_tos"]').prop("checked", false);

		$('input[name="frm_dificultadRespiratoria"]').prop("checked", false);

		if($('#frm_motivoConsulta option:selected').val()==""){
			$("#frm_numero").val("");
			$("#frm_nombre2").val("");
			$( "#frm_vif" ).prop( "checked", false );
			$( "#frm_colision" ).prop( "checked", false );
			$( "#frm_volcamiento" ).prop( "checked", false );
		}

		if($('#frm_motivoConsulta option:selected').val()==1){
			$("#DivCampoMotivo").show("slow");
			$("#divTipoAccidente").show("slow")
			$("#DivMutualidad").hide("slow");
			$("#frm_motivoAcci").val("")
			$('#frm_InstitucionDellate').val("");
			$("#divTipo_choque").hide("slow");
			$('#frm_tipo_choque option:selected').val(0);

			$('#frm_InstitucionDellate').val("");
			$('#frm_trabajoMutualidad').val("");
			$('#frm_tipo_choque_id').val("");
			$('#frm_produjoEn').val("");
			$('#frm_otroLugar').val("");

			var cod  = $('#frm_motivoConsulta option:selected').val();
			var response = ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','codigoAccidente='+cod+'&accion=cargarParametros', 'POST', 'JSON', 1);
			$('#frm_tipoAccidente').empty();
			$('#frm_tipoAccidente').append('<option value="">Seleccione</option>');
			$('#frm_institucion').append('<option value="">Seleccione</option>');
			for (var i=0; i<response.length; i++) {
				$('#frm_tipoAccidente').append('<option value="' + response[i].sub_mot_id + '">' + response[i].sub_mot_descripcion + '</option>');
			}
		} else {
			if($('#frm_motivoConsulta option:selected').val()!=1){
				$("#divTipoAccidente").hide("slow")
				$("#DivInstitucion").hide("slow")
				$("#DivN").hide("slow")
				$("#DivNombre").hide("slow")
				$("#DivMutualidad").hide("slow")
				$("#DivAtropelladoPor").hide("slow")
				$("#DivChoqueCon").hide("slow")
				$("#DivCheckBoxColisionVulcamiento").hide("slow")
				$("#DivHogar").hide("slow")
				$("#DivLugarPublico").hide("slow")
				$("#DivTransitoTipo").hide("slow")
				$("#frm_numero").val("");
				$("#frm_nombre2").val("");
				$('#frm_institucion option:selected').val(0);
				$('#frm_mutualidad option:selected').val(0);
				$('#frm_transitoTipo').prop('selectedIndex',0);
				$('#frm_hogar option:selected').val(0);
				$('#frm_lugarPublico option:selected').val(0);
				$("#frm_motivoenf").val("");
				$("#frm_motivoAlcoholemia").val("")
				$('#frm_tipo_choque_id').val("");
				$("#DivTransitoTipoManifestacion").hide("slow")
			}
		}

		if($('#frm_motivoConsulta option:selected').val()==2){
			$('#frm_tipo_choque').prop('selectedIndex',0);
			$('#frm_motivo').val("");
			$("#DivCampoMotivo").show("slow")
			$("#divTipo_choque").hide("slow")
			$('#frm_InstitucionDellate').val("");
			$("#divTipo_choque").hide("slow");
			$('#frm_tipo_choque option:selected').val(0);

			$('#frm_InstitucionDellate').val("");
			$('#frm_trabajoMutualidad').val("");
			$('#frm_tipo_choque_id').val("");
			$('#frm_produjoEn').val("");
			$('#frm_otroLugar').val("");
			$(".DivEnfermedadesRespiratorias").show(100);

		}else{

			$(".DivEnfermedadesRespiratorias").hide(100);
			if($('#frm_motivoConsulta option:selected').val()!=2){
				$("#DivCampoMotivo").show("slow")
				$("#frm_motivo").val("");
				$("#DivTransitoTipo").hide("slow")
				$("#DivTransitoTipoManifestacion").hide("slow")

			}
		}

		if($('#frm_motivoConsulta option:selected').val()==3){
			$('#frm_tipo_choque').prop('selectedIndex',0);
			$('#frm_motivo').val("");
			$("#divTipo_choque").hide("slow")
			$("#DivCampoMotivoAgresion").show("slow")
			$("#DivCampoMotivoAgresion2").show("slow")
			$("#frm_vif").show()
			$("#labelVIF").show()
			$("#DivCampoMotivoAgresionManifestaciones").show("slow");
			$("#DivCampoMotivoAgresionConstatacionLesiones").show("slow");

			$("#DivCampoMotivo").show("slow")
			$('#frm_InstitucionDellate').val("");
			$("#divTipo_choque").hide("slow");
			$('#frm_tipo_choque option:selected').val(0);

			$('#frm_InstitucionDellate').val("");
			$('#frm_trabajoMutualidad').val("");
			$('#frm_tipo_choque_id').val("");
			$('#frm_produjoEn').val("");
			$('#frm_otroLugar').val("");

		}else{
			if($('#frm_motivoConsulta option:selected').val()!=3){
				$("#DivCampoMotivoAgresion").hide("slow")
				$("#DivCampoMotivoAgresion2").hide("slow")
				$("#frm_motivoAgresion").val("")
				$("#frm_vif").hide()
				$("#labelVIF").hide()
				$( "#frm_vif" ).prop( "checked", false );
				$("#DivCampoMotivoAgresionManifestaciones").hide("slow");
				$("#DivCampoMotivoAgresionConstatacionLesiones").hide("slow");
			}
		}

		if($('#frm_motivoConsulta option:selected').val()==4){
			$("#DivCampoMotivoAgresionManifestaciones").show("slow");
			$('#frm_motivo').val("");
			$("#DivCampoMotivoLesiones").show("slow")
			$('#frm_tipo_choque').prop('selectedIndex',0);
			$("#divTipo_choque").hide("slow")

			$("#DivCampoMotivo").show("slow")
			$('#frm_InstitucionDellate').val("");
			$("#divTipo_choque").hide("slow");
			$('#frm_tipo_choque option:selected').val(0);

			$('#frm_InstitucionDellate').val("");
			$('#frm_trabajoMutualidad').val("");
			$('#frm_tipo_choque_id').val("");
			$('#frm_produjoEn').val("");
			$('#frm_otroLugar').val("");

		}else{
			if($('#frm_motivoConsulta option:selected').val()!=4){
				$("#DivCampoMotivoLesiones").hide("slow")
				$("#frm_motivoLesiones").val("")
			}
		}

		if($('#frm_motivoConsulta option:selected').val()==5){
			$('#frm_motivo').val("");
			$("#DivCampoMotivoAlcoholemia").show("slow")
			$('#frm_tipo_choque').prop('selectedIndex',0);
			$("#divTipo_choque").hide("slow")

			$("#DivCampoMotivo").show("slow")
			$('#frm_InstitucionDellate').val("");
			$("#divTipo_choque").hide("slow");
			$('#frm_tipo_choque option:selected').val(0);

			$('#frm_InstitucionDellate').val("");
			$('#frm_trabajoMutualidad').val("");
			$('#frm_tipo_choque_id').val("");
			$('#frm_produjoEn').val("");
			$('#frm_otroLugar').val("");
		}else{
			if($('#frm_motivoConsulta option:selected').val()!=5){
				$("#DivCampoMotivoAlcoholemia").hide("slow")
				$("#frm_motivoAlcoholemia").val("")
			}
		}
	});



	$('#frm_tipoAccidente').change(function(){

		$('#frm_institucion').prop("disabled", false);

		if($('#frm_tipoAccidente option:selected').val()==1){
			$("#DivInstitucion").show("slow");
			$("#DivN").show("slow");
			$("#DivNombre").show("slow");

			$("#divTipo_choque").hide("slow");
			$('#frm_tipo_choque option:selected').val(0);
			var cod  = $('#frm_tipoAccidente option:selected').val();
			var response = ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','codigoTipoAccidente='+cod+'&accion=cargarParametros2', 'POST', 'JSON', 1);

			$('#frm_institucion').empty();
			$('#frm_institucion').append('<option value="">Seleccione</option>');
			for (var i=0; i<response.length; i++) {
				var selected="";
				if (response[i].ins_id == $("#frm_InstitucionDellate").val())
					selected=" selected ";
				$('#frm_institucion').append('<option value="' + response[i].ins_id + '"'+selected+'>' + response[i].ins_descripcion + '</option>');
			}

		}else{
			if($('#frm_tipoAccidente option:selected').val()!=1){
				$("#DivInstitucion").hide("slow");
				$("#DivN").hide("slow");
				$("#DivNombre").hide("slow");
				$("#frm_numero").val("");
				$("#frm_nombre2").val("");
				$('#frm_institucion option:selected').val(0);
			}
		}

		if($('#frm_tipoAccidente option:selected').val()==2){
			$("#DivMutualidad").show("slow");
			$("#divTipo_choque").hide("slow");
			$('#frm_tipo_choque option:selected').val(0);
			var cod  = $('#frm_tipoAccidente option:selected').val();
			var response = ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','codigoTipoAccidente='+cod+'&accion=cargarParametros2', 'POST', 'JSON', 1);
			$('#frm_InstitucionDellate').val("");
			$('#frm_mutualidad').empty();
			$('#frm_mutualidad').append('<option value="">Seleccione</option>');
			for (var i=0; i<response.length; i++) {
				var selected="";
				if (response[i].ins_id == $("#frm_trabajoMutualidad").val())
					selected=" selected ";
				$('#frm_mutualidad').append('<option value="' + response[i].ins_id + '"'+selected+'>' + response[i].ins_descripcion + '</option>');
			}
		}else{
			if($('#frm_tipoAccidente option:selected').val()!=2){
				$("#DivMutualidad").hide("slow");
				$('#frm_mutualidad').prop('selectedIndex',0);
				$('#frm_trabajoMutualidad').val("");

			}
		}

		if($('#frm_tipoAccidente option:selected').val()==3){
			$("#DivTransitoTipo").show("slow");
			$('#frm_InstitucionDellate').val("");
			$("#divTipo_choque").hide("slow");
			$("#DivTransitoTipoManifestacion").show("slow");
		}else{
			if($('#frm_tipoAccidente option:selected').val()!=3){
				$("#DivTransitoTipo").hide("slow");
				$('#frm_transitoTipo').prop('selectedIndex',0);
				$('#frm_tipo_choque_id').val("");
				$("#DivTransitoTipoManifestacion").hide("slow");
			}
		}


		$('#frm_transitoTipo').change(function(){

			if($('#frm_transitoTipo option:selected').val()==4){
				$("#divTipo_choque").show("slow");
				var cod  = $('#frm_transitoTipo option:selected').val()

				var response = ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','codigoTipoChoque='+cod+'&accion=cargarParametros_tipo_choque', 'POST', 'JSON', 1);
				$('#frm_InstitucionDellate').val("");
				$('#frm_tipo_choque').empty();
				$('#frm_tipo_choque').append('<option value="">Seleccione</option>');
				for (var i=0; i<response.length; i++) {
					var selected="";
					if (response[i].tip_choque_id == $("#frm_tipo_choque_id").val())
						selected=" selected ";
					$('#frm_tipo_choque').append('<option value="' + response[i].tip_choque_id + '"'+selected+'>' + response[i].tip_choque_descripcion + '</option>');
				}
			}else{

				if($('#frm_transitoTipo option:selected').val()!=4){
					$("#frm_tipo_choque").val("");
					$("#divTipo_choque").hide("slow");
					$('#frm_tipo_choque_id').val("");
				}
			}
		});

		if($('#frm_tipoAccidente option:selected').val()==4){
			$("#DivHogar").show("slow");
			$("#divTipo_choque").hide("slow");
			$('#frm_tipo_choque option:selected').val(0);
			var cod  = $('#frm_tipoAccidente option:selected').val();
			var response = ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','codigoTipoAccidente='+cod+'&accion=cargarParametros2', 'POST', 'JSON', 1);
			$('#frm_InstitucionDellate').val("");
			$('#frm_hogar').empty();
			$('#frm_hogar').append('<option value="">Seleccione</option>');
			for (var i=0; i<response.length; i++) {
				var selected="";
				if (response[i].ins_id == $("#frm_produjoEn").val())
					selected=" selected ";
				$('#frm_hogar').append('<option value="' + response[i].ins_id + '"'+selected+'>' + response[i].ins_descripcion + '</option>');
			}
		}else{
			if($('#frm_tipoAccidente option:selected').val()!=4){
				$("#DivHogar").hide("slow");
				$('#frm_hogar option:selected').val(0);
				$('#frm_produjoEn').val("");
			}
		}

		if($('#frm_tipoAccidente option:selected').val()==5){
			$("#DivTransitoTipoManifestacion").show("slow");
			$("#divTipo_choque").hide("slow");
			$('#frm_tipo_choque option:selected').val(0);
			$("#DivLugarPublico").show("slow");
			var cod  = $('#frm_tipoAccidente option:selected').val();
			var response = ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','codigoTipoAccidente='+cod+'&accion=cargarParametros2', 'POST', 'JSON', 1);
			$('#frm_InstitucionDellate').val("");
			$('#frm_lugarPublico').empty();
			$('#frm_lugarPublico').append('<option value="">Seleccione</option>');
			for (var i=0; i<response.length; i++) {
				var selected="";
				if (response[i].ins_id == $("#frm_otroLugar").val())
					selected=" selected ";
				$('#frm_lugarPublico').append('<option value="' + response[i].ins_id + '"'+selected+'>' + response[i].ins_descripcion + '</option>');
			}
		}else{
			if($('#frm_tipoAccidente option:selected').val()!=5){
				$("#DivLugarPublico").hide("slow");
				$('#frm_lugarPublico option:selected').val(0);
				$('#frm_otroLugar').val("");
			}
		}
	});



	if($('#frm_motivoConsulta option:selected').val()==""){
		$("#frm_numero").val("");
		$("#frm_nombre2").val("");
		$( "#frm_vif" ).prop( "checked", false );
		$( "#frm_colision" ).prop( "checked", false );
		$( "#frm_volcamiento" ).prop( "checked", false );
	}



	if($('#frm_motivoConsulta option:selected').val()==1){
		$("#DivCampoMotivo").show("slow")
		$("#divTipoAccidente").show("slow")
		$("#DivMutualidad").hide("slow");


		var cod  = $('#frm_motivoConsulta option:selected').val();
		var response = ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','codigoAccidente='+cod+'&accion=cargarParametros', 'POST', 'JSON', 1);
		$('#frm_tipoAccidente').empty();
		$('#frm_tipoAccidente').append('<option value="">Seleccione</option>');
		$('#frm_institucion').append('<option value="">Seleccione</option>');
		for (var i=0; i<response.length; i++) {
			var selected="";
			if (response[i].sub_mot_id == $("#frm_tipoAccidenteDetalle").val())
				selected=" selected ";

			$('#frm_tipoAccidente').append('<option value="' + response[i].sub_mot_id + '"'+selected+'>' + response[i].sub_mot_descripcion + '</option>');
		}

		$('#frm_tipoAccidente').change();

	}



	if($('#frm_motivoConsulta option:selected').val()==2){

		$(".DivEnfermedadesRespiratorias").show(100);

		$("#DivCampoMotivo").show("slow")

	}



	if($('#frm_motivoConsulta option:selected').val()==3){
		$("#DivCampoMotivoAgresion").show("slow")
		$("#DivCampoMotivoAgresion2").show("slow")
		$("#frm_vif").show()
		$("#labelVIF").show()
		$("#DivCampoMotivo").show("slow")
		$("#DivCampoMotivoAgresionManifestaciones").show("slow");
		$("#DivCampoMotivoAgresionConstatacionLesiones").show("slow");
	}



	if($('#frm_motivoConsulta option:selected').val()==4){
		$("#DivCampoMotivoLesiones").show("slow")
		$("#DivCampoMotivo").show("slow")
		$("#DivCampoMotivoAgresionManifestaciones").show("slow");

	}



	if($('#frm_motivoConsulta option:selected').val()==5){
		$("#DivCampoMotivoAlcoholemia").show("slow")
		$("#DivCampoMotivo").show("slow")
	}



	if($('#frm_transitoTipo option:selected').val()==4){
		$("#divTipo_choque").show("slow");
		var cod  = $('#frm_transitoTipo option:selected').val()

		var response = ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','codigoTipoChoque='+cod+'&accion=cargarParametros_tipo_choque', 'POST', 'JSON', 1);
		$('#frm_InstitucionDellate').val("");
		$('#frm_tipo_choque').empty();
		$('#frm_tipo_choque').append('<option value="">Seleccione</option>');
		for (var i=0; i<response.length; i++) {
			var selected="";
			if (response[i].tip_choque_id == $("#frm_tipo_choque_id").val())
				selected=" selected ";
			$('#frm_tipo_choque').append('<option value="' + response[i].tip_choque_id + '"'+selected+'>' + response[i].tip_choque_descripcion + '</option>');
		}
	}else{

		if($('#frm_transitoTipo option:selected').val()!=4){
			$("#divTipo_choque").hide("slow");
			$('#frm_tipo_choque option:selected').val(0);
		}
	}



	$("#registrar_pacienteActualizar").click(function(){
		$.validity.start();
		if ( ! verificarDatosPacienteDerivado() ) {
			return;
		}
		if($('#frm_atencion option:selected').val()==""){
			$('#frm_atencion').assert(false,'Debe Seleccionar el Tipo de Atención');
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
		if($('#frm_motivoConsulta option:selected').val()==2 && $("#frm_motivo").val()==""){
			$('#frm_motivo').assert(false,'Debe ingresar un Motivo');
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
		if($('#frm_motivoConsulta option:selected').val()==1 && $('#frm_tipoAccidente option:selected').val()==3 && $('#frm_transitoTipo option:selected').val()==""){
			$('#frm_transitoTipo').assert(false,'Debe Seleccionar un tipo');
			$.validity.end();
			return false;
		}
		if($('#frm_motivoConsulta option:selected').val()==1 && $('#frm_tipoAccidente option:selected').val()==4 && $('#frm_hogar option:selected').val()==""){
			$('#frm_hogar').assert(false,'Debe Seleccionar un campo');
			$.validity.end();
			return false;
		}
		if($('#frm_motivoConsulta option:selected').val()==1 && $('#frm_tipoAccidente option:selected').val()==5 && $('#frm_lugarPublico option:selected').val()==""){
			$('#frm_lugarPublico').assert(false,'Debe Seleccionar un lugar');
			$.validity.end();
			return false;
		}
		result = $.validity.end();
		if(result.valid==false){
			return false;
		}
		var id = $("#FOLIO").val();
		var  actualizarPacienteAdmision = function(){
			camposDisable();
			result = $.validity.end();
			if(result.valid==false){
				return false;
			}
			var Actualizar = function(response){
				switch(response.status){
					case "success":
						var id = response.id;
						ajaxContent(raiz+'/views/modules/admision/busquedaAdmision.php','','#contenido','', true);
					break;
					case "error":   
						texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i>Error en el proceso </h4>  <hr>  <p class="mb-0">Error en la transacción, no se actualizo el Paciente, el siguiente error de sistema se ha desplegado:<br><br>'+response.message+'.</p></div>';
            			modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
					break;
					default:
                        ErrorSistemaDefecto();
					break;
				}
			}
			ajaxRequest(raiz+'/controllers/server/admision/main_controller.php',$("#frm_actualizar_pacienteDau").serialize()+'&accion=actualizarPacienteAdmision&id='+id, 'POST', 'JSON', 1,'Actualizando Paciente de la Admisión N°...'+id, Actualizar);
		}
		modalConfirmacionNuevo("Advertencia", "ATENCIÓN, se procedera a guardar los Examenes, <b>¿Desea continuar?</b>", "primary", actualizarPacienteAdmision);
	});



	function camposDisable(){
		$("#frm_direccion").prop('disabled', false);
		$("#frm_fechaNac").prop('disabled', false);
		$("#frm_rut").prop('disabled', false);
		$("#frm_previson").prop('disabled', false);
	}



	//FUNCIONES Y VARIABLES CON RESPECTO A REDES DE SALUD (PACIENTE DERIVADO O NO)
	let $clasePacienteEsDerivado 		= $(".pacienteEsDerivado"),
		$claseEstablecimientos      	= $(".establecimientosRedSalud"),
		$claseOtrosEstablecimientos		= $(".otrosEstablecimientos"),
		$pacienteDerivado           	= $("#slc_derivado"),
		$pacienteCritico                = $("#slc_pacienteCritico"),
		$establecimientoRedSalud  		= $("#frm_establecimientosRedSalud"),
		$otroEstablecimiento 			= $("#frm_nombreOtrosEstablecimientos");



	$clasePacienteEsDerivado.hide();



	$pacienteDerivado.on("change", function(){

		if ( $pacienteDerivado.val() == 'N' ) {

			$clasePacienteEsDerivado.find('select').val('');

			$clasePacienteEsDerivado.find('input').val('');

			$clasePacienteEsDerivado.hide(100);

			return;

		}

		$claseEstablecimientos.show(100);

		$claseEstablecimientos.trigger("change");

	});



	$claseEstablecimientos.on("change", () => {

		if ( $establecimientoRedSalud.val() == 35 || $establecimientoRedSalud.val() == 36 || $establecimientoRedSalud.val() == 37 ) {

			$otroEstablecimiento.val("");

			$claseOtrosEstablecimientos.show(100);

			return;

		}

		$claseOtrosEstablecimientos.hide(100);

	});



	$pacienteDerivado.val($("#hiddenPacienteDerivado").val());



	$pacienteCritico.val($("#hiddenPacienteCritico").val());



	if ( $("#hiddenPacienteDerivado").val() == 'S' ) {

		$pacienteDerivado.val($("#hiddenPacienteDerivado").val()).trigger("change");

		$establecimientoRedSalud.val($("#hiddenEstablecimientoRedSalud").val()).trigger("change");

		$otroEstablecimiento.val($("#hiddenOtrosEstablecimientos").val());

	}



	function verificarDatosPacienteDerivado ( ) {

		if ( $pacienteDerivado.val() == 'N' ) {

				if ( $pacienteCritico.val() == '' || $pacienteCritico.val() == 0 || $pacienteCritico.val() == undefined || $pacienteCritico.val() == null ) {

				$("#slc_pacienteCritico").assert(false, "Debe Seleccionar si Paciente es Crítico");

				return false;

			}

			return true;

		}

		if ( $pacienteDerivado.val() == '' || $pacienteDerivado.val() == 0 || $pacienteDerivado.val() == undefined || $pacienteDerivado.val() == null ) {

			$("#slc_derivado").assert(false, "Debe Seleccionar si Paciente es Derivado o No");

			return false;

		}

		if ( $establecimientoRedSalud.val() == '' || $establecimientoRedSalud.val() == undefined || $establecimientoRedSalud.val() == null) {

			$("#frm_establecimientosRedSalud").assert(false, "Debe Seleccionar Establecimiento Red de Salud");

			return false;

		}

		if ( $otroEstablecimiento.is(":visible") && ($otroEstablecimiento.val() == '' || $otroEstablecimiento.val() == 0 || $otroEstablecimiento.val() == undefined || $otroEstablecimiento.val() == null) ) {

			$("#frm_nombreOtrosEstablecimientos").assert(false, "Debe Ingresar Nombre Establecimiento");

			return false;

		}

		if ( $pacienteCritico.val() == '' || $pacienteCritico.val() == 0 || $pacienteCritico.val() == undefined || $pacienteCritico.val() == null ) {

			$("#slc_pacienteCritico").assert(false, "Debe Seleccionar si Paciente es Crítico");

			return false;

		}

		return true;

	}



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



	function desmarcarCheckBoxes ( ) {

		$('input[name="frm_manifestaciones"]').prop("checked", false);

		$('input[name="frm_constatacionLesiones"]').prop("checked", false);

	}



	(function(){

        const cambioSelectViajeOProcedencia = ( ) => {

            if ( viajeOProcedencia == null || String(viajeOProcedencia) === "" || String(viajeOProcedencia) === "N" ) {

                $paisEpidemiologia.val("");

			    $observacionEpidemiologica.val("");

            }

			if ( String($viajeOProcedenciaExtranjero.val()) === "S" ) {

				$(`${divPaisEpidemiologia}`).show(100);

				$(`${divObservacionEpidemiologica}`).show(100);

				return;

			}

			$(`${divPaisEpidemiologia}`).hide(100);

			$(`${divObservacionEpidemiologica}`).hide(100);

        }

        divViajeEpidemiologico       = "#divViajeEpidemiologico";
        divPaisEpidemiologia         = "#divPaisEpidemiologia";
        divObservacionEpidemiologica = "#divObservacionesEpidemiologia";
        viajeOProcedencia            = $("#viajeOProcedencia").val();
        pais                         = $("#pais").val();
        observaciones                = $("#observacion").val();
		$viajeOProcedenciaExtranjero = $("#frm_viajeEpidemiologico");
		$paisEpidemiologia   		 = $("#frm_paisEpidemiologia");
		$observacionEpidemiologica   = $("#frm_observacionEpidemiologica");

        $viajeOProcedenciaExtranjero.val((viajeOProcedencia == null || viajeOProcedencia == undefined || viajeOProcedencia == "") ? "" : viajeOProcedencia);
        $paisEpidemiologia.val((pais == null || pais == undefined || pais == "") ? "" : pais);
        $observacionEpidemiologica.val((observaciones == null || observaciones == undefined || observaciones == "") ? "Sin Obervaciones" : observaciones);

        cambioSelectViajeOProcedencia();

	})();

});