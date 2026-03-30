<?php
	class SolicitudHospitalizacion{

		function pacienteSeEncuentraHospitalizado($objCon, $parametros){
			$sql = "SELECT *
					FROM camas.camas
					WHERE camas.camas.id_paciente = '{$parametros['id_paciente']}'";
			$datos = $objCon->consultaSQL($sql,"<br>Error al obtener los datos del paciente en Gestion de Camas<br>");
			return $datos;
		}



		function pacienteSeEncuentraHospitalizadoSN($objCon, $parametros){
			$sql = "SELECT *
						FROM camas.listasn
						WHERE camas.listasn.idPacienteSN = '{$parametros['id_paciente']}'";
			$datos = $objCon->consultaSQL($sql,"<br>Error al obtener los datos del paciente en Gestion de Camas - Lista SN<br>");
			return $datos;
		}



		function pacienteSeEncuentraTransitoPac($objCon, $parametros){
			$sql = "SELECT *
					FROM camas.transito_paciente
					WHERE camas.transito_paciente.id_paciente = '{$parametros['id_paciente']}'";
			$datos = $objCon->consultaSQL($sql,"<br>Error al obtener los datos del paciente en Transito Paciente<br>");
			return $datos;
		}



		function insertPacienteTransitoPac($objCon, $parametros){
			$sql = "INSERT INTO camas.transito_paciente
					(transito_paciente.cta_cte,
					transito_paciente.cod_sscc_desde,
					transito_paciente.desc_sscc_desde,
					transito_paciente.cod_sscc_hasta,
					transito_paciente.desc_sscc_hasta,
					transito_paciente.id_paciente,
					transito_paciente.rut_paciente,
					transito_paciente.ficha_paciente,
					transito_paciente.nom_paciente,
					transito_paciente.tipo_traslado,
					transito_paciente.hospitalizado,
					transito_paciente.fecha,
					transito_paciente.hora,
					transito_paciente.diagnostico1)
					VALUES (
					'{$parametros['idctacte']}',
					'10322',
					'Unidad de Emergencia',
					'{$parametros['id_rau']}',
					'{$parametros['servicio']}',
					'{$parametros['id_paciente']}',
					'{$parametros['rut']}',
					'{$parametros['nroficha']}',
					'{$parametros['nombreFull']}',
					1,
					'{$parametros['fechaHoraActual_Hospitalizacion']}',
					'{$parametros['FechaActual']}',
					'{$parametros['HoraActual']}',
					'{$parametros['dau_hipotesis_diagnostica_inicial']}')";

			$datos = $objCon->ejecutarSQL($sql,"<br>Error al insertar los datos del paciente en Transito Paciente<br>");
			return $datos;
		}

	}
?>