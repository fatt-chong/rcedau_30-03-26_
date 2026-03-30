<?php
class Cabecera_indicaciones_enfermeria {

    function Insert($objCon, $parametros) {
        $campos_validos = [
            'dau_id', 'usuario', 'fecha', 'hora', 'observacion', 'estado', 'creado_en','tipo_indicacion',
        ];

        $campos  = [];
        $valores = [];

        foreach ($campos_validos as $campo) {
            if (isset($parametros[$campo])) {
                $campos[]  = $campo;
                $valores[] = "'" . addslashes($parametros[$campo]) . "'";
            }
        }

        if (empty($campos)) return false;

        $sql = "INSERT INTO dau.cabecera_indicaciones_enfermeria (" . implode(', ', $campos) . ")
                VALUES (" . implode(', ', $valores) . ")";
        
        $objCon->ejecutarSQL($sql, "ERROR al insertar en cabecera_indicaciones_enfermeria");

        return $objCon->lastInsertId();
    }

    function Update($objCon, $parametros, $id) {
        $campos_validos = [
            'dau_id', 'usuario', 'fecha', 'hora', 'observacion', 'estado', 'creado_en'
        ];

        $updates = [];

        foreach ($campos_validos as $campo) {
            if (isset($parametros[$campo])) {
                $updates[] = "$campo = '" . addslashes($parametros[$campo]) . "'";
            }
        }

        if (empty($updates)) return false;

        $sql = "UPDATE dau.cabecera_indicaciones_enfermeria
                SET " . implode(', ', $updates) . "
                WHERE id = '" . intval($id) . "'";

        return $objCon->ejecutarSQL($sql, "ERROR al actualizar cabecera_indicaciones_enfermeria");
    }

    function Delete($objCon, $id) {
        $sql = "DELETE FROM dau.cabecera_indicaciones_enfermeria WHERE id = '" . intval($id) . "'";
        return $objCon->ejecutarSQL($sql, "ERROR al eliminar cabecera_indicaciones_enfermeria");
    }

    function SelectByDau($objCon, $dau_id) {
        $sql = "SELECT * FROM dau.cabecera_indicaciones_enfermeria WHERE dau_id = '" . intval($dau_id) . "' ORDER BY fecha DESC, hora DESC";
        return $objCon->fetchAll($sql, "ERROR al consultar cabecera_indicaciones_enfermeria por dau_id");
    }

    function SelectById($objCon, $id) {
        $sql = "SELECT * FROM dau.cabecera_indicaciones_enfermeria WHERE id = '" . intval($id) . "'";
        return $objCon->fetchRow($sql, "ERROR al consultar cabecera_indicaciones_enfermeria por ID");
    }
}
