<?php
class cabecera_formularios {

    function InsertCabecera_formularios($objCon, $parametros) {
        $campos_validos = ['destripcion_formulario',  'estado_formulario'];
        $campos = [];
        $valores = [];

        foreach ($campos_validos as $campo) {
            if (isset($parametros[$campo])) {
                $campos[]  = $campo;
                $valores[] = "'" . addslashes($parametros[$campo]) . "'";
            }
        }

        if (empty($campos)) return false;

        $sql = "INSERT INTO dau.cabecera_formularios (" . implode(', ', $campos) . ") VALUES (" . implode(', ', $valores) . ")";
        $objCon->ejecutarSQL($sql, "ERROR al insertar procedimiento");
        return $objCon->lastInsertId();
    }

    function UpdateCabecera_formularios($objCon, $parametros, $id_formulario) {
        $campos_validos = ['destripcion_formulario', 'estado_formulario'];
        $updates = [];

        foreach ($campos_validos as $campo) {
            if (isset($parametros[$campo])) {
                $updates[] = "$campo = '" . addslashes($parametros[$campo]) . "'";
            }
        }

        if (empty($updates)) return false;

        $sql = "UPDATE dau.cabecera_formularios SET " . implode(', ', $updates) . " WHERE id_formulario = '" . intval($id_formulario) . "'";
        return $objCon->ejecutarSQL($sql, "ERROR al actualizar procedimiento");
    }

    function DeleteCabecera_formularios($objCon, $id_formulario) {
        $sql = "DELETE FROM dau.cabecera_formularios WHERE id_formulario = '" . intval($id_formulario) . "'";
        return $objCon->ejecutarSQL($sql, "ERROR al eliminar procedimiento");
    }

    function SelectAllCabecera_formularios($objCon) {
        $sql = "SELECT * FROM dau.cabecera_formularios WHERE estado_formulario = 'A' ORDER BY destripcion_formulario";
        return $objCon->consultaSQL($sql, "ERROR al consultar procedimientos");
    }
}
?>