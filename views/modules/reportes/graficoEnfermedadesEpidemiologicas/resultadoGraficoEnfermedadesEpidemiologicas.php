<?php
error_reporting(0);
$anioResumen                         = $_POST['anioResumen'];
$arrayDauCerradosSemanas             = $_POST['arrayDauCerradosSemanas'];
$arrayDauEnfermedadesEpidemiologicas = $_POST['arrayDauEnfermedadesEpidemiologicas'];
$arrayDauCerradosSemanas             = json_decode(stripslashes($arrayDauCerradosSemanas), true);
$arrayDauEnfermedadesEpidemiologicas = json_decode(stripslashes($arrayDauEnfermedadesEpidemiologicas), true);
$titulos                             = array("Adultos", "Pediátricos");
desplegarTablaResumenEnfermedadesEpidemiologicas($anioResumen, $titulos[0], $arrayDauCerradosSemanas[0], $arrayDauEnfermedadesEpidemiologicas[0]);
desplegarTablaResumenEnfermedadesEpidemiologicas($anioResumen, $titulos[1], $arrayDauCerradosSemanas[1], $arrayDauEnfermedadesEpidemiologicas[1]);
?>

<script>
    $(document).ready(function(){
        $("#tablaResumenEnfermedadesEpidemiologicasAdultos").highchartTable();
        $("#tablaResumenEnfermedadesEpidemiologicasPediátricos").highchartTable();
        Highcharts.setOptions({
            chart: {
                width: null,  // Deja que tome el ancho del contenedor
                height: 500   // Mantiene una altura fija
            }
        });
        Highcharts.setOptions({
    responsive: {
        rules: [{
            condition: {
                maxWidth: 600
            },
            chartOptions: {
                chart: {
                    height: 300 // Reduce la altura en pantallas pequeñas
                },
                legend: {
                    enabled: false
                }
            }
        }]
    }
});
    });
</script>
<style type="text/css">

#despliegueResultadoResumenEnfermedadesEpidemiologicas {
    width: 100%;
    margin: 0;
    padding: 0;
}

.highchart-container {
    width: 100% !important;
    max-width: 100% !important;
    height: 500px !important; /* Ajusta la altura aquí */
}


</style>

<?php
function desplegarTablaResumenEnfermedadesEpidemiologicas ( $anioResumen, $titulo, $arrayDauCerradosSemanas, $arrayDauEnfermedadesEpidemiologicas ) {
    $html =
        '
        <div id="despliegueResultadoResumenEnfermedadesEpidemiologicas" class="container-fluid">
            <div class="row mx-0" style="height: auto; width: 100%;">
                <br>
                <div class="col-12">
                    <div class="row">
                        <h1 id="tituloTablaResumenEnfermedadesEpidemiologicas" style="text-align:center;">Curva Demanda Consultas Respiratorias '.$titulo.' (Año: '.$anioResumen.')</h1>
                    </div>
                </div>
             </div>
         </div>
                        <table id="tablaResumenEnfermedadesEpidemiologicas'.$titulo.'" class="highchart mifuente"
    data-graph-container-before="1"
    data-graph-type="area"
    style="display:none; width: 100%;"
    data-graph-line-shadow="0"
    data-graph-height="500">
                            <thead>
                                <tr>
                                    <th>Semanas</th>
                                    <th>Cantidad Dau Cerrados '.$titulo.'</th>
                                    <th>Cantidad Dau Enfermedades Epidemiológicas '.$titulo.'</th>
                                </tr>
                            </thead>
                            <tbody>

                            ';
                                $anioActual = date("Y");
                                $semanaActual = ( intval($anioActual) !== intval($anioResumen) ) ? 52 : date("W", strtotime(date("Y-m-d")));
                                for ( $i = 1; $i <= $semanaActual; $i++ ) {
                                    $filaDauCerrados = 'dauCerradosSemana'.$i;
                                    $filaEnfermedadesEpidemiologicas = 'dauCerradosEnfermedadesRespiratoriasSemana'.$i;
                                    if ( $arrayDauCerradosSemanas[$filaDauCerrados] == 0 || $arrayDauEnfermedadesEpidemiologicas[$filaEnfermedadesEpidemiologicas] == 0 ) {
                                        continue;
                                    }
                                    $arrayFechas = obtenerRangoFechasSegunNumeroSemana($i, $anioResumen);
                                    $html .= '<tr>';
                                    $html .= '<td>Sem. '.$i.'  ('.$arrayFechas['principioSemana'].' / '.$arrayFechas['finSemana'].')</td>';
                                    $html .= '<td>'.$arrayDauCerradosSemanas[$filaDauCerrados].'</td>';
                                    $html .= '<td>'.$arrayDauEnfermedadesEpidemiologicas[$filaEnfermedadesEpidemiologicas].'</td>';
                                    $html .= '</tr>';
                                }
                            $html .= '
                            </tbody>
                        </table>
        ';
    echo $html;
}
function obtenerRangoFechasSegunNumeroSemana($week, $year) {
  $dto = new DateTime();
  $dto->setISODate($year, $week);
  $ret['principioSemana'] = $dto->format('d-m');
  $dto->modify('+6 days');
  $ret['finSemana'] = $dto->format('d-m');
  return $ret;
}
?>