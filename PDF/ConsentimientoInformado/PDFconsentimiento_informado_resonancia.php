<?php
session_start();
require_once('../../../estandar/TCPDF-main/tcpdf.php');
require_once('../../../estandar/tcpdf/config/lang/spa.php');
require("../../config/config.php");
require_once('../../class/Connection.class.php'); $objCon      = new Connection;$objCon->db_connect();
require_once('../../class/Util.class.php');       $objUtil     = new Util;
require_once('../../class/Paciente.class.php');   $objPaciente = new Paciente;
require_once('../../class/Imagenologia.class.php');   $objImagenologia = new Imagenologia;

error_reporting(0);
//date_default_timezone_set("America/Santiago");
ini_set('post_max_size', '512M');
ini_set('memory_limit', '1G');
set_time_limit(0);
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Cache-Control: no-store");

$idPaciente   = $_GET['id_paciente'];
$embarazado   = $_GET['embarazado'];

// print('<pre>'); print_r($_GET); print('</pre>');

$infoPaciente = $objPaciente->datosPaciente($objCon,$idPaciente);
$rutCompleto  = $infoPaciente[0]["rut"]."-".$objUtil->generaDigito($infoPaciente[0]["rut"]);
$fechaHoy     = date("d-m-Y");

$rs_prestacion = $objImagenologia->getImagenologia_prestaciones_tabla_nueva($objCon,$_GET['txt_examenes_codigo']);

class MYPDF extends TCPDF {
		//Page header
		public function Header() {
		//get the current page break margin
		$bMargin = $this->getBreakMargin();
		// get current auto-page-break mode
		$auto_page_break = $this->AutoPageBreak;
		// disable auto-page-break
		$this->SetAutoPageBreak(false, 0);
		// restore auto-page-break status
		$this->SetAutoPageBreak($auto_page_break, $bMargin);
		// set the starting point for the page content
		$this->setPageMark();
		}
	}

	// create new PDF document
	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('HJNC');
	$pdf->SetTitle('PDF');
	$pdf->SetSubject('Examen');
	$pdf->SetKeywords('dddd, eeee, fffff');
	//$pdf->SetHeaderData('../../assets/img/ABA-05.png', PDF_HEADER_LOGO_WIDTH,'SERVICIO DE SALUD ARICA ','HOSPITAL REGIONAL DE ARICA Y PARINACOTA');
	$pdf->setHeaderFont(Array('helvetica', '', 6));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	$pdf->SetMargins(PDF_MARGIN_LEFT, 8, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->SetAutoPageBreak(TRUE, 15);
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	$pdf->setLanguageArray($l);
	$pdf->setFontSubsetting(true);
	$pdf->SetFont('helvetica', '', 8, '', true);




	$html='
			<table class="bordeCeldaGrande" cellspacing="5" border="0" width="100%">
				<tr>
					<td>
						<table width="100%" border="0">
							<tr>
								<td align="left">
									<img src="/RCEDAU/assets/img/Logo_del_Ministerio_de_Salud.png" width="130" height="120" />
								</td>
								<td align="center">
									<br>
									<strong style="margin:13px; font-size: 12px;">REGISTRO</strong>
									<br><br>
									<strong style="margin:13px; font-size: 12px;">
										CONSENTIMIENTO INFORMADO PARA RESONANCIA MAGNETICA
									</strong>
								</td>
								<td height="10" width="30%" align="right" style="font-size: 12px;">Arica, '.date('d-m-Y').'</td>

							</tr>             
						</table>
					</td>        
				</tr>
			</table>

			<br>

			<table class="bordeCeldaGrande" border="0" width="100%">
				<tr style="background-color: black; line-height: 1px;" >
					<td ></td>
				</tr>
			</table>

			<br>

			<table>
				<tr>
					<td style="font-size: 12px;" colspan="3" align="left" valign="bottom"><strong text-align: justify>1. DATOS DEL PACIENTE</strong></td>
				</tr>
			</table>

			<br>

			<table class="" cellpadding="3" border="1" width="100%" style="">
				<tr>
					<td width="20%"><p style="font-size: 12px;">Nombre Completo</p></td>
					<td width="80%"><p style="font-size: 12px;">'.$infoPaciente[0]["nombre_completo"].'</p></td>
				</tr>
				<tr>
					<td width="20%"><p style="font-size: 12px;">RUT</p></td>';
					if($infoPaciente[0]["extranjero"] == 'S'){
						$html.='<td width="80%" ><p style="font-size: 12px;">'.$infoPaciente[0]["rut_extranjero"].'</p></td>';
					}else{
						$html.='<td width="80%" ><p style="font-size: 12px;">'.$rutCompleto.'</p></td>';

					}
				$html.='
				</tr>
				<tr>
					<td width="20%"><p style="font-size: 12px;">Diagnóstico</p></td>';
					if($_GET['txt_diag']!=""){
						$html.='<td width="80%"><p style="font-size: 12px;">'.$_GET['txt_diag'].'</p></td>';
					}else{
						$html.='<td width="80%"><p style="font-size: 12px;">-</p></td>';
					}
				$html.='
				</tr>
			</table>

			<br>

			<table>
				<tr>
					<td style="font-size: 12px;" colspan="3" align="left" valign="bottom"><strong text-align: justify>2.	PROCEDIMIENTO A REALIZAR</strong></td>
				</tr>
			</table>

			<br>

			<table class="" cellpadding="3" border="1" width="100%" style="">
				<tr>
					<td width="25%"><p style="font-size: 12px;">Resonancia Magnética de</p></td>
					<td width="75%"><p style="font-size: 12px;">'.$rs_prestacion[0]['examen'].'</p></td>
				</tr>

				<tr>
					<td width="40%"><p style="font-size: 12px;">Con uso de Medio de Contraste Endovenoso</p></td>
					<td width="60%">
						<table border="0">
							<tr >
								<td width="30%" align="center"></td>
								<td width="10%" align="center">SI</td>											
								<td width="10%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
								<td width="10%" align="center">NO</td>
								<td width="10%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
								<td width="30%" align="center"></td>
							</tr>
						</table>
					</td>
				</tr>

			</table>

			<br>

			<table>
				<tr>
					<td style="font-size: 12px;" colspan="3" align="left" valign="bottom"><strong text-align: justify>3. OBJETIVOS DEL PROCEDIMIENTO A REALIZAR, CARACTERÍSTICAS Y POTENCIALES RIESGOS</strong></td>
				</tr>
			</table>

			<br>

			<table class="bordeCeldaGrande" cellspacing="5" border="0" width="100%">
				<tr>
					<td>
						<p style="font-size: 12px; text-align: justify;">
							La Resonancia Magnética (RM) es un método para producir imágenes detalladas de los órganos y tejidos a lo 
							largo del cuerpo, sin la necesidad de usar rayos X. En cambio, la Resonancia utiliza un poderoso campo 
							magnético y  ondas de radiofrecuencia que no producen daño conocido a los tejidos. La Resonancia no produce 
							dolor. A veces, el aparato de Resonancia puede hacer ruidos fuertes como de martilleo, golpeteo u otros tipos de 
							ruidos, durante el procedimiento usted será ubicado en el interior del túnel de la máquina, acostado y 
							permanecerá en esa posición entre 30-45 minutos. En todo momento, podrá comunicarse con el Tecnólogo 
							Médico a través de un intercomunicador. Se trata de un procedimiento seguro, que se utiliza todos los días en 
							muchos lugares del mundo.<br><br>
							Pese a toda la seguridad que ofrece este procedimiento, siempre se deben tomar resguardos, que en algunos 
							casos son precauciones, y en otros, la prohibición de hacerse dicho examen.<br><br>
							En algunos estudios de resonancia, puede ser necesario inyectar en una vena un medio de contraste llamado 
							“gadolinio” para ayudar a interpretar las imágenes obtenidas. A diferencia de los medios de contraste que se 
							usan en los estudios de rayos X, el medio de contraste con gadolinio no contiene yodo y por lo tanto rara vez 
							produce reacciones alérgicas u otros problemas. No obstante, si usted tiene un historial de enfermedad en los 
							riñones, fallo renal, trasplante renal, debe informárselo a su Médico Tratante y al Tecnólogo Médico antes de 
							recibir este medio de contraste.<br><br>
							El poderoso campo magnético del sistema de Resonancia atrae los objetos que contienen hierro (llamados 
							ferromagnéticos) y puede moverlos de forma repentina y con gran fuerza. En su calidad de paciente, es de vital 
							importancia que se quite todos sus artículos metálicos antes del examen; esto incluye: audífonos, dentadura 
							removible, llaves, relojes, joyas y prendas de vestir con hilo o ganchos de metal, artefactos electrónicos, tarjetas 
							magnéticas, monedas, etc.  Además, se deben remover el maquillaje, la pintura de uñas u otros cosméticos que 
							contengan partículas metálicas.<br><br>
							El poderoso campo magnético del sistema de Resonancia atraerá todos los objetos del cuerpo que contengan
							hierro. Por tal motivo es de suma importancia que nos indique si tiene en su cuerpo algún elemento metálico o 
							ferromagnético.
							Las restricciones descritas anteriormente, también aplican para el acompañante del Paciente que se presente al 
							Resonador Magnético, como por ejemplo: familiar, representante legal, auxiliar de enfermería, enfermera(o) o 
							médico. <strong>La transgresión de estas medidas puede provocar accidentes serios con eventual daño a los pacientes, 
							a los funcionarios y a las instalaciones.</strong>
						</p>
					</td>
				</tr>
			</table>';

			$html .='<br pagebreak="true"/>

			<table>
				<tr>
					<td style="font-size: 12px;" colspan="3" align="left" valign="bottom"><strong text-align: justify>4. DECLARACIÓN DEL PACIENTE</strong></td>
				</tr>
			</table>

			<br>

			<table class="bordeCeldaGrande" cellspacing="5" border="0" width="100%">
				<tr>
					<td>
						<p style="font-size: 12px; text-align: justify;">
							He conocido y comprendido satisfactoriamente los propósitos de este procedimiento y/o intervención y 
							sus riesgos. Se me ha permitido hacer todas las observaciones y preguntas aclaratorias.<br>
							Si se presentara algún imprevisto en mi tratamiento, el equipo médico podrá realizar tratamientos 
							adicionales o variar la técnica prevista de antemano.<br><br>
							Por ello manifiesto que estoy satisfecho(a) con la información recibida y que comprendo la explicación del 
							alcance y riesgos de mi tratamiento.<br><br>
							Marque con una cruz su decisión:<br><br>
							En tales condiciones: 
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SÍ____ Acepto
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                                    NO____Acepto<br><br>
							Se me ha informado que, en cualquier momento, puedo revocar este Consentimiento, siendo informado de 
							las posibles consecuencias para mi estado de salud y que no perderé los beneficios q que tengo derecho, 
							haciéndome responsable de esa decisión.
						</p>

						<table border="1" width="100%"  cellpadding="3">
							<tr>
								<td width="20%" style="font-size: 12px; line-height: 10px;">Firma del Paciente</td>
								<td width="80%"></td>
							</tr>

							<tr>
								<td colspan="2" style="font-size: 12px; line-height: 10px;">
									Nombre, Firma y RUT del Representante Legal <label style="color:#9c9c9c;">(Si corresponde)</label>
								</td>
							</tr>

							<tr>
								<td colspan="2" style="line-height: 10px;">
									
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<br>
			
			<table>
				<tr>
					<td style="font-size: 12px;" colspan="3" align="left" valign="bottom"><strong text-align: justify>5. REVOCACIÓN DEL CONSENTIMIENTO INFORMADO</strong></td>
				</tr>
			</table>

			<br>

			<table class="bordeCeldaGrande" cellspacing="5" border="0" width="100%">
				<tr>
					<td>
						<p style="font-size: 12px; text-align: justify;">
							Habiendo consentido anteriormente el procedimiento  o intervención que se señala y siendo debidamente 
							informado de la enfermedad que me aqueja y su manejo, en este acto revoco mi autorización y rechazo 
							expresamente el procedimiento, exámenes, operación o tratamiento indicado, asumiendo plenamente 
							toda la responsabilidad de las consecuencias que se deriven de ello.
						</p>

						<table border="1" width="100%"  cellpadding="3">
							<tr>
								<td width="20%" style="font-size: 12px; line-height: 10px;">Firma del Paciente</td>
								<td width="80%"></td>
							</tr>

							<tr>
								<td colspan="2" style="font-size: 12px; line-height: 10px;">
									Nombre, Firma y RUT del Representante Legal <label style="color:#9c9c9c;">(Si corresponde)</label>
								</td>
							</tr>

							<tr>
								<td colspan="2" style="line-height: 10px;">
									
								</td>
							</tr>
						</table>

						<table border="1" width="100%"  cellpadding="3">
							<tr>
								<td width="50%" style="font-size: 12px; line-height: 10px;" align="right">Fecha de Revocación</td>
								<td width="50%"></td>
							</tr>

						</table>

					</td>
				</tr>
			</table>';


			$html .='<br pagebreak="true"/>

			<table>
				<tr>
					<td style="font-size: 12px;" colspan="3" align="left" valign="bottom"><strong text-align: justify>6. REGISTRO DE CUESTIONARIO DE SEGURIDAD PARA RESONANCIA MAGNÉTICA</strong></td>
				</tr>
			</table>


			<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
				<tr>
					<td colspan="2" align="center">ADVERTENCIA: EL IMÁN DEL RESONADOR ESTÁ SIEMPRE ENCENDIDO</td>
				</tr>
				<tr>
					<td width="20%">
						<img src="/RCEDAU/assets/img/img_resonancia.jpg" />
					</td>
					<td  width="80%">
						<p style="font-size: 13px; text-align: justify;">
							Estimado Médico Tratante: El examen que usted está indicando a nuestro usuario, requiere que el Paciente sea 
							sometido a un gran Campo Magnético, el cual puede afectar el funcionamiento de ciertos implantes o dispositivos 
							médicos.  La presencia de cuerpos extraños metálicos en el Paciente como las  esquirlas metálicas, puede afectar la 
							calidad técnica del estudio, así como también provocar quemaduras en el paciente. En cuanto a la indicación del 
							Medio de Contraste, debe evaluar cuidadosamente la función renal de su Paciente, para evitar desarrollar Fibrosis 
							Sistémica Nefrogénica. Por todo lo anterior y para prevenir efectos adversos asociados a este procedimiento, lo 
							invitamos a contestar el siguiente cuestionario junto a su Paciente, para recolectar información actualizada y 
							minimizar al máximo cualquier riesgo de Bioseguridad.
						</p>
					</td>
				</tr>
			</table>

			<br>

			

			<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
				<tr>
					<td width="5%">
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">
								<td></td>
							</tr>
							<tr style="line-height: 9px;">	
								<td></td>
							</tr>
							<tr style="line-height: 1px;">
								<td></td>
							</tr>
						</table>
					</td>
					<td width="43%">
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 9px;">									
								<td>a)	Antecedentes Generales </td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>						
					</td>
					<td width="5%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 9px;">									
								<td>SI</td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>		
					</td>
					<td width="5%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 9px;">								
								<td>NO</td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>
					</td>
					<td width="7%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr>								
								<td>No<br>Aplica</td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>
					</td>
					<td width="35%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 9px;">									
								<td>Complementación de las respuestas</td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>						
					</td>
				</tr>

				</table>

				<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">

				<tr>
					<td width="5%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">
								<td></td>
							</tr>
							<tr style="line-height: 3px;">	
								<td align="center" >1</td>
							</tr>
							<tr style="line-height: 1px;">
								<td></td>
							</tr>
						</table>
					</td>

					<td width="43%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td>¿El paciente se ha realizado RM anteriores?</td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>						
					</td>

					<td width="5%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>		
					</td>

					<td width="5%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>		
					</td>


					<td width="7%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="100%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>		
					</td>

					<td width="35%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>						
					</td>
				</tr>

				<tr>
					<td width="5%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 2px;">
								<td></td>
							</tr>
							<tr style="line-height: 3px;">	
								<td align="center" >2</td>
							</tr>
							<tr style="line-height: 1px;">
								<td></td>
							</tr>
						</table>
					</td>

					<td width="43%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 10px;">									
								<td>¿El paciente se ha realizado RM anteriores con uso de medio de Contraste?</td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>						
					</td>

					<td width="5%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>		
					</td>

					<td width="5%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>		
					</td>

					<td width="7%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="100%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>		
					</td>

					<td width="35%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>						
					</td>
				</tr>

				<tr>
					<td width="5%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 3px;">
								<td></td>
							</tr>
							<tr style="line-height: 3px;">	
								<td align="center" >3</td>
							</tr>
							<tr style="line-height: 1px;">
								<td></td>
							</tr>
						</table>
					</td>

					<td width="43%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 10px;">									
								<td>¿El paciente ha tenido alguna complicación con RM anteriores? Si la respuesta es SÍ, especifique la complicación.</td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>						
					</td>

					<td width="5%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>		
					</td>

					<td width="5%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>		
					</td>

					<td width="7%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="100%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>		
					</td>

					<td width="35%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>						
					</td>
				</tr>

				<tr>
					<td width="5%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 2px;">
								<td></td>
							</tr>
							<tr style="line-height: 3px;">	
								<td align="center" >4</td>
							</tr>
							<tr style="line-height: 1px;">
								<td></td>
							</tr>
						</table>
					</td>

					<td width="43%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 10px;">									
								<td>
									¿El paciente se encuentra en estado de gravidez? Si la respuesta es SÍ, especifique período de gestación
								</td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>						
					</td>

					<td width="5%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>		
					</td>

					<td width="5%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>		
					</td>

					<td width="7%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="100%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>		
					</td>

					<td width="35%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>						
					</td>
				</tr>

				<tr>
					<td width="5%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 2px;">
								<td></td>
							</tr>
							<tr style="line-height: 3px;">	
								<td align="center" >5</td>
							</tr>
							<tr style="line-height: 1px;">
								<td></td>
							</tr>
						</table>
					</td>

					<td width="43%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td>
									¿Paciente tiene historial de Enfermedad Hepática?
								</td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>						
					</td>

					<td width="5%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>		
					</td>

					<td width="5%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>		
					</td>

					<td width="7%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="100%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>		
					</td>

					<td width="35%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>						
					</td>
				</tr>

				<tr>
					<td width="5%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 2px;">
								<td></td>
							</tr>
							<tr style="line-height: 3px;">	
								<td align="center" >6</td>
							</tr>
							<tr style="line-height: 1px;">
								<td></td>
							</tr>
						</table>
					</td>

					<td width="43%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td>
									¿Historial de Asma?
								</td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>						
					</td>

					<td width="5%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>		
					</td>

					<td width="5%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>		
					</td>

					<td width="7%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="100%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>		
					</td>

					<td width="35%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>						
					</td>
				</tr>

				<tr>
					<td width="5%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 2px;">
								<td></td>
							</tr>
							<tr style="line-height: 3px;">	
								<td align="center" >7</td>
							</tr>
							<tr style="line-height: 1px;">
								<td></td>
							</tr>
						</table>
					</td>

					<td width="43%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td>
									¿Historial de Alergias a Medio de Contraste Yodado?
								</td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>						
					</td>

					<td width="5%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>		
					</td>

					<td width="5%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>		
					</td>

					<td width="7%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="100%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>		
					</td>

					<td width="35%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						
						<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
							<tr style="line-height: 1px;">								
								<td width="98%"></td>								
							</tr>

							<tr style="line-height: 3px;">									
								<td></td>								
							</tr>

							<tr style="line-height: 1px;">								
								<td></td>
							</tr>
						</table>						
					</td>
				</tr>
			</table>

			<br>

			<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">				
				<tr>
					<td align="center" width="5%"  rowspan="2" ></td>
					<td align="center" width="25%" rowspan="2" ><label style="line-height: 12px;">b)	Antecedentes Quirúrgicos</label></td>
					<td align="center" width="5%"  rowspan="2" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"><label style="line-height: 12px;">SI</label></td>
					<td align="center" width="5%"  rowspan="2" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"><label style="line-height: 12px;">NO</label></td>
					<td align="center" width="10%" rowspan="2" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"><label style="line-height: 7px;">No<br>Aplica</label></td>
					<td align="center" width="50%" colspan="2" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">Indique las cirugías previas</td>
				</tr>
				<tr>
					<td align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"><label style="line-height: 1px;">Fecha</label></td>
					<td align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"><label style="line-height: 1px;">Tipo de Cirugía</label></td>
				</tr>
			</table>

			<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
				<tr>
					<td rowspan="3" width="30%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >
						¿Paciente sometido a algún tipo de cirugía en la zona a estudiar u otra cirugía relevante?
					</td>
					<td rowspan="3" width="5%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td rowspan="3" width="5%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td rowspan="3" width="10%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td colspan="2" width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
				</tr>
				<tr>
					<td colspan="2" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
					<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
				</tr>
				<tr>
					<td colspan="2" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
				</tr>
			</table>

			<br>

			<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">				
				<tr>
					<td align="center" width="5%"  rowspan="2" ></td>
					<td align="center" width="25%" rowspan="2" ><label style="line-height: 12px;">c)	Antecedentes Función Renal</label></td>
				</tr>
			</table>
<!-- style="text-align: justify; line-height: 6px;" -->
			<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
				<tr>
					<td rowspan="8" style="text-align: justify; border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"><label style="line-height: 10px;"><br><br>Por favor señale si el Paciente tiene alguna(s) de estas condiciones</label></td>
					<td width="5%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
					<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" colspan="2">Insuficiencia renal</td>
					<td width="35%" style="text-align: justify; border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" rowspan="8"><br><br>A todo Paciente que se le indique uso de Medio de Contraste EV, se le debe solicitar Creatinina Sérica para calcular la Tasa de Filtrado Glomerular (TFG). Para valores de <strong> TFG &#60; 30, </strong> el uso de Medio de Contraste está <strong>CONTRAINDICADO ABSOLUTAMENTE</strong></td>
				</tr>

				<tr>
					<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
					<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" colspan="2">Diabetes Mellitus</td>
				</tr>

				<tr>
					<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
					<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" colspan="2">Monorreno</td>
				</tr>

				<tr>
					<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
					<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" colspan="2">Antecedente o sospecha de Cáncer Renal</td>
				</tr>

				<tr>
					<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" colspan="2">Hipertensión Arterial</td>
				</tr>

				<tr>
					<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
					<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" colspan="2">Ninguna</td>
				</tr>				
			</table>';

			$html .='<br pagebreak="true"/>


			<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">				
				<tr>
					<td align="center" width="5%"  rowspan="2" ></td>
					<td align="center" width="25%" rowspan="2" ><label style="line-height: 12px;">d)	Otros Antecedentes</label></td>
				</tr>
			</table>

			<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
				<tr>
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >Historial de Esquirlas Metálicas en el cuerpo</td>
					<!-- <td>2</td> -->
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >Dispositivo Implantado  para Infusión de Medicamentos</td>
					<!-- <td>5</td> -->
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">Cualquier tipo de prótesis (ojo, peneal, rodilla, etc)</td>
				</tr>
				<tr>
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >Historial de Trauma Ocular</td>
					<!-- <td>2</td> -->
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >Implante Activado Magnéticamente</td>
					<!-- <td>5</td> -->
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">Bomba de Infusión de Insulina</td>
				</tr>
				<tr>
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >Marcapasos Cardíaco o Diafragmático</td>
					<!-- <td>2</td> -->
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >Dispositivo  Intrauterino (DIU) o subcutáneo</td>
					<!-- <td>5</td> -->
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">Endoprótesis Vasculares (stent)</td>
				</tr>
				<tr>
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >Implante Coclear</td>
					<!-- <td>2</td> -->
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >Implante Electrónico</td>
					<!-- <td>5</td> -->
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">Placa dental o frenillos</td>
				</tr>
				<tr>
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >Desfibrilador Automático Implantado</td>
					<!-- <td>2</td> -->
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >Movimientos Involuntarios, temblores </td>
					<!-- <td>5</td> -->
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">Grapas o Corchetes Quirúrgicos</td>
				</tr>
				<tr>
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >Clip Aneurismático</td>
					<!-- <td>2</td> -->
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >Electrodos o Alambres internos </td>
					<!-- <td>5</td> -->
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">Tatuajes o Maquillaje Permanente</td>
				</tr>
				<tr>
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >Claustrofobia</td>
					<!-- <td>2</td> -->
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >Perforación o Piercing</td>
					<!-- <td>5</td> -->
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">Audífonos</td>
				</tr>

				<tr>
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >Ayuno en regla, si corresponde</td>
					<!-- <td>2</td> -->
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" >Expansor mamario</td>
					<!-- <td>5</td> -->
					<td width="8.3%"  style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px" ></td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">Sistema de Neuroestimulación</td>
				</tr>
			</table>

			<br>


			<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">				
				<tr>
					<td align="center" width="5%"  rowspan="2" ></td>
					<td align="center" width="25%" rowspan="2" ><label style="line-height: 12px;">e) Autorización Extraordinaria:</label></td>
				</tr>
			</table>

			<table class="bordeCeldaGrande" cellpadding="3" border="1" width="100%">
				<tr>
					<td width="90%">Usted autoriza a su paciente a practicarse Radiografías Simples, si fuese necesario después de la entrevista con el TM de RM, para confirmar la presencia de algún elemento metálico en su cuerpo</td>
					<td width="5%" align="center"><strong>SI</strong></td>
					<td width="5%" align="center"><strong>NO</strong></td>
				</tr>
			</table>

			<br>

			<table class="bordeCeldaGrande" cellpadding="3" border="1" width="100%">
				<tr>
					<td width="70%" style="line-height: 13px;"></td>
					<td width="30%" align="center"></td>
				</tr>

				<tr>
					<td width="70%" align="center">Nombre y Apellido de Médico Solicitante</td>
					<td width="30%" align="center">Firma</td>
				</tr>
			</table>

			<br>

			<table class="bordeCeldaGrande" cellpadding="3" border="1" width="100%">
				<tr>
					<td style="font-size: 12px;" colspan="3" align="left" valign="bottom"><strong text-align: justify>7.	REGISTRO DE ENTREVISTA A PACIENTE POR PARTE DE PERSONAL DE RM</strong></td>
				</tr>
			</table>

			<br>

			<table class="bordeCeldaGrande" cellpadding="3" border="1" width="100%">
				
				<tr>
					<td colspan="2">¿Información de Encuesta es concordante con información verbal de Paciente?</td>
					<td align="center">SI</td>
					<td align="center">NO</td>
				</tr>

				<tr>
					<td colspan="2">¿Cuál o cuáles son las discrepancias?</td>
					<td colspan="2"></td>
				</tr>

				<tr>
					<td colspan="2" rowspan="2">¿Fue necesario adquirir Radiografías Simples? Indique Cuál o Cuáles</td>
					<td align="center">SI</td>
					<td align="center">NO</td>
				</tr>

				<tr>
					<td colspan="2"></td>
				</tr>

				<tr>
					<td colspan="2">Personal de RM que realiza entrevista. Indique nombre</td>
					<td colspan="2"></td>
				</tr>

				<tr>
					<td colspan="2">Persona Entrevistada.  Indique nombre (Paciente, familiar, personal clínico)</td>
					<td colspan="2"></td>
				</tr>

				<tr>
					<td colspan="2">Paciente, finalmente, ¿puede ingresar al Resonador Magnético?</td>
					<td align="center">SI</td>
					<td align="center">NO</td>
				</tr>
			</table>';

			$html .='<br pagebreak="true"/>

			<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
				<tr>
					<td style="font-size: 12px;" colspan="3" align="left" valign="bottom"><strong text-align: justify>8.	REGISTRO DEL TECNÓLOGO MÉDICO</strong></td>
				</tr>
			</table>

			<br>

			<table class="bordeCeldaGrande" cellpadding="3" border="1" width="100%">
				<tr>
					<td width="18.7%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">Creatinina y Fecha Examen</td>
					<td width="12.5%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
					<td width="18.7%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">Peso del Paciente</td>
					<td width="12.5%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
					<td width="12.5%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">Talla del Paciente</td>
					<td width="8.5%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
					<td width="8.5%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">TFG</td>
					<td width="8.1%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
				</tr>
			</table>

			<table class="bordeCeldaGrande" cellpadding="3" border="1" width="100%">
				<tr>
					<td width="18.7%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">Horas de Ayuno</td>
					<td width="12.5%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
					<td width="18.7%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">Medio de Contraste</td>
					<td width="25%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
					<td width="17%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">Dosis Inyectada</td>
					<td width="8.1%"style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
				</tr>
			</table>

			<table class="bordeCeldaGrande" cellpadding="3" border="1" width="100%">
				<tr>
					<td width="18.7%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">Contraste Oral</td>
					<td width="31.2%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
					<td width="25%" align="center" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">Otros Medicamentos</td>
					<td width="25.1%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
				</tr>

				<tr>
					<td colspan="2" style="line-height: 13px;" ></td>
					<td colspan="2" style="line-height: 13px;" ></td>
				</tr>

				<tr>
					<td align="center" colspan="2">Nombre, Apellido y Firma de Tecnólogo Médico que realiza el procedimiento</td>
					<td align="center" colspan="2">Fecha del Examen</td>
				</tr>


			</table>

			<br>

			<table class="bordeCeldaGrande" cellpadding="3" border="0" width="100%">
				<tr>
					<td style="font-size: 12px;" colspan="3" align="left" valign="bottom"><strong text-align: justify>9. OBSERVACIONES</strong></td>
				</tr>

				<tr>
					<td width="" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px; line-height: 150px;"></td>

				</tr>
			</table>











			';

	$pdf->AddPage();
	$pdf->writeHTML($html, true, false, true, false, '');
	// $pdf->writeHTML($html, true, 0, true, 0);
	$pdf->Output('registro.pdf','I');
	//$url = "/pruebaxD/salidas/generarVoucher.pdf";
?>

