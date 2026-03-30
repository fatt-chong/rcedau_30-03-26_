<iframe height="100%" width="100%" hidden>

<?php

//session_start();
error_reporting(0);
ini_set('post_max_size', '512M');
ini_set('memory_limit', '1G');
set_time_limit(0);
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Cache-Control: no-store");

require_once('../../../../../estandar/TCPDF-main/tcpdf.php');
require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php'); 			$objCon      			= new Connection; $objCon->db_connect();
require_once('../../../../class/Util.class.php');       			$objUtil     			= new Util;
require_once('../../../../class/Admision.class.php');   			$objAdmision 			= new Admision;
require_once('../../../../class/RegistroClinico.class.php'); 		$objRegistroClinico		= new RegistroClinico;
// require_once('../../../../class/Pronostico.class.php'); 		$objPronostico  		= new Pronostico;
require_once("../../../../class/Dau.class.php" );  					$objDetalleDau       	= new Dau;
require_once("../../../../class/Rce.class.php" );  					$objRce       			= new Rce;
require_once("../../../../class/Servicios.class.php"); 				$objServicio     		= new Servicios;
require_once("../../../../class/Agenda.class.php" );  				$objAgenda        		= new Agenda;
require_once("../../../../class/Usuarios.class.php" );  			$objUsuarios        	= new Usuarios;
require_once('../../../../class/Formulario.class.php'); 			$objFormulario 			= new Formulario;
require_once('../../../../class/Especialista.class.php'); 			$objEspecialista		= new Especialista;
require_once('../../../../class/HojaHospitalizacion.class.php');	$objHojaHospitalizacion	= new HojaHospitalizacion;
require_once('../../../../class/Bitacora.class.php'); 				$objBitacora			= new Bitacora;
require_once('../../../../class/AltaUrgencia.class.php'); 			$objAltaUrgencia		= new AltaUrgencia;

class MYPDF extends TCPDF {
    //Page header
    public function Test( $ae ) {
        if( !isset($this->xywalter) ) {
            $this->xywalter = array();
        }
        $this->xywalter[] = array($this->GetX(), $this->GetY());
    }
}

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('HJNC-RCE');
$pdf->SetTitle('Datos de Atención de Urgencia DAU');
$pdf->SetSubject('Formularios');
$pdf->SetKeywords('RCE, Formularios');
//$pdf->SetHeaderData('logo_informe2.jpg', PDF_HEADER_LOGO_WIDTH,'HOSPITAL REGIONAL DE ARICA Y PARINACOTA','FORMULARIO DE CONSTANCIA INFORMACION AL PACIENTE GES');
$pdf->setHeaderFont(Array('helvetica', '', 9));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(10, 3, 10);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setLanguageArray($l);
$pdf->setFontSubsetting(false);
$pdf->SetFont('helvetica', '', 12, '', true);

// add a page
$pdf->AddPage();



$parametros                 	= $objUtil->getFormulario($_POST);
$parametros['rce_id']			= $parametros['idRCE'];
$datosPaciente              	= $objHojaHospitalizacion->obtenerDatosPaciente($objCon, $parametros);
$datosHojaHospitalizacion   	= $objHojaHospitalizacion->obtenerDatosHojaHospitalizacion($objCon, $parametros);
$eventos 						= 1;
// $listadoIndicaciones  			= $objRegistroClinico->listarIndicacionesRCE($objCon, $parametros, $eventos);
$parametros['dau_id'] 			= $parametros['idDau'];
$rsRce                      	= $objRegistroClinico->consultaRCE($objCon,$parametros);
$rsMovimientoEVOxEspe2 			= $objRegistroClinico->MovimientoEVOxEspe2($objCon,$parametros);
$parametrosBitacora['BITid'] 	= $parametros['dau_id'];
$rsBitacora                   	= $objBitacora->listarBitacoraHosp($objCon, $parametrosBitacora);

$rsAltaUrgencia                 = $objAltaUrgencia->SelectAltaUrgencia($objCon, $parametros['dau_id'] );

$rut = $datosPaciente[0]['rut_extranjero'];

if ( ! is_null($datosPaciente[0]['rut']) && ! empty($datosPaciente[0]['rut']) ) {

	$rut = $objUtil->setRun_addDV($datosPaciente[0]['rut']);

}

$listarSignos 		  		= $objRce->listarSignosVitales($objCon,$datosPaciente[0]['id_paciente'], $parametros['rce_id']);


$fechaHoraMot 			= $rsRce[0]['dau_inicio_atencion_fecha'];
list($fecha, $hora) 	= explode(' ', $fechaHoraMot);
$fechaMot 				= $objUtil->fechaInvertida($fecha);
$horaMot 				= substr($hora, 0, -3);

// $datosHojaHospitalizacion[0]['motivoIngreso'] 			= str_replace("<", "&#60;", $datosHojaHospitalizacion[0]['motivoIngreso']);
// $datosHojaHospitalizacion[0]['motivoIngreso'] 			= str_replace("&#60;br>", "<br>", $datosHojaHospitalizacion[0]['motivoIngreso']);
// $datosHojaHospitalizacion[0]['antecedentesMorbidos'] 	= str_replace("<", "&#60;", $datosHojaHospitalizacion[0]['antecedentesMorbidos']);
// $datosHojaHospitalizacion[0]['antecedentesMorbidos'] 	= str_replace("&#60;br>", "<br>", $datosHojaHospitalizacion[0]['antecedentesMorbidos']);
$html= '
		<head>
		<style type="text/css">
			.divAncho{
				width:1;
				}
			.enoform{
				border: 1px solid black;
				}
			.bordeCeldaGrande{
				border:0px solid white;
			}
			.bordeCompleto{
				border-bottom:1px solid black;
				border-left:1px solid black;
				border-right:1px solid black;
				border-top:1px solid black;
			}
			.bordeCelda{
				border-bottom:1px solid grey;
			}
			.enoformSin{
				border: 0px solid white;
				}
			.enoformSin td{
				border: 0px solid white;
				}
			  hr {
    height: 0.01px; /* Grosor del hr */  }
			.backBlue{
				background-color:#CCC;
				}
			.ultrachico{
				font-family:"SourceSansPro-Regular", Arial, Helvetica;
				font-size:6pt;
				}
			.superchico{
				font-family:"SourceSansPro-Regular", Arial, Helvetica;
				font-size:7pt;
				}
			
			p {
				line-height: 1.2;
				}
			.titulo {
				font-family:"SourceSansPro-Bold", Arial, Helvetica;
				font-size:10pt;
				}
				.negrita {
    font-weight: bold;
  }
			.simple {
				font-family:"SourceSansPro-Bold", Arial, Helvetica;
				font-size:10pt;
				font-weight:bold;}

		</style>
		</head>


		<table class="bordeCeldaGrande" cellspacing="5" border="0" width="100%">

			<tr nobr="true">
				<td>
					<table width="100%">
						<tr>
							<td width="75%"><img src="/estandar/img/logo_gobierno_chile.jpg" width="55" height="55"></td>
							
							<td width="25%">
							<br>
								<p class="titulo" align="right">
									<small style="" >Fecha : '.date("d-m-Y", strtotime($datosPaciente[0]['fechaHospitalizacion'])).'</small>
									<small style="" >Hora  : '.date("H:i:s", strtotime($datosPaciente[0]['fechaHospitalizacion'])).'</small>
								</p>
							</td>
						</tr>
						<tr> <td width="100%" colspan="2">
								<p class="titulo" align="center">HOJA HOSPITALIZACIÓN</p>
							</td> 
							</tr>
					</table>
				</td>
			</tr>
		</table>
		<table width="100%">
			<tr>
				<td><strong style="" >Datos del Paciente</strong></td>
			</tr>
			<br>
			<tr> 
				<td width="20%"><small style="" class="negrita">NOMBRE COMPLETO</small></td>
				<td width="35%"><small style="" >'.strtoupper($datosPaciente[0]['nombrePaciente']).'</small></td>

				<td width="10%" ><small style="" class="negrita">R.U.N.</small></td>
				<td width="15%"><small style=""> '.$rut.'</small></td>

				<td width="10%" ><small style="" class="negrita">EDAD</small></td>
				<td width="10%"><small style="">'.$datosPaciente[0]['edadPaciente'].'</small></td>

			</tr>
			<tr>
				<td width="10%" ><small style="" class="negrita">RELIGIÓN</small></td>
				<td width="10%"><small style="">'.(isset($datosPaciente[0]['religion_descripcion']) ? $datosPaciente[0]['religion_descripcion'] : '-').'</small></td>
			</tr>
			<tr> 
				<td width="100%"><small style="" class="negrita">ANAMNESIS Y MOTIVO DE INGRESO</small></td>
			</tr>
			<tr> 
				<td style="text-align: justify;" width="100%"><small style="">'.nl2br(htmlspecialchars($datosHojaHospitalizacion[0]['motivoIngreso'])).'</small></td>
			</tr>
			<tr> 
				<td width="100%"><small style="" class="negrita">Hipotesis Diagnóstica ('.$fechaMot.' a las '.$horaMot.' realizado por '.$objRegistroClinico->SelectUsuario($objCon,$rsRce[0]['dau_inicio_atencion_usuario']).')</small></td>
			</tr>
			<tr> 
				<td width="100%" style="text-align: justify;"><small style="" >'.nl2br(htmlspecialchars($rsRce[0]['regHipotesisInicial'])).'</small></td>
			</tr>
			<tr> 
				<td width="80%"><small style="" class="negrita">ANTECEDENTES MÓRBIDOS Y OPERACIONES</small></td>
				<td width="20%" style="" style="text-align:left" class="negrita"><small style="" >ALERGIAS :   SI ___    NO ___</small></td>
			</tr>
			<tr> 
				<td style="text-align: justify;" width="100%"><small style="" >'.nl2br(htmlspecialchars($datosHojaHospitalizacion[0]['antecedentesMorbidos'])).'</small></td>
			</tr>
		</table>
	
		<hr>
		<table  cellspacing="4" border="0" width="100%">
			<tr  class="">
				<td ><strong style="">Signos vitales</strong></td>
			</tr>
			<br>
			<tr>
				<td width="100%">
				
					<table cellspacing="0" border="0" width="100%">
						';

						if ( is_null($listarSignos[0]['SVITALfecha']) || empty($listarSignos[0]['SVITALfecha']) ) {
							$html .= '	<tr>
											<td width="100%"><small style="" ></small></td>
										</tr>';
						} else {

							$html .= '

							<tr>
								<td width="12%" align="center"><small style="" ><strong style="" >Usuario y Fecha</strong></small></td>
								<td  align="center"><small style="" ><strong>PAS / PAD</strong></small></td>
								<td  align="center"><small style="" ><strong>PAM</strong></small></td>
								<td  align="center"><small style="" ><strong>FC</strong></small></td>
								<td  align="center"><small style="" ><strong>SAT</strong></small></td>
								<td   align="center"><small style="" ><strong>FIO2</strong></small></td>
								<td   align="center"><small style="" ><strong>FR</strong></small></td>
								<td   align="center"><small style="" ><strong>HGT</strong></small></td>';
								if($datosPaciente[0]['dau_atencion'] == 3){ 
								$html .= '<td   align="center"><small style="" ><strong>LCF</strong></small></td>
								<td   align="center"><small style="" ><strong>RBNE</strong></small></td>';
						 		} 
				$html 		.= '<td   align="center"><small style="" ><strong>GCS</strong></small></td>
								<td   align="center"><small style="" ><strong>T°</strong></small></td>
								<td   align="center"><small style="" ><strong>EVA</strong></small></td>
							</tr>';

							for ($r=0; $r<count($listarSignos) ; $r++) {
								$html .= 
							'<tr>';
								$html.= '
								<td align="center"><small style="" >'.$listarSignos[$r]['SVITALusuario'].'<br>'.date("d-m-Y H:i",strtotime($listarSignos[$r]['SVITALfecha'])).'</small></td>';
								$html .= '
								<td align="center"><small style="" >'.$listarSignos[$r]['SVITALsistolica'].' / '.$listarSignos[$r]['SVITALdiastolica'].'</small></td>
								<td align="center"><small style="" >'.$listarSignos[$r]['SVITALPAM'].'</small></td>
								<td align="center"><small style="" >'.$listarSignos[$r]['SVITALpulso'].'</small></td>
								<td align="center"><small style="" >'.$listarSignos[$r]['SVITALsaturacion'].'</small></td>
								<td align="center"><small style="" >'.$listarSignos[$r]['FIO2'].'</small></td>
								<td align="center"><small style="" >'.$listarSignos[$r]['SVITALfr'].'</small></td>
								<td align="center"><small style="" >'.$listarSignos[$r]['SVITALHemoglucoTest'].'</small></td>
									';
								if($datosPaciente[0]['dau_atencion'] == 3){ 
									$html .=   
								'<td align="center"><small style="" >'.$listarSignos[$r]['SVITALfeto'].'</small></td>
								<td align="center"><small style="" >'.$listarSignos[$r]['SVITALrbne'].'</small></td>';
						 		} 
								$html 		.= '
								<td align="center"><small style="" >'.$listarSignos[$r]['SVITALglasgow'].'</small></td>
								<td align="center"><small style="" >'.$listarSignos[$r]['SVITALtemperatura'].'</small></td>
								<td align="center"><small style="" >'.$listarSignos[$r]['SVITALeva'].'</small></td>
							</tr>';
							}
						}
						$html .= '
					</table>
				</td>
			</tr>
		</table>';
		$fechaHoraMot 			= $rsRce[0]['dau_inicio_atencion_fecha'];
		list($fecha, $hora) 	= explode(' ', $fechaHoraMot);
		$fechaMot 				= $objUtil->fechaInvertida($fecha);
		$horaMot 				= substr($hora, 0, -3);



		$rsRce[0]['regMotivoConsulta'] = str_replace("<", "&#60;", $rsRce[0]['regMotivoConsulta']); 
		// $rsUsuario                      = $objRegistroClinico->SelectUsuario($objCon,$rsRce[0]['dau_inicio_atencion_usuario']);

		$rsRce[0]['regMotivoConsulta'] = str_replace("<", "&#60;", $rsRce[0]['regMotivoConsulta']);
		$rsRce[0]['regMotivoConsulta'] = str_replace("&#60;br>", "<br>", $rsRce[0]['regMotivoConsulta']);
		$rsRce[0]['regHipotesisInicial'] = str_replace("<", "&#60;", $rsRce[0]['regHipotesisInicial']);
		$rsRce[0]['regHipotesisInicial'] = str_replace("&#60;br>", "<br>", $rsRce[0]['regHipotesisInicial']);

		$html .= '
		<hr>
		<table  cellspacing="4" border="0" width="100%">
			<tr>
				<td><strong  style="" >Registro Médico</strong></td>
			</tr>
			
			';
			foreach ($rsMovimientoEVOxEspe2 as $rsMovimientoEVOxEspe2_clave => $rsMovimientoEVOxEspe2_valor) { 
				if($rsMovimientoEVOxEspe2_valor['tipo'] == 1){
					$rsMovimientoEVOxEspe2_valor['titulo'] = "Evolución Especialista (".$rsMovimientoEVOxEspe2_valor['titulo'].")";
				}if($rsMovimientoEVOxEspe2_valor['tipo'] == 3){
					$rsMovimientoEVOxEspe2_valor['titulo'] = "Solicitud Especialista (".$rsMovimientoEVOxEspe2_valor['titulo'].")";
				}
				$fechaHora 			= $rsMovimientoEVOxEspe2_valor['fecha'];
				list($fecha, $hora) = explode(' ', $fechaHora);
				$fechaEvo 				= $objUtil->fechaInvertida($fecha);
				$horaEvo 				= substr($hora, 0, -3);

				$html.= '
			<tr> 
				<td width="100%" ><small style="" class="negrita">'.nl2br($rsMovimientoEVOxEspe2_valor['titulo']).' ('.$fechaEvo.' a las '.$horaEvo.' realizado por '.$objRegistroClinico->SelectUsuario($objCon,$rsMovimientoEVOxEspe2_valor['usuario']).')</small></td>
			</tr>
			<tr> 
				<td width="100%" style="text-align: justify;"><small style="" >'.nl2br(htmlspecialchars($rsMovimientoEVOxEspe2_valor['evolucion'])).'</small></td>
			</tr>';

			}
		$html .= '
		</table>';

		$html .= '
		<hr>
		<table nobr="true"  cellspacing="4" border="0" width="100%">
			<tr>
				<td><strong  style="" >Procesos</strong></td>
			</tr>
			';
			foreach ($rsBitacora as $listadoBITACORA_clave => $listadoBITACORA_valor) {
				if( $listadoBITACORA_valor['BITtipo_codigo'] == 5 || $listadoBITACORA_valor['BITtipo_codigo'] == 28 || $listadoBITACORA_valor['BITtipo_codigo'] == 3 || $listadoBITACORA_valor['BITtipo_codigo'] == 4 || $listadoBITACORA_valor['BITtipo_codigo'] == 6 || $listadoBITACORA_valor['BITtipo_codigo'] == 7){
					$fechaHora 				= $listadoBITACORA_valor['BITdatetime'];
					list($fecha, $hora) 	= explode(' ', $fechaHora);
					$fechaBit 				= $objUtil->fechaInvertida($fecha);
					$horaBit 				= substr($hora, 0, -3);

				$html.= '
			<tr> 
				<td width="100%" style="text-align: justify;"><small style=""  class="">'.nl2br($listadoBITACORA_valor['BITdescripcion']).' <br><b>'.$fechaBit.'</b> a las <b>'.$horaBit.'</b> realizado por <b>'.$objRegistroClinico->SelectUsuario($objCon,$listadoBITACORA_valor['BITusuario']).'</b></small></td>
			</tr>
			';
				}
			}
		$html .= '
		</table>';

		$html .= '
		<hr>
		<table  cellspacing="4" border="0" width="100%">
			<tr>
				<td><strong  style="" >Hipótesis Diagnósticas</strong></td>
			</tr>
			<br>
			<tr> 
				<td width="100%"><small style="" class="negrita">Hipótesis Final</small></td>
			</tr>
			<tr> 
				<td width="100%"><small style="" >'.$rsRce[0]['regHipotesisFinal'].'</small></td>
			</tr>
			<tr>
				<td width="100%" style="text-align: justify;" ><small style="" >'.nl2br(htmlspecialchars($datosHojaHospitalizacion[0]['hipotesisDiagnosticas'])).'</small></td>
			</tr>
		</table>';

		

		$datosHojaHospitalizacion[0]['indicaciones'] = str_replace("<", "&#60;", $datosHojaHospitalizacion[0]['indicaciones']);
		$datosHojaHospitalizacion[0]['indicaciones'] = str_replace("&#60;br>", "<br>", $datosHojaHospitalizacion[0]['indicaciones']);

		$html .= '
		<hr>
		<table  nobr="true" cellspacing="4" border="0" width="100%">
			<tr>
				<td><strong  style="" >Hospitalización</strong></td>
			</tr>
			<br>
			<tr> 
				<td width="20%"><small style="" class="negrita">HOSPITALIZAR EN EL SERVICIO</small></td>
				<td width="80%"><small style="" >'.$datosPaciente[0]['hospitalizarEnServicio'].'.</small></td>
			</tr>
			<tr> 
				<td width="20%"><small style="" class="negrita">INDICACIONES</small></td>
				<td width="80%" style="text-align: justify;" ><small style="" >'.nl2br(htmlspecialchars($datosHojaHospitalizacion[0]['indicaciones'])).'.</small></td>
			</tr>
		</table>';

		$rsFirma 	= $objRegistroClinico->SelectUsuarioAll($objCon,$rsAltaUrgencia[0]['SAUusuario']);
		$rut 		=  $objUtil->setRun_addDV($rsFirma[0]['rutusuario']);

		// if ( ! is_null($datosPaciente[0]['rut']) && ! empty($datosPaciente[0]['rut']) ) {

			// $rut = $objUtil->setRun_addDV($datosPaciente[0]['rut']);

		// }
		$firma = FirmaPDF.'/firmaDigital/medicos/'.$rsFirma[0]['rutusuario'].'.png';

		$html .= '&nbsp;<br> 
		<table  nobr="true" cellspacing="0" border="0" width="100%">
			<tr>
				<td align="center" ><img   src="'.$firma.'" style="width:150px; height:35px;"></td>
			</tr>
			<tr>
				<td  align="center" >'.$rsFirma[0]['nombreusuario'].'</td>
			</tr>
			<tr>
				<td  align="center" >'.$rut.'</td>
			</tr>
		</table>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// array with names of columns
$arr_nomes = array(
    array("", 20, 53) // array(name, new X, new Y);
);

// num of pages
$ttPages = $pdf->getNumPages();
for($i=1; $i<=$ttPages; $i++) {
    // set page
    $pdf->setPage($i);
    // all columns of current page
    foreach( $arr_nomes as $num => $arrCols ) {
        $x = $pdf->xywalter[$num][0] + $arrCols[1]; // new X
        $y = $pdf->xywalter[$num][1] + $arrCols[2]; // new Y
        $n = $arrCols[0]; // column name
        // transforme Rotate
        $pdf->StartTransform();
        // Rotate 90 degrees counter-clockwise
        $pdf->Rotate(90, $x, $y);
        $pdf->Text($x, $y, $n);
        // Stop Transformation
        $pdf->StopTransform();
    }
}

// reset pointer to the last page
$pdf->lastPage();
$nombre_archivo = 'hojaHospitalizacion.pdf';
$pdf->Output(__DIR__ . '/' . $nombre_archivo, 'FI');
$url = "/RCEDAU/views/modules/rce/rce/hojaHospitalizacion.pdf";
?>

</iframe>
<div class="embed-responsive embed-responsive-16by9">
	<iframe id="pdfHojaHospitalizacion" class="embed-responsive-item" src="<?=$url?>" height="700" width="700" allowfullscreen></iframe>
</div>

<script>
$('#pdfHojaHospitalizacion').ready(function(){
	ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','nombreArchivo=<?=$url?>&accion=eliminarPDF', 'POST', 'JSON', 1, '', true);
});
</script>
