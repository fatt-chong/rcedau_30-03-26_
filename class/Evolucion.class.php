<?php
    class Evolucion{

        function eliminarSolicitudEvolucion($objCon, $idSolicitudEvolucion){

            $sql = "    DELETE FROM
                            rce.solicitud_evolucion
                        WHERE
                            rce.solicitud_evolucion.SEVOid = {$idSolicitudEvolucion}";

            $responde 	= $objCon->ejecutarSQL($sql, "Error al Eliminar Solicitud de Evolución");

        }



        function ingresarSolicitudEvolucion($objCon,$parametros){

            $sql = "    INSERT INTO
                            rce.solicitud_evolucion
                            (
                                SEVOfecha,
                                SEVOidRCE,
                                SEVOidPaciente,
                                SEVOevolucion,
                                SEVOusuario
                            )
                        VALUES
                            (
                                NOW(),
                                '{$parametros['SEVOidRCE']}',
                                '{$parametros['SEVOidPaciente']}',
                                '{$parametros['SEVOevolucion']}',
                                '{$parametros['SEVOusuario']}'
                            )";

            $responde 	= $objCon->ejecutarSQL($sql, "Error al Insertar Solicitud de Evolución");
            $idSESP	= $objCon->lastInsertId();
            return $idSESP;
        }



        function obtenerDatosSolicitudEvolucion($objCon, $idSolicitudEvolucion){

            $sql = "    SELECT
                            paciente.paciente.nombres,
                            paciente.paciente.apellidopat,
                            paciente.paciente.apellidomat,

                            paciente.paciente.transexual,
                            paciente.paciente.nombreSocial,

                            rce.solicitud_evolucion.*
                        FROM
                            rce.solicitud_evolucion
                        INNER JOIN paciente.paciente ON rce.solicitud_evolucion.SEVOidPaciente = paciente.paciente.id
                        WHERE rce.solicitud_evolucion.SEVOid = {$idSolicitudEvolucion}";

            $responde 	= $objCon->consultaSQL($sql, "Error al Obtener Solicitud de Evolución");
            return $responde;

        }



        function obtenerUsuarioSolicitudEvolucion($objCon, $idSolicitudEvolucion){

            $sql = "    SELECT
                            rce.solicitud_evolucion.SEVOusuario
                        FROM
                            rce.solicitud_evolucion
                        WHERE rce.solicitud_evolucion.SEVOid = {$idSolicitudEvolucion}";

            $responde 	= $objCon->consultaSQL($sql, "Error al Obtener Usuario que Realizó Solicitud de Evolución");
            return $responde[0];

        }



        function obtenerDatosSolicitudEvolucionSegunRCE ( $objCon, $idRCE ) {

            $sql = "    SELECT
                            rce.solicitud_evolucion.*
                        FROM
                            rce.solicitud_evolucion
                        WHERE
                            rce.solicitud_evolucion.SEVOidRCE = {$idRCE} order by SEVOid DESC";

            $responde 	= $objCon->consultaSQL($sql, "Error al Obtener Solicitud de Evolución");
            return $responde;

        }
        function obtenerDatosSolicitudEvolucionGlobal ( $objCon, $parametros ) {
            $condicion  = "";
            $sql = "    SELECT
                            rce.solicitud_evolucion.*
                        FROM
                            rce.solicitud_evolucion
                        ";

            $condicion = " WHERE rce.solicitud_evolucion.SEVOidRCE =  {$parametros['rce_id']}";
            if ($parametros['usuario']) {
                $condicion .= ($condicion == "") ? " WHERE " : " AND ";
                $condicion .= "solicitud_evolucion.SEVOusuario = '{$parametros['usuario']}' ";
            }
            $sql        .= $condicion." order by SEVOid DESC";
            $datos = $objCon->consultaSQL($sql,"<br>Error al listar los Datos del Paciente");
            return $datos;

        }

    }
?>