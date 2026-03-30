<?php   
session_start();
if((!isset($_SESSION["MM_UsernameName".SessionName]) || $_SESSION["MM_UsernameName".SessionName] == NULL)){
	$response = array("status" => "sesion_expirada", "message" => "La sesión expiro. Por favor, inicie sesión nuevamente");
	echo json_encode($response);
	exit();
}

?>