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
		$pdf->SetTitle('PDF ENTREGA TURNO CATEG ADULTO');
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
		$fechaHoy                 = $objUtil->getFechaPalabra(date('Y-m-d'));

		if ($parametros['frm_turno'] == 1) { //TURNO DIA
			$horas_turno = ', DESDE LAS 08:00 HASTA LAS 19:59 HORAS';
			$parametros['fechaInicio']=$objUtil->cambiarFormatoFecha($parametros['fechaInicio']);
			$parametros['fechaFin']=$objUtil->cambiarFormatoFecha($parametros['fechaFin']);			
			
			$parametros['horaInicio']=date('Y-m-d',strtotime($parametros['fechaInicio'])).' 08:00:00';
			$parametros['horaFin']=date('Y-m-d',strtotime($parametros['fechaFin'])).' 19:59:00';			
			$datos=$reporte->entregaTurnoCategorizacionAdulto($objCon,$parametros);
			
		}else if ($parametros['frm_turno'] == 2) { //TURNO NOCHE
			$horas_turno = ', DESDE LAS 20:00 HASTA LAS 07:59 HORAS';	
			$parametros['fechaInicio']=$objUtil->cambiarFormatoFecha($parametros['fechaInicio']);
			$parametros['fechaFin']=$objUtil->cambiarFormatoFecha($parametros['fechaFin']);		

			$parametros['horaInicio']=date('Y-m-d',strtotime($parametros['fechaInicio'])).' 20:00:00';
			$parametros['horaFin']=date('Y-m-d',strtotime($parametros['fechaFin'])).' 07:59:00';			
			$datos=$reporte->entregaTurnoCategorizacionAdulto($objCon,$parametros);
		}			
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


		<tr align="center">
			<td width="65"><!-- <img src="../../estandar/iconos/logos juntos.jpg" width="139" height="67" /> --></td>
			<td width="450" align="center" class="titulos"><strong class="titulotabla">REPORTE DE ENTREGA TURNO - CATEGORIZACIÓN</strong></td>
			<td width="85" align="center" class="titulos"><span class="derechosreservados"></span></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td colspan="2" align="center">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" class="foliochico"><table width="100%" border="0">
				<tr>
				<td width="100%"><strong>TURNO</strong><strong> '.$fechaInicio.' '.$horas_turno.' </strong></td>
				</tr>
			</table></td>
		</tr>
		<tr>
			<td colspan="3" class="foliochico">&nbsp;</td>
		</tr>
	

			<tr>
				<td colspan="3" class="foliochico"><strong>DAU de Pacientes mayores a 12 Años sin Categorización:</strong></td>
			</tr>
			<tr>
				<td colspan="3" class="foliochico">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3" align="center">
					<table width="600" border="1" cellpadding="2" cellspacing="0" class="reporte">
						<tr>
							<td width="20" bgcolor="#DFE8F7">&nbsp;</td>
							<td width="55" bgcolor="#DFE8F7"><strong>DAU</strong></td>
							<td width="120" bgcolor="#DFE8F7"><strong>FECHA, HORA</strong></td>
							<td width="47" bgcolor="#DFE8F7"><strong>EDAD</strong></td>
							<td width="230" bgcolor="#DFE8F7"><strong>PACIENTE</strong></td>
							<td width="128" bgcolor="#DFE8F7"><strong>ENFERMERO(A)</strong></td>
						</tr>
				
				';			
				

				for ($i=0; $i < count($datos) ; $i++) { 				
					$correlativo=$i+1;
					
					$transexual_bd   		  = $datos[$i]["transexual"];
					$nombreSocial_bd 		  = $datos[$i]["nombreSocial"];
					$nombrePaciente 		  = $datos[$i]['nombres'].' '.$datos[$i]['apellidopat'].' '.$datos[$i]['apellidomat'];
					$infoNombre    		  	  = $objUtil->infoNombreDoc($transexual_bd,$nombreSocial_bd,$nombrePaciente);
					
					$html.='
							<tr align="left" valign="top">
								<td width="20"> '.$correlativo.' </td>
								<td width="55">'.$datos[$i]['dau_id'].' </td>
								<td width="120">'.date("d-m-Y H:i",strtotime($datos[$i]['fechaHora'])).' </td>
								<td width="47"> '.$datos[$i]['dau_paciente_edad'].'</td>
								<td width="230">'.$infoNombre.' </td>
								<td width="128">  </td>
							</tr>';
				}

				if(count($datos)==0){
					$registrosEncontrados="No se encuentran registros";
				}				
					$html.='		
						</table></td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="3" align="center">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="3"><strong>Enfermera(o) de Turno.</strong></td>
					</tr>

					
					
					
					<tr>
						<td>&nbsp;</td>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td>Nombre </td>
						<td colspan="2">: ___________________________________&nbsp;&nbsp;&nbsp; Firma : _________________</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td colspan="2">&nbsp;</td>
					</tr>
					
					<tr>

						<td colspan="3" align="center">
							</td>
						</tr>
					</table>

<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center">'.$registrosEncontrados.'</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"></td>
  </tr>
</table>';

	$pdf->writeHTML($html, true, false, true, false, '');


	$nombre_archivo = "entregaTurnoCategAdulto.pdf";
	$pdf->Output(__DIR__ . '/' . $nombre_archivo, 'FI');
	$url = "/RCEDAU/views/modules/reportes/salidas/".$nombre_archivo;

?>
</iframe>

<div class="embed-responsive embed-responsive-16by9">
	<iframe id="iframReporte" class="embed-responsive-item" src="<?=$url?>" height="700" width="700" allowfullscreen></iframe>
</div>

<script>
$('#iframReporte').ready(function(){
	ajaxRequest(raiz+'/controllers/server/reportes/main_controller.php','nombreArchivo=<?=$url?>&accion=eliminarPDF', 'POST', 'JSON', 1, '', true);
});
</script>