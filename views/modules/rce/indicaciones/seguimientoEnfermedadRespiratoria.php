<?php
require("../../../../config/config.php");
require_once("../../../../class/Util.class.php");

$objUtil    = new Util;

$parametros = $objUtil->getFormulario($_POST);

$version = $objUtil->versionJS();
?>

<!--
################################################################################################################################################
                                                       			    CARGA JS
-->
<!-- C:\inetpub\wwwroot\RCEDAU\controllers\client\rce\indicaciones\seguimientoEnfermedadRespiratoria.js -->
<script type="text/javascript" src="<?=PATH?>/controllers/client/rce/indicaciones/seguimientoEnfermedadRespiratoria.js?v=<?=$version;?>"></script>


<!-- <style>
    .datepicker {
        z-index: 1050 !important; /* Asegura que esté sobre otros elementos */
        left: 0px !important;
    }
     .ScrollStyleModal{
        max-height: calc(100vh - 180px);
        overflow-x: hidden;
    }
    .input-group .datepicker-dropdown {
        position: absolute !important; /* Corrige la posición del calendario */
    }
</style> -->
<!--
**************************************************************************
                    SEGUIMIENTO ENFERMEDAD RESPIRATORIA
**************************************************************************
-->

<form id="frm_seguimientoEnfermedadRespiratoria" name="frm_seguimientoEnfermedadRespiratoria" class="ScrollStyleModal">

    <input type="hidden" id="idPaciente"         name="idPaciente" value="<?php echo $parametros['idPaciente']?>">

    <input type="hidden" id="idDau"              name="idDau"      value="<?php echo $parametros['idDau']?>">

    <input type="hidden" id="idFormulario"       name="idFormulario">

    <input type="hidden" id="estadoFormulario"   name="estadoFormulario">

    <input type="hidden" id="cantidadFormulario" name="cantidadFormulario">

    <input type="hidden" id="estadoMuestra"      name="estadoMuestra">
    
    <fieldset id="resultadoMuestrasAnteriores" >
        <div class="bd-callout bd-callout-warning border-bottom">
                <h6 id="ensure-correct-role-and-provide-a-label" style=" border-radius: inherit; font-size:15px;"> <svg class="svg-inline--fa fa-circle-notch fa-w-16 text-danger mr-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle-notch" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M288 39.056v16.659c0 10.804 7.281 20.159 17.686 23.066C383.204 100.434 440 171.518 440 256c0 101.689-82.295 184-184 184-101.689 0-184-82.295-184-184 0-84.47 56.786-155.564 134.312-177.219C216.719 75.874 224 66.517 224 55.712V39.064c0-15.709-14.834-27.153-30.046-23.234C86.603 43.482 7.394 141.206 8.003 257.332c.72 137.052 111.477 246.956 248.531 246.667C393.255 503.711 504 392.788 504 256c0-115.633-79.14-212.779-186.211-240.236C302.678 11.889 288 23.456 288 39.056z"></path></svg>Resultado Muestras Anteriores</h6>
            </div>
        <!-- <legend>Resultado Muestras Anteriores</legend> -->

        <div class="panel-body">

            <div class="row">

                <div class="col-md-12">
                
                    <table id="tablaResultadosAnteriores" class="table table-sm table-striped table-hover tablasHisto " >
                    
                        <thead class="table-primary">
                        
                            <tr>
                        
                                <th class="mifuente  text-center" >Omega</th>
                        
                                <th class="mifuente  text-center" > Fecha</th>
                        
                                <th class="mifuente  text-center" >Estado</th>
                        
                            </tr>
                        
                        </thead>
                        
                        <tbody>
                        
                        </tbody>
                        
                    </table>

                </div>
                
            </div>

        </div>

    </fieldset>
    
   <hr>

    <fieldset class="col-md-12">
        <h6 id="ensure-correct-role-and-provide-a-label" style=" border-radius: inherit; font-size:15px;"> <svg class="svg-inline--fa fa-circle-notch fa-w-16 text-danger mr-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle-notch" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M288 39.056v16.659c0 10.804 7.281 20.159 17.686 23.066C383.204 100.434 440 171.518 440 256c0 101.689-82.295 184-184 184-101.689 0-184-82.295-184-184 0-84.47 56.786-155.564 134.312-177.219C216.719 75.874 224 66.517 224 55.712V39.064c0-15.709-14.834-27.153-30.046-23.234C86.603 43.482 7.394 141.206 8.003 257.332c.72 137.052 111.477 246.956 248.531 246.667C393.255 503.711 504 392.788 504 256c0-115.633-79.14-212.779-186.211-240.236C302.678 11.889 288 23.456 288 39.056z"></path></svg>Datos Personales Paciente</h6>
        <!-- <legend>Datos Personales Paciente</legend> -->

        <div class="panel-body">

            <div class="row">

                <div class="col-md-2">

                    <label class="encabezado mifuente">R.U.N.</label>

                    <input type="text" name="frm_seguimientoRUN" id="frm_seguimientoRUN" class="form-control form-control-sm mifuente" placeholder="Ingrese RUN" readonly>

                </div>

                <div class="col-md-6">

                    <label class="encabezado mifuente">Nombre</label>

                    <input type="text" name="frm_seguimientoNombre" id="frm_seguimientoNombre" class="form-control form-control-sm mifuente" placeholder="Ingrese Nombre" readonly>

                </div>

                <div class="col-md-2">

                    <label class="encabezado mifuente">Edad</label>

                    <input type="text" name="frm_seguimientoEdad" id="frm_seguimientoEdad" class="form-control form-control-sm mifuente" placeholder="Ingrese Edad" readonly>

                </div>

                <div class="col-md-2">

                    <label class="encabezado mifuente">Nacionalidad</label>

                    <input type="text" name="frm_seguimientoNacionalidad" id="frm_seguimientoNacionalidad" class="form-control form-control-sm mifuente" placeholder="Ingrese Nacionalidad">

                </div>

            </div>

           

            <div class="row">

                <div class="col-md-4">

                    <label class="encabezado mifuente">País Residencia</label>

                    <input type="text" name="frm_seguimientoPaisResidencia" id="frm_seguimientoPaisResidencia" class="form-control form-control-sm mifuente" placeholder="Ingrese País Residencia">

                </div>

                <div class="col-md-4">

                    <label class="encabezado mifuente">Lugar de Trabajo</label>

                    <input type="text" name="frm_seguimientoLugarTrabajo" id="frm_seguimientoLugarTrabajo" class="form-control form-control-sm mifuente" placeholder="Ingrese Lugar Trabajo">

                </div>

                <div class="col-md-4">

                    <label class="encabezado mifuente">Dirección</label>

                    <input type="text" name="frm_seguimientoDireccion" id="frm_seguimientoDireccion" class="form-control form-control-sm mifuente" placeholder="Ingrese Dirección">

                </div>

            </div>

           

            <div class="row">

                <div class="col-md-4">

                    <label class="encabezado mifuente">Teléfono</label>

                    <input type="text" name="frm_seguimientoTelefono" id="frm_seguimientoTelefono" class="form-control form-control-sm mifuente" placeholder="Ingrese Teléfono">

                </div>



                <div class="col-md-4">

                    <label class="encabezado mifuente">Celular</label>

                    <input type="text" name="frm_seguimientoCelular" id="frm_seguimientoCelular" class="form-control form-control-sm mifuente" placeholder="Ingrese Celular">

                </div>



                <div class="col-md-4">

                    <label class="encabezado mifuente">Correo</label>

                    <input type="text" name="frm_seguimientoCorreo" id="frm_seguimientoCorreo" class="form-control form-control-sm mifuente" placeholder="Ingrese Correo">

                </div>

            </div>

        </div>

    </fieldset>
  <hr>
   

    <fieldset class="col-md-12 mt-3">
        <h6 id="ensure-correct-role-and-provide-a-label" style=" border-radius: inherit; font-size:15px;"> <svg class="svg-inline--fa fa-circle-notch fa-w-16 text-danger mr-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle-notch" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M288 39.056v16.659c0 10.804 7.281 20.159 17.686 23.066C383.204 100.434 440 171.518 440 256c0 101.689-82.295 184-184 184-101.689 0-184-82.295-184-184 0-84.47 56.786-155.564 134.312-177.219C216.719 75.874 224 66.517 224 55.712V39.064c0-15.709-14.834-27.153-30.046-23.234C86.603 43.482 7.394 141.206 8.003 257.332c.72 137.052 111.477 246.956 248.531 246.667C393.255 503.711 504 392.788 504 256c0-115.633-79.14-212.779-186.211-240.236C302.678 11.889 288 23.456 288 39.056z"></path></svg>Datos Muestra</h6>
        <!-- <legend>Datos Muestra</legend> -->

        <div class="panel-body">

            <div class="row">

                <div class="col-md-4">

                    <label class="encabezado mifuente">Lugar Toma de Muestra</label>

                    <input type="text" name="frm_lugarTomaMuestra" id="frm_lugarTomaMuestra" class="form-control form-control-sm mifuente" placeholder="Ingrese Lugar">

                </div>

                <div class="col-md-4">

                    <label class="encabezado mifuente">Muestra Tomada Por</label>

                    <input type="text" name="frm_muestraTomadaPor" id="frm_muestraTomadaPor" class="form-control form-control-sm mifuente" placeholder="Ingrese Nombre">

                </div>

                <div class="col-md-4">
                    <label class="encabezado mifuente">Fecha Muestra</label>
                    <div class="input-group date" id="fechaTomaMuestra" data-date-container='#fechaTomaMuestra'>

                        

                        <input id="frm_fechaMuestra" type="text" class="form-control form-control-sm mifuente" name="frm_fechaMuestra" placeholder="Fecha Muestra" onDrop="return false" data-date-format="dd-mm-yyyy">

                    </div>

                </div>

            </div>

           

           

            <div class="row mt-2">

                <div class="col-md-2">

                    <label class="encabezado mifuente mr-3">Examen Solicitado</label>

                </div>
                <div class="col-md-2">

                    <input class="form-check-input" type="radio" id="frm_examenCovid19" name="frm_examenCovid19" value="N"  >
                    <label class="form-check-label mifuente12 mr-3" for="frm_examenCovid19" >&nbsp; COVID-19</label>

                </div>
                <div class="col-md-2">
                    <input class="form-check-input" type="radio" id="frm_examenCovid19IFI" name="frm_examenCovid19IFI" value="N"><label class="form-check-label mifuente12" for="frm_examenCovid19IFI" >&nbsp; COVID-19 + IFI</label>

                </div>

            </div>

           

            <div class="row">

                <div class="col-md-2">

                    <label class="encabezado mifuente">Tipo Muestra</label>

                     </div>
                <div class="col-md-2">

                    <input class="form-check-input" type="checkbox" id="frm_muestraLavadoBroncoalveolar" name="frm_muestraLavadoBroncoalveolar" value="N"><label class="form-check-label mifuente12" for="frm_muestraLavadoBroncoalveolar" >&nbsp; Lavado Broncoalveolar</label>

                     </div>
                <div class="col-md-1">

                    <input class="form-check-input" type="checkbox" id="frm_muestraEsputo" name="frm_muestraEsputo" value="N"><label class="form-check-label mifuente12" for="frm_muestraEsputo" >&nbsp; Esputo</label>

                     </div>
                <div class="col-md-2">

                    <input class="form-check-input" type="checkbox" id="frm_muestraAspiradoTraqueal" name="frm_muestraAspiradoTraqueal" value="N"><label class="form-check-label mifuente12" for="frm_muestraAspiradoTraqueal" >&nbsp; Aspirado Traqueal</label>

                     </div>
                <div class="col-md-2">

                    <input class="form-check-input" type="checkbox" id="frm_muestraAspiradoNasofaringeo" name="frm_muestraAspiradoNasofaringeo" value="N"><label class="form-check-label mifuente12" for="frm_muestraAspiradoNasofaringeo" >&nbsp; Aspirado Nasofaringeo</label>

                     </div>
                <div class="col-md-1">

                    <input class="form-check-input" type="checkbox" id="frm_muestraTorulasNasofaringeas" name="frm_muestraTorulasNasofaringeas" value="N"><label class="form-check-label mifuente12" for="frm_muestraTorulasNasofaringeas" >&nbsp; Tórulas </label>

                     </div>
                <div class="col-md-2">

                    <input class="form-check-input" type="checkbox" id="frm_muestraTejidoPulmonar" name="frm_muestraTejidoPulmonar" value="N"><label class="form-check-label mifuente12" for="frm_muestraTejidoPulmonar" >&nbsp; Biopsia o Tejido Muscular</label>

                </div>

            </div>

        </div>

    </fieldset>

     <hr>

    <fieldset class="col-md-12 mt-3">
        <h6 id="ensure-correct-role-and-provide-a-label" style=" border-radius: inherit; font-size:15px;"> <svg class="svg-inline--fa fa-circle-notch fa-w-16 text-danger mr-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle-notch" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M288 39.056v16.659c0 10.804 7.281 20.159 17.686 23.066C383.204 100.434 440 171.518 440 256c0 101.689-82.295 184-184 184-101.689 0-184-82.295-184-184 0-84.47 56.786-155.564 134.312-177.219C216.719 75.874 224 66.517 224 55.712V39.064c0-15.709-14.834-27.153-30.046-23.234C86.603 43.482 7.394 141.206 8.003 257.332c.72 137.052 111.477 246.956 248.531 246.667C393.255 503.711 504 392.788 504 256c0-115.633-79.14-212.779-186.211-240.236C302.678 11.889 288 23.456 288 39.056z"></path></svg>Datos Seguimiento</h6>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <label class="encabezado mifuente">¿Cuántas Personas Viven en el Domicilio?</label>
                </div>
                <div class="col-md-4">
                    <input type="text" name="frm_seguimientoCantidadViven" id="frm_seguimientoCantidadViven" class="form-control form-control-sm mifuente" placeholder="Ingrese Cantidad de Personas">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label class="encabezado mifuente">Motivo Sospecha</label>
                    <textarea class="form-control form-control-sm mifuente" rows="3" cols="5" id="frm_seguimientoMotivoSospecha" name="frm_seguimientoMotivoSospecha" placeholder="Ingrese Motivo Sospecha"></textarea>
                </div>
            </div>

           

            <!-- <div class="row mt-2"> -->

                <!-- <div class="col-md-12"> -->

                    <!-- <div class="input-group date" id="seguimientoInicioSintomas" data-date-container='#seguimientoInicioSintomas'> -->

                        <!-- <label class="encabezado mifuente col-lg-2">Inicio Síntomas</label> -->

                        <!-- <input id="frm_seguimientoInicioSintomas" type="text" class="form-control form-control-sm mifuente col-lg-2" name="frm_seguimientoInicioSintomas" placeholder="Fecha Síntomas" onDrop="return false" data-date-format="dd-mm-yyyy"> -->

                    <!-- </div> -->

                <!-- </div> -->

            <!-- </div> -->

           

            <div class="row">
                 <div class="col-md-3">

                <!-- <div class="col-md-12"> -->
                     <label class="encabezado mifuente col-lg-12">Inicio Síntomas</label>
                    <div class="input-group date" id="seguimientoInicioSintomas" data-date-container='#seguimientoInicioSintomas'>

                        <!-- <label class="encabezado mifuente col-lg-2">Inicio Síntomas</label> -->

                        <input id="frm_seguimientoInicioSintomas" type="text" class="form-control form-control-sm mifuente col-lg-12" name="frm_seguimientoInicioSintomas" placeholder="Fecha Síntomas" onDrop="return false" data-date-format="dd-mm-yyyy">

                    </div>

                <!-- </div> -->

            </div>

                <div class="col-md-2">

                    <label class="encabezado mifuente">Estado Ingreso</label>

                    <select class="form-control form-control-sm mifuente" id="frm_seguimientoEstadoIngreso" name="frm_seguimientoEstadoIngreso">

                        <option value="0" disabled selected>Seleccione</option>

                    </select>

                </div>

                <div class="col-md-3">

                    <label class="encabezado mifuente">Antecedentes Epidemiológico</label>

                    <select class="form-control form-control-sm mifuente" id="frm_seguimientoAntecedentesEpidemiologicos" name="frm_seguimientoAntecedentesEpidemiologicos">

                        <option value="0" disabled selected>Seleccione</option>

                    </select>

                </div>

                <div class="col-md-2">

                    <label class="encabezado mifuente">Destino</label>

                    <select class="form-control form-control-sm mifuente" id="frm_seguimientoDestino" name="frm_seguimientoDestino">

                        <option value="0" disabled selected>Seleccione</option>

                    </select>

                </div>
                
                <div class="col-md-2">

                    <label class="encabezado mifuente">Embarazada</label>

                    <select class="form-control form-control-sm mifuente" id="frm_seguimientoEmbarazada" name="frm_seguimientoEmbarazada">

                        <option value="N" selected>No</option>
                        
                        <option value="S">Si</option>

                    </select>

                </div>

            </div>

           

            <div class="row">

                <div class="col-md-12">

                    <label class="encabezado mifuente">Observaciones</label>

                    <textarea class="form-control form-control-sm mifuente" rows="4" cols="5" id="frm_seguimientoObservaciones" name="frm_seguimientoObservaciones" placeholder="Ingrese Observaciones"></textarea>

                </div>

            </div>

        </div>

    </fieldset>

</form>