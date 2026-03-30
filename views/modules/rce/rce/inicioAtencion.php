<?php
session_start();
error_reporting(0);
require("../../../../config/config.php");
require_once('../../../../class/Util.class.php');               $objUtil            = new Util;
require_once('../../../../class/Connection.class.php');         $objCon             = new Connection;$objCon->db_connect();
require_once("../../../../class/RegistroClinico.class.php" );   $objRegistroClinico = new RegistroClinico;
require_once('../../../../class/Dau.class.php');                $objDau             = new Dau;
require_once('../../../../class/Diagnosticos.class.php');       $objDiag            = new Diagnosticos;
require_once('../../../../class/Imagenologia.class.php');       $objRayos           = new Imagenologia;
require_once('../../../../class/Laboratorio.class.php');        $objLaboratorio     = new Laboratorio;
require_once('../../../../class/Admision.class.php');           $objAdmision        = new Admision;
require_once('../../../../class/Rce.class.php');                $objRce             = new Rce;

$parametros                 = $objUtil->getFormulario($_POST);
$dau_id                     = $_POST['dau_id'];
$tipoMapa                   = $_POST['tipoMapa'];
$rsRce                      = $objRegistroClinico->consultaRCE($objCon,$parametros);
$rsEt                       = $objDau->obtenerEtilico($objCon);
$parametros['diagcie10']    = $rsRce[0]['regDiagnosticoCie10'];
$parametros['rce_id']       = $rsRce[0]['regId'];
$cargarPaisEpidemiologia    = $objAdmision->listarPaisNacimiento($objCon);

$version                    = $objUtil->versionJS();
if( $_POST['rce'] != 1 ){
	$col = "col-md-6";
}else{
	$col = "col-md-12";
}

?>


<div id="div_rceDetalle_<?=$dau_id?>">
    <div class="panel panel-default">
        <div class="container-fluid">
            <div class="col-md-12">
                <form id="frm_registro_clinicoSIA" class="formularios mb-1" name="frm_registro_clinico" role="form" method="POST">
                    <input      type="hidden"       id="frm_dau_idSIA"              name="frm_dau_idSIA"                value="<?=$dau_id;?>" />
                    <input      type="hidden"       id="tipoMapa"              name="tipoMapa"                value="<?=$tipoMapa;?>" />
                    <input      type="hidden"       id="frm_paciente_idSIA"         name="frm_paciente_idSIA"           value="<?=$_SESSION['datosPacienteDau']['id_paciente'];?>" />
                    <input      type="hidden"       id="frm_rce_idSIA"              name="frm_rce_idSIA"                value="<?=$parametros['rce_id'];?>">
                    <input      type="hidden"       id="frm_augeSIA"                name="frm_augeSIA"                  value="<?php if($rsRce[0]['regAuge'] == "N" || $rsRce[0]['regAuge'] == ""){echo "N";}else{echo "S";}?>" />
                    <input      type="hidden"       id="frm_mot_conSIA"             name="frm_mot_conSIA"               value="<?=$_SESSION['datosPacienteDau']['dau_motivo_consulta'];?>" />
                    
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label class="encabezado">Motivo Consulta</label>
                            <?php
                            $rsRce[0]['regMotivoConsulta'] = str_replace("<br>", "\n", $rsRce[0]['regMotivoConsulta']);
                            ?>
                            <textarea class="form-control form-control-sm mifuente12 ingresosRCE" oninput="filterInput_libre(event)" onpaste="handlePaste_libre(event)"  rows="7" id="frm_rce_motivoConsultaSIA" name="frm_rce_motivoConsultaSIA" placeholder="Motivo Consulta"><?=$rsRce[0]['regMotivoConsulta']?></textarea>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="encabezado">Hipótesis Diagnóstica Inicial</label>
                            <?php
                            $rsRce[0]['regHipotesisInicial'] = str_replace("<br>", "\n", $rsRce[0]['regHipotesisInicial']);
                            ?>
                            <textarea class="form-control form-control-sm mifuente12 ingresosRCE" oninput="filterInput_libre(event)" onpaste="handlePaste_libre(event)"  rows="7" id="frm_rce_hipotesisInicialSIA" name="frm_rce_hipotesisInicialSIA" placeholder="Hipótesis Diagnóstica Inicial"><?=$rsRce[0]['regHipotesisInicial']?></textarea>
                        </div>
                    </div>
                    <!-- <br> -->
                    <?php if( $_POST['rce'] != 1 ){ ?>
                    <div class="row">
                        <input type="hidden" id="viajeOProcedencia" value="<?php echo $rsRce[0]['dau_viaje_epidemiologico']; ?>">
                        <input type="hidden" id="pais" value="<?php echo $rsRce[0]['dau_pais_epidemiologia']; ?>">
                        <input type="hidden" id="observacion" value="<?php echo $rsRce[0]['dau_observacion_epidemiologica']; ?>">
                        <!-- Viaje o procedencia del extranjero -->
                        <div id="" class="col-md-6">
                            <label for="" class="control-label encabezado">¿Viaje o procedencia del extranjero en el último mes?</label>
                            <div class="input-group">
                                <select id="frm_viajeEpidemiologico" name="frm_viajeEpidemiologico" class="form-control form-control-sm mifuente12 " >
                                    <option value="" selected disabled>Seleccione Opción</option>
                                    <option value="N">No</option>
                                    <option value="S">Si</option>
                                </select>
                            </div>
                        </div>
                        <div id="divPaisEpidemiologia" class="col-md-6">
                            <label for="" class="control-label encabezado">País</label>
                            <div class="input-group">
                                <select class="form-control form-control-sm mifuente12" id='frm_paisEpidemiologia' name="frm_paisEpidemiologia">
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
                    </div>
                    <div class="row">
                        <!-- Observaciones -->
                        <div id="divObservacionesEpidemiologia" class="col-md-12">
                            <label for="" class="control-label encabezado">Observaciones</label>
                            <div class="input-group">

                            <textarea  oninput="filterInput_libre(event)" onpaste="handlePaste_libre(event)"  onkeypress="return limitaCampoTexto2(event, 500, 'frm_observacionEpidemiologica');" onkeyup="actualizaInfoTexto2(500, 'frm_observacionEpidemiologica', 'info_frm_observacionEpidemiologica')" onDrop="return false" maxlength="500" id="frm_observacionEpidemiologica" maxlength="500" id="frm_observacionEpidemiologica" onDrop="return false" class="form-control form-control-sm mifuente12 " rows="1" name="frm_observacionEpidemiologica" placeholder="Hipótesis Diagnóstica Inicial"><?=$rsRce[0]['regHipotesisInicial']?></textarea>
                            </div>
                            <div style = "margin-left: 1%;">
                                <p style="font-size: 12px; color: #606060" id="info_frm_observacionEpidemiologica">
                                    Máximo 500 caracteres <span id="maximo"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <label class="encabezado">Alcoholemia</label><br>
                            <div class="form-check form-check-inline">
								<input class="form-check-input mifuente12" type="radio" name="frm_rcedetalle_rbalcSIA" id="rdbtn_alcoh_siSIA" value="Si"<?php if($_SESSION['datosPacienteDau']['dau_alcoholemia_numero_frasco']!=''){echo "selected";}?>>
								<label class="form-check-label mifuente12" for="inlineRadio1">Sí</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input mifuente12" type="radio" name="frm_rcedetalle_rbalcSIA" id="rdbtn_alcoh_noSIA" value="No" <?php if($_SESSION['datosPacienteDau']['dau_alcoholemia_numero_frasco']==''){echo "selected";}?>>
								<label class="form-check-label mifuente12" for="inlineRadio2">No</label>
							</div>
                        </div>
                        <div id="nfrAlcohSIA" class="col-md-3" >
                            <label class="encabezado">N° de Frasco</label>
                            <input type="text" class="form-control form-control-sm mifuente12" id="frm_rce_n_frascoSIA" name="frm_rce_n_frascoSIA" placeholder="N°" value="<?=$_SESSION['datosPacienteDau']['dau_alcoholemia_numero_frasco'];?>"></input>
                        </div>
                        <div class="col-md-3" id="estAlcohSIA" >
                            <label class="encabezado">Estado Etílico</label>
                            <select class="form-control form-control-sm mifuente12" id="frm_rce_est_etiSIA" name="frm_rce_est_etiSIA">
                                <option value="5" disabled selected>Estado</option>
                                <?php
                                for ( $i = 0; $i < count($rsEt) ; $i++ ) {
                                ?>
                                <option value="<?=$rsEt[$i]['eti_id']?>" <?php if($_SESSION['datosPacienteDau']['dau_alcoholemia_estado_etilico']==$rsEt[$i]['eti_id']){ echo "selected";}?>> <?=$rsEt[$i]['eti_descripcion']?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div id="fechaAlcohSIA" class="col-md-3">
                            <label class="encabezado">Fecha / Hora</label>
                            <input type="text" class="form-control form-control-sm mifuente12" id="frm_rce_alc_fechSIA" name="frm_rce_alc_fechSIA" placeholder="dd/mm/aa" value="<?php if($_SESSION['datosPacienteDau']['dau_alcoholemia_fecha'] != ""){echo date("d-m-Y H:i",strtotime($_SESSION['datosPacienteDau']['dau_alcoholemia_fecha']));}?>"></input>
                            <input type="hidden" class="form-control form-control-sm mifuente12" id="frm_rce_alc_fech_admSIA" value="<?php echo date("d-m-Y H:i",strtotime($_SESSION['datosPacienteDau']['dau_admision_fecha']));?>"></input>
                            <input type="hidden" class="form-control form-control-sm mifuente12" id="frm_rce_alc_fech_actSIA" value="<?php echo date("d-m-Y H:i");?>"></input>
                        </div>
                    </div>
                	<?php } ?>
                </form>
            </div>
        </div>
    </div>
    <?php if( $_POST['rce'] == 1 ){ ?>
    <div class="row">
        <?php
        if ( pacienteTieneEstadoDauCerrado($_SESSION['datosPacienteDau']['est_id']) ) {
            $disabledCrearPlantilla = 'disabled';
        } ?>
    	
        <div class="col-lg-3" >
            <select class="form-control  form-control-sm mifuente" id="slc_nombrePlantilla" name="slc_nombrePlantilla">
                <option value="">Seleccione Plantilla</option>
                <?php
                if ( isset($_SESSION['MM_Username'.SessionName]) ) {
                    $parametros['idMedico'] = $_SESSION['MM_Username'.SessionName];
                    $respuestaConsulta      = $objRce->obtenerNombrePlantillasInicioAtencion($objCon, $parametros['idMedico']);
                    $totalRespuestaConsulta = count($respuestaConsulta);
                    for ($i=0; $i < $totalRespuestaConsulta ; $i++) {
                    ?>
                        <option value="<?php echo $respuestaConsulta[$i]['idPlantilla']; ?>" >  <?php echo $respuestaConsulta[$i]['nombrePlantilla']; ?> </option>
                    <?php }
                } else {
                    echo '<option value="" selected>Iniciar Sesión para Cargar Plantillas</option>';
                }
                ?>
            </select>
        </div>
        <div class="col-lg-3"> <button id="btnCrearPlantillaInicioAtencion" type="button" name="btnCrearPlantillaInicioAtencion" class="btn btn-sm btn-outline-primarydiag  mifuente11 col-lg-12 text-center" ><i class="fas fa-plus mr-2"></i>Crear Plantilla</button> </div>
		<div class="col-lg-3">
        </div>
        <div class="col-lg-3"> <button id="btnInicioAtencion" type="button" name="btnInicioAtencion" class="btn btn-sm btn-outline-primarydiag  mifuente11 col-lg-12 text-center" ><i class="fas fa-plus mr-2"></i>Iniciar Atención</button> </div>
	</div>
    <?php }else if( $_POST['rce'] == 2 ){ ?>
    <?php }else{?>
    <hr>
    <div class="row">
		<div class="col-lg-9">
		</div>
		<div class="col-lg-3"> <button id="btnGuardarModificacionSolicitudInicioAtencion" type="button" name="btnGuardarApliNEA" class="btn btn-sm btn-primary2  col-lg-12 text-center"><svg class="svg-inline--fa fa-save fa-w-14 mr-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="save" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M433.941 129.941l-83.882-83.882A48 48 0 0 0 316.118 32H48C21.49 32 0 53.49 0 80v352c0 26.51 21.49 48 48 48h352c26.51 0 48-21.49 48-48V163.882a48 48 0 0 0-14.059-33.941zM224 416c-35.346 0-64-28.654-64-64 0-35.346 28.654-64 64-64s64 28.654 64 64c0 35.346-28.654 64-64 64zm96-304.52V212c0 6.627-5.373 12-12 12H76c-6.627 0-12-5.373-12-12V108c0-6.627 5.373-12 12-12h228.52c3.183 0 6.235 1.264 8.485 3.515l3.48 3.48A11.996 11.996 0 0 1 320 111.48z"></path></svg>Guardar</button> </div>
	</div>
	<?php } ?>
</div>
<?php 
function pacienteTieneEstadoDauCerrado ( $estadoDau ) {
    if ( $estadoDau != 5 && $estadoDau != 6 && $estadoDau != 7 ) {
        return false;
    }
    return true;
}
?>
<script type="text/javascript">
$(document).ready(function() {
	let chk 			= 0;
	let alcoh 			= $("#frm_mot_conSIA").val();
	let fecha_actual    = $('#frm_rce_alc_fech_actSIA').val();
	let fecha_admision  = $('#frm_rce_alc_fech_admSIA').val();
	let dau_id 		   	= $('#frm_dau_idSIA').val();
	let tipoMapa 		= $('#tipoMapa').val();
	validar("#frm_rce_n_frascoSIA","numero");
	validar("#fechaAlcohSIA","fecha");
	$("#estAlcohSIA").hide();
	$("#fechaAlcohSIA").hide();
	$("#nfrAlcohSIA").hide();
	$('#frm_rce_alc_fechSIA').datetimepicker({
        language: 'es',
        todayHighlight: true,
        autoclose: true,
        clearBtn: true,
        minDate: fecha_admision,
        maxDate: fecha_actual
	});
	if ( $("#frm_rce_alc_fechSIA").val() == "" && $("#frm_rce_n_frascoSIA").val() == "" ) {
		$("input[name='frm_rcedetalle_rbalcSIA']").prop( "checked", true);
		if ( alcoh == 5 ) {
			$("#rdbtn_alcoh_siSIA").prop( "checked", true);
			$("#estAlcohSIA").show("fast");
			$("#fechaAlcohSIA").show("fast");
			$("#nfrAlcohSIA").show("fast");
			chk = 1;
		}
	} else {
		$("#rdbtn_alcoh_siSIA").prop( "checked", true);
		$("#estAlcohSIA").show("fast");
		$("#fechaAlcohSIA").show("fast");
		$("#nfrAlcohSIA").show("fast");
		chk = 1;
	}
	$("input[name='frm_rcedetalle_rbalcSIA']").click(function() {
		if ( $(this).val() == "Si" ) {
			$("#estAlcohSIA").show("fast");
			$("#fechaAlcohSIA").show("fast");
			$("#nfrAlcohSIA").show("fast");
			chk = 1;
		} else if ( $(this).val() == "No" ) {
			$("#estAlcohSIA").hide("fast");
			$("#fechaAlcohSIA").hide("fast");
			$("#nfrAlcohSIA").hide("fast");
			if ( $("#frm_rce_est_etiSIA").val() == 5 && $("#frm_rce_alc_fechSIA").val() == "" && $("#frm_rce_n_frascoSIA").val() == "" ) {
				$("#frm_rce_est_etiSIA").val(5);
				$("#frm_rce_alc_fechSIA").val("");
				$("#frm_rce_n_frascoSIA").val("");
			}
			chk = 0;
		}
	});

	$("#btnGuardarModificacionSolicitudInicioAtencion").click(function(){
		if ( perfilUsuario === 'administrativo') {
			return;
		}
		$.validity.start();
		if ( $("#rdbtn_alcoh_siSIA").is(':checked') ) {
			if ( $("#frm_rce_n_frascoSIA").val() == "" ) {
				$("#frm_rce_n_frascoSIA").assert(false,'Ingrese n° frasco');
			}
			if ( $("#frm_rce_est_etiSIA option[value=5]").is(':selected') ) {
				$("#frm_rce_est_etiSIA").assert(false,'Seleccione una opción');
			}
			if ( $("#frm_rce_alc_fechSIA").val() == "" ) {
				$("#frm_rce_alc_fechSIA").assert(false,'Ingrese fecha y hora');
			}
		}
		if ( $("#frm_rce_motivoConsultaSIA").val() == "" ) {
			$("#frm_rce_motivoConsultaSIA").assert(false,'Ingrese Motivo de Consulta');
		}
		if ( $("#frm_rce_hipotesisInicialSIA").val() == "" ) {
			$("#frm_rce_hipotesisInicialSIA").assert(false,'Ingrese Diagnóstica Inicial');
		}
		result = $.validity.end();
		if ( result.valid == false ) {
			return false;
		}
		respujestaPermiso = ajaxRequest(raiz+'/controllers/server/categorizacion/main_controller.php', 'accion=verificarPermisoUsuario&boton=btn_rce_guardar', 'POST', 'JSON', 1, 'Verificando permiso');
		if(respujestaPermiso){
			var confirmarGuardarInicioAtencion = function(){
				let tipoInicioAtencion = '';
				if ( banderapiso === 'RCE' ) {
					tipoInicioAtencion = 'modificarInicioAtencion';
				}

				ajaxRequest(`${raiz}/controllers/server/dau/main_controller.php`, $('#frm_registro_clinicoSIA').serialize()+`&chk=${chk}&dau_id=${dau_id}&tipoInicioAtencion=${tipoInicioAtencion}&accion=modificarInicioAtencion`, 'POST', 'JSON', 1,'Iniciando antencion...', verificarRespuesta);
				function verificarRespuesta(respuestaAjaxRequest) {
					switch ( respuestaAjaxRequest.status ) {
						case "success":
                            if(tipoInicioAtencion == 'modificarInicioAtencion'){
                                ajaxContent(raiz+'/views/modules/rce/medico/rce.php','tipoMapa='+tipoMapa+`&dau_id=${dau_id}`,'#contenido','', true);
                                $('#modalInicioAtencion').modal( 'hide' ).data( 'bs.modal', null );
                                
                            }else{
							     ajaxContent(raiz+'/views/modules/mapa_piso_full/detalle_dau/detalle_dau.php','dau_id='+dau_id+'&tipoMapa='+tipoMapa,'#contenido','', true);
                             }
							$('#modalDetalleCategorizacion').modal( 'hide' ).data( 'bs.modal', null );
						break;
						case "error":
							ErrorSistemaDefecto();
							break;
						default:
							ErrorSistemaDefecto();
						break;
					}
				}
			}
			modalConfirmacionNuevo("Inicio de Atención al Paciente", "Estimado se procedera a iniciar la atención del paciente, <b>¿Desea continuar?</b></label>","primary", confirmarGuardarInicioAtencion);
		}else{
			ErrorPermiso();
		}
	});
    $("#slc_nombrePlantilla").on('change', function() {

        if ( $("#slc_nombrePlantilla").val() == '' ) {
            $("#frm_rce_motivoConsultaSIA").val('');
            $("#frm_rce_motivoConsultaSIA").val('');
            return;
        }
        var solicitudServidor = function(response){
            switch(response.status){
                case "success":
                    $("#frm_rce_motivoConsultaSIA").val(response.motivoConsulta);
                    $("#frm_rce_hipotesisInicialSIA").val(response.hipotesisDiagnosticaInicial);
                break;
                default:        
                    ErrorSistemaDefecto();
                break;
            }
        };
        ajaxRequest(raiz+'/controllers/server/medico/main_controller.php','idPlantilla='+$("#slc_nombrePlantilla").val()+'&accion=obtenerPlantillaInicioAtencion', 'POST', 'JSON', 1,'Cargando...', solicitudServidor);

    });

    $("#btnCrearPlantillaInicioAtencion").on('click', function() {
        if ( perfilUsuario === 'administrativo') {
            return;
        }
        $.validity.start();
        if ( $("#frm_rce_motivoConsultaSIA").val() == "" ) {
            $("#frm_rce_motivoConsultaSIA").assert(false,'Ingrese Motivo de Consulta');
        }
        if ( $("#frm_rce_hipotesisInicialSIA").val() == "" ) {
            $("#frm_rce_hipotesisInicialSIA").assert(false,'Ingrese Diagnóstica Inicial');
        }  
        
        result = $.validity.end();
        if ( result.valid == false ) {
            return false;
        }
        modalFormulario_noCabecera('', raiz+"/views/modules/rce/plantillas/modalNombrePlantilla.php", $('#frm_registro_clinicoSIA').serialize(), "#ver_plantilla", "modal-md", "", "fas fa-plus");
    });

    $("#btnInicioAtencion").click(function(){
        if ( perfilUsuario === 'administrativo') {
            return;
        }
        $.validity.start();
        if ( $("#rdbtn_alcoh_siSIA").is(':checked') ) {
            if ( $("#frm_rce_n_frascoSIA").val() == "" ) {
                $("#frm_rce_n_frascoSIA").assert(false,'Ingrese n° frasco');
            }
            if ( $("#frm_rce_est_etiSIA option[value=5]").is(':selected') ) {
                $("#frm_rce_est_etiSIA").assert(false,'Seleccione una opción');
            }
            if ( $("#frm_rce_alc_fechSIA").val() == "" ) {
                $("#frm_rce_alc_fechSIA").assert(false,'Ingrese fecha y hora');
            }
        }
        if ( $("#frm_rce_motivoConsultaSIA").val() == "" ) {
            $("#frm_rce_motivoConsultaSIA").assert(false,'Ingrese Motivo de Consulta');
        }
        if ( $("#frm_rce_hipotesisInicialSIA").val() == "" ) {
            $("#frm_rce_hipotesisInicialSIA").assert(false,'Ingrese Diagnóstica Inicial');
        }
        result = $.validity.end();
        if ( result.valid == false ) {
            return false;
        }
        respujestaPermiso = ajaxRequest(raiz+'/controllers/server/categorizacion/main_controller.php', 'accion=verificarPermisoUsuario&boton=btn_rce_guardar', 'POST', 'JSON', 1, 'Verificando permiso');
        if(respujestaPermiso){
            var confirmarGuardarInicioAtencion = function(){
                let tipoInicioAtencion = '';
                // if ( banderapiso === 'RCE' ) {
                //     tipoInicioAtencion = 'modificarInicioAtencion';
                // }
                ajaxRequest(`${raiz}/controllers/server/dau/main_controller.php`, $('#frm_registro_clinicoSIA').serialize()+`&chk=${chk}&dau_id=${dau_id}&tipoInicioAtencion=${tipoInicioAtencion}&accion=registrarInicioAtencion`, 'POST', 'JSON', 1,'Iniciando antencion...', verificarRespuesta);
                function verificarRespuesta(respuestaAjaxRequest) {
                    switch ( respuestaAjaxRequest.status ) {
                        case "success":
                            
                            ajaxContent(raiz+'/views/modules/rce/medico/rce.php','tipoMapa='+tipoMapa+`&dau_id=${dau_id}`,'#contenido','', true);
                            $('#modalDetalleCategorizacion').modal( 'hide' ).data( 'bs.modal', null );
                        break;
                        case "error":
                            ErrorSistemaDefecto();
                            break;
                        default:
                            ErrorSistemaDefecto();
                        break;
                    }
                }
            }
            modalConfirmacionNuevo("Inicio de Atención al Paciente", "Estimado se procedera a iniciar la atención del paciente, <b>¿Desea continuar?</b></label>","primary", confirmarGuardarInicioAtencion);
        }else{
            ErrorPermiso();
        }
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
				return;
			}
			$(`${divPaisEpidemiologia}`).hide(100);
			$(`${divObservacionEpidemiologica}`).hide(100);
        }
        divViajeEpidemiologico       = "#divViajeEpidemiologico";
        divPaisEpidemiologia         = "#divPaisEpidemiologia";
        divObservacionEpidemiologica = "#divObservacionesEpidemiologia";
        viajeOProcedencia            = $("#viajeOProcedencia").val();
        pais                         = $("#pais").val();
        observaciones                = $("#observacion").val();
		$viajeOProcedenciaExtranjero = $("#frm_viajeEpidemiologico");
		$paisEpidemiologia   		 = $("#frm_paisEpidemiologia");
		$observacionEpidemiologica   = $("#frm_observacionEpidemiologica");
        $viajeOProcedenciaExtranjero.val(viajeOProcedencia);
        $paisEpidemiologia.val(pais);
        $observacionEpidemiologica.val(observaciones);
		validar("#frm_observacionesEpidemiologia", "letras_numero");
        cambioSelectViajeOProcedencia();
		$viajeOProcedenciaExtranjero.on("change", cambioSelectViajeOProcedencia);
	})();
});
</script>