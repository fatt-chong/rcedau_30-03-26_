<?php
ini_set('memory_limit', '512M');
set_time_limit(0);
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// error_reporting(0);
// Asegúrate de que el autoloader de Spout esté incluido correctamente
require_once __DIR__ . '/../../../../estandar/spout-master/src/Spout/Autoloader/autoload.php';
// require_once 'C:/inetpub/wwwroot/php8site/RCEDAU/spout-master/src/Spout/Autoloader/autoload.php';
// C:\inetpub\wwwroot\php8site\RCEDAU\class\Psr4Autoloader.php
require_once "C:/inetpub/wwwroot/php8site/RCEDAU/class/Psr4Autoloader.php";

$loader = new \Autoloader\Psr4Autoloader;
$loader->register();
$loader->addNamespace('Box\Spout', 'vendor/box/spout/src/Spout');

use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
// $writer = WriterEntityFactory::createXLSXWriter();
$writer = WriterFactory::create(WriterType::XLSX);
$writer->openToFile('C:\inetpub\wwwroot\php8site\reports\my_report.xlsx');
// $writer = WriterEntityFactory::createODSWriter();
// $writer = WriterEntityFactory::createCSVWriter();
$writer->openToFile('archivo.xls'); // write data to a file or to a PHP stream
//$writer->openToBrowser($fileName); // stream data directly to the browser
$cells = [
    WriterEntityFactory::createCell('Carl'),
    WriterEntityFactory::createCell('is'),
    WriterEntityFactory::createCell('great!'),
];
/** add a row at a time */
$singleRow = WriterEntityFactory::createRow($cells);
$writer->addRow($singleRow);
/** add multiple rows at a time */
$multipleRows = [
    WriterEntityFactory::createRow($cells),
    WriterEntityFactory::createRow($cells),
];
$writer->addRows($multipleRows); 
/** Shortcut: add a row from an array of values */
$values = ['Carl', 'is', 'great!'];
$rowFromValues = WriterEntityFactory::createRowFromArray($values);
$writer->addRow($rowFromValues);

$writer->close();
?>