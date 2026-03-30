<?php

$seccionO                   = $objReporte->reporteREM08SeccionO($objCon, $parametros);

$rangoEdades                = array(array(0, 4), array(5, 9), array(10, 14), array(15, 17), array(18, 24), array(25, 44), array(45, 64), array(65, 120));

$tituloViolacionesSexuales  = array(
                                    'Con Sospecha de Penetración Agudo (< 72 hrs)',
                                    'Con Sospecha de Penetración No agudo (> 72 hrs)',
                                    'Con Sospecha de Penetración Crónico',
                                    'Sin Sospecha de Penetración Agudo (< 72 hrs)',
                                    'Sin Sospecha de Penetración No agudo (> 72 hrs)',
                                    'Sin Sospecha de Penetración Crónico'
                                    );

$tipoPenetraciones          = array('Si - Aguda', 'Si - No Aguda', 'Si - Crónica', 'No - Aguda', 'No - No Aguda', 'No - Crónica');

$tipoAgresores              = array('Pareja / Ex Pareja', 'Familiar', 'Conocido/a', 'Desconocido/a');

?>



<!--
################################################################################################################################################
                                                            DESPLIEGUE SECCIÓN O
-->
<div class="row">
    <div class="container col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" width="100%">
                <thead class="thead-dark">

                <tr>

                    <th colspan="31" style="text-align: center;">Sección O: Atenciones en Urgencias por Violencia Sexual</th>

                </tr>

                <tr>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Concepto</th>

                    <th colspan="3" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Total</th>

                    <th colspan="16" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Rango Edades</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Gestantes</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Con Anticoncepción</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Sin Anticoncepción</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Con Profilaxis VIH</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Con Profilaxis ITS</th>

                    <th colspan="4" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Victimario/a</th>

                    <th colspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Total</th>

                </tr>

                <tr>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <?php

                    $limiteEdad = 65;

                    foreach ( $rangoEdades as $rangoEdad ) {

                        echo ( $rangoEdad[0] === $limiteEdad ) ? '<th colspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.$rangoEdad[0].' y más</th>' : '<th colspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.$rangoEdad[0].' - '.$rangoEdad[1].'</th>';

                    }

                    ?>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <?php

                    foreach ( $tipoAgresores as $tipoAgresor ) {

                        echo '<th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.$tipoAgresor.'</th>';

                    }

                    ?>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Hombre</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Mujer</th>

                </tr>

                <tr>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Ambos</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Hombres</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Mujeres</th>

                    <?php

                    $limiteSecciones = 8;

                    for ( $i = 0; $i < $limiteSecciones; $i++ ) {

                        echo '<th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">H</th>';

                        echo '<th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">M</th>';

                    }

                    ?>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                </tr>

            </thead>

            <tbody>

                <?php

                $i = 0;

                foreach ( $tipoPenetraciones as $tipoPenetracion ) {

                    echo '<tr>';

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$tituloViolacionesSexuales[$i].'</td>';

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">0</td>';

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicadorYSexo($seccionO, 'tipoPenetracion', $tipoPenetracion, 'M').'</td>';

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicadorYSexo($seccionO, 'tipoPenetracion', $tipoPenetracion, 'F').'</td>';

                    foreach ( $rangoEdades as $rangoEdad ) {

                        echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicadorYSexoYEdad($seccionO, 'tipoPenetracion', $tipoPenetracion, 'M', $rangoEdad[0], $rangoEdad[1]).'</td>';

                        echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicadorYSexoYEdad($seccionO, 'tipoPenetracion', $tipoPenetracion, 'F', $rangoEdad[0], $rangoEdad[1]).'</td>';

                    }

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunTipoPenetracionYCondicion($seccionO, 'tipoPenetracion', $tipoPenetracion, 'gestantes').'</td>';

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunTipoPenetracionYCondicion($seccionO, 'tipoPenetracion', $tipoPenetracion, 'conAnticoncepcion').'</td>';

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunTipoPenetracionYCondicion($seccionO, 'tipoPenetracion', $tipoPenetracion, 'sinAnticoncepcion').'</td>';

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunTipoPenetracionYCondicion($seccionO, 'tipoPenetracion', $tipoPenetracion, 'conProfilaxiaVIH').'</td>';

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunTipoPenetracionYCondicion($seccionO, 'tipoPenetracion', $tipoPenetracion, 'conProfilaxiaITS').'</td>';

                    foreach ( $tipoAgresores as $tipoAgresor ) {

                        echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunTipoIndicadorYAgresor($seccionO, 'tipoPenetracion', $tipoPenetracion, $tipoAgresor).'</td>';

                    }

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunTipoAgresorYSexo($seccionO, 'tipoPenetracion', $tipoPenetracion, 'H').'</td>';

                    echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunTipoAgresorYSexo($seccionO, 'tipoPenetracion', $tipoPenetracion, 'F').'</td>';

                    echo '</tr>';

                    $i++;

                }

                ?>

            </tbody>

        </table>
        </div>

    </div>

</div>