<?php

$seccionG      = $objReporte->reporteREM08SeccionG($objCon, $parametros);

$rangoEdades   = array(array(0, 9), array(10, 17), array(18, 19), array(20, 24), array(25, 34), array(35, 44), array(45, 54), array(55, 64), array(65, 74), array(75, 120));

$tipoAgresores = array('Pareja / Ex Pareja', 'Familiar', 'Conocido/a', 'Desconocido/a');

$tipoLesiones  = array('Traumatológicas', 'Odontológicas', 'Contusionales', 'Por Arma');

$tipoViolencias = array('VIF', 'Otras Violencias (no VIF)');

?>



<!--
################################################################################################################################################
                                                            DESPLIEGUE SECCIÓN G
-->
<div class="row">
    <div class="container col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" width="100%">
                <thead class="thead-dark">

                <tr>

                    <th colspan="34" style="text-align: center;">Sección G: Atenciones Médicas Asociadas a Violencia por Grupo Etario</th>

                </tr>

                <tr>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Concepto</th>

                    <th colspan="3" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Total</th>

                    <th colspan="20" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Rango Edades</th>

                </tr>

                <tr>

                    <th>&nbsp;</th>

                    <th>&nbsp;</th>

                    <th>&nbsp;</th>

                    <th>&nbsp;</th>

                    <?php

                    $limiteEdad = 75;

                    foreach ( $rangoEdades as $rangoEdad ) {

                        echo ( $rangoEdad[0] === $limiteEdad ) ? '<th colspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.$rangoEdad[0].' y más</th>' : '<th colspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.$rangoEdad[0].' - '.$rangoEdad[1].'</th>';

                    }

                    ?>

                </tr>

                <tr>

                    <th>&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Ambos</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Hombres</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Mujeres</th>

                    <?php

                    $limiteSecciones = 10;

                    for ( $i = 0; $i < $limiteSecciones; $i++ ) {

                        echo '<th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">H</th>';

                        echo '<th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">M</th>';

                    }

                    ?>

                </tr>

            </thead>

            <tbody>

            <?php

            $i = 0;

            foreach ( $tipoViolencias as $tipoViolencia ) {

                echo '<tr>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$tipoViolencia.'</td>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicador($seccionG, 'descripcionTipoViolencia', $tipoViolencia).'</td>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicadorYSexo($seccionG, 'descripcionTipoViolencia', $tipoViolencia, 'M').'</td>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicadorYSexo($seccionG, 'descripcionTipoViolencia', $tipoViolencia, 'F').'</td>';

                foreach ( $rangoEdades as $rangoEdad ) {

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicadorYSexoYEdad($seccionG, 'descripcionTipoViolencia', $tipoViolencia, 'M', $rangoEdad[0], $rangoEdad[1]).'</td>';

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicadorYSexoYEdad($seccionG, 'descripcionTipoViolencia', $tipoViolencia, 'F', $rangoEdad[0], $rangoEdad[1]).'</td>';

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



<!--
################################################################################################################################################
                                                            DESPLIEGUE SECCIÓN G.1
-->
<div class="row">
    <div class="container col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" width="100%">
                <thead class="thead-dark">

                <tr>

                    <th colspan="34" style="text-align: center;">Sección G.1: Atenciones Médicas Asociadas a Violencia por Condición (Incluidas en sección G)</th>

                </tr>

                <tr>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Concepto</th>

                    <th colspan="4" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Agresor/a</th>

                    <th colspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Total</th>

                    <th colspan="4" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Lesiones de la Víctima</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Sin Lesiones Constatables</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Diversidad Sexual</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Embarazadas</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Pueblos Originarios</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Migrantes</th>

                </tr>

                <tr>

                    <th>&nbsp;</th>

                    <?php

                    $totalTipoAgresores = count($tipoAgresores);

                    for ( $i = 0; $i < $totalTipoAgresores; $i++ ) {

                        echo '<th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.$tipoAgresores[$i].'</th>';

                    }

                    ?>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">H</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">M</th>

                    <?php

                    $totalTipoLesiones = count($tipoLesiones);

                    for ( $i = 0; $i < $totalTipoLesiones; $i++ ) {

                        echo '<th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.$tipoLesiones[$i].'</th>';

                    }

                    ?>

                    <th>&nbsp;</th>

                    <th>&nbsp;</th>

                    <th>&nbsp;</th>

                    <th>&nbsp;</th>

                    <th>&nbsp;</th>

                </tr>

            </thead>

            <tbody>

            <?php

            $i = 0;

            foreach ( $tipoViolencias as $tipoViolencia ) {

                echo '<tr>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$tipoViolencia.'</td>';

                foreach ( $tipoAgresores as $tipoAgresor ) {

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunTipoIndicadorYAgresor($seccionG, 'descripcionTipoViolencia', $tipoViolencia, $tipoAgresor).'</td>';

                }

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunTipoAgresorYSexo($seccionG, 'descripcionTipoViolencia', $tipoViolencia, 'H').'</td>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunTipoAgresorYSexo($seccionG, 'descripcionTipoViolencia', $tipoViolencia, 'F').'</td>';

                foreach ( $tipoLesiones as $tipoLesion ) {

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunTipoViolenciaYTipoLesion($seccionG, $tipoViolencia, $tipoLesion).'</td>';

                }

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunTipoViolenciaYTipoLesion($seccionG, $tipoViolencia, "Sin Lesiones Constatables").'</td>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">0</td>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunTipoViolenciaYEmbarazada($seccionG, $tipoViolencia, 'S').'</td>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunTipoViolenciaYPuebloOriginario($seccionG, $tipoViolencia, 'S').'</td>';

                echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunTipoViolenciaYMigrante($seccionG, $tipoViolencia, 'S').'</td>';

                echo '</tr>';

                $i++;

            }

            ?>

            </tbody>

        </table>
        </div>
    </div>

</div>