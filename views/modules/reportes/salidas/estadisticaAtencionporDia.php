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
$pdf->SetTitle('PDF ESTADISTICA ATENCION POR DÍA');
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
$parametros               = $objUtil->getFormulario($_POST);
$parametros['fechaInicio']=date('Y-m-d',strtotime($parametros['fechaInicio'])); 
$parametros['fechaFin']=date('Y-m-d',strtotime($parametros['fechaFin'])); 

$fechaHoy                 = $objUtil->getFechaPalabra(date('Y-m-d'));




//highlight_string(print_r($parametros),true);



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
      <table td width="50%" align="left" border="">
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
  <td width="550" align="center" class="titulos"><strong class="titulotabla">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ESTADISTICA DE ATENCIONES DIARIAS DE URGENCIA &nbsp;&nbsp;&nbsp;[PERIODO: '.$objUtil->cambiarFormatoFecha2($parametros['fechaInicio']).' AL '.$objUtil->cambiarFormatoFecha2($parametros['fechaFin']).']</strong></td>
    <!-- <td width="85" align="center" class="titulos"><span class="derechosreservados"></span></td> -->
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2" align="center">&nbsp;</td>
  </tr>

  <tr>
    <td colspan="3" >
     &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; 
        <table border="1" cellpadding="2" cellspacing="0" class="reporte" name="tablaexport" id="tablaexport" >

          <tr align="center" valign="top">
            
            <td width="90" align="center" valign="top" bgcolor="#CCCCCC"><strong>FECHA</strong> </td>
            <td width="110" align="center" bgcolor="#CCCCCC"><strong> ADULTO</strong></td>
            <td width="110" align="center" bgcolor="#CCCCCC"><strong> PEDIATRICO</strong></td>
            <td width="110" align="center" bgcolor="#CCCCCC"><strong> GINECOLOGICO</strong></td>
            <td width="110" align="center" bgcolor="#CCCCCC"><strong> TOTAL</strong></td>
          </tr>
 ';

    $fechaInicio = $parametros['fechaInicio'];
    $fechaFin    = $parametros['fechaFin'];

    // CODIGO PARA GENERAR GRAFICO
          // require_once('../../../../../estandar/TCPDF-main/tcpdf.php');
    require_once('../../../../../estandar/assets/jpgraph-4.4.2/src/jpgraph.php');
    require_once('../../../../../estandar/assets/jpgraph-4.4.2/src/jpgraph_bar.php');

    // require_once('../../../../assets/libs/jpgraph-4.4.2/src/jpgraph.php');
          // require_once('../../../../assets/libs/jpgraph-4.4.2/src/jpgraph_line.php');
    // require_once('../../../../assets/libs/jpgraph-4.4.2/src/jpgraph_bar.php');
    // include '../../../assets/libs/libchart/classes/libchart.php';

    $fecha = $fechaInicio;
    $i = 0;
    $acum_adulto = 0;
    $acum_pediatrico = 0;
    $acum_ginecologico = 0;

    $data_adulto = [];
    $data_pediatrico = [];
    $data_ginecologico = [];
    $labels = [];

    while ($fecha <= $fechaFin) {
        // Datos obtenidos de la base de datos
        $parametros['fechaInicio'] = $fecha;

        $parametros['tipoAtencion'] = 1;
        $datosAdulto = $reporte->estadisticaAtencionPorDia($objCon, $parametros);

        $parametros['tipoAtencion'] = 2;
        $datosPediatrico = $reporte->estadisticaAtencionPorDia($objCon, $parametros);

        $parametros['tipoAtencion'] = 3;
        $datosGinecologico = $reporte->estadisticaAtencionPorDia($objCon, $parametros);

        // Datos por tipo de atención
        $ateAdult = $datosAdulto[0]['cantidad'];
        $ate_pediatrico = $datosPediatrico[0]['cantidad'];
        $ate_ginecologico = $datosGinecologico[0]['cantidad'];
        $totalColumnas = $ateAdult + $ate_pediatrico + $ate_ginecologico;

        // Formatear la fecha
        $fechaAtencion = date("d-m-Y", strtotime($fecha));

        // Generar fila HTML
        $html .= '
            <tr align="left" valign="top">             
                <td>' . $fechaAtencion . '</td>
                <td align="right" valign="bottom">' . $ateAdult . '</td>
                <td align="right" valign="bottom">' . $ate_pediatrico . '</td>
                <td align="right" valign="bottom">' . $ate_ginecologico . '</td>
                <td align="right" valign="bottom">' . $totalColumnas . '</td>
            </tr>';

        // Agregar datos a los arrays
        $data_adulto[] = $ateAdult;
        $data_pediatrico[] = $ate_pediatrico;
        $data_ginecologico[] = $ate_ginecologico;
        $labels[] = $fechaAtencion; // Etiqueta de la fecha

        // Incrementar la fecha
        $ano = substr($fecha, 0, 4);
        $mes = substr($fecha, 5, 2);
        $dia = substr($fecha, 8, 2);
        $fecha = date("Y-m-d", mktime(0, 0, 0, $mes, $dia + 1, $ano));
    }
    $urlGraficoAdulto = crearGrafico($data_adulto, $labels, "Atenciones Adultas", "at_diarias_adulta.png");
    $urlGraficoPediatrico = crearGrafico($data_pediatrico, $labels, "Atenciones Pediátricas", "at_diarias_pediatrica.png");
    $urlGraficoGinecologico = crearGrafico($data_ginecologico, $labels, "Atenciones Ginecológicas", "at_diarias_ginecologica.png");



   
   
    $html.='
          <tr align="left" valign="top">             
            <td><strong>TOTAL</strong></td>
            <td align="right"><strong>'.$acum_adulto.'</strong></td>
            <td align="right"><strong> '.$acum_pediatrico.' </strong></td>
            <td align="right"><strong> '.$acum_ginecologico.' </strong></td>
            <td align="right"><strong>  '.$acumTotal.' </strong></td>
          </tr>

        </table>
      </td>

    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="reporte">
        <tr>
          <td colspan="2" align="center"><img src="'.PATH.'/views/reportes/graficos/at_diarias_adulta.png" width="800" height="300" /></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align="center"><img src="'.PATH.'/views/reportes/graficos/at_diarias_pediatrica.png" width="800" height="300" /></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" align="center"><img src="'.PATH.'/views/reportes/graficos/at_diarias_ginecologica.png" width="800" height="300" /></td>
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
     <!--    <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr> -->
       <!--  <tr>
          <td colspan="2">&nbsp;</td>
        </tr> -->
        <tr>
          <td align="center">...........................................................................</td>
          <td align="center">...........................................................................</td>
        </tr>
        <tr>
          <td width="50%" align="center"><strong>FIRMA ENCARGADO RESPONSABLE</strong></td>
          <td align="center"><strong>FIRMA JEFE RESPONSABLE</strong></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
   
  </table>';


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
  

  $nombre_archivo = "estadisticaAteporDia.pdf";
  $pdf->Output(__DIR__ . '/' . $nombre_archivo, 'FI');
  $url = "/RCEDAU/views/modules/reportes/salidas/".$nombre_archivo;
  ?>
</iframe>

<div class="embed-responsive embed-responsive-16by9">
  <iframe id="iframeAtePorDia" class="embed-responsive-item" src="<?=$url?>" height="700" width="700" allowfullscreen></iframe>
</div>

<script>
  $('#iframeAtePorDia').ready(function(){
    ajaxRequest(raiz+'/controllers/server/reportes/main_controller.php','nombreArchivo=<?=$url?>&accion=eliminarPDF', 'POST', 'JSON', 1, '', true);
  });
</script>