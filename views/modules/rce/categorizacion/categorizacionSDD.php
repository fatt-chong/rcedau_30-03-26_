<?php
require("../../../../config/config.php");;
require_once('../../../../class/Connection.class.php');        $objCon          = new Connection; $objCon->db_connect();
require_once('../../../../class/Categorizacion.class.php');    $objCat          = new Categorizacion;
require_once('../../../../class/Util.class.php');              $objUtil         = new Util;
require_once('../../../../class/Dau.class.php');               $objDau          = new Dau;
require_once('../../../../class/Admision.class.php');          $objAdmision     = new Admision;

$dau_id                     = $_POST['t_trid'];
$parametros['dau_id']       = $dau_id;
$datos                      = $objCat -> searchPaciente($objCon,$dau_id);
$datosdauFecha              = $objDau->ListarPacientesDau($objCon,$parametros);
$cargarPaisEpidemiologia    = $objAdmision->listarPaisNacimiento($objCon);
$fechaAdmision              = date("Y-m-d",strtotime($datosdauFecha[0]['dau_admision_fecha']));
$horaAdmision               = date("H: i", strtotime($datosdauFecha[0]['dau_admision_fecha']));
$fechaHora                  = date("Y-m-d");
$horaFecha                  = date("H: i");
$version                    = $objUtil->versionJS();
?>
<!--
################################################################################################################################################
                                                       			    CARGA JS
-->
<script type="text/javascript" src="<?=PATH?>/controllers/client/categorizacion/categorizacionSDD.js?v=<?=$version;?>"></script>
<script type="text/javascript">
    function limitar ( limite, campo ) {
        if  ( $("[name='"+campo+"']").val() > limite ) {
            $("[name='"+campo+"']").val(limite);
        }
    }
    function limitartemp ( minimo, campo ) {
        if ( $("[name='"+campo+"']").val() < minimo ) {
            $("[name='"+campo+"']").val(minimo);
        }
    }
</script>
<!--
################################################################################################################################################
                                               			DESPLIEGUE FORMULARIO CATEGORIZAR
-->
<form name="frm_triageSDD" id="frm_triageSDD">
    <div class="bd-callout bd-callout-warning ">
        <div class="row pr-2 pl-2">
            <div class="col-lg-1 ">
                <p class="m-0 p-0 mifuente">DAU</p>
            </div>
            <div class="col-lg-2 ">
                <p class="m-0 p-0 mifuente">:<label name="dau_id" id="dau_id" class="ml-2 texto-valor mb-0 " ><?php echo $dau_id?></label></p>

                <input type="hidden" name="inp_recat" id="inp_recat" value="<?= $objUtil->validateVar($_POST['recat'] ?? null) ?>" >
                <input type="hidden" name="recategorizar" id="recategorizar" value="<?= $objUtil->validateVar($_POST['recategorizar'] ?? null) ?>" >
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
                <?=$datos[0]['dau_motivo_descripcion']?>
                </label> </p>
            </div>
            <label id="añoPac" name="añoPac" hidden><?php echo $objCat->edadAno($datos[0]['fechanac']); ?></label>
            <label id="mesPac" name="mesPac" hidden><?php echo $objCat->edadMes($datos[0]['fechanac']); ?></label>
            <label id="diasPac" name="diasPac" hidden><?php echo $objCat->edadDia($datos[0]['fechanac']); ?></label>
        </div>
    </div>
    <hr style="margin-bottom : 0.1rem; margin-top: 0.1rem">
    <div class="row">
        <div class="col-lg-12">
            <input type="hidden" id="viajeOProcedencia" value="<?php echo $datos[0]['dau_viaje_epidemiologico']; ?>">
            <input type="hidden" id="pais" value="<?php echo $datos[0]['dau_pais_epidemiologia']; ?>">
            <input type="hidden" id="observacion" value="<?php echo $datos[0]['dau_observacion_epidemiologica']; ?>">
            <!-- Viaje o procedencia del extranjero -->
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
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" align="center">
            <div class="row">
                <div  class="col-md-4">&nbsp;
                </div>
                <div id="e4categdiv" class="col-md-4">
                    <div class="form-group" style="text-align:center;">
                        <label for="e4categ" class="control-label mifuente tituloNegrita" style="text-align:center">Categorización</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-adjust"></i></span>
                            <select id="e4categ" name="dau_cat_4_categ" class="form-control form-control-sm mifuente" >
                                <option value="" disabled selected>Categorización</option>
                                <option value="C1">C1</option>
                                <option value="C2">C2</option>
                                <option value="C3">C3</option>
                                <option value="C4">C4</option>
                                <option value="C5">C5</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div  class="col-md-4">&nbsp;
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="etapa4">
        <div class="col-md-12" align="center">
            <div class="row">
                <div  class="col-md-4">&nbsp;
                </div>
                <div  class="col-md-4">
                    <div class="form-group" style="text-align:center;">
                        <label  class="control-label mifuente tituloNegrita" style="text-align:center">Signos Vitales</label>
                    </div>
                </div>
                <div  class="col-md-4">&nbsp;
                </div>
                <div  class="col">
                    <div class="form-group" style="text-align:center;">
                        <label  class="control-label mifuente tituloNegrita" style="text-align:center">Presión</label>
                        <div class="row">
                            <div class="col">
                                <input type="text" class="form-control form-control-sm mifuente text-center" id="e4txtFR_1" onkeyup="limitar(300,'dau_cat_4_fr_1');" name="dau_cat_4_fr_1" maxlength="3" placeholder="0 - 300">
                            </div>
                            <div class="collg-1">
                                <label  class="control-label mifuente text-center tituloNegrita" style="text-align:center">/</label>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control form-control-sm mifuente text-center" id="e4txtFR_2" onkeyup="limitar(200,'dau_cat_4_fr_2');" name="dau_cat_4_fr_2" maxlength="3" placeholder="0 - 200">
                            </div>
                        </div>
                        <div id="mensajeAlert2"><input type="text" id="mensajeAlert2" hidden></div>
                    </div>
                </div>
                <div  class="col">
                    <div class="form-group" style="text-align:center;">
                        <label  class="control-label mifuente tituloNegrita" style="text-align:center">Pulso</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                            <input type="text" class="form-control form-control-sm mifuente text-center" id="e4txtFC" onkeyup="limitar(250,'dau_cat_4_fc');" name="dau_cat_4_fc" maxlength="3" placeholder="0 - 250">
                            <div id="mensajeAlert3"><input type="text" id="mensajeAlert3" hidden></div>
                        </div>
                    </div>
                </div>
                <div  class="col" id="e4temp">
                    <div class="form-group" style="text-align:center;">
                        <label  class="control-label mifuente tituloNegrita" style="text-align:center">Temperatura (°C)</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                            <input type="text" class="form-control form-control-sm mifuente text-center" id="e4txtTe" onblur="limitartemp(20.0,'dau_cat_4_temp');" onkeyup="limitar(50.0,'dau_cat_4_temp');" name="dau_cat_4_temp" maxlength="4" placeholder="20.0 - 50.0">
                            <div id="mensajeAlert4"><input type="text" id="mensajeAlert4" hidden></div>
                        </div>
                    </div>
                </div>
                <div  class="col">
                    <div class="form-group" style="text-align:center;">
                        <label  class="control-label mifuente tituloNegrita" style="text-align:center">Saturometría  (SaO2)</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                            <input id="e4txtSat" type="text" class="form-control form-control-sm mifuente text-center" onkeyup="limitar(100,'dau_cat_4_satu');" name="dau_cat_4_satu" maxlength="3" placeholder="0 - 100">
                            <div id="mensajeAlert"><input type="text" id="mensajeAlert" hidden></div>
                        </div>
                    </div>
                </div>
                <div  class="col">
                    <div class="form-group" style="text-align:center;">
                        <label  class="control-label mifuente tituloNegrita" style="text-align:center">Temperatura Rectal (°C)</label>
                        <div class="input-group">
                        <input type="text" class="form-control form-control-sm mifuente text-center" id="e4txtTe_rec" onblur="limitartemp(20.0,'dau_cat_4_temp_rec');" onkeyup="limitar(50.0,'dau_cat_4_temp_rec');" name="dau_cat_4_temp_rec" maxlength="4" placeholder="20.0 - 50.0">
                        <div id="mensajeAlert4"><input type="text" id="mensajeAlert4" hidden></div>

                            <!-- <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span> -->
                            <!-- <input id="e4txtSat" type="text" class="form-control form-control-sm mifuente text-center" onkeyup="limitar(100,'dau_cat_4_satu');" name="dau_cat_4_satu" maxlength="3" placeholder="0 - 100"> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<!--
################################################################################################################################################
                                               			CAMPOS OCULTOS
-->
<input type="hidden" name="idDau" id="idDau" value="<?php echo $dau_id; ?>" >