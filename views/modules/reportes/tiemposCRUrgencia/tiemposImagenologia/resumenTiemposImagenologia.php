<!--
################################################################################################################################################
                                                    DESPLIEGUE RESUMEN TIEMPOS IMAGENOLOGÍA
-->


<div class="row">
    <div class="container col-lg-12">
        <div class="table-responsive">
            <table id="tablaResumenTiemposIndicacionesImagenologia" class="table table-striped table-bordered">
                <thead class="thead-dark">

                <tr>

                    <th colspan="11" style="text-align: center;">RESUMEN TIEMPOS INDICACIONES IMAGENOLOGÍA</th>

                </tr>

                <tr>

                    <th rowspan="2" width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tipo Exámen</th>

                    <th rowspan="2" width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">DAU's</th>

                    <th rowspan="2" width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Indicaciones</th>

                    <th colspan="3" width="60%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempos desde: Indicación a Aplicación</th>

                </tr>

                <tr>

                    <th width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Promedio</th>

                    <th width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Máximo</th>

                    <th width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Mímimo</th>

                </tr>

            </thead>

            <tbody>

                <?php

                echo desplegarResumenTiemposImagenologia($objCon, $objReporte, $parametros);

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
function desplegarResumenTiemposImagenologia ( $objCon, $objReporte, $parametros ) {

    $textoADesplegar = '';

    $objReporte->crearTablaTemporalTiemposImagenologia($objCon, $parametros);

    $resumenTiemposImagenologia = $objReporte->obtenerResumenTiemposImagenologia($objCon);

    $totalResumenTiemposImagenologia = count($resumenTiemposImagenologia);

    for ( $i = 0; $i < $totalResumenTiemposImagenologia; $i++ ) {

        $textoADesplegar .= '

            <tr style="cursor: pointer;" id="'.$resumenTiemposImagenologia[$i]['tipoExamen'].'" class="tiemposImagenologia">

                <td width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$resumenTiemposImagenologia[$i]['tipoExamen'].'</td>

                <td width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$objReporte->obtenerTotalDAUTiemposImagenologia($objCon, $resumenTiemposImagenologia[$i]['tipoExamen']).'</td>

                <td width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.desplegarNumero($resumenTiemposImagenologia[$i]['cantidadIndicaciones']).'</td>

                <td width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.desplegarNumero($resumenTiemposImagenologia[$i]['tiempoPromedioInsertaAplica']).'</td>

                <td width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.desplegarNumero($resumenTiemposImagenologia[$i]['tiempoMaximoInsertaAplica']).'</td>

                <td width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.desplegarNumero($resumenTiemposImagenologia[$i]['tiempoMinimoInsertaAplica']).'</td>

            </tr>

        ';

    }

    return $textoADesplegar;

}
function desplegarNumero ( $numero ) {

    return ( empty($numero) || is_null($numero) ) ? 0 : $numero;

}
?>
