<?php
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['zpl'])) {
    header("Content-Type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"etiqueta.zpl\"");
    echo $data['zpl'];
}