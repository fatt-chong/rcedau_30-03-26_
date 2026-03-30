<?php
error_reporting(0);
session_start();
require("../../../config/config.php");
require_once("../../../class/Connection.class.php");         $objCon       = new Connection();
require_once("../../../class/Util.class.php"); 		           $objUtil      = new Util;
require_once("../../../class/TurnoCRUrgencia.class.php"); 	 $objTurno     = new TurnoCRUrgencia();
require_once("../../../class/Pizarra.class.php");            $objPizarra   = new Pizarra();

$objCon->db_connect();
// Determina tipo de entrega: 1 = Médica, 2 = Enfermería
if (isset($_POST['chk_enfermeria']) && $_POST['chk_enfermeria'] === 'S') {
    $parametros['tipo_entrega'] = 2;
    $chk_enfermeria_value = 'S';
    $chk_enfermeria_cheked = 'checked';
} else {
    $parametros['tipo_entrega'] = 1;
    $chk_enfermeria_value = 'N';
    $chk_enfermeria_cheked = '';
}
$tipoHorarioTurno = $objTurno->obtenerTipoHorarioTurnoParametros($objCon,$parametros);
$rsServer         = $objUtil->getHorarioServidor($objCon);

if($_POST['frm_fecha_pizarra'] == null){
  $parametrosPizarra['fecha_crea']           = $rsServer[0]['fecha'];
}else{
  $parametrosPizarra['fecha_crea']           = $_POST['frm_fecha_pizarra'];
}
$hoy      = $parametrosPizarra['fecha_crea'];
$ayer     = date('Y-m-d', strtotime('-1 day'));
$manana   = date('Y-m-d');
// $parametrosPizarra['fecha_crea']  = $rsServer[0]['fecha']; 

$rsPizarra                        = $objPizarra->SelectPizarra($objCon,$parametrosPizarra);
$arrayMedico      = [];
  $arrayTens        = [];
  $arrayEnfermero   = [];
  $arrayCirujano    = [];
if($_POST['id_pizarra'] > 0){
  $rsPizarraDetalle                        = $objPizarra->SelectPizarraDetalle($objCon,$parametrosPizarra);
  // $arrayMedico      = [];
  // $arrayTens        = [];
  // $arrayEnfermero   = [];
  // $arrayCirujano    = [];
  // Recorres el resultado de la consulta
  foreach ($rsPizarraDetalle as $fila) {
      switch ($fila['rol']) {
          case 'MÉDICO':
              $arrayMedico[] = $fila;
              break;
          case 'TENS':
              $arrayTens[] = $fila;
              break;
          case 'EU':
              $arrayEnfermero[] = $fila;
              break;
          case 'CIRUJANO':
              $arrayCirujano[] = $fila;
              break;
      }
  }
}
$version          = $objUtil->versionJS();



?>

<!--
################################################################################################################################################
                                                       			    CARGA JS
-->
<script type="text/javascript" src="<?=PATH?>/controllers/client/turnoCRUrgencia/turnoCRUrgencia.js?v=<?=$version;?>1"></script>


<style type="text/css">
    /* ====== Cards suaves y sombras ligeras ====== */
.card-soft {
  border: 1px solid rgba(0,0,0,.06);
  border-radius: 14px;
  box-shadow: 0 2px 10px rgba(0,0,0,.04);
  background: #fff;
}

.card-soft .card-header {
  border-bottom: 1px dashed rgba(0,0,0,.08);
  background: linear-gradient(180deg, rgba(0,0,0,.02), transparent);
  font-weight: 600;
}

/* ====== Segmented control (Sí/No) ====== */
.segmented {
  display: inline-flex; border: 1px solid rgba(0,0,0,.12);
  border-radius: 999px; overflow: hidden;
  background: #fff;
}
.segmented .seg-item {
  padding: .25rem .9rem; cursor: pointer; user-select: none;
  font-size: .9rem;
}
.segmented input { display:none; }
.segmented input:checked + .seg-item {
  background: var(--bs-primary); color:#fff;
}

/* ====== Lista de personas ====== */
.person-list { border: 1px solid rgba(0,0,0,.08); border-radius: 12px; }
.person-item {
  display:flex; align-items:center; gap:.75rem; padding:.6rem .75rem;
  border-bottom:1px solid rgba(0,0,0,.06); background:#fff;
}
.person-item:last-child{ border-bottom:none; }
.person-avatar {
  width:34px; height:34px; border-radius:50%;
  display:grid; place-items:center;
  background: #eef6ff; color:#2b6cb0; /* azul suave */
  box-shadow: inset 0 0 0 1px rgba(0,0,0,.05);
  font-size: .95rem;
}
.person-body { line-height:1.15; }
.person-name { font-weight:700; font-size:.95rem; }
.person-rut  { font-size:.8rem; color:#6c757d; }
.person-chip {
  font-size:.75rem; padding:.15rem .45rem; border-radius:999px;
  background:#f5f7fa; border:1px solid rgba(0,0,0,.06);
}
.person-actions .btn {
  --bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size:.8rem;
}

/* ====== Input group: botón + más compacto ====== */
.input-group-sm .btn-icon {
  width:34px; display:grid; place-items:center;
  border-top-left-radius:0; border-bottom-left-radius:0;
}

/* ====== Textareas y contadores ====== */
.section-title { font-weight:600; margin-bottom:.25rem; }
.counter { font-size:.8rem; color:#6c757d; }

/* Hover suave */
.person-item:hover { background:#fbfbfd; }

/* Dark mode amistoso (opcional) */
@media (prefers-color-scheme: dark){
  .card-soft { background:#111418; border-color:#1e2227; }
  .card-soft .card-header { background:linear-gradient(180deg, rgba(255,255,255,.02), transparent); }
  .person-list { border-color:#1e2227; }
  .person-item { background:#111418; border-bottom-color:#1e2227; }
  .person-rut,.counter { color:#9aa4af; }
  .person-chip { background:#151a20; border-color:#222833; color:#cfd6df; }
}
.input-error {
  border: 2px solid #ff9090 !important;
  border-radius: 4px;
}

</style>
<!--
################################################################################################################################################
                                                       			 DESPLIGUE TÍTULO
-->

<form id="frm_despliegueParametrosTurno" name="frm_despliegueParametrosTurno" class="formularios" role="form" method="POST">
  <div class="row mt-1">
      <div class="col-lg-4">
          <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i> Crear Resumen Turno CR Urgencia - Médica</b></h6>
      </div>
      <div class="col-md-2 form-group has-feedback">
        <div class="input-group shadow">
          <div class="input-group-prepend">
            <div class="input-group-text mifuente12" id="btnGroupAddonfrm_dau"><i class="fas fa-calendar darkcolor-barra2"></i></div>
          </div>
          <input id="frm_fechaActualTurno" type="date" onDrop="return false" class="form-control form-control-sm text-center mifuente12" name="frm_fechaActualTurno" placeholder="Fecha Actual" value="<?= $hoy ?>"
    min="<?= $ayer ?>"
    max="<?= $manana ?>" aria-describedby="btnGroupAddonfrm_dau" >
        </div>
      </div>
      <div class="col-2 form-group has-feedback">
        <div class="input-group shadow">
          <div class="input-group-prepend">
            <div class="input-group-text mifuente12" id="btnGroupAddonfrm_dau"><i class="fas fa-clock darkcolor-barra2"></i></div>
          </div>
          <input id="frm_horaActualTurno" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_horaActualTurno" placeholder="Hora Actual" value="<?php echo $rsServer[0]['hora'];?>" aria-describedby="btnGroupAddonfrm_dau" readonly>
        </div>
      </div>
      </div>
      <div class="row mt-1">

      <!-- <div class="col-1 form-group has-feedback text-right "> -->
        <!-- <label class="mifuente13"> Buscar Pizarra</label> -->
      <!-- </div> -->
      <div class="col-md-4 ">
      <div class="input-group shadow">
        <div class="input-group-prepend">
          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_dau"><b>Buscar Pizarra</b> <i class="ml-2 fas fa-calendar darkcolor-barra2"></i></div>
        </div>
        <input
    id="frm_fecha_pizarra"
    type="date"
    class="form-control form-control-sm text-center mifuente12"
    name="frm_fecha_pizarra"
    value="<?= $hoy ?>"
    min="<?= $ayer ?>"
    max="<?= $manana ?>" >

      </div>
    </div>
      <?php foreach ($rsPizarra as $row) { ?>
      <div class="col-2 form-group has-feedback">
        <button type="button" id="<?=$row['id_pizarra']?>" class="btn col-lg-12 btn-sm btn-outline-danger verPizarra mifuente"><i class="fas fa-chalkboard-teacher mr-1"></i><?=$row['descripcionHorarioTurno']?></button>
      </div>
      <?php } ?>


      <div  class="form-group col-lg-2" hidden>
          <div class="form-check mt-2">
              <input type="hidden" id="frm_chk_enfermeria" name="chk_enfermeria" value="N">
              <input class="form-check-input" type="checkbox" id="chk_enfermeria" <?=$chk_enfermeria_cheked;?> >
              <label class="form-check-label mifuente12" for="chk_enfermeria">Enfermería</label>
          </div>
      </div>
  </div>

<div id='divDespliegueParametrosTurno'>
        <div class="row m-1 border p-3">
            <div class="col-md-4 form-group has-feedback">
              <div class="input-group shadow">
                <div class="input-group-prepend">
                  <div class="input-group-text mifuente12" id="btnGroupAddonDocumento"><i class="fas fa-bars darkcolor-barra2"></i></div>
                </div>
                <?php $totalTipoHorarioTurno = count($tipoHorarioTurno); ?>
                <select class="form-control form-control-sm mifuente1" name="frm_tipoHorarioTurno" id="frm_tipoHorarioTurno">
                  <?php if( $_POST['chk_enfermeria'] === 'S') { 
                    for ( $i = 0; $i < count($tipoHorarioTurno); $i++ ) { ?>
                      <option value="<?php echo $tipoHorarioTurno[$i]['idTipoHorarioTurno']; ?>" ><?php echo $tipoHorarioTurno[$i]['descripcionHorarioTurno']; ?></option>
                  <?php }
                  } else{ ?>
                    <option value="" selected disabled>Seleccione Tipo Turno</option>
                    <?php echo selectedTipoHorarioTurno($tipoHorarioTurno); ?>
                    <option value="<?php echo $tipoHorarioTurno[7]['idTipoHorarioTurno']; ?>" ><?php echo $tipoHorarioTurno[7]['descripcionHorarioTurno']; ?></option>
                    <option value="<?php echo $tipoHorarioTurno[8]['idTipoHorarioTurno']; ?>" ><?php echo $tipoHorarioTurno[8]['descripcionHorarioTurno']; ?></option>

                  <?php }  ?>
                </select>
              </div>
            </div>
            <div class="col-md-4 form-group has-feedback accionProfesionalTurno" id="profesionalEntregaTurno">
              <div class="input-group shadow">
                <div class="input-group-prepend">
                  <div class="input-group-text mifuente12" id="btnGroupAddonfrm_dau"><i class="fas fa-user darkcolor-barra2"></i></div>
                </div>
                <input id="frm_profesionalEntregaTurno" type="text"  class="form-control form-control-sm mifuente12 profesionalTurno" name="frm_profesionalEntregaTurno" placeholder="Profesional Entrega Turno"  value="<?=$_SESSION['MM_UsernameName'.SessionName]?>" >
                <input id="frm_idProfesionalEntregaTurno" type="hidden" class="form-control idProfesionalTurno" name="frm_idProfesionalEntregaTurno" value="<?=$_SESSION['MM_RUNUSU'.SessionName]?>">
                <!-- <input id="frm_idProfesionalEntregaTurno" type="hidden" class="form-control idProfesionalTurno" name="frm_idProfesionalEntregaTurno" value="16225552"> -->
              </div>
            </div>
            <div class="col-md-4 form-group has-feedback accionProfesionalTurno" id="profesionalRecibeTurno">
                <div class="input-group shadow">
                    <div class="input-group-prepend">
                        <div class="input-group-text mifuente12" id="btnGroupAddonfrm_dau"><i class="fas fa-user darkcolor-barra2"></i></div>
                    </div>
                    <input id="frm_profesionalRecibeTurno" type="text" onDrop="return false" class="form-control form-control-sm mifuente12 profesionalTurno" name="frm_profesionalRecibeTurno" placeholder="Profesional Recibe Turno"  aria-describedby="btnGroupAddonfrm_dau" >
                    <input id="frm_idProfesionalRecibeTurno" type="hidden" class="form-control idProfesionalTurno" name="frm_idProfesionalRecibeTurno" >
                </div>
            </div>
            <div class="col-md-12 mb-2">
                <label class="mifuente12">Nombre Jefe Turno Médico</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="medico_jef_turno" id="medico_jef_turno" class="form-control form-control-sm mifuente11">
                    <input type="hidden" name="medico_jef_turno_rut" id="medico_jef_turno_rut" >
                </div>
            </div>
            <div class="col-md-12 mb-2" id="bloque_jefe_enfermeria" style="display:none;">
                <label class="mifuente12">Nombre Jefe Turno Enfermería</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="enf_jef_turno" id="enf_jef_turno" class="form-control form-control-sm mifuente11">
                    <input type="hidden" name="enf_jef_turno_rut" id="enf_jef_turno_rut" >
                </div>
            </div>
            <div class="card-soft p-3 mb-3 col-md-12 ">
                <div class="card-header mb-3 mifuente12">
                    <i class="fas fa-user-graduate me-2"></i> Equipo Médico
                </div>

                <div class="row g-3">
                <!-- Residente -->
                    <div class="col-md-6">
                    <label class="mifuente12">Agregar Médico</label>
                    <div class="input-group input-group-sm shadow-sm">
                    <input type="text" id="input_residente" class="form-control form-control-sm mifuente11" placeholder="Buscar médico ...">
                    <button type="button" id="btn_add_residente" class="btn btn-success "><i class="fas fa-plus"></i></button>
                    </div>
                    <small class="text-muted">Escribe al menos 3 letras y selecciona desde el autocompletar.</small>
                    <div class="mt-2 person-list" id="list_residentes">
                    </div>
                    <div id="hidden_residentes"> 
                    </div>
                    </div>

                    <!-- Cirujano -->
                    <div class="col-md-6">
                    <label class="mifuente12">Agregar Cirujano</label>
                    <div class="input-group input-group-sm shadow-sm">
                    <input type="text" id="input_cirujano" class="form-control form-control-sm mifuente11" placeholder="Buscar cirujano...">
                    <button type="button" id="btn_add_cirujano" class="btn btn-success "><i class="fas fa-plus"></i></button>
                    </div>
                    <small class="text-muted">Escribe al menos 3 letras y selecciona desde el autocompletar.</small>
                    <div class="mt-2 person-list" id="list_cirujanos"></div>
                    <div id="hidden_cirujanos"></div>
                    </div>
                </div>

                <div id="bloque_enfermeria" class="row g-3 mt-1" style="display:none;">
                    <!-- TENS -->
                    <div class="col-md-6">
                        <label class="mifuente12">Agregar TENS</label>
                        <div class="input-group input-group-sm shadow-sm">
                            <input type="text" id="input_tens" class="form-control form-control-sm mifuente11" placeholder="Buscar TENS...">
                            <button type="button" id="btn_add_tens" class="btn btn-success "><i class="fas fa-plus"></i></button>
                        </div>
                        <small class="text-muted">Escribe al menos 3 letras y selecciona desde el autocompletar.</small>
                        <div class="mt-2 person-list" id="list_tens"></div>
                        <div id="hidden_tens"></div>
                    </div>

                    <!-- Enfermeros -->
                    <div class="col-md-6">
                        <label class="mifuente12">Agregar Enfermero(a)</label>
                        <div class="input-group input-group-sm shadow-sm">
                            <input type="text" id="input_enfermero" class="form-control form-control-sm mifuente11" placeholder="Buscar enfermero(a)...">
                            <button type="button" id="btn_add_enfermero" class="btn btn-success "><i class="fas fa-plus"></i></button>
                        </div>
                        <small class="text-muted">Escribe al menos 3 letras y selecciona desde el autocompletar.</small>
                        <div class="mt-2 person-list" id="list_enfermeros"></div>
                        <div id="hidden_enfermeros"></div>
                    </div>
                </div>
            </div>

            <div class="card-soft p-3 mb-3 col-md-12 ">
                <div class="card-header mb-3 mifuente12">
                    <i class="fas fa-clipboard-check me-2"></i> Entrega y Recursos
                </div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="mifuente12 d-block mb-1">Entrega conforme</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="entrega_conforme" id="entrega_si" value="S" checked>
                            <label class="form-check-label mifuente11" for="entrega_si">Sí</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="entrega_conforme" id="entrega_no" value="N">
                            <label class="form-check-label mifuente11" for="entrega_no">No</label>
                        </div>
                        <div class="mt-2" id="box_entrega_no" style="display:none;">
                            <textarea class="form-control form-control-sm mifuente11" id="entrega_no_motivo" name="entrega_no_motivo" rows="4" maxlength="500" placeholder="Explique por qué no se entrega conforme..."></textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="mifuente12">BIC (0 - 10)</label>
                        <input type="number" min="0" max="10" step="1" class="form-control form-control-sm mifuente11" id="bic_cantidad" name="bic_cantidad" value="0">
                    </div>
                    <div class="col-md-3">
                        <label class="mifuente12 d-block mb-1">Ecógrafo disponible</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="ecografo_disponible" id="eco_si" value="S" checked>
                            <label class="form-check-label mifuente11" for="eco_si">Sí</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="ecografo_disponible" id="eco_no" value="N">
                            <label class="form-check-label mifuente11" for="eco_no">No</label>
                        </div>
                        <div class="mt-2" id="box_eco_no" style="display:none;">
                            <textarea class="form-control form-control-sm mifuente11" id="ecografo_no_motivo" name="ecografo_no_motivo" rows="4" maxlength="500" placeholder="Explique por qué no hay ecógrafo..."></textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="mifuente12">Cantidad de celulares (0 - 10)</label>
                        <input type="number" min="0" max="10" step="1" class="form-control form-control-sm mifuente11" id="celulares_cantidad" name="celulares_cantidad" value="0">
                    </div>
                </div>
            </div>

            <div class="card-soft p-3 col-md-12 ">
                <div class="card-header mb-3 mifuente12">
                    <i class="fas fa-clipboard-list me-2"></i> Novedades del turno
                </div>
                <div class="mb-2">
                    <label class="mifuente12 d-block mb-1">¿Hubo novedades?</label>

                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio"
                           name="novedades_turno_si_no" id="novedades_si" value="S">
                    <label class="form-check-label mifuente11" for="novedades_si">Sí</label>
                    </div>

                    <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio"
                           name="novedades_turno_si_no" id="novedades_no" value="N" checked>
                    <label class="form-check-label mifuente11" for="novedades_no">No</label>
                    </div>
                </div>
                <div id="box_novedades" class="mt-2" style="display:none;">
                    <div class="mb-2">
                      <div class="d-flex justify-content-between align-items-end">
                        <label for="novedades_general" class="section-title mifuente12">Novedades (general) <span class="text-danger">*</span></label>
                      </div>
                      <textarea class="form-control form-control-sm mifuente11" id="novedades_general" name="novedades_general" rows="3" maxlength="2000" placeholder="Describe brevemente las novedades del turno..."></textarea>
                    </div>

                    <div class="row g-3">
                      <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-end">
                          <label for="novedades_adm" class="section-title mifuente12">Novedades administrativas</label>
                        </div>
                        <textarea class="form-control form-control-sm mifuente11" id="novedades_adm" name="novedades_adm" rows="3" maxlength="1000" placeholder="Ej.: licencias, permisos, dotación, coordinaciones..."></textarea>
                      </div>

                      <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-end">
                          <label for="novedades_infra" class="section-title mifuente12">Novedades infraestructura</label>
                        </div>
                        <textarea class="form-control form-control-sm mifuente11" id="novedades_infra" name="novedades_infra" rows="3" maxlength="1000" placeholder="Ej.: salas, baños, luminarias, puertas, climatización..."></textarea>
                      </div>

                      <div class="col-md-4">
                        <div class="d-flex justify-content-between align-items-end">
                          <label for="novedades_equip" class="section-title mifuente12">Novedades equipamiento</label>
                        </div>
                        <textarea class="form-control form-control-sm mifuente11" id="novedades_equip" name="novedades_equip" rows="3" maxlength="1000" placeholder="Ej.: monitores, bombas, desfibriladores, PCs, impresoras..."></textarea>
                      </div>
                    </div>

                    <div class="mt-3">
                      <div class="d-flex justify-content-between align-items-end">
                        <label for="novedades_eventos" class="section-title mifuente12">Eventos adversos</label>
                      </div>
                      <textarea class="form-control form-control-sm mifuente11" id="novedades_eventos" name="novedades_eventos" rows="3" maxlength="1500" placeholder="Describe el evento, paciente afectado (si aplica), acciones inmediatas y notificación."></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="row">
    <div class="col-lg-4">
    </div>
    <div class="col-lg-4">
        <div class="d-grid">
        <button type="button" id="btnGuardarDatosTurno" name="btnGuardarDatosTurno" class="btn btn-danger shadow-sm rounded-pill   btn-sm mifuente  col-lg-12" >
          <i class="fas fa-flag-checkered me-2"></i>
          Generar cierre de turno
        </button>
        </div>
    </div>
</div>
<!-- <div  > -->
    <!-- Div Despliegue Hospitalizaciones -->
    <!-- <div id="divNumeroHospitalizaciones" class="row"> -->
        <?php
        // include('numeroHospitalizaciones.php');
        ?>
    <!-- </div> -->
    <!-- Div Despliegue Hospitalizaciones Urgencia -->
    <!-- <div id="divNumeroHospitalizacionesUrgencia" class="row"> -->
        <?php
        // include('numeroHospitalizacionesUrgencia.php');
        ?>

    <!-- </div> -->
    <!-- Div Despliegue Pacientes en Espera de Atención -->
    <!-- <div id="divNumeroPacientesEsperaAtencion" class="row"> -->
        <?php
        // include('numeroPacientesEsperaAtencion.php');
        ?>
    <!-- </div> -->
    <!-- Div Despliegue Pacientes con Solicitudes Especialistas -->
    <!-- <div id="divSolicitudesEspecialista" class="row"></div> -->

    <!-- Div Despliegue Cirugías Realizadas -->
    <!-- <div id="divCirugiasRealizadas" class="row"></div> -->

    <!-- Div Despliegue Tiempos Atención -->
    <!-- <div id="divTiemposAtencion" class="row"></div> -->

     <!-- Div Despliegue Tiempos Promedios -->
    <!-- <div id="divTiemposPromedio" class="row"></div> -->

<!-- </div> -->



<?php
function selectedTipoHorarioTurno ( $tipoHorarioTurno ) {
    $i = 0;
    switch ( date('N') ) {
        case 1:
            $i = 6;
        break;
        case 2:
            $i = 0;
        break;
        case 3:
            $i = 1;
        break;
        case 4:
            $i = 2;
        break;
        case 5:
            $i = 3;
        break;
        case 6:
            $i = 4;
        break;
        case 7:
            $i = 5;
        break;
    }
    return '<option value="'.$tipoHorarioTurno[$i]['idTipoHorarioTurno'].'" selected>'.$tipoHorarioTurno[$i]['descripcionHorarioTurno'].'</option>';
}

$objCon = NULL;
?>