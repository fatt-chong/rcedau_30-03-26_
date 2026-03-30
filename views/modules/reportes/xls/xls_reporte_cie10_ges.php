<?php
    set_time_limit(0);
    error_reporting(0);
    session_start();
    require('../../../../config/config.php');
    require('../../sesion_expirada.php');
    require_once('../../../../class/Connection.class.php');                        $objCon           = new Connection; $objCon->db_connect();
    require_once("../../../../class/Util.class.php");                              $objUtil          = new Util;

    require_once("../../../../class/Dau.class.php");                               $objDau              = new Dau;
    require_once('../../../../../estandar/PHPExcel8/Classes/PHPExcel.php');   $objPHPExcel      = new PHPExcel();
    
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

$fecha = explode("-", $_GET['frm_mes']);
$headers = array(
    'ID Registro', 'DAU ID', 'CIE10 Diagnóstico', 'Edad Paciente', 'Fecha Alta Urgencia', 'Nombre Paciente', 'Nombre Completo CIE10'
);

$sheet          = $objPHPExcel->setActiveSheetIndex(0);
$rowNumber      = 1;
$col            = 0;
foreach ($headers as $header) {
    $sheet->setCellValueByColumnAndRow($col, $rowNumber, $header);
    $col++;
}
$styleArray = array(
    'font' => array(
        'size'  => 9,
        'name'  => 'Arial'
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'C8D5E4')
    ),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('rgb' => 'FFFFFF')
        )
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
    )
);
$sheet->getStyle("A1:H1")->applyFromArray($styleArray);
$parametros['frm_mes']  = $_GET['frm_mes'];
$data                   = $objDau->SelectGesReporte($objCon,$parametros);


$rowNumber = 2; 
foreach ($data as $row) {
    $col = 0;
    foreach ($row as $key => $value) {
        $sheet->setCellValueByColumnAndRow($col, $rowNumber, $value);
        $col++;
    }
    $rowNumber++;
}


foreach (range('A', $sheet->getHighestDataColumn()) as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$objPHPExcel->getActiveSheet()->setTitle('EXTENDIDA');
$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>