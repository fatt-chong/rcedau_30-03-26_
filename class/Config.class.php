<?php
class Config{

	function getTriage($objCon){
		$sql = "SELECT * 
				FROM	dau.config 
				WHERE dau.config.config_tipo = 'triage'";
		$datos = $objCon->consultaSQL($sql,"<br>Error al obtener datos de los Triage<br>");
		return $datos;
	}
	
	function getTipoTriageActivo($objCon){
		$sql = "SELECT *
				FROM	dau.config
				WHERE dau.config.config_tipo = 'triage'
				AND dau.config.config_estado = 1";
		$datos = $objCon->consultaSQL($sql,"<br>Error al obtener tipo de Triage Activo<br>");
		return $datos;
	}

	function updateTriageActivo($objCon, $parametros){
		$this->setTriages($objCon);
		$sql = "UPDATE dau.config 
				SET dau.config.config_estado = 1 
				WHERE dau.config.config_tipo = 'triage' 
				AND dau.config.config_id = {$parametros['triage_id']}";
		$datos = $objCon->ejecutarSQL($sql,"<br>Error al actualizar el Triage Activo<br>");
		return $datos;
	}

	function setTriages($objCon){
		$sql = "UPDATE dau.config 
				SET dau.config.config_estado = 0 
				WHERE dau.config.config_tipo = 'triage'";
		$datos = $objCon->ejecutarSQL($sql,"<br>Error al setear todos los Triage Activo<br>");
		return $datos;		
	}

	function getValidacion($objCon){
		$sql = "SELECT * 
				FROM	dau.config 
				WHERE dau.config.config_tipo = 'validacion'";
		$datos = $objCon->consultaSQL($sql,"<br>Error al obtener datos de los Triage<br>");
		return $datos;
	}

	function addAllPermisosDAU(){
		session_start();
		//Todos los permisos DAU
		$permisos_dau = array(
							1 => '810', 
							2 => '811', 
							3 => '812', 
							4 => '813', 
							5 => '814', 
							6 => '815', 
							7 => '816', 
							8 => '817', 
							9 => '818', 
							10 => '819', 
							11 => '820', 
							12 => '821', 
							13 => '822', 
							14 => '823', 
							15 => '824', 
							16 => '825', 
							17 => '826', 
							18 => '827', 
							19 => '830', 
							20 => '831', 
							21 => '832', 
							22 => '833', 
							23 => '834', 
							24 => '835', 
							25 => '836', 
							26 => '837', 
							27 => '839', 
							28 => '840', 
							29 => '841', 
							30 => '842', 
							31 => '843', 
							32 => '844', 
							33 => '845',
							34 => '867'
						);

		$_SESSION['permisosDAU'.SessionName] = $permisos_dau;
		
 // print('<pre>'); print_r($_SESSION['permiso'.SessionName]); print('</pre>');
	}

	function cargarPermisoDau($objCon, $idsuario){
		// $objCon->db_select("acceso");
		$sql="SELECT
			acceso.usuario_has_rol.usuario_idusuario,
			acceso.rol.idrol,
			acceso.rol.descripcion
			FROM
			acceso.rol
			INNER JOIN acceso.usuario_has_rol ON acceso.usuario_has_rol.rol_idrol = rol.idrol
			WHERE usuario_has_rol.usuario_idusuario = '$idsuario'";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
		$roles=array();
		for($i=0;$i<count($datos); $i++){
			$roles[$i] = $datos[$i]['idrol'];
		}
		return $roles;
	}

	function endKey( $array ){
	    end( $array );
	    return key( $array );
	}

}