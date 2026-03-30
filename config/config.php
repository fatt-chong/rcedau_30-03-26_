<?php   
// session_start();
/*************************INSTRUCCIONES*********************************************************
	PARA CAMBIAR DE RAIZ Y SERVIDOR EL PROYECTO, SE DEBEN CAMBIAR LOS SIGUIENTES ARCHIVOS:

	config/config.php          =>  define("PATH",($_SERVER["DOCUMENT_ROOT"])."/MI_RUTA_AQUI");
	config/config.php          =>  define("RAIZ","MI_RAIZ_AQUI"");
	config/config.php          =>  define("BD_CONNECTION",MI_DB_ACA");
	class/Connection.class.php =>  $this->database = "MI_DATABASE";
	assets/js/main.js          =>  var raiz = "/MI_RUTA_AQUI";*/

/*******************************************************************************************/

/******VARIABLES DEFINIDAS PARA SISTEMA PABNET*******/
// define("IP",$_SERVER['SERVER_NAME']);
// echo $_SERVER['SERVER_NAME'];
define("IP","10.6.21.29:8081");
define("IpOnco","http://10.6.21.29:8081/RCEDAU");
define("IpDalca","http://10.6.21.29");

define("URL_BACKEND_BANCO_SANGRE", "http://10.6.21.33:8001");


define("PROYECTO",($_SERVER["DOCUMENT_ROOT"])."/RCEDAU");
define("PATH","/RCEDAU");
define("RAIZ","/RCEDAU");
define("ESTANDAR","/estandar");
define("ANO_INICIO","2018");
define("FirmaPDF","http://10.6.21.19/");
// define("SessionName","_RCEDAU");
define("FECHA_INTEGRACION_DALCA", "2024-05-28");
/******VARIABLES DEFINIDAS PARA SISTEMA PABNET*******/
define("SESSION_TIMEOUT", 10);
$I_RUTA_FTP = "http://10.6.21.29";
/*
if(date('Y-m-d') > "2021-10-07"){
	$I_RUTA_FTP = "http://10.6.21.29";
}*/

define("RutaFTP",$I_RUTA_FTP);
define("FTP_IP","10.6.21.29");
define("FTP_USUARIO","rceurgencia");
define("FTP_CLAVE","rceurgencia");
/*******VARIABLES CORREO***************************/
define("SERVER_MAIL_IP","http://10.6.21.29/");
define("URL_MAIL",SERVER_MAIL_IP."notificaciones/controllers/server/main_controller.php");
define("URL_PDF_SOLICITUD_IMAGENOLOGIA_DALCA", "http://10.6.21.29/imagenologia/solicitudes/Dalca/SOL_");


// ESTADOS INTERCONSULTA (agenda.interconsulta_estado)
define("ESTADO_INT_INICIAL", 1);
define("ESTADO_INT_AGENDADA_1RA_CONSULTA", 2);
define("ESTADO_INT_EGRESADA", 3);
define("ESTADO_INT_ANULADA", 4);
define("ESTADO_INT_AGENDADA_CONTROL", 5);
define("ESTADO_INT_EN_CONTROL", 6);
define("ESTADO_INT_EMITIDA", 0);

// ESTADOS PROCEDIMIENTO/IMAGENOLOGIA (le.le_estado)
define("ESTADO_PRO_INICIAL", 0);
define("ESTADO_PRO_EGRESADA", 3);
define("ESTADO_PRO_AGENDADA", 7);
define("SessionName","_RCEDAU");


// ESTADOS (agenda.estado)
define("ESTADO_AGENDA_CREADA", 1);
define("ESTADO_CALENDARIO_GENERADO", 2);
define("ESTADO_PACIENTES_AGENDADOS", 3);
define("ESTADO_AGENDA_COMPLETA", 4);
define("ESTADO_LIBRE", 10);
define("ESTADO_AGENDADO", 11);
define("ESTADO_ATENDIDO", 12);
define("ESTADO_EGRESADO", 13);
define("ESTADO_NO_ATENDIDO", 14);
define("ESTADO_BLOQUEADO", 15);
define("ESTADO_SIN_CONFIRMAR", 16);
define("ESTADO_PENDIENTE_GENERACIÓN", 20);
define("ESTADO_GENERADO", 21);
define("ESTADO_CONFIRMADO", 17);
define("ESTADO_ADMITIDO", 18);
define("ESTADO_EN_BOX", 19);
define("ESTADO_EN_ATENCIÓN", 22);
define("ESTADO_CANCELADO", 23);
define("ESTADO_TRASLADADO", 24);
define("ESTADO_EN_PAUSA", 25);


define("FTP_IP_DOCUMENTOS","10.6.21.29");
define("FTP_USUARIO_DOCUMENTOS","RCEDAU");
define("FTP_CLAVE_DOCUMENTOS","RCEDAU");

define("FTP_IP_DOCUMENTOSPaciente","10.6.21.29");
define("FTP_USUARIO_DOCUMENTOSPaciente","AdjuntoExterno");
define("FTP_CLAVE_DOCUMENTOSPaciente","AdjuntoExterno");

?>