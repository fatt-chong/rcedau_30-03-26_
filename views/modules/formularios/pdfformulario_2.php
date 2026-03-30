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

require_once('../../../../estandar/TCPDF-main/tcpdf.php');
require("../../../config/config.php");
require_once('../../../class/Connection.class.php');               $objCon                       = new Connection; $objCon->db_connect();
require_once('../../../class/Util.class.php');                     $objUtil                      = new Util;
require_once('../../../class/Admision.class.php');                 $objAdmision                  = new Admision;
require_once('../../../class/RegistroClinico.class.php');    $objRegistroClinico         = new RegistroClinico;
require_once("../../../class/Dau.class.php" );               $objDau                 = new Dau;
require_once('../../../class/Rce.class.php');                $objRce                 = new Rce;
require_once('../../../class/HojaEnfermeria.class.php');     $objHoja_enfermeria     = new Hoja_enfermeria;
require_once('../../../class/Categorizacion.class.php');     $objCategorizacion      = new Categorizacion;
require_once('../../../class/Diagnosticos.class.php');       $objDiagnosticos        = new Diagnosticos;
require_once('../../../class/Formulario_2.class.php');       $objFormulario_2        = new Formulario_2;
require_once('../../../class/Formulario_2_Detalle.class.php'); $objFormulario_2_Detalle = new Formulario_2_Detalle;


$parametros                     = $objUtil->getFormulario($_POST);
$dau_id                         = $_POST['dau_id'];
$datosU                         = $objCategorizacion->searchPaciente($objCon, $parametros['dau_id']);
$horarioServidor                = $objUtil->getHorarioServidor($objCon);
$datosDAUPaciente               = $objDau->buscarListaPaciente($objCon,$parametros);
$rsRce                          = $objRegistroClinico->consultaRCE($objCon,$parametros);
$parametros['cta_cte']          = $datosU[0]['idctacte'];
$rsRce_diagnostico              = $objDiagnosticos->obtenerRce_diagnosticoCompartido($objCon,$parametros);
$rsFormulario2                  = $objFormulario_2->SelectByDauFormulario_2($objCon,$dau_id);
$rsFormulario2Detalle           = $objFormulario_2_Detalle->SelectByFormulario2Id($objCon,$rsFormulario2[0]['id']);

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
$pdf->SetTitle('Registro Contención Física para Agitación Psicomotora');
$pdf->SetSubject('Formularios');
$pdf->SetKeywords('RCE, Formularios');
$pdf->setHeaderFont(Array('helvetica', '', 9));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(10, 3, 10);
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setLanguageArray($l);
$pdf->setFontSubsetting(false);
$pdf->SetFont('helvetica', '', 10, '', true);

// add a page
$pdf->AddPage();
// Procesar registros horarios para crear la tabla
$tablaHorarios = '';
if ($rsFormulario2Detalle && count($rsFormulario2Detalle) > 0) {
    $tablaHorarios = '<table cellpadding="2" border="1" style="border-collapse: collapse; width: 100%;" class="textoNormal">
        <tr style="background-color: #f0f0f0; font-weight: bold;">
            <th width="20%" style="border: 1px solid black; padding: 4px;" align="center" >DESCRIPCIÓN</th>';
    for ($i = 0; $i < 8; $i++) {
        $horaTexto = "HORA " . ($i + 1);
        $tablaHorarios .= '<th width="10%" style="border: 1px solid black; padding: 4px;" align="center">' . $horaTexto . '</th>';
    }

    $tablaHorarios .= '</tr>';
    $tablaHorarios .= '<tr>
        <td style="border: 1px solid black; padding: 4px;">+</td>';
    for ($i = 0; $i < 8; $i++) {
        if($rsFormulario2Detalle[$i]['fecha'] != ""){
            $horaTexto = date('d-m-y H:i', strtotime($rsFormulario2Detalle[$i]['fecha']));
        }else{
            $horaTexto = "";
        }
        $tablaHorarios .= '<td style="border: 1px solid black; padding: 4px;" align="center" >' . $horaTexto . '</td>';
    }
    $tablaHorarios .= '</tr>';
    $tablaHorarios .= '<tr style="background-color: #e0e0e0; font-weight: bold;">
        <td  style="border: 1px solid black; padding: 4px;" align="center" >ESTADO DEL PACIENTE</td>
    </tr>';

    $estadosPaciente = array('TRANQUILO', 'INQUIETO', 'AGITADO');
    foreach ($estadosPaciente as $estado) {
        $tablaHorarios .= '<tr>';
        $tablaHorarios .= '<td style="border: 1px solid black; padding: 4px;" align="center" >' . $estado . '</td>';
        for ($i = 0; $i < 8; $i++) {
            $valor = ($rsFormulario2Detalle[$i]['estado_paciente'] == $estado) ? '<b>X</b>' : '';
            $tablaHorarios .= '<td style="border: 1px solid black; padding: 4px;" align="center" >' . $valor . '</td>';
        }
        $tablaHorarios .= '</tr>';
    }

    $tablaHorarios .= '<tr style="background-color: #e0e0e0; font-weight: bold;">
        <td  style="border: 1px solid black; padding: 4px;" align="center" >REVISIÓN SUJECIONES</td>
    </tr>';

    $revisionesSujeciones = array('EXT. SUPERIOR' => 'extremidad_superior', 'EXT. INFERIOR' => 'extremidad_inferior');
    foreach ($revisionesSujeciones as $label => $campo) {
        $tablaHorarios .= '<tr>';
        $tablaHorarios .= '<td style="border: 1px solid black; padding: 4px;" align="center" >' . $label . '</td>';
        for ($i = 0; $i < 8; $i++) {
            $tablaHorarios .= '<td style="border: 1px solid black; padding: 4px;" align="center" ><b>'.$rsFormulario2Detalle[$i][$campo].'</b></td>';
        }
        $tablaHorarios .= '</tr>';
    }

    $tablaHorarios .= '<tr style="background-color: #e0e0e0; font-weight: bold;">
        <td  style="border: 1px solid black; padding: 4px;" align="center" >NECESIDADES BÁSICAS</td>
    </tr>';

    $necesidadesBasicas = array(
        'HIDRATACIÓN Y ALIMENTACIÓN (ACEPTA)' => 'hidratacion',
        'ELIMINACIÓN URINARIA EN CAMA (ACEPTA)' => 'eliminacion'
    );
    foreach ($necesidadesBasicas as $label => $campo) {
        $tablaHorarios .= '<tr>';
        $tablaHorarios .= '<td style="border: 1px solid black; padding: 4px;" align="center" >' . $label . '</td>';
        for ($i = 0; $i < 8; $i++) {
            $tablaHorarios .= '<td style="border: 1px solid black; padding: 4px;" align="center" ><b>'.$rsFormulario2Detalle[$i][$campo].'</b></td>';
        }
        $tablaHorarios .= '</tr>';
    }
    $tablaHorarios .= '<tr>
    <td  style="border: 1px solid black; padding: 4px;" align="center" ><b>USUARIO</b></td>';
for ($i = 0; $i < 8; $i++) {
    $tablaHorarios .= '<td style="border: 1px solid black; padding: 4px;" align="center" >' . $rsFormulario2Detalle[$i]['usuario'] . '</td>';
}
$tablaHorarios .= '</tr>';
    $tablaHorarios .= '</table>';
}


$html = '<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro Contención Física</title>
  <style>
    .textoPequeño{
      font-size: 9px !important;
    }
    .textoNormal{
      font-size: 10.4px !important;
    }
    .textoGrande{
      font-size: 12px !important;
    }
    .textoGigante{
      font-size: 16px !important;
    }
    .checkbox {
      display: inline-block;
      margin-right: 10px;
    }
    .borde-inferior {
        border-bottom: 1px solid #000;
        padding-bottom: 2px;
    }
  </style>
</head>
<body>
<table style="width: 100%;">
  <tr>
    <td width="20%" style="vertical-align: top;">
      <img src="/estandar/img/logo_gobierno_chile.jpg" width="95" height="95">
    </td>
    <td width="60%" style="vertical-align: top;">
      <p class="textoGigante" align="center"><b>REGISTRO<br><br>CONTENCIÓN FÍSICA PARA AGITACIÓN<br>PSICOMOTORA EN URGENCIA</b></p>
    </td>
    <td width="20%" style="vertical-align: top;">
      <p class="textoNormal">SGC HJNC GSM R N°008<br>Versión 1</p>
    </td>
  </tr>
</table>

<hr>
&nbsp; <br>
<table cellpadding="2">
    <tr class="textoNormal" >
      <td width="15%">NOMBRE &nbsp;:&nbsp;</td>
      <td width="55%" class="highlight-blue borde-inferior" colspan="2" >&nbsp; '.$rsFormulario2[0]['nombre_paciente'].'</td>
      <td width="10%" >&nbsp;&nbsp;FECHA&nbsp;:&nbsp;</td>
      <td width="20%" class="highlight-blue borde-inferior" colspan="2" >&nbsp; '.date("d-m-Y", strtotime($rsFormulario2[0]['fecha'])).'</td>
    </tr>
    <tr>
    <td>&nbsp;</td></tr>
    <tr class="textoNormal" >
      <td width="15%">FICHA &nbsp;:&nbsp;</td>
      <td width="55%" class="highlight-blue borde-inferior" colspan="2" >&nbsp; '.$rsFormulario2[0]['ficha_numero'].'</td>
      <td width="10%" >&nbsp;&nbsp;HORA&nbsp;:&nbsp;</td>
      <td width="20%" class="highlight-blue borde-inferior" colspan="2" >&nbsp; '.$rsFormulario2[0]['hora'].'</td>
    </tr>
  </table>



<h6 class="textoGrande"><b>MOTIVO DE CONTENCIÓN</b></h6>

<table cellpadding="4" >
    <tr class="textoNormal" >
        <td width="20%"  >Agitado
        </td>
        <td width="4%" style="border: 1px solid black; " align="center" >';
  if ($rsFormulario2[0]['agitado'] == 'Sí') $html .= 'X'; else $html .= '&nbsp;';
  $html .= '</td>
        <td width="10%"  >
        </td>
        <td width="20%"  >Violento/agresivo
        </td>
        <td width="4%" style="border: 1px solid black; " align="center" >';
  if ($rsFormulario2[0]['violento_agresivo'] == 'Sí') $html .= 'X'; else $html .= '&nbsp;';
$html .= '</td>
        <td width="10%"  >
        </td>
        <td width="20%"  >Impulsividad suicida
        </td>
        <td width="4%" style="border: 1px solid black; " align="center" >';
if ($rsFormulario2[0]['impulsividad'] == 'Sí') $html .= 'X'; else $html .= '&nbsp;';
$html .= '</td>
        <td width="10%"  >
        </td>
    ';
$html .= '</tr>
</table>

<h6 class="textoGrande"><b>Medios fracasados antes de la contención:</b></h6>
<table cellpadding="4" >
    <tr class="textoNormal" >
        <td width="20%"  >Contención Verbal
        </td>
        <td width="4%" style="border: 1px solid black; " align="center" >';
  if ($rsFormulario2[0]['verbal'] == 'Sí') $html .= 'X'; else $html .= '&nbsp;';
  $html .= '</td>
        <td width="10%"  >
        </td>
        <td width="20%"  >Contención ambiental
        </td>
        <td width="4%" style="border: 1px solid black; " align="center" >';
  if ($rsFormulario2[0]['ambiental'] == 'Sí') $html .= 'X'; else $html .= '&nbsp;';
$html .= '</td>
        <td width="10%"  >
        </td>
        <td width="20%"  >Contención farmacológica
        </td>
        <td width="4%" style="border: 1px solid black; " align="center" >';
if ($rsFormulario2[0]['farmacologica'] == 'Sí') $html .= 'X'; else $html .= '&nbsp;';
$html .= '</td>
        <td width="10%"  >
        </td>
    ';
$html .= '</tr>
</table>
<br>
<br>
<table cellpadding="4" >
    <tr class="textoNormal" >
        <td width="20%"  >Otra alternativa
        </td>
        <td width="4%" style="border: 1px solid black; " align="center" >';
  if ($rsFormulario2[0]['otra_contencion'] == 'Sí') $html .= 'X'; else $html .= '&nbsp;';
  $html .= '</td>
        <td width="10%"  >
        </td> <td width="20%">Especifique &nbsp;:&nbsp;</td>
      <td width="46%" class="highlight-blue borde-inferior" colspan="2" >&nbsp; '.$rsFormulario2[0]['medios_fracasados_otro'].'</td>';

$html .= '</tr>
</table>';

$html .= '<br>
<h6 class="textoGrande"><b>REGISTRO HORARIO</b></h6>
'.$tablaHorarios.'
&nbsp; <br>
<table cellpadding="2">
    <tr class="textoNormal" >
      <td width="25%">Administración de fármacos &nbsp;:&nbsp;</td>
      <td width="75%" class="highlight-blue borde-inferior" colspan="2" >&nbsp; '.$rsFormulario2[0]['administracion_farmacos'].'</td>
    </tr>
    <tr>
    <td>&nbsp;</td></tr>
    <tr class="textoNormal" >
      <td width="25%">Hora de retiro de contención &nbsp;:&nbsp;</td>
      <td width="75%" class="highlight-blue borde-inferior" colspan="2" >&nbsp; '.$rsFormulario2[0]['hora_retiro_contencion'].'</td>
    </tr>
  </table>
  <br>
  <br>
  <br>
    <table cellpadding="4" >
        <tr class="textoNormal" >
            <td width="100%" style="border: 1px solid black; "  >Observaciones:
            <br>'.$rsFormulario2[0]['observaciones'].'<br>
            </td>
        </tr>
    </table>
  <br>
  <br>
  <br>
    <table cellpadding="4" >
      <tr>
        <td width="100%" align="center">
          <p class="textoNormal"><b>Nombre y Firma enfermera/o</b> '.$rsFormulario2[0]['nombre_firma_enfermera'].'</p>
        </td>
      </tr>
    </table>

</body>
</html>
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
$nombre_archivo = 'pdfformulario_2.pdf';
$pdf->Output(__DIR__ . '/' . $nombre_archivo, 'FI');
$url = "/RCEDAU/views/modules/formularios/pdfformulario_2.pdf";
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