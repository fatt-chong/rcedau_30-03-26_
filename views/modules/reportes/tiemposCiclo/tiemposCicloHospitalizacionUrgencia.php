<?php
if ( $parametros['tipoAtencion'] == $adulto && $parametros['tipoEgreso'] == $hospitalizado ) {

    if ( $parametros['tipoResumen'] == 'cierre' ) {

        $tituloPrincipal = 'TIEMPOS DE CICLO ADULTOS HOSPITALIZADOS';

        $subTitulo       = 'Tiempo desde Admisión a Cierre DAU definitivo en Adulto - Promedio por Deciles';

    } else if ( $parametros['tipoResumen'] == 'indicacionEgreso' ) {

        $tituloPrincipal = 'TIEMPO PROCESOS URGENCIA ADULTOS';

        $subTitulo       = 'Tiempo desde Admisión a Indicación de Egreso en Adultos Hospitalizados - Promedio por Deciles';

    }

    $i = 0;

}

if ( $parametros['tipoAtencion'] == $adulto && $parametros['tipoEgreso'] == $alta ) {

    if ( $parametros['tipoResumen'] == 'cierre' ) {

        $tituloPrincipal = 'TIEMPOS DE CICLO ADULTOS DE ALTA';

        $subTitulo       = 'Tiempo desde Admisión a Cierre DAU definitivo en Adultos de Alta - Promedio por Deciles';

    } else if ( $parametros['tipoResumen'] == 'indicacionEgreso' ) {

        $tituloPrincipal = 'TIEMPO PROCESOS URGENCIA ADULTOS';

        $subTitulo       = 'Tiempo desde Admisión a Indicación de Egreso en Adultos de Alta - Promedio por Deciles';

    }

    $i = 0;

}

if ( $parametros['tipoAtencion'] == $pediatrico && $parametros['tipoEgreso'] == $hospitalizado ) {

    if ( $parametros['tipoResumen'] == 'cierre' ) {

        $tituloPrincipal = 'TIEMPOS DE CICLO PEDIÁTRICO HOSPITALIZADOS';

        $subTitulo       = 'Tiempo desde Admisión a Cierre DAU definitivo en Pediátricos - Promedio por Deciles';

    } else if ( $parametros['tipoResumen'] == 'indicacionEgreso' ) {

        $tituloPrincipal = 'TIEMPO PROCESOS URGENCIA PEDIÁTRICO';

        $subTitulo       = 'Tiempo desde Admisión a Indicación de Egreso en Pediátricos Hospitalizados - Promedio por Deciles';

    }

    $i = $totalCategorizaciones - 1;

}

if ( $parametros['tipoAtencion'] == $pediatrico && $parametros['tipoEgreso'] == $alta ) {

    if ( $parametros['tipoResumen'] == 'cierre' ) {

        $tituloPrincipal = 'TIEMPOS DE CICLO PEDIÁTRICO ALTA';

        $subTitulo       = 'Tiempo desde Admisión a Cierre DAU definitivo en Pediátricos de Alta - Promedio por Deciles';

    } else if ( $parametros['tipoResumen'] == 'indicacionEgreso' ) {

        $tituloPrincipal = 'TIEMPO PROCESOS URGENCIA PEDIÁTRICO';

        $subTitulo       = 'Tiempo desde Admisión a Indicación de Egreso en Pediátricos - Promedio por Deciles';

    }

    $i = 0;

}
?>



<!--
################################################################################################################################################
                                                DESPLIEGUE RESUMEN TIEMPOS DE CICLO HOSPITALIZADOS Y URGENCIAS
-->
<div class="row">
    <div class="container col-lg-12">
        <div class="table-responsive">
            <table id="tablaResumenTiemposCicloAdultoHospitalizados-<?php echo $parametros['tipoAtencion'].'-'.$parametros['tipoEgreso'].'-'.$parametros['tipoResumen']; ?>"  class="table table-striped table-bordered">
                <thead class="thead-dark">

                    <tr>

                        <th colspan="12" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $tituloPrincipal; ?></th>

                    </tr>

                    <tr>

                        <th colspan="12" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"  ><?php echo $subTitulo; ?></th>

                    </tr>

                    <tr>

                        <th width="5%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"  >CAT</th>

                        <th width="5%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Total</th>

                        <th width="9%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >D1</th>

                        <th width="9%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >D2</th>

                        <th width="9%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >D3</th>

                        <th width="9%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >D4</th>

                        <th width="9%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >D5</th>

                        <th width="9%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >D6</th>

                        <th width="9%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >D7</th>

                        <th width="9%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >D8</th>

                        <th width="9%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >D9</th>

                        <th width="9%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >D10</th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    $textoADesplegar                      = '';

                    $parametrosAEnviar                    = array();

                    $parametrosAEnviar['fechaAnterior']   = $parametros['fechaAnterior'];

                    $parametrosAEnviar['fechaActual']     = $parametros['fechaActual'];

                    $parametrosAEnviar['tipoAtencion']    = $parametros['tipoAtencion'];

                    $parametrosAEnviar['tipoEgreso']      = $parametros['tipoEgreso'];

                    $parametrosAEnviar['tipoResumen']     = $parametros['tipoResumen'];

                    for ( ; $i < $totalCategorizaciones; $i++ ) {

                        $parametrosAEnviar['tipoCategorizacion'] = $categorizaciones[$i];

                        $totalMuestras                           = $objReporte->obtenerTotalMuestrasCicloHospitalizacionUrgencia($objCon, $parametrosAEnviar);

                        $totalMuestraPorDeciles                  = round($totalMuestras['totalMuestras'] / 10);

                        $parametrosAEnviar['desdeDondeTomar']    = 0;

                        $parametrosAEnviar['cantidadATomar']     = $totalMuestraPorDeciles;

                        $textoADesplegar                        .= '<tr>';

                        $textoADesplegar                        .= '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"  >'.$categorizaciones[$i].'</td>';

                        $textoADesplegar                        .= '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$totalMuestras['totalMuestras'].'</td>';

                        for ( $k = 0; $k < 10; $k++ ) {

                            $tiempoPromedioDeciles                  = $objReporte->obtenerTiempoPromedioDecilesHospitalizacionUrgencia($objCon, $parametrosAEnviar);

                            $textoADesplegar                       .= '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.desplegarNumero($tiempoPromedioDeciles['tiempoPromedio']).'</td>';

                            $parametrosAEnviar['desdeDondeTomar']  += $parametrosAEnviar['cantidadATomar'];

                        }

                        $textoADesplegar .= '</tr>';

                    }

                    unset($parametrosAEnviar);

                    echo $textoADesplegar;

                    ?>

                </tbody>

            </table>
        </div>
    </div>
</div>