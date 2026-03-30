<div  class="scrollModal" >
<?php
session_start();
error_reporting(0);
require_once("../../../../class/Util.class.php");              $objUtil            = new Util;
require_once("../../../../class/Connection.class.php");         $objCon             = new Connection();
require_once('../../../../class/Pronostico.class.php');         $objPronostico      = new Pronostico;
require_once("../../../../class/Servicios.class.php");          $objServicio        = new Servicios;
require_once("../../../../class/Dau.class.php" );               $objDetalleDau      = new Dau;
require_once("../../../../class/Agenda.class.php" );            $objAgenda          = new Agenda;
require_once("../../../../class/RegistroClinico.class.php" );   $objRegistroClinico = new RegistroClinico;
require_once('../../../../class/Categorizacion.class.php');     $objCate            = new Categorizacion;
require_once('../../../../class/Rce.class.php');                $objRce             = new Rce;
require_once('../../../../class/Diagnosticos.class.php');       $objDiagnosticos    = new Diagnosticos;
require_once('../../../../class/Admision.class.php');           $objAdmision        = new Admision;
require_once('../../../../class/HospitalAmigo.class.php');           $objHospitalAmigo        = new HospitalAmigo;
require("../../../../config/config.php");

$objCon->db_connect();

$parametros                   = $objUtil->getFormulario($_POST);
$datosPaciente 				  = $objCate -> searchPaciente($objCon, $parametros['dau_id']);
$obtenerIndicacionEgreso      = $objDetalleDau->obtenerIndicacionEgreso($objCon,$parametros);
$ListarServiciosDau           = $objServicio->ListarServiciosDau($objCon);
$listarIndicaciones           = $objDetalleDau->listarIndicaciones($objCon,$parametros);
$obtenerEstadosIndicaciones   = $objDetalleDau->obtenerEstadosIndicaciones($objCon,$parametros);
$obtenerServiciosDau          = $objServicio->obtenerServiciosDau($objCon,$parametros);
$listaDestino                 = $objDetalleDau->getDatosEgreso($objCon,$parametros);
$destinoControl               = $listaDestino[0]['des_id'];
$rsListado                    = $objPronostico->listarPronosticos($objCon);
$rsRce                        = $objRegistroClinico->consultaRCE($objCon,$parametros);
$resEspecialidad              = $objAgenda->getEspecialidadLE($objCon);
$rsAPS                        = $objDetalleDau->getAPS($objCon);
$buscarCamaYsala              = $objDetalleDau->buscarCamaYsala($objCon,$parametros);
if ($_SESSION['datosPacienteDau']['dau_atencion'] == 1 ) {
    $filtroIndicacion       = 'indicacionAdulto';
    $rsDerivacion           = $objDetalleDau->getAltaDerivacionMPISO($objCon);
}
if ($_SESSION['datosPacienteDau']['dau_atencion'] == 2 ) {
    $filtroIndicacion       = 'indicacionPediatrico';
    $rsDerivacion           = $objDetalleDau->getAltaDerivacionMPISO($objCon);
}
if ($_SESSION['datosPacienteDau']['dau_atencion'] == 3) {
    $filtroIndicacion       = 'indicacionGinecologico';
    $rsDerivacion           = $objDetalleDau->getAltaDerivacionMPISOGO($objCon);
}
if($listaDestino[0]['dau_ind_aps'] == ""){
    $parametrosCentro['con_id']             = $datosPaciente[0]['dau_paciente_aps'];
    $rslistarConsultoriosAPS                = $objAdmision->listarConsultoriosAPS($objCon,$parametrosCentro);
    $listaDestino[0]['dau_ind_aps']         = $rslistarConsultoriosAPS[0]['ESTAcodigo'];
    // $listaDestino[0]['dau_ind_aps'] = $datosPaciente[0]['dau_paciente_aps'];
}
$ListarIndicacionEgreso     = $objDetalleDau->ListarIndicacionEgreso($objCon, $filtroIndicacion);
$fecha_admision             = date("d-m-Y H:i",strtotime($_SESSION['datosPacienteDau']['dau_admision_fecha']));
$fecha                      = date("d-m-Y H:i");
$dau_inicio_atencion_fecha  = date("Y-m-d",strtotime($_SESSION['datosPacienteDau']['dau_admision_fecha']));
$dau_inicio_atencion_hora   = date("H:i:s", strtotime($_SESSION['datosPacienteDau']['dau_admision_fecha']));
$fechaHora                  = date("Y-m-d");
$horaFecha                  = date("H:i:s");
$parametros['cta_cte']      = $datosPaciente[0]['idctacte'];
$rsRce_diagnostico          = $objDiagnosticos->obtenerRce_diagnosticoCompartido($objCon,$parametros);
$version                    = $objUtil->versionJS();

$getHorarioServidor         = $objUtil->getHorarioServidor($objCon);

$datosAcompaniante['idDau'] = $parametros['dau_id'];
$rsobtenerAcompaniante      = $objHospitalAmigo->obtenerAcompaniante($objCon, $datosAcompaniante);
if(count($rsobtenerAcompaniante) == 0 ){
    $rsobtenerAcompaniante[0]['fechaEntregaInformacionMedica']  = $getHorarioServidor[0]['fecha'];
    $rsobtenerAcompaniante[0]['horaEntregaInformacionMedica']   = $getHorarioServidor[0]['hora'];
    $rsobtenerAcompaniante[0]['nombreMedico']                   = $_SESSION['MM_UsernameName'.SessionName];
}


?>

<script type="text/javascript" src="<?=PATH?>/controllers/client/rce/alta_urgencia/alta_urgencia.js?v=<?=$version;?>125423442334142"></script>
<div id="div_altaUrgencia_<?=$dau_id?>">
    <div class="container-fluid">
        <div class="col-md-12">
            <form id="frmIndicacionEgreso" class="formularios" name="frmIndicacionEgreso" role="form" method="POST">
                <div class="row">
                    <div class="col-lg-2" >
                        <button type="button" id="modalPlantillaAltaUrgencia" name="modalPlantillaAltaUrgencia" type="button" class="btn btn btn-sm btn-outline-primarydiag  mifuente col-lg-12 botonesActivos">Crear Plantilla</button>
                    </div>
                    <div class="col-lg-3">
                        <select class="form-control form-control-sm mifuente  mifuente" id="slc_nombrePlantilla" name="slc_nombrePlantilla">
                            <option value="">Seleccione Plantilla</option>
                            <?php
                            if ( isset($_SESSION['MM_Username'.SessionName]) ) {
                                $parametros['idMedico'] = $_SESSION['MM_Username'.SessionName];
                                $respuestaConsulta = $objRce->obtenerNombrePlantillasAltaUrgencia($objCon, $parametros['idMedico']);
                                $totalRespuestaConsulta = count($respuestaConsulta);
                                for ($i=0; $i < $totalRespuestaConsulta ; $i++) {
                                ?>
                                    <option value="<?php echo $respuestaConsulta[$i]['idPlantilla']; ?>" >  <?php echo $respuestaConsulta[$i]['nombrePlantilla']; ?> </option>
                                <?php
                                }
                            } else {
                                echo '<option value="">Iniciar Sesión para Cargar Plantillas</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-4" id="actualizarplantilla" style="display: none;" >
                        <div class="row">
                        <button  type="button" id="modalUpdatePlantillaAltaUrgencia" name="modalUpdatePlantillaAltaUrgencia" type="button" class="btn btn btn-sm btn-outline-secondary  mifuente col-lg-5 botonesActivos mr-2" ><i class="far fa-edit mr-2"></i>Actualizar Plantilla</button>
                        <button  type="button" id="modalEliminarPlantillaAltaUrgencia" name="modalUpdatePlantillaAltaUrgencia" type="button" class="btn btn btn-sm btn-outline-danger  mifuente col-lg-5 botonesActivos" ><i class="fas fa-eraser mr-2"></i>Eliminar Plantilla</button>
                        </div>

                    </div>
                </div>
                <hr >
                <div class="row" hidden>
                    <div class="col-md-4">
                        <label class="encabezado">Fecha de Indicación de Egreso</label>
                        <input type="date"  class="form-control form-control-sm mifuente " placeholder="DD-MM-AA" id="frm_fecha_date" max="<?=$fechaHora?>" min="<?=$dau_inicio_atencion_fecha?>" name="frm_fecha_date" value="<?=$fechaHora;?>">
                    </div>
                    <div class="col-md-4">
                        <label class="encabezado">Hora de Indicación de Egreso</label>
                        <input type="input"  class="form-control form-control-sm mifuente " placeholder="HH:MM" id="frm_hora_date" min="<?=$dau_inicio_atencion_hora?>" max="<?=$horaFecha?>" name="frm_hora_date" value="<?=$horaFecha;?>" >
                    </div>
                </div>
                <div class="alert alert-warning" role="alert" style="text-align: center; font-size: 12px;">
                    IMPORTANTE: Según instrucciones Ministeriales, los códigos a registrar para <b>COVID-19</b> en Atenciones de Urgencia deberán ser<br><br>
                    <b>U071 COVID-19, virus identificado (Confirmado con resultado positivo de la prueba de COVID-19)</b><br>
                    <b>U072 COVID-19, virus no identificado (Caso sospechoso de COVID-19)</b><br><br>
                    Los códigos <b>Z290 Aislamiento</b> y <b>Z208 Contacto con y sin exposición a otras enfermedades transmisibles</b> deben ser utilizados sólo para uso de emisión de licencias médicas
                </div>
                <div id="recetaGes" class="alert alert-danger" role="alert" style="text-align: center; font-size: 12px;">
                    IMPORTANTE: No olvide entregar la <b>Receta GES</b> al paciente
                    <input type="hidden" name="PacienteGESReceta" id="PacienteGESReceta" >
                </div>
                <div id="cie10Ges" class="alert alert-danger" role="alert" style="text-align: center; font-size: 12px; display: none;">
                    IMPORTANTE: No olvides generar el <b>FORMULARIO DE CONSTANCIA INFORMACIÓN AL PACIENTE GES</b> <br><br>
                    <button id="formulariosEnfermeria" name="formulariosEnfermeria" type="button" class="btn formulariosEnfermeria btn-outline-danger btn-sm mb-2 btn-block " data-toggle="tooltip" data-placement="left" title="" data-original-title="Formularios"><svg class="svg-inline--fa fa-file fa-w-12" style="font-size: 16px !important;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="file" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" data-fa-i2svg=""><path fill="currentColor" d="M224 136V0H24C10.7 0 0 10.7 0 24v464c0 13.3 10.7 24 24 24h336c13.3 0 24-10.7 24-24V160H248c-13.2 0-24-10.8-24-24zm160-14.1v6.1H256V0h6.1c6.4 0 12.5 2.5 17 7l97.9 98c4.5 4.5 7 10.6 7 16.9z"></path></svg><!-- <i class="fas fa-file " style="font-size: 16px !important;"></i> --><br><label style="font-size: 9px !important; margin-bottom: 0rem !important; margin-top: 0.2rem !important;">Formularios</label></button>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label class="encabezado">Hipótesis Final</label>
                        <input type="text" name="frm_hipotesis_final" id="frm_hipotesis_final" class="form-control form-control-sm mifuente " placeholder="Ingrese Cie10 urgencia" value="<?=$rsRce[0]['regHipotesisFinal'];?>" >
                        <input type="hidden" name="frm_codigoCIE10" id="frm_codigoCIE10" class="form-control form-control-sm mifuente " value="<?=$rsRce[0]['regDiagnosticoCie10'];?>">
                    </div>
                    <?php if( count($rsRce_diagnostico) > 0 ){ ?>
                    <div class=" col-lg-12 col-md-12 mt-2" >
                        <div class="table-responsive-lg">
                        <table id="tabla_contenido_insumos" width="100%" class="table table-hover" style="font-size: 14px;">
                                <tbody id="contenido_diagnostico" >
                                    <?php  for ($i=0; $i < count($rsRce_diagnostico) ; $i++) { 
                                        $codigo = $rsRce_diagnostico[$i]['id_cie10'];
                                        $texto = $rsRce_diagnostico[$i]['diagnistico_descripcion_text'];
                                        $posicion = strpos($texto, $codigo);
                                        $textoDiag = "";
                                        if ($posicion !== false) {
                                        }else{
                                            $rsRce_diagnostico[$i]['diagnistico_descripcion_text'] = $rsRce_diagnostico[$i]['id_cie10']." ".$rsRce_diagnostico[$i]['diagnistico_descripcion_text'];
                                        }

                                        if($rsRce_diagnostico[$i]['diagnistico_descripcion_text_comentario'] != ""){
                                            $textoDiag = $rsRce_diagnostico[$i]['diagnistico_descripcion_text']."<br>-&nbsp;&nbsp;".$rsRce_diagnostico[$i]['diagnistico_descripcion_text_comentario']; 
                                        }else{
                                            $textoDiag .= $rsRce_diagnostico[$i]['diagnistico_descripcion_text']; 
                                        }
                                        ?>
                                    <tr id="id<?php echo $rsRce_diagnostico[$i]['id_compartido'];?>">
                                        <td class="my-1 py-1 mx-1 px-1 mifuente11 td_id_cie10_TABLA " hidden ><?php echo $rsRce_diagnostico[$i]['id_cie10'];?></td>

                                        <td class="my-1 py-1 mx-1 px-1 mifuente11  " width="90%"><?php echo $textoDiag;?></td>
                                        <td class="my-1 py-1 mx-1 px-1 mifuente11 text-center" style="vertical-align:middle;" >
                                             <input type="radio" name="select_prestacion" data-id="<?php echo $rsRce_diagnostico[$i]['id_cie10']; ?>" data-nombre="<?php echo $rsRce_diagnostico[$i]['diagnistico_descripcion_text']; ?>"  data-abierto="<?php echo $rsRce_diagnostico[$i]['diagnistico_descripcion_text_comentario']; ?>" onchange="seleccionarPrestacionRadio(this)">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="row mt-0">
                    <div class="col-md-12">
                        <label class="encabezado">CIE10 Abierto</label>
                        <?php
                        $rsRce[0]['regCIE10Abierto'] = str_replace("<br>", "\n", $rsRce[0]['regCIE10Abierto']);
                        ?>
                        <input type="text" name="frm_cie10Abierto" id="frm_cie10Abierto" class="form-control form-control-sm mifuente " placeholder="Ingrese Cie10 Abierto" value="<?=$rsRce[0]['regCIE10Abierto'];?>">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label class="encabezado">Indicaciones</label>
                        <?php
                        $rsRce[0]['regIndicacionEgresoUrgencia'] = str_replace("<br>", "\n", $rsRce[0]['regIndicacionEgresoUrgencia']);

                        ?>
                        <textarea oninput="filterInput_libre(event)" onpaste="handlePaste_libre(event)"  class="form-control form-control-sm mifuente  ingresosRCE" rows="6" id="frm_indicaciones_alta" name="frm_indicaciones_alta" placeholder="Indicaciones al alta de Urgencia"><?=$rsRce[0]['regIndicacionEgresoUrgencia'];?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="encabezado">Pronostico</label>
                        <select class="form-control form-control-sm mifuente " id="frm_pronostico" name="frm_pronostico">
                            <option value="" disabled selected>Seleccione</option>
                            <?php
                            for ( $i = 0; $i < count($rsListado); $i++ ) {
                            ?>
                                <option value="<?=$rsListado[$i]['PRONcodigo']?>" <?php if($rsRce[0]["PRONcodigo"]==$rsListado[$i]['PRONcodigo']){echo "selected";}?>><?=$rsListado[$i]['PRONdescripcion']?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-4">
                        <label class="encabezado">Indicación Egreso</label>
                        <select class="form-control form-control-sm mifuente " name="frm_Indicacion_Egreso" id="frm_Indicacion_Egreso" >
                            <option value="" disabled selected>Seleccione</option>
                            <?php
                            for ( $i = 0; $i < count($ListarIndicacionEgreso); $i++ ) {
                            ?>
                                <option value="<?=$ListarIndicacionEgreso[$i]['ind_egr_id'];?>" <?php if($obtenerIndicacionEgreso[0]['dau_indicacion_egreso']==$ListarIndicacionEgreso[$i]['ind_egr_id']){echo "selected";}?> ><?=$ListarIndicacionEgreso[$i]['ind_egr_descripcion'];?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div id="selectSegunIndicacionEgreso" class="col-md-8">
                        <!-- Destino -->
                        <div class="row">
                            <div class="col" id="frm_control_form" name="frm_control_form">
                                <!-- <div id="frm_control_form" name="frm_control_form" > -->
                                <label class="encabezado">Destinos</label><br>
                                <select class="form-control form-control-sm mifuente " id="frm_alta_derivacion" name="frm_alta_derivacion">
                                    <option value="0" disabled selected>Seleccione</option>
                                    <?php
                                    $derivacionAPS = 3;
                                    $fonasaA = 0;
                                    $fonasaB = 1;
                                    $fonasaC = 2;
                                    $fonasaD = 3;
                                    $prais   = 12;
                                    $tiposFonasas = array($fonasaA, $fonasaB, $fonasaC, $fonasaD);
                                    for ( $i = 0; $i < count($rsDerivacion); $i++ ) {

                                        if (
                                                intval($rsDerivacion[$i]["alt_der_id"]) === $derivacionAPS
                                                &&  (       in_array($datosPaciente[0]["dau_paciente_prevision"], $tiposFonasas) === false
                                                        &&  intval($datosPaciente[0]["dau_paciente_forma_pago"]) !== $prais
                                                    )
                                            ){

                                                continue;

                                            }
                                    ?>
                                    <option value="<?=$rsDerivacion[$i]['alt_der_id']?>"<?php if($listaDestino[0]['alt_der_id']==$rsDerivacion[$i]['alt_der_id']){echo"selected";}?>><?=$rsDerivacion[$i]['alt_der_descripcion']?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            <!-- </div> -->
                            </div>
                            <!-- Especialidad -->
                            <div class="col" id="frm_especialidad_oculto" name="frm_especialidad_oculto">
                                <!-- <div id="frm_especialidad_oculto" name="frm_especialidad_oculto" > -->
                                    <label class="encabezado">Especialidad</label><br>
                                    <select id="frm_especialidad" name="frm_especialidad" data-live-search="true" multiple class="selectpicker form-control form-control-sm mifuente">
                                        <?php
                                        for ( $i = 0; $i < count($resEspecialidad); $i++ ) {
                                        ?>
                                        <option value="<?=$resEspecialidad[$i]['ESPcodigo']?>"<?php if ( strpos($listaDestino[0]['dau_ind_especialidad'], $resEspecialidad[$i]['ESPcodigo']) !== false ){echo "selected";}?>><?=$resEspecialidad[$i]['ESPdescripcion']?></option>
                                        <?php } ?>
                                    </select>
                                <!-- </div> -->
                                <?php
                                if ( !empty($listaDestino[0]['dau_ind_especialidad']) && !is_null($listaDestino[0]['dau_ind_especialidad']) ) {
                                    $resultadoConsulta = $objRce->obtenerPrioridadYMotivoSolicitudSICSegunDau($objCon, $parametros['dau_id']);
                                    echo '<input type="hidden" id="idPrioridad" name="idPrioridad" value="'.$resultadoConsulta['SICprioridad'].'">';
                                    echo '<input type="hidden" id="idMotivoConsulta" name="idMotivoConsulta" value="'.$resultadoConsulta['SICmotivoConsulta'].'">';
                                    echo '<input type="hidden" id="otrosMotivos" name="otrosMotivos" value="'.$resultadoConsulta['SICotroMotivo'].'">';
                                }?>
                            </div>
                            <div class="col" id="frm_aps_oculto" name="frm_aps_oculto">
                                <!-- <div id="frm_aps_oculto" name="frm_aps_oculto" > -->
                                    <label class="encabezado">APS</label>
                                    <select id="frm_aps" name="frm_aps" class="form-control form-control-sm mifuente " data-live-search="true">
                                        <option value="0" disabled selected>Seleccione</option>
                                        <?php
                                        for ( $i = 0; $i < count($rsAPS); $i++ ) {
                                        ?>
                                        <option value="<?=$rsAPS[$i]['ESTAcodigo']?>" <?php if($listaDestino[0]['dau_ind_aps']==$rsAPS[$i]['ESTAcodigo']){echo "selected";}?>><?=$rsAPS[$i]['ESTAdescripcion']?></option>
                                        <?php  } ?>
                                    </select>
                                <!-- </div> -->
                            </div>
                            <div class="col" id="frm_otros_oculto" nam="frm_otros_oculto">
                                <!-- <div id="frm_otros_oculto" nam="frm_otros_oculto" > -->
                                    <label class="encabezado">Otros Motivo</label>
                                    <textarea oninput="filterInput_libre(event)" onpaste="handlePaste_libre(event)"  class="form-control form-control-sm mifuente " rows="4" cols="5" id="frm_otros" name="frm_otros" placeholder="Indique Otros..."><?=$obtenerIndicacionEgreso[0]['dau_ind_otros'];?></textarea>
                                <!-- </div> -->
                            </div>
                        </div>
                        <!-- Servicio Destino -->
                        <div id="frm_servicio_destino_oculto" name="frm_servicio_destino_oculto"  class="col-md-8">

                            <div class="row">
                                <div class="col">
                                    <label class="encabezado">Servicio destino</label>
                                    <select class="form-control form-control-sm mifuente " name="frm_servicio_destino" id="frm_servicio_destino">
                                        <option value="0" disabled selected>Seleccione</option>
                                        <?php
                                        for ( $i = 0; $i < count($ListarServiciosDau); $i++ ) {
                                        ?>
                                            <option value="<?=$ListarServiciosDau[$i]['id'];?>"<?php if($obtenerServiciosDau[0]['dau_ind_servicio']==$ListarServiciosDau[$i]['id']){echo "selected";}?> ><?=$ListarServiciosDau[$i]['servicio'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="encabezado">Otros Destinos</label>
                                    <select class="form-control form-control-sm mifuente " name="frm_otro_servicio_destino" id="frm_otro_servicio_destino">
                                        <?php
                                        $otrosServicios       = $_SESSION['datosPacienteDau']['dau_hospitalizacion_otros_servicios'];
                                        $selectedNinguno      = '';
                                        $selectedNeurocirugia = '';
                                        $selectedPabellon     = '';
                                        if ( empty($otrosServicios) || is_null($otrosServicios) || $otrosServicios == 'Ninguno' ) {
                                            $selectedNinguno = 'selected';
                                        } else if ( $otrosServicios == 'Neurocirugía' ) {
                                            $selectedNeurocirugia = 'selected';
                                        } else if ( $otrosServicios == 'Pabellón' ) {
                                            $selectedPabellon = 'selected';
                                        }
                                        ?>
                                        <option value="0" disabled selected>Seleccione (No Obligatorio)</option>
                                        <option value="Ninguno" <?php echo $selectedNinguno; ?> >Ninguno</option>
                                        <option value="Neurocirugía" <?php echo $selectedNeurocirugia; ?> >Neurocirugía</option>
                                        <option value="Pabellón" <?php echo $selectedPabellon; ?> >Pabellón</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div id="frm_defuncion_Fecha" name="frm_defuncion_Fecha" >
                                <div class="col-md-4">
                                    <label class="encabezado">Fecha y Hora</label>
                                    <div class="form-group">
                                        <input type='text' class="form-control form-control-sm mifuente " name="frm_fecha_defuncion" id="frm_fecha_defuncion" onDrop="return false" placeholder="DD/MM/YY" value="<?php $fechaInicio = $obtenerIndicacionEgreso[0]['dau_defuncion_fecha'];if($fechaInicio != ""){echo date("d-m-Y H:i",strtotime($fechaInicio));}?>" readonly/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div id="frm_destino" name="frm_destino" >
                                        <label class="encabezado">Destino</label>
                                        <div id="frm_destino" name="frm_destino">
                                            <input type="radio" name="frm_destino_defuncion" id="frm_destino_defuncion" value="1" <?php if($destinoControl == 7){echo "checked";}?>> ANATO.PAT.<br>
                                            <input type="radio" name="frm_destino_defuncion" id="frm_destino_defuncion" value="2" <?php if($destinoControl == 8){echo "checked";}?>> SERV.MED.<br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2" id="selectIndicacionEgresoEspecialista">
                    <!-- <div id="selectIndicacionEgresoEspecialista"> -->
                        <div class="col-md-4">
                            <label class="encabezado">Prioridad</label>
                            <?php
                            $resultadoConsulta = $objRce->obtenerTiposPrioridad($objCon);
                            $totalResultado    = count($resultadoConsulta);
                            ?>
                            <select class="form-control form-control-sm mifuente " name="slc_prioridad" id="slc_prioridad">
                                <option value="0" disabled selected>Seleccione Prioridad</option>
                                <?php
                                for ( $i = 0; $i < $totalResultado; $i++ ) {
                                ?>
                                    <option value="<?php echo $resultadoConsulta[$i]['idPrioridad'];?>" > <?php echo $resultadoConsulta[$i]['descripcionPrioridad']; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                        <!-- Motivo consulta -->
                        <div class="col-md-4">
                            <label class="encabezado">Motivo Consulta</label>
                            <?php
                            $resultadoConsulta = $objRce->obtenerTiposMotivoConsulta($objCon);
                            $totalResultado    = count($resultadoConsulta);
                            ?>
                            <select class="form-control form-control-sm mifuente " name="slc_motivoConsulta" id="slc_motivoConsulta">
                                <option value="0" disabled selected>Seleccione Motivo Consulta</option>
                                <?php
                                for ( $i = 0; $i < $totalResultado; $i++ ) {
                                ?>
                                    <option value="<?php echo $resultadoConsulta[$i]['idMotivoConsulta'];?>" > <?php echo $resultadoConsulta[$i]['descripcionMotivoConsulta']; ?> </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4" id="otrosMotivoConsulta">
                            <label class="encabezado">Otros</label>
                            <input type='text' class="form-control form-control-sm mifuente " name="frm_otrosMotivoConsulta" id="frm_otrosMotivoConsulta"/>
                        </div>
                    <!-- </div> -->
                </div>
                <div class="row mt-2">
                  <div class="col-lg-12">
                      <div class="form-group row">
                        <label for="frm_entregaInformacion" class="col-lg-5 col-form-label encabezado">
                          ¿Se entrega información médica?
                        </label>
                        <div class="col-lg-7">
                          <select class="form-control form-control-sm mifuente col-lg-12" id="frm_entregaInformacion" name="frm_entregaInformacion">
                            <option selected disabled> Seleccione </option>
                            <option value="S" <?php if ( $rsobtenerAcompaniante[0]['entregaInformacion'] == 'S') { echo "selected"; } ?> > Si </option>
                            <option value="N" <?php if ( $rsobtenerAcompaniante[0]['entregaInformacion'] == 'N') { echo "selected"; } ?> > No </option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="frm_motivo" class="col-lg-5 col-form-label encabezado"> Motivo </label>
                        <div class="col-lg-7"> 
                          <textarea oninput="filterInput_libre(event)" onpaste="handlePaste_libre(event)"  class="form-control form-control-sm mifuente" id="frm_motivo" name="frm_motivo" rows="4"><?=$rsobtenerAcompaniante[0]['motivo'];?></textarea>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="frm_motivo" class="col-lg-5 col-form-label encabezado">
                          Nombre familiar o acompañante que se le entregó la información
                        </label>
                        <div class="col-lg-7">
                          <input type="input" class="form-control form-control-sm mifuente" id="frm_nombreFamiliarOAcompaniante" name="frm_nombreFamiliarOAcompaniante" value="<?=$rsobtenerAcompaniante[0]['nombreAcompaniante'];?>">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="frm_motivo" class="col-lg-5 col-form-label encabezado">
                          Hora en que se entregó la información médica
                        </label>
                        <div class="col-lg-7">
                          <input type="input" class="form-control form-control-sm mifuente" id="frm_horaEntregaInformacion" name="frm_horaEntregaInformacion" value="<?php echo  $rsobtenerAcompaniante[0]['horaEntregaInformacionMedica']; ?>" readonly>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="frm_motivo" class="col-lg-5 col-form-label encabezado">
                          Nombre Médico
                        </label>
                        <div class="col-lg-7">
                          <input type="input" class="form-control form-control-sm mifuente" id="frm_nombreMedicoTratante" readonly value="<?=$rsobtenerAcompaniante[0]['nombreMedico']?>">
                        </div>
                      </div>
                  </div>
                </div>
                <?php
                $registroViolencia = $objRce->obtenerRegistroViolencia($objCon, $parametros['dau_id']);
                if ( ! empty($registroViolencia) && ! is_null($registroViolencia) ) {
                    echo '<input type="hidden"      id="hiddenExisteViolencia"       name="hiddenExisteViolencia"        value="S">';
                    echo '<input type="hidden"      id="hiddenTipoViolencia"         name="hiddenTipoViolencia"          value="'.$registroViolencia['idTipoViolencia'].'">';
                    echo '<input type="hidden"      id="hiddenTipoAgresor"           name="hiddenTipoAgresor"            value="'.$registroViolencia['idTipoAgresor'].'">';
                    echo '<input type="hidden"      id="hiddenTipoLesionVictima"     name="hiddenTipoLesionVictima"      value="'.$registroViolencia['idTipoLesionVictima'].'">';
                    echo '<input type="hidden"      id="hiddenSospechaPenetracion"   name="hiddenSospechaPenetracion"    value="'.$registroViolencia['idTipoSospechaPenetracion'].'">';
                    echo '<input type="hidden"      id="hiddenProfilaxis"            name="hiddenProfilaxis"             value="'.$registroViolencia['idTipoProfilaxis'].'">';
                    echo '<input type="hidden"      id="hiddenVictimaEmbarazada"     name="hiddenVictimaEmbarazada"      value="'.$registroViolencia['victimaEmbarazada'].'">';
                    echo '<input type="hidden"      id="hiddenPeritoSexual"          name="hiddenPeritoSexual"           value="'.$registroViolencia['peritoSexual'].'">';
                }?>
                <div class="row mt-2">
                    <div class="col" >
                        <label class="encabezado" >¿Violencia?</label>
                        <select class="form-control form-control-sm mifuente " name="slc_existeViolencia" id="slc_existeViolencia">
                            <option value="N">No</option>
                            <option value="S">Si</option>
                        </select>
                    </div>
                    <!-- <div id="divViolencias"  class="row"> -->
                        <?php
                        $tiposViolencias        = $objRce->obtenerTiposViolencias($objCon);
                        $totalTiposViolencias   = count($tiposViolencias);
                        ?>
                        <div class="col" id="tipoViolencias">
                            <label class="encabezado" >Tipo Violencia</label>
                            <select class="form-control form-control-sm mifuente " name="frm_tipoViolencia" id="frm_tipoViolencia" >
                                <option value="0" disabled selected>Seleccione</option>
                                <?php
                                for ( $i = 0; $i < $totalTiposViolencias; $i++ ) {
                                echo '<option value="'.$tiposViolencias[$i]['idTipoViolencia'].'">'.$tiposViolencias[$i]['descripcionTipoViolencia'].'</option>';
                                }?>
                            </select>
                        </div>
                        <div class="col violenciaNoAutoinfringidas" id="tipoAgresor" >
                            <label class="encabezado" >Tipo Agresor</label>
                            <select class="form-control form-control-sm mifuente " name="frm_tipoAgresor" id="frm_tipoAgresor">
                                <option value="0" disabled selected>Seleccione</option>
                            </select>
                        </div>
                        <?php
                        $tipoLesiones = $objRce->obtenerTipoLesionesVictima($objCon);
                        $totalTipoLesiones = count($tipoLesiones);
                        ?>
                        <div class="col violenciaNoAutoinfringidas violenciasVIFONoVIF" id="tipoLesiones" >
                            <label class="encabezado" >Lesiones de la Víctima</label>
                            <select class="form-control form-control-sm mifuente " name="frm_tipoLesionVictima" id="frm_tipoLesionVictima">
                                <option value="0" disabled selected>Seleccione</option>
                                <?php
                                for ( $i = 0; $i < $totalTipoLesiones; $i++ ) {
                                    echo '<option value="'.$tipoLesiones[$i]['idTipoLesionVictima'].'">'.$tipoLesiones[$i]['descripcionLesionVictima'].'</option>';
                                }?>
                            </select>
                        </div>
                        <?php
                        $sospechaPenetracion = $objRce->obtenerSospechasPenetracion($objCon);
                        $totalSospechaPenetracion = count($sospechaPenetracion);
                        ?>
                        <div class="col violenciaNoAutoinfringidas violenciaSexual" id="sospechaPenetracion" >
                            <label class="encabezado" >Tiempo Agresión</label>
                            <select class="form-control form-control-sm mifuente " name="frm_tipoSospechaPenetracion" id="frm_tipoSospechaPenetracion">
                                <option value="0" disabled selected>Seleccione</option>
                                <?php
                                for ( $i = 0; $i < $totalSospechaPenetracion; $i++ ) {
                                    echo '<option value="'.$sospechaPenetracion[$i]['idTipoSospechaPenetracion'].'">'.$sospechaPenetracion[$i]['descripcionSospechaPenetracion'].'</option>';
                                }?>
                            </select>
                        </div>
                        <?php
                        $tipoProfilaxis = $objRce->obtenerTipoProfilaxis($objCon);
                        $totalTipoProfilaxis = count($tipoProfilaxis);
                        ?>
                        <div class="col violenciaNoAutoinfringidas violenciaSexual" id="profilaxis" >
                            <label class="encabezado" >Profilaxis</label>
                            <select class="form-control form-control-sm mifuente " name="frm_profilaxis" id="frm_profilaxis">
                                <option value="0" disabled selected>Seleccione</option>
                                <?php
                                for ( $i = 0; $i < $totalTipoProfilaxis; $i++ ) {
                                    echo '<option value="'.$tipoProfilaxis[$i]['idTipoProfilaxis'].'" '.$selected.'>'.$tipoProfilaxis[$i]['descripcionProfilaxis'].'</option>';
                                }?>
                            </select>
                        </div>
                        <!-- Víctima Embarazada -->
                        <div class="col violenciaNoAutoinfringidas victimaEmbarazada" id="victimaEmbarazada" >

                            <label class="encabezado" >¿Embarazada?</label>

                            <select class="form-control form-control-sm mifuente " name="frm_victimaEmbarazada" id="frm_victimaEmbarazada">

                                <option value="0" disabled selected>Seleccione</option>

                                <option value="N">No</option>

                                <option value="S">Si</option>

                            </select>

                        </div>
                        <!-- Perito Sexual -->
                        <div class="col violenciaNoAutoinfringidas violenciaSexual" id="peritoSexual" >

                            <label class="encabezado" >Perito Sexual</label>

                            <select class="form-control form-control-sm mifuente " name="frm_peritoSexual" id="frm_peritoSexual">

                                <option value="0" disabled selected>Seleccione</option>

                                <option value="Turno">Turno</option>

                                <option value="Llamado">Llamado</option>

                                <option value="Otros Médicos">Otros Médicos</option>

                            </select>

                        </div>

                    </div>
                    <div class="row mt-2" id="divSeguimientoPaciente" style="display:none;">
                        <div class="col-md-12">
                            <label class="encabezado" >
                                ¿Realizar Seguimiento A Paciente?
                            </label>
                            <select class="form-control form-control-sm mifuente" name="frm_seguimientoPaciente" id="frm_seguimientoPaciente">
                                <option value="0" disabled selected>Seleccione</option>
                                <option
                                    value="S"
                                    <?php echo (
                                        $objUtil->existe($rsRce[0]["seguimientoPaciente"])
                                        && $rsRce[0]["seguimientoPaciente"] === "S"
                                    )
                                        ? "selected"
                                        : ""
                                    ?>
                                >
                                    Si
                                </option>
                                <option
                                    value="N"
                                    <?php echo (
                                        $objUtil->existe($rsRce[0]["seguimientoPaciente"])
                                        && $rsRce[0]["seguimientoPaciente"] === "N"
                                    )
                                        ? "selected"
                                        : ""
                                    ?>
                                >
                                    No
                                </option>
                            </select>
                        </div>
                    </div>
                <!-- </div> -->



                <?php
                if ( $_SESSION['datosPacienteDau']['dau_cierre_auge']  == 'S' ) {
                    $checkedGes = 'checked';
                }

                if ( $_SESSION['datosPacienteDau']['dau_cierre_pertinencia']  == 'S' ) {
                    $checkedPertinencia = 'checked';
                }

                if ( $_SESSION['datosPacienteDau']['dau_cierre_entrega_postinor']  == 'S' ) {
                    $checkedPostinor = 'checked';
                }

                if ( $_SESSION['datosPacienteDau']['dau_cierre_hepatitisB']  == 'S' ) {
                    $checkedHepatitisB = 'checked';
                }

                if ( $datosPaciente[0]['sexo'] !== 'F' ) {
                    $hiddenPostinor = 'hidden';
                }
                ?>
                <div class="row mt-3 m-1">

                    <!-- GES -->
                    <div class="col form-group form-check mifuente text-center">
                        <input type="checkbox" id="frm_auge" name="frm_auge" class="mt-1 form-check-input" value="<?php echo $_SESSION['datosPacienteDau']['dau_cierre_auge']; ?>"  <?php echo $checkedGes; ?> >
                        <label class="encabezado">&nbsp;GES</label>
                    </div>
                    <div class="col form-group form-check mifuente text-center">
                        <input type="checkbox" id="frm_pertinencia" name="frm_pertinencia" class="mt-1 form-check-input" value="<?php echo $_SESSION['datosPacienteDau']['dau_cierre_pertinencia']; ?>"  <?php echo $checkedPertinencia; ?> >
                        <label class="encabezado">&nbsp;Pertinencia</label>
                    </div>
                    <div class="col form-group form-check mifuente text-center" <?php echo $hiddenPostinor; ?> >
                        <input type="checkbox" id="frm_postinor" name="frm_postinor" class="mt-1 form-check-input" value="<?php echo $_SESSION['datosPacienteDau']['dau_cierre_entrega_postinor']; ?>"  <?php echo $checkedPostinor; ?> >
                        <label class="encabezado">&nbsp;Entrega Postinor</label>
                    </div>
                    <div class="col form-group form-check mifuente text-center">
                        <input type="checkbox" id="frm_hepatitisB" name="frm_hepatitisB" class="mt-1 form-check-input" value="<?php echo $_SESSION['datosPacienteDau']['dau_cierre_hepatitisB']; ?>"   <?php echo $checkedHepatitisB; ?> >
                        <label class="encabezado">&nbsp;Hepatitis B</label>
                    </div>

                </div>

            

                <!-- Variables a usar en JS y envío de parámetros-->
                <input type="hidden"        id="frm_destino_alta"      name="frm_destino_alta"          value="<?=$destinoControl;?>" >
                <input type="hidden"        id="dau_id"                name="dau_id"                    value="<?=$parametros['dau_id'];?>" >
                <input type="hidden"        id="paciente_id"           name="paciente_id"               value="<?=$parametros['paciente_id'];?>" >
                <input type="hidden"        id="fecha_actual"          name="fecha_actual"              value="<?=$fecha;?>" >
                <input type="hidden"        id="fecha_admision"        name="fecha_admision"            value="<?=$fecha_admision;?>" >
                <input type="hidden"        id="numero_cama"           name="numero_cama"               value="<?=$buscarCamaYsala[0]['cam_descripcion']?>" >
                <input type="hidden"        id="tipo_sala"             name="tipo_sala"                 value="<?=$buscarCamaYsala[0]['sal_descripcion']?>" >
                <input type="hidden"        id="dau_atencion"          name="dau_atencion"              value="<?=$_SESSION['datosPacienteDau']['dau_atencion']?>" >
                <input type="hidden"        id="rce_id"                name="rce_id"                    value="<?=$rsRce[0]['regId']?>" >
                <input type="hidden"        id="inpH_atencion_fecha"   name="inpH_atencion_fecha"       value="<?=$dau_inicio_atencion_fecha?>" >
                <input type="hidden"        id="inpH_atencion_hora"    name="inpH_atencion_hora" 	    value="<?=$dau_inicio_atencion_hora?>" >
                <input type="hidden"        id="inpH_horaActual"       name="inpH_horaActual" 	 	    value="<?=$horaFecha?>" >
                <input type="hidden"        id="inpH_FechaActual"      name="inpH_FechaActual" 	 	    value="<?=$fechaHora?>" >
                <input type="hidden"        id="edadPaciente"          name="edadPaciente"              value="<?=$_SESSION['datosPacienteDau']['dau_paciente_edad']?>" >

                <!-- Campos ocultos para enviar texto de los selects -->
                <input type="hidden" name="descripcionIndicacionEgreso" id="descripcionIndicacionEgreso"    value="">
                <input type="hidden" name="descripcionServicioDestinos" id="descripcionServicioDestinos"    value="">
                <input type="hidden" name="descripcionAltaDestinos"     id="descripcionAltaDestinos"        value="">
                <input type="hidden" name="descripcionAltaAps"          id="descripcionAltaAps"             value="">
                <input type="hidden" name="descripcionAltaOtros"        id="descripcionAltaOtros"           value="">
                <input type="hidden" name="fechaDefuncion"              id="fechaDefuncion"                 value="">
                <input type="hidden" name="destinoDefuncion"            id="destinoDefuncion"               value="">
                <input type="hidden" name="cie10_id"                    id="cie10_id"                       value="<?php echo $rsRce[0]['regDiagnosticoCie10']; ?>">

            </form>

        </div>

    </div>

</div>
<div>