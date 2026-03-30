<?php
error_reporting(0);
require("../../../config/config.php");
require_once("../../../class/Connection.class.php");        $objCon     = new Connection();
require_once("../../../class/Util.class.php"); 		        $objUtil    = new Util;
require_once("../../../class/TurnoCRUrgencia.class.php"); 	$objTurno   = new TurnoCRUrgencia();

$objCon->db_connect();
$parametros                     = $objUtil->getFormulario($_POST);
$parametros['fechaAnterior']    = $objUtil->fechaAnteriorSegunTurno($parametros['tipoHorarioTurno']);
$cirugiasRealizadas             = $objTurno->obtenerCirugiasRealizadas($objCon, $parametros);
?>



<!-- 
################################################################################################################################################
                                                       	        DESPLIEGUE CIRUGÍAS REALIZADAS
-->
<div  class="col-lg-1">&nbsp;</div>
<div  class="col-lg-10">
    <table id="tablaCirugiasRealizadas" class="table table-striped table-bordered table-hover table-condensed tablasHisto">
        <thead class="table-primary">
            <tr>
                <th style="text-align:center;" class=" font-weight-bold  mifuente11" width="100%" colspan="6" style="text-align:center;">Cirugías o Procedimientos Realizados por Profesional que Entrega Turno</th>
            </tr>
            <tr>
                <th style="text-align:center;" class=" font-weight-bold  mifuente11" width="20%">Nombre Paciente</th>
                <th style="text-align:center;" class=" font-weight-bold  mifuente11" width="10%" style="text-align:center;">RUN Paciente</th>
                <th style="text-align:center;" class=" font-weight-bold  mifuente11" width="38%">Diagnóstico Pre-Quirúrgico</th>
                <th style="text-align:center;" class=" font-weight-bold  mifuente11" width="12%" style="text-align:center;">Cod. Prestación</th>
                <th style="text-align:center;" class=" font-weight-bold  mifuente11" width="10%" style="text-align:center;">Nº Cirujano</th>
                <th style="text-align:center;" class=" font-weight-bold  mifuente11" width="10%" style="text-align:center;">Tipo Cirugía</th>
            </tr>
        </thead>

        <tbody>

            <?php

            echo desplegarCirugiasRealizadas($cirugiasRealizadas, $objUtil);

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
function desplegarCirugiasRealizadas ( $cirugiasRealizadas, $objUtil ) {

    if ( empty($cirugiasRealizadas) || is_null($cirugiasRealizadas) ) {

        return '

        <tr>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" colspan="6">&nbsp;</td>

        <tr>

        ';

    }

    $textoADesplegar = '';

    $totalCirugiasRealizadas = count($cirugiasRealizadas);

    for ( $i = 0, $desplazamiento = 0; $i < $totalCirugiasRealizadas; $i = $i + $desplazamiento ) {

        $desplazamiento = 0;

        $textoADesplegar .= '

            <tr>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="20%">'.$cirugiasRealizadas[$i]['nombrePaciente'].'</td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="10%" style="text-align:center;">'.$objUtil->rut($cirugiasRealizadas[$i]['runPaciente'].'-'.$objUtil->generaDigito($cirugiasRealizadas[$i]['runPaciente'])).'</td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="40%">

                    <ul>';

                    $idTablaQuirurgica = $cirugiasRealizadas[$i]['idTablaQuirurgica'] ;

                    for ($j = $i; $idTablaQuirurgica == $cirugiasRealizadas[$j]['idTablaQuirurgica']; $j++ ) {

                        $textoADesplegar .= '

                            <li>'.$cirugiasRealizadas[$j]['nombreIntervencion'].'</li>
                            
                        ';

                        $idTablaQuirurgica = $cirugiasRealizadas[$j]['idTablaQuirurgica'];

                    }

        $textoADesplegar .= '                

                    </ul>    

                </td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="10%" style="text-align:center;">


                    <ul>';

                    $idTablaQuirurgica = $cirugiasRealizadas[$i]['idTablaQuirurgica'] ;

                    for ($j = $i; $idTablaQuirurgica == $cirugiasRealizadas[$j]['idTablaQuirurgica']; $j++ ) {

                        $textoADesplegar .= '

                            <li>'.$cirugiasRealizadas[$j]['idIntervencion'].'</li>
                            
                        ';

                        $idTablaQuirurgica = $cirugiasRealizadas[$j]['idTablaQuirurgica'];

                        $desplazamiento++;

                    }

        $textoADesplegar .= '                

                    </ul>  

                </td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="10%" style="text-align:center;">Cirujano 1</td>';

                if ( $cirugiasRealizadas[$i]['descripcionTipoSolicitud'] == 'Urgencia' ) {

                    $tipoCirugia = 'U';

                }

                if ( $cirugiasRealizadas[$i]['descripcionTipoSolicitud'] == 'Normal' ) {

                    $tipoCirugia = 'N';

                }

                if ( $cirugiasRealizadas[$i]['descripcionTipoSolicitud'] == 'Procedimiento' ) {

                    $tipoCirugia = 'P';

                }

        $textoADesplegar .= '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="10%" style="text-align:center;">'.$tipoCirugia.'</td>

            <tr>

            ';

    }

    return $textoADesplegar;

}



function fechaAnterior ( $tipoHorarioTurno ) {

    $fechaAnterior  = date( 'Y-m-d', strtotime( date('Y-m-d')  . ' -1 day' ) );

    if ( empty($tipoHorarioTurno) || is_null($tipoHorarioTurno) ) {      

        $fechaAnterior .= ' 08:00:00';

        return $fechaAnterior;

    }

    $horaFechaAnterior = ( $tipoHorarioTurno == 1 || $tipoHorarioTurno == 2 || $tipoHorarioTurno == 3 || $tipoHorarioTurno == 4 || $tipoHorarioTurno == 5 ) ? '08:00:00' : '09:00:00';

    $fechaAnterior .= ' '.$horaFechaAnterior;

    return $fechaAnterior;

}
?>



<!-- 
################################################################################################################################################
                                                       	            CIERRE CONEXIÓN
-->
<?php

$objCon = NULL;

?>