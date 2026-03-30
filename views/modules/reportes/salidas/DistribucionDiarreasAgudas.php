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
		$pdf->SetTitle('PDF DISTRIBUCION DE DIARREAS AGUDAS CON DESHIDRATACION USO DEIS');
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
		$pdf->AddPage('L', 'A4');

		require("../../../../config/config.php");
		require_once('../../../../class/Connection.class.php');     $objCon     = new Connection; $objCon->db_connect();
		require_once('../../../../class/Util.class.php');           $objUtil    = new Util;
		require_once('../../../../class/Reportes.class.php');       $reporte    = new Reportes;
		$parametros               = $objUtil->getFormulario($_POST);
		$parametros['frm_inicio'] = $objUtil->fechaInvertida($parametros['fechaInicio']);
		$parametros['frm_fin']    = $objUtil->fechaInvertida($parametros['fechaFin']);
		$fechaHoy                 = $objUtil->getFechaPalabra(date('Y-m-d'));
		$diarreas = $reporte->diarreasAgudas($objCon,$parametros);

		// inicializando variables en 0
		$contmeses2_M = 0;
		$contmeses2_F = 0;
		$contmeses3_M = 0;
		$contmeses3_F = 0;
		$contmeses4_M = 0;
		$contmeses4_F = 0;
		$contmeses5_M = 0;
		$contmeses5_F = 0;
		$contmeses6_M = 0;
		$contmeses6_F = 0;
		$contmeses7_M = 0;
		$contmeses7_F = 0;
		$contmeses1_M = 0;
		$contmeses1_F = 0;
		$cont1_4_2M   = 0;
		$cont1_4_2F   = 0;
		$cont1_4_3M   = 0;
		$cont1_4_3F   = 0;
		$cont1_4_4M   = 0;
		$cont1_4_4F   = 0;
		$cont1_4_5M   = 0;
		$cont1_4_5F   = 0;
		$cont1_4_6M   = 0;
		$cont1_4_6F   = 0;
		$cont1_4_7M   = 0;
		$cont1_4_7F   = 0;
		$cont1_4_1M   = 0;
		$cont1_4_1F   = 0;
		$cont5_14_2M  = 0;
		$cont5_14_2F  = 0;
		$cont5_14_3M  = 0;
		$cont5_14_3F  = 0;
		$cont5_14_4M  = 0;
		$cont5_14_4F  = 0;
		$cont5_14_5M  = 0;
		$cont5_14_5F  = 0;
		$cont5_14_6M  = 0;
		$cont5_14_6F  = 0;
		$cont5_14_7M  = 0;
		$cont5_14_7F  = 0;
		$cont5_14_1M  = 0;
		$cont5_14_1F  = 0;
		$cont15_64_2M = 0;
		$cont15_64_2F = 0;
		$cont15_64_3M = 0;
		$cont15_64_3F = 0;
		$cont15_64_4M = 0;
		$cont15_64_4F = 0;
		$cont15_64_5M = 0;
		$cont15_64_5F = 0;
		$cont15_64_6M = 0;
		$cont15_64_6F = 0;
		$cont15_64_7M = 0;
		$cont15_64_7F = 0;
		$cont15_64_1M = 0;
		$cont15_64_1F = 0;
		$cont65_2M    = 0;
		$cont65_2F    = 0;
		$cont65_3M    = 0;
		$cont65_3F    = 0;
		$cont65_4M    = 0;
		$cont65_4F    = 0;
		$cont65_5M    = 0;
		$cont65_5F    = 0;
		$cont65_6M    = 0;
		$cont65_6F    = 0;
		$cont65_7M    = 0;
		$cont65_7F    = 0;
		$cont65_1M    = 0;
		$cont65_1F    = 0;

		for($i=0; $i<count($diarreas); $i++){
			$edad = $diarreas[$i]['dau_paciente_edad'];
			$diaS = $diarreas[$i]['DiaSemana'];	
   			$sexo = $diarreas[$i]['sexo'];

   			switch (TRUE){
	   			case ($edad < 1):
			   		if($diaS == 2){ // lunes
						if(strtoupper($sexo) == 'M'){
							$contmeses2_M++;
						}
						if(strtoupper($sexo) == 'F'){
							$contmeses2_F++;
						}
					}
					if($diaS == 3){ // martes
						if(strtoupper($sexo) == 'M'){
							$contmeses3_M++;
						}
						if(strtoupper($sexo) == 'F'){
							$contmeses3_F++;
						}
					}
					if($diaS == 4){  // miercoles
						if(strtoupper($sexo) == 'M'){
							$contmeses4_M++;
						}
						if(strtoupper($sexo) == 'F'){
							$contmeses4_F++;
						}
					}
					if($diaS == 5){  // jueves
						if(strtoupper($sexo) == 'M'){
							$contmeses5_M++;
						}
						if(strtoupper($sexo) == 'F'){
							$contmeses5_F++;
						}
					}
					if($diaS == 6){ // viernes
						if(strtoupper($sexo) == 'M'){
							$contmeses6_M++;
						}
						if(strtoupper($sexo) == 'F'){
							$contmeses6_F++;
						}
					}
					if($diaS == 7){ // sabado
						if(strtoupper($sexo) == 'M'){
							$contmeses7_M++;
						}
						if(strtoupper($sexo) == 'F'){
							$contmeses7_F++;
						}
					}
					if($diaS == 1){ // domingo
						if(strtoupper($sexo) == 'M'){
							$contmeses1_M++;
						}
						if(strtoupper($sexo) == 'F'){
							$contmeses1_F++;
						}
					}
				break;

				case ($edad >= 1 && $edad <= 4):
					if($diaS == 2){ // lunes
						if(strtoupper($sexo) == 'M'){
							$cont1_4_2M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont1_4_2F++;
						}
					}
					if($diaS == 3){ // martes
						if(strtoupper($sexo) == 'M'){
							$cont1_4_3M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont1_4_3F++;
						}
					}
					if($diaS == 4){  // miercoles
						if(strtoupper($sexo) == 'M'){
							$cont1_4_4M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont1_4_4F++;
						}
					}
					if($diaS == 5){  // jueves
						if(strtoupper($sexo) == 'M'){
							$cont1_4_5M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont1_4_5F++;
						}
					}
					if($diaS == 6){ // viernes
						if(strtoupper($sexo) == 'M'){
							$cont1_4_6M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont1_4_6F++;
						}
					}
					if($diaS == 7){ // sabado
						if(strtoupper($sexo) == 'M'){
							$cont1_4_7M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont1_4_7F++;
						}
					}
					if($diaS == 1){ // domingo
						if(strtoupper($sexo) == 'M'){
							$cont1_4_1M++;	
						}
						if(strtoupper($sexo) == 'F'){
							$cont1_4_1F++;
						}
					}
				break;

				case ($edad >=5 && $edad <= 14):
					if($diaS == 2){ // lunes
						if(strtoupper($sexo) == 'M'){
							$cont5_14_2M++;	
						}
						if(strtoupper($sexo) == 'F'){
							$cont5_14_2F++;
						}
					}
					if($diaS == 3){ // martes
						if(strtoupper($sexo) == 'M'){
							$cont5_14_3M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont5_14_3F++;
						}
					}
					if($diaS == 4){  // miercoles
						if(strtoupper($sexo) == 'M'){
							$cont5_14_4M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont5_14_4F++;
						}
					}
					if($diaS == 5){  // jueves
						if(strtoupper($sexo) == 'M'){
							$cont5_14_5M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont5_14_5F++;
						}
					}
					if($diaS == 6){ // viernes
						if(strtoupper($sexo) == 'M'){
							$cont5_14_6M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont5_14_6F++;
						}
					}
					if($diaS == 7){ // sabado
						if(strtoupper($sexo) == 'M'){
							$cont5_14_7M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont5_14_7F++;
						}
					}
					if($diaS == 1){ // domingo
						if(strtoupper($sexo) == 'M'){
							$cont5_14_1M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont5_14_1F++;
						}
					}	 
				break;

				case ($edad >= 15 && $edad <= 64 ):
					if($diaS == 2){ // lunes
						if(strtoupper($sexo) == 'M'){
							$cont15_64_2M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont15_64_2F++;
						}
					}
					if($diaS == 3){ // martes
						if(strtoupper($sexo) == 'M'){
							$cont15_64_3M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont15_64_3F++;
						}
					}
					if($diaS == 4){  // miercoles
						if(strtoupper($sexo) == 'M'){
							$cont15_64_4M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont15_64_4F++;
						}
					}
					if($diaS == 5){  // jueves
						if(strtoupper($sexo) == 'M'){
							$cont15_64_5M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont15_64_5F++;
						}
					}
					if($diaS == 6){ // viernes
						if(strtoupper($sexo) == 'M'){
							$cont15_64_6M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont15_64_6F++;
						}
					}
					if($diaS == 7){ // sabado
						if(strtoupper($sexo) == 'M'){
							$cont15_64_7M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont15_64_7F++;
						}
					}
					if($diaS == 1){ // domingo
						if(strtoupper($sexo) == 'M'){
							$cont15_64_1M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont15_64_1F++;
						}
					}
				break;

				case($edad >= 65):
					if($diaS == 2){ // lunes
						if(strtoupper($sexo) == 'M'){
							$cont65_2M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont65_2F++;
						}
					}
					if($diaS == 3){ // martes
						if(strtoupper($sexo) == 'M'){
							$cont65_3M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont65_3F++;
						}
					}
					if($diaS == 4){  // miercoles
						if(strtoupper($sexo) == 'M'){
							$cont65_4M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont65_4F++;
						}
					}
					if($diaS == 5){  // jueves
						if(strtoupper($sexo) == 'M'){
							$cont65_5M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont65_5F++;
						}
					}
					if($diaS == 6){ // viernes
						if(strtoupper($sexo) == 'M'){
							$cont65_6M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont65_6F++;
						}
					}
					if($diaS == 7){ // sabado
						if(strtoupper($sexo) == 'M'){
							$cont65_7M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont65_7F++;
						}
					}
					if($diaS == 1){ // domingo
						if(strtoupper($sexo) == 'M'){
							$cont65_1M++;
						}
						if(strtoupper($sexo) == 'F'){
							$cont65_1F++;
						}
					}
				break;
			}
		}

		//highlight_string(print_r($parametros),true);

		$html = '
		<table width="765" >
			<tr>
			<td border="0" width="115">
			<pre></pre>
				<img src="'.PATH.'/assets/img/logo.png" width="55" height="55" />
				<img src="'.PATH.'/assets/img/nuestroHospital.png" width="55" height="55" />
			</td>

			<td   valign="top">
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
				<td width="71%" >
					<table td width="50%" align="left" >
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;						
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
		
		<table >
			<tr>
				<td align="center">
					<strong style="font-size:10; color: ">DISTRIBUCION DE DIARREAS AGUDAS CON DESHIDRATACION USO DEIS<br />
													      SEMANA ESTADISTICA : '. strftime("%W",mktime(0,0,0,substr($parametros['frm_inicio'],5,2),substr($parametros['frm_inicio'],8,2),substr($parametros['frm_inicio'],0,4))) .'<br /> 
													      PERIODO :            '.$_POST['fechaInicio'].' AL '.$_POST['fechaFin'].'</strong>
				</td>
		    </tr>
		    <br>	    
			<table width="896" border="1" cellpadding="2" cellspacing="0" class="reporte">
				<tr>
					<td width="77" rowspan="2" align="center"><strong>GRUPO<br />ETARIOS</strong><strong></strong></td>
					<td colspan="2" align="center"><strong>LUNES</strong></td>
					<td colspan="2" align="center"><strong>MARTES</strong></td>
					<td colspan="2" align="center"><strong>MIERCOLES</strong></td>
					<td colspan="2" align="center"><strong>JUEVES</strong></td>
					<td colspan="2" align="center"><strong>VIERNES</strong></td>
					<td colspan="2" align="center"><strong>SABADO</strong></td>
					<td colspan="2" align="center"><strong>DOMINGO</strong></td>
					<td colspan="2" align="center"><strong>TOTAL</strong></td>
					<td width="42" rowspan="2"><strong>TOTAL<br />GRAL.</strong></td>
				</tr>

				<tr align="left" valign="top">
					<td width="50" align="center" valign="bottom"  bgcolor="#FFFFCC"><strong>M</strong></td>
					<td width="50" align="center" valign="bottom" bgcolor="#E0E0E0"><strong>F</strong></td>
					<td width="50" align="center" valign="bottom"  bgcolor="#FFFFCC"><strong>M</strong></td>
					<td width="49" align="center" valign="bottom" bgcolor="#E0E0E0"><strong>F</strong></td>
					<td width="50" align="center" valign="bottom" bgcolor="#FFFFCC"><strong>M</strong></td>
					<td width="50" align="center" valign="bottom" bgcolor="#E0E0E0"><strong>F</strong></td>
					<td width="50" align="center" valign="bottom" bgcolor="#FFFFCC"><strong>M</strong></td>
					<td width="49" align="center" valign="bottom" bgcolor="#E0E0E0"><strong>F</strong></td>
					<td width="50" align="center" valign="bottom" bgcolor="#FFFFCC"><strong>M</strong></td>
					<td width="50" align="center" valign="bottom" bgcolor="#E0E0E0"><strong>F</strong></td>
					<td width="50" align="center" valign="bottom" bgcolor="#FFFFCC"><strong>M</strong></td>
					<td width="50" align="center" valign="bottom" bgcolor="#E0E0E0"><strong>F</strong></td>
					<td width="50" align="center" valign="bottom" bgcolor="#FFFFCC"><strong>M</strong></td>
					<td width="49" align="center" valign="bottom" bgcolor="#E0E0E0"><strong>F</strong></td>
					<td width="50" align="center" valign="bottom" bgcolor="#FFFF99"><strong>M</strong></td>
					<td width="49" align="center" valign="bottom" bgcolor="#A6A6A6"><strong>F</strong></td>
				</tr>

				<tr align="left" valign="top">
					<td align="center">&nbsp;&nbsp;- 1 AÑO </td>
					<td align="center" valign="bottom" bgcolor="#FFFFCC">'.$contmeses2_M.'</td>
					<td align="center" valign="bottom" bgcolor="#E0E0E0">'.$contmeses2_F.'</td>
					<td align="center" valign="bottom" bgcolor="#FFFFCC">'.$contmeses3_M.'</td>
					<td align="center" valign="bottom" bgcolor="#E0E0E0">'.$contmeses3_F.'</td>
					<td align="center" valign="bottom" bgcolor="#FFFFCC">'.$contmeses4_M.'</td>
					<td align="center" valign="bottom" bgcolor="#E0E0E0">'.$contmeses4_F.'</td>
					<td align="center" valign="bottom" bgcolor="#FFFFCC">'.$contmeses5_M.'</td>
					<td align="center" valign="bottom" bgcolor="#E0E0E0">'.$contmeses5_F.'</td>
					<td align="center" valign="bottom" bgcolor="#FFFFCC">'.$contmeses6_M.'</td>
					<td align="center" valign="bottom" bgcolor="#E0E0E0">'.$contmeses6_F.'</td>
					<td align="center" valign="bottom" bgcolor="#FFFFCC">'.$contmeses7_M.'</td>
					<td align="center" valign="bottom" bgcolor="#E0E0E0">'.$contmeses7_F.'</td>
					<td align="center" valign="bottom" bgcolor="#FFFFCC">'.$contmeses1_M.'</td>
					<td align="center" valign="bottom" bgcolor="#E0E0E0">'.$contmeses1_F.'</td>
					<td align="center" valign="bottom" bgcolor="#FFFF99">'.$totalmesesM = $contmeses2_M + $contmeses3_M + $contmeses4_M + $contmeses5_M + $contmeses6_M + $contmeses7_M + $contmeses1_M.'</td>
					<td align="center" valign="bottom" bgcolor="#A6A6A6">'.$totalmesesF = $contmeses2_F + $contmeses3_F + $contmeses4_F + $contmeses5_F + $contmeses6_F + $contmeses7_F + $contmeses1_F.'</td>
					<td align="center" valign="bottom">'.$totalmesesGral = $contmeses2_M + $contmeses3_M + $contmeses4_M + $contmeses5_M + $contmeses6_M + $contmeses7_M + $contmeses1_M + $contmeses2_F + $contmeses3_F + $contmeses4_F + $contmeses5_F + $contmeses6_F + $contmeses7_F + $contmeses1_F.'</td>
				</tr>

				<tr align="left" valign="top">
					<td align="center">&nbsp;&nbsp;1 - 4 AÑOS</td>
					<td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont1_4_2M.'</td>
					<td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont1_4_2F.'</td>
					<td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont1_4_3M.'</td>
					<td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont1_4_3F.'</td>
					<td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont1_4_4M.'</td>
					<td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont1_4_4F.'</td>
					<td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont1_4_5M.'</td>
					<td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont1_4_5F.'</td>
					<td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont1_4_6M.'</td>
					<td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont1_4_6F.'</td>
					<td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont1_4_7M.'</td>
					<td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont1_4_7F.'</td>
					<td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont1_4_1M.'</td>
					<td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont1_4_1F.'</td>
					<td align="center" valign="bottom" bgcolor="#FFFF99">'.$total1_4M = $cont1_4_2M + $cont1_4_3M + $cont1_4_4M + $cont1_4_5M + $cont1_4_6M + $cont1_4_7M + $cont1_4_1M.'</td>
					<td align="center" valign="bottom" bgcolor="#A6A6A6">'.$total1_4F = $cont1_4_2F + $cont1_4_3F + $cont1_4_4F + $cont1_4_5F + $cont1_4_6F + $cont1_4_7F + $cont1_4_1F.'</td>
					<td align="center" valign="bottom">'.$total1_4Gral = $cont1_4_2M + $cont1_4_3M + $cont1_4_4M + $cont1_4_5M + $cont1_4_6M + $cont1_4_7M + $cont1_4_1M + $cont1_4_2F + $cont1_4_3F + $cont1_4_4F + $cont1_4_5F + $cont1_4_6F + $cont1_4_7F + $cont1_4_1F.'</td>
				</tr>

				<tr align="left" valign="top">
			        <td align="center">&nbsp;&nbsp;5 - 14 AÑOS</td>
			        <td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont5_14_2M.'</td>
			        <td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont5_14_2F.'</td>
			        <td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont5_14_3M.'</td>
			        <td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont5_14_3F.'</td>
			        <td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont5_14_4M.'</td>
			        <td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont5_14_4F.'</td>
			        <td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont5_14_5M.'</td>
			        <td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont5_14_5F.'</td>
			        <td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont5_14_6M.'</td>
			        <td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont5_14_6F.'</td>
			        <td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont5_14_7M.'</td>
			        <td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont5_14_7F.'</td>
			        <td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont5_14_1M.'</td>
			        <td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont5_14_1F.'</td>
			        <td align="center" valign="bottom" bgcolor="#FFFF99">'.$total5_14M = $cont5_14_2M + $cont5_14_3M + $cont5_14_4M + $cont5_14_5M + $cont5_14_6M + $cont5_14_7M + $cont5_14_1M.'</td>
			        <td align="center" valign="bottom" bgcolor="#A6A6A6">'.$total5_14F = $cont5_14_2F + $cont5_14_3F + $cont5_14_4F + $cont5_14_5F + $cont5_14_6F + $cont5_14_7F + $cont5_14_1F.'</td>
			        <td align="center" valign="bottom">'.$total5_14Gral = $cont5_14_2M + $cont5_14_3M + $cont5_14_4M + $cont5_14_5M + $cont5_14_6M + $cont5_14_7M + $cont5_14_1M + $cont5_14_2F + $cont5_14_3F + $cont5_14_4F + $cont5_14_5F + $cont5_14_6F + $cont5_14_7F + $cont5_14_1F.'</td>
			     </tr>

			     <tr align="left" valign="top">
			     	<td align="center">&nbsp;&nbsp;15 - 64 AÑOS</td>
			     	<td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont15_64_2M.'</td>
			     	<td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont15_64_2F.'</td>
			     	<td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont15_64_3M.'</td>
			     	<td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont15_64_3F.'</td>
			     	<td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont15_64_4M.'</td>
			     	<td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont15_64_4F.'</td>
			     	<td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont15_64_5M.'</td>
			     	<td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont15_64_5F.'</td>
			     	<td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont15_64_6M.'</td>
			     	<td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont15_64_6F.'</td>
			     	<td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont15_64_7M.'</td>
			     	<td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont15_64_7F.'</td>
			     	<td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont15_64_1M.'</td>
			     	<td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont15_64_1F.'</td>
			     	<td align="center" valign="bottom" bgcolor="#FFFF99">'.$total15_64M = $cont15_64_2M  + $cont15_64_3M + $cont15_64_4M + $cont15_64_5M + $cont15_64_6M + $cont15_64_7M + $cont15_64_1M.'</td>
			     	<td align="center" valign="bottom" bgcolor="#A6A6A6">'.$total15_64F = $cont15_64_2F  + $cont15_64_3F + $cont15_64_4F + $cont15_64_5F + $cont15_64_6F + $cont15_64_7F + $cont15_64_1F.'</td>
			     	<td align="center" valign="bottom">'.$total15_64Gral = $cont15_64_2M  + $cont15_64_3M + $cont15_64_4M + $cont15_64_5M + $cont15_64_6M + $cont15_64_7M + $cont15_64_1M + $cont15_64_2F  + $cont15_64_3F + $cont15_64_4F + $cont15_64_5F + $cont15_64_6F + $cont15_64_7F + $cont15_64_1F .'</td>
			     </tr>

			     <tr align="left" valign="top">
			        <td align="center">&nbsp;&nbsp;+ 65 AÑOS</td>
			        <td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont65_2M.'</td>
			        <td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont65_2F.'</td>
			        <td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont65_3M.'</td>
			        <td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont65_3F.'</td>
			        <td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont65_4M.'</td>
			        <td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont65_4F.'</td>
			        <td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont65_5M.'</td>
			        <td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont65_5F.'</td>
			        <td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont65_6M.'</td>
			        <td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont65_6F.'</td>
			        <td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont65_7M.'</td>
			        <td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont65_7F.'</td>
			        <td align="center" valign="bottom" bgcolor="#FFFFCC">'.$cont65_1M.'</td>
			        <td align="center" valign="bottom" bgcolor="#E0E0E0">'.$cont65_1F.'</td>
			        <td align="center" valign="bottom" bgcolor="#FFFF99">'.$total65_M = $cont65_2M + $cont65_3M + $cont65_4M + $cont65_5M + $cont65_6M + $cont65_7M + $cont65_1M.'</td>
			        <td align="center" valign="bottom" bgcolor="#A6A6A6">'.$total65_F = $cont65_2F + $cont65_3F + $cont65_4F + $cont65_5F + $cont65_6F + $cont65_7F + $cont65_1F.'</td>
			        <td align="center" valign="bottom">'.$total65Gral = $cont65_2M + $cont65_3M + $cont65_4M + $cont65_5M + $cont65_6M + $cont65_7M + $cont65_1M + $cont65_2F + $cont65_3F + $cont65_4F + $cont65_5F + $cont65_6F + $cont65_7F + $cont65_1F.'</td>
			      </tr>

			      <tr align="left" valign="top" >
			      	<td align="center"><strong>&nbsp;&nbsp;TOTAL</strong></td>
			      	<td align="center" valign="bottom" bgcolor="#FFFF99">'. $totalParcialLunesM  = $contmeses2_M + $cont1_4_2M + $cont5_14_2M + $cont15_64_2M + $cont65_2M.'</td>
			      	<td align="center" valign="bottom" bgcolor="#A6A6A6">'. $totalParcialLunesF  = $contmeses2_F + $cont1_4_2F + $cont5_14_2F + $cont15_64_2F + $cont65_2F.'</td>
			      	<td align="center" valign="bottom" bgcolor="#FFFF99">'. $totalParcialMartesM = $contmeses3_M + $cont1_4_3M + $cont5_14_3M + $cont15_64_3M + $cont65_3M.'</td>
			      	<td align="center" valign="bottom" bgcolor="#A6A6A6">'. $totalParcialMartesF = $contmeses3_F + $cont1_4_3F + $cont5_14_3F + $cont15_64_3F + $cont65_3F.'</td>
			      	<td align="center" valign="bottom" bgcolor="#FFFF99">'. $totalParcialMiercolesM = $contmeses4_M + $cont1_4_4M + $cont5_14_4M + $cont15_64_4M + $cont65_4M.'</td>
			      	<td align="center" valign="bottom" bgcolor="#A6A6A6">'. $totalParcialMiercolesJ = $contmeses4_F + $cont1_4_4F + $cont5_14_4F + $cont15_64_4F + $cont65_4F.'</td>
			      	<td align="center" valign="bottom" bgcolor="#FFFF99">'. $totalParcialJuevesM = $contmeses5_M + $cont1_4_5M + $cont5_14_5M + $cont15_64_5M + $cont65_5M.'</td>
			      	<td align="center" valign="bottom" bgcolor="#A6A6A6">'. $totalParcialJuevesF = $contmeses5_F + $cont1_4_5F + $cont5_14_5F + $cont15_64_5F + $cont65_5F.'</td>
			      	<td align="center" valign="bottom" bgcolor="#FFFF99">'. $totalParcialViernesM = $contmeses6_M + $cont1_4_6M + $cont5_14_6M + $cont15_64_6M + $cont65_6M.'</td>
			      	<td align="center" valign="bottom" bgcolor="#A6A6A6">'. $totalParcialViernesF = $contmeses6_F + $cont1_4_6F + $cont5_14_6F + $cont15_64_6F + $cont65_6F.'</td>
			      	<td align="center" valign="bottom" bgcolor="#FFFF99">'. $totalParcialSabadoM = $contmeses7_M + $cont1_4_7M + $cont5_14_7M + $cont15_64_7M + $cont65_7M.'</td>
			      	<td align="center" valign="bottom" bgcolor="#A6A6A6">'. $totalParcialSabadoF = $contmeses7_F + $cont1_4_7F + $cont5_14_7F + $cont15_64_7F + $cont65_7F.'</td>
			      	<td align="center" valign="bottom" bgcolor="#FFFF99">'. $totalParcialDomingoM = $contmeses1_M + $cont1_4_1M + $cont5_14_1M + $cont15_64_1M + $cont65_1M.'</td>
			      	<td align="center" valign="bottom" bgcolor="#A6A6A6">'. $totalParcialDomingoF = $contmeses1_F + $cont1_4_1F + $cont5_14_1F + $cont15_64_1F + $cont65_1F.'</td>
			      	<td align="center" valign="bottom" bgcolor="#FFFF99">'. $totalParcialGralM = $contmeses2_M + $contmeses3_M + $contmeses4_M + $contmeses5_M + $contmeses6_M + $contmeses7_M + $contmeses1_M + $cont1_4_2M + $cont1_4_3M + $cont1_4_4M + $cont1_4_5M + $cont1_4_6M + $cont1_4_7M + $cont1_4_1M + $cont5_14_2M + $cont5_14_3M + $cont5_14_4M + $cont5_14_5M + $cont5_14_6M + $cont5_14_7M + $cont5_14_1M + $cont15_64_2M  + $cont15_64_3M + $cont15_64_4M + $cont15_64_5M + $cont15_64_6M + $cont15_64_7M + $cont15_64_1M + $cont65_2M + $cont65_3M + $cont65_4M + $cont65_5M + $cont65_6M + $cont65_7M + $cont65_1M.'</td>
			      	<td align="center" valign="bottom" bgcolor="#A6A6A6">'. $totalParcialGralF  = $contmeses2_F + $contmeses3_F + $contmeses4_F + $contmeses5_F + $contmeses6_F + $contmeses7_F + $contmeses1_F + $cont1_4_2F + $cont1_4_3F + $cont1_4_4F + $cont1_4_5F + $cont1_4_6F + $cont1_4_7F + $cont1_4_1F + $cont5_14_2F + $cont5_14_3F + $cont5_14_4F + $cont5_14_5F + $cont5_14_6F + $cont5_14_7F + $cont5_14_1F + $cont15_64_2F  + $cont15_64_3F + $cont15_64_4F + $cont15_64_5F + $cont15_64_6F + $cont15_64_7F + $cont15_64_1F + $cont65_2F + $cont65_3F + $cont65_4F + $cont65_5F + $cont65_6F + $cont65_7F + $cont65_1F.'</td>
			      	<td align="center" valign="bottom">'.$total = $contmeses2_M + $contmeses3_M + $contmeses4_M + $contmeses5_M + $contmeses6_M + $contmeses7_M + $contmeses1_M + $contmeses2_F + $contmeses3_F + $contmeses4_F + $contmeses5_F + $contmeses6_F + $contmeses7_F + $contmeses1_F + $cont1_4_2M + $cont1_4_3M + $cont1_4_4M + $cont1_4_5M + $cont1_4_6M + $cont1_4_7M + $cont1_4_1M + $cont1_4_2F + $cont1_4_3F + $cont1_4_4F + $cont1_4_5F + $cont1_4_6F + $cont1_4_7F + $cont1_4_1F + $cont5_14_2M + $cont5_14_3M + $cont5_14_4M + $cont5_14_5M + $cont5_14_6M + $cont5_14_7M + $cont5_14_1M + $cont5_14_2F + $cont5_14_3F + $cont5_14_4F + $cont5_14_5F + $cont5_14_6F + $cont5_14_7F + $cont5_14_1F + $cont15_64_2M  + $cont15_64_3M + $cont15_64_4M + $cont15_64_5M + $cont15_64_6M + $cont15_64_7M + $cont15_64_1M + $cont15_64_2F  + $cont15_64_3F + $cont15_64_4F + $cont15_64_5F + $cont15_64_6F + $cont15_64_7F + $cont15_64_1F + $cont65_2M + $cont65_3M + $cont65_4M + $cont65_5M + $cont65_6M + $cont65_7M + $cont65_1M + $cont65_2F + $cont65_3F + $cont65_4F + $cont65_5F + $cont65_6F + $cont65_7F + $cont65_1F.'</td>
			      </tr>

			</table>
			<br>

			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3"  style="text-decoration:none;color:#666">*No incluye Atenciones Ginecol&oacute;gicas*</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
  
			<tr>
				<td colspan="4">
					<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reportechico">
						<tr>
							<td  style="text-decoration:none;color:#666">Nombre responsable de la informaci&oacute;n :  </td>
						</tr>
						<tr>
							<td  style="text-decoration:none;color:#666">Fecha Emisión Reporte: <strong>'.date('d-m-Y').'</strong></td>
						</tr> 
					</table>
				</td>
			</tr>
		</table>

		<br>';

	$pdf->writeHTML($html, true, false, true, false, '');


	$nombre_archivo = "reportesDistribucionDiarreasAgudas.pdf";
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