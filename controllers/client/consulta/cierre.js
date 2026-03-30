$(document).ready(function(){
	let derivacion 			= $('#frm_alta_derivacion').val();
	let var_frm_est_id 		= $('#frm_est_id').val();
	let idDau 				= $('#idDau').val();
	$('#contenidoIndicacion').hide();
	$('#contenidoTituloServicio').hide();
	$('#contenidoServicio').hide();
	$('#contenidoTituloDestino').hide();
	$('#frm_control').hide();
	$('#frm_especialidad_oculto').hide();
	$('#frm_aps_oculto').hide();
	$('#frm_otros_oculto').hide();
	$('#contenidoMotivoEgreso').hide();
	$('#contenidoFallecimiento').hide();
	if (var_frm_est_id == '4') {
		$('#contenidoIndicacion').show();
		$('#contenidoIndicacion').removeAttr( "hidden" );
		$('#frm_indicacion_egreso_h').val($('#frm_indicacion_egreso').val());
		$('#frm_indicacion_egreso').prop('disabled', 'disabled');
		$('#frm_destionos_h').val($('#frm_alta_derivacion').val());
		$('#frm_alta_derivacion').prop('disabled', 'disabled');
		$('#frm_especialidad_h').val($('#frm_especialidad').val());
		$('#frm_especialidad').prop('disabled', 'disabled');
		$('#frm_aps_h').val($('#frm_aps').val());
		$('#frm_aps').prop('disabled', 'disabled');
		$('#frm_otros_h').val($('#frm_otros').val());
		$('#frm_otros').prop('disabled', 'disabled');
		$('#frm_radio_defuncion').val($('#frm_destino_defuncion').val());
		$('#frm_destino_defuncion').prop('disabled', 'disabled');
		if ($('#frm_indicacion_egreso_h').val() == '3') {
			$('#contenidoTituloServicio').hide();
			$('#contenidoServicio').hide();
			$('#contenidoServicio').removeAttr( "hidden" );
			$('#frm_destionos_h').val($('#frm_alta_derivacion').val());
			$('#frm_destionos_h').prop('disabled', 'disabled');
			$('#frm_especialidad_h').val($('#frm_especialidad').val());
			$('#frm_especialidad_h').prop('disabled', 'disabled');
			$('#frm_aps_h').val($('#frm_aps').val());
			$('#frm_aps_h').prop('disabled', 'disabled');
			$('#frm_otros_h').val($('#frm_otros').val());
			$('#frm_otros_h').prop('disabled', 'disabled');
		}
		else if ($('#frm_indicacion_egreso_h').val() == '4') {
			$('#contenidoTituloServicio').show();
			$('#contenidoServicio').show();
			$('#contenidoServicio').removeAttr( "hidden" );
			$('#frm_servicio_h').val($('#frm_servicio').val());
			$('#frm_servicio').prop('disabled', 'disabled');
			$('#contenidoBrazalete').show();
		}
		else if($('#frm_indicacion_egreso_h').val() == '6'){
			$('#contenidoFallecimiento').show();
			$('#frm_fallecimiento_fecha_h').val($('#frm_fallecimiento_fecha').val());
			$('#frm_fallecimiento_fecha').prop('disabled', 'disabled');
			$('#contenidoBrazalete').show();
			$('#btn_genInfDEIS').hide();
		}
		$("#frm_motivo_egreso").attr("disabled", true);
		$("#frm_motivo_egreso").val("");
		$("#contenidoMotivoEgreso").hide("slow");
		}
		if($('#frm_indicacion_egreso').val() == 3){
			$("#frm_control").show();
			$("#contenidoTituloDestino").show();
			if(derivacion==2){
				$('#frm_especialidad_oculto').show();
			}else if(derivacion==3){
				$('#frm_aps_oculto').show();
			}else if(derivacion==5){
				$('#frm_otros_oculto').show();
			}
		}
		$("#frm_indicacion_egreso").change(function(){
			if($('#frm_indicacion_egreso option:selected').val()==3){
				$("#frm_control").show("slow");
				$('#frm_alta_derivacion').prop('selectedIndex',0);
            $('#frm_especialidad').prop('selectedIndex',0);
            $('#frm_destino_defuncion').prop('checked', false);
        }else{
        	if($('#frm_indicacion_egreso option:selected').val()!=3){
        		$('#frm_alta_derivacion').prop('selectedIndex',0);
        		$("#contenidoTituloDestino").hide("slow");
                $("#frm_control").hide("slow");
                $("#frm_especialidad_oculto").hide("slow");
                $('#frm_especialidad').prop('selectedIndex',0);
                $('#frm_otros_oculto').hide("slow");
                $('#frm_especialidad_oculto').hide("slow");
                $('#frm_aps_oculto').hide("slow");
                $('#frm_aps').prop('selectedIndex',0);
                $('#frm_otros').val("");
                $('#frm_destino_defuncion').prop('checked', false);
            }
        }
    });
	validar("#frm_peso"	,"numero_comas");
	validar("#frm_estatura","numero");
	validar("#frm_hora_atencion" ,"fecha");
	validar("#frm_nro","numero");
	validar("#horaAcoholemia" ,"fecha");
	validar("#frm_observacion_alcoholemia","letras_numeros");
	validar("#frm_motivo_egreso","letras_numeros");
	validar("#frm_fallecimiento_fecha","fecha");
	validar("#frm_fecha_egreso_adm","fecha");

	$('#frm_fallecimiento_fecha').datetimepicker({ locale:'es' });
	$('#frm_hora_atencion').datetimepicker({ dateFormat: '', timeFormat: 'hh:mm tt', timeOnly: true, pickDate: false  });
	$('#frm_fecha_egreso_adm').datetimepicker({  locale:'es'});

	validar("#frm_fecha_alcoholemia","fecha");
	validar("#frm_hora_alcoholemia", "numero");

	var atencion_fecha 	= $('#inpH_atencion_fecha').val();
    var atencion_hora  	= $('#inpH_atencion_hora').val();
    var horaActual  	= $('#inpH_horaActual').val();
	var FechaActual 	= $('#inpH_FechaActual').val();



	$('#frm_fecha_alcoholemia').datepicker({
		todayBtn: "linked",
		todayHighlight: true,
		autoclose: true,
		container: $("#date_fecha_alcoholemia"),
		format: 'dd/mm/yyyy',
		clearBtn: true,
		language: 'es',

	}).on('changeDate', function(e){
		$('#frm_fecha_alcoholemia').datepicker({
			autoclose: true
		})
	});



	$("#frm_fecha_alcoholemia").change(function(e){
		var fecha = $("#frm_fecha_alcoholemia").val();
		var hora  = $('#frm_hora_alcoholemia').val();
		if(fecha != ''){
			if($.datepicker.parseDate('dd/mm/yy', fecha) < $.datepicker.parseDate('dd/mm/yy', atencion_fecha)){
				$("#frm_fecha_alcoholemia").val(atencion_fecha);
				if(hora < atencion_hora){
					$('#frm_hora_alcoholemia').val(atencion_hora);
				}
			}
			else if($.datepicker.parseDate('dd/mm/yy', fecha) > $.datepicker.parseDate('dd/mm/yy', FechaActual)){
				$("#frm_fecha_alcoholemia").val(FechaActual);
				if(hora > horaActual){
					$('#frm_hora_alcoholemia').val(horaActual);
				}
			}
		}
	});

	$("#frm_hora_alcoholemia").keypress(function(e){
        if(e.keyCode == 13){
			verificarHorafecha(atencion_fecha, atencion_hora, horaActual, FechaActual);
        }
	});

	$("#frm_hora_alcoholemia").change(function(e){
		verificarHorafecha(atencion_fecha, atencion_hora, horaActual, FechaActual);
	});

	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
		$('[data-toggle="tooltip"]').on('shown.bs.tooltip', function () {
        	$('.tooltip').addClass('animated tada');
        })
	});

	$("#frm_indicacion_egreso").change(function(){
		if($('#frm_indicacion_egreso option:selected').val()==6){
			$("#contenidoFallecimiento").show("slow");
			$("#frm_fallecimiento_fecha").val("");
			$("#frm_fallecimiento_hora").val("");
		}else{
			if($('#frm_indicacion_egreso option:selected').val()!=6){
				$("#contenidoFallecimiento").hide("slow");
				$('#contenidoFallecimiento').prop('selectedIndex',0);
				$('.validity-tooltip').hide()
    		}
    	}
    	if($('#frm_indicacion_egreso option:selected').val()==3 || $('#frm_indicacion_egreso option:selected').val()==4){
    		$("#contenidoTituloServicio").show("slow");
    	}else if($('#frm_indicacion_egreso option:selected').val()!=3 && $('#frm_indicacion_egreso option:selected').val()!=4){
    		$("#contenidoTituloServicio").hide("slow");
    	}
    	if($('#frm_indicacion_egreso option:selected').val()==4){
    		$("#contenidoBrazalete").show("slow");
    		$("#contenidoServicio").show("slow");
    		$("#contenidoTituloServicio").show("slow");
    	}else{
    		if($('#frm_indicacion_egreso option:selected').val()!=4){
    			$("#contenidoBrazalete").hide("slow");
    			$('#contenidoBrazalete').prop('selectedIndex',0);
    			$("#contenidoServicio").hide("slow");
    			$("#frm_servicio").val("");
    			$("#frm_brazalette").val("")
    		}
    	}

	});
	$("#frm_alta_derivacion").change(function(){
    	var combo = $('#frm_alta_derivacion option:selected').val();
        if($('#frm_alta_derivacion option:selected').val()==1){
        	$('#frm_especialidad_oculto').hide("slow");
        	$('#frm_especialidad').prop('selectedIndex',0);
            $('#frm_aps_oculto').hide("slow");
            $('#frm_aps').prop('selectedIndex',0);
            $('#frm_otros_oculto').hide("slow");
            $('#frm_otros').val("");
        }else if($('#frm_alta_derivacion option:selected').val()==2){
        	$('#frm_especialidad_oculto').show("slow");
        	$('#frm_especialidad').prop('selectedIndex',0);
            $('#frm_otros_oculto').hide("slow");
            $('#frm_aps_oculto').hide("slow");
            $('#frm_aps').prop('selectedIndex',0);
            $('#frm_otros').val("");
        }else if($('#frm_alta_derivacion option:selected').val()==3){
        	$('#frm_aps_oculto').show("slow");
        	$('#frm_aps').prop('selectedIndex',0);
            $('#frm_especialidad_oculto').hide("slow");
            $('#frm_especialidad').prop('selectedIndex',0);
            $('#frm_otros_oculto').hide("slow");
            $('#frm_otros').val("");
        }else if($('#frm_alta_derivacion option:selected').val()==4){
        	$('#frm_especialidad_oculto').hide("slow");
        	$('#frm_especialidad').prop('selectedIndex',0);
            $('#frm_aps_oculto').hide("slow");
            $('#frm_aps').prop('selectedIndex',0);
            $('#frm_otros_oculto').hide("slow");
            $('#frm_otros').val("");
        }else if($('#frm_alta_derivacion option:selected').val()==5){
        	$('#frm_otros_oculto').show("slow");
        	$('#frm_especialidad_oculto').hide("slow");
        	$('#frm_especialidad').prop('selectedIndex',0);
            $('#frm_aps_oculto').hide("slow");
            $('#frm_aps').prop('selectedIndex',0);
            $('#frm_otros').val("");
        }
	});
	$('input:radio[name="radio_egreso"]').change(function(){
		$('.validity-tooltip').hide()
		if ($('#frm_egreso').is(':checked')) {
			if($('#frm_egreso').val()==5){
				$("#frm_motivo_egreso").attr("disabled", true);
				$("#frm_indicacion_egreso").attr("disabled", false);
				$("#frm_alta_derivacion").attr("disabled", false);
				$("#frm_especialidad").attr("disabled", false);
				$("frm_aps").attr("disabled",false);
				$("frm_otros").attr("disabled",false);
				$("#frm_motivo_egreso").val("");
				$("#contenidoIndicacion").show("slow");
				$("#contenidoMotivoEgreso").hide("slow");
				$("#frm_destino_defuncion").attr("disabled",false);
			}
		}
		var check_anula_mot = 0;
		if ($('#frm_anula').is(':checked')) {
			if($('#frm_anula').val()==6){
				$("#frm_motivo_egreso").attr("disabled", false);
				$("#frm_indicacion_egreso").val("");
				$("#frm_alta_derivacion").val("");
				$("#frm_especialidad").val("");
				$("frm_aps").val("");
				$("frm_otros").val("");
				$("#frm_brazalette").val("");
				$("#frm_servicio").val("");
				$("#frm_control").hide("slow");
				$("#frm_especialidad_oculto").hide("slow");
				$("#frm_aps_oculto").hide("slow");
				$("#frm_otros_oculto").hide("slow");
				$("#contenidoTituloDestino").hide("slow");
				$("#contenidoFallecimiento").hide("slow");
				$('#contenidoFallecimiento').prop('selectedIndex',0);
				$("#contenidoIndicacion").hide("slow");
				$("#contenidoServicio").hide("slow");
				$("#contenidoTituloServicio").hide("slow");
				$("#contenidoMotivoEgreso").show("slow");
				$("#contenidoBrazalete").hide("slow");
				check_anula_mot = 1;
				check_nea_mot = 0;
			}
		}
		$('#frm_anula').click(function(){
			if ($('#frm_anula').is(':checked') && check_anula_mot == 0) {
				if($('#frm_anula').val()==6){
					$("#frm_motivo_egreso").attr("disabled", false);
					$("#frm_indicacion_egreso").val("");
					$("#frm_alta_derivacion").val("");
					$("#frm_especialidad").val("");
					$("frm_aps").val("");
					$("frm_otros").val("");
					$("#frm_brazalette").val("");
					$("#frm_servicio").val("");
					$("#frm_control").hide("slow");
					$("#frm_especialidad_oculto").hide("slow");
					$("#frm_aps_oculto").hide("slow");
					$("#frm_otros_oculto").hide("slow");
					$("#contenidoTituloDestino").hide("slow");
					$("#contenidoFallecimiento").hide("slow");
					$('#contenidoFallecimiento').prop('selectedIndex',0);
					$("#contenidoIndicacion").hide("slow");
					$("#contenidoServicio").hide("slow");
					$("#contenidoTituloServicio").hide("slow");
					$("#contenidoMotivoEgreso").show("slow");
					$("#contenidoBrazalete").hide("slow");
					check_anula_mot = 1;
					check_nea_mot = 0;
				}
			}else{
				if (check_anula_mot == 1) {
					document.getElementById('frm_anula').checked = false;
					check_anula_mot = 0;
					check_nea_mot = 0;
					$("#contenidoMotivoEgreso").hide("slow");
					$("#frm_motivo_egreso").attr("disabled", true);
				}
			}
		});
		var check_nea_mot = 0;
		if ($('#frm_nea').is(':checked') && check_nea_mot == 0) {
			if($('#frm_nea').val()==7){
				$("#frm_motivo_egreso").attr("disabled", false);
				$("#frm_indicacion_egreso").attr("disabled", true);
				$("#frm_indicacion_egreso").val("");
				$("#frm_alta_derivacion").val("");
				$("#frm_especialidad").val("");
				$("frm_aps").val("");
				$("frm_otros").val("");
				$("#frm_brazalette").val("");
				$("#frm_control").hide("slow");
				$("#frm_especialidad_oculto").hide("slow");
				$("#frm_aps_oculto").hide("slow");
				$("#contenidoTituloDestino").hide("slow");
				$("#frm_otros_oculto").hide("slow");
				$("#contenidoFallecimiento").hide("slow");
				$('#contenidoFallecimiento').prop('selectedIndex',0);
				$("#contenidoIndicacion").hide("slow");
				$("#contenidoServicio").hide("slow");
				$("#contenidoTituloServicio").hide("slow");
				$("#contenidoMotivoEgreso").show("slow");
				$("#contenidoBrazalete").hide("slow");
				check_nea_mot = 1;
				check_anula_mot = 0;
			}
		}
		$('#frm_nea').click(function(){
			if ($('#frm_nea').is(':checked') && check_nea_mot == 0) {
				if($('#frm_nea').val()==7){
					$("#frm_motivo_egreso").attr("disabled", false);
					$("#frm_indicacion_egreso").attr("disabled", true);
					$("#frm_indicacion_egreso").val("");
					$("#frm_alta_derivacion").val("");
					$("#frm_especialidad").val("");
					$("frm_aps").val("");
					$("frm_otros").val("");
					$("#frm_brazalette").val("");
					$("#frm_control").hide("slow");
					$("#frm_especialidad_oculto").hide("slow");
					$("#frm_aps_oculto").hide("slow");
					$("#contenidoTituloDestino").hide("slow");
					$("#frm_otros_oculto").hide("slow");
					$("#contenidoFallecimiento").hide("slow");
					$('#contenidoFallecimiento').prop('selectedIndex',0);
					$("#contenidoIndicacion").hide("slow");
					$("#contenidoServicio").hide("slow");
					$("#contenidoTituloServicio").hide("slow");
					$("#contenidoMotivoEgreso").show("slow");
					$("#contenidoBrazalete").hide("slow");
					check_nea_mot = 1;
					check_anula_mot = 0;
				}
			}else{
				if (check_nea_mot == 1) {
					document.getElementById('frm_nea').checked = false;
					check_nea_mot = 0;
					$("#contenidoMotivoEgreso").hide("slow");
					$("#frm_motivo_egreso").attr("disabled", true);
				}
			}
		});
	});
	$(".generaBrazaleteBtn").click(function(){
		$.validity.start();
		$("#frm_brazalette").require('Debe Seleccionar una opcion');
		brazalete= $('#frm_brazalette').val();
		result = $.validity.end();
		if(result.valid==false){
			return false;
		}
		modalFormulario_noCabecera('', raiz+'/views/modules/consulta/salida/brazalete/brazaletePDF_adulto.php',$("#frm_cierre").serialize()+'&idDau='+idDau, "#brazaleteAdulto", "modal-lg", "", "fas fa-plus");
	});
	$(".generaInformeDEIS").click(function(){
		$('.tooltip').tooltip('hide')
		$('.validity-tooltip').hide()
		var Iddau = $(this).attr('id');
		window.open('http://10.6.21.19/solicitudHosp/informe_estadistico.php?&idDau='+idDau+'&desde=dau&frm_id_paciente='+$('#id_paciente').val(),'','toolbar=0, location=0, directories=0, status=0, menubar=0, scrollbars=1, resizable=1, left=0, top=0, height=700, width=1000');
	});
	$('#btnCerrarDAU').on('click', function(){
		$.validity.start();
		if($('#frm_nro').val()!="" ||  $('#frm_profesional_alcoholemia').val()!="" || $('#positivo').is(':checked') || $('#negativo').is(':checked') || $('#frm_fecha_alcoholemia').val() != "" || $('#frm_hora_alcoholemia').val() != ""){
			$("#frm_nro").require('Debe ingresar numero');
			$('#resultado').require('Debe seleccionar una opcion');
			$("#frm_profesional_alcoholemia").require('Debe Seleccionar una Opción');
			if($('#frm_fecha_alcoholemia').val() == ""){
				$('#frm_fecha_alcoholemia').assert(false,'Debe ingresar fecha')
			}
			if($('#frm_hora_alcoholemia').val() == ""){
				$('#frm_hora_alcoholemia').assert(false,'Debe ingresar hora')
			}
		}
		if($('#frm_hora_alcoholemia').val() != "" && $('#frm_fecha_alcoholemia').val() != ""){
			hora = $('#frm_hora_alcoholemia').val();
			fecha = $('#frm_fecha_alcoholemia').val();
			var fechaCorregida = fecha.split("/").reverse().join("-")
			horaAlcoholemia = fechaCorregida + " " + hora;
			$('#horaAcoholemia').val(horaAlcoholemia);
		}
		$("#frm_fecha_egreso_adm").require("Seleccione una fecha");
		if ($('input[name="radio_egreso"]').is(':checked')){
			if ($('#frm_egreso').is(':checked')){
				if($('#frm_egreso').val()==5){
					$("#frm_indicacion_egreso").require('Debe Seleccionar una Opción');
					if($('#frm_indicacion_egreso option:selected').val()==6){
						$("#frm_fallecimiento_fecha").require('Debe Seleccionar fecha');
					}else{
						$("#frm_fallecimiento_fecha").val("");
					}
					if($('#frm_indicacion_egreso option:selected').val()==4){
						$("#frm_servicio").require('Debe Seleccionar una opcion');
						$("#frm_brazalette").require('Debe Seleccionar una opcion');
					}else{
						$("#frm_brazalette").val("");
					}
				}
			}
			if ($('#frm_anula').is(':checked')) {
				if($('#frm_anula').val()==6){
					$("#frm_motivo_egreso").require('Debe ingresar motivo');
				}
			}
			if ($('#frm_nea').is(':checked')) {
				if($('#frm_nea').val()==7){
					$("#frm_motivo_egreso").require('Debe ingresar motivo');
				}
			}
		}
		if($('#frm_indicacion_egreso option:selected').val()=="" && $('#radio_egreso').val()==5){
			$('#frm_indicacion_egreso').require('Debe Seleccionar indicación de egreso');
			$.validity.end();
			return false;
		}else if($('#frm_indicacion_egreso option:selected').val()==3){
			if($('#frm_alta_derivacion option:selected').val()=="0"){
				$('#frm_alta_derivacion').assert(false,'Debe Seleccionar alguna derivacion');
			}else if($('#frm_alta_derivacion option:selected').val()==2){
				if($('#frm_especialidad option:selected').val()=="0"){
					$('#frm_especialidad').assert(false,'Debe Seleccionar alguna especialidad');
				}
			}else if($('#frm_alta_derivacion option:selected').val()==3){
				if($('#frm_aps option:selected').val()=="0"){
					$('#frm_aps').assert(false,'Debe Seleccionar algun APS');
				}
			}else if($('#frm_alta_derivacion option:selected').val()==5){
				if($('#frm_otros').val()==""){
					$('#frm_otros').assert(false,'Debe indicar alguna informacion');
				}
			}
		}else if($('#frm_indicacion_egreso option:selected').val()==4){
			if($('#frm_servicio_destino option:selected').val()=="0"){
				$('#frm_servicio_destino').assert(false,'Debe Seleccionar algun destino');
			}
		}else if($('#frm_indicacion_egreso option:selected').val()==6){
			if($('#frm_fecha_defuncion').val()==""){
				$('#frm_fecha_defuncion').require("debe ingresar fecha de defuncion");
			}else if($("input[name='frm_destino_defuncion']:checked").length<=0){
				$('#frm_destino_defuncion').assert(false,'Debe Seleccionar alguna opcion');
			}
		}
		result = $.validity.end();
		if(result.valid==false){
			return false;
		}

		var funcion = function miFuncion(){
			var grabar = function(response){
				switch(response.status){
					case "success":
						var pass = false;
						if (response.tipo_Egreso == '4' && pass == true) {
							fn_consPacATrans = function(response){
								switch(response.status){
									case 'success':
										texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-check-double throb2 text-success" style="font-size:29px"></i> ATENCIÓN </h4>  <hr>  <p class="mb-0">'+response.message+'.</p></div>';
                						modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
										$('#cierreDAU').modal( 'hide' ).data( 'bs.modal', null );
										ajaxContent(raiz+'/views/modules/consulta/consulta.php','','#contenido','Cargando...', true);
									break;
									case 'error':
										texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> ERROR! </h4>  <hr>  <p class="mb-0">'+response.message+'.</p></div>';
	            						modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
										ajaxContent(raiz+'/views/modules/consulta/consulta.php','','#contenido','Cargando...', true);
									break;
									default:
										ErrorSistemaDefecto();
										$('#cierreDAU').modal( 'hide' ).data( 'bs.modal', null );
										ajaxContent(raiz+'/views/modules/consulta/consulta.php','','#contenido','Cargando...', true);
									break;
								}
							}
							ajaxRequest(raiz+'/controllers/server/consulta/main_controller.php','dau_id='+idDau+'&accion=cd_enviarPacienteATransito', 'POST', 'JSON', 1,'Enviando Paciente a Transito...', fn_consPacATrans);
						}
						else{
							$('#cierreDAU').modal( 'hide' ).data( 'bs.modal', null );
							ajaxContent(raiz+'/views/modules/consulta/consulta.php','','#contenido','Cargando...', true);
						}
					break;

					case "error":  
						texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en el proceso </h4>  <hr>  <p class="mb-0">Error en la transacción, no se cerro el DAU<br><br>'+response.message+'.</p></div>';
            			modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
					break;
					default: 
                        ErrorSistemaDefecto();
					break;
				}
			}
			var response =ajaxRequest(raiz+'/controllers/server/consulta/main_controller.php',$("#frm_cierre").serialize()+'&Iddau='+idDau+'&accion=cerrarDAU', 'POST', 'JSON', 1,'Cerrando DAU...', grabar);
		}
		modalConfirmacionNuevo("Advertencia", "ATENCIÓN, se procedera a Cerrar Dau, <b>¿Desea continuar?</b>", "primary", funcion);
	});



	function verificarHorafecha(atencion_fecha, atencion_hora, horaActual, FechaActual){
		cambiarFormaDigitacionHora('frm_hora_alcoholemia');

		var fecha         = $('#frm_fecha_alcoholemia').val();
		var hora          = $('#frm_hora_alcoholemia').val();

		if(fecha == FechaActual && hora > horaActual ){
			$("#frm_hora_alcoholemia").val(horaActual);
		}
		else if(fecha == atencion_fecha && hora < atencion_hora){
			$("#frm_hora_alcoholemia").val(atencion_hora);
		}
	}

});