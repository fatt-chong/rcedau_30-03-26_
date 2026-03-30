<iframe id="pdfReporteDiarioDAURCE" height="100%" width="100%" hidden>
<?php
error_reporting(1);
ini_set('post_max_size', '512M');
ini_set('memory_limit', '1G');
set_time_limit(0);
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Cache-Control: no-store");

require_once('../../../../estandar/tcpdf/tcpdf.php');
require_once('../../../../estandar/tcpdf/config/lang/spa.php');
require_once("../../../config/config.php");
require_once("../../../class/Mail/class.phpmailer.php");
require_once("../../../class/Connection.class.php");        $objCon         = new Connection();
require_once("../../../class/Util.class.php"); 		        $objUtil        = new Util;
require_once("../../../class/Reportes.class.php"); 	        $objReporte     = new Reportes();
require_once('../../../class/Formulario.class.php'); 		$objFormulario 	= new Formulario;
include '../../../assets/libs/libchart/classes/libchart.php';



$objCon->db_connect();

$fechas[] = array();

$fechas['fechaAnterior'] = $objUtil->fechaAnteriorSegunTurno($fechas['fechaAnterior']);

$fechas['fechaActual']   = date('Y-m-d').' 07:59:59';



class MYPDF extends TCPDF {
    public function Test( $ae ) {
        if( !isset($this->xywalter) ) {
            $this->xywalter = array();
        }
        $this->xywalter[] = array($this->GetX(), $this->GetY());
    }
}


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
$pdf->SetFont('helvetica', '', 7, '', true);

$pdf->AddPage();

$html = '<head>';

$html .= '

    <style type="text/css">
        body {

                font-family:"SourceSansPro-Regular", Arial, Helvetica;
                font-size:7pt;

            }
    </style> ';

$html .= pdfEstilos();

$html .= '</head>';

$html .= '<body>';

$html .= pdfEncabezado();

$html .= '<br>';

$html .= pdfTitulo($fechas);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfNumeroPacientesConIndicacionFinal($objCon, $objReporte, $fechas);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfNumeroAtencionesPorProfesional($objCon, $objReporte, $fechas);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfNumeroPacientesPorDiagnosticos($objCon, $objReporte, $fechas);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfNumeroPacientesPorCategorizacion($objCon, $objReporte, $fechas);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfNumeroPacientesConIndicacionHospitalizacion($objCon, $objReporte, $fechas);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfTiemposEsperaPorProfesional($objCon, $objReporte, $fechas);

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= '<br pagebreak="true" />';

$html .= pdfEstilos();

$html .= pdfNumeroHospitalizacionesUrgencia($objCon, $objReporte, $fechas);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfCantidadAltasSegunProfesional($objCon, $objReporte, $fechas);

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= '<br pagebreak="true" />';

$html .= pdfEstilos();

$html .= pdfTiemposMaximosCategorizacionTabla($objCon, $objReporte, $fechas);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfTiemposMaximosCategorizacionGrafico($objCon, $objReporte, $fechas, 1);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfTiemposMaximosCategorizacionGrafico($objCon, $objReporte, $fechas, 2);

$html .= '<br pagebreak="true" />';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfCantidadPacientesSegunEstado($objCon, $objReporte, $fechas);

$html .= '<br pagebreak="true" />';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfCantidadPacientesSegunEstadoGrafico($objCon, $objReporte, $fechas, 'admision');

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfCantidadPacientesSegunEstadoGrafico($objCon, $objReporte, $fechas, 'categorizados');

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfCantidadPacientesSegunEstadoGrafico($objCon, $objReporte, $fechas, 'inicioAtencion');

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfCantidadPacientesSegunEstadoGrafico($objCon, $objReporte, $fechas, 'indicacionEgreso');

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfCantidadPacientesSegunEstadoGrafico($objCon, $objReporte, $fechas, 'egresados');

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

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

$nombreArchivo = 'reporteDiarioDAURCE_'.date('d-m-Y').'.pdf';

$urlArchivo = '../reportesDiarioDAURCE/'.date("Y").'/'.date("m");

if ( ! file_exists('path/to/directory') ) {

    mkdir($urlArchivo, 0777, true);

}

$pdf->Output($urlArchivo.'/'.$nombreArchivo,'FI');

$mail = new PHPMailer();

$mail->IsSMTP();

$mail->IsHTML(true);

$mail->SMTPDebug = false;

$mail->SMTPAuth = true;

$mail->SMTPSecure = "tls";

$mail->Host = "smtp.gmail.com";

$mail->Port = 587;

$mail->CharSet = "US-ASCII";

$mail->Encoding = "7BIT";

$mail->SetFrom("reportes.hospital@hjnc.cl","Informatica");

$mail->Username = "reportes.hospital@hjnc.cl";

$mail->Password = "12qwaszx";

$mail->Subject = "REPORTE DIARIO DAU RCE ".date('d-m-Y');

$body  = "Estimados,<br> Se adjunta Reporte Diario de DAU RCE.<br><br><b>Resumen Desde: ".date('d-m-Y H:i:s', strtotime($fechas['fechaAnterior']))." Hasta: ".date('d-m-Y H:i:s', strtotime($fechas['fechaActual']))."</b><br>".$tabla_resumen."<br><br><br>NOTA:<br>Este correo electronico ha sido generado automaticamente por nuestra plataforma,<br>Por lo que se ruega no responder el mensaje.<br>Copyright © 2012, Ingenieria de Sistemas,<br>Hospital Regional de Arica y Parinacota Dr. Juan Noe ";

$mail->MsgHTML($body);

$mail->AltBody = "";

$mail->AddAddress('luis.diaz@hjnc.cl', 'Luis Diaz');
$mail->AddAddress('luis.vasquez@hjnc.cl', 'Luis Vasquez');
$mail->AddAddress('jorge.becerra@hjnc.cl', 'Luis Becerra');
$mail->AddAddress('viviana.galarce@hjnc.cl', 'Viviana Galarce');
$mail->AddAddress('paula.godoy@hjnc.cl', 'Paula Godoy');
$mail->AddAddress('alfredo.figueroas@hjnc.cl', 'Alfredo Figueroa');
$mail->AddAddress('marco.mella@hjnc.cl', 'Marco Mella');
$mail->AddAddress('majose.retamal@hjnc.cl', 'Maria Jose Retamal');
$mail->AddAddress('magdalena.gardilcic.f@saludarica.cl', 'Magdalena Gardilcic Franulic');
$mail->AddAddress('marcela.soto@hjnc.cl', 'Marcela Soto');
$mail->AddAddress("pamela.sanchez@hjnc.cl","Pamela Sanchez");
$mail->AddAddress('andres.escobar@hjnc.cl', 'Andres Escobar');

$mail->AddAttachment($urlArchivo.'/'.$nombreArchivo, $nombreArchivo);

$mail->Send();

//unlink($nombreArchivo);
?>
</iframe>



<?php
function pdfEstilos ( ) {

    return '

        <style type="text/css">

            .titulo {

                background-color: #6495ED;
                color: white;
                font-weight: bold;

            }

            table {

                font-family:"SourceSansPro-Regular", Arial, Helvetica;
                font-size:7pt;

            }

        </style>

    ';

}



function pdfEncabezado ( ) {

    return '

        <table width="640" border="0">

            <tbody>

                <tr>

                    <td width="100" rowspan="4"><img src="../../../assets/img/logo.png" width="50" height="50" /></td>

                    <td width="200">MINISTERIO DE SALUD</td>

                    <td width="200" rowspan="4"></td>

                    <td width="50">Fecha:</td>

                    <td width="90">'.date("d-m-Y").'</td>

                </tr>

                <tr>

                    <td>HOSPITAL DR. JUAN NOÉ CREVANI</td>

                    <td>Hora:</td>

                    <td>'.date("H:i:s").'</td>

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



function pdfTitulo ( $fechas ) {

    return '

        <table width="100%" border="0">

            <tbody>

                <tr>

                    <td style="text-align: center;"><h2>Resumen Diario Dau RCE</h2></td>

                </tr>

                <tr>

                    <td style="text-align: center;"><h2>Desde: '.date('d-m-Y H:i:s', strtotime($fechas['fechaAnterior'])).' Hasta: '.date('d-m-Y H:i:s', strtotime($fechas['fechaActual'])).'</h2></td>

                </tr>

            </tbody>

        </table>

    ';

}



function pdfNumeroPacientesConIndicacionFinal ( $objCon, $objReporte, $fechas ) {

    $pacientesCierre = $objReporte->numeroPacientesConIndicacionFinal($objCon, $fechas);

    $pacientesNEA = $objReporte->numeroPacientesConIndicacionFinalNEA($objCon, $fechas);

    return '

        <table border="1" cellpadding="2">

            <thead>

                <tr class="titulo">

                    <td colspan="4" width="100%">Número de Pacientes con Indicación Final</td>

                </tr>

                <tr class="titulo">

                    <td width="25%" style="text-align: center;">Adulto</td>

                    <td width="25%" style="text-align: center;">Pediátrico</td>

                    <td width="25%" style="text-align: center;">Ginecológico</td>

                    <td width="25%" style="text-align: center;">Total</td>

                </tr>

                <tr class="titulo">

                    <td width="9%" style="text-align: center;">Atendidos</td>

                    <td width="8%" style="text-align: center;">N.E.A.</td>

                    <td width="8%" style="text-align: center;">% N.E.A.</td>

                    <td width="9%" style="text-align: center;">Atendidos</td>

                    <td width="8%" style="text-align: center;">N.E.A.</td>

                    <td width="8%" style="text-align: center;">% N.E.A.</td>

                    <td width="9%" style="text-align: center;">Atendidos</td>

                    <td width="8%" style="text-align: center;">N.E.A.</td>

                    <td width="8%" style="text-align: center;">% N.E.A.</td>

                    <td width="9%" style="text-align: center;">Atendidos</td>

                    <td width="8%" style="text-align: center;">N.E.A.</td>

                    <td width="8%" style="text-align: center;">% N.E.A.</td>

                </tr>

            </thead>

            <tbody>'

                .numeroPacientesConIndicacionFinal($pacientesCierre, $pacientesNEA).

            '</tbody>

        </table>

    ';

}



function numeroPacientesConIndicacionFinal ( $pacientesCierre, $pacientesNEA ) {

    return '

        <tr>

            <td width="9%" style="text-align: center;">'.desplegarNumero($pacientesCierre['totalPacientesAdultoCierre']).'</td>

            <td width="8%" style="text-align: center;">'.desplegarNumero($pacientesNEA['totalPacientesAdultoNEA']).'</td>

            <td width="8%" style="text-align: center;">'.desplegarNumero(floor(($pacientesNEA['totalPacientesAdultoNEA'] * 100) / $pacientesCierre['totalPacientesAdultoCierre'])).'%</td>

            <td width="9%" style="text-align: center;">'.desplegarNumero($pacientesCierre['totalPacientesPediatricoCierre']).'</td>

            <td width="8%" style="text-align: center;">'.desplegarNumero($pacientesNEA['totalPacientesPediatricoNEA']).'</td>

            <td width="8%" style="text-align: center;">'.desplegarNumero(floor(($pacientesNEA['totalPacientesPediatricoNEA'] * 100) / $pacientesCierre['totalPacientesPediatricoCierre'])).'%</td>

            <td width="9%" style="text-align: center;">'.desplegarNumero($pacientesCierre['totalPacientesGinecologicoCierre']).'</td>

            <td width="8%" style="text-align: center;">'.desplegarNumero($pacientesNEA['totalPacientesGinecologicoNEA']).'</td>

            <td width="8%" style="text-align: center;">'.desplegarNumero(floor(($pacientesNEA['totalPacientesGinecologicoNEA'] * 100) / $pacientesCierre['totalPacientesGinecologicoCierre'])).'%</td>

            <td width="9%" style="text-align: center;">'.desplegarNumero(($pacientesCierre['totalPacientesAdultoCierre'] + $pacientesCierre['totalPacientesPediatricoCierre'] + $pacientesCierre['totalPacientesGinecologicoCierre'])).'</td>

            <td width="8%" style="text-align: center;">'.desplegarNumero(($pacientesNEA['totalPacientesAdultoNEA'] + $pacientesNEA['totalPacientesPediatricoNEA'] + $pacientesNEA['totalPacientesGinecologicoNEA'])).'</td>

            <td width="8%" style="text-align: center;">'.desplegarNumero(floor((($pacientesNEA['totalPacientesAdultoNEA'] + $pacientesNEA['totalPacientesPediatricoNEA'] + $pacientesNEA['totalPacientesGinecologicoNEA']) * 100) / ($pacientesCierre['totalPacientesAdultoCierre'] + $pacientesCierre['totalPacientesPediatricoCierre'] + $pacientesCierre['totalPacientesGinecologicoCierre']))).'%</td>

        </tr>

    ';

}



function pdfNumeroAtencionesPorProfesional ( $objCon, $objReporte, $fechas ) {

    $adultoPediatrico   = $objReporte->numeroAtencionesPorMedico($objCon, $fechas);

    $ginecoObstetra     = $objReporte->numeroAtencionesPorGinecologo($objCon, $fechas);

    $matrona            = $objReporte->numeroAtencionesPorMatrona($objCon, $fechas);

    return '

        <table border="1" cellpadding="2">

            <thead>

                <tr class="titulo">

                    <td colspan="5" width="100%">Número Atenciones por Tipo Profesional</td>

                </tr>

                <tr class="titulo">

                    <td width="20%" style="text-align: center;">Tipo Profesional</td>

                    <td width="20%" style="text-align: center;">Hombre</td>

                    <td width="20%" style="text-align: center;">Mujeres</td>

                    <td width="20%" style="text-align: center;">Total</td>

                    <td width="20%" style="text-align: center;">Beneficiarios</td>

                </tr>

            </thead>

            <tbody>'

                .numeroAtencionesPorProfesional( $adultoPediatrico, $ginecoObstetra, $matrona).

            '</tbody>

        </table>

    ';

}



function numeroAtencionesPorProfesional ( $adultoPediatrico, $ginecoObstetra, $matrona ) {

    return '
            <tr>

                <td>Médico</td>

                <td style="text-align: center;">'.filtrarPacientesPorSexo($adultoPediatrico, 'M').'</td>

                <td style="text-align: center;">'.filtrarPacientesPorSexo($adultoPediatrico, 'F').'</td>

                <td style="text-align: center;">'.count($adultoPediatrico).'</td>

                <td style="text-align: center;">'.filtrarPacientesPorPrevision($adultoPediatrico).'</td>

            </tr>

            <tr>

                <td>Ginecólogo</td>

                <td style="text-align: center;">'.filtrarPacientesPorSexo($ginecoObstetra, 'M').'</td>

                <td style="text-align: center;">'.filtrarPacientesPorSexo($ginecoObstetra, 'F').'</td>

                <td style="text-align: center;">'.count($ginecoObstetra).'</td>

                <td style="text-align: center;">'.filtrarPacientesPorPrevision($ginecoObstetra).'</td>

            </tr>

            <tr>

                <td>Matrona</td>

                <td style="text-align: center;">'.filtrarPacientesPorSexo($matrona, 'M').'</td>

                <td style="text-align: center;">'.filtrarPacientesPorSexo($matrona, 'F').'</td>

                <td style="text-align: center;">'.count($matrona).'</td>

                <td style="text-align: center;">'.filtrarPacientesPorPrevision($matrona).'</td>

            </tr>

        ';

}



function pdfNumeroPacientesPorDiagnosticos ( $objCon, $objReporte, $fechas ) {

    $pacientesDiarreasAgudas = $objReporte->numeroPacientesDiagnosticoDiarreasAgudas($objCon, $fechas);

    $pacientesInfeccionesRespiratoriasAgudas = $objReporte->numeroPacientesDiagnosticoInfeccionesRespiratoriasAgudas($objCon, $fechas);

    $pacientesOtrosDiagnosticos= $objReporte->numeroPacientesOtrosDiagnosticos($objCon, $fechas);

    return '

        <table border="1" cellpadding="2">

            <thead>

                <tr class="titulo">

                    <td width="25%">Número de Pacientes por Diagnósticos</td>

                    <td width="25%" style="text-align: center;">Adultos</td>

                    <td width="25%" style="text-align: center;">Pediátricos</td>

                    <td width="25%" style="text-align: center;">Total</td>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td width="25%">Diarreas Agudas</td>'

                    .numeroPacientesDiagnosticoDiarreasAgudas($pacientesDiarreasAgudas).

                '</tr>

                <tr>

                    <td width="25%">Infecciones Respiratorias Agudas</td>'

                    .numeroPacientesInfeccionesRespiratoriasAgudas($pacientesInfeccionesRespiratoriasAgudas).

                '</tr>

                <tr>

                    <td width="25%">Otros Diagnósticos</td>'

                    .numeroPacientesOtrosDiagnosticos($pacientesOtrosDiagnosticos).

                '</tr>

                <tr>

                    <td width="25%">Total Por Pacientes</td>'

                    .totalNumerosPacientesDiagnosticos($pacientesDiarreasAgudas, $pacientesInfeccionesRespiratoriasAgudas, $pacientesOtrosDiagnosticos).

                '</tr>

            </tbody>

        </table>

    ';

}



function numeroPacientesDiagnosticoDiarreasAgudas ( $pacientesDiarreasAgudas ) {

    return '

        <td width="25%" style="text-align: center;">'.desplegarNumero($pacientesDiarreasAgudas['totalPacientesAdultosDiarreasAgudas']).'</td>

        <td width="25%" style="text-align: center;">'.desplegarNumero($pacientesDiarreasAgudas['totalPacientesPediatricosDiarreasAgudas']).'</td>

        <td width="25%" style="text-align: center;">'.desplegarNumero(($pacientesDiarreasAgudas['totalPacientesAdultosDiarreasAgudas'] + $pacientesDiarreasAgudas['totalPacientesPediatricosDiarreasAgudas'])).'</td>

    ';

}



function numeroPacientesInfeccionesRespiratoriasAgudas ( $pacientesInfeccionesRespiratoriasAgudas ) {

    return '

        <td width="25%" style="text-align: center;">'.desplegarNumero($pacientesInfeccionesRespiratoriasAgudas['totalPacientesAdultosRespiratoriasAgudas']).'</td>

        <td width="25%" style="text-align: center;">'.desplegarNumero($pacientesInfeccionesRespiratoriasAgudas['totalPacientesPediatricosRespiratoriasAgudas']).'</td>

        <td width="25%" style="text-align: center;">'.desplegarNumero(($pacientesInfeccionesRespiratoriasAgudas['totalPacientesAdultosRespiratoriasAgudas'] + $pacientesInfeccionesRespiratoriasAgudas['totalPacientesPediatricosRespiratoriasAgudas'])).'</td>

    ';

}



function numeroPacientesOtrosDiagnosticos ( $pacientesOtrosDiagnosticos ) {

    return '

        <td width="25%" style="text-align: center;">'.desplegarNumero($pacientesOtrosDiagnosticos['totalPacientesAdultosOtrosDiagnosticos']).'</td>

        <td width="25%" style="text-align: center;">'.desplegarNumero($pacientesOtrosDiagnosticos['totalPacientesPediatricosOtrosDiagnosticos']).'</td>

        <td width="25%" style="text-align: center;">'.desplegarNumero(($pacientesOtrosDiagnosticos['totalPacientesAdultosOtrosDiagnosticos'] + $pacientesOtrosDiagnosticos['totalPacientesPediatricosOtrosDiagnosticos'])).'</td>

    ';

}



function totalNumerosPacientesDiagnosticos ( $pacientesDiarreasAgudas, $pacientesInfeccionesRespiratoriasAgudas, $pacientesOtrosDiagnosticos ) {

    return '

        <td width="25%" style="text-align: center;">'.desplegarNumero($pacientesDiarreasAgudas['totalPacientesAdultosDiarreasAgudas'] + $pacientesInfeccionesRespiratoriasAgudas['totalPacientesAdultosRespiratoriasAgudas'] + $pacientesOtrosDiagnosticos['totalPacientesAdultosOtrosDiagnosticos']).'</td>

        <td width="25%" style="text-align: center;">'.desplegarNumero($pacientesDiarreasAgudas['totalPacientesPediatricosDiarreasAgudas'] +  $pacientesInfeccionesRespiratoriasAgudas['totalPacientesPediatricosRespiratoriasAgudas'] + $pacientesOtrosDiagnosticos['totalPacientesPediatricosOtrosDiagnosticos']).'</td>

        <td width="25%" style="text-align: center;">'.desplegarNumero($pacientesDiarreasAgudas['totalPacientesAdultosDiarreasAgudas'] +  $pacientesInfeccionesRespiratoriasAgudas['totalPacientesAdultosRespiratoriasAgudas'] + $pacientesOtrosDiagnosticos['totalPacientesAdultosOtrosDiagnosticos'] + $pacientesDiarreasAgudas['totalPacientesPediatricosDiarreasAgudas'] + $pacientesInfeccionesRespiratoriasAgudas['totalPacientesPediatricosRespiratoriasAgudas'] + $pacientesOtrosDiagnosticos['totalPacientesPediatricosOtrosDiagnosticos']).'</td>

    ';

}



function pdfNumeroPacientesPorCategorizacion ( $objCon, $objReporte, $fechas ) {

    return '

        <table border="1" cellpadding="2">

            <thead>

                <tr class="titulo">

                    <td rowspan="2" width="25%">Número de Pacientes por Categorización</td>

                    <td width="25%" style="text-align: center;">Adultos</td>

                    <td width="25%" style="text-align: center;">Pediátricos</td>

                    <td width="25%" style="text-align: center;">Total</td>

                </tr>

                <tr class="titulo">

                    <td width="8%" style="text-align: center;">Cantidad</td>

                    <td width="9%" style="text-align: center;">Intravenoso</td>

                    <td width="8%" style="text-align: center;">%</td>

                    <td width="8%" style="text-align: center;">Cantidad</td>

                    <td width="9%" style="text-align: center;">Intravenoso</td>

                    <td width="8%" style="text-align: center;">%</td>

                    <td width="8%" style="text-align: center;">Cantidad</td>

                    <td width="9%" style="text-align: center;">Intravenoso</td>

                    <td width="8%" style="text-align: center;">%</td>

                </tr>

            </thead>

            <tbody>'

                .numeroPacientesPorCategorizacion($objCon, $objReporte, $fechas).


            '</tbody>

        </table>

    ';

}



function numeroPacientesPorCategorizacion ( $objCon, $objReporte, $fechas ) {

    $textoADesplegar = '';

    $tipoCategorizaciones = array('ESI-1', 'ESI-2', 'ESI-3', 'ESI-4', 'ESI-5');

    $totalPacientesPorCategorizacion = count($tipoCategorizaciones);

    $parametrosAEnviar[] = array();

    $parametrosAEnviar['fechaAnterior'] = $fechas['fechaAnterior'];

    $parametrosAEnviar['fechaActual'] = $fechas['fechaActual'];

    $totalAdultosCategorizados = 0;

    $totalPediatricosCategorizados = 0;

    $totalAdultosIntravenosos = 0;

    $totalPediatricosIntravenoso = 0;

    for ( $i = 0; $i < $totalPacientesPorCategorizacion; $i++ ) {

        $parametrosAEnviar['tipoCategorizacion'] = $tipoCategorizaciones[$i];

        $pacientesCategorizados = $objReporte->numeroPacientesPorCategorizacion($objCon, $parametrosAEnviar);

        $pacientesIntravenosos = $objReporte->numeroPacientesPorIntravenosos($objCon, $parametrosAEnviar);

        $totalAdultosCategorizados += $pacientesCategorizados['totalPacientesAdultoCategorizados'];

        $totalPediatricosCategorizados += $pacientesCategorizados['totalPacientesPediatricoCategorizados'];

        $totalAdultosIntravenosos += $pacientesIntravenosos['totalPacientesAdultoIntravenoso'];

        $totalPediatricosIntravenoso += $pacientesIntravenosos['totalPacientesPediatricoIntravenoso'];

        $textoADesplegar .= '

            <tr>

                <td width="25%" style="text-align: center;">'.$tipoCategorizaciones[$i].'</td>

                <td width="8%" style="text-align: center;">'.desplegarNumero($pacientesCategorizados['totalPacientesAdultoCategorizados']).'</td>

                <td width="9%" style="text-align: center;">'.desplegarNumero($pacientesIntravenosos['totalPacientesAdultoIntravenoso']).'</td>

                <td width="8%" style="text-align: center;">'.desplegarNumero(floor((($pacientesIntravenosos['totalPacientesAdultoIntravenoso'] * 100) / $pacientesCategorizados['totalPacientesAdultoCategorizados']))).'%</td>

                <td width="8%" style="text-align: center;">'.desplegarNumero($pacientesCategorizados['totalPacientesPediatricoCategorizados']).'</td>

                <td width="9%" style="text-align: center;">'.desplegarNumero($pacientesIntravenosos['totalPacientesPediatricoIntravenoso']).'</td>

                <td width="8%" style="text-align: center;">'.desplegarNumero(floor((($pacientesIntravenosos['totalPacientesPediatricoIntravenoso'] * 100) / $pacientesCategorizados['totalPacientesPediatricoCategorizados']))).'%</td>

                <td width="8%" style="text-align: center;">'.desplegarNumero(($pacientesCategorizados['totalPacientesAdultoCategorizados'] + $pacientesCategorizados['totalPacientesPediatricoCategorizados'])).'</td>

                <td width="9%" style="text-align: center;">'.desplegarNumero(($pacientesIntravenosos['totalPacientesAdultoIntravenoso'] + $pacientesIntravenosos['totalPacientesPediatricoIntravenoso'])).'</td>

                <td width="8%" style="text-align: center;">'.desplegarNumero(floor((( ($pacientesIntravenosos['totalPacientesAdultoIntravenoso'] + $pacientesIntravenosos['totalPacientesPediatricoIntravenoso']) * 100) / ($pacientesCategorizados['totalPacientesAdultoCategorizados'] + $pacientesCategorizados['totalPacientesPediatricoCategorizados'])))).'%</td>

            </tr>

        ';

    }

    unset($parametrosAEnviar);

    $textoADesplegar .= '

        <tr>

            <td width="25%" style="text-align: center;">Total</td>

            <td width="8%" style="text-align: center;">'.desplegarNumero($totalAdultosCategorizados).'</td>

            <td width="9%" style="text-align: center;">'.desplegarNumero($totalAdultosIntravenosos).'</td>

            <td width="8%" style="text-align: center;">&nbsp;</td>

            <td width="8%" style="text-align: center;">'.desplegarNumero($totalPediatricosCategorizados).'</td>

            <td width="9%" style="text-align: center;">'.desplegarNumero($totalPediatricosIntravenoso).'</td>

            <td width="8%" style="text-align: center;">&nbsp;</td>

            <td width="8%" style="text-align: center;">'.desplegarNumero(($totalAdultosCategorizados + $totalPediatricosCategorizados)).'</td>

            <td width="9%" style="text-align: center;">'.desplegarNumero(($totalAdultosIntravenosos + $totalPediatricosIntravenoso)).'</td>

            <td width="8%" style="text-align: center;">&nbsp;</td>

        </tr>

    ';

    return $textoADesplegar;

}



function pdfNumeroPacientesConIndicacionHospitalizacion ( $objCon, $objReporte, $fechas ) {

    $pacientesCierre = $objReporte->numeroPacientesConIndicacionFinal($objCon, $fechas);

    $pacientesHospitalizados = $objReporte->numeroPacientesConIndicacionHospitalizacion($objCon, $fechas);

    return '

        <table border="1" cellpadding="2">

            <thead>

                <tr class="titulo">

                    <td colspan="4" width="100%">Número de Pacientes con Indicación de Hospitalización</td>

                </tr>

                <tr class="titulo">

                    <td width="25%" style="text-align: center;">Adulto</td>

                    <td width="25%" style="text-align: center;">Pediátrico</td>

                    <td width="25%" style="text-align: center;">Ginecológico</td>

                    <td width="25%" style="text-align: center;">Total</td>

                </tr>

                <tr class="titulo">

                    <td width="9%" style="text-align: center;">Atendidos</td>

                    <td width="8%" style="text-align: center;">Ind. Hosp.</td>

                    <td width="8%" style="text-align: center;">%</td>

                    <td width="9%" style="text-align: center;">Atendidos</td>

                    <td width="8%" style="text-align: center;">Ind. Hosp.</td>

                    <td width="8%" style="text-align: center;">%</td>

                    <td width="9%" style="text-align: center;">Atendidos</td>

                    <td width="8%" style="text-align: center;">Ind. Hosp.</td>

                    <td width="8%" style="text-align: center;">%</td>

                    <td width="9%" style="text-align: center;">Atendidos</td>

                    <td width="8%" style="text-align: center;">Ind. Hosp.</td>

                    <td width="8%" style="text-align: center;">%</td>

                </tr>

            </thead>

            <tbody>'

                .numeroPacientesConIndicacionHospitalizacion($pacientesCierre, $pacientesHospitalizados).

            '</tbody>

        </table>

    ';

}



function numeroPacientesConIndicacionHospitalizacion ( $pacientesCierre, $pacientesHospitalizados ) {

    return '

        <tr>

            <td width="9%" style="text-align: center;">'.desplegarNumero($pacientesCierre['totalPacientesAdultoCierre']).'</td>

            <td width="8%" style="text-align: center;">'.desplegarNumero($pacientesHospitalizados['totalPacientesAdultoHospitalizado']).'</td>

            <td width="8%" style="text-align: center;">'.desplegarNumero(floor(($pacientesHospitalizados['totalPacientesAdultoHospitalizado'] * 100) / $pacientesCierre['totalPacientesAdultoCierre'])).'%</td>

            <td width="9%" style="text-align: center;">'.desplegarNumero($pacientesCierre['totalPacientesPediatricoCierre']).'</td>

            <td width="8%" style="text-align: center;">'.desplegarNumero($pacientesHospitalizados['totalPacientesPediatricoHospitalizado']).'</td>

            <td width="8%" style="text-align: center;">'.desplegarNumero(floor(($pacientesHospitalizados['totalPacientesPediatricoHospitalizado'] * 100) / $pacientesCierre['totalPacientesPediatricoCierre'])).'%</td>

            <td width="9%" style="text-align: center;">'.desplegarNumero($pacientesCierre['totalPacientesGinecologicoCierre']).'</td>

            <td width="8%" style="text-align: center;">'.desplegarNumero($pacientesHospitalizados['totalPacientesGinecologicoHospitalizado']).'</td>

            <td width="8%" style="text-align: center;">'.desplegarNumero(floor(($pacientesHospitalizados['totalPacientesGinecologicoHospitalizado'] * 100) / $pacientesCierre['totalPacientesGinecologicoCierre'])).'%</td>

            <td width="9%" style="text-align: center;">'.desplegarNumero(($pacientesCierre['totalPacientesAdultoCierre'] + $pacientesCierre['totalPacientesPediatricoCierre'] + $pacientesCierre['totalPacientesGinecologicoCierre'])).'</td>

            <td width="8%" style="text-align: center;">'.desplegarNumero(($pacientesHospitalizados['totalPacientesAdultoHospitalizado'] + $pacientesHospitalizados['totalPacientesPediatricoHospitalizado'] + $pacientesHospitalizados['totalPacientesGinecologicoHospitalizado'])).'</td>

            <td width="8%" style="text-align: center;">'.desplegarNumero(floor((($pacientesHospitalizados['totalPacientesAdultoHospitalizado'] + $pacientesHospitalizados['totalPacientesPediatricoHospitalizado'] + $pacientesHospitalizados['totalPacientesGinecologicoHospitalizado']) * 100) / ($pacientesCierre['totalPacientesAdultoCierre'] + $pacientesCierre['totalPacientesPediatricoCierre'] + $pacientesCierre['totalPacientesGinecologicoCierre']))).'%</td>

        </tr>

    ';

}



function pdfTiemposMaximosCategorizacionTabla ( $objCon, $objReporte, $fechas ) {

    return '

        <table border="1" cellpadding="0">

            <thead>

                <tr class="titulo">

                    <td colspan="17" width="100%">Tiempo Máximo de Espera por cada Dos Horas, Últimas 24 Horas</td>

                </tr>

                <tr class="titulo">

                    <td rowspan="2" width="10%" style="text-align: center;">F/H</td>

                    <td width="30%" style="text-align: center;">Adulto</td>

                    <td width="30%" style="text-align: center;">Pediátrico</td>

                    <td width="30%" style="text-align: center;">Ginecológico</td>

                </tr>

                <tr class="titulo">

                    <td width="6%" style="text-align: center;">ESI-1</td>

                    <td width="6%" style="text-align: center;">ESI-2</td>

                    <td width="6%" style="text-align: center;">ESI-3</td>

                    <td width="6%" style="text-align: center;">ESI-4</td>

                    <td width="6%" style="text-align: center;">ESI-5</td>

                    <td width="6%" style="text-align: center;">ESI-1</td>

                    <td width="6%" style="text-align: center;">ESI-2</td>

                    <td width="6%" style="text-align: center;">ESI-3</td>

                    <td width="6%" style="text-align: center;">ESI-4</td>

                    <td width="6%" style="text-align: center;">ESI-5</td>

                    <td width="6%" style="text-align: center;">ESI-1</td>

                    <td width="6%" style="text-align: center;">ESI-2</td>

                    <td width="6%" style="text-align: center;">ESI-3</td>

                    <td width="6%" style="text-align: center;">ESI-4</td>

                    <td width="6%" style="text-align: center;">ESI-5</td>

                </tr>

            </thead>

            <tbody>'

                .desplegarTiemposMaximosPorCategorizacion($objCon, $objReporte, $fechas).

            '</tbody>

        </table>

    ';

}



function desplegarTiemposMaximosPorCategorizacion ( $objCon, $objReporte, $fechas ) {

    $textoADesplegar = '';

    $parametrosAEnviar[] = array();

    $parametrosAEnviar['fechaAnterior'] = $fechas['fechaAnterior'];

    $parametrosAEnviar['fechaActual'] = $fechas['fechaActual'];

    $parametrosAEnviar['tipoPaciente'] = 1;

    $tiemposMaximosAdulto = $objReporte->tiemposMaximosPorCategorizacion($objCon, $parametrosAEnviar);

    $parametrosAEnviar['tipoPaciente'] = 2;

    $tiemposMaximosPediatricos = $objReporte->tiemposMaximosPorCategorizacion($objCon, $parametrosAEnviar);

    $parametrosAEnviar['tipoPaciente'] = 3;

    $tiemposPromediosGinecologico = $objReporte->tiemposMaximosPorCategorizacion($objCon, $parametrosAEnviar);

    $totalTiemposMaximo = count($tiemposMaximosAdulto);

    for ( $i = 0; $i < $totalTiemposMaximo; $i = $i+2 ) {

        $textoADesplegar .= '

            <tr>

                <td width="10%" style="text-align: center;">'.date('d-m-Y H:i:s', strtotime($tiemposMaximosAdulto[$i]['fechaHora'])).'</td>

                <td width="6%" style="text-align: center; background-color: #7FFFD4;">'.segundosAHoraMinutos($tiemposMaximosAdulto[$i]['tiempoMaximoESI1']).'</td>

                <td width="6%" style="text-align: center; background-color: #7FFFD4;">'.segundosAHoraMinutos($tiemposMaximosAdulto[$i]['tiempoMaximoESI2']).'</td>

                <td width="6%" style="text-align: center; background-color: #7FFFD4;">'.segundosAHoraMinutos($tiemposMaximosAdulto[$i]['tiempoMaximoESI3']).'</td>

                <td width="6%" style="text-align: center; background-color: #7FFFD4;">'.segundosAHoraMinutos($tiemposMaximosAdulto[$i]['tiempoMaximoESI4']).'</td>

                <td width="6%" style="text-align: center; background-color: #7FFFD4;">'.segundosAHoraMinutos($tiemposMaximosAdulto[$i]['tiempoMaximoESI5']).'</td>

                <td width="6%" style="text-align: center; background-color: #F0E68C;">'.segundosAHoraMinutos($tiemposMaximosPediatricos[$i]['tiempoMaximoESI1']).'</td>

                <td width="6%" style="text-align: center; background-color: #F0E68C;">'.segundosAHoraMinutos($tiemposMaximosPediatricos[$i]['tiempoMaximoESI2']).'</td>

                <td width="6%" style="text-align: center; background-color: #F0E68C;">'.segundosAHoraMinutos($tiemposMaximosPediatricos[$i]['tiempoMaximoESI3']).'</td>

                <td width="6%" style="text-align: center; background-color: #F0E68C;">'.segundosAHoraMinutos($tiemposMaximosPediatricos[$i]['tiempoMaximoESI4']).'</td>

                <td width="6%" style="text-align: center; background-color: #F0E68C;">'.segundosAHoraMinutos($tiemposMaximosPediatricos[$i]['tiempoMaximoESI5']).'</td>

                <td width="6%" style="text-align: center; background-color: #e74c3c;">'.segundosAHoraMinutos($tiemposPromediosGinecologico[$i]['tiempoMaximoESI1']).'</td>

                <td width="6%" style="text-align: center; background-color: #e74c3c;">'.segundosAHoraMinutos($tiemposPromediosGinecologico[$i]['tiempoMaximoESI2']).'</td>

                <td width="6%" style="text-align: center; background-color: #e74c3c;">'.segundosAHoraMinutos($tiemposPromediosGinecologico[$i]['tiempoMaximoESI3']).'</td>

                <td width="6%" style="text-align: center; background-color: #e74c3c;">'.segundosAHoraMinutos($tiemposPromediosGinecologico[$i]['tiempoMaximoESI4']).'</td>

                <td width="6%" style="text-align: center; background-color: #e74c3c;">'.segundosAHoraMinutos($tiemposPromediosGinecologico[$i]['tiempoMaximoESI5']).'</td>

            </tr>

        ';

    }

    return $textoADesplegar;

}



function pdfTiemposEsperaPorProfesional ( $objCon, $objReporte, $fechas ) {

    return '

        <table border="1" cellpadding="2">

            <thead>

                <tr class="titulo">

                    <td colspan="10" width="100%">Tiempo Espera Por Profesional</td>

                </tr>

                <tr class="titulo">

                    <td rowspan="2" width="20%" style="text-align: center;">Nombre Profesional</td>

                    <td width="50%" style="text-align: center;">Cantidad de Pacientes</td>

                    <td width="30%" style="text-align: center;">Tiempos de Espera (Minutos)</td>

                </tr>

                <tr class="titulo">

                    <td width="8%" style="text-align: center;">ESI-1</td>

                    <td width="8%" style="text-align: center;">ESI-2</td>

                    <td width="8%" style="text-align: center;">ESI-3</td>

                    <td width="8%" style="text-align: center;">ESI-4</td>

                    <td width="8%" style="text-align: center;">ESI-5</td>

                    <td width="10%" style="text-align: center;">Atendidos</td>

                    <td width="10%" style="text-align: center;">Promedio</td>

                    <td width="10%" style="text-align: center;">Menor</td>

                    <td width="10%" style="text-align: center;">Mayor</td>

                </tr>

            </thead>

            <tbody>'

                .desplegarTiemposEsperaPorProfesional($objCon, $objReporte, $fechas).

            '</tbody>

        </table>

    ';

}



function desplegarTiemposEsperaPorProfesional ( $objCon, $objReporte, $fechas ) {

    $textoADesplegar = '';

    $tiemposEspera = $objReporte->tiemposEsperaPorProfesional($objCon, $fechas);

    $totalTiemposEspera = count($tiemposEspera);

    for ( $i = 0; $i < $totalTiemposEspera; $i++ ) {

        $textoADesplegar .= '

            <tr>

                <td width="20%" style="text-align: center;">'.ucwords(mb_strtolower($tiemposEspera[$i]['nombreProfesional'], "UTF-8")).'</td>

                <td width="8%" style="text-align: center;">'.$tiemposEspera[$i]['cantidadPacientesESI1'].'</td>

                <td width="8%" style="text-align: center;">'.$tiemposEspera[$i]['cantidadPacientesESI2'].'</td>

                <td width="8%" style="text-align: center;">'.$tiemposEspera[$i]['cantidadPacientesESI3'].'</td>

                <td width="8%" style="text-align: center;">'.$tiemposEspera[$i]['cantidadPacientesESI4'].'</td>

                <td width="8%" style="text-align: center;">'.$tiemposEspera[$i]['cantidadPacientesESI5'].'</td>

                <td width="10%" style="text-align: center;">'.$tiemposEspera[$i]['totalPacientes'].'</td>

                <td width="10%" style="text-align: center;">'.$tiemposEspera[$i]['tiempoPromedio'].'</td>

                <td width="10%" style="text-align: center;">'.$tiemposEspera[$i]['tiempoMinimo'].'</td>

                <td width="10%" style="text-align: center;">'.$tiemposEspera[$i]['tiempoMaximo'].'</td>

            </tr>

        ';

    }

    return $textoADesplegar;

}



function pdfTiemposMaximosCategorizacionGrafico ( $objCon, $objReporte, $fechas, $tipoPaciente ) {

    $parametrosAEnviar['fechaAnterior'] = $fechas['fechaAnterior'];

    $parametrosAEnviar['fechaActual'] = $fechas['fechaActual'];

    $parametrosAEnviar['tipoPaciente'] = $tipoPaciente;

    $tiemposMaximo = $objReporte->tiemposMaximosPorCategorizacion($objCon, $parametrosAEnviar);

    $chart  = new LineChart();

    $serie1 = new XYDataSet();

    $serie2 = new XYDataSet();

    $serie3 = new XYDataSet();

    $serie4 = new XYDataSet();

    $serie5 = new XYDataSet();

    $serie6 = new XYDataSet();

    for ( $i = 0; $i < count($tiemposMaximo) ; $i++) {

        $serie1->addPoint(new Point($tiemposMaximo[$i]['hora'], segundosAMinutos($tiemposMaximo[$i]['tiempoMaximoESI1'])));

        $serie2->addPoint(new Point($tiemposMaximo[$i]['hora'], segundosAMinutos($tiemposMaximo[$i]['tiempoMaximoESI2'])));

        $serie3->addPoint(new Point($tiemposMaximo[$i]['hora'], segundosAMinutos($tiemposMaximo[$i]['tiempoMaximoESI3'])));

        $serie4->addPoint(new Point($tiemposMaximo[$i]['hora'], segundosAMinutos($tiemposMaximo[$i]['tiempoMaximoESI4'])));

        $serie5->addPoint(new Point($tiemposMaximo[$i]['hora'], segundosAMinutos($tiemposMaximo[$i]['tiempoMaximoESI5'])));

    }

    $dataSet = new XYSeriesDataSet();

    $dataSet->addSerie("ESI-1", $serie1);

    $dataSet->addSerie("ESI-2", $serie2);

    $dataSet->addSerie("ESI-3", $serie3);

    $dataSet->addSerie("ESI-4", $serie4);

    $dataSet->addSerie("ESI-5", $serie5);

    $chart->setDataSet($dataSet);

    if ( $tipoPaciente == 1 ) {

        $chart->setTitle("Tiempo de espera máximo 24 hrs, desde Categorización a Inicio de Atención, Adulto");

    } else {

        $chart->setTitle("Tiempo de espera máximo 24 hrs, desde Categorización a Inicio de Atención, Pediátrico");

    }

    $chart->getPlot()->setGraphCaptionRatio(0.62);

    if ( $tipoPaciente == 1 ) {

        $chart->render("../graficos/graficoTiempoMaximoCategorizacionAdulto.png");

        $urlImagen = PATH.'/views/reportes/graficos/graficoTiempoMaximoCategorizacionAdulto.png';

    } else {

         $chart->render("../graficos/graficoTiempoMaximoCategorizacionPediatrico.png");

         $urlImagen = PATH.'/views/reportes/graficos/graficoTiempoMaximoCategorizacionPediatrico.png';

    }

    return '

        <table border="0" cellpadding="0">

            <tr>

                <td width="5%" style="text-align: center; font-weight: bold;">

                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    M<br>
                    I<br>
                    N<br>
                    U<br>
                    T<br>
                    O<br>
                    S<br>

                </td>

                <td width="90%" style="text-align: center;">

                    <img src="'.$urlImagen.'" width="800px" height="300px" />

                </td>

            </tr>

            <tr>

                <td>&nbsp;</td>

                <td style="text-align: center; font-weight: bold;">HORARIO TRANSCURRIDO</td>

            </tr>

        </table>';

}



function pdfNumeroHospitalizacionesUrgencia ( $objCon, $objReporte, $fechas ) {

    return '

        <table border="1" cellpadding="2">

            <thead>

                <tr class="titulo">

                    <td width="100%">Cantidad de Pacientes Hospitalizados en Urgencia</td>

                </tr>

                <tr class="titulo">

                    <td width="20%" rowspan="2" style="text-align: center;">Fechas</td>

                    <td width="40%" style="text-align: center;">Adulto</td>

                    <td width="40%" style="text-align: center;">Pediátrico</td>

                </tr>

                <tr class="titulo">

                    <td width="10%" style="text-align: center;">Menos 12 Hrs</td>

                    <td width="10%" style="text-align: center;">12 a 24 Hrs</td>

                    <td width="10%" style="text-align: center;">Más 24 Hrs</td>

                    <td width="10%" style="text-align: center;">Total</td>

                    <td width="10%" style="text-align: center;">Menos 12 Hrs</td>

                    <td width="10%" style="text-align: center;">12 a 24 Hrs</td>

                    <td width="10%" style="text-align: center;">Más 24 Hrs</td>

                    <td width="10%" style="text-align: center;">Total</td>

                </tr>

            </thead>

            <tbody>'

                .desplegarNumeroHospitalizacionesUrgencia($objCon, $objReporte, $fechas ).

            '</tbody>

        </table>

    ';

}



function desplegarNumeroHospitalizacionesUrgencia ( $objCon, $objReporte, $fechas ) {

    insertarHospitalizacionesUrgencia($objCon, $objReporte, $fechas);

    $cantidadPacientes = $objReporte->cantidadPacientesHospitalizacionesUrgencia($objCon);

    $textoADesplegar = '';

    $totalCantidadPacientes = count($cantidadPacientes);

    for ( $i = 0; $i < $totalCantidadPacientes; $i++ ) {

        $totalAdultos = $cantidadPacientes[$i]['numeroHospitalizacionesAdulto'] + $cantidadPacientes[$i]['numeroHospitalizacionesAdulto12'] + $cantidadPacientes[$i]['numeroHospitalizacionesAdulto24'];

        $totalPediatrico = $cantidadPacientes[$i]['numeroHospitalizacionesPediatrico'] + $cantidadPacientes[$i]['numeroHospitalizacionesPediatrico12'] + $cantidadPacientes[$i]['numeroHospitalizacionesPediatrico24'];

        $textoADesplegar .= '

            <tr>

                <td width="20%" style="text-align: center;">'.date('d-m-Y H:i:s', strtotime($cantidadPacientes[$i]['fechaHasta'])).'</td>

                <td width="5%" style="text-align: center;">'.desplegarNumero($cantidadPacientes[$i]['numeroHospitalizacionesAdulto']).'</td>

                <td width="5%" style="text-align: center;">'.desplegarNumero(floor(($cantidadPacientes[$i]['numeroHospitalizacionesAdulto'] * 100) / $totalAdultos)).'%</td>

                <td width="5%" style="text-align: center;">'.desplegarNumero($cantidadPacientes[$i]['numeroHospitalizacionesAdulto12']).'</td>

                <td width="5%" style="text-align: center;">'.desplegarNumero(floor(($cantidadPacientes[$i]['numeroHospitalizacionesAdulto12'] * 100) / $totalAdultos)).'%</td>

                <td width="5%" style="text-align: center;">'.desplegarNumero($cantidadPacientes[$i]['numeroHospitalizacionesAdulto24']).'</td>

                <td width="5%" style="text-align: center;">'.desplegarNumero(floor(($cantidadPacientes[$i]['numeroHospitalizacionesAdulto24'] * 100) / $totalAdultos)).'%</td>

                <td width="10%" style="text-align: center;">'.desplegarNumero($totalAdultos).'</td>

                <td width="5%" style="text-align: center;">'.desplegarNumero($cantidadPacientes[$i]['numeroHospitalizacionesPediatrico']).'</td>

                <td width="5%" style="text-align: center;">'.desplegarNumero(floor(($cantidadPacientes[$i]['numeroHospitalizacionesPediatrico'] * 100) / $totalPediatrico)).'%</td>

                <td width="5%" style="text-align: center;">'.desplegarNumero($cantidadPacientes[$i]['numeroHospitalizacionesPediatrico12']).'</td>

                <td width="5%" style="text-align: center;">'.desplegarNumero(floor(($cantidadPacientes[$i]['numeroHospitalizacionesPediatrico12'] * 100) / $totalPediatrico)).'%</td>

                <td width="5%" style="text-align: center;">'.desplegarNumero($cantidadPacientes[$i]['numeroHospitalizacionesPediatrico24']).'</td>

                <td width="5%" style="text-align: center;">'.desplegarNumero(floor(($cantidadPacientes[$i]['numeroHospitalizacionesPediatrico24'] * 100) / $totalPediatrico)).'%</td>

                <td width="10%" style="text-align: center;">'.desplegarNumero($totalPediatrico).'</td>

            </tr>

        ';

    }

    return $textoADesplegar;

}



function insertarHospitalizacionesUrgencia ( $objCon, $objReporte, $fechas ) {

    require_once("../../../class/TurnoCRUrgencia.class.php"); 	$objTurno   = new TurnoCRUrgencia();

    $parametrosAEnviar[] = array();

    $pacientesEsperando = $objTurno->obtenerNumeroHospitalizacionesUrgencia($objCon);

    $pacientesEsperando12 = $objTurno->obtenerNumeroHospitalizacionesUrgencia12($objCon);

    $pacientesEsperando24 = $objTurno->obtenerNumeroHospitalizacionesUrgencia24($objCon);

    $parametrosAEnviar['fechaAnterior'] = $fechas['fechaAnterior'];

    $parametrosAEnviar['fechaActual'] = $fechas['fechaActual'];

    $parametrosAEnviar['numeroHospitalizacionesAdulto'] = $pacientesEsperando['cantidadAdultoTotal'];

    $parametrosAEnviar['numeroHospitalizacionesAdulto12'] = $pacientesEsperando12['cantidadAdultoTotal'];

    $parametrosAEnviar['numeroHospitalizacionesAdulto24'] = $pacientesEsperando24['cantidadAdultoTotal'];

    $parametrosAEnviar['numeroHospitalizacionesPediactro'] = $pacientesEspserando['cantidadPediatricoTotal'];

    $parametrosAEnviar['numeroHospitalizacionesPediatrico12'] = $pacientesEsperando12['cantidadPediatricoTotal'];

    $parametrosAEnviar['numeroHospitalizacionesPediatrico24'] = $pacientesEsperando24['cantidadPediatricoTotal'];

    $objReporte->insertarHospitalizacionesUrgencia($objCon, $parametrosAEnviar);

    unset($parametrosAEnviar);

}



function pdfCantidadPacientesSegunEstado ( $objCon, $objReporte, $fechas ) {

    return '

        <table border="1" cellpadding="2">

            <thead>

                <tr class="titulo">

                    <td width="100%">Cantidad de Pacientes según Estado</td>

                </tr>

                <tr class="titulo">

                    <td rowspan="3" width="20%" style="text-align: center;">Horario</td>

                    <td width="80%" style="text-align: center;">Cantidad de Pacientes</td>

                </tr>

                <tr class="titulo">

                    <td width="16%" style="text-align: center;">Admisionados</td>

                    <td width="16%" style="text-align: center;">Categorizados</td>

                    <td width="16%" style="text-align: center;">Atención Iniciada</td>

                    <td width="16%" style="text-align: center;">Indicación Egreso</td>

                    <td width="16%" style="text-align: center;">Egresados</td>

                </tr>

                <tr class="titulo">

                    <td width="5%" style="text-align: center;">A</td>

                    <td width="5%" style="text-align: center;">P</td>

                    <td width="6%" style="text-align: center; ">G</td>

                    <td width="5%" style="text-align: center;">A</td>

                    <td width="5%" style="text-align: center;">P</td>

                    <td width="6%" style="text-align: center;">G</td>

                    <td width="5%" style="text-align: center;">A</td>

                    <td width="5%" style="text-align: center;">P</td>

                    <td width="6%" style="text-align: center;">G</td>

                    <td width="5%" style="text-align: center;">A</td>

                    <td width="5%" style="text-align: center;">P</td>

                    <td width="6%" style="text-align: center;">G</td>

                    <td width="5%" style="text-align: center;">A</td>

                    <td width="5%" style="text-align: center;">P</td>

                    <td width="6%" style="text-align: center;">G</td>

                </tr>

            </thead>

            <tbody>'

                .desplegarCantidadPacientesSegunTipoEstado($objCon, $objReporte, $fechas ).

            '</tbody>

        </table>

    ';

}



function desplegarCantidadPacientesSegunTipoEstado ( $objCon, $objReporte, $fechas ) {

    $textoADesplegar = '';

    $parametrosAEnviar[] = array();

    $parametrosAEnviar['fechaAnterior'] = $fechas['fechaAnterior'];

    $parametrosAEnviar['fechaActual'] = $fechas['fechaActual'];

    $parametrosAEnviar['tipoEstado'] = 'admision';

    $cantidadPacientesAdmisionados = $objReporte->cantidadPacientesSegunTipoEstado($objCon, $parametrosAEnviar);

    $parametrosAEnviar['tipoEstado'] = 'categorizados';

    $cantidadPacientesCategorizados = $objReporte->cantidadPacientesSegunTipoEstado($objCon, $parametrosAEnviar);

    $parametrosAEnviar['tipoEstado'] = 'inicioAtencion';

    $cantidadPacientesInicioAtencion = $objReporte->cantidadPacientesSegunTipoEstado($objCon, $parametrosAEnviar);

    $parametrosAEnviar['tipoEstado'] = 'indicacionEgreso';

    $cantidadPacientesIndicacionEgreso= $objReporte->cantidadPacientesSegunTipoEstado($objCon, $parametrosAEnviar);

    $parametrosAEnviar['tipoEstado'] = 'egresados';

    $cantidadPacientesEgresados = $objReporte->cantidadPacientesSegunTipoEstado($objCon, $parametrosAEnviar);

    unset($parametrosAEnviar);

    $horarios = array(
                            '08:00:00 - 08:59:59',
                            '09:00:00 - 09:59:59',
                            '10:00:00 - 10:59:59',
                            '11:00:00 - 11:59:59',
                            '12:00:00 - 12:59:59',
                            '13:00:00 - 13:59:59',
                            '14:00:00 - 14:59:59',
                            '15:00:00 - 15:59:59',
                            '16:00:00 - 16:59:59',
                            '17:00:00 - 17:59:59',
                            '18:00:00 - 18:59:59',
                            '19:00:00 - 19:59:59',
                            '20:00:00 - 20:59:59',
                            '21:00:00 - 21:59:59',
                            '22:00:00 - 22:59:59',
                            '23:00:00 - 23:59:59',
                            '00:00:00 - 00:59:59',
                            '01:00:00 - 01:59:59',
                            '02:00:00 - 02:59:59',
                            '03:00:00 - 03:59:59',
                            '04:00:00 - 04:59:59',
                            '05:00:00 - 05:59:59',
                            '06:00:00 - 06:59:59',
                            '07:00:00 - 07:59:59'
                        );

    $cantidades = array(
                            'cantidad08',
                            'cantidad09',
                            'cantidad10',
                            'cantidad11',
                            'cantidad12',
                            'cantidad13',
                            'cantidad14',
                            'cantidad15',
                            'cantidad16',
                            'cantidad17',
                            'cantidad18',
                            'cantidad19',
                            'cantidad20',
                            'cantidad21',
                            'cantidad22',
                            'cantidad23',
                            'cantidad00',
                            'cantidad01',
                            'cantidad02',
                            'cantidad03',
                            'cantidad04',
                            'cantidad05',
                            'cantidad06',
                            'cantidad07'
                        );

    for ( $i = 0; $i < count($horarios) ; $i++) {

        $textoADesplegar .= '

             <tr>

                <td width="20%" style="text-align: center;">'.$horarios[$i].'</td>

                <td width="5%" style="text-align: center; background-color: #7FFFD4;">'.$cantidadPacientesAdmisionados[0][$cantidades[$i]].'</td>

                <td width="5%" style="text-align: center; background-color: #F0E68C;">'.$cantidadPacientesAdmisionados[1][$cantidades[$i]].'</td>

                <td width="6%" style="text-align: center; background-color: #e74c3c;">'.$cantidadPacientesAdmisionados[2][$cantidades[$i]].'</td>

                <td width="5%" style="text-align: center; background-color: #7FFFD4;">'.$cantidadPacientesCategorizados[0][$cantidades[$i]].'</td>

                <td width="5%" style="text-align: center; background-color: #F0E68C;">'.$cantidadPacientesCategorizados[1][$cantidades[$i]].'</td>

                <td width="6%" style="text-align: center; background-color: #e74c3c;">'.$cantidadPacientesCategorizados[2][$cantidades[$i]].'</td>

                <td width="5%" style="text-align: center; background-color: #7FFFD4;">'.$cantidadPacientesInicioAtencion[0][$cantidades[$i]].'</td>

                <td width="5%" style="text-align: center; background-color: #F0E68C;">'.$cantidadPacientesInicioAtencion[1][$cantidades[$i]].'</td>

                <td width="6%" style="text-align: center; background-color: #e74c3c;">'.$cantidadPacientesInicioAtencion[2][$cantidades[$i]].'</td>

                <td width="5%" style="text-align: center; background-color: #7FFFD4;">'.$cantidadPacientesIndicacionEgreso[0][$cantidades[$i]].'</td>

                <td width="5%" style="text-align: center; background-color: #F0E68C;">'.$cantidadPacientesIndicacionEgreso[1][$cantidades[$i]].'</td>

                <td width="6%" style="text-align: center; background-color: #e74c3c;">'.$cantidadPacientesIndicacionEgreso[2][$cantidades[$i]].'</td>

                <td width="5%" style="text-align: center; background-color: #7FFFD4;">'.$cantidadPacientesEgresados[0][$cantidades[$i]].'</td>

                <td width="5%" style="text-align: center; background-color: #F0E68C;">'.$cantidadPacientesEgresados[1][$cantidades[$i]].'</td>

                <td width="6%" style="text-align: center; background-color: #e74c3c;">'.$cantidadPacientesEgresados[2][$cantidades[$i]].'</td>

            </tr>

        ';

    }


    return $textoADesplegar;

}



function pdfCantidadPacientesSegunEstadoGrafico ( $objCon, $objReporte, $fechas, $tipoEstado ) {

    $parametrosAEnviar[] = array();

    $parametrosAEnviar['fechaAnterior'] = $fechas['fechaAnterior'];

    $parametrosAEnviar['fechaActual'] = $fechas['fechaActual'];

    $parametrosAEnviar['tipoEstado'] = $tipoEstado;

    $cantidadPacientes = $objReporte->cantidadPacientesSegunTipoEstado($objCon, $parametrosAEnviar);

    unset($parametrosAEnviar);

    $chart  = new LineChart();

    $serie1 = new XYDataSet();

    $serie2 = new XYDataSet();

    $serie3 = new XYDataSet();

    $horarios = array(
                            '08:00:00',
                            '09:00:00',
                            '10:00:00',
                            '11:00:00',
                            '12:00:00',
                            '13:00:00',
                            '14:00:00',
                            '15:00:00',
                            '16:00:00',
                            '17:00:00',
                            '18:00:00',
                            '19:00:00',
                            '20:00:00',
                            '21:00:00',
                            '22:00:00',
                            '23:00:00',
                            '00:00:00',
                            '01:00:00',
                            '02:00:00',
                            '03:00:00',
                            '04:00:00',
                            '05:00:00',
                            '06:00:00',
                            '07:00:00',
                            '08:00:00'
                        );

    $cantidades = array(
                            'cantidad08',
                            'cantidad09',
                            'cantidad10',
                            'cantidad11',
                            'cantidad12',
                            'cantidad13',
                            'cantidad14',
                            'cantidad15',
                            'cantidad16',
                            'cantidad17',
                            'cantidad18',
                            'cantidad19',
                            'cantidad20',
                            'cantidad21',
                            'cantidad22',
                            'cantidad23',
                            'cantidad00',
                            'cantidad01',
                            'cantidad02',
                            'cantidad03',
                            'cantidad04',
                            'cantidad05',
                            'cantidad06',
                            'cantidad07',
                            'cantidad07',
                        );

    for ( $i = 0; $i < count($horarios) ; $i++) {

        $serie1->addPoint(new Point($horarios[$i], $cantidadPacientes[0][$cantidades[$i]]));

        $serie2->addPoint(new Point($horarios[$i], $cantidadPacientes[1][$cantidades[$i]]));

        $serie3->addPoint(new Point($horarios[$i], $cantidadPacientes[2][$cantidades[$i]]));

    }

    $dataSet = new XYSeriesDataSet();

    $dataSet->addSerie("Adultos", $serie1);

    $dataSet->addSerie("Pediátricos", $serie2);

    $dataSet->addSerie("Ginecológicos", $serie3);

    $chart->setDataSet($dataSet);

    switch ( $tipoEstado ) {

        case 'admision':
            $chart->setTitle("Cantidad de Pacientes Admisionados");
        break;

        case 'categorizados':
            $chart->setTitle("Cantidad de Pacientes Categorizados");
        break;

        case 'inicioAtencion':
            $chart->setTitle("Cantidad de Pacientes con Atención Iniciada");
        break;

        case 'indicacionEgreso':
            $chart->setTitle("Cantidad de Pacientes con Indicación de Egreso");
        break;

        case 'egresados':
            $chart->setTitle("Cantidad de Pacientes Egesados");
        break;

    }

    $chart->getPlot()->setGraphCaptionRatio(0.62);

    $chart->render("../graficos/graficoCantidadPacientesSegunEstado(".$tipoEstado.").png");

    $urlImagen = PATH.'/views/reportes/graficos/graficoCantidadPacientesSegunEstado('.$tipoEstado.').png';


    return '

        <table border="0" cellpadding="0">

            <tr>

                <td width="5%" style="text-align: center; font-weight: bold;">

                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    C<br>
                    A<br>
                    N<br>
                    T<br>
                    I<br>
                    D<br>
                    A<br>
                    D<br>

                </td>

                <td width="90%" style="text-align: center;">

                    <img src="'.$urlImagen.'" width="800px" height="200px" />

                </td>

            </tr>

            <tr>

                <td>&nbsp;</td>

                <td style="text-align: center; font-weight: bold;">HORARIO TRANSCURRIDO</td>

            </tr>

        </table>';


}



function pdfCantidadAltasSegunProfesional ( $objCon, $objReporte, $fechas ) {

    return '

        <table border="1" cellpadding="2">

            <thead>

                <tr class="titulo">

                    <td width="100%">Cantidad de Egresos por Profesional</td>

                </tr>

                <tr class="titulo">

                    <td width="26%" style="text-align: center;">Profesional</td>

                    <td width="8%" style="text-align: center;">Medicina</td>

                    <td width="8%" style="text-align: center;">Oncología</td>

                    <td width="8%" style="text-align: center;">Cirugía</td>

                    <td width="10%" style="text-align: center;">Cirugía Aislamiento</td>

                    <td width="10%" style="text-align: center;">Traumatología</td>

                    <td width="8%" style="text-align: center;">Pediatría</td>

                    <td width="8%" style="text-align: center;">Psiquiatría</td>

                    <td width="8%" style="text-align: center;">CR de la Mujer</td>

                    <td width="6%" style="text-align: center;">Otros</td>

                </tr>

            </thead>

            <tbody>'

                .desplegarCantidadAltasSegunProfesional($objCon, $objReporte, $fechas ).

            '</tbody>

        </table>

    ';

}



function desplegarCantidadAltasSegunProfesional ( $objCon, $objReporte, $fechas ) {

    $textoADesplegar = '';

    $parametrosAEnviar[] = array();

    $parametrosAEnviar['fechaAnterior'] = $fechas['fechaAnterior'];

    $parametrosAEnviar['fechaActual'] = $fechas['fechaActual'];

    $cantidadAltas = $objReporte->obtenerCantidadAltasSegunProfesional($objCon, $parametrosAEnviar);

    $totales = array(0, 0, 0, 0, 0, 0, 0, 0, 0);

    foreach ( $cantidadAltas as $cantidadAlta ) {

        $totales[0] += $cantidadAlta['altaMedicina'];

        $totales[1] += $cantidadAlta['altaOncologia'];

        $totales[2] += $cantidadAlta['altaCirugia'];

        $totales[3] += $cantidadAlta['altaCirugiaAislamiento'];

        $totales[4] += $cantidadAlta['altaTraumatologia'];

        $totales[5] += $cantidadAlta['altaPediatria'];

        $totales[6] += $cantidadAlta['altaPsiquiatria'];

        $totales[7] += $cantidadAlta['altaCRDeLaMujer'];

        $totales[8] += $cantidadAlta['altaOtros'];

        $textoADesplegar .= '

            <tr>

                <td width="26%" >'.ucwords(mb_strtolower($cantidadAlta['nombreProfesional'], "UTF-8")).'</td>

                <td width="8%" style="text-align: center;">'.$cantidadAlta['altaMedicina'].'</td>

                <td width="8%" style="text-align: center;">'.$cantidadAlta['altaOncologia'].'</td>

                <td width="8%" style="text-align: center;">'.$cantidadAlta['altaCirugia'].'</td>

                <td width="10%" style="text-align: center;">'.$cantidadAlta['altaCirugiaAislamiento'].'</td>

                <td width="10%" style="text-align: center;">'.$cantidadAlta['altaTraumatologia'].'</td>

                <td width="8%" style="text-align: center;">'.$cantidadAlta['altaPediatria'].'</td>

                <td width="8%" style="text-align: center;">'.$cantidadAlta['altaPsiquiatria'].'</td>

                <td width="8%" style="text-align: center;">'.$cantidadAlta['altaCRDeLaMujer'].'</td>

                <td width="6%" style="text-align: center;">'.$cantidadAlta['altaOtros'].'</td>

            </tr>

        ';

    }

    $textoADesplegar .= '

        <tr>

            <td width="26%">TOTALES</td>

            <td width="8%" style="text-align: center;">'.$totales[0].'</td>

            <td width="8%" style="text-align: center;">'.$totales[1].'</td>

            <td width="8%" style="text-align: center;">'.$totales[2].'</td>

            <td width="10%" style="text-align: center;">'.$totales[3].'</td>

            <td width="10%" style="text-align: center;">'.$totales[4].'</td>

            <td width="8%" style="text-align: center;">'.$totales[5].'</td>

            <td width="8%" style="text-align: center;">'.$totales[6].'</td>

            <td width="8%" style="text-align: center;">'.$totales[7].'</td>

            <td width="6%" style="text-align: center;">'.$totales[8].'</td>

        </tr>

    ';

    unset($parametrosAEnviar);

    return $textoADesplegar;

}



function desplegarNumero ( $numero ) {

    return ( empty($numero) || is_null($numero) ) ? 0 : $numero;

}



function filtrarPacientesPorSexo ( $arrayResultados, $sexo ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        if ( $array['sexoPaciente'] != $sexo ) {

            continue;

        }

        $total++;

    }

    return $total;

}



function filtrarPacientesPorPrevision ( $arrayResultados ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        if ( ! esBeneficiario($array['previsionPaciente']) ) {

            continue;

        }

        $total++;

    }

    return $total;

}



function esBeneficiario ( $tipoPrevision ) {

    return ( $tipoPrevision == 0 || $tipoPrevision == 1 || $tipoPrevision == 2 || $tipoPrevision == 3 ) ? true : false;

}



function segundosAHoraMinutos ( $seg ) {

    $seg = abs($seg);

    $d = floor($seg / 86400);

    $h = floor(($seg - ($d * 86400)) / 3600);

    $m = floor(($seg - ($d * 86400) - ($h * 3600)) / 60);

    return "$h:$m:00";

}



function segundosAMinutos ( $segundos ) {

    $segundos = abs($segundos);

    $minutos = floor($segundos / 60 );

    return $minutos;

}
?>