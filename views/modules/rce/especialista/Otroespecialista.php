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
    $respuestaEspecialista  = $objEspecialista->obtenerDatosSolicitudEspecialistaOtros($objCon, $_POST['idSolicitudEspecialista']);
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


$rsOtroEspecialista             = $objRce->SelectOtro_especialista($objCon,$parametros);



$SESPobservacionGestionRealizada = ( $objUtil->existe($respuestaEspecialista[0]['sol_otro_observacion']) ) ? $respuestaEspecialista[0]['sol_otro_observacion'] : "";
if($SESPobservacionGestionRealizada ==""){
    $SESPobservacionGestionRealizada = $rsRce[0]['regHipotesisInicial'];
}

?>

<script type="text/javascript" src="<?=PATH?>/controllers/client/rce/especialista/Otroespecialista.js?v=<?=$version;?>13522"></script>


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
                     <select class="form-control form-control-sm mifuente col-lg-12" id="ftm_especialista_otro" name="ftm_especialista_otro" <?php echo $disabled; ?>>
                        <option value="0" selected disabled>Seleccione...</option>
                        <?php for ( $i = 0; $i < count($rsOtroEspecialista); $i++ ) {  ?>
                        <option value="<?=$rsOtroEspecialista[$i]['id_otro']?>"  <?php if ( $respuestaEspecialista[0]['id_otro']  == $rsOtroEspecialista[$i]['id_otro']) { echo "selected";} ?> ><?=$rsOtroEspecialista[$i]['descripcion_otro']?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="row">
                <!-- Observación -->
                <div class="col-md-12">
                <label class="encabezado">Observación</label>
                    <textarea class="form-control form-control-sm mifuente" rows="4" id="frm_observacion" name="frm_observacion" maxlength="500" <?php echo $disabled; ?> ><?php echo $SESPobservacionGestionRealizada; ?></textarea>
                    <label id="lengthTextoSolicitudEspecialista" class="mifuente" style="float:right"></label>
                </div>
            </div>
            <?php if ( ($respuestaEspecialista[0]['estado_sol_otro'] == 1 || $respuestaEspecialista[0]['estado_sol_otro'] == 4 ) && $_POST['tipoFormulario'] != "verDetalle"){ ?>
                 <?php if (  $respuestaEspecialista[0]['estado_sol_otro'] == 4 ){ $disabledRespuesta = "disabled"; } ?>
                 <?php if (  $_POST['tipoFormulario'] == "verDetalle" ){ $disabledRespuesta = "disabled"; } ?>
            <div class="row" <?php echo $hidden; ?> >
                <!-- Observación del Especialista-->
                <div class="col-md-12">
                       <hr>
                    <label class="encabezado">Evaluación Especialista</label>
                    <textarea class="form-control form-control-sm mifuente" rows="5" id="frm_observacionEspecialista" name="frm_observacionEspecialista" <?php echo $disabledRespuesta; ?> maxlength="500"><?=$respuestaEspecialista[0]['sol_otro_usuarioAplica_observacion'];?></textarea>
                    <label id="lengthTextoObservacionEspecialista" style="float:right"></label>
                </div>

                <?php if ( $respuestaEspecialista[0]['estado_sol_otro'] == 1  ){ ?>
                <div class="col-md-12 mt-3"  >
                    <button type="button" id="btnAprobacionEspecialista" class="btn btn-sm mifuente btn-primary col-lg-2" alt="Evolucionar" title="Evolucionar" style="float:right">Evolucionar</button>
                </div>
                 <?php } ?>
            </div>

            <?php } ?>
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
    <input type="hidden"  class="form-control" id="idsolicitudEspecialista" name="idsolicitudEspecialista" value="<?php echo $_POST['idSolicitudEspecialista'];?>">
    <!-- Id profesional especialista -->
    <input type="hidden"  class="form-control" id="idProfesionalEspecialista" name="idProfesionalEspecialista" value="<?php echo $respuestaEspecialista[0]['SESPidProfesionalEspecialista'];?>">
    <!-- Id tipo atención -->
    <input type="hidden"  class="form-control" id="tipoAtencion" name="tipoAtencion" value="<?php echo $respuestaEspecialista[0]['tipoAtencion'];?>">
    <!-- Llamada desde -->
    <input type="hidden"  class="form-control" id="llamadaDesde" name="llamadaDesde" value="<?php echo $parametros['llamadaDesde'];?>">
</form>