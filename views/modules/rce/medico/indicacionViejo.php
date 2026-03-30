<?php
session_start();
error_reporting(0);
// require_once("../../../../config/config.php");
require_once('../../../../class/Util.class.php');               $objUtil                = new Util;
require_once('../../../../class/Connection.class.php');         $objCon                 = new Connection; $objCon->db_connect();

require_once("../../../../class/Dau.class.php" );               $objDau                 = new Dau;
require_once('../../../../class/Config.class.php');             $objConfig              = new Config;
require_once('../../../../class/Rce.class.php');                $objRce                 = new Rce;
require_once('../../../../class/Categorizacion.class.php');     $objCategorizacion      = new Categorizacion;
require_once('../../../../class/RegistroClinico.class.php');    $objRegistroClinico     = new RegistroClinico;
require_once('../../../../class/Rce.class.php');                $objRce                 = new Rce;
require_once('../../../../class/Bitacora.class.php');           $objBitacora            = new Bitacora;
require_once('../../../../class/AltaUrgencia.class.php');       $objAltaUrgencia        = new AltaUrgencia;
require_once('../../../../class/Evolucion.class.php');          $objEvolucion           = new Evolucion;
require_once('../../../../class/Admision.class.php');           $objAdmision            = new Admision;
require_once('../../../../class/Imagenologia.class.php');       $objImagenologia        = new Imagenologia;
require_once('../../../../class/Laboratorio.class.php');        $objLaboratorio         = new Laboratorio;
require_once('../../../../class/Usuarios.class.php');           $objUsuarios            = new Usuarios;

$parametros                 = $objUtil->getFormulario($_POST);
$dau_id                     = $_POST['dau_id'];
$rsRce                      = $objRegistroClinico->consultaRCE($objCon,$parametros);
$rsEt                       = $objDau->obtenerEtilico($objCon);
$parametros['rce_id']       = $rsRce[0]['regId'];
$cargarPaisEpidemiologia    = $objAdmision->listarPaisNacimiento($objCon);
$version                    = $objUtil->versionJS();
$datosDAU                   = $objDau->ListarPacientesDau($objCon, $parametros);

$fechaAdmision              = date("Y-m-d",strtotime($datosDAU[0]['dau_admision_fecha']));
$horaAdmision               = date("H:i:s", strtotime($datosDAU[0]['dau_admision_fecha']));

$fechaHora                  = date("Y-m-d");
$horaFecha                  = date("H:i:s");

$estadoAplicado             = 4;
$estadoAnulado              = 6;


$resRce                 = $objRegistroClinico->consultaRCE($objCon,$parametros);
$parametros['rce_id']   = $resRce[0]['regId'];
$rsTipoExmamen          = $objImagenologia->getTipoExamen($objCon);
$listaServicios         = $objRegistroClinico->listarServiciosIndicaciones($objCon);
$listadoExaLab          = $objLaboratorio->getExamenesLaboratorio($objCon);
$eventos                = 1;
$listadoIndicaciones    = $objRegistroClinico->listarIndicacionesMedicas($objCon,$parametros, $eventos);

// print('<pre>'); print_r($listadoIndicaciones); print('</pre>');

?>



<!--
################################################################################################################################################
                                                                    ARCHIVO JS
-->

<script type="text/javascript" src="<?=PATH?>/controllers/client/rce/rce/indicacion.js?v=<?=$version;?>"></script>



<!--
################################################################################################################################################
                                                        DESPLIGUE DETALLES DE RCE
-->
<div id="div_rceDetalle_<?=$dau_id?>">
    <form id="frm_solicitud_rayo" name="frm_solicitud_rayo">
        <input type="hidden" id="frm_dau_id" name="frm_dau_id" value="<?=$dau_id;?>" />
        <input type="hidden" id="frm_rce_id" name="frm_rce_id" value="<?=$parametros['rce_id'];?>" />
        <input type="hidden" id="frm_paciente_id" name="frm_paciente_id" value="<?=$datosDAU[0]['id_paciente'];?>" />
        <input type="hidden" id="tipoAtencion" name="frm_tipoAtencion" value="<?php echo $datosDAU[0]["dau_atencion"]; ?>" />
        <input type="hidden" id="dau_id" name="dau_id" value="<?=$parametros['dau_id']?>">
        <input type="hidden" id="id_rce" name="id_rce" value="<?=$parametros['rce_id']?>">
        <div class="card-body mifuente12">
            <div id="div_all_indicacion" class="row ScrollStylePIndicaciones">
                <div class=" col-lg-12 col-md-12" style="padding-left: 2px !important; padding-right: 2px !important;">
                    <?php
                        //si dauid está en la tabla de entrega de turno, buscar última persona que lo realizó y desplegar
                        for($i=0;$i<count($listadoIndicaciones);$i++){
                            if($listadoIndicaciones[$i]['descripcion'] == 'Solicitud Inicio Atención'){
                                if($_SESSION['datosPacienteDau']['dau_inicio_atencion_usuario'] != ""){
                                    $usuario      = $_SESSION['datosPacienteDau']['dau_inicio_atencion_usuario'];
                                    $datosUsuario = $objUsuarios->obtenerDatosUsuario ( $objCon, $usuario );
                                    if(count($datosUsuario)>0){
                                        echo '  <input type="hidden"    id="rutMedicoTratanteEntrega"       value="'.$datosUsuario[0]['PROcodigo'].'" >
                                                <input type="hidden"    id="codigoMedicoTratanteEntrega"    value="'.$datosUsuario[0]['usu_barcode_key'].'" >';
                                    }else{
                                        $usuario      = $_SESSION['datosPacienteDau']['dau_inicio_atencion_usuario'];
                                        $datosUsuario2 = $objUsuarios->obtenerDatosUsuarioTecnicoENF( $objCon, $usuario );
                                        echo '  <input type="hidden"    id="rutMedicoTratanteEntrega"       value="'.$datosUsuario2[0]['rutusuario'].'" >
                                                <input type="hidden"    id="codigoMedicoTratanteEntrega"    value="'.$datosUsuario2[0]['usu_barcode_key'].'" >';
                                    }

                                }
                            }
                        }
                        ?>
                    <div class="table-responsive-lg">
                        <table id="tbl_diarias" width="100%" class="table table-hover" style="font-size: 10px; margin-bottom: 0rem !important;">
                            <tbody id="tbody_diarias"> 
                            <?php for($i=0;$i<count($listadoIndicaciones);$i++){
                                switch ($listadoIndicaciones[$i]['estado']) {
                                    case 1:
                                    case 7:
                                        $clase_seleccionada = "seleccionable";
                                        $color = "color: #0069d9 !important;";
                                        break;
                                    case 4:
                                        $clase_seleccionada = "restringida";
                                        $color = "color: #28a745 !important;";
                                        break;
                                    case 6:
                                        $clase_seleccionada = "restringida";
                                        $color = "color: #d9000a !important;";
                                        break;
                                }?>                   
                                <tr>
                                    <td class="my-1 py-1 mx-1 px-1 mifuente10 text-center" style="vertical-align:middle;">
                                        <svg aria-hidden="true" focusable="false" data-prefix="fad" style="<?=$color;?>" data-icon="grip-lines-vertical" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512" class="svg-inline--fa fa-grip-lines-vertical fa-w-8 fa-3x"><g class="fa-group"><path fill="currentColor" d="M224,16V496a16,16,0,0,1-16,16H176a16,16,0,0,1-16-16V16A16,16,0,0,1,176,0h32A16,16,0,0,1,224,16Z" class="fa-secondary"></path><path fill="currentColor" d="M96,16V496a16,16,0,0,1-16,16H48a16,16,0,0,1-16-16V16A16,16,0,0,1,48,0H80A16,16,0,0,1,96,16Z" class="fa-primary"></path></g></svg>
                                    </td>
                                    <td class="my-1 py-1 mx-1 px-1 mifuente10 text-center" style="vertical-align:middle;">
                                        <b><i><?php 
                                        if($listadoIndicaciones[$i]['servicio']==4){ echo 'Otros'; 
                                        }else if($listadoIndicaciones[$i]['servicio']==6){  echo 'Procedimiento';
                                        }else{
                                            $tipoSolicitud = explode("Solicitud ", $listadoIndicaciones[$i]['descripcion']);
                                            echo $tipoSolicitud[1];
                                        }?> (<?php 
                                        if($listadoIndicaciones[$i]['descripcion'] == "Solicitud Laboratorio"){
                                            if ( $listadoIndicaciones[$i]['estado'] == 1 && ($listadoIndicaciones[$i]['fechaTomaMuestra'] == ' ' || $listadoIndicaciones[$i]['fechaTomaMuestra'] == NULL) && !solicitudCanceladaPreviamente($listadoIndicaciones[$i]['sol_id']) ){
                                                echo $listadoIndicaciones[$i]['estadoDescripcion'];
                                            } else if ($listadoIndicaciones[$i]['estado'] == 1 && ($listadoIndicaciones[$i]['fechaTomaMuestra'] != ' ' && $listadoIndicaciones[$i]['fechaTomaMuestra'] != NULL ) ) {
                                                echo $listadoIndicaciones[$i]['estadoDescripcion'].'<br>(Toma Muestra)';
                                            }else if ($listadoIndicaciones[$i]['estado'] == 1 && ($listadoIndicaciones[$i]['fechaTomaMuestra'] == ' ' || $listadoIndicaciones[$i]['fechaTomaMuestra'] == NULL ) && solicitudCanceladaPreviamente($listadoIndicaciones[$i]['sol_id'])) {
                                                echo $listadoIndicaciones[$i]['estadoDescripcion'].'<br>(M. Cancelada)';
                                            }else if ($listadoIndicaciones[$i]['estado'] == 7 && ($listadoIndicaciones[$i]['fechaTomaMuestra'] != ' ' && $listadoIndicaciones[$i]['fechaTomaMuestra'] != NULL ) ) {
                                                echo 'Solicitado<br>(Recepcionado)';
                                            } else {
                                                echo $listadoIndicaciones[$i]['estadoDescripcion'];
                                            }
                                        }else{
                                            echo $listadoIndicaciones[$i]['estadoDescripcion'];
                                        }?>)<br>
                                        <label style="font-size: 9px; color: cornflowerblue;">
                                        <?php if(!is_null($listadoIndicaciones[$i]['fechaInserta']) && !empty($listadoIndicaciones[$i]['fechaInserta'])){
                                            echo date("d-m-Y H:i:s",strtotime($listadoIndicaciones[$i]['fechaInserta']));
                                        }?>                                            
                                        </label>
                                        </i></b>
                                    </td>
                                    <td class="my-1 py-1 mx-1 px-1 mifuente10 text-center" style="vertical-align:middle;">
                                        <label id="label_nombre" class="d-inline-block" style="max-width: 250px;">
                                            <?php
                                            if (!empty($listadoIndicaciones[$i]['Prestacion'])) {
                                                echo ucwords($listadoIndicaciones[$i]['Prestacion']);
                                                echo '<br>';
                                            }
                                            if (!empty($listadoIndicaciones[$i]['descripcionClasificacion'])) {
                                                echo '( ' . ucwords($listadoIndicaciones[$i]['descripcionClasificacion']) . ' )';
                                            }
                                            ?>
                                        </label>                                    
                                    </td>
                                    <td width="100px;" class="my-1 py-1 mx-1 px-1 encabezado text-center" style="vertical-align:middle;">
                                        <?php if( $listadoIndicaciones[$i]['descripcion'] == "Solicitud Especialista Otros" ){ ?>
                                        <button style="padding: .275rem .35rem !important; border-color: #28a74500!important;" type="button" id="btnVerSolicitudEspecialista<?php echo $listadoIndicaciones[$i]['sol_id']; ?>" name="btnVerSolicitudEspecialista" class="btn mifuente16 btn-outline-primary btnVerSolicitudEspecialistaOtros"><i class="fa fa-search mifuente13"></i></button>
                                        <input type="hidden"  class="form-control" id="estadoSolicitudEspecialista<?php echo $listadoIndicaciones[$i]['sol_id'] ?>" name="estadoSolicitudEspecialista<?php echo $listadoIndicaciones[$i]['sol_id']; ?>" value="<?php echo $listadoIndicaciones[$i]['estado'];?> ">
                                    <?php }else if($listadoIndicaciones[$i]['descripcion'] == "Solicitud Especialista" ){ ?>
                                        <button style="padding: .275rem .35rem !important; border-color: #28a74500!important;" type="button" id="btnVerSolicitudEspecialista<?php echo $listadoIndicaciones[$i]['sol_id']; ?>" name="btnVerSolicitudEspecialista" class="btn mifuente16 btn-outline-primary btnVerSolicitudEspecialista"><i class="fa fa-search mifuente13"></i></button>
                                        <input type="hidden"  class="form-control" id="estadoSolicitudEspecialista<?php echo $listadoIndicaciones[$i]['sol_id'] ?>" name="estadoSolicitudEspecialista<?php echo $listadoIndicaciones[$i]['sol_id']; ?>" value="<?php echo $listadoIndicaciones[$i]['estado'];?> ">
                                    <?php } else if($listadoIndicaciones[$i]['descripcion'] == "Solicitud Inicio Atención"){ ?>
                                        <button style="padding: .275rem .35rem !important; border-color: #28a74500!important;" type="button" id="btnVerSolicitudInicioAtención<?php echo $listadoIndicaciones[$i]['sol_id']; ?>" name="btnVerSolicitudInicioAtención" class="btn mifuente16 btn-outline-primary btnVerSolicitudInicioAtención"><i class="fa fa-search mifuente13"></i></button>
                                    <?php } else if($listadoIndicaciones[$i]['descripcion'] == "Solicitud Evolución"){ ?>
                                        <button style="padding: .275rem .35rem !important; border-color: #28a74500!important;" type="button" id="btnVerSolicitudEvolucion<?php echo $listadoIndicaciones[$i]['sol_id']; ?>" name="btnVerSolicitudEvolucion" class="btn mifuente16 btn-outline-primary btnVerSolicitudEvolucion"><i class="fa fa-search mifuente13"></i></button>
                                    <?php }else if($listadoIndicaciones[$i]['descripcion'] == "Solicitud Alta Urgencia"){ ?>
                                        <button style="padding: .275rem .35rem !important; border-color: #28a74500!important;" type="button" id="<?php echo $listadoIndicaciones[$i]['sol_id']; ?>" name="btnVerSolicitudAltaUrgencia" class="btn mifuente16 btn-outline-primary btnVerSolicitudAltaUrgencia"><i class="fa fa-search mifuente13"></i></button>
                                    <?php  
                                    }else if($listadoIndicaciones[$i]['descripcion'] != 'Solicitud Alta Urgencia'){
                                        if (date("Y-m-d", strtotime($listadoIndicaciones[$i]['fechaInserta'])) < FECHA_INTEGRACION_DALCA) { ?>
                                            <button style="padding: .275rem .35rem !important; border-color: #28a74500!important;" type="button" id="<?=$listadoIndicaciones[$i]['sol_id']."-".$listadoIndicaciones[$i]['servicio']; ?>" class="btn mifuente16 btn-outline-primary verModalDetalleIndicacion" alt="Detalle Solicitud Indicación" title="Detalle Solicitud Indicación"><i class="fa fa-search mifuente13"/></button>
                                        <?php } if (date("Y-m-d", strtotime($listadoIndicaciones[$i]['fechaInserta'])) >= FECHA_INTEGRACION_DALCA && !is_null($listadoIndicaciones[$i]['idSolicitudDalca']) && !empty($listadoIndicaciones[$i]['idSolicitudDalca'])) { ?>
                                            <button style="padding: .275rem .35rem !important; border-color: #28a74500!important;" type="button" id="<?=$listadoIndicaciones[$i]['idSolicitudDalca']; ?>" class="btn mifuente16 btn-outline-primary verPDFSolicitudImagenologiaDalca" alt="Detalle Solicitud Indicación" title="Detalle Solicitud Indicación"><i class="fa fa-search mifuente13"/></button>
                                            <?php if ((int)$listadoIndicaciones[$i]['estado'] === $estadoAplicado) { ?>
                                            <button style="padding: .275rem .35rem !important; border-color: #28a74500!important;" type="button" id="<?=$listadoIndicaciones[$i]['idSolicitudDalca']; ?>" class="btn mifuente16 btn-outline-warning verInformeSolicitudImagenologiaDalca" alt="Informe Solicitud" title="Informe Solicitud"><i class="fa fa-file-pdf-o mifuente13"/></button>
                                            <?php } if ((int)$listadoIndicaciones[$i]['estado'] !== $estadoAnulado) { ?>
                                            <button style="padding: .275rem .35rem !important; border-color: #28a74500!important;" type="button" id="<?=$listadoIndicaciones[$i]['idSolicitudDalca']; ?>" class="btn mifuente16 btn-outline-success verImagenSolicitudImagenologiaDalca" alt="Informe Solicitud" title="Imagen Solicitud"><i class="fa fa-camera mifuente13"></i></button>
                                        <?php }
                                    }
                                    if ( (! $objUtil->existe($listadoIndicaciones[$i]["informe"]) || $listadoIndicaciones[$i]["informe"] == "N") && $objUtil->existe($listadoIndicaciones[$i]["urlResultado"])){ ?>
                                            <button style="padding: .275rem .35rem !important; border-color: #28a74500!important;" type="button" id="<?=$listadoIndicaciones[$i]['urlResultado']?>" class="btn mifuente16 btn-outline-warning verURLResultado" alt="Detalle Examen" title="Detalle Examen"><i class="fa fa-file-image-o" aria-hidden="true mifuente13"></i></button>
                                    <?php }
                                    if ( $objUtil->existe($listadoIndicaciones[$i]["informe"]) && $listadoIndicaciones[$i]["informe"] == "S" && $objUtil->existe($listadoIndicaciones[$i]["urlResultado"])){ ?>
                                            <button style="padding: .275rem .35rem !important; border-color: #28a74500!important;" type="button" id="<?=$listadoIndicaciones[$i]['urlResultado']?>" class="btn mifuente16 btn-outline-success verURLResultado" alt="Detalle Examen" title="Detalle Examen Validado"><i class="fa fa-file-image-o" aria-hidden="true mifuente13"></i></button>
                                    <?php }
                                    } ?>
                                    <?php 
                                    if($listadoIndicaciones[$i]['descripcion'] == "Solicitud Laboratorio") { ?>
                                            <button style="padding: .275rem .35rem !important; border-color: #28a74500!important;" type="button" id="<?=$i?>" class="btn mifuente16 btn-outline-success ampliarAll" alt="Ampliar Solicitud Laboratorio" title="Ampliar"  ><i class="fa fa-plus mifuente13"/></button>
                                        <?php 
                                        if($listadoIndicaciones[$i]['estado']=="1"){?>
                                            <button style="padding: .275rem .35rem !important; border-color: #28a74500!important;" type="button" id="<?=$listadoIndicaciones[$i]['fechaInserta']."_".$listadoIndicaciones[$i]['codigoExamen']?>" class="btn mifuente16 btn-outline-warning anularIndicacionAll" alt="Anular Solicitud Indicacion" title="Anular Solicitud Indicacion"  ><i class="fa fa-minus-circle mifuente13"/></button>
                                            <button style="padding: .275rem .35rem !important; border-color: #28a74500!important;" type="button" id="<?=$listadoIndicaciones[$i]['fechaInserta']."_".$listadoIndicaciones[$i]['codigoExamen']?>" class="btn mifuente16 btn-outline-default eliminarIndicacionAll"  alt="Eliminar Solicitud Indicacion" title="Eliminar Solicitud Indicacion"><i class="fa fa-times mifuente13"/></button>
                                            <?php 
                                        } ?>
                                    <?php 
                                    } ?>
                                    <?php if ( $listadoIndicaciones[$i]['descripcion'] != "Solicitud Inicio Atención" ) { ?>
                                        <?php if($listadoIndicaciones[$i]['estado']!=6 && $listadoIndicaciones[$i]['estado']!=4 && $listadoIndicaciones[$i]['descripcion'] != "Solicitud Alta Urgencia" && ! pacienteTieneEstadoDauCerrado($datosDAU[0]['est_id'])){?>
                                            <?php if($listadoIndicaciones[$i]['descripcion'] != "Solicitud Laboratorio"){ ?>
                                                <button style="padding: .275rem .35rem !important; border-color: #28a74500!important;" type="button" id="<?=$listadoIndicaciones[$i]['sol_id']."-".$listadoIndicaciones[$i]['servicio']?>" class="btn mifuente16 btn-outline-warning anularIndicacion" alt="Anular Solicitud Indicacion" title="Anular Solicitud Indicacion"  ><i class="fa fa-minus-circle mifuente13"/></button>
                                            <?php } ?>
                                        <?php 
                                        }
                                    }
                                    if ( $listadoIndicaciones[$i]['descripcion'] == "Solicitud Evolución" && $listadoIndicaciones[$i]['usuarioInserta'] == $_SESSION['MM_Username'.SessionName]) {
                                        ?>
                                            <button style="padding: .275rem .35rem !important; border-color: #28a74500!important;" type="button" id="<?php echo $listadoIndicaciones[$i]['sol_id']; ?>" name="<?php echo $listadoIndicaciones[$i]['sol_id']; ?>" class="btn mifuente16 btn-outline-danger eliminarSolicitudEvolucion"><i class="fa fa-times mifuente13"></i></button>
                                        <?php
                                        }

                                    if ( $listadoIndicaciones[$i]['descripcion'] == 'Solicitud Alta Urgencia' && $listadoIndicaciones[$i]['estado'] != 6 && $listadoIndicaciones[$i]['estado'] != 4 && ! pacienteTieneEstadoDauCerrado($datosDAU[0]['est_id']) ) {

                                    ?>

                                        <button style="padding: .275rem .35rem !important; border-color: #28a74500!important;" type="button" id="<?=$listadoIndicaciones[$i]['sol_id']."-".$listadoIndicaciones[$i]['servicio']?>" class="btn mifuente16 btn-outline-warning anularIndicacion" alt="Anular Solicitud Indicacion" title="Anular Solicitud Indicacion"><i class="fa fa-minus-circle mifuente13"></i></button>

                                    <?php

                                    }
                                    ?>
                                        
                                    </td>
                                </tr>
                                <?php if ($listadoIndicaciones[$i]['descripcion'] == "Solicitud Laboratorio") { 
                                    $parametroslab = [
                                        'rce_id' => $parametros['rce_id'],
                                        'sol_lab_fechaInserta' => $listadoIndicaciones[$i]['fechaInserta'],
                                        'tubo_id' => $listadoIndicaciones[$i]['codigoExamen']
                                    ];
                                    $listadoIndicacionesLab = $objRegistroClinico->listarIndicacionesRCELab2($objCon, $parametroslab);

                                    foreach ($listadoIndicacionesLab as $indicacionLab) {
                                        list($clase_seleccionada, $color) = obtenerClaseYColor($indicacionLab['estado']);
                                        ?>
                                        <tr class="tr<?= $i ?> <?= $clase_seleccionada ?>" style="display: none;">
                                            <td class="my-1 py-1 mx-1 px-1 mifuente10 text-center align-middle">
                                                <svg class="svg-inline--fa fa-minus fa-w-14 mr-1" style="<?= $color ?>" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="minus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                    <path fill="currentColor" d="M416 208H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h384c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path>
                                                </svg>
                                            </td>
                                            <td class="my-1 py-1 mx-1 px-1 mifuente10 text-center align-middle">
                                                <?= obtenerEstadoDescripcion($indicacionLab) ?>
                                            </td>
                                            <td class="my-1 py-1 mx-1 px-1 mifuente10 text-center align-middle">
                                                <label id="label_nombre" class="d-inline-block" style="max-width: 250px;">
                                                    <?= ucwords($indicacionLab['Prestacion']) ?>
                                                    
                                                </label>
                                            </td>
                                            <td class="my-1 py-1 mx-1 px-1 mifuente10 text-center align-middle">
                                                <?= generarBotones($indicacionLab, pacienteTieneEstadoDauCerrado($datosDAU[0]['est_id'])) ?>
                                            </td>
                                        </tr>
                                    <?php }
                                }
                            }
                            ?>
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div id="div_rceDetalle_<?=$dau_id?>">

    <br>

    <div class="panel panel-default">

        <div class="container-fluid">



            <!--
            **************************************************************************
                                        Inicio Atención
            **************************************************************************
            -->

            <?php

            //Si se ha iniciado atención, no desplegar cuadros respectivos
            if ( is_null($datosDAU[0]['dau_inicio_atencion_fecha']) || empty($datosDAU[0]['dau_inicio_atencion_fecha']) ) {

                echo  '<input type="hidden" id="inicioAtencion" name="inicioAtencion" value="0" />';

            ?>

                <div class="col-md-12">

                    <form id="frm_registro_clinico" class="formularios" name="frm_registro_clinico" role="form" method="POST">

                        <!-- Campos Ocultos -->
                        <input type="hidden" id="frm_dau_id" name="frm_dau_id" value="<?=$dau_id;?>" />
                        <input type="hidden" id="frm_mot_con" name="frm_mot_con" value="<?=$datosDAU[0]['dau_motivo_consulta'];?>" />

                        <br>

                        <div class="row">

                            <!-- Botón Crear Plantilla -->
                            <?php

                            if ( pacienteTieneEstadoDauCerrado($datosDAU[0]['est_id']) ) {

                                $disabledCrearPlantilla = 'disabled';

                            }

                            ?>

                            <div class="col-md-3" style="float:right;">

                                <button type="button" id="btnCrearPlantillaInicioAtencion" name="btnCrearPlantillaInicioAtencion" type="button" class="btn btn-primary botonesActivos" <?php echo $disabledCrearPlantilla; ?> >Crear Plantilla</button>

                            </div>

                            <!-- Select Cargar Plantilla Inicio Atención -->
                            <div class="col-md-3" style="float:right;">

                                <select class="form-control" id="slc_nombrePlantilla" name="slc_nombrePlantilla">

                                    <option value="">Seleccione Plantilla</option>

                                    <?php

                                    if ( isset($_SESSION['usuarioActivo']['usuario']) ) {

                                        $parametros['idMedico'] = $_SESSION['usuarioActivo']['usuario'];

                                        $respuestaConsulta = $objRce->obtenerNombrePlantillasInicioAtencion($objCon, $parametros['idMedico']);

                                        $totalRespuestaConsulta = count($respuestaConsulta);

                                        for ($i=0; $i < $totalRespuestaConsulta ; $i++) {

                                        ?>

                                            <option value="<?php echo $respuestaConsulta[$i]['idPlantilla']; ?>" >  <?php echo $respuestaConsulta[$i]['nombrePlantilla']; ?> </option>

                                        <?php

                                        }

                                    } else {

                                        echo '<option value="" selected>Iniciar Sesión para Cargar Plantillas</option>';

                                    }

                                    ?>

                                </select>

                            </div>

                        </div>

                        <br>

                        <hr class="hr-custom">

                        <br>

                        <div class="row">

                            <!-- Motivo Consulta -->
                            <div class="col-md-6">

                                <label class="encabezado">Motivo Consulta</label>

                                <textarea class="form-control ingresosRCE" rows="5" id="frm_rce_motivoConsulta" name="frm_rce_motivoConsulta" placeholder="Motivo Consulta" maxlength="500"><?=$rsRce[0]['regMotivoConsulta']?></textarea>

                            </div>

                            <!-- Hipóstesis Inicial -->
                            <div class="col-md-6">

                                <label class="encabezado">Hipótesis Diagnóstica Inicial</label>

                                <textarea class="form-control ingresosRCE" rows="5" id="frm_rce_hipotesisInicial" name="frm_rce_hipotesisInicial" placeholder="Hipótesis Diagnóstica Inicial" maxlength="500"><?=$rsRce[0]['regHipotesisInicial']?></textarea>

                            </div>


                        </div>

                        <br>

                        <div class="row">

                            <input type="hidden" id="viajeOProcedencia" value="<?php echo $rsRce[0]['dau_viaje_epidemiologico']; ?>">

                            <input type="hidden" id="pais" value="<?php echo $rsRce[0]['dau_pais_epidemiologia']; ?>">

                            <input type="hidden" id="observacion" value="<?php echo $rsRce[0]['dau_observacion_epidemiologica']; ?>">

                            <!-- Viaje o procedencia del extranjero -->
                            <div id="" class="col-md-12">

                                <label for="" class="control-label encabezado">¿Viaje o procedencia del extranjero en el último mes?</label>

                                <div class="input-group">

                                    <span class="input-group-addon"><i class="glyphicon glyphicon-adjust mifuente13"></i></span>

                                    <select id="frm_viajeEpidemiologico" name="frm_viajeEpidemiologico" class="form-control" >

                                        <option value="" selected disabled>Seleccione Opción</option>

                                        <option value="N">No</option>

                                        <option value="S">Si</option>

                                    </select>

                                </div>

                            </div>

                        </div>

                        <br />

                        <div class="row">

                            <!-- País -->
                            <div id="divPaisEpidemiologia" class="col-md-6">

                                <label for="" class="control-label encabezado">País</label>

                                <div class="input-group">

                                    <span class="input-group-addon"><i class="glyphicon glyphicon-flag mifuente13"></i></span>

                                    <select class="form-control" id='frm_paisEpidemiologia' name="frm_paisEpidemiologia">

                                        <option value="" selected disabled="disabled">Seleccione País</option>

                                        <?php
                                        for ( $i = 0; $i < count($cargarPaisEpidemiologia); $i++ ) {
                                        ?>

                                            <option value="<?php echo $cargarPaisEpidemiologia[$i]['NACcodigo']; ?>"><?php echo $cargarPaisEpidemiologia[$i]['NACpais']; ?></option>

                                        <?php
                                        }
                                        ?>

                                    </select>

                                </div>

                            </div>

                            <!-- Observaciones -->
                            <div id="divObservacionesEpidemiologia" class="col-md-6">

                                <label for="" class="control-label encabezado">Observaciones</label>

                                <div class="input-group">

                                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil mifuente13"></i></span>

                                    <input onkeypress="return limitaCampoTexto(event, 500, 'frm_observacionEpidemiologica');" onkeyup="actualizaInfoTexto(500, 'frm_observacionEpidemiologica', 'info_frm_observacionEpidemiologica')" onDrop="return false" maxlength="500" id="frm_observacionEpidemiologica" onDrop="return false" type="text" class="form-control" name="frm_observacionEpidemiologica" placeholder="Ingrese Observación" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;">

                                </div>

                                <div style = "margin-left: 1%;">

                                    <p style="font-size: 12px; color: #606060" id="info_frm_observacionEpidemiologica">

                                        Máximo 500 caracteres <span id="maximo"></span>

                                    </p>

                                </div>

                            </div>

                        </div>

                        <br />

                        <div class="row">

                            <!-- Alcoholemia -->
                            <div class="col-md-3" >

                                <label class="encabezado">Alcoholemia</label>

                                <div class="rbalc">

                                    <div class="radio-inline">

                                        <label class="radio" style="font-weight: normal">

                                            <input id="rdbtn_alcoh_si" name="frm_rcedetalle_rbalc" type="radio" value="Si"<?php if($datosDAU[0]['dau_alcoholemia_numero_frasco']!=''){echo "selected";}?> />

                                            Si

                                        </label>

                                    </div>

                                    <div class="radio-inline">

                                        <label class="radio" style="font-weight: normal">

                                            <input id="rdbtn_alcoh_no" name="frm_rcedetalle_rbalc" type="radio" value="No" <?php if($datosDAU[0]['dau_alcoholemia_numero_frasco']==''){echo "selected";}?>/>

                                            No

                                        </label>

                                    </div>

                                </div>

                            </div>

                            <?php

                            if ( pacienteTieneEstadoDauCerrado($datosDAU[0]['est_id']) ) {

                                $disabledIniciarAtencion = 'disabled';

                            }

                            ?>

                            <!-- Botón Guardar -->
                            <div class="col-md-3" style="float:right;">

                                <button type="button" id="btnGuardar" name="btnGuardar" type="button" class="btn btn-primary botonesActivos" <?php echo $disabledIniciarAtencion; ?> >Iniciar Atención</button>

                            </div>

                        </div>

                        <br>

                        <div class="row">

                            <!-- Número de Frasco -->
                            <div id="nfrAlcoh" class="col-md-3" hidden>

                                <label class="encabezado">N° de Frasco</label>

                                <input type="text" class="form-control" id="frm_rce_n_frasco" name="frm_rce_n_frasco" placeholder="N°" value="<?=$datosDAU[0]['dau_alcoholemia_numero_frasco'];?>">

                            </div>

                            <!-- Estado Etílico -->
                            <div class="col-md-3" id="estAlcoh" hidden>

                                <label class="encabezado">Estado Etílico</label>

                                <select class="form-control" id="frm_rce_est_eti" name="frm_rce_est_eti">

                                    <option value="5" disabled selected>Estado</option>

                                    <?php

                                    for ( $i=0; $i < count($rsEt) ; $i++ ) {

                                    ?>
                                        <option value="<?=$rsEt[$i]['eti_id']?>" <?php if($datosDAU[0]['dau_alcoholemia_estado_etilico']==$rsEt[$i]['eti_id']){ echo "selected";}?>> <?=$rsEt[$i]['eti_descripcion']?></option>

                                    <?php

                                    }

                                    ?>

                                </select>

                            </div>

                            <!-- Fecha y Hora -->
                            <div id="fechaAlcoh" class="col-md-3" hidden>

                                <label class="encabezado">Fecha / Hora</label>

                                <input type="text" class="form-control" id="frm_rce_alc_fech" name="frm_rce_alc_fech" placeholder="dd/mm/aa" value="<?php if($datosDAU[0]['dau_alcoholemia_fecha'] != ""){echo date("d-m-Y H:i",strtotime($datosDAU[0]['dau_alcoholemia_fecha']));}?>">

                                <input type="hidden" class="form-control" id="frm_rce_alc_fech_adm" value="<?php echo date("d-m-Y H:i",strtotime($datosDAU[0]['dau_admision_fecha']));?>">

                                <input type="hidden" class="form-control" id="frm_rce_alc_fech_act" value="<?php echo date("d-m-Y H:i");?>">

                            </div>

                            <div class="col-md-12" align="center" style="margin-bottom: 20px;">
                            </div>


                        </div>

                    </form>

                </div>

            <?php

            }//Fin IF

            ?>





<?php
function solicitudCanceladaPreviamente ( $idExamen ) {

	require_once("../../../../class/Connection.class.php");	$objCon        	= new Connection();

	require_once('../../../../class/Laboratorio.class.php');   $objLaboratorio	= new Laboratorio;

	$objCon->db_connect();

	$resultadoConsulta = $objLaboratorio->examenCanceladoPreviamente($objCon, $idExamen);

	return ( empty($resultadoConsulta[0]['sol_usuarioCancela']) || is_null($resultadoConsulta[0]['sol_usuarioCancela']) ) ? false : true;

}



function pacienteTieneEstadoDauCerrado ( $estadoDau ) {

	if ( $estadoDau != 5 && $estadoDau != 6 && $estadoDau != 7 ) {

		return false;

	}

	return true;

}



function tiempoPermitidoIndicacionEgreso( $idDau ) {

	require_once('../../../../class/Connection.class.php');     $objCon = new Connection; $objCon->db_connect();
	require_once('../../../../class/Dau.class.php'); 			$objDau = new Dau;

	$respuestaConsulta = $objDau->tiempoIndicacionEgreso($objCon, $idDau);

	if ( ! empty($respuestaConsulta['dau_indicacion_egreso_fecha']) && ! is_null($respuestaConsulta['dau_indicacion_egreso_fecha']) ) {

		$intervaloTiempo = strtotime(date("Y-m-d H:i:s")) - strtotime($respuestaConsulta['dau_indicacion_egreso_fecha']);

		$tiempoPermitido = 1800;

		return ( $intervaloTiempo > $tiempoPermitido && $respuestaConsulta['dau_indicacion_egreso'] == 4 ) ? false : true;

	}

	return true;

}
?>
<?php
function obtenerEstadoDescripcion($indicacion) {
    if ($indicacion['estado'] == 1 && (empty($indicacion['fechaTomaMuestra'])) && !solicitudCanceladaPreviamente($indicacion['sol_id'])) {
        return $indicacion['estadoDescripcion'];
    } elseif ($indicacion['estado'] == 1 && !empty($indicacion['fechaTomaMuestra'])) {
        return $indicacion['estadoDescripcion'] . '<br>(Toma Muestra)';
    } elseif ($indicacion['estado'] == 1 && empty($indicacion['fechaTomaMuestra']) && solicitudCanceladaPreviamente($indicacion['sol_id'])) {
        return $indicacion['estadoDescripcion'] . '<br>(M. Cancelada)';
    } elseif ($indicacion['estado'] == 7 && !empty($indicacion['fechaTomaMuestra'])) {
        return 'Solicitado<br>(Recepcionado)';
    }
    return $indicacion['estadoDescripcion'];
}

function generarBotones($indicacion, $estadoDau) {
    if ($indicacion['estado'] != 6 && $indicacion['estado'] != 4 && $indicacion['descripcion'] != "Solicitud Alta Urgencia" && !$estadoDau) {
        $buttons = '<button style="padding: .275rem .35rem;" type="button" id="' . $indicacion['sol_id'] . '-' . $indicacion['servicio'] . '" class="btn btn-outline-warning anularIndicacion" title="Anular Solicitud Indicacion"><i class="fa fa-minus-circle"></i></button>';
        if ($indicacion['descripcion'] == "Solicitud Laboratorio" && empty($indicacion['usuarioTomaMuestra']) && empty($indicacion['fechaTomaMuestra'])) {
            $buttons .= '<button style="padding: .275rem .35rem;" type="button" id="' . $indicacion['sol_id'] . '-' . $indicacion['servicio'] . '" class="btn btn-outline-danger eliminarIndicacion" title="Eliminar Solicitud Indicacion"><i class="fa fa-times"></i></button>';
        }
        return $buttons;
    }
    return '';
}
function obtenerClaseYColor($estado) {
    switch ($estado) {
        case 1:
        case 7:
            return ['seleccionable', 'color: #0069d9 !important;'];
        case 4:
            return ['restringida', 'color: #28a745 !important;'];
        case 6:
            return ['restringida', 'color: #d9000a !important;'];
        default:
            return ['', ''];
    }
}
?>