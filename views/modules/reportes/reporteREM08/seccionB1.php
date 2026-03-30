<?php

$seccionB1         = $objReporte->reporteREM08SeccionB1($objCon, $parametros);

$categorizaciones = array('C1', 'C2', 'C3', 'C4', 'C5');

?>



<!--
################################################################################################################################################
                                                            DESPLIEGUE SECCIÓN B1
-->
<div class="row">
    <div class="container col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">

                <tr>

                    <th colspan="38" style="text-align: center;">Sección B.1: Categorización de Pacientes De Urgencia Gineco Obstétrica, Previa a la Atención Clínica por Matrón(a) y/o Médico Gineco-Obstetra (Establecimientos alta, mediana y baja complejidad)</th>

                </tr>


<!-- <div class="row">

    <div  class="col-lg-12">

        <table id="tablaSeccionB" class="table table-striped table-bordered table-hover table-condensed tablasHisto" style="margin-left:2%; width:95%">

            <thead>

                <tr>

                    <th colspan="38" style="text-align: center;">Sección B.1: Categorización de Pacientes De Urgencia Gineco Obstétrica, Previa a la Atención Clínica por Matrón(a) y/o Médico Gineco-Obstetra (Establecimientos alta, mediana y baja complejidad)</th>

                </tr> -->

                <tr>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Categorías</th>

                    <th colspan="3" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Total</th>

                    <th colspan="34" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Rango Edades</th>

                </tr>

                <tr>

                    <th>&nbsp;</th>

                    <th>&nbsp;</th>

                    <th>&nbsp;</th>

                    <th>&nbsp;</th>

                    <?php

                    $limiteEdad = 80;

                    for ( $i = 0, $j = 4; $i <= $limiteEdad; $i += 5, $j += 5 ) {

                        echo ( $i === $limiteEdad ) ? '<th colspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.$i.' y más</th>' : '<th colspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.$i.' - '.$j.'</th>';

                    }

                    ?>

                </tr>

                <tr>

                    <th>&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Ambos</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Hombres</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Mujeres</th>

                    <?php

                    $limiteSecciones = 17;

                    for ( $i = 0; $i < $limiteSecciones; $i++ ) {

                        echo '<th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">H</th>';

                        echo '<th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">M</th>';

                    }

                    ?>

                </tr>

            </thead>

            <tbody>

                <?php

                $totalCategorizaciones = count($categorizaciones);

                $limiteSecciones = 17;

                foreach ( $categorizaciones as $categorizacion ) {

                    echo '<tr>';

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$categorizacion.'</td>';

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicador($seccionB1, 'categorizacionPaciente', $categorizacion).'</td>';

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicadorYSexo($seccionB1, 'categorizacionPaciente', $categorizacion, 'M').'</td>';

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicadorYSexo($seccionB1, 'categorizacionPaciente', $categorizacion, 'F').'</td>';

                    for ( $edadMenor = 0, $edadMayor = 4, $j = 0; $j < $limiteSecciones; $j++, $edadMenor += 5, $edadMayor += 5 ) {

                        echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicadorYSexoYEdad($seccionB1, 'categorizacionPaciente', $categorizacion, 'M', $edadMenor, $edadMayor).'</td>';

                        echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicadorYSexoYEdad($seccionB1, 'categorizacionPaciente', $categorizacion, 'F', $edadMenor, $edadMayor).'</td>';

                    }

                    echo '</tr>';

                }

                echo '<tr>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >TOTAL</td>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.count($seccionB1).'</td>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesPorSexo($seccionB1, 'M').'</td>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesPorSexo($seccionB1, 'F').'</td>';

                for ( $edadMenor = 0, $edadMayor = 4, $i = 0; $i < $limiteSecciones; $i++, $edadMenor += 5, $edadMayor += 5 ) {

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesPorSexoYEdad($seccionB1, 'M', $edadMenor, $edadMayor).'</td>';

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesPorSexoYEdad($seccionB1, 'F', $edadMenor, $edadMayor).'</td>';

                }

                echo '</tr>';

                ?>

            </tbody>

        </table>
            </div>
    </div>

</div>
