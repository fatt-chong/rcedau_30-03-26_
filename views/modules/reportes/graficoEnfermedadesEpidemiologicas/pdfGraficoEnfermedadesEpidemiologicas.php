<iframe height="100%" width="100%" hidden>
<?php
error_reporting(1);
ini_set('post_max_size', '512M');
ini_set('memory_limit', '1G');
set_time_limit(0);
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Cache-Control: no-store");

require_once('../../../../../estandar/TCPDF-main/tcpdf.php');
require_once("../../../../config/config.php");
require_once("../../../../class/Connection.class.php");        $objCon         = new Connection();
require_once("../../../../class/Util.class.php"); 		        $objUtil        = new Util;
require_once("../../../../class/Reportes.class.php"); 	        $objReporte     = new Reportes();
require_once('../../../../class/Formulario.class.php'); 		$objFormulario 	= new Formulario;
require_once('../../../../../estandar/assets/jpgraph-4.4.2/src/jpgraph.php');
require_once('../../../../../estandar/assets/jpgraph-4.4.2/src/jpgraph_line.php');
// require_once('../../../../assets/libs/jpgraph-4.4.2/src/jpgraph.php');
// require_once('../../../../assets/libs/jpgraph-4.4.2/src/jpgraph_line.php');



$anioResumen                         = $_POST['anioResumen'];

$arrayDauCerradosSemanas             = $_POST['arrayDauCerradosSemanas'];

$arrayDauEnfermedadesEpidemiologicas = $_POST['arrayDauEnfermedadesEpidemiologicas'];

$arrayDauCerradosSemanas             = json_decode(stripslashes($arrayDauCerradosSemanas), true);

$arrayDauEnfermedadesEpidemiologicas = json_decode(stripslashes($arrayDauEnfermedadesEpidemiologicas), true);

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

$html .= pdfTitulo($anioResumen);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfResumenEnfermedadesEpidemiologicasAdulto('adulto', $arrayDauCerradosSemanas[0], $arrayDauEnfermedadesEpidemiologicas[0]);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfResumenEnfermedadesEpidemiologicasAdulto('pediatrico', $arrayDauCerradosSemanas[1], $arrayDauEnfermedadesEpidemiologicas[1]);

$html .= '<br>';

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
$nombreArchivo = 'resumenGraficoEnfermedadesEpidemiologicas_'.$anioResumen.'.pdf';
$pdf->Output(__DIR__ . '/' . $nombreArchivo, 'FI');
$url = "/RCEDAU/views/modules/reportes/graficoEnfermedadesEpidemiologicas/".$nombreArchivo;
?>
</iframe>



<div class="embed-responsive embed-responsive-16by9">
	<iframe id="enfermedadesEpidemiologicas" class="embed-responsive-item" src="<?=$url?>" height="700" width="700" allowfullscreen></iframe>
</div>



<script>
$('#enfermedadesEpidemiologicas').ready(function(){
	ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','nombreArchivo=<?=$url?>&accion=eliminarPDF', 'POST', 'JSON', 1, '', true);
});
</script>
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
function pdfTitulo ( $fecha ) {
    return '
        <table width="100%" border="0">
            <tbody>
                <tr>
                    <td style="text-align: center;"><h2>Curva Demanda Consultas Respiratorias (Año: '.$fecha.')</h2></td>
                </tr>
            </tbody>
        </table>
    ';
}


function pdfResumenEnfermedadesEpidemiologicasAdulto($tipoAtencion, $arrayDauCerradosSemanas, $arrayDauEnfermedadesEpidemiologicas) {
   

    $data1 = [];
    $data2 = [];
    $labels = [];

    for ($i = 1; $i <= 52; $i++) {
        $filaDauCerrados = 'dauCerradosSemana' . $i;
        $filaEnfermedadesEpidemiologicas = 'dauCerradosEnfermedadesRespiratoriasSemana' . $i;

        // Validar si los datos existen y son mayores que 0
        if (!isset($arrayDauCerradosSemanas[$filaDauCerrados]) || 
            !isset($arrayDauEnfermedadesEpidemiologicas[$filaEnfermedadesEpidemiologicas]) || 
            $arrayDauCerradosSemanas[$filaDauCerrados] == 0 || 
            $arrayDauEnfermedadesEpidemiologicas[$filaEnfermedadesEpidemiologicas] == 0) {
            continue;
        }

        $data1[] = $arrayDauCerradosSemanas[$filaDauCerrados];
        $data2[] = $arrayDauEnfermedadesEpidemiologicas[$filaEnfermedadesEpidemiologicas];
        $labels[] = $i; // Semana
    }

    // Crear gráfico
    $graph = new Graph(800, 450);
    $graph->SetScale('intlin');
    $graph->img->SetMargin(50, 30, 50, 50);

    // Configuración de títulos
    switch ($tipoAtencion) {
        case 'adulto':
            $titulo = "Curva Demanda Consultas Respiratorias Adulto";
            break;
        case 'pediatrico':
            $titulo = "Curva Demanda Consultas Respiratorias Pediátricos";
            break;
        default:
            $titulo = "Curva Demanda Consultas Respiratorias";
    }

    $graph->title->Set($titulo);
    $graph->title->SetFont(FF_FONT1, FS_BOLD);
    $graph->xaxis->title->Set("Semanas");
    $graph->yaxis->title->Set("Cantidad");
    // Agregar etiquetas en el eje X
    $graph->xaxis->SetTickLabels($labels);

    // Crear las líneas de datos
    $lineplot1 = new LinePlot($data1);
    $lineplot1->SetLegend("Dau Cerrados");
    $lineplot1->SetColor("blue");

    $lineplot2 = new LinePlot($data2);
    $lineplot2->SetLegend("Dau Enfermedades Epidemiológicas");
    $lineplot2->SetColor("red");
    // Agregar las líneas al gráfico
    $graph->Add($lineplot1);
    $graph->Add($lineplot2);
    // Agregar leyenda
    $graph->legend->SetFrameWeight(1);
    $path = __DIR__ . '/../../../reportes/graficos/';
    $filename = 'graficoResumenEnfermedadesEpidemiologicas(' . $tipoAtencion . ').png';
    $fullPath = $path . $filename;
    if (file_exists($fullPath)) {
        unlink($fullPath); // Eliminar el archivo
    }
    $graph->Stroke($path . $filename);
    $urlImagen = PATH.'/views/reportes/graficos/graficoResumenEnfermedadesEpidemiologicas('.$tipoAtencion.').png';
    return '
        <table border="0" cellpadding="0">
            <tr>
                <td width="90%" style="text-align: center;">
                    <img src="' . $urlImagen . '" width="800px" height="450px" />
                    
                </td>
            </tr>
        </table>
    ';
}