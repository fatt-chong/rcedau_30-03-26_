<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);
    $zpl = $input['zpl'] ?? '';

    $printerIp = "10.6.21.22";
    $printerPort = 9100;

    try {
        $fp = fsockopen($printerIp, $printerPort, $errno, $errstr, 10);
        if (!$fp) {
            http_response_code(500);
            echo "Error al conectar: $errstr ($errno)";
        } else {
            fwrite($fp, $zpl);
            fclose($fp);
            echo "Impresión enviada correctamente";
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo "Excepción: " . $e->getMessage();
    }
}