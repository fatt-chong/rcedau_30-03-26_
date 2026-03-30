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
		$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		//SET DOCUMENT INFORMATION
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('DAU');
		$pdf->SetTitle('PDF REGISTRO HOSPITALIZACION ');
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
		$parametros               = $objUtil->getFormulario($_POST);
		$parametros['frm_inicio'] = $objUtil->fechaInvertida($parametros['fechaInicio']);
		$parametros['frm_fin']    = $objUtil->fechaInvertida($parametros['fechaFin']);
		$fechaHoy = $objUtil->getFechaPalabra(date('Y-m-d'));
		$datos = $reporte->registroAtencionDiaria($objCon,$parametros);

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
					<table td width="50%" align="left" >
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
					<strong style="font-size:10; color: ">REGISTRO ATENCIÓN DIARIA, FECHA '.$_POST['fechaInicio'].'</strong>
				</td>
		    </tr>
		</table>
		<br>

		<table border="1">
			<thead>
				<tr class="encabezado">
					<th width="8%"  bgcolor="#CCCCCC" align="center">DAU</th>
					<th width="6%"  bgcolor="#CCCCCC" align="center">CAT</th>
					<th width="28%" bgcolor="#CCCCCC" align="center">Nombre</th>
					<th width="5%"  bgcolor="#CCCCCC" align="center">Edad</th>
					<th width="12%" bgcolor="#CCCCCC" align="center">Servicio</th>
					<th width="9%"  bgcolor="#CCCCCC" align="center">Ctacte</th>
					<th width="16%" bgcolor="#CCCCCC" align="center">Fecha/Hora Indicación</th>
					<th width="16%" bgcolor="#CCCCCC" align="center">Fecha/Hora Aplica</th>
				</tr>
			</thead>
			<tbody id="">
			';
			for ($i=0; $i<count($datos); $i++) {
				 $datos[$i]['fechaHora']= date("d-m-Y H:i:s",strtotime($datos[$i]['fechaHora']));

				 $transexual_bd   		  = $datos[$i]["transexual"];
				 $nombreSocial_bd 		  = $datos[$i]["nombreSocial"];
				 $nombrePaciente	      = $datos[$i]['nombres'].' '.$datos[$i]['apellidopat'].' '.$datos[$i]['apellidomat'];
				 $infoNombre    		  = $objUtil->infoNombreDoc($transexual_bd,$nombreSocial_bd,$nombrePaciente);


			$html .='
				<tr align="left" valign="top">
					<td width="8%"  align="center"> '.$datos[$i]['dau_id'].'</td>
					<td width="6%"  align="center"> '.$datos[$i]['dau_categorizacion'].'</td>
					<td width="28%">&nbsp;&nbsp;    '.$infoNombre.'</td>
					<td width="5%"  align="center"> '.$datos[$i]['dau_paciente_edad'].'</td>
					<td width="12%" align="center"> '.$datos[$i]['servicio'].'</td>
					<td width="9%" align="center"> '.$datos[$i]['idctacte'].'</td>
					<td width="16%" align="center"> '.$datos[$i]['fechaHoraIndicacionEgreso'].'</td>
					<td width="16%" align="center"> '.$datos[$i]['fechaHoraAplicacionEgreso'].'</td>
				</tr>';
			}
			$html .='
					<tr align="left" valign="top">
		        		<td colspan="8" align="right" bgcolor="#CCCCCC"><strong>TOTAL : '.$i.'&nbsp;&nbsp;&nbsp;&nbsp;</strong></td>
		        	</tr>
			</tbody>
		</table>';

	$pdf->writeHTML($html, true, false, true, false, '');
	$nombre_archivo = "reporteRegistroAtencionDiaria.pdf";

	$pdf->Output(__DIR__ . '/' . $nombre_archivo, 'FI');
	$url = "/RCEDAU/views/modules/reportes/salidas/reporteRegistroAtencionDiaria.pdf";

?>
</iframe>

<div class="embed-responsive embed-responsive-16by9">
	<iframe id="iframeBincard" class="embed-responsive-item" src="<?=$url?>" height="700" width="700" allowfullscreen></iframe>
</div>

<script>
$('#iframeBincard').ready(function(){

	ajaxRequest(raiz+'/controllers/server/reportes/main_controller.php','nombreArchivo=<?=$url?>&accion=eliminarPDF', 'POST', 'JSON', 1, '', true);
});
</script>
