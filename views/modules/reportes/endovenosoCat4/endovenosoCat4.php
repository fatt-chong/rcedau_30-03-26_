<?php
session_start();

require("../../../config/config.php");
require_once("../../../class/Connection.class.php");
require_once("../../../class/Reportes.class.php");
require_once("../../../class/Util.class.php");

$objReporte = new Reportes;

$objUtil    = new Util;

$version    = $objUtil->versionJS();
?>



<!--
################################################################################################################################################
                                                       			    CARGA JS
-->
<script type="text/javascript" src="<?=PATH?>/controllers/client/reportes/endovenosoCat4/endovenosoCat4.js?v=<?=$version;?>"></script>



<!--
################################################################################################################################################
                                                       			 DESPLIGUE TÍTULO
-->
<div class="titulos">
	<h3>
		<span>Resumen ESI 4 con Indicación Tratamiento Endovenoso</span>
	</h3>
</div>

<br>



<!--
################################################################################################################################################
                                                       			 DESPLIGUE PARÁMETROS RESUMEN
-->
<div id='divDesplieguecamposBusqueda'>

    <form id="frm_reporteEndovenosoCat4" name="frm_reporteEndovenosoCat4" class="formularios" role="form" method="POST">

        <div class="row">

            <!-- Fecha Inicio Reporte -->
            <div  class="form-group col-lg-2">

                <label class="control-label">Fecha Inicio Reporte</label>

                <div class="input-group date" id="date_fecha_inicio" data-date-container='#date_fecha_inicio'>

                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>

                    <input id="frm_fechaInicioReporte" type="text" class="form-control" name="frm_fechaInicioReporte" placeholder="Fecha Inicio Reporte" onDrop="return false" data-date-format="dd-mm-yyyy">

                </div>

            </div>

            <!-- Fecha Término Reporte -->
            <div  class="form-group col-lg-2">

                <label class="control-label">Fecha Resumen Término</label>

                <div class="input-group date" id="date_fecha_termino" data-date-container='#date_fecha_termino'>

                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>

                    <input id="frm_fechaTerminoReporte" type="text" class="form-control" name="frm_fechaTerminoReporte" placeholder="Fecha Término Reporte" onDrop="return false" data-date-format="dd-mm-yyyy">

                </div>

            </div>

            <!-- Botón Buscar / Eliminar / Ver e Imprimir PDF-->
            <div  class="form-group col-lg-2">

                <label class="control-label">&nbsp;</label>

                <div class="input-group">

                    <button id="btnBuscarResumenEndovenosoCat4" type="button" class="btn btn-default enviar btn-xs"><img src="<?=PATH?>/assets/img/dau-05_.png" alt="Buscar"></button>

                    <button type="button" class="btn btn-default btn-xs" alt="Limpiar" title="Limpiar" id="btnEliminar"><img src="<?=PATH?>/assets/img/dau-08.png" ></button>

                    <button type="button" class="btn btn-default btn-xs" alt="verPDF" title="verPDf" id="btnVerPDF"><img src="<?=PATH?>/assets/img/pdf24.png"  style="width: 19px; height: 19px;"></button>

                    <button type="button" class="btn btn-default btn-xs" alt="verPDF" title="verExcel" id="btnVerExcel"><img src="<?=PATH?>/assets/img/Excel-03.png"  style="width: 19px; height: 19px;"></button>

                </div>

            </div>

        </div>

    </form>

</div>

<br>



<!--
################################################################################################################################################
                                                       			 DESPLIGUE RESULTADOS
-->
<div id='divDespliegueReporteEndovenosoCat4'>

    <div  class="col-lg-1">&nbsp;</div>

    <div  class="col-lg-10">

        <table id="tablaReporteEndovenosoCat4" class="table table-striped table-bordered table-hover table-condensed tablasHisto">

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