<?php
ini_set('memory_limit', '1G');
set_time_limit(0);

// error_reporting(0);
require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php');     $objCon     = new Connection; $objCon->db_connect();
require_once('../../../../class/Util.class.php');           $objUtil    = new Util;
require_once('../../../../class/Reportes.class.php');       $objReporte    = new Reportes;



if ( $_POST ) {

    $campos = $objUtil->getFormulario($_POST);

    $_SESSION['modulos']["reporteREM08"]["worklist"] = $campos;

} else if ( isset($_SESSION['modulos']["reporteREM08"]["worklist"]) ) {

    $campos = $_SESSION['modulos']["reporteREM08"]["worklist"];

} else {

    $campos = 0;

}

$parametros['fechaAnterior'] = date('Y-m-d', strtotime($campos['frm_fechaResumenInicio']));

$parametros['fechaActual']   = date('Y-m-d', strtotime($campos['frm_fechaResumenTermino']));

// $objCon                      = $objUtil->cambiarServidorReporte($parametros['fechaAnterior'], $parametros['fechaActual']);

$version                     = $objUtil->versionJS();
?>



<!--
################################################################################################################################################
                                                       			 DESPLIGUE RESULTADOS
-->
<?php
if ( empty($campos) || is_null($campos) ) {

    $objCon = NULL;

    return;

}
?>
<!-- <div id='divDesplieguecamposBusqueda'> -->

    <div class="row">

        <h2 style="text-align:center;">Reporte REM 08: <?php echo date('d-m-Y', strtotime($parametros['fechaAnterior'])); ?>  Hasta: <?php echo date('d-m-Y', strtotime($parametros['fechaActual'])) ?></h2>

    </div>

<!-- </div> -->

<br>

<!-- <div class="row"  style="height:550px; overflow-y:scroll; overflow-x:hidden" -->


    <!-- <div id="divSeccionA1" class=""> -->

        <?php
        include('seccionA1.php');
        ?>

    <!-- </div> -->

    <br>

   
    <!-- <div id="divSeccionB" class="row"> -->

        <?php
        include('seccionB.php');
        ?>

    <!-- </div> -->

    <br>
    <!-- <div id="divSeccionB" class="row"> -->

        <?php
        include('seccionB1.php');
        ?>

    <!-- </div> -->

    <br>

   
    <!-- <div id="divSeccionC" class="row"> -->

        <?php
        include('seccionC.php');
        ?>

    <!-- </div> -->

    <br>

   
    <!-- <div id="divSeccionD" class="row"> -->

        <?php
        include('seccionD.php');
        ?>

    <!-- </div> -->

    <br>

   
   <!--  <div id="divSeccionF" class="row"> -->

        <?php
        include('seccionF.php');
        ?>

    <!-- </div> -->

    <br>

   
    <!-- <div id="divSeccionG" class="row"> -->

        <?php
        include('seccionG.php');
        ?>

    <!-- </div> -->

    <br>

   
    <!-- <div id="divSeccionL" class="row"> -->

        <?php
        include('seccionL.php');
        ?>

    <!-- </div> -->

    <br>

   
    <!-- <div id="divSeccionM" class="row"> -->

        <?php
        include('seccionM.php');
        ?>

    <!-- </div> -->

    <br>

   
    <!-- <div id="divSeccionO" class="row"> -->

        <?php
        include('seccionO.php');
        ?>

    <!-- </div> -->

    <br>

  
    <!-- <div id="divSeccionO(2)" class="row"> -->

        <?php
        include('seccionO(2).php');
        ?>

    <!-- </div> -->

    <br>

   
    <!-- <div id="divSeccionP" class="row"> -->

        <?php
        include('seccionP.php');
        ?>

    <!-- </div> -->

    <br>

   
    <!-- <div id="divSeccionP(2)" class="row"> -->

        <?php
        include('seccionP(2).php');
        ?>

    <!-- </div> -->

    <br>

   
    <!-- <div id="divSeccionQ" class="row"> -->

        <?php
        include('seccionQ.php');
        ?>

    <!-- </div> -->

    <br>

   
    <!-- <div id="divSeccionQ" class="row"> -->

        <?php
        include('seccionR.php');
        ?>

    <!-- </div> -->

    <br> 

<!-- </div> -->



<!--
################################################################################################################################################
                                                       	            FUNCIONES PHP
-->
<?php
function filtrarPacientesPorSexo ( $arrayResultados, $sexo ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        if ( $array['sexoPaciente'] != $sexo ) {

            continue;

        }

        $total++;

    }

    return $total;

}



function filtrarPacientesPorSexoYEdad ( $arrayResultados, $sexo, $edadMenor, $edadMayor ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        if ( $array['sexoPaciente'] != $sexo ) {

            continue;

        }

        if ( $edadMenor == 80 && $array['edadPaciente'] >= $edadMenor ) {

            $total++;

            continue;

        }

        if (  $array['edadPaciente'] >= $edadMenor && $array['edadPaciente'] <= $edadMayor ) {

            $total++;

        }

    }

    return $total;

}


function filtrarPacientesPorPrevision ( $arrayResultados ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        if ( ! esBeneficiario($array['previsionPaciente']) ) {

            continue;

        }

        $total++;

    }

    return $total;

}



function filtrarPacientesSegunIndicador ( $arrayResultados, $indicador, $tipoIndicador ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        if ( $array[$indicador] != $tipoIndicador ) {

            continue;

        }

        $total++;

    }

    return $total;

}



function filtrarPacientesSegunIndicadorYSexo ( $arrayResultados, $indicador, $tipoIndicador, $sexo ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        if ( $array[$indicador] != $tipoIndicador ) {

            continue;

        }

        if ( $array['sexoPaciente'] != $sexo ) {

            continue;

        }

        $total++;

    }

    return $total;

}



function filtrarPacientesSegunIndicadorYSexoYEdad ( $arrayResultados, $indicador, $tipoIndicador, $sexo, $edadMenor, $edadMayor ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        if ( $array[$indicador] != $tipoIndicador ) {

            continue;

        }

        if ( $array['sexoPaciente'] != $sexo ) {

            continue;

        }

        if ( $edadMenor == 80 && $array['edadPaciente'] >= $edadMenor ) {

            $total++;

            continue;

        }

        if (  $array['edadPaciente'] >= $edadMenor && $array['edadPaciente'] <= $edadMayor ) {

            $total++;

        }

    }

    return $total;

}



function filtrarPacientesSegunTiempoHospitalizacion ( $arrayResultados, $tipoTiempoEspera ) {

    $doceHoras = 43200;

    $veinteycuatroHoras = 86400;

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        switch ( $tipoTiempoEspera ) {

            case 'menos 12 horas':

                if ( $array['tiempoEspera'] < $doceHoras ) {

                    $total++;

                }

            break;

            case 'entre 12 y 24 horas':

                if ( $array['tiempoEspera'] >= $doceHoras && $array['tiempoEspera'] <= $veinteycuatroHoras ) {

                    $total++;

                }

            break;

            case 'más 24 horas':

                if ( $array['tiempoEspera'] > $veinteycuatroHoras ) {

                    $total++;

                }

            break;

        }

    }

    return $total;

}



function filtrarPacientesSegunTiempoHospitalizacionYSexo ( $arrayResultados, $tipoTiempoEspera, $sexo ) {

    $doceHoras = 43200;

    $veinteycuatroHoras = 86400;

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        switch ( $tipoTiempoEspera ) {

            case 'menos 12 horas':

                if ( $array['tiempoEspera'] < $doceHoras && $array['sexoPaciente'] == $sexo ) {

                    $total++;

                }

            break;

            case 'entre 12 y 24 horas':

                if ( $array['tiempoEspera'] >= $doceHoras && $array['tiempoEspera'] <= $veinteycuatroHoras && $array['sexoPaciente'] == $sexo ) {

                    $total++;

                }

            break;

            case 'más 24 horas':

                if ( ($array['tiempoEspera'] > $veinteycuatroHoras) && ($array['sexoPaciente'] == $sexo) ) {

                    $total++;

                }

            break;

        }

    }

    return $total;

}



function filtrarPacientesSegunTiempoHospitalizacionYSexoYEdad( $arrayResultados, $tipoTiempoEspera, $sexo, $edadMenor, $edadMayor ) {

    if ( $edadMenor == 80 ) {

        $edadMayor = 200;

    }

    $doceHoras = 43200;

    $veinteycuatroHoras = 86400;

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        switch ( $tipoTiempoEspera ) {

            case 'menos 12 horas':

                if ( $array['tiempoEspera'] < $doceHoras && $array['sexoPaciente'] == $sexo && $array['edadPaciente'] >= $edadMenor && $array['edadPaciente'] <= $edadMayor ) {

                    $total++;

                }

            break;

            case 'entre 12 y 24 horas':

                if ( $array['tiempoEspera'] >= $doceHoras && $array['tiempoEspera'] <= $veinteycuatroHoras && $array['sexoPaciente'] == $sexo && $array['edadPaciente'] >= $edadMenor && $array['edadPaciente'] <= $edadMayor ) {

                    $total++;

                }

            break;

            case 'más 24 horas':

                if ( $array['tiempoEspera'] > $veinteycuatroHoras && $array['sexoPaciente'] == $sexo && $array['edadPaciente'] >= $edadMenor && $array['edadPaciente'] <= $edadMayor ) {

                    $total++;

                }

            break;

        }

    }

    return $total;

}



function filtrarPacientesPorPrevisionYTiempoHospitalizacion ( $arrayResultados, $tipoTiempoEspera ) {

    $doceHoras = 43200;

    $veinteycuatroHoras = 86400;

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        switch ( $tipoTiempoEspera ) {

            case 'menos 12 horas':

                if ( $array['tiempoEspera'] < $doceHoras && esBeneficiario($array['previsionPaciente']) ) {

                    $total++;

                }

            break;

            case 'entre 12 y 24 horas':

                if ( $array['tiempoEspera'] >= $doceHoras && $array['tiempoEspera'] <= $veinteycuatroHoras && esBeneficiario($array['previsionPaciente']) ) {

                    $total++;

                }

            break;

            case 'más 24 horas':

                if ( $array['tiempoEspera'] > $veinteycuatroHoras && esBeneficiario($array['previsionPaciente']) ) {

                    $total++;

                }

            break;

        }

    }

    return $total;

}



function filtrarPacientesPorPrevisionTiempoHospitalizacionYHospitalizacionDomiciliaria ( $arrayResultados, $tipoTiempoEspera, $tipoHospitalizacion ) {

    $doceHoras = 43200;

    $veinteycuatroHoras = 86400;

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        switch ( $tipoTiempoEspera ) {

            case 'menos 12 horas':

                if ( $array['tiempoEspera'] < $doceHoras && esBeneficiario($array['previsionPaciente']) && $array['tipoHospitalizacion'] == $tipoHospitalizacion ) {

                    $total++;

                }

            break;

            case 'entre 12 y 24 horas':

                if ( $array['tiempoEspera'] >= $doceHoras && $array['tiempoEspera'] <= $veinteycuatroHoras && esBeneficiario($array['previsionPaciente']) && $array['tipoHospitalizacion'] == $tipoHospitalizacion ) {

                    $total++;

                }

            break;

            case 'más 24 horas':

                if ( $array['tiempoEspera'] > $veinteycuatroHoras && esBeneficiario($array['previsionPaciente']) && $array['tipoHospitalizacion'] == $tipoHospitalizacion ) {

                    $total++;

                }

            break;

        }

    }

    return $total;

}



function filtrarPacientesPorPrevisionYTipoHospitalizacion ( $arrayResultados, $tipoHospitalizacion ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        if ( $array['tipoHospitalizacion'] == $tipoHospitalizacion && esBeneficiario($array['previsionPaciente'])  ) {

            $total++;

        }

    }

    return $total;

}



function filtrarPacientesSegunTipoIndicadorYAgresor ( $arrayResultados, $indicador, $tipoIndicador, $tipoAgresor ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        switch ( $tipoAgresor ) {

            case 'Pareja / Ex Pareja':

                if ( ($array[$indicador] == $tipoIndicador) && ($array['idTipoAgresor'] == 5 || $array['idTipoAgresor'] == 6) ) {

                    $total++;

                }

            break;

            case 'Familiar':

                if ( ($array[$indicador] == $tipoIndicador) && ($array['idTipoAgresor'] == 7 || $array['idTipoAgresor'] == 8) ) {

                    $total++;

                }

            break;

            case 'Conocido/a':

                if ( ($array[$indicador] == $tipoIndicador) && ($array['idTipoAgresor'] == 1 || $array['idTipoAgresor'] == 2) ) {

                    $total++;

                }

            break;

            case 'Desconocido/a':

                if ( ($array[$indicador] == $tipoIndicador) && ($array['idTipoAgresor'] == 3 || $array['idTipoAgresor'] == 4) ) {

                    $total++;

                }

            break;

        }

    }

    return $total;

}



function filtrarPacientesSegunTipoAgresorYSexo ( $arrayResultados, $indicador, $tipoIndicador, $sexo ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        switch ( $sexo ) {

            case 'H':

                if ( ($array[$indicador] == $tipoIndicador) && ($array['idTipoAgresor'] == 1 || $array['idTipoAgresor'] == 3 || $array['idTipoAgresor'] == 5 || $array['idTipoAgresor'] == 7) ) {

                    $total++;

                }

            break;

            case 'F':

                if ( ($array[$indicador] == $tipoIndicador) && ($array['idTipoAgresor'] == 2 || $array['idTipoAgresor'] == 4 || $array['idTipoAgresor'] == 6 || $array['idTipoAgresor'] == 8) ) {

                    $total++;

                }

            break;

        }

    }

    return $total;

}



function filtrarPacientesSegunTipoViolenciaYEmbarazada ( $arrayResultados, $tipoViolencia, $embarazada ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        if ( ($array['descripcionTipoViolencia'] == $tipoViolencia) && ($array['victimaEmbarazada'] == $embarazada) ) {

            $total++;

        }

    }

    return $total;

}



function filtrarPacientesSegunTipoViolenciaYMigrante ( $arrayResultados, $tipoViolencia, $migrante ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        if ( ($array['descripcionTipoViolencia'] == $tipoViolencia) && ($array['victimaMigrante'] == $migrante) ) {

            $total++;

        }

    }

    return $total;

}



function filtrarPacientesSegunTipoViolenciaYPuebloOriginario ( $arrayResultados, $tipoViolencia, $puebloOriginario ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        if ( ($array['descripcionTipoViolencia'] == $tipoViolencia) && ($array['victimaPuebloOriginario'] == $puebloOriginario) ) {

            $total++;

        }

    }

    return $total;

}




function filtrarPacientesSegunTipoViolenciaYTipoLesion ( $arrayResultados, $tipoViolencia, $tipoLesion ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        switch ( $tipoLesion ) {

            case 'Traumatológicas':

                if ( ($array['descripcionTipoViolencia'] == $tipoViolencia) && ($array['idTipoLesionVictima'] == 1) ) {

                    $total++;

                }

            break;

            case 'Odontológicas':

                if ( ($array['descripcionTipoViolencia'] == $tipoViolencia) && ($array['idTipoLesionVictima'] == 2) ) {

                    $total++;

                }

            break;

            case 'Contusionales':

                if ( ($array['descripcionTipoViolencia'] == $tipoViolencia) && ($array['idTipoLesionVictima'] == 5) ) {

                    $total++;

                }

            break;

            case 'Por Arma':

                if ( ($array['descripcionTipoViolencia'] == $tipoViolencia) && ($array['idTipoLesionVictima'] == 3 || $array['idTipoLesionVictima'] == 4) ) {

                    $total++;

                }

            break;

            case 'Sin Lesiones Constatables':

                if ( ($array['descripcionTipoViolencia'] == $tipoViolencia) && ($array['idTipoLesionVictima'] == 6) ) {

                    $total++;

                }

            break;

        }

    }

    return $total;

}



function filtrarPacientesSegunTipoPenetracionYCondicion ( $arrayResultados, $indicador, $tipoViolencia, $condicion ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        switch ( $condicion ) {

            case 'gestantes':

                if ( $array[$indicador] == $tipoViolencia && $array['gestante'] == 'S' ) {

                    $total++;

                }

            break;

            case 'conAnticoncepcion':

                if ( $array[$indicador] == $tipoViolencia && $array['anticoncepcion'] == 'S' ) {

                    $total++;

                }

            break;

            case 'sinAnticoncepcion':

                if ( $array[$indicador] == $tipoViolencia && $array['anticoncepcion'] == 'N' ) {

                    $total++;

                }

            break;

            case 'conProfilaxiaVIH':

                if ( $array[$indicador] == $tipoViolencia && $array['idTipoProfilaxis'] == 2 ) {

                    $total++;

                }

            break;

            case 'conProfilaxiaITS':

                if ( $array[$indicador] == $tipoViolencia && $array['idTipoProfilaxis'] == 3 ) {

                    $total++;

                }

            break;

            case '<':

                if ( $array[$indicador] == $tipoViolencia && $array['hepatitisB'] == 'S' ) {

                    $total++;

                }

            break;

        }

    }

    return $total;

}



function filtrarPacientesSegunTipoMordedura ( $arrayResultados, $indicador, $animalMordedura, $tipoMordedura ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        if ( $array[$indicador] == $animalMordedura && $array['tipoMordedura'] == $tipoMordedura ) {

            $total++;

        }

    }

    return $total;

}



function filtrarTrasladosPrimarios ( $arrayResultados, $filtroSamu, $avanzadaOBasica, $formaMedioLlegada, $filtroEnrutado, $filtroBeneficiario ) {


    if ( $filtroBeneficiario == 'N' ) {

        return filtrarTrasladosPrimariosSinContarBeneficiarios($arrayResultados, $filtroSamu, $avanzadaOBasica, $formaMedioLlegada, $filtroEnrutado);

    }

    return filtrarTrasladosPrimariosContandoBeneficiarios($arrayResultados, $filtroSamu, $avanzadaOBasica, $formaMedioLlegada, $filtroEnrutado);

}



function filtrarTrasladosPrimariosSinContarBeneficiarios ( $arrayResultados, $filtroSamu, $avanzadaOBasica, $formaMedioLlegada, $filtroEnrutado ) {

    if ( $filtroEnrutado == 'N' ) {

        return filtrarTrasladosPrimariosSinContarBeneficiariosNoEnrutados($arrayResultados, $filtroSamu, $avanzadaOBasica, $formaMedioLlegada);

    }

    return filtrarTrasladosPrimariosSinContarBeneficiariosEnrutados($arrayResultados, $formaMedioLlegada, $filtroEnrutado);

}



function filtrarTrasladosPrimariosContandoBeneficiarios ( $arrayResultados, $filtroSamu, $avanzadaOBasica, $formaMedioLlegada, $filtroEnrutado ) {

    if ( $filtroEnrutado == 'N') {

        return filtrarTrasladosPrimariosContandoBeneficiariosNoEnrutados($arrayResultados, $filtroSamu, $formaMedioLlegada, $avanzadaOBasica);

    }

    return filtrarTrasladosPrimariosContandoBeneficiariosEnrutados($arrayResultados, $formaMedioLlegada, $filtroEnrutado);

}



function filtrarTrasladosPrimariosSinContarBeneficiariosNoEnrutados ( $arrayResultados, $filtroSamu, $avanzadaOBasica, $formaMedioLlegada ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        if ( $array['filtroSamu'] == $filtroSamu && $array['avanzadaOBasica'] == $avanzadaOBasica && $array['formaMedioLlegada'] == $formaMedioLlegada ) {

            $total++;

        }

    }

    return $total;

}


function filtrarTrasladosPrimariosSinContarBeneficiariosEnrutados ( $arrayResultados, $formaMedioLlegada, $filtroEnrutado ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        if ( $array['formaMedioLlegada'] == $formaMedioLlegada && $array['filtroEnrutado'] == $filtroEnrutado ) {

            $total++;

        }

    }

    return $total;

}



function filtrarTrasladosPrimariosContandoBeneficiariosNoEnrutados ( $arrayResultados, $filtroSamu, $formaMedioLlegada, $avanzadaOBasica ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        if ( $array['filtroSamu'] == $filtroSamu && $array['avanzadaOBasica'] == $avanzadaOBasica && esBeneficiario($array['previsionPaciente']) && $array['formaMedioLlegada'] == $formaMedioLlegada ) {

            $total++;

        }

    }

    return $total;

}



function filtrarTrasladosPrimariosContandoBeneficiariosEnrutados ( $arrayResultados, $formaMediollegada, $filtroEnrutado ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        if ( $array['filtroEnrutado'] == $filtroEnrutado && esBeneficiario($array['previsionPaciente']) && $array['formaMedioLlegada'] == $formaMediollegada ) {

            $total++;

        }

    }

    return $total;

}



function filtrarTrasladosSecundariosCriticos ( $arrayResultados, $formaMedioLlegada, $filtroSamu ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        if ( $array['pacienteCritico'] == 'S' && $array['formaMedioLlegada'] == $formaMedioLlegada && $array['filtroSamu'] == $filtroSamu ) {

            $total++;

        }

    }

    return $total;

}



function filtrarTrasladosSecundariosCriticosBeneficiarios ( $arrayResultados, $formaMedioLlegada, $filtroSamu ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        if ( $array['pacienteCritico'] == 'S' && $array['formaMedioLlegada'] == $formaMedioLlegada && esBeneficiario($array['previsionPaciente']) && $array['filtroSamu'] == $filtroSamu ) {

            $total++;

        }

    }

    return $total;

}



function filtrarTrasladosSecundariosNoCriticos ( $arrayResultados, $formaMedioLlegada, $filtroSamu ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        if ( $array['pacienteCritico'] == 'N' && $array['formaMedioLlegada'] == $formaMedioLlegada && $array['filtroSamu'] == $filtroSamu ) {

            $total++;

        }

    }

    return $total;

}



function filtrarTrasladosSecundariosNoCriticosBeneficiarios ( $arrayResultados, $formaMedioLlegada, $filtroSamu ) {

    $total = 0;

    foreach ( $arrayResultados as $array ) {

        if ( $array['pacienteCritico'] == 'N' && $array['formaMedioLlegada'] == $formaMedioLlegada && esBeneficiario($array['previsionPaciente']) && $array['filtroSamu'] == $filtroSamu ) {

            $total++;

        }

    }

    return $total;

}



function esBeneficiario ( $tipoPrevision ) {

    return ( $tipoPrevision == 0 || $tipoPrevision == 1 || $tipoPrevision == 2 || $tipoPrevision == 3 ) ? true : false;

}
?>



<!--
################################################################################################################################################
                                                       	            CIERRE CONEXIÓN
-->
<?php

$objCon = NULL;

?>