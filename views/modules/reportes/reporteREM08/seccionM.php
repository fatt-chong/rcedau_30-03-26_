<?php

$seccionM = $objReporte->reporteREM08SeccionM($objCon, $parametros);

?>



<!--
################################################################################################################################################
                                                            DESPLIEGUE SECCIÓN M
-->
<div class="row">
    <div class="container col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" width="100%">
                <thead class="thead-dark">

                <tr>

                    <th colspan="7" style="text-align: center;">Sección M: Traslado Secundario (Desde un Establecimiento a Otro)</th>

                </tr>

                <tr>

                    <th colspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tipo de Acción</th>

                    <th colspan="3" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Total de Traslados</th>

                    <th colspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Beneficiarios</th>

                </tr>

                <tr>

                    <th>&nbsp;</th>

                    <th>&nbsp;</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Ambos</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">SAMU</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">No SAMU</th>

                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">SAMU</th>
                    
                    <th style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">No SAMU</th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td rowspan="4" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Crítico</td>

                </tr>

                <tr>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Aéreo</td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosCriticos($seccionM, 'Aereo', 'S') + filtrarTrasladosSecundariosCriticos($seccionM, 'Aereo', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosCriticos($seccionM, 'Aereo', 'S'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosCriticos($seccionM, 'Aereo', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosCriticosBeneficiarios($seccionM, 'Aereo', 'S'); ?></td>
                    
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosCriticosBeneficiarios($seccionM, 'Aereo', 'N'); ?></td>

                </tr>

                <tr>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Terrestre</td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosCriticos($seccionM, 'Terrestre', 'S') + filtrarTrasladosSecundariosCriticos($seccionM, 'Terrestre', 'N');  ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosCriticos($seccionM, 'Terrestre', 'S'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosCriticos($seccionM, 'Terrestre', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosCriticosBeneficiarios($seccionM, 'Terrestre', 'S'); ?></td>
                    
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosCriticosBeneficiarios($seccionM, 'Terrestre', 'N'); ?></td>

                </tr>

                <tr>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Marítimo</td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosCriticos($seccionM, 'Maritimo', 'S') + filtrarTrasladosSecundariosCriticos($seccionM, 'Maritimo', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosCriticos($seccionM, 'Maritimo', 'S'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosCriticos($seccionM, 'Maritimo', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosCriticosBeneficiarios($seccionM, 'Maritimo', 'S'); ?></td>
                    
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosCriticosBeneficiarios($seccionM, 'Maritimo', 'N'); ?></td>

                </tr>

                 <tr>

                    <td rowspan="4" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >No Crítico</td>

                </tr>

                <tr>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Aéreo</td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosNoCriticos($seccionM, 'Aereo', 'S') + filtrarTrasladosSecundariosNoCriticos($seccionM, 'Aereo', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosNoCriticos($seccionM, 'Aereo', 'S'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosNoCriticos($seccionM, 'Aereo', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosNoCriticosBeneficiarios($seccionM, 'Aereo', 'S'); ?></td>
                    
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosNoCriticosBeneficiarios($seccionM, 'Aereo', 'N'); ?></td>

                </tr>

                <tr>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Terrestre</td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosNoCriticos($seccionM, 'Terrestre', 'S') + filtrarTrasladosSecundariosNoCriticos($seccionM, 'Terrestre', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosNoCriticos($seccionM, 'Terrestre', 'S'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosNoCriticos($seccionM, 'Terrestre', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosNoCriticosBeneficiarios($seccionM, 'Terrestre', 'S'); ?></td>
                    
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosNoCriticosBeneficiarios($seccionM, 'Terrestre', 'N'); ?></td>

                </tr>

                <tr>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Marítimo</td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosNoCriticos($seccionM, 'Maritimo', 'S') + filtrarTrasladosSecundariosNoCriticos($seccionM, 'Maritimo', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosNoCriticos($seccionM, 'Maritimo', 'S'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosNoCriticos($seccionM, 'Maritimo', 'N'); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosNoCriticosBeneficiarios($seccionM, 'Maritimo', 'S'); ?></td>
                    
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo filtrarTrasladosSecundariosNoCriticosBeneficiarios($seccionM, 'Maritimo', 'N'); ?></td>

                </tr>

            </tbody>

        </table>
        </div>

    </div>

</div>