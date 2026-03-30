<?php
// $zplOriginal = "\n\nN\n\n\nA20,4,0,2,1,1,N,\"ASISTENCIA PUBLICA HIDALGO CASTILLO\"\n\r\nB50,30,0,1,2,5,95,N,\"2504170129001\"\n\r\nA80,130,0,4,1,1,N,\"2504170129001\"\n\r\nA345,30,1,2,1,1,N,\"17/04/2025\"\n\r\nA40,155,0,2,1,1,N,\"ASISTENCIA PUBLICA\"\n\r\nA325,30,1,2,1,1,N,\"19354280-8\"\n\r\nA365,30,1,2,0,1,N,\"TAPA AMARILLA\"\n\r\nA40,172,0,2,0,1,N,\"SUERO\"\n\r\nA385,30,1,2,1,1,N,\"BIOQUIMICA\"\n\r\nA40,188,0,2,1,1,N,\"ALBU, BIL.T.C, BUN.UREA, CAL, CK MBmasa, CK-TOT, CREA, ELP, FOS, GLU, LDH, LIPASA, MAGN, P HEP, PCR, PROCAL, PT.FR, TROPO\"\n\r\nP1\n\r";

// $zplOriginal = trim(str_replace(["\r", "\n"], "\n", $zplOriginal));
// $lineas = explode("\n", $zplOriginal);

// $zpl = "^XA\n";

// foreach ($lineas as $linea) {
//     $linea = trim($linea);

//     // A: Texto
//     if (preg_match('/^A(\d+),(\d+),\d+,\d+,\d+,\d+,N,"(.*?)"$/', $linea, $m)) {
//         $x = $m[1];
//         $y = $m[2];
//         $texto = $m[3];
//         $zpl .= "^FO{$x},{$y}^A0N,25,25^FD{$texto}^FS\n";
//     }

//     // B: Código de barras
//     elseif (preg_match('/^B(\d+),(\d+),\d+,\d+,\d+,\d+,\d+,N,"(.*?)"$/', $linea, $m)) {
//         $x = $m[1];
//         $y = $m[2];
//         $codigo = $m[3];
//         $zpl .= "^FO{$x},{$y}^BCN,100,Y,N,N^FD{$codigo}^FS\n";
//     }
// }

// $zpl .= "^XZ";
// $zpl = "^XA
// ^PW406
// ^LL203

// ^CF0,18
// ^FO20,10^FDASISTENCIA PUBLICA HIDALGO CASTILLO^FS

// ^BY1.4,2,100
// ^FO90,30^B3N,N,100,Y,N
// ^FD2504170131089^FS

// ^FO360,30^A0R,18,18^FDMUESTRA :^FS
// ^FO340,30^A0R,18,18^FDSUERO^FS
// ^FO320,30^A0R,18,18^FD11/04/2025^FS
// ^FO300,30^A0R,18,18^FD11111111-1^FS

// ^CF0,18
// ^FO20,155^FDASISTENCIA PUBLICA^FS
// ^FO20,175^FDPLASMA^FS
// ^FO20,195^FDAMONIO^FS

// ^XZ";

// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, "http://api.labelary.com/v1/printers/8dpmm/labels/4x6/0/");
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($ch, CURLOPT_POST, 1);
// curl_setopt($ch, CURLOPT_POSTFIELDS, $zpl);
// curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: image/png"]);

// $image = curl_exec($ch);

// if ($image === false) {
//     $error = curl_error($ch);
//     curl_close($ch);
//     die("❌ cURL error: $error");
// }

// $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
// curl_close($ch);

// if ($http_code != 200) {
//     header("Content-type: text/plain");
//     echo "❌ ERROR HTTP $http_code\n\n";
//     echo $image ? $image : "No hay contenido devuelto";
//     exit;
// }

// file_put_contents("etiqueta.png", $image);
// echo "✅ Imagen generada correctamente.<br>";
// echo '<img src="etiqueta.png" alt="Etiqueta">';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Imprimir ZPL en PDF</title>
<!-- C:\inetpub\wwwroot\php8site\RCEDAU\assets\js\browserzebra.js -->
<!-- <script src="http://10.6.21.290:8081/RCEDAU/assets/ZEBRA/browserprint-proxy.php"></script> -->
  <script src="http://10.6.21.290:8081/RCEDAU/assets/js/browserzebra.js"></script>
  <!-- <script src="http://localhost:9100/BrowserPrint.js"></script> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body>
	<?php
$epl = <<<EPL
N
q570
Q103,24
A20,4,0,2,1,1,N,"ASISTENCIA PUBLICA HIDALGO CASTILLO"
B50,30,0,1,2,5,95,N,"2504170131089"
A80,130,0,4,1,1,N,"2504170131089"
A345,30,1,2,1,1,N,"17/04/2025"
A40,155,0,2,1,1,N,"ASISTENCIA PUBLICA"
A325,30,1,2,1,1,N,"19354280-8"
A365,30,1,2,0,1,N,"TAPA LILA"
A40,172,0,2,0,1,N,"PLASMA"
A385,30,1,2,1,1,N,"BIOQUIMICA"
A40,188,0,2,1,1,N,"AMONIO"
P1
EPL;

$ip = "10.6.21.290"; // IP del Zebra Simulator
$port = 9100;

$fp = fsockopen($ip, $port, $errno, $errstr, 5);
if (!$fp) {
    die("No se pudo conectar con la Zebra: $errstr ($errno)");
}else{
	echo "si se conecta";
}
fwrite($fp, $epl);
fclose($fp);
?>


	<?php
	$epl = <<<EPL
N
q570
Q203,24
A20,4,0,2,1,1,N,"ASISTENCIA PUBLICA HIDALGO CASTILLO"
B50,30,0,1,2,5,95,N,"2504170131089"
A80,130,0,4,1,1,N,"2504170131089"
A345,30,1,2,1,1,N,"17/04/2025"
A40,155,0,2,1,1,N,"ASISTENCIA PUBLICA"
A325,30,1,2,1,1,N,"19354280-8"
A365,30,1,2,0,1,N,"TAPA LILA"
A40,172,0,2,0,1,N,"PLASMA"
A385,30,1,2,1,1,N,"BIOQUIMICA"
A40,188,0,2,1,1,N,"AMONIO"
P1
EPL;


?>
<img src="https://api.labelary.com/v1/printers/8dpmm/labels/2.25x1.25/0/<?=$epl;?>" />
  <button onclick="imprimirZPL()">Imprimir en PDF</button>
  <button onclick="imprimirEtiqueta()">Imprimir en PDF</button>
  <br><br>
    <?php
    // Funciones para generar código EPL
    function generarCodigoEpl($texto, $x, $y) {
    return "N" . $texto . "X" . $x . "," . $y . "F"; // Ejemplo muy básico
    }
    // Generar un código EPL
    $codigoEpl = generarCodigoEpl("Hola, mundo!", 10, 20);
    // Mostrar el código en la página (para pruebas)
    echo "<pre>$codigoEpl</pre>";
    // O, enviar el código a una impresora
    // header("Content-type: application/octet-stream");
    // header("Content-Disposition: inline; filename=\"etiqueta.epl\"");
    // echo $codigoEpl;
    ?>
  <img id="etiqueta" style="display:none;">
<?php
$zpl = '^XA
^PW570
^LL203
^CF0,30
^FO20,4^FDASISTENCIA PUBLICA HIDALGO CASTILLO^FS
^BY2,2,95
^FO50,30^BCN,95,Y,N,N
^FD2504170131089^FS
^FO80,130^A0N,25,25^FD2504170131089^FS
^FO345,30^A0N,25,25^FD17/04/2025^FS
^FO40,155^A0N,25,25^FDASISTENCIA PUBLICA^FS
^FO325,30^A0N,25,25^FD19354280-8^FS
^FO365,30^A0N,25,25^FDTAPA LILA^FS
^FO40,172^A0N,25,25^FDPLASMA^FS
^FO385,30^A0N,25,25^FDBIOQUIMICA^FS
^FO40,188^A0N,25,25^FDAMONIO^FS
^XZ';

$zpl_encoded = urlencode($zpl);
$url = "https://api.labelary.com/v1/printers/8dpmm/labels/2.25x1.25/0/$zpl_encoded";
?>

<img src="<?= $url ?>" alt="Vista previa etiqueta ZPL" style="border:1px solid #ccc;">
  <script>
    async function imprimirZPL() {

//       const zpl = `
// ^XA
// ^PW406
// ^LL203
// ^CF0,18
// ^FO20,10^FDASISTENCIA PUBLICA HIDALGO CASTILLO^FS
// ^BY1.4,2,100
// ^FO90,30^B3N,N,100,Y,N
// ^FD2504170131089^FS
// ^FO360,30^A0R,18,18^FDMUESTRA :^FS
// ^FO340,30^A0R,18,18^FDSUERO^FS
// ^FO320,30^A0R,18,18^FD11/04/2025^FS
// ^FO300,30^A0R,18,18^FD11111111-1^FS
// ^CF0,18
// ^FO20,155^FDASISTENCIA PUBLICA^FS
// ^FO20,175^FDPLASMA^FS
// ^FO20,195^FDAMONIO^FS
// ^XZ`;
const zpl =`N
q570
Q103,24
A20,4,0,2,1,1,N,"ASISTENCIA PUBLICA HIDALGO CASTILLO"
B50,30,0,1,2,5,95,N,"2504170131089"
A80,130,0,4,1,1,N,"2504170131089"
A345,30,1,2,1,1,N,"17/04/2025"
A40,155,0,2,1,1,N,"ASISTENCIA PUBLICA"
A325,30,1,2,1,1,N,"19354280-8"
A365,30,1,2,0,1,N,"TAPA LILA"
A40,172,0,2,0,1,N,"PLASMA"
A385,30,1,2,1,1,N,"BIOQUIMICA"
A40,188,0,2,1,1,N,"AMONIO"
P1`;
//  const zpl = `
// ^XA^PW457^LL254^CF0,16^FO40,15^FDASISTENCIA PUBLICA HIDALGO CASTILLO^FS^BY2,2,100^FO30,30^B3N,N,100,Y,NN^FD2504170131089^FS^FO390,135^A0R,16^FDMUESTRA :^FS^FO370,135^A0R,16^FDSUERO^FS^FO350,135^A0R,16^FD11/04/2025^FS^FO330,135^A0R,16^FD11111111-1^FS^CF0,16^FO40,155^FDASISTENCIA PUBLICA^FS^FO40,175^FDPLASMA^FS^FO40,195^FDAMONIO^FS^XZ`;
// ^XA
// ^PW457
// ^LL254
// ^CF0,18
// ^FO20,10^FDASISTENCIA PUBLICA HIDALGO CASTILLO^FS
// ^BY1.4,2,100
// ^FO90,30^B3N,N,100,Y,N
// ^FD2504170131089^FS
// ^FO360,30^A0R,18,18^FDMUESTRA :^FS
// ^FO340,30^A0R,18,18^FDSUERO^FS
// ^FO320,30^A0R,18,18^FD11/04/2025^FS
// ^FO300,30^A0R,18,18^FD11111111-1^FS
// ^CF0,18
// ^FO20,155^FDASISTENCIA PUBLICA^FS
// ^FO20,175^FDPLASMA^FS
// ^FO20,195^FDAMONIO^FS
// ^XZ

      const response = await fetch("http://api.labelary.com/v1/printers/8dpmm/labels/2.25x1.25/0/", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: zpl
      });

        const blob = await response.blob();
    const imageUrl = URL.createObjectURL(blob);

    const img = document.getElementById("etiqueta");
    img.src = imageUrl;
    img.style.display = "block";

    // img.onload = async () => {
    //   const { jsPDF } = window.jspdf;

    //   // Crear PDF en pulgadas reales
    //   const pdf = new jsPDF({
    //     orientation: "landscape", // Esto rota para que respete el ancho mayor
    //     unit: "in",
    //     format: [2.25, 1.25]
    //   });

    //   pdf.addImage(img, "PNG", 0, 0, 2.25, 1.25); // Ajuste exacto al tamaño del PDF
    //   pdf.save("etiqueta.pdf");
    // };
  }
</script>
<script>

function imprimirEtiqueta() {
  const imagen = document.getElementById("etiqueta");
  
  const ventana = window.open('', '_blank');
  ventana.document.write(`
    <html>
      <head>
        <title>Etiqueta</title>
        <style>
          body { margin: 0; padding: 0; text-align: center; }
          img { max-width: 100%; }
        </style>
      </head>
      <body>
        <img src="${imagen.src}" onload="window.print(); window.close();">
      </body>
    </html>
  `);
  ventana.document.close();
}
</script>
</body>
</html>
<button onclick="imprimirZPL2()">Imprimir ZPL2</button>

<script>
function imprimirZPL2() {
	let selectedPrinter = null;

BrowserPrint.getLocalDevices(function(devices) {
  console.log("Impresoras disponibles:", devices);
  selectedPrinter = devices[0]; // o filtrar por nombre
  console.log(selectedPrinter);
  // const zpl = "^XA^PW406^LL203^CF0,20^FO20,18^FDASISTENCIA PUBLICA HIDALGO CASTILLO^FS^BY1,3,2,100^FO40,45^B3N,N,100,Y,N^FD2504170131089^FS^FO360,45^A0R,18,18^FDMUESTRA :^FS^FO340,45^A0R,18,18^FDSUERO^FS^FO320,45^A0R,18,18^FD11/04/2025^FS^FO300,45^A0R,18,18^FD11111111-1^FSCF0,18^FO20,165^FDASISTENCIA PUBLICA^FS^FO20,185^FDPLASMA^FS^FO20,205^FDAMONIO^FS^XZ";

zpl =`N
q570
Q103,24
A20,4,0,2,1,1,N,"ASISTENCIA PUBLICA HIDALGO CASTILLO"
B50,30,0,1,2,5,95,N,"2504170131089"
A80,130,0,4,1,1,N,"2504170131089"
A345,30,1,2,1,1,N,"17/04/2025"
A40,155,0,2,1,1,N,"ASISTENCIA PUBLICA"
A325,30,1,2,1,1,N,"19354280-8"
A365,30,1,2,0,1,N,"TAPA LILA"
A40,172,0,2,0,1,N,"PLASMA"
A385,30,1,2,1,1,N,"BIOQUIMICA"
A40,188,0,2,1,1,N,"AMONIO"
P1`;
//   zpl =`^XA
// ^CF0,16
// ^FO70,15^FDASISTENCIA PUBLICA HIDALGO CASTILLO^FS
// ^BY1.5,1,80
// ^FO40,30^BCN,80,Y,N,N
// ^FD2504170131089^FS
// ^FO390,115^A0R,16^FDMUESTRA :^FS
// ^FO370,115^A0R,16^FDSUERO^FS
// ^FO350,115^A0R,16^FD11/04/2025^FS
// ^FO330,115^A0R,16^FD11111111-1^FS
// ^CF0,16
// ^FO40,155^FDASISTENCIA PUBLICA^FS
// ^FO40,175^FDPLASMA^FS
// ^FO40,195^FDAMONIO^FS
// ^XZ`;

  selectedPrinter.send(zpl, function() {
    console.log("Impresión enviada.");
  }, function(error) {
    console.error("Error al imprimir:", error);
  });
}, function(error) {
  console.error("No se pudieron obtener impresoras:", error);
}, "printer");


	// BrowserPrint.getDefaultDevice('printer', function(printer) {
  	// printer.send("^XA^FO50,50^ADN,36,20^FDHola Zebra!^FS^XZ");
	// });
	// console.log(typeof BrowserPrint); // debería decir: "object"

//   const zpl = `
// ^XA
// ^PW406
// ^LL203
// ^CF0,18
// ^FO20,10^FDASISTENCIA PUBLICA HIDALGO CASTILLO^FS
// ^BY0.8,1,80
// ^FO70,30^BCN,80,Y,N,N
// ^FD2504170131089^FS
// ^FO360,30^A0R,18,18^FDMUESTRA :^FS
// ^FO340,30^A0R,18,18^FDSUERO^FS
// ^FO320,30^A0R,18,18^FD11/04/2025^FS
// ^FO300,30^A0R,18,18^FD11111111-1^FS
// ^CF0,18
// ^FO20,155^FDASISTENCIA PUBLICA^FS
// ^FO20,175^FDPLASMA^FS
// ^FO20,195^FDAMONIO^FS
// ^XZ`;

//   BrowserPrint.getDefaultDevice('printer', function(printer) {
//     if (!printer) {
//       alert("No se encontró impresora.");
//       return;
//     }
//     printer.send(zpl, function() {
//       console.log("ZPL enviado correctamente.");
//     }, function(error) {
//       console.error("Error al imprimir:", error);
//     });
//   });
}
</script>