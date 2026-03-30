$(document).ready(function(){
	/***************EVENTOS**************/

	verificarPerfil();
	// function ConsultaPermisoUsuario (id){
	// 	parametrosPERFIL = 'accion=verificarPermisoUsuario&boton'+id;
	// 	var validarAccionUsuario = function(response){
	// 		switch (response.status) {
	// 		case "success":
	// 		return true;
	// 		break;
	// 		case "errorDePermiso":
	// 			texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-times throb2 text-danger" style="font-size:29px"></i> Error</h4>  <hr>  <p class="mb-0">Usted no tiene este permiso</p></div>';
 //                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
 //                return false;
	// 		break;
	// 		};
	// 	};RCEDAU/controllers/server/categorizacion/main_controller.php
	// 	ajaxRequest(raiz+'/controllers/server/categorizacion/main_controller.php', parametrosPERFIL, 'POST', 'JSON', 1, 'Actualizando ....',validarAccionUsuario);
	
	// }

	function verificarPerfil ( ) {
		parametrosPERFIL = 'accion=obtenerPerfilUsuario';
		var validarAccionUsuario = function(response){
			switch (response.status) {
			case "success":
				if ( response.perfilUsuario.contadorPerfilMedico > 0 ) {
					perfilUsuario = 'medico';
				}	else if ( response.perfilUsuario.contadorPerfilMatrona > 0 ) {
					perfilUsuario = 'matrona';
				}	else if ( response.perfilUsuario.contadorPerfilTens > 0 ) {
					perfilUsuario = 'tens';
				}	else if ( response.perfilUsuario.contadorPerfilEnfermero > 0  ) {
					perfilUsuario = 'enfermero';
				}	else if ( response.perfilUsuario.contadorPerfilAdministrativo > 0  ) {
					perfilUsuario = 'administrativo';
				}	else if ( response.perfilUsuario.contadorPerfilFull > 0  ) {
					perfilUsuario = 'full';
				}else{
				}
				$('#icon-'+perfilUsuario).show();
			break;
			case "errorUsuario":
                location.reload();
			break;
			};
		};
		ajaxRequest(raiz+'/controllers/server/categorizacion/main_controller.php', parametrosPERFIL, 'POST', 'JSON', 1, 'Actualizando ....',validarAccionUsuario);
	}
    view("#contenido");
    
    $("#homeDau").click(function(){
		setPosition('homeDau');
        view("#contenido");
    });
	$(".dropdown-item").click(function(){
		if($(this).hasClass("reportCOVID")){
			return false;
		}
		setPosition($(this).attr("id"));
		unsetSesion();
		removerValidity();
		if ($(this).attr("id") === "turnoCRUrgenciaEnfermeria") {
        ajaxContent(
            `${raiz}/views/modules/turnoCRUrgencia/turnoCRUrgencia.php`,
            'chk_enfermeria=S',
            '#contenido',
            'Cargando...',
            true
        );
    } else {
        view("#contenido");
    }
		  // Aplicar clase active para estilo hover al hacer clic
        $(".dropdown-item").removeClass("active"); // Opcional: quitar clase active de otros elementos
        $(this).addClass("active");
	});
	$("#closeSystem").click(function () {
		window.close();
	});
	$("#cambiarSesion").click(function () {
		// alert();
		// window.close();

        modalFormulario_noCabeceraIdentificacion('',raiz + '/assets/libs/identificacion_hjnc/modal/modal_identificacion.php', {}, "#modal_iniciar_sesion", "modal-md", "", "fas fa-plus");
		// modalMensajeBtnExit('<label class="mifuente text-primary">Iniciar Sesión</label>', raiz + '/assets/libs/identificacion_hjnc/modal/modal_identificacion.php', 'Cancelar=S', "#modal_iniciar_sesion",'modal-md','', 'fas fa-user-friends text-primary');
            
	});
	

	$(window).scroll(function(){
		if ($(this).scrollTop() > 100) {
			$('.scrollToTop').fadeIn();
		} else {
			$('.scrollToTop').fadeOut();
		}
	});
	$('.scrollToTop').click(function(){
		$('html, body').animate({scrollTop : 0}, 800);
		$(this).parent().css('z-index', 99999);
		return false;
	});

	$("#contenidoReporte").click(function () {
		  modalFormularioReporte('<label class="mifuente text-primary">Reporte Gestion Camas</label>',raiz+'/views/modules/reportes/reportesGestionCamas.php','','#contenidoReporteGestionCamas','modal-lg','', 'fas fa-file-excel text-primary','');
            
       // modalFormulario('Reporte Gestion Camas',raiz+'/views/modules/reportes/reportesGestionCamas.php','','#contenidoReporteGestionCamas','modal-lg','primary', '');
    });


});