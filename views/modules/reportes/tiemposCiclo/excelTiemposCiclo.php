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
$tablaResumenTiemposCicloCRUrgencia 			= json_decode($data['tablaResumenTiemposCicloCRUrgencia'], true);


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

$styleArray = array(
	'font'  => array(
	'bold'  => true,
	'color' => array('rgb' => 'FFFFFF')
));



/*
**************************************************************************
					RESUMEN TIEMPOS DE CICLO ADULTOS
**************************************************************************
*/
$objPHPExcel->getActiveSheet()->getStyle('A2:M2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A3:M3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A4:M4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A5:M5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$objPHPExcel->getActiveSheet()->getStyle('A2:M2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A3:M3')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A4:M4')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A5:M5')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));

$objPHPExcel->getActiveSheet()->getCell('A2')->setValue("Resumen Tiempos de Ciclo Adultos");
$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A2:M2');

$objPHPExcel->getActiveSheet()->getCell('A3')->setValue("Tiempo desde Admisión a Cierre Dau Definitivo");
$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A3:M3');

$objPHPExcel->getActiveSheet()->getCell('A4')->setValue("Tipo Categorización");
$objPHPExcel->getActiveSheet()->getStyle('A4')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B4')->setValue("Todos los Adultos");
$objPHPExcel->getActiveSheet()->getStyle('B4')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('B4:E4');

$objPHPExcel->getActiveSheet()->getCell('F4')->setValue("Adultos Hospitalizados");
$objPHPExcel->getActiveSheet()->getStyle('F4')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('F4:I4');

$objPHPExcel->getActiveSheet()->getCell('J4')->setValue("Adultos de Alta");
$objPHPExcel->getActiveSheet()->getStyle('J4')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('J4:M4');

$objPHPExcel->getActiveSheet()->getCell('A5')->setValue("");
$objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B5')->setValue("Número Atenciones");
$objPHPExcel->getActiveSheet()->getStyle('B5')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('C5')->setValue("Tiempo Promedio");
$objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('D5')->setValue("Tiempo Mínimo");
$objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('E5')->setValue("Tiempo Máximo");
$objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('F5')->setValue("Número Hospitalizados");
$objPHPExcel->getActiveSheet()->getStyle('F5')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('G5')->setValue("Tiempo Promedio");
$objPHPExcel->getActiveSheet()->getStyle('G5')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('H5')->setValue("Tiempo Mínimo");
$objPHPExcel->getActiveSheet()->getStyle('H5')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('I5')->setValue("Tiempo Máximo");
$objPHPExcel->getActiveSheet()->getStyle('I5')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('J5')->setValue("Número Alta");
$objPHPExcel->getActiveSheet()->getStyle('J5')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('K5')->setValue("Tiempo Promedio");
$objPHPExcel->getActiveSheet()->getStyle('K5')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('L5')->setValue("Tiempo Mínimo");
$objPHPExcel->getActiveSheet()->getStyle('L5')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('M5')->setValue("Tiempo Máximo");
$objPHPExcel->getActiveSheet()->getStyle('M5')->applyFromArray($styleArray);


for ( $i = 0; $i < count($tablaResumenTiemposCicloCRUrgencia[0]); $i++ ) {
	$j = $i + 6;
	$objPHPExcel->getActiveSheet()->getStyle('A:M')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $tablaResumenTiemposCicloCRUrgencia[0][$i]['categorizacion']);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $tablaResumenTiemposCicloCRUrgencia[0][$i]['atenciones']);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $tablaResumenTiemposCicloCRUrgencia[0][$i]['tiempoPromedio']);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $tablaResumenTiemposCicloCRUrgencia[0][$i]['tiempoMinimo']);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $tablaResumenTiemposCicloCRUrgencia[0][$i]['tiempoMaximo']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $tablaResumenTiemposCicloCRUrgencia[0][$i]['hospitalizados']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $tablaResumenTiemposCicloCRUrgencia[0][$i]['hospitalizadosTiempoPromedio']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $j, $tablaResumenTiemposCicloCRUrgencia[0][$i]['hospitalizadosTiempoMinimo']);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $j, $tablaResumenTiemposCicloCRUrgencia[0][$i]['hospitalizadosTiempoMaximo']);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $j, $tablaResumenTiemposCicloCRUrgencia[0][$i]['alta']);
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $j, $tablaResumenTiemposCicloCRUrgencia[0][$i]['altaTiempoPromedio']);
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $j, $tablaResumenTiemposCicloCRUrgencia[0][$i]['altaTiempoMinimo']);
    $objPHPExcel->getActiveSheet()->setCellValue('M' . $j, $tablaResumenTiemposCicloCRUrgencia[0][$i]['altaTiempoMaximo']);
}



/*
**************************************************************************
					RESUMEN TIEMPOS DE CICLO PEDIÁTRICOS
**************************************************************************
*/
$objPHPExcel->getActiveSheet()->getStyle('A15:M15')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A16:M16')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A17:M17')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A18:M18')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$objPHPExcel->getActiveSheet()->getStyle('A15:M15')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A16:M16')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A17:M17')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A18:M18')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));

$objPHPExcel->getActiveSheet()->getCell('A15')->setValue("Resumen Tiempos de Ciclo Pediátricos");
$objPHPExcel->getActiveSheet()->getStyle('A15')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A15:M15');

$objPHPExcel->getActiveSheet()->getCell('A16')->setValue("Tiempo desde Admisión a Cierre Dau Definitivo");
$objPHPExcel->getActiveSheet()->getStyle('A16')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A16:M16');

$objPHPExcel->getActiveSheet()->getCell('A17')->setValue("Tipo Categorización");
$objPHPExcel->getActiveSheet()->getStyle('A17')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B17')->setValue("Todos los Pediátricos");
$objPHPExcel->getActiveSheet()->getStyle('B17')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('B17:E17');

$objPHPExcel->getActiveSheet()->getCell('F17')->setValue("Pediátricos Hospitalizados");
$objPHPExcel->getActiveSheet()->getStyle('F17')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('F17:I17');

$objPHPExcel->getActiveSheet()->getCell('J17')->setValue("Pediátricos de Alta");
$objPHPExcel->getActiveSheet()->getStyle('J17')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('J17:M17');

$objPHPExcel->getActiveSheet()->getCell('A18')->setValue("");
$objPHPExcel->getActiveSheet()->getStyle('A18')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B18')->setValue("Número Atenciones");
$objPHPExcel->getActiveSheet()->getStyle('B18')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('C18')->setValue("Tiempo Promedio");
$objPHPExcel->getActiveSheet()->getStyle('C18')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('D18')->setValue("Tiempo Mínimo");
$objPHPExcel->getActiveSheet()->getStyle('D18')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('E18')->setValue("Tiempo Máximo");
$objPHPExcel->getActiveSheet()->getStyle('E18')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('F18')->setValue("Número Hospitalizados");
$objPHPExcel->getActiveSheet()->getStyle('F18')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('G18')->setValue("Tiempo Promedio");
$objPHPExcel->getActiveSheet()->getStyle('G18')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('H18')->setValue("Tiempo Mínimo");
$objPHPExcel->getActiveSheet()->getStyle('H18')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('I18')->setValue("Tiempo Máximo");
$objPHPExcel->getActiveSheet()->getStyle('I18')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('J18')->setValue("Número Alta");
$objPHPExcel->getActiveSheet()->getStyle('J18')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('K18')->setValue("Tiempo Promedio");
$objPHPExcel->getActiveSheet()->getStyle('K18')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('L18')->setValue("Tiempo Mínimo");
$objPHPExcel->getActiveSheet()->getStyle('L18')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('M18')->setValue("Tiempo Máximo");
$objPHPExcel->getActiveSheet()->getStyle('M18')->applyFromArray($styleArray);


for ( $i = 0; $i < count($tablaResumenTiemposCicloCRUrgencia[1]); $i++ ) {
	$j = $i + 19;
	$objPHPExcel->getActiveSheet()->getStyle('A:M')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $tablaResumenTiemposCicloCRUrgencia[1][$i]['categorizacion']);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $tablaResumenTiemposCicloCRUrgencia[1][$i]['atenciones']);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $tablaResumenTiemposCicloCRUrgencia[1][$i]['tiempoPromedio']);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $tablaResumenTiemposCicloCRUrgencia[1][$i]['tiempoMinimo']);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $tablaResumenTiemposCicloCRUrgencia[1][$i]['tiempoMaximo']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $tablaResumenTiemposCicloCRUrgencia[1][$i]['hospitalizados']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $tablaResumenTiemposCicloCRUrgencia[1][$i]['hospitalizadosTiempoPromedio']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $j, $tablaResumenTiemposCicloCRUrgencia[1][$i]['hospitalizadosTiempoMinimo']);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $j, $tablaResumenTiemposCicloCRUrgencia[1][$i]['hospitalizadosTiempoMaximo']);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $j, $tablaResumenTiemposCicloCRUrgencia[1][$i]['alta']);
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $j, $tablaResumenTiemposCicloCRUrgencia[1][$i]['altaTiempoPromedio']);
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $j, $tablaResumenTiemposCicloCRUrgencia[1][$i]['altaTiempoMinimo']);
    $objPHPExcel->getActiveSheet()->setCellValue('M' . $j, $tablaResumenTiemposCicloCRUrgencia[1][$i]['altaTiempoMaximo']);
}



/*
**************************************************************************
			RESUMEN TIEMPOS DE CICLO ADULTOS HOSPITALIZADOS
**************************************************************************
*/
$objPHPExcel->getActiveSheet()->getStyle('A28:L28')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A29:L29')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A30:L30')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$objPHPExcel->getActiveSheet()->getStyle('A28:L28')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A29:L29')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A30:L30')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));

$objPHPExcel->getActiveSheet()->getCell('A28')->setValue("Tiempos de Ciclo Adultos Hospitalizados");
$objPHPExcel->getActiveSheet()->getStyle('A28')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A28:L28');

$objPHPExcel->getActiveSheet()->getCell('A29')->setValue("Tiempo desde Admisión a Cierre DAU definicito en Adulto - Promedio por Deciles");
$objPHPExcel->getActiveSheet()->getStyle('A29')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A29:L29');

$objPHPExcel->getActiveSheet()->getCell('A30')->setValue("Tipo Categorización");
$objPHPExcel->getActiveSheet()->getStyle('A30')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B30')->setValue("Total");
$objPHPExcel->getActiveSheet()->getStyle('B30')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('C30')->setValue("D1");
$objPHPExcel->getActiveSheet()->getStyle('C30')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('D30')->setValue("D2");
$objPHPExcel->getActiveSheet()->getStyle('D30')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('E30')->setValue("D3");
$objPHPExcel->getActiveSheet()->getStyle('E30')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('F30')->setValue("D4");
$objPHPExcel->getActiveSheet()->getStyle('F30')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('G30')->setValue("D5");
$objPHPExcel->getActiveSheet()->getStyle('G30')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('H30')->setValue("D6");
$objPHPExcel->getActiveSheet()->getStyle('H30')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('I30')->setValue("D7");
$objPHPExcel->getActiveSheet()->getStyle('I30')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('J30')->setValue("D8");
$objPHPExcel->getActiveSheet()->getStyle('J30')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('K30')->setValue("D9");
$objPHPExcel->getActiveSheet()->getStyle('K30')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('L30')->setValue("D10");
$objPHPExcel->getActiveSheet()->getStyle('L30')->applyFromArray($styleArray);



for ( $i = 0; $i < count($tablaResumenTiemposCicloCRUrgencia[2]); $i++ ) {
	$j = $i + 31;
	$objPHPExcel->getActiveSheet()->getStyle('A:L')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $tablaResumenTiemposCicloCRUrgencia[2][$i]['categorizacion']);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $tablaResumenTiemposCicloCRUrgencia[2][$i]['total']);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $tablaResumenTiemposCicloCRUrgencia[2][$i]['d1']);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $tablaResumenTiemposCicloCRUrgencia[2][$i]['d2']);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $tablaResumenTiemposCicloCRUrgencia[2][$i]['d3']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $tablaResumenTiemposCicloCRUrgencia[2][$i]['d4']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $tablaResumenTiemposCicloCRUrgencia[2][$i]['d5']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $j, $tablaResumenTiemposCicloCRUrgencia[2][$i]['d6']);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $j, $tablaResumenTiemposCicloCRUrgencia[2][$i]['d7']);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $j, $tablaResumenTiemposCicloCRUrgencia[2][$i]['d8']);
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $j, $tablaResumenTiemposCicloCRUrgencia[2][$i]['d9']);
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $j, $tablaResumenTiemposCicloCRUrgencia[2][$i]['d10']);
}



/*
**************************************************************************
				RESUMEN TIEMPOS DE CICLO ADULTOS URGENCIA
**************************************************************************
*/
$objPHPExcel->getActiveSheet()->getStyle('A40:L40')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A41:L41')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A42:L42')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$objPHPExcel->getActiveSheet()->getStyle('A40:L40')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A41:L41')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A42:L42')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));

$objPHPExcel->getActiveSheet()->getCell('A40')->setValue("Tiempo Procesos Urgencia Adultos");
$objPHPExcel->getActiveSheet()->getStyle('A40')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A40:L40');

$objPHPExcel->getActiveSheet()->getCell('A41')->setValue("Tiempo desde Admisión a Indicación de Egreso en Adultos Hospitalizados - Promedio por Deciles");
$objPHPExcel->getActiveSheet()->getStyle('A41')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A41:L41');

$objPHPExcel->getActiveSheet()->getCell('A42')->setValue("Tipo Categorización");
$objPHPExcel->getActiveSheet()->getStyle('A42')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B42')->setValue("Total");
$objPHPExcel->getActiveSheet()->getStyle('B42')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('C42')->setValue("D1");
$objPHPExcel->getActiveSheet()->getStyle('C42')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('D42')->setValue("D2");
$objPHPExcel->getActiveSheet()->getStyle('D42')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('E42')->setValue("D3");
$objPHPExcel->getActiveSheet()->getStyle('E42')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('F42')->setValue("D4");
$objPHPExcel->getActiveSheet()->getStyle('F42')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('G42')->setValue("D5");
$objPHPExcel->getActiveSheet()->getStyle('G42')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('H42')->setValue("D6");
$objPHPExcel->getActiveSheet()->getStyle('H42')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('I42')->setValue("D7");
$objPHPExcel->getActiveSheet()->getStyle('I42')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('J42')->setValue("D8");
$objPHPExcel->getActiveSheet()->getStyle('J42')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('K42')->setValue("D9");
$objPHPExcel->getActiveSheet()->getStyle('K42')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('L42')->setValue("D10");
$objPHPExcel->getActiveSheet()->getStyle('L42')->applyFromArray($styleArray);



for ( $i = 0; $i < count($tablaResumenTiemposCicloCRUrgencia[3]); $i++ ) {
	$j = $i + 43;
	$objPHPExcel->getActiveSheet()->getStyle('A:L')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $tablaResumenTiemposCicloCRUrgencia[3][$i]['categorizacion']);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $tablaResumenTiemposCicloCRUrgencia[3][$i]['total']);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $tablaResumenTiemposCicloCRUrgencia[3][$i]['d1']);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $tablaResumenTiemposCicloCRUrgencia[3][$i]['d2']);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $tablaResumenTiemposCicloCRUrgencia[3][$i]['d3']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $tablaResumenTiemposCicloCRUrgencia[3][$i]['d4']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $tablaResumenTiemposCicloCRUrgencia[3][$i]['d5']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $j, $tablaResumenTiemposCicloCRUrgencia[3][$i]['d6']);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $j, $tablaResumenTiemposCicloCRUrgencia[3][$i]['d7']);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $j, $tablaResumenTiemposCicloCRUrgencia[3][$i]['d8']);
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $j, $tablaResumenTiemposCicloCRUrgencia[3][$i]['d9']);
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $j, $tablaResumenTiemposCicloCRUrgencia[3][$i]['d10']);
}



/*
**************************************************************************
			RESUMEN TIEMPOS DE CICLO PEDIÁTRICOS HOSPITALIZADOS
**************************************************************************
*/
$objPHPExcel->getActiveSheet()->getStyle('A52:L52')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A53:L53')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A54:L54')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$objPHPExcel->getActiveSheet()->getStyle('A52:L52')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A53:L53')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A54:L54')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));

$objPHPExcel->getActiveSheet()->getCell('A52')->setValue("Tiempos De Ciclo Pediátrico Hospitalizados");
$objPHPExcel->getActiveSheet()->getStyle('A52')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A52:L52');

$objPHPExcel->getActiveSheet()->getCell('A53')->setValue("Tiempo desde Admisión a Cierre Dau Definitivo en Pediátricos - Promedio por Deciles");
$objPHPExcel->getActiveSheet()->getStyle('A53')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A53:L53');

$objPHPExcel->getActiveSheet()->getCell('A54')->setValue("Tipo Categorización");
$objPHPExcel->getActiveSheet()->getStyle('A54')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B54')->setValue("Total");
$objPHPExcel->getActiveSheet()->getStyle('B54')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('C54')->setValue("D1");
$objPHPExcel->getActiveSheet()->getStyle('C54')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('D54')->setValue("D2");
$objPHPExcel->getActiveSheet()->getStyle('D54')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('E54')->setValue("D3");
$objPHPExcel->getActiveSheet()->getStyle('E54')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('F54')->setValue("D4");
$objPHPExcel->getActiveSheet()->getStyle('F54')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('G54')->setValue("D5");
$objPHPExcel->getActiveSheet()->getStyle('G54')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('H54')->setValue("D6");
$objPHPExcel->getActiveSheet()->getStyle('H54')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('I54')->setValue("D7");
$objPHPExcel->getActiveSheet()->getStyle('I54')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('J54')->setValue("D8");
$objPHPExcel->getActiveSheet()->getStyle('J54')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('K54')->setValue("D9");
$objPHPExcel->getActiveSheet()->getStyle('K54')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('L54')->setValue("D10");
$objPHPExcel->getActiveSheet()->getStyle('L54')->applyFromArray($styleArray);



for ( $i = 0; $i < count($tablaResumenTiemposCicloCRUrgencia[4]); $i++ ) {
	$j = $i + 55;
	$objPHPExcel->getActiveSheet()->getStyle('A:L')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $tablaResumenTiemposCicloCRUrgencia[4][$i]['categorizacion']);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $tablaResumenTiemposCicloCRUrgencia[4][$i]['total']);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $tablaResumenTiemposCicloCRUrgencia[4][$i]['d1']);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $tablaResumenTiemposCicloCRUrgencia[4][$i]['d2']);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $tablaResumenTiemposCicloCRUrgencia[4][$i]['d3']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $tablaResumenTiemposCicloCRUrgencia[4][$i]['d4']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $tablaResumenTiemposCicloCRUrgencia[4][$i]['d5']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $j, $tablaResumenTiemposCicloCRUrgencia[4][$i]['d6']);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $j, $tablaResumenTiemposCicloCRUrgencia[4][$i]['d7']);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $j, $tablaResumenTiemposCicloCRUrgencia[4][$i]['d8']);
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $j, $tablaResumenTiemposCicloCRUrgencia[4][$i]['d9']);
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $j, $tablaResumenTiemposCicloCRUrgencia[4][$i]['d10']);
}



/*
**************************************************************************
			RESUMEN TIEMPOS DE CICLO PEDIÁTRICOS URGENCIA
**************************************************************************
*/
$objPHPExcel->getActiveSheet()->getStyle('A60:L60')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A61:L61')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A62:L62')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$objPHPExcel->getActiveSheet()->getStyle('A60:L60')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A61:L61')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A62:L62')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));

$objPHPExcel->getActiveSheet()->getCell('A60')->setValue("Tiempo Procesos Urgencia Pediátrico");
$objPHPExcel->getActiveSheet()->getStyle('A60')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A60:L60');

$objPHPExcel->getActiveSheet()->getCell('A61')->setValue("Tiempo desde Admisión a Indicación de Egreso en Pediátricos Hospitalizados - Promedio por Deciles");
$objPHPExcel->getActiveSheet()->getStyle('A61')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A61:L61');

$objPHPExcel->getActiveSheet()->getCell('A62')->setValue("Tipo Categorización");
$objPHPExcel->getActiveSheet()->getStyle('A62')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B62')->setValue("Total");
$objPHPExcel->getActiveSheet()->getStyle('B62')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('C62')->setValue("D1");
$objPHPExcel->getActiveSheet()->getStyle('C62')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('D62')->setValue("D2");
$objPHPExcel->getActiveSheet()->getStyle('D62')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('E62')->setValue("D3");
$objPHPExcel->getActiveSheet()->getStyle('E62')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('F62')->setValue("D4");
$objPHPExcel->getActiveSheet()->getStyle('F62')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('G62')->setValue("D5");
$objPHPExcel->getActiveSheet()->getStyle('G62')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('H62')->setValue("D6");
$objPHPExcel->getActiveSheet()->getStyle('H62')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('I62')->setValue("D7");
$objPHPExcel->getActiveSheet()->getStyle('I62')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('J62')->setValue("D8");
$objPHPExcel->getActiveSheet()->getStyle('J62')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('K62')->setValue("D9");
$objPHPExcel->getActiveSheet()->getStyle('K62')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('L62')->setValue("D10");
$objPHPExcel->getActiveSheet()->getStyle('L62')->applyFromArray($styleArray);



for ( $i = 0; $i < count($tablaResumenTiemposCicloCRUrgencia[5]); $i++ ) {
	$j = $i + 63;
	$objPHPExcel->getActiveSheet()->getStyle('A:L')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $tablaResumenTiemposCicloCRUrgencia[5][$i]['categorizacion']);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $tablaResumenTiemposCicloCRUrgencia[5][$i]['total']);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $tablaResumenTiemposCicloCRUrgencia[5][$i]['d1']);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $tablaResumenTiemposCicloCRUrgencia[5][$i]['d2']);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $tablaResumenTiemposCicloCRUrgencia[5][$i]['d3']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $tablaResumenTiemposCicloCRUrgencia[5][$i]['d4']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $tablaResumenTiemposCicloCRUrgencia[5][$i]['d5']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $j, $tablaResumenTiemposCicloCRUrgencia[5][$i]['d6']);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $j, $tablaResumenTiemposCicloCRUrgencia[5][$i]['d7']);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $j, $tablaResumenTiemposCicloCRUrgencia[5][$i]['d8']);
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $j, $tablaResumenTiemposCicloCRUrgencia[5][$i]['d9']);
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $j, $tablaResumenTiemposCicloCRUrgencia[5][$i]['d10']);
}



/*
**************************************************************************
			RESUMEN TIEMPOS DE CICLO ADULTOS ALTA
**************************************************************************
*/
$objPHPExcel->getActiveSheet()->getStyle('A67:L67')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A68:L68')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A69:L69')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$objPHPExcel->getActiveSheet()->getStyle('A67:L67')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A68:L68')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A69:L69')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));

$objPHPExcel->getActiveSheet()->getCell('A67')->setValue("Tiempos de Ciclo Adultos Alta");
$objPHPExcel->getActiveSheet()->getStyle('A67')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A67:L67');

$objPHPExcel->getActiveSheet()->getCell('A68')->setValue("Tiempo desde Admisión a Cierre DAU definitivo en Adultos de Alta - Promedio por Deciles");
$objPHPExcel->getActiveSheet()->getStyle('A68')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A68:L68');

$objPHPExcel->getActiveSheet()->getCell('A69')->setValue("Tipo Categorización");
$objPHPExcel->getActiveSheet()->getStyle('A69')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B69')->setValue("Total");
$objPHPExcel->getActiveSheet()->getStyle('B69')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('C69')->setValue("D1");
$objPHPExcel->getActiveSheet()->getStyle('C69')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('D69')->setValue("D2");
$objPHPExcel->getActiveSheet()->getStyle('D69')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('E69')->setValue("D3");
$objPHPExcel->getActiveSheet()->getStyle('E69')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('F69')->setValue("D4");
$objPHPExcel->getActiveSheet()->getStyle('F69')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('G69')->setValue("D5");
$objPHPExcel->getActiveSheet()->getStyle('G69')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('H69')->setValue("D6");
$objPHPExcel->getActiveSheet()->getStyle('H69')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('I69')->setValue("D7");
$objPHPExcel->getActiveSheet()->getStyle('I69')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('J69')->setValue("D8");
$objPHPExcel->getActiveSheet()->getStyle('J69')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('K69')->setValue("D9");
$objPHPExcel->getActiveSheet()->getStyle('K69')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('L69')->setValue("D10");
$objPHPExcel->getActiveSheet()->getStyle('L69')->applyFromArray($styleArray);



for ( $i = 0; $i < count($tablaResumenTiemposCicloCRUrgencia[6]); $i++ ) {
	$j = $i + 70;
	$objPHPExcel->getActiveSheet()->getStyle('A:L')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $tablaResumenTiemposCicloCRUrgencia[6][$i]['categorizacion']);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $tablaResumenTiemposCicloCRUrgencia[6][$i]['total']);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $tablaResumenTiemposCicloCRUrgencia[6][$i]['d1']);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $tablaResumenTiemposCicloCRUrgencia[6][$i]['d2']);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $tablaResumenTiemposCicloCRUrgencia[6][$i]['d3']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $tablaResumenTiemposCicloCRUrgencia[6][$i]['d4']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $tablaResumenTiemposCicloCRUrgencia[6][$i]['d5']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $j, $tablaResumenTiemposCicloCRUrgencia[6][$i]['d6']);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $j, $tablaResumenTiemposCicloCRUrgencia[6][$i]['d7']);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $j, $tablaResumenTiemposCicloCRUrgencia[6][$i]['d8']);
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $j, $tablaResumenTiemposCicloCRUrgencia[6][$i]['d9']);
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $j, $tablaResumenTiemposCicloCRUrgencia[6][$i]['d10']);
}



/*
**************************************************************************
			RESUMEN TIEMPOS DE CICLO ADULTOS ALTA URGENCIA
**************************************************************************
*/
$objPHPExcel->getActiveSheet()->getStyle('A79:L79')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A80:L80')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A81:L81')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$objPHPExcel->getActiveSheet()->getStyle('A79:L79')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A80:L80')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A81:L81')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));

$objPHPExcel->getActiveSheet()->getCell('A79')->setValue("Tiempos Procesos Urgencia Adultos");
$objPHPExcel->getActiveSheet()->getStyle('A79')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A79:L79');

$objPHPExcel->getActiveSheet()->getCell('A80')->setValue("Tiempo Desde Admisión a Indicación de Egreso en Adultos de Alta - Promedio por Deciles");
$objPHPExcel->getActiveSheet()->getStyle('A80')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A80:L80');

$objPHPExcel->getActiveSheet()->getCell('A81')->setValue("Tipo Categorización");
$objPHPExcel->getActiveSheet()->getStyle('A81')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B81')->setValue("Total");
$objPHPExcel->getActiveSheet()->getStyle('B81')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('C81')->setValue("D1");
$objPHPExcel->getActiveSheet()->getStyle('C81')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('D81')->setValue("D2");
$objPHPExcel->getActiveSheet()->getStyle('D81')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('E81')->setValue("D3");
$objPHPExcel->getActiveSheet()->getStyle('E81')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('F81')->setValue("D4");
$objPHPExcel->getActiveSheet()->getStyle('F81')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('G81')->setValue("D5");
$objPHPExcel->getActiveSheet()->getStyle('G81')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('H81')->setValue("D6");
$objPHPExcel->getActiveSheet()->getStyle('H81')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('I81')->setValue("D7");
$objPHPExcel->getActiveSheet()->getStyle('I81')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('J81')->setValue("D8");
$objPHPExcel->getActiveSheet()->getStyle('J81')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('K81')->setValue("D9");
$objPHPExcel->getActiveSheet()->getStyle('K81')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('L81')->setValue("D10");
$objPHPExcel->getActiveSheet()->getStyle('L81')->applyFromArray($styleArray);



for ( $i = 0; $i < count($tablaResumenTiemposCicloCRUrgencia[7]); $i++ ) {
	$j = $i + 82;
	$objPHPExcel->getActiveSheet()->getStyle('A:L')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $tablaResumenTiemposCicloCRUrgencia[7][$i]['categorizacion']);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $tablaResumenTiemposCicloCRUrgencia[7][$i]['total']);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $tablaResumenTiemposCicloCRUrgencia[7][$i]['d1']);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $tablaResumenTiemposCicloCRUrgencia[7][$i]['d2']);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $tablaResumenTiemposCicloCRUrgencia[7][$i]['d3']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $tablaResumenTiemposCicloCRUrgencia[7][$i]['d4']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $tablaResumenTiemposCicloCRUrgencia[7][$i]['d5']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $j, $tablaResumenTiemposCicloCRUrgencia[7][$i]['d6']);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $j, $tablaResumenTiemposCicloCRUrgencia[7][$i]['d7']);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $j, $tablaResumenTiemposCicloCRUrgencia[7][$i]['d8']);
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $j, $tablaResumenTiemposCicloCRUrgencia[7][$i]['d9']);
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $j, $tablaResumenTiemposCicloCRUrgencia[7][$i]['d10']);
}



/*
**************************************************************************
			RESUMEN TIEMPOS DE CICLO PEDIÁTRICOS ALTA
**************************************************************************
*/
$objPHPExcel->getActiveSheet()->getStyle('A91:L91')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A92:L92')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A93:L93')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$objPHPExcel->getActiveSheet()->getStyle('A91:L91')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A92:L92')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A93:L93')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));

$objPHPExcel->getActiveSheet()->getCell('A91')->setValue("Tiempos de Ciclo Pediátrico Alta");
$objPHPExcel->getActiveSheet()->getStyle('A91')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A91:L91');

$objPHPExcel->getActiveSheet()->getCell('A92')->setValue("Tiempo desde Admisión a Cierra DAU Definitivo en Pediátricos de Alta - Promedio por Deciles");
$objPHPExcel->getActiveSheet()->getStyle('A92')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A92:L92');

$objPHPExcel->getActiveSheet()->getCell('A93')->setValue("Tipo Categorización");
$objPHPExcel->getActiveSheet()->getStyle('A93')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B93')->setValue("Total");
$objPHPExcel->getActiveSheet()->getStyle('B93')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('C93')->setValue("D1");
$objPHPExcel->getActiveSheet()->getStyle('C93')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('D93')->setValue("D2");
$objPHPExcel->getActiveSheet()->getStyle('D93')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('E93')->setValue("D3");
$objPHPExcel->getActiveSheet()->getStyle('E93')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('F93')->setValue("D4");
$objPHPExcel->getActiveSheet()->getStyle('F93')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('G93')->setValue("D5");
$objPHPExcel->getActiveSheet()->getStyle('G93')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('H93')->setValue("D6");
$objPHPExcel->getActiveSheet()->getStyle('H93')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('I93')->setValue("D7");
$objPHPExcel->getActiveSheet()->getStyle('I93')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('J93')->setValue("D8");
$objPHPExcel->getActiveSheet()->getStyle('J93')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('K93')->setValue("D9");
$objPHPExcel->getActiveSheet()->getStyle('K93')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('L93')->setValue("D10");
$objPHPExcel->getActiveSheet()->getStyle('L93')->applyFromArray($styleArray);



for ( $i = 0; $i < count($tablaResumenTiemposCicloCRUrgencia[8]); $i++ ) {
	$j = $i + 94;
	$objPHPExcel->getActiveSheet()->getStyle('A:L')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $tablaResumenTiemposCicloCRUrgencia[8][$i]['categorizacion']);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $tablaResumenTiemposCicloCRUrgencia[8][$i]['total']);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $tablaResumenTiemposCicloCRUrgencia[8][$i]['d1']);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $tablaResumenTiemposCicloCRUrgencia[8][$i]['d2']);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $tablaResumenTiemposCicloCRUrgencia[8][$i]['d3']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $tablaResumenTiemposCicloCRUrgencia[8][$i]['d4']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $tablaResumenTiemposCicloCRUrgencia[8][$i]['d5']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $j, $tablaResumenTiemposCicloCRUrgencia[8][$i]['d6']);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $j, $tablaResumenTiemposCicloCRUrgencia[8][$i]['d7']);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $j, $tablaResumenTiemposCicloCRUrgencia[8][$i]['d8']);
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $j, $tablaResumenTiemposCicloCRUrgencia[8][$i]['d9']);
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $j, $tablaResumenTiemposCicloCRUrgencia[8][$i]['d10']);
}



/*
**************************************************************************
			RESUMEN TIEMPOS DE CICLO PEDIÁTRICOS URGENCIA
**************************************************************************
*/
$objPHPExcel->getActiveSheet()->getStyle('A103:L103')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A104:L104')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A105:L105')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$objPHPExcel->getActiveSheet()->getStyle('A103:L103')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A104:L104')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
$objPHPExcel->getActiveSheet()->getStyle('A105:L105')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));

$objPHPExcel->getActiveSheet()->getCell('A103')->setValue("Tiempo Procesos Urgencia Pediátrico");
$objPHPExcel->getActiveSheet()->getStyle('A103')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A103:L103');

$objPHPExcel->getActiveSheet()->getCell('A104')->setValue("Tiempo desde Admisión a Indicación de Egreso en Pediátricos - Promedio por Deciles");
$objPHPExcel->getActiveSheet()->getStyle('A104')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A104:L104');

$objPHPExcel->getActiveSheet()->getCell('A105')->setValue("Tipo Categorización");
$objPHPExcel->getActiveSheet()->getStyle('A105')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B105')->setValue("Total");
$objPHPExcel->getActiveSheet()->getStyle('B105')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('C105')->setValue("D1");
$objPHPExcel->getActiveSheet()->getStyle('C105')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('D105')->setValue("D2");
$objPHPExcel->getActiveSheet()->getStyle('D105')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('E105')->setValue("D3");
$objPHPExcel->getActiveSheet()->getStyle('E105')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('F105')->setValue("D4");
$objPHPExcel->getActiveSheet()->getStyle('F105')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('G105')->setValue("D5");
$objPHPExcel->getActiveSheet()->getStyle('G105')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('H105')->setValue("D6");
$objPHPExcel->getActiveSheet()->getStyle('H105')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('I105')->setValue("D7");
$objPHPExcel->getActiveSheet()->getStyle('I105')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('J105')->setValue("D8");
$objPHPExcel->getActiveSheet()->getStyle('J105')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('K105')->setValue("D9");
$objPHPExcel->getActiveSheet()->getStyle('K105')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('L105')->setValue("D10");
$objPHPExcel->getActiveSheet()->getStyle('L105')->applyFromArray($styleArray);



for ( $i = 0; $i < count($tablaResumenTiemposCicloCRUrgencia[9]); $i++ ) {
	$j = $i + 106;
	$objPHPExcel->getActiveSheet()->getStyle('A:L')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $tablaResumenTiemposCicloCRUrgencia[9][$i]['categorizacion']);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $tablaResumenTiemposCicloCRUrgencia[9][$i]['total']);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $tablaResumenTiemposCicloCRUrgencia[9][$i]['d1']);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $tablaResumenTiemposCicloCRUrgencia[9][$i]['d2']);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $tablaResumenTiemposCicloCRUrgencia[9][$i]['d3']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $tablaResumenTiemposCicloCRUrgencia[9][$i]['d4']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $tablaResumenTiemposCicloCRUrgencia[9][$i]['d5']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $j, $tablaResumenTiemposCicloCRUrgencia[9][$i]['d6']);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $j, $tablaResumenTiemposCicloCRUrgencia[9][$i]['d7']);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $j, $tablaResumenTiemposCicloCRUrgencia[9][$i]['d8']);
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $j, $tablaResumenTiemposCicloCRUrgencia[9][$i]['d9']);
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $j, $tablaResumenTiemposCicloCRUrgencia[9][$i]['d10']);
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