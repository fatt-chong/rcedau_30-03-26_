<?php
error_reporting(0);
session_start();
require("../../../config/config.php");
require_once("../../../class/Connection.class.php");      $objCon         = new Connection();
require_once("../../../class/Util.class.php");            $objUtil        = new Util;
require_once("../../../class/Parametros.class.php");      $objParametros  = new Parametros();
require_once("../../../class/TurnoCRUrgencia.class.php"); $objTurno       = new TurnoCRUrgencia();
require_once("../../../class/Pizarra.class.php");         $objPizarra     = new Pizarra();

$objCon->db_connect();
$parametros['tipo_parametros']  = 2;
$rsDotacion                     = $objParametros->getParametros($objCon,$parametros);
$parametros['tipo_entrega']     = 2;
$tipoHorarioTurno               = $objTurno->obtenerTipoHorarioTurnoParametros($objCon,$parametros);
$rsServer                       = $objUtil->getHorarioServidor($objCon);
$version                        = $objUtil->versionJS();

if($_POST){
  $parametrosPizarra['idTipoHorarioTurno'] = $_POST['horarioPizarra'];
}else{
  $_POST['horarioPizarra']                 =  $tipoHorarioTurno[0]['idTipoHorarioTurno'];
  $parametrosPizarra['idTipoHorarioTurno'] = $tipoHorarioTurno[0]['idTipoHorarioTurno'];
}
// $parametrosPizarra['fecha_crea']           = $rsServer[0]['fecha'];
if($_POST['frm_fecha_pizarra'] == null){
  $parametrosPizarra['fecha_crea']           = $rsServer[0]['fecha'];
}else{
  $parametrosPizarra['fecha_crea']           = $_POST['frm_fecha_pizarra'];
}
$parametrosPizarra['id_pizarra']           = $_POST['id_pizarra'];
// print('<pre>'); print_r($parametrosPizarra); print('</pre>');
$rsPizarra = $objPizarra->SelectPizarraDetalle($objCon,$parametrosPizarra);
// print('<pre>'); print_r($rsPizarra); print('</pre>');
?>

<?php
$secciones = [];
foreach ($rsPizarra as $row) {
    $sec = trim($row['seccion_nombre'] ?? '');
    if ($sec === '') { $sec = 'Sin sección'; }
    $secciones[$sec][] = [
        'rol' => trim($row['rol'] ?? ''),
        'nombre' => trim($row['nombre_profesional'] ?? ''),
    ];
}
$bloques = [];
foreach ($secciones as $secNombre => $items) {
    $bloques[] = ['titulo' => $secNombre, 'items' => $items];
}
$mitad = (int)ceil(count($bloques) / 2);
$colLeft  = array_slice($bloques, 0, $mitad);
$colRight = array_slice($bloques, $mitad);
?>
  <style>
    body{background:#f2f5f7}
    .board{
      background:#fff;border:2px solid #cfd6dc;border-radius:8px;
      box-shadow:0 2px 10px rgba(0,0,0,.06); padding:18px 16px;
    }
    .board-date{
      display:inline-block; padding:.15rem .6rem; border:2px solid #222; border-radius:6px;
      font-weight:700; letter-spacing:.5px;
    }
    .section{
      margin-bottom:1.1rem; padding-bottom:.5rem; border-bottom:2px solid #1e1e1e20;
    }
    .section-title{
      text-transform:uppercase; font-weight:800; letter-spacing:.4px; margin:0 0 .25rem 0;
      border-bottom:2px solid #1e1e1e; display:inline-block; padding-bottom:2px;
      font-size:1rem; /* parecido a la pizarra */
    }
    .names{margin:0; padding-left:1rem}
    .names li{
      list-style-type:disc; margin:.1rem 0; line-height:1.25; font-size:.98rem;
    }
    .names .rol{font-weight:700}
    /* espaciado similar a marcadores en pizarra */
    .tight li{ margin:.05rem 0; }
  </style>
<div class="container my-3">
  <?php if ( array_search(1773, $_SESSION['permiso'.SessionName]) != null ) { ?>
    <button type="button" class="btn btn-sm mifuente btn-danger btn-block" id="btnEliminarPizarra">
      <i class="fas fa-shield-alt"></i> <i class="fas fa-eraser"></i> Eliminar Pizarra
    </button>
    <input type="hidden" name="id_pizarra" id="id_pizarra" value="<?=$rsPizarra[0]['id_pizarra'];?>">
  <?php } ?>
  <div class="board">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h5 class="mb-0"><span class="board-date">FECHA: <?= $objUtil->fechaInvertida($parametrosPizarra['fecha_crea']) ?></span></h5>
      <!-- Puedes poner flores o insignias aquí si quieres -->
    </div>
    <div class="row g-3">
      <!-- Columna izquierda -->
      <div class="col-12 col-lg-6">
        <?php foreach ($colLeft as $bloque): ?>
          <div class="section">
            <h6 class="section-title"><?= htmlspecialchars($bloque['titulo']) ?></h6>
            <ul class="names tight">
              <?php foreach ($bloque['items'] as $it): ?>
                <li>
                  <?php if ($it['rol'] !== ''): ?>
                    <span class="rol"><?= htmlspecialchars($it['rol']) ?>:</span>
                  <?php endif; ?>
                  <?= htmlspecialchars($it['nombre']) ?>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endforeach; ?>
      </div>
      <!-- Columna derecha -->
      <div class="col-12 col-lg-6">
        <?php foreach ($colRight as $bloque): ?>
          <div class="section">
            <h6 class="section-title"><?= htmlspecialchars($bloque['titulo']) ?></h6>
            <ul class="names tight">
              <?php foreach ($bloque['items'] as $it): ?>
                <li>
                  <?php if ($it['rol'] !== ''): ?>
                    <span class="rol"><?= htmlspecialchars($it['rol']) ?>:</span>
                  <?php endif; ?>
                  <?= htmlspecialchars($it['nombre']) ?>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
<script>
$('#btnEliminarPizarra').on('click', function(){
    var id_pizarra = $('#id_pizarra').val(); 
    respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/enfermera/main_controller.php`,'id_pizarra='+id_pizarra+'&accion=EliminarPizarra', 'POST','JSON', 1, '' );
    if(respuestaAjaxRequest.status == 'success'){
      ajaxContent(`${raiz}/views/modules/turnoCRUrgencia/pizarraEnfermeria.php`, $('#frm_generadorPizarra').serialize(), '#contenido', 'Cargando...', true);
    }

    });
</script>