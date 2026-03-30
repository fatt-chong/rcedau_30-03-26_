<?php

$seccionFFallecidoProcesoAtencion = $objReporte->reporteREM08SeccionFFallecidoProcesoAtencion($objCon, $parametros);

$seccionFFallecidoEsperaCama      = $objReporte->reporteREM08SeccionFFallecidoEsperaCama($objCon, $parametros);

?>



<!--
################################################################################################################################################
                                                            DESPLIEGUE SECCIÓN F
-->
<div class="row">
    <div class="container col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" width="100%">
                <thead class="thead-dark">

                <tr>

                    <th colspan="39" style="text-align: center;">Sección F: Pacientes Fallecidos en UEH (Establecimientos Alta Complejidad)</th>

                </tr>

                <tr>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Tipo Pacientes</th>

                    <th colspan="3" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Total</th>

                    <th colspan="34" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Rango Edades</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Beneficiarios</th>

                </tr>

                <tr>

                    <th>&nbsp;</th>

                    <th>&nbsp;</th>

                    <th>&nbsp;</th>

                    <th>&nbsp;</th>

                    <?php

                    $limiteEdad = 80;

                    for ( $i = 0, $j = 4; $i <= $limiteEdad; $i += 5, $j += 5 ) {

                        echo ( $i === $limiteEdad ) ? '<th colspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$i.' y más</th>' : '<th colspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$i.' - '.$j.'</th>';

                    }

                    ?>

                    <th>&nbsp;</th>

                </tr>

                <tr>

                    <th>&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Ambos</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Hombres</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Mujeres</th>

                    <?php

                    $limiteSecciones = 17;

                    for ( $i = 0; $i < $limiteSecciones; $i++ ) {

                        echo '<th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >H</th>';

                        echo '<th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >M</th>';

                    }

                    ?>

                    <th>&nbsp;</th>

                </tr>

            </thead>

            <tbody>

                <?php

                echo '<tr>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Fallecidos en Espera de Atención Médica</td>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">0</td>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">0</td>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">0</td>';

                for ( $edadMenor = 0, $edadMayor = 4, $j = 0; $j < $limiteSecciones; $j++, $edadMenor += 5, $edadMayor += 5 ) {

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >0</td>';

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >0</td>';

                }

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">0</td>';

                echo '</tr>';

                echo '<tr>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Fallecidos en Proceso de Atención</td>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.count($seccionFFallecidoProcesoAtencion).'</td>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesPorSexo($seccionFFallecidoProcesoAtencion, 'M').'</td>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesPorSexo($seccionFFallecidoProcesoAtencion, 'F').'</td>';

                for ( $edadMenor = 0, $edadMayor = 4, $j = 0; $j < $limiteSecciones; $j++, $edadMenor += 5, $edadMayor += 5 ) {

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesPorSexoYEdad($seccionFFallecidoProcesoAtencion, 'M', $edadMenor, $edadMayor).'</td>';

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesPorSexoYEdad($seccionFFallecidoProcesoAtencion, 'F', $edadMenor, $edadMayor).'</td>';

                }

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesPorPrevision($seccionFFallecidoProcesoAtencion).'</td>';

                echo '</tr>';

                echo '<tr>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Fallecidos en Espera de Cama Hospitalaria</td>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.count($seccionFFallecidoEsperaCama).'</td>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesPorSexo($seccionFFallecidoEsperaCama, 'M').'</td>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesPorSexo($seccionFFallecidoEsperaCama, 'F').'</td>';

                for ( $edadMenor = 0, $edadMayor = 4, $j = 0; $j < $limiteSecciones; $j++, $edadMenor += 5, $edadMayor += 5 ) {

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesPorSexoYEdad($seccionFFallecidoEsperaCama, 'M', $edadMenor, $edadMayor).'</td>';

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesPorSexoYEdad($seccionFFallecidoEsperaCama, 'F', $edadMenor, $edadMayor).'</td>';

                }

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesPorPrevision($seccionFFallecidoEsperaCama).'</td>';

                echo '</tr>';

                ?>

            </tbody>

        </table>
        
        </div>

    </div>

</div>