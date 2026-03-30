<?php

$seccionP = $objReporte->reporteREM08SeccionP_2($objCon, $parametros);

?>



<!--
################################################################################################################################################
                                                            DESPLIEGUE SECCIÓN P (REM 2020)
-->
<div class="row">
    <div class="container col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" width="100%">
                <thead class="thead-dark">

                <tr>

                    <th colspan="4" style="text-align: center;">Sección P: Atenciones Médicas por Violencia Sexual (REM 2020)</th>

                </tr>

                <tr>

                    <th style="text-align:center; width:25%;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tipo Atención</th>

                    <th style="text-align:center; width:25%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Total</th>

                    <th style="text-align:center; width:25%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">De Turno</th>

                    <th style="text-align:center; width:25%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">De Llamada</th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Atención por Médico Perito</td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo ($seccionP['peritoSexualTurno'] + $seccionP['peritoSexualLlamado']); ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $seccionP['peritoSexualTurno']; ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $seccionP['peritoSexualLlamado']; ?></td>

                </tr>

                <tr>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Atención Otros Médicos</td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $seccionP['peritoSexualOtrosMedicos']; ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $seccionP['peritoSexualOtrosMedicos']; ?></td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</td>

                </tr>

            </tbody>

        </table>

        </div>

    </div>

</div>