<?php
session_start();
require("../../../../config/config.php");
require_once("../../../../class/Util.class.php");               $objUtil    = new Util;
require_once("../../../../class/Connection.class.php");
require_once("../../../../class/Reportes.class.php"); $objReporte = new Reportes;
require_once("../../../../class/Util.class.php");

$objReporte     = new Reportes;
$objUtil        = new Util;
$version        = $objUtil->versionJS();
?>
<!--
################################################################################################################################################
                                                       			    CARGA JS
-->
<script type="text/javascript" src="<?=PATH?>/controllers/client/reportes/enfermedadesEpidemiologicas/enfermedadesEpidemiologicas.js?v=<?=$version;?>"></script>
<!--
################################################################################################################################################
                                                       			 DESPLIGUE TÍTULO
-->




<!--
################################################################################################################################################
                                                       			 DESPLIGUE PARÁMETROS RESUMEN
-->
<div id='divDesplieguecamposBusqueda'>
    <form id="frm_reporteEnfermedadesEpidemiologicas" name="frm_reporteEnfermedadesEpidemiologicas" class="formularios" role="form" method="POST">
        <div class="m-3">
            <div class="row">
                <label class="text-secondary ml-3"><i class="fas fa-file   mifuente20" style="color: #59a9ff;"></i> Resumen Enfermedades Epidemiológicas</label>
                <div class=" col-lg-12 dropdown-divider   mt-2 mb-4 " ></div>
            </div>
            <div class="row ">

            <!-- Fecha Inicio Reporte -->
                <div  class="form-group col-lg-2">

                    <label class="control-label mifuente">Fecha Inicio Reporte</label>

                    <div class="input-group date" id="date_fecha_inicio" data-date-container='#date_fecha_inicio'>

                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>

                        <input id="frm_fechaInicioReporte" type="text" class="form-control form-control-sm mifuente" name="frm_fechaInicioReporte" placeholder="Fecha Inicio Reporte" onDrop="return false" data-date-format="dd-mm-yyyy">

                    </div>

                </div>

                <!-- Fecha Término Reporte -->
                <div  class="form-group col-lg-2">

                    <label class="control-label mifuente">Fecha Resumen Término</label>

                    <div class="input-group date" id="date_fecha_termino" data-date-container='#date_fecha_termino'>

                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>

                        <input id="frm_fechaTerminoReporte" type="text" class="form-control form-control-sm mifuente" name="frm_fechaTerminoReporte" placeholder="Fecha Término Reporte" onDrop="return false" data-date-format="dd-mm-yyyy">

                    </div>

                </div>

                <!-- Botón Buscar / Eliminar / Ver e Imprimir PDF-->
                 <div  class="form-group col-lg-4">
                    <label class="control-label">&nbsp;</label>
                    <div class="input-group">
                        <button id="btnBuscarResumenEnfermedadesEpidemiologicas" type="button" class="btn btn-outline-primary btn-sm mifuente enviar col-lg-4 mr-3" alt="Buscar" title="Buscar"> <i class="mr-2 fas fa-search"></i> Buscar</button>

                        <button id="btnEliminar" type="button" class="btn btn-outline-danger btn-sm mifuente resultadoBusqueda col-lg-1 mr-3" alt="Limpiar" title="Limpiar"> <i class=" fas fa-times"></i></button>

                        <button id="btnVerPDF" type="button" class="btn btn-outline-danger btn-sm mifuente resultadoBusqueda col-lg-1 mr-3" alt="verPDF" title="verPDF"> <i class=" fas fa-file-pdf"></i></button>

                        <button id="btnVerExcel" type="button" class="btn btn-outline-success btn-sm mifuente resultadoBusqueda col-lg-1 mr-3" alt="verPDF" title="verPDF"> <i class="fas fa-file-excel"></i></button>
                    </div>
                </div>

               <!--  <div  class="form-group col-lg-2">

                    <label class="control-label mifuente">&nbsp;</label>

                    <div class="input-group">

                        <button id="btnBuscarResumenEnfermedadesEpidemiologicas" type="button" class="btn btn-default enviar btn-xs"><img src="<?=PATH?>/assets/img/dau-05_.png" alt="Buscar"></button>

                        <button type="button" class="btn btn-default btn-xs" alt="Limpiar" title="Limpiar" id="btnEliminar"><img src="<?=PATH?>/assets/img/dau-08.png" ></button>

                        <button type="button" class="btn btn-default btn-xs" alt="verPDF" title="verPDf" id="btnVerPDF"><img src="<?=PATH?>/assets/img/pdf24.png"  style="width: 19px; height: 19px;"></button>

                        <button type="button" class="btn btn-default btn-xs" alt="verPDF" title="verExcel" id="btnVerExcel"><img src="<?=PATH?>/assets/img/Excel-03.png"  style="width: 19px; height: 19px;"></button>

                    </div>

                </div> -->

            </div>
        </div>

    </form>

</div>

<br>



<!--
################################################################################################################################################
                                                       			 DESPLIGUE RESULTADOS
-->
<div id='divDespliegueReporteEnfermedadesEpidemiologicas'>



    <div  class="col-lg-12">

        <table id="tablaReporteEnfermedadesEpidemiologicas" class="table table-striped table-bordered table-hover table-condensed tablasHisto" width = "100%">

            <thead>

            </thead>

            <tbody>

            </tbody>

        </table>

    </div>

    <div  class="col-lg-1">&nbsp;</div>

</div>



<!--
################################################################################################################################################
                                                       	            CIERRE CONEXIÓN
-->
<?php

$objCon = NULL;

?>