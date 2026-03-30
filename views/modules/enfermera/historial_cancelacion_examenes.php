<?php
session_start();
ini_set('max_execution_time', 600);
ini_set('memory_limit', '128M');
require("../../../config/config.php");
require_once('../../../class/Connection.class.php'); 	$objCon      		= new Connection;
require_once('../../../class/Laboratorio.class.php'); 	$objLaboratorio 	= new Laboratorio;
require_once('../../../class/Util.class.php'); 			$objUtil 			= new Util;

$objCon->db_connect();

$parametros				= $objUtil->getFormulario($_POST);

$respuestaConsulta 		= $objLaboratorio->consultarMovimientosCancelacionExamenes($objCon, $parametros['solicitud_id']);

$totalRespuestaConsulta = count($respuestaConsulta);

if ( $totalRespuestaConsulta > 0 ) {
?>
	<div class="col-md-12">
					
		<br/>
					
		<h4>Información Examen Cancelado</h4>
				
	</div>
					
	<div class="col-md-12">

    	<table id="tablDetalleWL1" class="table table-condensed">
		
			<thead>
		
				<tr>
	    			<td class="encabezado" style="width: 15%;">
						Fecha Registro
		    		</td>
								
			    	<td class="encabezado" style="width: 15%">
						Usuario
					</td>
									
					<td class="encabezado">
						Motivo Cancelación
					</td>

                    <td class="encabezado">
						Observacion Cancelación
					</td>
									
				</tr>
							
			</thead>
							
			<tbody>

			    <?php
				for ( $i = 0; $i < $totalRespuestaConsulta; $i++ ) {
							
				    echo	'<tr>
								<td style="width: 15%;">'.date("d-m-Y H:i:s", strtotime($respuestaConsulta[$i]['fechaCancela'])).'</td>

								<td style="width: 15%;">'.$respuestaConsulta[$i]['usuarioCancela'].'</td>

                                <td>'.$respuestaConsulta[$i]['descripcionCancela'].'</td>

                                <td>'.$respuestaConsulta[$i]['observacionCancela'].'</td>
					    	<tr>';									 
										  
				}
				?>

			</tbody>

		</table>

	</div>

<?php
}
?>