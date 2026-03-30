<?php
header('Access-Control-Allow-Origin: *');
session_start();
ini_set('memory_limit', '1000M');
// error_reporting(0);
// 
// Include the main TCPDF library (search for installation path).
require_once('../../../../../estandar/TCPDF-main/tcpdf.php');
// require_once('../../../../../estandar/tcpdf/config/lang/spa.php');
// require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php'); 		$objCon      			= new Connection; $objCon->db_connect();
require_once('../../../../class/Util.class.php');       		$objUtil     			= new Util;
require_once('../../../../class/Admision.class.php');   		$objAdmision 			= new Admision;
require_once('../../../../class/RegistroClinico.class.php'); 	$objRegistroClinico		= new RegistroClinico;
// require_once('../../../../class/Pronostico.class.php'); 		$objPronostico  		= new Pronostico;
require_once("../../../../class/Dau.class.php" );  			$objDetalleDau       	= new Dau;
require_once("../../../../class/Rce.class.php" );  			$objRce       			= new Rce;
require_once("../../../../class/Servicios.class.php"); 		$objServicio     		= new Servicios;
require_once("../../../../class/Agenda.class.php" );  			$objAgenda        		= new Agenda;
require_once("../../../../class/Usuarios.class.php" );  		$objUsuarios        		= new Usuarios;
require_once('../../../../class/Formulario.class.php'); 		$objFormulario 			= new Formulario;
require_once('../../../../class/Especialista.class.php'); 		$objEspecialista		= new Especialista;

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
	$this->SetFont('helvetica','B',10);
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
	if ($_SESSION['MM_Username'.SessionName]!='ldiaz') {
	$this->Rotate($angle,$x,$y);
	$this->Text($x,$y,$txt);
	$this->Rotate(0);
	}

}
}

//RECEPCION VARIABLE

$parametros           		= $objUtil->getFormulario($_GET);
$datos                		= $objAdmision->listarDatosDau($objCon,$parametros);
$datosRce 					= $objRegistroClinico->consultaRCE($objCon,$parametros);
$rsMovimientoEVOxEspe		= $objRegistroClinico->MovimientoEVOxEspe2($objCon,$parametros);
$listarSignos 		  		= $objRce->listarSignosVitales($objCon,$datos[0]['id_paciente'], $datosRce[0]['regId']);
$eventos 					= 1;
$listadoIndicaciones  		= $objRegistroClinico->listarIndicacionesRCE($objCon,$parametros, $eventos);
$rsPronostico         		= $objRce->listarPronosticos($objCon);
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
$banderaLlamada             = $parametros['banderaLlamada'];
$registroViolencia 			= $objRce->obtenerRegistroViolenciaSegunRCE($objCon, $datosRce[0]['regId']);
$usuariosIndicaciones       = array();
$listarObsEspecialista 		= $objEspecialista->obtenerDatosSolicitudEspecialistaSegunRCE($objCon, $datosRce[0]['regId']);
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
$tratamientosArr 	= "";
$imagenologiaArr 	= "";
$laboratorioArr 	= "";
$procedimientoArr 	= "";
$otrosArr 			= "";
$indicacionesCont   = 0;
for ($i = 0; $i < count($listadoIndicaciones); $i++) {
	if($listadoIndicaciones[$i]['descripcion'] == 'Solicitud Tratamiento'){
		if($tratamientosArr != ""){
			$tratamientosArr .=", ";
		}
		$tratamientosArr .= $listadoIndicaciones[$i]['Prestacion'];
		if ( ! empty($listadoIndicaciones[$i]['descripcionClasificacion']) && ! is_null($listadoIndicaciones[$i]['descripcionClasificacion']) ) {
			$tratamientosArr .= "(".$listadoIndicaciones[$i]['descripcionClasificacion'].")";
			$indicacionesCont ++;
		}
	}
	if($listadoIndicaciones[$i]['descripcion'] == 'Solicitud Imagenologia'){
		if($imagenologiaArr != ""){
			$imagenologiaArr .=", ";
		}
		$imagenologiaArr .= $listadoIndicaciones[$i]['Prestacion'];
		if ( ! empty($listadoIndicaciones[$i]['descripcionClasificacion']) && ! is_null($listadoIndicaciones[$i]['descripcionClasificacion']) ) {
			$imagenologiaArr .= "(".$listadoIndicaciones[$i]['descripcionClasificacion'].")";
			$indicacionesCont ++;
		}
	}
	if($listadoIndicaciones[$i]['descripcion'] == 'Solicitud Laboratorio'){
		if($laboratorioArr != ""){
			$laboratorioArr .=", ";
		}
		$laboratorioArr .= $listadoIndicaciones[$i]['Prestacion'];
		if ( ! empty($listadoIndicaciones[$i]['descripcionClasificacion']) && ! is_null($listadoIndicaciones[$i]['descripcionClasificacion']) ) {
			$laboratorioArr .= "(".$listadoIndicaciones[$i]['descripcionClasificacion'].")";
			$indicacionesCont ++;
		}
	}
	if($listadoIndicaciones[$i]['descripcion'] == 'Solicitud Procedimiento'){
		if($procedimientoArr != ""){
			$procedimientoArr .=", ";
		}
		$procedimientoArr .= $listadoIndicaciones[$i]['Prestacion'];
		if ( ! empty($listadoIndicaciones[$i]['descripcionClasificacion']) && ! is_null($listadoIndicaciones[$i]['descripcionClasificacion']) ) {
			$procedimientoArr .= "(".$listadoIndicaciones[$i]['descripcionClasificacion'].")";
			$indicacionesCont ++;
		}
	}
	if($listadoIndicaciones[$i]['descripcion'] == 'Solicitud Otros'){
		if($otrosArr != ""){
			$otrosArr .=", ";
		}
		$otrosArr .= $listadoIndicaciones[$i]['Prestacion'];
		if ( ! empty($listadoIndicaciones[$i]['descripcionClasificacion']) && ! is_null($listadoIndicaciones[$i]['descripcionClasificacion']) ) {
			$otrosArr .= "(".$listadoIndicaciones[$i]['descripcionClasificacion'].")";
			$indicacionesCont ++;
		}
	}
}
$pdf = new MYPDF();

// add a page
$pdf->setCellPaddings(1, 1, 1, 1);
$pdf->SetFont('helvetica','',8);
$pdf->AddPage();

// set Rotate
// $params = $pdf->serializeTCPDFtagParameters(array(90));


//Funciones PHP
function pacienteHospitalizadoEIsapre ( $paciente ) {

	return ( $paciente['dau_indicacion_egreso'] == 4 && $paciente['dau_paciente_prevision'] != 0 && $paciente['dau_paciente_prevision'] != 1 && $paciente['dau_paciente_prevision'] != 2 && $paciente['dau_paciente_prevision'] != 3 && $paciente['dau_paciente_prevision'] != 4 ) ? true : false;

}

// create some HTML content
$html= '
<head>
<style type="text/css">
	.divAncho{
		width:1;
		}
	.enoform{
		border: 0.2px solid black;
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


<table class="bordeCeldaGrande" cellspacing="0" border="0" width="100%">
	<tr nobr="true">
        <td>
            <table width="100%">
                <tr>
                    <td width="15%"><img src="/estandar/img/logo_gobierno_chile.jpg" width="55" height="55"></td>
					<td width="85%">
						<p class="titulo" align="center">Datos de Atención de Urgencia DAU
						<br>';
						if( $datos[0]['est_id'] != 4 && $datos[0]['est_id'] != 5 && $datos[0]['est_id'] != 6 && $datos[0]['est_id'] != 7 ){
							$html .= '<strong>Folio: '.strtoupper($datos[0]['dau_id']).' (ABIERTO)</strong>';
							$fecha = 'Fecha y Hora (Actual): '.date('d-m-Y H:i:s');
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

    </tr>
    <tr nobr="true">
        <td class="enoform">
        	<table class="enoformSin " cellspacing="0" border="0" width="100%">
            	<tr>
                	<td width="54%">
						<table class="" cellspacing="0" border="0" width="100%">
							<tr>
								<td><strong>DATOS DEL PACIENTE</strong></td>
							</tr>
							<tr>
                                <td width="35%" >Nombre Completo:</td>
                                <td width="65%">'.strtoupper($datos[0]['nombres']).' '.strtoupper($datos[0]['apellidopat']).' '.strtoupper($datos[0]['apellidomat']).'</td>
							</tr>
							<tr>
                                <td width="35%" >Rut, Pasaporte u Otro:</td>
                                <td width="65%">'.$rut.'</td>
							</tr>
							<tr>
                                <td width="35%" >Fecha de Nacimiento:</td>
                                <td width="65%">'.strtoupper(date("d-m-Y",strtotime($datos[0]['fechanac']))).'</td>
							</tr>
							<tr>
                                <td width="35%" >Nacionalidad:</td>
								<td width="65%">';
									if($datos[0]['NACdescripcion'] == ""){
										$html .= ''.strtoupper($datos[0]['nacionalidad']).'';
									} else {
										$html .= ''.strtoupper($datos[0]['NACdescripcion']).'';
									}
								$html .= '
								</td>
							</tr>
							<tr>
                                <td width="35%" >País de Nacimiento:</td>
								<td width="65%">';
									if($datos[0]['NACpais'] == ""){
										$html .= ''.strtoupper('No Informada').'';
									} else {
										$html .= ''.strtoupper($datos[0]['NACpais']).'';
									}
								$html .= '
								</td>
							</tr>
							<tr>
                                <td width="35%" >Región:</td>
								<td width="65%">';
									if($datos[0]['REG_Descripcion'] == ""){
										$html .= ''.strtoupper('No Informada').'';
									} else {
										$html .= ''.strtoupper($datos[0]['REG_Descripcion']).'';
									}
								$html .= '
								</td>
							</tr>
							<tr>
                                <td width="35%" >Ciudad:</td>
								<td width="65%">';
									if($datos[0]['CIU_Descripcion'] == ""){
										$html .= ''.strtoupper('No Informada').'';
									} else {
										$html .= ''.strtoupper($datos[0]['CIU_Descripcion']).'';
									}
								$html .= '
								</td>
							</tr>
							<tr>
                                <td width="35%" >Comuna:</td>
								<td width="65%">';
									if($datos[0]['comuna'] == ""){
										$html .= ''.strtoupper('No Informada').'';
									} else {
										$html .= ''.strtoupper($datos[0]['comuna']).'';
									}
								$html .= '
								</td>
							</tr>
							<tr>
                                <td width="35%" >Dirección:</td>
								<td width="65%">'.strtoupper($datos[0]['dau_paciente_domicilio']).'</td>
							</tr>
							<tr>
                                <td width="35%" >Sector:</td>
								<td width="65%">';
									if($datos[0]['dau_paciente_domicilio_tipo'] == "R"){
										$html .= ''.strtoupper('Rural').'';
									} else if ($datos[0]['dau_paciente_domicilio_tipo'] == "U"){
										$html .= ''.strtoupper('Urbano').'';
									}
								$html .= '
								</td>
							</tr>
							<tr>
                                <td width="35%" >Teléfonos:</td>
								<td width="65%">';
								if($datos[0]['PACfono']==0){
									$html .= "FIJO NO DEFINIDO";
								} else {
									$html .= "".$datos[0]['PACfono']."";
								}

								$html .= ', ';

								if($datos[0]['fono1']==0){
									$html .= " CELULAR NO DEFINIDO";
								} else {
									$html .= "".$datos[0]['fono1']."";
								}
								$html .= '
								</td>
							</tr>
							<tr>
                                <td width="35%" >Consultorio:</td>
                                <td width="65%">'.strtoupper($datos[0]['con_descripcion']).'</td>
							</tr>
                        </table>
					</td>
					<td width="46%">
							<table class="enoformSin " cellspacing="0" border="0" width="100%">
							<tr>
								<td>&nbsp;</td>
							</tr>
							<tr>
                                <td width="35%" >Lugar de Accidente:</td>
							</tr>
							<tr>
                                <td width="35%" >N. Acompañante:</td>
							</tr>
							<tr>
                                <td width="35%" >Motivo de consulta:</td>
                                <td width="65%">'.strtoupper($datos[0]['mot_descripcion']).' - '.strtoupper($datos[0]['dau_motivo_descripcion']).''.$manifestaciones.'</td>
							</tr>';
								if($datos[0]['mor_descripcion']!=''){
									$html .= '<tr>';
									$html .= "<td>Mordedura:</td>";
									$html .= "<td>".$datos[0]['mor_descripcion']."</td>";
									$html .= '</tr>';
								}
								if($datos[0]['int_descripcion']!=''){
									$html .= '<tr>';
									$html .= "<td>Intoxicación:</td>";
									$html .= "<td>".$datos[0]['int_descripcion']."</td>";
									$html .= '</tr>';
								}
								if($datos[0]['que_descripcion']!=''){
									$html .= '<tr>';
									$html .= "<td>Quemado:</td>";
									$html .= "<td>".$datos[0]['que_descripcion']."</td>";
									$html .= '</tr>';
								}
							$html .= '
							<tr>
                                <td width="35%" >Edad:</td>
                                <td width="65%">'.$datos[0]['dau_paciente_edad'].'</td>
							</tr>
							<tr>
                                <td width="35%" >Etnia:</td>
                                <td width="65%">'.strtoupper($datos[0]['etn_descripcion']).'</td>
							</tr>
							<tr>
                                <td width="35%" >Afrodescendiente:</td>
								<td width="65%">';
								if($datos[0]['PACafro'] == 0){
									$html .= 'No';
								} else {
									$html .= 'Si';
								}
								$html .= '
									
								</td>
							</tr>
							<tr>
                                <td width="35%" >Sexo:</td>
								<td width="65%">';
								if($datos[0]['sexo']=='M'){
									$html .= "Masculino";
								} else if ($datos[0]['sexo']=='F') {
									$html .= 'Femenino';
								}
								$html .= '
								</td>
							</tr>
							<tr>
                                <td width="35%" >Medio de Transporte:</td>
                                <td width="65%">'.strtoupper($datos[0]['med_descripcion']).'</td>
							</tr>
							<tr>
                                <td width="35%" >Tipo de Atención:</td>
                                <td width="65%">'.strtoupper($datos[0]['ate_descripcion']).'</td>
							</tr>
							<tr>
                                <td width="35%" >Previsión:</td>
                                <td width="65%">'.strtoupper($datos[0]['prevision']).'</td>
							</tr>
							<tr>
                                <td width="35%" >Forma de Pago:</td>
                                <td width="65%">'.strtoupper($datos[0]['instNombre']).'</td>
							</tr>
							<tr>
                                <td width="35%" >Categorización:</td>
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
										$horaCategorizacion = date("H:i:s",strtotime($datosDau[0]['dau_categorizacion_fecha']));
									}


								$html .= ''.$cate.' ( '.$fechaCategorizacion.' '.$horaCategorizacion.')
								</td>
							</tr>
						</table>
					</td>
                </tr>
            </table>
        </td>
	</tr>

	<tr nobr="true">
        <td class="enoform">
        	<table class="enoformSin " cellspacing="0" border="0" width="100%">
				<tr>
					<td width="23%">
						<table class="enoformSin " cellspacing="2" border="0" width="100%">
							<tr>
								<td >Alcoholemia:</td>
								<td>';
								if(is_null($datosDau[0]['dau_alcoholemia_fecha']) && empty($datosDau[0]['dau_alcoholemia_fecha'])){
									$html .=  "No";
								} else {
									$html .= "Si";
								}
								$html .= '</td>
							</tr>
						</table>
					</td>

					<td width="15%">
						<table class="enoformSin " cellspacing="2" border="0" width="100%">
							<tr>
                                <td>Auge:</td>
                                <td>';
									if($datosDau[0]['dau_cierre_auge'] == "S"){
										$html .= 'Si';
									} else {
										$html .= 'No';;
									}
								$html .= '
								</td>
							</tr>
						</table>
					</td>

					<td width="20%">
						<table class="enoformSin " cellspacing="2" border="0" width="100%">
							<tr>
								<td>Pertinente:</td>
								<td>';
								if($datosDau[0]['dau_cierre_pertinencia'] == 'N' || $datosDau[0]['dau_cierre_pertinencia'] == NULL || $datosDau[0]['dau_cierre_pertinencia'] == ''){
									$html .= "No";
								} else {
									$html .= "Si";
								}
								$html .= '
								</td>
							</tr>
						</table>
					</td>

					<td width="20%">
						<table class="enoformSin " cellspacing="2" border="0" width="100%">
							<tr>
								<td>Postinor:</td>
								<td>';
								if($datosDau[0]['dau_cierre_entrega_postinor'] == 'N' || $datosDau[0]['dau_cierre_entrega_postinor'] == NULL || $datosDau[0]['dau_cierre_entrega_postinor'] == '' ){
									$html .= "No";
								} else {
									$html .= "Si";
								}
								$html .= '
								</td>
							</tr>
						</table>
					</td>

					<td width="22%">
						<table class="enoformSin " cellspacing="2" border="0" width="100%">
							<tr>
								<td>Hepatitis B:</td>
								<td>';
								if($datosDau[0]['dau_cierre_hepatitisB'] == 'N' || $datosDau[0]['dau_cierre_hepatitisB'] == NULL || $datosDau[0]['dau_cierre_hepatitisB'] == '' ){
									$html .= "No";
								} else {
									$html .= "Si";
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
							<table class="enoformSin " cellspacing="2" border="0" width="100%">
								<tr>
									<td>Estado Etílico: '.$datosDau[0]['eti_descripcion'].'</td>
								</tr>
							</table>
						</td>

						<td width="25%">
							<table class="enoformSin " cellspacing="2" border="0" width="100%">
								<tr>
									<td>N° de Frasco: '.$datosDau[0]['dau_alcoholemia_numero_frasco'].'</td>
								</tr>
							</table>
						</td>

						<td width="50%">
							<table class="enoformSin " cellspacing="2" border="0" width="100%">
								<tr>';

								if ($datosDau[0]['dau_alcoholemia_fecha'] == "") {
									$html .= '
										<td>Fecha: ------ </td>';
								} else {
									$html .= '
										<td>Fecha: '.date("d-m-Y H:i:s",strtotime($datosDau[0]['dau_alcoholemia_fecha'])).'</td>';
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
	</tr>

	<tr >
        <td class="enoform"><br>
			<table class="enoformSin " cellspacing="0" border="0" width="100%">
			<tr>
								<td><strong style=" ">SIGNOS VITALES</strong></td>
							</tr>
				<tr>
					<td width="100%">
					
						<table class="enoformSin " cellspacing="0" border="0" width="98%">
							';

							if ( is_null($listarSignos[0]['SVITALfecha']) || empty($listarSignos[0]['SVITALfecha']) ) {
								$html .= '	<tr>
												<td width="100%"></td>
											</tr>';
							} else {

								$html .= '

								<tr>
									<td width="15%" align="center"><strong>Usuario y Fecha</strong></td>
									<td  align="center"><strong>PA</strong></td>
									<td  align="center"><strong>PAM</strong></td>
									<td  align="center"><strong>SAT</strong></td>
									<td   align="center"><strong>FIO2</strong></td>
									<td   align="center"><strong>FR</strong></td>
									<td   align="center"><strong>HGT</strong></td>';
									if($datos[0]['dau_atencion'] == 3){ 
									$html .= '<td   align="center"><strong>LCF</strong></td>
									<td   align="center"><strong>RBNE</strong></td>';
							 		} 
					$html 		.= '<td   align="center"><strong>GCS</strong></td>
									<td   align="center"><strong>T°</strong></td>
									<td   align="center"><strong>EVA</strong></td>
								</tr>';

								for ($r=0; $r<count($listarSignos) ; $r++) {
									$html .= 
								'<tr>';
									$html.= '
									<td align="center">'.$listarSignos[$r]['SVITALusuario'].'<br>'.date("d-m-Y H:i",strtotime($listarSignos[$r]['SVITALfecha'])).'</td>';
									$html .= '
									<td align="center">'.$listarSignos[$r]['SVITALsistolica'].'</td>
									<td align="center">'.$listarSignos[$r]['SVITALPAM'].'</td>
									<td align="center">'.$listarSignos[$r]['SVITALsaturacion'].'</td>
									<td align="center">'.$listarSignos[$r]['FIO2'].'</td>
									<td align="center">'.$listarSignos[$r]['SVITALfr'].'</td>
									<td align="center">'.$listarSignos[$r]['SVITALHemoglucoTest'].'</td>
										';
									if($datos[0]['dau_atencion'] == 3){ 
										$html .=   
									'<td align="center">'.$listarSignos[$r]['SVITALfeto'].'</td>
									<td align="center">'.$listarSignos[$r]['SVITALrbne'].'</td>';
							 		} 
									$html 		.= '
									<td align="center">'.$listarSignos[$r]['SVITALglasgow'].'</td>
									<td align="center">'.$listarSignos[$r]['SVITALtemperatura'].'</td>
									<td align="center">'.$listarSignos[$r]['SVITALeva'].'</td>
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

	<tr >
		<td class="enoform">
			<table class="enoformSin " cellspacing="0" border="0" width="100%">
				<tr>
					<td >
						<table class="enoformSin " cellspacing="0" border="0" width="100%">
							<tr>
								<td><strong style=" ">Motivo Consulta</strong></td>
							</tr>
							<tr>';
								$resRceDau[0]['regMotivoConsulta'] = str_replace("<", "&#60;", $resRceDau[0]['regMotivoConsulta']);
								$resRceDau[0]['regMotivoConsulta'] = str_replace("&#60;br>", "<br>", $resRceDau[0]['regMotivoConsulta']);

								$html .= '
                                <td  style="text-align: justify;" width="100%">'.$resRceDau[0]['regMotivoConsulta'].'</td>
							</tr>
						</table>
					</td>
					</tr>
					<tr>
					<td >
						<table class="enoformSin " cellspacing="0" border="0" width="100%">
							<tr>
								<td><strong style=" ">Hipótesis Diagnóstica Inicial</strong></td>
							</tr>
							<tr>';
								$resRceDau[0]['regHipotesisInicial'] = str_replace("<", "&#60;", $resRceDau[0]['regHipotesisInicial']);
								$resRceDau[0]['regHipotesisInicial'] = str_replace("&#60;br>", "<br>", $resRceDau[0]['regHipotesisInicial']);

								$html .= '
                                <td  style="text-align: justify;" width="100%">'.$resRceDau[0]['regHipotesisInicial'].'</td>
							</tr>
						</table>
					</td>
				</tr>';
				for ($i = 0; $i < count($rsMovimientoEVOxEspe); $i++) {
					$trUsuario = "";
					if($rsMovimientoEVOxEspe[$i]['tipo'] == 1){
						$rsMovimientoEVOxEspe[$i]['titulo'] = "Evolución Especialista (".$rsMovimientoEVOxEspe[$i]['titulo'].")";
					}
					if($rsMovimientoEVOxEspe[$i]['tipo'] == 3){
						$rsMovimientoEVOxEspe[$i]['titulo'] = "Solicitud Especialista (".$rsMovimientoEVOxEspe[$i]['titulo'].")";
					}
					$fechaHora 			= $rsMovimientoEVOxEspe[$i]['fecha'];
					list($fecha, $hora) = explode(' ', $fechaHora);

					// if($rsMovimientoEVOxEspe[$i]['evolucion'] == ""){
					// 	$rsMovimientoEVOxEspe[$i]['evolucion'] = "(- - - - -)";
					// }
					// if($rsMovimientoEVOxEspe[$i]['usuario'] != ""){
						$trUsuario = $rsMovimientoEVOxEspe[$i]['usuario'].' '.$fecha.' a las '.substr($hora, 0, -3);
						// $trUsuario = '<tr> <td width="100%" style="text-align: right;">'.$rsMovimientoEVOxEspe[$i]['usuario'].' '.date('d-m-Y', strtotime($rsMovimientoEVOxEspe[$i]['fecha'])).' a las '.date('H:m', strtotime($rsMovimientoEVOxEspe[$i]['fecha'])).'</td> </tr>';
					// }
				$html .= '<tr> 
					<td>
						<table class="enoformSin " cellspacing="0" border="0" width="100%">
							<tr>
								
                                <td width="100%" style="text-align: justify;"><strong style=" ">'.$rsMovimientoEVOxEspe[$i]['titulo'].' '.$trUsuario.'  &nbsp;:&nbsp;</strong><br>'.$rsMovimientoEVOxEspe[$i]['evolucion'].'</td>
							</tr>
						</table>
					</td> 
				</tr>';

				}
				
			$html .='</table>
		</td>
	</tr>';
	$html .=' <tr nobr="true">
		<td class="enoform">
			<table class="enoformSin " cellspacing="0" border="0" width="100%">
				<tr>
					<td width="100%">
						<table class="enoformSin " cellspacing="5" border="0" width="100%">
							<tr>
								<td><strong style=" ">INDICACIONES MEDICAS</strong></td>
							</tr>';
							
							if($tratamientosArr != ""){
								$html .='<tr> <td width="15%"><strong style=" font-size:7px;">TRATAMIENTOS</strong> </td><td width="85%" style=" text-align: justify;" >'.$tratamientosArr.'. </td> </tr>';
							}
							if($imagenologiaArr != ""){
							$html .='<tr> <td width="15%"><strong style=" font-size:7px;">IMAGENOLOGIA</strong> </td><td width="85%" style=" text-align: justify;" >'.$imagenologiaArr.'. </td> </tr>';
							}
							if($laboratorioArr != ""){
							$html .='<tr> <td width="15%"><strong style=" font-size:7px;">LABORATORIO</strong> </td><td width="85%" style=" text-align: justify;" >'.$laboratorioArr.'. </td> </tr>';
							}
							if($procedimientoArr != ""){
							$html .='<tr> <td width="15%"><strong style=" font-size:7px;">PROCEDIMIENTOS</strong> </td><td width="85%" style=" text-align: justify;" >'.$procedimientoArr.'. </td> </tr>';
							}
							if($otrosArr != ""){
							$html .='<tr> <td width="15%"><strong style=" font-size:7px;">OTROS</strong> </td><td width="85%" style=" text-align: justify;" >'.$otrosArr.'. </td> </tr>';
							}
						
				$html .='</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>';

	// if ( $datos[0]['dau_atencion'] == 3 && ! empty($listarEvoluciones) && ! is_null($listarEvoluciones) ) {

	// 	$html .= '
	// 			<tr nobr="true">
	// 				<td class="enoform">
	// 					<table class="enoformSin " cellspacing="0" border="0" width="100%">
	// 						<tr>
	// 							<td><strong>Evoluciones</strong></td>
	// 						</tr>
	// 						<tbody>';

	// 						foreach ( $listarEvoluciones as $evolucion ) {

	// 							$html .= '
	// 									<tr>
	// 										<td><small> - '.$evolucion['SEVOevolucion'].'</small></td>
	// 									</tr>
	// 									';
	// 						}

	// 	$html .= '
	// 					</tbody>
	// 					</table>
	// 				</td>
	// 			</tr>
	// 			';

	// }

	$html .= '<tr nobr="true">
		<td class="enoform">
			<table class="enoformSin " cellspacing="0" border="0" width="100%">
				<tr>
					<td width="100%">
						<table class="enoformSin " cellspacing="2" border="0" width="100%">
							<tr>
								<td>Destino: ';
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
													$html .= $obtenerIndicacionEgreso[0]['dau_ind_otrosArr'];
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
								</td>
								<td>Pronóstico Médico Legal Provisorio: ';
								for ($q=0; $q<count($rsPronostico) ; $q++) {
									if($resRceDau[0]['PRONcodigo'] == $rsPronostico[$q]['PRONcodigo']){
										$html .= $rsPronostico[$q]['PRONdescripcion'];
									}
								}
								$html .= '
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr nobr="true">
		<td class="enoform">
			<table class="enoformSin " cellspacing="0" border="0" width="100%">
				<tr>
					<td width="100%">
						<table class="enoformSin " cellspacing="2" border="0" width="100%">
							<tr>
								<td><strong style=" ">Hipótesis Final</strong></td>
							</tr>';

							if ( $resRceDau[0]['regHipotesisFinal'] != '') {
								$html .= '
									<tr>
										<td width="100%">'.$resRceDau[0]['regHipotesisFinal'].'</td>
									</tr>';
							}

							if ($resRceDau[0]['regCIE10Abierto'] != '' ) {

								$resRceDau[0]['regCIE10Abierto'] = str_replace("<", "&#60;", $resRceDau[0]['regCIE10Abierto']);
								$resRceDau[0]['regCIE10Abierto'] = str_replace("&#60;br>", "<br>", $resRceDau[0]['regCIE10Abierto']);

								$html .= '
									<tr>
										<td width="100%">'.$resRceDau[0]['regCIE10Abierto'].'</td>
									</tr>';
							}

						$html .= '
						</table>
					</td>
					</tr>
					<tr>
					<td width="100%">
						<table class="enoformSin " cellspacing="2" border="0" width="100%">
							<tr>

								<td><strong style=" ">Indicaciones Médicas Alta Urgencia / Receta</strong></td>
							</tr>';

							if ( $resRceDau[0]['regIndicacionEgresoUrgencia'] != '' ) {
								$html .= '
									<tr>';
										$resRceDau[0]['regIndicacionEgresoUrgencia'] = str_replace("<", "&#60;", $resRceDau[0]['regIndicacionEgresoUrgencia']);
										$resRceDau[0]['regIndicacionEgresoUrgencia'] = str_replace("&#60;br>", "<br>", $resRceDau[0]['regIndicacionEgresoUrgencia']);

										$html .= '
										<td width="100%">'.$resRceDau[0]['regIndicacionEgresoUrgencia'].'</td>
									</tr>';
							}

							$html .= '
						</table>
					</td>
				</tr>
				
			</table>
		</td>
	</tr>';
	if( $datos[0]['dau_se_entrega_informacion'] == 'S' ){
		$datos[0]['dau_se_entrega_informacion'] = 'Sí';
		$datos[0]['dau_se_entrega_informacionTexto'] = '¿A quién? : '.$datos[0]['dau_entrega_informacion'].' ';
	}else{
		$datos[0]['dau_se_entrega_informacion'] = 'No';
		$datos[0]['dau_se_entrega_informacionTexto'] = 'Observación : '.$datos[0]['dau_observacionEntregaInformacion'].' ';
	}
	$html .= '
	<tr>
        <td class="enoform">
        	<table class="enoformSin " cellspacing="5" border="0" width="100%">
				<tr>
					<td width="25%">
						¿Se entrega información? : '.$datos[0]['dau_se_entrega_informacion'].' 
					</td>
					<td width="75%">
						'.$datos[0]['dau_se_entrega_informacionTexto'].'
					</td>';
				$html .= '
				</tr>
			</table>
		</td>
	</tr>';

if (is_array($registroViolencia) && count($registroViolencia) > 0 ) {
	$html .= '
	<tr>
        <td class="enoform">
        	<table class="enoformSin " cellspacing="5" border="0" width="100%">
				<tr>
					<td width="25%">
						Violencia: ';
								if(is_null($registroViolencia) || empty($registroViolencia)){
									$html .=  "No";
								} else {
									$html .= "Si";
								}
					$html .= '
					</td>';

					if ( ! empty($registroViolencia['descripcionTipoViolencia']) && ! is_null($registroViolencia['descripcionTipoViolencia']) ) {

						$html .= '
							<td width="50%">
								Tipo: '.$registroViolencia['descripcionTipoViolencia'].'
							</td>
							';

					}

				$html .= '</tr>

				<tr>';



				if ( ! empty($registroViolencia['descripcionTipoAgresor']) && ! is_null($registroViolencia['descripcionTipoAgresor']) ) {

					$html .= '
							<td width="25%">
								Agresor: '.$registroViolencia['descripcionTipoAgresor'].'
							</td>
							';

				}

				if ( ! empty($registroViolencia['descripcionLesionVictima']) && ! is_null($registroViolencia['descripcionLesionVictima']) ) {

					$html .= '
							<td width="28%">
								Lesión: '.$registroViolencia['descripcionLesionVictima'].'
							</td>
							';

				}

				if ( ! empty($registroViolencia['descripcionSospechaPenetracion']) && ! is_null($registroViolencia['descripcionSospechaPenetracion']) ) {

					$html .= '
							<td width="25%">
								Penetración: '.$registroViolencia['descripcionSospechaPenetracion'].'
							</td>
							';

				}

				if ( ! empty($registroViolencia['descripcionProfilaxis']) && ! is_null($registroViolencia['descripcionProfilaxis']) ) {

					$html .= '
							<td width="25%">
								Profilaxis: '.$registroViolencia['descripcionProfilaxis'].'
							</td>
							';

				}

				if ( ! empty($registroViolencia['victimaEmbarazada']) && ! is_null($registroViolencia['victimaEmbarazada']) ) {

					$html .= '
							<td width="25%">
								Embarazada: '.( ($registroViolencia['victimaEmbarazada'] == 'S') ? 'Si' : 'No' ).'
							</td>
							';

				}

				if ( ! empty($registroViolencia['peritoSexual']) && ! is_null($registroViolencia['peritoSexual']) ) {

					$html .= '
					</tr><tr>
							<td width="25%">
								Perito Sexual: '.$registroViolencia['peritoSexual'].'
							</td>
							';

				}

				$html .= '
				</tr>
			</table>
		</td>
	</tr>';
	}

	if ( ! empty($listarObsEspecialista) && ! is_null($listarObsEspecialista) && ! empty($listarObsEspecialista[0]['SESPobservacionEspecialista']) ) {

		$html .= '
				<tr nobr="true">
					<td class="enoform">
						<table class="enoformSin " cellspacing="0" border="0" width="100%">
							<tr>
								<td coslpan="2"><strong>Solicitud Especialista</strong></td>
							</tr>
							<tbody>';

							foreach ( $listarObsEspecialista as $obsEspecialista ) {

								if ( is_null($obsEspecialista['SESPobservacionEspecialista']) || empty($obsEspecialista['SESPobservacionEspecialista'])  ) {

									continue;

								}

								$obsEspecialista['SESPobservacionEspecialista'] = str_replace("<", "&#60;", $obsEspecialista['SESPobservacionEspecialista']);
								$obsEspecialista['SESPobservacionEspecialista'] = str_replace("&#60;br>", "<br>", $obsEspecialista['SESPobservacionEspecialista'] );

								$html .= '
										<tr>
											<td width="20%"> Solicitado Por:</td>
											<td width="80%">'.$obsEspecialista['SESPusuario'].'</td>
										</tr>
										<tr>
											<td width="20%"> Evaluado Por:</td>
											<td width="80%">'.$obsEspecialista['SESPusuarioAplica'].'</td>
										</tr>
										<tr>
											<td width="20%"> Observacion:</td>
											<td width="80%">'.$obsEspecialista['SESPobservacionEspecialista'].'</td>
										</tr>
										';
							}

		$html .= '
						</tbody>
						</table>
					</td>
				</tr>
				<br>
				<br>
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
				<tr nobr="true">
					<td class="enoform">
						<table class="enoformSin " cellspacing="0" border="0" width="100%" style="text-align:center;">
							<tr>
								<td width="100%">
									<strong>Siendo las '.$horaMensaje.' horas del dia '.$fechaMensaje.', el Medico Cirujano que suscribe, declara que, presenta una patología que le condiciona riesgo vital y/o riesgo de secuela funcional grave de no mediar tratamieto inmediato y, por lo tanto, se encuentra en la condición definida como Emergencia o Urgencia en ley 19.650 y por Decreto Supremo N° 896 del Ministerio de Salud</strong>
								</td>
							</tr>
						</table>
					</td>
				</tr>';


	}

	$html .=
	'<br>
	<br>
	<tr nobr="true">
		<td>
			<table class="enoformSin " cellspacing="0" border="0"  style="text-align:right;">
				<tr>';


	$medicoInvolucradoGinecologia = false;

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
							<strong>'.$resultadoUsuarioIndicaciones[0]['PROdescripcion'].'</strong></small>
						</td>
					</tr>
					<tr style="text-align:center;">
						<td>
							<strong>'.$objUtil->formatearNumero($resultadoUsuarioIndicaciones[0]['PROcodigo']).'-'.$objUtil->generaDigito($resultadoUsuarioIndicaciones[0]['PROcodigo']).'</strong></small>
						</td>
					</tr>
				</table>';

		}  else if ( ! empty($resultadoUsuarioIndicaciones[0]['PROcodigo']) && ! is_null($resultadoUsuarioIndicaciones[0]['PROcodigo']) ) {
			$html .= '
				<table style="margin-top:50px;">
					<tr style="text-align:center;">
						<td style="height:40px;"> </td>
					</tr>
					<tr style="text-align:center;">
						<td>
							<strong>'.$resultadoUsuarioIndicaciones[0]['PROdescripcion'].'</strong></small>
						</td>
					</tr>
					<tr style="text-align:center;">
						<td>
							<strong>'.$objUtil->formatearNumero($resultadoUsuarioIndicaciones[0]['PROcodigo']).'-'.$objUtil->generaDigito($resultadoUsuarioIndicaciones[0]['PROcodigo']).'</strong></small>
						</td>
					</tr>
				</table>';
		}


		$html .= '</td>';

		if ( $datos[0]['dau_atencion'] == 3 && ($resultadoUsuarioIndicaciones[0]['TIPROcodigo'] == 1 || $resultadoUsuarioIndicaciones[0]['TIPROcodigo'] == 15) ) {

			$medicoInvolucradoGinecologia = true;

		}

	}

$html .= '
				</tr>
			</table>
		</td>
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
$nombre_archivo = '22Informe_RCE_'.$parametros['dau_id'].'_'.$datos[0]['id_paciente'].'_'.date("d-m-Y",strtotime($datosDau[0]['dau_indicacion_egreso_fecha'])).'_'.date("H-i-s",strtotime($datosDau[0]['dau_indicacion_egreso_fecha'])).'.pdf';
$pdf->Output($nombre_archivo,'I');

// if($banderaLlamada == 'altaUrgencia'){

	// if ( $datos[0]['dau_atencion'] == 3 && $medicoInvolucradoGinecologia == true ) {

	// 	$objDetalleDau->actualizarMedicoInvolucradoGinecologia($objCon, $parametros['dau_id']);

	// }


	$archivo['nombreArchivo'] = $nombre_archivo;
	// $archivo['fechaArchivo'] = $datosDau[0]['dau_indicacion_egreso_fecha'];
	$archivo['fechaArchivo'] = "222222222";
	// print('<pre>'); print_r($archivo); print('</pre>');
	$objFormulario->subeFormularioPDF($archivo);
	// $parametrosAEnviar['dau_id'] 			= $datos[0]['dau_id'];
	// $parametrosAEnviar['dau_run_pac'] 		= $datos[0]['rut'];
	// $parametrosAEnviar['dau_nombre_pac'] 	= strtoupper($datos[0]['nombres']).' '.strtoupper($datos[0]['apellidopat']).' '.strtoupper($datos[0]['apellidomat']);
	// $parametrosAEnviar['dau_sexo_pac'] 		= $datos[0]['sexo'];
	// $parametrosAEnviar['dau_direccion_pac'] = $datos[0]['direccion'];
	// $parametrosAEnviar['dau_fono_pac'] 		= $datos[0]['fono1'];
	// $objDetalleDau->actualizarDatosPDFAltaUrgencia($objCon, $parametrosAEnviar);
// }
// unlink($nombre_archivo);
// ---------------------------------------------------------




// //============================================================+
// // END OF FILE
// //============================================================+
// ?>

<script>
// $('#iframePDFDAU').ready(function(){
// 	ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','nombreArchivo=<?=$url?>&accion=eliminarPDF', 'POST', 'JSON', 1, '', true);
// });
</script>
