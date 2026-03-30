<?php

ini_set('memory_limit', '5G');

// require($_SERVER['DOCUMENT_ROOT']."/dauRCE/config/config.php");

// require($_SERVER['DOCUMENT_ROOT']."/dauRCE/class/Connection.class.php");

// require($_SERVER['DOCUMENT_ROOT']."/dauRCE/class/Util.class.php");

// require($_SERVER['DOCUMENT_ROOT']."/dauRCE/class/Reportes.class.php");

// require($_SERVER['DOCUMENT_ROOT']."/estandar/PHPExcel/Classes/PHPExcel.php");
// 
error_reporting(0);
require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php');       				$objCon    		= new Connection(); $objCon->db_connect();
require_once('../../../../class/Util.class.php');       					$objUtil    	= new Util;
require_once('../../../../class/Reportes.class.php');       				$objReportes    = new Reportes;
require_once('../../../../../estandar/PHPExcel8/Classes/PHPExcel.php');   $objPHPExcel      = new PHPExcel();

	$parametros = array();
	$parametros['fechaInicio']    = $objUtil->fechaInvertida($_GET['fechaInicio']);
	$parametros['fechaFin']       = $objUtil->fechaInvertida($_GET['fechaFin']);

	$datos = $objReportes->listarSalaHidratacion_u_Observacion($objCon,$parametros);

// $datos                    = $objReportes->registroHospitalizacionPanelViral($objCon,$parametros);
	$fecha = date("d-m-Y");
	// header('Content-Disposition: attachment;filename="listadoHidritacion_u_obsercacion_'.$fecha.'.xls"');



	$objPHPExcel = new PHPExcel();

	$objPHPExcel 				= new PHPExcel();
	$objPHPExcel->getProperties()->setCreator("Plataforma Web HJNC")
							 ->setLastModifiedBy("Plataforma Web HJNC")
							 ->setTitle("Reporte Lavanderia")
							 ->setSubject("Office 2007 XLSX Document")
							 ->setDescription("Excel Document")
							 ->setKeywords("HJNC")
							 ->setCategory("Lavanderia");	
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);

	$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

	$styleArray = array(
    	'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'FFFFFF')
    ));

	$objPHPExcel->getActiveSheet()->getCell('A1')->setValue("N° DAU");
	$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);

	$objPHPExcel->getActiveSheet()->getCell('B1')->setValue("FECHA Y HORA DAU");
	$objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray);

	$objPHPExcel->getActiveSheet()->getCell('C1')->setValue("TIPO DOCUMENTO");
	$objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray($styleArray);

	$objPHPExcel->getActiveSheet()->getCell('D1')->setValue("NUMERO DE DOCUMENTO");
	$objPHPExcel->getActiveSheet()->getStyle('D1')->applyFromArray($styleArray);

	$objPHPExcel->getActiveSheet()->getCell('E1')->setValue("NOMBRES PACIENTE");
	$objPHPExcel->getActiveSheet()->getStyle('E1')->applyFromArray($styleArray);

	$objPHPExcel->getActiveSheet()->getCell('F1')->setValue("INGRESO SALA OBSERVACIÓN");
	$objPHPExcel->getActiveSheet()->getStyle('F1')->applyFromArray($styleArray);

	$objPHPExcel->getActiveSheet()->getCell('G1')->setValue("SALIDA SALA DE OBSERVACIÓN");
	$objPHPExcel->getActiveSheet()->getStyle('G1')->applyFromArray($styleArray);

	$objPHPExcel->getActiveSheet()->getCell('H1')->setValue("DIFERENCIA DE HORAS");
	$objPHPExcel->getActiveSheet()->getStyle('H1')->applyFromArray($styleArray);

	$objPHPExcel->getActiveSheet()->getCell('I1')->setValue("TIPO DE SALA");
	$objPHPExcel->getActiveSheet()->getStyle('I1')->applyFromArray($styleArray);

	$objPHPExcel->getActiveSheet()->getCell('J1')->setValue("PREVISION");
	$objPHPExcel->getActiveSheet()->getStyle('J1')->applyFromArray($styleArray);

	for($i=0; $i<count($datos); $i++){
		$j = $i + 2;

		$transexual_bd   	  = $datos[$i]["transexual"];
		$nombreSocial_bd 	  = $datos[$i]["nombreSocial"];
		$nombrePaciente	      = $datos[$i]['NOMBRE_PACIENTE'];
		$infoNombre    		  = $objUtil->infoNombreDocExcel($transexual_bd,$nombreSocial_bd,$nombrePaciente);


		$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $datos[$i]['dau_id']);
		$objPHPExcel->getActiveSheet()->setCellValue('B' . $j, date("d-m-Y H:i:s", strtotime($datos[$i]['dau_admision_fecha'])));
		$objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $datos[$i]['Tipo_documento']);
		$objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $datos[$i]['rut']);
		$objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $infoNombre);
		$objPHPExcel->getActiveSheet()->setCellValue('F' . $j, date("d-m-Y H:i:s", strtotime($datos[$i]['dau_mov_cama_fecha_ingreso'])));
		if($datos[$i]['dau_mov_cama_fecha_egreso'] == null || $datos[$i]['dau_mov_cama_fecha_egreso'] ==""){
			$objPHPExcel->getActiveSheet()->setCellValue('G' . $j, '');
		}else{
			$objPHPExcel->getActiveSheet()->setCellValue('G' . $j, date("d-m-Y H:i:s", strtotime($datos[$i]['dau_mov_cama_fecha_egreso'])));		
		}
		$objPHPExcel->getActiveSheet()->setCellValue('H' . $j, $datos[$i]['DIFdeHras']);
		$objPHPExcel->getActiveSheet()->setCellValue('I' . $j, $datos[$i]['TIPOSALA']);
		$objPHPExcel->getActiveSheet()->setCellValue('J' . $j, $datos[$i]['prevision']);
	}

	// $objPHPExcel->getActiveSheet()->setTitle('HIDRITACION U OBSERVACIÓN');
	// $objPHPExcel->setActiveSheetIndex(0);
	// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	// $objWriter->save('php://output');

	$objPHPExcel->getActiveSheet()->setTitle('EXTENDIDA');
	$objPHPExcel->setActiveSheetIndex(0);
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	
?>