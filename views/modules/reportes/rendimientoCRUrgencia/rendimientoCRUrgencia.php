<?php
session_start();
error_reporting(0);
require("../../../../config/config.php");
require_once("../../../../class/Util.class.php");               $objUtil    = new Util;
require_once("../../../../class/Connection.class.php");
require_once("../../../../class/Reportes.class.php"); $objReporte = new Reportes;
require_once("../../../../class/Util.class.php");

if ( $_POST ) {

    $campos = $objUtil->getFormulario($_POST);

    $medicoUrgencia = explode("/", $campos['frm_medicoUrgencia']);

    $idMedicoUrgencia = $medicoUrgencia[0];

    $nombreMedicoUrgencia = $medicoUrgencia[1];

    $_SESSION['modulos']["rendimiendoCRUrgencia"]["worklist"] = $campos;

} else if ( isset($_SESSION['modulos']["rendimiendoCRUrgencia"]["worklist"]) ) {

    $campos = $_SESSION['modulos']["rendimiendoCRUrgencia"]["worklist"];

} else {

    $campos = 0;

}

$parametros['fechaAnterior'] = date('Y-m-d', strtotime($campos['frm_fechaResumenInicio']));

$parametros['fechaActual']   = date('Y-m-d', strtotime($campos['frm_fechaResumenTermino']));

$objCon                      = $objUtil->cambiarServidorReporte($parametros['fechaAnterior'], $parametros['fechaActual']);

$version                     = $objUtil->versionJS();
?>



<!--
################################################################################################################################################
                                                       			    CARGA JS
-->
<script type="text/javascript" src="<?=PATH?>/controllers/client/reportes/reportesRendimientoCRUrgencia/rendimientoCRUrgencia.js?v=<?=$version;?>"></script>



<!--
################################################################################################################################################
                                                       			 DESPLIGUE TÍTULO
-->



<!--
################################################################################################################################################
                                                       			 DESPLIGUE PARÁMETROS TURNO
-->
<div id='divDesplieguecamposBusqueda'>
    <form id="frm_despliegueParametrosBusqueda" name="frm_despliegueParametrosBusqueda" class="formularios" role="form" method="POST">
        <div class="m-3">
            <div class="row">
                <label class="text-secondary ml-3"><i class="fas fa-file   mifuente20" style="color: #59a9ff;"></i> Reporte Rendimiento CR Urgencia</label>
                <div class=" col-lg-12 dropdown-divider   mt-2 mb-4 " ></div>
            </div>
            <div class="row ">

                <!-- Fecha Rendimiento CR Urgencia Inicio -->
                <div  class="form-group col-lg-2">

                    <label class="control-label mifuente">Fecha Resumen Inicio</label>

                    <div class="input-group date" id="date_fecha_desde" data-date-container='#date_fecha_desde'>

                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>

                        <input id="frm_fechaResumenInicio" type="text" class="form-control form-control-sm mifuente" name="frm_fechaResumenInicio" placeholder="Fecha Resumen Inicio" onDrop="return false" data-date-format="dd-mm-yyyy"

                            <?php
                            if ( $campos['frm_fechaResumenInicio'] ) {

                                echo 'value='.$campos['frm_fechaResumenInicio'];

                            }
                            ?>

                            >

                    </div>

                </div>

                <!-- Fecha RendimientoCR Urgencia Término -->
                <div  class="form-group col-lg-2">

                    <label class="control-label mifuente">Fecha Resumen Término</label>

                    <div class="input-group date" id="date_fecha_hasta" data-date-container='#date_fecha_hasta'>

                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>

                        <input id="frm_fechaResumenTermino" type="text" class="form-control form-control-sm mifuente" name="frm_fechaResumenTermino" placeholder="Fecha Resumen Término" onDrop="return false" data-date-format="dd-mm-yyyy"

                            <?php
                            if ( $campos['frm_fechaResumenTermino'] ) {

                                echo 'value='.$campos['frm_fechaResumenTermino'];

                            }
                            ?>

                            >

                    </div>

                </div>

                <!-- Médico de Urgencia -->
                <div class="form-group col-lg-2">

                    <label class="control-label mifuente">Médico de Urgencia</label><br>

                    <div class="input-group">

                        <select id="frm_medicoUrgencia" name="frm_medicoUrgencia" data-live-search="true" class="form-control form-control-sm mifuente selectpicker">

                            <option value="" disabled selected>Seleccione Médico</option>

                            <?php

                            $medicosUrgencia = $objReporte->obtenerMedicosUrgencia($objCon);

                            $totalMedicosUrgencia = count($medicosUrgencia);

                            for ( $i = 0; $i < $totalMedicosUrgencia; $i++ ) {
                            ?>

                                <option value="<?php echo  $medicosUrgencia[$i]['idusuario'].'/'.$medicosUrgencia[$i]['nombreusuario']; ?>" <?php echo ( $idMedicoUrgencia == $medicosUrgencia[$i]['idusuario'] ) ? 'selected' : ''; ?> ><?php echo $medicosUrgencia[$i]['nombreusuario']; ?></option>

                            <?php
                            }
                            ?>

                        </select>

                    </div>

                </div>

                <!-- Botón Buscar / Eliminar / Ver e Imprimir PDF-->
                <div  class="form-group col-lg-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="input-group">
                        <button id="btnBuscarReporteRendimientoCRUrgencia" type="button" class="btn btn-outline-primary btn-sm mifuente enviar col-lg-4 mr-3" alt="Buscar" title="Buscar"> <i class="mr-2 fas fa-search"></i> Buscar</button>
                         <?php
                        if( count($campos) > 1 ) {
                        ?>
                        <button id="btnEliminar" type="button" class="btn btn-outline-danger btn-sm mifuente resultadoBusqueda col-lg-1 mr-3" alt="Limpiar" title="Limpiar"> <i class=" fas fa-times"></i></button>

                        <button id="btnVerPDF" type="button" class="btn btn-outline-danger btn-sm mifuente resultadoBusqueda col-lg-1 mr-3" alt="verPDF" title="verPDF"> <i class=" fas fa-file-pdf"></i></button>

                        <button id="btnVerExcel" type="button" class="btn btn-outline-success btn-sm mifuente resultadoBusqueda col-lg-1 mr-3" alt="verPDF" title="verPDF"> <i class="fas fa-file-excel"></i></button>

                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div>

    </form>

</div>

<br>



<!--
################################################################################################################################################
                                                       			 DESPLIGUE RESULTADOS
-->
<?php
if ( empty($campos) || is_null($campos) ) {

    $objCon = NULL;

    return;

}
?>
<div id='divDesplieguecamposBusqueda'>

    <div class="row">

        <!-- <div  class="col-lg-1">&nbsp;</div> -->

        <div  class="col-lg-12">

            <h4 style="text-align:center;">Reporte Rendimiento CR Urgencia, Doctor(a): <?php echo $nombreMedicoUrgencia; ?></h4>

            <h4 style="text-align:center;">Desde: <?php echo $campos['frm_fechaResumenInicio']; ?> Hasta: <?php echo $campos['frm_fechaResumenTermino']; ?></h4>

        </div>

        <!-- <div  class="col-lg-1">&nbsp;</div> -->

    </div>

</div>

<br>



<div  >

    <!-- Div Despliegue Reporte Rendimiento CR Urgencia -->
    <!-- <div id="divReporteRendimientoCRUrgencia" class="row"> -->

        <?php
        include('detalleRendimientoCRUrgencia.php');
        ?>

    <!-- </div> -->

    <br>

</div>



<!--
################################################################################################################################################
                                                       	            FUNCIONES PHP
-->
<?php
// function desplegarNumero ( $numero ) {

//     return ( empty($numero) || is_null($numero) ) ? 0 : $numero;

// }



// function desplegarDivisionPorcentual ( $dividendo, $divisor ) {

//     return ( empty($divisor) || is_null($divisor) || $divisor == undefined || empty($dividendo) || is_null($dividendo) || $dividendo == undefined ) ? 0 : round(($dividendo * 100) / $divisor, 1);

// }
?>



<!--
################################################################################################################################################
                                                       	            CIERRE CONEXIÓN
-->
<?php

$objCon = NULL;

?>