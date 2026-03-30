<?php
session_start();
// error_reporting(0);
require("../../../../config/config.php");
require_once("../../../../class/Util.class.php");               $objUtil    = new Util;
require_once("../../../../class/Connection.class.php");
require_once("../../../../class/Reportes.class.php"); $objReporte = new Reportes;
require_once("../../../../class/Util.class.php");

$version = $objUtil->versionJS();
?>




<!--
################################################################################################################################################
                                                       			    CARGA JS
-->
<script type="text/javascript" src="<?= PATH ?>/controllers/client/reportes/reportesDiariosDAURCE/reportesDiariosDAURCE.js?v=<?= $version; ?>"></script>




<!--
################################################################################################################################################
                                                       			 DESPLIGUE TÍTULO
-->
<!-- <div class="titulos">
  <h3>
    <span>Reportes Diarios DAU RCE</span>
  </h3>
</div>

<br> -->


<!--
################################################################################################################################################
                                                       			 DESPLIGUE BÚSQUEDA
-->
<div id='divBusquedaSolicitudesAPS'>
    <form id="frm_busquedaReportesDiariosDAURCE" name="frm_busquedaReportesDiariosDAURCE" class="formularios" role="form" method="POST">
      <div class="m-3">
          <div class="row">
              <label class="text-secondary ml-3"><i class="fas fa-file   mifuente20" style="color: #59a9ff;"></i> Reportes Diarios DAU RCE</label>
              <div class=" col-lg-12 dropdown-divider   mt-2 mb-4 " ></div>
          </div>
          <div class="row ">

            <!-- Año -->
            <div class="form-group col-lg-2">

              <label class="control-label mifuente">Año</label>

              <div class="input-group">

                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>

                <select class="form-control form-control-sm mifuente" name="frm_anios" id="frm_anios">

                  <option value="0" disabled selected>Seleccione</option>

                </select>

              </div>

            </div>

            <!-- Mes -->
            <div class="form-group col-lg-2">

              <label class="control-label mifuente">Mes</label>

              <div class="input-group">

                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>

                <select class="form-control form-control-sm mifuente" name="frm_meses" id="frm_meses">

                  <option value="0" disabled selected>Seleccione</option>

                </select>

              </div>

            </div>
            <div  class="form-group col-lg-4">
                <label class="control-label">&nbsp;</label>
                <div class="input-group">
                    <button id="btnBuscarResportesDiariosDAURCE" type="button" class="btn btn-outline-primary btn-sm mifuente enviar col-lg-4 mr-3" alt="Buscar" title="Buscar"> <i class="mr-2 fas fa-search"></i> Buscar</button>

                    <button id="btnEliminar" type="button" class="btn btn-outline-danger btn-sm mifuente resultadoBusqueda col-lg-1 mr-3" alt="Limpiar" title="Limpiar"> <i class=" fas fa-times"></i></button>

                </div>
            </div>
          </div>
      </div>
  </form>
</div>



<!--
################################################################################################################################################
                                                                  ERRORES
-->
<div class="container-fluid">

  <div class="row" id="divErrorBuscarReportesDiariosDAURCE"></div>

</div>



<!--
################################################################################################################################################
                                                          DESPLIGUE LISTADO
-->
<div class="row">
  <div class="container col-lg-12" id="despliegueListadoReportesDiariosDAURCE" style="display:none;" >
    <div class="table-responsive">
      <table  id="listadoReportesDiariosDAURCE" class="table table-striped table-bordered" width="100%">
          <thead class="thead-dark">


          <tr>

            <th width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Año</th>

            <th width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Mes</th>

            <th width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Fecha Archivo</th>

            <th width="60%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Nombre Archivo</th>

            <th width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Acciones</th>

          </tr>

        </thead>

        <tbody></tbody>

      </table>
    </div>
  </div>
</div>