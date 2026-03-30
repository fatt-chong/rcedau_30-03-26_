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


		
$parametros                  = $objUtil->getFormulario($_POST);

$parametros['fechaAnterior'] = date('Y-m-d', strtotime($parametros['fechaAnterior']));
 
$parametros['fechaActual']   = date('Y-m-d', strtotime($parametros['fechaActual']));

$adulto                      = 1;

$pediatrico                  = 2;

$hospitalizado               = 4;

$alta                        = 3;

$objCon                      = $objUtil->cambiarServidorReporte($parametros['fechaAnterior'], $parametros['fechaActual']);

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
$pdf->SetTitle('Resumen Tiempos de Ciclo');
$pdf->SetSubject('Resumen');
$pdf->SetKeywords('Tiempos Ciclo, Resumen');
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

$html .= pdfTitulo($parametros);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfTiemposCicloAdultoPediatrico($parametros['tablasAEnviar'][0], $adulto);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$html .= pdfTiemposCicloAdultoPediatrico($parametros['tablasAEnviar'][1], $pediatrico);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$parametrosFiltro['tipoAtencion'] = $adulto;

$parametrosFiltro['tipoEgreso']   = $hospitalizado;   

$parametrosFiltro['tipoResumen']  = 'cierre';  

$html .= pdfTiemposCicloHospitalizacionUrgencia($parametros['tablasAEnviar'][2], $parametrosFiltro);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$parametrosFiltro['tipoAtencion'] = $adulto;

$parametrosFiltro['tipoEgreso']   = $hospitalizado;   

$parametrosFiltro['tipoResumen']  = 'indicacionEgreso';  

$html .= pdfTiemposCicloHospitalizacionUrgencia($parametros['tablasAEnviar'][3], $parametrosFiltro);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$parametrosFiltro['tipoAtencion'] = $pediatrico;

$parametrosFiltro['tipoEgreso']   = $hospitalizado;   

$parametrosFiltro['tipoResumen']  = 'cierre';  

$html .= pdfTiemposCicloHospitalizacionUrgencia($parametros['tablasAEnviar'][4], $parametrosFiltro);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$parametrosFiltro['tipoAtencion'] = $pediatrico;

$parametrosFiltro['tipoEgreso']   = $hospitalizado;   

$parametrosFiltro['tipoResumen']  = 'indicacionEgreso';  

$html .= pdfTiemposCicloHospitalizacionUrgencia($parametros['tablasAEnviar'][5], $parametrosFiltro);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$parametrosFiltro['tipoAtencion'] = $adulto;

$parametrosFiltro['tipoEgreso']   = $alta;   

$parametrosFiltro['tipoResumen']  = 'cierre';  

$html .= pdfTiemposCicloHospitalizacionUrgencia($parametros['tablasAEnviar'][6], $parametrosFiltro);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$parametrosFiltro['tipoAtencion'] = $adulto;

$parametrosFiltro['tipoEgreso']   = $alta;   

$parametrosFiltro['tipoResumen']  = 'indicacionEgreso';  

$html .= pdfTiemposCicloHospitalizacionUrgencia($parametros['tablasAEnviar'][7], $parametrosFiltro);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$parametrosFiltro['tipoAtencion'] = $pediatrico;

$parametrosFiltro['tipoEgreso']   = $alta;   

$parametrosFiltro['tipoResumen']  = 'cierre';  

$html .= pdfTiemposCicloHospitalizacionUrgencia($parametros['tablasAEnviar'][8], $parametrosFiltro);

$html .= '<br>';

$pdf->writeHTML($html, true, false, true, false, '');

$html = '';

$html .= pdfEstilos();

$parametrosFiltro['tipoAtencion'] = $pediatrico;

$parametrosFiltro['tipoEgreso']   = $alta;   

$parametrosFiltro['tipoResumen']  = 'indicacionEgreso';  

$html .= pdfTiemposCicloHospitalizacionUrgencia($parametros['tablasAEnviar'][9], $parametrosFiltro);

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

// $nombreArchivo = 'resumenTiemposCiclo_'.date('d-m-Y').'.pdf';

// $pdf->Output($nombreArchivo,'FI');

// $url = "/dauRCE/views/reportes/tiemposCiclo/".$nombreArchivo;
// C:\inetpub\wwwroot\php8site\RCEDAU\views\modules\reportes\tiemposCiclo\pdfTiemposCiclo.php

$nombre_archivo = 'resumenTiemposCiclo_'.date('d-m-Y').'.pdf';
$pdf->Output(__DIR__ . '/' . $nombre_archivo, 'FI');
$url = "/RCEDAU/views/modules/reportes/tiemposCiclo/".$nombre_archivo;

$objCon = null;
?>
</iframe>



<div class="embed-responsive embed-responsive-16by9">
	<iframe id="tiemposCicloPDF" class="embed-responsive-item" src="<?=$url?>" height="700" width="700" allowfullscreen></iframe>
</div>



<script>
$('#tiemposCicloPDF').ready(function(){
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



function pdfTitulo ( $parametros ) {

    return '
    
        <table width="100%" border="0">
	
            <tbody>

                <tr>
            
                    <td style="text-align: center;"><h2>Resumen Tiempos de Ciclo</h2></td>
            
                </tr>

                <tr>
            
                    <td style="text-align: center;"><h2>Desde: '.date('d-m-Y', strtotime($parametros['fechaAnterior'])).' Hasta: '.date('d-m-Y', strtotime($parametros['fechaActual'])).'</h2></td>
            
                </tr>
            
            </tbody>

        </table>

    ';

}



function pdfTiemposCicloAdultoPediatrico ( $parametros, $tipoAtencion ) {

    $tituloPrincipal         = 'RESUMEN TIEMPOS DE CICLO ADULTOS';

    $subTitulo               = 'Tiempo desde Admisión a Cierre Dau Definitivo';

    $subTituloTodos          = 'TODOS LOS ADULTOS';

    $subTituloHospitalizados = 'ADULTOS HOSPITALIZADOS';

    $subTituloAlta           = 'ADULTOS DE ALTA';

    if ( $tipoAtencion == 2 ) {

        $tituloPrincipal         = 'RESUMEN TIEMPOS DE CICLO PEDIÁTRICOS';

        $subTitulo               = 'Tiempo desde Admisión a Cierre Dau Definitivo';

        $subTituloTodos          = 'TODOS LOS PEDIÁTRICOS';

        $subTituloHospitalizados = 'PEDIÁTRICOS HOSPITALIZADOS';

        $subTituloAlta           = 'PEDIÁTRICOS DE ALTA';

    }

    return '
    
        <table border="1" cellpadding="2">

            <thead>

                <tr style="background-color: blue; color: white;">

                    <th colspan="13" style="text-align: center;">'.$tituloPrincipal.'</th>   

                </tr>

                <tr style="background-color: blue; color: white;">

                    <th colspan="13">'.$subTitulo.'</th>   

                </tr>

                <tr style="background-color: blue; color: white;">

                    <th width="10%">Categorización</th>            

                    <th width="30%" colspan="4" style="text-align:center;">'.$subTituloTodos.'</th>

                    <th width="30%" colspan="4" style="text-align:center;">'.$subTituloHospitalizados.'</th>

                    <th width="30%" colspan="4" style="text-align:center;">'.$subTituloAlta.'</th>

                </tr>

                <tr style="background-color: blue; color: white;">

                    <th width="10%">&nbsp;</th>

                    <th width="7%" style="text-align:center;">Atendidos</th>

                    <th width="7%" style="text-align:center;">Promedio</th>

                    <th width="8%" style="text-align:center;">Minimo</th>

                    <th width="8%" style="text-align:center;">Máximo</th>

                    <th width="7%" style="text-align:center;">Hosp.</th>

                    <th width="7%" style="text-align:center;">Promedio</th>

                    <th width="8%" style="text-align:center;">Minimo</th>

                    <th width="8%" style="text-align:center;">Máximo</th>

                    <th width="7%" style="text-align:center;">Nº Alta</th>

                    <th width="7%" style="text-align:center;">Promedio</th>

                    <th width="8%" style="text-align:center;">Minimo</th>

                    <th width="8%" style="text-align:center;">Máximo</th>

                </tr>

            </thead>

            <tbody>'

            .desplegarResumenTiemposCicloAdultoPediatrico($parametros).           

            '</tbody>

        </table>

    ';

}



function desplegarResumenTiemposCicloAdultoPediatrico ( $parametros ) {

    $textoADesplegar = '';

    $totalParametros = count($parametros);

    for ( $i = 0; $i < $totalParametros; $i++ ) {

        $textoADesplegar .= '<tr>';

        $textoADesplegar .= '<td width="10%">'.$parametros[$i]['categorizacion'].'</td>';

        $textoADesplegar .= '<td width="7%" style="text-align:center">'.$parametros[$i]['atenciones'].'</td>';

        $textoADesplegar .= '<td width="7%" style="text-align:center">'.$parametros[$i]['tiempoPromedio'].'</td>';

        $textoADesplegar .= '<td width="8%" style="text-align:center">'.$parametros[$i]['tiempoMinimo'].'</td>';

        $textoADesplegar .= '<td width="8%" style="text-align:center">'.$parametros[$i]['tiempoMaximo'].'</td>';    

        $textoADesplegar .= '<td width="7%" style="text-align:center">'.$parametros[$i]['hospitalizados'].'</td>';

        $textoADesplegar .= '<td width="7%" style="text-align:center">'.$parametros[$i]['hospitalizadosTiempoPromedio'].'</td>';

        $textoADesplegar .= '<td width="8%" style="text-align:center">'.$parametros[$i]['hospitalizadosTiempoMinimo'].'</td>';

        $textoADesplegar .= '<td width="8%" style="text-align:center">'.$parametros[$i]['hospitalizadosTiempoMaximo'].'</td>';

        $textoADesplegar .= '<td width="7%" style="text-align:center">'.$parametros[$i]['alta'].'</td>';

        $textoADesplegar .= '<td width="7%" style="text-align:center">'.$parametros[$i]['altaTiempoPromedio'].'</td>';

        $textoADesplegar .= '<td width="8%" style="text-align:center">'.$parametros[$i]['altaTiempoMinimo'].'</td>';

        $textoADesplegar .= '<td width="8%" style="text-align:center">'.$parametros[$i]['altaTiempoMaximo'].'</td>';

        $textoADesplegar .= '</tr>';


    }

    return $textoADesplegar;

}



function pdfTiemposCicloHospitalizacionUrgencia ( $parametros, $filtros  ) { 

    $titulos = array();   

    setearTituloYVariablesCicloHospitalizacionUrgencia($filtros, $titulos);    

    return '

        <table border="1" cellpadding="2">

            <thead>

                <tr style="background-color: blue; color: white;">

                    <th colspan="12" style="text-align: center;">'.$titulos['tituloPrincipal'].'</th>   

                </tr>

                <tr style="background-color: blue; color: white;">

                    <th colspan="12">'.$titulos['subTitulo'].'</th>   

                </tr>

                <tr style="background-color: blue; color: white;">

                    <th width="10%">CAT</th>         

                    <th width="10%" style="text-align: center;">Total</th>      

                    <th width="8%" style="text-align: center;">D1</th>            

                    <th width="8%" style="text-align: center;">D2</th> 

                    <th width="8%" style="text-align: center;">D3</th> 

                    <th width="8%" style="text-align: center;">D4</th> 

                    <th width="8%" style="text-align: center;">D5</th> 

                    <th width="8%" style="text-align: center;">D6</th> 

                    <th width="8%" style="text-align: center;">D7</th> 

                    <th width="8%" style="text-align: center;">D8</th> 

                    <th width="8%" style="text-align: center;">D9</th> 

                    <th width="8%" style="text-align: center;">D10</th> 

                </tr>

            </thead>

            <tbody>'

            .desplegarDetalleTiemposCicloHospitalizacionUrgencia($parametros).

            '</tbody>

        </table>
        
    ';     

}



function setearTituloYVariablesCicloHospitalizacionUrgencia ( $filtros, &$titulos ) {

    if ( $filtros['tipoAtencion'] == 1 && $filtros['tipoEgreso'] == 4 ) {

        if ( $filtros['tipoResumen'] == 'cierre' ) {

            $titulos['tituloPrincipal'] = 'TIEMPOS DE CICLO ADULTOS HOSPITALIZADOS';

            $titulos['subTitulo']       = 'Tiempo desde Admisión a Cierre DAU definitivo en Adulto - Promedio por Deciles';

        } else if ( $filtros['tipoResumen'] == 'indicacionEgreso' ) {

            $titulos['tituloPrincipal'] = 'TIEMPO PROCESOS URGENCIA ADULTOS';

            $titulos['subTitulo']       = 'Tiempo desde Admisión a Indicación de Egreso en Adultos Hospitalizados - Promedio por Deciles';

        }

    }

    if ( $filtros['tipoAtencion'] == 1 && $filtros['tipoEgreso'] == 3 ) {

        if ( $filtros['tipoResumen'] == 'cierre' ) {

            $titulos['tituloPrincipal'] = 'TIEMPOS DE CICLO ADULTOS DE ALTA';

            $titulos['subTitulo']       = 'Tiempo desde Admisión a Cierre DAU definitivo en Adultos de Alta - Promedio por Deciles';

        } else if ( $filtros['tipoResumen'] == 'indicacionEgreso' ) {

            $titulos['tituloPrincipal'] = 'TIEMPO PROCESOS URGENCIA ADULTOS';

            $titulos['subTitulo']       = 'Tiempo desde Admisión a Indicación de Egreso en Adultos de Alta - Promedio por Deciles';

        }

    }

    if ( $filtros['tipoAtencion'] == 2 && $filtros['tipoEgreso'] == 4 ) {

        if ( $filtros['tipoResumen'] == 'cierre' ) {

            $titulos['tituloPrincipal'] = 'TIEMPOS DE CICLO PEDIÁTRICO HOSPITALIZADOS';

            $titulos['subTitulo']       = 'Tiempo desde Admisión a Cierre DAU definitivo en Pediátricos - Promedio por Deciles';

        } else if ( $filtros['tipoResumen'] == 'indicacionEgreso' ) {

            $titulos['tituloPrincipal'] = 'TIEMPO PROCESOS URGENCIA PEDIÁTRICO';

            $titulos['subTitulo']       = 'Tiempo desde Admisión a Indicación de Egreso en Pediátricos Hospitalizados - Promedio por Deciles';

        }

    }

    if ( $filtros['tipoAtencion'] == 2 && $filtros['tipoEgreso'] == 3 ) {

        if ( $filtros['tipoResumen'] == 'cierre' ) {

            $titulos['tituloPrincipal'] = 'TIEMPOS DE CICLO PEDIÁTRICO ALTA';

            $titulos['subTitulo']       = 'Tiempo desde Admisión a Cierre DAU definitivo en Pediátricos de Alta - Promedio por Deciles';

        } else if ( $filtros['tipoResumen'] == 'indicacionEgreso' ) {

            $titulos['tituloPrincipal'] = 'TIEMPO PROCESOS URGENCIA PEDIÁTRICO';

            $titulos['subTitulo']       = 'Tiempo desde Admisión a Indicación de Egreso en Pediátricos de Alta - Promedio por Deciles';

        }

    }

}



function desplegarDetalleTiemposCicloHospitalizacionUrgencia ( $parametros ) {

    $textoADesplegar = '';

    $totalParametros = count($parametros);

    for ( $i = 0; $i < $totalParametros; $i++ ) {

        $textoADesplegar .= '<tr>';

        $textoADesplegar .= '<td width="10%">'.$parametros[$i]['categorizacion'].'</td>';

        $textoADesplegar .= '<td width="10%" style="text-align:center">'.$parametros[$i]['total'].'</td>';

        $textoADesplegar .= '<td width="8%" style="text-align:center">'.$parametros[$i]['d1'].'</td>';

        $textoADesplegar .= '<td width="8%" style="text-align:center">'.$parametros[$i]['d2'].'</td>';

        $textoADesplegar .= '<td width="8%" style="text-align:center">'.$parametros[$i]['d3'].'</td>';

        $textoADesplegar .= '<td width="8%" style="text-align:center">'.$parametros[$i]['d4'].'</td>';

        $textoADesplegar .= '<td width="8%" style="text-align:center">'.$parametros[$i]['d5'].'</td>';

        $textoADesplegar .= '<td width="8%" style="text-align:center">'.$parametros[$i]['d6'].'</td>';

        $textoADesplegar .= '<td width="8%" style="text-align:center">'.$parametros[$i]['d7'].'</td>';

        $textoADesplegar .= '<td width="8%" style="text-align:center">'.$parametros[$i]['d8'].'</td>';

        $textoADesplegar .= '<td width="8%" style="text-align:center">'.$parametros[$i]['d9'].'</td>';

        $textoADesplegar .= '<td width="8%" style="text-align:center">'.$parametros[$i]['d10'].'</td>';

        $textoADesplegar .= '</tr>';

    }

    return $textoADesplegar;

}
?>