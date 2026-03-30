<?php
session_start();
error_reporting(0);
require("../../../config/config.php");
require_once ("../../../class/Util.class.php");                             $objUtil                        = new Util;
require_once("../../../class/cabecera_formularios.class.php" );             $objcabecera_formularios        = new cabecera_formularios;
require_once("../../../class/Connection.class.php");        $objCon                 = new Connection();

$objCon->db_connect();
$parametros                        = $objUtil->getFormulario($_POST);
$rsCabecera_formularios            = $objcabecera_formularios->SelectAllCabecera_formularios($objCon);
?>
<!-- <div class="container "> -->
    <input type="hidden" name="frm_codigoCIE10Ges" id="frm_codigoCIE10Ges" value="<?=$_POST['frm_codigoCIE10Ges']?>">
    <input type="hidden" name="frm_hipotesis_finalGes" id="frm_hipotesis_finalGes" value="<?=$_POST['frm_hipotesis_finalGes']?>">
    <ul class="nav nav-tabs" id="formTabs">
        <?php
        $primero = true;
        foreach ($rsCabecera_formularios as $formulario):
            $form_id        = $formulario['id_formulario']; // o el nombre correcto del campo ID
            $form_nombre    = $formulario['destripcion_formulario']; // o el campo correcto con el nombre a mostrar
        ?>
            <li class="nav-item">
                <a class="nav-link mifuente14 <?php echo $primero ? 'active' : ''; ?>"
                   data-toggle="tab"
                   href="#form<?php echo $form_id; ?>"
                   data-url="views/modules/formularios/formulario_<?php echo $form_id; ?>.php">
                    <?php echo $form_nombre; ?>
                </a>
            </li>
        <?php
            $primero = false;
        endforeach;
        ?>
        <li class="nav-item">
            <a class="nav-link mifuente14 "
               data-toggle="tab"
               href="#form_descargas"
               data-url="views/modules/formularios/formulario_descargas.php">
                Formularios Descargables
            </a>
        </li>
    </ul>
    <input type="hidden" name="dau_id" id="dau_id" value="<?=$parametros['dau_id']?>">
    <div class="tab-content scrollModal-lg border pt-3 pr-3 pl-3 bg-light" id="formularioContenido">
        <div id="formContainer">Cargando...</div>
    </div>
<!-- </div> -->

<script>
$(document).ready(function () {
    // Cargar el primer formulario automáticamente

    var primerUrl = $('#formTabs a.active').data('url');

                        ajaxContentFast(`${raiz+"/"+primerUrl}`,'dau_id='+$('#dau_id').val(), '#formContainer');

    // $('#formContainer').load(primerUrl);

    $('#formTabs a').on('click', function (e) {
        e.preventDefault();
        $('#formTabs a').removeClass('active');
        $(this).addClass('active');

        var url = $(this).data('url');
        $('#formContainer').html('<div class="text-muted text-center">Cargando...</div>');
        ajaxContentFast(`${raiz+"/"+url}`,'dau_id='+$('#dau_id').val(), '#formContainer');

        // $('#formContainer').load(url);
    });
    // if($("#frm_hipotesis_finalGes").val() != ""){
    //     // $('.nav-link[href="#form2"]').removeClass('active');
    //     $('.nav-link[href="#form1"]').click();
    // }
    if ($("#frm_hipotesis_finalGes").val() != "") {
    // Activar visualmente la pestaña
    $('#formTabs a').removeClass('active');
    $('.nav-link[href="#form1"]').addClass('active');

    // Obtener la URL de esa pestaña
    var url = $('.nav-link[href="#form1"]').data('url');

    // Preparar parámetros adicionales
    var params = 'dau_id=' + $('#dau_id').val() +
                 '&frm_codigoCIE10Ges=' + encodeURIComponent($('#frm_codigoCIE10Ges').val()) +
                 '&frm_hipotesis_finalGes=' + encodeURIComponent($('#frm_hipotesis_finalGes').val());

    // Mostrar mensaje de carga
    $('#formContainer').html('<div class="text-muted text-center">Cargando...</div>');

    // Ejecutar AJAX con parámetros extra
    ajaxContentFast(`${raiz+"/"+url}`, params, '#formContainer');
}
});
</script>