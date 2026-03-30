<iframe height="100%" width="100%" hidden>
<?php
error_reporting(1);

ini_set('post_max_size', '512M');

ini_set('memory_limit', '1G');

set_time_limit(0);

header("Content-type: application/pdf");

header("Pragma: no-cache");

header("Cache-Control: no-cache");

header("Cache-Control: no-store");



require_once('../../../../estandar/tcpdf/tcpdf.php');

require_once('../../../../estandar/tcpdf/config/lang/spa.php');

require_once("../../../config/config.php");

require_once("../../../class/Connection.class.php");

require_once("../../../class/Util.class.php");

$objUtil        = new Util;

$parametros = $objUtil->getFormulario($_POST);



/*
################################################################################################################################################
                                                            Configuración PDF
################################################################################################################################################
*/
class MYPDF extends TCPDF {

    public function Test( $ae ) {

        if ( ! isset($this->xywalter) ) {

            $this->xywalter = array();
        }

        $this->xywalter[] = array($this->GetX(), $this->GetY());

    }

    var $top_margin = 20;

        function Header() {
            // set top margin to style pages 2, 3..
            //title goes here
            $this->top_margin = $this->GetY() + 5; // padding for second page
            }

}


$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('HJNC-INFORMÁTICA');
$pdf->SetTitle('PDF Reporte Indicadores Año '.$parametros['anio']);
$pdf->SetSubject('Resumen');
$pdf->SetKeywords('Resumen');
$pdf->setHeaderFont(Array('helvetica', '', 6));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(10, 10, 10);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(true);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setLanguageArray($l);
$pdf->setFontSubsetting(false);
$pdf->SetFont('helvetica', '', 7, '', true);


/*
################################################################################################################################################
                                                            DESPLIEGUE PDF
################################################################################################################################################
*/
$pdf->AddPage();

$html = '<head>';

$html .= '

    <style type="text/css">

        body {

                font-family: "SourceSansPro-Regular", Arial, Helvetica;

                font-size: 8pt;

            }

        .titulo {

                background-color: #6495ED;

                color: white;

                font-weight: bold;

            }

            table {

                font-family: "SourceSansPro-Regular", Arial, Helvetica;

                font-size: 8pt;

            }

    </style> ';

$html .= '</head>';

$html .= '<body>';

$html .= pdfEncabezado();

$html .= '<br>';

$html .= pdfTitulo($parametros);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = "";

$html .= pdfTablaReporteEndovenosoCat4($parametros);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = "";

$arr_nomes = array(

    array("", 20, 53)

);

$ttPages = $pdf->getNumPages();

for ( $i = 1; $i <= $ttPages; $i++ ) {

    $pdf->setPage($i);

    foreach ( $arr_nomes as $num => $arrCols ) {

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

$nombreArchivo = 'reporteEndovenosoCat4'.date('d-m-Y').'.pdf';

$pdf->Output($nombreArchivo,'FI');

$url = "/dauRCE/views/reportes/endovenosoCat4/".$nombreArchivo;

$objCon = null;


/*
################################################################################################################################################
                                                            DESPLIEGUE PDF
################################################################################################################################################
*/
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



function pdfTitulo ( $parametros ) {

    return '

        <table width="100%" border="0">

            <tbody>

                <tr>

                    <td style="text-align: center;"><h2>REPORTE ESI-4 CON INDIACIÓN TRATAMIENTO ENDOVENOSO</h2></td>

                </tr>

                <tr>

                    <td style="text-align: center;"><h2>DESDE: '.$parametros['fechaInicio'].' HASTA: '.$parametros['fechaTermino'].'</h2></td>

                </tr>

            </tbody>

        </table>

    ';

}



function pdfTablaReporteEndovenosoCat4 ( $parametros ) {

    return '

        <div>

            <table width="100%" border="1" cellspacing="1" cellpadding="2">

                '.html_entity_decode($parametros['htmlPDF']).'

            </table>

        </div>
        ';

}
?>
</iframe>

<div class="embed-responsive embed-responsive-16by9">

	<iframe id="pdf" class="embed-responsive-item" src="<?php echo $url; ?>" height="100%" width="100%" allowfullscreen></iframe>

</div>

<script>

    $('#modalPDF').ready(function(){

        ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','nombreArchivo=<?=$url?>&accion=eliminarPDF', 'POST', 'JSON', 1, '', true);

    });

</script>