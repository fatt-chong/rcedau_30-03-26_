<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 10pt; }
    table { width: 100%; border-collapse: collapse; }
    td, th { padding: 4px; vertical-align: top; }
    .seccion { border: 1px solid #000; margin-top: 10px; padding: 6px; }
    .titulo { font-weight: bold; text-align: center; margin-top: 10px; font-size: 14pt; }
    .subtitulo { font-weight: bold; margin-top: 6px; }
    .box { border: 1px solid #000; padding: 3px; }
    .tabla-braden th, .tabla-braden td { border: 1px solid #000; text-align: center; }
  </style>
</head>

<?php
session_start();
error_reporting(0);
require_once("../../../../config/config.php");
require_once('../../../../class/Util.class.php');               $objUtil                = new Util;
require_once('../../../../class/Connection.class.php');         $objCon                 = new Connection; $objCon->db_connect();
require_once("../../../../class/Dau.class.php" );               $objDau                 = new Dau;
require_once('../../../../class/Config.class.php');             $objConfig              = new Config;
require_once('../../../../class/RegistroClinico.class.php');    $objRegistroClinico     = new RegistroClinico;
require_once('../../../../class/Rce.class.php');                $objRce                 = new Rce;

$parametros                   = $objUtil->getFormulario($_POST);
$dau_id                       = $_POST['dau_id'];
$rsRce                        = $objRegistroClinico->consultaRCE($objCon,$parametros);
$listaSignos                  = $objRce ->listarSignosVitales($objCon, $rsRce[0]['id_paciente'], $rsRce[0]['regId']);

?>
<body>
  <div style="text-align: center;">
    <h3>HOJA INGRESO DE ENFERMERÍA ADULTO</h3>
    <p><strong>SERVICIO DE SALUD ARICA</strong><br>
    HOSPITAL EN RED "DR. JUAN NOÉ CREVANI"<br>
    CR. EMERGENCIA HOSPITALARIA</p>
  </div>

  <div class="seccion">
    <strong>Fecha:</strong> ______ de ______ del ______ &nbsp;&nbsp;&nbsp; <strong>Hora:</strong> ___________
  </div>

  <div class="seccion">
    <div class="subtitulo">ANTECEDENTES PERSONALES</div>
    <strong>Nombre:</strong> ___________________________ &nbsp;&nbsp; <strong>Edad:</strong> ______ &nbsp;&nbsp; <strong>Previsión:</strong> _______<br>
    <strong>Motivo de consulta:</strong> _______________________________________________________
  </div>

  <div class="seccion">
    <div class="subtitulo">ANTECEDENTES MÉDICOS Y QUIRÚRGICOS</div>
    <strong>Médicos:</strong> HTA ( )  DIABETES ( )  Otras: ________________________<br>
    <strong>Quirúrgicos:</strong> _______________________________________________________________<br>
    <strong>Alérgicos:</strong> SÍ ( )  NO ( )  DESCONOCIDA ( )<br>
    <strong>Medicamentos:</strong> _____________________________________________________________
  </div>

  <div class="seccion">
    <div class="subtitulo">EXAMEN FÍSICO GENERAL</div>
    <strong>Estado general:</strong> BUENO ( ) REGULAR ( ) MALO ( )<br>
    <strong>Estado de conciencia:</strong> CONSCIENTE ( ) VIGIL ( ) SOPOR ( ) COMA ( ) EBRIO ( ) GLASGOW (_____)<br>
    <strong>Condiciones higiénicas:</strong> BUENA ( ) REGULAR ( ) MALA ( )<br>
    <strong>Piel y mucosas:</strong> ERITEMA ( ) EDEMA ( ) MACERACIÓN ( )<br>
    <strong>Lesiones:</strong> DOLOR ( ) SEQUEDAD PIEL ( ) CALOR LOCALIZADO ( ) CAMBIO COLORACIÓN ( )<br>
    <strong>Heridas:</strong> SÍ ( ) NO ( ) UBICACIÓN: __________________________________________<br>
    <strong>Temperatura:</strong> AFEBRIL ( ) FEBRIL ( ) HIPOTERMIA ( ) SUDOROSA ( ) FRÍA ( )
    <p><strong>Observaciones de piel/ubicación:</strong><br><br><br></p>
  </div>

  <div class="seccion">
    <div class="subtitulo">ESCALA DE BRADEN PARA PACIENTE ADULTO</div>
    <table class="tabla-braden">
      <tr>
        <th>PERCEPCIÓN SENSORIAL</th>
        <th>EXPOSICIÓN A LA HUMEDAD</th>
        <th>ACTIVIDAD</th>
        <th>MOVILIDAD</th>
        <th>NUTRICIÓN</th>
        <th>RIESGO DE LESIONES CUTÁNEAS</th>
      </tr>
      <tr>
        <td>Completamente limitada</td>
        <td>Constantemente húmeda</td>
        <td>Encamado</td>
        <td>Completamente inmóvil</td>
        <td>Muy pobre</td>
        <td>Problema</td>
      </tr>
      <tr>
        <td>Muy limitada</td>
        <td>Húmeda con frecuencia</td>
        <td>En silla</td>
        <td>Muy limitada</td>
        <td>Probablemente inadecuada</td>
        <td>Problema potencial</td>
      </tr>
      <tr>
        <td>Ligeramente limitada</td>
        <td>Ocasionalmente húmeda</td>
        <td>Deambula ocasionalmente</td>
        <td>Ligeramente limitada</td>
        <td>Adecuada</td>
        <td>No existe problema aparente</td>
      </tr>
      <tr>
        <td>Sin limitaciones</td>
        <td>Raramente húmeda</td>
        <td>Deambula frecuentemente</td>
        <td>Sin limitación</td>
        <td>Excelente</td>
        <td></td>
      </tr>
    </table>
    <p>
      <strong>RIESGO LPP BRADEN-BERGSTROM:</strong> <br>
      ALTO RIESGO (≤ 12 pts), RIESGO MODERADO (13-14 pts), BAJO RIESGO (15 pts), SIN RIESGO (≥ 18 pts)<br>
      <strong>Tipo de riesgo:</strong> ____________ &nbsp;&nbsp;&nbsp; <strong>Puntaje:</strong> _______
    </p>
  </div>

  <div class="seccion">
    <div class="subtitulo">SIGNOS VITALES - Hora del control: _____</div>
    P.A.: ________ mmHg &nbsp;&nbsp;&nbsp; F. CARDIACA: ______ x’ &nbsp;&nbsp;&nbsp; TEMPERATURA: ______ ºC<br>
    F. RESPIRATORIA: ______ x’ &nbsp;&nbsp;&nbsp; EUPNEA ( ) DISNEA ( ) POLIPNEA ( ) BRADIPNEA ( )<br>
    SATURACIÓN O2: ______ % &nbsp;&nbsp;&nbsp; OXÍGENO COMPLEMENTARIO ( ) ____________________<br>
    HEMOGLOBINA: ___________
  </div>

  <div class="seccion">
    <div class="subtitulo">EVOLUCIÓN DE ENFERMERÍA</div>
    <br><br><br><br>
  </div>

  <div class="seccion">
    <div class="subtitulo">EXAMEN FÍSICO SEGMENTARIO</div>
    <br><br><br><br>
  </div>
</body>
</html>
