<?php
require("../../../config/config.php");
require_once("../../../class/Connection.class.php");        $objCon     = new Connection();
require_once("../../../class/Util.class.php"); 		        $objUtil    = new Util;
require_once("../../../class/TurnoCRUrgencia.class.php"); 	$objTurno   = new TurnoCRUrgencia();

$objCon->db_connect();

$parametros = $objUtil->getFormulario($_POST);

$parametros['fechaAnterior'] = $objUtil->fechaAnteriorSegunTurno($parametros['tipoHorarioTurno']);

$solicitudesEspecialistas = $objTurno->obtenerSolicitudesEspecialistas($objCon, $parametros);

if ( ! $objUtil->existe($solicitudesEspecialistas) ) {

	return;

}

?>



<!--
################################################################################################################################################
                                                       	DESPLIEGUE SOLICITUDES ESPECIALISTAS
-->
<div  class="col-lg-1">&nbsp;</div>

<div  class="col-lg-9">

    <table id="tablaPacientesEspera" class="table table-striped table-bordered table-hover table-condensed tablasHisto">

        <thead>

			<tr>

            	<th style="text-align:center;" colspan="6">Solicitudes Especialistas</th>

			</tr>

			<tr>

				<th style="text-align:center;">DAU</th>

				<th>Nombre Paciente</th>

				<th style="text-align:center;">Fecha Solicitud</th>

				<th style="text-align:center;">Gestión Realizada</th>

				<th>Profesional Especialista</th>

				<th style="text-align:center;">Estado Solicitud</th>

			</tr>

        </thead>

        <tbody>

			<?php

			for ( $i = 0; $i < count($solicitudesEspecialistas); $i++ ) {

				$gestionRealizada = ( $solicitudesEspecialistas[$i]['gestionRealizada'] == 'S' ) ? "Si" : "No";

				$html = "<tr>";

				$html .='<td style="text-align:center;">'.$solicitudesEspecialistas[$i]['idDau'].'</td>';

				$html .='<td>'.$solicitudesEspecialistas[$i]['nombrePaciente'].'</td>';

				$html .='<td style="text-align:center;">'.date("d-m-Y H:i:s", strtotime($solicitudesEspecialistas[$i]['fechaSolicitudEspecialista'])).'</td>';

				$html .='<td style="text-align:center;">'.$gestionRealizada.'</td>';

				$html .='<td style="text-align:center;">'.$solicitudesEspecialistas[$i]['descripcionProfesionalEspecialista'].'</td>';

				$html .='<td style="text-align:center;">'.$solicitudesEspecialistas[$i]['descripcionEstadoSolicitud'].'</td>';

				echo $html;

			}

			?>

        </tbody>

    </table>


</div>

<div  class="col-lg-1">&nbsp;</div>
