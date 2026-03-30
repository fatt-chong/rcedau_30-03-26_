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



/*
################################################################################################################################################
                                                                Declaración de Variables
*/
$parametros = array();
$parametros['fechaInicio']     			 = $objUtil->cambiarFormatoFecha2($_GET['fechaInicioReporteExcel']);
$parametros['fechaFin']        			 = $objUtil->cambiarFormatoFecha2($_GET['fechaTerminoReporteExcel']);
$parametros['frm_tipoAtencion']       	 = $_GET['tipoAtencion'];
$fecha   								 = date('d-m-Y H:i:s');
$atencion 								 = '';

switch ( $parametros['frm_tipoAtencion'] ) {

	case '1':
		$atencion = 'Adulto';
	break;

	case '2':
		$atencion = 'Pediátrico';
	break;

	case '3':
		$atencion = 'Ginecológico';
	break;

}   	

$datos = $objReportes->resumenTiemposEspera($objCon,$parametros); 



/*
################################################################################################################################################
                                                            Configuración Excel
*/

/*
**************************************************************************
							Nombre archivo
**************************************************************************
*/
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
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);

$objPHPExcel->getActiveSheet()->getStyle('A7:K7')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');
$objPHPExcel->getActiveSheet()->getStyle('A8:M8')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$styleArray = array(
	'font'  => array(
	'bold'  => true,
	'color' => array('rgb' => 'FFFFFF')
));



/*
**************************************************************************
							Títulos de celdas
**************************************************************************
*/
$objPHPExcel->getActiveSheet()->getCell('F2')->setValue("Resumen Tiempo de Espera");
$objPHPExcel->getActiveSheet()->getCell('F3')->setValue("Tipo Atención: ".$atencion);
$objPHPExcel->getActiveSheet()->getCell('F4')->setValue("Fecha Inicio: ".$_GET['fechaInicioReporteExcel']." - Fecha Término: ".$_GET['fechaTerminoReporteExcel']);
$objPHPExcel->getActiveSheet()->getStyle('F2:G2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
$objPHPExcel->getActiveSheet()->getStyle('F3:G3')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
$objPHPExcel->getActiveSheet()->getStyle('F4:G4')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));		
$objPHPExcel->getActiveSheet()->mergeCells('F2:G2');
$objPHPExcel->getActiveSheet()->mergeCells('F3:G3');
$objPHPExcel->getActiveSheet()->mergeCells('F4:G4');

$objPHPExcel->getActiveSheet()->getCell('A7')->setValue("ID Dau");
$objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('B7')->setValue("Estado");
$objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('C7')->setValue("Tipo Consulta");
$objPHPExcel->getActiveSheet()->getStyle('C7')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('D7')->setValue("Categorización");
$objPHPExcel->getActiveSheet()->getStyle('D7')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('E7')->setValue("Admisión");
$objPHPExcel->getActiveSheet()->getStyle('E7')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('F7')->setValue("Fecha Categorización");
$objPHPExcel->getActiveSheet()->getStyle('F7')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('G7')->setValue("Fecha Atención");
$objPHPExcel->getActiveSheet()->getStyle('G7')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('H7')->setValue("Fecha Indicación");
$objPHPExcel->getActiveSheet()->getStyle('H7')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('I7')->setValue("Término Atención");
$objPHPExcel->getActiveSheet()->getStyle('I7')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('J7')->setValue("Tipo Indicación");
$objPHPExcel->getActiveSheet()->getStyle('J7')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getCell('K7')->setValue("TIEMPO DE ESPERA (MINUTOS)");
$objPHPExcel->getActiveSheet()->getStyle('K7')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A7:M7')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));		
$objPHPExcel->getActiveSheet()->mergeCells('K7:M7');


$objPHPExcel->getActiveSheet()->getCell('K8')->setValue("ADM-CATE");
$objPHPExcel->getActiveSheet()->getCell('L8')->setValue("CATE-ATEN");
$objPHPExcel->getActiveSheet()->getCell('M8')->setValue("IND-ATEN");
$objPHPExcel->getActiveSheet()->getStyle('K8:M8')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
$objPHPExcel->getActiveSheet()->getStyle('K8:M8')->applyFromArray($styleArray);



/*
**************************************************************************
							Contenido de celdas
**************************************************************************
*/
for($i=0; $i<count($datos); $i++){
	$j = $i + 9;
	$objPHPExcel->getActiveSheet()->getStyle('A:M')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER ));	
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $datos[$i]['dau_id']);
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $datos[$i]['est_descripcion']);
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $datos[$i]['mot_descripcion']);	
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $datos[$i]['dau_categorizacion_actual']);	
	$objPHPExcel->getActiveSheet()->setCellValue('E' . $j, date("d-m-Y H:i:s",strtotime($datos[$i]['dau_admision_fecha'])));
	if ( $datos[$i]['dau_categorizacion_actual_fecha'] != "" ) {
		$objPHPExcel->getActiveSheet()->setCellValue('F' . $j, date("d-m-Y H:i:s",strtotime($datos[$i]['dau_categorizacion_actual_fecha'])));
	} else {
		$objPHPExcel->getActiveSheet()->setCellValue('F' . $j, '');
	}
	if ( $datos[$i]['dau_inicio_atencion_fecha'] != "" ) {
		$objPHPExcel->getActiveSheet()->setCellValue('G' . $j, date("d-m-Y H:i:s",strtotime($datos[$i]['dau_inicio_atencion_fecha'])));
	} else {
		$objPHPExcel->getActiveSheet()->setCellValue('G' . $j, '');
	}
	if ( $datos[$i]['est_descripcion'] == 'N.E.A' ) {
		$objPHPExcel->getActiveSheet()->setCellValue('H' . $j, date("d-m-Y H:i:s",strtotime($datos[$i]['dau_cierre_administrativo_fecha'])));
	} else if ($datos[$i]['dau_indicacion_egreso_fecha'] != "" ) {
		$objPHPExcel->getActiveSheet()->setCellValue('H' . $j, date("d-m-Y H:i:s",strtotime($datos[$i]['dau_indicacion_egreso_fecha'])));
	}
	if ( $datos[$i]['dau_indicacion_egreso_aplica_fecha'] != "" ) {
		$objPHPExcel->getActiveSheet()->setCellValue('I' . $j, $datos[$i]['dau_indicacion_egreso_aplica_fecha']);
	} else {
		$objPHPExcel->getActiveSheet()->setCellValue('I' . $j, '');
	}
	$objPHPExcel->getActiveSheet()->setCellValue('J' . $j, $datos[$i]['ind_egr_descripcion']);	
	$objPHPExcel->getActiveSheet()->setCellValue('k' . $j, $datos[$i]['ADM_CATE']);	
	$objPHPExcel->getActiveSheet()->setCellValue('L' . $j, $datos[$i]['CATE_ATEN']);	
	$objPHPExcel->getActiveSheet()->setCellValue('M' . $j, $datos[$i]['IND_ATEN']);	
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