
<?php
session_start();

header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1
header('Pragma: no-cache'); // HTTP 1.0
header('Expires: 0'); // Proxies
header('Clear-Site-Data: "cache", "cookies", "storage", "executionContexts"');


require("config/config.php");
///////////////////////////////////////////////////////////
//if($_GET['bandera'] == 'aprobarIndicacion' || $_GET['bandera'] == 'generarEsquema'){ 
    $miarray                                                    = base64_decode($_GET["k"]);
    mysql_connect ('10.6.21.26','OncoNet','123OncoNet');
    mysql_select_db('acceso') or die('Cannot select database');
    
    $sql                                                        = '';
    $rs_crs                                                     = '';
    $account                                                    = '';
    $sqlrol                                                     = '';
    $rs_AplicacionesxUsuarios                                   = '';
    $row_rs_AplicacionesxUsuarios                               = '';
    $totalRows_rs_AplicacionesxUsuarios                         = '';
    $permiso                                                    = '';
    $i                                                          = '';
    
    $sql  = "SELECT
    usuario.idusuario,
    usuario.rutusuario,
    UPPER(usuario.nombreusuario) AS 'Name',
    GROUP_CONCAT(usuario_has_servicio.idservicio) as servicios
    FROM
    acceso.usuario
    INNER JOIN acceso.usuario_has_servicio ON usuario.idusuario = usuario_has_servicio.idusuario  WHERE usuario.idusuario = '$miarray'";
    
    $rs_crs                                                     = mysql_query($sql) or die(mysql_error());
    $account                                                    = mysql_fetch_assoc($rs_crs);
    
    $sqlrol = "SELECT usuario_has_rol.rol_idrol
    FROM
    acceso.usuario_has_rol
    WHERE
    usuario_has_rol.usuario_idusuario  = '$miarray'";
    
    $rs_AplicacionesxUsuarios                                   = mysql_query($sqlrol) or die(mysql_error());
    $row_rs_AplicacionesxUsuarios                               = mysql_fetch_assoc($rs_AplicacionesxUsuarios);
    $totalRows_rs_AplicacionesxUsuarios                         = mysql_num_rows($rs_AplicacionesxUsuarios);

    if($totalRows_rs_AplicacionesxUsuarios > 0){ 
        $i=0;
        do {
            $permiso[$i++] = $row_rs_AplicacionesxUsuarios['rol_idrol'];
        } while ($row_rs_AplicacionesxUsuarios = mysql_fetch_assoc($rs_AplicacionesxUsuarios));
    }
    if($account['rutusuario'] > 0){
        unset($_SESSION['permiso'.SessionName]);
        unset($_SESSION['serviciousuario'.SessionName]);
        unset($_SESSION['loggedin'.SessionName]);
        unset($_SESSION['MM_Username'.SessionName]);
        unset($_SESSION['start'.SessionName]);
        unset($_SESSION['expire'.SessionName]);
        unset($_SESSION['MM_UsernameName'.SessionName]);
        unset($_SESSION['MM_RUNUSU'.SessionName]);
        unset($_SESSION['AR_SERVER'.SessionName]);
        unset($_SESSION['CR_SERVER'.SessionName]);
        unset($_SESSION['pyxi_SERVER'.SessionName]);
        unset($_SESSION['BD_SERVER'.SessionName]);
        unset($_SESSION['tiempoInactividad'.SessionName]);
        unset($_SESSION['MM_Servicio_activo'.SessionName]);

        $_SESSION['permiso'.SessionName]            = $permiso;
        $arr_servicios                  = explode(', ', $account['servicios']);
        $_SESSION['serviciousuario'.SessionName]    = $arr_servicios;
        $_SESSION['loggedin'.SessionName]           = true;
        $_SESSION['MM_Username'.SessionName]        = $account['idusuario'];
        $_SESSION['start'.SessionName]              = time();
        $_SESSION['expire'.SessionName]             = $_SESSION['start'.SessionName] + (1 * 60) ;
        $_SESSION['MM_UsernameName'.SessionName]    = $account['Name'];
        $_SESSION['MM_RUNUSU'.SessionName]          = $account['rutusuario'];
        $_SESSION['AR_SERVER'.SessionName]          = '10.6.21.29';
        $_SESSION['CR_SERVER'.SessionName]          = '10.6.21.18';
        $_SESSION['pyxi_SERVER'.SessionName]        = '10.6.18.95';
        $_SESSION['BD_SERVER'.SessionName]          ='10.6.21.26';
        $_SESSION['tiempoInactividad'.SessionName]  = '7';
        $_SESSION['MM_Servicio_activo'.SessionName] = '';


         $_SESSION['permiso'.SessionName]            = $permiso;
        $arr_servicios                  = explode(', ', $account['servicios']);
        $_SESSION['serviciousuario'.SessionName]    = $arr_servicios;
        $_SESSION['loggedin'.SessionName]           = true;
        $_SESSION['MM_Username'.SessionName]        = $account['idusuario'];
        $_SESSION['start'.SessionName]              = time();
        $_SESSION['expire'.SessionName]             = $_SESSION['start'.SessionName] + (1 * 60) ;
        $_SESSION['MM_UsernameName'.SessionName]    = $account['Name'];
        $_SESSION['MM_RUNUSU'.SessionName]          = $account['rutusuario'];
        $_SESSION['AR_SERVER'.SessionName]          = '10.6.21.29';
        $_SESSION['CR_SERVER'.SessionName]          = '10.6.21.18';
        $_SESSION['pyxi_SERVER'.SessionName]        = '10.6.18.95';
        $_SESSION['BD_SERVER'.SessionName]          ='10.6.21.26';
        $_SESSION['tiempoInactividad'.SessionName]  = '7';
        $_SESSION['MM_Servicio_activo'.SessionName] = '';
    }
//} 
//////////////////////////////////////////////////////////////////////////////////////
require(PROYECTO."/views/modules/sesion_expirada.php");
require_once("class/Util.class.php"); $objUtil = new Util;


$permisos = $_SESSION['permiso'.SessionName];
$version = $objUtil->versionJS();

if ( $_SESSION['MM_Username'.SessionName] == null) {
  $GoTo = "../../acceso/index.php";
  header(sprintf("Location: %s", $GoTo));
}
  $username = $_SESSION['MM_UsernameName'.SessionName];

if (isset($_SESSION["MM_Username".SessionName])) {
  $nombre = $_SESSION["MM_UsernameName".SessionName];
  $rut    = $_SESSION['MM_RUNUSU'.SessionName];
}
$bandera = $_GET['bandera'];
?>

 <?php
        $usuarioMarcaAgua = strtoupper (substr($_SESSION['MM_Username'.SessionName], 0, 3)."".substr($_SESSION['MM_RUNUSU'.SessionName],-3));
    ?>

    <style type="text/css">
        body {
          background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' version='1.1' height='100px' width='100px'><text transform='translate(20, 100) rotate(-45)' fill='rgb(231, 226, 226)' font-size='20' ><?=$usuarioMarcaAgua?></text></svg>");
      }
    </style>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="description" content="">
        <meta name="author" content="">
        <title>OncoNet - HJNC</title>

        <!--CSS FILES-->
        <link rel="icon" href="/../../estandar/assets/img/logo_hospital.png">
        <link rel="stylesheet" type="text/css" href="/../../estandar/assets/css/add-bootstrap.css?v=<?=date('H.i.s')?>">
        <link href="/../../estandar/assets/frameworks/scheduler/scheduler.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/../../estandar/assets/frameworks/bootstrap4/css/bootstrap.min.css?v=<?=date('H.i.s')?>">
        <link rel="stylesheet" href="/../../estandar/assets/frameworks/bootstrap4/css/bootstrap-datepicker3.css?v=<?=date('H.i.s')?>"/>
        <link rel="stylesheet" href="/../../estandar/assets/libs/jQuery/jquery-ui-1.12.1.css?v=<?=date('H.i.s')?>">
        <link rel="stylesheet" href="/../../estandar/assets/libs/validity/jquery.validity.css?v=<?=date('H.i.s')?>" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="/../../estandar/assets/libs/sumoselect2/sumoselect.css?v=<?=date('H.i.s')?>">
        <link rel="stylesheet" type="text/css" href="/../../estandar/assets/libs/dropdown-hover/css/bootstrap-dropdownhover.min.css?v=<?=$version;?>">
        <link rel="stylesheet" type="text/css" href="/../../estandar/assets/libs/dateTimePicker/bootstrap-datetimepicker.css?v=<?=date('H.i.s')?>">
        <link rel="stylesheet" type="text/css" href="/../../estandar/assets/libs/DataTables/datatables.css?v=<?=date('H.i.s')?>"/>
        <link rel="stylesheet" type="text/css" href="/../../estandar/assets/libs/DataTables-1.10.16/css/dataTables.bootstrap4.min.css?v=<?=date('H.i.s')?>"/>
        <link rel="stylesheet" type="text/css" href="/../../estandar/assets/libs/bootstrap-select-1.13.14/dist/css/bootstrap-select.min.css"/>
        <!-- <link rel="stylesheet" type="text/css" href="/../../estandar/assets_onco/frameworks/MDB5-STANDARD/css/mdb.min.css"/> -->


        <link rel="stylesheet" href="../estandar/jquery.timeEntry/jquery.timeentry.css" type="text/css" />


        <!-- <link href="/../../estandar/assets_onco/css/css_tabla?v=<?=round(microtime(true) * 1000);?>" rel="stylesheet"> -->
        <script type="text/javascript" src="/../../estandar/assets/libs/jQuery/jquery-3.3.1.min.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/libs/jQuery/jquery-ui-1.12.1.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/js/popper.min.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/frameworks/bootstrap4/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/libs/sumoselect2/jquery.sumoselect.js?v=<?=date('H.i.s')?>"></script>
        <script type="text/javascript" src="/../../estandar/assets/frameworks/notify/notify.min.js"></script>
        <script type="text/javascript" src="<?=RAIZ?>/controllers/client/index/js_index.js?v=<?=date('H.i.s')?>"></script>
        <script type="text/javascript" src="/../../estandar/assets/libs/DataTables-1.10.16/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/libs/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
        <script type="text/javascript" src="../../estandar/datatable/export/dataTables.buttons.min.js"></script> <!-- botones export datetable -->
        <script type="text/javascript" src="../../estandar/datatable/export/buttons.flash.min.js"></script><!-- botones export datetable -->
        <script type="text/javascript" src="../../estandar/datatable/export/jszip.min.js"></script><!-- botones export datetable -->
        <script type="text/javascript" src="../../estandar/datatable/export/pdfmake.min.js"></script><!-- botones export datetable -->
        <script type="text/javascript" src="../../estandar/datatable/export/vfs_fonts.js"></script><!-- botones export datetable -->
        <script type="text/javascript" src="../../estandar/datatable/export/buttons.html5.min.js"></script><!-- botones export datetable -->
        <script type="text/javascript" src="../../estandar/datatable/export/buttons.print.min.js"></script><!-- botones export datetable -->
        <link rel="stylesheet" type="text/css" href="../../estandar/datatable/export/buttons.dataTables.min.css"><!-- botones export datetable -->
        <script type="text/javascript" src="/../../estandar/assets/libs/fontawesome-free-5.13.0-web/js/all.js?v=<?=date('H.i.s')?>"></script>
        <script type="text/javascript" src="<?=RAIZ?>/controllers/main.js?v=<?=date('H.i.s')?>"></script>
        <script type="text/javascript" src="/../../estandar/assets/libs/blockUI/jquery.blockUI.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/libs/moment-2.22.2/moment.min.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/libs/moment-2.22.2/es.js?v=<?=date('H.i.s')?>"></script>
        <script type="text/javascript" src="/../../estandar/assets/libs/validaCamposFranz/validCampoFranz.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/js/permisosUsuario.js?v=<?=$version;?>"></script>
        <script type="text/javascript" src="/../../estandar/assets/libs/dropdown-hover/js/bootstrap-dropdownhover.min.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/libs/Rut/rut.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/frameworks/bootstrap4/js/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/libs/validity/jquery.validity.core.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/libs/validity/jquery.validity.outputs.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/libs/bootstrap-select-1.13.14/dist/js/bootstrap-select.min.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js?v=<?=date('H.i.s')?>"></script>
        <script type="text/javascript" src="/../../estandar/assets/js/main_calendario.js?v=<?=$version;?>"></script>
        <script type="text/javascript" src="/../../estandar/assets/libs/dropdown-hover/js/bootstrap-dropdownhover.min.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/libs/dateTimePicker/moment.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/libs/dateTimePicker/locale/es.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/libs/dateTimePicker/bootstrap-datetimepicker.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/libs/bootstrapX/bootstrapx-clickover.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/frameworks/scheduler/scheduler.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/libs/mark/jquery.mark.min.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/frameworks/Highcharts/code/highcharts.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/frameworks/Highcharts/code/modules/data.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/frameworks/Highcharts/code/modules/drilldown.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/frameworks/Highcharts/code/modules/exporting.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/frameworks/print.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/js/oms/oms.min.js" ></script>
        <script type="text/javascript" src="/../../estandar/assets/js/chartjs/Chart.min.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/js/chartjs/utils.js"></script>
        <script type="text/javascript" src="/../../estandar/assets/js/charts/loader.js"></script>
        <link rel="stylesheet" type="text/css" href="/../../estandar/assets/libs/Bootstrap-4-Chosen-Plugin/dist/css/component-chosen.min.css"/>
        <script type="text/javascript"         src="/../../estandar/assets/libs/chosen_v1.8.7/chosen.jquery.min.js?v=12"></script>
        <!-- <script type="text/javascript"         src="/../../estandar/assets_onco/frameworks/MDB5-STANDARD/js/mdb.min.js"></script> -->

        <script type="text/javascript" src="../estandar/jquery.timeEntry/jquery.timeentry.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                view("#contenido");
            });
        </script>
    </head>

    <body class="">

        <div id="wrapper">

            <?php
                if($_GET['bandera'] == 'aprobarIndicacion'){ ?>

                    <input type="hidden" name="CITcodigo"        id="CITcodigo"         value="<?=$_GET['CITcodigo']?>">
                    <input type="hidden" name="PACidentificador" id="PACidentificador"  value="<?=$_GET['PACidentificador']?>">
                    <input type="hidden" name="idRCE"            id="idRCE"             value="<?=$_GET['idRCE']?>">
                    <input type="hidden" name="bandera"          id="bandera"           value="<?=$_GET['bandera']?>">


                    <div id="contenido" class="col-xs-12 col-md-12 col-lg-12"></div>

            <?php }elseif($_GET['bandera'] == 'generarEsquema'){ ?>
                <input type="hidden" name="CITcodigo"        id="CITcodigo"        value="<?=$_GET['CITcodigo']?>">
                <input type="hidden" name="PACidentificador" id="PACidentificador" value="<?=$_GET['PACidentificador']?>">
                <input type="hidden" name="idRCE"             id="idRCE"           value="<?=$_GET['idRCE']?>">
                <input type="hidden" name="bandera"          id="bandera"           value="<?=$_GET['bandera']?>">


                <div id="contenido" class="col-xs-12 col-md-12 col-lg-12"></div>
            <?php }else{?>
            <?php   include("./views/modules/menu/menu.php"); ?>
            <div id="page-wrapper" class="py-3"> 
                    <div id="contenido" class="col-xs-12 col-md-12 col-lg-12"></div>
                    <button alt="Arriba" type="button" class="btn btn-primary scrollToTop"><i class="fas fa-angle-up"></i></button>
            </div>
            <?php }?>
        </div>
    </body>

</html>

