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
	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, 'L','pt','Legal');
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

	require("../../../config/config.php");
	require_once('../../../class/Connection.class.php');     $objCon     = new Connection; $objCon->db_connect();
	require_once('../../../class/Util.class.php');           $objUtil    = new Util;
	require_once('../../../class/Reportes.class.php');       $reporte    = new Reportes;
	$parametros               = $objUtil->getFormulario($_POST);
	$parametros['frm_inicio'] = $objUtil->fechaInvertida($parametros['fechaInicio']);
	$parametros['frm_fin']    = $objUtil->fechaInvertida($parametros['fechaFin']);
	$fechaHoy                 = $objUtil->getFechaPalabra(date('Y-m-d'));
	$rem                      = $reporte->rem08($objCon,$parametros);
	$rem_mordedura 			  = $reporte->rem08mordedura($objCon,$parametros);



	$at_an_10_14_h   = 0;
$at_an_10_14_m   = 0;

	$at_an_0_4 = 0;
$at_an_0_4_h = 0;
$at_an_0_4_m = 0;
$at_an_5_9 = 0;
$at_an_5_9_h = 0;
$at_an_5_9_m = 0;
$at_an_10_14= 0;
$at_an_10_1_h = 0;
$at_an_10_1_m = 0;
$at_an_15_19 = 0;
$at_an_15_19_h = 0;
$at_an_15_19_m = 0;
$at_an_20_24 = 0;
$at_an_20_24_h = 0;
$at_an_20_24_m = 0;
$at_an_25_29 = 0;
$at_an_25_29_h = 0;
$at_an_25_29_m = 0;
$at_an_30_34 = 0;
$at_an_30_34_h = 0;
$at_an_30_34_m = 0;
$at_an_35_39 = 0;
$at_an_35_39_h = 0;
$at_an_35_39_m = 0;
$at_an_40_44 = 0;
$at_an_40_44_h = 0;
$at_an_40_44_m = 0;
$at_an_45_49 = 0;
$at_an_45_49_h = 0;
$at_an_45_49_m = 0;
$at_an_50_54 = 0;
$at_an_50_54_h = 0;
$at_an_50_54_m = 0;
$at_an_55_59 = 0;
$at_an_55_59_h = 0;
$at_an_55_59_m = 0;
$at_an_60_64 = 0;
$at_an_60_64_h = 0;
$at_an_60_64_m = 0;
$at_an_65_69 = 0;
$at_an_65_69_h = 0;
$at_an_65_69_m = 0;
$at_an_70_74 = 0;
$at_an_70_74_h = 0;
$at_an_70_74_m = 0;
$at_an_75_79 = 0;
$at_an_75_79_h = 0;
$at_an_75_79_m = 0;
$at_an_80_m = 0;
$at_an_80_m_h = 0;
$at_an_80_m_m = 0;

$at_gi_0_4 = 0;
$at_gi_0_4_h = 0;
$at_gi_0_4_m = 0;
$at_gi_5_9 = 0;
$at_gi_5_9_h = 0;
$at_gi_5_9_m = 0;
$at_gi_10_14= 0;
$at_gi_10_14_h = 0;
$at_gi_10_14_m = 0;
$at_gi_15_19 = 0;
$at_gi_15_19_h = 0;
$at_gi_15_19_m = 0;
$at_gi_20_24 = 0;
$at_gi_20_24_h = 0;
$at_gi_20_24_m = 0;
$at_gi_25_29 = 0;
$at_gi_25_29_h = 0;
$at_gi_25_29_m = 0;
$at_gi_30_34 = 0;
$at_gi_30_34_h = 0;
$at_gi_30_34_m = 0;
$at_gi_35_39 = 0;
$at_gi_35_39_h = 0;
$at_gi_35_39_m = 0;
$at_gi_40_44 = 0;
$at_gi_40_44_h = 0;
$at_gi_40_44_m = 0;
$at_gi_45_49 = 0;
$at_gi_45_49_h = 0;
$at_gi_45_49_m = 0;
$at_gi_50_54 = 0;
$at_gi_50_54_h = 0;
$at_gi_50_54_m = 0;
$at_gi_55_59 = 0;
$at_gi_55_59_h = 0;
$at_gi_55_59_m = 0;
$at_gi_60_64 = 0;
$at_gi_60_64_h = 0;
$at_gi_60_64_m = 0;
$at_gi_65_69 = 0;
$at_gi_65_69_h = 0;
$at_gi_65_69_m = 0;
$at_gi_70_74 = 0;
$at_gi_70_74_h = 0;
$at_gi_70_74_m = 0;
$at_gi_75_79 = 0;
$at_gi_75_79_h = 0;
$at_gi_75_79_m = 0;
$at_gi_80_m = 0;
$at_gi_80_m_h = 0;
$at_gi_80_m_m = 0;

$at_ma_0_4 = 0;
$at_ma_0_4_h = 0;
$at_ma_0_4_m = 0;
$at_ma_5_9 = 0;
$at_ma_5_9_h = 0;
$at_ma_5_9_m = 0;
$at_ma_10_14= 0;
$at_ma_10_14_h = 0;
$at_ma_10_14_m = 0;
$at_ma_15_19 = 0;
$at_ma_15_19_h = 0;
$at_ma_15_19_m = 0;
$at_ma_20_24 = 0;
$at_ma_20_24_h = 0;
$at_ma_20_24_m = 0;
$at_ma_25_29 = 0;
$at_ma_25_29_h = 0;
$at_ma_25_29_m = 0;
$at_ma_30_34 = 0;
$at_ma_30_34_h = 0;
$at_ma_30_34_m = 0;
$at_ma_35_39 = 0;
$at_ma_35_39_h = 0;
$at_ma_35_39_m = 0;
$at_ma_40_44 = 0;
$at_ma_40_44_h = 0;
$at_ma_40_44_m = 0;
$at_ma_45_49 = 0;
$at_ma_45_49_h = 0;
$at_ma_45_49_m = 0;
$at_ma_50_54 = 0;
$at_ma_50_54_h = 0;
$at_ma_50_54_m = 0;
$at_ma_55_59 = 0;
$at_ma_55_59_h = 0;
$at_ma_55_59_m = 0;
$at_ma_60_64 = 0;
$at_ma_60_64_h = 0;
$at_ma_60_64_m = 0;
$at_ma_65_69 = 0;
$at_ma_65_69_h = 0;
$at_ma_65_69_m = 0;
$at_ma_70_74 = 0;
$at_ma_70_74_h = 0;
$at_ma_70_74_m = 0;
$at_ma_75_79 = 0;
$at_ma_75_79_h = 0;
$at_ma_75_79_m = 0;
$at_ma_80_m = 0;
$at_ma_80_m_h = 0;
$at_ma_80_m_m = 0;

$cat_c1_0_4 = 0;
$cat_c1_0_4_h = 0;
$cat_c1_0_4_m = 0;
$cat_c1_5_9 = 0;
$cat_c1_5_9_h = 0;
$cat_c1_5_9_m = 0;
$cat_c1_10_14= 0;
$cat_c1_10_14_h = 0;
$cat_c1_10_14_m = 0;
$cat_c1_15_19 = 0;
$cat_c1_15_19_h = 0;
$cat_c1_15_19_m = 0;
$cat_c1_20_24 = 0;
$cat_c1_20_24_h = 0;
$cat_c1_20_24_m = 0;
$cat_c1_25_29 = 0;
$cat_c1_25_29_h = 0;
$cat_c1_25_29_m = 0;
$cat_c1_30_34 = 0;
$cat_c1_30_34_h = 0;
$cat_c1_30_34_m = 0;
$cat_c1_35_39 = 0;
$cat_c1_35_39_h = 0;
$cat_c1_35_39_m = 0;
$cat_c1_40_44 = 0;
$cat_c1_40_44_h = 0;
$cat_c1_40_44_m = 0;
$cat_c1_45_49 = 0;
$cat_c1_45_49_h = 0;
$cat_c1_45_49_m = 0;
$cat_c1_50_54 = 0;
$cat_c1_50_54_h = 0;
$cat_c1_50_54_m = 0;
$cat_c1_55_59 = 0;
$cat_c1_55_59_h = 0;
$cat_c1_55_59_m = 0;
$cat_c1_60_64 = 0;
$cat_c1_60_64_h = 0;
$cat_c1_60_64_m = 0;
$cat_c1_65_69 = 0;
$cat_c1_65_69_h = 0;
$cat_c1_65_69_m = 0;
$cat_c1_70_74 = 0;
$cat_c1_70_74_h = 0;
$cat_c1_70_74_m = 0;
$cat_c1_75_79 = 0;
$cat_c1_75_79_h = 0;
$cat_c1_75_79_m = 0;
$cat_c1_80_m = 0;
$cat_c1_80_m_h = 0;
$cat_c1_80_m_m = 0;

$cat_c2_0_4 = 0;
$cat_c2_0_4_h = 0;
$cat_c2_0_4_m = 0;
$cat_c2_5_9 = 0;
$cat_c2_5_9_h = 0;
$cat_c2_5_9_m = 0;
$cat_c2_10_14= 0;
$cat_c2_10_14_h = 0;
$cat_c2_10_14_m = 0;
$cat_c2_15_19 = 0;
$cat_c2_15_19_h = 0;
$cat_c2_15_19_m = 0;
$cat_c2_20_24 = 0;
$cat_c2_20_24_h = 0;
$cat_c2_20_24_m = 0;
$cat_c2_25_29 = 0;
$cat_c2_25_29_h = 0;
$cat_c2_25_29_m = 0;
$cat_c2_30_34 = 0;
$cat_c2_30_34_h = 0;
$cat_c2_30_34_m = 0;
$cat_c2_35_39 = 0;
$cat_c2_35_39_h = 0;
$cat_c2_35_39_m = 0;
$cat_c2_40_44 = 0;
$cat_c2_40_44_h = 0;
$cat_c2_40_44_m = 0;
$cat_c2_45_49 = 0;
$cat_c2_45_49_h = 0;
$cat_c2_45_49_m = 0;
$cat_c2_50_54 = 0;
$cat_c2_50_54_h = 0;
$cat_c2_50_54_m = 0;
$cat_c2_55_59 = 0;
$cat_c2_55_59_h = 0;
$cat_c2_55_59_m = 0;
$cat_c2_60_64 = 0;
$cat_c2_60_64_h = 0;
$cat_c2_60_64_m = 0;
$cat_c2_65_69 = 0;
$cat_c2_65_69_h = 0;
$cat_c2_65_69_m = 0;
$cat_c2_70_74 = 0;
$cat_c2_70_74_h = 0;
$cat_c2_70_74_m = 0;
$cat_c2_75_79 = 0;
$cat_c2_75_79_h = 0;
$cat_c2_75_79_m = 0;
$cat_c2_80_m = 0;
$cat_c2_80_m_h = 0;
$cat_c2_80_m_m = 0;

$cat_c3_0_4 = 0;
$cat_c3_0_4_h = 0;
$cat_c3_0_4_m = 0;
$cat_c3_5_9 = 0;
$cat_c3_5_9_h = 0;
$cat_c3_5_9_m = 0;
$cat_c3_10_14= 0;
$cat_c3_10_14_h = 0;
$cat_c3_10_14_m = 0;
$cat_c3_15_19 = 0;
$cat_c3_15_19_h = 0;
$cat_c3_15_19_m = 0;
$cat_c3_20_24 = 0;
$cat_c3_20_24_h = 0;
$cat_c3_20_24_m = 0;
$cat_c3_25_29 = 0;
$cat_c3_25_29_h = 0;
$cat_c3_25_29_m = 0;
$cat_c3_30_34 = 0;
$cat_c3_30_34_h = 0;
$cat_c3_30_34_m = 0;
$cat_c3_35_39 = 0;
$cat_c3_35_39_h = 0;
$cat_c3_35_39_m = 0;
$cat_c3_40_44 = 0;
$cat_c3_40_44_h = 0;
$cat_c3_40_44_m = 0;
$cat_c3_45_49 = 0;
$cat_c3_45_49_h = 0;
$cat_c3_45_49_m = 0;
$cat_c3_50_54 = 0;
$cat_c3_50_54_h = 0;
$cat_c3_50_54_m = 0;
$cat_c3_55_59 = 0;
$cat_c3_55_59_h = 0;
$cat_c3_55_59_m = 0;
$cat_c3_60_64 = 0;
$cat_c3_60_64_h = 0;
$cat_c3_60_64_m = 0;
$cat_c3_65_69 = 0;
$cat_c3_65_69_h = 0;
$cat_c3_65_69_m = 0;
$cat_c3_70_74 = 0;
$cat_c3_70_74_h = 0;
$cat_c3_70_74_m = 0;
$cat_c3_75_79 = 0;
$cat_c3_75_79_h = 0;
$cat_c3_75_79_m = 0;
$cat_c3_80_m = 0;
$cat_c3_80_m_h = 0;
$cat_c3_80_m_m = 0;

$cat_c4_0_4 = 0;
$cat_c4_0_4_h = 0;
$cat_c4_0_4_m = 0;
$cat_c4_5_9 = 0;
$cat_c4_5_9_h = 0;
$cat_c4_5_9_m = 0;
$cat_c4_10_14= 0;
$cat_c4_10_14_h = 0;
$cat_c4_10_14_m = 0;
$cat_c4_15_19 = 0;
$cat_c4_15_19_h = 0;
$cat_c4_15_19_m = 0;
$cat_c4_20_24 = 0;
$cat_c4_20_24_h = 0;
$cat_c4_20_24_m = 0;
$cat_c4_25_29 = 0;
$cat_c4_25_29_h = 0;
$cat_c4_25_29_m = 0;
$cat_c4_30_34 = 0;
$cat_c4_30_34_h = 0;
$cat_c4_30_34_m = 0;
$cat_c4_35_39 = 0;
$cat_c4_35_39_h = 0;
$cat_c4_35_39_m = 0;
$cat_c4_40_44 = 0;
$cat_c4_40_44_h = 0;
$cat_c4_40_44_m = 0;
$cat_c4_45_49 = 0;
$cat_c4_45_49_h = 0;
$cat_c4_45_49_m = 0;
$cat_c4_50_54 = 0;
$cat_c4_50_54_h = 0;
$cat_c4_50_54_m = 0;
$cat_c4_55_59 = 0;
$cat_c4_55_59_h = 0;
$cat_c4_55_59_m = 0;
$cat_c4_60_64 = 0;
$cat_c4_60_64_h = 0;
$cat_c4_60_64_m = 0;
$cat_c4_65_69 = 0;
$cat_c4_65_69_h = 0;
$cat_c4_65_69_m = 0;
$cat_c4_70_74 = 0;
$cat_c4_70_74_h = 0;
$cat_c4_70_74_m = 0;
$cat_c4_75_79 = 0;
$cat_c4_75_79_h = 0;
$cat_c4_75_79_m = 0;
$cat_c4_80_m = 0;
$cat_c4_80_m_h = 0;
$cat_c4_80_m_m = 0;

$cat_c5_0_4 = 0;
$cat_c5_0_4_h = 0;
$cat_c5_0_4_m = 0;
$cat_c5_5_9 = 0;
$cat_c5_5_9_h = 0;
$cat_c5_5_9_m = 0;
$cat_c5_10_14= 0;
$cat_c5_10_14_h = 0;
$cat_c5_10_14_m = 0;
$cat_c5_15_19 = 0;
$cat_c5_15_19_h = 0;
$cat_c5_15_19_m = 0;
$cat_c5_20_24 = 0;
$cat_c5_20_24_h = 0;
$cat_c5_20_24_m = 0;
$cat_c5_25_29 = 0;
$cat_c5_25_29_h = 0;
$cat_c5_25_29_m = 0;
$cat_c5_30_34 = 0;
$cat_c5_30_34_h = 0;
$cat_c5_30_34_m = 0;
$cat_c5_35_39 = 0;
$cat_c5_35_39_h = 0;
$cat_c5_35_39_m = 0;
$cat_c5_40_44 = 0;
$cat_c5_40_44_h = 0;
$cat_c5_40_44_m = 0;
$cat_c5_45_49 = 0;
$cat_c5_45_49_h = 0;
$cat_c5_45_49_m = 0;
$cat_c5_50_54 = 0;
$cat_c5_50_54_h = 0;
$cat_c5_50_54_m = 0;
$cat_c5_55_59 = 0;
$cat_c5_55_59_h = 0;
$cat_c5_55_59_m = 0;
$cat_c5_60_64 = 0;
$cat_c5_60_64_h = 0;
$cat_c5_60_64_m = 0;
$cat_c5_65_69 = 0;
$cat_c5_65_69_h = 0;
$cat_c5_65_69_m = 0;
$cat_c5_70_74 = 0;
$cat_c5_70_74_h = 0;
$cat_c5_70_74_m = 0;
$cat_c5_75_79 = 0;
$cat_c5_75_79_h = 0;
$cat_c5_75_79_m = 0;
$cat_c5_80_m = 0;
$cat_c5_80_m_h = 0;
$cat_c5_80_m_m = 0;

$cat_csi_0_4 = 0;
$cat_csi_0_4_h = 0;
$cat_csi_0_4_m = 0;
$cat_csi_5_9 = 0;
$cat_csi_5_9_h = 0;
$cat_csi_5_9_m = 0;
$cat_csi_10_14= 0;
$cat_csi_10_14_h = 0;
$cat_csi_10_14_m = 0;
$cat_csi_15_19 = 0;
$cat_csi_15_19_h = 0;
$cat_csi_15_19_m = 0;
$cat_csi_20_24 = 0;
$cat_csi_20_24_h = 0;
$cat_csi_20_24_m = 0;
$cat_csi_25_29 = 0;
$cat_csi_25_29_h = 0;
$cat_csi_25_29_m = 0;
$cat_csi_30_34 = 0;
$cat_csi_30_34_h = 0;
$cat_csi_30_34_m = 0;
$cat_csi_35_39 = 0;
$cat_csi_35_39_h = 0;
$cat_csi_35_39_m = 0;
$cat_csi_40_44 = 0;
$cat_csi_40_44_h = 0;
$cat_csi_40_44_m = 0;
$cat_csi_45_49 = 0;
$cat_csi_45_49_h = 0;
$cat_csi_45_49_m = 0;
$cat_csi_50_54 = 0;
$cat_csi_50_54_h = 0;
$cat_csi_50_54_m = 0;
$cat_csi_55_59 = 0;
$cat_csi_55_59_h = 0;
$cat_csi_55_59_m = 0;
$cat_csi_60_64 = 0;
$cat_csi_60_64_h = 0;
$cat_csi_60_64_m = 0;
$cat_csi_65_69 = 0;
$cat_csi_65_69_h = 0;
$cat_csi_65_69_m = 0;
$cat_csi_70_74 = 0;
$cat_csi_70_74_h = 0;
$cat_csi_70_74_m = 0;
$cat_csi_75_79 = 0;
$cat_csi_75_79_h = 0;
$cat_csi_75_79_m = 0;
$cat_csi_80_m = 0;
$cat_csi_80_m_h = 0;
$cat_csi_80_m_m = 0;

$cat_sc_0_4 = 0;
$cat_sc_0_4_h = 0;
$cat_sc_0_4_m = 0;
$cat_sc_5_9 = 0;
$cat_sc_5_9_h = 0;
$cat_sc_5_9_m = 0;
$cat_sc_10_14= 0;
$cat_sc_10_14_h = 0;
$cat_sc_10_14_m = 0;
$cat_sc_15_19 = 0;
$cat_sc_15_19_h = 0;
$cat_sc_15_19_m = 0;
$cat_sc_20_24 = 0;
$cat_sc_20_24_h = 0;
$cat_sc_20_24_m = 0;
$cat_sc_25_29 = 0;
$cat_sc_25_29_h = 0;
$cat_sc_25_29_m = 0;
$cat_sc_30_34 = 0;
$cat_sc_30_34_h = 0;
$cat_sc_30_34_m = 0;
$cat_sc_35_39 = 0;
$cat_sc_35_39_h = 0;
$cat_sc_35_39_m = 0;
$cat_sc_40_44 = 0;
$cat_sc_40_44_h = 0;
$cat_sc_40_44_m = 0;
$cat_sc_45_49 = 0;
$cat_sc_45_49_h = 0;
$cat_sc_45_49_m = 0;
$cat_sc_50_54 = 0;
$cat_sc_50_54_h = 0;
$cat_sc_50_54_m = 0;
$cat_sc_55_59 = 0;
$cat_sc_55_59_h = 0;
$cat_sc_55_59_m = 0;
$cat_sc_60_64 = 0;
$cat_sc_60_64_h = 0;
$cat_sc_60_64_m = 0;
$cat_sc_65_69 = 0;
$cat_sc_65_69_h = 0;
$cat_sc_65_69_m = 0;
$cat_sc_70_74 = 0;
$cat_sc_70_74_h = 0;
$cat_sc_70_74_m = 0;
$cat_sc_75_79 = 0;
$cat_sc_75_79_h = 0;
$cat_sc_75_79_m = 0;
$cat_sc_80_m = 0;
$cat_sc_80_m_h = 0;
$cat_sc_80_m_m = 0;

$at_an_h = 0;
$at_an_m = 0;
$at_an_ben = 0;
$at_an_sapu = 0;
$at_an_bc = 0;
$at_an_otr = 0;

$at_gi_h = 0;
$at_gi_m = 0;
$at_gi_ben = 0;
$at_gi_sapu = 0;
$at_gi_bc = 0;
$at_gi_otr = 0;

$total_ma = 0;
$at_ma_h = 0;
$at_ma_m = 0;
$at_ma_ben = 0;
$at_ma_sapu = 0;
$at_ma_bc = 0;
$at_ma_otr = 0;

$medi_0_15 = 0;
$pedi_0_15 = 0;
$trau_0_15 = 0;
$neur_0_15 = 0;
$neci_0_15 = 0;
$psiq_0_15 = 0;

$medi_15_m = 0;
$pedi_15_m = 0;
$trau_15_m = 0;
$neur_15_m = 0;
$neci_15_m = 0;
$psiq_15_m = 0;

$h_urg_0_12 = 0;
$h_urg_12_24 = 0;
$h_urg_24_m = 0;
$h_rech_hosp = 0;
$h_otro_est = 0;
$h_ueh = 0;
$h_fall_espera = 0;

$total_cc_0_4 = 0;
$total_cc_0_4_h = 0;
$total_cc_0_4_m = 0;
$total_cc_5_9 = 0;
$total_cc_5_9_h = 0;
$total_cc_5_9_m = 0;
$total_cc_10_14 = 0;
$total_cc_10_14_h = 0;
$total_cc_10_14_m = 0;
$total_cc_15_19 = 0;
$total_cc_15_19_h = 0;
$total_cc_15_19_m = 0;
$total_cc_20_24 = 0;
$total_cc_20_24_h = 0;
$total_cc_20_24_m = 0;
$total_cc_25_29 = 0;
$total_cc_25_29_h = 0;
$total_cc_25_29_m = 0;
$total_cc_30_34 = 0;
$total_cc_30_34_h = 0;
$total_cc_30_34_m = 0;
$total_cc_35_39 = 0;
$total_cc_35_39_h = 0;
$total_cc_35_39_m = 0;
$total_cc_40_44 = 0;
$total_cc_40_44_h = 0;
$total_cc_40_44_m = 0;
$total_cc_45_49 = 0;
$total_cc_45_49_h = 0;
$total_cc_45_49_m = 0;
$total_cc_50_54 = 0;
$total_cc_50_54_h = 0;
$total_cc_50_54_m = 0;
$total_cc_55_59 = 0;
$total_cc_55_59_h = 0;
$total_cc_55_59_m = 0;
$total_cc_60_64 = 0;
$total_cc_60_64_h = 0;
$total_cc_60_64_m = 0;
$total_cc_65_69 = 0;
$total_cc_65_69_h = 0;
$total_cc_65_69_m = 0;
$total_cc_70_74 = 0;
$total_cc_70_74_h = 0;
$total_cc_70_74_m = 0;
$total_cc_75_79 = 0;
$total_cc_75_79_h = 0;
$total_cc_75_79_m = 0;
$total_cc_80_m = 0;
$total_cc_80_m_h = 0;
$total_cc_80_m_m = 0;

$total_con_cate = 0;

$total_cate_0_4 = 0;
$total_cate_0_4_h = 0;
$total_cate_0_4_m = 0;
$total_cate_5_9 = 0;
$total_cate_5_9_h = 0;
$total_cate_5_9_m = 0;
$total_cate_10_14 = 0;
$total_cate_10_14_h = 0;
$total_cate_10_14_m = 0;
$total_cate_15_19 = 0;
$total_cate_15_19_h = 0;
$total_cate_15_19_m = 0;
$total_cate_20_24 = 0;
$total_cate_20_24_h = 0;
$total_cate_20_24_m = 0;
$total_cate_25_29 = 0;
$total_cate_25_29_h = 0;
$total_cate_25_29_m = 0;
$total_cate_30_34 = 0;
$total_cate_30_34_h = 0;
$total_cate_30_34_m = 0;
$total_cate_35_39 = 0;
$total_cate_35_39_h = 0;
$total_cate_35_39_m = 0;
$total_cate_40_44 = 0;
$total_cate_40_44_h = 0;
$total_cate_40_44_m = 0;
$total_cate_45_49 = 0;
$total_cate_45_49_h = 0;
$total_cate_45_49_m = 0;
$total_cate_50_54 = 0;
$total_cate_50_54_h = 0;
$total_cate_50_54_m = 0;
$total_cate_55_59 = 0;
$total_cate_55_59_h = 0;
$total_cate_55_59_m = 0;
$total_cate_60_64 = 0;
$total_cate_60_64_h = 0;
$total_cate_60_64_m = 0;
$total_cate_65_69 = 0;
$total_cate_65_69_h = 0;
$total_cate_65_69_m = 0;
$total_cate_70_74 = 0;
$total_cate_70_74_h = 0;
$total_cate_70_74_m = 0;
$total_cate_75_79 = 0;
$total_cate_75_79_h = 0;
$total_cate_75_79_m = 0;
$total_cate_80_m = 0;
$total_cate_80_m_h = 0;
$total_cate_80_m_m = 0;

$total_cate = 0;

$Tperrohombre = $rem_mordedura["0"]["0a4m1"]+$rem_mordedura["0"]["5a9m1"]+$rem_mordedura["0"]["10a14m1"]+$rem_mordedura["0"]["15amm1"];
$Tperromujer = $rem_mordedura["0"]["0a4f1"]+$rem_mordedura["0"]["5a9f1"]+$rem_mordedura["0"]["10a14f1"]+$rem_mordedura["0"]["15amf1"];
$tperromix = $Tperrohombre+$Tperromujer;

$Tgatohombre = $rem_mordedura["0"]["0a4m6"]+$rem_mordedura["0"]["5a9m6"]+$rem_mordedura["0"]["10a14m6"]+$rem_mordedura["0"]["15amm6"];
$Tgatomujer = $rem_mordedura["0"]["0a4f6"]+$rem_mordedura["0"]["5a9f6"]+$rem_mordedura["0"]["10a14f6"]+$rem_mordedura["0"]["15amf6"];
$tgatomix = $Tgatohombre+$Tgatomujer;

$Tratonhombre = $rem_mordedura["0"]["0a4m2"]+$rem_mordedura["0"]["5a9m2"]+$rem_mordedura["0"]["10a14m2"]+$rem_mordedura["0"]["15amm2"];
$Tratonmujer = $rem_mordedura["0"]["0a4f2"]+$rem_mordedura["0"]["5a9f2"]+$rem_mordedura["0"]["10a14f2"]+$rem_mordedura["0"]["15amf2"];
$tratonmix = $Tratonhombre+$Tratonmujer;

$Tmurcielagohombre = $rem_mordedura["0"]["0a4m9"]+$rem_mordedura["0"]["5a9m9"]+$rem_mordedura["0"]["10a14m9"]+$rem_mordedura["0"]["15amm9"];
$Tmurcielagomujer = $rem_mordedura["0"]["0a4f9"]+$rem_mordedura["0"]["5a9f9"]+$rem_mordedura["0"]["10a14f9"]+$rem_mordedura["0"]["15amf9"];
$tmurcielagomix = $Tmurcielagohombre+$Tmurcielagomujer;

$Tasilvestrehombre = $rem_mordedura["0"]["0a4m10"]+$rem_mordedura["0"]["5a9m10"]+$rem_mordedura["0"]["10a14m10"]+$rem_mordedura["0"]["15amm10"];
$Tasilvestremujer = $rem_mordedura["0"]["0a4f10"]+$rem_mordedura["0"]["5a9f10"]+$rem_mordedura["0"]["10a14f10"]+$rem_mordedura["0"]["15amf10"];
$tasilvestremix = $Tasilvestrehombre+$Tasilvestremujer;

$totalambossexos = $tperromix+$tgatomix+$tratonmix+$tmurcielagomix+$tasilvestremix;
$totalhombres = $Tperrohombre+$Tgatohombre+$Tratonhombre+$Tmurcielagohombre+$Tasilvestrehombre;
$totalmujeres = $Tperromujer+$Tgatomujer+$Tratonmujer+$Tmurcielagomujer+$Tasilvestremujer;
$totalanimales0a4m= $rem_mordedura["0"]["0a4m1"]+$rem_mordedura["0"]["0a4m6"]+$rem_mordedura["0"]["0a4m10"]+$rem_mordedura["0"]["0a4m9"]+$rem_mordedura["0"]["0a4m2"];
$totalanimales0a4f= $rem_mordedura["0"]["0a4f1"]+$rem_mordedura["0"]["0a4f6"]+$rem_mordedura["0"]["0a4f10"]+$rem_mordedura["0"]["0a4f9"]+$rem_mordedura["0"]["0a4f2"];
$totalanimales5a9m= $rem_mordedura["0"]["5a9m1"]+$rem_mordedura["0"]["5a9m6"]+$rem_mordedura["0"]["5a9m10"]+$rem_mordedura["0"]["5a9m9"]+$rem_mordedura["0"]["5a9m2"];
$totalanimales5a9f= $rem_mordedura["0"]["5a9f1"]+$rem_mordedura["0"]["5a9f6"]+$rem_mordedura["0"]["5a9f10"]+$rem_mordedura["0"]["5a9f9"]+$rem_mordedura["0"]["5a9f2"];
$totalanimales10a14m= $rem_mordedura["0"]["10a14m1"]+$rem_mordedura["0"]["10a14m6"]+$rem_mordedura["0"]["10a14m10"]+$rem_mordedura["0"]["10a14m9"]+$rem_mordedura["0"]["10a14m2"];
$totalanimales10a14f= $rem_mordedura["0"]["10a14f1"]+$rem_mordedura["0"]["10a14f6"]+$rem_mordedura["0"]["10a14f10"]+$rem_mordedura["0"]["10a14f9"]+$rem_mordedura["0"]["10a14f2"];
$totalanimales15amm= $rem_mordedura["0"]["15amm1"]+$rem_mordedura["0"]["15amm6"]+$rem_mordedura["0"]["15amm10"]+$rem_mordedura["0"]["15amm9"]+$rem_mordedura["0"]["15amm2"];
$totalanimales15amf= $rem_mordedura["0"]["15amf1"]+$rem_mordedura["0"]["15amf6"]+$rem_mordedura["0"]["15amf10"]+$rem_mordedura["0"]["15amf9"]+$rem_mordedura["0"]["15amf2"];



		for($i=0; $i<count($rem); $i++){ //INICIO DEL FOR			
			$tipoatencion   = $rem[$i]['dau_atencion'];
			$edad           = $rem[$i]['dau_paciente_edad'];
			$idpaciente     = $rem[$i]['id_paciente'];
			$previcod       = $rem[$i]['dau_paciente_prevision'];
			$atendidopor    = $rem[$i]['ate_atendidopor_id'];
			$categorizacion = $rem[$i]['dau_categorizacion_actual'];
			$sexo           = $rem[$i]['sexo'];

			if($tipoatencion < 3){ //INICIO $tipoatencion < 3
				$total_an++;
				if ($edad < 5) { $at_an_0_4++;                   if($sexo == "M" ){$at_an_0_4_h++;}else{$at_an_0_4_m++;}} 
				if ($edad > 4 and $edad < 10) { $at_an_5_9++;    if($sexo == "M"){$at_an_5_9_h++;}else{$at_an_5_9_m++;}}
				if ($edad > 9 and $edad < 15) { $at_an_10_14++;  if($sexo == "M"){$at_an_10_14_h++;}else{$at_an_10_14_m++;}}
				if ($edad > 14 and $edad < 20) { $at_an_15_19++; if($sexo == "M"){$at_an_15_19_h++;}else{$at_an_15_19_m++;}}
				if ($edad > 19 and $edad < 25) { $at_an_20_24++; if($sexo == "M"){$at_an_20_24_h++;}else{$at_an_20_24_m++;}}
				if ($edad > 24 and $edad < 30) { $at_an_25_29++; if($sexo == "M"){$at_an_25_29_h++;}else{$at_an_25_29_m++;}}
				if ($edad > 29 and $edad < 35) { $at_an_30_34++; if($sexo == "M"){$at_an_30_34_h++;}else{$at_an_30_34_m++;}}
				if ($edad > 34 and $edad < 40) { $at_an_35_39++; if($sexo == "M"){$at_an_35_39_h++;}else{$at_an_35_39_m++;}}
				if ($edad > 39 and $edad < 45) { $at_an_40_44++; if($sexo == "M"){$at_an_40_44_h++;}else{$at_an_40_44_m++;}}
				if ($edad > 44 and $edad < 50) { $at_an_45_49++; if($sexo == "M"){$at_an_45_49_h++;}else{$at_an_45_49_m++;}}
				if ($edad > 49 and $edad < 55) { $at_an_50_54++; if($sexo == "M"){$at_an_50_54_h++;}else{$at_an_50_54_m++;}}
				if ($edad > 54 and $edad < 60) { $at_an_55_59++; if($sexo == "M"){$at_an_55_59_h++;}else{$at_an_55_59_m++;}}
				if ($edad > 59 and $edad < 65) { $at_an_60_64++; if($sexo == "M"){$at_an_60_64_h++;}else{$at_an_60_64_m++;}}
				if ($edad > 64 and $edad < 70) { $at_an_65_69++; if($sexo == "M"){$at_an_65_69_h++;}else{$at_an_65_69_m++;}}
				if ($edad > 69 and $edad < 75) { $at_an_70_74++; if($sexo == "M"){$at_an_70_74_h++;}else{$at_an_70_74_m++;}}
				if ($edad > 74 and $edad < 80) { $at_an_75_79++; if($sexo == "M"){$at_an_75_79_h++;}else{$at_an_75_79_m++;}}
				if ($edad > 79) { $at_an_80_m++; if($sexo == "M"){$at_an_80_m_h++;}else{$at_an_80_m_m++;} }
				if ($sexo == "M") { $at_an_h++; } else { $at_an_m++; }

				if ($previcod < 4) { $at_an_ben++; }
				switch ($categorizacion) {//INICIO $categorizacion 
					case 'ESI-1':
					if ($edad < 5) { $cat_c1_0_4++;                   if($sexo == "M"){$cat_c1_0_4_h++;}else{$cat_c1_0_4_m++;} }
					if ($edad > 4 and $edad < 10) { $cat_c1_5_9++;    if($sexo == "M"){$cat_c1_5_9_h++;}else{$cat_c1_5_9_m++;} }
					if ($edad > 9 and $edad < 15) { $cat_c1_10_14++;  if($sexo == "M"){$cat_c1_10_14_h++;}else{$cat_c1_10_14_m++;}}
					if ($edad > 14 and $edad < 20) { $cat_c1_15_19++; if($sexo == "M"){$cat_c1_15_19_h++;}else{$cat_c1_15_19_m++;} }
					if ($edad > 19 and $edad < 25) { $cat_c1_20_24++; if($sexo == "M"){$cat_c1_20_24_h++;}else{$cat_c1_20_24_m++;}}
					if ($edad > 24 and $edad < 30) { $cat_c1_25_29++; if($sexo == "M"){$cat_c1_25_29_h++;}else{$cat_c1_25_29_m++;}}
					if ($edad > 29 and $edad < 35) { $cat_c1_30_34++; if($sexo == "M"){$cat_c1_30_34_h++;}else{$cat_c1_30_34_m++;} }
					if ($edad > 34 and $edad < 40) { $cat_c1_35_39++; if($sexo == "M"){$cat_c1_35_39_h++;}else{$cat_c1_35_39_m++;} }
					if ($edad > 39 and $edad < 45) { $cat_c1_40_44++; if($sexo == "M"){$cat_c1_40_44_h++;}else{$cat_c1_40_44_m++;} }
					if ($edad > 44 and $edad < 50) { $cat_c1_45_49++; if($sexo == "M"){$cat_c1_45_49_h++;}else{$cat_c1_45_49_m++;} }
					if ($edad > 49 and $edad < 55) { $cat_c1_50_54++; if($sexo == "M"){$cat_c1_50_54_h++;}else{$cat_c1_50_54_m++;}}
					if ($edad > 54 and $edad < 60) { $cat_c1_55_59++; if($sexo == "M"){$cat_c1_55_59_h++;}else{$cat_c1_55_59_m++;}}
					if ($edad > 59 and $edad < 65) { $cat_c1_60_64++; if($sexo == "M"){$cat_c1_60_64_h++;}else{$cat_c1_60_64_m++;}}
					if ($edad > 64 and $edad < 70) { $cat_c1_65_69++; if($sexo == "M"){$cat_c1_65_69_h++;}else{$cat_c1_65_69_m++;} }
					if ($edad > 69 and $edad < 75) { $cat_c1_70_74++; if($sexo == "M"){$cat_c1_70_74_h++;}else{$cat_c1_70_74_m++;}}
					if ($edad > 74 and $edad < 80) { $cat_c1_75_79++; if($sexo == "M"){$cat_c1_75_79_h++;}else{$cat_c1_75_79_m++;}}
					if ($edad > 79) { $cat_c1_80_m++; if($sexo == "M"){$cat_c1_80_m_h++;}else{$cat_c1_80_m_m++;} }					
					break;
					case 'C1':
					if ($edad < 5) { $cat_c1_0_4++;                   if($sexo == "M"){$cat_c1_0_4_h++;}else{$cat_c1_0_4_m++;} }
					if ($edad > 4 and $edad < 10) { $cat_c1_5_9++;    if($sexo == "M"){$cat_c1_5_9_h++;}else{$cat_c1_5_9_m++;} }
					if ($edad > 9 and $edad < 15) { $cat_c1_10_14++;  if($sexo == "M"){$cat_c1_10_14_h++;}else{$cat_c1_10_14_m++;}}
					if ($edad > 14 and $edad < 20) { $cat_c1_15_19++; if($sexo == "M"){$cat_c1_15_19_h++;}else{$cat_c1_15_19_m++;} }
					if ($edad > 19 and $edad < 25) { $cat_c1_20_24++; if($sexo == "M"){$cat_c1_20_24_h++;}else{$cat_c1_20_24_m++;}}
					if ($edad > 24 and $edad < 30) { $cat_c1_25_29++; if($sexo == "M"){$cat_c1_25_29_h++;}else{$cat_c1_25_29_m++;}}
					if ($edad > 29 and $edad < 35) { $cat_c1_30_34++; if($sexo == "M"){$cat_c1_30_34_h++;}else{$cat_c1_30_34_m++;} }
					if ($edad > 34 and $edad < 40) { $cat_c1_35_39++; if($sexo == "M"){$cat_c1_35_39_h++;}else{$cat_c1_35_39_m++;} }
					if ($edad > 39 and $edad < 45) { $cat_c1_40_44++; if($sexo == "M"){$cat_c1_40_44_h++;}else{$cat_c1_40_44_m++;} }
					if ($edad > 44 and $edad < 50) { $cat_c1_45_49++; if($sexo == "M"){$cat_c1_45_49_h++;}else{$cat_c1_45_49_m++;} }
					if ($edad > 49 and $edad < 55) { $cat_c1_50_54++; if($sexo == "M"){$cat_c1_50_54_h++;}else{$cat_c1_50_54_m++;}}
					if ($edad > 54 and $edad < 60) { $cat_c1_55_59++; if($sexo == "M"){$cat_c1_55_59_h++;}else{$cat_c1_55_59_m++;}}
					if ($edad > 59 and $edad < 65) { $cat_c1_60_64++; if($sexo == "M"){$cat_c1_60_64_h++;}else{$cat_c1_60_64_m++;}}
					if ($edad > 64 and $edad < 70) { $cat_c1_65_69++; if($sexo == "M"){$cat_c1_65_69_h++;}else{$cat_c1_65_69_m++;} }
					if ($edad > 69 and $edad < 75) { $cat_c1_70_74++; if($sexo == "M"){$cat_c1_70_74_h++;}else{$cat_c1_70_74_m++;}}
					if ($edad > 74 and $edad < 80) { $cat_c1_75_79++; if($sexo == "M"){$cat_c1_75_79_h++;}else{$cat_c1_75_79_m++;}}
					if ($edad > 79) { $cat_c1_80_m++; if($sexo == "M"){$cat_c1_80_m_h++;}else{$cat_c1_80_m_m++;} }					
					break;

					case 'ESI-2':
					if ($edad < 5) { $cat_c2_0_4++; if($sexo == "M"){$cat_c2_0_4_h++;}else{$cat_c2_0_4_m++;} }
					if ($edad > 4 and $edad < 10) { $cat_c2_5_9++; if($sexo == "M"){$cat_c2_5_9_h++;}else{$cat_c2_5_9_m++;} }
					if ($edad > 9 and $edad < 15) { $cat_c2_10_14++; if($sexo == "M"){$cat_c2_10_14_h++;}else{$cat_c2_10_14_m++;} }
					if ($edad > 14 and $edad < 20) { $cat_c2_15_19++; if($sexo == "M"){$cat_c2_15_19_h++;}else{$cat_c2_15_19_m++;} }
					if ($edad > 19 and $edad < 25) { $cat_c2_20_24++; if($sexo == "M"){$cat_c2_20_24_h++;}else{$cat_c2_20_24_m++;} }
					if ($edad > 24 and $edad < 30) { $cat_c2_25_29++; if($sexo == "M"){$cat_c2_25_29_h++;}else{$cat_c2_25_29_m++;} }
					if ($edad > 29 and $edad < 35) { $cat_c2_30_34++; if($sexo == "M"){$cat_c2_30_34_h++;}else{$cat_c2_30_34_m++;} }
					if ($edad > 34 and $edad < 40) { $cat_c2_35_39++; if($sexo == "M"){$cat_c2_35_39_h++;}else{$cat_c2_35_39_m++;} }
					if ($edad > 39 and $edad < 45) { $cat_c2_40_44++; if($sexo == "M"){$cat_c2_40_44_h++;}else{$cat_c2_40_44_m++;} }
					if ($edad > 44 and $edad < 50) { $cat_c2_45_49++; if($sexo == "M"){$cat_c2_45_49_h++;}else{$cat_c2_45_49_m++;} }
					if ($edad > 49 and $edad < 55) { $cat_c2_50_54++; if($sexo == "M"){$cat_c2_50_54_h++;}else{$cat_c2_50_54_m++;} }
					if ($edad > 54 and $edad < 60) { $cat_c2_55_59++; if($sexo == "M"){$cat_c2_55_59_h++;}else{$cat_c2_55_59_m++;} }
					if ($edad > 59 and $edad < 65) { $cat_c2_60_64++; if($sexo == "M"){$cat_c2_60_64_h++;}else{$cat_c2_60_64_m++;} }
					if ($edad > 64 and $edad < 70) { $cat_c2_65_69++; if($sexo == "M"){$cat_c2_65_69_h++;}else{$cat_c2_65_69_m++;} }
					if ($edad > 69 and $edad < 75) { $cat_c2_70_74++; if($sexo == "M"){$cat_c2_70_74_h++;}else{$cat_c2_70_74_m++;} }
					if ($edad > 74 and $edad < 80) { $cat_c2_75_79++; if($sexo == "M"){$cat_c2_75_79_h++;}else{$cat_c2_75_79_m++;} }
					if ($edad > 79) { $cat_c2_80_m++; if($sexo == "M"){$cat_c2_80_m_h++;}else{$cat_c2_80_m_m++;} }	
					break;
					case 'C2':
					if ($edad < 5) { $cat_c2_0_4++; if($sexo == "M"){$cat_c2_0_4_h++;}else{$cat_c2_0_4_m++;} }
					if ($edad > 4 and $edad < 10) { $cat_c2_5_9++; if($sexo == "M"){$cat_c2_5_9_h++;}else{$cat_c2_5_9_m++;} }
					if ($edad > 9 and $edad < 15) { $cat_c2_10_14++; if($sexo == "M"){$cat_c2_10_14_h++;}else{$cat_c2_10_14_m++;} }
					if ($edad > 14 and $edad < 20) { $cat_c2_15_19++; if($sexo == "M"){$cat_c2_15_19_h++;}else{$cat_c2_15_19_m++;} }
					if ($edad > 19 and $edad < 25) { $cat_c2_20_24++; if($sexo == "M"){$cat_c2_20_24_h++;}else{$cat_c2_20_24_m++;} }
					if ($edad > 24 and $edad < 30) { $cat_c2_25_29++; if($sexo == "M"){$cat_c2_25_29_h++;}else{$cat_c2_25_29_m++;} }
					if ($edad > 29 and $edad < 35) { $cat_c2_30_34++; if($sexo == "M"){$cat_c2_30_34_h++;}else{$cat_c2_30_34_m++;} }
					if ($edad > 34 and $edad < 40) { $cat_c2_35_39++; if($sexo == "M"){$cat_c2_35_39_h++;}else{$cat_c2_35_39_m++;} }
					if ($edad > 39 and $edad < 45) { $cat_c2_40_44++; if($sexo == "M"){$cat_c2_40_44_h++;}else{$cat_c2_40_44_m++;} }
					if ($edad > 44 and $edad < 50) { $cat_c2_45_49++; if($sexo == "M"){$cat_c2_45_49_h++;}else{$cat_c2_45_49_m++;} }
					if ($edad > 49 and $edad < 55) { $cat_c2_50_54++; if($sexo == "M"){$cat_c2_50_54_h++;}else{$cat_c2_50_54_m++;} }
					if ($edad > 54 and $edad < 60) { $cat_c2_55_59++; if($sexo == "M"){$cat_c2_55_59_h++;}else{$cat_c2_55_59_m++;} }
					if ($edad > 59 and $edad < 65) { $cat_c2_60_64++; if($sexo == "M"){$cat_c2_60_64_h++;}else{$cat_c2_60_64_m++;} }
					if ($edad > 64 and $edad < 70) { $cat_c2_65_69++; if($sexo == "M"){$cat_c2_65_69_h++;}else{$cat_c2_65_69_m++;} }
					if ($edad > 69 and $edad < 75) { $cat_c2_70_74++; if($sexo == "M"){$cat_c2_70_74_h++;}else{$cat_c2_70_74_m++;} }
					if ($edad > 74 and $edad < 80) { $cat_c2_75_79++; if($sexo == "M"){$cat_c2_75_79_h++;}else{$cat_c2_75_79_m++;} }
					if ($edad > 79) { $cat_c2_80_m++; if($sexo == "M"){$cat_c2_80_m_h++;}else{$cat_c2_80_m_m++;} }	
					break;

					case 'ESI-3':
					if ($edad < 5) { $cat_c3_0_4++; if($sexo == "M"){$cat_c3_0_4_h++;}else{$cat_c3_0_4_m++;} }
					if ($edad > 4 and $edad < 10) { $cat_c3_5_9++; if($sexo == "M"){$cat_c3_5_9_h++;}else{$cat_c3_5_9_m++;} }
					if ($edad > 9 and $edad < 15) { $cat_c3_10_14++; if($sexo == "M"){$cat_c3_10_14_h++;}else{$cat_c3_10_14_m++;} }
					if ($edad > 14 and $edad < 20) { $cat_c3_15_19++; if($sexo == "M"){$cat_c3_15_19_h++;}else{$cat_c3_15_19_m++;} }
					if ($edad > 19 and $edad < 25) { $cat_c3_20_24++; if($sexo == "M"){$cat_c3_20_24_h++;}else{$cat_c3_20_24_m++;} }
					if ($edad > 24 and $edad < 30) { $cat_c3_25_29++; if($sexo == "M"){$cat_c3_25_29_h++;}else{$cat_c3_25_29_m++;} }
					if ($edad > 29 and $edad < 35) { $cat_c3_30_34++; if($sexo == "M"){$cat_c3_30_34_h++;}else{$cat_c3_30_34_m++;} }
					if ($edad > 34 and $edad < 40) { $cat_c3_35_39++; if($sexo == "M"){$cat_c3_35_39_h++;}else{$cat_c3_35_39_m++;} }
					if ($edad > 39 and $edad < 45) { $cat_c3_40_44++; if($sexo == "M"){$cat_c3_40_44_h++;}else{$cat_c3_40_44_m++;} }
					if ($edad > 44 and $edad < 50) { $cat_c3_45_49++; if($sexo == "M"){$cat_c3_45_49_h++;}else{$cat_c3_45_49_m++;} }
					if ($edad > 49 and $edad < 55) { $cat_c3_50_54++; if($sexo == "M"){$cat_c3_50_54_h++;}else{$cat_c3_50_54_m++;} }
					if ($edad > 54 and $edad < 60) { $cat_c3_55_59++; if($sexo == "M"){$cat_c3_55_59_h++;}else{$cat_c3_55_59_m++;} }
					if ($edad > 59 and $edad < 65) { $cat_c3_60_64++; if($sexo == "M"){$cat_c3_60_64_h++;}else{$cat_c3_60_64_m++;} }
					if ($edad > 64 and $edad < 70) { $cat_c3_65_69++; if($sexo == "M"){$cat_c3_65_69_h++;}else{$cat_c3_65_69_m++;} }
					if ($edad > 69 and $edad < 75) { $cat_c3_70_74++; if($sexo == "M"){$cat_c3_70_74_h++;}else{$cat_c3_70_74_m++;} }
					if ($edad > 74 and $edad < 80) { $cat_c3_75_79++; if($sexo == "M"){$cat_c3_75_79_h++;}else{$cat_c3_75_79_m++;} }
					if ($edad > 79) { $cat_c3_80_m++; if($sexo == "M"){$cat_c3_80_m_h++;}else{$cat_c3_80_m_m++;} }	
					break;
					case 'C3':
					if ($edad < 5) { $cat_c3_0_4++; if($sexo == "M"){$cat_c3_0_4_h++;}else{$cat_c3_0_4_m++;} }
					if ($edad > 4 and $edad < 10) { $cat_c3_5_9++; if($sexo == "M"){$cat_c3_5_9_h++;}else{$cat_c3_5_9_m++;} }
					if ($edad > 9 and $edad < 15) { $cat_c3_10_14++; if($sexo == "M"){$cat_c3_10_14_h++;}else{$cat_c3_10_14_m++;} }
					if ($edad > 14 and $edad < 20) { $cat_c3_15_19++; if($sexo == "M"){$cat_c3_15_19_h++;}else{$cat_c3_15_19_m++;} }
					if ($edad > 19 and $edad < 25) { $cat_c3_20_24++; if($sexo == "M"){$cat_c3_20_24_h++;}else{$cat_c3_20_24_m++;} }
					if ($edad > 24 and $edad < 30) { $cat_c3_25_29++; if($sexo == "M"){$cat_c3_25_29_h++;}else{$cat_c3_25_29_m++;} }
					if ($edad > 29 and $edad < 35) { $cat_c3_30_34++; if($sexo == "M"){$cat_c3_30_34_h++;}else{$cat_c3_30_34_m++;} }
					if ($edad > 34 and $edad < 40) { $cat_c3_35_39++; if($sexo == "M"){$cat_c3_35_39_h++;}else{$cat_c3_35_39_m++;} }
					if ($edad > 39 and $edad < 45) { $cat_c3_40_44++; if($sexo == "M"){$cat_c3_40_44_h++;}else{$cat_c3_40_44_m++;} }
					if ($edad > 44 and $edad < 50) { $cat_c3_45_49++; if($sexo == "M"){$cat_c3_45_49_h++;}else{$cat_c3_45_49_m++;} }
					if ($edad > 49 and $edad < 55) { $cat_c3_50_54++; if($sexo == "M"){$cat_c3_50_54_h++;}else{$cat_c3_50_54_m++;} }
					if ($edad > 54 and $edad < 60) { $cat_c3_55_59++; if($sexo == "M"){$cat_c3_55_59_h++;}else{$cat_c3_55_59_m++;} }
					if ($edad > 59 and $edad < 65) { $cat_c3_60_64++; if($sexo == "M"){$cat_c3_60_64_h++;}else{$cat_c3_60_64_m++;} }
					if ($edad > 64 and $edad < 70) { $cat_c3_65_69++; if($sexo == "M"){$cat_c3_65_69_h++;}else{$cat_c3_65_69_m++;} }
					if ($edad > 69 and $edad < 75) { $cat_c3_70_74++; if($sexo == "M"){$cat_c3_70_74_h++;}else{$cat_c3_70_74_m++;} }
					if ($edad > 74 and $edad < 80) { $cat_c3_75_79++; if($sexo == "M"){$cat_c3_75_79_h++;}else{$cat_c3_75_79_m++;} }
					if ($edad > 79) { $cat_c3_80_m++; if($sexo == "M"){$cat_c3_80_m_h++;}else{$cat_c3_80_m_m++;} }	
					break;

					case 'ESI-4':
					if ($edad < 5) { $cat_c4_0_4++; if($sexo=="M"){$cat_c4_0_4_h++;}else{$cat_c4_0_4_m++;} }
					if ($edad > 4 and $edad < 10) { $cat_c4_5_9++; if($sexo=="M"){$cat_c4_5_9_h++;}else{$cat_c4_5_9_m++;} }
					if ($edad > 9 and $edad < 15) { $cat_c4_10_14++; if($sexo=="M"){$cat_c4_10_14_h++;}else{$cat_c4_10_14_m++;} }
					if ($edad > 14 and $edad < 20) { $cat_c4_15_19++; if($sexo=="M"){$cat_c4_15_19_h++;}else{$cat_c4_15_19_m++;} }
					if ($edad > 19 and $edad < 25) { $cat_c4_20_24++; if($sexo=="M"){$cat_c4_20_24_h++;}else{$cat_c4_20_24_m++;} }
					if ($edad > 24 and $edad < 30) { $cat_c4_25_29++; if($sexo=="M"){$cat_c4_25_29_h++;}else{$cat_c4_25_29_m++;} }
					if ($edad > 29 and $edad < 35) { $cat_c4_30_34++; if($sexo=="M"){$cat_c4_30_34_h++;}else{$cat_c4_30_34_m++;} }
					if ($edad > 34 and $edad < 40) { $cat_c4_35_39++; if($sexo=="M"){$cat_c4_35_39_h++;}else{$cat_c4_35_39_m++;} }
					if ($edad > 39 and $edad < 45) { $cat_c4_40_44++; if($sexo=="M"){$cat_c4_40_44_h++;}else{$cat_c4_40_44_m++;} }
					if ($edad > 44 and $edad < 50) { $cat_c4_45_49++; if($sexo=="M"){$cat_c4_45_49_h++;}else{$cat_c4_45_49_m++;} }
					if ($edad > 49 and $edad < 55) { $cat_c4_50_54++; if($sexo=="M"){$cat_c4_50_54_h++;}else{$cat_c4_50_54_m++;} }
					if ($edad > 54 and $edad < 60) { $cat_c4_55_59++; if($sexo=="M"){$cat_c4_55_59_h++;}else{$cat_c4_55_59_m++;} }
					if ($edad > 59 and $edad < 65) { $cat_c4_60_64++; if($sexo=="M"){$cat_c4_60_64_h++;}else{$cat_c4_60_64_m++;} }
					if ($edad > 64 and $edad < 70) { $cat_c4_65_69++; if($sexo=="M"){$cat_c4_65_69_h++;}else{$cat_c4_65_69_m++;} }
					if ($edad > 69 and $edad < 75) { $cat_c4_70_74++; if($sexo=="M"){$cat_c4_70_74_h++;}else{$cat_c4_70_74_m++;} }
					if ($edad > 74 and $edad < 80) { $cat_c4_75_79++; if($sexo=="M"){$cat_c4_75_79_h++;}else{$cat_c4_75_79_m++;} }
					if ($edad > 79) { $cat_c4_80_m++; if($sexo=="M"){$cat_c4_80_m_h++;}else{$cat_c4_80_m_m++;} }
					break;
					case 'C4':
					if ($edad < 5) { $cat_c4_0_4++; if($sexo=="M"){$cat_c4_0_4_h++;}else{$cat_c4_0_4_m++;} }
					if ($edad > 4 and $edad < 10) { $cat_c4_5_9++; if($sexo=="M"){$cat_c4_5_9_h++;}else{$cat_c4_5_9_m++;} }
					if ($edad > 9 and $edad < 15) { $cat_c4_10_14++; if($sexo=="M"){$cat_c4_10_14_h++;}else{$cat_c4_10_14_m++;} }
					if ($edad > 14 and $edad < 20) { $cat_c4_15_19++; if($sexo=="M"){$cat_c4_15_19_h++;}else{$cat_c4_15_19_m++;} }
					if ($edad > 19 and $edad < 25) { $cat_c4_20_24++; if($sexo=="M"){$cat_c4_20_24_h++;}else{$cat_c4_20_24_m++;} }
					if ($edad > 24 and $edad < 30) { $cat_c4_25_29++; if($sexo=="M"){$cat_c4_25_29_h++;}else{$cat_c4_25_29_m++;} }
					if ($edad > 29 and $edad < 35) { $cat_c4_30_34++; if($sexo=="M"){$cat_c4_30_34_h++;}else{$cat_c4_30_34_m++;} }
					if ($edad > 34 and $edad < 40) { $cat_c4_35_39++; if($sexo=="M"){$cat_c4_35_39_h++;}else{$cat_c4_35_39_m++;} }
					if ($edad > 39 and $edad < 45) { $cat_c4_40_44++; if($sexo=="M"){$cat_c4_40_44_h++;}else{$cat_c4_40_44_m++;} }
					if ($edad > 44 and $edad < 50) { $cat_c4_45_49++; if($sexo=="M"){$cat_c4_45_49_h++;}else{$cat_c4_45_49_m++;} }
					if ($edad > 49 and $edad < 55) { $cat_c4_50_54++; if($sexo=="M"){$cat_c4_50_54_h++;}else{$cat_c4_50_54_m++;} }
					if ($edad > 54 and $edad < 60) { $cat_c4_55_59++; if($sexo=="M"){$cat_c4_55_59_h++;}else{$cat_c4_55_59_m++;} }
					if ($edad > 59 and $edad < 65) { $cat_c4_60_64++; if($sexo=="M"){$cat_c4_60_64_h++;}else{$cat_c4_60_64_m++;} }
					if ($edad > 64 and $edad < 70) { $cat_c4_65_69++; if($sexo=="M"){$cat_c4_65_69_h++;}else{$cat_c4_65_69_m++;} }
					if ($edad > 69 and $edad < 75) { $cat_c4_70_74++; if($sexo=="M"){$cat_c4_70_74_h++;}else{$cat_c4_70_74_m++;} }
					if ($edad > 74 and $edad < 80) { $cat_c4_75_79++; if($sexo=="M"){$cat_c4_75_79_h++;}else{$cat_c4_75_79_m++;} }
					if ($edad > 79) { $cat_c4_80_m++; if($sexo=="M"){$cat_c4_80_m_h++;}else{$cat_c4_80_m_m++;} }
					break;

					case 'ESI-5':
					if ($edad < 5) { $cat_c5_0_4++; if($sexo == "M"){$cat_c5_0_4_h++;}else{$cat_c5_0_4_m++;} }
					if ($edad > 4 and $edad < 10) { $cat_c5_5_9++; if($sexo == "M"){$cat_c5_5_9_h++;}else{$cat_c5_5_9_m++;} }
					if ($edad > 9 and $edad < 15) { $cat_c5_10_14++; if($sexo == "M"){$cat_c5_10_14_h++;}else{$cat_c5_10_14_m++;} }
					if ($edad > 14 and $edad < 20) { $cat_c5_15_19++; if($sexo == "M"){$cat_c5_15_19_h++;}else{$cat_c5_15_19_m++;} }
					if ($edad > 19 and $edad < 25) { $cat_c5_20_24++; if($sexo == "M"){$cat_c5_20_24_h++;}else{$cat_c5_20_24_m++;} }
					if ($edad > 24 and $edad < 30) { $cat_c5_25_29++; if($sexo == "M"){$cat_c5_25_29_h++;}else{$cat_c5_25_29_m++;} }
					if ($edad > 29 and $edad < 35) { $cat_c5_30_34++; if($sexo == "M"){$cat_c5_30_34_h++;}else{$cat_c5_30_34_m++;} }
					if ($edad > 34 and $edad < 40) { $cat_c5_35_39++; if($sexo == "M"){$cat_c5_35_39_h++;}else{$cat_c5_35_39_m++;} }
					if ($edad > 39 and $edad < 45) { $cat_c5_40_44++; if($sexo == "M"){$cat_c5_40_44_h++;}else{$cat_c5_40_44_m++;} }
					if ($edad > 44 and $edad < 50) { $cat_c5_45_49++; if($sexo == "M"){$cat_c5_45_49_h++;}else{$cat_c5_45_49_m++;} }
					if ($edad > 49 and $edad < 55) { $cat_c5_50_54++; if($sexo == "M"){$cat_c5_50_54_h++;}else{$cat_c5_50_54_m++;} }
					if ($edad > 54 and $edad < 60) { $cat_c5_55_59++; if($sexo == "M"){$cat_c5_55_59_h++;}else{$cat_c5_55_59_m++;} }
					if ($edad > 59 and $edad < 65) { $cat_c5_60_64++; if($sexo == "M"){$cat_c5_60_64_h++;}else{$cat_c5_60_64_m++;} }
					if ($edad > 64 and $edad < 70) { $cat_c5_65_69++; if($sexo == "M"){$cat_c5_65_69_h++;}else{$cat_c5_65_69_m++;} }
					if ($edad > 69 and $edad < 75) { $cat_c5_70_74++; if($sexo == "M"){$cat_c5_70_74_h++;}else{$cat_c5_70_74_m++;} }
					if ($edad > 74 and $edad < 80) { $cat_c5_75_79++; if($sexo == "M"){$cat_c5_75_79_h++;}else{$cat_c5_75_79_m++;} }
					if ($edad > 79) { $cat_c5_80_m++; if($sexo == "M"){$cat_c5_80_m_h++;}else{$cat_c5_80_m_m++;} }	
					break;
					case 'C5':
					if ($edad < 5) { $cat_c5_0_4++; if($sexo == "M"){$cat_c5_0_4_h++;}else{$cat_c5_0_4_m++;} }
					if ($edad > 4 and $edad < 10) { $cat_c5_5_9++; if($sexo == "M"){$cat_c5_5_9_h++;}else{$cat_c5_5_9_m++;} }
					if ($edad > 9 and $edad < 15) { $cat_c5_10_14++; if($sexo == "M"){$cat_c5_10_14_h++;}else{$cat_c5_10_14_m++;} }
					if ($edad > 14 and $edad < 20) { $cat_c5_15_19++; if($sexo == "M"){$cat_c5_15_19_h++;}else{$cat_c5_15_19_m++;} }
					if ($edad > 19 and $edad < 25) { $cat_c5_20_24++; if($sexo == "M"){$cat_c5_20_24_h++;}else{$cat_c5_20_24_m++;} }
					if ($edad > 24 and $edad < 30) { $cat_c5_25_29++; if($sexo == "M"){$cat_c5_25_29_h++;}else{$cat_c5_25_29_m++;} }
					if ($edad > 29 and $edad < 35) { $cat_c5_30_34++; if($sexo == "M"){$cat_c5_30_34_h++;}else{$cat_c5_30_34_m++;} }
					if ($edad > 34 and $edad < 40) { $cat_c5_35_39++; if($sexo == "M"){$cat_c5_35_39_h++;}else{$cat_c5_35_39_m++;} }
					if ($edad > 39 and $edad < 45) { $cat_c5_40_44++; if($sexo == "M"){$cat_c5_40_44_h++;}else{$cat_c5_40_44_m++;} }
					if ($edad > 44 and $edad < 50) { $cat_c5_45_49++; if($sexo == "M"){$cat_c5_45_49_h++;}else{$cat_c5_45_49_m++;} }
					if ($edad > 49 and $edad < 55) { $cat_c5_50_54++; if($sexo == "M"){$cat_c5_50_54_h++;}else{$cat_c5_50_54_m++;} }
					if ($edad > 54 and $edad < 60) { $cat_c5_55_59++; if($sexo == "M"){$cat_c5_55_59_h++;}else{$cat_c5_55_59_m++;} }
					if ($edad > 59 and $edad < 65) { $cat_c5_60_64++; if($sexo == "M"){$cat_c5_60_64_h++;}else{$cat_c5_60_64_m++;} }
					if ($edad > 64 and $edad < 70) { $cat_c5_65_69++; if($sexo == "M"){$cat_c5_65_69_h++;}else{$cat_c5_65_69_m++;} }
					if ($edad > 69 and $edad < 75) { $cat_c5_70_74++; if($sexo == "M"){$cat_c5_70_74_h++;}else{$cat_c5_70_74_m++;} }
					if ($edad > 74 and $edad < 80) { $cat_c5_75_79++; if($sexo == "M"){$cat_c5_75_79_h++;}else{$cat_c5_75_79_m++;} }
					if ($edad > 79) { $cat_c5_80_m++; if($sexo == "M"){$cat_c5_80_m_h++;}else{$cat_c5_80_m_m++;} }	
					break;

					default:
					if ($edad < 5) { $cat_sc_0_4++; if($sexo == "M"){$cat_sc_0_4_h++;}else{$cat_sc_0_4_m++;} }
					if ($edad > 4 and $edad < 10) { $cat_sc_5_9++; if($sexo == "M"){$cat_sc_5_9_h++;}else{$cat_sc_5_9_m++;} }
					if ($edad > 9 and $edad < 15) { $cat_sc_10_14++; if($sexo == "M"){$cat_sc_10_14_h++;}else{$cat_sc_10_14_m++;} }
					if ($edad > 14 and $edad < 20) { $cat_sc_15_19++; if($sexo == "M"){$cat_sc_15_19_h++;}else{$cat_sc_15_19_m++;} }
					if ($edad > 19 and $edad < 25) { $cat_sc_20_24++; if($sexo == "M"){$cat_sc_20_24_h++;}else{$cat_sc_20_24_m++;} }
					if ($edad > 24 and $edad < 30) { $cat_sc_25_29++; if($sexo == "M"){$cat_sc_25_29_h++;}else{$cat_sc_25_29_m++;} }
					if ($edad > 29 and $edad < 35) { $cat_sc_30_34++; if($sexo == "M"){$cat_sc_30_34_h++;}else{$cat_sc_30_34_m++;} }
					if ($edad > 34 and $edad < 40) { $cat_sc_35_39++; if($sexo == "M"){$cat_sc_35_39_h++;}else{$cat_sc_35_39_m++;} }
					if ($edad > 39 and $edad < 45) { $cat_sc_40_44++; if($sexo == "M"){$cat_sc_40_44_h++;}else{$cat_sc_40_44_m++;} }
					if ($edad > 44 and $edad < 50) { $cat_sc_45_49++; if($sexo == "M"){$cat_sc_45_49_h++;}else{$cat_sc_45_49_m++;} }
					if ($edad > 49 and $edad < 55) { $cat_sc_50_54++; if($sexo == "M"){$cat_sc_50_54_h++;}else{$cat_sc_50_54_m++;} }
					if ($edad > 54 and $edad < 60) { $cat_sc_55_59++; if($sexo == "M"){$cat_sc_55_59_h++;}else{$cat_sc_55_59_m++;} }
					if ($edad > 59 and $edad < 65) { $cat_sc_60_64++; if($sexo == "M"){$cat_sc_60_64_h++;}else{$cat_sc_60_64_m++;} }
					if ($edad > 64 and $edad < 70) { $cat_sc_65_69++; if($sexo == "M"){$cat_sc_65_69_h++;}else{$cat_sc_65_69_m++;} }
					if ($edad > 69 and $edad < 75) { $cat_sc_70_74++; if($sexo == "M"){$cat_sc_70_74_h++;}else{$cat_sc_70_74_m++;} }
					if ($edad > 74 and $edad < 80) { $cat_sc_75_79++; if($sexo == "M"){$cat_sc_75_79_h++;}else{$cat_sc_75_79_m++;} }
					if ($edad > 79) { $cat_sc_80_m++; if($sexo == "M"){$cat_sc_80_m_h++;}else{$cat_sc_80_m_m++;} }		
					break;
				}//FIN $categorizacion
			}//FIN $tipoatencion < 3

			if($tipoatencion == 3){ //INICIO $tipoatencion == 3
				if ($atendidopor == 1){//INICIO $tipoatencion == 1
					$total_gi++;
					if ($edad < 5) { $at_gi_0_4++;                   if($sexo == "M"){$at_gi_0_4_h++;}else{$at_gi_0_4_m++;} }
					if ($edad > 4 and $edad < 10) { $at_gi_5_9++;    if($sexo == "M"){$at_gi_5_9_h++;}else{$at_gi_5_9_m++;} }
					if ($edad > 9 and $edad < 15) { $at_gi_10_14++;  if($sexo == "M"){$at_gi_10_14_h++;}else{$at_gi_10_14_m++;} }
					if ($edad > 14 and $edad < 20) { $at_gi_15_19++; if($sexo == "M"){$at_gi_15_19_h++;}else{$at_gi_15_19_m++;}}
					if ($edad > 19 and $edad < 25) { $at_gi_20_24++; if($sexo == "M"){$at_gi_20_24_h++;}else{$at_gi_20_24_m++;} }
					if ($edad > 24 and $edad < 30) { $at_gi_25_29++; if($sexo == "M"){$at_gi_25_29_h++;}else{$at_gi_25_29_m++;}}
					if ($edad > 29 and $edad < 35) { $at_gi_30_34++; if($sexo == "M"){$at_gi_30_34_h++;}else{$at_gi_30_34_m++;}}
					if ($edad > 34 and $edad < 40) { $at_gi_35_39++; if($sexo == "M"){$at_gi_35_39_h++;}else{$at_gi_35_39_m++;}}
					if ($edad > 39 and $edad < 45) { $at_gi_40_44++; if($sexo == "M"){$at_gi_40_44_h++;}else{$at_gi_40_44_m++;}}
					if ($edad > 44 and $edad < 50) { $at_gi_45_49++; if($sexo == "M"){$at_gi_45_49_h++;}else{$at_gi_45_49_m++;}}
					if ($edad > 49 and $edad < 55) { $at_gi_50_54++; if($sexo == "M"){$at_gi_50_54_h++;}else{$at_gi_50_54_m++;}}
					if ($edad > 54 and $edad < 60) { $at_gi_55_59++; if($sexo == "M"){$at_gi_55_59_h++;}else{$at_gi_55_59_m++;}}
					if ($edad > 59 and $edad < 65) { $at_gi_60_64++; if($sexo == "M"){$at_gi_60_64_h++;}else{$at_gi_60_64_m++;}}
					if ($edad > 64 and $edad < 70) { $at_gi_65_69++; if($sexo == "M"){$at_gi_65_69_h++;}else{$at_gi_65_69_m++;}}
					if ($edad > 69 and $edad < 75) { $at_gi_70_74++; if($sexo == "M"){$at_gi_70_74_h++;}else{$at_gi_70_74_m++;}}
					if ($edad > 74 and $edad < 80) { $at_gi_75_79++; if($sexo == "M"){$at_gi_75_79_h++;}else{$at_gi_75_79_m++;}}
					if ($edad > 79) { $at_gi_80_m++; if($sexo == "M"){$at_gi_80_m_h++;}else{$at_gi_80_m_m++;}}			
					if ($sexo == "M") { $at_gi_h++; } else { $at_gi_m++; }
					if ($previcod < 4) { $at_gi_ben++; }
				}else{
					$total_ma++;
					if ($edad < 5) { $at_ma_0_4++;                   if($sexo == "M"){$at_ma_0_4_h++;}else{$at_ma_0_4_m++;} }
					if ($edad > 4 and $edad < 10) { $at_ma_5_9++;    if($sexo == "M"){$at_ma_5_9_h++;}else{$at_ma_5_9_m++;} }
					if ($edad > 9 and $edad < 15) { $at_ma_10_14++;  if($sexo == "M"){$at_ma_10_14_h++;}else{$at_ma_10_14_m++;}}
					if ($edad > 14 and $edad < 20) { $at_ma_15_19++; if($sexo == "M"){$at_ma_15_19_h++;}else{$at_ma_15_19_m++;} }
					if ($edad > 19 and $edad < 25) { $at_ma_20_24++; if($sexo == "M"){$at_ma_20_24_h++;}else{$at_ma_20_24_m++;}}
					if ($edad > 24 and $edad < 30) { $at_ma_25_29++; if($sexo == "M"){$at_ma_25_29_h++;}else{$at_ma_25_29_m++;}}
					if ($edad > 29 and $edad < 35) { $at_ma_30_34++; if($sexo == "M"){$at_ma_30_34_h++;}else{$at_ma_30_34_m++;}}
					if ($edad > 34 and $edad < 40) { $at_ma_35_39++; if($sexo == "M"){$at_ma_35_39_h++;}else{$at_ma_35_39_m++;}}
					if ($edad > 39 and $edad < 45) { $at_ma_40_44++; if($sexo == "M"){$at_ma_40_44_h++;}else{$at_ma_40_44_m++;}}
					if ($edad > 44 and $edad < 50) { $at_ma_45_49++; if($sexo == "M"){$at_ma_45_49_h++;}else{$at_ma_45_49_m++;}}
					if ($edad > 49 and $edad < 55) { $at_ma_50_54++; if($sexo == "M"){$at_ma_50_54_h++;}else{$at_ma_50_54_m++;}}
					if ($edad > 54 and $edad < 60) { $at_ma_55_59++; if($sexo == "M"){$at_ma_55_59_h++;}else{$at_ma_55_59_m++;}}
					if ($edad > 59 and $edad < 65) { $at_ma_60_64++; if($sexo == "M"){$at_ma_60_64_h++;}else{$at_ma_60_64_m++;}}
					if ($edad > 64 and $edad < 70) { $at_ma_65_69++; if($sexo == "M"){$at_ma_65_69_h++;}else{$at_ma_65_69_m++;}}
					if ($edad > 69 and $edad < 75) { $at_ma_70_74++; if($sexo == "M"){$at_ma_70_74_h++;}else{$at_ma_70_74_m++;}}
					if ($edad > 74 and $edad < 80) { $at_ma_75_79++; if($sexo == "M"){$at_ma_75_79_h++;}else{$at_ma_75_79_m++;}}
					if ($edad > 79) { $at_ma_80_m++; if($sexo == "M"){$at_ma_80_m_h++;}else{$at_ma_80_m_m++;} }				
					if ($sexo == "M") { $at_ma_h++; } else { $at_ma_m++; }
					if ($previcod < 4) { $at_ma_ben++; }
				}//FIN $tipoatencion == 1
			}//FIN $tipoatencion == 3

		}//FIN DEL FOR
		
		$total_cc_0_4 = $cat_c1_0_4 + $cat_c2_0_4 + $cat_c3_0_4 + $cat_c4_0_4 + $cat_c5_0_4 + $cat_csi_0_4;
		$total_cc_5_9 = $cat_c1_5_9 + $cat_c2_5_9 + $cat_c3_5_9 + $cat_c4_5_9 + $cat_c5_5_9 + $cat_csi_5_9;
		$total_cc_10_14 = $cat_c1_10_14 + $cat_c2_10_14 + $cat_c3_10_14 + $cat_c4_10_14 + $cat_c5_10_14 + $cat_csi_10_14;
		$total_cc_15_19 = $cat_c1_15_19 + $cat_c2_15_19 + $cat_c3_15_19 + $cat_c4_15_19 + $cat_c5_15_19 + $cat_csi_15_19;
		$total_cc_20_24 = $cat_c1_20_24 + $cat_c2_20_24 + $cat_c3_20_24 + $cat_c4_20_24 + $cat_c5_20_24 + $cat_csi_20_24;
		$total_cc_25_29 = $cat_c1_25_29 + $cat_c2_25_29 + $cat_c3_25_29 + $cat_c4_25_29 + $cat_c5_25_29 + $cat_csi_25_29;
		$total_cc_30_34 = $cat_c1_30_34 + $cat_c2_30_34 + $cat_c3_30_34 + $cat_c4_30_34 + $cat_c5_30_34 + $cat_csi_30_34;
		$total_cc_35_39 = $cat_c1_35_39 + $cat_c2_35_39 + $cat_c3_35_39 + $cat_c4_35_39 + $cat_c5_35_39 + $cat_csi_35_39;
		$total_cc_40_44 = $cat_c1_40_44 + $cat_c2_40_44 + $cat_c3_40_44 + $cat_c4_40_44 + $cat_c5_40_44 + $cat_csi_40_44;
		$total_cc_45_49 = $cat_c1_45_49 + $cat_c2_45_49 + $cat_c3_45_49 + $cat_c4_45_49 + $cat_c5_45_49 + $cat_csi_45_49;
		$total_cc_50_54 = $cat_c1_50_54 + $cat_c2_50_54 + $cat_c3_50_54 + $cat_c4_50_54 + $cat_c5_50_54 + $cat_csi_50_54;
		$total_cc_55_59 = $cat_c1_55_59 + $cat_c2_55_59 + $cat_c3_55_59 + $cat_c4_55_59 + $cat_c5_55_59 + $cat_csi_55_59;
		$total_cc_60_64 = $cat_c1_60_64 + $cat_c2_60_64 + $cat_c3_60_64 + $cat_c4_60_64 + $cat_c5_60_64 + $cat_csi_60_64;
		$total_cc_65_69 = $cat_c1_65_69 + $cat_c2_65_69 + $cat_c3_65_69 + $cat_c4_65_69 + $cat_c5_65_69 + $cat_csi_65_69;
		$total_cc_70_74 = $cat_c1_70_74 + $cat_c2_70_74 + $cat_c3_70_74 + $cat_c4_70_74 + $cat_c5_70_74 + $cat_csi_70_74;
		$total_cc_75_79 = $cat_c1_75_79 + $cat_c2_75_79 + $cat_c3_75_79 + $cat_c4_75_79 + $cat_c5_75_79 + $cat_csi_75_79;
		$total_cc_80_m = $cat_c1_80_m + $cat_c2_80_m + $cat_c3_80_m + $cat_c4_80_m + $cat_c5_80_m + $cat_csi_80_m;

		$total_con_cate = $total_cc_0_4 + $total_cc_5_9 + $total_cc_10_14 + $total_cc_15_19 + $total_cc_20_24 + $total_cc_25_29 + $total_cc_30_34 + $total_cc_35_39 + $total_cc_40_44 + $total_cc_45_49 + $total_cc_50_54 + $total_cc_55_59 + $total_cc_60_64 + $total_cc_65_69 + $total_cc_70_74 + $total_cc_75_79 + $total_cc_80_m;

		$total_cate_0_4 = $total_cc_0_4 + $cat_sc_0_4;
		$total_cate_5_9 = $total_cc_5_9 + $cat_sc_5_9;
		$total_cate_10_14 = $total_cc_10_14 + $cat_sc_10_14;
		$total_cate_15_19 = $total_cc_15_19 + $cat_sc_15_19;
		$total_cate_20_24 = $total_cc_20_24 + $cat_sc_20_24;
		$total_cate_25_29 = $total_cc_25_29 + $cat_sc_25_29;
		$total_cate_30_34 = $total_cc_30_34 + $cat_sc_30_34;
		$total_cate_35_39 = $total_cc_35_39 + $cat_sc_35_39;
		$total_cate_40_44 = $total_cc_40_44 + $cat_sc_40_44;
		$total_cate_45_49 = $total_cc_45_49 + $cat_sc_45_49;
		$total_cate_50_54 = $total_cc_50_54 + $cat_sc_50_54;
		$total_cate_55_59 = $total_cc_55_59 + $cat_sc_55_59;
		$total_cate_60_64 = $total_cc_60_64 + $cat_sc_60_64;
		$total_cate_65_69 = $total_cc_65_69 + $cat_sc_65_69;
		$total_cate_70_74 = $total_cc_70_74 + $cat_sc_70_74;
		$total_cate_75_79 = $total_cc_75_79 + $cat_sc_75_79;
		$total_cate_80_m = $total_cc_80_m + $cat_sc_80_m;

		$total_cate = $total_cate_0_4 + $total_cate_5_9 + $total_cate_10_14 + $total_cate_15_19 + $total_cate_20_24 + $total_cate_25_29 + $total_cate_30_34 + $total_cate_35_39 + $total_cate_40_44 + $total_cate_45_49 + $total_cate_50_54 + $total_cate_55_59 + $total_cate_60_64 + $total_cate_65_69 + $total_cate_70_74 + $total_cate_75_79 + $total_cate_80_m;

		$total_c1 = $cat_c1_0_4 + $cat_c1_5_9 + $cat_c1_10_14 + $cat_c1_15_19 + $cat_c1_20_24 + $cat_c1_25_29 + $cat_c1_30_34 + $cat_c1_35_39 + $cat_c1_40_44 + $cat_c1_45_49 + $cat_c1_50_54 + $cat_c1_55_59 + $cat_c1_60_64 + $cat_c1_65_69 + $cat_c1_70_74 + $cat_c1_75_79 + $cat_c1_80_m;
		$total_c2 = $cat_c2_0_4 + $cat_c2_5_9 + $cat_c2_10_14 + $cat_c2_15_19 + $cat_c2_20_24 + $cat_c2_25_29 + $cat_c2_30_34 + $cat_c2_35_39 + $cat_c2_40_44 + $cat_c2_45_49 + $cat_c2_50_54 + $cat_c2_55_59 + $cat_c2_60_64 + $cat_c2_65_69 + $cat_c2_70_74 + $cat_c2_75_79 + $cat_c2_80_m;
		$total_c3 = $cat_c3_0_4 + $cat_c3_5_9 + $cat_c3_10_14 + $cat_c3_15_19 + $cat_c3_20_24 + $cat_c3_25_29 + $cat_c3_30_34 + $cat_c3_35_39 + $cat_c3_40_44 + $cat_c3_45_49 + $cat_c3_50_54 + $cat_c3_55_59 + $cat_c3_60_64 + $cat_c3_65_69 + $cat_c3_70_74 + $cat_c3_75_79 + $cat_c3_80_m;
		$total_c4 = $cat_c4_0_4 + $cat_c4_5_9 + $cat_c4_10_14 + $cat_c4_15_19 + $cat_c4_20_24 + $cat_c4_25_29 + $cat_c4_30_34 + $cat_c4_35_39 + $cat_c4_40_44 + $cat_c4_45_49 + $cat_c4_50_54 + $cat_c4_55_59 + $cat_c4_60_64 + $cat_c4_65_69 + $cat_c4_70_74 + $cat_c4_75_79 + $cat_c4_80_m;
		$total_c5 = $cat_c5_0_4 + $cat_c5_5_9 + $cat_c5_10_14 + $cat_c5_15_19 + $cat_c5_20_24 + $cat_c5_25_29 + $cat_c5_30_34 + $cat_c5_35_39 + $cat_c5_40_44 + $cat_c5_45_49 + $cat_c5_50_54 + $cat_c5_55_59 + $cat_c5_60_64 + $cat_c5_65_69 + $cat_c5_70_74 + $cat_c5_75_79 + $cat_c5_80_m;
		$total_csi = $cat_csi_0_4 + $cat_csi_5_9 + $cat_csi_10_14 + $cat_csi_15_19 + $cat_csi_20_24 + $cat_csi_25_29 + $cat_csi_30_34 + $cat_csi_35_39 + $cat_csi_40_44 + $cat_csi_45_49 + $cat_csi_50_54 + $cat_csi_55_59 + $cat_csi_60_64 + $cat_csi_65_69 + $cat_csi_70_74 + $cat_csi_75_79 + $cat_csi_80_m;
		$total_sc = $cat_sc_0_4 + $cat_sc_5_9 + $cat_sc_10_14 + $cat_sc_15_19 + $cat_sc_20_24 + $cat_sc_25_29 + $cat_sc_30_34 + $cat_sc_35_39 + $cat_sc_40_44 + $cat_sc_45_49 + $cat_sc_50_54 + $cat_sc_55_59 + $cat_sc_60_64 + $cat_sc_65_69 + $cat_sc_70_74 + $cat_sc_75_79 + $cat_sc_80_m;

		$total_espe_0_15 = $medi_0_15 + $pedi_0_15 + $trau_0_15 + $neur_0_15 + $neci_0_15 + $psiq_0_15;
		$total_espe_15_m = $medi_15_m + $pedi_15_m + $trau_15_m + $neur_15_m + $neci_15_m + $psiq_15_m;

		$total_medi = $medi_0_15 + $medi_15_m;
		$total_pedi = $pedi_0_15 + $pedi_15_m;
		$total_trau = $trau_0_15 + $trau_15_m;
		$total_neur = $neur_0_15 + $neur_15_m;
		$total_neci = $neci_0_15 + $neci_15_m;
		$total_psiq = $psiq_0_15 + $psiq_15_m;
		$total_espe = $total_espe_0_15 + $total_espe_15_m;

		$hosp_tot_urg = $h_urg_0_12 + $h_urg_12_24 + $h_urg_24_m + $h_rech_hosp + $h_otro_est + $h_ueh + $h_fall_espera;

		$c1h = $cat_c1_0_4_h+$cat_c1_5_9_h+$cat_c1_10_14_h+$cat_c1_15_19_h+$cat_c1_20_24_h+$cat_c1_25_29_h+$cat_c1_30_34_h+$cat_c1_35_39_h+$cat_c1_40_44_h+$cat_c1_45_49_h+$cat_c1_50_54_h+$cat_c1_55_59_h+$cat_c1_60_64_h+$cat_c1_65_69_h+$cat_c1_70_74_h+$cat_c1_75_79_h+$cat_c1_80_m_h;
		$c1m = $cat_c1_0_4_m+$cat_c1_5_9_m+$cat_c1_10_14_m+$cat_c1_15_19_m+$cat_c1_20_24_m+$cat_c1_25_29_m+$cat_c1_30_34_m+$cat_c1_35_39_m+$cat_c1_40_44_m+$cat_c1_45_49_m+$cat_c1_50_54_m+$cat_c1_55_59_m+$cat_c1_60_64_m+$cat_c1_65_69_m+$cat_c1_70_74_m+$cat_c1_75_79_m+$cat_c1_80_m_m;

		$c2h = $cat_c2_0_4_h+$cat_c2_5_9_h+$cat_c2_10_14_h+$cat_c2_15_19_h+$cat_c2_20_24_h+$cat_c2_25_29_h+$cat_c2_30_34_h+$cat_c2_35_39_h+$cat_c2_40_44_h+$cat_c2_45_49_h+$cat_c2_50_54_h+$cat_c2_55_59_h+$cat_c2_60_64_h+$cat_c2_65_69_h+$cat_c2_70_74_h+$cat_c2_75_79_h+$cat_c2_80_m_h;
		$c2m = $cat_c2_0_4_m+$cat_c2_5_9_m+$cat_c2_10_14_m+$cat_c2_15_19_m+$cat_c2_20_24_m+$cat_c2_25_29_m+$cat_c2_30_34_m+$cat_c2_35_39_m+$cat_c2_40_44_m+$cat_c2_45_49_m+$cat_c2_50_54_m+$cat_c2_55_59_m+$cat_c2_60_64_m+$cat_c2_65_69_m+$cat_c2_70_74_m+$cat_c2_75_79_m+$cat_c2_80_m_m;
		
		$c3h = $cat_c3_0_4_h+$cat_c3_5_9_h+$cat_c3_10_14_h+$cat_c3_15_19_h+$cat_c3_20_24_h+$cat_c3_25_29_h+$cat_c3_30_34_h+$cat_c3_35_39_h+$cat_c3_40_44_h+$cat_c3_45_49_h+$cat_c3_50_54_h+$cat_c3_55_59_h+$cat_c3_60_64_h+$cat_c3_65_69_h+$cat_c3_70_74_h+$cat_c3_75_79_h+$cat_c3_80_m_h;
		$c3m = $cat_c3_0_4_m+$cat_c3_5_9_m+$cat_c3_10_14_m+$cat_c3_15_19_m+$cat_c3_20_24_m+$cat_c3_25_29_m+$cat_c3_30_34_m+$cat_c3_35_39_m+$cat_c3_40_44_m+$cat_c3_45_49_m+$cat_c3_50_54_m+$cat_c3_55_59_m+$cat_c3_60_64_m+$cat_c3_65_69_m+$cat_c3_70_74_m+$cat_c3_75_79_m+$cat_c3_80_m_m;

		$c4h = $cat_c4_0_4_h+$cat_c4_5_9_h+$cat_c4_10_14_h+$cat_c4_15_19_h+$cat_c4_20_24_h+$cat_c4_25_29_h+$cat_c4_30_34_h+$cat_c4_35_39_h+$cat_c4_40_44_h+$cat_c4_45_49_h+$cat_c4_50_54_h+$cat_c4_55_59_h+$cat_c4_60_64_h+$cat_c4_65_69_h+$cat_c4_70_74_h+$cat_c4_75_79_h+$cat_c4_80_m_h;
		$c4m = $cat_c4_0_4_m+$cat_c4_5_9_m+$cat_c4_10_14_m+$cat_c4_15_19_m+$cat_c4_20_24_m+$cat_c4_25_29_m+$cat_c4_30_34_m+$cat_c4_35_39_m+$cat_c4_40_44_m+$cat_c4_45_49_m+$cat_c4_50_54_m+$cat_c4_55_59_m+$cat_c4_60_64_m+$cat_c4_65_69_m+$cat_c4_70_74_m+$cat_c4_75_79_m+$cat_c4_80_m_m;

		$c5h = $cat_c5_0_4_h+$cat_c5_5_9_h+$cat_c5_10_14_h+$cat_c5_15_19_h+$cat_c5_20_24_h+$cat_c5_25_29_h+$cat_c5_30_34_h+$cat_c5_35_39_h+$cat_c5_40_44_h+$cat_c5_45_49_h+$cat_c5_50_54_h+$cat_c5_55_59_h+$cat_c5_60_64_h+$cat_c5_65_69_h+$cat_c5_70_74_h+$cat_c5_75_79_h+$cat_c5_80_m_h;
		$c5m = $cat_c5_0_4_m+$cat_c5_5_9_m+$cat_c5_10_14_m+$cat_c5_15_19_m+$cat_c5_20_24_m+$cat_c5_25_29_m+$cat_c5_30_34_m+$cat_c5_35_39_m+$cat_c5_40_44_m+$cat_c5_45_49_m+$cat_c5_50_54_m+$cat_c5_55_59_m+$cat_c5_60_64_m+$cat_c5_65_69_m+$cat_c5_70_74_m+$cat_c5_75_79_m+$cat_c5_80_m_m;

		$sch = $cat_csi_0_4_h+$cat_csi_5_9_h+$cat_csi_10_14_h+$cat_csi_15_19_h+$cat_csi_20_24_h+$cat_csi_25_29_h+$cat_csi_30_34_h+$cat_csi_35_39_h+$cat_csi_40_44_h+$cat_csi_45_49_h+$cat_csi_50_54_h+$cat_csi_55_59_h+$cat_csi_60_64_h+$cat_csi_65_69_h+$cat_csi_70_74_h+$cat_csi_75_79_h+$cat_csi_80_m_h;
		$scm = $cat_csi_0_4_m+$cat_csi_5_9_m+$cat_csi_10_14_m+$cat_csi_15_19_m+$cat_csi_20_24_m+$cat_csi_25_29_m+$cat_csi_30_34_m+$cat_csi_35_39_m+$cat_csi_40_44_m+$cat_csi_45_49_m+$cat_csi_50_54_m+$cat_csi_55_59_m+$cat_csi_60_64_m+$cat_csi_65_69_m+$cat_csi_70_74_m+$cat_csi_75_79_m+$cat_csi_80_m_m;

		$tch = $c1h+$c2h+$c3h+$c4h+$c5h+$sch;
		$tcm = $c1m+$c2m+$c3m+$c4m+$c5m+$scm;

		$tsch = $cat_sc_0_4_h+$cat_sc_5_9_h+$cat_sc_10_14_h+$cat_sc_15_19_h+$cat_sc_20_24_h+$cat_sc_25_29_h+$cat_sc_30_34_h+$cat_sc_35_39_h+$cat_sc_40_44_h+$cat_sc_45_49_h+$cat_sc_50_54_h+$cat_sc_55_59_h+$cat_sc_60_64_h+$cat_sc_65_69_h+$cat_sc_70_74_h+$cat_sc_75_79_h+$cat_sc_80_m_h;
		$tscm = $cat_sc_0_4_m+$cat_sc_5_9_m+$cat_sc_10_14_m+$cat_sc_15_19_m+$cat_sc_20_24_m+$cat_sc_25_29_m+$cat_sc_30_34_m+$cat_sc_35_39_m+$cat_sc_40_44_m+$cat_sc_45_49_m+$cat_sc_50_54_m+$cat_sc_55_59_m+$cat_sc_60_64_m+$cat_sc_65_69_m+$cat_sc_70_74_m+$cat_sc_75_79_m+$cat_sc_80_m_m;

		$total_cate_0_4_h = $cat_c1_0_4_h+$cat_c2_0_4_h+$cat_c3_0_4_h+$cat_c4_0_4_h+$cat_c5_0_4_h+$cat_csi_0_4_h;
		$total_cate_0_4_m = $cat_c1_0_4_m+$cat_c2_0_4_m+$cat_c3_0_4_m+$cat_c4_0_4_m+$cat_c5_0_4_m+$cat_csi_0_4_m;

		$total_cate_5_9_h = $cat_c1_5_9_h+$cat_c2_5_9_h+$cat_c3_5_9_h+$cat_c4_5_9_h+$cat_c5_5_9_h+$cat_csi_5_9_h;
		$total_cate_5_9_m = $cat_c1_5_9_m+$cat_c2_5_9_m+$cat_c3_5_9_m+$cat_c4_5_9_m+$cat_c5_5_9_m+$cat_csi_5_9_m;

		$total_cate_10_14_h = $cat_c1_10_14_h+$cat_c2_10_14_h+$cat_c3_10_14_h+$cat_c4_10_14_h+$cat_c5_10_14_h+$cat_csi_10_14_h;
		$total_cate_10_14_m = $cat_c1_10_14_m+$cat_c2_10_14_m+$cat_c3_10_14_m+$cat_c4_10_14_m+$cat_c5_10_14_m+$cat_csi_10_14_m;

		$total_cate_15_19_h = $cat_c1_15_19_h+$cat_c2_15_19_h+$cat_c3_15_19_h+$cat_c4_15_19_h+$cat_c5_15_19_h+$cat_csi_15_19_h;
		$total_cate_15_19_m = $cat_c1_15_19_m+$cat_c2_15_19_m+$cat_c3_15_19_m+$cat_c4_15_19_m+$cat_c5_15_19_m+$cat_csi_15_19_m;

		$total_cate_20_24_h = $cat_c1_20_24_h+$cat_c2_20_24_h+$cat_c3_20_24_h+$cat_c4_20_24_h+$cat_c5_20_24_h+$cat_csi_20_24_h;
		$total_cate_20_24_m = $cat_c1_20_24_m+$cat_c2_20_24_m+$cat_c3_20_24_m+$cat_c4_20_24_m+$cat_c5_20_24_m+$cat_csi_20_24_m;

		$total_cate_25_29_h = $cat_c1_25_29_h+$cat_c2_25_29_h+$cat_c3_25_29_h+$cat_c4_25_29_h+$cat_c5_25_29_h+$cat_csi_25_29_h;
		$total_cate_25_29_m = $cat_c1_25_29_m+$cat_c2_25_29_m+$cat_c3_25_29_m+$cat_c4_25_29_m+$cat_c5_25_29_m+$cat_csi_25_29_m;

		$total_cate_30_34_h = $cat_c1_30_34_h+$cat_c2_30_34_h+$cat_c3_30_34_h+$cat_c4_30_34_h+$cat_c5_30_34_h+$cat_csi_30_34_h;
		$total_cate_30_34_m = $cat_c1_30_34_m+$cat_c2_30_34_m+$cat_c3_30_34_m+$cat_c4_30_34_m+$cat_c5_30_34_m+$cat_csi_30_34_m;

		$total_cate_35_39_h = $cat_c1_35_39_h+$cat_c2_35_39_h+$cat_c3_35_39_h+$cat_c4_35_39_h+$cat_c5_35_39_h+$cat_csi_35_39_h;
		$total_cate_35_39_m = $cat_c1_35_39_m+$cat_c2_35_39_m+$cat_c3_35_39_m+$cat_c4_35_39_m+$cat_c5_35_39_m+$cat_csi_35_39_m;

		$total_cate_40_44_h = $cat_c1_40_44_h+$cat_c2_40_44_h+$cat_c3_40_44_h+$cat_c4_40_44_h+$cat_c5_40_44_h+$cat_csi_40_44_h;
		$total_cate_40_44_m = $cat_c1_40_44_m+$cat_c2_40_44_m+$cat_c3_40_44_m+$cat_c4_40_44_m+$cat_c5_40_44_m+$cat_csi_40_44_m;

		$total_cate_45_49_h = $cat_c1_45_49_h+$cat_c2_45_49_h+$cat_c3_45_49_h+$cat_c4_45_49_h+$cat_c5_45_49_h+$cat_csi_45_49_h;
		$total_cate_45_49_m = $cat_c1_45_49_m+$cat_c2_45_49_m+$cat_c3_45_49_m+$cat_c4_45_49_m+$cat_c5_45_49_m+$cat_csi_45_49_m;

		$total_cate_50_54_h = $cat_c1_50_54_h+$cat_c2_50_54_h+$cat_c3_50_54_h+$cat_c4_50_54_h+$cat_c5_50_54_h+$cat_csi_50_54_h;
		$total_cate_50_54_m = $cat_c1_50_54_m+$cat_c2_50_54_m+$cat_c3_50_54_m+$cat_c4_50_54_m+$cat_c5_50_54_m+$cat_csi_50_54_m;

		$total_cate_55_59_h = $cat_c1_55_59_h+$cat_c2_55_59_h+$cat_c3_55_59_h+$cat_c4_55_59_h+$cat_c5_55_59_h+$cat_csi_55_59_h;
		$total_cate_55_59_m = $cat_c1_55_59_m+$cat_c2_55_59_m+$cat_c3_55_59_m+$cat_c4_55_59_m+$cat_c5_55_59_m+$cat_csi_55_59_m;

		$total_cate_60_64_h = $cat_c1_60_64_h+$cat_c2_60_64_h+$cat_c3_60_64_h+$cat_c4_60_64_h+$cat_c5_60_64_h+$cat_csi_60_64_h;
		$total_cate_60_64_m = $cat_c1_60_64_m+$cat_c2_60_64_m+$cat_c3_60_64_m+$cat_c4_60_64_m+$cat_c5_60_64_m+$cat_csi_60_64_m;

		$total_cate_65_69_h = $cat_c1_65_69_h+$cat_c2_65_69_h+$cat_c3_65_69_h+$cat_c4_65_69_h+$cat_c5_65_69_h+$cat_csi_65_69_h;
		$total_cate_65_69_m = $cat_c1_65_69_m+$cat_c2_65_69_m+$cat_c3_65_69_m+$cat_c4_65_69_m+$cat_c5_65_69_m+$cat_csi_65_69_m;

		$total_cate_70_74_h = $cat_c1_70_74_h+$cat_c2_70_74_h+$cat_c3_70_74_h+$cat_c4_70_74_h+$cat_c5_70_74_h+$cat_csi_70_74_h;
		$total_cate_70_74_m = $cat_c1_70_74_m+$cat_c2_70_74_m+$cat_c3_70_74_m+$cat_c4_70_74_m+$cat_c5_70_74_m+$cat_csi_70_74_m;

		$total_cate_75_79_h = $cat_c1_75_79_h+$cat_c2_75_79_h+$cat_c3_75_79_h+$cat_c4_75_79_h+$cat_c5_75_79_h+$cat_csi_75_79_h;
		$total_cate_75_79_m = $cat_c1_75_79_m+$cat_c2_75_79_m+$cat_c3_75_79_m+$cat_c4_75_79_m+$cat_c5_75_79_m+$cat_csi_75_79_m;

		$total_cate_80_m_h = $cat_c1_80_m_h+$cat_c2_80_m_h+$cat_c3_80_m_h+$cat_c4_80_m_h+$cat_c5_80_m_h+$cat_csi_80_m_h;
		$total_cate_80_m_m = $cat_c1_80_m_m+$cat_c2_80_m_m+$cat_c3_80_m_m+$cat_c4_80_m_m+$cat_c5_80_m_m+$cat_csi_80_m_m;

		/*-------------------------------------------------*/
		$total_cc_0_4_h = $cat_c1_0_4_h+$cat_c2_0_4_h+$cat_c3_0_4_h+$cat_c4_0_4_h+$cat_c5_0_4_h;
		$total_cc_0_4_m = $cat_c1_0_4_m+$cat_c2_0_4_m+$cat_c3_0_4_m+$cat_c4_0_4_m+$cat_c5_0_4_m;

		$total_cc_5_9_h = $cat_c1_5_9_h+$cat_c2_5_9_h+$cat_c3_5_9_h+$cat_c4_5_9_h+$cat_c5_5_9_h;
		$total_cc_5_9_m = $cat_c1_5_9_m+$cat_c2_5_9_m+$cat_c3_5_9_m+$cat_c4_5_9_m+$cat_c5_5_9_m;

		$total_cc_10_14_h = $cat_c1_10_14_h+$cat_c2_10_14_h+$cat_c3_10_14_h+$cat_c4_10_14_h+$cat_c5_10_14_h;
		$total_cc_10_14_m = $cat_c1_10_14_m+$cat_c2_10_14_m+$cat_c3_10_14_m+$cat_c4_10_14_m+$cat_c5_10_14_m;

		$total_cc_15_19_h = $cat_c1_15_19_h+$cat_c2_15_19_h+$cat_c3_15_19_h+$cat_c4_15_19_h+$cat_c5_15_19_h;
		$total_cc_15_19_m = $cat_c1_15_19_m+$cat_c2_15_19_m+$cat_c3_15_19_m+$cat_c4_15_19_m+$cat_c5_15_19_m;

		$total_cc_20_24_h = $cat_c1_20_24_h+$cat_c2_20_24_h+$cat_c3_20_24_h+$cat_c4_20_24_h+$cat_c5_20_24_h;
		$total_cc_20_24_m = $cat_c1_20_24_m+$cat_c2_20_24_m+$cat_c3_20_24_m+$cat_c4_20_24_m+$cat_c5_20_24_m;

		$total_cc_25_29_h = $cat_c1_25_29_h+$cat_c2_25_29_h+$cat_c3_25_29_h+$cat_c4_25_29_h+$cat_c5_25_29_h;
		$total_cc_25_29_m = $cat_c1_25_29_m+$cat_c2_25_29_m+$cat_c3_25_29_m+$cat_c4_25_29_m+$cat_c5_25_29_m;

		$total_cc_30_34_h = $cat_c1_30_34_h+$cat_c2_30_34_h+$cat_c3_30_34_h+$cat_c4_30_34_h+$cat_c5_30_34_h;
		$total_cc_30_34_m = $cat_c1_30_34_m+$cat_c2_30_34_m+$cat_c3_30_34_m+$cat_c4_30_34_m+$cat_c5_30_34_m;

		$total_cc_35_39_h = $cat_c1_35_39_h+$cat_c2_35_39_h+$cat_c3_35_39_h+$cat_c4_35_39_h+$cat_c5_35_39_h;
		$total_cc_35_39_m = $cat_c1_35_39_m+$cat_c2_35_39_m+$cat_c3_35_39_m+$cat_c4_35_39_m+$cat_c5_35_39_m;

		$total_cc_40_44_h = $cat_c1_40_44_h+$cat_c2_40_44_h+$cat_c3_40_44_h+$cat_c4_40_44_h+$cat_c5_40_44_h;
		$total_cc_40_44_m = $cat_c1_40_44_m+$cat_c2_40_44_m+$cat_c3_40_44_m+$cat_c4_40_44_m+$cat_c5_40_44_m;

		$total_cc_45_49_h = $cat_c1_45_49_h+$cat_c2_45_49_h+$cat_c3_45_49_h+$cat_c4_45_49_h+$cat_c5_45_49_h;
		$total_cc_45_49_m = $cat_c1_45_49_m+$cat_c2_45_49_m+$cat_c3_45_49_m+$cat_c4_45_49_m+$cat_c5_45_49_m;

		$total_cc_50_54_h = $cat_c1_50_54_h+$cat_c2_50_54_h+$cat_c3_50_54_h+$cat_c4_50_54_h+$cat_c5_50_54_h;
		$total_cc_50_54_m = $cat_c1_50_54_m+$cat_c2_50_54_m+$cat_c3_50_54_m+$cat_c4_50_54_m+$cat_c5_50_54_m;

		$total_cc_55_59_h = $cat_c1_55_59_h+$cat_c2_55_59_h+$cat_c3_55_59_h+$cat_c4_55_59_h+$cat_c5_55_59_h;
		$total_cc_55_59_m = $cat_c1_55_59_m+$cat_c2_55_59_m+$cat_c3_55_59_m+$cat_c4_55_59_m+$cat_c5_55_59_m;

		$total_cc_60_64_h = $cat_c1_60_64_h+$cat_c2_60_64_h+$cat_c3_60_64_h+$cat_c4_60_64_h+$cat_c5_60_64_h;
		$total_cc_60_64_m = $cat_c1_60_64_m+$cat_c2_60_64_m+$cat_c3_60_64_m+$cat_c4_60_64_m+$cat_c5_60_64_m;

		$total_cc_65_69_h = $cat_c1_65_69_h+$cat_c2_65_69_h+$cat_c3_65_69_h+$cat_c4_65_69_h+$cat_c5_65_69_h;
		$total_cc_65_69_m = $cat_c1_65_69_m+$cat_c2_65_69_m+$cat_c3_65_69_m+$cat_c4_65_69_m+$cat_c5_65_69_m;

		$total_cc_70_74_h = $cat_c1_70_74_h+$cat_c2_70_74_h+$cat_c3_70_74_h+$cat_c4_70_74_h+$cat_c5_70_74_h;
		$total_cc_70_74_m = $cat_c1_70_74_m+$cat_c2_70_74_m+$cat_c3_70_74_m+$cat_c4_70_74_m+$cat_c5_70_74_m;

		$total_cc_75_79_h = $cat_c1_75_79_h+$cat_c2_75_79_h+$cat_c3_75_79_h+$cat_c4_75_79_h+$cat_c5_75_79_h;
		$total_cc_75_79_m = $cat_c1_75_79_m+$cat_c2_75_79_m+$cat_c3_75_79_m+$cat_c4_75_79_m+$cat_c5_75_79_m;

		$total_cc_80_m_h = $cat_c1_80_m_h+$cat_c2_80_m_h+$cat_c3_80_m_h+$cat_c4_80_m_h+$cat_c5_80_m_h;
		$total_cc_80_m_m = $cat_c1_80_m_m+$cat_c2_80_m_m+$cat_c3_80_m_m+$cat_c4_80_m_m+$cat_c5_80_m_m;
		/*---------------------------------------------------*/

		$tcateh = $total_cate_0_4_h+$total_cate_5_9_h+$total_cate_10_14_h+$total_cate_15_19_h+$total_cate_20_24_h+$total_cate_25_29_h+$total_cate_30_34_h+$total_cate_35_39_h+$total_cate_40_44_h+$total_cate_45_49_h+$total_cate_50_54_h+$total_cate_55_59_h+$total_cate_60_64_h+$total_cate_65_69_h+$total_cate_70_74_h+$total_cate_75_79_h+$total_cate_80_m_h;
		$tcatem = $total_cate_0_4_m+$total_cate_5_9_m+$total_cate_10_14_m+$total_cate_15_19_m+$total_cate_20_24_m+$total_cate_25_29_m+$total_cate_30_34_m+$total_cate_35_39_m+$total_cate_40_44_m+$total_cate_45_49_m+$total_cate_50_54_m+$total_cate_55_59_m+$total_cate_60_64_m+$total_cate_65_69_m+$total_cate_70_74_m+$total_cate_75_79_m+$total_cate_80_m_m;



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
				<td width="71%">
					<table td width="50%" align="left" border="">
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
						
						
						<tr >
							'.$fechaHoy.'
						</tr>

						<tr>
							
						</tr>
					</table>
				</td>
			</tr>
		</table>
					<td s colspan="3" align="center" class="foliochico"><strong class="titulotabla">REM A-08 Período: '.$_POST['fechaInicio'].' AL '.$_POST['fechaFin'].'</strong></td>
				</tr>
				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" align="left" class="foliochico"><strong class="titulotabla">SECCIÓN A.1: ATENCIONES REALIZADAS EN UNIDADES DE UEH DE HOSPITALES DE ALTA COMPLEJIDAD</strong></td>
				</tr>
				<tr>
					<td colspan="3" align="center"><table style="font-size: 24px;" width="950" border="1" cellpadding="2" cellspacing="0" class="reporte" name="tablaexport" id="tablaexport">
						
						<tr align="center" valign="top">
							<td valign="bottom" bgcolor="#CCCCCC" rowspan="3" width="50"><strong>TIPO DE ATENCION</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" rowspan="3" width="35"><strong>TOTAL</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" colspan="34" width="476"><strong>GRUPOS DE EDAD (en años)</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="90"><strong>SEXO</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" rowspan="3" width="50"><strong>A BENEFICI- ARIOS</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" colspan="3" width="157"><strong>ORIGEN DE LA PROCEDENCIA (Solo pacientes derivados de establecimientos de la Red)</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" rowspan="3" width="50"><strong>Estableci-mientos de otra Red</strong></td>
						</tr>
						<tr align="center" valign="top">
							<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>00 04</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>05 09</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>10 14</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>15 19</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>20 24</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>25 29</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>30 34</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>35 39</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>40 44</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>45 49</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>50 54</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>55 59</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>60 64</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>65 69</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>70 74</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>75 79</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>80 y Más</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" rowspan="2" width="45"><strong>Hombres</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" rowspan="2" width="45"><strong>Mujeres</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" rowspan="2" width="35"><strong>SAPU</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" rowspan="2" width="55"><strong>Hospital Baja Complejidad</strong></td>
							<td valign="bottom" bgcolor="#CCCCCC" rowspan="2" width="67"><strong>Otros Estableci- mientos de la Red</strong></td>
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
							<td align="left" valign="bottom" bgcolor="#CCCCCC" rowspan="2"><strong>ATENCIÓN MÉDICA NIÑO Y ADULTO</strong></td>

							<td align="right" rowspan="2"> <strong>'.$total_an.'</strong> </td>
							<td align="right" colspan="2">'.$at_an_0_4.'</td>
							<td align="right" colspan="2">'.$at_an_5_9.'</td>
							<td align="right" colspan="2">'.$at_an_10_14.'</td>
							<td align="right" colspan="2">'.$at_an_15_19.'</td>
							<td align="right" colspan="2">'.$at_an_20_24.'</td>
							<td align="right" colspan="2">'.$at_an_25_29.'</td>
							<td align="right" colspan="2">'.$at_an_30_34.'</td>
							<td align="right" colspan="2">'.$at_an_35_39.'</td>
							<td align="right" colspan="2">'.$at_an_40_44.'</td>
							<td align="right" colspan="2">'.$at_an_45_49.'</td>
							<td align="right" colspan="2">'.$at_an_50_54.'</td>
							<td align="right" colspan="2">'.$at_an_55_59.'</td>
							<td align="right" colspan="2">'.$at_an_60_64.'</td>
							<td align="right" colspan="2">'.$at_an_65_69.'</td>
							<td align="right" colspan="2">'.$at_an_70_74.'</td>
							<td align="right" colspan="2">'.$at_an_75_79.'</td>
							<td align="right" colspan="2">'.$at_an_80_m.'</td>

							<td align="right" rowspan="2">'.$at_an_h.'</td>
							<td align="right" rowspan="2">'.$at_an_m.'</td>
							<td align="right" rowspan="2">'.$at_an_ben.'</td>
							<td align="right" rowspan="2">'.$at_an_sapu.'</td>
							<td align="right" rowspan="2">'.$at_an_bc.'</td>
							<td align="right" rowspan="2">'.$at_an_otr.'</td>
							<td align="right" rowspan="2">XXX</td>

						</tr>
						<tr>
							<td align="right">'.$at_an_0_4_h.'</td>
							<td align="right">'.$at_an_0_4_m.'</td>
							<td align="right">'.$at_an_5_9_h.'</td>
							<td align="right">'.$at_an_5_9_m.'</td>
							<td align="right">'.$at_an_10_14_h.'</td>
							<td align="right">'.$at_an_10_14_m.'</td>
							<td align="right">'.$at_an_15_19_h.'</td>
							<td align="right">'.$at_an_15_19_m.'</td>
							<td align="right">'.$at_an_20_24_h.'</td>
							<td align="right">'.$at_an_20_24_m.'</td>
							<td align="right">'.$at_an_25_29_h.'</td>
							<td align="right">'.$at_an_25_29_m.'</td>
							<td align="right">'.$at_an_30_34_h.'</td>
							<td align="right">'.$at_an_30_34_m.'</td>
							<td align="right">'.$at_an_35_39_h.'</td>
							<td align="right">'.$at_an_35_39_m.'</td>
							<td align="right">'.$at_an_40_44_h.'</td>
							<td align="right">'.$at_an_40_44_m.'</td>
							<td align="right">'.$at_an_45_49_h.'</td>
							<td align="right">'.$at_an_45_49_m.'</td>
							<td align="right">'.$at_an_50_54_h.'</td>
							<td align="right">'.$at_an_50_54_m.'</td>
							<td align="right">'.$at_an_55_59_h.'</td>
							<td align="right">'.$at_an_55_59_m.'</td>
							<td align="right">'.$at_an_60_64_h.'</td>
							<td align="right">'.$at_an_60_64_m.'</td>
							<td align="right">'.$at_an_65_69_h.'</td>
							<td align="right">'.$at_an_65_69_m.'</td>
							<td align="right">'.$at_an_70_74_h.'</td>
							<td align="right">'.$at_an_70_74_m.'</td>
							<td align="right">'.$at_an_75_79_h.'</td>
							<td align="right">'.$at_an_75_79_m.'</td>
							<td align="right">'.$at_an_80_m_h.'</td>
							<td align="right">'.$at_an_80_m_m.'</td>

						</tr>
						<tr align="center" valign="top">
							<td align="left" valign="bottom" bgcolor="#CCCCCC" rowspan="2"><strong>ATENCIÓN MÉDICA GINECO-OBSTETRA</strong></td>
							<td align="right" rowspan="2"> <strong>'.$total_gi.'</strong> </td>
							<td align="right" colspan="2">'.$at_gi_0_4.'</td>
							<td align="right" colspan="2">'.$at_gi_5_9.'</td>
							<td align="right" colspan="2">'.$at_gi_10_14.'</td>
							<td align="right" colspan="2">'.$at_gi_15_19.'</td>
							<td align="right" colspan="2">'.$at_gi_20_24.'</td>
							<td align="right" colspan="2">'.$at_gi_25_29.'</td>
							<td align="right" colspan="2">'.$at_gi_30_34.'</td>
							<td align="right" colspan="2">'.$at_gi_35_39.'</td>
							<td align="right" colspan="2">'.$at_gi_40_44.'</td>
							<td align="right" colspan="2">'.$at_gi_45_49.'</td>
							<td align="right" colspan="2">'.$at_gi_50_54.'</td>
							<td align="right" colspan="2">'.$at_gi_55_59.'</td>
							<td align="right" colspan="2">'.$at_gi_60_64.'</td>
							<td align="right" colspan="2">'.$at_gi_65_69.'</td>
							<td align="right" colspan="2">'.$at_gi_70_74.'</td>
							<td align="right" colspan="2">'.$at_gi_75_79.'</td>
							<td align="right" colspan="2">'.$at_gi_80_m.'</td>

							<td align="right" rowspan="2">'.$at_gi_h.'</td>
							<td align="right" rowspan="2">'.$at_gi_m.'</td>
							<td align="right" rowspan="2">'.$at_gi_ben.'</td>
							<td align="right" rowspan="2">'.$at_gi_sapu.'</td>
							<td align="right" rowspan="2">'.$at_gi_bc.'</td>
							<td align="right" rowspan="2">'.$at_gi_otr.'</td>
							<td align="right" rowspan="2">XXX</td>
						</tr>
						<tr>
							<td align="right">'.$at_gi_0_4_h.'</td>
							<td align="right">'.$at_gi_0_4_m.'</td>
							<td align="right">'.$at_gi_5_9_h.'</td>
							<td align="right">'.$at_gi_5_9_m.'</td>
							<td align="right">'.$at_gi_10_14_h.'</td>
							<td align="right">'.$at_gi_10_14_m.'</td>
							<td align="right">'.$at_gi_15_19_h.'</td>
							<td align="right">'.$at_gi_15_19_m.'</td>
							<td align="right">'.$at_gi_20_24_h.'</td>
							<td align="right">'.$at_gi_20_24_m.'</td>
							<td align="right">'.$at_gi_25_29_h.'</td>
							<td align="right">'.$at_gi_25_29_m.'</td>
							<td align="right">'.$at_gi_30_34_h.'</td>
							<td align="right">'.$at_gi_30_34_m.'</td>
							<td align="right">'.$at_gi_35_39_h.'</td>
							<td align="right">'.$at_gi_35_39_m.'</td>
							<td align="right">'.$at_gi_40_44_h.'</td>
							<td align="right">'.$at_gi_40_44_m.'</td>
							<td align="right">'.$at_gi_45_49_h.'</td>
							<td align="right">'.$at_gi_45_49_m.'</td>
							<td align="right">'.$at_gi_50_54_h.'</td>
							<td align="right">'.$at_gi_50_54_m.'</td>
							<td align="right">'.$at_gi_55_59_h.'</td>
							<td align="right">'.$at_gi_55_59_m.'</td>
							<td align="right">'.$at_gi_60_64_h.'</td>
							<td align="right">'.$at_gi_60_64_m.'</td>
							<td align="right">'.$at_gi_65_69_h.'</td>
							<td align="right">'.$at_gi_65_69_m.'</td>
							<td align="right">'.$at_gi_70_74_h.'</td>
							<td align="right">'.$at_gi_70_74_m.'</td>
							<td align="right">'.$at_gi_75_79_h.'</td>
							<td align="right">'.$at_gi_75_79_m.'</td>
							<td align="right">'.$at_gi_80_m_h.'</td>
							<td align="right">'.$at_gi_80_m_m.'</td>
						</tr>
						<tr align="center" valign="top">
							<td align="left" valign="bottom" bgcolor="#CCCCCC" rowspan="2"><strong>ATENCIÓN POR MATRONA</strong></td>

							<td align="right" rowspan="2"><strong>'.$total_ma.'</strong></td>
							<td align="right" colspan="2">'.$at_ma_0_4.'</td>
							<td align="right" colspan="2">'.$at_ma_5_9.'</td>
							<td align="right" colspan="2">'.$at_ma_10_14.'</td>
							<td align="right" colspan="2">'.$at_ma_15_19.'</td>
							<td align="right" colspan="2">'.$at_ma_20_24.'</td>
							<td align="right" colspan="2">'.$at_ma_25_29.'</td>
							<td align="right" colspan="2">'.$at_ma_30_34.'</td>
							<td align="right" colspan="2">'.$at_ma_35_39.'</td>
							<td align="right" colspan="2">'.$at_ma_40_44.'</td>
							<td align="right" colspan="2">'.$at_ma_45_49.'</td>
							<td align="right" colspan="2">'.$at_ma_50_54.'</td>
							<td align="right" colspan="2">'.$at_ma_55_59.'</td>
							<td align="right" colspan="2">'.$at_ma_60_64.'</td>
							<td align="right" colspan="2">'.$at_ma_65_69.'</td>
							<td align="right" colspan="2">'.$at_ma_70_74.'</td>
							<td align="right" colspan="2">'.$at_ma_75_79.'</td>
							<td align="right" colspan="2">'.$at_ma_80_m.'</td>

							<td align="right" rowspan="2">'.$at_ma_h.'</td>
							<td align="right" rowspan="2">'.$at_ma_m.'</td>
							<td align="right" rowspan="2">'.$at_ma_ben.'</td>
							<td align="right" rowspan="2">'.$at_ma_sapu.'</td>
							<td align="right" rowspan="2">'.$at_ma_bc.'</td>
							<td align="right" rowspan="2">'.$at_ma_otr.'</td>
							<td align="right" rowspan="2">XXX</td>

						</tr>
						<tr>

							<td align="right">'.$at_ma_0_4_h.'</td>
							<td align="right">'.$at_ma_0_4_m.'</td>
							<td align="right">'.$at_ma_5_9_h.'</td>
							<td align="right">'.$at_ma_5_9_m.'</td>
							<td align="right">'.$at_ma_10_14_h.'</td>
							<td align="right">'.$at_ma_10_14_m.'</td>
							<td align="right">'.$at_ma_15_19_h.'</td>
							<td align="right">'.$at_ma_15_19_m.'</td>
							<td align="right">'.$at_ma_20_24_h.'</td>
							<td align="right">'.$at_ma_20_24_m.'</td>
							<td align="right">'.$at_ma_25_29_h.'</td>
							<td align="right">'.$at_ma_25_29_m.'</td>
							<td align="right">'.$at_ma_30_34_h.'</td>
							<td align="right">'.$at_ma_30_34_m.'</td>
							<td align="right">'.$at_ma_35_39_h.'</td>
							<td align="right">'.$at_ma_35_39_m.'</td>
							<td align="right">'.$at_ma_40_44_h.'</td>
							<td align="right">'.$at_ma_40_44_m.'</td>
							<td align="right">'.$at_ma_45_49_h.'</td>
							<td align="right">'.$at_ma_45_49_m.'</td>
							<td align="right">'.$at_ma_50_54_h.'</td>
							<td align="right">'.$at_ma_50_54_m.'</td>
							<td align="right">'.$at_ma_55_59_h.'</td>
							<td align="right">'.$at_ma_55_59_m.'</td>
							<td align="right">'.$at_ma_60_64_h.'</td>
							<td align="right">'.$at_ma_60_64_m.'</td>
							<td align="right">'.$at_ma_65_69_h.'</td>
							<td align="right">'.$at_ma_65_69_m.'</td>
							<td align="right">'.$at_ma_70_74_h.'</td>
							<td align="right">'.$at_ma_70_74_m.'</td>
							<td align="right">'.$at_ma_75_79_h.'</td>
							<td align="right">'.$at_ma_75_79_m.'</td>
							<td align="right">'.$at_ma_80_m_h.'</td>
							<td align="right">'.$at_ma_80_m_m.'</td>					
						</tr>
					</table></td>
				</tr>
				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="3" align="left" class="foliochico"><strong class="titulotabla">SECCIÓN A.2: CATEGORIZACIÓN DE PACIENTES, PREVIA A LA ATENCION MÉDICA</strong></td>
				</tr>
				<tr>
					<td colspan="3" align="left">
						<table style="font-size: 24px;" width="950" border="1" cellpadding="2" cellspacing="0" class="reporte" name="tablaexport" id="tablaexport2">
							<tr align="center" valign="top">
								<td valign="bottom" bgcolor="#CCCCCC" rowspan="2" width="65"><strong>CATEGORÍAS</strong></td>
								<td valign="bottom" bgcolor="#CCCCCC" rowspan="2" width="35"><strong>TOTAL</strong></td>
								<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>00 04</strong></td>
								<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>05 09</strong></td>
								<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>10 14</strong></td>
								<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>15 19</strong></td>
								<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>20 24</strong></td>
								<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>25 29</strong></td>
								<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>30 34</strong></td>
								<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>35 39</strong></td>
								<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>40 44</strong></td>
								<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>45 49</strong></td>
								<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>50 54</strong></td>
								<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>55 59</strong></td>
								<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>60 64</strong></td>
								<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>65 69</strong></td>
								<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>70 74</strong></td>
								<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>75 79</strong></td>
								<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="28"><strong>80 y Más</strong></td>
								<td valign="bottom" bgcolor="#CCCCCC" colspan="2" width="75"><strong>SEXO</strong></td>
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
								<td valign="bottom" bgcolor="#CCCCCC"><strong>Hombre</strong></td>
								<td valign="bottom" bgcolor="#CCCCCC"><strong>Mujer</strong></td>
							</tr>
							<tr align="center" valign="top">
								<td valign="bottom" bgcolor="#CCCCCC" rowspan="2"><strong>C1</strong></td>

								<td align="right" rowspan="2"> <strong>'.$total_c1.'</strong> </td>
								<td align="right" colspan="2">'.$cat_c1_0_4.'</td>
								<td align="right" colspan="2">'.$cat_c1_5_9.'</td>
								<td align="right" colspan="2">'.$cat_c1_10_14.'</td>
								<td align="right" colspan="2">'.$cat_c1_15_19.'</td>
								<td align="right" colspan="2">'.$cat_c1_20_24.'</td>
								<td align="right" colspan="2">'.$cat_c1_25_29.'</td>
								<td align="right" colspan="2">'.$cat_c1_30_34.'</td>
								<td align="right" colspan="2">'.$cat_c1_35_39.'</td>
								<td align="right" colspan="2">'.$cat_c1_40_44.'</td>
								<td align="right" colspan="2">'.$cat_c1_45_49.'</td>
								<td align="right" colspan="2">'.$cat_c1_50_54.'</td>
								<td align="right" colspan="2">'.$cat_c1_55_59.'</td>
								<td align="right" colspan="2">'.$cat_c1_60_64.'</td>
								<td align="right" colspan="2">'.$cat_c1_65_69.'</td>
								<td align="right" colspan="2">'.$cat_c1_70_74.'</td>
								<td align="right" colspan="2">'.$cat_c1_75_79.'</td>
								<td align="right" colspan="2">'.$cat_c1_80_m.'</td>

								<td align="right" rowspan="2">'.$c1h.'</td>
								<td align="right" rowspan="2">'.$c1m.'</td>

							</tr>
							<tr>

								<td align="right">'.$cat_c1_0_4_h.'</td>
								<td align="right">'.$cat_c1_0_4_m.'</td>
								<td align="right">'.$cat_c1_5_9_h.'</td>
								<td align="right">'.$cat_c1_5_9_m.'</td>
								<td align="right">'.$cat_c1_10_14_h.'</td>
								<td align="right">'.$cat_c1_10_14_m.'</td>
								<td align="right">'.$cat_c1_15_19_h.'</td>
								<td align="right">'.$cat_c1_15_19_m.'</td>
								<td align="right">'.$cat_c1_20_24_h.'</td>
								<td align="right">'.$cat_c1_20_24_m.'</td>
								<td align="right">'.$cat_c1_25_29_h.'</td>
								<td align="right">'.$cat_c1_25_29_m.'</td>
								<td align="right">'.$cat_c1_30_34_h.'</td>
								<td align="right">'.$cat_c1_30_34_m.'</td>
								<td align="right">'.$cat_c1_35_39_h.'</td>
								<td align="right">'.$cat_c1_35_39_m.'</td>
								<td align="right">'.$cat_c1_40_44_h.'</td>
								<td align="right">'.$cat_c1_40_44_m.'</td>
								<td align="right">'.$cat_c1_45_49_h.'</td>
								<td align="right">'.$cat_c1_45_49_m.'</td>
								<td align="right">'.$cat_c1_50_54_h.'</td>
								<td align="right">'.$cat_c1_50_54_m.'</td>
								<td align="right">'.$cat_c1_55_59_h.'</td>
								<td align="right">'.$cat_c1_55_59_m.'</td>
								<td align="right">'.$cat_c1_60_64_h.'</td>
								<td align="right">'.$cat_c1_60_64_m.'</td>
								<td align="right">'.$cat_c1_65_69_h.'</td>
								<td align="right">'.$cat_c1_65_69_m.'</td>
								<td align="right">'.$cat_c1_70_74_h.'</td>
								<td align="right">'.$cat_c1_70_74_m.'</td>
								<td align="right">'.$cat_c1_75_79_h.'</td>
								<td align="right">'.$cat_c1_75_79_m.'</td>
								<td align="right">'.$cat_c1_80_m_h.'</td>
								<td align="right">'.$cat_c1_80_m_m.'</td>						
							</tr>
							<tr align="center" valign="top">
								<td height="21" valign="bottom" bgcolor="#CCCCCC" rowspan="2"><strong>C2</strong></td>

								<td align="right" rowspan="2"> <strong>'.$total_c2.'</strong> </td>
								<td align="right" colspan="2">'.$cat_c2_0_4.'</td>
								<td align="right" colspan="2">'.$cat_c2_5_9.'</td>
								<td align="right" colspan="2">'.$cat_c2_10_14.'</td>
								<td align="right" colspan="2">'.$cat_c2_15_19.'</td>
								<td align="right" colspan="2">'.$cat_c2_20_24.'</td>
								<td align="right" colspan="2">'.$cat_c2_25_29.'</td>
								<td align="right" colspan="2">'.$cat_c2_30_34.'</td>
								<td align="right" colspan="2">'.$cat_c2_35_39.'</td>
								<td align="right" colspan="2">'.$cat_c2_40_44.'</td>
								<td align="right" colspan="2">'.$cat_c2_45_49.'</td>
								<td align="right" colspan="2">'.$cat_c2_50_54.'</td>
								<td align="right" colspan="2">'.$cat_c2_55_59.'</td>
								<td align="right" colspan="2">'.$cat_c2_60_64.'</td>
								<td align="right" colspan="2">'.$cat_c2_65_69.'</td>
								<td align="right" colspan="2">'.$cat_c2_70_74.'</td>
								<td align="right" colspan="2">'.$cat_c2_75_79.'</td>
								<td align="right" colspan="2">'.$cat_c2_80_m.'</td>

								<td align="right" rowspan="2">'.$c2h.'</td>
								<td align="right" rowspan="2">'.$c2m.'</td>

							</tr>
							<tr>

								<td align="right">'.$cat_c2_0_4_h.'</td>
								<td align="right">'.$cat_c2_0_4_m.'</td>
								<td align="right">'.$cat_c2_5_9_h.'</td>
								<td align="right">'.$cat_c2_5_9_m.'</td>
								<td align="right">'.$cat_c2_10_14_h.'</td>
								<td align="right">'.$cat_c2_10_14_m.'</td>
								<td align="right">'.$cat_c2_15_19_h.'</td>
								<td align="right">'.$cat_c2_15_19_m.'</td>
								<td align="right">'.$cat_c2_20_24_h.'</td>
								<td align="right">'.$cat_c2_20_24_m.'</td>
								<td align="right">'.$cat_c2_25_29_h.'</td>
								<td align="right">'.$cat_c2_25_29_m.'</td>
								<td align="right">'.$cat_c2_30_34_h.'</td>
								<td align="right">'.$cat_c2_30_34_m.'</td>
								<td align="right">'.$cat_c2_35_39_h.'</td>
								<td align="right">'.$cat_c2_35_39_m.'</td>
								<td align="right">'.$cat_c2_40_44_h.'</td>
								<td align="right">'.$cat_c2_40_44_m.'</td>
								<td align="right">'.$cat_c2_45_49_h.'</td>
								<td align="right">'.$cat_c2_45_49_m.'</td>
								<td align="right">'.$cat_c2_50_54_h.'</td>
								<td align="right">'.$cat_c2_50_54_m.'</td>
								<td align="right">'.$cat_c2_55_59_h.'</td>
								<td align="right">'.$cat_c2_55_59_m.'</td>
								<td align="right">'.$cat_c2_60_64_h.'</td>
								<td align="right">'.$cat_c2_60_64_m.'</td>
								<td align="right">'.$cat_c2_65_69_h.'</td>
								<td align="right">'.$cat_c2_65_69_m.'</td>
								<td align="right">'.$cat_c2_70_74_h.'</td>
								<td align="right">'.$cat_c2_70_74_m.'</td>
								<td align="right">'.$cat_c2_75_79_h.'</td>
								<td align="right">'.$cat_c2_75_79_m.'</td>
								<td align="right">'.$cat_c2_80_m_h.'</td>
								<td align="right">'.$cat_c2_80_m_m.'</td>

							</tr>
							<tr align="center" valign="top">
								<td valign="bottom" bgcolor="#CCCCCC" rowspan="2"><strong>C3</strong></td>

								<td align="right" rowspan="2"> <strong>'.$total_c3.'</strong> </td>
								<td align="right" colspan="2">'.$cat_c3_0_4.'</td>
								<td align="right" colspan="2">'.$cat_c3_5_9.'</td>
								<td align="right" colspan="2">'.$cat_c3_10_14.'</td>
								<td align="right" colspan="2">'.$cat_c3_15_19.'</td>
								<td align="right" colspan="2">'.$cat_c3_20_24.'</td>
								<td align="right" colspan="2">'.$cat_c3_25_29.'</td>
								<td align="right" colspan="2">'.$cat_c3_30_34.'</td>
								<td align="right" colspan="2">'.$cat_c3_35_39.'</td>
								<td align="right" colspan="2">'.$cat_c3_40_44.'</td>
								<td align="right" colspan="2">'.$cat_c3_45_49.'</td>
								<td align="right" colspan="2">'.$cat_c3_50_54.'</td>
								<td align="right" colspan="2">'.$cat_c3_55_59.'</td>
								<td align="right" colspan="2">'.$cat_c3_60_64.'</td>
								<td align="right" colspan="2">'.$cat_c3_65_69.'</td>
								<td align="right" colspan="2">'.$cat_c3_70_74.'</td>
								<td align="right" colspan="2">'.$cat_c3_75_79.'</td>
								<td align="right" colspan="2">'.$cat_c3_80_m.'</td>

								<td align="right" rowspan="2">'.$c3h.'</td>
								<td align="right" rowspan="2">'.$c3m.'</td>

							</tr>
							<tr>

								<td align="right">'.$cat_c3_0_4_h.'</td>
								<td align="right">'.$cat_c3_0_4_m.'</td>
								<td align="right">'.$cat_c3_5_9_h.'</td>
								<td align="right">'.$cat_c3_5_9_m.'</td>
								<td align="right">'.$cat_c3_10_14_h.'</td>
								<td align="right">'.$cat_c3_10_14_m.'</td>
								<td align="right">'.$cat_c3_15_19_h.'</td>
								<td align="right">'.$cat_c3_15_19_m.'</td>
								<td align="right">'.$cat_c3_20_24_h.'</td>
								<td align="right">'.$cat_c3_20_24_m.'</td>
								<td align="right">'.$cat_c3_25_29_h.'</td>
								<td align="right">'.$cat_c3_25_29_m.'</td>
								<td align="right">'.$cat_c3_30_34_h.'</td>
								<td align="right">'.$cat_c3_30_34_m.'</td>
								<td align="right">'.$cat_c3_35_39_h.'</td>
								<td align="right">'.$cat_c3_35_39_m.'</td>
								<td align="right">'.$cat_c3_40_44_h.'</td>
								<td align="right">'.$cat_c3_40_44_m.'</td>
								<td align="right">'.$cat_c3_45_49_h.'</td>
								<td align="right">'.$cat_c3_45_49_m.'</td>
								<td align="right">'.$cat_c3_50_54_h.'</td>
								<td align="right">'.$cat_c3_50_54_m.'</td>
								<td align="right">'.$cat_c3_55_59_h.'</td>
								<td align="right">'.$cat_c3_55_59_m.'</td>
								<td align="right">'.$cat_c3_60_64_h.'</td>
								<td align="right">'.$cat_c3_60_64_m.'</td>
								<td align="right">'.$cat_c3_65_69_h.'</td>
								<td align="right">'.$cat_c3_65_69_m.'</td>
								<td align="right">'.$cat_c3_70_74_h.'</td>
								<td align="right">'.$cat_c3_70_74_m.'</td>
								<td align="right">'.$cat_c3_75_79_h.'</td>
								<td align="right">'.$cat_c3_75_79_m.'</td>
								<td align="right">'.$cat_c3_80_m_h.'</td>
								<td align="right">'.$cat_c3_80_m_m.'</td>

							</tr>
							<tr align="center" valign="top">
								<td valign="bottom" bgcolor="#CCCCCC" rowspan="2"><strong>C4</strong></td>

								<td align="right" rowspan="2"> <strong>'.$total_c4.'</strong> </td>
								<td align="right" colspan="2">'.$cat_c4_0_4.'</td>
								<td align="right" colspan="2">'.$cat_c4_5_9.'</td>
								<td align="right" colspan="2">'.$cat_c4_10_14.'</td>
								<td align="right" colspan="2">'.$cat_c4_15_19.'</td>
								<td align="right" colspan="2">'.$cat_c4_20_24.'</td>
								<td align="right" colspan="2">'.$cat_c4_25_29.'</td>
								<td align="right" colspan="2">'.$cat_c4_30_34.'</td>
								<td align="right" colspan="2">'.$cat_c4_35_39.'</td>
								<td align="right" colspan="2">'.$cat_c4_40_44.'</td>
								<td align="right" colspan="2">'.$cat_c4_45_49.'</td>
								<td align="right" colspan="2">'.$cat_c4_50_54.'</td>
								<td align="right" colspan="2">'.$cat_c4_55_59.'</td>
								<td align="right" colspan="2">'.$cat_c4_60_64.'</td>
								<td align="right" colspan="2">'.$cat_c4_65_69.'</td>
								<td align="right" colspan="2">'.$cat_c4_70_74.'</td>
								<td align="right" colspan="2">'.$cat_c4_75_79.'</td>
								<td align="right" colspan="2">'.$cat_c4_80_m.'</td>

								<td align="right" rowspan="2">'.$c4h.'</td>
								<td align="right" rowspan="2">'.$c4m.'</td>

							</tr>
							<tr>

								<td align="right">'.$cat_c4_0_4_h.'</td>
								<td align="right">'.$cat_c4_0_4_m.'</td>
								<td align="right">'.$cat_c4_5_9_h.'</td>
								<td align="right">'.$cat_c4_5_9_m.'</td>
								<td align="right">'.$cat_c4_10_14_h.'</td>
								<td align="right">'.$cat_c4_10_14_m.'</td>
								<td align="right">'.$cat_c4_15_19_h.'</td>
								<td align="right">'.$cat_c4_15_19_m.'</td>
								<td align="right">'.$cat_c4_20_24_h.'</td>
								<td align="right">'.$cat_c4_20_24_m.'</td>
								<td align="right">'.$cat_c4_25_29_h.'</td>
								<td align="right">'.$cat_c4_25_29_m.'</td>
								<td align="right">'.$cat_c4_30_34_h.'</td>
								<td align="right">'.$cat_c4_30_34_m.'</td>
								<td align="right">'.$cat_c4_35_39_h.'</td>
								<td align="right">'.$cat_c4_35_39_m.'</td>
								<td align="right">'.$cat_c4_40_44_h.'</td>
								<td align="right">'.$cat_c4_40_44_m.'</td>
								<td align="right">'.$cat_c4_45_49_h.'</td>
								<td align="right">'.$cat_c4_45_49_m.'</td>
								<td align="right">'.$cat_c4_50_54_h.'</td>
								<td align="right">'.$cat_c4_50_54_m.'</td>
								<td align="right">'.$cat_c4_55_59_h.'</td>
								<td align="right">'.$cat_c4_55_59_m.'</td>
								<td align="right">'.$cat_c4_60_64_h.'</td>
								<td align="right">'.$cat_c4_60_64_m.'</td>
								<td align="right">'.$cat_c4_65_69_h.'</td>
								<td align="right">'.$cat_c4_65_69_m.'</td>
								<td align="right">'.$cat_c4_70_74_h.'</td>
								<td align="right">'.$cat_c4_70_74_m.'</td>
								<td align="right">'.$cat_c4_75_79_h.'</td>
								<td align="right">'.$cat_c4_75_79_m.'</td>
								<td align="right">'.$cat_c4_80_m_h.'</td>
								<td align="right">'.$cat_c4_80_m_m.'</td>

							</tr>
							<tr align="center" valign="top">
								<td valign="bottom" bgcolor="#CCCCCC" rowspan="2"><strong>C5</strong></td>

								<td align="right" rowspan="2"> <strong>'.$total_c5.'</strong> </td>
								<td align="right" colspan="2">'.$cat_c5_0_4.'</td>
								<td align="right" colspan="2">'.$cat_c5_5_9.'</td>
								<td align="right" colspan="2">'.$cat_c5_10_14.'</td>
								<td align="right" colspan="2">'.$cat_c5_15_19.'</td>
								<td align="right" colspan="2">'.$cat_c5_20_24.'</td>
								<td align="right" colspan="2">'.$cat_c5_25_29.'</td>
								<td align="right" colspan="2">'.$cat_c5_30_34.'</td>
								<td align="right" colspan="2">'.$cat_c5_35_39.'</td>
								<td align="right" colspan="2">'.$cat_c5_40_44.'</td>
								<td align="right" colspan="2">'.$cat_c5_45_49.'</td>
								<td align="right" colspan="2">'.$cat_c5_50_54.'</td>
								<td align="right" colspan="2">'.$cat_c5_55_59.'</td>
								<td align="right" colspan="2">'.$cat_c5_60_64.'</td>
								<td align="right" colspan="2">'.$cat_c5_65_69.'</td>
								<td align="right" colspan="2">'.$cat_c5_70_74.'</td>
								<td align="right" colspan="2">'.$cat_c5_75_79.'</td>
								<td align="right" colspan="2">'.$cat_c5_80_m.'</td>

								<td align="right" rowspan="2">'.$c5h.'</td>
								<td align="right" rowspan="2">'.$c5m.'</td>

							</tr>
							<tr>

								<td align="right">'.$cat_c5_0_4_h.'</td>
								<td align="right">'.$cat_c5_0_4_m.'</td>
								<td align="right">'.$cat_c5_5_9_h.'</td>
								<td align="right">'.$cat_c5_5_9_m.'</td>
								<td align="right">'.$cat_c5_10_14_h.'</td>
								<td align="right">'.$cat_c5_10_14_m.'</td>
								<td align="right">'.$cat_c5_15_19_h.'</td>
								<td align="right">'.$cat_c5_15_19_m.'</td>
								<td align="right">'.$cat_c5_20_24_h.'</td>
								<td align="right">'.$cat_c5_20_24_m.'</td>
								<td align="right">'.$cat_c5_25_29_h.'</td>
								<td align="right">'.$cat_c5_25_29_m.'</td>
								<td align="right">'.$cat_c5_30_34_h.'</td>
								<td align="right">'.$cat_c5_30_34_m.'</td>
								<td align="right">'.$cat_c5_35_39_h.'</td>
								<td align="right">'.$cat_c5_35_39_m.'</td>
								<td align="right">'.$cat_c5_40_44_h.'</td>
								<td align="right">'.$cat_c5_40_44_m.'</td>
								<td align="right">'.$cat_c5_45_49_h.'</td>
								<td align="right">'.$cat_c5_45_49_m.'</td>
								<td align="right">'.$cat_c5_50_54_h.'</td>
								<td align="right">'.$cat_c5_50_54_m.'</td>
								<td align="right">'.$cat_c5_55_59_h.'</td>
								<td align="right">'.$cat_c5_55_59_m.'</td>
								<td align="right">'.$cat_c5_60_64_h.'</td>
								<td align="right">'.$cat_c5_60_64_m.'</td>
								<td align="right">'.$cat_c5_65_69_h.'</td>
								<td align="right">'.$cat_c5_65_69_m.'</td>
								<td align="right">'.$cat_c5_70_74_h.'</td>
								<td align="right">'.$cat_c5_70_74_m.'</td>
								<td align="right">'.$cat_c5_75_79_h.'</td>
								<td align="right">'.$cat_c5_75_79_m.'</td>
								<td align="right">'.$cat_c5_80_m_h.'</td>
								<td align="right">'.$cat_c5_80_m_m.'</td>

							</tr>
							<tr align="center" valign="top">
								<td valign="bottom" bgcolor="#CCCCCC" rowspan="2"><strong>C - Sin Información</strong></td>

								<td align="right" rowspan="2"> <strong>'.$total_csi.'</strong> </td>
								<td align="right" colspan="2">'.$cat_csi_0_4.'</td>
								<td align="right" colspan="2">'.$cat_csi_5_9.'</td>
								<td align="right" colspan="2">'.$cat_csi_10_14.'</td>
								<td align="right" colspan="2">'.$cat_csi_15_19.'</td>
								<td align="right" colspan="2">'.$cat_csi_20_24.'</td>
								<td align="right" colspan="2">'.$cat_csi_25_29.'</td>
								<td align="right" colspan="2">'.$cat_csi_30_34.'</td>
								<td align="right" colspan="2">'.$cat_csi_35_39.'</td>
								<td align="right" colspan="2">'.$cat_csi_40_44.'</td>
								<td align="right" colspan="2">'.$cat_csi_45_49.'</td>
								<td align="right" colspan="2">'.$cat_csi_50_54.'</td>
								<td align="right" colspan="2">'.$cat_csi_55_59.'</td>
								<td align="right" colspan="2">'.$cat_csi_60_64.'</td>
								<td align="right" colspan="2">'.$cat_csi_65_69.'</td>
								<td align="right" colspan="2">'.$cat_csi_70_74.'</td>
								<td align="right" colspan="2">'.$cat_csi_75_79.'</td>
								<td align="right" colspan="2">'.$cat_csi_80_m.'</td>

								<td align="right" rowspan="2">'.$sch.'</td>
								<td align="right" rowspan="2">'.$scm.'</td>

							</tr>
							<tr>

								<td align="right">'.$cat_csi_0_4_h.'</td>
								<td align="right">'.$cat_csi_0_4_m.'</td>
								<td align="right">'.$cat_csi_5_9_h.'</td>
								<td align="right">'.$cat_csi_5_9_m.'</td>
								<td align="right">'.$cat_csi_10_14_h.'</td>
								<td align="right">'.$cat_csi_10_14_m.'</td>
								<td align="right">'.$cat_csi_15_19_h.'</td>
								<td align="right">'.$cat_csi_15_19_m.'</td>
								<td align="right">'.$cat_csi_20_24_h.'</td>
								<td align="right">'.$cat_csi_20_24_m.'</td>
								<td align="right">'.$cat_csi_25_29_h.'</td>
								<td align="right">'.$cat_csi_25_29_m.'</td>
								<td align="right">'.$cat_csi_30_34_h.'</td>
								<td align="right">'.$cat_csi_30_34_m.'</td>
								<td align="right">'.$cat_csi_35_39_h.'</td>
								<td align="right">'.$cat_csi_35_39_m.'</td>
								<td align="right">'.$cat_csi_40_44_h.'</td>
								<td align="right">'.$cat_csi_40_44_m.'</td>
								<td align="right">'.$cat_csi_45_49_h.'</td>
								<td align="right">'.$cat_csi_45_49_m.'</td>
								<td align="right">'.$cat_csi_50_54_h.'</td>
								<td align="right">'.$cat_csi_50_54_m.'</td>
								<td align="right">'.$cat_csi_55_59_h.'</td>
								<td align="right">'.$cat_csi_55_59_m.'</td>
								<td align="right">'.$cat_csi_60_64_h.'</td>
								<td align="right">'.$cat_csi_60_64_m.'</td>
								<td align="right">'.$cat_csi_65_69_h.'</td>
								<td align="right">'.$cat_csi_65_69_m.'</td>
								<td align="right">'.$cat_csi_70_74_h.'</td>
								<td align="right">'.$cat_csi_70_74_m.'</td>
								<td align="right">'.$cat_csi_75_79_h.'</td>
								<td align="right">'.$cat_csi_75_79_m.'</td>
								<td align="right">'.$cat_csi_80_m_h.'</td>
								<td align="right">'.$cat_csi_80_m_m.'</td>
								
							</tr>
							<tr align="center" valign="top">
								<td valign="bottom" bgcolor="#CCCCCC" rowspan="2"><strong>Total Categorizados</strong></td>

								<td align="right" bgcolor="#CCCCCC" rowspan="2"> <strong>'.$total_con_cate.'</strong> </td>
								<td align="right" colspan="2">'.$total_cc_0_4.'</td>
								<td align="right" colspan="2">'.$total_cc_5_9.'</td>
								<td align="right" colspan="2">'.$total_cc_10_14.'</td>
								<td align="right" colspan="2">'.$total_cc_15_19.'</td>
								<td align="right" colspan="2">'.$total_cc_20_24.'</td>
								<td align="right" colspan="2">'.$total_cc_25_29.'</td>
								<td align="right" colspan="2">'.$total_cc_30_34.'</td>
								<td align="right" colspan="2">'.$total_cc_35_39.'</td>
								<td align="right" colspan="2">'.$total_cc_40_44.'</td>
								<td align="right" colspan="2">'.$total_cc_45_49.'</td>
								<td align="right" colspan="2">'.$total_cc_50_54.'</td>
								<td align="right" colspan="2">'.$total_cc_55_59.'</td>
								<td align="right" colspan="2">'.$total_cc_60_64.'</td>
								<td align="right" colspan="2">'.$total_cc_65_69.'</td>
								<td align="right" colspan="2">'.$total_cc_70_74.'</td>
								<td align="right" colspan="2">'.$total_cc_75_79.'</td>
								<td align="right" colspan="2">'.$total_cc_80_m.'</td>

								<td align="right" rowspan="2">'.$tch.'</td>
								<td align="right" rowspan="2">'.$tcm.'</td>
								
							</tr>
							<tr>

								<td align="right">'.$total_cc_0_4_h.'</td>
								<td align="right">'.$total_cc_0_4_m.'</td>
								<td align="right">'.$total_cc_5_9_h.'</td>
								<td align="right">'.$total_cc_5_9_m.'</td>
								<td align="right">'.$total_cc_10_14_h.'</td>
								<td align="right">'.$total_cc_10_14_m.'</td>
								<td align="right">'.$total_cc_15_19_h.'</td>
								<td align="right">'.$total_cc_15_19_m.'</td>
								<td align="right">'.$total_cc_20_24_h.'</td>
								<td align="right">'.$total_cc_20_24_m.'</td>
								<td align="right">'.$total_cc_25_29_h.'</td>
								<td align="right">'.$total_cc_25_29_m.'</td>
								<td align="right">'.$total_cc_30_34_h.'</td>
								<td align="right">'.$total_cc_30_34_m.'</td>
								<td align="right">'.$total_cc_35_39_h.'</td>
								<td align="right">'.$total_cc_35_39_m.'</td>
								<td align="right">'.$total_cc_40_44_h.'</td>
								<td align="right">'.$total_cc_40_44_m.'</td>
								<td align="right">'.$total_cc_45_49_h.'</td>
								<td align="right">'.$total_cc_45_49_m.'</td>
								<td align="right">'.$total_cc_50_54_h.'</td>
								<td align="right">'.$total_cc_50_54_m.'</td>
								<td align="right">'.$total_cc_55_59_h.'</td>
								<td align="right">'.$total_cc_55_59_m.'</td>
								<td align="right">'.$total_cc_60_64_h.'</td>
								<td align="right">'.$total_cc_60_64_m.'</td>
								<td align="right">'.$total_cc_65_69_h.'</td>
								<td align="right">'.$total_cc_65_69_m.'</td>
								<td align="right">'.$total_cc_70_74_h.'</td>
								<td align="right">'.$total_cc_70_74_m.'</td>
								<td align="right">'.$total_cc_75_79_h.'</td>
								<td align="right">'.$total_cc_75_79_m.'</td>
								<td align="right">'.$total_cc_80_m_h.'</td>
								<td align="right">'.$total_cc_80_m_m.'</td>
								
							</tr>
							<tr align="center" valign="top">
								<td valign="bottom" bgcolor="#CCCCCC" rowspan="2"><strong>SIN CATEGORIZACIÓN</strong></td>
								
								<td align="right" rowspan="2"> <strong>'.$total_sc.'</strong> </td>
								<td align="right" colspan="2">'.$cat_sc_0_4.'</td>
								<td align="right" colspan="2">'.$cat_sc_5_9.'</td>	
								<td align="right" colspan="2">'.$cat_sc_10_14.'</td>	
								<td align="right" colspan="2">'.$cat_sc_15_19.'</td>	
								<td align="right" colspan="2">'.$cat_sc_20_24.'</td>	
								<td align="right" colspan="2">'.$cat_sc_25_29.'</td>	
								<td align="right" colspan="2">'.$cat_sc_30_34.'</td>	
								<td align="right" colspan="2">'.$cat_sc_35_39.'</td>	
								<td align="right" colspan="2">'.$cat_sc_40_44.'</td>	
								<td align="right" colspan="2">'.$cat_sc_45_49.'</td>	
								<td align="right" colspan="2">'.$cat_sc_50_54.'</td>	
								<td align="right" colspan="2">'.$cat_sc_55_59.'</td>	
								<td align="right" colspan="2">'.$cat_sc_60_64.'</td>	
								<td align="right" colspan="2">'.$cat_sc_65_69.'</td>	
								<td align="right" colspan="2">'.$cat_sc_70_74.'</td>	
								<td align="right" colspan="2">'.$cat_sc_75_79.'</td>	
								<td align="right" colspan="2">'.$cat_sc_80_m.'</td>

								<td align="right" rowspan="2">'.$tsch.'</td>
								<td align="right" rowspan="2">'.$tscm.'</td>

							</tr>
							<tr>

								<td align="right">'.$cat_sc_0_4_h.'</td>
								<td align="right">'.$cat_sc_0_4_m.'</td>
								<td align="right">'.$cat_sc_5_9_h.'</td>
								<td align="right">'.$cat_sc_5_9_m.'</td>
								<td align="right">'.$cat_sc_10_14_h.'</td>
								<td align="right">'.$cat_sc_10_14_m.'</td>
								<td align="right">'.$cat_sc_15_19_h.'</td>
								<td align="right">'.$cat_sc_15_19_m.'</td>
								<td align="right">'.$cat_sc_20_24_h.'</td>
								<td align="right">'.$cat_sc_20_24_m.'</td>
								<td align="right">'.$cat_sc_25_29_h.'</td>
								<td align="right">'.$cat_sc_25_29_m.'</td>
								<td align="right">'.$cat_sc_30_34_h.'</td>
								<td align="right">'.$cat_sc_30_34_m.'</td>
								<td align="right">'.$cat_sc_35_39_h.'</td>
								<td align="right">'.$cat_sc_35_39_m.'</td>
								<td align="right">'.$cat_sc_40_44_h.'</td>
								<td align="right">'.$cat_sc_40_44_m.'</td>
								<td align="right">'.$cat_sc_45_49_h.'</td>
								<td align="right">'.$cat_sc_45_49_m.'</td>
								<td align="right">'.$cat_sc_50_54_h.'</td>
								<td align="right">'.$cat_sc_50_54_m.'</td>
								<td align="right">'.$cat_sc_55_59_h.'</td>
								<td align="right">'.$cat_sc_55_59_m.'</td>
								<td align="right">'.$cat_sc_60_64_h.'</td>
								<td align="right">'.$cat_sc_60_64_m.'</td>
								<td align="right">'.$cat_sc_65_69_h.'</td>
								<td align="right">'.$cat_sc_65_69_m.'</td>
								<td align="right">'.$cat_sc_70_74_h.'</td>
								<td align="right">'.$cat_sc_70_74_m.'</td>
								<td align="right">'.$cat_sc_75_79_h.'</td>
								<td align="right">'.$cat_sc_75_79_m.'</td>
								<td align="right">'.$cat_sc_80_m_h.'</td>
								<td align="right">'.$cat_sc_80_m_m.'</td>

							</tr>
							<tr align="center" valign="top">
								<td valign="bottom" bgcolor="#CCCCCC" rowspan="2"><strong>TOTAL</strong></td>

								<td align="right" bgcolor="#CCCCCC" rowspan="2"> <strong>'.$total_cate.'</strong> </td>
								<td align="right" colspan="2">'.$total_cate_0_4.'</td>
								<td align="right" colspan="2">'.$total_cate_5_9.'</td>	
								<td align="right" colspan="2">'.$total_cate_10_14.'</td>	
								<td align="right" colspan="2">'.$total_cate_15_19.'</td>	
								<td align="right" colspan="2">'.$total_cate_20_24.'</td>	
								<td align="right" colspan="2">'.$total_cate_25_29.'</td>	
								<td align="right" colspan="2">'.$total_cate_30_34.'</td>	
								<td align="right" colspan="2">'.$total_cate_35_39.'</td>	
								<td align="right" colspan="2">'.$total_cate_40_44.'</td>	
								<td align="right" colspan="2">'.$total_cate_45_49.'</td>	
								<td align="right" colspan="2">'.$total_cate_50_54.'</td>	
								<td align="right" colspan="2">'.$total_cate_55_59.'</td>	
								<td align="right" colspan="2">'.$total_cate_60_64.'</td>	
								<td align="right" colspan="2">'.$total_cate_65_69.'</td>	
								<td align="right" colspan="2">'.$total_cate_70_74.'</td>	
								<td align="right" colspan="2">'.$total_cate_75_79.'</td>	
								<td align="right" colspan="2">'.$total_cate_80_m.'</td>

								<td align="right" rowspan="2">'.$tcateh.'</td>
								<td align="right" rowspan="2">'.$tcatem.'</td>

							</tr>
							<tr>

								<td align="right">'.$total_cate_0_4_h.'</td>
								<td align="right">'.$total_cate_0_4_m.'</td>
								<td align="right">'.$total_cate_5_9_h.'</td>
								<td align="right">'.$total_cate_5_9_m.'</td>
								<td align="right">'.$total_cate_10_14_h.'</td>
								<td align="right">'.$total_cate_10_14_m.'</td>
								<td align="right">'.$total_cate_15_19_h.'</td>
								<td align="right">'.$total_cate_15_19_m.'</td>
								<td align="right">'.$total_cate_20_24_h.'</td>
								<td align="right">'.$total_cate_20_24_m.'</td>
								<td align="right">'.$total_cate_25_29_h.'</td>
								<td align="right">'.$total_cate_25_29_m.'</td>
								<td align="right">'.$total_cate_30_34_h.'</td>
								<td align="right">'.$total_cate_30_34_m.'</td>
								<td align="right">'.$total_cate_35_39_h.'</td>
								<td align="right">'.$total_cate_35_39_m.'</td>
								<td align="right">'.$total_cate_40_44_h.'</td>
								<td align="right">'.$total_cate_40_44_m.'</td>
								<td align="right">'.$total_cate_45_49_h.'</td>
								<td align="right">'.$total_cate_45_49_m.'</td>
								<td align="right">'.$total_cate_50_54_h.'</td>
								<td align="right">'.$total_cate_50_54_m.'</td>
								<td align="right">'.$total_cate_55_59_h.'</td>
								<td align="right">'.$total_cate_55_59_m.'</td>
								<td align="right">'.$total_cate_60_64_h.'</td>
								<td align="right">'.$total_cate_60_64_m.'</td>
								<td align="right">'.$total_cate_65_69_h.'</td>
								<td align="right">'.$total_cate_65_69_m.'</td>
								<td align="right">'.$total_cate_70_74_h.'</td>
								<td align="right">'.$total_cate_70_74_m.'</td>
								<td align="right">'.$total_cate_75_79_h.'</td>
								<td align="right">'.$total_cate_75_79_m.'</td>
								<td align="right">'.$total_cate_80_m_h.'</td>
								<td align="right">'.$total_cate_80_m_m.'</td>

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

				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="3" class="foliochico">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" align="left" class="foliochico"><strong class="titulotabla">SECCIÓN C: ATENCIONES REALIZADAS POR MÉDICOS ESPECIALISTAS EN LAS UNIDADES DE URGENCIA HOSPITALARIA</strong></td>
				</tr>
				<tr>
					<td colspan="3" align="left">
						<table style="font-size: 24px;" width="950" border="1" cellpadding="2" cellspacing="0" class="reporte" name="tablaexport" id="tablaexport2">
							<tr align="center" valign="top">
								<td valign="bottom" bgcolor="#CCCCCC"><strong>ESPECIALIDADES</strong></td>
								<td valign="bottom" bgcolor="#CCCCCC"><strong>TOTAL</strong></td>
								<td valign="bottom" bgcolor="#CCCCCC"><strong>Menor 15 Años</strong></td>
								<td valign="bottom" bgcolor="#CCCCCC"><strong>15 Años y Mas</strong></td>
							</tr>
							<tr align="left" valign="top">
								<td valign="bottom" bgcolor="#CCCCCC"><strong>MEDICINA INTERNA</strong></td>

								<td align="right"> <strong>'.$total_medi.'</strong> </td>
								<td align="right">'.$medi_0_15.'</td>
								<td align="right">'.$medi_15_m.'</td>
								
							</tr>
							<tr align="left" valign="top">
								<td valign="bottom" bgcolor="#CCCCCC"><strong>PEDIATRÍA</strong></td>

								<td align="right"> <strong>'.$total_pedi.'</strong> </td>
								<td align="right">'.$pedi_0_15.'</td>
								<td align="right">'.$pedi_15_m.'</td>

							</tr>
							<tr align="left" valign="top">
								<td valign="bottom" bgcolor="#CCCCCC"><strong>TRAUMATOLOGÍA</strong></td>

								<td align="right"> <strong>'.$total_trau.'</strong> </td>
								<td align="right">'.$trau_0_15.'</td>
								<td align="right">'.$trau_15_m.'</td>

							</tr>
							<tr align="left" valign="top">
								<td valign="bottom" bgcolor="#CCCCCC"><strong>NEUROLOGÍA</strong></td>

								<td align="right"><strong>'.$total_neur.'</strong> </td>
								<td align="right">'.$neur_0_15.'</td>
								<td align="right">'.$neci_15_m.'</td>

							</tr>
							<tr align="left" valign="top">
								<td valign="bottom" bgcolor="#CCCCCC"><strong>NEUROCIGUGÍA</strong></td>

								<td align="right"> <strong>'.$total_psiq.'</strong> </td>
								<td align="right">'.$psiq_0_15.'</td>
								<td align="right">'.$psiq_15_m.'</td>

							</tr>
							<tr align="left" valign="top">
								<td valign="bottom" bgcolor="#CCCCCC"><strong>PSIQUIATRÍA</strong></td>

								<td align="right"> <strong>'.$total_psiq.'</strong> </td>
								<td align="right">'.$psiq_0_15.'</td>
								<td align="right">'.$psiq_15_m.'</td>

								<tr align="center" valign="top">
									<td valign="bottom" bgcolor="#CCCCCC"><strong>TOTAL</strong></td>

									<td align="right"> <strong>'.$total_espe.'</strong> </td>
									<td align="right"> <strong>'.$total_espe_0_15.'</strong> </td>
									<td align="right"> <strong>'.$total_espe_15_m.'</strong> </td>

								</tr>
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

					<tr>
						<td colspan="3" class="foliochico">&nbsp;</td>
					</tr>

					<tr>
						<td colspan="3" class="foliochico">&nbsp;</td>
					</tr>

					<tr>
						<td colspan="3" class="foliochico">&nbsp;</td>
					</tr>

					<tr>
						<td colspan="3" class="foliochico">&nbsp;</td>
					</tr>

					<tr>
						<td colspan="3" class="foliochico">&nbsp;</td>
					</tr>

					<tr>
						<td colspan="3" class="foliochico">&nbsp;</td>
					</tr>

					<tr>
						<td colspan="3" class="foliochico">&nbsp;</td>
					</tr>

					<tr>
						<td colspan="3" class="foliochico">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="3" align="left" class="foliochico"><strong class="titulotabla">SECCIÓN G: PACIENTES CON INDICACIÓN DE HOSPITALIZACIÓN EN ESPERA DE CAMAS EN UEH</strong></td>
					</tr>
					<tr>
						<td colspan="3" align="left">
							<table  border="1" cellpadding="2" cellspacing="0" class="reporte" name="tablaexport" id="tablaexport2">
								<tr align="center" valign="top">
									<td width="623" colspan="2" valign="bottom" bgcolor="#CCCCCC"><strong>TIPO DE PACIENTES</strong></td>
									<td width="8%" valign="bottom" bgcolor="#CCCCCC"><strong>TOTAL</strong></td>
								</tr>
								<tr align="left" valign="top">
									<td colspan="2" valign="bottom" bgcolor="#CCCCCC"><strong>TOTAL DE PACIENTES CON INDICACIÓN DE HOSPITALIZACIÓN</strong></td>
									<td align="right">'.$hosp_tot_urg.'</td>
								</tr>
								<tr align="center" valign="top">
									<td width="422" rowspan="3" valign="center" bgcolor="#CCCCCC"><strong>PACIENTES QUE INGRESAN A CAMA HOSPITALARIA SEGÚN TIEMPO DE DEMORA AL INGRESO</strong></td>
									<td width="22%" valign="bottom" bgcolor="#CCCCCC"><strong>MENOS DE 12 HORAS</strong></td>
									<td align="right">'.$h_urg_0_12.'</td>
								</tr>
								<tr align="center" valign="top">
									<td valign="bottom" bgcolor="#CCCCCC"><strong>12-24 HORAS</strong></td>
									<td align="right">'.$h_urg_12_24.'</td>
								</tr>
								<tr align="center" valign="top">
									<td valign="bottom" bgcolor="#CCCCCC"><strong>MAYOR A 24 HORAS</strong></td>
									<td align="right">'.$h_urg_24_m.'</td>
								</tr>
								<tr align="left" valign="top">
									<td colspan="2" valign="bottom" bgcolor="#CCCCCC"><strong>PACIENTES QUE RECHAZAN HOSPITALIZACIÓN</strong></td>
									<td align="right">'.$h_rech_hosp.'</td>
								</tr>
								<tr align="left" valign="top">
									<td colspan="2" valign="bottom" bgcolor="#CCCCCC"><strong>PACIENTES DERIVADOS A OTRO ESTABLECIMIENTO</strong></td>
									<td align="right">'.$h_otro_est.'</td>
								</tr>
								<tr align="left" valign="top">
									<td colspan="2" valign="bottom" bgcolor="#CCCCCC"><strong>PACIENTES QUE PERMANECEN EN UEH</strong></td>
									<td align="right">'.$h_ueh.'</td>
								</tr>
								<tr align="left" valign="top">
									<td colspan="2" valign="bottom" bgcolor="#CCCCCC"><strong>PACIENTES EN ESPERA DE CAMA HOSPITALARIA QUE FALLECIERON</strong></td>
									<td align="right">'.$h_fall_espera.'</td>
								</tr>
							</table>
						</td>
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
					<td colspan="3" align="left" class="foliochico"><strong class="titulotabla">SECCIÓN R: ATENCIONES POR MORDEDURA EN SERVICIO DE URGENCIA DE LA RED</strong></td>
				</tr>
				<tr>
					<td>
						<table border="1" cellpadding="2" cellspacing="0" class="reporte" name="tablaexport" id="tablaexport2" style="font-size: 24px;" width="950">
							<tr>
								<td rowspan="4" bgcolor="#CCCCCC" width="25%">IDENTIFICACION DEL ANIMAL MORDEDOR</td>
								<td rowspan="2" bgcolor="#CCCCCC" colspan="3" width="20%">TOTAL</td>
								<td colspan="8" bgcolor="#CCCCCC" width="40%">GRUPOS DE EDAD (en años)</td>
								<td rowspan="2" bgcolor="#CCCCCC" colspan="2" width="10%">TIPO DE MORDEDURA</td>
								<td rowspan="3" bgcolor="#CCCCCC" width="5%">INDICACIÓN DE VACUNA</td>
							</tr>
							<tr>
								<td colspan="2" bgcolor="#CCCCCC">0-4</td>
								<td colspan="2" bgcolor="#CCCCCC">5-9</td>
								<td colspan="2" bgcolor="#CCCCCC">10-14</td>
								<td colspan="2" bgcolor="#CCCCCC">Mayor a 15 años</td>
							</tr>
							<tr>
								<td bgcolor="#CCCCCC">Ambos Sexos</td>
								<td bgcolor="#CCCCCC">Hombres</td>
								<td bgcolor="#CCCCCC">Mujeres</td>
								<td bgcolor="#CCCCCC">Hombres</td>
								<td bgcolor="#CCCCCC">Mujeres</td>
								<td bgcolor="#CCCCCC">Hombres</td>
								<td bgcolor="#CCCCCC">Mujeres</td>
								<td bgcolor="#CCCCCC">Hombres</td>
								<td bgcolor="#CCCCCC">Mujeres</td>
								<td bgcolor="#CCCCCC">Hombres</td>
								<td bgcolor="#CCCCCC">Mujeres</td>
								<td bgcolor="#CCCCCC">Única</td>
								<td bgcolor="#CCCCCC">Múltiple</td>
							</tr>
							<tr>
								<td>'.$totalambossexos.'</td>
								<td>'.$totalhombres.'</td>
								<td>'.$totalmujeres.'</td>
								<td>'.$totalanimales0a4m.'</td>
								<td>'.$totalanimales0a4f.'</td>
								<td>'.$totalanimales5a9m.'</td>
								<td>'.$totalanimales5a9f.'</td>
								<td>'.$totalanimales10a14m.'</td>
								<td>'.$totalanimales10a14f.'</td>
								<td>'.$totalanimales15amm.'</td>
								<td>'.$totalanimales15amf.'</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
							</tr>
							<tr>
								<td bgcolor="#CCCCCC">PERRO</td>
								<td>'.$tperromix.'</td>
								<td>'.$Tperrohombre.'</td>
								<td>'.$Tperromujer.'</td>
								<td>'.$rem_mordedura["0"]["0a4m1"].'</td>
								<td>'.$rem_mordedura["0"]["0a4f1"].'</td>
								<td>'.$rem_mordedura["0"]["5a9m1"].'</td>
								<td>'.$rem_mordedura["0"]["5a9f1"].'</td>
								<td>'.$rem_mordedura["0"]["10a14m1"].'</td>
								<td>'.$rem_mordedura["0"]["10a14f1"].'</td>
								<td>'.$rem_mordedura["0"]["15amm1"].'</td>
								<td>'.$rem_mordedura["0"]["15amf1"].'</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
							</tr>
							<tr>
								<td bgcolor="#CCCCCC">GATO</td>
								<td>'.$tgatomix.'</td>
								<td>'.$Tgatohombre.'</td>
								<td>'.$Tgatomujer.'</td>
								<td>'.$rem_mordedura["0"]["0a4m6"].'</td>
								<td>'.$rem_mordedura["0"]["0a4f6"].'</td>
								<td>'.$rem_mordedura["0"]["5a9m6"].'</td>
								<td>'.$rem_mordedura["0"]["5a9f6"].'</td>
								<td>'.$rem_mordedura["0"]["10a14m6"].'</td>
								<td>'.$rem_mordedura["0"]["10a14f6"].'</td>
								<td>'.$rem_mordedura["0"]["15amm6"].'</td>
								<td>'.$rem_mordedura["0"]["15amf6"].'</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
							</tr>
							<tr>
								<td bgcolor="#CCCCCC">ANIMAL SILVESTRE</td>
								<td>'.$tasilvestremix.'</td>
								<td>'.$Tasilvestrehombre.'</td>
								<td>'.$Tasilvestremujer.'</td>
								<td>'.$rem_mordedura["0"]["0a4m10"].'</td>
								<td>'.$rem_mordedura["0"]["0a4f10"].'</td>
								<td>'.$rem_mordedura["0"]["5a9m10"].'</td>
								<td>'.$rem_mordedura["0"]["5a9f10"].'</td>
								<td>'.$rem_mordedura["0"]["10a14m10"].'</td>
								<td>'.$rem_mordedura["0"]["10a14f10"].'</td>
								<td>'.$rem_mordedura["0"]["15amm10"].'</td>
								<td>'.$rem_mordedura["0"]["15amf10"].'</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
							</tr>
							<tr>
								<td bgcolor="#CCCCCC">EXPOSICIÓN A MURCIELAGO</td>
								<td>'.$tmurcielagomix.'</td>
								<td>'.$Tmurcielagohombre.'</td>
								<td>'.$Tmurcielagomujer.'</td>
								<td>'.$rem_mordedura["0"]["0a4m9"].'</td>
								<td>'.$rem_mordedura["0"]["0a4f9"].'</td>
								<td>'.$rem_mordedura["0"]["5a9m9"].'</td>
								<td>'.$rem_mordedura["0"]["5a9f9"].'</td>
								<td>'.$rem_mordedura["0"]["10a14m9"].'</td>
								<td>'.$rem_mordedura["0"]["10a14f9"].'</td>
								<td>'.$rem_mordedura["0"]["15amm9"].'</td>
								<td>'.$rem_mordedura["0"]["15amf9"].'</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
							</tr>
							<tr>
								<td bgcolor="#CCCCCC">ROEDOR O ANIMAL ABASTO</td>
								<td>'.$tratonmix.'</td>
								<td>'.$Tratonhombre.'</td>
								<td>'.$Tratonmujer.'</td>
								<td>'.$rem_mordedura["0"]["0a4m2"].'</td>
								<td>'.$rem_mordedura["0"]["0a4f2"].'</td>
								<td>'.$rem_mordedura["0"]["5a9m2"].'</td>
								<td>'.$rem_mordedura["0"]["5a9f2"].'</td>
								<td>'.$rem_mordedura["0"]["10a14m2"].'</td>
								<td>'.$rem_mordedura["0"]["10a14f2"].'</td>
								<td>'.$rem_mordedura["0"]["15amm2"].'</td>
								<td>'.$rem_mordedura["0"]["15amf2"].'</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
							</tr>
						</table>
					</td>
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

		';

		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->Output('reporteREMA08.pdf','FI');
		$url = RAIZ."/views/reportes/salidas/reporteREMA08.pdf";
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