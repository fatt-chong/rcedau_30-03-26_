<?php
session_start();
error_reporting(0);
ini_set('max_execution_time', 600);
ini_set('memory_limit', '128M');
require("../../../config/config.php");
require_once('../../../class/Connection.class.php');        $objCon             = new Connection;       $objCon->db_connect();
require_once("../../../class/Admision.class.php");          $objAdmision        = new Admision;
require_once("../../../class/Rce.class.php" );              $objRce             = new Rce;
require_once("../../../class/Util.class.php");              $objUtil            = new Util;


$objCon->db_connect();

$totalPag = $_POST['totalPag'];

$version = $objUtil->versionJS();

if ( $_POST ) {

    $campos = $objUtil->getFormulario($_POST);

    $_SESSION['modulos']["solicitudesAPS"]["worklist"] = $campos;

} else if ( isset($_SESSION['modulos']["solicitudesAPS"]["worklist"]) ) {

    $campos = $_SESSION['modulos']["solicitudesAPS"]["worklist"];

}

switch ( $_POST['accion'] ) {

    case 1:

        $_SESSION['pagina_actual'] 	= 1;

        $totalPag					= 0;

    break;

    case 2:

        $objUtil->actualizaPagina('-','');

    break;

    case 3:

        $objUtil->actualizaPagina('+', $totalPag);

    break;

    case 4:

        $objUtil->actualizaPagina('P','');

    break;

    case 5:

        $objUtil->actualizaPagina('U',$totalPag);

    break;

    default:

        $_SESSION['pagina_actual'] 	= 1;

        $totalPag					= 0;

    break;

}

$resultado      = $objRce->obtenerResultadoSolicitudesAPS($objCon, $campos, $totalPag, $total);

$estados        = $objRce->obtenerEstadosSolicitudAPS($objCon);

$prioridades    = $objRce->obtenerPrioridadesSolicitudAPS($objCon);


?>



<!--
################################################################################################################################################
                                                       			    CARGA JS
-->
<script type="text/javascript" src="<?=PATH?>/controllers/client/solicitud_aps/solicitud_aps.js?v=<?=$version;?>"></script>




<!--
################################################################################################################################################
                                                       			 DESPLIGUE TÍTULO
-->
<!-- <div class="titulos">
	<h3>
		<span>Solicitudes de APS</span>
	</h3>
</div>

<br>
 -->

<!--
################################################################################################################################################
                                                       			 DESPLIGUE BÚSQUEDA
-->
<div id='divBusquedaSolicitudesAPS'>
    <form id="frm_busquedaSolicitudesAPS" name="frm_busquedaSolicitudesAPS" class="formularios" role="form" method="POST">
        <div class="m-3">
            <div class="row">
                <label class="text-secondary ml-3"><i class="fas fa-file   mifuente20" style="color: #59a9ff;"></i> Solicitudes de APS</label>
                <div class=" col-lg-12 dropdown-divider   mt-2 mb-4 " ></div>
            </div>
            <input type="hidden" id="totalPag" name="totalPag" value="<?= $totalPag;?>"/>
            <div class="row ">

            <!-- RUN Paciente -->
                <div  class="form-group col-lg-1">

                    <label class="control-label mifuente">RUN Paciente</label>

                    <div class="input-group">

                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>

                        <input id="frm_runPaciente" type="text" class="form-control form-control-sm mifuente" name="frm_runPaciente" placeholder="RUN" onDrop="return false"

                            <?php

                            if ( $campos['frm_runPaciente'] ) {

                                echo 'value="'.$objUtil->formatearNumero($campos['frm_runPaciente']).'-'.$objUtil->generaDigito($campos['frm_runPaciente']).'"';

                            }
                            ?>

                            >

                    </div>

                </div>



                <!-- Nombre Paciente -->
                <div  class="form-group col-lg-2">

                    <label class="control-label mifuente">Nombre Paciente</label>

                    <div class="input-group">

                        <span class="input-group-addon"><i class="glyphicon glyphicon-font"></i></span>

                        <input id="frm_nombrePaciente" type="text" class="form-control form-control-sm mifuente" name="frm_nombrePaciente" placeholder="Nombre Paciente" onDrop="return false"

                            <?php
                            if ( $campos['frm_nombrePaciente'] ) {

                                echo 'value="'.$campos['frm_nombrePaciente'].'"';

                            }
                            ?>

                            >

                    </div>

                </div>



                <!-- Consultorio -->
                <div  class="form-group col-lg-2">

                    <label class="control-label mifuente">Consultorio</label>

                    <div class="input-group">

                        <span class="input-group-addon"><i class="glyphicon glyphicon-plus"></i></span>

                        <?php

                        $consultorios      = $objAdmision->listarConsultorios($objCon, 'filtroConsultoriosAPS');

                        $totalConsultorios = count($consultorios);

                        ?>

                        <select class="form-control form-control-sm mifuente" name="slc_consultorio" id="slc_consultorio">

                            <option value="0" disabled selected>Seleccione Consultorio</option>

                            <?php
                            for ( $i = 0; $i < $totalConsultorios; $i++ ) {

                                if ( $campos['slc_consultorio'] == $consultorios[$i]['con_id'] ) {

                                    $selected = 'selected';

                                } else {

                                    $selected = '';

                                }

                                ?>

                                <option value="<?php echo $consultorios[$i]['con_id'];?>" <?php echo $selected; ?> > <?php echo $consultorios[$i]['con_descripcion']; ?> </option>

                            <?php

                            }

                            ?>

                        </select>

                    </div>

                </div>



                <!-- Fecha Solicitud (Desde)-->
                <div class="form-group col-lg-2">

                    <label class="control-label mifuente">Fecha Solicitud (Desde)</label>

                    <div class="form-group">

                    <div class='input-group date' id="date_fecha_desde" data-date-container='#date_fecha_desde'>

                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>

                        <input type='text' class="form-control form-control-sm mifuente" name="frm_fechaSolicitudDesde" id="frm_fechaSolicitudDesde" onDrop="return false" placeholder="DD/MM/YY"

                            <?php
                            if ( $campos['frm_fechaSolicitudDesde'] ) {

                                echo 'value="'.$campos['frm_fechaSolicitudDesde'].'"';

                            }
                            ?>

                            >

                    </div>

                    </div>

                </div>



                <!-- Fecha Solicitud (Término)-->
                <div class="form-group col-lg-2">

                    <label class="control-label mifuente">Fecha Solicitud (Hasta)</label>

                    <div class="form-group">

                    <div class='input-group date' id="date_fecha_hasta" data-date-container='#date_fecha_hasta'>

                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>

                        <input type='text' class="form-control form-control-sm mifuente" name="frm_fechaSolicitudHasta" id="frm_fechaSolicitudHasta" onDrop="return false" placeholder="DD/MM/YY"

                            <?php
                            if ( $campos['frm_fechaSolicitudHasta'] ) {

                                echo 'value="'.$campos['frm_fechaSolicitudHasta'].'"';

                            }
                            ?>

                            >

                    </div>

                    </div>

                </div>



                <!-- Estado -->
                <div  class="form-group col-lg-2">

                    <label class="control-label mifuente">Estado</label>

                    <div class="input-group">

                        <span class="input-group-addon"><i class="glyphicon glyphicon-th-list"></i></span>

                        <select class="form-control form-control-sm mifuente" name="slc_estadoSolicitud" id="slc_estadoSolicitud">

                            <option value="0" disabled selected>Seleccione</option>

                            <option value="TODOS" <?php echo (!empty($campos) && $campos['slc_estadoSolicitud'] == "TODOS") ? "selected" : ""; ?> >Todos</option>

                            <?php

                            $totalEstados = count($estados);

                            for ( $i = 0; $i < $totalEstados; $i++ ) {

                                if ( $campos['slc_estadoSolicitud'] == $estados[$i]['idEstadoSolicitud'] ) {

                                    $selected = 'selected';

                                } else {

                                    $selected = '';

                                }

                                ?>

                                <option value="<?php echo $estados[$i]['idEstadoSolicitud'];?>" <?php echo $selected; ?> > <?php echo $estados[$i]['descripcionEstadoSolicitud']; ?> </option>

                            <?php

                            }

                            ?>

                        </select>

                    </div>

                </div>




                <!-- Prioridad -->
                <?php
                $disabled = "disabled";

                if ( $campos['slc_estadoSolicitud'] == 3 ) {

                    $disabled = '';

                }

                ?>
                <div  class="form-group col-lg-1">

                    <label class="control-label mifuente">Prioridad</label>

                    <div class="input-group">

                        <span class="input-group-addon"><i class="glyphicon glyphicon-arrow-up"></i></span>

                        <select class="form-control form-control-sm mifuente" name="slc_prioridadSolicitud" id="slc_prioridadSolicitud" <?php echo $disabled; ?> >

                            <option value="0" disabled selected>Seleccione</option>

                            <?php

                            $totalPrioridades = count($prioridades);

                            for ( $i = 0; $i < $totalPrioridades; $i++ ) {

                                if ( $campos['slc_prioridadSolicitud'] == $prioridades[$i]['idPrioridadSolicitud'] ) {

                                    $selected = 'selected';

                                } else {

                                    $selected = '';

                                }

                                ?>

                                <option value="<?php echo $prioridades[$i]['idPrioridadSolicitud'];?>" <?php echo $selected; ?> > <?php echo $prioridades[$i]['descripcionPrioridadSolicitud']; ?> </option>

                            <?php

                            }

                            ?>

                        </select>

                    </div>

                </div>

            </div>

            <div class="row">

                <!-- Botón Buscar -->
                <div  class="form-group col-lg-12">

                    <label class="control-label mifuente">&nbsp;</label>

                    <div class="input-group">

                        <button id="btnBuscarSolicitudesAPS" type="button" class="btn btn-default enviar btn-sm"><img src="<?=PATH?>/assets/img/dau-05_.png" alt="Buscar"></button>

                        <?php
                        // if ( count($campos) > 1 ) {
                        ?>
                            <button type="button" class="btn btn-default btn-sm" alt="Limpiar" title="Limpiar" id="btnEliminar"><img src="<?=PATH?>/assets/img/dau-08.png" ></button>

                        <?php
                        // }
                        ?>

                        <button type="button" class="btn btn-default btn-sm" alt="Excel" title="Excel" id="btnExportarExcel"><img src="<?=PATH?>/assets/img/Excel-03.png" ></button>

                    </div>

                </div>

            </div>
        </div>

    </form>

</div>



<!--
################################################################################################################################################
                                                       			 DESPLIGUE RESULTADOS
-->
<?php
if ( $totalPag > 0 ) {
?>

    <div id="resultadosSolicitudesAPS">

        <table id="tablaSolicitudesAPS" class="table table-hover table-condensed">

            <thead>

                <tr class="detalle">

                    <th width="10%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><label>Fecha Solicitud</label></th>

                    <th width="10%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><label>RUN Paciente</label></th>

                    <th width="15%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><label>Nombre Paciente</label></th>

                    <th width="35%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><label>Diagnóstico</label></th>

                    <th width="10%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><label>Estado</label></th>

                    <th width="10%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><label>Prioridad</label></th>

                    <th width="10%"   style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><label >Acciones</label></th>

                </tr>

            </thead>

            <tbody>

            <?php

            $totalResultados = count($resultado);

            for ( $i = 0; $i < $totalResultados; $i++ ) {

                $color = '';

                switch ( $resultado[$i]['descripcionEstadoSolicitud'] ) {

                    case 'Inicial':

                        $color = '';

                    break;

                    case 'Pendiente':

                        $color = 'class="bg-warning"';

                    break;

                    case 'Egresado A Citación':

                        $color = 'class="bg-success"';

                    break;

                    case 'Egresado Sin Cita':

                        $color = 'class="bg-danger"';

                    break;

                }

                if ( $resultado[$i]['descripcionEstadoSolicitud'] === 'Inicial' ) {

                    $hidden = "style='display:none;'";

                }


                $transexual_bd 	 = $resultado[$i]['transexual'];
                $nombreSocial_bd = $resultado[$i]['nombreSocial'];
                $nombrePaciente  =$resultado[$i]['nombrePaciente'];
                $width           = 28;
                $height          = 23;

                
                $infoPaciente    = $objUtil->infoDatosNombreTabla($transexual_bd,$nombreSocial_bd,$nombrePaciente,$width,$height);

                ?>

                <tr <?php echo $color; ?> >

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo date('d-m-Y H:i:s', strtotime($resultado[$i]['fechaSolicitud'])); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $objUtil->formatearNumero($resultado[$i]['rutPaciente']).'-'.$objUtil->generaDigito($resultado[$i]['rutPaciente']); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $infoPaciente; ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $resultado[$i]['descripcionCie10']; ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $resultado[$i]['descripcionEstadoSolicitud']; ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $resultado[$i]['descripcionPrioridadSolicitud']; ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">

                        <button type="button" id="<?php echo $resultado[$i]['idDau'].'-'.$resultado[$i]['idRCE'] ?>" class="btn btn-sm btn-primary item-menu btnAccionDesplegarRCE" data-toggle="tooltip" data-placement="top" title="RCE"><i class="fas fa-file-pdf"></i></button>

                        <button type="button" id="<?php echo $resultado[$i]['idPaciente'] ?>" class="btn btn-sm btn-primary item-menu btnAccionDesplegarHistorialClinico" data-toggle="tooltip" data-placement="top" title="Historial Clínico"><i class="fas fa-laptop-medical"></i></button>

                        <button type="button" id="<?php echo $resultado[$i]['idSolicitudAPS']; ?>" class="btn btn-sm btn-primary item-menu btnCambiarConsultorio" data-toggle="tooltip" data-placement="top" title="Consultorio"><i class="far fa-hospital"></i></button>

                        <button type="button" id="<?php echo $resultado[$i]['idSolicitudAPS']; ?>" class="btn btn-sm btn-primary item-menu btnAgendamientoSolicitudAPS" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-pencil-alt"></i></button>

                        <button type="button" id="<?php echo $resultado[$i]['idSolicitudAPS']; ?>" class="btn btn-sm btn-primary item-menu btnMostrarDetalleSolicitud" data-toggle="tooltip" data-placement="top" title="Info" <?php echo $hidden; ?> ><i class="fas fa-search"></i></button>

                    </td>

                </tr>

            <?php

            }

            ?>

            </tbody>

        </table>

    </div>

    <br>



    <!--
    ################################################################################################################################################
                                                                    NAVEGADOR DE PÁGINAS
    -->
    <div id="navegadorPaginas" style="border-style: solid; border-width: 0.5px; border-color: gray;">

        <br>

        <table width="100%">

            <tr>

                <td width="20%" style="text-align:right">

                    <img id="primero_l" class="puntero" src="/rcedau/assets/img/first.png" sizes="100vw" title="Primera página" alt="Primera página"/>

                </td>

                <td width="2%" style="text-align:right">

                    <img id="atras_l" class="puntero" src="/rcedau/assets/img/previous.png" sizes="100vw" title="Anterior página" alt="Anterior página"/>

                </td>

                <td width="24%" style="text-align:center"><label class="control-label mifuente"><?= $total;?> Registros encontrados, mostrando <?php echo $_SESSION['pagina_actual']; ?> de <?= $totalPag;?> páginas.</label></td>

                <td width="2%" style="text-align:left">

                    <img id="siguiente_l" class="puntero" src="/rcedau/assets/img/next.png" sizes="100vw" title="Siguiente página" alt="Siguiente página"/>

                </td>

                <td width="20%" style="text-align:left">

                    <img id="ultimo_l" class="puntero" src="/rcedau/assets/img/last.png" sizes="100vw" title="Ultima página" alt="Ultima página"/>

                </td>

            </tr>

        </table>

        <br>

    </div>

<?php
} else {
?>

    <table width="100%" border="0">

        <tr>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><center>¡No hay resultados para desplegar!</center></td>

        </tr>

    </table>

<?php
}
?>

<br>



<!--
################################################################################################################################################
                                                       	            CIERRE CONEXIÓN
-->
<?php

$objCon = NULL;

?>
