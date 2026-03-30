<?php
// error_reporting(0);
// echo "asdsa dasd";
$parametrosAEnviar[]                  = array();

$parametrosAEnviar['fechaAnterior']    = date('Y-m-d', strtotime($campos['frm_fechaResumenInicio']));

$parametrosAEnviar['fechaActual']      = date('Y-m-d', strtotime($campos['frm_fechaResumenTermino']));

$parametrosAEnviar['idMedicoUrgencia'] = $idMedicoUrgencia;

$reporteRendimiento                    = $objReporte->obtenerReporteRendimientoCRUrgencia($objCon, $parametrosAEnviar);

unset($parametrosAEnviar);

?>



<!--
################################################################################################################################################
                                                        DESPLIEGUE REPORTE RENDIMIENTO CR URGENCIA
-->
<?php
if ( ! empty($reporteRendimiento) && ! is_null($reporteRendimiento) ) {
?>
<div class="row">
    <div class="container col-lg-12">
        <div class="table-responsive">
            <table id="tablaReporteRendimientoCRUrgencia" class="table table-striped table-bordered">
                <thead class="thead-dark">

                    <tr>

                        <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"  colspan="3" width="15%">&nbsp;</th>

                        <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"  colspan="2" width="12%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">ESI-1</th>

                        <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"  colspan="2" width="12%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">ESI-2</th>

                        <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"  colspan="2" width="13%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">ESI-3</th>

                        <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"  colspan="4" width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">ESI-4</th>

                        <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"  colspan="4" width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">ESI-5</th>

                        <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"  colspan="2" width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Solicitud Esp.</th>

                    </tr>

                    <tr>

                        <th width="7%">Fechas</th>

                        <th width="4%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Cant. A</th>

                        <th width="4%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Cant. E</th>

                        <th width="6%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">A</th>

                        <th width="6%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">E</th>

                        <th width="6%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">A</th>

                        <th width="6%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">E</th>

                        <th width="6%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">A</th>

                        <th width="6%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">E</th>

                        <th width="4%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">A</th>

                        <th width="4%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">IV</th>

                        <th width="4%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">%</th>

                        <th width="4%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">E</th>

                        <th width="4%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">A</th>

                        <th width="4%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">IV</th>

                        <th width="4%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">%</th>

                        <th width="4%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">E</th>

                        <th width="5%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Pedidas</th>

                        <th width="5%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Realizadas</th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    echo desplegarDetalleReporteRendimientoCRUrgencia($reporteRendimiento);

                    ?>

                </tbody>

            </table>
        </div>

    </div>
</div>

<?php
} else {
?>
    <br>

    <div  class="col-lg-1">&nbsp;</div>

    <div  class="col-lg-9">

        <table width="100%" border="0">

            <tr>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"  ><center>¡No hay resultados para desplegar!</center></td>

            </tr>

        </table>

    </div>

    <div  class="col-lg-1">&nbsp;</div>

<?php
}
?>



<!--
################################################################################################################################################
                                                       	            FUNCIONES PHP
-->
<?php
function desplegarDetalleReporteRendimientoCRUrgencia ( $reporteRendimiento ) {

    if ( empty($reporteRendimiento) || is_null($reporteRendimiento) ) {

        return '

        <tr>

            <td colspan="22">&nbsp;</td>

        <tr>

        ';

    }

    $textoADesplegar = '';

    $totalReporteRendimiento = count($reporteRendimiento);

    for ( $i = 0; $i < $totalReporteRendimiento; $i++ ) {

        $textoADesplegar .= '

            <tr>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"  >'.$reporteRendimiento[$i]['fecha'].'</td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$reporteRendimiento[$i]['totalPacientes'].'</td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$reporteRendimiento[$i]['totalPacientesEgresados'].'</td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$reporteRendimiento[$i]['pacientesAtendidosESI1'].'</td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$reporteRendimiento[$i]['pacientesEgresadosESI1'].'</td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$reporteRendimiento[$i]['pacientesAtendidosESI2'].'</td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$reporteRendimiento[$i]['pacientesEgresadosESI2'].'</td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$reporteRendimiento[$i]['pacientesAtendidosESI3'].'</td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$reporteRendimiento[$i]['pacientesEgresadosESI3'].'</td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$reporteRendimiento[$i]['pacientesAtendidosESI4'].'</td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.desplegarNumero($reporteRendimiento[$i]['intravenososESI4']).'</td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.desplegarDivisionPorcentual($reporteRendimiento[$i]['intravenososESI4'], $reporteRendimiento[$i]['pacientesAtendidosESI4']).'%</td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$reporteRendimiento[$i]['pacientesEgresadosESI4'].'</td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$reporteRendimiento[$i]['pacientesAtendidosESI5'].'</td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.desplegarNumero($reporteRendimiento[$i]['intravenososESI5']).'</td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.desplegarDivisionPorcentual($reporteRendimiento[$i]['intravenososESI5'], $reporteRendimiento[$i]['pacientesAtendidosESI5']).'%</td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$reporteRendimiento[$i]['pacientesEgresadosESI5'].'</td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$reporteRendimiento[$i]['totalSolicitudesEspecialistaPedidas'].'</td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$reporteRendimiento[$i]['totalSolicitudesEspecialistaRealizadas'].'</td>

            </tr>

            ';

    }

    return $textoADesplegar;

}

function desplegarNumero ( $numero ) {

    return ( empty($numero) || is_null($numero) ) ? 0 : $numero;

}
function desplegarDivisionPorcentual ( $dividendo, $divisor ) {

    return ( empty($divisor) || is_null($divisor) || $divisor == NULL || empty($dividendo) || is_null($dividendo) || $dividendo == NULL ) ? 0 : round(($dividendo * 100) / $divisor, 1);

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