<?php
require("../../../config/config.php");
require_once("../../../class/Connection.class.php");    $objCon     = new Connection();
require_once("../../../class/Util.class.php"); 	        $objUtil    = new Util;

$parametros = $objUtil->getFormulario($_POST);

$version    = $objUtil->versionJS(); 
?>



<!-- 
################################################################################################################################################
                                                       			    CARGA JS
-->
<script type="text/javascript" src="<?=PATH?>/controllers/client/solicitud_aps/solicitud_aps_agendamiento.js?v=<?=$version;?>"></script>


<!-- 
**************************************************************************
                    AGENDAMIENTO SOLICITUD
**************************************************************************
-->
<form id="frm_agendamientoSolicitudAPS">

<!-- Estado de Solicitud -->
<div class="row">

    <!-- <div class="col-lg-1"></div> -->

    <div class="col-lg-12">     	

        <!-- <div class="row"> -->

            <label>Estado Solicitud</label>

        <!-- </div> -->

        <!-- <div class="row"> -->
             
            <div id="estadoSolicitud">
           
                <div class="input-group">
            
                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                    
                    <select class="form-control form-control-sm mifuente" name="slc_estadoAgendamientoSolicitud" id="slc_estadoAgendamientoSolicitud">

                        <option value="0" disabled selected>Seleccione Estado</option>
                    
                    </select>
            
                </div>
            
            </div>    

        <!-- </div> -->

    </div>

    <!-- <div class="col-lg-1"></div> -->

</div>



<!-- Prioridad Solicitud -->
<div class="row egresadoConHora">

    <!-- <br> -->
<!--  -->
    <!-- <div class="col-lg-1"></div> -->

    <div class="col-lg-12">     	

        <!-- <div class="row"> -->

            <label class="mifuente">Prioridad Solicitud</label>

        <!-- </div> -->

        <!-- <div class="row"> -->
             
            <div id="prioridadSolicitud">
           
                <div class="input-group">
            
                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                    
                    <select class="form-control form-control-sm mifuente" name="slc_prioridadAgendamientoSolicitud" id="slc_prioridadAgendamientoSolicitud">

                        <option value="0" disabled selected>Seleccione Prioridad</option>
                    
                    </select>
            
                </div>
            
            </div>    

        <!-- </div> -->

    </div>

    <!-- <div class="col-lg-1"></div> -->

</div>

<!-- Programa Solicitud -->
<div class="row egresadoConHora">

    <!-- <br> -->

    <!-- <div class="col-lg-1"></div> -->

    <div class="col-lg-12">     	

        <!-- <div class="row"> -->

            <label class="mifuente">Programa Solicitud</label>

        <!-- </div> -->

        <!-- <div class="row"> -->
             
            <div id="prioridadSolicitud">
           
                <div class="input-group">
            
                    <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
                    
                    <select class="form-control form-control-sm mifuente" name="slc_programaAgendamientoSolicitud" id="slc_programaAgendamientoSolicitud">

                        <option value="0" disabled selected>Seleccione Programa</option>
                    
                    </select>
            
                </div>
            
            </div>    

        <!-- </div> -->

    </div>

    <!-- <div class="col-lg-1"></div> -->

</div>

<!-- Observación -->
<div class="row">

    <br>

    <!-- <div class="col-lg-1"></div> -->

    <div class="col-lg-12">     	

        <!-- <div class="row"> -->

            <label class="mifuente">Observación</label>

        <!-- </div> -->

        <!-- <div class="row"> -->
             
            <div id="observacionSolicitudAPS">
           
                <textarea id="txt_observacionSolicitudAPS" name="txt_observacionSolicitudAPS" class="form-control form-control-sm mifuente" maxlength="500"  rows="3" placeholder="Ingrese Observación"></textarea>
            
            </div>    

        <!-- </div> -->

    </div>

    <!-- <div class="col-lg-1"></div> -->

</div>

</form>



<!-- 
################################################################################################################################################
                                                       	            VARIABLES OCULTAS
-->
<input id="idSolicitudAPS"    value="<?php echo $parametros['idSolicitudAPS']; ?>"    hidden>