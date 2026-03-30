<?php
class Upload {
    private $FTPconnection  = false;
    private $FTPuser        = '';
    private $FTPpass        = '';
    private $FTPhost        = '';

    public function __construct($FTPservidor, $FTPusuario, $FTPpassword) {
        $this->FTPhost          = $FTPservidor;
        $this->FTPuser          = $FTPusuario;
        $this->FTPpass          = $FTPpassword;
    }
    //FUNCION CONECTAR AL SERVIDOR
    private function connect() {
        $this->FTPconnection    = ftp_connect($this->FTPhost);
        if($this->FTPconnection){
            if(ftp_login($this->FTPconnection, $this->FTPuser, $this->FTPpass)){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    //CREA DIRECTORIO SI NO EXISTE
    private function makeDir($directorio){
        if($this->_ftp_is_dir($directorio)){
            return true;
        }else{
            if(ftp_mkdir($this->FTPconnection, $directorio)){
                return true;
            }else{
                return false;
            }
        }
    }
    //DIRECTORIO EXISTE O NO
    private function _ftp_is_dir($directorio) {
        $pushd  = ftp_pwd($this->FTPconnection);
        if ($pushd !== false && @ftp_chdir($this->FTPconnection, $directorio)) {//WARNING SI ES QUE NO EXISTE @
            ftp_chdir($this->FTPconnection, $pushd);
            return true;
        }
        return false;
    }
    //FUNCION SUBIR ARCHIVO - NOMBRE_ARCHIVO, DIRECTORIO/NOMBRE_ARCHIVO
    private function uploadFile($parametros){
        // SUBIR EL ARCHIVO **********************************************
        if(ftp_put($this->FTPconnection, $parametros['directorio'].$parametros['nombre_archivo'], $parametros['nombre_archivo'], $parametros['mode'])){//$parametros['mode'] = FTP_BINARY; $parametros['mode'] = FTP_ASCII;
            return true;
        }else{
            return false;
        }
    }
    private function deleteFile($parametros) {// DIRECTORIO/NOMBRE_ARCHIVO
        // ELIMINAR EL ARCHIVO **********************************************
        if(ftp_delete($this->FTPconnection, $parametros['directorio'].$parametros['nombre_archivo'])){
            return true;
        }else{
            return false;
        }
    }
    private function close() {
        if ($this->FTPconnection) {
            ftp_close($this->FTPconnection);
            $this->FTPconnection = NULL;
        }
    }
    public function subirArchivoFTP($parametros){
        /* METODO QUE SUBE UN ARCHIVO ADJUNTADO AL FTP.
            $parametros['nombre_archivo']   = "nombre_archivo.pdf";
            $parametros['directorio']       = "directorio/".date('Y')."/";
            $parametros['mode']             = FTP_BINARY;//$parametros['mode'] = FTP_ASCII;
        */
        if($this->connect()){//SE CONECTA AL SERVIDOR
            if($this->makeDir($parametros['directorio'])){//CREA DIRECTORIO SI NO EXISTE
                if($this->uploadFile($parametros)){//ENVIAR: NOMBRE_ARCHIVO, DIRECTORIO/NOMBRE_ARCHIVO y FTP_BINARY-FTP_ASCII - RETORNA TRUE o FALSE
                    $this->close();
                    $response = array("status" => "success");
                }else{
                    $response = array("status" => "error", "message" => "No se pudo subir el Archivo: <b>".$parametros['nombre_archivo']."</b>.");
                }
            }else{
                $response = array("status" => "error", "message" => "No se pudo crear el Directorio: <b>".$parametros['directorio']."</b>.");
            }
        }else{
            $response = array("status" => "error", "message" => "No se pudo conectar al Servidor: <b>".$this->FTPhost."</b>.");
        }
        return $response;
    }
    public function eliminarArchivoFTP($parametros){
        /* METODO QUE SUBE UN ARCHIVO ADJUNTADO AL FTP.
            $parametros['nombre_archivo']   = "nombre_archivo.pdf";
            $parametros['directorio']       = "directorio/".date('Y')."/";
        */
        if($this->connect()){//SE CONECTA AL SERVIDOR
            if($this->deleteFile($parametros)){//ENVIAR: NOMBRE_ARCHIVO, DIRECTORIO/NOMBRE_ARCHIVO - RETORNA TRUE o FALSE
                $this->close();
                $response = array("status" => "success");
            }else{
                $response = array("status" => "error", "message" => "No se pudo eliminar el Archivo: <b>".$parametros['nombre_archivo']."</b>.");
            }
        }else{
            $response = array("status" => "error", "message" => "No se pudo conectar al Servidor: <b>".$this->FTPhost."</b>.");
        }
        return $response;
    }

}
