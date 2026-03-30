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
		$pdf->SetTitle('PDF LIBRO DE ACCIDENTES DEL TRABAJO');
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
		$fechaHoy                 = $objUtil->getFechaPalabra(date('Y-m-d'));
		$datos                    = $reporte->atencionesSinDiagnostico($objCon,$parametros);

		if ($parametros['tipoAtencion'] == 0) {
			$atencion = '(TODO TIPO DE ATENCIÓN)';
		}else if ($parametros['tipoAtencion'] == 1) {
			$atencion = '(ADULTO)';
		}
		else if ($parametros['tipoAtencion'] == 2) {
			$atencion = '(PEDIÁTRICO)';
		}else{
			$atencion = '(GINECOLÓGICO)';
		}

		// highlight_string(print_r($parametros),true);

		$html = '
		<table width="751" border="0" align="center" cellpadding="0" cellspacing="0" class="reporte">
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
					<td align="left">GOBIERNO DE CHILE</td>
				</tr>

				<tr>
					<td align="left">MINISTERIO DE SALUD</td>
				</tr>

				<tr>
					<td align="left">HOSPITAL DR. JUAN NOÉ CREVANI</td>
				</tr>

				<tr>
					<td align="left">RUT: 61.606.000-7</td>
				</tr>

				<tr>
					<td align="left">18 DE SEPTIEMBRE N°1000</td>
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

	    <tr>
	    	<td colspan="3" align="center">&nbsp;</td>
	    </tr>

	    <tr>
	    	<td colspan="3" align="center" >ATENCIONES Y HOSPITALIZACIONES DE URGENCIA <br />
			SIN DIAGNOSTICO '.$atencion.'<br />PERIODO: '.$_POST['fechaInicio'].' AL '.$_POST['fechaFin'].'</td>
	  	</tr>

    	<tr>
    		<td colspan="3" align="center">&nbsp;</td>
  		</tr>
  
	  	<tr>
	  		<td align="center" colspan="3">';
		  		$html_2 .='<table border="1" width="100%">
			        <tr  style="font:bold; background-color:#CCC">
			        	<td align="center">DAU</td>
			        	<td align="center">RUT PACIENTE</td>
			        	<td align="center">FECHA</td>
			        </tr>';
					for ($i=0; $i<count($datos); $i++) {
						if($datos[$i]['extranjero']!="S"){
							$runPac = $datos[$i]['rut'].$objUtil->generaDigito($datos[$i]['rut']);
							$runPac = $objUtil->rut($runPac);
						}else{
							$runPac = $datos[$i]['rut_extranjero'];
						}
						$html_2 .='
				        <tr>
				            <td align="center">'.$datos[$i]['dau_id'].'</td>
				            <td align="center">'.$runPac.'</td>
				            <td align="center">'.date("d-m-Y H:i",strtotime($datos[$i]['dau_admision_fecha'])).'</td>
				        </tr>';
					}
					$html_2 .='

		        </table>
	  		</td>
	  	</tr>
  
 <tr>
    <td colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
  </tr> 
</table>';

	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->writeHTML($html_2, true, false, true, false, '');


	$nombre_archivo = "reportesLibroAccidentes".date('Y-m-dms').".pdf";
	$pdf->Output(__DIR__ . '/' . $nombre_archivo, 'FI');
	$url = "/RCEDAU/views/modules/reportes/salidas/".$nombre_archivo;

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