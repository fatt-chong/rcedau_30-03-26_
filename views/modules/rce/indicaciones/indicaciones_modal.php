<?php
session_start();
error_reporting(0);
ini_set('max_execution_time', 600);
ini_set('memory_limit', '128M');

require("../../../../config/config.php");
require_once ("../../../../class/Util.class.php");
require_once('../../../../class/Categorizacion.class.php');
require_once('../../../../class/Rce.class.php');
require_once('../../../../class/Connection.class.php');

$objUtil = new Util;
$objCategorizacion = new Categorizacion;
$objRce = new Rce;
$objCon = new Connection;

$objCon->db_connect();

$parametros                                             = $objUtil->getFormulario($_POST);
$dau_id                                                 = $_SESSION['indicaciones']['post']['cargarIndicacionesModal']['dau_id'];
$rce_id                                                 = $_SESSION['indicaciones']['post']['cargarIndicacionesModal']['rce_id'];
$datos                                                  = $objCategorizacion -> searchPaciente($objCon, $dau_id);
$_SESSION['RCE']['rutPaciente']                         = $datos[0]['rut'];
$_SESSION['RCE']['idPaciente']                          = $datos[0]['id_paciente'];
$tipoPaciente                                           = $datos[0]['dau_paciente_complejo'];
$_SESSION['datosPacienteDau']['dau_paciente_complejo']  = $datos[0]['dau_paciente_complejo'];
$_SESSION['indicaciones']['imagenologia']               = $parametros['tablaRayosContendido'];
$_SESSION['indicaciones']['laboratorio']                = $parametros['aLab'];
$_SESSION['indicaciones']['procedimiento']              = $parametros['tablaProcedimiento'];
$_SESSION['indicaciones']['tratamiento']                = $parametros['tablaTratamiento'];
$_SESSION['indicaciones']['otros']                      = $parametros['tablaOtros'];
$_SESSION['indicaciones']['imageDatos']                 = $parametros;
$_SESSION['indicaciones']['antecedentesClinicos']       = $parametros['antecedentesClinicos'];
$version                                                = $objUtil->versionJS();
?>


<!--
################################################################################################################################################
                                                                    ARCHIVO JS
-->
<script type="text/javascript" src="<?=PATH?>/controllers/client/rce/indicaciones/indicaciones_href.js?v=<?=$version;?>"></script>



<!--
################################################################################################################################################
                                                      			 DESPLIGUE SELECT PLANTILLAS
-->
<!-- <div class="row" style="margin:2px 2px;"> -->
    <form id="frm_contenido_indicaciones" name="frm_contenido_indicaciones">
        <!--
		**************************************************************************
									CAMPOS OCULTOS
		**************************************************************************
		-->
        <input type="hidden" id="dau_id"            name="dau_id"           value="<?=$dau_id?>">
        <input type="hidden" id="rce_id"            name="rce_id"           value="<?=$rce_id?>">
        <input type="hidden" id="carroIma"          name="carroIma" >
        <input type="hidden" id="carroTra"          name="carroTra" > <!-- ESTE ES DE PROCEDIMIENTO -->
        <input type="hidden" id="carroTraTexto"     name="carroTraTexto" > <!-- ESTE ES DE PROCEDIMIENTO TEXTO-->
        <input type="hidden" id="carroLab"          name="carroLab" >
        <input type="hidden" id="carroLab2"         name="carroLab2" >
        <input type="hidden" id="carroOtr"          name="carroOtr" >
        <input type="hidden" id="carroTratamiento"  name="carroTratamiento" >
        <input type="hidden" id="idPlantillaHidden"  name="idPlantillaHidden" value="<?=$parametros['idPlantilla']?>" >
        
        <!--
		**************************************************************************
									Parte Superior
		**************************************************************************
		-->
        <div class="row">
            <!-- Botón paciente urgente -->
            <div id="" class="col-md-2">
                <div class="input-group">
                    <label for="paciente_urg"  class="control-label">
                        <button type="button" id="btn_tipoPaciente" class="btn btn-sm btn-danger  mifuente col-lg-12" <?php if($datos[0]['dau_paciente_complejo'] == "S"){?> disabled <?php }?>>Paciente Complejo</button>
                    </label>
                </div>
            </div>
            <div class="col-md-5">
            </div>
            <!-- Select Cargar Plantilla Alta Urgencia -->
            <div class="col-md-3">
                <select class="form-control  form-control-sm mifuente" id="slc_nombrePlantilla" name="slc_nombrePlantilla">
                    <?php
                    if ( isset($_SESSION['MM_Username'.SessionName]) ) {
                        $parametros['idMedico'] = $_SESSION['MM_Username'.SessionName];
                        $respuestaConsulta = $objRce->obtenerNombrePlantillasIndicaciones($objCon, $parametros['idMedico']);
                        $totalRespuestaConsulta = count($respuestaConsulta);
                        $selected = '';
                        echo '<option value="" selected>Seleccione Plantilla</option>';
                        for ($i=0; $i < $totalRespuestaConsulta ; $i++) {
                            if ( $respuestaConsulta[$i]['idPlantilla'] == $parametros['idPlantilla'] ) {
                                echo $respuestaConsulta[$i]['idPlantilla'];
                                $selected = 'selected';
                            } else {
                                $selected = '';
                            }
                        ?>
                        <option value="<?php echo $respuestaConsulta[$i]['idPlantilla']; ?>" <?php echo $selected; ?> >  <?php echo $respuestaConsulta[$i]['nombrePlantilla']; ?> </option>
                        <?php
                        }
                    } else {
                        echo '<option value="" selected>Iniciar Sesión para Cargar Plantillas</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col">
                <button type="button" id="modalCrearPlantillaIndicaciones" name="modalCrearPlantillaIndicaciones"
                    class="btn btn-sm btn-outline-primarydiag mifuente col-lg-12 botonesActivos">
                    <i class="fa fa-plus fa-lg"></i>
                </button>
            </div>
            <?php if($parametros['idPlantilla'] > 0) { ?>
            <div class="col">
                <button type="button" id="modalactualizarPlantillaIndicaciones" name="modalactualizarPlantillaIndicaciones"
                    class="btn btn-sm btn-outline-success mifuente col-lg-12 botonesActivos">
                    <i class="fas fa-pencil-alt fa-lg""></i> 
                </button>
            </div>
            <div class="col">
                <button type="button" id="modalEliminarPlantillaIndicaciones" name="modalEliminarPlantillaIndicaciones"
                    class="btn btn-sm btn-outline-danger mifuente col-lg-12 botonesActivos">
                    <i class="fa fa-trash fa-lg"></i>
                </button>
            </div>
            <?php } ?>
        </div>

        <!-- <br> -->

        <hr class="hr-custom">

        <!-- <br> -->

    </div>

    </form>

<!-- </div> -->



<!--
################################################################################################################################################
                                                      	    DESPLIGUE TABS TIPO INDICACIONES
-->
<nav class="nav nav-pills flex-column flex-sm-row" style="background-color: #b8daff;">
    <a class="flex-sm-fill text-sm-center nav-link navIndicacion active"  id="Imagenologia-tab" data-toggle="tab" href="#Imagenologia" role="tab" aria-controls="Imagenologia" aria-selected="false" ><i class="fas fa-bookmark text-primary mifuente18 mr-3"></i>Imagenologia</a>
    <a class="flex-sm-fill text-sm-center nav-link  navIndicacion"   id="Tratamiento-tab" data-toggle="tab" href="#Tratamiento" role="tab" aria-controls="Tratamiento" aria-selected="true" ><i class="fas fa-bookmark text-primary mifuente18 mr-3"></i> Tratamiento</a>
    <a class="flex-sm-fill text-sm-center nav-link  navIndicacion"   id="Laboratorio-tab" data-toggle="tab" href="#Laboratorio" role="tab" aria-controls="Laboratorio" aria-selected="true" ><i class="fas fa-bookmark text-primary mifuente18 mr-3"></i> Laboratorio</a>
    <a class="flex-sm-fill text-sm-center nav-link  navIndicacion"   id="Procedimiento-tab" data-toggle="tab" href="#Procedimiento" role="tab" aria-controls="Procedimiento" aria-selected="true" ><i class="fas fa-bookmark text-primary mifuente18 mr-3"></i> Procedimiento</a>
    <a class="flex-sm-fill text-sm-center nav-link  navIndicacion"   id="Otros-tab" data-toggle="tab" href="#Otros" role="tab" aria-controls="Otros" aria-selected="true" ><i class="fas fa-bookmark text-primary mifuente18 mr-3"></i> Otros</a>
</nav>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="Imagenologia" role="tabpanel" aria-labelledby="Imagenologia-tab">
        <div id='div_Imagenologia' class="" style="background-color: white;" ></div>
    </div>
    <div class="tab-pane fade " id="Tratamiento" role="tabpanel" aria-labelledby="Tratamiento-tab">
        <div id='div_Tratamiento' class="" style="background-color: white;" ></div>
    </div>
    <div class="tab-pane fade " id="Laboratorio" role="tabpanel" aria-labelledby="Laboratorio-tab">
        <div id='div_Laboratorio' class="" style="background-color: white;" ></div>
    </div>
    <div class="tab-pane fade " id="Procedimiento" role="tabpanel" aria-labelledby="Procedimiento-tab">
        <div id='div_Procedimiento' class="" style="background-color: white;" ></div>
    </div>
    <div class="tab-pane fade " id="Otros" role="tabpanel" aria-labelledby="Otros-tab">
        <div id='div_Otros' class="" style="background-color: white;" ></div>
    </div>
</div>

<!-- 
<div class="row">

    <div class="col-md-12 tabsHistorial">

        <ul class="nav nav-tabs nav-justified">

            <li role="presentation" class="active"><a href="<?=PATH?>/views/modules/rce/indicaciones/imagenologia.php" id="link-2" aria-controls="2" role="tab" data-target="#section-2" data-toggle="tab"><b>Imagenologia</b></a></li>

            <li role="presentation"><a href="<?=PATH?>/views/modules/rce/indicaciones/tratamientoNuevo.php?dau_id=<?=$dau_id;?>" id="link-8" aria-controls="8" role="tab" data-target="#section-8" data-toggle="tab"><b>Tratamiento</b></a></li>

            <li role="presentation"><a href="<?=PATH?>/views/modules/rce/indicaciones/laboratorio.php?dau_id=<?=$dau_id;?>" id="link-4" aria-controls="4" role="tab" data-target="#section-4" data-toggle="tab"><b>Laboratorio</b></a></li>

            <li role="presentation"><a href="<?=PATH?>/views/modules/rce/indicaciones/tratamiento.php?dau_id=<?=$dau_id;?>" id="link-3" aria-controls="3" role="tab" data-target="#section-3" data-toggle="tab"><b>Procedimiento</b></a></li>

            <li role="presentation"><a href="<?=PATH?>/views/modules/rce/indicaciones/otros.php" id="link-7" aria-controls="7" role="tab" data-target="#section-7" data-toggle="tab"><b>Otros</b></a></li>

        </ul>

        <div class="tab-content">

            <div role="tabpanel"  class="tab-pane active" id="section-2"></div>

            <div role="tabpanel"  class="tab-pane" id="section-3"></div>

            <div role="tabpanel"  class="tab-pane" id="section-4"></div>

            <div role="tabpanel"  class="tab-pane" id="section-6"></div>

            <div role="tabpanel"  class="tab-pane" id="section-7"></div>

            <div role="tabpanel" class="tab-pane" id="section-8"></div>

        </div>

    </div>

</div> -->

<!-- <br> -->
<!--  -->
<!--
################################################################################################################################################
                                                            CARGA DATOS DE PLANTILLAS
-->
<?php
if ( $parametros['cargaPlantilla'] == true ) {
?>
    <!-- Tratamiento -->
    <div style="display:none">
        <?php
        include('tratamientoNuevo.php');
        ?>
    </div>

    <!-- Laboratorio -->
    <div style="display:none">
        <?php
        include('laboratorio.php');
        ?>
    </div>

    <!-- Procedimiento -->
    <div style="display:none">
        <?php
        include('tratamiento.php');
        ?>
    </div>

    <!-- Otros -->
    <div style="display:none">
        <?php
        include('otros.php');
        ?>
    </div>
<?php
}
?>
