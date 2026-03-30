<!-- 
################################################################################################################################################
                                                    DESPLIEGUE RESUMEN TIEMPOS LABORATORIO
-->


<div class="row">
    <div class="container col-lg-12">
        <div class="table-responsive">
            <table id="tablaResumenTiemposIndicacionesLaboratorio" class="table table-striped table-bordered">
                <thead class="thead-dark">

                <tr>

                    <th colspan="11" style="text-align: center;">RESUMEN TIEMPOS INDICACIONES LABORATORIO</th>   

                </tr>

                <tr>                    

                    <th rowspan="2" width="5%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">DAU's</th>            

                    <th rowspan="2" width="5%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Indicaciones</th> 

                    <th colspan="3" width="30%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempos desde: Indicación a Toma Muestra</th>            

                    <th colspan="3" width="30%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempos desde: Toma de Muestra a Recepción</th> 

                    <th colspan="3" width="30%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempos desde: Recepción a Realización</th>       

                </tr> 

                <tr>

                    <th width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Promedio</th>            

                    <th width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Máximo</th> 

                    <th width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Mímimo</th>   

                    <th width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Promedio</th>            

                    <th width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Máximo</th> 

                    <th width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Mímimo</th>   

                    <th width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Promedio</th>            

                    <th width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Máximo</th> 

                    <th width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Mímimo</th>   

                </tr> 

            </thead>

            <tbody>                             

                <?php

                echo desplegarResumenTiemposLaboratorio($objCon, $objReporte, $parametros);

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
function desplegarResumenTiemposLaboratorio ( $objCon, $objReporte, $parametros ) {

    $objReporte->crearTablaTemporalTiemposLaboratorio($objCon, $parametros);

    $totalDAUTiemposLaboratorio = $objReporte->obtenerTotalDAUTiemposLaboratorio($objCon);

    $resumenTiemposLaboratorio = $objReporte->obtenerResumenTiemposLaboratorio($objCon);

    return '

        <tr style="cursor: pointer;" class="tiemposLaboratorio">

            <td width="5%" sstyle="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$totalDAUTiemposLaboratorio.'</td>

            <td width="5%" sstyle="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.desplegarNumero($resumenTiemposLaboratorio['cantidadIndicaciones']).'</td>   

            <td width="10%" sstyle="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.desplegarNumero($resumenTiemposLaboratorio['tiempoPromedioInsertaTomaMuestra']).'</td>

            <td width="10%" sstyle="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.desplegarNumero($resumenTiemposLaboratorio['tiempoMaximoInsertaTomaMuestra']).'</td>

            <td width="10%" sstyle="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.desplegarNumero($resumenTiemposLaboratorio['tiempoMinimoInsertaTomaMuestra']).'</td>

            <td width="10%" sstyle="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.desplegarNumero($resumenTiemposLaboratorio['tiempoPromedioTomaMuestraRecepcion']).'</td>

            <td width="10%" sstyle="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.desplegarNumero($resumenTiemposLaboratorio['tiempoMaximoTomaMuestraRecepcion']).'</td>

            <td width="10%" sstyle="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.desplegarNumero($resumenTiemposLaboratorio['tiempoMinimoTomaMuestraRecepcion']).'</td>

            <td width="10%" sstyle="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.desplegarNumero($resumenTiemposLaboratorio['tiempoPromedioRecepcionRealizacion']).'</td>

            <td width="10%" sstyle="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.desplegarNumero($resumenTiemposLaboratorio['tiempoMaximoRecepcionRealizacion']).'</td>

            <td width="10%" sstyle="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.desplegarNumero($resumenTiemposLaboratorio['tiempoMinimoRecepcionRealizacion']).'</td>

        </tr>            
     
    ';

}
?>