<?php
class Formulario_2 {

    function InsertFormulario_2($objCon, $parametros) {
        $campos_validos = [
            'dau_id', 'nombre_paciente', 'ficha_numero', 'fecha', 'hora',
            'agitado', 'violento_agresivo', 'impulsividad', 'verbal', 'ambiental', 'farmacologica', 'otra_contencion', 'medios_fracasados_otro',
            'administracion_farmacos', 'hora_retiro_contencion', 'observaciones',
            'registros_horarios', 'creado_en', 'creado_usuario'
        ];

        $campos = [];
        $valores = [];

        foreach ($campos_validos as $campo) {
            if (isset($parametros[$campo]) && $parametros[$campo] !== '') {
                $campos[] = $campo;
                $valores[] = "'" . addslashes($parametros[$campo]) . "'";
            }
        }

        if (empty($campos)) return false;

        $sql = "INSERT INTO dau.formulario_2 (" . implode(', ', $campos) . ")
                VALUES (" . implode(', ', $valores) . ")";

        $objCon->ejecutarSQL($sql, "ERROR al insertar en formulario_2");

        return $objCon->lastInsertId();
    }

    function UpdateFormulario_2($objCon, $parametros, $id) {
        $campos_validos = [
            'dau_id', 'nombre_paciente', 'ficha_numero', 'fecha', 'hora',
            'agitado', 'violento_agresivo', 'impulsividad', 'verbal', 'ambiental', 'farmacologica', 'otra_contencion', 'medios_fracasados_otro',
            'administracion_farmacos', 'hora_retiro_contencion', 'observaciones',
            'registros_horarios', 'modificado_en', 'modificado_usuario'
        ];

        $updates = [];

        foreach ($campos_validos as $campo) {
            if (isset($parametros[$campo]) && $parametros[$campo] !== '') {
                $updates[] = "$campo = '" . addslashes($parametros[$campo]) . "'";
            }
        }

        if (empty($updates)) return false;

        $sql = "UPDATE dau.formulario_2
                SET " . implode(', ', $updates) . "
                WHERE id = '" . intval($id) . "'";

        return $objCon->ejecutarSQL($sql, "ERROR al actualizar formulario_2");
    }

    function DeleteFormulario_2($objCon, $id) {
        $sql = "DELETE FROM dau.formulario_2 WHERE id = '" . intval($id) . "'";
        return $objCon->ejecutarSQL($sql, "ERROR al eliminar formulario_2");
    }

    function SelectByDauFormulario_2($objCon, $dau_id) {
        $sql = "SELECT * FROM dau.formulario_2 WHERE dau_id = '" . intval($dau_id) . "' ORDER BY creado_en DESC";
        return $objCon->consultaSQL($sql, "ERROR al consultar formulario_2 por dau_id");
    }

    function SelectByIdFormulario_2($objCon, $id) {
        $sql = "SELECT * FROM dau.formulario_2 WHERE id = '" . intval($id) . "'";
        return $objCon->consultaSQL($sql, "ERROR al consultar formulario_2 por ID");
    }
}
