<iframe height="100%" width="100%" hidden>
	<?php
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
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		//SET DOCUMENT INFORMATION
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('DAU');
		$pdf->SetTitle('ATENCIONES Y HOSPITALIZACIONES DE URGENCIA');
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
		$parametros               				= $objUtil->getFormulario($_POST);
		$parametros['frm_inicio'] 				= $objUtil->fechaInvertida($parametros['fechaInicio']);
		$parametros['frm_fin']    				= $objUtil->fechaInvertida($parametros['fechaFin']);
		$fechaHoy                 				= $objUtil->getFechaPalabra(date('Y-m-d'));
		$prestacionesPorEdad      				= $reporte->prestacionesPorEdad($objCon,$parametros);
		$prestacionesPorAccidente 				= $reporte->prestacionesPorAccidente($objCon,$parametros);
		$prestacionEdadTotal	  				= $reporte->prestacionEdadTotal($objCon,$parametros);
		$pacientesPabellon	      				= $reporte->pacientesPabellon($objCon, $parametros);
		$atencionesSospechas     			 	= $reporte->pacientesSospechasCoronavirus($objCon, $parametros, '');
		$atencionesCoronavirus    				= $reporte->pacientesCoronavirus($objCon, $parametros, '');
		$hospitalizacionesSospechaCoronavirus 	= $reporte->pacientesSospechasCoronavirus($objCon, $parametros, 'hospitalizaciones');
		$hospitalizacionesCoronavirus 			= $reporte->pacientesCoronavirus($objCon, $parametros, 'hospitalizaciones');
		$totalDemandas			  				= $reporte->totalDemanda($objCon, $parametros);
		//highlight_string(print_r($parametros),true);

		// INICIALIZANDO TODAS LAS VARIABLES EN 0
		$contadorTotalR         = 0;
		$contmeses_0000aZZZZ    = 0;
		$cont1_4_0000aZZZZ      = 0;
		$cont5_14_0000aZZZZ     = 0;
		$cont15_64_0000aZZZZ    = 0;
		$cont65_0000aZZZZ       = 0;
		$contmeses_J000aJ99Z    = 0;
		$cont1_4_J000aJ99Z      = 0;
		$cont5_14_J000aJ99Z     = 0;
		$cont15_64_J000aJ99Z    = 0;
		$cont65_J000aJ99Z       = 0;
		$contmeses_J000aJ06Z    = 0;
		$cont1_4_J000aJ06Z      = 0;
		$cont5_14_J000aJ06Z     = 0;
		$cont15_64_J000aJ06Z    = 0;
		$cont65_J000aJ06Z       = 0;
		$contmeses_J090aJ111    = 0;
		$cont1_4_J090aJ111      = 0;
		$cont5_14_J090aJ111     = 0;
		$cont15_64_J090aJ111    = 0;
		$cont65_J090aJ111       = 0;
		$contmeses_J120aJ18Z    = 0;
		$cont1_4_J120aJ18Z      = 0;
		$cont5_14_J120aJ18Z     = 0;
		$cont15_64_J120aJ18Z    = 0;
		$cont65_J120aJ18Z       = 0;
		$contmeses_J200aJ21Z    = 0;
		$cont1_4_J200aJ21Z      = 0;
		$cont5_14_J200aJ21Z     = 0;
		$cont15_64_J200aJ21Z    = 0;
		$cont65_J200aJ21Z       = 0;
		$contmeses_J400aJ46Z    = 0;
		$cont1_4_J400aJ46Z      = 0;
		$cont5_14_J400aJ46Z     = 0;
		$cont15_64_J400aJ46Z    = 0;
		$cont65_J400aJ46Z       = 0;
		$contmeses_I000aIZZZ    = 0;
		$cont1_4_I000aIZZZ      = 0;
		$cont5_14_I000aIZZZ     = 0;
		$cont15_64_I000aIZZZ    = 0;
		$cont65_I000aIZZZ       = 0;
		$contmeses_I219aI219    = 0;
		$cont1_4_I219aI219      = 0;
		$cont5_14_I219aI219     = 0;
		$cont15_64_I219aI219    = 0;
		$cont65_I219aI219       = 0;
		$contmeses_I64XaI64X    = 0;
		$cont1_4_I64XaI64X      = 0;
		$cont5_14_I64XaI64X     = 0;
		$cont15_64_I64XaI64X    = 0;
		$cont65_I640aI640       = 0;
		$contmeses_I10XaI10X    = 0;
		$cont1_4_I10XaI10X      = 0;
		$cont5_14_I10XaI10X     = 0;
		$cont15_64_I10XaI10X    = 0;
		$cont65_I10XaI10X       = 0;
		$contmeses_I499aI499    = 0;
		$cont1_4_I499aI499      = 0;
		$cont5_14_I499aI499     = 0;
		$cont15_64_I499aI499    = 0;
		$cont65_I499aI499       = 0;
		$contmeses_S000aT99Z    = 0;
		$cont1_4_S000aT99Z      = 0;
		$cont5_14_S000aT99Z     = 0;
		$cont15_64_S000aT99Z    = 0;
		$cont65_S000aT99Z       = 0;
		$contmeses_ACCS000aT99Z = 0;
		$cont1_4_ACCS000aT99Z   = 0;
		$cont5_14_ACCS000aT99Z  = 0;
		$cont15_64_ACCS000aT99Z = 0;
		$cont65_ACCS000aT99Z    = 0;
		$contmeses_A000aA09X    = 0;
		$cont1_4_A000aA09X      = 0;
		$cont5_14_A000aA09X     = 0;
		$cont15_64_A000aA09X    = 0;
		$cont65_A000aA09X       = 0;
		$contmeses_HOS0000aZZZZ = 0;
		$cont1_4_HOS0000aZZZZ   = 0;
		$cont5_14_HOS0000aZZZZ  = 0;
		$cont15_64_HOS0000aZZZZ = 0;
		$cont65_HOS0000aZZZZ    = 0;
		$contmeses_HOSJ000aJ99Z = 0;
		$cont1_4_HOSJ000aJ99Z   = 0;
		$cont5_14_HOSJ000aJ99Z  = 0;
		$cont15_64_HOSJ000aJ99Z = 0;
		$cont65_HOSJ000aJ99Z    = 0;
		$contmeses_HOSI000aI99Z = 0;
		$cont1_4_HOSI000aI99Z   = 0;
		$cont5_14_HOSI000aI99Z  = 0;
		$cont15_64_HOSI000aI99Z = 0;
		$cont65_HOSI000aI99Z    = 0;
		$contmeses_HOSS000aT99Z = 0;
		$cont1_4_HOSS000aT99Z   = 0;
		$cont5_14_HOSS000aT99Z  = 0;
		$cont15_64_HOSS000aT99A = 0;
		$cont65_HOSS000aT99Z    = 0;

		for($i=0; $i<count($prestacionesPorEdad); $i++){ //inicio de for prestacionesPorEdad
			$edad = $prestacionesPorEdad[$i]['dau_paciente_edad'];
			$diag = $prestacionesPorEdad[$i]['dau_cierre_cie10'];

			switch (TRUE){//inicio switch
				case ($edad < 1):
					if($diag){
						if(strtoupper($diag) >= '0000' && strtoupper($diag) <= 'ZZZZ'){
							$contmeses_0000aZZZZ++;
						}
						if(strtoupper($diag) >= 'J000' && strtoupper($diag) <= 'J99Z'){
							$contmeses_J000aJ99Z++;
						}
						if(strtoupper($diag) >= 'J000' && strtoupper($diag) <= 'J06Z'){
							$contmeses_J000aJ06Z++;
						}
						if(strtoupper($diag) >= 'J090' && strtoupper($diag) <= 'J111'){
							$contmeses_J090aJ111++;
						}
						if(strtoupper($diag) >= 'J120' && strtoupper($diag) <= 'J18Z'){
							$contmeses_J120aJ18Z++;
						}
						if(strtoupper($diag) >= 'J200' && strtoupper($diag) <= 'J21Z'){
							$contmeses_J200aJ21Z++;
						}
						if(strtoupper($diag) >= 'J400' && strtoupper($diag) <= 'J46Z'){
							$contmeses_J400aJ46Z++;
						}
						if(strtoupper($diag) >= 'I000' && strtoupper($diag) <= 'IZZZ'){
							$contmeses_I000aIZZZ++;
						}
						if(strtoupper($diag) >= 'I210' && strtoupper($diag) <= 'I219'){
							$contmeses_I219aI219++;
						}
						if(strtoupper($diag) >= 'I64X' && strtoupper($diag) <= 'I64X'){
							$contmeses_I64XaI64X++;
						}
						if(strtoupper($diag) >= 'I10X' && strtoupper($diag) <= 'I10X'){
							$contmeses_I10XaI10X++;
						}
						if(strtoupper($diag) >= 'I499' && strtoupper($diag) <= 'I499'){
							$contmeses_I499aI499++;
						}
						if(strtoupper($diag) >= 'S000' && strtoupper($diag) <= 'T99Z'){
							$contmeses_S000aT99Z++;
						}
						if(strtoupper($diag) >= 'A000' && strtoupper($diag) <= 'A09X'){
							$contmeses_A000aA09X++;
						}
					}else if($diag == '') {
						$contvacioMESES++;
					}
				break;

				case ($edad >= 1 && $edad <= 4):
					if ($diag){
						if(strtoupper($diag) >= '0000' && strtoupper($diag) <= 'ZZZZ'){
							$cont1_4_0000aZZZZ++;
						}
						if(strtoupper($diag) >= 'J000' && strtoupper($diag) <= 'J99Z'){
							$cont1_4_J000aJ99Z++;
						}
						if(strtoupper($diag) >= 'J000' && strtoupper($diag) <= 'J06Z'){
							$cont1_4_J000aJ06Z++;
						}
						if(strtoupper($diag) >= 'J090' && strtoupper($diag) <= 'J111'){
							$cont1_4_J090aJ111++;
						}
						if(strtoupper($diag) >= 'J120' && strtoupper($diag) <= 'J18Z'){
							$cont1_4_J120aJ18Z++;
						}
						if(strtoupper($diag) >= 'J200' && strtoupper($diag) <= 'J21Z'){
							$cont1_4_J200aJ21Z++;
						}
						if(strtoupper($diag) >= 'J400' && strtoupper($diag) <= 'J46Z'){
							$cont1_4_J400aJ46Z++;
						}
						if(strtoupper($diag) >= 'I000' && strtoupper($diag) <= 'IZZZ'){
							$cont1_4_I000aIZZZ++;
						}
						if(strtoupper($diag) >= 'I210' && strtoupper($diag) <= 'I219'){
							$cont1_4_I219aI219++;
						}
						if(strtoupper($diag) >= 'I64X' && strtoupper($diag) <= 'I64X'){
							$cont1_4_I64XaI64X++;
						}
						if(strtoupper($diag) >= 'I10X' && strtoupper($diag) <= 'I10X'){
							$cont1_4_I10XaI10X++;
						}
						if(strtoupper($diag) >= 'I499' && strtoupper($diag) <= 'I499'){
							$cont1_4_I499aI499++;
						}
						if(strtoupper($diag) >= 'S000' && strtoupper($diag) <= 'T99Z'){
							$cont1_4_S000aT99Z++;
						}
						if(strtoupper($diag) >= 'A000' && strtoupper($diag) <= 'A09X'){
							$cont1_4_A000aA09X++;
						}
					}else if($diag == '') {
						$contvacio1_4++;
					}
				break;

				case ($edad >=5 && $edad <= 14):
					 if ($diag){
						 if(strtoupper($diag) >= '0000' && strtoupper($diag) <= 'ZZZZ'){
							$cont5_14_0000aZZZZ++;
						 }
						 if(strtoupper($diag) >= 'J000' && strtoupper($diag) <= 'J99Z'){
							$cont5_14_J000aJ99Z++;
						 }
						 if(strtoupper($diag) >= 'J000' && strtoupper($diag) <= 'J06Z'){
							$cont5_14_J000aJ06Z++;
						 }
						 if(strtoupper($diag) >= 'J090' && strtoupper($diag) <= 'J111'){
							$cont5_14_J090aJ111++;
						 }
						 if(strtoupper($diag) >= 'J120' && strtoupper($diag) <= 'J18Z'){
							$cont5_14_J120aJ18Z++;
						 }
						 if(strtoupper($diag) >= 'J200' && strtoupper($diag) <= 'J21Z'){
							$cont5_14_J200aJ21Z++;
						 }
						 if(strtoupper($diag) >= 'J400' && strtoupper($diag) <= 'J46Z'){
							$cont5_14_J400aJ46Z++;
						 }
						 if(strtoupper($diag) >= 'I000' && strtoupper($diag) <= 'IZZZ'){
							$cont5_14_I000aIZZZ++;
						 }
						 if(strtoupper($diag) >= 'I210' && strtoupper($diag) <= 'I219'){
							$cont5_14_I219aI219++;
						 }
						 if(strtoupper($diag) >= 'I64X' && strtoupper($diag) <= 'I64X'){
							$cont5_14_I64XaI64X++;
						 }
						 if(strtoupper($diag) >= 'I10X' && strtoupper($diag) <= 'I10X'){
							$cont5_14_I10XaI10X++;
						 }
						 if(strtoupper($diag) >= 'I499' && strtoupper($diag) <= 'I499'){
							$cont5_14_I499aI499++;
						 }
						 if(strtoupper($diag) >= 'S000' && strtoupper($diag) <= 'T99Z'){
							$cont5_14_S000aT99Z++;
						 }
						 if(strtoupper($diag) >= 'A000' && strtoupper($diag) <= 'A09X'){
							$cont5_14_A000aA09X++;
						 }
					 }else if($diag == '') {
						 $contvacio5_14++;
					 }
				break;

				case ($edad >= 15 && $edad <= 64 ):
				if($diag){
					if(strtoupper($diag) >= '0000' && strtoupper($diag) <= 'ZZZZ'){
						$cont15_64_0000aZZZZ++;
					}
					if(strtoupper($diag) >= 'J000' && strtoupper($diag) <= 'J99Z'){
						$cont15_64_J000aJ99Z++;
					}
					if(strtoupper($diag) >= 'J000' && strtoupper($diag) <= 'J06Z'){
						$cont15_64_J000aJ06Z++;
					}
					if(strtoupper($diag) >= 'J090' && strtoupper($diag) <= 'J111'){
						$cont15_64_J090aJ111++;
					}
					if(strtoupper($diag) >= 'J120' && strtoupper($diag) <= 'J18Z'){
						$cont15_64_J120aJ18Z++;
					}
					if(strtoupper($diag) >= 'J200' && strtoupper($diag) <= 'J21Z'){
						$cont15_64_J200aJ21Z++;
					}
					if(strtoupper($diag) >= 'J400' && strtoupper($diag) <= 'J46Z'){
						$cont15_64_J400aJ46Z++;
					}
					if(strtoupper($diag) >= 'I000' && strtoupper($diag) <= 'IZZZ'){
						$cont15_64_I000aIZZZ++;
					}
					if(strtoupper($diag) >= 'I210' && strtoupper($diag) <= 'I219'){
						$cont15_64_I219aI219++;
					}
					if(strtoupper($diag) >= 'I64X' && strtoupper($diag) <= 'I64X'){
						$cont15_64_I64XaI64X++;
					}
					if(strtoupper($diag) >= 'I10X' && strtoupper($diag) <= 'I10X'){
						$cont15_64_I10XaI10X++;
					}
					if(strtoupper($diag) >= 'I499' && strtoupper($diag) <= 'I499'){
						$cont15_64_I499aI499++;
					}
					if(strtoupper($diag) >= 'S000' && strtoupper($diag) <= 'T99Z'){
						$cont15_64_S000aT99Z++;
					}
					if(strtoupper($diag) >= 'A000' && strtoupper($diag) <= 'A09X'){
						$cont15_64_A000aA09X++;
					}
				}else if($diag == '') {
					$contvacio15_64++;
				}
				break;

				case($edad >= 65):
				if($diag){
					if(strtoupper($diag) >= '0000' && strtoupper($diag) <= 'ZZZZ'){
						$cont65_0000aZZZZ++;
					}
					if(strtoupper($diag) >= 'J000' && strtoupper($diag) <= 'J99Z'){
						$cont65_J000aJ99Z++;
					}
					if(strtoupper($diag) >= 'J000' && strtoupper($diag) <= 'J06Z'){
						$cont65_J000aJ06Z++;
					}
					if(strtoupper($diag) >= 'J090' && strtoupper($diag) <= 'J111'){
						$cont65_J090aJ111++;
					}
					if(strtoupper($diag) >= 'J120' && strtoupper($diag) <= 'J18Z'){
						$cont65_J120aJ18Z++;
					}
					if(strtoupper($diag) >= 'J200' && strtoupper($diag) <= 'J21Z'){
						$cont65_J200aJ21Z++;
					}
					if(strtoupper($diag) >= 'J400' && strtoupper($diag) <= 'J46Z'){
						$cont65_J400aJ46Z++;
					}
					if(strtoupper($diag) >= 'I0000' && strtoupper($diag) <= 'IZZZ'){
						$cont65_I000aIZZZ++;
					}
					if(strtoupper($diag) >= 'I210' && strtoupper($diag) <= 'I219'){
						$cont65_I219aI219++;
					}
					if(strtoupper($diag) >= 'I64X' && strtoupper($diag) <= 'I64X'){
						$cont65_I640aI640++;
					}
					if(strtoupper($diag) >= 'I10X' && strtoupper($diag) <= 'I10X'){
						$cont65_I10XaI10X++;
					}
					if(strtoupper($diag) >= 'I499' && strtoupper($diag) <= 'I499'){
						$cont65_I499aI499++;
					}
					if(strtoupper($diag) >= 'S000' && strtoupper($diag) <= 'T99Z'){
						$cont65_S000aT99Z++;
					}
					if(strtoupper($diag) >= 'A000' && strtoupper($diag) <= 'A09X'){
						$cont65_A000aA09X++;
					}
				}else if($diag == '') {
					$contvacio6++;
				}
				break;
			}//fin switch

		}//fin de for prestacionesPorEdad



		// Suma  prestaciones por edad total

			$total_0000aZZZZ = $contmeses_0000aZZZZ+$cont1_4_0000aZZZZ+$cont5_14_0000aZZZZ+$cont15_64_0000aZZZZ+$cont65_0000aZZZZ;
			$total_J000aJ99Z = $contmeses_J000aJ99Z+$cont1_4_J000aJ99Z+$cont5_14_J000aJ99Z+$cont15_64_J000aJ99Z+$cont65_J000aJ99Z;
			$total_J000aJ06Z = $contmeses_J000aJ06Z+$cont1_4_J000aJ06Z+$cont5_14_J000aJ06Z+$cont15_64_J000aJ06Z+$cont65_J000aJ06Z;
			$total_J090aJ111 = $contmeses_J090aJ111+$cont1_4_J090aJ111+$cont5_14_J090aJ111+$cont15_64_J090aJ111+$cont65_J090aJ111;
			$total_J120aJ18Z = $contmeses_J120aJ18Z+$cont1_4_J120aJ18Z+$cont5_14_J120aJ18Z+$cont15_64_J120aJ18Z+$cont65_J120aJ18Z;
			$total_J200aJ21Z = $contmeses_J200aJ21Z+$cont1_4_J200aJ21Z+$cont5_14_J200aJ21Z+$cont15_64_J200aJ21Z+$cont65_J200aJ21Z;
			$total_J400aJ46Z = $contmeses_J400aJ46Z+$cont1_4_J400aJ46Z+$cont5_14_J400aJ46Z+$cont15_64_J400aJ46Z+$cont65_J400aJ46Z;
			$total_I000aIZZZ = $contmeses_I000aIZZZ+$cont1_4_I000aIZZZ+$cont5_14_I000aIZZZ+$cont15_64_I000aIZZZ+$cont65_I000aIZZZ;
			$total_I219aI219 = $contmeses_I219aI219+$cont1_4_I219aI219+$cont5_14_I219aI219+$cont15_64_I219aI219+$cont65_I219aI219;
			$total_I64XaI64X = $contmeses_I64XaI64X+$cont1_4_I64XaI64X+$cont5_14_I64XaI64X+$cont15_64_I64XaI64X+$cont65_I640aI640;
			$total_I10XaI10X = $contmeses_I10XaI10X+$cont1_4_I10XaI10X+$cont5_14_I10XaI10X+$cont15_64_I10XaI10X+$cont65_I10XaI10X;
			$total_I499aI499 = $contmeses_I499aI499+$cont1_4_I499aI499+$cont5_14_I499aI499+$cont15_64_I499aI499+$cont65_I499aI499;
			$total_S000aT99Z = $contmeses_S000aT99Z+$cont1_4_S000aT99Z+$cont5_14_S000aT99Z+$cont15_64_S000aT99Z+$cont65_S000aT99Z;
			$total_A000aA09X = $contmeses_A000aA09X+$cont1_4_A000aA09X+$cont5_14_A000aA09X+$cont15_64_A000aA09X+$cont65_A000aA09X;

		// Suma  prestaciones por edad total

		for($j=0; $j<count($prestacionesPorAccidente); $j++){ //for prestaciones por accidentes
			$edad = $prestacionesPorAccidente[$j]['dau_paciente_edad'];
			$diag = $prestacionesPorAccidente[$j]['dau_cierre_cie10'];

			switch (TRUE){ //inicio switch
				case ($edad < 1):
					if($diag){
						if(strtoupper($diag) >= 'S000' && strtoupper($diag) <= 'T99Z'){
							$contmeses_ACCS000aT99Z++;
						}
					}else if($diag == '') {
						$contvacioACC++;
					}
				break;

				case ($edad >= 1 && $edad <= 4):
					if($diag){
						if(strtoupper($diag) >= 'S000' && strtoupper($diag) <= 'T99Z'){
							$cont1_4_ACCS000aT99Z++;
						}
					}else if($diag == '') {
						$contvacioACC++;
					}
				break;

				case ($edad >=5 && $edad <= 14):
					if($diag){
						if(strtoupper($diag) >= 'S000' && strtoupper($diag) <= 'T99Z'){
							$cont5_14_ACCS000aT99Z++;
						}
					}else if($diag == '') {
						$contvacioACC++;
					}
				break;

				case ($edad >= 15 && $edad <= 64 ):
					if($diag){
						if(strtoupper($diag) >= 'S000' && strtoupper($diag) <= 'T99Z'){
							$cont15_64_ACCS000aT99Z++;
						}
					}else if($diag == '') {
						$contvacioACC++;
					}
				break;

				case($edad >= 65):
					if($diag){
						if(strtoupper($diag) >= 'S000' && strtoupper($diag) <= 'T99Z'){
							$cont65_ACCS000aT99Z++;
						}
					}else if($diag == '') {
						$contvacioACC++;
					}
				break;
			}//fin switch

		} //for prestaciones por accidentes

		//SUMA  prestaciones por accidentes
			$total_ACCS000aT99Z = $contmeses_ACCS000aT99Z+$cont1_4_ACCS000aT99Z+$cont5_14_ACCS000aT99Z+$cont15_64_ACCS000aT99Z+$cont65_ACCS000aT99Z;
		//SUMA  prestaciones por accidentes

		for($x=0; $x<count($prestacionEdadTotal); $x++){ //for prestaciones por prestacionEdadTotal
			$edad = $prestacionEdadTotal[$x]['dau_paciente_edad'];
			$diag = $prestacionEdadTotal[$x]['dau_cierre_cie10'];

			switch (TRUE){ //inicio switch
				case ($edad < 1):
					if($diag){
						if(strtoupper($diag) >= '0000' && strtoupper($diag) <= 'ZZZZ'){
							$contmeses_HOS0000aZZZZ++;
						}
						if(strtoupper($diag) >= 'J000' && strtoupper($diag) <= 'J99Z'){
							$contmeses_HOSJ000aJ99Z++;
						}
						if(strtoupper($diag) >= 'I000' && strtoupper($diag) <= 'I99Z'){
							$contmeses_HOSI000aI99Z++;
						}
						if(strtoupper($diag) >= 'S000' && strtoupper($diag) <= 'T99Z'){
							$contmeses_HOSS000aT99Z++;
						}
					}else if($diag == '') {
						$contvacioH++;
					}
				break;

				case ($edad >= 1 && $edad <= 4):
					if($diag){
						if(strtoupper($diag) >= '0000' && strtoupper($diag) <= 'ZZZZ'){
							$cont1_4_HOS0000aZZZZ++;
						}
						if(strtoupper($diag) >= 'J000' && strtoupper($diag) <= 'J99Z'){
							$cont1_4_HOSJ000aJ99Z++;
						}
						if(strtoupper($diag) >= 'I000' && strtoupper($diag) <= 'I99Z'){
							$cont1_4_HOSI000aI99Z++;
						}
						if(strtoupper($diag) >= 'S000' && strtoupper($diag) <= 'T99Z'){
							$cont1_4_HOSS000aT99Z++;
						}
					}else if($diag == '') {
						$contvacioH++;
					}
				break;

				case ($edad >=5 && $edad <= 14):
					if($diag){
						if(strtoupper($diag) >= '0000' && strtoupper($diag) <= 'ZZZZ'){
							$cont5_14_HOS0000aZZZZ++;
						}
						if(strtoupper($diag) >= 'J000' && strtoupper($diag) <= 'J99Z'){
							$cont5_14_HOSJ000aJ99Z++;
						}
						if(strtoupper($diag) >= 'I000' && strtoupper($diag) <= 'I99Z'){
							$cont5_14_HOSI000aI99Z++;
						}
						if(strtoupper($diag) >= 'S000' && strtoupper($diag) <= 'T99Z'){
							$cont5_14_HOSS000aT99Z++;
						}
					}else if($diag == '') {
						$contvacioH++;
					}
				break;

				case ($edad >= 15 && $edad <= 64 ):
					if($diag){
						if(strtoupper($diag) >= '0000' && strtoupper($diag) <= 'ZZZZ'){
							$cont15_64_HOS0000aZZZZ++;
						}
						if(strtoupper($diag) >= 'J000' && strtoupper($diag) <= 'J99Z'){
							$cont15_64_HOSJ000aJ99Z++;
						}
						if(strtoupper($diag) >= 'I000' && strtoupper($diag) <= 'I99Z'){
							$cont15_64_HOSI000aI99Z++;
						}
						if(strtoupper($diag) >= 'S000' && strtoupper($diag) <= 'T99A'){
							$cont15_64_HOSS000aT99A++;
						}
					}else if($diag == '') {
						$contvacioH++;
					}
				break;

				case($edad >= 65):
					if($diag){
						if(strtoupper($diag) >= '0000' && strtoupper($diag) <= 'ZZZZ'){
							$cont65_HOS0000aZZZZ++;
						}
						if(strtoupper($diag) >= 'J000' && strtoupper($diag) <= 'J99Z'){
							$cont65_HOSJ000aJ99Z++;
						}
						if(strtoupper($diag) >= 'I000' && strtoupper($diag) <= 'I99Z'){
							$cont65_HOSI000aI99Z++;
						}
						if(strtoupper($diag) >= 'S000' && strtoupper($diag) <= 'T99A'){
							$cont65_HOSS000aT99A++;
						}
					}else if($diag == '') {
						$contvacioH++;
					}
				break;
			}//fin switch
		}//for prestaciones por prestacionEdadTotal

		//SUMA   prestaciones por edad total HOSPITAL
			$total_HOS0000aZZZZ = $contmeses_HOS0000aZZZZ+$cont1_4_HOS0000aZZZZ+$cont5_14_HOS0000aZZZZ+$cont15_64_HOS0000aZZZZ+$cont65_HOS0000aZZZZ;
			$total_HOSJ000aJ99Z = $contmeses_HOSJ000aJ99Z+$cont1_4_HOSJ000aJ99Z+$cont5_14_HOSJ000aJ99Z+$cont15_64_HOSJ000aJ99Z+$cont65_HOSJ000aJ99Z;
			$total_HOSI000aI99Z = $contmeses_HOSI000aI99Z+$cont1_4_HOSI000aI99Z+$cont5_14_HOSI000aI99Z+$cont15_64_HOSI000aI99Z+$cont65_HOSI000aI99Z;
			$total_HOSS000aT99Z = $contmeses_HOSS000aT99Z+$cont1_4_HOSS000aT99Z+$cont5_14_HOSS000aT99Z+$cont15_64_HOSS000aT99A+$cont65_HOSS000aT99Z;
		//SUMA   prestaciones por edad total HOSPITAL

		//CUDRADO DE TOTALES NEA, ANULA, TODOS
		$parametros['est_id'] = 6;
		$respANULA = $reporte->cantidadDAUCerrados($objCon,$parametros);
		$parametros['est_id'] = 7;
		$respNEA = $reporte->cantidadDAUCerrados($objCon,$parametros);
		// $parametros['est_id'] = 'TODOS'; // DAU en estado 5, 6 y 7
		// $respTODOS = $reporte->cantidadDAUCerrados($objCon,$parametros);
		$cantANULA = count($respANULA);
		$cantNEA = count($respNEA);
		//CUADRO!!


		//PACIENTES EN PABELLÓN
		$pacientes1 = 0;
		$pacientes1a4 = 0;
		$pacientes5a14 = 0;
		$pacientes15a64 = 0;
		$pacientes65 = 0;

		foreach ( $pacientesPabellon as $pacientePabellon ) {

			switch ( $pacientePabellon['edadPaciente'] ) {

				case $pacientePabellon['edadPaciente'] < 1 :

					$pacientes1++;

				break;

				case $pacientePabellon['edadPaciente'] >= 1 && $pacientePabellon['edadPaciente'] <= 4 :

					$pacientes1a4++;

				break;

				case $pacientePabellon['edadPaciente'] >= 5 && $pacientePabellon['edadPaciente'] <= 14 :

					$pacientes5a14++;

				break;

				case $pacientePabellon['edadPaciente'] >= 15 && $pacientePabellon['edadPaciente'] <= 64 :

					$pacientes15a64++;

				break;

				case $pacientePabellon['edadPaciente'] >= 65 :

					$pacientes65++;

				break;

			}

		}



		//PACIENTES ATENCION SOSPECHA CORONAVIRUS
		$pacientesSospechaCoronavirusAtencion1 = 0;
		$pacientesSospechaCoronavirusAtencion1a4 = 0;
		$pacientesSospechaCoronavirusAtencion5a14 = 0;
		$pacientesSospechaCoronavirusAtencion15a64 = 0;
		$pacientesSospechaCoronavirusAtencion65 = 0;

		foreach ( $atencionesSospechas as $atencionSospechaCoronavirus ) {

			switch ( $atencionSospechaCoronavirus['edadPaciente'] ) {

				case $atencionSospechaCoronavirus['edadPaciente'] < 1 :

					$pacientesSospechaCoronavirusAtencion1++;

				break;

				case $atencionSospechaCoronavirus['edadPaciente'] >= 1 && $atencionSospechaCoronavirus['edadPaciente'] <= 4 :

					$pacientesSospechaCoronavirusAtencion1a4++;

				break;

				case $atencionSospechaCoronavirus['edadPaciente'] >= 5 && $atencionSospechaCoronavirus['edadPaciente'] <= 14 :

					$pacientesSospechaCoronavirusAtencion5a14++;

				break;

				case $atencionSospechaCoronavirus['edadPaciente'] >= 15 && $atencionSospechaCoronavirus['edadPaciente'] <= 64 :

					$pacientesSospechaCoronavirusAtencion15a64++;

				break;

				case $atencionSospechaCoronavirus['edadPaciente'] >= 65 :

					$pacientesSospechaCoronavirusAtencion65++;

				break;

			}

		}



		//PACIENTES ATENCION CORONAVIRUS
		$pacientesCoronavirusAtencion1 = 0;
		$pacientesCoronavirusAtencion1a4 = 0;
		$pacientesCoronavirusAtencion5a14 = 0;
		$pacientesCoronavirusAtencion15a64 = 0;
		$pacientesCoronavirusAtencion65 = 0;

		foreach ( $atencionesCoronavirus as $atencionCoronavirus ) {

			switch ( $atencionCoronavirus['edadPaciente'] ) {

				case $atencionCoronavirus['edadPaciente'] < 1 :

					$pacientesCoronavirusAtencion1++;

				break;

				case $atencionCoronavirus['edadPaciente'] >= 1 && $atencionCoronavirus['edadPaciente'] <= 4 :

					$pacientesCoronavirusAtencion1a4++;

				break;

				case $atencionCoronavirus['edadPaciente'] >= 5 && $atencionCoronavirus['edadPaciente'] <= 14 :

					$pacientesCoronavirusAtencion5a14++;

				break;

				case $atencionCoronavirus['edadPaciente'] >= 15 && $atencionCoronavirus['edadPaciente'] <= 64 :

					$pacientesCoronavirusAtencion15a64++;

				break;

				case $atencionCoronavirus['edadPaciente'] >= 65 :

					$pacientesCoronavirusAtencion65++;

				break;

			}

		}



		//PACIENTES ATENCION SOSPECHA CORONAVIRUS HOSPITALIZADOS
		$pacientesSospechaCoronavirusHospitalizacion1 = 0;
		$pacientesSospechaCoronavirusHospitalizacion1a4 = 0;
		$pacientesSospechaCoronavirusHospitalizacion5a14 = 0;
		$pacientesSospechaCoronavirusHospitalizacion15a64 = 0;
		$pacientesSospechaCoronavirusHospitalizacion65 = 0;

		foreach ( $hospitalizacionesSospechaCoronavirus as $hospitalizacionSospechaCoronavirus ) {

			switch ( $hospitalizacionSospechaCoronavirus['edadPaciente'] ) {

				case $hospitalizacionSospechaCoronavirus['edadPaciente'] < 1 :

					$pacientesSospechaCoronavirusHospitalizacion1++;

				break;

				case $hospitalizacionSospechaCoronavirus['edadPaciente'] >= 1 && $hospitalizacionSospechaCoronavirus['edadPaciente'] <= 4 :

					$pacientesSospechaCoronavirusHospitalizacion1a4++;

				break;

				case $hospitalizacionSospechaCoronavirus['edadPaciente'] >= 5 && $hospitalizacionSospechaCoronavirus['edadPaciente'] <= 14 :

					$pacientesSospechaCoronavirusHospitalizacion5a14++;

				break;

				case $hospitalizacionSospechaCoronavirus['edadPaciente'] >= 15 && $hospitalizacionSospechaCoronavirus['edadPaciente'] <= 64 :

					$pacientesSospechaCoronavirusHospitalizacion15a64++;

				break;

				case $hospitalizacionSospechaCoronavirus['edadPaciente'] >= 65 :

					$pacientesSospechaCoronavirusHospitalizacion65++;

				break;

			}

		}



		//PACIENTES ATENCION CORONAVIRUS HOSPITALIZADOS
		$pacientesCoronavirusHospitalizacion1 = 0;
		$pacientesCoronavirusHospitalizacion1a4 = 0;
		$pacientesCoronavirusHospitalizacion5a14 = 0;
		$pacientesCoronavirusHospitalizacion15a64 = 0;
		$pacientesCoronavirusHospitalizacion65 = 0;

		foreach ( $hospitalizacionesCoronavirus as $hospitalizacionCoronavirus ) {

			switch ( $hospitalizacionCoronavirus['edadPaciente'] ) {

				case $hospitalizacionCoronavirus['edadPaciente'] < 1 :

					$pacientesCoronavirusHospitalizacion1++;

				break;

				case $hospitalizacionCoronavirus['edadPaciente'] >= 1 && $hospitalizacionCoronavirus['edadPaciente'] <= 4 :

					$pacientesCoronavirusHospitalizacion1a4++;

				break;

				case $hospitalizacionCoronavirus['edadPaciente'] >= 5 && $hospitalizacionCoronavirus['edadPaciente'] <= 14 :

					$pacientesCoronavirusHospitalizacion5a14++;

				break;

				case $hospitalizacionCoronavirus['edadPaciente'] >= 15 && $hospitalizacionCoronavirus['edadPaciente'] <= 64 :

					$pacientesCoronavirusHospitalizacion15a64++;

				break;

				case $hospitalizacionCoronavirus['edadPaciente'] >= 65 :

					$pacientesCoronavirusHospitalizacion65++;

				break;

			}

		}



		//PACIENTES ATENCION CORONAVIRUS HOSPITALIZADOS
		$totalDemanda1 = 0;
		$totalDemanda1a4 = 0;
		$totalDemanda5a14 = 0;
		$totalDemanda15a64 = 0;
		$totalDemanda65 = 0;

		foreach ( $totalDemandas as $totalDemanda ) {

			switch ( $totalDemanda['edadPaciente'] ) {

				case  $totalDemanda['edadPaciente'] >= 1 && $totalDemanda['edadPaciente'] < 1 :

					$totalDemanda1++;

				break;

				case $totalDemanda['edadPaciente'] >= 1 && $totalDemanda['edadPaciente'] <= 4 :

					$totalDemanda1a4++;

				break;

				case $totalDemanda['edadPaciente'] >= 5 && $totalDemanda['edadPaciente'] <= 14 :

					$totalDemanda5a14++;

				break;

				case $totalDemanda['edadPaciente'] >= 15 && $totalDemanda['edadPaciente'] <= 64 :

					$totalDemanda15a64++;

				break;

				case $totalDemanda['edadPaciente'] >= 65 :

					$totalDemanda65++;

				break;

			}

		}

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

		<table border="0">
			<tr>
				<td align="center">
					<strong style="font-size:10; color: ">ATENCIONES Y HOSPITALIZACIONES DE URGENCIA POR TODAS LAS CAUSAS EN UNIDADES DE EMERGENCIA '.$_POST['fechaInicio'].' AL '.$_POST['fechaFin'].'</strong>
				</td>
		    </tr>
		</table>
		<br>

		<table width="100%" border="1" >

				<tr class="">
					<td width="32%" bgcolor="#CCCCCC" align="center"><strong>&nbsp;&nbsp;TOTAL DEMANDAS</strong> </td>
					<td width="12%" bgcolor="#CCCCCC" align="center"><strong>&nbsp;&nbsp;'.$totalDemanda1.'</strong></td>
					<td width="12%" bgcolor="#CCCCCC" align="center"><strong>&nbsp;&nbsp;'.$totalDemanda1a4.'</strong></td>
					<td width="12%" bgcolor="#CCCCCC" align="center"><strong>&nbsp;&nbsp;'.$totalDemanda5a14.'</strong></td>
					<td width="12%" bgcolor="#CCCCCC" align="center"><strong>&nbsp;&nbsp;'.$totalDemanda15a64.'</strong></td>
					<td width="12%" bgcolor="#CCCCCC" align="center"><strong>&nbsp;&nbsp;'.$totalDemanda65.'</strong></td>
					<td width="12%" bgcolor="#CCCCCC" align="center"><strong>&nbsp;&nbsp;'.count($totalDemandas).'</strong></td>
				</tr>
				<tr class="">
					<td width="32%" bgcolor="#CCCCCC" align="center"><strong>&nbsp;&nbsp;ATENCIONES Y HOSPITALIZACIONES</strong> </td>
					<td width="12%" bgcolor="#CCCCCC" align="center"><strong>&nbsp;&nbsp;-1 año</strong></td>
					<td width="12%" bgcolor="#CCCCCC" align="center"><strong>&nbsp;&nbsp;1-4 años</strong></td>
					<td width="12%" bgcolor="#CCCCCC" align="center"><strong>&nbsp;&nbsp;5-14 años</strong></td>
					<td width="12%" bgcolor="#CCCCCC" align="center"><strong>&nbsp;&nbsp;15-64 años</strong></td>
					<td width="12%" bgcolor="#CCCCCC" align="center"><strong>&nbsp;&nbsp;65 años y +</strong></td>
					<td width="12%" bgcolor="#CCCCCC" align="center"><strong>&nbsp;&nbsp;TOTAL</strong></td>
				</tr>

				<tr align="left" valign="top" bgcolor="#BFBFBF" >
				<td width="32%" bgcolor="#BFBFBF"><strong>&nbsp;&nbsp;SECCIÓN 1.<br />&nbsp;&nbsp;TOTAL ATENCIONES DE URGENCIA</strong> </td>
					<td width="12%" align="right" valign="bottom">'.$t1 = $contmeses_0000aZZZZ + $contvacioMESES + $pacientesSospechaCoronavirusAtencion1 + $pacientesCoronavirusAtencion1.'</td>
					<td width="12%" align="right" valign="bottom">'.$t2 = $cont1_4_0000aZZZZ   + $contvacio1_4 + $pacientesSospechaCoronavirusAtencion1a4 + $pacientesCoronavirusAtencion1a4.'</td>
					<td width="12%" align="right" valign="bottom">'.$t3 = $cont5_14_0000aZZZZ  + $contvacio5_14 + $pacientesSospechaCoronavirusAtencion5a14 + $pacientesCoronavirusAtencion5a14.'</td>
					<td width="12%" align="right" valign="bottom">'.$t4 = $cont15_64_0000aZZZZ + $contvacio15_64 + $pacientesSospechaCoronavirusAtencion15a64 + $pacientesCoronavirusAtencion15a64.'</td>
					<td width="12%" align="right" valign="bottom">'.$t5 = $cont65_0000aZZZZ    + $contvacio65 + $pacientesSospechaCoronavirusAtencion65 + $pacientesCoronavirusAtencion65.'</td>
					<td width="12%" align="right" valign="bottom">'.$TOTAL_URGENCIAS = $contmeses_0000aZZZZ + $contvacioMESES + $cont1_4_0000aZZZZ   + $contvacio1_4 + $cont5_14_0000aZZZZ  + $contvacio5_14 + $cont15_64_0000aZZZZ + $contvacio15_64 + $cont65_0000aZZZZ    + $contvacio65 + count($atencionesSospechas) + count($atencionesCoronavirus).'</td>
				</tr>

				<tr align="left" valign="top" bgcolor="#E0E0E0" >
					<td width="32%">&nbsp;&nbsp;Total Causas Sistema Respiratorio</td>
					<td width="12%" align="right">'.($contmeses_J000aJ99Z + $pacientesSospechaCoronavirusAtencion1 + $pacientesCoronavirusAtencion1).'</td>
					<td width="12%" align="right">'.($cont1_4_J000aJ99Z + $pacientesSospechaCoronavirusAtencion1a4 + $pacientesCoronavirusAtencion1a4).'</td>
					<td width="12%" align="right">'.($cont5_14_J000aJ99Z + $pacientesSospechaCoronavirusAtencion5a14 + $pacientesCoronavirusAtencion5a14).'</td>
					<td width="12%" align="right">'.($cont15_64_J000aJ99Z + $pacientesSospechaCoronavirusAtencion15a64 + $pacientesCoronavirusAtencion15a64).'</td>
					<td width="12%" align="right">'.($cont65_J000aJ99Z + $pacientesSospechaCoronavirusAtencion65 + $pacientesCoronavirusAtencion65).'</td>
					<td width="12%" align="right">'.($total_J000aJ99Z + count($atencionesSospechas) + count($atencionesCoronavirus)).'</td>
				</tr>

				<tr align="left" valign="top" >
					<td width="32%">&nbsp;&nbsp;- IRA Alta (J00-J06)</td>
					<td width="12%" align="right">'.$contmeses_J000aJ06Z.'</td>
					<td width="12%" align="right">'.$cont1_4_J000aJ06Z.'</td>
					<td width="12%" align="right">'.$cont5_14_J000aJ06Z.'</td>
					<td width="12%" align="right">'.$cont15_64_J000aJ06Z.'</td>
					<td width="12%" align="right">'.$cont65_J000aJ06Z.'</td>
					<td width="12%" align="right">'.$total_J000aJ06Z.'</td>
				</tr>

				<tr align="left" valign="top" >
					<td width="32%">&nbsp;&nbsp;- Influenza (J09-J11)</td>
					<td width="12%" align="right">'.$contmeses_J090aJ111.'</td>
					<td width="12%" align="right">'.$cont1_4_J090aJ111.'</td>
					<td width="12%" align="right">'.$cont5_14_J090aJ111.'</td>
					<td width="12%" align="right">'.$cont15_64_J090aJ111.'</td>
					<td width="12%" align="right">'.$cont65_J090aJ111.'</td>
					<td width="12%" align="right">'.$total_J090aJ111.'</td>
				</tr>

				<tr align="left" valign="top" >
					<td width="32%">&nbsp;&nbsp;- Neumon&iacute;a (J12-J18)</td>
					<td width="12%" align="right">'.$contmeses_J120aJ18Z.'</td>
					<td width="12%" align="right">'.$cont1_4_J120aJ18Z.'</td>
					<td width="12%" align="right">'.$cont5_14_J120aJ18Z.'</td>
					<td width="12%" align="right">'.$cont15_64_J120aJ18Z.'</td>
					<td width="12%" align="right">'.$cont65_J120aJ18Z.'</td>
					<td width="12%" align="right">'.$total_J120aJ18Z.'</td>
				</tr>

				<tr align="left" valign="top" >
					<td width="32%">&nbsp;&nbsp;- Bronquitis/bronquiolitis aguda (J20-J21)</td>
					<td width="12%" align="right">'.$contmeses_J200aJ21Z.'</td>
					<td width="12%" align="right">'.$cont1_4_J200aJ21Z.'</td>
					<td width="12%" align="right">'.$cont5_14_J200aJ21Z.'</td>
					<td width="12%" align="right">'.$cont15_64_J200aJ21Z.'</td>
					<td width="12%" align="right">'.$cont65_J200aJ21Z.'</td>
					<td width="12%" align="right">'.$total_J200aJ21Z.'</td>
				</tr>

				<tr align="left" valign="top" >
					<td width="32%">&nbsp;&nbsp;- Crisis obstructiva bronquial (J40-J46)</td>
					<td width="12%" align="right">'.$contmeses_J400aJ46Z.'</td>
					<td width="12%" align="right">'.$cont1_4_J400aJ46Z.'</td>
					<td width="12%" align="right">'.$cont5_14_J400aJ46Z.'</td>
					<td width="12%" align="right">'.$cont15_64_J400aJ46Z.'</td>
					<td width="12%" align="right">'.$cont65_J400aJ46Z.'</td>
					<td width="12%" align="right">'.$total_J400aJ46Z.'</td>
				</tr>

				<tr align="left" valign="top">
					<td width="32%">&nbsp;&nbsp;- Otra causa respiratoria &nbsp;&nbsp;(J22,J30-J39,J47,J60-J98)</td>
					<td width="12%" align="right">'.$otra1 = ($contmeses_J000aJ99Z 	- $contmeses_J000aJ06Z 	- $contmeses_J090aJ111 	- $contmeses_J120aJ18Z 	- $contmeses_J200aJ21Z 	- $contmeses_J400aJ46Z).'</td>
					<td width="12%" align="right">'.$otra2 = ($cont1_4_J000aJ99Z 	- $cont1_4_J000aJ06Z 	- $cont1_4_J090aJ111	- $cont1_4_J120aJ18Z 	- $cont1_4_J200aJ21Z 	- $cont1_4_J400aJ46Z).'</td>
					<td width="12%" align="right">'.$otra3 = ($cont5_14_J000aJ99Z 	- $cont5_14_J000aJ06Z 	- $cont5_14_J090aJ111	- $cont5_14_J120aJ18Z	- $cont5_14_J200aJ21Z 	- $cont5_14_J400aJ46Z).'</td>
					<td width="12%" align="right">'.$otra4 = ($cont15_64_J000aJ99Z 	- $cont15_64_J000aJ06Z 	- $cont15_64_J090aJ111 	- $cont15_64_J120aJ18Z 	- $cont15_64_J200aJ21Z 	- $cont15_64_J400aJ46Z).'</td>
					<td width="12%" align="right">'.$otra5 = ($cont65_J000aJ99Z 	- $cont65_J000aJ06Z 	- $cont65_J090aJ111 	- $cont65_J120aJ18Z 	- $cont65_J200aJ21Z 	- $cont65_J400aJ46Z).'</td>
					<td width="12%" align="right">'.$otra6 = ($total_J000aJ99Z 		- $total_J000aJ06Z 		- $total_J090aJ111 		- $total_J120aJ18Z 		- $total_J200aJ21Z 		- $total_J400aJ46Z).'</td>
				</tr>

				<tr align="left" valign="top">
					<td width="32%">&nbsp;&nbsp;- Sospecha Coronavirus</td>
					<td width="12%" align="right">'.$pacientesSospechaCoronavirusAtencion1.'</td>
					<td width="12%" align="right">'.$pacientesSospechaCoronavirusAtencion1a4.'</td>
					<td width="12%" align="right">'.$pacientesSospechaCoronavirusAtencion5a14.'</td>
					<td width="12%" align="right">'.$pacientesSospechaCoronavirusAtencion15a64.'</td>
					<td width="12%" align="right">'.$pacientesSospechaCoronavirusAtencion65.'</td>
					<td width="12%" align="right">'.count($atencionesSospechas).'</td>
				</tr>

				<tr align="left" valign="top">
					<td width="32%">&nbsp;&nbsp;- Coronavirus (U0071)</td>
					<td width="12%" align="right">'.$pacientesCoronavirusAtencion1.'</td>
					<td width="12%" align="right">'.$pacientesCoronavirusAtencion1a4.'</td>
					<td width="12%" align="right">'.$pacientesCoronavirusAtencion5a14.'</td>
					<td width="12%" align="right">'.$pacientesCoronavirusAtencion15a64.'</td>
					<td width="12%" align="right">'.$pacientesCoronavirusAtencion65.'</td>
					<td width="12%" align="right">'.count($atencionesCoronavirus).'</td>
				</tr>

				<tr align="left" valign="top"  bgcolor="#E0E0E0">
					<td width="32%">&nbsp;&nbsp;Total Causas Sistema Circulatorio</td>
					<td width="12%" align="right">'.$contmeses_I000aIZZZ.'</td>
					<td width="12%" align="right">'.$cont1_4_I000aIZZZ.'</td>
					<td width="12%" align="right">'.$cont5_14_I000aIZZZ.'</td>
					<td width="12%" align="right">'.$cont15_64_I000aIZZZ.'</td>
					<td width="12%" align="right">'.$cont65_I000aIZZZ.'</td>
					<td width="12%" align="right">'.$total_I000aIZZZ.'</td>
				</tr>

				<tr align="left" valign="top" >
					<td width="32%">&nbsp;&nbsp;- Infarto agudo miocardio</td>
					<td width="12%" align="right">'.$contmeses_I219aI219.'</td>
					<td width="12%" align="right">'.$cont1_4_I219aI219.'</td>
					<td width="12%" align="right">'.$cont5_14_I219aI219.'</td>
					<td width="12%" align="right">'.$cont15_64_I219aI219.'</td>
					<td width="12%" align="right">'.$cont65_I219aI219.'</td>
					<td width="12%" align="right">'.$total_I219aI219.'</td>
				</tr>

				<tr align="left" valign="top" >
					<td width="32%">&nbsp;&nbsp;- Accidente vascular encefálico</td>
					<td width="12%" align="right">'.$contmeses_I64XaI64X.'</td>
					<td width="12%" align="right">'.$cont1_4_I64XaI64X.'</td>
					<td width="12%" align="right">'.$cont5_14_I64XaI64X.'</td>
					<td width="12%" align="right">'.$cont15_64_I64XaI64X.'</td>
					<td width="12%" align="right">'.$cont65_I640aI640.'</td>
					<td width="12%" align="right">'.$total_I64XaI64X.'</td>
				</tr>

				<tr align="left" valign="top">
					<td width="32%">&nbsp;&nbsp;- Crisis hipertensiva</td>
					<td width="12%" align="right">'.$contmeses_I10XaI10X.'</td>
					<td width="12%" align="right">'.$cont1_4_I10XaI10X.'</td>
					<td width="12%" align="right">'.$cont5_14_I10XaI10X.'</td>
					<td width="12%" align="right">'.$cont15_64_I10XaI10X.'</td>
					<td width="12%" align="right">'.$cont65_I10XaI10X.'</td>
					<td width="12%" align="right">'.$total_I10XaI10X.'</td>
				</tr>

				<tr align="left" valign="top">
					<td width="32%">&nbsp;&nbsp;- Arritmia severa</td>
					<td width="12%" align="right">'.$contmeses_I499aI499.'</td>
					<td width="12%" align="right">'.$cont1_4_I499aI499.'</td>
					<td width="12%" align="right">'.$cont5_14_I499aI499.'</td>
					<td width="12%" align="right">'.$cont15_64_I499aI499.'</td>
					<td width="12%" align="right">'.$cont65_I499aI499.'</td>
					<td width="12%" align="right">'.$total_I499aI499.'</td>
				</tr>

				<tr align="left" valign="top">
					<td width="32%">&nbsp;&nbsp;- Otras causas circulatorias</td>
					<td width="12%" align="right">'.$otraCir1 = $contmeses_I000aIZZZ		- $contmeses_I219aI219		- $contmeses_I64XaI64X		- $contmeses_I10XaI10X		- $contmeses_I499aI499.'</td>
					<td width="12%" align="right">'.$otraCir2 = $cont1_4_I000aIZZZ 		    - $cont1_4_I219aI219		- $cont1_4_I64XaI64X		- $cont1_4_I10XaI10X		- $cont1_4_I499aI499.'</td>
					<td width="12%" align="right">'.$otraCir3 = $cont5_14_I000aIZZZ		    - $cont5_14_I219aI219		- $cont5_14_I64XaI64X		- $cont5_14_I10XaI10X		- $cont5_14_I499aI499.'</td>
					<td width="12%" align="right">'.$otraCir4 = $cont15_64_I000aIZZZ 	    - $cont15_64_I219aI219		- $cont15_64_I64XaI64X		- $cont15_64_I10XaI10X		- $cont15_64_I499aI499.'</td>
					<td width="12%" align="right">'.$otraCir5 = $cont65_I000aIZZZ 		    - $cont65_I219aI219			- $cont65_I640aI640			- $cont65_I10XaI10X			- $cont65_I499aI499.'</td>
					<td width="12%" align="right">'.$otraCir6 = $total_I000aIZZZ 		    - $total_I219aI219			- $total_I64XaI64X			- $total_I10XaI10X			- $total_I499aI499.'</td>
				</tr>

				<tr align="left" valign="top"  bgcolor="#E0E0E0">
					<td>Total Traumatismos y Envenenamientos</td>
					<td align="right">'.$contmeses_S000aT99Z.'</td>
					<td align="right">'.$cont1_4_S000aT99Z.'</td>
					<td align="right">'.$cont5_14_S000aT99Z.'</td>
					<td align="right">'.$cont15_64_S000aT99Z.'</td>
					<td align="right">'.$cont65_S000aT99Z.'</td>
					<td align="right">'.$total_S000aT99Z.'</td>
				</tr>

				<tr align="left" valign="top">
					<td width="32%">&nbsp;&nbsp;Accidentes del tr&aacute;nsito</td>
					<td width="12%" align="right">'.$contmeses_ACCS000aT99Z.'</td>
					<td width="12%" align="right">'.$cont1_4_ACCS000aT99Z.'</td>
					<td width="12%" align="right">'.$cont5_14_ACCS000aT99Z.'</td>
					<td width="12%" align="right">'.$cont15_64_ACCS000aT99Z.'</td>
					<td width="12%" align="right">'.$cont65_ACCS000aT99Z.'</td>
					<td width="12%" align="right">'.$total_ACCS000aT99Z.'</td>
				</tr>

				<tr align="left" valign="top">
					<td width="32%">&nbsp;&nbsp;Otras causas externas</td>
					<td width="12%" align="right">'.$otraExt1 = $contmeses_S000aT99Z - $contmeses_ACCS000aT99Z.'</td>
					<td width="12%" align="right">'.$otraExt2 = $cont1_4_S000aT99Z	  - $cont1_4_ACCS000aT99Z.'</td>
					<td width="12%" align="right">'.$otraExt3 = $cont5_14_S000aT99Z  - $cont5_14_ACCS000aT99Z.'</td>
					<td width="12%" align="right">'.$otraExt4 = $cont15_64_S000aT99Z - $cont15_64_ACCS000aT99Z.'</td>
					<td width="12%" align="right">'.$otraExt5 = $cont65_S000aT99Z	  - $cont65_ACCS000aT99Z.'</td>
					<td width="12%" align="right">'.$otraExt6 = $total_S000aT99Z	  - $total_ACCS000aT99Z.'</td>
				</tr>

				<tr align="left" valign="top">
					<td width="32%">&nbsp;&nbsp;Total Diarrea Aguda (A00-A09)</td>
					<td width="12%" align="right">'.$contmeses_A000aA09X.'</td>
					<td width="12%" align="right">'.$cont1_4_A000aA09X.'</td>
					<td width="12%" align="right">'.$cont5_14_A000aA09X.'</td>
					<td width="12%" align="right">'.$cont15_64_A000aA09X.'</td>
					<td width="12%" align="right">'.$cont65_A000aA09X.'</td>
					<td width="12%" align="right">'.$total_A000aA09X.'</td>
				</tr>

				<tr align="left" valign="top">
					<td width="32%">&nbsp;&nbsp;Las dem&aacute;s Causas</td>
					<td width="12%" align="right">'.$d1 = ($contmeses_0000aZZZZ - $contmeses_J000aJ99Z - $contmeses_I000aIZZZ - $contmeses_S000aT99Z - $contmeses_A000aA09X).'</td>
					<td width="12%" align="right">'.$d2 = ($cont1_4_0000aZZZZ   - $cont1_4_J000aJ99Z   - $cont1_4_I000aIZZZ   - $cont1_4_S000aT99Z   - $cont1_4_A000aA09X).'</td>
					<td width="12%" align="right">'.$d3 = ($cont5_14_0000aZZZZ  - $cont5_14_J000aJ99Z  - $cont5_14_I000aIZZZ  - $cont5_14_S000aT99Z  - $cont5_14_A000aA09X).'</td>
					<td width="12%" align="right">'.$d4 = ($cont15_64_0000aZZZZ - $cont15_64_J000aJ99Z - $cont15_64_I000aIZZZ - $cont15_64_S000aT99Z - $cont15_64_A000aA09X).'</td>
					<td width="12%" align="right">'.$d5 = ($cont65_0000aZZZZ    - $cont65_J000aJ99Z    - $cont65_I000aIZZZ    - $cont65_S000aT99Z    - $cont65_A000aA09X).'</td>
					<td width="12%" align="right">'.$X  = ($total_0000aZZZZ     - $total_J000aJ99Z     - $total_I000aIZZZ     - $total_S000aT99Z     - $total_A000aA09X).'</td>
				</tr>

				<tr align="left" valign="top" bgcolor="#E0E0E0" >
					<td width="32%">&nbsp;&nbsp;Atenciones sin Diagnóstico</td>
					<td width="12%" align="right">'.$contvacioMESES.'</td>
					<td width="12%" align="right">'.$contvacio1_4.'</td>
					<td width="12%" align="right">'.$contvacio5_14.'</td>
					<td width="12%" align="right">'.$contvacio15_64.'</td>
					<td width="12%" align="right">'.$contvacio65.'</td>
					<td width="12%" align="right">'.$contTOTALvacio = $contvacioMESES + $contvacio1_4 + $contvacio5_14 + $contvacio15_64 + $contvacio65.'</td>
				</tr>

				<tr align="left" valign="top " bgcolor="#BFBFBF" >
        			<td width="32%"><strong>&nbsp;&nbsp;SECCI&Oacute;N 2.<br />&nbsp;&nbsp;TOTAL HOSPITALIZACIONES</strong></td>
					<td width="12%" align="right">'.($contmeses_HOS0000aZZZZ + $pacientesSospechaCoronavirusHospitalizacion1 + $pacientesCoronavirusHospitalizacion1 + $pacientes1).'</td>
					<td width="12%" align="right">'.($cont1_4_HOS0000aZZZZ + $pacientesSospechaCoronavirusHospitalizacion1a4 + $pacientesCoronavirusHospitalizacion1a4 + $pacientes1a4).'</td>
					<td width="12%" align="right">'.($cont5_14_HOS0000aZZZZ + $pacientesSospechaCoronavirusHospitalizacion5a14 + $pacientesCoronavirusHospitalizacion5a14 + $pacientes5a14).'</td>
					<td width="12%" align="right">'.($cont15_64_HOS0000aZZZZ + $pacientesSospechaCoronavirusHospitalizacion15a64 + $pacientesCoronavirusHospitalizacion15a64 + $pacientes15a64).'</td>
					<td width="12%" align="right">'.($cont65_HOS0000aZZZZ + $pacientesSospechaCoronavirusHospitalizacion65 + $pacientesCoronavirusHospitalizacion65 + $pacientes65).'</td>
					<td width="12%" align="right">'.($total_HOS0000aZZZZ + count($hospitalizacionesSospechaCoronavirus) + count($hospitalizacionesCoronavirus) + count($pacientesPabellon)).'</td>
				</tr>

				<tr align="left" valign="top" >
					<td width="32%">&nbsp;&nbsp;- Causas Respiratorias</td>
					<td width="12%" align="right">'.$contmeses_HOSJ000aJ99Z.'</td>
					<td width="12%" align="right">'.$cont1_4_HOSJ000aJ99Z.'</td>
					<td width="12%" align="right">'.$cont5_14_HOSJ000aJ99Z.'</td>
					<td width="12%" align="right">'.$cont15_64_HOSJ000aJ99Z.'</td>
					<td width="12%" align="right">'.$cont65_HOSJ000aJ99Z.'</td>
					<td width="12%" align="right">'.$total_HOSJ000aJ99Z.'</td>
				</tr>

				<tr align="left" valign="top">
					<td width="32%">&nbsp;&nbsp;- Sospecha Coronavirus</td>
					<td width="12%" align="right">'.$pacientesSospechaCoronavirusHospitalizacion1.'</td>
					<td width="12%" align="right">'.$pacientesSospechaCoronavirusHospitalizacion1a4.'</td>
					<td width="12%" align="right">'.$pacientesSospechaCoronavirusHospitalizacion5a14.'</td>
					<td width="12%" align="right">'.$pacientesSospechaCoronavirusHospitalizacion15a64.'</td>
					<td width="12%" align="right">'.$pacientesSospechaCoronavirusHospitalizacion65.'</td>
					<td width="12%" align="right">'.count($hospitalizacionesSospechaCoronavirus).'</td>
				</tr>

				<tr align="left" valign="top">
					<td width="32%">&nbsp;&nbsp;- Coronavirus (U0071)</td>
					<td width="12%" align="right">'.$pacientesCoronavirusHospitalizacion1.'</td>
					<td width="12%" align="right">'.$pacientesCoronavirusHospitalizacion1a4.'</td>
					<td width="12%" align="right">'.$pacientesCoronavirusHospitalizacion5a14.'</td>
					<td width="12%" align="right">'.$pacientesCoronavirusHospitalizacion15a64.'</td>
					<td width="12%" align="right">'.$pacientesCoronavirusHospitalizacion65.'</td>
					<td width="12%" align="right">'.count($hospitalizacionesCoronavirus).'</td>
				</tr>

				<tr align="left" valign="top" >
					<td width="32%">&nbsp;&nbsp;- Causas Circulatorias</td>
					<td width="12%" align="right">'.$contmeses_HOSI000aI99Z.'</td>
					<td width="12%" align="right">'.$cont1_4_HOSI000aI99Z.'</td>
					<td width="12%" align="right">'.$cont5_14_HOSI000aI99Z.'</td>
					<td width="12%" align="right">'.$cont15_64_HOSI000aI99Z.'</td>
					<td width="12%" align="right">'.$cont65_HOSI000aI99Z.'</td>
					<td width="12%" align="right">'.$total_HOSI000aI99Z.'</td>
				</tr>

				<tr align="left" valign="top" >
					<td width="32%">&nbsp;&nbsp;- Traumatismos y Envenenamientos</td>
					<td width="12%" align="right">'.$contmeses_HOSS000aT99Z.'</td>
					<td width="12%" align="right">'.$cont1_4_HOSS000aT99Z.'</td>
					<td width="12%" align="right">'.$cont5_14_HOSS000aT99Z.'</td>
					<td width="12%" align="right">'.$cont15_64_HOSS000aT99A.'</td>
					<td width="12%" align="right">'.$cont65_HOSS000aT99Z.'</td>
					<td width="12%" align="right">'.$total_HOSS000aT99Z.'</td>
				</tr>

				<tr align="left" valign="top" >
					<td width="32%">&nbsp;&nbsp;- Las dem&aacute;s Causas</td>
					<td width="12%" align="right">'.$demasCausa1 = $contmeses_HOS0000aZZZZ	- $contmeses_HOSJ000aJ99Z	- $contmeses_HOSI000aI99Z	- $contmeses_HOSS000aT99Z.'</td>
					<td width="12%" align="right">'.$demasCausa2 = $cont1_4_HOS0000aZZZZ	- $cont1_4_HOSJ000aJ99Z		- $cont1_4_HOSI000aI99Z		- $cont1_4_HOSS000aT99Z.'</td>
					<td width="12%" align="right">'.$demasCausa3 = $cont5_14_HOS0000aZZZZ	- $cont5_14_HOSJ000aJ99Z	- $cont5_14_HOSI000aI99Z	- $cont5_14_HOSS000aT99Z.'</td>
					<td width="12%" align="right">'.$demasCausa4 = $cont15_64_HOS0000aZZZZ	- $cont15_64_HOSJ000aJ99Z	- $cont15_64_HOSI000aI99Z	- $cont15_64_HOSS000aT99A.'</td>
					<td width="12%" align="right">'.$demasCausa5 = $cont65_HOS0000aZZZZ		- $cont65_HOSJ000aJ99Z		- $cont65_HOSI000aI99Z		- $cont65_HOSS000aT99Z.'</td>
					<td width="12%" align="right">'.$demasCausa6 = $total_HOS0000aZZZZ		- $total_HOSJ000aJ99Z		- $total_HOSI000aI99Z 		- $total_HOSS000aT99Z.'</td>
				</tr>

				<tr align="left" valign="top" >
					<td width="32%">&nbsp;&nbsp;- Cirug&iacute;as de Urgencia</td>
					<td width="12%" align="right">'.$pacientes1.'</td>
					<td width="12%" align="right">'.$pacientes1a4.'</td>
					<td width="12%" align="right">'.$pacientes5a14.'</td>
					<td width="12%" align="right">'.$pacientes15a64.'</td>
					<td width="12%" align="right">'.$pacientes65.'</td>
					<td width="12%" align="right">'.count($pacientesPabellon).'</td>
				</tr>

			</table>
			<br>
			<table border="0">
				<tr>
					<td align="center">
						<strong style="font-size:10; color: ">RESUMEN DE TOTAL DE ATENCIONES</strong>
					</td>
			    </tr>
			</table>
			<br>
			<table width="100%" border="1" >
				<tr valign="top" bgcolor="#BFBFBF">
					<td></td>
					<td width="15%" align="center"><strong>ANULA</strong></td>
					<td width="15%" align="center"><strong>N.E.A.</strong></td>
					<td width="30%" align="center"><strong>TODAS (CERRADOS, ANULA Y N.E.A.)</strong></td>
				</tr>
				<tr>
					<td bgcolor="#BFBFBF" align="center"><strong>TOTAL DE ATENCIONES</strong></td>
					<td width="15%" align="ceter">'.$cantANULA.'</td>
					<td width="15%" align="ceter">'.$cantNEA.'</td>
					<td width="30%" align="ceter">'. (($contmeses_0000aZZZZ + $contvacioMESES + $cont1_4_0000aZZZZ   + $contvacio1_4 + $cont5_14_0000aZZZZ  + $contvacio5_14 + $cont15_64_0000aZZZZ + $contvacio15_64 + $cont65_0000aZZZZ    + $contvacio65) + $cantANULA + $cantNEA) .'</td>
				</tr>
			</table>
			<br>
			<tr>

				<td colspan="3"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="reportechico">
					<tr>
						<td  style="text-decoration:none;color:#666">Nombre responsable de la informaci&oacute;n :  </td>
					</tr>
					<tr>
						<td  style="text-decoration:none;color:#666">Fecha Emisión Reporte: <strong>'.date('d-m-Y').'</strong></td>
					</tr>
				</table></td>
			</tr>';

	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output('reportesLibroAccidentes.pdf','FI');
	$url = RAIZ."/views/reportes/salidas/reportesLibroAccidentes.pdf";
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