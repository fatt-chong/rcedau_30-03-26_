<?php
// error_reporting(0);
class Admision{
	function nuevaCtaCte($objCon,$idpaciente,$rut,$fecha,$servicio,$prevision,$rau,$tipoP,$convenio_pago =''){
		if($rau==''){
			$rau=0;
		}
		if($rut==''){
			$rut=0;
		}
		$tipo_cta = "U"; //CUENTA URGENCIA
		if($convenio_pago == ''){
			if($prevision >= '0' && $prevision <= '3'){
				$convenio_pago = "1";	//FONASA INSTITUCIONAL
			}
			else if($prevision == '4'){
				$convenio_pago = "3";	//PARTICULAR
			}
			else{
				$convenio_pago = "2";	//ISAPRE
			}
		}
		//TERMINA VERIFICACION DE PREVISION
		$rut = explode('-',$rut);
		$rut = $rut[0];
		//CADENA PARA CREAR NUEVA CTA CTE
		$sql = "INSERT INTO paciente.ctacte (idpaciente, paciente_id, fechaapertura, unidadorigen, conveniopago, idprevision, idrau, tipoctacte, estado, estadoPSS, canceladoPSS,tipoPaciente)
			VALUES('$idpaciente', '0', '$fecha', '$servicio','$convenio_pago', '$prevision', '$rau', '$tipo_cta', 'A', 'A', 'N','$tipoP')";
		$response = $objCon->ejecutarSQL($sql, "Error al Insertar Paciente CTA");
		$CTACTE = $objCon->lastInsertId($sql);
		return $CTACTE;
	}
	function cargarParametrosTipoAccidente($objCon,$parametros){
		$sql="SELECT
		sub_motivo_consulta.mot_id,
		sub_motivo_consulta.sub_mot_id,
		sub_motivo_consulta.sub_mot_descripcion
		FROM
		dau.sub_motivo_consulta
		WHERE sub_motivo_consulta.mot_id={$parametros['frm_motivoConsulta']}";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tipo de Accidentes<br>");
		return json_encode($datos);
	}



	function cargarParametrosInstitucion($objCon,$parametros){
		// $frm_institucion = (int) $parametros['frm_institucion'];
		$sql="SELECT
		institucion.tip_id,
		institucion.ins_id,
		institucion.ins_descripcion
		FROM
		dau.institucion
		WHERE institucion.tip_id = '{$parametros}' ";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tipo de Institucion<br>");
		return json_encode($datos);
	}



	function cargarParametrosAtropellado($objCon){
		$sql="SELECT
		institucion.tip_id,
		institucion.ins_id,
		institucion.ins_descripcion
		FROM
		dau.institucion
		WHERE institucion.tip_id = 3 AND institucion.ins_id NOT IN (22,23,24,25,26,27,28)";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tipo de Institucion<br>");
		return json_encode($datos);
	}



	function cargarTipoChoque($objCon,$parametros){
		$sql="SELECT
		dau.tipo_choque.tip_choque_id,
		dau.tipo_choque.tip_choque_descripcion
		FROM
		dau.tipo_choque";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar listarTipoChoque<br>");
		return json_encode($datos);
	}



	function cargarModerdeduras($objCon){
		$sql="SELECT
		mordedura.mor_id,
		mordedura.mor_descripcion
		FROM
		dau.mordedura
		WHERE mor_estado='A'
		";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar mordedura<br>");
		return $datos;
	}



	function cargarIntoxicacion($objCon){
		$sql="SELECT
		intoxicacion.int_id,
		intoxicacion.int_descripcion
		FROM
		dau.intoxicacion";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar intoxicacion<br>");
		return $datos;
	}



	function cargarQuemado($objCon){
		$sql="SELECT
		quemado.que_id,
		quemado.que_descripcion
		FROM
		dau.quemado";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar quemadura<br>");
		return $datos;
	}



	function agregarAdmision($objCon,$parametros){
		$sql="INSERT INTO dau.dau(est_id,
		id_paciente,
		idctacte,
		dau_admision_fecha,
		dau_admision_usuario,
		dau_paciente_domicilio,
		dau_paciente_domicilio_tipo,
		dau_paciente_edad,
		dau_paciente_prevision,
		dau_paciente_forma_pago,
		dau_motivo_consulta,
		dau_atencion,
		dau_forma_llegada,
		dau_mordedura,
		dau_intoxicacion,
		dau_quemadura,
		dau_imputado,
		dau_reanimacion,
		dau_conscripto,
		dau_motivo_descripcion,
		dau_tipo_accidente,
		dau_accidente_escolar_institucion,
		dau_accidente_escolar_numero,
		dau_accidente_escolar_nombre,
		dau_accidente_trabajo_mutualidad,
		dau_accidente_transito_tipo,
		dau_accidente_hogar_lugar,
		dau_accidente_otro_lugar,
		dau_agresion_vif,
		dau_tipo_documento,
		dau_paciente_aps,
		dau_numero_documento,
		dau_tipo_choque,
		dau_tipo_mordedura,
		dau_cierre_condicion_ingreso_id,
		dau_paciente_nn";
		if ($parametros['frm_fechaAdmision']) {$sql .= ", dau_tipo_admision";}
		if ( ! empty($parametros['dau_paciente_trasladado']) && ! is_null($parametros['dau_paciente_trasladado']) ) {$sql .= ", dau_paciente_trasladado";}
		$sql .= " , dau_paciente_critico, dau_manifestaciones, dau_constatacion_lesiones, dau_sintomasRespiratorios";
		$sql .= " , dau_viaje_epidemiologico, dau_pais_epidemiologia, dau_observacion_epidemiologica)
		VALUES('{$parametros['est_id']}',
		'{$parametros['id_paciente']}',
		'{$parametros['idctacte']}',";

		if ($parametros['frm_fechaAdmision']) {
			$sql .= "'{$parametros['frm_fechaAdmision']}',";
		}
		else{
			$sql .= "NOW(),";
		}

		$sql .= "'{$parametros['dau_admision_usuario']}',
		'{$parametros['dau_paciente_domicilio']}',
		'{$parametros['dau_paciente_domicilio_tipo']}',
		'{$parametros['dau_paciente_edad']}',
		'{$parametros['dau_paciente_prevision']}',
		'{$parametros['dau_paciente_forma_pago']}',
		'{$parametros['dau_motivo_consulta']}',
		'{$parametros['dau_atencion']}',
		'{$parametros['dau_forma_llegada']}',
		'{$parametros['dau_mordedura']}',
		'{$parametros['dau_intoxicacion']}',
		'{$parametros['dau_quemadura']}',
		'{$parametros['dau_imputado']}',
		'{$parametros['dau_reanimacion']}',
		'{$parametros['dau_conscripto']}',
		'{$parametros['dau_motivo_descripcion']}',
		'{$parametros['dau_tipo_accidente']}',
		'{$parametros['dau_accidente_escolar_institucion']}',
		'{$parametros['dau_accidente_escolar_numero']}',
		'{$parametros['dau_accidente_escolar_nombre']}',
		'{$parametros['dau_accidente_trabajo_mutualidad']}',
		'{$parametros['dau_accidente_transito_tipo']}',
		'{$parametros['dau_accidente_hogar_lugar']}',
		'{$parametros['dau_accidente_otro_lugar']}',
		'{$parametros['dau_agresion_vif']}',
		'{$parametros['id_doc_documentoDau']}',
		'{$parametros['frm_centroAtencion']}',
		'{$parametros['frm_rut']}',
		'{$parametros['frm_tipo_choque']}',
		'{$parametros['dau_tipo_mordedura']}',
		'{$parametros['dau_cierre_condicion_ingreso_id']}',
		'{$parametros['pacienteNN']}'";
		if ($parametros['frm_fechaAdmision']) {$sql .= ", '{$parametros['dau_tipo_admision']}'";}
		if ( ! empty($parametros['dau_paciente_trasladado']) && ! is_null($parametros['dau_paciente_trasladado']) ){$sql .= ", 'S'";}
		$sql .= " , '{$parametros['slc_pacienteCritico']}', '{$parametros['dau_manifestaciones']}', '{$parametros['dau_constatacion_lesiones']}', '{$parametros['sintomasRespiratorios']}'";
		$sql .= " , '{$parametros['dau_viaje_epidemiologico']}', '{$parametros['dau_pais_epidemiologia']}', '{$parametros['dau_observacion_epidemiologica']}')";
		// echo $sql;
		$response = $objCon->ejecutarSQL($sql, "Error al Insertar Paciente DAU");
		$idDau = $objCon->lastInsertId($sql);
		return $idDau;
	}



	function actualizarAdmision($objCon,$parametros){
		$sql="UPDATE dau.dau
		SET    dau_paciente_domicilio_tipo 	 		= '{$parametros['dau_paciente_domicilio_tipo']}',
		dau_paciente_prevision      		 	    = '{$parametros['dau_paciente_prevision']}',
		dau_paciente_forma_pago     		 	    = '{$parametros['dau_paciente_forma_pago']}',
		dau_motivo_consulta         		 	    = '{$parametros['dau_motivo_consulta']}',
		dau_motivo_descripcion     	                = '{$parametros['dau_motivo_descripcion']}',
		dau_forma_llegada           		 	    = '{$parametros['dau_forma_llegada']}',
		dau_mordedura               		        = '{$parametros['dau_mordedura']}',
		dau_intoxicacion            	            = '{$parametros['dau_intoxicacion']}',
		dau_quemadura                               = '{$parametros['dau_quemadura']}',
		dau_imputado                                = '{$parametros['dau_imputado']}',
		dau_reanimacion                             = '{$parametros['dau_reanimacion']}',
		dau_conscripto                              = '{$parametros['dau_conscripto']}',
		dau_tipo_accidente			                = '{$parametros['dau_tipo_accidente']}',
		dau_accidente_escolar_institucion           = '{$parametros['dau_accidente_escolar_institucion']}',
		dau_accidente_escolar_numero                = '{$parametros['dau_accidente_escolar_numero']}',
		dau_accidente_escolar_nombre                = '{$parametros['dau_accidente_escolar_nombre']}',
		dau_accidente_trabajo_mutualidad            = '{$parametros['dau_accidente_trabajo_mutualidad']}',
		dau_accidente_transito_tipo                 = '{$parametros['dau_accidente_transito_tipo']}',
		dau_accidente_hogar_lugar                   = '{$parametros['dau_accidente_hogar_lugar']}',
		dau_accidente_otro_lugar                    = '{$parametros['dau_accidente_otro_lugar']}',
		dau_agresion_vif                            = '{$parametros['dau_agresion_vif']}',
		dau_tipo_choque                             = '{$parametros['frm_tipo_choque']}',
		dau_tipo_mordedura               		    = '{$parametros['dau_tipo_mordedura']}',
		dau_paciente_critico               		    = '{$parametros['slc_pacienteCritico']}',
		dau_manifestaciones                         = '{$parametros['dau_manifestaciones']}',
		dau_constatacion_lesiones                   = '{$parametros['dau_constatacion_lesiones']}',
		dau_sintomasRespiratorios                   = '{$parametros['sintomasRespiratorios']}'
		WHERE  dau_id={$parametros['dau_id']}";
		$response = $objCon->ejecutarSQL($sql, "Error al Actualizar Admision");
	}



	function actualizarFechas($objCon,$parametros){
		$sql="UPDATE dau.dau
		SET 	 dau_cierre_administrativo_fecha      = NULL,
				 dau_indicacion_egreso_aplica_fecha   = NULL
		WHERE  dau_id={$parametros['dau_id']}";
		$response = $objCon->ejecutarSQL($sql, "Error al Actualizar Admision");

	}



	function listarDatosDau($objCon,$parametros){
		$sql="SELECT
		dau.dau.dau_id,
		dau.dau.est_id,
		dau.dau.dau_indicacion_egreso,
		dau.dau.id_paciente,
		dau.dau.idctacte,
		dau.dau.dau_admision_fecha,
		dau.dau.dau_admision_usuario,
		dau.dau.dau_paciente_domicilio,
		dau.dau.dau_paciente_domicilio_tipo,
		dau.dau.dau_paciente_edad,
		dau.dau.dau_paciente_prevision,
		dau.dau.dau_paciente_forma_pago,
		dau.dau.dau_motivo_consulta,
		dau.dau.dau_atencion,
		dau.dau.dau_forma_llegada,
		dau.dau.dau_mordedura,
		dau.dau.dau_intoxicacion,
		dau.dau.dau_quemadura,
		dau.dau.dau_reanimacion,
		dau.dau.dau_entrega_informacion,
		dau.dau.dau_se_entrega_informacion,
		dau.dau.dau_observacionEntregaInformacion,
		dau.dau.dau_conscripto,
		dau.dau.dau_tipo_accidente,
		dau.dau.dau_accidente_escolar_institucion,
		dau.dau.dau_accidente_escolar_numero,
		dau.dau.dau_accidente_escolar_nombre,
		dau.dau.dau_accidente_trabajo_mutualidad,
		dau.dau.dau_accidente_transito_tipo,
		dau.dau.dau_accidente_hogar_lugar,
		dau.dau.dau_accidente_otro_lugar,
		dau.dau.dau_agresion_vif,
		dau.dau.dau_cierre_administrativo_observacion,
		dau.dau.dau_pyxis,
		dau.dau.dau_tipo_mordedura,
		dau.dau.dau_inicio_atencion_usuario,
		dau.dau.dau_indicacion_egreso_usuario,
		dau.dau.dau_paciente_critico,
		dau.dau.dau_manifestaciones,
		dau.dau.dau_constatacion_lesiones,
		dau.dau.dau_sintomasRespiratorios,
		paciente.paciente.nombres,
		paciente.paciente.apellidopat,
		paciente.paciente.apellidomat,
		paciente.paciente.fechanac,
		paciente.paciente.sexo,
		paciente.paciente.nacionalidad,
		paciente.paciente.prevision AS idPrevision,
		paciente.prevision.prevision,
		paciente.paciente.rut,
		paciente.paciente.rut_extranjero,
		paciente.paciente.extranjero,
		paciente.paciente.fono1,
		#paciente.paciente.religion,
		paciente.paciente.etnia,
		paciente.etnia.etnia_descripcion,
		paciente.paciente.PACfono,
		dau.etnia.etn_descripcion,
		paciente.paciente.centroatencionprimaria,
		dau.consultorios.con_descripcion,
		paciente.institucion.instNombre,
		dau.atencion.ate_descripcion,
		dau.medio_llegada.med_descripcion,
		dau.dau.dau_imputado,
		dau.motivo_consulta.mot_descripcion,
		dau.tipo_choque.tip_choque_descripcion,
		dau.dau.dau_tipo_choque,
		dau.dau.dau_motivo_descripcion as motivo,
		paciente.paciente.PACafro,
		paciente.paciente.paisNacimiento,
		paciente.nacionalidadavis.NACpais,
		paciente.paciente.prais,
		paciente.region.REG_Descripcion,
		paciente.paciente.region,
		paciente.paciente.ciudad,
		paciente.ciudad.CIU_Descripcion,
		paciente.paciente.idcomuna,
		paciente.comuna.comuna,
		paciente.paciente.calle,
		paciente.paciente.numero,
		paciente.paciente.restodedireccion,
		paciente.paciente.sector_domicilio,
		paciente.sector_domiciliario.descripcion_sector_domiciliario,
		paciente.paciente.PACfonoOtros,
		paciente.paciente.direccion,
		acceso.usuario.nombreusuario AS nombreUsuario,
		CASE
		WHEN dau.dau_motivo_consulta = 1 THEN
		CASE
		WHEN sub_motivo_consulta.sub_mot_id = 1 THEN

		CONCAT(
		sub_motivo_consulta.sub_mot_descripcion,
		', ',
		dau.institucion.ins_descripcion,
		': ',
		CONCAT(
		dau.dau_accidente_escolar_nombre,
		', ',

		IF (
		dau.dau_accidente_escolar_numero = '',
		'',
		dau.dau_accidente_escolar_numero
		)
		),
		' - ',
		dau.dau.dau_motivo_descripcion
		)
		WHEN sub_motivo_consulta.sub_mot_id = 2 THEN

		CONCAT(
		sub_motivo_consulta.sub_mot_descripcion,
		', ',
		INS_2.ins_descripcion,
		' - ',
		dau.dau.dau_motivo_descripcion
		)
		WHEN sub_motivo_consulta.sub_mot_id = 3 THEN

		CONCAT(
		sub_motivo_consulta.sub_mot_descripcion,
		', ',
		dau.tipo_transito.tran_descripcion,
		' - ',
		dau.dau.dau_motivo_descripcion,
		' - ',
		IF (
		dau.dau.dau_tipo_choque != 0,
		dau.tipo_choque.tip_choque_descripcion,''
		)
		)
		WHEN sub_motivo_consulta.sub_mot_id = 4 THEN

		CONCAT(
		sub_motivo_consulta.sub_mot_descripcion,
		', ',
		INS_3.ins_descripcion,
		' - ',
		dau.dau.dau_motivo_descripcion
		)
		WHEN sub_motivo_consulta.sub_mot_id = 5 THEN

		CONCAT(
		sub_motivo_consulta.sub_mot_descripcion,
		', ',
		INS_4.ins_descripcion,
		' - ',
		dau.dau.dau_motivo_descripcion
		)
		ELSE
		dau.dau.dau_motivo_descripcion
		END
		WHEN dau.dau_motivo_consulta = 2 THEN
		dau.dau.dau_motivo_descripcion
		WHEN dau.dau_motivo_consulta = 3 THEN

		CONCAT(
		dau.dau.dau_motivo_descripcion,

		IF (
		dau.dau_agresion_vif = 'S',
		', VIF',
		''
		)
		)
		ELSE
		dau.dau.dau_motivo_descripcion
		END AS dau_motivo_descripcion,
		dau.dau.dau_cierre_administrativo_fecha,
		paciente.paciente.email,
		dau.institucion.ins_descripcion,
		paciente.nacionalidadavis.NACdescripcion,
		paciente.nacionalidadavis.NACpais,
		mordedura.mor_descripcion,
		intoxicacion.int_descripcion,
		quemado.que_descripcion,
		dau_viaje_epidemiologico,
		dau_pais_epidemiologia,
		dau_observacion_epidemiologica
		#religion.rlg_descripcion AS religion_descripcion
		FROM
		dau.dau
		LEFT JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
		LEFT JOIN paciente.prevision ON paciente.prevision.id = dau.dau.dau_paciente_prevision
		LEFT JOIN dau.etnia ON paciente.paciente.etnia = dau.etnia.etn_id
		LEFT JOIN dau.consultorios ON dau.consultorios.con_id = paciente.paciente.centroatencionprimaria
		LEFT JOIN paciente.institucion ON paciente.institucion.instCod = dau.dau.dau_paciente_forma_pago
		LEFT JOIN dau.atencion ON dau.dau.dau_atencion = dau.atencion.ate_id
		LEFT JOIN dau.medio_llegada ON dau.dau.dau_forma_llegada = dau.medio_llegada.med_id
		LEFT JOIN dau.motivo_consulta ON dau.motivo_consulta.mot_id = dau.dau.dau_motivo_consulta
		LEFT JOIN acceso.usuario ON dau.dau.dau_admision_usuario = acceso.usuario.idusuario




		LEFT JOIN dau.institucion ON dau.dau.dau_tipo_accidente = dau.institucion.tip_id
		AND dau.dau.dau_accidente_escolar_institucion = dau.institucion.ins_id
		LEFT JOIN dau.institucion INS_2 ON dau.dau.dau_tipo_accidente = INS_2.tip_id
		AND dau.dau.dau_accidente_trabajo_mutualidad = INS_2.ins_id
		LEFT JOIN dau.institucion INS_3 ON dau.dau.dau_tipo_accidente = INS_3.tip_id
		AND dau.dau.dau_accidente_hogar_lugar = INS_3.ins_id
		LEFT JOIN dau.institucion INS_4 ON dau.dau.dau_tipo_accidente = INS_4.tip_id
		AND dau.dau.dau_accidente_otro_lugar = INS_4.ins_id
		LEFT JOIN paciente.nacionalidadavis ON paciente.nacionalidadavis.NACcodigo = paciente.paciente.nacionalidad
		LEFT JOIN dau.sub_motivo_consulta ON dau.dau.dau_tipo_accidente = dau.sub_motivo_consulta.sub_mot_id
		LEFT JOIN dau.tipo_transito ON dau.dau.dau_accidente_transito_tipo = dau.tipo_transito.tran_id
		LEFT JOIN dau.mordedura ON dau.dau.dau_mordedura = mordedura.mor_id
		LEFT JOIN dau.intoxicacion ON dau.dau.dau_intoxicacion = intoxicacion.int_id
		LEFT JOIN dau.quemado ON dau.dau.dau_quemadura = quemado.que_id
		LEFT JOIN dau.tipo_choque ON dau.tipo_choque.tip_choque_id = dau.dau.dau_tipo_choque

		LEFT JOIN paciente.etnia  ON paciente.etnia.etnia_id = paciente.paciente.etnia
		LEFT JOIN paciente.region ON paciente.region.REG_Id = paciente.paciente.region
		LEFT JOIN paciente.ciudad ON paciente.ciudad.CIU_Id = paciente.paciente.ciudad
		LEFT JOIN paciente.comuna ON paciente.comuna.id = paciente.paciente.idcomuna
		#LEFT JOIN paciente.religion on paciente.religion.rlg_id = paciente.paciente.religion
		LEFT JOIN paciente.sector_domiciliario ON paciente.sector_domiciliario.id_sector_domiciliario = paciente.paciente.sector_domicilio

		WHERE dau_id={$parametros['dau_id']}";
		// print_r("<pre>"); print_r($sql); print_r("</pre>");
		// die();
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla DAU <br>");
		return $datos;
	}



	function listarDatosDauIndicePaciente($objCon,$parametros){
		// $objCon->db_select("paciente");
		$sql="SELECT
				paciente.paciente.id,
				paciente.paciente.rut,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,
				paciente.paciente.fechanac,
				paciente.paciente.sexo,
				paciente.paciente.direccion,
				paciente.paciente.fono1,
				paciente.paciente.rut_extranjero,
				paciente.paciente.PACfono,
				paciente.prevision.prevision,
				dau.consultorios.con_descripcion,
				dau.etnia.etn_descripcion,
				paciente.institucion.instNombre,
				paciente.paciente.etnia,
				paciente.paciente.centroatencionprimaria,
				paciente.paciente.email,
				paciente.paciente.prevision,
				paciente.paciente.conveniopago,
				paciente.paciente.act_fonasa_fecha,
				paciente.paciente.act_fonasa_folio,
				paciente.paciente.act_fonasa_hrs,
				paciente.paciente.nacionalidad,
				paciente.paciente.paisNacimiento,
				paciente.nacionalidadavis.NACpais,
				paciente.sector_domicilio
				FROM
				paciente.paciente
				LEFT JOIN paciente.prevision ON paciente.paciente.prevision = paciente.prevision.id
				LEFT JOIN dau.consultorios ON dau.consultorios.con_id = paciente.paciente.centroatencionprimaria
				LEFT JOIN dau.etnia ON paciente.paciente.etnia = dau.etnia.etn_id
				LEFT JOIN paciente.institucion ON paciente.paciente.conveniopago = paciente.institucion.instCod
				LEFT JOIN paciente.nacionalidadavis ON paciente.paciente.paisNacimiento = paciente.nacionalidadavis.NACcodigo
		WHERE paciente.paciente.id='{$parametros['id_paciente']}'";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla DAU <br>");
		return $datos;
	}



	function ActualizarCtaCorriente($objCon,$parametros){
		  $sql="UPDATE dau.dau
		SET    idctacte = '{$parametros['ctaCte']}',
		dau_cierre_administrativo_fecha  = NULL,
		dau_indicacion_egreso_aplica_fecha = NULL
		WHERE  dau.dau_id={$parametros['dau_id']}";
		// echo $sql;
		$response = $objCon->ejecutarSQL($sql, "Error al Actualizar CTACTE");
	}



	function listarDatosBuscador($objCon, $parametros){
		$condicion = '';
		$sql="SELECT
		paciente.paciente.id,
		dau.dau.dau_id,
		dau.dau.id_paciente,
		dau.dau.dau_admision_fecha,
		dau.estado.est_id,
		dau.estado.est_descripcion,
		paciente.paciente.rut,
		paciente.paciente.rut_extranjero,
		paciente.paciente.extranjero,
		paciente.paciente.nombres,
		paciente.paciente.apellidopat,
		paciente.paciente.apellidomat,
		dau.dau.dau_atencion,
		dau.dau.dau_motivo_consulta,
		dau.motivo_consulta.mot_descripcion,
		dau.atencion.ate_descripcion,
		dau.dau.idctacte,
		paciente.doc_extranjero.nombre_doc_extranjero,
		paciente.paciente.id_doc_extranjero
		FROM
		dau.dau
		INNER JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
		LEFT JOIN dau.estado ON dau.dau.est_id = dau.estado.est_id
		LEFT JOIN dau.motivo_consulta ON dau.motivo_consulta.mot_id = dau.dau.dau_motivo_consulta
		LEFT JOIN dau.atencion ON dau.dau.dau_atencion = dau.atencion.ate_id
		LEFT JOIN paciente.doc_extranjero ON paciente.paciente.id_doc_extranjero = paciente.doc_extranjero.id_doc_extranjero";

		if ($parametros['id_paciente']) {
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion.="dau.id_paciente = {$parametros['id_paciente']}";
		}

		if ($parametros['frm_rut']) {
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion.="paciente.rut = {$parametros['frm_rut']}";
		}else{
			if ($parametros['frm_nroDocumento']) {
				$condicion .= ($condicion == "") ? " WHERE " : " AND ";
				$condicion.="paciente.rut_extranjero = '{$parametros['frm_nroDocumento']}'";
			}
		}

		if ($parametros['estado']) {
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion.="dau.estado.est_id = {$parametros['estado']}";
		}

		if ($parametros['frm_dau']) {
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion.="dau.dau.dau_id = {$parametros['frm_dau']}";
		}

		if ($parametros['frm_ctacte']) {
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion.="dau.dau.idctacte = {$parametros['frm_ctacte']}";
		}

		if ($parametros['frm_nombreCompleto']) {
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion.= "CONCAT(paciente.nombres,' ',paciente.apellidopat,' ',paciente.apellidomat) LIKE REPLACE('%{$parametros['frm_nombreCompleto']}%',' ','%')";
		}

		if ($parametros['frm_estados']) {
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion.="dau.dau.est_id = {$parametros['frm_estados']}";
		}

		if ($parametros['frm_motivo']) {
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion.="dau.dau.dau_motivo_consulta = {$parametros['frm_motivo']}";
		}

		if ($parametros['frm_atencion']) {
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion.="dau.dau.dau_atencion = {$parametros['frm_atencion']}";
		}

		$sql  .= $condicion;
		$sql  .= " ORDER BY dau_id ASC LIMIT 5000";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
		return $datos;
	}



	function listarDatosBuscador_verificar($objCon, $parametros){
		$condicion = '';
		$sql="SELECT
		paciente.paciente.id,
		dau.dau.dau_id,
		dau.dau.id_paciente,
		dau.estado.est_id,
		dau.estado.est_descripcion,
		paciente.paciente.rut,
		paciente.paciente.rut_extranjero,
		paciente.paciente.extranjero,
		paciente.paciente.nombres,
		paciente.paciente.apellidopat,
		paciente.paciente.apellidomat,
		dau.dau.dau_atencion,
		dau.dau.dau_motivo_consulta,
		dau.motivo_consulta.mot_descripcion,
		dau.atencion.ate_descripcion,
		dau.dau.idctacte,
		paciente.doc_extranjero.nombre_doc_extranjero,
		paciente.paciente.id_doc_extranjero
		FROM
		dau.dau
		LEFT JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
		LEFT JOIN dau.estado ON dau.dau.est_id = dau.estado.est_id
		LEFT JOIN dau.motivo_consulta ON dau.motivo_consulta.mot_id = dau.dau.dau_motivo_consulta
		LEFT JOIN dau.atencion ON dau.dau.dau_atencion = dau.atencion.ate_id
		LEFT JOIN paciente.doc_extranjero ON paciente.paciente.id_doc_extranjero = paciente.doc_extranjero.id_doc_extranjero";

		$condicion.=" WHERE dau.id_paciente = {$parametros['id_paciente']}";
	
		$sql  .= $condicion;
		$sql  .= " ORDER BY dau.dau.dau_id DESC LIMIT 1";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
		return $datos;
	}



	function listarAtencion($objCon){
		$sql="SELECT
		atencion.ate_id,
		atencion.ate_descripcion
		FROM
		dau.atencion";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Atención<br>");
		return $datos;
	}



	function listarTransito($objCon){
		$sql="SELECT
		tipo_transito.tran_id,
		tipo_transito.tran_descripcion
		FROM
		dau.tipo_transito";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Transito<br>");
		return $datos;
	}



	function listarEtnia($objCon){
		$sql="SELECT
		paciente.etnia.etnia_id,
		paciente.etnia.etnia_descripcion
		FROM
		paciente.etnia";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Etnia<br>");
		return $datos;
	}



	function listarPaisNacimiento($objCon){
		$sql="SELECT
		nacionalidadavis.NACcodigo,
		nacionalidadavis.NACdescripcion,
		nacionalidadavis.NACpais
		FROM
		paciente.nacionalidadavis";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Transito<br>");
		return $datos;
	}



	function listarConsultoriosAPS($objCon, $parametros){
		$sql="SELECT *
		FROM
		dau.consultorios
		WHERE
			con_id = '{$parametros['con_id']}'  ";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Transito<br>");
		return $datos;
	}
	function listarConsultorios($objCon, $tipoFiltro = 'filtroAdmisionDatosLocalizacion'){
		$sql="SELECT
		consultorios.con_id,
		consultorios.con_descripcion
		FROM
		dau.consultorios
		WHERE
			{$tipoFiltro} = 'S'
		ORDER BY
			CASE WHEN BINARY consultorios.con_id = 35 OR consultorios.con_id = 36 OR consultorios.con_id = 37 THEN 1 ELSE 0 END ";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Transito<br>");
		return $datos;
	}


	function listarDatosBuscadorInDAU($objCon){
		$sql="SELECT
		paciente.paciente.id,
		dau.dau.dau_id,
		dau.dau.id_paciente,
		dau.dau.dau_admision_fecha,
		dau.estado.est_id,
		dau.estado.est_descripcion,
		paciente.paciente.rut,
		paciente.paciente.rut_extranjero,
		paciente.paciente.extranjero,
		paciente.paciente.nombres,
		paciente.paciente.apellidopat,
		paciente.paciente.apellidomat,
		dau.dau.dau_atencion,
		dau.dau.dau_motivo_consulta,
		dau.motivo_consulta.mot_descripcion,
		dau.atencion.ate_descripcion,
		dau.dau.idctacte,
		paciente.doc_extranjero.nombre_doc_extranjero,
		paciente.paciente.id_doc_extranjero
		FROM
		dau.dau
		LEFT JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
		LEFT JOIN dau.estado ON dau.dau.est_id = dau.estado.est_id
		LEFT JOIN dau.motivo_consulta ON dau.motivo_consulta.mot_id = dau.dau.dau_motivo_consulta
		LEFT JOIN dau.atencion ON dau.dau.dau_atencion = dau.atencion.ate_id
		LEFT JOIN paciente.doc_extranjero ON paciente.paciente.id_doc_extranjero = paciente.doc_extranjero.id_doc_extranjero
		WHERE dau.estado.est_id IN (1,2,3,4,8)";

		$sql  .= " ORDER BY dau_id DESC LIMIT 5000";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
		return $datos;
	}



	function addPrestacionAdmisionPaciente($objCon, $parametros){
		 $sql = "INSERT INTO recauda.detalle_prestacion(
					det_pre_cta_cte,
					det_pre_usuario,
					det_pre_codigo,
					det_pre_rut_paciente,
					det_pre_fecha,
					det_pre_cantidad,
					det_pre_cod_sscc,
					det_pre_valor_unit,
					det_pre_item_presup)
				VALUES (
					'{$parametros['det_pre_cta_cte']}',
					'{$parametros['det_pre_usuario']}',
					'{$parametros['det_pre_codigo']}',
					'{$parametros['det_pre_rut_paciente']}',
					NOW(),
					'{$parametros['det_pre_cantidad']}',
					'{$parametros['det_pre_cod_sscc']}',
					'{$parametros['det_pre_valor_unit']}',
				    '4310104')";
		// $sql;
		$response = $objCon->ejecutarSQL($sql, "Error al Insertar Paciente DAU");
		return $objCon->lastInsertId();
	}



	function listarTipoChoque($objCon){
		$sql="SELECT
		dau.tipo_choque.tip_choque_descripcion,
		dau.tipo_choque.tip_choque_id
		FROM
		dau.tipo_choque";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar listarTipoChoque<br>");
		return $datos;
	}



	function cargarTipoModerdeduras($objCon){
		$sql="SELECT
		mordedura_tipo.tip_mor_id,
		mordedura_tipo.tip_mor_descripcion
		FROM
		dau.mordedura_tipo
		";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar mordedura<br>");
		return $datos;
	}



	function ingresarPacienteDerivado ( $objCon, $parametros ) {

		$sql = "INSERT INTO
					dau.paciente_derivado
					(
						idDau,
						idEstablecimientoRedSalud,
						nombreOtroEstablecimiento,
						usuarioInserta,
						fechaInserta
					)
				VALUES
				(
					'{$parametros['idDau']}',
					'{$parametros['idEstablecimientoRedSalud']}',
					'{$parametros['nombreOtroEstablecimiento']}',
					'{$parametros['usuarioInserta']}',
					NOW()
				)";

		$objCon->ejecutarSQL($sql, "Error al Insertar Paciente Derivado");

	}



	function obtenerInfoPacienteDerivadoSegunDau ( $objCon, $idDau ) {

		$sql = "SELECT
					dau.paciente_derivado.*
				FROM
					dau.paciente_derivado
				WHERE
					dau.paciente_derivado.idDau = '{$idDau}' ";

		$datos = $objCon->consultaSQL($sql,"<br>Error al obtener Información Paciente Derivado<br>");

		return $datos[0];

	}



	function eliminarPacienteDerivadoSegunDau ( $objCon, $idDau ) {

		$sql = "DELETE FROM
					dau.paciente_derivado
				WHERE
					dau.paciente_derivado.idDau = '{$idDau}' ";

		$objCon->ejecutarSQL($sql, "Error al Eliminar Paciente Derivado");

	}



	function obtenerDatosDauCerrado ( $objCon, $idDau ) {


		$sql = "SELECT
					id_paciente,
					dau_paciente_domicilio,
					dau_paciente_domicilio_tipo,
					dau_paciente_edad,
					dau_paciente_prevision,
					dau_paciente_forma_pago,
					dau_motivo_consulta,
					dau_atencion,
					dau_forma_llegada,
					dau_mordedura,
					dau_intoxicacion,
					dau_quemadura,
					dau_imputado,
					dau_reanimacion,
					dau_conscripto,
					dau_motivo_descripcion,
					dau_tipo_accidente,
					dau_accidente_escolar_institucion,
					dau_accidente_escolar_numero,
					dau_accidente_escolar_nombre,
					dau_accidente_trabajo_mutualidad,
					dau_accidente_transito_tipo,
					dau_accidente_hogar_lugar,
					dau_accidente_otro_lugar,
					dau_agresion_vif,
					dau_tipo_documento,
					dau_paciente_aps,
					dau_numero_documento,
					dau_tipo_choque,
					dau_tipo_mordedura,
					dau_cierre_condicion_ingreso_id,
					dau_tipo_admision,
					dau.dau_tiene_indicacion.ind_egr_id
				FROM
					dau.dau
				INNER JOIN
					dau.dau_tiene_indicacion ON dau.dau_tiene_indicacion.dau_id = dau.dau.dau_id
				WHERE
					dau.dau.dau_id = '{$idDau}' ";

		$datos = $objCon->consultaSQL($sql,"<br>Error al obtener Información DAU Cerrado (Para Traslado)<br>");

		return $datos[0];

	}



	function ingresarDatosContingencia ($objCon, $parametros) {

		$condicion = "";

		$sql = "UPDATE
					dau.dau
				SET
					dau.dau.dau_contingencia                     = 'S',
					dau.dau.dau_admision_fecha                   = NULL,
					dau.dau.dau_admision_usuario                 = NULL,
					dau.dau.dau_categorizacion                   = NULL,
					dau.dau.dau_categorizacion_actual            = NULL,
					dau.dau.dau_categorizacion_fecha             = NULL,
					dau.dau.dau_categorizacion_actual_fecha      = NULL,
					dau.dau.dau_categorizacion_usuario           = NULL,
					dau.dau.dau_categorizacion_actual_usuario    = NULL,
					dau.dau.dau_ingreso_sala_fecha               = NULL,
					dau.dau.dau_ingreso_sala_usuario             = NULL,
					dau.dau.dau_inicio_atencion_fecha            = NULL,
					dau.dau.dau_inicio_atencion_usuario          = NULL,
					dau.dau.dau_indicacion_egreso_fecha          = NULL,
					dau.dau.dau_indicacion_egreso_usuario        = NULL,
					dau.dau.dau_indicacion_egreso_aplica_fecha   = NULL,
					dau.dau.dau_indicacion_egreso_aplica_usuario = NULL,
					dau.dau.dau_cierre_administrativo_usuario    = NULL,
					dau.dau.dau_cierre_administrativo_fecha      = NULL,
					dau.dau.dau_cierre_fecha_final               = NULL
				";

		     	if ( ! is_null($parametros['frm_contingenciaFechaAdmision']) && ! empty($parametros['frm_contingenciaFechaAdmision']) ) {

					$fecha = date("Y-m-d", strtotime($parametros['frm_contingenciaFechaAdmision'])).' '.$parametros["frm_contingenciaTiempoAdmision"];

					$usuario = 'dauContingencia';

					$condicion .= ", dau.dau.dau_admision_fecha = '{$fecha}' ";

					$condicion .= ", dau.dau.dau_admision_usuario = '{$usuario}' ";

				}

				if ( ! is_null($parametros['slc_contingenciaCategorizacion']) && ! empty($parametros['slc_contingenciaCategorizacion']) ) {

					$condicion .= ", dau.dau.dau_categorizacion = '{$parametros["slc_contingenciaCategorizacion"]}' ";

					$condicion .= ", dau.dau.dau_categorizacion_actual = '{$parametros["slc_contingenciaCategorizacion"]}' ";

				}

				if ( ! is_null($parametros['frm_contingenciaFechaCategorizacion']) && ! empty($parametros['frm_contingenciaFechaCategorizacion']) ) {

					$fecha = date("Y-m-d", strtotime($parametros['frm_contingenciaFechaCategorizacion'])).' '.$parametros["frm_contingenciaTiempoCategorizacion"];

					$usuario = 'dauContingencia';

					$condicion .= ", dau.dau.dau_categorizacion_fecha = '{$fecha}' ";

					$condicion .= ", dau.dau.dau_categorizacion_actual_fecha = '{$fecha}' ";

					$condicion .= ", dau.dau.dau_categorizacion_usuario  = '{$usuario}' ";

					$condicion .= ", dau.dau.dau_categorizacion_actual_usuario  = '{$usuario}' ";

				}

				if ( ! is_null($parametros['frm_contingenciaFechaIngresoBox']) && ! empty($parametros['frm_contingenciaFechaIngresoBox']) ) {

					$fecha = date("Y-m-d", strtotime($parametros['frm_contingenciaFechaIngresoBox'])).' '.$parametros["frm_contingenciaTiempoIngresoBox"];

					$usuario = 'dauContingencia';

					$condicion .= ", dau.dau.dau_ingreso_sala_fecha = '{$fecha}' ";

					$condicion .= ", dau.dau.dau_ingreso_sala_usuario = '{$usuario}' ";

				}

				if ( ! is_null($parametros['frm_contingenciaFechaInicioAtencion']) && ! empty($parametros['frm_contingenciaFechaInicioAtencion']) ) {

					$fecha = date("Y-m-d", strtotime($parametros['frm_contingenciaFechaInicioAtencion'])).' '.$parametros["frm_contingenciaTiempoInicioAtencion"];

					$usuario = 'dauContingencia';

					$condicion .= " dau.dau.dau_inicio_atencion_fecha = '{$fecha}' ";

					$condicion .= ", dau.dau.dau_inicio_atencion_usuario = '{$usuario}' ";

				}

				if ( ! is_null($parametros['frm_contingenciaFechaIndicacionEgreso']) && ! empty($parametros['frm_contingenciaFechaIndicacionEgreso']) ) {

					$fecha = date("Y-m-d", strtotime($parametros['frm_contingenciaFechaIndicacionEgreso'])).' '.$parametros["frm_contingenciaTiempoIndicacionEgreso"];

					$usuario = 'dauContingencia';

					$condicion .= ", dau.dau.dau_indicacion_egreso_fecha = '{$fecha}' ";

					$condicion .= ", dau.dau.dau_indicacion_egreso_usuario = '{$usuario}' ";

				}

				if ( ! is_null($parametros['frm_contingenciaFechaNEA']) && ! empty($parametros['frm_contingenciaFechaNEA']) ) {

					$fecha = date("Y-m-d", strtotime($parametros['frm_contingenciaFechaNEA'])).' '.$parametros["frm_contingenciaTiempoNEA"];

					$usuario = 'dauContingencia';

					$condicion .= ", dau.dau.dau_cierre_administrativo_fecha = '{$fecha}' ";

					$condicion .= ", dau.dau.dau_cierre_administrativo_usuario = '{$usuario}' ";

					$condicion .= ", dau.dau.dau_cierre_fecha_final = '{$fecha}' ";

				}

				if ( ! is_null($parametros['frm_contingenciaFechaCierre']) && ! empty($parametros['frm_contingenciaFechaCierre']) ) {

					$fecha = date("Y-m-d", strtotime($parametros['frm_contingenciaFechaCierre'])).' '.$parametros["frm_contingenciaTiempoCierre"];

					$usuario = 'dauContingencia';

					$condicion .= ", dau.dau.dau_indicacion_egreso_aplica_fecha = '{$fecha}' ";

					$condicion .= ", dau.dau.dau_indicacion_egreso_aplica_usuario = '{$usuario}' ";

					$condicion .= ", dau.dau.dau_cierre_fecha_final = '{$fecha}' ";

				}

		$sql .= $condicion;

		$sql .= " WHERE dau.dau.dau_id = '{$parametros['idDau']}' ";

		$objCon->ejecutarSQL($sql, "ERROR ACTUALIZAR DATOS CONTINGENCIA");

	}



	function cambiarEstadoDAUContingencia ( $objCon, $parametros ) {

		$sql = "UPDATE
					dau.dau
				SET
					dau.dau.est_id = '{$parametros['estado']}'
				WHERE
					dau.dau.dau_id = '{$parametros['idDau']}'
				";

		$objCon->ejecutarSQL($sql, "ERROR ACTUALIZAR ESTADO DAU CONTINGENCIA");

	}

}
?>