<?php
session_start();
error_reporting(0);
require_once("../../../../class/Util.class.php");               $objUtil             = new Util;
require_once("../../../../class/Connection.class.php");         $objCon              = new Connection();     $objCon->db_connect();
require_once("../../../../class/Dau.class.php" );               $objDau              = new Dau;
require_once("../../../../class/Categorizacion.class.php" );    $objCategorizacion   = new Categorizacion;
require_once("../../../../class/Admision.class.php" );          $objAdmision         = new Admision;
require_once("../../../../class/Parametros.class.php" );          $objParametros         = new Parametros;
require("../../../../config/config.php");
// $dau_id = null;
// $dau_id                     = $_POST['t_trid'];
$dau_id = isset($_POST['t_trid']) && !empty($_POST['t_trid']) ? $_POST['t_trid'] : null;

$idrau                      = $_POST['t_trid'];
$bandera_acceso             = $_POST['bandera_acceso'];
switch ( $bandera_acceso ) {
    case 'RAU':
        $datos = $objCategorizacion -> searchPacienterau($objCon,$idrau);
    break;
    case 'DAU':
        $datos  = $objCategorizacion -> searchPaciente($objCon,$dau_id);
    break;
}
$rsFechaHora                = $objUtil->getHorarioServidor($objCon);
$parametros['dau_id']       = $dau_id;
$datosdauFecha              = $objDau->fechaAdmision($objCon,$parametros);
$fechaAdmision              = date("Y-m-d",strtotime($datosdauFecha[0]['dau_admision_fecha']));
$horaAdmision               = date("H: i: s", strtotime($datosdauFecha[0]['dau_admision_fecha']));
$fechaHora                  = $rsFechaHora[0]['fecha'];
$horaFecha                  = $rsFechaHora[0]['hora'];
$cargarPaisEpidemiologia    = $objAdmision->listarPaisNacimiento($objCon);
$datos                      = $objAdmision->listarDatosDau($objCon,$parametros);

$parametrosInvasivos['tipo_parametros']     = 1;
$rsparametrosInvasivos                      = $objParametros->getParametros($objCon,$parametrosInvasivos);

$version                    = $objUtil->versionJS();
?>

<script type="text/javascript">
    function limitar ( limite, campo ) {
        if ( $("[name='"+campo+"']").val() > limite ) {
            $("[name='"+campo+"']").val(limite);
        }
    }
    function limitartemp ( minimo, campo ) {
        if ( $("[name='"+campo+"']").val() < minimo ) {
            $("[name='"+campo+"']").val(minimo);
        }
    }
</script>

<style type="text/css">
    .ButtonC2{
    background-color :#d97e43;
    }
    .ButtonC4{
    background-color :#6bd943;
    }
    .ButtonC5{
    background-color :#435fd9;
    }
    .tituloNegrita{
        font-weight: 500;
        color: #24262d;
    }
</style>

<input type = "hidden" name = "frm_saturometria"        id = "frm_saturometria"         value = "0">
<input type = "hidden" name = "frecuencia_respiratorio" id = "frecuencia_respiratorio"  value = "0">
<input type = "hidden" name = "frecuencia_cardiaca"     id = "frecuencia_cardiaca"      value = "0">
<input type = "hidden" name = "frm_val_saturo"          id = "frm_val_saturo"           value = "0">
<input type = "hidden" name = "frm_val_freres"          id = "frm_val_freres"           value = "0">
<input type = "hidden" name = "frm_val_frecer"          id = "frm_val_frecer"           value = "0">

<form class="form-horizontal" id="form-cat" name="form-cat" style="margin-bottom: 0px;">
    <div class="row" >
        <input type="hidden" id="dau_cat_considerada" name="dau_cat_considerada">
        <div class="col-md-4">
            <label style="font-size: 18px; font-weight: 500;">Categorizar Paciente</label>
        </div>
        <div class="col-md-4 text-center"> 
            <div id="esiDiv" class="text-center  ">
                <div id="esiAlert" align="center" class=" text-center alert" style="padding:0rem !important; margin-bottom : 0rem !important;">
                    <label style="color: #fff; margin-bottom: 0rem !important;">C</label><label id="esiPac" style="color: #fff;margin-bottom: 0rem !important;"></label>
                    <input type="hidden" id="frm_valor_esi" name="frm_valor_esi" value="3">
                </div>
            </div> 
        </div>
        <div class="col-md-4">
            <div id="nuevoAlert" class="form-inline">
                &nbsp;&nbsp;<strong id="esiNuevo">Se considera Categorizacion: C<label id="nuevoEsi" ></label></strong>
            </div>
        </div>
    </div>
    <hr style="margin-bottom : 0.1rem; margin-top: 0.1rem">

    <div class="bd-callout bd-callout-warning ">
        <div class="row pr-2 pl-2">
            <div class="col-lg-1 ">
                <p class="m-0 p-0 mifuente">DAU</p>
            </div>
            <div class="col-lg-2 ">
                <p class="m-0 p-0 mifuente">:<label name="dau_id" id="dau_id" class="ml-2 texto-valor mb-0 " ><?=$dau_id?></label></p>

                <input type="hidden" name="inp_recat" id="inp_recat" value="<?=$_POST['recat']?>">
            </div>
            <div class="col-lg-1 ">
                <p class="m-0 p-0 mifuente">Nombre</p>
            </div>

            <div class="col-lg-4 ">
                <p class="m-0 p-0 mifuente">:<label class="ml-2 texto-valor mb-0 " ><?=$datos[0]['nombres']." ".$datos[0]['apellidopat']." ".$datos[0]['apellidomat']?></label> </p>
            </div>
            <div class="col-lg-1">
                <p class="m-0 p-0 mifuente">Edad </p>
            </div>

            <div class="col-lg-3 ">
                <p class="m-0 p-0 mifuente">:<label id="edadPac" class="ml-2 texto-valor mb-0 " ><?php echo $objUtil->edadActualCompleto($datos[0]['fechanac']); ?></label> </p>
            </div>
            <div class="col-lg-1">
                <p class="m-0 p-0 mifuente">Motivo </p>
            </div>

            <div class="col-lg-11 ">
                <p class="m-0 p-0 mifuente">:<label id="edadPac" class="ml-2 texto-valor mb-0 " >
                <?php
                if ( $bandera_acceso == "DAU" ) { 
                    echo $datos[0]['dau_motivo_descripcion']; 
                } else{
                    echo $datos[0]['motivoconsulta']; 
                }
                ?>
                </label> </p>
            </div>
            <label id="añoPac" hidden><?php echo $objCategorizacion->edadAno($datos[0]['fechanac']); ?></label>
            <label id="mesPac" hidden><?php echo $objCategorizacion->edadMes($datos[0]['fechanac']); ?></label>
            <label id="diasPac" hidden><?php echo $objCategorizacion->edadDia($datos[0]['fechanac']); ?></label>
            <input type="hidden" name="est_id" id="est_id" value="<?=$datos[0]['est_id']?>">
            <label id="rutpac" hidden><?=$datos[0]['rut']?></label>
            <label id="bandera" hidden><?php echo $bandera_acceso?></label>
        </div>
    </div>
    <hr style="margin-bottom : 0.1rem; margin-top: 0.1rem">
    <div class="row">
        <div class="col-lg-12">
            <input type="hidden" id="viajeOProcedencia" value="<?php echo $datos[0]['dau_viaje_epidemiologico']; ?>">
            <input type="hidden" id="pais" value="<?php echo $datos[0]['dau_pais_epidemiologia']; ?>">
            <input type="hidden" id="observacion" value="<?php echo $datos[0]['dau_observacion_epidemiologica']; ?>">
            <div class="row">
                <div id="divViajeEpidemiologico" class="col-md-4">
                    <div class="form-group" style="text-align:center;">
                        <label for="frm_viajeEpidemiologico" class="control-label mifuente tituloNegrita" style="text-align:center">¿Viaje o procedencia del extranjero en el último mes?</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-adjust"></i></span>
                            <select id="frm_viajeEpidemiologico" name="frm_viajeEpidemiologico" class="form-control form-control-sm mifuente" >
                                <option value="" selected disabled>Seleccione Opción</option>
                                <option value="N">No</option>
                                <option value="S">Si</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="divPaisEpidemiologia" class="col-md-3">
                    <div class="form-group" style="text-align:center;">
                        <label for="frm_viajeEpidemiologico" class="control-label mifuente tituloNegrita" style="text-align:center">País</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-adjust"></i></span>
                            <select id="frm_paisEpidemiologia" name="frm_paisEpidemiologia" class="form-control form-control-sm mifuente" >
                                <option value="" selected disabled="disabled">Seleccione País</option>
                                <?php for ( $i = 0; $i < count($cargarPaisEpidemiologia); $i++ ) { ?>
                                <option value="<?php echo $cargarPaisEpidemiologia[$i]['NACcodigo']; ?>"><?php echo $cargarPaisEpidemiologia[$i]['NACpais']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="divObservacionesEpidemiologia" class="col-md-5">
                    <div class="form-group" style="text-align:center;">
                        <label for="frm_viajeEpidemiologico" class="control-label mifuente tituloNegrita" style="text-align:center">Observaciones</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                            <input onkeypress="return limitaCampoTexto(event, 500, 'frm_observacionEpidemiologica');" onkeyup="actualizaInfoTexto2(500, 'frm_observacionEpidemiologica', 'info_frm_observacionEpidemiologica')" onDrop="return false" maxlength="500" id="frm_observacionEpidemiologica" onDrop="return false" type="text" class="form-control form-control-sm mifuente" name="frm_observacionEpidemiologica" placeholder="Ingrese Observación">
                        </div>
                        <div style = "margin-left: 1%;">
                            <p style="font-size: 12px; color: #606060" id="info_frm_observacionEpidemiologica">
                                Máximo 500 caracteres <span id="maximo"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <hr id="e1se"  style="margin-bottom : 0.1rem; margin-top: 0.1rem">
            <div class="row" id="etapa1">
                <div  class="col-md-12">
                    <div class="form-group" style="text-align:center;">
                        <label for="frm_viajeEpidemiologico" class="control-label mifuente tituloNegrita" style="text-align:center">¿Este paciente presenta una amenaza real para su vida, requiere una intervención inmediata?</label><br>
                        <div class="form-check form-check-inline ml-3">
                          <input class="form-check-input" type="radio" name="dau_cat_1_resp" id="dau_cat_1_resp1" value="Si">
                          <label class="form-check-label mifuente" for="dau_cat_1_resp1">Si</label>
                        </div>
                        <div class="form-check form-check-inline ml-1">
                          <input class="form-check-input" type="radio" name="dau_cat_1_resp" id="dau_cat_1_resp2" value="No">
                          <label class="form-check-label mifuente" for="dau_cat_1_resp2">No</label>
                        </div>
                    </div>
                <hr id="e2se" style="margin-bottom : 0.1rem; margin-top: 0.1rem">
                </div>
            </div>
            <div class="row" id="etapa2">
                <div  class="col-md-3">
                    <div class="form-group" style="text-align:center;">
                        <label for="frm_viajeEpidemiologico" class="control-label mifuente tituloNegrita" style="text-align:center">¿El paciente posee riesgo vital?</label><br>
                        <div class="form-check form-check-inline ml-3">
                          <input class="form-check-input" type="radio" name="dau_cat_2_resp" id="dau_cat_2_resp1" value="Si">
                          <label class="form-check-label mifuente" for="dau_cat_2_resp1">Si</label>
                        </div>
                        <div class="form-check form-check-inline ml-1">
                          <input class="form-check-input" type="radio" name="dau_cat_2_resp" id="dau_cat_2_resp2" value="No">
                          <label class="form-check-label mifuente" for="dau_cat_2_resp2">No</label>
                        </div>
                    </div>
                    <div id="mensajeDist"><input type="text" id="mensajeDist" hidden></div>
                </div>
                <div class="col-md-3">
                    <div class="form-group" align="center">
                        <div class="form-group" >
                            <label class="control-label mifuente tituloNegrita">
                                Evaluación AVDI
                            </label>
                            <br>
                            <select class="form-control mifuente form-control-sm mifuente" id="e2avdi" name="dau_cat_2_avdi">
                                <option disabled selected>AVDI</option>
                                <option value="1">Alerta</option>
                                <option value="2">Respuesta Verbal</option>
                                <option value="3">Respuesta al Dolor</option>
                                <option value="4">Inconsciente</option>
                            </select>
                            <div id="mensajeAvdi"><input type="text" id="mensajeAvdi" hidden></div>
                        </div>
                    </div>
                </div>
                <div  class="col-md-3">
                    <div class="form-group" style="text-align:center;">
                        <label for="frm_viajeEpidemiologico" class="control-label mifuente tituloNegrita" style="text-align:center">¿Distresado?</label><br>
                        <div class="form-check form-check-inline ml-3">
                          <input class="form-check-input" type="radio" name="dau_cat_2_dist" id="dau_cat_2_dist1" value="Si">
                          <label class="form-check-label mifuente" for="dau_cat_2_dist1">Si</label>
                        </div>
                        <div class="form-check form-check-inline ml-1">
                          <input class="form-check-input" type="radio" name="dau_cat_2_dist" id="dau_cat_2_dist2" value="No">
                          <label class="form-check-label mifuente" for="dau_cat_2_dist2">No</label>
                        </div>
                    </div>
                    <div id="mensajeDist"><input type="text" id="mensajeDist" hidden></div>
                </div>
                <div class="col-md-3">
                    <div class="form-group" style="text-align:center;">
                        <label class="control-label mifuente tituloNegrita">
                            Escala EVA
                        </label>
                        <br>
                        <select class="form-control mifuente form-control-sm mifuente" id="e2eva" name="dau_cat_2_eva">
                            <option disabled selected>Intervalo: 0 - 10</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                        <div id="mensajeEva"><input type="text" id="mensajeEva" hidden></div>
                    </div>
                </div>
                <div class="col-md-12">

                <hr id="e2se" style="margin-bottom : 0.1rem; margin-top: 0.1rem">
                </div>
            </div>
            <div class="row" id="etapa3">
                <div  class="col-md-12">
                    <div class="form-group" style="text-align:center;">
                        <label for="frm_viajeEpidemiologico" class="control-label mifuente tituloNegrita" style="text-align:center"> ¿Cuántos recursos se necesitan para la atención?</label><br>
                        <select class="form-control mifuente form-control-sm mifuente" id="e3rec" name="dau_cat_3_resp" >
                            <option disabled selected value="0">Cantidad</option>
                            <option value="1">Ninguno</option>
                            <option value="2">Uno</option>
                            <option value="3">Varios</option>
                        </select>
                        <div id="div_nota">
                            <label class="mifuente12 text-danger"><i>NOTA: La etapa "3" No esta disponible para todo pacientes menor de 3 años.</i></label> 
                        </div>
                    </div>
                    <hr style="margin-bottom : 0.1rem; margin-top: 0.1rem">
                </div>
            </div>
            <div class="row" id="etapa4">
                <div  class="col-md-12 text-center">
                    <label for="frm_viajeEpidemiologico" class="control-label mifuente text-center tituloNegrita" > ¿Signos vitales en zona de riesgo?</label>
                </div>
                <div class="col-md-4 text-center">
                    <label class="control-label mifuente tituloNegrita">
                        Saturometría (SaO2)
                    </label>
                    <input id="e4txtSat" type="text" class="form-control form-control-sm mifuente" onkeyup="limitar(100,'dau_cat_4_satu');" name="dau_cat_4_satu" maxlength="3" placeholder="0 - 100">
                    <div id="mensajeAlert"><input type="text" id="mensajeAlert" hidden></div>
                </div>

                <div class="col-md-4 text-center">
                    <label class="control-label mifuente tituloNegrita">
                         Frecuencia Respiratoria (FR)
                    </label>
                    <input type="text" class="form-control form-control-sm mifuente" id="e4txtFR" onkeyup="limitar(100,'dau_cat_4_fr');" name="dau_cat_4_fr" maxlength="3" placeholder="0 - 100">
                    <div id="mensajeAlert2"><input type="text" id="mensajeAlert2" hidden></div>
                </div>
                <div class="col-md-4 text-center">
                    <label class="control-label mifuente tituloNegrita">
                         Frecuencia Cardiaca (FC)
                    </label>
                    <input type="text" class="form-control form-control-sm mifuente" id="e4txtFC" onkeyup="limitar(300,'dau_cat_4_fc');" name="dau_cat_4_fc" maxlength="3" placeholder="0 - 300">
                    <div id="mensajeAlert3"><input type="text" id="mensajeAlert3" hidden></div>
                </div>
                <div class="col-md-4 text-center" id="e4temp">
                    <label class="control-label mifuente tituloNegrita">
                        Temperatura (°C)
                    </label>
                    <input type="text" class="form-control form-control-sm mifuente" id="e4txtTe" onkeyup="limitar(50.0,'dau_cat_4_temp');" name="dau_cat_4_temp" maxlength="4" placeholder="20.0 - 50.0">
                    <div id="mensajeAlert4"><input type="text" id="mensajeAlert4" hidden></div>
                </div>
                <div class="col-md-4 text-center" id="e4inmudiv">
                    <label class="control-label mifuente tituloNegrita">
                        Inmunizaciones
                    </label>
                    <select class="form-control form-control-sm mifuente mifuente" id="e4inmu" name="dau_cat_4_inmu">
                        <option value="0" disabled selected>Estado</option>
                        <option value="1">Esquema Completo</option>
                        <option value="2">Esquema Incompleto</option>
                    </select>
                </div>
                <div class="col-md-4 text-center" id="e4fiebrediv">
                    <label class="control-label mifuente tituloNegrita">
                        Origen de Fiebre
                    </label>
                    <select class="form-control form-control-sm mifuente mifuente" id="e4fiebre" name="dau_cat_4_fiebre">
                        <option value="0" disabled selected>Origen</option>
                        <option value="1">Origen Determinado</option>
                        <option value="2">Origen No Evidente</option>
                    </select>
                </div>
            </div>
        
            <div id="div_botones">
                <div class="col-md-12" align="center">
                    <label>Considerar</label>
                </div>
                <div class="col-md-12" align="center">
                    <div id="div_C2" class="col-md-12">
                        <button type="button" class="btn ButtonC2" id="btn_c2" name="btn_c2" style="width: 90px;">C2</button>
                    </div>
                    <div id="div_C4" class="col-md-12">
                        <button type="button" class="btn ButtonC4" id="btn_c4" name="btn_c4" style="width: 85px;">C4</button>
                        <button type="button" class="btn ButtonC5" id="btn_c5" name="btn_c5" style="width: 85px;">C5</button>
                    </div>
                </div>
            </div>
            <div class="form-group" style="width:30%;" hidden>
                <label class="control-label ">
                    Observación
                </label>
                <textarea class="form-control" rows="5" name="dau_cat_5_obs2" style="height: 10%;"></textarea>
                <div id="mensajeAlert4"><input type="text" id="mensajeAlert4" hidden></div>
            </div>
        </div>
    </div>
    <div class="form-check float-right" id="div_indiferenciado">
        <input class="form-check-input" type="checkbox"  id="dau_indiferenciado" name="dau_indiferenciado" value="S">
        <label class="form-check-label mifuente tituloNegrita" for="dau_indiferenciado" >
        Indiferenciado
        </label>
    </div>
    <div class="row">
    <div class="form-group col-6"  >
        <label class="control-label mifuente tituloNegrita">
            Observación Enfermera
        </label>
        <textarea class="form-control form-control-sm mifuente" rows="2" name="dau_cat_obs_enfermera" placeholder="Escriba una observación"></textarea>
    </div>
    <div class="form-group col-3"  >
        <label class="control-label mifuente tituloNegrita">
            Dispositivos Invasivos 
        </label>
        <select id="frm_dispositivoinvasivo" name="frm_dispositivoinvasivo" class="form-control form-control-sm mifuente">
            <option value="" >Seleccione Opción</option>
            <?php for ( $i = 0; $i < count($rsparametrosInvasivos); $i++ ) { ?>
            <option value="<?php echo $rsparametrosInvasivos[$i]['id_parametros']; ?>"><?php echo $rsparametrosInvasivos[$i]['descripcion_parametros']; ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="form-group col-3" id="div_dispositivo_invasivo" >
         <label class="control-label mifuente tituloNegrita">
            Otro Dispositivo Invasivo
        </label>
        <input class="form-control form-control-sm mifuente" type="text" id="texto_dispositivo_invasivo" name="texto_dispositivo_invasivo" >
    </div>
</div>
</form>
<hr style="margin-top: 0.3rem; margin-bottom: 0.8rem;">
    <div class="row pr-2 pl-2">
        <div class="col-lg-4">
        </div>
        <div class="col"> <button id="btn_nea" type="button" name="btn_nea" class="btn btn-sm btn-primary2  col-lg-12 text-center" ><i class="fas fa-bullhorn mr-2 throb"></i>N.E.A.</button> </div>
        <div class="col"> <button id="btn_signos_vitales" type="button" name="btn_signos_vitales" class="btn btn-sm btn-primary2  col-lg-12 text-center" ><i class="fas fa-heartbeat mr-2 throb"></i>Signos Vitales</button> </div>
        <div class="col"> <button id="btnCategorizarESI" type="button" name="btnCategorizarESI" class="btn btn-sm btn-primary2  col-lg-12 text-center" ><i class="fas fa-edit mr-2"></i> Categorizar</button> </div>
    </div>

<script type="text/javascript">
    $(document).ready(function(){

        var edad;
        var catesi;
        var chekeado = 0;
        var catesiedit = "C0"
        var resp1 = 0;
        var resp2 = 0;
        var avdi = 0;
        var distresado = 0;
        var eva = 0;
        var saturometria = 0;
        var fresp = 0;
        var fcard = 0;
        var temperatura = 0;
        var inmunizacion = 0;
        var orifiebre = 0;
        var dau_id = $('#dau_id').text();
        var est_id = $('#est_id').val();
        var estado_saturometria = 0;
        var estado_fre_res = 0;
        var estado_fre_car = 0;
        var estado_temperatura = 0;
        var estado_inmunizaciones = 1;
        var estado_fiebre = 1;
        var estado_nomral_saturo = 1;
        var estado_nomral_freres = 1;
        var estado_nomral_frecar = 1;
        var estado_discrecion_saturo = 1;
        var estado_discrecion_freres = 1;
        var estado_discrecion_frecar = 1;
        $('#frm_val_saturo').val(0);
        $('#frm_val_freres').val(0);
        $('#frm_val_frecer').val(0);
        $('#div_indiferenciado').hide();
        $("#btn_signos_vitales").click(function(){
            modalFormulario_noCabecera('', raiz+"/views/modules/rce/signos_vitales/signos_vitales.php", 'dau_id='+dau_id+'&tipoMapa=mapaAdultoPediatrico', "#modalSignosVitales", "modal-lgg", "", "fas fa-plus",'');
        });
        const select_frm_dispositivoinvasivo = document.getElementById("frm_dispositivoinvasivo");
        const div_div_dispositivo_invasivo = document.getElementById("div_dispositivo_invasivo");
        div_div_dispositivo_invasivo.style.display = "none";
        select_frm_dispositivoinvasivo.addEventListener("change", function () {
            if (select_frm_dispositivoinvasivo.value === "3") {
                div_div_dispositivo_invasivo.style.display = "block";
            } else {
                div_div_dispositivo_invasivo.style.display = "none";
                $("#texto_dispositivo_invasivo").val("");
            }
        });
        function categorizarC3_main(){
            $("#esiDiv").show();
            catesi = "C3";

            $('#div_indiferenciado').hide();
            $("#esiAlert").css('background-color','#d9d143');
            $('label[id*="esiPac"]').text('');
            $('#esiPac').append(' 3');
            $('#div_botones').show();
            $('#div_C2').show();
            $('#div_C4').hide();
        }
        function categorizarC3_sinBotones(){
            $("#esiDiv").show();
            catesi = "C3";
            $('#div_indiferenciado').hide();
            $("#esiAlert").css('background-color','#d9d143');
            $('label[id*="esiPac"]').text('');
            $('#esiPac').append(' 3');
            $('#div_botones').hide();
            $('#div_C2').hide();
            $('#div_C4').hide();
        }
        function categorizarC2_main(){
            $("#esiDiv").show();
            catesi = "C2";
            $('#div_indiferenciado').hide();
            $("#esiAlert").css('background-color','#d97e43');
            $('label[id*="esiPac"]').text('');
            $('#esiPac').append(' 2');
            $('#div_botones').hide();
            $('#div_C2').hide();
            $('#div_C4').hide();
        }
        function ocultarBotones(){
            $("#nuevoAlert").css('background-color','#ffffff');
            $('label[id*="nuevoEsi"]').text('');
        }
        function limpiarEsi(){
            $("#esiDiv").hide();
            $("#esiAlert").css('background-color','#ffffff');
            $('label[id*="esiPac"]').text('');
            ocultarBotones();
        }
        function resetVariables(){
            estado_saturometria = 0;
            estado_fre_res  = 0;
            estado_fre_car  = 0;
            estado_temperatura  = 0;
            $('#e4txtSat').val('');
            $('#e4txtFR').val('');
            $('#e4txtFC').val('');
            $('#e4txtTe').val('');
        }
        $("#etapa2").hide();
        $("#etapa3").hide();
        $("#etapa4").hide();
        $("#e2se").hide();
        $("#e3se").hide();
        $("#e4se").hide();
        $("#esiDiv").hide();
        $("#div_botones").hide();
        $("#div_nota").hide();

        $("select[name='dau_cat_2_avdi']").attr("disabled", true);
        $("input[name='dau_cat_2_dist']").attr("disabled", true);
        $("select[name='dau_cat_2_eva']").attr("disabled", true);
        $("select[name='dau_cat_3_resp']").attr("disabled", true);
        $("input[name='dau_cat_4_satu']").attr("disabled", true);
        $("input[name='dau_cat_4_fr']").attr("disabled", true);
        $("input[name='dau_cat_4_fc']").attr("disabled", true);
        $("input[name='dau_cat_4_temp']").attr("disabled", true);
        $("select[name='dau_cat_4_inmu']").attr("disabled", true);
        $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
        $("select[name='dau_cat_5_robs']").attr("disabled", true)

        if ($('#bandera').text() == "RAU") {
            if(edad == 1){
                $("input[name='dau_cat_4_temp']").attr("disabled", false);
                $("select[name='dau_cat_4_inmu']").attr("disabled", false);
                $("select[name='dau_cat_4_fiebre']").attr("disabled", false);
                $("#e4fiebrediv").show("fast");
                $("#e4inmudiv").show("fast");
                $("#e4temp").show("fast");
            }else if(edad == 2){
                $("input[name='dau_cat_4_temp']").attr("disabled", false);
                $("select[name='dau_cat_4_inmu']").attr("disabled", false);
                $("select[name='dau_cat_4_fiebre']").attr("disabled", false);
                $("#e4fiebrediv").show("fast");
                $("#e4inmudiv").show("fast");
                $("#e4temp").show("fast");
            }else if(edad == 3){
                $("input[name='dau_cat_4_temp']").attr("disabled", true);
                $("select[name='dau_cat_4_inmu']").attr("disabled", true);
                $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
                $("#e4inmudiv").hide();
                $("#e4fiebrediv").hide();
                $("#e4temp").hide();
            }else if(edad == 4){
                $("input[name='dau_cat_4_temp']").attr("disabled", true);
                $("select[name='dau_cat_4_inmu']").attr("disabled", true);
                $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
                $("#e4inmudiv").hide();
                $("#e4fiebrediv").hide();
                $("#e4temp").hide();
            }
        }else if ($('#bandera').text() == "DAU"){
            if( $('#añoPac').text() == 0 && $('#mesPac').text() < 3){
                // < a 3 Meses
                edad = 1;
                $("input[name='dau_cat_4_temp']").attr("disabled", false);
                $("select[name='dau_cat_4_inmu']").attr("disabled", false);
                $("select[name='dau_cat_4_fiebre']").attr("disabled", false);
                $("#e4fiebrediv").show("fast");
                $("#e4inmudiv").show("fast");
                $("#e4temp").show("fast");
                // alert()
            }else if( $('#añoPac').text() < 3){
                // >= a 3 Meses / < a 3 Años
                edad = 2;
                $("input[name='dau_cat_4_temp']").attr("disabled", false);
                $("select[name='dau_cat_4_inmu']").attr("disabled", false);
                $("select[name='dau_cat_4_fiebre']").attr("disabled", false);
                $("#e4fiebrediv").show("fast");
                $("#e4inmudiv").show("fast");
                $("#e4temp").show("fast");
            }else if( $('#añoPac').text() >= 3 && $('#añoPac').text() < 8 ){
                // 3 Años / < a 8 Años
                edad = 3;
                $("input[name='dau_cat_4_temp']").attr("disabled", true);
                $("select[name='dau_cat_4_inmu']").attr("disabled", true);
                $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
                $("#e4inmudiv").hide();
                $("#e4fiebrediv").hide();
                $("#e4temp").hide();
            }else if( $('#añoPac').text() >= 8 ){
                // > 8 Años
                edad = 4;
                $("input[name='dau_cat_4_temp']").attr("disabled", true);
                $("select[name='dau_cat_4_inmu']").attr("disabled", true);
                $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
                $("#e4inmudiv").hide();
                $("#e4fiebrediv").hide();
                $("#e4temp").hide();
            }
            else{
                setTimeout(function() {
                    texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Advertencia </h4>  <hr>  <p class="mb-0">El paciente no posee <b>"Fecha de Nacimiento"</b>. <br><br>Debe regularizar esta información para continuar con el proceso de categorización, ya que el formulario varía dependiendo de la edad.</p></div>';
                    modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                $("input[name='dau_cat_1_resp']").attr("disabled", true);

                }, 200);
            }
        }

        validar("#e4txtSat","numero");
        validar("#e4txtFR","numero");
        validar("#e4txtFC","numero");
        validar("#e4txtTe","numero_punto");
        var añoPac  = $('#añoPac').text();
        var mesPac  = $('#mesPac').text();
        var diasPac = $('#diasPac').text();

        $("input[name='dau_cat_4_temp']").blur(function(){
            var temp = $(this).val();
            var templ = temp.length;
            if(templ == 2){
                $(this).val($(this).val()+".0")
            }
        });

        $("input[name='dau_cat_1_resp']").click(function() {
            ocultarBotones();
            if( $(this).val() == "Si" ){
                // Hide - Disabled (Etapa 2)
                $("select[name='dau_cat_2_resp']").attr("disabled", true);
                $("select[name='dau_cat_2_avdi']").attr("disabled", true);
                $("input[name='dau_cat_2_dist']").attr("disabled", true);
                $("select[name='dau_cat_2_eva']").attr("disabled", true);
                $("#etapa2").hide("fast");
                $("#e2se").hide("fast");
                $("#e1se").show("fast");
                // Hide - Disabled (Etapa 3)
                $("select[name='dau_cat_3_resp']").attr("disabled", true);
                $("#etapa3").hide("fast");
                $("#e3se").hide("fast");
                // Hide - Disabled (Etapa 4)
                $("input[name='dau_cat_4_satu']").attr("disabled", true);
                $("input[name='dau_cat_4_fr']").attr("disabled", true);
                $("input[name='dau_cat_4_fc']").attr("disabled", true);
                $("input[name='dau_cat_4_temp']").attr("disabled", true);
                $("select[name='dau_cat_4_inmu']").attr("disabled", true);
                $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
                $("#etapa4").hide("fast");
                $("#e4se").hide("fast");
                // Asignación Categoría
                $('label[id*="esiPac"]').text('');
                $('#esiPac').append(' 1');
                catesi = "C1";

                $('#div_indiferenciado').hide();
                $("#esiDiv").show();
                $('strong[id*="stresiPacEdit"]').attr("hidden", true);
                $('label[id*="esiPacEdit"]').removeAttr("hidden");
                $("#esiAlert").css('background-color','#d94743');
                $("#div_botones").hide();
                $('#e4inmu').prop('selectedIndex',0);
                $('#e4fiebre').prop('selectedIndex',0);
                resp1 = 2;
            }else if($(this).val() == "No"){
                $("select[name='dau_cat_2_resp']").attr("disabled", false);
                $("select[name='dau_cat_2_avdi']").attr("disabled", false);
                $("input[name='dau_cat_2_dist']").attr("disabled", false);
                $("select[name='dau_cat_2_eva']").attr("disabled", false);
                $("#etapa2").show("fast");
                $("#e2se").show("fast");
                $('label[id*="esiPac"]').text('');
                catesi = "C0";
                $('#div_indiferenciado').hide();
                $('#div_indiferenciado').hide();
                $("#esiDiv").hide();
                $('strong[id*="stresiPacEdit"]').attr("hidden", true);
                $('label[id*="esiPacEdit"]').removeAttr("hidden", true);
                $("#e1se").show("fast");
                resp1 = 1;
            }
        });

        $("input[name='dau_cat_2_resp']").click(function() {
            ocultarBotones()
            if( $(this).val() == "Si" ) {
                resp2 = 1;
                if (avdi == 2 || eva == 2 || distresado == 1) {
                    $("#esiDiv").show();
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                }else if(avdi == 1 || eva == 1 || distresado == 2){
                    $("#esiDiv").show();
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                    // Hide - Disabled (Etapa 3)
                    $("select[name='dau_cat_3_resp']").attr("disabled", true);
                    $("#etapa3").hide("fast");
                    $("#e3se").hide("fast");
                    // Hide - Disabled (Etapa 4)
                    $("input[name='dau_cat_4_satu']").attr("disabled", true);
                    $("input[name='dau_cat_4_fr']").attr("disabled", true);
                    $("input[name='dau_cat_4_fc']").attr("disabled", true);
                    $("input[name='dau_cat_4_temp']").attr("disabled", true);
                    $("select[name='dau_cat_4_inmu']").attr("disabled", true);
                    $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
                    $("#etapa4").hide("fast");
                    $("#e4se").hide("fast");
                    $("#div_botones").hide();
                    $('#e4inmu').prop('selectedIndex',0);
                    $('#e4fiebre').prop('selectedIndex',0);
                }else if(avdi == 0 || eva == 0 || distresado == 0){
                    $("#esiDiv").show();
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                    // Hide - Disabled (Etapa 3)
                    $("select[name='dau_cat_3_resp']").attr("disabled", true);
                    $("#etapa3").hide("fast");
                    $("#e3se").hide("fast");
                    // Hide - Disabled (Etapa 4)
                    $("input[name='dau_cat_4_satu']").attr("disabled", true);
                    $("input[name='dau_cat_4_fr']").attr("disabled", true);
                    $("input[name='dau_cat_4_fc']").attr("disabled", true);
                    $("input[name='dau_cat_4_temp']").attr("disabled", true);
                    $("select[name='dau_cat_4_inmu']").attr("disabled", true);
                    $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
                    $("#etapa4").hide("fast");
                    $("#e4se").hide("fast");
                }
                resetVariables();
            }else if( $(this).val() == "No" ){
                resp2 = 2;
                if (avdi == 2 || eva == 2 || distresado == 1) {
                    $("#esiDiv").show();
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                }else if(avdi == 1 && eva == 1 && distresado == 2){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                    // Hide - Disabled (Etapa 3)
                    $("select[name='dau_cat_3_resp']").attr("disabled", false);
                    if(añoPac >=3 && mesPac >=0 && diasPac >=0){ // mayor a 3 años
                        $("#etapa3").show("fast");
                        $("#e3se").show("fast");
                    }else{ // menor que 3 años
                        $("#etapa4").show("fast");
                        $("#e4se").show("fast");
                        $("#esiDiv").hide();
                        $("input[name='dau_cat_4_satu']").attr("disabled", false);
                        $("input[name='dau_cat_4_fr']").attr("disabled", false);
                        $("input[name='dau_cat_4_fc']").attr("disabled", false);
                        $("input[name='dau_cat_4_temp']").attr("disabled", false);
                        if(añoPac == 0 && mesPac >= 3 ){ // mayor a 3 meses
                            $("#e3se").show("fast");
                            $("#etapa3").show("fast");
                            $( "#e3rec" ).prop( "disabled", true );
                            $('#div_nota').show("fast");
                            $("#e4fiebrediv").hide();
                            $("#e4inmudiv").hide();
                            $("select[name='dau_cat_4_inmu']").attr("disabled", true);
                            $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
                        }else if(añoPac < 3 ){ // menores de 3 años
                            $("#e3se").show("fast");
                            $("#etapa3").show("fast");
                            $( "#e3rec" ).prop( "disabled", true );
                            $('#div_nota').show("fast");
                            $("#e4fiebrediv").show("fast");
                            $("#e4inmudiv").show("fast");
                            $("select[name='dau_cat_4_inmu']").attr("disabled", false);
                            $("select[name='dau_cat_4_fiebre']").attr("disabled", false);
                        }else if(añoPac >= 3){
                            $("#e4fiebrediv").hide("fast");
                            $("#e4inmudiv").hide("fast");
                            $("select[name='dau_cat_4_inmu']").attr("disabled", true);
                            $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
                            $("#e3se").show("fast");
                            $("#etapa3").show("fast");
                            $( "#e3rec" ).prop( "disabled", true );
                            $('#div_nota').show("fast");
                        }
                        $("#etapa4").show("fast");
                        $("#e4se").show("fast");
                        $('label[id*="esiPac"]').text('');
                        catesi = "C0";
                        $('#div_indiferenciado').hide();
                    }
                }else if(avdi == 0 && eva == 0 && distresado == 0){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }else if(avdi == 1 && distresado == 2){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }else if(distresado == 2 && eva == 1){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }else if(avdi == 1 && eva == 1){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }else if(avdi == 0 || avdi == 1){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }else if(eva == 0 || eva == 1){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }else if(distresado == 0 || distresado == 2){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }
            }
        });

        $("select[name='dau_cat_2_avdi']").change(function() {
            if( $(this).val() == "1" ) {
                avdi = 1;
                if (distresado == 1 || eva == 2 || resp2 == 1) {
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                    $("#esiDiv").show();
                    $("#div_botones").hide();
                    $('#e4inmu').prop('selectedIndex',0);
                    $('#e4fiebre').prop('selectedIndex',0);
                }else if(distresado == 2 && eva == 1 && resp2 == 2){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                    // Hide - Disabled (Etapa 3)
                    $("select[name='dau_cat_3_resp']").attr("disabled", false);
                    if(añoPac >=3 && mesPac >=0 && diasPac >=0){ // mayor a 3 años
                        $("#etapa3").show("fast");
                        $("#e3se").show("fast");
                    }else{ // menor que 3 años
                        $("#etapa4").show("fast");
                        $("#e4se").show("fast");
                        $("#esiDiv").hide();
                        $("input[name='dau_cat_4_satu']").attr("disabled", false);
                        $("input[name='dau_cat_4_fr']").attr("disabled", false);
                        $("input[name='dau_cat_4_fc']").attr("disabled", false);
                        $("input[name='dau_cat_4_temp']").attr("disabled", false);
                        if(añoPac == 0 && mesPac >= 3 ){ // mayor a 3 meses
                            $("#e3se").show("fast");
                            $("#etapa3").show("fast");
                            $( "#e3rec" ).prop( "disabled", true );
                            $('#div_nota').show("fast");
                            $("#e4fiebrediv").hide();
                            $("#e4inmudiv").hide();
                            $("select[name='dau_cat_4_inmu']").attr("disabled", true);
                            $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
                        }else if(añoPac < 3 ){ // menores de 3 años
                            $("#e3se").show("fast");
                            $("#etapa3").show("fast");
                            $( "#e3rec" ).prop( "disabled", true );
                            $('#div_nota').show("fast");
                            $("#e4fiebrediv").show("fast");
                            $("#e4inmudiv").show("fast");
                            $("select[name='dau_cat_4_inmu']").attr("disabled", false);
                            $("select[name='dau_cat_4_fiebre']").attr("disabled", false);
                        }else if(añoPac >= 3){
                            $("#e4fiebrediv").hide("fast");
                            $("#e4inmudiv").hide("fast");
                            $("select[name='dau_cat_4_inmu']").attr("disabled", true);
                            $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
                            $("#e3se").show("fast");
                            $("#etapa3").show("fast");
                            $( "#e3rec" ).prop( "disabled", true );
                            $('#div_nota').show("fast");
                        }
                        $("#etapa4").show("fast");
                        $("#e4se").show("fast");
                        $('label[id*="esiPac"]').text('');
                        catesi = "C0";
                        $('#div_indiferenciado').hide();
                    }
                }else if(distresado == 0 && eva == 0 && resp2 == 0){
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                    $("#esiDiv").hide();
                }else if(resp2 == 2 && distresado == 2){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }else if(resp2 == 2 && eva == 1){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }else if(distresado == 2 && eva == 1){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }else if(resp2 == 0 || resp2 == 2){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }else if(eva == 0 || eva == 1){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }else if(distresado == 0 || distresado == 2){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }
            }else if( $(this).val() == "2" || $(this).val() == "3" || $(this).val() == "4" ){
                resetVariables();
                avdi = 2;
                if (distresado == 1 || eva == 2 || resp2 == 1) {
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                    $("#esiDiv").show();
                }else if(distresado == 2 || eva == 1 || resp2 == 2){
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                    // Hide - Disabled (Etapa 3)
                    $("select[name='dau_cat_3_resp']").attr("disabled", true);
                    $("#etapa3").hide("fast");
                    $("#e3se").hide("fast");
                    // Hide - Disabled (Etapa 4)
                    $("input[name='dau_cat_4_satu']").attr("disabled", true);
                    $("input[name='dau_cat_4_fr']").attr("disabled", true);
                    $("input[name='dau_cat_4_fc']").attr("disabled", true);
                    $("input[name='dau_cat_4_temp']").attr("disabled", true);
                    $("select[name='dau_cat_4_inmu']").attr("disabled", true);
                    $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
                    $("#etapa4").hide("fast");
                    $("#e4se").hide("fast");
                    $("#esiDiv").show();
                }else if(distresado == 0 || eva == 0 || resp2 == 0){
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                    // Hide - Disabled (Etapa 3)
                    $("select[name='dau_cat_3_resp']").attr("disabled", true);
                    $("#etapa3").hide("fast");
                    $("#e3se").hide("fast");
                    // Hide - Disabled (Etapa 4)
                    $("input[name='dau_cat_4_satu']").attr("disabled", true);
                    $("input[name='dau_cat_4_fr']").attr("disabled", true);
                    $("input[name='dau_cat_4_fc']").attr("disabled", true);
                    $("input[name='dau_cat_4_temp']").attr("disabled", true);
                    $("select[name='dau_cat_4_inmu']").attr("disabled", true);
                    $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
                    $("#etapa4").hide("fast");
                    $("#e4se").hide("fast");
                    $("#esiDiv").show();
                }
            }
        });

        $("input[name='dau_cat_2_dist']").click(function() {
            if( $(this).val() == "Si" ) {
                resetVariables();
                distresado = 1;
                if (avdi == 2 || eva == 2 || resp2 == 1) {
                    $("#esiDiv").show();
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                    $("#div_botones").hide();
                    $('#e4inmu').prop('selectedIndex',0);
                    $('#e4fiebre').prop('selectedIndex',0);
                }else if(avdi == 1 || eva == 1 || resp2 == 2){
                    $("#esiDiv").show();
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                    // Hide - Disabled (Etapa 3)
                    $("select[name='dau_cat_3_resp']").attr("disabled", true);
                    $("#etapa3").hide("fast");
                    $("#e3se").hide("fast");
                    // Hide - Disabled (Etapa 4)
                    $("input[name='dau_cat_4_satu']").attr("disabled", true);
                    $("input[name='dau_cat_4_fr']").attr("disabled", true);
                    $("input[name='dau_cat_4_fc']").attr("disabled", true);
                    $("input[name='dau_cat_4_temp']").attr("disabled", true);
                    $("select[name='dau_cat_4_inmu']").attr("disabled", true);
                    $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
                    $("#etapa4").hide("fast");
                    $("#e4se").hide("fast");
                }else if(avdi == 0 || eva == 0 || resp2 == 0){
                    $("#esiDiv").show();
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                    // Hide - Disabled (Etapa 3)
                    $("select[name='dau_cat_3_resp']").attr("disabled", true);
                    $("#etapa3").hide("fast");
                    $("#e3se").hide("fast");
                    // Hide - Disabled (Etapa 4)
                    $("input[name='dau_cat_4_satu']").attr("disabled", true);
                    $("input[name='dau_cat_4_fr']").attr("disabled", true);
                    $("input[name='dau_cat_4_fc']").attr("disabled", true);
                    $("input[name='dau_cat_4_temp']").attr("disabled", true);
                    $("select[name='dau_cat_4_inmu']").attr("disabled", true);
                    $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
                    $("#etapa4").hide("fast");
                    $("#e4se").hide("fast");
                }
            }else if( $(this).val() == "No" ){
                distresado = 2;
                if (avdi == 2 || eva == 2 || resp2 == 1) {
                    $("#esiDiv").show();
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                }else if(avdi == 1 && eva == 1 && resp2 == 2){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                    // Hide - Disabled (Etapa 3)
                    $("select[name='dau_cat_3_resp']").attr("disabled", false);
                    if(añoPac >=3 && mesPac >=0 && diasPac >=0){ // mayor a 3 años
                        $("#etapa3").show("fast");
                        $("#e3se").show("fast");
                    }else{ // menor que 3 años
                        $("#etapa4").show("fast");
                        $("#e4se").show("fast");
                        $("#esiDiv").hide();
                        $("input[name='dau_cat_4_satu']").attr("disabled", false);
                        $("input[name='dau_cat_4_fr']").attr("disabled", false);
                        $("input[name='dau_cat_4_fc']").attr("disabled", false);
                        $("input[name='dau_cat_4_temp']").attr("disabled", false);
                        if(añoPac == 0 && mesPac >= 3 ){ // mayor a 3 meses
                            $("#e3se").show("fast");
                            $("#etapa3").show("fast");
                            $( "#e3rec" ).prop( "disabled", true );
                            $('#div_nota').show("fast");
                            $("#e4fiebrediv").hide();
                            $("#e4inmudiv").hide();
                            $("select[name='dau_cat_4_inmu']").attr("disabled", true);
                            $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
                        }else if(añoPac < 3 ){ // menores de 3 años
                            $("#e3se").show("fast");
                            $("#etapa3").show("fast");
                            $( "#e3rec" ).prop( "disabled", true );
                            $('#div_nota').show("fast");
                            $("#e4fiebrediv").show("fast");
                            $("#e4inmudiv").show("fast");
                            $("select[name='dau_cat_4_inmu']").attr("disabled", false);
                            $("select[name='dau_cat_4_fiebre']").attr("disabled", false);
                        }else if(añoPac >= 3){
                            $("#e4fiebrediv").hide("fast");
                            $("#e4inmudiv").hide("fast");
                            $("select[name='dau_cat_4_inmu']").attr("disabled", true);
                            $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
                            $("#e3se").show("fast");
                            $("#etapa3").show("fast");
                            $( "#e3rec" ).prop( "disabled", true );
                            $('#div_nota').show("fast");
                        }
                        $("#etapa4").show("fast");
                        $("#e4se").show("fast");
                        $('label[id*="esiPac"]').text('');
                        catesi = "C0";
                        $('#div_indiferenciado').hide();
                    }
                }else if(avdi == 0 && eva == 0 && resp2 == 0){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();

                    $('label[id*="esiPac"]').text('');
                }else if(resp2 == 2 && avdi == 1){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();

                    $('label[id*="esiPac"]').text('');
                }else if(resp2 == 2 && eva == 1){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }else if(avdi == 1 && eva == 1){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }else if(avdi == 0 || avdi == 1){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }else if(eva == 0 || eva == 1){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }else if(resp2 == 0 || resp2 == 2){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }
            }
        });

        $("select[name='dau_cat_2_eva']").change(function() {
            if ( $(this).val() < 7 ){
                resetVariables();
                eva = 1;
                if (avdi == 2 || distresado == 1 || resp2 == 1) {
                    $("#esiDiv").show();
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                    $("#div_botones").hide();
                    $('#e4inmu').prop('selectedIndex',0);
                    $('#e4fiebre').prop('selectedIndex',0);
                }else if(avdi == 1 && distresado == 2 && resp2 == 2){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                    // Hide - Disabled (Etapa 3)
                    $("select[name='dau_cat_3_resp']").attr("disabled", false);
                    if(añoPac >=3 && mesPac >=0 && diasPac >=0){ // mayor a 3 años
                        $("#etapa3").show("fast");
                        $("#e3se").show("fast");
                    }else{ // menor que 3 años
                        $("#etapa4").show("fast");
                        $("#e4se").show("fast");
                        $("#esiDiv").hide();
                        $("input[name='dau_cat_4_satu']").attr("disabled", false);
                        $("input[name='dau_cat_4_fr']").attr("disabled", false);
                        $("input[name='dau_cat_4_fc']").attr("disabled", false);
                        $("input[name='dau_cat_4_temp']").attr("disabled", false);
                        if(añoPac == 0 && mesPac <= 3 ){ // mayor a 3 meses
                            $("#e3se").show("fast");
                            $("#etapa3").show("fast");
                            $( "#e3rec" ).prop( "disabled", true );
                            $('#div_nota').show("fast");
                            $("#e4fiebrediv").hide();
                            $("#e4inmudiv").hide();
                            $("select[name='dau_cat_4_inmu']").attr("disabled", true);
                            $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
                        }else if(añoPac < 3 ){ // menores de 3 años
                            $("#e3se").show("fast");
                            $("#etapa3").show("fast");
                            $( "#e3rec" ).prop( "disabled", true );
                            $('#div_nota').show("fast");
                            $("#e4fiebrediv").show("fast");
                            $("#e4inmudiv").show("fast");
                            $("select[name='dau_cat_4_inmu']").attr("disabled", false);
                            $("select[name='dau_cat_4_fiebre']").attr("disabled", false);
                        }else if(añoPac >= 3){
                            $("#e4fiebrediv").hide("fast");
                            $("#e4inmudiv").hide("fast");
                            $("select[name='dau_cat_4_inmu']").attr("disabled", true);
                            $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
                            $("#e3se").show("fast");
                            $("#etapa3").show("fast");
                            $( "#e3rec" ).prop( "disabled", true );
                            $('#div_nota').show("fast");
                        }
                        $("#etapa4").show("fast");
                        $("#e4se").show("fast");
                        $('label[id*="esiPac"]').text('');
                        catesi = "C0";
                        $('#div_indiferenciado').hide();
                    }
                }else if(avdi == 0 && distresado == 0 && resp2 == 0){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }else if(resp2 == 2 && avdi == 1){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }else if(resp2 == 2 && distresado == 2){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }else if(avdi == 1 && distresado == 2){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }else if(avdi == 0 || avdi == 1){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }else if(resp2 == 0 || resp2 == 2){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }else if(distresado == 0 || distresado == 2){
                    $("#esiDiv").hide();
                    catesi = "C0";
                    $('#div_indiferenciado').hide();
                    $('label[id*="esiPac"]').text('');
                }
            }else if( $(this).val() >= 7 ){
                //egs
                eva = 2;
                if (avdi == 2 || distresado == 1 || resp2 == 1) {
                    $("#esiDiv").show();
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                }else if(avdi == 1 || distresado == 1 || resp2 == 1){
                    $("#esiDiv").show();
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                    // Hide - Disabled (Etapa 3)
                    $("select[name='dau_cat_3_resp']").attr("disabled", true);
                    $("#etapa3").hide("fast");
                    $("#e3se").hide("fast");
                    // Hide - Disabled (Etapa 4)
                    $("input[name='dau_cat_4_satu']").attr("disabled", true);
                    $("input[name='dau_cat_4_fr']").attr("disabled", true);
                    $("input[name='dau_cat_4_fc']").attr("disabled", true);
                    $("input[name='dau_cat_4_temp']").attr("disabled", true);
                    $("select[name='dau_cat_4_inmu']").attr("disabled", true);
                    $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
                    $("#etapa4").hide("fast");
                    $("#e4se").hide("fast");
                }else if(avdi == 0 || distresado == 0 || resp2 == 0){
                    $("#esiDiv").show();
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                    // Hide - Disabled (Etapa 3)
                    $("select[name='dau_cat_3_resp']").attr("disabled", true);
                    $("#etapa3").hide("fast");
                    $("#e3se").hide("fast");
                    // Hide - Disabled (Etapa 4)
                    $("input[name='dau_cat_4_satu']").attr("disabled", true);
                    $("input[name='dau_cat_4_fr']").attr("disabled", true);
                    $("input[name='dau_cat_4_fc']").attr("disabled", true);
                    $("input[name='dau_cat_4_temp']").attr("disabled", true);
                    $("select[name='dau_cat_4_inmu']").attr("disabled", true);
                    $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
                    $("#etapa4").hide("fast");
                    $("#e4se").hide("fast");
                }
            }
        });

        $("select[name='dau_cat_3_resp']").change(function() {
            ocultarBotones()
            if( $(this).val() == "3" ) {
                // $("#prueba").animate({ scrollTop: $('#prueba')[0].scrollHeight}, 1000);
                $("#esiDiv").hide();
                $("input[name='dau_cat_4_satu']").attr("disabled", false);
                $("input[name='dau_cat_4_fr']").attr("disabled", false);
                $("input[name='dau_cat_4_fc']").attr("disabled", false);
                $("input[name='dau_cat_4_temp']").attr("disabled", false);
                $("select[name='dau_cat_4_inmu']").attr("disabled", false);
                $("select[name='dau_cat_4_fiebre']").attr("disabled", false);
                $("#etapa4").show("fast");
                $("#e4se").show("fast");
                $('label[id*="esiPac"]').text('');
                catesi = "C0";
                $('#div_indiferenciado').hide();
            }else if( $(this).val() == "1" ){
                $("#esiDiv").show();
                $("input[name='dau_cat_4_satu']").attr("disabled", true);
                $("input[name='dau_cat_4_fr']").attr("disabled", true);
                $("input[name='dau_cat_4_fc']").attr("disabled", true);
                $("input[name='dau_cat_4_temp']").attr("disabled", true);
                $("select[name='dau_cat_4_inmu']").attr("disabled", true);
                $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
                $("#etapa4").hide("fast");
                $("#e4se").hide("fast");
                $('label[id*="esiPac"]').text('');
                $('#esiPac').append(' 5');
                catesi = "C5";
                $('#div_indiferenciado').show();
                resetVariables();
                $("#esiAlert").css('background-color','#435fd9');
            }else{
                $("#esiDiv").show();
                $("input[name='dau_cat_4_satu']").attr("disabled", true);
                $("input[name='dau_cat_4_fr']").attr("disabled", true);
                $("input[name='dau_cat_4_fc']").attr("disabled", true);
                $("input[name='dau_cat_4_temp']").attr("disabled", true);
                $("select[name='dau_cat_4_inmu']").attr("disabled", true);
                $("select[name='dau_cat_4_fiebre']").attr("disabled", true);
                $("#etapa4").hide("fast");
                $("#e4se").hide("fast");
                $('label[id*="esiPac"]').text('');
                $('#esiPac').append(' 4');
                catesi = "C4";
                $('#div_indiferenciado').show();
                resetVariables();
                $("#esiAlert").css('background-color','#6bd943');
            }
        });

        if( añoPac == 0 && mesPac <3){
            var fr_res= $('#e4txtFR').val();
            var fr_car= $('#e4txtFC').val()
            var tem =   $('#e4txtTe').val()
            var sat =   $('#e4txtSat').val()
            $("input[name='dau_cat_4_satu']").change(function() {
                var valor_saturo = $(this).val();
                if (valor_saturo.length == 0) { // si esta vacia la caja
                    $("#esiDiv").hide();
                    $('label[id*="esiPac"]').text('');
                    estado_saturometria = 0;
                }else if($(this).val() < 92){
                    $("#esiDiv").show();
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                    estado_saturometria = 1;
                }else if($(this).val() >= 92){
                    estado_saturometria = 2;
                    if( estado_fre_res == 2 && estado_fre_car == 2 && estado_temperatura == 2){
                        $("#esiDiv").show();
                        catesi = "C3";
                        $('#div_indiferenciado').hide();
                        $("#esiAlert").css('background-color','#d9d143');
                        $('label[id*="esiPac"]').text('');
                        $('#esiPac').append(' 3');
                    }else if(estado_fre_res == 1 || estado_fre_car == 1 || estado_temperatura == 1){
                        $("#esiDiv").show();
                        catesi = "C2";
                        $('#div_indiferenciado').hide();
                        $("#esiAlert").css('background-color','#d97e43');
                        $('label[id*="esiPac"]').text('');
                        $('#esiPac').append(' 2');
                    }
                }
            })
            $("input[name='dau_cat_4_fr']").change(function() {
                var valor_fre_resp = $(this).val();
                if (valor_fre_resp.length == 0) { // si esta vacia la caja
                    $("#esiDiv").hide();
                    $('label[id*="esiPac"]').text('');
                    estado_fre_res = 0;
                }else if($(this).val() >= 50){
                    $("#esiDiv").show();
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                    estado_fre_res = 1;
                }else if($(this).val() < 50){
                    estado_fre_res = 2;
                    if( estado_saturometria == 2 && estado_fre_car == 2 && estado_temperatura == 2){
                        $("#esiDiv").show();
                        catesi = "C3";
                        $('#div_indiferenciado').hide();
                        $("#esiAlert").css('background-color','#d9d143');
                        $('label[id*="esiPac"]').text('');
                        $('#esiPac').append(' 3');
                        //estado_fre_res = 1;
                    }else if(estado_saturometria == 1 || estado_fre_car == 1 || estado_temperatura == 1){
                        $("#esiDiv").show();
                        catesi = "C2";
                        $('#div_indiferenciado').hide();
                        $("#esiAlert").css('background-color','#d97e43');
                        $('label[id*="esiPac"]').text('');
                        $('#esiPac').append(' 2');
                    }
                }
            })
            $("input[name='dau_cat_4_fc']").change(function() {
                var valor_fre_car = $(this).val();
                if (valor_fre_car.length == 0) { // si esta vacia la caja
                    $("#esiDiv").hide();
                    $('label[id*="esiPac"]').text('');
                    estado_fre_car = 0;
                }else if($(this).val()>= 180){
                    $("#esiDiv").show();
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                    estado_fre_car = 1;
                }else if($(this).val()< 180){
                    estado_fre_car = 2;
                    if( estado_saturometria == 2 && estado_fre_res == 2 && estado_temperatura == 2){
                        $("#esiDiv").show();
                        catesi = "C3";
                        $('#div_indiferenciado').hide();
                        $("#esiAlert").css('background-color','#d9d143');
                        $('label[id*="esiPac"]').text('');
                        $('#esiPac').append(' 3');
                    }else if(estado_saturometria == 1 || estado_fre_res == 1 || estado_temperatura == 1){
                        $("#esiDiv").show();
                        catesi = "C2";
                        $('#div_indiferenciado').hide();
                        $("#esiAlert").css('background-color','#d97e43');
                        $('label[id*="esiPac"]').text('');
                        $('#esiPac').append(' 2');
                    }
                }
            })
            $("input[name='dau_cat_4_temp']").change(function(){
                var valor_temp = $(this).val();
                if (valor_temp.length == 0) { // si esta vacia la caja
                    $("#esiDiv").hide();
                    $('label[id*="esiPac"]').text('');
                    estado_temperatura = 0;
                }else if($(this).val() >= 38){
                    $("#esiDiv").show();
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                    estado_temperatura = 1;
                }else if($(this).val() <38){
                    estado_temperatura = 2;
                    if( estado_saturometria == 2 && estado_fre_res == 2 && estado_fre_car == 2){
                        $("#esiDiv").show();
                        catesi = "C3";
                        $('#div_indiferenciado').hide();
                        $("#esiAlert").css('background-color','#d9d143');
                        $('label[id*="esiPac"]').text('');
                        $('#esiPac').append(' 3');
                    }else if(estado_saturometria == 1 || estado_fre_res == 1 || estado_fre_car == 1){
                        $("#esiDiv").show();
                        catesi = "C2";
                        $('#div_indiferenciado').hide();
                        $("#esiAlert").css('background-color','#d97e43');
                        $('label[id*="esiPac"]').text('');
                        $('#esiPac').append(' 2');
                    }
                }
            })
        }else if( añoPac < 3 ){
            $("input[name='dau_cat_4_satu']").change(function() {
                var valor_saturo2 = $(this).val();
                if (valor_saturo2.length == 0) { // si esta vacia la caja
                    $("#esiDiv").hide();
                    $('label[id*="esiPac"]').text('');
                    $('#div_botones').hide();
                    $('#div_C2').hide();
                    $('#div_C4').hide();
                    estado_saturometria = 0;
                }else if($(this).val() < 92){
                    $("#esiDiv").show();
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                    $('#div_botones').hide();
                    $('#div_C2').hide();
                    $('#div_C4').hide();
                    estado_saturometria = 1;
                }else if($(this).val() >= 92){
                    estado_saturometria = 2;
                    if( estado_fre_res == 2 && estado_fre_car == 2 && estado_temperatura == 2){
                        $("#esiDiv").show();
                        catesi = "C3";
                        $('#div_indiferenciado').hide();
                        $("#esiAlert").css('background-color','#d9d143');
                        $('label[id*="esiPac"]').text('');
                        $('#esiPac').append(' 3');
                        $("#div_botones").show();
                        $("#div_C2").hide();
                        $("#div_C4").show();
                    }else if(estado_fre_res == 1 || estado_fre_car == 1 || estado_temperatura == 1){
                        $("#esiDiv").show();
                        catesi = "C2";
                        $('#div_indiferenciado').hide();
                        $("#esiAlert").css('background-color','#d97e43');
                        $('label[id*="esiPac"]').text('');
                        $('#esiPac').append(' 2');
                    }
                }
            })
            $("input[name='dau_cat_4_fr']").change(function() {
                var valor_fre_resp2 = $(this).val();
                if (valor_fre_resp2.length == 0) { // si esta vacia la caja
                    $("#esiDiv").hide();
                    $('label[id*="esiPac"]').text('');
                    $('#div_botones').hide();
                    $('#div_C2').hide();
                    $('#div_C4').hide();
                    estado_fre_res = 0;
                }else if($(this).val() >= 40){
                    $("#esiDiv").show();
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                    $('#div_botones').hide();
                    $('#div_C2').hide();
                    $('#div_C4').hide();
                    estado_fre_res = 1;
                }else if($(this).val() < 40){
                    estado_fre_res = 2;
                    if( estado_saturometria == 2 && estado_fre_car == 2 && estado_temperatura == 2){
                        $("#esiDiv").show();
                        catesi = "C3";
                        $('#div_indiferenciado').hide();
                        $("#esiAlert").css('background-color','#d9d143');
                        $('label[id*="esiPac"]').text('');
                        $('#esiPac').append(' 3');
                        $("#div_botones").show();
                        $("#div_C2").hide();
                        $("#div_C4").show();
                    }else if(estado_saturometria == 1 || estado_fre_car == 1 || estado_temperatura == 1){
                        $("#esiDiv").show();
                        catesi = "C2";
                        $('#div_indiferenciado').hide();
                        $("#esiAlert").css('background-color','#d97e43');
                        $('label[id*="esiPac"]').text('');
                        $('#esiPac').append(' 2');
                    }
                }
            })

            $("input[name='dau_cat_4_fc']").change(function() {
                var valor_fre_car2 = $(this).val();
                if (valor_fre_car2.length == 0) { // si esta vacia la caja
                    $("#esiDiv").hide();
                    $('label[id*="esiPac"]').text('');
                    $('#div_botones').hide();
                    $('#div_C2').hide();
                    $('#div_C4').hide();
                    estado_fre_car = 0;
                }else if($(this).val()>= 160){
                    $("#esiDiv").show();
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                    $('#div_botones').hide();
                    $('#div_C2').hide();
                    $('#div_C4').hide();
                    estado_fre_car = 1;
                }else if($(this).val()< 160){
                    estado_fre_car = 2;
                    if( estado_saturometria == 2 && estado_fre_res == 2 && estado_temperatura == 2){
                        $("#esiDiv").show();
                        catesi = "C3";
                        $('#div_indiferenciado').hide();
                        $("#esiAlert").css('background-color','#d9d143');
                        $('label[id*="esiPac"]').text('');
                        $('#esiPac').append(' 3');
                        $("#div_botones").show();
                        $("#div_C2").hide();
                        $("#div_C4").show();
                    }else if(estado_saturometria == 1 || estado_fre_res == 1 || estado_temperatura == 1){
                        $("#esiDiv").show();
                        catesi = "C2";
                        $('#div_indiferenciado').hide();
                        $("#esiAlert").css('background-color','#d97e43');
                        $('label[id*="esiPac"]').text('');
                        $('#esiPac').append(' 2');
                    }
                }
            })

            $("input[name='dau_cat_4_temp']").change(function(){
                var valor_temp2 = $(this).val();
                if (valor_temp2.length == 0) { // si esta vacia la caja
                    $("#esiDiv").hide();
                    $('label[id*="esiPac"]').text('');
                    $('#div_botones').hide();
                    $('#div_C2').hide();
                    $('#div_C4').hide();
                    estado_temperatura = 0;
                }else if($(this).val() >= 39){
                    $("#esiDiv").show();
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                    $('#div_botones').hide();
                    $('#div_C2').hide();
                    $('#div_C4').hide();
                    estado_temperatura = 1;
                }else if($(this).val() < 39){
                    estado_temperatura = 2;
                    if( estado_saturometria == 2 && estado_fre_res == 2 && estado_fre_car == 2){
                       $("#esiDiv").show();
                       catesi = "C3";
                       $('#div_indiferenciado').hide();
                       $("#esiAlert").css('background-color','#d9d143');
                       $('label[id*="esiPac"]').text('');
                       $('#esiPac').append(' 3');
                       $("#div_botones").show();
                       $("#div_C2").hide();
                       $("#div_C4").show();
                    }else if(estado_saturometria == 1 || estado_fre_res == 1 || estado_fre_car == 1){
                        $("#esiDiv").show();
                        catesi = "C2";
                        $('#div_indiferenciado').hide();
                        $("#esiAlert").css('background-color','#d97e43');
                        $('label[id*="esiPac"]').text('');
                        $('#esiPac').append(' 2');
                    }
                }
            });
        }else if( añoPac < 8 && mesPac >= 0 && diasPac >= 0){
            $("input[name='dau_cat_4_satu']").change(function() {
                var valor_saturo = $(this).val();
                if (valor_saturo.length == 0) { // si esta vacia la caja
                    $("#esiDiv").hide();
                    $('label[id*="esiPac"]').text('');
                    estado_saturometria = 0;
                }else if($(this).val() < 92){
                    estado_saturometria = 1;
                    $("#esiDiv").show();
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                }else if($(this).val() >= 92){
                    limpiarEsi()
                    estado_saturometria = 2;
                    if( estado_fre_res == 2 && estado_fre_car == 2 ){
                        $("#esiDiv").show();
                        catesi = "C3";
                        $('#div_indiferenciado').hide();
                        $("#esiAlert").css('background-color','#d9d143');
                        $('label[id*="esiPac"]').text('');
                        $('#esiPac').append(' 3');
                    }else if(estado_fre_res == 1 || estado_fre_car == 1){
                        $("#esiDiv").show();
                        catesi = "C2";
                        $('#div_indiferenciado').hide();
                        $("#esiAlert").css('background-color','#d97e43');
                        $('label[id*="esiPac"]').text('');
                        $('#esiPac').append(' 2');
                    }
                }
            })
            $("input[name='dau_cat_4_fr']").change(function() {
                var valor_fre_resp = $(this).val();
                if (valor_fre_resp.length == 0) { // si esta vacia la caja
                    $("#esiDiv").hide();
                    $('label[id*="esiPac"]').text('');
                    estado_fre_res = 0;
                }else if($(this).val() >= 30){
                    estado_fre_res =1;
                    $("#esiDiv").show();
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                }else if($(this).val()< 30){
                    limpiarEsi()
                    estado_fre_res = 2;
                    if( estado_saturometria == 2 && estado_fre_car == 2 ){
                        $("#esiDiv").show();
                        catesi = "C3";
                        $('#div_indiferenciado').hide();
                        $("#esiAlert").css('background-color','#d9d143');
                        $('label[id*="esiPac"]').text('');
                        $('#esiPac').append(' 3');
                    }else if(estado_saturometria == 1 || estado_fre_car == 1 ){
                        $("#esiDiv").show();
                        catesi = "C2";
                        $('#div_indiferenciado').hide();
                        $("#esiAlert").css('background-color','#d97e43');
                        $('label[id*="esiPac"]').text('');
                        $('#esiPac').append(' 2');
                    }
                }
            })
            $("input[name='dau_cat_4_fc']").change(function() {
                var valor_fre_car = $(this).val();
                if (valor_fre_car.length == 0) { // si esta vacia la caja
                    $("#esiDiv").hide();
                    $('label[id*="esiPac"]').text('');
                    estado_fre_car = 0;
                }else if($(this).val()>= 140){
                    estado_fre_car = 1;
                    $("#esiDiv").show();
                    catesi = "C2";
                    $('#div_indiferenciado').hide();
                    $("#esiAlert").css('background-color','#d97e43');
                    $('label[id*="esiPac"]').text('');
                    $('#esiPac').append(' 2');
                }else if($(this).val()< 140){
                    limpiarEsi()
                    estado_fre_car = 2;
                    if( estado_saturometria == 2 && estado_fre_res == 2 ){
                        $("#esiDiv").show();
                        catesi = "C3";
                        $('#div_indiferenciado').hide();
                        $("#esiAlert").css('background-color','#d9d143');
                        $('label[id*="esiPac"]').text('');
                        $('#esiPac').append(' 3');
                    }else if(estado_saturometria == 1 || estado_fre_res == 1){
                        $("#esiDiv").show();
                        catesi = "C2";
                        $('#div_indiferenciado').hide();
                        $("#esiAlert").css('background-color','#d97e43');
                        $('label[id*="esiPac"]').text('');
                        $('#esiPac').append(' 2');
                    }
                }
            })
        }else if( añoPac >= 8 && mesPac >= 0 && diasPac >=0){
            $("input[name='dau_cat_4_satu']").change(function() {
                var valor_saturo = $(this).val();
                if (valor_saturo.length == 0) { // si esta vacia la caja
                    $("#esiDiv").hide();
                    $('label[id*="esiPac"]').text('');
                    $('#frm_val_saturo').val(0);
                    $('#div_botones').hide();
                    $('#div_C2').hide();
                    $('#div_C4').hide();
                    estado_saturometria =1 ;
                }else if($(this).val() >= 92){ // normal
                    estado_saturometria = 2;
                    $('#frm_val_saturo').val(1);
                    if( estado_fre_res == 2 && estado_fre_car == 2){
                        categorizarC3_sinBotones();
                    }else if($('#frm_val_saturo').val() == 2 && $('#frm_val_freres').val() == 2 && $('#frm_val_frecer').val() == 1){ // discrecional - discrecional - normal
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 2 && $('#frm_val_freres').val() == 1 && $('#frm_val_frecer').val() == 1){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 1 && $('#frm_val_freres').val() == 2 && $('#frm_val_frecer').val() == 2){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 1 && $('#frm_val_freres').val() == 1 && $('#frm_val_frecer').val() == 2){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 1 && $('#frm_val_freres').val() == 2 && $('#frm_val_frecer').val() == 1){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 2 && $('#frm_val_freres').val() == 1 && $('#frm_val_frecer').val() == 2){
                        categorizarC3_main();
                    }
                }else if($(this).val() < 88){ // alterado
                    estado_saturometria = 3;
                    if( estado_saturometria == 3 || estado_fre_res == 3 || estado_fre_car == 3){
                        categorizarC2_main();
                    }
                }else if($(this).val() <= 91 || $(this).val() >= 88 ){ // discrecional
                    estado_saturometria = 4;
                    $('#frm_val_saturo').val(2);
                    if(estado_fre_res == 4 && estado_fre_car == 4){
                        categorizarC2_main();
                    }else if($('#frm_val_saturo').val() == 2 && $('#frm_val_freres').val() == 2 && $('#frm_val_frecer').val() == 1){ // discrecional - discrecional - normal
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 2 && $('#frm_val_freres').val() == 1 && $('#frm_val_frecer').val() == 1){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 1 && $('#frm_val_freres').val() == 2 && $('#frm_val_frecer').val() == 2){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 1 && $('#frm_val_freres').val() == 1 && $('#frm_val_frecer').val() == 2){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 1 && $('#frm_val_freres').val() == 2 && $('#frm_val_frecer').val() == 1){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 2 && $('#frm_val_freres').val() == 1 && $('#frm_val_frecer').val() == 2){
                        categorizarC3_main();
                    }
                }
            })
            $("input[name='dau_cat_4_fr']").change(function() {
                var valor_fre_resp = $(this).val();
                if (valor_fre_resp.length == 0) {
                    $("#esiDiv").hide();
                    $('label[id*="esiPac"]').text('');
                    $('#frm_val_freres').val(0);
                    $('#div_botones').hide();
                    $('#div_C2').hide();
                    $('#div_C4').hide();
                    estado_fre_res = 1;
                }else if($(this).val() < 20){
                    estado_fre_res = 2;
                    $('#frm_val_freres').val(1);
                    if( estado_saturometria == 2  && estado_fre_car == 2){
                        categorizarC3_sinBotones();
                    }else if($('#frm_val_saturo').val() == 2 && $('#frm_val_freres').val() == 2 && $('#frm_val_frecer').val() == 1){ // discrecional - discrecional - normal
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 2 && $('#frm_val_freres').val() == 1 && $('#frm_val_frecer').val() == 1){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 1 && $('#frm_val_freres').val() == 2 && $('#frm_val_frecer').val() == 2){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 1 && $('#frm_val_freres').val() == 1 && $('#frm_val_frecer').val() == 2){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 1 && $('#frm_val_freres').val() == 2 && $('#frm_val_frecer').val() == 1){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 2 && $('#frm_val_freres').val() == 1 && $('#frm_val_frecer').val() == 2){
                        categorizarC3_main();
                    }
                }else if($(this).val() >= 25){
                    estado_fre_res = 3;
                    if( estado_saturometria == 3 || estado_fre_res == 3 || estado_fre_car == 3){
                        categorizarC2_main();
                    }
                }else if($(this).val() <= 24 || $(this).val() >= 21 ){
                    estado_fre_res = 4;
                    $('#frm_val_freres').val(2);
                    if(estado_saturometria == 4 && estado_fre_car == 4){
                        categorizarC2_main();
                    }else if($('#frm_val_saturo').val() == 2 && $('#frm_val_freres').val() == 2 && $('#frm_val_frecer').val() == 1){ // discrecional - discrecional - normal
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 2 && $('#frm_val_freres').val() == 1 && $('#frm_val_frecer').val() == 1){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 1 && $('#frm_val_freres').val() == 2 && $('#frm_val_frecer').val() == 2){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 1 && $('#frm_val_freres').val() == 1 && $('#frm_val_frecer').val() == 2){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 1 && $('#frm_val_freres').val() == 2 && $('#frm_val_frecer').val() == 1){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 2 && $('#frm_val_freres').val() == 1 && $('#frm_val_frecer').val() == 2){
                        categorizarC3_main();;
                    }
                }
            })
            $("input[name='dau_cat_4_fc']").change(function() {
                var valor_fre_car = $(this).val();
                if (valor_fre_car.length == 0) {
                    $("#esiDiv").hide();
                    $('label[id*="esiPac"]').text('');
                    $('#frm_val_frecer').val(0);
                    $('#div_botones').hide();
                    $('#div_C2').hide();
                    $('#div_C4').hide();
                    estado_fre_car = 1;
                }else if($(this).val() < 100){
                    estado_fre_car = 2;
                    $('#frm_val_frecer').val(1);
                    if( estado_saturometria == 2 && estado_fre_res == 2 ){
                        categorizarC3_sinBotones();
                    }else if($('#frm_val_saturo').val() == 2 && $('#frm_val_freres').val() == 2 && $('#frm_val_frecer').val() == 1){ // discrecional - discrecional - normal
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 2 && $('#frm_val_freres').val() == 1 && $('#frm_val_frecer').val() == 1){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 1 && $('#frm_val_freres').val() == 2 && $('#frm_val_frecer').val() == 2){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 1 && $('#frm_val_freres').val() == 1 && $('#frm_val_frecer').val() == 2){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 1 && $('#frm_val_freres').val() == 2 && $('#frm_val_frecer').val() == 1){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 2 && $('#frm_val_freres').val() == 1 && $('#frm_val_frecer').val() == 2){
                        categorizarC3_main();
                    }
                }else if($(this).val() >= 120){
                    estado_fre_car = 3;
                    if( estado_saturometria == 3 || estado_fre_res == 3 || estado_fre_car == 3){
                        categorizarC2_main();
                    }
                }else if($(this).val() <= 119 || $(this).val() >= 101 ){
                    estado_fre_car = 4;
                    $('#frm_val_frecer').val(2);
                    if(estado_saturometria == 4 && estado_fre_res == 4 ){
                        categorizarC2_main();
                    }else if($('#frm_val_saturo').val() == 2 && $('#frm_val_freres').val() == 2 && $('#frm_val_frecer').val() == 1){ // discrecional - discrecional - normal
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 2 && $('#frm_val_freres').val() == 1 && $('#frm_val_frecer').val() == 1){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 1 && $('#frm_val_freres').val() == 2 && $('#frm_val_frecer').val() == 2){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 1 && $('#frm_val_freres').val() == 1 && $('#frm_val_frecer').val() == 2){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 1 && $('#frm_val_freres').val() == 2 && $('#frm_val_frecer').val() == 1){
                        categorizarC3_main();
                    }else if($('#frm_val_saturo').val() == 2 && $('#frm_val_freres').val() == 1 && $('#frm_val_frecer').val() == 2){
                        categorizarC3_main();
                    }
                }
            })
        }

        $('#btn_c2').click(function(){
            $('#dau_cat_considerada').val('ESI-3');
            var valor_esi_nueva = $('#frm_valor_esi').val();
            if(valor_esi_nueva == 3){
                catesi = "C2";
                $('#div_indiferenciado').hide();
                $("#nuevoAlert").css('background-color','#d97e43');
                $('label[id*="nuevoEsi"]').text('');
                $('#nuevoEsi').append(' 2');
                $('strong[id*="esiNuevo"]').attr("hidden", false);
                $('#frm_valor_esi').val(2);
            }else if(valor_esi_nueva == 2){ // SI VUELVE APRETAR EL BOTON QUEDA COMO ESI-3
                $("#esiDiv").show();
                catesi = "C3";
                $('#div_indiferenciado').hide();
                $("#esiAlert").css('background-color','#d9d143');
                $('label[id*="esiPac"]').text('');
                $('#esiPac').append(' 3');
                $('#frm_valor_esi').val(3);
                ocultarBotones();
            }
        })
        $('#btn_c4').click(function(){
            $('#dau_cat_considerada').val('ESI-3');
            var valor_esi_nueva = $('#frm_valor_esi').val();
            if(valor_esi_nueva == 3){
                catesi = "C4";
                $('#div_indiferenciado').show();
                $("#nuevoAlert").css('background-color','#6bd943');
                $('label[id*="nuevoEsi"]').text('');
                $('#nuevoEsi').append(' 4');
                $('strong[id*="esiNuevo"]').attr("hidden", false);
                $('#frm_valor_esi').val(4);
            }else if(valor_esi_nueva == 4){
                $("#esiDiv").show();
                catesi = "C3";
                $('#div_indiferenciado').hide();
                $("#esiAlert").css('background-color','#d9d143');
                $('label[id*="esiPac"]').text('');
                $('#esiPac').append(' 3');
                $('#frm_valor_esi').val(3);
                ocultarBotones();
            }else if(valor_esi_nueva == 5){
                catesi = "C4";
                $('#div_indiferenciado').show();
                $("#nuevoAlert").css('background-color','#6bd943');
                $('label[id*="nuevoEsi"]').text('');
                $('#nuevoEsi').append(' 4');
                $('strong[id*="esiNuevo"]').attr("hidden", false);
                $('#frm_valor_esi').val(4);
            }
        })
        $('#btn_c5').click(function(){
            $('#dau_cat_considerada').val('ESI-3');
            var valor_esi_nueva = $('#frm_valor_esi').val();
            if(valor_esi_nueva == 3){
                catesi = "C5";
                $('#div_indiferenciado').show();
                $("#nuevoAlert").css('background-color','#435fd9');
                $('label[id*="nuevoEsi"]').text('');
                $('#nuevoEsi').append(' 5');
                $('strong[id*="esiNuevo"]').attr("hidden", false);
                $('#frm_valor_esi').val(5);
            }else if(valor_esi_nueva == 5){
                $("#esiDiv").show();
                catesi = "C3";
                $('#div_indiferenciado').hide();
                $("#esiAlert").css('background-color','#d9d143');
                $('label[id*="esiPac"]').text('');
                $('#esiPac').append(' 3');
                $('#frm_valor_esi').val(3);
                ocultarBotones();
            }else if(valor_esi_nueva == 4){
               catesi = "C5";
                $('#div_indiferenciado').show();
                $("#nuevoAlert").css('background-color','#435fd9');
                $('label[id*="nuevoEsi"]').text('');
                $('#nuevoEsi').append(' 5');
                $('strong[id*="esiNuevo"]').attr("hidden", false);
                $('#frm_valor_esi').val(5);
            }
        })


        $("#btn_nea").click(function(){
            modalFormulario_noCabecera('', raiz+"/views/modules/rce/nea/modalAplicarNEA.php", 'dau_id='+dau_id, "#modalNEA", "modal-md", "", "fas fa-plus",'');
        });

        $('#btnCategorizarESI').on('click', function ( ) {
            $.validity.start();
           

            if (resp1 == 0) {
                $("input[name='dau_cat_1_resp']").assert(false,'Seleccione una opción');
            }
            if ( edad == 1 ) {
                if ( $("#etapa4").attr('style') !== "display: none;" ) {
                    if($("input[name='dau_cat_4_satu']").val() == '' && $("input[name='dau_cat_4_fr']").val() == '' && $("input[name='dau_cat_4_fc']").val() == '' && $("input[name='dau_cat_4_temp']").val() == '' ) {
                        $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FR)');
                        $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        $("input[name='dau_cat_4_temp']").assert(false,'Ingrese< (TEMPERATURA)');
                        $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                    }
                    if ( $("input[name='dau_cat_4_satu']").val() >= 92 ) {
                        if($("input[name='dau_cat_4_fr']").val() == ''){
                            $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FR)');
                        }else if($("input[name='dau_cat_4_fc']").val() == ''){
                            $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        }else if($("input[name='dau_cat_4_temp']").val() == ''){
                            $("input[name='dau_cat_4_temp']").assert(false,'Ingrese (TEMPERATURA)');
                        }
                    } else if ( $("input[name='dau_cat_4_satu']").val() < 92 ) {
                        if($("input[name='dau_cat_4_fr']").val() == ''){
                            $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FR)');
                        }else if($("input[name='dau_cat_4_fc']").val() == ''){
                            $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        }else if($("input[name='dau_cat_4_temp']").val() == ''){
                            $("input[name='dau_cat_4_temp']").assert(false,'Ingrese (TEMPERATURA)');
                        }
                    }
                    if ( $("input[name='dau_cat_4_fr']").val() < 50 ) {
                        if($("input[name='dau_cat_4_satu']").val() == ''){
                            $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                        }else if($("input[name='dau_cat_4_fc']").val() == ''){
                            $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        }else if($("input[name='dau_cat_4_temp']").val() == ''){
                            $("input[name='dau_cat_4_temp']").assert(false,'Ingrese (TEMPERATURA)');
                        }
                    } else if ( $("input[name='dau_cat_4_fr']").val() >= 50 ) {
                        if($("input[name='dau_cat_4_satu']").val() == ''){
                            $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                        }else if($("input[name='dau_cat_4_fc']").val() == ''){
                            $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        }else if($("input[name='dau_cat_4_temp']").val() == ''){
                            $("input[name='dau_cat_4_temp']").assert(false,'Ingrese (TEMPERATURA)');
                        }
                    }
                    if ( $("input[name='dau_cat_4_fc']").val() <180 ) {
                        if($("input[name='dau_cat_4_satu']").val() == '' ){
                            $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                        }else if($("input[name='dau_cat_4_fr']").val() == ''){
                            $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FC)');
                        }else if($("input[name='dau_cat_4_temp']").val() == ''){
                            $("input[name='dau_cat_4_temp']").assert(false,'Ingrese (TEMPERATURA)');
                        }
                    } else if ( $("input[name='dau_cat_4_fc']").val() >=180 ) {
                        if($("input[name='dau_cat_4_satu']").val() == '' ){
                            $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                        }else if($("input[name='dau_cat_4_fr']").val() == ''){
                            $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FC)');
                        }else if($("input[name='dau_cat_4_temp']").val() == ''){
                            $("input[name='dau_cat_4_temp']").assert(false,'Ingrese (TEMPERATURA)');
                        }
                    }
                    if ( $("input[name='dau_cat_4_temp']").val() <38 ) {
                        if($("input[name='dau_cat_4_satu']").val() == '' ){
                            $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                        }else if($("input[name='dau_cat_4_fc']").val() == ''){
                            $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        }else if($("input[name='dau_cat_4_fr']").val() == ''){
                            $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FR)');
                        }
                    } else if ( $("input[name='dau_cat_4_temp']").val() >=38 ) {
                        if($("input[name='dau_cat_4_satu']").val() == '' ){
                            $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                        }else if($("input[name='dau_cat_4_fc']").val() == ''){
                            $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        }else if($("input[name='dau_cat_4_fr']").val() == ''){
                            $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FR)');
                        }
                    }
                }
            }else if ( edad == 2 ) {
                if ( $("#etapa4").attr('style') !== "display: none;" ) {
                    if($("input[name='dau_cat_4_satu']").val() == '' && $("input[name='dau_cat_4_fr']").val() == '' && $("input[name='dau_cat_4_fc']").val() == '' && $("input[name='dau_cat_4_temp']").val() == '' ) {
                        $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                        $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FR)');
                        $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        $("input[name='dau_cat_4_temp']").assert(false,'Ingrese (TEMPERATURA)');
                    }
                    if ( $("input[name='dau_cat_4_satu']").val() >= 92 ) {
                        if($("input[name='dau_cat_4_fr']").val() == ''){
                            $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FR)');
                        }else if($("input[name='dau_cat_4_fc']").val() == ''){
                            $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        }else if($("input[name='dau_cat_4_temp']").val() == ''){
                            $("input[name='dau_cat_4_temp']").assert(false,'Ingrese (TEMPERATURA)');
                        }
                    } else if ( $("input[name='dau_cat_4_satu']").val() < 92 ) {
                        if($("input[name='dau_cat_4_fr']").val() == ""){
                            $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FR)');
                        }else if($("input[name='dau_cat_4_fc']").val() == ""){
                            $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        }else if($("input[name='dau_cat_4_temp']").val() == ''){
                            $("input[name='dau_cat_4_temp']").assert(false,'Ingrese (TEMPERATURA)');
                        }
                    }
                    if ( $("input[name='dau_cat_4_fr']").val() < 40 ) {
                        if($("input[name='dau_cat_4_satu']").val() == ""){
                            $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                        }else if($("input[name='dau_cat_4_fc']").val() == ""){
                            $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        }else if($("input[name='dau_cat_4_temp']").val() == ""){
                            $("input[name='dau_cat_4_temp']").assert(false,'Ingrese (TEMPERATURA)');
                        }
                    } else if ( $("input[name='dau_cat_4_fr']").val() >= 40 ) {
                        if($("input[name='dau_cat_4_satu']").val() == ""){
                            $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                        }else if($("input[name='dau_cat_4_fc']").val() == ""){
                            $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        }else if($("input[name='dau_cat_4_temp']").val() == ""){
                            $("input[name='dau_cat_4_temp']").assert(false,'Ingrese (TEMPERATURA)');
                        }
                    }
                    if ( $("input[name='dau_cat_4_fc']").val() <160 ) {
                        if($("input[name='dau_cat_4_satu']").val() == "" ){
                            $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                        }else if($("input[name='dau_cat_4_fr']").val() == ""){
                            $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FC)');
                        }else if($("input[name='dau_cat_4_temp']").val() == ""){
                            $("input[name='dau_cat_4_temp']").assert(false,'Ingrese (TEMPERATURA)');
                        }
                    } else if ($("input[name='dau_cat_4_fc']").val() >=160 ) {
                        if($("input[name='dau_cat_4_satu']").val() == ""){
                            $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                        }else if($("input[name='dau_cat_4_fr']").val() == ""){
                            $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FR)');
                        }else if($("input[name='dau_cat_4_temp']").val() == ""){
                            $("input[name='dau_cat_4_temp']").assert(false,'Ingrese (TEMPERATURA)');
                        }
                    }
                    if ($("input[name='dau_cat_4_temp']").val() >=39 ) {
                        if($("input[name='dau_cat_4_fc']").val() == ""){
                            $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        }else if($("input[name='dau_cat_4_satu']").val() == ""){
                            $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                        }else if($("input[name='dau_cat_4_fr']").val() == ""){
                            $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FR)');
                        }
                    } else if ($("input[name='dau_cat_4_temp']").val() < 39 ) {
                        if($("input[name='dau_cat_4_satu']").val() == "" ){
                            $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                        }else if($("input[name='dau_cat_4_fr']").val() == ""){
                            $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FC)');
                        }else if($("input[name='dau_cat_4_fc']").val() == ""){
                            $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        }
                    }
                }
            }else if ( edad == 3 ) {
                if ( $("#etapa4").attr('style') !== "display: none;" ) {
                    if ( $("input[name='dau_cat_4_satu']").val() == '' && $("input[name='dau_cat_4_fr']").val() == '' && $("input[name='dau_cat_4_fc']").val() == '' ) {
                        $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FR)');
                        $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                    }
                    if ( $("input[name='dau_cat_4_satu']").val() >= 92 ) {
                        if($("input[name='dau_cat_4_fr']").val() == ''){
                            $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FR)');
                        }else if($("input[name='dau_cat_4_fc']").val() == ''){
                            $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        }
                    } else if ( $("input[name='dau_cat_4_satu']").val() < 92 ) {
                        if($("input[name='dau_cat_4_fr']").val() == ""){
                            $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FR)');
                        }else if($("input[name='dau_cat_4_fc']").val() == ''){
                            $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        }
                    } else if ( $("input[name='dau_cat_4_fr']").val() < 30 ) {
                        if($("input[name='dau_cat_4_satu']").val() == ''){
                            $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                        }else if($("input[name='dau_cat_4_fc']").val() == ''){
                            $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        }
                    } else if ( $("input[name='dau_cat_4_fr']").val() >= 30 ) {
                        if($("input[name='dau_cat_4_satu']").val() == ""){
                            $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                        }else if($("input[name='dau_cat_4_fc']").val() == ''){
                            $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        }
                    } else if ( $("input[name='dau_cat_4_fc']").val() < 140 ) {
                        if($("input[name='dau_cat_4_satu']").val() == '' ){
                            $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                        }else if($("input[name='dau_cat_4_fc']").val() == ''){
                            $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FC)');
                        }
                    } else if ($("input[name='dau_cat_4_fc']").val() >=140 ) {
                        if($("input[name='dau_cat_4_satu']").val() == ""){
                            $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                        }else if($("input[name='dau_cat_4_fr']").val() == ""){
                            $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FR)');
                        }
                    }
                }
            }else if ( edad == 4 ) {
                if ( $("#etapa4").attr('style') !== "display: none;" ) {
                    if ( $("input[name='dau_cat_4_satu']").val() == '' && $("input[name='dau_cat_4_fr']").val() == '' && $("input[name='dau_cat_4_fc']").val() == '' ) {
                        $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FR)');
                        $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                    }
                    if ( $("input[name='dau_cat_4_satu']").val() >= 92 ) {
                        if($("input[name='dau_cat_4_fr']").val() == ''){
                            $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FR)');
                        }else if($("input[name='dau_cat_4_fc']").val() == ''){
                            $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        }
                    } else if ( $("input[name='dau_cat_4_satu']").val() < 88 ) {
                        if($("input[name='dau_cat_4_fr']").val() == ''){
                            $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FR)');
                        }else if($("input[name='dau_cat_4_fc']").val() == ''){
                            $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        }
                    } else if ( $("input[name='dau_cat_4_satu']").val() >= 88 || $("input[name='dau_cat_4_satu']").val() <= 91 ){
                        if($("input[name='dau_cat_4_fr']").val() == '' ){
                            $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FR)');
                        }else if($("input[name='dau_cat_4_fc']").val() == ''){
                            $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        }
                    }
                    if ( $("input[name='dau_cat_4_fr']").val() < 20 ) {
                        if($("input[name='dau_cat_4_satu']").val() == ''){
                            $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                        }else if($("input[name='dau_cat_4_fc']").val() == ''){
                            $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        }
                    } else if ( $("input[name='dau_cat_4_fr']").val() >= 21 || $("input[name='dau_cat_4_fr']").val() <= 24 ) {
                        if($("input[name='dau_cat_4_satu']").val() == '' ){
                            $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                        }else if( $("input[name='dau_cat_4_fc']").val() == ''){
                            $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        }
                    } else if ( $("input[name='dau_cat_4_fr']").val() >= 25 ) {
                        if($("input[name='dau_cat_4_satu']") < 0){
                            $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                        }else if( $("input[name='dau_cat_4_fc']").val() == ''){
                            $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        }
                    }
                    if ( $("input[name='dau_cat_4_fc']").val() < 100 ) {
                        if($("input[name='dau_cat_4_satu']").val() == '' ){
                            $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                        }else if($("input[name='dau_cat_4_fc']").val() == ''){
                            $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                        }
                    } else if ( $("input[name='dau_cat_4_fc']").val() >= 101 || $("input[name='dau_cat_4_fc']").val() <= 119 ) {
                        if($("input[name='dau_cat_4_satu']").val() == '' ){
                            $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                        }else if( $("input[name='dau_cat_4_fr']").val() == ''){
                            $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FR)');
                        }
                    } else if ($("input[name='dau_cat_4_fc']").val() >= 120 ) {
                        if( $("input[name='dau_cat_4_fr']").val() == ''){
                            $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FR)');
                        }else if($("input[name='dau_cat_4_satu']").val() == '' ){
                            $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                        }
                    }
                }
            }else {
                if ( $("#etapa4").attr('style') !== "display: none;" ) {
                    if (saturometria == 0) {
                        $("input[name='dau_cat_4_satu']").assert(false,'Ingrese (SaO2)');
                    }
                    if (fresp == 0) {
                        $("input[name='dau_cat_4_fr']").assert(false,'Ingrese (FR)');
                    }
                    if (fcard == 0) {
                        $("input[name='dau_cat_4_fc']").assert(false,'Ingrese (FC)');
                    }
                }
            }
            if ( $("#frm_viajeEpidemiologico").val() === null || $("#frm_viajeEpidemiologico").val() === undefined || String($("#frm_viajeEpidemiologico").val()) === "" ) {
                $("#frm_viajeEpidemiologico").assert(false,'Seleccione Opción');
            }
            if ( String($("#frm_viajeEpidemiologico").val()) === "S" ) {
                if ( $("#frm_paisEpidemiologia").val() === null || $("#frm_paisEpidemiologia").val() === undefined || String($("#frm_paisEpidemiologia").val()) === "" ) {
                    $("#frm_paisEpidemiologia").assert(false,'Seleccione País');
                }
            }
            if (select_frm_dispositivoinvasivo.value === "3" && $("#texto_dispositivo_invasivo").val() == "") {
                $("#texto_dispositivo_invasivo").assert(false,'Indique el otro dispositivo.');
            }
            result = $.validity.end();
            if(result.valid==false){
                return false;
            }

            if ( catesi == "C0" ) {
                texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Categorización de Paciente </h4>  <hr>  <p class="mb-0">Para proceder deberá completar el formulario correctamente..</p></div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                 return false;
            }
            if(catesi == "C3" || catesi == "C4" || catesi == "C5") {
                const respuestaAjaxRequestSignos = ajaxRequest(`${raiz}/controllers/server/categorizacion/main_controller.php`, $("#form-cat").serialize()+'&accion=ObtenerSignosVitales&idDau='+dau_id+'&catesi='+catesi+'&est_id='+est_id, 'POST', 'JSON', 1, 'Obteniendo Signos Vitales...');
                
                if(respuestaAjaxRequestSignos.status == "error"){
                    texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Categorización de Paciente </h4>  <hr>  <p class="mb-0">Para continuar con el proceso, será necesario que ingrese los signos vitales correspondientes del paciente. <br><br><button id="btn_signos_vitales" type="button" name="btn_signos_vitales" class="col-6 btn btn-sm btn-primary2  col-lg-12 text-center"><svg class="svg-inline--fa fa-heartbeat fa-w-16 mr-2 throb" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="heartbeat" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M320.2 243.8l-49.7 99.4c-6 12.1-23.4 11.7-28.9-.6l-56.9-126.3-30 71.7H60.6l182.5 186.5c7.1 7.3 18.6 7.3 25.7 0L451.4 288H342.3l-22.1-44.2zM473.7 73.9l-2.4-2.5c-51.5-52.6-135.8-52.6-187.4 0L256 100l-27.9-28.5c-51.5-52.7-135.9-52.7-187.4 0l-2.4 2.4C-10.4 123.7-12.5 203 31 256h102.4l35.9-86.2c5.4-12.9 23.6-13.2 29.4-.4l58.2 129.3 49-97.9c5.9-11.8 22.7-11.8 28.6 0l27.6 55.2H481c43.5-53 41.4-132.3-7.3-182.1z"></path></svg><!-- <i class="fas fa-heartbeat mr-2 throb"></i> -->Signos Vitales</button> </p></div>';
                    modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                    return false;
                }
            }
            var  funcionCategorizarPaciente = function(){
                let est_id = $('#est_id').val();
                if ( est_id <= 2 ) {
                    est_id = 2;
                } else if ( est_id > 2 ) {
                    est_id = est_id;
                }
                const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/categorizacion/main_controller.php`, $("#form-cat").serialize()+'&accion=pacienteYaCategorizado&idDau='+dau_id+'&catesi='+catesi+'&est_id='+est_id, 'POST', 'JSON', 1, '¿Paciente Ya Categorizado?...');
                if(respuestaAjaxRequest.status == "success"){
                    texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-check-double throb2 text-success" style="font-size:29px"></i> Exito </h4>  <hr>  <p class="mb-0">La categorización se realizó correctamente</p></div>';
                    modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                    $('#modalDetalleCategorizacion').modal( 'hide' ).data( 'bs.modal', null );
                    ajaxContent(`${raiz}/views/modules/mapa_piso_full/mapa_piso_full.php`,'tipoMapa=mapaAdultoPediatrico','#contenido','', true);

                }else if(respuestaAjaxRequest.textoError != null){
                    texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-times throb2 text-danger" style="font-size:29px"></i> Error en categorizar al paciente </h4>  <hr>  <p class="mb-0">'+respuestaAjaxRequest.textoError+'</p></div>';
                    modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                    $('#modalDetalleCategorizacion').modal( 'hide' ).data( 'bs.modal', null );
                    ajaxContent(`${raiz}/views/modules/mapa_piso_full/mapa_piso_full.php`,'tipoMapa=mapaAdultoPediatrico','#contenido','', true);
                }
            }
            
            texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Confirmación de Categorización </h4>  <hr>  <p class="mb-0 text-center">¿Está seguro que desea categorizar a este paciente?</p></div>';
            modalConfirmacion("<label class='mifuente'>Advertencia</label>", texto, "primary", funcionCategorizarPaciente);

        });



    (function(){

        const cambioSelectViajeOProcedencia = ( ) => {

            if ( viajeOProcedencia == null || String(viajeOProcedencia) === "" || String(viajeOProcedencia) === "N" ) {

                $paisEpidemiologia.val("");

                $observacionEpidemiologica.val("");

            }

            if ( String($viajeOProcedenciaExtranjero.val()) === "S" ) {

                $(`${divPaisEpidemiologia}`).show(100);

                $(`${divObservacionEpidemiologica}`).show(100);

                $(`${divViajeEpidemiologico}`).removeClass("col-md-12");

                $(`${divViajeEpidemiologico}`).addClass("col-md-4");

                return;

            }

            $(`${divPaisEpidemiologia}`).hide(100);

            $(`${divObservacionEpidemiologica}`).hide(100);

            $(`${divViajeEpidemiologico}`).removeClass("col-md-4");

            $(`${divViajeEpidemiologico}`).addClass("col-md-12");

        }

        divViajeEpidemiologico       = "#divViajeEpidemiologico";
        divPaisEpidemiologia         = "#divPaisEpidemiologia";
        divObservacionEpidemiologica = "#divObservacionesEpidemiologia";
        viajeOProcedencia            = $("#viajeOProcedencia").val();
        pais                         = $("#pais").val();
        observaciones                = $("#observacion").val();
        $viajeOProcedenciaExtranjero = $("#frm_viajeEpidemiologico");
        $paisEpidemiologia           = $("#frm_paisEpidemiologia");
        $observacionEpidemiologica   = $("#frm_observacionEpidemiologica");

        $viajeOProcedenciaExtranjero.val((viajeOProcedencia == null || viajeOProcedencia == undefined || viajeOProcedencia == "") ? "" : viajeOProcedencia);
        $paisEpidemiologia.val((pais == null || pais == undefined || pais == "") ? "" : pais);
        $observacionEpidemiologica.val((observaciones == null || observaciones == undefined || observaciones == "") ? "" : observaciones);

        validar("#frm_observacionesEpidemiologia", "letras_numero");

        cambioSelectViajeOProcedencia();

        $viajeOProcedenciaExtranjero.on("change", cambioSelectViajeOProcedencia);

    })();


});

</script>