$(document).ready(function(){
	/***************EVENTOS**************/

	if($('#bandera').val() == 'aprobarIndicacion'){

		var CITcodigo = $('#CITcodigo').val();
        var PACidentificador = $('#PACidentificador').val();
        var idRCE             = $('#idRCE').val();

		ajaxContent(raiz+'/views/modules/gestion_onco/esquema_quimio/verEsquema.php', 'idPaciente='+PACidentificador+'&bandera='+$('#bandera').val(), '#contenido', '', true);



	}else if($('#bandera').val() == 'generarEsquema'){
        var CITcodigo = $('#CITcodigo').val();
        var PACidentificador = $('#PACidentificador').val();
        var idRCE             = $('#idRCE').val();


        // localStorage.setItem('position_id_/gespaciente/index.php', 'mis_traslados_worklist');
        // sessionStorage.setItem('bandera', '1');
        ajaxContent(raiz+'/views/modules/gestion_onco/esquema_quimio/esquema_quimio.php','CITcodigo='+CITcodigo+'&PACidentificador='+PACidentificador+'&idRCE='+idRCE+'&bandera='+$('#bandera').val(),'#contenido','',true);
    }else{
    	// view("#contenido");
    }
	$(".dropdown-item").click(function(){
		if($(this).hasClass("reportCOVID")){
			return false;
		}
		setPosition($(this).attr("id"));
		unsetSesion();
		removerValidity();
		view("#contenido");
	});
	$("#closeSystem").click(function () {
		window.close();
	});
	$("#cambiarSesion").click(function () {
		// alert();
		// window.close();
		modalFormulario('<label class="mifuente text-primary">Iniciar Sesión</label>', raiz + '/assets/libs/identificacion_hjnc/modal/modal_identificacion.php', 'Cancelar=S', "#modal_iniciar_sesion",'modal-md','', 'fas fa-user-friends text-primary');
            
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