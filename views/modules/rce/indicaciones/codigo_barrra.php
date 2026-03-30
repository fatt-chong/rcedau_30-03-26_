<?php
error_reporting(0);
session_start();
// error_reporting(0);
$permisos = $_SESSION["permisosDAU"];

require("../../../../config/config.php");
require_once ("../../../../class/Util.class.php");             $objUtil            = new Util;
require_once("../../../../class/Connection.class.php");        $objCon             = new Connection();         $objCon->db_connect();
require_once("../../../../class/RegistroClinico.class.php");   $objRegistroClinico = new RegistroClinico;
require_once('../../../../class/Imagenologia.class.php');      $objRayos           = new Imagenologia;
require_once('../../../../class/Laboratorio.class.php');       $objLaboratorio     = new Laboratorio;
require_once('../../../../class/Dau.class.php');               $objDau             = new Dau;


$parametros                 = $objUtil->getFormulario($_POST);
$parametros['eti_codigo']   = $parametros['solicitud_examen'];
$listadoExaLab              = $objLaboratorio->SelectEtiqueta($objCon,$parametros);
// print('<pre>'); print_r($listadoExaLab); print('</pre>');
$version = $objUtil->versionJS();
?>
<script src="../RCEDAU/assets/js/browserzebra.js"></script>
<!-- <script src="http://localhost:9100/BrowserPrint.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<table class="table table-bordered">
    <thead>
        <tr>
            <th width="10%">Indicador</th>
            <th>Etiqueta</th>
            <th width="15%">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($listadoExaLab as $index => $eti): 

            $zplEncoded = base64_encode($eti['eti_barcode']);

        ?>
            <tr>
                <td width="10%"><?= htmlspecialchars($eti['eti_id']) ?></td>
                <td><?= htmlspecialchars($eti['eti_examenes_etiqueta']) ?></td>
                <td width="15%">
                    <button class="btn btn-sm btn-primary mifuente12" onclick="descargarZPL('<?= $zplEncoded ?>', '<?= $eti['eti_codigo'] ?>')">Descargar</button>
                    <button class="btn btn-sm btn-success mifuente12" onclick="imprimirZPL('<?= $zplEncoded ?>')">Imprimir</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script type="text/javascript">
function base64ToUtf8(b64) {
    return decodeURIComponent(escape(atob(b64)));
}
function limpiarZPL(zplBruto) {
  let zplLimpio = zplBruto
    .replace(/\\n/g, "\n")     // \n literal (doble backslash)
    .replace(/\\r\\n/g, "\r\n")// \r\n literal
    .replace(/\\u00a4/g, "ñ")  // u00a4 → ñ (reemplaza según tu necesidad real)
    .replace(/\\\//g, "/")     // \/ → /
    .replace(/\\"/g, "\"")     // \" → "
    .replace(/^\s+|\s+$/g, ""); // trim
  try {
    zplLimpio = JSON.parse('"' + zplLimpio + '"');
  } catch (e) {
  }
  return zplLimpio;
}
function imprimirZPL(base64, nombreArchivo) {
    const zplbruto = base64ToUtf8(base64);
    var zplDinamico = limpiarZPL(zplbruto);
    console.log(zplDinamico);
    fetch("views/modules/rce/indicaciones/imprimir_red.php", {
        method: "POST",
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ zpl: zplDinamico })
    })
    .then(res => res.text())
    .then(resp => alert(resp))
    .catch(err => alert("Error al imprimir: " + err));
}
// function imprimirZPL(base64, nombreArchivo) {
//     const zplbruto = base64ToUtf8(base64);
//     var zplDinamico = limpiarZPL(zplbruto);
//     console.log(zplDinamico);
//     BrowserPrint.getLocalDevices(function(devices) {
//         console.log("Impresoras disponibles:", devices);
//         const impresoras = devices.printer;
//         console.log("Impresoras disponibles:", impresoras);
//         if (impresoras.length === 0) {
//         console.warn("No hay impresoras disponibles.");
//         return;
//         }
//         const selectedPrinter = impresoras[0];
//         console.log(selectedPrinter)
//         selectedPrinter.send(zplDinamico, 
//         function() {
//             console.log("Etiqueta enviada a la impresora.");
//         }, 
//         function(error) {
//             console.error("Error al imprimir:", error);
//         });
//     });
// }
function descargarZPL(base64, nombreArchivo) {
    const zplbruto = base64ToUtf8(base64);
    var zplDinamico = limpiarZPL(zplbruto);
    console.log(zplDinamico)
    window.location.href = "http://localhost/Zebra/imprimir_red.php?zpl=" + encodeURIComponent(zplDinamico);

    // C:\inetpub\wwwroot\Zebra\imprimir_red.php
    // fetch("http://10.6.21.290/Zebra/imprimir_red.php", {
    //     method: "POST",
    //     headers: {
    //         'Content-Type': 'application/json'
    //     },
    //     body: JSON.stringify({ zpl: zplDinamico })
    // })
    // .then(res => res.json())
    // .then(data => {
    //     alert(data.mensaje);
    // })
    // .catch(err => {
    //     alert("Error de red o CORS");
    //     console.error(err);
    // });

    // fetch("http://localhost/Zebra/imprimir_red.php", {
    //     method: "POST",
    //     headers: { 'Content-Type': 'application/json' },
    //     body: JSON.stringify({ zpl: zplDinamico })
    // })
    // .then(res => res.blob())
    // .then(blob => {
    //     const url = window.URL.createObjectURL(blob);
    //     const a = document.createElement("a");
    //     a.href = url;
    //     a.download = "codigo.zpl";
    //     document.body.appendChild(a);
    //     a.click();
    //     a.remove();
    // });
}
</script>