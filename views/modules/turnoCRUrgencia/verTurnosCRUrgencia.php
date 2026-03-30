<?php
session_start();
error_reporting(0);
require("../../../config/config.php");
require_once("../../../class/Connection.class.php");        $objCon     = new Connection();         $objCon->db_connect();
require_once("../../../class/Util.class.php"); 		        $objUtil    = new Util;
require_once("../../../class/TurnoCRUrgencia.class.php"); 	$objTurno   = new TurnoCRUrgencia();

$totalPag = $_POST['totalPag'];
if ( $_POST ) {
    $campos = $objUtil->getFormulario($_POST);		
    $_SESSION['modulos']["verResumenesTurnoCRUrgencia"]["worklist"] = $campos;
} else if ( isset($_SESSION['modulos']["verResumenesTurnoCRUrgencia"]["worklist"]) ) {
    $campos = $_SESSION['modulos']["verResumenesTurnoCRUrgencia"]["worklist"];
} 
if($_POST['tipo'] == 2){
    $campos['tipo'] = 2;
    $parametros['tipo_entrega'] = 2;
}else{
    $parametros['tipo_entrega'] = 1;
    $campos['tipo'] = 1;
}
switch ( $_POST['accion'] ) {
    case 1:		
        $_SESSION['pagina_actual'] 	= 1; 
        $totalPag					= 0;
        $resultado  = $objTurno->obtenerInfoTurnoCRUrgenciaSegunParametros($objCon, $campos, $totalPag, $total);					
    break;
    case 2:		     
        $objUtil->actualizaPagina('-','');
        $resultado  = $objTurno->obtenerInfoTurnoCRUrgenciaSegunParametros($objCon, $campos, $totalPag, $total);	
    break;
    case 3:		
        $objUtil->actualizaPagina('+', $totalPag);
        $resultado  = $objTurno->obtenerInfoTurnoCRUrgenciaSegunParametros($objCon, $campos, $totalPag, $total);						
    break;
    case 4:		    
        $objUtil->actualizaPagina('P','');
        $resultado  = $objTurno->obtenerInfoTurnoCRUrgenciaSegunParametros($objCon, $campos, $totalPag, $total);	
    break;
    case 5:		    
        $objUtil->actualizaPagina('U',$totalPag);
        $resultado  = $objTurno->obtenerInfoTurnoCRUrgenciaSegunParametros($objCon, $campos, $totalPag, $total);	
    break;
    default:
        $_SESSION['pagina_actual'] 	= 1; 
        $totalPag					= 0;
        $resultado  = $objTurno->obtenerInfoTurnoCRUrgenciaSegunParametros($objCon, $campos, $totalPag, $total);		
    break;
}

$tipoHorarioTurno = $objTurno->obtenerTipoHorarioTurnoParametros($objCon,$parametros);
$version          = $objUtil->versionJS(); 

?>



<!-- 
################################################################################################################################################
                                                       			    CARGA JS
-->
<script type="text/javascript" src="<?=PATH?>/controllers/client/turnoCRUrgencia/verTurnosCRUrgencia.js?v=<?=$version;?>"></script>



<!-- 
################################################################################################################################################
                                                       			 DESPLIGUE TÍTULO
-->
<div class="row ">
    <div class="col-lg-10">
        <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i> Ver Resúmenes Turnos CR Urgencia</b></h6>
    </div>
</div>
<!-- 
################################################################################################################################################
                                                       			 DESPLIGUE PARÁMETROS TURNO
-->
<div id='divDespliegueParametrosTurno'>
    <form id="frm_despliegueParametrosTurno" name="frm_despliegueParametrosTurno" class="formularios" role="form" method="POST">
        <input type="hidden" id="totalPag" name="totalPag" value="<?= $totalPag;?>"/>
        <div class="row">
            <!-- Número de Folio -->
            <div class="col-md-2 form-group has-feedback">
              <div class="input-group shadow">
                <div class="input-group-prepend">
                  <div class="input-group-text mifuente12" id="btnGroupAddonfrm_dau"><i class="fas fa-calendar darkcolor-barra2"></i></div>
                </div>
                <input id="frm_fechaResumenTurno" type="date" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_fechaResumenTurno" placeholder="Fecha Resumen Turno" <?php if ( $campos['frm_fechaResumenTurno'] ) { echo 'value='.$campos['frm_fechaResumenTurno']; } ?> aria-describedby="btnGroupAddonfrm_dau">
              </div>
            </div>
            <!-- Tipo Documento -->
            <div class="col-md-4 form-group has-feedback">
              <div class="input-group shadow">
                <div class="input-group-prepend">
                  <div class="input-group-text mifuente12" id="btnGroupAddonDocumento"><i class="fas fa-bars darkcolor-barra2"></i></div>
                </div>
                <?php $totalTipoHorarioTurno = count($tipoHorarioTurno); ?>
                <select class="form-control form-control-sm mifuente1" name="frm_tipoHorarioTurno" id="frm_tipoHorarioTurno">
                    <option value="" selected disabled>Seleccione Tipo Turno</option>
                    <?php
                    for ( $i = 0; $i < $totalTipoHorarioTurno; $i++ ) {
                        $selected = '';
                        if ( $campos['frm_tipoHorarioTurno'] == $tipoHorarioTurno[$i]['idTipoHorarioTurno'] ) {
                            $selected = 'selected';
                        }
                        ?> 
                    <option value="<?php echo $tipoHorarioTurno[$i]['idTipoHorarioTurno']; ?>" <?php echo $selected; ?> ><?php echo $tipoHorarioTurno[$i]['descripcionHorarioTurno']; ?></option>
                    <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-md-4 form-group has-feedback">
              <div class="input-group shadow">
                <div class="input-group-prepend">
                  <div class="input-group-text mifuente12" id="btnGroupAddonDocumento"><i class="fas fa-bars darkcolor-barra2"></i></div>
                </div>
                <select class="form-control form-control-sm mifuente1" name="tipo" id="tipo">
                    <option value="1" <?php if ($campos['tipo'] == 1){ echo "selected" ; } ?> > Entrega de turno Médica</option>
                    <option value="2" <?php if ($campos['tipo'] == 2){ echo "selected" ; } ?>  > Entrega de turno Enfermeria</option>
                </select>
              </div>
            </div>
            <div class="col-lg-2 col-md-2 col-2">
              <div class="input-group-append shadow" id="button-addon4">
                  <button id="btnBuscarResumenTurnoCRUrgencia" class="btn btn-secondary2  mifuente12 col-lg-6" type="button" style="border-radius: 0rem !important;"><svg class="svg-inline--fa fa-search fa-w-16 mr-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path></svg><!-- <i class="fas fa-search mr-2"></i> -->Buscar</button>
                  <button id="btnEliminar" class="btn btn-outline-secondary2 mifuente12 col-lg-6" type="button" style="border-radius: 0rem !important;">Limpiar</button>
              </div>
            </div>
        </div>
    </form>        
</div>
<!-- 
################################################################################################################################################
                                                       			 DESPLIGUE RESULTADOS
-->
<?php 

if ( $totalPag > 0 ) { 

?>
    <div id="resultadosTurnosCRUrgencia">
        <table id="tablaTurnosCRUrgencia" class="table table-hover table-condensed">
            <thead>
                <tr class="detalle">
                    <th width="15%"  style="text-align:center;" class="encabezado mifuente12" ><label>Fecha Entrega Turno</label></th>
                    <th width="25%"  style="text-align:center;" class="encabezado mifuente12" ><label>Descripción Horario Turno</label></th>
                    <th width="25%"  style="text-align:center;" class="encabezado mifuente12" ><label>Profesional Entrega Turno</label></th>
                    <th width="25%"  style="text-align:center;" class="encabezado mifuente12" ><label>Profesional Recibe Turno</label></th>
                    <th style="text-align: center;" width="10%"  class="encabezado" ><label>Ver PDF</label></th>
                </tr>
            </thead>
            <tbody>

                <?php
                $totalResultados = count($resultado);
                for ( $i = 0; $i < $totalResultados; $i++ ) {
                ?>
                <tr>
                    <td  style="vertical-align:middle;" class="mifuente12 my-1 py-1 mx-1 px-1 text-center"><?php echo date('d-m-Y H:i:s', strtotime($resultado[$i]['fechaEntregaTurno'])); ?></td>
                    <td  style="vertical-align:middle;" class="mifuente12 my-1 py-1 mx-1 px-1 text-center"><?php echo $resultado[$i]['descripcionHorarioTurno']; ?></td>
                    <td  style="vertical-align:middle;" class="mifuente12 my-1 py-1 mx-1 px-1"><i class="fas fa-angle-double-right text-danger mifuente18 mr-2"></i><?php echo $resultado[$i]['nombreProfesionalEntregaTurno']; ?></td>
                    <td  style="vertical-align:middle;" class="mifuente12 my-1 py-1 mx-1 px-1"><i class="fas fa-angle-double-left text-success mifuente18 mr-2"></i><?php echo $resultado[$i]['nombreProfesionalRecibeTurno']; ?></td>
                    <td  style="vertical-align:middle;" class="mifuente12 my-1 py-1 mx-1 px-1 text-center" style="text-align: center;">
                        <a href="#" class="item-menu verPDFResumenTurnoRCUrgencia" data-toggle="tooltip" data-placement="top" title="Ver PDF" id="<?php echo $resultado[$i]['idTurnoCRUrgencia'].'/'.$resultado[$i]['fechaEntregaTurno']; ?>" >
                            <i  class="fas fa-file-pdf mifuente20 text-danger "></i>
                        </a>
                    </td>
                <?php  } ?>
            </tbody>
        </table>
    </div>
  


    <!-- 
    ################################################################################################################################################
                                                                    NAVEGADOR DE PÁGINAS
    -->
    <div id="navegadorPaginas" style="border-style: solid; border-width: 0.5px; border-color: gray;">

    <br>

    <table width="100%">
        <tr>        
            <!-- Primera página -->
            <td width="20%" align="right">
                <button id="primero_l" class="btn btn-outline-secondary2 mifuente12 col-lg-1" type="button" style="border-radius: 0rem !important;"><i  class="fas fa-angle-double-left puntero " title="Primera página" ></i></button>

                <!-- <i  class="fas fa-angle-double-left puntero primero_l" title="Primera página" style="cursor: pointer; font-size: 1.5em;"></i> -->
            </td>

            <!-- Página anterior -->
            <td width="2%" align="right">
                <!-- <i class="fas fa-angle-left puntero atras_l" title="Anterior página" style="cursor: pointer; font-size: 1.5em;"></i> -->
                <button id="atras_l" class="btn btn-outline-secondary2 mifuente12 col-lg-12" type="button" style="border-radius: 0rem !important;"><i  class="fas fa-angle-left puntero " title="Anterior página" ></i></button>
            </td>

            <!-- Información de páginas -->
            <td width="24%" align="center">
                <label class="control-label"><?= $total;?> Registros encontrados, mostrando <?php echo $_SESSION['pagina_actual']; ?> de <?= $totalPag;?> páginas.</label>
            </td>

            <!-- Página siguiente -->
            <td width="2%" align="left">

                <button id="siguiente_l" class="btn btn-outline-secondary2 mifuente12 col-lg-12" type="button" style="border-radius: 0rem !important;"><i  class="fas fa-angle-right puntero siguiente_l" title="Siguiente página" ></i></button>
            </td>

            <!-- Última página -->
            <td width="20%" align="left">
                <button id="ultimo_l" class="btn btn-outline-secondary2 mifuente12 col-lg-1" type="button" style="border-radius: 0rem !important;"><i  class="fas fa-angle-double-right puntero " title="Última página" ></i></button>
            </td>
        </tr>
    </table>

    <br>
</div>

<?php
} else {
?>
  
    <table width="100%" border="0">
  
        <tr>
  
            <td><center>¡No hay resultados para desplegar!</center></td>
  
        </tr>
  
    </table>
  
<?php 
}
?>



<!-- 
################################################################################################################################################
                                                       	            CIERRE CONEXIÓN
-->
<?php

$objCon = NULL;

?>