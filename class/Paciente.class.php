<?php
	class Paciente{

		function obtenerInformacionPaciente ( $objCon, $idPaciente ) {
			$sql = "SELECT
						paciente.paciente.*,
						paciente.nacionalidadavis.NACdescripcion
					FROM
						paciente.paciente
					LEFT JOIN
						paciente.nacionalidadavis ON paciente.nacionalidadavis.NACcodigo = paciente.paciente.nacionalidad
					WHERE
						paciente.paciente.id = '{$idPaciente}'
					";
			$resultado=$objCon->consultaSQL($sql,"<br>ERROR Obtener Información de Paciente<br>");
			return $resultado[0];

		}
	function listarPaciente($objCon, $parametros){
		$condicion 	= "";
		$sql="SELECT
				paciente.id,
				paciente.conruralidad,
				paciente.rut,
				paciente.nombres,
				paciente.apellidopat,
				paciente.apellidomat,
				paciente.fechanac,
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
				paciente.estadocivil,
				paciente.etnia,
				paciente.religion,
				paciente.fechaexpiracion,
				paciente.tipo_id,
				paciente.tipo_paciente,
				IF(paciente.nacionalidad = '', 'NOI', paciente.nacionalidad) AS nacionalidad,
				paciente.ocupacion,
				paciente.lugartrabajo,
				paciente.direcciontrabajo,
				paciente.comunatrabajo,
				paciente.nombrespadre,
				paciente.apellidopatpadre,
				paciente.apellidomatpadre,
				paciente.nombresmadre,
				paciente.apellidopatmadre,
				paciente.apellidomatmadre,
				paciente.nombrescontacto,
				paciente.apellidopatcontacto,
				paciente.apellidomatcontacto,
				paciente.tipocontacto,
				paciente.fechaingreso,
				paciente.telefonocontacto,
				paciente.ficha_despachada,
				paciente.revisado,
				paciente.tipodoc,
				paciente.prais,
				paciente.funcionario,
				paciente.conveniopago,
				paciente.bloqueado,
				paciente.duplicado,
				paciente.extranjero,
				paciente.rut_extranjero,
				paciente.idusuario,
				paciente.tipopaciente,
				paciente.hospitalizado,
				paciente.id_trakcare,
				paciente.idcomunacodigo,
				paciente.idprovincia,
				paciente.idregion,
				paciente.id_doc_extranjero,
				paciente.fallecido,
				paciente.polimetales,
				paciente.act_fonasa_fecha,
				paciente.act_fonasa_hrs,
				paciente.act_fonasa_folio,
				paciente.act_fonasa_motivo,
				paciente.catocupacional,
				paciente.nivintitucional,
				paciente.nombreocupacional,
				paciente.ficNroOrdenamiento,
				paciente.tipoDocAvis,
				paciente.idPacAvis,
				paciente.PACdireccion,
				paciente.PACpoblacion,
				paciente.PACnumeroVivienda,
				paciente.PACnacionalidadDesc,
				paciente.PACnombreSocial,
				paciente.PACcodigoOcupacion,
				paciente.PACfono,
				paciente.PACcelular,
				paciente.PACfonoOtros,
				paciente.PACfechaUpdateHjnc,
				paciente.PACfechaUpdateAvis,
				paciente.PACafro,
				IF(paciente.paisNacimiento = '', '00', paciente.paisNacimiento) AS paisNacimiento,
				doc_extranjero.nombre_doc_extranjero,
				paciente.ciudad,
				paciente.region,
				paciente.calle,
				paciente.numero,
				paciente.restodedireccion,
				paciente.sector_domicilio
			FROM
			paciente.paciente
			LEFT JOIN paciente.doc_extranjero ON doc_extranjero.id_doc_extranjero = paciente.id_doc_extranjero";

				if ($parametros['frm_id_paciente']) {
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion.="paciente.id = {$parametros['frm_id_paciente']}";
				}

				if ($parametros['frm_rut']) {
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion.="paciente.rut = {$parametros['frm_rut']}";
				}

				if ($parametros['frm_documento']) {	//Validar el tipo de Documento Extranjero
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion.="paciente.id_doc_extranjero = {$parametros['frm_documento']}";
				}

				if ($parametros['frm_nroDocumento']) {	//Validar el numero de Documento Extranjero
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion.="paciente.rut_extranjero = '{$parametros['frm_nroDocumento']}'";
				}

				if ($parametros['frm_nroFicha']) {
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion.="paciente.nroficha = {$parametros['frm_nroFicha']}";
				}

				if ($parametros['frm_APaterno']) {
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion .="paciente.apellidopat LIKE '%{$parametros['frm_APaterno']}%'";
					/*$condicion .="LIMIT 2751";	*/
				}

				if ($parametros['frm_AMaterno']) {
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion .="paciente.apellidomat  LIKE '%{$parametros['frm_AMaterno']}%'";
					/*$condicion .="LIMIT 2751";	*/
				}

				if ($parametros['frm_nombresDos']) {
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion .="paciente.nombres LIKE '%{$parametros['frm_nombresDos']}%'";
					/*$condicion .="LIMIT 2751";*/
				}

				$sql  .= $condicion;
				$sql  .= " ORDER BY id DESC LIMIT 5000";
				// echo $sql;
				$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
				return $datos;
		}

		function listarPacienteExterno($objCon, $parametros){
			$condicion 	= "";
		$sql="SELECT
				paciente.id,
				paciente.rut,
				paciente.nombres,
				paciente.apellidopat,
				paciente.apellidomat,
				paciente.fechanac,
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
				paciente.estadocivil,
				paciente.etnia,
				paciente.religion,
				paciente.fechaexpiracion,
				paciente.tipo_id,
				paciente.tipo_paciente,
				IF(paciente.nacionalidad = ' ', NULL, paciente.nacionalidad) AS nacionalidad,
				paciente.ocupacion,
				paciente.lugartrabajo,
				paciente.direcciontrabajo,
				paciente.comunatrabajo,
				paciente.nombrespadre,
				paciente.apellidopatpadre,
				paciente.apellidomatpadre,
				paciente.nombresmadre,
				paciente.apellidopatmadre,
				paciente.apellidomatmadre,
				paciente.nombrescontacto,
				paciente.apellidopatcontacto,
				paciente.apellidomatcontacto,
				paciente.tipocontacto,
				paciente.fechaingreso,
				paciente.telefonocontacto,
				paciente.ficha_despachada,
				paciente.conruralidad,
				paciente.revisado,
				paciente.tipodoc,
				paciente.prais,
				paciente.funcionario,
				paciente.conveniopago,
				paciente.bloqueado,
				paciente.duplicado,
				paciente.extranjero,
				paciente.rut_extranjero,
				paciente.idusuario,
				paciente.tipopaciente,
				paciente.hospitalizado,
				paciente.id_trakcare,
				paciente.idcomunacodigo,
				paciente.idprovincia,
				paciente.idregion,
				paciente.id_doc_extranjero,
				paciente.fallecido,
				paciente.polimetales,
				paciente.act_fonasa_fecha,
				paciente.act_fonasa_hrs,
				paciente.act_fonasa_folio,
				paciente.act_fonasa_motivo,
				paciente.catocupacional,
				paciente.nivintitucional,
				paciente.nombreocupacional,
				paciente.ficNroOrdenamiento,
				paciente.tipoDocAvis,
				paciente.idPacAvis,
				paciente.PACdireccion,
				paciente.PACpoblacion,
				paciente.PACnumeroVivienda,
				paciente.PACnacionalidadDesc,
				paciente.PACnombreSocial,
				paciente.PACcodigoOcupacion,
				paciente.PACfono,
				paciente.PACcelular,
				paciente.PACfonoOtros,
				paciente.PACfechaUpdateHjnc,
				paciente.PACfechaUpdateAvis,
				paciente.PACafro,
				IF(paciente.paisNacimiento = ' ', NULL, paciente.paisNacimiento) AS paisNacimiento,
				doc_extranjero.nombre_doc_extranjero
			FROM
			paciente
			LEFT JOIN doc_extranjero ON doc_extranjero.id_doc_extranjero = paciente.id_doc_extranjero";
				

				if ($parametros['frm_rut']) {
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion.="paciente.rut = {$parametros['frm_rut']}";
				}

				if ($parametros['frm_documento']) {	//Validar el tipo de Documento Extranjero
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion.="paciente.id_doc_extranjero = {$parametros['frm_documento']}";
				}

				if ($parametros['frm_documentoExterno']) {	//Validar el numero de Documento Extranjero
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion.="paciente.rut_extranjero = '{$parametros['frm_documentoExterno']}'";
				}

				if ($parametros['frm_nroFichaExterno']) {
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion.="paciente.nroficha = {$parametros['frm_nroFichaExterno']}";
				}

				if ($parametros['frm_APaternoExterno']) {
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion .="paciente.apellidopat LIKE '%{$parametros['frm_APaternoExterno']}%'";
					/*$condicion .="LIMIT 2751";	*/
				}

				if ($parametros['frm_AMaternoExterno']) {
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion .="paciente.apellidomat  LIKE '%{$parametros['frm_AMaternoExterno']}%'";
					/*$condicion .="LIMIT 2751";	*/
				}

				if ($parametros['frm_nombresDosExterno']) {
					$condicion .= ($condicion == "") ? " WHERE " : " AND ";
					$condicion .="paciente.nombres LIKE '%{$parametros['frm_nombresDosExterno']}%'";
					/*$condicion .="LIMIT 2751";*/
				}

				$sql  .= $condicion;
				$sql  .= " ORDER BY id DESC LIMIT 5000";
				$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
				return $datos;
		}

		function grabar_paciente($objCon, $parametros){
			// require_once("Log_Paciente.class.php"); $objLog = new Log_Paciente;
			$sql="INSERT INTO paciente( extranjero,
											 id_doc_extranjero,
											 rut_extranjero,
											 rut,
											 nroficha,
											 nombres,
											 apellidopat,
											 apellidomat,
											 direccion,
											 idcomuna,
											 centroatencionprimaria,
											 fechanac,
											 sexo,
											 estadocivil,
											 email,
											 fono1,
											 fono2,
											 PACfono,
											 PACcelular,
											 etnia,
											 nacionalidad,
											 PACafro,
											 prevision,
											 conveniopago,
											 ocupacion,
											 lugartrabajo,
											 direcciontrabajo,
											 fono3,
											 comunatrabajo,
											 nombrespadre,
											 apellidopatpadre,
											 apellidomatpadre,
											 nombresmadre,
											 apellidopatmadre,
											 apellidomatmadre,
											 nombrescontacto,
											 apellidopatcontacto,
											 apellidomatcontacto,
											 tipocontacto,
											 telefonocontacto,
											 idusuario,
											 act_fonasa_fecha,
											 act_fonasa_hrs,
											 act_fonasa_folio,
											 prais,
											 paisNacimiento,
											 dv,
											 calle,
											 numero,
											 restodedireccion,
											 ciudad,
											 region,
											 conruralidad,
											 sector_domicilio)
								VALUES('{$parametros['frm_extranjero']}',
									   '{$parametros['frm_documento']}',
									   '{$parametros['frm_nroDocumento']}',
									   '{$parametros['frm_rut_pac']}',
									   '{$parametros['frm_nroFicha']}',
									   '{$parametros['frm_nombres']}',
									   '{$parametros['frm_AP']}',
									   '{$parametros['frm_AM']}',
									   '{$parametros['frm_direccion']}',
									   '{$parametros['frm_comunaIndicePaciente']}',
									   '{$parametros['frm_centroAtencion']}',
									   '{$parametros['frm_Naciemito']}',
									   '{$parametros['frm_sexo']}',
									   '{$parametros['frm_estadoCivil']}',
									   '{$parametros['frm_email']}',
									   '{$parametros['frm_telefonoFijo']}',
									   '{$parametros['frm_telefonoCelularAvis']}',
									   '{$parametros['frm_telefonoCelular']}',
									   '{$parametros['frm_telefonoFijoAvis']}',
									   '{$parametros['frm_etnia']}',
									   '{$parametros['frm_nacionalidad']}',
									   '{$parametros['frm_afro']}',
									   '{$parametros['frm_prevision']}',
									   '{$parametros['frm_convenio']}',
									   '{$parametros['frm_ocupacionLaboral']}',
									   '{$parametros['frm_lugarTrabajo']}',
									   '{$parametros['frm_direccion_laboral']}',
									   '{$parametros['frm_telefono_laboral']}',
									   '{$parametros['frm_comunaLaboral']}',
									   '{$parametros['frm_NombresPadre']}',
									   '{$parametros['frm_AP_Padre']}',
									   '{$parametros['frm_AM_Padre']}',
									   '{$parametros['frm_NombresMadre']}',
									   '{$parametros['frm_AP_Madre']}',
									   '{$parametros['frm_AM_Madre']}',
									   '{$parametros['frm_nombres_otroContacto']}',
									   '{$parametros['frm_AP_otroContacto']}',
									   '{$parametros['frm_AM_otroContacto']}',
									   '{$parametros['frm_parentesco']}',
									   '{$parametros['frm_telefono_otroContacto']}',
									   '{$parametros['reg_usuario_insercion']}',
									   '{$parametros['frm_act_fonasa_fecha']}',
									   '{$parametros['frm_act_fonasa_hrs']}',
									   '{$parametros['frm_act_fonasa_folio']}',
									   '{$parametros['frm_prais']}',
									   '{$parametros['frm_paisNacimiento']}',
									   '{$parametros['dv']}',
									   '{$parametros['frm_nombreCalleIndicePaciente']}',
									   '{$parametros['frm_numeroDireccionIndicePaciente']}',
									   '{$parametros['frm_direccion']}',
									   '{$parametros['frm_ciudadIndicePaciente']}',
									   '{$parametros['frm_regionIndicePaciente']}',
									   '{$parametros['frm_tipoDomicilioIndicePaciente']}',
									   '{$parametros['frm_sectorDomicilioIndicePaciente']}'
									   )";
			$response = $objCon->ejecutarSQL($sql, "Error al Insertar Paciente");
			// $objLog->registrar_log($objCon, $parametros);
			return $objCon->lastInsertId();
		}			

		function actualiza_paciente($objCon, $parametros){
			$sql="UPDATE paciente
				  SET    rut                        = '{$parametros['frm_rut_pac']}',
				  		 rut_extranjero             = '{$parametros['frm_nroDocumento']}',
				  		 nombres                    = '{$parametros['frm_nombres']}',
				         apellidopat                = '{$parametros['frm_AP']}',
						 apellidomat                = '{$parametros['frm_AM']}',
						 direccion                  = '{$parametros['frm_direccion']}',
						 idcomuna                   = '{$parametros['frm_comuna']}',
						 centroatencionprimaria     = '{$parametros['frm_centroAtencion']}',
						 fechanac                   = '{$parametros['frm_Naciemito']}',
						 sexo                       = '{$parametros['frm_sexo']}',
						 estadocivil                = '{$parametros['frm_estadoCivil']}',
						 email                      = '{$parametros['frm_email']}',
						 fono1                      = '{$parametros['frm_telefonoFijo']}',
						 fono2                      = '{$parametros['frm_telefonoCelularAvis']}',
						 PACfono                    = '{$parametros['frm_telefonoCelular']}',
						 PACcelular                 = '{$parametros['frm_telefonoFijoAvis']}',
						 etnia                      = '{$parametros['frm_etnia']}',
						 nacionalidad               = '{$parametros['frm_nacionalidad']}',
						 PACafro                    = '{$parametros['frm_afro']}',
						 prevision                  = '{$parametros['frm_prevision']}',
						 conveniopago               = '{$parametros['frm_convenio']}',
						 ocupacion                  = '{$parametros['frm_ocupacionLaboral']}',
						 lugartrabajo               = '{$parametros['frm_lugarTrabajo']}',
						 direcciontrabajo           = '{$parametros['frm_direccion_laboral']}',
						 fono3                      = '{$parametros['frm_telefono_laboral']}',
						 comunatrabajo              = '{$parametros['frm_comunaLaboral']}',
						 nombrespadre               = '{$parametros['frm_NombresPadre']}',
						 apellidopatpadre           = '{$parametros['frm_AP_Padre']}',
						 apellidomatpadre           = '{$parametros['frm_AM_Padre']}',
						 nombresmadre               = '{$parametros['frm_NombresMadre']}',
						 apellidopatmadre           = '{$parametros['frm_AP_Madre']}',
						 apellidomatmadre           = '{$parametros['frm_AM_Madre']}',
						 nombrescontacto            = '{$parametros['frm_nombres_otroContacto']}',
						 apellidopatcontacto        = '{$parametros['frm_AP_otroContacto']}',
						 apellidomatcontacto        = '{$parametros['frm_AM_otroContacto']}',
						 tipocontacto               = '{$parametros['frm_parentesco']}',
						 telefonocontacto           = '{$parametros['frm_telefono_otroContacto']}',
						 etnia 						= '{$parametros['frm_etnia']}',
						 PACafro 					= '{$parametros['frm_afrodescendiente']}',
						 paisNacimiento 			= '{$parametros['frm_pais_nacimiento']}',
						 region 					= '{$parametros['frm_region']}',
						 ciudad 					= '{$parametros['frm_ciudad']}',
						 idcomuna 					= '{$parametros['frm_comuna']}',
						 prais 						= '{$parametros['frm_prais']}',
						 calle 						= '{$parametros['frm_nombreCalle']}',
						 numero 					= '{$parametros['frm_numeroDireccion']}',
						 restodedireccion 			= '{$parametros['frm_direccion']}',
						 sector_domicilio 			= '{$parametros['frm_sectorDomicilio']}',
						 conruralidad 				= '{$parametros['frm_tipoDomicilio']}',
						 PACfonoOtros 				= '{$parametros['frm_otrosTelefonos']}',
						 dv                         = '{$parametros['dv']}'
				  WHERE id={$parametros['frm_id_paciente']}";
			$response = $objCon->ejecutarSQL($sql, "Error al Actualizar Detalle Paciente");
		}

		// function actualiza_pacienteFonasa($objCon, $parametros){
		// 	$sql="UPDATE paciente
		// 		  SET    nombres                    = '{$parametros['nombrePacienteHjnc']}',
		// 		         apellidopat                = '{$parametros['paternoPacienteHjnc']}',
		// 				 apellidomat                = '{$parametros['maternoPacienteHjnc']}',
		// 				 fechanac                   = '{$parametros['nacimientoHjnc']}',
		// 				 sexo                       = '{$parametros['sexoHjnc']}',
		// 				 prevision                  = '{$parametros['prevision']}'
		// 		  WHERE id={$parametros['frm_id_paciente']}";
		// 	$response = $objCon->ejecutarSQL($sql, "Error al Actualizar Detalle Paciente");
		// }
		function grabar_estado($objCon, $parametros){

			$sql="INSERT INTO  paciente.fallecido (fal_id_paciente,
										  fal_reporta,
										  fal_fecha_notificacion,
										  fal_fecha_difuncion,
										  fal_hora_difuncion,
										  fal_fecha_ingreso,
										  fal_observacion,
										  fal_usuario)
				  VALUES ('{$parametros['frm_id_paciente']}',
				  		  '{$parametros['frm_reporta']}',
				  		  '{$parametros['frm_notificacion']}',
				  		  '{$parametros['frm_fechaDefuncion']}',
				  		  '{$parametros['frm_hora']}',
				  		  '{$parametros['frm_fechaIngreso']}',
				  		  '{$parametros['frm_observacion']}',
				  		  '{$parametros['reg_usuario_insercion']}')";
				  $response = $objCon->ejecutarSQL($sql, "Error al Insertar Paciente Fallecido");
				  return $response;
		} 
		function consultarUltimoID($objCon){
			$sql="SELECT MAX(id)+1 AS MAYOR 
				  FROM paciente.paciente";
			$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
			return $datos;
		}
		function obtenerCentroAtencionPrimaria ( $objCon, $idPaciente ) {
			$sql = "SELECT
						paciente.paciente.centroatencionprimaria
					FROM
						paciente.paciente
					WHERE
						paciente.paciente.id = '{$idPaciente}' ";
			$resultado=$objCon->consultaSQL($sql,"<br>ERROR obtenerCentroAtencionPrimaria <br>");
			return $resultado[0];
		}

		function actualiza_estado_paciente($objCon, $parametros){
			$sql="UPDATE paciente.paciente  
				  SET    paciente.fallecido='S'
		          WHERE id={$parametros['frm_id_paciente']}";
			$response = $objCon->ejecutarSQL($sql, "Error al Actualizar Detalle Paciente Fallecido"); 
		}

		function actualizadatos_pacientes_fonasa($objCon,$parametros){
			// paciente.prevision          = '{$parametros['frm_previsionFonasa']}',
			$sql="UPDATE paciente.paciente
				  SET    paciente.nombres            = '{$parametros['frm_nombresFonasa']}',
				  		 paciente.apellidopat        = '{$parametros['frm_apellidopatFonasa']}',
				  		 paciente.apellidomat        = '{$parametros['frm_apellidomatFonasa']}',
				  		 paciente.fechanac           = '{$parametros['frm_fechanacFonasa']}',
				  		 paciente.direccion          = '{$parametros['frm_direccionFonasa']}',				  		
				  		 paciente.act_fonasa_fecha   = '{$parametros['frm_act_fonasa_fecha']}',
				  		 paciente.act_fonasa_hrs     = '{$parametros['frm_act_fonasa_hrs']}',
				  		 paciente.act_fonasa_folio   = '{$parametros['frm_act_fonasa_folio']}',
				  		 paciente.prevision          = '{$parametros['frm_prevision']}',
				  		 paciente.sexo               = '{$parametros['frm_sexo']}'/*,
				  		 paciente.PACfechaUpdateHjnc = '{$parametros['frm_PACfechaUpdateHjnc']}'
*/ 				  WHERE paciente.id ='{$parametros['frm_id_paciente']}'";
 				  $response = $objCon->ejecutarSQL($sql, "Error al Actualizar Detalle Paciente Fallecido");
		}

		function actualizaPacienteHjnc($objCon,$parametros){
			//$fechaupdate = date("Y-m-d G:i:s");
			//paciente.act_fonasa_fecha   = '{$parametros['frm_act_fonasa_fecha']}',
			$sql="UPDATE paciente.paciente
				  SET    paciente.nombres            = '{$parametros['frm_nombres']}',
						 paciente.apellidopat        = '{$parametros['frm_apellidopat']}',
						 paciente.apellidomat        = '{$parametros['frm_apellidomat']}',
						 paciente.fechanac           = '{$parametros['frm_fechanac']}',
						 paciente.prevision          = '{$parametros['frm_prevision']}',
						 paciente.sexo               = '{$parametros['frm_sexo']}',
						 paciente.act_fonasa_motivo  = '{$parametros['frm_act_fonasa_motivo']}',						 
						 -- paciente.act_fonasa_hrs     = '{$parametros['frm_act_fonasa_hrs']}',
						 paciente.act_fonasa_folio   = '{$parametros['frm_act_fonasa_folio']}',
               			 paciente.PACfechaUpdateHjnc = '{$parametros['frm_registro_actualizacion']}',
               			 -- paciente.PACfechafonasahjnc = '{$parametros['frm_act_fonasa_fecha']}',
               			 paciente.idusuario          = '{$parametros['reg_usuario_insercion']}'			  
				  WHERE paciente.id ='{$parametros['frm_id_paciente']}'";
				  $response = $objCon->ejecutarSQL($sql, "Error al Actualizar Detalle Paciente Fallecido"); 
				  
		}

		function verPermiso($objCon,$idUsuario){
			// $objCon->db_select("acceso");
			$sql="SELECT
					usuario_has_rol.usuario_idusuario,
					usuario_has_rol.rol_idrol
				  FROM
					acceso.usuario_has_rol
				  WHERE usuario_has_rol.usuario_idusuario='$idUsuario' AND usuario_has_rol.rol_idrol IN (854)";
			$response = $objCon->consultaSQL($sql, "Error al verPermiso");
			return $response;
		}

		function actualizarPacienteDAU($objCon, $parametros){
			$condicion 	= "";
		$sql="UPDATE paciente.paciente";

			if ($parametros['frm_nombres']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .="paciente.nombres = '{$parametros['frm_nombres']}'";
			}
			if ($parametros['frm_prevision']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .="paciente.prevision = '{$parametros['frm_prevision']}'";
			}
			if ($parametros['frm_AP']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.apellidopat = '{$parametros['frm_AP']}'";
			}
			if ($parametros['frm_AM']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.apellidomat = '{$parametros['frm_AM']}'";
			}
			if ($parametros['frm_direccion']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.direccion = '{$parametros['frm_direccion']}'";
			}
			if ($parametros['frm_centroAtencion']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.centroatencionprimaria = '{$parametros['frm_centroAtencion']}'";
			}
			if ($parametros['frm_Naciemito']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.fechanac = '{$parametros['frm_Naciemito']}'";
			}
			if ($parametros['frm_sexo']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.sexo = '{$parametros['frm_sexo']}'";
			}
			if ($parametros['frm_correo']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.email = '{$parametros['frm_correo']}'";
			}
			if ($parametros['frm_etnia']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.etnia = '{$parametros['frm_etnia']}'";
			}
			if ($parametros['frm_Nacionalidad']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.nacionalidad = '{$parametros['frm_Nacionalidad']}'";
			}
			if ($parametros['frm_formaPago']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.conveniopago = '{$parametros['frm_formaPago']}'";
			}
			if (!is_null($parametros['frm_afrodescendiente'])) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.PACafro = '{$parametros['frm_afrodescendiente']}'";
			}
			if ($parametros['frm_pais_nacimiento']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.paisNacimiento = '{$parametros['frm_pais_nacimiento']}'";
			}
			if ($parametros['frm_region']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.region = '{$parametros['frm_region']}'";
			}
			if ($parametros['frm_ciudad']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.ciudad = '{$parametros['frm_ciudad']}'";
			}
			if ($parametros['frm_comuna']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.idcomuna = '{$parametros['frm_comuna']}'";
			}
			if (!is_null($parametros['frm_prais'])) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.prais = '{$parametros['frm_prais']}'";
			}

			if ($parametros['frm_nombreCalle']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.calle = '{$parametros['frm_nombreCalle']}'";
			}

			if ($parametros['frm_numeroDireccion']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.numero = '{$parametros['frm_numeroDireccion']}'";
			}

			if ($parametros['frm_direccion']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.restodedireccion = '{$parametros['frm_direccion']}'";
			}

			if ($parametros['frm_sectorDomicilio']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.sector_domicilio = '{$parametros['frm_sectorDomicilio']}'";
			}

			if ($parametros['frm_tipoDomicilio']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.conruralidad = '{$parametros['frm_tipoDomicilio']}'";
			}
			if ($parametros['frm_religion']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.religion = '{$parametros['frm_religion']}'";
			}

			if ($parametros['fono1']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.fono1 = '{$parametros['fono1']}'";
			}if ($parametros['fono2']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.fono2 = '{$parametros['fono2']}'";
			}if ($parametros['fono3']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.fono3 = '{$parametros['fono3']}'";
			}
			if (!is_null($parametros['dv'])) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion .= "paciente.dv = '{$parametros['dv']}'";
			}


			$sql .= $condicion;
			$sql .= " WHERE paciente.id={$parametros['id_paciente']}";
			$response = $objCon->ejecutarSQL($sql, "Error al Actualizar Detalle Paciente en DAU"); 
		}	

		function datosPaciente($objCon,$idPaciente){ 
	
			//   $objCon->db_select("paciente");
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
					paciente.transexual,
					paciente.nombreSocial,
					nacionalidadavis.NACdescripcion,
					etnia.etnia_descripcion,
					paciente.calle,
					paciente.numero,
					paciente.restodedireccion,
					comuna.comuna AS COM_Descripcion,
					paciente.PACcsa,
					paciente.PACprais,
					paciente.PACOnco,
					paciente.nombre_legal,
					paciente.identidad_genero
					FROM paciente.paciente
					LEFT JOIN paciente.comuna ON comuna.id = paciente.idcomuna
					LEFT JOIN paciente.institucion AS conv ON conv.instCod = paciente.conveniopago
					LEFT JOIN paciente.instituciondetalle AS prev ON prev.previCod = paciente.prevision
					LEFT JOIN paciente.nacionalidadavis ON paciente.nacionalidad = nacionalidadavis.NACcodigo
					LEFT JOIN paciente.etnia ON paciente.etnia = etnia.etnia_id
					WHERE paciente.id = ".$idPaciente;
			$datos = $objCon->consultaSQL($sql,"Error al listar citas");
			return $datos;
		}	

	}
?>