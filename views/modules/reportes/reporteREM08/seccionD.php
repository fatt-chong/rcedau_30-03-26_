<?php

$seccionDPacientesHospitalizados      = $objReporte->reporteREM08SeccionDPacientesHospitalizacion($objCon, $parametros);

$seccionDPacientesTipoHospitalizacion = $objReporte->reporteREM08SeccionDPacientesTipoHospitalizacion($objCon, $parametros);

$tiemposEspera                        = array('menos 12 horas', 'entre 12 y 24 horas', 'más 24 horas');

$tituloTipoHospitalizaciones          = array('Rechazan Hospitalización', 'Fuga', 'Derivados a Otro Establecimiento', 'Permanecen en UEH', 'Ingresan Directamente a Proceso Quirúrgico');

$tipoHospitalizaciones                = array('Rechazo Hospitalización', 'Fuga', 'Traslado', 'Alta Médica', 'Pabellón')

?>



<!--
################################################################################################################################################
                                                            DESPLIEGUE SECCIÓN D
-->
<div class="row">
    <div class="container col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" width="100%">
                <thead class="thead-dark">

                <tr>

                    <th colspan="40" style="text-align: center;">Sección D: Pacientes con Indicación de Hospitalización en Espera de Camas en UEH</th>

                </tr>

                <tr>

                    <th  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tipo Pacientes</th>

                    <th colspan="3"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Total</th>

                    <th colspan="34"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Rango Edades</th>

                    <th  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Benificiarios</th>

                    <th  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Hospitalización Domiciliaria</th>

                </tr>

                <tr>

                    <th>&nbsp;</th>

                    <th>&nbsp;</th>

                    <th>&nbsp;</th>

                    <th>&nbsp;</th>

                    <?php

                    $limiteEdad = 80;

                    for ( $i = 0, $j = 4; $i <= $limiteEdad; $i += 5, $j += 5 ) {

                        echo ( $i === $limiteEdad ) ? '<th colspan="2"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.$i.' y más</th>' : '<th colspan="2"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.$i.' - '.$j.'</th>';

                    }

                    ?>

                    <th>&nbsp;</th>

                    <th>&nbsp;</th>

                </tr>

                <tr>

                    <th>&nbsp;</th>

                    <th  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Ambos</th>

                    <th  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Hombres</th>

                    <th  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Mujeres</th>

                    <?php

                    $limiteSecciones = 17;

                    for ( $i = 0; $i < $limiteSecciones; $i++ ) {

                        echo '<th  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">H</th>';

                        echo '<th  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">M</th>';

                    }

                    ?>

                    <th>&nbsp;</th>

                    <th>&nbsp;</th>

                </tr>

            </thead>

            <tbody>

                <?php

                foreach ( $tiemposEspera as $tiempoEspera ) {

                    echo '<tr>';

                    echo '<td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Hospitalización '.$tiempoEspera.'</td>';

                    echo '<td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunTiempoHospitalizacion($seccionDPacientesHospitalizados, $tiempoEspera).'</td>';

                    echo '<td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunTiempoHospitalizacionYSexo($seccionDPacientesHospitalizados, $tiempoEspera, 'M').'</td>';

                    echo '<td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunTiempoHospitalizacionYSexo($seccionDPacientesHospitalizados, $tiempoEspera, 'F').'</td>';

                    for ( $edadMenor = 0, $edadMayor = 4, $j = 0; $j < $limiteSecciones; $j++, $edadMenor += 5, $edadMayor += 5 ) {

                        echo '<td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunTiempoHospitalizacionYSexoYEdad($seccionDPacientesHospitalizados, $tiempoEspera, 'M', $edadMenor, $edadMayor).'</td>';

                        echo '<td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunTiempoHospitalizacionYSexoYEdad($seccionDPacientesHospitalizados, $tiempoEspera, 'F', $edadMenor, $edadMayor).'</td>';

                    }

                    echo '<td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesPorPrevisionYTiempoHospitalizacion($seccionDPacientesHospitalizados, $tiempoEspera).'</td>';

                    echo '<td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesPorPrevisionTiempoHospitalizacionYHospitalizacionDomiciliaria($seccionDPacientesHospitalizados, $tiempoEspera, 'Hospitalización Domiciliaria').'</td>';

                    echo '</tr>';

                }

                $i = 0;

                foreach ( $tipoHospitalizaciones AS $tipoHospitalizacion ) {

                    echo '<tr>';

                    echo '<td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.$tituloTipoHospitalizaciones[$i].'</td>';

                    echo '<td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicador($seccionDPacientesTipoHospitalizacion, 'tipoHospitalizacion', $tipoHospitalizacion).'</td>';

                    echo '<td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicadorYSexo($seccionDPacientesTipoHospitalizacion, 'tipoHospitalizacion', $tipoHospitalizacion, 'M').'</td>';

                    echo '<td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicadorYSexo($seccionDPacientesTipoHospitalizacion, 'tipoHospitalizacion', $tipoHospitalizacion, 'F').'</td>';

                    for ( $edadMenor = 0, $edadMayor = 4, $j = 0; $j < $limiteSecciones; $j++, $edadMenor += 5, $edadMayor += 5 ) {

                        echo '<td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicadorYSexoYEdad($seccionDPacientesTipoHospitalizacion, 'tipoHospitalizacion', $tipoHospitalizacion, 'M', $edadMenor, $edadMayor).'</td>';

                        echo '<td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesSegunIndicadorYSexoYEdad($seccionDPacientesTipoHospitalizacion, 'tipoHospitalizacion', $tipoHospitalizacion, 'F', $edadMenor, $edadMayor).'</td>';

                    }

                    echo '<td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.filtrarPacientesPorPrevisionYTipoHospitalizacion($seccionDPacientesTipoHospitalizacion, $tipoHospitalizacion).'</td>';

                    echo '<td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">&nbsp;</td>';

                    echo '</tr>';

                    $i++;

                }

                echo '<tr>';

                ?>

            </tbody>

        </table>

    </div>
    </div>

</div>