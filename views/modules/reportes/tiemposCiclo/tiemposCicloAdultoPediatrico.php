<?php

$tituloPrincipal         = 'RESUMEN TIEMPOS DE CICLO ADULTOS';

$subTitulo               = 'Tiempo desde Admisión a Cierre Dau Definitivo';

$subTituloTodos          = 'TODOS LOS ADULTOS';

$subTituloHospitalizados = 'ADULTOS HOSPITALIZADOS';

$subTituloAlta           = 'ADULTOS DE ALTA';

if ( $parametros['tipoAtencion'] == 2 ) {

    $tituloPrincipal         = 'RESUMEN TIEMPOS DE CICLO PEDIÁTRICOS';

    $subTitulo               = 'Tiempo desde Admisión a Cierre Dau Definitivo';

    $subTituloTodos          = 'TODOS LOS PEDIÁTRICOS';

    $subTituloHospitalizados = 'PEDIÁTRICOS HOSPITALIZADOS';

    $subTituloAlta           = 'PEDIÁTRICOS DE ALTA';

}

?>

<!-- 
################################################################################################################################################
                                                DESPLIEGUE RESUMEN TIEMPOS DE CICLO ADULTO Y PEDIÁTRICO
-->
<div class="row">
    <div class="container col-lg-12">
        <div class="table-responsive">
            <table id="tablaResumenTiemposCicloAdultoPediatrico-<?php echo $parametros['tipoAtencion']; ?>" class="table table-striped table-bordered">
                <thead class="thead-dark">

                    <tr>

                        <th colspan="13" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $tituloPrincipal; ?></th>   

                    </tr>

                    <tr>

                        <th colspan="13" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $subTitulo; ?></th>   

                    </tr>

                    <tr>

                        <th width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tipo Categorización</th>            

                        <th width="30%" colspan="4" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $subTituloTodos; ?></th>

                        <th width="30%" colspan="4" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $subTituloHospitalizados; ?></th>

                        <th width="30%" colspan="4" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $subTituloAlta; ?></th>

                    </tr>

                    <tr>

                        <th width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">&nbsp;</th>

                        <th width="7%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Nº Atenciones</th>

                        <th width="7%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Promedio</th>

                        <th width="7%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Minimo</th>

                        <th width="7%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Máximo</th>

                        <th width="7%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Nº Hospitalizados</th>

                        <th width="7%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Promedio</th>

                        <th width="7%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Minimo</th>

                        <th width="7%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Máximo</th>

                        <th width="7%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Nº Alta</th>

                        <th width="7%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Promedio</th>

                        <th width="7%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Minimo</th>

                        <th width="6%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tiempo Máximo</th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    $textoADesplegar                    = '';

                    $hospitalizados                     = 4;

                    $alta                               = 3;    

                    $totalCategorizaciones  = count($categorizaciones);

                    for ( $i = 0; $i < $totalCategorizaciones; $i++ ) {

                        $parametrosAEnviar                           = array();

                        $parametrosAEnviar['tipoAtencion']           = $parametros['tipoAtencion'];

                        $textoADesplegar                            .= '<tr>';

                        $parametrosAEnviar['tipoCategorizacion']    = $categorizaciones[$i];

                        $todosLosAdultos                            = $objReporte->obtenerTiemposCiclo($objCon, $parametrosAEnviar);        

                        $textoADesplegar                            .= '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.$categorizaciones[$i].'</td>';

                        $textoADesplegar                            .= '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.desplegarNumero($todosLosAdultos['totalAtencion']).'</td>';

                        $textoADesplegar                            .= '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.desplegarNumero($todosLosAdultos['tiempoPromedioEnBox']).'</td>';

                        $textoADesplegar                            .= '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.desplegarNumero($todosLosAdultos['tiempoMinimoEnBox']).'</td>';

                        $textoADesplegar                            .= '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.desplegarNumero($todosLosAdultos['tiempoMaximoEnBox']).'</td>';    

                        $parametrosAEnviar['tipoEgreso']             = $hospitalizados;

                        $adultosHospitalizados                       = $objReporte->obtenerTiemposCiclo($objCon, $parametrosAEnviar);   

                        $textoADesplegar                            .= '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.desplegarNumero($adultosHospitalizados['totalAtencion']).'</td>';

                        $textoADesplegar                            .= '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.desplegarNumero($adultosHospitalizados['tiempoPromedioEnBox']).'</td>';

                        $textoADesplegar                            .= '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.desplegarNumero($adultosHospitalizados['tiempoMinimoEnBox']).'</td>';

                        $textoADesplegar                            .= '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.desplegarNumero($adultosHospitalizados['tiempoMaximoEnBox']).'</td>';

                        $parametrosAEnviar['tipoEgreso']             = $alta;

                        $adultosAlta                                 = $objReporte->obtenerTiemposCiclo($objCon, $parametrosAEnviar);   

                        $textoADesplegar                            .= '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.desplegarNumero($adultosAlta['totalAtencion']).'</td>';

                        $textoADesplegar                            .= '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.desplegarNumero($adultosAlta['tiempoPromedioEnBox']).'</td>';

                        $textoADesplegar                            .= '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.desplegarNumero($adultosAlta['tiempoMinimoEnBox']).'</td>';

                        $textoADesplegar                            .= '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.desplegarNumero($adultosAlta['tiempoMaximoEnBox']).'</td>';

                        $textoADesplegar                            .= '</tr>';

                        unset($parametrosAEnviar);

                    }

                    echo $textoADesplegar;

                    ?>                

                </tbody>

            </table>
        </div>

    </div>

    <div  class="col-lg-1">&nbsp;</div>

</div>