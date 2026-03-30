$(document).ready(function(){

	$btnExcelRegistroHospitalizacion = $("#excelRegistroHospitalizacion");
	$btnExcelRegistroAtencionDiaria = $("#excelRegistroAtencionDiaria");

	validar("#frm_fecha_admision_desde"             ,"fecha");
	validar("#frm_fecha_admision_hasta"             ,"fecha");



	$.fn.datepicker.dates['es'] = {
		days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
		daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
		daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
		months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
		monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
		today: "Hoy",
		monthsTitle: "Meses"
	};



	$('#date_fecha_desde').datepicker({
	 	todayHighlight: true,
	 	autoclose: true,
	 	container: $("#date_fecha_desde"),
	 	format: 'dd-mm-yyyy',
	 	language: 'es',
	 	endDate: '0d'
	}).on('changeDate', function(e){
	 	$('#date_fecha_hasta').datepicker({
	 		autoclose: true
	 	}).datepicker('setStartDate', e.date);
	 	$('#date_fecha_hasta').focus();
	});



	$('#date_fecha_hasta').datepicker({
		todayHighlight: true,
		autoclose: true,
		format: 'dd-mm-yyyy',
		container: $("#date_fecha_hasta"),
		language: 'es',
		endDate: '0d',
		startDate: '0d'
	}).on('changeDate', function(e){
		$('#date_fecha_desde').datepicker({
			autoclose: true
		}).datepicker('setEndDate', e.date);
	});



	$btnExcelRegistroHospitalizacion.hide();
	$btnExcelRegistroAtencionDiaria.hide();

	$('#dvRAUOLD').hide();



	$("#abrirRepOld").click(function(){
		$('#dvRAUOLD').show();
	});



	$("#generarPDF2").hide();



	$("#sala_Hidratacion_u_observacion_listado").hide();



	if ( $('#tipoReporte option:selected').val() == 1 ) {
		$('#frm_reportesDau').append('<option value="1">Registro de Hospitalización</option>');
		$('#frm_reportesDau').append('<option value="24">Registro de Pacientes Atendidos por Día</option>');
		$('#frm_reportesDau').append('<option value="2">Libro de Alcoholemia</option>');
		$('#frm_reportesDau').append('<option value="3">Libro de Maternidad</option>');
		$('#frm_reportesDau').append('<option value="4">Libro de Accidentes de Transito</option>');
	}



	if ( $('#frm_reportesDau option:selected').val() != 11 ) {
		$('#reportesAtencion').hide()
	}



	$('#tipoReporte').change(function(){
		$("#contenidoAtenciones").empty();
		$("#divFechaHasta").show();
		$btnExcelRegistroHospitalizacion.hide();
		$btnExcelRegistroAtencionDiaria.hide();

		if ( $('#tipoReporte option:selected').val() == 1 ) {

			$('#frm_reportesDau').append('<option value="1">Registro de Hospitalización</option>');
			$('#frm_reportesDau').append('<option value="24">Registro de Pacientes Atendidos por Día</option>');
			$('#frm_reportesDau').append('<option value="2">Libro de Alcoholemia</option>');
			$('#frm_reportesDau').append('<option value="3">Libro de Maternidad</option>');
			$('#frm_reportesDau').append('<option value="4">Libro de Accidentes de Transito</option>');
			$("#reportesAtencion").hide();
			$("#frm_tipoAtencion option[value='0']").remove();
			$("#frm_tipoAtencion option[value='1']").remove();
			$("#frm_tipoAtencion option[value='2']").remove();
			$("#frm_tipoAtencion option[value='3']").remove();

		} else if ( $('#tipoReporte option:selected').val() != 1 ) {

			$("#frm_reportesDau option[value='1']").remove();
			$("#frm_reportesDau option[value='2']").remove();
			$("#frm_reportesDau option[value='3']").remove();
			$("#frm_reportesDau option[value='4']").remove();
			$("#frm_reportesDau option[value='24']").remove();

		}

		if ( $('#tipoReporte option:selected').val() == 2 ) {

			$('#frm_reportesDau').append('<option value="5">REM-A8</option>');
			$('#frm_reportesDau').append('<option value="6">DEIS - Ate y Hosp diarias de Urgencia</option>');
			$('#frm_reportesDau').append('<option value="7">DEIS - Distribución de Diarreas Agudas</option>');
			$('#frm_reportesDau').append('<option value="8">Distribución de Diarreas Agudas</option>');
			$('#frm_reportesDau').append('<option value="9">Vigilancia de Infección Resp. Aguda</option>');
			$('#frm_reportesDau').append('<option value="10">Estadísticas de Diarreas Agudas x Hora</option>');
			$('#frm_reportesDau').append('<option value="11">Categorizacion Urgencia</option>');
			$('#frm_reportesDau').append('<option value="12">Atenciones sin Diagnostico</option>');
			$('#frm_reportesDau').append('<option value="13">Tiempo espera nuevo</option>');
			$('#frm_reportesDau').append('<option value="14">Listado de Hidratación u Observación</option>');
			$("#reportesAtencion").hide();
			$("#frm_tipoAtencion option[value='0']").remove();
			$("#frm_tipoAtencion option[value='1']").remove();
			$("#frm_tipoAtencion option[value='2']").remove();
			$("#frm_tipoAtencion option[value='3']").remove();

		} else {

			$("#sala_Hidratacion_u_observacion_listado").hide();

			if ( $('#tipoReporte option:selected').val() != 2 ) {

				$("#frm_reportesDau option[value='5']").remove();
				$("#frm_reportesDau option[value='6']").remove();
				$("#frm_reportesDau option[value='7']").remove();
				$("#frm_reportesDau option[value='8']").remove();
				$("#frm_reportesDau option[value='9']").remove();
				$("#frm_reportesDau option[value='10']").remove();
				$("#frm_reportesDau option[value='11']").remove();
				$("#frm_reportesDau option[value='12']").remove();
				$("#frm_reportesDau option[value='13']").remove();
				$("#frm_reportesDau option[value='14']").remove();

			}

		}

		if ( $('#tipoReporte option:selected').val() == 3 ) {

			$('#frm_reportesDau').append('<option value="15">Atenciones Médicas (Fecha ADM)</option>');
			$('#frm_reportesDau').append('<option value="16">Atenciones Médicas por Turno</option>');
			$('#frm_reportesDau').append('<option value="17">Entrega y Cierre de Turno DAU</option>');
			$('#frm_reportesDau').append('<option value="18">Entrega de Turno Categ. Adulto</option>');
			$('#frm_reportesDau').append('<option value="19">Entrega de Turno Categ. Pediatrico</option>');
			$('#frm_reportesDau').append('<option value="20">Entrega de Turno Categ. Ginecológico</option>');
			$('#frm_reportesDau').append('<option value="21">Estadistica Atencion por Día</option>');
			$('#frm_reportesDau').append('<option value="22">Estadistica Atencion por Hora</option>');
			$('#frm_reportesDau').append('<option value="23">Resumen Tiempos de Espera </option>');
			$("#reportesAtencion").hide();
			$("#frm_tipoAtencion option[value='0']").remove();
			$("#frm_tipoAtencion option[value='1']").remove();
			$("#frm_tipoAtencion option[value='2']").remove();
			$("#frm_tipoAtencion option[value='3']").remove();

		} else if ( $('#tipoReporte option:selected').val() != 3 ) {

			$("#frm_reportesDau option[value='15']").remove();
			$("#frm_reportesDau option[value='16']").remove();
			$("#frm_reportesDau option[value='17']").remove();
			$("#frm_reportesDau option[value='18']").remove();
			$("#frm_reportesDau option[value='19']").remove();
			$("#frm_reportesDau option[value='20']").remove();
			$("#frm_reportesDau option[value='21']").remove();
			$("#frm_reportesDau option[value='22']").remove();
			$("#frm_reportesDau option[value='23']").remove();
			$("#resumenAtencionesReporte" ).remove();
			$("#resumenTiemposEspera" ).remove();

		}

	});



	$('#frm_reportesDau').change(function(){
		$("#divFechaHasta").show();
		$btnExcelRegistroHospitalizacion.hide();
		$btnExcelRegistroAtencionDiaria.hide();
		$("#contenidoAtenciones").empty();

		if ( $('#frm_reportesDau option:selected').val() == 1 ) {
			$btnExcelRegistroHospitalizacion.show();
		}
		if ( $('#frm_reportesDau option:selected').val() == 24 ) {
			$("#divFechaHasta").hide();
			$btnExcelRegistroAtencionDiaria.show();
		}

		if ( $('#frm_reportesDau option:selected').val() == 18 || $('#frm_reportesDau option:selected').val() == 19 || $('#frm_reportesDau option:selected').val() == 20 ) {
			$("#turnos").show();
		} else {
			$("#turnos").hide();
		}

		if($('#frm_reportesDau option:selected').val()==11 || $('#frm_reportesDau option:selected').val()==12 || $('#frm_reportesDau option:selected').val()==23){

			$('#reportesAtencion').hide()
			$("#frm_tipoAtencion option[value='0']").remove();
			$("#frm_tipoAtencion option[value='1']").remove();
			$("#frm_tipoAtencion option[value='2']").remove();
			$("#frm_tipoAtencion option[value='3']").remove();
			$('#reportesAtencion').show()
			$('#frm_tipoAtencion').append('<option value="0">Todos</option>');
			$('#frm_tipoAtencion').append('<option value="1">Adulto</option>');
			$('#frm_tipoAtencion').append('<option value="2">Pediátrico</option>');
			$('#frm_tipoAtencion').append('<option value="3">Ginecológico</option>');

		} else if ( $('#frm_reportesDau option:selected').val() != 11 || $('#frm_reportesDau option:selected').val() == 23 ) {

			$('#reportesAtencion').hide()
			$("#frm_tipoAtencion option[value='0']").remove();
			$("#frm_tipoAtencion option[value='1']").remove();
			$("#frm_tipoAtencion option[value='2']").remove();
			$("#frm_tipoAtencion option[value='3']").remove();

		}

		if ( $('#frm_reportesDau option:selected').val() == 15 ) {

			$("#generarPDF").hide();
			$("#generarPDF2").show();

		} else if ( $('#frm_reportesDau option:selected').val() != 15 ) {

			$("#generarPDF").show();
			$("#generarPDF2").hide();
			$("#resumenAtencionesReporte" ).remove();

		}

		if ( $('#frm_reportesDau option:selected').val() != 23 ) {

				$("#resumenTiemposEspera" ).remove();
		}

		if ( $('#frm_reportesDau option:selected').val() == 14 ) {

			$("#resumenTiemposEspera" ).remove();
			$("#generarPDF").hide();
			$("#generaExcelRemA8").hide();
			$("#sala_Hidratacion_u_observacion_listado").show();

		} else {

			$("#sala_Hidratacion_u_observacion_listado").hide();

		}

	});



	$("#generarPDF").click(function(){

		$("#resumenAtencionesReporte" ).remove();

		$("#contenidoAtenciones").empty();

		let fechaInicio 	= $("#frm_fecha_admision_desde").val(),

			fechaFin    	= $("#frm_fecha_admision_hasta").val(),

			opcion 			= $('#frm_reportesDau option:selected').val(),

			tipoAtencion 	= $("#frm_tipoAtencion option:selected").val(),

			frm_turno     	= $("#frm_turno").val();

		switch ( opcion ) {

			case '1':
				modalFormulario_noCabecera('', raiz+"/views/modules/reportes/salidas/registroHospitalizacion.php", "fechaInicio="+fechaInicio+"&fechaFin="+fechaFin, "#RegistroHospitalizacion", "modal-lg", "", "fas fa-plus");
			break;
			case '2':
				var funcionImprimir1 = function(){
					if ( $("#result").val() != 0 ) {
						setTimeout(function(){
						$("#scroll").css("overflow-y", "scroll");
						$("#scroll").css("width", "100%");
						$("#scroll").css("height", "650px");
						// $("#fechaTabla").css("margin-top", "-9%");
						// $("#fechaTabla").css("margin-right", "0%");
						}, 100);
						$("#scroll").removeAttr("style");
						// $("#fechaTabla").css("margin-right", "40%");
						// $("#fechaTabla").css("margin-top", "-9%");
						$("#contendidoAlcoholemia").print();
					}
				}
				var botones = 	[
									{ id: 'btnImprimir', value: '<i class="glyphicon glyphicon-print" aria-hidden="true"></i> Imprimir', function: funcionImprimir1, class: 'btn btn-primary' }
								];
				modalFormulario('Registro de Alcoholemia',raiz+"/views/modules/reportes/salidas/libroAlcoholemia.php",'fechaInicio='+fechaInicio+'&fechaFin='+fechaFin,'#RegistroAlcolemia',"modal-lg", "", "fas fa-plus",botones);
			break;
			case '3':
				modalFormulario_noCabecera("Libro de Ingresos y Egresos de Maternidad", raiz+"/views/modules/reportes/salidas/libroMaternidad.php", "fechaInicio="+fechaInicio+"&fechaFin="+fechaFin, "#RegistroLibroParto", "modal-lg", "", "fas fa-plus");
			break;


			case '4':
				modalFormulario_noCabecera("Libro de Accidentes del Transito", raiz+"/views/modules/reportes/salidas/accidentesTrabajo.php", "fechaInicio="+fechaInicio+"&fechaFin="+fechaFin, "#RegistroAccidenteTrabajo", "modal-lg", "", "fas fa-plus");
			break;


			case '5':
				ajaxContentFast('/RCEDAU/views/modules/reportes/reporteREM08/reporteREM08.php',"frm_fechaResumenInicio="+fechaInicio+"&frm_fechaResumenTermino="+fechaFin,'#contenidoAtenciones2');
			break;


			case '6':
				modalFormulario_noCabecera("ATENCIONES Y HOSPITALIZACIONES DE URGENCIA", raiz+"/views/modules/reportes/salidas/ateHosp.php", "fechaInicio="+fechaInicio+"&fechaFin="+fechaFin, "#AteHosp", "modal-lg", "", "fas fa-plus");
			break;


			case '7':
				modalFormulario_noCabecera("DISTRIBUCION DE DIARREAS AGUDAS CON DESHIDRATACION USO DEIS", raiz+"/views/modules/reportes/salidas/DistribucionDiarreasAgudas.php", "fechaInicio="+fechaInicio+"&fechaFin="+fechaFin, "#DistribuciónDiarreasAgudas", "modal-lg", "", "fas fa-plus");
			break;


			case '8':
				modalFormulario_noCabecera("DISTRIBUCION DE DIARREAS AGUDAS", raiz+"/views/modules/reportes/salidas/DistribucionDiarreasAgudas2.php", "fechaInicio="+fechaInicio+"&fechaFin="+fechaFin, "#DistribuciónDiarreasAgudas2", "modal-lg", "", "fas fa-plus");
			break;


			case '9':
				var funcionImprimir1 = function(){
					if ( $("#result").val() != 0 ) {
						setTimeout(function(){
						$("#scroll").css("overflow-y", "scroll");
						$("#scroll").css("width", "100%");
						$("#scroll").css("height", "650px");
						// $("#fechaTabla").css("margin-top", "-9%");
						// $("#fechaTabla").css("margin-right", "0%");
						}, 100);
						$("#scroll").removeAttr("style");
						// $("#fechaTabla").css("margin-right", "40%");
						// $("#fechaTabla").css("margin-top", "-9%");
						$("#contendidoIra").print();
					}
				}
				var botones = 	[
									{ id: 'btnImprimir', value: '<i class="glyphicon glyphicon-print" aria-hidden="true"></i> Imprimir', function: funcionImprimir1, class: 'btn btn-primary' }
								];
				modalFormulario('VIGILANCIA DE INFECCIONES RESPIRATORIAS AGUDAS (I.R.A.)',raiz+"/views/modules/reportes/salidas/ira.php","fechaInicio="+fechaInicio+"&fechaFin="+fechaFin,'#VigilanciaDeInfeccionesRespiratoriaAgudas',"modal-lg", "", "fas fa-plus",botones);
			break;


			case '10':
				modalFormulario_noCabecera("ESTADISTICA DE ATENCIONES POR HORA DE URGENCIA", raiz+"/views/modules/reportes/salidas/atencionesPorHora.php", "fechaInicio="+fechaInicio+"&fechaFin="+fechaFin, "#VigilanciaDeInfeccionesRespiratoriaAgudas", "modal-lg", "", "fas fa-plus");
			break;


			case '11':
				modalFormulario_noCabecera("INFORME CATEGORIZACIÓN URGENCIA", raiz+"/views/modules/reportes/salidas/categorizacionUrgencia.php", "fechaInicio="+fechaInicio+"&fechaFin="+fechaFin+"&tipoAtencion="+tipoAtencion, "#VigilanciaDeInfeccionesRespiratoriaAgudas", "modal-lg", "", "fas fa-plus");
			break;


			case '12':
				modalFormulario_noCabecera("ATENCIONES SIN DIAGNOSTICO", raiz+"/views/modules/reportes/salidas/atencionesSinDiagnostico.php", "fechaInicio="+fechaInicio+"&fechaFin="+fechaFin+"&tipoAtencion="+tipoAtencion, "#VigilanciaDeInfeccionesRespiratoriaAgudas", "modal-lg", "", "fas fa-plus");
			break;


			case '13':
				modalFormulario_noCabecera("TIEMPO ESPERA NUEVO", raiz+"/views/modules/reportes/salidas/tiempoEsperaNuevo.php", "fechaInicio="+fechaInicio+"&fechaFin="+fechaFin, "#VigilanciaDeInfeccionesRespiratoriaAgudas", "modal-lg", "", "fas fa-plus");
			break;


			case '14':
				modalFormulario_noCabecera("AUDITORIA REGISTRO DAU", raiz+"/views/modules/reportes/salidas/auditoriaRegistroDau.php", "fechaInicio="+fechaInicio+"&fechaFin="+fechaFin, "#VigilanciaDeInfeccionesRespiratoriaAgudas", "modal-lg", "", "fas fa-plus");
			break;


			case '16':
				modalFormulario_noCabecera("ATENCIONES MEDICAS POR TURNO", raiz+"/views/modules/reportes/salidas/atencionesMedicasTurno.php", "fechaInicio="+fechaInicio+"&fechaFin="+fechaFin, "#VigilanciaDeInfeccionesRespiratoriaAgudas", "modal-lg", "", "fas fa-plus");
			break;


			case '17':
				modalFormulario_noCabecera("ENTREGA Y CIERRE DE TURNO DAU", raiz+"/views/modules/reportes/salidas/entregaCierreTurno.php", "fechaInicio="+fechaInicio+"&fechaFin="+fechaFin, "#VigilanciaDeInfeccionesRespiratoriaAgudas", "modal-lg", "", "fas fa-plus");
			break;


			case '18':
				modalFormulario_noCabecera("ENTREGA de Turno Categ. Adulto", raiz+"/views/modules/reportes/salidas/entregaTurnoCategAdulto.php", "fechaInicio="+fechaInicio+"&fechaFin="+fechaFin+"&frm_turno="+frm_turno, "#VigilanciaDeInfeccionesRespiratoriaAgudas", "modal-lg", "", "fas fa-plus");
			break;


			case '19':
				modalFormulario_noCabecera("ENTREGA de Turno Categ. Pediatrico", raiz+"/views/modules/reportes/salidas/entregaTurnoCategPediatrico.php", "fechaInicio="+fechaInicio+"&fechaFin="+fechaFin+"&frm_turno="+frm_turno, "#VigilanciaDeInfeccionesRespiratoriaAgudas", "modal-lg", "", "fas fa-plus");
			break;


			case '20':
				modalFormulario_noCabecera("ENTREGA de Turno Categ. Ginecológico", raiz+"/views/modules/reportes/salidas/entregaTurnoCategGinecologico.php", "fechaInicio="+fechaInicio+"&fechaFin="+fechaFin+"&frm_turno="+frm_turno, "#VigilanciaDeInfeccionesRespiratoriaAgudas", "modal-lg", "", "fas fa-plus");
			break;


			case '21':
				modalFormulario_noCabecera("ESTADISTICA DE ATENCIONES DIARIAS DE URGENCIA", raiz+"/views/modules/reportes/salidas/estadisticaAtencionporDia.php", "fechaInicio="+fechaInicio+"&fechaFin="+fechaFin, "#VigilanciaDeInfeccionesRespiratoriaAgudas", "modal-lg", "", "fas fa-plus");
			break;


			case '22':
				modalFormulario_noCabecera("ESTADISTICA DE ATENCIONES HORAS DE URGENCIA", raiz+"/views/modules/reportes/salidas/estadisticaAtencionporHora.php", "fechaInicio="+fechaInicio+"&fechaFin="+fechaFin, "#VigilanciaDeInfeccionesRespiratoriaAgudas", "modal-lg", "", "fas fa-plus");
			break;


			case '23':

				ajaxContent('/RCEDAU/views/modules/reportes/salidas/contenidoResumenTiemposEspera.php', $("#frm_reportes").serialize(),'#contenidoAtenciones2');

				// ajaxContent(raiz+'/views/modules/reportes/salidas/contenidoResumenTiemposEspera.php', $("#frm_reportes").serialize(), '#contenidoAtenciones', '',true);
			break;
			case '24':
				modalFormulario_noCabecera("Registro de Atención Diaria", raiz+"/views/modules/reportes/salidas/registroAtencionDiaria.php", "fechaInicio="+fechaInicio+"&fechaFin="+fechaFin, "#registroAtencionDiaria", "modal-lg", "", "fas fa-plus");
			

				// modalDetalle("Registro de Atención Diaria", raiz+"/views/reportes/salidas/registroAtencionDiaria.php", "fechaInicio="+fechaInicio, "#registroAtencionDiaria", "66%", "100%")
			break;

		}

	});



	$("#generarPDF2").click(function(){
		// C:\inetpub\wwwroot\php8site\RCEDAU\views\modules\reportes\salidas\contenidoAtencionUrgencia.php

		ajaxContent('/RCEDAU/views/modules/reportes/salidas/contenidoAtencionUrgencia.php', $("#frm_reportes").serialize(),'#contenidoAtenciones2');
		// ajaxContent(raiz+'/views/modules/reportes/salidas/contenidoAtencionUrgencia.php', $("#frm_reportes").serialize(), '#contenidoAtenciones', '',true);
	});



	$("#sala_Hidratacion_u_observacion_listado").click(function(){
		var fechaInicio = $("#frm_fecha_admision_desde").val();
		var fechaFin 	= $("#frm_fecha_admision_hasta").val();
		// generarExcel(raiz+'/views/modules/reportes/salidas/sala_Hidratacion_u_observacion_listado.php','fechaInicio='+fechaInicio+'&fechaFin='+fechaFin);

		parametros			= 'fechaInicio='+fechaInicio+'&fechaFin='+fechaFin;
		const url 			= raiz+'/views/modules/reportes/salidas/sala_Hidratacion_u_observacion_listado.php?'+parametros;

		$(document).ready(function (){
            $.blockUI({
                    baseZ: 1060,
                css: { 
                border: 'none', 
                padding: '15px', 
                backgroundColor: '#000', 
                '-webkit-border-radius': '10px', 
                '-moz-border-radius': '10px', 
                opacity: .5, 
                color: '#fff',
                fontSize:'16px'
                },
                message:'<div class="centerTable"><table><tr><td><label class="loadingBlock">Generando Excel... </label></td><td><img src="/estandar/assets/img/loading-5.gif" alt="Generando Excel ... " height="50" width="50"  /></td></tr></table></div>'
            });
        });
        fetch(url)
        .then(resp => {
            console.log("resp", resp);
            if(resp.ok){
                return resp.blob()
            }       
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            // the filename you want
            a.download = 'xls_gestion_reporte.xls';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            $.unblockUI()
        })
        .catch((e) => {
            console.log("e", e);
            modalMensaje('ATENCION','Ha ocurrido un error, comunicarse con <b>mesa de ayuda.',  "#modal", "", "danger");
            $.unblockUI()
        });


	});



	$("#btnEliminarFiltrosReportes").click(function(){
		unsetSesion();
		ajaxContent(raiz+'/views/reportes/consultaReporte.php', '', '#contenidoDAU', '',true);
	});



	$btnExcelRegistroHospitalizacion.on("click", function(){

		const fechaInicio 	= $("#frm_fecha_admision_desde").val();
		const fechaFin    	= $("#frm_fecha_admision_hasta").val();
		parametros			= `fechaInicio=${fechaInicio}&fechaFin=${fechaFin}`;
		const url 			= raiz+'/views/modules/reportes/salidas/excelRegistroHospitalizacion.php?'+parametros;

		$(document).ready(function (){
            $.blockUI({
                    baseZ: 1060,
                css: { 
                border: 'none', 
                padding: '15px', 
                backgroundColor: '#000', 
                '-webkit-border-radius': '10px', 
                '-moz-border-radius': '10px', 
                opacity: .5, 
                color: '#fff',
                fontSize:'16px'
                },
                message:'<div class="centerTable"><table><tr><td><label class="loadingBlock">Generando Excel... </label></td><td><img src="/estandar/assets/img/loading-5.gif" alt="Generando Excel ... " height="50" width="50"  /></td></tr></table></div>'
            });
        });
        fetch(url)
        .then(resp => {
            console.log("resp", resp);
            if(resp.ok){
                return resp.blob()
            }       
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            // the filename you want
            a.download = 'xls_gestion_reporte.xls';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            $.unblockUI()
        })
        .catch((e) => {
            console.log("e", e);
            modalMensaje('ATENCION','Ha ocurrido un error, comunicarse con <b>mesa de ayuda.',  "#modal", "", "danger");
            $.unblockUI()
        });


	});
	$btnExcelRegistroAtencionDiaria.on("click", function(){

		const fechaInicio = $("#frm_fecha_admision_desde").val();
		parametros			= `fechaInicio=${fechaInicio}`;
		const url 			= raiz+'/views/modules/reportes/salidas/excelRegistroAtencionDiaria.php?'+parametros;

		$(document).ready(function (){
            $.blockUI({
                    baseZ: 1060,
                css: { 
                border: 'none', 
                padding: '15px', 
                backgroundColor: '#000', 
                '-webkit-border-radius': '10px', 
                '-moz-border-radius': '10px', 
                opacity: .5, 
                color: '#fff',
                fontSize:'16px'
                },
                message:'<div class="centerTable"><table><tr><td><label class="loadingBlock">Generando Excel... </label></td><td><img src="/estandar/assets/img/loading-5.gif" alt="Generando Excel ... " height="50" width="50"  /></td></tr></table></div>'
            });
        });
        fetch(url)
        .then(resp => {
            console.log("resp", resp);
            if(resp.ok){
                return resp.blob()
            }       
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            // the filename you want
            a.download = 'xls_gestion_reporte.xls';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            $.unblockUI()
        })
        .catch((e) => {
            console.log("e", e);
            modalMensaje('ATENCION','Ha ocurrido un error, comunicarse con <b>mesa de ayuda.',  "#modal", "", "danger");
            $.unblockUI()
        });


	});

});
