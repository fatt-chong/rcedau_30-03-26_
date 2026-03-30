<?php 
class Usuarios{
	function SelectPacienteLis($objCon , $parametros){
		$sql = "SELECT
			dau.dau.id_paciente,
			dau.dau.idctacte,
			dau.cama.cam_id,
			dau.cama.sal_id,
			paciente.paciente.*,
			dau.dau.dau_id,
			dau.sala.sal_descripcion, 
			dau.cama.cam_descripcion,
			paciente.nacionalidadavis.id_pais_hosp,
			paciente.nacionalidadavis.NACpais,
			paciente.comuna.comuna,
			paciente.prevision.homologacion_ingrad
		FROM
			dau.dau
			INNER JOIN dau.cama ON dau.dau.dau_id = dau.cama.dau_id
			INNER JOIN dau.sala ON dau.cama.sal_id = dau.sala.sal_id
			INNER JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
			LEFT JOIN paciente.nacionalidadavis ON paciente.paciente.paisNacimiento = paciente.nacionalidadavis.NACcodigo 
			LEFT JOIN paciente.comuna ON paciente.paciente.idcomuna = paciente.comuna.id
			LEFT JOIN paciente.prevision ON paciente.paciente.prevision = paciente.prevision.id
		WHERE
			dau.dau_id = '{$parametros['dau_id']}' ";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar los Datos del Paciente");
		return $datos;
	}
	function SelectParametrosClinicos ( $objCon, $parametros ) {
		$condicion = "";
        $sql = "
			SELECT
			parametros_clinicos.profesional.PROcodigo, 
			parametros_clinicos.profesional.PROdescripcion, 
			parametros_clinicos.profesional.PROapellidopat, 
			parametros_clinicos.profesional.PROapellidomat, 
			parametros_clinicos.profesional.PROnombres, 
			acceso.usuario.idusuario, 
			acceso.usuario.rutusuario, 
			parametros_clinicos.profesional.PROid_medico_camas, 
			parametros_clinicos.profesional.TIPROcodigo, 
			parametros_clinicos.profesional.TIPROdescripcion, 
			parametros_clinicos.profesional.ESTAcodigo
		FROM
			parametros_clinicos.profesional
			INNER JOIN
			acceso.usuario
			ON 
		parametros_clinicos.profesional.PROcodigo = acceso.usuario.rutusuario
		WHERE usuario.idusuario = '{$parametros['usuario']}'";



		$datos = $objCon->consultaSQL($sql,"Error al obtener los datos del usuario");
	 	return $datos;
    }

    function obtenerDatosUsuario ( $objCon, $usuario ) {

       $sql = "SELECT
                    parametros_clinicos.profesional.PROdescripcion,
                    acceso.usuario.idusuario,
                    parametros_clinicos.profesional.PROcodigo,
                    acceso.usuario.usu_barcode_key,
                    parametros_clinicos.profesional.TIPROcodigo
                FROM
                    parametros_clinicos.profesional
                INNER JOIN
                    acceso.usuario ON acceso.usuario.rutusuario = parametros_clinicos.profesional.PROcodigo
                WHERE
                    acceso.usuario.idusuario = '{$usuario}' ";
		$datos = $objCon->consultaSQL($sql,"Error al obtener los datos del usuario");
	 	return $datos;
    }
    function obtenerDatosUsuario_MEDICO_TRATANTE ( $objCon, $parametros ) {

        $sql = "SELECT
                    LOWER(parametros_clinicos.profesional.PROdescripcion) as PROdescripcion ,
                    LOWER(acceso.usuario.idusuario) as idusuario,
                    parametros_clinicos.profesional.PROcodigo
                FROM
                    parametros_clinicos.profesional
                INNER JOIN
                    acceso.usuario ON acceso.usuario.rutusuario = parametros_clinicos.profesional.PROcodigo
                WHERE
                    parametros_clinicos.profesional.PROcodigo = '{$parametros['rutRecibeTurno']}'  ";
        $datos = $objCon->consultaSQL($sql,"Error al obtener los datos del usuario");
        return $datos;
    }
     function obtenerDatosUsuarioTecnicoENF ( $objCon, $usuario ) {
        $sql = "SELECT
                usuario.idusuario,
                usuario.nombreusuario,
                usuario.rutusuario,
                acceso.usuario.usu_barcode_key
                FROM acceso.usuario
                WHERE
                    acceso.usuario.idusuario = '{$usuario}'  ";
        $datos = $objCon->consultaSQL($sql,"Error al obtener los datos del usuario");
        return $datos;
    }
	function servicioUsuario($objCon,$parametros){
		$sql="SELECT
		acceso.servicio.idservicio,
		acceso.servicio.nombre,
		acceso.servicio.id
		FROM
		acceso.usuario_has_servicio

		LEFT JOIN acceso.servicio ON acceso.usuario_has_servicio.idservicio = acceso.servicio.idservicio
		WHERE
		acceso.usuario_has_servicio.idusuario = '{$parametros['usuario']}' 
		AND acceso.servicio.id = '{$parametros['servicio']}'";
		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR servicioUsuario<br>");
		return $datos;
	}

	function get_datos_usuario($objCon,$parametros){
		$sql="SELECT
		usuario.idusuario,
		usuario.nombreusuario,
		usuario.rutusuario
		FROM acceso.usuario
		WHERE usuario.idusuario = '{$parametros['usuario']}'";
		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR get_datos_usuario<br>");
		return $datos;
	}




	function obtenerCirujanos($objCon){
	/*
		METODO QUE SIRVE PARA OBTENER A LOS CIRUJANOS
	*/
		$objCon->setDB(BD_CONNECTION_PC);
		$sql="SELECT PROcodigo,PROdescripcion
			FROM profesional
			WHERE PROactivo = 'S'";
		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR obtenerCirujanos<br>");
		return $datos;
	}
	function obtenerCirujanosFiltro($objCon){
		$objCon->setDB(BD_CONNECTION_PC);
		$sql="SELECT
			profesional.PROid_medico_camas,
			profesional.PROdescripcion,
			profesional.PROcodigo
			FROM
			profesional_has_especialidad
			INNER JOIN profesional ON profesional_has_especialidad.PROcodigo = profesional.PROcodigo
			WHERE	profesional_has_especialidad.ESPcodigo in ('07-301-0','07-300-1')";
		// $sql="SELECT PROcodigo,PROdescripcion
		// 	FROM profesional
		// 	WHERE PROactivo = 'S' AND TIPROcodigo IN(1,6,15)";
		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR obtenerCirujanosFiltro<br>");
		return $datos;
	}
	function obtenerCirujanosMatrona($objCon){
		$objCon->setDB(BD_CONNECTION_PC);
		$sql="SELECT PROcodigo,PROdescripcion
			FROM profesional
			WHERE PROactivo = 'S' AND TIPROcodigo IN(1,6,15,3)";
		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR obtenerCirujanosMatrona<br>");
		return $datos;
	}
	function obtenerMatronas($objCon){
		$objCon->setDB(BD_CONNECTION_PC);
		$sql="SELECT PROcodigo,PROdescripcion
			FROM profesional
			WHERE PROactivo = 'S' AND TIPROcodigo IN(3)";
		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR obtenerMatronas<br>");
		return $datos;
	}
	function obtenerMedicos($objCon){

		$sql="SELECT
		medicos.id,
		medicos.medico,
		medicos.rut,
		medicos.especialidad,
		medicos.servicio_idservicio,
		medicos.emailmedico,
		medicos.telefonomedico,
		medicos.at_abierta,
		medicos.at_cerrada,
		medicos.at_urgencia,
		medicos.upc_turno
		FROM camas.medicos";
		

		// $objCon->setDB(BD_CONNECTION_PC);
		// $sql="SELECT
		// 	profesional.PROcodigo,
		// 	profesional.PROid_medico_camas,
		// 	profesional.PROdescripcion,
		// 	profesional.PROapellidopat,
		// 	profesional.PROapellidomat,
		// 	profesional.PROnombres,
		// 	profesional.TIPROcodigo,
		// 	profesional.TIPROdescripcion,
		// 	profesional.ESTAcodigo,
		// 	profesional.PROactivo,
		// 	profesional.PROat_abierta,
		// 	profesional.PROat_cerrada,
		// 	profesional.PROat_urgencia,
		// 	profesional.PROupc_turno,
		// 	profesional.PROrce_unacess,
		// 	profesional.PROpabellon
		// 	FROM profesional
		// 	WHERE TIPROcodigo IN(1,6,15) ";
		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR obtenerMedicos<br>");
		return $datos;
	}


	function get_medico_autorizaRCE($objCon,$parametros){
		$sql="SELECT
		oncologia.indicacion.id_indicacion,
		oncologia.indicacion.regId,
		rce.registroclinico.PROcodigo,
		acceso.usuario.idusuario,
		acceso.usuario.nombreusuario
		FROM
		oncologia.indicacion
		INNER JOIN rce.registroclinico ON oncologia.indicacion.regId = rce.registroclinico.regId
		INNER JOIN acceso.usuario ON rce.registroclinico.PROcodigo = acceso.usuario.idusuario
		WHERE oncologia.indicacion.id_indicacion = '{$parametros['id_indicacion']}' ";
		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR obtenerMedicos<br>");
		return $datos;
	}
}