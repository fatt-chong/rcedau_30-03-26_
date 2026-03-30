<?php

ini_set('memory_limit', '5G');

require($_SERVER['DOCUMENT_ROOT']."/dauRCE/config/config.php");

require($_SERVER['DOCUMENT_ROOT']."/dauRCE/class/Connection.class.php");

require($_SERVER['DOCUMENT_ROOT']."/dauRCE/class/Util.class.php");

require($_SERVER['DOCUMENT_ROOT']."/dauRCE/class/Reportes.class.php");

require($_SERVER['DOCUMENT_ROOT']."/estandar/PHPExcel/Classes/PHPExcel.php");

$objCon        = new Connection();

$objCon->db_connect();

$objUtil                    = new Util;

$objReportes                = new Reportes;

$parametros                 = $objUtil->getFormulario($_POST);

$parametros['fechaInicio']  = $objUtil->fechaInvertida($parametros['fechaInicio']);

$parametros['fechaTermino'] = $objUtil->fechaInvertida($parametros['fechaTermino']);

$fechaHoy                   = $objUtil->getFechaPalabra(date('Y-m-d'));

$objCon = $objUtil->cambiarServidorReporte($parametros['fechaInicio'], $parametros['fechaTermino']);

$datos                      = $objReportes->obtenerReporteEndovenosoCat4($objCon,$parametros);

$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("Plataforma Web HJNC")
								->setLastModifiedBy("Plataforma Web HJNC")
								->setTitle("Reporte Indicaciones EV CAT ESI-4")
								->setSubject("Office 2007 XLSX Document")
								->setDescription("Excel Document")
								->setKeywords("HJNC")
								->setCategory("Sistema RRHH");
PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(50);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(50);

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
$objPHPExcel->getActiveSheet()->getStyle('A2:O2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('5b9bd5');

$objPHPExcel->getActiveSheet()->getStyle('A2:O2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT ));

//DAU
$objPHPExcel->getActiveSheet()->getCell('A2')->setValue("DAU");
$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);

//Id paciente
$objPHPExcel->getActiveSheet()->getCell('B2')->setValue("ID Paciente");
$objPHPExcel->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);

//Tipo paciente
$objPHPExcel->getActiveSheet()->getCell('C2')->setValue("Tipo Atención");
$objPHPExcel->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);

//Nombre paciente
$objPHPExcel->getActiveSheet()->getCell('D2')->setValue("Nombre Paciente");
$objPHPExcel->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray);

//RUN paciente
$objPHPExcel->getActiveSheet()->getCell('E2')->setValue("RUN Paciente");
$objPHPExcel->getActiveSheet()->getStyle('E2')->applyFromArray($styleArray);

//Edad paciente
$objPHPExcel->getActiveSheet()->getCell('F2')->setValue("Edad Paciente");
$objPHPExcel->getActiveSheet()->getStyle('F2')->applyFromArray($styleArray);

//Tratamiento
$objPHPExcel->getActiveSheet()->getCell('G2')->setValue("Tratamiento");
$objPHPExcel->getActiveSheet()->getStyle('G2')->applyFromArray($styleArray);

//Fecha admisión
$objPHPExcel->getActiveSheet()->getCell('H2')->setValue("Fecha Admisión");
$objPHPExcel->getActiveSheet()->getStyle('H2')->applyFromArray($styleArray)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DMYMINUS);

//Fecha indicación
$objPHPExcel->getActiveSheet()->getCell('I2')->setValue("Fecha Indicación");
$objPHPExcel->getActiveSheet()->getStyle('I2')->applyFromArray($styleArray)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DMYMINUS);

//Usuario indicación
$objPHPExcel->getActiveSheet()->getCell('J2')->setValue("Usuario Indicación");
$objPHPExcel->getActiveSheet()->getStyle('J2')->applyFromArray($styleArray);

//Fecha inicio
$objPHPExcel->getActiveSheet()->getCell('K2')->setValue("Fecha Inicio");
$objPHPExcel->getActiveSheet()->getStyle('K2')->applyFromArray($styleArray)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DMYMINUS);

//Usuario inicia
$objPHPExcel->getActiveSheet()->getCell('L2')->setValue("Usuario Inicia");
$objPHPExcel->getActiveSheet()->getStyle('L2')->applyFromArray($styleArray);

//Fecha aplicación
$objPHPExcel->getActiveSheet()->getCell('M2')->setValue("Fecha Aplicación");
$objPHPExcel->getActiveSheet()->getStyle('M2')->applyFromArray($styleArray)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DMYMINUS);

//Usuario aplica
$objPHPExcel->getActiveSheet()->getCell('N2')->setValue("Usuario Aplica");
$objPHPExcel->getActiveSheet()->getStyle('N2')->applyFromArray($styleArray);

//CIE 10
$objPHPExcel->getActiveSheet()->getCell('O2')->setValue("CIE10");
$objPHPExcel->getActiveSheet()->getStyle('O2')->applyFromArray($styleArray);

for ( $i = 0; $i < count($datos); $i++ ) {

	$j = $i + 3;

	$transexual_bd   = $datos[$i]['transexual'];
	$nombreSocial_bd = $datos[$i]['nombreSocial'];
	$nombrePaciente  = $datos[$i]['Nombre Paciente'];

	$infoNombre = $objUtil->infoNombreDocExcel($transexual_bd,$nombreSocial_bd,$nombrePaciente);

	$tratamiento = str_replace("<br>", "\n- ", $datos[$i]['Tratamiento']);

	$objPHPExcel->getActiveSheet()->getStyle('A:O')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT ));

	$objPHPExcel->getActiveSheet()->getStyle('A:O')->getAlignment()->applyFromArray(array('vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP ));

	$objPHPExcel->getActiveSheet()->setCellValue('A' . $j, $datos[$i]['Id Dau']);

	$objPHPExcel->getActiveSheet()->setCellValue('B' . $j, $datos[$i]['Id Paciente']);

	$objPHPExcel->getActiveSheet()->setCellValue('C' . $j, $datos[$i]['Tipo Atención']);

	$objPHPExcel->getActiveSheet()->setCellValue('D' . $j, $infoNombre);

	$objPHPExcel->getActiveSheet()->setCellValue('E' . $j, $datos[$i]['RUT Paciente']);

	$objPHPExcel->getActiveSheet()->setCellValue('F' . $j, $datos[$i]['Edad Paciente']);

	$objPHPExcel->getActiveSheet()->setCellValue('G' . $j, "- ".$tratamiento);

	$objPHPExcel->getActiveSheet()->setCellValue('H' . $j, $datos[$i]['Fecha Admisión']);

	$objPHPExcel->getActiveSheet()->setCellValue('I' . $j, $datos[$i]['Fecha Solicitud Indicación']);

	$objPHPExcel->getActiveSheet()->setCellValue('J' . $j, $datos[$i]['Usuario Inserta Solicitud']);

	$objPHPExcel->getActiveSheet()->setCellValue('K' . $j, $datos[$i]['Fecha Inicio Indicación']);

	$objPHPExcel->getActiveSheet()->setCellValue('L' . $j, $datos[$i]['Usuario Inicia Indicación']);

	$objPHPExcel->getActiveSheet()->setCellValue('M' . $j, $datos[$i]['Fecha Aplica Indicación']);

	$objPHPExcel->getActiveSheet()->setCellValue('N' . $j, $datos[$i]['Usuario Aplica Indicación']);

	$objPHPExcel->getActiveSheet()->setCellValue('O' . $j, $datos[$i]['CIE10']);

}



/*
**************************************************************************
							Salida excel
**************************************************************************
*/
$objPHPExcel->getActiveSheet()->setTitle('Excel Registro EV CAT ESI-4');

$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.ms-excel');

header('Content-Disposition: attachment;filename="excel_endovenoso_cat4_"'.$parametros["fechaInicio"].'"_"'.$parametros["fechaTermino"].'".xls"');

header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');

?>