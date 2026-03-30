<iframe height="100%" width="100%" hidden>
	<?php
		
		error_reporting(0);
		set_time_limit(60);
		error_reporting(1);
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
		$pdf->SetTitle('PDF ESTADISTICA DE ATENCIONES POR HORA DE URGENCIA');
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
		//$pdf->AddPage('L', 'A4');
		require("../../../../config/config.php");
		require_once('../../../../class/Connection.class.php');     $objCon     = new Connection; $objCon->db_connect();
		require_once('../../../../class/Util.class.php');           $objUtil    = new Util;
		require_once('../../../../class/Reportes.class.php');       $reporte    = new Reportes;
		$parametros               = $objUtil->getFormulario($_POST);
		$parametros['frm_inicio'] = $objUtil->fechaInvertida($parametros['fechaInicio']);
		$parametros['frm_fin']    = $objUtil->fechaInvertida($parametros['fechaFin']);
		$fechaHoy                 = $objUtil->getFechaPalabra(date('Y-m-d'));		
		//highlight_string(print_r($parametros),true);

		$fechainicio = $parametros['frm_inicio'];
		$fechafin    = $parametros['frm_fin'];

		$dau = array();
		function atenciones($objCon, $fechainicio, $fechafin, $tipoatencion, $horainicio, $horafin){
			$sql=sprintf("SELECT Count(dau.dau.dau_id) AS cantidad
						  FROM dau.dau
                          INNER JOIN paciente.paciente ON paciente.paciente.id = dau.dau.dau_id
                          WHERE (EXTRACT(YEAR FROM curdate()) - EXTRACT(YEAR FROM paciente.paciente.fechanac)) BETWEEN  0 AND 99999
								AND date(dau.dau.dau_admision_fecha)  BETWEEN '$fechainicio' AND '$fechafin'
								AND time(dau.dau.dau_admision_fecha)  BETWEEN '$horainicio' AND '$tipoatencion'
								AND dau.dau.est_id in (1,2,3,4)
								AND dau.dau.dau_motivo_consulta = '$tipoatencion'");						
			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte prestacionEdadTotalIra<br>");
			return $datos;
		}

		$dau[0]['hora']         = '00-01';
		$dau[0]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '00:00','01:00');
		$dau[0]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '00:00','01:00');
		$dau[0]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '00:00','01:00');

		$dau[1]['hora']         = '01-02';
		$dau[1]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '01:00','02:00');
		$dau[1]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '01:00','02:00');
		$dau[1]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '01:00','02:00');

		$dau[2]['hora']         = '02-03';
		$dau[2]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '02:00','03:00');
		$dau[2]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '02:00','03:00');
		$dau[2]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '02:00','03:00');

		$dau[3]['hora']         = '03-04';
		$dau[3]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '03:00','04:00');
		$dau[3]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '03:00','04:00');
		$dau[3]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '03:00','04:00');

		$dau[4]['hora']         = '04-05';
		$dau[4]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '04:00','05:00');
		$dau[4]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '04:00','05:00');
		$dau[4]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '04:00','05:00');

		$dau[5]['hora']         = '05-06';
		$dau[5]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '05:00','06:00');
		$dau[5]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '05:00','06:00');
		$dau[5]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '05:00','06:00');

		$dau[6]['hora']         = '06-07';
		$dau[6]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '06:00','07:00');
		$dau[6]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '06:00','07:00');
		$dau[6]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '07:00','07:00');

		$dau[7]['hora']         = '07-08';
		$dau[7]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '07:00','08:00');
		$dau[7]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '07:00','08:00');
		$dau[7]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '07:00','08:00');

		$dau[8]['hora']         = '08-09';
		$dau[8]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '08:00','09:00');
		$dau[8]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '08:00','09:00');
		$dau[8]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '08:00','09:00');

		$dau[9]['hora']         = '09-10';
		$dau[9]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '09:00','10:00');
		$dau[9]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '09:00','10:00');
		$dau[9]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '09:00','10:00');

		$dau[10]['hora']         = '10-11';
		$dau[10]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '10:00','11:00');
		$dau[10]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '10:00','11:00');
		$dau[10]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '10:00','11:00');

		$dau[11]['hora']         = '11-12';
		$dau[11]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '11:00','12:00');
		$dau[11]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '11:00','12:00');
		$dau[11]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '11:00','12:00');

		$dau[12]['hora']         = '12-13';
		$dau[12]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '12:00','13:00');
		$dau[12]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '12:00','13:00');
		$dau[12]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '12:00','13:00');

		$dau[13]['hora']         = '13-14';
		$dau[13]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '13:00','14:00');
		$dau[13]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '13:00','14:00');
		$dau[13]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '13:00','14:00');

		$dau[14]['hora']         = '14-15';
		$dau[14]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '14:00','15:00');
		$dau[14]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '14:00','15:00');
		$dau[14]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '14:00','15:00');

		$dau[15]['hora']         = '15-16';
		$dau[15]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '15:00','16:00');
		$dau[15]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '15:00','16:00');
		$dau[15]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '15:00','16:00');

		$dau[16]['hora']         = '16-17';
		$dau[16]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '16:00','17:00');
		$dau[16]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '16:00','17:00');
		$dau[16]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '16:00','17:00');

		$dau[17]['hora']         = '17-18';
		$dau[17]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '17:00','18:00');
		$dau[17]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '17:00','18:00');
		$dau[17]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '17:00','18:00');

		$dau[18]['hora']         = '18-19';
		$dau[18]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '18:00','19:00');
		$dau[18]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '18:00','19:00');
		$dau[18]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '18:00','19:00');

		$dau[19]['hora']         = '19-20';
		$dau[19]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '19:00','20:00');
		$dau[19]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '19:00','20:00');
		$dau[19]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '19:00','20:00');

		$dau[20]['hora']         = '20-21';
		$dau[20]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '20:00','21:00');
		$dau[20]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '20:00','21:00');
		$dau[20]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '20:00','21:00');

		$dau[21]['hora']         = '21-22';
		$dau[21]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '21:00','22:00');
		$dau[21]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '21:00','22:00');
		$dau[21]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '21:00','22:00');

		$dau[22]['hora']         = '22-23';
		$dau[22]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '22:00','23:00');
		$dau[22]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '22:00','23:00');
		$dau[22]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '22:00','23:00');

		$dau[23]['hora']         = '23-24';
		$dau[23]['adulto']       = atenciones($objCon,$fechainicio, $fechafin, 1, '23:00','24:00');
		$dau[23]['pediatrico']   = atenciones($objCon,$fechainicio, $fechafin, 2, '23:00','24:00');
		$dau[23]['ginecologico'] = atenciones($objCon,$fechainicio, $fechafin, 3, '23:00','24:00');


		$html = '
		<table width="1000" border="0" align="center" cellpadding="0" cellspacing="0">
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

		<tr>
			<td colspan="3" align="center" class="foliochico"><strong class="titulotabla">ESTADISTICA DE ATENCIONES POR HORA DE URGENCIA<br />
			PERIODO: '.$_POST['fechaInicio'].' AL '.$_POST['fechaFin'].'</strong></td>
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
					</tr>';
					for ($i=0; $i<count($dau); $i++) {
					$at_adulto       = $dau[$i]['adulto'][0]['cantidad'];
					$acum_adulto     = $acum_adulto +  $at_adulto;

					$at_pediatrico   = $dau[$i]['pediatrico'][0]['cantidad'];
					$acum_pediatrico = $acum_pediatrico +  $at_pediatrico;


					$at_ginecologico   = $dau[$i]['ginecologico'][0]['cantidad'];
					$acum_ginecologico = $acum_ginecologico +  $at_ginecologico;

					$total  = ($at_adulto +  $at_pediatrico + $at_ginecologico);
					$total2 = $acum_adulto + $acum_pediatrico + $acum_ginecologico;

					$html .='	
					<tr align="left" valign="top">
						<td align="center">'.$dau[$i]['hora'].'</td>
						<td align="right" valign="bottom">'.$dau[$i]['adulto'][0]['cantidad'].'</td>
						<td align="right" valign="bottom">'.$dau[$i]['pediatrico'][0]['cantidad'].'</td>
						<td align="right" valign="bottom">'.$dau[$i]['ginecologico'][0]['cantidad'].'</td>
						<td align="right" valign="bottom">'.$total.'</td>
					</tr>';	
					}

					// CODIGO PARA GENERAR GRAFICO
					require_once('../../../../../estandar/assets/jpgraph-4.4.2/src/jpgraph.php');
					require_once('../../../../../estandar/assets/jpgraph-4.4.2/src/jpgraph_bar.php');
					// require_once('../../../../assets/libs/jpgraph-4.4.2/src/jpgraph.php');
					// require_once('../../../../assets/libs/jpgraph-4.4.2/src/jpgraph_line.php');
					// require_once('../../../../assets/libs/jpgraph-4.4.2/src/jpgraph_bar.php');
					// ------------------------------------------------------------------------------------------------------------------
					// $graf_adulto    = new VerticalBarChart(800, 300);
					// $set_pediatrico = new XYDataSet();

			  //   	for($i=0; $i<=count($dau); $i++){
					// 	$set_pediatrico->addPoint(new Point($dau[$i]['hora'], $dau[$i]['adulto'][0]['cantidad']));
					// }
					// $graf_adulto->setDataSet($set_pediatrico);
					// $graf_adulto->setTitle("Atenciones Adultas");
					// $graf_adulto->render("../graficos/atxhora_adulta.png");
					// // ------------------------------------------------------------------------------------------------------------------
					// $graf_pediatrico = new VerticalBarChart(800, 300);
					// $set_pediatrico = new XYDataSet();
					
			  //   	for($i=0; $i<=count($dau); $i++){
					// 	$set_pediatrico->addPoint(new Point($dau[$i]['hora'], $dau[$i]['pediatrico'][0]['cantidad']));
					// }
					// $graf_pediatrico->setDataSet($set_pediatrico);
					// $graf_pediatrico->setTitle("Atenciones Pediatricas");
					// $graf_pediatrico->render("../graficos/atxhora_pediatrica.png");
					// // ------------------------------------------------------------------------------------------------------------------	
					// $graf_ginecologica = new VerticalBarChart(800, 300);
					// $set_ginecologica = new XYDataSet();
		
			  //   	for($i=0; $i<=count($dau); $i++){
					// 	$set_ginecologica->addPoint(new Point($dau[$i]['hora'], $dau[$i]['ginecologico'][0]['cantidad']));
					// }
					// $graf_ginecologica->setDataSet($set_ginecologica);
					// $graf_ginecologica->setTitle("Atenciones Ginecologicas");
					// $graf_ginecologica->render("../graficos/atxhora_ginecologica.png");


				// Datos de ejemplo
				// $dau = [
				//     ['hora' => '08:00', 'adulto' => [['cantidad' => 10]], 'pediatrico' => [['cantidad' => 5]], 'ginecologico' => [['cantidad' => 7]]],
				//     ['hora' => '09:00', 'adulto' => [['cantidad' => 15]], 'pediatrico' => [['cantidad' => 7]], 'ginecologico' => [['cantidad' => 6]]],
				//     ['hora' => '10:00', 'adulto' => [['cantidad' => 20]], 'pediatrico' => [['cantidad' => 10]], 'ginecologico' => [['cantidad' => 8]]],
				// ];

				// // Extraer datos y etiquetas
				// $horas = array_column($dau, 'hora');
				// $adulto = array_column(array_column($dau, 'adulto'), 0, 'cantidad');
				// $pediatrico = array_column(array_column($dau, 'pediatrico'), 0, 'cantidad');
				// $ginecologico = array_column(array_column($dau, 'ginecologico'), 0, 'cantidad');
				foreach ($dau as $item) {
				    $horas[] = $item['hora']; // Etiquetas para el eje X
				    $adulto[] = $item['adulto'][0]['cantidad']; // Cantidades pediátricas
				}
				// Crear gráficos
				crearGrafico("Atenciones Adultas", $adulto, $horas, "../graficos/atxhora_adulta.png");

				foreach ($dau as $item) {
				    $horas[] = $item['hora']; // Etiquetas para el eje X
				    $pediatrico[] = $item['pediatrico'][0]['cantidad']; // Cantidades pediátricas
				}
				// Crear gráficos
				crearGrafico("Atenciones Pediátricas", $pediatrico, $horas, "../graficos/atxhora_pediatrica.png");
				foreach ($dau as $item) {
				    $horas[] = $item['hora']; // Etiquetas para el eje X
				    $ginecologico[] = $item['ginecologico'][0]['cantidad']; // Cantidades pediátricas
				}
				// Crear gráficos
				crearGrafico("Atenciones Ginecológicas", $ginecologico, $horas, "../graficos/atxhora_ginecologica.png");
				// crearGrafico("Atenciones Pediátricas", $pediatrico, $horas, "../graficos/atxhora_pediatrica.png");
				// crearGrafico("Atenciones Ginecológicas", $ginecologico, $horas, "../graficos/atxhora_ginecologica.png");



					$html .='		     
					
					<tr align="left" valign="top">
						<td><strong>TOTAL </strong></td>
						<td align="right"><strong>'.$acum_adulto.'</strong></td>
						<td align="right"><strong>'.$acum_pediatrico.'</strong></td>
						<td align="right"><strong>'.$acum_ginecologico.'</strong></td>
						<td align="right"><strong>'.$total2.'</strong></td>
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
					<td colspan="2" align="center">'.PATH.'/views/reportes/graficos/atxhora_adulta.png</td>
				</tr>

				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="2" align="center">'.PATH.'/views/reportes/graficos/atxhora_ginecologica.png</td>
				</tr>

				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="2" align="center">'.PATH.'/views/reportes/graficos/atxhora_pediatrica.png</td>
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
	</table>		

		';

	$pdf->writeHTML($html, true, false, true, false, '');
	// $pdf->Output('reporteAtencionesPorHora.pdf','FI');
	// $url = RAIZ."/views/reportes/salidas/reporteAtencionesPorHora.pdf";


	$nombre_archivo = "reporteAtencionesPorHora.pdf";
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
<?php 
function crearGrafico($titulo, $datos, $etiquetas, $archivo_salida) {
    // Crear el objeto gráfico
    // Crear gráfico
   	$graph = new Graph(800, 450);
    $graph->SetScale('intlin');
    $graph->img->SetMargin(50, 30, 50, 50);
    $graph->title->Set($titulo);
    $graph->title->SetFont(FF_FONT1, FS_BOLD);
    $graph->xaxis->title->Set("Semanas");
    $graph->yaxis->title->Set("Cantidad");
    // Agregar etiquetas en el eje X
    $graph->xaxis->SetTickLabels($labels);
    $lineplot1 = new BarPlot($datos);
    $lineplot1->SetLegend("Dau Cerrados");
    $lineplot1->SetColor("blue");
    // Agregar las líneas al gráfico
    $graph->Add($lineplot1);
    // Agregar leyenda
    $graph->legend->SetFrameWeight(1);
    $path = __DIR__ . '/../../../reportes/graficos/';
    $filename = $archivo_salida;
    $fullPath = $path . $filename;
    if (file_exists($fullPath)) {
        unlink($fullPath); // Eliminar el archivo
    }
    $graph->Stroke($path . $filename);
    $urlImagen = PATH.'/views/reportes/graficos/'.$fullPath;
}
?>