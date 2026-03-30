<iframe height="100%" width="100%" hidden>
	<?php		
		error_reporting(0);
		ini_set('post_max_size', '512M'); 
		ini_set('memory_limit', '1G'); 
		set_time_limit(0);
		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Cache-Control: no-store");
		require_once('../../../../estandar/tcpdf/tcpdf.php');
		require_once('../../../../estandar/tcpdf/config/lang/spa.php');

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

		require("../../../config/config.php");
		require_once('../../../class/Connection.class.php');     $objCon     = new Connection; $objCon->db_connect();
		require_once('../../../class/Util.class.php');           $objUtil    = new Util;
		require_once('../../../class/Reportes.class.php');       $reporte    = new Reportes;
		$parametros                             = $objUtil->getFormulario($_POST);		
		$fechaHoy                               = $objUtil->getFechaPalabra(date('Y-m-d'));
		$parametros['frm_fecha_admision_desde'] = $parametros['fechaInicio'];
		$parametros['frm_fecha_admision_hasta'] = $parametros['fechaFin'];
		$datos                                  = $reporte->listarPacientePediatrico($objCon,$parametros);
			
		// highlight_string(print_r($datos),true);

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
		<br>
			<table width="600" border="0">              
            	<tr>
    				<td colspan="3" align="center">RESUMEN  DE ATENCIONES MEDICAS POR FECHA (ADMISIÓN)<br />TIPO ATENCIÓN: PEDIATRICA<br/>MEDICO TRATANTE: '.$parametros['medicoTratante'].'<br />TOTAL DATOS DE ATENCIÓN DE URGENCIA : '.count($datos).'</td>
  				</tr>

                <tr>
    				<td colspan="3" align="center">&nbsp;</td>
  				</tr>
            	<tr>
            		<td>
            		<br>
            			<table width="600" border="1" id="demo_table4">
              				<tr bgcolor="#4682b4" style="color:#FFF;" align="center">
				                <td width="57">DAU</td>
				                <td width="162">FECHA INDICACIÓN</td>
				                <td width="367">PACIENTE</td>
              				</tr>
              				<tbody id="">';

             				for ($i=0; $i<count($datos); $i++) {
								$html .='
	              				<tr>              				
	              					<td align="center">&nbsp;&nbsp;'.$datos[$i]['dau_id'].'</td>
	              					<td align="center">&nbsp;&nbsp;'.$datos[$i]['dau_indicacion_egreso_fecha'].'</td>
	              					<td>&nbsp;&nbsp;  '.$datos[$i]['nombres'].' &nbsp;'.$datos[$i]['apellidopat']. ' &nbsp;'.$datos[$i]['apellidomat'].'</td>
	              				</tr>';
	              				}
								$html .='
							</tbody>           
           				</table>
           			</td>
           		</tr>
              
         		<tr>
         			<td colspan="3">&nbsp;</td>
         		</tr>

            	<tr>
            		<td colspan="3">&nbsp;</td>
            	</tr>          	
            	
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
	$pdf->Output('reportesGeneraPDFpediatrico.pdf','FI');
	$url = PATH."/views/reportes/salidas/reportesGeneraPDFpediatrico.pdf";
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