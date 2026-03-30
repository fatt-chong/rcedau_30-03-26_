<?php
header('Access-Control-Allow-Origin: *');
session_start();
ini_set('memory_limit', '1000M');
error_reporting(0);
// Include the main TCPDF library (search for installaon path).
require_once('../../../../../estandar/TCPDF-main/tcpdf.php');
require_once('../../../../../estandar/tcpdf/config/lang/spa.php');
require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php'); 				$objCon 		= new Connection; $objCon->db_connect();
require_once('../../../../class/Util.class.php');       				$objUtil    	= new Util;
require_once("../../../../class/Dau.class.php" );  						$objDetalleDau  = new Dau;




$parametros['dau_id']          = $_GET['idDau'];
$datosDetalleDau    		   = $objDetalleDau->obtenerDatosDetalleDau($objCon, $parametros['dau_id']);
$datosLT                       = $objDetalleDau->ListarPacienteLineaTiempo($objCon, $parametros);

$manifestaciones 			   = "";

if ( ! is_null($datosDetalleDau[0]['manifestaciones']) && ! empty($datosDetalleDau[0]['manifestaciones']) && $datosDetalleDau[0]['manifestaciones'] == 'S' ) {

	$manifestaciones = " (Manifestaciones)";

}

$transexual_bd 		= $datosDetalleDau[0]['transexual'];
$nombreSocial_bd 	= $datosDetalleDau[0]['nombreSocial'];
$nombrePaciente     = $datosDetalleDau[0]['nombreCompletoPaciente'];
$infoNombre    		= $objUtil->infoNombreDocMinuscula($transexual_bd,$nombreSocial_bd,$nombrePaciente);


$rsNea 						= $objDetalleDau->obtenerInformacionLlamados($objCon,$parametros['dau_id']);

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


$pdf = new MYPDF();

// add a page
$pdf->AddPage();

// set Rotate
// $params = $pdf->serializeTCPDFtagParameters(array(90));

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


<table class="bordeCeldaGrande" cellspacing="5" border="0" width="100%">
	<tr>
        <td>
            <table width="100%">
                <tr>
                    <td width="15%"><img src="/estandar/img/logo_gobierno_chile.jpg" width="55" height="55"></td>
					<td width="85%">
						<p class="titulo" align="center">Datos Detalle DAU
						<br>
						<strong>Folio: '.strtoupper($parametros['dau_id']).'</strong>
						<br>
						<small>Fecha y Hora Impresión: '.date("d-m-Y H:i").'</small></p>
					</td>
                </tr>
            </table>
        </td>
	</tr>

	<tr>
        <td class="enoform">
        	<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
            	<tr>
                	<td width="100%">
						<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
							<tr>
								<td><strong>Datos del Paciente</strong></td>
							</tr>
							<tr>
								<td width="15%" ><small>Nombre Completo</small></td>
								<td width="35%" ><small>: '.$infoNombre.'</small></td>
								<td width="15%" ><small>Edad</small></td>
								<td width="35%" ><small>: '.$objUtil->edadActualCompleto($datosDetalleDau[0]['fechaNacimientoPaciente']).'</small></td>
							</tr>
							<tr>
								<td width="15%" ><small>Religión</small></td>
								<td width="35%" ><small>: '.(isset($datosDetalleDau[0]['religion_descripcion']) ? $datosDetalleDau[0]['religion_descripcion'] : '-').'</small></td>
								<td width="15%" ><small></small></td>
								<td width="35%" ><small></small></td>
							</tr>
							<tr>
								<td width="15%" ><small>RUT</small></td>
								<td width="35%" ><small>: '.$objUtil->formatearNumero($datosDetalleDau[0]['rutPaciente']).'-'.$objUtil->generaDigito($datosDetalleDau[0]['rutPaciente']).'</small></td>
								<td width="15%" ><small>Ficha</small></td>
								<td width="35%" ><small>: '.$datosDetalleDau[0]['numeroFichaPaciente'].'</small></td>
							</tr>
							<tr>
								<td width="15%" ><small>Dirección</small></td>
								<td width="35%" ><small>: '.$datosDetalleDau[0]['direccionCompletaPaciente'].'</small></td>
								<td width="15%" ><small>Previsión</small></td>
								<td width="35%" ><small>: '.$datosDetalleDau[0]['previsionPaciente'].'</small></td>
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
                	<td width="100%">
						<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
							<tr>
								<td><strong>Datos de Atención</strong></td>
							</tr>
							<tr>
								<td width="10%" ><small>Paciente</small></td>
								<td width="25%" ><small>: '.$datosDetalleDau[0]['tipoAtencionPaciente'].'</small></td>
								<td width="10%" ><small>Consulta</small></td>
								<td width="25%" ><small>: '.$datosDetalleDau[0]['descripcionConsulta'].''.$manifestaciones.'</small></td>
								<td width="10%" ><small>Estado</small></td>
								<td width="20%" ><small>: '.$datosDetalleDau[0]['descripcionEstado'].'</small></td>
							</tr>
							<tr>
								<td width="10%" ><small>CAT</small></td>
								<td width="25%" ><small>: '.$datosDetalleDau[0]['categoriaPaciente'].'</small></td>
								<td width="10%" ><small>Sala</small></td>
								<td width="25%" ><small>: '.$datosDetalleDau[0]['descripcionSala'].'</small></td>
								<td width="10%" ><small>Cama</small></td>
								<td width="20%" ><small>: '.$datosDetalleDau[0]['descripcionCama'].'</small></td>
							</tr>
							<tr>
								<td width="10%" ><small>Detalle</small></td>
								<td width="90%" ><small>: '.$datosDetalleDau[0]['detalle'].'</small></td>
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
                	<td width="100%">
						<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
							<tr>
								<td><strong>Tiempos de Atención</strong></td>
							</tr>';

							for ($x=0; $x < 6 ; $x++) {
								if ($datosLT[$x]['estado'] == 1) {
									$estlt = "Admisión";
								}else if ($datosLT[$x]['estado'] == 2){
									$estlt = "Categorización";
								}else if ($datosLT[$x]['estado'] == 3){
									$estlt = "Inicio Atención";
								}else if ($datosLT[$x]['estado'] == 4){
									$estlt = "Indicación Egreso";
								}else if ($datosLT[$x]['estado'] == 5){
									if ($datosDetalleDau[0]['est_id'] == 5) {
										$estlt = "Cierre";
									}
								}else if ($datosLT[$x]['estado'] == 6){
									if ($datosDetalleDau[0]['est_id'] == 6) {
										$estlt = "Cierre: Anula";
									}else if ($datosDetalleDau[0]['est_id'] == 7){
										$estlt = "Cierre: N.E.A.";
									}else if ($datosDetalleDau[0]['est_descripcion	'] == 5){
										$estlt = "Cierre: Administrativo";
									}
								}else if ($datosLT[$x]['estado'] == 8){
									$estlt = "Ingreso Box";
								}

								if($datosLT[$x]['usuario'] != NULL ) {
									$html .= '
									<tr>
										<td width="25%" ><small>'.$estlt.' </small></td>
										<td width="75%" ><small>: '.$datosLT[$x]['usuario'].' '.date("d-m-Y H:i:s",strtotime($datosLT[$x]['fecha'])).'</small></td>
									</tr>';
								}
							}
						$html .= '
                        </table>
					</td>
                </tr>
            </table>
        </td>
	</tr>';
	if ( !empty($rsNea) ) {
		$html .= '<tr>
        <td class="enoform">
        	<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
            	<tr>
                	<td width="100%">
						<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
							<tr>
								<td><strong>N.E.A.</strong></td>
							</tr>';
		if( $rsNea['usuarioPrimerLlamado'] != "" ){
			$html .= '
			<tr>
				<td width="25%" ><small>N.E.A. Primer llamado </small></td>
				<td width="75%" ><small>: '.$rsNea['usuarioPrimerLlamado'].' '.date("d-m-Y H:i:s",strtotime($rsNea['fechaPrimerLlamado'])).'</small></td>
			</tr>';
		}
		if( $rsNea['usuarioSegundoLlamado'] != "" ){
			$html .= '
			<tr>
				<td width="25%" ><small>N.E.A. Segundo llamado </small></td>
				<td width="75%" ><small>: '.$rsNea['usuarioSegundoLlamado'].' '.date("d-m-Y H:i:s",strtotime($rsNea['fechaSegundoLlamado'])).'</small></td>
			</tr>';
		}
		if( $rsNea['usuarioTercerLlamado'] != "" ){
			$html .= '
			<tr>
				<td width="25%" ><small>N.E.A. Tercer llamado </small></td>
				<td width="75%" ><small>: '.$rsNea['usuarioTercerLlamado'].' '.date("d-m-Y H:i:s",strtotime($rsNea['fechaTercerLlamado'])).'</small></td>
			</tr>';
		}
		$html .= ' 
				</table>
					</td>
                </tr>
            </table>
        </td>
	</tr>';
	}
$html .='
	<tr>
        <td class="enoform">
        	<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
            	<tr>
                	<td width="100%">
						<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
							<tr>
								<td><strong>Indicaciones</strong></td>
							</tr>
							<tr>
								<td width="10%" ><small>Fecha</small></td>
								<td width="25%" ><small>: '.$datosDetalleDau[0]['fechaIndicacion'].'</small></td>
								<td width="10%" ><small>Indicación</small></td>
								<td width="25%" ><small>: '.$datosDetalleDau[0]['indicacion'].'</small></td>
								<td width="15%" ><small>Det. Indicación</small></td>
								<td width="15%" ><small>: '.$datosDetalleDau[0]['descripcionIndicacion'].'</small></td>
							</tr>
							<tr>
								<td width="10%" ><small>Estado</small></td>
								<td width="25%" ><small>: '.$datosDetalleDau[0]['descripcionEstadoIndicacion'].'</small></td>
								<td width="10%" ><small>Ind. Por</small></td>
								<td width="25%" ><small>: '.$datosDetalleDau[0]['usuarioIndica'].'</small></td>
								<td width="15%" ><small>Aplicada</small></td>
								<td width="15%" ><small>: '.$datosDetalleDau[0]['usuarioAplica'].'</small></td>
							</tr>
                        </table>
					</td>
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
$nombre_archivo = "informe_detalleDau.pdf";
$pdf->Output(__DIR__ . '/' . $nombre_archivo, 'FI');
// $pdf->Output($nombre_archivo,'FI');
unlink($nombre_archivo);
?>