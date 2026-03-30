<?php
session_start();
require("../../../../config/config.php");
$keys_sesion = base64_encode($_SESSION['MM_Username'.SessionName]);
?>
<style>
/* Estilo CSS para eliminar los bordes del iframe y el contenedor */
  #histClinico {
    border: 0;
  }
  iframe {
    border: 0;
  }
</style>
<script type="text/javascript">
  
    var alturaNavegador = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
    var iframe = document.createElement("iframe");
      // Establece los atributos del iframe (src, ancho, alto, etc.)
      // iframe.src = "../../../../../historialclinicoNew/Interfaz/resumenHistorial.php?pacId=<?=$_POST['paciente_id']?>&act=rau&k=<?=$keys_sesion?>";
      iframe.src = "http://10.6.21.19/HCE_ONE/Interfaz/resumenHistorial.php?pacId=<?=$_POST['paciente_id']?>&act=rau&k=<?=$keys_sesion?>";
      iframe.width = "100%"; // Ancho en píxeles
      iframe.height = alturaNavegador-250; // Alto en píxeles
      var divContenedor = document.getElementById("histClinico");
      // Agrega el iframe al cuerpo del documento
      divContenedor.appendChild(iframe);
</script>
<div id="histClinico" class="col-lg-12 mini-box bordeColumnas"></div>
