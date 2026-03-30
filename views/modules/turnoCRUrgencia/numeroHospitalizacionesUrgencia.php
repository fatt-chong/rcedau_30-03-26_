<?php
$numeroHospitalizaciones   = $objTurno->obtenerNumeroHospitalizacionesUrgencia($objCon);

$numeroHospitalizaciones12 = $objTurno->obtenerNumeroHospitalizacionesUrgencia12($objCon);

$numeroHospitalizaciones24 = $objTurno->obtenerNumeroHospitalizacionesUrgencia24($objCon);
?>



<!-- 
################################################################################################################################################
                                                       	DESPLIEGUE NÚMERO DE HOSPITALIZACIONES URGENCIA
-->
<div  class="col-lg-1">&nbsp;</div>

<div  class="col-lg-10">

    <table id="tablaNumeroHospitalizacionesUrgencia" class="table table-striped table-bordered table-hover table-condensed tablasHisto">

        <thead class="table-primary">

            <th width="40%" style="text-align:center;" class=" font-weight-bold  mifuente11">Descripción Número de Hospitalizaciones Urgencia</th>

            <th width="30%" style="text-align:center;" class=" font-weight-bold  mifuente11">Cantidad Adultos</th>

            <th width="30%" style="text-align:center;" class=" font-weight-bold  mifuente11">Cantidad Pediátricos</th>

        </thead>

        <tbody>

            <tr>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Número de Pacientes Hospitalizados en Urgencia esperando menos de 12 horas</td>

                <?php

                echo desplegarNumeroHospitalizacionesUrgencia($numeroHospitalizaciones);

                ?>

            </tr>

            <tr>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Número de Pacientes Hospitalizados en Urgencia esperando entre 12 a 24 horas</td>

                <?php

                echo desplegarNumeroHospitalizacionesUrgencia($numeroHospitalizaciones12);

                ?>

            </tr>

            <tr>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Número de Pacientes Hospitalizados en Urgencia esperando más de 24 horas</td>

                <?php

                echo desplegarNumeroHospitalizacionesUrgencia($numeroHospitalizaciones24);

                ?>

            </tr>

        </tbody>

    </table>

</div>

<div  class="col-lg-1">&nbsp;</div>



<!-- 
################################################################################################################################################
                                                       	            FUNCIONES PHP
-->
<?php
function desplegarNumeroHospitalizacionesUrgencia ( $numeroHospitalizaciones ) {

    return '

        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.existeNumeroHospitalizacionesUrgencia($numeroHospitalizaciones['cantidadAdultoTotal']).'</td>
    
        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">'.existeNumeroHospitalizacionesUrgencia($numeroHospitalizaciones['cantidadPediatricoTotal']).'</td>

        ';

}



function existeNumeroHospitalizacionesUrgencia ( $numeroHospitalizaciones ) {

    return ( empty($numeroHospitalizaciones) || is_null($numeroHospitalizaciones) ) ? '0' : $numeroHospitalizaciones;

}
?>