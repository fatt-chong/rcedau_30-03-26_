<?php
session_start();

require("../../../config/config.php");
require_once("../../../class/Connection.class.php");    $objCon      = new Connection();
require_once("../../../class/Util.class.php"); 		    $objUtil     = new Util;
require_once('../../../class/Admision.class.php');      $objAdmision = new Admision;
require_once("../../../class/Rce.class.php" );          $objRce      = new Rce;

$objCon->db_connect();

$parametros = $objUtil->getFormulario($_POST);

$resultado  = $objRce->obtenerInfoSolicitudAPS($objCon, $parametros['idSolicitudAPS']);

$version    = $objUtil->versionJS();
?>



<!--
################################################################################################################################################
                                                       			    CARGA JS
-->
<script type="text/javascript" src="<?=PATH?>/controllers/client/solicitud_aps/solicitud_aps_cambiar_consultorio.js?v=<?=$version;?>"></script>



<!--
################################################################################################################################################
                                                       	    DESPLIGUE CAMBIO CONSULTORIO
-->
<div class="row">

    <div class="col-lg-1"></div>

    <div class="col-lg-10">

        <div class="row">

            <label class="mifuente">Consultorio</label>

        </div>

        <div class="row">

            <div id="consultarioDetalleAPS">

                <select class="form-control  form-control-sm mifuente" name="slc_consultorioDetalleSolicitud" id="slc_consultorioDetalleSolicitud">

                    <option value="0" disabled selected>Seleccione Consultorio</option>

                    <?php

                    $consultorios      = $objAdmision->listarConsultorios($objCon, 'filtroConsultoriosAPS');

                    $totalConsultorios = count($consultorios);

                    for ( $i = 0; $i < $totalConsultorios; $i++ ) {

                        $selected = '';

                        if ( $resultado['codigoConsultorio'] == $consultorios[$i]['con_id'] ) {

                            $selected = 'selected';

                        }

                        ?>

                        <option value="<?php echo $consultorios[$i]['con_id'];?>" <?php echo $selected; ?> ><?php echo $consultorios[$i]['con_descripcion']; ?></option>

                    <?php

                    }

                    ?>

                </select>

            </div>

        </div>

    </div>

    <div class="col-lg-1"></div>

</div>



<!--
################################################################################################################################################
                                                       	            VARIABLES OCULTAS
-->
<input id="idSolicitudAPS" value="<?php echo $resultado['idSolicitudAPS']; ?>" hidden>

<input id="idDau"             value="<?php echo $resultado['idDau']; ?>"             hidden>

<input id="idPaciente"        value="<?php echo $resultado['idPaciente']; ?>"        hidden>



<!--
################################################################################################################################################
                                                       	            CIERRE CONEXIÓN
-->
<?php

$objCon = NULL;

?>