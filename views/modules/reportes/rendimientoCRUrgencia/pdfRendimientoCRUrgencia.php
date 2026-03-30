<iframe id="pdfReporteRendimientoCRUrgencia" height="100%" width="100%" hidden>

<?php
error_reporting(0);
ini_set('post_max_size', '512M');
ini_set('memory_limit', '2G');
set_time_limit(0);
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Cache-Control: no-store");

require_once('../../../../../estandar/TCPDF-main/tcpdf.php');
require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php');     $objCon     = new Connection; $objCon->db_connect();
require_once('../../../../class/Util.class.php');           $objUtil    = new Util;
require_once('../../../../class/Reportes.class.php');       $objReporte    = new Reportes;

$parametros = $objUtil->getFormulario($_POST);

$objCon = $objUtil->cambiarServidorReporte($parametros['fechaAnterior'], $parametros['fechaActual']);

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
$pdf->SetAuthor('HJNC-REPORTE-RENDIMIENTO-CR-URGENCIA');
$pdf->SetTitle('Reporte Rendimiento Turno CR Urgencia');
$pdf->SetSubject('Reporte');
$pdf->SetKeywords('Reporte Rendimiento CR Urgencia, Reporte');
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

$html = '<head>';

$html .= pdfEstilos();


$html .= '</head>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = "";

$html .= '<body>';

$html .= pdfEncabezado();

$pdf->writeHTML($html, true, false, true, false, '');

$html = "";

$html .= '<br>';

$html .= pdfTitulo($parametros);

$pdf->writeHTML($html, true, false, true, false, '');

$html = "";

$html .= '<br>';

$html .= pdfDesplegarReporteRendimientoCRUrgencia($objCon, $objReporte, $parametros);

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


    // $pdf->writeHTML($html, true, false, true, false, '');

$nombre_archivo = 'reporteRendimientoCRURgencia_'.date('d-m-Y').'.pdf';
$pdf->Output(__DIR__ . '/' . $nombre_archivo, 'FI');
$url = "/RCEDAU/views/modules/reportes/rendimientoCRUrgencia/".$nombre_archivo;


// $nombreArchivo = 'reporteRendimientoCRURgencia_'.date('d-m-Y').'.pdf';

// $pdf->Output($nombreArchivo,'FI');

// $url = "/dauRCE/views/reportes/rendimientoCRUrgencia/".$nombreArchivo;
?>
</iframe>



<div class="embed-responsive embed-responsive-16by9">
	<iframe id="reporteRendimientoCRUrgenciaPDF" class="embed-responsive-item" src="<?=$url?>" height="700" width="700" allowfullscreen></iframe>
</div>



<script>
$('#reporteRendimientoCRUrgenciaPDF').ready(function(){
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



function pdfEncabezado ( ) {

    return '

    <table width="640" border="0">

        <tbody>

            <tr>

                <td width="100" rowspan="4"><img src="/estandar/img/logo_gobierno_chile.jpg" width="50" height="50" /></td>

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



function pdfTitulo ( $parametros ) {

    $medicoUrgencia = explode("/", $parametros['medicoUrgencia']);

    $nombreMedicoUrgencia = $medicoUrgencia[1];

    return '

        <table width="100%" border="0">

        <tbody>

            <tr>

                <td style="text-align: center;"><h2>Reporte Rendimiento CR Urgencia, Doctor(a): '.$nombreMedicoUrgencia.'</h2></td>

            </tr>

            <tr>

                <td style="text-align: center;"><h2>Desde: '.$parametros['fechaAnterior'].' Hasta: '.$parametros['fechaActual'].'</h2></td>

            </tr>

        </tbody>

    </table>

    ';

}



function pdfDesplegarReporteRendimientoCRUrgencia ( $objCon, $objReporte, $parametros ) {

    $medicoUrgencia = explode("/", $parametros['medicoUrgencia']);

    $parametrosAEnviar[]                  = array();

    $parametrosAEnviar['fechaAnterior']    = date('Y-m-d', strtotime($parametros['fechaAnterior']));

    $parametrosAEnviar['fechaActual']      = date('Y-m-d', strtotime($parametros['fechaActual']));

    $parametrosAEnviar['idMedicoUrgencia'] = $medicoUrgencia[0];

    $reporteRendimiento                    = $objReporte->obtenerReporteRendimientoCRUrgencia($objCon, $parametrosAEnviar);

    return '

        <table border="1" cellpadding="2">

            <thead>

                <tr style="background-color: blue; color: white;">

                    <th colspan="3" width="18%">&nbsp;</th>

                    <th width="12%" style="text-align:center;">ESI-1</th>

                    <th width="12%" style="text-align:center;">ESI-2</th>

                    <th width="12%" style="text-align:center;">ESI-3</th>

                    <th width="20%" style="text-align:center;">ESI-4</th>

                    <th width="20%" style="text-align:center;">ESI-5</th>

                    <th width="8%" style="text-align:center;">Esp.</th>

                </tr>

                <tr style="background-color: blue; color: white;">

                    <th width="10%">Fechas</th>

                    <th width="4%" style="text-align:center;">Cant. A</th>

                    <th width="4%" style="text-align:center;">Cant. E</th>

                    <th width="6%" style="text-align:center;">A</th>

                    <th width="6%" style="text-align:center;">E</th>

                    <th width="6%" style="text-align:center;">A</th>

                    <th width="6%" style="text-align:center;">E</th>

                    <th width="6%" style="text-align:center;">A</th>

                    <th width="6%" style="text-align:center;">E</th>

                    <th width="5%" style="text-align:center;">A</th>

                    <th width="5%" style="text-align:center;">IV</th>

                    <th width="5%" style="text-align:center;">%</th>

                    <th width="5%" style="text-align:center;">E</th>

                    <th width="5%" style="text-align:center;">A</th>

                    <th width="5%" style="text-align:center;">IV</th>

                    <th width="5%" style="text-align:center;">%</th>

                    <th width="5%" style="text-align:center;">E</th>

                    <th width="4%" style="text-align:center;">P</th>

                    <th width="4%" style="text-align:center;">R</th>

                </tr>

            </thead>

            <tbody>'

                .desplegarDetalleReporteRendimientoCRUrgencia($reporteRendimiento).

            '</tbody>

        </table>';

}



function desplegarDetalleReporteRendimientoCRUrgencia ( $reporteRendimiento ) {

    $textoADesplegar = '';

    $totalReporteRendimiento = count($reporteRendimiento);

    for ( $i = 0; $i < $totalReporteRendimiento; $i++ ) {

        $textoADesplegar .= '

            <tr>

                <td width="10%">'.$reporteRendimiento[$i]['fecha'].'</td>

                <td width="4%" style="text-align: center;">'.$reporteRendimiento[$i]['totalPacientes'].'</td>

                <td width="4%" style="text-align: center;">'.$reporteRendimiento[$i]['totalPacientesEgresados'].'</td>

                <td width="6%" style="text-align: center;">'.$reporteRendimiento[$i]['pacientesAtendidosESI1'].'</td>

                <td width="6%" style="text-align: center;">'.$reporteRendimiento[$i]['pacientesEgresadosESI1'].'</td>

                <td width="6%" style="text-align: center;">'.$reporteRendimiento[$i]['pacientesAtendidosESI2'].'</td>

                <td width="6%" style="text-align: center;">'.$reporteRendimiento[$i]['pacientesEgresadosESI2'].'</td>

                <td width="6%" style="text-align: center;">'.$reporteRendimiento[$i]['pacientesAtendidosESI3'].'</td>

                <td width="6%" style="text-align: center;">'.$reporteRendimiento[$i]['pacientesEgresadosESI3'].'</td>

                <td width="5%" style="text-align: center;">'.$reporteRendimiento[$i]['pacientesAtendidosESI4'].'</td>

                <td width="5%" style="text-align: center;">'.desplegarNumero($reporteRendimiento[$i]['intravenososESI4']).'</td>

                <td width="5%" style="text-align: center;">'.desplegarDivisionPorcentual($reporteRendimiento[$i]['intravenososESI4'], $reporteRendimiento[$i]['pacientesAtendidosESI4']).'%</td>

                <td width="5%" style="text-align: center;">'.$reporteRendimiento[$i]['pacientesEgresadosESI4'].'</td>

                <td width="5%" style="text-align: center;">'.$reporteRendimiento[$i]['pacientesAtendidosESI5'].'</td>

                <td width="5%" style="text-align: center;">'.desplegarNumero($reporteRendimiento[$i]['intravenososESI5']).'</td>

                <td width="5%" style="text-align: center;">'.desplegarDivisionPorcentual($reporteRendimiento[$i]['intravenososESI5'], $reporteRendimiento[$i]['pacientesAtendidosESI5']).'%</td>

                <td width="5%" style="text-align: center;">'.$reporteRendimiento[$i]['pacientesEgresadosESI5'].'</td>

                <td width="4%" style="text-align: center;">'.$reporteRendimiento[$i]['totalSolicitudesEspecialistaPedidas'].'</td>

                <td width="4%" style="text-align: center;">'.$reporteRendimiento[$i]['totalSolicitudesEspecialistaRealizadas'].'</td>

            </tr>

            ';

    }

    return $textoADesplegar;

}



function desplegarNumero ( $numero ) {

    return ( empty($numero) || is_null($numero) ) ? 0 : $numero;

}



function desplegarDivisionPorcentual ( $dividendo, $divisor ) {

    return ( empty($divisor) || is_null($divisor) || $divisor == NULL || empty($dividendo) || is_null($dividendo) || $dividendo == NULL ) ? 0 : round(($dividendo * 100) / $divisor, 1);

}
?>
