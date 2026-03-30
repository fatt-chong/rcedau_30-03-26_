<?php
	class RegistroMedico{

		function listaCie10Urgencia($objCon, $parametros){
			$sql="SELECT
				codigoCIE AS codigoCIE,
				nombreCIE AS nombreCIE
			FROM
				cie10.cie10
			WHERE
				 codigoCIE LIKE '%{$parametros}%' OR nombreCIE LIKE '%{$parametros}%' ";
			$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LEER ITEM<br>");
			$return_arr = array();
			for($i=0; $i<count($datos); $i++) {
					$row_array['id']         = $datos[$i]['codigoCIE'];
					$row_array['value']      = "{$datos[$i]['codigoCIE']} - (Descripcion: {$datos[$i]['nombreCIE']})";
					$row_array['nombre']     = $datos[$i]['nombreCIE'];
					array_push($return_arr,$row_array);
			}
			return json_encode($return_arr);
		}
		function SelectParametrosClinicosSensitiva ( $objCon, $parametros ) {
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
		WHERE
				 (PROnombres LIKE '%{$parametros}%' OR PROapellidopat LIKE '%{$parametros}%' OR PROapellidopat LIKE '%{$parametros}%' )
				 AND TIPROcodigo = 2 ";
			$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LEER ITEM<br>");
			$return_arr = array();
			for($i=0; $i<count($datos); $i++) {
					$row_array['id']         = $datos[$i]['PROcodigo'];
					$row_array['value']      = "{$datos[$i]['PROcodigo']} -  {$datos[$i]['PROnombres']} {$datos[$i]['PROapellidopat']} {$datos[$i]['PROapellidomat']}";
					$row_array['nombre']     = $datos[$i]['PROnombres']." ".$datos[$i]['PROapellidopat']." ".$datos[$i]['PROapellidomat'];
					array_push($return_arr,$row_array);
			}
			return json_encode($return_arr);
	    }
	    
	    function SelectUsuarioSensitiva ( $objCon, $tipo, $parametros, $objUtil ) {
		$condicion = "";
        $sql = "
			SELECT
			UPPER(usuario.nombreusuario) AS nombreusuario, 
			usuario.rutusuario
		FROM
			acceso.usuario
		WHERE
				 (nombreusuario LIKE '%{$parametros}%' )
				  and  usuario.auxiliar = 'S' ";
			$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LEER ITEM<br>");
			$return_arr = array();
			for($i=0; $i<count($datos); $i++) {
					$row_array['id']         = $datos[$i]['rutusuario'];
					$row_array['rut']         = $objUtil->rutDigito($datos[$i]['rutusuario']);
					$row_array['value']      = "{$datos[$i]['nombreusuario']}";
					$row_array['nombre']     = $datos[$i]['nombreusuario'];
					array_push($return_arr,$row_array);
			}
			return json_encode($return_arr);
	    }
	    function SelectParametrosClinicosSensitivaTodos ( $objCon, $tipo, $parametros, $objUtil ) {
		$condicion = "";
        $sql = "
			SELECT
			parametros_clinicos.profesional.PROcodigo, 
			parametros_clinicos.profesional.PROdescripcion, 
			UPPER(parametros_clinicos.profesional.PROapellidopat) AS PROapellidopat, 
			UPPER(parametros_clinicos.profesional.PROapellidomat) AS PROapellidomat, 
			UPPER(parametros_clinicos.profesional.PROnombres) AS PROnombres, 
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
		WHERE
				 (PROnombres LIKE '%{$parametros}%' OR PROapellidopat LIKE '%{$parametros}%' OR PROapellidopat LIKE '%{$parametros}%' )
				  and  TIPROcodigo IN ({$tipo}) ";
			$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LEER ITEM<br>");
			$return_arr = array();
			for($i=0; $i<count($datos); $i++) {
					$row_array['id']         = $datos[$i]['PROcodigo'];
					$row_array['rut']         = $objUtil->rutDigito($datos[$i]['PROcodigo']);
					$row_array['value']      = "{$datos[$i]['PROnombres']} {$datos[$i]['PROapellidopat']} {$datos[$i]['PROapellidomat']}";
					$row_array['nombre']     = $datos[$i]['PROnombres']." ".$datos[$i]['PROapellidopat'];
					array_push($return_arr,$row_array);
			}
			return json_encode($return_arr);
	    }
	    function SelectParametrosClinicosSensitivaMedicos ( $objCon, $parametros, $objUtil ) {
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
		WHERE
				 (PROnombres LIKE '%{$parametros}%' OR PROapellidopat LIKE '%{$parametros}%' OR PROapellidopat LIKE '%{$parametros}%' ) and TIPROcodigo IN (1,15)
				  ";
			$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LEER ITEM<br>");
			$return_arr = array();
			for($i=0; $i<count($datos); $i++) {
					$row_array['id']         = $datos[$i]['PROcodigo'];
					$row_array['rut']         = $objUtil->rutDigito($datos[$i]['PROcodigo']);
					$row_array['value']      = "{$datos[$i]['PROnombres']} {$datos[$i]['PROapellidopat']}";
					$row_array['nombre']     = $datos[$i]['PROnombres']." ".$datos[$i]['PROapellidopat'];
					array_push($return_arr,$row_array);
			}
			return json_encode($return_arr);
	    }



		
		function registarCierreMedico($objCon, $parametros){

			$sql=" UPDATE dau.dau ";

			$cie10;
			if ($parametros['item_producto_final'] == 'undefined') {
				$cie10 = '';
			}else{
				$cie10 = $parametros['item_producto_final'][0][0];
			}

			if ($parametros['Iddau']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_cie10 = '{$cie10}'";

			}

			$sql .= $condicion." WHERE dau_id = {$parametros['Iddau']}";
			$response = $objCon->ejecutarSQL($sql, "ERROR AL REGISTRAR CIERRE MEDICO");
			return $response;
		}		

	}
?>