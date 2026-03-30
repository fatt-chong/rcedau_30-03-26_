$(document).ready(function(){
	
	let parametros 		= { 'tipo_solicitud' : $('#tipo_solicitud').val() , 'solicitud_id' : $('#solicitud_id').val() , 'dau_id' : $("#dau_id").val(), };

	switch ( parametros.tipo_solicitud ) {

		case '1' :
		case '2' :
		case '4' :
		case '6' :
		case '8' :



			ajaxContentFast(`${raiz}/views/modules/enfermera/indicaciones_movimiento.php`, parametros,'#contenidoTrazabilidad','', true);

		break;



		case '3' :

			ajaxContentFast(`${raiz}/views/modules/enfermera/indicaciones_movimiento.php`, parametros, '#contenidoTrazabilidad', '', true);
			ajaxContentFast(`${raiz}/views/modules/enfermera/historial_cancelacion_examenes.php`, `solicitud_id=${parametros.solicitud_id}`, '#contenidoHistorialCancelacion', '', true);

		break;

	}

});