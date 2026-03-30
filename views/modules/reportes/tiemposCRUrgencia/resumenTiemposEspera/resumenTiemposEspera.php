<?php

$categorizaciones = array('ESI-1', 'ESI-2', 'ESI-3', 'ESI-4', 'ESI-5');

?>



<!-- 
################################################################################################################################################
                                                        DESPLIEGUE RESUMEN TIEMPOS DE ESPERA
-->


<div class="row">
    <div class="container col-lg-12">
        <div class="table-responsive">
            <table  id="tablaResumenTiemposEsperaAdultoPediatrico" class="table table-striped table-bordered">
                <thead class="thead-dark">

                <tr>

                    <th colspan="7" style="text-align: center;">RESUMEN TIEMPOS DE ESPERA ADULTOS Y PEDIÁTRICOS</th>   

                </tr>

                <tr>

                    <th rowspan="2" width="25%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >CAT</th>            

                    <th colspan="3" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">ADULTOS</th>

                    <th colspan="3" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">PEDIÁTRICOS</th>

                </tr>

                <tr>     

                    <th width="13%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Cantidad DAU</th>

                    <th width="12%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Espera Promedio</th>

                    <th width="13%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Espera Máximo</th>

                    <th width="12%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Cantidad DAU</th>

                    <th width="13%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Espera Promedio</th>

                    <th width="12%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Espera Máximo</th>

                </tr>
                </thead>
                <tbody>
                    <?php

                    echo desplegarDetalleResumenTiemposEspera($objCon, $objReporte, $categorizaciones, $parametros);

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
function desplegarDetalleResumenTiemposEspera ( $objCon, $objReporte, $categorizaciones, $parametros ) {

    $textoADesplegar                    = '';

    $totalCategorizaciones              = count($categorizaciones);

    $parametrosAEnviar[]                = array();

    $parametrosAEnviar['fechaAnterior'] = $parametros['fechaAnterior'];

    $parametrosAEnviar['fechaActual']   = $parametros['fechaActual'];

    $resumenTiemposEspera               = $objReporte->obtenerResumenTiemposEspera($objCon, $parametrosAEnviar);

    for ( $i = 0; $i < $totalCategorizaciones; $i++ ) {

        $textoADesplegar .= '

            <tr style="cursor: pointer" id="'.$categorizaciones[$i].'" class="resumenTiemposEspera">

                <td width="25%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'
                
                    .$categorizaciones[$i].
                    
                '</td>';

                            
                for ( $k = 0; $k < count($resumenTiemposEspera); $k++ ) {

                    if ( $resumenTiemposEspera[$k]['tipoAtencion'] == 1 && $resumenTiemposEspera[$k]['tipoCategorizacion'] == $categorizaciones[$i] ) {

                        break;

                    } 

                }

                $textoADesplegar .= '        
        
                <td width="13%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'
                
                    .desplegarNumero($resumenTiemposEspera[$k]['totalAtendidos']).
                    
                '</td> 

                <td width="12%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'
                
                    .desplegarNumero($resumenTiemposEspera[$k]['tiempoPromedio']).
                    
                '</td>

                <td width="12%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'
                
                    .desplegarNumero($resumenTiemposEspera[$k]['tiempoMaximo']).
                    
                '</td>';

                for ( $k = 0; $k < count($resumenTiemposEspera); $k++ ) {

                    if ( $resumenTiemposEspera[$k]['tipoAtencion'] == 2 && $resumenTiemposEspera[$k]['tipoCategorizacion'] == $categorizaciones[$i] ) {

                        break;

                    } 

                }

                $textoADesplegar .= '        
        
                <td width="13%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'
                
                    .desplegarNumero($resumenTiemposEspera[$k]['totalAtendidos']).
                    
                '</td> 

                <td width="12%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'
                
                    .desplegarNumero($resumenTiemposEspera[$k]['tiempoPromedio']).
                    
                '</td>

                <td width="12%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'
                
                    .desplegarNumero($resumenTiemposEspera[$k]['tiempoMaximo']).
                    
                '</td>       

            </tr>        
        
        ';

    }

    $resumenTiemposEsperaNEA     = $objReporte->obtenerResumenTiemposEsperaNEA($objCon, $parametrosAEnviar);

    $textoADesplegar .= '
    
        <tr style="cursor: pointer" id="NEA" class="resumenTiemposEspera">

            <td width="25%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >NEA</td>            

            <td width="13%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'
            
                .desplegarNumero($resumenTiemposEsperaNEA[0]['totalAtencion']).
                
            '</td> 

            <td width="12%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'
            
                .desplegarNumero($resumenTiemposEsperaNEA[0]['tiempoPromedio']).
                
            '</td>

            <td width="12%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'
            
                .desplegarNumero($resumenTiemposEsperaNEA[0]['tiempoMaximo']).
                
            '</td>

            <td width="12%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'
            
                .desplegarNumero($resumenTiemposEsperaNEA[1]['totalAtencion']).
                
            '</td>             

            <td width="12%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'
            
                .desplegarNumero($resumenTiemposEsperaNEA[1]['tiempoPromedio']).
                
            '</td>             

            <td width="12%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'
            
                .desplegarNumero($resumenTiemposEsperaNEA[1]['tiempoMaximo']).
                
            '</td> 

        </tr>
    
    ';

    unset($parametrosAEnviar);

    return $textoADesplegar;

}
?>