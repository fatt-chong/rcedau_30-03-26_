<?php

$seccionQ    = $objReporte->reporteREM08SeccionQ($objCon, $parametros);

$rangoEdades = array(array(0, 9), array(10, 19), array(20, 24), array(25, 44), array(45, 54), array(55, 64), array(65, 74), array(75, 84), array(85, 120));

?>



<!--
################################################################################################################################################
                                                            DESPLIEGUE SECCIÓN Q
-->
<div class="row">
    <div class="container col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" width="100%">
                <thead class="thead-dark">

                <tr>

                    <th colspan="22" style="text-align: center;">Sección Q: Atenciones de Urgencia Asociadas a Lesiones Autoinfringidas</th>

                </tr>

                <tr>

                    <th style="text-align:center; width:15%;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Concepto</th>

                    <th colspan="3" style="text-align:center; width:15%;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Total</th>

                    <th colspan="18" style="text-align:center; " style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Rango Edades</th>

                </tr>

                <tr>

                    <th style="text-align:center; width:15%;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="text-align:center; width:15%;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="text-align:center; width:15%;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="text-align:center; width:15%;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <?php

                    $limiteEdad = 65;

                    foreach ( $rangoEdades as $rangoEdad ) {

                        echo ( $rangoEdad[0] === $limiteEdad ) ? '<th colspan="2" style="text-align:center; " style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$rangoEdad[0].' y más</th>' : '<th colspan="2" style="text-align:center; " style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$rangoEdad[0].' - '.$rangoEdad[1].'</th>';

                    }

                    ?>

                </tr>

                <tr>

                    <th style="text-align:center; width:15%;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</th>

                    <th style="text-align:center; width:15%;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Ambos</th>

                    <th style="text-align:center; width:15%;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Hombres</th>

                    <th style="text-align:center; width:15%;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Mujeres</th>

                    <?php

                    $limiteSecciones = 9;

                    for ( $i = 0; $i < $limiteSecciones; $i++ ) {

                        echo '<th style="text-align:center; " style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >H</th>';

                        echo '<th style="text-align:center; " style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >M</th>';

                    }

                    ?>

                </tr>

            </thead>

            <tbody>

                <td style="text-align:center; " style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Número de Atenciones</td>

                <td style="text-align:center; " style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo count($seccionQ); ?></td>

                <td style="text-align:center; " style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesPorSexo($seccionQ, 'M'); ?></td>

                <td style="text-align:center; " style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesPorSexo($seccionQ, 'F'); ?></td>

                <?php

                foreach ( $rangoEdades as $rangoEdad ) {

                    echo '<td style="text-align:center; " style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.filtrarPacientesPorSexoYEdad($seccionQ, 'H', $rangoEdad[0], $rangoEdad[1]).'</td>';

                    echo '<td style="text-align:center; " style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.filtrarPacientesPorSexoYEdad($seccionQ, 'F', $rangoEdad[0], $rangoEdad[1]).'</td>';

                }

                echo '</tr>';

                ?>

            </tbody>

        </table>

        </div>

    </div>

</div>