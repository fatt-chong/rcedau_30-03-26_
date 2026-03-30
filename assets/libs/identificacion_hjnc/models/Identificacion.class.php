<?php 
session_start();
require("../../../../config/config.php");

if (!function_exists('array_column')) {
    function array_column(array $input, $columnKey, $indexKey = null)
    {
        $array = array();
        foreach ($input as $value) {
            if (!array_key_exists($columnKey, $value)) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            } else {
                if (!array_key_exists($indexKey, $value)) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if (!is_scalar($value[$indexKey])) {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }
}

class Identificacion
{
    function verificarExistenciaUsuario($objCon,$parametros){
            // $objCon->db_select("acceso");
            $sql="SELECT
                    usuario.idusuario,
                    usuario.rutusuario,
                    usuario.nombreusuario
                    FROM
                    acceso.usuario
                    WHERE usuario.usu_barcode_key = '{$parametros['codigoBarra']}'";
            $datos = $objCon->consultaSQL($sql,"<br>Error al listar Usurio Identificado<br>");
            return $datos;
        }

    function updateUsuario($objCon, $usuario_idusuario, $logeado)
    {

        $sql = "UPDATE acceso.usuario SET logeado = '$logeado'  WHERE usuario.idusuario = '$usuario_idusuario' ";

        $objCon->ejecutarSQL($sql, "Error al actualizar médico involucrado en updateUsuario");
    }

    function iniciarSesion($objCon, $login, $password)
    {

        $sql = "SELECT
                    usuario.idusuario,
                    usuario.rutusuario,
                    usuario.claveacceso,
                    usuario.nombreusuario,
                    usuario.usu_activo,
                    usuario_has_servicio.idservicio,
                    servicio.nombre
                FROM
                    acceso.usuario
                INNER JOIN acceso.usuario_has_servicio ON usuario.idusuario = usuario_has_servicio.idusuario
                INNER JOIN acceso.servicio ON usuario_has_servicio.idservicio = servicio.idservicio  																														
                WHERE 
                    usuario.rutusuario='$login' 
                AND usuario_has_servicio.estado = 'A' 
                AND AES_DECRYPT(claveacceso,'idusuario')='$password'";

        $datos = $objCon->consultaSQL($sql, "<br>ERROR AL iniciarSesion<br>");
        return $datos;
    }

    function buscarPermisos($objCon, $usuario_idusuario)
    {

        $sql = "SELECT rol_idrol FROM acceso.usuario_has_rol WHERE usuario_idusuario = '$usuario_idusuario' ORDER BY rol_idrol ASC";

        $datos = $objCon->consultaSQL($sql, "<br>ERROR AL buscarPermisos<br>");
        return array_column($datos, "rol_idrol");
    }

    function buscarServicios($objCon, $usuario_idusuario)
    {

        $sql = "SELECT idservicio FROM acceso.usuario_has_servicio WHERE idusuario = '$usuario_idusuario'";

        $datos = $objCon->consultaSQL($sql, "<br>ERROR AL buscarServicios<br>");
        return array_column($datos, "idservicio");
    }

    /**
     * Estableciendo variables de la nueva sesión
     */
    function declararVariablesNuevaSession($datos)
    {
        // DATOS DEL USUARIO
        $_SESSION['MM_Username' . SessionName]            = $datos["usuario"]['idusuario'];
        $_SESSION['MM_UsernameName' . SessionName]        = $datos["usuario"]['nombreusuario'];
        $_SESSION['MM_RUNUSU' . SessionName]              = $datos["usuario"]['rutusuario'];
        $_SESSION['idUsuario' . SessionName]              = $datos["usuario"]['idusuario'];
        $_SESSION['MM_Servicio' . SessionName]            = $datos["usuario"]['idservicio'];
        $_SESSION['permiso' . SessionName]                = $datos['permisos'];
        $_SESSION['serviciousuario' . SessionName]        = $datos['servicios'];

        $_SESSION['categoriaID' . SessionName]            = $_SESSION['categoriaID'];

        // DATOS DEL SERVIDOR
        $_SESSION['SERVER_ADDR' . SessionName]            = $_SESSION['SERVER_ADDR'];
        $_SESSION['MM_Servicio_activo' . SessionName]     = $_SESSION['MM_Servicio_activo'];
        $_SESSION['AR_SERVER' . SessionName]              = $_SESSION['AR_SERVER'];
        $_SESSION['CR_SERVER' . SessionName]              = $_SESSION['CR_SERVER'];
        $_SESSION['pyxi_SERVER' . SessionName]            = $_SESSION['pyxi_SERVER'];
        $_SESSION['webservice' . SessionName]             = $_SESSION['webservice'];
        $_SESSION['BD_SERVER' . SessionName]              = $_SESSION['BD_SERVER'];
        $_SESSION['BDR_SERVER' . SessionName]             = $_SESSION['BDR_SERVER'];

        // $_SESSION['tiempoInactividad' . SessionName] = SESSION_TIMEOUT;
        $_SESSION['ContadorSession' . SessionName]          = 2;

        // Se reinicia el estado de la sesión
        $_SESSION['SESSION_estado'] = "1";
    }

    /**
     * Eliminando datos de la actual sesión
     */
    function eliminarDatosSession()
    {
        $_SESSION['tiempoInactividad' . SessionName] = 0;

        // Se establece un estado de la sesión
        $_SESSION['SegundaSession'] = "0";
        $_SESSION['SESSION_estado'] = "0";
    }
}
