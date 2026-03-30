<?php
class Dau{
	function insertardetalle_indicaciones_rce($objCon,$parametros){	
		$sql="INSERT INTO rce.detalle_indicaciones_rce
		(	tipo,
			tipo_descripcion,
			descripcion_detalle,
			usuario,
			fecha,
			hora,
			solicitud_id,
			id_cabecera,
			dau_id
		)VALUES(
			'{$parametros['tipo']}',
			'{$parametros['tipo_descripcion']}',
			'{$parametros['descripcion_detalle']}',
			'{$parametros['usuario']}',
			'{$parametros['fecha']}',
			'{$parametros['hora']}',
			'{$parametros['solicitud_id']}',
			'{$parametros['id_cabecera']}',
			'{$parametros['dau_id']}'
		)";
		$objCon->ejecutarSQL($sql, "Error al insertar Evento");
		return $objCon->lastInsertId();
	}
	function insertarCabeceraIndicacionRec($objCon,$parametros){	
		$sql="INSERT INTO rce.cabecera_indicaciones_rce
		(
			dau_id,
			fecha,
			usuario,
			tipo,
			hora
		)VALUES(
			'{$parametros['dau_id']}',
			'{$parametros['fecha']}',
			'{$parametros['usuario']}',
			'{$parametros['tipo']}',
			'{$parametros['hora']}'
		)";
		$objCon->ejecutarSQL($sql, "Error al insertar Evento");
		return $objCon->lastInsertId();
	}
	function SelectGesReporte($objCon,$parametros){		
		$sql="
		SELECT
			rce.registroclinico.regId, 
			rce.registroclinico.dau_id, 
			rce.registroclinico.regDiagnosticoCie10, 
			dau.dau.dau_paciente_edad,  
			dau.dau.dau_indicacion_egreso_fecha,
			dau.dau.dau_nombre_pac, 
			-- dau.dau.dau_run_pac, 
			cie10.cie10.nombreCIE as cieNombreCompleto
		FROM
			rce.registroclinico
			INNER JOIN
			dau.dau
			ON 
				rce.registroclinico.dau_id = dau.dau.dau_id
			LEFT JOIN
			dau.cie10_ges
			ON 
				rce.registroclinico.regDiagnosticoCie10 = dau.cie10_ges.Id_cie10
			INNER JOIN
			cie10.cie10
			ON 
				rce.registroclinico.regDiagnosticoCie10 = cie10.cie10.codigoCIE
		WHERE
		    (
			    ((
			        dau.cie10_ges.todos = 'S'
			    ) OR
			    (
			        dau.dau.dau_paciente_edad <= 5 AND
			        dau.cie10_ges.under5 = 'S'
			    ) OR
			    (
			        dau.dau.dau_paciente_edad >= 65 AND
			        dau.cie10_ges.over65 = 'S'
			    )) 
		    OR regAuge ='S')
		AND
		    CONCAT(LPAD(MONTH(dau.dau_indicacion_egreso_fecha), 2, '0'), '-', YEAR(dau.dau_indicacion_egreso_fecha)) = '{$parametros['frm_mes']}'";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Usurio Identificado<br>");
		return $datos;
	}    

	function validaPermisoUsuario($objCon,$parametros){		
		$sql="SELECT
			usuario.idusuario,
			usuario.nombreusuario,
			usuario.rutusuario,
			usuario_has_rol.rol_idrol
		FROM
			acceso.usuario
		INNER JOIN acceso.usuario_has_rol ON usuario_has_rol.usuario_idusuario = usuario.idusuario
		WHERE usuario.idusuario = '{$parametros['userName']}' AND usuario_has_rol.rol_idrol = {$parametros['permiso']}";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Usurio Identificado<br>");
		return $datos;
	}

	function obtenerPerfilUsuario($objCon, $parametros){
		//Perfil Tens			: 57
		//Perfil Enfermeras		: 53, 55, 56, 61
		//Perfil Médico 		: 59, 74 
		//Perfil Administrativo : 75
		//Perfil Full           : 60

		$sql	= "	
			SELECT
				SUM(CASE WHEN perfil.id_perfil = 57 THEN 1 ELSE 0 END) AS contadorPerfilTens,
				SUM(CASE WHEN perfil.id_perfil = 53 OR perfil.id_perfil = 55 OR perfil.id_perfil = 56 OR perfil.id_perfil = 61 THEN 1 ELSE 0 END) AS contadorPerfilEnfermero,
				SUM(CASE WHEN perfil.id_perfil = 59 OR perfil.id_perfil = 74 THEN 1 ELSE 0 END) AS contadorPerfilMedico,
				SUM(CASE WHEN perfil.id_perfil = 75 THEN 1 ELSE 0 END) AS contadorPerfilAdministrativo,
				SUM(CASE WHEN perfil.id_perfil = 60 THEN 1 ELSE 0 END) AS contadorPerfilFull,
				usuario.rutusuario,
				usuario.idusuario,
				usuario.nombreusuario
			FROM
				acceso.perfil
			INNER JOIN acceso.usuario_has_perfil ON perfil.id_perfil = usuario_has_perfil.id_perfil
			INNER JOIN acceso.usuario ON usuario_has_perfil.idusuario = usuario.idusuario
			WHERE acceso.usuario_has_perfil.idusuario = '{$parametros['usuario']}' ";
		$datos =  $objCon->consultaSQL($sql,"<br>Error al listar Usurio Identificado<br>");
		return $datos;

	}
	function guardarMovimiento($objCon, $parametros){
		$sql="INSERT INTO dau.dau_movimiento
		(
			dau_id,
			dau_mov_descripcion,
			dau_mov_fecha,
			dau_mov_usuario,
			dau_mov_tipo,
			ip
		)VALUES(
			'{$parametros['dau_id']}',
			'{$parametros['dau_mov_descripcion']}',
			NOW(),
			'{$parametros['dau_mov_usuario']}',
			'{$parametros['dau_mov_tipo']}',
			'{$_SERVER['REMOTE_ADDR']}'
		)";
		$response = $objCon->ejecutarSQL($sql, "Error al Insertar Movimiento DAU");
		return $response;
	}
	
	function vaciarCamaCierre($objCon, $parametros){
		$objCon->setDB("dau");
		$sql=" UPDATE dau.cama ";
		$condicion = "";
		if ($parametros['frm_est_id']==3 || $parametros['frm_est_id']==4 || $parametros['frm_est_id']==8 || $parametros['frm_est_id']==7) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_id = NULL, est_id = 10, cam_fecha_desocupada = NOW()";
		}
		$sql .= $condicion." WHERE dau_id = {$parametros['Iddau']}";
		$response = $objCon->ejecutarSQL($sql, "ERROR AL VACIAR CAMA CIERRE");
		return $response;
	}
	function cierreAdministrativoDAU($objCon, $parametros){
		$sql=" UPDATE dau.dau ";

		$condicion = "";

		if ($parametros['frm_condicion_ingreso']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_condicion_ingreso_id = '{$parametros['frm_condicion_ingreso']}'";
		}else{
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_condicion_ingreso_id = NULL";
		}

		if ($parametros['frm_pronostico']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_pronostico_id = '{$parametros['frm_pronostico']}'";
		}else{
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_pronostico_id = NULL";
		}

		if ($parametros['frm_peso']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_peso = '{$parametros['frm_peso']}'";
		}else{
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_peso = NULL";
		}

		if ($parametros['frm_estatura']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_estatura = '{$parametros['frm_estatura']}'";
		}else{
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_estatura = NULL";
		}

		if ($parametros['frm_tratamiento']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_tratamiento_id = '{$parametros['frm_tratamiento']}'";
		}else{
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_tratamiento_id = NULL";
		}

		if ($parametros['frm_atendido_por']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_atendidopor_id = '{$parametros['frm_atendido_por']}'";
		}else{
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_atendidopor_id = NULL";
		}

		if ($parametros['frm_etilico']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_alcoholemia_estado_etilico = '{$parametros['frm_etilico']}'";
		}else{
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_alcoholemia_estado_etilico = NULL";
		}

		if ($parametros['Profesional']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_profesional_id = '{$parametros['Profesional']}'";
		}else{
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_profesional_id = NULL";
		}

		if ($parametros['frm_turno']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_turno_id = '{$parametros['frm_turno']}'";
		}else{
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_turno_id = NULL";
		}

		if ($parametros['frm_hora_atencion']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_hora_atencion = '{$parametros['frm_hora_atencion']}'";
		}else{
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_hora_atencion = NULL";
		}

		if ($parametros['frm_auge']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_auge = '{$parametros['frm_auge']}'";
		}else{
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_auge = NULL";
		}

		if ($parametros['frm_postinor']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_entrega_postinor = '{$parametros['frm_postinor']}'";
		}else{
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_entrega_postinor = NULL";
		}

		if ($parametros['frm_hepatitisB']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_hepatitisB = '{$parametros['frm_hepatitisB']}'";
		}else{
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_hepatitisB = NULL";
		}

		if ($parametros['frm_pertinencia']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_pertinencia = '{$parametros['frm_pertinencia']}'";
		}else{
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_pertinencia = NULL";
		}

		if ($parametros['resultado']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_alcoholemia_resultado = '{$parametros['resultado']}'";
		}

		if ($parametros['frm_nro']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_alcoholemia_numero_frasco = '{$parametros['frm_nro']}'";
		}else{
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_alcoholemia_numero_frasco = NULL";
		}

		if ($parametros['horaAcoholemia']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_alcoholemia_fecha = '{$parametros['horaAcoholemia']}'";
		}else{
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_alcoholemia_fecha = NULL";
		}

		if ($parametros['frm_profesional_alcoholemia']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_alcoholemia_medico = '{$parametros['frm_profesional_alcoholemia']}'";
		}else{
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_alcoholemia_medico = NULL";
		}

		if ($parametros['frm_observacion_alcoholemia']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_alcoholemia_apreciacion = '{$parametros['frm_observacion_alcoholemia']}'";
		}else{
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_alcoholemia_apreciacion = NULL";
		}

		if ($parametros['radio_egreso'] && $parametros['radio_egreso'] != "5") {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" est_id = '{$parametros['radio_egreso']}'";

			if ($parametros['dau_cierre_administrativo']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_administrativo = '{$parametros['dau_cierre_administrativo']}'";
			}

			if ($parametros['dau_cierre_administrativo']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_administrativo = '{$parametros['dau_cierre_administrativo']}'";
			}

			if ($parametros['reg_usuario_insercion']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_administrativo_usuario = '{$parametros['reg_usuario_insercion']}'";
			}

			if ($parametros['frm_fecha_egreso_adm']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_administrativo_fecha = NOW()";
			}

			if ($parametros['fecha_cierre_final']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_fecha_final = NOW()";
			}

		}
		else {
			if ($parametros['frm_est_id']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" est_id = '{$parametros['frm_est_id']}'";
			}else{
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" est_id = '{$parametros['frm_estado_cierre']}'";
			}


		}

		if ($parametros['frm_indicacion_egreso']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_indicacion_egreso = '{$parametros['frm_indicacion_egreso']}'";
		}
		else if ($parametros['frm_indicacion_egreso_h']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_indicacion_egreso = '{$parametros['frm_indicacion_egreso_h']}'";
		}

		if ($parametros['frm_servicio']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_servicio = '{$parametros['frm_servicio']}'";
		}
		else if ($parametros['frm_servicio_h']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_servicio = '{$parametros['frm_servicio_h']}'";
		}

		if ($parametros['frm_motivo_egreso']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_administrativo_observacion = '{$parametros['frm_motivo_egreso']}'";
		}

		if ($parametros['frm_fallecimiento_fecha']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_defuncion_fecha = '{$parametros['frm_fallecimiento_fecha']}'";
		}
		else if ($parametros['frm_fallecimiento_fecha_h']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_defuncion_fecha = '{$parametros['frm_fallecimiento_fecha_h']}'";
		}

		if ($parametros['reg_usuario_defuncion']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_defuncion_usuario = '{$parametros['reg_usuario_defuncion']}'";
		}

		if ($parametros['frm_destionos_h']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_atl_der_id = '{$parametros['frm_destionos_h']}'";
		}
		if ($parametros['especialidad']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_ind_especialidad = '{$parametros['especialidad']}'";
		}
		if ($parametros['aps']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_ind_aps = '{$parametros['aps']}'";
		}
		if ($parametros['frm_otros']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_ind_otros = '{$parametros['frm_otros']}'";
		}
		if ($parametros['frm_sum_indicacion']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_des_id = '{$parametros['frm_sum_indicacion']}'";
		}
		$sql .= $condicion." WHERE dau_id = {$parametros['Iddau']}";
		$response = $objCon->ejecutarSQL($sql, "ERROR AL CERRAR DAU");
		return $response;
	}
	function existeSolicitudAltaUrgencia($objCon, $idDau){
        $sql = "    SELECT
                            dau_tiene_indicacion.ind_egr_id AS tipoSolicitud
                        FROM
                            dau.dau_tiene_indicacion
                        WHERE
                            dau_tiene_indicacion.dau_id = '{$idDau}' ";

        $resultado = $objCon->consultaSQL($sql,"Error al buscar Eventos del paciente");
		return $resultado;
    }
	function crearEvento($objCon,$parametros){

		$sql = "INSERT INTO rce.evento
				(pacId,
				oriId,
				eveFecha,
				eveEstado,
				eveUsuarioInserta,
				intCodigo,
				dau_id)
				VALUES(
				'{$parametros["paciente_id"]}',
				'{$parametros["origen"]}',
				NOW(),
				'{$parametros["estadoEve"]}',
				'{$parametros["usuarioEve"]}',
				'{$parametros["intCodigo"]}',
				'{$parametros["dau_id"]}')";
				
		$objCon->ejecutarSQL($sql, "Error al insertar Evento");
		return $objCon->lastInsertId();
		
	}
	function consultaEvento($objCon,$parametros){
		$sql = "SELECT
				evento.eveId,
				evento.dau_id
				FROM
				rce.evento
				WHERE
				evento.dau_id = '{$parametros['dau_id']}'";
		$resultado = $objCon->consultaSQL($sql,"Error al buscar Eventos del paciente");
		return $resultado;
	}
	function SelectDau($objCon, $parametros){
		$sql="SELECT * 
		FROM dau.dau 
		WHERE dau.dau.dau_id = {$parametros['dau_id']}";

		$resultado = $objCon->consultaSQL($sql,"Error al buscar Eventos del paciente");
		return $resultado;
	}
	function ListarSolicitudIndicaciones($objCon, $parametros){
			 $sql="SELECT
				banco_sangre.solicitudes_transfusion.*,
            	estados.nombre AS estado_transfusion, 
				producto.producto_descripcion, 
				solicitudes_productos.cantidad, 
				solicitudes_productos.unidad_medida, 
				caracter_transfusion.descripcion, 
				caracter_transfusion.nombre AS caracter_transfusion
			FROM
				banco_sangre.solicitudes_transfusion
			INNER JOIN banco_sangre.caracter_transfusion ON solicitudes_transfusion.caracter_transfusion_id = caracter_transfusion.id
			INNER JOIN banco_sangre.estados ON solicitudes_transfusion.estado = estados.id
			INNER JOIN banco_sangre.grupo_sanguineo ON solicitudes_transfusion.grupo_sangre = grupo_sanguineo.id
			INNER JOIN banco_sangre.solicitudes_productos ON solicitudes_transfusion.id = solicitudes_productos.solicitud_id
			INNER JOIN banco_sangre.producto ON solicitudes_productos.producto = producto.id_producto
			WHERE
				solicitudes_transfusion.id = '{$parametros['id_solicitudTransfusion']}'";

			// echo $sql;
		$resultado=$objCon->consultaSQL($sql,"<br>ERROR Listar ListarSolicitudIndicaciones <br>");
		return $resultado;
	}
	function UpdateSolicitud_transfusion($objCon,$parametros){
		$estado = (int)($parametros['estado'] ?? 0);
		$sql=" UPDATE banco_sangre.solicitudes_transfusion
				SET  solicitudes_transfusion.toma_muestra_usuario = '{$parametros['toma_muestra_usuario']}',
				solicitudes_transfusion.estado = IF(solicitudes_transfusion.estado = 7, 7, {$estado}),
				solicitudes_transfusion.toma_muestra_fecha = '{$parametros['toma_muestra_fecha']}',
				solicitudes_transfusion.toma_muestra_hora = '{$parametros['toma_muestra_hora']}',
				solicitudes_transfusion.toma_muestra_observacion ='{$parametros['toma_muestra_observacion']}'
				WHERE solicitudes_transfusion.id = '{$parametros['id_solicitud_transfusion']}'";
				$response = $objCon->ejecutarSQL($sql, "Error en UpdateSolicitud_transfusion");
		return $response;
	}

	function InsertSolicitudes_transfusion_movimiento($objCon,$parametros){

		$sql="
	     INSERT INTO banco_sangre.solicitudes_transfusion_movimientos (
            solicitud_id,
            usuario_id,
            fecha,
            hora,
            descripcion,
            observacion,
            datos_json
        ) VALUES (
            '{$parametros['id_solicitud_transfusion']}',
            '{$parametros['toma_muestra_usuario']}',
            CURDATE(),
            CURTIME(),
            '{$parametros['descripcion_log']}',
            '{$parametros['toma_muestra_observacion']}',
           '{$parametros}'
        )";

        $response = $objCon->ejecutarSQL($sql, "Error en UpdateSolicitud_transfusion");
		return $response;
	}

	function ListarPacientesDau($objCon, $parametros){
			 $sql="SELECT
					dau.dau_id,
					dau.est_id,
					dau.id_paciente,
					dau.idctacte,
					dau.dau_admision_fecha,
					dau.dau_admision_usuario,
					dau.dau_categorizacion,
					dau.dau_categorizacion_fecha,
					dau.dau_categorizacion_usuario,
					dau.dau_categorizacion_actual,
					dau.dau_categorizacion_actual_fecha,
					dau.dau_categorizacion_actual_usuario,
					dau.dau_ingreso_sala_fecha,
					dau.dau_ingreso_sala_usuario,
					dau.dau_inicio_atencion_fecha,
					dau.dau_inicio_atencion_usuario,
					dau.dau_indicacion_egreso,
					dau.dau_indicacion_egreso_fecha,
					dau.dau_indicacion_egreso_usuario,
					dau.dau_indicacion_egreso_aplica_fecha,
					dau.dau_indicacion_egreso_aplica_usuario,
					dau.dau_apreciacion_diagnostica,
					dau.dau_terapia_inicial,
					dau.dau_paciente_aps,
					dau.dau_paciente_domicilio,
					dau.dau_paciente_domicilio_tipo,
					dau.dau_paciente_edad,
					dau.dau_paciente_prevision,
					dau.dau_paciente_forma_pago,
					dau.dau_atencion,
					dau.dau_motivo_consulta,
					dau.dau_motivo_descripcion,
					dau.dau_forma_llegada,
					dau.dau_mordedura,
					dau.dau_intoxicacion,
					dau.dau_quemadura,
					dau.dau_imputado,
					dau.dau_reanimacion,
					dau.dau_tipo_accidente,
					dau.dau_accidente_escolar_institucion,
					dau.dau_accidente_escolar_numero,
					dau.dau_accidente_escolar_nombre,
					dau.dau_accidente_trabajo_mutualidad,
					dau.dau_accidente_transito_tipo,
					dau.dau_accidente_hogar_lugar,
					dau.dau_accidente_otro_lugar,
					dau.dau_agresion_vif,
					dau.dau_alcoholemia_fecha,
					dau.dau_alcoholemia_apreciacion,
					dau.dau_alcoholemia_numero_frasco,
					dau.dau_alcoholemia_resultado,
					dau.dau_alcoholemia_estado_etilico,
					dau.dau_alcoholemia_medico,
					dau.dau_defuncion_fecha,
					dau.dau_defuncion_usuario,
					dau.dau_pyxis,
					dau.dau_cierre_administrativo,
					dau.dau_cierre_condicion_ingreso_id,
					dau.dau_cierre_pronostico_id,
					dau.dau_cierre_peso,
					dau.dau_cierre_estatura,
					dau.dau_cierre_tratamiento_id,
					dau.dau_cierre_atendidopor_id,
					dau.dau_cierre_profesional_id,
					dau.dau_cierre_turno_id,
					dau.dau_cierre_hora_atencion,
					dau.dau_cierre_auge,
					dau.dau_cierre_entrega_postinor,
					dau.dau_cierre_hepatitisB,
					dau.dau_cierre_pertinencia,
					dau.dau_cierre_servicio,
					dau.dau_cierre_cie10,
					dau.dau_cierre_administrativo_observacion,
					dau.dau_cierre_administrativo_usuario,
					dau.dau_cierre_administrativo_fecha,
					dau.dau_cierre_fecha_final,
					dau.dau_hipotesis_diagnostica_inicial,
					dau.dau_hipotesis_diagnostica_fecha,
					dau.dau_hipotesis_diagnostica_usuario,
					dau.dau_paciente_complejo,
					dau.dau_indicaciones_completas,
					dau.dau_indicaciones_solicitadas,
					dau.dau_indicaciones_realizadas,
					dau.dau.dau_hospitalizacion_otros_servicios,
					categorizacion.cat_nivel,
					categorizacion.cat_tiempo_maximo,
					categorizacion.cat_tipo,
					categorizacion.cat_nombre_mostrar,
					atencion.ate_descripcion,
					motivo_consulta.mot_descripcion,
					usuarioAdmision.nombreusuario AS nombreUsuario,
					usuarioCategorizacion.nombreusuario as usuarioCategoriza,
					CONCAT(dau.sala.sal_resumen,'_',dau.cama.cam_descripcion) AS salaCama,
					dau.cama.cam_id,
					dau.dau.dau_cierre_hepatitisB,
					dau.dau.dau_atencion,
					CASE dau.dau.dau_atencion
					WHEN '1' THEN 'Adulto'
					WHEN '2' THEN 'Pediátrico'
					WHEN '3' THEN 'Ginecológico'
					END AS dau_atencion2,
					etilico.eti_descripcion
					FROM
					dau.dau
					LEFT JOIN dau.motivo_consulta ON dau.dau_motivo_consulta = motivo_consulta.mot_id
					LEFT JOIN dau.sub_motivo_consulta ON dau.dau_tipo_accidente = sub_motivo_consulta.sub_mot_id
					LEFT JOIN dau.atencion ON dau.dau_atencion = atencion.ate_id
					LEFT JOIN dau.categorizacion ON dau.dau_categorizacion_actual = categorizacion.cat_id
					LEFT JOIN acceso.usuario AS usuarioAdmision ON dau.dau.dau_admision_usuario = usuarioAdmision.idusuario
					LEFT JOIN acceso.usuario AS usuarioCategorizacion ON dau.dau.dau_categorizacion_usuario = usuarioCategorizacion.idusuario
					LEFT JOIN dau.etilico ON dau.dau.dau_alcoholemia_estado_etilico = dau.etilico.eti_id
					LEFT JOIN dau.cama ON dau.cama.dau_id = dau.dau.dau_id
					LEFT JOIN dau.sala ON dau.cama.sal_id = dau.sala.sal_id
					WHERE dau.dau_id = '{$parametros['dau_id']}'";
		$resultado=$objCon->consultaSQL($sql,"<br>ERROR ListarPacientesDau dau<br>");
		return $resultado;
	}



	function buscarListaPaciente($objCon, $parametros){
			$sql="SELECT
					dau.dau.dau_id,
					dau.dau.id_paciente,
					paciente.paciente.rut,
					paciente.paciente.nombres,
					paciente.paciente.apellidopat,
					paciente.paciente.apellidomat,
					paciente.paciente.fono1,
					paciente.paciente.fono2,
					paciente.paciente.fono3,
					paciente.paciente.sexo,
					paciente.paciente.region,
					paciente.paciente.idcomuna,
					paciente.paciente.prevision AS id_prevision, 
					paciente.paciente.fechanac,
					CONCAT(paciente.paciente.calle,' Nº',paciente.paciente.numero,' ',paciente.paciente.restodedireccion) as Direccion,
					paciente.prevision.prevision,
					paciente.paciente.nroficha,
					paciente.comuna.comuna,
                    religion.rlg_descripcion AS religion_descripcion
					FROM
					dau.dau
					INNER JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					INNER JOIN paciente.prevision ON paciente.paciente.prevision = paciente.prevision.id
					LEFT JOIN paciente.comuna ON paciente.paciente.idcomuna = paciente.comuna.id
					LEFT JOIN paciente.region ON paciente.region.REG_Id = paciente.paciente.region
                    LEFT JOIN paciente.religion on paciente.religion.rlg_id = paciente.paciente.religion
					WHERE dau.dau_id = '{$parametros['dau_id']}'";
		$resultado=$objCon->consultaSQL($sql,"<br>ERROR Listar pacientes dau<br>");
		return $resultado;
	}
	function pacienteTieneIndicacionAlta($objCon, $idDAU) {
	    $existeSolicitudAltaUrgencia = $this->existeSolicitudAltaUrgencia($objCon, $idDAU);

	    // Verificar si el resultado es un arreglo y si el índice 0 existe
	    if (is_array($existeSolicitudAltaUrgencia) && isset($existeSolicitudAltaUrgencia[0]['tipoSolicitud'])) {
	        if (!empty($existeSolicitudAltaUrgencia[0]['tipoSolicitud'])) {
	            return true;
	        }
	    }

	    return false;
	}
	// function pacienteTieneIndicacionAlta ($objCon, $idDAU ) {

	// 	$existeSolicitudAltaUrgencia = $this->existeSolicitudAltaUrgencia($objCon, $idDAU);

	// 	if ( ! is_null($existeSolicitudAltaUrgencia[0]['tipoSolicitud']) && ! empty($existeSolicitudAltaUrgencia[0]['tipoSolicitud']) ) {

	// 		return true;
	// 	}

	// 	return false;

	// }

	function buscarCamaYsala($objCon, $parametros){
			$sql="SELECT
				dau.dau_id,
				cama.cam_descripcion,
				sala.sal_descripcion
				FROM dau.dau
				LEFT JOIN  dau.cama ON cama.dau_id= dau.dau_id
				LEFT JOIN  dau.sala ON cama.sal_id= sala.sal_id
				WHERE dau.dau_id = '{$parametros['dau_id']}'";
		$resultado=$objCon->consultaSQL($sql,"<br>ERROR buscarCamaYsala pacientes dau<br>");
		return $resultado;
	}



	function listarIndicaciones($objCon, $parametros){
			    $sql="SELECT *
				from dau.dau
				LEFT JOIN dau.dau_tiene_indicacion on dau_tiene_indicacion.dau_id = dau.dau_id
				LEFT JOIN dau.indicacion on indicacion.ind_id = dau_tiene_indicacion.ind_id
				WHERE dau.dau_id = '{$parametros['dau_id']}'
				";
				$resultado=$objCon->consultaSQL($sql,"<br>ERROR en listar indicaciones<br>");
				return $resultado;
	}



	function listarEtilico($objCon){
		$sql ="SELECT *
				FROM dau.etilico";
			$resultado=$objCon->consultaSQL($sql,"<br>ERROR en listar estados etilicos<br>");
			return $resultado;

	}



	function listarMedicosUrgencia($objCon){
		$sql ="SELECT
			profesional.PROcodigo,
			profesional.PROdescripcion,
			profesional.PROat_urgencia
			FROM
			parametros_clinicos.profesional
			where PROat_urgencia='S'
			ORDER BY profesional.PROdescripcion";
			$resultado=$objCon->consultaSQL($sql,"<br>ERROR al listar medicos de urgencias<br>");
			return $resultado;

	}



	function listarMedicosUrgenciaAcceso($objCon){
		// $objCon->db_select("acceso");
		$sql="SELECT
		medico.rut,
		medico.nombremedico,
		medico.tipoUrg
		FROM
		acceso.medico
		where tipoUrg!=''";
		$resultado=$objCon->consultaSQL($sql,"<br>ERROR en listar medicos de urgencias en bd acceso<br>");
		return $resultado;
	}



	function listaInstituciones($objCon,$parametros){
		$sql="SELECT
		dau.institucion.tip_id,
		dau.institucion.ins_id,
		dau.institucion.ins_descripcion
		FROM
		dau.institucion
		WHERE institucion.tip_id = '{$parametros['tip_id']}'";

		$resultado=$objCon->consultaSQL($sql,"<br>ERROR AL LISTAR INSTITUCION<br>");
		return $resultado;
	}



	function anularFechas($objCon,$parametros){

		$sql=" UPDATE dau.dau
				SET  dau.est_id = {$parametros['estado_dau']},
				dau.dau_indicacion_egreso_fecha = '{$parametros['frm_fecha_modificar']}',
				dau.dau_indicacion_egreso = '{$parametros['frm_Indicacion_Egreso']}',
				dau.dau_indicacion_egreso_usuario ='{$parametros['indEgreso']}',
				dau.dau_defuncion_fecha = NULL,
				dau.dau_defuncion_usuario = NULL
				WHERE dau.dau_id = '{$parametros['dau_id']}'";
				$response = $objCon->ejecutarSQL($sql, "Error en anular la fecha de defuncion");
		return $response;
	}



	function actualizarAlcoholemia ($objCon,$parametros){
		 $sql=" UPDATE dau.dau";
		 $condicion = "";

		if ($parametros['frm_alc_fecha']){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_alcoholemia_fecha = '{$parametros['frm_alc_fecha']}'";

		}
		if ($parametros['frm_apreciacion']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_alcoholemia_apreciacion = '{$parametros['frm_apreciacion']}'";

		}
		if ($parametros['frm_numero_frasco']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_alcoholemia_numero_frasco = {$parametros['frm_numero_frasco']}";

		}
		if ($parametros['frm_resultado']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_alcoholemia_resultado = '{$parametros['frm_resultado']}'";

		}
		if ($parametros['frm_estado_etilico']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_alcoholemia_estado_etilico = '{$parametros['frm_estado_etilico']}'";

		}
		if ($parametros['frm_alcoholemia_medico']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_alcoholemia_medico = '{$parametros['frm_alcoholemia_medico']}'";

		}
		$sql .= $condicion." WHERE dau.dau_id = {$parametros['dau_id']}";
		$response = $objCon->ejecutarSQL($sql, "Error al actualizar los datos de alcoholemia");
		return $response;

	}



	function ListarIndicacionEgreso($objCon, $filtroIndicacion){
		$sql="SELECT *
			from dau.indicacion_egreso
			WHERE {$filtroIndicacion} = 'S'";
		$resultado=$objCon->consultaSQL($sql,"<br>ERROR Listar indicacion egreso dau<br>");
		return $resultado;
	}



	function obtenerIndicacionEgreso($objCon,$parametros){
			 $sql="SELECT *
					FROM
					dau.indicacion_egreso
					LEFT JOIN dau.dau ON dau.dau.dau_indicacion_egreso = dau.indicacion_egreso.ind_egr_id
					LEFT JOIN dau.dau_tiene_indicacion ON dau.dau_tiene_indicacion.dau_id = dau.dau.dau_id
					LEFT JOIN camas.sscc ON dau.dau_tiene_indicacion.dau_ind_servicio = camas.sscc.id
					WHERE
					dau.dau.dau_id = {$parametros['dau_id']}";
			 $resultado=$objCon->consultaSQL($sql,"<br>ERROR obtener indicacion egreso dau<br>");
		return $resultado;
	}



	function ActualizarIndicacionEgresoDau($objCon, $parametros){

		$condicion = '';

		if ($parametros['frm_auge'] == '') {
			$parametros['frm_auge'] = 'N';
		}

		if ($parametros['frm_pertinencia'] == '') {
			$parametros['frm_pertinencia'] = 'N';
		}

		if ($parametros['frm_postinor'] == '') {
			$parametros['frm_postinor'] = 'N';
		}

		if ($parametros['frm_hepatitisB'] == '') {
			$parametros['frm_hepatitisB'] = 'N';
		}

		 $sql=" UPDATE dau.dau";
		if($parametros['estado_dau']){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" est_id = {$parametros['estado_dau']}";
		}

		if ($parametros['frm_fecha_modificar']){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_indicacion_egreso_fecha = NOW()";
		}
		if ($parametros['frm_Indicacion_Egreso']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_indicacion_egreso = '{$parametros['frm_Indicacion_Egreso']}'";
		}
		if ($parametros['indEgreso']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_indicacion_egreso_usuario = '{$parametros['indEgreso']}'";
		}
		if ($parametros['frm_codigoCIE10']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_cie10 = '{$parametros['frm_codigoCIE10']}'";
		}
		if ($parametros['frm_textArea_FundamentoDiagnostico']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_fundamento_diag = '{$parametros['frm_textArea_FundamentoDiagnostico']}'";
		}
		if ($parametros['frm_auge']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_auge = '{$parametros['frm_auge']}'";
		}
		if ($parametros['frm_pertinencia']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_pertinencia = '{$parametros['frm_pertinencia']}'";
		}
		if ($parametros['frm_postinor']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_entrega_postinor = '{$parametros['frm_postinor']}'";
		}
		if ($parametros['frm_hepatitisB']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_hepatitisB = '{$parametros['frm_hepatitisB']}'";
		}
		if ($parametros['frm_cie10Abierto']) {
            $condicion .= ($condicion == "") ? " SET " : " , ";
            $condicion.=" dau_cie10_abierto = '{$parametros['frm_cie10Abierto']}'";
        }
		if ($parametros['dauAbiertoMantenedor']) {
            $condicion .= ($condicion == "") ? " SET " : " , ";
            $condicion.=" dau_abierto_mantenedor = '{$parametros['dauAbiertoMantenedor']}' ";
        }

		if ( ! empty($parametros['frm_otro_servicio_destino']) && ! is_null($parametros['frm_otro_servicio_destino']) ) {

			if ( $parametros['frm_otro_servicio_destino'] != 'Ninguno' ) {

				$condicion .= ($condicion == "") ? " SET " : " , ";

				$condicion.=" dau_hospitalizacion_otros_servicios = '{$parametros['frm_otro_servicio_destino']}' ";

			} else {

				$condicion .= ($condicion == "") ? " SET " : " , ";

				$condicion.=" dau_hospitalizacion_otros_servicios = NULL ";

			}
		}
		if ($parametros['frm_aQuienSeEntregaInformacion']) {
            $condicion .= ($condicion == "") ? " SET " : " , ";
            $condicion.=" dau_entrega_informacion = '{$parametros['frm_aQuienSeEntregaInformacion']}' ";
        }
        if ($parametros['frm_entregaInformacion']) {
            $condicion .= ($condicion == "") ? " SET " : " , ";
            $condicion.=" dau_se_entrega_informacion = '{$parametros['frm_entregaInformacion']}' ";
        }

        if ($parametros['frm_ObservacionEntregaInformacion']) {
            $condicion .= ($condicion == "") ? " SET " : " , ";
            $condicion.=" dau_observacionEntregaInformacion = '{$parametros['frm_ObservacionEntregaInformacion']}' ";
        }
		$condicion .= " ,
						dau.dau_defuncion_fecha = NULL,
						dau.dau_defuncion_usuario = NULL ";
		$sql .= $condicion." WHERE dau.dau_id = {$parametros['dau_id']}";
		$response = $objCon->ejecutarSQL($sql, "Error al actualizar la indicacion de egreso");
		return $response;
	}


	function ActualizarDauEvo($objCon, $parametros){
		$sql=" 	UPDATE 	dau.dau
				SET
						dau.dau.dau_usuario_ultima_evo = '{$parametros['dau_usuario_ultima_evo']}'
				WHERE 	dau.dau_id = '{$parametros['dau_id']}'";
		$response = $objCon->ejecutarSQL($sql, "Error al actualizar la inicio de atencion");
		return $response;
	}
	function ActualizarInicioAtencion($objCon, $parametros){

		if ( ! empty($parametros['tipoInicioAtencion']) && ! is_null($parametros['tipoInicioAtencion']) ) {

			$sql=" UPDATE dau.dau
				SET
					dau.dau.dau_viaje_epidemiologico = '{$parametros['dau_viaje_epidemiologico']}',
					dau.dau.dau_pais_epidemiologia = '{$parametros['dau_pais_epidemiologia']}',
					dau.dau.dau_observacion_epidemiologica = '{$parametros['dau_observacion_epidemiologica']}'
				WHERE dau.dau_id = '{$parametros['dau_id']}'";


		} else {

		  $sql=" UPDATE dau.dau
				SET dau.dau_inicio_atencion_fecha = NOW(),
				    dau.est_id = '3',
				    dau.dau_inicio_atencion_usuario ='{$parametros['atencion']}',
					dau.dau.dau_viaje_epidemiologico = '{$parametros['dau_viaje_epidemiologico']}',
					dau.dau.dau_pais_epidemiologia = '{$parametros['dau_pais_epidemiologia']}',
					dau.dau.dau_observacion_epidemiologica = '{$parametros['dau_observacion_epidemiologica']}'
				WHERE dau.dau_id = '{$parametros['dau_id']}'";

		}

		$response = $objCon->ejecutarSQL($sql, "Error al actualizar la inicio de atencion");
		return $response;
	}



	function registrarIndicacionEgreso($objCon,$parametros){
		$condicion  = "";
		$condicion2 = "";
		$condicion3 = "";
		$condicion4 = "";
	 if($parametros['especialidad']==''){
	 		$condicion.= "dau_ind_especialidad 	= NULL,";
	 	}else{
	 		$condicion.= "dau_ind_especialidad 	= '{$parametros['especialidad']}',";
	 	}
	 	if($parametros['aps']==''){
	 		$condicion2.= "dau_ind_aps 	= NULL,";
	 	}else{
	 		$condicion2.= "dau_ind_aps 	= '{$parametros['aps']}',";
	 	}
	 	if($parametros['frm_otros']==''){
	 		$condicion3.= "dau_ind_otros = NULL,";
	 	}else{
	 		$condicion3.= "dau_ind_otros = '{$parametros['frm_otros']}',";
	 	}
	 	if($parametros['frm_alta_derivacion']==''){
	 		$condicion4.= "alt_der_id = NULL";
	 	}else{
	 		$condicion4.= "alt_der_id = '{$parametros['frm_alta_derivacion']}'";
	 	}
	 $sql="INSERT INTO dau.dau_tiene_indicacion(
		 	ind_id,
			dau_id,
			est_id,
		 	ind_egr_id,
			dau_ind_servicio,
			dau_ind_fecha_indicada,
			dau_ind_usuario_indica,
			des_id,
			alt_der_id,
			dau_ind_especialidad,
			dau_ind_aps,
			dau_ind_otros
			)
			VALUES(
				1,
				{$parametros['dau_id']},
				{$parametros['estado_ind']},
				{$parametros['frm_Indicacion_Egreso']},
				'{$parametros['frm_servicio_destino']}',
				NOW(),
				'{$parametros['indEgreso']}',
				'{$parametros['frm_sum_indicacion']}',
				'{$parametros['frm_alta_derivacion']}',
				'{$parametros['especialidad']}',
				'{$parametros['aps']}',
				'{$parametros['frm_otros']}')
				ON DUPLICATE KEY
				UPDATE est_id 			= '{$parametros['estado_ind']}',
				ind_egr_id 				= '{$parametros['frm_Indicacion_Egreso']}',
				dau_ind_servicio 		= '{$parametros['frm_servicio_destino']}',
				dau_ind_fecha_indicada 	=  NOW(),
				dau_ind_usuario_indica 	= '{$parametros['indEgreso']}',
				des_id 					= '{$parametros['frm_sum_indicacion']}',".$condicion.$condicion2.$condicion3.$condicion4;
		$response = $objCon->ejecutarSQL($sql, "ERROR AL INDICAR EGRESO");
	}



	function ActualizarIndicacionAplica($objCon, $parametros){
		$sql=" UPDATE dau.dau_tiene_indicacion";
		$condicion = "";

		if($parametros['estado_ind']){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" est_id = {$parametros['estado_ind']}";
		}
		if($parametros['frm_fecha_modificar']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_ind_fecha_aplicada = '{$parametros['frm_fecha_modificar']}'";
		}
		if($parametros['indAplica']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.="dau_ind_usuario_aplica = '{$parametros['dau_mov_usuario']}'";
		}
		$sql .= $condicion." WHERE dau_id = {$parametros['dau_id']}";
		// $response = $objCon->ejecutarSQL($sql, "Error al actualizar la indicacion aplicada");
		// return $response;
	}



	function actualizarDau($objCon,$parametros){
		$sql=" UPDATE dau.dau";
		$condicion = "";

		if($parametros['destino_dau']){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_des_id = '{$parametros['destino_dau']}'";
		}
		if($parametros['derivacion_dau']){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_atl_der_id = '{$parametros['derivacion_dau']}'";
		}
		if($parametros['derivacion_especialista']){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_ind_especialidad = '{$parametros['derivacion_especialista']}'";
		}
		if($parametros['derivacion_aps']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_cierre_ind_aps = '{$parametros['derivacion_aps']}'";
		}
		if($parametros['derivacion_otros']) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.="dau_cierre_ind_otros = '{$parametros['derivacion_otros']}'";
		}
		$sql .= $condicion." WHERE dau_id = {$parametros['dau_id']}";
		$response = $objCon->ejecutarSQL($sql, "Error al actualizar la indicacion aplicada");
		return $response;
	}



	function ActualizarIndicacionAplica2($objCon, $parametros){
		$sql="	INSERT INTO dau.dau_tiene_indicacion(
					 	ind_id,
						dau_id,
						est_id,
					 	ind_egr_id,
						dau_ind_servicio,
						dau_ind_fecha_indicada,
						dau_ind_usuario_indica)
					VALUES
						(1,
						{$parametros['dau_id']},
						{$parametros['estado_ind']},
						{$parametros['frm_indicar2']},
						'{$parametros['frm_serv2']}',
						NOW(),
						'{$parametros['dau_mov_usuario']}')
					ON DUPLICATE KEY
					UPDATE est_id = '{$parametros['estado_ind']}',
						ind_egr_id = '{$parametros['frm_indicar2']}',
						dau_ind_servicio = '{$parametros['frm_serv2']}',
						dau_ind_fecha_indicada = NOW(),
						dau_ind_fecha_aplicada = NOW(),
						dau_ind_usuario_indica = '{$parametros['dau_mov_usuario']}',
						dau_ind_usuario_aplica = '{$parametros['dau_mov_usuario']}'";
		$response = $objCon->ejecutarSQL($sql, "Error al actualizar la indicacion aplicada");
		return $response;
	}



	function ActualizarIndicacionAplicaDau($objCon, $parametros){
		// $condicion = '';

		// $sql=" UPDATE dau";

		// $condicion.=" SET est_id = 5";

		// if($parametros['frm_fecha_modificar']) {
		// 	$condicion .= ($condicion == "") ? " SET " : " , ";
		// 	$condicion.=" dau_indicacion_egreso_aplica_fecha = '{$parametros['frm_fecha_modificar']}'";
		// }
		// if($parametros['indAplica']) {
		// 	$condicion .= ($condicion == "") ? " SET " : " , ";
		// 	$condicion.=" dau_indicacion_egreso_aplica_usuario = '{$parametros['indAplica']}'";
		// }

		// if($parametros['frm_fecha_modificar']) {
		// 	$condicion .= ($condicion == "") ? " SET " : " , ";
		// 	$condicion.=" dau_cierre_fecha_final = '{$parametros['frm_fecha_modificar']}'";
		// }

		// if($parametros['servicio_destino']) {
		// 	$condicion .= ($condicion == "") ? " SET " : " , ";
		// 	$condicion.=" dau_cierre_servicio = {$parametros['servicio_destino']}";
		// }


		// $sql .= $condicion." WHERE dau_id = {$parametros['dau_id']}";
		// $response = $objCon->ejecutarSQL($sql, "Error al actualizar la indicacion aplicada dau");
		// return $response;

		$condicion = '';

		$sql = "UPDATE
					dau.dau
				SET
					dau.dau.est_id = 5,
					dau.dau.dau_indicacion_egreso_aplica_fecha = NOW(),
					dau.dau.dau_cierre_fecha_final = NOW(),
					dau.dau.dau_indicacion_egreso_aplica_usuario = '{$parametros['indAplica']}'
				";

		if($parametros['servicio_destino']) {
			$condicion.=" , dau.dau.dau_cierre_servicio = {$parametros['servicio_destino']}";
		}

		$sql .= $condicion." WHERE dau.dau.dau_id = {$parametros['dau_id']}";
		$response = $objCon->ejecutarSQL($sql, "Error al actualizar la indicacion aplicada dau");
		return $response;
	}



	function ActualizarIndicacionAplicaDauRollback($objCon, $parametros){

		$sql = "UPDATE
					dau.dau
				SET
					est_id                               = 4,
					dau_indicacion_egreso_aplica_fecha   = NULL,
					dau_indicacion_egreso_aplica_usuario = NULL,
					dau_cierre_fecha_final               = NULL,
					dau_cierre_servicio 				 = NULL
				WHERE
					dau_id = {$parametros['idDau']}";

		$response = $objCon->ejecutarSQL($sql, "Error al actualizar la indicacion aplicada dau");
		return $response;
	}



	function ActualizarIndicacionAplicaDau2($objCon, $parametros){
		$sql=" UPDATE dau.dau
					SET
						est_id = '{$parametros['estado_dau']}',
						dau_indicacion_egreso = '{$parametros['frm_indicar2']}',
						dau_defuncion_fecha = '{$parametros['frm_horadefuncion2']}',
						dau_indicacion_egreso_fecha = NOW(),
						dau_indicacion_egreso_usuario = '{$parametros['dau_mov_usuario']}',
						dau_indicacion_egreso_aplica_fecha = NOW(),
						dau_indicacion_egreso_aplica_usuario = '{$parametros['dau_mov_usuario']}',
						dau_cierre_fecha_final = NOW()
					WHERE dau_id = {$parametros['dau_id']}";
		$response = $objCon->ejecutarSQL($sql, "Error al actualizar la indicacion aplicada dau");
		return $response;
	}



	function obtenerEstadosIndicaciones($objCon,$parametros){
			 $sql="SELECT *
					from dau.estado
					LEFT JOIN dau.dau_tiene_indicacion on dau_tiene_indicacion.est_id = estado.est_id
					WHERE dau_id ='{$parametros['dau_id']}'";
			$resultado = $objCon->consultaSQL($sql,"<br> ERROR en los estado de las indicaciones <br>");
			return $resultado;
	}



	function obtenerEstadosIndicacionesDau($objCon,$parametros){
			 $sql="SELECT *
					from dau.estado
					LEFT JOIN dau.dau on dau.est_id = estado.est_id
					WHERE dau_id ='{$parametros['dau_id']}'";
			$resultado = $objCon->consultaSQL($sql,"<br> ERROR en los estado de las indicaciones dau <br>");
			return $resultado;
	}



	function actualizarCama($objCon,$parametros){
		$estadoCamaDesocupada 	= 10;
		$condicion 				= "";

		$sql=" UPDATE dau.cama";
		if($parametros['id_dau']){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_id = {$parametros['id_dau']}";
		}
		if($parametros['estadoCama'] && ($parametros['estadoCama'] != $estadoCamaDesocupada)) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" est_id = '{$parametros['estadoCama']}' ,
			              cam_fecha_desocupada = null";
		}
		if($parametros['estadoCama'] && ($estadoCamaDesocupada == $parametros['estadoCama'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" est_id = '{$parametros['estadoCama']}',
						  dau_id = null ,
						  cam_fecha_desocupada = NOW()";
		}
		$sql .= $condicion." WHERE dau_id = {$parametros['dau_id']}";
		$response = $objCon->ejecutarSQL($sql, "Error al actualizar el estado de la cama");
		return $response;
	}



	function ActualizarEstadoPyxis($objCon,$parametros){
		$sql="UPDATE dau.dau
			  SET dau.dau_pyxis = 'S'
			  WHERE dau_id ='{$parametros['dau_id']}'";
		$response = $objCon->ejecutarSQL($sql, "Error al actualizar Pyxis");
		return $response;
	}



	function getIndicacionEgreso($objCon, $parametros){
		$sql = "SELECT
				dau.dau.id_paciente,
				dau.dau_tiene_indicacion.ind_id,
				dau.dau_tiene_indicacion.dau_id,
				dau.dau_tiene_indicacion.est_id,
				dau.dau_tiene_indicacion.ind_egr_id,
				dau.dau_tiene_indicacion.dau_ind_servicio,
				dau.dau.dau_hipotesis_diagnostica_inicial,
				dau.dau.idctacte,
				paciente.paciente.rut,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,
				paciente.paciente.nroficha,
				camas.sscc.id_rau,
				camas.sscc.servicio,
				NOW() AS FechaHoraActual,
				DATE(NOW()) AS FechaActual,
				TIME(NOW()) AS HoraActual
				FROM
				dau.dau
				INNER JOIN dau.dau_tiene_indicacion ON dau.dau_tiene_indicacion.dau_id = dau.dau.dau_id
				INNER JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
				LEFT JOIN camas.sscc ON dau.dau_tiene_indicacion.dau_ind_servicio = camas.sscc.id
				WHERE
				dau.dau.dau_id ='{$parametros['dau_id']}'";
		$sql;
		$resultado = $objCon->consultaSQL($sql,"<br> ERROR en obtener los datos de indicacion del paciente <br>");
		return $resultado;
	}



	function getEgresoCierreAdministrativo($objCon, $parametros){
		$sql = "SELECT
				dau.dau.dau_id,
				dau.dau.id_paciente,
				dau.dau.dau_hipotesis_diagnostica_inicial,
				dau.dau.idctacte,
				paciente.paciente.rut,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,
				paciente.paciente.nroficha,
				camas.sscc.id_rau,
				camas.sscc.servicio,
				NOW() AS FechaHoraActual,
				DATE(NOW()) AS FechaActual,
				TIME(NOW()) AS HoraActual,
				dau.dau.dau_indicacion_egreso,
				dau.dau.dau_cierre_servicio
				FROM
				dau.dau
				INNER JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
				INNER JOIN camas.sscc ON dau.dau.dau_cierre_servicio = camas.sscc.id
				WHERE
				dau.dau.dau_id ='{$parametros['dau_id']}'";
		$resultado = $objCon->consultaSQL($sql,"<br> ERROR en obtener los datos de indicacion del paciente <br>");
		return $resultado;
	}



	function nombrePrevision($objCon, $parametros){
		$sql = "SELECT prevision.prevision FROM paciente.prevision WHERE prevision.id = {$parametros['frm_prevision']}";
		$resultado = $objCon->consultaSQL($sql,"<br> ERROR en obtener el nombre de la previsión<br>");
		return $resultado;
	}



	function nombreInstitucion($objCon, $parametros){
		$sql = "SELECT institucion.instNombre FROM paciente.institucion WHERE institucion.instCod = {$parametros['frm_formaPago']}";
		$resultado = $objCon->consultaSQL($sql,"<br> ERROR en obtener el nombre de la institución<br>");
		return $resultado;
	}



	function valorPrestacion($objCon){
		$sql = "SELECT prestacion.preFacturacion FROM paciente.prestacion WHERE prestacion.preCod = '0101103'";
		$resultado = $objCon->consultaSQL($sql,"<br> ERROR en obtener el valor de la prestación<br>");
		return $resultado;
	}



	function registrarPacAdmisionadoMatrizEstadistica($objCon, $parametros){

		$sql = "INSERT into estadistica.matrizppvppi(
					matrizppvppi.matrizCodPrestacion,
					matrizppvppi.matrizTipoPrestacionValorada,
					matrizppvppi.matrizCodPrograma,
					matrizppvppi.matrizCantPrestacion,
					matrizppvppi.matrizNombrePrestacion,
					matrizppvppi.matrizTipoPrestacion,
					matrizppvppi.matrizCodPatologia,
					matrizppvppi.matrizNombrePatologia,
					matrizppvppi.matrizValorPrestacion,
					matrizppvppi.matrizPacieCod,
					matrizppvppi.matrizRutPaciente,
					matrizppvppi.matrizNombrePacie,
					matrizppvppi.matrizFichaPacie,
					matrizppvppi.matrizSexoPacie,
					matrizppvppi.matrizFNacPacie,
					matrizppvppi.matrizPreviCod,
					matrizppvppi.matrizConvenio,
					matrizppvppi.matrizCodServicio,
					matrizppvppi.matrizNomServicio,
					matrizppvppi.matrizFRegPrestacion,
					matrizppvppi.matrizFDigitacion,
					matrizppvppi.matrizOrigenAtPrestacion,
					matrizppvppi.matrizTablaOrigen,
					matrizppvppi.matrizCantidadComprometida,
					matrizppvppi.matrizEdadPaciente,
					matrizppvppi.matrizPreviNombre,
					matrizppvppi.matrizConvenioNombre,
					matrizppvppi.matrizNombrePrograma,
					matrizppvppi.matrizNombreCompromiso,
					matrizppvppi.matrizCodCompromiso,
					matrizppvppi.matrizCodSistema,
					matrizppvppi.matrizTipoSistema,
					matrizppvppi.matrizUsuario)
					values (
					'{$parametros['matrizCodPrestacion']}',
					'{$parametros['matrizTipoPrestacionValorada']}',
					{$parametros['matrizCodPrograma']},
					{$parametros['matrizCantPrestacion']},
					'{$parametros['matrizNombrePrestacion']}',
					'{$parametros['matrizTipoPrestacion']}',
					{$parametros['matrizCodPatologia']},
					'{$parametros['matrizNombrePatologia']}',
					{$parametros['matrizValorPrestacion']},
					{$parametros['matrizPacieCod']},
					'{$parametros['matrizRutPaciente']}',
					'{$parametros['matrizNombrePacie']}',
					{$parametros['matrizFichaPacie']},
					'{$parametros['matrizSexoPacie']}',
					'{$parametros['matrizFNacPacie']}',
					{$parametros['matrizPreviCod']},
					{$parametros['matrizConvenio']},
					{$parametros['matrizCodServicio']},
					'{$parametros['matrizNomServicio']}', ";

					if ($parametros['dau_tipo_admision'] == 'M') {
						$sql .= "'{$parametros['matrizFRegPrestacion']}', ";
					}
					else{
						$sql .= "CURRENT_DATE(), ";
					}

					$sql .= " CURRENT_DATE(),
					'{$parametros['matrizOrigenAtPrestacion']}',
					'{$parametros['matrizTablaOrigen']}',
					{$parametros['matrizCantidadComprometida']},
					{$parametros['matrizEdadPaciente']},
					'{$parametros['matrizPreviNombre']}',
					'{$parametros['matrizConvenioNombre']}',
					'{$parametros['matrizNombrePrograma']}',
					'{$parametros['matrizNombreCompromiso']}',
					{$parametros['matrizCodCompromiso']},
					{$parametros['matrizCodSistema']},
					'{$parametros['matrizTipoSistema']}',
					'{$parametros['matrizUsuario']}')";
					// echo $sql;
		$response = $objCon->ejecutarSQL($sql, "Error al insertar matriz en estadistica");
		return $response;
	}



	function datosDau($objCon, $parametros){
		$sql = "SELECT *
				FROM dau.dau
				WHERE dau.dau_id = {$parametros['dau_id']}";
		$resultado = $objCon->consultaSQL($sql,"<br> ERROR en obtener los datos del DAU<br>");
		return $resultado;
	}



	function getAltaDerivacion($objCon){
		$sql="SELECT alt_der_id,alt_der_descripcion FROM dau.alta_derivacion";
		$datos = $objCon->consultaSQL($sql,"Error al getAltaDerivacion");
		return $datos;
	}



	function getAPS($objCon){
		$sql="SELECT ESTAcodigo,ESTAdescripcion FROM parametros_clinicos.establecimiento";
		$datos = $objCon->consultaSQL($sql,"Error al getAPS");
		return $datos;
	}



	function getDatosEgreso($objCon,$parametros){
		$sql="SELECT
			dau_tiene_indicacion.*
			FROM
			dau.dau_tiene_indicacion
			WHERE
			dau_tiene_indicacion.dau_id = '{$parametros['dau_id']}'";
		$resultado=$objCon->consultaSQL($sql,"<br>ERROR obtener indicacion egreso dau<br>");
		return $resultado;
	}



	function obtenerPaciente($objCon,$parametros){

		  $sql = "SELECT DISTINCT
		  		paciente.id,
				paciente.rut,
				paciente.nombres,
				paciente.apellidopat,
				paciente.apellidomat,
				paciente.fechanac,
				DATE_FORMAT(paciente.fechanac,'%d-%m-%Y') as fechaNacPaciente,
				DATE_FORMAT(paciente.fechanac,'%d%m%y') as fechaNacPaciente2,
				CONCAT(paciente.nombres,' ',paciente.apellidopat,' ',paciente.apellidomat) AS nombre_completo,
				paciente.sexo,
				paciente.direccion,
				paciente.prevision,
				paciente.nroficha,
				paciente.idcomuna,
				paciente.email,
				paciente.fono1,
				paciente.fono2,
				paciente.fono3,
				paciente.centroatencionprimaria,
				prev.instdetNombre,
				paciente.conveniopago,
				paciente.id_trakcare,
				paciente.nroficha,
				paciente.fallecido,
				paciente.prais,
				comuna.comuna,
				conv.instNombre,
				paciente.PACfono,
				paciente.PACcelular,
				paciente.extranjero,
				paciente.PACnacionalidadDesc,
				paciente.etnia,
				paciente.PACafro,
				paciente.PACdireccion,
				paciente.PACpoblacion,
				paciente.PACnumeroVivienda,
				DATE_FORMAT(paciente.PACfechaUpdateHjnc,'%d-%m-%Y') as PACfechaUpdateHjnc,
				DATE_FORMAT(paciente.PACfechaUpdateAvis,'%d-%m-%Y') as PACfechaUpdateAvis,
				religion.rlg_descripcion AS religion_descripcion
				FROM paciente.paciente
				LEFT JOIN paciente.comuna ON comuna.id = paciente.idcomuna
				LEFT JOIN paciente.institucion AS conv ON conv.instCod = paciente.conveniopago
				LEFT JOIN paciente.instituciondetalle AS prev ON prev.previCod = paciente.prevision
                LEFT JOIN paciente.religion on paciente.religion.rlg_id = paciente.paciente.religion
				WHERE paciente.id = '{$parametros['frm_id_paciente']}'";
		$datos = $objCon->consultaSQL($sql,"Error al listar citas");
		return $datos;
	}



	function getUsuarioNombre($objCon,$parametros){
		$sql="SELECT idusuario,nombreusuario
			FROM acceso.usuario
			WHERE usuario.idusuario = '{$parametros['id_usuario']}'";
		$datos = $objCon->consultaSQL($sql,"Error al getUsuarioNombre");
		return $datos;
	}



	function getDatosIngresosInterconsulta($objCon,$parametros){
		$sql="SELECT
			dau.dau_id,
			dau.id_paciente,
			dau.dau_hipotesis_diagnostica_inicial,
			dau.idctacte,
			paciente.rut,
			paciente.nombres,
			paciente.apellidopat,
			paciente.apellidomat,
			paciente.nroficha,
			NOW() AS FechaHoraActual,
			DATE(NOW()) AS FechaActual,
			TIME(NOW()) AS HoraActual,
			dau.dau_indicacion_egreso,
			dau.dau_cierre_servicio,
			dau.est_id,
			dau.dau_admision_fecha,
			dau.dau_admision_usuario,
			dau.dau_categorizacion,
			dau.dau_categorizacion_fecha,
			dau.dau_categorizacion_usuario,
			dau.dau_categorizacion_actual,
			dau.dau_categorizacion_actual_fecha,
			dau.dau_categorizacion_actual_usuario,
			dau.dau_ingreso_sala_fecha,
			dau.dau_ingreso_sala_usuario,
			dau.dau_inicio_atencion_fecha,
			dau.dau_inicio_atencion_usuario,
			dau.dau_indicacion_egreso_fecha,
			dau.dau_indicacion_egreso_usuario,
			dau.dau_indicacion_egreso_aplica_fecha,
			dau.dau_indicacion_egreso_aplica_usuario,
			dau.dau_apreciacion_diagnostica,
			dau.dau_terapia_inicial,
			dau.dau_paciente_aps,
			dau.dau_paciente_domicilio,
			dau.dau_paciente_domicilio_tipo,
			dau.dau_paciente_edad,
			dau.dau_paciente_prevision,
			dau.dau_paciente_forma_pago,
			dau.dau_atencion,
			dau.dau_motivo_consulta,
			dau.dau_motivo_descripcion,
			dau.dau_forma_llegada,
			dau.dau_mordedura,
			dau.dau_intoxicacion,
			dau.dau_quemadura,
			dau.dau_imputado,
			dau.dau_reanimacion,
			dau.dau_conscripto,
			dau.dau_tipo_accidente,
			dau.dau_accidente_escolar_institucion,
			dau.dau_accidente_escolar_numero,
			dau.dau_accidente_escolar_nombre,
			dau.dau_accidente_trabajo_mutualidad,
			dau.dau_accidente_transito_tipo,
			dau.dau_accidente_hogar_lugar,
			dau.dau_accidente_otro_lugar,
			dau.dau_agresion_vif,
			dau.dau_alcoholemia_fecha,
			dau.dau_alcoholemia_apreciacion,
			dau.dau_alcoholemia_numero_frasco,
			dau.dau_alcoholemia_resultado,
			dau.dau_alcoholemia_estado_etilico,
			dau.dau_alcoholemia_medico,
			dau.dau_defuncion_fecha,
			dau.dau_defuncion_usuario,
			dau.dau_pyxis,
			dau.dau_cierre_administrativo,
			dau.dau_cierre_condicion_ingreso_id,
			dau.dau_cierre_pronostico_id,
			dau.dau_cierre_peso,
			dau.dau_cierre_estatura,
			dau.dau_cierre_tratamiento_id,
			dau.dau_cierre_atendidopor_id,
			dau.dau_cierre_profesional_id,
			dau.dau_cierre_turno_id,
			dau.dau_cierre_hora_atencion,
			dau.dau_cierre_auge,
			dau.dau_cierre_entrega_postinor,
			dau.dau_cierre_hepatitisB,
			dau.dau_cierre_pertinencia,
			dau.dau_cierre_cie10,
			dau.dau_cierre_administrativo_observacion,
			dau.dau_cierre_administrativo_usuario,
			dau.dau_cierre_administrativo_fecha,
			dau.dau_cierre_fecha_final,
			dau.dau_hipotesis_diagnostica_fecha,
			dau.dau_hipotesis_diagnostica_usuario,
			dau.dau_tipo_admision,
			dau.dau_cierre_des_id,
			dau.dau_cierre_atl_der_id,
			dau.dau_cierre_ind_especialidad,
			dau.dau_cierre_ind_aps,
			dau.dau_cierre_ind_otros,
			dau.dau_cierre_cie10,
			dau.dau_cierre_fundamento_diag,
			cie10.cie10.nombreCIE AS nombreCIE
			FROM dau.dau
			INNER JOIN paciente.paciente ON dau.id_paciente = paciente.id
			LEFT JOIN cie10.cie10 ON dau.dau_cierre_cie10 = cie10.cie10.codigoCIE
			WHERE dau.dau_id ='{$parametros['dau_id']}'";
		$datos = $objCon->consultaSQL($sql,"Error al getDatosIngresosInterconsulta");
		return $datos;
	}



	function nombrePacienteDAU($objCon,$parametros){
		$sql = "SELECT
				dau.dau.dau_id,
				paciente.paciente.nombres,
				paciente.paciente.apellidomat,
				paciente.paciente.apellidopat,
				paciente.paciente.sexo,
				paciente.paciente.fono1,
				paciente.paciente.fono2,
				paciente.paciente.fono3,
				paciente.paciente.direccion,
				religion.rlg_descripcion AS religion_descripcion
				FROM
				dau.dau
				INNER JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
                LEFT JOIN paciente.religion on paciente.religion.rlg_id = paciente.paciente.religion
				WHERE dau.dau.dau_id = '{$parametros['dau_id']}'";
		$datos = $objCon->consultaSQL($sql,"Error al obtener nombre del paciente");
		return $datos;
	}



	function tiemposDAU($objCon,$parametros){
		$sql = "SELECT
					dau.dau_id,
					dau.est_id,
					dau.id_paciente,
					dau.idctacte,
					dau.dau_admision_fecha,
					dau.dau_categorizacion,
					dau.dau_categorizacion_fecha,
					dau.dau_categorizacion_actual,
					dau.dau_categorizacion_actual_fecha,
					dau.dau_ingreso_sala_fecha,
					dau.dau_inicio_atencion_fecha,
					dau.dau_indicacion_egreso_fecha,
					dau.dau_indicacion_egreso_aplica_fecha,
					dau.dau_alcoholemia_fecha,
					dau.dau_defuncion_fecha,
					dau.dau_cierre_administrativo_fecha,
					dau.dau_cierre_fecha_final,
					dau.dau_hipotesis_diagnostica_fecha,
					NOW() AS FechaActual,
					categorizacion.cat_nivel,
					categorizacion.cat_tiempo_maximo,
					categorizacion.cat_tipo
					FROM
					dau.dau
					LEFT JOIN categorizacion ON dau.dau_categorizacion = categorizacion.cat_id
					WHERE dau.dau_id ='{$parametros['dau_id']}'";
		$datos = $objCon->consultaSQL($sql,"Error al obtener datos del DAU");
		return $datos;
	}



	function tiemposDAUActGine($objCon, $estado){

		$sql = "SELECT
				dau.dau.dau_id,
				dau.dau.est_id,
				dau.dau.id_paciente,
				dau.dau.dau_admision_fecha,
				dau.dau.dau_categorizacion,
				dau.dau.dau_categorizacion_fecha,
				dau.dau.dau_categorizacion_actual,
				dau.dau.dau_categorizacion_actual_fecha,
				dau.dau.dau_motivo_consulta,
				dau.dau.dau_motivo_descripcion,
				dau.dau_ingreso_sala_fecha,
				dau.dau_inicio_atencion_fecha,
				dau.dau_indicacion_egreso_fecha,
				dau.dau_indicacion_egreso_aplica_fecha,
				dau.dau_alcoholemia_fecha,
				dau.dau_defuncion_fecha,
				paciente.paciente.rut,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,
				paciente.paciente.sexo,
				paciente.paciente.fechanac,
				dau.dau.dau_atencion,
				dau.atencion.ate_descripcion,
				dau.motivo_consulta.mot_descripcion,
				NOW() AS FechaActual,
				dau.categorizacion.cat_nivel,
				dau.categorizacion.cat_tiempo_maximo,
				dau.categorizacion.cat_tipo,
				dau.categorizacion.cat_nombre_mostrar,
				religion.rlg_descripcion AS religion_descripcion
				FROM
				dau.dau
				INNER JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
				INNER JOIN dau.atencion ON dau.dau.dau_atencion = dau.atencion.ate_id
				INNER JOIN dau.motivo_consulta ON dau.dau.dau_motivo_consulta = dau.motivo_consulta.mot_id
				LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
                LEFT JOIN paciente.religion on paciente.religion.rlg_id = paciente.paciente.religion
				WHERE dau.est_id = '{$estado}'
				AND dau.dau.dau_atencion = '3'
				ORDER BY dau.categorizacion.cat_nivel, dau.dau.dau_id ";

		$datos = $objCon->consultaSQL($sql,"Error al obtener datos del DAU");
		return $datos;
	}



	function ListarPacienteLineaTiempo($objCon, $parametros){
			$sql="SELECT
						dau.dau_id AS id,
						1 AS estado,
						dau.dau_admision_fecha AS fecha,
						dau.dau_admision_usuario AS usuario
					FROM dau.dau
					WHERE dau.dau_id = '{$parametros['dau_id']}'
					AND dau.dau_admision_fecha <> ''
					AND dau.dau_admision_usuario <> ''
					UNION
					SELECT
						dau.dau_id,
						2 AS estado,
						dau.dau_categorizacion_actual_fecha AS fecha,
						dau.dau_categorizacion_actual_usuario AS usuario
					FROM dau.dau
					WHERE dau.dau_id = '{$parametros['dau_id']}'
					AND dau.dau_categorizacion_actual_fecha <> ''
					AND dau.dau_categorizacion_actual_usuario <> ''
					UNION
					SELECT
						dau.dau_id AS id,
						3 AS estado,
						dau.dau_inicio_atencion_fecha AS fecha,
						dau.dau_inicio_atencion_usuario AS usuario
					FROM dau.dau
					WHERE dau.dau_id = '{$parametros['dau_id']}'
					AND dau.dau_inicio_atencion_fecha <> ''
					AND dau.dau_inicio_atencion_usuario <> ''
					UNION
					SELECT
						dau.dau_id AS id,
						4 AS estado,
						dau.dau_indicacion_egreso_fecha AS fecha,
						dau.dau_indicacion_egreso_usuario AS usuario
					FROM dau.dau
					WHERE dau.dau_id = '{$parametros['dau_id']}'
					AND dau.dau_indicacion_egreso_fecha <> ''
					AND dau.dau_indicacion_egreso_usuario <> ''
					UNION
					SELECT
						dau.dau_id AS id,
						5 AS estado,
						dau.dau_indicacion_egreso_aplica_fecha AS fecha,
						dau.dau_indicacion_egreso_aplica_usuario AS usuario
					FROM dau.dau
					WHERE dau.dau_id = '{$parametros['dau_id']}'
					AND dau.dau_cierre_fecha_final <> ''
					AND dau.dau_indicacion_egreso_aplica_usuario <> ''
					UNION
					SELECT
						dau.dau_id AS id,
						6 AS estado,
						dau.dau_cierre_fecha_final AS fecha,
						dau_cierre_administrativo_usuario AS usuario
					FROM dau.dau
					WHERE dau.dau_id = '{$parametros['dau_id']}'
					AND dau.dau_cierre_fecha_final <> ''
					AND dau_cierre_administrativo_usuario <> ''
					UNION
					SELECT
						dau.dau_id AS id,
						8 AS estado,
						dau.dau_ingreso_sala_fecha AS fecha,
						dau.dau_ingreso_sala_usuario AS usuario
					FROM dau.dau
					WHERE dau.dau_id = '{$parametros['dau_id']}'
					AND dau.dau_ingreso_sala_fecha <> ''
					AND dau.dau_ingreso_sala_usuario <> ''
					ORDER BY fecha";
		$resultado=$objCon->consultaSQL($sql,"<br>ERROR ListarPacienteLineaTiempo dau<br>");
		return $resultado;
	}



	function insertarMovimientoIndicacion($objCon, $parametros){

		$dau_mov_ind_desc = $parametros['descripcionIndicacionEgreso'];
		if(!empty($parametros['descripcionServicioDestinos'])){
			$dau_mov_ind_desc .= '-'.$parametros['descripcionServicioDestinos'];
		}
		if(!empty($parametros['descripcionAltaDestinos'])){
			$dau_mov_ind_desc .= '-'.$parametros['descripcionAltaDestinos'];
		}
		if(!empty($parametros['descripcionAltaEspecialidad'])){
			$dau_mov_ind_desc .= '-'.$parametros['descripcionAltaEspecialidad'];
		}
		if(!empty($parametros['descripcionAltaAps'])){
			$dau_mov_ind_desc .= '-'.$parametros['descripcionAltaAps'];
		}
		if(!empty($parametros['fechaDefuncion'])){
			$dau_mov_ind_desc .= '-'.$parametros['fechaDefuncion'];
		}
		if(!empty($parametros['destinoDefuncion'])){
			$dau_mov_ind_desc .= '-'.$parametros['destinoDefuncion'];
		}
		if(!empty($parametros['frm_otros'])){
			$dau_mov_ind_desc .= '-'.$parametros['frm_otros'];
		}

		$sql = "SELECT
					dau_id
				FROM
					dau.dau_movimiento_indicacion
				WHERE
					dau_id = {$parametros['dau_id']}";

		$dau_id =  $objCon->consultaSQL($sql, "ERROR AL BUSCAR ID DE DAU");

		if(is_null($dau_id) || empty($dau_id)){
			$dau_mov_ind_accion = 'indicacion egreso';
			$dau_id[0]['dau_id'] = $parametros['dau_id'];
		}
		else{
			$dau_mov_ind_accion = 'modificar indicacion egreso';
		}

	 	$sql="INSERT INTO dau.dau_movimiento_indicacion(
		 			dau_id,
					dau_mov_ind_accion,
					ind_id,
					ind_egr_id,
					des_id,
					alt_der_id,
					dau_ind_especialidad,
					dau_ind_aps,
					dau_ind_otros,
					dau_ind_servicio,
					dau_mov_ind_desc,
					dau_mov_ind_fecha,
					dau_mov_ind_usuario
				)

				VALUES(
					{$dau_id[0]['dau_id']},
					'{$dau_mov_ind_accion}',
					1,
					{$parametros['frm_Indicacion_Egreso']},
					'{$parametros['frm_sum_indicacion']}',
					'{$parametros['frm_alta_derivacion']}',
					'{$parametros['especialidad']}',
					'{$parametros['aps']}',
					'{$parametros['frm_otros']}',
					'{$parametros['frm_servicio_destino']}',
					'{$dau_mov_ind_desc}',
					NOW(),
					'{$parametros['indEgreso']}'
				) ";
	   $response = $objCon->ejecutarSQL($sql, "ERROR AL GUARDAR EN LOG INDICAR EGRESO");
	}



	function editarFechasHorasPaciente($objCon, $parametros){

		$condicion = "";

		if (isset($_SESSION['usuarioActivo']['usuario'])) {
			$nuevoUsuario = $_SESSION['usuarioActivo']['usuario'];
		}else{
			$nuevoUsuario = $_SESSION['MM_Username'.SessionName];
		}

		$sql = "UPDATE
					dau.dau
				SET ";

				if( !is_null($parametros['frm_dau_inicio_atencion_fecha']) && !is_null($parametros['frm_dau_inicio_atencion_hora'])){
					$condicion.= "dau.dau.dau_inicio_atencion_fecha = CONCAT('{$parametros['frm_dau_inicio_atencion_fecha']}',' ','{$parametros['frm_dau_inicio_atencion_hora']}')
					              ,  dau.dau.dau_inicio_atencion_usuario = '{$nuevoUsuario}'";
				}

				if( !is_null($parametros['frm_dau_indicacion_egreso_fecha']) && !is_null($parametros['frm_dau_indicacion_egreso_fecha'])){
					$condicion.= ",  dau.dau.dau_indicacion_egreso_fecha = CONCAT('{$parametros['frm_dau_indicacion_egreso_fecha']}',' ','{$parametros['frm_dau_indicacion_egreso_hora']}')
								  ,  dau.dau.dau_indicacion_egreso_usuario = '{$nuevoUsuario}'";
				}

				if( !is_null($parametros['frm_dau_aplicacion_egreso_fecha']) && !is_null($parametros['frm_dau_aplicacion_egreso_fecha'])){
					$condicion.= ",  dau.dau.dau_indicacion_egreso_aplica_usuario = CONCAT('{$parametros['frm_dau_aplicacion_egreso_fecha']}',' ','{$parametros['frm_dau_aplicacion_egreso_hora']}')
					              ,  dau.dau.dau_indicacion_egreso_aplica_usuario = '{$nuevoUsuario}'";
				}

				$condicion.= "
					WHERE
						dau.dau.dau_id = {$parametros['frm_dau_id']}";

		$sql.=$condicion;
		$response = $objCon->ejecutarSQL($sql, "ERROR AL EDITAR FECHAS Y HORAS DEL PACIENTE");
	}



	function getDatosAlcoholemia($objCon, $parametros){

		$sql = "SELECT
					dau.dau_alcoholemia_fecha,
					dau.dau_alcoholemia_apreciacion,
					dau.dau_alcoholemia_numero_frasco,
					dau.dau_alcoholemia_resultado,
					dau.dau_alcoholemia_estado_etilico,
					dau.dau_alcoholemia_medico
				FROM
					dau.dau
				WHERE
					dau.dau.dau_id = {$parametros['dau_id']} ";

		$response = $objCon->consultaSQL($sql, "ERROR AL OBTENER DATOS DE ALCOHOLEMIA");
		return $response;
	}



	function getDatosDauInicioAtencion($objCon, $parametros){

		$sql = "SELECT
					dau.dau.dau_inicio_atencion_fecha,
					dau.dau.dau_inicio_atencion_usuario,
					acceso.usuario.nombreusuario
				FROM
					dau.dau
				INNER JOIN
					acceso.usuario ON dau.dau.dau_inicio_atencion_usuario = acceso.usuario.idusuario
				WHERE
					dau.dau.dau_id = {$parametros['dau_id']}";

		$response = $objCon->consultaSQL($sql, "ERROR AL OBTENER DATOS DE INICIO DE ATENCIÓN");
		return $response;
	}



	function getDatosDauIndicacionEgreso($objCon, $parametros){

		$sql = "SELECT
					dau.dau.dau_indicacion_egreso_fecha,
					dau.dau.dau_indicacion_egreso_usuario,
					acceso.usuario.nombreusuario
				FROM
					dau.dau
				INNER JOIN
					acceso.usuario ON dau.dau.dau_indicacion_egreso_usuario = acceso.usuario.idusuario
				WHERE
					dau.dau.dau_id = {$parametros['dau_id']}";

		$response = $objCon->consultaSQL($sql, "ERROR AL OBTENER DATOS DE INDICACIÓN DE EGRESO");
		return $response;
	}



	function getDatosDauAplicarEgreso($objCon, $parametros){

		$sql = "SELECT
					dau.dau.dau_indicacion_egreso_aplica_fecha,
					dau.dau.dau_indicacion_egreso_aplica_usuario,
					acceso.usuario.nombreusuario
				FROM
					dau.dau
				INNER JOIN
					acceso.usuario ON dau.dau.dau_indicacion_egreso_aplica_usuario = acceso.usuario.idusuario
				WHERE
					dau.dau.dau_id = {$parametros['dau_id']}";

		$response = $objCon->consultaSQL($sql, "ERROR AL OBTENER DATOS DE APLICACIÓN DE EGRESO");
		return $response;
	}



	function obtenerEtilico($objCon){
		$sql = "SELECT *
			  FROM dau.etilico";
		$datos = $objCon->consultaSQL($sql,"Error al listar datos de estados etilicos");
		return $datos;
	}



	function getIndEgreso($objCon){
		$sql="SELECT ind_egr_id,ind_egr_descripcion FROM dau.indicacion_egreso";
		$datos = $objCon->consultaSQL($sql,"Error al getIndEgresos");
		return $datos;
	}



	function actualizarSolicitudInicioAtencion($objCon, $parametros){

		$sql = "	UPDATE
						rce.solicitud_inicioatencion
					SET
						solicitud_inicioatencion.SIAfechaModificacion = NOW(),
						solicitud_inicioatencion.SIAusuarioModifica = '{$parametros['SIAusuarioModifica']}'
					WHERE
						rce.solicitud_inicioatencion.SIAid = '{$parametros['SIAid']}'";

		$response = $objCon->ejecutarSQL($sql, "ERROR AL ACTUALIZAR SOLICITUD INICIO ATENCIÓN");

	}



	function obtenerDatosSolicitudInicioAtencion($objCon, $idRCE){

		$sql = "	SELECT
						rce.solicitud_inicioatencion.*
					FROM
						rce.solicitud_inicioatencion
					WHERE
						rce.solicitud_inicioatencion.SIAidRCE = '{$idRCE}'";

		$response = $objCon->consultaSQL($sql,"ERROR AL OBTENER DATOS DE INICIO DE ATENCIÓN");

		return $response;
	}


	function obtenerDatosSolicitudInicioAtencionDATOS($objCon, $idRCE){

		$sql = "	SELECT
						rce.solicitud_inicioatencion.SIAusuarioModifica,
						rce.solicitud_inicioatencion.SIAfechaModificacion
					FROM
						rce.solicitud_inicioatencion
					WHERE
						rce.solicitud_inicioatencion.SIAidRCE = '{$idRCE}'";

		$response = $objCon->consultaSQL($sql,"ERROR AL OBTENER DATOS DE INICIO DE ATENCIÓN");

		return $response;
	}

	function ingresarSolicitudInicioAtencion($objCon, $parametros){

		$sql = "	INSERT INTO
						rce.solicitud_inicioatencion
						(
							SIAfecha,
							SIAidRCE,
							SIAidPaciente,
							SIAusuario
						)
						VALUES
						(
							NOW(),
							'{$parametros['SIAidRCE']}',
							'{$parametros['SIAidPaciente']}',
							'{$parametros['SIAusuario']}'
						)";

		$response = $objCon->ejecutarSQL($sql, "ERROR AL INGRESAR SOLICITUD INICIO ATENCIÓN");

	}



	function actualizarDatosPDFAltaUrgencia($objCon, $parametros){

		$sql = "	UPDATE
						dau.dau
					SET
						dau.dau_run_pac = '{$parametros['dau_run_pac']}',
						dau.dau_nombre_pac = '{$parametros['dau_nombre_pac']}',
						dau.dau_sexo_pac = '{$parametros['dau_sexo_pac']}',
						dau.dau_direccion_pac = '{$parametros['dau_direccion_pac']}',
						dau.dau_fono_pac = '{$parametros['dau_fono_pac']}'
					WHERE
						dau.dau_id = '{$parametros['dau_id']}'";

		$response = $objCon->ejecutarSQL($sql, "ERROR AL ACTUALIZAR DATOS PDF ALTA URGENCIA");

	}



	function resultadoAlcoholemia( $objCon, $idDau ) {

		$sql = 	"	SELECT
						dau_alcoholemia_estado_etilico,
						dau_alcoholemia_fecha,
						dau_alcoholemia_numero_frasco
					FROM
						dau.dau
					WHERE
						dau_id = '{$idDau}' ";

		$response = $datos = $objCon->consultaSQL($sql,"Error al obtener datos etílicos del paciente");

		return $response[0];

	}



	function profesionalAlcoholemia ( $objCon, $idDau ) {

		$sql = "	SELECT
						usuarioIniciaAtencion.nombreusuario AS usuarioIniciaAtencion,
						usuarioModificaAtencion.nombreusuario AS usuarioModificaAtencion
					FROM
						dau.dau
					LEFT JOIN
						rce.registroclinico ON dau.dau.dau_id = rce.registroclinico.dau_id
					LEFT JOIN
						rce.solicitud_inicioatencion ON rce.registroclinico.regId = rce.solicitud_inicioatencion.SIAidRCE
					LEFT JOIN
						acceso.usuario AS usuarioIniciaAtencion ON rce.solicitud_inicioatencion.SIAusuario = usuarioIniciaAtencion.idusuario
					LEFT JOIN
						acceso.usuario AS usuarioModificaAtencion ON rce.solicitud_inicioatencion.SIAusuarioModifica = usuarioModificaAtencion.idusuario
					WHERE
						dau.dau.dau_id =  '{$idDau}' ";

		$response = $datos = $objCon->consultaSQL($sql,"Error al obtener datos de profesional que registro datos etílicos del paciente");

		return $response[0];

	}



	function dau_indicacion($objCon, $parametros){
		$sql = "UPDATE	dau.dau
				SET		dau.dau_indicaciones_completas = '{$parametros['dau_indicacion_terminada']}'
				WHERE	dau.dau_id = '{$parametros['dau_id']}'";
		$objCon->ejecutarSQL($sql, "Error al actualizar los datos de dau_indicacion");
	}



	function dau_indicaciones_solicitadas_realizadas($objCon, $parametros){
		$sql = "UPDATE	dau.dau
				SET		dau_indicaciones_solicitadas = '{$parametros['dau_indicaciones_solicitadas']}',
						dau_indicaciones_realizadas  = '{$parametros['dau_indicaciones_realizadas']}'
				WHERE	dau.dau_id = '{$parametros['dau_id']}'";
		$objCon->ejecutarSQL($sql, "Error al actualizar los datos de dau_indicacion");
	}



	function dau_PacienteComplejo($objCon, $parametros){
		$sql = "UPDATE	dau.dau
				SET		dau_paciente_complejo 		      = null,
						dau_paciente_complejo_fecha       = null,
						dau_paciente_complejo_usuario     = null,
						dau_paciente_complejo_observacion = null
				WHERE	dau.dau_id = '{$parametros['dau_id']}'";
		$objCon->ejecutarSQL($sql, "Error al actualizar los datos de dau_indicacion");
	}



	function dau_PacienteComplejo2($objCon, $parametros){
		$sql = "UPDATE	dau.dau
				SET		dau_paciente_complejo 		      = 'S',
						dau_paciente_complejo_fecha       = NOW(),
						dau_paciente_complejo_usuario     = '{$parametros['dau_mov_usuario']}',
						dau_paciente_complejo_observacion = '{$parametros['frm_observacion']}'
				WHERE	dau.dau_id = '{$parametros['dau_id']}'";
		$objCon->ejecutarSQL($sql, "Error al actualizar los datos de dau_indicacion");
	}



	function getAltaDerivacionMPISO($objCon){
		$sql="SELECT alt_der_id,alt_der_descripcion FROM dau.alta_derivacion
			WHERE alt_der_ap = 'S'";
		$datos = $objCon->consultaSQL($sql,"Error al getAltaDerivacion");
		return $datos;
	}



	function getAltaDerivacionMPISOGO($objCon){
		$sql="SELECT alt_der_id,alt_der_descripcion FROM dau.alta_derivacion
			WHERE alt_der_g = 'S'";
		$datos = $objCon->consultaSQL($sql,"Error al getAltaDerivacion");
		return $datos;
	}



	function obtenerDestinosUrgenciaContingencia ( $objCon ) {

		$sql = "SELECT
					dau.alta_derivacion.alt_der_id,
					dau.alta_derivacion.alt_der_descripcion
				FROM
					dau.alta_derivacion
				WHERE
					dau.alta_derivacion.alt_der_contingencia = 'S'
				";

		$datos = $objCon->consultaSQL($sql,"Error al obtener destinos contingencia");

		return $datos;

	}



	function dau_inicioAtencionCambioTurno($objCon, $parametros){
		$sql = "UPDATE	dau.dau
				SET		dau_inicio_atencion_usuario = '{$parametros['usuarioMedicoTratante']}'
				WHERE	dau.dau_id = '{$parametros['dauId']}'";
		$objCon->ejecutarSQL($sql, "Error al actualizar los datos de dau_indicacion");
	}



	function ingresarLlamado ( $objCon, $parametros ) {

		if ( $parametros['numeroLlamado'] == 'primero' ) {

			$sql = "	INSERT INTO
							dau.tablallamadonea
							(
								idDau,
								fechaPrimerLlamado,
								usuarioPrimerLlamado
							)
						VALUES
							(
								'{$parametros['idDau']}',
								NOW(),
								'{$parametros['usuario']}'
							)";

			$response = $objCon->ejecutarSQL($sql, "Error al ingresar primera llamada en NEA");

		} else if ( $parametros['numeroLlamado'] == 'segundo' ) {

			$sql = "	UPDATE
							dau.tablallamadonea
						SET
							fechaSegundoLlamado = NOW(),
							usuarioSegundoLlamado = '{$parametros['usuario']}'
						WHERE
							idDau =  '{$parametros['idDau']}' ";

			$response = $objCon->ejecutarSQL($sql, "Error al ingresar segundo llamado en NEA");

		} else if ( $parametros['numeroLlamado'] == 'tercero' ) {

			$sql = "	UPDATE
							dau.tablallamadonea
						SET
							fechaTercerLlamado = NOW(),
							usuarioTercerLlamado = '{$parametros['usuario']}'
						WHERE
							idDau =  '{$parametros['idDau']}' ";

			$response = $objCon->ejecutarSQL($sql, "Error al ingresar tercer llamado en NEA");

		}

	}



	function obtenerInformacionLlamados ( $objCon, $idDau ) {

		$sql 		= "	SELECT
								dau.tablallamadonea.*
							FROM
								dau.tablallamadonea
							WHERE
								dau.tablallamadonea.idDau = '{$idDau}' ";

		$resultado 	= $objCon->consultaSQL($sql,"Error al obtener informacion de los llamados de NEA");

		return $resultado[0];

	}



	function fechaAdmision ( $objCon, $idDau ) {
		$sql = "SELECT
					dau.dau_admision_fecha
				FROM
					dau.dau
				WHERE dau.dau_id = '{$idDau}' ";
		$resultado=$objCon->consultaSQL($sql,"<br>ERROR Traer Fecha Admisión<br>");
		return $resultado;

	}



	function registrarFechaDefuncion ( $objCon, $parametros ) {

		$sql=" UPDATE dau.dau
				SET
					dau.est_id = {$parametros['estado_dau']},
					dau.dau_indicacion_egreso_fecha = NOW(),
					dau.dau_indicacion_egreso = '{$parametros['frm_Indicacion_Egreso']}',
					dau.dau_indicacion_egreso_usuario ='{$parametros['indEgreso']}',
					dau.dau_defuncion_fecha = '{$parametros['fecha_defuncion']}',
					dau.dau_defuncion_usuario = '{$parametros['usuario_defuncion_ingreso']}',
					dau.dau_cierre_cie10	=	'{$parametros['frm_codigoCIE10']}'
				WHERE
					dau.dau_id = '{$parametros['dau_id']}' ";

		$objCon->ejecutarSQL($sql, "Error en actualizar fecha de defunción");

	}



	function obtenerDatosBusquedaPDFRCE($objCon, $idDau) {

		$sql = "SELECT
					dau.id_paciente,
					dau.dau_admision_fecha,
					dau.dau_indicacion_egreso_fecha,
					dau.est_id
				FROM
					dau.dau
				WHERE dau.dau_id = '{$idDau}' ";

		$resultado=$objCon->consultaSQL($sql,"<br>ERROR al obtener datos para la búsqueda de PDF de RCE<br>");

		return $resultado[0];

	}



	function obtenerIdPacienteSegunDAU($objCon, $idDau) {

		$sql = "SELECT
					dau.id_paciente
				FROM
					dau
				WHERE dau.dau_id = '{$idDau}' ";

		$resultado=$objCon->consultaSQL($sql,"<br>ERROR al obtener datos para la búsqueda de PDF de RCE<br>");

		return $resultado[0];

	}



	function tiemposDAUActFull($objCon, $estado){

		$sql = "SELECT
				dau.dau.dau_id,
				dau.dau.est_id,
				dau.dau.id_paciente,
				dau.dau.dau_admision_fecha,
				dau.dau.dau_categorizacion,
				dau.dau.dau_categorizacion_fecha,
				dau.dau.dau_categorizacion_actual,
				dau.dau.dau_categorizacion_actual_fecha,
				dau.dau.dau_motivo_consulta,
				dau.dau.dau_motivo_descripcion,
				dau.dau_ingreso_sala_fecha,
				dau.dau_inicio_atencion_fecha,
				dau.dau_indicacion_egreso_fecha,
				dau.dau_indicacion_egreso_usuario,
				dau.dau_indicacion_egreso_aplica_fecha,
				dau.indicacion_egreso.ind_egr_descripcion,
				dau.dau_alcoholemia_fecha,
				dau.dau_defuncion_fecha,
				dau.dau_indicaciones_solicitadas,
				dau.dau_indicaciones_completas,
				dau.dau_abierto_mantenedor,
				paciente.paciente.rut,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,
				paciente.paciente.sexo,
				paciente.paciente.fechanac,
				dau.dau.dau_atencion,
				dau.atencion.ate_descripcion,
				dau.motivo_consulta.mot_descripcion,
				NOW() AS FechaActual,
				dau.categorizacion.cat_nivel,
				dau.categorizacion.cat_tiempo_maximo,
				dau.categorizacion.cat_tipo,
				dau.categorizacion.cat_nombre_mostrar,
				dau.categorizacion.cat_tiempo_alerta,
				religion.rlg_descripcion AS religion_descripcion
				FROM
				dau.dau
				INNER JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
				INNER JOIN dau.atencion ON dau.dau.dau_atencion = dau.atencion.ate_id
				INNER JOIN dau.motivo_consulta ON dau.dau.dau_motivo_consulta = dau.motivo_consulta.mot_id
				LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
				LEFT JOIN dau.indicacion_egreso ON dau.dau.dau_indicacion_egreso = dau.indicacion_egreso.ind_egr_id
                LEFT JOIN paciente.religion on paciente.religion.rlg_id = paciente.paciente.religion
				WHERE dau.est_id IN ({$estado})
				ORDER BY dau.categorizacion.cat_nivel, dau.dau.dau_id ";

		$datos = $objCon->consultaSQL($sql,"Error al obtener datos del DAU");
		return $datos;
	}



	function obtenerDatosDetalleDau ( $objCon, $idDau ) {

		$sql = "SELECT
					CONCAT( paciente.paciente.nombres, ' ', paciente.paciente.apellidopat, ' ', paciente.paciente.apellidomat ) AS nombreCompletoPaciente,
					paciente.paciente.rut AS rutPaciente,
					paciente.paciente.transexual,
					paciente.paciente.nombreSocial,
					paciente.paciente.fechanac AS fechaNacimientoPaciente,
					CONCAT( paciente.paciente.calle, ' Nº ', paciente.paciente.numero, ' ', paciente.paciente.restodedireccion ) AS direccionCompletaPaciente,
					paciente.paciente.nroficha AS numeroFichaPaciente,
					paciente.prevision.prevision AS previsionPaciente,
					dau.atencion.ate_descripcion AS tipoAtencionPaciente,
					dau.motivo_consulta.mot_descripcion AS descripcionConsulta,
					dau.estado.est_descripcion AS descripcionEstado,
					dau.categorizacion.cat_nombre_mostrar AS categoriaPaciente,
					dau.sala.sal_descripcion AS descripcionSala,
					dau.cama.cam_descripcion AS descripcionCama,
					dau.dau.dau_motivo_descripcion AS detalle,
					dau.dau.est_id,
					dau.dau_tiene_indicacion.dau_ind_fecha_indicada AS fechaIndicacion,
					dau.indicacion.ind_descripcion AS indicacion,
					dau.indicacion_egreso.ind_egr_descripcion AS descripcionIndicacion,
					dau.dau_manifestaciones AS manifestaciones,
					camas.sscc.servicio AS descripcionServicio,
					estadoIndicacion.est_descripcion AS descripcionEstadoIndicacion,
					dau.dau_tiene_indicacion.dau_ind_usuario_indica AS usuarioIndica,
					dau.dau_tiene_indicacion.dau_ind_usuario_aplica AS usuarioAplica,
                    religion.rlg_descripcion AS religion_descripcion
				FROM
					dau.dau
					INNER JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					INNER JOIN paciente.prevision ON dau.dau.dau_paciente_prevision = paciente.prevision.id
					INNER JOIN dau.atencion ON dau.dau.dau_atencion = dau.atencion.ate_id
					INNER JOIN dau.motivo_consulta ON dau.dau.dau_motivo_consulta = dau.motivo_consulta.mot_id
					INNER JOIN dau.estado ON dau.dau.est_id = dau.estado.est_id
					LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion = dau.categorizacion.cat_id
					LEFT JOIN dau.cama ON dau.dau.dau_id = dau.cama.dau_id
					LEFT JOIN dau.dau_tiene_indicacion ON dau.dau.dau_id = dau.dau_tiene_indicacion.dau_id
					LEFT JOIN dau.estado AS estadoIndicacion ON dau.dau_tiene_indicacion.est_id = estadoIndicacion.est_id
					LEFT JOIN dau.indicacion_egreso ON dau.dau_tiene_indicacion.ind_egr_id = dau.indicacion_egreso.ind_egr_id
					LEFT JOIN dau.sala ON dau.cama.sal_id = dau.sala.sal_id
					LEFT JOIN camas.sscc ON dau.dau_tiene_indicacion.dau_ind_servicio = camas.sscc.id
					LEFT JOIN dau.indicacion ON dau.dau_tiene_indicacion.ind_id = dau.indicacion.ind_id
                    LEFT JOIN paciente.religion on paciente.religion.rlg_id = paciente.paciente.religion
				WHERE
					dau.dau.dau_id = '{$idDau}' ";

		$datos = $objCon->consultaSQL($sql,"Error al obtener datos de Detalle de DAU");

		return $datos;

	}



	function seHaIniciadoAtencion ( $objCon, $idDau ) {

		$sql = "SELECT
					dau.dau_inicio_atencion_fecha
				FROM
					dau
				WHERE
					dau.dau_id = '{$idDau}' ";

		$datos = $objCon->consultaSQL($sql,"Error al obtener fecha inicio atención al paciente");

		return $datos[0];

	}



	function obtenerEstadoDauPaciente ( $objCon, $idDau ) {
		$sql = "SELECT
					dau.est_id,
					dau.dau_abierto_mantenedor
				FROM
					dau.dau
				WHERE
					dau.dau_id = '{$idDau}' ";
		$datos = $objCon->consultaSQL($sql,"Error al obtener estado actual de DAU del paciente");
		return $datos[0];
	}



	function obtenerDatosDetalleDauDesplegarCategorizacion ( $objCon, $idDau ) {

		$sql = "SELECT
					CONCAT( paciente.paciente.nombres, ' ', paciente.paciente.apellidopat, ' ', paciente.paciente.apellidomat ) AS nombreCompletoPaciente,
					dau.motivo_consulta.mot_descripcion AS descripcionConsulta,
					dau.dau.dau_motivo_descripcion AS detalle,
					dau.dau.dau_id,
					dau.dau.est_id,
					dau_viaje_epidemiologico,
					paciente.nacionalidadavis.NACpais AS dau_pais_epidemiologia,
					dau.dau.dau_observacion_epidemiologica,
					paciente.paciente.fechanac,
					paciente.paciente.transexual,
					paciente.paciente.nombreSocial,
					religion.rlg_descripcion AS religion_descripcion
				FROM
					dau.dau
					INNER JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					INNER JOIN dau.motivo_consulta ON dau.dau.dau_motivo_consulta = dau.motivo_consulta.mot_id
					LEFT JOIN  paciente.nacionalidadavis ON dau.dau.dau_pais_epidemiologia = paciente.nacionalidadavis.NACcodigo
                    LEFT JOIN paciente.religion on paciente.religion.rlg_id = paciente.paciente.religion

				WHERE
					dau.dau.dau_id = '{$idDau}' ";

		$datos = $objCon->consultaSQL($sql,"Error al obtener datos de Detalle de DAU");

		return $datos[0];

	}



	function obtenerPostIndicacionEgreso ( $objCon ) {

		$sql = "SELECT
					*
				FROM
					dau.tipo_post_indicacion_egreso
				ORDER BY
					dau.tipo_post_indicacion_egreso.descripcionPostIndicacionEgreso ASC";

		$datos = $objCon->consultaSQL($sql,"Error al obtener datos post indicación de egreso");

		return $datos;

	}



	function tiempoIndicacionEgreso ( $objCon, $idDau ) {

		$sql = "SELECT
					dau.dau.est_id,
					dau.dau.dau_indicacion_egreso_fecha,
					dau.dau.dau_abierto_mantenedor,
					dau.dau.dau_indicacion_egreso
				FROM
					dau.dau
				WHERE
					dau.dau.dau_id = '{$idDau}' ";

		$datos = $objCon->consultaSQL($sql,"Error al obtener dato tiempo indicacion egreso");

		return $datos[0];

	}



	function obtenerInfoNumeroAtencion ( $objCon, $parametros ) {

		$parametros['tipoAtencion'] = $this->obtenerTipoAtencionSegunDau($objCon, $parametros['idDau']);

		$sql = "SELECT
					*
				FROM
					dau.numero_atencion_dau
				WHERE
					dau.numero_atencion_dau.tipoCategorizacion = '{$parametros['tipoCategorizacion']}'
				AND
					dau.numero_atencion_dau.tipoAtencion = '{$parametros['tipoAtencion']}' ";

		$datos = $objCon->consultaSQL($sql,"Error al obtener info sobre numero de atención");

		return $datos[0];

	}



	function obtenerTipoAtencionSegunDau ( $objCon, $idDau ) {

		 $sql = "SELECT
					dau.dau.dau_atencion
				FROM
					dau.dau
				WHERE
					dau.dau.dau_id = '{$idDau}' ";

		$datos = $objCon->consultaSQL($sql,"Error al obtener tipo de atención según número de DAU");

		return $datos[0]['dau_atencion'];

	}



	function insertarNumeroAtencionDau ( $objCon, $parametros ) {

		$sql = "UPDATE
					dau.dau
				SET
					dau.dau.dau_numero_atencion = '{$parametros['numeroAtencion']}'
				WHERE
					dau.dau.dau_id = '{$parametros['idDau']}' ";

		$objCon->ejecutarSQL($sql, "Error al ingresar número de atención a DAU");

	}



	function actualizarNumeroAtencionDau ( $objCon, $parametros ) {

		$parametros['tipoAtencion'] = $this->obtenerTipoAtencionSegunDau($objCon, $parametros['idDau']);

		$sql = "UPDATE
					dau.numero_atencion_dau
				SET
					dau.numero_atencion_dau.numeroActual = '{$parametros['numeroAGuardar']}'
				WHERE
					dau.numero_atencion_dau.tipoCategorizacion = '{$parametros['tipoCategorizacion']}'
				AND
					dau.numero_atencion_dau.tipoAtencion = '{$parametros['tipoAtencion']}' ";

		$objCon->ejecutarSQL($sql, "Error al actualizar número de atención según categorización");

	}



	function obtenerDatosImpresionVoucherTermico ( $objCon, $idDau ) {

		$sql = "SELECT
					dau.dau.dau_id AS idDau,
					dau.dau.idctacte AS cuentaCorrientePaciente,
					dau.dau.dau_admision_fecha AS fechaAdmision,
					dau.dau.dau_categorizacion_fecha as fechaCategorizacion,
					dau.dau.dau_categorizacion as tipoCategorizacion,
					CONCAT(paciente.paciente.nombres,' ',paciente.paciente.apellidopat,' ',paciente.paciente.apellidomat) AS nombreCompletoPaciente,
					paciente.paciente.rut AS rutPaciente,
					dau.dau.dau_numero_atencion AS numeroAtencionDau,
					paciente.paciente.extranjero,
					paciente.paciente.rut_extranjero,
					paciente.paciente.fechanac,
					paciente.paciente.sexo,
                    religion.rlg_descripcion AS religion_descripcion
				FROM
					dau.dau
				INNER JOIN
						paciente.paciente FORCE INDEX (idx_idpaciente) ON dau.dau.id_paciente = paciente.paciente.id
                LEFT JOIN paciente.religion on paciente.religion.rlg_id = paciente.paciente.religion
				WHERE dau.dau.dau_id = '{$idDau}' ";

		$datos = $objCon->consultaSQL($sql,"Error al obtener info sobre numero de atención");

		return $datos[0];

	}



	function insertarPostIndicacionEgreso ( $objCon, $parametros ) {

		$sql = "INSERT INTO
					dau.dau_post_indicacion_egreso
					(
						idDau,
						tipoPostIndicacionEgreso,
						usuarioAplicacionEgreso,
						fechaAplicacionEgreso
					)
				VALUES
					(
						'{$parametros['idDau']}',
						'{$parametros['tipoPostIndicacionEgreso']}',
						'{$parametros['usuarioAplicacionEgreso']}',
						NOW()
					) ";

		$objCon->ejecutarSQL($sql, "Error al ingresar DAU a Post Indicación de Egreso");

	}



	function obtenerListadoMatronas ( $objCon ) {

		$sql = "SELECT
					acceso.usuario.idusuario,
					parametros_clinicos.profesional.PROdescripcion
				FROM
					acceso.usuario
				INNER JOIN
					parametros_clinicos.profesional ON parametros_clinicos.profesional.PROcodigo = acceso.usuario.rutusuario
				WHERE
					parametros_clinicos.profesional.TIPROcodigo = 3
				AND
					parametros_clinicos.profesional.PROat_urgencia = 'S'
				ORDER BY
					parametros_clinicos.profesional.PROdescripcion ASC
				";

		$datos = $objCon->consultaSQL($sql,"Error al obtener listado de matronas en urgencia");

		return $datos;

	}



	function actualizarMedicoInvolucradoGinecologia ( $objCon, $idDau ) {

		$sql = "UPDATE
					dau.dau
				SET
					dau.dau.dau_medico_involucrado_ginecologia = 'S'
				WHERE
					dau.dau.dau_id = '{$idDau}'
				";

		$objCon->ejecutarSQL($sql, "Error al actualizar médico involucrado en ginecología");

	}



	function dauPostIndicacionEgreso ( $objCon, $idDau ) {

		$sql = "SELECT
					dau.dau_post_indicacion_egreso.tipoPostIndicacionEgreso,
					dau.tipo_post_indicacion_egreso.descripcionPostIndicacionEgreso
				FROM
					dau.dau_post_indicacion_egreso
				INNER JOIN
					dau.tipo_post_indicacion_egreso ON dau.tipo_post_indicacion_egreso.idPostIndicacionEgreso = dau.dau_post_indicacion_egreso.tipoPostIndicacionEgreso
				WHERE
					dau.dau_post_indicacion_egreso.idDau = '{$idDau}'
				";

		$datos = $objCon->consultaSQL($sql,"Error al obtener listado de matronas en urgencia");

		return $datos[0];

	}


	function getEspecialidadesREG($objCon, $dau_id){
		$sql = "
			SELECT
			    rc.regId,
			    GROUP_CONCAT(
			        CASE 
			            WHEN se.SESPfuente = 'P' THEN 
			                CASE 
			                    WHEN se.SESPusuarioAplica IS NOT NULL AND se.SESPusuarioAplica != '' 
			                    THEN CONCAT(pc.ESPdescripcion, ' : ', se.SESPusuarioAplica)
			                    ELSE pc.ESPdescripcion
			                END
			            WHEN se.SESPfuente = 'O' THEN 
			                CASE 
			                    WHEN se.SESPusuarioAplica IS NOT NULL AND se.SESPusuarioAplica != '' 
			                    THEN CONCAT(oe.descripcion_otro, ' : ', se.SESPusuarioAplica)
			                    ELSE oe.descripcion_otro
			                END
			        END
			        SEPARATOR ', '
			    ) AS especialidades
			FROM rce.solicitud_especialista se
			INNER JOIN rce.registroclinico rc 
			    ON se.SESPidRCE = rc.regId
			LEFT JOIN parametros_clinicos.especialidad pc 
			    ON se.SESPfuente = 'P' 
			    AND se.SESPidEspecialidad = pc.ESPcodigo
			LEFT JOIN rce.otro_especialista oe 
			    ON se.SESPfuente = 'O' 
			    AND se.SESPidEspecialidad = oe.id_otro

			WHERE rc.dau_id = '{$dau_id}'
			GROUP BY rc.regId";
		$resultado = $objCon->consultaSQL($sql,"Error al buscar RCE del paciente");
		return $resultado;
	}

	function listarDAUEspecialidadGinecologica ( $objCon ) {

		$sql = "SELECT
					rce.solicitud_especialista.SESPfecha AS fechaSolicitud,
					rce.solicitud_especialista.SESPid AS idSolicitudEspecialista,
					dau.dau.dau_id AS idDAU,
					rce.registroclinico.regId AS idRCE,
					dau.dau.id_paciente AS idPaciente,
					dau.dau.dau_atencion AS tipoAtencion,
					CONCAT(paciente.paciente.nombres,' ',paciente.paciente.apellidopat,' ',paciente.paciente.apellidomat) AS nombrePaciente,
					paciente.paciente.transexual,
					paciente.paciente.nombreSocial,
					dau.dau.dau_paciente_edad AS edadPaciente,
					CASE
						WHEN dau.dau.dau_atencion = 1 THEN '(A)'
						WHEN dau.dau.dau_atencion = 2 THEN '(P)'
					END AS atencionPaciente,
					CASE
						WHEN dau.dau.dau_categorizacion = 'ESI-1' THEN 'C1'
						WHEN dau.dau.dau_categorizacion = 'ESI-2' THEN 'C2'
						WHEN dau.dau.dau_categorizacion = 'ESI-3' THEN 'C3'
						WHEN dau.dau.dau_categorizacion = 'ESI-4' THEN 'C4'
						WHEN dau.dau.dau_categorizacion = 'ESI-5' THEN 'C4'
					END AS categorizacionPaciente,
					dau.categorizacion.cat_nivel AS nivelCategorizacion,
                    religion.rlg_descripcion AS religion_descripcion
				FROM
					dau.dau
				INNER JOIN
					paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
				INNER JOIN
					rce.registroclinico ON dau.dau.dau_id = rce.registroclinico.dau_id
				INNER JOIN
					rce.solicitud_especialista ON rce.registroclinico.regId = rce.solicitud_especialista.SESPidRCE
				INNER JOIN
					dau.categorizacion ON dau.dau.dau_categorizacion = dau.categorizacion.cat_id
				LEFT JOIN paciente.religion on paciente.religion.rlg_id = paciente.paciente.religion
				WHERE
					dau.dau.dau_atencion IN (1, 2)
				AND
					dau.dau.est_id = 3
				AND
					rce.solicitud_especialista.SESPidEspecialidad = '07-301-0'
				AND
					rce.solicitud_especialista.SESPestado = 1
				ORDER BY
					dau.categorizacion.cat_nivel ASC, rce.solicitud_especialista.SESPfecha ASC
				";

		$datos = $objCon->consultaSQL($sql,"Error al obtener listado pacientes con solicitud especialidad ginecológica");

		return $datos;

	}



	function cumpleCondicionesParaDesplegarRecetaGES($objCon, $idDau) {
		$sql = "
			SELECT
				CASE
					WHEN
						dau.dau.dau_cierre_cie10 IN (
							'J040',
							'J041',
							'J042',
							'J050',
							'J051',
							'J060',
							'J100',
							'J101',
							'J108',
							'J110',
							'J111',
							'J118',
							'J120',
							'J121',
							'J122',
							'J128',
							'J129',
							'J13X',
							'J14X',
							'J150',
							'J151',
							'J152',
							'J153',
							'J154',
							'J155',
							'J156',
							'J156',
							'J157',
							'J158',
							'J159',
							'J160',
							'J168',
							'J180',
							'J181',
							'J188',
							'J189',
							'J200',
							'J201',
							'J202',
							'J203',
							'J204',
							'J205',
							'J206',
							'J207',
							'J208',
							'J209',
							'J210',
							'J218',
							'J219',
							'J22X',
							'J40X',
							'J211',
							'A370',
							'A371',
							'A378',
							'A379',
							'A492'
						)
						AND dau.dau.dau_paciente_edad >= 0
						AND dau.dau.dau_paciente_edad < 5
					THEN 'S'
					WHEN
						dau.dau.dau_cierre_cie10 IN (
							'J100',
							'J101',
							'J108',
							'J110',
							'J111',
							'J118',
							'J120',
							'J121',
							'J122',
							'J128',
							'J129',
							'J13X',
							'J14X',
							'J150',
							'J151',
							'J152',
							'J153',
							'J154',
							'J155',
							'J156',
							'J156',
							'J157',
							'J158',
							'J158',
							'J159',
							'J160',
							'J168',
							'J170',
							'J171',
							'J180',
							'J181',
							'J188',
							'J189'
						)
						AND dau.dau.dau_paciente_edad >= 65
					THEN 'S'
					ELSE 'N'
				END AS cumple
			FROM
				dau.dau
			WHERE
				dau.dau.dau_id = '{$idDau}'
		";

		return	$objCon->consultaSQL($sql,"Error al cumpleCondicionesParaDesplegarRecetaGES");
	}
	function getDauPacPorIdDau($objCon, $dau_id){

		$sql = "SELECT
					dau.dau_id,
					dau.id_paciente
				FROM
					dau.dau
				WHERE
					dau_id = {$dau_id}";

		$datos = $objCon->consultaSQL($sql,"Error al obtener getDauPacPorIdDau");

		return $datos;

	}



	function ingresarSeguimientoPaciente($objCon, $parametros) {
		require_once("Util.class.php");

		$objUtil = new Util();

		if (!$objUtil->existe($parametros)) {
			return;
		}

		$sql = "
			INSERT INTO
				dau.seguimiento_paciente(
					idDau,
					seguimientoPaciente,
					usuarioIngresoSeguimiento,
					fechaIngresoSeguimiento
				)
			VALUES(
				'{$parametros['idDau']}',
				'{$parametros['seguimientoPaciente']}',
				'{$parametros['usuarioIngresoSeguimiento']}',
				NOW()
			)
			ON DUPLICATE KEY UPDATE
				dau.seguimiento_paciente.seguimientoPaciente = '{$parametros['seguimientoPaciente']}',
				dau.seguimiento_paciente.usuarioIngresoSeguimiento = '{$parametros['usuarioIngresoSeguimiento']}',
				dau.seguimiento_paciente.fechaIngresoSeguimiento = NOW()
		";

		$objCon->ejecutarSQL($sql, "Error al ingresarSeguimientoPaciente");
	}



	function obtenerSeguimientoPacientes($objCon, $parametros) {
		require_once("Util.class.php");

		$objUtil = new Util();

		$condicion = "";

		$sql = "
			SELECT
				dau.seguimiento_paciente.idSeguimientoPaciente,
				dau.seguimiento_paciente.idDau,
				dau.seguimiento_paciente.fechaIngresoSeguimiento,
				CONCAT(
					paciente.paciente.nombres,
					' ',
					paciente.paciente.apellidopat,
					' ',
					paciente.paciente.apellidomat
				) AS nombre,
				paciente.paciente.rut AS run,
				paciente.paciente.rut_extranjero AS runExtranjero,
				CASE
					WHEN
						camas.transito_paciente.cta_cte IS NOT NULL
					THEN
						'En tránsito paciente'
					WHEN
						camas.camas.servicio IS NOT NULL
					THEN
						CONCAT(
							'Servicio: ',
							camas.camas.servicio,
							' - Sala: ',
							camas.camas.sala,
							' - Cama: ',
							camas.camas.cama
						)
					WHEN
						camas.camas.servicio IS NULL
						AND dau.dau.est_id <> 5
					THEN
						CONCAT(
							'Sercicio: Urgencias',
							' - Sala: ',
							dau.sala.sal_descripcion,
							' - Cama: ',
							dau.cama.cam_descripcion
						)
					ELSE
						'Sin Lugar Actual'
				END AS lugarActual,
				CONCAT(
					cie10.cie10.codigoCIE,
					' - ',
					cie10.cie10.nombreCIE
				) AS cie10
			FROM
				dau.seguimiento_paciente
			INNER JOIN
				dau.dau
				ON dau.seguimiento_paciente.idDau = dau.dau.dau_id
			INNER JOIN
				paciente.paciente
				ON dau.dau.id_paciente = paciente.paciente.id
			LEFT JOIN
				dau.cama
				ON dau.dau.dau_id = dau.cama.dau_id
			LEFT JOIN
				dau.sala
				ON dau.cama.sal_id = dau.sala.sal_id
			INNER JOIN
				cie10.cie10 force index for join (index1)
				ON dau.dau.dau_cierre_cie10 = cie10.cie10.codigoCIE
			LEFT JOIN
				camas.transito_paciente
				ON dau.dau.idctacte = camas.transito_paciente.cta_cte
			LEFT JOIN
				camas.camas
				ON dau.dau.idctacte = camas.camas.cta_cte
			WHERE
				dau.seguimiento_paciente.seguimientoPaciente = 'S'
		";

		if ($objUtil->existe($parametros["idDau"])) {
			$condicion .= " AND dau.seguimiento_paciente.idDau = '{$parametros['idDau']}' ";
		}

		if ($objUtil->existe($parametros["run"])) {
			$condicion .= " AND paciente.paciente.rut = '{$parametros['run']}' ";
		}

		if ($objUtil->existe($parametros["nombre"])) {
			$condicion .= "
				AND
					CONCAT(
						paciente.nombres,
						' ',
						paciente.apellidopat,
						' ',
						paciente.apellidomat
					) LIKE REPLACE('%{$parametros['nombre']}%',' ','%') ";
		}

		$condicion .= "
			ORDER BY
				dau.seguimiento_paciente.idSeguimientoPaciente ASC
		";

		$sql .= $condicion;

		return $objCon->consultaSQL($sql, "Error al obtenerSeguimientoPaciente");
	}



	function obtenerObservacionesSeguimientoPaciente($objCon, $parametros) {
		require_once("Util.class.php");

		$objUtil = new Util();

		if (!$objUtil->existe($parametros["idSeguimientoPaciente"])) {
			return array();
		}

		$sql = "
			SELECT
				dau.observacion_seguimiento_paciente.*
			FROM
				dau.observacion_seguimiento_paciente
			INNER JOIN
				dau.seguimiento_paciente
				ON dau.observacion_seguimiento_paciente.idSeguimientoPaciente = dau.seguimiento_paciente.idSeguimientoPaciente
			WHERE
				dau.observacion_seguimiento_paciente.idSeguimientoPaciente = '{$parametros['idSeguimientoPaciente']}'
			AND
				dau.seguimiento_paciente.seguimientoPaciente = 'S'
		";

		return $objCon->consultaSQL($sql, "Error al obtenerObservacionesSeguimientoPaciente");
	}



	function ingresarObservacionSeguimientoPaciente($objCon, $parametros) {
		require_once("Util.class.php");

		$objUtil = new Util();

		if (!$objUtil->existe($parametros)) {
			return;
		}

		$sql = "
			INSERT INTO
				dau.observacion_seguimiento_paciente(
					idSeguimientoPaciente,
					fechaObservacion,
					usuarioObservacion,
					observacion
				)
			VALUES(
				'{$parametros['idSeguimientoPaciente']}',
				NOW(),
				'{$parametros['usuarioObservacion']}',
				'{$parametros['observacion']}'
			)
		";

		$objCon->ejecutarSQL($sql, "Error al ingresarObservacionSeguimientoPaciente");
	}



	function dejarSeguimientoPaciente($objCon, $parametros) {
		require_once("Util.class.php");

		$objUtil = new Util();

		if (!$objUtil->existe($parametros["usuarioDejarSeguimiento"])) {
			return;
		}

		$realizarConsulta = false;

		$condicion = "";

		$sql = "
			UPDATE
				dau.seguimiento_paciente
			SET
				dau.seguimiento_paciente.seguimientoPaciente = 'N',
				dau.seguimiento_paciente.usuarioDejarSeguimiento = '{$parametros['usuarioDejarSeguimiento']}',
				dau.seguimiento_paciente.fechaDejarSeguimiento = NOW()
		";

		if ($objUtil->existe($parametros["idSeguimientoPaciente"])) {
			$realizarConsulta = true;

			$condicion .= (!$objUtil->existe($condicion)) ? " WHERE " : " AND ";
			$condicion .= " dau.seguimiento_paciente.idSeguimientoPaciente = '{$parametros['idSeguimientoPaciente']}' ";
		}

		if ($objUtil->existe($parametros["idDau"])) {
			$realizarConsulta = true;

			$condicion .= (!$objUtil->existe($condicion)) ? " WHERE " : " AND ";
			$condicion .= " dau.seguimiento_paciente.idDau = '{$parametros['idDau']}' ";
		}

		if (!$realizarConsulta) {
			return;
		}

		$sql .= $condicion;

		$objCon->ejecutarSQL($sql, "Error al dejarSeguimientoPaciente");
	}



	function eliminarSeguimientoPaciente($objCon, $parametros) {
		require_once("Util.class.php");

		$objUtil = new Util();

		$realizarConsulta = false;

		$condicion = "";

		$sql = "
			DELETE FROM
				dau.seguimiento_paciente
		";

		if ($objUtil->existe($parametros["idSeguimientoPaciente"])) {
			$realizarConsulta = true;

			$condicion .= (!$objUtil->existe($condicion)) ? " WHERE " : " AND ";
			$condicion .= " dau.seguimiento_paciente.idSeguimientoPaciente = '{$parametros['idSeguimientoPaciente']}' ";
		}

		if ($objUtil->existe($parametros["idDau"])) {
			$realizarConsulta = true;

			$condicion .= (!$objUtil->existe($condicion)) ? " WHERE " : " AND ";
			$condicion .= " dau.seguimiento_paciente.idDau = '{$parametros['idDau']}' ";
		}

		if (!$realizarConsulta) {
			return;
		}

		$sql .= $condicion;

		$objCon->ejecutarSQL($sql, "Error al eliminarSeguimientoPaciente");
	}

}
?>
