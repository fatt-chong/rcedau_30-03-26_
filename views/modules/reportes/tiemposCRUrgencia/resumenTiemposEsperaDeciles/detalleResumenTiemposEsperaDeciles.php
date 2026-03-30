<?php
session_start();
error_reporting(0);
?>

<div id='divDespliegueDetalleResumenTiemposEsperaDeciles'>

    <div class="col-lg-12">

    <?php
    
    require("../../../../../config/config.php");
    require_once("../../../../../class/Connection.class.php");  
    require_once("../../../../../class/Reportes.class.php");            $objReporte = new Reportes;
    require_once("../../../../../class/Util.class.php");                $objUtil    = new Util;

    $campos            = array();
    
    $parametrosAEnviar = array();    

    $objCon            = null;

    obtenerDatosEnviadosPorPOST($objCon, $objUtil, $objReporte, $campos, $parametrosAEnviar, $totalPag, $_POST);  

    $titulo = tituloSegunTipoCategorizacion($parametrosAEnviar['tipoCategorizacion']);   

    accionPagina($objUtil, $totalPag, $_POST['accion']);      

    $resultado = $objReporte->obtenerDetalleResumenTiemposEsperaDeciles($objCon, $parametrosAEnviar, $totalPag, $total);

    $version   = $objUtil->versionJS();
    ?>



    <!-- 
    ################################################################################################################################################
                                                                        CARGA JS
    -->
    <script type="text/javascript" src="<?=PATH?>/controllers/client/reportes/reportesTiemposCRUrgencia/detalleResumenTiemposEsperaDeciles.js?v=<?=$version;?>"></script>



    <!-- 
    ################################################################################################################################################
                                                                    DESPLIGUE TÍTULO
    -->
    <div class="titulos">
        <h3>
            <span>Detalle Resumen Tiempos Espera por Deciles <?php echo $titulo; ?></span>
        </h3>
    </div>

    <br>



    <!-- 
    ################################################################################################################################################
                                                    DESPLIGUE PARÁMETROS DETALLE TIEMPOS ESPERA POR DECILES
    -->
    <div  class="col-lg-12">

        <form id="frm_despliegueParametrosBusquedaDetalleResumenTiemposEsperaDeciles" name="frm_despliegueParametrosBusquedaDetalleResumenTiemposEsperaDeciles" class="formularios" role="form" method="POST">

            <!-- Campos ocultos -->

            <input type="hidden" id="totalPag" name="totalPag" value="<?= $totalPag;?>"/>

            <input type="hidden" id="fechaAnterior" name="fechaAnterior" value="<?= $campos['fechaAnterior'];?>"/>

            <input type="hidden" id="fechaActual" name="fechaActual" value="<?= $campos['fechaActual'];?>"/>

            <input type="hidden" id="tipoCategorizacion" name="tipoCategorizacion" value="<?= $campos['tipoCategorizacion'];?>"/>

            <input type="hidden" id="tipoAtencion" name="tipoAtencion" value="<?= $campos['tipoAtencion'];?>"/>

            <div class="row">

                <!-- Número DAU -->
                <div  class="form-group col-lg-2">
                
                    <label class="control-label mifuente">Número DAU</label>
                
                    <div class="input-group">
                
                        <span class="input-group-addon"><i class="glyphicon glyphicon-folder-open"></i></span>
                
                        <input id="frm_numeroDAU" type="text" class="form-control form-control-sm mifuente" name="frm_numeroDAU" value="<?php echo $campos['frm_numeroDAU']; ?>" placeholder="Ingrese Nº DAU">
                
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

                <!-- Nümero de Decil -->
                <div  class="form-group col-lg-2">
                
                    <label class="control-label mifuente">Número de Decil</label>
                
                    <div class="input-group">
                
                        <span class="input-group-addon"><i class="glyphicon glyphicon-sort-by-attributes"></i></span>
                        
                        <select class="form-control form-control-sm mifuente" name="frm_numeroDecil" id="frm_numeroDecil"> 

                            <option value="" selected disabled>Seleccione Nº de Decil</option>

                            <?php

                            echo opcionesDeDeciles($objCon, $objReporte, $parametrosAEnviar, $campos['frm_numeroDecil']);                                                    

                            ?>                                           
                        
                        </select>
                
                    </div>
                
                </div>

                <!-- Botón Buscar / Eliminar -->
                <div  class="form-group col-lg-2">
                    
                    <label class="control-label mifuente">&nbsp;</label>
                    
                    <div class="input-group">
                    
                        <button id="btnBuscarDetalleResumenTiemposEsperaDeciles" type="button" class="btn btn-default enviar btn-xs"><img src="<?=PATH?>/assets/img/dau-05_.png" alt="Buscar"></button>
                        
                        <?php
                        if( count($campos) > 1 ) { 
                        ?>

                            <button type="button" class="btn btn-default btn-xs" alt="Limpiar" title="Limpiar" id="btnEliminarFiltroBusquedaDetalleResumenTiemposEsperaDeciles"><img src="<?=PATH?>/assets/img/dau-08.png" ></button>
                    
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

            <div id="resultadosDetalleResumenTiemposEsperaDeciles">

                <table id="tablaDetalleResumenTiemposEsperaDeciles" class="table table-hover table-condensed ">

                    <thead>

                        <tr class="detalle">

                            <th width="10%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><label>Número DAU</label></th>

                            <th width="10%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><label>Tipo Atención</label></th>

                            <th width="20%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><label>Nombre Paciente</label></th>

                            <th width="15%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><label>RUT Paciente</label></th>

                            <?php

                            if ( $parametrosAEnviar['tipoCategorizacion'] != 'NEA' ) {

                                echo '<th width="15%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><label>Categorización</label></th>';

                                echo '<th width="15%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><label>Inicio Atención</label></th>';

                            } else {

                                echo '<th width="15%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><label>Admisión</label></th>';

                                echo '<th width="15%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><label>Cierre DAU</label></th>';

                            }

                            ?>

                            <th width="15%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><label>Tiempo Espera</label></th>

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

                            <?php

                            if ( $parametrosAEnviar['tipoCategorizacion'] != 'NEA' ) {

                                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.existeFecha($resultado[$i]['fechaCategorizacion']).'</td>';

                                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.existeFecha($resultado[$i]['fechaInicioAtencion']).'</td>';

                            } else {

                                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.existeFecha($resultado[$i]['fechaAdmision']).'</td>';

                                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.existeFecha($resultado[$i]['fechaCierre']).'</td>';

                            }

                            ?>

                            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $resultado[$i]['tiempoEspera']; ?></td>

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

    <div class="col-lg-1"></div>

</div>



<!-- 
################################################################################################################################################
                                                                FUNCIONES PHP
-->
<?php
function obtenerDatosEnviadosPorPOST ( &$objCon, $objUtil, $objReporte, &$campos, &$parametrosAEnviar, &$totalPag, $metodoPOST ) {

    $campos                                  = $objUtil->getFormulario($metodoPOST);

    $totalPag                                = $campos['totalPag'];

    $parametrosAEnviar['fechaAnterior']      = date('Y-m-d', strtotime($campos['fechaAnterior']));

    $parametrosAEnviar['fechaActual']        = date('Y-m-d', strtotime($campos['fechaActual']));

    $parametrosAEnviar['tipoCategorizacion'] = $campos['tipoCategorizacion'];

    $parametrosAEnviar['tipoAtencion']       = $campos['tipoAtencion'];		

    $parametrosAEnviar['numeroDAU']          = $campos['frm_numeroDAU'];	

    $parametrosAEnviar['nombrePaciente']     = $campos['frm_nombrePaciente'];

    $parametrosAEnviar['rutPaciente']        = $campos['frm_rutPaciente'];

    $objCon                                  = $objUtil->cambiarServidorReporte($parametrosAEnviar['fechaAnterior'], $parametrosAEnviar['fechaActual']); 

    $objReporte->crearTablaTemporalTotalMuestrasDetalle($objCon, $parametrosAEnviar);

    if ( ! empty($campos['frm_numeroDecil']) && ! is_null($campos['frm_numeroDecil']) ) {

        $arrayLimitOffset                     = split('/', $campos['frm_numeroDecil']);

        $parametrosAEnviar['desdeDondeTomar'] = $arrayLimitOffset[0];

        $parametrosAEnviar['cantidadATomar']  = $arrayLimitOffset[1];

    }

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



function opcionesDeDeciles ( $objCon, $objReporte, $parametrosAEnviar, $numeroDecil ) {

    $totalMuestras = $objReporte->obtenerTotalMuestrasDetalle($objCon, $parametrosAEnviar);

    $textoADesplegar = '';

    $desdeDondeTomar = 0;

    $totalMuestraPorDeciles = round($totalMuestras['totalMuestras'] / 10);

    for ( $k = 0; $k < 10; $k++ ) {

        $selectedNumeroDecil = '';
        
        if ( $numeroDecil == $desdeDondeTomar.'/'.$totalMuestraPorDeciles ) {

            $selectedNumeroDecil = 'selected';

        }

        $textoADesplegar .= '<option value="'.$desdeDondeTomar.'/'.$totalMuestraPorDeciles.'" '.$selectedNumeroDecil.'>Decil Nº '.($k+1).'</option>';

        $desdeDondeTomar += $totalMuestraPorDeciles;

    }  

    return $textoADesplegar;  

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

$objReporte->eliminarTablaTemporalDetalleMuestrasDeciles($objCon);

$objCon = NULL;

?>