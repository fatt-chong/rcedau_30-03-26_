<?php
class Imagenologia{

	function getTipoExamen($objCon){
		$sql="SELECT EXTIcod,EXTInombre FROM rayos.tipoexamen";
		$datos = $objCon->consultaSQL($sql,"Error al listar getPrioridad");
	 	return $datos;
	}



	function getExamenes($objCon,$tipo,$parametros){
		$sql="SELECT preCod,preNombre,preIMAGEClasi
			  FROM paciente.prestacion
			  WHERE  prestacion.preGrupo = '04' AND prestacion.preIMAGEclasi='$tipo' AND (prestacion.preCod LIKE '%{$parametros}%' OR prestacion.preNombre LIKE '%$parametros%' )";
		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LEER getExamenes<br>");
		$return_arr = array();
		for($i=0; $i<count($datos); $i++) {
			$row_array['id']            = $datos[$i]['preCod'];
			$row_array['descripcion'] 	= $datos[$i]['preNombre'];
			$row_array['value']         = "{$datos[$i]['preNombre']}";
			array_push($return_arr,$row_array);
		}
		return json_encode($return_arr);
	}

	 function getTipoExamen2($objCon){
        $sql="SELECT
		prestaciones_imagenologia.id_prestaciones,
		prestaciones_imagenologia.examen,
		prestaciones_imagenologia.tipo_examen,
		prestaciones_imagenologia.contraste,
		prestaciones_imagenologia.Lateralidad,
		prestaciones_imagenologia.Segmento
		/*
		prestaciones_imagenologia.plano,
		prestaciones_imagenologia.extremidad
		*/
		FROM le.prestaciones_imagenologia GROUP BY prestaciones_imagenologia.tipo_examen";
        $datos = $objCon->consultaSQL($sql,"Error al listar getPrioridad");
        return $datos;
    }
    function buscar_examenes($objCon,$term,$tipo_examen){
		// $objCon->db_select("le");
		$sql = "SELECT
		prestaciones_imagenologia.id_prestaciones,
		prestaciones_imagenologia.examen,
		prestaciones_imagenologia.tipo_examen,
		prestaciones_imagenologia.contraste,
		prestaciones_imagenologia.Lateralidad,
		prestaciones_imagenologia.Segmento
		/*
		prestaciones_imagenologia.plano,
		prestaciones_imagenologia.extremidad
		*/
		FROM le.prestaciones_imagenologia
		WHERE
		prestaciones_imagenologia.examen LIKE '%$term%'";
		if($tipo_examen != ""){
			$sql .= " AND prestaciones_imagenologia.tipo_examen = '{$tipo_examen}'" ;
		}

		$sql .= " AND prestaciones_imagenologia.estado = 'A'" ;

		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LEER DIAGNOSTICOS<br>");
		$return_arr = array();
		for($j=0; $j<count($datos); $j++) {
			$row_array['id_prestaciones'] = $datos[$j]['id_prestaciones'];
			$row_array['examen']          = $datos[$j]['examen'];
			$row_array['tipo_examen']     = $datos[$j]['tipo_examen'];
			$row_array['contraste']       = $datos[$j]['contraste'];
			$row_array['Lateralidad']     = $datos[$j]['Lateralidad'];
			$row_array['Segmento']        = $datos[$j]['Segmento'];

			/*
			$row_array['extremidad']      = $datos[$j]['extremidad'];
			$row_array['plano']           = $datos[$j]['plano'];
			*/
			$row_array['value']           = "{$datos[$j]['tipo_examen']} - {$datos[$j]['examen']}";
			array_push($return_arr,$row_array);
		}
		return json_encode($return_arr);
	}
    function listaPlano($objCon){
		$sql = "SELECT
		plano.planoID,
		plano.planoNombre
		FROM le.plano
		WHERE planoID IN (1,2,6)";
		$resultado = $objCon->consultaSQL($sql,"<br>ERROR AL BUSCAR pareo en pabellon<br>");
		return $resultado;
	}

	function listaExtremidad($objCon){
		$sql = "SELECT * FROM le.extremidad";
		$resultado = $objCon->consultaSQL($sql,"<br>ERROR AL BUSCAR pareo en pabellon<br>");
		return $resultado;
	}

	function getExamenes2($objCon,$tipo){
		$sql="SELECT preCod,CONCAT(preCod,' - ',preNombre) as preNombre,preIMAGEClasi,prePacienteUrgencia
			  FROM paciente.prestacion
			  WHERE  prestacion.preGrupo = '04' AND prestacion.preIMAGEclasi='$tipo'";
		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LEER getExamenes2<br>");
		return $datos;
	}



	function getExamenes3($objCon,$tipo){
		$sql="SELECT preCod,CONCAT(preCod,' - ',preNombre) as preNombre,preIMAGEClasi,prePacienteUrgencia
			  FROM paciente.prestacion
			  WHERE  prestacion.preGrupo = '04' AND prestacion.preIMAGEclasi='$tipo' AND prestacion.prePacienteUrgencia = 'S'";
		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LEER getExamenes3<br>");
		return $datos;
	}



	function insertarSolicitudRayo($objCon,$parametros,$subparametros){
		$sql="INSERT INTO rayos.solicitud_imagen_camas(
		SIC_fecha,
		SIC_idpac,
		SIC_ctacte,
		SIC_edad,
		SIC_sexo,
		SIC_crprocedencia,
		SIC_contraste,
		SIC_diagnostico,
		SIC_sintomas_pricipales,
		SIC_antecedentes_quir,
		SIC_embarazo,
		SIC_asma,
		SIC_hipertencion,
		SIC_diabetes,
		SIC_multires,
		SIC_otros,
		SIC_informado,
		SIC_clearence_creatina,
		SIC_proteccionrenal,
		SIC_premedicacion,
		SIC_medico_solicitante,
		SIC_procedencia_solicitud,
		SIC_RAU,
		SIC_tipo_examen,
		SIC_cod_prestacion,
		SIC_aplicado,
		SIC_RCE_sol_ima_id,
		SIC_RCE_observacion,
		id_solicitud_cabecera_registro,
		id_estado,
		ID
		)VALUES(
		NOW(),
		'{$parametros['id_paciente']}',
		'{$parametros['ctacte_paciente']}',
		'{$parametros['edad_paciente']}',
		'{$parametros['sexo_paciente']}',
		'{$parametros['procedencia']}',
		'{$parametros['frm_examen_contraste']}',
		'{$parametros['frm_diagnostico']}',
		'{$parametros['frm_sintomasp']}',
		'{$parametros['frm_antecedentes']}',
		'{$parametros['frm_Embarazo']}',
		'{$parametros['frm_Asma']}',
		'{$parametros['frm_Hipertension']}',
		'{$parametros['frm_Diabetes']}',
		'{$parametros['frm_multirresistente']}',
		'{$parametros['frm_otros_text']}',
		'{$parametros['frm_conocimiento']}',
		'{$parametros['frm_crearence']}',
		'{$parametros['frm_proteccion_renal']}',
		'{$parametros['frm_premedicacion']}',
		'{$parametros['usuarioInserta']}',
		'{$parametros['procedencia_cod']}',
		'{$parametros['dau_id']}',
		'{$subparametros['ima_tipo']}',
		'{$subparametros['codigoPrestacionIntegracion']}',
		'{$parametros['SIC_no_aplicado']}',
		'{$subparametros['sol_ind_id']}',
		'{$subparametros['observacion']}',
		'{$subparametros['id_solicitud_cabecera_registro']}',
		2,
		'{$subparametros['idPrestacionIntegracion']}')";
		$response = $objCon->ejecutarSQL($sql, "ERROR AL insertarSolicitudRayo");
		return $objCon->lastInsertId();
	}



	function insertarSolicitudImagenologia($objCon,$parametros){
		$sql="
			INSERT INTO
				rce.solicitud_imagenologia(
					regId,
					sol_ima_estado,
					sol_ima_tipo,
					sol_ima_usuarioInserta,
					sol_ima_fechaInserta,
					infeccionOColonizacionMultirresistente,
					asma,
					embarazo,
					hipertension,
					diabetes,
					otro,
					id_cabecera_indicaciones,
					otrosTexto
				)
			VALUES(
				{$parametros['rce_id']},
				'{$parametros['estado_indicacion']}',
				'{$parametros['servicioImagenologia']}',
				'{$parametros['dau_mov_usuario']}',
				NOW(),
				'{$parametros['infeccionOColonizacionMultirresistente']}',
				'{$parametros['asma']}',
				'{$parametros['embarazo']}',
				'{$parametros['hipertension']}',
				'{$parametros['diabetes']}',
				'{$parametros['otro']}',
				'{$parametros['id_cabecera_indicaciones']}',
				'{$parametros['otrosTexto']}'
			)
		";

		$response = $objCon->ejecutarSQL($sql, "Error al insertarNuevaIndicacion imagen");
		$ima_id = $objCon->lastInsertId();
		return $ima_id;
	}



	function insertarDetalleIndicacionImagenologia($objCon,$parametros){
		$sql="INSERT INTO rce.detalle_solicitud_imagenologia
		(sol_ima_id,
		det_ima_estado,
		det_ima_tipo_examen,
		det_ima_codigo,
		det_ima_descripcion,
		det_ima_observacion
		)VALUES(
		{$parametros['sol_ind_id']},
		{$parametros['est_id']},
		'{$parametros['ima_tipo']}',
		'{$parametros['codigo']}',
		'{$parametros['descripcion']}',
		'{$parametros['observacion']}')";
		$response = $objCon->ejecutarSQL($sql, "Error al insertarDetalleIndicacion");
		return $objCon->lastInsertId();
	}



	function editarEstadoRayo($objCon,$parametros){
		if (is_null($parametros["id_sic"]) || empty($parametros["id_sic"])) {
			return;
		}

		$condicion = "";

		$sql=" UPDATE rayos.solicitud_imagen_camas";

		if(isset($parametros['SIC_aplicado'])){
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" SIC_aplicado = '{$parametros['SIC_aplicado']}'";
		}

		$sql .= $condicion." WHERE SIC_id = {$parametros['id_sic']}";
		$resultado = $objCon->ejecutarSQL($sql, "Error al editarEstadoRayo");
	}



	function editarCabeceraImagenologia($objCon,$parametros){
		$condicion = "";
		$sql="UPDATE rce.solicitud_imagenologia";
		if(isset($parametros['estado_indicacion'])){
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" rce.solicitud_imagenologia.sol_ima_estado = '{$parametros['estado_indicacion']}'";
		}
		if(isset($parametros['observacion_aplica'])){
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" rce.solicitud_imagenologia.sol_ima_obsAplica = '{$parametros['observacion_aplica']}'";
		}
		if(isset($parametros['usuario_Aplica'])){
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" rce.solicitud_imagenologia.sol_ima_usuarioAplica = '{$parametros['usuario_Aplica']}', sol_ima_fechaAplica= NOW()";
		}
		if(isset($parametros['observacion_detalle'])){
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" rce.solicitud_imagenologia.sol_ima_obs_Anula = '{$parametros['observacion_detalle']}'";
		}
		if(isset($parametros['usuarioAnula'])){
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" rce.solicitud_imagenologia.sol_ima_usuarioAnula = '{$parametros['usuarioAnula']}', sol_ima_fechaAnula = NOW()";
		}
		if(isset($parametros['observacion_elimina'])){
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" rce.solicitud_imagenologia.sol_ima_obsElimina = '{$parametros['observacion_elimina']}'";
		}
		if(isset($parametros['usuario_Elimina'])){
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" rce.solicitud_imagenologia.sol_ima_usuarioElimina = '{$parametros['usuario_Elimina']}', sol_ima_fechaElimina = NOW()";
		}
		$sql .= $condicion." WHERE rce.solicitud_imagenologia.sol_ima_id = '{$parametros['solicitud_id']}'";
		$resultado = $objCon->ejecutarSQL($sql, "Error al editarCabeceraImagenologia");
	}



	function editarDetalleCabecera($objCon,$parametros){
		if (is_null($parametros["solicitud_id"]) || empty($parametros["solicitud_id"])) {
			return;
		}

		if (is_null($parametros["id_sic"]) || empty($parametros["id_sic"])) {
			return;
		}

		$condicion = "";
		$sql="UPDATE rce.detalle_solicitud_imagenologia";
		if(isset($parametros['estado_indicacion'])){
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" det_ima_estado = '{$parametros['estado_indicacion']}'";
		}

		$sql .= $condicion." WHERE sol_ima_id = {$parametros['solicitud_id']} AND SIC_id = {$parametros['id_sic']}";
		$resultado = $objCon->ejecutarSQL($sql, "Error al editarDetalleFiltroCabecera");
	}



	function editarEstadoDetalleSolicitudImagenologiaDalca($objCon,$parametros){
		$sql = "
			UPDATE
				rce.detalle_solicitud_imagenologia_dalca
			SET
				rce.detalle_solicitud_imagenologia_dalca.idEstadoDetalleSolicitud = '{$parametros['estado_indicacion']}'
			WHERE
				rce.detalle_solicitud_imagenologia_dalca.idSolicitudImagenologia = {$parametros['solicitud_id']}
		";

		$objCon->ejecutarSQL($sql, "Error al editarEstadoDetalleSolicitudImagenologiaDalca");
	}




	function editarDetalleCabecera_new($objCon,$parametros){
		$condicion = "";
		$sql="UPDATE rce.detalle_solicitud_imagenologia";
		if(isset($parametros['estado_indicacion'])){
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" det_ima_estado = ' {$parametros['estado_indicacion']}'";
		}
		if(isset($parametros['sic_id'])){
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" SIC_id = ' {$parametros['sic_id']}'";
		}
		$sql .= $condicion." WHERE det_ima_id = '{$parametros['solicitud_id']}'";
		$resultado = $objCon->ejecutarSQL($sql, "Error al editarDetalleFiltroCabecera");
	}



	function listarIndicacionesImagenologia($objCon,$parametros){
    require_once($_SERVER["DOCUMENT_ROOT"] . "/RCEDAU/config/config.php");

		$sql="
			SELECT
				IF(
					rce.solicitud_imagenologia.sol_ima_fechaInserta >= '".FECHA_INTEGRACION_DALCA."',
					rce.detalle_solicitud_imagenologia_dalca.idDetalleSolicitudImagenologiaDalca,
					rce.detalle_solicitud_imagenologia.det_ima_id
				) AS det_ima_id,
				IF(
					rce.solicitud_imagenologia.sol_ima_fechaInserta >= '".FECHA_INTEGRACION_DALCA."',
					rce.detalle_solicitud_imagenologia_dalca.idEstadoDetalleSolicitud,
					rce.detalle_solicitud_imagenologia.det_ima_estado
				) AS det_ima_estado,
				IF(
					rce.solicitud_imagenologia.sol_ima_fechaInserta >= '".FECHA_INTEGRACION_DALCA."',
					le.prestaciones_imagenologia.tipo_examen,
					rce.detalle_solicitud_imagenologia.det_ima_tipo_examen
				) AS det_ima_tipo_examen,
				IF(
					rce.solicitud_imagenologia.sol_ima_fechaInserta >= '".FECHA_INTEGRACION_DALCA."',
					le.prestaciones_imagenologia.id_prestaciones,
					rce.detalle_solicitud_imagenologia.det_ima_codigo
				) AS det_ima_codigo,
				IF(
					rce.solicitud_imagenologia.sol_ima_fechaInserta >= '".FECHA_INTEGRACION_DALCA."',
					le.prestaciones_imagenologia.examen,
					rce.detalle_solicitud_imagenologia.det_ima_descripcion
				) AS descripcion,
				rce.solicitud_imagenologia.sol_ima_usuarioInserta AS usuarioAplica,
				rce.solicitud_imagenologia.sol_ima_fechaInserta AS fechaAplica,
				rce.solicitud_imagenologia.sol_ima_obsAplica AS observacion,
				rce.solicitud_imagenologia.sol_ima_usuarioAplica AS usuarioAplicaDetalle,
				rce.solicitud_imagenologia.sol_ima_fechaAplica AS fechaAplicaDetalle,
				rce.detalle_solicitud_imagenologia.SIC_id,
				rce.detalle_solicitud_imagenologia_dalca.idSolicitudDalca,
				rce.tipo_indicaciones.ser_descripcion,
				IF(
					rce.solicitud_imagenologia.sol_ima_fechaInserta >= '".FECHA_INTEGRACION_DALCA."',
					estadoindicaciondalca.est_descripcion,
					estadoIndicacion.est_descripcion
				) AS est_descripcion,
				rce.solicitud_imagenologia.sol_ima_estado AS estadoCabecera,
				rce.solicitud_imagenologia.sol_ima_tipo AS Tipo,
				rce.solicitud_imagenologia.regId
			FROM
				rce.solicitud_imagenologia
			LEFT JOIN
				rce.detalle_solicitud_imagenologia
				ON  rce.solicitud_imagenologia.sol_ima_id = rce.detalle_solicitud_imagenologia.sol_ima_id
			LEFT JOIN
				rce.tipo_indicaciones
				ON rce.tipo_indicaciones.ser_codigo = rce.solicitud_imagenologia.sol_ima_tipo
			LEFT JOIN
				rce.estado_indicacion estadoIndicacion
				ON rce.detalle_solicitud_imagenologia.det_ima_estado = estadoIndicacion.est_id
			LEFT JOIN
				rce.detalle_solicitud_imagenologia_dalca
				ON rce.solicitud_imagenologia.sol_ima_id = rce.detalle_solicitud_imagenologia_dalca.idSolicitudImagenologia
			LEFT JOIN
				le.prestaciones_imagenologia
				ON rce.detalle_solicitud_imagenologia_dalca.idPrestacionImagenologia = le.prestaciones_imagenologia.id_prestaciones
			LEFT JOIN
				rce.estado_indicacion estadoIndicacionDalca
				ON rce.detalle_solicitud_imagenologia_dalca.idEstadoDetalleSolicitud = estadoIndicacionDalca.est_id
			WHERE
				rce.solicitud_imagenologia.sol_ima_id = {$parametros['solicitud_id']}
		";

		$resultado = $objCon->consultaSQL($sql,"Error al listarIndicacionesImagenologia");
		return $resultado;
	}



	function datosDauRce($objCon,$parametros){
		$sql="SELECT
			solicitud_imagenologia.sol_ima_id,
			solicitud_imagenologia.regId,
			solicitud_imagenologia.sol_ima_tipo,
			solicitud_imagenologia.sol_ima_estado,
			solicitud_imagenologia.sol_ima_usuarioInserta,
			solicitud_imagenologia.sol_ima_fechaInserta,
			registroclinico.dau_id
			FROM
			rce.solicitud_imagenologia
			INNER JOIN rce.registroclinico ON solicitud_imagenologia.regId = registroclinico.regId
			WHERE solicitud_imagenologia.sol_ima_id = {$parametros['solicitud_id']}";
		$resultado = $objCon->consultaSQL($sql,"Error al datosDauRce");
		return $resultado;
	}



	function pdfImagenologia($objCon,$parametros){
		$sql="SELECT
				rayos.solicitud_imagen_camas.SIC_fecha,
				rayos.solicitud_imagen_camas.SIC_idpac,
				rayos.solicitud_imagen_camas.SIC_ctacte,
				rayos.solicitud_imagen_camas.SIC_RAU,
				rayos.solicitud_imagen_camas.SIC_edad,
				rayos.solicitud_imagen_camas.SIC_sexo,
				rayos.solicitud_imagen_camas.SIC_crprocedencia,
				rayos.solicitud_imagen_camas.SIC_sala,
				rayos.solicitud_imagen_camas.SIC_cama,
				rayos.solicitud_imagen_camas.SIC_contraste,
				rayos.solicitud_imagen_camas.SIC_diagnostico,
				rayos.solicitud_imagen_camas.SIC_sintomas_pricipales,
				rayos.solicitud_imagen_camas.SIC_antecedentes_quir,
				rayos.solicitud_imagen_camas.SIC_embarazo,
				rayos.solicitud_imagen_camas.SIC_asma,
				rayos.solicitud_imagen_camas.SIC_hipertencion,
				rayos.solicitud_imagen_camas.SIC_diabetes,
				rayos.solicitud_imagen_camas.SIC_multires,
				rayos.solicitud_imagen_camas.SIC_otros,
				rayos.solicitud_imagen_camas.SIC_informado,
				rayos.solicitud_imagen_camas.SIC_clearence_creatina,
				rayos.solicitud_imagen_camas.SIC_proteccionrenal,
				rayos.solicitud_imagen_camas.SIC_premedicacion,
				rayos.solicitud_imagen_camas.SIC_medico_solicitante,
				rayos.solicitud_imagen_camas.SIC_procedencia_solicitud,
				rayos.solicitud_imagen_camas.SIC_tipo_examen,
				rayos.solicitud_imagen_camas.SIC_cod_prestacion,
				rayos.solicitud_imagen_camas.SIC_RCE_observacion,
				paciente.paciente.rut,
				paciente.paciente.nroficha,
				paciente.paciente.fechanac,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,
				paciente.paciente.sexo,
				paciente.paciente.direccion,
				paciente.prevision.prevision
			FROM
				rayos.solicitud_imagen_camas
				INNER JOIN paciente.paciente ON rayos.solicitud_imagen_camas.SIC_idpac = paciente.paciente.id
				INNER JOIN paciente.prevision ON paciente.prevision = prevision.id
			WHERE
				rayos.solicitud_imagen_camas.SIC_id = {$parametros['idSIC']} ";
		$resultado = $objCon->consultaSQL($sql,"Error al datosDauRce");
		return $resultado;
	}



	function listadoImagenologia($objCon,$parametros){
		$sql="SELECT
				rce.detalle_solicitud_imagenologia.det_ima_estado,
				rce.detalle_solicitud_imagenologia.det_ima_tipo_examen,
				rce.detalle_solicitud_imagenologia.det_ima_codigo,
				rce.detalle_solicitud_imagenologia.det_ima_descripcion,
				rce.detalle_solicitud_imagenologia.SIC_id,
				rce.detalle_solicitud_imagenologia.det_ima_id,
				rce.detalle_solicitud_imagenologia.sol_ima_id,
				parametros_clinicos.profesional.PROdescripcion,
				parametros_clinicos.profesional.PROcodigo,
				rce.solicitud_imagenologia.sol_ima_fechaAplica ,
				rce.solicitud_imagenologia.sol_ima_fechaInserta
			FROM
				rce.solicitud_imagenologia
				INNER JOIN rce.detalle_solicitud_imagenologia ON rce.solicitud_imagenologia.sol_ima_id = rce.detalle_solicitud_imagenologia.sol_ima_id
				INNER JOIN acceso.usuario ON rce.solicitud_imagenologia.sol_ima_usuarioInserta = acceso.usuario.idusuario
				INNER JOIN parametros_clinicos.profesional ON acceso.usuario.rutusuario = parametros_clinicos.profesional.PROcodigo
			WHERE
				detalle_solicitud_imagenologia.sol_ima_id = {$parametros['solicitud_id']}";
		$resultado = $objCon->consultaSQL($sql,"Error al datosDauRce");
		return $resultado;
	}



	function movimientosIndicaciones($objCon,$parametros){
		$sql="SELECT
			dau_movimiento_rce.dau_mov_rce_id,
			dau_movimiento_rce.dau_id,
			dau_movimiento_rce.rce_id,
			dau_movimiento_rce.rce_sol_id,
			dau_movimiento_rce.sol_ind_id,
			dau_movimiento_rce.sol_tipo_id,
			dau_movimiento_rce.sol_ind_est_id,
			dau_movimiento_rce.dau_mov_rce_accion,
			dau_movimiento_rce.dau_mov_rce_fecha,
			dau_movimiento_rce.dau_mov_rce_usuario,
			estado_indicacion.est_descripcion,
			dau_movimiento_rce.dau_observacion_rce,";
			if($parametros['tipo_solicitud'] == 3){
				$sql .= " rce.detalle_solicitud_laboratorio.det_lab_descripcion,";
			}
			$sql .= " dau_movimiento_rce.SIC_id_rayos
			FROM dau.dau_movimiento_rce
			INNER JOIN rce.estado_indicacion ON dau.dau_movimiento_rce.sol_ind_est_id = rce.estado_indicacion.est_id";
			if($parametros['tipo_solicitud'] == 3){
				$sql .= " INNER JOIN rce.detalle_solicitud_laboratorio ON dau.dau_movimiento_rce.rce_sol_id = rce.detalle_solicitud_laboratorio.sol_lab_id";
			}
		$condicion = "";
		if($parametros['solicitud_id_list']){
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion .= " dau_movimiento_rce.rce_sol_id in ({$parametros['solicitud_id_list']}) ";
		}
		if($parametros['solicitud_id']){
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion .= " dau_movimiento_rce.rce_sol_id = {$parametros['solicitud_id']} ";
		}
		if($parametros['indicacion_id']){
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion .= " dau_movimiento_rce.sol_ind_id = {$parametros['indicacion_id']}";
		}
		if($parametros['tipo_id']){
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion .= " dau_movimiento_rce.sol_tipo_id = {$parametros['tipo_id']}";
		}
		if($parametros['tipo_solicitud']){
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion .= " dau_movimiento_rce.sol_tipo_id = {$parametros['tipo_solicitud']}";
		}
		if($parametros['dau_id']){
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion .= " dau_movimiento_rce.dau_id = {$parametros['dau_id']}";
		}
		$sql.= $condicion;
		$resultado = $objCon->consultaSQL($sql,"Error al movimientosIndicaciones");
		return $resultado;
	}



	function getSolicitudRayo($objCon,$objMovimientos,$parametros){
		$sql="SELECT
			solicitud_imagen_camas_historial.SIC_id,
			solicitud_imagen_camas_historial.SIC_fecha,
			solicitud_imagen_camas_historial.SIC_idpac,
			solicitud_imagen_camas_historial.SIC_ctacte,
			solicitud_imagen_camas_historial.SIC_RAU
			FROM
			rayos.solicitud_imagen_camas_historial";
		$condicion = "";
		if($parametros['sic_id']){
		$condicion .= ($condicion == "") ? " WHERE " : " AND ";
		$condicion .= " solicitud_imagen_camas_historial.SIC_id = {$parametros['sic_id']}";
		}
		$sql.= $condicion;
		$resultado = $objCon->consultaSQL($sql,"Error al getSolicitudRayo");

		if($resultado[0]['SIC_fecha']==''){
			return $resultado;
		}

	}



	function obtenerSolicitudImagenologia($objCon, $parametros){
		$sql = "SELECT
				rayos.solicitud_imagen_camas.SIC_id,
				rayos.solicitud_imagen_camas.SIC_fecha,
				rayos.solicitud_imagen_camas.SIC_idpac,
				rayos.solicitud_imagen_camas.SIC_ctacte,
				rayos.solicitud_imagen_camas.SIC_edad,
				rayos.solicitud_imagen_camas.SIC_sexo,
				rayos.solicitud_imagen_camas.SIC_crprocedencia,
				rayos.solicitud_imagen_camas.SIC_sala,
				rayos.solicitud_imagen_camas.SIC_cama,
				rayos.solicitud_imagen_camas.SIC_contraste,
				rayos.solicitud_imagen_camas.SIC_diagnostico,
				rayos.solicitud_imagen_camas.SIC_sintomas_pricipales,
				rayos.solicitud_imagen_camas.SIC_antecedentes_quir,
				rayos.solicitud_imagen_camas.SIC_embarazo,
				rayos.solicitud_imagen_camas.SIC_asma,
				rayos.solicitud_imagen_camas.SIC_hipertencion,
				rayos.solicitud_imagen_camas.SIC_diabetes,
				rayos.solicitud_imagen_camas.SIC_multires,
				rayos.solicitud_imagen_camas.SIC_otros,
				rayos.solicitud_imagen_camas.SIC_informado,
				rayos.solicitud_imagen_camas.SIC_clearence_creatina,
				rayos.solicitud_imagen_camas.SIC_proteccionrenal,
				rayos.solicitud_imagen_camas.SIC_premedicacion,
				rayos.solicitud_imagen_camas.SIC_medico_solicitante,
				rayos.solicitud_imagen_camas.SIC_procedencia_solicitud,
				rayos.solicitud_imagen_camas.SIC_RAU,
				rayos.solicitud_imagen_camas.SIC_tipo_examen,
				rayos.solicitud_imagen_camas.SIC_cod_prestacion,
				rayos.solicitud_imagen_camas.SIC_aplicado,
				rayos.solicitud_imagen_camas.SIC_sprocedencia,
				rayos.solicitud_imagen_camas.SIC_RCE_sol_ima_id
				FROM
				rayos.solicitud_imagen_camas
				WHERE
				rayos.solicitud_imagen_camas.SIC_RCE_sol_ima_id = {$parametros['solicitud_id']}";

		$resultado = $objCon->consultaSQL($sql,"Error al obtenerSolicitudImagenologia");
		return $resultado;
	}



	function eliminarSolicitudaImagenologia($objCon, $parametros){

		$sql1 = "INSERT INTO rayos.solicitud_imagen_camas_eliminados
				SELECT *,'{$parametros['usuario_Elimina']}' as SIC_usuario_elimina FROM rayos.solicitud_imagen_camas WHERE rayos.solicitud_imagen_camas.SIC_id ='{$parametros['SIC_id']}'";
		$resultado = $objCon->ejecutarSQL($sql1, "ERROR AL CARGAR elimina_solicitud");

		$sql2 ="DELETE FROM rayos.solicitud_imagen_camas WHERE rayos.solicitud_imagen_camas.SIC_id ='{$parametros['SIC_id']}'";
		$resultado = $objCon->ejecutarSQL($sql2, "ERROR AL CARGAR elimina_solicitud");

	}



	function pdfImagenologiaHistorico($objCon,$parametros){
		$sql="SELECT
				rayos.solicitud_imagen_camas_historial.SIC_fecha,
				rayos.solicitud_imagen_camas_historial.SIC_idpac,
				rayos.solicitud_imagen_camas_historial.SIC_ctacte,
				rayos.solicitud_imagen_camas_historial.SIC_RAU,
				rayos.solicitud_imagen_camas_historial.SIC_edad,
				rayos.solicitud_imagen_camas_historial.SIC_sexo,
				rayos.solicitud_imagen_camas_historial.SIC_crprocedencia,
				rayos.solicitud_imagen_camas_historial.SIC_sala,
				rayos.solicitud_imagen_camas_historial.SIC_cama,
				rayos.solicitud_imagen_camas_historial.SIC_contraste,
				rayos.solicitud_imagen_camas_historial.SIC_diagnostico,
				rayos.solicitud_imagen_camas_historial.SIC_sintomas_pricipales,
				rayos.solicitud_imagen_camas_historial.SIC_antecedentes_quir,
				rayos.solicitud_imagen_camas_historial.SIC_embarazo,
				rayos.solicitud_imagen_camas_historial.SIC_asma,
				rayos.solicitud_imagen_camas_historial.SIC_hipertencion,
				rayos.solicitud_imagen_camas_historial.SIC_diabetes,
				rayos.solicitud_imagen_camas_historial.SIC_multires,
				rayos.solicitud_imagen_camas_historial.SIC_otros,
				rayos.solicitud_imagen_camas_historial.SIC_informado,
				rayos.solicitud_imagen_camas_historial.SIC_clearence_creatina,
				rayos.solicitud_imagen_camas_historial.SIC_proteccionrenal,
				rayos.solicitud_imagen_camas_historial.SIC_premedicacion,
				rayos.solicitud_imagen_camas_historial.SIC_medico_solicitante,
				rayos.solicitud_imagen_camas_historial.SIC_procedencia_solicitud,
				rayos.solicitud_imagen_camas_historial.SIC_tipo_examen,
				rayos.solicitud_imagen_camas_historial.SIC_cod_prestacion,
				rayos.solicitud_imagen_camas_historial.SIC_RCE_observacion,
				paciente.paciente.rut,
				paciente.paciente.nroficha,
				paciente.paciente.fechanac,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,
				paciente.paciente.sexo,
				paciente.paciente.direccion,
				paciente.prevision.prevision
			FROM
				rayos.solicitud_imagen_camas_historial
				INNER JOIN paciente.paciente ON rayos.solicitud_imagen_camas_historial.SIC_idpac = paciente.paciente.id
				INNER JOIN paciente.prevision ON paciente.prevision = prevision.id
			WHERE
				rayos.solicitud_imagen_camas_historial.SIC_id = {$parametros['idSIC']} ";
		$resultado = $objCon->consultaSQL($sql,"Error al datosDauRce");
		return $resultado;
	}



	function obtenerDatosSolicitudImagenologiaReverso($objCon, $parametros){
		$sql = "SELECT
					rayos.examen.EXAcorrelativo,
					rayos.examen.EXTIcod,
					rayos.examen.EXA_SCA_folio,
					rayos.examen.ATEcorrelativo,
					rayos.examen.PREcod,
					rayos.examen.PREnombre,
					rayos.examen.EXAhora,
					rayos.examen.EXAtecnologo,
					rayos.examen.EXAparamedico,
					rayos.examen.EXAsala,
					rayos.atencion.ATEcorrelativo,
					rayos.atencion.ATEfecha,
					rayos.atencion.PACid,
					rayos.atencion.PACrut,
					rayos.atencion.PACrut_digito,
					rayos.atencion.PACrau,
					rayos.atencion.PACnom,
					rayos.atencion.PACcta_cte,
					rayos.atencion.ATEprocede,
					rayos.sala.SALid,
					rayos.sala.SALnumero,
					rayos.sala.SALtipo_examen,
					rayos.examen_usa_placa.PLAcod,
					rayos.placa.PLAnombre,
					paciente.paciente.fono1,
					paciente.paciente.fono2,
					paciente.paciente.fono3,
					acceso.usuario.nombreusuario,
					rayos.solicitud_imagen_camas_historial.SIC_fecha,
					rayos.solicitud_imagen_camas_historial.SIC_id
					FROM
					rayos.examen
					INNER JOIN rayos.atencion ON rayos.examen.ATEcorrelativo = rayos.atencion.ATEcorrelativo
					INNER JOIN rayos.sala ON rayos.examen.EXAsala = rayos.sala.SALid
					LEFT JOIN rayos.examen_usa_placa ON rayos.examen_usa_placa.EXAcorrelativo = rayos.examen.EXAcorrelativo
					LEFT JOIN rayos.placa ON rayos.examen_usa_placa.PLAcod = rayos.placa.PLAcod
					INNER JOIN paciente.paciente ON rayos.atencion.PACid = paciente.paciente.id
					INNER JOIN acceso.usuario ON rayos.examen.EXAtecnologo = acceso.usuario.idusuario
					INNER JOIN rayos.solicitud_imagen_camas_historial ON rayos.examen.ATEcorrelativo = rayos.solicitud_imagen_camas_historial.ATE_id
					WHERE
					rayos.examen.EXAcorrelativo = '{$parametros['EXAcorrelativo']}'";

		$resultado = $objCon->consultaSQL($sql,"Error al obtenerDatosSolicitudImagenologiaReverso");
		return $resultado;
	}

	function pdfImagenologia_historico($objCon,$parametros){
		$sql="SELECT
				rayos.solicitud_imagen_camas_historial.SIC_fecha,
				rayos.solicitud_imagen_camas_historial.SIC_idpac,
				rayos.solicitud_imagen_camas_historial.SIC_ctacte,
				rayos.solicitud_imagen_camas_historial.SIC_RAU,
				rayos.solicitud_imagen_camas_historial.SIC_edad,
				rayos.solicitud_imagen_camas_historial.SIC_sexo,
				rayos.solicitud_imagen_camas_historial.SIC_crprocedencia,
				rayos.solicitud_imagen_camas_historial.SIC_sala,
				rayos.solicitud_imagen_camas_historial.SIC_cama,
				rayos.solicitud_imagen_camas_historial.SIC_contraste,
				rayos.solicitud_imagen_camas_historial.SIC_diagnostico,
				rayos.solicitud_imagen_camas_historial.SIC_sintomas_pricipales,
				rayos.solicitud_imagen_camas_historial.SIC_antecedentes_quir,
				rayos.solicitud_imagen_camas_historial.SIC_embarazo,
				rayos.solicitud_imagen_camas_historial.SIC_asma,
				rayos.solicitud_imagen_camas_historial.SIC_hipertencion,
				rayos.solicitud_imagen_camas_historial.SIC_diabetes,
				rayos.solicitud_imagen_camas_historial.SIC_multires,
				rayos.solicitud_imagen_camas_historial.SIC_otros,
				rayos.solicitud_imagen_camas_historial.SIC_informado,
				rayos.solicitud_imagen_camas_historial.SIC_clearence_creatina,
				rayos.solicitud_imagen_camas_historial.SIC_proteccionrenal,
				rayos.solicitud_imagen_camas_historial.SIC_premedicacion,
				rayos.solicitud_imagen_camas_historial.SIC_medico_solicitante,
				rayos.solicitud_imagen_camas_historial.SIC_procedencia_solicitud,
				rayos.solicitud_imagen_camas_historial.SIC_tipo_examen,
				rayos.solicitud_imagen_camas_historial.SIC_cod_prestacion,
				rayos.solicitud_imagen_camas_historial.SIC_RCE_observacion,
				paciente.paciente.rut,
				paciente.paciente.nroficha,
				paciente.paciente.fechanac,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,
				paciente.paciente.sexo,
				paciente.paciente.direccion,
				paciente.prevision.prevision
			FROM
				rayos.solicitud_imagen_camas_historial
				INNER JOIN paciente.paciente ON rayos.solicitud_imagen_camas_historial.SIC_idpac = paciente.paciente.id
				INNER JOIN paciente.prevision ON paciente.prevision = prevision.id
			WHERE
				rayos.solicitud_imagen_camas_historial.SIC_id = {$parametros['idSIC']} ";
		$resultado = $objCon->consultaSQL($sql,"Error al datosDauRce");
		return $resultado;
	}



	//Consultas integración ingrad
	function anularIndicacionSolicitudImagenologia($objCon, $parametros) {
		$sql = "
			UPDATE
				rayos.solicitud_imagen_camas
			SET
				rayos.solicitud_imagen_camas.id_estado = 3
			WHERE
				rayos.solicitud_imagen_camas.SIC_RCE_sol_ima_id = '{$parametros['SIC_RCE_sol_ima_id']}'
		";

		$objCon->ejecutarSQL($sql, "Error al modificar estado de solicitud imagenología a anulado");
	}




	function ingresarCabeceraIntegracionSolicitudImagenologia($objCon, $parametros) {
		$sql = "INSERT INTO
							rayos.solicitud_cabecera_img_registro
							(
								fecha_solicitud_cabecera_registro,
								idpac_solicitud_cabecera_registro,
								fecha_registro,
								hora_registro,
								usuario_solicitud_cabecera_registro,
								tipo_prestacion_solicitud_cabecera_registro,
								procedencia,
								procedencia_id
							)
						VALUES
						(
							CURDATE(),
							'{$parametros['idpac_solicitud_cabecera_registro']}',
							CURDATE(),
							CURTIME(),
							'{$parametros['usuario_solicitud_cabecera_registro']}',
							'{$parametros['tipo_prestacion_solicitud_cabecera_registro']}',
							'{$parametros['procedencia']}',
							'{$parametros['procedencia_id']}'
						)
						";

		$objCon->ejecutarSQL($sql, "Error al ingresar cabecera integración de solicitud imagenología");
		$idSolicitudCabecera = $objCon->lastInsertId();
		return $idSolicitudCabecera;
	}



	function ingresarIntegracionIngrad($objCon, $parametros) {
		$sql = "INSERT INTO
							integraciones.integracion_ingrad
							(
								INTfecha_registro,
								INTusuario_registro,
								INTmetodo,
								INTid_paciente,
								INTcit_codigo,
								INTsolicitud_lenet,
								INTfecha_inicio,
								INTfecha_termino,
								INTid_recurso,
								INTrut_paciente,
								INTnombre,
								INTap_pat,
								INTap_mat,
								INTcorreo,
								INTtelefono,
								INTgenero,
								INTid_prevision,
								INTfecha_nacimiento,
								INTcontraste,
								INTprestacion,
								INTid_local,
								INTmedico_solicitante,
								INTcomuna,
								INTdireccion,
								INTresultado,
								LEX_id_solicitud_rce,
								INTprocedencia,
								INTmotivo,
								INTdiagnostico
							)
					VALUES
						(
							NOW(),
							'{$parametros['INTusuario_registro']}',
							'{$parametros['INTmetodo']}',
							'{$parametros['INTid_paciente']}',
							'{$parametros['INTcit_codigo']}',
							'{$parametros['INTsolicitud_lenet']}',
							NOW(),
							NOW(),
							'{$parametros['INTid_recurso']}',
							'{$parametros['INTrut_paciente']}',
							'{$parametros['INTnombre']}',
							'{$parametros['INTap_pat']}',
							'{$parametros['INTap_mat']}',
							'{$parametros['INTcorreo']}',
							'{$parametros['INTtelefono']}',
							'{$parametros['INTgenero']}',
							'{$parametros['INTid_prevision']}',
							'{$parametros['INTfecha_nacimiento']}',
							'{$parametros['INTcontraste']}',
							'{$parametros['INTprestacion']}',
							'{$parametros['INTid_local']}',
							'{$parametros['INTmedico_solicitante']}',
							'{$parametros['INTcomuna']}',
							'{$parametros['INTdireccion']}',
							'{$parametros['INTresultado']}',
							'{$parametros['LEX_id_solicitud_rce']}',
							'{$parametros['INTprocedencia']}',
							'{$parametros['INTmotivo']}',
							'{$parametros['INTdiagnostico']}'
						)
						";

		$objCon->ejecutarSQL($sql, "Error al ingresar integración de solicitud imagenología");
	}



	function obtenerDatosPacienteIntegracionIngrad($objCon, $parametros) {
		$sql = "SELECT
						IF(
								paciente.paciente.rut = 0
								OR paciente.paciente.rut = NULL
								OR paciente.paciente.rut = ''
								, '0-0'
								, CONCAT(paciente.paciente.rut,'-',IFNULL(paciente.paciente.dv,0))
							) AS 'INTrut_paciente',
							paciente.paciente.nombres AS 'INTnombre',
							paciente.paciente.apellidopat AS 'INTap_pat',
							paciente.paciente.apellidomat AS 'INTap_mat',
							IF(paciente.paciente.email IS NOT NULL AND paciente.paciente.email <> '', paciente.paciente.email, 'SINCORREO')  AS 'INTcorreo',
							paciente.paciente.fono1 AS 'INTtelefono',
							paciente.paciente.sexo AS 'INTgenero',
							paciente.prevision.homologacion_ingrad AS 'INTid_prevision',
							paciente.paciente.fechanac AS 'INTfecha_nacimiento',
							paciente.paciente.direccion AS 'INTdireccion'
						FROM
							paciente.paciente
						INNER JOIN
							paciente.prevision ON paciente.paciente.prevision = paciente.prevision.id
						WHERE
							paciente.paciente.id = '{$parametros['id_paciente']}'
						";

		$resultado = $objCon->consultaSQL($sql, "Error al obtener datos de paciente de integración ingra");
		return $resultado;
	}



	function obtenerIdSolicitudCabeceraRegistro($objCon, $parametros) {
		$sql = "
			SELECT
				rayos.solicitud_imagen_camas.id_solicitud_cabecera_registro
			FROM
				rayos.solicitud_imagen_camas
			WHERE
				rayos.solicitud_imagen_camas.SIC_id = '{$parametros['SIC_id']}'
		";

		$resultado = $objCon->consultaSQL($sql, "Error al obtenerIdSolicitudCabeceraRegistro ");
		return $resultado[0]["id_solicitud_cabecera_registro"];
	}






	function obtenerDatosIntegracionIngrad($objCon, $parametros) {
		$sql = "SELECT
							integraciones.integracion_ingrad.*
						FROM
							integraciones.integracion_ingrad
						WHERE
							integraciones.integracion_ingrad.LEX_id_solicitud_rce = '{$parametros['LEX_id_solicitud_rce']}'
						";

		$resultado = $objCon->consultaSQL($sql, "Error al obtener datos de integración ingrad");
		return $resultado;
	}



	function obtenerDatosIntegracionIngradHistorico($objCon, $parametros) {
		$sql = "SELECT
							integraciones.integracion_ingrad_historico.*
						FROM
							integraciones.integracion_ingrad_historico
						WHERE
							integraciones.integracion_ingrad_historico.LEX_id_solicitud_rce = '{$parametros['LEX_id_solicitud_rce']}'
						";

		$resultado = $objCon->consultaSQL($sql, "Error al obtener datos de integración ingrad");
		return $resultado;
	}



	function obtenerIdIngradIntegracion($objCon, $parametros) {
		$sql = "SELECT
							integraciones.integracion_ingrad.INTid_imgrad
						FROM
							integraciones.integracion_ingrad
						WHERE
							integraciones.integracion_ingrad.LEX_id_solicitud_rce = '{$parametros['LEX_id_solicitud_rce']}'
						";
		$resultado = $objCon->consultaSQL($sql, "Error al obtener datos de integración ingrad");
		return $resultado;
	}



	function obtenerMotivoEHipotesisInicial($objCon, $parametros) {
		$sql = "SELECT
							rce.registroclinico.regMotivoConsulta,
							rce.registroclinico.regHipotesisInicial
						FROM
							rce.registroclinico
						WHERE
							rce.registroclinico.regId = '{$parametros['regId']}'
						";

		$resultado = $objCon->consultaSQL($sql, "Error al obtener datos de integración ingrad");
		return $resultado;
	}



	function obtenerPartesCuerpoIntegracionIngrad($objCon, $parametros = NULL) {
		$condicion = "";

		$sql = "SELECT
							le.homologacion_ingrad.PARTE_CUERPO
						FROM
							le.homologacion_ingrad
						WHERE
							le.homologacion_ingrad.ID = '{$parametros['ID']}'
						";

		if (is_null($parametros['pacienteComplejo']) || empty($parametros['pacienteComplejo']) || $parametros['pacienteComplejo'] == 'N') {
			$condicion .= " AND le.homologacion_ingrad.pacienteUrgencia = 'S' ";
		}

		$condicion .= " GROUP BY le.homologacion_ingrad.PARTE_CUERPO ASC ";

		$sql .= $condicion;
		$resultado = $objCon->consultaSQL($sql, "Error al obtener partes del cuerpo");
		return $resultado;
	}



	function obtenerPrestacionesIntegracionIngrad($objCon, $parametros) {
		$condicion = "";

		$sql = "SELECT
							le.homologacion_ingrad.ID,
							le.homologacion_ingrad.DESCRIPCION,
							le.homologacion_ingrad.CODIGO,
							le.homologacion_ingrad.Contraste,
							le.homologacion_ingrad.Codigo_Plano,
							le.plano.planoNombre
						FROM
							le.homologacion_ingrad
						LEFT JOIN
							le.plano
							ON le.homologacion_ingrad.Codigo_Plano = le.plano.planoID
						WHERE
							le.homologacion_ingrad.clasificacion = '{$parametros['clasificacion']}'
						";

		if (is_null($parametros['pacienteComplejo']) || empty($parametros['pacienteComplejo']) || $parametros['pacienteComplejo'] == 'N') {
			$condicion = " AND le.homologacion_ingrad.pacienteUrgencia = 'S' ";
		}

		$condicion .= " ORDER BY le.homologacion_ingrad.CODIGO ASC ";

		$sql .= $condicion;

		$resultado = $objCon->consultaSQL($sql, "Error al obtener prestaciones de integración ingra");

		return $resultado;
	}



	function obtenerTiposExamenesIntegracionIngrad($objCon, $parametros = NULL) {
		$condicion = "";

		$sql = "SELECT
							le.homologacion_ingrad.clasificacion
						FROM
							le.homologacion_ingrad
						";

		if (is_null($parametros['pacienteComplejo']) || empty($parametros['pacienteComplejo']) || $parametros['pacienteComplejo'] == 'N') {
			$condicion .= " WHERE le.homologacion_ingrad.pacienteUrgencia = 'S' ";
		}

		$condicion .= " GROUP BY le.homologacion_ingrad.clasificacion ASC ";

		$sql .= $condicion;
		$resultado = $objCon->consultaSQL($sql, "Error al obtener tipos de exámenes");
		return $resultado;
	}



	function obtenerExamenesIntegracionDALCA($objCon) {
		$sql = "
			SELECT
				le.prestaciones_imagenologia.parte_del_cuerpo,
				le.prestaciones_imagenologia.id_prestaciones,
				le.prestaciones_imagenologia.examen,
				le.prestaciones_imagenologia.tipo_examen,
				CONCAT_WS(
					', ',
					IF(LENGTH(prestacion_1), prestacion_1, NULL),
					IF(LENGTH(prestacion_2), prestacion_2, NULL),
					IF(LENGTH(prestacion_3), prestacion_3, NULL),
					IF(LENGTH(prestacion_4), prestacion_4, NULL),
					IF(LENGTH(prestacion_5), prestacion_5, NULL),
					IF(LENGTH(prestacion_6), prestacion_6, NULL)
				) AS prestaciones,
				le.prestaciones_imagenologia.Lateralidad,
				le.prestaciones_imagenologia.contraste
			FROM
				le.prestaciones_imagenologia
			ORDER BY
				le.prestaciones_imagenologia.examen ASC
		";

		$resultado = $objCon->consultaSQL($sql, "Error al obtenerExamenesIntegracionDALCA");
		return $resultado;
	}



	function obtenerTiposExamenesIntegracionDALCA($objCon) {
		$sql = "
			SELECT
				le.prestaciones_imagenologia.tipo_examen
			FROM
				le.prestaciones_imagenologia
			GROUP BY
				le.prestaciones_imagenologia.tipo_examen
			ORDER BY
				le.prestaciones_imagenologia.tipo_examen ASC
		";

		$resultado = $objCon->consultaSQL($sql, "Error al obtenerTiposExamenesIntegracionDALCA");
		return $resultado;
	}



	function ingresarDetalleSolicitudImagenologiaDalca($objCon, $parametros) {
		$sql = "
			INSERT INTO
				rce.detalle_solicitud_imagenologia_dalca(
					idSolicitudImagenologia,
					idSolicitudDalca,
					idPrestacionImagenologia,
					idEstadoDetalleSolicitud,
					contraste,
					consentimientoInformado,
					clearenceDeCreatinina,
					premedicacion,
					proteccionRenal,
					sedacion,
					marcapasos,
					observacionSolicitud
				)
			VALUES(
				'{$parametros['idSolicitudImagenologia']}',
				'{$parametros['idSolicitudDalca']}',
				'{$parametros['idPrestacionImagenologia']}',
				'{$parametros['idEstadoDetalleSolicitud']}',
				'{$parametros['contraste']}',
				'{$parametros['consentimientoInformado']}',
				'{$parametros['clearenceDeCreatinina']}',
				'{$parametros['premedicacion']}',
				'{$parametros['proteccionRenal']}',
				'{$parametros['sedacion']}',
				'{$parametros['marcapasos']}',
				'{$parametros['observacionSolicitud']}'
			)
		";

		$objCon->ejecutarSQL($sql, "Error al ingresarDetalleSolicitudImagenologiaDalca");
	 	return $objCon->lastInsertId();
	}


	function SELECT_TiposExamenes($objCon,$parametros) {
		$sql = "
			SELECT
				le.prestaciones_imagenologia.tipo_examen
			FROM
				le.prestaciones_imagenologia
			WHERE le.prestaciones_imagenologia.id_prestaciones = '{$parametros['id_prestaciones']}'";

		$resultado = $objCon->consultaSQL($sql, "Error al obtenerTiposExamenesIntegracionDALCA");
		return $resultado;
	}


	function getImagenologia_prestaciones_tabla_nueva($objCon,$txt_examenes_codigo){
		$sql ="
	   SELECT
	   prestaciones_imagenologia.id_prestaciones,
	   prestaciones_imagenologia.examen,
	   prestaciones_imagenologia.tipo_examen
	   FROM
	   le.prestaciones_imagenologia
	   WHERE prestaciones_imagenologia.id_prestaciones = $txt_examenes_codigo";
	   $datos = $objCon->consultaSQL($sql,"Error al listar examenes de getImagenologia_prestaciones_tabla_nueva");
	   return $datos;
   }
}
?>
