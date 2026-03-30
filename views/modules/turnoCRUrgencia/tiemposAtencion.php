<?php

require("../../../config/config.php");
require_once("../../../class/Connection.class.php");        $objCon     = new Connection();
require_once("../../../class/Util.class.php"); 		        $objUtil    = new Util;
require_once("../../../class/TurnoCRUrgencia.class.php"); 	$objTurno   = new TurnoCRUrgencia();

$objCon->db_connect();

$parametros = $objUtil->getFormulario($_POST);

$infoProfesional = $objTurno->obtenerInfoProfesionalPorRun($objCon, $parametros['idProfesional']);

$parametros['idProfesional'] = $infoProfesional['idusuario'];

$parametros['fechaAnterior'] = $objUtil->fechaAnteriorSegunTurno($parametros['tipoHorarioTurno']);

$tiemposAtencion = $objTurno->obtenerTiemposAtencion($objCon, $parametros);
?>



<!-- 
################################################################################################################################################
                                                       	        DESPLIEGUE TIEMPOS ATENCIÓN
-->
<div  class="col-lg-1">&nbsp;</div>

<div  class="col-lg-10">

    <table id="tablaCirugiasRealizadas" class="table table-striped table-bordered table-hover table-condensed tablasHisto">

        <thead class="table-primary">

            <tr>

                <th style="text-align:center;" class=" font-weight-bold  mifuente11" width="100%" colspan="5" style="text-align:center;">Tiempos de Atención Realizados por Profesional que Entrega Turno (Desde Admisión a Inicio Atención)</th>

            </tr>

            <tr>

                <th style="text-align:center;" class=" font-weight-bold  mifuente11" width="20%" style="text-align: center;">Nombre Profesional</th>

                <th style="text-align:center;" class=" font-weight-bold  mifuente11" width="20%" style="text-align: center;">Total Pacientes</th>

                <th style="text-align:center;" class=" font-weight-bold  mifuente11" width="20%" style="text-align: center;">Tiempo Promedio</th>

                <th style="text-align:center;" class=" font-weight-bold  mifuente11" width="20%" style="text-align: center;">Tiempo Mínimo</th>

                <th style="text-align:center;" class=" font-weight-bold  mifuente11" width="20%" style="text-align: center;">Tiempo Máximo</th>

            </tr>

        </thead>

        <tbody>

            <?php

            echo desplegarTiemposAtencion($objUtil, $infoProfesional, $tiemposAtencion);

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
function desplegarTiemposAtencion ( $objUtil, $infoProfesional, $tiemposAtencion ) {

    if ( empty($tiemposAtencion['totalFilas']) || is_null($tiemposAtencion['totalFilas']) ) {

        return '

        <tr>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">&nbsp;</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">&nbsp;</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">&nbsp;</td>
            
            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">&nbsp;</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">&nbsp;</td>

        </tr>

        ';

    }

    return '

        <tr>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="20%">'.$infoProfesional['nombreUsuario'].'</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="20%" style="text-align:center;">'.$tiemposAtencion['totalFilas'].'</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="20%" style="text-align:center;">'.$objUtil->promedioTiempos($tiemposAtencion).'</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="20%" style="text-align:center;">'.$tiemposAtencion['tiempoMinimoAtencion'].'</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="20%" style="text-align:center;">'.$tiemposAtencion['tiempoMaximoAtencion'].'</td>

        </tr>

        ';


}
?>