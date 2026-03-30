<?php
session_start();
error_reporting(0);
ini_set('max_execution_time', 600);
ini_set('memory_limit', '128M');
require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php');  	$objCon 			= new Connection; 		$objCon->db_connect();
require_once('../../../../class/Laboratorio.class.php'); 	$objLaboratorio	 	= new Laboratorio; 
require_once('../../../../class/Util.class.php'); 			$objUtil 			= new Util; 


$pacienteComplejo 		= $_SESSION['datosPacienteDau']['dau_paciente_complejo'];
$parametros['aLab'] 	= json_decode(stripslashes($_SESSION['indicaciones']['laboratorio'] ?? "[]"), true);

$categorias = [
    "Químicos" => 1,
    "Hormonas" => 2,
    "Hematológicos" => 3,
    "Orina" => 4,
    "Inmunológicos" => 5,
    "Bacteriológicos" => 7,
    "Gases" => 10,
    "Deposiciones" => 11,
    "Líquidos" => 12,
    "Solicitudes" => 13
];


$datosLaboratorio = [];
foreach ($categorias as $categoria => $codigo) {
    $datosLaboratorio[$categoria] = $pacienteComplejo === 'S'
        ? $objLaboratorio->listarPrestaciones($objCon, $codigo)
        : $objLaboratorio->listarPrestaciones_urg($objCon, $codigo);
}

$version = $objUtil->versionJS();


function renderCategoria($categoria, $datos, $seleccionados) {
    if (!is_array($datos) || empty($datos)) {
        return;
    }
    echo "<div class='col-md-4'>";
    echo "<div class='panel panel-default border mb-2'>";
    echo "<div class='card-header text-center' style='background-color: rgb(184 218 255);border-bottom: 1px solid rgb(184 218 255);font-weight: 500;color: #25748e;font-size: 14px; padding: .25rem 1.25rem !important'>{$categoria}</div>";
    echo "<div class='panel-body mifuente ml-3 mr-3 mb-2 examenesNormales'>";
    foreach ($datos as $examen) {
        $checked = in_array($examen['pre_codOmega'], array_column($seleccionados, '0')) ? "checked" : "";
        echo "<label style='margin-bottom: -4px; font-weight: normal;'>";
        echo "<input class='{$examen['pre_pacienteUrgencia']} checkPruebaComplejo' style='margin-right: 5px;' type='checkbox' name='frm_laboratorio' value='{$examen['pre_codOmega']}' {$checked}>";
        echo htmlspecialchars($examen['pre_examen']);
        echo "<input type='hidden' id='lab{$examen['pre_codOmega']}' value='" . htmlspecialchars($examen['pre_examen']) . "'>";
        echo "</label><br>";
    }
    echo "</div></div>";
    echo "</div>";
}


function renderCategoriaTuberculosis($categoria, $datos, $seleccionados) {
    if (!is_array($datos) || empty($datos)) {
        return;
    }
    echo "<div class='col-md-4'>";
    echo "<div class='panel panel-default border mb-2'>";
    echo "<div class='card-header text-center' style='background-color: rgb(184 218 255);border-bottom: 1px solid rgb(184 218 255);font-weight: 500;color: #25748e;font-size: 14px; padding: .25rem 1.25rem !important'>Solicitudes</div>";
    echo "<div class='panel-body mifuente ml-3 mr-3 mb-2 '>";
    // $checked = in_array($examen['pre_codOmega'], array_column($seleccionados, '0')) ? "checked" : "";
    echo "<label style='margin-bottom: -4px; font-weight: normal;'>";
    echo "<input class=' ' style='margin-right: 5px;' type='checkbox' name='frm_laboratorio' value='11' {$checked}>";
    echo "INVESTIGACIÓN BACTERIOLÓGICA DE TUBERCULOSIS";
    echo "<input type='hidden' id='11' value='INVESTIGACIÓN BACTERIOLÓGICA DE TUBERCULOSIS'>";
    echo "</label><br>";

    echo "</div></div>";
    echo "</div>";
}
?>

<script type="text/javascript" src="<?=PATH?>/controllers/client/rce/indicaciones/laboratorio.js?v=<?=$version;?>"></script>
<br>
<div id="complejo" <?= $pacienteComplejo !== "S" ? "hidden" : "" ?>>
    <form id="frm_laboratorio_master" name="frm_laboratorio_master">
        <div class="row">
            <?php
            foreach ($categorias as $categoria => $codigo) {
                // echo "<div class='col-md-4'>";
                renderCategoria($categoria, $datosLaboratorio[$categoria] ?? [], $parametros['aLab']);
                // echo "</div>";
            }
            ?>
        </div>
    </form>
</div>
<div id="urgencia" <?= $pacienteComplejo === "S" ? "hidden" : "" ?>>
    <form id="frm_laboratorio_master2" name="frm_laboratorio_master2">
        <div class="row">
            <?php
            foreach ($categorias as $categoria => $codigo) {
                // echo "<div class='col-md-4'>";
                renderCategoria($categoria, $datosLaboratorio[$categoria] ?? [], $parametros['aLab']);
                // echo "</div>";
            }
            ?>
        </div>
    </form>
</div>
<!-- <div class="row">
	<div class="col-md-4">
		<div class="panel panel-default border mb-2"><div class="card-header text-center" style="background-color: rgb(184 218 255);border-bottom: 1px solid rgb(184 218 255);font-weight: 500;color: #25748e;font-size: 14px; padding: .25rem 1.25rem !important">Solicitudes</div>
		<div id="divInv" class="panel-body mifuente ml-3 mr-3 mb-2 ">
			<label id="hiddenInv" style="margin-bottom: -4px; font-weight: normal;"><input  style="margin-right: 5px;" type="checkbox" name="frm_laboratorio" value="11" <?php echo $checked; ?> >INVESTIGACIÓN BACTERIOLÓGICA DE TUBERCULOSIS
				<input type="hidden" id="11" value="INVESTIGACIÓN BACTERIOLÓGICA DE TUBERCULOSIS">
			</label><br>
			</div>
		</div>
	</div>
</div> -->