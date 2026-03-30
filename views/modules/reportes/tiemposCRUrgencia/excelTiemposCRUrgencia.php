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
// $fechaInicio     			                    = $_POST['fechaInicio'];
// $fechaTermino    			                    = $_POST['fechaTermino'];

$requestBody = file_get_contents("php://input");
$data = json_decode($requestBody, true);
// Acceso a los parámetros
$fechaInicio = $data['fechaInicio'];
$fechaTermino = $data['fechaTermino'];

$tablaDemandaUrgenciaAdultoPediatrica 			= json_decode($data['tablaDemandaUrgenciaAdultoPediatrica'], true);
$tablaResumenTiempoEsperaAdultosPediatricos     = json_decode($data['tablaResumenTiempoEsperaAdultosPediatricos'], true);
$tablaResumenTiempoEsperaAdultoDeciles          = json_decode($data['tablaResumenTiempoEsperaAdultoDeciles'], true);
$tablaResumenTiempoEsperaPediatricoDeciles      = json_decode($data['tablaResumenTiempoEsperaPediatricoDeciles'], true);
$tablaResumenCumplimientoCategorizacionESI      = json_decode($data['tablaResumenCumplimientoCategorizacionESI'], true);
$tablaResumenDiagnosticosInespecificos          = json_decode($data['tablaResumenDiagnosticosInespecificos'], true);
$tablaResumenTiemposIndicacionesLaboratorio     = json_decode($data['tablaResumenTiemposIndicacionesLaboratorio'], true);
$tablaResumenTiemposIndicacionesImagenologia    = json_decode($data['tablaResumenTiemposIndicacionesImagenologia'], true);



/*
################################################################################################################################################
                                                            Configuración Excel
*/

/*
**************************************************************************
							Nombre archivo
**************************************************************************
*/
// header('Content-Disposition: attachment;filename="Resumen Tiempos CR Urgencia( Desde_'.$fechaInicio.'__Hasta_'.$fechaTermino.').xls"');



/*
**************************************************************************
						Propiedades de Excel
**************************************************************************
*/
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



$styleArray = array(
	'font'  => array(
	'bold'  => true,
	'color' => array('rgb' => 'FFFFFF')
));



/*
**************************************************************************
					DEMANDA URGENCIA ADULTO PEDIÁTRICA
**************************************************************************
*/
$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	

$objPHPExcel->getActiveSheet()->getCell('A2')->setValue("Demanda Urgencia Adulto y Pediátrico");
$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A2:G2');

$objPHPExcel->getActiveSheet()->getCell('A3')->setValue("Descripción Tipo de Demanda");
$objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B3')->setValue("Adulto");
$objPHPExcel->getActiveSheet()->getStyle('B3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('C3')->setValue("% Adultos");
$objPHPExcel->getActiveSheet()->getStyle('C3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('D3')->setValue("Pediátricos");
$objPHPExcel->getActiveSheet()->getStyle('D3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('E3')->setValue("% Pediátricos");
$objPHPExcel->getActiveSheet()->getStyle('E3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('F3')->setValue("Todos");
$objPHPExcel->getActiveSheet()->getStyle('F3')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('G3')->setValue("% Todos");
$objPHPExcel->getActiveSheet()->getStyle('G3')->applyFromArray($styleArray);

for ( $i = 0; $i < count($tablaDemandaUrgenciaAdultoPediatrica); $i++ ) {
	$j = $i + 4;
	$objPHPExcel->getActiveSheet()->getStyle('A:G')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $tablaDemandaUrgenciaAdultoPediatrica[$i]['tipoDemanda']);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $tablaDemandaUrgenciaAdultoPediatrica[$i]['adultos']);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $tablaDemandaUrgenciaAdultoPediatrica[$i]['adultosPorcentaje']);	
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $tablaDemandaUrgenciaAdultoPediatrica[$i]['pediatricos']);	
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $tablaDemandaUrgenciaAdultoPediatrica[$i]['pediatricosPorcentaje']);	
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $tablaDemandaUrgenciaAdultoPediatrica[$i]['todos']);	
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $tablaDemandaUrgenciaAdultoPediatrica[$i]['todosPorcentaje']);	
}



/*
**************************************************************************
			RESUMEN TIEMPOS DE ESPERA ADULTOS Y PEDIÁTRICOS
**************************************************************************
*/
$objPHPExcel->getActiveSheet()->getStyle('A11:G11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A12:G12')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A13:G13')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$objPHPExcel->getActiveSheet()->getStyle('A11:G11')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
$objPHPExcel->getActiveSheet()->getStyle('A12:G12')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
$objPHPExcel->getActiveSheet()->getStyle('A13:G13')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	

$objPHPExcel->getActiveSheet()->getCell('A11')->setValue("Resumen Tiempos de Espera Adultos y Pediátricos");
$objPHPExcel->getActiveSheet()->getStyle('A11')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A11:G11');

$objPHPExcel->getActiveSheet()->getCell('A12')->setValue("Categorización");
$objPHPExcel->getActiveSheet()->getStyle('A12')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B12')->setValue("Adultos");
$objPHPExcel->getActiveSheet()->getStyle('B12')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('B12:D12');

$objPHPExcel->getActiveSheet()->getCell('E12')->setValue("Pediátricos");
$objPHPExcel->getActiveSheet()->getStyle('E12')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('E12:G12');

$objPHPExcel->getActiveSheet()->getCell('B13')->setValue("Cantidad DAU");
$objPHPExcel->getActiveSheet()->getStyle('B13')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('C13')->setValue("Tiempo Espera Promedio");
$objPHPExcel->getActiveSheet()->getStyle('C13')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('D13')->setValue("Tiempo Espera Máximo");
$objPHPExcel->getActiveSheet()->getStyle('D13')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('E13')->setValue("Cantidad DAU");
$objPHPExcel->getActiveSheet()->getStyle('E13')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('F13')->setValue("Tiempo Espera Promedio");
$objPHPExcel->getActiveSheet()->getStyle('F13')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('G13')->setValue("Tiempo Espera Máximo");
$objPHPExcel->getActiveSheet()->getStyle('G13')->applyFromArray($styleArray);

for ( $i = 0; $i < count($tablaResumenTiempoEsperaAdultosPediatricos); $i++ ) {
	$j = $i + 14;
	$objPHPExcel->getActiveSheet()->getStyle('A:G')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $tablaResumenTiempoEsperaAdultosPediatricos[$i]['tipCategorizacion']);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $tablaResumenTiempoEsperaAdultosPediatricos[$i]['cantidadDauAdulto']);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $tablaResumenTiempoEsperaAdultosPediatricos[$i]['tiempoEsperaPromedioAdulto']);	
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $tablaResumenTiempoEsperaAdultosPediatricos[$i]['tiempoEsperaMaximoAdulto']);	
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $tablaResumenTiempoEsperaAdultosPediatricos[$i]['cantidadDauPediatrico']);	
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $tablaResumenTiempoEsperaAdultosPediatricos[$i]['tiempoEsperaPromedioPediatrico']);	
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $tablaResumenTiempoEsperaAdultosPediatricos[$i]['tiempoEsperaMximoPediatrico']);	
}



/*
**************************************************************************
				RESUMEN TIEMPO DE ESPERA DECILES ADULTOS
**************************************************************************
*/
$objPHPExcel->getActiveSheet()->getStyle('A23:K23')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A24:K24')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$objPHPExcel->getActiveSheet()->getStyle('A23:K23')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
$objPHPExcel->getActiveSheet()->getStyle('A24:K24')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	

$objPHPExcel->getActiveSheet()->getCell('A23')->setValue("Resumen Tiempos de Espera Desciles Adultos");
$objPHPExcel->getActiveSheet()->getStyle('A23')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A23:K23');

$objPHPExcel->getActiveSheet()->getCell('A24')->setValue("Categorización");
$objPHPExcel->getActiveSheet()->getStyle('A24')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B24')->setValue("Hrs D1");
$objPHPExcel->getActiveSheet()->getStyle('B24')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('C24')->setValue("Hrs D2");
$objPHPExcel->getActiveSheet()->getStyle('C24')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('D24')->setValue("Hrs D3");
$objPHPExcel->getActiveSheet()->getStyle('D24')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('E24')->setValue("Hrs D4");
$objPHPExcel->getActiveSheet()->getStyle('E24')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('F24')->setValue("Hrs D5");
$objPHPExcel->getActiveSheet()->getStyle('F24')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('G24')->setValue("Hrs D6");
$objPHPExcel->getActiveSheet()->getStyle('G24')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('H24')->setValue("Hrs D7");
$objPHPExcel->getActiveSheet()->getStyle('H24')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('I24')->setValue("Hrs D8");
$objPHPExcel->getActiveSheet()->getStyle('I24')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('J24')->setValue("Hrs D9");
$objPHPExcel->getActiveSheet()->getStyle('J24')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('K24')->setValue("Hrs D10");
$objPHPExcel->getActiveSheet()->getStyle('K24')->applyFromArray($styleArray);

for ( $i = 0; $i < count($tablaResumenTiempoEsperaAdultoDeciles); $i++ ) {
	$j = $i + 25;
	$objPHPExcel->getActiveSheet()->getStyle('A:K')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $tablaResumenTiempoEsperaAdultoDeciles[$i]['tipCategorizacion']);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $tablaResumenTiempoEsperaAdultoDeciles[$i]['d1']);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $tablaResumenTiempoEsperaAdultoDeciles[$i]['d2']);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $tablaResumenTiempoEsperaAdultoDeciles[$i]['d3']);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $tablaResumenTiempoEsperaAdultoDeciles[$i]['d4']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $tablaResumenTiempoEsperaAdultoDeciles[$i]['d5']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $tablaResumenTiempoEsperaAdultoDeciles[$i]['d6']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $j, $tablaResumenTiempoEsperaAdultoDeciles[$i]['d7']);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $j, $tablaResumenTiempoEsperaAdultoDeciles[$i]['d8']);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $j, $tablaResumenTiempoEsperaAdultoDeciles[$i]['d9']);
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $j, $tablaResumenTiempoEsperaAdultoDeciles[$i]['d10']);

}



/*
**************************************************************************
			RESUMEN TIEMPO DE ESPERA DECILES PEDIÁTRICOS
**************************************************************************
*/
$objPHPExcel->getActiveSheet()->getStyle('A34:K34')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A35:K35')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$objPHPExcel->getActiveSheet()->getStyle('A34:K34')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
$objPHPExcel->getActiveSheet()->getStyle('A35:K35')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	

$objPHPExcel->getActiveSheet()->getCell('A34')->setValue("Resumen Tiempos de Espera Desciles Pediátricos");
$objPHPExcel->getActiveSheet()->getStyle('A34')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A34:K34');

$objPHPExcel->getActiveSheet()->getCell('A35')->setValue("Categorización");
$objPHPExcel->getActiveSheet()->getStyle('A35')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B35')->setValue("Hrs D1");
$objPHPExcel->getActiveSheet()->getStyle('B35')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('C35')->setValue("Hrs D2");
$objPHPExcel->getActiveSheet()->getStyle('C35')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('D35')->setValue("Hrs D3");
$objPHPExcel->getActiveSheet()->getStyle('D35')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('E35')->setValue("Hrs D4");
$objPHPExcel->getActiveSheet()->getStyle('E35')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('F35')->setValue("Hrs D5");
$objPHPExcel->getActiveSheet()->getStyle('F35')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('G35')->setValue("Hrs D6");
$objPHPExcel->getActiveSheet()->getStyle('G35')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('H35')->setValue("Hrs D7");
$objPHPExcel->getActiveSheet()->getStyle('H35')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('I35')->setValue("Hrs D8");
$objPHPExcel->getActiveSheet()->getStyle('I35')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('J35')->setValue("Hrs D9");
$objPHPExcel->getActiveSheet()->getStyle('J35')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('K35')->setValue("Hrs D10");
$objPHPExcel->getActiveSheet()->getStyle('K35')->applyFromArray($styleArray);

for ( $i = 0; $i < count($tablaResumenTiempoEsperaPediatricoDeciles); $i++ ) {
	$j = $i + 36;
	$objPHPExcel->getActiveSheet()->getStyle('A:K')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $tablaResumenTiempoEsperaPediatricoDeciles[$i]['tipCategorizacion']);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $tablaResumenTiempoEsperaPediatricoDeciles[$i]['d1']);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $tablaResumenTiempoEsperaPediatricoDeciles[$i]['d2']);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $tablaResumenTiempoEsperaPediatricoDeciles[$i]['d3']);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $tablaResumenTiempoEsperaPediatricoDeciles[$i]['d4']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $tablaResumenTiempoEsperaPediatricoDeciles[$i]['d5']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $tablaResumenTiempoEsperaPediatricoDeciles[$i]['d6']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $j, $tablaResumenTiempoEsperaPediatricoDeciles[$i]['d7']);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $j, $tablaResumenTiempoEsperaPediatricoDeciles[$i]['d8']);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $j, $tablaResumenTiempoEsperaPediatricoDeciles[$i]['d9']);
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $j, $tablaResumenTiempoEsperaPediatricoDeciles[$i]['d10']);

}



/*
**************************************************************************
			    RESUMEN CUMPLIMIENTO CATEGORIZACIÓN ESI
**************************************************************************
*/
$objPHPExcel->getActiveSheet()->getStyle('A45:G45')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A46:G46')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A47:G47')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$objPHPExcel->getActiveSheet()->getStyle('A45:G45')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
$objPHPExcel->getActiveSheet()->getStyle('A46:G46')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
$objPHPExcel->getActiveSheet()->getStyle('A47:G47')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	

$objPHPExcel->getActiveSheet()->getCell('A45')->setValue("Resumen Cumplimiento Categorización ESI");
$objPHPExcel->getActiveSheet()->getStyle('A45')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A45:G45');

$objPHPExcel->getActiveSheet()->getCell('A46')->setValue("Categorización");
$objPHPExcel->getActiveSheet()->getStyle('A46')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B46')->setValue("Adultos");
$objPHPExcel->getActiveSheet()->getStyle('B46')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('B46:D46');

$objPHPExcel->getActiveSheet()->getCell('E46')->setValue("Pediátricos");
$objPHPExcel->getActiveSheet()->getStyle('E46')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('E46:G46');

$objPHPExcel->getActiveSheet()->getCell('B47')->setValue("Cantidad DAU");
$objPHPExcel->getActiveSheet()->getStyle('B47')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('C47')->setValue("Atendidos a Tiempo");
$objPHPExcel->getActiveSheet()->getStyle('C47')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('D47')->setValue("% Atendidos a Tiempo");
$objPHPExcel->getActiveSheet()->getStyle('D47')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('E47')->setValue("Cantidad DAU");
$objPHPExcel->getActiveSheet()->getStyle('E47')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('F47')->setValue("Atendidos a Tiempo");
$objPHPExcel->getActiveSheet()->getStyle('F47')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('G47')->setValue("% Atendidos a Tiempo");
$objPHPExcel->getActiveSheet()->getStyle('G47')->applyFromArray($styleArray);

for ( $i = 0; $i < count($tablaResumenCumplimientoCategorizacionESI); $i++ ) {
	$j = $i + 48;
	$objPHPExcel->getActiveSheet()->getStyle('A:G')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $tablaResumenCumplimientoCategorizacionESI[$i]['tipCategorizacion']);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $tablaResumenCumplimientoCategorizacionESI[$i]['cantidadDauAdulto']);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $tablaResumenCumplimientoCategorizacionESI[$i]['atendidosATiempoAdulto']);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $tablaResumenCumplimientoCategorizacionESI[$i]['atendidosATiempoAdultoPorcentaje']);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $tablaResumenCumplimientoCategorizacionESI[$i]['cantidadDauPediatrico']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $tablaResumenCumplimientoCategorizacionESI[$i]['atendidosATiempoPediatrico']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $tablaResumenCumplimientoCategorizacionESI[$i]['atendidosATiempoPediatricoPorcentaje']);

}



/*
**************************************************************************
			        RESUMEN DIAGNÓSTICO INESPECÍFICOS
**************************************************************************
*/
$objPHPExcel->getActiveSheet()->getStyle('A56:E56')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A57:E57')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$objPHPExcel->getActiveSheet()->getStyle('A56:E56')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
$objPHPExcel->getActiveSheet()->getStyle('A57:E57')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	

$objPHPExcel->getActiveSheet()->getCell('A56')->setValue("Resumen Diagnósticos Inespecíficos");
$objPHPExcel->getActiveSheet()->getStyle('A56')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A56:E56');

$objPHPExcel->getActiveSheet()->getCell('A57')->setValue("Tipo de Diagnóstico");
$objPHPExcel->getActiveSheet()->getStyle('A57')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B57')->setValue("Adultos");
$objPHPExcel->getActiveSheet()->getStyle('B57')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('C57')->setValue("% Adultos");
$objPHPExcel->getActiveSheet()->getStyle('C57')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('D57')->setValue("Pediátricos");
$objPHPExcel->getActiveSheet()->getStyle('D57')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('E57')->setValue("% Pediátricos");
$objPHPExcel->getActiveSheet()->getStyle('E57')->applyFromArray($styleArray);

for ( $i = 0; $i < count($tablaResumenDiagnosticosInespecificos); $i++ ) {
	$j = $i + 58;
	$objPHPExcel->getActiveSheet()->getStyle('A:E')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $tablaResumenDiagnosticosInespecificos[$i]['tipoDiagnostico']);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $tablaResumenDiagnosticosInespecificos[$i]['adultos']);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $tablaResumenDiagnosticosInespecificos[$i]['adultosPorcentaje']);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $tablaResumenDiagnosticosInespecificos[$i]['pediatricos']);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $tablaResumenDiagnosticosInespecificos[$i]['pediatricosPorcenjate']);

}



/*
**************************************************************************
			        RESUMEN TIEMPOS INDICACIONES LABORATORIO
**************************************************************************
*/
$objPHPExcel->getActiveSheet()->getStyle('A63:K63')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A64:K64')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A65:K65')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$objPHPExcel->getActiveSheet()->getStyle('A63:K63')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
$objPHPExcel->getActiveSheet()->getStyle('A64:K64')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
$objPHPExcel->getActiveSheet()->getStyle('A65:K65')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	

$objPHPExcel->getActiveSheet()->getCell('A63')->setValue("Resumen Tiempos Indicaciones Laboratorio");
$objPHPExcel->getActiveSheet()->getStyle('A63')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A63:K63');

$objPHPExcel->getActiveSheet()->getCell('A64')->setValue("DAU's");
$objPHPExcel->getActiveSheet()->getStyle('A64')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B64')->setValue("Indicaciones");
$objPHPExcel->getActiveSheet()->getStyle('B64')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('C64')->setValue("Tiempos Desde: Indicación a Toma Muestra");
$objPHPExcel->getActiveSheet()->getStyle('C64')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('C64:E64');

$objPHPExcel->getActiveSheet()->getCell('F64')->setValue("Tiempos Desde: Toma de Muestra a Recepción");
$objPHPExcel->getActiveSheet()->getStyle('F64')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('F64:H64');

$objPHPExcel->getActiveSheet()->getCell('I64')->setValue("Tiempos Desde: Recepción a Realización");
$objPHPExcel->getActiveSheet()->getStyle('I64')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('I64:J64');

$objPHPExcel->getActiveSheet()->getCell('C65')->setValue("Tiempo Promedio");
$objPHPExcel->getActiveSheet()->getStyle('C65')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('D65')->setValue("Tiempo Máximo");
$objPHPExcel->getActiveSheet()->getStyle('D65')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('E65')->setValue("Tiempo Mínimo");
$objPHPExcel->getActiveSheet()->getStyle('E65')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('F65')->setValue("Tiempo Promedio");
$objPHPExcel->getActiveSheet()->getStyle('F65')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('G65')->setValue("Tiempo Máximo");
$objPHPExcel->getActiveSheet()->getStyle('G65')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('H65')->setValue("Tiempo Mínimo");
$objPHPExcel->getActiveSheet()->getStyle('H65')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('I65')->setValue("Tiempo Promedio");
$objPHPExcel->getActiveSheet()->getStyle('I65')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('J65')->setValue("Tiempo Máximo");
$objPHPExcel->getActiveSheet()->getStyle('J65')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('K65')->setValue("Tiempo Mínimo");
$objPHPExcel->getActiveSheet()->getStyle('K65')->applyFromArray($styleArray);

for ( $i = 0; $i < count($tablaResumenTiemposIndicacionesLaboratorio); $i++ ) {
	$j = $i + 66;
	$objPHPExcel->getActiveSheet()->getStyle('A:E')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $tablaResumenTiemposIndicacionesLaboratorio[$i]['cantidadDau']);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $tablaResumenTiemposIndicacionesLaboratorio[$i]['cantidadIndicaciones']);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $tablaResumenTiemposIndicacionesLaboratorio[$i]['tiempoPromedioIndicacionTomaMuestra']);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $tablaResumenTiemposIndicacionesLaboratorio[$i]['tiempoMaximoIndicacionTomaMuestra']);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $tablaResumenTiemposIndicacionesLaboratorio[$i]['tiempoMinimoIndicacionTomaMuestra']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $tablaResumenTiemposIndicacionesLaboratorio[$i]['tiempoPromedioIndicacionMuestraRecepcion']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $tablaResumenTiemposIndicacionesLaboratorio[$i]['tiempoMaximoIndicacionMuestraRecepcion']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $j, $tablaResumenTiemposIndicacionesLaboratorio[$i]['tiempoMinimoIndicacionMuestraRecepcion']);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $j, $tablaResumenTiemposIndicacionesLaboratorio[$i]['tiempoPromedioIndicacionRecepcionRealizacion']);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $j, $tablaResumenTiemposIndicacionesLaboratorio[$i]['tiempoMaximoIndicacionRecepcionRealizacion']);
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $j, $tablaResumenTiemposIndicacionesLaboratorio[$i]['tiempoMinimoIndicacionRecepcionRealizacion']);

}



/*
**************************************************************************
			    RESUMEN TIEMPOS INDICACIONES IMAGENOLOGÍA
**************************************************************************
*/
$objPHPExcel->getActiveSheet()->getStyle('A70:F70')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A71:F71')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A72:F72')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$objPHPExcel->getActiveSheet()->getStyle('A70:K70')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
$objPHPExcel->getActiveSheet()->getStyle('A71:K71')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
$objPHPExcel->getActiveSheet()->getStyle('A72:K72')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	

$objPHPExcel->getActiveSheet()->getCell('A70')->setValue("Resumen Tiempos Indicaciones Imagenología");
$objPHPExcel->getActiveSheet()->getStyle('A70')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('A70:F70');

$objPHPExcel->getActiveSheet()->getCell('A71')->setValue("Tipo Exámen");
$objPHPExcel->getActiveSheet()->getStyle('A71')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B71')->setValue("DAU's");
$objPHPExcel->getActiveSheet()->getStyle('B71')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('C71')->setValue("Indicaciones");
$objPHPExcel->getActiveSheet()->getStyle('C71')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('D71')->setValue("Tiempo desde: Indicación a Aplicación");
$objPHPExcel->getActiveSheet()->getStyle('D71')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->mergeCells('D71:F71');

$objPHPExcel->getActiveSheet()->getCell('D72')->setValue("Tiempo Promedio");
$objPHPExcel->getActiveSheet()->getStyle('D72')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('E72')->setValue("Tiempo Máximo");
$objPHPExcel->getActiveSheet()->getStyle('E72')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('F72')->setValue("Tiempo Mínimo");
$objPHPExcel->getActiveSheet()->getStyle('F72')->applyFromArray($styleArray);

for ( $i = 0; $i < count($tablaResumenTiemposIndicacionesImagenologia); $i++ ) {
	$j = $i + 73;
	$objPHPExcel->getActiveSheet()->getStyle('A:E')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $tablaResumenTiemposIndicacionesImagenologia[$i]['tipoExamen']);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $tablaResumenTiemposIndicacionesImagenologia[$i]['cantidadDau']);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $tablaResumenTiemposIndicacionesImagenologia[$i]['cantidadIndicaciones']);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $tablaResumenTiemposIndicacionesImagenologia[$i]['tiempoPromedio']);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $tablaResumenTiemposIndicacionesImagenologia[$i]['tiempoMaximo']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $tablaResumenTiemposIndicacionesImagenologia[$i]['tiempoMinimo']);

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