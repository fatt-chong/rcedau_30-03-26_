<?php

$seccionP = $objReporte->reporteREM08SeccionP($objCon, $parametros);

?>



<!--
################################################################################################################################################
                                                            DESPLIEGUE SECCIÓN P
-->
<div class="row">
    <div class="container col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered" width="100%">
                <thead class="thead-dark">

  

                <tr>

                    <th colspan="2" style="text-align: center;">Sección P: Atenciones Médicas por Violencia Sexual</th>

                </tr>

                <tr>

                    <th style="text-align:center; width:50%;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Tipo Atención</th>

                    <th style="text-align:center; width:50%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Total</th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Atención por Médicos de Peritaje Forense</td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $seccionP['medicosForenses']; ?></td>

                </tr>

                <tr>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Atención Otros Médicos</td>

                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $seccionP['otrosMedicos']; ?></td>

                </tr>

            </tbody>

        </table>

    </div>

    </div>

</div>