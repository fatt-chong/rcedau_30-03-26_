<?php 
session_start();
require(dirname(__FILE__)."/../config/config.php");

date_default_timezone_set("America/Santiago");

class Adjunto{
    function subirArchivos($objCon,$parametros){
		$sql="	INSERT INTO paciente.adjunto 
							(
								id_cabecera,
								fecha,
								hora,
								usuario,
								tipo_adjunto,
								nombre_archivo,
                                formato_archivo
								
							)
				VALUES 		(
								'{$parametros['id_cabecera']}',
								'{$parametros['fecha']}',
								'{$parametros['hora']}',
								'{$parametros['usuario']}',
								'{$parametros['tipo_adjunto']}',
								'{$parametros['nombre_archivo']}',
								'{$parametros['formato_archivo']}'

							)";
		$response = $objCon->ejecutarSQL($sql, "ERROR AL subirArchivos");
		return $objCon->lastInsertId();
	}
	function update_Adjunto($objCon, $parametros){
		$condicion 	= "";
        $sql="UPDATE paciente.adjunto ";
        if(isset($parametros['id_cabecera'])){
            $condicion .= ($condicion == "") ? " SET " : " , ";
            $condicion.=" id_cabecera = '{$parametros['id_cabecera']}'";
        }if(isset($parametros['tipo_adjunto_descripcion'])){
            $condicion .= ($condicion == "") ? " SET " : " , ";
            $condicion.=" tipo_adjunto_descripcion = '{$parametros['tipo_adjunto_descripcion']}'";
        }if(isset($parametros['id_paciente'])){
            $condicion .= ($condicion == "") ? " SET " : " , ";
            $condicion.=" id_paciente = '{$parametros['id_paciente']}'";
        }if(isset($parametros['nombre_archivo'])){
            $condicion .= ($condicion == "") ? " SET " : " , ";
            $condicion.=" nombre_archivo = '{$parametros['nombre_archivo']}'";
        }
        $sql .= $condicion." WHERE id_adjunto = '{$parametros['id_adjunto']}' ";
        $resultado = $objCon->ejecutarSQL($sql, "Error al UpdateIndicacion");
    }

    function deleteArchivos($objCon,$parametros){
        $sql=" DELETE FROM paciente.adjunto  WHERE id_adjunto= '{$parametros['id_adjunto']}'";
		$objCon->ejecutarSQL($sql, "ERROR AL subirArchivos");
	}


   


    function SelectArchivosAdjuntos($objCon,$parametros){
		$sql="SELECT
				*
			FROM
				paciente.adjunto
            WHERE id_cabecera = '{$parametros['id_cabecera']}'  and tipo_adjunto = '{$parametros['tipo_adjunto']}' ";
		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR SelectAntiaz_Cito<br>");
        return $datos;
	}


    function subirArchivosFTP($objCon, $postFile, $parametros){
		require_once("FTPClient.class.php"); $ftpObj = new FTPClient;
		

		if($postFile['name']){
			define('FTP_HOST', FTP_IP_DOCUMENTOSPaciente);
			define('FTP_USER', FTP_USUARIO_DOCUMENTOSPaciente);
			define('FTP_PASS', FTP_CLAVE_DOCUMENTOSPaciente);

			// define('FTP_HOST', "10.6.21.22");
			// define('FTP_USER', "siveas");
			// define('FTP_PASS', "siveas");
			
				$conectar = $ftpObj->connect(FTP_HOST, FTP_USER, FTP_PASS);//SE CONECTA AL SERVIDOR
				if($conectar){
					// echo "1";
					$directorio = $ftpObj->makeDir($parametros['directorio']);//CREA DIRECTORIO SI NO EXISTE
					if($directorio){
						// echo "2";
						// print('<pre>'); print_r($postFile); print('</pre>');
						$subir = $ftpObj->uploadFile($postFile, $parametros);//ENVIAR: $FILES['NOMBRE INPUT'], DIRECTORIO/NOMBRE_ARCHIVO - RETORNA TRUE o FALSE
						// echo "subir: ".$subir;
						if($subir){
							// echo "3";
							$objCon->commit();
							$response = array("status" => "success", "id" => $parametros['id']);
						}else{
							// echo "4";
							$objCon->rollback();
							$response = array("status" => "error", "message" => "No se pudo subir el Archivo: <b>".$parametros['nombre_archivo']."</b>.");
						}
					}else{
						echo "5";
						$objCon->rollback();
						$response = array("status" => "error", "message" => "No se pudo crear el directorio.");
					}
				}else{
					echo "6";
					$objCon->rollback();
					$response = array("status" => "error", "message" => "No se pudo conectar al Servidor: <b>".FTP_HOST."</b>.");
				}
				return $response;
			}
	}
}
