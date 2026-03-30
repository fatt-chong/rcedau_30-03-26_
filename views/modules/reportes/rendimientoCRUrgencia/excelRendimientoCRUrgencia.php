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

$requestBody 								= file_get_contents("php://input");
$data 										= json_decode($requestBody, true);
$fechaInicio     			       			= $data['fechaInicio'];
$fechaTermino    			       			= $data['fechaTermino'];
$nombreMedico                      			= $data['nombreMedico'];
$tablaResumenRendimientoCRUrgencia 			= json_decode($data['tablaResumenRendimientoCRUrgencia'], true);


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
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(30);



$styleArray = array(
	'font'  => array(
	'bold'  => true,
	'color' => array('rgb' => 'FFFFFF')
));



/*
**************************************************************************
					RESUMEN RENDIMIENTO URGENCIA
**************************************************************************
*/
$objPHPExcel->getActiveSheet()->getStyle('A2:S2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A3:S3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$objPHPExcel->getActiveSheet()->getStyle('A2:S2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A3:S3')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));

$objPHPExcel->getActiveSheet()->getCell('A2')->setValue("");
$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A2:C2');

$objPHPExcel->getActiveSheet()->getCell('D2')->setValue("ESI-1");
$objPHPExcel->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('D2:E2');

$objPHPExcel->getActiveSheet()->getCell('F2')->setValue("ESI-2");
$objPHPExcel->getActiveSheet()->getStyle('F2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('F2:G2');

$objPHPExcel->getActiveSheet()->getCell('H2')->setValue("ESI-3");
$objPHPExcel->getActiveSheet()->getStyle('H2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('H2:I2');

$objPHPExcel->getActiveSheet()->getCell('J2')->setValue("ESI-4");
$objPHPExcel->getActiveSheet()->getStyle('J2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('J2:M2');

$objPHPExcel->getActiveSheet()->getCell('N2')->setValue("ESI-5");
$objPHPExcel->getActiveSheet()->getStyle('N2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('N2:Q2');

$objPHPExcel->getActiveSheet()->getCell('R2')->setValue("Solicitud Especialistas");
$objPHPExcel->getActiveSheet()->getStyle('R2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('R2:S2');

$objPHPExcel->getActiveSheet()->getCell('A3')->setValue("Fechas");
$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B3')->setValue("Cant. Atendidos");
$objPHPExcel->getActiveSheet()->getStyle('B3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('C3')->setValue("Cant. Egresados");
$objPHPExcel->getActiveSheet()->getStyle('C3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('D3')->setValue("A");
$objPHPExcel->getActiveSheet()->getStyle('D3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('E3')->setValue("E");
$objPHPExcel->getActiveSheet()->getStyle('E3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('F3')->setValue("A");
$objPHPExcel->getActiveSheet()->getStyle('F3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('G3')->setValue("E");
$objPHPExcel->getActiveSheet()->getStyle('G3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('H3')->setValue("A");
$objPHPExcel->getActiveSheet()->getStyle('H3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('I3')->setValue("E");
$objPHPExcel->getActiveSheet()->getStyle('I3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('J3')->setValue("A");
$objPHPExcel->getActiveSheet()->getStyle('J3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('K3')->setValue("IV");
$objPHPExcel->getActiveSheet()->getStyle('K3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('L3')->setValue("%");
$objPHPExcel->getActiveSheet()->getStyle('L3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('M3')->setValue("E");
$objPHPExcel->getActiveSheet()->getStyle('M3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('N3')->setValue("A");
$objPHPExcel->getActiveSheet()->getStyle('N3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('O3')->setValue("IV");
$objPHPExcel->getActiveSheet()->getStyle('O3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('P3')->setValue("%");
$objPHPExcel->getActiveSheet()->getStyle('P3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('Q3')->setValue("E");
$objPHPExcel->getActiveSheet()->getStyle('Q3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('R3')->setValue("Pedidas");
$objPHPExcel->getActiveSheet()->getStyle('R3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('S3')->setValue("Realizadas");
$objPHPExcel->getActiveSheet()->getStyle('S3')->applyFromArray($styleArray);


for ( $i = 0; $i < count($tablaResumenRendimientoCRUrgencia); $i++ ) {
	$j = $i + 4;
	$objPHPExcel->getActiveSheet()->getStyle('A:S')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $tablaResumenRendimientoCRUrgencia[$i]['fechas']);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $tablaResumenRendimientoCRUrgencia[$i]['cantidadAtendidos']);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $tablaResumenRendimientoCRUrgencia[$i]['cantidadEgresados']);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $tablaResumenRendimientoCRUrgencia[$i]['atendidosESI1']);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $tablaResumenRendimientoCRUrgencia[$i]['egresadosESI1']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $tablaResumenRendimientoCRUrgencia[$i]['atendidosESI2']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $tablaResumenRendimientoCRUrgencia[$i]['egresadosESI2']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $j, $tablaResumenRendimientoCRUrgencia[$i]['atendidosESI3']);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $j, $tablaResumenRendimientoCRUrgencia[$i]['egresadosESI3']);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $j, $tablaResumenRendimientoCRUrgencia[$i]['atendidosESI4']);
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $j, $tablaResumenRendimientoCRUrgencia[$i]['intravenososESI4']);
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $j, $tablaResumenRendimientoCRUrgencia[$i]['porcentajeIntravenososESI4']);
    $objPHPExcel->getActiveSheet()->setCellValue('M' . $j, $tablaResumenRendimientoCRUrgencia[$i]['egresadosESI5']);
    $objPHPExcel->getActiveSheet()->setCellValue('N' . $j, $tablaResumenRendimientoCRUrgencia[$i]['atendidosESI5']);
    $objPHPExcel->getActiveSheet()->setCellValue('O' . $j, $tablaResumenRendimientoCRUrgencia[$i]['intravenososESI5']);
    $objPHPExcel->getActiveSheet()->setCellValue('P' . $j, $tablaResumenRendimientoCRUrgencia[$i]['porcentajeIntravenososESI5']);
    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $j, $tablaResumenRendimientoCRUrgencia[$i]['egresadosESI5']);
    $objPHPExcel->getActiveSheet()->setCellValue('R' . $j, $tablaResumenRendimientoCRUrgencia[$i]['solicitudEspecialistasPedidas']);
	$objPHPExcel->getActiveSheet()->setCellValue('S' . $j, $tablaResumenRendimientoCRUrgencia[$i]['solicitudEspecialistasRealizadas']);
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