<?php
class FormPacienteGes {

    function InsertFormPacienteGes($objCon, $parametros) {
        $campos_validos = [
            'PACGESfecha', 'PACGESpaciente', 'RCEid', 'PACGESdiagGes',
            'PACGESconfDiagn', 'PACGEStratamiento', 'PACGESnomApoderado',
            'PACGESrunApoderado', 'PACGESmailApoderado', 'PACGESfonoApoderado',
            'PACGESprofesional', 'tipo_atencion', 'teleconsulta_conocimiento_nopac', 'creado_usuario','dau_id','cie10_Codigo','direccion_paciente','telefono_fijo','telefono_celular','celular_representante','email'
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

        $sql = "INSERT INTO rce.form_pacienteges (" . implode(', ', $campos) . ")
                VALUES (" . implode(', ', $valores) . ")";

        $objCon->ejecutarSQL($sql, "ERROR al insertar en form_pacienteges");

        return $objCon->lastInsertId();
    }

    function UpdateFormPacienteGes($objCon, $parametros, $id) {
        $campos_validos = [
            'PACGESpaciente', 'RCEid', 'PACGESdiagGes',
            'PACGESconfDiagn', 'PACGEStratamiento', 'PACGESnomApoderado',
            'PACGESrunApoderado', 'PACGESmailApoderado', 'PACGESfonoApoderado',
            'PACGESprofesional', 'tipo_atencion', 'teleconsulta_conocimiento_nopac',
            'modificado_fecha', 'modificado_usuario','cie10_Codigo','direccion_paciente','telefono_fijo','telefono_celular','celular_representante','email'
        ];

        $updates = [];

        foreach ($campos_validos as $campo) {
            if (isset($parametros[$campo]) && $parametros[$campo] !== '') {
                $updates[] = "$campo = '" . addslashes($parametros[$campo]) . "'";
            }
        }

        if (empty($updates)) return false;

        $sql = "UPDATE rce.form_pacienteges
                SET " . implode(', ', $updates) . "
                WHERE PACGESid = '" . intval($id) . "'";

        return $objCon->ejecutarSQL($sql, "ERROR al actualizar form_pacienteges");
    }

    function DeleteFormPacienteGes($objCon, $id) {
        $sql = "DELETE FROM rce.form_pacienteges WHERE PACGESid = '" . intval($id) . "'";
        return $objCon->ejecutarSQL($sql, "ERROR al eliminar form_pacienteges");
    }

    function SelectByRceFormPacienteGes($objCon, $dau_id) {
        $sql = "SELECT * FROM rce.form_pacienteges WHERE dau_id = '" . intval($dau_id) . "' ORDER BY PACGESfecha DESC";
        return $objCon->consultaSQL($sql, "ERROR al consultar form_pacienteges por RCEid");
    }

    function SelectByIdFormPacienteGes($objCon, $id) {
        $sql = "SELECT * FROM rce.form_pacienteges WHERE PACGESid = '" . intval($id) . "'";
        return $objCon->consultaSQL($sql, "ERROR al consultar form_pacienteges por ID");
    }

    function SelectByIdFormPacienteGesSub($objCon, $id) {
        $sql = "
        SELECT
            rce.form_pacienteges.*,  CONCAT(
            paciente.paciente.nombres, ' ', 
            paciente.paciente.apellidopat, ' ', 
            paciente.paciente.apellidomat
            ) AS nombre_paciente,
            paciente.rut,
            paciente.prevision,
            paciente.comuna.comuna,
            parametros_clinicos.profesional.PROnombres, 
            parametros_clinicos.profesional.PROapellidopat
        FROM
            rce.form_pacienteges
        INNER JOIN paciente.paciente ON  rce.form_pacienteges.PACGESpaciente = paciente.paciente.id
        INNER JOIN parametros_clinicos.profesional ON rce.form_pacienteges.PACGESprofesional = parametros_clinicos.profesional.PROcodigo
        LEFT JOIN paciente.comuna ON paciente.paciente.idcomuna = paciente.comuna.id
        WHERE
            form_pacienteges.PACGESid = '" . intval($id) . "' ";
        return $objCon->consultaSQL($sql, "ERROR al consultar form_pacienteges por ID");
    }

    function SelectByPacienteFormPacienteGes($objCon, $paciente) {
        $sql = "SELECT * FROM rce.form_pacienteges WHERE PACGESpaciente = '" . addslashes($paciente) . "' ORDER BY PACGESfecha DESC";
        return $objCon->consultaSQL($sql, "ERROR al consultar form_pacienteges por paciente");
    }

    function SelectAllFormPacienteGes($objCon) {
        $sql = "SELECT * FROM rce.form_pacienteges ORDER BY PACGESfecha DESC";
        return $objCon->consultaSQL($sql, "ERROR al consultar todos los registros de form_pacienteges");
    }
    function SelectFormPacienteGes($objCon, $parametros) {
        $sql = "
        SELECT
            rce.form_pacienteges.*
        FROM
            rce.form_pacienteges
        WHERE form_pacienteges.PACGESpaciente = '" . $parametros['PACGESpaciente'] . "'  and form_pacienteges.cie10_Codigo = '" . $parametros['cie10_Codigo'] . "'  ";

        return $objCon->consultaSQL($sql, "ERROR al consultar SelectFormPacienteGes ");
    }
}
?> 