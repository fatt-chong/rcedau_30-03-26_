<?php

$categorizaciones = array('ESI-1', 'ESI-2', 'ESI-3', 'ESI-4', 'ESI-5');
 
?>



<!-- 
################################################################################################################################################
                                                    DESPLIEGUE RESUMEN CUMPLIMIENTO CATEGORIZACIÓN ESI
-->


<div class="row">
            <div class="container col-lg-12">
                <div class="table-responsive">
                    <table id="tablaResumenCumplimientoCategorizacionESI" class="table table-striped table-bordered">
                        <thead class="thead-dark">

                <tr>

                    <th colspan="7" style="text-align: center;">RESUMEN CUMPLIMIENTO CATEGORIZACIÓN ESI</th>   

                </tr>

                <tr>

                    <th rowspan="2" width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >CAT</th>            

                    <th colspan="3" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >ADULTOS</th>

                    <th colspan="3" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >PEDIÁTRICOS</th>

                </tr>

                <tr>     

                    <th width="13%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Cantidad DAU</th>

                    <th width="13%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Atendidos a Tiempo</th>

                    <th width="14%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >% Atendidos a Tiempo</th>

                    <th width="13%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Cantidad DAU</th>

                    <th width="13%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Atendidos a Tiempo</th>

                    <th width="14%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >% Atendidos a Tiempo</th>

                </tr>

            </thead>

            <tbody>

                <?php

                echo desplegarDetalleCumplimientoCategorizacionESI($objCon, $objReporte, $categorizaciones, $parametros);

                ?>            

            </tbody>

        </table>

    </div>
</div>

</div>



<!-- 
################################################################################################################################################
                                                       	            FUNCIONES PHP
-->
<?php
function desplegarDetalleCumplimientoCategorizacionESI ( $objCon, $objReporte, $categorizaciones, $parametros ) {

    $textoADesplegar                    = '';

    $totalCategorizaciones              = count($categorizaciones);

    $parametrosAEnviar[]                = array();

    $parametrosAEnviar['fechaAnterior'] = $parametros['fechaAnterior'];

    $parametrosAEnviar['fechaActual']   = $parametros['fechaActual'];

    $resumenCumplimientoCategorizacionESI     = $objReporte->obtenerResumenCumplimientoCategorizacionESI($objCon, $parametrosAEnviar);

    for ( $i = 0; $i < $totalCategorizaciones; $i++ ) {        

        $textoADesplegar .= '

            <tr style="cursor: pointer" id="'.$categorizaciones[$i].'" class="cumplimientoCategorizacionESI">

                <td width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'
                
                    .$categorizaciones[$i].
                    
                '</td>';

                for ( $k = 0; $k < count($resumenCumplimientoCategorizacionESI); $k++ ) {

                    if ( $resumenCumplimientoCategorizacionESI[$k]['tipoAtencion'] == 1 && $resumenCumplimientoCategorizacionESI[$k]['tipoCategorizacion'] == $categorizaciones[$i] ) {

                        break;

                    }

                }         

                $textoADesplegar .= '

                    <td width="13%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'
                    
                        .desplegarNumero($resumenCumplimientoCategorizacionESI[$k]['totalAtencion']). 
                        
                    '</td> 

                    <td width="13%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'
                    
                        .desplegarNumero($resumenCumplimientoCategorizacionESI[$k]['aTiempo']).
                        
                    '</td>

                    <td width="14%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'
                    
                        .desplegarDivisionPorcentual($resumenCumplimientoCategorizacionESI[$k]['aTiempo'], $resumenCumplimientoCategorizacionESI[$k]['totalAtencion']).
                        
                    '%</td>
                    
                ';
                
                for ( $k = 0; $k < count($resumenCumplimientoCategorizacionESI); $k++ ) {

                    if ( $resumenCumplimientoCategorizacionESI[$k]['tipoAtencion'] == 2 && $resumenCumplimientoCategorizacionESI[$k]['tipoCategorizacion'] == $categorizaciones[$i] ) {

                        break;

                    }

                } 

                $textoADesplegar .= '

                    <td width="13%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'
                    
                        .desplegarNumero($resumenCumplimientoCategorizacionESI[$k]['totalAtencion']).
                        
                    '</td> 

                    <td width="13%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'
                    
                        .desplegarNumero($resumenCumplimientoCategorizacionESI[$k]['aTiempo']).
                        
                    '</td>

                    <td width="14%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'
                    
                        .desplegarDivisionPorcentual($resumenCumplimientoCategorizacionESI[$k]['aTiempo'], $resumenCumplimientoCategorizacionESI[$k]['totalAtencion']).
                        
                    '%</td>
                    
                ';                   

        $textoADesplegar .= '
        
            </tr>       
    
        ';

    }

    unset($parametrosAEnviar);

    return $textoADesplegar;

}
?>