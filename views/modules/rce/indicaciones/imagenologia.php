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
// print('<pre>');  print_r($parametros);  print('</pre>');
$datosRce = $objRegistroClinico->consultaRCE($objCon, $parametros);
$parametros['frm_id_paciente'] = $_SESSION['datosPacienteDau']['id_paciente'];
$sexoPaciente = $objPac->pacienteSexo($objCon, $parametros);
$rsTipoExmamen           = $objRayos->getTipoExamen2($objCon);
    $RSplano                 = $objRayos->listaPlano($objCon);
    $RSextremidad            = $objRayos->listaExtremidad($objCon);
// print('<pre>'); print_r($rsTipoExmamen); print('</pre>');
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

        <input type="hidden" id="pacId" name="pacId" value="<?=$parametros['frm_id_paciente']?>" >

        <div class="panel panel-default" style="margin-bottom: 0px;">
            <div class="bd-callout bd-callout-warning border-bottom">
                <h6 id="ensure-correct-role-and-provide-a-label" style=" border-radius: inherit; font-size:15px;"> <svg class="svg-inline--fa fa-circle-notch fa-w-16 text-danger mr-2"  focusable="false" data-prefix="fas" data-icon="circle-notch" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M288 39.056v16.659c0 10.804 7.281 20.159 17.686 23.066C383.204 100.434 440 171.518 440 256c0 101.689-82.295 184-184 184-101.689 0-184-82.295-184-184 0-84.47 56.786-155.564 134.312-177.219C216.719 75.874 224 66.517 224 55.712V39.064c0-15.709-14.834-27.153-30.046-23.234C86.603 43.482 7.394 141.206 8.003 257.332c.72 137.052 111.477 246.956 248.531 246.667C393.255 503.711 504 392.788 504 256c0-115.633-79.14-212.779-186.211-240.236C302.678 11.889 288 23.456 288 39.056z"></path></svg><!-- <i class="fas fa-circle-notch text-danger mr-2"></i> -->Exámenes</h6>
            </div>
            <input type="hidden" id="cod_examen" name="cod_examen" value="">
            <input type="hidden" id="dau_paciente_complejo" name="dau_paciente_complejo" value="<?= $_SESSION['datosPacienteDau']['dau_paciente_complejo'] ?>">
            <div class="panel-body mt-3" >
                <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                
                    <div class="mifuente ">

                        <div class="row mb-2">
                            <div class="col-md-6" id="div_Examenes" >
                                <div id="aquiExamenes" >                        
                                    <div id="examenesCargados_I" >

                                        <input class="form-control form-control-sm" type="text" id="txt_examenes" name="txt_examenes" placeholder="Examen Paciente">

                                        <input type="hidden" id="txt_examenes_codigo" name="txt_examenes_codigo" value="" placeholder="txt_examenes_codigo">
                                        <input type="hidden" id="descripcion_examen" name="descripcion_examen" value="">
                                    

                                        <input type="hidden" name="tipoExamen_2" id="tipoExamen_2" placeholder="tipoExamen_2" >
                                        <input type="hidden" name="conContraste" id="conContraste" placeholder="conContraste">

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 " >
                                <select id="tipoExamen" name="tipoExamen" class="form-control form-control-sm" >
                                    <option value="">Tipo examen...</option>
                                     <?php for ($i = 0; count($rsTipoExmamen) > $i; $i++) { ?>
                                        <option value="<?= $rsTipoExmamen[$i]['tipo_examen'] ?>"><?= $rsTipoExmamen[$i]['tipo_examen']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-2 " id="Div_plano">
                                <!-- <label style="font-size: 13px;" >Lateralidad</label> -->
                                <select id="frm_plano" name="frm_plano" class="form-control form-control-sm mifuente">
                                    <option value="0" disabled selected>Lateralidad</option>
                                    <?php for ($i = 0; count($RSplano) > $i; $i++) { ?>
                                        <option value="<?= $RSplano[$i]['planoID'] ?>"><?= $RSplano[$i]['planoNombre']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-2 mt-1" >
                                <div class="row ml-0
                                ">  
                                    <button type="button" id="btnBorrarExamen" class="btn btn-danger btn-sm col-lg-4 mr-2" ><i class="fas fa-times-circle"></i></button>
                                    <button type="button" id="agregar_examen"  class="btnAgregaExamen col-lg-4 btn btn-primary  btn-sm" name="agregar_examen"  ><i class="fas fa-plus-circle"></i></button>
                                </div>
                            </div> 
                        </div>

                        <div class="row form-group-sm mb-2">
                            <div id="div_informe_sin_contraste" class="col-xs-5 col-md-5 col-lg-5">
                                <div class="checkbox">
                                    <input type="checkbox" name="chk_informe_sin_contraste" id="chk_informe_sin_contraste" class="mifuente" value="S">
                                        <label id="chk_informe_sin_contraste_s" for="chk_informe_sin_contraste" >
                                            Consentimiento Informado
                                        </label>
                                    </input>
                                </div>
                            </div>

                            

                            <div id="div_exacon_hijos" class="col-xs-12 col-md-12 col-lg-12">

                                <div id="div_exacon" class="col-xs-12 col-md-12 col-lg-12">
                                    
                                    <table border="0">
                                        <tr>
                                           <!--  <td>
                                                <label style="font-size: 13px;">Contraste</label>
                                            </td> -->
                                            <td>&nbsp;&nbsp;&nbsp;</td>
                                            <td><label style="font-size: 13px;">Examen con contraste :</label></td>
                                            <td>&nbsp;&nbsp;&nbsp;</td>
                                            <td><label style="font-size: 13px;">Si</label></td>
                                            <td>&nbsp;&nbsp;&nbsp;</td>
                                            <td><input type="radio" id="frm_examen_si" name="chk_exacon" class="chk_exacon" value="S" style="margin-top: -2px;" /></td>
                                            <td>&nbsp;&nbsp;&nbsp;</td>
                                            <td><label style="font-size: 13px;">No</label></td>
                                            <td>&nbsp;&nbsp;&nbsp;</td>
                                            <td><input type="radio" id="frm_examen_ni" name="chk_exacon" class="chk_exacon" value="N" style="margin-top: -2px;" /></td>
                                        </tr>
                                    </table>
                                    
                                </div>

                                <div id="trimage_contraste" class="trimage_contraste row">
                                    <div class="col-xs-4 col-md-4 col-lg-4">
                                        <div class="checkbox">
                                            <input type="checkbox" name="chk_coninf" id="chk_coninf" class="clase_contraste" value="coninf"><label id="chk_coninf_s" for="chk_coninf" style="font-size: 13px;">&nbsp;&nbsp; Consentimiento informado completo</label></input>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-md-4 col-lg-4">
                                        <div class="checkbox">
                                            <input type="checkbox" name="chk_clecre" id="chk_clecre" class="clase_contraste" value="clecre"><label id="chk_clecre_s" for="chk_clecre" style="font-size: 13px;">&nbsp;&nbsp; Clearence de creatinina</label></input>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-md-4 col-lg-4">
                                        <div class="checkbox">
                                            <input type="checkbox" name="chk_preme" id="chk_preme" class="clase_contraste" value="preme"><label id="chk_preme_s" for="chk_preme" style="font-size: 13px;">&nbsp;&nbsp; Premedicación</label></input>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-md-4 col-lg-4">
                                        <div class="checkbox">
                                            <input type="checkbox" name="chk_proren" id="chk_proren" class="clase_contraste" value="proren"><label id="chk_proren_s" for="chk_proren" style="font-size: 13px;">&nbsp;&nbsp; Protección renal</label></input>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-md-4 col-lg-4">
                                        <div class="checkbox">
                                            <input type="checkbox" name="chk_sedacion" id="chk_sedacion" class="clase_contraste" value="sedacion"><label id="chk_sedacion_s" for="chk_sedacion" style="font-size: 13px;">&nbsp;&nbsp; Sedación</label></input>
                                        </div>
                                    </div>

                                    <div id="div_marca_paso" class="col-xs-4 col-md-4 col-lg-4">
                                        <div class="checkbox">
                                            <input type="checkbox" name="chk_marca_paso" id="chk_marca_paso" class="clase_contraste" value="marca_paso"><label id="chk_marca_paso_s" for="chk_marca_paso" style="font-size: 13px;">&nbsp;&nbsp; Marcapasos</label></input>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="div_exacon_hijos_espacio" class="col-xs-6 col-md-6 col-lg-6" hidden>
                            </div>

                            <div class="col-xs-1 col-md-1 col-lg-1">
                                <!-- <button type="button" id="agregar_examen" name="agregar_examen" class="btnAgregaExamen btn btn-primary "><i class="fa fa-plus" aria-hidden="true" style="font-size: 20px;"></i></button> -->
                            </div>
                            <div class="col-xs-12 col-md-12 col-lg-12">
                                <!-- <label id="select_image3_s1" style="font-size: 13px;" >Observación</label> -->
                                <textarea class="form-control mifuente" rows="4" name="frm_examen_obs" id="frm_examen_obs" placeholder="Observación"></textarea>
                            </div>

                        </div>
                         <div class="row ">
                            <input type="hidden" name="tbody_global_examenes" id="tbody_global_examenes" value="0">

                            <div class="col-md-12 mifuente ">
                                <table id="tablaContenido" class="table responsive table-hover table-condensed tablasHisto table-sm">
                                    <thead>
                                        <tr style="color: #21728e;background-color: #b8daff;" >
                                            <th class="text-center" style="font-size: 12px;width: 6%;font-weight:  bold;"  hidden  >Código</th>
                                            <th class="text-center" style="font-size: 12px;width: 10%;font-weight: bold;">Tipo</th>
                                            <th class="text-center" style="font-size: 12px;width: 30%;font-weight: bold;">Nombre del Examen</th>
                                            <th class="text-center" style="font-size: 12px;width: 20%;font-weight: bold;">Observación</th>
                                            <th class="text-center" style="font-size: 12px;width: 10%;font-weight: bold;">Contraste</th>
                                            <th class="text-center" style="font-size: 12px;width: 10%;font-weight: bold;">Lateralidad</th>
                                            <th class="text-center" style="font-size: 12px;width: 6%;font-weight:  bold;" hidden >BD</th>
                                            <th class="text-center" style="font-size: 12px;width: 6%;font-weight:  bold;" hidden >id_sol_imagenologia</th>
                                            <th  class="text-center" style="font-size: 12px;width: 4%;font-weight:  bold;">Eliminar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="contenidoRayo">
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
                                    <input type="text" class="form-control form-control form-control-sm mifuente text_diag" id="frm_diagnostico" name="frm_diagnostico" placeholder="Diagnóstico" <?php if (isset($_SESSION['indicaciones']['imageDatos']['frm_diagnostico'])) { ?> value="<?= $_SESSION['indicaciones']['imageDatos']['frm_diagnostico'] ?>" <?php } else { ?> value="<?= $datosRce[0]['regHipotesisInicial'] ?>" <?php } ?>>
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
