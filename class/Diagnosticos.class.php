<?php class Diagnosticos{
	function DeletetRce_diagnosticoCompartido($objCon,$parametros){
		$condicion 	= "";
		$sql="DELETE FROM camas.diagnostico_compartido";
		if(isset($parametros['id_compartido'])){
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion.=" id_compartido = '{$parametros['id_compartido']}' ";
		}
		$sql .= $condicion;
		$response = $objCon->ejecutarSQL($sql, "ERROR AL DeletetRce_diagnostico");
	}
	function InsertLog_diagnosticos_camas($objCon,$parametros){
		$condicion 	= "";
		$filtro 	= "";
		$sql="INSERT INTO bdlog.log_diagnosticos_camas( ";
		if(isset($parametros['rce_diagnostico_cie10'])){
			$condicion .= ($condicion == "") ? "  " : " , ";
			$condicion.=" rce_diagnostico_cie10  ";
		}if(isset($parametros['rce_diagnostico_fecha_agregado'])){
			$condicion .= ($condicion == "") ? "  " : " , ";
			$condicion.=" rce_diagnostico_fecha_agregado ";
		}if(isset($parametros['rce_diagnostico_hora_agregado'])){
			$condicion .= ($condicion == "") ? "  " : " , ";
			$condicion.=" rce_diagnostico_hora_agregado  ";
		}if(isset($parametros['rce_diagnostico_usuario_agregado'])){
			$condicion .= ($condicion == "") ? "  " : " , ";
			$condicion.=" rce_diagnostico_usuario_agregado  ";
		}if(isset($parametros['rce_evolucion_id'])){
			$condicion .= ($condicion == "") ? "  " : " , ";
			$condicion.=" rce_evolucion_id  ";
		}if(isset($parametros['rce_id'])){
			$condicion .= ($condicion == "") ? "  " : " , ";
			$condicion.=" rce_id  ";
		}if(isset($parametros['rce_diagnostico_cie10_descrip'])){
			$condicion .= ($condicion == "") ? "  " : " , ";
			$condicion.=" rce_diagnostico_cie10_descrip  ";
		}if(isset($parametros['rce_diagnistico_descripcion_text'])){
			$condicion .= ($condicion == "") ? "  " : " , ";
			$condicion.=" rce_diagnistico_descripcion_text  ";
		}if(isset($parametros['rce_diagnostico_fecha_eliminado'])){
			$condicion .= ($condicion == "") ? "  " : " , ";
			$condicion.=" rce_diagnostico_fecha_eliminado  ";
		}if(isset($parametros['rce_diagnostico_hora_eliminado'])){
			$condicion .= ($condicion == "") ? "  " : " , ";
			$condicion.=" rce_diagnostico_hora_eliminado  ";
		}if(isset($parametros['rce_diagnostico_usuario_eliminado'])){
			$condicion .= ($condicion == "") ? "  " : " , ";
			$condicion.=" rce_diagnostico_usuario_eliminado  ";
		}if(isset($parametros['cta_cte'])){
			$condicion .= ($condicion == "") ? "  " : " , ";
			$condicion.=" cta_cte  ";
		}if(isset($parametros['origen'])){
			$condicion .= ($condicion == "") ? "  " : " , ";
			$condicion.=" origen  ";
		}

		if(isset($parametros['rce_diagnostico_cie10'])){
			$filtro .= ($filtro == "") ? " ) VALUES ( " : " , ";
			$filtro.=" '{$parametros['rce_diagnostico_cie10']}'  ";
		}if(isset($parametros['rce_diagnostico_fecha_agregado'])){
			$filtro .= ($filtro == "") ? " ) VALUES ( " : " , ";
			$filtro.=" '{$parametros['rce_diagnostico_fecha_agregado']}' ";
		}if(isset($parametros['rce_diagnostico_hora_agregado'])){
			$filtro .= ($filtro == "") ? " ) VALUES ( " : " , ";
			$filtro.=" '{$parametros['rce_diagnostico_hora_agregado']}'  ";
		}if(isset($parametros['rce_diagnostico_usuario_agregado'])){
			$filtro .= ($filtro == "") ? " ) VALUES ( " : " , ";
			$filtro.=" '{$parametros['rce_diagnostico_usuario_agregado']}'  ";
		}if(isset($parametros['rce_evolucion_id'])){
			$filtro .= ($filtro == "") ? " ) VALUES ( " : " , ";
			$filtro.=" '{$parametros['rce_evolucion_id']}'  ";
		}if(isset($parametros['rce_id'])){
			$filtro .= ($filtro == "") ? " ) VALUES ( " : " , ";
			$filtro.=" '{$parametros['rce_id']}'  ";
		}if(isset($parametros['rce_diagnostico_cie10_descrip'])){
			$filtro .= ($filtro == "") ? " ) VALUES ( " : " , ";
			$filtro.=" '{$parametros['rce_diagnostico_cie10_descrip']}'  ";
		}if(isset($parametros['rce_diagnistico_descripcion_text'])){
			$filtro .= ($filtro == "") ? " ) VALUES ( " : " , ";
			$filtro.=" '{$parametros['rce_diagnistico_descripcion_text']}'  ";
		}if(isset($parametros['rce_diagnostico_fecha_eliminado'])){
			$filtro .= ($filtro == "") ? " ) VALUES ( " : " , ";
			$filtro.=" '{$parametros['rce_diagnostico_fecha_eliminado']}'  ";
		}if(isset($parametros['rce_diagnostico_hora_eliminado'])){
			$filtro .= ($filtro == "") ? " ) VALUES ( " : " , ";
			$filtro.=" '{$parametros['rce_diagnostico_hora_eliminado']}'  ";
		}if(isset($parametros['rce_diagnostico_usuario_eliminado'])){
			$filtro .= ($filtro == "") ? " ) VALUES ( " : " , ";
			$filtro.=" '{$parametros['rce_diagnostico_usuario_eliminado']}'  ";
		}if(isset($parametros['cta_cte'])){
			$filtro .= ($filtro == "") ? " ) VALUES ( " : " , ";
			$filtro.=" '{$parametros['cta_cte']}'  ";
		}if(isset($parametros['origen'])){
			$filtro .= ($filtro == "") ? " ) VALUES ( " : " , ";
			$filtro.=" '{$parametros['origen']}'  ";
		}
		$sql .= $condicion.$filtro.")";
		$response = $objCon->ejecutarSQL($sql, "ERROR AL InsertRce_diagnostico");
		return $objCon->lastInsertId();
	}

	function sensitivaDiagnostico($objCon,$termino){
        $sql = "SELECT
                    cie10.codigoCIE,
                    cie10.nombreCIE,
                    cie10.nomcompletoCIE
                FROM
                    cie10.cie10
                WHERE
                    cie10.nomcompletoCIE LIKE '%{$termino}%' limit 100";
        $datos = $objCon->consultaSQL($sql,"<br>ERROR AL LEER sensitivaDiagnostico<br>");
        $return_arr = array();
        for ( $i = 0; $i < count($datos); $i++ ) {
            $row_array['id'] = $datos[$i]['codigoCIE'];
            $row_array['value'] = $datos[$i]['nomcompletoCIE'];
            $row_array['nomcompletoCIE'] = $datos[$i]['nomcompletoCIE'];
            array_push($return_arr,$row_array);
        }
        return json_encode($return_arr);
    }
    function InsertRce_diagnosticoCompartido($objCon,$parametros){
		$condicion 	= "";
		$filtro 	= "";
		$sql="INSERT INTO camas.diagnostico_compartido ( ";
		if(isset($parametros['dau_id'])){
			$condicion .= ($condicion == "") ? "  " : " , ";
			$condicion.=" dau_id  ";
		}if(isset($parametros['id_cie10'])){
			$condicion .= ($condicion == "") ? "  " : " , ";
			$condicion.=" id_cie10  ";
		}if(isset($parametros['fecha'])){
			$condicion .= ($condicion == "") ? "  " : " , ";
			$condicion.=" fecha ";
		}if(isset($parametros['hora'])){
			$condicion .= ($condicion == "") ? "  " : " , ";
			$condicion.=" hora  ";
		}if(isset($parametros['usuario'])){
			$condicion .= ($condicion == "") ? "  " : " , ";
			$condicion.=" usuario  ";
		}if(isset($parametros['cta_cte'])){
			$condicion .= ($condicion == "") ? "  " : " , ";
			$condicion.=" cta_cte  ";
		}if(isset($parametros['origen'])){
			$condicion .= ($condicion == "") ? "  " : " , ";
			$condicion.=" origen  ";
		}if(isset($parametros['diagnistico_descripcion_text'])){
			$condicion .= ($condicion == "") ? "  " : " , ";
			$condicion.=" diagnistico_descripcion_text  ";
		}

		if(isset($parametros['dau_id'])){
			$filtro .= ($filtro == "") ? " ) VALUES ( " : " , ";
			$filtro.=" '{$parametros['dau_id']}'  ";
		}if(isset($parametros['id_cie10'])){
			$filtro .= ($filtro == "") ? " ) VALUES ( " : " , ";
			$filtro.=" '{$parametros['id_cie10']}'  ";
		}if(isset($parametros['fecha'])){
			$filtro .= ($filtro == "") ? " ) VALUES ( " : " , ";
			$filtro.=" '{$parametros['fecha']}' ";
		}if(isset($parametros['hora'])){
			$filtro .= ($filtro == "") ? " ) VALUES ( " : " , ";
			$filtro.=" '{$parametros['hora']}'  ";
		}if(isset($parametros['usuario'])){
			$filtro .= ($filtro == "") ? " ) VALUES ( " : " , ";
			$filtro.=" '{$parametros['usuario']}'  ";
		}if(isset($parametros['cta_cte'])){
			$filtro .= ($filtro == "") ? " ) VALUES ( " : " , ";
			$filtro.=" '{$parametros['cta_cte']}'  ";
		}if(isset($parametros['origen'])){
			$filtro .= ($filtro == "") ? " ) VALUES ( " : " , ";
			$filtro.=" '{$parametros['origen']}'  ";
		}if(isset($parametros['diagnistico_descripcion_text'])){
			$filtro .= ($filtro == "") ? " ) VALUES ( " : " , ";
			$filtro.=" '{$parametros['diagnistico_descripcion_text']}'  ";
		}
		$sql .= $condicion.$filtro.")";
		$response = $objCon->ejecutarSQL($sql, "ERROR AL InsertRce_diagnostico");
		return $objCon->lastInsertId();
	}
	function obtenerRce_diagnosticoCompartido($objCon,$parametros){
		// $objCon->setDB(BD_CONNECTION);
		$condicion 	= "";
		$sql="SELECT *
			FROM camas.diagnostico_compartido
			INNER JOIN cie10.cie10 ON camas.diagnostico_compartido.id_cie10 = cie10.cie10.codigoCIE ";
		if(isset($parametros['cta_cte'])){
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion.=" diagnostico_compartido.cta_cte = '{$parametros['cta_cte']}' ";
		}
		if(isset($parametros['id_compartido'])){
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion.=" diagnostico_compartido.id_compartido = '{$parametros['id_compartido']}' ";
		}
		if(isset($parametros['ges'])){
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion.=" cie10.ges = '{$parametros['ges']}' ";
		}
			// WHERE rce_diagnostico.rce_id = {$parametros['rce_id']}
		$sql .= $condicion."	ORDER BY id_compartido desc";
		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR obtenerDatosPacientesAcostados<br>");
        return $datos;
	}

	 function UpdateRce_diagnosticoCompartido($objCon,$parametros){
        $condicion = '';
        $sql="UPDATE camas.diagnostico_compartido";
        if(isset($parametros['diagnistico_descripcion_text_comentario'])){
            $condicion .= ($condicion == "") ? " SET " : " , ";
            $condicion.=" diagnistico_descripcion_text_comentario = '{$parametros['diagnistico_descripcion_text_comentario']}'";
        }
        $sql .= $condicion." WHERE id_compartido = '{$parametros['id_compartido']}' ";
        $resultado = $objCon->ejecutarSQL($sql, "Error al editarSolicitudImagenologia");
    }
	// function obtenerRce_diagnosticoCompartido($objCon,$parametros){
	// 	// $objCon->setDB(BD_CONNECTION);
	// 	$sql="SELECT *
	// 		FROM camas.diagnostico_compartido";
	// 	if(isset($parametros['cta_cte'])){
	// 		$condicion .= ($condicion == "") ? " WHERE " : " AND ";
	// 		$condicion.=" diagnostico_compartido.cta_cte = '{$parametros['cta_cte']}' ";
	// 	}
	// 	if(isset($parametros['id_compartido'])){
	// 		$condicion .= ($condicion == "") ? " WHERE " : " AND ";
	// 		$condicion.=" diagnostico_compartido.id_compartido = '{$parametros['id_compartido']}' ";
	// 	}
	// 		// WHERE rce_diagnostico.rce_id = {$parametros['rce_id']}
	// 	$sql .= $condicion."	ORDER BY id_compartido desc";
	// 	$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR obtenerDatosPacientesAcostados<br>");
 //        return $datos;
	// }
}
?>