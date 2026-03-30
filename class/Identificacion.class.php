<?php
	class Identificacion{

		function identicarUsuario($objCon,$parametros){
			// $objCon->db_select("acceso");
			$sql="SELECT
					usuario.idusuario,
					usuario.nombreusuario,
					usuario.rutusuario,
					usuario_has_rol.rol_idrol
				  FROM
					acceso.usuario
				  INNER JOIN acceso.usuario_has_rol ON usuario_has_rol.usuario_idusuario = usuario.idusuario
				  WHERE usuario.usu_barcode_key = '{$parametros['codigoBarra']}' AND usuario_has_rol.rol_idrol = {$parametros['permiso']}";
			$datos = $objCon->consultaSQL($sql,"<br>Error al listar Usurio Identificado<br>");
			return $datos;
		}



		function verificarExistenciaUsuario($objCon,$parametros){
			// $objCon->db_select("acceso");
			$sql="SELECT
					usuario.idusuario,
					usuario.rutusuario,
					usuario.nombreusuario
					FROM
					acceso.usuario
					WHERE usuario.usu_barcode_key = '{$parametros['codigoBarra']}'";
			$datos = $objCon->consultaSQL($sql,"<br>Error al listar Usurio Identificado<br>");
			return $datos;
		}



		function verificarExistenciaUsuarioSinPistola($objCon,$parametros){
			// $objCon->db_select("acceso");
			$sql="SELECT
					usuario.idusuario,
					usuario.rutusuario,
					usuario.nombreusuario
					FROM
					acceso.usuario
					WHERE usuario.rutusuario='{$parametros['run']}'
						AND AES_DECRYPT(claveacceso,'idusuario')='{$parametros['password']}'";
			$datos = $objCon->consultaSQL($sql,"<br>Error al listar Usurio Identificado<br>");
			return $datos;
		}



		function validaPermisoUsuario($objCon,$parametros){
			// $objCon->db_select("acceso");
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



		function identicarUsuarioSinPistola($objCon,$parametros){
			// $objCon->db_select("acceso");
			$sql="	SELECT
						usuario.idusuario,
						usuario.rutusuario,
						usuario.claveacceso,
						usuario.nombreusuario,
						usuario.usu_activo,
						usuario_has_servicio.idservicio,
						usuario_has_rol.rol_idrol,
						servicio.nombre
					FROM
						acceso.usuario
					INNER JOIN acceso.usuario_has_servicio ON usuario.idusuario = usuario_has_servicio.idusuario
					INNER JOIN acceso.servicio ON usuario_has_servicio.idservicio = servicio.idservicio
					INNER JOIN acceso.usuario_has_rol ON usuario_has_rol.usuario_idusuario = usuario.idusuario
					WHERE usuario.rutusuario='{$parametros['run']}'
						AND usuario_has_servicio.estado = 'A'
						AND AES_DECRYPT(claveacceso,'idusuario')='{$parametros['password']}'
						AND usuario_has_rol.rol_idrol = {$parametros['permiso']}";
			$datos = $objCon->consultaSQL($sql,"<br>Error al listar Usurio Identificado<br>");
			return $datos;
		}



		function obtenerPerfilUsuario($objCon, $parametros){
			// $objCon->setDB('acceso');
			//Perfil Tens			: 57
			//Perfil Enfermeras		: 53, 55, 56, 61
			//Perfil Médico 		: 59, 74
			//Perfil Administrativo : 75
			//Perfil Full           : 60

			$sql	= "	SELECT
							SUM(CASE WHEN perfil.id_perfil = 57 THEN 1 ELSE 0 END) AS contadorPerfilTens,
							SUM(CASE WHEN perfil.id_perfil = 53 OR perfil.id_perfil = 55 OR perfil.id_perfil = 56 OR perfil.id_perfil = 61 THEN 1 ELSE 0 END) AS contadorPerfilEnfermero,
							SUM(CASE WHEN perfil.id_perfil = 59 OR perfil.id_perfil = 74 THEN 1 ELSE 0 END) AS contadorPerfilMedico,
							SUM(CASE WHEN perfil.id_perfil = 75 THEN 1 ELSE 0 END) AS contadorPerfilAdministrativo,
							SUM(CASE WHEN perfil.id_perfil = 60 THEN 1 ELSE 0 END) AS contadorPerfilFull,
							usuario.rutusuario,
							usuario.idusuario,
							usuario.nombreusuario
						FROM
							perfil
							INNER JOIN usuario_has_perfil ON perfil.id_perfil = usuario_has_perfil.id_perfil
							INNER JOIN usuario ON usuario_has_perfil.idusuario = usuario.idusuario";

			if ( $parametros['verificacionConPistola'] === 'falso' ) {

				$sql.= " WHERE
							usuario.rutusuario = '{$parametros['run']}'
						AND
							AES_DECRYPT(claveacceso,'idusuario')='{$parametros['password']}'";

			} else if ( $parametros['verificacionConPistola'] === 'verdadero' ) {

				 $sql.= " WHERE
							usuario.usu_barcode_key = '{$parametros['codigoBarra']}'  ";

			}

			$datos =  $objCon->consultaSQL($sql,"<br>Error al listar Usurio Identificado<br>");

			return $datos;

		}

	}
?>
