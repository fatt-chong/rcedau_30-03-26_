<?php
class Formulario_3 {

    function InsertFormulario_3($objCon, $parametros) {
        $campos_validos = [
            'dau_id', 'nombre_paciente', 'apellidos_paciente', 'edad_paciente',
            'direccion_paciente', 'consultorio', 'fecha_registro', 'animal_mordedor',
            'animal_provocado', 'animal_no_provocado', 'ubicable', 'no_ubicable',
            'observaciones', 'nombre_medico', 'creado_en', 'creado_usuario'
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

        $sql = "INSERT INTO dau.formulario_3 (" . implode(', ', $campos) . ")
                VALUES (" . implode(', ', $valores) . ")";

        $objCon->ejecutarSQL($sql, "ERROR al insertar en formulario_3");

        return $objCon->lastInsertId();
    }

    function UpdateFormulario_3($objCon, $parametros, $id) {
        $campos_validos = [
            'dau_id', 'nombre_paciente', 'apellidos_paciente', 'edad_paciente',
            'direccion_paciente', 'consultorio', 'fecha_registro', 'animal_mordedor',
            'animal_provocado', 'animal_no_provocado', 'ubicable', 'no_ubicable',
            'observaciones', 'nombre_medico', 'modificado_en', 'modificado_usuario'
        ];

        $updates = [];

        foreach ($campos_validos as $campo) {
            if (isset($parametros[$campo]) && $parametros[$campo] !== '') {
                $updates[] = "$campo = '" . addslashes($parametros[$campo]) . "'";
            }
        }

        if (empty($updates)) return false;

        $sql = "UPDATE dau.formulario_3
                SET " . implode(', ', $updates) . "
                WHERE id = '" . intval($id) . "'";

        return $objCon->ejecutarSQL($sql, "ERROR al actualizar formulario_3");
    }

    function DeleteFormulario_3($objCon, $id) {
        $sql = "DELETE FROM dau.formulario_3 WHERE id = '" . intval($id) . "'";
        return $objCon->ejecutarSQL($sql, "ERROR al eliminar formulario_3");
    }

    function SelectByDauFormulario_3($objCon, $dau_id) {
        $sql = "SELECT * FROM dau.formulario_3 WHERE dau_id = '" . intval($dau_id) . "' ORDER BY creado_en DESC";
        return $objCon->consultaSQL($sql, "ERROR al consultar formulario_3 por dau_id");
    }

    function SelectByIdFormulario_3($objCon, $id) {
        $sql = "SELECT * FROM dau.formulario_3 WHERE id = '" . intval($id) . "'";
        return $objCon->consultaSQL($sql, "ERROR al consultar formulario_3 por ID");
    }
}