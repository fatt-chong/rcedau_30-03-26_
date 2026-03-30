<?php

// Include the main TCPDF library (search for installation path).
require("../../../../config/config.php");
require_once('../../../../../estandar/TCPDF-main/tcpdf.php');
require_once("../../../../class/Upload.class.php");							$objUpload 					= new Upload(FTP_IP, FTP_USUARIO, FTP_CLAVE);


// Crear un nuevo objeto TCPDF
$pdf = new TCPDF();

// Establecer información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Tu Nombre');
$pdf->SetTitle('Título del Documento');
$pdf->SetSubject('Tema del Documento');
$pdf->SetKeywords('TCPDF, PHP, PDF');

// Añadir una página
$pdf->AddPage();

// Establecer contenido
$pdf->SetFont('helvetica', '', 12);
$pdf->Write(0, '¡Hola, este es un PDF generado con TCPDF!');

// Output (mostrar el PDF en el navegador)
// $pdf->Output('ejemplo.pdf', 'FI');
// C:\inetpub\wwwroot\php8site\RCEDAU\views\modules\rce\rce\detalle_rce.php
$pdf->Output('C:/inetpub/wwwroot/php8site/RCEDAU/views/modules/rce/rce/ejemplo.pdf', 'FI');
// $pdf->Output('C:/inetpub/wwwroot/php8site/RCEDAU/views/modules/rce/rce/ejemplo.pdf', 'FI');

$archivo['nombreArchivo'] = "ejemplo.pdf";
// $archivo['fechaArchivo'] = $datosDau[0]['dau_indicacion_egreso_fecha'];
$archivo['fechaArchivo'] = "2222-02-02";
// print('<pre>'); print_r($archivo); print('</pre>');
list($anio,$mes,$dia)=explode("-",$archivo['fechaArchivo']);
	$parametros['directorio']     = "pruebaPHP8/".$anio."/".$mes."/";
	$parametros['nombre_archivo'] = $archivo['nombreArchivo'];
	$parametros['mode']           = FTP_BINARY;

$objUpload->subirArchivoFTP($parametros);
?>