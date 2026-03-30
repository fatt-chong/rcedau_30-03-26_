$(document).ready(function(){
	banderapiso = 'INDICACIONESENF';
	$("#btnEliminar").click(function(){
		unsetSesion();
		ajaxContent(raiz+'/views/modules/indicaciones/indicaciones.php','','#contenido','', true);
	});
	$("#btnBuscarPaciente").click(function(){
		ajaxContent(raiz+'/views/modules/indicaciones/indicaciones.php',$("#frm_enf_indicaciones").serialize(),'#contenido','', true);
	});
	$(".btnUpdateIndicaciones").click(function(){
		let mystr  = $(this).attr('id');
		let myarr  = mystr.split("|");

		modalFormulario_noCabecera('', raiz+"/views/modules/enfermera/despliegueIndicaciones.php", `dau_id=${myarr[0]}&regId=${myarr[1]}`, "#detalleIndicacion", "modal-lg", "", "fas fa-plus");
			// C:\inetpub\wwwroot\php8site\RCEDAU\views\modules\enfermera\despliegueIndicaciones.php
		// modalFormulario(`Detalle Indicación ${myarr[0]}`, `${raiz}/views/modules/Enfermera/despliegueIndicaciones.php`, `dau_id=${myarr[0]}&regId=${myarr[1]}`, '#detalleIndicacion', '80%', '80%');
	});
	tabla3("#tablaContenidoIndicacionesResumen");
});