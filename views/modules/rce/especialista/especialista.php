<?php
session_start();
error_reporting(0);
require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php');         $objCon             = new Connection; $objCon->db_connect();
require_once('../../../../class/Util.class.php');               $objUtil            = new Util;
require_once('../../../../class/Rce.class.php');                $objRce             = new Rce;
require_once('../../../../class/Categorizacion.class.php');     $objCate            = new Categorizacion;
require_once('../../../../class/RegistroClinico.class.php');    $objRegistroClinico = new RegistroClinico;
require_once('../../../../class/Agenda.class.php');             $objAgenda          = new Agenda;
require_once('../../../../class/Especialista.class.php');       $objEspecialista    = new Especialista;

$parametros                 = $objUtil->getFormulario($_POST);
if ( ! is_null($_POST['tipoFormulario']) && ! empty($_POST['tipoFormulario']) ) {
    $respuestaEspecialista  = $objEspecialista->obtenerDatosSolicitudEspecialista($objCon, $_POST['idSolicitudEspecialista']);
    $parametros['dau_id']   = $_POST['dau_id'];
    if($parametros['paciente_id']>0){
        $parametros['idPaciente']   = $parametros['paciente_id'];
    }
    $transexual_bd          = $respuestaEspecialista[0]['transexual'];
    $nombreSocial_bd        = $respuestaEspecialista[0]['nombreSocial'];
    $nombrePaciente         = $respuestaEspecialista[0]['nombres']." ".$respuestaEspecialista[0]['apellidopat']." ".$respuestaEspecialista[0]['apellidomat'];
    $nombreLabel            = 'Paciente';
    $disabled = "disabled";
} else {
    $parametros['dau_id'] 	    = $_POST['dau_id'];
    $parametros['id_dau'] 	    = $_POST['dau_id'];
    $datosRce 				    = $objRegistroClinico->consultaRCE($objCon,$parametros);
    $datosU 				    = $objCate -> searchPaciente($objCon, $parametros['dau_id']);
    $especialidades             = $objAgenda->getEspecialidad($objCon);
    $parametros['rce_id']	    = $datosRce[0]['regId'];
    $parametros['idPaciente']   = $datosU[0]['id_paciente'];
    $transexual_bd              = $datosU[0]['transexual'];
    $nombreSocial_bd            = $datosU[0]['nombreSocial'];
    $nombrePaciente             = $datosU[0]['nombres']." ".$datosU[0]['apellidopat']." ".$datosU[0]['apellidomat'];
    $nombreLabel                = 'Paciente';
}

$version = $objUtil->versionJS();

$rsRce                          = $objRegistroClinico->consultaRCE($objCon,$parametros);
$InfoNombre                     = $objUtil->vista_dau_input_label($transexual_bd,$nombreSocial_bd,$nombrePaciente,$nombreLabel,'');
// print('<pre>');  print_r($InfoNombre);  print('</pre>');
// 

?>

<script type="text/javascript" src="<?=PATH?>/controllers/client/rce/especialista/especialista.js?v=<?=$version;?>1"></script>


<form id="frm_ingresarSolicitudEspecialista" name="frm_ingresarSolicitudEspecialista" class="formularios m-3" method="POST">
    <h6 id="ensure-correct-role-and-provide-a-label" style=" border-radius: inherit; font-size:15px;"> <svg class="svg-inline--fa fa-circle-notch fa-w-16 text-danger mr-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle-notch" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M288 39.056v16.659c0 10.804 7.281 20.159 17.686 23.066C383.204 100.434 440 171.518 440 256c0 101.689-82.295 184-184 184-101.689 0-184-82.295-184-184 0-84.47 56.786-155.564 134.312-177.219C216.719 75.874 224 66.517 224 55.712V39.064c0-15.709-14.834-27.153-30.046-23.234C86.603 43.482 7.394 141.206 8.003 257.332c.72 137.052 111.477 246.956 248.531 246.667C393.255 503.711 504 392.788 504 256c0-115.633-79.14-212.779-186.211-240.236C302.678 11.889 288 23.456 288 39.056z"></path></svg>Detalle de Solicitud</h6>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <label class="encabezado"><?=$InfoNombre['label']?></label>
                    <?php $value = $InfoNombre['input']; ?>
                    <input type="input"  class="form-control form-control-sm mifuente" id="frm_paciente" name="frm_paciente" value="<?php echo $value;?>" readonly>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-8">
                    <label class="encabezado">Especialidad</label>
                    <?php
                    if ( ! is_null($_POST['tipoFormulario']) && ! empty($_POST['tipoFormulario']) ){
                    ?> 
                    <input type="input"  class="form-control form-control form-control-sm mifuente" id="frm_especialidad" name="frm_especialidad" value="<?php echo $respuestaEspecialista[0]['ESPdescripcion'];?>" disabled>
                    <input type="hidden"  class="form-control form-control form-control-sm mifuente" id="frm_idEspecialidad" name="frm_idEspecialidad" value="<?php echo $respuestaEspecialista[0]['SESPidEspecialidad'];?>" readonly>
                    <?php } else { ?>
                    <input type="input"  class="form-control form-control-sm mifuente" id="frm_especialidad" name="frm_especialidad"  >
                    <input type="hidden"  class="form-control form-control-sm mifuente" id="SESPfuente" name="SESPfuente"  >
                    <input type="hidden"  class="form-control form-control-sm mifuente" id="frm_idEspecialidad" name="frm_idEspecialidad">
                    <?php } ?>
                </div>
                <div class="col-md-2 form-group form-check mifuente">
                    <?php
                    $checked = ( $objUtil->existe($respuestaEspecialista[0]['SESPespecialistaDeLlamado']) && $respuestaEspecialista[0]['SESPespecialistaDeLlamado'] == "S" ) ? "checked" : "";
                    $disabled = ( $_POST['tipoFormulario'] == "verDetalle" || $objUtil->existe($respuestaEspecialista[0]['SESPespecialistaDeLlamado']) ) ? "disabled" : "";
                    ?>
                    <label class="encabezado">&nbsp;</label><br>
                    <input type="checkbox" id="frm_especialistaDeLlamado" name="frm_especialistaDeLlamado" class="mt-1 form-check-input" <?php echo $checked; ?> <?php echo $disabled; ?> >
                    <label class="encabezado">&nbsp;Especialista de Llamado</label>
                    
                </div>
                <div class="col-md-2 form-group form-check mifuente">
                    <?php
                    $checked = ( $objUtil->existe($respuestaEspecialista[0]['SESPgestionRealizada']) && $respuestaEspecialista[0]['SESPgestionRealizada'] == "S" ) ? "checked" : "";
                    $disabled = ( $_POST['tipoFormulario'] == "verDetalle" || $objUtil->existe($respuestaEspecialista[0]['SESPusuarioGestionRealizada']) || ($respuestaEspecialista[0]['SESPespecialistaDeLlamado'] == 'N' && $respuestaEspecialista[0]['SESPgestionRealizada'] == 'N') ) ? "disabled" : "";
                    ?>
                    <label class="encabezado">&nbsp;</label><br>
                    <input type="checkbox" id="frm_gestionRealizada" name="frm_gestionRealizada"  class="mt-1 form-check-input"<?php echo $checked; ?> <?php echo $disabled; ?> >
                    <label class="encabezado">&nbsp;Gestión Realizada</label>
                    
                    
                </div>
            </div>
            <?php
            $hidden = ( $respuestaEspecialista[0]['SESPgestionRealizada'] == "N" ) ? "Style='display:none;'" : "";
            ?>
            <div class="row" id="gestionRealizada" <?php echo $hidden; ?> >
                <!-- Select médicos especialistas -->
                <div class="col-md-4">
                    <label class="encabezado">Médicos Especialistas</label>
                    <select class="form-control form-control-sm mifuente" name="frm_medicoEspecialista" id="frm_medicoEspecialista">
                        <option value="" selected disabled>Seleccione Médico Especialista</option>
                    </select>

                </div>
                <!-- Observación -->
                <div class="col-md-8">
                <label class="encabezado">Observación Gestión Realizada</label>
                    <?php
                    $value = ( $objUtil->existe($respuestaEspecialista[0]['SESPobservacionGestionRealizada']) ) ? $respuestaEspecialista[0]['SESPobservacionGestionRealizada'] : "";
                    $disabled = ( $_POST['tipoFormulario'] == "verDetalle" || $objUtil->existe($respuestaEspecialista[0]['SESPobservacionGestionRealizada']) ) ? "disabled" : "";
                    ?>
                    <textarea class="form-control form-control-sm mifuente" rows="4" id="frm_observacionGestionRealizada" name="frm_observacionGestionRealizada" maxlength="500" <?php echo $disabled; ?> ><?php echo $value; ?></textarea>
                    <label id="lengthTextoObservacionGestionRealizada" class="mifuente" style="float:right"></label>
                </div>
            </div>
            <div class="row">
                <!-- Observación -->
                <div class="col-md-12">
                <label class="encabezado">Observación</label>
                    <?php
                    $value = ( $objUtil->existe($respuestaEspecialista[0]['SESPobservacion']) ) ? $respuestaEspecialista[0]['SESPobservacion'] : "";
                    if($value ==""){
                        $value = $rsRce[0]['regHipotesisInicial'];
                    }
                    $disabled = ( $objUtil->existe($_POST['tipoFormulario']) || $objUtil->existe($respuestaEspecialista[0]['SESPobservacion']) ) ? "disabled" : "";
                    ?>
                    <textarea class="form-control form-control-sm mifuente" rows="4" id="frm_observacion" name="frm_observacion" maxlength="500" <?php echo $disabled; ?> ><?php echo $value; ?></textarea>
                    <label id="lengthTextoSolicitudEspecialista" class="mifuente" style="float:right"></label>
                </div>
            </div>
            <?php
            $hidden = ( $respuestaEspecialista[0]['SESPestado'] != 4 && $respuestaEspecialista[0]['SESPestado'] != 6 && $_POST['tipoFormulario'] != "aprobacionEspecialista" ) ? "Style='display:none;'" : "";
            $hiddenBoton = ( $objUtil->existe($respuestaEspecialista[0]['SESPusuarioAplica']) || $objUtil->existe($respuestaEspecialista[0]['SESPobservacionEspecialista']) ) ? "Style='display:none;'" : "";
            $disabled = ( $objUtil->existe($respuestaEspecialista[0]['SESPusuarioAplica']) || $objUtil->existe($respuestaEspecialista[0]['SESPobservacionEspecialista']) ) ? "disabled" : "";
            $value = $respuestaEspecialista[0]['SESPobservacionEspecialista'];
            ?>
            <div class="row" <?php echo $hidden; ?> >
                <!-- Observación del Especialista-->
                <div class="col-md-12">
                    <label class="encabezado">Evaluación Especialista</label>
                    <textarea class="form-control form-control-sm mifuente" rows="5" id="frm_observacionEspecialista" name="frm_observacionEspecialista" <?php echo $disabled; ?> maxlength="500"><?php echo $value;?></textarea>
                    <label id="lengthTextoObservacionEspecialista" style="float:right"></label>
                </div>
                <div class="col-md-12">&nbsp;</div>
                <div class="col-md-12" <?php echo $hiddenBoton; ?> >
                    <button type="button" id="btnAprobacionEspecialista" class="btn btn-sm mifuente btn-primary" alt="Evolucionar" title="Evolucionar" style="float:right">Evolucionar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tipo Formulario -->
    <input type="hidden"  class="form-control" id="tipoFormulario" name="idFormulario" value="<?php echo $_POST['tipoFormulario'];?>">
    <!-- Id Dau -->
    <input type="hidden"  class="form-control" id="idDau" name="idDau" value="<?php echo $parametros['dau_id'];?>">
    <!-- Id RCE -->
    <input type="hidden"  class="form-control" id="idRCE" name="idRCE" value="<?php echo $parametros['rce_id'];?>">
    <!-- Id Paciente-->
    <input type="hidden"  class="form-control" id="idPaciente" name="idPaciente" value="<?php echo $parametros['idPaciente'];?>">
    <!-- Id solicitud de especialista -->
    <input type="hidden"  class="form-control" id="idsolicitudEspecialista" name="idsolicitudEspecialista" value="<?php echo $respuestaEspecialista[0]['SESPid'];?>">
    <!-- Id profesional especialista -->
    <input type="hidden"  class="form-control" id="idProfesionalEspecialista" name="idProfesionalEspecialista" value="<?php echo $respuestaEspecialista[0]['SESPidProfesionalEspecialista'];?>">
    <!-- Id tipo atención -->
    <input type="hidden"  class="form-control" id="tipoAtencion" name="tipoAtencion" value="<?php echo $respuestaEspecialista[0]['tipoAtencion'];?>">
    <!-- Llamada desde -->
    <input type="hidden"  class="form-control" id="llamadaDesde" name="llamadaDesde" value="<?php echo $parametros['llamadaDesde'];?>">
</form>