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
// require_once('../../../../../estandar/PHPExcel/Classes/PHPExcel.php');  	$objPHPExcel    = new PHPExcel();
    // require_once('../../../../../estandar/PHPExcel8/Classes/PHPExcel.php');   $objPHPExcel      = new PHPExcel();





$parametros               = $objUtil->getFormulario($_GET);

$parametros['frm_inicio'] = $objUtil->fechaInvertida($parametros['fechaInicio']);

$parametros['frm_fin']    = $objUtil->fechaInvertida($parametros['fechaFin']);

// $objCon = $objUtil->cambiarServidorReporte($parametros['frm_inicio'], $parametros['frm_fin']);

$fechaHoy                 = $objUtil->getFechaPalabra(date('Y-m-d'));

$datos                    = $objReportes->registroHospitalizacionPanelViral($objCon,$parametros);



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
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(60);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(50);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$objPHPExcel->getDefaultStyle()->getAlignment()->setWrapText(true);

$styleArray = array(
	'font'  => array(
	'bold'  => true,
	'color' => array('rgb' => 'FFFFFF')
));



/*
**************************************************************************
					            ENCABEZADOS
**************************************************************************
*/
$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT ));

//DAU
$objPHPExcel->getActiveSheet()->getCell('A2')->setValue("DAU");
$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);

//CAT
$objPHPExcel->getActiveSheet()->getCell('B2')->setValue("CAT");
$objPHPExcel->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);

//NOMBRE
$objPHPExcel->getActiveSheet()->getCell('C2')->setValue("NOMBRE");
$objPHPExcel->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);

//EDAD
$objPHPExcel->getActiveSheet()->getCell('D2')->setValue("EDAD");
$objPHPExcel->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray);

//SERVICIO
$objPHPExcel->getActiveSheet()->getCell('E2')->setValue("SERVICIO");
$objPHPExcel->getActiveSheet()->getStyle('E2')->applyFromArray($styleArray);

//CTA CTE
$objPHPExcel->getActiveSheet()->getCell('F2')->setValue("CTA CTE");
$objPHPExcel->getActiveSheet()->getStyle('F2')->applyFromArray($styleArray);

//FECHA/HORA
$objPHPExcel->getActiveSheet()->getCell('G2')->setValue("FECHA/HORA");
$objPHPExcel->getActiveSheet()->getStyle('G2')->applyFromArray($styleArray)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DMYMINUS);

//CIE 10
$objPHPExcel->getActiveSheet()->getCell('H2')->setValue("CIE 10");
$objPHPExcel->getActiveSheet()->getStyle('H2')->applyFromArray($styleArray);

//PANEL VIRAL
$objPHPExcel->getActiveSheet()->getCell('I2')->setValue("PANEL VIRAL");
$objPHPExcel->getActiveSheet()->getStyle('I2')->applyFromArray($styleArray);



for ( $i = 0; $i < count($datos); $i++ ) {

	$j = $i + 3;

	$panelViral = ($objUtil->existe($datos[$i]['panelViral'])) ? $datos[$i]["panelViral"] : "No";

	$objPHPExcel->getActiveSheet()->getStyle('A:I')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT ));

	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $datos[$i]['dau_id']);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $datos[$i]['dau_categorizacion']);

	$objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $datos[$i]['nombres'].' '.$datos[$i]['apellidopat']. ' '.$datos[$i]['apellidomat']);

	$objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $datos[$i]['dau_paciente_edad']);

	$objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $datos[$i]['servicio']);

	$objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $datos[$i]['idctacte']);

	$objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $datos[$i]['fechaHora']);

	$objPHPExcel->getActiveSheet()->setCellValue('H' . $j, $datos[$i]['cie10']);

	$objPHPExcel->getActiveSheet()->setCellValue('I' . $j, $panelViral);


}



/*
**************************************************************************
							Salida excel
**************************************************************************
*/
// $objPHPExcel->getActiveSheet()->setTitle('Excel Registro Hospitalización');

// $objPHPExcel->setActiveSheetIndex(0);

// header('Content-Type: application/vnd.ms-excel');

// header('Content-Disposition: attachment;filename="excel_registro_hospitalizacion"'.$parametros["fechaInicio"].'"_"'.$parametros["fechaFin"].'".xls"');

// header('Cache-Control: max-age=0');

// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

// $objWriter->save('php://output');

$objPHPExcel->getActiveSheet()->setTitle('EXTENDIDA');
$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

?>