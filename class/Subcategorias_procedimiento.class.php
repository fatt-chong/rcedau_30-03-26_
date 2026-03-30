<?php
class Subcategorias_procedimiento {

    function Insert($objCon, $parametros) {
        $campos_validos = ['procedimiento_id', 'nombre', 'detalle', 'orden'];
        $campos = [];
        $valores = [];

        foreach ($campos_validos as $campo) {
            if (isset($parametros[$campo])) {
                $campos[]  = $campo;
                $valores[] = "'" . addslashes($parametros[$campo]) . "'";
            }
        }

        if (empty($campos)) return false;

        $sql = "INSERT INTO dau.subcategorias_procedimiento (" . implode(', ', $campos) . ") VALUES (" . implode(', ', $valores) . ")";
        $objCon->ejecutarSQL($sql, "ERROR al insertar subcategoría");
        return $objCon->lastInsertId();
    }

    function Update($objCon, $parametros, $id) {
        $campos_validos = ['procedimiento_id', 'nombre', 'detalle', 'orden'];
        $updates = [];

        foreach ($campos_validos as $campo) {
            if (isset($parametros[$campo])) {
                $updates[] = "$campo = '" . addslashes($parametros[$campo]) . "'";
            }
        }

        if (empty($updates)) return false;

        $sql = "UPDATE dau.subcategorias_procedimiento SET " . implode(', ', $updates) . " WHERE id = '" . intval($id) . "'";
        return $objCon->ejecutarSQL($sql, "ERROR al actualizar subcategoría");
    }

    function Delete($objCon, $id) {
        $sql = "DELETE FROM dau.subcategorias_procedimiento WHERE id = '" . intval($id) . "'";
        return $objCon->ejecutarSQL($sql, "ERROR al eliminar subcategoría");
    }

    function SelectByProcedimiento($objCon, $procedimiento_id) {
        $sql = "SELECT * FROM dau.subcategorias_procedimiento WHERE procedimiento_id = '" . intval($procedimiento_id) . "' ORDER BY orden";
        return $objCon->consultaSQL($sql, "ERROR al consultar subcategorías");
    }

    function SelectById($objCon, $id) {
        $sql = "SELECT * FROM dau.subcategorias_procedimiento WHERE id = '" . intval($id) . "'";
        return $objCon->consultaSQL($sql, "ERROR al consultar subcategoría por ID");
    }
}
?>
