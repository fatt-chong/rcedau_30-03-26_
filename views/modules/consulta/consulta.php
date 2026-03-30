<?php
session_start();

require("../../../config/config.php");
$permisos = $_SESSION['permisosDAU'.SessionName];
if ( array_search(830, $permisos) == null ) {
  $GoTo = "../error_permisos.php";
  header(sprintf("Location: %s", $GoTo));
}
ini_set('max_execution_time', 600);
ini_set('memory_limit', '128M');
error_reporting(0);
// require("../../../config/config.php");
require_once('../../../class/Connection.class.php');      $objCon             = new Connection; $objCon->db_connect();
require_once('../../../class/Util.class.php');            $objUtil            = new Util;
require_once('../../../class/Consulta.class.php');        $objConsulta        = new Consulta;
require_once('../../../class/Atencion.class.php');        $objAtencion        = new Atencion;
require_once('../../../class/MotivoConsulta.class.php');  $objMotivo          = new MotivoConsulta;
require_once('../../../class/Config.class.php');          $Config             = new Config;
require_once('../../../class/RegistroClinico.class.php'); $objRegistroClinico = new RegistroClinico;
$datos = [];
if ( $_POST ) {
  $campos       = $objUtil->getFormulario($_POST);
  $_SESSION['modulos']["consulta"]["ConsultaDau"] = $campos;
  $datos        = $objConsulta->listarConsulta($objCon,$campos);
} else if ( isset($_SESSION['modulos']["consulta"]["ConsultaDau"]) ) {
  $campos       = $_SESSION['modulos']["consulta"]["ConsultaDau"];
  $datos        = $objConsulta->listarConsulta($objCon,$campos);
}
$listarMotivos  = $objMotivo->listarMotivo($objCon,'');
$listarAtencion = $objAtencion->listarAtencion($objCon);
$idsuario       = $objUtil->usuarioActivo();
$permisosPerfil = $Config->cargarPermisoDau($objCon,$idsuario);
$version        = $objUtil->versionJS();
?>

<!--
################################################################################################################################################
                                                                    ESTILOS
-->
<style>
  div.dataTables_wrapper div.dataTables_filter label {
    font-weight: normal;
    white-space: nowrap;
    text-align: left;
    display: none;
  }
  div.dataTables_wrapper div.dataTables_info {
    padding-top: 8px;
    white-space: nowrap;
    display: none;
  }
</style>
<!--
################################################################################################################################################
                                                                    ARCHIVO JS
-->

<script type="text/javascript" charset="utf-8" src="<?=PATH?>/controllers/client/consulta/consulta.js?v=<?=$version;?>"></script>
<!--
################################################################################################################################################
                                                        DESPLIGUE WORKLIST CONSULTA
-->
<!-- *********************************************************************
                                Título
**************************************************************************
-->

<!-- *********************************************************************
                               Formulario
**************************************************************************
-->
<form id="frm_consulta" name="frm_consulta" class="formularios" role="form" method="POST">
  <!-- *********************************************************************
                              Campos de Búsqueda
  **************************************************************************
  -->
  <div class="row">
    <!-- Número de Folio -->
    <div class="col-md-2 form-group has-feedback">
      <div class="input-group shadow">
        <div class="input-group-prepend">
          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_dau"><i class="fas fa-pen darkcolor-barra2"></i></div>
        </div>
        <input id="frm_numero_dau" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_numero_dau" placeholder="Número de DAU" value="<?=$campos['frm_dau']?>" aria-describedby="btnGroupAddonfrm_dau">
      </div>
    </div>
    <!-- Tipo Documento -->
    <div class="col-md-2 form-group has-feedback">
      <div class="input-group shadow">
        <div class="input-group-prepend">
          <div class="input-group-text mifuente12" id="btnGroupAddonDocumento"><i class="fas fa-bars darkcolor-barra2"></i></div>
        </div>
        <select class="form-control form-control-sm mifuente12" id="documento" name="documento" aria-describedby="btnGroupAddonDocumento">
          <option value="1" <?php if($campos["documento"]==1) echo "selected"?>>Rut</option>
          <option value="2" <?php if($campos["documento"]==2) echo "selected"?>>N° Documento</option>
        </select>
      </div>
    </div>
    <!-- Número de Documento -->
    <div class="col-md-2 form-group has-feedback">
      <div class="input-group shadow">
        <div class="input-group-prepend">
          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_nroDocumento"><i class="fas fa-pen darkcolor-barra2"></i></div>
        </div>
        <input id="frm_nroDocumento" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_nroDocumento" placeholder="Número de documento" value="<?php if($campos['frm_rut'] && $campos['documento'] == 1) { echo $objUtil->formatearNumero($campos['frm_rut']).'-'.$objUtil->generaDigito($campos['frm_rut']); } else if($campos['frm_extranjero'] && $campos['documento'] == 2) { echo $campos['frm_nroDocumento']; } ?>" aria-describedby="btnGroupAddonfrm_nroDocumento">
      </div>
    </div>
    <!-- Nombre y Apellido -->
    <div class="col-md-2 form-group has-feedback">
      <div class="input-group shadow">
        <div class="input-group-prepend">
          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_nombreCompleto"><i class="fas fa-pen darkcolor-barra2"></i></div>
        </div>
        <input id="frm_nombreCompleto" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_nombreCompleto" placeholder="Nombre y Apellido" value="<?=$campos['frm_nombreCompleto']?>" aria-describedby="btnGroupAddonfrm_nombreCompleto">
      </div>
    </div>
    <!-- Nombre Social -->
    <div class="col-md-2 form-group has-feedback">
      <div class="input-group shadow">
        <div class="input-group-prepend">
          <div class="input-group-text mifuente12" id="btnGroupAddonNombreSocial"><i class="fas fa-pen darkcolor-barra2"></i></div>
        </div>
        <input id="nombreSocial" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="nombreSocial" placeholder="Nombre Social" value="<?=$campos['nombreSocial']?>" aria-describedby="btnGroupAddonNombreSocial">
      </div>
    </div>
    <!-- Tipo de Atención -->
    <div class="col-md-2 form-group has-feedback">
      <div class="input-group shadow">
        <div class="input-group-prepend">
          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_tipo_atencion"><i class="fas fa-bars darkcolor-barra2"></i></div>
        </div>
        <select class="form-control form-control-sm mifuente12" id="frm_tipo_atencion" name="frm_tipo_atencion" aria-describedby="btnGroupAddonfrm_tipo_atencion">
          <option value="" <?php if($campos["frm_tipo_atencion"]==$listarAtencion[$i]["ate_id"]) echo "selected"?>>Seleccione</option>
          <?php for($i = 0; $i < count($listarAtencion); $i++) { ?>
            <option value="<?=$listarAtencion[$i]['ate_id']?>" <?php if($campos["frm_tipo_atencion"]==$listarAtencion[$i]["ate_id"]) echo "selected"?>><?=$listarAtencion[$i]['ate_descripcion']?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <!-- Motivo Consulta -->
    <div class="col-md-2 form-group has-feedback">
      <div class="input-group shadow">
        <div class="input-group-prepend">
          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_motivo"><i class="fas fa-bars darkcolor-barra2"></i></div>
        </div>
        <select class="form-control form-control-sm mifuente12" id="frm_motivo" name="frm_motivo" aria-describedby="btnGroupAddonfrm_motivo">
          <option value="" <?php if($campos["frm_motivo"]==$listarMotivos[$i]["mot_descripcion"]) echo "selected"?>>Seleccione</option>
          <?php for($i = 0; $i < count($listarMotivos); $i++) { ?>
            <option value="<?=$listarMotivos[$i]['mot_id']?>" <?php if($campos["frm_motivo"]==$listarMotivos[$i]["mot_id"]) echo "selected"?>><?=$listarMotivos[$i]['mot_descripcion']?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <!-- Fecha Admisión (Inicio) -->
    <div class="col-md-2 form-group has-feedback">
      <div class="input-group shadow">
        <div class="input-group-prepend">
          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_fecha_admision_desde"><i class="fas fa-calendar darkcolor-barra2"></i></div>
        </div>
        <input type="date" class="form-control form-control-sm mifuente12" name="frm_fecha_admision_desde" id="frm_fecha_admision_desde" onDrop="return false" placeholder="DD/MM/YY" value="<?=$campos['frm_fecha_admision_desde']?>" aria-describedby="btnGroupAddonfrm_fecha_admision_desde">
      </div>
    </div>
    <!-- Fecha Admisión (Fin) -->
    <div class="col-md-2 form-group has-feedback">
      <div class="input-group shadow">
        <div class="input-group-prepend">
          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_fecha_admision_hasta"><i class="fas fa-calendar darkcolor-barra2"></i></div>
        </div>
        <input type="date" class="form-control form-control-sm mifuente12" name="frm_fecha_admision_hasta" id="frm_fecha_admision_hasta" onDrop="return false" placeholder="DD/MM/YY" value="<?=$campos['frm_fecha_admision_hasta']?>" aria-describedby="btnGroupAddonfrm_fecha_admision_hasta">
      </div>
    </div>
    <!-- Cuenta Corriente -->
    <div class="col-md-2 form-group has-feedback">
      <div class="input-group shadow">
        <div class="input-group-prepend">
          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_cuentaCorriente"><i class="fas fa-pen darkcolor-barra2"></i></div>
        </div>
        <input id="frm_cuentaCorriente" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_cuentaCorriente" placeholder="Número de Ctacte" value="<?=$campos['frm_cuentaCorriente']?>" aria-describedby="btnGroupAddonfrm_cuentaCorriente">
      </div>
    </div>
    <!-- Sin Categorización -->
    <div class="col-md-1 form-group has-feedback">
      <div class="form-check">
        <input class="form-check-input" id="checkSinCategorizacionCerrados" name="checkSinCategorizacionCerrados" type="checkbox" value="5" <?php if($campos['checkSinCategorizacionCerrados']) echo "checked"?>>
        <label for="checkSinCategorizacionCerrados" class="form-check-label mifuente12">Sin Categorizar</label>
      </div>
    </div>
    <!-- Datos Históricos -->
    <div class="col-md-1 form-group has-feedback">
      <div class="form-check">
        <input class="form-check-input" id="checkHistorico" name="checkHistorico" type="checkbox" value="H" <?php if($campos['checkHistorico']) echo "checked"?>>
        <label for="checkHistorico" class="form-check-label mifuente12">Datos Históricos</label>
      </div>
    </div>
    <div class="col-lg-2 col-md-2 col-2">
      <div class="input-group-append shadow" id="button-addon4">
          <button id="btnBuscarPaciente" class="btn btn-secondary2  mifuente12 col-lg-6" type="button" style="border-radius: 0rem !important;"><svg class="svg-inline--fa fa-search fa-w-16 mr-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path></svg><!-- <i class="fas fa-search mr-2"></i> -->Buscar</button>
          <button id="btnEliminarFiltrosPa" class="btn btn-outline-secondary2 mifuente12 col-lg-6" type="button" style="border-radius: 0rem !important;">Limpiar</button>
      </div>
    </div>
  </div>



  <!-- *********************************************************************
                          Tabla Despliegue Resultados
  **************************************************************************
  -->
  <div class="row mt-2">

    <div class=" col-lg-12  mifuente">

      <table id="table_consulta_dau" class="table display table-condensed table-hover mifuente" width="100%">

        <thead>

          <tr class="encabezadoListAdmision mifuente12">

            <th >Tipo</th>

            <th >N°Folio</th>

            <th >Doc.</th>

            <th ">N° Doc.</th>

            <th >Fecha Admin.</th>

            <th ">Nombre</th>

            <th >Atencion</th>

            <th >Ctacte</th>

            <th >Consulta</th>

            <th >Cierre Ad.</th>

            <th  style="text-align:center">Acciones</th>

          </tr>

        </thead>

        <tbody id="contenidoTabla" >

          <?php
          for ( $i = 0; $i < count($datos); $i++ ) {

            $ban_verDetalle        = 0;
            $ban_cierreDAU         = 0;
            $ban_verCierreDAU      = 0;
            $ban_verRegistroMedico = 0;
            $ban_aplicarNEA        = 0;
            $ban_aplicarNulo       = 0;
            $ban_verRCE            = 0;
            $colorTr               = "";


            $transexual_bd 	 = $datos[$i]['transexual'];
						$nombreSocial_bd = $datos[$i]['nombreSocial'];
						$nombrePaciente  = $datos[$i]['nombres'].' '.$datos[$i]['apellidopat'].' '.$datos[$i]['apellidomat'];
            $width           = 28;
						$height          = 23;


						$infoPaciente    = $objUtil->infoDatosNombreTabla($transexual_bd,$nombreSocial_bd,$nombrePaciente,$width,$height);

            if ( $datos[$i]['est_id'] == 5 && $datos[$i]['tipo'] == "DAU" && $datos[$i]['dau_categorizacion_actual'] == '' ) {

              $colorTr = "tbl-danger";

            } else {

              $colorTr = "";

            }
            ?>

            <tr id="<?=$datos[$i]['id_paciente']?>" class="puntero detalle <?=$colorTr;?> detalleListAdmision">

              <td>

                <?=$datos[$i]['tipo'];?>

              </td>

              <td>

                <?=$datos[$i]['dau_id'];?>

              </td>

              <td>

                <?php
                if ( $datos[$i]['extranjero'] == "S" && $datos[$i]['rut'] != "0" && $datos[$i]['rut_extranjero'] != "" ) {

                  echo "RUT";

                } else {

                  //FUE REGISTRADO RUT VALIDO
                  if ( ($datos[$i]['rut'] || $datos[$i]['rut'] == 0) && $datos[$i]['extranjero'] != "S" ) {

                    echo "RUT";

                  }

                  //TIPO DE DOC "DNI"
                  if ( ($datos[$i]['rut_extranjero']== 0 || $datos[$i]['rut_extranjero']) && $datos[$i]['id_doc_extranjero'] == 1 ) {

                    echo "DNI";

                  }

                  //TIPO DE DOC "PASAPORTE"
                  if ( ($datos[$i]['rut_extranjero'] == 0 || $datos[$i]['rut_extranjero']) && $datos[$i]['id_doc_extranjero'] == 2 ) {

                    echo "PASAPORTE";

                  }

                  //TIPO DE DOC "OTROS"
                  if ( ($datos[$i]['rut_extranjero'] == 0 || $datos[$i]['rut_extranjero']) && $datos[$i]['id_doc_extranjero'] == 3 ) {

                    echo "OTROS";

                  }

                  if ( $datos[$i]['rut'] == 0 && ($datos[$i]['rut_extranjero'] || $datos[$i]['rut_extranjero'] == 0) && $datos[$i]['extranjero'] == "S" && ($datos[$i]['id_doc_extranjero'] == "" || $datos[$i]['id_doc_extranjero'] == 0) ) {

                    echo "No definido";

                  }

                }
                ?>

              </td>

              <td>

                <?php
                if ( $datos[$i]['extranjero'] == "S" && $datos[$i]['rut'] != "0" && $datos[$i]['rut_extranjero'] != "" ) {

                  echo $objUtil->formatearNumero($datos[$i]['dau_numero_documento']).'-'.$objUtil->generaDigito($datos[$i]['dau_numero_documento']);

                } else {

                  if ( ($datos[$i]['rut'] || $datos[$i]['rut'] == 0 ) && $datos[$i]['extranjero'] != "S" ) {

                    echo $objUtil->formatearNumero($datos[$i]['rut']).'-'.$objUtil->generaDigito($datos[$i]['rut']);

                  } else {

                    echo $datos[$i]['rut_extranjero'];

                  }

                }
                ?>

              </td>

              <td>

                <span class='hide'><?php echo $datos[$i]['dau_admision_fecha']; ?></span>

                <?= date("d-m-Y H:i", strtotime($datos[$i]['dau_admision_fecha']));?>

              </td>

              <td>

                <?=$infoPaciente?>

              </td>

              <td>

                <?=$datos[$i]['ate_descripcion'];?>

              </td>

              <td>

                <?=$datos[$i]['idctacte'];?>

              </td>

              <td>

                <?=$datos[$i]['mot_descripcion'];?>

              </td>

              <td align="center">

              <?php
              if ( $datos[$i]['dau_cierre_administrativo'] == 'S' ) {
              ?>

                <img src="assets/img/DAU-07.png">

              <?php
              } else {

                echo "-";

              }
              ?>

              </td>

              <td style="text-align:center;">

              <?php
              if ( array_search(841, $permisosPerfil) != null ) {
                if ( $datos[$i]['tipo'] != "RAU" ) {
                  $ban_verDetalle = 1; ?>
                  <a href="#" class="item-menu verDetalle" id="<?=$datos[$i]['dau_id']?>" data-toggle="tooltip" data-placement="top" title="Ver Detalle" style="text-decoration: none;">
                    <i class="fas fa-search mifuente20 darkcolor-barra2" ></i>
                  </a>
                <?php } 
                if ( !$objUtil->existe($datos[$i]["fechaReemplazo"]) && $objUtil->existe($datos[$i]["dau_paciente_nn"]) && $datos[$i]["dau_paciente_nn"] === "S" ) { ?>
                  <!-- <a href="#" class="item-menu" data-toggle="tooltip" data-placement="top" title="Reemplazar Datos Paciente" style="text-decoration: none;" >
                    <img
                      src="<?php echo PATH; ?>/assets/img/replace.png"
                      id="<?php echo $datos[$i]['dau_id']; ?>-<?php echo $datos[$i]['id']; ?>-<?php echo $datos[$i]['idctacte']; ?>"
                      class="puntero reemplazarDatosPacienteNN"
                      title="Reemplazar Datos Paciente"
                    >
                  </a> -->
                <?php } 
                }

              if ( array_search(843, $permisosPerfil) != null ) {
                if ( $datos[$i]['dau_cierre_administrativo'] != 'S' && ($datos[$i]['est_id'] == 1 || $datos[$i]['est_id'] == 2 || $datos[$i]['est_id'] == 3 || $datos[$i]['est_id'] == 4 || $datos[$i]['est_id'] == 8) && $datos[$i]['tipo'] == "DAU" ) {
                  $ban_cierreDAU = 1;
                  ?>
                  <a href="#" id="<?=$datos[$i]['dau_id']?>" class="item-menu cierreDAU" data-toggle="tooltip" data-placement="top" title="Cerrar DAU" style="text-decoration: none;">
                    <i class="fas fa-lock-open mifuente20 darkcolor-barra2"></i>
                  </a>
                  <input type="hidden" name="inp_runUsu" id="inp_runUsu" value="<?=$_SESSION['MM_RUNUSU'.SessionName]?>">
                <?php
                }
              }
              if ( $datos[$i]['dau_cierre_administrativo'] == 'S' || ($datos[$i]['est_id'] == 5 || $datos[$i]['est_id'] == 6 || $datos[$i]['est_id'] == 7) && $datos[$i]['tipo'] == "DAU" ) {
                if ( array_search(843, $permisosPerfil) != null ) {
                  $ban_verCierreDAU =1; ?>
                  <a href="#" id="<?=$datos[$i]['dau_id']?>" class="item-menu verCierreDAU" data-toggle="tooltip" data-placement="top" title="Ver DAU" style="text-decoration: none;"><i class="fas fa-lock mifuente20 darkcolor-barra2"></i>
                  </a>
          <?php }
                if ( array_search(844, $permisosPerfil) != null ) {
                  $ban_verRegistroMedico =1; ?>
                  <a href="#" class="item-menu verRegistroMedico" id="<?=$datos[$i]['dau_id']?>" data-toggle="tooltip" data-placement="top" title="Registro Medico" style="text-decoration: none;">
                    <i class="fas fa-file-medical-alt  mifuente20 darkcolor-barra2"></i>
                  </a>
                <?php
                }
              }
              if ( $datos[$i]['est_id'] == 5 && $datos[$i]['tipo'] == "DAU" && $datos[$i]['dau_categorizacion_actual'] == '' ) {
                if ( array_search(852, $permisosPerfil) != null ) {
                ?>
                 <!--  <a href="#" class="item-menu" data-toggle="tooltip" data-placement="top" title="Agregar Categorización" style="text-decoration: none;">
                    <img id="<?=$datos[$i]['dau_id']?>" class="puntero addCategorizacionDAU" src="<?=PATH?>/assets/img/DAU-19.png"  title="">
                  </a> -->
                <?php
                }
              }
              $parametros['dau_id'] = $datos[$i]['dau_id'];
              $datosRce             = $objRegistroClinico->consultaRCE($objCon,$parametros);
              $parametros['rce_id']	= $datosRce[0]['regId'];
              ?>
              <input type="hidden" id="rce_id_<?php echo  $parametros['dau_id']; ?>" name="rce_id" value="<?=$parametros['rce_id'];?>">
              <input type="hidden" id="estadoDau_<?php echo  $parametros['dau_id']; ?>" name="estadoDau" value="<?=$datos[$i]['est_id'];?>">
              <!-- Documento RCE o DAU (RAU si es dato histórico)-->
              <?php if ( ! empty($parametros['rce_id']) && ! is_null($parametros['rce_id']) ){  ?>
                <a href="#" class="item-menu verDauRCE" id="<?=$datos[$i]['dau_id']?>" data-toggle="tooltip" data-placement="top" title="Ver RCE" style="text-decoration: none;">
                  <i  class="fas fa-file-pdf mifuente20 text-danger "></i>
                </a>
              <?php } else { ?>
                <a href="#" class="item-menu verDAU" id="<?=$datos[$i]['dau_id'].'/'.$datos[$i]['dau_admision_fecha'].'/'.$datos[$i]['tipo']?>" data-toggle="tooltip" data-placement="top" title="Ver <?php echo $datos[$i]['tipo']; ?>" style="text-decoration: none;">
                  <i  class="fas fa-file-pdf mifuente20 text-danger "></i>
                </a>
              <?php } ?>
              <?php
              if ($objUtil->existe($parametros["dau_id"]) && $objUtil->existe($datos[$i]['idRecetaGES'])){
              ?>
                <a href="#" class="item-menu verRecetaGES" id="<?=$datos[$i]['dau_id']?>" data-toggle="tooltip" data-placement="top" title="Ver Receta GES" style="text-decoration: none;">
                  <i class="fas fa-prescription-bottle-alt mifuente20 darkcolor-barra2"></i>
                </a>
              <?php
              }
              ?>
              <!-- Detalle DAU -->
              <?php if ( $datos[$i]['tipo'] == 'DAU' ){ ?>
                <a href="#" class="item-menu verDetalleDau" id="<?=$datos[$i]['dau_id']?>" data-toggle="tooltip" data-placement="top" title="Ver Detalle" style="text-decoration: none;">
                  <i  class="fas fa-file-pdf mifuente20 text-danger"></i>
                </a>
              <?php } ?>
              <?php
              if ( array_search(843, $permisosPerfil) != null ) {
                if ( $datos[$i]['dau_cierre_administrativo'] != 'S' && ($datos[$i]['est_id'] == 1 || $datos[$i]['est_id'] == 2 || $datos[$i]['est_id'] == 3 || $datos[$i]['est_id'] == 4 || $datos[$i]['est_id'] == 8) && $datos[$i]['tipo'] == "DAU" ) {
                  $ban_aplicarNEA = 1; ?>
                  <a href="#" class="item-menu aplicarNEA" id="<?=$datos[$i]['dau_id']?>" data-toggle="tooltip" data-placement="top" title="NEA" style="text-decoration: none;">
                    <i class="fas fa-bullhorn mifuente20 darkcolor-barra2"></i>
                  </a>
          <?php }
              }?>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</form>



<!--
################################################################################################################################################
                                                        LEYENDAS
-->
<div class="row mr-2 ml-2">
  <div class="col-12  mifuente p-1 mt-1 mb-0 pb-0">
    <strong><svg class="svg-inline--fa fa-info fa-w-6 mr-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="info" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512" data-fa-i2svg=""><path fill="currentColor" d="M20 424.229h20V279.771H20c-11.046 0-20-8.954-20-20V212c0-11.046 8.954-20 20-20h112c11.046 0 20 8.954 20 20v212.229h20c11.046 0 20 8.954 20 20V492c0 11.046-8.954 20-20 20H20c-11.046 0-20-8.954-20-20v-47.771c0-11.046 8.954-20 20-20zM96 0C56.235 0 24 32.235 24 72s32.235 72 72 72 72-32.235 72-72S135.764 0 96 0z"></path></svg><!-- <i class="fas fa-info mr-2"></i> -->&nbsp;Leyendas </strong>
    <div class="thumbnail">
      <table id="" width="100%" class="display table-condensed table-hover mt-1 ">
        <tbody>
          <tr>
            <td class=" mifuente my-1 py-1 mx-1 px-1 " style="width:20%">
              <label class="" style="margin-bottom: 0rem !important;width: 100%; ">
                <label style="width: 50px;"><center><img src="<?=PATH?>/assets/img/transIco.png" width="19" height="19" ></center></label><label style="font-weight:normal;"> &nbsp;&nbsp;Paciente Transexual. </label>&nbsp;&nbsp;
              </label>
            </td>
            <td class=" mifuente my-1 py-1 mx-1 px-1 " style="width:27%">
              <label class="" style="margin-bottom: 0rem !important;width: 100%;">
                <span style="border:1px solid;border-color:#000; background-color: #FFDBE5" class="color-FFF0F6"><label style="width: 50px;">&nbsp;</label></span><label style="font-weight:normal;"> &nbsp;&nbsp;Pacientes con atención finalizada sin categorizar </label>
              </label>
            </td>
            <td class=" mifuente my-1 py-1 mx-1 px-1 " style="width:19%">
              <label class="" style="margin-bottom: 0rem !important;width: 100%; ">
                <label style="width: 50px;"><center><i class="fas fa-search mifuente16 darkcolor-barra2"></i></center></label><label style="font-weight:normal;"> &nbsp;&nbsp;Ver Detalle </label>&nbsp;&nbsp;
              </label>
            </td>
            <td class=" mifuente my-1 py-1 mx-1 px-1 " style="width:19%">
              <label class="" style="margin-bottom: 0rem !important;width: 100%; ">
                <label style="width: 50px;"><center><i class="fas fa-lock-open mifuente16 darkcolor-barra2"></i></center></label><label style="font-weight:normal;"> &nbsp;&nbsp;Cerrar Dau </label>&nbsp;&nbsp;
              </label>
            </td>
            <td class=" mifuente my-1 py-1 mx-1 px-1 " style="width:18%">
              <label class="" style="margin-bottom: 0rem !important;width: 100%; ">
                <label style="width: 50px;"><center><i class="fas fa-lock mifuente16 darkcolor-barra2"></i></center></label><label style="font-weight:normal;"> &nbsp;&nbsp;Ver Dau </label>&nbsp;&nbsp;
              </label>
            </td>
          </tr>
          <tr>
            <td class=" mifuente my-1 py-1 mx-1 px-1 " style="width:20%">
              <label class="" style="margin-bottom: 0rem !important;width: 100%; ">
                <label style="width: 50px;"><center><i class="fas fa-file-pdf mifuente16 text-danger"></i></center></label><label style="font-weight:normal;"> &nbsp;&nbsp; Ver PDF's (DAU, RCE) </label>&nbsp;&nbsp;
              </label>
            </td>
            <td class=" mifuente my-1 py-1 mx-1 px-1 " style="width:20%">
              <label class="" style="margin-bottom: 0rem !important;width: 100%; ">
                <label style="width: 50px;"><center><i class="fas fa-file-medical-alt  mifuente16 darkcolor-barra2"></i></center></label><label style="font-weight:normal;"> &nbsp;&nbsp; Registro Médico </label>&nbsp;&nbsp;
              </label>
            </td>
            <td class=" mifuente my-1 py-1 mx-1 px-1 " style="width:20%">
              <label class="" style="margin-bottom: 0rem !important;width: 100%; ">
                <label style="width: 50px;"><center><i class="fas fa-edit mifuente16 darkcolor-barra2"></i></center></label><label style="font-weight:normal;"> &nbsp;&nbsp;Agregar Categorización</label>&nbsp;&nbsp;
              </label>
            </td>
            <td class=" mifuente my-1 py-1 mx-1 px-1 "  style="width:20%">
              <label class="" style="margin-bottom: 0rem !important;width: 100%; ">
                <label style="width: 50px;"><center><i class="fas fa-bullhorn mifuente16 darkcolor-barra2"></i></center></label><label style="font-weight:normal;"> &nbsp;&nbsp; N.E.A. </label>&nbsp;&nbsp;
              </label>
            </td>
            <td class=" mifuente my-1 py-1 mx-1 px-1 "  style="width:18%">
              <label class="" style="margin-bottom: 0rem !important;width: 100%; ">
                <label style="width: 50px;"><center><i class="fas fa-window-close mifuente16 text-danger"></i></center></label><label style="font-weight:normal;"> &nbsp;&nbsp;Nulo </label>&nbsp;&nbsp;
              </label>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>