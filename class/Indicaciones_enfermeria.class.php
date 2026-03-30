<?php
class Indicaciones_enfermeria {

    function Insert($objCon, $parametros) {
        $campos_validos = ['procedimiento_id', 'subcategoria_id', 'comentario', 'usuario', 'fecha', 'hora', 'dau_id','estado', 'id_cabecera_indicaciones_enfermeria','tipo_indicacion'];
        $campos = [];
        $valores = [];

        foreach ($campos_validos as $campo) {
            if (isset($parametros[$campo])) {
                $campos[]  = $campo;
                $valores[] = "'" . addslashes($parametros[$campo]) . "'";
            }
        }

        if (empty($campos)) return false;

        $sql = "INSERT INTO dau.indicaciones_enfermeria (" . implode(', ', $campos) . ") VALUES (" . implode(', ', $valores) . ")";
        $objCon->ejecutarSQL($sql, "ERROR al guardar procedimiento realizado");
        return $objCon->lastInsertId();
    }

    function Update($objCon, $parametros, $id) {
        $campos_validos = ['procedimiento_id', 'subcategoria_id', 'comentario', 'usuario', 'fecha', 'hora', 'dau_id', 'estado'];
        $updates = [];

        foreach ($campos_validos as $campo) {
            if (isset($parametros[$campo])) {
                $updates[] = "$campo = '" . addslashes($parametros[$campo]) . "'";
            }
        }

        if (empty($updates)) return false;

        $sql = "UPDATE dau.indicaciones_enfermeria SET " . implode(', ', $updates) . " WHERE id = '" . intval($id) . "'";
        return $objCon->ejecutarSQL($sql, "ERROR al actualizar procedimiento realizado");
    }

    function Delete($objCon, $id) {
        $sql = "DELETE FROM dau.indicaciones_enfermeria WHERE id = '" . intval($id) . "'";
        return $objCon->ejecutarSQL($sql, "ERROR al eliminar procedimiento realizado");
    }

    function SelectByDau($objCon, $dau_id) {
        $sql = "SELECT pr.*, 
                       p.nombre AS procedimiento_nombre, 
                       s.nombre AS subcategoria_nombre 
                FROM dau.indicaciones_enfermeria pr
                JOIN dau.procedimientos_enfermeria p ON p.id = pr.procedimiento_id
                JOIN dau.subcategorias_procedimiento s ON s.id = pr.subcategoria_id
                WHERE pr.dau_id = '" . intval($dau_id) . "'
                ORDER BY pr.fecha, pr.hora";
        return $objCon->consultaSQL($sql, "ERROR al consultar procedimientos realizados");
    }

    function SelectById($objCon, $id) {
        $sql = "SELECT * FROM dau.indicaciones_enfermeria WHERE id = '" . intval($id) . "'";
        return $objCon->consultaSQL($sql, "ERROR al consultar procedimiento realizado por ID");
    }
}
?>
