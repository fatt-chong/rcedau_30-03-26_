<?php
// CONFIGURACIÓN
$ip_zebra = "192.168.0.100";     // IP del simulador Zebra
$puerto_zebra = 9100;            // Puerto TCP (normalmente 9100)
$ruta_bmp = "/mnt/etiquetas/ultima.bmp";  // Carpeta compartida (montada en servidor)
$ruta_png = "etiqueta.png";     // PNG convertido para mostrar

$mensaje = "";
$mostrar = false;

// === Enviar EPL ===
if (isset($_POST['imprimir'])) {
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

    // Enviar a Zebra
    $fp = @fsockopen($ip_zebra, $puerto_zebra, $errno, $errstr, 5);
    if (!$fp) {
        $mensaje = "Error de conexión con Zebra: $errstr ($errno)";
    } else {
        fwrite($fp, $epl);
        fclose($fp);
        sleep(2); // Espera que Zebra termine de procesar

        // Convertir BMP a PNG
        if (file_exists($ruta_bmp)) {
            $img = imagecreatefrombmp($ruta_bmp);
            imagepng($img, $ruta_png);
            imagedestroy($img);
            $mensaje = "Etiqueta generada correctamente.";
            $mostrar = true;
        } else {
            $mensaje = "No se encontró el archivo BMP generado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Impresión EPL a Imagen</title>
</head>
<body style="font-family: sans-serif; padding: 20px;">

<h2>📦 Impresión Zebra desde EPL (Servidor Web)</h2>

<form method="POST">
    <button type="submit" name="imprimir">🖨️ Imprimir etiqueta</button>
</form>

<p><?= $mensaje ?></p>

<?php if ($mostrar): ?>
    <h3>🖼️ Imagen Generada</h3>
    <img src="<?= $ruta_png ?>?t=<?= time() ?>" alt="Etiqueta" style="border:1px solid #000;">

    <h3>📄 Descargar como PDF</h3>
    <button onclick="descargarPDF()">Descargar PDF</button>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        async function descargarPDF() {
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF({ unit: "mm", format: [50.8, 25.4] }); // 2"x1"
            const img = new Image();
            img.onload = () => {
                pdf.addImage(img, "PNG", 0, 0, 50.8, 25.4);
                pdf.save("etiqueta.pdf");
            };
            img.src = "<?= $ruta_png ?>?t=<?= time() ?>";
        }
    </script>
<?php endif; ?>

</body>
</html>