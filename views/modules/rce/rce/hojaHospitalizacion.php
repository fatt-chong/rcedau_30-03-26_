<div class="ScrollStyleModal">
<?php
session_start();
error_reporting(0);
ini_set('max_execution_time', 600);
ini_set('memory_limit', '128M');

require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php');             $objCon                 = new Connection; $objCon->db_connect();
require_once('../../../../class/Util.class.php');                   $objUtil                = new Util;
require_once('../../../../class/Rce.class.php');                    $objRce                 = new Rce;
require_once('../../../../class/Dau.class.php');                    $objDau                 = new Dau;
require_once("../../../../class/RegistroClinico.class.php" );       $objRegistroClinico     = new RegistroClinico;
require_once("../../../../class/HojaHospitalizacion.class.php" );   $objHojaHospitalizacion = new HojaHospitalizacion;


$parametros                   = $objUtil->getFormulario($_POST);
$parametros['dau_id']         = $parametros['idDAU'];
$rsRce                        = $objRegistroClinico->consultaRCE($objCon,$parametros);

$datosHojaHospitalizacion       = $objHojaHospitalizacion->obtenerDatosHojaHospitalizacion2($objCon, $parametros);
?>



<!--
################################################################################################################################################
                                                       			        CARGA JS
-->
<script type="text/javascript" src="<?php echo PATH; ?>/controllers/client/rce/hojaHospitalizacion/hojaHospitalizacion.js?v=212"></script>



<!--
################################################################################################################################################
                                                       	    VARIABLES ENVIADAS POR POST
-->
<?php

if ( $objUtil->existe($_POST) ) {

    $parametros = $objUtil->getFormulario($_POST);

    //Id dau
    echo '<input type="hidden" id="idDau" name="idDAU" value="'.$parametros['idDAU'].'">';

    //Id rce
    echo '<input type="hidden" id="idRCE" name="idRCE" value="'.$parametros['idRCE'].'">';

    //Id paciente
    echo '<input type="hidden" id="idPaciente" name="idPaciente" value="'.$parametros['idPaciente'].'">';

}
$parametros['idDau']      = $parametros['idDAU'];
$datosPaciente            = $objHojaHospitalizacion->obtenerDatosPaciente($objCon, $parametros);
$parametros['idPaciente'] = $datosPaciente[0]['id_paciente'];
$antecedentesMorbidos     = $objHojaHospitalizacion->obtenerAntecedentesMorbidos($objCon, $parametros);

if( count($datosHojaHospitalizacion) == 0 ){
    $datosHojaHospitalizacion[0]['motivoIngreso']       = $datosPaciente[0]['motivoConsulta'];
    $datosHojaHospitalizacion[0]['antecedentesMorbidos']= $antecedentesMorbidos[0]['descripcionAntecedente'];
    $datosHojaHospitalizacion[0]['indicaciones']        = $datosPaciente[0]['indicaciones'];
}
$datosHojaHospitalizacion[0]['motivoIngreso']           = str_replace(['<br>', '<br/>', '<br />'],"\n",$datosHojaHospitalizacion[0]['motivoIngreso']);
$datosHojaHospitalizacion[0]['antecedentesMorbidos']    = str_replace(['<br>', '<br/>', '<br />'],"\n",$datosHojaHospitalizacion[0]['antecedentesMorbidos']);
$datosHojaHospitalizacion[0]['indicaciones']            = str_replace(['<br>', '<br/>', '<br />'],"\n",$datosHojaHospitalizacion[0]['indicaciones']);

?>



<!--
################################################################################################################################################
                                                       	    DESPLIEGUE HOJA HOSPITALIZACIÓN
-->
<div class="container-fluid">

    <div id='divFormularioHojaHospitalizacion' class="row">

        <div class="col-lg-12">

            <form id="frm_hojaHospitalizacion" name="frm_hojaHospitalizacion" role="form" method="POST">

                <h6 id="ensure-correct-role-and-provide-a-label" style=" border-radius: inherit; font-size:15px;"> <svg class="svg-inline--fa fa-circle-notch fa-w-16 text-danger mr-2"  focusable="false" data-prefix="fas" data-icon="circle-notch" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M288 39.056v16.659c0 10.804 7.281 20.159 17.686 23.066C383.204 100.434 440 171.518 440 256c0 101.689-82.295 184-184 184-101.689 0-184-82.295-184-184 0-84.47 56.786-155.564 134.312-177.219C216.719 75.874 224 66.517 224 55.712V39.064c0-15.709-14.834-27.153-30.046-23.234C86.603 43.482 7.394 141.206 8.003 257.332c.72 137.052 111.477 246.956 248.531 246.667C393.255 503.711 504 392.788 504 256c0-115.633-79.14-212.779-186.211-240.236C302.678 11.889 288 23.456 288 39.056z"></path></svg><!-- <i class="fas fa-circle-notch text-danger mr-2"></i> -->Datos del Paciente</h6>

                <fieldset>

                    <div class="row">

                        <!-- Nombre paciente -->
                        <div class="form-group col-lg-2">

                            <label class="encabezado">Nombre:</label>

                        </div>

                        <div class="form-group col-lg-6">

                            <input type="text" class="form-control form-control-sm mifuente12" id="frm_nombrePaciente" name="frm_nombrePaciente" readonly>

                        </div>

                        <!-- Edad paciente -->
                        <div class="form-group col-lg-2">

                            <label class="encabezado">Edad:</label>

                        </div>

                        <div class="form-group col-lg-2">

                            <input type="text" class="form-control form-control-sm mifuente12" id="frm_edadPaciente" name="frm_edadPaciente" readonly>

                        </div>
                        <div class="form-group col-lg-2">
                            <label class="encabezado">Religión:</label>
                        </div>
                        <div class="form-group col-lg-2">
                            <input type="text" class="form-control form-control-sm mifuente12" id="frm_religionPaciente" name="frm_religionPaciente" readonly value="<?= isset($datosHojaHospitalizacion[0]['religion_descripcion']) ? htmlspecialchars($datosHojaHospitalizacion[0]['religion_descripcion']) : ''; ?>">
                        </div>

                    </div>

                    <div class="row">

                        <!-- Anamnesis y motivo de ingreso-->
                        <div class="col-lg-12">

                            <label class="encabezado">Anamnesis y Motivo de Ingreso:</label>

                        </div>

                        <div class="col-lg-12">

                            <textarea rows="5" class="form-control form-control-sm mifuente12" id="frm_motivoIngreso" name="frm_motivoIngreso"><?=htmlspecialchars($datosHojaHospitalizacion[0]['motivoIngreso'])?></textarea>

                        </div>

                    </div>

                    <div class="row mt-2">

                        <!-- Antecedentes mórbidos-->
                        <div class=" col-lg-10">

                            <label class="encabezado">Antecedentes Mórbidos:</label>

                        </div>
                         <div class=" col-lg-2 text-center">

                            <label class="encabezado">Alergias:</label>

                        </div>
                        <div class=" col-lg-10">

                            <textarea rows="3" class="form-control form-control-sm mifuente12" id="frm_antecedentesMorbidos" name="frm_antecedentesMorbidos" ><?=$datosHojaHospitalizacion[0]['antecedentesMorbidos']?></textarea>

                        </div>

                        <!-- Alergias-->
                       <!--  <div class="form-group col-lg-2">

                            <label class="encabezado">Alergias:</label>

                        </div> -->

                        <div class=" col-lg-2 text-center">

                            <input type="radio" id="frm_alergiasSi" name="frm_alergias" value="Si" class=" mifuente12" ><label for="frm_alergias" class="mifuente12">&nbsp;SI</label> &nbsp;&nbsp;&nbsp;
                            <input type="radio" id="frm_alergiasNo" name="frm_alergias" value="No" class=" mifuente12" ><label for="frm_alergias" class="mifuente12">&nbsp;NO</label>

                        </div>

                       <!--  <div class=" col-lg-1">

                            <input type="radio" id="frm_alergiasNo" name="frm_alergias" value="No" class=" mifuente12" ><label for="frm_alergias" class="mifuente12">&nbsp;NO</label>

                        </div> -->

                        <!-- Signos vitales -->
                        <div id='div_bitacoraHoja' class="ScrollStylePBitacoraHoja mb-1 m-2" style="background-color: white; width: 100%" ></div>
                        <!-- <div id="divSignosVitales" class="form-group col-lg-12">

                            <table id="tablaSignosVitales" class="table table-hover table-condensed">

                                <thead>

                                    <tr class="detalle">

                                        <th width="14%" class="encabezado" style="text-align:center;">Hora</th>

                                        <th width="14%" class="encabezado" style="text-align:center;">P.A.</th>

                                        <th width="14%" class="encabezado" style="text-align:center;">Pulso</th>

                                        <th width="14%" class="encabezado" style="text-align:center;">Resp.</th>

                                        <th width="14%" class="encabezado" style="text-align:center;">Temp. Ax.</th>

                                        <th width="14%" class="encabezado" style="text-align:center;">Rectal</th>

                                        <th width="16%" class="encabezado" style="text-align:center;">Sat.</th>

                                    </tr>

                                </thead>

                                <tbody>


                                </tbody>

                            </table>

                        </div> -->

                    </div>

                </fieldset>

                

                    
                <h6 id="ensure-correct-role-and-provide-a-label" style=" border-radius: inherit; font-size:15px;"> <svg class="svg-inline--fa fa-circle-notch fa-w-16 text-danger mr-2"  focusable="false" data-prefix="fas" data-icon="circle-notch" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M288 39.056v16.659c0 10.804 7.281 20.159 17.686 23.066C383.204 100.434 440 171.518 440 256c0 101.689-82.295 184-184 184-101.689 0-184-82.295-184-184 0-84.47 56.786-155.564 134.312-177.219C216.719 75.874 224 66.517 224 55.712V39.064c0-15.709-14.834-27.153-30.046-23.234C86.603 43.482 7.394 141.206 8.003 257.332c.72 137.052 111.477 246.956 248.531 246.667C393.255 503.711 504 392.788 504 256c0-115.633-79.14-212.779-186.211-240.236C302.678 11.889 288 23.456 288 39.056z"></path></svg><!-- <i class="fas fa-circle-notch text-danger mr-2"></i> -->Hipótesis Diagnósticas</h6>

                <!-- <legend>Hipótesis Diagnósticas</legend> -->

                <fieldset>
                    <div class="col-md-12">
                        <label class="encabezado">Hipótesis Final</label>
                        <input type="text" name="frm_hipotesis_final" id="frm_hipotesis_final" disabled class="form-control form-control-sm mifuente "  value="<?=$rsRce[0]['regHipotesisFinal'];?>" >
                    </div>
                    <div class="mt-3 col-lg-12">

                        <textarea rows="5" class="form-control form-control-sm mifuente12" id="frm_hipotesisDiagnostica" name="frm_hipotesisDiagnostica"></textarea>

                    </div>
                    

                </fieldset>
                <br>
                <h6 id="ensure-correct-role-and-provide-a-label" style=" border-radius: inherit; font-size:15px;"> <svg class="svg-inline--fa fa-circle-notch fa-w-16 text-danger mr-2"  focusable="false" data-prefix="fas" data-icon="circle-notch" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M288 39.056v16.659c0 10.804 7.281 20.159 17.686 23.066C383.204 100.434 440 171.518 440 256c0 101.689-82.295 184-184 184-101.689 0-184-82.295-184-184 0-84.47 56.786-155.564 134.312-177.219C216.719 75.874 224 66.517 224 55.712V39.064c0-15.709-14.834-27.153-30.046-23.234C86.603 43.482 7.394 141.206 8.003 257.332c.72 137.052 111.477 246.956 248.531 246.667C393.255 503.711 504 392.788 504 256c0-115.633-79.14-212.779-186.211-240.236C302.678 11.889 288 23.456 288 39.056z"></path></svg><!-- <i class="fas fa-circle-notch text-danger mr-2"></i> -->Hospitalización</h6>

                <fieldset>

                    <div class=" col-lg-12">

                        <label class="encabezado" ">Hospitalizar en el Servicio de:</label>

                    </div>

                    <div class=" col-lg-12">

                        <input type="text" class="form-control form-control-sm mifuente12" id="frm_hospitalizarEnServicio" name="frm_hospitalizarEnServicio" readonly>

                    </div>

                    <!-- Indicaciones -->
                    <div class=" col-lg-2">

                        <label class="encabezado" style="margin-top:5px;">Indicaciones</label>

                    </div>

                    <div class="col-lg-12">

                        <textarea rows="5" class="form-control form-control-sm mifuente12" id="frm_indicaciones" name="frm_indicaciones"><?=$datosHojaHospitalizacion[0]['indicaciones']?></textarea>

                    </div>

                </fieldset>

            </form>

        </div>

    </div>

</div>
</div>