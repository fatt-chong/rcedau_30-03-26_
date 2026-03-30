<?php

$tipoDiagnostico = array('Z995', 'Z038');
 
?>



<!-- 
################################################################################################################################################
                                                    DESPLIEGUE RESUMEN DIAGNÓSTICOS INESPECÍFICOS
-->



<div class="row">
            <div class="container col-lg-12">
                <div class="table-responsive">
                    <table  id="tablaResumenDiagnosticosInespecificos" class="table table-striped table-bordered">
                        <thead class="thead-dark">

                <tr>

                    <th colspan="7" style="text-align: center;">RESUMEN DIAGNÓSTICOS INESPECÍFICOS</th>   

                </tr>

                <tr>

                    <th width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Tipo de Diagnóstico</th>            

                    <th width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >ADULTOS</th>

                    <th width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >% ADULTOS</th>

                    <th width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >PEDIÁTRICOS</th>

                    <th width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >% PEDIÁTRICOS</th>          

                </tr>

            </thead>

            <tbody>

                <?php

                echo desplegarDiagnosticosInespecificos($objCon, $objReporte, $tipoDiagnostico, $parametros);

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
function desplegarDiagnosticosInespecificos ( $objCon, $objReporte, $tipoDiagnostico, $parametros ) {

    $textoADesplegar                    = '';

    $totalTipoDiagnostico               = count($tipoDiagnostico);

    $parametrosAEnviar[]                = array();

    $parametrosAEnviar['fechaAnterior'] = $parametros['fechaAnterior'];

    $parametrosAEnviar['fechaActual']   = $parametros['fechaActual'];

    for ( $i = 0; $i < $totalTipoDiagnostico; $i++ ) {

        $parametrosAEnviar['tipoDiagnostico']   = $tipoDiagnostico[$i];

        $resumenDiagnosticosInespecificos       = $objReporte->obtenerResumenDiagnosticosInespecificos($objCon, $parametrosAEnviar);

        $textoADesplegar .= '

            <tr style="cursor: pointer;" id="'.$parametrosAEnviar['tipoDiagnostico'].'" class="diagnosticosInespecificos">

                <td width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'
                
                    .$tipoDiagnostico[$i].
                    
                '</td>            

                <td width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'
                
                    .desplegarNumero($resumenDiagnosticosInespecificos['totalAdultosDiagnostico']).
                    
                '</td> 

                <td width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'
                
                    .desplegarDivisionPorcentual($resumenDiagnosticosInespecificos['totalAdultosDiagnostico'], $resumenDiagnosticosInespecificos['totalAdultos']).
                    
                '%</td> 

                <td width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'
                
                    .desplegarNumero($resumenDiagnosticosInespecificos['totalPediatricosDiagnostico']).
                    
                '</td>          

                <td width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'
                
                    .desplegarDivisionPorcentual($resumenDiagnosticosInespecificos['totalPediatricosDiagnostico'], $resumenDiagnosticosInespecificos['totalPediatricos']).
                    
                '%</td>     

            </tr>        
        
        ';

    }

    unset($parametrosAEnviar);

    return $textoADesplegar;

}
?>