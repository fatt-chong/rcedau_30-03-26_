<?php
// error_reporting(0);

$seccionA1AdultoPediatra                                        = $objReporte->reporteREM08SeccionA1AdultoPediatra($objCon, $parametros);

$seccionA1GinecoObstetra                                        = $objReporte->reporteREM08SeccionA1GinecoObstetra($objCon, $parametros);

$seccionA1Matrona                                               = $objReporte->reporteREM08SeccionA1Matrona($objCon, $parametros);

$seccionA1Odontologo                                            = $objReporte->reporteREM08SeccionA1Odontologo($objCon, $parametros);

$parametros['demandaUrgencia']                                  = 'S';

$seccionA1AdultoPediatraDemandaUrgancia                         = $objReporte->reporteREM08SeccionA1AdultoPediatra($objCon, $parametros);

$seccionA1AdultoPediatraDemandaUrganciaCategorizadosAnulados    = $objReporte->reporteREM08SeccionA1AdultoPediatraCategorizadosAnulados($objCon, $parametros);

$seccionA1GinecoObstetraDemandaUrgencia                         = $objReporte->reporteREM08SeccionA1GinecoObstetra($objCon, $parametros);

$seccionA1OdontologoDemandaUrgencia                             = $objReporte->reporteREM08SeccionA1Odontologo($objCon, $parametros);

$seccionA1OdontologoDemandaUrgenciaCategorizadosAnulados        = $objReporte->reporteREM08SeccionA1OdontologoCategorizadosAnulados($objCon, $parametros);

?>



<!--
################################################################################################################################################
                                                            DESPLIEGUE SECCIÓN A1
-->
<div class="row">
    <div class="container col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">

                    <tr>

                        <th colspan="44" style="text-align: center;"  >Sección A.1: Atenciones Realizadas en Unidades de Emergencia Hospitalaria de Alta y Mediana Complejidad (UEH)</th>

                    </tr>

                    <tr>

                        <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Tipo Atención</th>

                        <th colspan="3" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Total</th>

                        <th colspan="34" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Rango Edades</th>

                        <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Benificiarios</th>

                        <th colspan="3" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Origen Procedencia (Sólo Pacientes Derivados)</th>

                        <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Establecimientos Otra Red</th>

                        <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Demanda de Urgencia</th>

                    </tr>

                    <tr>

                        <th >&nbsp;</th>

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

                        <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >SAPU/SAR/SUR</th>

                        <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Med./Alta Compl.</th>

                        <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Otros de Red</th>

                        <th>&nbsp;</th>

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

                        <th>&nbsp;</th>

                        <th>&nbsp;</th>

                        <th>&nbsp;</th>

                        <th>&nbsp;</th>

                        <th>&nbsp;</th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Niño y Adulto</td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo count($seccionA1AdultoPediatra); ?></td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesPorSexo($seccionA1AdultoPediatra, 'M'); ?></td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesPorSexo($seccionA1AdultoPediatra, 'F'); ?></td>

                        <?php

                        $limiteSecciones = 17;

                        $edadMenor = 0;

                        $edadMayor = 4;

                        for ( $i = 0; $i < $limiteSecciones; $i++, $edadMenor += 5, $edadMayor += 5 ) {

                            echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.filtrarPacientesPorSexoYEdad($seccionA1AdultoPediatra, 'M', $edadMenor, $edadMayor).'</td>';

                            echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.filtrarPacientesPorSexoYEdad($seccionA1AdultoPediatra, 'F', $edadMenor, $edadMayor).'</td>';

                        }

                        ?>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesPorPrevision($seccionA1AdultoPediatra); ?></td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesSegunIndicador($seccionA1AdultoPediatra, 'tipoRedEstablecimiento', 'SAPU/SAR/SUR'); ?></td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesSegunIndicador($seccionA1AdultoPediatra, 'tipoRedEstablecimiento', 'HOSP MED COMPL'); ?></td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesSegunIndicador($seccionA1AdultoPediatra, 'tipoRedEstablecimiento', 'RED'); ?></td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesSegunIndicador($seccionA1AdultoPediatra, 'tipoRedEstablecimiento', 'NO RED'); ?></td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo (count($seccionA1AdultoPediatraDemandaUrgancia) + $seccionA1AdultoPediatraDemandaUrganciaCategorizadosAnulados['totalDemandaCategorizadosAnulados']); ?></td>

                    </tr>

                    <tr>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Gineco-Obstetra</td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo count($seccionA1GinecoObstetra); ?></td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >0</td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesPorSexo($seccionA1GinecoObstetra, 'F'); ?></td>

                        <?php

                        $limiteSecciones = 17;

                        $edadMenor = 0;

                        $edadMayor = 4;

                        for ( $i = 0; $i < $limiteSecciones; $i++, $edadMenor += 5, $edadMayor += 5 ) {

                            echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >0</td>';

                            echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.filtrarPacientesPorSexoYEdad($seccionA1GinecoObstetra, 'F', $edadMenor, $edadMayor).'</td>';

                        }

                        ?>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesPorPrevision($seccionA1GinecoObstetra); ?></td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesSegunIndicador($seccionA1GinecoObstetra, 'tipoRedEstablecimiento', 'SAPU/SAR/SUR'); ?></td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesSegunIndicador($seccionA1GinecoObstetra, 'tipoRedEstablecimiento', 'HOSP MED COMPL'); ?></td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesSegunIndicador($seccionA1GinecoObstetra, 'tipoRedEstablecimiento', 'RED'); ?></td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesSegunIndicador($seccionA1GinecoObstetra, 'tipoRedEstablecimiento', 'NO RED'); ?></td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo count($seccionA1GinecoObstetraDemandaUrgencia); ?></td>

                    </tr>

                    <tr>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Matronas</td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo count($seccionA1Matrona); ?></td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >0</td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesPorSexo($seccionA1Matrona, 'F'); ?></td>

                        <?php

                        $limiteSecciones = 17;

                        $edadMenor = 0;

                        $edadMayor = 4;

                        for ( $i = 0; $i < $limiteSecciones; $i++, $edadMenor += 5, $edadMayor += 5 ) {

                            echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >0</td>';

                            echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.filtrarPacientesPorSexoYEdad($seccionA1Matrona, 'F', $edadMenor, $edadMayor).'</td>';

                        }

                        ?>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesPorPrevision($seccionA1Matrona); ?></td>

                        <th>&nbsp;</th>

                        <th>&nbsp;</th>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</td>

                    </tr>

                    <tr>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Odontólogos</td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo count($seccionA1Odontologo); ?></td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesPorSexo($seccionA1Odontologo, 'M'); ?></td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesPorSexo($seccionA1Odontologo, 'F'); ?></td>

                        <?php

                        $limiteSecciones = 17;

                        $edadMenor = 0;

                        $edadMayor = 4;

                        for ( $i = 0; $i < $limiteSecciones; $i++, $edadMenor += 5, $edadMayor += 5 ) {

                            echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.filtrarPacientesPorSexoYEdad($seccionA1Odontologo, 'M', $edadMenor, $edadMayor).'</td>';

                            echo '<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >'.filtrarPacientesPorSexoYEdad($seccionA1Odontologo, 'F', $edadMenor, $edadMayor).'</td>';

                        }

                        ?>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesPorPrevision($seccionA1Odontologo); ?></td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesSegunIndicador($seccionA1Odontologo, 'tipoRedEstablecimiento', 'SAPU/SAR/SUR'); ?></td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesSegunIndicador($seccionA1Odontologo, 'tipoRedEstablecimiento', 'HOSP MED COMPL'); ?></td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesSegunIndicador($seccionA1Odontologo, 'tipoRedEstablecimiento', 'RED'); ?></td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarPacientesSegunIndicador($seccionA1Odontologo, 'tipoRedEstablecimiento', 'NO RED'); ?></td>

                        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo (count($seccionA1OdontologoDemandaUrgencia) + $seccionA1OdontologoDemandaUrgenciaCategorizadosAnulados['totalDemandaCategorizadosAnulados']); ?></td>

                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>