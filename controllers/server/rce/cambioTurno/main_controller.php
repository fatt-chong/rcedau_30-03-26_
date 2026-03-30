<?php

session_start();

require_once('../../../../class/Connection.class.php'); $objCon      = new Connection;
require_once("../../../../class/Util.class.php");       $objUtil     = new Util;

switch ($_POST['accion']) {

    case 'sesionEntregaTurno' :

        if ( isset($_SESSION['usuarioActivo']['cambioTurno']['rut']) ){

            unset($_SESSION['usuarioActivo']['cambioTurno']['rut']);
            
        }

        $_SESSION['usuarioActivo']['cambioTurno']['rut'] = array();

    break;



    case 'cambiarTurno' :
    
        require_once("../../../../class/Rce.class.php");       $objRce     = new Rce;
        require_once("../../../../class/Usuarios.class.php");   $objUsuarios     = new Usuarios;
        require_once("../../../../class/Dau.class.php");       $objDau     = new Dau;
        
        $objCon->db_connect();

        try{
            
            $objCon->beginTransaction();
            			
            $parametros = $objUtil->getFormulario($_POST);

            $parametros['rutEntregaTurno']  = $_SESSION['usuarioActivo']['cambioTurno']['rut'][0];

            $parametros['rutRecibeTurno']   = $_SESSION['usuarioActivo']['cambioTurno']['rut'][1];

            $objRce->insertarEntregaTurno($objCon,$parametros);
             
            $datosMedico                         = $objUsuarios->obtenerDatosUsuario_MEDICO_TRATANTE($objCon, $parametros);

            $parametros['usuarioMedicoTratante'] = $datosMedico[0]['idusuario'];

            $objDau->dau_inicioAtencionCambioTurno($objCon, $parametros);

            $objCon->commit();		

            $response  = array("status" => "success");

            echo json_encode($response);

        }catch ( PDOException $e ) {

            $objCon->rollback();

            $response  = array("status" => "error", "message" => $e->getMessage());

            echo json_encode($response);

        }
        
    break;



    default:

        $response  = array("status" => "error");

    break;

}