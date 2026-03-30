<?php 
session_start();
ini_set('max_execution_time', 600);
ini_set('memory_limit', '128M');
require("../../../../config/config.php");
require(PROYECTO."/views/modules/sesion_expirada.php");
require_once("../../../../class/Connection.class.php");         $objCon             = new Connection();
require_once("../../../../class/Util.class.php");				$objUtil 	 		= new Util;
require_once('../../../../class/Diagnosticos.class.php');       $objDiagnosticos    = new Diagnosticos;
$objCon->db_connect();

$version        						= $objUtil->versionJS();
$parametros     						= $objUtil->getFormulario($_POST);
$subparametros['id_compartido'] 	= $parametros['id'];
$rsRce_diagnostico 	= $objDiagnosticos->obtenerRce_diagnosticoCompartido($objCon,$subparametros);

?>

<div class="row">
	<div class="col-lg-12">
		<textarea class="  form-control form-control-sm mifuente11"  style="height: 100%"  name="frm_diagnostico_descrip" rows="10" id="frm_diagnostico_descrip" ><?php echo $rsRce_diagnostico[0]['diagnistico_descripcion_text_comentario'];?></textarea>
	</div>
</div>

<script type="text/javascript">
           

  
    $(document).ready(function() {
        $(document).on('shown.bs.modal', '.modal', function () {
            var inputText = $('#frm_diagnostico_descrip');
            
            if (inputText.length > 0 && inputText.val() !== "") {
                inputText.focus();
                
                var strLength = inputText.val().length;
                inputText[0].setSelectionRange(strLength, strLength);
            } else if (inputText.length > 0) {
                setTimeout(function () {
                    inputText.focus();
                }, 0);
            }
        });
    });
  

    </script>