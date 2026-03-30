<iframe height="100%" width="100%" hidden>
	<?php
		//echo "asd";
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
		$pdf->SetTitle('PDF RESUMEN DE ATENCIONES MÉDICAS  POR FECHA (ADMISIÓN)');
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

		require("../../../../config/config.php");
		require_once('../../../../class/Connection.class.php');     $objCon     = new Connection; $objCon->db_connect();
		require_once('../../../../class/Util.class.php');           $objUtil    = new Util;
		require_once('../../../../class/Reportes.class.php');       $reporte    = new Reportes;
		$i = $objUtil->fechaInvertida($_POST['fechaInicio']);
		$f = $objUtil->fechaInvertida($_POST['fechaFin']);
        $adulto                                 = $reporte->atencionAdulto2($objCon,$i,$f);
        $pediatrico                             = $reporte->atencionPediatrica2($objCon,$i,$f);
        $ginecologico                           = $reporte->atencionGinecologica2($objCon,$i,$f);
	   	$fechaHoy        = $objUtil->fechaNormal(date('Y-m-d')); 
	   	$total  = 0; 
	   	$total2 = 0;  
	   	$total3 = 0;
   		// highlight_string(print_r($i),true);
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

				<tr align="left">
					<td>GOBIERNO DE CHILE</td>
				</tr>

				<tr  align="left">
					<td>MINISTERIO DE SALUD</td>
				</tr>

				<tr  align="left">
					<td>HOSPITAL DR. JUAN NOÉ CREVANI</td>
				</tr>

				<tr align="left">
					<td>RUT: 61.606.000-7</td>
				</tr>

				<tr  align="left">
					<td>18 DE SEPTIEMBRE N°1000</td>
				</tr>
			</td>
				<td>
					<table td width="50%" align="left" >
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;						
						<tr>
							'.$fechaHoy.'
						</tr>
					</table>
				</td>
			</tr>
		</table>

          <div align="center">RESUMEN  DE ATENCIONES MÉDICAS POR FECHA (ADMISIÓN)<br />
          PERIODO: '.$i.' AL '.$f.'<br/><br/></div>
      <table border="1" width="100%">
                    <tr>
                      <td colspan="2" align="center" style="background-color:#337ab7;color:#fff;">ATENCIÓN: PEDIÁTRICA</td>
                    </tr>
                    <tr>
                      <td align="center">PROFESIONAL</td>
                      <td align="center">N°</td>
                    </tr>';
                for ($i=0; $i<count($adulto); $i++) {
                $html .='
                    <tr>
                        <td align="center">'.$adulto[$i]['PROdescripcion'].'</td>
                        <td align="center">'.$adulto[$i]['TOTAL'].'</td>
                    </tr>
              <tr>
                <td align="center" style="background-color:#337ab7;color:#fff;">TOTAL DE ATENCIONES</td>
                <td align="center" style="background-color:#337ab7;color:#fff;">'; 
                $total = $total + $adulto[$i]['TOTAL'];
                $html .='
                '.$total.'
                </td>
              </tr>'; 
                  }       
                $html .='
      </table>
      <br/>
      <table border="1" width="100%">
                    <tr>
                      <td colspan="2" align="center" style="background-color:#337ab7;color:#fff;">ATENCIÓN: GINECOLÓGICA</td>
                    </tr>
                    <tr>
                      <td align="center">PROFESIONAL</td>
                      <td align="center">N°</td>
                    </tr>';
                for ($i=0; $i<count($pediatrico); $i++) {
                $html .='
                    <tr>
                        <td align="center">'.$pediatrico[$i]['PROdescripcion'].'</td>
                        <td align="center">'.$pediatrico[$i]['TOTAL'].'</td>
                    </tr>
              <tr>
                <td align="center" style="background-color:#337ab7;color:#fff;">TOTAL DE ATENCIONES</td>
                <td align="center" style="background-color:#337ab7;color:#fff;">'; 
                $total2 = $total + $pediatrico[$i]['TOTAL'];
                $html .='
                '.$total2.'
                </td>
              </tr>'; 
                  }       
                $html .='
      </table>
      <br/>
      <table border="1" width="100%">
                    <tr>
                      <td colspan="2" align="center" style="background-color:#337ab7;color:#fff;">ATENCIÓN: ADULTO</td>
                    </tr>
                    <tr>
                      <td align="center">PROFESIONAL</td>
                      <td align="center">N°</td>
                    </tr>';
                for ($i=0; $i<count($ginecologico); $i++) {
                $html .='
                    <tr>
                        <td align="center">'.$ginecologico[$i]['PROdescripcion'].'</td>
                        <td align="center">'.$ginecologico[$i]['TOTAL'].'';  
                $html .='
                        </td>
                    </tr>
              <tr>
                <td align="center" style="background-color:#337ab7;color:#fff;">TOTAL DE ATENCIONES</td>
                <td align="center" style="background-color:#337ab7;color:#fff;">'; 
                $total3 = $total + $ginecologico[$i]['TOTAL'];
                $html .='
                '.$total3.'
                </td>
              </tr>'; 
                  }       
                $html .='
      </table>
		<table border="0">
			<br /><br /><br /><br />
			<tr align="center">
			<td align="center">...........................................................................</td>
			<td align="center">...........................................................................</td>
			</tr>
			<tr align="center">
			<td align="center"><strong>FIRMA ENCARGADO RESPONSABLE</strong></td>
			<td align="center"><strong>FIRMA JEFE RESPONSABLE</strong></td>
			</tr>
		</table>
         ';

	$pdf->writeHTML($html, true, false, true, false, '');


	$nombre_archivo = "resumenAtencionesAdmision.pdf";
	$pdf->Output(__DIR__ . '/' . $nombre_archivo, 'FI');
	$url = "/RCEDAU/views/modules/reportes/salidas/".$nombre_archivo;
?>
</iframe>
<?php //highlight_string(print_r($ginecologico),true); ?>
<div class="embed-responsive embed-responsive-16by9">
	<iframe id="iframeBincard" class="embed-responsive-item" src="<?=$url?>" height="700" width="700" allowfullscreen></iframe>
</div>
<script>
$('#iframeBincard').ready(function(){
	ajaxRequest(raiz+'/controllers/server/reportes/main_controller.php','nombreArchivo=<?=$url?>&accion=eliminarPDF', 'POST', 'JSON', 1, '', true);
});
</script>