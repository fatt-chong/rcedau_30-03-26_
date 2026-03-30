<?php 
session_start();
error_reporting(0);
require("../../../../config/config.php");
require(PROYECTO . "/assets/libs/identificacion_hjnc/models/Identificacion.class.php");

$objIdentificacion = new Identificacion();

// DESTRUIR SESION ACTUAL
$objIdentificacion->eliminarDatosSession();
/////////////////////////
?>

<script type="text/javascript" charset="utf-8" src="<?= PATH ?>/assets/libs/identificacion_hjnc/js/modal_identificacion.js?v=<?=date('H:M:s');?>"></script>

<div class="row mb-5 mt-3">
    <div class="col-12 text-center">
        <label style="font-size: 18px;">Para continuar es necesario identificarse, acerque su credencial al lector.</label>
        <img src="<?= PATH ?>/assets/img/codigo-barra.gif" width="50%" />
    </div>
</div>

<form id="form_modal_identificacion" name="form_modal_identificacion" class="m-0" role="form" method="POST" onsubmit="return false">

    <div class="row px-3">
        <div class="col-lg-4 col-md-4 col-sm-12 px-1">
            <input type="text" id="identificacion_usuario" name="identificacion_usuario" class="form-control rut" placeholder="Usuario">
            <input type="hidden" id="identificacion_usuarioCard" name="identificacion_usuarioCard">
        </div>

        <div class="col-lg-4 col-md-4 col-sm-12 px-1">
            <input type="password" id="identificacion_password" name="identificacion_password" class="form-control" placeholder="Contraseña">
        </div>

        <div class="col-lg-4 col-md-4 col-sm-12 px-1">
            <button id="btnModalIdentificacion" class="btn btn-primary btn-block">Iniciar sesión</button>
        </div>
    </div>

    <input type="hidden" id="codigoBarra" name="codigoBarra">
</form>