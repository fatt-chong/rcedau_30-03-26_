<?php
ini_set('memory_limit', '5G');
error_reporting(0);
require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php');       				$objCon    		= new Connection(); $objCon->db_connect();
require_once('../../../../class/Util.class.php');       					$objUtil    	= new Util;
require_once('../../../../class/Reportes.class.php');       				$objReportes    = new Reportes;
require_once('../../../../../estandar/PHPExcel8/Classes/PHPExcel.php');   $objPHPExcel      = new PHPExcel();
/*
################################################################################################################################################
                                                                Declaración de Variables
*/
$requestBody = file_get_contents("php://input");
$data = json_decode($requestBody, true);
// Acceso a los parámetros
$fechaInicio = $data['fechaInicio'];
$fechaTermino = $data['fechaTermino'];
$informacionReporte = json_decode($data['informacionReporte'], true);


$objPHPExcel = new PHPExcel();

$objPHPExcel 				= new PHPExcel();
$objPHPExcel->getProperties()->setCreator("Plataforma Web HJNC")
						 ->setLastModifiedBy("Plataforma Web HJNC")
						 ->setTitle("Reporte Enfermedades Epidemiológicas")
						 ->setSubject("Office 2007 XLSX Document")
						 ->setDescription("Excel Document")
						 ->setKeywords("HJNC")
						 ->setCategory("Lavanderia");	
$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(30);
$objPHPExcel->getDefaultStyle()->getAlignment()->setWrapText(true);

$styleArray = array(
	'font'  => array(
	'bold'  => true,
	'color' => array('rgb' => 'FFFFFF')
));

$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));

$objPHPExcel->getActiveSheet()->getCell('A2')->setValue("Reporte Enfermedades Epidemiológicas");
$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A2:I2');

$objPHPExcel->getActiveSheet()->getCell('A3')->setValue("DAU");
$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B3')->setValue("Nombre");
$objPHPExcel->getActiveSheet()->getStyle('B3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('C3')->setValue("RUN");
$objPHPExcel->getActiveSheet()->getStyle('C3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('D3')->setValue("Fecha Admisión");
$objPHPExcel->getActiveSheet()->getStyle('D3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('E3')->setValue("Fecha Cierre");
$objPHPExcel->getActiveSheet()->getStyle('E3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('F3')->setValue("Indicación Egreso");
$objPHPExcel->getActiveSheet()->getStyle('F3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('G3')->setValue("Destino");
$objPHPExcel->getActiveSheet()->getStyle('G3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('H3')->setValue("CIE10");
$objPHPExcel->getActiveSheet()->getStyle('H3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('I3')->setValue("Hipótesis Final");
$objPHPExcel->getActiveSheet()->getStyle('I3')->applyFromArray($styleArray);

for ( $i = 0; $i < count($informacionReporte); $i++ ) {
	$j = $i + 4;

	$transexual_bd   	  = $informacionReporte[$i]["transexual"];
	$nombreSocial_bd 	  = $informacionReporte[$i]["nombreSocial"];
	$nombrePaciente	      = $informacionReporte[$i]['nombre'];
	$infoNombre    		  = $objUtil->infoNombreDocExcel($transexual_bd,$nombreSocial_bd,$nombrePaciente);

	$objPHPExcel->getActiveSheet()->getStyle('A:I')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT ));
	$objPHPExcel->getActiveSheet()->getStyle('A:I')->getAlignment()->applyFromArray(array('vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP ));
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $informacionReporte[$i]['idDau']);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $infoNombre);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $informacionReporte[$i]['run']);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $informacionReporte[$i]['fechaAdmision']);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $informacionReporte[$i]['fechaCierre']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $informacionReporte[$i]['indicacionEgreso']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $informacionReporte[$i]['destino']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $j, $informacionReporte[$i]['CIE10']);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $j, $informacionReporte[$i]['hipotesisFinal']);
}
/*
**************************************************************************
							Salida excel
**************************************************************************
*/
$objPHPExcel->getActiveSheet()->setTitle('EXTENDIDA');
$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
?>