<?php
 session_start();


require("../../../config/config.php");
error_reporting(0);
$permisos      = $_SESSION['permisosDAU'.SessionName];
$permisoPerfil = $_SESSION['permiso'.SessionName];


if ( array_search(811, $permisos) == null ) {

    $GoTo = "../error_permisos.php"; header(sprintf("Location: %s", $GoTo));

}
require_once('../../../class/Connection.class.php');       $objCon            = new Connection; $objCon->db_connect();
require_once('../../../class/Util.class.php');             $objUtil           = new Util;
require_once('../../../class/SqlDinamico.class.php');      $objSqlDinamico    = new SqlDinamico;

$parametros                             = $objUtil->getFormulario($_POST);
$cargarNacionalidad                     = $objSqlDinamico->generarSelect($objCon,'paciente.nacionalidadavis' , $parametrosSelect, $order);

// $cargarReligion                         = $objSqlDinamico->generarSelect($objCon,'paciente.religion' , $parametrosSelect, " order by rlg_descripcion asc");

$cargarPrevision                        = $objSqlDinamico->generarSelect($objCon,'paciente.prevision' , $parametrosSelect, " order by prevision asc");
$cargarConvenio                         = $objSqlDinamico->generarSelect($objCon,'recauda.institucion' , $parametrosSelect, " order by instNombre asc");
$cargarMedios                           = $objSqlDinamico->generarSelect($objCon,'dau.medio_llegada' , $parametrosSelect, " order by med_descripcion asc");
$cargarMotivos                          = $objSqlDinamico->generarSelect($objCon,'dau.motivo_consulta' , $parametrosSelect, $order);
// $parametros['id_paciente']           = $parametros['id'];
// $datos                               = $objAdmision->listarDatosDauIndicePaciente($objCon,$parametros);
$cargarMordedura                        = $objSqlDinamico->generarSelect($objCon,'dau.mordedura' , $parametrosSelect, $order);
$cargarIntoxicacion                     = $objSqlDinamico->generarSelect($objCon,'dau.intoxicacion' , $parametrosSelect, $order);
$cargarQuemadura                        = $objSqlDinamico->generarSelect($objCon,'dau.quemado' , $parametrosSelect, $order);
$cargarAtencion                         = $objSqlDinamico->generarSelect($objCon,'dau.atencion' , $parametrosSelect, $order);
$cargarTransito                         = $objSqlDinamico->generarSelect($objCon,'dau.tipo_transito' , $parametrosSelect, $order);
$cargarEtnia                            = $objSqlDinamico->generarSelect($objCon,'paciente.etnia' , $parametrosSelect, $order);
$cargarPaisNacimiento                   = $objSqlDinamico->generarSelect($objCon,'paciente.nacionalidadavis' , $parametrosSelect, $order);
$parametrosSelect['filtroAdmisionDatosLocalizacion'] = ' filtroAdmisionDatosLocalizacion = "S" ';
$cargarConsultorios                     = $objSqlDinamico->generarSelect($objCon,'dau.consultorios' , $parametrosSelect, 'ORDER BY
            CASE WHEN BINARY consultorios.con_id = 35 OR consultorios.con_id = 36 OR consultorios.con_id = 37 THEN 1 ELSE 0 END');
$parametrosSelect                       = "";
$cargarTipoChoque                       = $objSqlDinamico->generarSelect($objCon,'dau.tipo_choque' , $parametrosSelect, $order);
$cargarRegiones                         = $objSqlDinamico->generarSelect($objCon,'paciente.region' , $parametrosSelect, $order);
$cargarSectorDomicilio                  = $objSqlDinamico->generarSelect($objCon,'paciente.sector_domiciliario' , $parametrosSelect, $order);
$cargarTipoMordedura                    = $objSqlDinamico->generarSelect($objCon,'dau.mordedura_tipo' , $parametrosSelect, $order);
$listaCondicionIngreso                  = $objSqlDinamico->generarSelect($objCon,'dau.condicion_ingreso' , $parametrosSelect, $order);
$parametrosSelect = []; // Asegurar que sea un arreglo
$parametrosSelect['filtroAdmisionPacienteDerivado'] = 'filtroAdmisionPacienteDerivado = "S"';
$resultadoEstablecimientoRedSalud                     = $objSqlDinamico->generarSelect($objCon,'dau.consultorios' , $parametrosSelect, 'ORDER BY
            CASE WHEN BINARY consultorios.con_id = 35 OR consultorios.con_id = 36 OR consultorios.con_id = 37 THEN 1 ELSE 0 END');
$totalResultadoEstablecimientosRedSalud = count($resultadoEstablecimientoRedSalud);
$idsuario                               = $objUtil->usuarioActivo();
$permisosPerfil                         = $objUtil->cargarPermisoDau($objCon,$idsuario);
?>

<script type="text/javascript" charset="utf-8" src="<?=PATH?>/controllers/client/admision/admision.js?v=<?=time()?>23"></script>
<!-- <script>
    function checkLocalStorage() {
        const idPacienteDau = localStorage.getItem('idPacienteDau');
        if (idPacienteDau !== null) {

            console.log('idPacienteDau encontrado:', idPacienteDau);
            localStorage.removeItem('idPacienteDau');
            $.ajax({
                url  : raiz+'/controllers/server/admision/main_controller.php',
                type : 'POST',
                data : 'accion=buscarPaciente&idPacienteDau='+idPacienteDau,
                dataType : 'JSON',
                async: true
            }).done(function(retorno){
                    $('#frm_nombres_dau').val(retorno[0].nombres);  
                    $('#frm_rut1').val(retorno[0].run); 
                    $('#frm_AP_dau').val(retorno[0].apellidopat);   
                    $('#frm_AM_dau').val(retorno[0].apellidomat);   
                    $('#frm_Naciemito').val(retorno[0].fechanacimiento);   
                    $('#labelEdad').text(retorno[0].calcularEdad);   
                    $('#frm_sexo').val(retorno[0].sexo);    
                    $('#frm_etnia').val(retorno[0].etnia);  
                    $('#frm_centroAtencion').val(retorno[0].centroatencionprimaria);   
                    $('#frm_Nacionalidad').val(retorno[0].nacionalidad); 
                    $('#frm_direccion').val(retorno[0].restodedireccion);  
                    $('#frm_correo').val(retorno[0].email);    
                    $('#frm_telefonoCelular').val(retorno[0].fono1); 
                    $('#frm_telefonoCelular2').val(retorno[0].fono2); 
                    $('#frm_telefonoCelular3').val(retorno[0].fono3);     
                    $('#frm_prevision').val(retorno[0].prevision);  
                    $('#frm_formaPago').val(retorno[0].conveniopago);  
                    $('#idPacienteDau').val(retorno[0].id); 
                    $('#direccionOculta').val(retorno[0].direccionOculta);  
                    $('#pacienteFallDau').val(retorno[0].fallecido); 
                    $('#tipoDocumentoDau').text(retorno[0].tipoDocumentoLabel);  
                    $('#id_doc_documentoDau').val(retorno[0].id_doc_extranjero);    
                    $('#frm_pais_nacimiento').val(retorno[0].paisNacimiento);   
                    $('#frm_afrodescendiente').val(retorno[0].PACafro); 
                    $('#frm_region').val(retorno[0].region).change();    
                    $('#frm_ciudad').val(retorno[0].ciudad).change();    
                    $('#frm_comuna').val(retorno[0].idcomuna);    
                    $('#frm_nombreCalle').val(retorno[0].calle);    
                    $('#frm_numeroDireccion').val(retorno[0].numero);   
                    $('#frm_sectorDomicilio').val(retorno[0].sector_domicilio);   
                    $('#frm_prais').val(retorno[0].prais);  
            })
        } 

    }
    setInterval(checkLocalStorage, 1000);
</script> -->
<!-- <script type="text/javascript" src="<?=PATH?>/assets/libs/jQuery.print/jQuery.print.js"></script> -->
<form id="frm_registro_paciente" role="form" method="post" accept-charset="utf-8" enctype="multipart/form-data">
    <input type="hidden" name="idPacienteDau"         id="idPacienteDau">
    <input type="hidden" name="direccionOculta"       id="direccionOculta">
    <input type="hidden" name="pacienteFallDau"       id="pacienteFallDau">
    <input type="hidden" name="id_doc_documentoDau"   id="id_doc_documentoDau">
    <input type="hidden" name="idDau"                 id="idDau">
    <input type="hidden" name="pacienteNN"                 id="pacienteNN">
    <div class="row m-1">
        
        <div class="col-lg-3">
            <h5 class="modal-title">Datos Personales Paciente</h5>
        </div>
        <?php if ( array_search(836, $permisos) != null ) { ?>
        <div class="col-lg-2">
            <button class="btn btn-primary2  mifuente col-lg-12" type="button" id="consFonasa">
                <i class="fas fa-search mr-2"></i>Buscar Paciente
            </button>
        </div>
        <div class="col-lg-3">
            &nbsp;
        </div>
        <div class="col-lg-3" >
            <div class="input-group  shadow" id="fechaAdmision" name="fechaAdmision">
                <div class="input-group-prepend ">
                  <div class="input-group-text mifuente12" id="btnGroupAddonfrm_fechaAdmision"><i class="fas fa-clock darkcolor-barra2"></i></div>
                </div>
                <input id="frm_fechaAdmision" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_fechaAdmision" placeholder="Ingrese fecha de Admisión" value="<?=$campos['frm_fechaAdmision']?>" aria-describedby="btnGroupAddonfrm_fechaAdmision">
            </div>
        </div>
        <div class="col-lg-1 text-right">
            <a id="btn_pilaFechAdm" name="btn_pilaFechAdm">
                <img src="http://10.6.21.19:4/rcedau/assets/img/battery_time.png" style="width: 25px; height: 25px; cursor: pointer;">
            </a>
        </div>
        <?php } ?>
    </div>
    <div class="row m-1">
        <div class="row mt-3">
            <div class="col-md-3 form-group has-feedback" id="div_run">
                <div class="input-group  shadow">
                    <div class="input-group-prepend ">
                      <div class="input-group-text mifuente12" id="btnGroupAddonfrm_rut"><i class="fas fa-pen darkcolor-barra2"></i></div>
                    </div>
                    <label id="tipoDocumentoDau" class="input-group-text text-center mifuente12" style="border-radius: 0rem !important;" for="btnGroupAddonfrm_rut">RUN</label>
                    <input id="frm_rut1" type="text" readonly class="form-control form-control-sm mifuente12" name="frm_rut1" placeholder="Número de RUN"  >
                </div>          
            </div>
            <div id="div_frm_nombreSocial" class="col form-group has-feedback" >
                <div class="input-group  shadow">
                    <div class="input-group-prepend ">
                      <div class="input-group-text mifuente12" id="btnGroupAddonfrm_AP_dau"><img class="" src="<?=PATH?>/assets/img/transIco.png" style="width: 16px;" ></div>
                    </div>
                    <input id="frm_nombreSocial" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_nombreSocial" placeholder="Nombre Social" value="<?=$datos[0]["frm_nombreSocial"]?>"aria-describedby="btnGroupAddonfrm_AP_dau" <?php if(!isset($datos[0]["frm_nombreSocial"])){echo "";}?>>
                </div>
            </div>
            <div class="col-md-3 form-group has-feedback" id="div_nombres">
                <div class="input-group  shadow">
                    <div class="input-group-prepend ">
                      <div class="input-group-text mifuente12" id="btnGroupAddonfrm_nombres_dau"><i class="fas fa-user darkcolor-barra2"></i></div>
                    </div>
                    <input id="frm_nombres_dau" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_nombres_dau" placeholder="Nombre" value="<?=$datos[0]["nombres"]?>" aria-describedby="btnGroupAddonfrm_nombres_dau">
                </div>          
            </div>
            <div class="col-md-3 form-group has-feedback" id="div_apellidopat">
                <div class="input-group  shadow">
                    <div class="input-group-prepend ">
                      <div class="input-group-text mifuente12" id="btnGroupAddonfrm_AP_dau"><i class="fas fa-list darkcolor-barra2"></i></div>
                    </div>
                    <input id="frm_AP_dau" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_AP_dau" placeholder="Apellido Paterno" value="<?=$datos[0]["apellidopat"]?>" aria-describedby="btnGroupAddonfrm_AP_dau">
                </div>          
            </div>
            <div class="col-md-3 form-group has-feedback" id="div_apellidopat">
                <div class="input-group  shadow">
                    <div class="input-group-prepend ">
                      <div class="input-group-text mifuente12" id="btnGroupAddonfrm_AM_dau"><i class="fas fa-list darkcolor-barra2"></i></div>
                    </div>
                    <input id="frm_AM_dau" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_AM_dau" placeholder="Apellido Materno" value="<?=$datos[0]["apellidomat"]?>" aria-describedby="btnGroupAddonfrm_AM_dau">
                </div>          
            </div>
            <div class="col-md-3 form-group has-feedback" id="div_fecha_nac">
                <div class="input-group  shadow">
                    <div class="input-group-prepend ">
                      <div class="input-group-text mifuente12" id="btnGroupAddonfrm_Naciemito"><i class="fas fa-calendar darkcolor-barra2"></i></div>
                    </div>
                    <input id="frm_Naciemito" type="text" onDrop="return false" readonly class="form-control  text-center form-control-sm mifuente12" name="frm_Naciemito" placeholder="Nacimiento" 
                    <?php if ( $datos[0]["fechanac"] ) { ?>
                        value="<?=date("d-m-Y", strtotime($datos[0]["fechanac"]))?>"
                    <?php } ?> aria-describedby="btnGroupAddonfrm_Naciemito">
                    <label id="labelEdad" class="input-group-text text-center mifuente12" style="border-radius: 0rem !important;" for="btnGroupAddonfrm_Naciemito">Edad del paciente</label>
                </div>          
            </div>

            <div class="col-md-2 form-group has-feedback" id="div_sexo">
                <div class="input-group  shadow">
                    <div class="input-group-prepend ">
                      <div class="input-group-text mifuente12" id="btnGroupAddonfrm_sexo"><i class="fas fa-venus-mars darkcolor-barra2"></i></div>
                    </div>
                    <select class="form-control form-control-sm mifuente12" id='frm_sexo' name="frm_sexo" <?php if(!isset($datos[0]["sexo"])){echo "";}?>>

                        <option value="">Seleccione Sexo...</option>
                        <option value="M" <?php if($datos[0]["sexo"]=="M"){ echo "selected";}?> >MASCULINO</option>
                        <option value="F" <?php if($datos[0]["sexo"]=="F"){ echo "selected";}?> >FEMENINO</option>
                        <option value="O" <?php if($datos[0]["sexo"]=="O"){ echo "selected";}?> >INDETERMINADO</option>
                        <option value="D" <?php if($datos[0]["sexo"]=="D"){ echo "selected";}?> >DESCONOCIDO</option>

                    </select>
                </div>          
            </div>
            <div class="col-md-2 form-group has-feedback" id="div_Etnia">
                <div class="input-group  shadow">
                    <div class="input-group-prepend ">
                      <div class="input-group-text mifuente12" id="btnGroupAddonfrm_sexo"><i class="fas fa-list darkcolor-barra2"></i></div>
                    </div>
                    <select id="frm_etnia" name="frm_etnia" class="form-control form-control-sm mifuente12" <?php if(!isset($datos[0]["etnia"])){echo "";}?> >
                        <option value="">Pueblo Originario...</option>
                        <?php
                        for ( $i = 0; $i < count($cargarEtnia); $i++ ) {
                        ?>
                            <option value="<?=$cargarEtnia[$i]['etnia_id']?>" <?php if($datos[0]["etnia"]==$cargarEtnia[$i]['etnia_id']){echo "selected";}?> >
                                <?=$cargarEtnia[$i]['etnia_descripcion']?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>          
            </div>
            <div class="col-md-3 form-group has-feedback" id="div_afrodescendiente">
                <div class="input-group  shadow">
                    <div class="input-group-prepend ">
                      <div class="input-group-text mifuente12" id="btnGroupAddonfrm_sexo"><i class="fas fa-list darkcolor-barra2"></i></div>
                    </div>
                    <select class="form-control form-control-sm mifuente12" id='frm_afrodescendiente' name="frm_afrodescendiente" <?php if(!isset($datos[0]["PACafro"])){echo "";}?>>
                        <option value="">Seleccione Afrodescendiente...</option>
                        <option value="0" <?php if($datos[0]["PACafro"]=="0"){ echo "selected";}?> >NO es Afrodescendiente</option>
                        <option value="1" <?php if($datos[0]["PACafro"]=="1"){ echo "selected";}?> >SI es Afrodescendiente</option>

                    </select>
                </div>          
            </div>
            <div class="col-md-2 form-group has-feedback" id="div_prais">
                <div class="input-group  shadow">
                    <div class="input-group-prepend ">
                      <div class="input-group-text mifuente11" id="btnGroupAddonfrm_sexo"><i class="fas fa-list darkcolor-barra2 mr-2"></i>PRAIS</div>
                    </div>
                    <select class="form-control form-control-sm mifuente12" id='frm_prais' name="frm_prais" <?php if(!isset($datos[0]["prais"])){echo "";}?>>
                        <option value="0" <?php if($datos[0]["prais"]=="0"){ echo "selected";}?> >No</option>
                        <option value="1" <?php if($datos[0]["prais"]=="1"){ echo "selected";}?> >Si</option>
                    </select>
                </div>          
            </div>
            <div class="col-md-3 form-group has-feedback" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_sexo"><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_religion" name="frm_religion" class="form-control form-control-sm mifuente12" <?php if(!isset($datos[0]["religion"])){echo "";}?>>
                            <option value="" selected>Seleccione Religión</option>
                            <?php 
                                for ( $i = 0; $i < count($cargarReligion); $i++ ) { ?>
                                <option value="<?=$cargarReligion[$i]['rlg_id']?>" <?php if(strtoupper($datos[0]["religion"])==$cargarReligion[$i]['rlg_id']){echo "selected";}?>>
                                    <?=$cargarReligion[$i]['rlg_descripcion']?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>          
                </div>
            <div id="div_transexual" class="col-md-2 form-group has-feedback">
                <!-- <label for="" class="control-label encabezado">Transgénero</label> -->
                <div class="input-group">
                      <div class="input-group-text mifuente11" id="btnGroupAddonfrm_sexo"><i class="fas fa-list darkcolor-barra2 mr-2"></i>Transgénero</div>
                    <select class="form-control form-control-sm mifuente12" id='frm_transexual' name="frm_transexual" <?if(!isset($datos[0]["transexual"])){echo "";}?>>
                        <option value="0" <?php if($datos[0]["transexual"]=="0"){ echo "selected";}?> >No</option>
                        <option value="1" <?php if($datos[0]["transexual"]=="1"){ echo "selected";}?> >Si</option>
                    </select>
                </div>
            </div>

            <div id="div_nombre_legal" class="col-md-2 form-group has-feedback">
                <div class="input-group">
                      <div class="input-group-text mifuente11" id="btnGroupAddonfrm_sexo"><i class="fas fa-list darkcolor-barra2 mr-2"></i>Nombre Legal</div>
                    <select class="form-control form-control-sm mifuente12" id='frm_nombre_legal' name="frm_nombre_legal" <?php if(!isset($datos[0]["nombre_legal"])){echo "";}?>>
                        <option value="0" <?php if($datos[0]["nombre_legal"]=="0"){ echo "selected";}?> >No</option>
                        <option value="1" <?php if($datos[0]["nombre_legal"]=="1"){ echo "selected";}?> >Si</option>
                    </select>
                </div>
            </div>


            <div id="div_identidadGenero" class="col form-group has-feedback">
                <div class="input-group">
                      <div class="input-group-text mifuente11" id="btnGroupAddonfrm_sexo"><i class="fas fa-list darkcolor-barra2 mr-2"></i>Iden. de genero</div>
                    <select class="form-control form-control-sm mifuente12" id='frm_identidadGenero' name="frm_identidadGenero" <?php if(!isset($datos[0]["identidad_genero"])){echo "";}?>>
                        <option value="TF" <?php if($datos[0]["identidad_genero"]=="TF"){ echo "selected";}?> >Transexual Femenino</option>
                        <option value="TM" <?php if($datos[0]["identidad_genero"]=="TM"){ echo "selected";}?> >Transexual Masculino</option>
                        <option value="NB" <?php if($datos[0]["identidad_genero"]=="NB"){ echo "selected";}?> >No Binario</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
     <hr style="margin-top: 0rem !important; margin-bottom: 0rem !important;" >
    <div class="row m-1">
        <div class="col-lg-12">
            <h5 class="modal-title">Datos Localización y Contacto</h5>
        </div>
    </div>
    <div class="row m-1">
        <div class="col-lg-12" style="padding-right: 0px; padding-left: 0px;">
            <div class="row ">
                <div class="col-md-3 form-group has-feedback" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_sexo"><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_centroAtencion" name="frm_centroAtencion" class="form-control form-control-sm mifuente12" <?php if(!isset($datos[0]["centroatencionprimaria"])){echo "";}?>>
                            <option value="" >Seleccione Centro</option>
                            <?php for ( $i = 0; $i < count($cargarConsultorios); $i++ ) { ?>
                                <option value="<?=$cargarConsultorios[$i]['con_id']?>" <?php if($datos[0]["centroatencionprimaria"]==$cargarConsultorios[$i]['con_id']){echo "selected";}?> >
                                    <?=$cargarConsultorios[$i]['con_descripcion']?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>          
                </div>
                <div class="col-md-3 form-group has-feedback" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_sexo"><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_Nacionalidad" name="frm_Nacionalidad" class="form-control form-control-sm mifuente12" <?php if(!isset($datos[0]["nacionalidad"])){echo "";}?>>
                            <option value="" selected>Seleccione Nacionalidad</option>
                            <?php if ( empty($datos[0]["nacionalidad"]) || is_null($datos[0]["nacionalidad"]) ) { $datos[0]["nacionalidad"] = 'NOINF';
                                }
                                for ( $i = 0; $i < count($cargarNacionalidad); $i++ ) { ?>
                                <option value="<?=$cargarNacionalidad[$i]['NACcodigo']?>" <?php if(strtoupper($datos[0]["nacionalidad"])==$cargarNacionalidad[$i]['NACcodigo']){echo "selected";}?>>
                                    <?=$cargarNacionalidad[$i]['NACdescripcion']?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>          
                </div>

                <div class="col-md-3 form-group has-feedback" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_sexo"><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select class="form-control form-control-sm mifuente12" id='frm_pais_nacimiento' name="frm_pais_nacimiento">
                            <option value="" >Seleccione País Nacimiento</option>
                            <?php for ( $i = 0; $i < count($cargarPaisNacimiento); $i++ ) { ?>
                            <option value="<?=$cargarPaisNacimiento[$i]['NACcodigo']?>" <?php if(strtoupper($datos[0]["paisNacimiento"])==$cargarPaisNacimiento[$i]['NACcodigo']){echo "selected";}?> >
                                <?=$cargarPaisNacimiento[$i]['NACpais']?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>          
                </div>
            </div>
        </div>
        <div class="col-lg-12" style="padding-right: 0px; padding-left: 0px;">
            <div class="row ">
                <div class="col-md-3 form-group has-feedback" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_sexo"><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_region" name="frm_region" class="form-control form-control-sm mifuente12">
                            <option value=""> Seleccione Región </option> 
                            <?php for ( $i = 0; $i < count($cargarRegiones); $i++ ) { ?>
                            <option value="<?=$cargarRegiones[$i]['REG_Id']?>" <?php if($datos[0]["region"]==$cargarRegiones[$i]['REG_Id']){echo "selected";}?> >
                                <?=$cargarRegiones[$i]['REG_Descripcion']?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>          
                </div>
                <div class="col-md-3 form-group has-feedback" id="divSeleccionCiudades">
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_sexo"><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_ciudad" name="frm_ciudad" class="form-control form-control-sm mifuente12" ></select>
                    </div>          
                </div>
                <div class="col-md-3 form-group has-feedback"  id="divSeleccionComunas" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_sexo"><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_comuna" name="frm_comuna" class="form-control form-control-sm mifuente12" ></select>
                    </div>          
                </div>
            </div>
        </div>
        <div class="col-lg-12" style="padding-right: 0px; padding-left: 0px;">
            <div class="row ">
                <div class="col-md-3 form-group has-feedback" id="div_nombreCalle">
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_nombreCalle"><i class="fas fa-home darkcolor-barra2"></i></div>
                        </div>
                        <input id="frm_nombreCalle" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_nombreCalle" placeholder="Nombre de Calle" value="<?=$datos[0]["calle"]?>" aria-describedby="btnGroupAddonfrm_nombreCalle">
                    </div>          
                </div>
                <div class="col-md-2 form-group has-feedback" id="div_numeroDireccion">
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_numeroDireccion"><i class="fas fa-home darkcolor-barra2"></i></div>
                        </div>
                        <input id="frm_numeroDireccion" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_numeroDireccion" placeholder="Número de Dirección" value="<?=$datos[0]["numero"]?>" aria-describedby="btnGroupAddonfrm_numeroDireccion">
                    </div>          
                </div>
                <div class="col-md-3 form-group has-feedback" id="div_direccion">
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_direccion"><i class="fas fa-home darkcolor-barra2"></i></div>
                        </div>
                        <input id="frm_direccion" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_direccion" placeholder="Resto de la Dirección (Nombre, pasaje, etc)" value="<?=$datos[0]["restodedireccion"]?>" aria-describedby="btnGroupAddonfrm_direccion">
                    </div>          
                </div>
                <div class="col-md-2 form-group has-feedback" id="">
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_direccion"><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_sectorDomicilio" name="frm_sectorDomicilio" class="form-control form-control-sm mifuente12" <?php if(!$datos){?> <?php }?>  >
                            <option value="">Seleccione Sector</option>
                            <?php for ( $i = 0; $i < count($cargarSectorDomicilio); $i++ ) { ?>
                            <option value="<?=$cargarSectorDomicilio[$i]['id_sector_domiciliario']?>" <?php if($datos[0]["sector_domicilio"]==$cargarSectorDomicilio[$i]['id_sector_domiciliario']){echo "selected";}?>>
                                <?=$cargarSectorDomicilio[$i]['descripcion_sector_domiciliario']?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>          
                </div>
                <div class="col-md-2 form-group has-feedback" id="">
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_tipoDomicilio"><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_tipoDomicilio" name="frm_tipoDomicilio" class="form-control form-control-sm mifuente12" >
                            <option value='U'>Domicilio Urbano</option>
                            <option value='R'>Domicilio Rural</option>
                        </select>
                    </div>          
                </div>
            </div>
        </div>
        <div class="col-lg-12" style="padding-right: 0px; padding-left: 0px;">
            <div class="row ">
                <div class="col-md-3 form-group has-feedback" id="div_correo_elect">
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_correo"><i class="fas fa-envelope darkcolor-barra2"></i></div>
                        </div>
                        <input id="frm_correo" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_correo" placeholder="Correo Electrónico" value="<?=$datos[0]["email"]?>" aria-describedby="btnGroupAddonfrm_correo">
                    </div>          
                </div>
                <div class="col-md-3 form-group has-feedback" id="div_telefono_cel">
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente11" id="btnGroupAddonfrm_telefonoCelular"><i class="fas fa-mobile-alt darkcolor-barra2 mr-2"></i>+56 9</div>
                        </div>
                        <input id="frm_telefonoCelular" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_telefonoCelular" placeholder="Número de Teléfono Celular" value="<?=$datos[0]["fono1"]?>" aria-describedby="btnGroupAddonfrm_telefonoCelular">
                    </div>          
                </div>
                <div class="col-md-3 form-group has-feedback" id="div_telefono_cel">
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente11" id="btnGroupAddonfrm_telefonoCelular2"><i class="fas fa-mobile-alt darkcolor-barra2 mr-2"></i>+56 9</div>
                        </div>
                        <input id="frm_telefonoCelular2" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_telefonoCelular2" placeholder="Número de Teléfono Celular" value="<?=$datos[0]["fono2"]?>" aria-describedby="btnGroupAddonfrm_telefonoCelular2">
                    </div>          
                </div>
                <div class="col-md-3 form-group has-feedback" id="div_telefono_cel">
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente11" id="btnGroupAddonfrm_telefonoCelular3"><i class="fas fa-mobile-alt darkcolor-barra2 mr-2"></i>+56 9</div>
                        </div>
                        <input id="frm_telefonoCelular3" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_telefonoCelular3" placeholder="Número de Teléfono Celular" value="<?=$datos[0]["fono3"]?>" aria-describedby="btnGroupAddonfrm_telefonoCelular3">
                    </div>          
                </div>
            </div>
        </div>
    </div>
     <hr style="margin-top: 0rem !important; margin-bottom: 0rem !important;" >
    <div class="row m-1">
        <div class="col-lg-3">
            <h5 class="modal-title">Datos Epidemiológicos</h5>
        </div>
    </div>
    <div class="row m-1">
        <div class="col-lg-12" style="padding-right: 0px; padding-left: 0px;">
            <div class="row ">
                <div class="col-lg-12">
                    <label for="" class="control-label mifuente ">¿Viaje o procedencia del extranjero en el último mes?</label>
                </div>
                <div class="col-md-4 form-group has-feedback" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_viajeEpidemiologico"><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_viajeEpidemiologico" name="frm_viajeEpidemiologico" class="form-control form-control-sm mifuente12" >
                            <option value="" selected disabled>Seleccione Opción</option>
                            <option value="N">No</option>
                            <option value="S">Si</option>
                        </select>
                    </div>          
                </div>
                <div class="col-md-3 form-group has-feedback" id="divPaisEpidemiologia" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_paisEpidemiologia"><i class="fas fa-flag darkcolor-barra2"></i></div>
                        </div>
                        <select class="form-control form-control-sm mifuente12" id='frm_paisEpidemiologia' name="frm_paisEpidemiologia">
                            <option value="" selected disabled="disabled">Seleccione País</option>
                            <?php for ( $i = 0; $i < count($cargarPaisNacimiento); $i++ ) { ?>
                            <option value="<?php echo $cargarPaisNacimiento[$i]['NACcodigo']; ?>"><?php echo $cargarPaisNacimiento[$i]['NACpais']; ?></option>
                            <?php } ?>
                        </select>
                    </div>           
                </div>
                <div class="col-md-5 form-group has-feedback mb-0"" id="divObservacionesEpidemiologia" >
                    <div class="input-group shadow">
                        <div class="input-group-prepend">
                            <div class="input-group-text mifuente12" id="btnGroupAddonfrm_observacionEpidemiologica">
                                <i class="fas fa-text-height darkcolor-barra2"></i>
                            </div>
                        </div>
                        <textarea id="frm_observacionEpidemiologica" maxlength="500" placeholder="Ingrese Observación" name="frm_observacionEpidemiologica" rows="1"  oninput="updateCharCount('frm_observacionEpidemiologica')" class="form-control form-control-sm mifuente"></textarea>
                    </div>
                    <span id="charCountfrm_observacionEpidemiologica" class="mifuente12 ml-2 float-right">500 caracteres restantes</span>         
                </div>
            </div>
        </div>
    </div>
     <hr style="margin-top: 0rem !important; margin-bottom: 0rem !important;" >
    <div class="row m-1 mt-0">
        <div class="col-lg-3">
            <h5 class="modal-title">Datos Previsionales</h5>
        </div>
    </div>
    <div class="row m-1">
        <div class="col-lg-12" style="padding-right: 0px; padding-left: 0px;">
            <div class="row ">
                <div class="col-md-4 form-group has-feedback" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_viajeEpidemiologico"><i class="fas fa-list darkcolor-barra2"></i></div>
                          <input type="hidden" name="imp_prevision" id="imp_prevision">
                        </div>
                        <?php if ( $datos[0]["prevision"] == 0 || $datos[0]["prevision"] == 1 || $datos[0]["prevision"] == 2 || $datos[0]["prevision"] == 3 ) { ?>
                            <select id="frm_prevision" name="frm_prevision" class="form-control form-control-sm mifuente12" >
                                <option value="">Seleccione Prevision</option>
                            <?php for ( $i = 0; $i < count($cargarPrevision); $i++ ) { ?>
                                <option value="<?=$cargarPrevision[$i]['id']?>" <?php if($datos[0]["prevision"]==$cargarPrevision[$i]['id']){echo "selected";}?> >
                                    <?=$cargarPrevision[$i]['prevision']?>
                                </option>
                            <?php } ?>
                            </select>
                        <?php  } else {
                            if ( $datos[0]["prevision"] != 0 || $datos[0]["prevision"] != 1 || $datos[0]["prevision"] != 2 || $datos[0]["prevision"] !=3 ) { ?>
                            <select id="frm_prevision" name="frm_prevision" class="form-control form-control-sm mifuente12" >
                                <option value="">Seleccione Prevision</option>
                            </select>
                        <?php }
                        } ?>
                    </div>          
                </div>
                <div class="col-md-3 form-group has-feedback" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_paisEpidemiologia"><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_formaPago" name="frm_formaPago" class="form-control form-control-sm mifuente12" <?php if(!$datos){?>  <?php }?> >
                            <option value="">Seleccione Forma de Pago</option>
                        <?php if ( $datos[0]["conveniopago"] ) {
                            for ( $i = 0; $i < count($cargarConvenio); $i++ ) { ?>
                            <option value="<?=$cargarConvenio[$i]['instCod']?>" <?php if($datos[0]["conveniopago"]==$cargarConvenio[$i]['instCod']){echo "selected";}?> >
                                <?=$cargarConvenio[$i]['instNombre']?>
                            </option>
                        <?php } } else {
                            for ( $i = 0; $i < count($cargarConvenio); $i++ ) { ?>
                            <option value="<?=$cargarConvenio[$i]['instCod']?>" <?php if($datos[0]["conveniopago"]==$cargarConvenio[$i]['instCod']){echo "selected";}?> >
                                <?=$cargarConvenio[$i]['instNombre']?>
                            </option>
                        <?php }
                        }   ?>
                        </select>
                    </div>           
                </div>
            </div>
        </div>
    </div>
     <hr style="margin-top: 0rem !important; margin-bottom: 0rem !important;">
    <div class="row m-1 mt-0">
        <div class="col-lg-3">
            <h5 class="modal-title">Datos de Admisión</h5>
        </div>
    </div>
    <div class="row m-1">
        <div class="col-lg-12" style="padding-right: 0px; padding-left: 0px;">
            <div class="row ">
                <div class="col-md-3 form-group has-feedback" id="divPacienteDerivado" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" ><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select class="form-control form-control-sm mifuente12" name="slc_derivado" id="slc_derivado">
                            <option value="" disabled selected>¿Paciente es Derivado?</option>
                            <option value="N">No</option>
                            <option value="S">Si</option>
                        </select>
                    </div>          
                </div>
                <div class="col-md-3 form-group has-feedback pacienteEsDerivado establecimientosRedSalud"  id="divEstablecimientos" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" ><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_establecimientosRedSalud" name="frm_establecimientosRedSalud" class="form-control form-control-sm mifuente12">
                            <option value="" disabled selected>Seleccione Establecimiento</option>
                            <?php for ( $i = 0; $i < $totalResultadoEstablecimientosRedSalud; $i++ ) {
                            echo '<option value="'.$resultadoEstablecimientoRedSalud[$i]['con_id'].'">'.$resultadoEstablecimientoRedSalud[$i]['con_descripcion'].'</option>';
                            }?>
                        </select>
                    </div>          
                </div>
                <div class="col-md-3 form-group has-feedback pacienteEsDerivado otrosEstablecimientos" id="divNombreOtrosEstablecimientos" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" ><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <input id="frm_nombreOtrosEstablecimientos" type="text" class="form-control form-control-sm mifuente12" name="frm_nombreOtrosEstablecimientos" placeholder="Ingrese Nombre Establecimiento">
                    </div>          
                </div>
                <div class="col-md-3 form-group has-feedback " id="divPacienteCritico" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" ><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select class="form-control form-control-sm mifuente12" name="slc_pacienteCritico" id="slc_pacienteCritico">
                            <option value="0" disabled selected>¿Paciente es Crítico?</option>
                            <option value="N">No</option>
                            <option value="S">Si</option>
                        </select>
                    </div>          
                </div>
            </div>
            <div class="row ">
                <div class="col-md-3 form-group has-feedback "  >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" ><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_atencion_admision" name="frm_atencion_admision" class="form-control form-control-sm mifuente12" >
                            <option value="">Seleccione Atencion</option>
                            <?php for ( $i = 0; $i < count($cargarAtencion); $i++ ) { ?>
                            <option value="<?=$cargarAtencion[$i]['ate_id']?>">
                                <?=$cargarAtencion[$i]['ate_descripcion']?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>          
                </div>
                <div class="col-md-3 form-group has-feedback "  >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" ><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_formallegada" name="frm_formallegada" class="form-control form-control-sm mifuente12" >
                            <option value="">Seleccione Forma llegada</option>
                            <?php for ( $i = 0; $i < count($cargarMedios); $i++ ) { 
                                if ( $cargarMedios[$i]['med_id'] == 20 ) {
                                    continue;
                                } ?>
                            <option value="<?=$cargarMedios[$i]['med_id']?>">
                                <?=$cargarMedios[$i]['med_descripcion']?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>          
                </div>
                <div class="col-md-1 form-group has-feedback "  >
                    <div class="custom-control custom-checkbox  mt-1 ">
                        <input class="form-check-input position-static" type="checkbox" id="frm_imputado" name="frm_imputado" value="S" aria-label="...">
                        <label class=" mifuente" >Imputado</label>

                        <!-- 
                      <input type="checkbox" class="custom-control-input" id="frm_imputado" name="frm_imputado" value="S">
                      <label class="custom-control-label mifuente" for="frm_imputado">Imputado</label> -->
                    </div>
                </div>
                <div class="col-md-3 form-group has-feedback "  >
                    <div class="custom-control custom-checkbox  mt-1 ">

                        <input class="form-check-input position-static" type="checkbox" id="frm_reanimacion" name="frm_reanimacion" value="S" aria-label="...">
                        <label class=" mifuente" >Directo a Sala de Reanimación</label>

                      <!-- <input type="checkbox" class="custom-control-input" id="frm_reanimacion" name="frm_reanimacion" value="S">
                      <label class="custom-control-label mifuente" for="frm_reanimacion">Directo a Sala de Reanimación</label> -->
                    </div>
                </div>
                <div class="col-md-2 form-group has-feedback "  >
                    <div class="custom-control custom-checkbox  mt-1 ">
                        <input class="form-check-input position-static" type="checkbox" id="frm_conscripto" name="frm_conscripto" value="S" aria-label="...">
                        <label class=" mifuente" >Conscripto</label>

                      <!-- <input type="checkbox" class="custom-control-input" id="frm_conscripto" name="frm_conscripto" value="S">
                      <label class="custom-control-label mifuente" for="frm_conscripto">Conscripto</label> -->
                    </div>
                </div>
            </div>
            <div class="row ">
                <div class="col-md-3 form-group has-feedback "  >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" ><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_motivoConsulta" name="frm_motivoConsulta" class="form-control form-control-sm mifuente12">
                            <option value="">Seleccione Motivo Consulta</option>
                            <?php for ( $i = 0; $i < count($cargarMotivos); $i++ ) { ?>
                            <option value="<?=$cargarMotivos[$i]['mot_id']?>">
                                <?=$cargarMotivos[$i]['mot_descripcion']?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>          
                </div>
                <div class="col-md-4 form-group has-feedback mb-0" id="DivCampoMotivo" >
                    <div class="input-group shadow">
                        <div class="input-group-prepend">
                            <div class="input-group-text mifuente12" id="btnGroupAddonfrm_motivoText">
                                <i class="fas fa-text-height darkcolor-barra2"></i>
                            </div>
                        </div>
                        <textarea id="frm_motivoText" maxlength="500" oninput="updateCharCount('frm_motivoText')"  placeholder="Motivo de la enfermedad" name="frm_motivoText" rows="1" class="form-control form-control-sm mifuente"></textarea>
                    </div>
                    <span id="charCountfrm_motivoText" class="mifuente12 ml-2 float-right">500 caracteres restantes</span>         
                </div>
                <div class="col-md-9 form-group has-feedback mb-0" id="DivCampoMotivo2" >
                    <div class="input-group shadow">
                        <div class="input-group-prepend">
                            <div class="input-group-text mifuente12" id="btnGroupAddonfrm_motivoText2">
                                <i class="fas fa-text-height darkcolor-barra2"></i>
                            </div>
                        </div>
                        <textarea id="frm_motivoText2" maxlength="500" oninput="updateCharCount('frm_motivoText2')"  placeholder="Descripción del accidente" name="frm_motivoText2" rows="1" class="form-control form-control-sm mifuente"></textarea>
                    </div>
                    <span id="charCountfrm_motivoText2" class="mifuente12 ml-2 float-right">500 caracteres restantes</span>         
                </div>

                <div class="col-md-2 form-group has-feedback  DivEnfermedadesRespiratorias" >
                    <div class="custom-control custom-checkbox  mt-1 ">
                        <input class="form-check-input position-static" type="checkbox" id="frm_dolorGarganta" name="frm_dolorGarganta" value="S" aria-label="...">
                        <label class=" mifuente" >Dolor Garganta</label>

                      <!-- <input type="checkbox" class="custom-control-input" id="frm_dolorGarganta" name="frm_dolorGarganta" value="S"> -->
                      <!-- <label class="custom-control-label mifuente" for="frm_dolorGarganta">Dolor Garganta</label> -->
                    </div>
                </div>
                <div class="col-md-1 form-group has-feedback  DivEnfermedadesRespiratorias" >
                    <div class="custom-control custom-checkbox  mt-1 ">
                        <input class="form-check-input position-static" type="checkbox" id="frm_tos" name="frm_tos" value="S" aria-label="...">
                        <label class=" mifuente" >Tos</label>

                      <!-- <input type="checkbox" class="custom-control-input" id="frm_tos" name="frm_tos" value="S">
                      <label class="custom-control-label mifuente" for="frm_tos">Tos</label> -->
                    </div>
                </div>

                <div class="col-md-2 form-group has-feedback  DivEnfermedadesRespiratorias" >
                    <div class="custom-control custom-checkbox  mt-1 ">
                        <input class="form-check-input position-static" type="checkbox" id="frm_dificultadRespiratoria" name="frm_dificultadRespiratoria" value="S" aria-label="...">
                        <label class=" mifuente" >Dificultad Respiratoria</label>

                      <!-- <input type="checkbox" class="custom-control-input" id="frm_dificultadRespiratoria" name="frm_dificultadRespiratoria" value="S">
                      <label class="custom-control-label mifuente" for="frm_dificultadRespiratoria">Dificultad Respiratoria</label> -->
                    </div>
                </div>
                <div class="col-md-4 form-group has-feedback mb-0" id="DivCampoMotivoAgresion" >
                    <div class="input-group shadow">
                        <div class="input-group-prepend">
                            <div class="input-group-text mifuente12" id="btnGroupAddonfrm_motivo">
                                <i class="fas fa-text-height darkcolor-barra2"></i>
                            </div>
                        </div>
                        <textarea id="frm_motivoAgresion" maxlength="500" oninput="updateCharCount('frm_motivoAgresion')" placeholder="Ingrese Motivo Agresión" name="frm_motivoAgresion" rows="1" class="form-control form-control-sm mifuente"></textarea>
                    </div>
                    <span id="charCountfrm_motivoAgresion" class="mifuente12 ml-2 float-right">500 caracteres restantes</span>         
                </div>
                <div id="DivCampoMotivoAgresion2" class="col-md-1 form-group has-feedback  " style="padding-right: 4px !important;" >
                    <div class="custom-control custom-checkbox  mt-1 ">
                        <input class="form-check-input position-static" type="checkbox" id="frm_vif" name="frm_vif" value="S" aria-label="...">
                        <label class=" mifuente" >Violencia IF</label>

                        <!-- 
                      <input type="checkbox" class="custom-control-input" id="frm_vif" name="frm_vif" value="S">
                      <label class="custom-control-label mifuente" for="frm_vif">Violencia IF</label> -->
                    </div>
                </div>

                <div id="DivCampoMotivoAgresionManifestaciones" class="col-md-2 form-group has-feedback  " >
                    <div class="custom-control custom-checkbox  mt-1 ">
                         <input class="form-check-input position-static" type="checkbox" id="frm_manifestaciones" name="frm_manifestaciones" value="S" aria-label="...">
                        <label class=" mifuente" >Manifestaciones</label>
                    </div>
                </div>
                <div id="DivCampoMotivoAgresionConstatacionLesiones" class="col-md-2 form-group has-feedback  " >
                    <div class="custom-control custom-checkbox  mt-1 ">
                        <input class="form-check-input position-static" type="checkbox" id="frm_constatacionLesiones" name="frm_constatacionLesiones" value="S" aria-label="...">
                        <label class=" mifuente" >Constatación Lesiones</label>
                        <!-- 
                      <input type="checkbox" class="custom-control-input" id="frm_constatacionLesiones" name="frm_constatacionLesiones" value="S">
                      <label class="custom-control-label mifuente" for="frm_constatacionLesiones">Constatación Lesiones</label> -->
                    </div>
                </div>
                <div class="col-md-7 form-group has-feedback mb-0" id="DivCampoMotivoLesiones">
                    <div class="input-group shadow">
                        <div class="input-group-prepend">
                            <div class="input-group-text mifuente12" id="btnGroupAddonfrm_motivoLesiones">
                                <i class="fas fa-text-height darkcolor-barra2"></i>
                            </div>
                        </div>
                        <textarea id="frm_motivoLesiones" maxlength="500" oninput="updateCharCount('frm_motivoLesiones')" placeholder="Ingrese Motivo Lesión" name="frm_motivoLesiones" rows="1" class="form-control form-control-sm mifuente"></textarea>
                    </div>
                    <span id="charCountfrm_motivoLesiones" class="mifuente12 ml-2 float-right">500 caracteres restantes</span>         
                </div>
                <div id="DivConstatacionLesionesManifestacion" class="col-md-2 form-group has-feedback  " >
                    <div class="custom-control custom-checkbox  mt-1 ">
                        <input class="form-check-input position-static" type="checkbox" id="frm_manifestaciones" name="frm_manifestaciones" value="S" aria-label="...">
                         <!-- <input class="form-check-input position-static" type="checkbox" id="blankCheckbox" value="option1" aria-label="..."> -->

                      <!-- <input type="checkbox" class="custom-control-input" id="frm_manifestaciones" name="frm_manifestaciones" value="S"> -->
                      <label class=" mifuente" for="frm_manifestaciones">Manifestaciones</label>
                    </div>
                </div>
                <div class="col-md-9 form-group has-feedback mb-0" id="DivCampoMotivoAlcoholemia" >
                    <div class="input-group shadow">
                        <div class="input-group-prepend">
                            <div class="input-group-text mifuente12" >
                                <i class="fas fa-text-height darkcolor-barra2"></i>
                            </div>
                        </div>
                        <textarea id="frm_motivoAlcoholemia" maxlength="500" oninput="updateCharCount('frm_motivoAlcoholemia')" placeholder="Ingrese Motivo Alcoholemia" name="frm_motivoAlcoholemia" rows="1" class="form-control form-control-sm mifuente"></textarea>
                    </div>
                    <span id="charCountfrm_motivoAlcoholemia" class="mifuente12 ml-2 float-right">500 caracteres restantes</span>         
                </div>
            </div>
            <div class="row ">
                <div class="col-md-3 form-group has-feedback " id="divTipoAccidente" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" ><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_tipoAccidente" name="frm_tipoAccidente" class="form-control form-control-sm mifuente12">
                        </select>
                    </div>          
                </div>
                <div class="col-md-3 form-group has-feedback " id="DivInstitucion" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" ><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_institucion" name="frm_institucion" class="form-control form-control-sm mifuente12">
                        </select>
                    </div>          
                </div>
                <div class="col-md-3 form-group has-feedback " id="DivN" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" ><i class="fas fa-pen darkcolor-barra2"></i></div>
                        </div>
                        <input id="frm_numero" type="text" class="form-control form-control-sm mifuente12" name="frm_numero" placeholder="Ingrese Numero">
                    </div>          
                </div>
                <div class="col-md-3 form-group has-feedback " id="DivNombre" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" ><i class="fas fa-pen darkcolor-barra2"></i></div>
                        </div>
                        <input id="frm_nombre2" type="text" class="form-control form-control-sm mifuente12" name="frm_nombre2" placeholder="Ingrese Nombre">
                    </div>          
                </div>
                <div class="col-md-3 form-group has-feedback " id="DivMutualidad" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" ><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_mutualidad" name="frm_mutualidad" class="form-control form-control-sm mifuente12">
                        </select>
                    </div>          
                </div>
                <div class="col-md-3 form-group has-feedback " id="DivTransitoTipo" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" ><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_transitoTipo" name="frm_transitoTipo" class="form-control form-control-sm mifuente12">
                            <option value="">Seleccione Tipo</option>
                            <?php for ( $i = 0; $i < count($cargarTransito); $i++ ) { ?>
                            <option value="<?=$cargarTransito[$i]['tran_id']?>">
                                <?=$cargarTransito[$i]['tran_descripcion']?>
                            </option> 
                            <?php } ?>
                        </select>
                    </div>          
                </div>
                <div id="DivTransitoTipoManifestacion" class="col-md-2 form-group has-feedback  " >
                    <div class="custom-control custom-checkbox  mt-1 ">
                        <input class="form-check-input position-static" type="checkbox" id="frm_manifestaciones" name="frm_manifestaciones" value="S" aria-label="...">
                      <!-- <input type="checkbox" class="custom-control-input" id="frm_manifestaciones" name="frm_manifestaciones" value="S"> -->
                      <label class=" mifuente" for="frm_manifestaciones3">Manifestaciones</label>
                    </div>
                </div>
            </div>
            <div class="row ">
                <div class="col-md-3 form-group has-feedback " id="divTipo_choque" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" ><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_tipo_choque" name="frm_tipo_choque" class="form-control form-control-sm mifuente12" >
                            <option value="">Seleccione Tipo</option>
                            <?php for ( $i = 0; $i < count($cargarTipoChoque); $i++ ) { ?>
                            <option value="<?=$cargarTipoChoque[$i]['tip_choque_id']?>">
                                <?=$cargarTipoChoque[$i]['tip_choque_descripcion']?>
                            </option> 
                            <?php } ?>
                        </select>
                    </div>          
                </div>
                <div class="col-md-3 form-group has-feedback " id="DivHogar" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" ><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_hogar" name="frm_hogar" class="form-control form-control-sm mifuente12">
                        </select>
                    </div>          
                </div>
                <div class="col-md-3 form-group has-feedback " id="DivLugarPublico" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" ><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_lugarPublico" name="frm_lugarPublico" class="form-control form-control-sm mifuente12">
                        </select>
                    </div>          
                </div>
            </div>
            <div class="row ">
                <div class="col-md-3 form-group has-feedback "  >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" ><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_condicionIngreso" name="frm_condicionIngreso" class="form-control form-control-sm mifuente12" >
                            <option value="">Seleccione Condición de Ingreso</option>
                            <?php for ($i=0; $i < count($listaCondicionIngreso) ; $i++) { ?>
                            <option value="<?=$listaCondicionIngreso[$i]['con_ingreso_id']?>"><?php echo $listaCondicionIngreso[$i]['con_ingreso_nombre']; ?></option>
                            <?php } ?>
                        </select>
                    </div>          
                </div>
            </div>
            <div class="row ">
                <div class="col-md-3 form-group has-feedback "  >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" ><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_mordedura" name="frm_mordedura" class="form-control form-control-sm mifuente12" >
                            <option value="">Seleccione Mordedura</option>
                            <?php for ( $i = 0; $i < count($cargarMordedura); $i++ ) { ?>
                            <option value="<?=$cargarMordedura[$i]['mor_id']?>">
                                <?=$cargarMordedura[$i]['mor_descripcion']?>
                            </option>
                            <?php } ?>
                        </select>
                    </div>          
                </div>
                <div class="col-md-3 form-group has-feedback "  id="div_frm_tipo_mordedura" >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" ><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_tipo_mordedura" name="frm_tipo_mordedura" class="form-control form-control-sm mifuente12">
                            <option value="0">Seleccione Tipo de Mordedura</option>
                            <?php for ( $i = 0; $i < count($cargarTipoMordedura); $i++ ) { ?>
                            <option value="<?=$cargarTipoMordedura[$i]['tip_mor_id']?>">
                                <?=$cargarTipoMordedura[$i]['tip_mor_descripcion']?>
                            </option> 
                            <?php } ?>
                        </select>
                    </div>          
                </div>
                <div class="col-md-3 form-group has-feedback "   >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" ><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_intoxicacion" name="frm_intoxicacion" class="form-control form-control-sm mifuente12" >
                            <option value="">Seleccione Intoxicación</option>
                            <?php for ( $i = 0; $i < count($cargarIntoxicacion); $i++ ) {  ?> 
                            <option value="<?=$cargarIntoxicacion[$i]['int_id']?>">
                                <?=$cargarIntoxicacion[$i]['int_descripcion']?>
                            </option> 
                            <?php }  ?> 
                        </select>
                    </div>          
                </div>
                <div class="col-md-3 form-group has-feedback "   >
                    <div class="input-group  shadow">
                        <div class="input-group-prepend ">
                          <div class="input-group-text mifuente12" ><i class="fas fa-list darkcolor-barra2"></i></div>
                        </div>
                        <select id="frm_quemadura" name="frm_quemadura" class="form-control form-control-sm mifuente12" >
                            <option value="">Seleccione Quemado</option>
                            <?php for ( $i = 0; $i < count($cargarQuemadura); $i++ ) { ?> 
                            <option value="<?=$cargarQuemadura[$i]['que_id']?>">
                                <?=$cargarQuemadura[$i]['que_descripcion']?>
                            </option> 
                            <?php } ?>
                        </select>
                    </div>          
                </div>
            </div>
        </div>
    </div>
    <fieldset>

        <div>

            <div class="row">

                <div class="col-md-12 text-center " id="divBottonAdmisionar" >
                    <?php
                    if ( array_search(837, $permisosPerfil) != null ) {
                    ?>

                        <button id="registrar_paciente" type="button" name="registrar_paciente" class="btn btn-sm btn-primary2  mr-3  col-lg-4" style="margin-right:30px">Admisionar Paciente&nbsp;<i class=" ml-3 fa fa-save" aria-hidden="true"></i></button>

                    <?php
                    }
                    ?>

                </div>

            </div>

        </div>
    </fieldset>
</form>
