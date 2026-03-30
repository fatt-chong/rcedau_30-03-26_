<?php
session_start();
error_reporting(0);
require_once("../../../config/config.php");
require_once('../../../class/Util.class.php');
$objUtil = new Util;
require_once('../../../class/Connection.class.php');
$objCon = new Connection;
$objCon->db_connect();
require_once("../../../class/Dau.class.php");
$objDau = new Dau;
require_once('../../../class/Config.class.php');
$objConfig = new Config;
require_once('../../../class/RegistroClinico.class.php');
$objRegistroClinico = new RegistroClinico;
require_once('../../../class/Categorizacion.class.php');
$objCategorizacion = new Categorizacion;
require_once('../../../class/Diagnosticos.class.php');
$objDiagnosticos = new Diagnosticos;
require_once('../../../class/Formulario_3.class.php');
$objFormulario_3 = new Formulario_3;
require_once('../../../class/Formulario_3_Dosis.class.php');
$objFormulario_3_Dosis = new Formulario_3_Dosis;

require_once('../../../class/SqlDinamico.class.php');        $objSqlDinamico         = new SqlDinamico;

$parametros = $objUtil->getFormulario($_POST);
$dau_id = $_POST['dau_id'];
$datosU = $objCategorizacion->searchPaciente($objCon, $parametros['dau_id']);
$horarioServidor = $objUtil->getHorarioServidor($objCon);
$datosDAUPaciente = $objDau->buscarListaPaciente($objCon,$parametros);
$rsRce = $objRegistroClinico->consultaRCE($objCon,$parametros);
$parametros['cta_cte'] = $datosU[0]['idctacte'];
$rsRce_diagnostico = $objDiagnosticos->obtenerRce_diagnosticoCompartido($objCon,$parametros);
$rsFormulario3 = $objFormulario_3->SelectByDauFormulario_3($objCon,$dau_id);
$rsFormulario3Dosis = $objFormulario_3_Dosis->SelectByFormulario3Id($objCon,$rsFormulario3[0]['id']);

if(count($rsFormulario3) == 0){
    $rsFormulario3[0]['fecha_registro'] = $horarioServidor[0]['fecha'];
    $rsFormulario3[0]['nombre_paciente'] = $datosU[0]['nombres'];
    $rsFormulario3[0]['apellidos_paciente'] = $datosU[0]['apellidopat'].' '.$datosU[0]['apellidomat'];
    $rsFormulario3[0]['edad_paciente'] = $datosU[0]['dau_paciente_edad'];
    $rsFormulario3[0]['direccion_paciente'] = $datosDAUPaciente[0]['Direccion'];
    if($datosU[0]['dau_mordedura'] > 0 ){
        $parametrosSelect['mor_id']             = "mor_id = '".$datosU[0]['dau_mordedura']."'";
        $cargarMordedura                        = $objSqlDinamico->generarSelect($objCon,'dau.mordedura' , $parametrosSelect, $order);
        $rsFormulario3[0]['animal_mordedor']    = $cargarMordedura[0]['mor_descripcion'];
    }
}
?>

<script type="text/javascript">
// function agregarDosis() {
//     $.validity.start();
    
//     if($("#numero_dosis").val() == ""){
//         $('#numero_dosis').assert(false,'Debe seleccionar el número de dosis');
//         $.validity.end();
//         return false;
//     }
    
//     if($("#fecha_aplicacion").val() == ""){
//         $('#fecha_aplicacion').assert(false,'Debe ingresar la fecha de aplicación');
//         $.validity.end();
//         return false;
//     }


// }
function formatearFecha(fechaYMD) {
    if (!fechaYMD) return "";
    const partes = fechaYMD.split("-");
    if (partes.length !== 3) return fechaYMD;
    return `${partes[2]}-${partes[1]}-${partes[0]}`;
}
function normalizarDosis(v) {
    return (v || "").toString().replace(/\D+/g, "");
}
function agregarDosis() {
    $.validity.start();

    if ($("#numero_dosis").val() == "") {
        $('#numero_dosis').assert(false, 'Debe seleccionar el número de dosis');
        $.validity.end();
        return false;
    }

    if ($("#fecha_aplicacion").val() == "") {
        $('#fecha_aplicacion').assert(false, 'Debe ingresar la fecha de aplicación');
        $.validity.end();
        return false;
    }

    const ok = $.validity.end();
    if (!ok) return false;

    // Tomar valores de los inputs
    const numeroDosis = $("#numero_dosis").val();
    const fechaAplicacion = formatearFecha($("#fecha_aplicacion").val()); 
    const citacionVacuna = $("#citacion_vacuna").val();
     const dosisNueva = normalizarDosis(numeroDosis);
    let duplicado = false;

    $("#tablaVacunas tbody tr").each(function () {
        const dosisExistenteTxt = $(this).find("td").eq(0).text().trim();
        const dosisExistente = normalizarDosis(dosisExistenteTxt);
        if (dosisExistente !== "" && dosisExistente === dosisNueva) {
            duplicado = true;
            // resalta la fila existente (opcional)
            $(this).addClass("table-warning");
            setTimeout(() => $(this).removeClass("table-warning"), 1200);
            return false; // break
        }
    });

    if (duplicado) {
        $("#numero_dosis").assert(false, 'Ya existe número de dosis');
        return false;
    }
    // Construir el <tr>
    const fila = `
        <tr>
            <td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center">${numeroDosis}</td>
            <td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center">${fechaAplicacion}</td>
            <td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center">${citacionVacuna}</td>
            <td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center">
                <button type="button" class="btn btn-sm btn-outline-danger  form-control btnEliminar"><i class="fas fa-eraser mr-1"></i>Eliminar</button>
            </td>
        </tr>`;

    // Agregar al final del tbody de la tabla con id=tablaVacunas
    $("#tablaVacunas tbody").append(fila);

    // Limpiar inputs (opcional)
    $("#numero_dosis").val("");
    $("#fecha_aplicacion").val("");
    $("#citacion_vacuna").val("");

    return true;
}
$(document).on("click", ".btnEliminar", function () {
    $(this).closest("tr").remove();
});
$(document).ready(function() {
    $('#guardarFormulario3').on('click', function(){
        var idDau = $('#dau_id').val();
        var filas = document.querySelectorAll("#tablaVacunas tbody tr");
        var nuevosCampos = [];

        filas.forEach(fila => {
            var columnas = fila.querySelectorAll("td");
            var datos = {
                numeroDosis: columnas[0].innerText.trim(),
                fechaAplicacion: columnas[1].innerText.trim(),
                citacionVacuna: columnas[2].innerText.trim()
            };
            nuevosCampos.push(datos);
        });
        respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/enfermera/main_controller.php`,$("#formulario_3").serialize()+'&accion=guardarFormulario3Controller&nuevosCampos='+ encodeURIComponent(JSON.stringify(nuevosCampos)), 'POST','JSON', 1, '' );
        switch ( respuestaAjaxRequest.status ) {
            case "success":
                ajaxContent(raiz+'/views/modules/formularios/formulario_3.php','dau_id='+$('#dau_id').val(),'#formContainer','', true);
                modalFormulario("<label class='mifuente ml-2'>Protocolo Vacunación Antirrábica DAU N°"+idDau+"</label>", `${raiz}/views/modules/formularios/pdfformulario_3.php`, 'dau_id='+idDau, "#modalHojaEnfermeria", "modal-lg", "light",'', '');
        }
    });
});
</script>

<style>
.writing-icon {
    animation: wiggle 1s infinite;
}
@keyframes wiggle {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(10deg); }
    75% { transform: rotate(-10deg); }
}
.table-formulario {
    border-collapse: collapse;
    width: 100%;
}
.table-formulario td, .table-formulario th {
    border: 1px solid #dee2e6;
    padding: 4px;
}
.titulo-seccion {
    background: #f8f9fa;
    font-weight: bold;
}
</style>

<form class="formularios" name="formulario_3" id="formulario_3">
    <input type="hidden" name="id_formulario" id="id_formulario" value="<?=$rsFormulario3[0]['id'];?>" >
    <input type="hidden" name="dau_id" id="dau_id" value="<?=$dau_id;?>" >

    <h5 class="mt-2 mifuente14">PROTOCOLO VACUNACIÓN ANTIRRÁBICA <i class="fas fa-pen ml-2 writing-icon text-primary"></i></h5>
    
    <div class="row mb-3">
        <div class="col-md-3">
            <label class="mifuente12">NOMBRE</label>
            <input type="text" name="nombre_paciente" class="form-control form-control-sm mifuente11" readonly value="<?=$rsFormulario3[0]['nombre_paciente']?>">
        </div>
        <div class="col-md-3">
            <label class="mifuente12">APELLIDOS</label>
            <input type="text" name="apellidos_paciente" class="form-control form-control-sm mifuente11" readonly value="<?=$rsFormulario3[0]['apellidos_paciente']?>">
        </div>
        <div class="col-md-2">
            <label class="mifuente12">EDAD</label>
            <input type="text" name="edad_paciente" class="form-control form-control-sm mifuente11" readonly value="<?=$rsFormulario3[0]['edad_paciente']?>">
        </div>
        <div class="col-md-2">
            <label class="mifuente12">RELIGIÓN</label>
            <input type="text" name="religion_paciente" class="form-control form-control-sm mifuente11" readonly value="<?= isset($rsFormulario3[0]['religion_descripcion']) ? $rsFormulario3[0]['religion_descripcion'] : ''; ?>">
        </div>
        <div class="col-md-2">
            <label class="mifuente12">FECHA</label>
            <input type="date" name="fecha_registro" class="form-control form-control-sm mifuente11" value="<?=$rsFormulario3[0]['fecha_registro']?>">
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="mifuente12">DIRECCIÓN</label>
            <input type="text" name="direccion_paciente" class="form-control form-control-sm mifuente11" value="<?=$rsFormulario3[0]['direccion_paciente']?>">
        </div>
        <div class="col-md-6">
            <label class="mifuente12">CONSULTORIO</label>
            <input type="text" name="consultorio" class="form-control form-control-sm mifuente11" value="<?=$rsFormulario3[0]['consultorio']?>">
        </div>
    </div>

    <h6 class="mt-3 mifuente13">MORDEDURA</h6>
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="mifuente12">ANIMAL MORDEDOR</label>
            <input type="text" name="animal_mordedor" class="form-control form-control-sm mifuente11" value="<?=$rsFormulario3[0]['animal_mordedor']?>">
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="animal_provocado" value="Sí" <?php if($rsFormulario3[0]['animal_provocado'] == 'Sí') echo "checked"; ?>>
                        <label class="form-check-label mifuente12">Animal provocado</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="animal_no_provocado" value="Sí" <?php if($rsFormulario3[0]['animal_no_provocado'] == 'Sí') echo "checked"; ?>>
                        <label class="form-check-label mifuente12">Animal no provocado</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="ubicable" value="Sí" <?php if($rsFormulario3[0]['ubicable'] == 'Sí') echo "checked"; ?>>
                        <label class="form-check-label mifuente12">Ubicable</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="no_ubicable" value="Sí" <?php if($rsFormulario3[0]['no_ubicable'] == 'Sí') echo "checked"; ?>>
                        <label class="form-check-label mifuente12">No ubicable</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h6 class="mt-3 mifuente13">DOSIS DE VACUNACIÓN</h6>
    
    <!-- Formulario para agregar dosis -->
    <div class="card mb-3">
        <div class="card-header">
            <h6 class="mb-0">Agregar Dosis</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label class="mifuente11">N° Dosis</label>
                    <select id="numero_dosis" class="form-control form-control-sm mifuente11">
                        <option value="">Seleccione...</option>
                        <option value="3">3°</option>
                        <option value="4">4°</option>
                        <option value="5">5°</option>
                        <option value="6">6°</option>
                        <option value="7">7°</option>
                        <option value="8">8°</option>
                        <option value="9">9°</option>
                        <option value="10">10°</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="mifuente11">Fecha de Aplicación</label>
                    <input type="date" id="fecha_aplicacion" class="form-control form-control-sm mifuente11">
                </div>
                <div class="col-md-4">
                    <label class="mifuente11">Citación de Vacuna</label>
                    <input type="text" id="citacion_vacuna" class="form-control form-control-sm mifuente11" placeholder="Fecha o notas">
                </div>
                <div class="col-md-2">
                    <label class="mifuente11">&nbsp;</label>
                    <button type="button" class="btn btn-sm btn-success form-control" onclick="agregarDosis()">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de dosis -->
    <div class="table-responsive">
        <table id="tablaVacunas" class="table table-bordered table-sm">
            <thead class="table-dark mifuente11">
                <tr>
                    <th class="mifuente11 text-center">N° DOSIS</th>
                    <th class="mifuente11 text-center">FECHA DE APLICACIÓN</th>
                    <th class="mifuente11 text-center">CITACIÓN DE VACUNA</th>
                    <th class="mifuente11 text-center">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                <?php if ( count($rsFormulario3Dosis) ==0 ) { ?>
                <tr>
                    <td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center">1°</td>
                    <td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center"><?php echo date("d-m-Y"); ?></td>
                    <td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center">Aplicado en URGENCIAS Hospital Juan Noé Crevani</td>
                    <td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center"><button type="button" class="btn btn-sm btn-outline-danger  form-control btnEliminar">
                        <i class="fas fa-eraser mr-1"></i>Eliminar
                    </button></td>
                </tr>
                <tr>
                    <td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center">2°</td>
                    <td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center"><?php echo date("d-m-Y", strtotime("+2 days")); ?></td>
                    <td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center">Debe aplicar en su CESFAM</td>
                    <td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center"><button type="button" class="btn btn-sm btn-outline-danger  form-control btnEliminar">
                        <i class="fas fa-eraser mr-1"></i>Eliminar
                    </button></td>
                </tr>

                <?php } ?>
                <?php foreach ($rsFormulario3Dosis as $dosis): ?>
                <tr>
                    <td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center btnEliminar"><?= $dosis['numero_dosis']; ?>° </td>
                    <td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center"><?= date("d-m-Y", strtotime($dosis['fecha_aplicacion'])); ?></td>
                    <td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center"><?= $dosis['citacion_vacuna']; ?></td>
                    <td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center"><button type="button" class="btn btn-sm btn-outline-danger  form-control btnEliminar">
                        <i class="fas fa-eraser mr-1"></i>Eliminar
                    </button></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

 <!--    <div class="row mb-3">
        <div class="col-md-12">
            <label class="mifuente12">Observaciones:</label>
            <textarea name="observaciones" class="form-control mifuente11" rows="4"><?=$rsFormulario3[0]['observaciones']?></textarea>
        </div>
    </div>
 -->

    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4"></div>
        <div class="col-lg-4">
            <button id="guardarFormulario3" type="button" class="btn btn-sm btn-primary col-lg-12">
                <i class="fas fa-check"></i>
                <i class="glyphicon glyphicon-print"></i> Imprimir
            </button>
        </div>
    </div>
</form>