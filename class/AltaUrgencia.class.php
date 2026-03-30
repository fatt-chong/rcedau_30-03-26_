<?php
    class AltaUrgencia{



        //Actualizar DAU al anular alta urgencia
        function actualizarDAUAlAnularAltaUrgencia($objCon, $idDau) {

            $sql = "    UPDATE
                            dau.dau
                        SET
                            dau.dau.est_id = 3,
                            dau.dau.dau_indicacion_egreso_fecha = NULL,
                            dau.dau.dau_indicacion_egreso = NULL,
                            dau.dau.dau_indicacion_egreso_usuario = NULL,
                            dau.dau.dau_cierre_cie10 = NULL,
                            dau.dau.dau_cierre_fundamento_diag = NULL,
                            dau.dau.dau_cierre_auge = 'N',
                            dau.dau.dau_cierre_pertinencia = 'N',
                            dau.dau.dau_cierre_entrega_postinor = 'N',
                            dau.dau.dau_cierre_hepatitisB = 'N',
                            dau.dau.dau_cie10_abierto = 'N',
                            dau.dau.dau_defuncion_fecha = NULL,
                            dau.dau.dau_defuncion_usuario = NULL,
                            dau.dau.dau_entrega_informacion = NULL
                        WHERE
                            dau.dau.dau_id = '{$idDau}' ";

            $objCon->ejecutarSQL($sql, "Error al Actualizar DAU");

         }



        //Actualizar registro clínico al anular alta urgencia
        function actualizarRegistroClinicoAlAnularAltaUrgencia($objCon, $idRCE) {

            $sql = "    UPDATE
                            rce.registroClinico
                        SET
                            rce.registroClinico.regIndicacionEgreso = NULL,
                            rce.registroClinico.regDiagnosticoCie10 = NULL,
                            rce.registroClinico.regHipotesisFinal = NULL,
                            rce.registroClinico.regIndicacionEgresoUrgencia = NULL,
                            rce.registroClinico.PRONcodigo = NULL,
                            rce.registroClinico.regUsuarioActualiza = NULL,
                            rce.registroClinico.regFechaActualiza = NULL,
                            rce.registroClinico.regCIE10Abierto = NULL
                        WHERE
                            rce.registroClinico.regId = '{$idRCE}' ";

            $objCon->ejecutarSQL($sql, "Error al Actualizar Registro Clinico");

         }



        //Anular solicitud alta urgencia
        function anularSolicitudAltaUrgencia($objCon, $parametros) {

            $sql = "    UPDATE
                            rce.solicitud_altaUrgencia
                        SET
                            rce.solicitud_altaUrgencia.SAUestado = 6,
                            rce.solicitud_altaUrgencia.SAUfechaAnula = NOW(),
                            rce.solicitud_altaUrgencia.SAUusuarioAnula = '{$parametros['usuarioAnula']}',
                            rce.solicitud_altaUrgencia.SAUobservacionAnula = '{$parametros['observacion_detalle']}'
                        WHERE
                            rce.solicitud_altaurgencia.SAUid = '{$parametros['solicitud_id']}' ";

            $objCon->ejecutarSQL($sql, "Error al Anular Solicitud de Alta Urgencia");

         }



         //Anular solicitudes alta urgencia previas
         function anularSolicitudesUrgenciasPrevias($objCon, $parametros) {

            $sql = "    UPDATE
                            rce.solicitud_altaUrgencia
                        SET
                            rce.solicitud_altaUrgencia.SAUestado = 6,
                            rce.solicitud_altaUrgencia.SAUfechaAnula = NOW(),
                            rce.solicitud_altaUrgencia.SAUusuarioAnula = '{$parametros['SAUusuarioAnula']}',
                            rce.solicitud_altaUrgencia.SAUobservacionAnula = '{$parametros['SAUobservacionAnula']}'
                        WHERE
                            rce.solicitud_altaurgencia.SAUidDau = '{$parametros['SAUidDau']}'
                        AND
                            rce.solicitud_altaurgencia.SAUid <> '{$parametros['SAUid']}' ";

            $objCon->ejecutarSQL($sql, "Error al Anular Solicitud de Alta Urgencia");

         }



        //Descripcion alta urgencia
        function descripcioAltaUrgencia($objCon, $idDau){
            $sql = "   SELECT
                             dau_movimiento_indicacion.dau_mov_ind_desc  AS tipoSolicitud
                         FROM
                             dau.dau_movimiento_indicacion
                         WHERE
                             dau_movimiento_indicacion.dau_id = '{$idDau}'
                         ORDER BY dau_movimiento_indicacion.dau_mov_ind_id  DESC LIMIT 1";

             $respuesta = $objCon->consultaSQL($sql,"Erro al verificar si existe descripción de Alta Urgencia");
             return $respuesta;
        }

        function SelectAltaUrgencia($objCon, $idDau){
             $sql = "   SELECT *
                         FROM
                             rce.solicitud_altaurgencia
                         WHERE
                             solicitud_altaurgencia.SAUidDau = '{$idDau}'
                         ORDER BY solicitud_altaurgencia.SAUid  DESC LIMIT 1";

             $respuesta = $objCon->consultaSQL($sql,"Erro al verificar si existe descripción de Alta Urgencia");
             return $respuesta;
        }



        //Eliminar indicación de egreso
        function eliminarIndicacionEgreso($objCon, $idDau){

            $sql = "    DELETE FROM
                            dau.dau_tiene_indicacion
                        WHERE dau.dau_tiene_indicacion.dau_id = '{$idDau}' ";

            $objCon->ejecutarSQL($sql, "Error al Eliminar Indicación Egreso Alta Urgencia");

         }



        //Existe solicitud alta urgencia
        function existeSolicitudAltaUrgencia($objCon, $idDau){
            $sql = "    SELECT
                                dau_tiene_indicacion.ind_egr_id AS tipoSolicitud
                            FROM
                                dau.dau_tiene_indicacion
                            WHERE
                                dau_tiene_indicacion.dau_id = '{$idDau}' ";

                $respuesta = $objCon->consultaSQL($sql,"Erro al verificar si existe solicitud de Alta Urgencia");
                return $respuesta;
        }



        //Insertar solicitud alta urgencia
        function ingresarSolicitudAltaUrgencia($objCon,$parametros){

            if ($parametros['SAUauge'] == '') {
                $parametros['SAUauge'] = 'N';
            }

            if ($parametros['SAUpertinencia'] == '') {
                $parametros['SAUpertinencia'] = 'N';
            }

            if ($parametros['SAUpostinor'] == '') {
                $parametros['SAUpostinor'] = 'N';
            }

            $sql = "    INSERT INTO
                            rce.solicitud_altaUrgencia
                            (
                                SAUfecha,
                                SAUidRCE,
                                SAUidDau,
                                SAUPidPaciente,
                                SAUusuario,
                                SAUidCie10,
                                SAUcie10Abierto,
                                SAUindicaciones,
                                SAUauge,
                                SAUpertinencia,
                                SAUpostinor
                            )
                        VALUES
                            (
                                NOW(),
                                '{$parametros['SAUidRCE']}',
                                '{$parametros['SAUidDau']}',
                                '{$parametros['SAUidPaciente']}',
                                '{$parametros['SAUusuario']}',
                                '{$parametros['SAUidCie10']}',
                                '{$parametros['SAUcie10Abierto']}',
                                '{$parametros['SAUindicaciones']}',
                                '{$parametros['SAUauge']}',
                                '{$parametros['SAUpertinencia']}',
                                '{$parametros['SAUpostinor']}'
                            )";

            $respuesta 	= $objCon->ejecutarSQL($sql, "Error al Insertar Solicitud de Alta Urgencia");
            $idSAU	= $objCon->lastInsertId();
            return $idSAU;
        }



        //Obtener datos de alta urgencia
        function obtenerDatosIndicacionAltaUrgencia($objCon, $SAUid){

            $sql = "    SELECT
                            rce.solicitud_altaurgencia.*,
                            cie10.cie10.nombreCIE AS descripcionCIE10,
                            dau.dau_movimiento_indicacion.dau_mov_ind_desc AS descripcionIndicacionEgreso
                        FROM
                            rce.solicitud_altaurgencia
                        LEFT JOIN
                            cie10.cie10 ON rce.solicitud_altaurgencia.SAUidCie10 = cie10.cie10.codigoCIE
                        LEFT JOIN
                            dau.dau_movimiento_indicacion ON rce.solicitud_altaurgencia.SAUidDau = dau.dau_movimiento_indicacion.dau_id
                        WHERE
                            rce.solicitud_altaurgencia.SAUid = '{$SAUid}'
                        AND
	                        rce.solicitud_altaurgencia.SAUfecha = dau.dau_movimiento_indicacion.dau_mov_ind_fecha";

            $respuesta = $objCon->consultaSQL($sql,"Erro al Obtener Datos de Alta Urgencia");
            return $respuesta;
        }
        function obtenerDatosIndicacionAltaUrgenciarRCE($objCon, $SAUidRCE){

            $sql = "    SELECT
                            rce.solicitud_altaurgencia.*,
                            cie10.cie10.nombreCIE AS descripcionCIE10,
                            dau.dau_movimiento_indicacion.dau_mov_ind_desc AS descripcionIndicacionEgreso
                        FROM
                            rce.solicitud_altaurgencia
                        LEFT JOIN
                            cie10.cie10 ON rce.solicitud_altaurgencia.SAUidCie10 = cie10.cie10.codigoCIE
                        LEFT JOIN
                            dau.dau_movimiento_indicacion ON rce.solicitud_altaurgencia.SAUidDau = dau.dau_movimiento_indicacion.dau_id
                        WHERE
                            rce.solicitud_altaurgencia.SAUidRCE = '{$SAUidRCE}'
                        AND
                            rce.solicitud_altaurgencia.SAUfecha = dau.dau_movimiento_indicacion.dau_mov_ind_fecha";

            $respuesta = $objCon->consultaSQL($sql,"Erro al Obtener Datos de Alta Urgencia");
            return $respuesta;
        }



        function cambiarEstadoSolicitudAltaUrgencia ( $objCon, $parametros ) {

             $sql       = "	UPDATE
                                rce.solicitud_altaurgencia
                            SET
                                rce.solicitud_altaurgencia.SAUestado 		= 4,
                                rce.solicitud_altaurgencia.SAUusuarioAplica = '{$parametros['SAUusuarioAplica']}',
                                rce.solicitud_altaurgencia.SAUfechaAplica   = NOW()
                            WHERE
                                rce.solicitud_altaurgencia.SAUidDau = '{$parametros['SAUidDau']}' ";

            $response  = $objCon -> ejecutarSQL($sql, "Error al cambiar estado de aplicar egreso en solicitud de alta urgencia");


        }



        function pacienteEgresado ( $objCon, $idDau ) {

            $sql = "SELECT
                        dau.dau.est_id AS estadoDau
                    FROM
                        dau.dau
                    WHERE
                        dau.dau.dau_id = '{$idDau}'
                    ";

            $respuesta = $objCon->consultaSQL($sql,"Erro al verificar si existe solicitud de Alta Urgencia");

            return $respuesta;

        }

    }
?>