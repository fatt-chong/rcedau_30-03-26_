<?php

$categorizaciones = array('ESI-1', 'ESI-2', 'ESI-3', 'ESI-4', 'ESI-5');

$tipoAtencion     = array(1, 2);

?>



<!-- 
################################################################################################################################################
                                                    DESPLIEGUE RESUMEN TIEMPOS DE ESPERA POR DECILES
-->
<?php

$parametrosAEnviar                    = array();

$parametrosAEnviar['fechaAnterior']   = $parametros['fechaAnterior'];

$parametrosAEnviar['fechaActual']     = $parametros['fechaActual'];

$objReporte->crearTablaTemporalTotalMuestras($objCon, $parametrosAEnviar);

$objReporte->crearTablaTemporalTotalMuestrasNEA($objCon, $parametrosAEnviar);

unset($parametrosAEnviar);

echo desplegarResumenTiemposEsperaDeciles2('ADULTO', $objCon, $objReporte, $categorizaciones, $tipoAtencion[0], $parametros);

echo desplegarResumenTiemposEsperaDeciles2('PEDIÁTRICO', $objCon, $objReporte, $categorizaciones, $tipoAtencion[1], $parametros);

?>



<!-- 
################################################################################################################################################
                                                       	            FUNCIONES PHP
-->
<?php
function desplegarResumenTiemposEsperaDeciles2 ( $tituloTipoAtencion, $objCon, $objReporte, $categorizaciones, $tipoAtencion, $parametros ) {

    $textoADesplegar3 = '';

    $textoADesplegar3 .= '
    
    
        <div class="row">
            <div class="container col-lg-12">
                <div class="table-responsive">
                    <table id="tablaResumenTiemposEsperaDeciles-'.$tipoAtencion.'"class="table table-striped table-bordered">
                        <thead class="thead-dark">

                            <tr>

                                <th colspan="11" style="text-align: center;">RESUMEN TIEMPOS DE ESPERA DECILES '.$tituloTipoAtencion.'</th>   

                            </tr>

                            <tr>

                                <th width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >CAT</th>  

                                <th width="9%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Hrs D1</th>            

                                <th width="9%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Hrs D2</th> 

                                <th width="9%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Hrs D3</th> 

                                <th width="9%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Hrs D4</th> 

                                <th width="9%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Hrs D5</th> 

                                <th width="9%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Hrs D6</th> 

                                <th width="9%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Hrs D7</th> 

                                <th width="9%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Hrs D8</th> 

                                <th width="9%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Hrs D9</th> 

                                <th width="9%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Hrs D10</th> 

                            </tr>

                        </thead>

                        <tbody>';

                            $textoADesplegar3 .= desplegarDetalleResumenTiemposEsperaDeciles($objCon, $objReporte, $categorizaciones, $tipoAtencion, $parametros);

                            $textoADesplegar3 .= desplegarDetalleResumenTiemposEsperaDecilesNEA($objCon, $objReporte, $tipoAtencion, $parametros);
        
                      $textoADesplegar3 .= '</tbody>

                    </table>
                    </div>
                    </div>
                    </div>
    
    ';

    return $textoADesplegar3;

}



function desplegarDetalleResumenTiemposEsperaDeciles ( $objCon, $objReporte, $categorizaciones, $tipoAtencion, $parametros ) {

    $totalCategorizaciones = count($categorizaciones);

    $textoADesplegar3                      = '';

    $parametrosAEnviar                    = array();

    $parametrosAEnviar['fechaAnterior']   = $parametros['fechaAnterior'];

    $parametrosAEnviar['fechaActual']     = $parametros['fechaActual'];

    $parametrosAEnviar['tipoAtencion']    = $tipoAtencion;

    for ( $i = 0; $i < $totalCategorizaciones; $i++ ) {

        $parametrosAEnviar['tipoCategorizacion'] = $categorizaciones[$i];
        
        $totalMuestras                           = $objReporte->obtenerTotalMuestras($objCon, $parametrosAEnviar);

        $totalMuestraPorDeciles                  = round($totalMuestras['totalMuestras'] / 10);

        $parametrosAEnviar['desdeDondeTomar']    = 0;

        $parametrosAEnviar['cantidadATomar']     = $totalMuestraPorDeciles;

        $textoADesplegar3 .= '<tr style="cursor: pointer" id="'.$parametrosAEnviar['tipoAtencion'].'/'.$parametrosAEnviar['tipoCategorizacion'].'" class="resumenTiemposEsperaDeciles">';

        $textoADesplegar3 .= '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$categorizaciones[$i].'</td>';

        for ( $k = 0; $k < 10; $k++ ) {

            $textoADesplegar3 .= desplegarFilaDetalleResumenTiemposEsperaDeciles($objCon, $objReporte, $parametrosAEnviar);

            $parametrosAEnviar['desdeDondeTomar'] += $parametrosAEnviar['cantidadATomar'];

        }

        $textoADesplegar3 .= '</tr>';

    }

    unset($parametrosAEnviar);    

    return $textoADesplegar3;

}



function desplegarFilaDetalleResumenTiemposEsperaDeciles ( $objCon, $objReporte, $parametrosAEnviar ) {

    $textoADesplegar3       = '';

    $tiempoPromedioDeciles = $objReporte->obtenerTiempoPromedioDeciles($objCon, $parametrosAEnviar);

    $textoADesplegar3       = '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.desplegarNumero($tiempoPromedioDeciles['tiempoPromedio']).'</td>';  

    return $textoADesplegar3;
    
}



function desplegarDetalleResumenTiemposEsperaDecilesNEA ( $objCon, $objReporte, $tipoAtencion, $parametros ) {

    $textoADesplegar3                      = '';

    $parametrosAEnviar[]                  = array();

    $parametrosAEnviar['fechaAnterior']   = $parametros['fechaAnterior'];

    $parametrosAEnviar['fechaActual']     = $parametros['fechaActual'];

    $parametrosAEnviar['tipoAtencion']    = $tipoAtencion;      

    $totalMuestras                        = $objReporte->obtenerTotalMuestrasNEA($objCon, $parametrosAEnviar);

    $totalMuestraPorDeciles               = round($totalMuestras['totalMuestras'] / 10);

    $parametrosAEnviar['desdeDondeTomar'] = 0;

    $parametrosAEnviar['cantidadATomar']  = $totalMuestraPorDeciles;

    $textoADesplegar3 .= '<tr style="cursor: pointer" id="'.$parametrosAEnviar['tipoAtencion'].'/NEA" class="resumenTiemposEsperaDeciles">';

    $textoADesplegar3 .= '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >NEA</td>';

    for ( $k = 0; $k < 10; $k++ ) {

        $textoADesplegar3 .= desplegarFilaDetalleResumenTiemposEsperaDecilesNEA($objCon, $objReporte, $parametrosAEnviar);

        $parametrosAEnviar['desdeDondeTomar'] += $parametrosAEnviar['cantidadATomar'];

    }

    $textoADesplegar3 .= '</tr>';

    unset($parametrosAEnviar);    

    return $textoADesplegar3;

}



function desplegarFilaDetalleResumenTiemposEsperaDecilesNEA ( $objCon, $objReporte, $parametrosAEnviar ) {

    $textoADesplegar3       = '';

    $tiempoPromedioDeciles = $objReporte->obtenerTiempoPromedioDecilesNEA($objCon, $parametrosAEnviar);

    $textoADesplegar3       = '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.desplegarNumero($tiempoPromedioDeciles['tiempoPromedio']).'</td>';  

    return $textoADesplegar3;
    
}
?>