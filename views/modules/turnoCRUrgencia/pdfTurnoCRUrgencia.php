<iframe id="pdfTurnoCRUrgencia" height="100%" width="100%" hidden>
<?php
error_reporting(0);
ini_set('post_max_size', '512M');
ini_set('memory_limit', '1G');
set_time_limit(0);
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Cache-Control: no-store");

require_once('../../../../estandar/TCPDF-main/tcpdf.php');
require_once("../../../config/config.php");
require_once("../../../class/Connection.class.php");        $objCon         = new Connection();
require_once("../../../class/Util.class.php"); 		        $objUtil        = new Util;
require_once("../../../class/TurnoCRUrgencia.class.php"); 	$objTurno       = new TurnoCRUrgencia();
require_once('../../../class/Formulario.class.php'); 		$objFormulario 	= new Formulario;
require_once("../../../class/Upload.class.php");            $objUpload      = new Upload(FTP_IP, 'dauentregaturnourgencia', 'dauentregaturnourgencia');

$objCon->db_connect();

$idTurnoCRUrgencia = $_POST['idTurnoCRUrgencia'];

$inforTurnoCRUrgencia   = $objTurno->obtenerInfoTurnoCRUrgencia($objCon, $idTurnoCRUrgencia);
$detalleMedico          = $objTurno->obtenerturno_equipo_detalle($objCon, $idTurnoCRUrgencia, 1);
$detallecirujano        = $objTurno->obtenerturno_equipo_detalle($objCon, $idTurnoCRUrgencia, 2);
$detalleTens            = $objTurno->obtenerturno_equipo_detalle($objCon, $idTurnoCRUrgencia, 3);
$detalleEnfermero       = $objTurno->obtenerturno_equipo_detalle($objCon, $idTurnoCRUrgencia, 4);
$rsHospitalizacionesRea = $objTurno->obtenerturno_hospitalizaciones_detalle($objCon, $idTurnoCRUrgencia, 1);
$rsHospitalizaciones    = $objTurno->obtenerturno_hospitalizaciones_detalle($objCon, $idTurnoCRUrgencia, 2);

$tabladetalleEnfermero2 = "";
$totaldetalleEnfermero = ceil(count($detalleEnfermero) / 2)+1;
for ($i = 0; $i < count($detalleEnfermero); $i += 2) {
    $n1 = isset($detalleEnfermero[$i]['nombre']) ? $detalleEnfermero[$i]['nombre'] : '';
    $n2 = isset($detalleEnfermero[$i+1]['nombre']) ? $detalleEnfermero[$i+1]['nombre'] : '';
     $tablaEnfermeros2 .= '<tr>
        <td width="30%">&nbsp; '.$n1.'</td>
        <td width="30%">&nbsp; '.$n2.'</td>
    </tr>';
}

$tablaMedico2 = "";
$totalMedico = ceil(count($detalleMedico) / 2)+1;
for ($i = 0; $i < count($detalleMedico); $i += 2) {
    $n1 = isset($detalleMedico[$i]['nombre']) ? $detalleMedico[$i]['nombre'] : '';
    $n2 = isset($detalleMedico[$i+1]['nombre']) ? $detalleMedico[$i+1]['nombre'] : '';
     $tablaMedicos2 .= '<tr>
        <td width="30%">&nbsp; '.$n1.'</td>
        <td width="30%">&nbsp; '.$n2.'</td>
    </tr>';
}

$tablaCirujano2 = "";
$totalCirujano = ceil(count($detallecirujano) / 2);
for ($i = 2; $i < count($detallecirujano); $i += 2) {
    $n1 = isset($detallecirujano[$i]['nombre']) ? $detallecirujano[$i]['nombre'] : '';
    $n2 = isset($detallecirujano[$i+1]['nombre']) ? $detallecirujano[$i+1]['nombre'] : '';
     $tablaCirujanos2 .= '<tr>
        <td width="30%">&nbsp; '.$n1.'</td>
        <td width="30%">&nbsp; '.$n2.'</td>
    </tr>';
}

$tablaTens2 = "";
$totalTens = ceil(count($detalleTens) / 2);
for ($i = 2; $i < count($detalleTens); $i += 2) {
    $n1 = isset($detalleTens[$i]['nombre']) ? $detalleTens[$i]['nombre'] : '';
    $n2 = isset($detalleTens[$i+1]['nombre']) ? $detalleTens[$i+1]['nombre'] : '';
     $tablaTens2 .= '<tr>
        <td width="30%">&nbsp; '.$n1.'</td>
        <td width="30%">&nbsp; '.$n2.'</td>
    </tr>';
}


class MYPDF extends TCPDF {
    public function Test( $ae ) {
        if( !isset($this->xywalter) ) {
            $this->xywalter = array();
        }
        $this->xywalter[] = array($this->GetX(), $this->GetY());
    }
}

$inforTurnoCRUrgencia['novedades_adm']     = empty($inforTurnoCRUrgencia['novedades_adm'])     ? 'No' : $inforTurnoCRUrgencia['novedades_adm'];
$inforTurnoCRUrgencia['novedades_infra']   = empty($inforTurnoCRUrgencia['novedades_infra'])   ? 'No' : $inforTurnoCRUrgencia['novedades_infra'];
$inforTurnoCRUrgencia['novedades_equip']   = empty($inforTurnoCRUrgencia['novedades_equip'])   ? 'No' : $inforTurnoCRUrgencia['novedades_equip'];
$inforTurnoCRUrgencia['novedades_eventos'] = empty($inforTurnoCRUrgencia['novedades_eventos']) ? 'No' : $inforTurnoCRUrgencia['novedades_eventos'];


$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('HJNC-TURNO-CR-URGENCIA');
$pdf->SetTitle('Resumen Entrega Turno CR Urgencia');
$pdf->SetSubject('Resumeun');
$pdf->SetKeywords('Turno CR Urgencia, Resumen');
$pdf->setHeaderFont(Array('helvetica', '', 6));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(10, 3, 10);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(true);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setLanguageArray($l);
$pdf->setFontSubsetting(false);
$pdf->SetFont('helvetica', '', 8, '', true);

$pdf->AddPage();

// $html = "holas";
$html = '<head>';

$html .= pdfEstilos();

$html .= '</head>';

$html .= '<body>';

$html .= pdfEncabezado($inforTurnoCRUrgencia);

$html .= '<br>';

// $html .= pdfTitulo();
if($inforTurnoCRUrgencia['tipo'] == 2){
    $entregaTurno  = "Enfermera";
}else{
    $entregaTurno  = "Médica";
}
$html .=' <table width="100%" border="0">

        <tbody>

            <tr>

                <td style="text-align: center;"><h2>Resumen Entrega Turno '.$entregaTurno.'</h2></td>

            </tr>

        </tbody>

    </table> ';

$html .= '<br>';


$html .= '

        <table border="1" cellpadding="2">

            <tbody>

                <tr>

                    <td width="20%">Fecha</td>

                    <td width="20%" style="text-align:center;">'.date("d-m-Y", strtotime($inforTurnoCRUrgencia['fechaEntregaTurno'])).'</td>

                    <td width="20%" style="text-align:center;"> Horario del turno  </td>

                    <td width="40%" style="text-align:center;">'.($inforTurnoCRUrgencia['descripcionHorarioTurno']).'</td>

                </tr>';
if($inforTurnoCRUrgencia['tipo'] == 2){                
$html .= '
                <tr>

                    <td width="40%" rowspan = "'.$totaldetalleEnfermero.'" >Nombre de Enfermeros/as</td>
                    <td width="60%" colspan = "2" >&nbsp;Jefe Turno : '.($inforTurnoCRUrgencia['enf_jef_turno_nombre']).'
                    </td>
                </tr>
                '.$tablaEnfermeros2;
}

$html .= '
                <tr>

                    <td width="40%" rowspan = "'.$totalMedico.'" >Nombre de Medicos/as</td>
                    <td width="60%" colspan = "2" >&nbsp;Jefe Turno : '.($inforTurnoCRUrgencia['med_jef_turno_nombre']).'
                    </td>
                </tr>
                '.$tablaMedicos2.'
                <tr>

                    <td width="40%" rowspan = "'.$totalCirujano.'" >Nombre de Cirujanos de turno</td>
                    <td width="30%">&nbsp; '.$detallecirujano[0]['nombre'].'</td>
                    <td width="30%">&nbsp; '.$detallecirujano[1]['nombre'].'</td>
                </tr>
                '.$tablaCirujanos2;


if($inforTurnoCRUrgencia['tipo'] == 2){  
    $html .='   <tr>

                    <td width="40%" rowspan = "'.$totalTens.'" >Nombre de TENS</td>
                    <td width="30%">&nbsp; '.$detalleTens[0]['nombre'].'</td>
                    <td width="30%">&nbsp; '.$detalleTens[1]['nombre'].'</td>
                </tr>
                '.$tablaTens2;
}

$html .='
            </tbody>

        </table>';
$html .= pdfDesplegarNumeroPacientesEsperaAtencion($objCon, $objTurno, $objUtil, $idTurnoCRUrgencia);

$html .= '
        <table border="1" cellpadding="2">
            <tbody>
                <tr>
                    <td width="40%" >Novedades del turno</td>
                    <td width="20%">&nbsp; '.$inforTurnoCRUrgencia['novedadesTurno'].'</td>
                    <td width="20%">&nbsp; Administrativas : </td>
                    <td width="20%">&nbsp; '.$inforTurnoCRUrgencia['novedades_adm'].'</td>
                </tr>
                <tr>
                    <td width="40%" >Infratestructura</td>
                    <td width="20%">&nbsp; '.$inforTurnoCRUrgencia['novedades_infra'].'</td>
                    <td width="20%">&nbsp; Equipamiento : </td>
                    <td width="20%">&nbsp; '.$inforTurnoCRUrgencia['novedades_equip'].'</td>
                </tr>
                <tr>
                    <td width="40%" >Eventos adversos y/o Centinelas</td>
                    <td width="60%">&nbsp; '.$inforTurnoCRUrgencia['novedades_eventos'].'</td>
                </tr>
            </tbody>
        </table>';

        
// $html .= pdfDesplegarfechaTurno($objCon, $objTurno, $idTurnoCRUrgencia);

$html .= '<br>';

$html .= pdfDesplegarNumeroHospitalizaciones($objCon, $objTurno, $idTurnoCRUrgencia);

$html .= '<br>';

$html .= pdfDesplegarNumeroHospitalizacionesUrgencia($objCon, $objTurno, $idTurnoCRUrgencia);

$html .= '
        <table border="1" cellpadding="2">
            <tbody>
                <tr style="background-color: blue; color: white;" >
                    <td width="100%"  style="text-align:center;" >PACIENTES HOSPITALIZADOS CR EMERGENCIA HOSPITALARIA</td>
                </tr>

                <tr style="background-color: blue; color: white;" >
                    <td width="10%"  style="text-align:center;" >SALA</td>
                    <td width="10%"  style="text-align:center;" >CAMA</td>
                    <td width="20%"  style="text-align:center;" >NOMBRE PACIENTE</td>
                    <td width="10%"  style="text-align:center;" >DAU</td>
                    <td width="20%"  style="text-align:center;" >DIAGNOSTICO</td>
                    <td width="10%"  style="text-align:center;" >DESTINO</td>
                    <td width="20%"  style="text-align:center;" >OBSERVACIONES</td>
                </tr>';

for ($i = 0; $i < count($rsHospitalizacionesRea); $i++) {
        $html .= '        
                <tr  >
                    <td width="10%"  style="text-align:center;" >'.$rsHospitalizacionesRea[$i]['sala'].'</td>
                    <td width="10%"  style="text-align:center;" >'.$rsHospitalizacionesRea[$i]['cama'].'</td>
                    <td width="20%"  style="text-align:center;" >'.$rsHospitalizacionesRea[$i]['nombre_paciente'].'</td>
                    <td width="10%"  style="text-align:center;" >'.$rsHospitalizacionesRea[$i]['dau'].'</td>
                    <td width="20%"  style="text-align:center;" >'.$rsHospitalizacionesRea[$i]['diagnostico'].'</td>
                    <td width="10%"  style="text-align:center;" >'.$rsHospitalizacionesRea[$i]['destino'].'</td>
                    <td width="20%"  style="text-align:center;" >'.$rsHospitalizacionesRea[$i]['observaciones'].'</td>
                </tr>';
}
for ($i = 0; $i < count($rsHospitalizaciones); $i++) {
        $html .= '        
                <tr  >
                    <td width="10%"  style="text-align:center;" >'.$rsHospitalizaciones[$i]['sala'].'</td>
                    <td width="10%"  style="text-align:center;" >'.$rsHospitalizaciones[$i]['cama'].'</td>
                    <td width="20%"  style="text-align:center;" >'.$rsHospitalizaciones[$i]['nombre_paciente'].'</td>
                    <td width="10%"  style="text-align:center;" >'.$rsHospitalizaciones[$i]['dau'].'</td>
                    <td width="20%"  style="text-align:center;" >'.$rsHospitalizaciones[$i]['diagnostico'].'</td>
                    <td width="10%"  style="text-align:center;" >'.$rsHospitalizaciones[$i]['destino'].'</td>
                    <td width="20%"  style="text-align:center;" >'.$rsHospitalizaciones[$i]['observaciones'].'</td>
                </tr>';
}
        $html .= '       
            </tbody>
        </table>';

        // rsHospitalizacionesRea
// rsHospitalizaciones


$html .= pdfDesplegarSolicitudesEspecialistas($objCon, $objTurno, $idTurnoCRUrgencia);

// $html .= '<br>';

$html .= pdfDesplegarCirugiasRealizadas($objCon, $objTurno, $objUtil, $idTurnoCRUrgencia);

// $html .= '<br>';

$html .= pdfDesplegarTiemposAtencion($objCon, $objTurno, $idTurnoCRUrgencia);

// $html .= '<br>';

if($inforTurnoCRUrgencia['tipo'] == 2){
if($inforTurnoCRUrgencia['entrega_conforme'] == 'S'){
    $inforTurnoCRUrgencia['entrega_conforme'] = "Sí.";
}else{
    $inforTurnoCRUrgencia['entrega_conforme'] = "No. ".$inforTurnoCRUrgencia['entrega_no_motivo'];
}
if($inforTurnoCRUrgencia['ecografo_disponible'] == 'S'){
    $inforTurnoCRUrgencia['ecografo_disponible'] = "Sí.";
}else{
    $inforTurnoCRUrgencia['ecografo_disponible'] = "No. ".$inforTurnoCRUrgencia['ecografo_no_motivo'];
}
$html .= '

    <table border="1" cellpadding="2">
        <tr>
            <td width="25%">Entrega/recibe conforme : '.$inforTurnoCRUrgencia['entrega_conforme'].'</td>
            <td width="25%" style="text-align:center;"> BIC : '.($inforTurnoCRUrgencia['bic_cantidad']).'  </td>
            <td width="25%" style="text-align:center;"> ECOGRAFO : '.($inforTurnoCRUrgencia['ecografo_disponible']).' </td>
            <td width="25%" style="text-align:center;"> CELULARES  : '.($inforTurnoCRUrgencia['celulares_cantidad']).'</td>
        </tr>
    </table>';
}
// $html .= pdfDesplegarTiemposPromedioCategorizacion($objCon, $objTurno, $idTurnoCRUrgencia);

$html .= '<br>';

// $html .= pdfDesplegarNovedadesTurno($objCon, $objTurno, $idTurnoCRUrgencia);

$html .= '<br>';

$html .= pdfDesplegarFirmaMedicos($objCon, $objTurno, $idTurnoCRUrgencia);

$html .= '</body>';


$pdf->writeHTML($html, true, false, true, false, '');

$arr_nomes = array(
    array("", 20, 53)
);

$ttPages = $pdf->getNumPages();

for($i=1; $i<=$ttPages; $i++) {
    $pdf->setPage($i);
    foreach( $arr_nomes as $num => $arrCols ) {
        $x = $pdf->xywalter[$num][0] + $arrCols[1];
        $y = $pdf->xywalter[$num][1] + $arrCols[2];
		$n = $arrCols[0];
        $pdf->StartTransform();
        $pdf->Rotate(90, $x, $y);
        $pdf->Text($x, $y, $n);
        $pdf->StopTransform();
    }
}

$pdf->lastPage();
  
$nombreArchivo = 'resumenTurnoUrgencia_Numero('.$idTurnoCRUrgencia.').pdf';
$pdf->Output(__DIR__ . '/' . $nombreArchivo, 'FI');
$url = "/RCEDAU/views/modules/turnoCRUrgencia/".$nombreArchivo;


$archivo['nombreArchivo']               = $nombreArchivo;
$parametros['directorio']               = date('Y')."/".date('m')."/";
$parametros['nombre_archivo']           = $archivo['nombreArchivo'];
$parametros['mode']                     = FTP_BINARY;
// print('<pre>'); print_r($parametros); print('</pre>');
$objUpload->subirArchivoFTP($parametros);
// 

// $objFormulario->subeResumenEntregaTurnoUrgencia($nombreArchivo);
?>
</iframe>




<div class="embed-responsive embed-responsive-16by9">
	<iframe id="pdfTurnoCRUrgencia" class="embed-responsive-item" src="<?=$url?>" height="700" width="700" allowfullscreen></iframe>
</div>



<script>
$('#pdfTurnoCRUrgencia').ready(function(){
	ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','nombreArchivo=<?=$url?>&accion=eliminarPDF', 'POST', 'JSON', 1, '', true);
});
</script>



<?php
function pdfEstilos ( ) {

    return '

        <style type="text/css">

            body {

                font-family:"SourceSansPro-Regular", Arial, Helvetica;
                font-size:7pt;

            }

        </style>

    ';

}



function pdfEncabezado ( $inforTurnoCRUrgencia ) {

    return '

    <table width="640" border="0">

        <tbody>

            <tr>

                <td width="100" rowspan="4"><img src="/estandar/img/logo_gobierno_chile.jpg" width="50" height="50" /></td>

                <td width="200">MINISTERIO DE SALUD</td>

                <td width="200" rowspan="4"></td>

                <td width="50">Fecha:</td>

                <td width="90">'.date("d-m-Y", strtotime($inforTurnoCRUrgencia['fechaEntregaTurno'])).'</td>

            </tr>

            <tr>

                <td>HOSPITAL DR. JUAN NOÉ CREVANI</td>

                <td>Hora:</td>

                <td>'.date("H:i:s", strtotime($inforTurnoCRUrgencia['fechaEntregaTurno'])).'</td>

            </tr>

            <tr>

                <td>RUT: 61.606.000-7</td>

                <td>&nbsp;</td>

                <td>&nbsp;</td>

            </tr>

            <tr>

                <td>18 DE SEPTIEMBRE N°1000</td>

                <td colspan="2">&nbsp;</td>

            </tr>

        </tbody>

    </table>

    ';

}



function pdfTitulo ( ) {

    return '

        <table width="100%" border="0">

        <tbody>

            <tr>

                <td style="text-align: center;"><h2>Resumen Entrega Turno CR Urgencias</h2></td>

            </tr>

        </tbody>

    </table>

    ';

}



function pdfDesplegarNumeroHospitalizaciones ( $objCon, $objTurno, $idTurnoCRUrgencia ) {

    $numeroHospitalizaciones = $objTurno->pdfObtenerNumeroHospitalizacion($objCon, $idTurnoCRUrgencia);

    return '

        <table border="1" cellpadding="2">

            <thead>

                <tr style="background-color: blue; color: white;">

                    <td width="40%">Descripción Número de Hospitalizaciones</td>

                    <td width="30%" style="text-align:center;">Cantidad Adultos</td>

                    <td width="30%" style="text-align:center;">Cantidad Pediátricos</td>


                </tr>

            </thead>

            <tbody>

                <tr>

                    <td width="40%">Número de Pacientes con Indicación de Egreso en DAU cuyo destino es Hospitalización</td>

                    <td width="30%" style="text-align:center;">'.desplegarNumero($numeroHospitalizaciones['numeroHospitalizacionesAdulto']).'</td>

                    <td width="30%" style="text-align:center;">'.desplegarNumero($numeroHospitalizaciones['numeroHospitalizacionesPediatrico']).'</td>


                </tr>

            </tbody>

        </table>';

}



function pdfDesplegarNumeroHospitalizacionesUrgencia ( $objCon, $objTurno, $idTurnoCRUrgencia ) {

    $numeroHospitalizacionesUrgencia= $objTurno->pdfObtenerNumeroHospitalizacionesUrgencia($objCon, $idTurnoCRUrgencia);

    return '

        <table border="1" cellpadding="2">

            <thead>

                <tr style="background-color: blue; color: white;">

                    <td width="40%">Descripción Número de Hospitalizaciones Urgencia</td>

                    <td width="30%" style="text-align:center;">Cantidad Adultos</td>

                    <td width="30%" style="text-align:center;">Cantidad Pediátricos</td>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td width="40%">Número de Pacientes Hospitalizados en Urgencia esperando menos de 12 horas</td>'

                    .desplegarNumeroHospitalizacionesUrgencia($numeroHospitalizacionesUrgencia).

                '</tr>

                <tr>

                    <td width="40%">Número de Pacientes Hospitalizados en Urgencia esperando más de 12 horas y menos de 24 horas</td>'

                    .desplegarNumeroHospitalizacionesUrgencia12($numeroHospitalizacionesUrgencia).

                '</tr>

                <tr>

                    <td width="40%">Número de Pacientes Hospitalizados en Urgencia esperando más de 24 horas</td>'

                    .desplegarNumeroHospitalizacionesUrgencia24($numeroHospitalizacionesUrgencia).

                '</tr>

            </tbody>

        </table>';

}



function desplegarNumeroHospitalizacionesUrgencia ( $numeroHospitalizaciones ) {

    return '

        <td width="30%" style="text-align:center;">'.desplegarNumero($numeroHospitalizaciones['numeroHospitalizacionesAdulto']).'</td>

        <td width="30%" style="text-align:center;">'.desplegarNumero($numeroHospitalizaciones['numeroHospitalizacionesPediatrico']).'</td>

        ';

}



function desplegarNumeroHospitalizacionesUrgencia12 ( $numeroHospitalizaciones ) {

    return '

        <td width="30%" style="text-align:center;">'.desplegarNumero($numeroHospitalizaciones['numeroHospitalizacionesAdulto12']).'</td>

        <td width="30%" style="text-align:center;">'.desplegarNumero($numeroHospitalizaciones['numeroHospitalizacionesPediatrico12']).'</td>

        ';

}



function desplegarNumeroHospitalizacionesUrgencia24 ( $numeroHospitalizaciones ) {

    return '

        <td width="30%" style="text-align:center;">'.desplegarNumero($numeroHospitalizaciones['numeroHospitalizacionesAdulto24']).'</td>

        <td width="30%" style="text-align:center;">'.desplegarNumero($numeroHospitalizaciones['numeroHospitalizacionesPediatrico24']).'</td>

        ';

}



function pdfDesplegarNumeroPacientesEsperaAtencion ( $objCon, $objTurno, $objUtil, $idTurnoCRUrgencia ) {

    $pacienteEsperaAtencion = $objTurno->pdfObtenerPacientesEnEsperaAtencion($objCon, $idTurnoCRUrgencia);


    return '

        <table border="1" cellpadding="2">

            <thead>

                <tr style="background-color: blue; color: white;">

                    <td width="30%">Paciente en Espera</td>

                    <td width="35%" style="text-align:center;">Adultos</td>

                    <td width="35%" style="text-align:center;">Pediátricos</td>

                </tr>

          

            </thead>

            <tbody>'

                .desplegarNumeroPacientesEsperaAtencionCategorizacion($objCon, $objUtil, $objTurno, $pacienteEsperaAtencion).

            '</tbody>

        </table>';

}



function desplegarNumeroPacientesEsperaAtencionCategorizacion ( $objCon, $objUtil, $objTurno, $pacienteEsperaAtencion ) {

    $textoADesplegar = '';

    $categorizaciones = array('SC', 'ESI-1', 'ESI-2', 'ESI-3', 'ESI-4', 'ESI-5');
    $categorizacioneC = array('SC', 'C-1', 'C-2', 'C-3', 'C-4', 'C-5');

    $totalCategorizaciones = count($categorizaciones);

    for ( $i = 0; $i < $totalCategorizaciones; $i++ ) {

        $parametrosAEnviar[] = array();

        $parametrosAEnviar['idTurnoEsperaAtencion'] = $pacienteEsperaAtencion['idTurnoEsperaAtencion'];

        $parametrosAEnviar['tipoCategorizacion'] = $categorizaciones[$i];

        $parametrosAEnviar['tipoPaciente'] = 1;

        $pacientesAdultoEspera = $objTurno->pdfObtenerPacientesEnEsperaAtencionDetalle($objCon, $parametrosAEnviar);

        $parametrosAEnviar['tipoPaciente'] = 2;

        $pacientesPediatricoEspera = $objTurno->pdfObtenerPacientesEnEsperaAtencionDetalle($objCon, $parametrosAEnviar);

        $tituloCategorizacion = 'Categoría '.$categorizacioneC[$i];

        if ( $categorizaciones[$i] == 'SC' ) {

            $tituloCategorizacion = 'Sin Categorizar';

        }

        // $textoADesplegar .= '

        //     <tr>

        //         <td width="30%">'.$tituloCategorizacion.' (Adultos: '.count($pacientesAdultoEspera).' - Pediátricos: '.count($pacientesPediatricoEspera).')</td>

        //         <td width="35%">

        //             <table width="100%">

        //                 <tbody>'

        //                     .desplegarNumeroPacientesEsperaAtencionCategorizacionDetalle($objUtil, $pacientesAdultoEspera).

        //                 '</tbody>

        //             </table>

        //         </td>

        //         <td width="35%">

        //             <table width="100%">

        //                 <tbody>'

        //                     .desplegarNumeroPacientesEsperaAtencionCategorizacionDetalle($objUtil, $pacientesPediatricoEspera).

        //                 '</tbody>

        //             </table>

        //         </td>

        //     </tr>


        // ';
        $cantAdulto = count($pacientesAdultoEspera);
        $cantPediatrico = count($pacientesPediatricoEspera);
        if($cantAdulto  == 0){
            $cantAdulto  = "-";
        }
        if($cantPediatrico  == 0){
            $cantPediatrico  = "-";
        }
    $textoADesplegar .= '

            <tr>

                <td width="30%" style="text-align:center;">'.$tituloCategorizacion.'</td>

                <td width="35%" style="text-align:center;">

                   '.$cantAdulto.'

                </td>

                <td width="35%" style="text-align:center;">

                      '.$cantPediatrico.'

                </td>

            </tr>


        ';
        unset($parametrosAEnviar);

    }

    return $textoADesplegar;

}



function desplegarNumeroPacientesEsperaAtencionCategorizacionDetalle ( $objUtil, $pacienteEsperaAtencion ) {

    $textoADesplegar = '';

    $totalPacienteEsperaAtencion = count($pacienteEsperaAtencion);

    for ( $i = 0; $i < $totalPacienteEsperaAtencion; $i++ ) {

        $textoADesplegar .= '

            <tr>

                <td width="20%">'.$pacienteEsperaAtencion[$i]['numeroDau'].'</td>

                <td width="40%">'.$pacienteEsperaAtencion[$i]['nombrePaciente'].'</td>

                <td width="20%" style="text-align:center">'.$objUtil->edadActual($pacienteEsperaAtencion[$i]['fechaNacimientoPaciente']).'</td>

                <td width="20%" style="text-align:center">'.$pacienteEsperaAtencion[$i]['tiempoEsperaPaciente'].'</td>

            </tr>

            ';

    }

    return $textoADesplegar;

}



function pdfDesplegarCirugiasRealizadas ( $objCon, $objTurno, $objUtil, $idTurnoCRUrgencia ) {

    $cirugiasRealizadas = $objTurno->pdfObtenerCirugiasRealizadas($objCon, $idTurnoCRUrgencia);

    if ( empty($cirugiasRealizadas) || is_null($cirugiasRealizadas) ) {

        return false;

    }

    return '

        <table border="1" cellpadding="2">

            <thead>

                <tr style="background-color: blue; color: white;">

                    <td width="100%" colspan="6" style="text-align:center;">Cirugías o Procedimientos Realizados</td>

                </tr>

                <tr style="background-color: blue; color: white;">

                    <td width="20%">Nombre Paciente</td>

                    <td width="10%" style="text-align:center;">RUN Paciente</td>

                    <td width="35%">Diagnóstico Pre-Quirúrgico</td>

                    <td width="15%" style="text-align:center;">Código Prestación</td>

                    <td width="10%" style="text-align:center;">Nº Cirujano</td>

                    <td width="10%" style="text-align:center;">Tipo Cirugía</td>

                </tr>

            </thead>

            <tbody>'

                .desplegarCirugiasRealizadasDetalle($objCon, $objTurno, $objUtil, $cirugiasRealizadas).

            '</tbody>

        </table>

    ';

}



function desplegarCirugiasRealizadasDetalle ( $objCon, $objTurno, $objUtil, $cirugiasRealizadas ) {

    $textoADesplegar = '';

    $totalCirugiasRealizadas = count($cirugiasRealizadas);

    if ( $totalCirugiasRealizadas == 0 ) {

        return '

            <tr>

                <td colspan="6" width="100%">&nbsp;</td>

            </tr>

            ';

    }

    for ( $i = 0; $i < $totalCirugiasRealizadas; $i++ ) {

        $parametrosAEnviar[] = array();

        $cirugiasRealizadasDetalle = $objTurno->pdfObtenerCirugiasRealizadasDetalle($objCon, $cirugiasRealizadas[$i]['idTurnoCirugiasRealizadas']);

        $textoADesplegar .= '

            <tr>

                <td width="20%">'.$cirugiasRealizadas[$i]['nombrePaciente'].'</td>

                <td width="10%" style="text-align:center;">'.$objUtil->rut($cirugiasRealizadas[$i]['runPaciente'].'-'.$objUtil->generaDigito($cirugiasRealizadas[$i]['runPaciente'])).'</td>

                <td width="35%">

                    <ul>';

                        for ( $j = 0; $j < count($cirugiasRealizadasDetalle); $j++ ) {

                            $textoADesplegar .= '

                                <li>'.$cirugiasRealizadasDetalle[$j]['glosaCirugia'].'</li>

                            ';

                        }

                    $textoADesplegar .= '

                    </ul>

                </td>

                <td width="15%">


                    <ul>';

                        for ( $j = 0; $j < count($cirugiasRealizadasDetalle); $j++ ) {

                            $textoADesplegar .= '

                                <li>'.$cirugiasRealizadasDetalle[$j]['codigoPrestacion'].'</li>

                            ';

                        }

                    $textoADesplegar .= '

                    </ul>

                </td>

                <td width="10%" style="text-align:center;">'.$cirugiasRealizadas[$i]['numeroCirujano'].'</td>

                <td width="10%" style="text-align:center;">'.$cirugiasRealizadas[$i]['tipoCirugia'].'</td>

            </tr>

            ';

        unset($parametrosAEnviar);

    }

    return $textoADesplegar;

}



function pdfDesplegarSolicitudesEspecialistas ( $objCon, $objTurno, $idTurnoCRUrgencia ) {

     $solicitudesEspecialistas = $objTurno->pdfObtenerSolicitudesEspecialistas($objCon, $idTurnoCRUrgencia);

    if ( empty($solicitudesEspecialistas) || is_null($solicitudesEspecialistas) ) {

        return false;

    }

    return '

        <table border="1" cellpadding="2">

            <thead>

                <tr style="background-color: blue; color: white;">

                    <td width="100%" colspan="6" style="text-align:center;">Solicitudes Especialistas</td>

                </tr>

                <tr>

                    <th width="10%" style="text-align:center;">DAU</th>

                    <th width="20%">Nombre Paciente</th>

                    <th width="18%" style="text-align:center;">F. Solicitud</th>

                    <th width="12%" style="text-align:center;">Gestión Realizada</th>

                    <th width="20%">Profesional Especialista</th>

                    <th width="20%" style="text-align:center;">Estado Solicitud</th>

                </tr>

            </thead>

            <tbody>'

                .desplegarSolicitudesEspecialistasDetalle($solicitudesEspecialistas).

            '</tbody>

        </table>

    ';


}



function pdfDesplegarTiemposAtencion ( $objCon, $objTurno, $idTurnoCRUrgencia ) {

    $tiemposAtencion = $objTurno->pdfObtenerTiemposAtencion($objCon, $idTurnoCRUrgencia);

    if ( empty($tiemposAtencion) || is_null($tiemposAtencion) ) {

        return false;

    }

    return '

        <table border="1" cellpadding="2">

            <thead>

                <tr style="background-color: blue; color: white;">

                    <td width="100%" colspan="5" style="text-align:center;">Tiempos de Atención Realizados por Profesional que Entrega Turno (Desde Admisión a Inicio Atención)</td>

                </tr>

                <tr style="background-color: blue; color: white;">

                    <td width="20%" style="text-align: center;">Nombre Profesional</td>

                    <td width="20%" style="text-align: center;">Total Pacientes</td>

                    <td width="20%" style="text-align: center;">Tiempo Promedio</td>

                    <td width="20%" style="text-align: center;">Tiempo Mínimo</td>

                    <td width="20%" style="text-align: center;">Tiempo Máximo</td>

                </tr>

            </thead>

            <tbody>'

                .desplegarTiemposAtencionDetalle($tiemposAtencion).

            '</tbody>

        </table>

    ';

}



function desplegarTiemposAtencionDetalle ( $tiemposAtencion ) {

    if ( empty($tiemposAtencion) || is_null($tiemposAtencion) ) {

        return '

        <tr>

            <td>&nbsp;</td>

            <td>&nbsp;</td>

            <td>&nbsp;</td>

            <td>&nbsp;</td>

            <td>&nbsp;</td>

        </tr>

        ';

    }

    return '

        <tr>

            <td width="20%">'.$tiemposAtencion['nombreProfesional'].'</td>

            <td width="20%" style="text-align:center;">'.$tiemposAtencion['cantidadPacientesAtendidos'].'</td>

            <td width="20%" style="text-align:center;">'.$tiemposAtencion['tiempoPromedioAtencion'].'</td>

            <td width="20%" style="text-align:center;">'.$tiemposAtencion['tiempoMinimoAtencion'].'</td>

            <td width="20%" style="text-align:center;">'.$tiemposAtencion['tiempoMaximoAtencion'].'</td>

        </tr>

        ';

}



function pdfDesplegarTiemposPromedioCategorizacion ( $objCon, $objTurno, $idTurnoCRUrgencia ) {

    return '

        <table border="1" cellpadding="2">

            <thead>

                <tr style="background-color: blue; color: white;">

                    <td width="100%" style="text-align:center;">Tiempos Promedios según Tipo de Categorización</td>

                </tr>

                <tr style="background-color: blue; color: white;">

                    <td width="25%" rowspan="2" style="text-align: center;">Categorización</td>

                    <td width="25%" style="text-align: center;">Categorización a Inicio Atención</td>

                    <td width="25%" style="text-align: center;">Inicio a Cierre de Atención</td>

                    <td width="25%" style="text-align: center;">Cierre Atención a Aplicación de Cierre</td>

                </tr>

                <tr style="background-color: blue; color: white;">

                    <td width="12%" style="text-align: center;">Pacientes</td>

                    <td width="13%" style="text-align: center;">Tiempo Promedio</td>

                    <td width="12%" style="text-align: center;">Pacientes</td>

                    <td width="13%" style="text-align: center;">Tiempo Promedio</td>

                    <td width="12%" style="text-align: center;">"Pacientes</td>

                    <td width="13%" style="text-align: center;">Tiempo Promedio</td>

                </tr>

            </thead>

            <tbody>'

                .desplegarTiemposPromediosDetalle($objCon, $objTurno, $idTurnoCRUrgencia).

            '</tbody>

        </table>


    ';

}



function desplegarSolicitudesEspecialistasDetalle ( $solicitudesEspecialistas ) {

    $html = "";

    for ( $i = 0; $i < count($solicitudesEspecialistas); $i++ ) {

        $gestionRealizada = ( $solicitudesEspecialistas[$i]['gestionRealizada'] == 'S' ) ? "Si" : "No";

        $html .= "<tr>";

        $html .='<td width="10%" style="text-align:center;">'.$solicitudesEspecialistas[$i]['idDau'].'</td>';

        $html .='<td width="20%">'.$solicitudesEspecialistas[$i]['nombrePaciente'].'</td>';

        $html .='<td width="18%" style="text-align:center;">'.date("d-m-Y H:i:s", strtotime($solicitudesEspecialistas[$i]['fechaSolicitudEspecialista'])).'</td>';

        $html .='<td width="12%" style="text-align:center;">'.$gestionRealizada.'</td>';

        $html .='<td width="20%" style="text-align:center;">'.$solicitudesEspecialistas[$i]['descripcionProfesionalEspecialista'].'</td>';

        $html .='<td width="20%" style="text-align:center;">'.$solicitudesEspecialistas[$i]['descripcionEstadoSolicitud'].'</td>';

        $html .= "</tr>";

    }

    return $html;

}



function desplegarTiemposPromediosDetalle ( $objCon, $objTurno, $idTurnoCRUrgencia ) {

    $textoADesplegar = '';

    $categorizaciones = array('ESI-1', 'ESI-2', 'ESI-3', 'ESI-4', 'ESI-5');

    for ( $i = 0; $i < count($categorizaciones); $i++ ) {

        $parametrosAEnviar[] = array();

        $parametrosAEnviar['idTurnoCRUrgencia'] = $idTurnoCRUrgencia;

        $parametrosAEnviar['tipoCategorizacion'] = $categorizaciones[$i];

        $textoADesplegar .= desplegarTiemposPromediosSegunCategorizacion($objCon, $objTurno, $parametrosAEnviar);

        unset($parametrosAEnviar);

    }

    return $textoADesplegar;

}



function desplegarTiemposPromediosSegunCategorizacion ( $objCon, $objTurno, $parametros ) {


    $tiemposPromedioCategorizacion = $objTurno->pdfObtenerTiemposPromedioCategorizacion($objCon, $parametros);

    return '

        <tr>

            <td width="25%">'.$parametros['tipoCategorizacion'].'</td>

            <td width="25%">

                <table width="100%">

                    <tr>

                        <td width="50%" style="text-align: center;">'.$tiemposPromedioCategorizacion['totalPacientes_CategorizacionInicioAtencion'].'</td>

                        <td width="50%" style="text-align: center;">'.$tiemposPromedioCategorizacion['tiempoPromedio_CategorizacionInicioAtencion'].'</td>

                    </tr>

                </table>

            </td>

            <td width="25%">

                <table width="100%">

                    <tr>

                        <td width="50%" style="text-align: center;">'.$tiemposPromedioCategorizacion['totalPacientes_InicioAtencionCierreAtencion'].'</td>

                        <td width="50%" style="text-align: center;">'.$tiemposPromedioCategorizacion['tiempoPromedio_InicioAtencionCierreAtencion'].'</td>

                    </tr>

                </table>

            </td>

            <td width="25%">

                <table width="100%">

                    <tr>

                        <td width="50%" style="text-align: center;">'.$tiemposPromedioCategorizacion['totalPacientes_CierreAtencionAplicacionCierre'].'</td>

                        <td width="50%" style="text-align: center;">'.$tiemposPromedioCategorizacion['tiempoPromedio_CierreAtencionAplicacionCierre'].'</td>

                    </tr>

                </table>

            </td>

        </tr>

    ';

}




function pdfDesplegarNovedadesTurno ( $objCon, $objTurno, $idTurnoCRUrgencia ) {

    $inforTurnoCRUrgencia = $objTurno->obtenerInfoTurnoCRUrgencia($objCon, $idTurnoCRUrgencia);

    if ( empty($inforTurnoCRUrgencia['novedadesTurno']) || is_null($inforTurnoCRUrgencia['novedadesTurno']) ) {

        return false;

    }

    $inforTurnoCRUrgencia['novedadesTurno'] = str_replace("<", "&#60;", $inforTurnoCRUrgencia['novedadesTurno']);
	$inforTurnoCRUrgencia['novedadesTurno'] = str_replace("&#60;br>", "<br>", $inforTurnoCRUrgencia['novedadesTurno']);

    return '

        <table border="1" cellpadding="2">

            <thead>

                <tr style="background-color: blue; color: white;">

                    <td>Novedades del Turno</td>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td>'.$inforTurnoCRUrgencia['novedadesTurno'].'</td>

                </tr>

            </tbody>

        </table>

    ';

}



function pdfDesplegarFirmaMedicos ( $objCon, $objTurno, $idTurnoCRUrgencia ) {

    $imgProfesionalEntregaTurno = '&nbsp;';

    $imgProfesionalRecibeTurno = '&nbsp;';

    $infoTurnoCRUrgencia = $objTurno->obtenerInfoTurnoCRUrgencia($objCon, $idTurnoCRUrgencia);

    $infoProfesional = $objTurno->obtenerInfoProfesionalPorId($objCon, $infoTurnoCRUrgencia['profesionalEntregaTurno']);

    $URLFirmaProfesionalEntregaTurno = "http://".IP."/firmaDigital/medicos/".$infoProfesional['rutusuario'].".png";

    $infoProfesional = $objTurno->obtenerInfoProfesionalPorId($objCon, $infoTurnoCRUrgencia['profesionalRecibeTurno']);

    $URLFirmaProfesionalRecibeTurno = "http://".IP."/firmaDigital/medicos/".$infoProfesional['rutusuario'] .".png";

    $file_headers_profesionalEntregaTurno = @get_headers($URLFirmaProfesionalEntregaTurno, 1);

    $file_headers_profesionalRecibeTurno = @get_headers($URLFirmaProfesionalRecibeTurno, 1);

    if ( $file_headers_profesionalEntregaTurno[0] == 'HTTP/1.1 200 OK' ) {

        $imgProfesionalEntregaTurno = '<img src="'.$URLFirmaProfesionalEntregaTurno.'" width="150px" height="35px">';

    }

    if ( $file_headers_profesionalRecibeTurno[0] == 'HTTP/1.1 200 OK' ) {

        $imgProfesionalRecibeTurno = '<img src="'.$URLFirmaProfesionalRecibeTurno.'" width="150px" height="35px">';

    }

    return '

        <table>

            <tr>

                <td width="50%" style="text-align: center;">'.$imgProfesionalEntregaTurno.'</td>

                <td width="50%" style="text-align: center;">'.$imgProfesionalRecibeTurno.'</td>

            </tr>

            <tr>

                <td width="50%" style="text-align: center;">

                    <strong>Profesional Entrega Turno: '.$infoTurnoCRUrgencia['nombreProfesionalEntregaTurno'].'</strong>

                </td>

                <td width="50%" style="text-align: center;">

                    <strong>Profesional Recibe Turno: '.$infoTurnoCRUrgencia['nombreProfesionalRecibeTurno'].'</strong>

                </td>

            </tr>

        </table>

    ';


}



function tiempoEsperapaciente ( $pacienteEsperaAtencion ) {

    return ( empty($pacienteEsperaAtencion['tiempoEsperaConCategorizacion']) && is_null($pacienteEsperaAtencion['tiempoEsperaConCategorizacion']) ) ? $pacienteEsperaAtencion['tiempoEsperaSinCategorizacion'] : $pacienteEsperaAtencion['tiempoEsperaConCategorizacion'];

}






function desplegarNumero ( $numero ) {

    return ( empty($numero) || is_null($numero) ) ? '0' : $numero;

}
?>
