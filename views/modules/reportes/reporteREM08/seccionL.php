<?php

$seccionL = $objReporte->reporteREM08SeccionL($objCon, $parametros);

?>



<!--
################################################################################################################################################
                                                            DESPLIEGUE SECCIÓN L
-->
<div class="row">
    <div class="container col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" width="100%">
                <thead class="thead-dark">

                <tr>

                    <th colspan="9" style="text-align: center;">Sección L: Traslados Primarios a Unidades de Urgencia (Desde el Lugar del Evento a Unidad de Emergencia)</th>

                </tr>

                <tr>

                    <th colspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tipo de Móvil</th>

                    <th colspan="3" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Medio Transporte</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Total</th>

                    <th colspan="3" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Beneficiarios</th>

                </tr>

                <tr>

                    <th>&nbsp;</th>

                    <th>&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Terrestre</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Aéreo</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Marítimo</th>

                    <th>&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Terrestre</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Aéreo</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Marítimo</th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td rowspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">SAMU</td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Básico</td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $totalBasicaTerrestre = filtrarTrasladosPrimarios($seccionL, 'S', 'Basica', 'Terrestre', 'N', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $totalBasicaAereo = filtrarTrasladosPrimarios($seccionL, 'S', 'Basica', 'Aereo', 'N', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $totalBasicaMaritimo = filtrarTrasladosPrimarios($seccionL, 'S', 'Basica', 'Maritimo', 'N', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $totalBasicaTerrestre + $totalBasicaAereo + $totalBasicaMaritimo; ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo filtrarTrasladosPrimarios($seccionL, 'S', 'Basica', 'Terrestre', 'N', 'S'); ?></td>
                    
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo filtrarTrasladosPrimarios($seccionL, 'S', 'Basica', 'Aereo', 'N', 'S'); ?></td>
                    
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo filtrarTrasladosPrimarios($seccionL, 'S', 'Basica', 'Maritimo', 'N', 'S'); ?></td>

                </tr>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Avanzada</td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $totalAvanzadaTerrestre = filtrarTrasladosPrimarios($seccionL, 'S', 'Avanzada', 'Terrestre', 'N', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $totalAvanzadaAereo = filtrarTrasladosPrimarios($seccionL, 'S', 'Avanzada', 'Aereo', 'N', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $totalAvanzadaMaritimo = filtrarTrasladosPrimarios($seccionL, 'S', 'Avanzada', 'Maritimo', 'N', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $totalAvanzadaTerrestre + $totalAvanzadaAereo + $totalAvanzadaMaritimo; ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo filtrarTrasladosPrimarios($seccionL, 'S', 'Avanzada', 'Terrestre', 'N', 'S'); ?></td>
                    
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo filtrarTrasladosPrimarios($seccionL, 'S', 'Avanzada', 'Aereo', 'N', 'S'); ?></td>
                    
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo filtrarTrasladosPrimarios($seccionL, 'S', 'Avanzada', 'Maritimo', 'N', 'S'); ?></td>

                </tr>

                </tr>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Enrutado</td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Básico</td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $totalEnrutadoTerrestre = filtrarTrasladosPrimarios($seccionL, '', '', 'Terrestre', 'S', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $totalEnrutadoAereo = filtrarTrasladosPrimarios($seccionL, '', '', 'Aereo', 'S', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $totalEnrutadoMaritimo = filtrarTrasladosPrimarios($seccionL, '', '', 'Maritimo', 'S', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $totalEnrutadoTerrestre + $totalEnrutadoAereo + $totalEnrutadoMaritimo; ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo filtrarTrasladosPrimarios($seccionL, '', '', 'Terrestre', 'S', 'S'); ?></td>
                    
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo filtrarTrasladosPrimarios($seccionL, '', '', 'Aereo', 'S', 'S'); ?></td>
                    
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo filtrarTrasladosPrimarios($seccionL, '', '', 'Maritimo', 'S', 'S'); ?></td>

                </tr>

                <tr>

                    <td rowspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">No SAMU</td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Básico</td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $totalBasicaTerreste = filtrarTrasladosPrimarios($seccionL, 'N', 'Basica', 'Terrestre', 'N', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $totalBasicaAereo = filtrarTrasladosPrimarios($seccionL, 'N', 'Basica', 'Aereo', 'N', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $totalBasicaMaritimo = filtrarTrasladosPrimarios($seccionL, 'N', 'Basica', 'Maritimo', 'N', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $totalBasicaTerreste + $totalBasicaAereo + $totalBasicaMaritimo; ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo filtrarTrasladosPrimarios($seccionL, 'N', 'Basica', 'Terrestre', 'N', 'S'); ?></td>
                    
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo filtrarTrasladosPrimarios($seccionL, 'N', 'Basica', 'Aereo', 'N', 'S'); ?></td>
                    
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo filtrarTrasladosPrimarios($seccionL, 'N', 'Basica', 'Maritimo', 'N', 'S'); ?></td>

                </tr>

                <tr>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Avanzada</td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $totalAvanzadaTerrestre = filtrarTrasladosPrimarios($seccionL, 'N', 'Avanzada', 'Terrestre', 'N', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $totalAvanzadaAereo = filtrarTrasladosPrimarios($seccionL, 'N', 'Avanzada', 'Aereo', 'N', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $totalAvanzadaMaritimo = filtrarTrasladosPrimarios($seccionL, 'N', 'Avanzada', 'Maritimo', 'N', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $totalAvanzadaTerrestre + $totalAvanzadaAereo + $totalAvanzadaMaritimo; ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo filtrarTrasladosPrimarios($seccionL, 'N', 'Avanzada', 'Terrestre', 'N', 'S'); ?></td>
                    
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo filtrarTrasladosPrimarios($seccionL, 'N', 'Avanzada', 'Aereo', 'N', 'S'); ?></td>
                    
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo filtrarTrasladosPrimarios($seccionL, 'N', 'Avanzada', 'Maritimo', 'N', 'S'); ?></td>

                </tr>

            </tbody>

        </table>
        
    </div>
    </div>

</div>