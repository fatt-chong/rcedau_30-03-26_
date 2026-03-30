<?php
class Formulario_2_Detalle {

    function InsertDetalle($objCon, $parametros) {
        $campos_validos = [
            'formulario_2_id', 'fecha', 'estado_paciente',
            'extremidad_superior', 'extremidad_inferior',
            'hidratacion', 'eliminacion', 'creado_en','usuario'
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

        $sql = "INSERT INTO dau.formulario_2_detalle (" . implode(', ', $campos) . ")
                VALUES (" . implode(', ', $valores) . ")";

        return $objCon->ejecutarSQL($sql, "ERROR al insertar en formulario_2_detalle");
    }

    function DeleteByFormulario2Id($objCon, $formulario_2_id) {
        $sql = "DELETE FROM dau.formulario_2_detalle WHERE formulario_2_id = '" . intval($formulario_2_id) . "'";
        return $objCon->ejecutarSQL($sql, "ERROR al eliminar detalles por formulario_2_id");
    }

    function SelectByFormulario2Id($objCon, $formulario_2_id) {
        $sql = "SELECT * FROM dau.formulario_2_detalle WHERE formulario_2_id = '" . intval($formulario_2_id) . "' ORDER BY fecha asc";
        return $objCon->consultaSQL($sql, "ERROR al consultar detalles por formulario_2_id");
    }
}
