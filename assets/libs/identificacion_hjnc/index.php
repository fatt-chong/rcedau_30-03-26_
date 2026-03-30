<?php 
session_start();
error_reporting(0);
require("config/config.php");

// define("SESSION_TIMEOUT", 1);

// QUITAR SI SE SUBE AL 4 O 19
if($_SESSION['ContadorSession' . SessionName] == ""){
    $_SESSION['SegundaSession'] = '1';
}else{
    $_SESSION['SegundaSession'] = '0';
}
// echo $_SESSION['SegundaSession']; 
////////////////////////////////////
$_SESSION['ContadorSession' . SessionName]  = $_SESSION['ContadorSession' . SessionName]  - 1;
if ($_SESSION['SegundaSession'] == "1") {

    // DATOS DEL USUARIO
    $_SESSION['permiso' . SessionName]                = $_SESSION['permiso'];
    $_SESSION['MM_UsernameName' . SessionName]        = $_SESSION['MM_UsernameName'];
    $_SESSION['MM_RUNUSU' . SessionName]              = $_SESSION['MM_RUNUSU'];
    $_SESSION['MM_Username' . SessionName]            = $_SESSION['MM_Username'];
    $_SESSION['categoriaID' . SessionName]            = $_SESSION['categoriaID'];
    $_SESSION['idUsuario' . SessionName]              = $_SESSION['idUsuario'];
    $_SESSION['serviciousuario' . SessionName]        = $_SESSION['serviciousuario'];

    // DATOS DEL SERVIDOR
    $_SESSION['SERVER_ADDR' . SessionName]            = $_SESSION['SERVER_ADDR'];
    $_SESSION['MM_Servicio' . SessionName]            = $_SESSION['MM_Servicio'];
    $_SESSION['MM_Servicio_activo' . SessionName]     = $_SESSION['MM_Servicio_activo'];
    $_SESSION['AR_SERVER' . SessionName]              = $_SESSION['AR_SERVER'];
    $_SESSION['CR_SERVER' . SessionName]              = $_SESSION['CR_SERVER'];
    $_SESSION['pyxi_SERVER' . SessionName]            = $_SESSION['pyxi_SERVER'];
    $_SESSION['webservice' . SessionName]             = $_SESSION['webservice'];
    $_SESSION['BD_SERVER' . SessionName]              = $_SESSION['BD_SERVER'];
    $_SESSION['BDR_SERVER' . SessionName]             = $_SESSION['BDR_SERVER'];
    $_SESSION['SESSION_estado']                       = "1";
    
} else if ($_SESSION['ContadorSession' . SessionName] <= 0 && ($_SESSION['MM_UsernameName' . SessionName] != $_SESSION['MM_UsernameName'])) {
    $_SESSION['tiempoInactividad' . SessionName]    = 0;
    $_SESSION['SESSION_estado']                     = "0";
}

if ($_SESSION['SESSION_estado'] == "1") {
    $_SESSION['tiempoInactividad' . SessionName] = SESSION_TIMEOUT; // En minutos
}
?>

<script type="text/javascript" charset="utf-8" src="<?= PATH ?>/assets/libs/identificacion_hjnc/js/identificacion.js?v=<?= $version; ?>"></script>
<input type="hidden" name="tiempoInactividad" id="tiempoInactividad" value="<?= $_SESSION['tiempoInactividad' . SessionName]; ?>">