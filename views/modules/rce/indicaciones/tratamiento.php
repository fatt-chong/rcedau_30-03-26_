<?php
session_start();
error_reporting(0);
ini_set('max_execution_time', 600);
ini_set('memory_limit', '128M');

require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php'); 	 $objCon          = new Connection;$objCon->db_connect();
require_once("../../../../class/Util.class.php"); 		 	 $objUtil         = new Util;
require_once('../../../../class/Dau.class.php');			 $objDau          = new Dau;
require_once('../../../../class/RegistroClinico.class.php'); $RegistroClinico = new RegistroClinico;

$tipo_atencion 							  = $objDau -> obtenerTipoAtencionSegunDau( $objCon, $_GET['dau_id'] );
// echo $tipo_atencion['dau_atencion'];
// print('<pre>'); print_r($tipo_atencion);print ('</pre>'); 
$tratamiento            				  = $RegistroClinico->sensitivaTratamientos( $objCon, $tipo_atencion );

$datos_contendidoCargadoCarroProce  	  = $_SESSION['indicaciones']['procedimiento'];

if( ! empty($datos_contendidoCargadoCarroProce)) { 
	
	$datos_contendidoCargadoCarroProce  = explode(",",$datos_contendidoCargadoCarroProce);

}

$version = $objUtil->versionJS();
?>



<!-- 
################################################################################################################################################
                                                                    ARCHIVO JS
-->

<script type="text/javascript" src="<?=PATH?>/controllers/client/rce/indicaciones/tratamiento.js?v=<?=$version;?>1"></script>
<style type="text/css">
	.oculto{
		display:none !important;
	}
</style>


<br>
<script type="text/javascript">
    // $(document).ready(function() {
        // $('.select_buscador').select2();
    // });
</script>
<script type="text/javascript">
    // $(document).ready(function() {
        // $('.select_buscador2').multiselect();
    // });
</script>
<script>
  // $(document).ready(function() {
    // $('.select2').select2({
      // theme: 'bootstrap4', // Usa el tema compatible con Bootstrap 4
      // placeholder: "Selecciona una opción",
      // allowClear: true
    // });
  // });
</script>
<!-- <script>
    $(document).ready(function() {
        $('#exampleSelect').select2({
            theme: 'bootstrap4',
            placeholder: "Selecciona una opción", 
            dropdownParent: $('#modalContenido') // Asegura que el dropdown se muestre correctamente
      
            allowClear: true
        });
    });
</script> -->
<!--  <script>
    $('#modalContenido').on('shown.bs.modal', function () {
      $('#exampleSelect').select2({
        theme: 'bootstrap4',
            placeholder: "Selecciona una opción",
            allowClear: true,
        dropdownParent: $('#modalContenido') // Asegura que el dropdown se muestre correctamente
      });
    });

    $('#modalContenido').on('hidden.bs.modal', function () {
      $('#exampleSelect').select2('destroy'); // Limpia la instancia al cerrar el modal
    });
  </script> -->
<!-- <style>
  .select2-container {
      z-index: 999999999999 !important; /* Bootstrap modal tiene z-index 1040 o 1050 */
  }
  .select2-container--open {
    position: absolute !important;
    z-index: 2050 !important; /* Asegúrate de que esté por encima del modal */
	}
</style> -->
<script>
    $(document).ready(function() {
        $('#comboProcedimiento').select2({
            theme: 'bootstrap4',
            dropdownParent: $('#agregarExamen')
        });
    });
</script>
<!-- <div class="container mt-5">
    <label for="asdasdd">Selecciona una opción:</label>
    <select id="asdasdd" class="exampleSelect3232 form-control select2" style="width: 100%;">
        <option></option>
        <option value="1">Opción 1</option>
        <option value="2">Opción 2</option>
        <option value="3">Opción 3</option>
    </select>
</div> -->
<div id="contenidoOtros" class="mr-2 ml-2">

	<input type="hidden" id="idPaciente" name="idPaciente" value="<?=$_SESSION['datosPacienteDau']['id_paciente']?>">
	
	<input type="hidden" id="idDau" 	 name="idDau" 	   value="<?=$_SESSION['datosPacienteDau']['dau_id']?>">
	
	<form onsubmit="return false">
		<div class="panel panel-default" style="margin-bottom: 0px;">
			<div class="bd-callout bd-callout-warning border-bottom">
				<h6 id="ensure-correct-role-and-provide-a-label" style=" border-radius: inherit; font-size:15px;"> <svg class="svg-inline--fa fa-circle-notch fa-w-16 text-danger mr-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle-notch" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M288 39.056v16.659c0 10.804 7.281 20.159 17.686 23.066C383.204 100.434 440 171.518 440 256c0 101.689-82.295 184-184 184-101.689 0-184-82.295-184-184 0-84.47 56.786-155.564 134.312-177.219C216.719 75.874 224 66.517 224 55.712V39.064c0-15.709-14.834-27.153-30.046-23.234C86.603 43.482 7.394 141.206 8.003 257.332c.72 137.052 111.477 246.956 248.531 246.667C393.255 503.711 504 392.788 504 256c0-115.633-79.14-212.779-186.211-240.236C302.678 11.889 288 23.456 288 39.056z"></path></svg><!-- <i class="fas fa-circle-notch text-danger mr-2"></i> -->Descripcion del Procedimiento</h6>
			</div>
			<div class="panel-body mt-3" >


		<!-- <div class="panel panel-default" style="margin-bottom: 0px;">
		
			<div class="panel-heading" style="height: 30px; background-color: #337ab7 !important;">
				
				<label style="position: relative; color: #ffffff;">Descripcion del Procedimiento</label>
			
			</div> -->

			<div class="panel-body mt-3" >

				<!-- 
				**************************************************************************
										DESCRIPCIÓN PROCEDIMIENTO
				**************************************************************************
				-->
				<div class="row m-2"> 
					<!-- Select descripción -->         
					<!-- <select id="comboProcedimiento" name="comboProcedimiento" <?=$disabledAll;?> class="form-select select_buscador form-select-sm mifuente12 "  <?=$disabledInput;?> aria-label="Default select example ">
                            <option value="0" >Seleccione ...</option>
								<?php 
								for ( $i = 0; $i < count($tratamiento); $i++ ) { 
								?>
									<option value="<?=$tratamiento[$i]['preCod']?>" ><?=$tratamiento[$i]['preNombre']?></option>
								<?php 
								}
								?>
                        </select> -->

					<div class="col-md-11">
						<div class="input-group" style="border: 1px solid #ced4da;">	
							<!-- <select id="comboProcedimiento" name="comboProcedimiento" class="selectpicker mifuente col-lg-12" data-live-search="true">
								<option value="0" >Seleccione ...</option>
								<?php 
								for ( $i = 0; $i < count($tratamiento); $i++ ) { 
								?>
									<option value="<?=$tratamiento[$i]['preCod']?>" ><?=$tratamiento[$i]['preNombre']?></option>
								<?php 
								}
								?>
							</select> -->
							<select id="comboProcedimiento" name="comboProcedimiento" class="form-select select_buscador   form-select-sm mifuente  col-lg-12"  aria-label="Default select example "  <?=$disabledInput;?> style="width: 100%;">
                            <option value="0" >Seleccione ...</option>
								<?php 
								for ( $i = 0; $i < count($tratamiento); $i++ ) { 
								?>
									<option value="<?=$tratamiento[$i]['preCod']?>" ><?=$tratamiento[$i]['preNombre']?></option>
								<?php 
								}
								?>
                        </select>
						</div>
					</div>
					<!-- Botón Agregar -->
					<div class="col-md-1">
						<!-- <label class="encabezado">&nbsp;</label> -->
						<button type="button" id="agregar_row"  type="button" class="btn btn btn-sm btn-outline-primarydiag  mifuente col-lg-12 " alt="Agregar Examen" ><i class="fas fa-plus"></i></button>
						<!-- <button type="button" id="agregar_row" class="btn btn-default" alt="Agregar Examen" title="Agregar Examen" style="margin-top: -2px;"><img src="<?=PATH?>/assets/img/DAU-06.png"></button> -->
					</div>
				</div>
				<!-- 
				**************************************************************************
										DESPLIEGUE DESCRIPCIONES
				**************************************************************************
				-->		
				<div class="row mt-3 m-2">
					<div class="col-md-12">
						<table id="table_Tratamiento" class="table table-sm table-striped table-hover">
							<thead>
								<tr class="detalle">
									<th width="80%" class="mifuente  text-center">Examen</th>
									<th width="10%"> </th>
									<th width="10%" class="mifuente  text-center">Eliminar</th>
								</tr>
							</thead>
							
							<tbody id="contenidoTratamiento">
								
								<?php
								if( ! empty($datos_contendidoCargadoCarroProce) ) {

									for ($i=0; $i<count($datos_contendidoCargadoCarroProce); $i++) {

										 $parametros['preCod']     = $datos_contendidoCargadoCarroProce[$i];
										 
										 $datosTablasProcedimiento = $RegistroClinico->cargarProcedimientosPrevios($objCon,$parametros); 

										?>
										
										<tr id="<?=$datosTablasProcedimiento[0]['preCod']?>" class="detalle">
											
											<td hidden class='trata_codigo my-1 py-1 mx-1 px-1 mifuente'><?=$datosTablasProcedimiento[0]['preCod']?></td>
											
											<td class='trata_nombre my-1 py-1 mx-1 px-1 mifuente' colspan="2"><?=$datosTablasProcedimiento[0]['preNombre']?></td>
											
											<td width="5%">
											
												<button type='button' id='eli<?=$datosTablasProcedimiento[0]['preCod']?>' class='btn btn btn-sm btn-outline-danger  mifuente col-lg-12 eliminarExamen'><i class="fas fa-trash"></i></button>
												
											</td>
										
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