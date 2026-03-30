$(document).ready(function(){
	let atencion_fecha 	= $('#inpH_atencion_fecha').val();
    	atencion_hora  	= $('#inpH_atencion_hora').val(),
    	horaActual  	= $('#inpH_horaActual').val(),
		FechaActual 	= $('#inpH_FechaActual').val(),
		idDau 			= $('#idDau').val();
	validar("#frm_peso"	,"numero_comas");
	validar("#frm_estatura","numero");
	validar("#frm_hora_atencion" ,"fecha");
	validar("#frm_nro","numero");
	validar("#horaAcoholemia" ,"fecha");
	validar("#frm_observacion_alcoholemia","letras_numeros");
	validar("#frm_motivo_egreso","letras_numeros");
	validar("#frm_fallecimiento_fecha","fecha");
	validar("#frm_fecha_alcoholemia","fecha");
	validar("#frm_hora_alcoholemia", "numero");
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
		});
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
            cambiarFormaDigitacionHora('frm_hora_alcoholemia');

            var fecha         = $('#frm_fecha_alcoholemia').val();
            var hora          = $('#frm_hora_alcoholemia').val();

            if(fecha == FechaActual && hora > horaActual ){
            $("#frm_hora_alcoholemia").val(horaActual);
            }else if(fecha == atencion_fecha && hora < atencion_hora){
                $("#frm_hora_alcoholemia").val(atencion_hora);
            }
        }
	});
	$("#frm_hora_alcoholemia").change(function(e){
		cambiarFormaDigitacionHora('frm_hora_alcoholemia');
		var fecha         = $('#frm_fecha_alcoholemia').val();
		var hora          = $('#frm_hora_alcoholemia').val();
		if(fecha == FechaActual && hora > horaActual ){
		$("#frm_hora_alcoholemia").val(horaActual);
		}else if(fecha == fechaSala && hora < horaSala){
			$("#frm_hora_alcoholemia").val(horaSala);
		}
	});
	$('#horaAcoholemia').datetimepicker({locale:'es'});
    $('#frm_fallecimiento_fecha').datetimepicker({ locale:'es' });
	$('#frm_hora_atencion').datetimepicker({ dateFormat: '', timeFormat: 'hh:mm tt', timeOnly: true, pickDate: false  });
	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
		$('[data-toggle="tooltip"]').on('shown.bs.tooltip', function () {
        	$('.tooltip').addClass('animated tada');
        })
	});
	$("#frm_egreso").prop('disabled', true);
	$("#frm_anula").prop('disabled', true);
	$("#frm_nea").prop('disabled', true);
	if ($('#frm_egreso').is(':checked')) {
		if($('#frm_egreso').val()==5){
			$("#frm_motivo_egreso").hide();
		}
	}
	if ($('#frm_anula').is(':checked')) {
		if($('#frm_anula').val()==6){
			$("#frm_indicacion_egreso").hide();
		}
	}
	if ($('#frm_nea').is(':checked')) {
		if($('#frm_nea').val()==7){
			$("#frm_indicacion_egreso").hide();
		}
	}
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
	$('#btnVerCierreDAU').on('click', function(){
		$.validity.start();
		if($('#frm_nro').val()!="" || $('#frm_profesional_alcoholemia').val()!="" || $('#positivo').is(':checked') || $('#negativo').is(':checked') || $('#frm_fecha_alcoholemia').val() != "" || $('#frm_hora_alcoholemia').val() != ""){
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
		if($('#frm_hora_alcoholemia').val() != "" && $('#frm_fecha_alcoholemia').val()){
			hora = $('#frm_hora_alcoholemia').val();
			fecha = $('#frm_fecha_alcoholemia').val();
			horaAlcoholemia = fecha + " " + hora;
			var fechaCorregida = fecha.split("/").reverse().join("-")
			horaAlcoholemia = fechaCorregida + " " + hora;
			$('#horaAcoholemia').val(horaAlcoholemia);
		}
		result = $.validity.end();
		if(result.valid==false){
			return false;
		}
		var funcion = function miFuncion(){
			var grabar = function(response){
				switch(response.status){
					case "success":
						$('#verCierreDAU').modal( 'hide' ).data( 'bs.modal', null );
						ajaxContent(raiz+'/views/modules/consulta/consulta.php','','#contenido','Cargando...', true);
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
			var response = ajaxRequest(raiz+'/controllers/server/consulta/main_controller.php',$("#frm_cierre").serialize()+'&Iddau='+idDau+'&accion=actualizarDatosDauCerrado'+'&dau_mov_descripcion=actualizarDau', 'POST', 'JSON', 1,'Actualizando Datos...', grabar);
		}
		modalConfirmacionNuevo("Advertencia", "ATENCIÓN, se procedera a Actualizar los datos, <b>¿Desea continuar?</b>", "primary", funcion);
	});
 });