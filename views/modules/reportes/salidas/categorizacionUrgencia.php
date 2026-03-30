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
		$pdf->SetTitle('PDF INFORME CATEGORIZACIÓN URGENCIA');
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
		$parametros                 = $objUtil->getFormulario($_POST);
		$parametros["fechaInicio"] 	= $objUtil->cambiarFormatoFecha2($parametros["fechaInicio"]);
		$parametros["fechaFin"] 	  = $objUtil->cambiarFormatoFecha2($parametros["fechaFin"]);
		$fechaHoy                   = $objUtil->getFechaPalabra(date('Y-m-d'));
		$tipoAtencion 							= array(0 => "TODOS", 1 => "ADULTO", 2 => "PEDIÁTRICO", 3 => "GINECOLÓGICO");
		$categorizacionesUrgencias  = $reporte->reporteCategorizacionTotalUrgencia($objCon, $parametros);

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

		<table border="0">
				<tr>
					<td align="center">
						<strong style="font-size:10; color: ">INFORME CATEGORIZACIÓN URGENCIA '.$_POST['fechaInicio'].' AL '.$_POST['fechaFin'].'</strong>
					</td>
				</tr>
				<tr>
					<td align="center">
						<strong style="font-size:10; color: ">TIPO ATENCIÓN: '.$tipoAtencion[$parametros["tipoAtencion"]].'</strong>
					</td>
		    </tr>
		    <br>
		    <tr>
    			<td colspan="3" align="center">
					<table border="1" cellpadding="2" cellspacing="0" class="reporte" name="tablaexport" id="tablaexport">
						<tr align="center" valign="top" bgcolor="#4682b4" style="color:#FFF;">
							<td width="20%" style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;border-top: 1px solid #E0E0E0;border-left: 1px solid #E0E0E0;"><strong>FECHAS</strong></td>
							<td width="10%" style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;border-top: 1px solid #E0E0E0;border-left: 1px solid #E0E0E0;"><strong>ATENDIDOS</strong></td>
							<td width="10%" style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;border-top: 1px solid #E0E0E0;border-left: 1px solid #E0E0E0;"><strong>C-1</strong></td>
							<td width="10%" style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;border-top: 1px solid #E0E0E0;border-left: 1px solid #E0E0E0;"><strong>C-2</strong></td>
							<td width="10%" style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;border-top: 1px solid #E0E0E0;border-left: 1px solid #E0E0E0;"><strong>C-3</strong></td>
							<td width="10%" style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;border-top: 1px solid #E0E0E0;border-left: 1px solid #E0E0E0;"><strong>C-4</strong></td>
							<td width="10%" style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;border-top: 1px solid #E0E0E0;border-left: 1px solid #E0E0E0;"><strong>C-5</strong></td>
							<td width="10%" style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;border-top: 1px solid #E0E0E0;border-left: 1px solid #E0E0E0;"><strong>NEA S/CAT.</strong></td>
							<td width="10%" style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;border-top: 1px solid #E0E0E0;border-left: 1px solid #E0E0E0;"><strong>NEA C/CAT.</strong></td>
						</tr>';

						$totalAdmisionados = 0;
						$totalCAT1 = 0;
						$totalCAT2 = 0;
						$totalCAT3 = 0;
						$totalCAT4 = 0;
						$totalCAT5 = 0;
						$totalNEASinCAT = 0;
						$totalNEAConCAT = 0;

						foreach ($categorizacionesUrgencias as $categorizacionUrgencia) {
							$totalAdmisionados = $totalAdmisionados + $categorizacionUrgencia["totalAdmisionados"];
							$totalCAT1 = $totalCAT1 + $categorizacionUrgencia["totalCAT1"];
							$totalCAT2 = $totalCAT2 + $categorizacionUrgencia["totalCAT2"];
							$totalCAT3 = $totalCAT3 + $categorizacionUrgencia["totalCAT3"];
							$totalCAT4 = $totalCAT4 + $categorizacionUrgencia["totalCAT4"];
							$totalCAT5 = $totalCAT5 + $categorizacionUrgencia["totalCAT5"];
							$totalNEASinCAT = $totalNEASinCAT + $categorizacionUrgencia["totalNEASinCAT"];
							$totalNEAConCAT = $totalNEAConCAT + $categorizacionUrgencia["totalNEAConCAT"];

							$html .='
								<tr>
									<td  style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;border-left: 1px solid #E0E0E0;">'.$objUtil->cambiarFormatoFecha($categorizacionUrgencia["fechaAdmision"]).'</td>
									<td  style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;" align="right">'.$categorizacionUrgencia["totalAdmisionados"].'</td>
									<td  style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;" align="right">'.$categorizacionUrgencia["totalCAT1"].'</td>
									<td  style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;" align="right">'.$categorizacionUrgencia["totalCAT2"].'</td>
									<td  style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;" align="right">'.$categorizacionUrgencia["totalCAT3"].'</td>
									<td  style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;" align="right">'.$categorizacionUrgencia["totalCAT4"].'</td>
									<td  style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;" align="right">'.$categorizacionUrgencia["totalCAT5"].'</td>
									<td  style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;" align="right">'.$categorizacionUrgencia["totalNEASinCAT"].'</td>
									<td  style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;" align="right">'.$categorizacionUrgencia["totalNEAConCAT"].'</td>
								</tr>
							';
						}

						$html .='
							<tr align="right" valign="top" bgcolor="#4682b4" style="color:#FFF;">
								<td width="20%" style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;border-top: 1px solid #E0E0E0;border-left: 1px solid #E0E0E0;"><strong>TOTAL</strong></td>
								<td width="10%" style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;border-top: 1px solid #E0E0E0;border-left: 1px solid #E0E0E0;"><strong>'.$totalAdmisionados.'</strong></td>
								<td width="10%" style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;border-top: 1px solid #E0E0E0;border-left: 1px solid #E0E0E0;"><strong>'.$totalCAT1.'</strong></td>
								<td width="10%" style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;border-top: 1px solid #E0E0E0;border-left: 1px solid #E0E0E0;"><strong>'.$totalCAT2.'</strong></td>
								<td width="10%" style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;border-top: 1px solid #E0E0E0;border-left: 1px solid #E0E0E0;"><strong>'.$totalCAT3.'</strong></td>
								<td width="10%" style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;border-top: 1px solid #E0E0E0;border-left: 1px solid #E0E0E0;"><strong>'.$totalCAT4.'</strong></td>
								<td width="10%" style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;border-top: 1px solid #E0E0E0;border-left: 1px solid #E0E0E0;"><strong>'.$totalCAT5.'</strong></td>
								<td width="10%" style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;border-top: 1px solid #E0E0E0;border-left: 1px solid #E0E0E0;"><strong>'.$totalNEASinCAT.'</strong></td>
								<td width="10%" style="border-bottom: 1px solid #E0E0E0; border-right: 1px solid #E0E0E0;border-top: 1px solid #E0E0E0;border-left: 1px solid #E0E0E0;"><strong>'.$totalNEAConCAT.'</strong></td>
							</tr>
						</table>
				</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="reporte">


				</table></td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
		</table>';

	$pdf->writeHTML($html, true, false, true, false, '');


	$nombre_archivo = "reportescategorizacionUrgencia".date('Y-m-dms').".pdf";
	$pdf->Output(__DIR__ . '/' . $nombre_archivo, 'FI');
	$url = "/RCEDAU/views/modules/reportes/salidas/".$nombre_archivo;


	// $pdf->Output('reportescategorizacionUrgencia.pdf','FI');
	// $url = RAIZ."/views/reportes/salidas/reportescategorizacionUrgencia.pdf";
?>
</iframe>

<div class="embed-responsive embed-responsive-16by9">
	<iframe id="iframeBincard1" class="embed-responsive-item" src="<?=$url?>" height="700" width="700" allowfullscreen></iframe>
</div>

<script>
$('#iframeBincard').ready(function(){
	ajaxRequest(raiz+'/controllers/server/reportes/main_controller.php','nombreArchivo=<?=$url?>&accion=eliminarPDF', 'POST', 'JSON', 1, '', true);
});
</script>
