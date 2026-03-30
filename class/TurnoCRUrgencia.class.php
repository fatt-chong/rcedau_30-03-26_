<?php
class TurnoCRUrgencia{
    function actualizarTurnoEsperaAtencion ( $objCon, $parametros ) {
        $campoActualizar = $this->campoActualizarSegunCategorizacionYTipoPaciente($parametros['tipoCategorizacion'], $parametros['tipoPaciente']);
        $sql = "UPDATE
                    dau.turno_espera_atencion
                SET
                    {$campoActualizar} = '{$parametros['totalPacientes']}'
                WHERE
                    idTurnoEsperaAtencion = '{$parametros['idTurnoEsperaAtencion']}' ";
        $respuesta = $objCon->ejecutarSQL($sql, "Error al Actualizar Total de Pacientes Según Categorización");
    }
    function actualizarTurnoTiemposPromedioCategorizacion ( $objCon, $parametros ) {
        $sql = "UPDATE
                    dau.turno_tiempos_promedio_categorizacion
                SET
                    totalPacientes_CategorizacionInicioAtencion = '{$parametros['totalPacientes_CategorizacionInicioAtencion']}',
                    tiempoPromedio_CategorizacionInicioAtencion = '{$parametros['tiempoPromedio_CategorizacionInicioAtencion']}',
                    totalPacientes_InicioAtencionCierreAtencion = '{$parametros['totalPacientes_InicioAtencionCierreAtencion']}',
                    tiempoPromedio_InicioAtencionCierreAtencion = '{$parametros['tiempoPromedio_InicioAtencionCierreAtencion']}',
                    totalPacientes_CierreAtencionAplicacionCierre = '{$parametros['totalPacientes_CierreAtencionAplicacionCierre']}',
                    tiempoPromedio_CierreAtencionAplicacionCierre = '{$parametros['tiempoPromedio_CierreAtencionAplicacionCierre']}'
                WHERE
                    idTurnoTiemposPromedioCategorizacion = '{$parametros['idTurnoTiemposPromedioCategorizacion']}'
                AND
                    tipoCategorizacion = '{$parametros['tipoCategorizacion']}' ";
        $respuesta = $objCon->ejecutarSQL($sql, "Error al Actualizar Turno de Promedio según Categorización");
    }
    function campoActualizarSegunCategorizacionYTipoPaciente ( $tipoCategorizacion, $tipoPaciente ) {
        $campoActualizar = '';
        if ( $tipoPaciente === 'adulto' ) {
            switch ( $tipoCategorizacion ) {
                case '':
                    $campoActualizar = 'numeroEsperaAtencionAdultoSC';
                break;
                case 'ESI-1':
                    $campoActualizar = 'numeroEsperaAtencionAdultoC1';
                break;
                case 'ESI-2':
                    $campoActualizar = 'numeroEsperaAtencionAdultoC2';
                break;
                case 'ESI-3':
                    $campoActualizar = 'numeroEsperaAtencionAdultoC3';
                break;
                case 'ESI-4':
                    $campoActualizar = 'numeroEsperaAtencionAdultoC4';
                break;
                case 'ESI-5':
                    $campoActualizar = 'numeroEsperaAtencionAdultoC5';
                break;
            }
        }
        if ( $tipoPaciente === 'pediatrico' ) {
            switch ( $tipoCategorizacion ) {
                case '':
                    $campoActualizar = 'numeroEsperaAtencionPediatricoSC';
                break;
                case 'ESI-1':
                    $campoActualizar = 'numeroEsperaAtencionPediatricoC1';
                break;
                case 'ESI-2':
                    $campoActualizar = 'numeroEsperaAtencionPediatricoC2';
                break;
                case 'ESI-3':
                    $campoActualizar = 'numeroEsperaAtencionPediatricoC3';
                break;
                case 'ESI-4':
                    $campoActualizar = 'numeroEsperaAtencionPediatricoC4';
                break;
                case 'ESI-5':
                    $campoActualizar = 'numeroEsperaAtencionPediatricoC5';
                break;
            }
        }
        return $campoActualizar;
    }
    function insertarTurnoCirugiasRealizadas ( $objCon, $parametros ) {
        $sql = "INSERT INTO
                    dau.turno_cirugias_realizadas(
                        idTurnoCRUrgencia,
                        codigoCirugia,
                        nombrePaciente,
                        runPaciente,
                        numeroCirujano,
                        tipoCirugia
                    )
                VALUES(
                        '{$parametros['idTurnoCRUrgencia']}',
                        '{$parametros['codigoCirugia']}',
                        '{$parametros['nombrePaciente']}',
                        '{$parametros['runPaciente']}',
                        '{$parametros['numeroCirujano']}',
                        '{$parametros['tipoCirugia']}'
                    ) ";
        $respuesta = $objCon->ejecutarSQL($sql, "Error al Insertar en Turno CR Urgencia las Cirugías Realizadas");
        $idTurnoCirugiasRealizadas = $objCon->lastInsertId($sql);
        return $idTurnoCirugiasRealizadas;
    }
    function insertarTurnoCirugiasRealizadasDetalle ( $objCon, $parametros ) {
        $sql = "INSERT INTO
                    dau.turno_cirugias_realizadas_detalle(
                        idTurnoCirugiasRealizadas,
                        glosaCirugia,
                        codigoPrestacion
                    )
                VALUES(
                        '{$parametros['idTurnoCirugiasRealizadas']}',
                        '{$parametros['glosaCirugia']}',
                        '{$parametros['codigoPrestacion']}'
                    ) ";
        $respuesta = $objCon->ejecutarSQL($sql, "Error al Insertar en Turno CR Urgencia el detalle de las Cirugías Realizadas");
    }



    function insertarNumeroHospitalizaciones ( $objCon, $parametros ) {
        $sql = "INSERT INTO
                    dau.turno_hospitalizaciones(
                        idTurnoCRUrgencia,
                        numeroHospitalizacionesAdulto,
                        numeroHospitalizacionesPediatrico,
                        numeroHospitalizacionesGinecologico
                    )
                VALUES(
                        '{$parametros['idTurnoCRUrgencia']}',
                        '{$parametros['numeroHospitalizacionesAdulto']}',
                        '{$parametros['numeroHospitalizacionesPediatrico']}',
                        '{$parametros['numeroHospitalizacionesGinecologico']}'
                    ) ";
        $respuesta = $objCon->ejecutarSQL($sql, "Error al Insertar en Turno CR Urgencia el número de Hospitalizaciones");
    }



    function insertarNumeroHospitalizacionesUrgencia ( $objCon, $parametros ) {
        $sql = "INSERT INTO
                    dau.turno_hospitalizaciones_urgencia(
                        idTurnoCRUrgencia,
                        numeroHospitalizacionesAdulto,
                        numeroHospitalizacionesAdulto12,
                        numeroHospitalizacionesAdulto24,
                        numeroHospitalizacionesPediatrico,
                        numeroHospitalizacionesPediatrico12,
                        numeroHospitalizacionesPediatrico24
                    )
                VALUES(
                        '{$parametros['idTurnoCRUrgencia']}',
                        '{$parametros['numeroHospitalizacionesAdulto']}',
                        '{$parametros['numeroHospitalizacionesAdulto12']}',
                        '{$parametros['numeroHospitalizacionesAdulto24']}',
                        '{$parametros['numeroHospitalizacionesPediatrico']}',
                        '{$parametros['numeroHospitalizacionesPediatrico12']}',
                        '{$parametros['numeroHospitalizacionesPediatrico24']}'
                    ) ";
        $respuesta = $objCon->ejecutarSQL($sql, "Error al Insertar en Turno CR Urgencia el número de Hospitalizaciones de Urgencia");
    }
    function insertarPacientesEspera ( $objCon, $idTurnoCRUrgencia ) {
        $sql = "INSERT INTO
                    dau.turno_espera_atencion(
                        idTurnoCRUrgencia
                    )
                VALUES(
                        '{$idTurnoCRUrgencia}'
                    ) ";
        $respuesta = $objCon->ejecutarSQL($sql, "Error al Insertar Turno Espera Pacientes");
        $idTurnoEsperaAtencion = $objCon->lastInsertId($sql);
        return $idTurnoEsperaAtencion;
    }
    function insertarTiemposAtencion ( $objCon, $parametros ) {
        $sql = "INSERT INTO
                    dau.turno_tiempos_atencion(
                        idTurnoCRUrgencia,
                        cantidadPacientesAtendidos,
                        tiempoPromedioAtencion,
                        tiempoMinimoAtencion,
                        tiempoMaximoAtencion
                    )
                VALUES(
                        '{$parametros['idTurnoCRUrgencia']}',
                        '{$parametros['cantidadPacientesAtendidos']}',
                        '{$parametros['tiempoPromedioAtencion']}',
                        '{$parametros['tiempoMinimoAtencion']}',
                        '{$parametros['tiempoMaximoAtencion']}'
                    ) ";
        $respuesta = $objCon->ejecutarSQL($sql, "Error al Insertar Turno Tiempos Atención");
    }
    function insertarTurnoCRUrgencia ( $objCon, $parametros ) {
         $sql = "INSERT INTO
                    dau.turno_cr_urgencia(
                        idTipoHorarioTurno,
                        fechaEntregaTurno,
                        profesionalEntregaTurno,
                        profesionalRecibeTurno,
                        novedadesTurno,
                        novedades_general,
                        novedades_adm,
                        novedades_infra,
                        novedades_equip,
                        novedades_eventos,
                        novedades_turno_si_no,
                        med_jef_turno_rut,
                        med_jef_turno_nombre,
                        enf_jef_turno_rut,
                        enf_jef_turno_nombre,
                        tipo,
                        entrega_conforme,
                        entrega_no_motivo,
                        bic_cantidad,
                        ecografo_disponible,
                        ecografo_no_motivo,
                        celulares_cantidad
                    )
                VALUES(
                        '{$parametros['idTipoHorarioTurno']}',
                        '{$parametros['frm_fechaActualTurno']}',
                        '{$parametros['profesionalEntregaTurno']}',
                        '{$parametros['profesionalRecibeTurno']}',
                        '{$parametros['novedadesTurno']}',
                        '{$parametros['novedades_general']}',
                        '{$parametros['novedades_adm']}',
                        '{$parametros['novedades_infra']}',
                        '{$parametros['novedades_equip']}',
                        '{$parametros['novedades_eventos']}',
                        '{$parametros['novedades_turno_si_no']}',
                        '{$parametros['med_jef_turno_rut']}',
                        '{$parametros['med_jef_turno_nombre']}',
                        '{$parametros['enf_jef_turno_rut']}',
                        '{$parametros['enf_jef_turno_nombre']}',
                        '{$parametros['tipo']}',
                        '{$parametros['entrega_conforme']}',
                        '{$parametros['entrega_no_motivo']}',
                        '{$parametros['bic_cantidad']}',
                        '{$parametros['ecografo_disponible']}',
                        '{$parametros['ecografo_no_motivo']}',
                        '{$parametros['celulares_cantidad']}'
                    ) ";
        $respuesta = $objCon->ejecutarSQL($sql, "Error al Insertar Turno CR Urgencia");
        $idTurnoCRUrgencia = $objCon->lastInsertId($sql);
        return $idTurnoCRUrgencia;
    }
    function insertarTurnoMedicos ( $objCon, $parametros ) {
         $sql = "INSERT INTO
                    dau.turno_equipo_detalle(
                        idTurnoCRUrgencia,
                        rut,
                        nombre,
                        tipo,
                        nombre_tipo
                    )
                VALUES(
                        '{$parametros['idTurnoCRUrgencia']}',
                        '{$parametros['rut']}',
                        '{$parametros['nombre']}',
                        '{$parametros['tipo']}',
                        '{$parametros['nombre_tipo']}'
                    ) ";
        $respuesta = $objCon->ejecutarSQL($sql, "Error al insertarTurnoMedicos");
        $idTurnoCRUrgencia = $objCon->lastInsertId($sql);
        return $idTurnoCRUrgencia;
    }
    function insertarTurnoEsperaAtencionDetalle ( $objCon, $parametros ) {
        $tipoPaciente = $this->tipoPaciente($parametros['tipoPaciente']);
        $sql = "INSERT INTO
                    dau.turno_espera_atencion_detalle(
                        idTurnoEsperaAtencion,
                        tipoPaciente,
                        tipoCategorizacion,
                        numeroDau,
                        nombrePaciente,
                        fechaNacimientoPaciente,
                        tiempoEsperaPaciente
                    )
                VALUES(
                        '{$parametros['idTurnoEsperaAtencion']}',
                        '{$tipoPaciente}',
                        '{$parametros['tipoCategorizacion']}',
                        '{$parametros['numeroDau']}',
                        '{$parametros['nombrePaciente']}',
                        '{$parametros['fechaNacimientoPaciente']}',
                        '{$parametros['tiempoEsperaPaciente']}'
                    ) ";
        $respuesta = $objCon->ejecutarSQL($sql, "Error al Insertar en Turno Espera Atención Detalle, el detalle del Paciente");
    }
    function insertarTurnoSolicitudesEspecialista ( $objCon, $parametros ) {
        $sql = "INSERT INTO
                    dau.turno_solicitudes_especialistas(
                        idTurnoCRUrgencia,
                        idDau,
                        nombrePaciente,
                        fechaSolicitudEspecialista,
                        gestionRealizada,
                        descripcionProfesionalEspecialista,
                        descripcionEstadoSolicitud
                    )
                VALUES(
                        '{$parametros['idTurnoCRUrgencia']}',
                        '{$parametros['idDau']}',
                        '{$parametros['nombrePaciente']}',
                        '{$parametros['fechaSolicitudEspecialista']}',
                        '{$parametros['gestionRealizada']}',
                        '{$parametros['descripcionProfesionalEspecialista']}',
                        '{$parametros['descripcionEstadoSolicitud']}'
                    ) ";
        $objCon->ejecutarSQL($sql, "Error al Insertar Turno Solicitudes de Especialista");
    }
    function insertarTurnoTiemposPromedio ( $objCon, $idTurnoCRUrgencia ) {
        $sql = "INSERT INTO
                    dau.turno_tiempos_promedio(
                        idTurnoCRUrgencia
                    )
                VALUES(
                    '{$idTurnoCRUrgencia}'
                    ) ";
        $respuesta              = $objCon->ejecutarSQL($sql, "Error al Insertar Turno Tiempos Promedios");
        $idTurnoTiemposPromedio = $objCon->lastInsertId($sql);
        return $idTurnoTiemposPromedio;
    }
    function insertarTurnoTiemposPromedioCategorizacion ( $objCon, $parametros ) {
        $sql = "INSERT INTO
                    dau.turno_tiempos_promedio_categorizacion(
                        idTurnoCRUrgencia,
                        tipoCategorizacion
                    )
                VALUES(
                    '{$parametros['idTurnoCRUrgencia']}',
                    '{$parametros['tipoCategorizacion']}'
                    )";
        $respuesta                              = $objCon->ejecutarSQL($sql, "Error al Insertar Turno Tiempos Promedios por Categorización");
        $idTurnoTiemposPromedioCategorizacion   = $objCon->lastInsertId($sql);
        return $idTurnoTiemposPromedioCategorizacion;
    }
    function obtenerCirugiasRealizadas ( $objCon, $parametros ) {
        $sql = "SELECT
                    pabnet.protocolo_has_prestacion.tabla_quirurgica_tq_id AS idTablaQuirurgica,
                    pabnet.solicitudes.sol_id AS idSolicitud,
                    CONCAT(paciente.paciente.nombres,' ',paciente.paciente.apellidopat,' ',paciente.paciente.apellidomat) AS nombrePaciente,
                    paciente.paciente.rut AS runPaciente,
                    pabnet.solicitudes_has_medicos.sol_PROcodigo,
                    pabnet.solicitudes.sol_fecha_pabellon,
                    pabnet.tabla_quirurgica.tq_estado,
                    pabnet.tipo_estado.tipoestado_descripcion,
                    le.intervencion.nom_intervencion AS nombreIntervencion,
                    le.intervencion.id_intervencion AS idIntervencion,
                    pabnet.tipo_solicitud.tip_sol_descripcion AS descripcionTipoSolicitud,
                    pabnet.solicitudes_has_medicos.sol_tipo_profesional_id
                FROM
                    pabnet.solicitudes
                INNER JOIN pabnet.solicitudes_has_medicos ON pabnet.solicitudes.sol_id = pabnet.solicitudes_has_medicos.sol_id
                INNER JOIN pabnet.tabla_quirurgica ON pabnet.solicitudes.sol_id = pabnet.tabla_quirurgica.sol_id
                INNER JOIN pabnet.tipo_estado ON pabnet.tabla_quirurgica.tq_estado = pabnet.tipo_estado.tipoestado_id
                INNER JOIN pabnet.tipo_solicitud ON pabnet.solicitudes.sol_tipo_cirugia = pabnet.tipo_solicitud.tip_sol_id
                INNER JOIN pabnet.protocolo ON pabnet.tabla_quirurgica.tq_id = pabnet.protocolo.tabla_quirurgica_tq_id
                INNER JOIN pabnet.protocolo_has_prestacion ON pabnet.protocolo.tabla_quirurgica_tq_id = pabnet.protocolo_has_prestacion.tabla_quirurgica_tq_id
                INNER JOIN le.intervencion ON pabnet.protocolo_has_prestacion.prestacion_id = le.intervencion.id_intervencion
                INNER JOIN paciente.paciente ON pabnet.solicitudes.sol_id_paciente = paciente.paciente.id
                WHERE
                    pabnet.solicitudes_has_medicos.sol_tipo_profesional_id = 1
                AND
                solicitudes.sol_tipo_ingreso_paciente in (1,2)
                AND
                    pabnet.tabla_quirurgica.tq_estado <> 8
                ## AND pabnet.solicitudes.sol_fecha_pabellon BETWEEN DATE('2025-02-05') AND DATE(NOW())
                AND pabnet.solicitudes.sol_fecha_pabellon BETWEEN DATE('{$parametros['fechaAnterior']}') AND DATE(NOW())
                GROUP BY
                    pabnet.solicitudes.sol_id, le.intervencion.id_intervencion
                ";
        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar por la cantidad de Cirugías Realizadas");
        return $respuesta;
    }
    function obtenerInfoProfesionalPorId ( $objCon, $idProfesional ) {
        $sql = "SELECT
                    acceso.usuario.nombreUsuario,
                    acceso.usuario.idusuario,
                    acceso.usuario.rutusuario
                FROM
                    acceso.usuario
                WHERE
                    acceso.usuario.idusuario = '{$idProfesional}' ";
        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar información de Profesional");
        return $respuesta[0];
    }
    function obtenerInfoProfesionalPorRun ( $objCon, $runProfesional ) {
        $sql = "SELECT
                    acceso.usuario.nombreUsuario,
                    acceso.usuario.idusuario,
                    acceso.usuario.rutusuario
                FROM
                    acceso.usuario
                WHERE
                    acceso.usuario.rutusuario = '{$runProfesional}' ";
        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar información de Profesional");
        return $respuesta[0];
    }
    function obtenerturno_hospitalizaciones_detalle ( $objCon, $idTurnoCRUrgencia, $tipo ) {
        $sql = "SELECT
                     * 
                FROM
                    dau.turno_hospitalizaciones_detalle
                WHERE
                    dau.turno_hospitalizaciones_detalle.idTurnoCRUrgencia = '{$idTurnoCRUrgencia}'
                    and 
                    dau.turno_hospitalizaciones_detalle.tipo = '{$tipo}' ";

        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar por la información del Turno CR de Urgencia");
        return $respuesta;
    }
    function obtenerturno_equipo_detalle ( $objCon, $idTurnoCRUrgencia, $tipo ) {
        $sql = "SELECT
                     * 
                FROM
                    dau.turno_equipo_detalle
                WHERE
                    dau.turno_equipo_detalle.idTurnoCRUrgencia = '{$idTurnoCRUrgencia}'
                    and 
                    dau.turno_equipo_detalle.tipo = '{$tipo}' ";

        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar por la información del Turno CR de Urgencia");
        return $respuesta;
    }
    function obtenerInfoTurnoCRUrgencia ( $objCon, $idTurnoCRUrgencia ) {
        $sql = "SELECT
                    dau.turno_cr_urgencia.*,
                    profesionalEntregaTurno.nombreusuario AS nombreProfesionalEntregaTurno,
                    profesionalRecibeTurno.nombreusuario AS nombreProfesionalRecibeTurno,
                    dau.turno_tipo_horario.descripcionHorarioTurno
                FROM
                    dau.turno_cr_urgencia
                INNER JOIN
                    acceso.usuario AS profesionalEntregaTurno ON dau.turno_cr_urgencia.profesionalEntregaTurno = profesionalEntregaTurno.idusuario
                INNER JOIN
                    acceso.usuario AS profesionalRecibeTurno ON dau.turno_cr_urgencia.profesionalRecibeTurno = profesionalRecibeTurno.idusuario
                INNER JOIN
                    dau.turno_tipo_horario  ON dau.turno_cr_urgencia.idTipoHorarioTurno = dau.turno_tipo_horario.idTipoHorarioTurno
                WHERE
                    dau.turno_cr_urgencia.idTurnoCRUrgencia = '{$idTurnoCRUrgencia}' ";

        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar por la información del Turno CR de Urgencia");
        return $respuesta[0];
    }
    function obtenerInfoTurnoCRUrgenciaSegunParametros ( $objCon, $parametros, &$totalPag, &$total ) {
        require_once("Util.class.php");       $objUtil    = new Util;
        if ( $_SESSION['pagina_actual'] < 1 ) {
            $_SESSION['pagina_actual'] = 1;
        }
        $limit = 10;
        $offset = ($_SESSION['pagina_actual']-1) * $limit;
        $sql = "SELECT
                    dau.turno_cr_urgencia.*,
                    dau.turno_tipo_horario.descripcionHorarioTurno,
                    profesionalEntregaTurno.nombreusuario AS nombreProfesionalEntregaTurno,
                    profesionalRecibeTurno.nombreusuario AS nombreProfesionalRecibeTurno
                FROM
                dau.turno_cr_urgencia
                INNER JOIN dau.turno_tipo_horario ON dau.turno_cr_urgencia.idTipoHorarioTurno = dau.turno_tipo_horario.idTipoHorarioTurno
                INNER JOIN acceso.usuario AS profesionalEntregaTurno ON  CONVERT(CAST(dau.turno_cr_urgencia.profesionalEntregaTurno as BINARY) USING latin1) COLLATE latin1_spanish_ci = profesionalEntregaTurno.idusuario
                INNER JOIN acceso.usuario AS profesionalRecibeTurno ON  CONVERT(CAST(dau.turno_cr_urgencia.profesionalRecibeTurno as BINARY) USING latin1) COLLATE latin1_spanish_ci = profesionalRecibeTurno.idusuario  ";
        if ( $parametros['frm_fechaResumenTurno'] ) {
            $condicion .= ($condicion == "") ? " WHERE " : " AND ";
        	$parametros['frm_fechaResumenTurno'] = $parametros['frm_fechaResumenTurno'];
        	$condicion .= " DATE(dau.turno_cr_urgencia.fechaEntregaTurno) = '{$parametros['frm_fechaResumenTurno']}' ";
        }
        if ( $parametros['frm_tipoHorarioTurno'] ) {
            $condicion .= ($condicion == "") ? " WHERE " : " AND ";
        	$condicion .= " dau.turno_cr_urgencia.idTipoHorarioTurno = '{$parametros['frm_tipoHorarioTurno']}' ";
        }
        if ( $parametros['tipo'] ) {
            $condicion .= ($condicion == "") ? " WHERE " : " AND ";
            $condicion .= " dau.turno_cr_urgencia.tipo = '{$parametros['tipo']}' ";
        }
        $sql    .= $condicion;
        $sql    .= " ORDER BY dau.turno_cr_urgencia.idTurnoCRUrgencia DESC ";
        $datos  = $objCon->consultaSQL($sql, '');
        $sqlTotalResultados = " SELECT FOUND_ROWS() as totalResultados";
        $totalResultados = $objCon->consultaSQL($sqlTotalResultados,"Error al obtener el total de resultado de información de Turnos CR Urgencia según parámetros de búsqueda");
        $total    = $totalResultados[0]["totalResultados"];
        $sql  .= " LIMIT $offset, $limit";
        $datos = $objCon->consultaSQL($sql,"Error al obtener resultado de Turnos CR Urgencia de acuerdo a los parámetros de búsqueda");
        $totalPag = ceil($total/$limit);
        return $datos;
    }
    function obtenerNumeroHospitalizaciones ( $objCon, $parametros ) {
        $sql = "SELECT
                    SUM( IF ( dau.dau_atencion = 1, 1, 0 ) ) AS cantidadAdultoTotal,
                    SUM( IF ( dau.dau_atencion = 2, 1, 0 ) ) AS cantidadPediatricoTotal,
                    SUM( IF ( dau.dau_atencion = 3, 1, 0 ) ) AS cantidadGinecologicoTotal
                FROM
                    dau.dau
                WHERE
                    dau.dau.dau_indicacion_egreso = 4
                AND
                    dau.est_id IN (4, 5)
                AND
                    dau.dau.dau_admision_fecha BETWEEN '{$parametros['fechaAnterior']}' AND NOW()  ";
        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar por la cantidad de Hospitalizaciones en Urgencia");
        return $respuesta[0];
    }
    function obtenerNumeroHospitalizacionesUrgencia ( $objCon ) {
        $sql = "SELECT
                    SUM(IF(dau.dau_atencion = 1, 1, 0)) as cantidadAdultoTotal,
                    SUM(IF(dau.dau_atencion = 2, 1, 0)) as cantidadPediatricoTotal
                FROM
                    dau.dau
                WHERE
                    dau.dau_indicacion_egreso = 4
                AND
                    dau.est_id = 4
                AND
                    dau.dau_atencion IN ( 1, 2 )
                AND
                    TIMESTAMPDIFF(SECOND, dau.dau_indicacion_egreso_fecha,NOW()) BETWEEN 0 AND 43200 ";
        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar por la cantidad de Hospitalizaciones en Urgencia con espera de menos de 12 Horas");
        return $respuesta[0];
    }
    function obtenerNumeroHospitalizacionesUrgencia12 ( $objCon ) {
        $sql = "SELECT
                    SUM(IF(dau.dau_atencion = 1, 1, 0)) as cantidadAdultoTotal,
                    SUM(IF(dau.dau_atencion = 2, 1, 0)) as cantidadPediatricoTotal
                FROM
                    dau.dau
                WHERE
                    dau.dau_indicacion_egreso = 4
                AND
                    dau.est_id = 4
                AND
                    dau.dau_atencion IN ( 1, 2 )
                AND
                    TIMESTAMPDIFF(SECOND, dau.dau_indicacion_egreso_fecha,NOW()) BETWEEN 43200 AND 86400 ";
        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar por la cantidad de Hospitalizaciones en Urgencia con espera de más de 12 Horas");
        return $respuesta[0];
    }
    function obtenerNumeroHospitalizacionesUrgencia24 ( $objCon ) {
        $sql = "SELECT
                    SUM(IF(dau.dau_atencion = 1, 1, 0)) as cantidadAdultoTotal,
                    SUM(IF(dau.dau_atencion = 2, 1, 0)) as cantidadPediatricoTotal
                FROM
                    dau.dau
                WHERE
                    dau.dau_indicacion_egreso = 4
                AND
                    dau.dau_atencion IN ( 1, 2 )
                AND
                    dau.est_id = 4
                AND
                    TIMESTAMPDIFF(SECOND, dau.dau_indicacion_egreso_fecha,NOW()) > 86400 ";
        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar por la cantidad de Hospitalizaciones en Urgencia con espera de más de 24 Horas");
        return $respuesta[0];
    }
    function obtenerPacientesEnEsperaAtencion ( $objCon, $tipoCategorizacion, $tipoPaciente ) {
        $tipoPaciente = $this->tipoPaciente($tipoPaciente);
        $sql = "SELECT
                    CONCAT(paciente.paciente.nombres,' ',paciente.paciente.apellidopat,' ',paciente.paciente.apellidomat) AS nombrePaciente,
                    paciente.paciente.fechanac,
                    dau.dau.dau_id,
                    TIMEDIFF(NOW(),dau.dau_admision_fecha) AS tiempoEsperaSinCategorizacion,
                    TIMEDIFF(NOW(),dau.dau_categorizacion_fecha) AS tiempoEsperaConCategorizacion
                FROM
                    dau.dau
                INNER JOIN
                    paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
                WHERE
                    dau.est_id IN (1,2)
                AND
                    dau.dau_atencion = '{$tipoPaciente}' ";
                if ( $tipoCategorizacion !== '' ) {
                    $condicion = " AND dau.dau_categorizacion = '{$tipoCategorizacion}' ";
                } else {
                    $condicion = " AND dau.dau_categorizacion IS NULL ";
                }
                $condicion .= " ORDER BY dau.dau_id ASC ";
        $sql .= $condicion;
        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar por la cantidad de Pacientes esperando Atención");
        return $respuesta;
    }
    
    function obtenerSolicitudesEspecialistas ( $objCon, $parametros ) {
        $sql = "SELECT
                    rce.registroclinico.dau_id AS idDau,
                    CONCAT(paciente.paciente.nombres,' ',paciente.paciente.apellidopat,' ',paciente.paciente.apellidomat) AS nombrePaciente,
                    rce.solicitud_especialista.SESPfecha AS fechaSolicitudEspecialista,
                    rce.solicitud_especialista.SESPespecialistaDeLlamado,
                    rce.solicitud_especialista.SESPgestionRealizada AS gestionRealizada,
                    rce.solicitud_especialista.SESPobservacionGestionRealizada AS observacionGestionRealizada,
                    rce.estado_indicacion.est_descripcion AS descripcionEstadoSolicitud,
                    parametros_clinicos.profesional.PROdescripcion AS descripcionProfesionalEspecialista
                FROM
                    rce.solicitud_especialista
                INNER JOIN
                    rce.registroclinico ON rce.solicitud_especialista.SESPidRCE = rce.registroclinico.regId
                INNER JOIN
                    paciente.paciente ON rce.solicitud_especialista.SESPidPaciente = paciente.paciente.id
                INNER JOIN
                    rce.estado_indicacion ON rce.solicitud_especialista.SESPestado = rce.estado_indicacion.est_id
                LEFT JOIN
                    parametros_clinicos.profesional ON rce.solicitud_especialista.SESPidProfesionalEspecialista = parametros_clinicos.profesional.PROcodigo
                WHERE
                    rce.solicitud_especialista.SESPfecha BETWEEN '{$parametros['fechaAnterior']}' AND NOW()
                AND
                    rce.solicitud_especialista.SESPespecialistaDeLlamado = 'S'
                ";
        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar por las solicitudes de especialista");
        return $respuesta;
    }
    function obtenerTiemposAtencion ( $objCon, $parametros ) {
        $sql = "SELECT
                    count(*) AS totalFilas,
                    SUM(TIMESTAMPDIFF(SECOND,dau.dau.dau_admision_fecha,dau.dau.dau_inicio_atencion_fecha)) as totalSegundos,
                    MIN(
                        DATE_FORMAT(
                            SEC_TO_TIME(
                                (TIMESTAMPDIFF
                                    (SECOND, TIME(dau.dau.dau_admision_fecha), TIME(dau.dau.dau_inicio_atencion_fecha) )
                                )
                            ),
                        '%H:%i:%s'
                        )
                    ) AS tiempoMinimoAtencion,
                    MAX(
                        DATE_FORMAT(
                            SEC_TO_TIME(
                                (TIMESTAMPDIFF
                                    (SECOND, TIME(dau.dau.dau_admision_fecha), TIME(dau.dau.dau_inicio_atencion_fecha) )
                                )
                            ),
                        '%H:%i:%s'
                        )
                    ) AS tiempoMaximoAtencion
                FROM
                    dau.dau
                WHERE
                    dau.dau.dau_admision_fecha BETWEEN '{$parametros['fechaAnterior']}' AND NOW()
                AND
                    dau.dau.dau_inicio_atencion_fecha IS NOT NULL
                AND
                    dau.dau.dau_inicio_atencion_usuario = '{$parametros['idProfesional']}'
                ORDER BY
                    dau.dau.dau_id ";
        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar por los tiempos de atención en Turno CR de Urgencia");
        return $respuesta[0];
    }
    function obtenerTiemposPromedioCategorizacionInicioAtencion ( $objCon, $parametros ) {
        $sql = "SELECT
                    count( * ) AS totalFilas,
                    SUM( TIMESTAMPDIFF( SECOND, dau.dau.dau_categorizacion_fecha, dau.dau.dau_inicio_atencion_fecha ) ) AS totalSegundos
                FROM
                    dau.dau
                WHERE
                    dau.dau.dau_inicio_atencion_fecha IS NOT NULL
                AND
                    dau.dau.dau_admision_fecha BETWEEN '{$parametros['fechaAnterior']}' AND NOW()
                AND
                    dau.dau.dau_categorizacion = '{$parametros['tipoCategorizacion']}' ";

        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar por los tiempos promedio desde admisión a inicio de atención en Turno CR de Urgencia");
        return $respuesta[0];
    }
    function obtenerTiemposPromedioInicioAtencionCierreAtencion ( $objCon, $parametros ) {
        $sql = "SELECT
                    count( * ) AS totalFilas,
                    SUM( TIMESTAMPDIFF( SECOND, dau.dau.dau_inicio_atencion_fecha, dau.dau.dau_indicacion_egreso_fecha ) ) AS totalSegundos
                FROM
                    dau.dau
                WHERE
                    dau.dau.dau_indicacion_egreso_fecha IS NOT NULL
                AND
                    dau.dau.dau_admision_fecha BETWEEN '{$parametros['fechaAnterior']}' AND NOW()
                AND
                    dau.dau.dau_categorizacion = '{$parametros['tipoCategorizacion']}' ";
        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar por los tiempos promedio desde inicio de atención a indicación de egreso en Turno CR de Urgencia");
        return $respuesta[0];
    }
    function obtenerTiemposPromedioCierreAtencionAplicacionCierre ( $objCon, $parametros ) {
        $sql = "SELECT
                    count( * ) AS totalFilas,
                    SUM( TIMESTAMPDIFF( SECOND, dau.dau.dau_indicacion_egreso_fecha, dau.dau.dau_indicacion_egreso_aplica_fecha ) ) AS totalSegundos
                FROM
                    dau.dau
                WHERE
                    dau.dau.dau_indicacion_egreso_aplica_fecha IS NOT NULL
                AND
                    dau.dau.dau_admision_fecha BETWEEN '{$parametros['fechaAnterior']}' AND NOW()
                AND
                    dau.dau.dau_categorizacion = '{$parametros['tipoCategorizacion']}' ";
        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar por los tiempos promedio desde indicación de egreso hasta aplicación de cierre en Turno CR de Urgencia");
        return $respuesta[0];
    }
    function obtenerTipoHorarioTurno ( $objCon ) {
        $sql = "    SELECT
                        *
                    FROM
                        dau.turno_tipo_horario ";
        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar por los Tipo de Horarios de Turno");
        return $respuesta;
    }
    function obtenerTipoHorarioTurnoParametros ( $objCon,$parametros ) {
        $sql = "    SELECT
                        *
                    FROM
                        dau.turno_tipo_horario 
                    WHERE 
                    dau.turno_tipo_horario.tipo_entrega = '{$parametros['tipo_entrega']}'  ";
        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar por los Tipo de Horarios de Turno");
        return $respuesta;
    }
    function pdfObtenerCirugiasRealizadas ( $objCon, $idTurnoCRUrgencia ) {
        $sql = "SELECT
                    dau.turno_cirugias_realizadas.*
                FROM
                    dau.turno_cirugias_realizadas
                WHERE
                    dau.turno_cirugias_realizadas.idTurnoCRUrgencia = '{$idTurnoCRUrgencia}' ";
         $respuesta   = $objCon->consultaSQL($sql, "Error al consultar por PDF Cirugías Realizadas");
        return $respuesta;
    }
    function pdfObtenerCirugiasRealizadasDetalle ( $objCon, $idTurnoCirugiasRealizadas ) {
        $sql = "SELECT
                    dau.turno_cirugias_realizadas_detalle.*
                FROM
                    dau.turno_cirugias_realizadas_detalle
                WHERE
                    dau.turno_cirugias_realizadas_detalle.idTurnoCirugiasRealizadas = '{$idTurnoCirugiasRealizadas}' ";

        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar por PDF Cirugías Realizadas Detalle");
        return $respuesta;
    }
    function pdfObtenerNumeroHospitalizacion ( $objCon, $idTurnoCRUrgencia ) {
        $sql = "SELECT
                    dau.turno_hospitalizaciones.numeroHospitalizacionesAdulto,
                    dau.turno_hospitalizaciones.numeroHospitalizacionesPediatrico,
                    dau.turno_hospitalizaciones.numeroHospitalizacionesGinecologico
                FROM
                    dau.turno_hospitalizaciones
                WHERE
                    dau.turno_hospitalizaciones.idTurnoCRUrgencia = '{$idTurnoCRUrgencia}' ";
        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar PDF Numero de Hospitalizaciones");
        return $respuesta[0];
    }
    function pdfObtenerNumeroHospitalizacionesUrgencia ( $objCon, $idTurnoCRUrgencia ) {
        $sql = "SELECT
                    dau.turno_hospitalizaciones_urgencia.numeroHospitalizacionesAdulto,
                    dau.turno_hospitalizaciones_urgencia.numeroHospitalizacionesAdulto12,
                    dau.turno_hospitalizaciones_urgencia.numeroHospitalizacionesAdulto24,
                    dau.turno_hospitalizaciones_urgencia.numeroHospitalizacionesPediatrico,
                    dau.turno_hospitalizaciones_urgencia.numeroHospitalizacionesPediatrico12,
                    dau.turno_hospitalizaciones_urgencia.numeroHospitalizacionesPediatrico24
                FROM
                    dau.turno_hospitalizaciones_urgencia
                WHERE
                    dau.turno_hospitalizaciones_urgencia.idTurnoCRUrgencia = '{$idTurnoCRUrgencia}' ";
        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar PDF Numero de Hospitalizaciones de Urgencia");
        return $respuesta[0];

    }
    function pdfObtenerPacientesEnEsperaAtencion ( $objCon, $idTurnoCRUrgencia ) {
        $sql = "SELECT
                    dau.turno_espera_atencion.*
                FROM
                    dau.turno_espera_atencion
                WHERE
                    dau.turno_espera_atencion.idTurnoCRUrgencia = '{$idTurnoCRUrgencia}' ";
        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar PDF Pacientes en Espera de Atención");
        return $respuesta[0];
    }
    function pdfObtenerPacientesEnEsperaAtencionDetalle ( $objCon, $parametros ) {
        $sql = "SELECT
                    dau.turno_espera_atencion_detalle.*
                FROM
                    dau.turno_espera_atencion_detalle
                WHERE
                    dau.turno_espera_atencion_detalle.idTurnoEsperaAtencion = '{$parametros['idTurnoEsperaAtencion']}'
                AND
                    dau.turno_espera_atencion_detalle.tipoPaciente = '{$parametros['tipoPaciente']}'
                AND
                    dau.turno_espera_atencion_detalle.tipoCategorizacion = '{$parametros['tipoCategorizacion']}' ";
        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar PDF Pacientes en Espera de Atención Detalle");
        return $respuesta;
    }
    function pdfObtenerTiemposAtencion ( $objCon, $idTurnoCRUrgencia ) {
        $sql = "SELECT
                    dau.turno_tiempos_atencion.*,
                    acceso.usuario.nombreusuario  AS nombreProfesional
                FROM
                    dau.turno_tiempos_atencion
                INNER JOIN dau.turno_cr_urgencia
                    ON dau.turno_tiempos_atencion.idTurnoCRUrgencia = dau.turno_cr_urgencia.idTurnoCRUrgencia
                INNER JOIN acceso.usuario
                    ON dau.turno_cr_urgencia.profesionalEntregaTurno = acceso.usuario.idusuario
                WHERE
                    dau.turno_tiempos_atencion.idTurnoCRUrgencia = '{$idTurnoCRUrgencia}' ";
        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar PDF Tiempos de Atención");
        return $respuesta[0];
    }
    function pdfObtenerTiemposPromedioCategorizacion ( $objCon, $parametros ) {
        $sql = "SELECT
                    dau.turno_tiempos_promedio_categorizacion.*
                FROM
                    dau.turno_tiempos_promedio_categorizacion
                WHERE
                    dau.turno_tiempos_promedio_categorizacion.idTurnoCRUrgencia = '{$parametros['idTurnoCRUrgencia']}'
                AND
                    dau.turno_tiempos_promedio_categorizacion.tipoCategorizacion = '{$parametros['tipoCategorizacion']}' ";

        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar PDF Tiempos Promedio según Categorización");

        return $respuesta[0];
    }
    function pdfObtenerSolicitudesEspecialistas ( $objCon, $idTurnoCRUrgencia ) {
        $sql = "SELECT
                    dau.turno_solicitudes_especialistas.*
                FROM
                    dau.turno_solicitudes_especialistas
                WHERE
                    dau.turno_solicitudes_especialistas.idTurnoCRUrgencia = '{$idTurnoCRUrgencia}'
                 ";
        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar PDF Solicitudes de Especialistas");
        return $respuesta;
    }
    function tipoPaciente ( $tipoPaciente ) {
        switch ( $tipoPaciente ) {
            case 'adulto':
                return 1;
            break;
            case 'pediatrico':
                return 2;
            break;
        }
    }
    function verificarProfesionalTieneTurnoUrgencia ( $objCon, $idProfesional ) {
        $sql = "    SELECT
                        parametros_clinicos.profesional.PROturnoCRUrgencia
                    FROM
                        parametros_clinicos.profesional
                    WHERE
                        parametros_clinicos.profesional.PROcodigo = '{$idProfesional}' ";

        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar si Profesional tiene Turno de Urgencia");
        return $respuesta[0];
    }
    function verificarPacientesHospitalizados ( $objCon ) {
        $sql = "  
        SELECT
        CONCAT( paciente.paciente.nombres, ' ', paciente.paciente.apellidopat, ' ', paciente.paciente.apellidomat ) AS nombre,
        dau.dau.dau_id,
        rce.registroclinico.regHipotesisInicial,
        dau.dau.dau_categorizacion_actual,
        dau.sala.sal_descripcion,
        dau.cama.sal_id,
        dau.dau.est_id,
        dau.cama.cam_descripcion,
        dau.tipo_cama.tipo_cama_sigla,
        dau.dau.dau_inicio_atencion_fecha,
        rce.registroclinico.regId,
        COUNT( DISTINCT CASE WHEN rce.solicitud_indicaciones.sol_ind_servicio = 6 AND rce.solicitud_indicaciones.sol_ind_estado = 1 THEN rce.solicitud_indicaciones.sol_ind_id END ) AS Procedimiento,
        COUNT( DISTINCT CASE WHEN rce.solicitud_imagenologia.sol_ima_estado = 1 THEN rce.solicitud_imagenologia.sol_ima_id END ) AS Imagenologia,
        COUNT( DISTINCT CASE WHEN rce.solicitud_indicaciones.sol_ind_servicio = 2 AND rce.solicitud_indicaciones.sol_ind_estado = 1 THEN rce.solicitud_indicaciones.sol_ind_id END ) AS Tratamiento,
        COUNT( DISTINCT CASE WHEN rce.solicitud_laboratorio.sol_lab_estado = 1 THEN rce.solicitud_laboratorio.sol_lab_id END ) AS Laboratorio,
        COUNT( DISTINCT CASE WHEN rce.solicitud_indicaciones.sol_ind_servicio = 4 AND rce.solicitud_indicaciones.sol_ind_estado = 1 THEN rce.solicitud_indicaciones.sol_ind_id END ) AS Otros,
        COUNT( DISTINCT CASE WHEN rce.solicitud_especialista.SESPestado = 1 THEN rce.solicitud_especialista.SESPid END ) + COUNT( DISTINCT CASE WHEN rce.solicitud_otros_especialidad.estado_sol_otro = 1 THEN rce.solicitud_otros_especialidad.id_sol_otro END ) AS Especialidad 
        FROM
        dau.dau
        INNER JOIN dau.cama ON dau.cama.dau_id = dau.dau.dau_id
        INNER JOIN dau.sala ON dau.cama.sal_id = dau.sala.sal_id
        INNER JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
        INNER JOIN dau.tipo_cama ON dau.sala.sal_tipo_cama = dau.tipo_cama.tipo_cama_id
        INNER JOIN rce.registroclinico ON rce.registroclinico.dau_id = dau.dau.dau_id
        LEFT JOIN rce.solicitud_indicaciones ON rce.registroclinico.regId = rce.solicitud_indicaciones.regId
        LEFT JOIN rce.solicitud_imagenologia ON rce.registroclinico.regId = rce.solicitud_imagenologia.regId
        LEFT JOIN rce.detalle_solicitud_imagenologia_dalca ON rce.solicitud_imagenologia.sol_ima_id = rce.detalle_solicitud_imagenologia_dalca.idSolicitudImagenologia
        LEFT JOIN le.prestaciones_imagenologia ON rce.detalle_solicitud_imagenologia_dalca.idPrestacionImagenologia = le.prestaciones_imagenologia.id_prestaciones
        LEFT JOIN rce.solicitud_laboratorio ON rce.solicitud_laboratorio.regId = rce.registroclinico.regId
        LEFT JOIN rce.solicitud_especialista ON rce.registroclinico.regId = rce.solicitud_especialista.SESPidRCE
        LEFT JOIN rce.solicitud_otros_especialidad ON rce.registroclinico.regId = rce.solicitud_otros_especialidad.idRCE 
        WHERE
        dau.dau_indicacion_egreso = 4 
        AND dau.est_id = 4 
        AND dau.dau_atencion IN ( 1, 2 ) 
        and cam_id not in ( 155, 156) 
        GROUP BY
        dau.dau.dau_id
        ORDER BY dau_categorizacion_actual asc";

        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar si Profesional tiene Turno de Urgencia");
        return $respuesta;
    }
    function verificarPacientesREA ( $objCon ) {
        $sql = "  
        SELECT
        CONCAT( paciente.paciente.nombres, ' ', paciente.paciente.apellidopat ) AS nombre,
        dau.dau.dau_id,
        rce.registroclinico.regHipotesisInicial,
        dau.dau.dau_categorizacion_actual,
        dau.sala.sal_descripcion,
        dau.cama.sal_id,
        dau.dau.est_id,
        dau.cama.cam_descripcion,
        dau.tipo_cama.tipo_cama_sigla,
        dau.dau.dau_inicio_atencion_fecha,
        rce.registroclinico.regId,
        COUNT( DISTINCT CASE WHEN rce.solicitud_indicaciones.sol_ind_servicio = 6 AND rce.solicitud_indicaciones.sol_ind_estado = 1 THEN rce.solicitud_indicaciones.sol_ind_id END ) AS Procedimiento,
        COUNT( DISTINCT CASE WHEN rce.solicitud_imagenologia.sol_ima_estado = 1 THEN rce.solicitud_imagenologia.sol_ima_id END ) AS Imagenologia,
        COUNT( DISTINCT CASE WHEN rce.solicitud_indicaciones.sol_ind_servicio = 2 AND rce.solicitud_indicaciones.sol_ind_estado = 1 THEN rce.solicitud_indicaciones.sol_ind_id END ) AS Tratamiento,
        COUNT( DISTINCT CASE WHEN rce.solicitud_laboratorio.sol_lab_estado = 1 THEN rce.solicitud_laboratorio.sol_lab_id END ) AS Laboratorio,
        COUNT( DISTINCT CASE WHEN rce.solicitud_indicaciones.sol_ind_servicio = 4 AND rce.solicitud_indicaciones.sol_ind_estado = 1 THEN rce.solicitud_indicaciones.sol_ind_id END ) AS Otros,
        COUNT( DISTINCT CASE WHEN rce.solicitud_especialista.SESPestado = 1 THEN rce.solicitud_especialista.SESPid END ) + COUNT( DISTINCT CASE WHEN rce.solicitud_otros_especialidad.estado_sol_otro = 1 THEN rce.solicitud_otros_especialidad.id_sol_otro END ) AS Especialidad 
        FROM
        dau.cama
        LEFT JOIN dau.dau ON dau.cama.dau_id = dau.dau.dau_id
        LEFT JOIN dau.sala ON dau.cama.sal_id = dau.sala.sal_id
        LEFT JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
        LEFT JOIN dau.tipo_cama ON dau.sala.sal_tipo_cama = dau.tipo_cama.tipo_cama_id
        LEFT JOIN rce.registroclinico ON rce.registroclinico.dau_id = dau.dau.dau_id
        LEFT JOIN rce.solicitud_indicaciones ON rce.registroclinico.regId = rce.solicitud_indicaciones.regId
        LEFT JOIN rce.solicitud_imagenologia ON rce.registroclinico.regId = rce.solicitud_imagenologia.regId
        LEFT JOIN rce.detalle_solicitud_imagenologia_dalca ON rce.solicitud_imagenologia.sol_ima_id = rce.detalle_solicitud_imagenologia_dalca.idSolicitudImagenologia
        LEFT JOIN le.prestaciones_imagenologia ON rce.detalle_solicitud_imagenologia_dalca.idPrestacionImagenologia = le.prestaciones_imagenologia.id_prestaciones
        LEFT JOIN rce.solicitud_laboratorio ON rce.solicitud_laboratorio.regId = rce.registroclinico.regId
        LEFT JOIN rce.solicitud_especialista ON rce.registroclinico.regId = rce.solicitud_especialista.SESPidRCE
        LEFT JOIN rce.solicitud_otros_especialidad ON rce.registroclinico.regId = rce.solicitud_otros_especialidad.idRCE 
        WHERE 
        cam_id  in ( 155, 156) 
        GROUP BY
        dau.dau.dau_id
        ORDER BY cam_descripcion asc";

        $respuesta   = $objCon->consultaSQL($sql, "Error al consultar si Profesional tiene Turno de Urgencia");
        return $respuesta;
    }
    function insertarHospitalizacionesDetalle ($objCon, $parametros ) {
        $sql = "INSERT INTO
                    dau.turno_hospitalizaciones_detalle(
                        idTurnoCRUrgencia,
                        sala,
                        cama,
                        nombre_paciente,
                        dau,
                        diagnostico,
                        destino,
                        observaciones,
                        tipo,
                        categorizacion
                    )
                VALUES(
                        '{$parametros['idTurnoCRUrgencia']}',
                        '{$parametros['sala']}',
                        '{$parametros['cama']}',
                        '{$parametros['nombre_paciente']}',
                        '{$parametros['dau']}',
                        '{$parametros['diagnostico']}',
                        '{$parametros['destino']}',
                        '{$parametros['observaciones']}',
                        '{$parametros['tipo']}',
                        '{$parametros['categorizacion']}'
                    ) ";
        $respuesta = $objCon->ejecutarSQL($sql, "Error al Insertar insertarHospitalizacionesDetalle");
        $idTurnoCirugiasRealizadas = $objCon->lastInsertId($sql);
        return $idTurnoCirugiasRealizadas;
    }
}
?>
