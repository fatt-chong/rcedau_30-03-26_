<?php
error_reporting(0);
session_start();
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
$version                    = $objUtil->versionJS();
?>
<script src="../RCEDAU/assets/js/browserzebra.js"></script>
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
                <td class="mifuente12" width="10%"><?= htmlspecialchars($eti['eti_id']) ?></td>
                <td class="mifuente12"><?= htmlspecialchars($eti['eti_examenes_etiqueta']) ?></td>
                <td width="15%">
                    <button class="btn btn-sm btn-primary mifuente12" onclick="descargarZPL('<?= $zplEncoded ?>', '<?= $eti['eti_codigo'] ?>')">Descargar</button>
                    <button class="btn btn-sm btn-success mifuente12" onclick="imprimirEPL('<?= $zplEncoded ?>', '<?= $eti['eti_codigo'] ?>')">Imprimir</button>
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
function descargarZPL(base64, nombreArchivo) {
    const zplbruto = base64ToUtf8(base64);
    var zplDinamico = limpiarZPL(zplbruto);
    console.log(zplDinamico)
    fetch("views/modules/enfermera/imprimir.php", {
        method: "POST",
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ zpl: zplDinamico })
    })
    .then(res => res.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = "codigo.zpl";
        document.body.appendChild(a);
        a.click();
        a.remove();
    });
}

// function imprimirEPL(base64, nombreArchivo) {
//     const zplbruto = base64ToUtf8(base64);
//     const zplDinamico = limpiarZPL(zplbruto);
//     console.log(zplDinamico);

//     const width = 400;
//     const height = 200;
//     const left = (screen.width / 2) - (width / 2);
//     const top = (screen.height / 2) - (height / 2);

//     const win = window.open(
//         "http://localhost/Zebra/imprimir_red.php?zpl=" + encodeURIComponent(zplDinamico),
//         "_blank",
//         `width=${width},height=${height},top=${top},left=${left},resizable=no,scrollbars=no`
//     );
// }
function imprimirEPL(base64, nombreArchivo) {
    const zplbruto = base64ToUtf8(base64);
    const zplDinamico = limpiarZPL(zplbruto);
    console.log(zplDinamico);

    const width = 400;
    const height = 200;
    const left = (screen.width / 2) - (width / 2);
    const top = (screen.height / 2) - (height / 2);

    const win = window.open(
        "http://localhost/Zebra/imprimir_red_html.html?zpl=" + encodeURIComponent(zplDinamico),
        "_blank",
        `width=${width},height=${height},top=${top},left=${left},resizable=no,scrollbars=no`
    );
}
</script>