<?php   
// session_start();

 // print('<pre>'); print_r($_SESSION['permiso'.SessionName]); print('</pre>');
/*VALIDAMOS LA SESSION DE USUARIO*/
if((!isset($_SESSION["MM_UsernameName".SessionName]) || $_SESSION["MM_UsernameName".SessionName] == NULL)){
?>
	<style type="text/css">
		h1 {
		  color: #000;
		  text-shadow: #555 1px 1px 1px;
		}
	</style>
	<div class="page-header">
	  <h1>La sesi&#243;n no est&#225; iniciada, porfavor inicie sesi&#243;n...</h1>
	</div>
<?php   
	exit();
}
$permisos = $_SESSION['permiso'.SessionName];
function redireccionar(){
	header("Location: http://10.6.21.29/onconet/index.php");
}
?>