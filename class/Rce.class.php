<?php
 	class Rce{

 	function SelectOtro_especialista($objCon,$parametros){
        $condicion = '';
        $sql = "SELECT *
                FROM
                rce.otro_especialista";
        if(isset($parametros['id_otro'])){
            $condicion .= ($condicion == "") ? " WHERE " : " AND ";
            $condicion.=" id_otro = '{$parametros['id_otro']}' ";
        }
         $sql .= $condicion;

        $datos = $objCon->consultaSQL($sql,"Error al listar Antecedentes");
        return $datos;
    }
 	function infoCIE10($objCon,$parametros){
		$sql = "SELECT
				cie10.codigoCIE,
				cie10.nombreCIE,
				cie10.nomcompletoCIE
				FROM cie10.cie10
				WHERE codigoCIE = '{$parametros['diagcie10']}'";
		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL BUSCAR CIE10<br>");
		return $datos;
	}
 	function ingresarAntecedentes($objCon,$parametros){
        // $objCon->setDB("RCE");
        $sql="INSERT INTO rce.paciente_has_antecedente(
            pacId,
            antId,
            pac_ant_fecha_inicio,
            pac_ant_fecha_termino,
            reg_usuario_inserta,
            pac_ant_observacion,
            pac_ant_descripcion,
            ctacte
            )VALUES(
            {$parametros['paciente_id']},
            {$parametros['tipoAntecedente']},
            '{$parametros['frm_fecha_inicio']}',
            '{$parametros['frm_fecha_termino']}',
            '{$parametros['usuario']}',
            '{$parametros['obsAntecedente']}',
            '{$parametros['detalleAntecedente']}',
            {$parametros['frm_ctacte']})";
        // print('<pre>');  print_r($sql);  print('</pre>');
        $objCon->ejecutarSQL($sql, "Error al ingresarAntecedentes");
        return $objCon->lastInsertId();
    }
 	function listarAntecedentes($objCon,$parametros){
        $condicion = '';
        $sql = "SELECT
                tipoantecedente.tipAntId,
                tipoantecedente.tipAntDescripcion
                FROM
                rce.tipoantecedente";
        if(isset($parametros['idAntecedente'])){
            $condicion .= ($condicion == "") ? " WHERE " : " AND ";
            $condicion.=" tipAntId = '{$parametros['idAntecedente']}'";
        }
         $sql .= $condicion." ORDER BY
                tipoantecedente.tipAntId DESC ";

        $datos = $objCon->consultaSQL($sql,"Error al listar Antecedentes");
        return $datos;
    }
    function obtenerTipoAntecedentes($objCon,$parametros){
        $sql = "SELECT
                antecedente.antDescripcion,
                antecedente.antId
                FROM
                rce.antecedente
                INNER JOIN rce.tipoantecedente ON antecedente.tipAntId = tipoantecedente.tipAntId
                WHERE
                tipoantecedente.tipAntId = {$parametros['idAntecedente']}";
        $datos = $objCon->consultaSQL($sql,"Error al obtenerTipoAntecedentes");
        return $datos;
    }
     function antecedenteIngresado($objCon,$parametros){
        // $objCon->db_select("RCE");
         $sql = "SELECT
                paciente_has_antecedente.pac_ant_fecha_inicio,
                paciente_has_antecedente.pac_ant_fecha_termino,
                antecedente.antDescripcion,
                paciente_has_antecedente.pac_ant_descripcion,
                paciente_has_antecedente.pac_ant_observacion,
                paciente_has_antecedente.antId
                FROM
                rce.paciente_has_antecedente
                LEFT JOIN rce.antecedente ON rce.paciente_has_antecedente.antId = antecedente.antId
                WHERE
                paciente_has_antecedente.pacId = '{$parametros["pac_id"]}' AND
                antecedente.tipAntId = '{$parametros["id_indicaciones"]}'";
        $datos = $objCon->consultaSQL($sql,"Error al listar Antecedentes");
        return $datos;
    }
 	function listarPronosticos($objCon){	
		$sql="SELECT PRONcodigo,PRONdescripcion FROM rce.pronostico";
		$datos = $objCon->consultaSQL($sql,"Error al listar citas");
		return $datos;
	}
	function getLaboratorio($objCon,$rutPaciente){

		  $objCon->setDB("laboratorio");
		  $sql = "SELECT
				laboratorio.controllaboratorio.anio,
				laboratorio.controllaboratorio.desc_servicio,
				laboratorio.controllaboratorio.fecha_extraccion,
				laboratorio.controllaboratorio.fecha_registro,
				laboratorio.controllaboratorio.desc_solicitante,
				laboratorio.controllaboratorio.nomb_medico,
				laboratorio.controllaboratorio.solicitud_examen,
				laboratorio.controllaboratorio.tipo_solicitante,
				laboratorio.controllaboratorio.estado
				FROM
				laboratorio.controllaboratorio
				WHERE
				laboratorio.controllaboratorio.rut_paciente = '$rutPaciente' AND
				laboratorio.controllaboratorio.estado IS NULL
				UNION ALL
				SELECT
				laboratorio.controllaboratorio.anio,
				laboratorio.controllaboratorio.desc_servicio,
				laboratorio.controllaboratorio.fecha_extraccion,
				laboratorio.controllaboratorio.fecha_registro,
				laboratorio.controllaboratorio.desc_solicitante,
				laboratorio.controllaboratorio.nomb_medico,
				laboratorio.controllaboratorio.solicitud_examen,
				laboratorio.controllaboratorio.tipo_solicitante,
				laboratorio.controllaboratorio.estado
				FROM
				laboratorio.controllaboratorio
				inner JOIN laboratorio.prestacioneslaboratorio ON prestacioneslaboratorio.solicitud_examen = controllaboratorio.solicitud_examen
				WHERE controllaboratorio.rut_paciente = '$rutPaciente'
				AND ( prestacioneslaboratorio.prestacion = 4140 OR prestacioneslaboratorio.prestacion = 99991)
				AND laboratorio.controllaboratorio.estado = 'V'
				order by fecha_extraccion DESC";
		$datos = $objCon->consultaSQL($sql,"Error al listar examenes de Laboratorio");
		return $datos;
	}



	function datosPaciente($objCon,$idPaciente){

		  $objCon->setDB("paciente");
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
				DATE_FORMAT(paciente.PACfechaUpdateAvis,'%d-%m-%Y') as PACfechaUpdateAvis
				FROM paciente
				LEFT JOIN comuna ON comuna.id = paciente.idcomuna
				LEFT JOIN institucion AS conv ON conv.instCod = paciente.conveniopago
				LEFT JOIN instituciondetalle AS prev ON prev.previCod = paciente.prevision
				WHERE paciente.id = ".$idPaciente;
		$datos = $objCon->consultaSQL($sql,"Error al listar citas");
		return $datos;
	}



	function getPabellon($objCon,$idPaciente){

		  $objCon->setDB("pabellon");
		  $sql = "SELECT
                pabellon.cirugia.ciruCod,
				pabellon.cirugia.ciruFecha,
				pabellon.cirugia.ciruHora,
				pabellon.cirugia.pabCod,
				pabellon.cirugia.ciruInter1Glosa,
				pabellon.cirugia.ciruEstado,
				pabellon.cirugia.ciruHLlegada,
				pabellon.cirugia.ciruHAnest,
				pabellon.cirugia.ciruHICirugia,
				pabellon.cirugia.ciruHSalida,
				pabellon.cirugia.ciruMotSuspencion,
				acceso.servicio.nombre as servicio,
				acceso.medico.nombremedico
				FROM
				pabellon.cirugia
				LEFT JOIN acceso.servicio ON pabellon.cirugia.ciruServOrigCod = acceso.servicio.idservicio
				LEFT JOIN acceso.medico ON pabellon.cirugia.ciruCirujano1 = acceso.medico.id
				WHERE
				pabellon.cirugia.pacieCod = '".$idPaciente."' AND
				pabellon.cirugia.ciruEstado IN ('EN PROCESO','REALIZADA')
				ORDER BY
				pabellon.cirugia.ciruFecha DESC";

		$datos = $objCon->consultaSQL($sql,"Error al listar historial de pabellon");
		return $datos;
	}



	function getInterconsulta($objCon,$idPaciente){

		  $objCon->setDB("agenda");
		  $sql = "SELECT *, DATE_FORMAT(interconsulta.INTfecha_solicitud, '%d-%m-%Y') AS fecha_solicitud, DATE_FORMAT(interconsulta.INThora_solicitud, '%H:%i') AS hora_solicitud,
				DATE_FORMAT(interconsulta.INTfecha_egreso, '%d-%m-%Y') AS fecha_egreso,
				DATE_FORMAT(interconsulta.CITfecha, '%d-%m-%Y') AS fecha_cita,
				est.ESTdescripcion,
				esta.ESTAdescripcion AS origen,
				esta.ESTAabreviado AS origen_abre,
				esta_2.ESTAdescripcion AS destino,
				esta_2.ESTAabreviado AS destino_abre,
				esp.ESPdescripcion AS esp_destino
				FROM interconsulta
				JOIN procedencia AS pro ON pro.PROCEcodigo = interconsulta.INTprocedencia
				LEFT JOIN parametros_clinicos.especialidad AS esp ON esp.ESPcodigo = interconsulta.INTespecialidad_destino
				JOIN interconsulta_estado AS est ON est.ESTcodigo = interconsulta.INTestado
				LEFT JOIN parametros_clinicos.establecimiento AS esta ON esta.ESTAcodigo = interconsulta.INTestablecimiento_origen
				LEFT JOIN parametros_clinicos.establecimiento AS esta_2 ON esta_2.ESTAcodigo = interconsulta.INTestablecimiento_destino
				WHERE PACidentificador = ".$idPaciente."
				ORDER BY fecha_solicitud";

		$datos = $objCon->consultaSQL($sql,"Error al listar Interconsultas de paciente");
		return $datos;
	}



	function getImagenologia($objCon,$idPaciente){

		  $objCon->setDB("rayos");
		  $sql = "SELECT DISTINCT
				(rayos.examen.EXAcorrelativo),
				rayos.atencion.ATEprocede,
				rayos.atencion.ATEfecha,
				rayos.examen.PREcod,
				paciente.prestacion.preNombre,
				rayos.examen.PREcantidad,
				rayos.examen.EXAinforme_numero,
				rayos.examen.EXAinforme_estado,
				rayos.examen.EXAinforme_tipo,
				rayos.examen.EXTIcod,
				serv.nombre AS servicio,
				esp.nombre AS especialidad
				FROM rayos.atencion
				Left Join rayos.examen ON rayos.examen.ATEcorrelativo = rayos.atencion.ATEcorrelativo
				Left Join paciente.prestacion ON paciente.prestacion.preCod = rayos.examen.PREcod
				Left Join acceso.servicio AS serv ON rayos.atencion.ATEservicio = serv.idservicio
				Left Join acceso.servicio AS esp ON rayos.atencion.ATEespecialidad = esp.idservicio
				WHERE rayos.atencion.PACid = '".$idPaciente."'
				ORDER BY ATEfecha DESC";

		$datos = $objCon->consultaSQL($sql,"Error al listar examenes de imagenologia");
		return $datos;
	}



	function getAnatomia($objCon,$idPaciente){

		  $objCon->setDB("anatomia");
		  $sql = "SELECT DISTINCT ana.regAId,
				ana.regAtipoExamen,
				ana.regAFechaSolicitud,
				ana.regAFechaTomaPap,
				ana.regAserviCod, serv.nombre,
				ana.regAConsultorio,
				cons.consulDesc,
				ana.regAFolioInterno,
				ana.regNumFolioDiag,
				ana.regAEstadoDiagnostico,
				acceso.medico.medico
				FROM controlanatomia AS ana
				LEFT JOIN acceso.servicio AS serv ON ana.regAserviCod = serv.idservicio
				LEFT JOIN consultorios AS cons ON ana.regAConsultorio = cons.consulCod
				LEFT JOIN acceso.medico ON acceso.medico.id = ana.RegAmedico
				WHERE pacId = '".$idPaciente."'
				AND (ana.regAFechaSolicitud > '2012-03-20')
				ORDER BY regAId DESC";

		$datos = $objCon->consultaSQL($sql,"Error al listar Biopsias de anatomia");
		return $datos;
	}



	function getTraslados($objCon,$idPaciente){

		  $objCon->setDB("registro_clinico");
		  $sql = "SELECT
				tra.TRAid,
				tra.TRAfechaSol,
				tra.CTRmotivo_traslado,
				tra.TRAdiag,
				tra.TRAmotivo,
				GREATEST(tra.TRAfecha2,tra.TRAfecha3,tra.TRAfecha4,tra.TRAfecha5) AS fecha

				FROM traslado tra
				WHERE tra.TRAidPac = '".$idPaciente."' AND tra.TRAestado = 'A'";

		$datos = $objCon->consultaSQL($sql,"Error al listar Biopsias de anatomia");
		return $datos;
	}



	function getPartos($objCon,$idPaciente){

		  $objCon->setDB("partos");
		  $sql = "SELECT
				rn_datos.RNid,
				rn_datos.PACid,
				rn_datos.PAidparto,
				rn_datos.RNestado,
				rn_datos.RNsexo,
				rn_datos.RNpeso,
				rn_datos.RNtalla,
				rn_datos.RNcc,
				rn_datos.RNip,
				rn_datos.RNapgar1,
				rn_datos.RNapgar5,
				rn_datos.RNego,
				rn_datos.RNegp,
				rn_datos.RNfecha,
				rn_datos.RNhora,
				tipo_parto.TInombre
				FROM
				libro_partos
				INNER JOIN rn_datos ON rn_datos.PAidparto = libro_partos.PAidparto
				INNER JOIN tipo_parto ON rn_datos.TIid = tipo_parto.TIid
				WHERE libro_partos.idPaciente = '".$idPaciente."'";

		$datos = $objCon->consultaSQL($sql,"Error al listar historial de partos");
		return $datos;
	}



	function getFarmacos($objCon, $idPaciente){
		$objCon->setDB("farmacos");
		$sql = "SELECT * FROM(SELECT
				farmacos.egresosdetalle.produCodInt as codigo,
				aba.producto.produNombre as nom_producto,
				farmacos.egresosdetalle.egreDespachado as cantidad,
				aba.producto.umediCod as um,
				acceso.servicio.nombre as servicio,
				farmacos.egresos.egreFecha AS fecha
				FROM
				farmacos.egresosdetalle
				LEFT JOIN aba.producto ON aba.producto.produCodInt = farmacos.egresosdetalle.produCodInt
				LEFT JOIN farmacos.egresos ON farmacos.egresosdetalle.egreId = farmacos.egresos.egreId AND farmacos.egresos.tipoEgreId = farmacos.egresosdetalle.tipoEgreId
				LEFT JOIN acceso.servicio ON farmacos.egresos.egreserviCod = acceso.servicio.idservicio
				WHERE farmacos.egresosdetalle.egrePacId = '$idPaciente'
				UNION
					(SELECT
					farmacos.detallefarmaco.cod_farmaco,
					farmacos.detallefarmaco.nom_farmaco,
					farmacos.detallefarmaco.cant_despachada,
					aba.producto.umediCod,
					CASE
					WHEN farmacos.cabecerafarmaco.sala_hosp = 'CAE'  THEN farmacos.cabecerafarmaco.sala_hosp
					WHEN farmacos.cabecerafarmaco.sala_hosp = 'DIAL' THEN farmacos.cabecerafarmaco.sala_hosp
					WHEN farmacos.cabecerafarmaco.sala_hosp = 'PAB'  THEN 'Pabellon'
					WHEN farmacos.cabecerafarmaco.sala_hosp = '6to Piso'  THEN CONCAT('SN - ',farmacos.cabecerafarmaco.sala_hosp)
					WHEN farmacos.cabecerafarmaco.sala_hosp = '3er Piso'  THEN CONCAT('SN - ',farmacos.cabecerafarmaco.sala_hosp)
					WHEN farmacos.cabecerafarmaco.sala_hosp = 'CMI 3'  THEN 'SN - CMI 3er PISO'
					ELSE farmacos.cabecerafarmaco.desc_servicio
					END AS servicio,
					farmacos.cabecerafarmaco.fecha_despacho AS fecha
					FROM
					farmacos.cabecerafarmaco
					LEFT JOIN farmacos.detallefarmaco ON farmacos.cabecerafarmaco.correlativo_solicitud = farmacos.detallefarmaco.correlativo_solicitud
					LEFT JOIN aba.producto ON farmacos.detallefarmaco.cod_farmaco = aba.producto.produCodInt
					WHERE
					farmacos.cabecerafarmaco.id_pac = '$idPaciente')
				ORDER BY fecha DESC) AS consulta";
		$datos = $objCon -> consultaSQL($sql,"Error al listar datos");
		return $datos;
	}



	function ListarDatosAtencion($objCon, $dau_id){
		$sql="SELECT *
				FROM
				dau.dau_tiene_categorizacion
				INNER JOIN dau.categorizacion ON dau_tiene_categorizacion.cat_id = categorizacion.cat_id
				WHERE dau_id = '$dau_id'
				ORDER BY dau_cat_fecha DESC";
		$resultado=$objCon->consultaSQL($sql,"<br>Error Listar paciente categorizado dau<br>");
		return $resultado;
	}



	function listarSignosVitales($objCon, $idPaciente, $idRCE){
		$sql="SELECT
					rce.signo_vital.*,
  					acceso.usuario.nombreusuario
				FROM
					rce.signo_vital
				INNER JOIN
					acceso.usuario ON acceso.usuario.idUsuario = rce.signo_vital.SVITALusuario
				WHERE
					idRCE = '$idRCE'
				ORDER BY SVITALfecha DESC";
		$resultado=$objCon->consultaSQL($sql,"<br>Error Listar signos viatales<br>");
		return $resultado;
	}
	function listarSignosVitalesLectura($objCon, $idPaciente, $idRCE){
		$sql="SELECT
					IFNULL(acceso.usuario.nombreusuario, '-') AS nombreusuario, 
				    IFNULL(rce.signo_vital.SVITALid, '-') AS SVITALid,
				    IFNULL(rce.signo_vital.SVITALfecha, '-') AS SVITALfecha,
				    IFNULL(rce.signo_vital.idRCE, '-') AS idRCE,
				    IFNULL(rce.signo_vital.idPaciente, '-') AS idPaciente,
				    IFNULL(rce.signo_vital.SVITALtalla, '-') AS SVITALtalla,
				    IFNULL(rce.signo_vital.SVITALpeso, '-') AS SVITALpeso,
				    IFNULL(rce.signo_vital.SVITALpulso, '-') AS SVITALpulso,
				    IFNULL(rce.signo_vital.SVITALsistolica, '-') AS SVITALsistolica,
				    IFNULL(rce.signo_vital.SVITALdiastolica, '-') AS SVITALdiastolica,
				    IFNULL(rce.signo_vital.SVITALPAM, '-') AS SVITALPAM,
				    IFNULL(rce.signo_vital.SVITALtemperatura, '-') AS SVITALtemperatura,
				    IFNULL(rce.signo_vital.SVITALsaturacion, '-') AS SVITALsaturacion,
				    IFNULL(rce.signo_vital.SVITALparterial, '-') AS SVITALparterial,
				    IFNULL(rce.signo_vital.SVITALfr, '-') AS SVITALfr,
				    IFNULL(rce.signo_vital.SVITALfc, '-') AS SVITALfc,
				    IFNULL(rce.signo_vital.SVITALglasgow, '-') AS SVITALglasgow,
				    IFNULL(rce.signo_vital.SVITALeva, '-') AS SVITALeva,
				    IFNULL(rce.signo_vital.SVITALusuario, '-') AS SVITALusuario,
				    IFNULL(rce.signo_vital.SVITALHemoglucoTest, '-') AS SVITALHemoglucoTest,
				    IFNULL(rce.signo_vital.SVITALfeto, '-') AS SVITALfeto,
				    IFNULL(rce.signo_vital.SVITALrbne, '-') AS SVITALrbne,
				    IFNULL(rce.signo_vital.FIO2, '-') AS FIO2
				FROM
					rce.signo_vital
				INNER JOIN
					acceso.usuario ON acceso.usuario.idUsuario = rce.signo_vital.SVITALusuario
				WHERE
					idRCE = '$idRCE'
				ORDER BY SVITALfecha DESC";
		$resultado=$objCon->consultaSQL($sql,"<br>Error Listar signos viatales<br>");
		return $resultado;
	}


	function registrarSVITAL($objCon,$parametros){
		$sql = "INSERT INTO rce.signo_vital(
						SVITALfecha,
						idRCE,
						idPaciente,
						SVITALpulso,
						SVITALsistolica,
						SVITALdiastolica,
						SVITALPAM,
						SVITALtemperatura,
						SVITALsaturacion,
						SVITALfr,
						SVITALfc,
						SVITALglasgow,
						SVITALeva,
						SVITALusuario,
						SVITALpeso,
						SVITALtalla,
						SVITALhemoglucoTest,
						SVITALfeto,
						SVITALrbne)
				VALUES 	(NOW(),
						{$parametros['rce_id']},
						{$parametros['idPaciente']},
						{$parametros['frm_svital_pulso']},
						{$parametros['frm_svital_psis']},
						{$parametros['frm_svital_pdias']},
						{$parametros['frm_svital_pam']},
						'{$parametros['frm_svital_temp']}',
						{$parametros['frm_svital_satu']},
						{$parametros['frm_svital_fr']},
						{$parametros['frm_svital_fc']},
						{$parametros['frm_svital_glas']},
						{$parametros['frm_svital_eva']},
						'{$parametros['usuario']}',
						'{$parametros['frm_svital_peso']}',
						'{$parametros['frm_svital_talla']}',
						'{$parametros['frm_hemoglucoTest']}',
						'{$parametros['signosFetales']}',
						'{$parametros['rbne']}'
						)";
		$response = $objCon -> ejecutarSQL($sql, "Error registrar signos vitales");
	}



	function registrarFechaInicioIndicacionENF($objCon,$parametros){
		$sql="UPDATE rce.solicitud_indicaciones
			  SET 	sol_ind_fechaIniciaIndicacion   = NOW(),
			  	    sol_ind_usuarioIniciaIndicacion = '{$parametros['usuario_IniciaAtencion']}'
			  WHERE sol_ind_id = '{$parametros['solicitud_id']}' AND sol_ind_servicio ='{$parametros['tipo_id']}'";
		$objCon->ejecutarSQL($sql, "Error al registrarFechaInicioAtencionENF");
	}



	function registrarTomaDeMuestra($objCon,$parametros){
		$sql="UPDATE rce.solicitud_laboratorio
			  SET 	sol_lab_fechaTomaMuestra   	= NOW(),
			  	    sol_lab_usuarioTomaMuestra 	= '{$parametros['usuario_muestraTomadas']}'
			  WHERE sol_lab_id = '{$parametros['solicitud_id']}' AND sol_lab_tipo ='{$parametros['tipo_id']}'";
		$objCon->ejecutarSQL($sql, "Error al registrarFechaInicioAtencionENF");
	}

	function registrarTomaDeMuestraSolicitud($objCon,$parametros){
		$sql="UPDATE rce.solicitud_laboratorio
			  SET 	solicitud_examen 			= '{$parametros['solicitud_examen']}'
			  WHERE sol_lab_id = '{$parametros['solicitud_id']}' ";
		$objCon->ejecutarSQL($sql, "Error al registrarFechaInicioAtencionENF");
	}


	function insertarEntregaTurno($objCon,$parametros){
		$sql = "INSERT INTO dau.entregaturno(
			ENTdau_id,
			ENTprofEntregaRut,
			ENTprofEntregaDesc,
			ENTprofRecibeRut,
			ENTprofRecibeDesc,
			ENTfecha)
			VALUES 	(
			{$parametros['dauId']},
			{$parametros['rutEntregaTurno']},
			'{$parametros['observacionEntregarTurno']}',
			{$parametros['rutRecibeTurno']},
			'{$parametros['observacionRecibirTurno']}',
			NOW())";
			$response = $objCon -> ejecutarSQL($sql, "Error registrar entregar turno");
	}



	function listarSignosVitalesCierreDAU($objCon, $idPaciente, $idRCE){

		$sql = "	SELECT
						rce.signo_vital.SVITALpeso,
						rce.signo_vital.SVITALtalla
					FROM
						rce.signo_vital
					WHERE
						
						idRCE = '{$idRCE}'
					AND (
						( rce.signo_vital.SVITALpeso != null OR rce.signo_vital.SVITALpeso != '')
						OR
						( rce.signo_vital.SVITALpeso != null OR rce.signo_vital.SVITALpeso != '') )
					ORDER BY SVITALfecha DESC LIMIT 1 ";

		$resultado=$objCon->consultaSQL($sql,"Erro al obtener signos vitales para el cierre del DAU");

		return $resultado[0];

	}



	function obtenerIdRCESegunDAU ( $objCon, $idDau ) {

		$sql = "	SELECT
						rce.registroclinico.regId
					FROM
						rce.registroclinico
					WHERE
						rce.registroclinico.dau_id = '{$idDau}'  ";

		$resultado=$objCon->consultaSQL($sql,"Erro al obtener id de RCE de acuerdo al id de DAU");

		return $resultado[0];

	}



	function obtenerCantidadSolicitudesNoSuperfluas ( $objCon, $idRCE ) {

		$sql = "	SELECT
						SUM(
							CASE
								WHEN rce.solicitud_indicaciones.sol_ind_servicio = 2
								OR rce.solicitud_indicaciones.sol_ind_servicio = 4
								OR rce.solicitud_indicaciones.sol_ind_servicio = 6
								OR rce.solicitud_indicaciones.sol_ind_servicio = 8
								THEN 1 ELSE 0
							END
							) AS solicitudesImportantes,
						SUM(
							CASE
								WHEN
									rce.solicitud_indicaciones.sol_ind_estado = 4
								OR
									rce.solicitud_indicaciones.sol_ind_estado = 6
								OR
									rce.solicitud_indicaciones.sol_ind_estado = 8
								THEN 1 ELSE 0 END
							) AS solicitudesAplicadas
						FROM
							rce.solicitud_indicaciones
						WHERE
							rce.solicitud_indicaciones.regId = '{$idRCE}'  ";

		$resultado=$objCon->consultaSQL($sql,"Erro al obtener solicitudes no supérfluas");

		return $resultado[0];

	}



	function crearPlantillaInicioAtencion ( $objCon, $parametros ) {

		$sql = "	INSERT INTO
						rce.plantilla_inicio_atencion
							(
								idMedico,
								nombrePlantilla,
								motivoConsulta,
								hipotesisDiagnosticaInicial
							)

					VALUES
						(
							'{$parametros['idMedico']}',
							'{$parametros['nombrePlantilla']}',
							'{$parametros['motivoConsulta']}',
							'{$parametros['hipotesisDiagnosticaInicial']}'
						)";

		$response = $objCon -> ejecutarSQL($sql, "Error al crear plantilla inicio atención");

		return $objCon->lastInsertId();

	}



	function obtenerNombrePlantillasInicioAtencion ( $objCon, $idMedico ) {

		$sql = "	SELECT
						rce.plantilla_inicio_atencion.idPlantilla,
						rce.plantilla_inicio_atencion.nombrePlantilla
					FROM
						rce.plantilla_inicio_atencion
					WHERE
						rce.plantilla_inicio_atencion.idMedico = '{$idMedico}'
					ORDER BY
						rce.plantilla_inicio_atencion.nombrePlantilla ASC";

		$resultado = $objCon->consultaSQL($sql,"Erro al obtener nombres de plantillas de inicio de atención");

		return $resultado;

	}



	function obtenerPlantillasInicioAtencion ( $objCon, $idPlantilla ) {

		$sql = "	SELECT
						*
					FROM
						rce.plantilla_inicio_atencion
					WHERE
						rce.plantilla_inicio_atencion.idPlantilla = '{$idPlantilla}'  ";

		$resultado = $objCon->consultaSQL($sql,"Erro al obtener plantilla de inicio de atención");

		return $resultado[0];

	}



	function crearPlantillaAltaUrgencia ( $objCon, $parametros ) {

		$sql = "	INSERT INTO
						rce.plantilla_alta_urgencia
							(
								idMedico,
								nombrePlantilla,
								idCie10,
								cie10Abierto,
								indicaciones,
								idPronostico,
								idIndicacionEgreso
							)

					VALUES
						(
							'{$parametros['idMedico']}',
							'{$parametros['nombrePlantilla']}',
							'{$parametros['idCie10']}',
							'{$parametros['cie10Abierto']}',
							'{$parametros['indicaciones']}',
							'{$parametros['idPronostico']}',
							'{$parametros['idIndicacionEgreso']}'
						)";

		$response = $objCon -> ejecutarSQL($sql, "Error al crear plantilla alta urgencia");

		return $objCon->lastInsertId();

	}
	function UpdatePlantillaAltaUrgencia ( $objCon, $parametros ) {
		$sql    = "	UPDATE
						rce.plantilla_alta_urgencia
					SET
						rce.plantilla_alta_urgencia.idCie10 = '{$parametros['idCie10']}',
						rce.plantilla_alta_urgencia.cie10Abierto = '{$parametros['cie10Abierto']}',
						rce.plantilla_alta_urgencia.indicaciones = '{$parametros['indicaciones']}',
						rce.plantilla_alta_urgencia.idPronostico = '{$parametros['idPronostico']}',
						rce.plantilla_alta_urgencia.idIndicacionEgreso = '{$parametros['idIndicacionEgreso']}'
					WHERE
						rce.plantilla_alta_urgencia.idPlantilla = '{$parametros['slc_nombrePlantilla']}' ";
		$response  = $objCon -> ejecutarSQL($sql, "Error al cambiar estado de aplicar egreso en solicitud de interconsulta");
	}
	function DeletePlantillaAltaUrgencia ( $objCon, $parametros ) {
		
		$sql = "DELETE FROM
					rce.plantilla_alta_urgencia
				WHERE
					rce.plantilla_alta_urgencia.idPlantilla = '{$parametros['slc_nombrePlantilla']}'  ";
		$response  = $objCon -> ejecutarSQL($sql, "Error al cambiar estado de aplicar egreso en solicitud de interconsulta");
	}

	function obtenerNombrePlantillasAltaUrgencia ( $objCon, $idMedico ) {

		$sql = "	SELECT
					    rce.plantilla_alta_urgencia.idPlantilla,
						rce.plantilla_alta_urgencia.nombrePlantilla
					FROM
						rce.plantilla_alta_urgencia
					WHERE
						rce.plantilla_alta_urgencia.idMedico = '{$idMedico}'
					ORDER BY
						rce.plantilla_alta_urgencia.nombrePlantilla ASC	";

		$resultado = $objCon->consultaSQL($sql,"Erro al obtener nombres de plantillas de alta urgencia");

		return $resultado;

	}



	function obtenerPlantillasAltaUrgencia ( $objCon, $idPlantilla ) {

		$sql = "	SELECT
						rce.plantilla_alta_urgencia.*,
						CONCAT(rce.plantilla_alta_urgencia.idCie10,' ',cie10.cie10.nombreCIE,'') AS descripcionCie10
					FROM
						rce.plantilla_alta_urgencia
					INNER JOIN
						cie10.cie10
					ON
						rce.plantilla_alta_urgencia.idCie10 = cie10.cie10.codigoCIE
					WHERE
						rce.plantilla_alta_urgencia.idPlantilla = '{$idPlantilla}'  ";

		$resultado = $objCon->consultaSQL($sql,"Erro al obtener plantilla de alta urgencia");

		return $resultado[0];

	}


	function eliminarPlantillaIndicaciones ( $objCon, $idPlantilla ) {

		$sql = "DELETE FROM
					rce.plantilla_indicaciones
				WHERE
					rce.plantilla_indicaciones.idPlantilla = '{$idPlantilla}' ";

		$response = $objCon -> ejecutarSQL($sql, "Error al eliminar eliminarPlantillaIndicaciones");

	}

	function crearPlantillaIndicaciones ( $objCon, $parametros ) {

		$sql = "	INSERT INTO
						rce.plantilla_indicaciones
							(
								idMedico,
								nombrePlantilla
							)

					VALUES
						(
							'{$parametros['idMedico']}',
							'{$parametros['nombrePlantilla']}'
						)";

		$response = $objCon -> ejecutarSQL($sql, "Error al crear plantilla indicaciones");

		return $objCon->lastInsertId();

	}



	function crearPlantillaIndicacionesAntecedentesClinicos ( $objCon, $parametros ) {

		$sql = "	INSERT INTO
						rce.plantilla_indicaciones_antecedentes_clinicos
							(
								idPlantilla,
								infeccionMultirresistente,
								embarazo,
								diabetes,
								asma,
								hipertension,
								otro,
								otroDescripcion
							)

					VALUES
						(
							'{$parametros['idPlantilla']}',
							'{$parametros['infeccionMultirresistente']}',
							'{$parametros['embarazo']}',
							'{$parametros['diabetes']}',
							'{$parametros['asma']}',
							'{$parametros['hipertension']}',
							'{$parametros['otro']}',
							'{$parametros['otroDescripcion']}'
						)";

		$response = $objCon -> ejecutarSQL($sql, "Error al crear plantilla indicaciones antecedentes clinicos");

		return $objCon->lastInsertId();

	}




	function crearPlantillaIndicacionesImagenologia ( $objCon, $parametros ) {

		$sql = "	INSERT INTO
						rce.plantilla_indicaciones_imagenologia
							(
								idPlantilla,
								codigoExamen,
								observacionExamen,
								tipoExamen,
								parteCuerpo,
								nombreExamen,
								lateralidad,
								prestaciones,
								contrastes
							)
					VALUES
						(
							'{$parametros['idPlantilla']}',
							'{$parametros['codigoExamen']}',
							'{$parametros['observacionExamen']}',
							'{$parametros['tipoExamen']}',
							'{$parametros['parteCuerpo']}',
							'{$parametros['nombreExamen']}',
							'{$parametros['lateralidad']}',
							'{$parametros['prestaciones']}',
							'{$parametros['contrastes']}'
						)";

		$response = $objCon -> ejecutarSQL($sql, "Error al crear plantilla indicaciones imagenologia");

		return $objCon->lastInsertId();

	}



	function obtenerNombrePlantillasIndicaciones ( $objCon, $idMedico ) {

		$sql = "	SELECT
					    rce.plantilla_indicaciones.idPlantilla,
						rce.plantilla_indicaciones.nombrePlantilla
					FROM
						rce.plantilla_indicaciones
					WHERE
						rce.plantilla_indicaciones.idMedico = '{$idMedico}'
					ORDER BY
						rce.plantilla_indicaciones.nombrePlantilla ASC ";

		$resultado = $objCon->consultaSQL($sql,"Erro al obtener nombres de plantillas de indicaciones");

		return $resultado;

	}



	function obtenerPlantillasIndicacionesImagenologia($objCon, $idPlantilla) {
		$sql = "
			SELECT
				rce.plantilla_indicaciones_imagenologia.nombreExamen,
				rce.plantilla_indicaciones_imagenologia.tipoExamen,
				rce.plantilla_indicaciones_imagenologia.lateralidad,
				rce.plantilla_indicaciones_imagenologia.contrastes,
				rce.plantilla_indicaciones_imagenologia.observacionExamen,
				rce.plantilla_indicaciones_imagenologia.codigoExamen,
				rce.plantilla_indicaciones_imagenologia.prestaciones
			FROM
				rce.plantilla_indicaciones_imagenologia
			WHERE
				rce.plantilla_indicaciones_imagenologia.idPlantilla = '{$idPlantilla}'
			ORDER BY
				rce.plantilla_indicaciones_imagenologia.idDetalleImagenologia ASC
		";

		return $objCon->consultaSQL($sql,"Erro al obtener plantilla de indicaciones imagenologia");
	}



	function obtenerPlantillasIndicacionesAntecedentesClinicos ( $objCon, $idPlantilla ) {

		$sql = "	SELECT
						*
					FROM
						rce.plantilla_indicaciones_antecedentes_clinicos
					WHERE
						rce.plantilla_indicaciones_antecedentes_clinicos.idPlantilla = '{$idPlantilla}'  ";

		$resultado = $objCon->consultaSQL($sql,"Erro al obtener plantilla de indicaciones antecedentes clinicos");

		return $resultado[0];

	}



	function crearPlantillaIndicacionesTratamiento ( $objCon, $parametros ) {

		$sql = "	INSERT INTO
						rce.plantilla_indicaciones_tratamiento
							(
								idPlantilla,
								detalleTratamiento,
								idClasificacionTratamiento
							)

					VALUES
						(
							'{$parametros['idPlantilla']}',
							'{$parametros['detalleTratamiento']}',
							'{$parametros['idClasificacionTratamiento']}'
						)";

		$response = $objCon -> ejecutarSQL($sql, "Error al crear plantilla indicaciones tratamiento");

		return $objCon->lastInsertId();

	}



	function obtenerPlantillasIndicacionesTratamiento ( $objCon, $idPlantilla ) {

		$sql = "	SELECT
						rce.plantilla_indicaciones_tratamiento.*,
						clasificacion_tratamiento.descripcionClasificacion
					FROM
						rce.plantilla_indicaciones_tratamiento
					LEFT JOIN
					 	rce.clasificacion_tratamiento ON rce.clasificacion_tratamiento.idClasificacion = rce.plantilla_indicaciones_tratamiento.idClasificacionTratamiento
					WHERE
						rce.plantilla_indicaciones_tratamiento.idPlantilla = '{$idPlantilla}'  ";

		$resultado = $objCon->consultaSQL($sql,"Error al obtener plantilla de indicaciones tratamiento");

		return $resultado;

	}



	function crearPlantillaIndicacionesLaboratorio ( $objCon, $parametros ) {

		$sql = "	INSERT INTO
						rce.plantilla_indicaciones_laboratorio
							(
								idPlantilla,
								idPrestacionLaboratorio
							)

					VALUES
						(
							'{$parametros['idPlantilla']}',
							'{$parametros['idPrestacionLaboratorio']}'
						)";

		$response = $objCon -> ejecutarSQL($sql, "Error al crear plantilla indicaciones laboratorio");

		return $objCon->lastInsertId();

	}



	function obtenerPlantillasIndicacionesLaboratorio ( $objCon, $idPlantilla ) {

		$sql = "	SELECT
						laboratorio.prestacion.pre_examen AS descripcionExamen,
						rce.plantilla_indicaciones_laboratorio.*
					FROM
						rce.plantilla_indicaciones_laboratorio
					LEFT JOIN
						laboratorio.prestacion
					ON
						rce.plantilla_indicaciones_laboratorio.idPrestacionLaboratorio = laboratorio.prestacion.pre_codOmega
					WHERE
						rce.plantilla_indicaciones_laboratorio.idPlantilla = '{$idPlantilla}'  ";

		$resultado = $objCon->consultaSQL($sql,"Error al obtener plantilla de indicaciones laboratorio");

		return $resultado;

	}



	function crearPlantillaIndicacionesProcedimiento ( $objCon, $parametros ) {

		$sql = "	INSERT INTO
						rce.plantilla_indicaciones_procedimientos
							(
								idPlantilla,
								idProcedimiento
							)

					VALUES
						(
							'{$parametros['idPlantilla']}',
							'{$parametros['idProcedimiento']}'
						)";

		$response = $objCon -> ejecutarSQL($sql, "Error al crear plantilla indicaciones procedimiento");

		return $objCon->lastInsertId();

	}



	function obtenerPlantillasIndicacionesProcedimiento ( $objCon, $idPlantilla ) {

		$sql = "	SELECT
						rce.plantilla_indicaciones_procedimientos.idProcedimiento
					FROM
						rce.plantilla_indicaciones_procedimientos
					WHERE
						rce.plantilla_indicaciones_procedimientos.idPlantilla = '{$idPlantilla}'  ";

		$resultado = $objCon->consultaSQL($sql,"Error al obtener plantilla de indicaciones procedimiento");

		return $resultado;

	}



	function crearPlantillaIndicacionesOtros ( $objCon, $parametros ) {

		$sql = "	INSERT INTO
						rce.plantilla_indicaciones_otros
							(
								idPlantilla,
								detalleOtros
							)

					VALUES
						(
							'{$parametros['idPlantilla']}',
							'{$parametros['detalleOtros']}'
						)";

		$response = $objCon -> ejecutarSQL($sql, "Error al crear plantilla indicaciones otros");

		return $objCon->lastInsertId();

	}



	function obtenerPlantillasIndicacionesOtros ( $objCon, $idPlantilla ) {

		$sql = "	SELECT
						*
					FROM
						rce.plantilla_indicaciones_otros
					WHERE
						rce.plantilla_indicaciones_otros.idPlantilla = '{$idPlantilla}'  ";

		$resultado = $objCon->consultaSQL($sql,"Error al obtener plantilla de indicaciones otros");

		return $resultado;

	}



	function obtenerTiposPrioridad( $objCon ) {

		$sql = "	SELECT
						*
					FROM
						rce.tipo_prioridad ";

		$resultado = $objCon->consultaSQL($sql,"Error al obtener tipos de prioridad");

		return $resultado;

	}



	function obtenerTiposMotivoConsulta ( $objCon ) {

		$sql = "	SELECT
						*
					FROM
						rce.tipo_motivo_consulta ";

		$resultado = $objCon->consultaSQL($sql,"Error al obtener tipos motivos de consulta");

		return $resultado;

	}



	function ingresarSolicitudSIC ( $objCon, $parametros ) {

		$sql = "	INSERT INTO
						rce.solicitud_sic
							(
								SICdau,
								SICidPaciente,
								SICfechaSolicitud,
								SIChoraSolicitud,
								SICfolio,
								SICestaOrigen,
								SICestaDestino,
								SICprocedencia,
								SICespecialidadOrigen,
								SICespecialidadDestino,
								SICprioridad,
								SICmotivoConsulta,
								SICotroMotivo,
								SIChipotesisDiagnostica,
								SICauge,
								SICproblemaAuge,
								SICfundamentoDiagnostico,
								SICexamenesRealizados,
								SICprofesionalDescripcion,
								SICrunProfesional,
								SICestado,
								SICusuarioSolicita,
								SICfechaSolicitudRegistro,
								SICusuarioAplica,
								SICfechaAplica,
								SICusuarioNoAplica,
								SICfechaNoAplica,
								SICEstadoAplicarEgreso
							)

					VALUES
						(
							'{$parametros['SICdau']}',
							'{$parametros['SICidPaciente']}',
							'{$parametros['SICfechaSolicitud']}',
							'{$parametros['SIChoraSolicitud']}',
							0,
							101100,
							101100,
							2,
							'{$parametros['SICespecialidadOrigen']}',
							NULL,
							'{$parametros['SICprioridad']}',
							'{$parametros['SICmotivoConsulta']}',
							'{$parametros['SICotroMotivo']}',
							'{$parametros['SIChipotesisDiagnostica']}',
							'{$parametros['SICauge']}',
							NULL,
							'{$parametros['SICfundamentoDiagnostico']}',
							NULL,
							'{$parametros['SICprofesionalDescripcion']}',
							'{$parametros['SICrunProfesional']}',
							1,
							NULL,
							NULL,
							NULL,
							NULL,
							NULL,
							NULL,
							0
						)";

		$response = $objCon -> ejecutarSQL($sql, "Error al insertar solicitud de interconsulta en RCE");

	}



	function eliminarSolicitudSIC ( $objCon, $idDau ) {

		$sql = "DELETE FROM
					rce.solicitud_sic
				WHERE
					rce.solicitud_sic.SICdau = '{$idDau}' ";

		$response = $objCon -> ejecutarSQL($sql, "Error al eliminar solicitud de interconsulta en RCE");

	}



	function obtenerResultadoSolicitudesInteconsultas ( $objCon, $parametros, &$totalPag, &$total ) {

		require_once("Util.class.php");       $objUtil    = new Util;

		if ( $_SESSION['pagina_actual'] < 1 ) {

			$_SESSION['pagina_actual'] = 1;

		}

		$limit = 10;

		$offset = ($_SESSION['pagina_actual']-1) * $limit;
		$condicion 	= "";
		$sql = " SELECT
					rce.solicitud_sic.SICdau AS idDau,
					rce.registroclinico.regId AS idRce,
					paciente.paciente.rut AS rutPaciente,
					CONCAT(paciente.paciente.nombres,' ',paciente.paciente.apellidopat,' ',paciente.paciente.apellidomat) AS nombreCompletoPaciente,
					parametros_clinicos.especialidad.ESPdescripcion AS descripcionEspecialidad,
					rce.solicitud_sic.SIChipotesisDiagnostica AS descripcionCie10,
					rce.solicitud_sic.SIC_urgencia AS idSolicitudSic,
					rce.solicitud_sic.SICusuarioAplica AS usuarioAplica,
					rce.solicitud_sic.SICusuarioNoAplica AS usuarioNoAplica,
					rce.solicitud_sic.SICfechaSolicitud AS fechaSolicitud,
					rce.solicitud_sic.SIChoraSolicitud AS horaSolicitud
				FROM
					rce.solicitud_sic
				INNER JOIN paciente.paciente ON rce.solicitud_sic.SICidPaciente = paciente.paciente.id
				INNER JOIN parametros_clinicos.especialidad ON rce.solicitud_sic.SICespecialidadOrigen = parametros_clinicos.especialidad.ESPcodigo
				INNER JOIN rce.registroclinico ON rce.solicitud_sic.SICdau = rce.registroclinico.dau_id
				INNER JOIN dau.dau ON dau.dau.dau_id = rce.solicitud_sic.SICdau
				WHERE SICEstadoAplicarEgreso = '1'
				";

				if ( $parametros['permisoUsuario'] == 'adultoPediatricoGinecologico' ) {
					$condicion .= " AND dau.dau.dau_atencion IN (1, 2, 3) ";
				}
				if ( $parametros['permisoUsuario'] == 'adultoPediatrico' ) {
					$condicion .= " AND dau.dau.dau_atencion IN (1, 2) ";
				}

				if ( $parametros['permisoUsuario'] == 'ginecologico' ) {
					$condicion .= " AND dau.dau.dau_atencion = 3 ";
				}

				if ( $parametros['frm_numeroDau'] ) {
					$condicion .= " AND rce.solicitud_sic.SICdau = '{$parametros['frm_numeroDau']}' ";
				}

				if ( $parametros['frm_runPaciente'] ) {
					$condicion .= " AND paciente.paciente.rut = '{$parametros['frm_runPaciente']}' ";
				}

				if ( $parametros['frm_nombrePaciente'] ) {
					$condicion .= " AND CONCAT(paciente.nombres,' ',paciente.apellidopat,' ',paciente.apellidomat) LIKE REPLACE('%{$parametros['frm_nombrePaciente']}%',' ','%')";
				}

				if ( $parametros['slc_especialidad'] ) {
					$condicion .= " AND parametros_clinicos.especialidad.ESPcodigo = '{$parametros['slc_especialidad']}' ";
				}

				if ( $parametros['frm_fechaSolicitud'] ) {
					$parametros['frm_fechaSolicitud'] = $objUtil->fechaInvertida(str_replace("/","-",$parametros['frm_fechaSolicitud']));
					$condicion .= " AND rce.solicitud_sic.SICfechaSolicitud = '{$parametros['frm_fechaSolicitud']}' ";
				}

				if ( empty($parametros['frm_numeroDau']) && empty($parametros['frm_nombrePaciente']) && empty($parametros['frm_runPaciente']) && empty($parametros['slc_especialidad']) && empty($parametros['frm_fechaSolicitud']) && empty($parametros['slc_estadoSolicitud']) ) {
					$condicion .= " AND rce.solicitud_sic.SICusuarioAplica IS NULL AND rce.solicitud_sic.SICusuarioNoAplica IS NULL";
					$order = 'ASC';
				}

				if ( ! empty($parametros['slc_estadoSolicitud']) && $parametros['slc_estadoSolicitud'] == 1 ) {
					$condicion .= " AND rce.solicitud_sic.SICusuarioAplica IS NOT NULL ";
					$order = 'DESC';
				}

				if ( ! empty($parametros['slc_estadoSolicitud']) && $parametros['slc_estadoSolicitud'] == 2 ) {
					$condicion .= " AND rce.solicitud_sic.SICusuarioNoAplica IS NOT NULL ";
					$order = 'DESC';
				}

				if ( ! empty($parametros['slc_estadoSolicitud']) && $parametros['slc_estadoSolicitud'] == 3 ) {
					$condicion .= " AND rce.solicitud_sic.SICusuarioAplica IS NULL AND rce.solicitud_sic.SICusuarioNoAplica IS NULL";
					$order = 'ASC';
				}

		$sql .= $condicion;

		$sql .= " ORDER BY rce.solicitud_sic.SIC_urgencia ".$order10;

		$datos = $objCon->consultaSQL($sql, '');

		$sqlTotalResultados = " SELECT FOUND_ROWS() as totalResultados";

		$totalResultados = $objCon->consultaSQL($sqlTotalResultados,"Error al obtener el total de resultado de solicitudes de interconsultas de acuerdo a los parámetros de búsqueda");

		$total    = $totalResultados[0]["totalResultados"];

		$sql  .= " LIMIT $offset, $limit";

		$datos = $objCon->consultaSQL($sql,"Error al obtener resultado de solicitudes de interconsultas de acuerdo a los parámetros de búsqueda");

		$totalPag = ceil($total/$limit);

		return $datos;

	}



	function noAplicarSolicitudInterconsulta ( $objCon, $parametros ) {

		$sql = "UPDATE
					rce.solicitud_sic
				SET
					rce.solicitud_sic.SICusuarioNoAplica      = '{$parametros['SICusuarioNoAplica']}',
					rce.solicitud_sic.SICfechaNoAplica   	  = NOW(),
					rce.solicitud_sic.SICobservacionSolicitud = '{$parametros['SICobservacionSolicitud']}'
				WHERE
					rce.solicitud_sic.SIC_urgencia = '{$parametros['SIC_urgencia']}' ";

		$response = $objCon -> ejecutarSQL($sql, "Error al no aplicar solicitud de interconsulta");

	}



	function aplicarSolicitudInterconsulta ( $objCon, $parametros ) {

		$sql       = "	UPDATE
							rce.solicitud_sic
						SET
							rce.solicitud_sic.SICusuarioAplica 	  	  = '{$parametros['SICusuarioAplica']}',
							rce.solicitud_sic.SICfechaAplica   		  = NOW(),
							rce.solicitud_sic.SICobservacionSolicitud = '{$parametros['SICobservacionSolicitud']}',
							rce.solicitud_sic.SICEstadoAplicarEgreso  = 1
						WHERE
							rce.solicitud_sic.SIC_urgencia = '{$parametros['SIC_urgencia']}' ";

		$response  = $objCon -> ejecutarSQL($sql, "Error al aplicar solicitud de interconsulta");

	}



	function observacionSolicitudInterconsulta ( $objCon, $idSolicitudSic ) {

		$sql 		= "	SELECT
							rce.solicitud_sic.SICobservacionSolicitud
						FROM
							rce.solicitud_sic
						WHERE
							rce.solicitud_sic.SIC_urgencia = '{$idSolicitudSic}'  ";

		$resultado = $objCon->consultaSQL($sql,"Error al obtener observación de interconsulta");

		return $resultado[0];

	}



	function obtenerPrioridadYMotivoSolicitudSICSegunDau ( $objCon, $idDau ) {

		$sql 		= "	SELECT
							rce.solicitud_sic.SICprioridad,
							rce.solicitud_sic.SICmotivoConsulta,
							rce.solicitud_sic.SICotroMotivo
						FROM
							rce.solicitud_sic
						WHERE
							rce.solicitud_sic.SICdau = '{$idDau}'  ";

		$resultado = $objCon->consultaSQL($sql,"Error al obtener prioridad y motivo de consulta según id de Dau");

		return $resultado[0];

	}



	function obtenerDatosSolicitudInterconsulta ( $objCon, $idSolicitudSic ) {

		$sql 		= "	SELECT
							rce.solicitud_sic.*
						FROM
							rce.solicitud_sic
						WHERE
							rce.solicitud_sic.SIC_urgencia = '{$idSolicitudSic}'  ";

		$resultado = $objCon->consultaSQL($sql,"Error al obtener datos de la solicitud de interconsulta");

		return $resultado[0];

	}



	function obtenerSolicitudesInterconsultaSegunDau ( $objCon, $idDau ) {

		$sql 		= "	SELECT
							rce.solicitud_sic.SIC_urgencia,
							rce.solicitud_sic.SICespecialidadOrigen,
							rce.solicitud_sic.SICusuarioAplica,
							rce.solicitud_sic.SICEstadoAplicarEgreso,
							dau.dau.dau_atencion
						FROM
							rce.solicitud_sic
						INNER JOIN
							dau.dau ON rce.solicitud_sic.SICdau = dau.dau.dau_id
						WHERE
							rce.solicitud_sic.SICdau = '{$idDau}'
						";

		$resultado = $objCon->consultaSQL($sql,"Error al obtener datos de la solicitud de interconsulta mediante el número de DAU");

		return $resultado;

	}



	function esInterconsultaPrimordial ( $objCon, $idEspecialidad ) {

		$sql 		= "	SELECT
							parametros_clinicos.especialidad.ESPprimordial
						FROM
							parametros_clinicos.especialidad
						WHERE
							parametros_clinicos.especialidad.ESPcodigo = '{$idEspecialidad}' ";

		$resultado = $objCon->consultaSQL($sql,"Error al obtener dato de que si especialidad es primordial o no");

		return $resultado[0];

	}



	function cambiarEstadoAplicarEgresoSolicitudSIC ( $objCon, $idDau ) {

		$sql       = "	UPDATE
							rce.solicitud_sic
						SET
							rce.solicitud_sic.SICEstadoAplicarEgreso = '1'
						WHERE
							rce.solicitud_sic.SICdau = '{$idDau}' ";

		$response  = $objCon -> ejecutarSQL($sql, "Error al cambiar estado de aplicar egreso en solicitud de interconsulta");

	}



	function obtenerPesoSignoVital ( $objCon, $idRCE ) {

		$sql 		= " SELECT
							rce.signo_vital.SVITALpeso
						FROM
							rce.signo_vital
						WHERE
							rce.signo_vital.idRCE = '{$idRCE}'
						ORDER BY
							rce.signo_vital.SVITALid DESC LIMIT 1 ";

		$resultado 	= $objCon->consultaSQL($sql,"Error al obtener peso de paciente en signos vitales");

		return $resultado[0];

	}



	function obtenerTallaSignoVital ( $objCon, $idRCE ) {

		$sql 		= " SELECT
							rce.signo_vital.SVITALtalla
						FROM
							rce.signo_vital
						WHERE
							rce.signo_vital.idRCE = '{$idRCE}'
						ORDER BY
							rce.signo_vital.SVITALid DESC LIMIT 1 ";

		$resultado 	= $objCon->consultaSQL($sql,"Error al obtener talla de paciente en signos vitales");

		return $resultado[0];

	}



	function obtenerCIE10FiltroAPS ( $objCon, $codigoCIE10 ) {
		$sql = "SELECT
					cie10.cie10.filtroSolicitudAPS
				FROM
					cie10.cie10
				WHERE
					cie10.cie10.codigoCIE = '{$codigoCIE10}' ";
		$resultado 	= $objCon->consultaSQL($sql,"Error al obtener filtro de solicitud de cesfam del código CIE10");
		return $resultado[0];
	}



	function insertarSolicitudAPS ( $objCon, $parametros ) {
		$sql = "INSERT INTO
					rce.solicitud_aps
						(
							idDau,
							idPaciente,
							fechaSolicitud,
							usuarioPideSolicitud,
							codigoCIE10,
							codigoConsultorio
						)
				VALUES 	(
							'{$parametros['idDau']}',
							'{$parametros['idPaciente']}',
							NOW(),
							'{$parametros['usuarioPideSolicitud']}',
							'{$parametros['codigoCIE10']}',
							'{$parametros['codigoConsultorio']}'
						)";

		$response = $objCon -> ejecutarSQL($sql, "Error registrar solicitud cesfam");

	}



	function obtenerResultadoSolicitudesAPS ( $objCon, $parametros, &$totalPag, &$total ) {

		require_once("Util.class.php");       $objUtil = new Util;

		if ( $_SESSION['pagina_actual'] < 1 ) {

			$_SESSION['pagina_actual'] = 1;

		}

		$condicion = '';

		$condicionCambioSQL = "";

		$limit = 10;

		$offset = ($_SESSION['pagina_actual']-1) * $limit;

		$fechaCambioSQL = "2023-07-01";

		$condicionAntesCambioSQL .= "
			  AND
					rce.solicitud_aps.fechaSolicitud < '{$fechaCambioSQL}'
			";

		$condicionCambioSQL .= "
			  AND
					rce.solicitud_aps.fechaSolicitud >= '{$fechaCambioSQL}'
				AND
				(
					dau.dau.dau_paciente_prevision IN ( 0, 1, 2, 3 )
					OR ( dau.dau.dau_paciente_prevision IN ( 0, 1, 2, 3 ) AND dau.dau.dau_paciente_forma_pago = 12 )
					OR ( dau.dau.dau_paciente_prevision = 4 AND dau.dau.dau_paciente_forma_pago = 12 )
					OR ( dau.dau.dau_paciente_prevision = 4 AND dau.dau.dau_paciente_forma_pago = 3 )
				)
				AND (
					paciente.paciente.rut <> 0
					AND paciente.paciente.rut <> '0-0'
					AND paciente.paciente.rut <> ''
					AND paciente.paciente.rut IS NOT NULL
				)
				AND dau.dau.dau_indicacion_egreso NOT IN (4,6)
			";


		$sql = "SELECT
					rce.solicitud_aps.idSolicitudAPS,
					rce.solicitud_aps.fechaSolicitud,
					rce.solicitud_aps.idDau,
					rce.solicitud_aps.idPaciente,
					rce.registroclinico.regId AS idRCE,
					CONCAT(paciente.paciente.nombres,' ',paciente.paciente.apellidopat,' ',paciente.paciente.apellidomat) AS nombrePaciente,
					paciente.paciente.rut as rutPaciente,
					dau.consultorios.con_id AS idConsultorio,
					dau.consultorios.con_descripcion AS descripcionConsultorio,
					rce.solicitud_aps_estados.descripcionEstadoSolicitud,
					CONCAT(cie10.cie10.codigoCIE,' - ',cie10.cie10.nombreCIE) AS descripcionCie10,
					rce.solicitud_aps_prioridades.descripcionPrioridadSolicitud
				FROM
					rce.solicitud_aps
				INNER JOIN
					dau.dau ON rce.solicitud_aps.idDau = dau.dau.dau_id
				INNER JOIN
					paciente.paciente FORCE INDEX (idx_idpaciente) ON rce.solicitud_aps.idPaciente = paciente.paciente.id
				INNER JOIN
					dau.consultorios FORCE INDEX (con_id) ON paciente.paciente.centroatencionprimaria = dau.consultorios.con_id
				INNER JOIN
					cie10.cie10 FORCE INDEX (index1) ON rce.solicitud_aps.codigoCIE10 = cie10.cie10.codigoCIE
				INNER JOIN
					rce.registroclinico FORCE INDEX (dau_id) ON rce.solicitud_aps.idDau = rce.registroclinico.dau_id
				INNER JOIN
					rce.solicitud_aps_estados ON rce.solicitud_aps_estados.idEstadoSolicitud = rce.solicitud_aps.estadoSolicitud
				LEFT JOIN
					rce.solicitud_aps_prioridades ON rce.solicitud_aps_prioridades.idPrioridadSolicitud = rce.solicitud_aps.prioridadSolicitud
				";

				if ( $parametros['frm_runPaciente'] ) {
					$condicion .= (! $objUtil->existe($condicion)) ? " WHERE " : " AND ";
					$condicion .= " paciente.paciente.rut = '{$parametros['frm_runPaciente']}' ";
				}

				if ( $parametros['frm_nombrePaciente'] ) {
					$condicion .= (! $objUtil->existe($condicion)) ? " WHERE " : " AND ";
					$condicion .= " CONCAT(paciente.nombres,' ',paciente.apellidopat,' ',paciente.apellidomat) LIKE REPLACE('%{$parametros['frm_nombrePaciente']}%',' ','%')";
				}

				if ( $parametros['slc_consultorio'] ) {
					$condicion .= (! $objUtil->existe($condicion)) ? " WHERE " : " AND ";
					$condicion .= " dau.consultorios.con_id = '{$parametros['slc_consultorio']}' ";
				}

				if ( $parametros['frm_fechaSolicitudDesde'] && $parametros['frm_fechaSolicitudHasta']) {
					$parametros['frm_fechaSolicitudDesde'] = $objUtil->fechaInvertida(str_replace("/","-",$parametros['frm_fechaSolicitudDesde']));
					$parametros['frm_fechaSolicitudHasta'] = $objUtil->fechaInvertida(str_replace("/","-",$parametros['frm_fechaSolicitudHasta']));
					$condicion .= (! $objUtil->existe($condicion)) ? " WHERE " : " AND ";
					$condicion .= " DATE(rce.solicitud_aps.fechaSolicitud) BETWEEN '{$parametros['frm_fechaSolicitudDesde']}' AND '{$parametros['frm_fechaSolicitudHasta']}'";
				} else if ( $parametros['frm_fechaSolicitudDesde'] && ! $parametros['frm_fechaSolicitudHasta'] ) {
					$parametros['frm_fechaSolicitudDesde'] = $objUtil->fechaInvertida(str_replace("/","-",$parametros['frm_fechaSolicitudDesde']));
					$condicion .= (! $objUtil->existe($condicion)) ? " WHERE " : " AND ";
					$condicion .= " DATE(rce.solicitud_aps.fechaSolicitud) = '{$parametros['frm_fechaSolicitudDesde']}'";
				}

				if ( $parametros['slc_prioridadSolicitud'] ) {
					$condicion .= (! $objUtil->existe($condicion)) ? " WHERE " : " AND ";
					$condicion .= " rce.solicitud_aps.prioridadSolicitud = '{$parametros['slc_prioridadSolicitud']}' ";
				}

				if ( (! empty($parametros['frm_numeroDau']) || ! empty($parametros['frm_runPaciente']) || ! empty($parametros['frm_nombrePaciente']) || ! empty($parametros['frm_fechaSolicitud'])) && empty($parametros['slc_estadoSolicitud']) ) {
					$condicion .= (! $objUtil->existe($condicion)) ? " WHERE " : " AND ";
					$condicion .= " rce.solicitud_aps.estadoSolicitud IN (1 , 2, 3, 4) ";
				} else if ( ! empty($parametros['slc_estadoSolicitud']) && $parametros['slc_estadoSolicitud'] != "TODOS"){
					$condicion .= (! $objUtil->existe($condicion)) ? " WHERE " : " AND ";
					$condicion .= " rce.solicitud_aps.estadoSolicitud = '{$parametros['slc_estadoSolicitud']}' ";
				} else if ( ! empty($parametros['slc_estadoSolicitud']) && $parametros['slc_estadoSolicitud'] == "TODOS" ){
					$condicion .= (! $objUtil->existe($condicion)) ? " WHERE " : " AND ";
					$condicion .= " rce.solicitud_aps.estadoSolicitud IN (1 , 2, 3, 4) ";
				} else {
					$condicion .= (! $objUtil->existe($condicion)) ? " WHERE " : " AND ";
					$condicion .= " rce.solicitud_aps.estadoSolicitud = 1 ";
				}

				$condicionOrderBy = " ORDER BY fechaSolicitud DESC";

		$sql = $sql.$condicion.$condicionAntesCambioSQL." UNION ".$sql.$condicion.$condicionCambioSQL.$condicionOrderBy;

		$datos = $objCon->consultaSQL($sql, '');

		$sqlTotalResultados = " SELECT FOUND_ROWS() as totalResultados";

		$totalResultados = $objCon->consultaSQL($sqlTotalResultados,"Error al obtener el total de resultado de solicitudes de cesfam de acuerdo a los parámetros de búsqueda");

		$total    = $totalResultados[0]["totalResultados"];

		$sql  .= " LIMIT $offset, $limit";

		$datos = $objCon->consultaSQL($sql,"Error al obtener resultado de solicitudes de cesfam de acuerdo a los parámetros de búsqueda");

		$totalPag = ceil($total/$limit);

		return $datos;
	}



	function obtenerResultadoSolicitudesAPSExcel ( $objCon, $parametros ) {

		require_once("Util.class.php");       $objUtil = new Util;

		$fechaCambioSQL = "2023-07-01";

		$condicionAntesCambioSQL .= "
			  AND
					rce.solicitud_aps.fechaSolicitud < '{$fechaCambioSQL}'
			";

		$condicionCambioSQL .= "
			  AND
					rce.solicitud_aps.fechaSolicitud >= '{$fechaCambioSQL}'
				AND
				(
					dau.dau.dau_paciente_prevision IN ( 0, 1, 2, 3 )
					OR ( dau.dau.dau_paciente_prevision IN ( 0, 1, 2, 3 ) AND dau.dau.dau_paciente_forma_pago = 12 )
					OR ( dau.dau.dau_paciente_prevision = 4 AND dau.dau.dau_paciente_forma_pago = 12 )
					OR ( dau.dau.dau_paciente_prevision = 4 AND dau.dau.dau_paciente_forma_pago = 3 )
				)
				AND (
					paciente.paciente.rut <> 0
					AND paciente.paciente.rut <> '0-0'
					AND paciente.paciente.rut <> ''
					AND paciente.paciente.rut IS NOT NULL
				)
				AND dau.dau.dau_indicacion_egreso NOT IN (4,6)
			";

			$condicion 	= "";
		$sql = "SELECT
					rce.solicitud_aps.idSolicitudAPS,
					rce.solicitud_aps.fechaSolicitud,
					rce.solicitud_aps.idDau,
					rce.solicitud_aps.idPaciente,
					rce.solicitud_aps.observacionSolicitud,
					rce.registroclinico.regId AS idRCE,
					CONCAT(paciente.paciente.nombres,' ',paciente.paciente.apellidopat,' ',paciente.paciente.apellidomat) AS nombrePaciente,
					paciente.paciente.rut as rutPaciente,
					dau.consultorios.con_id AS idConsultorio,
					dau.consultorios.con_descripcion AS descripcionConsultorio,
					rce.solicitud_aps_estados.descripcionEstadoSolicitud,
					CONCAT(cie10.cie10.codigoCIE,' - ',cie10.cie10.nombreCIE) AS descripcionCie10,
					rce.solicitud_aps_prioridades.descripcionPrioridadSolicitud
				FROM
					rce.solicitud_aps
				INNER JOIN
					dau.dau ON rce.solicitud_aps.idDau = dau.dau.dau_id
				INNER JOIN
					paciente.paciente FORCE INDEX (idx_idpaciente) ON rce.solicitud_aps.idPaciente = paciente.paciente.id
				INNER JOIN
					dau.consultorios FORCE INDEX (con_id) ON paciente.paciente.centroatencionprimaria = dau.consultorios.con_id
				INNER JOIN
					cie10.cie10 FORCE INDEX (index1) ON rce.solicitud_aps.codigoCIE10 = cie10.cie10.codigoCIE
				INNER JOIN
					rce.registroclinico FORCE INDEX (dau_id) ON rce.solicitud_aps.idDau = rce.registroclinico.dau_id
				INNER JOIN
					rce.solicitud_aps_estados ON rce.solicitud_aps_estados.idEstadoSolicitud = rce.solicitud_aps.estadoSolicitud
				LEFT JOIN
					rce.solicitud_aps_prioridades ON rce.solicitud_aps_prioridades.idPrioridadSolicitud = rce.solicitud_aps.prioridadSolicitud
				";

				if ( $parametros['frm_runPaciente'] ) {
					$condicion .= (! $objUtil->existe($condicion)) ? " WHERE " : " AND ";
					$condicion .= " paciente.paciente.rut = '{$parametros['frm_runPaciente']}' ";
				}

				if ( $parametros['frm_nombrePaciente'] ) {
					$condicion .= (! $objUtil->existe($condicion)) ? " WHERE " : " AND ";
					$condicion .= " CONCAT(paciente.nombres,' ',paciente.apellidopat,' ',paciente.apellidomat) LIKE REPLACE('%{$parametros['frm_nombrePaciente']}%',' ','%')";
				}

				if ( $parametros['slc_consultorio'] ) {
					$condicion .= (! $objUtil->existe($condicion)) ? " WHERE " : " AND ";
					$condicion .= " dau.consultorios.con_id = '{$parametros['slc_consultorio']}' ";
				}

				if ( $parametros['frm_fechaSolicitudDesde'] && $parametros['frm_fechaSolicitudHasta']) {
					$parametros['frm_fechaSolicitudDesde'] = $objUtil->fechaInvertida(str_replace("/","-",$parametros['frm_fechaSolicitudDesde']));
					$parametros['frm_fechaSolicitudHasta'] = $objUtil->fechaInvertida(str_replace("/","-",$parametros['frm_fechaSolicitudHasta']));
					$condicion .= (! $objUtil->existe($condicion)) ? " WHERE " : " AND ";
					$condicion .= " DATE(rce.solicitud_aps.fechaSolicitud) BETWEEN '{$parametros['frm_fechaSolicitudDesde']}' AND '{$parametros['frm_fechaSolicitudHasta']}'";
				} else if ( $parametros['frm_fechaSolicitudDesde'] && ! $parametros['frm_fechaSolicitudHasta'] ) {
					$parametros['frm_fechaSolicitudDesde'] = $objUtil->fechaInvertida(str_replace("/","-",$parametros['frm_fechaSolicitudDesde']));
					$condicion .= (! $objUtil->existe($condicion)) ? " WHERE " : " AND ";
					$condicion .= " DATE(rce.solicitud_aps.fechaSolicitud) = '{$parametros['frm_fechaSolicitudDesde']}'";
				}

				if ( $parametros['slc_prioridadSolicitud'] ) {
					$condicion .= (! $objUtil->existe($condicion)) ? " WHERE " : " AND ";
					$condicion .= " rce.solicitud_aps.prioridadSolicitud = '{$parametros['slc_prioridadSolicitud']}' ";
				}

				if ( (! empty($parametros['frm_numeroDau']) || ! empty($parametros['frm_runPaciente']) || ! empty($parametros['frm_nombrePaciente']) || ! empty($parametros['frm_fechaSolicitud'])) && empty($parametros['slc_estadoSolicitud']) ) {
					$condicion .= (! $objUtil->existe($condicion)) ? " WHERE " : " AND ";
					$condicion .= " rce.solicitud_aps.estadoSolicitud IN (1 , 2, 3, 4) ";
				} else if ( ! empty($parametros['slc_estadoSolicitud']) && $parametros['slc_estadoSolicitud'] != "TODOS"){
					$condicion .= (! $objUtil->existe($condicion)) ? " WHERE " : " AND ";
					$condicion .= " rce.solicitud_aps.estadoSolicitud = '{$parametros['slc_estadoSolicitud']}' ";
				} else if ( ! empty($parametros['slc_estadoSolicitud']) && $parametros['slc_estadoSolicitud'] == "TODOS" ){
					$condicion .= (! $objUtil->existe($condicion)) ? " WHERE " : " AND ";
					$condicion .= " rce.solicitud_aps.estadoSolicitud IN (1 , 2, 3, 4) ";
				} else {
					$condicion .= (! $objUtil->existe($condicion)) ? " WHERE " : " AND ";
					$condicion .= " rce.solicitud_aps.estadoSolicitud = 1 ";
				}

				$condicionOrderBy = " ORDER BY fechaSolicitud DESC";

		$sql = $sql.$condicion.$condicionAntesCambioSQL." UNION ".$sql.$condicion.$condicionCambioSQL.$condicionOrderBy;

		$datos = $objCon->consultaSQL($sql,"Error al obtener resultado de solicitudes de cesfam de acuerdo a los parámetros de búsqueda para excel");

		return $datos;

	}



	function obtenerInfoSolicitudAPS ( $objCon, $idSolicitudAPS ) {

		$sql = "SELECT
					rce.solicitud_aps.*
				FROM
					rce.solicitud_aps
				WHERE
					rce.solicitud_aps.idSolicitudAPS = '{$idSolicitudAPS}' ";

		$resultado 	= $objCon->consultaSQL($sql,"Error al obtener información de solicitud de cesfam");

		return $resultado[0];

	}



	function obtenerInfoSolicitudCambioEstado ( $objCon, $idSolicitudAPS ) {

		$sql = "SELECT
					rce.solicitud_aps_cambio_estado.*,
					rce.solicitud_aps_estados.descripcionEstadoSolicitud,
					rce.solicitud_aps_prioridades.descripcionPrioridadSolicitud,
					rce.solicitud_aps_programas.descripcionPrograma
				FROM
					rce.solicitud_aps_cambio_estado
				LEFT JOIN
					rce.solicitud_aps_estados ON rce.solicitud_aps_estados.idEstadoSolicitud = rce.solicitud_aps_cambio_estado.estadoSolicitud
				LEFT JOIN
					rce.solicitud_aps_prioridades ON rce.solicitud_aps_prioridades.idPrioridadSolicitud = rce.solicitud_aps_cambio_estado.prioridadSolicitud
				LEFT JOIN
					rce.solicitud_aps_programas ON rce.solicitud_aps_programas.idPrograma = rce.solicitud_aps_cambio_estado.programaSolicitud
				WHERE
					rce.solicitud_aps_cambio_estado.idSolicitudAPS = '{$idSolicitudAPS}' ";

		$resultado 	= $objCon->consultaSQL($sql,"Error al obtener información de cambios de estados en solicitud de cesfam");

		return $resultado;

	}



	function obtenerInfoSolicitudCambioConsultorio ( $objCon, $idSolicitudAPS ) {

		$sql = "SELECT
					rce.solicitud_aps_cambio_consultorio.*,
					consultorioPrevio.con_descripcion AS consultorioPrevio,
					consultorioActual.con_descripcion AS consultorioActual
				FROM
					rce.solicitud_aps_cambio_consultorio
				INNER JOIN
					dau.consultorios AS consultorioPrevio	ON rce.solicitud_aps_cambio_consultorio.codigoConsultorioPrevio  = consultorioPrevio.con_id
				INNER JOIN
					dau.consultorios AS consultorioActual	ON rce.solicitud_aps_cambio_consultorio.codigoConsultorioActual  = consultorioActual.con_id
				WHERE
					rce.solicitud_aps_cambio_consultorio.idSolicitudAPS = '{$idSolicitudAPS}' ";

		$resultado 	= $objCon->consultaSQL($sql,"Error al obtener información de cambios de consultorios en solicitud de cesfam");

		return $resultado;

	}



	function obtenerEstadosSolicitudAPS ( $objCon ) {

		$sql = "SELECT
					rce.solicitud_aps_estados.*
				FROM
					rce.solicitud_aps_estados";

		$resultado 	= $objCon->consultaSQL($sql,"Error al obtener estados de solicitudes APS");

		return $resultado;

	}



	function obtenerPrioridadesSolicitudAPS ( $objCon ) {

		$sql = "SELECT
					rce.solicitud_aps_prioridades.*
				FROM
					rce.solicitud_aps_prioridades";

		$resultado 	= $objCon->consultaSQL($sql,"Error al obtener prioridades de solicitudes APS");

		return $resultado;

	}



	function obtenerProgramasSolicitudAPS ( $objCon ) {

		$sql = "SELECT
					rce.solicitud_aps_programas.*
				FROM
					rce.solicitud_aps_programas
				ORDER BY
					rce.solicitud_aps_programas.descripcionPrograma ASC";

		$resultado 	= $objCon->consultaSQL($sql,"Error al obtener programas asociados a solicitudes APS");

		return $resultado;

	}



	function ingresarCambioConsultorio ( $objCon, $parametros ) {

		$sql = "INSERT INTO
					rce.solicitud_aps_cambio_consultorio
					(
						idSolicitudAPS,
						codigoConsultorioPrevio,
						codigoConsultorioActual,
						usuarioCambioConsultorio,
						fechaCambioConsultorio
					)
				VALUE
					(
						'{$parametros['idSolicitudAPS']}',
						'{$parametros['codigoConsultorioPrevio']}',
						'{$parametros['codigoConsultorioActual']}',
						'{$parametros['usuarioCambioConsultorio']}',
						NOW()
					)";

		$objCon->ejecutarSQL($sql, "Error al cambiar ingresar glosa de cambio de consultorio");

		$sql = "UPDATE
					rce.solicitud_aps
				SET
					rce.solicitud_aps.codigoConsultorio = '{$parametros['codigoConsultorioActual']}'
				WHERE
					rce.solicitud_aps.idSolicitudAPS = '{$parametros['idSolicitudAPS']}' ";

		$objCon -> ejecutarSQL($sql, "Error al cambiar consultorio a paciente");

	}



	function insertarCambioEstadoSolicitudAPS ( $objCon, $parametros ) {

		$sql = "INSERT INTO
					rce.solicitud_aps_cambio_estado
					(
						idSolicitudAPS,
						fechaCambioEstado,
						usuarioCambioEstado,
						estadoSolicitud,
						prioridadSolicitud,
						programaSolicitud,
						observacionSolicitud
					)
				VALUE
					(
						'{$parametros['idSolicitudAPS']}',
						NOW(),
						'{$parametros['usuarioAgendamientoSolicitud']}',
						'{$parametros['estadoSolicitud']}',
						'{$parametros['prioridadSolicitud']}',
						'{$parametros['programaSolicitud']}',
						'{$parametros['observacionSolicitud']}'
					)";

		$objCon->ejecutarSQL($sql, "Error al cambiar ingresar glosa de cambio de estado");

	}



	function agendarSolicitudAPS ( $objCon, $parametros ) {

		$sql = "UPDATE
					rce.solicitud_aps
				SET
					rce.solicitud_aps.fechaAgendamientoSolicitud   = NOW(),
					rce.solicitud_aps.usuarioAgendamientoSolicitud = '{$parametros['usuarioAgendamientoSolicitud']}',
					rce.solicitud_aps.estadoSolicitud              = '{$parametros['estadoSolicitud']}'";

					if ( $parametros['prioridadSolicitud'] ) {

						$sql .= ", rce.solicitud_aps.prioridadSolicitud = '{$parametros['prioridadSolicitud']}'";

					} else {

						$sql .= ", rce.solicitud_aps.prioridadSolicitud = NULL";

					}

					if ( $parametros['programaSolicitud'] ) {

						$sql .= ", rce.solicitud_aps.programaSolicitud = '{$parametros['programaSolicitud']}'";

					} else {

						$sql .= ", rce.solicitud_aps.programaSolicitud = NULL";

					}

					if ( $parametros['observacionSolicitud'] ) {

						$sql .= ", rce.solicitud_aps.observacionSolicitud = '{$parametros['observacionSolicitud']}' ";

					} else {

						$sql .= ", rce.solicitud_aps.observacionSolicitud = NULL";

					}

				$sql .= " WHERE
							rce.solicitud_aps.idSolicitudAPS = '{$parametros['idSolicitudAPS']}' ";

		$objCon -> ejecutarSQL($sql, "Error al actualizar agendamiento de solicitud APS");

	}



	function eliminarSolicitudAPS ( $objCon, $idDau ) {

		$sql = "DELETE
				FROM
					rce.solicitud_aps
				WHERE
					rce.solicitud_aps.idSolicitudAPS = '{$idDau}' ";

		$objCon -> ejecutarSQL($sql, "Error al eliminar solicitud aps");

	}



	function obtenerTiposViolencias ( $objCon ) {

		$sql = "SELECT
					rce.tipo_violencia.*
				FROM
					rce.tipo_violencia
				ORDER BY
					rce.tipo_violencia.descripcionTipoViolencia ASC";

		$datos = $objCon->consultaSQL($sql,"Error al obtener tipos de violencias");

		return $datos;

	}



	function obtenerTipoAgresorSegunViolencias ( $objCon, $idTipoViolencia ) {

		$tipoFiltroViolencia = '';

		if ( $idTipoViolencia == 2 ) {

			$tipoFiltroViolencia = "filtroOtrasViolencias";

		}

		if ( $idTipoViolencia == 3 ) {

			$tipoFiltroViolencia = "filtroSexual";

		}

		if ( $idTipoViolencia == 4 ) {

			$tipoFiltroViolencia = "filtroVIF";

		}

		$sql = "SELECT
					rce.tipo_agresor_violencia.idTipoAgresor,
					rce.tipo_agresor_violencia.descripcionTipoAgresor
				FROM
					rce.tipo_agresor_violencia
				WHERE
					{$tipoFiltroViolencia} = 'S'
				ORDER BY
					rce.tipo_agresor_violencia.descripcionTipoAgresor ASC";

		$datos = $objCon->consultaSQL($sql,"Error al obtener tipos de agresores según tipo de violencia");

		return $datos;

	}



	function obtenerTipoLesionesVictima ( $objCon ) {

		$sql = "SELECT
					rce.tipo_lesiones_victima.*
				FROM
					rce.tipo_lesiones_victima
				ORDER BY
					rce.tipo_lesiones_victima.descripcionLesionVictima ASC	";

		$datos = $objCon->consultaSQL($sql,"Error al obtener descripción de tipos de lesiones de la víctima");

		return $datos;

	}



	function obtenerSospechasPenetracion ( $objCon ) {

		$sql = "SELECT
					rce.tipo_sospecha_penetracion.*
				FROM
					rce.tipo_sospecha_penetracion
				WHERE
					rce.tipo_sospecha_penetracion.idTipoSospechaPenetracion IN (7,8)
				ORDER BY
					rce.tipo_sospecha_penetracion.descripcionSospechaPenetracion ASC";

		$datos = $objCon->consultaSQL($sql,"Error al obtener descripción de tipos de sospechas de penetración");

		return $datos;

	}



	function obtenerTipoProfilaxis ( $objCon ) {

		$sql = "SELECT
					rce.tipo_profilaxis.*
				FROM
					rce.tipo_profilaxis
				ORDER BY
					rce.tipo_profilaxis.descripcionProfilaxis ASC";

		$datos = $objCon->consultaSQL($sql,"Error al obtener descripción de tipos de profilaxis");

		return $datos;

	}



	function ingresarRegistroViolencia ( $objCon, $parametros ) {

		$sql = "INSERT INTO
					rce.registro_violencia
					(
						idRCE,
						idDau,
						idPaciente,
						idTipoViolencia,
						idTipoAgresor,
						idTipoLesionVictima,
						idTipoSospechaPenetracion,
						idTipoProfilaxis,
						victimaEmbarazada,
						peritoSexual,
						usuarioRegistraViolencia,
						fechaRegistraViolencia
					)
				VALUES
				(
					'{$parametros['idRCE']}',
					'{$parametros['idDau']}',
					'{$parametros['idPaciente']}',
					'{$parametros['idTipoViolencia']}',
					'{$parametros['idTipoAgresor']}',
					'{$parametros['idTipoLesionVictima']}',
					'{$parametros['idTipoSospechaPenetracion']}',
					'{$parametros['idTipoProfilaxis']}',
					'{$parametros['victimaEmbarazada']}',
					'{$parametros['peritoSexual']}',
					'{$parametros['usuarioRegistraViolencia']}',
					NOW()
				)";

		$objCon->ejecutarSQL($sql, "Error al insertar registro de violencia");

	}



	function obtenerRegistroViolencia ( $objCon, $idDau ) {

		$sql = "SELECT
					rce.registro_violencia.*
				FROM
					rce.registro_violencia
				WHERE
					rce.registro_violencia.idDau = '{$idDau}'";

		$datos = $objCon->consultaSQL($sql,"Error al obtener registro de violencia");

		return $datos[0];

	}



	function eliminarRegistroViolencia ( $objCon, $idDau ) {

		$sql = "DELETE FROM
					rce.registro_violencia
				WHERE
					rce.registro_violencia.idDau = '{$idDau}'";

		$objCon -> ejecutarSQL($sql, "Error al eliminar registro de violencia");

	}



	function obtenerRegistroViolenciaSegunRCE ( $objCon, $idRCE ) {

		$sql = "SELECT
					rce.tipo_violencia.descripcionTipoViolencia,
					rce.tipo_agresor_violencia.descripcionTipoAgresor,
					rce.tipo_lesiones_victima.descripcionLesionVictima,
					rce.tipo_sospecha_penetracion.descripcionSospechaPenetracion,
					rce.tipo_profilaxis.descripcionProfilaxis,
					rce.registro_violencia.victimaEmbarazada,
					rce.registro_violencia.peritoSexual
				FROM
					rce.registro_violencia
				LEFT JOIN
					rce.tipo_violencia ON rce.registro_violencia.idTipoViolencia = rce.tipo_violencia.idTipoViolencia
				LEFT JOIN
					rce.tipo_agresor_violencia ON rce.registro_violencia.idTipoAgresor = rce.tipo_agresor_violencia.idTipoAgresor
				LEFT JOIN
					rce.tipo_lesiones_victima ON rce.registro_violencia.idTipoLesionVictima = rce.tipo_lesiones_victima.idTipoLesionVictima
				LEFT JOIN
					rce.tipo_sospecha_penetracion ON rce.registro_violencia.idTipoSospechaPenetracion = rce.tipo_sospecha_penetracion.idTipoSospechaPenetracion
				LEFT JOIN
					rce.tipo_profilaxis ON rce.registro_violencia.idTipoProfilaxis = rce.tipo_profilaxis.idTipoProfilaxis
				WHERE
					rce.registro_violencia.idRCE = '{$idRCE}' ";

		$datos = $objCon->consultaSQL($sql,"Error al obtener registro de violencia");

		return $datos[0];

	}



	function pacienteCumpleCondicionesSolicitudAPS ( $objCon, $idDau ) {
		$sql = "
			SELECT
				dau.dau.id_paciente
			FROM
				dau.dau
			INNER JOIN
				paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
			WHERE
				(
					dau.dau.dau_paciente_prevision IN ( 0, 1, 2, 3 )
					OR ( dau.dau.dau_paciente_prevision IN ( 0, 1, 2, 3 ) AND dau.dau.dau_paciente_forma_pago = 12 )
					OR ( dau.dau.dau_paciente_prevision = 4 AND dau.dau.dau_paciente_forma_pago = 12 )
					OR ( dau.dau.dau_paciente_prevision = 4 AND dau.dau.dau_paciente_forma_pago = 3 )
				)
			AND (
				paciente.paciente.rut <> 0
				AND paciente.paciente.rut <> '0-0'
				AND paciente.paciente.rut <> ''
				AND paciente.paciente.rut IS NOT NULL
			)
			AND
				dau.dau.dau_indicacion_egreso NOT IN (4,6)
			AND
				dau.dau.dau_id = '{$idDau}'
		";

		$resultado 	= $objCon->consultaSQL($sql,"Error al obtener si paciente cumple con condiciones para solicitud APS");
		return $resultado;
	}



	function pacienteConSolicitudAPS ( $objCon, $idDau ) {

		$sql = "SELECT
					rce.solicitud_aps.idSolicitudAPS
				FROM
					rce.solicitud_aps
				WHERE
					rce.solicitud_aps.idDau = '{$idDau}'
				";

		$datos = $objCon->consultaSQL($sql,"Error al obtener id solicitud aps");

		return $datos[0];

	}



	function buscarIngresoSignosVitalesPrioritarios ( $objCon, $idRCE ) {

		$sql = "SELECT
					SUM(IF(rce.signo_vital.SVITALpulso IS NOT NULL OR rce.signo_vital.SVITALpulso <> '', 1, 0)) AS totalSignoVitalPulso,
					SUM(IF(rce.signo_vital.SVITALsistolica IS NOT NULL OR rce.signo_vital.SVITALsistolica <> '', 1, 0)) AS totalSignoVitalSistolica,
					SUM(IF(rce.signo_vital.SVITALdiastolica IS NOT NULL OR rce.signo_vital.SVITALdiastolica <> '', 1, 0)) AS totalSignoVitalDiastolica,
					SUM(IF(rce.signo_vital.SVITALtemperatura IS NOT NULL AND rce.signo_vital.SVITALtemperatura <> '', 1, 0)) AS totalSignoVitalTemperatura,
					SUM(IF(rce.signo_vital.SVITALfr IS NOT NULL OR rce.signo_vital.SVITALfr <> '', 1, 0)) AS totalSignoVitalFR,
					SUM(IF(rce.signo_vital.SVITALsaturacion IS NOT NULL OR rce.signo_vital.SVITALsaturacion <> '', 1, 0)) AS totalSignoVitalSaturacion,
					SUM(IF(rce.signo_vital.SVITALEVA IS NOT NULL OR rce.signo_vital.SVITALEVA <> '', 1, 0)) AS totalSignoVitalEVA,
					SUM(IF(rce.signo_vital.SVITALGlasgow IS NOT NULL OR rce.signo_vital.SVITALGlasgow <> '', 1, 0)) AS totalSignoVitalGlasgow
				FROM
					rce.signo_vital
				WHERE
					rce.signo_vital.idRCE = '{$idRCE}'
				";

		$datos = $objCon->consultaSQL($sql,"Error al obtener id solicitud aps");

		return $datos[0];

	}



	function obtenerEstadoSAU ( $objCon, $idDAU ) {

		$sql = "SELECT
					rce.solicitud_altaurgencia.SAUestado
				FROM
					rce.solicitud_altaurgencia
				WHERE
					rce.solicitud_altaurgencia.SAUidDau = '{$idDAU}'
				ORDER BY
					rce.solicitud_altaurgencia.SAUid  DESC LIMIT 1
			";

		$datos = $objCon->consultaSQL($sql,"Error al obtener estado solicitud alta urgencia");

		return $datos[0];

	}

	function actualizarFolioSIC ( $objCon, $SIC_urgencia,$INTcodigo ) {

		$sql       = "	UPDATE
							rce.solicitud_sic
						SET
							rce.solicitud_sic.SICfolio = $INTcodigo
						WHERE
							rce.solicitud_sic.SIC_urgencia = '{$SIC_urgencia}' ";

		$response  = $objCon -> ejecutarSQL($sql, "Error al cambiar estado de aplicar egreso en solicitud de interconsulta");

	}


	function get_rce_dau($objCon, $idDAU){
		
		$sql = "SELECT * FROM rce.registroclinico WHERE dau_id = '{$idDAU}'";

		$datos = $objCon->consultaSQL($sql,"Error al obtener get_rce_dau");

		return $datos;

	}


	function actualizarRCE_Interconsulta ( $objCon, $regId,$INTcodigo ) {

		$sql       = "	UPDATE
							agenda.interconsulta
						SET
							agenda.interconsulta.regId = $regId
						WHERE
							agenda.interconsulta.INTcodigo = '{$INTcodigo}' ";

		$response  = $objCon -> ejecutarSQL($sql, "Error al cambiar estado de aplicar egreso en solicitud de interconsulta");

	}


}
?>
