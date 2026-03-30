<div id="div_signos">
<?php
session_start();
error_reporting(0);
require("../../../../config/config.php");
$permisos = $_SESSION['permiso'.SessionName];
// print('<pre>'); print_r($permisos); print('</pre>');
// require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php');         $objCon         	= new Connection; $objCon->db_connect();
require_once('../../../../class/Util.class.php');               $objUtil        	= new Util;
require_once('../../../../class/Rce.class.php');                $objRce         	= new Rce;
require_once('../../../../class/Categorizacion.class.php');     $objCate        	= new Categorizacion;
require_once('../../../../class/RegistroClinico.class.php');    $objRegistroClinico = new RegistroClinico;

$_POST['bandera']           = $_POST['bandera'] ?? '';
$_POST['cd2']               = $_POST['cd2'] ?? '';
$_POST['cd3']               = $_POST['cd3'] ?? '';
$_POST['estadoDau']         = $_POST['estadoDau'] ?? '';
$_POST['tipoMapa']          = $_POST['tipoMapa'] ?? '';
$_POST['banderaRCE']        = $_POST['banderaRCE'] ?? '';



$parametros['dau_id'] 	    = $_POST['dau_id'];
$parametros['estadoDau']    = $_POST['estadoDau'];
$parametros['tipoMapa']     = $_POST['tipoMapa'];
$parametros['rce_id'] = null;
$datosRce 				= $objRegistroClinico->consultaRCE($objCon,$parametros);
$datosU 				= $objCate -> searchPaciente($objCon, $parametros['dau_id']);
// print('<pre>'); print_r($datosU); print('</pre>');
$datosU = $objCate->searchPaciente($objCon, $parametros['dau_id']);
if (!empty($datosU) && isset($datosU[0]['id_paciente'])) {
    $listaSignos = $objRce->listarSignosVitalesLectura($objCon, $datosU[0]['id_paciente'], $datosRce[0]['regId'] ?? null);
    $parametros['rce_id']   = $datosRce[0]['regId'] ?? null;
} else {
    $listaSignos = []; // Valor predeterminado si no hay resultados
}
// $listaSignos 			= $objRce ->listarSignosVitales($objCon, $datosU[0]['id_paciente'], $datosRce[0]['regId']);
// $parametros['rce_id']	= $datosRce[0]['regId'];
$pesoSV         = null;
$tallaSV        = null;

if ($parametros['rce_id']) {
    $pesoSV  = $objRce->obtenerPesoSignoVital($objCon, $parametros['rce_id']);
    $tallaSV = $objRce->obtenerTallaSignoVital($objCon, $parametros['rce_id']);
}
$version        = $objUtil->versionJS();


?>

<script type="text/javascript" src="<?=PATH?>/controllers/client/rce/signos_vitales/signos_vitales.js?v=<?=time()?>"></script>

<div id="avisoSigno"></div>
<form id="frm_ciclo_vital" name="frm_ciclo_vital" class="formularios mr-3 ml-3" role="form" method="POST">
    <input id="banderaRCE" type="hidden" name="banderaRCE" value="<?php echo $_POST['banderaRCE']; ?>" >
    <div class="row mb-2">
        <label class="text-secondary ml-3"><i class="fas fa-minus mr-1" style="color: #59a9ff;"></i> Registro de Signos Vitales</label>
    </div>
	<div class="row ">
        <div class="col-lg-2 form-group has-feedback" >
            <div class="input-group  shadow">
            	<label id="tipoDocumentoDau" class="input-group-text text-center mifuente12" style="border-radius: 0rem !important;" for="btnGroupAddonpesoCiclo">Peso</label>
                <input id="pesoCiclo" type="text"  class="form-control form-control-sm mifuente12 calculoIMC" name="pesoCiclo" placeholder="Kgs"  tabindex="1" value="<?php echo $pesoSV['SVITALpeso']; ?>" >
                <input id="pesoCiclohidden" type="hidden" name="pesoCiclohidden" value="<?php echo $pesoSV['SVITALpeso']; ?>" >
                <div class="input-group-prepend ">
                  <div class="input-group-text mifuente12" id="btnGroupAddonpesoCiclo">Kg.</div>
                </div>
            </div>          
        </div>
        <div class="col-lg-2 form-group has-feedback" >
            <div class="input-group  shadow">
            	<label id="tipoDocumentoDau" class="input-group-text text-center mifuente12" style="border-radius: 0rem !important;" for="btnGroupAddonsistolicaCiclo">Sistolica</label>
                <input id="sistolicaCiclo" type="text"  class="form-control form-control-sm mifuente12 calculoPAM" name="sistolicaCiclo"  tabindex="3" >
                <div class="input-group-prepend ">
                  <div class="input-group-text mifuente12" id="btnGroupAddonsistolicaCiclo"><i class="fas fa-stethoscope darkcolor-barra2"></i></div>
                </div>
            </div>          
        </div>

        <div class="col-lg-2 form-group has-feedback" >
            <div class="input-group  shadow">
            	<label id="tipoDocumentoDau" class="input-group-text text-center mifuente12" style="border-radius: 0rem !important;" for="btnGroupAddonpulsoCiclo">Pulso</label>
                <input id="pulsoCiclo" type="text"  class="form-control form-control-sm mifuente12 " name="pulsoCiclo"   tabindex="6">
                <div class="input-group-prepend ">
                  <div class="input-group-text mifuente12" id="btnGroupAddonpulsoCiclo"><i class="fas fa-heartbeat darkcolor-barra2"></i></div>
                </div>
            </div>          
        </div>
        <div class="col-lg-3 form-group has-feedback" >
            <div class="input-group  shadow">
                <label id="tipoDocumentoDau" class="input-group-text text-center mifuente12" style="border-radius: 0rem !important;" for="btnGroupAddonglasgowCiclo">Glasgow</label>
                <select class="form-control form-control-sm mifuente12" id="glasgowCiclo" name="glasgowCiclo" tabindex="9">
                    <option value="" disabled selected>3-15</option>
                    <?php for ($i = 3; $i <= 15; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>
                <div class="input-group-prepend ">
                  <div class="input-group-text mifuente12" id="btnGroupAddonglasgowCiclo"><i class="fas fa-chart-line darkcolor-barra2"></i></div>
                </div>
            </div>          
        </div>

      <!--   <div class="col-lg form-group has-feedback" >
            <div class="input-group  shadow">
            	<label id="tipoDocumentoDau" class="input-group-text text-center mifuente12" style="border-radius: 0rem !important;" for="btnGroupAddonglasgowCiclo">Glasgow</label>
                <input id="glasgowCiclo" type="text"  class="form-control form-control-sm mifuente12 " name="glasgowCiclo"   >
                <div class="input-group-prepend ">
                  <div class="input-group-text mifuente12" id="btnGroupAddonglasgowCiclo"><i class="fas fa-chart-line darkcolor-barra2"></i></div>
                </div>
            </div>          
        </div> -->

        <div class="col-lg form-group has-feedback" >
            <div class="input-group  shadow">
            	<label id="tipoDocumentoDau" class="input-group-text text-center mifuente12" style="border-radius: 0rem !important;" for="btnGroupAddonhemoglucoTest">HemoglucoTest</label>
                <input id="hemoglucoTest" type="text"  class="form-control form-control-sm mifuente12 " name="hemoglucoTest"  tabindex="12" >
                <div class="input-group-prepend ">
                  <div class="input-group-text mifuente12" id="btnGroupAddonhemoglucoTest"><i class="fas fa-chart-line darkcolor-barra2"></i></div>
                </div>
            </div>          
        </div>
    </div>
    <div class="row ">
        <div class="col-lg-2 form-group has-feedback" >
            <div class="input-group  shadow">
            	<label id="tipoDocumentoDau" class="input-group-text text-center mifuente12" style="border-radius: 0rem !important;" for="btnGroupAddontallaCiclo">Talla</label>
                <input id="tallaCiclo" type="text"  class="form-control form-control-sm mifuente12 calculoIMC"  name="tallaCiclo" placeholder="Cms"  tabindex="2" value="<?php echo $tallaSV['SVITALtalla']; ?>" >
                <input id="tallaCiclohidden" type="hidden" name="tallaCiclohidden" value="<?php echo $tallaSV['SVITALtalla']; ?>" >
                <div class="input-group-prepend ">
                  <div class="input-group-text mifuente12" id="btnGroupAddontallaCiclo">cm.</div>
                </div>
            </div>          
        </div>
        <div class="col-lg-2 form-group has-feedback" >
            <div class="input-group  shadow">
            	<label id="tipoDocumentoDau" class="input-group-text text-center mifuente12" style="border-radius: 0rem !important;"  for="btnGroupAddondiastolicaCiclo">Distolica</label>
                <input id="diastolicaCiclo" type="text"  class="form-control form-control-sm mifuente12 calculoPAM" name="diastolicaCiclo"  tabindex="4" >
                <div class="input-group-prepend ">
                  <div class="input-group-text mifuente12" id="btnGroupAddondiastolicaCiclo"><i class="fas fa-stethoscope darkcolor-barra2"></i></div>
                </div>
            </div>          
        </div>
        <div class="col-lg-2 form-group has-feedback" >
            <div class="input-group  shadow">
            	<label id="tipoDocumentoDau" class="input-group-text text-center mifuente12" style="border-radius: 0rem !important;" for="btnGroupAddonfrCiclo">FR</label>
                <input id="frCiclo" type="text"  class="form-control form-control-sm mifuente12 " name="frCiclo"  tabindex="7" >
                <div class="input-group-prepend ">
                  <div class="input-group-text mifuente12" id="btnGroupAddonfrCiclo"><i class="fas fa-heartbeat darkcolor-barra2"></i></div>
                </div>
            </div>          
        </div>
         <div class="col-lg-3 form-group has-feedback" >
            <div class="input-group  shadow">
            	<label id="tipoDocumentoDau" class="input-group-text text-center mifuente12" style="border-radius: 0rem !important;" for="btnGroupAddontemperaturaCiclo">Temperatura</label>
                <input id="temperaturaCiclo" type="text"  class="form-control form-control-sm mifuente12 " name="temperaturaCiclo" tabindex="10"  >
                <div class="input-group-prepend ">
                  <div class="input-group-text mifuente12" id="btnGroupAddontemperaturaCiclo"><i class="fa fa-thermometer-half darkcolor-barra2"></i></div>
                </div>
            </div>          
        </div>
        <?php if( $parametros['tipoMapa'] != "mapaAdultoPediatrico"){ ?>
        <div class="col-lg form-group has-feedback" >
            <div class="input-group  shadow">
            	<label id="tipoDocumentoDau" class="input-group-text text-center mifuente12" style="border-radius: 0rem !important;" for="btnGroupAddonsignosVitalesFetales">LCF</label>
                <input id="signosVitalesFetales" type="text"  class="form-control form-control-sm mifuente12 " name="signosVitalesFetales"  tabindex="13" >
                <div class="input-group-prepend ">
                  <div class="input-group-text mifuente12" id="btnGroupAddonsignosVitalesFetales"><i class="fa fa-heartbeat darkcolor-barra2"></i></div>
                </div>
            </div>          
        </div>
        <?php } ?>
        <?php if( $parametros['tipoMapa'] == "mapaAdultoPediatrico"){ ?>
        <div class="col-lg form-group has-feedback" >
            <div class="input-group  shadow">
                <label id="tipoDocumentoDau" class="input-group-text text-center mifuente12" style="border-radius: 0rem !important;" for="btnGroupAddonFio2">Fio2</label>
                <input id="Fio2" type="text"  class="form-control form-control-sm mifuente12 " name="Fio2"  tabindex="13"  >
                <div class="input-group-prepend ">
                  <div class="input-group-text mifuente12" id="btnGroupAddonFio2">%</div>
                </div>
            </div>          
        </div>
        <?php } ?>
    </div>
    <div class="row ">
        <div class="col-lg-2 form-group has-feedback" >
            <div class="input-group  shadow">
            	<label id="tipoDocumentoDau" class="input-group-text text-center mifuente12" style="border-radius: 0rem !important;" for="btnGroupAddonimc">IMC</label>
                <input id="imc" type="text"  readonly="readonly" class="form-control form-control-sm mifuente12 calculoIMC"  name="imc"   value="<?php echo $tallaSV['SVITALtalla']; ?>" >
                <div class="input-group-prepend ">
                  <div class="input-group-text mifuente12" id="btnGroupAddonimc"><i class="fas fa-male darkcolor-barra2"></i></div>
                </div>
            </div>          
        </div>
        <div class="col-lg-2 form-group has-feedback" >
            <div class="input-group  shadow">
            	<label id="tipoDocumentoDau" class="input-group-text text-center mifuente12" style="border-radius: 0rem !important;" for="btnGroupAddonpamCiclo">PAM</label>
                <input id="pamCiclo" type="text"  tabindex="5" class="form-control form-control-sm mifuente12 " name="pamCiclo" readonly   >
                <div class="input-group-prepend ">
                  <div class="input-group-text mifuente12" id="btnGroupAddonpamCiclo"><i class="fas fa-stethoscope darkcolor-barra2"></i></div>
                </div>
            </div>          
        </div>

        <div class="col-lg-2 form-group has-feedback" >
            <div class="input-group  shadow">
            	<label id="tipoDocumentoDau" class="input-group-text text-center mifuente12" style="border-radius: 0rem !important;" for="btnGroupAddonsaturacionCiclo">Sat. Oxigeno</label>
                <input id="saturacionCiclo" type="text"  class="form-control form-control-sm mifuente12 " name="saturacionCiclo"  tabindex="8" >
                <div class="input-group-prepend ">
                  <div class="input-group-text mifuente12" id="btnGroupAddonsaturacionCiclo"><i class="fas fa-chart-line darkcolor-barra2"></i></div>
                </div>
            </div>          
        </div>
        <div class="col-lg-3 form-group has-feedback" >
            <div class="input-group  shadow">
            	<label id="tipoDocumentoDau" class="input-group-text text-center mifuente12" style="border-radius: 0rem !important;" for="btnGroupAddonevaCiclo">EVA</label>
                <select class="form-control form-control-sm mifuente12" id="evaCiclo" name="evaCiclo" tabindex="11">
					<option value="11" disabled selected>0-10</option>
					<option value="0">0</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
				</select>
                <div class="input-group-prepend ">
                  <div class="input-group-text mifuente12" id="btnGroupAddonevaCiclo"><i class="fas fa-chart-line darkcolor-barra2"></i></div>
                </div>
            </div>          
        </div>

        <?php if( $parametros['tipoMapa'] != "mapaAdultoPediatrico"){ ?>
        <div class="col-lg form-group has-feedback" >
            <div class="input-group  shadow">
            	<label id="tipoDocumentoDau" class="input-group-text text-center mifuente12" style="border-radius: 0rem !important;" for="btnGroupAddonrbne">RBNE</label>
                <select class="form-control form-control-sm mifuente12" id="rbne" name="rbne" tabindex="14">
					<option value=""  selected></option>
					<option value="Reactivo">Reactivo</option>
					<option value="No Reactivo">No Reactivo</option>
					<option value="No Excluyente">Excluyente</option>
				</select>
                <div class="input-group-prepend ">
                  <div class="input-group-text mifuente12" id="btnGroupAddonrbne"><i class="fas fa-chart-line darkcolor-barra2"></i></div>
                </div>
            </div>          
        </div>
        <?php } ?>
    </div>
    <hr>
	<div class="row mr-1 ml-1" id="lugarTablaAdjunto">
		<table id="lista_signos" class="table table-striped table-bordered table-hover table-condensed tablaSignosVitales tablasHisto mifuente12" >
			<thead>
				<tr class="text-center mifuente12">
					<th>Fecha</th>
					<th>Peso</th>
					<th>Talla</th>
					<th>PAS</th>
					<th>PAD</th>
                    <th>PAM</th>
					<th>Pulso</th>
					<th>FR</th>
					<th>SaO2</th>
					<th>Glasgow</th>
					<th>T°</th>
					<th>EVA</th>
					<th>Hemogluco</th>
                    <?php if( $parametros['tipoMapa'] != "mapaAdultoPediatrico"){ ?>
					<th>LCF</th>
					<th>RBNE</th>
                    <?php } ?>
                    <?php if( $parametros['tipoMapa'] == "mapaAdultoPediatrico"){ ?>
                    <th>Fio2</th>
                    <?php } ?>
				</tr>
			</thead>
			<tbody>
				<?php
				$contadorListaSignos = count($listaSignos);
				if ( $contadorListaSignos == 0 ){
				?>
					<tr id="signos" hidden>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
                        <?php if( $parametros['tipoMapa'] != "mapaAdultoPediatrico"){ ?>
						<td></td>
						<td></td>
                        <?php } ?>
                        <?php if( $parametros['tipoMapa'] == "mapaAdultoPediatrico"){ ?>
                        <td></td>
                        <?php } ?>
					</tr>
				<?php
				} else {

				for($i=0;$i<count($listaSignos);$i++){ ?>

				<tr id="signos" class="text-center mifuente11">
					<td class="text-secondary text-center"><?= date("d-m-Y", strtotime($listaSignos[$i]['SVITALfecha'])); ?> <?= date("H:i:s", strtotime($listaSignos[$i]['SVITALfecha'])); ?></td>
					<td><?= $listaSignos[$i]['SVITALpeso']; ?> Kg</td>
					<td><?= $listaSignos[$i]['SVITALtalla']; ?> cm</td>
					<td><?= $listaSignos[$i]['SVITALsistolica']; ?></td>
					<td><?= $listaSignos[$i]['SVITALdiastolica']; ?></td>
                    <td><?= $listaSignos[$i]['SVITALPAM']; ?></td>
					<td><?= $listaSignos[$i]['SVITALpulso']; ?></td>
					<td><?= $listaSignos[$i]['SVITALfr']; ?></td>
					<td><?= $listaSignos[$i]['SVITALsaturacion']; ?></td>
					<td><?= $listaSignos[$i]['SVITALglasgow']; ?></td>
					<td><?= $listaSignos[$i]['SVITALtemperatura']; ?></td>
					<td><?= $listaSignos[$i]['SVITALeva']; ?></td>
					<td><?= $listaSignos[$i]['SVITALHemoglucoTest']; ?></td>
                    <?php if( $parametros['tipoMapa'] != "mapaAdultoPediatrico"){ ?>
					<td><?= $listaSignos[$i]['SVITALfeto']; ?></td>
					<td><?= $listaSignos[$i]['SVITALrbne']; ?></td>
                    <?php } ?>
                    <?php if( $parametros['tipoMapa'] == "mapaAdultoPediatrico"){ ?>
                    <td><?= $listaSignos[$i]['FIO2']; ?></td>
                    <?php } ?>
				</tr>
				<?php }
				}?>

			</tbody>
		</table>
	</div>
	<input type="hidden" 		name="rce_idSV" 					id="rce_idSV" 					value="<?php echo $parametros['rce_id'];?>" />
	<input type="hidden" 		name="dau_idSV" 					id="dau_idSV" 					value="<?php echo $parametros['dau_id'];?>" />
	<input type="hidden" 		name="bandera" 						id="bandera" 					value="<?= $_POST['bandera']; ?>" 			/>
	<input type="hidden" 		name="cd2" 							id="cd2" 						value="<?= $_POST['cd2']; ?>" 				/>
	<input type="hidden"	 	name="cd3" 							id="cd3" 						value="<?= $_POST['cd3']; ?>" 				/>
	<input type="hidden" 		name="id_paciente" 					id="id_paciente" 			    value="<?= $datosU[0]['id_paciente']?>" 	/>
    <?php if ( array_search(859,$permisos) != null ) { ?>
    <?php if ( $parametros['estadoDau'] != 5 && $parametros['estadoDau'] != 6 && $parametros['estadoDau'] != 7) { ?>
	<hr>
	<div class="row">
		<div class="col-lg-9">
		</div>
		<div class="col-lg-3"> <button id="btn_agregar_signos_vitales" type="button" name="btn_agregar_signos_vitales" class="btn btn-sm btn-primary2  col-lg-12 text-center" ><i class="fas fa-plus mr-2"></i>Agregar Signos</button> </div>
	</div>
    <?php } ?>
    <?php } ?>
</form>
</div>