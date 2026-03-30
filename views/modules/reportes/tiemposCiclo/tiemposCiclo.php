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

    $_SESSION['modulos']["tiemposCiclo"]["worklist"] = $campos;

} else if ( isset($_SESSION['modulos']["tiemposCiclo"]["worklist"]) ) {

    $campos = $_SESSION['modulos']["tiemposCiclo"]["worklist"];

} else {

    $campos = 0;

}

$categorizaciones            = array('ESI-1', 'ESI-2', 'ESI-3', 'ESI-4', 'ESI-5', 'Atendidos');

$totalCategorizaciones       = count($categorizaciones);

$parametros['fechaAnterior'] = date('Y-m-d', strtotime($campos['frm_fechaResumenInicio']));

$parametros['fechaActual']   = date('Y-m-d', strtotime($campos['frm_fechaResumenTermino']));

$adulto                      = 1;

$pediatrico                  = 2;

$parametros['tipoAtencion']  = 0;

$hospitalizado               = 4;

$alta                        = 3;

$parametros['tipoEgreso']    = 0;

$objCon                      = $objUtil->cambiarServidorReporte($parametros['fechaAnterior'], $parametros['fechaActual']);

$version                     = $objUtil->versionJS();
?>



<!--
################################################################################################################################################
                                                       			    CARGA JS
-->
<script type="text/javascript" src="<?=PATH?>/controllers/client/reportes/reportesTiemposCiclo/reporteTiemposCiclo.js?v=<?=$version;?>"></script>



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
                <label class="text-secondary ml-3"><i class="fas fa-file   mifuente20" style="color: #59a9ff;"></i> Resumen Tiempos de Ciclo</label>
                <div class=" col-lg-12 dropdown-divider   mt-2 mb-4 " ></div>
            </div>
            <div class="row ">

            <!-- Fecha Resumen Tiempos de Ciclo Inicio -->
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
                <div  class="form-group col-lg-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="input-group">
                        <button id="btnBuscarResumenesTiemposCiclos" type="button" class="btn btn-outline-primary btn-sm mifuente enviar col-lg-4 mr-3" alt="Buscar" title="Buscar"> <i class="mr-2 fas fa-search"></i> Buscar</button>
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

    <div class="row text-center ml-1">

        <h4 style="text-align:center;">Resumen Tiempos de Ciclo Desde: <?php echo date('d-m-y', strtotime($parametros['fechaAnterior'])); ?>  Hasta: <?php echo date('d-m-Y', strtotime($parametros['fechaActual'])) ?></h4>

    </div>

</div>

<br>



<div  >

    <!-- Div Resumen Tiempos de Ciclo Adultos -->
    <!-- <div id="divResumenTiemposCicloAdultos" class="row"> -->

        <?php

        $objReporte->crearTablaTemporalResumenTiemposCiclo($objCon, $parametros);

        $parametros['tipoAtencion'] = $adulto;

        include('tiemposCicloAdultoPediatrico.php');

        ?>

    <!-- </div> -->

    <!-- <br> -->

    <!-- Div Resumen Tiempos de Ciclo Pediátrico -->
    <!-- <div id="divResumenTiemposCicloPediatricos" class="row"> -->

        <?php

        $parametros['tipoAtencion'] = $pediatrico;

        include('tiemposCicloAdultoPediatrico.php');

        ?>

    <!-- </div> -->

    <!-- <br> -->

    <!-- Div Resumen Tiempos de Ciclo Adultos Hospitalizados-->
    <!-- <div id="divResumenTiemposCicloAdultosHospitalizados" class="row"> -->

        <?php

        $objReporte->crearTablaTemporalTiemposCicloHospitalizacionUrgencia($objCon, $parametros);

        $parametros['tipoAtencion'] = $adulto;

        $parametros['tipoEgreso']   = $hospitalizado;

        $parametros['tipoResumen']  = 'cierre';

        include('tiemposCicloHospitalizacionUrgencia.php');

        ?>

    <!-- </div> -->

    <!-- <br> -->

    <!-- Div Resumen Tiempos de Ciclo Adultos Urgencia-->
    <!-- <div id="divResumenTiemposCicloAdultosUrgencia" class="row"> -->

        <?php

        $parametros['tipoAtencion'] = $adulto;

        $parametros['tipoEgreso']   = $hospitalizado;

        $parametros['tipoResumen']  = 'indicacionEgreso';

        include('tiemposCicloHospitalizacionUrgencia.php');

        ?>

    <!-- </div> -->

    <!-- <br> -->

    <!-- Div Resumen Tiempos de Ciclo Pediátricos Hospitalizados-->
    <!-- <div id="divResumenTiemposCicloAdultosUrgencia" class="row"> -->

        <?php

        $parametros['tipoAtencion'] = $pediatrico;

        $parametros['tipoEgreso']   = $hospitalizado;

        $parametros['tipoResumen']  = 'cierre';

        include('tiemposCicloHospitalizacionUrgencia.php');

        ?>

    <!-- </div> -->

    <!-- <br> -->

    <!-- Div Resumen Tiempos de Ciclo Pediátricos Urgencia-->
    <!-- <div id="divResumenTiemposCicloAdultosUrgencia" class="row"> -->

        <?php

        $parametros['tipoAtencion'] = $pediatrico;

        $parametros['tipoEgreso']   = $hospitalizado;

        $parametros['tipoResumen']  = 'indicacionEgreso';

        include('tiemposCicloHospitalizacionUrgencia.php');

        ?>

    <!-- </div> -->

    <!-- <br> -->

    <!-- Div Resumen Tiempos de Ciclo Adulto Hospitalizado Alta-->
    <!-- <div id="divResumenTiemposCicloAdultosHospitalizadoAlta" class="row"> -->

        <?php

        $parametros['tipoAtencion'] = $adulto;

        $parametros['tipoEgreso']   = $alta;

        $parametros['tipoResumen']  = 'cierre';

        include('tiemposCicloHospitalizacionUrgencia.php');

        ?>

    <!-- </div> -->

    <!-- <br> -->

    <!-- Div Resumen Tiempos de Ciclo Adulto Urgencia Alta-->
    <!-- <div id="divResumenTiemposCicloAdultosUrgenciaAlta" class="row"> -->

        <?php

        $parametros['tipoAtencion'] = $adulto;

        $parametros['tipoEgreso']   = $alta;

        $parametros['tipoResumen']  = 'indicacionEgreso';

        include('tiemposCicloHospitalizacionUrgencia.php');

        ?>

    <!-- </div> -->
<!--  -->
    <!-- <br> -->

    <!-- Div Resumen Tiempos de Ciclo Pediátrico Hospitalizado Alta-->
    <!-- <div id="divResumenTiemposCicloPediatricosHospitalizadosAlta" class="row"> -->

        <?php

        $parametros['tipoAtencion'] = $pediatrico;

        $parametros['tipoEgreso']   = $alta;

        $parametros['tipoResumen']  = 'cierre';

        include('tiemposCicloHospitalizacionUrgencia.php');

        ?>

    <!-- </div> -->

    <!-- <br> -->

    <!-- Div Resumen Tiempos de Ciclo Pediátrico Urgencia Alta-->
    <!-- <div id="divResumenTiemposCicloPediatricosUrgenciaAlta" class="row"> -->

        <?php

        $parametros['tipoAtencion'] = $pediatrico;

        $parametros['tipoEgreso']   = $alta;

        $parametros['tipoResumen']  = 'indicacionEgreso';

        include('tiemposCicloHospitalizacionUrgencia.php');

        ?>

    <!-- </div> -->

    <!-- <br> -->

</div>



<!--
################################################################################################################################################
                                                       	            FUNCIONES PHP
-->
<?php
function desplegarNumero ( $numero ) {

    return ( empty($numero) || is_null($numero) ) ? 0 : $numero;

}
?>



<!--
################################################################################################################################################
                                                       	            CIERRE CONEXIÓN
-->
<?php

$objCon = NULL;

?>