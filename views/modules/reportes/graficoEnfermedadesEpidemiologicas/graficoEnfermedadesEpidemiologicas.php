<?php
session_start();

require("../../../../config/config.php");
require_once("../../../../class/Util.class.php"); 		        $objUtil    = new Util;

$version = $objUtil->versionJS();
?>



<!--
################################################################################################################################################
                                                       			    CARGA JS
-->
<script type="text/javascript" src="<?php echo PATH; ?>/controllers/client/reportes/reporteGraficoEnfermedadesEpidemiologicas/reporteGraficoEnfermedadesEpidemiologicas.js?v=<?php echo $version; ?>"></script>



<!--
################################################################################################################################################
                                                       			 DESPLIGUE TÍTULO
-->
<!-- <div class="m-3">
    <div class="row">
        <label class="text-secondary ml-3"><i class="fas fa-file   mifuente20" style="color: #59a9ff;"></i> Resumen Gráfico Enfermedades Epidemiológicas</label>
        <div class=" col-lg-12 dropdown-divider   mt-2 mb-4 " ></div>
    </div>
</div>
 -->



<!--
################################################################################################################################################
                                                       			 DESPLIGUE PARÁMETROS RESUMEN
-->
<div id='divDesplieguecamposBusqueda'>
    <form id="frm_despliegueParametrosBusqueda" name="frm_despliegueParametrosBusqueda" class="formularios" role="form" method="POST">
        <div class="m-3">
            <div class="row">
                <label class="text-secondary ml-3"><i class="fas fa-file   mifuente20" style="color: #59a9ff;"></i> Resumen Gráfico Enfermedades Epidemiológicas</label>
                <div class=" col-lg-12 dropdown-divider   mt-2 mb-4 " ></div>
            </div>
            <div class="row ">
                <!-- Año Resumen Enfermedades Epidiomológicas -->
                <div  class="form-group col-lg-3">
                    <label class="control-label mifuente">Año Resumen Enfermedades Epidemiológicas</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        <select class="form-control form-control-sm mifuente" name="slc_anioEnfermedadesEpidemiologicas" id="slc_anioEnfermedadesEpidemiologicas">
                            <option value="0" disabled selected>Seleccione Año</option>
                        </select>
                    </div>
                </div>
                <!-- Botón Buscar / Eliminar / Ver e Imprimir PDF-->
                <div  class="form-group col-lg-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="input-group">
                        <button id="btnBuscarResumenesEnfermedadesEpidemiologicas" type="button" class="btn btn-outline-primary btn-sm mifuente enviar col-lg-4 mr-3" alt="Buscar" title="Buscar"> <i class="mr-2 fas fa-search"></i> Buscar</button>

                        <button id="btnEliminar" type="button" class="btn btn-outline-danger btn-sm mifuente resultadoBusqueda col-lg-1 mr-3" alt="Limpiar" title="Limpiar"> <i class=" fas fa-times"></i></button>

                        <button id="btnVerPDF" type="button" class="btn btn-outline-danger btn-sm mifuente resultadoBusqueda col-lg-1 mr-3" alt="verPDF" title="verPDF"> <i class=" fas fa-file-pdf"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!--
################################################################################################################################################
                                                       			 DESPLIGUE PARÁMETROS RESUMEN
-->
<div id="divDespliegueResultados" style=""></div>