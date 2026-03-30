<?php
session_start();
error_reporting(0);
?>

<div id='divDespliegueDetalleCumplimientoCategorizacionESI'>
<!--  -->
    <!-- <div class="col-lg-1"></div> -->

    <div class="col-lg-12">

    <?php
    
    require("../../../../../config/config.php");
    require_once("../../../../../class/Connection.class.php");  
    require_once("../../../../../class/Reportes.class.php");            $objReporte = new Reportes;
    require_once("../../../../../class/Util.class.php");                $objUtil    = new Util;

    $campos            = array();
    
    $parametrosAEnviar = array();   

    $totalPag          = 0;

    obtenerDatosEnvidasPorPOST($objUtil, $campos, $parametrosAEnviar, $totalPag, $_POST);  

    $titulo = tituloSegunTipoCategorizacion($parametrosAEnviar['tipoCategorizacion']);   

    accionPagina($objUtil, $totalPag, $_POST['accion']);  

    $objCon     = $objUtil->cambiarServidorReporte($parametrosAEnviar['fechaAnterior'], $parametrosAEnviar['fechaActual']);    

    $resultado  = $objReporte->obtenerDetalleResumenCumplimientoCategorizacionESI($objCon, $parametrosAEnviar, $totalPag, $total);

    $version    = $objUtil->versionJS();
    ?>



    <!-- 
    ################################################################################################################################################
                                                                        CARGA JS
    -->
    <script type="text/javascript" src="<?=PATH?>/controllers/client/reportes/reportesTiemposCRUrgencia/detalleCumplimientoCategorizacionESI.js?v=<?=$version;?>"></script>



    <!-- 
    ################################################################################################################################################
                                                                    DESPLIGUE TÍTULO
    -->
    <div class="titulos">
        <h3>
            <span>Detalle Cumplimiento Categorización ESI <?php echo $titulo; ?></span>
        </h3>
    </div>

    <br>



    <!-- 
    ################################################################################################################################################
                                                            DESPLIGUE PARÁMETROS CUMPLIMIENTO CATEGORIZACIÓN ESI
    -->
    <div  class="col-lg-12">

        <form id="frm_despliegueParametrosBusquedaCumplimientoCategorizacionESI" name="frm_despliegueParametrosBusquedaCumplimientoCategorizacionESI" class="formularios" role="form" method="POST">

            <!-- Campos ocultos -->

            <input type="hidden" id="totalPag" name="totalPag" value="<?= $totalPag;?>"/>

            <input type="hidden" id="fechaAnterior" name="fechaAnterior" value="<?= $campos['fechaAnterior'];?>"/>

            <input type="hidden" id="fechaActual" name="fechaActual" value="<?= $campos['fechaActual'];?>"/>

            <input type="hidden" id="tipoCategorizacion" name="tipoCategorizacion" value="<?= $campos['tipoCategorizacion'];?>"/>

            <div class="row">

                <!-- Número DAU -->
                <div  class="form-group col-lg-2">
                
                    <label class="control-label mifuente">Número DAU</label>
                
                    <div class="input-group">
                
                        <span class="input-group-addon"><i class="glyphicon glyphicon-folder-open"></i></span>
                
                        <input id="frm_numeroDAU" type="text" class="form-control form-control-sm mifuente" name="frm_numeroDAU" value="<?php echo $campos['frm_numeroDAU']; ?>" placeholder="Ingrese Nº DAU">
                
                    </div>
                
                </div>

                <!-- Tipo de Atención -->
                <div  class="form-group col-lg-2">
                
                    <label class="control-label mifuente">Tipo de Atención</label>
                
                    <div class="input-group">
                
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        
                        <select class="form-control form-control-sm mifuente" name="frm_tipoAtencion" id="frm_tipoAtencion"> 

                            <?php

                            $selectedAmbos = $selectedAdulto = $selectedPediatrico = '';

                            seleccionSegunTipoAtecion($campos['frm_tipoAtencion'], $selectedAmbos, $selectedAdulto, $selectedPediatrico);                       

                            ?>

                            <option value="(1, 2)" <?php echo $selectedAmbos; ?> >Adulto y Pediátrico</option>    
                            
                            <option value="(1)" <?php echo $selectedAdulto; ?> >Adulto</option>
                            
                            <option value="(2)" <?php echo $selectedPediatrico; ?> >Pediátrico</option>                                              
                        
                        </select>
                
                    </div>
                
                </div>

                <!-- Nombre Paciente -->
                <div  class="form-group col-lg-3">
                
                    <label class="control-label mifuente">Nombre Paciente</label>
                
                    <div class="input-group">
                
                        <span class="input-group-addon"><i class="glyphicon glyphicon-font"></i></span>
                
                        <input id="frm_nombrePaciente" type="text" class="form-control form-control-sm mifuente" name="frm_nombrePaciente" value="<?php echo $campos['frm_nombrePaciente']; ?>" placeholder="Ingrese Nombre Paciente">
                
                    </div>
                
                </div>

                <!-- RUT Paciente -->
                <div  class="form-group col-lg-2">
                
                    <label class="control-label mifuente">RUT Paciente</label>
                
                    <div class="input-group">
                
                        <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
                
                        <input id="frm_rutPaciente" type="text" class="form-control form-control-sm mifuente" name="frm_rutPaciente" value="<?php if ( ! empty($campos['frm_rutPaciente']) && ! is_null($campos['frm_rutPaciente']) ) { echo $objUtil->setRun_addDV($campos['frm_rutPaciente']); } ?>" placeholder="Ingrese RUT Paciente">
                
                    </div>
                
                </div>

                <!-- A Tiempo -->
                <div  class="form-group col-lg-2">
                
                    <label class="control-label mifuente">A Tiempo</label>
                
                    <div class="input-group">
                
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        
                        <select class="form-control form-control-sm mifuente" name="frm_tipoATiempo" id="frm_tipoATiempo"> 

                            <?php

                            $selectedAmbos = $selectedATiempo = $selectedNoATiempo = '';

                            seleccionSegunTipoTiempo($campos['frm_tipoATiempo'], $selectedAmbos, $selectedATiempo, $selectedNoATiempo);                       

                            ?>

                            <option value="Ambos" <?php echo $selectedAmbos; ?> >Ambos</option>    
                            
                            <option value="A Tiempo" <?php echo $selectedATiempo; ?> >A Tiempo</option>
                            
                            <option value="No a Tiempo" <?php echo $selectedNoATiempo; ?> >No a Tiempo</option>                                              
                        
                        </select>
                
                    </div>
                
                </div>

                <!-- Botón Buscar / Eliminar -->
                <div  class="form-group col-lg-1">
                    
                    <label class="control-label mifuente">&nbsp;</label>
                    
                    <div class="input-group">
                    
                        <button id="btnBuscarDetalleCumplimientoCategorizacionESI" type="button" class="btn btn-default enviar btn-xs"><img src="<?=PATH?>/assets/img/dau-05_.png" alt="Buscar"></button>
                        
                        <?php
                        if( count($campos) > 1 ) { 
                        ?>

                            <button type="button" class="btn btn-default btn-xs" alt="Limpiar" title="Limpiar" id="btnEliminarFiltroBusquedaDetalleCumplimientoCategorizacionESI"><img src="<?=PATH?>/assets/img/dau-08.png" ></button>
                    
                        <?php 
                        } 
                        ?>
                    
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
    <div  class="col-lg-12">

        <?php 
        if ( $totalPag > 0 ) { 
        ?>

            <div id="resultadosDetalleCumplimientoCategorizacionESI">

                <table id="tablaDetalleCumplimientoCategorizacionESI" class="table table-hover table-condensed ">

                    <thead>

                        <tr class="detalle">

                            <th width="10%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><label>Número DAU</label></th>

                            <th width="10%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><label>Tipo Atención</label></th>

                            <th width="20%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><label>Nombre Paciente</label></th>

                            <th width="10%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><label>RUT Paciente</label></th>

                            <th width="10%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><label>Admisión</label></th>

                            <th width="10%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><label>Inicio Atención</label></th>

                            <th width="10%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><label>Tiempo de Espera</label></th>

                            <th width="10%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><label>A Tiempo</label></th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        $totalResultados = count($resultado);

                        for ( $i = 0; $i < $totalResultados; $i++ ) {
                        
                        ?>

                        <tr>

                            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $resultado[$i]['numeroDAU']; ?></td>

                            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $resultado[$i]['tipoAtencion']; ?></td>

                            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $resultado[$i]['nombrePaciente']; ?></td>

                            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $objUtil->setRun_addDV($resultado[$i]['rutPaciente']); ?></td>

                            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo existeFecha($resultado[$i]['fechaCategorizacion']); ?></td>

                            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo existeFecha($resultado[$i]['fechaInicioAtencion']); ?></td>

                            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $resultado[$i]['tiempoEspera']; ?></td>

                            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $resultado[$i]['aTiempo']; ?></td>

                        <?php

                        }

                        ?>
                    
                    </tbody>

                </table>

            </div>

            <br>



            <!-- 
            ################################################################################################################################################
                                                                            NAVEGADOR DE PÁGINAS
            -->
            <div id="navegadorPaginas" style="border-style: solid; border-width: 0.5px; border-color: gray;">

                <br>

                <table width="100%">
                
                    <tr>        
                
                        <td width="20%" align="right">
                
                            <img id="primero_l" class="puntero" src="/rcedau/assets/img/first.png" sizes="100vw" title="Primera página" alt="Primera página"/>
                
                        </td>
                
                        <td width="2%" align="right">
                
                            <img id="atras_l" class="puntero" src="/rcedau/assets/img/previous.png" sizes="100vw" title="Anterior página" alt="Anterior página"/>
                
                        </td>
                
                        <td width="24%" align="center"><label class="control-label mifuente"><?= $total;?> Registros encontrados, mostrando <?php echo $_SESSION['pagina_actual']; ?> de <?= $totalPag;?> páginas.</label></td>
                
                        <td width="2%" align="left">
                
                            <img id="siguiente_l" class="puntero" src="/rcedau/assets/img/next.png" sizes="100vw" title="Siguiente página" alt="Siguiente página"/>
                
                        </td>
                
                        <td width="20%" align="left">
                
                            <img id="ultimo_l" class="puntero" src="/rcedau/assets/img/last.png" sizes="100vw" title="Ultima página" alt="Ultima página"/>
                
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
        
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><center>¡No hay resultados para desplegar!</center></td>
        
                </tr>
        
            </table>
        
        <?php 
        }
        ?>

    </div>

    <!-- <div class="col-lg-1"></div> -->

</div>



<!-- 
################################################################################################################################################
                                                                FUNCIONES PHP
-->
<?php
function obtenerDatosEnvidasPorPOST ( $objUtil, &$campos, &$parametrosAEnviar, &$totalPag, $metodoPOST ) {

    $campos                                  = $objUtil->getFormulario($metodoPOST);

    $totalPag                                = $campos['totalPag'];

    $parametrosAEnviar['fechaAnterior']      = date('Y-m-d', strtotime($campos['fechaAnterior']));

    $parametrosAEnviar['fechaActual']        = date('Y-m-d', strtotime($campos['fechaActual']));

    $parametrosAEnviar['tipoCategorizacion'] = $campos['tipoCategorizacion'];

    $parametrosAEnviar['tipoAtencion']       = $campos['frm_tipoAtencion'];		

    $parametrosAEnviar['numeroDAU']          = $campos['frm_numeroDAU'];	

    $parametrosAEnviar['nombrePaciente']     = $campos['frm_nombrePaciente'];

    $parametrosAEnviar['rutPaciente']        = $campos['frm_rutPaciente'];

    $parametrosAEnviar['aTiempo']            = $campos['frm_tipoATiempo'];

}



function tituloSegunTipoCategorizacion ( $tipoCategorizacion ) {

    return ( $tipoCategorizacion != 'NEA') ? '(Categorización: '.$tipoCategorizacion.')' : '(NEA)';

}



function accionPagina ( $objUtil, &$totalPag, $accion ) {

    switch ( $accion ) {

        case 1:		            
            $_SESSION['pagina_actual'] 	= 1;         
            $totalPag					= 0;		
        break;

        case 2:		                    
            $objUtil->actualizaPagina('-','');            
        break;
        
        case 3:		            
            $objUtil->actualizaPagina('+', $totalPag);					            
        break;
        
        case 4:		                    
            $objUtil->actualizaPagina('P','');            
        break;
        
        case 5:		                    
            $objUtil->actualizaPagina('U',$totalPag);
        break;
        
        default:
            $_SESSION['pagina_actual'] 	= 1; 
            $totalPag					= 0;            
        break;
        
    }

}



function seleccionSegunTipoAtecion ( $tipoAtencion, &$selectedAmbos, &$selectedAdulto, &$selectedPediatrico ) {

 if ( $tipoAtencion== '(1, 2)' || empty($tipoAtencion) || is_null($tipoAtencion) ) {

    $selectedAmbos = 'selected';

    } 

    if ( $tipoAtencion == '(1)' ) {

    $selectedAdulto = 'selected';

    }  

    if ( $tipoAtencion== '(2)' ) {

    $selectedPediatrico = 'selected';

    }     

}



function seleccionSegunTipoTiempo ( $tipoATiempo, &$selectedAmbos, &$selectedATiempo, &$selectedNoATiempo ) {

 if ( $tipoATiempo == 'Ambos' || empty($tipoATiempo) || is_null($tipoATiempo) ) {

    $selectedAmbos = 'selected';

    } 

    if ( $tipoATiempo == 'A Tiempo' ) {

    $selectedATiempo = 'selected';

    }  

    if ( $tipoATiempo== 'No a Tiempo' ) {

    $selectedNoATiempo = 'selected';

    }     

}



function existeFecha ( $fecha ) {

    return ( ! empty($fecha) && ! is_null($fecha) ) ? date('d-m-Y H:i:s', strtotime($fecha)) : '';

}
?>



<!-- 
################################################################################################################################################
                                                       	            CIERRE CONEXIÓN
-->
<?php

$objCon = NULL;

?>