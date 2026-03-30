<?php
session_start();
error_reporting(0);
require_once("../../../../class/Util.class.php"); 			$objUtil   		= new Util;
require_once("../../../../class/Connection.class.php"); 	$objCon    		= new Connection();
require_once("../../../../class/Dau.class.php" );  			$objDetalleDau  = new Dau;
require("../../../../config/config.php");

$objCon->db_connect();

$parametros      = $objUtil->getFormulario($_POST);
$rsFechaHora     = $objUtil->getHorarioServidor($objCon);
$fechaAplicarNEA = $rsFechaHora[0]['fecha'];
$horaAplicarNEA  = $rsFechaHora[0]['hora'];
$datosDAU        = $objDetalleDau->SelectDau($objCon, $parametros);
$fechas = array(
    'dau_indicacion_egreso_fecha',
    'dau_inicio_atencion_fecha',
    'dau_ingreso_sala_fecha',
    'dau_categorizacion_actual_fecha',
    'dau_admision_fecha'
);
foreach ($fechas as $campo) {
    if (!is_null($datosDAU[0][$campo])) {
        if ($campo !== 'dau_admision_fecha' && !is_null($datosDAU[0]['dau_categorizacion_actual_fecha'])) {
            $datetimeX = strtotime($datosDAU[0][$campo]);
            $datetime2 = strtotime($datosDAU[0]['dau_categorizacion_actual_fecha']);
            
            if ($datetimeX > $datetime2) {
                $fechaUltima = date("Y-m-d", $datetimeX);
                $horaUltima = date("H:i:s", $datetimeX);
            } else {
                $fechaUltima = date("Y-m-d", $datetime2);
                $horaUltima = date("H:i:s", $datetime2);
            }
        } else {
            $fechaUltima = date("Y-m-d", strtotime($datosDAU[0][$campo]));
            $horaUltima = date("H:i:s", strtotime($datosDAU[0][$campo]));
        }
        break;
    }
}
$resultadoConsulta = $objDetalleDau->obtenerInformacionLlamados($objCon, $parametros['dau_id']);
if ( !is_null($resultadoConsulta['fechaPrimerLlamado']) && !empty($resultadoConsulta['fechaPrimerLlamado'] ) ) {
	$fechaPrimerLlamado 			= date("d-m-Y H:i", strtotime($resultadoConsulta['fechaPrimerLlamado']));
	$textoFechaPrimerLlamado		= '('.$fechaPrimerLlamado.')';
	$checkedPrimerLlamado			= 'checked';
	$disabledCheckBoxPrimerLlamado 	= 'disabled';
	$disabledCheckBoxSegundoLlamado	= '';
	$disabledCheckBoxTercerLlamado	= 'disabled';
} else {
	$disabledCheckBoxPrimerLlamado 	= '';
	$disabledCheckBoxSegundoLlamado	= 'disabled';
	$disabledCheckBoxTercerLlamado	= 'disabled';
}
if ( !is_null($resultadoConsulta['fechaSegundoLlamado']) && !empty($resultadoConsulta['fechaSegundoLlamado'] ) ) {
	$fechaSegundoLlamado 			= date("d-m-Y H:i", strtotime($resultadoConsulta['fechaSegundoLlamado']));
	$textoFechaSegundoLlamado		= '('.$fechaSegundoLlamado.')';
	$checkedSegundoLlamado			= 'checked';
	$disabledCheckBoxPrimerLlamado 	= 'disabled';
	$disabledCheckBoxSegundoLlamado	= 'disabled';					
	$disabledCheckBoxTercerLlamado	= '';
}
if ( !is_null($resultadoConsulta['fechaTercerLlamado']) && !empty($resultadoConsulta['fechaTercerLlamado'] ) ) {
	$fechaTercerLlamado 			= date("d-m-Y H:i", strtotime($resultadoConsulta['fechaTercerLlamado']));
	$textoFechaTercerLlamado		= '('.$fechaTercerLlamado.')';
	$checkedTercerLlamado			= 'checked';
	$disabledCheckBoxPrimerLlamado 	= 'disabled';
	$disabledCheckBoxSegundoLlamado	= 'disabled';
	$disabledCheckBoxTercerLlamado	= 'disabled';
}
$version    = $objUtil->versionJS();
?>
<script type="text/javascript" src="<?=PATH?>/controllers/client/rce/nea/modalAplicarNEA.js?v=<?=$version;?>111"></script>
<form id="frm_aplicar_nea" name="frm_aplicar_nea" class="formularios mr-3 ml-3" role="form" method="POST">
    <div class="row mb-2">
        <label class="text-secondary ml-3"><i class="fas fa-minus mr-1" style="color: #59a9ff;"></i> Aplicar N.E.A.</label>
    </div>

	<div class="row mb-2" hidden>
		<div class="col-md-6">
			<label class="mifuente">Fecha de Aplicación N.E.A.</label>
			<div class="input-group  ">
                <div class="input-group-prepend ">
                  <div class="input-group-text mifuente12" id="btnGroupAddonfrm_rut"><i class="fas fa-calendar darkcolor-barra2"></i></div>
                </div>
                <input type="date"  class="form-control form-control-sm mifuente12" placeholder="DD-MM-AA" id="frm_fecha_aNEA" name="frm_fecha_aNEA" max="<?=$fechaAplicarNEA?>" min="<?=$fechaUltima?>"  value="<?=$fechaAplicarNEA;?>">
            </div>
		</div>
		<div class="col-md-6">
			<label class="mifuente">Hora de Aplicación N.E.A.</label>
			<div class="input-group  ">
                <div class="input-group-prepend ">
                  <div class="input-group-text mifuente12" id="btnGroupAddonfrm_rut"><i class="fas fa-clock darkcolor-barra2"></i></div>
                </div>
                <input type="input"  class="form-control form-control-sm mifuente12" placeholder="HH:MM" id="frm_hora_aNEA" name="frm_hora_aNEA" value="<?=$horaAplicarNEA;?>" onClick="this.value=''">
            </div>
		</div>
	</div>
	<div class="row ">
		<div class="col-md-6">
         	<label class="mifuente">Motivo</label>
			<div class="input-group  ">
                <textarea onkeypress="return limita_TxtArea(event, 200);" onkeyup="actualizaInfoDiagnostico(200)" onDrop="return false" maxlength="200" id="frm_txt_apliNEA" name="frm_txt_apliNEA" class="form-control form-control-sm mifuente12" rows="5"></textarea>
            </div>
            <div class="text-right">
				<p style="font-size: 12px; color: #606060" id="infoMotApliNEA">
				    Máximo caracteres <span id="maximo"></span>
				</p>
			</div>
		</div>
		<div class="col-md-6 mt-4">
			<div class="row mt-2">
				<div class="col-md-12">	
					<label class="mifuente">1º Llamado </label>
					<label id="textoFechaHoraPrimerLlamado" class="mifuente"><?php echo $textoFechaPrimerLlamado; ?></label>
					<input type="checkbox" name="chk_primerLlamado" id="chk_primerLlamado"  onclick="manejarClickLlamado('primero', 'chk_primerLlamado', 'textoFechaHoraPrimerLlamado', 'fechaPrimerLlamado','usuarioPrimerLlamado')" style="float:right;" value="<?php echo $fechaPrimerLlamado; ?>"  <?php echo $checkedPrimerLlamado; ?> <?php echo $disabledCheckBoxPrimerLlamado; ?> >
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">	
					<label class="mifuente">2º Llamado</label>
					<label id="textoFechaHoraSegundoLlamado" class="mifuente"><?php echo $textoFechaSegundoLlamado; ?></label>
					<input type="checkbox" name="chk_segundoLlamado" id="chk_segundoLlamado"  onclick="manejarClickLlamado('segundo', 'chk_segundoLlamado', 'textoFechaHoraSegundoLlamado','fechaSegundoLlamado','usuarioSegundoLlamado')" style="float:right;" value="<?php echo $fechaSegundoLlamado; ?>" <?php echo $checkedSegundoLlamado; ?> <?php echo $disabledCheckBoxSegundoLlamado; ?> >
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<label class="mifuente">3º Llamado</label>
					<label id="textoFechaHoraTercerLlamado" class="mifuente"><?php echo $textoFechaTercerLlamado; ?></label>
					<input type="checkbox" name="chk_tercerLlamado" id="chk_tercerLlamado"  onclick="manejarClickLlamado('tercero', 'chk_tercerLlamado', 'textoFechaHoraTercerLlamado','fechaTercerLlamado','usuarioTercerLlamado')" style="float:right;" value="<?php echo $fechaTercerLlamado; ?>" <?php echo $checkedTercerLlamado; ?> <?php echo $disabledCheckBoxTercerLlamado; ?> >
				</div>
			</div>
		</div>
    </div>

	<input type="hidden" 		name="inpH_fechaAdmision" 		id="inpH_fechaAdmision" 		value="<?=$fechaUltima?>" 			>
	<input type="hidden" 		name="inpH_fechaAplicarNEA" 	id="inpH_fechaAplicarNEA" 		value="<?=$fechaAplicarNEA?>"		>
	<input type="hidden"	 	name="inpH_horaAdmision" 		id="inpH_horaAdmision" 			value="<?=$horaUltima?>"			>
	<input type="hidden" 		name="inpH_horaAplicarNEA" 		id="inpH_horaAplicarNEA" 		value="<?=$horaAplicarNEA?>"		>
	<input type="hidden" 		name="idDau" 					id="idDau" 						value="<?=$parametros['dau_id']?>"	>
	<input type="hidden" 		name="tipoMapa" 				id="tipoMapa" 					value="<?=$parametros['tipoMapa']?>"	>
	<hr>
	<div class="row">
		<div class="col-lg-9">
		</div>
		<div class="col-lg-3"> <button id="btnGuardarApliNEA" type="button" name="btnGuardarApliNEA" class="btn btn-sm btn-primary2  col-lg-12 text-center" ><i class="fas fa-save mr-2"></i>Registrar</button> </div>
	</div>
</form>