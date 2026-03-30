<?php

$demandaUrgenciaAdultoPediatrica = $objReporte->obtenerDemandaUrgenciaAdultoPediatrica($objCon, $parametros);

$totales[]                       = array();

$totales['totalCierre']          = $demandaUrgenciaAdultoPediatrica['totalPacientesAdultoCierre'] + $demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoCierre'];

$totales['totalNEA']             = $demandaUrgenciaAdultoPediatrica['totalPacientesAdultoNEA'] + $demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoNEA'];

$totales['totalAnula']           = $demandaUrgenciaAdultoPediatrica['totalPacientesAdultoAnula'] + $demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoAnula'];

$totales['totalAdultos']         = $demandaUrgenciaAdultoPediatrica['totalPacientesAdultoCierre'] + $demandaUrgenciaAdultoPediatrica['totalPacientesAdultoNEA'] + $demandaUrgenciaAdultoPediatrica['totalPacientesAdultoAnula'];

$totales['totalPediatricos']     = $demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoCierre'] + $demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoNEA'] + $demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoAnula'];

$totales['total']                = $totales['totalAdultos'] + $totales['totalPediatricos'];

?>



<!-- 
################################################################################################################################################
                                                    DESPLIEGUE DEMANDA URGENCIA ADULTO Y PEDIÁTRICO
-->
<div class="row">
    <div class="container col-lg-12">
        <div class="table-responsive">
            <table id="tablaDemandaUrgenciaAdultoPediatrico" class="table table-striped table-bordered">
                <thead class="thead-dark">

                    <tr>

                        <th colspan="7" style="text-align: center;">DEMANDA URGENCIA ADULTO Y PEDIÁTRICA</th>   

                    </tr>

                    <tr>

                        <th width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"  >Descripción Tipo de Demanda</th>            

                        <th width="15%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Adulto</th>

                        <th width="15%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >% Adultos</th>

                        <th width="15%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Pediátricos</th>

                        <th width="15%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >% Pediátricos</th>

                        <th width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Todos</th>

                        <th width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >% Todos</th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    echo desplegarDemandaCerrados($demandaUrgenciaAdultoPediatrica, $totales);

                    echo desplegarDemandaNEA($demandaUrgenciaAdultoPediatrica, $totales);

                    echo desplegarDemandaAnula($demandaUrgenciaAdultoPediatrica, $totales);

                    echo desplegarDemandaTotales($totales);

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
function desplegarDemandaCerrados ( $demandaUrgenciaAdultoPediatrica, $totales ) {

    return '
    
        <tr style="cursor: pointer" id="cerrado" class="demandaUrgencia">

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"  >CERRADOS</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'        
            
                .desplegarNumero($demandaUrgenciaAdultoPediatrica['totalPacientesAdultoCierre']).

            '</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'

                .desplegarDivisionPorcentual($demandaUrgenciaAdultoPediatrica['totalPacientesAdultoCierre'], $totales['totalAdultos']).
            
            '%</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'
            
                .desplegarNumero($demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoCierre']).        
            
            '</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'
            
                .desplegarDivisionPorcentual($demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoCierre'], $totales['totalPediatricos']).
            
            '%</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'
            
                .desplegarNumero($totales['totalCierre']).
            
            '</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'
            
                .desplegarDivisionPorcentual($totales['totalCierre'], $totales['total']).
            
            '%</td>

        </tr>    
    
    ';

}



function desplegarDemandaNEA ( $demandaUrgenciaAdultoPediatrica, $totales ) {

    return '
    
        <tr style="cursor: pointer" id="nea" class="demandaUrgencia">

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"  >NEA</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'        
            
                .desplegarNumero($demandaUrgenciaAdultoPediatrica['totalPacientesAdultoNEA']).

            '</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'

                .desplegarDivisionPorcentual($demandaUrgenciaAdultoPediatrica['totalPacientesAdultoNEA'], $totales['totalAdultos']).
            
            '%</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'
            
                .desplegarNumero($demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoNEA']).        
            
            '</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'
            
                .desplegarDivisionPorcentual($demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoNEA'], $totales['totalPediatricos']).
            
            '%</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'
            
                .desplegarNumero($totales['totalNEA']).
            
            '</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'
            
                .desplegarDivisionPorcentual($totales['totalNEA'], $totales['total']).
            
            '%</td>

        </tr>    
    
    ';

}



function desplegarDemandaAnula ( $demandaUrgenciaAdultoPediatrica, $totales ) {

    return '
    
        <tr style="cursor: pointer" id="anula" class="demandaUrgencia">

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"  >ANULA</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'        
            
                .desplegarNumero($demandaUrgenciaAdultoPediatrica['totalPacientesAdultoAnula']).

            '</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'

                .desplegarDivisionPorcentual($demandaUrgenciaAdultoPediatrica['totalPacientesAdultoAnula'], $totales['totalAdultos']).
            
            '%</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'
            
                .desplegarNumero($demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoAnula']).        
            
            '</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'
            
                .desplegarDivisionPorcentual($demandaUrgenciaAdultoPediatrica['totalPacientesPediatricoAnula'], $totales['totalPediatricos']).
            
            '%</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'
            
                .desplegarNumero($totales['totalAnula']).
            
            '</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'
            
                .desplegarDivisionPorcentual($totales['totalAnula'], $totales['total']).
            
            '%</td>

        </tr>    
    
    ';

}



function desplegarDemandaTotales ( $totales ) {

    return '
    
        <tr>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"  >TOTALES</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'        
            
                .desplegarNumero($totales['totalAdultos']).

            '</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'

                .desplegarDivisionPorcentual($totales['totalAdultos'], $totales['total']).
            
            '%</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'
            
                .desplegarNumero($totales['totalPediatricos']).        
            
            '</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'
            
                .desplegarDivisionPorcentual($totales['totalPediatricos'], $totales['total']).
            
            '%</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'
            
                .desplegarNumero($totales['total']).
            
            '</td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'
            
                .desplegarDivisionPorcentual($totales['total'], $totales['total']).
            
            '%</td>

        </tr>    
    
    ';

}
?>