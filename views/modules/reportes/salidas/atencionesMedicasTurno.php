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
		$pdf->SetTitle('PDF ATENCIONES MEDICAS POR TURNO');
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
		$parametros['frm_inicio'] = $objUtil->fechaInvertida($parametros['fechaInicio'])." 08:00:00";
		$parametros['frm_fin']    = $objUtil->fechaInvertida($parametros['fechaFin']);
		$fechaHoy                 = $objUtil->getFechaPalabra(date('Y-m-d'));
		$datos                    = $reporte->atencionMedicasTurno($objCon,$parametros);				
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
		<table width="auto" border="0" align="center" cellpadding="0" cellspacing="0">		
		    <tr>
		    	<td colspan="3" align="center">&nbsp;</td>
		    </tr>

		    <tr>
		    	<td colspan="3" align="center" class="foliochico"><strong class="titulotabla">RESUMEN  DE ATENCIONES MEDICAS POR FECHA (TURNO)<br />
		    PERIODO: '.$_POST['fechaInicio'].' 08:00:00'.' AL '.$_POST['fechaFin'].' 08:00:00'.' </strong></td>
		  	</tr>
	  		
	  		<tr>
	    		<td colspan="3" class="foliochico">&nbsp;</td>
	  		</tr>
	  		<br><br>
	  		<tr>
	    		<td colspan="4" align="center">
	    		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	    		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	    		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	    		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	    		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	        		<table align="center" width="330" border="0" id="demo_table">
	              		<tr bgcolor="#4682b4" style="color:#FFF;">
	                		<td width="215">PROFESIONAL</td>
	                		<td width="105">ATENCIONES</td>
	              		</tr>';
	              			$total = 0;
							for ($i=0; $i<count($datos); $i++) {
								if($datos[$i]['PROdescripcion']){ 
									$PROFESIONAL = $datos[$i]['PROdescripcion']; 
								}else{
									$PROFESIONAL = 'DAU EN PROCESO (SIN CIERRE ADM)';
								}

								$total = $total + $datos[$i]['TOTAL'];
						$html .='	            
	              		<tr>
	                		<td>'.$PROFESIONAL.'</td>
	                		<td align="right">'.$datos[$i]['TOTAL'].'</td>
	              		</tr>';
							}
						$html .='
	            
	              		<tr bgcolor="#4682b4" style="color:#FFF;">
	               			<td  bgcolor="#4682b4" style="color:#FFF;">TOTAL ATENCIONES</td>
	                		<td align="right"  bgcolor="#4682b4" style="color:#FFF;">'.$total.'</td>
	              		</tr>
	        		</table>
				</td>
	  		</tr>

	  		<tr>
	    		<td colspan="3">
		    		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reporte">
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
	    		</td>
	  		</tr>

		  	<tr>
		    	<td colspan="3">&nbsp;</td>
		  	</tr> 
		</table>';

	$pdf->writeHTML($html, true, false, true, false, '');


	$nombre_archivo = "reportesAtencionesMedicasPorTurno.pdf";
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