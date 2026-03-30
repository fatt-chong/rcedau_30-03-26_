<?php
class FTPClient {
    private $connectionId;
    private $loginOk = false;
    private $_ftpUser = '';
    private $_ftpPassword = '';
    private $_ftpHost = '';

    public function __construct() {
        
    }



     //FUNCION CONECTAR AL SERVIDOR
    public function connect($server, $ftpUser, $ftpPassword, $isPassive = false) {
        $this->_ftpHost     = $server;
        $this->_ftpUser     = $ftpUser;
        $this->_ftpPassword = $ftpPassword;
        
        $this->connectionId = ftp_connect($server, 21, 10);
        if($this->connectionId){
            $loginResult    = @ftp_login($this->connectionId, $ftpUser, $ftpPassword);
            if($loginResult){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }



    //CREAR DIRECTORIO SI NO EXISTE
    public function makeDir($directory){
        if($this->_ftp_is_dir($directory)){
            return true;
        }else{
            if(ftp_mkdir($this->connectionId, $directory)){
                return true;
            }else{
                return false;
            }
        }
    }



    //DIRECTORIO EXISTE O NO
    private function _ftp_is_dir($directory) {
        $pushd = ftp_pwd($this->connectionId);
        if ($pushd !== false && @ftp_chdir($this->connectionId, $directory)) {
            ftp_chdir($this->connectionId, $pushd);
            return true;
        }
        return false;
    }



    //FUNCION SUBIR ARCHIVO - $_FILES[], documento_1, directorio/2017
    public function uploadFile($arrPostFile, $parametros){
        $fileFrom   = $arrPostFile['tmp_name'];
        $fileTo     = $parametros['directorio'].$parametros['nombre_archivo'];
        $upload = ftp_put($this->connectionId, $fileTo, $fileFrom, $parametros['mode']);

        if($upload){
            return true;
        }else{
            return false;
        }
    }



    public function deleteFile($parametros) {
        $file = $parametros['directorio'].$parametros['nombre_archivo'];
        $delete = @ftp_delete($this->connectionId, $file);
        if($delete){
            return true;
        }else{
            return false;
        }
    }


    
    public function __deconstruct() {
        if ($this->connectionId) {
            ftp_close($this->connectionId);
        }
    }

}
