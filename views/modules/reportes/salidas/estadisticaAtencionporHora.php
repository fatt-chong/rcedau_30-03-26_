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

  class MYPDF extends TCPDF {
  //Page header
  public function Header() {
  //get the current page break margin
  $bMargin = $this->getBreakMargin();
  // get current auto-page-break mode
  $auto_page_break = $this->AutoPageBreak;
  // disable auto-page-break
  $this->SetAutoPageBreak(false, 0);
  // restore auto-page-break status
  $this->SetAutoPageBreak($auto_page_break, $bMargin);
  // set the starting point for the page content
  $this->setPageMark();
}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//SET DOCUMENT INFORMATION
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('DAU');
$pdf->SetTitle('PDF ESTADISTICA ATENCION POR HORA');
$pdf->SetSubject('cccccc');
$pdf->SetKeywords('dddd, eeee, fffff');
//$pdf->SetHeaderData('../../assets/img/ABA-05.png', PDF_HEADER_LOGO_WIDTH,'SERVICIO DE SALUD ARICA ','HOSPITAL REGIONAL DE ARICA Y PARINACOTA');
$pdf->setHeaderFont(Array('helvetica', '', 6));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, 8, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setLanguageArray($l);
$pdf->setFontSubsetting(true);
$pdf->SetFont('helvetica', '', 8, '', true);
//CREA UNA PAGINA
$pdf->AddPage();
$pdf->setPrintFooter(false);

require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php');     $objCon     = new Connection; $objCon->db_connect();
require_once('../../../../class/Util.class.php');           $objUtil    = new Util;
require_once('../../../../class/Reportes.class.php');       $reporte    = new Reportes;


require_once('../../../../../estandar/assets/jpgraph-4.4.2/src/jpgraph.php');
require_once('../../../../../estandar/assets/jpgraph-4.4.2/src/jpgraph_bar.php');
    
// require_once('../../../../assets/libs/jpgraph-4.4.2/src/jpgraph.php');
      // require_once('../../../../assets/libs/jpgraph-4.4.2/src/jpgraph_line.php');
// require_once('../../../../assets/libs/jpgraph-4.4.2/src/jpgraph_bar.php');

// include '../../../assets/libs/libchart/classes/libchart.php';
$parametros               = $objUtil->getFormulario($_POST);
$parametros['fechaInicio']=date('Y-m-d',strtotime($parametros['fechaInicio'])); 
$parametros['fechaFin']=date('Y-m-d',strtotime($parametros['fechaFin'])); 

$fechaHoy                 = $objUtil->getFechaPalabra(date('Y-m-d'));


$ate_adulto=1;
$ate_pediatrico=2;
$ate_ginecologico=3;


  $dau[0]['hora']= '00-01';
  $dau[1]['hora']= '01-02';
  $dau[2]['hora']= '02-03';
  $dau[3]['hora']= '03-04';
  $dau[4]['hora']= '04-05';
  $dau[5]['hora']= '05-06';
  $dau[6]['hora']= '06-07';
  $dau[7]['hora']= '07-08';
  $dau[8]['hora']= '08-09';
  $dau[9]['hora']= '09-10';
  $dau[10]['hora']= '10-11';
  $dau[11]['hora']= '11-12';
  $dau[12]['hora']= '12-13';
  $dau[13]['hora']= '13-14';
  $dau[14]['hora']= '14-15';
  $dau[15]['hora']= '15-16';
  $dau[16]['hora']= '16-17';
  $dau[17]['hora']= '17-18';
  $dau[18]['hora']= '18-19';
  $dau[19]['hora']= '19-20';
  $dau[20]['hora']= '20-21';
  $dau[21]['hora']= '21-22';
  $dau[22]['hora']= '22-23';
  $dau[23]['hora']= '23-24';

if ($ate_adulto==1) {
  $parametros['tipoAtencion']=1;  
  $dau[0]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '00:00','01:00');
  $dau[1]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '01:00','02:00');
  $dau[2]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '02:00','03:00');
  $dau[3]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '03:00','04:00');
  $dau[4]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '04:00','05:00');
  $dau[5]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '05:00','06:00');
  $dau[6]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '06:00','07:00');
  $dau[7]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '07:00','08:00');
  $dau[8]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '08:00','09:00');
  $dau[9]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '09:00','10:00');
  $dau[10]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '10:00','11:00');
  $dau[11]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '11:00','12:00');
  $dau[12]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '12:00','13:00');
  $dau[13]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '13:00','14:00');
  $dau[14]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '14:00','15:00');
  $dau[15]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '15:00','16:00');
  $dau[16]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '16:00','17:00');
  $dau[17]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '17:00','18:00');
  $dau[18]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '18:00','19:00');
  $dau[19]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '19:00','20:00');
  $dau[20]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '20:00','21:00');
  $dau[21]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '21:00','22:00');
  $dau[22]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '22:00','23:00');
  $dau[23]['adulto']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '23:00','24:00');
}

if ($ate_pediatrico==2) {
  $parametros['tipoAtencion']=2;
  $dau[0]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '00:00','01:00');
  $dau[1]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '01:00','02:00');
  $dau[2]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '02:00','03:00');
  $dau[3]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '03:00','04:00');
  $dau[4]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '04:00','05:00');
  $dau[5]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '05:00','06:00');
  $dau[6]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '06:00','07:00');
  $dau[7]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '07:00','08:00');
  $dau[8]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '08:00','09:00');
  $dau[9]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '09:00','10:00');
  $dau[10]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '10:00','11:00');
  $dau[11]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '11:00','12:00');
  $dau[12]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '12:00','13:00');
  $dau[13]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '13:00','14:00');
  $dau[14]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '14:00','15:00');
  $dau[15]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '15:00','16:00');
  $dau[16]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '16:00','17:00');
  $dau[17]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '17:00','18:00');
  $dau[18]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '18:00','19:00');
  $dau[19]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '19:00','20:00');
  $dau[20]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '20:00','21:00');
  $dau[21]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '21:00','22:00');
  $dau[22]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '22:00','23:00');
  $dau[23]['pediatrico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '23:00','24:00');
}

if ($ate_ginecologico==3) {
  $parametros['tipoAtencion']=3;
  $dau[0]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '00:00','01:00');
  $dau[1]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '01:00','02:00');
  $dau[2]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '02:00','03:00');
  $dau[3]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '03:00','04:00');
  $dau[4]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '04:00','05:00');
  $dau[5]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '05:00','06:00');
  $dau[6]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '06:00','07:00');
  $dau[7]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '07:00','08:00');
  $dau[8]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '08:00','09:00');
  $dau[9]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '09:00','10:00');
  $dau[10]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '10:00','11:00');
  $dau[11]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '11:00','12:00');
  $dau[12]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '12:00','13:00');
  $dau[13]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '13:00','14:00');
  $dau[14]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '14:00','15:00');
  $dau[15]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '15:00','16:00');
  $dau[16]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '16:00','17:00');
  $dau[17]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '17:00','18:00');
  $dau[18]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '18:00','19:00');
  $dau[19]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '19:00','20:00');
  $dau[20]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '20:00','21:00');
  $dau[21]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '21:00','22:00');
  $dau[22]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '22:00','23:00');
  $dau[23]['ginecologico']= $reporte->estadisticaAtencionPorHora($objCon,$parametros, '23:00','24:00');
}

//highlight_string(print_r($dau),true);

$html = '

<table width="765" border="0">
  <tr>
    <td border="0" width="115">
      <pre></pre>
      <img src="'.PATH.'/assets/img/logo.png" width="55" height="55" />
      <img src="'.PATH.'/assets/img/nuestroHospital.png" width="55" height="55" />
    </td>

    <td  border="0" valign="top">
      <pre></pre>

      <tr>
        <td>GOBIERNO DE CHILE</td>
      </tr>

      <tr>
        <td>MINISTERIO DE SALUD</td>
      </tr>

      <tr>
        <td>HOSPITAL DR. JUAN NOÉ CREVANI</td>
      </tr>

      <tr>
        <td>RUT: 61.606.000-7</td>
      </tr>

      <tr>
        <td>18 DE SEPTIEMBRE N°1000</td>
      </tr>
    </td>
    <td>
      <table td width="50%" align="left">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;


        <tr>
          '.$fechaHoy.'
        </tr>

        <tr>

        </tr>
      </table>
    </td>
  </tr>
</table>

<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">

  <tr align="center">  
   <td width="550" align="center" class="titulos"><strong class="titulotabla">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ESTADISTICA DE ATENCIONES POR HORA DE URGENCIA &nbsp;&nbsp;&nbsp;[PERIODO: '.$objUtil->cambiarFormatoFecha2($parametros['fechaInicio']).' AL '.$objUtil->cambiarFormatoFecha2($parametros['fechaFin']).']</strong>
   </td>   
  </tr>

  <tr>
    <td colspan="3" class="foliochico">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center">
      <table border="1" cellpadding="2" cellspacing="0" class="reporte" name="tablaexport" id="tablaexport">
    
      <tr align="left" valign="top">
        <td width="180" align="center" valign="top" bgcolor="#CCCCCC"><strong>HORA</strong></td>
        <td width="110" align="center" bgcolor="#CCCCCC"><strong>ADULTO</strong></td>
        <td width="110" align="center" bgcolor="#CCCCCC"><strong>PEDIATRICO</strong></td>
        <td width="110" align="center" bgcolor="#CCCCCC"><strong>GINECOLOGICO</strong></td>

        <td width="110" align="center" bgcolor="#CCCCCC"><strong>TOTAL</strong></td>
        </tr>
      ';
    //GRAFICO ADULTO-------------------------------
    // $graf_adulto = new VerticalBarChart(800, 300);
    // $set_adulto = new XYDataSet();

    // //GRAFICO PEDIATRICO----------------------------    
    // $graf_pediatrico = new VerticalBarChart(800, 300);
    // $set_pediatrico = new XYDataSet(); 

    // //GRAFICO GINECOLOGICO----------------------------
    // $graf_ginecologica = new VerticalBarChart(800, 300);
    // $set_ginecologica = new XYDataSet();               

    $data_adulto = [];
    $data_pediatrico = [];
    $data_ginecologico = [];
    $labels = [];
    // $html = ''; // Asegurarse de inicializar la variable HTML

    $acum_adulto = 0;
    $acum_pediatrico = 0;
    $acum_ginecologico = 0;

    for ($i = 0; $i < count($dau); $i++) { 
        $hora = $dau[$i]['hora'];
        $atencionAdulto = $dau[$i]['adulto'][0]['cantidad'];
        $atencionPediatrico = $dau[$i]['pediatrico'][0]['cantidad'];
        $atencionGinecologico = $dau[$i]['ginecologico'][0]['cantidad'];
        $total = $atencionAdulto + $atencionPediatrico + $atencionGinecologico;

        // Generar fila HTML
        $html .= '
            <tr align="left" valign="top">
                <td align="center">' . $hora . '</td>
                <td align="right" valign="bottom">' . $atencionAdulto . '</td>
                <td align="right" valign="bottom">' . $atencionPediatrico . '</td>
                <td align="right" valign="bottom">' . $atencionGinecologico . '</td>
                <td align="right" valign="bottom">' . $total . '</td>
            </tr>';

        // Acumular datos
        $acum_adulto += $atencionAdulto;
        $acum_pediatrico += $atencionPediatrico;
        $acum_ginecologico += $atencionGinecologico;

        // Agregar datos a los arrays
        $data_adulto[] = $atencionAdulto;
        $data_pediatrico[] = $atencionPediatrico;
        $data_ginecologico[] = $atencionGinecologico;
        $labels[] = $hora;
    }

    // Generar gráficos después del bucle
    $urlGraficoAdulto = crearGrafico($data_adulto, $labels, "Atenciones Adultas", "atxhora_adulta.png");
    $urlGraficoPediatrico = crearGrafico($data_pediatrico, $labels, "Atenciones Pediátricas", "atxhora_pediatrica.png");
    $urlGraficoGinecologico = crearGrafico($data_ginecologico, $labels, "Atenciones Ginecológicas", "atxhora_ginecologica.png");

    $html.='      
      <tr align="left" valign="top">
        <td><strong>TOTAL</strong></td>
        <td align="right"><strong> '.$acum_adulto.'</strong></td>
        <td align="right"><strong> '.$acum_pediatrico.'</strong></td>
        <td align="right"><strong> '.$acum_ginecologico.'</strong></td>
        <td align="right"><strong> '.$totalAcum.' </strong></td>
      </tr>

    </table></td>
  </tr>

  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    
    <table width="650" border="0" cellpadding="0" cellspacing="0" class="reporte">
      <tr>
        <td colspan="2" align="center"><img src="'.PATH.'/views/reportes/graficos/atxhora_adulta.png" width="840" height="300" /></td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align="center"><img src="'.PATH.'/views/reportes/graficos/atxhora_pediatrica.png" width="840" height="300" /></td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align="center"><img src="'.PATH.'/views/reportes/graficos/atxhora_ginecologica.png" width="840" height="300" /></td>
      </tr>
    </table>
    <table>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td align="center">...........................................................................</td>
        <td align="center">...........................................................................</td>
      </tr>
      <tr>
        <td width="50%" align="center"><strong>FIRMA ENCARGADO RESPONSABLE</strong></td>
        <td align="center"><strong>FIRMA JEFE RESPONSABLE</strong></td>
      </tr>
    </table>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
 
</table>

';
   function crearGrafico($data, $labels, $titulo, $archivo_salida) {
    $path = __DIR__ . '/../../../reportes/graficos/';
    $filename = $archivo_salida;
    $fullPath = $path . $filename;

    // Eliminar archivo existente si ya existe
    if (file_exists($fullPath)) {
        unlink($fullPath);
    }

    // Crear el gráfico
    $grafico = new Graph(800, 300);
    $grafico->SetScale("textlin");

    // Estilo del gráfico
    $grafico->xaxis->SetTickLabels($labels);
    $grafico->xaxis->SetLabelAngle(50);
    $grafico->title->Set($titulo);

    // Crear la barra de datos
    $barra = new BarPlot($data);
    $barra->SetColor("blue");
    $barra->SetFillColor("blue");

    $grafico->Add($barra);

    // Guardar el gráfico en el archivo
    $grafico->Stroke($fullPath);

    // Devolver la URL relativa del gráfico
    return PATH . '/views/reportes/graficos/' . $filename;
  }

  $pdf->writeHTML($html, true, false, true, false, '');


  $nombre_archivo = "estadisticaAtencionPorHora.pdf";
  $pdf->Output(__DIR__ . '/' . $nombre_archivo, 'FI');
  $url = "/RCEDAU/views/modules/reportes/salidas/".$nombre_archivo;


  ?>
</iframe>

<div class="embed-responsive embed-responsive-16by9">
  <iframe id="iframAtencionPorHora" class="embed-responsive-item" src="<?=$url?>" height="700" width="700" allowfullscreen></iframe>
</div>

<script>
  $('#iframAtencionPorHora').ready(function(){
    ajaxRequest(raiz+'/controllers/server/reportes/main_controller.php','nombreArchivo=<?=$url?>&accion=eliminarPDF', 'POST', 'JSON', 1, '', true);
  });
</script>

