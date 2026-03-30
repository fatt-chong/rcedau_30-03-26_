<?php
class Consulta{

	function listarConsulta($objCon, $parametros){

		require_once("Util.class.php");
		$objUtil    = new Util;

		if ($objUtil->existe($parametros['frm_rut'])) {
			$index = 'idx3_rut';
		} else if ($parametros['frm_nombreCompleto']) {
			$index = 'idx1_pac';
		} else {
			$index = 'idx_idpaciente';
		}

		$sql1 = " 	SELECT
						dau.dau_id,
						dau.dau.est_id,
						dau.dau.dau_admision_fecha,
						dau.dau.id_paciente,
						dau.dau.idctacte,
						dau.dau.dau_paciente_nn,
						motivo_consulta.mot_descripcion,
						paciente.paciente.id,
						paciente.paciente.rut,
						paciente.paciente.nombres,
						paciente.paciente.apellidopat,
						paciente.paciente.apellidomat,
						paciente.paciente.transexual,
						paciente.paciente.nombreSocial,
						dau.atencion.ate_descripcion,
						dau.atencion.ate_id,
						dau.motivo_consulta.mot_id,
						paciente.paciente.id_doc_extranjero,
						paciente.paciente.rut_extranjero,
						paciente.paciente.extranjero,
						'DAU' AS 'tipo',
						dau.dau_cierre_administrativo,
						dau.dau_categorizacion_actual,
						dau.dau_numero_documento,
						dau.dau_contingencia,
						MAX(dau.receta_ges.idRecetaGES) AS idRecetaGES,
						dauPacienteNN.fechaReemplazo
					FROM
							dau.dau
					INNER JOIN paciente.paciente force index ({$index}) ON dau.dau.id_paciente = paciente.paciente.id
					INNER JOIN dau.motivo_consulta ON dau.dau_motivo_consulta = motivo_consulta.mot_id
					INNER JOIN dau.atencion ON dau.dau_atencion = atencion.ate_id
					LEFT JOIN dau.receta_ges ON dau.dau.dau_id = dau.receta_ges.idDau
					LEFT JOIN (
						SELECT
								dauPacienteNN.*
						FROM
								dau.dau_paciente_nn AS dauPacienteNN
						LEFT JOIN (
								SELECT
										dau.dau_paciente_nn.idDau,
										MAX(dau.dau_paciente_nn.idDauPacienteNN) AS idDauPacienteNN
								FROM
										dau.dau_paciente_nn
								GROUP BY
										dau.dau_paciente_nn.idDau
						) AS dauPacienteNN_max
						ON dauPacienteNN.idDau = dauPacienteNN_max.idDau
						AND dauPacienteNN.idDauPacienteNN = dauPacienteNN_max.idDauPacienteNN
				) AS dauPacienteNN
				ON dau.dau.dau_id = dauPacienteNN.idDau
				";

				if ( $parametros['frm_rut'] && $parametros['documento'] == 1 ) {
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion.="paciente.paciente.rut = {$parametros['frm_rut']}";
				}

				if ( $parametros['frm_nroDocumento'] && $parametros['documento'] == 2) {
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion.="paciente.rut_extranjero = '{$parametros['frm_nroDocumento']}' AND paciente.extranjero='S' AND paciente.rut=0";
				}

				if ( $parametros['frm_numero_dau'] ) {
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion.="dau.dau_id = {$parametros['frm_numero_dau']}";
				}

				if ( $parametros['frm_nombreCompleto'] ) {
					$parametros['frm_nombreCompleto'] = str_replace(' ', ' +', $parametros['frm_nombreCompleto']);
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion .= "MATCH (paciente.nombres, paciente.apellidopat, paciente.apellidomat) AGAINST ('+{$parametros['frm_nombreCompleto']}' IN BOOLEAN MODE)";
				}

				if ( $parametros['frm_tipo_atencion'] ) {
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion.="dau.dau.dau_atencion = {$parametros['frm_tipo_atencion']}";
				}

				if ( $parametros['frm_motivo'] ) {
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion.="dau.dau.dau_motivo_consulta = {$parametros['frm_motivo']}";
				}

				if ( $parametros['frm_cuentaCorriente'] ) {
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion.="dau.idctacte = {$parametros['frm_cuentaCorriente']}";

				}


				if($parametros['nombreSocial']!=""){
					// $statusFilterNombres = true;
					$nombreSocial = $parametros['nombreSocial'];
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion .= "  paciente.paciente.nombreSocial like '$nombreSocial%' AND paciente.paciente.transexual = 'S'";
				}

				if ( $parametros['frm_fecha_admision_desde'] && $parametros['frm_fecha_admision_hasta'] ){
					$parametros['frm_fecha_admision_desde'] = str_replace("/","-",$parametros['frm_fecha_admision_desde']);
					$parametros['frm_fecha_admision_hasta'] = str_replace("/","-",$parametros['frm_fecha_admision_hasta']);
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion .="DATE_FORMAT(dau.dau.dau_admision_fecha, '%Y-%m-%d') BETWEEN '{$parametros['frm_fecha_admision_desde']}' AND '{$parametros['frm_fecha_admision_hasta']}'";
				} else {
					if ( $parametros['frm_fecha_admision_desde'] ) {
						$parametros['frm_fecha_admision_desde'] = str_replace("/","-",$parametros['frm_fecha_admision_desde']);
						$condicion .= ($condicion == "") ? " WHERE " : " AND ";
						$condicion .="DATE_FORMAT(dau.dau.dau_admision_fecha, '%Y-%m-%d') = '{$parametros['frm_fecha_admision_desde']}'";
					}

					if ($parametros['frm_fecha_admision_hasta']) {
						$parametros['frm_fecha_admision_hasta'] = str_replace("/","-",$parametros['frm_fecha_admision_hasta']);
						$condicion .= ($condicion == "") ? " WHERE " : " AND ";
						$condicion .="DATE_FORMAT(dau.dau.dau_admision_fecha, '%Y-%m-%d') = '{$parametros['frm_fecha_admision_hasta']}'";
					}
				}

				if ( $parametros['checkSinCategorizacionCerrados'] == 5 ) {
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion.="dau.est_id = 5 AND dau.dau_categorizacion_actual IS NULL";
				}


		if ( $parametros['checkHistorico'] == "H" ) {

			$sql2 = "	SELECT
							rau.rau.idrau,
							rau.rau.estado,
							rau.rau.fecha,
							rau.rau.idpaciente,
							rau.rau.idctacte,
							'' AS dau_paciente_nn,
							(select dau.motivo_consulta.mot_descripcion from dau.motivo_consulta force index (mot_id) where mot_id = rau.rau.tipoconsulta) as 'mot_descripcion',
							paciente.paciente.id,
							rau.rau.rut,
							paciente.paciente.nombres,
							paciente.paciente.apellidopat,
							paciente.paciente.apellidomat,

							paciente.paciente.transexual,
							paciente.paciente.nombreSocial,

							(select dau.atencion.ate_descripcion from dau.atencion force index (ate_id) where dau.atencion.ate_id = rau.rau.tipoatencion) as ate_descripcion,
							(select dau.atencion.ate_id from dau.atencion force index (ate_id) where dau.atencion.ate_id = rau.rau.tipoatencion) as ate_id,
							(select dau.motivo_consulta.mot_id from dau.motivo_consulta force index (mot_id) where mot_id = rau.rau.tipoconsulta) as 'mot_id',
							paciente.paciente.id_doc_extranjero,
							paciente.paciente.rut_extranjero,
							paciente.paciente.extranjero,
							'RAU' AS 'tipo',
							'' AS dau_cierre_administrativo,
							'' AS dau_categorizacion_actual,
							'' AS numeroDocumento,
							'' AS dau_contingencia,
							'' AS idRecetaGES,
							'' AS fechaReemplazo
						FROM
							rau.rau
						INNER JOIN paciente.paciente force index ({$index}) ON rau.rau.idpaciente = paciente.paciente.id ";


					if ( $parametros['frm_rut'] &&  $parametros['documento'] == 1) {

						$condicion2 .= ($condicion2 == "") ? " WHERE " : " AND ";
						$condicion2.="paciente.paciente.rut= {$parametros['frm_rut']}";

					}

					if ( $parametros['frm_nroDocumento'] && $parametros['documento'] == 2 ) {

						$condicion2 .= ($condicion2 == "") ? " WHERE " : " AND ";
						$condicion2.="paciente.rut_extranjero = {$parametros['frm_nroDocumento']} AND paciente.extranjero='S' AND paciente.rut=0";

					}

					if ( $parametros['frm_numero_dau'] ) {

						$condicion2 .= ($condicion2 == "") ? " WHERE " : " AND ";
						$condicion2.="rau.idrau = {$parametros['frm_numero_dau']}";

					}

					if ( $parametros['frm_nombreCompleto'] ) {

						$parametros['frm_nombreCompleto'] = str_replace(' ', ' +', $parametros['frm_nombreCompleto']);
						$condicion2 .= ($condicion2 == "") ? " WHERE " : " AND ";
						$condicion2 .= "MATCH (paciente.nombres, paciente.apellidopat, paciente.apellidomat) AGAINST ('+{$parametros['frm_nombreCompleto']}' IN BOOLEAN MODE)";

					}

					if ( $parametros['frm_tipo_atencion'] ) {

						$condicion2 .= ($condicion2 == "") ? " WHERE " : " AND ";
						$condicion2.="rau.rau.tipoatencion = {$parametros['frm_tipo_atencion']}";

					}

					if ( $parametros['frm_motivo'] ) {

						$condicion2 .= ($condicion2 == "") ? " WHERE " : " AND ";
						$condicion2.="rau.rau.tipoconsulta = {$parametros['frm_motivo']}";

					}

					if ( $parametros['frm_cuentaCorriente'] ) {

						$condicion2 .= ($condicion2 == "") ? " WHERE " : " AND ";
						$condicion2.="rau.idctacte = {$parametros['frm_cuentaCorriente']}";

					}

					if ( $parametros['frm_fecha_admision_desde'] && $parametros['frm_fecha_admision_hasta'] ) {

						$condicion2 .= ($condicion2 == "") ? " WHERE " : " AND ";
						$condicion2 .="DATE(rau.rau.fecha) BETWEEN date('{$parametros['frm_fecha_admision_desde']}') AND date('{$parametros['frm_fecha_admision_hasta']}')";

					} else {

						if ( $parametros['frm_fecha_admision_desde'] ) {

							$parametros['frm_fecha_admision_desde'] = str_replace("/","-",$parametros['frm_fecha_admision_desde']);
							$condicion2 .= ($condicion2 == "") ? " WHERE " : " AND ";
							$condicion2 .="DATE(rau.rau.fecha) = date('{$parametros['frm_fecha_admision_desde']}')";

						}

						if ( $parametros['frm_fecha_admision_hasta'] ) {

							$parametros['frm_fecha_admision_hasta'] = str_replace("/","-",$parametros['frm_fecha_admision_hasta']);
							$condicion2 .= ($condicion2 == "") ? " WHERE " : " AND ";
							$condicion2 .="DATE(rau.rau.fecha) = date('{$parametros['frm_fecha_admision_hasta']}')";

						}

					}

			$condicion2 .= " and rau.rau.idpaciente IS NOT NULL";

			$sql  .= $sql1.$condicion." GROUP BY dau.dau.dau_id UNION ALL ".$sql2.$condicion2;

			$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR DAU<br>");

			return $datos;

		} else {

			$sql1  .= $condicion." GROUP BY dau.dau.dau_id";

   		$datos = $objCon->consultaSQL($sql1,"<br>ERROR AL LISTAR DAU<br>");

			return $datos;

		}

	}



	function consultaDAU($objCon,$parametros){

		$condicion = "";

		$sql="SELECT
		dau.dau.dau_id,
		dau.dau.idctacte,
		dau.dau.id_paciente,
		dau.dau.dau_admision_fecha,
		dau.dau.dau_paciente_domicilio,
		dau.consultorios.con_descripcion,
		paciente.paciente.rut,
		paciente.paciente.nombres,
		paciente.paciente.apellidopat,
		paciente.paciente.apellidomat,
		paciente.etnia.etnia_descripcion,
		paciente.nacionalidadavis.NACpais,
		dau.atencion.ate_descripcion,
		paciente.paciente.sexo,
		paciente.paciente.fechanac,
		paciente.paciente.nroficha,
		dau.dau.dau_paciente_domicilio_tipo,
		paciente.paciente.fono1,
		paciente.paciente.PACfono,

		paciente.paciente.transexual,
		paciente.paciente.nombreSocial,


		dau.medio_llegada.med_descripcion,
		dau.dau.dau_imputado,
		dau.intoxicacion.int_descripcion,
		dau.mordedura.mor_descripcion,
		dau.quemado.que_descripcion,
		dau.dau.dau_categorizacion_actual,
		dau.categorizacion.cat_nombre,
		dau.dau.dau_paciente_prevision,
		dau.dau.dau_paciente_forma_pago,
		paciente.institucion.instNombre,
		paciente.prevision.prevision,
		dau.dau.dau_cierre_administrativo_fecha,
		dau.dau.dau_alcoholemia_numero_frasco,
		dau.dau.dau_alcoholemia_resultado,
		dau.dau.dau_alcoholemia_fecha,
		dau.dau.dau_alcoholemia_medico,
		dau.dau.dau_alcoholemia_apreciacion,
		dau.dau.dau_alcoholemia_estado_etilico,
		dau.etilico.eti_descripcion,
		dau.dau.dau_cierre_condicion_ingreso_id,
		dau.dau.dau_cierre_pronostico_id,
		dau.dau.dau_cierre_peso,
		dau.dau.dau_cierre_estatura,
		dau.dau.dau_cierre_tratamiento_id,
		dau.dau.dau_cierre_atendidopor_id,
		dau.dau.dau_cierre_profesional_id,
		dau.dau.dau_cierre_turno_id,
		dau.dau.dau_cierre_hora_atencion,
		dau.dau.dau_cierre_auge,
		dau.dau.dau_cierre_entrega_postinor,
		dau.dau.dau_cierre_hepatitisB,
		dau.dau.dau_cierre_pertinencia,
		dau.dau.est_id,
		dau.dau.dau_cierre_administrativo_observacion,
		dau.dau.dau_indicacion_egreso,
		dau.dau.dau_defuncion_fecha,
		paciente.paciente.rut_extranjero,
		dau.dau.dau_cierre_servicio,
		dau_cierre_fundamento_diag,
		IF(camas.sscc.servicio != '', camas.sscc.servicio,
		(SELECT
		camas.sscc.servicio
		FROM
		dau.dau
		INNER JOIN dau.dau_tiene_indicacion ON dau.dau_tiene_indicacion.dau_id = dau.dau.dau_id
		INNER JOIN camas.sscc ON dau.dau_tiene_indicacion.dau_ind_servicio = camas.sscc.id
		WHERE dau.dau.dau_id = '{$parametros['Iddau']}')
		) as servicio,
		dau.dau.dau_motivo_consulta,
		dau.motivo_consulta.mot_descripcion,
		dau.dau.dau_motivo_descripcion,
		dau.dau.dau_ingreso_sala_fecha,
		dau.condicion_ingreso.con_ingreso_nombre,
		dau.atendido_por.ate_atendidopor_nombre,
		dau.pronostico.pro_pronostico_nombre,
		dau.indicacion_egreso.ind_egr_descripcion,
		dau.tratamiento.tra_tratamiento_nombre,
		parametros_clinicos.profesional.PROdescripcion,
		dau.dau.dau_tipo_accidente,
		dau.sub_motivo_consulta.sub_mot_descripcion,
		dau.dau.dau_accidente_escolar_institucion,
		dau.dau.dau_accidente_escolar_numero,
		dau.dau.dau_accidente_escolar_nombre,
		dau.dau.dau_accidente_trabajo_mutualidad,
		dau.dau.dau_accidente_transito_tipo,
		dau.tipo_transito.tran_descripcion,
		dau.dau.dau_accidente_hogar_lugar,
		dau.dau.dau_accidente_otro_lugar,
		dau.dau.dau_cierre_cie10 AS idcie10,
		cie10.cie10.nombreCIE AS cie10_nombre,
		dau.dau.dau_paciente_edad,
		paciente.comuna.comuna,
		dau.categorizacion.cat_id,
		dau.dau.dau_categorizacion_actual_fecha,
		dau.dau.dau_inicio_atencion_fecha,
		dau.dau.dau_indicacion_egreso_fecha,
		dau.dau.dau_indicacion_egreso_aplica_fecha,
		dau.dau.dau_hipotesis_diagnostica_inicial,
		dau.dau.dau_atencion,
		dau.dau.dau_cierre_des_id,
		dau.dau.dau_cierre_atl_der_id,
		dau.dau.dau_cierre_ind_especialidad,
		dau.dau.dau_cierre_ind_aps,
		dau.dau.dau_cierre_ind_otros,
		dau.dau.dau_cierre_administrativo,
		dau.tipo_choque.tip_choque_descripcion,
		dau.tipo_choque.tip_choque_id,
		dau.dau.dau_tipo_choque,
		dau.dau.dau_manifestaciones,
		acceso.usuario.nombreusuario AS nombreUsuario
		FROM
		dau.dau
		LEFT JOIN paciente.paciente ON paciente.paciente.id = dau.dau.id_paciente
		LEFT JOIN dau.consultorios ON dau.consultorios.con_id = paciente.paciente.centroatencionprimaria
		LEFT JOIN paciente.etnia ON paciente.etnia.etnia_id = paciente.paciente.etnia
		LEFT JOIN paciente.nacionalidadavis ON paciente.nacionalidadavis.NACcodigo = paciente.paciente.nacionalidad
		LEFT JOIN dau.atencion ON dau.atencion.ate_id = dau.dau.dau_atencion
		LEFT JOIN dau.medio_llegada ON dau.medio_llegada.med_id = dau.dau.dau_forma_llegada
		LEFT JOIN dau.intoxicacion ON dau.dau.dau_intoxicacion = dau.intoxicacion.int_id
		LEFT JOIN dau.mordedura ON dau.dau.dau_mordedura = dau.mordedura.mor_id
		LEFT JOIN dau.quemado ON dau.dau.dau_quemadura = dau.quemado.que_id
		LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
		LEFT JOIN paciente.institucion ON dau.dau.dau_paciente_forma_pago = paciente.institucion.instCod
		LEFT JOIN paciente.prevision ON dau.dau.dau_paciente_prevision = paciente.prevision.id
		LEFT JOIN dau.etilico ON dau.dau.dau_alcoholemia_estado_etilico = dau.etilico.eti_id
		LEFT JOIN camas.sscc ON dau.dau.dau_cierre_servicio = camas.sscc.id
		LEFT JOIN dau.motivo_consulta ON dau.dau.dau_motivo_consulta = dau.motivo_consulta.mot_id
		LEFT JOIN dau.condicion_ingreso ON dau.dau.dau_cierre_condicion_ingreso_id = dau.condicion_ingreso.con_ingreso_id
		LEFT JOIN dau.atendido_por ON dau.dau.dau_cierre_atendidopor_id = dau.atendido_por.ate_atendidopor_id
		LEFT JOIN dau.pronostico ON dau.dau.dau_cierre_pronostico_id = dau.pronostico.pro_pronostico_id
		LEFT JOIN dau.indicacion_egreso ON dau.dau.dau_indicacion_egreso = dau.indicacion_egreso.ind_egr_id
		LEFT JOIN dau.tratamiento ON dau.dau.dau_cierre_tratamiento_id = dau.tratamiento.tra_tratamiento_id
		LEFT JOIN parametros_clinicos.profesional ON dau.dau.dau_alcoholemia_medico = parametros_clinicos.profesional.PROcodigo
		LEFT JOIN dau.sub_motivo_consulta ON dau.dau.dau_tipo_accidente = dau.sub_motivo_consulta.sub_mot_id
		LEFT JOIN dau.tipo_transito ON dau.dau.dau_accidente_transito_tipo = dau.tipo_transito.tran_id
		LEFT JOIN cie10.cie10 ON dau.dau.dau_cierre_cie10 = cie10.cie10.codigoCIE
		LEFT JOIN paciente.comuna ON paciente.paciente.idcomuna = paciente.comuna.id
		LEFT JOIN dau.tipo_choque ON dau.dau.dau_tipo_choque = dau.tipo_choque.tip_choque_id
		LEFT JOIN acceso.usuario ON dau.dau.dau_inicio_atencion_usuario = acceso.usuario.idusuario";

		if ($parametros['Iddau']) {
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion.="dau.dau_id = {$parametros['Iddau']}";
		}

		$sql  .= $condicion;
		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR DAU<br>");
		return $datos;

	}



	function consultaRau($objCon,$parametros){

		$condicion = "";

		$sql="SELECT
			rau.rau.idrau,
			rau.rau.rut,
			rau.rau.fecha,
			DATE_FORMAT(rau.rau.fecha, '%Y') AS anio,
			rau.rau.tipoatencion,
			rau.rau.idpaciente,
			paciente.paciente.rut,
			paciente.paciente.nombres,
			paciente.paciente.apellidopat,
			paciente.paciente.apellidomat,
			paciente.paciente.fechanac,
			paciente.paciente.sexo,
			paciente.paciente.direccion,
			paciente.paciente.nroficha,
			paciente.paciente.fono1,
			paciente.paciente.fono3,

			paciente.paciente.transexual,
			paciente.paciente.nombreSocial,

			rau.rau.tipozona,
			paciente.prevision.prevision,
			paciente.nacionalidad.nacionalidadnombre,
			paciente.paciente.etnia,
			dau.etnia.etn_descripcion,
			rau.rau.mediollegadapaciente,
			rau.rau.imputado,
			rau.rau.tipoaccidente,
			rau.rau.tipoconsulta,
			dau.motivo_consulta.mot_descripcion,
			rau.rau.motivoconsulta,
			rau.rau.mordedura,
			rau.rau.intoxicacion,
			rau.rau.quemadura,
			dau.mordedura.mor_descripcion,
			dau.intoxicacion.int_descripcion,
			dau.quemado.que_descripcion,
			rau.rau.peso,
			rau.rau.talla,
			rau.rau.atendidopor,
			rau.rau.horabox,
			rau.rau.condicioningreso,
			rau.rau.categorizacion,
			dau.atendido_por.ate_atendidopor_nombre,
			dau.condicion_ingreso.con_ingreso_nombre,
			rau.rau.deriva,
			dau.derivacion.der_nombre,
			rau.rau.pronostico,
			dau.pronostico.pro_pronostico_nombre,
			rau.rau.pertinencia,
			rau.rau.estadoetilico,
			dau.etilico.eti_descripcion,
			rau.rau.entregapostinor,
			rau.rau.auge,
			rau.rau.destino,
			dau.destino.des_nombre,
			rau.rau.tratamiento,
			dau.tratamiento.tra_tratamiento_nombre,
			rau.rau.horaalcoholemia,
			rau.rau.boletaalcoholemia,
			rau.rau.resultadoalcoholemia,
			rau.rau.alcoholemia_medico,
			rau.rau.idcie10,
			rau.rau.medicotratante,
			rau.rau.rechazar,
			dau.atencion.ate_descripcion,
			rau.rau.idctacte,
			dau.medio_llegada.med_descripcion,
			dau.categorizacion.cat_id,
			dau.tipo_zona.tip_nombre,
			rau.rau.sscc,
			acceso.servicio.nombre,
			acceso.medico.nombremedico,
			dau.sub_motivo_consulta.sub_mot_descripcion,
			dau.atropellado_por.atr_nombre,
			rau.rau.transito_colision,
			dau.chocado_por.cho_nombre,
			rau.rau.escolar_tipoinstitucion,
			rau.rau.escolar_nro,
			rau.rau.escolar_nombre,
			rau.rau.mutualidad,
			rau.rau.hogar_tipo,
			rau.rau.otro_tipo,
			dau.consultorios.con_descripcion,
			cie10.cie10.nombreCIE as cie10_nombre
			FROM
			rau.rau
			LEFT JOIN paciente.paciente ON rau.rau.idpaciente = paciente.paciente.id
			LEFT JOIN paciente.prevision ON rau.rau.previCod = paciente.prevision.id
			LEFT JOIN paciente.nacionalidad ON paciente.paciente.nacionalidad = paciente.nacionalidad.nacionalidad
			LEFT JOIN dau.etnia ON paciente.paciente.etnia = dau.etnia.etn_id
			LEFT JOIN dau.motivo_consulta ON rau.rau.tipoconsulta = dau.motivo_consulta.mot_id
			LEFT JOIN dau.mordedura ON rau.rau.mordedura = dau.mordedura.mor_id
			LEFT JOIN dau.intoxicacion ON rau.rau.intoxicacion = dau.intoxicacion.int_id
			LEFT JOIN dau.quemado ON rau.rau.quemadura = dau.quemado.que_id
			LEFT JOIN dau.atendido_por ON rau.rau.atendidopor = dau.atendido_por.ate_atendidopor_id
			LEFT JOIN dau.condicion_ingreso ON rau.rau.condicioningreso = dau.condicion_ingreso.con_ingreso_id
			LEFT JOIN dau.derivacion ON rau.rau.deriva = dau.derivacion.der_id
			LEFT JOIN dau.pronostico ON rau.rau.pronostico = dau.pronostico.pro_pronostico_id
			LEFT JOIN dau.etilico ON rau.rau.estadoetilico = dau.etilico.eti_id
			LEFT JOIN dau.destino ON rau.rau.destino = dau.destino.des_id
			LEFT JOIN dau.tratamiento ON rau.rau.tratamiento = dau.tratamiento.tra_tratamiento_id
			LEFT JOIN dau.atencion ON rau.rau.tipoatencion = dau.atencion.ate_id
			LEFT JOIN dau.medio_llegada ON rau.rau.mediollegadapaciente = dau.medio_llegada.med_id
			LEFT JOIN dau.categorizacion ON rau.rau.categorizacion = dau.categorizacion.cat_nivel
			LEFT JOIN dau.tipo_zona ON rau.rau.tipozona = dau.tipo_zona.tip_id
			LEFT JOIN acceso.servicio ON rau.rau.sscc = acceso.servicio.idservicio
			LEFT JOIN acceso.medico ON rau.rau.alcoholemia_medico = acceso.medico.rut
			LEFT JOIN dau.sub_motivo_consulta ON rau.rau.tipoaccidente = dau.sub_motivo_consulta.sub_mot_id
			LEFT JOIN dau.atropellado_por ON rau.rau.transito_atropelladopor = dau.atropellado_por.atr_id
			LEFT JOIN dau.chocado_por ON rau.rau.transito_chocadopor = dau.chocado_por.cho_id
			LEFT JOIN dau.consultorios ON paciente.paciente.centroatencionprimaria = dau.consultorios.con_id
			LEFT JOIN cie10.cie10 ON rau.rau.idcie10 = cie10.cie10.codigoCIE";

		if ($parametros['Iddau']) {
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion.="rau.idrau = {$parametros['Iddau']}";
		}

		if ($parametros['fechaAdmisionAnio']) {
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion.="DATE_FORMAT(rau.rau.fecha, '%Y') = '{$parametros['fechaAdmisionAnio']}'";
		}

		$sql  .= $condicion;
		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR RAU<br>");
		return $datos;

	}



	function consultarHorasPaciente($objCon, $parametros){

		$condicion = "";

		$sql = "SELECT
					dau.dau.dau_id,
					dau.dau.id_paciente,
					dau.dau.idctacte,
					dau.dau.dau_inicio_atencion_fecha,
					dau.dau.dau_indicacion_egreso_fecha,
					dau.dau.dau_indicacion_egreso_aplica_fecha,
					CONCAT(
						paciente.paciente.nombres,
						' ',
						paciente.paciente.apellidopat,
						' ',
						paciente.paciente.apellidomat
					) AS nombrePaciente,
					paciente.paciente.rut,
					paciente.paciente.rut_extranjero,
					paciente.paciente.extranjero
				FROM
					dau.dau
				INNER JOIN paciente.paciente  force index (idx_idpaciente) ON dau.dau.id_paciente = paciente.paciente.id";

		if ($parametros['frm_rut']) {
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion.="paciente.paciente.rut = {$parametros['frm_rut']}";
		}
		if ($parametros['frm_nroDocumento']) {
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion.="paciente.rut_extranjero = '{$parametros['frm_nroDocumento']}' AND paciente.extranjero='S' AND paciente.rut=0";
		}
		if ($parametros['frm_numero_dau']) {
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion.="dau.dau_id = {$parametros['frm_numero_dau']}";
		}
		if ($parametros['frm_nombreCompleto']) {
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion.= "CONCAT(paciente.nombres,' ',paciente.apellidopat,' ',paciente.apellidomat) LIKE REPLACE('%{$parametros['frm_nombreCompleto']}%',' ','%')";
		}

		$condicion .= ($condicion == "") ? " WHERE " : " AND ";
		$condicion .= "dau.dau.est_id IN (4, 5, 6, 7)";

		$sql  .= $condicion;
		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR HORAS DEL PACIENTE<br>");
		return $datos;
	}



	function consultarCIE10paciente($objCon, $parametros){
			$sql = "SELECT dau.dau.dau_cierre_cie10 FROM dau.dau WHERE dau.dau_id = {$parametros['dau_id']}";
			$datos = $objCon->consultaSQL($sql,"<br>ERROR AL CIE 10 PACIENTE<br>");
			return $datos;
	}



	function consultarTipoMovimiento($objCon, $parametros){
			$sql = "SELECT * FROM dau.dau_movimiento WHERE dau.dau_movimiento.dau_id = {$parametros['dau_id']} AND dau.dau_movimiento.dau_mov_tipo = '{$parametros['dau_mov_tipo']}'";
			$datos = $objCon->consultaSQL($sql,"<br>ERROR AL OBTENER TIPO MOVIMIENTO PACIENTE<br>");
			return $datos;

	}

}
?>
