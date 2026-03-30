<iframe height="100%" width="100%" hidden>
  <?php
  error_reporting(0);
  ini_set('post_max_size', '512M');
  ini_set('memory_limit', '1G');
  set_time_limit(0);
  header("Pragma: no-cache");
  header("Cache-Control: no-cache");
  header("Cache-Control: no-store");
  // Include the main TCPDF library (search for installation path).

  require_once('../../../../../estandar/TCPDF-main/tcpdf.php');
  require_once('../../../../class/Util.class.php');       		  $objUtil     			  = new Util;
  require("../../../../config/config.php");
  require_once('../../../../class/Connection.class.php');       $objCon             = new Connection; $objCon->db_connect();
  require_once('../../../../class/Imagenologia.class.php');     $objImagenologia    = new Imagenologia;
  //RECEPCION VARIABLE

  // create new PDF document
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetCreator(PDF_CREATOR);
  $pdf->SetAuthor('HJNC-RCE');
  $pdf->SetTitle('RCE - SOLICITUD DE EXAMEN');
  $pdf->SetSubject('Solicitud de Examen');
  $pdf->SetKeywords('RCE, SOLICITUD DE EXAMEN');
  //$pdf->SetHeaderData('logo_informe2.jpg', PDF_HEADER_LOGO_WIDTH,'HOSPITAL REGIONAL DE ARICA Y PARINACOTA','FORMULARIO DE CONSTANCIA INFORMACION AL PACIENTE GES');
  $pdf->setHeaderFont(array('helvetica', '', 6));
  $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
  $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
  $pdf->SetMargins(10, 3, 10);
  //$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
  //$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
  $pdf->SetPrintHeader(false);
  $pdf->SetPrintFooter(false);
  $pdf->SetAutoPageBreak(TRUE, 15);
  $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
  $pdf->setLanguageArray($l);
  $pdf->setFontSubsetting(false);
  $pdf->SetFont('helvetica', '', 8, '', true);

  // add a page
  $pdf->AddPage();

  // require("../../../config/config.php");
  // require_once('../../../class/Connection.class.php');
  // $objCon            = new Connection;
  // $objCon->db_connect();
  // require_once('../../../class/Util.class.php');
  // $objUtil           = new Util;
  // require_once('../../../class/Imagenologia.class.php');
  // $objImagenologia    = new Imagenologia;

  $parametros['solicitud_id'] = $_POST['idIndicacion'];
  $fechaHoy = $objUtil->getFechaPalabra(date('Y-m-d'));
  $resultadoIndicacionesImagenologia = $objImagenologia->listarIndicacionesImagenologia($objCon, $parametros);
  $parametros['idSIC'] = $resultadoIndicacionesImagenologia[0]['SIC_id'];
  if ($resultadoIndicacionesImagenologia[0]['det_ima_estado'] == 4) {
    $resultadoPDFImagenologia = $objImagenologia->pdfImagenologia_historico($objCon, $parametros);
    if (!$objUtil->existe($resultadoPDFImagenologia)) {
      $resultadoPDFImagenologia = $objImagenologia->pdfImagenologia($objCon, $parametros);
    }
  } else {
    $resultadoPDFImagenologia = $objImagenologia->pdfImagenologia($objCon, $parametros);
  }

  $resultadoExamenesImagenologia = $objImagenologia->listadoImagenologia($objCon, $parametros);

  if ($resultadoPDFImagenologia[0]['sexo'] == "M") {
    $masculino = "X";
  } else if ($resultadoPDFImagenologia[0]['sexo'] == "F") {
    $femenino = "X";
  }

  if ($resultadoPDFImagenologia[0]['SIC_multires'] == "S") {
    $multires = "SI";
  } else {
    $multires = "NO";
  }
  if ($resultadoPDFImagenologia[0]['SIC_embarazo'] == "S") {
    $embarazo = "SI";
  } else {
    $embarazo = "NO";
  }
  if ($resultadoPDFImagenologia[0]['SIC_diabetes'] == "S") {
    $diabetes = "SI";
  } else {
    $diabetes = "NO";
  }
  if ($resultadoPDFImagenologia[0]['SIC_asma'] == "S") {
    $asma = "SI";
  } else {
    $asma = "NO";
  }
  if ($resultadoPDFImagenologia[0]['SIC_hipertencion'] == "S") {
    $hipertencion = "SI";
  } else {
    $hipertencion = "NO";
  }

  if ($resultadoPDFImagenologia[0]['SIC_premedicacion'] == "S") {
    $premedicacionSI = "X";
  } else {
    $premedicacionNO = "X";
  }
  if ($resultadoPDFImagenologia[0]['SIC_contraste'] == "S") {
    $contrasteSI = "X";
  } else {
    $contrasteNO = "X";
  }
  if ($resultadoPDFImagenologia[0]['SIC_informado'] == "S") {
    $informado = "SI";
  } else {
    $informado = "NO";
  }
  if ($resultadoPDFImagenologia[0]['SIC_clearence_creatina'] == "S") {
    $clearence = "SI";
  } else {
    $clearence = "NO";
  }
  if ($resultadoPDFImagenologia[0]['SIC_proteccionrenal'] == "S") {
    $proteccionrenalSI = "X";
  } else {
    $proteccionrenalNO = "X";
  }
  if ($resultadoPDFImagenologia[0]['SIC_premedicacion'] == "S") {
    $premedicacionSI = "X";
  } else {
    $premedicacionNO = "X";
  }


  $html .= '
<table width="640" border="0">
	<br><br>
	<tbody>
		<tr>
			<td width="100" rowspan="4"><img src="../../../assets/img/logo.png" width="50" height="50" /><img src="../../../assets/img/nuestroHospital.png" width="50" height="50" /></td>
			<td width="200">MINISTERIO DE SALUD</td>
			<td width="200" rowspan="4"></td>
			<td width="50">Fecha:</td>
			<td width="90">' . date("d-m-Y") . '</td>
		</tr>
		<tr>
			<td>HOSPITAL DR. JUAN NOÉ CREVANI</td>
			<td>Hora:</td>
			<td>' . date("H:i:s") . '</td>
		</tr>
		<tr>
			<td>RUT: 61.606.000-7</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>18 DE SEPTIEMBRE N°1000</td>
			<td colspan="2">&nbsp;</td>
		</tr>
	</tbody>
</table>
<br><br>
<table width="660" border="1" cellpadding="3">
  <tbody>
  	<tr>
      <td colspan="9" align="center">REGISTRO SOLICITUD DE EXAMENES IMAGENOLOGICOS</td>
    </tr>
    <tr>
      <td width="220" colspan="2" align="center">FECHA SOLICITUD DE EXAMEN</td>
      <td width="220" colspan="2">TIPO EXAMEN</td>
      <td width="220" colspan="4" align="center">USO RECAUDACION</td>
    </tr>
    <tr>
      <td colspan="2" rowspan="1" align="center"><br><br>' . date("d-m-Y H:i", strtotime($resultadoPDFImagenologia[0]['SIC_fecha'])) . '</td>
      <td colspan="2" rowspan="1" align="center"><br><br>' . $resultadoPDFImagenologia[0]['SIC_tipo_examen'] . '</td>
    </tr>
    <tr>
      <td colspan="2">' . $resultadoPDFImagenologia[0]['apellidopat'] . '</td>
      <td colspan="2">' . $resultadoPDFImagenologia[0]['apellidomat'] . '</td>
      <td colspan="4">' . $resultadoPDFImagenologia[0]['nombres'] . '</td>
    </tr>
    <tr>
      <td colspan="2" align="center">APELLIDO PATERNO</td>
      <td colspan="2" align="center">APELLIDO MATERNO</td>
      <td colspan="4" align="center">NOMBRES</td>
    </tr>
    <tr>
      <td width="165">' . $objUtil->setRun_addDV($resultadoPDFImagenologia[0]['rut']) . '</td>
      <td width="165" colspan="3">' . $resultadoPDFImagenologia[0]['nroficha'] . '</td>
      <td width="165" colspan="2">' . $resultadoPDFImagenologia[0]['SIC_RAU'] . '</td>
      <td width="165" colspan="3">' . $resultadoPDFImagenologia[0]['SIC_ctacte'] . '</td>
    </tr>
    <tr>
      <td align="center">RUT</td>
      <td colspan="3" align="center">Nº FICHA CLINICA</td>
      <td colspan="2" align="center">DAU</td>
      <td colspan="3" align="center">CUENTA CORRIENTE</td>
    </tr>
    <tr>
      <td colspan="2" rowspan="2">EDAD<br><br>' . $objUtil->edadActual($resultadoPDFImagenologia[0]['fechanac']) . ' AÑOS</td>
      <td width="100" rowspan="2">SEXO</td>
      <td width="100" >Femenino</td>
      <td width="20">' . $femenino . '</td>
      <td width="73">' . date("d", strtotime($resultadoPDFImagenologia[0]['fechanac'])) . '</td>
      <td width="73"colspan="2">' . date("m", strtotime($resultadoPDFImagenologia[0]['fechanac'])) . '</td>
      <td width="74">' . date("Y", strtotime($resultadoPDFImagenologia[0]['fechanac'])) . '</td>
    </tr>
    <tr>
      <td>Masculino</td>
      <td>' . $masculino . '</td>
      <td colspan="4" align="center">FECHA NACIMIENTO</td>
    </tr>
    <tr>
      <td colspan="2">CR PROCEDENCIA<br><br>' . $resultadoPDFImagenologia[0]['SIC_crprocedencia'] . '</td>
      <td colspan="3">SALA<br><br>' . $resultadoPDFImagenologia[0]['SIC_sala'] . '</td>
      <td colspan="4">CAMA<br><br>' . $resultadoPDFImagenologia[0]['SIC_cama'] . '</td>
    </tr>
    <tr>
      <td colspan="5" rowspan="3">EXAMEN SOLICITADO<br><br>';
  $html .= '<table border="1" width="100%">
              <tr style="color: #ffffff; background-color: #337ab7;">
                  <td align="center" width="20%">Fecha</td>
                  <td align="center" width="20%">Tipo de Examen</td>
                  <td align="center" width="60%">Examen</td>
              </tr>';
  for ($i = 0; $i < count($resultadoExamenesImagenologia); $i++) {
    $html .= '<tr>
                  <td align="center" style="vertical-align: middle;">' . date("d-m-Y H:i", strtotime($resultadoExamenesImagenologia[$i]['sol_ima_fechaInserta'])) . '</td>
                  <td align="center" style="vertical-align: middle;">' . $resultadoExamenesImagenologia[$i]['det_ima_tipo_examen'] . '</td>
                  <td align="center" style="vertical-align: middle;">' . $resultadoExamenesImagenologia[$i]['det_ima_descripcion'] . ' (Obs: ' . $resultadoPDFImagenologia[0]['SIC_RCE_observacion'] . ')</td>
              </tr>';
  }
  $html .= '</table></td>
      <td colspan="3">SIN CONTRASTE</td>
      <td>' . $contrasteNO . '</td>
    </tr>
    <tr>
      <td colspan="3">CON CONTRASTE</td>
      <td>' . $contrasteSI . '</td>
    </tr>
    <tr>
      <td colspan="4">*Cuestionario uso medio de contraste.</td>
    </tr>
    <tr>
      <td colspan="9">DIAGNÓSTICO<br><br>' . $objUtil->reemplazarCaracteresHTML($resultadoPDFImagenologia[0]['SIC_diagnostico']) . '</td>
    </tr>
    <tr>
      <td colspan="9">SINTOMAS PRINCIPALES<br><br>' . $objUtil->reemplazarCaracteresHTML($resultadoPDFImagenologia[0]['SIC_sintomas_pricipales']) . '</td>
    </tr>
    <tr>
      <td colspan="9">ANTECEDENTES QUIRURGICOS (Cirugías, Biopsias, Etc.)<br><br>' . $objUtil->reemplazarCaracteresHTML($resultadoPDFImagenologia[0]['SIC_antecedentes_quir']) . '</td>
    </tr>
    <tr>
      <td colspan="9">ANTECEDENTES CLINICOS RELEVANTES</td>
    </tr>
    <tr>
      <td>Hipertensión</td>
      <td>' . $hipertencion . '</td>
      <td colspan="6">Diabetes</td>
      <td>' . $diabetes . '</td>
    </tr>
    <tr>
      <td>Asma</td>
      <td>' . $asma . '</td>
      <td colspan="6">Infección o colonizacón multiresistente</td>
      <td>' . $multires . '</td>
    </tr>
    <tr>
      <td>Embarazo Actual</td>
      <td>' . $embarazo . '</td>
      <td>Otros</td>
      <td colspan="6">' . $objUtil->reemplazarCaracteresHTML($resultadoPDFImagenologia[0]['SIC_otros']) . '</td>
    </tr>
    <tr>
      <td colspan="9" align="center"><p>CHEQUEO SEGURIDAD</p>
      <p>SOLICITUD EXAMEN IMAGENOLOGICO CON MEDIO DE CONTRASTE</p></td>
    </tr>
    <tr>
      <td colspan="2" align="center">CONSENTIMIENTO INFORMADO COMPLETO</td>
      <td colspan="3" align="center">CLEARENCE DE CREATININA</td>
      <td colspan="4" align="center">PREMEDICACION</td>
    </tr>
    <tr>
      <td colspan="2" align="center">' . $informado . '</td>
      <td colspan="3" align="center">' . $clearence . '</td>
      <td colspan="4">(Pacientes alérgicos y/o asmáticos)</td>
    </tr>
    <tr>
      <td colspan="2" rowspan="2">*Exámenes con uso de medios de contraste o procedimientos imagenológicos.</td>
      <td rowspan="2">Protección Renal</td>
      <td>SI</td>
      <td>' . $proteccionrenalSI . '</td>
      <td rowspan="2">SI</td>
      <td rowspan="2">' . $premedicacionSI . '</td>
      <td rowspan="2">NO</td>
      <td rowspan="2">' . $premedicacionNO . '</td>
    </tr>
    <tr>
      <td>NO</td>
      <td>' . $proteccionrenalNO . '</td>
    </tr>
    <tr>
      <td colspan="3" align="center">' . $resultadoExamenesImagenologia[0]['PROdescripcion'] . '<br>' . $objUtil->setRun_addDV($resultadoExamenesImagenologia[0]['PROcodigo']) . '</td>';

      $firma = '&nbsp;';
      $imagenFirma = "http://".IP."/firmaDigital/medicos/".$resultadoExamenesImagenologia[0]['PROcodigo'].".png";
  		$verificacionImagenFirma = @get_headers($imagenFirma, 1);
    	if($verificacionImagenFirma[0] == 'HTTP/1.1 200 OK') {
        $firma = '<img class="indicaciones" src="http://'.IP.'/firmaDigital/medicos/'.$resultadoExamenesImagenologia[0]['PROcodigo'].'.png" style="width:150px; height:35px;">';
      }
      $html .= '<td colspan="6" align="center">'.$firma.'</td>
    </tr>
    <tr>
      <td colspan="3" align="center">NOMBRE MEDICO SOLICITANTE</td>
      <td colspan="6" align="center">FIRMA</td>
    </tr>
  </tbody>
</table>
';


  $pdf->writeHTML($html, true, false, true, false, '');
  $pdf->writeHTML($html2, true, false, true, false, '');



  $pdf->Output(__DIR__ . '/registroSolicitudExamen.pdf', 'FI');
  $url = "/RCEDAU/views/modules/rce/rce/registroSolicitudExamen.pdf";


  // $url = "/RCEDAU/views/modules/rce/rce/hojaHospitalizacion.pdf";
  ?>
</iframe>
<div class="embed-responsive embed-responsive-16by9">
  <iframe id="iframeSolicitudImagenologia" class="embed-responsive-item" src="<?= $url ?>" height="100%" width="100%" allowfullscreen></iframe>
</div>

<script>
  $('#iframeSolicitudImagenologia').ready(function() {
    ajaxRequest(raiz + '/controllers/server/admision/main_controller.php', 'nombreArchivo=<?= $url ?>&accion=eliminarPDF', 'POST', 'JSON', 1, '', true);
  });
</script>
