<?php

$parametros = $objUtil->getFormulario($_POST);

$parametros['fechaAnterior'] = $objUtil->fechaAnteriorSegunTurno($parametros['tipoHorarioTurno']);

$numeroHospitalizaciones = $objTurno->obtenerNumeroHospitalizaciones($objCon, $parametros);
?>



<!-- 
################################################################################################################################################
                                                       	    DESPLIEGUE NÚMERO DE HOSPITALIZACIONES 
-->
<div  class="col-lg-1">&nbsp;</div>
<div  class="col-lg-10">
    <table id="tablaNumeroHospitalizaciones" class="table table-striped table-bordered table-hover table-condensed tablasHisto">
        <thead class="table-primary">
            <th width="40%" style="text-align:center;" class=" font-weight-bold  mifuente11">Descripción Número de Hospitalizaciones</th>
            <th width="20%" style="text-align:center;" class=" font-weight-bold  mifuente11">Cantidad Adultos</th>
            <th width="20%" style="text-align:center;" class=" font-weight-bold  mifuente11">Cantidad Pediátricos</th>
            <th width="20%" style="text-align:center;" class=" font-weight-bold  mifuente11">Cantidad Ginecológicos</th>
        </thead>
        <tbody>
            <tr>
                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Número de Pacientes con Indicación de Egreso en DAU cuyo destino es Hospitalización</td>
                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">
            
                    <?php 
            
                    echo desplegarNumeroHospitalizaciones($numeroHospitalizaciones['cantidadAdultoTotal']);
                
                    ?>

                </td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">

                    <?php 
                    
                    echo desplegarNumeroHospitalizaciones($numeroHospitalizaciones['cantidadPediatricoTotal']);
                
                    ?>
                
                </td>

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">
                
                    <?php 
                    
                    echo desplegarNumeroHospitalizaciones($numeroHospitalizaciones['cantidadGinecologicoTotal']);
                
                    ?>
                </td>
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
function desplegarNumeroHospitalizaciones ( $numeroHospitalizaciones ) {
    return ( empty($numeroHospitalizaciones) || is_null($numeroHospitalizaciones) ) ? '0' : $numeroHospitalizaciones;
}
?>