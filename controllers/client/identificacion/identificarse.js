$(document).ready(function () {
	//Validaciones de campos	
	validar("#inputUsuarioModalSinPistola", "rut");
	validar("#inputContraseniaModalSinPistola", "letras_numeros_caracteres");
	var parametros = '';
	var mensaje = '';
	//Rescate de variables
	var accessRequest = $('#accessRequest').val();
	//Formateo rut
	$("#inputUsuarioModalSinPistola").Rut({
		on_error: function () {
			return false;
		},
		on_success: function () {
		},
		format_on: 'keyup'
	});
	//Condiciones para lector código de barra
	$("#codigoBarra").delayPasteKeyUp(function () {
		$("#respuesta").append("Producto registrado: " + $("#codigoBarra").val() + "");
		$("#codigoBarra").val("");
	}, 150);
	f5($(document), true);
	$('#accesoPistola').on('shown.bs.modal', function () {
		$("#codigoBarra").focus();
		$("#codigoBarra").on('change', function () {
			if ($("#codigoBarra").val() != "") {
				$("#prueba").focus();
			}
		});
		$("#divIdentificacionSinPistola").on("click", function () {
			$("#codigoBarra").blur();
			$("#codigoBarra").val("");
		});
		$("#gifPistola").on("click", function () {
			$("#gifPistola").blur();
			$("#codigoBarra").focus();
			$("#codigoBarra").val("");
		});
		$("#divOtraSeccion").on("click", function () {
			$("#divOtraSeccion").blur();
			$("#codigoBarra").focus();
			$("#codigoBarra").val("");
		});
	})
	//Lector código de barra
	$("#frm_codigoBarra").keypress(function (event) {
		if ( teclaPresionadaEsEnter(event.keyCode) && usuarioYContraseniaNoEstanEnFoco() && codigoDeBarraIngresado() ) {
			let accionConPistola = true;
			verificarAccessRequest(accessRequest, accionConPistola);
		}
	});
	//Botón enter cuando se ingresa usuario y contraseña
	$('#divIdentificacionSinPistola').on('keypress', function(event) {
		if ( teclaPresionadaEsEnter(event.keyCode) ) {
			$('#btnIdentificacionModalSinPistola').click();
		}
	});
	//Botón identificación sin pistola
	$("#btnIdentificacionModalSinPistola").on('click', function () {
		if ( verificarRut() && verificarContrasenia() ) {
			let accionConPistola = false;
			verificarAccessRequest(accessRequest, accionConPistola);	
		}
	});
	/*
	**********************************************************************************************
										FUNCIONES ANEXAS
	**********************************************************************************************
	*/
	//Función validar acción de usuario
	function validarAccionUsuario(response) {
		switch (response.status) {
			case "success":
				if ( response.perfilUsuario.contadorPerfilMedico > 0 ) {
					$imagenPerfil = '<img class="imagenPaciente"  style="margin-top:12px; margin-right:5px; width: 30px;" src="../estandar/iconoPerfilesDau/perfilMedico.png"></img>';
					perfilUsuario = 'medico';
					$('#imagenPerfilUsuario').append($imagenPerfil);
				}	else if ( response.perfilUsuario.contadorPerfilTens > 0 ) {
					$imagenPerfil = '<img class="imagenPaciente"  style="margin-top:12px; margin-right:5px; width: 30px;" src="../estandar/iconoPerfilesDau/perfilEnfermero.png"></img>';
					perfilUsuario = 'tens';
					$('#imagenPerfilUsuario').html($imagenPerfil);
				}	else if ( response.perfilUsuario.contadorPerfilEnfermero > 0  ) {
					$imagenPerfil = '<img class="imagenPaciente"  style="margin-top:12px; margin-right:5px; width: 30px;" src="../estandar/iconoPerfilesDau/perfilEnfermero.png"></img>';
					perfilUsuario = 'enfermero';
					$('#imagenPerfilUsuario').html($imagenPerfil);
				}	else if ( response.perfilUsuario.contadorPerfilAdministrativo > 0  ) {
					perfilUsuario = 'administrativo';
				}	else if ( response.perfilUsuario.contadorPerfilFull > 0  ) {
					perfilUsuario = 'full';
				}
				identificador 		= true;
				$('#accesoPistola').modal('hide').data('bs.modal', null);
				tiempo 				= 'false';
				fn_global();
				document.getElementById('nombre1').innerHTML = response.nombre;
				nombreUsuario = response.nombre;
				$('#mensaje').html('');
				$('#imagenPerfilUsuario').html('');	
				$('#logueado2').html('');
				$('#mensaje').html('Tiempo Restante ');
				$('#logueado').remove();
			break;
			case "successTurnoCRUrgencia":
				$('#accesoPistola').modal('hide').data('bs.modal', null);
				$(`#${idDomProfesionalTurno}`).val(response.nombre);
				$(`#${idDomIdProfesionalTurno}`).val(response.id);
				fn_global();
			break;
			case "errorPermiso":
				mensaje = 'Usted no posee este permiso';
				desplegarError();
			break;
			case "errorUsuario":
				mensaje = 'Usted no existe como usuario';
				desplegarError();
			break;
			case "errorPerfil":
				mensaje = 'Usted no posee perfil requerido';
				desplegarError();
			break;
			default:
				mensaje = 'Error genérico';
				desplegarError();
			break;
		}
	}
	//Función verificar access request (tipo de llamada)
	function verificarAccessRequest(accessRequest, accionConPistola) {
		if (true === accionConPistola) {
			if ("finalizarSesion" != accessRequest  && "btn_cambioTurno" != accessRequest && "obtenerPerfilUsuario" != accessRequest && 'turnoCRUrgencia' != accessRequest ) {
				parametros = 'codigoBarra=' + $("#codigoBarra").val() + '&accessRequest=' + accessRequest + '&accion=validarPermiso';
			} else if ( "finalizarSesion" != accessRequest  && "btn_cambioTurno" != accessRequest && "obtenerPerfilUsuario" == accessRequest && 'turnoCRUrgencia' != accessRequest ) {
				parametros = 'codigoBarra=' + $("#codigoBarra").val() + '&accessRequest=' + accessRequest + '&accion=obtenerPerfilUsuario&verificacionConPistola=verdadero';
			}  else if ( "btn_cambioTurno" == accessRequest ) {
				if ( verificarRutMedicosDeEntregaTurnoConPistola ( ) ) {
					parametros = 'codigoBarra=' + $("#codigoBarra").val() + '&accessRequest=' + accessRequest + '&accion=validarPermiso';
				}
			} else if ( 'turnoCRUrgencia' == accessRequest ) {
				parametros = 'codigoBarra=' + $("#codigoBarra").val() + '&accion=verificarUsuarioConPistola(TurnoRCUrgencia)';
			} else {
				parametros = 'codigoBarra=' + $("#codigoBarra").val() + '&accion=verificarUsuarioConPistola';
			}
		} else if ( false === accionConPistola ) {
			let run      = formatearRut($('#inputUsuarioModalSinPistola').val());
			let password = $('#inputContraseniaModalSinPistola').val();
			if ( "finalizarSesion" != accessRequest  && "btn_cambioTurno" != accessRequest && "obtenerPerfilUsuario" != accessRequest && 'turnoCRUrgencia' != accessRequest ) {
				parametros = 'run=' + run + '&password=' + password + '&accion=validarPermisoSinPistola&accessRequest=' + accessRequest;
			} else if ( "finalizarSesion" != accessRequest && "btn_cambioTurno" != accessRequest && "obtenerPerfilUsuario" == accessRequest && 'turnoCRUrgencia' != accessRequest ) {
				parametros = 'run=' + run + '&password=' + password + '&accion=obtenerPerfilUsuario&verificacionConPistola=falso';
			} else if ( "btn_cambioTurno" == accessRequest ) {
				if ( verificarRutMedicosDeEntregaTurnoSinPistola ( ) ) {
					parametros = 'run=' + run + '&password=' + password + '&accion=validarPermisoSinPistola&accessRequest=' + accessRequest;
				}
			} else if ( 'turnoCRUrgencia' == accessRequest ) {
				parametros = 'run=' + run + '&password=' + password + '&accion=verificarUsuarioSinPistola(TurnoRCUrgencia)';
			} else {
				parametros = 'codigoBarra=' + $("#codigoBarra").val() + '&accion=verificarUsuarioSinPistola';
			}
		}
		ajaxRequest(raiz + '/controllers/server/identificacion/main_controller.php', parametros, 'POST', 'JSON', 1, 'Verificando USUARIO ...', validarAccionUsuario);
	}
	//Función para verificar si tecla presionada es enter
	function teclaPresionadaEsEnter (tecla) {
		if ( 13 === tecla) {
			return true;
		}
		return false;
	}
	//Función para verifivar si se ha leído el código de barras
	function codigoDeBarraIngresado() {
		var banderaError = true;
		if ( $("#codigoBarra").val() == "")  {
			$('#identificacionAlerta').empty('fast');
			$('#identificacionAlertaError').empty('fast');
			message("warning", "No se detecto Código de Barra", "", "#identificacionAlerta", "dangerMensaje", true, false);
			$("#codigoBarra").focus();
			banderaError = false;
		} 
		return banderaError;
	}
	//Función para verificar si usuario y contraseña no están en foco
	function usuarioYContraseniaNoEstanEnFoco () {
		if ( !$("#inputUsuarioModalSinPistola").is(":focus") && !$("#inputContraseniaModalSinPistola").is(":focus") ) {
			return true;
		}
		return false;
	}
	//Función formatear rut (quita puntos y digito verificador)
	function formatearRut ( run ) {
		var rutConFormato 	= run;
		rutSinFormato 		= $.Rut.quitarFormato(rutConFormato);
		rutSinFormato 		= rutSinFormato.substring(0, rutSinFormato.length - 1);
		run 				= rutSinFormato;
		return run;
	}
	//Función para verificar si se ha ingresado rut
	function verificarRut () {
		var run 			= $('#inputUsuarioModalSinPistola').val();
		var banderaError 	= true;
		$.validity.start();	
		if (run != "" && !$.Rut.validar(run) ) {
			$('#inputUsuarioModalSinPistola').assert(false, 'El Run Ingresado, no es valido');
			banderaError = false;
		} else if (run == "") {
			$("#inputUsuarioModalSinPistola").assert(false, 'Debe Ingresar Usuario');
			banderaError = false;
		}
		return banderaError;
	}
	//Función para verificar si se ha ingresado contraseña
	function verificarContrasenia () {
		var banderaError 	= true;
		$.validity.start();
		var password 		= $('#inputContraseniaModalSinPistola').val();
		if (password == "") {
			$("#inputContraseniaModalSinPistola").assert(false, 'Debe Ingresar Contraseña');
			banderaError = false;
		}
		return banderaError;
	}
	//Función que despliega error
	function desplegarError () {
		$('#identificacionAlertaError').empty('fast');
		$('#identificacionAlerta').empty('fast');
		message("warning", mensaje, "", "#identificacionAlertaError", "dangerMensaje", true, false);
		$("#codigoBarra").val("");
	}
	//Función que compara rut de médico tratante con rut de médico quien entrega turno (con pistola)
	function verificarRutMedicosDeEntregaTurnoConPistola ( ) {
		let codigoBarraMedicoTratante     = $("#codigoBarra").val()
		let codigoBarraMedicoEntregaTurno = $('#codigoBarraCambioTurno').val();
		let banderaEntregaTurno   		  = $('#banderaEntregaTurno').val();
		if ( ( codigoBarraMedicoTratante != codigoBarraMedicoEntregaTurno ) && banderaEntregaTurno == 'verdadero'  ) {
			ajaxRequest(raiz+'/controllers/server/rce/cambioTurno/main_controller.php', 'accion=sesionEntregaTurno', 'POST', 'JSON', 1);
			texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> ¡¡¡ ATENCIÓN !!! </h4>  <hr>  <p class="mb-0">El médico que entraga el turno, no es el mismo que el médico tratante, intentelo de nuevo.</p></div>';
            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
			$('#codigoBarra').val('');
			return false;
		} 
		return true;
	}
	//Función que compara rut de médico tratante con rut de médico quien entrega turno (sin pistola)
	function verificarRutMedicosDeEntregaTurnoSinPistola ( ) {
		let rutMedicoTratante     = formatearRut($('#inputUsuarioModalSinPistola').val());
		let rutMedicoEntregaTurno = $('#rutCambioTurno').val();
		let banderaEntregaTurno   = $('#banderaEntregaTurno').val();
		if ( ( rutMedicoTratante != rutMedicoEntregaTurno ) && banderaEntregaTurno == 'verdadero'  ) {
			ajaxRequest(raiz+'/controllers/server/rce/cambioTurno/main_controller.php', 'accion=sesionEntregaTurno', 'POST', 'JSON', 1);    
			texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> ¡¡¡ ATENCIÓN !!! </h4>  <hr>  <p class="mb-0">El médico que entraga el turno, no es el mismo que el médico tratante, intentelo de nuevo.</p></div>';
            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
			$('#inputUsuarioModalSinPistola').val('');
			$('#inputContraseniaModalSinPistola').val('');
			return false;
		} 
		return true;
	}
});