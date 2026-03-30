<?php
session_start();
error_reporting(0);
require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php');         $objCon             = new Connection; $objCon->db_connect();
require_once('../../../../class/Util.class.php');               $objUtil            = new Util;
require_once('../../../../class/Categorizacion.class.php');     $objCate            = new Categorizacion;
require_once('../../../../class/RegistroClinico.class.php');    $objRegistroClinico = new RegistroClinico;


if ( $_POST['tipoFormulario'] == 'verSolicitudEvolucion' ) {
    
    require_once('../../../../class/Evolucion.class.php');          
    
    $objEvolucion       = new Evolucion;
    
    $respuestaEvolucion = $objEvolucion->obtenerDatosSolicitudEvolucion($objCon, $_POST['idSolicitudEvolucion']);
    
    $parametros['dau_id'] 	    = $_POST['dau_id'];


    $transexual_bd   = $respuestaEvolucion[0]['transexual'];
    $nombreSocial_bd = $respuestaEvolucion[0]['nombreSocial'];
    $nombrePaciente  = $respuestaEvolucion[0]['nombres'].' '.$respuestaEvolucion[0]['apellidopat'].' '.$respuestaEvolucion[0]['apellidomat'];
    $nombreLabel      = 'Paciente';

} else {

    $parametros['dau_id'] 	    = $_POST['dau_id'];

    $parametros['id_dau'] 	    = $_POST['dau_id'];

    $datosRce 				    = $objRegistroClinico->consultaRCE($objCon,$parametros);

    $datosU 				    = $objCate -> searchPaciente($objCon, $parametros['dau_id']);

    $parametros['rce_id']	    = $datosRce[0]['regId'];

    $parametros['idPaciente']   = $datosU[0]['id_paciente'];



    $transexual_bd   = $datosU[0]['transexual'];
    $nombreSocial_bd = $datosU[0]['nombreSocial'];
    $nombrePaciente  = $datosU[0]['nombres'].' '.$datosU[0]['apellidopat'].' '.$datosU[0]['apellidomat'];
    $nombreLabel      = 'Paciente';

}


$infoInputLabel  = $objUtil->vista_dau_input_label($transexual_bd,$nombreSocial_bd,$nombrePaciente,$nombreLabel,'');
// print('<pre>');  print_r($infoInputLabel);  print('</pre>');

$version = $objUtil->versionJS();

$fechaHora          = $respuestaEvolucion[0]['SEVOfecha'];
list($fecha, $hora) = explode(' ', $fechaHora);

?>



<!-- 
################################################################################################################################################
                                                            ARCHIVO JS
-->



<!-- 
################################################################################################################################################
                                                    FORMULARIO EVOLUCIÓN
-->
<form id="frm_ingresarSolicitudEvolucion" name="frm_ingresarSolicitudEvolucion" class="formularios" method="POST">



    <fieldset style="padding: 0px">
        
        <div class="col-md-12">
            
            <!-- Primer Cuadro -->
            <div class="row">
                
                <!-- Paciente -->
                <div class="col-md-12">
                
                    <label class="encabezado"><?=$infoInputLabel['label']?></label>
                    
                    <?php
                    if($_POST['tipoFormulario'] == 'verSolicitudEvolucion'){
                    ?>
                        <input type="input"  class="form-control form-control-sm mifuente" id="frm_paciente" name="frm_paciente" value="<?=$infoInputLabel['input']?>" readonly>
                    <?php
                    }
                    else{
                    ?> 
                        <input type="input"  class="form-control form-control-sm mifuente" id="frm_paciente" name="frm_paciente" value="<?=$infoInputLabel['input']?>" readonly>
                    <?php
                    }
                    ?>
                
                </div>
            
            </div>



            <!-- Segundo Cuadro -->
            <div class="row mt-2">
                
                <!-- Evolución -->
                <div class="col-md-12">
                    <label class="encabezado">Evolución&nbsp;(<b><?=$respuestaEvolucion[0]['SEVOusuario']?> <?= $objUtil->fechaInvertida($fecha)?> a las <?=substr($hora, 0, -3);?></b>)</label>

                    <?php
                    if ( $_POST['tipoFormulario'] == 'verSolicitudEvolucion' ) {
                    ?>

                        <textarea class="form-control form-control-sm mifuente" rows="10" id="frm_evolucion" name="frm_evolucion" readonly><?php echo $respuestaEvolucion[0]['SEVOevolucion'];?></textarea>
                    
                    <?php
                    } else if ( ! isset($_POST['tipoFormulario']) ) {
                    ?>

                        <textarea class="form-control form-control-sm mifuente" rows="10" id="frm_evolucion" name="frm_evolucion"></textarea>
                    
                    <?php
                    }
                    ?>
                
                </div>
            
            </div>
       
       </div>
    
    </fieldset>

    <!-- 
    **************************************************************************
                                Parte Superior
    **************************************************************************
    -->
    <!-- Id Dau -->
    <input type="hidden"  class="form-control form-control-sm mifuente" id="idDau" name="idDau" value="<?php echo $parametros['dau_id'];?> ">

    <!-- Id RCE -->
    <input type="hidden"  class="form-control form-control-sm mifuente" id="idRCE" name="idRCE" value="<?php echo $parametros['rce_id'];?> ">

    <!-- Id Paciente-->
    <input type="hidden"  class="form-control form-control-sm mifuente" id="idPaciente" name="idPaciente" value="<?php echo $parametros['idPaciente'];?> ">

</form>