<iframe height="100%" width="100%" hidden>
<?php
error_reporting(0);
ini_set('post_max_size', '512M'); 
ini_set('memory_limit', '1G'); 
set_time_limit(0);
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Cache-Control: no-store");

require_once('../../../../../estandar/TCPDF-main/tcpdf.php');

require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php');     $objCon     = new Connection; $objCon->db_connect();
require_once('../../../../class/Util.class.php');           $objUtil    = new Util;
require_once('../../../../class/Reportes.class.php');       $objReporte    = new Reportes;



		

$fechas = $objUtil->getFormulario($_POST);

$fechas['fechaAnterior'] = date('Y-m-d', strtotime($fechas['fechaAnterior']));
 
$fechas['fechaActual']   = date('Y-m-d', strtotime($fechas['fechaActual']));

$objCon                  = $objUtil->cambiarServidorReporte($fechas['fechaAnterior'], $fechas['fechaActual']);

$categorizaciones        = array('ESI-1', 'ESI-2', 'ESI-3', 'ESI-4', 'ESI-5');

$tipoAtencion            = array(1, 2);

$tipoDiagnostico         = array('Z995', 'Z038');

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
$pdf->SetTitle('Resumen Tiempos CR Urgencia');
$pdf->SetSubject('Resumen');
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

$html .= pdfDemandaUrgenciaAdultoPediatrica($objCon, $objReporte, $fechas);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfResumenTiemposEspera($objCon, $objReporte, $categorizaciones, $fechas);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

crearTablasTemporales($objCon, $objReporte, $fechas);

$html = '';

$html .= pdfEstilos();

$html .= pdfResumenTiemposEsperaDeciles('ADULTO', $objCon, $objReporte, $categorizaciones, $tipoAtencion[0], $fechas);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfResumenTiemposEsperaDeciles('PEDIÁTRICO', $objCon, $objReporte, $categorizaciones, $tipoAtencion[1], $fechas);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfCumplimientoCategorizacionESI($objCon, $objReporte, $categorizaciones, $fechas);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfDiagnosticosInespecificos($objCon, $objReporte, $tipoDiagnostico, $fechas);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfTiemposLaboratorio($objCon, $objReporte, $fechas);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfTiemposImagenologia($objCon, $objReporte, $fechas);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$objReporte->eliminarTablaTemporalMuestraDeciles($objCon);

$objReporte->eliminarTablaTemporalTiemposLaboratorio($objCon);

$objReporte->eliminarTablaTemporalTiemposImagenologia($objCon);

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

$nombreArchivo = 'resumenTiemposCRURgencia_'.date('d-m-Y').'.pdf';
$pdf->Output(__DIR__ . '/' . $nombreArchivo, 'FI');
$url = "/RCEDAU/views/modules/reportes/tiemposCRUrgencia/".$nombreArchivo;


?>
</iframe>



<div class="embed-responsive embed-responsive-16by9">
	<iframe id="tiemposCRUrgenciaPDF" class="embed-responsive-item" src="<?=$url?>" height="700" width="700" allowfullscreen></iframe>
</div>



<script>
$('#tiemposCRUrgenciaPDF').ready(function(){
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



function pdfTitulo ( $fechas ) {

    return '
    
        <table width="100%" border="0">
	
            <tbody>

                <tr>
            
                    <td style="text-align: center;"><h2>Resumen Tiempos CR Urgencia</h2></td>
            
                </tr>

                <tr>
            
                    <td style="text-align: center;"><h2>Desde: '.date('d-m-Y', strtotime($fechas['fechaAnterior'])).' Hasta: '.date('d-m-Y', strtotime($fechas['fechaActual'])).'</h2></td>
            
                </tr>
            
            </tbody>

        </table>

    ';

}



function pdfDemandaUrgenciaAdultoPediatrica ( $objCon, $objReporte, $fechas ) {

    $demandaUrgenciaAdultoPediatrica = $objReporte->obtenerDemandaUrgenciaAdultoPediatrica($objCon, $fechas);

    $totales[]                       = array();

    $totales['totalCierre']          = $demandaUrgenciaAdultoPediatrica['totalPacientesAdultoCierre'] + $demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoCierre'];

    $totales['totalNEA']             = $demandaUrgenciaAdultoPediatrica['totalPacientesAdultoNEA'] + $demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoNEA'];

    $totales['totalAnula']           = $demandaUrgenciaAdultoPediatrica['totalPacientesAdultoAnula'] + $demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoAnula'];

    $totales['totalAdultos']         = $demandaUrgenciaAdultoPediatrica['totalPacientesAdultoCierre'] + $demandaUrgenciaAdultoPediatrica['totalPacientesAdultoNEA'] + $demandaUrgenciaAdultoPediatrica['totalPacientesAdultoAnula'];

    $totales['totalPediatricos']     = $demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoCierre'] + $demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoNEA'] + $demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoAnula'];

    $totales['total']                = $totales['totalAdultos'] + $totales['totalPediatricos'];

    return '
    
        <table border="1" cellpadding="2">

            <thead>

                <tr style="background-color: blue; color: white;">

                    <th colspan="7" style="text-align: center;">DEMANDA URGENCIA ADULTO Y PEDIÁTRICA</th>   

                </tr>

                <tr style="background-color: blue; color: white;">

                    <th width="20%">Descripción Tipo de Demanda</th>            

                    <th width="15%" style="text-align:center;">Adulto</th>

                    <th width="15%" style="text-align:center;">% Adultos</th>

                    <th width="15%" style="text-align:center;">Pediátricos</th>

                    <th width="15%" style="text-align:center;">% Pediátricos</th>

                    <th width="10%" style="text-align:center;">Todos</th>

                    <th width="10%" style="text-align:center;">% Todos</th>

                </tr>                

            </thead>'


                .desplegarDemandaCerrados($demandaUrgenciaAdultoPediatrica, $totales)

                .desplegarDemandaNEA($demandaUrgenciaAdultoPediatrica, $totales)

                .desplegarDemandaAnula($demandaUrgenciaAdultoPediatrica, $totales)

                .desplegarDemandaTotales($totales).

            '<tbody>
                

            </tbody>

        </table>
    
    
    ';

}



function desplegarDemandaCerrados ( $demandaUrgenciaAdultoPediatrica, $totales ) {

    return '
    
        <tr>

            <td width="20%">CERRADOS</td>

            <td width="15%" style="text-align:center;">'        
            
                .desplegarNumero($demandaUrgenciaAdultoPediatrica['totalPacientesAdultoCierre']).

            '</td>

            <td width="15%" style="text-align:center;">'

                .desplegarDivisionPorcentual($demandaUrgenciaAdultoPediatrica['totalPacientesAdultoCierre'], $totales['totalAdultos']).
            
            '%</td>

            <td width="15%" style="text-align:center;">'
            
                .desplegarNumero($demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoCierre']).        
            
            '</td>

            <td width="15%" style="text-align:center;">'
            
                .desplegarDivisionPorcentual($demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoCierre'], $totales['totalPediatricos']).
            
            '%</td>

            <td width="10%" style="text-align:center;">'
            
                .desplegarNumero($totales['totalCierre']).
            
            '</td>

            <td width="10%" style="text-align:center;">'
            
                .desplegarDivisionPorcentual($totales['totalCierre'], $totales['total']).
            
            '%</td>

        </tr>    
    
    ';

}



function desplegarDemandaNEA ( $demandaUrgenciaAdultoPediatrica, $totales ) {

    return '
    
        <tr>

            <td>NEA</td>

            <td style="text-align:center;">'        
            
                .desplegarNumero($demandaUrgenciaAdultoPediatrica['totalPacientesAdultoNEA']).

            '</td>

            <td style="text-align:center;">'

                .desplegarDivisionPorcentual($demandaUrgenciaAdultoPediatrica['totalPacientesAdultoNEA'], $totales['totalAdultos']).
            
            '%</td>

            <td style="text-align:center;">'
            
                .desplegarNumero($demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoNEA']).        
            
            '</td>

            <td style="text-align:center;">'
            
                .desplegarDivisionPorcentual($demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoNEA'], $totales['totalPediatricos']).
            
            '%</td>

            <td style="text-align:center;">'
            
                .desplegarNumero($totales['totalNEA']).
            
            '</td>

            <td style="text-align:center;">'
            
                .desplegarDivisionPorcentual($totales['totalNEA'], $totales['total']).
            
            '%</td>

        </tr>    
    
    ';

}



function desplegarDemandaAnula ( $demandaUrgenciaAdultoPediatrica, $totales ) {

    return '
    
        <tr>

            <td width="20%">ANULA</td>

            <td width="15%" style="text-align:center;">'        
            
                .desplegarNumero($demandaUrgenciaAdultoPediatrica['totalPacientesAdultoAnula']).

            '</td>

            <td width="15%" style="text-align:center;">'

                .desplegarDivisionPorcentual($demandaUrgenciaAdultoPediatrica['totalPacientesAdultoAnula'], $totales['totalAdultos']).
            
            '%</td>

            <td width="15%" style="text-align:center;">'
            
                .desplegarNumero($demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoAnula']).        
            
            '</td>

            <td width="15%" style="text-align:center;">'
            
                .desplegarDivisionPorcentual($demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoAnula'], $totales['totalPediatricos']).
            
            '%</td>

            <td width="10%" style="text-align:center;">'
            
                .desplegarNumero($totales['totalAnula']).
            
            '</td>

            <td width="10%" style="text-align:center;">'
            
                .desplegarDivisionPorcentual($totales['totalAnula'], $totales['total']).
            
            '%</td>

        </tr>    
    
    ';

}



function desplegarDemandaTotales ( $totales ) {

    return '
    
        <tr>

            <td width="20%">TOTALES</td>

            <td width="15%" style="text-align:center;">'        
            
                .desplegarNumero($totales['totalAdultos']).

            '</td>

            <td width="15%" style="text-align:center;">'

                .desplegarDivisionPorcentual($totales['totalAdultos'], $totales['total']).
            
            '%</td>

            <td width="15%" style="text-align:center;">'
            
                .desplegarNumero($totales['totalPediatricos']).        
            
            '</td>

            <td width="15%" style="text-align:center;">'
            
                .desplegarDivisionPorcentual($totales['totalPediatricos'], $totales['total']).
            
            '%</td>

            <td width="10%" style="text-align:center;">'
            
                .desplegarNumero($totales['total']).
            
            '</td>

            <td width="10%" style="text-align:center;">'
            
                .desplegarDivisionPorcentual($totales['total'], $totales['total']).
            
            '%</td>

        </tr>    
    
    ';

}



function pdfResumenTiemposEspera ( $objCon, $objReporte, $categorizaciones, $fechas ) {

    return '
    
        <table border="1" cellpadding="2">

            <thead>

                <tr style="background-color: blue; color: white;">

                    <th colspan="7" style="text-align: center;">RESUMEN TIEMPOS DE ESPERA ADULTOS Y PEDIÁTRICOS</th>   

                </tr>

                <tr style="background-color: blue; color: white;">

                    <th rowspan="2" width="25%">CAT</th>            

                    <th colspan="3" width="38%" style="text-align:center;">ADULTOS</th>

                    <th colspan="3" width="37%" style="text-align:center;">PEDIÁTRICOS</th>

                </tr>

                <tr style="background-color: blue; color: white;">    

                    <th width="12%" style="text-align:center;">Cantidad DAU</th>

                    <th width="13%" style="text-align:center;">T. Espera Promedio</th>

                    <th width="13%" style="text-align:center;">T. Espera Máximo</th>

                    <th width="12%" style="text-align:center;">Cantidad DAU</th>

                    <th width="13%" style="text-align:center;">T. Espera Promedio</th>

                    <th width="12%" style="text-align:center;">T. Espera Máximo</th>

                </tr>

            </thead>

            <tbody>'

                .desplegarDetalleResumenTiemposEspera($objCon, $objReporte, $categorizaciones, $fechas).

            '</tbody>

        </table>    
    
    ';

}



function desplegarDetalleResumenTiemposEspera ( $objCon, $objReporte, $categorizaciones, $fechas ) {

    $textoADesplegar                    = '';

    $totalCategorizaciones              = count($categorizaciones);

    $parametrosAEnviar[]                = array();

    $parametrosAEnviar['fechaAnterior'] = $fechas['fechaAnterior'];

    $parametrosAEnviar['fechaActual']   = $fechas['fechaActual'];

    $resumenTiemposEspera               = $objReporte->obtenerResumenTiemposEspera($objCon, $parametrosAEnviar);

    for ( $i = 0; $i < $totalCategorizaciones; $i++ ) {

        $textoADesplegar .= '

            <tr>

                <td width="25%">'
                
                    .$categorizaciones[$i].
                    
                '</td>';

                            
                for ( $k = 0; $k < count($resumenTiemposEspera); $k++ ) {

                    if ( $resumenTiemposEspera[$k]['tipoAtencion'] == 1 && $resumenTiemposEspera[$k]['tipoCategorizacion'] == $categorizaciones[$i] ) {

                        break;

                    } 

                }

                $textoADesplegar .= '        
        
                <td width="12%" style="text-align:center;">'
                
                    .desplegarNumero($resumenTiemposEspera[$k]['totalAtendidos']).
                    
                '</td> 

                <td width="13%" style="text-align:center;">'
                
                    .desplegarNumero($resumenTiemposEspera[$k]['tiempoPromedio']).
                    
                '</td>

                <td width="13%" style="text-align:center;">'
                
                    .desplegarNumero($resumenTiemposEspera[$k]['tiempoMaximo']).
                    
                '</td>';

                for ( $k = 0; $k < count($resumenTiemposEspera); $k++ ) {

                    if ( $resumenTiemposEspera[$k]['tipoAtencion'] == 2 && $resumenTiemposEspera[$k]['tipoCategorizacion'] == $categorizaciones[$i] ) {

                        break;

                    } 

                }

                $textoADesplegar .= '        
        
                <td width="12%" style="text-align:center;">'
                
                    .desplegarNumero($resumenTiemposEspera[$k]['totalAtendidos']).
                    
                '</td> 

                <td width="13%" style="text-align:center;">'
                
                    .desplegarNumero($resumenTiemposEspera[$k]['tiempoPromedio']).
                    
                '</td>

                <td width="12%" style="text-align:center;">'
                
                    .desplegarNumero($resumenTiemposEspera[$k]['tiempoMaximo']).
                    
                '</td>       

            </tr>        
        
        ';

    }

    $resumenTiemposEsperaNEA     = $objReporte->obtenerResumenTiemposEsperaNEA($objCon, $parametrosAEnviar);

    $textoADesplegar .= '
    
        <tr>

            <td width="25%">NEA</td>            

            <td width="12%" style="text-align:center;">'
            
                .desplegarNumero($resumenTiemposEsperaNEA[0]['totalAtencion']).
                
            '</td> 

            <td width="13%" style="text-align:center;">'
            
                .desplegarNumero($resumenTiemposEsperaNEA[0]['tiempoPromedio']).
                
            '</td>

            <td width="13%" style="text-align:center;">'
            
                .desplegarNumero($resumenTiemposEsperaNEA[0]['tiempoMaximo']).
                
            '</td>

            <td width="12%" style="text-align:center;">'
            
                .desplegarNumero($resumenTiemposEsperaNEA[1]['totalAtencion']).
                
            '</td>             

            <td width="13%" style="text-align:center;">'
            
                .desplegarNumero($resumenTiemposEsperaNEA[1]['tiempoPromedio']).
                
            '</td>             

            <td width="12%" style="text-align:center;">'
            
                .desplegarNumero($resumenTiemposEsperaNEA[1]['tiempoMaximo']).
                
            '</td> 

        </tr>
    
    ';

    unset($parametrosAEnviar);

    return $textoADesplegar;

}



function pdfResumenTiemposEsperaDeciles ($tituloTipoAtencion, $objCon, $objReporte, $categorizaciones, $tipoAtencion, $fechas) {

    return '
    
        <table border="1" cellpadding="2">

            <thead>

                <tr style="background-color: blue; color: white;"> 

                    <th colspan="11" style="text-align: center;">RESUMEN TIEMPOS DE ESPERA DECILES '.$tituloTipoAtencion.'</th>   

                </tr>

                <tr style="background-color: blue; color: white;"> 

                    <th width="10%">CAT</th>  

                    <th width="9%" style="text-align: center;">Hrs D1</th>            

                    <th width="9%" style="text-align: center;">Hrs D2</th> 

                    <th width="9%" style="text-align: center;">Hrs D3</th> 

                    <th width="9%" style="text-align: center;">Hrs D4</th> 

                    <th width="9%" style="text-align: center;">Hrs D5</th> 

                    <th width="9%" style="text-align: center;">Hrs D6</th> 

                    <th width="9%" style="text-align: center;">Hrs D7</th> 

                    <th width="9%" style="text-align: center;">Hrs D8</th> 

                    <th width="9%" style="text-align: center;">Hrs D9</th> 

                    <th width="9%" style="text-align: center;">Hrs D10</th> 

                </tr>

            </thead>

            <tbody>'

                .desplegarDetalleResumenTiemposEsperaDeciles($objCon, $objReporte, $categorizaciones, $tipoAtencion, $fechas)

                .desplegarDetalleResumenTiemposEsperaDecilesNEA($objCon, $objReporte, $tipoAtencion, $fechas).

            '</tbody>

        </table>
    
    ';

}



function desplegarDetalleResumenTiemposEsperaDeciles ( $objCon, $objReporte, $categorizaciones, $tipoAtencion, $fechas ) {

    $totalCategorizaciones = count($categorizaciones);

    $textoADesplegar                      = '';

    $parametrosAEnviar[]                  = array();

    $parametrosAEnviar['fechaAnterior']   = $fechas['fechaAnterior'];

    $parametrosAEnviar['fechaActual']     = $fechas['fechaActual'];

    $parametrosAEnviar['tipoAtencion']    = $tipoAtencion;  

    for ( $i = 0; $i < $totalCategorizaciones; $i++ ) {

        $parametrosAEnviar['tipoCategorizacion'] = $categorizaciones[$i];
        
        $totalMuestras                           = $objReporte->obtenerTotalMuestras($objCon, $parametrosAEnviar);

        $totalMuestraPorDeciles                  = round($totalMuestras['totalMuestras'] / 10);

        $parametrosAEnviar['desdeDondeTomar']    = 0;

        $parametrosAEnviar['cantidadATomar']     = $totalMuestraPorDeciles;

        $textoADesplegar .= '<tr>';

        $textoADesplegar .= '<td width="10%">'.$categorizaciones[$i].'</td>';

        for ( $k = 0; $k < 10; $k++ ) {

            $textoADesplegar .= desplegarFilaDetalleResumenTiemposEsperaDeciles($objCon, $objReporte, $parametrosAEnviar);

            $parametrosAEnviar['desdeDondeTomar'] += $parametrosAEnviar['cantidadATomar'];

        }

        $textoADesplegar .= '</tr>';

    }

    unset($parametrosAEnviar);    

    return $textoADesplegar;

}



function desplegarFilaDetalleResumenTiemposEsperaDeciles ( $objCon, $objReporte, $parametrosAEnviar ) {

    $textoADesplegar       = '';

    $tiempoPromedioDeciles = $objReporte->obtenerTiempoPromedioDeciles($objCon, $parametrosAEnviar);

    $textoADesplegar       = '<td width="9%" style="text-align: center;">'.desplegarNumero($tiempoPromedioDeciles['tiempoPromedio']).'</td>';  

    return $textoADesplegar;
    
}



function desplegarDetalleResumenTiemposEsperaDecilesNEA ( $objCon, $objReporte, $tipoAtencion, $fechas ) {

    $textoADesplegar                      = '';

    $parametrosAEnviar[]                  = array();

    $parametrosAEnviar['fechaAnterior']   = $fechas['fechaAnterior'];

    $parametrosAEnviar['fechaActual']     = $fechas['fechaActual'];

    $parametrosAEnviar['tipoAtencion']    = $tipoAtencion;  

    $totalMuestras                        = $objReporte->obtenerTotalMuestrasNEA($objCon, $parametrosAEnviar);

    $totalMuestraPorDeciles               = round($totalMuestras['totalMuestras'] / 10);

    $parametrosAEnviar['desdeDondeTomar'] = 0;

    $parametrosAEnviar['cantidadATomar']  = $totalMuestraPorDeciles;

    $textoADesplegar .= '<tr>';

    $textoADesplegar .= '<td width="10%">NEA</td>';

    for ( $k = 0; $k < 10; $k++ ) {

        $textoADesplegar .= desplegarFilaDetalleResumenTiemposEsperaDecilesNEA($objCon, $objReporte, $parametrosAEnviar);

        $parametrosAEnviar['desdeDondeTomar'] += $parametrosAEnviar['cantidadATomar'];

    }

    $textoADesplegar .= '</tr>';

    unset($parametrosAEnviar);    

    return $textoADesplegar;

}



function desplegarFilaDetalleResumenTiemposEsperaDecilesNEA ( $objCon, $objReporte, $parametrosAEnviar ) {

    $textoADesplegar       = '';

    $tiempoPromedioDeciles = $objReporte->obtenerTiempoPromedioDecilesNEA($objCon, $parametrosAEnviar);

    $textoADesplegar       = '<td width="9%" style="text-align: center;">'.desplegarNumero($tiempoPromedioDeciles['tiempoPromedio']).'</td>';  

    return $textoADesplegar;
    
}



function pdfCumplimientoCategorizacionESI ( $objCon, $objReporte, $categorizaciones, $fechas ) {

    return '
    
        <table border="1" cellpadding="2">

            <thead>

                <tr style="background-color: blue; color: white;">

                    <th colspan="7" style="text-align: center;">RESUMEN CUMPLIMIENTO CATEGORIZACIÓN ESI</th>   

                </tr>

                <tr style="background-color: blue; color: white;">

                    <th rowspan="2" width="20%">CAT</th>            

                    <th colspan="3" width="40%" style="text-align:center;">ADULTOS</th>

                    <th colspan="3" width="40%" style="text-align:center;">PEDIÁTRICOS</th>

                </tr>

                <tr style="background-color: blue; color: white;">    

                    <th width="13%" style="text-align:center;">Cantidad DAU</th>

                    <th width="13%" style="text-align:center;">Atendidos a Tiempo</th>

                    <th width="14%" style="text-align:center;">% Atendidos a Tiempo</th>

                    <th width="13%" style="text-align:center;">Cantidad DAU</th>

                    <th width="13%" style="text-align:center;">Atendidos a Tiempo</th>

                    <th width="14%" style="text-align:center;">% Atendidos a Tiempo</th>

                </tr>

            </thead>

            <tbody>'

                .desplegarDetalleCumplimientoCategorizacionESI($objCon, $objReporte, $categorizaciones, $fechas).

            '</tbody>

        </table>

    ';

}



function desplegarDetalleCumplimientoCategorizacionESI ( $objCon, $objReporte, $categorizaciones, $fechas ) {

    $textoADesplegar                    = '';

    $totalCategorizaciones              = count($categorizaciones);

    $parametrosAEnviar[]                = array();

    $parametrosAEnviar['fechaAnterior'] = $fechas['fechaAnterior'];

    $parametrosAEnviar['fechaActual']   = $fechas['fechaActual'];

    $resumenCumplimientoCategorizacionESI     = $objReporte->obtenerResumenCumplimientoCategorizacionESI($objCon, $parametrosAEnviar);

    for ( $i = 0; $i < $totalCategorizaciones; $i++ ) {        

        $textoADesplegar .= '

            <tr>

                <td width="20%">'
                
                    .$categorizaciones[$i].
                    
                '</td>';

                for ( $k = 0; $k < count($resumenCumplimientoCategorizacionESI); $k++ ) {

                    if ( $resumenCumplimientoCategorizacionESI[$k]['tipoAtencion'] == 1 && $resumenCumplimientoCategorizacionESI[$k]['tipoCategorizacion'] == $categorizaciones[$i] ) {

                        break;

                    }

                }         

                $textoADesplegar .= '

                    <td width="13%" style="text-align:center;">'
                    
                        .desplegarNumero($resumenCumplimientoCategorizacionESI[$k]['totalAtencion']). 
                        
                    '</td> 

                    <td width="13%" style="text-align:center;">'
                    
                        .desplegarNumero($resumenCumplimientoCategorizacionESI[$k]['aTiempo']).
                        
                    '</td>

                    <td width="14%" style="text-align:center;">'
                    
                        .desplegarDivisionPorcentual($resumenCumplimientoCategorizacionESI[$k]['aTiempo'], $resumenCumplimientoCategorizacionESI[$k]['totalAtencion']).
                        
                    '%</td>
                    
                ';
                
                for ( $k = 0; $k < count($resumenCumplimientoCategorizacionESI); $k++ ) {

                    if ( $resumenCumplimientoCategorizacionESI[$k]['tipoAtencion'] == 2 && $resumenCumplimientoCategorizacionESI[$k]['tipoCategorizacion'] == $categorizaciones[$i] ) {

                        break;

                    }

                } 

                $textoADesplegar .= '

                    <td width="13%" style="text-align:center;">'
                    
                        .desplegarNumero($resumenCumplimientoCategorizacionESI[$k]['totalAtencion']).
                        
                    '</td> 

                    <td width="13%" style="text-align:center;">'
                    
                        .desplegarNumero($resumenCumplimientoCategorizacionESI[$k]['aTiempo']).
                        
                    '</td>

                    <td width="14%" style="text-align:center;">'
                    
                        .desplegarDivisionPorcentual($resumenCumplimientoCategorizacionESI[$k]['aTiempo'], $resumenCumplimientoCategorizacionESI[$k]['totalAtencion']).
                        
                    '%</td>
                    
                ';                   

        $textoADesplegar .= '
        
            </tr>       
    
        ';

    }

    unset($parametrosAEnviar);

    return $textoADesplegar;

}



function pdfDiagnosticosInespecificos( $objCon, $objReporte, $tipoDiagnostico, $fechas ) {

    return '
    
        <table border="1" cellpadding="2">

            <thead>

                <tr style="background-color: blue; color: white;">

                    <th colspan="7" style="text-align: center;">RESUMEN DIAGNÓSTICOS INESPECÍFICOS</th>   

                </tr>

                <tr style="background-color: blue; color: white;">

                    <th width="20%">Tipo de Diagnóstico</th>            

                    <th width="20%" style="text-align:center;">ADULTOS</th>

                    <th width="20%" style="text-align:center;">% ADULTOS</th>

                    <th width="20%" style="text-align:center;">PEDIÁTRICOS</th>

                    <th width="20%" style="text-align:center;">% PEDIÁTRICOS</th>          

                </tr>

            </thead>

            <tbody>'

                .desplegarDiagnosticosInespecificos($objCon, $objReporte, $tipoDiagnostico, $fechas).

            '</tbody>

        </table>
    
    ';

}



function desplegarDiagnosticosInespecificos ( $objCon, $objReporte, $tipoDiagnostico, $fechas ) {

    $textoADesplegar                    = '';

    $totalTipoDiagnostico               = count($tipoDiagnostico);

    $parametrosAEnviar[]                = array();

    $parametrosAEnviar['fechaAnterior'] = $fechas['fechaAnterior'];

    $parametrosAEnviar['fechaActual']   = $fechas['fechaActual'];

    for ( $i = 0; $i < $totalTipoDiagnostico; $i++ ) {

        $parametrosAEnviar['tipoDiagnostico']   = $tipoDiagnostico[$i];

        $resumenDiagnosticosInespecificos       = $objReporte->obtenerResumenDiagnosticosInespecificos($objCon, $parametrosAEnviar);

        $textoADesplegar .= '

            <tr>

                <td width="20%">'
                
                    .$tipoDiagnostico[$i].
                    
                '</td>            

                <td width="20%" style="text-align:center;">'
                
                    .desplegarNumero($resumenDiagnosticosInespecificos['totalAdultosDiagnostico']).
                    
                '</td> 

                <td width="20%" style="text-align:center;">'
                
                    .desplegarDivisionPorcentual($resumenDiagnosticosInespecificos['totalAdultosDiagnostico'], $resumenDiagnosticosInespecificos['totalAdultos']).
                    
                '%</td> 

                <td width="20%" style="text-align:center;">'
                
                    .desplegarNumero($resumenDiagnosticosInespecificos['totalPediatricosDiagnostico']).
                    
                '</td>          

                <td width="20%" style="text-align:center;">'
                
                    .desplegarDivisionPorcentual($resumenDiagnosticosInespecificos['totalPediatricosDiagnostico'], $resumenDiagnosticosInespecificos['totalPediatricos']).
                    
                '%</td>     

            </tr>        
        
        ';

    }

    unset($parametrosAEnviar);

    return $textoADesplegar;

}



function pdfTiemposLaboratorio ( $objCon, $objReporte, $fechas ) {

    return '
    
        <table border="1" cellpadding="2">

            <thead>

                <tr style="background-color: blue; color: white;">

                    <th colspan="11" style="text-align: center;">RESUMEN TIEMPOS INDICACIONES LABORATORIO</th>   

                </tr>

                <tr style="background-color: blue; color: white;">                   

                    <th rowspan="2" width="5%" style="text-align:center;">DAUs</th>            

                    <th rowspan="2" width="5%" style="text-align:center;">Ind.</th> 

                    <th colspan="3" width="30%" style="text-align:center;">Tiempos desde: Indiación a Toma Muestra</th>            

                    <th colspan="3" width="30%" style="text-align:center;">Tiempos desde: Toma de Muestra a Recepción</th> 

                    <th colspan="3" width="30%" style="text-align:center;">Tiempos desde: Recepción a Realización</th>       

                </tr> 

                <tr style="background-color: blue; color: white;">

                    <th width="10%" style="text-align:center;">T. Promedio</th>            

                    <th width="10%" style="text-align:center;">T. Máximo</th> 

                    <th width="10%" style="text-align:center;">T. Mímimo</th>   

                    <th width="10%" style="text-align:center;">T. Promedio</th>            

                    <th width="10%" style="text-align:center;">T. Máximo</th> 

                    <th width="10%" style="text-align:center;">T. Mímimo</th>   

                    <th width="10%" style="text-align:center;">T. Promedio</th>            

                    <th width="10%" style="text-align:center;">T. Máximo</th> 

                    <th width="10%" style="text-align:center;">T. Mímimo</th>   

                </tr> 

            </thead>

            <tbody>'                             

                .desplegarResumenTiemposLaboratorio($objCon, $objReporte, $fechas).

            '</tbody>

        </table>
    
    ';

}



function desplegarResumenTiemposLaboratorio ( $objCon, $objReporte, $fechas ) {

    $objReporte->crearTablaTemporalTiemposLaboratorio($objCon, $fechas);

    $totalDAUTiemposLaboratorio = $objReporte->obtenerTotalDAUTiemposLaboratorio($objCon);

    $resumenTiemposLaboratorio = $objReporte->obtenerResumenTiemposLaboratorio($objCon);

    return '

        <tr>

            <td width="5%" style="text-align:center;">'.desplegarNumero($totalDAUTiemposLaboratorio).'</td>

            <td width="5%" style="text-align:center;">'.desplegarNumero($resumenTiemposLaboratorio['cantidadIndicaciones']).'</td>   

            <td width="10%" style="text-align:center;">'.desplegarNumero($resumenTiemposLaboratorio['tiempoPromedioInsertaTomaMuestra']).'</td>

            <td width="10%" style="text-align:center;">'.desplegarNumero($resumenTiemposLaboratorio['tiempoMaximoInsertaTomaMuestra']).'</td>

            <td width="10%" style="text-align:center;">'.desplegarNumero($resumenTiemposLaboratorio['tiempoMinimoInsertaTomaMuestra']).'</td>

            <td width="10%" style="text-align:center;">'.desplegarNumero($resumenTiemposLaboratorio['tiempoPromedioTomaMuestraRecepcion']).'</td>

            <td width="10%" style="text-align:center;">'.desplegarNumero($resumenTiemposLaboratorio['tiempoMaximoTomaMuestraRecepcion']).'</td>

            <td width="10%" style="text-align:center;">'.desplegarNumero($resumenTiemposLaboratorio['tiempoMinimoTomaMuestraRecepcion']).'</td>

            <td width="10%" style="text-align:center;">'.desplegarNumero($resumenTiemposLaboratorio['tiempoPromedioRecepcionRealizacion']).'</td>

            <td width="10%" style="text-align:center;">'.desplegarNumero($resumenTiemposLaboratorio['tiempoMaximoRecepcionRealizacion']).'</td>

            <td width="10%" style="text-align:center;">'.desplegarNumero($resumenTiemposLaboratorio['tiempoMinimoRecepcionRealizacion']).'</td>

        </tr>            
     
    ';

}



function pdfTiemposImagenologia ( $objCon, $objReporte, $fechas ) {

    return '
    
        <table border="1" cellpadding="2">

            <thead>

                <tr style="background-color: blue; color: white;">

                    <th colspan="11" style="text-align: center;">RESUMEN TIEMPOS INDICACIONES IMAGENOLOGÍA</th>   

                </tr>

                <tr style="background-color: blue; color: white;">                   

                    <th rowspan="2" width="20%" style="text-align:center;">Tipo Exámen</th>                      

                    <th rowspan="2" width="10%" style="text-align:center;">DAUs</th>            

                    <th rowspan="2" width="10%" style="text-align:center;">Indicaciones</th> 

                    <th colspan="3" width="60%" style="text-align:center;">Tiempos desde: Indicación a Aplicación</th>    

                </tr> 

                <tr style="background-color: blue; color: white;">

                    <th width="20%" style="text-align:center;">Tiempo Promedio</th>            

                    <th width="20%" style="text-align:center;">Tiempo Máximo</th> 

                    <th width="20%" style="text-align:center;">Tiempo Mímimo</th>    

                </tr> 

            </thead>

            <tbody>'                             

                .desplegarResumenTiemposImagenologia($objCon, $objReporte, $fechas).

            '</tbody>

        </table>
    
    ';

}



function desplegarResumenTiemposImagenologia ( $objCon, $objReporte, $fechas ) {

    $textoADesplegar = '';

    $objReporte->crearTablaTemporalTiemposImagenologia($objCon, $fechas);

    $resumenTiemposImagenologia = $objReporte->obtenerResumenTiemposImagenologia($objCon);

    $totalResumenTiemposImagenologia = count($resumenTiemposImagenologia);

    for ( $i = 0; $i < $totalResumenTiemposImagenologia; $i++ ) {
        
        $textoADesplegar .= '

            <tr>

                <td width="20%" style="text-align:center;">'.desplegarNumero($resumenTiemposImagenologia[$i]['tipoExamen']).'</td>

                <td width="10%" style="text-align:center;">'.$objReporte->obtenerTotalDAUTiemposImagenologia($objCon, $resumenTiemposImagenologia[$i]['tipoExamen']).'</td>

                <td width="10%" style="text-align:center;">'.desplegarNumero($resumenTiemposImagenologia[$i]['cantidadIndicaciones']).'</td>   

                <td width="20%" style="text-align:center;">'.desplegarNumero($resumenTiemposImagenologia[$i]['tiempoPromedioInsertaAplica']).'</td>

                <td width="20%" style="text-align:center;">'.desplegarNumero($resumenTiemposImagenologia[$i]['tiempoMaximoInsertaAplica']).'</td>

                <td width="20%" style="text-align:center;">'.desplegarNumero($resumenTiemposImagenologia[$i]['tiempoMinimoInsertaAplica']).'</td>

            </tr>            
        
        ';

    }

    return $textoADesplegar;

}



function desplegarNumero ( $numero ) {

    return ( empty($numero) || is_null($numero) ) ? 0 : $numero;
    
}



function desplegarDivisionPorcentual ( $dividendo, $divisor ) {

    return ( empty($divisor) || is_null($divisor) ) ? 0 : round(($dividendo * 100) / $divisor, 1);

}



function crearTablasTemporales ( $objCon, $objReporte, $fechas ) {

    $parametrosAEnviar['fechaAnterior']   = $fechas['fechaAnterior'];

    $parametrosAEnviar['fechaActual']     = $fechas['fechaActual'];

    $objReporte->crearTablaTemporalTotalMuestras($objCon, $parametrosAEnviar);

    $objReporte->crearTablaTemporalTotalMuestrasNEA($objCon, $parametrosAEnviar);

}
?>