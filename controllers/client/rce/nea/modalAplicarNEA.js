function manejarClickLlamado(tipoLlamado, chkId, textoFechaHoraId,bdllamado,usuarioLlamada) {
    if ($('#' + chkId).prop('checked')) {	
    	numeroLlamado 	 = tipoLlamado;
		idCheckBox    	 = chkId;
		idTextoFechaHora = textoFechaHoraId;
		// if(bdllamado == 'fechaTercerLlamado'){
		// 	$('#btnGuardarApliNEA').trigger('click');
		// }
		var  ingresarLlamado = function(){
			var grabarLlamado = function(response){
				$("#frm_prevision").prop('disabled', false);
				switch(response.status){
					case "success":
						if (bdllamado == 'fechaTercerLlamado') {

						    var  registrarLlamado = function(){
								var respuestaAjaxRequest = ajaxRequest(raiz+'/controllers/server/mapa_piso_full/main_controller.php', $("#frm_aplicar_nea").serialize()+'&accion=pacienteYaConNEA', 'POST', 'JSON', 1, '¿Paciente Ya con NEA?...');
								if(respuestaAjaxRequest.status == 'success'){
									texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> ATENCIÓN </h4>  <hr>  <p class="mb-0 text-center">Paciente ya se encuentra con estado aplicado de N.E.A. Se recargó nuevamente el sistema para que visualice los cambios.</p></div>';
									modalMensajNoCabecera('Exito',texto,  "#modal", "modal-md", "success");
									if(tipoMapa == ""){
										ajaxContent(`${raiz}/views/modules/consulta/consulta.php`,'','#contenido','', true);
									}else{
										ajaxContent(raiz+'/views/modules/mapa_piso_full/mapa_piso_full.php','tipoMapa='+tipoMapa,'#contenido','', true);
									}
									// ajaxContent(raiz+'/views/modules/mapa_piso_full/mapa_piso_full.php','tipoMapa='+tipoMapa,'#contenido','', true);
									
								}else{
									var respuestaAjaxRequest = ajaxRequest(raiz+'/controllers/server/mapa_piso_full/main_controller.php', $("#frm_aplicar_nea").serialize()+'&accion=pacienteTieneAltaUrgencia', 'POST', 'JSON', 1, 'Cargando...');
									if(respuestaAjaxRequest.status == 'success'){
										texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> ATENCIÓN </h4>  <hr>  <p class="mb-0 text-center">Paciente ya se encuentra con indicación de alta, anular primero la indicación de alta para aplicar N.E.A..</p></div>';
										modalMensajNoCabecera('Exito',texto,  "#modal", "modal-md", "success");
										// ajaxContent(raiz+'/views/modules/mapa_piso_full/mapa_piso_full.php','tipoMapa='+tipoMapa,'#contenido','', true);
									}else{
										banderaAdministrativo = 'N';
										var respuestaAjaxRequest = ajaxRequest(raiz+'/controllers/server/mapa_piso_full/main_controller.php', $("#frm_aplicar_nea").serialize()+'&accion=aplicarNEAClinico&est_id=7&banderaAdministrativo='+banderaAdministrativo, 'POST', 'JSON', 1, 'Cargando...');
										if(respuestaAjaxRequest.status == 'success'){
											if(tipoMapa == ""){
												ajaxContent(`${raiz}/views/modules/consulta/consulta.php`,'','#contenido','', true);
											}else{
												ajaxContent(raiz+'/views/modules/mapa_piso_full/mapa_piso_full.php','tipoMapa='+tipoMapa,'#contenido','', true);
											}
										}else{
											modalMensajNoCabecera('Error Defecto','',  "#modal", "modal-md", "success");
										}
										$('#modalNEA').modal( 'hide' ).data( 'bs.modal', null );
										$('#modalDetalleCategorizacion').modal( 'hide' ).data( 'bs.modal', null );
									}
									console.log(respuestaAjaxRequest)
								}
							}
							registrarLlamado();
						}
						$(`#${idTextoFechaHora}`).empty();
						$(`#${idTextoFechaHora}`).append(`(${response.fechaHora})`);

						texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-edit mr-2" style="color: #5a8dc5; font-size: 26px"></i>Éxito. </h4><hr><p>Se ha ingresado el llamado de forma exitosa.</p> </div>';
						modalMensajNoCabecera('Exito',texto,  "#modal", "modal-md", "success");
					break;
					default:        
						modalMensajNoCabecera('Error Defecto','',  "#modal", "modal-md", "success");
					break;
				}
				if ( idCheckBox == 'chk_primerLlamado') {
					$(`#${idCheckBox}`).attr("disabled", true);
					$(`#${idCheckBox}`).attr("checked", true);
					$('#chk_segundoLlamado').attr("disabled", false);
					$('#chk_tercerLlamado').attr("disabled", true);
				} 
				if ( idCheckBox == 'chk_segundoLlamado') {
					$(`#${idCheckBox}`).attr("disabled", true);
					$(`#${idCheckBox}`).attr("checked", true);
					$('#chk_primerLlamado').attr("disabled", true);
					$('#chk_tercerLlamado').attr("disabled", false);
				}
				if ( idCheckBox == 'chk_tercerLlamado') {

					$(`#${idCheckBox}`).attr("disabled", true);
					$(`#${idCheckBox}`).attr("checked", true);
					$('#chk_primerLlamado').attr("disabled", true);
					$('#chk_tercerLlamado').attr("disabled", true);
				}
	        	// $('#modal_admision').modal( 'hide' ).data( 'bs.modal', null );
	        };
			ajaxRequest(raiz+'/controllers/server/mapa_piso_full/main_controller.php',$("#frm_aplicar_nea").serialize()+'&accion=ingresarLlamado&bdllamado='+bdllamado+'&usuarioLlamada='+usuarioLlamada, 'POST', 'JSON', 1,'Guardando...', grabarLlamado);
		}

			modalConfirmacionNuevo("Advertencia", "Se ingresará el Llamado (Acción no reversible), <b>¿Desea continuar?</b>","primary", ingresarLlamado);


		// texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> ATENCIÓN </h4>  <hr>  <p class="mb-0 text-center">Se ingresará el Llamado (Acción no reversible), <b>¿Desea continuar?</b></p></div>';
    	// modalConfirmacion("<label class='mifuente'>Advertencia</label>", texto, "primary", ingresarLlamado);
    }
}
function limita_TxtArea(elEvento, maximoCaracteres) {
	let elemento = document.getElementById("frm_txt_apliNEA");
	let evento = elEvento || window.event;
	let codigoCaracter = evento.charCode || evento.keyCode;

	if ( codigoCaracter == 37 || codigoCaracter == 39 ) {
		return true;
	}
	if ( codigoCaracter == 8 || codigoCaracter == 46 ) {
		return true;
	} else if ( elemento.value.length >= maximoCaracteres ) {
		return false;
	} else {
		return true;
	}
}
function actualizaInfoDiagnostico(maximoCaracteres) {
	let elemento = document.getElementById('frm_txt_apliNEA');
	let info     = document.getElementById('infoMotApliNEA');
	if ( elemento.value.length >= maximoCaracteres || elemento.value.length == 0 ) {
		info.innerHTML = 'Máximo '+maximoCaracteres+' caracteres';
	} else {
		info.innerHTML = 'Puedes escribir hasta '+(maximoCaracteres-elemento.value.length)+' caracteres adicionales';
	}
}
$(document).ready(function(){
	validar("#frm_fecha_aNEA","fecha");
	let idDau 		  		= $('#idDau').val();
	let tipoMapa 		  	= $('#tipoMapa').val();
	let idCheckBox	  		= '';
	let idTextoFechaHora	= '';
	let numeroLlamado 		= '';
	let fechaAdmision 		= $('#inpH_fechaAdmision').val(), 
		fechaAplicarNEA 	= $('#inpH_fechaAplicarNEA').val(); 
	let horaAdmision 		= $('#inpH_horaAdmision').val(),
		horaAplicarNEA 		= $('#inpH_horaAplicarNEA').val();

	$("#frm_hora_aNEA").attr({
		"max" : horaAplicarNEA,
		"min" : '00:00'
	});
	$("#frm_fecha_aNEA").change(function(){
		let fecha = $('#frm_fecha_aNEA').val();
		if (fecha >  fechaAdmision && fecha < fechaAplicarNEA) {
			$("#frm_hora_aNEA").attr({
				"max" : '23:59',
				"min" : '00:00'
			});	
		} else if ( fecha == fechaAdmision ) {
			$("#frm_hora_aNEA").val(horaAdmision);
			if (fechaAdmision == fechaAplicarNEA) {
				$("#frm_hora_aNEA").attr({
					"max" : horaAplicarNEA,
					"min" : horaAdmision
				});	
			} else {
				$("#frm_hora_aNEA").attr({	
					"max" : '23:59',
					"min" : horaAdmision
				});	
			}
		} else if ( fecha == fechaAplicarNEA ) {
			$("#frm_hora_aNEA").val(horaAplicarNEA);
			$("#frm_hora_aNEA").attr({
				"max" : horaAplicarNEA,
				"min" : '00:00'
			});
		}
	});
	$("#frm_hora_aNEA").keypress(function(e){
		if(e.keyCode == 13){
			cambiarFormaDigitacionHora('frm_hora_aNEA');
			let fechaH = $('#frm_fecha_aNEA').val(); 
			let hora   = $('#frm_hora_aNEA').val();
			if ( fechaH == fechaAplicarNEA && hora > horaAplicarNEA ) {
				$("#frm_hora_aNEA").val(horaAplicarNEA);
			} else if ( fechaH == fechaAdmision && hora < horaAdmision ) {
				$("#frm_hora_aNEA").val(horaAdmision);
			} 
		}
	});
	$("#frm_hora_aNEA").change(function(e){
		cambiarFormaDigitacionHora('frm_hora_aNEA');
		let fecha  = $('#frm_fecha_date').val(); 
		let hora   = $('#frm_hora_aNEA').val();
		if ( fecha == FechaActual && hora > horaActual ) {
			$("#frm_hora_aNEA").val(horaActual); 
		} else if ( fecha == fechaSala && hora < horaSala ) {
			$("#frm_hora_aNEA").val(horaSala);            
		}
	});
	$('#btnGuardarApliNEA').on('click', function ( ) {
		$.validity.start();
		if( $('#frm_fecha_aNEA').val() == "" ) {
			$('#frm_fecha_aNEA').assert(false,'Debe Indicar la fecha de egreso');
			$.validity.end();
			return false;
		}
		if( $('#frm_hora_aNEA').val() == "" ) {
			$('#frm_hora_aNEA').assert(false,'Debe Indicar la hora de egreso');
			$.validity.end();
			return false;
		}
		if( $('#frm_txt_apliNEA').val() == "" ) {
			$('#frm_txt_apliNEA').assert(false,'Debe Indicar observación de egreso');
			$.validity.end();
			return false;
		}
		result = $.validity.end();
		if( result.valid == false ) {
			return false;
	
		}
		var  registrarLlamado = function(){
			var respuestaAjaxRequest = ajaxRequest(raiz+'/controllers/server/mapa_piso_full/main_controller.php', $("#frm_aplicar_nea").serialize()+'&accion=pacienteYaConNEA', 'POST', 'JSON', 1, '¿Paciente Ya con NEA?...');
			if(respuestaAjaxRequest.status == 'success'){
				texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> ATENCIÓN </h4>  <hr>  <p class="mb-0 text-center">Paciente ya se encuentra con estado aplicado de N.E.A. Se recargó nuevamente el sistema para que visualice los cambios.</p></div>';
				modalMensajNoCabecera('Exito',texto,  "#modal", "modal-md", "success");
				if(tipoMapa == ""){
					ajaxContent(`${raiz}/views/modules/consulta/consulta.php`,'','#contenido','', true);
				}else{
					ajaxContent(raiz+'/views/modules/mapa_piso_full/mapa_piso_full.php','tipoMapa='+tipoMapa,'#contenido','', true);
				}
				// ajaxContent(raiz+'/views/modules/mapa_piso_full/mapa_piso_full.php','tipoMapa='+tipoMapa,'#contenido','', true);
				
			}else{
				var respuestaAjaxRequest = ajaxRequest(raiz+'/controllers/server/mapa_piso_full/main_controller.php', $("#frm_aplicar_nea").serialize()+'&accion=pacienteTieneAltaUrgencia', 'POST', 'JSON', 1, 'Cargando...');
				if(respuestaAjaxRequest.status == 'success'){
					texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> ATENCIÓN </h4>  <hr>  <p class="mb-0 text-center">Paciente ya se encuentra con indicación de alta, anular primero la indicación de alta para aplicar N.E.A..</p></div>';
					modalMensajNoCabecera('Exito',texto,  "#modal", "modal-md", "success");
					// ajaxContent(raiz+'/views/modules/mapa_piso_full/mapa_piso_full.php','tipoMapa='+tipoMapa,'#contenido','', true);
				}else{
					banderaAdministrativo = 'N';
					var respuestaAjaxRequest = ajaxRequest(raiz+'/controllers/server/mapa_piso_full/main_controller.php', $("#frm_aplicar_nea").serialize()+'&accion=aplicarNEAClinico&est_id=7&banderaAdministrativo='+banderaAdministrativo, 'POST', 'JSON', 1, 'Cargando...');
					if(respuestaAjaxRequest.status == 'success'){
						if(tipoMapa == ""){
							ajaxContent(`${raiz}/views/modules/consulta/consulta.php`,'','#contenido','', true);
						}else{
							ajaxContent(raiz+'/views/modules/mapa_piso_full/mapa_piso_full.php','tipoMapa='+tipoMapa,'#contenido','', true);
						}
					}else{
						modalMensajNoCabecera('Error Defecto','',  "#modal", "modal-md", "success");
					}
					$('#modalNEA').modal( 'hide' ).data( 'bs.modal', null );
					$('#modalDetalleCategorizacion').modal( 'hide' ).data( 'bs.modal', null );
				}
				console.log(respuestaAjaxRequest)
			}
		}
		modalConfirmacionNuevo("Advertencia", "Se procederá a CERRAR el DAU como N.E.A., <b>¿Desea continuar?</b>","primary", registrarLlamado);

			
		// texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> ATENCIÓN </h4>  <hr>  <p class="mb-0 text-center">Se procederá a CERRAR el DAU como N.E.A., <b>¿Desea continuar?</b></p></div>';
    	// modalConfirmacion("<label class='mifuente'>Advertencia</label>", texto, "primary", registrarLlamado);
	});
	$("#frm_aplicar_nea").keypress(function(e) {
		if ( e.which == 13 ) {
			return false;
		}
	});
});