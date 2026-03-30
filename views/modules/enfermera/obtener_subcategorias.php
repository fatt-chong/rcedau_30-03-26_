<?php
require_once("../../../config/config.php");
require_once('../../../class/Connection.class.php');                   
require_once('../../../class/Subcategorias_procedimiento.class.php'); 

$objCon = new Connection;
$objCon->db_connect();
$objSub = new Subcategorias_procedimiento;

$procedimiento_id = $_POST['procedimiento_id'] ?? 0;
$subcategorias = $objSub->SelectByProcedimiento($objCon, $procedimiento_id);

echo '<option value="">Seleccione...</option>';
foreach ($subcategorias as $sub) {
    echo '<option value="'.$sub['id'].'">'.htmlspecialchars($sub['nombre']).'</option>';
}