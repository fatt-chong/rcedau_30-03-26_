<?php
class Trazabilidad_indicaciones_enfermeria {

    function Insert($objCon, $parametros) {
        $campos_validos = ['id_paciente', 'id_indicacion_enfermeria', 'fecha', 'hora', 'usuario', 'observacion', 'estado', 'dau_id','movimiento'];
        $campos = [];
        $valores = [];

        foreach ($campos_validos as $campo) {
            if (isset($parametros[$campo])) {
                $campos[]  = $campo;
                $valores[] = "'" . addslashes($parametros[$campo]) . "'";
            }
        }

        if (empty($campos)) return false;

        $sql = "INSERT INTO dau.trazabilidad_indicaciones_enfermeria (" . implode(', ', $campos) . ") VALUES (" . implode(', ', $valores) . ")";
        $objCon->ejecutarSQL($sql, "ERROR al guardar trazabilidad de indicación");
        return $objCon->lastInsertId();
    }

    function Update($objCon, $parametros, $id_trazabilidad) {
        $campos_validos = ['id_paciente', 'id_indicacion_enfermeria', 'fecha', 'hora', 'usuario', 'observacion', 'estado', 'dau_id','movimiento'];
        $updates = [];

        foreach ($campos_validos as $campo) {
            if (isset($parametros[$campo])) {
                $updates[] = "$campo = '" . addslashes($parametros[$campo]) . "'";
            }
        }

        if (empty($updates)) return false;

        $sql = "UPDATE dau.trazabilidad_indicaciones_enfermeria SET " . implode(', ', $updates) . " WHERE id_trazabilidad = '" . intval($id_trazabilidad) . "'";
        return $objCon->ejecutarSQL($sql, "ERROR al actualizar trazabilidad");
    }

    function Delete($objCon, $id_trazabilidad) {
        $sql = "DELETE FROM dau.trazabilidad_indicaciones_enfermeria WHERE id_trazabilidad = '" . intval($id_trazabilidad) . "'";
        return $objCon->ejecutarSQL($sql, "ERROR al eliminar trazabilidad");
    }

    function SelectByDau($objCon, $dau_id) {
        $sql = "SELECT * 
                FROM dau.trazabilidad_indicaciones_enfermeria 
                WHERE dau_id = '" . intval($dau_id) . "' 
                ORDER BY fecha DESC, hora DESC";
        return $objCon->consultaSQL($sql, "ERROR al consultar trazabilidad por DAU");
    }

    function SelectByIndicacion($objCon, $id_indicacion_enfermeria) {
        $sql = "SELECT *,
                dau.estado_indicaciones_enfermeria.descripcion_estado
                FROM dau.trazabilidad_indicaciones_enfermeria
                INNER JOIN dau.estado_indicaciones_enfermeria ON dau.trazabilidad_indicaciones_enfermeria.estado = dau.estado_indicaciones_enfermeria.id_estado_ind_enf 
                WHERE id_indicacion_enfermeria = '" . intval($id_indicacion_enfermeria) . "' 
                ORDER BY fecha DESC, hora DESC";
        return $objCon->consultaSQL($sql, "ERROR al consultar trazabilidad por indicación");
    }

    function SelectById($objCon, $id_trazabilidad) {
        $sql = "SELECT * 
                FROM dau.trazabilidad_indicaciones_enfermeria 
                WHERE id_trazabilidad = '" . intval($id_trazabilidad) . "'";
        return $objCon->consultaSQL($sql, "ERROR al consultar trazabilidad por ID");
    }
}
?>
