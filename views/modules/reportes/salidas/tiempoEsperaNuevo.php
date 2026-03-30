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
	$pdf->SetTitle('PDF REM A-08');
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
	$pdf->AddPage('L');
		//$pdf->AddPage('L', 'A4');

	require("../../../../config/config.php");
	require_once('../../../../class/Connection.class.php');     $objCon     = new Connection; $objCon->db_connect();
	require_once('../../../../class/Util.class.php');           $objUtil    = new Util;
	require_once('../../../../class/Reportes.class.php');       $reporte    = new Reportes;
	$parametros               = $objUtil->getFormulario($_POST);
	$parametros['frm_inicio'] = $objUtil->fechaInvertida($parametros['fechaInicio']);
	$parametros['frm_fin']    = $objUtil->fechaInvertida($parametros['fechaFin']);
	$fechaHoy                 = $objUtil->getFechaPalabra(date('Y-m-d'));
	$tiempoEspera             = $reporte->tiempoEsperaNuevo($objCon,$parametros);

	$m_12_10_14_h = 0;
	$m_12_10_14_m = 0;
	$m_12_0_4 = 0;
	$m_12_0_4_h = 0;
	$m_12_0_4_m = 0;
	$m_12_5_9 = 0;
	$m_12_5_9_h = 0;
	$m_12_5_9_m = 0;
	$m_12_10_14= 0;
	$m_12_10_1_h = 0;
	$m_12_10_1_m = 0;
	$m_12_15_19 = 0;
	$m_12_15_19_h = 0;
	$m_12_15_19_m = 0;
	$m_12_20_24 = 0;
	$m_12_20_24_h = 0;
	$m_12_20_24_m = 0;
	$m_12_25_29 = 0;
	$m_12_25_29_h = 0;
	$m_12_25_29_m = 0;
	$m_12_30_34 = 0;
	$m_12_30_34_h = 0;
	$m_12_30_34_m = 0;
	$m_12_35_39 = 0;
	$m_12_35_39_h = 0;
	$m_12_35_39_m = 0;
	$m_12_40_44 = 0;
	$m_12_40_44_h = 0;
	$m_12_40_44_m = 0;
	$m_12_45_49 = 0;
	$m_12_45_49_h = 0;
	$m_12_45_49_m = 0;
	$m_12_50_54 = 0;
	$m_12_50_54_h = 0;
	$m_12_50_54_m = 0;
	$m_12_55_59 = 0;
	$m_12_55_59_h = 0;
	$m_12_55_59_m = 0;
	$m_12_60_64 = 0;
	$m_12_60_64_h = 0;
	$m_12_60_64_m = 0;
	$m_12_65_69 = 0;
	$m_12_65_69_h = 0;
	$m_12_65_69_m = 0;
	$m_12_70_74 = 0;
	$m_12_70_74_h = 0;
	$m_12_70_74_m = 0;
	$m_12_75_79 = 0;
	$m_12_75_79_h = 0;
	$m_12_75_79_m = 0;
	$m_12_80_m = 0;
	$m_12_80_m_h = 0;
	$m_12_80_m_m = 0;

	$m_24_0_4 = 0;
	$m_24_0_4_h = 0;
	$m_24_0_4_m = 0;
	$m_24_5_9 = 0;
	$m_24_5_9_h = 0;
	$m_24_5_9_m = 0;
	$m_24_10_14= 0;
	$m_24_10_14_h = 0;
	$m_24_10_14_m = 0;
	$m_24_15_19 = 0;
	$m_24_15_19_h = 0;
	$m_24_15_19_m = 0;
	$m_24_20_24 = 0;
	$m_24_20_24_h = 0;
	$m_24_20_24_m = 0;
	$m_24_25_29 = 0;
	$m_24_25_29_h = 0;
	$m_24_25_29_m = 0;
	$m_24_30_34 = 0;
	$m_24_30_34_h = 0;
	$m_24_30_34_m = 0;
	$m_24_35_39 = 0;
	$m_24_35_39_h = 0;
	$m_24_35_39_m = 0;
	$m_24_40_44 = 0;
	$m_24_40_44_h = 0;
	$m_24_40_44_m = 0;
	$m_24_45_49 = 0;
	$m_24_45_49_h = 0;
	$m_24_45_49_m = 0;
	$m_24_50_54 = 0;
	$m_24_50_54_h = 0;
	$m_24_50_54_m = 0;
	$m_24_55_59 = 0;
	$m_24_55_59_h = 0;
	$m_24_55_59_m = 0;
	$m_24_60_64 = 0;
	$m_24_60_64_h = 0;
	$m_24_60_64_m = 0;
	$m_24_65_69 = 0;
	$m_24_65_69_h = 0;
	$m_24_65_69_m = 0;
	$m_24_70_74 = 0;
	$m_24_70_74_h = 0;
	$m_24_70_74_m = 0;
	$m_24_75_79 = 0;
	$m_24_75_79_h = 0;
	$m_24_75_79_m = 0;
	$m_24_80_m = 0;
	$m_24_80_m_h = 0;
	$m_24_80_m_m = 0;

	$ma_24_0_4 = 0;
	$ma_24_0_4_h = 0;
	$ma_24_0_4_m = 0;
	$ma_24_5_9 = 0;
	$ma_24_5_9_h = 0;
	$ma_24_5_9_m = 0;
	$ma_24_10_14= 0;
	$ma_24_10_14_h = 0;
	$ma_24_10_14_m = 0;
	$ma_24_15_19 = 0;
	$ma_24_15_19_h = 0;
	$ma_24_15_19_m = 0;
	$ma_24_20_24 = 0;
	$ma_24_20_24_h = 0;
	$ma_24_20_24_m = 0;
	$ma_24_25_29 = 0;
	$ma_24_25_29_h = 0;
	$ma_24_25_29_m = 0;
	$ma_24_30_34 = 0;
	$ma_24_30_34_h = 0;
	$ma_24_30_34_m = 0;
	$ma_24_35_39 = 0;
	$ma_24_35_39_h = 0;
	$ma_24_35_39_m = 0;
	$ma_24_40_44 = 0;
	$ma_24_40_44_h = 0;
	$ma_24_40_44_m = 0;
	$ma_24_45_49 = 0;
	$ma_24_45_49_h = 0;
	$ma_24_45_49_m = 0;
	$ma_24_50_54 = 0;
	$ma_24_50_54_h = 0;
	$ma_24_50_54_m = 0;
	$ma_24_55_59 = 0;
	$ma_24_55_59_h = 0;
	$ma_24_55_59_m = 0;
	$ma_24_60_64 = 0;
	$ma_24_60_64_h = 0;
	$ma_24_60_64_m = 0;
	$ma_24_65_69 = 0;
	$ma_24_65_69_h = 0;
	$ma_24_65_69_m = 0;
	$ma_24_70_74 = 0;
	$ma_24_70_74_h = 0;
	$ma_24_70_74_m = 0;
	$ma_24_75_79 = 0;
	$ma_24_75_79_h = 0;
	$ma_24_75_79_m = 0;
	$ma_24_80_m = 0;
	$ma_24_80_m_h = 0;
	$ma_24_80_m_m = 0;

	$t_h = 0;
	$t_m = 0;
	$t_t = 0;

	$t_h24 = 0;
	$t_m24 = 0;
	$t_t24 = 0;

	$t_hm24 = 0;
	$t_mm24 = 0;
	$t_tmm24 = 0;

	$m12convenio = 0;
	$m24convenio = 0;
	$ma24convenio = 0;

	for ($i=0; $i<count($tiempoEspera); $i++) {
		$diferencia   = $tiempoEspera[$i]['diferencia'];
		$edad         = $tiempoEspera[$i]['dau_paciente_edad'];
		$sexo         = $tiempoEspera[$i]['sexo'];
		$conveniopago = $tiempoEspera[$i]['conveniopago'];

		if($diferencia < '12:00:00'){
			if ($edad < 5) { $m_12_0_4++; if($sexo == "M"){$m_12_0_4_h++;}else{$m_12_0_4_m++;}}
			if ($edad > 4 and $edad < 10) { $m_12_5_9++; if($sexo == "M"){$m_12_5_9_h++;}else{$m_12_5_9_m++;}}
			if ($edad > 9 and $edad < 15) { $m_12_10_14++; if($sexo == "M"){$m_12_10_14_h++;}else{$m_12_10_14_m++;}}
			if ($edad > 14 and $edad < 20) { $m_12_15_19++; if($sexo == "M"){$m_12_15_19_h++;}else{$m_12_15_19_m++;}}
			if ($edad > 19 and $edad < 25) { $m_12_20_24++; if($sexo == "M"){$m_12_20_24_h++;}else{$m_12_20_24_m++;}}
			if ($edad > 24 and $edad < 30) { $m_12_25_29++; if($sexo == "M"){$m_12_25_29_h++;}else{$m_12_25_29_m++;}}
			if ($edad > 29 and $edad < 35) { $m_12_30_34++; if($sexo == "M"){$m_12_30_34_h++;}else{$m_12_30_34_m++;}}
			if ($edad > 34 and $edad < 40) { $m_12_35_39++; if($sexo == "M"){$m_12_35_39_h++;}else{$m_12_35_39_m++;}}
			if ($edad > 39 and $edad < 45) { $m_12_40_44++; if($sexo == "M"){$m_12_40_44_h++;}else{$m_12_40_44_m++;}}
			if ($edad > 44 and $edad < 50) { $m_12_45_49++; if($sexo == "M"){$m_12_45_49_h++;}else{$m_12_45_49_m++;}}
			if ($edad > 49 and $edad < 55) { $m_12_50_54++; if($sexo == "M"){$m_12_50_54_h++;}else{$m_12_50_54_m++;}}
			if ($edad > 54 and $edad < 60) { $m_12_55_59++; if($sexo == "M"){$m_12_55_59_h++;}else{$m_12_55_59_m++;}}
			if ($edad > 59 and $edad < 65) { $m_12_60_64++; if($sexo == "M"){$m_12_60_64_h++;}else{$m_12_60_64_m++;}}
			if ($edad > 64 and $edad < 70) { $m_12_65_69++; if($sexo == "M"){$m_12_65_69_h++;}else{$m_12_65_69_m++;}}
			if ($edad > 69 and $edad < 75) { $m_12_70_74++; if($sexo == "M"){$m_12_70_74_h++;}else{$m_12_70_74_m++;}}
			if ($edad > 74 and $edad < 80) { $m_12_75_79++; if($sexo == "M"){$m_12_75_79_h++;}else{$m_12_75_79_m++;}}
			if ($edad > 79) { $m_12_80_m++; if($sexo == "M"){$m_12_80_m_h++;}else{$m_12_80_m_m++;}}
   			if ($conveniopago == '1' || $conveniopago == '6' || $conveniopago == '12'){$m12convenio++;}
		}elseif($diferencia >= '12:00:00' and $diferencia < '24:00:00'){
			if ($edad < 5) { $m_24_0_4++; if($sexo == "M"){$m_24_0_4_h++;}else{$m_24_0_4_m++;}}
			if ($edad > 4 and $edad < 10) { $m_24_5_9++; if($sexo == "M"){$m_24_5_9_h++;}else{$m_24_5_9_m++;}}
			if ($edad > 9 and $edad < 15) { $m_24_10_14++; if($sexo == "M"){$m_24_10_14_h++;}else{$m_24_10_14_m++;}}
			if ($edad > 14 and $edad < 20) { $m_24_15_19++; if($sexo == "M"){$m_24_15_19_h++;}else{$m_24_15_19_m++;}}
			if ($edad > 19 and $edad < 25) { $m_24_20_24++; if($sexo == "M"){$m_24_20_24_h++;}else{$m_24_20_24_m++;}}
			if ($edad > 24 and $edad < 30) { $m_24_25_29++; if($sexo == "M"){$m_24_25_29_h++;}else{$m_24_25_29_m++;}}
			if ($edad > 29 and $edad < 35) { $m_24_30_34++; if($sexo == "M"){$m_24_30_34_h++;}else{$m_24_30_34_m++;}}
			if ($edad > 34 and $edad < 40) { $m_24_35_39++; if($sexo == "M"){$m_24_35_39_h++;}else{$m_24_35_39_m++;}}
			if ($edad > 39 and $edad < 45) { $m_24_40_44++; if($sexo == "M"){$m_24_40_44_h++;}else{$m_24_40_44_m++;}}
			if ($edad > 44 and $edad < 50) { $m_24_45_49++; if($sexo == "M"){$m_24_45_49_h++;}else{$m_24_45_49_m++;}}
			if ($edad > 49 and $edad < 55) { $m_24_50_54++; if($sexo == "M"){$m_24_50_54_h++;}else{$m_24_50_54_m++;}}
			if ($edad > 54 and $edad < 60) { $m_24_55_59++; if($sexo == "M"){$m_24_55_59_h++;}else{$m_24_55_59_m++;}}
			if ($edad > 59 and $edad < 65) { $m_24_60_64++; if($sexo == "M"){$m_24_60_64_h++;}else{$m_24_60_64_m++;}}
			if ($edad > 64 and $edad < 70) { $m_24_65_69++; if($sexo == "M"){$m_24_65_69_h++;}else{$m_24_65_69_m++;}}
			if ($edad > 69 and $edad < 75) { $m_24_70_74++; if($sexo == "M"){$m_24_70_74_h++;}else{$m_24_70_74_m++;}}
			if ($edad > 74 and $edad < 80) { $m_24_75_79++; if($sexo == "M"){$m_24_75_79_h++;}else{$m_24_75_79_m++;}}
			if ($edad > 79) { $m_24_80_m++; if($sexo == "M"){$m_24_80_m_h++;}else{$m_24_80_m_m++;}}
    		if ($conveniopago == '1' || $conveniopago == '6' || $conveniopago == '12'){$m24convenio++;}
		}elseif($diferencia >= '24:00:00'){
			if ($edad < 5) { $ma_24_0_4++; if($sexo == "M"){$ma_24_0_4_h++;}else{$ma_24_0_4_m++;}}
			if ($edad > 4 and $edad < 10) { $ma_24_5_9++; if($sexo == "M"){$ma_24_5_9_h++;}else{$ma_24_5_9_m++;}}
			if ($edad > 9 and $edad < 15) { $ma_24_10_14++; if($sexo == "M"){$ma_24_10_14_h++;}else{$ma_24_10_14_m++;}}
			if ($edad > 14 and $edad < 20) { $ma_24_15_19++; if($sexo == "M"){$ma_24_15_19_h++;}else{$ma_24_15_19_m++;}}
			if ($edad > 19 and $edad < 25) { $ma_24_20_24++; if($sexo == "M"){$ma_24_20_24_h++;}else{$ma_24_20_24_m++;}}
			if ($edad > 24 and $edad < 30) { $ma_24_25_29++; if($sexo == "M"){$ma_24_25_29_h++;}else{$ma_24_25_29_m++;}}
			if ($edad > 29 and $edad < 35) { $ma_24_30_34++; if($sexo == "M"){$ma_24_30_34_h++;}else{$ma_24_30_34_m++;}}
			if ($edad > 34 and $edad < 40) { $ma_24_35_39++; if($sexo == "M"){$ma_24_35_39_h++;}else{$ma_24_35_39_m++;}}
			if ($edad > 39 and $edad < 45) { $ma_24_40_44++; if($sexo == "M"){$ma_24_40_44_h++;}else{$ma_24_40_44_m++;}}
			if ($edad > 44 and $edad < 50) { $ma_24_45_49++; if($sexo == "M"){$ma_24_45_49_h++;}else{$ma_24_45_49_m++;}}
			if ($edad > 49 and $edad < 55) { $ma_24_50_54++; if($sexo == "M"){$ma_24_50_54_h++;}else{$ma_24_50_54_m++;}}
			if ($edad > 54 and $edad < 60) { $ma_24_55_59++; if($sexo == "M"){$ma_24_55_59_h++;}else{$ma_24_55_59_m++;}}
			if ($edad > 59 and $edad < 65) { $ma_24_60_64++; if($sexo == "M"){$ma_24_60_64_h++;}else{$ma_24_60_64_m++;}}
			if ($edad > 64 and $edad < 70) { $ma_24_65_69++; if($sexo == "M"){$ma_24_65_69_h++;}else{$ma_24_65_69_m++;}}
			if ($edad > 69 and $edad < 75) { $ma_24_70_74++; if($sexo == "M"){$ma_24_70_74_h++;}else{$ma_24_70_74_m++;}}
			if ($edad > 74 and $edad < 80) { $ma_24_75_79++; if($sexo == "M"){$ma_24_75_79_h++;}else{$ma_24_75_79_m++;}}
			if ($edad > 79) { $ma_24_80_m++; if($sexo == "M"){$ma_24_80_m_h++;}else{$ma_24_80_m_m++;}}
	    	if ($conveniopago == '1' || $conveniopago == '6' || $conveniopago == '12'){$ma24convenio++;}
		}else{
    		$acumotros++;
  		}
	}
	$t_h    = $m_12_0_4_h+$m_12_5_9_h+$m_12_10_14_h+$m_12_15_19_h+$m_12_20_24_h+$m_12_25_29_h+$m_12_30_34_h+$m_12_35_39_h+$m_12_40_44_h+$m_12_45_49_h+$m_12_50_54_h+$m_12_55_59_h+$m_12_60_64_h+$m_12_65_69_h+$m_12_70_74_h+$m_12_75_79_h+$m_12_80_m_h;  	    		
	$t_m    = $m_12_0_4_m+$m_12_5_9_m+$m_12_10_14_m+$m_12_15_19_m+$m_12_20_24_m+$m_12_25_29_m+$m_12_30_34_m+$m_12_35_39_m+$m_12_40_44_m+$m_12_45_49_m+$m_12_50_54_m+$m_12_55_59_m+$m_12_60_64_m+$m_12_65_69_m+$m_12_70_74_m+$m_12_75_79_m+$m_12_80_m_m;
	$t_t    = $t_h + $t_m;

	$t_h24  = $m_24_0_4_h+$m_24_5_9_h+$m_24_10_14_h+$m_24_15_19_h+$m_24_20_24_h+$m_24_25_29_h+$m_24_30_34_h+$m_24_35_39_h+$m_24_40_44_h+$m_24_45_49_h+$m_24_50_54_h+$m_24_55_59_h+$m_24_60_64_h+$m_24_65_69_h+$m_24_70_74_h+$m_24_75_79_h+$m_24_80_m_h;            
	$t_m24  = $m_24_0_4_m+$m_24_5_9_m+$m_24_10_14_m+$m_24_15_19_m+$m_24_20_24_m+$m_24_25_29_m+$m_24_30_34_m+$m_24_35_39_m+$m_24_40_44_m+$m_24_45_49_m+$m_24_50_54_m+$m_24_55_59_m+$m_24_60_64_m+$m_24_65_69_m+$m_24_70_74_m+$m_24_75_79_m+$m_24_80_m_m;
	$t_t24  = $t_h24 + $t_m24;

	$t_hm24  = $ma_24_0_4_h+$ma_24_5_9_h+$ma_24_10_14_h+$ma_24_15_19_h+$ma_24_20_24_h+$ma_24_25_29_h+$ma_24_30_34_h+$ma_24_35_39_h+$ma_24_40_44_h+$ma_24_45_49_h+$ma_24_50_54_h+$ma_24_55_59_h+$ma_24_60_64_h+$ma_24_65_69_h+$ma_24_70_74_h+$ma_24_75_79_h+$ma_24_80_m_h;            
	$t_mm24  = $ma_24_0_4_m+$ma_24_5_9_m+$ma_24_10_14_m+$ma_24_15_19_m+$ma_24_20_24_m+$ma_24_25_29_m+$ma_24_30_34_m+$ma_24_35_39_m+$ma_24_40_44_m+$ma_24_45_49_m+$ma_24_50_54_m+$ma_24_55_59_m+$ma_24_60_64_m+$ma_24_65_69_m+$ma_24_70_74_m+$ma_24_75_79_m+$ma_24_80_m_m;
	$t_tmm24 = $t_hm24 + $t_mm24;

	$html = '
	<table width="1300" border="0" align="center" cellpadding="0" cellspacing="0">
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
				<td width="71%">
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
  	<tr>
    	<td colspan="3" align="center">&nbsp;</td>
  	</tr>
  	<tr>
    	<td colspan="3" align="center" class="foliochico"><strong class="titulotabla">REM A-08 Período: '.$_POST['fechaInicio'].' AL '.$_POST['fechaFin'].' </strong></td>
  	</tr>
  	<tr>
    	<td colspan="3" class="foliochico">&nbsp;</td>
  	</tr>
  	<tr>
    	<td colspan="3" class="foliochico">&nbsp;</td>
  	</tr>
    <tr>
       	<td colspan="3" align="left" class="foliochico"><strong class="titulotabla">SECCIÓN D: PACIENTES CON INDICACION DE HOSPITALIZACION EN ESPERA DE CAMAS EN UEH</strong></td>
    </tr>
  	<tr>
		<td colspan="3" align="center"><table width="900" border="1" cellpadding="2" cellspacing="0" class="reporte" name="tablaexport" id="tablaexport">
    		
    	  	<tr align="center" valign="top">
                <td width="70" valign="bottom" bgcolor="#CCCCCC" rowspan="3"><strong>TIPO DE Paciente</strong></td>
                <td width="50" valign="bottom" bgcolor="#CCCCCC" rowspan="3"><strong>TOTAL</strong></td>
                <td width="700" valign="bottom" bgcolor="#CCCCCC" colspan="37"><strong>GRUPOS DE EDAD (en años)</strong></td>
                <td width="90" valign="bottom" bgcolor="#CCCCCC" rowspan="3"><strong>A BENEFICIARIOS</strong></td>
  	    	</tr>
    	  	<tr align="center" valign="top">
                <td width="35" valign="bottom" bgcolor="#CCCCCC" rowspan="2"><strong>Ambos Sexos</strong></td>
                <td width="35" valign="bottom" bgcolor="#CCCCCC" rowspan="2"><strong>Hombres</strong></td>
                <td width="35" valign="bottom" bgcolor="#CCCCCC" rowspan="2"><strong>Mujeres</strong></td>
                <td width="35" valign="bottom" bgcolor="#CCCCCC" colspan="2"><strong>00 04</strong></td>
                <td width="35"  valign="bottom" bgcolor="#CCCCCC" colspan="2"><strong>05 09</strong></td>
                <td width="35" valign="bottom" bgcolor="#CCCCCC" colspan="2"><strong>35 14</strong></td>
                <td width="35" valign="bottom" bgcolor="#CCCCCC" colspan="2"><strong>15 19</strong></td>
                <td width="35" valign="bottom" bgcolor="#CCCCCC" colspan="2"><strong>35 24</strong></td>
                <td width="35" valign="bottom" bgcolor="#CCCCCC" colspan="2"><strong>25 29</strong></td>
                <td width="35" valign="bottom" bgcolor="#CCCCCC" colspan="2"><strong>30 34</strong></td>
                <td width="35" valign="bottom" bgcolor="#CCCCCC" colspan="2"><strong>35 39</strong></td>
                <td width="35" valign="bottom" bgcolor="#CCCCCC" colspan="2"><strong>40 44</strong></td>
                <td width="35" valign="bottom" bgcolor="#CCCCCC" colspan="2"><strong>45 49</strong></td>
                <td width="35" valign="bottom" bgcolor="#CCCCCC" colspan="2"><strong>35 54</strong></td>
                <td width="35" valign="bottom" bgcolor="#CCCCCC" colspan="2"><strong>55 59</strong></td>
                <td width="35" valign="bottom" bgcolor="#CCCCCC" colspan="2"><strong>60 64</strong></td>
                <td width="35" valign="bottom" bgcolor="#CCCCCC" colspan="2"><strong>65 69</strong></td>
                <td width="35" valign="bottom" bgcolor="#CCCCCC" colspan="2"><strong>70 74</strong></td>
                <td width="35" valign="bottom" bgcolor="#CCCCCC" colspan="2"><strong>75 79</strong></td>
                <td width="35" valign="bottom" bgcolor="#CCCCCC" colspan="2"><strong>80 y Más</strong></td>
  	    	</tr>
  	    	<tr>
  	    		<td valign="bottom" bgcolor="#CCCCCC"><strong>H</strong></td>
  	    		<td valign="bottom" bgcolor="#CCCCCC"><strong>M</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>H</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>M</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>H</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>M</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>H</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>M</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>H</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>M</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>H</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>M</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>H</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>M</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>H</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>M</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>H</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>M</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>H</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>M</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>H</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>M</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>H</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>M</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>H</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>M</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>H</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>M</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>H</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>M</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>H</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>M</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>H</strong></td>
                <td valign="bottom" bgcolor="#CCCCCC"><strong>M</strong></td>
  	    	</tr>
    	  	<tr align="center" valign="top">
 				<td align="left" valign="bottom" bgcolor="#CCCCCC" rowspan="3"><strong>PACIENTES QUE INGRESAN A CAMA HOSPITALARIA SEGUN TIEMPO DE DEMORA AL INGRESO</strong></td>
 				<td align="left" valign="bottom" bgcolor="#CCCCCC"> <strong>MENOS DE 12 HORAS</strong></td>
    	    	<td align="right">'.$t_t.'</td>
            	<td align="right">'.$t_h.'</td>
            	<td align="right">'.$t_m.'</td>

            	<td align="right">'.$m_12_0_4_h.'</td>
  	    		<td align="right">'.$m_12_0_4_m.'</td>
    			<td align="right">'.$m_12_5_9_h.'</td>
    			<td align="right">'.$m_12_5_9_m.'</td>
    			<td align="right">'.$m_12_10_14_h.'</td>
    			<td align="right">'.$m_12_10_14_m.'</td>
    			<td align="right">'.$m_12_15_19_h.'</td>
            	<td align="right">'.$m_12_15_19_m.'</td>  	    		
    			<td align="right">'.$m_12_20_24_h.'</td>
    			<td align="right">'.$m_12_20_24_m.'</td>
    			<td align="right">'.$m_12_25_29_h.'</td>
    			<td align="right">'.$m_12_25_29_m.'</td>
    			<td align="right">'.$m_12_30_34_h.'</td>
    			<td align="right">'.$m_12_30_34_m.'</td>
    			<td align="right">'.$m_12_35_39_h.'</td>
    			<td align="right">'.$m_12_35_39_m.'</td>
    			<td align="right">'.$m_12_40_44_h.'</td>
    			<td align="right">'.$m_12_40_44_m.'</td>
    			<td align="right">'.$m_12_45_49_h.'</td>
    			<td align="right">'.$m_12_45_49_m.'</td>
    			<td align="right">'.$m_12_50_54_h.'</td>
    			<td align="right">'.$m_12_50_54_m.'</td>
    			<td align="right">'.$m_12_55_59_h.'</td>
    			<td align="right">'.$m_12_55_59_m.'</td>
    			<td align="right">'.$m_12_60_64_h.'</td>
    			<td align="right">'.$m_12_60_64_m.'</td>
    			<td align="right">'.$m_12_65_69_h.'</td>
    			<td align="right">'.$m_12_65_69_m.'</td>
    			<td align="right">'.$m_12_70_74_h.'</td>
    			<td align="right">'.$m_12_70_74_m.'</td>
    			<td align="right">'.$m_12_75_79_h.'</td>
    			<td align="right">'.$m_12_75_79_m.'</td>
    			<td align="right">'.$m_12_80_m_h.'</td>
    			<td align="right">'.$m_12_80_m_m.'</td>
    			<td align="right">'.$m12convenio.'</td>            
  	    	</tr>
  	    	<tr>
  	    		<td align="left" valign="bottom" bgcolor="#CCCCCC"> <strong>12-24 HORAS</strong></td>  	    		
            	<td align="right">'.$t_t24.'</td>
            	<td align="right">'.$t_h24.'</td>
            	<td align="right">'.$t_m24.'</td>

  	    		<td align="right">'.$m_24_0_4_h.'</td>
  	    		<td align="right">'.$m_24_0_4_m.'</td>
    			<td align="right">'.$m_24_5_9_h.'</td>
    			<td align="right">'.$m_24_5_9_m.'</td>
    			<td align="right">'.$m_24_10_14_h.'</td>
    			<td align="right">'.$m_24_10_14_m.'</td>
    			<td align="right">'.$m_24_15_19_h.'</td>
    			<td align="right">'.$m_24_15_19_m.'</td>
    			<td align="right">'.$m_24_20_24_h.'</td>
    			<td align="right">'.$m_24_20_24_m.'</td>
    			<td align="right">'.$m_24_25_29_h.'</td>
    			<td align="right">'.$m_24_25_29_m.'</td>
    			<td align="right">'.$m_24_30_34_h.'</td>
    			<td align="right">'.$m_24_30_34_m.'</td>
            	<td align="right">'.$m_24_35_39_h.'</td>
    			<td align="right">'.$m_24_35_39_m.'</td>
    			<td align="right">'.$m_24_40_44_h.'</td>
    			<td align="right">'.$m_24_40_44_m.'</td>
    			<td align="right">'.$m_24_45_49_h.'</td>
    			<td align="right">'.$m_24_45_49_m.'</td>
    			<td align="right">'.$m_24_50_54_h.'</td>
    			<td align="right">'.$m_24_50_54_m.'</td>
    			<td align="right">'.$m_24_55_59_h.'</td>
    			<td align="right">'.$m_24_55_59_m.'</td>
    			<td align="right">'.$m_24_60_64_h.'</td>
    			<td align="right">'.$m_24_60_64_m.'</td>
    			<td align="right">'.$m_24_65_69_h.'</td>
    			<td align="right">'.$m_24_65_69_m.'</td>
    			<td align="right">'.$m_24_70_74_h.'</td>
    			<td align="right">'.$m_24_70_74_m.'</td>
    			<td align="right">'.$m_24_75_79_h.'</td>
    			<td align="right">'.$m_24_75_79_m.'</td>
    			<td align="right">'.$m_24_80_m_h.'</td>
    			<td align="right">'.$m_24_80_m_m.'</td>
    			<td align="right">'.$m24convenio.'</td>
  	    		
  	    	</tr>
    	  	<tr align="center" valign="top">
    	    	<td align="left" valign="bottom" bgcolor="#CCCCCC"> <strong>Mas24 HORAS</strong></td>
	            <td align="right">'.$t_tmm24.'</td>
	            <td align="right">'.$t_hm24.'</td>
	            <td align="right">'.$t_mm24.'</td>

	  	    	<td align="right">'.$ma_24_0_4_h.'</td>
	  	    	<td align="right">'.$ma_24_0_4_m.'</td>
	    		<td align="right">'.$ma_24_5_9_h.'</td>
	    		<td align="right">'.$ma_24_5_9_m.'</td>
	    		<td align="right">'.$ma_24_10_14_h.'</td>
	    		<td align="right">'.$ma_24_10_14_m.'</td>
	    		<td align="right">'.$ma_24_15_19_h.'</td>
	    		<td align="right">'.$ma_24_15_19_m.'</td>
	    		<td align="right">'.$ma_24_20_24_h.'</td>
	    		<td align="right">'.$ma_24_20_24_m.'</td>
	    		<td align="right">'.$ma_24_25_29_h.'</td>
	    		<td align="right">'.$ma_24_25_29_m.'</td>
	    		<td align="right">'.$ma_24_30_34_h.'</td>
	    		<td align="right">'.$ma_24_30_34_m.'</td>
	    		<td align="right">'.$ma_24_35_39_h.'</td>
	    		<td align="right">'.$ma_24_35_39_m.'</td>
	    		<td align="right">'.$ma_24_40_44_h.'</td>
	    		<td align="right">'.$ma_24_40_44_m.'</td>
	    		<td align="right">'.$ma_24_45_49_h.'</td>
	    		<td align="right">'.$ma_24_45_49_m.'</td>
	    		<td align="right">'.$ma_24_50_54_h.'</td>
	    		<td align="right">'.$ma_24_50_54_m.'</td>
	    		<td align="right">'.$ma_24_55_59_h.'</td>
	    		<td align="right">'.$ma_24_55_59_m.'</td>
	    		<td align="right">'.$ma_24_60_64_h.'</td>
	    		<td align="right">'.$ma_24_60_64_m.'</td>
	    		<td align="right">'.$ma_24_65_69_h.'</td>
	    		<td align="right">'.$ma_24_65_69_m.'</td>
	    		<td align="right">'.$ma_24_70_74_h.'</td>
	    		<td align="right">'.$ma_24_70_74_m.'</td>
	    		<td align="right">'.$ma_24_75_79_h.'</td>
	    		<td align="right">'.$ma_24_75_79_m.'</td>
	    		<td align="right">'.$ma_24_80_m_h.'</td>
	    		<td align="right">'.$ma_24_80_m_m.'</td>
	            <td align="right">'.$ma24convenio.'</td>
  	    		
  	    	</tr>

  	  	</table></td>
	</tr>
    <table border="0">
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
</table></td>';

	$pdf->writeHTML($html, true, false, true, false, '');
	// $pdf->Output('reporteREMA08.pdf','FI');
	// $url = RAIZ."/views/reportes/salidas/reporteREMA08.pdf";



	$nombre_archivo = "reporteREMA08".date('Y-m-dms').".pdf";
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