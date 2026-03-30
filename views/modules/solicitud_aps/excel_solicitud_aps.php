<?php
ini_set('memory_limit', '5G');
error_reporting(0);



/*
################################################################################################################################################
                                                                Links de Clases
*/
require("../../../config/config.php");
require_once("../../../class/Connection.class.php");                    $objCon      = new Connection();
require_once("../../../class/Rce.class.php" );                          $objRce      = new Rce;
require_once("../../../class/Util.class.php"); 		                    $objUtil     = new Util;
require_once('../../../../estandar/PHPExcel8/Classes/PHPExcel.php');   $objPHPExcel      = new PHPExcel();



/*
################################################################################################################################################
                                                                Declaración de Variables
*/
$objCon->db_connect();

$parametros = $objUtil->getFormulario($_GET);

$resultado = $objRce->obtenerResultadoSolicitudesAPSExcel($objCon, $parametros);



/*
################################################################################################################################################
                                                            Configuración Excel
*/

/*
**************************************************************************
							Nombre archivo
**************************************************************************
*/
// header('Content-Disposition: attachment;filename="Solicitudes APS.xls"');



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
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(60);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(60);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(60);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(60);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(60);



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
$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$objPHPExcel->getActiveSheet()->getStyle('A2:I2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));

$objPHPExcel->getActiveSheet()->getCell('A2')->setValue("Fecha Solicitud");
$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B2')->setValue("RUN Paciente");
$objPHPExcel->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('C2')->setValue("Nombre Paciente");
$objPHPExcel->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('D2')->setValue("Consultorio");
$objPHPExcel->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('E2')->setValue("Diagnóstico");
$objPHPExcel->getActiveSheet()->getStyle('E2')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('F2')->setValue("Estado");
$objPHPExcel->getActiveSheet()->getStyle('F2')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('G2')->setValue("Prioridad");
$objPHPExcel->getActiveSheet()->getStyle('G2')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('H2')->setValue("Programa");
$objPHPExcel->getActiveSheet()->getStyle('H2')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('I2')->setValue("Observación");
$objPHPExcel->getActiveSheet()->getStyle('I2')->applyFromArray($styleArray);

for ( $i = 0; $i < count($resultado); $i++ ) {
	$j = $i + 3;
	$objPHPExcel->getActiveSheet()->getStyle('A:I')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, date("d-m-Y H:i:s", strtotime($resultado[$i]['fechaSolicitud'])));
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $objUtil->formatearNumero($resultado[$i]['rutPaciente']).'-'.$objUtil->generaDigito($resultado[$i]['rutPaciente']));
	//$objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $resultado[$i]['rutPaciente']);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $resultado[$i]['nombrePaciente']);
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $resultado[$i]['descripcionConsultorio']);
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $resultado[$i]['descripcionCie10']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $resultado[$i]['descripcionEstadoSolicitud']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $j, $resultado[$i]['descripcionPrioridadSolicitud']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $j, $resultado[$i]['descripcionPrograma']);
	$objPHPExcel->getActiveSheet()->setCellValue('I' . $j, $resultado[$i]['observacionSolicitud']);
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