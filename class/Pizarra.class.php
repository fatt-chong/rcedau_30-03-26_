<?php
class Pizarra {

    function InsertPizarra($objCon, $parametros) {
        $campos_validos = [
            'usuario_crea', 'fecha_crea', 'hora_crea', 'idTipoHorarioTurno'
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
        $sql = "INSERT INTO dau.pizarra (" . implode(', ', $campos) . ")
                VALUES (" . implode(', ', $valores) . ")";
        $objCon->ejecutarSQL($sql, "ERROR al insertar en Pizarra");
        return $objCon->lastInsertId();
    }
    function InsertPizarradetalle($objCon, $parametros) {
        $campos_validos = [
            'id_pizarra', 'seccion_id', 'seccion_nombre', 'rol', 'rut_profesional', 'nombre_profesional'
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
        $sql = "INSERT INTO dau.pizarra_detalle (" . implode(', ', $campos) . ")
                VALUES (" . implode(', ', $valores) . ")";
        $objCon->ejecutarSQL($sql, "ERROR al insertar en pizarra_detalle");
        return $objCon->lastInsertId();
    }

    function DeletePizarradetalle($objCon, $id_pizarra) {
        $sql = "DELETE FROM dau.pizarra_detalle WHERE id_pizarra = '" . intval($id_pizarra) . "'";
        return $objCon->ejecutarSQL($sql, "ERROR al eliminar DeletePizarradetalle");
    }
    function SelectPizarra($objCon,$parametros){
        $condicion  = "";
        $sql="SELECT *
            FROM dau.pizarra
            Inner join  dau.turno_tipo_horario  ON pizarra.idTipoHorarioTurno = turno_tipo_horario.idTipoHorarioTurno ";
        if(isset($parametros['id_pizarra'])){
            $condicion .= ($condicion == "") ? " WHERE " : " AND ";
            $condicion.=" pizarra.id_pizarra = '{$parametros['id_pizarra']}' ";
        }
        if(isset($parametros['idTipoHorarioTurno'])){
            $condicion .= ($condicion == "") ? " WHERE " : " AND ";
            $condicion.=" pizarra.idTipoHorarioTurno = '{$parametros['idTipoHorarioTurno']}' ";
        }
        if(isset($parametros['fecha_crea'])){
            $condicion .= ($condicion == "") ? " WHERE " : " AND ";
            $condicion.=" pizarra.fecha_crea = '{$parametros['fecha_crea']}' ";
        }
        $sql .= $condicion."   ";
        $datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR SelectPizarra<br>");
        return $datos;
    }
    function SelectPizarraDetalle($objCon,$parametros){
        $condicion  = "";
        $sql="SELECT *
            FROM dau.pizarra
            INNER JOIN dau.pizarra_detalle ON pizarra.id_pizarra = pizarra_detalle.id_pizarra ";
        if(isset($parametros['id_pizarra'])){
            $condicion .= ($condicion == "") ? " WHERE " : " AND ";
            $condicion.=" pizarra.id_pizarra = '{$parametros['id_pizarra']}' ";
        }
        if(isset($parametros['idTipoHorarioTurno'])){
            $condicion .= ($condicion == "") ? " WHERE " : " AND ";
            $condicion.=" pizarra.idTipoHorarioTurno = '{$parametros['idTipoHorarioTurno']}' ";
        }
        if(isset($parametros['fecha_crea'])){
            $condicion .= ($condicion == "") ? " WHERE " : " AND ";
            $condicion.=" pizarra.fecha_crea = '{$parametros['fecha_crea']}' ";
        }
        $sql .= $condicion."  order by seccion_id asc ";
        $datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR SelectPizarra<br>");
        return $datos;
    }
    function DeleteByPizarraId($objCon, $id_pizarra) {
        $sql = "DELETE FROM dau.pizarra WHERE id_pizarra = '" . intval($id_pizarra) . "'";
        return $objCon->ejecutarSQL($sql, "ERROR al eliminar DeletePizarradetalle");
    }
    // function SelectByDauFormulario_1($objCon, $dau_id) {
    //     $sql = "SELECT * FROM dau.formulario_1 WHERE dau_id = '" . intval($dau_id) . "' ORDER BY creado_en DESC";
    //     return $objCon->consultaSQL($sql, "ERROR al consultar formulario_1 por dau_id");
    // }

    // function SelectByIdFormulario_1($objCon, $id) {
    //     $sql = "SELECT * FROM dau.formulario_1 WHERE id = '" . intval($id) . "'";
    //     return $objCon->consultaSQL($sql, "ERROR al consultar formulario_1 por ID");
    // }
}