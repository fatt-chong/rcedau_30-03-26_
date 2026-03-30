<?php   
session_start();
error_reporting(0);
// print('<pre>'); print_r($_SESSION); print('</pre>');
require("../../config/config.php");
switch($_POST["position_id"]){
	case "viewAdmision":
		echo RAIZ."/views/modules/admision/busquedaAdmision.php";
	break;
	case "viewConsulta":
		echo RAIZ."/views/modules/consulta/consulta.php";
	break;
	case "mapa_piso":
		echo RAIZ."/views/modules/mapa_piso_full/mapa_piso_full.php?tipoMapa=mapaAdultoPediatrico";
	break;
	case "mapa_piso_gine":
		echo RAIZ."/views/modules/mapa_piso_full/mapa_piso_full.php?tipoMapa=mapaGinecologico";
	break;
	case "sol_especialista":
		echo RAIZ."/views/modules/solicitud_especialista/solicitud_especialista.php";
	break;
	case "turnoCRUrgencia":
		echo RAIZ."/views/modules/turnoCRUrgencia/turnoCRUrgencia.php";
	break;

	case "pizarraEnfermeria":
		echo RAIZ."/views/modules/turnoCRUrgencia/pizarraEnfermeria.php";
	// case "pizarraEnfermeria2":
	// 	echo RAIZ."/views/modules/turnoCRUrgencia/pizarraEnfermeria2.php";
	break;
	case "verTurnosCRUrgencia":
		echo RAIZ."/views/modules/turnoCRUrgencia/verTurnosCRUrgencia.php";
	break;
	case "indicaciones":
		echo RAIZ."/views/modules/indicaciones/indicaciones.php";
	break;
	case "sol_aps":
		echo RAIZ."/views/modules/solicitud_aps/solicitud_aps_worklist.php";
	break;

	// case "pacientes_oncologicos":
	// 	echo RAIZ."/views/modules/quimioterapia/pacientes_oncologicos/pacientes_oncologicos_worklist.php";
	// break;
	// case "pacientes_oncologicos_dinamico":
	// 	echo RAIZ."/views/modules/quimioterapia/pacientes_oncologicos/pacientes_oncologicos_dinamico_worklist.php";
	// break;
	// case "ingreso_onco":C:\inetpub\wwwroot\php8site\RCEDAU\views\modules\consulta\consulta.php
	// 	echo RAIZ."/views/modules/gestion_onco/ingresar_paciente/ingresar_paciente_onco.php";
	// break;

	// case "reportes_pacOnco":
	// 	echo RAIZ."/views/modules/reportes/pacientes_oncologicos.php";
	// break;

	// case "gestion_comite":
	// 	echo RAIZ."/views/modules/gestion_onco/ficha_oncologica/comite_oncologico_worklist.php";
	// break;

	// case "mi_solicitud":
	// 	echo RAIZ."/views/modules/gestion_onco/ficha_oncologica/mis_solicitudes_onco_worklist.php";
	// break;

	// case "crear_esquema":
	// 	echo RAIZ."/views/modules/gestion_onco/crear_esquema/worklist_esquemas.php";
	// break;

	// case "Quimioterapia":
	// 	echo RAIZ."/views/modules/gestion_onco/mapaPisoQuimio/mapaPisoQuimio.php";
	// break;
	case "resportesDiariosDAURCE":
		echo RAIZ."/views/modules/reportes/reportesDiariosDAURCE/reportesDiariosDAURCE.php";
	break;
	case "reporteTiemposCiclo":
		echo RAIZ."/views/modules/reportes/tiemposCiclo/tiemposCiclo.php";
	break;
	case "reporteRendimientoCRUrgencia":
		echo RAIZ."/views/modules/reportes/rendimientoCRUrgencia/rendimientoCRUrgencia.php";
	break;
	case "reporteTiemposCRUrgencia":
		echo RAIZ."/views/modules/reportes/tiemposCRUrgencia/tiemposCRUrgencia.php";
	break;
	case "enfermedadesEpidemiologicas":
		echo RAIZ."/views/modules/reportes/enfermedadesEpidemiologicas/enfermedadesEpidemiologicas.php";
	break;

	case "reportes":
		echo RAIZ."/views/modules/reportes/consultaReporte.php";
	break;

	case "reporteGraficoEnfermedadesEpidemiologicas":
		echo RAIZ."/views/modules/reportes/graficoEnfermedadesEpidemiologicas/graficoEnfermedadesEpidemiologicas.php";
	break;

	case "reportes_pat_ges":
		echo RAIZ."/views/modules/reportes/reporte_cie10_ges.php";
	break;


	default:
		echo RAIZ."/views/modules/homeDau.php";
	break;
}
?>