<?php

error_reporting(0);
require("../../../config/config.php");
require_once("../../../class/Connection.class.php");        $objCon     = new Connection();
require_once("../../../class/Util.class.php"); 		        $objUtil    = new Util;
require_once("../../../class/TurnoCRUrgencia.class.php"); 	$objTurno   = new TurnoCRUrgencia();

$objCon->db_connect();

$parametros = $objUtil->getFormulario($_POST);

$infoProfesional = $objTurno->obtenerInfoProfesionalPorRun($objCon, $parametros['idProfesional']);
$parametros['idProfesional'] = $infoProfesional['idusuario'];

$parametros['fechaAnterior'] = $objUtil->fechaAnteriorSegunTurno($parametros['tipoHorarioTurno']);
?>



<!-- 
################################################################################################################################################
                                                     DESPLIEGUE TIEMPOS PROMEDIOS SEGÚN CATEGORIZACIÓN
-->
<div  class="col-lg-1">&nbsp;</div>

<div  class="col-lg-10">

    <table id="tablaCirugiasRealizadas" class="table table-striped table-bordered table-hover table-condensed tablasHisto">

        <thead class="table-primary">

            <tr>

                <th style="text-align:center;" class=" font-weight-bold  mifuente11"  width="100%" colspan="4" style="text-align:center;">Tiempos Promedios según Tipo de Categorización</th>

            </tr>

            <tr>

                <th style="text-align:center;" class=" font-weight-bold  mifuente11"  width="25%" style="text-align: center;">Categorización</th>

                <th style="text-align:center;" class=" font-weight-bold  mifuente11"  width="25%" style="text-align: center;">Categorización a Inicio Atención

                    <table width="100%" class="table-borderless">

                        <thead class="table-primary">

                            <th style="text-align:center;" class=" font-weight-bold  mifuente11"  width="50%" style="text-align: center;">Pacientes</th>

                            <th style="text-align:center;" class=" font-weight-bold  mifuente11"  width="50%" style="text-align: center;">Tiempo Promedio</th>

                        </thead>

                    </table>

                </th>

                <th style="text-align:center;" class=" font-weight-bold  mifuente11"  width="25%" style="text-align: center;">Inicio a Cierre de Atención

                    <table width="100%" class="table-borderless">
                        <thead class="table-primary">

                        <th style="text-align:center;" class=" font-weight-bold  mifuente11" ead>

                            <th style="text-align:center;" class=" font-weight-bold  mifuente11"  width="50%" style="text-align: center;">Pacientes</th>

                            <th style="text-align:center;" class=" font-weight-bold  mifuente11"  width="50%" style="text-align: center;">Tiempo Promedio</th>

                        </thead>

                    </table>

                </th>
                
                <th style="text-align:center;" class=" font-weight-bold  mifuente11"  width="25%" style="text-align: center;">Cierre Atención a Aplicación de Cierre

                    <table width="100%" class="table-borderless">
                        <thead class="table-primary">

                        <th style="text-align:center;" class=" font-weight-bold  mifuente11" ead>

                            <th style="text-align:center;" class=" font-weight-bold  mifuente11"  width="50%" style="text-align: center;">Pacientes</th>

                            <th style="text-align:center;" class=" font-weight-bold  mifuente11"  width="50%" style="text-align: center;">Tiempo Promedio</th>

                        </thead>

                    </table>

                </th>

            </tr>

        </thead>

        <tbody>

            <?php

            echo desplegarTiemposPromedios($objCon, $objTurno, $objUtil, $parametros);

            ?>

        </tbody>

    </table>

</div>

<div  class="col-lg-1">&nbsp;</div>



<!-- 
################################################################################################################################################
                                                       	            FUNCIONES PHP
-->
<?php
function desplegarTiemposPromedios ( $objCon, $objTurno, $objUtil, $parametros ) {

    $textoADesplegar = '';

    $categorizaciones = array('ESI-1', 'ESI-2', 'ESI-3', 'ESI-4', 'ESI-5');

    for ( $i = 0; $i < count($categorizaciones); $i++ ) {

        $parametros['tipoCategorizacion'] = $categorizaciones[$i];

        $textoADesplegar .= desplegarTiemposPromediosSegunCategorizacion($objCon, $objTurno, $objUtil, $parametros);

    }

    return $textoADesplegar;

}



function desplegarTiemposPromediosSegunCategorizacion ( $objCon, $objTurno, $objUtil, $parametros ) {

    $tiempoPromedioCategorizacionInicioAtencion = $objTurno->obtenerTiemposPromedioCategorizacionInicioAtencion($objCon, $parametros);

    $tiempoPromedioInicioAtencionCierreAtencion = $objTurno->obtenerTiemposPromedioInicioAtencionCierreAtencion($objCon, $parametros);

    $tiempoCierreAtencionAplicarCierreAtencion = $objTurno->obtenerTiemposPromedioCierreAtencionAplicacionCierre($objCon, $parametros);

    return '

        <tr>

            <td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="25%">'.$parametros['tipoCategorizacion'].'</td>

            <td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="25%">

                <table width="100%">

                    <tr>
                        
                        <td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="50%" style="text-align: center;">'.$tiempoPromedioCategorizacionInicioAtencion['totalFilas'].'</td>

                        <td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="50%" style="text-align: center;">'.$objUtil->promedioTiempos($tiempoPromedioCategorizacionInicioAtencion).'</td>

                    </tr>

                </table>

            </td>

            <td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="25%">

                <table width="100%">

                    <tr>
                        
                        <td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="50%" style="text-align: center;">'.$tiempoPromedioInicioAtencionCierreAtencion['totalFilas'].'</td>

                        <td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="50%" style="text-align: center;">'.$objUtil->promedioTiempos($tiempoPromedioInicioAtencionCierreAtencion).'</td>

                    </tr>

                </table>

            </td>

            <td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="25%">

                <table width="100%">

                    <tr>
                        
                        <td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="50%" style="text-align: center;">'.$tiempoCierreAtencionAplicarCierreAtencion['totalFilas'].'</td>

                        <td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="50%" style="text-align: center;">'.$objUtil->promedioTiempos($tiempoCierreAtencionAplicarCierreAtencion).'</td>

                    </tr>

                </table>

            </td>

        </tr>

    ';

}
?>