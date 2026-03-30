<?php
session_start();
require("../../../config/config.php");
require_once("../sesion_expirada.php");

?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>

<script type="text/javascript" src="<?=RAIZ?>/controllers/client/reportes/reporte_cie10_ges.js?v=<?=round(microtime(true) * 1000);?>"></script>
<div class="m-3">
    <div class="row">
        <label class="text-secondary ml-3"><i class="fas fa-file   mifuente20" style="color: #59a9ff;"></i> Reporte Pacientes GES</label>
        <div class=" col-lg-12 dropdown-divider   mt-2 mb-4 " ></div>
    </div>
    <div class="row ">
        <div class=" col-md-2 ">
            <input id="frm_mes" name="frm_mes" class="form-control form-control-sm text-center BlockDeletion col-12 mifuente frm_mes" type="text" placeholder="Indique Mes" aria-label=".form-control-sm example" >
        </div><div class=" col-md-2 ">
            <button id="btn_generarReporte" type="button" class="btn btn-outline-primary btn-sm mifuente enviarfiltro me-4" alt="Buscar" title="Buscar"> <i class="mr-2 fas fa-search"></i> Generar Reporte</button>
        </div>
    </div>
    <div id="contenidoReporte" class="mt-4" >
    </div>
</div>