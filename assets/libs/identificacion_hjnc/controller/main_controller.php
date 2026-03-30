<?php 
session_start();
header("Content-Type: text/html; charset=iso-8859-1");
error_reporting(0);
require("../../../../config/config.php");
require(PROYECTO . "/class/Connection.class.php");
require(PROYECTO . "/assets/libs/identificacion_hjnc/models/Identificacion.class.php");

$objCon = new Connection();
$objIdentificacion = new Identificacion();

require_once(PROYECTO . '/class/Menu_colores.class.php');      $objMenu_colores    = new Menu_colores;
require_once(PROYECTO . '/class/Dau.class.php');               $objDau             = new Dau();

$accion = $_POST["accion"];

switch ($accion) {
    case "iniciar_sesion":
        // if($_POST["identificacion_usuarioCard"] == "000.569.225-5" || $_POST["identificacion_usuarioCard"] == "0005692255"){
        //     $_POST["identificacion_usuario"]  = "16.013.283-3";
        //     $_POST["identificacion_password"]  = "BTW1234F";
        // }
        $usuario = $_POST["identificacion_usuario"];
        $usuario = substr($usuario, 0, -2);
        $login = str_replace('.', '', $usuario);

        $password = $_POST["identificacion_password"];

        $objCon->db_connect();

        try {
            $objCon->beginTransaction();
            $parametros['codigoBarra'] = $_POST['codigoBarra'];

            $logeado = 0;
            $usuario_idusuario = $_SESSION['MM_Username' . SessionName];
            
            $objIdentificacion->updateUsuario($objCon, $usuario_idusuario, $logeado);

            if($parametros['codigoBarra'] != ""){
                $resultado = $objIdentificacion->verificarExistenciaUsuario($objCon,$parametros);
            }else{
                $resultado = $objIdentificacion->iniciarSesion($objCon, $login, $password);
                if (!$resultado) {
                    echo json_encode(array("status" => "not_found", "mensaje" => "Usuario no encontrado"));
                    return;
                }
            }
            // $resultado = $objIdentificacion->iniciarSesion($objCon, $login, $password);
            // if (!$resultado) {
            //     echo json_encode(array("status" => "not_found", "mensaje" => "Usuario no encontrado"));
            //     return;
            // }
            $banderaIguales = 0;
            if($login ==  $_SESSION['MM_RUNUSU' . SessionName] ){
                $banderaIguales = 1;
            }
            $permisos = $objIdentificacion->buscarPermisos($objCon, $resultado[0]["idusuario"]);
            $servicios = $objIdentificacion->buscarServicios($objCon, $resultado[0]["idusuario"]);
            // print('<pre>'); print_r($permisos); print('</pre>');
            // permisos
            $datos = array(
                "usuario" => $resultado[0],
                "permisos" => $permisos,
                "servicios" => $servicios,
            );




            $objIdentificacion->declararVariablesNuevaSession($datos);
            $logeado = 1;
            $usuario_idusuario = $_SESSION['MM_Username' . SessionName];
            
            $objIdentificacion->updateUsuario($objCon, $usuario_idusuario, $logeado);
            if($banderaIguales == 1){
                $_SESSION['SegundaSession'] = '1';
                $_SESSION['tiempoInactividad' . SessionName] = SESSION_TIMEOUT;
                $response = array("status" => "successIguales", "mensaje" => $resultado ,"SESSION_TIMEOUT" => SESSION_TIMEOUT);
            }else{
                 $parametros['usuario']                  = $_SESSION['MM_Username'.SessionName];
                $resultadoConsulta = $objDau->obtenerPerfilUsuario($objCon,$parametros);
                if($resultadoConsulta[0]['contadorPerfilEnfermero'] != ""){
                    $parametrosColor['PROcodigo'] =  $_SESSION['MM_RUNUSU'.SessionName];
                    $rsSelectProfesional = $objMenu_colores->SelectProfesional($objCon,$parametrosColor);
                    if($rsSelectProfesional[0]['TIPROcodigo'] == 3 ){
                        $resultadoConsulta[0]['contadorPerfilMatrona'] = 1;
                    }
                }

                if ( $resultadoConsulta[0]['contadorPerfilMedico'] > 0 ) {
                    $_SESSION['tipo_color'] = '1';
                }   else if ( $resultadoConsulta[0]['contadorPerfilMatrona'] > 0 ) {
                    $_SESSION['tipo_color'] = '2';
                }   else if ( $resultadoConsulta[0]['contadorPerfilTens'] > 0 ) {
                    $_SESSION['tipo_color'] = '3';
                }   else if ( $resultadoConsulta[0]['contadorPerfilEnfermero'] > 0  ) {
                    $_SESSION['tipo_color'] = '4';
                }   else if ( $resultadoConsulta[0]['contadorPerfilAdministrativo'] > 0  ) {
                    $_SESSION['tipo_color'] = '5';
                }   else if ( $resultadoConsulta[0]['contadorPerfilFull'] > 0  ) {
                    $_SESSION['tipo_color'] = '6';
                }   else{
                    $_SESSION['tipo_color'] = '0';
                }
            $response = array("status" => "success", "mensaje" => $resultado);
            }
            // $response = array("status" => "success", "mensaje" => $resultado);
            echo json_encode($response);
        } catch (PDOException $e) {
            $objCon->rollback();
            $response = array("status" => "error", "message" => $e->getMessage());
            echo json_encode($response);
        }
        break;

    default:
        # code...
        break;
}
