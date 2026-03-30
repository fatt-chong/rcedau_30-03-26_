<?php 
class Agenda{
	function obtenerPaciente2($objCon,$PACidentificador){
		$sql = "SELECT 
			paciente.id,paciente.nacionalidad,paciente.paisNacimiento, paciente.calle, paciente.numero,paciente.restodedireccion, paciente.nombres,paciente.conruralidad,paciente.sector_domicilio,paciente.calle,paciente.numero,paciente.email, paciente.restodedireccion, paciente.rut, paciente.etnia, paciente.PACafro,paciente.apellidopat, paciente.apellidomat, paciente.region,paciente.ciudad,paciente.idcomunacodigo,paciente.fechanac,paciente.act_fonasa_fecha,paciente.act_fonasa_hrs,paciente.act_fonasa_folio,
			CONCAT(paciente.nombres,' ',paciente.apellidopat,' ',paciente.apellidomat) AS nombre_completo, paciente.sexo,
			paciente.direccion,	paciente.prevision,	paciente.nroficha, paciente.idcomuna, paciente.email, paciente.fono1, paciente.fono2, paciente.fono3,
			paciente.centroatencionprimaria, prev.instdetNombre, paciente.conveniopago, paciente.id_trakcare, paciente.nroficha, paciente.fallecido, paciente.prais, comuna.comuna, conv.instNombre,paciente.PACfono,paciente.PACcelular,paciente.extranjero,paciente.etnia,paciente.PACafro,paciente.PACdireccion,paciente.PACpoblacion,paciente.PACnumeroVivienda,DATE_FORMAT(paciente.PACfechaUpdateHjnc,'%d-%m-%Y') as PACfechaUpdateHjnc,DATE_FORMAT(paciente.PACfechaUpdateAvis,'%d-%m-%Y') as PACfechaUpdateAvis
				FROM paciente.paciente
				LEFT JOIN paciente.comuna ON comuna.id = paciente.idcomuna
				LEFT JOIN paciente.institucion AS conv ON conv.instCod = paciente.conveniopago
				LEFT JOIN paciente.instituciondetalle AS prev ON prev.previCod = paciente.prevision
				WHERE paciente.id = '$PACidentificador'"; 
		
		$datos 			= $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
		return $datos[0];
	}
	function obtenerCentroOftalmologicoNewGrupo($objCon,$ESTAorigen){
		$sql 	= "SELECT ESTAgrupo FROM parametros_clinicos.establecimiento WHERE establecimiento.ESTAcodigo = '$ESTAorigen'";
		$datos	= $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
		return $datos[0]['ESTAgrupo'];
	}
	function obtenerCentroOftalmologicoNewCie10($objCon,$DIAGcie10){
	  $sql = "SELECT Count(cie10.codigoCIE) as cantidad FROM cie10.cie10 WHERE cie10.UAPO = 'S' AND cie10.codigoCIE not LIKE 'H52%' and cie10.codigoCIE='$DIAGcie10'";
		$datos	= $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
		return $datos[0]['cantidad'];
	}
	function obtenerCentroOftalmologicoNewCie10h52($objCon,$DIAGcie10){
	   $sql = "SELECT Count(cie10.codigoCIE) as cantidad FROM cie10.cie10 WHERE cie10.UAPO = 'S' AND cie10.codigoCIE  LIKE 'H52%' and cie10.codigoCIE='$DIAGcie10'";
		$datos	= $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
		return $datos[0]['cantidad'];
	}
	function obtenerCentroOftalmologicoNewCie10Full($objCon,$DIAGcie10){
	  	$sql 	= "SELECT Count(cie10.codigoCIE) as cantidad FROM cie10.cie10 WHERE cie10.UAPO = 'S' and cie10.codigoCIE='$DIAGcie10'";
		$datos	= $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
		return $datos[0]['cantidad'];
	}
	function obtenerCentroOftalmologicoNew($objCon,$PACedad,$DIAGcie10,$ESTAorigen){
		$datos					= $this->obtenerCentroOftalmologicoNewGrupo($objCon,$ESTAorigen);
		$establecimientoDestino	= '';
		$cie10Valido 			= $this->obtenerCentroOftalmologicoNewCie10($objCon,$DIAGcie10);
		$cie10ValidoSoloh52 	= $this->obtenerCentroOftalmologicoNewCie10h52($objCon,$DIAGcie10);
		$cie10ValidoSoloFull 	= $this->obtenerCentroOftalmologicoNewCie10Full($objCon,$DIAGcie10);

		$regla1= array('APS_PUTRE','APS_GLAGOS','APS_CAMARONES','APS_ARICA','SSARICA');
		$regla2= array('APS_PUTRE','APS_GLAGOS','APS_CAMARONES','APS_ARICA','SSARICA');
		$regla3= array('APS_PUTRE','APS_GLAGOS','APS_CAMARONES','APS_ARICA','SSARICA');
		$regla4= array('APS_PUTRE','APS_GLAGOS','APS_CAMARONES','SSARICA');
		$regla5= array('APS_ARICA');
		$regla6= array('APS_ARICA');
		$regla7= array('UAPO');
		$regla8= array('HJNC');
		$regla9= array('HJNC');

		if (array_search($datos,$regla1) && $PACedad >= 0 && $PACedad <= 14 && $cie10Valido>0) {
					 $establecimientoDestino= '101100';
		}else if (array_search($datos,$regla2) && $PACedad >= 0 && $PACedad <= 14 && $cie10ValidoSoloh52>0) {
					 $establecimientoDestino= '101100';
		}else if (array_search($datos,$regla3) && $PACedad >= 15 && $cie10Valido>0) {
			 $establecimientoDestino= '101100';
		}else if (array_search($datos,$regla4) && $PACedad >= 15 && $cie10ValidoSoloh52>0) {
			 $establecimientoDestino= '101999';
		}else if (array_search($datos,$regla5) && $PACedad >= 15 &&  $PACedad <= 64 && $cie10ValidoSoloh52>0) {
			 $establecimientoDestino= '101999';
		}else if (array_search($datos,$regla6) && $PACedad >= 65 && $cie10ValidoSoloh52>0) {
			 $establecimientoDestino= '101100';
		}else if (array_search($datos,$regla7) && $cie10ValidoSoloFull>0) {
			 $establecimientoDestino= '101100';
		}else if (array_search($datos,$regla8) && $cie10Valido>0) {
			 $establecimientoDestino= '101100';
		}else if (array_search($datos,$regla9) && $cie10ValidoSoloh52>0) {
			 $establecimientoDestino= '101100';
		}else{
			 $establecimientoDestino= '101100';
		}
		return $establecimientoDestino;
	}
	function obtenerEstablecimientoMapaDerivacion($objCon,$ESPcodigo,$ESTAorigen,$PACedad,$DIAGcie10){
		switch($ESPcodigo){
			case '07-117-0':
				switch($ESTAorigen){
					case '101100':		return 101608;//HOSPITAL -> ESMASUR
										break;
					case '101300':		return 101607;//BERTIN S -> ESMASUR
										break;
					case '101302':		return 101607;//A NEGHME -> ESMASUR
										break;
					case '101303':		return 101608;//HOSPITAL -> ESMASUR
										break;
					case '101305':		return 101608;//HOSPITAL -> ESMASUR
										break;
					case '101306':		return 101608;//HOSPITAL -> ESMASUR
										break;
					case '101307':		return 101607;//HOSPITAL -> ESMASUR
										break;
					case '101400':		return 101608;//HOSPITAL -> ESMASUR
										break;
					case '101401':		return 101608;//HOSPITAL -> ESMASUR
										break;
					case '101408':		return 101608;//HOSPITAL -> ESMASUR
										break;
					case '101607':		return 101608;//HOSPITAL -> ESMASUR
										break;
					case '101608':		return 101608;//HOSPITAL -> ESMASUR
										break;
					case '101702':		return 101608;//HOSPITAL -> ESMASUR
										break;
					case '101703':		return 101608;//HOSPITAL -> ESMASUR
										break;
					case '101704':		return 101608;//HOSPITAL -> ESMASUR
										break;
					case '101011':		return 101608;//HOSPITAL -> ESMASUR
										break;
					case '101304':		return 101608;//HOSPITAL -> ESMASUR
										break;
					case '101402':		return 101608;//HOSPITAL -> ESMASUR
										break;
					case '101404':		return 101608;//HOSPITAL -> ESMASUR
										break;
					case '101405':		return 101608;//HOSPITAL -> ESMASUR
										break;
					case '101406':		return 101608;//HOSPITAL -> ESMASUR
										break;
					case '101407':		return 101608;//HOSPITAL -> ESMASUR
										break;
				}
			break;
			case '07-400-9':	$respuesta = $this->obtenerCentroOftalmologicoNew($objCon,$PACedad,$DIAGcie10,$ESTAorigen);
						return $respuesta;
			default:	return 101100;
						break;
		}
	}
	function obtenerEstablecimiento($objCon, $ESTAcodigo){
		$sql 	= "SELECT ESTAcodigo, ESTAdescripcion,ESTAtelefono
				FROM parametros_clinicos.establecimiento
				WHERE ESTAcodigo = '$ESTAcodigo'";
		$datos	= $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
		return $datos[0];
	}

	function get_derivacion_essmass($objCon,$parametros){    
        $sql="SELECT
                    centroatencion_indicepac.cai_id, 
                    centroatencion_indicepac.cai_nom, 
                    centroatencion_indicepac.ESTAcodigo, 
                    centroatencion_indicepac.derivacion, 
                    establecimiento.ESTAdescripcion
                FROM
                    parametros_clinicos.centroatencion_indicepac
                    INNER JOIN
                    parametros_clinicos.establecimiento
                    ON 
                        centroatencion_indicepac.derivacion = establecimiento.ESTAcodigo
                WHERE
                    cai_id = '{$parametros['ct_primaria']}' ";
        $datos = $objCon->consultaSQL($sql,"<br>Error al listar get_derivacion_essmass <br>");
        return $datos;
    }
	function insertarNuevaInterconsulta2($objUtil,$objCon,$PACidentificador,$INTfolio,$fecha,$hora,$est_origen,$est_destino,$procedencia,$esp_origen,$esp_destino,$prioridad,$motivo_consulta,$otro_motivo,$hipotesis_diagnostica,$auge,$problema_auge,$subproblema_auge,$fundamento_diagnostico,$examenes_realizados,$profesional,$run_profesional,$INTrau,$INTestado,$contralor){
		
		$RSpaciente 				= $this->obtenerPaciente2($objCon,$PACidentificador);

        $parametros['ct_primaria']  = $RSpaciente["centroatencionprimaria"];

		if( $est_origen =='101100' && ($esp_destino == '07-117-1' || $esp_destino == '07-117-2')){;
            $rs_centroAtencion = $this->get_derivacion_essmass($objCon,$parametros);
            if (!empty($rs_centroAtencion[0]['derivacion'])) {
                // Si derivacion NO es nulo ni vacío
                $RSestablecimiento_origen                   		= $this->obtenerEstablecimiento($objCon, $est_origen); 
                $est_origen					                   		= $RSestablecimiento_origen['ESTAcodigo'];
                $RSestablecimiento_origen['ESTAdescripcion']     	= $RSestablecimiento_origen['ESTAdescripcion'];

                $ESTAcodigo_derivado           						= $rs_centroAtencion[0]['derivacion'];
                $RSestablecimiento_destino['ESTAdescripcion']       = $rs_centroAtencion[0]['ESTAdescripcion'];
            } else {
                // Si derivacion es nulo o vacío
                $RSestablecimiento_origen                    		= $this->obtenerEstablecimiento($objCon, $est_origen);
                $est_origen					                    	= $RSestablecimiento_origen['ESTAcodigo'];
                $RSestablecimiento_origen['ESTAdescripcion']      	= $RSestablecimiento_origen['ESTAdescripcion'];

                $ESTAcodigo_derivado           						= "101608"; // por defecto a centro de salud|
                $RSestablecimiento_destino['ESTAdescripcion']     	= "ESSMA SUR DE ARICA";
            }

        }else{
            $RSestablecimiento_origen 	= $this->obtenerEstablecimiento($objCon,$est_origen);
			$ESTAcodigo_derivado 		= $this->obtenerEstablecimientoMapaDerivacion($objCon,$esp_destino, $est_origen,$objUtil->edadActual($RSpaciente['fechanac']),$hipotesis_diagnostica);
			$RSestablecimiento_destino 	= $this->obtenerEstablecimiento($objCon,$ESTAcodigo_derivado);            
        }

		
		$sql = "INSERT INTO agenda.interconsulta (INTfecha_solicitud,INThora_solicitud,INTfolio,INTestablecimiento_origen,INTestablecimiento_origen_descripcion,
											INTestablecimiento_destino,INTestablecimiento_destino_descripcion,
											PACidentificador,PACrut,PACficha,PACfecha_nac,PACnombre_completo,
											INTprocedencia,INTespecialidad_origen,INTespecialidad_destino,INTprioridad,
											INTmotivo_solicitud,INTotro_motivo,INThipotesis_diagnostica,
											INTauge,INTauge_problema,INTauge_subproblema,INTfundamento_diagnostico,
											INTexamenes_realizados,INTprofesional,INTrut_profesional,INTrau,INTestado,contralor)
									VALUES('$fecha','$hora','$INTfolio','$est_origen','$RSestablecimiento_origen[ESTAdescripcion]','$ESTAcodigo_derivado','$RSestablecimiento_destino[ESTAdescripcion]','$PACidentificador','$RSpaciente[rut]','$RSpaciente[nroficha]','$RSpaciente[fechanac]','$RSpaciente[nombre_completo]',
											'$procedencia','$esp_origen','$esp_destino','$prioridad',
											'$motivo_consulta','$otro_motivo','$hipotesis_diagnostica',
											'$auge','$problema_auge','$subproblema_auge','$fundamento_diagnostico',
											'$examenes_realizados','$profesional','$run_profesional','$INTrau',$INTestado,'$contralor')";
		$responde 	= $objCon->ejecutarSQL($sql, "Error al insertarNuevaInterconsulta2");
		$INTcodigo	= $objCon->lastInsertId();
		if($INTestado == 1){
			$this->insertarMovimientoInterconsulta($objCon, $INTcodigo, $est_destino, '', '', '', 'CREACION SIC', 1, $_SESSION['MM_Username'.SessionName]);
			$this->insertarAccionLog($objCon,'interconsulta',$INTcodigo,$_SESSION['MM_Username'.SessionName],'CREACION SIC','','FOLIO:'.$INTfolio,$PACidentificador);
		}else{
			$this->insertarMovimientoInterconsulta($objCon, $INTcodigo, $est_destino, '', '', '', 'SOLICITUD DE SIC', 0, $_SESSION['MM_Username'.SessionName]);
			$this->insertarAccionLog($objCon,'interconsulta',$INTcodigo,$_SESSION['MM_Username'.SessionName],'SOLICITUD DE SIC','','FOLIO:'.$INTfolio,$PACidentificador);
		}
		return $INTcodigo;
	}
	function obtenerRecurso($objCon, $recurso){
		$recurso 			= trim($recurso);
		list($RECcodigo) 	= explode(" ", $recurso);
		$sql = "SELECT RECcodigo as RECcodigo,
					NULL AS TIPROcodigo,
					NULL AS ESPcodigo,
					RECdescripcion as descripcion,
					RECnombres AS nombres,
					RECapellido_paterno AS apellido_paterno,
					RECapellido_materno AS apellido_materno
				FROM agenda.recurso
				WHERE RECtipo <> 'S' AND RECcodigo = '$RECcodigo'
				UNION
				SELECT pro.PROcodigo as RECcodigo,
					pro.TIPROcodigo,
					proesp.ESPcodigo,
					pro.PROdescripcion as descripcion,
					pro.PROnombres AS nombres,
					pro.PROapellidopat AS apellido_paterno,
					pro.PROapellidomat AS apellido_materno
				FROM parametros_clinicos.profesional AS pro
				LEFT JOIN parametros_clinicos.profesional_has_especialidad AS proesp USING (PROcodigo)
				WHERE pro.PROactivo = 'S' AND pro.PROcodigo = '$RECcodigo'"; // echo $sql;

		$datos 	= $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
		return ($datos[0]);
	}
	function obtenerRecursoMedico($objCon, $RECcodigo, $tipo_profesional){
		$sql 	= "SELECT 
					PROcodigo as RECcodigo, 
					PROdescripcion as descripcion
				FROM parametros_clinicos.profesional
				WHERE PROcodigo ='$RECcodigo'
				AND TIPROcodigo = '$tipo_profesional'";
		
		$datos 	= $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
		return ($datos[0]);
	}
	function reversarInterconsulta($objCon, $INTcodigo, $motivo, $comentario, $tipo_reversa){
		$RSsic 	= $this->obtenerInterconsulta($objCon,$INTcodigo);
		$est 	= 1;
		if($RSsic['INTestado']==5){
			$sql = "UPDATE agenda.interconsulta SET INTestado = 6, CITcodigo = NULL, CITfecha = NULL, RECcodigo = NULL WHERE INTcodigo = '$INTcodigo'";
			$est = 6;
		}else{
			$sql = "UPDATE agenda.interconsulta SET INTestado = 1, CITcodigo = NULL, CITfecha = NULL, RECcodigo = NULL WHERE INTcodigo = '$INTcodigo'";
		}
		$objCon->ejecutarSQL($sql, "Error al actualizar médico involucrado en ginecología");

		$this->insertarMovimientoInterconsulta($objCon, $INTcodigo, 101100, '', '', '', $tipo_reversa, $est, $_SESSION['MM_Username'.SessionName]);
		$this->insertarAccionLog($objCon,'interconsulta',$INTcodigo,$_SESSION['MM_Username'.SessionName],'REVERSAR SIC A INICIAL','','FOLIO:'.$INTcodigo,$PACidentificador);

		$RScita 	= $this->obtenerCita($objCon,$RSsic['CITcodigo']);
		$RSrecurso 	= $this->obtenerRecurso($objCon,$RScita['RECcodigo']);
		if($RSsic['INTid_avis']){//INTERCONSULTA DE AVIS
			$this->insertarMensajeAvis($objCon,'CE',$INTcodigo,$RSsic['INTid_avis'],$RScita['CITcodigo'],$RScita['CITfecha'],$RScita['CIThora_inicio'],$RScita['RECcodigo'].'-'.$objUtil->generaDigito($RScita['RECcodigo']),$RSrecurso['apellido_paterno']." ".$RSrecurso['apellido_materno'],$RSrecurso['nombres'],23, $comentario,$motivo,$objUtil->getTimeStamp(),'');
		}
		return $RSsic['INTprocedencia'];
	}
	function copiarCitaHistorico($objCon, $CITcodigo){
		$sql = "SELECT *
				FROM
				agenda.cita
				WHERE cita.CITcodigo = '$CITcodigo'";
		$datos 			= $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
		$RScita 		= $datos[0];

		$AGEcodigo 					= $RScita['AGEcodigo'];
		$ESTAcodigo 				= $RScita['ESTAcodigo'];
		$CUPcodigo 					= $RScita['CUPcodigo'];
		$CITcodigo2 				= $RScita['CITcodigo'];
		$LOCcodigo 					= $RScita['LOCcodigo'];
		$LOCdescripcion 			= $RScita['LOCdescripcion'];
		$RECcodigo 					= $RScita['RECcodigo'];
		$RECdescripcion 			= $RScita['RECdescripcion'];
		$PROGcodigo 				= $RScita['PROGcodigo'];
		$PROGdescripcion 			= $RScita['PROGdescripcion'];
		$TIATcodigo 				= $RScita['TIATcodigo'];
		$TIATdescripcion 			= $RScita['TIATdescripcion'];
		$TIATclasificacion 			= $RScita['TIATclasificacion'];
		$CITfecha 					= $RScita['CITfecha'];
		$CITdia 					= $RScita['CITdia'];
		$CIThora_inicio 			= $RScita['CIThora_inicio'];
		$CITduracion 				= $RScita['CITduracion'];
		$CITsobrecupo 				= $RScita['CITsobrecupo'];
		$CITestado_cita 			= $RScita['CITestado_cita'];
		$INTcodigo 					= $RScita['INTcodigo'];
		$PACidentificador 			= $RScita['PACidentificador'];
		$PACrut 					= $RScita['PACrut'];
		$PACficha 					= $RScita['PACficha'];
		$PACnombre_completo 		= $RScita['PACnombre_completo'];
		$PACedad 					= $RScita['PACedad'];
		$PACprevision 				= $RScita['PACprevision'];
		$PACconvenio 				= $RScita['PACconvenio'];
		$PREcodigo 					= $RScita['PREcodigo'];
		$PREdescripcion 			= $RScita['PREdescripcion'];
		$CITatencion_sobrescrita 	= $RScita['CITatencion_sobrescrita'];
		$CITatencion_anterior 		= $RScita['CITatencion_anterior'];
		$CITespontaneo 				= $RScita['CITespontaneo'];
		$CITestado_anterior 		= $RScita['CITestado_anterior'];
		$CITalta 					= $RScita['CITalta'];
		$CITges 					= $RScita['CITges'];
		$CITpertinencia 			= $RScita['CITpertinencia'];
		$CITpertinencia_tiempo 		= $RScita['CITpertinencia_tiempo'];
		$CITna_motivo 				= $RScita['CITna_motivo'];
		$CITestado_paciente 		= $RScita['CITestado_paciente'];
		$CITadmitido_stamp 			= $RScita['CITadmitido_stamp'];
		$CITbox_stamp 				= $RScita['CITbox_stamp'];
		$CITnota 					= $RScita['CITnota'];
		$CITusuario_agenda 			= $RScita['CITusuario_agenda'];
		$CITusuario_bloquea 		= $RScita['CITusuario_bloquea'];
		$CITusuario_cancela 		= $RScita['CITusuario_cancela'];
		$CITmotivo_bloqueo 			= $RScita['CITmotivo_bloqueo'];
		$CITmotivo_cancela 			= $RScita['CITmotivo_cancela'];
		$CITobservacion_cancela 	= $RScita['CITobservacion_cancela'];
		$CITctacte 					= $RScita['CITctacte'];
		$REGcodigo 					= $RScita['REGcodigo'];
		$TIATaps 					= $RScita['TIATaps'];
		$FICdespachada 				= $RScita['FICdespachada'];
		$CITcie10abierto 			= $RScita['CITcie10abierto']; //danny
		$CITcie10 					= $RScita['CITcie10'];   //danny
		$sql = "INSERT INTO 
		agenda.cita_historico (
			AGEcodigo, 
			ESTAcodigo,
			CUPcodigo,
			CITcodigo,
			LOCcodigo,
			LOCdescripcion,
			RECcodigo,
			RECdescripcion,
			PROGcodigo,
			PROGdescripcion,
			TIATcodigo,
			TIATdescripcion,
			TIATclasificacion,
			CITfecha,
			CITdia,
			CIThora_inicio,
			CITduracion,
			CITnota,
			CITsobrecupo,
			CITestado_cita,
			INTcodigo,
			PACidentificador,
			PACrut,
			PACficha,
			PACnombre_completo,
			PACedad,
			PACprevision,
			PACconvenio,
			PREcodigo,
			PREdescripcion,
			CITatencion_sobrescrita,
			CITatencion_anterior,
			CITalta,
			CITges,
			CITespontaneo,
			CITestado_anterior,
			CITpertinencia,
			CITpertinencia_tiempo,
			CITna_motivo,
			CITestado_paciente,
			CITadmitido_stamp,
			FICdespachada,
			CITbox_stamp,
			CITusuario_agenda,
			CITusuario_bloquea,
			CITusuario_cancela,
			CITmotivo_bloqueo,
			CITmotivo_cancela,
			CITobservacion_cancela,
			CITctacte,
			CITcie10,
			CITcie10abierto,
			REGcodigo,
			TIATaps
		)
		VALUES(
			'$AGEcodigo',
			'$ESTAcodigo',
			'$CUPcodigo',
			'$CITcodigo2',
			'$LOCcodigo',
			'$LOCdescripcion',
			'$RECcodigo',
			'$RECdescripcion',
			'$PROGcodigo',
			'$PROGdescripcion',
			'$TIATcodigo',
			'$TIATdescripcion',
			'$TIATclasificacion',
			'$CITfecha',
			'$CITdia',
			'$CIThora_inicio',
			'$CITduracion',
			'$CITnota',
			'$CITsobrecupo',
			'$CITestado_cita',
			'$INTcodigo',
			'$PACidentificador',
			'$PACrut',
			'$PACficha',
			'$PACnombre_completo',
			'$PACedad',
			'$PACprevision',
			'$PACconvenio',
			'$PREcodigo',
			'$PREdescripcion',
			'$CITatencion_sobrescrita',
			'$CITatencion_anterior',
			'$CITalta',
			'$CITges',
			'$CITespontaneo',
			'$CITestado_anterior',
			'$CITpertinencia',
			'$CITpertinencia_tiempo',
			'$CITna_motivo',
			'$CITestado_paciente',
			'$CITadmitido_stamp',
			'$FICdespachada',
			'$CITbox_stamp',
			'$CITusuario_agenda',
			'$CITusuario_bloquea',
			'$CITusuario_cancela',
			'$CITmotivo_bloqueo',
			'$CITmotivo_cancela',
			'$CITobservacion_cancela',
			'$CITctacte',
			'".$CITcie10."',
			'".$CITcie10abierto."',
			'$REGcodigo',
			'$TIATaps'
		)";
		$responde 	= $objCon->ejecutarSQL($sql, "Error al insertarNuevaInterconsulta");
	}
	function obtenerCita($objCon, $CITcodigo){
		$sql = "
		SELECT 
			cita.CUPcodigo, 
			cita.CITcodigo, 
			cita.CITdia, 
			cita.CITfecha, 
			DATE_FORMAT(cita.CITfecha,'%d-%m-%Y') AS fecha_cita, 
			cita.CIThora_inicio, TIME_FORMAT(cita.CIThora_inicio,'%H:%i') AS hora_cita,
			cita.PACidentificador, 
			cita.PACrut, 
			cita.PACficha, 
			cita.PACnombre_completo, 
			cita.PACedad, 
			cita.CITpertinencia, 
			cita.CITctacte, 
			cita.PROGcodigo, 
			cita.INTcodigo,
			cita.PACprevision, 
			prev.prevision AS prevision_paciente, 
			cita.PACconvenio, 
			conv.instNombre AS convenio_paciente, 
			cita.CITpertinencia_tiempo, 
			cita.CITna_motivo,
			cita.TIATcodigo, 
			cita.TIATdescripcion, 
			cita.PREcodigo, 
			cita.PREdescripcion, 
			cita.LOCcodigo, 
			cita.LOCdescripcion, 
			cita.RECcodigo, 
			cita.RECdescripcion, 
			cita.TIATclasificacion,
			cita.CITsobrecupo, 
			cita.CITestado_cita, 
			est_1.ESTdescripcion AS estado_cita, 
			cita.CITestado_paciente, 
			est_2.ESTdescripcion AS estado_paciente, 
			cup.CUPobservacion,
			cita.CITsobrecupo, 
			cita.CITespontaneo, 
			cita.CITnota, 
			cita.ESTAcodigo, 
			cita.CITalta, 
			cita.CITges, 
			cita.CITcie10abierto, 
			cita.CITcie10, 
			cita.REGcodigo, 
			cita.FICdespachada
		FROM agenda.cita
		LEFT JOIN agenda.cupo AS cup ON cup.CUPcodigo = cita.CUPcodigo
		LEFT JOIN paciente.prevision AS prev ON prev.id = cita.PACprevision
		LEFT JOIN paciente.institucion AS conv ON conv.instCod = cita.PACconvenio
		LEFT JOIN agenda.estado AS est_1 ON est_1.ESTcodigo = CITestado_cita
		LEFT JOIN agenda.estado AS est_2 ON est_2.ESTcodigo = CITestado_paciente
		WHERE CITcodigo = '$CITcodigo'";
		
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
		if(count($datos)>0){
			return $datos[0];
		}
	}
	function obtenerEstadoCita($objCon,$CITcodigo){
		$sql = "SELECT cita.CITestado_cita
				FROM agenda.cita
				WHERE cita.CITcodigo = '$CITcodigo'";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
		return $datos[0]['CITestado_cita'];
	}
	function consultaPacienteEnListaEspera($objCon,$PACidentificador,$LOCcodigo,$RECcodigo){
		$sql = "SELECT *, 
					DATE_FORMAT(LERfecha_ingreso,'%d-%m-%Y') AS fecha_ingreso
				FROM agenda.lista_espera_reagendamiento
				WHERE PACidentificador = '$PACidentificador'
				AND LOCcodigo = '$LOCcodigo'
				AND RECcodigo = '$RECcodigo'";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
		return $datos;
	}
	function agregarAListaEsperaReagendamiento($objCon,$PACidentificador,$PACrut,$PACficha,$PACnombre_completo,$CITcodigo,$LOCcodigo,$LOCdescripcion,$RECcodigo,$RECdescripcion,$PROGcodigo,$PROGdescripcion,$TIATcodigo,$CITfecha,$CIThora,$PREcodigo){
		$usuario 		= $_SESSION['MM_Username'.SessionName];
		$QRlistaEspera 	= $this->consultaPacienteEnListaEspera($objCon,$PACidentificador,$LOCcodigo,$RECcodigo);
		if(coun($QRlistaEspera) == 0){
			$sql = "INSERT INTO agenda.lista_espera_reagendamiento (LERfecha_ingreso, PACidentificador, PACrut, PACficha, PACnombre_completo, CITcodigo, LOCcodigo, RECcodigo, PROGcodigo, TIATcodigo, CITfecha, CIThora, PREcodigo,LERusuario_log)
					VALUES('$fechaHora','$PACidentificador','$PACrut','$PACficha','$PACnombre_completo','$CITcodigo','$LOCcodigo','$RECcodigo','$PROGcodigo','$TIATcodigo','$CITfecha','$CIThora','$PREcodigo','$usuario')";
			
			$responde 	= $objCon->ejecutarSQL($sql, "Error al agregarAListaEsperaReagendamiento");
			$LERcodigo	= $objCon->lastInsertId();
			$this->insertarAccionLog($objCon,'lista_espera_reagendamiento',$LERcodigo,$_SESSION['MM_Username'.SessionName],'AGREGA A LE REAGENDAMIENTO','','',$PACidentificador);
			return 2;
		}else{
			return 0;
		}
	}
	function eliminarCita($objCon,$CITcodigo){
		$sql = "DELETE FROM agenda.cita WHERE CITcodigo = '$CITcodigo'";
		$objCon->ejecutarSQL($sql, "Error al actualizar médico involucrado en ginecología");
	}
	function liberarSobrecupo($objCon,$CUPcodigo,$fecha){
		$sql = "UPDATE agenda.sobrecupo SET SOButilizado = (SOButilizado - 1) WHERE CUPcodigo = '$CUPcodigo' AND SOBfecha = '$fecha'";
		$objCon->ejecutarSQL($sql, "Error al actualizar médico involucrado en ginecología");
	}
	function liberarCita($objCon,$CITcodigo,$CITestado_anterior){
		if($CITestado_anterior == 11)
			$estado_cita = 10;
		else if($CITestado_anterior == 15)
			$estado_cita = 15;
		else
			$estado_cita = 10;
		$sql = "UPDATE agenda.cita SET PACidentificador = NULL, PACrut = NULL, PACficha = NULL, PACnombre_completo = NULL,
								PACedad = NULL, PACprevision = NULL, PACconvenio = NULL, PREcodigo = NULL, CITalta = NULL, CITges = NULL, FICdespachada = 'N', CITmotivo_cancela = NULL,
								PREdescripcion = NULL, CITestado_cita = '$estado_cita', CITestado_anterior = 10, CITestado_paciente = NULL, INTcodigo = NULL, CITnota = NULL
				WHERE CITcodigo = '$CITcodigo'";
		$objCon->ejecutarSQL($sql, "Error al actualizar médico involucrado en ginecología");
	}
	function cancelarCita($objCon,$CITcodigo, $motivo, $observacion){
		$usuario 			= $_SESSION['MM_Username'.SessionName];
		$CITestado_anterior = $this->obtenerEstadoCita($objCon, $CITcodigo);
		$sql 	= "UPDATE agenda.cita SET CITestado_cita = 23, CITestado_paciente = 23, CITusuario_cancela = '$usuario',
				CITmotivo_cancela = '$motivo', CITobservacion_cancela = '$observacion'
				WHERE CITcodigo = '$CITcodigo'";
		$objCon->ejecutarSQL($sql, "Error al actualizar médico involucrado en ginecología");

		$RScita = $this->obtenerCita($objCon, $CITcodigo);

		if($RScita['PACidentificador'])
			$this->copiarCitaHistorico($objCon, $CITcodigo);
		if($RScita['INTcodigo']){//CITA CON INTERCONSULTA
			$this->reversarInterconsulta($objCon,$RScita['INTcodigo'], $motivo, $observacion,'CANCELACION DE CITA');
		}else{//CONTROL
			$this->agregarAListaEsperaReagendamiento($objCon,$RScita['PACidentificador'],$RScita['PACrut'],$RScita['PACficha'],$RScita['PACnombre_completo'],$CITcodigo,$RScita['LOCcodigo'],$RScita['LOCdescripcion'],$RScita['RECcodigo'],$RScita['RECdescripcion'],$RScita['PROGcodigo'],$RScita['PROGdescripcion'],$RScita['TIATcodigo'],$RScita['CITfecha'],$RScita['CIThora_inicio'],$RScita['PREcodigo']);
		}
		if($RScita['CITespontaneo'] == 'S'){//CITA ESPONTANEA DE SOBRECUPO CANCELADA ELIMINA LA CITA
			$this->eliminarCita($objCon,$CITcodigo);
		}else if($RScita['CITsobrecupo'] == 'S'){//CITA DE SOBRECUPO CANCELADA ELIMINA LA CITA Y ACTUALIZA SOBRECUPOS USADOS
			$this->eliminarCita($objCon,$CITcodigo);
			$this->liberarSobrecupo($objCon,$RScita['CUPcodigo'],$RScita['CITfecha']);
		}else{//CITA NORMAL QUE SE CANCELA LIBERA LA CITA
			$this->liberarCita($objCon,$CITcodigo,$CITestado_anterior);
		}
		$this->insertarAccionLog($objCon,'cita',$CITcodigo,$_SESSION['MM_Username'.SessionName],'CANCELA CITA',$motivo,$observacion,$RScita['PACidentificador']);
	}
	function insertarMensajeAvis($objCon,$MENtipo,$INTcodigo,$AVIScodigo_solicitud,$CITcodigo,$CITfecha,$CIThora,$PROrut,$PROapellidos,$PROnombres,$CITestado,$MENcomentario,$INTegreso_codigo,$MENfecha_registro,$MENfecha_procesado){
		$sql = "INSERT INTO integraciones.mensajes_avis 
		(
			MENtipo,
			INTcodigo,
			AVIScodigo_solicitud,
			CITcodigo,
			CITfecha,
			CIThora,
			PROrut,
			PROapellidos,
			PROnombres,
			CITestado,
			MENcomentario,
			INTegreso_codigo,
			MENfecha_registro,
			MENfecha_procesado
		)
		VALUES(
			'$MENtipo',
			'$INTcodigo',
			'$AVIScodigo_solicitud',
			'$CITcodigo',
			'$CITfecha',
			'$CIThora',
			'$PROrut',
			'$PROapellidos',
			'$PROnombres',
			'$CITestado',
			'$MENcomentario',
			'$INTegreso_codigo',
			NOW(),
			'$MENfecha_procesado'
		)";
		$responde 	= $objCon->ejecutarSQL($sql, "Error al insertarMensajeAvis");
	}
	function obtenerInterconsulta($objCon,$INTcodigo){
		$sql = "
			SELECT *, 
				DATE_FORMAT(interconsulta.INTfecha_solicitud, '%d-%m-%Y') AS fecha_solicitud, 
				DATE_FORMAT(interconsulta.INThora_solicitud, '%H:%i') AS hora_solicitud,
				DATE_FORMAT(interconsulta.INTfecha_egreso, '%d-%m-%Y') AS fecha_egreso, 
				mot_egre.MOTdescripcion AS causal_egreso,
				DATE_FORMAT(interconsulta.CITfecha, '%d-%m-%Y') AS fecha_cita, 
				IF(rec.RECdescripcion IS NOT NULL, rec.RECdescripcion, pro.PROdescripcion) AS descripcion,
				esta.ESTAdescripcion AS origen, 
				esta_2.ESTAdescripcion AS destino, 
				esp.ESPdescripcion AS esp_destino, 
				esp_1.ESPdescripcion AS esp_origen,esp_2.ESPdescripcion AS esp_origenAPS,
				interconsulta.INTtipo_interconsulta, 
				interconsulta.INTprocedencia
			FROM agenda.interconsulta
			JOIN agenda.procedencia AS pro ON pro.PROCEcodigo = interconsulta.INTprocedencia
			LEFT JOIN agenda.recurso AS rec ON rec.RECcodigo = interconsulta.RECcodigo
			LEFT JOIN parametros_clinicos.profesional AS pro ON pro.PROcodigo = interconsulta.RECcodigo
			LEFT JOIN parametros_clinicos.especialidad AS esp_1 ON esp_1.ESPcodigo = interconsulta.INTespecialidad_origen
			LEFT JOIN parametros_clinicos.especialidad AS esp_2 ON esp_2.ESPcodigo = interconsulta.INTespecialidadAps
			LEFT JOIN parametros_clinicos.especialidad AS esp ON esp.ESPcodigo = interconsulta.INTespecialidad_destino
			JOIN agenda.interconsulta_estado AS est ON est.ESTcodigo = interconsulta.INTestado
			LEFT JOIN agenda.interconsulta_motivo_egreso AS mot_egre ON mot_egre.MOTcodigo = interconsulta.INTcausal_egreso
			LEFT JOIN parametros_clinicos.establecimiento AS esta ON esta.ESTAcodigo = interconsulta.INTestablecimiento_origen
			LEFT JOIN parametros_clinicos.establecimiento AS esta_2 ON esta_2.ESTAcodigo = interconsulta.INTestablecimiento_destino
			JOIN agenda.interconsulta_prioridad AS pri ON pri.PRIcodigo = interconsulta.INTprioridad
			JOIN agenda.interconsulta_motivo AS mot ON mot.MOTcodigo = interconsulta.INTmotivo_solicitud
				WHERE INTcodigo = '$INTcodigo'";  //echo $sql;
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
		return $datos[0];
	}
	function insertarAccionLog($objCon,$tabla,$identificador_registro,$usuario,$accion,$motivo,$descripcion,$PACidentificador){
		$sql 		= "INSERT INTO agenda.log_acciones (LOGtabla_afecta, LOGidentificador_registro, LOGfecha_hora, LOGusuario, LOGaccion, LOGmotivo, LOGdescripcion, LOGid_paciente)
								VALUES ('$tabla', '$identificador_registro', NOW(), '$usuario', '$accion','$motivo','$descripcion','$PACidentificador')";
		$responde 	= $objCon->ejecutarSQL($sql, "Error al insertarAccionLog");
	}
	function insertarMovimientoInterconsulta($objCon, $INTcodigo, $establecimiento, $CITcodigo, $RECcodigo, $CITfecha, $movimiento, $estado, $usuario){
		if($CITcodigo){
			$campos 	= 'CITcodigo,RECcodigo,CITfecha,';
			$valores 	= "'$CITcodigo','$RECcodigo','$CITfecha',";
		}
		$sql = "INSERT INTO agenda.interconsulta_movimiento 
		(INTcodigo,MOVdescripcion,ESTAcodigo,".$campos."INTestado,INTusuario_log,INTmovimiento_fecha)
		VALUES
		('$INTcodigo','$movimiento','$establecimiento',".$valores."'$estado','$usuario',NOW())";
		$responde 	= $objCon->ejecutarSQL($sql, "Error al insertarMovimientoInterconsulta");
	}
	function egresarInterconsulta($objCon, $INTcodigo, $tipo_egreso, $motivo, $descripcion){
		if($tipo_egreso == 1)
		$motivo = 100;
		if($motivo == 100)
		$tipo_egreso = 1;

		$sql = "UPDATE agenda.interconsulta SET INTestado = 3, INTtipo_egreso = '$tipo_egreso', INTfecha_egreso = CURDATE(), INTcausal_egreso = '$motivo', INTglosa_egreso = '$descripcion'
		WHERE INTcodigo = '$INTcodigo'";
		$objCon->ejecutarSQL($sql, "Error al actualizar médico involucrado en ginecología");

		$sql = "UPDATE interconsulta SET INTfecha_egreso = CURDATE()
		WHERE INTcodigo = '$INTcodigo'
		AND CITfecha IS NOT NULL";
		$objCon->ejecutarSQL($sql, "Error al actualizar médico involucrado en ginecología");

		$this->insertarMovimientoInterconsulta($objCon, $INTcodigo, 101100, '', '', '', 'EGRESO DE SIC', 3, $_SESSION['MM_Username'.SessionName]);
		$this->insertarAccionLog($objCon,'interconsulta',$INTcodigo,$_SESSION['MM_Username'.SessionName],'EGRESAR SIC','','FOLIO:'.$INTcodigo,$PACidentificador);// egresar sic
		$RSsic = $this->obtenerInterconsulta($objCon,$INTcodigo);
		if(coun($RSsic)>0){//INTERCONSULTA DE AVIS
			$this->insertarMensajeAvis($objCon,'EL',$INTcodigo,$RSsic['INTid_avis'],'','','','','','','','',$motivo,$objUtil->getTimeStamp(),'');
		}
	}
	function contadorCitas($objCon,$parametros){
		  $sql="SELECT cita.CITcodigo 
			  FROM   agenda.cita
			  WHERE  cita.PACidentificador = '{$parametros['frm_id_paciente']}' AND cita.CITestado_cita IN (15,11)"; 
    	$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
		return $datos;
	}
	function contadorInterconsultas($objCon,$parametros){
		$sql ="SELECT interconsulta.INTcodigo
			   FROM   agenda.interconsulta
			   WHERE  interconsulta.PACidentificador = '{$parametros['frm_id_paciente']}' AND interconsulta.INTestado IN (1, 2)"; 
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
		return $datos;
	}
	function getPrioridad($objCon){
		$sql="SELECT
			interconsulta_prioridad.PRIcodigo,
			interconsulta_prioridad.PRIdescripcion
			FROM
			agenda.interconsulta_prioridad";
		$datos = $objCon->consultaSQL($sql,"Error al listar getPrioridad");
	 	return $datos;
	}



	function getEspecialidad($objCon){
		$sql="SELECT
			especialidad.ESPcodigo,
			especialidad.ESPdescripcion
			FROM
			parametros_clinicos.especialidad
			WHERE
			ESPactivoHJNC = 'S'
			ORDER BY especialidad.ESPdescripcion";
		$datos = $objCon->consultaSQL($sql,"Error al listar getEspecialidad");
	 	return $datos;	
	}

	function getEspecialidadLE($objCon){
		$sql="SELECT
			especialidad.ESPcodigo,
			especialidad.ESPdescripcion
			FROM
			parametros_clinicos.especialidad
			WHERE
			ESPactivoHJNC = 'S' AND ESPactivoSolicitud = 'S'
			ORDER BY especialidad.ESPdescripcion";
		$datos = $objCon->consultaSQL($sql,"Error al listar getEspecialidad");
	 	return $datos;	
	}


	function insertarNuevaInterconsulta($objCon,$parametros){
		if($parametros['nombreCie10']==""){
			$condicion.= 'NULL';
		}else{
			$condicion.= $parametros['nombreCie10'];
		}
		if($parametros['hipo_final']==""){
			$condicion2.= 'NULL';
		}else{
			$condicion2.=  $parametros['hipo_final'];
		}
		$sql="INSERT INTO agenda.interconsulta(
				INTfecha_solicitud,
				INThora_solicitud,
				INTestablecimiento_origen,
				INTestablecimiento_origen_descripcion,
				INTestablecimiento_destino,
				INTestablecimiento_destino_descripcion,
				INTestado,
				PACidentificador,
				PACrut,
				PACfecha_nac,
				PACnombre_completo,
				INTprocedencia,
				INTespecialidad_origen,
				INTespecialidad_destino,
				INTprioridad,
				INTmotivo_solicitud,
				INThipotesis_diagnostica,
				INTdiagnostico_actual,
				INTauge,
				INTfundamento_diagnostico,
				INTprofesional,
				INTrut_profesional,
				INTrau,
				INTtipo_interconsulta)
					VALUES(
					'{$parametros['fecha_inicio']}',
					'{$parametros['hora_inicio']}',
					'{$parametros['cod_establecimiento']}',
					'{$parametros['establecimiento']}',
					'{$parametros['cod_establecimiento']}',
					'{$parametros['establecimiento']}',
					'{$parametros['estado_inicial']}',
					'{$parametros['frm_id_paciente']}',
					'{$parametros['rut_paciente']}',
					'{$parametros['fecha_nacimiento']}',
					'{$parametros['nombre_completo']}',
					'{$parametros['procedencia']}',
					'{$parametros['especialidad']}',
					'{$parametros['especialidad']}',
					'{$parametros['prioridad']}',
					'{$parametros['otro_motivo']}',".$condicion.",".$condicion.",
					'{$parametros['ague_interconsulta']}',".$condicion2.",
					'{$parametros['profesional']}',
					'{$parametros['rut_profesional']}',
					'{$parametros['dau_id']}',
					'{$parametros['tipo_interconsulta']}')";
		$responde 	= $objCon->ejecutarSQL($sql, "Error al insertarNuevaInterconsulta");
		$cita_id	= $objCon->lastInsertId();
		return $cita_id;
	}



	function getDescripcionEspecialidad($objCon, $codigoEspecialidad){
		$sql="SELECT
			parametros_clinicos.especialidad.ESPdescripcion
			FROM
			parametros_clinicos.especialidad
			WHERE 
			parametros_clinicos.especialidad.ESPcodigo = '{$codigoEspecialidad}' ";
		$datos = $objCon->consultaSQL($sql,"Error al listar getDescripcionEspecialidad");
	 	return $datos;	
	}

}
?>