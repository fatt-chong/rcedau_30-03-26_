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
$fechaHora                     = date("Y-m-d");
$horaFecha                     = date("H:i:s");
$intervaloTiempo               = strtotime(date("Y-m-d H:i:s")) - strtotime($listarIndicaciones[0]['dau_indicacion_egreso_fecha']);
$tiposPostIndiacionEgreso      = $objDetalleDau->obtenerPostIndicacionEgreso($objCon);
$totalTiposPostIndiacionEgreso = count($tiposPostIndiacionEgreso);
?>



<!-- 
################################################################################################################################################
                                                       			JS
-->
<script>
    validar("#frm_hora_date", "numero");

    var atencion_fecha  = $('#inpH_indicacion_egreso_fecha').val();
    var atencion_hora   = $('#inpH_indicacion_egreso_hora').val();
    var horaActual      = $('#inpH_horaActual').val();
    var FechaActual     = $('#inpH_FechaActual').val();

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
</script>



<!-- 
################################################################################################################################################
                                                       	FORMULARIO APLICAR INDICACION EGRESO
-->
<div class="panel-body">

    <form id="frmIndicacionAplica" name="frmIndicacionAplica" class="formularios" role="form" method="POST">
        <div class="row mb-2">
            <label class="text-secondary ml-3"><i class="fas fa-minus mr-1" style="color: #59a9ff;"></i> Aplicar Indicación de Egreso</label>
        </div>
        <fieldset>
            
            <div class="col-md-12" >          
            
                
                <!-- 
                **************************************************************************
                            FECHA Y HORA EN LA QUE SE APLICARÁ LA INDICACIÓN
                **************************************************************************
                -->
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



                <!-- 
                **************************************************************************
                                DESPLIEGUE INFORMACIÓN DE INDICACIÓN
                **************************************************************************
                -->
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
                <!-- 
                **************************************************************************
                                                CAMPOS OCULTOS
                **************************************************************************
                -->
                <input type="hidden"        name="inpH_indicacion_egreso_fecha"     id="inpH_indicacion_egreso_fecha"       value="<?=$indicacion_egreso_fecha?>" >
                <input type="hidden"        name="inpH_indicacion_egreso_hora"      id="inpH_indicacion_egreso_hora"        value="<?=$indicacion_egreso_hora?>" >
                <input type="hidden"        name="inpH_horaActual"                  id="inpH_horaActual"                    value="<?=$horaFecha?>">
                <input type="hidden"        name="inpH_FechaActual"                 id="inpH_FechaActual"                   value="<?=$fechaHora?>">                  
        
            </div>
        
        </fieldset>

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

</div>