<?php
session_start();
error_reporting(0);


require("../../../config/config.php");
$permisos      = $_SESSION['permisosDAU'.SessionName];
$permisoPerfil = $_SESSION['permiso'.SessionName];
require_once('../../../class/Connection.class.php');       $objCon            = new Connection; $objCon->db_connect();
require_once('../../../class/Util.class.php');             $objUtil           = new Util;
require_once('../../../class/Nacionalidad.class.php');     $objNacionalidad   = new Nacionalidad;
require_once('../../../class/Prevision.class.php');        $objPrevision      = new Prevision;
require_once('../../../class/Convenio.class.php');         $objConvenio       = new Convenio;
require_once('../../../class/MedioLlegada.class.php');     $objLlegada        = new Mediollegada;
require_once('../../../class/MotivoConsulta.class.php');   $objMotivo         = new MotivoConsulta;
require_once('../../../class/Admision.class.php');         $objAdmision       = new Admision;
require_once('../../../class/Config.class.php');           $Config            = new Config;
require_once('../../../class/Localidad.class.php');        $objLocalidad      = new Localidad;
require_once('../../../class/Sector.class.php');           $objSector         = new Sector;
require_once('../../../class/SqlDinamico.class.php');      $objSqlDinamico    = new SqlDinamico;

$parametros                = $objUtil->getFormulario($_POST);
$cargarNacionalidad        = $objNacionalidad->listarNacionalidad($objCon,"");
$cargarPrevisionSinFonasas = $objPrevision->listarPrevisionSinFonasa($objCon);
$cargarPrevision           = $objPrevision->listarPrevision($objCon,"");
$cargarConvenio            = $objConvenio->listarConvenio($objCon,"");
$cargarMedios              = $objLlegada->listarMediollegada($objCon,"");
$cargarMotivos             = $objMotivo->listarMotivo($objCon,"");
$cargarMordedura           = $objAdmision->cargarModerdeduras($objCon);
$cargarIntoxicacion        = $objAdmision->cargarIntoxicacion($objCon);
$cargarQuemadura           = $objAdmision->cargarQuemado($objCon);
$cargarAtencion            = $objAdmision->listarAtencion($objCon);
$cargarTransito            = $objAdmision->listarTransito($objCon);
$parametrosSelect = [];

// $cargarReligion   = $objSqlDinamico->generarSelect($objCon, 'paciente.religion', $parametrosSelect, " order by rlg_descripcion asc");

$cargarEtnia               = $objAdmision->listarEtnia($objCon);

$cargarConsultorios        = $objAdmision->listarConsultorios($objCon);

$parametros['dau_id']      = $parametros['id'];
$datos                     = $objAdmision->listarDatosDau($objCon,$parametros);

$cargarPaisNacimiento      = $objAdmision->listarPaisNacimiento($objCon);
$cargarRegiones            = $objLocalidad->listarRegiones($objCon);
$cargarCiudades            = $objLocalidad->listarCiudades($objCon);
$cargarComunas             = $objLocalidad->listarComunas($objCon);
$cargarSectorDomicilio     = $objSector->listarSectorDomicilio($objCon);
$cargarTipoMordedura       = $objAdmision->cargarTipoModerdeduras($objCon);
$idsuario                  = $objUtil->usuarioActivo();
$permisosPerfil            = $Config->cargarPermisoDau($objCon,$idsuario);

$version                = $objUtil->versionJS();


$transexual_bd   = $datos[0]["transexual"];
$nombreSocial_bd = $datos[0]["nombreSocial"];
$resultado       = $objUtil->vista_nombre_admisionUpdateDetalle($transexual_bd, $nombreSocial_bd);
// print('<pre>');  print_r($resultado);  print('</pre>');
$mostrar         = $resultado['mostrar'];
$classCol        = $resultado['classCol'];



?>



<!--
################################################################################################################################################
                                                                    ARCHIVO JS
-->
<script type="text/javascript" charset="utf-8" src="<?=PATH?>/controllers/client/admision/admisionUpdateDetalle.js?v=<?=$version;?>"></script>
<!-- <script type="text/javascript" src="<?=PATH?>/assets/libs/jQuery.print/jQuery.print.js"></script> -->




<!--
################################################################################################################################################
                                                                    ESTILOS
-->
<style>
  
</style>



<!--
################################################################################################################################################
                                                        DESPLIGUE DETALLES DE ADMISIÓN
-->

<!-- *********************************************************************
                                Título
**************************************************************************
-->
<div class="row form-group">
    <div class="col-lg-10   text-secondary  " style="font-size: 20px;">
        
        <button type="button"  id="btnVolver" class="btn margin-6 btn-sm btn-outline-primary volverWorklist_detalle mifuente12"><i class="fas fa-chevron-left "></i>&nbsp;&nbsp;Atrás</button>
        &nbsp;&nbsp; Detalle Admisión #<?= $parametros['id'];?>     
    </div>
</div>
<input type="hidden" id="FOLIO" value="<?= $parametros['id'];?>">
<!-- <div class="row">

    <div class="col-lg-12">

        <div class="btn_volver">

            <button id="btnVolver" type="button" class="btn btn-primary"><i class="fa fa-arrow-left"></i></button>

        </div>

        <h3 class="titulos"><span>Detalle Admisión <label id="FOLIO" style="font-size: 23px;"> <?= $parametros['id'];?> </label></span></h3>

    </div>

</div> -->


<!-- *********************************************************************
                            Formulario
**************************************************************************
-->
<style>
    /* Estilos personalizados para diferentes tamanhos de tela */
    @media (max-height: 576px) { /* Tela pequena */
      .ScrollStyleAdmision {
         max-height: calc(100vh - 520px);
        overflow-x: hidden;
      }
    }

    @media (min-height: 577px) and (max-height: 768px) { /* Tela média */
      .ScrollStyleAdmision {
         max-height: calc(100vh - 198px);
        overflow-x: hidden;
      }
    }

    @media (min-height: 769px) and (max-height: 992px) { /* Tela grande */
      .ScrollStyleAdmision {
         max-height: calc(100vh - 198px);
        overflow-x: hidden;
      }
    }
    @media (min-height: 993px) and (max-height: 1080px)  { /* Tela extra grande */
      .ScrollStyleAdmision {
         max-height: calc(100vh - 198px);
        overflow-x: hidden;
      }
    }
    @media (min-height: 1080px)  { /* Tela extra grande */
      .ScrollStyleAdmision {
         max-height: calc(100vh - 198px);
        overflow-x: hidden;
      }
    }
  </style>

<div class=" ScrollStyleAdmision ">

    <form id="frm_actualizar_pacienteDau" class=" mr-3 ml-3">

        <!-- Campos Ocultos -->
        <input type="hidden" name="idPacienteDau"            id="idPacienteDau"            value="<?=$parametros["datosPaciente"]["datos"][0]["id"]?>">
        <input type="hidden" name="frm_tipoAccidenteDetalle" id="frm_tipoAccidenteDetalle" value="<?=$datos[0]['dau_tipo_accidente']?>">
        <input type="hidden" name="frm_trabajoMutualidad"    id="frm_trabajoMutualidad"    value="<?=$datos[0]['dau_accidente_trabajo_mutualidad']?>">
        <input type="hidden" name="frm_InstitucionDellate"   id="frm_InstitucionDellate"   value="<?=$datos[0]['dau_accidente_escolar_institucion']?>">
        <input type="hidden" name="frm_produjoEn"            id="frm_produjoEn"            value="<?=$datos[0]['dau_accidente_hogar_lugar']?>">
        <input type="hidden" name="frm_otroLugar"            id="frm_otroLugar"            value="<?=$datos[0]['dau_accidente_otro_lugar']?>">
        <input type="hidden" name="motivoConsultaDetalle"    id="motivoConsultaDetalle"    value="<?=$datos[0]['dau_motivo_consulta']?>">
        <input type="hidden" name="frm_tipo_choque_id"       id="frm_tipo_choque_id"       value="<?=$datos[0]['dau_tipo_choque']?>">

        <!-- *********************************************************************
                            Datos Personales del Paciente
        **************************************************************************
        -->
        <!-- <fieldset class="col-md-12"> -->
            <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i> Datos Personales Paciente</b></h6>
            <!-- <legend>Datos Personales Paciente</legend> -->
            <!-- <div class="m-2"> -->
                <div class="row">
                    <div id="div_run" class="col-lg-3 ">
                        <label for="" class="control-label encabezado">RUN</label>
                        <input id="frm_rut" type="text" class="form-control form-control-sm mifuente12" name="frm_rut" placeholder="Número de RUN" disabled
                        <?php
                        if ( $datos[0]["extranjero"] != "S" ) {
                            if($datos[0]["rut"]){
                            ?>
                                value="<?=$objUtil->formatearNumero($datos[0]["rut"]).'-'.$objUtil->generaDigito($datos[0]["rut"])?>"
                            <?php }
                        } else {
                        ?> 
                        value="<?=$datos[0]["rut_extranjero"]?>"
                        <?php } ?>
                        >
                    </div>
                    <?php if ($mostrar == 1){ ?>
                        <div id="div_frm_nombreSocial" class="col-lg-2 " >
                            <label for="" class="control-label encabezado">Nombre Social</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <img class="" src="<?=PATH?>/assets/img/transIco.png" style="width: 11px;" >
                                </span>
                                <input id="frm_nombreSocial" type="text" class="form-control form-control-sm mifuente12" name="frm_nombreSocial" placeholder="Nombre Social" readonly value="<?php
                                if($datos[0]["nombreSocial"] == "(NULL)" || $datos[0]["nombreSocial"] == "" || $datos[0]["nombreSocial"] == null){
                                    echo "SIN NOMBRE SOCIAL";
                                }else{
                                    echo $datos[0]["nombreSocial"];
                                }
                                ?>" <?php if(!isset($datos[0]["nombreSocial"])){echo "";}?>>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- Nombre -->
                    <div id="div_nombres" class="col-lg-3 ">
                        <label for="" class="control-label encabezado">Nombres</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="frm_nombres" type="text" class="form-control form-control-sm mifuente12" name="frm_nombres" placeholder="Nombres" disabled value="<?=$datos[0]["nombres"]?>">
                        </div>
                    </div>
                    <!-- Apellido Paterno -->
                    <div id="div_apellidopat" class="col-lg-3 ">
                        <label for="" class="control-label encabezado">Apellido Paterno</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-menu-hamburger"></i></span>
                            <input id="frm_paterno" type="text" class="form-control form-control-sm mifuente12" name="frm_paterno" placeholder="Apellido Paterno" disabled value="<?=$datos[0]["apellidopat"]?>">
                        </div>
                    </div>
                    <!-- Apellido Materno -->
                    <div id="div_apellidomat" class="col-lg-3 ">
                        <label for="" class="control-label encabezado">Apellido Materno</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-menu-hamburger"></i></span>
                            <input id="frm_materno" type="text" class="form-control form-control-sm mifuente12" name="frm_materno" placeholder="Apellido Materno"  disabled value="<?=$datos[0]["apellidomat"]?>">
                        </div>
                    </div>
                    <!-- Fecha de Nacimiento -->
                    <div id="div_fecha_nac" class="col-lg-3 ">
                        <label for="" class="control-label encabezado">Fecha de Nacimiento</label>
                        <input type='text' class="form-control text-center form-control-sm mifuente12" id="frm_fechaNac" name="frm_fechaNac" disabled value="<?=date("d-m-Y", strtotime($datos[0]['fechanac']))?> | EDAD : <?=$objUtil->edadActualCompleto($datos[0]['fechanac'])?>">
                    </div>
                    <!-- Sexo -->
                    <div id="div_sexo" class="col-lg-2 ">
                        <label for="" class="control-label encabezado">Sexo</label>
                        <select class="form-control form-control-sm mifuente12" id='frm_sexo' name="frm_sexo" disabled>
                            <option value="">Seleccione Sexo</option>
                            <option value="M" <?php if($datos[0]["sexo"]=="M"){ echo "selected";}?>>MASCULINO</option>
                            <option value="F" <?php if($datos[0]["sexo"]=="F"){ echo "selected";}?>>FEMENINO</option>
                            <option value="O" <?php if($datos[0]["sexo"]=="O"){ echo "selected";}?>>INDETERMINADO</option>
                            <option value="D" <?php if($datos[0]["sexo"]=="D"){ echo "selected";}?>>DESCONOCIDO</option>
                        </select>
                    </div>
                    <!-- Pueblo Originario -->
                    <div id="div_Etnia" class="col-lg-2 ">
                        <label for="inputRUN" class="control-label encabezado">Pueblo Originario</label>
                        <select id="frm_etnia" name="frm_etnia" class="form-control form-control-sm mifuente12" disabled>
                            <option value="">Seleccione Etnia</option>
                            <?php
                            for ( $i = 0; $i < count($cargarEtnia); $i++ ) {
                            ?>
                            <option value="<?=$cargarEtnia[$i]['etnia_id']?>" <?php if($datos[0]["etnia"]==$cargarEtnia[$i]['etnia_id']){ echo "selected";}?>>
                                <?=$cargarEtnia[$i]['etnia_descripcion']?>
                            </option>

                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Afrodescendiente -->
                    <div id="div_afrodescendiente" class="col-lg-3 ">
                        <label for="" class="control-label encabezado">Afrodescendiente</label>
                        <select class="form-control form-control-sm mifuente12" id='frm_afrodescendiente' name="frm_afrodescendiente" disabled>
                            <option value="" <?php if($datos[0]["PACafro"]=="" || is_null($datos[0]["PACafro"])){ echo "selected";}?> >NO INFORMADO</option>
                            <option value="0" <?php if($datos[0]["PACafro"]=="0"){ echo "selected";}?> >NO es Afrodescendiente</option>
                            <option value="1" <?php if($datos[0]["PACafro"]=="1"){ echo "selected";}?> >SI es Afrodescendiente</option>
                        </select>
                    </div>
                    <!-- Prais -->
                    <div id="div_prais" class="col-lg-2 ">
                        <label for="" class="control-label encabezado">PRAIS</label>
                        <select class="form-control form-control-sm mifuente12" id='frm_prais' name="frm_prais" <?php if(!isset($datos[0]["prais"])){echo "";}?> disabled>
                            <option value="1" <?php if($datos[0]["prais"]=="" || is_null($datos[0]["prais"])){ echo "selected";}?> >NO INFORMADO</option>
                            <option value="0" <?php if($datos[0]["prais"]=="0"){ echo "selected";}?> >No</option>
                            <option value="1" <?php if($datos[0]["prais"]=="1"){ echo "selected";}?> >Si</option>
                        </select>
                    </div>
                    <div id="div_religion" class="col-lg-2 ">
                        <label for="frm_religion" class="control-label encabezado">Religión</label>
                        <select id="frm_religion" name="frm_religion" class="form-control form-control-sm mifuente12" disabled>
                            <option value="">Seleccione Religión</option>
                            <?php
                            for ( $i = 0; $i < count($cargarReligion); $i++ ) {
                            ?>
                            <option value="<?=$cargarReligion[$i]['rlg_id']?>" <?php if(!empty($datos[0]["religion"]) && $datos[0]["religion"] == $cargarReligion[$i]['rlg_id']){ echo "selected";}?>>
                                <?=$cargarReligion[$i]['rlg_descripcion']?>
                            </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <!-- inicio 02-07-24 -->
                    <div id="div_transexual" class="col-lg-2 ">
                        <label for="" class="control-label encabezado">Trangénero</label>
                        <select class="form-control form-control-sm mifuente12" id='frm_transexual' name="frm_transexual" <?php if(!isset($datos[0]["transexual"])){echo "";}?>>
                            <option value="0" <?php if($datos[0]["transexual"]=="N"){ echo "selected";}?> >No</option>
                            <option value="1" <?php if($datos[0]["transexual"]=="S"){ echo "selected";}?> >Si</option>
                        </select>
                    </div>
                    <?php
                    if($datos[0]["transexual"]=="S"){ ?>
                    <div id="div_nombre_legal" class="col-lg-2 ">
                        <label for="" class="control-label encabezado">Nombre Legal</label>
                        <select class="form-control form-control-sm mifuente12" id='frm_nombre_legal' name="frm_nombre_legal" <?php if(!isset($datos[0]["nombre_legal"])){echo "";}?>>
                            <option value="0" <?php if($datos[0]["nombre_legal"]=="N"){ echo "selected";}?> >No</option>
                            <option value="1" <?php if($datos[0]["nombre_legal"]=="S"){ echo "selected";}?> >Si</option>
                        </select>
                    </div>
                    <?php } ?>
                    <?php if($datos[0]["transexual"]=="S"){ ?>
                    <div id="div_identidadGenero" class="col-lg-2 ">
                        <label for="" class="control-label encabezado">Identidad de genero</label>
                        <select class="form-control form-control-sm mifuente12" id='frm_identidadGenero' name="frm_identidadGenero" <?php if(!isset($datos[0]["identidad_genero"])){echo "";}?>>
                            <option value="TF" <?php if($datos[0]["identidad_genero"]=="TF"){ echo "selected";}?> >Transexual Femenino</option>
                            <option value="TM" <?php if($datos[0]["identidad_genero"]=="TM"){ echo "selected";}?> >Transexual Masculino</option>
                            <option value="NB" <?php if($datos[0]["identidad_genero"]=="NB"){ echo "selected";}?> >No Binario</option>
                        </select>
                    </div>
                    <?php } ?>
                </div>
            <!-- </div> -->
        <!-- </fieldset> -->
                <hr style="margin-top: 0.6rem; margin-bottom: 0.6rem;">
        <!-- *********************************************************************
                                Localización y Contacto
        **************************************************************************
        -->
        <!-- <fieldset class="col-md-12"> -->
        <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i>Datos Localización y Contacto</b></h6>
        <!-- <div class=" m-2"> -->
        <div class="row">
            <!-- Centro de Atención Primaria -->
            <div id="" class="col-lg-3 ">
                <label for="inputRUN" class="control-label encabezado">Centro Atención Primaria</label>
                <select id="frm_centroAtencion" name="frm_centroAtencion" class="form-control form-control-sm mifuente12" disabled>
                    <option value="">Seleccione Centro</option>
                    <?php
                    for ( $i = 0; $i < count($cargarConsultorios); $i++ ) {
                    ?>
                    <option value="<?=$cargarConsultorios[$i]['con_id']?>" <?php if($datos[0]["centroatencionprimaria"]==$cargarConsultorios[$i]['con_id']){ echo "selected";}?>>
                        <?=$cargarConsultorios[$i]['con_descripcion']?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <!-- Nacionalidad -->
            <div id="" class="col-lg-3 ">
                <label for="inputRUN" class="control-label encabezado">Nacionalidad</label>
                <select id="frm_Nacionalidad" name="frm_Nacionalidad" class="form-control form-control-sm mifuente12" disabled>
                    <option value="NOI" <?php if($datos[0]["nacionalidad"]=="NOI" || $datos[0]["nacionalidad"]=="" ){ echo "selected";}?>>NO INFORMADA</option>
                    <?php
                    for ( $i = 0; $i < count($cargarNacionalidad); $i++ ) {
                    ?>
                        <option value="<?=$cargarNacionalidad[$i]['NACcodigo']?>" <?php if($datos[0]["nacionalidad"]==$cargarNacionalidad[$i]['NACcodigo']){echo "selected";}?>>
                            <?=$cargarNacionalidad[$i]['NACdescripcion']?>
                        </option>
                    <?php  } ?>
                </select>
            </div>
            <!-- País de Nacimiento -->
            <div id="div_pais_nacimiento" class="col-lg-3 ">
                <label for="" class="control-label encabezado">País de Nacimiento</label>
                <select class="form-control form-control-sm mifuente12" id='frm_pais_nacimiento' name="frm_pais_nacimiento" disabled>
                    <option value="" <?php if($datos[0]["paisNacimiento"]=="" || is_null($datos[0]["paisNacimiento"])){ echo "selected";}?> >NO INFORMADA</option>
                    <?php
                    for ( $i = 0; $i < count($cargarPaisNacimiento); $i++ ) {
                    ?>
                    <option value="<?=$cargarPaisNacimiento[$i]['NACcodigo']?>" <?php if($datos[0]["paisNacimiento"]==$cargarPaisNacimiento[$i]['NACcodigo']){echo "selected";}?> >
                            <?=$cargarPaisNacimiento[$i]['NACpais']?>
                    </option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="row">
            <!-- Región -->
            <div id="" class="col-lg-3 ">
                <label for="inputRegion" class="control-label encabezado">Región</label>
                <select id="frm_region" name="frm_region" class="form-control form-control-sm mifuente12" disabled>
                    <option value="" <?php if($datos[0]["region"]=="" || is_null($datos[0]["region"])){echo "selected";}?> > IGNORADA </option>
                    <?php for ( $i = 0; $i < count($cargarRegiones); $i++ ) { ?>
                    <option value="<?=$cargarRegiones[$i]['REG_Id']?>" <?php if($datos[0]["region"]==$cargarRegiones[$i]['REG_Id']){echo "selected";}?> >
                        <?=$cargarRegiones[$i]['REG_Descripcion']?>
                    </option>
                    <?php  } ?>
                </select>
            </div>
            <!-- Ciudad -->
            <div id="divSeleccionCiudades" class="col-lg-3 ">
                <label for="inputCiudad" class="control-label encabezado">Ciudad</label>
                <select id="frm_ciudad" name="frm_ciudad" class="form-control form-control-sm mifuente12" disabled>
                    <option value="" <?php if($datos[0]["ciudad"]=="" || is_null($datos[0]["ciudad"])){echo "selected";}?> > IGNORADA </option>
                    <?php
                    for ( $i = 0; $i < count($cargarCiudades); $i++ ) {
                    ?>
                    <option value="<?=$cargarCiudades[$i]['CIU_Id']?>" <?php if($datos[0]["ciudad"]==$cargarCiudades[$i]['CIU_Id']){echo "selected";}?> >
                        <?=$cargarCiudades[$i]['CIU_Descripcion']?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <!-- Comuna -->
            <div id="divSeleccionComunas" class="col-lg-3 ">
                <label for="inputComuna" class="control-label encabezado">Comuna</label>
                <select id="frm_comuna" name="frm_comuna" class="form-control form-control-sm mifuente12" disabled >
                    <option value="" <?php if($datos[0]["idcomuna"]=="" || is_null($datos[0]["idcomuna"])){echo "selected";}?> > IGNORADA </option>
                    <?php for ( $i = 0; $i < count($cargarComunas); $i++ ) { ?>
                    <option value="<?=$cargarComunas[$i]['id']?>" <?php if($datos[0]["idcomuna"]==$cargarComunas[$i]['id']){echo "selected";}?> >
                        <?=$cargarComunas[$i]['comuna']?>
                    </option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="row">
            <!-- Nombre Calle -->
            <div id="div_nombreCalle" class="col-lg-3 ">
                <label for="inputRUN" class="control-label encabezado">Nombre Calle</label>
                <input id="frm_nombreCalle" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_nombreCalle" disabled
                    <?php if ( $datos[0]["calle"] ) { ?>
                        value="<?=$datos[0]["calle"]?>"
                    <?php  } else {
                     if ( $datos[0]["calle"]=="" || is_null($datos[0]["calle"]) ) { ?>

                        value="NO INFORMADA" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;"
                    <?php } 
                    } ?> >
            </div>
            <!-- Número Dirección -->
            <div id="div_numeroDireccion" class="col-lg-2 ">
                <label for="inputRUN" class="control-label encabezado">Número Dirección</label>
                <input id="frm_numeroDireccion" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_numeroDireccion" disabled 
                <?php if ( $datos[0]["numero"] ) { ?>
                    value="<?=$datos[0]["numero"]?>"
                <?php } else {
                if ( $datos[0]["numero"]=="" || is_null($datos[0]["numero"]) ) {  ?>
                    value="NO INFORMADA" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;"
                <?php } 
                } ?> >
            </div>
            <!-- Resto de Dirección -->
            <div id="div_direccion" class="col-lg-3 " >
                <label for="inputRUN" class="control-label encabezado">Resto Dirección</label>
                <input id="frm_direccion" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_direccion" disabled
                    <?php
                    if ( $datos[0]["restodedireccion"] ) {
                    ?>
                        value="<?=$datos[0]["restodedireccion"]?>"

                    <?php
                    } else {
                        if ( ( $datos[0]["restodedireccion"] == "" || is_null($datos[0]["restodedireccion"]) ) &&  ( ! is_null($datos[0]['dau_paciente_domicilio']) || ! empty($datos[0]['dau_paciente_domicilio']) ) ) {
                        ?>
                            value="<?php echo $datos[0]['dau_paciente_domicilio']; ?>"
                        <?php
                        } else if ( ($datos[0]["restodedireccion"] == "" || is_null($datos[0]["restodedireccion"]) ) &&  ( is_null($datos[0]['dau_paciente_domicilio'] ) || empty($datos[0]['dau_paciente_domicilio']) ) ) {
                        ?>
                            value="No Informada" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;"
                        <?php
                        }
                    }
                    ?>
                >
            </div>
            <!-- Sector de Domicilio -->
            <div id="" class="col-lg-2 " >
                <label for="inputRUN" class="control-label encabezado">Sector de Domicilio</label>
                <select id="frm_sectorDomicilio" name="frm_sectorDomicilio" class="form-control form-control-sm mifuente12" disabled>
                    <option value="" <?php if($datos[0]["sector_domicilio"]=="" || is_null($datos[0]["sector_domicilio"])){echo "selected";}?> > IGNORADA </option>
                    <?php
                    for ( $i = 0; $i < count($cargarSectorDomicilio); $i++ ) {
                    ?>
                    <option value="<?=$cargarSectorDomicilio[$i]['id_sector_domiciliario']?>" <?php if($datos[0]["sector_domicilio"]==$cargarSectorDomicilio[$i]['id_sector_domiciliario']){echo "selected";}?>>
                        <?=$cargarSectorDomicilio[$i]['descripcion_sector_domiciliario']?>
                    </option>
                    <?php  } ?>
                </select>
            </div>
            <!-- Tipo de Domicilio -->
            <div id="" class="col-lg-2 ">
                <label for="inputRUN" class="control-label encabezado">Tipo de Domicilio</label>
                <select id="frm_tipoDomicilio" name="frm_tipoDomicilio" class="form-control form-control-sm mifuente12" disabled>
                    <option value=""  <?php if($datos[0]["dau_paciente_domicilio_tipo"]=="" || is_null($datos[0]["dau_paciente_domicilio_tipo"])){echo "selected";}?> > IGNORADA </option>
                    <option value='U' <?php if($datos[0]["dau_paciente_domicilio_tipo"]=="U"){ echo "selected";}?>>Urbano</option>
                    <option value='R' <?php if($datos[0]["dau_paciente_domicilio_tipo"]=="R"){ echo "selected";}?>>Rural</option>
                </select>
            </div>
        </div>
        <div class="row">
            <!-- Correo Electrónico -->
            <div id="div_correo_elect" class="col-lg-3 ">
                <div id="mensajesCorreo" style="display: auto;"></div>
                <label for="" class="control-label encabezado">Correo Electrónico<i class="ml-2 text-danger fas fa-envelope"></i></label>
                <input id="frm_correo" type="email" class="form-control form-control-sm mifuente12" name="frm_correo" disabled placeholder="Correo Electrónico" required value="<?=$datos[0]["email"]?>">
            </div>
            <!-- Teléfono Celular -->
            <div id="div_telefono_cel" class="col-lg-3 ">
                <div id="mensajesTelCel" style="display: auto;"></div>
                <label for="" class="control-label encabezado">Número de Teléfono Celular<i class="ml-2 text-danger fas fa-mobile-alt"></i></label>
                <input id="frm_telefonoCelular" type="text" class="form-control form-control-sm mifuente12" name="frm_telefonoCelular" disabled placeholder="Ejemplo: 98765432" required value="+56 9 <?=$datos[0]["fono1"]?>">
            </div>
            <!-- Teléfono Fijo -->
            <div id="div_telefono_fijo" class="col-lg-3 ">
                <div id="mensajesTelFijo" style="display: auto;"></div>
                <label for="" class="control-label encabezado">Número de Teléfono Fijo<i class="ml-2 text-danger fas fa-mobile-alt"></i></label>
                <input id="frm_telefonoFijo" type="text" class="form-control form-control-sm mifuente12" name="frm_telefonoFijo" disabled placeholder="Ejemplo: 582223344" value="<?=$datos[0]["PACfono"]?>">
            </div>
            <!-- Otros Teléfonos -->
            <div id="div_otrosTelefonos" class="col-lg-3 ">
                <div id="mensajesOtrosTel" style="display: auto;"></div>
                <label for="" class="control-label encabezado">Otros Teléfonos<i class="ml-2 text-danger fas fa-mobile-alt"></i></label>
                <input id="frm_otrosTelefonos" type="text" class="form-control form-control-sm mifuente12" name="frm_otrosTelefonos" <?php if(!isset($datos[0]["PACfonoOtros"])){echo "";}?> disabled placeholder="Ejemplo: Trabajo - 582223344" value="<?=$datos[0]["PACfonoOtros"]?>">
            </div>
        </div>
            <!-- </div> -->
        <!-- </fieldset> -->
        <hr style="margin-top: 0.6rem; margin-bottom: 0.6rem;">
        <!--
        **************************************************************************
                                Datos Epidemiológicos
        **************************************************************************
        -->
        <!-- <fieldset class="col-md-12"> -->
        <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i>Datos Epidemiológicos</b></h6>
            <!-- <div class="m-2"> -->
        <div class="row">
            <input type="hidden" id="viajeOProcedencia" value="<?php echo $datos[0]['dau_viaje_epidemiologico']; ?>">
            <input type="hidden" id="pais" value="<?php echo $datos[0]['dau_pais_epidemiologia']; ?>">
            <input type="hidden" id="observacion" value="<?php echo $datos[0]['dau_observacion_epidemiologica']; ?>">
            <!-- Viaje o procedencia del extranjero -->
            <div id="" class="col-md-4 " >
                <label for="" class="control-label encabezado">¿Viaje o procedencia del extranjero en el último mes?</label>
                <select id="frm_viajeEpidemiologico" name="frm_viajeEpidemiologico" class="form-control form-control-sm mifuente12" disabled>
                    <option value="" selected disabled>Seleccione Opción</option>
                    <option value="N" <?php echo ($objUtil->existe($datos[0]["dau_viaje_epidemiologico"]) && $datos[0]["dau_viaje_epidemiologico"] == "N") ? "selected" : null; ?> >No</option>
                    <option value="S" <?php echo ($objUtil->existe($datos[0]["dau_viaje_epidemiologico"]) && $datos[0]["dau_viaje_epidemiologico"] == "S") ? "selected" : null; ?> >Si</option>
                </select>
            </div>
            <!-- País -->
            <div id="divPaisEpidemiologia" class="col-lg-3 ">
                <label for="" class="control-label encabezado">País</label>
                <select class="form-control form-control-sm mifuente12" id='frm_paisEpidemiologia' name="frm_paisEpidemiologia" disabled>
                    <option value="" selected disabled="disabled">Seleccione País</option>
                    <?php
                    for ( $i = 0; $i < count($cargarPaisNacimiento); $i++ ) {
                    ?>

                    <option value="<?php echo $cargarPaisNacimiento[$i]['NACcodigo']; ?>" <?php echo ($objUtil->existe($datos[0]["dau_viaje_epidemiologico"]) && $datos[0]["dau_pais_epidemiologia"] == $cargarPaisNacimiento[$i]['NACcodigo']) ? "selected" : null; ?> ><?php echo $cargarPaisNacimiento[$i]['NACpais']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <!-- Observaciones -->
            <div id="divObservacionesEpidemiologia" class="col-md-5 ">
                <label for="" class="control-label encabezado">Observaciones</label>
                <input onkeypress="return limitaCampoTexto(event, 500, 'frm_observacionEpidemiologica');" onkeyup="actualizaInfoTexto(500, 'frm_observacionEpidemiologica', 'info_frm_observacionEpidemiologica')" onDrop="return false" maxlength="500" id="frm_observacionEpidemiologica" onDrop="return false" type="text" class="form-control form-control-sm mifuente12" name="frm_observacionEpidemiologica" placeholder="Ingrese Observación" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;" value="<?php echo $datos[0]["dau_observacion_epidemiologica"]; ?>" disabled>
            </div>
        </div>
            <!-- </div> -->
        <!-- </fieldset> -->
        <hr style="margin-top: 0.6rem; margin-bottom: 0.6rem;">
        <!-- *********************************************************************
                                Datos Previsionales
        **************************************************************************
        -->
        <!-- <fieldset class="col-md-12"> -->
        <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i>Datos Previsionales</b></h6>
        <!-- <div class="m-2"> -->
        <div class="row">
            <!-- Previsión -->
            <div id="" class="col-lg-3 ">
                <label for="" class="control-label encabezado">Prevision</label>
                <?php
                if ( $datos[0]["dau_paciente_prevision"] == 0 || $datos[0]["dau_paciente_prevision"] == 1 || $datos[0]["dau_paciente_prevision"] == 2 || $datos[0]["dau_paciente_prevision"] == 3 ) {
                ?>
                <select id="frm_previson" name="frm_previson" class="form-control form-control-sm mifuente12" disabled>
                    <option value="">Seleccione Prevision</option>
                        <?php
                        for ( $i = 0; $i < count($cargarPrevision); $i++ ) {
                        ?>
                        <option value="<?=$cargarPrevision[$i]['id']?>"
                            <?php if($datos[0]["dau_paciente_prevision"]==$cargarPrevision[$i]['id']){
                                echo "selected";
                            } ?> >
                        <?=$cargarPrevision[$i]['prevision']?>
                        </option>
                    <?php }  ?>
                </select>
                <?php
                } else {
                ?>
                <select id="frm_previson" name="frm_previson" class="form-control form-control-sm mifuente12" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;">
                        <option value="">Seleccione Prevision</option>
                        <?php
                        for ( $i = 0; $i < count($cargarPrevisionSinFonasas); $i++ ) {
                        ?>
                        <option value="<?=$cargarPrevisionSinFonasas[$i]['id']?>"
                            <?php if($datos[0]["dau_paciente_prevision"]==$cargarPrevisionSinFonasas[$i]['id']){
                                echo "selected";}
                            ?>
                        >
                        <?=$cargarPrevisionSinFonasas[$i]['prevision']?>
                        </option>
                    <?php
                    }
                    ?>
                </select>
                <?php }  ?>
            </div>
            <!-- Forma de pago -->
            <div id="" class="col-lg-3 ">
                <label for="" class="control-label encabezado">Forma de Pago</label>
                <select id="frm_formaPago" name="frm_formaPago" class="form-control form-control-sm mifuente12" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;" >
                    <option value="">Seleccione Forma de Pago</option>
                    <?php
                    for ( $i = 0; $i < count($cargarConvenio); $i++ ) {
                        if ( $cargarMedios[$i]['med_id'] == 20 ) {
                            continue;
                        }
                    ?>
                        <option value="<?=$cargarConvenio[$i]['instCod']?>" <?php if($datos[0]["dau_paciente_forma_pago"]==$cargarConvenio[$i]['instCod']){echo "selected";}?> >
                            <?=$cargarConvenio[$i]['instNombre']?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>
            <!-- </div> -->
        <!-- </fieldset> -->
        <hr style="margin-top: 0.6rem; margin-bottom: 0.6rem;">
        <!-- *********************************************************************
                                Datos de Admisión
        **************************************************************************
        -->
        <!-- <fieldset class="col-md-12"> -->
        <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i>Datos de Admisión</b></h6>
        <!-- <legend>Datos de Admisión</legend> -->
        <!-- <div class=""> -->
        <div class="" id="DivAdmision">
            <div class="row">
                <?php
                $resultadoEstablecimientoRedSalud       = $objAdmision->listarConsultorios($objCon, 'filtroAdmisionPacienteDerivado');
                $pacienteDerivado                       = $objAdmision->obtenerInfoPacienteDerivadoSegunDau($objCon, $parametros['dau_id']);
                $totalResultadoEstablecimientosRedSalud = count($resultadoEstablecimientoRedSalud);
                if ( empty($pacienteDerivado) || is_null($pacienteDerivado) ) {
                    echo '<input type="hidden"      id="hiddenPacienteDerivado"         name="hiddenPacienteDerivado"           value="N">';
                }
                if ( ! empty($pacienteDerivado) && ! is_null($pacienteDerivado) ) {
                    echo '<input type="hidden"      id="hiddenPacienteDerivado"         name="hiddenPacienteDerivado"           value="S">';
                    echo '<input type="hidden"      id="hiddenEstablecimientoRedSalud"  name="hiddenEstablecimientoRedSalud"    value="'.$pacienteDerivado['idEstablecimientoRedSalud'].'">';
                    echo '<input type="hidden"      id="hiddenOtrosEstablecimientos"    name="hiddenOtrosEstablecimientos"      value="'.$pacienteDerivado['nombreOtroEstablecimiento'].'">';
                }
                echo '<input type="hidden"          id="hiddenPacienteCritico"          name="hiddenPacienteCritico"            value="'.$datos[0]['dau_paciente_critico'].'">';
                ?>
                <!-- ¿Paciente derivado? -->
                <div id="divPacienteDerivado" class="col-lg-3 ">
                    <label class="control-label encabezado">Derivado</label>
                    <select class="form-control form-control-sm mifuente12" name="slc_derivado" id="slc_derivado" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;">
                        <option value="0" disabled selected>¿Paciente es Derivado?</option>
                        <option value="N">No</option>
                        <option value="S">Si</option>
                    </select>
                </div>
                <!-- Establecimientos -->
                <div id="divEstablecimientos" class="col-lg-3  pacienteEsDerivado establecimientosRedSalud">
                    <label class="control-label encabezado">Establecimientos</label>
                    <select id="frm_establecimientosRedSalud" name="frm_establecimientosRedSalud" class="form-control form-control-sm mifuente12" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;">
                        <option value="" disabled selected>Seleccione Establecimiento</option>
                        <?php
                        for ( $i = 0; $i < $totalResultadoEstablecimientosRedSalud; $i++ ) {

                            echo '<option value="'.$resultadoEstablecimientoRedSalud[$i]['con_id'].'">'.$resultadoEstablecimientoRedSalud[$i]['con_descripcion'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <!-- Nombre Otros Establecimientos -->
                <div id="divNombreOtrosEstablecimientos" class="col-lg-3  pacienteEsDerivado otrosEstablecimientos">
                    <label for="" class="control-label encabezado">Nombre Establecimiento</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-adjust"></i></span>
                        <input id="frm_nombreOtrosEstablecimientos" type="text" class="form-control form-control-sm mifuente12" name="frm_nombreOtrosEstablecimientos" placeholder="Ingrese Nombre Establecimiento" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;">
                    </div>
                </div>
                <!-- ¿Paciente Crítico? -->
                <div id="divPacienteCritico" class="col-lg-3 ">
                    <label class="control-label encabezado">Crítico</label>
                    <select class="form-control form-control-sm mifuente12" name="slc_pacienteCritico" id="slc_pacienteCritico" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;">
                        <option value="" disabled selected>¿Paciente es Crítico?</option>
                        <option value="N">No</option>
                        <option value="S">Si</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <!-- Atención -->
                <div id="" class="col-lg-3 ">
                    <label for="" class="control-label encabezado">Atención</label>
                    <select id="frm_atencion" name="frm_atencion" class="form-control form-control-sm mifuente12" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;" disabled>
                        <option value="">Seleccione Atencion</option>
                        <?php
                        for ( $i = 0; $i < count($cargarAtencion); $i++ ) {
                        ?>
                        <option value="<?=$cargarAtencion[$i]['ate_id']?>" <?php if($datos[0]["dau_atencion"]==$cargarAtencion[$i]['ate_id']){echo "selected";}?>>
                                <?=$cargarAtencion[$i]['ate_descripcion']?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <!-- Forma llegada -->
                <div id="" class="col-lg-3 ">
                    <label for="" class="control-label encabezado">Forma llegada</label>
                    <select id="frm_formallegada" name="frm_formallegada" class="form-control form-control-sm mifuente12" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;">
                        <option value="">Seleccione Forma llegada</option>
                        <?php
                        for ( $i = 0; $i < count($cargarMedios); $i++ ) {
                        ?>
                        <option value="<?=$cargarMedios[$i]['med_id']?>" <?php if($datos[0]["dau_forma_llegada"]==$cargarMedios[$i]['med_id']){echo "selected";}?>>
                                <?=$cargarMedios[$i]['med_descripcion']?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <!-- Imputado -->
                <div id="" class="col-md-1 ">
                    <label for="" class="control-label mifuente12">&nbsp;</label>
                    <div class="input-group">
                        <div class="checkbox checkbox-primary">
                            <input id="frm_imputado" name="frm_imputado" type="checkbox" value="S" <?php if($datos[0]['dau_imputado']=="S") echo "checked";?> >
                            <label for="frm_imputado"  class="control-label mifuente12">
                                Imputado
                            </label>
                        </div>
                    </div>
                </div>
                <!-- Sala Reanimación -->
                <div id="" class="col-lg-3 ">
                    <label for="" class="control-label mifuente12">&nbsp;</label>
                    <div class="input-group">
                        <div class="checkbox checkbox-primary">
                            <input id="frm_reanimacion" name="frm_reanimacion" type="checkbox" value="S" <?php if($datos[0]['dau_reanimacion']=="S") echo "checked";?> >
                            <label for="frm_reanimacion"  class="control-label mifuente12">
                                Directo a Sala de Reanimación
                            </label>
                        </div>
                    </div>
                </div>
                <!-- Conscripto -->
                <div id="" class="col-md-1 ">
                    <label for="" class="control-label mifuente12">&nbsp;</label>
                    <div class="input-group">
                        <div class="checkbox checkbox-primary">
                            <input id="frm_conscripto" name="frm_conscripto" type="checkbox" value="S" <?php if($datos[0]['dau_conscripto']=="S") echo "checked";?> >
                            <label for="frm_conscripto"  class="control-label mifuente12">
                                Conscripto
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" >
                <!-- Motivo Consulta -->
                <div id="" class="col-lg-3 ">
                    <label for="" class="control-label encabezado">Motivo Consulta</label>
                    <select id="frm_motivoConsulta" name="frm_motivoConsulta" class="form-control form-control-sm mifuente12" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;">
                        <option value="">Seleccione Motivo Consulta</option>
                        <?php
                        for( $i = 0; $i < count($cargarMotivos); $i++ ) {
                        ?>
                        <option value="<?=$cargarMotivos[$i]['mot_id']?>" <?php if($datos[0]['dau_motivo_consulta'] == $cargarMotivos[$i]['mot_id']){echo "selected";}?>>
                            <?=$cargarMotivos[$i]['mot_descripcion']?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <div id="DivCampoMotivo" class="col " >
                    <label for="" class="control-label mifuente12">&nbsp;</label>
                    <input id="frm_motivo" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_motivo" placeholder="Ingrese Motivo" value="<?=$datos[0]['motivo']?>" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;">
                </div>
                <div class="col  DivEnfermedadesRespiratorias">
                    <label for="" class="control-label mifuente12">&nbsp;</label>
                    <div class="input-group">
                        <input type="checkbox" id="frm_dolorGarganta" name="frm_dolorGarganta" value="S" <?php if($objUtil->existe($datos[0]["dau_sintomasRespiratorios"]) && strpos($datos[0]['dau_sintomasRespiratorios'], 'S', 0) === 0 ) echo "checked";?> >&nbsp;&nbsp;<label class="control-label mifuente12">Dolor Garganta</label>
                    </div>
                </div>
                <div class="col  DivEnfermedadesRespiratorias">
                    <label for="" class="control-label mifuente12">&nbsp;</label>
                    <div class="input-group">
                        <input type="checkbox" id="frm_tos" name="frm_tos" value="S" <?php if($objUtil->existe($datos[0]["dau_sintomasRespiratorios"]) && strpos($datos[0]['dau_sintomasRespiratorios'], 'S', 1) === 1 ) echo "checked";?> >&nbsp;&nbsp;<label class="control-label mifuente12">Tos</label>
                    </div>
                </div>
                <div class="col  DivEnfermedadesRespiratorias">
                    <label for="" class="control-label mifuente12">&nbsp;</label>

                    <div class="input-group">
                        <input type="checkbox" id="frm_dificultadRespiratoria" name="frm_dificultadRespiratoria" value="S" <?php if($objUtil->existe($datos[0]["dau_sintomasRespiratorios"]) && strpos($datos[0]['dau_sintomasRespiratorios'], 'S', 2) === 2 ) echo "checked";?> >&nbsp;&nbsp;<label class="control-label mifuente12">Dificultad Respiratoria</label>
                    </div>
                </div>
                <div id="DivCampoMotivoAgresion2" class="col-lg-1"  >
                    <label for="" class="control-label mifuente12">&nbsp;</label>
                    <div class="input-group">
                    <input type="checkbox" id="frm_vif" name="frm_vif" value="S"    <?php if($datos[0]['dau_agresion_vif']=="S") echo "checked";?> >&nbsp;&nbsp;<label for="" id="labelVIF" class="control-label mifuente12" >Violencia IF</label>
                    </div>
                </div>
                <div id="DivCampoMotivoAgresionManifestaciones" class="col-lg-2 " >
                    <label for="" class="control-label mifuente12">&nbsp;</label>
                    <div class="input-group">
                    <input type="checkbox" id="frm_manifestaciones" name="frm_manifestaciones" <?php if($datos[0]['dau_manifestaciones']=="S") echo "checked";?> >&nbsp;&nbsp;<label class="control-label mifuente12">Manifestaciones</label>
                    </div>
                </div>
                <div id="DivCampoMotivoAgresionConstatacionLesiones" class="col-lg-2 " >
                    <label for="" class="control-label mifuente12">&nbsp;</label>
                    <div class="input-group">
                    <input type="checkbox" id="frm_constatacionLesiones" name="frm_constatacionLesiones" <?php if($datos[0]['dau_constatacion_lesiones']=="S") echo "checked";?> >&nbsp;&nbsp;<label class="control-label mifuente12" value="S">Constatación Lesiones</label>
                    </div>
                </div>
            </div>
            <div class="row" id="">
                <!-- Tipo de Accidente -->
                <div id="divTipoAccidente" class="col-lg-3 " >
                    <label for="" class="control-label encabezado">Tipo de Accidente</label>
                    <select id="frm_tipoAccidente" name="frm_tipoAccidente" class="form-control form-control-sm mifuente12" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;">
                    </select>
                </div>
                <!-- Institución -->
                <div id="DivInstitucion" class="col-lg-3 " >
                    <label for="inputRUN" class="control-label encabezado">Institución </label>
                    <select id="frm_institucion" name="frm_institucion" class="form-control form-control-sm mifuente12" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;">
                        <option value="0">Seleccione</option>
                    </select>
                </div>
                <div id="DivN" class="col-lg-3 " >
                    <label for="inputRUN" class="control-label encabezado">N°</label>
                    <input id="frm_numero" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_numero" placeholder="Ingrese Numero" value="<?=$datos[0]['dau_accidente_escolar_numero']?>" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;">
                </div>
                <!-- Nombre -->
                <div id="DivNombre" class="col-lg-3 " >
                    <label for="inputRUN" class="control-label encabezado">Nombre</label>
                    <input id="frm_nombre2" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_nombre2" placeholder="Ingrese Nombre" value="<?=$datos[0]['dau_accidente_escolar_nombre']?>" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;">
                </div>
                <!-- Mutualidad -->
                <div id="DivMutualidad" class="col-lg-3 " >
                    <label for="inputRUN" class="control-label encabezado">Mutualidad </label>
                    <select id="frm_mutualidad" name="frm_mutualidad" class="form-control form-control-sm mifuente12" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;">
                        <option value="0">Seleccione Mutualidad</option>
                    </select>
                </div>
                <!-- Tipo Tránsito -->
                <div id="DivTransitoTipo" class="col-lg-3 " >
                    <label for="tipoTransito" class="control-label encabezado">Tipo</label>
                    <select id="frm_transitoTipo" name="frm_transitoTipo" class="form-control form-control-sm mifuente12" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;">
                        <option value="">Seleccione Tipo</option>
                        <?php for ( $i = 0; $i < count($cargarTransito); $i++ ) { ?>
                        <option value="<?=$cargarTransito[$i]['tran_id']?>" <?php if($datos[0]['dau_accidente_transito_tipo']==$cargarTransito[$i]['tran_id']){echo "selected";}?>>
                            <?=$cargarTransito[$i]['tran_descripcion']?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <div id="DivTransitoTipoManifestacion" class="col-lg-2 " >
                    <label for="" class="control-label mifuente12">&nbsp;</label>
                    <div class="input-group">
                    <input type="checkbox" id="frm_manifestaciones" name="frm_manifestaciones" <?php if($datos[0]['dau_manifestaciones']=="S") echo "checked";?> >&nbsp;&nbsp;<label class="control-label mifuente12">Manifestaciones</label>
                </div>
                </div>
                <!-- Tipo Choque -->
                <div id="divTipo_choque" class="col-lg-3 " >
                    <label for="tipo_choque" class="control-label encabezado">Tipo Choque</label>
                    <select id="frm_tipo_choque" name="frm_tipo_choque" class="form-control form-control-sm mifuente12" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;">
                        <option value="0">Seleccione Tipo</option>
                    </select>
                </div>
                <!-- Producido En... -->
                <div id="DivHogar" class="col-lg-3 " >
                    <label for="" class="control-label encabezado">Se Produjo en </label>
                    <select id="frm_hogar" name="frm_hogar" class="form-control form-control-sm mifuente12" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;">
                        <option value="0">Seleccione</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <!-- Lugar Público -->
                <div id="DivLugarPublico" class="col-lg-3 " >
                    <label for="" class="control-label encabezado">Lugar Público </label>
                    <select id="frm_lugarPublico" name="frm_lugarPublico" class="form-control form-control-sm mifuente12" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;">
                        <option value="0">Seleccione</option>
                    </select>
                </div>
            </div>

                <!-- <div  > -->
            <div class="row">
                <div class="col-lg-3">
                    <label for="" class="control-label encabezado">Mordedura</label>
                    <select id="frm_mordedura" name="frm_mordedura" class="form-control form-control-sm mifuente12" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;">
                        <option value="">Seleccione Mordedura</option>
                        <?php
                        for ( $i = 0; $i < count($cargarMordedura); $i++ ) {
                        ?>
                        <option value="<?=$cargarMordedura[$i]['mor_id']?>" <?php if($datos[0]["dau_mordedura"]==$cargarMordedura[$i]['mor_id']){echo "selected";}?>>
                            <?=$cargarMordedura[$i]['mor_descripcion']?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-lg-3" id="div_frm_tipo_mordedura">
                    <!-- Tipo de Mordedura -->
                    <label for="" class="control-label encabezado">Tipo de Mordedura</label>
                    <select id="frm_tipo_mordedura" name="frm_tipo_mordedura" class="form-control form-control-sm mifuente12" <?php if(!$datos){ ?> style="/* border: 2px solid rgba(51, 122, 183, 0.75); *//* border-radius: 4px; */"  <?php } ?> style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;">
                        <option value="0">Seleccione Tipo</option>
                        <?php
                        for ( $i = 0; $i < count($cargarTipoMordedura); $i++ ) {
                        ?>
                        <option value="<?=$cargarTipoMordedura[$i]['tip_mor_id']?>" <?php if($datos[0]["dau_tipo_mordedura"]==$cargarTipoMordedura[$i]['tip_mor_id']){echo "selected";}?> >
                            <?=$cargarTipoMordedura[$i]['tip_mor_descripcion']?>
                        </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-lg-3">
                    <label for="" class="control-label encabezado">Intoxicación</label>
                    <select id="frm_intoxicacion" name="frm_intoxicacion" class="form-control form-control-sm mifuente12" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;">
                        <option value="">Seleccione Intoxicación</option>
                        <?php
                        for ( $i = 0; $i < count($cargarIntoxicacion); $i++ ) {
                        ?>
                        <option value="<?=$cargarIntoxicacion[$i]['int_id']?>" <?php if($datos[0]["dau_intoxicacion"]==$cargarIntoxicacion[$i]['int_id']){echo "selected";}?>>
                                <?=$cargarIntoxicacion[$i]['int_descripcion']?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-lg-3">
                    <label for="" class="control-label encabezado">Quemado</label>
                    <select id="frm_quemadura" name="frm_quemadura" class="form-control form-control-sm mifuente12" style="border: 1px solid rgba(51, 122, 183, 0.75); border-radius: 4px;">
                        <option value="">Seleccione Quemado</option>
                        <?php
                        for ( $i = 0; $i < count($cargarQuemadura); $i++ ) {
                        ?>
                        <option value="<?=$cargarQuemadura[$i]['que_id']?>" <?php if($datos[0]["dau_quemadura"]==$cargarQuemadura[$i]['que_id']){echo "selected";}?>>
                            <?=$cargarQuemadura[$i]['que_descripcion']?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
            <!-- </div> -->
            
        <!-- </fieldset> -->
        <div class="clearfix"></div>
    </form>

</div>
<div class="row mt-3">
    <!-- <div class="col-md-4" > -->
    <!-- </div> -->
    <!-- <div class="col-md-12"> -->
    <div class="col-lg-12 text-center" id="admisionAlerta"></div>
    <!-- </div> -->
    <div class="col-md-8" >
    </div>
    <div class="col-lg-2">
        <?php if ( array_search(812, $permisosPerfil) != null ) { ?>
            <button id="registrar_pacienteActualizar" type="button" name="registrar_paciente"  class="btn btn-sm col-lg-12 btn-primary pull-right"><i class="fas fa-edit mr-2"></i>Actualizar Admisión</button>
        <?php } ?>
    </div>
    <div class="col-lg-2">
        <?php if ( array_search(812, $permisosPerfil) != null ) { ?>
            <button id="recuperarInformacionAdmision"  type="button" name="registrar_paciente2" class=" col-lg-12 btn-sm btn btn-primary"><i class="fas fa-sync mr-2"></i>Resetear Formulario</button>
        <?php } ?>
    </div>
</div>