<?php
session_start();

ini_set('max_execution_time', 600);
ini_set('memory_limit', '128M');

require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php'); 		$objCon 			= new Connection;$objCon->db_connect();
require_once("../../../../class/Util.class.php");          		$objUtil    		= new Util;
require_once('../../../../class/RegistroClinico.class.php'); 	$registroClinico 	= new RegistroClinico;

$datos_contendidoCargadoCarroTratamiento  	= $_SESSION['indicaciones']['tratamiento'];
$datos_contendidoCargadoCarroTratamiento 	= json_decode(stripslashes($datos_contendidoCargadoCarroTratamiento));

$clasificacionTratamiento 					= $registroClinico->obtenerClasificacionesTratamiento($objCon);

$version        = $objUtil->versionJS();
?>



<!-- 
################################################################################################################################################
                                                                    ARCHIVO JS
-->
<script type="text/javascript" src="<?=PATH?>/controllers/client/rce/indicaciones/tratamientoNuevo.js?v=<?=$version;?>1"></script>



<br>
<!-- 
################################################################################################################################################
                                                        DESPLIEGUE CAMPOS TRATAMIENTO
-->
<div id="contenidoTratamientoNuevo2" class="mr-2 ml-2">
	<form>
		<div class="panel panel-default" style="margin-bottom: 0px;">
			<div class="bd-callout bd-callout-warning border-bottom">
				<h6 id="ensure-correct-role-and-provide-a-label" style=" border-radius: inherit; font-size:15px;"> <svg class="svg-inline--fa fa-circle-notch fa-w-16 text-danger mr-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle-notch" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M288 39.056v16.659c0 10.804 7.281 20.159 17.686 23.066C383.204 100.434 440 171.518 440 256c0 101.689-82.295 184-184 184-101.689 0-184-82.295-184-184 0-84.47 56.786-155.564 134.312-177.219C216.719 75.874 224 66.517 224 55.712V39.064c0-15.709-14.834-27.153-30.046-23.234C86.603 43.482 7.394 141.206 8.003 257.332c.72 137.052 111.477 246.956 248.531 246.667C393.255 503.711 504 392.788 504 256c0-115.633-79.14-212.779-186.211-240.236C302.678 11.889 288 23.456 288 39.056z"></path></svg><!-- <i class="fas fa-circle-notch text-danger mr-2"></i> -->Tratamiento</h6>
			</div>

<!-- <div id="contenidoTratamientoNuevo2"> -->
	<!-- 
	**************************************************************************
							    FORMULARIO
	**************************************************************************
	-->
	<!-- <form> -->

		<!-- <div class="panel panel-default" style="margin-bottom: 0px;">

			<div class="panel-heading" style="height: 30px; background-color: #337ab7 !important;">

				<label style="position: relative; color: #ffffff;">Tratamiento</label>

			</div> -->
			<div class="panel-body mt-3" >
				<!-- 
				**************************************************************************
										DESCRIPCIÓN OTROS
				**************************************************************************
				-->				
				<div class="row m-2">
					<!-- Ingreso descripción --> 
					<div class="col-md-8">
						<label class="encabezado">Descripcion</label>
						<textarea class="form-control form-control-sm mifuente" id="frm_tratamientoNuevo" name="frm_tratamientoNuevo" placeholder="Describa Indicación"></textarea>
					</div>

			<!-- <div class="panel-body" style="margin: 2px;"> -->

				<!-- 
				**************************************************************************
											DESCRIPCIÓN
				**************************************************************************
				-->
				<!-- <div class="row"> -->

					<!-- Descripción -->
					<!-- <div class="col-md-4"> -->

						<!-- <label class="encabezado">Descripción</label><br> -->

						<!-- <textarea cols="10" rows="2" class="form-control" id="frm_tratamientoNuevo" name="frm_tratamientoNuevo" placeholder="Describa Indicación"></textarea> -->

					<!-- </div> -->

					<!-- Clasificación -->
					<div class="col-md-3">

						<label class="encabezado">Clasificación</label>
						
						<br>

						<div class="input-group">

							<!-- <span class="input-group-addon"><i class="fa fa-th-list" aria-hidden="true"></i></span>					 -->
							
							<select id="slc_clasificacionTratamiento" name="slc_clasificacionTratamiento" class="form-control  form-control-sm mifuente">
								<option value="0" selected disabled>Seleccione Clasificación</option>
								<?php 
								for ( $i = 0; $i < count($clasificacionTratamiento); $i++ ) { 
								?>
									<option value="<?php echo $clasificacionTratamiento[$i]['idClasificacion']; ?>" ><?php echo $clasificacionTratamiento[$i]['descripcionClasificacion']; ?></option>
								<?php 
								}
								?>
							</select>
						
						</div>

					</div>
					<div class="col-md-1">
						<label class="encabezado">&nbsp;</label>
						<button type="button" id="btn_add_row_tratamientoNuevo"  type="button" class="btn btn btn-sm btn-outline-primarydiag  mifuente col-lg-12 " alt="Agregar Examen" ><i class="fas fa-plus"></i></button>
					</div>

					<!-- Botón agregar descripción -->
					<!-- <div class="col-md-1">

						<br>
						
						<br>
						
						<button type="button" id="btn_add_row_tratamientoNuevo" class="btn btn-default" alt="Agregar Examen" title="Agregar Examen"><img src="<?=PATH?>/assets/img/DAU-06.png"></button>
					
					</div> -->
				
				</div>

				<!-- 
				**************************************************************************
										DESPLIEGUE DESCRIPCIÓN
				**************************************************************************
				-->				
				<div class="row mt-3 m-2">
					<div class="col-md-12">
						
						<table id="table_tratamientoNuevo" class="table table-sm table-striped table-hover" >
						
							<thead>
						
								<tr class="detalle">
						
									<th width="45%"  class="mifuente  text-center" >Examen</th>

									<th width="45%"  class="mifuente  text-center">Clasificación</th>
						
									<th width="6"  	 class="mifuente  text-center">Eliminar</th>
						
								</tr>
					
							</thead>
					
							<tbody id="contenidoTratamientoNuevo">
					
								<?php
								if ( ! empty($datos_contendidoCargadoCarroTratamiento[0]) ) { 
									for ( $i = 0; $i < count($datos_contendidoCargadoCarroTratamiento); $i++ ) {

										$datos_contendidoCargadoCarroTratamiento[$i][0] = str_replace('"', "'", $datos_contendidoCargadoCarroTratamiento[$i][0]);
									?>
										<tr id="<?=$datos_contendidoCargadoCarroTratamiento[$i][0]?>" class="detalle">
											
											<td class='frm_tratamientoNuevo_nombre my-1 py-1 mx-1 px-1 mifuente'><?php echo $datos_contendidoCargadoCarroTratamiento[$i][1]; ?></td>
											
											<td style="display:none" class='frm_idClasificacion my-1 py-1 mx-1 px-1 mifuente'><?php echo $datos_contendidoCargadoCarroTratamiento[$i][2]; ?></td>

											<td class='frm_clasificacionTratamiento my-1 py-1 mx-1 px-1 mifuente'><?php echo $datos_contendidoCargadoCarroTratamiento[$i][3]; ?></td>

											<td width="5%">

											<button type="button" id='eli<?=$datos_contendidoCargadoCarroTratamiento[$i][0]?>'  type="button" class="btn btn btn-sm btn-outline-danger  mifuente col-lg-12 eliminarTratamiento"  ><i class="fas fa-trash"></i></button>

											<!-- <button type='button' id="eli<?=$datos_contendidoCargadoCarroTratamiento[$i][0]?>"" class='btn btn-default eliminarTratamiento'><img src="<?=PATH?>/assets/img/DAU-16.png" class='puntero' /></button></td> -->
										
										</tr>
									<?php
									}
								
								}
								?>
							
							</tbody>
						
						</table>
					
					</div>
				
				</div>
			
			</div>

		</div>

	</form>

</div>