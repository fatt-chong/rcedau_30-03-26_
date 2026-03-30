<?php

$categorizaciones = array('', 'ESI-1', 'ESI-2', 'ESI-3', 'ESI-4', 'ESI-5');

$totalCategorizaciones = count($categorizaciones);

?>

<!-- 
################################################################################################################################################
                                                       	DESPLIEGUE NÚMERO DE HOSPITALIZACIONES URGENCIA
-->
<style type="text/css">
    .table-bordered-tbody {
    border: 1px solid #000; /* Borde negro de 1px */
}
.table-bordered-tbody td {
    border: 1px solid #000; /* Borde interno para cada celda */
}
</style>
<div  class="col-lg-1">&nbsp;</div>

<div  class="col-lg-10">

    <table id="tablaPacientesEspera" class="table table-striped table-bordered   table-hover table-condensed tablasHisto">

        <thead class="table-primary">

            <th width="40%" style="text-align:center; vertical-align:middle;" class=" font-weight-bold  mifuente11">Tipo Categorización de Paciente en Espera</th>

            <th width="30%" style="text-align:center; vertical-align:middle;" class=" font-weight-bold  mifuente11" >Pacientes Adultos

                <table width="100%" class="table-borderless">

                    <thead class="table-primary">

                        <th width="20%" style="text-align:center; vertical-align:middle;" class=" font-weight-bold  mifuente11">DAU</th>

                        <th width="45%" style="text-align:center; vertical-align:middle;" class=" font-weight-bold  mifuente11">Nombre</th>

                        <th width="13%" style="text-align:center; vertical-align:middle;" class=" font-weight-bold  mifuente11">Edad</th>

                        <th width="22%" style="text-align:center; vertical-align:middle;" class=" font-weight-bold  mifuente11">T. Espera</th>

                    </thead>

                </table>

            </th>

            <th width="30%" style="text-align:center; vertical-align:middle;" class=" font-weight-bold  mifuente11" >Pacientes Pediátricos

                <table width="100%" class="table-borderless">

                    <thead>

                        <th width="20%" style="text-align:center; vertical-align:middle;" class=" font-weight-bold  mifuente11">DAU</th>

                        <th width="50%" style="text-align:center; vertical-align:middle;" class=" font-weight-bold  mifuente11">Nombre</th>

                        <th width="15%" style="text-align:center; vertical-align:middle;" class=" font-weight-bold  mifuente11">Edad</th>

                        <th width="15%" style="text-align:center; vertical-align:middle;" class=" font-weight-bold  mifuente11">T. Espera</th>

                    </thead>

                </table>

            </th>

        </thead>

        <tbody class="table-border">

            <?php            

            for ( $i = 0; $i < $totalCategorizaciones; $i++ ) {

                $pacientesAdultoEspera = $objTurno->obtenerPacientesEnEsperaAtencion($objCon, $categorizaciones[$i], 'adulto');

                $pacientesPediatricoEspera = $objTurno->obtenerPacientesEnEsperaAtencion($objCon, $categorizaciones[$i], 'pediatrico');

                $tituloCategorizacion = 'Categoría '.$categorizaciones[$i];

                if ( $categorizaciones[$i] == '' ) {

                    $tituloCategorizacion = 'Sin Categorizar';

                }

                ?>

                <tr>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $tituloCategorizacion; ?> (Adultos: <?php echo count($pacientesAdultoEspera); ?> - Pediátricos: <?php echo count($pacientesPediatricoEspera)?>)</td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >

                        <table width="100%">

                            <tbody>

                                <?php

                                echo desplegarPacientesEsperaAtencion($pacientesAdultoEspera, $objUtil);

                                ?>

                            </tbody>

                        </table>
                    
                    </td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >

                        <table width="100%">

                            <tbody>

                                <?php

                                echo desplegarPacientesEsperaAtencion($pacientesPediatricoEspera, $objUtil);

                                ?>

                            </tbody>

                        </table>
                    
                    </td>
                            
                </tr>

            <?php

            }

            ?>

        </tbody>

    </table>


</div>

<div  class="col-lg-1">&nbsp;</div>



<!-- 
################################################################################################################################################
                                                       	            FUNCIONES PHP
-->
<?php
function desplegarPacientesEsperaAtencion ( $pacienteEsperaAtencion, $objUtil ) {

    $textoADesplegar = '';

    $totalPacienteEsperaAtencion = count($pacienteEsperaAtencion);

    for ( $i = 0; $i < $totalPacienteEsperaAtencion; $i++ ) {

        $tiempoEsperaPaciente = tiempoEsperapaciente($pacienteEsperaAtencion[$i]);       

        $textoADesplegar .= '

            <tr>

                <td width="20%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.$pacienteEsperaAtencion[$i]['dau_id'].'</td>

                <td width="50%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.$pacienteEsperaAtencion[$i]['nombrePaciente'].'</td>

                <td width="15%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.$objUtil->edadActual($pacienteEsperaAtencion[$i]['fechanac']).'</td>

                <td width="15%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.$tiempoEsperaPaciente.'</td>

            </tr>

            ';

    }

    return $textoADesplegar;

}



function tiempoEsperapaciente ( $pacienteEsperaAtencion ) {

    return ( empty($pacienteEsperaAtencion['tiempoEsperaConCategorizacion']) && is_null($pacienteEsperaAtencion['tiempoEsperaConCategorizacion']) ) ? $pacienteEsperaAtencion['tiempoEsperaSinCategorizacion'] : $pacienteEsperaAtencion['tiempoEsperaConCategorizacion'];

}
?>



<!-- 
################################################################################################################################################
                                                       	            CIERRE CONEXIÓN
-->
<?php

$objCon = NULL;

?>