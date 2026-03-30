<?php
session_start();
error_reporting(0);
ini_set('max_execution_time', 600);
ini_set('memory_limit', '200M');

require_once("../../../config/config.php");
require_once("../../../class/Util.class.php");
require_once("../../../class/Connection.class.php");
require_once('../../../class/Categorizacion.class.php');
require_once("../../../class/RegistroClinico.class.php");
require_once('../../../class/Rce.class.php');
require_once('../../../class/Imagenologia.class.php');
require_once('../../../class/Laboratorio.class.php');

$objUtil = new Util;
$objCon = new Connection();
$objCat = new Categorizacion;
$objRegistroClinico = new RegistroClinico;
$objRce = new Rce;
$objRayos = new Imagenologia;
$objLaboratorio = new Laboratorio;
require_once('../../../class/AltaUrgencia.class.php'); 		$objAltaUrgencia  		= new AltaUrgencia;
require_once('../../../class/MapaPiso.class.php');  	$objMapaPiso   = new MapaPiso;

$permisos = $_SESSION["permisosDAU"];

$objCon->db_connect();

if ($_POST) {
	$campos = $objUtil->getFormulario($_POST);
	$_SESSION["modulos"]["Enfermera"]["indacacionEnf"] = $campos;
	$datos  = $objCat->listarPacientes_IND_ENF($objCon, $campos);
} else if (isset($_SESSION["modulos"]["Enfermera"]["indacacionEnf"])) {
	$campos = $_SESSION["modulos"]["Enfermera"]["indacacionEnf"];
	$datos  = $objCat->listarPacientes_IND_ENF($objCon, $campos);
} else {
	$campos = []; // Inicializar como un array vacío
	$datos  = $objCat->listarPacientes_IND_ENF($objCon, $campos);
}
$cargarCat = $objCat->listarCategorizaciones($objCon);
$version   = $objUtil->versionJS();

$rsUnidad          				= $objMapaPiso->SelectUnidad($objCon, $parametros['tipoMapa']);
// print('<pre>'); print_r($rsUnidad); print('</pre>');

?>



<!--
########################################################################################################################
ARCHIVO JS
-->
<script type="text/javascript" charset="utf-8" src="<?= PATH ?>/controllers/client/indicaciones/indicaciones.js?v=<?= $version; ?>1"></script>

<script>

    // Función para actualizar el tiempo en cada td
    function actualizarTiempo() {
        // Seleccionar todos los elementos con la clase 'diferenciaTiempo'
        const celdas = document.querySelectorAll('.diferenciaTiempo');
        
        celdas.forEach((celda) => {
            // Obtener el valor de la diferencia en segundos desde el atributo 'data-fecha-diferencia'
            let diferencia = parseInt(celda.parentElement.getAttribute('data-fecha-diferencia'));
            
            // Calcular los días, horas, minutos y segundos
            let segundos = diferencia;
            let minutos = Math.floor(segundos / 60);
            let horas = Math.floor(minutos / 60);
            let dias = Math.floor(horas / 24);

            // Calcular los componentes restantes
            segundos = segundos % 60;
            minutos = minutos % 60;
            horas = horas % 24;

           // Formatear el tiempo en 00:00:00:00 (días, horas, minutos, segundos)
            let diasFormateados = (dias > 0) ? String(dias).padStart(2, '0') + ':' : '';
            let horasFormateados = String(horas).padStart(2, '0');
            let minutosFormateados = String(minutos).padStart(2, '0');
            // let segundosFormateados = String(segundos).padStart(2, '0');

            // Mostrar el tiempo formateado
            if(diferencia){
            	celda.innerHTML = `${diasFormateados}${horasFormateados}:${minutosFormateados}`;
            }else{
            	celda.innerHTML = `00:00`;
            }

            // Incrementar la diferencia cada segundo
            celda.parentElement.setAttribute('data-fecha-diferencia', diferencia + 1);
        });
    }

    // Llamar a la función cada 1000ms (1 segundo)
    if (!window.intervalActualizacion) {
	    window.intervalActualizacion = setInterval(actualizarTiempo, 1000);
	}
</script>

<!--
########################################################################################################################
ESTILOS
-->
<style>
	div.dataTables_wrapper div.dataTables_filter label {
		font-weight: normal;
		white-space: nowrap;
		text-align: left;
		display: none;
	}

	div.dataTables_wrapper div.dataTables_info {
		padding-top: 8px;
		white-space: nowrap;
		display: none;
	}
</style>



<!--
########################################################################################################################
DESPLIEGUE FORMULARIO INDICACIONES
-->
<div class="row ">
    <div class="col-lg-4">
        <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i> Indicaciones</b></h6>
    </div>
</div>



<div id='divIndicacionesEnfermera'>
	<!--
	**************************************************************************
	Filtros de Búsqueda
	**************************************************************************
	-->
	<form class="formularios" id="frm_enf_indicaciones" name="frm_enf_indicaciones" role="form" method="POST" >
		<div class="row">
			<div class="col-md-3 form-group has-feedback">
		      <div class="input-group shadow">
		        <div class="input-group-prepend">
		          <div class="input-group-text mifuente12" id="btnGroupAddonDocumento"><i class="fas fa-bars darkcolor-barra2"></i></div>
		        </div>
		        <select id="frm_unidad" name="frm_unidad" class="form-control form-control-sm mifuente12" style="">
		        	<?php for( $i = 0; $i < count($rsUnidad); $i++ ) { ?>
					<option value="<?=$rsUnidad[$i]['id_unidad'];?>" <?php if($campos["frm_unidad"]==$rsUnidad[$i]['id_unidad']) echo "selected"?> ><?=$rsUnidad[$i]['unidad_descripcion'];?></option>
					<?php } ?>
				</select>
		      </div>
		    </div>
			<div class="col-md-3 form-group has-feedback">
		      <div class="input-group shadow">
		        <div class="input-group-prepend">
		          <div class="input-group-text mifuente12" id="btnGroupAddonDocumento"><i class="fas fa-bars darkcolor-barra2"></i></div>
		        </div>
		        <select class="form-control form-control-sm mifuente12" id="estado" name="estado" aria-describedby="btnGroupAddonestado">
		        <option value="" >Todas los Estados</option>
		          <option value="1" <?php if($campos["estado"]==1) echo "selected"?>>Indicaciones Listas</option>
		          <option value="2" <?php if($campos["estado"]==2) echo "selected"?>>Indicaciones Pendientes</option>
		        </select>
		      </div>
		    </div>
			<div class="col-md-3 form-group has-feedback">
		      <div class="input-group shadow">
		        <div class="input-group-prepend">
		          <div class="input-group-text mifuente12" id="btnGroupAddonDocumento"><i class="fas fa-bars darkcolor-barra2"></i></div>
		        </div>
		        <select class="form-control form-control-sm mifuente12" id="indicacion" name="indicacion" aria-describedby="btnGroupAddonindicacion">
		        <option value="" >Todas las Indicaciones</option>
		          <option value="1" <?php if($campos["indicacion"]==1) echo "selected"?>>Solicitud Especialidad</option>
		          <option value="2" <?php if($campos["indicacion"]==2) echo "selected"?>>Indicacion Procedimiento</option>
		          <option value="3" <?php if($campos["indicacion"]==3) echo "selected"?>>Indicacion Imagenologia</option>
		          <option value="7" <?php if($campos["indicacion"]==7) echo "selected"?>>Indicacion TC</option>
		          <option value="4" <?php if($campos["indicacion"]==4) echo "selected"?>>Indicacion Tratamiento</option>
		          <option value="5" <?php if($campos["indicacion"]==5) echo "selected"?>>Indicacion Laboratorio</option>
		          <option value="6" <?php if($campos["indicacion"]==6) echo "selected"?>>Indicacion Otros</option>
		        </select>
		      </div>
		    </div>
		    <div class="col-lg-2 col-md-2 col-2">
		      <div class="input-group-append shadow" id="button-addon4">
		          <button id="btnBuscarPaciente" class="btn btn-secondary2  mifuente12 col-lg-6" type="button" style="border-radius: 0rem !important;"><svg class="svg-inline--fa fa-search fa-w-16 mr-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path></svg><!-- <i class="fas fa-search mr-2"></i> -->Buscar</button>
		          <button id="btnEliminarFiltrosPa" class="btn btn-outline-secondary2 mifuente12 col-lg-6" type="button" style="border-radius: 0rem !important;">Limpiar</button>
		      </div>
		    </div>
		</div>
			
	</form>
	<!--
	**************************************************************************
	Resultado de Búsqueda
	**************************************************************************
	-->
	<div
		class="row  mifuente11"
	>
		<table id="tablaContenidoIndicacionesResumen"
			class="table table-bordered table-hover table-condensed  mifuente11"
			
		>
			<thead>
				<tr class="table-primary">
					<td class="mifuente11  text-center"
						style="width:5%; vertical-align:middle;"
					>
						DAU
					</td>
					<td class="mifuente11  text-center"
						style="width:7%; vertical-align:middle;"
					>
						RUN
					</td>
					<td class="mifuente11  text-center"
						style="width:20%; vertical-align:middle;"
					>
						PACIENTE
					</td>
					<!-- <td class="mifuente11  text-center"
						style="width:5%; vertical-align:middle;"
					>
						CATEG
					</td> -->
					<td class="mifuente11  text-center"
						style="width:5%; vertical-align:middle;"
					>
						UBICACIÓN
					</td>
					<td class="mifuente11  text-center"
						style="width:10%; vertical-align:middle;"
					>
						TIEMPO<br>
						<span class="mifuente9">Inicio</span><span class="mifuente9">&nbsp;/&nbsp;Indicica</span>
					</td>
					<td class="mifuente11  text-center"
						style="width:9%; vertical-align:middle;"
					>
						S. ESPECIALIDAD
					</td>
					<td class="mifuente11  text-center"
						style="width:7%; vertical-align:middle;"
					>
						IND. PROCE
					</td>
					<td class="mifuente11  text-center"
						style="width:7%; vertical-align:middle;"
					>
						IND. TC
					</td>
					<td class="mifuente11  text-center"
						style="width:7%; vertical-align:middle;"
					>
						IND. IMAGEN
					</td>
					<td class="mifuente11  text-center"
						style="width:7%; vertical-align:middle;"
					>
						IND. TRAT
					</td>
					<td class="mifuente11  text-center"
						style="width:7%; vertical-align:middle;"
					>
						IND. LAB
					</td>
					<td class="mifuente11  text-center"
						style="width:7%; vertical-align:middle;"
					>
						IND. OTROS
					</td>
					<td class="mifuente11  text-center"
						style="width:4%; vertical-align:middle;"
					>
						ACCIÓN
					</td>
				</tr>
			</thead>
			<tbody id="contenidoTabla">
				<?php
				for ($i = 0; $i < count($datos); $i++) {

					$existeSolicitudAltaUrgencia 		= $objAltaUrgencia->existeSolicitudAltaUrgencia($objCon, $datos[$i]["dau_id"]);
					$dau = $datos[$i]["dau_id"];
					$run = (!$objUtil->existe($datos[$i]["rut_extranjero"]))
						? $objUtil->formatearNumero($datos[$i]["rut"]) . '-' . $objUtil->generaDigito($datos[$i]["rut"])
						: $datos[$i]["rut_extranjero"];
					$paciente = $datos[$i]["nombre"];
					if (pacienteTieneIndicacionAltaHospitalizacion($existeSolicitudAltaUrgencia)) { 
						$paciente = '<i class="fas fa-procedures mr-1 text-primary mifuente15"></i> '.$paciente;
					}
					$categorizacion = $datos[$i]["dau_categorizacion_actual"];
					$ubicacion = $datos[$i]["sal_descripcion"]. " / ".$datos[$i]["tipo_cama_sigla"]."-".$datos[$i]["cam_descripcion"];
					if($datos[$i]["fecha_mas_pequena"] != null){
						$fechaMasPequena 	= strtotime($datos[$i]["fecha_mas_pequena"]);
						$fechaServidor  	= strtotime($datos[$i]["fecha_servidor"]);
						$diferenciaSegundos = $fechaServidor - $fechaMasPequena;
					}
					if($datos[$i]["dau_inicio_atencion_fecha"] != null){
						$dau_inicio_atencion_fecha 	= strtotime($datos[$i]["dau_inicio_atencion_fecha"]);
						$fechaServidor  	= strtotime($datos[$i]["fecha_servidor"]);
						$diferenciaSegundosdau_inicio_atencion_fecha = $fechaServidor - $dau_inicio_atencion_fecha;
					}
					// Inicializar las fechas y diferencias
					$fechas = [
					    "primera_fecha_solicitud_imagenologiaTC" => ["aplicada" => $datos[$i]["cantidadAplicadaImagenologiaTC"], "total" => $datos[$i]["cantidadTotalImagenologiaTC"]],
					    "primera_fecha_solicitud_imagenologia" => ["aplicada" => $datos[$i]["cantidadAplicadaImagenologia"], "total" => $datos[$i]["cantidadTotalImagenologia"]],
					    "primera_fecha_solicitud_especialista" => ["aplicada" => $datos[$i]["cantidadAplicadaEspecialidad"], "total" => $datos[$i]["cantidadTotalEspecialidad"]],
					    "primera_fecha_solicitud_laboratorio" => ["aplicada" => $datos[$i]["cantidadAplicadaLaboratorio"], "total" => $datos[$i]["cantidadTotalLaboratorio"]],
					    "primera_fecha_Procedimiento" => ["aplicada" => $datos[$i]["cantidadAplicadaProcedimiento"], "total" => $datos[$i]["cantidadTotalProcedimiento"]],
					    "primera_fecha_Otros" => ["aplicada" => $datos[$i]["cantidadAplicadaOtros"], "total" => $datos[$i]["cantidadTotalOtros"]],
					    "primera_fecha_Tratamiento" => ["aplicada" => $datos[$i]["cantidadAplicadaTratamiento"], "total" => $datos[$i]["cantidadTotalTratamiento"]],
					];

					$fechasSegundos = [];
					$fechaServidor = strtotime($datos[$i]["fecha_servidor"]);

					foreach ($fechas as $clave => &$detalle) {
						$diferencia = "";
					    if (!empty($datos[$i][$clave])) {
					    	$diferencia 			= "";
					    	$fechaTimestamp 		= strtotime($datos[$i][$clave]);
					        $diferencia 			= $fechaServidor - $fechaTimestamp;
					        if($detalle["total"] > 0){
					        	
					        $fechasSegundos[$clave] = generarHtmlSolicitud($detalle["aplicada"], $detalle["total"], $diferencia);
					        }
					        // $fechasSegundos[$clave] = generarHtmlSolicitud($detalle["aplicada"], $detalle["total"], $diferencia);
					    } else {
					        $fechasSegundos[$clave] = ""; // Vacío si no hay fecha
					    }
					}
					echo "
						<tr
							id='{$dau}'
							style='text-align:center;'
						>
							<td class='mifuente11 my-1 py-1 mx-1 px-1 text-center' style='vertical-align:middle;' >{$dau}</td>
							<td class='mifuente11 my-1 py-1 mx-1 px-1 text-center' style='vertical-align:middle;' >{$run}</td>
							<td class='mifuente11 my-1 py-1 mx-1 px-1 text-center' style='vertical-align:middle;' >{$paciente}</td>
							<td class='mifuente11 my-1 py-1 mx-1 px-1 text-center' style='vertical-align:middle;' >{$ubicacion}</td>
							<td class='mifuente11 my-1 py-1 mx-1 px-1 text-center' style='vertical-align:middle;' >
							<span data-fecha-diferencia='$diferenciaSegundosdau_inicio_atencion_fecha' >
							<i class='fas fa-stopwatch throb mr-1 text-danger mifuente15'></i><span class='diferenciaTiempo'>00:00</span>
							</span>
							&nbsp;/&nbsp;
							<span data-fecha-diferencia='$diferenciaSegundos' >
							<i class='fas fa-stopwatch throb mr-1 text-danger mifuente15'></i><span class='diferenciaTiempo'>00:00</span>
							</span>

							

							</td>
							
							<td class='mifuente11 my-1 py-1 mx-1 px-1 text-center' style=' vertical-align:middle; ".evaluarEstadoSolicitud($datos[$i]["cantidadAplicadaEspecialidad"], $datos[$i]["cantidadTotalEspecialidad"])."'>
								  {$fechasSegundos['primera_fecha_solicitud_especialista']}
							</td>
							<td class='mifuente11 my-1 py-1 mx-1 px-1 text-center' style=' vertical-align:middle; ".evaluarEstadoSolicitud($datos[$i]["cantidadAplicadaProcedimiento"], $datos[$i]["cantidadTotalProcedimiento"])."'>
							 {$fechasSegundos['primera_fecha_Procedimiento']}
							</td>
							<td class='mifuente11 my-1 py-1 mx-1 px-1 text-center' style=' vertical-align:middle; ".evaluarEstadoSolicitud($datos[$i]["cantidadAplicadaImagenologiaTC"], $datos[$i]["cantidadTotalImagenologiaTC"])."'>
								{$fechasSegundos['primera_fecha_solicitud_imagenologiaTC']}
							</td>
							<td class='mifuente11 my-1 py-1 mx-1 px-1 text-center' style=' vertical-align:middle; ".evaluarEstadoSolicitud($datos[$i]["cantidadAplicadaImagenologia"], $datos[$i]["cantidadTotalImagenologia"])."'>
								{$fechasSegundos['primera_fecha_solicitud_imagenologia']}
							</td>
							<td class='mifuente11 my-1 py-1 mx-1 px-1 text-center' style=' vertical-align:middle; ".evaluarEstadoSolicitud($datos[$i]["cantidadAplicadaTratamiento"], $datos[$i]["cantidadTotalTratamiento"])."'>
								{$fechasSegundos['primera_fecha_Tratamiento']}
							</td>
							<td class='mifuente11 my-1 py-1 mx-1 px-1 text-center' style=' vertical-align:middle; ".evaluarEstadoSolicitud($datos[$i]["cantidadAplicadaLaboratorio"], $datos[$i]["cantidadTotalLaboratorio"])."'>
								{$fechasSegundos['primera_fecha_solicitud_laboratorio']}
							</td>
							<td class='mifuente11 my-1 py-1 mx-1 px-1 text-center' style=' vertical-align:middle; ".evaluarEstadoSolicitud($datos[$i]["cantidadAplicadaOtros"], $datos[$i]["cantidadTotalOtros"])."'>
								{$fechasSegundos['primera_fecha_Otros']}
							</td>
							<td class='mifuente11 my-1 py-1 mx-1 px-1 text-center' style='vertical-align:middle;' >
								<button
									type='button'
									id='{$datos[$i]["dau_id"]}|{$datos[$i]["regId"]}'
									class='btn btn-sm mifuente col-lg-12 btn-outline-primary btnUpdateIndicaciones'
								>
									<i class='fas fa-hand-holding-medical '></i>
								</button>
							</td>
						</tr>
					";
				}
				?>
			</tbody>
		</table>
	</div>



	<!--
	**************************************************************************
								Leyendas
	**************************************************************************
	-->
	<div class="row mr-2 ml-2">
  <div class="col-12  mifuente p-1 mt-1 mb-0 pb-0">
    <strong><svg class="svg-inline--fa fa-info fa-w-6 mr-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="info" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512" data-fa-i2svg=""><path fill="currentColor" d="M20 424.229h20V279.771H20c-11.046 0-20-8.954-20-20V212c0-11.046 8.954-20 20-20h112c11.046 0 20 8.954 20 20v212.229h20c11.046 0 20 8.954 20 20V492c0 11.046-8.954 20-20 20H20c-11.046 0-20-8.954-20-20v-47.771c0-11.046 8.954-20 20-20zM96 0C56.235 0 24 32.235 24 72s32.235 72 72 72 72-32.235 72-72S135.764 0 96 0z"></path></svg><!-- <i class="fas fa-info mr-2"></i> -->&nbsp;Leyendas </strong>
    <div class="thumbnail">
      <table id="" width="100%" class="display table-condensed table-hover mt-1 ">
        <tbody>
          <tr>
            <td class=" mifuente my-1 py-1 mx-1 px-1 " style="width:27%">
              <label class="" style="margin-bottom: 0rem !important;width: 100%;">
                <span style="border:1px solid;border-color:#000; background-color: #90e390" class="color-FFF0F6"><label style="width: 50px;">&nbsp;</label></span><label style="font-weight:normal;"> &nbsp;&nbsp;Indicación Aplicada Total </label>
              </label>
            </td>
            <td class=" mifuente my-1 py-1 mx-1 px-1 " style="width:27%">
              <label class="" style="margin-bottom: 0rem !important;width: 100%;">
                <span style="border:1px solid;border-color:#000; background-color: #f3db58" class="color-FFF0F6"><label style="width: 50px;">&nbsp;</label></span><label style="font-weight:normal;"> &nbsp;&nbsp;Indicación Completa Parcialmente </label>
              </label>
            </td>
            <td class=" mifuente my-1 py-1 mx-1 px-1 " style="width:27%">
              <label class="" style="margin-bottom: 0rem !important;width: 100%;">
                <span style="border:1px solid;border-color:#000; background-color: #f19696" class="color-FFF0F6"><label style="width: 50px;">&nbsp;</label></span><label style="font-weight:normal;"> &nbsp;&nbsp;Ninguna Indicación Aplicada </label>
              </label>
            </td>
            <td class=" mifuente my-1 py-1 mx-1 px-1 " style="width:27%">
              <label class="" style="margin-bottom: 0rem !important;width: 100%;">
                <span style="border:1px solid;border-color:#000; background-color: #C0C0C0" class="color-FFF0F6"><label style="width: 50px;">&nbsp;</label></span><label style="font-weight:normal;"> &nbsp;&nbsp;No Posee Indicación </label>
              </label>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>




<!--
**************************************************************************
FUNCIONES
**************************************************************************
-->
<?php
function pacienteTieneIndicacionAltaHospitalizacion($existeSolicitudAltaUrgencia) {
	return ( pacienteTieneIndicacionAlta($existeSolicitudAltaUrgencia) && tipoIndicacionEsHospitalizacion($existeSolicitudAltaUrgencia[0]['tipoSolicitud']) );
}
function pacienteTieneIndicacionAlta($existeSolicitudAltaUrgencia) {
    return isset($existeSolicitudAltaUrgencia[0]['tipoSolicitud']) && 
           !is_null($existeSolicitudAltaUrgencia[0]['tipoSolicitud']) && 
           !empty($existeSolicitudAltaUrgencia[0]['tipoSolicitud']);
}
function tipoIndicacionEsHospitalizacion($tipoSolicitud) {
	$hospitalizacion 		= 4;
	return $tipoSolicitud 	== $hospitalizacion;
}
function generarHtmlSolicitud($aplicada, $total, $diferenciaSegundos, $iconClass = 'fas fa-stopwatch', $evaluarFunc = 'evaluarCantidadSolicitud') {
    $icono = "<br><span class='text-muted mr-1' data-fecha-diferencia='{$diferenciaSegundos}' >
                <i class='{$iconClass} throb mr-1 text-danger mifuente15'></i>
                <span class='diferenciaTiempo'>00:00</span>
              </span>";
    $texto = "<span class=''><i class='fas fa-pencil-alt mr-1 text-primary'></i> " . $evaluarFunc($aplicada, $total) . "</span>";
    return $texto . $icono;
}
function evaluarEstadoSolicitud($aplicadas, $total) {
	$backgrounds = array(
		"plomo" => "background-color: #C0C0C0;",
		"verde" => "background-color: #90e390;",
		"rojo" => "background-color: #f19696;",
		"amarillo" => "background-color: #f3db58;"
	);

	if ((int)$total === 0) {
		return $backgrounds["plomo"];
	}

	if ((int)$aplicadas === 0) {
		return $backgrounds["rojo"];
	}

	if ((int)$aplicadas < (int)$total) {
		return $backgrounds["amarillo"];
	}

	if ((int)$aplicadas === (int)$total) {
		return $backgrounds["verde"];
	}
}



function evaluarCantidadSolicitud($aplicadas, $total) {
	return ((int)$total !== 0)
		? $aplicadas."/".$total
		: "--";
}
