<?php
session_start();
require_once('../../../estandar/TCPDF-main/tcpdf.php');
require_once('../../../estandar/tcpdf/config/lang/spa.php');
require("../../config/config.php");
require_once('../../class/Connection.class.php');     $objCon          = new Connection;$objCon->db_connect();
require_once('../../class/Util.class.php');           $objUtil         = new Util;
require_once('../../class/Paciente.class.php');       $objPaciente     = new Paciente;
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
$embarazado   = $_GET['emb'];

// print('<pre>');  print_r($_GET);  print('</pre>');


$infoPaciente = $objPaciente->datosPaciente($objCon,$idPaciente);
$rutCompleto  = $infoPaciente[0]["rut"]."-".$objUtil->generaDigito($infoPaciente[0]["rut"]);
$fechaHoy     = date("d-m-Y");


$rs_prestacion = $objImagenologia->getImagenologia_prestaciones_tabla_nueva($objCon,$_GET['txt_examenes_codigo']);
// print('<pre>'); print_r($rs_prestacion); print('</pre>');

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
	//SET DOCUMENT INFORMATION
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('LEnet');
	$pdf->SetTitle('PDF');
	$pdf->SetSubject('cccccc');
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

	

	if($embarazado == 'S'){

		$html.='
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
											CONSENTIMIENTO INFORMADO EXAMEN DE TOMOGRAFIA COMPUTADA CON MEDIO DE CONTRASTE IODADO ENDOVENOSO';
											if($embarazado == 'S'){
												$html.=' PACIENTE EMBARAZADA';
											}										
										$html.='
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

				<table class="bordeCeldaGrande" cellspacing="5" border="0" width="100%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">
					

					<tr>
						<td style="" colspan="3" align="left">
							<table width="100%" cellpadding="1" style="font-size:8;" border="0">
								<tr>
									<td width="30%"><p style="font-size: 12px;">a)&nbsp;&nbsp;NOMBRE</p></td>
									<td width="50%"><p style="font-size: 12px;">'.$infoPaciente[0]["nombre_completo"].' </p></td>								
									<td width="9%"><p style="font-size: 12px;">RUN</p></td>
									<td width="11%"><p style="font-size: 12px;">'.$rutCompleto.'</p></td>								
								</tr>

								<tr>
									<td width="30%"><p style="font-size: 12px;" >b)&nbsp;&nbsp;DIAGNOSTICO</p></td>';
									if($_GET['txt_diag']!=""){
										$html.='<td width="67%"><p style="font-size: 12px;" >'.$_GET['txt_diag'].' </p></td>';
									}else{
										$html.='<td width="67%"><p style="font-size: 12px;" > - </p></td>';
									}
									
									$html.='
									<td width="1.5%">&nbsp;</td>
									<td width="1.5%">&nbsp;</td>	
								</tr>';

								if($embarazado == 'S'){
								$html.='
									<tr>
										<td ><p style="font-size: 12px;">c)&nbsp;&nbsp;SEMNAS DE GESTACION</p></td>
										<td colspan="3"><p style="font-size: 12px;">.............................................................................................................................</p></td>
									</tr>';
								}							
							$html.='
							</table>
						</td>
					</tr>
				</table>

				<br>

				<table>
					<tr>
						<td style=";" colspan="3" align="left" valign="bottom"><strong style="font-size: 12px; text-align: justify">2. INTERVENCIÓN Y/O PROCEDIMIENTO A REALIZAR</strong></td>
					</tr>
				</table>

				<table class="bordeCeldaGrande" cellspacing="5" border="0" width="100%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">
					<tr>
						<td style="" colspan="3" align="left">
							<table width="100%" cellpadding="1" style="font-size:8;" border="0">
								<tr>
									<td><p style="font-size: 12px;">Examen de Tomografía computada de : '.$rs_prestacion[0]['examen'].'</p></td>			
								</tr>
							</table>
						</td>
					</tr>
				</table>


				<br>

				<table>
					<tr>
						<td style=";" colspan="3" align="left" valign="bottom"><strong style="font-size: 12px; text-align: justify">3. OBJETIVOS DE LA INTERVENCIÓN Y/O PROCEDIMIENTO, CARACTERÍSTICAS Y POTENCIALES RIESGOS </strong></td>
					</tr>
				</table>

				<table class="bordeCeldaGrande" cellspacing="5" border="0" width="100%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">
					<tr>
						<td>
							<p style="font-size: 12px; text-align: justify">
								Es un examen realizado con radiación X en el cual se le administrará medio de 
								contraste endovenoso, con el fin de obtener una mejor imagen del examen, 
								contrastando los órganos, se le tomarán imágenes seriadas según el órgano a estudiar.<br>
								La administración de medio de contraste por vía endovenosa  no produce 
								problema en la mayoría de los casos a quien lo reciba pero en algunas personas 
								puede provocar  efectos adversos menores y en otros casos efectos adversos que 
								muy excepcionalmente pueden  poner en riesgo su vida.<br>
								En pacientes con alguna patología renal, al ser sometidos bajo este procedimiento 
								puede  agudizar su enfermedad, por lo que es importante evaluar la función renal 
								previa a este exámen.<br>
								Los rayos X son potencialmente peligrosos para el feto, por lo que para prevenir 
								esos posibles efectos, se le va a colocar protección en el abdomen (si el examen 
								no es de esa zona) para disminuir la radiación hasta el útero, de esta forma, se 
								reducen los efectos perjudiciales. 
								Su médico tratante ha considerado que los beneficios que se obtienen para su 
								diagnóstico al realizar la exploración superan a los posibles perjuicios que le 
								puedan ocasionar a Usted y el feto.
								Si bien el medio de contraste no ha demostrado ser dañino para el feto, tampoco 
								se ha demostrado que es inocuo.
								Toda esta información le puede resultar confusa, por lo que es libre de realizar 
								todas las preguntas con su medico tratante.
							</p>



						</td>
					</tr>

				</table>';


				$html .='<br pagebreak="true"/>

				<table>
					<tr>
						<td style=";" colspan="3" align="left" valign="bottom"><strong style="font-size: 12px; text-align: justify">4. DECLARACION DEL PACIENTE </strong></td>
					</tr>
				</table>

				<table class="bordeCeldaGrande" cellspacing="5" border="0" width="100%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">
					<tr>
						<td>
							<p style="font-size: 12px; text-align: justify; font-style: italic">
								He conocido  y comprendido satisfactoriamente los propósitos de éste procedimiento y/o 
								intervención  y sus riesgos. Se me ha permitido hacer todas las observaciones y 
								preguntas aclaratorias.<br><br>
								Si se presentara algún imprevisto en mi tratamiento el equipo médico podrá realizar 
								tratamientos adicionales o variar la técnica quirúrgica prevista de antemano.<br><br>
								Por ello manifiesto que estoy satisfecho(a) con la información recibida y que comprendo 
								la explicación del alcance y riesgos de mi tratamiento.
							</p>

							<strong style="font-size: 12px; text-align: justify; font-style: italic">
								Marque con una cruz  su decisión:
							</strong>

							<p style="font-size: 12px; text-align: justify; font-style: italic">
								En tales condiciones   &nbsp;&nbsp;&nbsp;&nbsp; <strong>SI………</strong>  acepto &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>NO………</strong> acepto
							</p>

							<p style="font-size: 12px; text-align: justify; font-style: italic">
								Se me ha informado que, en cualquier momento, puedo revocar este consentimiento, 
								siendo  informado de las posibles consecuencias para mi estado de salud y que no 
								perderé los beneficios a que tengo derecho, haciéndome responsable de esa decisión.
							</p>

							<p style="font-size: 12px; text-align: justify; font-style: italic">
								Firma del Paciente .....................................................................................................................................................
							</p>


							<p style="font-size: 12px; text-align: justify; font-style: italic">
								Nombre, Firma y RUN del Representante Legal <label style="color:#9c9c9c;">(Si corresponde)</label><br><br>
								.....................................................................................................................................................
							</p>						
						</td>
					</tr>
				</table>

				<br>

				<table>
					<tr>
						<td style=";" colspan="3" align="left" valign="bottom"><strong style="font-size: 12px; text-align: justify">5. IDENTIFICACIÓN Y FIRMAS DE PROFESIONALES RESPONSABLES </strong></td>
					</tr>
				</table>

				<table class="bordeCeldaGrande" cellspacing="5" border="0" width="100%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px">
					<tr>
						<td><br>
							<p style="font-size: 12px; text-align: justify; font-style: italic">
								Nombre: ……………………………………………………………………………… &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Firma: …………………………<br>
								PROFESIONAL QUE INDICA EL PROCEDIMIENTO <br><br>
								Nombre: ……………………………………………………………………………… &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Firma: …………………………<br>
								PROFESIONAL QUE REALIZA EL PROCEDIMIENTO   <label style="color:#9c9c9c;">(Si corresponde)</label>
							</p>						
						</td>
					</tr>
				</table>

				<br>

				<table>
					<tr>
						<td style=";" colspan="3" align="left" valign="bottom"><strong style="font-size: 12px; text-align: justify">Fecha </strong>................................................</td>
					</tr>
				</table> ';
	}else{
		$html ='
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
										CONSENTIMIENTO INFORMADO PARA EXAMEN IMAGENOLOGICO CON USO DE MEDIO CONTRASTE YODADO ENDOVENOSO										
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

			<table class="bordeCeldaGrande" cellspacing="5" border="1" width="100%" >
				<tr>
					<td style="" colspan="3" align="left">
						<table width="100%" cellpadding="3" style="font-size:8;" border="1">
							<tr>
							    <td width="30%"><p style="font-size: 12px;">&nbsp;&nbsp;NOMBRE</p></td>
								<td width="70%"><p style="font-size: 12px;">'.$infoPaciente[0]["nombre_completo"].' </p></td>															
							</tr>

							<tr>
								 <td width="30%"><p style="font-size: 12px;" >&nbsp;&nbsp;RUN</p></td>';
								 if($infoPaciente[0]["extranjero"] == 'S'){
								 	$html.=' <td width="70%"><p style="font-size: 12px;" >'.$infoPaciente[0]["rut_extranjero"].' </p></td>';
								 }else{
								 	$html.=' <td width="70%"><p style="font-size: 12px;" >'.$rutCompleto.' </p></td>';

								 }	
							$html.=' 
							</tr>

							<tr>
								 <td width="30%"><p style="font-size: 12px;" >&nbsp;&nbsp;DIAGNOSTICO</p></td>';
								 if($_GET['txt_diag']!=""){
								 	$html.='<td width="70%"><p style="font-size: 12px;" >'.$_GET['txt_diag'].' </p></td>';	
								 }else{
								 	$html.='<td width="70%"><p style="font-size: 12px;" >-</p></td>';
								 }

							$html.='</tr>';						
						$html.='
						</table>
					</td>
				</tr>
			</table>

			<br>

			<table>
				<tr>
					<td style="font-size: 12px;" colspan="3" align="left" valign="bottom"><strong text-align: justify>2. PROCEDIMIENTO A REALIZAR</strong></td>
				</tr>
			</table>

			<table class="bordeCeldaGrande" cellspacing="5" border="1" width="100%" >
				<tr>
					<td style="" colspan="3" align="left">

						<table class="bordeCeldaGrande" cellpadding="3" border="1" width="100%">
							<tr>
								<td width="40%"><p style="font-size: 12px;">&nbsp;&nbsp;Tomografía Computada de</p></td>
								<td width="60%"><p style="font-size: 12px;">'.$rs_prestacion[0]['examen'].' </p></td>	
							</tr>

							<tr>
								<td width="40%"><p style="font-size: 12px;">&nbsp;&nbsp;Otro Examen con Uso de MC (Indicar)</p></td>
								<td width="60%"><p style="font-size: 12px;"> </p></td>	
							</tr>

						</table>
					</td>
				</tr>
			</table>

			<br>

			<table>
				<tr>
					<td style="font-size: 12px;" colspan="3" align="left" valign="bottom"><strong text-align: justify>3. OBJETIVOS DEL PROCEDIMIENTO A REALIZAR, CARACTERÍSTICAS Y POTENCIALES RIESGOS </strong></td>
				</tr>
			</table>

			<table class="bordeCeldaGrande" cellspacing="5" border="1" width="100%" >
				<tr>
					<td style="" colspan="3" align="left">
						<table class="bordeCeldaGrande" cellpadding="3" border="1" width="100%">
							<tr>
								<td>
									<p style="font-size: 14px; text-align: justify; ">
										El Medio de Contraste Yodado, se ocupa en la ejecución de exámenes con uso de Radiación Ionizante
										o Rayos X. El objetivo de realizar un examen con Medio de Contraste Endovenoso, es obtener mejores 
										imágenes y mayor información, resaltando el sistema vascular y los órganos durante la exploración.
										La administración de Medio de Contraste Yodado por vía Endovenosa no produce problema en la 
										mayoría de los casos, pero en algunas personas puede provocar efectos adversos menores y en otros
										casos efectos adversos que, muy excepcionalmente, pueden poner en riesgo su vida.<br>
										En pacientes con alguna patología renal, al administrarles Medio de Contraste Yodado Endovenoso,
										puede agudizar su enfermedad. Es por esto, que es importante evaluar la función renal previo a este
										examen.<br>
										Los Rayos X revisten algunos riesgos, sin embargo, la valoración del beneficio por su Médico Tratante,
										es mayor que el riesgo de la  Dosis de Radiación a la que Usted se expondría. 
									</p>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>';

			$html .='<br pagebreak="true"/>

			<table>
				<tr>
					<td style="font-size: 12px;" colspan="3" align="left" valign="bottom"><strong text-align: justify>4. DECLARACIÓN DEL PACIENTE </strong></td>
				</tr>
			</table>

			<table class="bordeCeldaGrande" cellspacing="5" border="1" width="100%" >
				<tr>
					<td style="" colspan="3" align="left">
						<table class="bordeCeldaGrande" cellpadding="3" border="1" width="100%">
							<tr>
								<td>
									<p style="font-size: 14px; text-align: justify; ">
										He conocido y comprendido satisfactoriamente los propósitos de este procedimiento y/o intervención
										y sus riesgos. Se me ha permitido hacer todas las observaciones y preguntas aclaratorias.
										Si se presentara algún imprevisto en mi tratamiento, el equipo médico podrá realizar tratamientos
										adicionales o variar técnica quirúrgica prevista de antemano.
										Por ello manifiesto que estoy satisfecho(a) con la información recibida y que comprendo la 
										explicación del alcance y riesgos de mi tratamiento.<br>
										Marque con una cruz su decisión:<br>
										En tales condiciones:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SÍ____ Acepto&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NO____Acepto<br><br>
										Se me ha informado que, en cualquier momento, puedo revocar este Consentimiento, siendo 
										informado de las posibles consecuencias para mi estado de salud y que no perderé los beneficios 
										que tengo derecho, haciéndome responsable de esa decisión.
									</p>

									<br>

									<table class="bordeCeldaGrande" cellpadding="3" border="1" width="100%">
										<tr style="line-height: 10px;">
											<td width="17%" style="font-size: 12px;"><strong>Firma Paciente</strong></td>
											<td width="83%"></td>
										</tr>
										<tr>
											<td colspan="2" style="font-size: 12px;">
												Nombre, Firma y RUT del Representante Legal <label style="color:#9c9c9c;">(Si corresponde)</label>
											</td>
										</tr>

										<tr style="line-height: 10px;">
											<td colspan="2" style="font-size: 12px;">
												&nbsp;
											</td>
										</tr>

									</table>
								</td>								
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<br>

			<table>
				<tr>
					<td style="font-size: 12px;" colspan="3" align="left" valign="bottom"><strong text-align: justify>5. REVOCACIÓN DEL CONSENTIMIENTO INFORMADO </strong></td>
				</tr>
			</table>

			<table class="bordeCeldaGrande" cellspacing="5" border="1" width="100%" >
				<tr>
					<td style="" colspan="3" align="left">
						<table class="bordeCeldaGrande" cellpadding="3" border="1" width="100%">
							<tr>
								<td>
									<p style="font-size: 14px; text-align: justify; ">
										Habiendo consentido anteriormente el procedimiento  o intervención que se señala y 
										siendo debidamente informado de la enfermedad que me aqueja y su manejo, en este 
										acto revoco mi autorización y rechazo expresamente el procedimiento, exámenes, 
										operación o tratamiento indicado, asumiendo plenamente toda la responsabilidad de 
										las consecuencias que se deriven de ello.
									</p>

									<br>

									<table class="bordeCeldaGrande" cellpadding="3" border="1" width="100%">
										<tr style="line-height: 10px;">
											<td width="17%" style="font-size: 12px;"><strong>Firma Paciente</strong></td>
											<td width="83%"></td>
										</tr>
										<tr>
											<td colspan="2" style="font-size: 12px;">
												Nombre, Firma y RUT del Representante Legal <label style="color:#9c9c9c;">(Si corresponde)</label>
											</td>
										</tr>

										<tr style="line-height: 10px;">
											<td colspan="2" style="font-size: 12px;">
												&nbsp;
											</td>
										</tr>

									</table>

								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>';

			$html .='<br pagebreak="true"/>

			<table>
				<tr>
					<td style="font-size: 12px;" colspan="3" align="left" valign="bottom"><strong text-align: justify>6. REGISTRO DE CUESTIONARIO PARA USO DE MEDIO DE CONTRASTE YODADO (MCY) </strong></td>
				</tr>
			</table>

			<table class="bordeCeldaGrande" cellpadding="5" border="1" width="100%" >
				<tr>
					<td style="" colspan="3" align="left">
						<table class="bordeCeldaGrande" cellpadding="5" border="1" width="100%">
							<tr>
								<td width="5%" style="vertical-align:middle;"  >
									<table border="0">
										<tr>
											<td></td>
										</tr>
										<tr>											
											<td align="center">1</td>											
										</tr>
										<tr>											
											<td></td>											
										</tr>
									</table>								
								</td>

								<td width="39.7%" style="text-align: justify;">
									¿Paciente se ha debido realizar 
									anteriormente un examen 
									imagenológico con uso de Medio 
									de Contraste Yodado?
								</td>

								<td width="15.6%">
									<table border="0">											
										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
											
										<tr>
											<td>SI</td>											
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
											<td></td>
											<td>NO</td>
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
										</tr>

										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</table>
								</td>

								<td width="39.7%">
									
								</td>
							</tr>

							
							<tr>
								<td width="5%" style="vertical-align:middle;"  >
									<table border="0">
										<tr>
											<td></td>
										</tr>
										<tr>											
											<td align="center">2</td>											
										</tr>
										<tr>											
											<td></td>											
										</tr>
									</table>								
								</td>

								<td width="39.7%" style="text-align: justify; line-height: 12px;">
									¿Paciente presentó alguna reacción 
									alérgica con el Medio de Contraste 
									Yodado?
								</td>

								<td width="15.6%">
									<table border="0">											
										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
											
										<tr>
											<td>SI</td>											
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
											<td></td>
											<td>NO</td>
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
										</tr>

										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</table>
								</td>

								<td width="39.7%" style="text-align: justify;" rowspan="2">
									<strong>Premedicación Estándar:</strong><br>
									Indicar 30 mg de Prednisona 12 y 2 horas antes del examen (dosis total 60 mg).
									<br>
									<strong>Premedicación de Urgencia:</strong>
									Indicar 200 mg Hidrocortisona EV 4  hrs antes del examen más una ampolla de clorfenamina 1 hora antes del examen.

								</td>
							</tr>

							<tr>
								<td width="5%" style="vertical-align:middle;"  >
									<table border="0">
										<tr>
											<td></td>
										</tr>
										<tr>											
											<td align="center">3</td>											
										</tr>
										<tr>											
											<td></td>											
										</tr>
									</table>								
								</td>

								<td width="39.7%" style="text-align: justify; line-height: 14px;">
									¿El paciente es <strong>Asmático</strong>?
								</td>

								<td width="15.6%" style="line-height: 4px;">
									<table border="0">											
										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
											
										<tr>
											<td>SI</td>											
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
											<td></td>
											<td>NO</td>
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
										</tr>

										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</table>
								</td>
							</tr>

							<tr>
								<td width="5%" style="vertical-align:middle; "  >
									<table border="0">
										<tr>
											<td></td>
										</tr>
										<tr>											
											<td align="center">4</td>											
										</tr>
										<tr>											
											<td></td>											
										</tr>
									</table>								
								</td>

								<td width="39.7%" style="text-align: justify; line-height: 14px;">
									¿El paciente es <strong>Diabético</strong>?
								</td>

								<td width="15.6%">
									<table border="0">											
										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
											
										<tr>
											<td>SI</td>											
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
											<td></td>
											<td>NO</td>
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
										</tr>

										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</table>
								</td>

								<td width="39.7%" style="text-align: justify;" rowspan="2">
									<strong>Usuario de Metformina:</strong><br>
									Clearence de Creatinina >= 60, suspender medicamento al momento del examen y reanudar 48 hrs después.<br>
									Clearence de Creatinina < 60, suspender medicamento 48 hrs antes y 48 hrs después de la inyección del MCY. Reanudación Metformina, sujeto a evaluación médica de la Función Renal del paciente.
								</td>
							</tr>

							<tr>
								<td width="5%" style="vertical-align:middle;"  >
									<table border="0">
										<tr  style="line-height: 8px;">
											<td></td>
										</tr>
										<tr>											
											<td align="center">5</td>											
										</tr>
										<tr>											
											<td></td>											
										</tr>
									</table>								
								</td>

								<td width="39.7%" style="text-align: justify; line-height: 20px;" >
									¿El paciente toma <strong>Metformina</strong>?
								</td>

								<td width="15.6%">
									<table border="0">											
										<tr style="line-height: 8px;">
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
											
										<tr>
											<td>SI</td>											
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
											<td></td>
											<td>NO</td>
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
										</tr>

										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</table>
								</td>
							</tr>

							<tr>
								<td width="5%" style="vertical-align:middle;"  >
									<table border="0">
										<tr>
											<td></td>
										</tr>
										<tr>											
											<td align="center">6</td>											
										</tr>
										<tr>											
											<td></td>											
										</tr>
									</table>								
								</td>

								<td width="39.7%" style="text-align: justify; line-height: 13px;">
									¿El paciente es Hipertenso?
								</td>

								<td width="15.6%">
									<table border="0">											
										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
											
										<tr>
											<td>SI</td>											
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
											<td></td>
											<td>NO</td>
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
										</tr>

										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</table>
								</td>

								<td width="39.7%" style="text-align: justify;">
									Pacientes Hipertensos deben <strong>mantener tratamiento</strong> farmacológico
								</td>
							</tr>


							<tr>
								<td width="5%" style="vertical-align:middle;"  >
									<table border="0">
										<tr style="line-height: 8px;">
											<td></td>
										</tr>
										<tr>											
											<td align="center">7</td>											
										</tr>
										<tr>											
											<td></td>											
										</tr>
									</table>								
								</td>

								<td width="39.7%" style="text-align: justify; line-height: 20px;">
									¿El paciente tiene Miastenia Gravis?
								</td>

								<td width="15.6%">
									<table border="0">											
										<tr style="line-height: 8px;">
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
											
										<tr>
											<td>SI</td>											
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
											<td></td>
											<td>NO</td>
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
										</tr>

										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</table>
								</td>

								<td width="39.7%" style="text-align: justify;">
									Se puede administrar MCY, sin embargo es necesaria una evaluación basal cuidadosa por parte del médico tratante, quien deberá <strong>continuar su seguimiento en el tiempo</strong>, para evitar eventos adversos.
								</td>
							</tr>

							<tr>
								<td width="5%" style="vertical-align:middle;"  >
									<table border="0">
										<tr style="line-height: 12px;">
											<td></td>
										</tr>
										<tr>											
											<td align="center">8</td>											
										</tr>
										<tr>											
											<td></td>											
										</tr>
									</table>								
								</td>

								<td width="39.7%" style="text-align: justify; line-height: 26px;">
									¿Paciente actualmente tiene <strong>Hipertiroidismo</strong>?
								</td>

								<td width="15.6%">
									<table border="0">											
										<tr style="line-height: 11px;">
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
											
										<tr>
											<td>SI</td>											
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
											<td></td>
											<td>NO</td>
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
										</tr>

										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</table>
								</td>

								<td width="39.7%" style="text-align: justify;">
									Se debe evitar el uso de MCY. Evaluar examen alternativo. Si <strong>médico tratante</strong> insiste en continuar con la administración del MCY, deberá <strong>hacer seguimiento</strong> al paciente con <strong>pruebas tiroideas por 12 semanas posteriores</strong> a la exposición del MCY, para monitorizar posibles eventos adversos.
								</td>								
							</tr>

							<tr>
								<td width="5%" style="vertical-align:middle;"  >
									<table border="0">
										<tr>
											<td></td>
										</tr>
										<tr>											
											<td align="center">9</td>											
										</tr>
										<tr>											
											<td></td>											
										</tr>
									</table>								
								</td>

								<td width="39.7%" style="text-align: justify; line-height: 12px;">
									¿Solicitó examen de Creatinina y  Clearence de Creatinina?
								</td>

								<td width="15.6%">
									<table border="0">											
										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
											
										<tr>
											<td>SI</td>											
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
											<td></td>
											<td>NO</td>
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
										</tr>

										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</table>
								</td>

								<td width="39.7%" style="text-align: justify;">
									Valor de <strong>Clearence de Creatinina &#60; 60 </strong>, se debe indicar <strong>Protección Renal</strong>
								</td>
								
							</tr>

							<tr>
								<td width="5%" style="vertical-align:middle;"  >
									<table border="0">
										<tr style="line-height: 8px;">
											<td></td>
										</tr>
										<tr>											
											<td align="center">10</td>											
										</tr>
										<tr>											
											<td></td>											
										</tr>
									</table>								
								</td>

								<td width="39.7%" style="text-align: justify; line-height: 20px;">
									¿Indicó Protección Renal?
								</td>

								<td width="15.6%">
									<table border="0">											
										<tr style="line-height: 8px;">
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
											
										<tr>
											<td>SI</td>											
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
											<td></td>
											<td>NO</td>
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
										</tr>

										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</table>
								</td>

								<td width="39.7%" style="text-align: justify;">
									
									<strong>Protección Renal:</strong><br>
									suero fisiológico 100 cc/hora,  12 horas antes del examen y  12 horas después del examen.
									Se debe evaluar riesgo de sobrecarga de volumen caso a caso, por Médico Tratante.
								</td>

							</tr>

							<tr>
								<td width="5%" style="vertical-align:middle;"  >
									<table border="0">
										<tr>
											<td></td>
										</tr>
										<tr>											
											<td align="center">11</td>											
										</tr>
										<tr>											
											<td></td>											
										</tr>
									</table>								
								</td>

								<td width="39.7%" style="text-align: justify; line-height: 13px;">
									¿Paciente Embarazada?
								</td>

								<td width="15.6%">
									<table border="0">											
										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
											
										<tr>
											<td>SI</td>											
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
											<td></td>
											<td>NO</td>
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
										</tr>

										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</table>
								</td>

								<td width="39.7%" style="text-align: justify;">									
									En caso de duda, se debe realizar b-hCG
								</td>

							</tr>

							<tr>
								<td width="5%" style="vertical-align:middle;"  >
									<table border="0">
										<tr>
											<td></td>
										</tr>
										<tr>											
											<td align="center">12</td>											
										</tr>
										<tr>											
											<td></td>											
										</tr>
									</table>								
								</td>

								<td width="39.7%" style="text-align: justify; line-height: 13px;">
									¿Paciente dando Lactancia?
								</td>

								<td width="15.6%">
									<table border="0">											
										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
											
										<tr>
											<td>SI</td>											
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
											<td></td>
											<td>NO</td>
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
										</tr>

										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</table>
								</td>

								<td width="39.7%" style="text-align: justify;">									
									Se puede desechar la leche según preferencia o criterio de la madre.
								</td>							
							</tr>

							<tr>
								<td width="5%" style="vertical-align:middle;"  >
									<table border="0">
										<tr>
											<td></td>
										</tr>
										<tr>											
											<td align="center">13</td>											
										</tr>
										<tr>											
											<td></td>											
										</tr>
									</table>								
								</td>

								<td width="39.7%" style="text-align: justify; line-height: 12px;">
									¿Paciente será sometido a algún tratamiento con Yodo Radioactivo, en el futuro próximo?
								</td>

								<td width="15.6%">
									<table border="0">											
										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
											
										<tr>
											<td>SI</td>											
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
											<td></td>
											<td>NO</td>
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
										</tr>

										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</table>
								</td>

								<td width="39.7%" style="text-align: justify;">									
									Se recomienda <strong>esperar 6 semanas de lavado</strong> antes de comenzar con el tratamiento con Yodo Radioactivo.
								</td>
							</tr>

							<tr>
								<td width="5%" style="vertical-align:middle;"  >
									<table border="0">
										<tr>
											<td></td>
										</tr>
										<tr>											
											<td align="center">14</td>											
										</tr>
										<tr>											
											<td></td>											
										</tr>
									</table>								
								</td>

								<td width="39.7%" style="text-align: justify; line-height: 14px;">
									¿Paciente tiene <strong>4 horas de ayuno</strong>?
								</td>

								<td width="15.6%">
									<table border="0">											
										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
											
										<tr>
											<td>SI</td>											
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
											<td></td>
											<td>NO</td>
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
										</tr>

										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</table>
								</td>


								<td width="39.7%" style="text-align: justify;">									

								</td>

							</tr>

							<tr>
								<td width="5%" style="vertical-align:middle;"  >
									<table border="0">
										<tr>
											<td></td>
										</tr>
										<tr>											
											<td align="center">15</td>											
										</tr>
										<tr>											
											<td></td>											
										</tr>
									</table>								
								</td>

								<td width="39.7%" style="text-align: justify; justify; line-height: 12px;">
									<strong>¿El paciente se encuentra en riesgo vital y necesita el examen?</strong>
								</td>

								<td width="15.6%">
									<table border="0">											
										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
											
										<tr>
											<td>SI</td>											
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
											<td></td>
											<td>NO</td>
											<td style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
										</tr>

										<tr>
											<td></td>											
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</table>
								</td>

								<td width="39.7%" style="text-align: justify;">									
									Riesgo de muerte o de secuela funcional grave.
									Se podrán omitir todas las medidas de prevención de Eventos Adversos Asociados a la Inyección del MCY. 
								</td>

							</tr>

							<tr>
								<td colspan="3" style="line-height: 12px;"></td>
								<td colspan="2"></td>
							</tr>

							<tr>
								<td colspan="3" align="center"><strong>Nombre y Apellido de Médico Solicitante</strong></td>
								<td colspan="2" align="center"><strong>Firma</strong></td>
							</tr>



						</table>
					</td>
				</tr>
			</table>

			';

			$html .='<br pagebreak="true"/>

			<table>
				<tr>
					<td style="font-size: 12px;" colspan="3" align="left" valign="bottom"><strong text-align: justify>7. REGISTRO DEL TECNOLOGO MEDICO </strong></td>
				</tr>
			</table>

			<table class="bordeCeldaGrande" cellpadding="5" border="1" width="100%" >
				<tr>
					<td style="" colspan="3" align="left">
						<table class="bordeCeldaGrande" cellpadding="5" border="1" width="100%">
							<tr>
								<td  align="center" width="14.3%" style="font-size: 12px;">
									Creatinina
									Fecha Examen
								</td>

								<td >
									
								</td>

								<td  align="center">
									<table border="0" width="100%">
										<tr style="line-height: 2px;">
											<td></td>
										</tr>

										<tr>
											<td style="font-size: 12px;">Clearence</td>
										</tr>

										<tr style="line-height: 1px;">
											<td></td>
										</tr>
									</table>
									
								</td>

								<td >
									
								</td>

								<td  align="center" style="font-size: 12px;">
									Presión<br>
									Arterial
								</td>

								<td >
									
								</td>

								<td  align="center"  width="10.6%" style="font-size: 12px;">
									Horas
									de
									Ayuno
								</td>

								<td >
									
								</td>
							</tr>
						</table>

						<table class="bordeCeldaGrande" cellpadding="5" border="1" width="100%">
							<tr>
								<td rowspan="3" align="center" style="font-size: 12px;">Premedicación</td>
								<td width="5%"></td>
								<td colspan="2" width="27.8%" style="font-size: 12px;">Estándar</td>
								<td rowspan="3" align="center" width="20%" style="font-size: 12px;">Protección<br>Renal</td>
								<td width="5%"></td>
								<td width="27.8%" style="font-size: 12px;">Estándar</td>
							</tr>
				            <tr>
				                <td></td>
				                <td  colspan="2" style="font-size: 12px;">De Urgencia</td>
				                <td></td>
				                <td>Otra</td>
				            </tr>
				            <tr>
				                <td></td>
				                <td>Otra</td>
				                <td></td>
				                <td colspan="2"></td>
				            </tr>
						</table>

						<table class="bordeCeldaGrande" cellpadding="5" border="1" width="100%">
							<tr>
								<td align="center" width="14.3%" style="font-size: 12px;">Medio de<br>Contraste</td>
								<td width="32.8%"></td>
								<td  width="20%" style="font-size: 12px;">Dosis Inyectada</td>
								<td width="32.8%"></td>
							</tr>

							<tr>
								<td align="center" width="14.3%" style="font-size: 12px;">Contraste Oral</td>
								<td></td>
								<td style="font-size: 12px;">Otros<br> Medicamentos</td>
								<td></td>
							</tr>

							<tr>
								<td colspan="2" style="line-height: 12px;"></td>
								<td colspan="2"></td>
							</tr>

							<tr>
								<td colspan="2" align="center" style="font-size: 12px;">Nombre, Apellido y Firma de Tecnólogo Médico que realiza el procedimiento</td>
								<td colspan="2" align="center" style="font-size: 12px;">Fecha del Examen</td>
							</tr>

						</table>

						<br>

						<table class="bordeCeldaGrande" cellpadding="5" border="0" width="100%">
							<tr>
								<td width="100%" style="font-size: 12px;" colspan="3" align="left" valign="bottom"><strong text-align: justify>Observaciones </strong></td>
							</tr>

							<tr style="line-height: 170px;">
								<td width="100%" style="border-bottom-width:1px; border-left-width:1px; border-right-width:1px; border-top-width:1px"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>



			';
	}
	$pdf->AddPage();
	$pdf->writeHTML($html, true, false, true, false, '');
	// $pdf->writeHTML($html, true, 0, true, 0);
	$pdf->Output('registro.pdf','I');
	//$url = "/pruebaxD/salidas/generarVoucher.pdf";

?>