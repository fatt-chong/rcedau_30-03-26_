<?php

$seccionCPsiquiatricaPsiquiatrica = $objReporte->reporteREM08SeccionCPsiquiatrica($objCon, $parametros);

$seccionCPsiquiatricaNeurocirugia = $objReporte->reporteREM08SeccionCNeurocirugia($objCon, $parametros);

?>



<!--
################################################################################################################################################
                                                            DESPLIEGUE SECCIÓN C
-->
<div class="row">
    <div class="container col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" width="100%">
                <thead class="thead-dark">

                <tr>

                    <th colspan="4" style="text-align: center;">Sección C: Atenciones Realizadas por Médicos Especialistas en las Unidades de Urgencia Hospitalaria</th>

                </tr>

                <tr>

                    <th style="text-align:center; width:25%;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Especialidades</th>

                    <th style="text-align:center; width:25%;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Total</th>

                    <th style="text-align:center; width:25%;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">De Turno</th>

                    <th style="text-align:center; width:25%;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Consultor Llamada</th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Neurocirugía</td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $seccionCPsiquiatricaNeurocirugia['totalPacientes']; ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $seccionCPsiquiatricaNeurocirugia['totalPacientes']; ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >0</td>

                </tr>

                <tr>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Psiquiatría Pediátrica y Adolescente</td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $seccionCPsiquiatricaPsiquiatrica['totalPacientes']; ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $seccionCPsiquiatricaPsiquiatrica['totalPacientes']; ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >0</td>

                </tr>

            </tbody>

        </table>

    </div>
    </div>

</div>