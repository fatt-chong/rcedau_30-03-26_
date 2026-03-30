<?php
class Formulario_3_Dosis {

    function InsertDosis($objCon, $parametros) {
        $campos_validos = [
            'formulario_3_id', 'numero_dosis', 'fecha_aplicacion',
            'citacion_vacuna', 'creado_en', 'creado_usuario'
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

        $sql = "INSERT INTO dau.formulario_3_dosis (" . implode(', ', $campos) . ")
                VALUES (" . implode(', ', $valores) . ")";

        return $objCon->ejecutarSQL($sql, "ERROR al insertar en formulario_3_dosis");
    }

    function UpdateDosis($objCon, $parametros, $id) {
        $campos_validos = [
            'formulario_3_id', 'numero_dosis', 'fecha_aplicacion',
            'citacion_vacuna', 'modificado_en', 'modificado_usuario'
        ];

        $updates = [];

        foreach ($campos_validos as $campo) {
            if (isset($parametros[$campo]) && $parametros[$campo] !== '') {
                $updates[] = "$campo = '" . addslashes($parametros[$campo]) . "'";
            }
        }

        if (empty($updates)) return false;

        $sql = "UPDATE dau.formulario_3_dosis
                SET " . implode(', ', $updates) . "
                WHERE id = '" . intval($id) . "'";

        return $objCon->ejecutarSQL($sql, "ERROR al actualizar formulario_3_dosis");
    }

    function DeleteDosis($objCon, $id) {
        $sql = "DELETE FROM dau.formulario_3_dosis WHERE id = '" . intval($id) . "'";
        return $objCon->ejecutarSQL($sql, "ERROR al eliminar formulario_3_dosis");
    }

    function SelectByFormulario3Id($objCon, $formulario_3_id) {
        $sql = "SELECT * FROM dau.formulario_3_dosis WHERE formulario_3_id = '" . intval($formulario_3_id) . "' ORDER BY numero_dosis ASC";
        return $objCon->consultaSQL($sql, "ERROR al consultar formulario_3_dosis por formulario_3_id");
    }

    function DeleteByFormulario3Id($objCon, $formulario_3_id) {
        $sql = "DELETE FROM dau.formulario_3_dosis WHERE formulario_3_id = '" . intval($formulario_3_id) . "'";
        return $objCon->ejecutarSQL($sql, "ERROR al eliminar dosis por formulario_3_id");
    }
}