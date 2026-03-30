$html= '
<head>
<style type="text/css">
	thead { display: table-header-group }
	tfoot { display: table-row-group }
	tr { page-break-inside: avoid }
	.divAncho{
		width:1;
		}
	.enoform{
		border: 1px solid black;
		}
	.bordeCeldaGrande{
		border:0px solid white;
	}
	.bordeCompleto{
		border-bottom:1px solid black;
		border-left:1px solid black;
		border-right:1px solid black;
		border-top:1px solid black;
	}
	.bordeCelda{
		border-bottom:1px solid grey;
	}
	.enoformSin{
		border: 0px solid white;
		}
	.enoformSin td{
		border: 0px solid white;
		}
	hr{
	   height:2px;
	   border:none;
	 }
	.backBlue{
		background-color:#CCC;
		}
	.ultrachico{
		font-family:"SourceSansPro-Regular", Arial, Helvetica;
		font-size:7pt;
		}
	.superchico{
		font-family:"SourceSansPro-Regular", Arial, Helvetica;
		font-size:9pt;
		}
	.chico{
		font-family:"SourceSansPro-Regular", Arial, Helvetica;
		font-size:12pt;
		}
	p {
		line-height: 1.2;
		}
	.titulo {
		font-family:"SourceSansPro-Bold", Arial, Helvetica;
		font-size:12pt;
		}
	.simple {
		font-family:"SourceSansPro-Bold", Arial, Helvetica;
		font-size:12pt;
		font-weight:bold;}

</style>
</head>


<table class="bordeCeldaGrande" cellspacing="2" border="0" width="100%">
	<tr>
        <td>
            <table width="100%">
                <tr>
                    <td width="15%"><img src="/estandar/img/logo_gobierno_chile.jpg" width="55" height="55"></td>
					<td width="85%">
						<p class="titulo" align="center">Datos de Atención de Urgencia DAU
						<br>';
						if( $datos[0]['est_id'] != 4 && $datos[0]['est_id'] != 5 && $datos[0]['est_id'] != 6 && $datos[0]['est_id'] != 7 ){
							$html .= '<strong>Folio: '.strtoupper($datos[0]['dau_id']).' (ABIERTO)</strong>';
							$fecha = 'Fecha y Hora (Actual): '.$fechActual;
						} else if ($datos[0]['est_id'] == 4 || $datos[0]['est_id'] == 5 )  {
							$html .= '<strong>Folio: '.strtoupper($datos[0]['dau_id']).' (CERRADO)</strong>';
							$fecha = 'Fecha y Hora (Cierre): '.date("d-m-Y H:i:s",strtotime($datosDau[0]['dau_indicacion_egreso_fecha']));
						} else if ($datos[0]['est_id'] == 6 )  {
							$html .= '<strong>Folio: '.strtoupper($datos[0]['dau_id']).' (ANULADO)</strong>';
							$fecha = 'Fecha y Hora (Cierre): '.date("d-m-Y H:i:s",strtotime($datosDau[0]['dau_cierre_fecha_final']));
						} else if ($datos[0]['est_id'] == 7 )  {
							$html .= '<strong>Folio: '.strtoupper($datos[0]['dau_id']).' (N.E.A.)</strong>';
							$fecha = 'Fecha y Hora (Cierre): '.date("d-m-Y H:i:s",strtotime($datosDau[0]['dau_cierre_fecha_final']));
						}
						$html .= '
						<br>
						<small>Cuenta Corriente: '.strtoupper($datos[0]['idctacte']).'</small>
						<br>
						<small>Fecha y Hora (Admisión): '.date("d-m-Y H:i:s", strtotime($datosDau[0]['dau_admision_fecha'])).'</small>
						<br>
						<small>'.$fecha.'</small></p>
					</td>
                </tr>
            </table>
        </td>

    </tr>
    <tr>
        <td class="enoform">
        	<table class="enoformSin chico" cellspacing="0" border="0" width="100%">
            	<tr>
                	<td width="50%">
						<table class="enoformSin chico" cellspacing="0" border="0" width="100%">
							<tr>
								<td><strong>Datos del Paciente</strong></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Nombre Completo:</small></td>
                                <td width="65%"><small>'.strtoupper($datos[0]['nombres']).' '.strtoupper($datos[0]['apellidopat']).' '.strtoupper($datos[0]['apellidomat']).'</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Rut, Pasaporte u Otro:</small></td>
                                <td width="65%"><small>'.$rut.'</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Fecha de Nacimiento:</small></td>
                                <td width="65%"><small>'.strtoupper(date("d-m-Y",strtotime($datos[0]['fechanac']))).'</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Nacionalidad:</small></td>
								<td width="65%">';
									if($datos[0]['NACdescripcion'] == ""){
										$html .= '<small>'.strtoupper($datos[0]['nacionalidad']).'</small>';
									} else {
										$html .= '<small>'.strtoupper($datos[0]['NACdescripcion']).'</small>';
									}
								$html .= '
								</td>
							</tr>
							<tr>
                                <td width="35%" ><small>País de Nacimiento:</small></td>
								<td width="65%">';
									if($datos[0]['NACpais'] == ""){
										$html .= '<small>'.strtoupper('No Informada').'</small>';
									} else {
										$html .= '<small>'.strtoupper($datos[0]['NACpais']).'</small>';
									}
								$html .= '
								</td>
							</tr>
							<tr>
                                <td width="35%" ><small>Región:</small></td>
								<td width="65%">';
									if($datos[0]['REG_Descripcion'] == ""){
										$html .= '<small>'.strtoupper('No Informada').'</small>';
									} else {
										$html .= '<small>'.strtoupper($datos[0]['REG_Descripcion']).'</small>';
									}
								$html .= '
								</td>
							</tr>
							<tr>
                                <td width="35%" ><small>Ciudad:</small></td>
								<td width="65%">';
									if($datos[0]['CIU_Descripcion'] == ""){
										$html .= '<small>'.strtoupper('No Informada').'</small>';
									} else {
										$html .= '<small>'.strtoupper($datos[0]['CIU_Descripcion']).'</small>';
									}
								$html .= '
								</td>
							</tr>
							<tr>
                                <td width="35%" ><small>Comuna:</small></td>
								<td width="65%">';
									if($datos[0]['comuna'] == ""){
										$html .= '<small>'.strtoupper('No Informada').'</small>';
									} else {
										$html .= '<small>'.strtoupper($datos[0]['comuna']).'</small>';
									}
								$html .= '
								</td>
							</tr>
							<tr>
                                <td width="35%" ><small>Dirección:</small></td>
								<td width="65%"><small>'.strtoupper($datos[0]['direccion']).'</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Sector:</small></td>
								<td width="65%">';
									if($datos[0]['dau_paciente_domicilio_tipo'] == "R"){
										$html .= '<small>'.strtoupper('Rural').'</small>';
									} else if ($datos[0]['dau_paciente_domicilio_tipo'] == "U"){
										$html .= '<small>'.strtoupper('Urbano').'</small>';
									}
								$html .= '
								</td>
							</tr>
							<tr>
                                <td width="35%" ><small>Teléfonos:</small></td>
								<td width="65%">';
								if($datos[0]['PACfono']==0){
									$html .= "<small>FIJO NO DEFINIDO</small>";
								} else {
									$html .= "<small>".$datos[0]['PACfono']."</small>";
								}

								$html .= ', ';

								if($datos[0]['fono1']==0){
									$html .= " <small>CELULAR NO DEFINIDO</small>";
								} else {
									$html .= "<small>".$datos[0]['fono1']."</small>";
								}
								$html .= '
								</td>
							</tr>
							<tr>
                                <td width="35%" ><small>Consultorio:</small></td>
                                <td width="65%"><small>'.strtoupper($datos[0]['con_descripcion']).'</small></td>
							</tr>
                        </table>
					</td>
					<td width="50%">
							<table class="enoformSin chico" cellspacing="0" border="0" width="100%">
							<tr>
								<td>&nbsp;</td>
							</tr>
							<tr>
                                <td width="35%" ><small>Lugar de Accidente:</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>N. Acompañante:</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Motivo de consulta:</small></td>
                                <td width="65%"><small>'.strtoupper($datos[0]['mot_descripcion']).' - '.strtoupper($datos[0]['dau_motivo_descripcion']).''.$manifestaciones.'</small></td>
							</tr>';
								if($datos[0]['mor_descripcion']!=''){
									$html .= '<tr>';
									$html .= "<td><small><strong>MORDEDURA:</strong></small></td>";
									$html .= "<td><small><strong>".$datos[0]['mor_descripcion']."</strong></small></td>";
									$html .= '</tr>';
								}
								if($datos[0]['int_descripcion']!=''){
									$html .= '<tr>';
									$html .= "<td><small><strong>INTOXICACIÓN:</strong></small></td>";
									$html .= "<td><small><strong>".$datos[0]['int_descripcion']."</strong></small></td>";
									$html .= '</tr>';
								}
								if($datos[0]['que_descripcion']!=''){
									$html .= '<tr>';
									$html .= "<td><small><strong>QUEMADO:</strong></small></td>";
									$html .= "<td><small><strong>".$datos[0]['que_descripcion']."</strong></small></td>";
									$html .= '</tr>';
								}
							$html .= '
							<tr>
                                <td width="35%" ><small>Edad:</small></td>
                                <td width="65%"><small>'.$datos[0]['dau_paciente_edad'].'</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Etnia:</small></td>
                                <td width="65%"><small>'.strtoupper($datos[0]['etn_descripcion']).'</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Afrodescendiente:</small></td>
								<td width="65%"><small>';
								if($datos[0]['PACafro'] == 0){
									$html .= 'No';
								} else {
									$html .= 'Si';
								}
								$html .= '
									</small>
								</td>
							</tr>
							<tr>
                                <td width="35%" ><small>Sexo:</small></td>
								<td width="65%">';
								if($datos[0]['sexo']=='M'){
									$html .= "<small>Masculino</small>";
								} else if ($datos[0]['sexo']=='F') {
									$html .= '<small>Femenino</small>';
								}
								$html .= '
								</td>
							</tr>
							<tr>
                                <td width="35%" ><small>Medio de Transporte:</small></td>
                                <td width="65%"><small>'.strtoupper($datos[0]['med_descripcion']).'</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Tipo de Atención:</small></td>
                                <td width="65%"><small>'.strtoupper($datos[0]['ate_descripcion']).'</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Previsión:</small></td>
                                <td width="65%"><small>'.strtoupper($datos[0]['prevision']).'</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Forma de Pago:</small></td>
                                <td width="65%"><small>'.strtoupper($datos[0]['instNombre']).'</small></td>
							</tr>
							<tr>
                                <td width="35%" ><small>Categorización:</small></td>
                                <td width="65%">';
									if ($datosDau[0]['dau_categorizacion'] == "ESI-1") {
										$cate = "C1";
									}elseif ($datosDau[0]['dau_categorizacion'] == "ESI-2") {
										$cate = "C2";
									}elseif ($datosDau[0]['dau_categorizacion'] == "ESI-3") {
										$cate = "C3";
									}elseif ($datosDau[0]['dau_categorizacion'] == "ESI-4") {
										$cate = "C4";
									}elseif ($datosDau[0]['dau_categorizacion'] == "ESI-5") {
										$cate = "C5";
									}
									else{
										$cate = $datosDau[0]['dau_categorizacion'];
									}

									if ($datosDau[0]['dau_categorizacion_fecha'] == "") {
										$fechaCategorizacion = '------';
									} else {
										$fechaCategorizacion = date("d-m-Y",strtotime($datosDau[0]['dau_categorizacion_fecha']));
									}

									if ($datosDau[0]['dau_categorizacion_fecha'] == "") {
										$horaCategorizacion = '------';
									} else {
										$horaCategorizacion = date("H:m:i",strtotime($datosDau[0]['dau_categorizacion_fecha']));
									}


								$html .= '<small>'.$cate.' ( '.$fechaCategorizacion.' '.$horaCategorizacion.') (Usuario: '.ucwords(mb_strtolower($datosDau[0]['usuarioCategoriza'], "UTF-8")).')</small>
								</td>
							</tr>
						</table>
					</td>
                </tr>
            </table>
        </td>
	</tr>

	<tr>
        <td class="enoform">
        	<table class="enoformSin chico" cellspacing="0" border="0" width="100%">
				<tr>
					<td width="25%">
						<table class="enoformSin chico" cellspacing="2" border="0" width="100%">
							<tr>
								<td ><small>Alcoholemia:</small></td>
								<td>';
								if(is_null($datosDau[0]['dau_alcoholemia_fecha']) && empty($datosDau[0]['dau_alcoholemia_fecha'])){
									$html .=  "<small>No</small>";
								} else {
									$html .= "<small>Si</small>";
								}
								$html .= '</td>
							</tr>
						</table>
					</td>

					<td width="15%">
						<table class="enoformSin chico" cellspacing="2" border="0" width="100%">
							<tr>
                                <td><small>Auge:</small></td>
                                <td>';
									if($datosDau[0]['dau_cierre_auge'] == "S"){
										$html .= '<small>Si</small>';
									} else {
										$html .= '<small>No</small>';;
									}
								$html .= '
								</td>
							</tr>
						</table>
					</td>

					<td width="20%">
						<table class="enoformSin chico" cellspacing="2" border="0" width="100%">
							<tr>
								<td><small>Pertinente:</small></td>
								<td>';
								if($datosDau[0]['dau_cierre_pertinencia'] == 'N' || $datosDau[0]['dau_cierre_pertinencia'] == NULL || $datosDau[0]['dau_cierre_pertinencia'] == ''){
									$html .= "<small>No</small>";
								} else {
									$html .= "<small>Si</small>";
								}
								$html .= '
								</td>
							</tr>
						</table>
					</td>

					<td width="20%">
						<table class="enoformSin chico" cellspacing="2" border="0" width="100%">
							<tr>
								<td><small>Postinor:</small></td>
								<td>';
								if($datosDau[0]['dau_cierre_entrega_postinor'] == 'N' || $datosDau[0]['dau_cierre_entrega_postinor'] == NULL || $datosDau[0]['dau_cierre_entrega_postinor'] == '' ){
									$html .= "<small>No</small>";
								} else {
									$html .= "<small>Si</small>";
								}
								$html .= '
								</td>
							</tr>
						</table>
					</td>

					<td width="20%">
						<table class="enoformSin chico" cellspacing="2" border="0" width="100%">
							<tr>
								<td><small>Hepatitis B:</small></td>
								<td>';
								if($datosDau[0]['dau_cierre_hepatitisB'] == 'N' || $datosDau[0]['dau_cierre_hepatitisB'] == NULL || $datosDau[0]['dau_cierre_hepatitisB'] == '' ){
									$html .= "<small>No</small>";
								} else {
									$html .= "<small>Si</small>";
								}
								$html .= '
								</td>
							</tr>
						</table>
					</td>
				</tr>';

				if ( !is_null($datosDau[0]['dau_alcoholemia_fecha']) || !empty($datosDau[0]['dau_alcoholemia_fecha'])){

					$html .= '
					<tr>
						<td width="25%">
							<table class="enoformSin chico" cellspacing="2" border="0" width="100%">
								<tr>
									<td><small>Estado Etílico: '.$datosDau[0]['eti_descripcion'].'</small></td>
								</tr>
							</table>
						</td>

						<td width="25%">
							<table class="enoformSin chico" cellspacing="2" border="0" width="100%">
								<tr>
									<td><small>N° de Frasco: '.$datosDau[0]['dau_alcoholemia_numero_frasco'].'</small></td>
								</tr>
							</table>
						</td>

						<td width="50%">
							<table class="enoformSin chico" cellspacing="2" border="0" width="100%">
								<tr>';

								if ($datosDau[0]['dau_alcoholemia_fecha'] == "") {
									$html .= '
										<td><small>Fecha: ------ </small></td>';
								} else {
									$html .= '
										<td><small>Fecha: '.date("d-m-Y H:i:s",strtotime($datosDau[0]['dau_alcoholemia_fecha'])).'</small></td>';
								}

								$html.= '
								</tr>
							</table>
						</td>

					</tr>';
				}

				$html .= '

			</table>
		</td>
	</tr>

	<tr>
        <td class="enoform">
			<table class="enoformSin chico" cellspacing="0" border="0" width="100%">
				<tr>
					<td width="100%">
						<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
							<tr>
								<td><strong>Signos Vitales</strong></td>
							</tr>';

							if ( is_null($listarSignos[0]['SVITALfecha']) || empty($listarSignos[0]['SVITALfecha']) ) {
								$html .= '	<tr>
												<td width="100%"><small></small></td>
											</tr>';
							} else {

								$html .= '

								<tr>
									<td width="10%" align="center"><small><strong>Usuario</strong></small></td>
									<td width="10%" align="center"><small><strong>Fecha</strong></small></td>
									<td width="10%" align="center"><small><strong>P. Arteria</strong>l</small></td>
									<td width="8%" align="center"><small><strong>Pulso</strong></small></td>
									<td width="7%" align="center"><small><strong>FR</strong></small></td>
									<td width="7%" align="center"><small><strong>SaO2</strong></small></td>
									<td width="8%" align="center"><small><strong>Glasgow</strong></small></td>
									<td width="5%" align="center"><small><strong>Tº</strong></small></td>
									<td width="5%" align="center"><small><strong>Eva</strong></small></td>
									<td width="7%" align="center"><small><strong>H. Test</strong></small></td>
									<td width="7%" align="center"><small><strong>L.C.F.</strong></small></td>
									<td width="10%" align="center"><small><strong>RBNE</strong></small></td>
								</tr>';

								for ($r=0; $r<count($listarSignos) ; $r++) {
									$html .= '<tr>';

									$html.= '<td align="center"><small>'.ucwords(mb_strtolower($listarSignos[$r]['nombreusuario'], "UTF-8")).'</small></td>';

									if (is_null($listarSignos[$r]['SVITALfecha']) || empty($listarSignos[$r]['SVITALfecha']) ) {
										$html .= '<td align="center"><small></small></td>';
									} else {
										$html .= '<td align="center"><small>'.date("d-m-Y H:i:s",strtotime($listarSignos[$r]['SVITALfecha'])).'</small></td>';
									}

									if ($listarSignos[$r]['SVITALsistolica'] == "" && $listarSignos[$r]['SVITALdiastolica'] == "") {
										$html .= '<td align="center"><small></small></td>';
									}else{
										$html .= '<td align="center"><small>'.$listarSignos[$r]['SVITALsistolica'].' - '.$listarSignos[$r]['SVITALdiastolica'].'</small></td>';
									}

									$html .= '
										<td align="center"><small>'.$listarSignos[$r]['SVITALpulso'].'</small></td>
										<td align="center"><small>'.$listarSignos[$r]['SVITALfr'].'</small></td>
										<td align="center"><small>'.$listarSignos[$r]['SVITALsaturacion'].'</small></td>
										<td align="center"><small>'.$listarSignos[$r]['SVITALglasgow'].'</small></td>
										<td align="center"><small>'.$listarSignos[$r]['SVITALtemperatura'].'</small></td>
										<td align="center"><small>'.$listarSignos[$r]['SVITALeva'].'</small></td>
										<td align="center"><small>'.$listarSignos[$r]['SVITALHemoglucoTest'].'</small></td>
										<td align="center"><small>'.$listarSignos[$r]['SVITALfeto'].'</small></td>
										<td align="center"><small>'.$listarSignos[$r]['SVITALrbne'].'</small></td>
									</tr>';
								}
							}
							$html .= '
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td class="enoform">
			<table class="enoformSin chico" cellspacing="0" border="0" width="100%">
				<tr>
					<td width="50%">
						<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
							<tr>
								<td><strong>Antecedentes Mórbidos</strong></td>
							</tr>';

							if ($listarAnt[0]['pac_ant_descripcion'] == "") {
								$html .= '	<tr>
												<td><small></small></td>
											</tr>';
							} else {

								for ($n=0; $n<count($listarAnt); $n++) {
									$html .= '<tr>
												<td width="35%" ><small>'.$listarAnt[$n]['antDescripcion'].':</small></td>
												<td width="65%"><small>'.$listarAnt[$n]['pac_ant_descripcion'].'</small></td>
											</tr>';
								}
							}
						$html .= '
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td class="enoform">
			<table class="enoformSin chico" cellspacing="0" border="0" width="100%">
				<tr>
					<td >';
						for($i=0;$i<count($listadoIndicaciones);$i++) {

							if (  $listadoIndicaciones[$i]['descripcion'] == 'Solicitud Inicio Atención') {

								$usuarioInicioAtencion = $listadoIndicaciones[$i]['nombreUsuario'];

							}
						}
						$html .= '<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
							<tr>';
								$resRceDau[0]['regMotivoConsulta'] = str_replace("<", "&#60;", $resRceDau[0]['regMotivoConsulta']);
								$resRceDau[0]['regMotivoConsulta'] = str_replace("&#60;br>", "<br>", $resRceDau[0]['regMotivoConsulta']);

								$html .= '
								<td><strong>Motivo Consulta <small>(Usuario: '.ucwords(mb_strtolower($usuarioInicioAtencion, "UTF-8")).')</small></strong></td>
							</tr>
							<tr>
                                <td width="100%"><small>'.$resRceDau[0]['regMotivoConsulta'].'</small></td>
							</tr>
						</table>
					</td>
					</tr>
					<tr>
					<td >
						<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
							<tr>
								<td><strong>Hipótesis Diagnóstica Inicial <small>(Usuario: '.ucwords(mb_strtolower($usuarioInicioAtencion, "UTF-8")).')</small></strong></td>
							</tr>
							<tr>';
							$resRceDau[0]['regHipotesisInicial'] = str_replace("<", "&#60;", $resRceDau[0]['regHipotesisInicial']);
							$resRceDau[0]['regHipotesisInicial'] = str_replace("&#60;br>", "<br>", $resRceDau[0]['regHipotesisInicial']);

							$html .= '
                                <td height="auto" width="100%"><small>'.$resRceDau[0]['regHipotesisInicial'].'</small></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td class="enoform">
			<table class="enoformSin chico" cellspacing="0" border="0" width="100%">
				<thead>
				<tr>
					<td width="100%">
						<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
							<thead>
							<tr>
								<td><strong>Indicaciones Médicas</strong></td>
							</tr>
							<tr>
								<th width="12%" align="center"><small><strong>Indicación</strong></small></th>
								<th width="20%" align="center"><small><strong>Prestación</strong></small></th>
								<th width="10%" align="center"><small><strong>Estado</strong></small></th>
								<th width="13%" align="center"><small><strong>Solicitado</strong></small></th>
								<th width="13%" align="center"><small><strong>Inicio Ind.</strong></small></th>
								<th width="13%" align="center"><small><strong>Toma Muestra</strong></small></th>
								<th width="13%" align="center"><small><strong>Aplicado</strong></small></th>
							</tr>
							</thead>
							<tbody>';
							for($i=0;$i<count($listadoIndicaciones);$i++) {

								if ( $listadoIndicaciones[$i]['estado'] == 6 || $listadoIndicaciones[$i]['estado'] == 8 ) {

									continue;

								}

								array_push($usuariosIndicaciones, $listadoIndicaciones[$i]['usuarioInserta']);
								$tipoSolicitud = explode("Solicitud ", $listadoIndicaciones[$i]['descripcion']);

								if( $datos[0]['dau_atencion'] == 3 && $tipoSolicitud[1]  == 'Evolución') {

									continue;

								}

								$listadoIndicaciones[$i]['Prestacion'] = str_replace("<", "&#60;", $listadoIndicaciones[$i]['Prestacion']);
								$listadoIndicaciones[$i]['Prestacion'] = str_replace("&#60;br>", "<br>", $listadoIndicaciones[$i]['Prestacion']);

								$html .= '<tr>';

									if ( $listadoIndicaciones[$i]['servicio']==4 ) {
										$html .= '<td align="center"><small>Solicitud Otros</small></td>';
									} else {
										$html .= '<td align="center"><small>'.ucwords(mb_strtolower($tipoSolicitud[1], "UTF-8")).'</small></td>';
									}

									$html .= '<td align="center"><small>'.ucwords(mb_strtolower($listadoIndicaciones[$i]['Prestacion'], "UTF-8"));

									if ( ! empty($listadoIndicaciones[$i]['descripcionClasificacion']) && ! is_null($listadoIndicaciones[$i]['descripcionClasificacion'])) {
										$html .= '<br>';
										$html .= ' ( '.$listadoIndicaciones[$i]['descripcionClasificacion'].' )';
									}
									$html .= '</small></td>';

									$html .= '
										<td align="center"><small>'.$listadoIndicaciones[$i]['estadoDescripcion'].'</small></td>
										<td align="center"><small>'.ucwords(mb_strtolower($listadoIndicaciones[$i]['usuarioInserta'], "UTF-8")).'<br>'.existeFecha(date("d-m-Y H:i:s",strtotime($listadoIndicaciones[$i]['fechaInserta']))).'</small></td>
										<td align="center"><small>'.ucwords(mb_strtolower($listadoIndicaciones[$i]['UsuarioIniciaIndicacion'], "UTF-8")).'<br>'.existeFecha(date("d-m-Y H:i:s",strtotime($listadoIndicaciones[$i]['fechaIniciaIndicacion']))).'</small></td>
										<td align="center"><small>'.ucwords(mb_strtolower($listadoIndicaciones[$i]['usuarioTomaMuestra'], "UTF-8")).'<br>'.existeFecha(date("d-m-Y H:i:s",strtotime($listadoIndicaciones[$i]['fechaTomaMuestra']))).'</small></td>
										<td align="center"><small>'.ucwords(mb_strtolower($listadoIndicaciones[$i]['usuarioAplica'], "UTF-8")).'<br>'.existeFecha(date("d-m-Y H:i:s",strtotime($listadoIndicaciones[$i]['fechaAplica']))).'</small></td>
								</tr>';
							}
								$html .= '</tbody>
						</table>
					</td>
				</tr>
				</thead>
			</table>
		</td>
	</tr>';

	if ( $datos[0]['dau_atencion'] == 3 && ! empty($listarEvoluciones) && ! is_null($listarEvoluciones) ) {

		$html .= '
				<tr>
					<td class="enoform">
						<table class="enoformSin chico" cellspacing="0" border="0" width="100%">
							<tr>
								<td><strong>Evoluciones</strong></td>
							</tr>
							<tbody>';

							foreach ( $listarEvoluciones as $evolucion ) {

								$html .= '
										<tr>
											<td><small> - '.$evolucion['SEVOevolucion'].'</small></td>
										</tr>
										';
							}

		$html .= '
						</tbody>
						</table>
					</td>
				</tr>
				';

	}

	$html .= '
	<tr>
		<td class="enoform">
			<table class="enoformSin chico" cellspacing="0" border="0" width="100%">
				<tr>
					<td width="50%">
						<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
							<tr>
								<td><strong>Alta Médica</strong></td>
							</tr>
							<tr>';
								$usuarioMedicoTratante = $datosDau[0]['dau_inicio_atencion_usuario'];
								$datosUsuario 		   = $objUsuarios->obtenerDatosUsuario ( $objCon, $usuarioMedicoTratante );
								$medicoTratante        = $datosUsuario[0]['PROdescripcion'];

								$usuarioMedicoEgresa   = $datosDau[0]['dau_indicacion_egreso_usuario'];
								$datosUsuario 		   = $objUsuarios->obtenerDatosUsuario ( $objCon, $usuarioMedicoEgresa );
								$medicoEgresa          = $datosUsuario[0]['PROdescripcion'];

								$html.='
								<td width="100%"><small>Profesional Tratante: '.$medicoTratante.'</small></td>
								<td width="100%"><small>Profesional Egresa: '.$medicoEgresa.'</small></td>
							</tr>
							<tr>
								<td width="100%"><small>Destino: ';
									for ($w=0; $w<count($rsIndEgreso) ; $w++) {
										if( $obtenerIndicacionEgreso[0]['dau_indicacion_egreso'] == $rsIndEgreso[$w]['ind_egr_id'] ) {
											$html .= $rsIndEgreso[$w]['ind_egr_descripcion'];
										}
									}

									$html .= ' - ';

									switch ($obtenerIndicacionEgreso[0]['dau_indicacion_egreso']) {
										case '3':
											for ($g=0; $g<count($rsDerivacion);$g++) {
												if($obtenerIndicacionEgreso[0]['alt_der_id'] == $rsDerivacion[$g]['alt_der_id']){
													$html .= $rsDerivacion[$g]['alt_der_descripcion'];
												}
											}
											switch ($obtenerIndicacionEgreso[0]['alt_der_id']) {
												case '2':
													$html .= ' - ';
													$descripcionesEspecialidad = '';
													for ($esp=0; $esp<count($resEspecialidad); $esp++) {
														if ( strpos($obtenerIndicacionEgreso[0]['dau_ind_especialidad'], $resEspecialidad[$esp]['ESPcodigo']) !== false ){
															if ( empty($descripcionesEspecialidad) || is_null($descripcionesEspecialidad) ) {
																$descripcionesEspecialidad = $resEspecialidad[$esp]['ESPdescripcion'];
																continue;
															}
															$descripcionesEspecialidad = $descripcionesEspecialidad.'-'.$resEspecialidad[$esp]['ESPdescripcion'];
														}
													}
													$html .= $descripcionesEspecialidad;
													break;
												case '3':
													$html .= ' - ';
													for ($ap=0; $ap<count($rsAPS); $ap++){
														if($obtenerIndicacionEgreso[0]['dau_ind_aps'] == $rsAPS[$ap]['ESTAcodigo']){
															$html .= $rsAPS[$ap]['ESTAdescripcion'];
														}
													}
													break;
												case '5':
													$html .= ' - ';
													$html .= $obtenerIndicacionEgreso[0]['dau_ind_otros'];
													break;
											}
											break;
										case '4':
											for ($op=0; $op<count($ListarServiciosDau); $op++) {
												if($obtenerIndicacionEgreso[0]['dau_ind_servicio'] == $ListarServiciosDau[$op]['id']){
													$html .= $ListarServiciosDau[$op]['servicio'];
												}
											}
											break;
										case '5':
											break;
										case '6':
											if($obtenerIndicacionEgreso[0]['des_id'] == 7){
												$servicio_Destino = 'Anatomía Patológicas';
											}else if($obtenerIndicacionEgreso[0]['des_id'] == 8){
												$servicio_Destino = 'Servicio Médico Legal';
											}
											$html .= $servicio_Destino.' ('.$fecha_defuncion.')';
											break;
										case '7':
											break;

									}

									$dauPostIndicacionEgreso = $objDetalleDau->dauPostIndicacionEgreso($objCon, $datos[0]['dau_id']);

									if ( ! empty($dauPostIndicacionEgreso) && ! is_null($dauPostIndicacionEgreso) ) {

										$html .= " (Post Indicación Egreso: ".$dauPostIndicacionEgreso['descripcionPostIndicacionEgreso'].")";

									}

								$html .= '
								</small></td>
							</tr>
							<tr>
								<td><small>Pronóstico Médico Legal Provisorio: ';
								for ($q=0; $q<count($rsPronostico) ; $q++) {
									if($resRceDau[0]['PRONcodigo'] == $rsPronostico[$q]['PRONcodigo']){
										$html .= $rsPronostico[$q]['PRONdescripcion'];
									}
								}
								$html .= '
								</small></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
        <td class="enoform">
        	<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
				<tr>
					<td width="20%">
						<small>Violencia: ';
								if(is_null($registroViolencia) || empty($registroViolencia)){
									$html .=  "No";
								} else {
									$html .= "Si";
								}
					$html .= '</small>
					</td>';

					if ( ! empty($registroViolencia['descripcionTipoViolencia']) && ! is_null($registroViolencia['descripcionTipoViolencia']) ) {

						$html .= '
							<td width="20%">
								<small>Tipo: '.$registroViolencia['descripcionTipoViolencia'].'</small>
							</td>
							';

					}

				$html .= '</tr>

				<tr>';



				if ( ! empty($registroViolencia['descripcionTipoAgresor']) && ! is_null($registroViolencia['descripcionTipoAgresor']) ) {

					$html .= '
							<td width="23%">
								<small>Agresor: '.$registroViolencia['descripcionTipoAgresor'].'</small>
							</td>
							';

				}

				if ( ! empty($registroViolencia['descripcionLesionVictima']) && ! is_null($registroViolencia['descripcionLesionVictima']) ) {

					$html .= '
							<td width="20%">
								<small>Lesión: '.$registroViolencia['descripcionLesionVictima'].'</small>
							</td>
							';

				}

				if ( ! empty($registroViolencia['descripcionSospechaPenetracion']) && ! is_null($registroViolencia['descripcionSospechaPenetracion']) ) {

					$html .= '
							<td width="20%">
								<small>Penetración: '.$registroViolencia['descripcionSospechaPenetracion'].'</small>
							</td>
							';

				}

				if ( ! empty($registroViolencia['descripcionProfilaxis']) && ! is_null($registroViolencia['descripcionProfilaxis']) ) {

					$html .= '
							<td width="17%">
								<small>Profilaxis: '.$registroViolencia['descripcionProfilaxis'].'</small>
							</td>
							';

				}

				if ( ! empty($registroViolencia['victimaEmbarazada']) && ! is_null($registroViolencia['victimaEmbarazada']) ) {

					$html .= '
							<td width="17%">
								<small>Embarazada: '.( ($registroViolencia['victimaEmbarazada'] == 'S') ? 'Si' : 'No' ).'</small>
							</td>
							';

				}

				if ( ! empty($registroViolencia['peritoSexual']) && ! is_null($registroViolencia['peritoSexual']) ) {

					$html .= '
							<td width="20%">
								<small>Perito Sexual: '.$registroViolencia['peritoSexual'].'</small>
							</td>
							';

				}

				$html .= '
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td class="enoform">
			<table class="enoformSin chico" cellspacing="0" border="0" width="100%">
				<tr>
					<td width="50%">
						<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
							<tr>
								<td><strong>Hipótesis Final <small>(Usuario: '.ucwords(mb_strtolower($resRceDau[0]['nombreUsuario'], "UTF-8")).')</small></strong></td>
							</tr>';
							if ( $resRceDau[0]['regHipotesisFinal'] != '') {
								$html .= '
									<tr>
										<td width="100%"><small>'.$resRceDau[0]['regHipotesisFinal'].'</small></td>
									</tr>';
							}

							if ($resRceDau[0]['regCIE10Abierto'] != '' ) {

								$resRceDau[0]['regCIE10Abierto'] = str_replace("<", "&#60;", $resRceDau[0]['regCIE10Abierto']);
								$resRceDau[0]['regCIE10Abierto'] = str_replace("&#60;br>", "<br>", $resRceDau[0]['regCIE10Abierto']);

								$html .= '
									<tr>
										<td width="100%"><small>'.$resRceDau[0]['regCIE10Abierto'].'</small></td>
									</tr>';
							}
							$html .= '
						</table>
					</td>
					<td width="50%">
						<table class="enoformSin chico" cellspacing="5" border="0" width="100%">
							<tr>
								<td><strong>Receta / Indicaciones Alta Urgencia <small>(Usuario: '.ucwords(mb_strtolower($resRceDau[0]['nombreUsuario'], "UTF-8")).')</small></strong></td>
							</tr>
							<tr>';
								$resRceDau[0]['regIndicacionEgresoUrgencia'] = str_replace("<", "&#60;", $resRceDau[0]['regIndicacionEgresoUrgencia']);
								$resRceDau[0]['regIndicacionEgresoUrgencia'] = str_replace("&#60;br>", "<br>", $resRceDau[0]['regIndicacionEgresoUrgencia']);

								$html .= '
                                <td width="100%"><small>'.$resRceDau[0]['regIndicacionEgresoUrgencia'].'</small></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<br>
	<br>';

	if ( pacienteHospitalizadoEIsapre($datos[0]) ) {

		if( $datos[0]['est_id'] != 4 && $datos[0]['est_id'] != 5 && $datos[0]['est_id'] != 6 && $datos[0]['est_id'] != 7 ){
			$fechaMensaje = date('d-m-Y');
			$horaMensaje = date('H:i:s');
		} else if ($datos[0]['est_id'] == 4 || $datos[0]['est_id'] == 5 )  {
			$fechaMensaje = date("d-m-Y",strtotime($datosDau[0]['dau_indicacion_egreso_fecha']));
			$horaMensaje = date("H:i:s",strtotime($datosDau[0]['dau_indicacion_egreso_fecha']));
		} else if ($datos[0]['est_id'] == 6 )  {
			$fechaMensaje = date("d-m-Y",strtotime($datosDau[0]['dau_cierre_fecha_final']));
			$horaMensaje = date("H:i:s",strtotime($datosDau[0]['dau_cierre_fecha_final']));
		} else if ($datos[0]['est_id'] == 7 )  {
			$fechaMensaje = date("d-m-Y",strtotime($datosDau[0]['dau_cierre_fecha_final']));
			$horaMensaje = date("H:i:s",strtotime($datosDau[0]['dau_cierre_fecha_final']));
		}

		$html .= '
				<tr>
					<td class="enoform">
						<table class="enoformSin chico" cellspacing="0" border="0" width="100%" style="text-align:center;">
							<tr>
								<td width="100%">
									<strong><small>Siendo las '.$horaMensaje.' horas del dia '.$fechaMensaje.', el Medico Cirujano que suscribe, declara que, presenta una patología que le condiciona riesgo vital y/o riesgo de secuela funcional grave de no mediar tratamieto inmediato y, por lo tanto, se encuentra en la condición definida como Emergencia o Urgencia en ley 19.650 y por Decreto Supremo N° 896 del Ministerio de Salud</small></strong>
								</td>
							</tr>
						</table>
					</td>
				</tr>';


	}


	$html.=
	'<br>
	<br>
	<tr>
		<td>
			<table class="enoformSin chico" cellspacing="0" border="0" width="100%" style="text-align:right;">
				<tr>';

	$arrayUsuariosIndicaciones = array_values(array_unique($usuariosIndicaciones));

	for ( $i = 0; $i < count($arrayUsuariosIndicaciones); $i++) {

		$html .= '<td>';

		$usuarioIndicacion = $arrayUsuariosIndicaciones[$i];

		$resultadoUsuarioIndicaciones = $objUsuarios->obtenerDatosUsuario($objCon, $usuarioIndicacion);

		$URLUsuarioIndicaciones = "http://".IP."/firmaDigital/medicos/".$resultadoUsuarioIndicaciones[0]['PROcodigo'].".png";

		$file_headers_usuarioIndicaciones = @get_headers($URLUsuarioIndicaciones, 1);

		if($file_headers_usuarioIndicaciones[0] == 'HTTP/1.1 200 OK') {

			$html .= '
				<table style="margin-top:50px;">
					<tr style="text-align:center;">
						<td>
							<img id="'.$parametros['dau_id'].'" class="indicaciones" name="'.$parametros['dau_id'].'" src="http://'.IP.'/firmaDigital/medicos/'.$resultadoUsuarioIndicaciones[0]['PROcodigo'].'.png" style="width:150px; height:35px;">
						</td>
					</tr>
					<tr style="text-align:center;">
						<td>
							<small><strong>'.ucwords(mb_strtolower($resultadoUsuarioIndicaciones[0]['PROdescripcion'], "UTF-8")).'</strong></small>
						</td>
					</tr>
					<tr style="text-align:center;">
						<td>
							<small><strong>'.$objUtil->formatearNumero($resultadoUsuarioIndicaciones[0]['PROcodigo']).'-'.$objUtil->generaDigito($resultadoUsuarioIndicaciones[0]['PROcodigo']).'</strong></small>
						</td>
					</tr>
				</table>';

		} else if ( ! empty($resultadoUsuarioIndicaciones[0]['PROcodigo']) && ! is_null($resultadoUsuarioIndicaciones[0]['PROcodigo']) ) {
			$html .= '
				<br>
				<table style="margin-top:50px;">
					<tr style="text-align:center;">
						<td style="height:40px;"> </td>
					</tr>
					<tr style="text-align:center;">
						<td>
							<small><strong>'.ucwords(mb_strtolower($resultadoUsuarioIndicaciones[0]['PROdescripcion'], "UTF-8")).'</strong></small>
						</td>
					</tr>
					<tr style="text-align:center;">
						<td>
							<small><strong>'.$objUtil->formatearNumero($resultadoUsuarioIndicaciones[0]['PROcodigo']).'-'.$objUtil->generaDigito($resultadoUsuarioIndicaciones[0]['PROcodigo']).'</strong></small>
						</td>
					</tr>
				</table>';
		}

		$html .= '</td>';

	}

$html .= '
				</tr>
			</table>
		</td>
	</tr>
</table>
';