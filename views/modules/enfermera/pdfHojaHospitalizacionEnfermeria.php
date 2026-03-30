<iframe height="100%" width="100%" hidden>

<?php

session_start();
error_reporting(0);
ini_set('post_max_size', '512M');
ini_set('memory_limit', '1G');
set_time_limit(0);
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Cache-Control: no-store");

require_once('../../../../estandar/TCPDF-main/tcpdf.php');
require("../../../config/config.php");
require_once('../../../class/Connection.class.php'); 			$objCon      			= new Connection; $objCon->db_connect();
require_once('../../../class/Util.class.php');       			$objUtil     			= new Util;
require_once('../../../class/Admision.class.php');   			$objAdmision 			= new Admision;
require_once('../../../class/RegistroClinico.class.php'); 		$objRegistroClinico		= new RegistroClinico;
require_once("../../../class/Dau.class.php" );  				$objDetalleDau       	= new Dau;
require_once("../../../class/Rce.class.php" );  				$objRce       			= new Rce;
require_once("../../../class/Servicios.class.php"); 			$objServicio     		= new Servicios;
require_once("../../../class/Agenda.class.php" );  				$objAgenda        		= new Agenda;
require_once("../../../class/Usuarios.class.php" );  			$objUsuarios        	= new Usuarios;
require_once('../../../class/Formulario.class.php'); 			$objFormulario 			= new Formulario;
require_once('../../../class/Especialista.class.php'); 			$objEspecialista		= new Especialista;
require_once('../../../class/HojaHospitalizacion.class.php');	$objHojaHospitalizacion	= new HojaHospitalizacion;
require_once('../../../class/Bitacora.class.php'); 				$objBitacora			= new Bitacora;
require_once('../../../class/AltaUrgencia.class.php'); 			$objAltaUrgencia		= new AltaUrgencia;
require_once('../../../class/HojaEnfermeria.class.php');     	$objHoja_enfermeria     = new Hoja_enfermeria;


$parametros                 	= $objUtil->getFormulario($_POST);
if (!empty($_GET['idDau'])) {
    $parametros['idDau'] = $_GET['idDau'];
    $parametros['dau_id'] = $_GET['idDau'];
}
$parametros['dau_id'] 			= $parametros['idDau'];
$dau_id 						= $parametros['dau_id'];
$rsRce                      	= $objRegistroClinico->consultaRCE($objCon,$parametros);
$datosPaciente              	= $objHojaHospitalizacion->obtenerDatosPaciente($objCon, $parametros);
$listarSignos 		  			= $objRce->listarSignosVitales($objCon,$datosPaciente[0]['id_paciente'], $rsRce[0]['regId']);
$rsHoja                         = $objHoja_enfermeria->SelectFormularioEnfermeriaById($objCon, $dau_id);

$rsExamenesRealizados           = $objHoja_enfermeria->SelectExamenesRealizados($objCon, $rsRce[0]['regId']);
$rsTratamientosRealizados       = $objHoja_enfermeria->SelectTratamientosRealizados($objCon, $rsRce[0]['regId']);
$rsProcedimientosRealizados     = $objHoja_enfermeria->SelectIndicaciones_enfermeria($objCon, $parametros);
$fecha = isset($rsHoja[0]['fecha_creacion']) ? $rsHoja[0]['fecha_creacion'] : '';
$hora  = isset($rsHoja[0]['hora_creacion']) ? $rsHoja[0]['hora_creacion'] : '';

$meses = [
  '01' => 'ENERO',
  '02' => 'febrero',
  '03' => 'marzo',
  '04' => 'abril',
  '05' => 'mayo',
  '06' => 'junio',
  '07' => 'julio',
  '08' => 'agosto',
  '09' => 'septiembre',
  '10' => 'octubre',
  '11' => 'noviembre',
  '12' => 'diciembre',
];
$ojos 		= $rsHoja[0]['ojos'];
$verbal 	= $rsHoja[0]['verbal'];
$motora 	= $rsHoja[0]['motora'];
// Separar la fecha
$dia = $mes = $anio = '';
if ($fecha != '') {
    list($anio, $mesNum, $dia) = explode('-', $fecha);
    $mes = isset($meses[$mesNum]) ? strtoupper($meses[$mesNum]) : $mesNum;
}
$fecha_entrega = isset($rsHoja[0]['fecha_entrega']) ? $rsHoja[0]['fecha_entrega'] : '';

if (!empty($fecha_entrega)) {
    $fecha_entrega = date('d-m-Y', strtotime($fecha_entrega));
}
if($rsHoja[0]['tipoGlasgow'] == 'L'){
  $tipoGlas     = 'Lactante';
}else if($rsHoja[0]['tipoGlasgow'] == 'P'){
  $tipoGlas     = 'Pediatrico';
}else{
  $tipoGlas     = 'Adulto';
}
$glasgow = [
    'Adulto' => [
        'ojos' => [
            1 => 'Ninguna',
            2 => 'Al dolor',
            3 => 'Al hablar',
            4 => 'Espontánea'
        ],
        'verbal' => [
            1 => 'Ninguna',
            2 => 'Leng. incomprensible',
            3 => 'Leng. inapropiado',
            4 => 'Confuso',
            5 => 'Orientada'
        ],
        'motora' => [
            1 => 'Nula',
            2 => 'Extensión al dolor',
            3 => 'Flexión al dolor',
            4 => 'Mov. c/evitac. dolor',
            5 => 'Mov. sentido al dolor',
            6 => 'Obediente'
        ]
    ],
    'Pediatrico' => [
        'ojos' => [
            1 => 'Ninguna',
            2 => 'En respuesta al dolor',
            3 => 'al Oir una voz',
            4 => 'Espontánea'
        ],
        'verbal' => [
            1 => 'Ninguna',
            2 => 'Palabras/sonidos no especificos',
            3 => 'Palabras inapropiadas',
            4 => 'Confusa',
            5 => 'Orientada, apropiada'
        ],
        'motora' => [
            1 => 'Ninguna',
            2 => 'Extensión en respuesta al dolor',
            3 => 'Flexión en respuesta al dolor',
            4 => 'Retirada al dolor',
            5 => 'Localiza dolor',
            6 => 'Obedece ordenes'
        ]
    ],
    'Lactante' => [
        'ojos' => [
            1 => 'Ninguna',
            2 => 'En respuesta al dolor',
            3 => 'al Oir una voz',
            4 => 'Espontánea'
        ],
        'verbal' => [
            1 => 'Ninguna',
            2 => 'Gime en respuesta al dolor',
            3 => 'Llora en respuesta al dolor',
            4 => 'Irritable, llanto',
            5 => 'Arrullos y balbuceos'
        ],
        'motora' => [
            1 => 'Ninguna',
            2 => 'Postura de descerebración en respuesta al dolor',
            3 => 'Postura de decorticación en respuesta al dolor',
            4 => 'Retirada al dolor',
            5 => 'Se retrae al tacto',
            6 => 'Se mueve espontánea y deliberadamente'
        ]
    ]
];
$rsGlasgow = $glasgow[$tipoGlas];
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
$pdf->SetFont('helvetica', '', 10, '', true);

// add a page
$pdf->AddPage();



$html = '

<style>
    body {
      font-family: Arial, sans-serif;
      margin: 40px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    td, th {
      padding: 6px;
      vertical-align: top;
    }
    .section-title {
      font-weight: bold;
      text-decoration: underline;
      padding-top: 20px;
    }
    .header {
      text-align: center;
      font-weight: bold;
    }
    .box-table {
      	// border: 1px solid black;
      	// margin-top: 10px;

    	// padding: 4px 6px;
    }
    .box-table2 {
      	// border: 1px solid black;
      	// margin-top: 10px;
      	
    	// padding: 1px 4px;
    }
    .textoPequeño{
    	font-size: 9px !important;
    }
    .textoNormal{
    	font-size: 10.3px !important;
    }
    .textoGrande{
    	font-size: 12px !important;
    }
    .textoGigante{
    	font-size: 16px !important;
    }
  </style>
<br><br><br>
	<table border="0">
		<tr>
			<td width="20%"> </td>
			<td width="50%" style="text-align:center;">SERVICIO DE SALUD ARICA<br>HOSPITAL EN RED "DR. JUAN NOE CREVANI" <br> <strong>CR. EMERGENCIA HOSPITALARIA</strong><br><label class="textoPequeño">E.U. GGV/LHA </label></td>
			<td width="30%"  >  
		  		<table class="box-table textoNormal" >
					<tr>
					  <td><strong class="textoNormal">VACUNAS COVID</strong> :&nbsp;&nbsp;'.$rsHoja[0]['vacuna_covid'].'</td>
					</tr>
					<tr>
					  <td><strong>VACUNAS INFLUENZA</strong> :&nbsp;&nbsp;'.$rsHoja[0]['vacuna_influenza'].'</td>
					</tr>
			    </table>
		  	</td>
		</tr>
		<tr>
			<td> </td>
			<td style="text-align:center;" class="textoGigante" ><strong> <br>HOJA INGRESO DE ENFERMERIA ADULTO </strong> </td>
			<td >  
		  		<table class="box-table textoNormal" >
		  		<tr>
					  <td style="text-align:center;" ><strong><u>CONTACTO</u> </strong></td>
					</tr>
					<tr>
					  <td><strong>NÚMERO</strong> :&nbsp;&nbsp;'.$rsHoja[0]['contacto_numero'].' </td>
					</tr>
					<tr>
					  <td><strong>NOMBRE</strong> :&nbsp;&nbsp;'.$rsHoja[0]['contacto_nombre'].'</td>
					</tr>
					<tr>
					  <td><strong>PARENTESCO</strong> :&nbsp;&nbsp;'.$rsHoja[0]['contacto_parentesco'].'</td>
					</tr>
			    </table>
		  	</td>
		</tr>
  </table>

	<table class="textoNormal">
  <tr>
    <td width="8%">FECHA</td>
    <td width="5%">:&nbsp;&nbsp;<span style="border-bottom: 1px solid #000; display: inline-block; width: 100%;"><u>' . htmlspecialchars($dia) . '</u></span></td>
    <td width="5%">DE</td>
    <td width="12%"><span style="border-bottom: 1px solid #000; display: inline-block; width: 100%;"><u>' . htmlspecialchars($mes) . '</u></span></td>
    <td width="5%">DEL</td>
    <td width="10%"><span style="border-bottom: 1px solid #000; display: inline-block; width: 100%;"><u>' . htmlspecialchars($anio) . '</u></span></td>
    <td width="8%">HORA</td>
    <td>:&nbsp;&nbsp;<span style="border-bottom: 1px solid #000; display: inline-block; width: 100%;"><u>' . htmlspecialchars(substr($hora, 0, 5)) . '</u></span></td>
  </tr>
</table>
	<br>
  <div class="textoGrande"><strong><u>ANTECEDENTES PERSONALES:</strong></u></div>
	<br>
  <table border="0" class="textoNormal" >
    <tr>
      <td width="18%">NOMBRE</td><td colspan="2" width="44%">:&nbsp;&nbsp;'.$rsHoja[0]['nombre'].'</td>
      <td width="8%" >EDAD</td><td width="8%" >:&nbsp;&nbsp;'.$rsHoja[0]['edad'].'</td>
      <td width="12%" >PREVISIÓN</td><td>:&nbsp;&nbsp;'.$rsHoja[0]['prevision'].'</td>
    </tr>
    <tr>
      <td >RELIGIÓN</td><td colspan="7">:&nbsp;&nbsp;'.(isset($datosPaciente[0]['religion_descripcion']) ? htmlspecialchars($datosPaciente[0]['religion_descripcion']) : '-').'.</td>
    </tr>
    <tr>
      <td >MOTIVO CONSULTA</td><td colspan="7">:&nbsp;&nbsp;'.htmlspecialchars($rsHoja[0]['motivo_consulta']).'.</td>
    </tr>
  </table>
	<br>
  	<div class="textoGrande"><strong><u>ANTECEDENTES MÓRBIDOS Y QUIRÚRGICOS:</strong></u></div>
	<br>
  <table class="textoNormal" >
    <tr>
      <td width="15%">MEDICOS</td>
      <td width="15%">:&nbsp;&nbsp; HTA ( <strong>' . ($rsHoja[0]['frm_hta'] == "Sí" ? 'X' : ' NO ') . '</strong> )</td>
      <td width="15%" >DIABETES ( <strong>' . ($rsHoja[0]['frm_diabetes'] == "Sí" ? 'X' : ' NO ') . '</strong> )</td>
      <td width="15%" >OTRAS</td><td colspan="3" style="text-align: justify;" >:&nbsp;&nbsp;'.$rsHoja[0]['otras'].'.</td>
    </tr>
    <tr>
      <td width="15%" >QUIRÚRGICOS</td><td colspan="6" style="text-align: justify;" >:&nbsp;&nbsp;'.$rsHoja[0]['frm_quirurgicos'].'.</td>
    </tr>
    <tr>
      <td width="15%" >ALÉRGICOS</td>
      <td width="15%">:&nbsp;&nbsp;SI ( <strong>' . ($rsHoja[0]['frm_alergia'] == "Sí" ? 'X' : '  ') . '</strong> )</td>
      <td width="15%">NO ( <strong>' . ($rsHoja[0]['frm_alergia'] != "Sí" ? 'X' : '  ') . '</strong> )</td>
      <td width="15%">DESCONOCIDA</td><td colspan="3" style="text-align: justify;" >:&nbsp;&nbsp;'.$rsHoja[0]['frm_desconocida'].'.</td>
    </tr>
    <tr>
      <td width="15%" >MEDICAMENTOS</td><td colspan="6" style="text-align: justify;" >:&nbsp;&nbsp;'.$rsHoja[0]['frm_medicamentos_medicos'].'.</td>
    </tr>
  </table>
  <br>
  <br>
  <table class="box-table textoNormal" >
    <tr>
      <td ><strong>ANAMNESIS ENFERMERIA </strong></td>
    </tr>
    <tr>
      <td style="text-align: justify;" >'.htmlspecialchars($rsHoja[0]['frm_evolucion_enfermeria']).'.</td>
    </tr>
  </table>
  <br>
  <br>
  <table class="box-table textoNormal" >
    <tr>
      <td ><strong>EXAMEN FISICO GENERAL </strong></td>
    </tr>
    <tr>
      <td style="text-align: justify;" >'.htmlspecialchars($rsHoja[0]['frm_examen_fisico_general']).'.</td>
    </tr>
  </table>
  <br>
  <br>
  <table class="box-table textoNormal" >
    <tr>
      <td ><strong>VALORACION DE PIEL Y ZONAS DE APOYO </strong></td>
    </tr>
    <tr>
      <td style="text-align: justify;" >'.htmlspecialchars($rsHoja[0]['frm_obs_piel_ubicacion']).'.</td>
    </tr>
  </table>
  <br>
  <br>';

  if ($rsHoja[0]['tipobraden'] == 'N'){

  $html .= '
  <table class="textoNormal" >
    <tr>
      <td width="100%" style="text-align:center;"><strong><u>Escala de Braden para Paciente Neonatal NSRAS</strong></u></td>
    </tr>
  </table>
  <table width="100%" class="box-table textoNormal" border="1">
    <thead>
      <tr>
        <th width="10%" align="center" ></th>
        <th width="15%" align="center" >COND FISICA GENERAL</th>
        <th width="15%" align="center" >ESTADO MENTAL</th>
        <th width="15%" align="center" >MOVILIDAD</th>
        <th width="15%" align="center" >ACTIVIDAD</th>
        <th width="15%" align="center" >NUTRICIÓN</th>
        <th width="15%" align="center" >HUMEDAD</th>
      </tr>
    </thead>
    <tbody style="text-align:center;" >
      <tr>
        <td width="10%" align="center" >1</td>
        <td width="15%" align="center" >Muy Pobre  <strong>' . ($rsHoja[0]['cond_fisica'] == "1" ? '<br> X' : '') . '</strong> </td>
        <td width="15%" align="center" >Completamente limitado <strong>' . ($rsHoja[0]['estado_mental'] == "1" ? '<br> X' : '') . '</strong> </td>
        <td width="15%" align="center" >Completamente inmóvil <strong>' . ($rsHoja[0]['movilidad_neo'] == "1" ? '<br> X' : '') . '</strong> </td>
        <td width="15%" align="center" >Completamente encamado/a <strong>' . ($rsHoja[0]['actividad_neo'] == "1" ? '<br> X' : '') . '</strong> </td>
        <td width="15%" align="center" >Muy deficiente <strong>' . ($rsHoja[0]['nutricion_neo'] == "1" ? '<br> X' : '') . '</strong> </td>
        <td width="15%" align="center" >Piel constantemente húmeda <strong>' . ($rsHoja[0]['humedad_neo'] == "1" ? '<br> X' : '') . '</strong> </td>
      </tr>
      <tr>
        <td align="center" >2</td>
        <td align="center" >Edad Gestacional > 28 semanas y < 33 semanas <strong>' . ($rsHoja[0]['cond_fisica'] == "2" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Muy limitado <strong>' . ($rsHoja[0]['estado_mental'] == "2" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Muy limitado <strong>' . ($rsHoja[0]['movilidad_neo'] == "2" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Encamado/a<strong>' . ($rsHoja[0]['actividad_neo'] == "2" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Inadecuada <strong>' . ($rsHoja[0]['nutricion_neo'] == "2" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Puel húmeda <strong>' . ($rsHoja[0]['humedad_neo'] == "2" ? '<br> X' : '') . '</strong> </td>
      </tr>
      <tr>
        <td align="center" >3</td>
        <td align="center" >Edad Gestacional > 33 semanas y < 38 semanas <strong>' . ($rsHoja[0]['cond_fisica'] == "3" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Ligeramente limitado <strong>' . ($rsHoja[0]['estado_mental'] == "3" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Ligeramente limitada <strong>' . ($rsHoja[0]['movilidad_neo'] == "3" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Ligeramente limitada <strong>' . ($rsHoja[0]['actividad_neo'] == "3" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Adecuada <strong>' . ($rsHoja[0]['nutricion_neo'] == "3" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Piel ocasionalmente húmeda <strong>' . ($rsHoja[0]['humedad_neo'] == "3" ? '<br> X' : '') . '</strong> </td>
      </tr>
      <tr>
        <td align="center" >4</td>
        <td align="center" >Edad Gestacional > 38 semanas <strong>' . ($rsHoja[0]['cond_fisica'] == "4" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Sin limitaciones <strong>' . ($rsHoja[0]['estado_mental'] == "4" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Sin limitación <strong>' . ($rsHoja[0]['movilidad_neo'] == "4" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Sin limitación <strong>' . ($rsHoja[0]['actividad_neo'] == "4" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Excelente <strong>' . ($rsHoja[0]['nutricion_neo'] == "4" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Piel rara vez húmeda <strong>' . ($rsHoja[0]['humedad_neo'] == "4" ? '<br> X' : '') . '</strong> </td>
      </tr>
      <tr>
        <td colspan="3" style="text-align:left">
          <strong>RIESGO DE LPP BRADEN – BERGSTROM:</strong><br><br>
          <strong>ALTO RIESGO:</strong> Menor o igual a 12 puntos<br>
          <strong>RIESGO MODERADO:</strong> 13 a 14 puntos<br>
          <strong>BAJO RIESGO:</strong> Mayor o igual a 15 puntos<br>
          <strong>SIN RIESGO:</strong> Mayor a 18 puntos
        </td>
        <td colspan="4" style="text-align:left">
          <strong>VALORACIÓN DEL RIESGO DE LESIÓN POR PRESIÓN:</strong><br><br>
          <strong>TIPO DE RIESGO:</strong> '.$rsHoja[0]['tipo_riesgo'].'. <br>
          <strong>PUNTAJE:</strong> '.$rsHoja[0]['puntaje_total'].'.
        </td>
      </tr>
    </tbody>
  </table>';
   }else if ($rsHoja[0]['tipobraden'] == 'P'){

  $html .= '
  <table class="textoNormal" >
    <tr>
      <td width="100%" style="text-align:center;"><strong><u>Escala de Braden para Paciente Pediátricos
Intensidad y duración de la presión</strong></u></td>
    </tr>
  </table>
  <table width="100%" class="box-table textoNormal" border="1">
    <thead>
      <tr>
        <th width="10%" align="center" ></th>
        <th width="30%" align="center" >MOVILIDAD</th>
        <th width="30%" align="center" >ACTIVIDAD</th>
        <th width="30%" align="center" >PERSEPCION SENSORIAL</th>
      </tr>
    </thead>
    <tbody style="text-align:center;" >
      <tr>
        <td width="10%" align="center" >1</td>
        <td width="30%" align="center" >Completamente Inmóvil  <strong>' . ($rsHoja[0]['movilidad_ped'] == "1" ? '<br> X' : '') . '</strong> </td>
        <td width="30%" align="center" >Encamado <strong>' . ($rsHoja[0]['actividad_ped'] == "1" ? '<br> X' : '') . '</strong> </td>
        <td width="30%" align="center" >Completamente limitada <strong>' . ($rsHoja[0]['Sensorial_ped'] == "1" ? '<br> X' : '') . '</strong> </td>
      </tr>
      <tr>
        <td align="center" >2</td>
        <td align="center" >Muy limitada <strong>' . ($rsHoja[0]['movilidad_ped'] == "2" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >En silla <strong>' . ($rsHoja[0]['actividad_ped'] == "2" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Muy limitada <strong>' . ($rsHoja[0]['Sensorial_ped'] == "2" ? '<br> X' : '') . '</strong> </td>
      </tr>
      <tr>
        <td align="center" >3</td>
        <td align="center" >Ligeramente limitada <strong>' . ($rsHoja[0]['movilidad_ped'] == "3" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Camina ocasionalmente <strong>' . ($rsHoja[0]['actividad_ped'] == "3" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Ligeramente limitada <strong>' . ($rsHoja[0]['Sensorial_ped'] == "3" ? '<br> X' : '') . '</strong> </td>
      </tr>
      <tr>
        <td align="center" >4</td>
        <td align="center" >Sin limitaciones <strong>' . ($rsHoja[0]['movilidad_ped'] == "4" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Camina frecuentemente <strong>' . ($rsHoja[0]['actividad_ped'] == "4" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Sin limitación <strong>' . ($rsHoja[0]['Sensorial_ped'] == "4" ? '<br> X' : '') . '</strong> </td>
      </tr>
      </table>
      <br>
      <br>
      <table class="textoNormal" >
        <tr>
          <td width="100%" style="text-align:center;"><strong><u> Tolerancia de la piel y estructura de soporte</strong></u></td>
        </tr>
      </table>
      <table width="100%" class="box-table textoNormal" border="1">
    <thead>
      <tr>
        <th width="10%" align="center" ></th>
        <th width="22.5%" align="center" >HUMEDAD</th>
        <th width="22.5%" align="center" >FRICCION</th>
        <th width="22.5%" align="center" >NUTRICION</th>
        <th width="22.5%" align="center" >PERFUSION TISULAR Y OXIGENACION</th>
      </tr>
    </thead>
    <tbody style="text-align:center;" >
      <tr>
        <td width="10%" align="center" >1</td>
        <td width="22.5%" align="center" >Piel constantemente húmeda  <strong>' . ($rsHoja[0]['humedad_ped'] == "1" ? '<br> X' : '') . '</strong> </td>
        <td width="22.5%" align="center" >Problema significativo <strong>' . ($rsHoja[0]['friccion_ped'] == "1" ? '<br> X' : '') . '</strong> </td>
        <td width="22.5%" align="center" >Muy pobre <strong>' . ($rsHoja[0]['nutricion_ped'] == "1" ? '<br> X' : '') . '</strong> </td>
        <td width="22.5%" align="center" >Muy comprometida <strong>' . ($rsHoja[0]['perfusion_ped'] == "1" ? '<br> X' : '') . '</strong> </td>
      </tr>
      <tr>
        <td align="center" >2</td>
        <td align="center" >Piel muy húmeda <strong>' . ($rsHoja[0]['humedad_ped'] == "2" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Problema <strong>' . ($rsHoja[0]['friccion_ped'] == "2" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Inadecuada <strong>' . ($rsHoja[0]['nutricion_ped'] == "2" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Comprometida <strong>' . ($rsHoja[0]['perfusion_ped'] == "2" ? '<br> X' : '') . '</strong> </td>
      </tr>
      <tr>
        <td align="center" >3</td>
        <td align="center" >Piel ocasionalmente húmeda <strong>' . ($rsHoja[0]['humedad_ped'] == "3" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Problema potencial <strong>' . ($rsHoja[0]['friccion_ped'] == "3" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Adecuada <strong>' . ($rsHoja[0]['nutricion_ped'] == "3" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Adecuada <strong>' . ($rsHoja[0]['perfusion_ped'] == "3" ? '<br> X' : '') . '</strong> </td>
      </tr>
      <tr>
        <td align="center" >4</td>
        <td align="center" >Piel raramente húmeda <strong>' . ($rsHoja[0]['humedad_ped'] == "4" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Sin problema aparente <strong>' . ($rsHoja[0]['friccion_ped'] == "4" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Excelente <strong>' . ($rsHoja[0]['nutricion_ped'] == "4" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Excelente <strong>' . ($rsHoja[0]['perfusion_ped'] == "4" ? '<br> X' : '') . '</strong> </td>
      </tr>
      </table>
      <table width="100%" class="box-table textoNormal" border="1">
      <tr>
        <td colspan="3" style="text-align:left">
          <strong>RIESGO DE LPP BRADEN – BERGSTROM:</strong><br><br>
          <strong>ALTO RIESGO:</strong> Menor o igual a 12 puntos<br>
          <strong>RIESGO MODERADO:</strong> 13 a 14 puntos<br>
          <strong>BAJO RIESGO:</strong> Mayor o igual a 15 puntos<br>
          <strong>SIN RIESGO:</strong> Mayor a 18 puntos
        </td>
        <td colspan="4" style="text-align:left">
          <strong>VALORACIÓN DEL RIESGO DE LESIÓN POR PRESIÓN:</strong><br><br>
          <strong>TIPO DE RIESGO:</strong> '.$rsHoja[0]['tipo_riesgo'].'. <br>
          <strong>PUNTAJE:</strong> '.$rsHoja[0]['puntaje_total'].'.
        </td>
      </tr>
    </tbody>
  </table>';
  }else{
  $html .= '
  <table class="textoNormal" >
    <tr>
      <td width="100%" style="text-align:center;"><strong><u>ESCALA DE BRADEN PARA PACIENTE ADULTO</strong></u></td>
    </tr>
  </table>
  <table width="100%" class="box-table textoNormal" border="1">
	  <thead>
	    <tr>
	      <th width="10%" align="center" ></th>
	      <th width="15%" align="center" >PERCEPCIÓN SENSORIAL</th>
	      <th width="15%" align="center" >EXPOSICIÓN A LA HUMEDAD</th>
	      <th width="15%" align="center" >ACTIVIDAD</th>
	      <th width="15%" align="center" >MOVILIDAD</th>
	      <th width="15%" align="center" >NUTRICIÓN</th>
	      <th width="15%" align="center" >RIESGO DE LESIONES CUTÁNEAS</th>
	    </tr>
	  </thead>
    <tbody style="text-align:center;" >
      <tr>
        <td width="10%" align="center" >1</td>
        <td width="15%" align="center" >Completamente limitada  <strong>' . ($rsHoja[0]['sensorial'] == "1" ? '<br> X' : '') . '</strong> </td>
        <td width="15%" align="center" >Constantemente húmeda <strong>' . ($rsHoja[0]['humedad'] == "1" ? '<br> X' : '') . '</strong> </td>
        <td width="15%" align="center" >Encamado <strong>' . ($rsHoja[0]['actividad'] == "1" ? '<br> X' : '') . '</strong> </td>
        <td width="15%" align="center" >Completamente inmóvil <strong>' . ($rsHoja[0]['movilidad'] == "1" ? '<br> X' : '') . '</strong> </td>
        <td width="15%" align="center" >Muy Pobre <strong>' . ($rsHoja[0]['nutricion'] == "1" ? '<br> X' : '') . '</strong> </td>
        <td width="15%" align="center" >Problema <strong>' . ($rsHoja[0]['lesion'] == "1" ? '<br> X' : '') . '</strong> </td>
      </tr>
      <tr>
        <td align="center" >2</td>
        <td align="center" >Muy limitada <strong>' . ($rsHoja[0]['sensorial'] == "2" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Húmeda con frecuencia <strong>' . ($rsHoja[0]['humedad'] == "2" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >En silla <strong>' . ($rsHoja[0]['actividad'] == "2" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Muy limitada <strong>' . ($rsHoja[0]['movilidad'] == "2" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Probablemente Inadecuada <strong>' . ($rsHoja[0]['nutricion'] == "2" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Problema potencial <strong>' . ($rsHoja[0]['lesion'] == "2" ? '<br> X' : '') . '</strong> </td>
      </tr>
      <tr>
        <td align="center" >3</td>
        <td align="center" >Ligeramente limitada <strong>' . ($rsHoja[0]['sensorial'] == "3" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Ocasionalmente húmeda <strong>' . ($rsHoja[0]['humedad'] == "3" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Deambula ocasionalmente <strong>' . ($rsHoja[0]['actividad'] == "3" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Ligeramente limitada <strong>' . ($rsHoja[0]['movilidad'] == "3" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Adecuada <strong>' . ($rsHoja[0]['nutricion'] == "3" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >No existe problema aparente <strong>' . ($rsHoja[0]['lesion'] == "3" ? '<br> X' : '') . '</strong> </td>
      </tr>
      <tr>
        <td align="center" >4</td>
        <td align="center" >Sin Limitaciones <strong>' . ($rsHoja[0]['sensorial'] == "4" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Raramente húmeda <strong>' . ($rsHoja[0]['humedad'] == "4" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Deambula frecuentemente <strong>' . ($rsHoja[0]['actividad'] == "4" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Sin Limitación <strong>' . ($rsHoja[0]['movilidad'] == "4" ? '<br> X' : '') . '</strong> </td>
        <td align="center" >Excelente <strong>' . ($rsHoja[0]['nutricion'] == "4" ? '<br> X' : '') . '</strong> </td>
        <td align="center" ></td>
      </tr>
      <tr>
        <td colspan="3" style="text-align:left">
          <strong>RIESGO DE LPP BRADEN – BERGSTROM:</strong><br><br>
          <strong>ALTO RIESGO:</strong> Menor o igual a 12 puntos<br>
          <strong>RIESGO MODERADO:</strong> 13 a 14 puntos<br>
          <strong>BAJO RIESGO:</strong> Mayor o igual a 15 puntos<br>
          <strong>SIN RIESGO:</strong> Mayor a 18 puntos
        </td>
        <td colspan="4" style="text-align:left">
          <strong>VALORACIÓN DEL RIESGO DE LESIÓN POR PRESIÓN:</strong><br><br>
          <strong>TIPO DE RIESGO:</strong> '.$rsHoja[0]['tipo_riesgo'].'. <br>
          <strong>PUNTAJE:</strong> '.$rsHoja[0]['puntaje_total'].'.
        </td>
      </tr>
    </tbody>
	  
	</table>
  ';
  }
  $html .= '
  <br>
  <div class="textoGrande"><strong><u>SIGNOS VITALES : </strong></u></div>


	<table  cellspacing="4" border="0" width="100%" class="">
		<tr>
			<td width="100%">
			
				<table cellspacing="0" border="0" width="100%" class="textoNormal">
					';

					if ( is_null($listarSignos[0]['SVITALfecha']) || empty($listarSignos[0]['SVITALfecha']) ) {
						$html .= '	<tr>
										<td width="100%"></td>
									</tr>';
					} else {

						$html .= '

						<tr>
							<td width="12%" align="center"><strong style="" >Usuario y Fecha</strong></td>
							<td  align="center"><strong>PAS / PAD</strong></td>
              <td  align="center"><strong>FC</strong></td>
							<td  align="center"><strong>PAM</strong></td>
							<td  align="center"><strong>SAT</strong></td>
							<td   align="center"><strong>FIO2</strong></td>
							<td   align="center"><strong>FR</strong></td>
							<td   align="center"><strong>HGT</strong></td>';
							if($datosPaciente[0]['dau_atencion'] == 3){ 
							$html .= '<td   align="center"><strong>LCF</strong></td>
							<td   align="center"><strong>RBNE</strong></td>';
					 		} 
			$html 		.= '<td   align="center"><strong>GCS</strong></td>
							<td   align="center"><strong>T°</strong></td>
							<td   align="center"><strong>EVA</strong></td>
						</tr>';

						for($i=0;$i< 3;$i++){ 

              if ( $listarSignos[$i]['SVITALusuario'] != "" ){ 
							$html .= 
						'<tr>';
							$html.= '
							<td style="vertical-align: middle;" align="center">'.$listarSignos[$i]['SVITALusuario'].'<br>'.date("d-m-Y H:i",strtotime($listarSignos[$i]['SVITALfecha'])).'</td>';
							$html .= '
							<td style="vertical-align: middle;" align="center">'.$listarSignos[$i]['SVITALsistolica'].' / '.$listarSignos[$i]['SVITALdiastolica'].'</td>
              <td style="vertical-align: middle;" align="center">'.$listarSignos[$i]['SVITALpulso'].'</td>
							<td style="vertical-align: middle;" align="center">'.$listarSignos[$i]['SVITALPAM'].'</td>
							<td style="vertical-align: middle;" align="center">'.$listarSignos[$i]['SVITALsaturacion'].'</td>
							<td style="vertical-align: middle;" align="center">'.$listarSignos[$i]['FIO2'].'</td>
							<td style="vertical-align: middle;" align="center">'.$listarSignos[$i]['SVITALfr'].'</td>
							<td style="vertical-align: middle;" align="center">'.$listarSignos[$i]['SVITALHemoglucoTest'].'</td>
								';
							if($datosPaciente[0]['dau_atencion'] == 3){ 
								$html .=   
							'<td style="vertical-align: middle;" align="center">'.$listarSignos[$i]['SVITALfeto'].'</td>
							<td style="vertical-align: middle;" align="center">'.$listarSignos[$i]['SVITALrbne'].'</td>';
					 		} 
							$html 		.= '
							<td style="vertical-align: middle;" align="center">'.$listarSignos[$i]['SVITALglasgow'].'</td>
							<td style="vertical-align: middle;" align="center">'.$listarSignos[$i]['SVITALtemperatura'].'</td>
							<td style="vertical-align: middle;" align="center">'.$listarSignos[$i]['SVITALeva'].'</td>
						</tr>';
						}
					}
        }
					$html .= '
				</table>
			</td>
		</tr>
	</table>

  <br>
  <hr>
  <br>
	<table class="textoNormal" cellspacing="1"  width="100%">
		<tr>
		  <td width="100%" >
			<table class="textoNormal" cellspacing="1" >
				<tr>
				<td  ><strong>CONTENCIÓN FÍSICA</strong></td><td  >:&nbsp;&nbsp; SI ( <strong>' . ($rsHoja[0]['frm_contencion_fisica'] == "Sí" ? 'X' : '  ') . '</strong> )</td><td  >NO ( <strong>' . ($rsHoja[0]['frm_contencion_fisica'] == "No" ? 'X' : '  ') . '</strong> )</td>
				</tr>
				<tr>
				<td  >EXT. SUPERIORES ( <strong>' . ($rsHoja[0]['frm_ext_superiores'] == "Sí" ? 'X' : '  ') . '</strong> )</td><td  >EXT. INFERIORES ( <strong>' . ($rsHoja[0]['frm_ext_inferiores'] == "Sí" ? 'X' : '  ') . '</strong> )</td>
				</tr>
				<tr>
				<td  ><strong>HOJA DE CONTENCIÓN</strong></td><td  >:&nbsp;&nbsp; SI ( <strong>' . ($rsHoja[0]['frm_hoja_contencion'] == "Sí" ? 'X' : '  ') . '</strong> )</td><td  >NO ( <strong>' . ($rsHoja[0]['frm_hoja_contencion'] == "No" ? 'X' : '  ') . '</strong> )</td>
				</tr>
				<tr>
				<td colspan="3" >'.$rsHoja[0]['obs_hoja_contencion'].'</td>
				</tr>
				<tr>
        <td colspan="3" >
        <div class="textoGrande"><strong><u>EXAMENES REALIZADOS E INTERCONSULTAS:</strong></u></div>
        <br>
        </td>
				</tr>
        <tr>
        <td colspan="3">';
        foreach ($rsExamenesRealizados as $examen) { 
          $html .= "- [".date('d-m-Y H:i', strtotime($examen['fechaInserta']))."]  ".htmlspecialchars($examen['nombreUsuario']) ." : ".htmlspecialchars($examen['descripcion'])." - ".htmlspecialchars($examen['Prestacion'])."<br>";
          if($examen['fechaTomaMuestra'] != null ){
            $parametrosEnfermeria['rce_sol_id'] = $examen['sol_id'];
            $parametrosEnfermeria['dau_id']     = $parametros['dau_id'];
            $rsDauMovimientoEnfermeria = $objBitacora->SelectDauMovimientoEnfermeria($objCon, $parametrosEnfermeria);
            if(count ($rsDauMovimientoEnfermeria) > 0){
              foreach ($rsDauMovimientoEnfermeria as $movimiento) {
               $html .= '<strong class="">Observación Enfermeria ('.htmlspecialchars($movimiento['estado_solicitud']) .') :</strong> '. htmlspecialchars($movimiento['observacion']) .'<br>'; 
              }
            }
          }

        }
      $html .= '
      </td>
				</tr>
				<tr>
				<td colspan="3" >
        <div class="textoGrande"><strong><u>OBSERVACIONES Y/O TRATAMIENTOS EFECTUADOS EN BOX:</strong></u></div>
        <br>
        </td>
				</tr>
				<tr>
				<td colspan="3" >';
        foreach ($rsTratamientosRealizados as $tratamiento) { 
          $html .= "- [".date('d-m-Y H:i', strtotime($tratamiento['fechaInserta']))."]  ".htmlspecialchars($tratamiento['nombreUsuario']) ." : SOLICITUD ".htmlspecialchars($tratamiento['descripcion'])." - ".htmlspecialchars($tratamiento['Prestacion'])."<br>";
          if($tratamiento['fechaIniciaIndicacion'] != null ){
            $parametrosEnfermeria['rce_sol_id'] = $tratamiento['sol_id'];
            $parametrosEnfermeria['dau_id']     = $parametros['dau_id'];
            $rsDauMovimientoEnfermeria = $objBitacora->SelectDauMovimientoEnfermeria($objCon, $parametrosEnfermeria);
            if(count ($rsDauMovimientoEnfermeria) > 0){
              foreach ($rsDauMovimientoEnfermeria as $movimiento) {
               $html .= '<strong class="">Observación Enfermeria ('.htmlspecialchars($movimiento['estado_solicitud']) .') :</strong> '. htmlspecialchars($movimiento['observacion']) .'<br>'; 
              }
            }
          }

        }
        $html .='</td>
				</tr>
				
				<tr>
        <td colspan="3" >
        <div class="textoGrande"><strong><u>ELEMENTOS INVASIVOS:</strong></u></div>
        <br>
        </td>
				</tr>
				<tr>
        <td colspan="3" >';
        foreach ($rsProcedimientosRealizados as $Procedimientos) { 
          $html .= "- [".date('d-m-Y', strtotime($Procedimientos['fecha']))." ".$Procedimientos['hora']."]  ".htmlspecialchars($Procedimientos['nombreUsuario']) ." : SOLICITUD ".htmlspecialchars($Procedimientos['nombre_procedimiento'])." - ".htmlspecialchars($Procedimientos['nombre_subProcedimiento'])."<br>Observación : ". $Procedimientos['comentario']."<br>";
        }
        $html .='</td>
				</tr>
				<tr>
				<td colspan="3" ></td>
				</tr>
				<tr>
				<td colspan="3" ><strong>PERTENENCIAS:</strong></td>
				</tr>

				<tr>
				<td width="60%" >VALOR (Inventario en recaudación)</td><td width="20%" >:&nbsp;&nbsp; SI ( <strong>' . ($rsHoja[0]['frm_valor_recaudacion'] == "Sí" ? 'X' : '  ') . '</strong> )</td><td width="20%" > NO ( <strong>' . ($rsHoja[0]['frm_valor_recaudacion'] == "No" ? 'X' : '  ') . '</strong> )</td>
				</tr>
				<tr>
				<td width="60%" >N° INVENTARIO</td><td width="20%" >:&nbsp;&nbsp; '.htmlspecialchars($rsHoja[0]['frm_num_inventario']).' </td>
				</tr>
				<tr>
				<td width="60%" >ARTICULOS PERSONALES (Sube a piso)</td><td width="20%" >:&nbsp;&nbsp; SI ( <strong>' . ($rsHoja[0]['frm_articulos_personales'] == "Sí" ? 'X' : '  ') . '</strong> )</td><td width="20%" > NO ( <strong>' . ($rsHoja[0]['frm_articulos_personales'] == "No" ? 'X' : '  ') . '</strong> )</td>
				</tr>
				<tr>
				<td width="60%" >CUSTODIA CR EMERGENCIA (Uci-Sai-Pabellón)</td><td width="20%" >:&nbsp;&nbsp; SI ( <strong>' . ($rsHoja[0]['frm_custodia_cr'] == "Sí" ? 'X' : '  ') . '</strong> )</td><td width="20%" > NO ( <strong>' . ($rsHoja[0]['frm_custodia_cr'] == "No" ? 'X' : '  ') . '</strong> )</td>
				</tr>
				<tr>
				<td colspan="3" >'.htmlspecialchars($rsHoja[0]['obs_custodia_cr']).'</td>
				</tr>
			</table>
		  </td>
		 
		</tr>
	</table><br><br>
  <table border="1" class="textoNormal">
    <tr> 
      <td>
        <table>
          <tr style="text-align:center;">
            <td colspan="2" class="textoGrande"><strong><u>G L A S G O W</u></strong></td>
          </tr>
          
          <tr><td colspan="2"><strong><u>APERTURA DE OJOS :</u></strong></td></tr>
          <tr><td width="80%">&nbsp;'.strtoupper($rsGlasgow['ojos'][4]).'</td><td width="20%">:&nbsp;&nbsp; '.($ojos == 4 ? '<del>( 4 )</del>' : '( 4 )').'</td></tr>
          <tr><td width="80%">&nbsp;'.strtoupper($rsGlasgow['ojos'][3]).'</td><td width="20%">:&nbsp;&nbsp; '.($ojos == 3 ? '<del>( 3 )</del>' : '( 3 )').'</td></tr>
          <tr><td width="80%">&nbsp;'.strtoupper($rsGlasgow['ojos'][2]).'</td><td width="20%">:&nbsp;&nbsp; '.($ojos == 2 ? '<del>( 2 )</del>' : '( 2 )').'</td></tr>
          <tr><td width="80%">&nbsp;'.strtoupper($rsGlasgow['ojos'][1]).'</td><td width="20%">:&nbsp;&nbsp; '.($ojos == 1 ? '<del>( 1 )</del>' : '( 1 )').'</td></tr>
          
          <tr><td colspan="2">&nbsp;</td></tr>
          <tr><td colspan="2"><strong><u>RESPUESTA VERBAL:</u></strong></td></tr>
          <tr><td width="80%">&nbsp;'.strtoupper($rsGlasgow['verbal'][5]).'</td><td width="20%">:&nbsp;&nbsp; '.($verbal == 5 ? '<del>( 5 )</del>' : '( 5 )').'</td></tr>
          <tr><td width="80%">&nbsp;'.strtoupper($rsGlasgow['verbal'][4]).'</td><td width="20%">:&nbsp;&nbsp; '.($verbal == 4 ? '<del>( 4 )</del>' : '( 4 )').'</td></tr>
          <tr><td width="80%">&nbsp;'.strtoupper($rsGlasgow['verbal'][3]).'</td><td width="20%">:&nbsp;&nbsp; '.($verbal == 3 ? '<del>( 3 )</del>' : '( 3 )').'</td></tr>
          <tr><td width="80%">&nbsp;'.strtoupper($rsGlasgow['verbal'][2]).'</td><td width="20%">:&nbsp;&nbsp; '.($verbal == 2 ? '<del>( 2 )</del>' : '( 2 )').'</td></tr>
          <tr><td width="80%">&nbsp;'.strtoupper($rsGlasgow['verbal'][1]).'</td><td width="20%">:&nbsp;&nbsp; '.($verbal == 1 ? '<del>( 1 )</del>' : '( 1 )').'</td></tr>
          
          <tr><td colspan="2">&nbsp;</td></tr>
          <tr><td colspan="2"><strong><u>RESPUESTA MOTORA:</u></strong></td></tr>
          <tr><td width="80%">&nbsp;'.strtoupper($rsGlasgow['motora'][6]).'</td><td width="20%">:&nbsp;&nbsp; '.($motora == 6 ? '<del>( 6 )</del>' : '( 6 )').'</td></tr>
          <tr><td width="80%">&nbsp;'.strtoupper($rsGlasgow['motora'][5]).'</td><td width="20%">:&nbsp;&nbsp; '.($motora == 5 ? '<del>( 5 )</del>' : '( 5 )').'</td></tr>
          <tr><td width="80%">&nbsp;'.strtoupper($rsGlasgow['motora'][4]).'</td><td width="20%">:&nbsp;&nbsp; '.($motora == 4 ? '<del>( 4 )</del>' : '( 4 )').'</td></tr>
          <tr><td width="80%">&nbsp;'.strtoupper($rsGlasgow['motora'][3]).'</td><td width="20%">:&nbsp;&nbsp; '.($motora == 3 ? '<del>( 3 )</del>' : '( 3 )').'</td></tr>
          <tr><td width="80%">&nbsp;'.strtoupper($rsGlasgow['motora'][2]).'</td><td width="20%">:&nbsp;&nbsp; '.($motora == 2 ? '<del>( 2 )</del>' : '( 2 )').'</td></tr>
          <tr><td width="80%">&nbsp;'.strtoupper($rsGlasgow['motora'][1]).'</td><td width="20%">:&nbsp;&nbsp; '.($motora == 1 ? '<del>( 1 )</del>' : '( 1 )').'</td></tr>

          <tr><td colspan="2">&nbsp;</td></tr>
          <tr><td width="80%">&nbsp;TOTAL</td><td width="20%">:&nbsp;&nbsp; '.$rsHoja[0]['totalGlasgow'].' PTS.</td></tr>
        </table>
        </td>
        <td>
        <table class="" >
         <tr style="text-align:center;">
            <td colspan="2" class="textoGrande"><strong><u>ARTICULOS PERSONALES</u></strong></td>
          </tr>
          <tr>
            <td colspan="2"><strong><u>UTILES DE ASEO :</u></strong></td>
          </tr>
          <tr>
          <td width="60%" >&nbsp;JABÓN</td><td width="40%" >:&nbsp;&nbsp; ( <strong>' . ($rsHoja[0]['jabon'] == "Sí" ? 'X' : '  ') . '</strong> )</td>
          </tr>
          <tr>
          <td width="60%" >&nbsp;SHAMPOO</td><td width="40%" >:&nbsp;&nbsp; ( <strong>' . ($rsHoja[0]['shampoo'] == "Sí" ? 'X' : '  ') . '</strong> )</td>
          </tr>
          <tr>
          <td width="60%" >&nbsp;PASTA DENTAL</td><td width="40%" >:&nbsp;&nbsp; ( <strong>' . ($rsHoja[0]['pasta'] == "Sí" ? 'X' : '  ') . '</strong> )</td>
          </tr>
          <tr>
          <td width="60%" >&nbsp;DESODORANTE</td><td width="40%" >:&nbsp;&nbsp; ( <strong>' . ($rsHoja[0]['desodorante'] == "Sí" ? 'X' : '  ') . '</strong> )</td>
          </tr>
          <tr>
          <td width="60%" >&nbsp;CONFORT</td><td width="40%" >:&nbsp;&nbsp; ( <strong>' . ($rsHoja[0]['confort'] == "Sí" ? 'X' : '  ') . '</strong> )</td>
          </tr>
          <tr>
          <td width="60%" >&nbsp;PAÑAL</td><td width="40%" >:&nbsp;&nbsp; ( <strong>' . ($rsHoja[0]['pañal'] == "Sí" ? 'X' : '  ') . '</strong> )</td>
          </tr>
          <tr>
          <td width="60%" >&nbsp;OTRO</td><td width="40%" >:&nbsp;&nbsp;'.$rsHoja[0]['frm_otro_util_aseo'].'.</td>
          </tr>
          <tr>
            <td colspan="2"></td>
          </tr>
          <tr>
            <td colspan="2"><strong><u>VESTUARIO :</u></strong></td>
          </tr>
          <tr>
          <td width="60%" >&nbsp;PIJAMA</td><td width="40%" >:&nbsp;&nbsp; ( <strong>' . ($rsHoja[0]['pijama'] == "Sí" ? 'X' : '  ') . '</strong> )</td>
          </tr>
          <tr>
          <td width="60%" >&nbsp;PANTUFLAS</td><td width="40%" >:&nbsp;&nbsp; ( <strong>' . ($rsHoja[0]['pantuflas'] == "Sí" ? 'X' : '  ') . '</strong> )</td>
          </tr>
          <tr>
          <td width="60%" >&nbsp;POLERA</td><td width="40%" >:&nbsp;&nbsp; ( <strong>' . ($rsHoja[0]['polera'] == "Sí" ? 'X' : '  ') . '</strong> )</td>
          </tr>
          <tr>
          <td width="60%" >&nbsp;POLERON</td><td width="40%" >:&nbsp;&nbsp; ( <strong>' . ($rsHoja[0]['poleron'] == "Sí" ? 'X' : '  ') . '</strong> )</td>
          </tr>
          <tr>
          <td width="60%" >&nbsp;PANTALON</td><td width="40%" >:&nbsp;&nbsp; ( <strong>' . ($rsHoja[0]['pantalon'] == "Sí" ? 'X' : '  ') . '</strong> )</td>
          </tr>
          <tr>
          <td width="60%" >&nbsp;OTRO</td><td width="40%" >:&nbsp;&nbsp;'.$rsHoja[0]['frm_otra_prenda'].'.</td>
          </tr>
          <tr>
            <td colspan="2"></td>
          </tr>
          <tr>
            <td colspan="2"><strong><u>ROPA DE CAMA:</u></strong></td>
          </tr>
          <tr>
          <td width="60%" >&nbsp;ALMOHADA</td><td width="40%" >:&nbsp;&nbsp; ( <strong>' . ($rsHoja[0]['almohada'] == "Sí" ? 'X' : '  ') . '</strong> )</td>
          </tr>
          <tr>
          <td width="60%" >&nbsp;FRAZADA</td><td width="40%" >:&nbsp;&nbsp; ( <strong>' . ($rsHoja[0]['frazada'] == "Sí" ? 'X' : '  ') . '</strong> )</td>
          </tr>
          <tr>
          <td width="60%" >&nbsp;SÁBANA</td><td width="40%" >:&nbsp;&nbsp; ( <strong>' . ($rsHoja[0]['sabana'] == "Sí" ? 'X' : '  ') . '</strong> )</td>
          </tr>
          <tr>
          <td width="60%" >&nbsp;OTRO</td><td width="40%" >:&nbsp;&nbsp;'.$rsHoja[0]['frm_otra_ropa_cama'].'.</td>
          </tr>
          <tr>
            <td colspan="2"></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br>
  <br>
  <table class="box-table textoNormal" >
    <tr>
      <td ><strong>OBSERVACION ENFERMERIA </strong></td>
    </tr>
    <tr>
      <td style="text-align: justify;" >'.htmlspecialchars($rsHoja[0]['obs_enfermeria']).'.</td>
    </tr>
  </table>
  <br><br><br>
	<table class="textoNormal" cellspacing="1">
		<tr style="text-align:center;" >
		  <td width="45%" ><strong>'.$_SESSION['MM_UsernameName'.SessionName].'<br>'.$_SESSION['MM_RUNUSU'.SessionName]."-".$objUtil->generaDigito($_SESSION['MM_RUNUSU'.SessionName]).'</strong></td>
		  <td width="25%" ></td>
		</tr>
		<tr style="text-align:center;" >
		  <td width="45%" >ENFERMERA/O DE TURNO UEH</td>
		  <td width="25%" ></td>
		</tr>
	</table>
	<br>
	<br>
	<table class="textoNormal" cellspacing="1">
		<tr  >
		  <td width="40%" >( <strong>' . ($rsHoja[0]['frm_via_telefonica'] == "Sí" ? 'X' : '  ') . '</strong> ) VÍA TELEFÓNICA</td>
      <td  style="vertical-align: middle;" align="center" rowspan="2" width="35%" >ENTREGA EU<BR>( <strong>' . $rsHoja[0]['nombre_enfermero'] . '</strong> ) </td>
      <td  style="vertical-align: middle;" align="center" rowspan="2" width="25%" >FECHA Y HORA<BR>[<strong>' . $fecha_entrega . ' ' . $rsHoja[0]['hora_entrega'] . '</strong>] </td>
		</tr>
		<tr  >
		  <td width="100%" >( <strong>' . ($rsHoja[0]['frm_via_presencial'] == "Sí" ? 'X' : '  ') . '</strong> ) VÍA PRESENCIAL</td>
		</tr>
	</table>

	';


// $html = " holas";
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
$nombre_archivo = 'hojaHospitalizacionEnfermeria.pdf';
$pdf->Output(__DIR__ . '/' . $nombre_archivo, 'FI');
$url = "/RCEDAU/views/modules/enfermera/hojaHospitalizacionEnfermeria.pdf";
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
