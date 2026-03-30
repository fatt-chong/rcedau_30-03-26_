<?php
session_start();
error_reporting(0);
ini_set('max_execution_time', 600);
ini_set('memory_limit', '128M');

require_once("../../../../config/config.php");
require_once('../../../../class/Connection.class.php');
require_once('../../../../class/Imagenologia.class.php');
require_once('../../../../class/Util.class.php');
require_once('../../../../class/Dau.class.php');
require_once('../../../../class/PacienteDAU.class.php');
require_once('../../../../class/RegistroClinico.class.php');



$objCon = new Connection;
$objRayos = new Imagenologia;
$objUtil = new Util;
$objDau = new Dau;
$objPac = new PacienteDAU;
$objRegistroClinico = new RegistroClinico;
$objCon->db_connect();

$parametros = $objUtil->getFormulario($_POST);
$datosRce = $objRegistroClinico->consultaRCE($objCon, $parametros);
$parametros['frm_id_paciente'] = $_SESSION['datosPacienteDau']['id_paciente'];
$sexoPaciente = $objPac->pacienteSexo($objCon, $parametros);
$rsTipoExmamen = $objRayos->getTipoExamen($objCon);

$datos_contendidoCargadoCarroImagenologia = $_SESSION['indicaciones']['imagenologia'];
$datos_contendidoCargadoCarroImagenologia = json_decode(stripslashes($datos_contendidoCargadoCarroImagenologia));

$datos_antecedentesClinicos = $_SESSION['indicaciones']['antecedentesClinicos'];
$datos_antecedentesClinicos = json_decode(stripslashes($datos_antecedentesClinicos));

$version = $objUtil->versionJS();
?>



<!--
################################################################################################################################################
                                                                    ARCHIVO JS
-->

<script type="text/javascript" src="<?= PATH ?>/controllers/client/rce/indicaciones/imagenologia.js?v=<?= $version; ?>33"></script>



<br>



<style>
/*    [data-id="frm_examen"] {
        font-size: 13px !important;
    }

    [data-id="frm_contrastes"] {
        font-size: 13px !important;
    }*/
</style>
<!-- <script type="text/javascript">
    $(document).ready(function() {
        // $('.select_buscador2').select2();
    });
</script> -->
<script>
    $(document).ready(function() {
        $('.comboImagenologia').select2({
            theme: 'bootstrap4',
            dropdownParent: $('#agregarExamen')
        });
    });
</script>


<!--
################################################################################################################################################
                                                            DESPLIEGUE CAMPOS IMAGENOLOGÍA
-->
<div id="contenidoImagenologia" class="mr-2 ml-2">
    
    <form id="frm_des_ima" name="frm_des_ima" role="form" method="POST" enctype="multipart/form-data">
        <div class="panel panel-default" style="margin-bottom: 0px;">
            <div class="bd-callout bd-callout-warning border-bottom">
                <h6 id="ensure-correct-role-and-provide-a-label" style=" border-radius: inherit; font-size:15px;"> <svg class="svg-inline--fa fa-circle-notch fa-w-16 text-danger mr-2"  focusable="false" data-prefix="fas" data-icon="circle-notch" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M288 39.056v16.659c0 10.804 7.281 20.159 17.686 23.066C383.204 100.434 440 171.518 440 256c0 101.689-82.295 184-184 184-101.689 0-184-82.295-184-184 0-84.47 56.786-155.564 134.312-177.219C216.719 75.874 224 66.517 224 55.712V39.064c0-15.709-14.834-27.153-30.046-23.234C86.603 43.482 7.394 141.206 8.003 257.332c.72 137.052 111.477 246.956 248.531 246.667C393.255 503.711 504 392.788 504 256c0-115.633-79.14-212.779-186.211-240.236C302.678 11.889 288 23.456 288 39.056z"></path></svg><!-- <i class="fas fa-circle-notch text-danger mr-2"></i> -->Exámenes</h6>
            </div>
            <input type="hidden" id="cod_examen" name="cod_examen" value="">
            <input type="hidden" id="dau_paciente_complejo" name="dau_paciente_complejo" value="<?= $_SESSION['datosPacienteDau']['dau_paciente_complejo'] ?>">
            <div class="panel-body mt-3" >
                <div class="row">
                    <input type="hidden" id="idPrestacion" name="idPrestacion" />
                    <div class="col-lg-6">
                        <label class="encabezado">Exámenes</label>
                        <div class="input-group" style="border: 1px solid #ced4da;"> 
                            <select id="frm_examenes" name="frm_examenes"  class="form-select select_buscador2 comboImagenologia  form-select-sm mifuente  col-lg-12"  style="padding-top: 4px;" aria-label="Default select example ">
                            </select>
                        </div>
                    </div>

                    <!-- Tipo Examen -->
                    <div class="col-lg-2" >
                        <label class="encabezado">Tipo Examen</label>
                        <select class="form-control form-control-sm mifuente col-lg-12" id="frm_tiposExamenes" name="frm_tiposExamenes">
                            <option value="0" selected disabled>Seleccione</option>
                        </select>
                    </div>

                    <!-- Lateralidad -->
                    <div class="col-lg-2" >
                        <label class="encabezado">Lateralidad</label>
                        <select class="form-control form-control-sm mifuente col-lg-12" id="frm_lateralidades" name="frm_lateralidades">
                            <option value="0" selected disabled>Seleccione</option>
                        </select>
                    </div>

                    <!-- Contraste -->
                    <div class="col-lg-2" >
                        <label class="encabezado">Contraste</label>
                        <select id="frm_llevaContraste" name="frm_llevaContraste" class="form-control form-control-sm mifuente col-lg-12">
                            <option value="N" selected>No</option>
                            <option value="S">Si</option>
                        </select>
                    </div>

                    <!-- Tipo contraste -->
                    <div class="col" id="tipoContraste" >
                        <label class="encabezado">Chequeo de Seguridad</label>
                        <select id="frm_contrastes" name="frm_contrastes" multiple class="selectpicker form-control form-control-sm mifuente" title="Seleccione ">
                        </select>
                    </div>

                </div>

                <div class="row">
                    <!-- Observación -->
                    <div class="col-md-11">
                        <label class="encabezado">Observación</label>
                        <input type="text" class="form-control form-control-sm mifuente" id="frm_observacionExamen" name="frm_observacionExamen">
                    </div>

                    <!-- Botón agregar -->
                    <div class="col-md-1">
                        <label class="encabezado">&nbsp;</label>
                        <button type="button" id="btn_agregar_linea"  type="button" class="btn btn btn-sm btn-outline-primarydiag  mifuente col-lg-12 " alt="Agregar Examen" ><i class="fas fa-plus"></i></button>
                    </div>

                    <!-- <div class="col-md-2" style="margin-top: 12px;">
                        <button type="button" id="btn_agregar_linea" class="btn btn-default" alt="Agregar Examen" title="Agregar Examen"><img src="<?= PATH ?>/assets/img/DAU-06.png"></button>
                    </div> -->
                </div>

                <div class="row mt-3 " id="despliegueExamenesSeleccionados">
                    <div class="col-md-12">
                        <table id="tablaContenido" class="table table-sm table-striped table-hover" >


                <!-- Despliegue exámenes seleccionados -->
                <!-- <div class="row" id="despliegueExamenesSeleccionados"> -->
                    <!-- <div class="col-md-12"> -->
                        <!-- <label class="encabezado">Solicitud de Exámenes</label><br> -->

                        <!-- <table id="tablaContenido" class="table table-sm table-striped table-hover" > -->
                            <thead>
                                <tr class="detalle">
                                    <td width="30%" class="mifuente  text-center" >Examen</td>
                                    <td width="10%" class="mifuente  text-center" >Tipo Examen</td>
                                    <td width="15%" class="mifuente  text-center" >Lateralidad</td>
                                    <td width="15%" class="mifuente  text-center" >Contraste</td>
                                    <td width="20%" class="mifuente  text-center" >Observación</td>
                                    <td width="10%" class="mifuente  text-center" >Eliminar</td>
                                </tr>
                            </thead>

                            <tbody id="contenidoRayo">
                                <?php
                                if ($objUtil->existe($datos_contendidoCargadoCarroImagenologia[0])) {

                                    for ($i = 0; $i < count($datos_contendidoCargadoCarroImagenologia); $i++) {
                                    ?>
                                        <tr id="<?php echo $datos_contendidoCargadoCarroImagenologia[$i][0]; ?>" class="detalle">
                                            <td class= " my-1 py-1 mx-1 px-1 mifuente ima_valorParteCuerpo" hidden>
                                                <?php echo $datos_contendidoCargadoCarroImagenologia[$i][7]; ?>
                                            </<td>

                                            <td width="30%" class= ' my-1 py-1 mx-1 px-1 mifuente ima_valorExamen'>
                                                <?php echo $datos_contendidoCargadoCarroImagenologia[$i][0]; ?>
                                            </td>

                                            <td width="10%" class= ' my-1 py-1 mx-1 px-1 mifuente ima_valorTipoExamen' style="text-align:center;">
                                                <?php echo $datos_contendidoCargadoCarroImagenologia[$i][1]; ?>
                                            </td>

                                            <td width="15%" class= ' my-1 py-1 mx-1 px-1 mifuente ima_valorLateralidad' style="text-align:center;">
                                                <?php echo $datos_contendidoCargadoCarroImagenologia[$i][2]; ?>
                                            </td>

                                            <td width="15%" class= ' my-1 py-1 mx-1 px-1 mifuente ima_valorContraste' style="text-align:center;">
                                                <?php echo ($objUtil->existe($datos_contendidoCargadoCarroImagenologia[$i][3])) ? "Si" : "No"; ?>
                                            </td>

                                            <td class= ' my-1 py-1 mx-1 px-1 mifuente ima_valorContrastes' hidden>
                                                <?php echo $datos_contendidoCargadoCarroImagenologia[$i][3]; ?>
                                            </td>

                                            <td width="20%" class= ' my-1 py-1 mx-1 px-1 mifuente ima_valorObservacion'>
                                                <?php echo $datos_contendidoCargadoCarroImagenologia[$i][4]; ?>
                                            </td>

                                            <td class= ' my-1 py-1 mx-1 px-1 mifuente ima_valorIdPrestacion' hidden>
                                                <?php echo $datos_contendidoCargadoCarroImagenologia[$i][5]; ?>
                                            </td>

                                            <td class= ' my-1 py-1 mx-1 px-1 mifuente ima_valorPrestaciones' hidden>
                                                <?php echo $datos_contendidoCargadoCarroImagenologia[$i][6]; ?>
                                            </td>

                                            <td width="10%"  class="my-1 py-1 mx-1 px-1 mifuente" style="text-align:center;">
                                                <button type="button"
                                                    class= "  btn btn-default btn-sm btn-outline-danger  mifuente col-lg-12 eliminarExamen"
                                                    id="eli<?php echo $datos_contendidoCargadoCarroImagenologia[$i][1]; ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div>
    <!-- </div> -->
<!-- </div> -->

  


        <!--
        **************************************************************************
                                    PARTE DIAGNÓSTICO
        **************************************************************************
        -->
        <div class="row mt-2">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="bd-callout bd-callout-warning border-bottom">
                        <h6 id="ensure-correct-role-and-provide-a-label" style=" border-radius: inherit; font-size:15px;"> <svg class="svg-inline--fa fa-circle-notch fa-w-16 text-danger mr-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle-notch" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M288 39.056v16.659c0 10.804 7.281 20.159 17.686 23.066C383.204 100.434 440 171.518 440 256c0 101.689-82.295 184-184 184-101.689 0-184-82.295-184-184 0-84.47 56.786-155.564 134.312-177.219C216.719 75.874 224 66.517 224 55.712V39.064c0-15.709-14.834-27.153-30.046-23.234C86.603 43.482 7.394 141.206 8.003 257.332c.72 137.052 111.477 246.956 248.531 246.667C393.255 503.711 504 392.788 504 256c0-115.633-79.14-212.779-186.211-240.236C302.678 11.889 288 23.456 288 39.056z"></path></svg><!-- <i class="fas fa-circle-notch text-danger mr-2"></i> -->Diagnóstico</h6>
                    </div>
                    <div class="panel-body mt-3" >
                        <div class="row">
                            <div class="col">
                                <label class="encabezado">Diagnóstico</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control form-control-sm mifuente" id="frm_diagnostico" name="frm_diagnostico" placeholder="Diagnóstico" <?php if (isset($_SESSION['indicaciones']['imageDatos']['frm_diagnostico'])) { ?> value="<?= $_SESSION['indicaciones']['imageDatos']['frm_diagnostico'] ?>" <?php } else { ?> value="<?= $datosRce[0]['regHipotesisInicial'] ?>" <?php } ?>>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label class="encabezado">Síntomas Principales</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control form-control-sm mifuente" id="frm_sintomasp" name="frm_sintomasp" placeholder="Sintomas Principales" <?php if (isset($_SESSION['indicaciones']['imageDatos']['frm_sintomasp'])) { ?> value="<?= $_SESSION['indicaciones']['imageDatos']['frm_sintomasp'] ?>" <?php } else { ?> value="<?= str_replace(["<br>", "\n"], ' ', $datosRce[0]['regMotivoConsulta']) ?>" <?php } ?>>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <label class="encabezado">Antecedentes Quirúrgicos</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control form-control-sm mifuente" id="frm_antecedentes" name="frm_antecedentes" placeholder="Antecedentes Quirúrgicos" <?php if (isset($_SESSION['indicaciones']['imageDatos']['frm_sintomasp'])) { ?> value="<?= $_SESSION['indicaciones']['imageDatos']['frm_antecedentes'] ?>" <?php } ?>>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row mt-3" id="frm_div_checkbox" name="frm_div_checkbox">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="bd-callout bd-callout-warning border-bottom">
                        <h6 id="ensure-correct-role-and-provide-a-label" style=" border-radius: inherit; font-size:15px;"> <svg class="svg-inline--fa fa-circle-notch fa-w-16 text-danger mr-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle-notch" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M288 39.056v16.659c0 10.804 7.281 20.159 17.686 23.066C383.204 100.434 440 171.518 440 256c0 101.689-82.295 184-184 184-101.689 0-184-82.295-184-184 0-84.47 56.786-155.564 134.312-177.219C216.719 75.874 224 66.517 224 55.712V39.064c0-15.709-14.834-27.153-30.046-23.234C86.603 43.482 7.394 141.206 8.003 257.332c.72 137.052 111.477 246.956 248.531 246.667C393.255 503.711 504 392.788 504 256c0-115.633-79.14-212.779-186.211-240.236C302.678 11.889 288 23.456 288 39.056z"></path></svg><!-- <i class="fas fa-circle-notch text-danger mr-2"></i> -->Antecedentes Clinicos Relevantes</h6>
                    </div>


            <!-- <div class="col-md-12"> -->

                <!-- <div class="panel panel-default"> -->

                    <!-- <div class="panel-heading" style="height: 30px; background-color: #337ab7 !important;"> -->

                        <!-- <label style="position: relative; color: #ffffff;">Antecedentes Clinicos Relevantes</label> -->

                    <!-- </div> -->

                    <div class="panel-body mt-3" >

                        <div class="row">
                        <!-- <div class="col-md-12"> -->

                            <!-- Infección o colonizción multirresistente -->
                            <div class="col-lg-3">
                                <div class="checkbox">
                                    <input id="frm_multirresistente" type="checkbox" class="mr-2" value="S" name="frm_multirresistente" <?php if ($_SESSION['indicaciones']['imageDatos']['frm_multirresistente'] == 'S' || $datos_antecedentesClinicos[2] == 'S') { ?> checked <?php } ?>>
                                    <label class="mifuente" for="frm_multirresistente">
                                        Infección o colonización multirresistente
                                    </label>
                                </div>
                            </div>

                            <!-- Embarazo -->
                            <?php
                            if ($sexoPaciente[0]['sexo'] == 'F') {
                            ?>
                                <div class="col">
                                    <div class="checkbox">
                                        <input id="frm_Embarazo" type="checkbox" class="mr-2" value="S" name="frm_Embarazo" <?php if ($_SESSION['indicaciones']['imageDatos']['frm_Embarazo'] == 'S' || $datos_antecedentesClinicos[3] == 'S') { ?> checked <?php } ?>>
                                        <label class="mifuente" for="frm_Embarazo">Embarazo</label>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>

                            <!-- Diabetes -->
                            <div class="col">
                                <div class="checkbox">
                                    <input id="frm_Diabetes" type="checkbox" class="mr-2" value="S" name="frm_Diabetes" <?php if ($_SESSION['indicaciones']['imageDatos']['frm_Diabetes'] == 'S' || $datos_antecedentesClinicos[4] == 'S') { ?> checked <?php } ?>>
                                    <label class="mifuente" for="frm_Diabetes">Diabetes</label>
                                </div>
                            </div>
                        <!-- </div> -->
<!--  -->
                        <!-- <div class="col-md-12"> -->
                            <!-- Asma -->
                            <div class="col">
                                <div class="checkbox">
                                    <input id="frm_Asma" type="checkbox" class="mr-2" value="S" name="frm_Asma" <?php if ($_SESSION['indicaciones']['imageDatos']['frm_Asma'] == 'S' || $datos_antecedentesClinicos[5] == 'S') { ?> checked <?php } ?>>
                                    <label class="mifuente" for="frm_Asma">Asma</label>
                                </div>
                            </div>

                            <!-- Hipertensión -->
                            <div class="col">
                                <div class="checkbox">
                                    <input id="frm_Hipertension" type="checkbox" class="mr-2" value="S" name="frm_Hipertension" <?php if ($_SESSION['indicaciones']['imageDatos']['frm_Hipertension'] == 'S' || $datos_antecedentesClinicos[6] == 'S') { ?> checked <?php } ?>>
                                    <label class="mifuente" for="frm_Hipertension">Hipertensión</label>
                                </div>
                            </div>


                            <!-- Otro -->
                            <div class="col">
                                <div class="checkbox">
                                    <input id="frm_Otro" type="checkbox" class="mr-2" value="S" name="frm_Otro" <?php if ($_SESSION['indicaciones']['imageDatos']['frm_Otro'] == 'S' || $datos_antecedentesClinicos[7] == 'S') { ?> checked <?php } ?>>
                                    <label class="mifuente" for="frm_Otro">Otro</label>
                                </div>
                            </div>


                            <div class="col" id="frm_div_otros" >
                                <input type="text" class="form-control form-control form-control-sm mifuente" name="frm_otros_text" id="frm_otros_text" placeholder="Otros" <?php if (isset($_SESSION['indicaciones']['imageDatos']['frm_otros_text'])) { ?> value="<?= $_SESSION['indicaciones']['imageDatos']['frm_otros_text'] ?>" <?php } else if (isset($datos_antecedentesClinicos[8])) { ?> value="<?= $datos_antecedentesClinicos[8] ?>" <?php } ?>>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>
