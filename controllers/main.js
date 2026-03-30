// local = localStorage.getItem('position_id_/gespaciente/index.php');
// if(local == "S"){
	var ip_local= "10.6.21.29:8081";
	var ip_doce = "10.6.21.29:8081";
// }else{
// 	var ip_local= "190.54.59.78";
// 	var ip_doce = "190.54.59.78";
// }
// var ip_node = "http://10.6.21.29:3000";
var refreshTime;
var raiz 	= "http://"+ip_local+"/RCEDAU";
var estandar 	= "http://"+ip_local+"/estandar";
var host 	= "http://"+ip_local;
var num_solicitud_id;
var urlPort = ip_local+":3000";

var consultarInformeDalca = "http://10.6.21.29/apiHJNCDalca/consultarInforme";
var consultarImagenDalca = "http://10.6.21.29/apiHJNCDalca/consultarImagen";
var intervals = [];
let tiempoServidor = '';

let perfilUsuario  = '';

let PermisoVerificado = false;

let idDomProfesionalTurno = '';
let idDomIdProfesionalTurno = '';
const allowedCharacters = "1234567890.,():;>-+<abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ?¿¡!º /º!·$·%&/()=?¿^*ç¨_:´`Ç´{[]}{ °!#$%&()=?¡ ¨*[]_:;¿´+{},.-~^`";

// --- Filtro mientras se escribe ---
function filterInput_libre(event) {
	const input = event.target;
	input.value = input.value.split('').filter(char => {
		// Permitir salto de línea solo si es un textarea
		return allowedCharacters.includes(char) || 
			(char === '\n' && input.tagName === 'TEXTAREA');
	}).join('');
}

// --- Filtro al pegar texto ---
function handlePaste_libre(event) {
	const clipboardData = event.clipboardData || window.clipboardData;
	const pastedData = clipboardData.getData('text/plain');
	const input = event.target;

	// Filtra caracteres pegados
	const filteredData = pastedData.split('').filter(char => {
		return allowedCharacters.includes(char) ||
			(char === '\n' && input.tagName === 'TEXTAREA');
	}).join('');

	// Obtiene posición actual del cursor
	const start = input.selectionStart;
	const end = input.selectionEnd;

	// Inserta el texto filtrado en la posición del cursor
	const textBefore = input.value.substring(0, start);
	const textAfter = input.value.substring(end);
	input.value = textBefore + filteredData + textAfter;

	// Reposiciona el cursor
	input.setSelectionRange(start + filteredData.length, start + filteredData.length);

	event.preventDefault(); // Evita pegar texto no permitido
}
function infoNombreDoc(transexual,nombreSocial,nombrePac){
	var nombre = "";
	if(transexual == 'S' || transexual == 's' ){
		if( nombreSocial!= ""){
			nombre = '<img src="/rcedau/assets/img/transIco.png" height="16" width="16"  /><b> '+nombreSocial.toUpperCase()+'</b> / '+nombrePac.toUpperCase();
		}
	}else{
		nombre = nombrePac.toUpperCase();
	}

	return nombre;
}

function tiempoPermitidoTranscurridoDesdeIndicacionEgreso ( idDau ,tipoMapa) {
	const tiempoPermitido = 1800;
	const parametros = { 'idDau' : idDau , 'accion' : 'tiempoIndicacionEgreso' };
	const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/dau/main_controller.php`, parametros, 'POST', 'JSON', 1, 'Cargando...');
	if ( respuestaAjaxRequest.status != 'error' && respuestaAjaxRequest.intervaloTiempo > tiempoPermitido ) {
		texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error! </h4>  <hr>  <p class="mb-0">Se le indicó egreso al paciente hace más de 30 min, favor de aplicar egreso según nuevas opciones presentadas. Se recargará la página.</p></div>';
        modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
    	ajaxContentSlideLeft(`${raiz}/views/modules/mapa_piso_full/mapa_piso_full.php`,'tipoMapa='+tipoMapa, '#contenido');
		return false;
	}
	return true;
}
async function validarEstadoPaciente(dau_id) {
    const resultado = await ajaxRequestPromisified(
        raiz + '/controllers/server/medico/main_controller.php',
        'dau_id=' + dau_id + '&accion=verificarEstadoPaciente',
        'POST',
        'JSON'
    );

    switch (resultado.status) {
        case 'error':
            const texto = `<div class="alert alert-light" role="alert">
                            <h4 class="alert-heading">
                                <i class="fas fa-times throb2 text-danger" style="font-size:29px"></i> 
                                Error para registrar 
                            </h4>
                            <hr>
                            <p class="mb-0">${resultado.textoError}</p>
                           </div>`;
            modalMensajNoCabecera('', texto, "#modal", "modal-md", "success");
			ajaxContent(raiz+'/views/modules/rce/medico/rce.php','tipoMapa='+tipoMapa+`&dau_id=${dau_id}`,'#contenido','', true);
            return false;
        case 'success':
            return true;
        default:
            return false;
    }
}
async function pacienteYaConNEA(dau_id,tipoMapa) {
    const resultado = await ajaxRequestPromisified(
        raiz + '/controllers/server/medico/main_controller.php',
        'dau_id=' + dau_id + '&accion=pacienteYaConNEA',
        'POST',
        'JSON'
    );

    switch (resultado.status) {
        case 'error':
            const texto = `<div class="alert alert-light" role="alert">
                            <h4 class="alert-heading">
                                <i class="fas fa-times throb2 text-danger" style="font-size:29px"></i> 
                                Error para registrar 
                            </h4>
                            <hr>
                            <p class="mb-0">${resultado.textoError}</p>
                           </div>`;
            modalMensajNoCabecera('', texto, "#modal", "modal-md", "success");
			ajaxContent(raiz+'/views/modules/rce/medico/rce.php','tipoMapa='+tipoMapa+`&dau_id=${dau_id}`,'#contenido','', true);
            return false;
        case 'success':
            return true;
        default:
            return false;
    }
}
async function validarPermisoUsuario(boton) {
    const resultado = await ajaxRequestPromisified(
        raiz + '/controllers/server/categorizacion/main_controller.php',
        'boton=' + boton + '&accion=verificarPermisoUsuario',
        'POST',
        'JSON'
    );
    switch (resultado) {
        case true:
            return true;
        default:
        	ErrorPermiso();
            return false;
    }
}


function ErrorPermiso (){
	texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-shield-alt throb2 text-danger" style="font-size:29px"></i>  Permiso Denegado</h4>  <hr>  <p class="mb-0">Usted no cuenta con los permisos necesarios para realizar esta acción. Por favor, comuníquese con el equipo de Mesa de Ayuda para obtener asistencia.<br><br><div class="text-center"><i class="fas fa-envelope text-danger mr-1"></i> mesadeayuda@hjnc.cl <i class="ml-3 mr-1 text-danger fas fa-mobile-alt"></i>584685 <i class="fas fa-mobile-alt ml-3 mr-1 text-danger"></i>584686 <i class="ml-3 mr-1 fas fa-mobile-alt text-danger"></i>584679</div></p></div>';
    modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
}
function ErrorSistema (){
	texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-ban throb2 text-danger" style="font-size:29px"></i> Error al mover Paciente </h4>  <hr>  <p class="mb-0">'+respuestaControlador.message+'<br><br><div class="text-center"><i class="fas fa-envelope text-danger mr-1"></i> mesadeayuda@hjnc.cl <i class="ml-3 mr-1 text-danger fas fa-mobile-alt"></i>584685 <i class="fas fa-mobile-alt ml-3 mr-1 text-danger"></i>584686 <i class="ml-3 mr-1 fas fa-mobile-alt text-danger"></i>584679</div></p></div>';
	modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
}
function ErrorSistemaDefecto (){
	texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-bomb throb2 text-danger" style="font-size:29px"></i> Error </h4>  <hr>  <p class="mb-0"><br><div class="text-center">Pedimos disculpas, pero ha ocurrido un error y no fue posible procesar su solicitud. Por favor, comuníquese con la Mesa de Ayuda para recibir asistencia.<br><br><br><i class="fas fa-envelope text-danger mr-1"></i> mesadeayuda@hjnc.cl <i class="ml-3 mr-1 text-danger fas fa-mobile-alt"></i>584685 <i class="fas fa-mobile-alt ml-3 mr-1 text-danger"></i>584686 <i class="ml-3 mr-1 fas fa-mobile-alt text-danger"></i>584679</div></p></div>';
	modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
}
function ajaxRequestPromisified(url, data, method, dataType) {
    return new Promise((resolve, reject) => {
        ajaxRequest(url, data, method, dataType, 1, 'Cargando...', function (response) {
            resolve(response);
        });
    });
}
function ajaxContentSlideLeft(url,parametros,contenedor){
	$(contenedor).show(function(){
		$(contenedor).hide('slide', {direction: 'right'}, 250);
		$.ajax({
			type : "POST",
			url  : url,
			data : parametros
		}).done(function(datos){
			$(contenedor).html(datos);
			$(contenedor).show('slide', {direction: 'left'}, 250);
			$.unblockUI();
		}).fail(function(){
			$.unblockUI();
			return false;
		});
	});
}
function ajaxContentSlideRight(url,parametros,contenedor){
	$(contenedor).show(function(){
		$(contenedor).hide('slide', {direction: 'left'}, 250);
		$.ajax({
			type : "POST",
			url  : url,
			data : parametros
		}).done(function(datos){
			$(contenedor).html(datos);
			$(contenedor).show('slide', {direction: 'right'}, 250);
			$.unblockUI();
		}).fail(function(){
			$.unblockUI();
			return false;
		});
	});
}
function cargarContentAsync(url,parametros,contenedor){
	return new Promise((response) => {
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
			message:'<div class="centerTable"><table><tr><td><label class="loadingBlock">Cargando ... </label></td><td><img src="/Camas/assets/img/loading-5.gif" alt="Generando Excel ... " height="50" width="50"  /></td></tr></table></div>'
		});
		$(contenedor).fadeOut(25, function(){
			$.ajax({
				type: "POST",
				url:url,
				data:parametros,
				success: function(datos){
					$('.validity-tooltip').remove();
					//$.unblockUI(); 
					$(contenedor).html(datos);
					response(true);
					$.unblockUI()
				},
				error : function(err){
					response(false);
					$.unblockUI()
					modalMensajeBtnExit("Error en el proceso", "Por favor, Comunicarse con mesa de ayuda 4679", "error_doc", 400, 300,'danger');
					// alert()
				}

			});
			$(contenedor).fadeIn();
		});
		//FIN FUNCION AJAX
	});
}
function limitaCampoTexto2(elEvento, maximoCaracteres, id_campoTexto) {
	var elemento = document.getElementById(id_campoTexto);
	var evento = elEvento || window.event;
	var codigoCaracter = evento.charCode || evento.keyCode;

	if(codigoCaracter == 37 || codigoCaracter == 39) {
		return true;
	}

	if(codigoCaracter == 8 || codigoCaracter == 46) {
		return true;
	}
	else if(elemento.value.length >= maximoCaracteres ) {
		return false;
	}
	else {
		return true;
	}
}
function actualizaInfoTexto2(maximoCaracteres,id_campoTexto, id_infoTexto) {
	var elemento = document.getElementById(id_campoTexto);
	var info     = document.getElementById(id_infoTexto);

	if(elemento.value.length >= maximoCaracteres || elemento.value.length == 0) {
		info.innerHTML = 'Máximo '+maximoCaracteres+' caracteres';
	}
	else {
		info.innerHTML = 'Puedes escribir hasta '+(maximoCaracteres-elemento.value.length)+' caracteres adicionales';
	}
}
function cambiarFormaDigitacionHora(id, e){

	var expresionRegularHoraIngresada = /^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$/;
	var expresionRegularHora = /^([01]?[0-9]|2[0-3]):?[0-5][0-9]$/;
	var horaIngresada = $("#"+id).val();
	var error = false;

	if(!expresionRegularHoraIngresada.test(horaIngresada) || horaIngresada == ''){
		if (expresionRegularHora.test(horaIngresada)) {
			hh = parseInt(horaIngresada / 100, 10);
			mm = horaIngresada % 100;
			ss = new Date().getSeconds();

			if(hh >= 0 && hh <= 9){
				hh = '0'+hh;
			}
			if(mm >= 0 && mm <= 9){
				mm = '0'+mm;
			}
			if(ss <= 9){
				ss = '0'+ss;
			}

			horaIngresada = hh+':'+mm+':'+ss;
			$("#"+id).val(horaIngresada);
		}
		else {
			var horaActual = new Date();
			horaActual = horaActual.getHours() + ":" + horaActual.getMinutes() + ":" + horaActual.getSeconds();
			$("#"+id).val(horaActual);
			$('#'+id).assert(false,'Debe ingresar formato válido');
			error = true;
		}
	}
	return error;
}
function contadorTiempoEspera(idcont, src, controlador = 'adulto_pediatrico') {

	let intervalo 	= null;
	let segundos 	= 0;
	let minutos 	= 0;
	let horas 		= 0;
	let dias 		= 0;
	let aux 		= 0;
	let arr_idcont 	= [];

	for (i = 0; i < idcont.length; i++) {
		aux = idcont[i].id.split('_');
		arr_idcont[i] = aux[1];
	}

	for ( let i = 0; i < intervals.length; i++ ) {

		clearInterval (intervals[i].id_interval);

	}

	let fn_tiempos = function(response){


		switch(response[0].status){

			case 'success':

				for(j=0; j<arr_idcont.length; j++){

					i = 0;

					for(k=0; k<response.length; k++){
						if(response[k].id == arr_idcont[j]){
							i = k;
						}
					}

					dias = Math.floor(response[i].segundos/86400);
					horas = Math.floor((response[i].segundos%86400)/3600);
					minutos = Math.floor(((response[i].segundos%86400)%3600)/60);
					// segundos = Math.floor(((response[i].segundos%86400)%3600)%60);


					if (segundos < 10) { segundos = "0"+segundos }
					if (minutos < 10) { minutos = "0"+minutos }
					if (horas < 10) { horas = "0"+horas}
					var txt_dias = '';
					if (dias > 0) { txt_dias = dias+"d ";}else{txt_dias = '';}

					var txt_segundos =  segundos;
					var txt_minutos  = minutos;
					var txt_horas    = horas;

					var gif_reloj 		 = '';
					var class_colorIE 	 = '';
					var css_ti_aapli 	 = '';
					var css_ti_aapli_ant = '';

					if ( (typeof response[i].cat_tiempo_maximo !== 'undefined') && (typeof response[i].cat_nivel !== 'undefined') ) {

						if ( response[i].cat_nivel == '1' ) {

							gif_reloj = '<i class="far fa-clock text-danger throb mifuente14  " aria-hidden="true"></i>&nbsp;&nbsp;';

						} else if ( (response[i].cat_nivel == '2') || (response[i].cat_nivel == '3') || (response[i].cat_nivel == '4') ) {

							if ( response[i].segundos >= response[i].cat_tiempo_maximo_seg ) {

								gif_reloj = '<i class="far fa-clock text-danger throb mifuente14  " aria-hidden="true"></i>&nbsp;&nbsp;';

							}

						}

					}

					$('#'+idcont[j].id).html(gif_reloj+txt_dias+txt_horas +":"+ txt_minutos +":"+ txt_segundos);

					intervalo = setearIntervalo(
													idcont[j].id,
													segundos,
													txt_segundos,
													minutos,
													txt_minutos,
													horas,
													txt_horas,
													dias,
													txt_dias,
													gif_reloj,
													class_colorIE,
													css_ti_aapli,
													css_ti_aapli_ant,
													response[i].cat_tiempo_maximo,
													response[i].cat_nivel,
													response[i].segundos,
													response[i].cat_tiempo_maximo_seg,
													response[i].dau_indicacion_egreso_fecha,
													response[i].dau_inicio_atencion_fecha,
													response[i].fecha_ini_atencion,
													response[i].fecha_ind_egreso,
													response[i].motivo_ind_egreso,
													response[i].dau_indicacion_terminada,
													response[i].tiempoCategorizacion,
													response[i].tiempoAlerta,
													response[i].solicitudesAplicadas,
													response[i].dauAbiertoMantenedor
												);

					var n = intervalo.toString();

					intervals.push({'id' : arr_idcont[j], 'id_interval' : n});

				}
			break;

			case 'error':
			break;

			default:
			break;
		}
	}
	var parametros = {'accion': 'tiempoEsperaPaciente', 'opc': src, 'dau_id': arr_idcont};

	ajaxRequest(raiz+'/controllers/server/mapa_piso_full/main_controller.php', parametros, 'POST', 'JSON', 1, 'Actualizando ....',fn_tiempos);

}

function contadorTiempoEsperaIndividual(idcont, src) {
	var interval2 = null;
	var segundos = 0;
	var minutos = 0;
	var horas = 0;
	var dias = 0;

	var arr_idcont = idcont.split('_');



	var fn_tiempos2 = function(response){

		switch(response.status){
			case 'success':

				var inter = searchObj(intervals, arr_idcont[1]);
				clearInterval(parseInt(inter));
				deleteElementObj(intervals, arr_idcont[1]);
				dias = Math.floor(response.segundos/86400);
			    horas = Math.floor((response.segundos%86400)/3600);
			    minutos = Math.floor(((response.segundos%86400)%3600)/60);
			    segundos = Math.floor(((response.segundos%86400)%3600)%60);

			    if (segundos < 10) { segundos = "0"+segundos }
				if (minutos < 10) { minutos = "0"+minutos }
				if (horas < 10) { horas = "0"+horas}
				var txt_dias = '';
				if (dias > 0) { txt_dias = dias+"d ";}else{txt_dias = '';}

				var txt_segundos = segundos;
				var txt_minutos = minutos;
				var txt_horas = horas;

				//=============================================================
				var gif_reloj = '';
				// var class_colorIE = 'well-camas-';
				var css_ti_aapli = '';

				if ((typeof response.cat_tiempo_maximo !== 'undefined') && (typeof response.cat_nivel !== 'undefined')) {
					if (response.cat_nivel == '1') {
						gif_reloj = '<i class="far fa-clock text-danger throb mifuente14   " aria-hidden="true"></i>&nbsp;&nbsp;';
					}
					else if ((response.cat_nivel == '2') || (response.cat_nivel == '3') || (response.cat_nivel == '4')) {
						if (response.segundos >= response.cat_tiempo_maximo_seg) {
							gif_reloj = '<i class="far fa-clock text-danger throb mifuente14   " aria-hidden="true"></i>&nbsp;&nbsp;';
						}
					}
				}
				//=============================================================

				$('#'+idcont).html(gif_reloj+txt_dias+txt_horas +":"+ txt_minutos +":"+ txt_segundos)

				//=============================================================

				interval2 = window.setInterval(function(){
					segundos ++;
					if (segundos < 10) { segundos = "0"+segundos; }
					txt_segundos = segundos;

					if (segundos == 59) {
						segundos = -1;
					}

					if ( segundos == 0 ) {
						minutos++;
						if (minutos < 10) { minutos = "0"+minutos; }
						txt_minutos = minutos;
					}

					if (minutos == 59) {
						minutos = -1;
					}

					if ( (segundos == 0)&&(minutos == 0) ) {
						horas ++;
						if (horas < 10) { horas = "0"+horas; }
						txt_horas = horas;
					}

					if (horas == 23){
						horas = -1;
					}

					if ((horas == 0)&&(segundos == 0)&&(minutos == 0)){
						dias++;
						txt_dias = dias+"d ";
					}

					//===========================================
					if ((typeof response.cat_tiempo_maximo !== 'undefined') && (typeof response.cat_nivel !== 'undefined')) {
						if (response.cat_nivel == '1') {
							gif_reloj = '<i class="far fa-clock text-danger throb mifuente14   " aria-hidden="true"></i>&nbsp;&nbsp;';
						}
						else if ((response.cat_nivel == '2') || (response.cat_nivel == '3') || (response.cat_nivel == '4')) {
							var seg_total = parseInt(dias*86400)+parseInt(horas*3600)+parseInt(minutos*60);
							if (seg_total >= response.cat_tiempo_maximo_seg) {
								gif_reloj = '<i class="far fa-clock text-danger throb mifuente14   " aria-hidden="true"></i>&nbsp;&nbsp;';
							}
						}
					}
					//=============================================================

					$('#'+idcont).html(gif_reloj+txt_dias+txt_horas +":"+ txt_minutos +":"+ txt_segundos)

					//=============================================================

				}, 1000);

				 var n = interval2.toString();
				 intervals.push({'id' : arr_idcont[1], 'id_interval' : n});

			break;
			case 'error':

			break;
			default:

			break;
		}
	}
	var parametros = {'accion': 'tiempoEsperaPacienteIndividual', 'opc': src, 'dau_id': arr_idcont[1]};
	ajaxRequest(raiz+'/controllers/server/mapa_piso/main_controller.php', parametros, 'POST', 'JSON', 1, 'Actualizando ....',fn_tiempos2);

}

//Función setear intervalos de tiempo
function setearIntervalo (
							idcont,
							segundos,
							txt_segundos,
							minutos,
							txt_minutos,
							horas,
							txt_horas,
							dias,
							txt_dias,
							gif_reloj,
							class_colorIE,
							css_ti_aapli,
							css_ti_aapli_ant,
							response_cat_tiempo_maximo,
							response_cat_nivel,
							response_segundos,
							response_cat_tiempo_maximo_seg,
							response_dau_indicacion_egreso_fecha,
							response_dau_inicio_atencion_fecha,
							response_fecha_ini_atencion,
							response_fecha_ind_egreso,
							response_motivo_ind_egreso,
							response_dau_indicacion_terminada,
							response_tiempoCategorizacion,
							response_tiempoAlerta,
							solicitudesAplicadas,
							dauAbiertoMantenedor
						 ){

	intervalo2 = setInterval(function(){

		css_ti_aapli = '';
		segundos ++;

		if ( segundos < 10 ) { segundos = "0"+segundos; }

		txt_segundos = segundos;

		if ( segundos == 59 ) {

			segundos = -1;

		}

		if ( segundos == 0 ) {

			minutos++;

			if (minutos < 10) { minutos = "0"+minutos; }

			txt_minutos = minutos;

		}

		if ( minutos == 59 ) {

			minutos = -1;

		}

		if ( (segundos == 0) && (minutos == 0) ) {

			horas ++;

			if (horas < 10) { horas = "0"+horas; }

			txt_horas = horas;

		}

		if ( horas == 23 ){

			horas = -1;

		}

		if ( (horas == 0) && (segundos == 0) && (minutos == 0) ){

			dias++;
			txt_dias = dias+"d ";

		}

		if ( (typeof response_cat_tiempo_maximo !== 'undefined') && (typeof response_cat_nivel !== 'undefined') ) {

			if (response_cat_nivel == '1') {

				gif_reloj = '<i class="far fa-clock text-danger throb mifuente14   " aria-hidden="true"></i>&nbsp;&nbsp;';

			}else if ( (response_cat_nivel == '2') || (response_cat_nivel == '3') || (response_cat_nivel == '4') ) {

				var seg_total = parseInt(dias*86400)+parseInt(horas*3600)+parseInt(minutos*60);

				if (seg_total >= response_cat_tiempo_maximo_seg) {

					gif_reloj = '<i class="far fa-clock text-danger throb mifuente14   " aria-hidden="true"></i>&nbsp;&nbsp;';
				}

			}

		}

		let fecha_ini_atencion 		= tiempoServidor - response_fecha_ini_atencion;

		let fecha_ind_egreso 		= tiempoServidor - response_fecha_ind_egreso;

		if ( response_fecha_ind_egreso == 0 || response_fecha_ind_egreso == null || response_fecha_ind_egreso == false) {

			fecha_ind_egreso = 0 ;

		}

		if  ( response_dau_inicio_atencion_fecha != '' && response_dau_inicio_atencion_fecha != null) {

			if ( fecha_ini_atencion > 21599 ) {

				if ( response_motivo_ind_egreso != '' && response_motivo_ind_egreso != null ) {

					if ( fecha_ind_egreso < 43200 && fecha_ind_egreso > 0) {

						css_ti_aapli = 'rojo';

					} else if ( fecha_ind_egreso >= 43200 ) {

						css_ti_aapli = 'fucsia';

					}

				} else {

					css_ti_aapli = 'amarillo';

				}

			} else {

				if ( response_dau_indicacion_terminada == 1 ) {

					if ( response_dau_indicacion_egreso_fecha != '' && response_dau_indicacion_egreso_fecha != null ) {

						if ( fecha_ind_egreso < 43200 && fecha_ind_egreso > 0) {

							css_ti_aapli = 'rojo';

						} else if ( fecha_ind_egreso >= 43200 ) {

							css_ti_aapli = 'fucsia';

						}

					} else {

						css_ti_aapli = 'verde';

					}

				} else if ( response_dau_indicacion_terminada == 0 ) {

					if ( response_dau_indicacion_egreso_fecha != '' && response_dau_indicacion_egreso_fecha != null  ){

						if ( fecha_ind_egreso < 43200 ) {

							css_ti_aapli = 'rojo';

						} else if ( fecha_ind_egreso >= 43200 ) {

							css_ti_aapli = 'fucsia';

						}

					} else {

						css_ti_aapli = 'plomo';

					}

				}

			}

		} else {

			css_ti_aapli = 'plomo';

		}

		if( (css_ti_aapli_ant != css_ti_aapli)  ) {

			let idPadre = $('#'+idcont).parent().attr('id');

			if ( css_ti_aapli_ant == ''){

				$('#'+idPadre).removeClass(class_colorIE+'plomo');

			} else {

				$('#'+idPadre).removeClass(class_colorIE+css_ti_aapli_ant);

			}

			$('#'+idPadre).addClass(class_colorIE+css_ti_aapli);

		}

		if ( existeMotivoParaRetirarPaciente(response_motivo_ind_egreso) && solicitudesNoSuperfluas(solicitudesAplicadas) && haPasadoTiempoRequeridoDesdeIndicacionEgreso(fecha_ind_egreso) &&  dauNoAbiertoRecientementeDesdeMantenedor(dauAbiertoMantenedor) ) {

			retirarPacienteMapaPiso ( idcont );

		}

		if ( seCumpleTiempoEsperaCategorizacion(response_tiempoCategorizacion, response_tiempoAlerta) && ( response_dau_inicio_atencion_fecha == '' || response_dau_inicio_atencion_fecha == null ) ) {


			insertarRelojEsperaCategorizacion ( idcont );

		}


		$('#'+idcont).html(gif_reloj+txt_dias+txt_horas +":"+ txt_minutos +":"+ txt_segundos);

	}, 1000);

	return intervalo2;
	//=============================================================
}

function existeMotivoParaRetirarPaciente ( motivoIndicacionEgreso ) {

	return ( motivoIndicacionEgreso != '' && motivoIndicacionEgreso != null && motivoIndicacionEgreso != 'Hospitalización' && motivoIndicacionEgreso != "Traslado Ginecología" && motivoIndicacionEgreso != "Traslado Adulto" && motivoIndicacionEgreso != "Traslado Pediátrico" ) ? true : false;

}
function seCumpleTiempoEsperaCategorizacion ( tiempoCategorizacion, tiempoAlerta ) {

	let tiempoTranscurridoDesdeCategorizacion = 0;

	if ( tiempoCategorizacion !== 0 ){

		tiempoTranscurridoDesdeCategorizacion = Math.round(Date.now() / 1000) - tiempoCategorizacion;

	}

	return ( tiempoTranscurridoDesdeCategorizacion > tiempoAlerta ) ? true : false;

}
function solicitudesNoSuperfluas ( solicitudesAplicadas ) {


	return ( solicitudesAplicadas == 1 ) ? true : false;

}
function insertarRelojEsperaCategorizacion ( idDiv ) {

	const arrDau 					  = idDiv.split('_');

	const idDau  					  = arrDau[1];

	const nombreSala 			 	  = $(`#${idDiv}`).parent().attr('id');

	const idRelojEsperaCategorizacion =	`relojEsperaCategorizacion_${idDau}`;

	const gifRelojCategorizacion 	  = `<span id="${idRelojEsperaCategorizacion}" class="text-upleft-custom"><i class="far fa-clock text-danger throb mifuente14    " aria-hidden="true"></i></span>`;

	if ( contenidoNoEstaAgregadoADiv(idRelojEsperaCategorizacion) ) {

		$(`#${nombreSala}`).append(gifRelojCategorizacion);

	}

}
function contenidoNoEstaAgregadoADiv ( idRelojEsperaCategorizacion ) {

	return ( $(`span#${idRelojEsperaCategorizacion}`).length === 0 ) ? true : false;

}

function capitalizarString ( str ) {

	let linea = str.toLowerCase().split(' ');

	return linea.map( function ( item ) {

		return item.slice( 0, 1 ).toUpperCase() + item.slice( 1 );

	}).join(' ');

}

////////////////////////DAU/////////////////////////
function verImagenDalca(idSolicitud, titulo){
	$.blockUI({
	message: '<img height="80" src="'+raiz+'/assets/img/loading-5.gif"/><label class="loadingBlock">Cargando imagen de DALCA porfavor espere...</label>',
	baseZ: 2000,
	css: {
	  border: 'none',
	  backgroundColor:'transparent'
	}
	});

	fetch(`${consultarImagenDalca}/${idSolicitud}`, {
	method: 'GET',
	headers: {
	  'Accept': 'application/json'
	}
	})
	.then(response => {
	if (response.status === 404) {
	  throw new Error('Error 404: Página no encontrada');
	}
	if (!response.ok) {
	  throw new Error('Error en la solicitud Fetch: ' + response.status);
	}
	return response.json();
	})
	.then(data => {
	console.log(data); // Maneja los datos recibidos aquí

	if(data.attending_doctor_to_study_link){
	 window.open(data.attending_doctor_to_study_link, titulo, 'titlebars=0,toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,height=700,width=700');

	}else{
	  modalMensaje("No disponible", "La imagen aun no se encuentra disponible en dalca.", "imagen_not_found", "", 'warning');
	}


	})
	.catch(error => {
	console.error('Error:', error);
	})
	.finally(() => {
	$.unblockUI(); // Desbloquear UI después de completar la solicitud
	});


}
$(document).on('show.bs.modal', '.modal', function () {
    var zIndex = 1040 + (10 * $('.modal:visible').length);
    $(this).css('z-index', zIndex);
    setTimeout(function() {
        $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
    }, 0);
});
function tablaFixedHeader(referencia, size) {
	if (referencia.startsWith(".")) {
		$(referencia).each(function (index, element) {
			tablaFixedHeader("#" + $(this).attr("id"));
		});
	} else {
		var tabla = $(referencia).DataTable({
			"ordering": false,
			"info": false,
			"bPaginate": false,
			"stateSave": true,
			"autoWidth": false,
			"bLengthChange": false,
			"bSort": true,
			"bFilter": false,
			"aaSorting": [],
			"scrollX": true,
			"sDom": 'lfrtip',
			"sScrollY": size,
			"paging": false,
			"fixedHeader": {
				header: true,
			},
			language: {
							"decimal": ",",
            			"thousands": ".",
							"sProcessing":     "Procesando...",
							"sLengthMenu":     "Mostrar _MENU_ registros",
							"sZeroRecords":    "No se encontraron resultados",
							"sEmptyTable":     "Ningún dato disponible en esta tabla",
							"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
							"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
							"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
							"sInfoPostFix":    "",
							"sSearch":         "Filtrar tabla:",
							"sUrl":            "",
							"sInfoThousands":  ",",
							"sLoadingRecords": "Cargando...",
							"oPaginate": {
								"sFirst":    "Primero",
								"sLast":     "Último",
								"sNext":     "Siguiente",
								"sPrevious": "Anterior"
							},
							"oAria": {
								"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
								"sSortDescending": ": Activar para ordenar la columna de manera descendente"
							}
						},
		});

		setTimeout(function () {
			tabla.columns.adjust();
		}, 100);

		$(window).resize(function () {
			tabla.columns.adjust();
		});
	}

	return tabla;
}
function validarEmail( email ) {
	expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if ( !expr.test(email) ){// INCORRECTO , ENTRA
		return false;
	}else{
		return true;
	}
}
function ajaxContentSinLoad(url,parametros,contenedor, mensaje, sincrono, callback){
	//document.getElementById(boton).blur();
	var msg = "Cargando...";
	var async = false;
	if(typeof mensaje !== "undefined" && mensaje !="") {
		msg   = mensaje;
	}

	if(typeof (sincrono) === 'boolean' && sincrono == true) {
		async = sincrono;
	}

	// $.blockUI({
	// 	message: '<img height="80" src="/../../estandar/assets/img/loading-5.gif"/><label class="loadingBlock">'+msg+'</label>',
	// 	css: {
	// 		border: 'none',
	// 		backgroundColor:'transparent'
	// 	},
	// 	baseZ: 2000
	// });
	$(contenedor).fadeOut(50, function(){
		$.ajax({
			type : "POST",
			url  : url,
			data : parametros,
			async: async
		}).done(function(datos){
			$(contenedor).html(datos);
			$(contenedor).fadeIn();
			if(typeof callback === "function" && sincrono){
				callback();
			}
			$.unblockUI();
		}).fail(function(){
			$.unblockUI();
			return false;
		});
	});
}
function calendarioReloj(identificador, formato){
	//http://momentjs.com/docs/#/displaying/format/
	if(typeof formato === "undefined" && formato == "") {
		formato   = 'DD-MM-YYYY';
	}
	if(identificador.startsWith(".")){
		$(identificador).each(function( index, element ) {
			calendarioHora("#"+$(this).attr("id"));
		});
	}else
		$(identificador).datetimepicker({
			format: formato,
			locale: 'es',
			icons: {
                time: 'far fa-clock text-danger throb mifuente14',
                date: 'far fa-calendar-alt',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'far fa-calendar-check',
                clear: 'far fa-trash',
                close: 'fas fa-times'
            },tooltips: {
				hoy: 'Ir a Hoy',
				clear: 'Borrar Selección',
				close: 'Cerrar el Selector',
				selectMonth: 'Seleccionar Mes',
				prevMonth: 'Mes Anterior',
				nextMonth: 'Próximo Mes',
				selectYear: 'Seleccionar Año',
				prevYear: 'Año Anterior',
				nextYear: 'Próximo Año',
				selectDecade: 'Seleccione Década',
				prevDecade: 'Década Anterior',
				nextDecade: 'Próxima Década',
				prevCentury: 'Siglo Anterior',
				nextCentury: 'Próximo Siglo',
				incrementHour: 'Incrementar Hora',
				pickHour: 'Seleccionar Hora',
				decrementHour: 'Decrementar Hora',
				incrementMinute: 'Incrementar Minuto',
				pickMinute: 'Seleccionar Minuto',
				decrementMinute: 'Decrementar Minuto',
				incrementSecond: 'Incrementar Segundo',
				pickSecond: 'Seleccionar Segundo',
				decrementSecond:'Decrementar Segundo',
				selectTime: 'Seleccionar Tiempo',
				selectDate: 'Seleccionar Fecha'
			}
		});
}
function message2(tipo, mensaje, ubicacion, id, autoClose, btnCerrar, duracion){//Funcion para mostrar label con errores
	id	= id+""+Math.floor(Date.now() / 1000);

	switch(tipo){
		case 'success':
				mensaje = '<i class="fas fa-check"></i> '+mensaje;
		break;
		case 'info':
				mensaje = '<i class="fas fa-info-circle"></i> '+mensaje;
		break;
		case 'warning':
				mensaje = '<i class="fas fa-exclamation-triangle"></i> '+mensaje;
		break;
		case 'danger':
				mensaje = '<i class="fas fa-times"></i> '+mensaje;
		break;
	}
	if(typeof(btnCerrar) !== "undefined" && btnCerrar == false){
		btnCerrar = "";
	}else{
		btnCerrar = '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	}
	var duracionCerrar = 5000;
	if(typeof(duracion) !== "undefined" && isNaN(duracion) == false){
		duracionCerrar = duracion;
	}
	var html =	'<div id="alert'+id+'" class="alert alert-'+tipo+' alert-dismissible fade show" role="alert">'+
					btnCerrar+
					mensaje+
				'</div>';
	$(html).hide().appendTo(ubicacion).slideDown(500);
	if(typeof(autoClose) !== "undefined" && autoClose == true){
		setTimeout(function(){
			$("#alert"+id).slideUp(500, function(){
				$("#alert"+id).slideUp(500).alert('close');
				$(this).off();
			});
		}, duracionCerrar);
	}
	return id;
}
function message(tipo, mensaje, ancho, ubicacion, id, autoClose, btnCerrar, duracion){//Funcion para mostrar label con errores

	// Tipos alertas ==> succes, info, warning, danger
	/***** Configuracion dependiendo del estado*****/
	/*$("#"+id).remove();*/
	id = id +""+Math.floor(Date.now() / 1000);
	var span;
	switch(tipo){
		case 'success':
				span = '<span class="glyphicon glyphicon-ok-sign"></span>';
		break;

		case 'info':
				span = '<span class="glyphicon glyphicon-info-sign"></span></b>';
		break;

		case 'warning':
				span = '<span class="glyphicon glyphicon-warning-sign"></span>';
		break;

		case 'danger':
				span = '<span class="glyphicon glyphicon-remove-sign"></span>';
		break;
	}
	var hidden= '<span aria-hidden="true">&times;</span>';
	if(typeof(btnCerrar) !=="undefined" && btnCerrar == false){
		hidden = "";
	}
	var duracionCerrar = 5000;
	if(typeof(duracion) !=="undefined" && isNaN(duracion) == false){
		duracionCerrar = duracion;
	}
	var html = '<div id="'+id+'" class="messageAlert">'+
							'<div class="alert alert-'+tipo+'" style="width:'+ancho+';" id="alert'+id+'">'+
								'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
								hidden+
								'</button>'+
									span+" "+mensaje+
					'</div>';
	$(html).hide().appendTo(ubicacion).slideDown(500);
	if(typeof(autoClose) !=="undefined" && autoClose == true){
		setTimeout(function(){
			$("#alert"+id).slideUp(500, function(){
	          $("#alert"+id).slideUp(500).alert('close');
	          $("#"+id).remove();
	          $(this).off();
	      });
		}, duracionCerrar);
	}
	return id;
}

function ajaxContent(url,parametros,contenedor, mensaje, sincrono, callback){
	//document.getElementById(boton).blur();
	var msg = "Cargando...";
	var async = false;
	if(typeof mensaje !== "undefined" && mensaje !="") {
		msg   = mensaje;
	}

	if(typeof (sincrono) === 'boolean' && sincrono == true) {
		async = sincrono;
	}

	$.blockUI({
		message: '<img height="80" src="/../../estandar/assets/img/loading-5.gif"/><label class="loadingBlock" style="font-weight:bold; color:white">'+msg+'</label>',
		css: {
			border: 'none',
			backgroundColor:'transparent'
		},
		baseZ: 2000
	});
	$(contenedor).fadeOut(50, function(){
		$.ajax({
			type : "POST",
			url  : url,
			data : parametros,
			async: async
		}).done(function(datos){
			$(contenedor).html(datos);
			$(contenedor).fadeIn();
			if(typeof callback === "function" && sincrono){
				callback();
			}
			$.unblockUI();
		}).fail(function(){
			$.unblockUI();
			return false;
		});
	});
}
function ajaxContentFast(url,parametros,contenedor){
	$(contenedor).show(function(){
		$.ajax({
			type : "POST",
			url  : url,
			data : parametros
		}).done(function(datos){
			$(contenedor).html(datos);
			$(contenedor).show();
			$.unblockUI();
		}).fail(function(){
			$.unblockUI();
			return false;
		});
	});
}

function ajaxRequest(url,data, http, dataType, iterator, mensaje, callBack, toView){//Funcion para hacer peticiones mediante ajax.
	/*
		iterator => Cantidad de veces que intentara realizar una peticion al servicio.
		Se hace esto porque a veces da error 404.
	*/
	if (iterator == 0){
		return false;
	}

	var async_type = false;
	var response;

	if(typeof (callBack) === "function"){
		async_type = true;
		if(typeof mensaje !== "undefined"){
			$.blockUI({
				message: '<img height="80" src="/../../estandar/assets/img/loading-5.gif"/><label class="loadingBlock" style="font-weight:bold; color:white">'+mensaje+'</label>',
				baseZ: 2000,
				css: {
					border: 'none',
					backgroundColor:'transparent'
				},
				baseZ: 2000
			});
		}
   }
   if(typeof (callBack) === 'boolean' && callBack == true)
   	async_type = true;

	$.ajax({
	 	url  : url,
	 	type : http,
	 	data : data,
	 	dataType : dataType,
		async: async_type
	}).done(function(retorno){
		var go = true;
		// if(retorno.status=="sesion_expirada"){
		// 	go = false;
		// 	var fn = function(){ view("#contenido_gestionCama"); }
  //        modalMensaje("Sesión Expirada", retorno.message, "error_sesion", 500, 300, 'panel-danger', fn);
		// 	$.unblockUI();
		// }
		if(go){
			if(async_type){
				if(typeof (callBack) === "function"){
					response = callBack(retorno);
					if(typeof (toView) === "undefined")
						$.unblockUI();
				}
			}else{
				response = retorno;
			}
		}
	}).fail(function(){
		$.unblockUI();
		return retorno = ajaxRequest(url, data, http, dataType, --iterator, mensaje, callBack); //La hacemos recursiva.
	});
	return response;
}

function view(content){
	// $(".dropdown-item").removeClass("active"); // Opcional: quitar clase active de otros elementos
	// position = getPosition();
    // $("#"+position).addClass("active");
	removerValidity();
	var fn = function (retorno) {
		ajaxContent(retorno,'',content,'Cargando vista...', true);
	}
	
	ajaxRequest(raiz+'/controllers/server/view_controller.php', "position_id="+getPosition(), "POST", "text", 1,'Obteniendo vista...',fn, "Si" );
}

function setPosition(valor){
	localStorage.setItem("position_id_"+window.location.pathname, valor);
}

function getPosition(){
	var position_id = ""; // Por defecto
	if (localStorage.getItem("position_id_"+window.location.pathname)!=null) {
		position_id = localStorage.getItem("position_id_"+window.location.pathname);
	}
	return position_id;
}
function modalConfirmacionNuevo(titulo, body, clase, funcion, funcionSalir){
	var time	= new Date().getTime();
	var id		= "modalConfirmacion"+time;
	switch(clase){
		case 'primary':
			clase		= 'bg-primary text-white';
			clase_boton = 'btn-primary'
		break;
		case 'secondary':
			clase		= 'bg-secondary text-white';
			clase_boton = 'btn-secondary'
		break;
		case 'success':
			clase		= 'bg-success text-white';
			clase_boton = 'btn-success'
		break;
		case 'info':
			clase		= 'bg-info text-white';
			clase_boton = 'btn-info'
		break;
		case 'warning':
			clase		= 'bg-warning text-white';
			clase_boton = 'btn-warning'
		break;
		case 'danger':
			clase		= 'bg-danger text-white';
			clase_boton = 'btn-danger'
		break;
		case 'dark':
			clase		= 'bg-dark text-white';
			clase_boton = 'btn-dark'
		break;
		case 'light':
			clase		= 'bg-light';
			clase_boton = 'btn-primary'
		break;
		default:
			clase		= '';
			clase_boton = 'btn-primary'
		break;
	}
	var modal ='<div id="modalConfirmacion'+time+'" class="modal fade" tabindex="-1" role="dialog" >'+
					'<div class="modal-dialog modal-dialog-centered modal-md" role="document" >'+
						'<div class="modal-content border-0" id="modalContenido" >'+
						'<div class="modal-body"><label class="text-danger" style="font-size:26px;">'+titulo+' <i class="fas throb fa-pen-square ml-2"></i></label  ><hr style ="margin-top:0rem !important; margin-bottom : 2rem;"> <label class="mb-0 text-secondary">'+body+'</label> <br><br></div>'+
							'<div class="modal-footer py-1">'+
								'<button type="button" class="btn btn-sm btn-success" data-dismiss="modal" id="idSi'+time+'" ><i class="fas fa-check"></i> Aceptar</button>'+
								'<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" id="cancelar'+time+'"><i class="fas fa-times"></i> Cancelar</button>'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div>';
	//SE AGREGA AL BODY
	$("body").append(modal);
	//PARA ELIMINAR TODO Y QUE NO QUEDE NADA DUPLICADO
	$('#modalConfirmacion'+time).off().on('hidden.bs.modal', function (e) {
		$("body").removeAttr( "style" );
		$('#modalConfirmacion'+time).remove();
		// SOLUCIONA PROBLEMA DE SCROLL AL ABRIR UN MODAL SOBRE OTRO MODAL
		if ($(".modal")[0]) {
		   $("body").addClass("modal-open");
		}
	});
	//SE HACE MODAL Y SE LE SETEAN LAS OPCIONES
	$('#modalConfirmacion'+time).modal({
		keyboard: false,
		show: true,
		backdrop: true,
		backdrop: 'static'
	});
	//EVENT QUE  SE LLEVA ACABO CUANDO CONFIRMA LA ALERTA
	// $("#idSi"+time).click(function(){
 //        $(this).prop('disabled', true); // Deshabilita el botón
	// 	funcion();
	// 	$('#modalConfirmacion'+time).modal( 'hide' ).data( 'bs.modal', null );
	// });
	$("#idSi"+time).click(function(){
    $(this).prop('disabled', true); // Deshabilita el botón
    const $modalBody = $('#modalConfirmacion'+time+' .modal-body');

    // Muestra un gif centrado mientras se ejecuta la función
    $modalBody.html(`
        <div class="text-center">
            <img src="/../../estandar/assets/img/loading-5.gif" width="80" alt="Cargando..." />
            <p class="mt-2 text-secondary">Procesando...</p>
        </div>
    `);

    // Llamas a tu función principal
    setTimeout(() => {
        funcion(); // ejecuta la función real
        // Luego cierra el modal
        $('#modalConfirmacion'+time).modal('hide').data('bs.modal', null);
    }, 1200); // retrasa un poco para que se vea el gif
});
	if(typeof funcionSalir !== "undefined"){
		//EVENT QUE  SE LLEVA ACABO CUANDO CONFIRMA LA ALERTA
		$("#cancelar"+time).click(function(){
			funcionSalir();
		});
	}
	return id;
}
function modalConfirmacion(titulo, body, clase, funcion, funcionSalir){
	var time	= new Date().getTime();
	var id		= "modalConfirmacion"+time;
	switch(clase){
		case 'primary':
			clase		= 'bg-primary text-white';
			clase_boton = 'btn-primary'
		break;
		case 'secondary':
			clase		= 'bg-secondary text-white';
			clase_boton = 'btn-secondary'
		break;
		case 'success':
			clase		= 'bg-success text-white';
			clase_boton = 'btn-success'
		break;
		case 'info':
			clase		= 'bg-info text-white';
			clase_boton = 'btn-info'
		break;
		case 'warning':
			clase		= 'bg-warning text-white';
			clase_boton = 'btn-warning'
		break;
		case 'danger':
			clase		= 'bg-danger text-white';
			clase_boton = 'btn-danger'
		break;
		case 'dark':
			clase		= 'bg-dark text-white';
			clase_boton = 'btn-dark'
		break;
		case 'light':
			clase		= 'bg-light';
			clase_boton = 'btn-primary'
		break;
		default:
			clase		= '';
			clase_boton = 'btn-primary'
		break;
	}
	var modal ='<div id="modalConfirmacion'+time+'" class="modal fade" tabindex="-1" role="dialog" >'+
					'<div class="modal-dialog modal-dialog-centered modal-md" role="document" >'+
						'<div class="modal-content border-0" id="modalContenido" >'+
						'<div class="modal-body">'+body+'</div>'+
							'<div class="modal-footer py-1">'+
								'<button type="button" class="btn btn-sm btn-success" data-dismiss="modal" id="idSi'+time+'" ><i class="fas fa-check"></i> Aceptar</button>'+
								'<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" id="cancelar'+time+'"><i class="fas fa-times"></i> Cancelar</button>'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div>';

	//SE AGREGA AL BODY
	$("body").append(modal);

	//PARA ELIMINAR TODO Y QUE NO QUEDE NADA DUPLICADO
	$('#modalConfirmacion'+time).off().on('hidden.bs.modal', function (e) {
		$("body").removeAttr( "style" );
		$('#modalConfirmacion'+time).remove();
		// SOLUCIONA PROBLEMA DE SCROLL AL ABRIR UN MODAL SOBRE OTRO MODAL
		if ($(".modal")[0]) {
		   $("body").addClass("modal-open");
		}
	});

	//SE HACE MODAL Y SE LE SETEAN LAS OPCIONES
	$('#modalConfirmacion'+time).modal({
		keyboard: false,
		show: true,
		backdrop: true,
		backdrop: 'static'
	});

	//EVENT QUE  SE LLEVA ACABO CUANDO CONFIRMA LA ALERTA
	$("#idSi"+time).click(function(){
		funcion();
		$('#modalConfirmacion'+time).modal( 'hide' ).data( 'bs.modal', null );
	});

	if(typeof funcionSalir !== "undefined"){
		//EVENT QUE  SE LLEVA ACABO CUANDO CONFIRMA LA ALERTA
		$("#cancelar"+time).click(function(){
			funcionSalir();
		});
	}
	return id;
}
function modalConfirmacionNoExit(titulo, body, clase, funcion, funcionSalir){
	var time	= new Date().getTime();
	var id		= "modalConfirmacion"+time;
	switch(clase){
		case 'primary':
			clase		= 'bg-primary text-white';
			clase_boton = 'btn-primary'
		break;
		case 'secondary':
			clase		= 'bg-secondary text-white';
			clase_boton = 'btn-secondary'
		break;
		case 'success':
			clase		= 'bg-success text-white';
			clase_boton = 'btn-success'
		break;
		case 'info':
			clase		= 'bg-info text-white';
			clase_boton = 'btn-info'
		break;
		case 'warning':
			clase		= 'bg-warning text-white';
			clase_boton = 'btn-warning'
		break;
		case 'danger':
			clase		= 'bg-danger text-white';
			clase_boton = 'btn-danger'
		break;
		case 'dark':
			clase		= 'bg-dark text-white';
			clase_boton = 'btn-dark'
		break;
		case 'light':
			clase		= 'bg-light';
			clase_boton = 'btn-primary'
		break;
		default:
			clase		= '';
			clase_boton = 'btn-primary'
		break;
	}
	var modal ='<div id="modalConfirmacion'+time+'" class="modal fade" tabindex="-1" role="dialog" >'+
					'<div class="modal-dialog modal-dialog-centered modal-md" role="document" >'+
						'<div class="modal-content border-0" id="modalContenido" >'+
						'<div class="modal-body">'+body+'</div>'+
							'<div class="modal-footer py-1">'+
								'<button type="button" class="btn btn-sm btn-success" data-dismiss="modal" id="idSi'+time+'" ><i class="fas fa-check"></i> Aceptar</button>'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div>';

	//SE AGREGA AL BODY
	$("body").append(modal);

	//PARA ELIMINAR TODO Y QUE NO QUEDE NADA DUPLICADO
	$('#modalConfirmacion'+time).off().on('hidden.bs.modal', function (e) {
		$("body").removeAttr( "style" );
		$('#modalConfirmacion'+time).remove();
		// SOLUCIONA PROBLEMA DE SCROLL AL ABRIR UN MODAL SOBRE OTRO MODAL
		if ($(".modal")[0]) {
		   $("body").addClass("modal-open");
		}
	});

	//SE HACE MODAL Y SE LE SETEAN LAS OPCIONES
	$('#modalConfirmacion'+time).modal({
		keyboard: false,
		show: true,
		backdrop: true,
		backdrop: 'static'
	});

	//EVENT QUE  SE LLEVA ACABO CUANDO CONFIRMA LA ALERTA
	$("#idSi"+time).click(function(){
		funcion();
		$('#modalConfirmacion'+time).modal( 'hide' ).data( 'bs.modal', null );
	});

	if(typeof funcionSalir !== "undefined"){
		//EVENT QUE  SE LLEVA ACABO CUANDO CONFIRMA LA ALERTA
		$("#cancelar"+time).click(function(){
			funcionSalir();
		});
	}
	return id;
}
function modalDetalleSinCerrar(titulo, url, parametros, idModal, tamaño, clase, functionAceptar){
	/**********************************TAMAÑOS MODAL**********************************/
	// - SM = modal-sm
	// - MD = modal-md
	// - LG = modal-lg
	// - XL = modal-xl
	/*********************************************************************************/
	var id	  = idModal.substring(1);
	var time  = new Date().getTime();
	switch(clase){
		case 'primary':
			clase		= 'bg-primary text-white';
			clase_boton = 'btn-primary'
			clase_x		= 'text-white';
		break;
		case 'secondary':
			clase		= 'bg-secondary text-white';
			clase_boton = 'btn-secondary'
			clase_x		= 'text-white';
		break;
		case 'success':
			clase		= 'bg-success text-white';
			clase_boton = 'btn-success'
			clase_x		= 'text-white';
		break;
		case 'info':
			clase		= 'bg-info text-white';
			clase_boton = 'btn-info'
			clase_x		= 'text-white';
		break;
		case 'warning':
			clase		= 'bg-warning text-white';
			clase_boton = 'btn-warning'
			clase_x		= 'text-white';
		break;
		case 'danger':
			clase		= 'bg-danger text-white';
			clase_boton = 'btn-danger'
			clase_x		= 'text-white';
		break;
		case 'dark':
			clase		= 'bg-dark text-white';
			clase_boton = 'btn-dark'
			clase_x		= 'text-white';
		break;
		case 'light':
			clase		= 'bg-light';
			clase_boton = 'btn-primary'
			clase_x		= 'text-dark';
		break;
		default:
			clase		= '';
			clase_boton = 'btn-primary'
			clase_x		= 'text-dark';
		break;
	}
	var modal = '<div id="modalDetalle'+time+'" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">'+
					'<div class="modal-dialog '+tamaño+'" role="document">'+
						'<div class="modal-content border-0">'+
							'<div class="modal-header py-1 '+clase+'">'+
								'<h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-info-circle"></i> '+titulo+'</h5>'+
								'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="'+clase_x+'" aria-hidden="true">&times;</span></button>'+
							'</div>'+
							'<div class="modal-body" id="'+id+'">'+
							'</div>'+
							''+
						'</div>'+
					'</div>'+
				'</div>';
	//SE AGREGA AL BODY
	$("body").append(modal);
	//CARGAMOS EL CONTENIDO
	var funcionModal = function(){
		//PARA ELIMINAR TODO Y QUE NO QUEDE NADA DUPLICADO
		$('#modalDetalle'+time).off().on('hidden.bs.modal', function (e) {
			$('#modalDetalle'+time).remove();
			//$("body").removeAttr( "style" );
			$('.validity-tooltip').remove();

			// CHACHI CULIAO FIXED PANTALLA SCROLL
			if ($(".modal")[0]) {
			   $("body").addClass("modal-open");
			}
		});
		//SE HACE MODAL Y SE LE SETEAN LAS OPCIONES
		$('#modalDetalle'+time).modal({
			keyboard: true,
			show: true
		});
		$("#aceptarDetalle"+time).click(function(){
			$('#modalDetalle'+time).modal( 'hide' ).data( 'bs.modal', null );
			if(typeof functionAceptar === "function")
				functionAceptar();
		});
	}
	ajaxContent(url, parametros, idModal, 'Cargando...', true, funcionModal );
	return "modalDetalle"+time;
}
function modalDetalle(titulo, url, parametros, idModal, tamaño, clase, functionAceptar){
	/**********************************TAMAÑOS MODAL**********************************/
	// - SM = modal-sm
	// - MD = modal-md
	// - LG = modal-lg
	// - XL = modal-xl
	/*********************************************************************************/
	var id	  = idModal.substring(1);
	var time  = new Date().getTime();
	switch(clase){
		case 'primary':
			clase		= 'bg-primary text-white';
			clase_boton = 'btn-primary'
			clase_x		= 'text-white';
		break;
		case 'secondary':
			clase		= 'bg-secondary text-white';
			clase_boton = 'btn-secondary'
			clase_x		= 'text-white';
		break;
		case 'success':
			clase		= 'bg-success text-white';
			clase_boton = 'btn-success'
			clase_x		= 'text-white';
		break;
		case 'info':
			clase		= 'bg-info text-white';
			clase_boton = 'btn-info'
			clase_x		= 'text-white';
		break;
		case 'warning':
			clase		= 'bg-warning text-white';
			clase_boton = 'btn-warning'
			clase_x		= 'text-white';
		break;
		case 'danger':
			clase		= 'bg-danger text-white';
			clase_boton = 'btn-danger'
			clase_x		= 'text-white';
		break;
		case 'dark':
			clase		= 'bg-dark text-white';
			clase_boton = 'btn-dark'
			clase_x		= 'text-white';
		break;
		case 'light':
			clase		= 'bg-light';
			clase_boton = 'btn-primary'
			clase_x		= 'text-dark';
		break;
		default:
			clase		= '';
			clase_boton = 'btn-primary'
			clase_x		= 'text-dark';
		break;
	}
	var modal = '<div id="modalDetalle'+time+'" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">'+
					'<div class="modal-dialog '+tamaño+'" role="document">'+
						'<div class="modal-content border-0">'+
							'<div class="modal-header py-1 '+clase+'">'+
								'<h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-info-circle"></i> '+titulo+'</h5>'+
								'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="'+clase_x+'" aria-hidden="true">&times;</span></button>'+
							'</div>'+
							'<div class="modal-body" id="'+id+'">'+
							'</div>'+
							'<div class="modal-footer py-1">'+
								'<button type="button" id="aceptarDetalle'+time+'" class="btn btn-sm '+clase_boton+'"><i class="fas fa-check"></i> Cerrar</button>'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div>';
	//SE AGREGA AL BODY
	$("body").append(modal);
	//CARGAMOS EL CONTENIDO
	var funcionModal = function(){
		//PARA ELIMINAR TODO Y QUE NO QUEDE NADA DUPLICADO
		$('#modalDetalle'+time).off().on('hidden.bs.modal', function (e) {
			$('#modalDetalle'+time).remove();
			//$("body").removeAttr( "style" );
			$('.validity-tooltip').remove();

			// CHACHI CULIAO FIXED PANTALLA SCROLL
			if ($(".modal")[0]) {
			   $("body").addClass("modal-open");
			}
		});
		//SE HACE MODAL Y SE LE SETEAN LAS OPCIONES
		$('#modalDetalle'+time).modal({
			keyboard: true,
			show: true
		});
		$("#aceptarDetalle"+time).click(function(){
			$('#modalDetalle'+time).modal( 'hide' ).data( 'bs.modal', null );
			if(typeof functionAceptar === "function")
				functionAceptar();
		});
	}
	ajaxContent(url, parametros, idModal, 'Cargando...', true, funcionModal );
	return "modalDetalle"+time;
}

function modalMensaje(titulo, mensaje, idModal, tamaño, clase, functionAceptar){
	/**********************************TAMAÑOS MODAL**********************************/
	// - SM = modal-sm
	// - MD = modal-md
	// - LG = modal-lg
	// - XL = modal-xl
	/*********************************************************************************/
	var idModal		= idModal.substring(1);
	var time		= new Date().getTime();
	switch(clase){
		case 'primary':
			clase		= 'bg-primary text-white';
			clase_boton = 'btn-primary'
			clase_x		= 'text-white';
		break;
		case 'secondary':
			clase		= 'bg-secondary text-white';
			clase_boton = 'btn-secondary'
			clase_x		= 'text-white';
		break;
		case 'success':
			clase		= 'bg-success text-white';
			clase_boton = 'btn-success'
			clase_x		= 'text-white';
		break;
		case 'info':
			clase		= 'bg-info text-white';
			clase_boton = 'btn-info'
			clase_x		= 'text-white';
		break;
		case 'warning':
			clase		= 'bg-warning text-white';
			clase_boton = 'btn-warning'
			clase_x		= 'text-white';
		break;
		case 'danger':
			clase		= 'bg-danger text-white';
			clase_boton = 'btn-danger'
			clase_x		= 'text-white';
		break;
		case 'dark':
			clase		= 'bg-dark text-white';
			clase_boton = 'btn-dark'
			clase_x		= 'text-white';
		break;
		case 'light':
			clase		= 'bg-light';
			clase_boton = 'btn-primary'
			clase_x		= 'text-dark';
		break;
		default:
			clase		= '';
			clase_boton = 'btn-primary'
			clase_x		= 'text-dark';
		break;
	}
	var modal	= '<div id="'+idModal+time+'" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">'+
						'<div class="modal-dialog modal-dialog-centered '+tamaño+'" role="document">'+
							'<div class="modal-content border-0"">'+
								'<div class="modal-header py-1 '+clase+'">'+
									'<h5 class="modal-title mifuente18" id="exampleModalLabel"><i class="fas fa-exclamation-triangle parpadea"></i>&nbsp;&nbsp;'+titulo+'</h5>'+
									'<button type="button" class="close" id="btnEquis" data-dismiss="modal" aria-label="Close"><span class="'+clase_x+'" aria-hidden="true">&times;</span></button>'+
								'</div>'+
								'<div class="modal-body" id="contenidoMensaje">'+ mensaje +
								'</div>'+
								'<div class="modal-footer py-1">'+
									'<button type="button" id="btn_aceptar_mensaje'+time+'" class="btn btn-sm '+clase_boton+'"><i class="fas fa-check"></i> Aceptar</button>'+
								'</div>'+
							'</div>'+
						'</div>'+
					'</div>';
	//SE AGREGA AL BODY
	$("body").append(modal);

	//PARA ELIMINAR TODO Y QUE NO QUEDE NADA DUPLICADO
	$('#'+idModal+time).off().on('hidden.bs.modal', function (e) {
		$('#'+idModal+time).remove();
		$("body").removeAttr( "style" );
		$('.validity-tooltip').remove();
		// CHACHI CULIAO FIXED PANTALLA SCROLL
		if ($(".modal")[0]) {
		   $("body").addClass("modal-open");
		}
		// if(typeof functionAceptar === "function")
		// 		functionAceptar();
	});

	$("#btnEquis").click(function(){
		$('#'+idModal+time).modal('hide').data('bs.modal', null);
		if(typeof functionAceptar === "function")
			functionAceptar();
	});

	//SE HACE MODAL Y SE LE SETEAN LAS OPCIONES
	$('#'+idModal+time).modal({
		keyboard: true,
		show: true,
		backdrop: true,
		backdrop: 'static'
	});

	$("#btn_aceptar_mensaje"+time).click(function(){
		$('#'+idModal+time).modal('hide').data('bs.modal', null);
		if(typeof functionAceptar === "function")
			functionAceptar();
	});
	return idModal+time;
}
function modalMensajNoCabecera(titulo, mensaje, idModal, tamaño, clase, functionAceptar){
	/**********************************TAMAÑOS MODAL**********************************/
	// - SM = modal-sm
	// - MD = modal-md
	// - LG = modal-lg
	// - XL = modal-xl
	/*********************************************************************************/
	if(mensaje == ""){
		mensaje = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-bomb mr-2" style="color: #ef645d; font-size: 26px"></i>ERROR. </h4><hr><p class="text-center">Lamentamos informarle que se ha producido un error. Para resolver este inconveniente, le pedimos amablemente que se contacte con nuestra mesa de ayuda. <br> <br><i class="far fa-envelope mr-2 text-dangerLight"></i>mesadeayuda@hjnc.cl  <i class="fas fa-mobile-alt ml-2 mr-2 text-dangerLight"></i>584685 <i class="fas fa-mobile-alt ml-2 mr-2 text-dangerLight"></i>584686 <i class="fas fa-mobile-alt ml-2 mr-2 text-dangerLight"></i>584679</p> </div>';
	}
	var idModal		= idModal.substring(1);
	var time		= new Date().getTime();
	switch(clase){
		case 'primary':
			clase		= 'bg-primary text-white';
			clase_boton = 'btn-primary'
			clase_x		= 'text-white';
		break;
		case 'secondary':
			clase		= 'bg-secondary text-white';
			clase_boton = 'btn-secondary'
			clase_x		= 'text-white';
		break;
		case 'success':
			clase		= 'bg-success text-white';
			clase_boton = 'btn-success'
			clase_x		= 'text-white';
		break;
		case 'info':
			clase		= 'bg-info text-white';
			clase_boton = 'btn-info'
			clase_x		= 'text-white';
		break;
		case 'warning':
			clase		= 'bg-warning text-white';
			clase_boton = 'btn-warning'
			clase_x		= 'text-white';
		break;
		case 'danger':
			clase		= 'bg-danger text-white';
			clase_boton = 'btn-danger'
			clase_x		= 'text-white';
		break;
		case 'dark':
			clase		= 'bg-dark text-white';
			clase_boton = 'btn-dark'
			clase_x		= 'text-white';
		break;
		case 'light':
			clase		= 'bg-light';
			clase_boton = 'btn-primary'
			clase_x		= 'text-dark';
		break;
		default:
			clase		= '';
			clase_boton = 'btn-primary'
			clase_x		= 'text-dark';
		break;
	}
	var modal	= '<div id="'+idModal+time+'" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">'+
						'<div class="modal-dialog modal-dialog-centered '+tamaño+'" role="document">'+
							'<div class="modal-content border-0"">'+
								''+
									''+
									''+
								''+
								'<div class="modal-body" id="contenidoMensaje">'+ mensaje +
								'</div>'+
								'<div class="modal-footer py-1">'+
									'<button type="button" id="btn_aceptar_mensaje'+time+'" class="btn btn-sm btn-primary2  "><i class="fas fa-check"></i> Cerrar</button>'+
								'</div>'+
							'</div>'+
						'</div>'+
					'</div>';
	//SE AGREGA AL BODY
	$("body").append(modal);

	//PARA ELIMINAR TODO Y QUE NO QUEDE NADA DUPLICADO
	$('#'+idModal+time).off().on('hidden.bs.modal', function (e) {
		$('#'+idModal+time).remove();
		$("body").removeAttr( "style" );
		$('.validity-tooltip').remove();
		// CHACHI CULIAO FIXED PANTALLA SCROLL
		if ($(".modal")[0]) {
		   $("body").addClass("modal-open");
		}
		// if(typeof functionAceptar === "function")
		// 		functionAceptar();
	});

	$("#btnEquis").click(function(){
		$('#'+idModal+time).modal('hide').data('bs.modal', null);
		if(typeof functionAceptar === "function")
			functionAceptar();
	});

	//SE HACE MODAL Y SE LE SETEAN LAS OPCIONES
	$('#'+idModal+time).modal({
		keyboard: true,
		show: true,
		backdrop: true,
		backdrop: 'static'
	});

	$("#btn_aceptar_mensaje"+time).click(function(){
		$('#'+idModal+time).modal('hide').data('bs.modal', null);
		if(typeof functionAceptar === "function")
			functionAceptar();
	});
	return idModal+time;
}
// function modalFormulario(titulo, url, parametros, idModal, ancho, alto, botones, funcionSalir){
// 	/*************************EJEMPLO DE ENVIO DE BOTONES***************************************************************/
//    // 	var funcion = function miFuncion(){
//    //   			alert("funciona");
//    // 	}
//    // 	var botones = [
//    //      { id: 'btnAceptar', value: 'Aceptar', function: funcion, class: 'btn btn-success' }
//    //    ]
//    //****************************************************************************************************************/
//    var time = new Date().getTime();
// 	var id = idModal.substring(1);
// 	var modal ='<div id="'+id+'" class="modal fade" tabindex="-1" role="dialog" >'+
// 				  		'<div class="modal-dialog" role="document" style="width: '+ancho+'; height:'+alto+';">'+
// 				    		'<div class="modal-content"  id="modalContenido" >'+
// 				      		'<div class="modal-header">'+
// 					        		'<span style="float:left; font-size: 18px;" class="glyphicon glyphicon-list" aria-hidden="true"></span>'+
// 					        		'<h5 style="margin-left: 23px;" class="modal-title"><b>'+titulo+'</b></h5>'+
// 				      		'</div>'+
// 						      '<div class="modal-body" id="'+id+time+'">'+
// 						      '</div>'+
// 						      '<div class="modal-footer">'+
// 						      '<button type="button" class="btn btn-default" id="cancelar'+time+'"> Cancelar</button>';
// 						      for (var btn in botones){
// 							     modal+='<button id="'+botones[btn].id+'" type="button" class="btnModalesFrm-'+id+" "+botones[btn].class+'">'+botones[btn].value+'</button>';
// 							   }
// 								modal+='</div>' +
// 					    	'</div>'+
// 					   '</div>'+
// 					'</div>';

// 	//SE AGREGA AL BODY
// 	$("body").append(modal);

// 	//CARGAMOS EL CONTENIDO

// 	var funcionModal = function(){
// 		//AGREGAMOS LOS EVENTOS A LAS MODALES
// 		for(var btn in botones){
// 			$("#"+botones[btn].id).click(botones[btn].function);
// 			//alert("#"+botones[btn].id);
// 		}
// 		//PARA ELIMINAR TODO Y QUE NO QUEDE NADA DUPLICADO
// 		$('#'+id).off().on('hidden.bs.modal', function (e) {
// 			$('#'+id).remove();
// 			$(".btnModalesFrm-"+id).off();
// 			$("body").removeAttr( "style" );
// 			$('.validity-tooltip').remove();
// 		})

// 		//SE HACE MODAL Y SE LE SETEAN LAS OPCIONES
// 		$('#'+id).modal({
// 			keyboard: false,
// 			show: true,
// 			backdrop: true,
// 			backdrop: 'static'
// 		})
// 		$("#cancelar"+time).click(function(){
// 			if(typeof funcionSalir !== "undefined")
// 				funcionSalir();
// 			else
//          	$('#'+id).modal( 'hide' ).data( 'bs.modal', null );
// 		});
// 	}
// 	ajaxContent(url, parametros, "#"+id+time, 'Cargando Formulario...', true, funcionModal);
// }
function modalFormularioIconos(titulo, url, parametros, idModal, tamaño, clase, icono, botones, funcionSalir){
	/**********************************EJEMPLO DE ENVIO DE BOTONES**********************************/
   	// 	var funcion = function miFuncion(){
   	//   	alert("funciona");
   	// 	}
	// var botones = [
	// 				{ id: 'btnPrimary', value: 'Primary', function: funcion, class: 'btn btn-primary' , icon: 'fas fa-times'},
	// 				{ id: 'btnSuccess', value: 'Confirmar', function: funcion, class: 'btn btn-success' , icon: 'fas fa-check'},
	// 				{ id: 'btnWarning', value: 'Warning', function: funcion, class: 'btn btn-warning' , icon: 'fas fa-plus'}
	// 				]
   	//****************************************************************************************************************/
	/**********************************TAMAÑOS MODAL**********************************/
	// - SM = modal-sm
	// - MD = modal-md
	// - LG = modal-lg
	// - XL = modal-xl
	/*********************************************************************************/
	var time = new Date().getTime();
	var id   = idModal.substring(1);
	switch(clase){
		case 'primary':
			clase_x = 'text-white';
			clase	= 'bg-primary '+clase_x;
		break;
		case 'secondary':
			clase_x = 'text-white';
			clase	= 'bg-secondary '+clase_x;
		break;
		case 'success':
			clase_x = 'text-white';
			clase	= 'bg-success '+clase_x;
		break;
		case 'info':
			clase_x = 'text-white';
			clase	= 'bg-info '+clase_x;
		break;
		case 'warning':
			clase_x = 'text-white';
			clase	= 'bg-warning '+clase_x;
		break;
		case 'danger':
			clase_x = 'text-white';
			clase	= 'bg-danger '+clase_x;
		break;
		case 'dark':
			clase_x = 'text-white';
			clase	= 'bg-dark '+clase_x;
		break;
		case 'light':
			clase	= 'bg-light';
			clase_x = 'text-dark';
		break;
		default:
			clase  = '';
			clase_x = 'text-dark';
		break;
	}
	if (icono == "") {
		icono = "fas fa-list";
	}
	var modal	= '<div id="'+id+'" class="modal fade" tabindex="-1" role="dialog">'+
						'<div class="modal-dialog '+tamaño+'" role="document">'+
							'<div class="modal-content border-0" id="modalContenido">'+
								'<div class="modal-header py-1 '+clase+'">'+
									'<h5 class="modal-title" id="exampleModalLabel"><i class="'+icono+'"></i> '+titulo+'</h5>'+
									'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="'+clase_x+'" aria-hidden="true">&times;</span></button>'+
								'</div>'+
								'<div class="modal-body" id="'+id+time+'">'+
						      	'</div>'+
								'<div class="modal-footer py-1">';
							    for (var btn in botones){
									if (botones[btn].icon != null) {
										icono_boton = botones[btn].icon;
									}else{
										icono_boton = 'fas fa-check';
									}
								    modal+='<button id="'+botones[btn].id+'" type="button" class="btnModalesFrm-'+id+" btn btn-sm "+botones[btn].class+'"><i class="'+icono_boton+'"></i> '+botones[btn].value+'</button>';
								}
						      	modal+='<button type="button" class="btn btn-sm btn-danger" id="cancelar'+time+'"><i class="fas fa-times"></i> Cancelar</button>';
								'</div>'+
							'</div>'+
						'</div>'+
					'</div>';

	//SE AGREGA AL BODY
	$("body").append(modal);

	//CARGAMOS EL CONTENIDO
	var funcionModal = function(){
		//AGREGAMOS LOS EVENTOS A LAS MODALES
		for(var btn in botones){
			$("#"+botones[btn].id).click(botones[btn].function);
		}
		//PARA ELIMINAR TODO Y QUE NO QUEDE NADA DUPLICADO
		$('#'+id).off().on('hidden.bs.modal', function (e) {
			$('#'+id).remove();
			$(".btnModalesFrm-"+id).off();
			$("body").removeAttr( "style" );
			$('.validity-tooltip').remove();
			// SOLUCIONA PROBLEMA DE SCROLL AL ABRIR UN MODAL SOBRE OTRO MODAL
			if ($(".modal")[0]) {
			   $("body").addClass("modal-open");
			}
		});

		//SE HACE MODAL Y SE LE SETEAN LAS OPCIONES
		$('#'+id).modal({
			keyboard: false,
			show: true,
			backdrop: true,
			backdrop: 'static'
		});
		$("#cancelar"+time).click(function(){
			if(typeof funcionSalir !== "undefined")
				funcionSalir();
			else
         	$('#'+id).modal( 'hide' ).data( 'bs.modal', null );
		});
	}
	ajaxContent(url, parametros, "#"+id+time, 'Cargando Formulario...', true, funcionModal);
}
function modalFormularioSinCerrarnuevo(titulo, url, parametros, idModal, tamaño, clase, icono, botones, funcionSalir){
	/**********************************EJEMPLO DE ENVIO DE BOTONES**********************************/
   	// 	var funcion = function miFuncion(){
   	//   	alert("funciona");
   	// 	}
	// var botones = [
	// 				{ id: 'btnPrimary', value: 'Primary', function: funcion, class: 'btn btn-primary' , icon: 'fas fa-times'},
	// 				{ id: 'btnSuccess', value: 'Confirmar', function: funcion, class: 'btn btn-success' , icon: 'fas fa-check'},
	// 				{ id: 'btnWarning', value: 'Warning', function: funcion, class: 'btn btn-warning' , icon: 'fas fa-plus'}
	// 				]
   	//****************************************************************************************************************/
	/**********************************TAMAÑOS MODAL**********************************/
	// - SM = modal-sm
	// - MD = modal-md
	// - LG = modal-lg
	// - XL = modal-xl
	/*********************************************************************************/
	var time = new Date().getTime();
	var id   = idModal.substring(1);
	switch(clase){
		case 'primary':
			clase_x = 'text-white';
			clase	= 'bg-primary '+clase_x;
		break;
		case 'secondary':
			clase_x = 'text-white';
			clase	= 'bg-secondary '+clase_x;
		break;
		case 'success':
			clase_x = 'text-white';
			clase	= 'bg-success '+clase_x;
		break;
		case 'info':
			clase_x = 'text-white';
			clase	= 'bg-info '+clase_x;
		break;
		case 'warning':
			clase_x = 'text-white';
			clase	= 'bg-warning '+clase_x;
		break;
		case 'danger':
			clase_x = 'text-white';
			clase	= 'bg-danger '+clase_x;
		break;
		case 'dark':
			clase_x = 'text-white';
			clase	= 'bg-dark '+clase_x;
		break;
		case 'light':
			clase	= 'bg-light';
			clase_x = 'text-dark';
		break;
		default:
			clase  = '';
			clase_x = 'text-dark';
		break;
	}
	if (icono == "") {
		icono = "fas fa-list";
	}
	var modal	= '<div id="'+id+'" class="modal fade" tabindex="-1" role="dialog">'+
						'<div class="modal-dialog '+tamaño+'" role="document">'+
							'<div class="modal-content border-0" id="modalContenido">'+
								'<div class="modal-header py-1 '+clase+'">'+
									'<h5 class="modal-title" id="exampleModalLabel"><i class="'+icono+'"></i> '+titulo+'</h5>'+
									'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="'+clase_x+'" aria-hidden="true">&times;</span></button>'+
								'</div>'+
								'<div class="modal-body" id="'+id+time+'">'+
						      	'</div>'+
								'<div class="modal-footer py-1">';
							    for (var btn in botones){
									if (botones[btn].icon != null) {
										icono_boton = botones[btn].icon;
									}else{
										icono_boton = 'fas fa-check';
									}
								    modal+='<button id="'+botones[btn].id+'" type="button" class="btnModalesFrm-'+id+" btn btn-sm "+botones[btn].class+'"><i class="'+icono_boton+'"></i> '+botones[btn].value+'</button>';
								}
								'</div>'+
							'</div>'+
						'</div>'+
					'</div>';

	//SE AGREGA AL BODY
	$("body").append(modal);

	//CARGAMOS EL CONTENIDO
	var funcionModal = function(){
		//AGREGAMOS LOS EVENTOS A LAS MODALES
		for(var btn in botones){
			$("#"+botones[btn].id).click(botones[btn].function);
		}
		//PARA ELIMINAR TODO Y QUE NO QUEDE NADA DUPLICADO
		$('#'+id).off().on('hidden.bs.modal', function (e) {
			$('#'+id).remove();
			$(".btnModalesFrm-"+id).off();
			$("body").removeAttr( "style" );
			$('.validity-tooltip').remove();
			// SOLUCIONA PROBLEMA DE SCROLL AL ABRIR UN MODAL SOBRE OTRO MODAL
			if ($(".modal")[0]) {
			   $("body").addClass("modal-open");
			}
		});

		//SE HACE MODAL Y SE LE SETEAN LAS OPCIONES
		$('#'+id).modal({
			keyboard: false,
			show: true,
			backdrop: true,
			backdrop: 'static'
		});
		$("#cancelar"+time).click(function(){
			if(typeof funcionSalir !== "undefined")
				funcionSalir();
			else
         	$('#'+id).modal( 'hide' ).data( 'bs.modal', null );
		});
	}
	ajaxContent(url, parametros, "#"+id+time, 'Cargando Formulario...', true, funcionModal);
}
function modalFormulario_noCabecera(titulo, url, parametros, idModal, tamaño, clase, icono, botones, funcionSalir){
	/**********************************EJEMPLO DE ENVIO DE BOTONES**********************************/
   	// 	var funcion = function miFuncion(){
   	//   	alert("funciona");
   	// 	}
	// var botones = [
	// 				{ id: 'btnPrimary', value: 'Primary', function: funcion, class: 'btn btn-primary' , icon: 'fas fa-times'},
	// 				{ id: 'btnSuccess', value: 'Confirmar', function: funcion, class: 'btn btn-success' , icon: 'fas fa-check'},
	// 				{ id: 'btnWarning', value: 'Warning', function: funcion, class: 'btn btn-warning' , icon: 'fas fa-plus'}
	// 				]
   	//****************************************************************************************************************/
	/**********************************TAMAÑOS MODAL**********************************/
	// - SM = modal-sm
	// - MD = modal-md
	// - LG = modal-lg
	// - XL = modal-xl
	/*********************************************************************************/
	var time = new Date().getTime();
	var id   = idModal.substring(1);
	switch(clase){
		case 'primary':
			clase_x = 'text-white';
			clase	= 'bg-primary '+clase_x;
		break;
		case 'secondary':
			clase_x = 'text-white';
			clase	= 'bg-secondary '+clase_x;
		break;
		case 'success':
			clase_x = 'text-white';
			clase	= 'bg-success '+clase_x;
		break;
		case 'info':
			clase_x = 'text-white';
			clase	= 'bg-info '+clase_x;
		break;
		case 'warning':
			clase_x = 'text-white';
			clase	= 'bg-warning '+clase_x;
		break;
		case 'danger':
			clase_x = 'text-white';
			clase	= 'bg-danger '+clase_x;
		break;
		case 'dark':
			clase_x = 'text-white';
			clase	= 'bg-dark '+clase_x;
		break;
		case 'light':
			clase	= 'bg-light';
			clase_x = 'text-dark';
		break;
		default:
			clase  = '';
			clase_x = 'text-dark';
		break;
	}
	if (icono == "") {
		icono = "fas fa-list";
	}
	var modal	= '<div id="'+id+'" class="modal fade" tabindex="-1" role="dialog">'+
						'<div class="modal-dialog '+tamaño+'" role="document">'+
							'<div class="modal-content border-0" id="modalContenido">'+
								'<div class="modal-header py-1 '+clase+'" style="border-bottom: 0px solid !important">'+
									'<h5 class="modal-title" id="exampleModalLabel"> '+titulo+'</h5>'+
									'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="'+clase_x+'" aria-hidden="true">&times;</span></button>'+
								'</div>'+
								'<div class="modal-body" style="padding-top : 0rem !important;" id="'+id+time+'">'+
						      	'</div>'+
								''+
							'</div>'+
						'</div>'+
					'</div>';

	//SE AGREGA AL BODY
	$("body").append(modal);

	//CARGAMOS EL CONTENIDO
	var funcionModal = function(){
		//AGREGAMOS LOS EVENTOS A LAS MODALES
		for(var btn in botones){
			$("#"+botones[btn].id).click(botones[btn].function);
		}
		//PARA ELIMINAR TODO Y QUE NO QUEDE NADA DUPLICADO
		$('#'+id).off().on('hidden.bs.modal', function (e) {
			$('#'+id).remove();
			$(".btnModalesFrm-"+id).off();
			$("body").removeAttr( "style" );
			$('.validity-tooltip').remove();
			// SOLUCIONA PROBLEMA DE SCROLL AL ABRIR UN MODAL SOBRE OTRO MODAL
			if ($(".modal")[0]) {
			   $("body").addClass("modal-open");
			}
		});

		//SE HACE MODAL Y SE LE SETEAN LAS OPCIONES
		$('#'+id).modal({
			keyboard: false,
			show: true,
			backdrop: true,
			backdrop: 'static'
		});
		$("#cancelar"+time).click(function(){
			if(typeof funcionSalir !== "undefined")
				funcionSalir();
			else
         	$('#'+id).modal( 'hide' ).data( 'bs.modal', null );
		});
	}
	ajaxContent(url, parametros, "#"+id+time, 'Cargando Formulario...', true, funcionModal);
}

function modalFormulario(titulo, url, parametros, idModal, tamaño, clase, icono, botones, funcionSalir){
	/**********************************EJEMPLO DE ENVIO DE BOTONES**********************************/
   	// 	var funcion = function miFuncion(){
   	//   	alert("funciona");
   	// 	}
	// var botones = [
	// 				{ id: 'btnPrimary', value: 'Primary', function: funcion, class: 'btn btn-primary' , icon: 'fas fa-times'},
	// 				{ id: 'btnSuccess', value: 'Confirmar', function: funcion, class: 'btn btn-success' , icon: 'fas fa-check'},
	// 				{ id: 'btnWarning', value: 'Warning', function: funcion, class: 'btn btn-warning' , icon: 'fas fa-plus'}
	// 				]
   	//****************************************************************************************************************/
	/**********************************TAMAÑOS MODAL**********************************/
	// - SM = modal-sm
	// - MD = modal-md
	// - LG = modal-lg
	// - XL = modal-xl
	/*********************************************************************************/
	var time = new Date().getTime();
	var id   = idModal.substring(1);
	switch(clase){
		case 'primary':
			clase_x = 'text-white';
			clase	= 'bg-primary '+clase_x;
		break;
		case 'secondary':
			clase_x = 'text-white';
			clase	= 'bg-secondary '+clase_x;
		break;
		case 'success':
			clase_x = 'text-white';
			clase	= 'bg-success '+clase_x;
		break;
		case 'info':
			clase_x = 'text-white';
			clase	= 'bg-info '+clase_x;
		break;
		case 'warning':
			clase_x = 'text-white';
			clase	= 'bg-warning '+clase_x;
		break;
		case 'danger':
			clase_x = 'text-white';
			clase	= 'bg-danger '+clase_x;
		break;
		case 'dark':
			clase_x = 'text-white';
			clase	= 'bg-dark '+clase_x;
		break;
		case 'light':
			clase	= 'bg-light';
			clase_x = 'text-dark';
		break;
		default:
			clase  = '';
			clase_x = 'text-dark';
		break;
	}
	if (icono == "") {
		icono = "fas fa-list";
	}
	var modal	= '<div id="'+id+'" class="modal fade" tabindex="-1" role="dialog">'+
						'<div class="modal-dialog '+tamaño+'" role="document">'+
							'<div class="modal-content border-0" id="modalContenido">'+
								'<div class="modal-header py-1 '+clase+'">'+
									'<h5 class="modal-title" id="exampleModalLabel"><i class="'+icono+'"></i> '+titulo+'</h5>'+
									'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="'+clase_x+'" aria-hidden="true">&times;</span></button>'+
								'</div>'+
								'<div class="modal-body" id="'+id+time+'">'+
						      	'</div>'+
								'<div class="modal-footer py-1">';
							    for (var btn in botones){
									if (botones[btn].icon != null) {
										icono_boton = botones[btn].icon;
									}else{
										icono_boton = 'fas fa-check';
									}
								    modal+='<button id="'+botones[btn].id+'" type="button" class="btnModalesFrm-'+id+" btn btn-sm "+botones[btn].class+'"><i class="'+icono_boton+'"></i> '+botones[btn].value+'</button>';
								}
						      	modal+='<button type="button" class="btn btn-sm btn-danger" id="cancelar'+time+'"><i class="fas fa-times"></i> Cerrar</button>';
								'</div>'+
							'</div>'+
						'</div>'+
					'</div>';

	//SE AGREGA AL BODY
	$("body").append(modal);

	//CARGAMOS EL CONTENIDO
	var funcionModal = function(){
		//AGREGAMOS LOS EVENTOS A LAS MODALES
		for(var btn in botones){
			$("#"+botones[btn].id).click(botones[btn].function);
		}
		//PARA ELIMINAR TODO Y QUE NO QUEDE NADA DUPLICADO
		$('#'+id).off().on('hidden.bs.modal', function (e) {
			$('#'+id).remove();
			$(".btnModalesFrm-"+id).off();
			$("body").removeAttr( "style" );
			$('.validity-tooltip').remove();
			// SOLUCIONA PROBLEMA DE SCROLL AL ABRIR UN MODAL SOBRE OTRO MODAL
			if ($(".modal")[0]) {
			   $("body").addClass("modal-open");
			}
		});

		//SE HACE MODAL Y SE LE SETEAN LAS OPCIONES
		$('#'+id).modal({
			keyboard: false,
			show: true,
			backdrop: true,
			backdrop: 'static'
		});
		$("#cancelar"+time).click(function(){
			if(typeof funcionSalir !== "undefined")
				funcionSalir();
			else
         	$('#'+id).modal( 'hide' ).data( 'bs.modal', null );
		});
	}
	ajaxContent(url, parametros, "#"+id+time, 'Cargando Formulario...', true, funcionModal);
}

function tabla3_1(referencia){
	/*jQuery.fn.dataTableExt.aTypes.unshift(
		function ( sData )
		{
			if (sData !== null && sData.match(/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/(19|20|21)\d\d$/))
			{
				return 'date-uk';
			}
			return null;
		}
   );*/
	var tabla = $(referencia).DataTable({
						"aLengthMenu": [10, 15,30,50,100],
						"iDisplayLength": 10,
						"stateSave": true,
						"autoWidth": false,
						"bSort": true,
						"aaSorting": [],
						responsive: true,
						"paging": false,
						"info": false,
						"searching": false,

						/*"columnDefs": [
                		{ "type": "date-uk", targets: 3 }
            		],*/

						language: {
							"decimal": ",",
            			"thousands": ".",
							"sProcessing":     "Procesando...",
							"sLengthMenu":     "Mostrar _MENU_ registros",
							"sZeroRecords":    "No se encontraron resultados",
							"sEmptyTable":     "Ningún dato disponible en esta tabla",
							"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
							"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
							"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
							"sInfoPostFix":    "",
							"sSearch":         "Filtrar tabla:",
							"sUrl":            "",
							"sInfoThousands":  ",",
							"sLoadingRecords": "Cargando...",
							"oPaginate": {
								"sFirst":    "Primero",
								"sLast":     "Último",
								"sNext":     "Siguiente",
								"sPrevious": "Anterior"
							},
							"oAria": {
								"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
								"sSortDescending": ": Activar para ordenar la columna de manera descendente"
							}
						},
						select: {
            style: 'multi'
        }
					});
	return tabla;
}

function tabla3(referencia) {
   // Verificar si la tabla ya está inicializada y destruirla si es necesario
   if ($.fn.DataTable.isDataTable(referencia)) {
       $(referencia).DataTable().clear().destroy(); // Limpiar y destruir la instancia previa
   }

   // Inicializar el DataTable nuevamente
   var tabla = $(referencia).DataTable({
       "aLengthMenu": [10, 15, 30, 50, 100],
       "iDisplayLength": 10,
       "stateSave": true,
       "autoWidth": false,
       "bSort": true,
       "aaSorting": [],
       responsive: true,
       language: {
           "decimal": ",",
           "thousands": ".",
           "sProcessing": "Procesando...",
           "sLengthMenu": "Mostrar _MENU_ registros",
           "sZeroRecords": "No se encontraron resultados",
           "sEmptyTable": "Ningún dato disponible en esta tabla",
           "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
           "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
           "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
           "sSearch": "Filtrar tabla:",
           "oPaginate": {
               "sFirst": "Primero",
               "sLast": "Último",
               "sNext": "Siguiente",
               "sPrevious": "Anterior"
           },
           "oAria": {
               "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
               "sSortDescending": ": Activar para ordenar la columna de manera descendente"
           }
       },
       select: {
           style: 'multi'
       }
   });
   return tabla;
}
function tabla4(referencia){
	/*jQuery.fn.dataTableExt.aTypes.unshift(
		function ( sData )
		{
			if (sData !== null && sData.match(/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/(19|20|21)\d\d$/))
			{
				return 'date-uk';
			}
			return null;
		}
   );*/
	var tabla = $(referencia).DataTable({
						"aLengthMenu": [10, 15,30,50,100],
						"iDisplayLength": 10,
						"stateSave": true,
						"autoWidth": false,
						"bSort": false,
						"aaSorting": [],
						"searching": false,
						responsive: true,
						"paging": false,
						"info": false,
						/*"columnDefs": [
                		{ "type": "date-uk", targets: 3 }
            		],*/

						language: {
							"decimal": ",",
            			"thousands": ".",
							"sProcessing":     "Procesando...",
							"sLengthMenu":     "Mostrar _MENU_ registros",
							"sZeroRecords":    "No se encontraron resultados",
							"sEmptyTable":     "Ningún dato disponible en esta tabla",
							"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
							"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
							"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
							"sInfoPostFix":    "",
							"sSearch":         "Filtrar tabla:",
							"sUrl":            "",
							"sInfoThousands":  ",",
							"sLoadingRecords": "Cargando...",
							"oPaginate": {
								"sFirst":    "Primero",
								"sLast":     "Último",
								"sNext":     "Siguiente",
								"sPrevious": "Anterior"
							},
							"oAria": {
								"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
								"sSortDescending": ": Activar para ordenar la columna de manera descendente"
							}
						},
						select: {
            style: 'multi'
        }
					});
	return tabla;
}
// function tabla(referencia){
// 	/*jQuery.fn.dataTableExt.aTypes.unshift(
// 		function ( sData )
// 		{
// 			if (sData !== null && sData.match(/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/(19|20|21)\d\d$/))
// 			{
// 				return 'date-uk';
// 			}
// 			return null;
// 		}
//    );*/
// 	var tabla = $(referencia).DataTable({
// 						"aLengthMenu": [10, 15,30,50,100],
// 						"iDisplayLength": 12,
// 						"stateSave": false,
// 						"autoWidth": true,
// 						"bSort": true,
// 						"aaSorting": [],
// 			        scrollX:        true,
// 			        scrollCollapse: true,
// 			        columnDefs: [
// 			            { width: 10, targets: 0 }
// 			        ],
// 						responsive: true,
// 						/*"columnDefs": [
//                 		{ "type": "date-uk", targets: 3 }
//             		],*/

// 						language: {
// 							"decimal": ",",
//             			"thousands": ".",
// 							"sProcessing":     "Procesando...",
// 							"sLengthMenu":     "Mostrar _MENU_ registros",
// 							"sZeroRecords":    "No se encontraron resultados",
// 							"sEmptyTable":     "Ningún dato disponible en esta tabla",
// 							"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
// 							"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
// 							"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
// 							"sInfoPostFix":    "",
// 							"sSearch":         "Filtrar tabla:",
// 							"sUrl":            "",
// 							"sInfoThousands":  ",",
// 							"sLoadingRecords": "Cargando...",
// 							"oPaginate": {
// 								"sFirst":    "Primero",
// 								"sLast":     "Último",
// 								"sNext":     "Siguiente",
// 								"sPrevious": "Anterior"
// 							},
// 							"oAria": {
// 								"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
// 								"sSortDescending": ": Activar para ordenar la columna de manera descendente"
// 							}
// 						},
// 						select: {
//             style: 'multi'
//         }
// 					});
// 	return tabla;
// }
function tablaNormal(referencia){
	/*jQuery.fn.dataTableExt.aTypes.unshift(
		function ( sData )
		{
			if (sData !== null && sData.match(/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/(19|20|21)\d\d$/))
			{
				return 'date-uk';
			}
			return null;
		}
   );*/
	var tabla = $(referencia).DataTable({

						"bStateSave": true,
						// "stateSave": false,
						// "autoWidth": true,
						"bSort": true,
						"aaSorting": [],
			        // scrollX:        true,
			        // scrollCollapse: true,
						/*"columnDefs": [
                		{ "type": "date-uk", targets: 3 }
            		],*/

						language: {
							"decimal": ",",
            			"thousands": ".",
							"sProcessing":     "Procesando...",
							"sLengthMenu":     "Mostrar _MENU_ registros",
							"sZeroRecords":    "No se encontraron resultados",
							"sEmptyTable":     "Ningún dato disponible en esta tabla",
							"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
							"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
							"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
							"sInfoPostFix":    "",
							"sSearch":         "Filtrar tabla:",
							"sUrl":            "",
							"sInfoThousands":  ",",
							"sLoadingRecords": "Cargando...",
							"oPaginate": {
								"sFirst":    "Primero",
								"sLast":     "Último",
								"sNext":     "Siguiente",
								"sPrevious": "Anterior"
							},
							"oAria": {
								"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
								"sSortDescending": ": Activar para ordenar la columna de manera descendente"
							}
						},
						select: {
            style: 'multi'
        }
					});
	return tabla;
}
function tablaNormalExcel(referencia){
	/*jQuery.fn.dataTableExt.aTypes.unshift(
		function ( sData )
		{
			if (sData !== null && sData.match(/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/(19|20|21)\d\d$/))
			{
				return 'date-uk';
			}
			return null;
		}
   );*/
	var tabla = $(referencia).DataTable({

						"bStateSave": true,
						dom: 'Bfrtip',
        buttons: ['excel'],
        buttons: {
        buttons: [
		        { extend: 'excel',
		            text:      '<i class="fas fa-file-excel"></i>&nbsp;&nbsp;Generar Excel',
		            className: 'btn btn-sm btn-outline-success mifuente col-lg-12 ',
		            init: function(api, node, config) {
       $(node).removeClass('dt-button')
    }
		        }
		    ]
		},

						// "stateSave": false,
						// "autoWidth": true,
						"bSort": true,
						"aaSorting": [],
			        // scrollX:        true,
			        // scrollCollapse: true,
						/*"columnDefs": [
                		{ "type": "date-uk", targets: 3 }
            		],*/

						language: {
							"decimal": ",",
            			"thousands": ".",
							"sProcessing":     "Procesando...",
							"sLengthMenu":     "Mostrar _MENU_ registros",
							"sZeroRecords":    "No se encontraron resultados",
							"sEmptyTable":     "Ningún dato disponible en esta tabla",
							"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
							"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
							"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
							"sInfoPostFix":    "",
							"sSearch":         "Filtrar tabla:",
							"sUrl":            "",
							"sInfoThousands":  ",",
							"sLoadingRecords": "Cargando...",
							"oPaginate": {
								"sFirst":    "Primero",
								"sLast":     "Último",
								"sNext":     "Siguiente",
								"sPrevious": "Anterior"
							},
							"oAria": {
								"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
								"sSortDescending": ": Activar para ordenar la columna de manera descendente"
							}
						},
						select: {
            style: 'multi'
        }
					});
	return tabla;
}

function tablaSimple(referencia){
	if(referencia.startsWith(".")){
		$(referencia).each(function( index, element ) {
			tablaSimple("#"+$(this).attr("id"));
		});
	}else{
		var tabla = $(referencia).DataTable({
							"aLengthMenu": [15,30,50,100],
							"iDisplayLength": 15,
							// "bPaginate": false,
							"stateSave": true,
							"autoWidth": false,//true
							"bLengthChange": false,
							"bSort": true,
							"bFilter": false,
							"aaSorting": [],
							language: {
								"decimal": ",",
	            				"thousands": ".",
								"sProcessing":     "Procesando...",
								"sLengthMenu":     "Mostrar _MENU_ registros",
								"sZeroRecords":    "No se encontraron resultados",
								"sEmptyTable":     "Ningún dato disponible en esta tabla",
								"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
								"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
								"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
								"sInfoPostFix":    "",
								"sSearch":         "Filtrar tabla:",
								"sUrl":            "",
								"sInfoThousands":  ",",
								"sLoadingRecords": "Cargando...",
								"oPaginate": {
									"sFirst":    "Primero",
									"sLast":     "Último",
									"sNext":     "Siguiente",
									"sPrevious": "Anterior"
								},
								"oAria": {
									"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
									"sSortDescending": ": Activar para ordenar la columna de manera descendente"
								}
							}
						});
	}
	return tabla;
}
function tablaSimple3(referencia,size){
	if(referencia.startsWith(".")){
		$(referencia).each(function( index, element ) {
			tablaSimple("#"+$(this).attr("id"));
		});
	}else{
		var tabla = $(referencia).DataTable({
						"searching": false,
						"paging": false,
						"info": false,
						"aLengthMenu": [10, 15,30,50,100],
						"iDisplayLength": 100,
						"stateSave": false,
        				"scrollCollapse": true,
						"autoWidth": true,
						"bInfo": false,
							"bLengthChange": false,
							"bSort": false,
							"bFilter": false,
							"aaSorting": [],
							language: {
								"decimal": ",",
	            				"thousands": ".",
								"sProcessing":     "Procesando...",
								"sLengthMenu":     "Mostrar _MENU_ registros",
								"sZeroRecords":    "No se encontraron resultados",
								"sEmptyTable":     "Ningún dato disponible en esta tabla",
								"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
								"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
								"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
								"sInfoPostFix":    "",
								"sSearch":         "Filtrar tabla:",
								"sUrl":            "",
								"sInfoThousands":  ",",
								"sLoadingRecords": "Cargando...",
								"oPaginate": {
									"sFirst":    "Primero",
									"sLast":     "Último",
									"sNext":     "Siguiente",
									"sPrevious": "Anterior"
								},
								"oAria": {
									"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
									"sSortDescending": ": Activar para ordenar la columna de manera descendente"
								}
							}
						});
	}
	return tabla;
}
function tablaReporte(referencia,titulo,archivo){
	var tabla = $(referencia).DataTable({
						language: {
							"decimal": ",",
					        "thousands": ".",
							"sProcessing":     "Procesando...",
							"sLengthMenu":     "Mostrar _MENU_ registros",
							"sZeroRecords":    "No se encontraron resultados",
							"sEmptyTable":     "Ningún dato disponible en esta tabla",
							"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
							"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
							"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
							"sInfoPostFix":    "",
							"sSearch":         "Filtrar tabla:",
							"sUrl":            "",
							"sInfoThousands":  ".",
							"sLoadingRecords": "Cargando...",
							"oPaginate": {
								"sFirst":    "Primero",
								"sLast":     "Último",
								"sNext":     "Siguiente",
								"sPrevious": "Anterior"
							},
							"oAria": {
								"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
								"sSortDescending": ": Activar para ordenar la columna de manera descendente"
							}
						},
				        dom: 'Bfrtip',
				        buttons: [
				            {
					            extend: 'excelHtml5',
					            text: 'EXCEL <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>',
					            title: titulo,
					            className:'btn btn-success',
 								filename: archivo,
 								autoFilter: true,
 								sheetName: 'Exported data'
					        },
				            {
				                extend: 'pdfHtml5',
				                text: 'PDF <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>',
				                title: titulo,
				                className:'btn btn-success',
				                filename: archivo,
				                orientation: 'landscape'
				            }
				        ]
					});
	return tabla;
}
function clearDataTable(referencia){//Elimina LocalStorage asociado a un dataTable
	localStorage.setItem( 'DataTables_'+referencia+"_"+window.location.pathname, "");
}

function enlaceBoton(identificador){// ENLAZA EL BOTON A LA TECLA ENTER
	$('.formularios').off().keydown(function(e) { //.formularios => Clase del Formulario.
		var key = e.which;
		if (typeof identificador == "undefined") {
			if (key == 13) {
				$(".enviar").click(); // .enviar => Clase del boton que gatilla el submit
			}
		}else{
			if (key == 13) {
				$(identificador).click(); // identificador => identificador del boton que gatilla el submit
			}
		}
	});
}

function unsetSesion(){//Elimina todas las variables de sesion.
	ajaxRequest(raiz+'/controllers/server/base_controller.php', "accion=unsetSesion", "POST", "text", 1);
}

function validar(referencia,tipo){
	var caracteres="";
   switch(tipo){
      case 'rut'      :   caracteres = "0123456789-kK";
      						  $(referencia).validCampoFranz(caracteres);
                          break;
      case 'email'    :   caracteres = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ.-_@";
      						  $(referencia).validCampoFranz(caracteres);
                          break;
      case 'numero'   :   caracteres = "0123456789";
      						  $(referencia).validCampoFranz(caracteres);
                          break;
	  case 'numero_punto'   :   caracteres = "0123456789.";
						  $(referencia).validCampoFranz(caracteres);
	   break;
	  case 'celular'   :   caracteres = "0123456789+";
						  $(referencia).validCampoFranz(caracteres);
	   break;
    case 'numero_comas':
      						  caracteres = "0123456789,";
      						  $(referencia).validCampoFranz(caracteres);
     							  break;
      case 'letras'   :   caracteres = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ ";
      						  $(referencia).validCampoFranz(caracteres);
                          break;
      case 'letras_numeros'  :
      						  caracteres = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ0123456789.:- ";
      						  $(referencia).validCampoFranz(caracteres);
                          break;

      case 'letras_numeros_1'  :
      						  caracteres = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ0123456789.:-,() ";
      						  $(referencia).validCampoFranz(caracteres);
                          break;


      case 'correo'   :   caracteres = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@_.-";
      						  $(referencia).validCampoFranz(caracteres);
                          break;
      case 'codigoPortal'   :
      						  caracteres = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-";
      						  $(referencia).validCampoFranz(caracteres);
                          break;
      case 'alfaNumerico'   :
      						  caracteres = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
      						  $(referencia).validCampoFranz(caracteres);
                      	  break;
      case 'nombreProducto' :
      						  caracteres = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789°/.,% ";
      						  $(referencia).validCampoFranz(caracteres);
                      	  break;
      case 'nombreProducto_1' :
      						  caracteres = "abcdefghijklmnopqrstuvwxyzñABCDEFGHIJKLMNOÑPQRSTUVWXYZ0123456789°/.,%<>() ";
      						  $(referencia).validCampoFranz(caracteres);
                      	  break;
  	   case 'decimal'   :
			  				  	  caracteres = "0123456789.";
			  				     $(referencia).validCampoFranz(caracteres);
  	   					     break;
 		case 'decimalPunto'   :
			  				  	  caracteres = "0123456789. ";
			  				     $(referencia).validCampoFranz(caracteres);
  	   					     break;

  	   case 'direccion'   :
			  				    caracteres = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789# ";
			  				    $(referencia).validCampoFranz(caracteres);
  	   					    break;
  	   case 'proveedor'   :
						  		caracteres = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789- ";
						  		$(referencia).validCampoFranz(caracteres);
						      break;
		case 'fecha'      :
								caracteres = "";
			  					$(referencia).validCampoFranz(caracteres);
		case 'texto_textarea' :
      						  caracteres = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789°/.,%>!¡$()?¿* ";
      						  $(referencia).validCampoFranz(caracteres);
                      	  break;
      break;

   }
   $(referencia).bind('paste', function(event) {
   	var pegado = event.originalEvent.clipboardData.getData('Text');
   	var text = $(referencia).val();
   	for (var i = 0; i < pegado.length; i++) {
   		if(caracteres.search(pegado[i])==-1){
	   		return false;
   		}
   	}
   });
   $(referencia).bind('drop', function(event) {
	   return false;
   });

   $(referencia).blur(function() {
   	var string = $(this).val().trim();
   	string = string.replace(/\s+/g, ' ');
   	$(this).val(string);
   });
}

function calendario(identificador){
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
		format: "dd/mm/yyyy"
	};
	//var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
	//var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : identificador;
	var options={
		format: 'dd-mm-yyyy',
		orientation: 'auto left',
		container: $(identificador),
		todayHighlight: true,
		autoclose: true,
		language: 'es'
		/*orientation: 'bottom'*/
	};
	// alert(identificador.startsWith("."))
	if(identificador.startsWith(".")){
		$(identificador).each(function( index, element ) {
			calendario("#"+$(this).attr("id"));
		});
	}else
		$(identificador).datepicker(options);
}
function soloNumeros(e){
      var key = window.event ? e.which : e.keyCode;
      if (key < 48 || key > 57) {
        e.preventDefault();
        }

    }
    function soloNumerosComa(e){
      	var key = window.event ? e.which : e.keyCode;
      	// alert(key)
	    if ((key < 48 && key!=46) || key > 57 ) {
	      	e.preventDefault();
	    }

    }

    function pierdeFoco(e){
    var valor = e.value.replace(/^0*/, '');
    e.value = valor;
 }
function getColoresEstados(estado){
	switch(estado){
		case '3':
			solicitado = '#FFE000'; //PRE TABLA
		break;
		case '4':
			solicitado = '#898989'; //EN TABLA
		break;
		case '6':
			solicitado = '#0EB23A'; //REALIZADA
		break;
		case '8':
			solicitado = '#F11313'; //SUSPENDIDA
		break;
		case '13':
			solicitado = '#DAF7A6'; //INGRESO PABELLON
		break;
		case '14':
			solicitado = '#FFC300'; //INGRESO QUIROFANO
		break;
		case '15':
			solicitado = '#9643FF'; //SALIDA QUIROFANO
		break;
		case '16':
			solicitado = '#00C9FF'; //SALIDA PABELLON
		break;
		case '18':
			solicitado = '#FF5733'; //INICIO CIRUGIA
		break;
		case '19':
			solicitado = '#FF30F3'; //FIN CIRUGIA
		break;
		default:
			$solicitado ='';
			break;
	}
	return solicitado;
}
function ajaxForm(url,formulario, callBack, mensaje, parametros){
	var formData;
	if(typeof parametros !== "undefined")
		formData = parametros;
	else
		formData = new FormData($(formulario)[0]);//var formData = new FormData($('form')[0]);

	var retorno;
	$.ajax({
		url  : url,
		type : "POST",
		data : formData,
		dataType : "JSON",
		cache: false,
		contentType: false,
		processData: false,
		async: true,
		beforeSend: function() {
			if(typeof mensaje !== "undefined") {
				$.blockUI({
					baseZ: 2000,
					message: '<img height="70" src="/../../estandar/assets/img/loading-5.gif"/><label class="loadingBlock" style="font-weight:bold; color:white">'+mensaje+'</label>',
					css: {
						border: 'none',
						backgroundColor:'transparent'
					}
				});
			}
		}
	}).done(function(response){
		var go = true;
		if(response.status=="sesion_expirada"){
			go = false;
			var fn = function(){ view("#contenido"); }
         modalMensaje("Sesión Expirada", response.message, "error_sesion", 500, 300, 'panel-danger', fn);
			$.unblockUI();
		}
		callBack(response);
		$.unblockUI();
	}).fail(function(){
		$.unblockUI();
	});
	//return retorno;
}

function formatear(string){
    var retorno = "";
    var guion="";
    var string = String(string);
    if(string[0]=="-"){
    	guion = string.substring(0,1);
    	string = string.substring(1);
    }
    for (var j, i = string.length - 1, j = 0; i >= 0; i--, j++)
     retorno = string.charAt(i) + ((j > 0) && (j % 3 == 0)? ".": "") + retorno;

  	 if(guion=="-"){
  	 	retorno=guion+retorno;
  	 }
    return retorno;
}

function quitarFormatoNumeros(string){
	var valor = string.trim();
	valor = valor.replace('/./g', '');
	return valor;
}

function remplazar( pajar, aguja, reemplaza ){
  while (pajar.toString().indexOf(aguja) != -1)
      pajar = pajar.toString().replace(aguja,reemplaza);
  return pajar;
}

function  removerValidity(){
  $.validity.start();
  $.validity.end();
}
function showFile(file, ancho, alto){
	window.open(file, "iddd","width="+ancho+",height="+alto+",menubar=no,status=no,titlebar=yes,clearcache=yes");
}
function sumoSelect(identificador){
	$(identificador).SumoSelect({
		placeholder: 'Seleccione...',
		csvDispCount: 3,
		captionFormat:'{0} Seleccionados',
		captionFormatAllSelected:'{0},, Todos seleccionados',
		floatWidth: 400,
		forceCustomRendering: false,
		nativeOnDevice: ['Android', 'BlackBerry', 'iPhone', 'iPad', 'iPod', 'Opera Mini', 'IEMobile', 'Silk'],
		outputAsCSV: false,
		csvSepChar: ',',
		okCancelInMulti: false,
		triggerChangeCombined: false,
		selectAll: false,
		search: true,
		searchText: 'Buscar...',
		noMatch: 'Sin coincidencias para "{0}"',
		prefix: '',
		locale: ['OK', 'Cancel', 'Select All'],
		up: false
	});
}
function sumoSelectMultiple(identificador, prefijo){
	$(identificador).SumoSelect({
		placeholder: 'Seleccione...',
		csvDispCount: 3,
		captionFormat: '{0} Seleccionados',
		captionFormatAllSelected: 'Todos seleccionados',
		forceCustomRendering: true,
		nativeOnDevice: ['Android', 'BlackBerry', 'iPhone', 'iPad', 'iPod', 'Opera Mini', 'IEMobile', 'Silk'],
		outputAsCSV: false,
		csvSepChar: ',',
		okCancelInMulti: true,
		triggerChangeCombined: true,
		selectAll: true,
		search: true,
		searchText: 'Buscar...',
		noMatch: 'Sin coincidencias para "{0}"',
		prefix: prefijo,
		locale: ['OK', 'Cancelar', 'Seleccionar Todos']
	});
}
function validaFecha(fechaString){
   var fecha = remplazar(fechaString, "-", "/" );
   var regexLote = /^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/;
   return regexLote.test(fecha)
}
function fechaBootstrap(identificador){ // PIDE MINDATE
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
    $(identificador).datetimepicker({
        pickTime: false,
        dateFormat: 'dd:mm:yyyy',
        autoclose: true,
        minView: 2,
        minDate: new Date(),
    });
}
function fechaBootstrapMaxHoy(identificador){ // PIDE MINDATE
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
    $(identificador).datetimepicker({
        pickTime: false,
        dateFormat: 'dd:mm:yyyy',
        autoclose: true,
        minView: 2,
        maxDate: new Date(),
    });
}
function fechaBootstrapMax(identificador,max){ // PIDE MINDATE
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
    $(identificador).datetimepicker({
        pickTime: false,
        dateFormat: 'dd:mm:yyyy',
        minView: 2,
        maxDate: max,
    });
}
function fechaBloqueadaBootstrap(identificador,fechas,minimo){ // PIDE MINDATE
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
    $(identificador).datetimepicker({
        pickTime: false,
        dateFormat: 'dd:mm:yyyy',
        autoclose: true,
        minView: 2,
        minDate: minimo,
        disabledDates: fechas,
    });
}
function fechaBloqueadaBootstrap2(identificador,minimo){ // PIDE MINDATE
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
    $(identificador).datetimepicker({
        pickTime: false,
        dateFormat: 'dd:mm:yyyy',
        autoclose: true,
        minView: 2,
        minDate: minimo
    });
}
function fechaBootstrapNormal(identificador){ // NO PIDE MINDATE
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
    $(identificador).datetimepicker({
        pickTime: false,
        dateFormat: 'dd:mm:yyyy',
        autoclose: true,
        minView: 2,
    });
}
function fechaBootstrapMes(identificador){ // NO PIDE MINDATE
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
        format: "mm-yyyy",
    	startView: "months",
    	minViewMode: "months"
    };
    $(identificador).datetimepicker({
        pickTime: false,
        dateFormat: 'mm:yyyy',
        autoclose: true,
        startView: 1,
  		minViewMode: 1
    });
}
function fechaHoraBootstrap(identificador){ // NO PIDE MINDATE
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
    $(identificador).datetimepicker({
        pickTime: true,
        dateFormat: 'dd:mm:yyyy',
        autoclose: true,
        minView: 2,
    });
}
function fechaBootstrapLinked(identificador1,identificador2){ // NO PIDE MINDATE Y ES LINKED
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
    $(identificador1).datetimepicker({
        pickTime: false,
        dateFormat: 'dd:mm:yyyy',
        autoclose: true,
        minView: 2/*,
        minDate: new Date(),*/
    });
    $(identificador2).datetimepicker({
        pickTime: false,
        dateFormat: 'dd:mm:yyyy',
        autoclose: true,
        minView: 2,
    });
    $(identificador1).on("dp.change", function (e) {
        $(identificador2).data("DateTimePicker").setMinDate(e.date);
    });
    $(identificador2).on("dp.change", function (e) {
        $(identificador1).data("DateTimePicker").setMaxDate(e.date);
    });
}
function fechaBootstrapLinked2(identificador1,identificador2){ // NO PIDE MINDATE Y ES LINKED
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
    $(identificador1).datetimepicker({
        pickTime: false,
        dateFormat: 'dd:mm:yyyy',
        autoclose: true,
        minView: 2,
        minDate: '03-09-2020',
        maxDate: new Date()
    });
    $(identificador2).datetimepicker({
        pickTime: false,
        dateFormat: 'dd:mm:yyyy',
        autoclose: true,
        minView: 2,
        maxDate: new Date()
    });
    $(identificador1).on("dp.change", function (e) {
        $(identificador2).data("DateTimePicker").setMinDate(e.date);
    });
    $(identificador2).on("dp.change", function (e) {
        $(identificador1).data("DateTimePicker").setMaxDate(e.date);
    });
}
function fechaBootstrapLinked3(identificador1,identificador2){ // NO PIDE MINDATE Y ES LINKED
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
    $(identificador1).datetimepicker({
        pickTime: false,
        dateFormat: 'dd:mm:yyyy',
        autoclose: true,
        minView: 2,
        maxDate: new Date()
    });
    $(identificador2).datetimepicker({
        pickTime: false,
        dateFormat: 'dd:mm:yyyy',
        autoclose: true,
        minView: 2,
        maxDate: new Date()
    });
    $(identificador1).on("dp.change", function (e) {
        $(identificador2).data("DateTimePicker").setMinDate(e.date);
    });
    $(identificador2).on("dp.change", function (e) {
        $(identificador1).data("DateTimePicker").setMaxDate(e.date);
    });
}
function fechamiuntos(identificador){
	$(identificador).datetimepicker({
        timeOnly: true,
        pickDate: false,
        format: 'HH:mm',
        disabledTimeIntervals: [[moment({ h: 0 }), moment({ h: 6 })], [moment({ h: 17, m: 30 }), moment({ h: 24 })]],
        enabledHours: [6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
        minuteStepping: 30,
        defaultDate: '00:00',
    });
}
function fechamiuntosLinked(identificador1,identificador2){
	$(identificador1).datetimepicker({
        timeOnly: true,
        pickDate: false,
        format: 'HH:mm',
        disabledTimeIntervals: [[moment({ h: 0 }), moment({ h: 6 })], [moment({ h: 17, m: 30 }), moment({ h: 24 })]],
        enabledHours: [6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
        minuteStepping: 30,
        defaultDate: '00:00',
    });
    $(identificador2).datetimepicker({
        timeOnly: true,
        pickDate: false,
        format: 'HH:mm',
        disabledTimeIntervals: [[moment({ h: 0 }), moment({ h: 6 })], [moment({ h: 17, m: 30 }), moment({ h: 24 })]],
        enabledHours: [6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
        minuteStepping: 30,
    });
    $(identificador1).on("dp.change", function (e) {
        $(identificador2).data("DateTimePicker").setMinDate(e.date._i);
    });
    $(identificador2).on("dp.change", function (e) {
        $(identificador1).data("DateTimePicker").setMaxDate(e.date._i);
    });
}
function tablaScrollVertical(referencia,alto,mensaje){
	var tabla = $(referencia).DataTable({
		"scrollY":        alto,
        "scrollCollapse": true,
        "paging":         false,
        "destroy": true,
		"bDestroy": true,
		"aaSorting": [],
		"searching": false,
		"ordering": false,
        language: {
            "decimal": ",",
            "thousands": ".",
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "",
            "sEmptyTable":     mensaje,
            "sInfo":           "",
            "sInfoEmpty":      "",
            "sInfoFiltered":   "Mostrando _END_ indicaciones de un total de _MAX_ ",
            "sInfoPostFix":    "",
            "sSearch":         "Filtrar tabla:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando..."
        }
	});
	return tabla;
}

function horaBootstrap(identificador){
	$(identificador).datetimepicker({
    	dateFormat: '',
    	timeFormat: 'hh:mm tt',
    	timeOnly: true,
    	pickDate: false
	});
}
$.fn.extend({
	animateCss: function (animationName) {
		var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
		this.addClass(' ' + animationName).one(animationEnd, function() {
			$(this).removeClass(' ' + animationName);
		});
		return this;
	}
});

function generarPlanillaExcel(url,parametros){
	// window.open(url+'?'+parametros,'_blank');
	window.open(url+'?'+parametros,'planilla_extendida','toolbar=0,location=0, directories=0,status=0,menubar=0,scrollbars=1,resizable=1,left=0,top=0,height=600,width=850');
}


function modalFormulario_indice (titulo, url, parametros, idModal, tamaño, clase, icono, botones, funcionSalir){
	/**********************************EJEMPLO DE ENVIO DE BOTONES**********************************/
   	// 	var funcion = function miFuncion(){
   	//   	alert("funciona");
   	// 	}
	// var botones = [
	// 				{ id: 'btnPrimary', value: 'Primary', function: funcion, class: 'btn btn-primary' , icon: 'fas fa-times'},
	// 				{ id: 'btnSuccess', value: 'Confirmar', function: funcion, class: 'btn btn-success' , icon: 'fas fa-check'},
	// 				{ id: 'btnWarning', value: 'Warning', function: funcion, class: 'btn btn-warning' , icon: 'fas fa-plus'}
	// 				]
   	//****************************************************************************************************************/
	/**********************************TAMAÑOS MODAL**********************************/
	// - SM = modal-sm
	// - MD = modal-md
	// - LG = modal-lg
	// - XL = modal-xl
	/*********************************************************************************/
	var time = new Date().getTime();
	var id   = idModal.substring(1);
	switch(clase){
		case 'primary':
			clase_x = 'text-white';
			clase	= 'bg-primary '+clase_x;
		break;
		case 'secondary':
			clase_x = 'text-white';
			clase	= 'bg-secondary '+clase_x;
		break;
		case 'success':
			clase_x = 'text-white';
			clase	= 'bg-success '+clase_x;
		break;
		case 'info':
			clase_x = 'text-white';
			clase	= 'bg-info '+clase_x;
		break;
		case 'warning':
			clase_x = 'text-white';
			clase	= 'bg-warning '+clase_x;
		break;
		case 'danger':
			clase_x = 'text-white';
			clase	= 'bg-danger '+clase_x;
		break;
		case 'dark':
			clase_x = 'text-white';
			clase	= 'bg-dark '+clase_x;
		break;
		case 'light':
			clase	= 'bg-light';
			clase_x = 'text-dark';
		break;
		default:
			clase  = '';
			clase_x = 'text-dark';
		break;
	}
	if (icono == "") {
		icono = "fas fa-list";
	}
	var modal	= '<div id="'+id+'" class="modal fade" tabindex="-1" role="dialog">'+
						'<div class="modal-dialog '+tamaño+'" role="document">'+
							'<div class="modal-content border-0" id="modalContenido">'+
								'<div class="modal-header py-1 '+clase+'">'+
									'<h5 class="modal-title" id="exampleModalLabel"><i class="'+icono+'"></i> '+titulo+'</h5>'+
									'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="'+clase_x+'" aria-hidden="true">&times;</span></button>'+
								'</div>'+
								'<div class="modal-body" id="'+id+time+'">'+
						      	'</div>'+
								'<div class="modal-footer py-1">';
							    for (var btn in botones){
									if (botones[btn].icon != null) {
										icono_boton = botones[btn].icon;
									}else{
										icono_boton = 'fas fa-check';
									}
								    modal+='<button id="'+botones[btn].id+'" type="button" class="btnModalesFrm-'+id+" btn btn-sm "+botones[btn].class+'"><i class="'+icono_boton+'"></i> '+botones[btn].value+'</button>';
								}
							'</div>'+
						'</div>'+
					'</div>';

	//SE AGREGA AL BODY
	$("body").append(modal);

	//CARGAMOS EL CONTENIDO
	var funcionModal = function(){
		//AGREGAMOS LOS EVENTOS A LAS MODALES
		for(var btn in botones){
			$("#"+botones[btn].id).click(botones[btn].function);
		}
		//PARA ELIMINAR TODO Y QUE NO QUEDE NADA DUPLICADO
		$('#'+id).off().on('hidden.bs.modal', function (e) {
			$('#'+id).remove();
			$(".btnModalesFrm-"+id).off();
			$("body").removeAttr( "style" );
			$('.validity-tooltip').remove();

			// CHACHI CULIAO FIXED PANTALLA SCROLL
			if ($(".modal")[0]) {
			   $("body").addClass("modal-open");
			}
		});

		//SE HACE MODAL Y SE LE SETEAN LAS OPCIONES
		$('#'+id).modal({
			keyboard: false,
			show: true,
			backdrop: true,
			backdrop: 'static'
		});
		$("#cancelar"+time).click(function(){
			if(typeof funcionSalir !== "undefined")
				funcionSalir();
			else
         	$('#'+id).modal( 'hide' ).data( 'bs.modal', null );
		});
	}
	ajaxContent(url, parametros, "#"+id+time, 'Cargando Formulario...', true, funcionModal);
}
function modalFormulario_indice2 (titulo, url, parametros, idModal, tamaño, clase, icono, botones, funcionSalir){
	/**********************************EJEMPLO DE ENVIO DE BOTONES**********************************/
   	// 	var funcion = function miFuncion(){
   	//   	alert("funciona");
   	// 	}
	// var botones = [
	// 				{ id: 'btnPrimary', value: 'Primary', function: funcion, class: 'btn btn-primary' , icon: 'fas fa-times'},
	// 				{ id: 'btnSuccess', value: 'Confirmar', function: funcion, class: 'btn btn-success' , icon: 'fas fa-check'},
	// 				{ id: 'btnWarning', value: 'Warning', function: funcion, class: 'btn btn-warning' , icon: 'fas fa-plus'}
	// 				]
   	//****************************************************************************************************************/
	/**********************************TAMAÑOS MODAL**********************************/
	// - SM = modal-sm
	// - MD = modal-md
	// - LG = modal-lg
	// - XL = modal-xl
	/*********************************************************************************/
	var time = new Date().getTime();
	var id   = idModal.substring(1);
	switch(clase){
		case 'primary':
			clase_x = 'text-white';
			clase	= 'bg-primary '+clase_x;
		break;
		case 'secondary':
			clase_x = 'text-white';
			clase	= 'bg-secondary '+clase_x;
		break;
		case 'success':
			clase_x = 'text-white';
			clase	= 'bg-success '+clase_x;
		break;
		case 'info':
			clase_x = 'text-white';
			clase	= 'bg-info '+clase_x;
		break;
		case 'warning':
			clase_x = 'text-white';
			clase	= 'bg-warning '+clase_x;
		break;
		case 'danger':
			clase_x = 'text-white';
			clase	= 'bg-danger '+clase_x;
		break;
		case 'dark':
			clase_x = 'text-white';
			clase	= 'bg-dark '+clase_x;
		break;
		case 'light':
			clase	= 'bg-light';
			clase_x = 'text-dark';
		break;
		default:
			clase  = '';
			clase_x = 'text-dark';
		break;
	}
	if (icono == "") {
		icono = "fas fa-list";
	}
	var modal	= '<div id="'+id+'" class="modal fade" tabindex="-1" role="dialog">'+
						'<div class="modal-dialog '+tamaño+'" role="document">'+
							'<div class="modal-content border-0" id="modalContenido">'+
								'<div class="modal-header py-1 '+clase+'">'+
									'<h5 class="modal-title" id="exampleModalLabel"><i class="'+icono+'"></i> '+titulo+'</h5>'+
									'<div class="close" data-dismiss="modal" aria-label="Close" > <span class="'+clase_x+'" aria-hidden="true">&times;</span></div>'+
								'</div>'+
								'<div class="modal-body" id="'+id+time+'" style="background-color: #6fb8d8;">'+
						      	'</div>'+
								
						'</div>'+
					'</div>';

	//SE AGREGA AL BODY
	$("body").append(modal);

	//CARGAMOS EL CONTENIDO
	var funcionModal = function(){
		//AGREGAMOS LOS EVENTOS A LAS MODALES
		for(var btn in botones){
			$("#"+botones[btn].id).click(botones[btn].function);
		}
		//PARA ELIMINAR TODO Y QUE NO QUEDE NADA DUPLICADO
		$('#'+id).off().on('hidden.bs.modal', function (e) {
			$('#'+id).remove();
			$(".btnModalesFrm-"+id).off();
			$("body").removeAttr( "style" );
			$('.validity-tooltip').remove();

			// CHACHI CULIAO FIXED PANTALLA SCROLL
			if ($(".modal")[0]) {
			   $("body").addClass("modal-open");
			}
		});

		//SE HACE MODAL Y SE LE SETEAN LAS OPCIONES
		$('#'+id).modal({
			keyboard: false,
			show: true,
			backdrop: true,
			backdrop: 'static'
		});
		$("#cancelar"+time).click(function(){
			if(typeof funcionSalir !== "undefined")
				funcionSalir();
			else
         	$('#'+id).modal( 'hide' ).data( 'bs.modal', null );
		});
	}
	ajaxContent(url, parametros, "#"+id+time, 'Cargando Formulario...', true, funcionModal);
}
function tablaSimple_2(referencia){
	if(referencia.startsWith(".")){
		$(referencia).each(function( index, element ) {
			tablaSimple("#"+$(this).attr("id"));
		});
	}else{
		var tabla = $(referencia).DataTable({
							"aLengthMenu": [10,30,50,100],
							"iDisplayLength": 10,
							"stateSave": true,
							"autoWidth": true,
							"bLengthChange": false,
							"bSort": true,
							"bFilter": false,
							"destroy": true,
							"bDestroy": true,
							"aaSorting": [],
							"bPaginate": false,
							 "info":     false,
							language: {
								"decimal": ",",
	            			"thousands": ".",
								"sProcessing":     "Procesando...",
								"sLengthMenu":     "Mostrar _MENU_ registros",
								"sZeroRecords":    "No se encontraron resultados",
								"sEmptyTable":     "Ningún dato disponible en esta tabla",
								"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
								"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
								"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
								"sInfoPostFix":    "",
								"sSearch":         "Filtrar tabla:",
								"sUrl":            "",
								"sInfoThousands":  ",",
								"sLoadingRecords": "Cargando...",
								"oPaginate": {
									"sFirst":    "Primero",
									"sLast":     "Último",
									"sNext":     "Siguiente",
									"sPrevious": "Anterior"
								}
							}
						});
	}
	return tabla;
}


function quitarEspacio(string){
		string = string.trim();
		string = string.replace(/\s+/g, ' ');
		return string
}


function modalFormulario_2 (titulo, url, parametros, idModal, tamaño, clase, icono, botones, funcionSalir){
	/**********************************EJEMPLO DE ENVIO DE BOTONES**********************************/
   	// 	var funcion = function miFuncion(){
   	//   	alert("funciona");
   	// 	}
	// var botones = [
	// 				{ id: 'btnPrimary', value: 'Primary', function: funcion, class: 'btn btn-primary' , icon: 'fas fa-times'},
	// 				{ id: 'btnSuccess', value: 'Confirmar', function: funcion, class: 'btn btn-success' , icon: 'fas fa-check'},
	// 				{ id: 'btnWarning', value: 'Warning', function: funcion, class: 'btn btn-warning' , icon: 'fas fa-plus'}
	// 				]
   	//****************************************************************************************************************/
	/**********************************TAMAÑOS MODAL**********************************/
	// - SM = modal-sm
	// - MD = modal-md
	// - LG = modal-lg
	// - XL = modal-xl
	/*********************************************************************************/
	var time = new Date().getTime();
	var id   = idModal.substring(1);
	switch(clase){
		case 'primary':
			clase_x = 'text-white';
			clase	= 'bg-primary '+clase_x;
		break;
		case 'secondary':
			clase_x = 'text-white';
			clase	= 'bg-secondary '+clase_x;
		break;
		case 'success':
			clase_x = 'text-white';
			clase	= 'bg-success '+clase_x;
		break;
		case 'info':
			clase_x = 'text-white';
			clase	= 'bg-info '+clase_x;
		break;
		case 'warning':
			clase_x = 'text-white';
			clase	= 'bg-warning '+clase_x;
		break;
		case 'danger':
			clase_x = 'text-white';
			clase	= 'bg-danger '+clase_x;
		break;
		case 'dark':
			clase_x = 'text-white';
			clase	= 'bg-dark '+clase_x;
		break;
		case 'light':
			clase	= 'bg-light';
			clase_x = 'text-dark';
		break;
		default:
			clase  = '';
			clase_x = 'text-dark';
		break;
	}
	if (icono == "") {
		icono = "fas fa-list";
	}
	var modal	= '<div id="'+id+'" class="modal fade" tabindex="-1" role="dialog">'+
						'<div class="modal-dialog '+tamaño+'" role="document">'+
							'<div class="modal-content border-0" id="modalContenido">'+
								'<div class="modal-header py-1 '+clase+'">'+
									'<h5 class="modal-title" id="exampleModalLabel"><i class="'+icono+'"></i> '+titulo+'</h5>'+
									'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="'+clase_x+'" aria-hidden="true">&times;</span></button>'+
								'</div>'+
								'<div class="modal-body" id="'+id+time+'">'+
						      	'</div>'+
								'<div class="modal-footer py-1">';
							    for (var btn in botones){
									if (botones[btn].icon != null) {
										icono_boton = botones[btn].icon;
									}else{
										icono_boton = '';
									}
								    modal+='<button id="'+botones[btn].id+'" type="button" class="btnModalesFrm-'+id+" btn btn-sm "+botones[btn].class+'"><i class="'+icono_boton+'"></i> '+botones[btn].value+'</button>';
								}
							'</div>'+
						'</div>'+
					'</div>';

	//SE AGREGA AL BODY
	$("body").append(modal);

	//CARGAMOS EL CONTENIDO
	var funcionModal = function(){
		//AGREGAMOS LOS EVENTOS A LAS MODALES
		for(var btn in botones){
			$("#"+botones[btn].id).click(botones[btn].function);
		}
		//PARA ELIMINAR TODO Y QUE NO QUEDE NADA DUPLICADO
		$('#'+id).off().on('hidden.bs.modal', function (e) {
			$('#'+id).remove();
			$(".btnModalesFrm-"+id).off();
			$("body").removeAttr( "style" );
			$('.validity-tooltip').remove();
		});

		//SE HACE MODAL Y SE LE SETEAN LAS OPCIONES
		$('#'+id).modal({
			keyboard: false,
			show: true,
			backdrop: true,
			backdrop: 'static'
		});
		$("#cancelar"+time).click(function(){
			if(typeof funcionSalir !== "undefined")
				funcionSalir();
			else
         	$('#'+id).modal( 'hide' ).data( 'bs.modal', null );
		});
	}
	ajaxContent(url, parametros, "#"+id+time, 'Cargando Formulario...', true, funcionModal);
}
function startTimer(duration, display) {
	var timer = duration, minutes, seconds;
	var t;
	interval = setInterval(function () {
		minutes  = parseInt(timer / 60, 10)
		seconds  = parseInt(timer % 60, 10);

		minutes  = minutes < 10 ? "0" + minutes : minutes;
		seconds  = seconds < 10 ? "0" + seconds : seconds;

//		display.textContent = minutes + ":" + seconds;

		if(t == 0){
			$('#mensaje').html('');
			$('#mensaje').html('Usuario Inactivo');
			$('#logueado2').remove();
			clearInterval(interval);
		}else{
			t = --timer;
		}
	}, 1000);
}

function view_dau(content){
	var fn = function (retorno) {
		ajaxContent(retorno,'',content,'Cargando vista...', true);
	}
	ajaxRequest(raiz+'/controllers/server/view_controller.php', "position_id="+getPosition(), "POST", "text", 1,'Obteniendo vista...',fn, "Si" );
}
function modalFormularioSinCancelar(titulo, url, parametros, idModal, ancho, alto, botones, funcionSalir){
	/*************************EJEMPLO DE ENVIO DE BOTONES***************************************************************/
   // 	var funcion = function miFuncion(){
   //   			alert("funciona");
   // 	}
   // 	var botones = [
   //      { id: 'btnAceptar', value: 'Aceptar', function: funcion, class: 'btn btn-success' }
   //    ]
   //****************************************************************************************************************/
   $("#modal_").focus();
   var time = new Date().getTime();
	var id = idModal.substring(1);
	var modal ='<div id="'+id+'" class="modal fade" tabindex="-1" role="dialog" >'+
				  		'<div class="modal-dialog" role="document" style="width: '+ancho+'; height:'+alto+';">'+
				    		'<div class="modal-content"  id="modalContenido" >'+
				      		'<div class="modal-header">'+
					        		// '<span style="float:left; font-size: 18px;" class="glyphicon glyphicon-list" aria-hidden="true"></span>'+
					        		'<h5 style="margin-left: 23px;" class="modal-title"><b>'+titulo+'</b></h5>'+
				      		'</div>'+
						      '<div class="modal-body" id="'+id+time+'">'+
						      '</div>'+
						      '<div class="modal-footer">'+
						      '<button type="button" class="btn btn-danger" hidden data-dismiss="modal" id="cancelar'+time+'"> Cerrar</button>';
						      for (var btn in botones){
							     modal+='<button id="'+botones[btn].id+'" type="button" class="btnModalesFrm-'+id+" "+botones[btn].class+'">'+botones[btn].value+'</button>';
							   }
								modal+='</div>' +
					    	'</div>'+
					   '</div>'+
					'</div>';

	//SE AGREGA AL BODY
	$("body").append(modal);

	//CARGAMOS EL CONTENIDO

	var funcionModal = function(){
		//AGREGAMOS LOS EVENTOS A LAS MODALES
		for(var btn in botones){
			$("#"+botones[btn].id).click(botones[btn].function);
		}
		//PARA ELIMINAR TODO Y QUE NO QUEDE NADA DUPLICADO
		$('#'+id).off().on('hidden.bs.modal', function (e) {
			$('#'+id).remove();
			$(".btnModalesFrm-"+id).off();
			$("body").removeAttr( "style" );
			$('.validity-tooltip').remove();
		})

		//SE HACE MODAL Y SE LE SETEAN LAS OPCIONES
		$('#'+id).modal({
			keyboard: false,
			show: true,
			backdrop: true,
			backdrop: 'static'
		})

		if(typeof funcionSalir !== "undefined"){
			//EVENT QUE  SE LLEVA ACABO CUANDO CONFIRMA LA ALERTA
			$("#cancelar"+time).click(function(){
				funcionSalir();
			});
		}
	}
	ajaxContent(url, parametros, "#"+id+time, 'Cargando Formulario...', true, funcionModal);
}
function modalFormulario_noCabeceraIdentificacion(titulo, url, parametros, idModal, tamaño, clase, icono, botones, funcionSalir){
	/**********************************EJEMPLO DE ENVIO DE BOTONES**********************************/
   	// 	var funcion = function miFuncion(){
   	//   	alert("funciona");
   	// 	}
	// var botones = [
	// 				{ id: 'btnPrimary', value: 'Primary', function: funcion, class: 'btn btn-primary' , icon: 'fas fa-times'},
	// 				{ id: 'btnSuccess', value: 'Confirmar', function: funcion, class: 'btn btn-success' , icon: 'fas fa-check'},
	// 				{ id: 'btnWarning', value: 'Warning', function: funcion, class: 'btn btn-warning' , icon: 'fas fa-plus'}
	// 				]
   	//****************************************************************************************************************/
	/**********************************TAMAÑOS MODAL**********************************/
	// - SM = modal-sm
	// - MD = modal-md
	// - LG = modal-lg
	// - XL = modal-xl
	/*********************************************************************************/
	var time = new Date().getTime();
	var id   = idModal.substring(1);
	switch(clase){
		case 'primary':
			clase_x = 'text-white';
			clase	= 'bg-primary '+clase_x;
		break;
		case 'secondary':
			clase_x = 'text-white';
			clase	= 'bg-secondary '+clase_x;
		break;
		case 'success':
			clase_x = 'text-white';
			clase	= 'bg-success '+clase_x;
		break;
		case 'info':
			clase_x = 'text-white';
			clase	= 'bg-info '+clase_x;
		break;
		case 'warning':
			clase_x = 'text-white';
			clase	= 'bg-warning '+clase_x;
		break;
		case 'danger':
			clase_x = 'text-white';
			clase	= 'bg-danger '+clase_x;
		break;
		case 'dark':
			clase_x = 'text-white';
			clase	= 'bg-dark '+clase_x;
		break;
		case 'light':
			clase	= 'bg-light';
			clase_x = 'text-dark';
		break;
		default:
			clase  = '';
			clase_x = 'text-dark';
		break;
	}
	if (icono == "") {
		icono = "fas fa-list";
	}
	var modal	= '<div id="'+id+'" class="modal fade" tabindex="-1" role="dialog">'+
						'<div class="modal-dialog '+tamaño+'" role="document">'+
							'<div class="modal-content border-0" id="modalContenido">'+
								'<div class="modal-header py-1 '+clase+'" style="border-bottom: 0px solid !important">'+
									'<h5 class="modal-title" id="exampleModalLabel"> '+titulo+'</h5>'+
									''+
								'</div>'+
								'<div class="modal-body" style="padding-top : 0rem !important;" id="'+id+time+'">'+
						      	'</div>'+
								''+
							'</div>'+
						'</div>'+
					'</div>';

	//SE AGREGA AL BODY
	$("body").append(modal);

	//CARGAMOS EL CONTENIDO
	var funcionModal = function(){
		//AGREGAMOS LOS EVENTOS A LAS MODALES
		for(var btn in botones){
			$("#"+botones[btn].id).click(botones[btn].function);
		}
		//PARA ELIMINAR TODO Y QUE NO QUEDE NADA DUPLICADO
		$('#'+id).off().on('hidden.bs.modal', function (e) {
			$('#'+id).remove();
			$(".btnModalesFrm-"+id).off();
			$("body").removeAttr( "style" );
			$('.validity-tooltip').remove();
			// SOLUCIONA PROBLEMA DE SCROLL AL ABRIR UN MODAL SOBRE OTRO MODAL
			if ($(".modal")[0]) {
			   $("body").addClass("modal-open");
			}
		});

		//SE HACE MODAL Y SE LE SETEAN LAS OPCIONES
		$('#'+id).modal({
			keyboard: false,
			show: true,
			backdrop: true,
			backdrop: 'static'
		});
		$("#cancelar"+time).click(function(){
			if(typeof funcionSalir !== "undefined")
				funcionSalir();
			else
         	$('#'+id).modal( 'hide' ).data( 'bs.modal', null );
		});
	}
	ajaxContent(url, parametros, "#"+id+time, 'Cargando Formulario...', true, funcionModal);
}
function modalFormularioSinCancelar(titulo, url, parametros, idModal, ancho, alto, botones, funcionSalir){
	/*************************EJEMPLO DE ENVIO DE BOTONES***************************************************************/
   // 	var funcion = function miFuncion(){
   //   			alert("funciona");
   // 	}
   // 	var botones = [
   //      { id: 'btnAceptar', value: 'Aceptar', function: funcion, class: 'btn btn-success' }
   //    ]
   //****************************************************************************************************************/
   $("#modal_").focus();
   var time = new Date().getTime();
	var id = idModal.substring(1);
	var modal ='<div id="'+id+'" class="modal fade" tabindex="-1" role="dialog" >'+
				  		'<div class="modal-dialog" role="document" style="width: '+ancho+'; height:'+alto+';">'+
				    		'<div class="modal-content"  id="modalContenido" >'+
				      		'<div class="modal-header">'+
					        		// '<span style="float:left; font-size: 18px;" class="glyphicon glyphicon-list" aria-hidden="true"></span>'+
					        		'<h5 style="margin-left: 23px;" class="modal-title"><b>'+titulo+'</b></h5>'+
				      		'</div>'+
						      '<div class="modal-body" id="'+id+time+'">'+
						      '</div>'+
						      '<div class="modal-footer">'+
						      '<button type="button" class="btn btn-danger" hidden data-dismiss="modal" id="cancelar'+time+'"> Cerrar</button>';
						      for (var btn in botones){
							     modal+='<button id="'+botones[btn].id+'" type="button" class="btnModalesFrm-'+id+" "+botones[btn].class+'">'+botones[btn].value+'</button>';
							   }
								modal+='</div>' +
					    	'</div>'+
					   '</div>'+
					'</div>';

	//SE AGREGA AL BODY
	$("body").append(modal);

	//CARGAMOS EL CONTENIDO

	var funcionModal = function(){
		//AGREGAMOS LOS EVENTOS A LAS MODALES
		for(var btn in botones){
			$("#"+botones[btn].id).click(botones[btn].function);
		}
		//PARA ELIMINAR TODO Y QUE NO QUEDE NADA DUPLICADO
		$('#'+id).off().on('hidden.bs.modal', function (e) {
			$('#'+id).remove();
			$(".btnModalesFrm-"+id).off();
			$("body").removeAttr( "style" );
			$('.validity-tooltip').remove();
		})

		//SE HACE MODAL Y SE LE SETEAN LAS OPCIONES
		$('#'+id).modal({
			keyboard: false,
			show: true,
			backdrop: true,
			backdrop: 'static'
		})

		if(typeof funcionSalir !== "undefined"){
			//EVENT QUE  SE LLEVA ACABO CUANDO CONFIRMA LA ALERTA
			$("#cancelar"+time).click(function(){
				funcionSalir();
			});
		}
	}
	ajaxContent(url, parametros, "#"+id+time, 'Cargando Formulario...', true, funcionModal);
}

function redirigirSegunBanderaPiso ( banderapiso, idDau = null, idRce = null , ctacte = null, servicio_activo = null, pac_id = null) {


	let url = '', parametros = '';
	// let contenedor = '#contenidoDAU';
	let contenedor = '#contenido_gestionCama';
	// alert("url => "+url+" contenedor =>"+contenedor)
	// alert(banderapiso);
	switch ( banderapiso ) {
		case "MPISO" :
			url = `${raiz}/views/modules/mapa_piso_full/mapa_piso_full.php`;
			parametros = `tipoMapa=mapaAdultoPediatrico`;
			break;
		case "MPISOFULL" :
			url = `${raiz}/views/modules/mapa_piso_full/mapa_piso_full.php`;
			parametros = `tipoMapa=mapaFull`;
			break;
		case "MGINE" :
			url = `${raiz}/views/modules/mapa_piso_full/mapa_piso_full.php`;
			parametros = `tipoMapa=mapaGinecologico`;
			break;
		case "DETALLEDAU" :
			url = `${raiz}/views/modules/mapa_piso/detalle_dau/detalle_dau.php`;
			parametros = `dau_id=${idDau}`;
			break;
		case "RCE" :
			url = `${raiz}/views/modules/rce/rce.php`;
			parametros = `dau_id=${idDau}`;
			break;
		// case "CONSULTA" :
			// url = `${raiz}/views/modules/consulta/consulta.php`;
			// break;
		//new dau
		case "MEDICO" :
			url = `${raiz}/views/modules/gestion_hospital/gestion_hospital/detalle_paciente/acciones/rce_medico/rce_medico.php`
			parametros = `ctacte=${ctacte}&frm_servicio=${servicio_activo}&pac_id=${pac_id}`
			contenedor = '#contenido_gestionCama'
			break;
		/*
		case "CTACTE" :
			url = `${raiz}/views/modules/consulta/consulta.php`
			parametros = `servicio=${servicio_activo}&ctacte=${ctacte}`
			contenedor = '#contenido_gestionCama'
			break;
		*/
		case "MAPA_SERVICIO" :
			url = `GS${raiz}/views/modules/gestion_hospital/gestion_hospital/contenido_servicio/gestion_servicios.php`
		default:
			view_dau("#contenidoDAU");
			return;
			break;
	}
	ajaxContent(url, parametros, contenedor, 'Cargando...', true);
	$(".tooltip").remove();
}

function inicializaReloj(){
	// var fiveMinutes = 5, display = document.querySelector('#time');  //cuando son 5 segundos
	// var fiveMinutes = 600, display = document.querySelector('#time'); //cuando son 10 minutos
	// var fiveMinutes = 900, display = document.querySelector('#time');   //cuando son 15 minutos
	var fiveMinutes = 360, display = document.querySelector('#time');   //cuando son 15 minutos
	startTimer(fiveMinutes, display);
}


function modalMensajeBtnExit(titulo, mensaje, idModal, ancho, alto, clase, functionAceptar){
	var clasS;
	if(typeof clase !=="undefined"){
		clasS = clase;
	}

	var icon;
	switch(clase){
		case 'success':
			icon = 'ok';
		break;
		case 'warning':
			icon = 'warning-sign';
		break;
		case 'danger':
			icon = 'remove';
		break;
		case 'info':
			icon = 'info-sign';
		break;
		default:
			icon = 'info-sign';
		break;
	}

	var time = new Date().getTime();
	var modalMensaje ='<div id="'+idModal+time+'" class="modal fade" tabindex="-1" role="dialog" >'+
						  		'<div class="modal-dialog" role="document" style="width: '+ancho+'; height:'+alto+';">'+
						    		'<div class="modal-content"  id="modalContenidoMensaje" >'+
						      		'<div class="modal-header panel-heading modal-header-'+clasS+'">'+
							        		'<button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btnEquis"><span aria-hidden="true">&times;</span></button>'+
							        		'<span style="float:left; font-size: 18px;" class="glyphicon glyphicon-'+icon+'" aria-hidden="true"></span>'+
							        		'<h5 style="margin-left: 23px;" class="modal-title"><b>'+titulo+'</b></h5>'+
						      		'</div>'+
								      '<div class="modal-body" id="contenidoMensaje">'+ mensaje +
								      '</div>'+
								      '<div class="modal-footer">'+
									      '<button type="button" class="btn btn-primary" id="btn_aceptar_mensaje"> Aceptar</button>'+
										'</div>'+
							    	'</div>'+
							   '</div>'+
							'</div>';
	//SE AGREGA AL BODY
	$("body").append(modalMensaje);

	//PARA ELIMINAR TODO Y QUE NO QUEDE NADA DUPLICADO
	$('#'+idModal+time).off().on('hidden.bs.modal', function (e) {
		$('#'+idModal+time).remove();
		$("body").removeAttr( "style" );
		$('.validity-tooltip').remove();
		/*if(typeof functionAceptar === "function"){
			functionAceptar();
		}*/

	});

	$("#btnEquis").click(function(){
		$('#'+idModal+time).modal('hide').data('bs.modal', null);
		if(typeof functionAceptar === "function"){
			functionAceptar();
		}
	});

	//SE HACE MODAL Y SE LE SETEAN LAS OPCIONES
	$('#'+idModal+time).modal({
		keyboard: true,
		show: true,
		backdrop: true,
		backdrop: 'static'
	});

	$("#btn_aceptar_mensaje").click(function(){
		$('#'+idModal+time).modal('hide').data('bs.modal', null);
		if(typeof functionAceptar === "function"){
			functionAceptar();
		}
	});

	// Cuando Presiona ESC, cierra la modal y ejecuta la funcion
	if (window.addEventListener) {
	    window.addEventListener("keydown", compruebaTecla, false);
	} else if (document.attachEvent) {
	    document.attachEvent("onkeydown", compruebaTecla);
	}
	function compruebaTecla(evt){
	    var tecla = evt.which || evt.keyCode;
	    if(tecla == 27){
	        functionAceptar();
	    }
	}

}




function tablaSimple_new_vineta(referencia){
	if(referencia.startsWith(".")){
		$(referencia).each(function( index, element ) {
			tablaSimple("#"+$(this).attr("id"));
		});
	}else{
		var tabla = $(referencia).DataTable({
							"lengthChange": false,
							bLengthChange: false,
							"iDisplayLength": 5,
							bInfo: true,
							responsive: true,
							"bAutoWidth": false,
							"bFilter": false,
							"bStateSave": true,

							language: {
								"decimal": ",",
	            				"thousands": ".",
								"sProcessing":     "Procesando...",
								"sLengthMenu":     "Mostrar _MENU_ registros",
								"sZeroRecords":    "No se encontraron resultados",
								"sEmptyTable":     "Ningún dato disponible en esta tabla",
								"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
								"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
								"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
								"sInfoPostFix":    "",
								"sSearch":         "Filtrar tabla:",
								"sUrl":            "",
								"sInfoThousands":  ",",
								"sLoadingRecords": "Cargando...",
								"oPaginate": {
									"sFirst":    "Primero",
									"sLast":     "Último",
									"sNext":     "Siguiente",
									"sPrevious": "Anterior"
								},
								"oAria": {
									"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
									"sSortDescending": ": Activar para ordenar la columna de manera descendente"
								}
							}
						});
	}
	return tabla;
}

function tablaHistorial(referencia, tamaño, arriba, orden){
	var tabla = $(referencia).DataTable({
	//$('#hist_ambularoria_rce').DataTable({
    "dom": '<"top"' + arriba + '>rt<"bottom"p><"clear">',
	"aLengthMenu": [tamaño, 30, 50, 100],
	"iDisplayLength": tamaño,
	"ordering": orden,
	"stateSave": true,
	"autoWidth": false,
	"bSort": true,
	"aaSorting": [],

	"ordering": false,
    "scrollY": "250px",
    "scrollCollapse": true,
    "paging":   false,
    "search": {
    	"smart": false
 		 },
	language: {
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sInfo": "Mostrando _START_ al _END_ de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando 0 de 0 registros",
        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix": "",
        /*"sSearch": "Filtrar tabla:",*/
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
          "sFirst": "Primero",
          "sLast": "Último",
          "sNext": "Siguiente",
          "sPrevious": "Anterior"
        },
        "columnDefs": [ {
          "targets"  : 'no-sort',
          "orderable": false,
          "order": []
        }]
      }
  })
  return tabla;
};

function tablaRESimple(referencia){
	if(referencia.startsWith(".")){
		$(referencia).each(function( index, element ) {
			tablaRESimple("#"+$(this).attr("id"));
		});
	}else{
		var tabla = $(referencia).DataTable({
							"ordering": false,
							"info":     false,
							"bPaginate": false,
							"stateSave": true,
							"autoWidth": false,//true
							"bLengthChange": false,
							"bSort": true,
							"bFilter": false,
							"aaSorting": [],
							language: {
								"decimal": ",",
								"thousands": ".",
								"sProcessing":     "Procesando...",
								"sLengthMenu":     "Mostrar _MENU_ registros",
								"sZeroRecords":    "No se encontraron resultados",
								"sEmptyTable":     "Ningún dato disponible en esta tabla",
								"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
								"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
								"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
								"sInfoPostFix":    "",
								"sSearch":         "Filtrar tabla:",
								"sUrl":            "",
								"sInfoThousands":  ",",
								"sLoadingRecords": "Cargando...",
								"oPaginate": {
									"sFirst":    "Primero",
									"sLast":     "Último",
									"sNext":     "Siguiente",
									"sPrevious": "Anterior"
								},
								"oAria": {
									"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
									"sSortDescending": ": Activar para ordenar la columna de manera descendente"
								}
							}
						});
	}
	return tabla;
}


function modalFormularioReporte(titulo, url, parametros, idModal, tamaño, clase, icono, botones, funcionSalir){
	/**********************************EJEMPLO DE ENVIO DE BOTONES**********************************/
   	// 	var funcion = function miFuncion(){
   	//   	alert("funciona");
   	// 	}
	// var botones = [
	// 				{ id: 'btnPrimary', value: 'Primary', function: funcion, class: 'btn btn-primary' , icon: 'fas fa-times'},
	// 				{ id: 'btnSuccess', value: 'Confirmar', function: funcion, class: 'btn btn-success' , icon: 'fas fa-check'},
	// 				{ id: 'btnWarning', value: 'Warning', function: funcion, class: 'btn btn-warning' , icon: 'fas fa-plus'}
	// 				]
   	//****************************************************************************************************************/
	/**********************************TAMAÑOS MODAL**********************************/
	// - SM = modal-sm
	// - MD = modal-md
	// - LG = modal-lg
	// - XL = modal-xl
	/*********************************************************************************/
	var time = new Date().getTime();
	var id   = idModal.substring(1);
	switch(clase){
		case 'primary':
			clase_x = 'text-white';
			clase	= 'bg-primary '+clase_x;
		break;
		case 'secondary':
			clase_x = 'text-white';
			clase	= 'bg-secondary '+clase_x;
		break;
		case 'success':
			clase_x = 'text-white';
			clase	= 'bg-success '+clase_x;
		break;
		case 'info':
			clase_x = 'text-white';
			clase	= 'bg-info '+clase_x;
		break;
		case 'warning':
			clase_x = 'text-white';
			clase	= 'bg-warning '+clase_x;
		break;
		case 'danger':
			clase_x = 'text-white';
			clase	= 'bg-danger '+clase_x;
		break;
		case 'dark':
			clase_x = 'text-white';
			clase	= 'bg-dark '+clase_x;
		break;
		case 'light':
			clase	= 'bg-light';
			clase_x = 'text-dark';
		break;
		default:
			clase  = '';
			clase_x = 'text-dark';
		break;
	}
	if (icono == "") {
		icono = "fas fa-list";
	}
	var modal	= '<div id="'+id+'" class="modal fade" tabindex="-1" role="dialog">'+
						'<div class="modal-dialog '+tamaño+'" role="document">'+
							'<div class="modal-content border-0" id="modalContenido">'+
								'<div class="modal-header py-1 '+clase+'">'+
									'<h5 class="modal-title" id="exampleModalLabel"><i class="'+icono+'"></i> '+titulo+'</h5>'+
									'<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="'+clase_x+'" aria-hidden="true">&times;</span></button>'+
								'</div>'+
								'<div class="modal-body" id="'+id+time+'">'+
						      	'</div>'+
								'<div class="modal-footer py-1">';
							    for (var btn in botones){
									if (botones[btn].icon != null) {
										icono_boton = botones[btn].icon;
									}else{
										icono_boton = 'fas fa-check';
									}
								    modal+='<button id="'+botones[btn].id+'" type="button" class="btnModalesFrm-'+id+" btn btn-sm "+botones[btn].class+'"><i class="'+icono_boton+'"></i> '+botones[btn].value+'</button>';
								}
						      	modal+='<button type="button" class="btn btn-sm btn-danger" id="cancelar'+time+'"><i class="fas fa-times"></i> Cerrar</button>';
								'</div>'+
							'</div>'+
						'</div>'+
					'</div>';

	//SE AGREGA AL BODY
	$("body").append(modal);

	//CARGAMOS EL CONTENIDO
	var funcionModal = function(){
		//AGREGAMOS LOS EVENTOS A LAS MODALES
		for(var btn in botones){
			$("#"+botones[btn].id).click(botones[btn].function);
		}
		//PARA ELIMINAR TODO Y QUE NO QUEDE NADA DUPLICADO
		$('#'+id).off().on('hidden.bs.modal', function (e) {
			$('#'+id).remove();
			$(".btnModalesFrm-"+id).off();
			$("body").removeAttr( "style" );
			$('.validity-tooltip').remove();
			// SOLUCIONA PROBLEMA DE SCROLL AL ABRIR UN MODAL SOBRE OTRO MODAL
			if ($(".modal")[0]) {
			   $("body").addClass("modal-open");
			}
		});

		//SE HACE MODAL Y SE LE SETEAN LAS OPCIONES
		$('#'+id).modal({
			keyboard: false,
			show: true,
			backdrop: true,
			backdrop: 'static'
		});
		$("#cancelar"+time).click(function(){
			if(typeof funcionSalir !== "undefined")
				funcionSalir();
			else
         	$('#'+id).modal( 'hide' ).data( 'bs.modal', null );
		});
	}
	ajaxContent(url, parametros, "#"+id+time, 'Cargando Formulario...', true, funcionModal);
}

function fechaHoy(){
	var date = new Date();
	var aaaa = date.getFullYear();
	var dd   = date.getDate();
	var mm   = (date.getMonth()+1);//Enero 0

	if (dd < 10){
		dd = "0" + dd;
	}
	if (mm < 10){
		mm = "0" + mm;
	}
	var cur_day = dd+"-"+mm+"-"+aaaa;

	return cur_day;
}

function tablaNoArriba(referencia, tamaño, orden) {
	var tabla = $(referencia).DataTable(
		{
			"aLengthMenu": [10, 30, 50, 100],
			"iDisplayLength": 10,
			"ordering": orden,
			"stateSave": true,
			"autoWidth": false,
			"bSort": true,
			"aaSorting": [],

			language: {
				"sProcessing": "Procesando...",
				"sLengthMenu": "Mostrar _MENU_ registros",
				"sZeroRecords": "No se encontraron resultados",
				"sEmptyTable": "Ningún dato disponible en esta tabla",
				"sInfo": "Mostrando _START_ al _END_ de _TOTAL_ registros",
				"sInfoEmpty": "Mostrando 0 de 0 registros",
				"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
				"sInfoPostFix": "",
				"sSearch": "Filtrar tabla:",
				"sUrl": "",
				"sInfoThousands": ",",
				"sLoadingRecords": "Cargando...",
				"oPaginate": {
					"sFirst": "Primero",
					"sLast": "Último",
					"sNext": "Siguiente",
					"sPrevious": "Anterior"
				},
				"oAria": {
					"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
					"sSortDescending": ": Activar para ordenar la columna de manera descendente"
				}
			}
		});
	return tabla;
}


function modalFormularioMejorado(titulo, url, parametros, idModal, ancho, alto, botones, funcionSalir) {
	/*************************EJEMPLO DE ENVIO DE BOTONES***************************************************************/

	var time = new Date().getTime();
	var id = idModal.substring(1);
	var modal = '<div id="' + id + '" class="modal fade modalMejorado" tabindex="-1" role="dialog" >' +
		'<div class="modal-dialog" role="document" style="width: ' + ancho + ';">' +
		'<div class="modal-content contenidoModalMejorado"  id="modalContenido" >' +
		'<div class="modal-header">' +

		'<h5 style="margin-left: 23px;" class="modal-title"><b>' + titulo + '</b></h5>' +
		'</div>' +
		'<div class="modal-body cuerpoModalMejorado" id="' + id + time + '" style="height:' + alto + ';">' +
		'</div>' +
		'<div class="modal-footer">' +
		'<button type="button" class="btn btn-default" data-dismiss="modal" id="cancelar' + time + '"> Salir</button>';
	for (var btn in botones) {
		modal += '<button id="' + botones[btn].id + '" type="button" class="btnModalesFrm-' + id + " " + botones[btn].class + '">' + botones[btn].value + '</button>';
	}
	modal += '</div>' +
		'</div>' +
		'</div>' +
		'</div>';

	//SE AGREGA AL BODY
	$("body").append(modal);

	//CARGAMOS EL CONTENIDO

	var funcionModal = function () {
		//AGREGAMOS LOS EVENTOS A LAS MODALES
		for (var btn in botones) {
			$("#" + botones[btn].id).click(botones[btn].function);
		}
		//PARA ELIMINAR TODO Y QUE NO QUEDE NADA DUPLICADO
		$('#' + id).off().on('hidden.bs.modal', function (e) {
			$('#' + id).remove();
			$(".btnModalesFrm-" + id).off();
			$("body").removeAttr("style");
			$('.validity-tooltip').remove();
		})

		//SE HACE MODAL Y SE LE SETEAN LAS OPCIONES
		$('#' + id).modal({
			keyboard: false,
			show: true,
			backdrop: true,
			backdrop: 'static'
		})

		if (typeof funcionSalir !== "undefined") {
			//EVENT QUE  SE LLEVA ACABO CUANDO CONFIRMA LA ALERTA
			$("#cancelar" + time).click(function () {
				funcionSalir();
			});
		}
	}
	ajaxContent(url, parametros, "#" + id + time, 'Cargando Formulario...', true, funcionModal);
}
function modalFormularioMejoradoSinCerrar(titulo, url, parametros, idModal, ancho, alto, botones, funcionSalir) {
	/*************************EJEMPLO DE ENVIO DE BOTONES***************************************************************/

	var time = new Date().getTime();
	var id = idModal.substring(1);
	var modal = '<div id="' + id + '" class="modal fade modalMejorado" tabindex="-1" role="dialog" >' +
		'<div class="modal-dialog" role="document" style="width: ' + ancho + ';">' +
		'<div class="modal-content contenidoModalMejorado"  id="modalContenido" >' +
		'<div class="modal-header">' +

		'<h5 style="margin-left: 23px;" class="modal-title"><b>' + titulo + '</b></h5>' +
		'</div>' +
		'<div class="modal-body cuerpoModalMejorado" id="' + id + time + '" style="height:' + alto + ';">' +
		'</div>' +
		'</div>' +
		'</div>' +
		'</div>';

	//SE AGREGA AL BODY
	$("body").append(modal);

	//CARGAMOS EL CONTENIDO

	var funcionModal = function () {
		//AGREGAMOS LOS EVENTOS A LAS MODALES
		for (var btn in botones) {
			$("#" + botones[btn].id).click(botones[btn].function);
		}
		//PARA ELIMINAR TODO Y QUE NO QUEDE NADA DUPLICADO
		$('#' + id).off().on('hidden.bs.modal', function (e) {
			$('#' + id).remove();
			$(".btnModalesFrm-" + id).off();
			$("body").removeAttr("style");
			$('.validity-tooltip').remove();
		})

		//SE HACE MODAL Y SE LE SETEAN LAS OPCIONES
		$('#' + id).modal({
			keyboard: false,
			show: true,
			backdrop: true,
			backdrop: 'static'
		})

		if (typeof funcionSalir !== "undefined") {
			//EVENT QUE  SE LLEVA ACABO CUANDO CONFIRMA LA ALERTA
			$("#cancelar" + time).click(function () {
				funcionSalir();
			});
		}
	}
	ajaxContent(url, parametros, "#" + id + time, 'Cargando Formulario...', true, funcionModal);
}
function verificarRUT ( rut ) {

	let rutValido = $.Rut.validar(rut);

	if ( rutValido == false ) {

		return false;

	}

	rut     = $.Rut.quitarFormato(rut);

	rut     = rut.substring(0, rut.length-1);

	if(rut==0){
		return false;
	}

	return true;

}


function fechaBootstrapNormal_2(identificador) { // NO PIDE MINDATE
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

	$(identificador).datetimepicker({
		pickTime: false,
		dateFormat: 'dd:mm:yyyy',
		autoclose: true,
		minView: 2
	});
}


function loadIframe(iframeName, url) {
    var $iframe = $('#' + iframeName);
    if ($iframe.length) {
        $iframe.attr('src', url);
        return false;
    }
    return true;
}
