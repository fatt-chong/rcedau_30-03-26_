<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/RCEDAU/config/config.php");

if (is_null($_POST["idSolicitudDalca"]) || empty($_POST["idSolicitudDalca"])) {
  exit(1);
}

echo '<iframe class="embed-responsive-item" id="iframeSolicitudImagenologiaDalca" width="100%" height="550" src="'.URL_PDF_SOLICITUD_IMAGENOLOGIA_DALCA.$_POST["idSolicitudDalca"].'.pdf" allowfullscreen ></iframe>';
?>
