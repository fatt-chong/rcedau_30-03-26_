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
		$pdf->SetTitle('PDF ENTREGA Y CIERRE DE TURNO DAU');
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
		$datos                    = $reporte->entregaCierreDAU($objCon,$parametros['frm_inicio'],$parametros['frm_fin']);
		$datosLista               = $reporte->listaEntregaCierreDAU($objCon,$parametros['frm_inicio'],$parametros['frm_fin']);

		/*$datosPRINT = $datos;
		if($datos['P'][3]['TOTAL']==0){$datos['P'][3]['TOTAL']=0;}else{$datos['P'][3]['TOTAL'];}
		if($datos['G'][3]['TOTAL']==0){$datos['G'][3]['TOTAL']=0;}else{$datos['G'][3]['TOTAL'];}
		if($datos['A'][0]['TOTAL']==0){$datos['A'][0]['TOTAL']=0;}else{$datos['A'][0]['TOTAL'];}
		if($datos['P'][0]['TOTAL']==0){$datos['P'][0]['TOTAL']=0;}else{$datos['P'][0]['TOTAL'];}
		if($datos['G'][0]['TOTAL']==0){$datos['G'][0]['TOTAL']=0;}else{$datos['G'][0]['TOTAL'];}
		if($datos['A'][1]['TOTAL']==0){$datos['A'][1]['TOTAL']=0;}else{$datos['A'][1]['TOTAL'];}
		if($datos['P'][1]['TOTAL']==0){$datos['P'][1]['TOTAL']=0;}else{$datos['P'][1]['TOTAL'];}
		if($datos['G'][1]['TOTAL']==0){$datos['G'][1]['TOTAL']=0;}else{$datos['G'][1]['TOTAL'];}
		if($datos['A'][2]['TOTAL']==0){$datos['A'][2]['TOTAL']=0;}else{$datos['A'][2]['TOTAL'];}
		if($datos['P'][2]['TOTAL']==0){$datos['P'][2]['TOTAL']=0;}else{$datos['P'][2]['TOTAL'];}
		if($datos['G'][2]['TOTAL']==0){$datos['G'][2]['TOTAL']=0;}else{$datos['G'][2]['TOTAL'];}*/
		//highlight_string(print_r($datos,true));
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
		<table width="635" border="0" align="center" cellpadding="0" cellspacing="0">
  			<tr>
    			<td colspan="3" align="center" >REPORTE DE ENTREGA Y CIERRE DE TURNO</td>
  			</tr>

  			<tr>
  				<td colspan="3" align="center" style="text-decoration:none;color:#666">&nbsp;</td>
  			</tr>

  			<tr>
    			<td colspan="3" class="foliochico">
    				<table width="100%" border="0">
	   					<tr>
	        				<td colspan="3">DESDE: '.$parametros['fechaInicio'].' HASTA: '.$parametros['fechaFin'].'</td>
						</tr>

	  					<tr>
	  						<td colspan="3" align="center">&nbsp;</td>
	  					</tr>

	      				<tr>
	        				<td colspan="3">
	        					<table width="600" border="1" align="center" cellpadding="0" cellspacing="0"  id="demo_table">
	          						<tr align="right">
	            						<td align="center" width="110" bgcolor="#4682b4" style="color:#FFF;"><strong>DAU ESTADO</strong></td>
	            						<td width="119" bgcolor="#4682b4" style="color:#FFF;"><strong>TOTAL</strong></td>
	            						<td width="119" bgcolor="#4682b4" style="color:#FFF;"><strong>ADULTO</strong></td>
	            						<td width="119" bgcolor="#4682b4" style="color:#FFF;"><strong>PEDIATRICO</strong></td>
	            						<td width="119" bgcolor="#4682b4" style="color:#FFF;"><strong>GINECOLOGICO</strong></td>
	            					</tr>

	          						<tr  align="right">
	            						<td align="left">EN PROCESO</td>
	            						<td>'.($datos['A'][0]['P']+$datos['P'][0]['P']+$datos['G'][0]['P']).'</td>
	            						<td>'.$datos['A'][0]['P'].'</td>
	            						<td>'.$datos['P'][0]['P'].'</td>
	            						<td>'.$datos['G'][0]['P'].'</td>
	            					</tr>
	            					<tr  align="right">
	            						<td align="left">DERIVADO</td>
	            						<td>'.($datos['A'][0]['D']+$datos['P'][0]['D']+$datos['G'][0]['D']).'</td>
	            						<td>'.$datos['A'][0]['D'].'</td>
	            						<td>'.$datos['P'][0]['D'].'</td>
	            						<td>'.$datos['G'][0]['D'].'</td>
	            					</tr>
	            					<tr  align="right">
	            						<td align="left">N.E.A</td>
	            						<td>'.($datos['A'][0]['N']+$datos['P'][0]['N']+$datos['G'][0]['N']).'</td>
	            						<td>'.$datos['A'][0]['N'].'</td>
	            						<td>'.$datos['P'][0]['N'].'</td>
	            						<td>'.$datos['G'][0]['N'].'</td>
	            					</tr>
	            					<tr  align="right">
	            						<td align="left">NULO</td>
	            						<td>'.($datos['A'][0]['NU']+$datos['P'][0]['NU']+$datos['G'][0]['NU']).'</td>
	            						<td>'.$datos['A'][0]['NU'].'</td>
	            						<td>'.$datos['P'][0]['NU'].'</td>
	            						<td>'.$datos['G'][0]['NU'].'</td>
	            					</tr>
	        					</table>
	        				</td>
	        			</tr>
    				</table>
    			</td>
  			</tr>

  			<tr>
  				<td colspan="3" class="foliochico">&nbsp;</td>
  			</tr>

  			<tr>
    			<td colspan="3" >ATENCIONES DE URGENCIA EN PROCESO - PENDIENTES<br />* No considera pacientes en lista de espera, ni en box de urgencia.</td>
  			</tr>

  			<tr>
  				<td colspan="3" >&nbsp;</td>
  			</tr>

  			&nbsp;&nbsp;<tr>
    			<td colspan="3" align="center">
      				<table width="900" border="1" cellpadding="2" cellspacing="0"  id="demo_tableB">
      					<tr>
        					<td width="60" bgcolor="#4682b4" style="color:#FFF;"><strong>DAU</strong></td>
        					<td width="120" bgcolor="#4682b4" style="color:#FFF;"><strong>FECHA</strong></td>
        					<td width="120" bgcolor="#4682b4" style="color:#FFF;"><strong>TIPO ATENCI&Oacute;N</strong></td>
        					<td bgcolor="#4682b4" style="color:#FFF;"><strong>PACIENTE</strong></td>
        				</tr>';
        				for($i = 0; $i<count($datosLista);$i++){
        					$html .='<tr align="left" valign="top">
	        					<td>'.$datosLista[$i]['dau_id'].'</td>
	        					<td>'.date("d-m-Y H:i",strtotime($datosLista[$i]['dau_admision_fecha'])).'</td>
	        					<td>'.$datosLista[$i]['ate_descripcion'].'</td>
	        					<td>'.$datosLista[$i]['nombre'].'</td>
	        				</tr>';
        				}
    				$html.='</table>
    			</td>
  			</tr>

  			<tr>
  				<td colspan="3">&nbsp;</td>
  			</tr>

  			<tr>
  				<td colspan="3">OBSERVACIONES</td>
  			</tr>

  			<tr>
  				<td colspan="3" >&nbsp;</td>
  			</tr>

		  	<tr>
		    	<td colspan="3" align="center"><textarea style="width:100%; height:70px" name="" rows="4" cols="50"></textarea></td>
		  	</tr>

		  	<tr>
		    	<td colspan="3" align="center">&nbsp;</td>
		  	</tr>

		  	<tr>
		    	<td colspan="3">RECAUDADOR DE TURNO:</td>
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
		    	<td colspan="3" class="foliochico">&nbsp;</td>
		  	</tr>

		  	<tr>
		    	<td colspan="3" class="foliochico">&nbsp;</td>
		  	</tr>
		</table>

		<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
  			<tr>
    			<td align="center">No existen registros asociados para su búsqueda.</td>
  			</tr>

  			<tr>
    			<td align="center">&nbsp;</td>
  			</tr>

		</table>';

	$pdf->writeHTML($html, true, false, true, false, '');


	$nombre_archivo = "reportesEntregaCierreTurno.pdf";
	$pdf->Output(__DIR__ . '/' . $nombre_archivo, 'FI');
	$url = "/RCEDAU/views/modules/reportes/salidas/".$nombre_archivo;
	
?>
</iframe>

<div class="embed-responsive embed-responsive-16by9">
	<iframe id="iframeBincard" class="embed-responsive-item" src="<?=$url?>" height="700" width="700" allowfullscreen></iframe>
</div>
<?
//highlight_string(print_r($datosLista,true));
?>
<script>
$('#iframeBincard').ready(function(){
	ajaxRequest(raiz+'/controllers/server/reportes/main_controller.php','nombreArchivo=<?=$url?>&accion=eliminarPDF', 'POST', 'JSON', 1, '', true);
});
</script>