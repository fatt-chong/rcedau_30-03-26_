<?php
session_start();
error_reporting(0);
require("../../../../config/config.php");
require_once("../../../../class/Connection.class.php");
require_once("../../../../class/Paciente.class.php");
require_once("../../../../class/Laboratorio.class.php");
require_once("../../../../class/Util.class.php");

$objCon = new Connection();
$objCon->db_connect();
$objPaciente = new Paciente();
$objLaboratorio = new Laboratorio();
$objUtil = new Util();

$usuario = $_SESSION['MM_Username'.SessionName];
$usuarioNombre = $_SESSION['MM_UsernameName'.SessionName];

$id_paciente = $_POST['id_paciente'];
$dau_id = $_POST['dau_id'];
$tipoMapa = $_POST['tipoMapa'];
$rce_id = $_POST['rce_id'];
$urlHemocontrol = "http://10.6.21.19/frontend_hemocontrol";
$urlCompleta = $urlHemocontrol;

if (empty($usuario)) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="utf-8">
        <script>
            alert("Su sesión ha caducado. Por favor, inicie sesión nuevamente.");
            window.location.href = "/";
        </script>
    </head>
    <body></body>
    </html>
    <?php
    exit;
}

// Obtener información del paciente para conseguir el RUT
$datosPaciente = $objPaciente->obtenerInformacionPaciente($objCon, $id_paciente);
$rutPaciente = $datosPaciente['rut'];

// Buscar PDF de laboratorio
$pdfLaboratorio = null;
$urlPDF = null;
$pdfLaboratorio = null;
$urlPDF = null;
$ruta = "http://10.6.21.29";

if (!empty($rutPaciente)) {
    $resultadoLab = $objLaboratorio->getLaboratorio1($objCon, $rutPaciente);
    if (!empty($resultadoLab) && count($resultadoLab) > 0) {
        $pdfLaboratorio = $resultadoLab[0];
        
        // Variables para la lógica condicional
        // $fecha_asd = $pdfLaboratorio['fecha_extraccion']; // o fecha_registro según corresponda
        $fecha_asd = substr($pdfLaboratorio['fecha_registro'], 0, 10);
        $fecha= $objUtil->cambiarFormatoFechaEspecial($fecha_asd);
        $infinity=$pdfLaboratorio['infinity'];
        $tecnigen=$pdfLaboratorio['contenido_base64'];

        // $infinity = $pdfLaboratorio['infinity'] ?? '';
        // $tecnigen = $pdfLaboratorio['tecnigen'] ?? '';
        // $fecha = date('Ymd', strtotime($fecha_asd));
        
// echo $fecha_asd;
        // varruir URL según las condiciones
        if ($fecha_asd > '2012-11-21' && $infinity != 'S' && $tecnigen == '') {
            // Caso 1: Formato con fecha-solicitud
            $urlPDF = $ruta . "/omega/pdf/" . $pdfLaboratorio['anio'] . "/" . $fecha . "-" . $pdfLaboratorio['solicitud_examen'] . ".PDF";
        } elseif ($fecha_asd > '2012-11-21' && $infinity != 'S' && $tecnigen == '') {
            // Caso 2: Formato solo con solicitud (sin fecha)
            $urlPDF = $ruta . "/omega/pdf/" . $pdfLaboratorio['anio'] . "/" . $pdfLaboratorio['solicitud_examen'] . ".PDF";
        } elseif ($infinity == 'S') {
            // Caso 3: Infinity
            $fechainfinity = date('Ymd', strtotime($fecha_asd));
            $urlPDF = $ruta . "/omega/pdf/" . $pdfLaboratorio['anio'] . "/" . $fechainfinity . "-" . $pdfLaboratorio['solicitud_examen'] . ".PDF";
        } elseif ($tecnigen != '') {
            // Caso 4: Tecnigen
            $fecha_=strtotime($pdfLaboratorio['fecha_solicitud']);
            $fecha_anio= date("Y",$fecha_);
            $fecha_mes= date("m",$fecha_);
            $urlPDF = "http://10.6.21.29/tecnigen_exa/" . $fecha_anio . "/" . $fecha_mes . "/" . $pdfLaboratorio['solicitud_examen'] . ".PDF?v=" . time();
        }
    }
}
// echo $urlPDF;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<style>
.iframe-transfusiones {
    width: 100%;
    height: calc(100vh - 250px);
    min-height: 600px;
    border: 0;
}
.iframe-pdf {
    width: 100%;
    height: calc(100vh - 250px);
    min-height: 600px;
    border: 0;
}
.nav-tabs .nav-link {
    color: #495057;
    font-weight: 500;
}
.nav-tabs .nav-link.active {
    color: #007bff;
    font-weight: 600;
}
.tab-content {
    padding-top: 15px;
}
</style>
</head>

<body>
<script type="text/javascript" src="<?=PATH?>/controllers/client/rce/rce/transfusiones.js?v=<?=uniqid();?>"></script>
<div id="modalTransfusiones" style="min-height:600px">
    <input type="hidden" name="id_paciente"     id="id_paciente"    value="<?= $id_paciente ?>">
    <input type="hidden" name="dau_id"          id="dau_id"         value="<?= $dau_id ?>">
    <input type="hidden" name="usuarioNombre"   id="usuarioNombre"  value="<?= $usuarioNombre ?>">
    <input type="hidden" name="rce_id"          id="rce_id"         value="<?= $rce_id ?>">
    <input type="hidden" name="usuario"         id="usuario"        value="<?= $usuario ?>">
    <input type="hidden" name="tipoMapa"        id="tipoMapa"       value="<?= $tipoMapa ?>">

    <!-- Navbar con pestañas -->
    <ul class="nav nav-tabs" id="transfusionesTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="transfusiones-tab" data-toggle="tab" href="#transfusiones" role="tab" aria-controls="transfusiones" aria-selected="true">
                <i class="fas fa-tint"></i> Transfusiones
            </a>
        </li>
        <?php if ($pdfLaboratorio): ?>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="pdf-tab" data-toggle="tab" href="#pdf" role="tab" aria-controls="pdf" aria-selected="false">
                <i class="fas fa-file-pdf"></i> Último PDF Laboratorio
            </a>
        </li>
        <?php endif; ?>
    </ul>

    <!-- Contenido de las pestañas -->
    <div class="tab-content" id="transfusionesTabContent">
        <!-- Pestaña Transfusiones -->
        <div class="tab-pane fade show active" id="transfusiones" role="tabpanel" aria-labelledby="transfusiones-tab">
            <iframe
                id="iframeHemocontrol"
                class="iframe-transfusiones"
                src="<?= htmlspecialchars($urlCompleta, ENT_QUOTES, 'UTF-8') ?>"
                allowfullscreen>
            </iframe>

            <div id="iframeHemocontrol_expirada" class="alert alert-danger" role="alert">
                <br>
                <h4 class="alert-heading">Sesión expirada</h4>
                <p>Su sesión ha caducado por motivos de seguridad.</p>
                <hr>
                <p class="mb-0">Por favor, inicie sesión nuevamente para continuar.  
                Si el problema persiste, comuníquese con la <b>Mesa de Ayuda</b>.</p>
                <br>
                <br>
                <div class="col-lg-12 text-center"> 
                    <span class="navbar-text darkcolor-barra2" style="padding:0.0rem 1rem;">
                        <svg class="svg-inline--fa fa-envelope fa-w-16 mr-2 text-dangerLight" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="envelope" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z"></path></svg><!-- <i class="fas fa-envelope mr-2 text-dangerLight"></i> -->
                        <a href="https://mail.google.com/mail?view=cm&amp;fs=1&amp;tf=1&amp;to=mesadeayuda@hjnc.cl&amp;su=Sistema%20DAURCE" target="_blank">
                            mesadeayuda@hjnc.cl
                        </a>
                        <svg class="svg-inline--fa fa-mobile-alt fa-w-10 mr-2 ml-3 text-dangerLight" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="mobile-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M272 0H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h224c26.5 0 48-21.5 48-48V48c0-26.5-21.5-48-48-48zM160 480c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32zm112-108c0 6.6-5.4 12-12 12H60c-6.6 0-12-5.4-12-12V60c0-6.6 5.4-12 12-12h200c6.6 0 12 5.4 12 12v312z"></path></svg><!-- <i class="fas fa-mobile-alt mr-2 ml-3 text-dangerLight"></i> -->584685
                        <svg class="svg-inline--fa fa-mobile-alt fa-w-10 mr-2 ml-2 text-dangerLight" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="mobile-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M272 0H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h224c26.5 0 48-21.5 48-48V48c0-26.5-21.5-48-48-48zM160 480c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32zm112-108c0 6.6-5.4 12-12 12H60c-6.6 0-12-5.4-12-12V60c0-6.6 5.4-12 12-12h200c6.6 0 12 5.4 12 12v312z"></path></svg><!-- <i class="fas fa-mobile-alt mr-2 ml-2 text-dangerLight"></i> -->584686
                        <svg class="svg-inline--fa fa-mobile-alt fa-w-10 mr-2 ml-2 text-dangerLight" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="mobile-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M272 0H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h224c26.5 0 48-21.5 48-48V48c0-26.5-21.5-48-48-48zM160 480c-17.7 0-32-14.3-32-32s14.3-32 32-32 32 14.3 32 32-14.3 32-32 32zm112-108c0 6.6-5.4 12-12 12H60c-6.6 0-12-5.4-12-12V60c0-6.6 5.4-12 12-12h200c6.6 0 12 5.4 12 12v312z"></path></svg><!-- <i class="fas fa-mobile-alt mr-2 ml-2 text-dangerLight"></i> -->584679
                    </span>
                </div>
                <br>
                <br>
            </div>
        </div>

        <!-- Pestaña PDF -->
        <?php if ($pdfLaboratorio): ?>
        <div class="tab-pane fade" id="pdf" role="tabpanel" aria-labelledby="pdf-tab">
            <div class="alert alert-info mb-2" role="alert">
                <small>
                    <strong>Información del PDF:</strong><br>
                    Servicio: <?= htmlspecialchars($pdfLaboratorio['desc_servicio']) ?><br>
                    Fecha Extracción: <?= $objUtil->fechaNormal($pdfLaboratorio['fecha_extraccion']) ?><br>
                    Solicitud: <?= htmlspecialchars($pdfLaboratorio['solicitud_examen']) ?>
                </small>
            </div>
            <iframe
                id="iframePDF"
                class="iframe-pdf"
                src="<?= htmlspecialchars($urlPDF, ENT_QUOTES, 'UTF-8') ?>"
                allowfullscreen>
            </iframe>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- <script>
$(document).ready(function() {
    var iframeHemocontrol = document.getElementById('iframeHemocontrol');

    // Bandera global: registrar el listener sólo una vez
    if (typeof window.__hemocontrolListenerRegistrado === 'undefined') {
        window.__hemocontrolListenerRegistrado = false;
    }

    var user = JSON.parse(localStorage.getItem("hjnc_user"));
    console.log(user);

    if (!user) {
        $('#iframeHemocontrol').hide();
        $('#iframeHemocontrol_expirada').show();
        return;
    }

    $('#iframeHemocontrol').show();
    $('#iframeHemocontrol_expirada').hide();

    if (!window.__hemocontrolListenerRegistrado) {
        window.__hemocontrolListenerRegistrado = true;

        window.addEventListener("message", (event) => {
            console.log("📨 mensaje recibido:", event.origin, event.data);
            if (event.origin !== 'http://10.6.21.290') return;

            if (event.data?.type === "hemocontrol_ready") {
                console.log("✅ Hemocontrol listo, enviando token");
                iframeHemocontrol.contentWindow.postMessage(
                    {
                        type: 'AUTH_TOKEN',
                        email: user.email,
                        id: user.id,
                        role: user.role,
                        roles_usuario_id: user.roles_usuario_id,
                        token: user.token,
                        username: user.username,

                        RCE_DAU_origen: 'RCEDAU',
                        RCE_DAU_id_paciente: "<?= $id_paciente ?>",
                        RCE_DAU_dau_id: "<?= $dau_id ?>",
                        RCE_DAU_usuarioNombreDau: "<?= $usuarioNombre ?>",
                        SISTEMA_RCE: "<?= $rce_id ?>",

                        RCE_DAU_usuarioDau: "<?= $usuario ?>"
                    },
                    'http://10.6.21.290'
                );
            }

            if (event.data?.type === "cerrar_iframe_hemocontrol") {
                if (event.data.success === true) {
                    var respuestaAjaxRequest = ajaxRequest(
                        `${raiz}/controllers/server/rce/indicaciones/main_controller.php`,
                        'id_solicitudTransfusion=' + event.data.id_solicitud +
                        '&dau_id=<?= $dau_id ?>&rce_id=<?= $rce_id ?>&pacId=<?= $id_paciente ?>&accion=insertarIndicaciones',
                        'POST',
                        'JSON',
                        1,
                        'Guardando indicaciones...'
                    );

                    switch (respuestaAjaxRequest.status) {
                        case "success":
                            $('#modalTransfusiones').modal('hide').data('bs.modal', null);
                            ajaxContentFast(
                                `${raiz}/views/modules/rce/medico/rce.php`,
                                'tipoMapa=<?= $tipoMapa ?>&dau_id=<?= $dau_id ?>',
                                '#contenido'
                            );
                            var texto = `
                                <div class="alert alert-light" role="alert">
                                    <h4 class="alert-heading">
                                        <i class="fas fa-check-double throb2 text-success" style="font-size:29px"></i>
                                        Éxito
                                    </h4>
                                    <hr>
                                    <p class="mb-0">
                                        La solicitud de transfusión fue registrada
                                        <b>correctamente</b>.
                                    </p>
                                </div>
                            `;
                            modalMensajNoCabecera('Éxito', texto, "#modal", "modal-md", "success");
                            break;
                        case "error":
                        default:
                            ErrorSistemaDefecto();
                            break;
                    }
                } else {
                    ErrorSistemaDefecto();
                }
            }
        });
    }
});
</script> -->

</body>
</html>