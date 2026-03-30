<?php 
error_reporting(0);
session_start(); 
require("config/config.php");

require_once('class/Connection.class.php');    $objCon              = new Connection; 
$objCon->db_connect();
require_once('class/menu_colores.class.php');   $objMenu_colores     = new Menu_colores;
require_once("class/Dau.class.php");            $objDau              = new Dau();
require(PROYECTO. "/assets/libs/identificacion_hjnc/index.php");

$permisos = $_SESSION['permiso'.SessionName];

// print('<pre>'); print_r($_SESSION); print('</pre>');

$parametros['usuario']                  = $_SESSION['MM_Username'.SessionName];
$resultadoConsulta = $objDau->obtenerPerfilUsuario($objCon,$parametros);
if($resultadoConsulta[0]['contadorPerfilEnfermero'] != ""){
    $parametrosColor['PROcodigo'] =  $_SESSION['MM_RUNUSU'.SessionName];
    $rsSelectProfesional = $objMenu_colores->SelectProfesional($objCon,$parametrosColor);
    if($rsSelectProfesional[0]['TIPROcodigo'] == 3 ){
        $resultadoConsulta[0]['contadorPerfilMatrona'] = 1;
    }
}

if ( $resultadoConsulta[0]['contadorPerfilMedico'] > 0 ) {
    $colorParam['tipo_color'] = '1';
}   else if ( $resultadoConsulta[0]['contadorPerfilMatrona'] > 0 ) {
    $colorParam['tipo_color'] = '2';
}   else if ( $resultadoConsulta[0]['contadorPerfilTens'] > 0 ) {
    $colorParam['tipo_color'] = '3';
}   else if ( $resultadoConsulta[0]['contadorPerfilEnfermero'] > 0  ) {
    $colorParam['tipo_color'] = '4';
}   else if ( $resultadoConsulta[0]['contadorPerfilAdministrativo'] > 0  ) {
    $colorParam['tipo_color'] = '5';
}   else if ( $resultadoConsulta[0]['contadorPerfilFull'] > 0  ) {
    $colorParam['tipo_color'] = '6';
}   else{
    $colorParam['tipo_color'] = '0';
}


$parametrosColores['tipo'] = $colorParam['tipo_color'];

$rsColores                 = [];//$objMenu_colores->SelectMenu_colores($objCon,$parametrosColores);
if(count($rsColores) == 0){
    $rsColores[0]['color_texto'] =  "#252525 !important;";
    $rsColores[0]['color_barra'] =  "linear-gradient(97deg, #84cf94, #ffffff, #ffffff)";
}



?>
<style type="text/css">
    .scrollModal {
        max-height: calc(87vh - 70px); height: calc(88vh - 70px); overflow-y: auto
    }

    .scrollModal-lg {
        max-height: calc(92vh - 70px);
        height: calc(94vh - 70px);
        overflow-y: auto;
    }
    @media print {
        body.modal-open {
            visibility: hidden;
        }
        .modal {
            position: static !important;
            visibility: visible !important;
            overflow: visible !important;
            width: 100% !important;
            height: auto !important;
            max-height: none !important;
        }
        .modal-dialog {
            margin: 0;
            width: 100%;
        }
        .modal-content {
            border: none;
        }
    }
    .color-E7F4FF{
    background-color: #e1f1ff;
    }
    .color-F0FFF0{
    background-color: #e3ffe1;
    }
    .color-FFF0F6{
    background-color: #fde0e0;
    }
    .text-primary-light{
    color:#59a9ff;
    }
    .ui-autocomplete{
    z-index: 9999;
    }.mifuente9 {
    font-size: 9px !important;
    }
    .encabezado2 {
    font-size: .8rem !important;
    color: #0e78c3;
    }
    .encabezado{
    vertical-align:middle;
    }
    .border-system {
        border-color: #e16a76!important;
    }
    .select2-container .select2-selection--single .select2-selection__rendered{
        
        padding-top: 4px;
    }
    .select2 select2-container select2-container--default select2-container--disabled select2-container--focus{
        width: 100% !important;
        padding-top: 4px;
        font-size: 12px;
    }
    .select2 {
        width: 100% !important;
        font-size: 12px;
    }
    .select2 select2-container select2-container--default select2-container--disabled select2-container--focus{
        width: 100% !important;
    }
    .select2-container{
        font-size: 12px !important;
    }

    .datepicker {
    z-index: 1050 !important; /* Asegura que esté sobre otros elementos */
    left: 0px !important;
    }
    .ScrollStyleModal{
    max-height: calc(100vh - 180px);
    overflow-x: hidden;
    }
    .input-group .datepicker-dropdown {
    position: absolute !important; /* Corrige la posición del calendario */
    }
    .dropdown-menu {
    min-width: 220px;
    }
    .datepicker {
    z-index: 1050 !important; /* Asegura que esté sobre otros elementos */
    left: 0px !important;
    }

    .input-group .datepicker-dropdown {
    position: absolute !important; /* Corrige la posición del calendario */
    }
    .btn-outline-primarydiag {
    color: #007bff;
    background-color: transparent;
    background-image: none;
    border-color: #007bff;
    }
    .indigo.lighten-1 {
    background-color: #b8daff !important;
    }
    .testimonial-card .card-up {
    height: 42px;
    overflow: hidden;
    border-top-left-radius: 0.25rem;
    border-top-right-radius: 0.25rem;
    }
    .font-weight-bolder{
    font-weight: 500 !important
    }
    .catAct_1_detDau{
    background-color: #e55656;
    /*margin-left: -33px;*/

    color: #fff;
    }
    .catAct_2_detDau{
    background-color: #f19b47;
    /*margin-left: -33px;*/

    color: #fff;
    }
    .catAct_3_detDau{
    background-color: #fcd350;
    /*margin-left: -33px;*/

    color: #fff;
    }
    .catAct_4_detDau{
    background-color: #6cb061;
    /*margin-left: -33px;*/

    color: #fff;
    }
    .catAct_5_detDau{
    background-color: #4676b6;
    /*margin-left: -33px;*/

    color: #fff;
    }
    .border-primary1 {
    border-color: #176b87 !important;
    /*box-shadow: 0 0px 4px 1px rgba(0, 0, 0, 0.3);*/
    }
    .tooltip-inner {
    text-align: justify;
    font-size: 12px;
     max-width: none; /* Quitar el ancho máximo predeterminado */
    width: auto; 
    }
    .bd-callout-warning {
    border-left-color: #f0ad4e;
    }
    .bd-callout {
    padding-right: 1.25rem;
    padding-left: 1.25rem;

    padding: 0.6rem;
    /*margin-top: 1.25rem;*/
    /*margin-bottom: 1rem;*/
    /*border: 1px solid #176B87;*/
    border-left-width: .25rem;
    border-radius: .25rem;
    }
    .texto-valor{
    font-weight: 500;
    font-size: 13px !important;
    color: #000000;
    }
    .contPacRecidencia {
    position: relative;
    display: block;
    }
    /*.iconInicioAte {
    font-size: 9px;
    position: absolute;
    color: #000;
    left: 9%;
    }*/
    .dropdown-item.active, .dropdown-item:active {
        color: #176B87 !important;
        text-decoration: none;
        background-color: #eef5ff00;
    }
    .dropdown-item:focus, .dropdown-item:hover {
        color: #176B87 !important;
        text-decoration: none;
        background-color: #eef5ff00;
    }
    .bg-barra{

        background-color : <?=$rsColores[0]['color_barra'];?>;
        background : <?=$rsColores[0]['color_barra'];?>;
        /*background-color: #EEF5FF;*/
    }.bg-barraRed{
        background-color: #ffe2e2;
    }
     .darkcolor-barra2  {
        /*color: #176B87!important;*/
        color : <?=$rsColores[0]['color_texto'];?>;
    }
     .darkcolor-barra2Red  {
        color: #c31c00!important;
    }
    .text-dangerLight{
        color: #f36976!important;
    }
    .nav-link:hover {
        color: <?=$rsColores[0]['color_texto'];?>;
        border-bottom-color: <?=$rsColores[0]['color_texto'];?>;
    }
    .nav-link {
        border: 2px solid #eef5ff00; /* Ajusta según tus necesidades */
    }
    .btn-primary2 {
        color: #fff;
        background-color: #176B87;
        border-color: #176B87;
        box-shadow: 0 6px 10px 2px rgba(0, 0, 0, 0.3) !important;
    }
    .btn-primary21 {
        color: #fff;
        background-color: #176B87;
        border-color: #176B87;
    }.hide {
      display: none !important;
    }
    .btn-secondary2 {
        color: #176B87;
        background-color: #B4D4FF;
        border-color: #B4D4FF;

    }
    .btn-secondary2:not(:disabled):not(.disabled).active, .btn-secondary2:not(:disabled):not(.disabled):active, .show>.btn-secondary2.dropdown-toggle {
        color: #176B87;
        background-color: #86B6F6;
        border-color: #86B6F6;
    }
    .btn-secondary2:hover {
        color: #176B87;
        background-color: #86B6F6;
        border-color: #86B6F6;
    }
    .btn-outline-secondary2 {
        color: #176B87;
        background-color: transparent;
        background-image: none;
        border-color: #B4D4FF;animation: removeFocus 0.1s forwards;
        outline: none;

    }
    .btn-outline-secondary2:hover {
        color: #176B87;
        background-color: #B4D4FF;
        border-color: #B4D4FF;
        animation: removeFocus 0.1s forwards;
        outline: none;
    }
    .page-item.active .page-link {
        z-index: 1;
        color: #176b87;
        background-color: #b4d4ff;
        border-color: #b4d4ff;
        box-shadow: 0 4px 2px -2px rgba(0, 0, 0, 0.3);
    }
    .page-link {
        position: relative;
        display: block;
        padding: .5rem .75rem;
        margin-left: -1px;
        line-height: 1.25;
        color: #176b87;
        background-color: #fff;
        border: 1px solid #dee2e6;
        box-shadow: 0 4px 2px -2px rgba(0, 0, 0, 0.3);
    }
    .nav-link.active {
        color: <?=$rsColores[0]['color_texto'];?>;
        border-bottom-color: <?=$rsColores[0]['color_texto'];?>;
    }
    .shadow{
        box-shadow: 0 4px 2px -2px rgba(0, 0, 0, 0.3); /* sombra */
    }
    .modal-lgg {
        max-width: 98% !important;
    }
    .table-primary3, .table-primary3>td, .table-primary3>th {
        background-color: #176b87;
        color: #ffffff;
    }
    .tr_tblCat-default{
        border: 1px solid #cacaca;
    }
    .tr_tblCat-init{
        background-color: #f8f8f8;
    }
    .tr_tblCat-ESI-1{  /* inicio  RAA */
        /*border: 1px solid #E53256;*/
        background-color: #f1bfbf !important;
    }
    .tr_tblCat-ESI-2{
        background-color: #fdd8b4 !important;
        /*border: 1px solid #F19B47;*/
    }
    .tr_tblCat-ESI-3{
        /*border: 1px solid #ffe55b;*/
        background-color: #fbfdbd !important;
    }
    .tr_tblCat-ESI-4{
        /*border: 1px solid #508261;*/
        background-color: #ccefc4 !important;
    }
    .tr_tblCat-ESI-5{
        /*border: 1px solid #505AAA;*/
        background-color: #c5e7f8 !important;    /* FIN  RAA */
    }
    .tr_tblCat-1{
        border: 1px solid #E53256 !important;;
        background-color: #f1bfbf !important;;
    }
    .tr_tblCat-2{
        background-color: #fdd8b4 !important;;
        border: 1px solid #F19B47 !important;;
    }
    .tr_tblCat-3{
        border: 1px solid #ffe55b !important;;
        background-color: #fbfdbd !important;;
    }
    .tr_tblCat-4{
        border: 1px solid #508261 !important;;
        background-color: #ccefc4 !important;;
    }
    .tr_tblCat-5{
        border: 1px solid #505AAA !important;;
        background-color: #c5e7f8 !important;;
    }
    .responsive-container {
        max-height: calc(80vh - 70px);
        height: auto;
        overflow-y: auto;
    }

      a{
        color: #176b87 !important;
      }
    /* Media queries for responsive adjustments */
    @media (max-width: 1200px) {
        .responsive-container {
            max-height: calc(70vh - 100px);
        }
    }

    @media (max-width: 992px) {
        .responsive-container {
            max-height: calc(60vh - 90px);
        }
    }

    @media (max-width: 768px) {
        .responsive-container {
            max-height: calc(50vh - 80px);
        }
    }

    @media (max-width: 576px) {
        .responsive-container {
            max-height: calc(40vh - 70px);
        }
    }
    .well-camas-verde {
    width: 60%;
    /*min-height: 43px;*/
    min-height: 35px;
    max-height: 43px;
    /*max-height: 61px;*/
    padding: 5px 0px 5px 0px;
    margin-bottom: 1px;
    background-color: #6cb061; /*f5f5f5*/
    border: 1px solid #b7b7b7;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
    }
    .well-camas-amarillo {
    background-color: #ffc107 !important;
    }
    .well-camas-naranja {
    background-color: #ff8100 !important;
    }
    .well-camas-fucsia {
    background-color: #d954d9 !important;
    }
    .well-camas-rojo {
    background-color: #dc3545 !important;
    }
    .well-camas-plomo { 
    background-color: #d3dbe5  !important;
    border: 1px solid #e3e3e3;
    }
    .text-downleft-default {
    position: absolute;
    /* top: 0; */
    bottom:10%;
    /* right: 15px; */
    z-index: 10;
    display: block;
    /*padding: 3px 5px;*/
    padding: 0px 2px;
    font-size: 9px;
    color: white;
    font-weight: bold;
    /* cursor: pointer; */
    /*background-color: #b346b6;*/
    background-color: #9d9d9d;
    border: 1px solid #e1e1e8;
    /* border-radius: 0 4px 0 4px; */
    border-radius: 0px 4px 0px 4px;
    }

    .text-downleft-1 {
    position: absolute;
    /* top: 0; */
    bottom:0%;
     left: 0px; 
    z-index: 10;
    display: block;
    /*padding: 4px 5px;
    font-size: 12px;*/
    /*padding: 2px 3px;*/
    padding: 0px 2px;
    font-size: 10px;
    color: white;
    font-weight: bold;
    /* cursor: pointer; */
    background-color: #e55656;
    border: 1px solid #e1e1e8;
    /* border-radius: 0 4px 0 4px; */
    border-radius: 0px 4px 0px 4px;
    }
    .text-downleft-2 {
    position: absolute;
    /* top: 0; */
    bottom:0%;
     left: 0px; 
    z-index: 10;
    display: block;
    /*padding: 4px 5px;
    font-size: 12px;*/
    /*padding: 2px 3px;*/
    padding: 0px 2px;
    font-size: 10px;
    color: white;
    font-weight: bold;
    /* cursor: pointer; */
    background-color: #f19b47;
    border: 1px solid #e1e1e8;
    /* border-radius: 0 4px 0 4px; */
    border-radius: 0px 4px 0px 4px;
    }
    .text-downleft-3 {
    position: absolute;
    /* top: 0; */
    left: 0px;
    bottom:0%;
    /* right: 15px; */
    z-index: 10;
    display: block;
    /*padding: 4px 5px;
    font-size: 12px;*/
    /*padding: 2px 3px;*/
    padding: 0px 2px;
    font-size: 10px;
    color: white;
    font-weight: bold;
    /* cursor: pointer; */
    background-color: #fcd350;
    border: 1px solid #e1e1e8;
    /* border-radius: 0 4px 0 4px; */
    border-radius: 0px 4px 0px 4px;
    }
    .text-downleft-4 {
    position: absolute;
    /* top: 0; */
    left: 0px;
    bottom:0%;
    /* right: 15px; */
    z-index: 10;
    display: block;
    /*padding: 4px 5px;
    font-size: 12px;*/
    /*padding: 2px 3px;*/
    padding: 0px 2px;
    font-size: 10px;
    color: white;
    font-weight: bold;
    /* cursor: pointer; */
    background-color: #6cb061;
    border: 1px solid #e1e1e8;
    /* border-radius: 0 4px 0 4px; */
    border-radius: 0px 4px 0px 4px;
    }
    .text-downleft-5 {
    position: absolute;
    /* top: 0; */
    bottom:0%;
    left: 0px;
    /* right: 15px; */
    z-index: 10;
    display: block;
    /*padding: 4px 5px;
    font-size: 12px;*/
    /*padding: 2px 3px;*/
    padding: 0px 2px;
    font-size: 10px;
    color: white;
    font-weight: bold;
    /* cursor: pointer; */
    background-color: #4676b6;
    border: 1px solid #e1e1e8;
    /* border-radius: 0 4px 0 4px; */
    border-radius: 0px 4px 0px 4px;
    }
    .shadow{
        text-shadow: 1px 2px 14px #777777;
    }
    img {
    vertical-align: middle;
    }
    .imagenPaciente {
    position: relative;
    /* width: 22px; */
    width: 17px;
    }
    .text-downright {
    position: absolute;
    bottom: 0%;
    right: 0px;
    z-index: 10;
    display: block;
    font-size: 14px;
    color: #ffffff;
    margin-bottom: 2px;
    margin-right: 3px;
    }

    .text-upleft-custom {
    position: absolute;
    top: 0px;
    left: 0px;
    z-index: 10;
    display: block;
    font-size: 14px;
    color: #ffffff;
    /* cursor: pointer; */
    margin-top: 1px;
    margin-left: 2px;
    }

    .text-upright-custom {
    position: absolute;
    top: 0px;
    right: 0px;
    z-index: 10;
    display: block;
    font-size: 14px;
    color: #ffffff;
    /* cursor: pointer; */
    margin-top: 1px;
    margin-left: 2px;
    }
    .highlight2 {
    background-color: #232121 !important;
    }
    .throb2 {
    -webkit-animation: pulsate 1s ease-out;
    -webkit-animation-iteration-count: infinite;
    }
    .flashingBorder {
    border: 3px solid red; /* Color inicial del borde */
    border-radius: 5px; /* Opcional: Redondea los bordes */
    animation: flashingBorder 0.5s infinite; /* Aplica la animación */
    }

    /* Definición de la animación */
    @keyframes flashingBorder {
    0% {
        border-color: red; /* Color inicial */
    }
    50% {
        border-color: transparent; /* Color intermedio (transparente) */
    }
    100% {
        border-color: red; /* Vuelve al color inicial */
    }
    }

    .highlight {
        border: 3px solid #176b87;
    }
    div.dataTables_wrapper div.dataTables_length label {
        display: none;
    }
    div.dataTables_wrapper div.dataTables_filter {
        color: #337ab7;
        font-size: 10px;
        font-family: 'SourceSansPro-Semibold', Fallback, sans-serif;
    }
    mark {
      background: yellow;
    }
    mark.current {
      background: orange;
    }
    .sintomasRespiratorios {
        border: 2px solid;
        border-color: black;
    }
    /*   .tooltip-inner {

        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' version='1.1' height='100px' width='50px'><text transform='translate(20, 100) rotate(-45)' fill='rgb(180, 180, 180)' font-size='13'><?php echo $usuarioMarcaAgua; ?></text></svg>");
    }*/
    body {

        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' version='1.1' height='100px' width='100px'><text transform='translate(20, 100) rotate(-45)' fill='rgb(231, 226, 226)' font-size='20' ><?php echo $usuarioMarcaAgua; ?></text></svg>");
    }
    .grid-container {
        display: grid;
        grid-auto-flow: column;
        grid-auto-columns: minmax(50px, 1fr);
        grid-template-rows: repeat(auto-fill, 51px);
        gap: 10px;
        height: 71vh; /* Ajusta la altura según tus necesidades */
        /*border: 1px solid #dee2e6;*/
        padding: 9px;
        /*box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);*/
        background-color: #ffffff00;
        overflow: auto; /* Agrega desplazamiento si hay demasiados elementos */
    }
    .grid-containerMapa {
        display: grid;
        grid-auto-flow: column;
        grid-auto-columns: minmax(50px, 1fr);
        grid-template-rows: repeat(auto-fill, 51px);
        gap: 10px;
        height: 500px; /* 500 para 8 camas por lado */
        /*border: 1px solid #dee2e6;*/
        padding: 2px;
        /*box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);*/
        background-color: #ffffff00;
        overflow: auto; /* Agrega desplazamiento si hay demasiados elementos */
    }
    .grid-item {
        justify-self: center;
        position: relative;
        background-color: #6cb061;
        color: #ffffff;
        width: 45px; /* Tamaño fijo de 50px */
        height: 43px; /* Tamaño fijo de 50px */
        font-size: 1.2em;
        text-align: center;
        border-radius: 5px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .grid-item i {
        position: absolute;
        font-size: 0.8em;
    }

    .grid-item i:first-child {
        top: 5px;
        left: 5px;
    }

    .grid-item i:last-child {
        top: 5px;
        right: 5px;
    }

    .grid-item img {
        width: 30px;
        height: 30px;
    }
    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active{
        color: white !important;
        background-color: #176b87;
        border-color: #176b87 #176b87  #176b87 ;
    }
    .deshabilitarTR{
        display: none !important;
    }

    .habilitarTR{
        display: block !important;
    }
    .mifuente13{
        font-size: 13px !important;
    }
</style>
<div class="bg-barra">
<nav class="navbar navbar-light  row mifuente14 mb-0 pb-0">
    <!-- <div class="row"> -->
        <div class="col-lg-6"> 
            <span class="navbar-text darkcolor-barra2" style="padding:0.0rem 1rem;">
        <i class="fas fa-envelope mr-2 text-dangerLight"></i>
        <a href="https://mail.google.com/mail?view=cm&amp;fs=1&amp;tf=1&amp;to=mesadeayuda@hjnc.cl&amp;su=Sistema%20DAURCE" target="_blank">
            mesadeayuda@hjnc.cl
        </a>
        <i class="fas fa-mobile-alt mr-2 ml-3 text-dangerLight"></i>584685
        <i class="fas fa-mobile-alt mr-2 ml-2 text-dangerLight"></i>584686
        <i class="fas fa-mobile-alt mr-2 ml-2 text-dangerLight"></i>584679
    </span> </div>
        <div class="col-lg-6"> <ul class="list-unstyled  darkcolor-barra2 mb-0 pb-0" style="top: -8px; font-size: 20px; position: absolute;
    z-index: 1;">
          <li id="icon-tens"            style="display: none;" ><i class="fas fa-user-nurse " ></i> TENS</li>
          <li id="icon-enfermero"       style="display: none;" ><i class="fas fa-syringe " ></i> Enfermero</li>
          <li id="icon-matrona"         style="display: none;" ><i class="fas fa-baby " ></i> Matrona</li>
          <li id="icon-medico"          style="display: none;" ><i class="fas fa-stethoscope " ></i> Médico</li>
          <li id="icon-administrativo"  style="display: none;" ><i class="fas fa-hospital-user " ></i> Administrativo</li>
          <li id="icon-full"            style="display: none;" ><i class="fas fa-user-shield " ></i> Full</li>
        </ul> </div>
</nav>
<nav class="navbar navbar-expand-lg navbar-dark  py-0 pb-0" style="font-size: 14px; box-shadow: 0 4px 2px -2px rgba(0, 0, 0, 0.3); /* sombra */">
    <a class="navbar-brand mb-0 h1 darkcolor-barra2 ml-2 " href="#" id="homeDau"><img alt="Logo base" width="30" style="padding-bottom: 10px;" src="/../../estandar/assets/img/logo_hospital.png"> DAU<label class="darkcolor-barra2 mifuente14">RCE</label> &nbsp;&nbsp;&nbsp;</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse " id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">

            <?php if ( array_search(811, $_SESSION['permiso'.SessionName]) != null ) { ?>
            <li class="nav-item  mx-0">
                <a id="viewAdmision" class="nav-link dropdown-item darkcolor-barra2 mifuente12" href="#" role="button" data-toggle="" aria-haspopup="true"  aria-expanded="false"> <i class="fas fa-clipboard-check mifuente15 darkcolor-barra2 mr-1"></i> Admisión </a>
            </li>
            <?php } ?>
            <?php if ( array_search(830, $_SESSION['permiso'.SessionName]) != null ) { ?>
            <li class="nav-item  mx-0">
                <a id="viewConsulta" class="nav-link -toggle dropdown-item darkcolor-barra2 mifuente12" href="#" role="button" data-toggle="" aria-haspopup="true"  aria-expanded="false"> <i class="fas fa-search-plus mifuente15 darkcolor-barra2 mr-1"></i> Consulta </a>
            </li>
            <?php } ?>
            <?php if ( array_search(831, $_SESSION['permiso'.SessionName]) != null ) { ?>
            <li class="nav-item  mx-0">
                <a id="mapa_piso" class="nav-link -toggle dropdown-item darkcolor-barra2 mifuente12" href="#" role="button" data-toggle="" aria-haspopup="true"  aria-expanded="false">  MP Adulto y Pediátrico </a>
            </li>
            <?php } ?>
            <?php if ( array_search(832, $_SESSION['permiso'.SessionName]) != null ) { ?>
             <li class="nav-item  mx-0">
                <a id="mapa_piso_gine" class="nav-link -toggle dropdown-item darkcolor-barra2 mifuente12" href="#" role="button" data-toggle="" aria-haspopup="true"  aria-expanded="false">  MP Ginecología </a>
            </li>
            <?php } ?>
            <?php if (
            in_array(1327, $_SESSION['permiso'.SessionName])
            || in_array(1015, $_SESSION['permiso'.SessionName])
            || in_array(1134, $_SESSION['permiso'.SessionName])
            || in_array(1125, $_SESSION['permiso'.SessionName])
            ) { ?>
            <li class="nav-item dropdown mx-0">
                <a class="nav-link dropdown-toggle darkcolor-barra2 mifuente12" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"  aria-expanded="false"> <i class="fas fa-paper-plane mifuente15 darkcolor-barra2"></i>&nbsp; Solicitudes </a>
                <div class="dropdown-menu  " aria-labelledby="navbarDropdown">
                    <?php if (in_array(1327, $_SESSION['permiso'.SessionName])) { ?>
                    <a id="sol_especialista" class="dropdown-item darkcolor-barra2 mifuente" href="#"> <i class="fas fa-users darkcolor-barra2 mifuente15"></i>&nbsp;&nbsp;Solicitud Especialista</a>
                    <?php } ?>
                    <?php if (in_array(1125, $_SESSION['permiso'.SessionName])) { ?>
                    <a id="sol_aps" class="dropdown-item darkcolor-barra2 mifuente" href="#"> <i class="fas fa-file darkcolor-barra2 mifuente15"></i>&nbsp;&nbsp;Solicitud APS</a>
                    <?php } ?>
                </div>
            </li>
            <?php } ?>
            <?php if ( array_search(1326, $_SESSION['permiso'.SessionName]) != null ) { ?>

            <li class="nav-item  mx-0">
                <a id="indicaciones" class="nav-link -toggle dropdown-item darkcolor-barra2 mifuente12" href="#" role="button" data-toggle="" aria-haspopup="true"  aria-expanded="false"><i class="fas fa-plus-square mifuente15 darkcolor-barra2 mr-1"></i> Indicaciones </a>
            </li>


            <?php } ?>
            <?php if ( array_search(1328, $_SESSION['permiso'.SessionName]) != null || array_search(1759, $_SESSION['permiso'.SessionName]) != null ) { ?>
                <!-- 1328 -->
            <li class="nav-item dropdown mx-0">
                <a class="nav-link dropdown-toggle darkcolor-barra2 mifuente12" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"  aria-expanded="false"> <i class="fas fa-exchange-alt mifuente15 darkcolor-barra2"></i>&nbsp; Turno CR Urgencia </a>
                <div class="dropdown-menu  " aria-labelledby="navbarDropdown">
                    <a id="turnoCRUrgencia" class="dropdown-item darkcolor-barra2 mifuente" href="#"> <i class="fas fa-exchange-alt darkcolor-barra2 mifuente15"></i>&nbsp;&nbsp;Crear Resumen Turno Médica</a>
                    <a id="verTurnosCRUrgencia" class="dropdown-item darkcolor-barra2 mifuente" href="#"> <i class="fas fa-exchange-alt darkcolor-barra2 mifuente15"></i>&nbsp;&nbsp;Ver Resúmenes Turno CR Urgencia</a>
                <?php if ( array_search(1759, $_SESSION['permiso'.SessionName]) != null ) { ?>
                    <a id="turnoCRUrgenciaEnfermeria" class="dropdown-item darkcolor-barra2 mifuente" href="#"> <i class="fas fa-exchange-alt darkcolor-barra2 mifuente15"></i>&nbsp;&nbsp;Crear Resumen Turno Enfermeria</a>
                <?php } ?>
                <a id="pizarraEnfermeria" class="dropdown-item darkcolor-barra2 mifuente" href="#"> <i class="fas fa-highlighter darkcolor-barra2 mifuente15"></i>&nbsp;&nbsp; Pizarra</a>

                </div>
            </li>

            <?php } ?>
            <?php if ( array_search(833, $_SESSION['permiso'.SessionName]) != null ) { ?>
            <li class="nav-item dropdown mx-0">
                <a class="nav-link dropdown-toggle darkcolor-barra2 mifuente12" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"  aria-expanded="false"> <i class="fas fa-file mifuente15 darkcolor-barra2"></i>&nbsp; Reportes </a>
                <div class="dropdown-menu  " aria-labelledby="navbarDropdown">
                    <a id="reportes" class="dropdown-item darkcolor-barra2 mifuente" href="#"> <i class="fas fa-file darkcolor-barra2 mifuente15"></i>&nbsp;&nbsp;Reportes en General</a>
                <?php if ( array_search(1115, $_SESSION['permiso'.SessionName]) != null ) { ?>
                    <a id="reporteGraficoEnfermedadesEpidemiologicas" class="dropdown-item darkcolor-barra2 mifuente" href="#"> <i class="fas fa-file darkcolor-barra2 mifuente15"></i>&nbsp;&nbsp;Reporte Gráfico Enfermedades Epidemiológicas</a>
                    <a id="enfermedadesEpidemiologicas" class="dropdown-item darkcolor-barra2 mifuente" href="#"> <i class="fas fa-file darkcolor-barra2 mifuente15"></i>&nbsp;&nbsp;Reporte Enfermedades Epidemiológicas</a>
                    <a id="reporteTiemposCRUrgencia" class="dropdown-item darkcolor-barra2 mifuente" href="#"> <i class="fas fa-file darkcolor-barra2 mifuente15"></i>&nbsp;&nbsp;Reporte Tiempos CR Urgencia</a>
                    <a id="reporteRendimientoCRUrgencia" class="dropdown-item darkcolor-barra2 mifuente" href="#"> <i class="fas fa-file darkcolor-barra2 mifuente15"></i>&nbsp;&nbsp;Reporte Rendimiento CR Urgencia</a>
                    <a id="reporteTiemposCiclo" class="dropdown-item darkcolor-barra2 mifuente" href="#"> <i class="fas fa-file darkcolor-barra2 mifuente15"></i>&nbsp;&nbsp;Reporte Tiempos de Ciclo</a>
                    <a id="reportes_pat_ges" class="dropdown-item darkcolor-barra2 mifuente" href="#"> <i class="fas fa-file darkcolor-barra2 mifuente15"></i>&nbsp;&nbsp;Reporte Patologias GES</a>
                <?php } ?>
                <?php if ( array_search(1558, $_SESSION['permiso'.SessionName]) != null ) { ?>
                    <a id="resportesDiariosDAURCE" class="dropdown-item darkcolor-barra2 mifuente" href="#"> <i class="fas fa-file darkcolor-barra2 mifuente15"></i>&nbsp;&nbsp;Reportes Diarios DAU-RCE</a>
                <?php } ?>
                </div>
            </li>
            <?php } ?>
            <!-- <li class="nav-item  mx-0">
                <a class="nav-link -toggle darkcolor-barra2 mifuente12" href="#" role="button" data-toggle="" aria-haspopup="true"  aria-expanded="false"><i class="fas fa-file-medical mifuente15 darkcolor-barra2 mr-1"></i>  Solicitud APS </a>
            </li> -->
           
        </ul>
        <li class="nav-item dropdown pt-2 mr-2 text-danger throb">
                <i class="far fa-clock  "></i> <label id="tiempo"></label>
            </li>
        <div class="navbar-nav ">

            <div class="nav-item dropleft ">
                <div class="nav-link dropdown-toggle dropleft darkcolor-barra2" data-toggle="dropdown" aria-haspopup="true"  ><i class="fas fa-id-card-alt" style="font-size: 20px;"></i>&nbsp;&nbsp;<?=$_SESSION['MM_UsernameName'.SessionName]?></div>
                <div   class="dropdown-menu dropdown" aria-labelledby="navbarDropdown">
                    <a  id="cambiarSesion" class=" darkcolor-barra2"  style="display: block; width: 100%; padding: .25rem 1.5rem; clear: both; font-weight: 400; color: #212529; text-align: inherit; white-space: nowrap; background-color: transparent; border: 0;"href="#"><i  class="fas fa-user-friends mifuente15 darkcolor-barra2 mr-3"></i>Cambiar Sesión</a>
                    <a  id="closeSystem" class="dropdown-item darkcolor-barra2" href="#"><i  class="fas fa-sign-out-alt mifuente15 darkcolor-barra2 mr-3"></i>Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </div>
</nav>
</div>