$(document).ready(function(){
	validar("#pesoCiclo","numero_comas");
	validar("#tallaCiclo","numero_comas");
	validar("#pulsoCiclo","numero");
	validar("#temperaturaCiclo","numero_comas");
	validar("#saturacionCiclo","numero");
	validar("#hemoglucoTest","letras_numeros_caracteres");
	validar("#signosVitalesFetales","numero");
	calcularIMC();
	$(".calculoIMC").on('keypress keyup', function(){
		calcularIMC();
	});
	$(".calculoPAM").on('keypress keyup', function(){
		calcularPAM();
	});
	function calcularIMC ( ) {
		let peso 		= $("#pesoCiclo").val();
		let talla 		= $("#tallaCiclo").val();
		if ( peso == '' || talla == '' ) {
			$("#imc").val("");
			$("#imc").css("background-color","#FFF");
			return;
		}
		if( talla > 0 && peso > 0){
			let tallaM 		= (talla*talla)/10000;
			let imcTotal 	= peso/tallaM;
			imcTotal 		= imcTotal.toFixed(2);
			tipoObesidad(imcTotal);
		}else{
			$("#imc").val(0);
			$("#imc").css("background-color","#00a0b0 ");
		}
	}
	function tipoObesidad ( imcTotal ) {
		$("#imc").val(imcTotal);
		//obesidad grado 3
		if ( imcTotal >= 40 ) {
			$("#imc").css("background-color","#e72523");
		//obesidad grado 2
		} else if ( imcTotal >= 30 && imcTotal < 40 ) {
			$("#imc").css("background-color","#ec5f20");
		//obesidad grado 1
		} else if ( imcTotal >= 27 && imcTotal < 30 ) {
			$("#imc").css("background-color","#f29800");
		//sobrepeso
		} else if ( imcTotal >= 25 && imcTotal < 27 ) {
			$("#imc").css("background-color","#ffcd2b");
			$("#imc").css("color","#000");
		//normal
		} else if ( imcTotal >= 18 && imcTotal < 25 ) {
			$("#imc").css("background-color","#4ec24b");
		//peso bajo
		}else{
			$("#imc").css("background-color","#00a0b0 ");
		}
		$("#imc").css("color","#FFF");
	}
	function calcularPAM ( ) {
		if ( isNaN(parseFloat($("#sistolicaCiclo").val())) || $("#sistolicaCiclo").val() == '' ) {
			sistolica = 0;
		}else{
			sistolica = parseFloat($("#sistolicaCiclo").val());
		}
		if ( isNaN(parseFloat($("#diastolicaCiclo").val())) || $("#diastolicaCiclo").val() == '' ) {
			diastolica = 0;
		}else{
			diastolica =  parseFloat($("#diastolicaCiclo").val());
		}
		if( diastolica > 0 && sistolica > 0){
			let PAM = ( ( ( 2 * diastolica ) + sistolica ) / 3 ).toFixed(1);
			$("#pamCiclo").val(PAM);	
		}
	}
	$("#btn_agregar_signos_vitales").click(function(){
		$.validity.start();
		if ( $("#pesoCiclo").val() == "" && $("#tallaCiclo").val() == "" && $("#sistolicaCiclo").val() == "" && $("#diastolicaCiclo").val() == "" && $("#pulsoCiclo").val() == "" && $("#frCiclo").val() == ""  && $("#saturacionCiclo").val() == "" && $("#glasgowCiclo").val() == "" && $("#temperaturaCiclo").val() == "" && $("#hemoglucoTest").val() == "" && $("#signosVitalesFetales").val() == "" && $("#rbne").val() == ""){
			$('#pesoCiclo').assert(false,'Debe Ingresar al Menos Un Campo');
			$.validity.end();
			return false;
		}
		result = $.validity.end();
		if(result.valid==false){
			return false;
		}
		var  grabarSignosVitales = function(){
			var grabarSigno = function(response){
				$("#frm_prevision").prop('disabled', false);
				switch(response.status){
					case "success":
						texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-edit mr-2" style="color: #5a8dc5; font-size: 26px"></i>Signos Vitales. </h4><hr><p>¡Felicidades! Se han guardado los signos vitales correctamente.</p> </div>';
						modalMensajNoCabecera('Exito',texto,  "#modal", "modal-md", "success");
						ajaxContent(raiz+'/views/modules/rce/signos_vitales/signos_vitales.php','dau_id='+$('#dau_idSV').val()+'&tipoMapa='+$('#tipoMapa').val(),'#div_signos','', true);
						if($('#banderaRCE').val() == 1){
							ajaxContent(raiz+'/views/modules/rce/medico/rce.php','tipoMapa='+$('#tipoMapa').val()+'&dau_id='+$('#dau_idSV').val(),'#contenido','', true);
						}
					break;
					default:        
						modalMensajNoCabecera('Error Defecto','',  "#modal", "modal-md", "success");
					break;
				}
	        };
			ajaxRequest(raiz+'/controllers/server/mapa_piso_full/main_controller.php',$("#frm_ciclo_vital").serialize()+'&accion=registrarSVITAL', 'POST', 'JSON', 1,'Guardando Signos vitales...', grabarSigno);
		}
		modalConfirmacionNuevo("Signos Vitales", "Se procedera a Guardar los signos vitales del Paciente, <b>¿Desea continuar?</b></label>","primary", grabarSignosVitales);
	});
});