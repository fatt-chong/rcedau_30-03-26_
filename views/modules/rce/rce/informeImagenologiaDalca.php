<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/RCEDAU/config/config.php");

if (is_null($_POST["informeDalca"]) || empty($_POST["informeDalca"])) {
  exit(1);
}

// echo "<embed width='100%' height='700px' src='data:application/pdf;base64,".$_POST["informeDalca"]."' type='application/pdf'></embed>";
?>

<style>
   /* Reiniciar márgenes y asegurar que el body ocupe toda la pantalla */
html, body {
    margin: 0;
    padding: 0;
    height: 100%;
    overflow: hidden; /* Evitar barras de desplazamiento del body */
}

/* Contenedor principal que se ajusta a la pantalla */
.ScrollStyleFrame2 {
    width: 100%;
    height: 100vh; /* Ocupa el 100% de la pantalla */
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Estilos para hacer el PDF responsivo */
.ScrollStyleFrame {
    width: 100%;
    height: 100%; /* Hace que el PDF ocupe toda la altura del contenedor */
}
/* Ajustes específicos según la altura del viewport */
@media (max-height: 576px) { /* Pantallas pequeñas */
    .ScrollStyleFrame2 {
        height: calc(105vh - 318px);
    }
}
@media (min-height: 577px) and (max-height: 768px) { /* Pantallas medianas */
    .ScrollStyleFrame2 {
        height: 590px;
    }
}
@media (min-height: 769px) and (max-height: 992px) { /* Pantallas grandes */
    .ScrollStyleFrame2 {
        height: 590px;
    }
}
@media (min-height: 993px) and (max-height: 1080px) { /* Extra grandes */
    .ScrollStyleFrame2 {
        height: 575px;
    }
}
@media (min-height: 1081px) { /* Más de 1080px */
    .ScrollStyleFrame2 {
        height: 80vh; /* Ocupará el 80% del viewport */
   }
/* Ajustes específicos según la altura del viewport */
@media (max-height: 576px) { /* Pantallas pequeñas */
    .ScrollStyleFrame {
        height: calc(105vh - 318px);
    }
}
@media (min-height: 577px) and (max-height: 768px) { /* Pantallas medianas */
    .ScrollStyleFrame {
        height: 590px;
    }
}
@media (min-height: 769px) and (max-height: 992px) { /* Pantallas grandes */
    .ScrollStyleFrame {
        height: 590px;
    }
}
@media (min-height: 993px) and (max-height: 1080px) { /* Extra grandes */
    .ScrollStyleFrame {
        height: 575px;
    }
}
@media (min-height: 1081px) { /* Más de 1080px */
    .ScrollStyleFrame {
        height: 80vh; /* Ocupará el 80% del viewport */
    }
}


</style>
<div class="ScrollStyleFrame2">
    <embed id="pdfEmbed" class="ScrollStyleFrame" width="100%" src="data:application/pdf;base64,<?php echo $_POST["informeDalca"]; ?>" type="application/pdf">
</div>
