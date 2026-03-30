<?php
session_start();
error_reporting(0);
require("../../../../config/config.php");
require_once("../../../../class/Util.class.php");               $objUtil    = new Util;
require_once("../../../../class/Connection.class.php");
require_once("../../../../class/Reportes.class.php"); $objReporte = new Reportes;
require_once("../../../../class/Util.class.php");

$objReporte     = new Reportes;
$objUtil        = new Util;


if ( $_POST ) {

    $campos = $objUtil->getFormulario($_POST);

    $_SESSION['modulos']["tiemposCRUrgencia"]["worklist"] = $campos;

} else if ( isset($_SESSION['modulos']["tiemposCRUrgencia"]["worklist"]) ) {

    $campos = $_SESSION['modulos']["tiemposCRUrgencia"]["worklist"];

} else {

    $campos = array();

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
<script type="text/javascript" src="<?=PATH?>/controllers/client/reportes/reportesTiemposCRUrgencia/tiemposCRUrgencia.js?v=<?=$version;?>"></script>



<!--
################################################################################################################################################
                                                       			 DESPLIGUE TÍTULO
-->




<!--
################################################################################################################################################
                                                       			 DESPLIGUE PARÁMETROS RESUMEN
-->
<div id='divDesplieguecamposBusqueda'>
    <form id="frm_despliegueParametrosBusqueda" name="frm_despliegueParametrosBusqueda" class="formularios" role="form" method="POST">
        <div class="m-3">
            <div class="row">
                <label class="text-secondary ml-3"><i class="fas fa-file   mifuente20" style="color: #59a9ff;"></i> Resumen Tiempos CR Urgencia</label>
                <div class=" col-lg-12 dropdown-divider   mt-2 mb-4 " ></div>
            </div>
            <div class="row ">
                <!-- Fecha Resumen Tiempos CR Urgencia Inicio -->
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
                <!-- Fecha Resumen Tiempos CR Urgencia Término -->
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
                <!-- Botón Buscar / Eliminar / Ver e Imprimir PDF-->
                 <div  class="form-group col-lg-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="input-group">
                        <button id="btnBuscarResumenTiemposTurnoCRUrgencia" type="button" class="btn btn-outline-primary btn-sm mifuente enviar col-lg-4 mr-3" alt="Buscar" title="Buscar"> <i class="mr-2 fas fa-search"></i> Buscar</button>
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

    <div class="row mr-3 ml-3">

        <h2 style="text-align:center;">Resumen Tiempos CR Urgencia Desde: <?php echo date('d-m-y', strtotime($parametros['fechaAnterior'])); ?>  Hasta: <?php echo date('d-m-Y', strtotime($parametros['fechaActual'])) ?></h2>

    </div>

</div>

<br>



<div  >

    <!-- Div Despliegue Demanda Urgencia Adulto y Pediátrica -->
    <!-- <div id="divDemandaUrgenciaAdultoPediatrica" class="row"> -->

        <?php
        include('demandaUrgenciaAdultoPediatrica/demandaUrgenciaAdultoPediatrica.php');
        ?>

    <!-- </div> -->

    <br>

    <!-- Div Despliegue Resumen Tiempos de Espera-->
    <!-- <div id="divResumenTiemposEspera" class="row"> -->

        <?php
        include('resumenTiemposEspera/resumenTiemposEspera.php');
        ?>

    <!-- </div> -->

    <br>

    <!-- Div Despliegue Resumen Tiempos de Espera Deciles-->
    <!-- <div id="divResumenTiemposEsperaDeciles" class="row"> -->

        <?php
        include('resumenTiemposEsperaDeciles/resumenTiemposEsperaDeciles.php');
        ?>

    <!-- </div> -->

    <br>

    <!-- Div Despliegue Resumen Cumplimiento Categorización ESI-->
    <!-- <div id="divCumplimientoCategorizacionESI" class="row"> -->

        <?php
        include('cumplimientoCategorizacionESI/cumplimientoCategorizacionESI.php');
        ?>

    <!-- </div> -->

    <br>

    <!-- Div Despliegue Resumen Diagnósticos Inespecíficos-->
    <!-- <div id="divDiagnosticosInespecificos" class="row"> -->

        <?php
        include('diagnosticosInespecificos/diagnosticosInespecificos.php');
        ?>

    <!-- </div> -->

    <br>

    <!-- Div Despliegue Resumen Tiempos Laboratorio-->
    <!-- <div id="divResumenTiemposLaboratorio" class="row"> -->

        <?php
        include('tiemposLaboratorio/resumenTiemposLaboratorio.php');
        ?>

    <!-- </div> -->

    <br>

    <!-- Div Despliegue Resumen Tiempos Imagenología-->
    <!-- <div id="divResumenTiemposImagenologia" class="row"> -->

        <?php
        include('tiemposImagenologia/resumenTiemposImagenologia.php');
        ?>

    <!-- </div> -->

    <br>

</div>



<!--
################################################################################################################################################
                                                       	            FUNCIONES PHP
-->
<?php
function desplegarNumero ( $numero ) {

    return ( empty($numero) || is_null($numero) ) ? 0 : $numero;

}



function desplegarDivisionPorcentual ( $dividendo, $divisor ) {

    return ( empty($divisor) || is_null($divisor) || $divisor == null  ) ? 0 : round(($dividendo * 100) / $divisor, 1);

}
?>



<!--
################################################################################################################################################
                                                       	            CIERRE CONEXIÓN
-->
<?php

$objReporte->eliminarTablaTemporalMuestraDeciles($objCon);

$objReporte->eliminarTablaTemporalTiemposLaboratorio($objCon);

$objReporte->eliminarTablaTemporalTiemposImagenologia($objCon);
// 
$objCon = NULL;

?>
