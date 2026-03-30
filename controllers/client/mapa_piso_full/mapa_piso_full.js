var parametroIdDau = {idDau : ''};
var categoriasMap = {};

categoriaMapeo.forEach(cat => {
    categoriasMap[cat.cat_nombre_mostrar] = parseInt(cat.cat_tiempo_maximo, 10);
});
$(document).ready(function(){
	const savedLiId = localStorage.getItem('selectedLI');
	if(savedLiId >0){
		$('#'+savedLiId).click();
	}
	$('.tuvieja').on('click', function(){
		localStorage.setItem('selectedLI', $(this).attr("id"));
	});
    // alert(horaServidorMapa)
	var horaServidorDate = new Date(horaServidorMapa);
    // alert(horaServidorDate)
    var contenidoTooltip ="";
    $('.creadorTool').each(function() {
        var $this = $(this);
        var tiempoCamaDesocupadaHidden = $this.find('.tiempoCamaDesocupadaHidden').val();
        $nombrePaciente = `${$this.find('.nombre_paciente').val()}`;
        if( $this.find('.nombreSocial').val() != ""){
        	$nombrePaciente = `<i class="fas fa-venus-mars " style="color:#dd3bd1;"></i> <b>${$this.find('.nombreSocial').val()} / </b>${$this.find('.nombre_paciente').val()}`;
        }
        var contenidoTooltip = `
            <strong>Nº DAU:</strong> ${$this.find('.dau_id').val()} <i class="fas fa-heartbeat text-danger"></i> <strong>R.U.N.:</strong> ${$this.find('.runPaciente').val()}<br>
            <strong>Paciente:</strong> ${$nombrePaciente}
            <i class="fas fa-birthday-cake text-primary" ></i> <strong>Edad:</strong> ${$this.find('.edadPaciente').val()} años<br>
            <strong>Categorización: </strong>${$this.find('.nombre_categorizacion').val()} (${$this.find('.fecha_categorizacion').val()})<br>
            <strong>Consulta:</strong> ${$this.find('.descripcion_consulta').val()}<br>
            <strong>Ingreso a Sala UE: </strong>${$this.find('.ingreso_sala').val()}<br>
        `;
        if( $this.find('.especialistas').val() != null){
        	contenidoTooltip += `
            <strong>Especialistas: </strong>${$this.find('.especialistas').val()}<br>`;
        }
        // if( $this.find('.dau_cat_obs_enfermera').val() != null){
        // 	contenidoTooltip += `
        //     <strong>Obs. Enfermera: </strong>${$this.find('.dau_cat_obs_enfermera').val()}<br>`;
        // }
        if( $this.find('.fecha_atencion2').val() != null){
        	var tiempo = "";
        	var fecha_atencion2 = $this.find('.fecha_atencion2').val();
        	// console.log(fecha_atencion2)
            var fecha_atencion2Date = new Date(fecha_atencion2);
            var diferencia = horaServidorDate - fecha_atencion2Date;
            var dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
            var horas = Math.floor((diferencia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutos = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));
            var segundos = Math.floor((diferencia % (1000 * 60)) / 1000);
            horas = horas.toString().padStart(2, '0');
            minutos = minutos.toString().padStart(2, '0');
            segundos = segundos.toString().padStart(2, '0');
            if( $this.find('.fecha_egreso2').val() == null){
            	if(dias > 0) {
	            	var tiempo = `${dias}d ${horas}:${minutos}:${segundos}`;
	            }else{
	            	var tiempo = `${horas}:${minutos}:${segundos}`;
	            }
	            tiempo += '<i class="fas fa-clock text-primary"></i>';
	        }
        	contenidoTooltip += `
            <strong>Inicio de Atención: </strong>${tiempo}  (${$this.find('.fecha_atencion').val()})<br>`;
        }
        if( $this.find('.atencionIniciadaPor').val() != ""){
        	contenidoTooltip += `
            <strong>Iniciada : </strong>${$this.find('.atencionIniciadaPor').val()}`;
        }
        if( $this.find('.dau_usuario_ultima_evo').val() != ""){
        	contenidoTooltip += `
            <i class="fas fa-random text-primary"></i> <strong>Evolución : </strong>${$this.find('.dau_usuario_ultima_evo').val()}`;
        }
        contenidoTooltip += `<br>`;
        if( $this.find('.fecha_egreso2').val() != null){
        	var tiempo = "";
        	var fecha_egreso2 = $this.find('.fecha_egreso2').val();
            var fecha_egreso2Date = new Date(fecha_egreso2);
            var diferencia = horaServidorDate - fecha_egreso2Date;
            var dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
            var horas = Math.floor((diferencia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutos = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));
            var segundos = Math.floor((diferencia % (1000 * 60)) / 1000);
            horas = horas.toString().padStart(2, '0');
            minutos = minutos.toString().padStart(2, '0');
            segundos = segundos.toString().padStart(2, '0');
            if(dias > 0) {
            	var tiempo = `${dias}d ${horas}:${minutos}:${segundos}`;
            }else{
            	var tiempo = `${horas}:${minutos}:${segundos}`;
            }
        	contenidoTooltip += `
            <strong>Tiempo de Hospitalización: </strong>${tiempo} <i class="fas fa-clock text-danger"></i> (${$this.find('.fecha_egreso').val()})<br>`;
        }
        if( $this.find('.motivo_egreso').val() != ""){
        	contenidoTooltip += `
            <strong>Motivo Indicación Egreso: </strong>${$this.find('.motivo_egreso').val()} ${$this.find('.servicioHospitalizacion').val()}<br>`;
        }
        if(tiempoCamaDesocupadaHidden != null){
        	var contenidoTooltip = `
            <strong>CAMA DESOCUPADA</strong><br>
            <strong>Tiempo de cama Desocupada: </strong>${tiempoCamaDesocupadaHidden}
        `;
        }
        $this.attr('title', contenidoTooltip).tooltip('update');
        $this.attr('html', true).tooltip('update');
    });
    $('.creadorTool').tooltip({
        title: contenidoTooltip,
        html: true,
        placement: 'right',
        container: 'body'
    })
    $('.buscadorLi').on('dragenter dragover', function(event) {
		event.preventDefault();
		var target = $(this).find('.ActivoColor').attr('href');
		var targetid = $(this).find('.ActivoColor').attr('id');
		$('.ActivoColor').removeClass('active');
		$(this).find('.ActivoColor').addClass('active');
		$('.tab-pane').removeClass('show active');
		$(target).addClass('show active');
		$('#unidad'+targetid).addClass(' active');
	});
	// Variables gloables
	let styleTooltip;
	let datosDivToolTip 		  	= { idDiv : '' , idDau : '' };
	let movimientoPaciente 			= { tipoPaciente : '' , nombrePaciente : '' , sala : '' , cama : '' , salaDest_id : '' };
	let $divMapapisoFull 		 	= $('#divMapapisoFull'),
		$frm_mp_adulto      	 	= $('#frm_mp_adulto'),
		$frm_mp_pediatrico 		 	= $('#frm_mp_pediatrico'),
		$tablaPacientesEspera_tr 	= $('#tablaPacientesEspera tr'),
		$frm_mp_ginecologia 	 	= $('#frm_mp_ginecologia'),
		$tablaPacientesEspera    	= $('#tablaPacientesEspera'),
		$verInfoPac					= $('.verInfoPac'),
		$mapapiso_adulto			= $('#mapapiso_adulto'),
		$mapapiso_pediatrico		= $('#mapapiso_pediatrico'),
		$mapapiso_ginecologico		= $('#mapapiso_ginecologico'),
		$pacienteCategorizado		= $('.pacienteCategorizado'),
		$btnDonanteOrganos		    = $('#avisoDonanteOrganos'),
		tipoMapa                   	= $("#tipoMapa").val();
		// alert(tipoMapa)
	if(tipoMapa == 'mapaAdultoPediatrico'){
		localStorage.setItem('urlAtras', '/views/modules/mapa_piso_full/mapa_piso_full.php');
		localStorage.setItem('parametrosAtras', 'tipoMapa=mapaAdultoPediatrico');
	}else{
		localStorage.setItem('urlAtras', '/views/modules/mapa_piso_full/mapa_piso_full.php');
		localStorage.setItem('parametrosAtras', 'tipoMapa=mapaGinecologico');
	}
	// setPosition('mapa_piso');
	// localStorage.setItem('ultimaPosicion', player.x);

	let labelPacientesEnEspera   	= document.getElementById("lblResultPE"),
		labelPacientesCategorizados = document.getElementById("lblResultPC");
	//Refresh mapa piso (3 min)
	clearInterval(refreshTime);

	function refreshMapaPiso(){
		var moviendo= false;
		document.onmousemove = function(){moviendo= true;};
		document.onkeypress = function() {moviendo = true;};

		if ($divMapapisoFull.length){
			refreshTime = setInterval (function(){
				if (!moviendo) {
					if (refreshTime){
						if ($divMapapisoFull.length){clearInterval(refreshTime);refresh();   		}
						else{clearInterval(refreshTime);}
					}
				}else{moviendo=false;}
			}, 5000); //3 min.
		}else{clearInterval(refreshTime);}
	}

	function refresh() {
		ajaxContent(`${raiz}/views/modules/mapa_piso_full/mapa_piso_full.php`,'','#contenidoDAU','', true);
		$("#resumenAtencionesReporte" ).remove();
		$("#resumenTiemposEspera" ).remove();
		setPosition($(this).attr("id"));
		unsetSesion();
	}
	//Búsqueda según criterio
	busquedaSegunCriterio();
	//Verificar si existen exámenes de laboratorio cancelados
	verificarCancelacionExamenLaboratorio();
	//Verificar síntomas respiratorios
	verificarSintomasRespiratorios();
	//Pacientes especialidad ginecológica
	pacientesEspecialidadGinecologica();
	//Resetea filtros en sección de despliegue pacientes en espera y/o categorizados
	$('#quitarFiltros').on('click', function(){
		cargarMapa();
	});
	//Cambia número de label 'Pacientes en Espera' según filtro seleccionado
	$('.resultadoPacienteEspera').on('click', function(){
		cargarMapa();
		var claseACambiar = "pacienteEnEspera";
		var filtroSelect = "";
		$tablaPacientesEspera_tr.each(function(){
			if($(this).hasClass('pacienteCategorizado')){
				$(this).addClass('deshabilitarTR');
			}
		});
	});
	//Cambia número de label 'Categorizados' según filtro seleccionado
	$('.resultadoPacienteCategorizados').on('click', function(){
		cargarMapa();
		var claseACambiar = "pacienteCategorizado";
		var filtroSelect = "";
		$('#tablaPacientesEspera tr').each(function(){
			if ( ! this.id ) {
				return;
			}
			if ( filtroSelect != '' && ($(this).hasClass(claseACambiar) && $(this).hasClass(filtroSelect)) ) {
				$(this).removeClass('deshabilitarTR');
				return;
			}
			if ( filtroSelect == '' && $(this).hasClass(claseACambiar) ) {
				$(this).removeClass('deshabilitarTR');
				return;
			}
			$(this).addClass('deshabilitarTR');
		});
	});
	//Despliega tipos de pacientes según filtro seleccionado (adulto, pediátrico, ginecológico)
	$("#frm_tipo_Atenciones").change(function(){
		const tipoAtencion = $('#frm_tipo_Atenciones option:selected').val();
		let claseTr        = '';
		switch ( tipoAtencion ) {
			case '1' :
				claseTr = 'sincategorizar';
			break;
			case '2' :
				claseTr = 'adulto';
			break;
			case '3' :
				claseTr = 'pediatrico';
			break;
			case '4' :
				claseTr = 'ginecologico';
			break;
			case '5':
				claseTr = 'adultoPediatrico';
			break;
			case '6':
				claseTr = 'indiferenciado';
			break;
		}
		let parametros = { C1 : 0 , C2 : 0 , C3 : 0 , C4 : 0 , C5 : 0 , SC : 0 , CAT : 0 , TOTAL : 0 };
		$('#tablaPacientesEspera tr').each(function(){
			$(this).removeClass('deshabilitarTR');
			if ( claseTr != 'sincategorizar' && this.id && ! $(this).hasClass(claseTr) ) {
				$(this).addClass('deshabilitarTR');
				return;
			}
			id = this;
			if ( $(id).hasClass('sincategorizar') ) {
			 parametros.SC = parametros.SC + 1;
			}
			if ( $(id).hasClass('tr_tblCat-ESI-1') ) {
				parametros.C1 = parametros.C1 + 1;
			}
			if ( $(id).hasClass('tr_tblCat-ESI-2') ) {
				parametros.C2 = parametros.C2 + 1;
			}
			if ( $(id).hasClass('tr_tblCat-ESI-3') ) {
				parametros.C3 = parametros.C3 + 1;
			}
			if ( $(id).hasClass('tr_tblCat-ESI-4') ) {
				parametros.C4 = parametros.C4 + 1;
			}
			if ( $(id).hasClass('tr_tblCat-ESI-5') ) {
				parametros.C5 = parametros.C5 + 1;
			}
			parametros.CAT   = parametros.C1 + parametros.C2 + parametros.C3 + parametros.C4 + parametros.C5;
			parametros.TOTAL = parametros.SC + parametros.C1 + parametros.C2 + parametros.C3 + parametros.C4 + parametros.C5;
		});
		document.getElementById("td_c1").innerHTML     	= parametros.C1;
		document.getElementById("td_c2").innerHTML     	= parametros.C2;
		document.getElementById("td_c3").innerHTML     	= parametros.C3;
		document.getElementById("td_c4").innerHTML     	= parametros.C4;
		document.getElementById("td_c5").innerHTML     	= parametros.C5;
		document.getElementById("td_sc").innerHTML     	= parametros.SC;
		document.getElementById("td_cat").innerHTML    	= parametros.CAT;
		document.getElementById("td_total").innerHTML  	= parametros.TOTAL;
		labelPacientesEnEspera.innerHTML  				= '('+parametros.SC+')';
		labelPacientesCategorizados.innerHTML  			= '('+parametros.CAT+')';
	});
	//Ver que pacientes fueron atendidos por usuario logueado
	$('#checkAtencionIniciadasPor').on('change', function(){
		let nombreUsuarioLogueado = $('#usuarioLogueado').val();
		let usuarioLogueadoID = $('#usuarioLogueadoID').val();

		// console.log(usuarioLogueadoID)
		if ( ! $(this).is(":checked") ) {
			console.log(nombreUsuarioLogueado)
			const contentDivs = document.querySelectorAll('.contPacRecidencia');
		    contentDivs.forEach(div => {
		        const hiddenInput = div.querySelector('.atencionIniciadaPorID');
		            div.classList.remove('highlight');
	            	div.classList.remove('throb2');
		    });
			return;
		}
			console.log(nombreUsuarioLogueado)
		const contentDivs = document.querySelectorAll('.contPacRecidencia');
	    contentDivs.forEach(div => {
	        const hiddenInput = div.querySelector('.atencionIniciadaPorID');
	        if (hiddenInput && hiddenInput.value === usuarioLogueadoID) {
	            div.classList.add('highlight');
	            div.classList.add('throb2');
	        } else {
	            div.classList.remove('highlight'); 
	            div.classList.remove('throb2');
	        }
	    });
	});
	//Entrar a Detalle Dau del paciente
	$('.verInfoPac').on('click', function(){
		const dau_id = $('#'+this.id).children(".hidden").val();
		if ( dau_id === undefined || perfilUsuario === 'gestionCama' ) {
			return;
		}
		if ( perfilUsuario !== 'medico' ) {

			ajaxContent(raiz+'/views/modules/mapa_piso_full/detalle_dau/detalle_dau.php','dau_id='+dau_id+'&tipoMapa='+tipoMapa+'&banderapiso'+banderapiso+'&perfilUsuario'+perfilUsuario,'#contenido','', true);
			// ajaxContent(`${raiz}/views/modules/mapa_piso_full/detalle_dau/detalle_dau.php`,`dau_id=${dau_id}&tipoMapa=${tipoMapa}&banderapiso=${banderapiso}&perfilUsuario=${perfilUsuario}`,'#contenido', '', true);
			ajaxRequest(`${raiz}/controllers/server/mapa_piso_full/main_controller.php`, `dau_id=${dau_id}&accion=guardarMovimientoQuienVioDAU`, 'POST', 'JSON', 1, 'Cargando');
			return;
		}
		if ( perfilUsuario === 'medico' ) {
			ajaxRequest(`${raiz}/controllers/server/mapa_piso_full/main_controller.php`, `dau_id=${dau_id}&accion=eventoRCE`, 'POST', 'JSON', 1, 'Cargando');
			ajaxRequest(`${raiz}/controllers/server/mapa_piso_full/main_controller.php`, `dau_id=${dau_id}&accion=guardarMovimientoQuienVioDAU`, 'POST', 'JSON', 1, 'Cargando');
			ajaxContent(`${raiz}/views/modules/rce/medico/rce.php`,'dau_id='+dau_id+'&tipoMapa='+tipoMapa,'#contenido');
		
			return;
		}
	});
	//Categorizar pacientes en espera
	$('.pacienteEnEspera').on('click', function ( ) {
		if ( perfilUsuario == 'gestionCama' ) {
			return;
		}
		let idDau 				= $(this).attr('id');
		parametroIdDau.idDau 	= idDau;
		if ( $(`#${idDau} .tipoPaciente`).val() == 'ginecologico' ) {
			formularioCategorizarPacienteSDD(idDau);
			return;
		}
		modalFormulario_noCabecera('', raiz+"/views/modules/rce/categorizacion/categorizacion.php", `t_trid=${idDau}&bandera_acceso=DAU`, "#modalDetalleCategorizacion", "modal-lg", "", "fas fa-plus",'');
	});
	//Detalles, signos vitales y NEA a pacientes categorizados
	$('.pacienteCategorizado').on('click', function ( ) {
		if ( perfilUsuario == 'gestionCama' ) {
			return;
		}
		let idDau = $(this).attr('id');
		parametroIdDau.idDau = idDau;
		modalFormulario_noCabecera('', raiz+"/views/modules/rce/rce/dau_detalle.php", 'dau_id='+idDau, "#modalDetalleCategorizacion", "modal-lg", "", "fas fa-plus");
	});
	//================================================================================================
	// DRAG & DROP
	//Tabla de Pacientes en Espera
	$tablaPacientesEspera.on("dragstart", ".tbl_esp", handleDragStartTab2 );
	$tablaPacientesEspera.on("dragleave", ".tbl_esp", handleDragLeaveTab2 );
	$tablaPacientesEspera.on("drop", ".tbl_esp", handleDropTab2 );
	$tablaPacientesEspera.on("dragstart", ".tbl_cat", handleDragStartTab );
	$tablaPacientesEspera.on("dragleave", ".tbl_cat", handleDragLeaveTab );
	$tablaPacientesEspera.on("drop", ".tbl_cat", handleDropTab );
	//Salas de UE
	var cols = document.querySelectorAll('.camaMapaPiso');
	[].forEach.call(cols, function(col) {
		col.addEventListener('dragstart', handleDragStart, false);
		col.addEventListener('dragenter', handleDragEnter, false);
		col.addEventListener('dragover', handleDragOver, false);
		col.addEventListener('dragleave', handleDragLeave, false);
		col.addEventListener('drop', handleDrop, false);
		col.addEventListener('dragend', handleDragEnd, false);
	});
	//Eventos de los box de mapa de piso
	var dragSrcEl = null;
	var wichOne   = null;
	var css;
	var cssOrig;
	var salaOrig;
	let salaId;
	var camaOrig;
	var dauMov;
	//===========================
	var id_dauPacienteOrig 	= null;
	var thisDestino 		= null;
	var eDestino			= null;
	var contEvent 			= null;
	//===========================
	function handleDragStart(e) {
		$("."+styleTooltip).remove();
		this.style.opacity 				= '0.4';
		wichOne 						= "mapaPiso";
		dragSrcEl 						= this;
		e.dataTransfer.effectAllowed 	= 'move';
		e.dataTransfer.setData('text/html', this.innerHTML);
		var salacamaOrig 				= this.id.split('_');
		salaOrig 						= salacamaOrig[0];
		camaOrig 						= salacamaOrig[1];
		id_dauPacienteOrig 				= $("#"+e.currentTarget.id).children(".hidden").val();
		camaOrigen 						= $("#"+e.currentTarget.id+' .cama_id').val();
		dauMov 							= $("#"+e.currentTarget.id).children(".hidden").val();
		salaOrig_id						= $("#"+e.currentTarget.id+' .sala_id').val();
		$(this).tooltip('hide');
	}
	function handleDragEnter(e){
		var id_dauPacienteOrig = $("#"+e.currentTarget.id).children(".hidden").val();
		if (id_dauPacienteOrig) {
			if(id_dauPacienteOrig == dauMov){
				this.classList.add('over');
			}else{
				this.classList.add('camaOcupada');
			}
		}else{
			this.classList.add('over');
		}
	}
	function handleDragOver(e){
		if (e.preventDefault) {
			e.preventDefault();
		}
		e.dataTransfer.dropEffect = 'move';
	}
	function handleDragLeave(e){
		this.classList.remove('over');
		this.classList.remove('camaOcupada');
	}
	function handleDrop ( event ) {
		if ( perfilUsuario === 'gestionCama' ) {
			this.classList.remove('over');
			return;

		}
		thisDestino = this;
		eDestino 	= event;
		contEvent 	= event.dataTransfer.getData('text/html');
		if ( $("#"+event.currentTarget.id).children(".hidden").val() != undefined && thisDestino != dragSrcEl ) {
			texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-ban throb2 text-danger" style="font-size:29px"></i> Error al mover Paciente </h4>  <hr>  <p class="mb-0">Usted esta intentando mover a un paciente a una cama ocupada, favor de intentar con otra.</p></div>';
            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
			cssOrig = $("#"+dragSrcEl.id).children(".css_border").val();
			dragSrcEl.classList.add('over-'+cssOrig);
			this.classList.remove('camaOcupada');
			return;
		}
		switch ( wichOne ) {
			case 'mapaPiso':
				dragSrcEl.style.opacity = '1';
				if ( $('#'+dragSrcEl.id).children('.hidden').val() !== undefined && dragSrcEl != thisDestino ) {
					if ( ! pacienteSigueEnCama() && ( salaOrig && camaOrig && id_dauPacienteOrig ) ){
						$("#"+thisDestino.id).html("");
						texto = `<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-ban throb2 text-danger" style="font-size:29px"></i> Error </h4>  <hr>  <p class="mb-0">El paciente que usted esta moviendo, no se encuentra en: <br>- Sala: <strong>${salaOrig}</strong><br>- Cama: <strong>${camaOrig}</strong><br>Se recargó nuevamente el <strong>Mapa de Piso</strong> para que visualice los cambios.</p></div>`;
        				modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
        				ajaxContent(`${raiz}/views/modules/mapa_piso_full/mapa_piso_full.php`,'tipoMapa='+tipoMapa,'#contenido','', true);
						return;
					}
					movimientoPaciente.tipoPaciente 	= $(`#${dragSrcEl.id} .tipoPaciente`).val();
					let salaTipo 						= $(`#${thisDestino.id} .salaTipo`).val();
					if ( ! tipoPacienteConcuerdaConTipoSala(movimientoPaciente.tipoPaciente, salaTipo, thisDestino.id) ) {
						return;
					}
					let salacama 							= thisDestino.id.split('_');
					movimientoPaciente.nombrePaciente 		= $(`#${dragSrcEl.id} .nombre_paciente`).val();
					movimientoPaciente.sala 				= salacama[0];
					movimientoPaciente.cama 				= salacama[1];
					movimientoPaciente.salaDest_id 			= $(`#${thisDestino.id} .sala_id`).val();
					respujestaPermiso = ajaxRequest(raiz+'/controllers/server/categorizacion/main_controller.php', 'accion=verificarPermisoUsuario&boton=btn_mapaPisoCama_a_Cama', 'POST', 'JSON', 1, 'Verificando permiso');
					if(respujestaPermiso){
						var  funcionCambiarPacienteCama = function(){
							const parametros    = {'accion': 'updateSalaCamaMovPaciente', 'id_dau': id_dauPacienteOrig, 'salaOrig': salaOrig, 'camaOrig': camaOrig, 'sala': movimientoPaciente.sala, 'cama': movimientoPaciente.cama, 'salaOrig_id': salaOrig_id, "salaDest_id": movimientoPaciente.salaDest_id};
							const respuestaControlador = ajaxRequest(`${raiz}/controllers/server/mapa_piso_full/main_controller.php`, parametros, 'POST', 'JSON', 1);
							if ( respuestaControlador.status == 'success' ) {
								texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-check-double throb2 text-success" style="font-size:29px"></i> Exito </h4>  <hr>  <p class="mb-0">Se ha movido al paciente de cama <b>Correctamente</b>.</p></div>';
                				modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                				ajaxContent(`${raiz}/views/modules/mapa_piso_full/mapa_piso_full.php`,'tipoMapa='+tipoMapa,'#contenido','', true);
							}else{
								texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-ban throb2 text-danger" style="font-size:29px"></i> Error al mover Paciente </h4>  <hr>  <p class="mb-0">'+respuestaControlador.message+'<br><br><div class="text-center"><i class="fas fa-envelope text-danger mr-1"></i> mesadeayuda@hjnc.cl <i class="ml-3 mr-1 text-danger fas fa-mobile-alt"></i>584685 <i class="fas fa-mobile-alt ml-3 mr-1 text-danger"></i>584686 <i class="ml-3 mr-1 fas fa-mobile-alt text-danger"></i>584679</div></p></div>';
       	 						modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
							}
						}
						modalConfirmacion('Confirmación para mover al paciente', `Esta Seguro de mover al paciente <strong>${movimientoPaciente.nombrePaciente}</strong> a:<br>- Sala: <strong>${movimientoPaciente.sala}</strong><br>- Cama: <strong>${movimientoPaciente.cama}</strong>`, "primary", funcionCambiarPacienteCama);
					}else{
						ErrorPermiso();
					}
				}
				thisDestino.classList.remove('over');
			break;
			case 'listaCategorizado':
				movimientoPaciente.tipoPaciente = $(`#${elementDragTab.id} .tipoPaciente`).val().split(" ").shift();
				let salaTipo     				= $(`#${thisDestino.id} .salaTipo`).val();
				if ( ! tipoPacienteConcuerdaConTipoSala(movimientoPaciente.tipoPaciente, salaTipo, thisDestino.id) ) {
					return;
				}
				if ( ! elementDragTab.id ) {
					return;
				}
				let salacama = thisDestino.id.split('_');
				movimientoPaciente.nombrePaciente 		= $(`#${elementDragTab.id} .nombrePaciente`).val();
				movimientoPaciente.sala 				= salacama[0];
				movimientoPaciente.cama 				= salacama[1];
				movimientoPaciente.salaDest_id			= $(`#${thisDestino.id} .sala_id`).val();
				respujestaPermiso = ajaxRequest(raiz+'/controllers/server/categorizacion/main_controller.php', 'accion=verificarPermisoUsuario&boton=btn_mapaPiso_cat_a_Cama', 'POST', 'JSON', 1, 'Verificando permiso');
				if(respujestaPermiso){
					var  funcionConfirmarMoverPacienteCategorizadoACama = function(){
						const parametros = {'accion': 'updateSalaCamaAddPaciente', 'id_dau': elementDragTab.id, 'sala': movimientoPaciente.sala, 'cama': movimientoPaciente.cama, 'lista': 'listaCategorizados', 'salaDest_id': movimientoPaciente.salaDest_id};
						const addCamaPac = ajaxRequest(`${raiz}/controllers/server/mapa_piso_full/main_controller.php`, parametros, 'POST', 'JSON', 1);
						if ( addCamaPac.status == 'success' ) {
							texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-check-double throb2 text-success" style="font-size:29px"></i> Exito </h4>  <hr>  <p class="mb-0">Se ha movido al paciente de cama <b>Correctamente</b>.</p></div>';
            				modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
            				ajaxContent(`${raiz}/views/modules/mapa_piso_full/mapa_piso_full.php`,'tipoMapa='+tipoMapa,'#contenido','', true);
						}else{
							texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-ban throb2 text-danger" style="font-size:29px"></i> Error al mover Paciente </h4>  <hr>  <p class="mb-0">'+addCamaPac.message+'<br><br><div class="text-center"><i class="fas fa-envelope text-danger mr-1"></i> mesadeayuda@hjnc.cl <i class="ml-3 mr-1 text-danger fas fa-mobile-alt"></i>584685 <i class="fas fa-mobile-alt ml-3 mr-1 text-danger"></i>584686 <i class="ml-3 mr-1 fas fa-mobile-alt text-danger"></i>584679</div></p></div>';
   	 						modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
						}
					}
					modalConfirmacion('Confirmación para mover al paciente', `Esta seguro de mover al paciente <strong>${movimientoPaciente.nombrePaciente}</strong> a:<br>- Sala: <strong>${movimientoPaciente.sala}</strong><br>- Cama: <strong>${movimientoPaciente.cama}</strong>`, "primary", funcionConfirmarMoverPacienteCategorizadoACama);
				}else{
					ErrorPermiso();
				}
			break;
			case 'listaEspera':
				texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-ban throb2 text-danger" style="font-size:29px"></i> Error al mover Paciente </h4>  <hr>  <p class="mb-0">Usted esta intentando mover a un paciente que aún no se encuentra categorizado.</p></div>';
       	 		modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
			break;
		}
		this.style.opacity = '1';
		this.classList.remove('over');
		return false;
	}
	function handleDragEnd(e){
		dragSrcEl.style.opacity = '1';
		this.classList.remove('over-'+css);
	}
	//Eventos Sobre TR'S de lista espera atención
	var elementDragTab 					= null;
	var dragIcon 						= document.createElement('img');
	dragIcon.src 						= raiz+'/assets/img/indefinidoB.png';
	dragIcon.width 						= 100;
	function handleDragStartTab(e){
		var e 							= e.originalEvent;
		elementDragTab 					= this;
		wichOne 						= "listaCategorizado";
		e.dataTransfer.setDragImage(dragIcon, -10, -10);
		e.dataTransfer.effectAllowed 	= 'move';
		e.dataTransfer.setData('text/html', this.innerHTML);
		var idtr 						= e.currentTarget.id;
	}
	function handleDragLeaveTab(e){
		e.preventDefault();
		this.classList.remove('over');
	}
	function handleDropTab(e){
	}
	//********************************************
	function handleDragStartTab2(e){
		var e = e.originalEvent;
		elementDragTab = this;
		wichOne = "listaEspera";


		e.dataTransfer.setDragImage(dragIcon, -10, -10);

		e.dataTransfer.effectAllowed = 'move';
		e.dataTransfer.setData('text/html', this.innerHTML);
		var idtr = e.currentTarget.id;
	}
	function handleDragLeaveTab2(e){
		e.preventDefault();
		this.classList.remove('over');
	}
	function handleDropTab2(e){
		//console.log('handleDropTab');
	}
	// let nombreBuscado = "MILTON RODRIGO ALARCON ARIAS";
	// let paciente = Array.from(document.querySelectorAll('.grid-item input.nombre_paciente')).find(input => input.value === nombreBuscado);

	// if (paciente) {
	//     console.log("Paciente encontrado:", paciente.value);
	// } else {
	//     console.log("Paciente no encontrado.");
	// }
	// const gridItems = document.querySelectorAll('.grid-item');

	// // Recorre cada elemento para buscar el nombre del paciente
	// gridItems.forEach(item => {
	//     // Busca el input con la clase "nombre_paciente" dentro del grid-item
	//     const nombrePacienteInput = item.querySelector('input.nombre_paciente');
	    
	//     // Verifica si el nombre existe y si coincide con el buscado
	//     if (nombrePacienteInput && nombrePacienteInput.value.includes('MILTON RODRIGO ALARCON ARIAS')) {
	//         // Añade el fondo de color
	//         item.classList.add('highlight2');
	//     }
	// });

	//========================================================================================================================================
	// CONTADOR DE TIEMPOS
    // function actualizarTiempoEspera() {
    //     $('.tiempo-espera').each(function() {
    //         Obtener la fecha de ingreso desde el atributo data-fecha-ingreso
    //         let fechaIngreso 		= $(this).data('fecha-ingreso');
    //         // console.log(fechaIngreso)
    //         let fechaIngresoDate 	= new Date(fechaIngreso);
    //         // Calcular la diferencia entre la fecha de ingreso y la hora actual del servidor
    //         let diferencia 			= horaServidorDate - fechaIngresoDate;
    //         // Convertir la diferencia en días, horas, minutos
    //         let dias 				= Math.floor(diferencia / (1000 * 60 * 60 * 24));
    //         let horas 				= Math.floor((diferencia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    //         let minutos 			= Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));
    //         let segundos 			= Math.floor((diferencia % (1000 * 60)) / 1000);
    //        // Asegurarse de que horas, minutos y segundos tengan siempre dos dígitos
    //         horas 					= horas.toString().padStart(2, '0');
    //         minutos 				= minutos.toString().padStart(2, '0');
    //         segundos 				= segundos.toString().padStart(2, '0');
    //         Mostrar el tiempo de espera en el formato: "XXd HH:MM:SS"
    //         if(dias > 0) {
    //         	$(this).text(`${dias}d ${horas}:${minutos}:${segundos}`);
    //         }else{
    //         	$(this).text(`${horas}:${minutos}:${segundos}`);
    //         }
    //     });
    // }
	const searchInput = document.querySelector('#searchInput');
    const gridItems = document.querySelectorAll('.grid-item');

	const clearButton = document.querySelector('[data-search="clear"]');
	// Agrega el evento "input" al campo de búsqueda
	searchInput.addEventListener('input', () => {
		const query = searchInput.value.toLowerCase().trim(); // Texto ingresado en minúsculas y sin espacios al inicio/final

		// Si el campo de búsqueda está vacío, elimina los resaltados y muestra todos los elementos
		if (query === '') {
		  gridItems.forEach(item => {
		    item.classList.remove('highlight2');
		    item.style.display = ''; // Asegúrate de mostrar todos los elementos
		  });
		  return; // Salimos del evento si no hay texto de búsqueda
		}

		// Itera sobre cada grid-item
		gridItems.forEach(item => {
		  // Busca el input con la clase "nombre_paciente" dentro del grid-item
		  const nombrePacienteInput = item.querySelector('.nombre_paciente');
		  if (nombrePacienteInput) {
		    const nombrePaciente = nombrePacienteInput.value.toLowerCase(); // Nombre del paciente en minúsculas

		    // Divide el texto ingresado y el nombre completo en palabras
		    const queryWords = query.split(/\s+/); // Palabras separadas por espacios
		    const nombreWords = nombrePaciente.split(/\s+/); // Palabras del nombre del paciente

		    // Verifica si al menos una palabra del query coincide parcialmente con alguna palabra del nombre
		    const hasMatch = queryWords.some(qWord =>
		      nombreWords.some(nWord => nWord.includes(qWord))
		    );

		    if (hasMatch) {
		      // Agrega la clase highlight2 si hay coincidencia
		      item.classList.add('highlight2');
		      // item.style.display = ''; // Asegúrate de mostrarlo
		    } else {
		      // Elimina la clase highlight2 y oculta si no hay coincidencia
		      item.classList.remove('highlight2');
		      // item.style.display = 'none';
		    }
		  }
		});
	});
	clearButton.addEventListener('click', () => {
	// Elimina el texto del input de búsqueda
		searchInput.value = '';

		// Elimina los resaltados y muestra todos los elementos
		gridItems.forEach(item => {
		  item.classList.remove('highlight2');
		  item.style.display = ''; // Mostrar todos los elementos
		});
	});
    function incrementarHoraServidor() {
        horaServidorDate.setSeconds(horaServidorDate.getSeconds() + 1);
    }
    const FechaServidor = new Date(horaServidorMapa);
    function actualizarTiempos() {
	    var celdas = document.querySelectorAll('.tiempo-espera');

	    celdas.forEach(celda => {
	        var fechaIngreso = new Date(celda.dataset.fechaIngreso);
	        var categoria = celda.dataset.categoria;

	        if (isNaN(fechaIngreso.getTime())) {
	            celda.textContent = "00:00:00";
	            return;
	        }

	        // Tiempo transcurrido real
	        var diferenciaMs =
	            FechaServidor.getTime() -
	            fechaIngreso.getTime() +
	            (Date.now() - FechaServidor.getTime());

	        if (diferenciaMs < 0) diferenciaMs = 0;

	        var totalSeg = Math.floor(diferenciaMs / 1000);
	        var totalMin = Math.floor(totalSeg / 60);

	        var horas = Math.floor((totalSeg % 86400) / 3600);
	        var minutos = Math.floor((totalSeg % 3600) / 60);
	        var segundos = totalSeg % 60;

	        var tiempoTxt =
	            `${String(horas).padStart(2,'0')}:` +
	            `${String(minutos).padStart(2,'0')}:` +
	            `${String(segundos).padStart(2,'0')}`;

	        // Validar tiempo máximo según categoría
	        var tiempoMax = categoriasMap[categoria];

	        if ( totalMin >= tiempoMax) {
	            celda.innerHTML = `<i class="fas fa-clock throb2 text-danger"></i> ${tiempoTxt}`;
	            // celda.classList.add('text-danger'); // opcional
	        } else {
	            celda.textContent = tiempoTxt;
	            // celda.classList.remove('text-danger');
	        }
	    });
	}
	// 1893498
 //    function actualizarTiempos() {
	//     var celdas = document.querySelectorAll('.tiempo-espera');

	//     celdas.forEach(celda => {
	//         var fechaIngreso = new Date(celda.dataset.fechaIngreso);

	//         // Validar fechaIngreso
	//         if (isNaN(fechaIngreso.getTime())) {
	//             celda.textContent = "00:00:00";
	//             return;
	//         }

	//         // Calcular diferencia real usando hora del servidor
	//         var diferenciaMs =
	//             FechaServidor.getTime() -
	//             fechaIngreso.getTime() +
	//             (Date.now() - FechaServidor.getTime());

	//         // Evitar tiempos negativos
	//         if (diferenciaMs < 0) diferenciaMs = 0;

	//         // Convertir a hh:mm:ss
	//         var totalSeg = Math.floor(diferenciaMs / 1000);

	// 	        var horas = Math.floor((totalSeg % (24 * 60 * 60)) / (60 * 60));
	//         // var horas = Math.floor(totalSeg / 3600);
	//         var minutos = Math.floor((totalSeg % 3600) / 60);
	//         var segundos = totalSeg % 60;

	//         celda.textContent =
	//             `${String(horas).padStart(2,'0')}:` +
	//             `${String(minutos).padStart(2,'0')}:` +
	//             `${String(segundos).padStart(2,'0')}`;
	//     });
	// }
 //    function actualizarTiempos() {
	//     var celdas = document.querySelectorAll('.tiempo-espera');

	//     celdas.forEach(celda => {
	//         // Obtener la fecha de ingreso desde el atributo data-fecha-ingreso
	//         var fechaIngreso = new Date(celda.dataset.fechaIngreso);

	//         // Calcular la diferencia en milisegundos
	//         var diferenciaMs = FechaServidor - fechaIngreso + (Date.now() - FechaServidor.getTime());

	//         // Convertir la diferencia en días, horas, minutos y segundos
	//         var diferenciaSegundos = Math.floor(diferenciaMs / 1000);
	//         var dias = Math.floor(diferenciaSegundos / (24 * 60 * 60));
	//         var horas = Math.floor((diferenciaSegundos % (24 * 60 * 60)) / (60 * 60));
	//         var minutos = Math.floor((diferenciaSegundos % (60 * 60)) / 60);
	//         var segundos = diferenciaSegundos % 60;

	//         // Formatear en XXD HH:mm:ss
	//         var formatoTiempo = `${String(horas).padStart(2, '0')}:${String(minutos).padStart(2, '0')}:${String(segundos).padStart(2, '0')}`;

	//         // Actualizar el contenido de la celda
	//         celda.textContent = formatoTiempo;
	//     });
	// }

  // Actualizar cada minuto para optimizar el rendimiento
  setInterval(actualizarTiempos, 1000);
    // Actualizar el tiempo de espera cada segundo
    // setInterval(function() {
    //     incrementarHoraServidor();
    //     actualizarTiempoEspera();
    // }, 100);
	//========================================================================================================================================
	// FUNCIONES
	function busquedaSegunCriterio ( ) {
		var $input 			= $("input[type='search']"),
		// clear button
		$clearBtn 			= $("button[data-search='clear']"),
		// prev button
		$prevBtn 			= $("button[data-search='prev']"),
		// next button
		$nextBtn 			= $("button[data-search='next']"),
		// the context where to search
		// $content = $(".content"),
		$content 			= $(".pac-list"),
		// jQuery object to save <mark> elements
		$results,
		// the class that will be appended to the current
		// focused element
		currentClass 		= "current",
		// top offset for the jump (the search bar)
		offsetTop 			= 50,
		// the current index of the focused element
		currentIndex 		= 0;
		//text matches
		var $matches 		= $('#lblResult');
		/**
		 * Jumps to the element matching the currentIndex
		 */
	

		function jumpTo() {
			if ($results.length) {
				var position,
				$current 	= $results.eq(currentIndex);
				$results.removeClass(currentClass);
				if ($current.length) {
					$current.addClass(currentClass);
					position 	= $current.offset().top - offsetTop;
					window.scrollTo(0, position);
				}
			}
		}
		/**
		 * Searches for the entered keyword in the
		 * specified context on input
		 */
		$input.on("input", function() {
			var searchVal = this.value;
			$('mark').parent().parent().css('background','');
			$content.unmark({
			done: function() {
				$content.mark(searchVal, {
				separateWordSearch: true,
				done: function() {
					$results = $content.find("mark");
					$('mark').parent().parent().css('background','#11e4e5','important');
					// $('mark').parent().parent().each(function() {
						// this.classList.add('highlight2');
					    // this.style.setProperty('background', '#11e4e5', 'important');
					// });
					$matches.html('');
					$matches.append($results.length);
					currentIndex = 0;
					jumpTo();
				}
				});
			}
			});
		});
		/**
		 * Clears the search
		 */
		$clearBtn.on("click", function() {
			// $('mark').parent().parent().css('background','white');
			$('mark').parent().parent().css('background','');
			$content.unmark();
			$input.val("").focus();
			$matches.html('0');
		});
		/**
		 * Next and previous search jump to
		 */
		$nextBtn.add($prevBtn).on("click", function() {
			if ($results.length) {
			currentIndex += $(this).is($prevBtn) ? -1 : 1;
			if (currentIndex < 0) {
				currentIndex = $results.length - 1;
			}
			if (currentIndex > $results.length - 1) {
				currentIndex = 0;
			}
			jumpTo();
			}
		});
	}
	function desplegarTipoMapaSegunUsuario ( tipoMapa ) {
		switch ( tipoMapa ) {
			case 'A':
				$frm_mp_adulto.prop( "checked", true );
				$("#frm_tipo_Atenciones").children('option[value="2"]').prop('disabled', false);
				$("#frm_tipo_Atenciones").children('option[value="3"]').attr('disabled','disabled');
				$("#frm_tipo_Atenciones").children('option[value="4"]').attr('disabled','disabled');
				$mapapiso_adulto.show();
				$mapapiso_pediatrico.hide();
				$mapapiso_ginecologico.hide();
				$mapapiso_adulto.css("margin-left", "300px");
				$mapapiso_pediatrico.css("margin-left", "0px");
				$mapapiso_ginecologico.css("margin-left", "0px");
			break;
			case 'P':
				$frm_mp_pediatrico.prop( "checked", true );
				$("#frm_tipo_Atenciones").children('option[value="2"]').attr('disabled','disabled');
				$("#frm_tipo_Atenciones").children('option[value="3"]').prop('disabled', false);
				$("#frm_tipo_Atenciones").children('option[value="4"]').attr('disabled','disabled');
				$mapapiso_adulto.hide();
				$mapapiso_pediatrico.show();
				$mapapiso_ginecologico.hide();
				$mapapiso_adulto.css("margin-left", "2px");
				$mapapiso_pediatrico.css("margin-left", "550px");
				$mapapiso_ginecologico.css("margin-left", "0px");
			break;
			case 'G':
				$frm_mp_ginecologia.prop( "checked", true );
				$("#frm_tipo_Atenciones").children('option[value="2"]').attr('disabled','disabled');
				$("#frm_tipo_Atenciones").children('option[value="3"]').attr('disabled','disabled');
				$("#frm_tipo_Atenciones").children('option[value="4"]').prop('disabled', false);
				$mapapiso_adulto.hide();
				$mapapiso_pediatrico.hide();
				$mapapiso_ginecologico.show();
				$mapapiso_adulto.css("margin-left", "2px");
				$mapapiso_pediatrico.css("margin-left", "0px");
				$mapapiso_ginecologico.css("margin-left", "430px");
			break;
			case 'AP':
				$("#frm_tipo_Atenciones").children('option[value="2"]').prop('disabled', false);
				$("#frm_tipo_Atenciones").children('option[value="3"]').prop('disabled', false);
				$("#frm_tipo_Atenciones").children('option[value="4"]').attr('disabled','disabled');
				$frm_mp_adulto.prop( "checked", true );
				$frm_mp_pediatrico.prop( "checked", true );
				$mapapiso_adulto.show();
				$mapapiso_pediatrico.show();
				$mapapiso_ginecologico.hide();
				// $mapapiso_adulto.css("margin-left", "100px");
				$mapapiso_adulto.css("margin-left", "0px");
				$mapapiso_pediatrico.css("margin-left", "0px");
				$mapapiso_ginecologico.css("margin-left", "0px");
			break;
			case 'AG':
				$frm_mp_adulto.prop( "checked", true );
				$frm_mp_ginecologia.prop( "checked", true );
				$("#frm_tipo_Atenciones").children('option[value="2"]').prop('disabled', false);
				$("#frm_tipo_Atenciones").children('option[value="3"]').attr('disabled','disabled');
				$("#frm_tipo_Atenciones").children('option[value="4"]').prop('disabled', false);
				$mapapiso_adulto.show();
				$mapapiso_pediatrico.hide();
				$mapapiso_ginecologico.show();
				$mapapiso_adulto.css("margin-left", "100px");
				$mapapiso_pediatrico.css("margin-left", "0px");
				$mapapiso_ginecologico.css("margin-left", "0px");
			break;
			case 'PG':
				$frm_mp_pediatrico.prop( "checked", true );
				$frm_mp_ginecologia.prop( "checked", true );
				$("#frm_tipo_Atenciones").children('option[value="2"]').attr('disabled','disabled');
				$("#frm_tipo_Atenciones").children('option[value="3"]').prop('disabled', false);
				$("#frm_tipo_Atenciones").children('option[value="4"]').prop('disabled', false);
				$mapapiso_adulto.hide();
				$mapapiso_pediatrico.show();
				$mapapiso_ginecologico.show();
				$mapapiso_adulto.css("margin-left", "2px");
				$mapapiso_pediatrico.css("margin-left", "300px");
				$mapapiso_ginecologico.css("margin-left", "0px");
			break;
			case 'APG':
				$frm_mp_adulto.prop( "checked", true );
				$frm_mp_pediatrico.prop( "checked", true );
				$frm_mp_ginecologia.prop( "checked", true );
				$("#frm_tipo_Atenciones").children('option[value="2"]').prop('disabled', false);
				$("#frm_tipo_Atenciones").children('option[value="3"]').prop('disabled', false);
				$("#frm_tipo_Atenciones").children('option[value="4"]').prop('disabled', false);
				$("#frm_tipo_Atenciones").val(1);
				$("#frm_tipo_Atenciones").trigger("change");
				$mapapiso_adulto.show();
				$mapapiso_pediatrico.show();
				$mapapiso_ginecologico.show();
				$mapapiso_adulto.css("margin-left", "2px");
				$mapapiso_pediatrico.css("margin-left", "0px");
				$mapapiso_ginecologico.css("margin-left", "0px");
			break;
			default:
				$("#frm_tipo_Atenciones").children('option[value="2"]').prop('disabled', false);
				$("#frm_tipo_Atenciones").children('option[value="3"]').prop('disabled', false);
				$("#frm_tipo_Atenciones").children('option[value="4"]').prop('disabled', false);
				$("#frm_tipo_Atenciones").val(1);
				$("#frm_tipo_Atenciones").trigger("change");
				$mapapiso_adulto.show();
				$mapapiso_pediatrico.show();
				$mapapiso_ginecologico.show();
				$mapapiso_adulto.css("margin-left", "2px");
				$mapapiso_pediatrico.css("margin-left", "0px");
				$mapapiso_ginecologico.css("margin-left", "0px");
			break;
		}
	}
	function tipoPacienteConcuerdaConTipoSala ( tipoPaciente, salaTipo, idSala ) {
		let parametros 	= { tituloModal : 'Error al mover Paciente' , mensajeModal : '' , banderaError : false };
		let idCama 		= $(`#${thisDestino.id} .cama_id`).val();
		if ( ( tipoPaciente == 'adulto' || tipoPaciente == 1) && ! salaCorrespondeATipoPaciente('adulto', salaTipo) && ! camaCorrespondeATodos(idCama) ) {
			parametros.mensajeModal = 'Usted esta intentando mover a un paciente adulto a una cama no correspondiente de su atención, favor de intentar con otra.';
			parametros.banderaError = true;
		} else if ( ( tipoPaciente == 'pediatrico' || tipoPaciente == 2 ) && ! salaCorrespondeATipoPaciente('pediatrico', salaTipo) && ! camaCorrespondeATodos(idCama) ) {
			parametros.mensajeModal = 'Usted esta intentando mover a un paciente pediátrico a una cama no correspondiente de su atención, favor de intentar con otra.';
			parametros.banderaError = true;
		}  else if ( ( tipoPaciente == 'ginecologico' || tipoPaciente == 3 ) && ! salaCorrespondeATipoPaciente('ginecologico', salaTipo) ) {
			parametros.mensajeModal = 'Usted esta intentando mover a un paciente ginecológico a una cama no correspondiente de su atención, favor de intentar con otra.';
			parametros.banderaError = true;
		}
		if ( parametros.banderaError === true ) {
			texto = `<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-ban throb2 text-danger" style="font-size:29px"></i> ${parametros.tituloModal} </h4>  <hr>  <p class="mb-0">${parametros.mensajeModal}</p></div>`;
           	modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
			$(`#${idSala}`).removeClass('over');
			return false;
		}
		return true;
	}
	function salaCorrespondeATipoPaciente ( tipoPaciente, salaTipo ) {
		if ( tipoPaciente == 'adulto' ) {
			return ( salaTipo == 'A' );
		}
		if ( tipoPaciente == 'pediatrico' ) {
			return ( salaTipo == 'P' );
		}
		if ( tipoPaciente == 'ginecologico' ) {
			return ( salaTipo == 'GO' );
		}
	}
	function camaCorrespondeATodos ( idCama ) {
		const camasREA = [
			155,
			156,
			331,
			332,
		];
		const camasBRP = [
			337
		];
		const camasObservacion = [
			82,
			83,
			84,
			85,
			86,
			87,
			335,
			336
		];
		const camasML = [
			299,
			300,
			301,
			302,
			303,
			304,
			305,
			306
		];
		const camasUIndf = [
			307,
			308,
			309,
			310,
			311,
			312,
			313,
			314,
			315,
			316,
			317,
			318,
			319,
			320,
			321,
			322,
			323,
			324,
			325,
			326,
			327,
			328,
			329,
			330
		];
		return camasBRP.includes(Number(idCama)) || camasREA.includes(Number(idCama)) || camasObservacion.includes(Number(idCama)) || camasML.includes(Number(idCama)) || camasUIndf.includes(Number(idCama));
	}
	function pacienteSigueEnCama ( ) {
		const parametros = {'accion': 'pacienteSigueCamaOrg', 'id_dau': id_dauPacienteOrig, 'salaOrig': salaOrig, 'camaOrig': camaOrig, 'num_salaOrig': salaOrig_id };
		const sigueOrigen = ajaxRequest(`${raiz}/controllers/server/mapa_piso_full/main_controller.php`, parametros, 'POST', 'JSON', 1);
		return ( sigueOrigen.status == 'success' );
	}
	function verificarCancelacionExamenLaboratorio ( ) {
		const contentDivs = document.querySelectorAll('.contPacRecidencia');
	    contentDivs.forEach(div => {
	        const hiddenInput = div.querySelector('.examenLaboratorioCancelado');
	        if (hiddenInput && hiddenInput.value === 'S') {
	            div.classList.add('flashingBorder');
	        } else {
	            div.classList.remove('flashingBorder'); 
	        }
	    });
	}
	function formularioCategorizarPacienteSDD ( idDau ) {
		let botones = 	[
							{ id: 'btnMapaPisoAplicarSignosVitales', value: 'Signos Vitales', function:funcionAplicarSignosVitales, class: 'btn btn-primary' },
							{ id: 'btnCategorizarSDD', value: 'Categorizar', class: 'btn btn-primary' }
						];
		modalFormulario("<label class='mifuente'>Categorizar Paciente  </label>",raiz+'/views/modules/rce/categorizacion/categorizacionSDD.php',`t_trid=${idDau}`,'#categorizarPaciente',"modal-lg","light","fas fa-minus text-primary-light mifuente16",botones);
	}
	function funcionAplicarSignosVitales ( ) {
		modalFormulario_noCabecera('', raiz+"/views/modules/rce/signos_vitales/signos_vitales.php", 'dau_id='+parametroIdDau.idDau, "#modalSignosVitales", "modal-lgg", "", "fas fa-plus",'');
	}
	function verificarSintomasRespiratorios ( ) {
		$('.verInfoPac').each( function ( ) {
			const idDiv = $(this).attr('id');
			const sintomasRespiratorios = $(`#${idDiv} .sintomasRespiratorios`).val();
			if ( sintomasRespiratorios == 'S') {
				$(`#${idDiv}`).css( {"border-width" : "2px" , "border-color" : "black"} );
				return;
			}
			$(`#${idDiv}`).css( {"border-width" : "" , "border-color" : ""} );
		});
	}
	function pacientesEspecialidadGinecologica ( ) {
		$(".pacientesEspecialidadGinecologica").on("click", function(){
			const [idSolicitudEspecialista, idDAU, idRCE, idPaciente, tipoAtencion] = $(this).attr("id").split("-");
			const botones = [{ id: 'btnTrasladoGinecologico', value: 'Traslado Ginecológico', class: 'btn btn-primary' }];
			modalFormulario('Solicitud de Especialista', `${raiz}/views/modules/rce/especialista/especialista.php`, `idSolicitudEspecialista=${idSolicitudEspecialista}&dau_id=${idDAU}&rce_id=${idRCE}&idPaciente=${idPaciente}&tipoAtencion=${tipoAtencion}&tipoFormulario=aprobacionEspecialista&bandera=${$("#bandera").val()}`, '#modalVerEspecialista', '40%', 'auto', botones);
			adjuntarPronosticoFormulario();
		});
	}
	function adjuntarPronosticoFormulario ( ) {
		const pronosticos = ajaxRequest(raiz+'/controllers/server/rce/especialista/main_controller.php',"&accion=obtenerPronosticosAltaUrgencia", 'POST', 'JSON', 1,'');
		let html = `<div class="col-md-4">
					<select class="form-control" id="frm_pronostico" name="frm_pronostico">
					<option value="" disabled selected>Pronóstico</option>
					`;
		for ( let pronostico of pronosticos ) {
			html += `<option value="${pronostico.PRONcodigo}">${pronostico.PRONdescripcion}</option>`;
		}
		html += `</select>
				</div>
				`;
		$("#modalVerEspecialista .modal-footer").prepend(html );
	}
	const cargarMapa =  ( ) => {
		if ( tipoMapa == "mapaAdultoPediatrico" ) {
			desplegarTipoMapaSegunUsuario('AP');
			$(".checkBoxsMapas").hide();
			$btnDonanteOrganos.css("margin-left", "");
			$btnDonanteOrganos.css("margin-left", "480px");
			$("#frm_tipo_Atenciones").find('[value="4"]').remove();
			$("#frm_tipo_Atenciones").find('[value="1"]').val(5);
			$("#frm_tipo_Atenciones").trigger("change");
			banderapiso = 'MPISO';
			return;
		}
		if ( tipoMapa == "mapaGinecologico" ) {
			desplegarTipoMapaSegunUsuario('G');
			$(".checkBoxsMapas").hide();
			$btnDonanteOrganos.css("margin-left", "");
			$btnDonanteOrganos.css("margin-left", "480px");
			$("#frm_tipo_Atenciones").val(4);
			$("#frm_tipo_Atenciones").trigger("change");
			$("#frm_tipo_Atenciones").prop("disabled", true);
			banderapiso = 'MGINE';
			return;
		}
		if ( tipoMapa == "mapaFull") {
			const tipoMapaUsuario = $('#tipoMapaUsuario').val();
			desplegarTipoMapaSegunUsuario ( tipoMapaUsuario );
			banderapiso = 'MPISOFULL';
			banderacat	= 'MPISOFULL';
			return;
		}
	};	cargarMapa();
});