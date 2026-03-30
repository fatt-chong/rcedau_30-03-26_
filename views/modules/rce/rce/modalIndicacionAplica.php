<?php
session_start();
error_reporting(0);
require_once ("../../../../class/Util.class.php");      $objUtil        = new Util;
require_once("../../../../class/Dau.class.php" );       $objDetalleDau  = new Dau;
require_once("../../../../class/Servicios.class.php");  $objServicio    = new Servicios;
require_once("../../../../class/Connection.class.php"); $objCon         = new Connection();
require_once("../../../../class/Agenda.class.php" );    $objAgenda      = new Agenda;

$objCon->db_connect();
 
$parametros                    = $objUtil->getFormulario($_POST);
$listarIndicaciones            = $objDetalleDau->listarIndicaciones($objCon,$parametros);
$obtenerEstadosIndicaciones    = $objDetalleDau->obtenerEstadosIndicaciones($objCon,$parametros);
$obtenerIndicacionEgreso       = $objDetalleDau->obtenerIndicacionEgreso($objCon,$parametros);
$obtenerServiciosDau           = $objServicio->obtenerServicioDau2($objCon,$parametros);
$rsAltaDerivacion              = $objDetalleDau->getAltaDerivacion($objCon);
$rsAps                         = $objDetalleDau->getAPS($objCon);
$resEspecialidad               = $objAgenda->getEspecialidad($objCon);
$parametros['id_usuario']      = $listarIndicaciones[0]['dau_ind_usuario_indica'];
$getNombreUsuario              = $objDetalleDau->getUsuarioNombre($objCon,$parametros);
$indicacion_egreso_fecha       = date("Y-m-d",strtotime($_SESSION['datosPacienteDau']['dau_indicacion_egreso_fecha']));
$indicacion_egreso_hora        = date("H:i:s", strtotime($_SESSION['datosPacienteDau']['dau_indicacion_egreso_fecha']));
$getHorarioServidor = $objUtil->getHorarioServidor($objCon);
$fechaHora                     = $getHorarioServidor[0]['fecha'];
$horaFecha                     = $getHorarioServidor[0]['hora'];
$intervaloTiempo               = strtotime(date($fechaHora." ".$horaFecha)) - strtotime($listarIndicaciones[0]['dau_indicacion_egreso_fecha']);
$tiposPostIndiacionEgreso      = $objDetalleDau->obtenerPostIndicacionEgreso($objCon);
$totalTiposPostIndiacionEgreso = count($tiposPostIndiacionEgreso);
?>



<!-- 
################################################################################################################################################
                                                       			JS
-->
<script>
    // function pacienteTieneIndicacionesNoSuperfluas ( ) {
    //     let respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/gestion_dau/detalle_dau/main_controller.php`, `idDau=${idDau}&accion=pacienteTieneIndicacionesNoSuperfluas`, 'POST', 'JSON', 1);
    //     switch ( respuestaAjaxRequest.status ) {
    //         case "success":
    //             if ( respuestaAjaxRequest.solicitudesAplicadas == 1 ) {
    //                 return true;
    //             } else {
    //                 modalMensaje("Error al Aplicar Egreso", "El paciente presenta solicitudes de Procedimiento, Tratamientos y/u Otros aún no aplicados<br><br>", "error_aplicar_egreso", 500, 300);
    //             }
    //         break;
    //         case "error":
    //             modalMensaje("Error en el proceso", "Error en verificar si paciente presenta solicitudes aún no aplicadas:<br><br>"+respuestaAjaxRequest.message, "error_aplicar_egreso", 500, 300);
    //         break;
    //         default:
    //             modalMensaje("Error generico", respuestaAjaxRequest, "error_aplicar_egreso", 400, 300);
    //         break;
    //     }
    //     return false;
    // }
    validar("#frm_hora_date", "numero");
    var atencion_fecha  = $('#inpH_indicacion_egreso_fecha').val();
    var atencion_hora   = $('#inpH_indicacion_egreso_hora').val();
    var horaActual      = $('#inpH_horaActual').val();
    var FechaActual     = $('#inpH_FechaActual').val();
    var dau_id          = $('#dau_id').val();
    var tipoMapa        = $('#tipoMapa').val();
    var pacienteId      = $('#pacienteId').val();
    $("#frm_fecha_date").change(function(){
        var fecha = $('#frm_fecha_date').val();
        if (fecha >  atencion_fecha && fecha < FechaActual) {
            $("#frm_hora_date").attr({
                "max" : '23:59:59',
                "min" : '00:00:00'
            }); 
        }
        else if(fecha == atencion_fecha){
            $("#frm_hora_date").val(atencion_hora);
            if (atencion_fecha == FechaActual) {
                $("#frm_hora_date").attr({
                    "max" : horaActual,
                    "min" : atencion_hora
                }); 
            }
            else{
                $("#frm_hora_date").attr({
                    "max" : '23:59:59',
                    "min" : atencion_hora
                }); 
            }
        }
        else if(fecha == FechaActual){
            $("#frm_hora_date").val(horaActual);
            $("#frm_hora_date").attr({
                "max" : horaActual,
                "min" : '00:00:00'
            });
        }
    });
    $("#frm_hora_date").keypress(function(e){
        if(e.keyCode == 13){
            cambiarFormaDigitacionHora('frm_hora_date');
            let fecha = $('#frm_fecha_date').val(); 
            let hora  = $('#frm_hora_date').val();

            if ( fecha == FechaActual && hora > horaActual ) {
                $("#frm_hora_date").val(horaActual); 
            } else if ( fecha == atencion_fecha && hora < atencion_hora ) {
                $("#frm_hora_date").val(atencion_hora);            
            }    
        }           
    });
    $("#frm_hora_date").change(function(e){
        cambiarFormaDigitacionHora('frm_hora_date');
        let fecha = $('#frm_fecha_date').val(); 
        let hora  = $('#frm_hora_date').val();
        if ( fecha == FechaActual && hora > horaActual ) {
            $("#frm_hora_date").val(horaActual); 
        } else if ( fecha == fechaSala && hora < horaSala ) {
            $("#frm_hora_date").val(horaSala);            
        }        

    });

    $('#btnRegistrar').on('click', function(){
        var confirmarAplicarIndicacionEgreso = function(){
            let funcion = function ( ) {
                const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/dau/main_controller.php`, $("#frmIndicacionAplica").serialize()+`&dau_id=${dau_id}&paciente_id=${pacienteId}&accion=registrarIndicacionAplica`, 'POST', 'JSON', 1, 'Cerrando DAU...');
                switch ( respuestaAjaxRequest.status ) {
                    case "success":
                        modalFormulario_noCabecera('', raiz+"/views/modules/rce/rce/ver_rce.php", `dau_id=${dau_id}&banderaLlamada=altaUrgencia`, "#detalle_rce_pdf", "modal-lg", "", "fas fa-plus",'');
                     if (respuestaAjaxRequest.message != '') {
                         if ( respuestaAjaxRequest.typeMessage != '' ) {
                            texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-ban throb2 text-danger mr-1" style="font-size:29px"></i>ATENCIÓN </h4>  <hr>  <p class="mb-0">'+respuestaAjaxRequest.message+'.</p></div>';
                         } else {
                             texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-ban throb2 text-danger mr-1" style="font-size:29px"></i>ATENCIÓN </h4>  <hr>  <p class="mb-0">'+respuestaAjaxRequest.message+'.</p></div>';
                         }
                         ajaxContent(raiz+'/views/modules/mapa_piso_full/detalle_dau/detalle_dau.php','dau_id='+dau_id+'&tipoMapa='+tipoMapa,'#contenido','', true);
                     } else {
                        ajaxContent(raiz+'/views/modules/mapa_piso_full/detalle_dau/detalle_dau.php','dau_id='+dau_id+'&tipoMapa='+tipoMapa,'#contenido','', true);
                     }
                     $('#modalmodalIndicacionAplica').modal( 'hide' ).data( 'bs.modal', null );
                    break;
                    case "warning":
                        texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-ban throb2 text-danger mr-1" style="font-size:29px"></i>Error al aplicar Egreso </h4>  <hr>  <p class="mb-0">'+respuestaAjaxRequest.message+'.</p></div>';
                        modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                    break;
                    case "error":
                        ErrorSistemaDefecto();
                    break;
                    default:
                        ErrorSistemaDefecto();
                    break;
                }
            }
            if ( $("#indicacionAlta").val() == 'Alta' ) {
                 funcion();
                 return;
             }
             respujestaPermiso = ajaxRequest(raiz+'/controllers/server/categorizacion/main_controller.php', 'accion=verificarPermisoUsuario&boton=btn_indicacionAplica', 'POST', 'JSON', 1, 'Verificando permiso');
            if(respujestaPermiso){
                funcion();
            }else{
                ErrorPermiso();
            }
        };


        let respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/dau/main_controller.php`, `idDau=${dau_id}&accion=pacienteTieneIndicacionesNoSuperfluas`, 'POST', 'JSON', 1);
        switch ( respuestaAjaxRequest.status ) {
            case "success":
                if ( respuestaAjaxRequest.solicitudesAplicadas == 1 ) {
                    $.validity.start();
                    if ( $('#frm_fecha_date').val() == "" ) {
                        $('#frm_fecha_date').assert(false,'Debe Indicar la fecha de egreso');
                    }
                    if ( $('#frm_hora_date').val() == "" ) {
                        $('#frm_hora_date').assert(false,'Debe Indicar la hora de egreso');
                    }
                    result = $.validity.end();
                    if ( result.valid == false ) {
                        return false;
                    }

                    modalConfirmacionNuevo("Advertencia", "ATENCIÓN, se procedera a registrar la indicación de egreso, <b>¿Desea continuar?</b>","primary", confirmarAplicarIndicacionEgreso);
                } else {
                    texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-ban throb2 text-danger mr-1" style="font-size:29px"></i>Error al aplicar Egreso </h4>  <hr>  <p class="mb-0">El paciente presenta solicitudes de Procedimiento, Tratamientos y/u Otros aún no aplicados.</p></div>';
                    modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                }
            break;
            case "error":
                ErrorSistemaDefecto();
            break;
            default:
                ErrorSistemaDefecto();
            break;
        }
    });
</script>
<!-- 
################################################################################################################################################
                                                       	FORMULARIO APLICAR INDICACION EGRESO
-->
<form id="frmIndicacionAplica" name="frmIndicacionAplica" class="formularios mr-3 ml-3" role="form" method="POST">
    <div class="row mb-2">
        <label class="text-secondary ml-3"><i class="fas fa-minus mr-1" style="color: #59a9ff;"></i> Aplicar Indicación de Egreso</label>
    </div>
    <!-- <div class="col-md-12" >       -->
    <div class="row" hidden>
        <!-- Fecha -->
        <div class="col-md-2">
            <label class="mifuente ">Fecha de Aplicar Indicación</label>
        </div>
        <div class="col-sm-4">
            <input type="date"  class="form-control" placeholder="DD-MM-AA" id="frm_fecha_date" max="<?=$fechaHora?>" min="<?=$indicacion_egreso_fecha?>" name="frm_fecha_date" value="<?=$fechaHora;?>">
        </div>
        <!-- Hora -->
        <div class="col-md-2">
            <label class="mifuente ">Hora de Aplicar Indicación</label>
        </div>
        <div class="col-sm-4">
            <input type="input"  class="form-control" placeholder="HH:MM" id="frm_hora_date" min="<?=$indicacion_egreso_hora?>" max="<?=$horaFecha?>" name="frm_hora_date" value="<?=$horaFecha;?>" onClick="this.value=''">
        </div>
    </div>
    <div class="row" >
        <div class="col-sm-2">
            <label class="mifuente font-weight-bolder ">Fecha</label>
        </div>
        <div class="col-sm-4">
            <span class="mifuente  text-secondary">:&nbsp;&nbsp;<?=date("d-m-Y H:i:s",strtotime($listarIndicaciones[0]['dau_ind_fecha_indicada']));?></span>
        </div>                   
        <div class="col-sm-2">
            <label class="mifuente font-weight-bolder">Indicado Por</label>
        </div>
        <div class="col-sm-4">
            <?php
            if ( $getNombreUsuario[0]['nombreusuario'] != '' ) {
            ?>
                <span class="mifuente  text-secondary" >:&nbsp;&nbsp;<?=$getNombreUsuario[0]['nombreusuario']?></span>
            <?php
            } else { 
            ?>
                <span class="mifuente  text-secondary" >:&nbsp;&nbsp;<?=$listarIndicaciones[0]['dau_ind_usuario_indica']?></span>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="row" >
        <div class="col-sm-2 ">
            <label class="mifuente font-weight-bolder">Detalle</label>
        </div>
        <div class="col-sm-4">
            <span  class="mifuente  text-secondary">:&nbsp;&nbsp;<?=$listarIndicaciones[0]['ind_descripcion']?></span>
        </div>
        <div class="col-sm-2">
            <label class="mifuente  font-weight-bolder">Estado:</label>
        </div>
        <div class="col-sm-4">
            <span  class="mifuente  text-secondary">:&nbsp;&nbsp;<?=$obtenerEstadosIndicaciones[0]['est_descripcion']?></span>
        </div>
    </div>
    <div class="row" >
        <?php if ( $obtenerIndicacionEgreso[0]['dau_indicacion_egreso'] == 3 ){ ?>
            <div class="col-sm-2">
                <label class="mifuente font-weight-bolder">Indicado Egreso</label>
            </div>
            <div class="col-sm-4">
                <span class="mifuente  text-secondary">:&nbsp;&nbsp;<?=$obtenerIndicacionEgreso[0]['ind_egr_descripcion']?></span>
            </div>
            <div class="col-sm-2">
                <label class="mifuente  font-weight-bolder">Derivacion Destino</label>
            </div>
            <div class="col-sm-4">
                <span class="mifuente  text-secondary" >:&nbsp;&nbsp; 
                <?php
                for ( $i = 0; $i < count($rsAltaDerivacion) ; $i++ ) {
                    if ( $rsAltaDerivacion[$i]['alt_der_id'] == $obtenerIndicacionEgreso[0]['alt_der_id'] ) { ?>
                    <?=$rsAltaDerivacion[$i]['alt_der_descripcion']?>
                <?php } 
                }?>
                </span>
            </div>
            <?php
            if ( $obtenerIndicacionEgreso[0]['alt_der_id'] == 2 ) { 
            ?>
            <div class="col-sm-2">
                <label class="mifuente  font-weight-bolder">Especialidad Destino </label>
            </div>
            <div class="col-sm-4">
                <?php $descripcionesEspecialidad = '';
                for ( $esp = 0; $esp < count($resEspecialidad); $esp++) {
                    if ( strpos($obtenerIndicacionEgreso[0]['dau_ind_especialidad'], $resEspecialidad[$esp]['ESPcodigo']) !== false ){
                        if ( empty($descripcionesEspecialidad) || is_null($descripcionesEspecialidad) ) {
                            $descripcionesEspecialidad = $resEspecialidad[$esp]['ESPdescripcion'];
                            continue;
                        }
                        $descripcionesEspecialidad = $descripcionesEspecialidad.' - '.$resEspecialidad[$esp]['ESPdescripcion'];
                    }
                }?>
                <span class="mifuente  text-secondary" >:&nbsp;&nbsp;<?=$descripcionesEspecialidad?></span>
            </div>
            <?php } if ( $obtenerIndicacionEgreso[0]['alt_der_id'] == 3 ) { 
            ?>
            <div class="col-sm-2">
                <label class="mifuente  font-weight-bolder">APS Destino</label>
            </div>
            <div class="col-sm-4">
                <span class="mifuente  text-secondary" >:&nbsp;&nbsp;
                <?php for ( $i = 0; $i < count($rsAps) ; $i++ ) {
                    if ( $rsAps[$i]['ESTAcodigo'] == $obtenerIndicacionEgreso[0]['dau_ind_aps'] ) { ?>
                        <?=$rsAps[$i]['ESTAdescripcion']?>
                    <?php } 
                }?>
                </span>
            </div>
            <?php }
        }
        if ( $obtenerIndicacionEgreso[0]['dau_indicacion_egreso'] == 4 ) { ?>
            <div class="col-sm-2">
                <label class="mifuente  font-weight-bolder">Indicado Egreso</label> 
            </div>
            <div class="col-sm-4">
                <span class="mifuente  text-secondary" >:&nbsp;&nbsp;<?=$obtenerIndicacionEgreso[0]['ind_egr_descripcion']?></span>
            </div>
            <div class="col-sm-2">
                <label class="mifuente  font-weight-bolder">Servicio Destino</label>
            </div>
            <div class="col-sm-4">
                <span class="mifuente  text-secondary" >:&nbsp;&nbsp;<?=$obtenerServiciosDau[0]['servicio']?></span>
                <input type="hidden" name="servicio_destino" id="servicio_destino" value="<?=$obtenerServiciosDau[0]['dau_ind_servicio'];?>">
            </div>
        <?php }
        if ( $obtenerIndicacionEgreso[0]['dau_indicacion_egreso'] == 5 ) { ?>
            <div class="col-sm-2">
                <label class="mifuente  font-weight-bolder">Indicado Egreso</label>
            </div>
            <div class="col-sm-4">
                <span class="mifuente  text-secondary" >:&nbsp;&nbsp;<?=$obtenerIndicacionEgreso[0]['ind_egr_descripcion']?></span>
            </div>
        <?php }
        if ( $obtenerIndicacionEgreso[0]['dau_indicacion_egreso'] == 6 ) { ?>
            <div class="col-sm-2">
                <label class="mifuente  font-weight-bolder">Indicado Egreso</label>
            </div>
            <div class="col-sm-4">
                <span class="mifuente  text-secondary" >:&nbsp;&nbsp;<?=$obtenerIndicacionEgreso[0]['ind_egr_descripcion']?></span>
            </div>
            <div class="col-sm-2">
                <label class="mifuente  font-weight-bolder">Fecha y hora de defunción</label>
            </div>
            <div class="col-sm-4">
                <span class="mifuente  text-secondary" >:&nbsp;&nbsp;<?=date("d-m-Y H:i:s",strtotime($obtenerIndicacionEgreso[0]['dau_defuncion_fecha']));?></span>
            </div>
            <div class="col-sm-2">
                <label class="mifuente  font-weight-bolder">Destino</label>
            </div>
            <div class="col-sm-4">
                <?php
                if ( $obtenerIndicacionEgreso[0]['des_id'] == 7 ) {  ?>
                <span class="mifuente  text-secondary" >:&nbsp;&nbsp;Anatomia Patologica</span>
                <?php } else if ( $obtenerIndicacionEgreso[0]['des_id'] == 8 ) { ?>
                <span class="mifuente  text-secondary" >:&nbsp;&nbsp;Servicio Medico Legal</span>
                <?php } ?>
            </div>
        <?php
        }
        if ( $obtenerIndicacionEgreso[0]['dau_indicacion_egreso'] == 7 ) {
        ?>
            <div class="col-sm-2">
                <label class="mifuente  font-weight-bolder">Indicado Egreso</label>
            </div>
            <div class="col-sm-4">
                <span class="mifuente  text-secondary" >:&nbsp;&nbsp;<?=$obtenerIndicacionEgreso[0]['ind_egr_descripcion']?></span>
            </div>
        <?php } ?>
    </div>
    <input type="hidden"        name="dau_id"                           id="dau_id"                             value="<?=$parametros['dau_id']?>" >
    <input type="hidden"        name="tipoMapa"                         id="tipoMapa"                           value="<?=$parametros['tipoMapa']?>" >
    <input type="hidden"        name="pacienteId"                       id="pacienteId"                         value="<?=$listarIndicaciones[0]['id_paciente']?>" >
    <input type="hidden"        name="inpH_indicacion_egreso_fecha"     id="inpH_indicacion_egreso_fecha"       value="<?=$indicacion_egreso_fecha?>" >
    <input type="hidden"        name="inpH_indicacion_egreso_hora"      id="inpH_indicacion_egreso_hora"        value="<?=$indicacion_egreso_hora?>" >
    <input type="hidden"        name="inpH_horaActual"                  id="inpH_horaActual"                    value="<?=$horaFecha?>">
    <input type="hidden"        name="inpH_FechaActual"                 id="inpH_FechaActual"                   value="<?=$fechaHora?>">
    <!-- </div> -->
   
    <?php
    //1800 = 30 minutos
    $tiempoPermitido = 1800;
    if ( $intervaloTiempo > $tiempoPermitido && $listarIndicaciones[0]['dau_indicacion_egreso'] == 4 ) {?>
         <hr>
    <div class="row mt-2"> 
        <div class="col-lg-2 ">
            <label class="mifuente font-weight-bolder ">Tipo Egreso Médico</label>
        </div>
        <div class="col-lg-4">
            <select class="form-control form-control-sm mifuente12" name="frm_postIndicacionEgreso" id="frm_postIndicacionEgreso"> 
                <option value="7" selected>Servicio Indicado</option>
                <?php for ( $i = 0; $i < $totalTiposPostIndiacionEgreso; $i++ ) {
                    if ( $tiposPostIndiacionEgreso[$i]['idPostIndicacionEgreso'] == 7 ) {
                        continue;
                    }
                    echo '<option value="'.$tiposPostIndiacionEgreso[$i]['idPostIndicacionEgreso'].'">'.$tiposPostIndiacionEgreso[$i]['descripcionPostIndicacionEgreso'].'</option>';
                }?>    
            </select>
        </div>  
    </div>
    <input type="hidden" name="postIndicacionEgreso" id="postIndicacionEgreso" value="true">  
    <?php } ?>
</form>
<hr>
    <div class="row">
        <div class="col-lg-9">
        </div>
        <div class="col-lg-3"> <button id="btnRegistrar" type="button" name="btnRegistrar" class="btn btn-sm btn-primary2  col-lg-12 text-center"><svg class="svg-inline--fa fa-save fa-w-14 mr-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="save" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M433.941 129.941l-83.882-83.882A48 48 0 0 0 316.118 32H48C21.49 32 0 53.49 0 80v352c0 26.51 21.49 48 48 48h352c26.51 0 48-21.49 48-48V163.882a48 48 0 0 0-14.059-33.941zM224 416c-35.346 0-64-28.654-64-64 0-35.346 28.654-64 64-64s64 28.654 64 64c0 35.346-28.654 64-64 64zm96-304.52V212c0 6.627-5.373 12-12 12H76c-6.627 0-12-5.373-12-12V108c0-6.627 5.373-12 12-12h228.52c3.183 0 6.235 1.264 8.485 3.515l3.48 3.48A11.996 11.996 0 0 1 320 111.48z"></path></svg>Registrar</button> </div>
    </div>