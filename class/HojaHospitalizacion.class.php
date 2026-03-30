<?php
 	class HojaHospitalizacion {

        function ingresarHojaHospitalizacion ( $objCon, $parametros ) {

            $sql = "INSERT INTO dau.hoja_hospitalizacion
                                (
                                    idDau
                                    ,motivoIngreso
                                    ,antecedentesMorbidos
                                    ,examenGeneral
                                    ,descripcionExamenGeneral
                                    ,conjuntivas
                                    ,escleras
                                    ,otrosExamenFisico
                                    ,cuelloYColumna
                                    ,rigidezNuca
                                    ,ganglios
                                    ,yugular
                                    ,tiroides
                                    ,torax
                                    ,pulmones
                                    ,descripcionPulmones
                                    ,rrEn2T
                                    ,soplos
                                    ,descripcionCorazon
                                    ,inspeccion
                                    ,palpacion
                                    ,higadoYVesicula
                                    ,bazo
                                    ,rinionesYPtosUretrales
                                    ,hernias
                                    ,tactoRectal
                                    ,genitales
                                    ,extremidades
                                    ,varices
                                    ,edema
                                    ,pupilas
                                    ,reflejosOsteotendinosos
                                    ,signosMeningeos
                                    ,focalizacion
                                    ,conciencia
                                    ,glasgow
                                    ,ptos
                                    ,hora
                                    ,glasgowO
                                    ,glasgowV
                                    ,glasgowM
                                    ,hipotesisDiagnosticas
                                    ,indicaciones
                                    ,usuario
                                )
                    VALUES  (
                                 '{$parametros['idDau']}'
                                ,'{$parametros['motivoIngreso']}'
                                ,'{$parametros['antecedentesMorbidos']}'
                                ,'{$parametros['examenGeneral']}'
                                ,'{$parametros['descripcionExamenGeneral']}'
                                ,'{$parametros['conjuntivas']}'
                                ,'{$parametros['escleras']}'
                                ,'{$parametros['otrosExamenFisico']}'
                                ,'{$parametros['cuelloYColumna']}'
                                ,'{$parametros['rigidezNuca']}'
                                ,'{$parametros['ganglios']}'
                                ,'{$parametros['yugular']}'
                                ,'{$parametros['tiroides']}'
                                ,'{$parametros['torax']}'
                                ,'{$parametros['pulmones']}'
                                ,'{$parametros['descripcionPulmones']}'
                                ,'{$parametros['rrEn2T']}'
                                ,'{$parametros['soplos']}'
                                ,'{$parametros['descripcionCorazon']}'
                                ,'{$parametros['inspeccion']}'
                                ,'{$parametros['palpacion']}'
                                ,'{$parametros['higadoYVesicula']}'
                                ,'{$parametros['bazo']}'
                                ,'{$parametros['rinionesYPtosUretrales']}'
                                ,'{$parametros['hernias']}'
                                ,'{$parametros['tactoRectal']}'
                                ,'{$parametros['genitales']}'
                                ,'{$parametros['extremidades']}'
                                ,'{$parametros['varices']}'
                                ,'{$parametros['edema']}'
                                ,'{$parametros['pupilas']}'
                                ,'{$parametros['reflejosOsteotendinosos']}'
                                ,'{$parametros['signosMeningeos']}'
                                ,'{$parametros['focalizacion']}'
                                ,'{$parametros['conciencia']}'
                                ,'{$parametros['glasgow']}'
                                ,'{$parametros['ptos']}'
                                ,'{$parametros['hora']}'
                                ,'{$parametros['glasgowO']}'
                                ,'{$parametros['glasgowV']}'
                                ,'{$parametros['glasgowM']}'
                                ,'{$parametros['hipotesisDiagnosticas']}'
                                ,'{$parametros['indicaciones']}'
                                ,'{$parametros['usuario']}'
                            )
                    ";

            $objCon->ejecutarSQL($sql, "Error ingresar hoja de hospitalización");

            return $objCon->lastInsertId($sql);

        }



        function obtenerAntecedentesMorbidos ( $objCon, $parametros ) {

            $sql = "SELECT
                        CONCAT(rce.antecedente.antDescripcion,': ',rce.paciente_has_antecedente.pac_ant_descripcion) AS descripcionAntecedente
                    FROM
                        rce.paciente_has_antecedente
                    INNER JOIN
                        rce.antecedente ON rce.paciente_has_antecedente.antid = rce.antecedente.antid
                    WHERE
                        rce.paciente_has_antecedente.pacId = '{$parametros['idPaciente']}'
                    ";

            $datos = $objCon->consultaSQL($sql,"Error al obtener antecedentes mórbidos");

            return $datos;

        }



        function obtenerDatosHojaHospitalizacion ( $objCon, $parametros ) {

            $sql = "SELECT
                        dau.hoja_hospitalizacion.*
                    FROM
                        dau.hoja_hospitalizacion
                    WHERE
                        dau.hoja_hospitalizacion.idHojaHospitalizacion = '{$parametros['idHojaHospitalizacion']}'
                    ";

            $datos = $objCon->consultaSQL($sql,"Error al obtener datos de hoja de hospitalización");

            return $datos;

        }
        function obtenerDatosHojaHospitalizacion2 ( $objCon, $parametros ) {

            $sql = "SELECT
                        dau.hoja_hospitalizacion.*
                    FROM
                        dau.hoja_hospitalizacion
                    WHERE
                        dau.hoja_hospitalizacion.idDau = '{$parametros['idDAU']}'
                    order by hoja_hospitalizacion.idHojaHospitalizacion desc
                    ";

            $datos = $objCon->consultaSQL($sql,"Error al obtener datos de hoja de hospitalización");

            return $datos;

        }



        function obtenerDatosPaciente ( $objCon, $parametros ) {

            $sql = "SELECT
                        CONCAT(paciente.paciente.nombres,' ',paciente.paciente.apellidopat,' ',paciente.paciente.apellidomat) AS nombrePaciente,
                        dau.dau.dau_paciente_edad AS edadPaciente,
                        dau.dau.id_paciente,
                        paciente.paciente.rut,
                        paciente.paciente.rut_extranjero,
                        dau.dau.dau_admision_fecha AS fechaAdmision,
                        IF(dau.dau.dau_indicacion_egreso_aplica_fecha IS NULL OR dau.dau.dau_indicacion_egreso_aplica_fecha = '', dau.dau.dau_indicacion_egreso_fecha, dau.dau.dau_indicacion_egreso_aplica_fecha) AS fechaHospitalizacion,
                        rce.registroclinico.regMotivoConsulta AS motivoConsulta,
                        rce.registroclinico.regHipotesisInicial AS hipotesisDiagnostica,
                        rce.registroclinico.regHipotesisFinal AS hipotesisFinal,
                        rce.registroclinico.regIndicacionEgresoUrgencia AS indicaciones,
                        dau.dau_movimiento_indicacion.dau_mov_ind_desc AS hospitalizarEnServicio,
                        religion.rlg_descripcion AS religion_descripcion
                    FROM
                        dau.dau
                    INNER JOIN
                        paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
                    LEFT JOIN paciente.religion on paciente.religion.rlg_id = paciente.paciente.religion
                    LEFT JOIN
                        rce.registroclinico ON dau.dau.dau_id = rce.registroclinico.dau_id
                    LEFT JOIN
                        rce.solicitud_altaurgencia ON rce.registroclinico.regId = rce.solicitud_altaurgencia.SAUidRCE AND rce.solicitud_altaurgencia.SAUid = (SELECT MAX(rce.solicitud_altaurgencia.SAUid) FROM rce.solicitud_altaurgencia WHERE rce.registroclinico.regId = rce.solicitud_altaurgencia.SAUidRCE)
                    LEFT JOIN
                        dau.dau_movimiento_indicacion ON dau.dau.dau_id = dau.dau_movimiento_indicacion.dau_id AND dau.dau_movimiento_indicacion.dau_mov_ind_id = (SELECT MAX(dau.dau_movimiento_indicacion.dau_mov_ind_id) FROM dau.dau_movimiento_indicacion WHERE dau.dau.dau_id = dau.dau_movimiento_indicacion.dau_id)
                    WHERE
                        dau.dau.dau_id = '{$parametros['idDau']}'
                    ";

            $datos = $objCon->consultaSQL($sql,"Error al obtener datos de paciente");

            return $datos;

        }



        function obtenerIdHojaHospitalizacion ( $objCon, $parametros ) {

            $sql = "SELECT
                        MAX(dau.hoja_hospitalizacion.idHojaHospitalizacion) AS idHojaHospitalizacion
                    FROM
                        dau.hoja_hospitalizacion
                    WHERE
                        dau.hoja_hospitalizacion.idDau = '{$parametros['idDau']}'
                    ";

            $datos = $objCon->consultaSQL($sql,"Error al obtener id hoja de hospitalizacion");

            return $datos;

        }



        function obtenerSignosVitales ( $objCon, $parametros ) {

            $sql = "SELECT
                        rce.signo_vital.*
                    FROM
                        rce.signo_vital
                    WHERE
                        rce.signo_vital.idRCE = '{$parametros['idRCE']}'
                    ";

            $datos = $objCon->consultaSQL($sql,"Error al obtener signos vitales");

            return $datos;

        }

    }

?>