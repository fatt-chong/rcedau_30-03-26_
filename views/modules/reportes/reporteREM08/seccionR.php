<?php

$seccionR         = $objReporte->reporteREM08SeccionR($objCon, $parametros);

$rangoEdades      = array(array(0, 4), array(5, 9), array(10, 14), array(15, 120));

$tipoAnimales     = array('Perro', 'Gato', 'Animal Silvestre', 'Exposición a Murciélago', 'Roedor o Animal de Abasto');

$idAnimales       = array(1, 6, 10, 9, 2);

$idTipoMordeduras = array(1, 2);

?>



<!--
################################################################################################################################################
                                                            DESPLIEGUE SECCIÓN R
-->
<div class="row">
    <div class="container col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" width="100%">
                <thead class="thead-dark">

                <tr>

                    <th colspan="22" style="text-align: center;">Sección R: Atenciones por Mordeduras en Servicio de Urgencia de la Red</th>

                </tr>

                <tr>

                    <th style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Identificador del Animal Mordedor</th>

                    <th colspan="3" style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Total</th>

                    <th colspan="8" style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Rango Edades</th>

                    <th colspan="2" style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tipo Mordedura</th>

                </tr>

                <tr>

                    <th style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <?php

                    $limiteEdad = 15;

                    foreach ( $rangoEdades as $rangoEdad ) {

                        echo ( $rangoEdad[0] === $limiteEdad ) ? '<th colspan="2" style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.$rangoEdad[0].' y más</th>' : '<th colspan="2" style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.$rangoEdad[0].' - '.$rangoEdad[1].'</th>';

                    }

                    ?>

                    <th style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Única</th>

                    <th style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Múltiple</th>

                </tr>

                <tr>

                    <th style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Ambos</th>

                    <th style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Hombres</th>

                    <th style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Mujeres</th>

                    <?php

                    $limiteSecciones = 4;

                    for ( $i = 0; $i < $limiteSecciones; $i++ ) {

                        echo '<th style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">H</th>';

                        echo '<th style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">M</th>';

                    }

                    ?>

                    <th style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                </tr>

            </thead>

            <tbody>

                <?php

                $i = 0;

                foreach ( $idAnimales as $idAnimal) {

                    echo '<tr>';

                    echo '<td style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$tipoAnimales[$i].'</td>';

                    echo '<td style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicador($seccionR, 'animalMordedura', $idAnimal).'</td>';

                    echo '<td style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicadorYSexo($seccionR, 'animalMordedura', $idAnimal, 'M').'</td>';

                    echo '<td style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicadorYSexo($seccionR, 'animalMordedura', $idAnimal, 'F').'</td>';

                    foreach ( $rangoEdades as $rangoEdad ) {

                        echo '<td style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicadorYSexoYEdad($seccionR, 'animalMordedura', $idAnimal, 'M', $rangoEdad[0], $rangoEdad[1]).'</td>';

                        echo '<td style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicadorYSexoYEdad($seccionR, 'animalMordedura', $idAnimal, 'F', $rangoEdad[0], $rangoEdad[1]).'</td>';

                    }

                    foreach ( $idTipoMordeduras as $idTipoMordedura) {

                        echo '<td style="text-align:center; ;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunTipoMordedura($seccionR, 'animalMordedura', $idAnimal, $idTipoMordedura).'</td>';

                    }

                    echo '</tr>';

                    $i++;

                }

                ?>

            </tbody>

        </table>
        </div>

    </div>

</div>