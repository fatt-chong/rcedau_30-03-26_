<iframe height="100%" width="100%" hidden>

<?php
header('Access-Control-Allow-Origin: *');
session_start();
ini_set('memory_limit', '1000M');
error_reporting(0);

// Include the main TCPDF library (search for installation path).
require_once('../../../../../estandar/TCPDF-main/tcpdf.php');
require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php');     $objCon           = new Connection; $objCon->db_connect();
require_once('../../../../class/Util.class.php');           $objUtil          = new Util;
require_once('../../../../class/Admision.class.php');       $objAdmision      = new Admision;
require_once('../../../../class/RegistroClinico.class.php');  $objRegistroClinico   = new RegistroClinico;
// require_once('../../../../class/Pronostico.class.php');    $objPronostico      = new Pronostico;
require_once("../../../../class/Dau.class.php" );       $objDetalleDau        = new Dau;
require_once("../../../../class/Rce.class.php" );       $objRce             = new Rce;
require_once("../../../../class/Servicios.class.php");    $objServicio        = new Servicios;
require_once("../../../../class/Agenda.class.php" );        $objAgenda            = new Agenda;
require_once("../../../../class/Usuarios.class.php" );      $objUsuarios            = new Usuarios;
require_once('../../../../class/Formulario.class.php');     $objFormulario      = new Formulario;
require_once('../../../../class/RecetaGES.class.php');     $objRecetaGES    = new RecetaGES;


  class MYPDF extends TCPDF {
    //Page header
    public function Test($ae) {
      if (!isset($this->xywalter)) {
        $this->xywalter = array();
      }
      $this->xywalter[] = array($this->GetX(), $this->GetY());
    }
  }

  // create new PDF document
  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
  $pdf->SetCreator(PDF_CREATOR);
  $pdf->SetAuthor('HJNC-RCE');
  $pdf->SetTitle('Datos de Atención de Urgencia DAU');
  $pdf->SetSubject('Formularios');
  $pdf->SetKeywords('RCE, Formularios');
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

  // set Rotate
  // $params       = $pdf->serializeTCPDFtagParameters(array(90));
  $parametros   = $objUtil->getFormulario($_POST);
  $recetaGES    = $objRecetaGES->obtenerPDFRecetaGES($objCon, $parametros);
  $fechaIngreso = date("d-m-Y", strtotime($recetaGES[0]['fechaIngreso']));
  $horaIngreso  = date("H:i:s", strtotime($recetaGES[0]['fechaIngreso']));
  $runPaciente  = ($objUtil->existe($recetaGES[0]["runExtranjero"]))
    ? $recetaGES[0]["runExtranjero"]
    : $objUtil->setRun_addDV($recetaGES[0]['runPaciente']);



  /*
  ################################################################################################################################################
                                DESPLIEGUE PDF
  ################################################################################################################################################
  */
  $pdf->AddPage();

  $html = '
		<table cellspacing="5" border="0" width="100%">
			<tr nobr="true">
				<td>
					<table width="100%">
						<tr>
							<td width="15%"><img src="/estandar/img/logo_gobierno_chile.jpg" width="55" height="55"></td>
							<td width="60%" style="font-size:18x; text-align:center;">
                <strong>RECETA GES URGENCIA <br /> NÚMERO DE DAU: ' . $recetaGES[0]['idDau'] . '</strong>
							</td>
							<td width="35%" style="font-size:15px; text-align:center;">
                Fecha : ' . $fechaIngreso . '
                <br />
                Hora  : ' . $horaIngreso . '
							</td>
						</tr>
					</table>
				</td>
			</tr>
    </table>

    <table cellspacing="5" border="0" width="100%">
			<tr nobr="true">
				<td>
					<table width="100%">
						<tr>
              <td width="15%">
                <p >Nombre:</p>
              </td>
							<td width="55%" style="border-bottom: 1px solid #000;">
                <p >' . $recetaGES[0]["nombrePaciente"] . '</p>
							</td>
              <td width="10%">
                <p >Edad:</p>
              </td>
							<td width="20%" style="border-bottom: 1px solid #000;">
                <p >' . $recetaGES[0]["edadPaciente"] . '</p>
							</td>
						</tr>
            <tr>
              <td width="15%">
                <p >RUN:</p>
              </td>
							<td width="85%" style="border-bottom: 1px solid #000;">
                <p >' . $runPaciente . '</p>
							</td>
						</tr>
            <tr>
              <td width="15%">
                <p >Diagnóstico:</p>
              </td>
							<td width="85%" style="border-bottom: 1px solid #000;">
                <p >' . $recetaGES[0]["diagnosticoPaciente"] . '</p>
							</td>
						</tr>
					</table>
				</td>
			</tr>
    </table>

    <br />
    <br />
    <br />
    <br />

    <table cellspacing="5" border="0" width="100%">
			<tr nobr="true">
				<td>
					<table width="100%" border="1" cellpadding="8">
            <thead>
              <tr>
                <th width="60%" style=" text-align:center;">MEDICAMENTO</th>
                <th width="20%" style=" text-align:center;">DOSIS</th>
                <th width="20%" style=" text-align:center;">DÍAS</th>
              </tr>
            </thead>
            <tbody>';

            foreach ($recetaGES as $receta) {
              $html .= '<tr>';
              $html .= '<td width="60%" >';
              $html .= "&nbsp;" . $receta["descripcionMedicamento"];
              $html .= '</td>';
              $html .= '<td width="20%" style=" text-align:center;">';
              $html .= "&nbsp;" . $receta["dosis"];
              $html .= '</td>';
              $html .= '<td width="20%" style=" text-align:center;">';
              $html .= "&nbsp;" . $receta["dias"];
              $html .= '</td>';
              $html .= '</tr>';
            }

  $html .= '
            </tbody>
		      </table>
		    </td>
		  </tr>
    </table>

    <br />
    <br />
    <br />
    <br />
  ';

  $usuarioReceta = $objUsuarios->obtenerDatosUsuario($objCon, $recetaGES[0]['usuarioIngresa']);
  $URLUsuarioIndicaciones = "http://" . FirmaPDF . "medicos/" . $usuarioReceta[0]['PROcodigo'] . ".png";
  $file_headers_usuarioIndicaciones = @get_headers($URLUsuarioIndicaciones, 1);

  $html .= '
    <table style="margin-top:50px;">
    <tr>
      <td style="width:50%">
        &nbsp;
      </td>
      <td style="width:50%">';

      if ($file_headers_usuarioIndicaciones[0] == 'HTTP/1.1 200 OK') {
        $html .= '
          <tr style="text-align:center;">
            <td>
              <img src="http://' . FirmaPDF . 'medicos/' . $usuarioReceta[0]['PROcodigo'] . '.png" style="width:150px; height:35px;">
            </td>
          </tr>
          <tr style="text-align:center;">
            <td>
              <p ><strong>' .$usuarioReceta[0]['PROdescripcion'] . '</strong></p>
            </td>
          </tr>
          <tr style="text-align:center;">
            <td>
              <p ><strong>' . $objUtil->formatearNumero($usuarioReceta[0]['PROcodigo']) . '-' . $objUtil->generaDigito($usuarioReceta[0]['PROcodigo']) . '</strong></p>
            </td>
          </tr>
        ';
      } else {
        $html .= '
          <tr style="text-align:center;">
            <td >
              <br />
              <br />
              <br />
              <br />
              __________________________________
              <br />
              <br />
              <strong>NOMBRE Y FIRMA DE MÉDICO</strong>
            </td>
          </tr>
        ';
      }

  $html .= '
      </td>
      </tr>
    </table>
  ';

  // output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// array with names of columns
$arr_nomes = array(
    array("", 20, 53) // array(name, new X, new Y);
);

// num of pages
$ttPages = $pdf->getNumPages();
for($i=1; $i<=$ttPages; $i++) {
    // set page
    $pdf->setPage($i);
    // all columns of current page
    foreach( $arr_nomes as $num => $arrCols ) {
        $x = $pdf->xywalter[$num][0] + $arrCols[1]; // new X
        $y = $pdf->xywalter[$num][1] + $arrCols[2]; // new Y
        $n = $arrCols[0]; // column name
        // transforme Rotate
        $pdf->StartTransform();
        // Rotate 90 degrees counter-clockwise
        $pdf->Rotate(90, $x, $y);
        $pdf->Text($x, $y, $n);
        // Stop Transformation
        $pdf->StopTransform();
    }
}
  // reset pointer to the last page
  
$pdf->lastPage();
$nombre_archivo = 'recetaGES.pdf';
$pdf->Output(__DIR__ . '/' . $nombre_archivo, 'FI');
$url = "/RCEDAU/views/modules/rce/rce/recetaGES.pdf";


  $objCon = null;
  ?>

</iframe>
<div class="embed-responsive embed-responsive-16by9">
  <iframe id="pdfRecetaGES" class="embed-responsive-item" src="<?php echo $url.'?v='.rand(); ?>" height="700" width="700" allowfullscreen></iframe>
</div>

<script>
  $('#pdfRecetaGES').ready(function() {
    ajaxRequest(raiz + '/controllers/server/admision/main_controller.php', 'nombreArchivo=<?= $url ?>&accion=eliminarPDF', 'POST', 'JSON', 1, '', true);
  });
</script>
