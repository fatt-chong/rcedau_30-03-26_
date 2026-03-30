<?php
class Formulario_1 {

    function InsertFormulario_1($objCon, $parametros) {
        $campos_validos = [
            'dau_id', 'nombre_medico', 'rut_medico', 'rut_medico_hidden',
            'nombre_paciente', 'rut_paciente', 'aseguradora', 'comuna_region',
            'direccion_paciente', 'telefono_fijo', 'telefono_celular', 'email',
            'cie10', 'cie10_Codigo', 'fecha_notificacion', 'hora_notificacion',
            'confirmacion_diagnostico', 'paciente_tratamiento',
            'nombre_representante', 'rut_representante', 'telefono_representante',
            'celular_representante', 'email_representante', 'creado_en', 'creado_usuario'
        ];

        $campos = [];
        $valores = [];

        foreach ($campos_validos as $campo) {
             if (isset($parametros[$campo]) && $parametros[$campo] !== '') {
                $campos[]  = $campo;
                $valores[] = "'" . addslashes($parametros[$campo]) . "'";
            }
        }

        if (empty($campos)) return false;

        $sql = "INSERT INTO dau.formulario_1 (" . implode(', ', $campos) . ")
                VALUES (" . implode(', ', $valores) . ")";

        $objCon->ejecutarSQL($sql, "ERROR al insertar en formulario_1");

        return $objCon->lastInsertId();
    }

    function UpdateFormulario_1($objCon, $parametros, $id) {
        $campos_validos = [
            'dau_id', 'nombre_medico', 'rut_medico', 'rut_medico_hidden',
            'nombre_paciente', 'rut_paciente', 'aseguradora', 'comuna_region',
            'direccion_paciente', 'telefono_fijo', 'telefono_celular', 'email',
            'cie10', 'cie10_Codigo', 'fecha_notificacion', 'hora_notificacion',
            'confirmacion_diagnostico', 'paciente_tratamiento',
            'nombre_representante', 'rut_representante', 'telefono_representante',
            'celular_representante', 'email_representante', 'modificado_fecha','modificado_usuario'
        ];

        $updates = [];

        foreach ($campos_validos as $campo) {
            if (isset($parametros[$campo]) && $parametros[$campo] !== '') {
                $updates[] = "$campo = '" . addslashes($parametros[$campo]) . "'";
            }
        }

        if (empty($updates)) return false;

        $sql = "UPDATE dau.formulario_1
                SET " . implode(', ', $updates) . "
                WHERE id = '" . intval($id) . "'";

        return $objCon->ejecutarSQL($sql, "ERROR al actualizar formulario_1");
    }

    function DeleteFormulario_1($objCon, $id) {
        $sql = "DELETE FROM dau.formulario_1 WHERE id = '" . intval($id) . "'";
        return $objCon->ejecutarSQL($sql, "ERROR al eliminar formulario_1");
    }

    function SelectByDauFormulario_1($objCon, $dau_id) {
        $sql = "SELECT * FROM dau.formulario_1 WHERE dau_id = '" . intval($dau_id) . "' ORDER BY creado_en DESC";
        return $objCon->consultaSQL($sql, "ERROR al consultar formulario_1 por dau_id");
    }

    function SelectByIdFormulario_1($objCon, $id) {
        $sql = "SELECT * FROM dau.formulario_1 WHERE id = '" . intval($id) . "'";
        return $objCon->consultaSQL($sql, "ERROR al consultar formulario_1 por ID");
    }
}