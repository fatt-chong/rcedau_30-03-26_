<?php
ini_set('max_execution_time', 600); 
ini_set('memory_limit', '128M'); 
error_reporting(0);
require("../../../config/config.php"); 
require_once('../../../class/Connection.class.php');            $objCon             = new Connection; $objCon->db_connect();
require_once('../../../class/Util.class.php');                  $objUtil            = new Util;
require_once('../../../class/MotivoConsulta.class.php');        $objMotivo          = new MotivoConsulta;
require_once('../../../class/Indicacion.class.php');            $objIndicacion      = new Indicacion;
require_once('../../../class/Consulta.class.php');              $objConsulta        = new Consulta;
require_once('../../../class/Dau.class.php');                   $objDau             = new Dau;
require_once('../../../class/Cierre.class.php');                $objCierre          = new Cierre;
require_once("../../../class/Agenda.class.php" );               $objAgenda          = new Agenda;
require_once("../../../class/RegistroClinico.class.php" );      $objRegistroClinico = new RegistroClinico;
require_once('../../../class/Rce.class.php');                   $objRce             = new Rce;


$listarIndicacion     = $objIndicacion->listarIndicacion($objCon);
$parametros['Iddau']  = $_POST['Iddau'];
$parametros['dau_id'] = $parametros['Iddau'];
$datos                = $objConsulta->consultaDAU($objCon,$parametros);
$resEspecialidad      = $objAgenda->getEspecialidad($objCon);
$rsDerivacion         = $objDau->getAltaDerivacion($objCon);
$rsAPS                = $objDau->getAPS($objCon);
$destinoControl       = $datos[0]['dau_cierre_des_id'];;
$esp                  = '';
                                    
for ( $i = 0; $i < count($resEspecialidad); $i++) {
        
    if ( strpos($datos[0]['dau_cierre_ind_especialidad'], $resEspecialidad[$i]['ESPcodigo']) !== false ){
            
        if ( empty($esp) || is_null($esp) ) {
                
            $esp = $resEspecialidad[$i]['ESPdescripcion'];
                    
            continue;
                
        }
            
        $esp = $esp.' - '.$resEspecialidad[$i]['ESPdescripcion'];
            
    }
    
}

for ( $i = 0; $i < count($rsAPS) ; $i++ ) {

    if ( $rsAPS[$i]['ESTAcodigo'] == $datos[0]['dau_cierre_ind_aps'] ) {

        $aps  = $rsAPS[$i]['ESTAdescripcion'];
  
    }

}

for ( $i = 0; $i < count($rsDerivacion) ; $i++ ) {

    if ( $rsDerivacion[$i]['alt_der_id'] == $datos[0]['dau_cierre_atl_der_id'] ) {

        $deri  = $rsDerivacion[$i]['alt_der_descripcion'];
  
    }

}

$fechaActual         = date("Y-m-d H: i: s");
$fechaAdmision       = $datos[0]['dau_admision_fecha'];
$fechaCategorizacion = $datos[0]['dau_categorizacion_actual_fecha'];
$fechaCierreMapaPiso = $datos[0]['dau_indicacion_egreso_aplica_fecha'];

if ( $datos[0]['dau_cierre_administrativo_fecha'] ) {

    $tiempoCierre = $datos[0]['dau_cierre_administrativo_fecha'];

} else if ( $datos[0]['dau_indicacion_egreso_aplica_fecha'] ) {

    $tiempoCierre= $datos[0]['dau_indicacion_egreso_aplica_fecha'];

}


if ( $tiempoCierre == '' ) {
    
    $fechaCierreAdministrativo = date("d-m-Y H:i");

} else { 

    $fechaCierreAdministrativo = date("d-m-Y H:i", strtotime($tiempoCierre));

}

if ( $datos[0]['dau_alcoholemia_fecha'] ) {

    $fechaAcoholemia=date("d-m-Y H:i", strtotime($datos[0]['dau_alcoholemia_fecha']));

}

if ( $datos[0]['cat_id'] ) {
    
    $datos[0]['cat_id'];

} else {

    $datos[0]['cat_id']="Sin definir";

}


if ( $datos[0]['est_id'] == 5 && $datos[0]['dau_cierre_administrativo_fecha'] != "" ) {

    $tiempoCierre= $objUtil->get_timespan_string($fechaAdmision,$fechaCierreAdministrativo)."(Cierre Administrativo)";      

}

if ( $datos[0]['est_id'] == 5 && $datos[0]['dau_indicacion_egreso_aplica_fecha'] != "" ) {

   $tiempoCierre= $objUtil->get_timespan_string($fechaAdmision,$fechaCierreMapaPiso)."(Cierre Mapa Piso)"; 

   if ( $fechaCierreAdministrativo == "" && $datos[0]['dau_categorizacion_actual_fecha'] != "" && $datos[0]['dau_inicio_atencion_fecha'] == "" && $datos[0]['dau_indicacion_egreso_fecha'] == "" && $datos[0]['dau_indicacion_egreso_aplica_fecha'] == "" ) {

        $tiempoCierre= $objUtil->get_timespan_string($fechaAdmision,$fechaCategorizacion)."(Cierre Mapa Piso)";  
    
    }

}

if ( $datos[0]['est_id'] != 5 ) {

    $tiempoCierre= $objUtil->get_timespan_string($fechaAdmision,$fechaActual)."(Atencion)";

}

$listaProfesional          = $objDau->listarMedicosUrgencia($objCon);
$listaCondicionIngreso     = $objCierre->listarCondicionIngreso($objCon);
$listaPronostico           = $objCierre->listarPronostico($objCon);
$listaTratamiento          = $objCierre->listarTratamiento($objCon);
$listaAtendidoPor          = $objCierre->listarAtendidoPor($objCon);
$listaEtilico              = $objDau->listarEtilico($objCon);
$listaMedicosUrgencia      = $objDau->listarMedicosUrgencia($objCon);
$listaTurnos               = $objCierre->listarTurno($objCon);
$listaTipoAtencion         = $objCierre->listarTipoAtencion($objCon);
$rsRce                     = $objRegistroClinico->consultaRCE($objCon,$parametros);
$fechaActualAlcoholemia    = date("d/m/Y");
$fechaActualAlcoholemiaMax = date("Y-m-d");
$horaActualAlcoholemia     = date("H: i: s");

$transexual_bd   = $datos[0]['transexual'];
$nombreSocial_bd = $datos[0]['nombreSocial'];
$nombrePaciente  = $datos[0]['nombres']." ". $datos[0]['apellidopat']." ".$datos[0]['apellidomat'];
$nombreLabel     = 'Paciente';
$InfoNombre      = $objUtil->vista_dau_input_label_modo_2($transexual_bd,$nombreSocial_bd,$nombrePaciente,$nombreLabel,'S');
// print('<pre>');  print_r($InfoNombre);  print('</pre>');

$version             = $objUtil->versionJS();
?>



<!-- 
################################################################################################################################################
                                                                    ARCHIVO JS
-->
<script type="text/javascript" src="<?=PATH?>/assets/libs/dateTimePicker/moment.js"></script>
<script type="text/javascript" src="<?=PATH?>/assets/libs/dateTimePicker/locale/es.js"></script>
<script type="text/javascript" src="<?=PATH?>/assets/libs/dateTimePicker/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" charset="utf-8" src="<?=PATH?>/controllers/client/consulta/ver_cierre_dau.js?v=<?=$version;?>"></script>



<!-- 
################################################################################################################################################
                                                                    ESTILOS
-->



<!-- 
################################################################################################################################################
                                                        DESPLIGUE DETALLES CIERRE DAU
-->



<!-- *********************************************************************
                            Formulario 
**************************************************************************
-->
<form id="frm_cierre" name="frm_cierre" class="formularios form-horizontal" role="form" method="POST"  onsubmit="return false">

    <!-- Campos Ocultos -->          
    <input type="hidden" id="frm_estado_cierre"     name="frm_estado_cierre"    value="<?=$datos[0]['est_id']?>">
    <input type="hidden" id="id_paciente"           name="id_paciente"          value="<?=$datos[0]['id_paciente']?>">
    <input type="hidden" id="idDau"                 name="idDau"                value="<?=$_POST['Iddau']?>">

    <!-- *********************************************************************
                                    Datos del Paciente 
    **************************************************************************
    -->
   
    <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i> Datos del paciente</b></h6>
            <!-- <legend>Datos del paciente</legend>       -->
    <div class="row" >
      <div class="col-md-1 mifuente">
         <label class="control-label encabezado">N° DAU </label>
      </div>
      <div class="col-md-2 mifuente">
         <span>:&nbsp;<?=$datos[0]['dau_id']?></span>
      </div>
      <div class="col-md-1 mifuente">
          <label class="control-label encabezado">Cuenta Cte</label>
      </div>
      <div class="col-md-2 mifuente">
         <span>:&nbsp;<?=$datos[0]['idctacte']?></span>
      </div>
      <div class="col-md-1 mifuente">
         <label class="control-label encabezado">Previsión:</label>
      </div>
      <div class="col-md-2 mifuente">
         <span>:&nbsp;<?=$datos[0]['prevision']?></span>
      </div>
      <div class="col-md-1 mifuente">
          <label class="control-label encabezado">Convenio</label>
      </div>
      <div class="col-md-2 mifuente">
         <span>:&nbsp;<?=$datos[0]['prevision']?></span>
      </div>

    </div>
    <div class="row" >
        <!-- Paciente -->
        <div class="col-md-1 mifuente">
            <label class="control-label encabezado"><?=$InfoNombre['label']?></label>
        </div>

        <div class="col-md-4 mifuente">
            <span>:&nbsp;<?=$InfoNombre['input']?></span>
        </div>
        <!-- Tiempo Atención -->
        <div class="col-md-2 text-right mifuente">
            <label class="control-label encabezado">Tiempo atención</label>
        </div>
        <div class="col-md-5 mifuente">
            <span>:&nbsp;<?=$tiempoCierre?></span>
        </div>
    </div>
    <hr>
     <!-- *********************************************************************
                                    Datos Clínicos 
    **************************************************************************
    -->
    <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i> Datos Clínicos</b></h6>
    <div class="row">
        <div class="col-lg-12">       
            <label class="titulo"> * Admisión</label>
        </div>
    </div>
    <div class="row" >  
        <!-- Paciente llega en... -->
        <div class="col-md-2" "><label class="encabezado">Paciente llega en</label></div>
        <div class="col-md-2"><input type="text" class="form-control form-control-sm mifuente "  value="<?=$datos[0]['med_descripcion']?>" disabled></div>
        <!-- Categorización -->
        <div class="col-md-1" ><label class="encabezado">Categorizacion</label></div>
        <div class="col-md-2"><input type="text" class="form-control form-control-sm mifuente "  value="<?=$datos[0]['cat_id']?>" disabled></div>
        <!-- Fecha -->            
        <div class="col-md-1" ><label class="encabezado">Fecha</label></div>
        <div class="col-md-2"><input type="text" class="form-control form-control-sm mifuente "   value="<?=date('d-m-Y',strtotime($datos[0]['dau_admision_fecha']))?>" disabled></div>
        <!-- Hora -->
        <div class="col-md-1" ><label class="encabezado">Hora</label></div>
        <div class="col-md-1"><input type="text" class="form-control form-control-sm mifuente "  value="<?=date('H:i:s',strtotime($datos[0]['dau_admision_fecha']))?>" disabled></div>
    </div>
    <div class="row mt-2" >
        <!-- Condición Ingreso -->
        <div class="col-md-2" ><label class="encabezado">Condicion ingreso</label></div>
        <div class="col-md-2">
            <select class="form-control form-control-sm mifuente " id='frm_condicion_ingreso' name="frm_condicion_ingreso" > 
                <option value="">Seleccione</option>                        
                <?php for ($i=0; $i <count($listaCondicionIngreso) ; $i++) { ?>
                    <option value="<?=$listaCondicionIngreso[$i]['con_ingreso_id']?>" <?php if($datos[0]['dau_cierre_condicion_ingreso_id']==$listaCondicionIngreso[$i]['con_ingreso_id']){ echo "selected";}?>> <?=$listaCondicionIngreso[$i]['con_ingreso_nombre']?> 
                    </option>
                <?php } ?>
            </select>
        </div>
        <!-- Pronóstico -->
        <div class="col-md-1" ><label class="encabezado">Pronostico</label></div>
        <div class="col-md-2">  
            <select class="form-control form-control-sm mifuente " id='frm_pronostico' name="frm_pronostico" > 
            <option value="">Seleccione</option> 
                <?php for ($i=0; $i <count($listaPronostico) ; $i++) { ?>
                    <option value="<?=$listaPronostico[$i]['pro_pronostico_id']?>" <?php if($rsRce[0]["PRONcodigo"]===$listaPronostico[$i]['pro_pronostico_id']){ echo "selected";}?>> <?=$listaPronostico[$i]['pro_pronostico_nombre']?> 
                    </option>
                <?php } ?>
            </select>
        </div>
        <?php
            $idPaciente = $datos[0]['id_paciente'];
            $idRCE      = $rsRce[0]['regId'];
            $signosVitales = $objRce->listarSignosVitalesCierreDAU($objCon, $idPaciente, $idRCE);
        ?>
        <!-- Peso -->
        <div class="col-md-1" ><label class="encabezado">Peso</label></div>
        <div class="col-md-2"><input type="text" class="form-control form-control-sm mifuente " id="frm_peso" name="frm_peso" placeholder="(Kgs)" maxlength="5" value="<?=$signosVitales['SVITALpeso']?>"></div>
        <!-- Estatura -->
        <div class="col-md-1" ><label class="encabezado">Estatura</label></div>
        <div class="col-md-1"><input type="text" class="form-control form-control-sm mifuente " id="frm_estatura" name="frm_estatura" placeholder="(cms)" maxlength="5" value="<?=$signosVitales['SVITALtalla']?>"></div>
    </div>
    <div class="row">
        <div class="col-lg-12">       
            <label class="titulo"> * Atención</label>
        </div>
    </div>
    <div class="row" >
        <div class="col-md-2" ><label class="encabezado">Tratamiento</label></div>
        <div class="col-md-2">
            <select class="form-control form-control-sm mifuente " id='frm_tratamiento' name="frm_tratamiento" > 
                <option value="">Seleccione</option> 
                <?php for ($i=0; $i <count($listaTratamiento) ; $i++) { ?>
                    <option value="<?=$listaTratamiento[$i]['tra_tratamiento_id']?>" <?php if($datos[0]['dau_cierre_tratamiento_id']==$listaTratamiento[$i]['tra_tratamiento_id']){ echo "selected";}?>> <?=$listaTratamiento[$i]['tra_tratamiento_nombre']?> 
                    </option>
                <?php } ?>
            </select>  
        </div>
        <div class="col-md-1" ><label class="encabezado">Atendido por</label></div>
        <div class="col-md-2">
            <select class="form-control form-control-sm mifuente " id='frm_atendido_por' name="frm_atendido_por" > 
                <option value="">Seleccione</option> 
                <?php for ($i=0; $i <count($listaAtendidoPor) ; $i++) { ?>
                    <option value="<?=$listaAtendidoPor[$i]['ate_atendidopor_id']?>" <?php if($datos[0]['dau_cierre_atendidopor_id']==$listaAtendidoPor[$i]['ate_atendidopor_id']){ echo "selected";}?>> <?=$listaAtendidoPor[$i]['ate_atendidopor_nombre']?> 
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="col-md-1" ><label class="encabezado">Estado Etilico</label></div>
        <div class="col-md-4">
            <select class="form-control form-control-sm mifuente " id='frm_etilico' name="frm_etilico" > 
                <option value="">Seleccione</option>
                <?php for ($i=0; $i <count($listaEtilico) ; $i++) { ?>
                    <option value="<?=$listaEtilico[$i]['eti_id']?>" <?php if($datos[0]['dau_alcoholemia_estado_etilico']==$listaEtilico[$i]['eti_id']){ echo "selected";}?>> <?=$listaEtilico[$i]['eti_descripcion']?> 
                    </option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="row mt-2" >
        <div class="col-md-4">
            <div class="row"> 
                <div class="col-md-6" ><label class="encabezado">Profesional</label></div>   
                <div class="col-md-6">
                    <input type="text" class="form-control form-control-sm mifuente " id="Profesional" name="Profesional" value="<?php echo $datos[0]['nombreUsuario']; ?>" >
                </div>
                <div class="col-md-6 mt-2" ><label class="encabezado">Hora Atencion</label></div>   
                <div class="col-md-6 mt-2">
                    <input type="text" class="form-control form-control-sm mifuente " id="frm_hora_atencion" name="frm_hora_atencion" value="<?php echo date('H:i', strtotime($datos[0]['dau_inicio_atencion_fecha'])); ?>">
                </div>
                <div class="col-md-6 mt-2" ><label class="encabezado">Entrega Postinor</label></div>   
                <div class="col-md-6 mt-2">
                    <input type="checkbox" id="frm_postinor" name="frm_postinor" <?php if($datos[0]['dau_cierre_entrega_postinor']=='S'){ echo "checked";}?> value="S">
                </div>
                <div class="col-md-6 mt-2" ><label class="encabezado">Auge</label></div>   
                <div class="col-md-6 mt-2">
                    <input type="checkbox"  id="frm_auge" name="frm_auge" <?php if($datos[0]['dau_cierre_auge']=='S'){echo "checked";}?> value="S">
                </div>
                <div class="col-md-6 mt-2" ><label class="encabezado">Pertinencia</label></div>   
                <div class="col-md-6 mt-2">
                    <input type="checkbox"  id="frm_pertinencia" name="frm_pertinencia" <?php if($datos[0]['dau_cierre_pertinencia']=='S'){ echo "checked";}?> value="S">
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row"> 
                <div class="col-md-4" ><label class="encabezado">Turno</label></div>
                <div class="col-md-8"> 
                    <select class="form-control form-control-sm mifuente " id='frm_turno' name="frm_turno" > 
                        <option value="">Seleccione</option> 
                        <?php for ($i=0; $i <count($listaTurnos) ; $i++) { ?>
                            <option value="<?=$listaTurnos[$i]['tur_turno_id']?>" <?php if($datos[0]['dau_cierre_turno_id']==$listaTurnos[$i]['tur_turno_id']){ echo "selected";}?>> <?=$listaTurnos[$i]['tur_nombre']?> 
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-4 mt-2" ><label class="encabezado">Fecha Egreso</label></div>
                <div class="col-md-8 mt-2"> 
                    <input type="text"  class="form-control form-control-sm mifuente  date" id="frm_fecha_egreso_adm" name="frm_fecha_egreso_adm" placeholder="DD-MM-AA"  value="<?=$fechaCierreAdministrativo;?>" readonly>
                </div>
            </div>
        </div>
        <div class="col-md-5 ">                  
            <div class="row border-left  border-top" >
                <fieldset class="col-md-12">
                     <div class="row">
                        <div class="col-lg-12">       
                            <label class="titulo mt-2"> * Alcoholemia</label>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Número Frasco -->
                        <div class="col-sm-3">
                            <label class="encabezado">Nro:</label>
                        </div>
                        <div class="col-sm-3">
                            <?php 
                            if(!is_null($datos[0]['dau_alcoholemia_numero_frasco']) && !empty($datos[0]['dau_alcoholemia_numero_frasco'])){
                                $readonly = "readonly";
                            }
                            else{
                                $readonly = "";
                            }
                            ?>
                            <input type="text" class="form-control form-control-sm mifuente " id="frm_nro" name="frm_nro" value="<?=$datos[0]['dau_alcoholemia_numero_frasco']?>" placeholder="Nro" <?php echo $readonly; ?>>
                        </div>
                        <!-- Resultado -->
                        <div class="col-sm-3">
                            <label class="encabezado">Resultado:</label>
                        </div>
                        <div class="col-sm-3 text-center" >
                            +&nbsp;&nbsp;<input type="radio"  name="resultado" id="positivo" <?php if($datos[0]['dau_alcoholemia_resultado']=='P'){ echo "checked";}?> value="P">
                            &nbsp;&nbsp;-&nbsp;&nbsp;<input type="radio" name="resultado" id="negativo" <?php if($datos[0]['dau_alcoholemia_resultado']=='N'){ echo "checked";}?> value="N">                                
                        </div>
                    </div>
                    <!-- Variables ocultas -->
                    <?php
                        $dau_alcoholemia_fecha = date("Y-m-d",strtotime($datos[0]['dau_alcoholemia_fecha']));
                        $dau_alcoholemia_hora = date("H:i:s", strtotime($datos[0]['dau_alcoholemia_fecha']));
                     ?>
                    <!-- Fecha y hora de atención -->
                    <input type="hidden" name="inpH_atencion_fecha"  id="inpH_atencion_fecha"   value="<?=date('d/m/Y',strtotime($datos[0]['dau_admision_fecha']))?>">
                    <input type="hidden" name="inpH_atencion_hora"   id="inpH_atencion_hora"    value="<?=date('H:i:s',strtotime($datos[0]['dau_admision_fecha']))?>">
                    <!-- Fecha y hora actual -->
                    <input type="hidden" name="inpH_horaActual"      id="inpH_horaActual"   value="<?=$horaActualAlcoholemia?>">
                    <input type="hidden" name="inpH_FechaActual"     id="inpH_FechaActual"  value="<?=$fechaActualAlcoholemia?>">
                    <!-- Input a guardar fecha y hora ingresadas -->
                    <input type="hidden"  name="horaAcoholemia" id="horaAcoholemia">
                    <div class="row mt-2">
                        <!-- Fecha -->
                        <div class="col-sm-3">
                            <label class="encabezado">Fecha:</label>
                        </div>                  
                        <div class="col-sm-9 input-group-date" id="date_fecha_alcoholemia">
                            <input type="text"  class="form-control form-control-sm mifuente" placeholder="DD-MM-AA" id="frm_fecha_alcoholemia" min="<?=date('Y-m-d',strtotime($datos[0]['dau_admision_fecha']))?>"  max="<?=$fechaActualAlcoholemiaMax?>" name="frm_fecha_date" value="<?php echo ($datos[0]['dau_alcoholemia_fecha']) ? date("d/m/Y",strtotime($datos[0]['dau_alcoholemia_fecha'])) : ""; ?>">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <!-- Hora -->
                        <div class="col-sm-3">
                            <label class="encabezado">Hora:</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text"  class="form-control form-control-sm mifuente" placeholder="HH:MM" id="frm_hora_alcoholemia" min="<?=date('H:i:s',strtotime($datos[0]['dau_admision_fecha']))?>" max="<?=$horaActualAlcoholemia?>" name="frm_hora_date" value="<?php echo ($datos[0]['dau_alcoholemia_fecha']) ? $dau_alcoholemia_hora : ""; ?>" onClick="this.value=''">  
                        </div>
                    </div>
                    <div class="row mt-2">
                        <?php
                        $fechaAlcoholemia   = $datos[0]['dau_alcoholemia_fecha'];
                        $profesional        = '';
                        if ( existeAlcoholemia( $fechaAlcoholemia ) ) {
                            $profesionalesAlcoholemia = $objDau->profesionalAlcoholemia($objCon, $_POST['Iddau']);
                            $profesional = profesionalQueRegistroAlcoholemia($profesionalesAlcoholemia);
                        } else {
                            $placeHolder = 'Profesional No Registrado';
                        }
                        ?>
                        <!-- Profesional -->
                        <div class="col-sm-3">
                            <label class="encabezado">Profesional:</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text"  class="form-control form-control-sm mifuente " id="frm_profesional_alcoholemia" name="frm_profesional_alcoholemia" value="<?php echo $profesional; ?>" placeholder='<?php echo $placeHolder; ?>' >
                        </div>
                        <!-- Observación -->
                        <div class="col-sm-3 mt-2">
                            <label class="encabezado">Observacion:</label>
                        </div>
                        <div class="col-sm-9 mt-2">
                            <input type="text"  class="form-control form-control-sm mifuente " id="frm_observacion_alcoholemia" name="frm_observacion_alcoholemia" value="<?=$datos[0]['dau_alcoholemia_apreciacion']?>" placeholder="Ingrese Observacion">
                        </div>
                    </div>
                </fieldset>
            </div>
        </div> 
    </div>
    <hr>


    <!-- *********************************************************************
                            GLOSA EGRESO / DERIVACIÓN 
    **************************************************************************
    --> 
    <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i> Glosa Egreso / Derivación</b></h6>                                      
    <div class="row">
        <div class="col-md-12" >
            <div class="row">
                <div class="col-sm-1">
                    <label class="encabezado">Egreso</label>
                </div>
                <div class="col-sm-1">
                    <div class="form-check">
                        <?php
                        $chk = "";
                        if ($datos[0]['est_id']=='5') {
                            $chk = "checked";
                        }else{
                            if ($datos[0]['dau_cierre_administrativo'] == 'S') {
                                if ($datos[0]['dau_indicacion_egreso']==3 || $datos[0]['dau_indicacion_egreso']==4 || $datos[0]['dau_indicacion_egreso']==6 ){
                                    $chk = "checked";
                                }
                            }
                        }
                    ?>
                        <input type="radio" class="form-check-input" name="radio_egreso" id="frm_egreso" <?=$chk;?> value="5"  disabled>
                        <label class="form-check-label" for="frm_anula"></label>
                    </div>
                </div>
                <?php if ($datos[0]['dau_indicacion_egreso']==3 || $datos[0]['dau_indicacion_egreso']==4 || $datos[0]['dau_indicacion_egreso']==6 ){?>
                    <div class="col-sm-2">
                        <select class="form-control form-control-sm mifuente " id='frm_indicacion_egreso' name="frm_indicacion_egreso" disabled style="width: 100%;">  
                            <option value="">Seleccione</option>                            
                            <?php for ($i=0; $i <count($listarIndicacion) ; $i++) { ?>
                                <option value="<?=$listarIndicacion[$i]['ind_egr_id']?>" <?php if($datos[0]['dau_indicacion_egreso']==$listarIndicacion[$i]['ind_egr_id']){ echo "selected";}?>> <?=$listarIndicacion[$i]['ind_egr_descripcion']?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <!-- Destino -->
                    <div class="col-sm-1">
                        <label class="encabezado mifuente">Destino</label>
                    </div>
                    <div class="col-sm-2">
                        <label class="mifuente ">:&nbsp;&nbsp;
                        <?php if ($datos[0]['dau_indicacion_egreso']==3){
                            echo $deri;
                        }else if($datos[0]['dau_indicacion_egreso']==4){ 
                            echo $datos[0]['servicio'];
                        }else if($datos[0]['dau_indicacion_egreso']==6){
                            echo $datos[0]['servicio'];
                        }?>
                        </label>
                    </div>
                    <?php
                    if($datos[0]['dau_cierre_atl_der_id']==1){ ?>
                    <?php } else if($datos[0]['dau_cierre_atl_der_id']==2){?>
                        <div class="col-sm-1">
                            <label class="encabezado mifuente">Especialidad</label>
                        </div>
                        <div class="col-sm-4">
                            <label class="mifuente">:&nbsp;&nbsp;<?=$esp;?></label>
                        </div>
                    <?php } else if($datos[0]['dau_cierre_atl_der_id']==3){?>
                        <div class="col-sm-1">
                            <label class="encabezado mifuente">Aps</label>
                        </div>
                        <div class="col-sm-4">
                            <label class="mifuente">:&nbsp;&nbsp;<?=$aps;?></label>
                        </div>
                    <?php } else if($datos[0]['dau_cierre_atl_der_id']==3){?>
                        <div class="col-sm-1">
                            <label class="encabezado mifuente">Otros</label>
                        </div>
                        <div class="col-sm-4">
                            <label class="mifuente">:&nbsp;&nbsp;<?=$datos[0]['dau_cierre_ind_otros'];?></label>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <div class="row">
    <!-- Anula DAU y NEA -->
                <div class="col-sm-1">
                    <label class="encabezado mifuente">Anula DAU</label><br>
                    <label class="encabezado mifuente">N.E.A</label>
                </div>
                <div class="col-sm-1">
                    <div class="form-check mt-0">
                        <input type="radio" class="form-check-input" name="radio_egreso" id="frm_anula" 
                            <?php if ($datos[0]['est_id'] == '6') echo "checked"; ?> value="6" disabled>
                        <label class="form-check-label" for="frm_anula"></label>
                    </div>
                    <br>
                    <div class="form-check mt-2">
                        <input type="radio" class="form-check-input" name="radio_egreso" id="frm_nea" 
                            <?php if ($datos[0]['est_id'] == '7') echo "checked"; ?> value="7" disabled>
                        <label class="form-check-label" for="frm_nea"></label>
                    </div>
                </div>
                <?php if ($datos[0]['est_id'] == '6' || $datos[0]['est_id'] == '7') { ?>
                    <div class="col-sm-10">
                        <textarea class="form-control form-control-sm mifuente" rows="3" 
                            id="frm_motivo_egreso" name="frm_motivo_egreso" placeholder="" disabled>
                            <?= $datos[0]['dau_cierre_administrativo_observacion'] ?>
                        </textarea>
                    </div>
                <?php } ?>
            </div>
        <!-- </div>
    </div> -->
    <?php if($datos[0]['dau_indicacion_egreso']==4 || $datos[0]['dau_indicacion_egreso']==6 ){?>
    <hr>
    <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i> Brazalete Identificatorio</b></h6>    
    <div class="row"   id="contenidoBrazalete">
        <div class="col-sm-2">
            <label class="encabezado">Tamaño brazalete:</label>
        </div>
        <div class="col-sm-2">
            <select class="form-control form-control-sm mifuente " id='frm_brazalette' name="frm_brazalette">
                <option value="">Seleccione</option>   
                <?php for ($i=0; $i <count($listaTipoAtencion) ; $i++) { ?>
                    <option value="<?=$listaTipoAtencion[$i]['ate_id']?>" <?php if($datos[0]['dau_atencion'] == $listaTipoAtencion[$i]['ate_id']){echo "selected";}?> ><?=$listaTipoAtencion[$i]['ate_descripcion']?> </option>
                <?php   } ?>
            </select>
        </div>
        <div class="col-sm-2">
            <button id="<?=$datos[0]['dau_id']?>" class="btn  col-lg-12 btn-primary btn-sm mifuente generaBrazaleteBtn">Generar Brazalete</button>
        </div>
        <?php if($datos[0]['dau_indicacion_egreso']==4){?>
        <div class="col-sm-2" id="btn_genInfDEIS">
            <button id="<?=$datos[0]['dau_id']?>" class="btn col-lg-12 btn-primary btn-sm mifuente generaInformeDEIS">Generar Informe DEIS</button>
        </div>
        <?php } ?>
    </div>
    <?php if($datos[0]['dau_indicacion_egreso']==6){?>
    <hr>
    <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i>Fallecimiento</b></h6>    
    <div class="row"   id="contenidoFallecimiento">         
        <div class="col-sm-2">                                
            <label class="encabezado">Fecha:</label>                            
        </div>
        <div class="col-sm-6">
            <div class="input-group">
                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
                <input type="text"  class="form-control form-control-sm mifuente  date" id="frm_fallecimiento_fecha" name="frm_fallecimiento_fecha" placeholder="DD-MM-AA" value="<?=$datos[0]['dau_defuncion_fecha']?>" disabled>
            </div>
        </div>
        <div class="col-sm-2">
            <label class="encabezado">Destino:</label>
        </div>
        <div class="col-sm-5">
            <div class="input-group">
                <div id="frm_destino" name="frm_destino">
                    <input type="radio" name="frm_destino_defuncion" id="frm_destino_defuncion" disabled value="1" <?php if($destinoControl == 7){echo "checked";}?>> ANATO.PATOLOGICA<br>
                    <input type="radio" name="frm_destino_defuncion" id="frm_destino_defuncion" disabled value="2" <?php if($destinoControl == 8){echo "checked";}?>> SERV.MED.LEGAL
                </div>
            </div>
        </div>
    </div>
        <?php } ?>
    <?php } ?>
    <!-- </div> -->
</form> 
<?php

//Funciones PHP
function existeAlcoholemia ( $fechaAlcoholemia ) {

    if ( $fechaAlcoholemia != '' && $fechaAlcoholemia != NULL && $fechaAlcoholemia ) {

        return true;
    }

    return false;
}



function profesionalQueRegistroAlcoholemia( $profesionalesAlcoholemia ) {

    if ( $profesionalesAlcoholemia['usuarioModificaAtencion'] != '' && $profesionalesAlcoholemia['usuarioModificaAtencion'] != NULL  ) {

        return $profesionalesAlcoholemia['usuarioModificaAtencion'];

    }

    return $profesionalesAlcoholemia['usuarioIniciaAtencion'];

}
?>