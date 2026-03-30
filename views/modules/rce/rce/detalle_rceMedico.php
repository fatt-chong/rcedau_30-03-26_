<?php
header('Access-Control-Allow-Origin: *');
session_start();
ini_set('memory_limit', '1000M');
error_reporting(0);

// Include the main TCPDF library (search for installation path).
require_once('../../../../../estandar/TCPDF-main/tcpdf.php');
require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php'); 		$objCon      			= new Connection; $objCon->db_connect();
require_once('../../../../class/Util.class.php');       		$objUtil     			= new Util;
require_once('../../../../class/Admision.class.php');   		$objAdmision 			= new Admision;
require_once('../../../../class/RegistroClinico.class.php'); 	$objRegistroClinico		= new RegistroClinico;
require_once('../../../../class/Pronostico.class.php'); 		$objPronostico  		= new Pronostico;
require_once("../../../../class/Dau.class.php" );  			$objDetalleDau       	= new Dau;
require_once("../../../../class/Rce.class.php" );  			$objRce       			= new Rce;
require_once("../../../../class/Servicios.class.php"); 		$objServicio     		= new Servicios;
require_once("../../../../class/Agenda.class.php" );  			$objAgenda        		= new Agenda;
require_once("../../../../class/Usuarios.class.php" );  		$objUsuarios        		= new Usuarios;
require_once('../../../../class/Formulario.class.php'); 		$objFormulario 			= new Formulario;
require_once('../../../../class/Especialista.class.php'); 		$objEspecialista		= new Especialista;
require_once('../../../../class/LPP.class.php'); 				$objLPP					= new LPP();
require_once('../../../../class/HospitalAmigo.class.php');      $objHospitalAmigo       = new HospitalAmigo;

class PDF_Rotate extends TCPDF
{
var $angle=0;

function Rotate($angle,$x=-1,$y=-1)
{
	if($x==-1)
		$x=$this->x;
	if($y==-1)
		$y=$this->y;
	if($this->angle!=0)
		$this->_out('Q');
	$this->angle=$angle;
	if($angle!=0)
	{
		$angle*=M_PI/180;
		$c=cos($angle);
		$s=sin($angle);
		$cx=$x*$this->k;
		$cy=($this->h-$y)*$this->k;
		$this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
	}
}

function _endpage()
{
	if($this->angle!=0)
	{
		$this->angle=0;
		$this->_out('Q');
	}
	parent::_endpage();
}
}

class MYPDF extends PDF_Rotate
{
function Header()
{
	//Put the watermark
	$nombre = $_SESSION['MM_Username'.SessionName];
	$rut = $_SESSION['MM_RUNUSU'.SessionName];
	if ( isset($_SESSION['usuarioActivo']) ) {
		$nombre = $_SESSION['usuarioActivo']['usuario'];
		$rut = $_SESSION['usuarioActivo']['rut'];
	}
	$usuarioNuevo = strtoupper (substr($nombre, 0, 3)."".substr($rut,-3));
	$this->SetFont('helvetica','B',40);
	$this->SetTextColor(230, 240, 252);

	$this->RotatedText(0,285,$usuarioNuevo,40);
	$this->RotatedText(60,285,$usuarioNuevo,40);
	$this->RotatedText(120,285,$usuarioNuevo,40);
	$this->RotatedText(180,285,$usuarioNuevo,40);

	$this->RotatedText(0,245,$usuarioNuevo,40);
	$this->RotatedText(60,245,$usuarioNuevo,40);
	$this->RotatedText(120,245,$usuarioNuevo,40);
	$this->RotatedText(180,245,$usuarioNuevo,40);

	$this->RotatedText(0,205,$usuarioNuevo,40);
	$this->RotatedText(60,205,$usuarioNuevo,40);
	$this->RotatedText(120,205,$usuarioNuevo,40);
	$this->RotatedText(180,205,$usuarioNuevo,40);

	$this->RotatedText(0,165,$usuarioNuevo,40);
	$this->RotatedText(60,165,$usuarioNuevo,40);
	$this->RotatedText(120,165,$usuarioNuevo,40);
	$this->RotatedText(180,165,$usuarioNuevo,40);

	$this->RotatedText(0,125,$usuarioNuevo,40);
	$this->RotatedText(60,125,$usuarioNuevo,40);
	$this->RotatedText(120,125,$usuarioNuevo,40);
	$this->RotatedText(180,125,$usuarioNuevo,40);

	$this->RotatedText(0,85,$usuarioNuevo,40);
	$this->RotatedText(60,85,$usuarioNuevo,40);
	$this->RotatedText(120,85,$usuarioNuevo,40);
	$this->RotatedText(180,85,$usuarioNuevo,40);

	$this->RotatedText(0,45,$usuarioNuevo,40);
	$this->RotatedText(60,45,$usuarioNuevo,40);
	$this->RotatedText(120,45,$usuarioNuevo,40);
	$this->RotatedText(180,45,$usuarioNuevo,40);

	$this->RotatedText(0,5,$usuarioNuevo,40);
	$this->RotatedText(60,5,$usuarioNuevo,40);
	$this->RotatedText(120,5,$usuarioNuevo,40);
	$this->RotatedText(180,5,$usuarioNuevo,40);
}

function RotatedText($x, $y, $txt, $angle)
{
	//Text rotated around its origin
	$this->Rotate($angle,$x,$y);
	$this->Text($x,$y,$txt);
	$this->Rotate(0);
}
}

//RECEPCION VARIABLE

$parametros           		= $objUtil->getFormulario($_GET);
$datos                		= $objAdmision->listarDatosDau($objCon,$parametros);
$datosRce 					= $objRegistroClinico->consultaRCE($objCon,$parametros);
$listarSignos 		  		= $objRce->listarSignosVitales($objCon,$datos[0]['id_paciente'], $datosRce[0]['regId']);
$eventos 					= 1;
$listadoIndicaciones  		= $objRegistroClinico->listarIndicacionesRCE($objCon,$parametros, $eventos);
$listaServicios       		= $objRegistroClinico->listarServiciosIndicaciones($objCon);
$rsPronostico         		= $objPronostico->listarPronosticos($objCon);
$rsIndEgreso 		  		= $objDetalleDau->getIndEgreso($objCon);
$datosDau             		= $objDetalleDau->ListarPacientesDau($objCon,$parametros);
$resRceDau			  		= $objRegistroClinico->consultaRCE($objCon,$parametros);
$listarAnt 			  		= $objRegistroClinico -> listarAnt($objCon,$datos[0]['id_paciente'], $datos[0]['dau_id']);
$obtenerIndicacionEgreso    = $objDetalleDau->obtenerIndicacionEgreso($objCon,$parametros);
$rsDerivacion         		= $objDetalleDau->getAltaDerivacion($objCon);
$ListarServiciosDau         = $objServicio->ListarServiciosDau($objCon);
$resEspecialidad            = $objAgenda->getEspecialidad($objCon);
$rsAPS                      = $objDetalleDau->getAPS($objCon);
$fecha_defuncion 			= date('d-m-Y H:i',strtotime($obtenerIndicacionEgreso[0]['dau_defuncion_fecha']));
$usuariosIndicaciones       = array();
$usuarioInicioAtencion      = '';
$registroViolencia 			= $objRce->obtenerRegistroViolenciaSegunRCE($objCon, $datosRce[0]['regId']);
$acompaniante 				= $objHospitalAmigo->obtenerAcompaniante($objCon, array("idDau" => $datos[0]['dau_id']));
$LPP 						= $objLPP->obtenerLPP($objCon, array("idDau" => $datos[0]['dau_id']));

if ( $datos[0]['dau_atencion'] == 3 ) {
	require_once('../../../../class/Evolucion.class.php');
	$objEvolucion	   = new Evolucion;
	$listarEvoluciones = $objEvolucion->obtenerDatosSolicitudEvolucionSegunRCE($objCon, $datosRce[0]['regId']);
}

$manifestaciones = '';
if ( $datos[0]['dau_manifestaciones'] == 'S' ) {

	$manifestaciones = ' - (Manifestaciones)';

}

$rut = $datos[0]['rut_extranjero'];

if ( ! is_null($datos[0]['rut']) && ! empty($datos[0]['rut']) ) {
	$rut = $objUtil->setRun_addDV($datos[0]['rut']);
}

$rsFechaHora    = $objUtil->getHorarioServidor($objCon);
$fechActual 	= $rsFechaHora[0]['fecha']." ".$rsFechaHora[0]['hora'];

$pdf = new MYPDF();

// add a page
$pdf->AddPage();

// set Rotate
// $params = $pdf->serializeTCPDFtagParameters(array(90));


//Funciones PHP
function existeFecha ( $fecha ) {

	if ( is_null($fecha) || empty($fecha) || '31-12-1969 20:00:00' == $fecha || '31-12-1969 21:00:00' == $fecha ) {

		return '--------------';
	}

	return $fecha;
}

function pacienteHospitalizadoEIsapre ( $paciente ) {

	return ( $paciente['dau_indicacion_egreso'] == 4 && $paciente['dau_paciente_prevision'] != 0 && $paciente['dau_paciente_prevision'] != 1 && $paciente['dau_paciente_prevision'] != 2 && $paciente['dau_paciente_prevision'] != 3 && $paciente['dau_paciente_prevision'] != 4 ) ? true : false;

}

// create some HTML content
$html= '
<head>
<style type="text/css">
	thead { display: table-header-group }
	tfoot { display: table-row-group }
	tr { page-break-inside: avoid }
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
	hr{
	   height:2px;
	   border:none;
	 }
	.backBlue{
		background-color:#CCC;
		}
	.ultrachico{
		font-family:"SourceSansPro-Regular", Arial, Helvetica;
		font-size:7pt;
		}
	.superchico{
		font-family:"SourceSansPro-Regular", Arial, Helvetica;
		font-size:9pt;
		}
	.chico{
		font-family:"SourceSansPro-Regular", Arial, Helvetica;
		font-size:12pt;
		}
	p {
		line-height: 1.2;
		}
	.titulo {
		font-family:"SourceSansPro-Bold", Arial, Helvetica;
		font-size:12pt;
		}
	.simple {
		font-family:"SourceSansPro-Bold", Arial, Helvetica;
		font-size:12pt;
		font-weight:bold;}

</style>
</head>
<table class="bordeCeldaGrande" cellspacing="2" border="0" width="100%">
	<tr>
        <td>
            <table width="100%">
                <tr>
                    <td width="15%"><img src="/estandar/img/logo_gobierno_chile.jpg" width="55" height="55"></td>
					<td width="85%">
						<p class="titulo" align="center">Datos de Atención de Urgencia DAU
						<br>';
						if( $datos[0]['est_id'] != 4 && $datos[0]['est_id'] != 5 && $datos[0]['est_id'] != 6 && $datos[0]['est_id'] != 7 ){
							$html .= '<strong>Folio: '.strtoupper($datos[0]['dau_id']).' (ABIERTO)</strong>';
							$fecha = 'Fecha y Hora (Actual): '.$fechActual;
						} else if ($datos[0]['est_id'] == 4 || $datos[0]['est_id'] == 5 )  {
							$html .= '<strong>Folio: '.strtoupper($datos[0]['dau_id']).' (CERRADO)</strong>';
							$fecha = 'Fecha y Hora (Cierre): '.date("d-m-Y H:i:s",strtotime($datosDau[0]['dau_indicacion_egreso_fecha']));
						} else if ($datos[0]['est_id'] == 6 )  {
							$html .= '<strong>Folio: '.strtoupper($datos[0]['dau_id']).' (ANULADO)</strong>';
							$fecha = 'Fecha y Hora (Cierre): '.date("d-m-Y H:i:s",strtotime($datosDau[0]['dau_cierre_fecha_final']));
						} else if ($datos[0]['est_id'] == 7 )  {
							$html .= '<strong>Folio: '.strtoupper($datos[0]['dau_id']).' (N.E.A.)</strong>';
							$fecha = 'Fecha y Hora (Cierre): '.date("d-m-Y H:i:s",strtotime($datosDau[0]['dau_cierre_fecha_final']));
						}
						$html .= '
						<br>
						<small>Cuenta Corriente: '.strtoupper($datos[0]['idctacte']).'</small>
						<br>
						<small>Fecha y Hora (Admisión): '.date("d-m-Y H:i:s", strtotime($datosDau[0]['dau_admision_fecha'])).'</small>
						<br>
						<small>'.$fecha.'</small></p>
					</td>
                </tr>
            </table>
        </td>

    </tr><tr>
        <td class="enoform">
        	<table class="enoformSin chico" cellspacing="0" border="0" width="100%">
            	<tr>
                	<td width="50%">
						<table class="enoformSin chico" cellspacing="0" border="0" width="100%">
							<tr>
								<td><strong>Datos del Paciente</strong></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Nombre Completo:</small></td>
                                <td width="65%"><small>'.strtoupper($datos[0]['nombres']).' '.strtoupper($datos[0]['apellidopat']).' '.strtoupper($datos[0]['apellidomat']).'</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Rut, Pasaporte u Otro:</small></td>
                                <td width="65%"><small>'.$rut.'</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Fecha de Nacimiento:</small></td>
                                <td width="65%"><small>'.strtoupper(date("d-m-Y",strtotime($datos[0]['fechanac']))).'</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Nacionalidad:</small></td>
								<td width="65%">';
									if($datos[0]['NACdescripcion'] == ""){
										$html .= '<small>'.strtoupper($datos[0]['nacionalidad']).'</small>';
									} else {
										$html .= '<small>'.strtoupper($datos[0]['NACdescripcion']).'</small>';
									}
								$html .= '
								</td>
							</tr>
							<tr>
                                <td width="35%" ><small>País de Nacimiento:</small></td>
								<td width="65%">';
									if($datos[0]['NACpais'] == ""){
										$html .= '<small>'.strtoupper('No Informada').'</small>';
									} else {
										$html .= '<small>'.strtoupper($datos[0]['NACpais']).'</small>';
									}
								$html .= '
								</td>
							</tr>

							<tr>
                                <td width="35%" ><small>Región:</small></td>
								<td width="65%">';
									if($datos[0]['REG_Descripcion'] == ""){
										$html .= '<small>'.strtoupper('No Informada').'</small>';
									} else {
										$html .= '<small>'.strtoupper($datos[0]['REG_Descripcion']).'</small>';
									}
								$html .= '
								</td>
							</tr>
							<tr>
                                <td width="35%" ><small>Ciudad:</small></td>
								<td width="65%">';
									if($datos[0]['CIU_Descripcion'] == ""){
										$html .= '<small>'.strtoupper('No Informada').'</small>';
									} else {
										$html .= '<small>'.strtoupper($datos[0]['CIU_Descripcion']).'</small>';
									}
								$html .= '
								</td>
							</tr>
							<tr>
                                <td width="35%" ><small>Comuna:</small></td>
								<td width="65%">';
									if($datos[0]['comuna'] == ""){
										$html .= '<small>'.strtoupper('No Informada').'</small>';
									} else {
										$html .= '<small>'.strtoupper($datos[0]['comuna']).'</small>';
									}
								$html .= '
								</td>
							</tr>
							<tr>
                                <td width="35%" ><small>Dirección:</small></td>
								<td width="65%"><small>'.strtoupper($datos[0]['direccion']).'</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Sector:</small></td>
								<td width="65%">';
									if($datos[0]['dau_paciente_domicilio_tipo'] == "R"){
										$html .= '<small>'.strtoupper('Rural').'</small>';
									} else if ($datos[0]['dau_paciente_domicilio_tipo'] == "U"){
										$html .= '<small>'.strtoupper('Urbano').'</small>';
									}
								$html .= '
								</td>
							</tr>
							<tr>
                                <td width="35%" ><small>Religión:</small></td>
                                <td width="65%"><small>'.(isset($datos[0]['religion_descripcion']) ? $datos[0]['religion_descripcion'] : '-').'</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Teléfonos:</small></td>
								<td width="65%">';
								if($datos[0]['PACfono']==0){
									$html .= "<small>FIJO NO DEFINIDO</small>";
								} else {
									$html .= "<small>".$datos[0]['PACfono']."</small>";
								}

								$html .= ', ';

								if($datos[0]['fono1']==0){
									$html .= " <small>CELULAR NO DEFINIDO</small>";
								} else {
									$html .= "<small>".$datos[0]['fono1']."</small>";
								}
								$html .= '
								</td>
							</tr>
							<tr>
                                <td width="35%" ><small>Consultorio:</small></td>
                                <td width="65%"><small>'.strtoupper($datos[0]['con_descripcion']).'</small></td>
							</tr>
                        </table>
					</td>
					<td width="50%">
							<table class="enoformSin chico" cellspacing="0" border="0" width="100%">
							<tr>
								<td>&nbsp;</td>
							</tr>
							<tr>
                                <td width="35%" ><small>Lugar de Accidente:</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>N. Acompañante:</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Motivo de consulta:</small></td>
                                <td width="65%"><small>'.strtoupper($datos[0]['mot_descripcion']).' - '.strtoupper($datos[0]['dau_motivo_descripcion']).''.$manifestaciones.'</small></td>
							</tr>';
								if($datos[0]['mor_descripcion']!=''){
									$html .= '<tr>';
									$html .= "<td><small><strong>MORDEDURA:</strong></small></td>";
									$html .= "<td><small><strong>".$datos[0]['mor_descripcion']."</strong></small></td>";
									$html .= '</tr>';
								}
								if($datos[0]['int_descripcion']!=''){
									$html .= '<tr>';
									$html .= "<td><small><strong>INTOXICACIÓN:</strong></small></td>";
									$html .= "<td><small><strong>".$datos[0]['int_descripcion']."</strong></small></td>";
									$html .= '</tr>';
								}
								if($datos[0]['que_descripcion']!=''){
									$html .= '<tr>';
									$html .= "<td><small><strong>QUEMADO:</strong></small></td>";
									$html .= "<td><small><strong>".$datos[0]['que_descripcion']."</strong></small></td>";
									$html .= '</tr>';
								}
							$html .= '
							<tr>
                                <td width="35%" ><small>Edad:</small></td>
                                <td width="65%"><small>'.$datos[0]['dau_paciente_edad'].'</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Etnia:</small></td>
                                <td width="65%"><small>'.strtoupper($datos[0]['etn_descripcion']).'</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Afrodescendiente:</small></td>
								<td width="65%"><small>';
								if($datos[0]['PACafro'] == 0){
									$html .= 'No';
								} else {
									$html .= 'Si';
								}
								$html .= '
									</small>
								</td>
							</tr>
							<tr>
                                <td width="35%" ><small>Sexo:</small></td>
								<td width="65%">';
								if($datos[0]['sexo']=='M'){
									$html .= "<small>Masculino</small>";
								} else if ($datos[0]['sexo']=='F') {
									$html .= '<small>Femenino</small>';
								}
								$html .= '
								</td>
							</tr>
							<tr>
                                <td width="35%" ><small>Medio de Transporte:</small></td>
                                <td width="65%"><small>'.strtoupper($datos[0]['med_descripcion']).'</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Tipo de Atención:</small></td>
                                <td width="65%"><small>'.strtoupper($datos[0]['ate_descripcion']).'</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Previsión:</small></td>
                                <td width="65%"><small>'.strtoupper($datos[0]['prevision']).'</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Forma de Pago:</small></td>
                                <td width="65%"><small>'.strtoupper($datos[0]['instNombre']).'</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Categorización:</small></td>
                                <td width="65%">';
									if ($datosDau[0]['dau_categorizacion'] == "ESI-1") {
										$cate = "C1";
									}elseif ($datosDau[0]['dau_categorizacion'] == "ESI-2") {
										$cate = "C2";
									}elseif ($datosDau[0]['dau_categorizacion'] == "ESI-3") {
										$cate = "C3";
									}elseif ($datosDau[0]['dau_categorizacion'] == "ESI-4") {
										$cate = "C4";
									}elseif ($datosDau[0]['dau_categorizacion'] == "ESI-5") {
										$cate = "C5";
									}
									else{
										$cate = $datosDau[0]['dau_categorizacion'];
									}

									if ($datosDau[0]['dau_categorizacion_fecha'] == "") {
										$fechaCategorizacion = '------';
									} else {
										$fechaCategorizacion = date("d-m-Y",strtotime($datosDau[0]['dau_categorizacion_fecha']));
									}

									if ($datosDau[0]['dau_categorizacion_fecha'] == "") {
										$horaCategorizacion = '------';
									} else {
										$horaCategorizacion = date("H:m:i",strtotime($datosDau[0]['dau_categorizacion_fecha']));
									}


								$html .= '<small>'.$cate.' ( '.$fechaCategorizacion.' '.$horaCategorizacion.') (Usuario: '.$datosDau[0]['usuarioCategoriza'].')</small>
								</td>
							</tr>
						</table>
					</td>
                </tr>
            </table>
        </td>
	</tr><tr>
        <td class="enoform">
        	<table class="enoformSin chico" cellspacing="0" border="0" width="100%">
				<tr>
					<td width="25%">
						<table class="enoformSin chico" cellspacing="2" border="0" width="100%">
							<tr>
								<td ><small>Alcoholemia:</small></td>
								<td>';
								if(is_null($datosDau[0]['dau_alcoholemia_fecha']) && empty($datosDau[0]['dau_alcoholemia_fecha'])){
									$html .=  "<small>No</small>";
								} else {
									$html .= "<small>Si</small>";
								}
								$html .= '</td>
							</tr>
						</table>
					</td>

					<td width="15%">
						<table class="enoformSin chico" cellspacing="2" border="0" width="100%">
							<tr>
                                <td><small>Auge:</small></td>
                                <td>';
									if($datosDau[0]['dau_cierre_auge'] == "S"){
										$html .= '<small>Si</small>';
									} else {
										$html .= '<small>No</small>';;
									}
								$html .= '
								</td>
							</tr>
						</table>
					</td>

					<td width="20%">
						<table class="enoformSin chico" cellspacing="2" border="0" width="100%">
							<tr>
								<td><small>Pertinente:</small></td>
								<td>';
								if($datosDau[0]['dau_cierre_pertinencia'] == 'N' || $datosDau[0]['dau_cierre_pertinencia'] == NULL || $datosDau[0]['dau_cierre_pertinencia'] == ''){
									$html .= "<small>No</small>";
								} else {
									$html .= "<small>Si</small>";
								}
								$html .= '
								</td>
							</tr>
						</table>
					</td>

					<td width="20%">
						<table class="enoformSin chico" cellspacing="2" border="0" width="100%">
							<tr>
								<td><small>Postinor:</small></td>
								<td>';
								if($datosDau[0]['dau_cierre_entrega_postinor'] == 'N' || $datosDau[0]['dau_cierre_entrega_postinor'] == NULL || $datosDau[0]['dau_cierre_entrega_postinor'] == '' ){
									$html .= "<small>No</small>";
								} else {
									$html .= "<small>Si</small>";
								}
								$html .= '
								</td>
							</tr>
						</table>
					</td>

					<td width="20%">
						<table class="enoformSin chico" cellspacing="2" border="0" width="100%">
							<tr>
								<td><small>Hepatitis B:</small></td>
								<td>';
								if($datosDau[0]['dau_cierre_hepatitisB'] == 'N' || $datosDau[0]['dau_cierre_hepatitisB'] == NULL || $datosDau[0]['dau_cierre_hepatitisB'] == '' ){
									$html .= "<small>No</small>";
								} else {
									$html .= "<small>Si</small>";
								}
								$html .= '
								</td>
							</tr>
						</table>
					</td>
				</tr>';

				if ( !is_null($datosDau[0]['dau_alcoholemia_fecha']) || !empty($datosDau[0]['dau_alcoholemia_fecha'])){

					$html .= '
					<tr>
						<td width="25%">
							<table class="enoformSin chico" cellspacing="2" border="0" width="100%">
								<tr>
									<td><small>Estado Etílico: '.$datosDau[0]['eti_descripcion'].'</small></td>
								</tr>
							</table>
						</td>

						<td width="25%">
							<table class="enoformSin chico" cellspacing="2" border="0" width="100%">
								<tr>
									<td><small>N° de Frasco: '.$datosDau[0]['dau_alcoholemia_numero_frasco'].'</small></td>
								</tr>
							</table>
						</td>

						<td width="50%">
							<table class="enoformSin chico" cellspacing="2" border="0" width="100%">
								<tr>';

								if ($datosDau[0]['dau_alcoholemia_fecha'] == "") {
									$html .= '
										<td><small>Fecha: ------ </small></td>';
								} else {
									$html .= '
										<td><small>Fecha: '.date("d-m-Y H:i:s",strtotime($datosDau[0]['dau_alcoholemia_fecha'])).'</small></td>';
								}

								$html.= '
								</tr>
							</table>
						</td>

					</tr>';
				}

				$html .= '

			</table>
		</td>
	</tr><tr>
        <td class="enoform">
			<table class="enoformSin chico" cellspacing="0" border="0" width="100%">
				<tr>
					<td width="100%">
						<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
							<tr>
								<td><strong>Signos Vitales</strong></td>
							</tr>';

							if ( is_null($listarSignos[0]['SVITALfecha']) || empty($listarSignos[0]['SVITALfecha']) ) {
								$html .= '	<tr>
												<td width="100%"><small></small></td>
											</tr>';
							} else {

								$html .= '

								<tr>
									<td width="10%" align="center"><small><strong>Usuario</strong></small></td>
									<td width="10%" align="center"><small><strong>Fecha</strong></small></td>
									<td width="10%" align="center"><small><strong>P. Arteria</strong>l</small></td>
									<td width="8%" align="center"><small><strong>Pulso</strong></small></td>
									<td width="7%" align="center"><small><strong>FR</strong></small></td>
									<td width="7%" align="center"><small><strong>SaO2</strong></small></td>
									<td width="8%" align="center"><small><strong>Glasgow</strong></small></td>
									<td width="5%" align="center"><small><strong>Tº</strong></small></td>
									<td width="5%" align="center"><small><strong>Eva</strong></small></td>
									<td width="7%" align="center"><small><strong>H. Test</strong></small></td>
									<td width="7%" align="center"><small><strong>L.C.F.</strong></small></td>
									<td width="10%" align="center"><small><strong>RBNE</strong></small></td>
								</tr>';

								for ($r=0; $r<count($listarSignos) ; $r++) {
									$html .= '<tr>';

									$html.= '<td align="center"><small>'.$listarSignos[$r]['nombreusuario'].'</small></td>';

									if (is_null($listarSignos[$r]['SVITALfecha']) || empty($listarSignos[$r]['SVITALfecha']) ) {
										$html .= '<td align="center"><small></small></td>';
									} else {
										$html .= '<td align="center"><small>'.date("d-m-Y H:i:s",strtotime($listarSignos[$r]['SVITALfecha'])).'</small></td>';
									}

									if ($listarSignos[$r]['SVITALsistolica'] == "" && $listarSignos[$r]['SVITALdiastolica'] == "") {
										$html .= '<td align="center"><small></small></td>';
									}else{
										$html .= '<td align="center"><small>'.$listarSignos[$r]['SVITALsistolica'].' - '.$listarSignos[$r]['SVITALdiastolica'].'</small></td>';
									}

									$html .= '
										<td align="center"><small>'.$listarSignos[$r]['SVITALpulso'].'</small></td>
										<td align="center"><small>'.$listarSignos[$r]['SVITALfr'].'</small></td>
										<td align="center"><small>'.$listarSignos[$r]['SVITALsaturacion'].'</small></td>
										<td align="center"><small>'.$listarSignos[$r]['SVITALglasgow'].'</small></td>
										<td align="center"><small>'.$listarSignos[$r]['SVITALtemperatura'].'</small></td>
										<td align="center"><small>'.$listarSignos[$r]['SVITALeva'].'</small></td>
										<td align="center"><small>'.$listarSignos[$r]['SVITALHemoglucoTest'].'</small></td>
										<td align="center"><small>'.$listarSignos[$r]['SVITALfeto'].'</small></td>
										<td align="center"><small>'.$listarSignos[$r]['SVITALrbne'].'</small></td>
									</tr>';
								}
							}
							$html .= '
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td class="enoform">
			<table class="enoformSin chico" cellspacing="0" border="0" width="100%">
				<tr>
					<td width="50%">
						<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
							<tr>
								<td><strong>Antecedentes Mórbidos</strong></td>
							</tr>';

							if ($listarAnt[0]['pac_ant_descripcion'] == "") {
								$html .= '	<tr>
												<td><small></small></td>
											</tr>';
							} else {

								for ($n=0; $n<count($listarAnt); $n++) {
									$html .= '<tr>
												<td width="35%" ><small>'.$listarAnt[$n]['antDescripcion'].':</small></td>
												<td width="65%"><small>'.$listarAnt[$n]['pac_ant_descripcion'].'</small></td>
											</tr>';
								}
							}
						$html .= '
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr><tr>
		<td class="enoform">
			<table class="enoformSin chico" cellspacing="0" border="0" width="100%">
				<tr>
					<td width="50%">';
						for($i=0;$i<count($listadoIndicaciones);$i++) {

							if (  $listadoIndicaciones[$i]['descripcion'] == 'Solicitud Inicio Atención') {

								$usuarioInicioAtencion = $listadoIndicaciones[$i]['nombreUsuario'];

							}
						}
						$html .= '<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
							<tr>';

								$html .= '
								<td><strong>Motivo Consulta <small>(Usuario: '.$usuarioInicioAtencion.')</small></strong></td>
							</tr>
							<tr>
                                <td width="100%"><small>'.htmlspecialchars($resRceDau[0]['regMotivoConsulta']).'</small></td>
							</tr>
						</table>
					</td>
					<td width="50%">
						<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
							<tr>
								<td><strong>Hipótesis Diagnóstica Inicial <small>(Usuario: '.$usuarioInicioAtencion.')</small></strong></td>
							</tr>
							<tr>';

							$html .= '
                                <td height="auto" width="100%"><small>'.htmlspecialchars($resRceDau[0]['regHipotesisInicial']).'</small></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td class="enoform">
			<table class="enoformSin chico" cellspacing="0" border="0" width="100%">
				<thead>
				<tr>
					<td width="100%">
						<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
							<thead>
							<tr>
								<td><strong>Indicaciones Médicas</strong></td>
							</tr>
							<tr>
								<th width="12%" align="center"><small><strong>Indicación</strong></small></th>
								<th width="20%" align="center"><small><strong>Prestación</strong></small></th>
								<th width="10%" align="center"><small><strong>Estado</strong></small></th>
								<th width="13%" align="center"><small><strong>Solicitado</strong></small></th>
								<th width="13%" align="center"><small><strong>Inicio Ind.</strong></small></th>
								<th width="13%" align="center"><small><strong>Toma Muestra</strong></small></th>
								<th width="13%" align="center"><small><strong>Aplicado</strong></small></th>
							</tr>
							</thead>
							<tbody>';
							for($i=0;$i<count($listadoIndicaciones);$i++) {

								if ( $listadoIndicaciones[$i]['estado'] == 6 || $listadoIndicaciones[$i]['estado'] == 8 ) {

									continue;

								}

								array_push($usuariosIndicaciones, $listadoIndicaciones[$i]['usuarioInserta']);
								$tipoSolicitud = explode("Solicitud ", $listadoIndicaciones[$i]['descripcion']);

								if( $datos[0]['dau_atencion'] == 3 && $tipoSolicitud[1]  == 'Evolución') {

									continue;

								}

								// $listadoIndicaciones[$i]['Prestacion'] = str_replace("<", "&#60;", $listadoIndicaciones[$i]['Prestacion']);
								// $listadoIndicaciones[$i]['Prestacion'] = str_replace("&#60;br>", "<br>", $listadoIndicaciones[$i]['Prestacion']);

								$html .= '<tr>';

									if ( $listadoIndicaciones[$i]['servicio']==4 ) {
										$html .= '<td align="center"><small>Solicitud Otros</small></td>';
									} else {
										$html .= '<td align="center"><small>'.$tipoSolicitud[1].'</small></td>';
									}

									$html .= '<td align="center"><small>'.htmlspecialchars($listadoIndicaciones[$i]['Prestacion']);

									if ( ! empty($listadoIndicaciones[$i]['descripcionClasificacion']) && ! is_null($listadoIndicaciones[$i]['descripcionClasificacion'])) {
										$html .= '<br>';
										$html .= ' ( '.$listadoIndicaciones[$i]['descripcionClasificacion'].' )';
									}
									$html .= '</small></td>';

									$html .= '
										<td align="center"><small>'.$listadoIndicaciones[$i]['estadoDescripcion'].'</small></td>
										<td align="center"><small>'.$listadoIndicaciones[$i]['usuarioInserta'].'<br>'.existeFecha(date("d-m-Y H:i:s",strtotime($listadoIndicaciones[$i]['fechaInserta']))).'</small></td>
										<td align="center"><small>'.$listadoIndicaciones[$i]['UsuarioIniciaIndicacion'].'<br>'.existeFecha(date("d-m-Y H:i:s",strtotime($listadoIndicaciones[$i]['fechaIniciaIndicacion']))).'</small></td>
										<td align="center"><small>'.$listadoIndicaciones[$i]['usuarioTomaMuestra'].'<br>'.existeFecha(date("d-m-Y H:i:s",strtotime($listadoIndicaciones[$i]['fechaTomaMuestra']))).'</small></td>
										<td align="center"><small>'.$listadoIndicaciones[$i]['usuarioAplica'].'<br>'.existeFecha(date("d-m-Y H:i:s",strtotime($listadoIndicaciones[$i]['fechaAplica']))).'</small></td>
								</tr>';
							}
								$html .= '</tbody>
						</table>
					</td>
				</tr>
				</thead>
			</table>
		</td>
	</tr>';
	if ( $datos[0]['dau_atencion'] == 3 && ! empty($listarEvoluciones) && ! is_null($listarEvoluciones) ) {

		$html .= '
				<tr>
					<td class="enoform">
						<table class="enoformSin chico" cellspacing="0" border="0" width="100%">
							<tr>
								<td><strong>Evoluciones</strong></td>
							</tr>
							<tbody>';

							foreach ( $listarEvoluciones as $evolucion ) {

								$html .= '
										<tr>
											<td><small> - '.$evolucion['SEVOevolucion'].'</small></td>
										</tr>
										';
							}

		$html .= '
						</tbody>
						</table>
					</td>
				</tr>
				';

	}

	$html .= '
	<tr>
		<td class="enoform">
			<table class="enoformSin chico" cellspacing="0" border="0" width="100%">
				<tr>
					<td width="50%">
						<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
							<tr>
								<td><strong>Alta Médica</strong></td>
							</tr>
							<tr>';
								$usuarioMedicoTratante = $datosDau[0]['dau_inicio_atencion_usuario'];
								$datosUsuario 		   = $objUsuarios->obtenerDatosUsuario ( $objCon, $usuarioMedicoTratante );
								$medicoTratante        = $datosUsuario[0]['PROdescripcion'];

								$usuarioMedicoEgresa   = $datosDau[0]['dau_indicacion_egreso_usuario'];
								$datosUsuario 		   = $objUsuarios->obtenerDatosUsuario ( $objCon, $usuarioMedicoEgresa );
								$medicoEgresa          = $datosUsuario[0]['PROdescripcion'];

								$html.='
								<td width="100%"><small>Profesional Tratante: '.$medicoTratante.'</small></td>
								<td width="100%"><small>Profesional Egresa: '.$medicoEgresa.'</small></td>
							</tr>
							<tr>
								<td width="100%"><small>Destino: ';
									for ($w=0; $w<count($rsIndEgreso) ; $w++) {
										if( $obtenerIndicacionEgreso[0]['dau_indicacion_egreso'] == $rsIndEgreso[$w]['ind_egr_id'] ) {
											$html .= $rsIndEgreso[$w]['ind_egr_descripcion'];
										}
									}

									$html .= ' - ';

									switch ($obtenerIndicacionEgreso[0]['dau_indicacion_egreso']) {
										case '3':
											for ($g=0; $g<count($rsDerivacion);$g++) {
												if($obtenerIndicacionEgreso[0]['alt_der_id'] == $rsDerivacion[$g]['alt_der_id']){
													$html .= $rsDerivacion[$g]['alt_der_descripcion'];
												}
											}
											switch ($obtenerIndicacionEgreso[0]['alt_der_id']) {
												case '2':
													$html .= ' - ';
													$descripcionesEspecialidad = '';
													for ($esp=0; $esp<count($resEspecialidad); $esp++) {
														if ( strpos($obtenerIndicacionEgreso[0]['dau_ind_especialidad'], $resEspecialidad[$esp]['ESPcodigo']) !== false ){
															if ( empty($descripcionesEspecialidad) || is_null($descripcionesEspecialidad) ) {
																$descripcionesEspecialidad = $resEspecialidad[$esp]['ESPdescripcion'];
																continue;
															}
															$descripcionesEspecialidad = $descripcionesEspecialidad.'-'.$resEspecialidad[$esp]['ESPdescripcion'];
														}
													}
													$html .= $descripcionesEspecialidad;
													break;
												case '3':
													$html .= ' - ';
													for ($ap=0; $ap<count($rsAPS); $ap++){
														if($obtenerIndicacionEgreso[0]['dau_ind_aps'] == $rsAPS[$ap]['ESTAcodigo']){
															$html .= $rsAPS[$ap]['ESTAdescripcion'];
														}
													}
													break;
												case '5':
													$html .= ' - ';
													$html .= $obtenerIndicacionEgreso[0]['dau_ind_otros'];
													break;
											}
											break;
										case '4':
											for ($op=0; $op<count($ListarServiciosDau); $op++) {
												if($obtenerIndicacionEgreso[0]['dau_ind_servicio'] == $ListarServiciosDau[$op]['id']){
													$html .= $ListarServiciosDau[$op]['servicio'];
												}
											}
											break;
										case '5':
											break;
										case '6':
											if($obtenerIndicacionEgreso[0]['des_id'] == 7){
												$servicio_Destino = 'Anatomía Patológicas';
											}else if($obtenerIndicacionEgreso[0]['des_id'] == 8){
												$servicio_Destino = 'Servicio Médico Legal';
											}
											$html .= $servicio_Destino.' ('.$fecha_defuncion.')';
											break;
										case '7':
											break;

									}

									$dauPostIndicacionEgreso = $objDetalleDau->dauPostIndicacionEgreso($objCon, $datos[0]['dau_id']);

									if ( ! empty($dauPostIndicacionEgreso) && ! is_null($dauPostIndicacionEgreso) ) {

										$html .= " (Post Indicación Egreso: ".$dauPostIndicacionEgreso['descripcionPostIndicacionEgreso'].")";

									}

								$html .= '
								</small></td>
							</tr>
							<tr>
								<td><small>Pronóstico Médico Legal Provisorio: ';
								for ($q=0; $q<count($rsPronostico) ; $q++) {
									if($resRceDau[0]['PRONcodigo'] == $rsPronostico[$q]['PRONcodigo']){
										$html .= $rsPronostico[$q]['PRONdescripcion'];
									}
								}
								$html .= '
								</small></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
        <td class="enoform">
        	<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
				<tr>
					<td width="20%">
						<small>Violencia: ';
								if(is_null($registroViolencia) || empty($registroViolencia)){
									$html .=  "No";
								} else {
									$html .= "Si";
								}
					$html .= '</small>
					</td>';

					if ( ! empty($registroViolencia['descripcionTipoViolencia']) && ! is_null($registroViolencia['descripcionTipoViolencia']) ) {

						$html .= '
							<td width="20%">
								<small>Tipo: '.$registroViolencia['descripcionTipoViolencia'].'</small>
							</td>
							';

					}

				$html .= '</tr>

				<tr>';



				if ( ! empty($registroViolencia['descripcionTipoAgresor']) && ! is_null($registroViolencia['descripcionTipoAgresor']) ) {

					$html .= '
							<td width="23%">
								<small>Agresor: '.$registroViolencia['descripcionTipoAgresor'].'</small>
							</td>
							';

				}

				if ( ! empty($registroViolencia['descripcionLesionVictima']) && ! is_null($registroViolencia['descripcionLesionVictima']) ) {

					$html .= '
							<td width="20%">
								<small>Lesión: '.$registroViolencia['descripcionLesionVictima'].'</small>
							</td>
							';

				}

				if ( ! empty($registroViolencia['descripcionSospechaPenetracion']) && ! is_null($registroViolencia['descripcionSospechaPenetracion']) ) {

					$html .= '
							<td width="20%">
								<small>Penetración: '.$registroViolencia['descripcionSospechaPenetracion'].'</small>
							</td>
							';

				}

				if ( ! empty($registroViolencia['descripcionProfilaxis']) && ! is_null($registroViolencia['descripcionProfilaxis']) ) {

					$html .= '
							<td width="17%">
								<small>Profilaxis: '.$registroViolencia['descripcionProfilaxis'].'</small>
							</td>
							';

				}

				if ( ! empty($registroViolencia['victimaEmbarazada']) && ! is_null($registroViolencia['victimaEmbarazada']) ) {

					$html .= '
							<td width="17%">
								<small>Embarazada: '.( ($registroViolencia['victimaEmbarazada'] == 'S') ? 'Si' : 'No' ).'</small>
							</td>
							';

				}

				if ( ! empty($registroViolencia['peritoSexual']) && ! is_null($registroViolencia['peritoSexual']) ) {

					$html .= '
							<td width="20%">
								<small>Perito Sexual: '.$registroViolencia['peritoSexual'].'</small>
							</td>
							';

				}

				$html .= '
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="enoform">
			<table class="enoformSin chico" cellspacing="0" border="0" width="100%">
				<tr>
					<td width="50%">
						<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
							<tr>
								<td><strong>Hipótesis Final <small>(Usuario: '.$resRceDau[0]['nombreUsuario'].')</small></strong></td>
							</tr>';
							if ( $resRceDau[0]['regHipotesisFinal'] != '') {
								$html .= '
									<tr>
										<td width="100%"><small>'.$resRceDau[0]['regHipotesisFinal'].'</small></td>
									</tr>';
							}

							if ($resRceDau[0]['regCIE10Abierto'] != '' ) {

								$resRceDau[0]['regCIE10Abierto'] = str_replace("<", "&#60;", $resRceDau[0]['regCIE10Abierto']);
								$resRceDau[0]['regCIE10Abierto'] = str_replace("&#60;br>", "<br>", $resRceDau[0]['regCIE10Abierto']);

								$html .= '
									<tr>
										<td width="100%"><small>'.$resRceDau[0]['regCIE10Abierto'].'</small></td>
									</tr>';
							}
							$html .= '
						</table>
					</td>
					<td width="50%">
						<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
							<tr>
								<td><strong>Indicaciones Médicas Alta Urgencia / Receta <small>(Usuario: '.$resRceDau[0]['nombreUsuario'].')</small></strong></td>
							</tr>
							<tr>';
								$resRceDau[0]['regIndicacionEgresoUrgencia'] = str_replace("<", "&#60;", $resRceDau[0]['regIndicacionEgresoUrgencia']);
								$resRceDau[0]['regIndicacionEgresoUrgencia'] = str_replace("&#60;br>", "<br>", $resRceDau[0]['regIndicacionEgresoUrgencia']);

								$html .= '
                                <td width="100%"><small>'.$resRceDau[0]['regIndicacionEgresoUrgencia'].'</small></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<br>
	<br>';
	if ($objUtil->existe($acompaniante)) {
		$entregaInformacion = ($acompaniante[0]["entregaInformacion"] === "S")
			? "Si"
			: "No";
		$motivo = $acompaniante[0]["motivo"];
		$nombreAcompaniante = $acompaniante[0]["nombreAcompaniante"];
		$fechaEntregaInformacionMedica = $objUtil->cambiarFormatoFecha($acompaniante[0]["fechaEntregaInformacionMedica"]);
		$horaEntregaInformacionMedica = $acompaniante[0]["horaEntregaInformacionMedica"];
		$fechaYHoraEntregaInformacionMedica = $fechaEntregaInformacionMedica." ".$horaEntregaInformacionMedica;
		$nombreMedico = $acompaniante[0]["nombreMedico"];

		$html .= '
			<tr nobr="true">
				<td class="enoform">
					<table class="enoformSin chico" cellspacing="0" border="0" width="100%" style="text-align:center;">
						<tr>
							<td width="100%" style="text-align:left;">
								<strong>Hospital Amigo</strong>
							</td>
						</tr>
						<tr>
							<td width="100%" style="text-align:left;">
								<small><strong>¿Entrega de Información Médica?:</strong> '.$entregaInformacion.'</small>
							</td>
						</tr>
						<tr>
							<td width="100%" style="text-align:left;">
								<small><strong>Motivo:</strong> '.$motivo.'</small>
							</td>
						</tr>
						<tr>
							<td width="100%" style="text-align:left;">
								<small><strong>Nombre Familiar o Acompañante:</strong> '.$nombreAcompaniante.'</small>
							</td>
						</tr>
						<tr>
							<td width="100%" style="text-align:left;">
								<small><strong>Fecha y Hora Entrega Información Médica:</strong> '.$fechaYHoraEntregaInformacionMedica.'</small>
							</td>
						</tr>
						<tr>
							<td width="100%" style="text-align:left;">
								<small><strong>Nombre Médico:</strong> '.$nombreMedico.'</small>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		';
	}

	if ($objUtil->existe($LPP)) {
		$valoracionesPies = str_replace(",", ", ", $LPP[0]["descripcionesValoracionPiel"]);
		$zonaAfectada = $LPP[0]["zonaAfectada"];
		$puntajeEvaluacion = $LPP[0]["puntajeEvaluacion"];
		$riesgo = $LPP[0]["descripcionRiesgo"];
		$aplicacionSEMP = $LPP[0]["descripcionAplicacionSEMP"];
		$cambioPosicion = $LPP[0]["descripcionCambioPosicion"];
		$registrosEjecucion = $LPP[0]["registrosEjecucion"];
		$usuarios = $LPP[0]["usuarios"];
		$fechas = $LPP[0]["fechas"];

		$html .= '
			<tr nobr="true">
				<td class="enoform">
					<table
						class="enoformSin chico"
						cellspacing="0"
						border="0"
						width="100%"
						style="text-align:center;">
						<tr>
							<td>
								<table
									class="enoformSin chico"
									cellspacing="0"
									border="0"
									width="100%"
									style="text-align:center;">
									<thead>
										<tr>
											<th style="text-align:left;">
												<strong>Evaluación del Riesgo y Prevención de Lesiones por Presión</strong>
											</th>
										</tr>
									</thead>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<table
									class="enoformSin chico"
									cellspacing="0"
									border="0"
									width="100%">
									<tbody>
										<tr style="text-align:left;">
											<td colspan="2">
												<small><strong>1. Valoración de la Piel</strong></small>
											</td>
										</tr>
										<tr style="text-align:left;">
											<td style="width:20%;">
												<small>&nbsp;&nbsp;&nbsp;&nbsp;Valoración de la Piel</small>
											</td>
											<td style="width:80%;">
												<small>: ' . $valoracionesPies . '</small>
											</td>
										</tr>
										<tr style="text-align:left;">
											<td style="width:20%;">
												<small>&nbsp;&nbsp;&nbsp;&nbsp;Zona Afectada</small>
											</td>
											<td style="width:80%;">
												<small>: ' . $zonaAfectada . '</small>
											</td>
										</tr>
										<tr style="text-align:left;">
											<td colspan="2">
												<small><strong>2. Evaluación de Riesgo LPP</strong></small>
											</td>
										</tr>
										<tr style="text-align:left;">
											<td style="width:20%;">
												<small>&nbsp;&nbsp;&nbsp;&nbsp;Puntaje de Evaluación</small>
											</td>
											<td style="width:80%;">
												<small>: ' . $puntajeEvaluacion . '</small>
											</td>
										</tr>
										<tr style="text-align:left;">
											<td style="width:20%;">
												<small>&nbsp;&nbsp;&nbsp;&nbsp;Riesgo LPP</small>
											</td>
											<td style="width:80%;">
												<small>: ' . $riesgo . '</small>
											</td>
										</tr>
										<tr style="text-align:left;">
											<td colspan="2">
												<small><strong>3. Aplicación Medidas Preventivas</strong></small>
											</td>
										</tr>
										<tr style="text-align:left;">
											<td style="width:20%;">
												<small>&nbsp;&nbsp;&nbsp;&nbsp;Aplicación de SEMP</small>
											</td>
											<td style="width:80%;">
												<small>: ' . $aplicacionSEMP . '</small>
											</td>
										</tr>
										<tr style="text-align:left;">
											<td style="width:20%;">
												<small>&nbsp;&nbsp;&nbsp;&nbsp;Cambio de Posición</small>
											</td>
											<td style="width:80%;">
												<small>: ' . $cambioPosicion . '</small>
											</td>
										</tr>
										<tr style="text-align:left;">
											<td colspan="2">
												<small><strong>4. Registro Ejecución Aplicación de Medidas</strong></small>
											</td>
										</tr>
										<tr>
											<td colspan="2">
												<table style="border:1px solid black;">
													<thead>
														<tr style="border:1px solid black;">
															<th style="border:1px solid black;">
																<small>Registro</small>
															</th>
															<th style="border:1px solid black;">
																<small>Usuario</small>
															</th>
															<th style="border:1px solid black;">
																<small>Fecha</small>
															</th>
														</tr>
													</thead>
													<tbody>';

												$arrayRegistros = explode(",", $registrosEjecucion);
												$arrayUsuarios = explode(",", $usuarios);
												$arrayFechas = explode(",", $fechas);

												for ($i = 0; $i < count($arrayRegistros); $i++) {
													$registro = $arrayRegistros[$i];
													$usuario = $arrayUsuarios[$i];
													$fecha = date("d-m-Y H:i:s", strtotime($arrayFechas[$i]));

													$html .= '
														<tr>
															<td style="border:1px solid black; text-align:left;">
																<small>' . $registro . '</small>
															</td>
															<td style="border:1px solid black;">
																<small>' . $usuario . '</small>
															</td>
															<td style="border:1px solid black;">
																<small>' . $fecha . '</small>
															</td>
														</tr>
													';
												}
											$html .= '
													</tbody>
												</table>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		';
	}
	if ( pacienteHospitalizadoEIsapre($datos[0]) ) {

		if( $datos[0]['est_id'] != 4 && $datos[0]['est_id'] != 5 && $datos[0]['est_id'] != 6 && $datos[0]['est_id'] != 7 ){
			$fechaMensaje = date('d-m-Y');
			$horaMensaje = date('H:i:s');
		} else if ($datos[0]['est_id'] == 4 || $datos[0]['est_id'] == 5 )  {
			$fechaMensaje = date("d-m-Y",strtotime($datosDau[0]['dau_indicacion_egreso_fecha']));
			$horaMensaje = date("H:i:s",strtotime($datosDau[0]['dau_indicacion_egreso_fecha']));
		} else if ($datos[0]['est_id'] == 6 )  {
			$fechaMensaje = date("d-m-Y",strtotime($datosDau[0]['dau_cierre_fecha_final']));
			$horaMensaje = date("H:i:s",strtotime($datosDau[0]['dau_cierre_fecha_final']));
		} else if ($datos[0]['est_id'] == 7 )  {
			$fechaMensaje = date("d-m-Y",strtotime($datosDau[0]['dau_cierre_fecha_final']));
			$horaMensaje = date("H:i:s",strtotime($datosDau[0]['dau_cierre_fecha_final']));
		}

	$html .= '
	<tr>
		<td class="enoform">
			<table class="enoformSin chico" cellspacing="0" border="0" width="100%" style="text-align:center;">
				<tr>
					<td width="100%">
						<strong><small>Siendo las '.$horaMensaje.' horas del dia '.$fechaMensaje.', el Medico Cirujano que suscribe, declara que, presenta una patología que le condiciona riesgo vital y/o riesgo de secuela funcional grave de no mediar tratamieto inmediato y, por lo tanto, se encuentra en la condición definida como Emergencia o Urgencia en ley 19.650 y por Decreto Supremo N° 896 del Ministerio de Salud</small></strong>
					</td>
				</tr>
			</table>
		</td>
	</tr>';
	}
	$html.=
	'<br>
	<br>
	<tr>
		<td>
			<table class="enoformSin chico" cellspacing="0" border="0" width="100%" style="text-align:right;">
				<tr>';
	$arrayUsuariosIndicaciones = array_values(array_unique($usuariosIndicaciones));

	for ( $i = 0; $i < count($arrayUsuariosIndicaciones); $i++) {

		$html .= '<td>';

		$usuarioIndicacion = $arrayUsuariosIndicaciones[$i];

		$resultadoUsuarioIndicaciones = $objUsuarios->obtenerDatosUsuario($objCon, $usuarioIndicacion);

		$URLUsuarioIndicaciones = "http://".IP."/firmaDigital/medicos/".$resultadoUsuarioIndicaciones[0]['PROcodigo'].".png";

		$file_headers_usuarioIndicaciones = @get_headers($URLUsuarioIndicaciones, 1);

		if($file_headers_usuarioIndicaciones[0] == 'HTTP/1.1 200 OK') {

			$html .= '
				<table style="margin-top:50px;">
					<tr style="text-align:center;">
						<td>
							<img id="'.$parametros['dau_id'].'" class="indicaciones" name="'.$parametros['dau_id'].'" src="http://'.IP.'/firmaDigital/medicos/'.$resultadoUsuarioIndicaciones[0]['PROcodigo'].'.png" style="width:150px; height:35px;">
						</td>
					</tr>
					<tr style="text-align:center;">
						<td>
							<small><strong>'.$resultadoUsuarioIndicaciones[0]['PROdescripcion'].'</strong></small>
						</td>
					</tr>
					<tr style="text-align:center;">
						<td>
							<small><strong>'.$objUtil->formatearNumero($resultadoUsuarioIndicaciones[0]['PROcodigo']).'-'.$objUtil->generaDigito($resultadoUsuarioIndicaciones[0]['PROcodigo']).'</strong></small>
						</td>
					</tr>
				</table>';

		} else if ( ! empty($resultadoUsuarioIndicaciones[0]['PROcodigo']) && ! is_null($resultadoUsuarioIndicaciones[0]['PROcodigo']) ) {
			$html .= '
				<br>
				<table style="margin-top:50px;">
					<tr style="text-align:center;">
						<td style="height:40px;"> </td>
					</tr>
					<tr style="text-align:center;">
						<td>
							<small><strong>'.$resultadoUsuarioIndicaciones[0]['PROdescripcion'].'</strong></small>
						</td>
					</tr>
					<tr style="text-align:center;">
						<td>
							<small><strong>'.$objUtil->formatearNumero($resultadoUsuarioIndicaciones[0]['PROcodigo']).'-'.$objUtil->generaDigito($resultadoUsuarioIndicaciones[0]['PROcodigo']).'</strong></small>
						</td>
					</tr>
				</table>';
		}

		$html .= '</td>
				';

	}
	$html .= '
				</tr>
			</table>
		</td>
	</tr>
</table>
		
';
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
$nombre_archivo = 'Informe_RCE_'.$parametros['dau_id'].'_'.$datos[0]['id_paciente'].'_'.date("d-m-Y",strtotime($datosDau[0]['dau_admision_fecha'])).'_'.date("H-i-s",strtotime($datosDau[0]['dau_admision_fecha'])).'.pdf';
$pdf->Output(__DIR__ . '/' . $nombre_archivo, 'FI');
unlink($nombre_archivo);
// ---------------------------------------------------------




//============================================================+
// END OF FILE
//============================================================+
?>
