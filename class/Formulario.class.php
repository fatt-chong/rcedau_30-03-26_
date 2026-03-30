<?php class Formulario{

	function subeFormularioPDF($archivo){

		require_once("FTPClient.class.php"); $ftpObj = new FTPClient;
		require($_SERVER['DOCUMENT_ROOT']."/RCEDAU/config/config.php");

		// $FTP_HOST = FTP_IP;
		$FTP_HOST2 = "10.6.21.29";
		$FTP_HOST = "10.6.21.29";
		$FTP_USER = "OncoNet";
		$FTP_PASS= "OncoNet";

		$conectar = $ftpObj->connect($FTP_HOST, $FTP_USER, $FTP_PASS);

		if($conectar){
			$dir = date('Y', strtotime($archivo['fechaArchivo']))."/".date('m', strtotime($archivo['fechaArchivo']));
			$directorio = $ftpObj->makeDir($dir);

			if($directorio){
				$nombre_temp['tmp_name'] = $archivo['nombreArchivo'];
				$parametros['directorio'] = $dir."/";
				$parametros['nombre_archivo'] = $archivo['nombreArchivo'];
				$parametros['mode'] = FTP_BINARY;
				$subir = $ftpObj->uploadFile($nombre_temp, $parametros);
			}
		} else  {
			$conectar2 = $ftpObj->connect($FTP_HOST2, $FTP_USER, $FTP_PASS);
			echo $dir = date('Y', strtotime($archivo['fechaArchivo']))."/".date('m', strtotime($archivo['fechaArchivo']));
			$directorio = $ftpObj->makeDir($dir);

			if($directorio){
				$nombre_temp['tmp_name'] = $archivo['nombreArchivo'];
				$parametros['directorio'] = $dir."/";
				$parametros['nombre_archivo'] = $archivo['nombreArchivo'];
				$parametros['mode'] = FTP_BINARY;
				$subir = $ftpObj->uploadFile($nombre_temp, $parametros);
			}

		}
	}



	function subeResumenEntregaTurnoUrgencia ( $nombre_archivo ) {

		require_once("Upload.class.php"); $ftpObj = new FTPClient;
		require($_SERVER['DOCUMENT_ROOT']."/RCEDAU/config/config.php");

		$FTP_HOST = FTP_IP;
		$FTP_USER = "dauentregaturnourgencia";
		$FTP_PASS= "dauentregaturnourgencia";

		$conectar = $ftpObj->connect($FTP_HOST, $FTP_USER, $FTP_PASS);

		if ( $conectar ) {

			$dir = date('Y')."/1212".date('m');

			$directorio = $ftpObj->makeDir($dir);

			if ( $directorio ) {

				$nombre_temp['tmp_name'] = $nombre_archivo;

				$parametros['directorio'] = $dir."/";

				$parametros['nombre_archivo'] = $nombre_archivo;

				$parametros['mode'] = FTP_BINARY;

				$subir = $ftpObj->uploadFile($nombre_temp, $parametros);

			}
		}

	}

}
?>