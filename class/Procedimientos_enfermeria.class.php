<?php
class Procedimientos_enfermeria {

    function Insert($objCon, $parametros) {
        $campos_validos = ['nombre', 'descripcion', 'estado'];
        $campos = [];
        $valores = [];

        foreach ($campos_validos as $campo) {
            if (isset($parametros[$campo])) {
                $campos[]  = $campo;
                $valores[] = "'" . addslashes($parametros[$campo]) . "'";
            }
        }

        if (empty($campos)) return false;

        $sql = "INSERT INTO dau.procedimientos_enfermeria (" . implode(', ', $campos) . ") VALUES (" . implode(', ', $valores) . ")";
        $objCon->ejecutarSQL($sql, "ERROR al insertar procedimiento");
        return $objCon->lastInsertId();
    }

    function Update($objCon, $parametros, $id) {
        $campos_validos = ['nombre', 'descripcion', 'estado'];
        $updates = [];

        foreach ($campos_validos as $campo) {
            if (isset($parametros[$campo])) {
                $updates[] = "$campo = '" . addslashes($parametros[$campo]) . "'";
            }
        }

        if (empty($updates)) return false;

        $sql = "UPDATE dau.procedimientos_enfermeria SET " . implode(', ', $updates) . " WHERE id = '" . intval($id) . "'";
        return $objCon->ejecutarSQL($sql, "ERROR al actualizar procedimiento");
    }

    function Delete($objCon, $id) {
        $sql = "DELETE FROM dau.procedimientos_enfermeria WHERE id = '" . intval($id) . "'";
        return $objCon->ejecutarSQL($sql, "ERROR al eliminar procedimiento");
    }

    function SelectAll($objCon) {
        $sql = "SELECT * FROM dau.procedimientos_enfermeria WHERE estado = 'Activo' ORDER BY nombre";
        return $objCon->consultaSQL($sql, "ERROR al consultar procedimientos");
    }
}
?>