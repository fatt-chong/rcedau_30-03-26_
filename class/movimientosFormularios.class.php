<?php
class movimientosFormularios {

    function InsertmovimientosFormularios($objCon, $parametros) {
        $campos_validos = [
            'formulario_nombre',
            'formulario_id',
            'tipo_accion',
            'campos_afectados',
            'ip_origen',
            'dau_id',
            'usuario'
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

        $sql = "INSERT INTO dau.movimientosFormularios (" . implode(', ', $campos) . ")
                VALUES (" . implode(', ', $valores) . ")";

        return $objCon->ejecutarSQL($sql, "ERROR al insertar en movimientosFormularios");
    }

    function SelectByFormularioIdmovimientosFormularios($objCon, $formulario_nombre, $formulario_id) {
        $sql = "SELECT * FROM dau.movimientosFormularios 
                WHERE formulario_nombre = '" . addslashes($formulario_nombre) . "' 
                  AND formulario_id = '" . intval($formulario_id) . "'
                ORDER BY creado_en DESC";

        return $objCon->consultaSQL($sql, "ERROR al consultar bitácora por formulario");
    }

    function SelectByIdmovimientosFormularios($objCon, $id) {
        $sql = "SELECT * FROM dau.movimientosFormularios WHERE id = '" . intval($id) . "'";
        return $objCon->consultaSQL($sql, "ERROR al consultar bitácora por ID");
    }

    function DeletemovimientosFormularios($objCon, $id) {
        $sql = "DELETE FROM dau.movimientosFormularios WHERE id = '" . intval($id) . "'";
        return $objCon->ejecutarSQL($sql, "ERROR al eliminar bitácora");
    }
}