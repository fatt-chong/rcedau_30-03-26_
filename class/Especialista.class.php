<?php
    class Especialista{
         function actualizarEstadoSolicitudEspecialidadOtros($objCon,$parametros){

            $sql = "    UPDATE
                            rce.solicitud_otros_especialidad
                        SET
                            solicitud_otros_especialidad.estado_sol_otro                          = {$parametros['estado_sol_otro']},
                            solicitud_otros_especialidad.sol_otro_usuarioAplica                   = '{$parametros['sol_otro_usuarioAplica']}',
                            solicitud_otros_especialidad.sol_otro_usuarioAplica_observacion       = '{$parametros['sol_otro_usuarioAplica_observacion']}',
                            solicitud_otros_especialidad.sol_otro_usuarioAplica_fecha             = NOW()
                        WHERE
                            solicitud_otros_especialidad.id_sol_otro                              = {$parametros['id_sol_otro']}";

            $responde   = $objCon->ejecutarSQL($sql, "Error al Actualizar Solicitud de Especialista");

            return $responde;

        }

        function actualizarEstadoSolicitudEspecialidad($objCon,$parametros){

            $sql = "    UPDATE
                            rce.solicitud_especialista
                        SET
                            solicitud_especialista.SESPestado                   = {$parametros['SESPestado']},
                            solicitud_especialista.SESPobservacionEspecialista  = '{$parametros['SESPobservacionEspecialista']}',
                            solicitud_especialista.SESPusuarioAplica            = '{$parametros['SESPusuarioAplica']}',
                            solicitud_especialista.SESPfechaAplicacion          = NOW(),
                            SESPgestionRealizada                                = '{$parametros['SESPgestionRealizada']}',
                            SESPusuarioGestionRealizada                         = '{$parametros['SESPusuarioGestionRealizada']}',
                            SESPidProfesionalEspecialista                       = '{$parametros['SESPidProfesionalEspecialista']}',
                            SESPobservacionGestionRealizada                     = '{$parametros['SESPobservacionGestionRealizada']}'
                        WHERE
                            solicitud_especialista.SESPid = {$parametros['SESPid']}";

            $responde   = $objCon->ejecutarSQL($sql, "Error al Actualizar Solicitud de Especialista");

            return $responde;

        }



        function anularSolicitudEspecialidad($objCon,$parametros){
            $sql = "    UPDATE
                            rce.solicitud_especialista
                        SET
                            solicitud_especialista.SESPestado = 6
                        WHERE
                            solicitud_especialista.SESPid = {$parametros['solicitud_id']}";

            $objCon->ejecutarSQL($sql, "Error al Anular Solicitud de Especialista");

        }

        function anularSolicitudEspecialidadOtros($objCon,$parametros){
            $sql = "    UPDATE
                            rce.solicitud_otros_especialidad
                        SET
                            solicitud_otros_especialidad.estado_sol_otro = 6
                        WHERE
                            solicitud_otros_especialidad.id_sol_otro = {$parametros['solicitud_id']}";

            $objCon->ejecutarSQL($sql, "Error al Anular Solicitud de Especialista");

        }



        function buscarMedicosEspecialistas ( $objCon, $parametros ) {

            $sql = "SELECT
                        parametros_clinicos.profesional_has_especialidad.PROcodigo,
                        parametros_clinicos.profesional.PROdescripcion
                    FROM
                        parametros_clinicos.profesional_has_especialidad
                    INNER JOIN
                        parametros_clinicos.profesional ON parametros_clinicos.profesional_has_especialidad.PROcodigo = parametros_clinicos.profesional.PROcodigo
                    WHERE
                        parametros_clinicos.profesional_has_especialidad.ESPcodigo = '{$parametros['idEspecialidad']}'
                    AND
                        parametros_clinicos.profesional_has_especialidad.PROESPactivoDAU = 'S'
                    ORDER BY
                        parametros_clinicos.profesional.PROdescripcion ASC
                    ";

            $respuesta = $objCon->consultaSQL($sql, "Error al obtener médicos especialistas");

            return $respuesta;

        }



        function sensitivaEspecialidad($objCon,$termino){

            $sql = "SELECT 
                        especialidad.ESPcodigo, 
                        especialidad.ESPdescripcion, 
                        'P' AS fuente 
                    FROM parametros_clinicos.especialidad 
                    WHERE especialidad.ESPdescripcion LIKE '%{$termino}%'

                    AND especialidad.ESPactivoHJNC = 'S'
                    AND especialidad.ESPactivoSolicitud ='S'

                    UNION ALL

                    SELECT 
                        otro_especialista.id_otro AS ESPcodigo, 
                        otro_especialista.descripcion_otro AS ESPdescripcion, 
                        'O' AS fuente 
                    FROM rce.otro_especialista 
                    WHERE otro_especialista.descripcion_otro LIKE '%{$termino}%'";

            $datos = $objCon->consultaSQL($sql,"<br>ERROR AL LEER ESPECIALIDADES<br>");

            $return_arr = array();

            for ( $i = 0; $i < count($datos); $i++ ) {

                $row_array['label'] = $datos[$i]['ESPdescripcion'];
                $row_array['fuente'] = $datos[$i]['fuente'];

                $row_array['value'] = $datos[$i]['ESPcodigo'];

                array_push($return_arr,$row_array);

            }

            return $return_arr;

        }



        function eliminarSolicitudEspecialidad($objCon, $parametros){

            $sql = "    DELETE FROM
                            rce.solicitud_especialista
                        WHERE
                            rce.solicitud_especialista.SESPid = '{$parametros['solicitud_id']}' ";

            $objCon->ejecutarSQL($sql, "Error al Eliminar Solicitud de Especialista");

        }



        function ingresarGestionRealizada ( $objCon, $parametros ) {

            $sql = "    UPDATE
                            rce.solicitud_especialista
                        SET
                            SESPgestionRealizada                                = '{$parametros['SESPgestionRealizada']}',
                            SESPusuarioGestionRealizada                         = '{$parametros['SESPusuarioGestionRealizada']}',
                            SESPidProfesionalEspecialista                       = '{$parametros['SESPidProfesionalEspecialista']}',
                            SESPobservacionGestionRealizada                     = '{$parametros['SESPobservacionGestionRealizada']}'
                        WHERE
                            solicitud_especialista.SESPid = {$parametros['SESPid']}";

            $responde   = $objCon->ejecutarSQL($sql, "Error al Actualizar Gestión Realizada");

            return $responde;

        }



        function ingresarSolicitudEspecialidad($objCon,$parametros){

            $sql = "    INSERT INTO
                            rce.solicitud_especialista
                            (
                                SESPfecha,
                                SESPidRCE,
                                SESPidPaciente,
                                SESPidEspecialidad,
                                SESPobservacion,
                                SESPusuario,
                                SESPespecialistaDeLlamado,
                                SESPusuarioEspecialistaDeLlamado,
                                SESPgestionRealizada,
                                SESPidProfesionalEspecialista,
                                SESPusuarioGestionRealizada,
                                SESPobservacionGestionRealizada,
                                SESPfuente
                            )
                        VALUES
                            (
                                 NOW(),
                                '{$parametros['SESPidRCE']}',
                                '{$parametros['SESPidPaciente']}',
                                '{$parametros['SESPidEspecialidad']}',
                                '{$parametros['SESPobservacion']}',
                                '{$parametros['SESPusuario']}',
                                '{$parametros['SESPespecialistaDeLlamado']}',
                                '{$parametros['SESPusuarioEspecialistaDeLlamado']}',
                                '{$parametros['SESPgestionRealizada']}',
                                '{$parametros['SESPidProfesionalEspecialista']}',
                                '{$parametros['SESPusuarioGestionRealizada']}',
                                '{$parametros['SESPobservacionGestionRealizada']}',
                                '{$parametros['SESPfuente']}'
                            )";

            $responde   = $objCon->ejecutarSQL($sql, "Error al Insertar Solicitud de Especialista");

            $idSESP = $objCon->lastInsertId();
            return $idSESP;

        }
        function ingresarsolicitud_otros_especialidad($objCon,$parametros){

            $sql = "    INSERT INTO
                            rce.solicitud_otros_especialidad
                            (
                                sol_otro_fecha,
                                sol_otro_usuario,
                                sol_otro_paciente,
                                id_otro,
                                sol_otro_observacion,
                                idRCE,
                                idPaciente,
                                estado_sol_otro
                            )
                        VALUES
                            (
                                 NOW(),
                                '{$parametros['sol_otro_usuario']}',
                                '{$parametros['sol_otro_paciente']}',
                                {$parametros['id_otro']},
                                '{$parametros['sol_otro_observacion']}',
                                '{$parametros['idRCE']}',
                                '{$parametros['idPaciente']}',
                                1
                            )";

            $responde   = $objCon->ejecutarSQL($sql, "Error al Insertar Solicitud de Especialista");

            $idSESP = $objCon->lastInsertId();
            return $idSESP;

        }

         function obtenerDatosSolicitudEspecialistaOtros($objCon, $id_sol_otro){

            $sql = "    SELECT
                            otro_especialista.id_otro,
                            otro_especialista.descripcion_otro,
                            paciente.paciente.nombres,
                            paciente.paciente.apellidopat,
                            paciente.paciente.apellidomat,

                            paciente.paciente.transexual,
                            paciente.paciente.nombreSocial,

                            rce.solicitud_otros_especialidad.*
                        FROM
                            rce.solicitud_otros_especialidad
                        INNER JOIN rce.otro_especialista ON rce.solicitud_otros_especialidad.id_otro = otro_especialista.id_otro
                        INNER JOIN paciente.paciente ON rce.solicitud_otros_especialidad.idPaciente = paciente.paciente.id
                        WHERE rce.solicitud_otros_especialidad.id_sol_otro = {$id_sol_otro}";

            $responde   = $objCon->consultaSQL($sql, "Error al Obtener Solicitud de Especialista");

            return $responde;

        }

        // function obtenerDatosSolicitudEspecialista($objCon, $idSolicitudEspecialista){

        //     $sql = "    SELECT
        //                     parametros_clinicos.especialidad.ESPcodigo,
        //                     parametros_clinicos.especialidad.ESPdescripcion,
        //                     paciente.paciente.nombres,
        //                     paciente.paciente.apellidopat,
        //                     paciente.paciente.apellidomat,

        //                     paciente.paciente.transexual,
        //                     paciente.paciente.nombreSocial,

        //                     rce.solicitud_especialista.*
        //                 FROM
        //                     rce.solicitud_especialista
        //                 INNER JOIN parametros_clinicos.especialidad ON rce.solicitud_especialista.SESPidEspecialidad = parametros_clinicos.especialidad.ESPcodigo
        //                 INNER JOIN paciente.paciente ON rce.solicitud_especialista.SESPidPaciente = paciente.paciente.id
        //                 WHERE rce.solicitud_especialista.SESPid = {$idSolicitudEspecialista}";

        //     $responde   = $objCon->consultaSQL($sql, "Error al Obtener Solicitud de Especialista");

        //     return $responde;

        // }

        function obtenerDatosSolicitudEspecialista($objCon, $idSolicitudEspecialista){

            $sql = "   SELECT 
                CASE 
                    WHEN rce.solicitud_especialista.SESPfuente = 'P' THEN parametros_clinicos.especialidad.ESPcodigo 
                    ELSE otro_especialista.id_otro 
                END AS ESPcodigo,
                
                CASE 
                    WHEN rce.solicitud_especialista.SESPfuente = 'P' THEN parametros_clinicos.especialidad.ESPdescripcion 
                    ELSE otro_especialista.descripcion_otro 
                END AS ESPdescripcion,
                paciente.paciente.nombres,
                paciente.paciente.apellidopat,
                paciente.paciente.apellidomat,
                paciente.paciente.transexual,
                paciente.paciente.nombreSocial,
                rce.solicitud_especialista.*

            FROM rce.solicitud_especialista
            LEFT JOIN parametros_clinicos.especialidad 
                ON rce.solicitud_especialista.SESPfuente = 'P' 
                AND rce.solicitud_especialista.SESPidEspecialidad = parametros_clinicos.especialidad.ESPcodigo
            LEFT JOIN rce.otro_especialista 
                ON rce.solicitud_especialista.SESPfuente = 'O' 
                AND rce.solicitud_especialista.SESPidEspecialidad = otro_especialista.id_otro
            INNER JOIN paciente.paciente 
                ON rce.solicitud_especialista.SESPidPaciente = paciente.paciente.id

            WHERE rce.solicitud_especialista.SESPid = {$idSolicitudEspecialista}";

            $responde   = $objCon->consultaSQL($sql, "Error al Obtener Solicitud de Especialista");

            return $responde;

        }



        function getSolicitudEspecialista($objCon,$parametros){

            $sql="SELECT
                solicitud_especialista.SESPid,
                solicitud_especialista.SESPfecha,
                solicitud_especialista.SESPfechaAplicacion,
                solicitud_especialista.SESPidRCE,
                solicitud_especialista.SESPidPaciente,
                solicitud_especialista.SESPidEspecialidad,
                solicitud_especialista.SESPobservacionEspecialista,
                solicitud_especialista.SESPobservacion,
                solicitud_especialista.SESPusuario,
                solicitud_especialista.SESPusuarioAplica,
                solicitud_especialista.SESPestado,
                solicitud_especialista.SESPtipo,
                CONCAT( paciente.paciente.nombres ,' ', paciente.paciente.apellidopat ,' ', paciente.paciente.apellidomat ) as Nombres,

                paciente.paciente.transexual,
                paciente.paciente.nombreSocial,
                CASE 
                    WHEN solicitud_especialista.SESPfuente = 'P' THEN parametros_clinicos.especialidad.ESPdescripcion
                    ELSE otro_especialista.descripcion_otro
                END AS ESPdescripcion,
                ( SELECT acceso.usuario.nombreusuario FROM acceso.usuario WHERE acceso.usuario.idusuario = CONVERT(CAST(solicitud_especialista.SESPusuario as BINARY) USING latin1) COLLATE latin1_spanish_ci ) AS usuarioInserta,
                rce.registroclinico.dau_id,
                dau.dau.dau_categorizacion,
                dau.cama.cam_descripcion,
                dau.sala.sal_descripcion,
                dau.sala.sal_id,
                rce.estado_indicacion.est_descripcion
                FROM rce.solicitud_especialista
                INNER JOIN paciente.paciente ON rce.solicitud_especialista.SESPidPaciente = paciente.paciente.id
                LEFT JOIN parametros_clinicos.especialidad 
                    ON solicitud_especialista.SESPfuente = 'P' 
                    AND solicitud_especialista.SESPidEspecialidad = parametros_clinicos.especialidad.ESPcodigo
                LEFT JOIN rce.otro_especialista 
                    ON solicitud_especialista.SESPfuente = 'O' 
                    AND solicitud_especialista.SESPidEspecialidad = otro_especialista.id_otro
                INNER JOIN rce.registroclinico ON rce.solicitud_especialista.SESPidRCE = rce.registroclinico.regId
                INNER JOIN dau.dau ON rce.registroclinico.dau_id = dau.dau.dau_id
                LEFT JOIN dau.cama ON dau.cama.dau_id = dau.dau.dau_id
                LEFT JOIN dau.sala ON dau.cama.sal_id = dau.sala.sal_id
                INNER JOIN rce.estado_indicacion ON rce.solicitud_especialista.SESPestado = rce.estado_indicacion.est_id";
            $condicion ="";
            if ($parametros['estados']) {
                $condicion .= ($condicion == "") ? " WHERE " : " AND ";
                $condicion.="dau.dau.est_id IN (3,4)";
            }
            if ($parametros['especialidad']) {
                $condicion .= ($condicion == "") ? " WHERE " : " AND ";
                $condicion.="solicitud_especialista.SESPidEspecialidad IN ({$parametros['especialidad']})";
            }

            $condicion .= ($condicion == "") ? " WHERE " : " AND ";
            $condicion.="rce.solicitud_especialista.SESPestado = 1";

            $sql.=$condicion;
            $sql.=" ORDER BY solicitud_especialista.SESPid DESC";
            $responde   = $objCon->consultaSQL($sql, "Error al Obtener Solicitud de Especialista");
            return $responde;
        }



        function getEspecialidad($objCon,$parametros){

                $sql="SELECT
                profesional_has_especialidad.PROcodigo,
                profesional_has_especialidad.ESPcodigo
                FROM parametros_clinicos.profesional_has_especialidad
                INNER JOIN parametros_clinicos.especialidad ON profesional_has_especialidad.ESPcodigo = especialidad.ESPcodigo
                INNER JOIN parametros_clinicos.profesional ON profesional_has_especialidad.PROcodigo = profesional.PROcodigo
                WHERE profesional_has_especialidad.PROcodigo = '{$parametros['rut']}'";
            $responde   = $objCon->consultaSQL($sql, "Error al Obtener Solicitud de Especialista");
            return $responde;
        }



        function obtenerDatosSolicitudEspecialistaSegunRCE ( $objCon, $idRCE ) {

            $sql = "SELECT
                        rce.solicitud_especialista.*
                    FROM
                        rce.solicitud_especialista
                    WHERE
                        rce.solicitud_especialista.SESPidRCE = {$idRCE}
                    ";

            $response 	= $objCon->consultaSQL($sql, "Error al Obtener Solicitud de Especialista");

            return $response;

        }
    }
?>