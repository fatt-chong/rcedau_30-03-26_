<?php
error_reporting(0); 
  require("../../../../config/config.php");
  require_once('../../../../class/Connection.class.php');       $objCon      = new Connection; $objCon->db_connect();
  require_once('../../../../class/Util.class.php');             $objUtil     = new Util; 
  require_once('../../../../class/Reportes.class.php');         $reporte     = new Reportes; 
	
   //highlight_string(print_r($pediatrico,true));
if($_POST){ 
   $parametros                    = $objUtil->getFormulario($_POST); 
   $_SESSION['views']["reportes"] = $parametros;
   $parametros['fechaInicio']     = $objUtil->cambiarFormatoFecha2($parametros['frm_fecha_admision_desde']);
   $parametros['fechaFin']        = $objUtil->cambiarFormatoFecha2($parametros['frm_fecha_admision_hasta']);
   $datos                         = $reporte->resumenTiemposEspera($objCon,$parametros);
    
}else if(isset($_SESSION['views']["reportes"])){
   $parametros                  = $_SESSION['views']["reportes"];
   $parametros['fechaInicio']   = $objUtil->cambiarFormatoFecha2($parametros['frm_fecha_admision_desde']);
   $parametros['fechaFin']      = $objUtil->cambiarFormatoFecha2($parametros['frm_fecha_admision_hasta']);
   $datos                       = $reporte->resumenTiemposEspera($objCon,$parametros);

}else{
   $parametros= 0;
}

$c1_atencion=0;
$c1_nulos=0;
$c1_nea=0;
$c1_cerrados=0;

$c2_atencion=0;
$c2_nulos=0;
$c2_nea=0;
$c2_cerrados=0;

$c3_atencion=0;
$c3_nulos=0;
$c3_nea=0;
$c3_cerrados=0;

$c4_atencion=0;
$c4_nulos=0;
$c4_nea=0;
$c4_cerrados=0;

$c5_atencion=0;
$c5_nulos=0;
$c5_nea=0;
$c5_cerrados=0;

$SC_atencion=0;
$SC_nulos=0;
$SC_nea=0;
$SC_cerrados=0;

$totalTotal=0;

$cont6 = 0;
$cont6_12 = 0;
$cont12_24 = 0;
$cont24 = 0;

$contHosp0_6 =0;
$contHosp6_12 =0;
$contHosp12_24 =0;
$contHosp24 =0;

$contAlta0_6 =0;
$contAlta6_12 =0;
$contAlta12_24 =0;
$contAlta24 =0;

$C1AtenAtiempo = 0;
$C1A = 0;
$C1F = 0;
$C1T = 0;
$C2A = 0;
$C2F = 0;
$C2T = 0;
$C3A = 0;
$C3F = 0;
$C3T = 0;
$C4A = 0;
$C4F = 0;
$C4T = 0;
$C5A = 0;
$C5F = 0;
$C5T = 0;
$SIA = 0;
$SIF = 0;
$SIT = 0;
$C0A = 0;
$C0F = 0;
$C0T = 0;

$tiempoSumadoC1='00:00:00';
$tiempoSumadoC2='00:00:00';
$tiempoSumadoC3='00:00:00';
$tiempoSumadoC4='00:00:00';
$tiempoSumadoC5='00:00:00';
$tiempoSumadoSI='00:00:00';
$tiempoSumadoC0='00:00:00';
$tiempoSumadoTotal='00:00:00';
$x = '00:00:00';

function dif($hr1,$hr2){
  $hr =  explode(" ",$hr1);
  $hrnew = $hr[1];
  $hrnew = explode(":",$hrnew);
  $hrV = $hrnew[0];
  $minV = $hrnew[1];
  $segV = $hrnew[2];
     
  $hr2 =  explode(" ",$hr2);
  $hrnew2 = $hr2[1];
  $hrnew2 = explode(":",$hrnew2);
  $hrN = $hrnew2[0];
  $minN = $hrnew2[1];
  $segN = $hrnew2[2]; 
  
  $h1 = $hrN.":".$minN.":".$segN;
  $h2 = $hrV.":".$minV.":".$segV;
  
  $h=((strtotime($h1)-strtotime($h2)))/3600;
  $m=intval((($h)-intval($h))*60);
  $s=intval((((($h)-intval($h))*60)-$m)*60);
  return (intval($h)<10?'0'.intval($h):intval($h)).':'.($m<10?'0'.$m:$m).':'.($s<10?'0'.$s:$s);
}

// PROMEDIO DE CATEGORIZACION
function promedio(string $valor, int $total): string {
    // Verificar si el total es mayor que 0 para evitar división por cero
    if ($total <= 0) {
        return "0"; // Retornar un valor por defecto si no es posible calcular el promedio
    }

    // Dividir el valor en horas, minutos y segundos
    [$nhr, $nmin, $nseg] = explode(":", $valor);

    // Convertir las horas y minutos a su representación total en minutos
    $nhrEnMinutos = (int)$nhr * 60;
    $nmin = (int)$nmin;

    // Calcular el promedio en minutos
    $prom = ($nhrEnMinutos + $nmin) / $total;

    // Formatear el promedio con número entero y puntos como separador de miles
    return number_format($prom, 0, ",", ".");
}


$version                = $objUtil->versionJS();
?> 


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<script type="text/javascript" charset="utf-8" src="<?=PATH?>/controllers/client/reportes/contenidoResumenTiempoEspera.js?v=<?=$version;?>"></script>

<body>
<div id="resumenTiemposEspera">
<form id="formContenidoResumenTiemposEspera" name="formContenidoResumenTiemposEspera"  method="post">
<input type="hidden" name="fechaInicioReporteExcel" id="fechaInicioReporteExcel" value="<?=$parametros['frm_fecha_admision_desde'];?>">
<input type="hidden" name="fechaTerminoReporteExcel" id="fechaTerminoReporteExcel" value="<?=$parametros['frm_fecha_admision_hasta'];?>">
<input type="hidden" name="tipoAtencion" id="tipoAtencion" value="<?=$parametros['frm_tipoAtencion'];?>">
<div class="row"> 
 
  <div  class="col-lg-3">
      <button id="btnGenerarPDFResumen" type="button" class="btn btm-sm mifuente btn-primary col-lg-12 "><i class="fas fa-file-pdf"></i>&nbsp;  Generar PDF</button>
  </div>
   <div  class="col-lg-6 text-center">
      <table style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" cellpadding="0" cellspacing="0" width= "100%" >

    <tr>
      <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" class="foliochico" style="font-size:16px; text-align:center; text-decoration:blink;">REPORTE - RESUMEN TIEMPO DE ESPERA <br /> <?php echo $parametros['frm_fecha_admision_desde'].' - '; echo $parametros['frm_fecha_admision_hasta']; ?></td>
    </tr>

   <tr>
   <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;<img src="<?=RAIZ;?>/assets/img/aster.png" width="16" height="16" /> TIEMPO MAYOR O IGUAL 12 HRS. (720 MINUTOS)</td>
  </tr>
</table>
  </div>
  <div  class="col-lg-3">
      <button id="btnGenerarEXCELResumen" type="button" class="btn btm-sm mifuente btn-success col-lg-12"><i class="fas fa-file-excel"></i>&nbsp;  Generar EXCEL</button>
  </div>
</div> 
<table style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" cellpadding="0" cellspacing="0" style="width: 100%;">

  	
  
    <tr>
        <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >
              <table width="100%" id="demo_table" class='noprint table table-hover table-bordered' cellpadding="0" cellspacing="0" >
                  <tr  style="font:bold; background-color:#1e73be;color:#fff; font-size: 12px;">
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="4%" >ID DAU</td>
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="5%">ESTADO</td>
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="6%">TIPO CONSULTA</td>
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="8%">CATEGORIZACION</td>
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="10%">ADMISIÓN</td>
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="10%">FECHA CATEGORIZACION</td>
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="10%">FECHA ATENCIÓN</td>
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="10%">FECHA INDICACION</td>                    
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="10%">TERMINO ATENCIÓN</td>
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="9%">TIPO INDICACIÓN</td>
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="21%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> TIEMPO DE ESPERA (MINUTOS)
                        <table width="100%">
                          <tr style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">
                            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >ADM - CATE</td>
                            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >CATE - ATEN</td>
                            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >IND - ATEN</td>
                          </tr>
                        </table>
                    </td>                    
                  </tr>                
              <?php for ($i=0; $i < count($datos) ; $i++) {  ?>             
                   
                  <tr> 
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $datos[$i]['dau_id']; ?></td>
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $datos[$i]['est_descripcion']; ?></td>
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $datos[$i]['mot_descripcion']; ?></td>
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $datos[$i]['dau_categorizacion_actual']; ?></td>
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo  $fechaAdmision= date("d-m-Y H:i:s",strtotime($datos[$i]['dau_admision_fecha'])); ?></td>
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php if($datos[$i]['dau_categorizacion_actual_fecha']!=""){echo $fechaCategorizacion= date("d-m-Y H:i:s",strtotime($datos[$i]['dau_categorizacion_actual_fecha']));} ?></td>
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php  if($datos[$i]['dau_inicio_atencion_fecha']!=""){echo $fechaCategorizacion= date("d-m-Y H:i:s",strtotime($datos[$i]['dau_inicio_atencion_fecha']));} ?></td>
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >
                      <?php 
                      if ( $datos[$i]['est_descripcion'] == 'N.E.A' ) {

                        echo $fechaCategorizacion = date("d-m-Y H:i:s",strtotime($datos[$i]['dau_cierre_administrativo_fecha']));

                      }

                      if ($datos[$i]['dau_indicacion_egreso_fecha'] != "" ) {
                          echo $fechaCategorizacion = date("d-m-Y H:i:s",strtotime($datos[$i]['dau_indicacion_egreso_fecha']));
                      } 
                      ?>
                      </td>
                    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php if($datos[$i]['dau_indicacion_egreso_aplica_fecha']!=""){echo $fechaCategorizacion= date("d-m-Y H:i:s",strtotime($datos[$i]['dau_indicacion_egreso_aplica_fecha']));} ?></td>
                     <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $datos[$i]['ind_egr_descripcion']; ?></td>
                     <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >
                      <table width="100%">
                        <tr style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">
                            <td width="33%">
                              <?php if($datos[$i]['ADM_CATE']>=720){ ?>
                                <img src="<?=RAIZ;?>/assets/img/aster.png" width="14" height="14" />
                                <?php
                                echo $datos[$i]['ADM_CATE'];
                              }else{ 
                                echo $datos[$i]['ADM_CATE'];
                              } ?>    
                            </td>
                            <td width="33%" >                           

                            <?php if($datos[$i]['CATE_ATEN']>=720){ ?>
                              <img src="<?=RAIZ;?>/assets/img/aster.png" width="14" height="14" />
                              <?php
                              echo $datos[$i]['CATE_ATEN']; 
                            }else{ 
                              echo $datos[$i]['CATE_ATEN'];
                            } ?>
                              
                            </td>
                            <td width="33%" >                              
                               <?php if($datos[$i]['IND_ATEN']>=720){?>
                                <img src="<?=RAIZ;?>/assets/img/aster.png" width="14" height="14" />
                                <?php
                                echo $datos[$i]['IND_ATEN'];
                              }else{ 
                                echo $datos[$i]['IND_ATEN'];
                              } ?>                             
                            </td>
                        </tr>
                      </table>

                    </td>
                  </tr>
         <?php  
              // C1--------------------------------------------------------------------------------------
              if($datos[$i]['dau_categorizacion_actual']=="ESI-1" or $datos[$i]['dau_categorizacion_actual']=="C1"){
                    if($datos[$i]['est_id']==1 || $datos[$i]['est_id']==2 || $datos[$i]['est_id']==3 || $datos[$i]['est_id']==4 ||  $datos[$i]['est_id']==8 ||  $datos[$i]['est_id']==5){ // C1 EN ATENCION CUANDO ESTA EN ESTADO 1,2,3,4,8
                        if($datos[$i]['est_id']==1 || $datos[$i]['est_id']==2 || $datos[$i]['est_id']==3 || $datos[$i]['est_id']==4 ||  $datos[$i]['est_id']==8){
                          $c1_atencion++;
                        }

                      if($datos[$i]['CATE_ATEN'] >= 0 && $datos[$i]['CATE_ATEN'] <= 5){  //EN ATENCION
                        $C1A++;
                      }
                        $C1T++; 

                    }
                    if($datos[$i]['est_id']==6){ // C1  NULOS
                        $c1_nulos++;
                    }
                    if($datos[$i]['est_id']==7){ // C1 NEA
                        $c1_nea++;
                    }
                    if($datos[$i]['est_id']==5){ // C1 CERRADOS
                        $c1_cerrados++;
                    }
                    if($datos[$i]['dau_categorizacion_actual_fecha']!=""){
                      $x = dif($datos[$i]['dau_admision_fecha'],$datos[$i]['dau_categorizacion_actual_fecha']);
                    }             

                    $h2h = date('H', strtotime($x));
                    $h2m = date('i', strtotime($x));
                    $h2s = date('s', strtotime($x));
                    $tiempoSumadoC1 = date('H:i:s', strtotime($tiempoSumadoC1." + ".$h2h." hour ".$h2m." min ".$h2s." second"));
                      
                    
                    
                    
              }

                // C2-----------------------------------------------------------------------------------------
                if($datos[$i]['dau_categorizacion_actual']=="ESI-2" or $datos[$i]['dau_categorizacion_actual']=="C2"){
                    if($datos[$i]['est_id']==1 || $datos[$i]['est_id']==2 || $datos[$i]['est_id']==3 || $datos[$i]['est_id']==4 || $datos[$i]['est_id']==8 ||  $datos[$i]['est_id']==5){ // C2 EN ATENCION CUANDO ESTA EN ESTADO 1,2,3,4,8
                        if($datos[$i]['est_id']==1 || $datos[$i]['est_id']==2 || $datos[$i]['est_id']==3 || $datos[$i]['est_id']==4 || $datos[$i]['est_id']==8){
                          $c2_atencion++;
                        }

                         if($datos[$i]['CATE_ATEN'] >= 0 && $datos[$i]['CATE_ATEN'] <= 30){  //EN ATENCION
                            $C2A++;
                          } 
                          $C2T++;   
                    }
                    if($datos[$i]['est_id']==6){ // C2  NULOS
                        $c2_nulos++;
                    }
                    if($datos[$i]['est_id']==7){ // C2 NEA
                        $c2_nea++;
                    }
                    if($datos[$i]['est_id']==5){ // C2 CERRADOS
                        $c2_cerrados++;
                    }

                    if($datos[$i]['dau_categorizacion_actual_fecha']!=""){
                      $x = dif($datos[$i]['dau_admision_fecha'],$datos[$i]['dau_categorizacion_actual_fecha']);
                    }

                    $h2h = date('H', strtotime($x));
                    $h2m = date('i', strtotime($x));
                    $h2s = date('s', strtotime($x));
                    $tiempoSumadoC2 = date('H:i:s', strtotime($tiempoSumadoC2." + ".$h2h." hour ".$h2m." min ".$h2s." second"));

                   
                }

                 // C3----------------------------------------------------------------------------------------
                 if($datos[$i]['dau_categorizacion_actual']=="ESI-3" or $datos[$i]['dau_categorizacion_actual']=="C3"){
                      if($datos[$i]['est_id']==1 || $datos[$i]['est_id']==2 || $datos[$i]['est_id']==3 || $datos[$i]['est_id']==4 || $datos[$i]['est_id']==8 ||  $datos[$i]['est_id']==5){ // C3 EN ATENCION CUANDO ESTA EN ESTADO 1,2,3,4,8
                          if($datos[$i]['est_id']==1 || $datos[$i]['est_id']==2 || $datos[$i]['est_id']==3 || $datos[$i]['est_id']==4 || $datos[$i]['est_id']==8){
                            $c3_atencion++;
                          }

                           if($datos[$i]['CATE_ATEN'] >= 0 && $datos[$i]['CATE_ATEN'] <= 90){  //EN ATENCION
                            $C3A++;
                          } 
                            $C3T++;     
                      }
                      if($datos[$i]['dau_categorizacion_actual']=="ESI-3" && $datos[$i]['est_id']==6){ // C3  NULOS
                          $c3_nulos++;
                      }
                      if($datos[$i]['dau_categorizacion_actual']=="ESI-3" && $datos[$i]['est_id']==7){ // C3 NEA
                          $c3_nea++;
                      }
                      if($datos[$i]['dau_categorizacion_actual']=="ESI-3" && $datos[$i]['est_id']==5){ // C3 CERRADOS
                          $c3_cerrados++;
                      }

                      if($datos[$i]['dau_categorizacion_actual_fecha']!=""){
                        $x = dif($datos[$i]['dau_admision_fecha'],$datos[$i]['dau_categorizacion_actual_fecha']);
                      }

                      $h2h = date('H', strtotime($x));
                      $h2m = date('i', strtotime($x));
                      $h2s = date('s', strtotime($x));
                      $tiempoSumadoC3 = date('H:i:s', strtotime($tiempoSumadoC3." + ".$h2h." hour ".$h2m." min ".$h2s." second"));

                     

                  }
                 // C4----------------------------------------------------------------------------------------
                 if($datos[$i]['dau_categorizacion_actual']=="ESI-4" or $datos[$i]['dau_categorizacion_actual']=="C4"){
                      if($datos[$i]['est_id']==1 || $datos[$i]['est_id']==2 || $datos[$i]['est_id']==3 || $datos[$i]['est_id']==4 || $datos[$i]['est_id']==8 ||  $datos[$i]['est_id']==5){ // C4 EN ATENCION CUANDO ESTA EN ESTADO 1,2,3,4,8
                          if($datos[$i]['est_id']==1 || $datos[$i]['est_id']==2 || $datos[$i]['est_id']==3 || $datos[$i]['est_id']==4 || $datos[$i]['est_id']==8){
                            $c4_atencion++;
                          }

                          if($datos[$i]['CATE_ATEN'] >= 0 && $datos[$i]['CATE_ATEN'] <= 180){  //EN ATENCION
                            $C4A++;
                          } 
                            $C4T++;   
                      }
                      if($datos[$i]['dau_categorizacion_actual']=="ESI-4" && $datos[$i]['est_id']==6){ // C4  NULOS
                          $c4_nulos++;
                      }
                      if($datos[$i]['dau_categorizacion_actual']=="ESI-4" && $datos[$i]['est_id']==7){ // C4 NEA
                          $c4_nea++;
                      }
                      if($datos[$i]['dau_categorizacion_actual']=="ESI-4" && $datos[$i]['est_id']==5){ // C4 CERRADOS
                          $c4_cerrados++;
                      }

                      if($datos[$i]['dau_categorizacion_actual_fecha']!=""){
                         $x = dif($datos[$i]['dau_admision_fecha'],$datos[$i]['dau_categorizacion_actual_fecha']);
                      }

                      $h2h = date('H', strtotime($x));
                      $h2m = date('i', strtotime($x));
                      $h2s = date('s', strtotime($x));
                      $tiempoSumadoC4 = date('H:i:s', strtotime($tiempoSumadoC4." + ".$h2h." hour ".$h2m." min ".$h2s." second"));
                      
                      
                 }

                 // C5----------------------------------------------------------------------------------------
                 if($datos[$i]['dau_categorizacion_actual']=="ESI-5" or $datos[$i]['dau_categorizacion_actual']=="C5"){
                      if($datos[$i]['est_id']==1 || $datos[$i]['est_id']==2 || $datos[$i]['est_id']==3 || $datos[$i]['est_id']==4 || $datos[$i]['est_id']==8 ||  $datos[$i]['est_id']==5){ // C5 EN ATENCION CUANDO ESTA EN ESTADO 1,2,3,4,8
                          if($datos[$i]['est_id']==1 || $datos[$i]['est_id']==2 || $datos[$i]['est_id']==3 || $datos[$i]['est_id']==4 || $datos[$i]['est_id']==8){
                            $c5_atencion++;
                          }

                          $C5T++;
                      }
                      if($datos[$i]['dau_categorizacion_actual']=="ESI-5" && $datos[$i]['est_id']==6){ // C5  NULOS
                          $c5_nulos++;
                      }
                      if($datos[$i]['dau_categorizacion_actual']=="ESI-5" && $datos[$i]['est_id']==7){ // C5 NEA
                          $c5_nea++;
                      }
                      if($datos[$i]['dau_categorizacion_actual']=="ESI-5" && $datos[$i]['est_id']==5){ // C5 CERRADOS
                          $c5_cerrados++;
                      }

                      if($datos[$i]['dau_categorizacion_actual_fecha']!=""){
                        $x = dif($datos[$i]['dau_admision_fecha'],$datos[$i]['dau_categorizacion_actual_fecha']);
                      }

                      $h2h = date('H', strtotime($x));
                      $h2m = date('i', strtotime($x));
                      $h2s = date('s', strtotime($x));
                      $tiempoSumadoC5 = date('H:i:s', strtotime($tiempoSumadoC5." + ".$h2h." hour ".$h2m." min ".$h2s." second"));
                  }

                  // SC----------------------------------------------------------------------------------------
                  if($datos[$i]['dau_categorizacion_actual']==""){
                      if($datos[$i]['est_id']==1 || $datos[$i]['est_id']==2 || $datos[$i]['est_id']==3 || $datos[$i]['est_id']==4 || $datos[$i]['est_id']==8 ||  $datos[$i]['est_id']==5){ // SC EN ATENCION  CUANDO ESTA EN ESTADO 1,2,3,4,8
                          if($datos[$i]['est_id']==1 || $datos[$i]['est_id']==2 || $datos[$i]['est_id']==3 || $datos[$i]['est_id']==4 || $datos[$i]['est_id']==8){
                            $SC_atencion++; 
                          }

                          $C0T++;                       
                      }
                      if($datos[$i]['est_id']==6){ // SC  NULOS
                          $SC_nulos++;
                      }
                      if($datos[$i]['est_id']==7){ // SC NEA
                          $SC_nea++;
                      }
                      if($datos[$i]['est_id']==5){ // SC CERRADOS
                          $SC_cerrados++;
                      }
                      
                   }

                    $totalC1=$c1_atencion+$c1_nulos+$c1_nea+$c1_cerrados;
                    $totalC2=$c2_atencion+$c2_nulos+$c2_nea+$c2_cerrados;
                    $totalC3=$c3_atencion+$c3_nulos+$c3_nea+$c3_cerrados;
                    $totalC4=$c4_atencion+$c4_nulos+$c4_nea+$c4_cerrados;
                    $totalC5=$c5_atencion+$c5_nulos+$c5_nea+$c5_cerrados;
                    $totalSC=$SC_atencion+$SC_nulos+$SC_nea+$SC_cerrados;

                    $totalTotal= $totalC1+$totalC2+$totalC3+$totalC4+$totalC5+$totalSC;

                    // $datos[$i]['ADM_CATE'];
                    // $datos[$i]['CATE_ATEN'];
                    // $datos[$i]['IND_ATEN'];

                    if($datos[$i]['IND_ATEN']>=0 && $datos[$i]['IND_ATEN']<=360){
                      $cont6++;
                        if($datos[$i]['dau_indicacion_egreso']==4){
                           $contHosp0_6++;
                        }elseif ($datos[$i]['dau_indicacion_egreso']==3) {
                          $contAlta0_6++;
                        } 
                    }

                    if($datos[$i]['IND_ATEN']>=361 && $datos[$i]['IND_ATEN']<=720){
                      $cont6_12++;
                          if($datos[$i]['dau_indicacion_egreso']==4){
                           $contHosp6_12++;
                        }elseif ($datos[$i]['dau_indicacion_egreso']==3) {
                          $contAlta6_12++;
                        } 
                    }

                    if($datos[$i]['IND_ATEN']>=721 && $datos[$i]['IND_ATEN']<=1440){
                      $cont12_24++;
                      if($datos[$i]['dau_indicacion_egreso']==4){
                        $contHosp12_24++; 
                      }elseif ($datos[$i]['dau_indicacion_egreso']==3) {
                        $contAlta12_24++;
                      } 
                    }

                    if($datos[$i]['IND_ATEN']>=1441){
                      $cont24++;
                      if($datos[$i]['dau_indicacion_egreso']==4){
                        $contHosp24++; 
                      }elseif ($datos[$i]['dau_indicacion_egreso']==3) {
                        $contAlta24++;
                      } 
                    }


                    $C1_TOTAL_F = $C1T - $C1A;
                    $C2_TOTAL_F = $C2T - $C2A;
                    $C3_TOTAL_F = $C3T - $C3A;
                    $C4_TOTAL_F = $C4T - $C4A;
                    $C5_TOTAL_F = 0;                   
                    $C0_TOTAL_F = 0;
                    $total_total_atiempo = $C1_TOTAL_F+$C2_TOTAL_F+$C3_TOTAL_F+$C4_TOTAL_F+$C5_TOTAL_F+$C0_TOTAL_F;
                

                    ?>



              <?php } ?>  
            </table>  
        </td>
    
    </tr>
   <tr>
    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</td>
    </tr>
   <tr>
    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</td>
  </tr>
   <tr>
     <td valign="top">   
        <table width="100%" id="tabla" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" class="table table-bordered table-hover" cellpadding="0" cellspacing="0">
             <tr  style="font:bold; background-color:#1e73be;color:#fff;">
              <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" colspan="8" style=" font:bold">RESUMEN TIEMPO DE ESPERA SEGÚN ESTADO - ADMISIÓN - CATEGORIZACIÓN</td>
            </tr>
            <tr  style="font:bold; background-color:#1e73be;color:#fff;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">
              <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="128" >CATEGORIZACIÓN</td>
              <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="70" >EN ATENCIÓN</td>
              <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="70" >NULOS</td>
              <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="70" >N.E.A</td>
              <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="70">CERRADO</td>
              <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="70" >TOTAL</td>
              <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="70" >TOTAL %</td>
              <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="150" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >PROMEDIO MINUTOS DE <BR />ESPERA CATEGORIZACIÓN</td>
            
            <tr align="right">
                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">ESI-1</td>
                <input type="hidden" name="c1_atencion" id="c1_atencion" value="<?=$c1_atencion?>">
                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php  echo $c1_atencion;?></td>
                <input type="hidden" name="c1_nulos" id="c1_nulos" value="<?=$c1_nulos?>">
                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php  echo $c1_nulos;?></td>
                <input type="hidden" name="c1_nea" id="c1_nea" value="<?=$c1_nea?>">
                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php  echo $c1_nea;?></td>
                <input type="hidden" name="c1_cerrados" id="c1_cerrados" value="<?=$c1_cerrados?>">
                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php  echo $c1_cerrados;?></td>
                <input type="hidden" name="totalC1" id="totalC1" value="<?=$totalC1?>">
                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php  echo $totalC1;?></td>
                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php  @$PorC1 =  (($totalC1 * 100)/$totalTotal); echo number_format($PorC1,1,",","."); ?></td>
                <input type="hidden" name="PorC1" id="PorC1" value="<?=number_format($PorC1,1,",",".");?>">                
                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo @$prom1 = promedio($tiempoSumadoC1,$totalC1)." minutos"; ?></td>
                <input type="hidden" name="prom1" id="prom1" value="<?=@$prom1?>"> 
            </tr>

            <tr align="right">
                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">ESI-2</td>
                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $c2_atencion; ?>	</td>
                <input type="hidden" name="c2_atencion" id="c2_atencion" value="<?=$c2_atencion?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $c2_nulos; ?>  	</td>
                <input type="hidden" name="c2_nulos" id="c2_nulos" value="<?=$c2_nulos?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $c2_nea; ?>  	</td>
                <input type="hidden" name="c2_nea" id="c2_nea" value="<?=$c2_nea?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">	<?php echo $c2_cerrados; ?>  </td>
                <input type="hidden" name="c2_cerrados" id="c2_cerrados" value="<?=$c2_cerrados?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">	<?php  echo $totalC2;?></td>
                <input type="hidden" name="totalC2" id="totalC2" value="<?=$totalC2?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php  @$PorC2 =  (($totalC2 * 100)/$totalTotal); echo number_format($PorC2,1,",","."); ?>	</td>
                <input type="hidden" name="PorC2" id="PorC2" value="<?=number_format($PorC2,1,",",".");?>">

                 <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo @$prom2 = promedio($tiempoSumadoC2,$totalC2)." minutos"; ?>	</td>
                 <input type="hidden" name="prom2" id="prom2" value="<?=@$prom2?>">
            </tr>

            <tr  align="right">
                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">ESI-3</td>
                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $c3_atencion; ?>	</td>
                <input type="hidden" name="c3_atencion" id="c3_atencion" value="<?=$c3_atencion?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $c3_nulos; ?>	</td>
                <input type="hidden" name="c3_nulos" id="c3_nulos" value="<?=$c3_nulos?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $c3_nea; ?>	</td>
                <input type="hidden" name="c3_nea" id="c3_nea" value="<?=$c3_nea?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $c3_cerrados; ?>	</td>
                <input type="hidden" name="c3_cerrados" id="c3_cerrados" value="<?=$c3_cerrados?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $totalC3; ?>	</td>
                <input type="hidden" name="totalC3" id="totalC3" value="<?=$totalC3?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">  <?php  @$PorC3 =  (($totalC3 * 100)/$totalTotal); echo number_format($PorC3,1,",","."); ?>	</td>
                <input type="hidden" name="PorC3" id="PorC3" value="<?=number_format($PorC3,1,",",".");?>">

                 <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo @$prom3 = promedio($tiempoSumadoC3,$totalC3)." minutos"; ?>	</td>
                 <input type="hidden" name="prom3" id="prom3" value="<?=@$prom3?>">
            </tr>
            <tr  align="right">
                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">ESI-4</td>
                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $c4_atencion ?> </td>
                <input type="hidden" name="c4_atencion" id="c4_atencion" value="<?=$c4_atencion?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $c4_nulos ?> </td>
                <input type="hidden" name="c4_nulos" id="c4_nulos" value="<?=$c4_nulos?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $c4_nea ?> </td>
                <input type="hidden" name="c4_nea" id="c4_nea" value="<?=$c4_nea?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $c4_cerrados ?> </td>
                <input type="hidden" name="c4_cerrados" id="c4_cerrados" value="<?=$c4_cerrados?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $totalC4; ?> </td>
                <input type="hidden" name="totalC4" id="totalC4" value="<?=$totalC4?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" > <?php  @$PorC4 =  (($totalC4 * 100)/$totalTotal); echo number_format($PorC4,1,",","."); ?>  </td>
                <input type="hidden" name="PorC4" id="PorC4" value="<?=number_format($PorC4,1,",",".");?>">

                 <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo @$prom4 = promedio($tiempoSumadoC4,$totalC4)." minutos"; ?> </td>
                 <input type="hidden" name="prom4" id="prom4" value="<?=@$prom4?>">

            </tr>
            <tr  align="right">
                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">ESI-5</td>
                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $c5_atencion; ?> </td>
                <input type="hidden" name="c5_atencion" id="c5_atencion" value="<?=$c5_atencion?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $c5_nulos; ?> </td>
                <input type="hidden" name="c5_nulos" id="c5_nulos" value="<?=$c5_nulos?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">  <?php echo $c5_nea ?> </td>
                <input type="hidden" name="c5_nea" id="c5_nea" value="<?=$c5_nea?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">  <?php echo $c5_cerrados ?> </td>
                 <input type="hidden" name="c5_cerrados" id="c5_cerrados" value="<?=$c5_cerrados?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $totalC5; ?>  </td>
                <input type="hidden" name="totalC5" id="totalC5" value="<?=$totalC5?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >  <?php  @$PorC5 =  (($totalC5 * 100)/$totalTotal); echo number_format($PorC5,1,",","."); ?> </td>
                <input type="hidden" name="PorC5" id="PorC5" value="<?=number_format($PorC5,1,",",".");?>">
                 <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo @$prom5 = promedio($tiempoSumadoC5,$totalC5)." minutos"; ?></td>
                 <input type="hidden" name="prom5" id="prom5" value="<?=@$prom5?>">
            </tr>

              <tr  align="right">
                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">S/C</td>
               <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $SC_atencion; ?> </td>
               <input type="hidden" name="SC_atencion" id="SC_atencion" value="<?=$SC_atencion?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $SC_nulos; ?> </td>
                 <input type="hidden" name="SC_nulos" id="SC_nulos" value="<?=$SC_nulos?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $SC_nea;  ?> </td>
                <input type="hidden" name="SC_nea" id="SC_nea" value="<?=$SC_nea?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $SC_cerrados; ?> </td>
                <input type="hidden" name="SC_cerrados" id="SC_cerrados" value="<?=$SC_cerrados?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $totalSC; ?></td>
                 <input type="hidden" name="totalSC" id="totalSC" value="<?=$totalSC?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php  ?> <?php  @$PorSC =  (($totalSC * 100)/$totalTotal); echo number_format($PorSC,1,",","."); ?> </td>
                <input type="hidden" name="PorSC" id="PorSC" value="<?=number_format($PorSC,1,",",".");?>">

                 <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo @$promC0 = promedio($tiempoSumadoC0,$totalSC)." minutos"; ?> </td>
                 <input type="hidden" name="promC0" id="promC0" value="<?=@$promC0?>">

            </tr>
            <tr  align="right">
                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">TOTAL </td>
                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $totalAtencion=$c1_atencion+$c2_atencion+$c3_atencion+$c4_atencion+$c5_atencion+ $SC_atencion; ?>  </td>
                <input type="hidden" name="totalAtencion" id="totalAtencion" value="<?=$totalAtencion?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $totalNulos= $c1_nulos+$c2_nulos+$c3_nulos+$c4_nulos+$c5_nulos+$SC_nulos; ?> </td>
                <input type="hidden" name="totalNulos" id="totalNulos" value="<?=$totalNulos?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $totalNea= $c1_nea+$c2_nea+$c3_nea+$c4_nea+$c5_nea+$SC_nea; ?> </td>
                 <input type="hidden" name="totalNea" id="totalNea" value="<?=$totalNea?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $totalCerrado=$c1_cerrados+$c2_cerrados+$c3_cerrados+$c4_cerrados+$c5_cerrados+$SC_cerrados; ?> </td>
                <input type="hidden" name="totalCerrado" id="totalCerrado" value="<?=$totalCerrado?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $totalTotal; ?>  </td>
                <input type="hidden" name="totalTotal" id="totalTotal" value="<?=$totalTotal?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php echo $totalPorc = $PorC1+$PorC2+$PorC3+$PorC4+$PorC5+$PorSC; ?> </td>
                <input type="hidden" name="totalPorc" id="totalPorc" value="<?=$totalPorc?>">

                <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?php  $total_promedio = ($prom1+$prom2+$prom3+$prom4+$prom5+$promC0); echo $total_promedio." minutos"; ?></td>
                <input type="hidden" name="total_promedio" id="total_promedio" value="<?=$total_promedio." minutos"?>">
            </tr>
        </table>    
    </td>
   
     </tr>
   <tr>
    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</td>
    </tr>
   <tr>
    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</td>
  </tr>
  <tr>
    <td  valign="top" width="40%">
       
 				 <table width="100%" id="tabla" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" class="table table-bordered table-hover">
                         <tr  style="font:bold; background-color:#1e73be;color:#fff;">
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" colspan="4"  style=" font:bold;">RESUMEN TIEMPO DE ESPERA </td>
                        </tr>
                        <tr  style="font:bold; background-color:#1e73be;color:#fff;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="28%" >INDICACIÓN - ATENCIÓN</td>
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="27%" >HOSPITALIZADOS</td>
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="22%" >ALTA</td>
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="23%" >TOTAL</td>
                        </tr>
                        <tr align="right">
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >0 - 6 HRS.</td>
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $contHosp0_6; ?> </td>
                          <input type="hidden" name="contHosp0_6" id="contHosp0_6" value="<?=$contHosp0_6?>">
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $contAlta0_6; ?> </td>
                          <input type="hidden" name="contAlta0_6" id="contAlta0_6" value="<?=$contAlta0_6?>"> 
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $contTotal6=$contHosp0_6 + $contAlta0_6; ?> </td>
                          <input type="hidden" name="contTotal6" id="contTotal6" value="<?=$contTotal6?>"> 
                        </tr>
                        <tr align="right">
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">6 - 12 HRS.</td>
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $contHosp6_12; ?>  </td>
                          <input type="hidden" name="contHosp6_12" id="contHosp6_12" value="<?=$contHosp6_12?>">
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $contAlta6_12;?>  </td>
                          <input type="hidden" name="contAlta6_12" id="contAlta6_12" value="<?=$contAlta6_12?>">
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $conTotal12=$contHosp6_12+$contAlta6_12; ?> </td>
                          <input type="hidden" name="conTotal12" id="conTotal12" value="<?=$conTotal12?>">
                        </tr>
                        <tr align="right">
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">12 - 24 HRS.</td>
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $contHosp12_24; ?> </td>
                          <input type="hidden" name="contHosp12_24" id="contHosp12_24" value="<?=$contHosp12_24?>">
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $contAlta12_24; ?> </td>
                          <input type="hidden" name="contAlta12_24" id="contAlta12_24" value="<?=$contAlta12_24?>">
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $conTotal24=$contHosp12_24+$contAlta12_24; ?> </td>
                          <input type="hidden" name="conTotal24" id="conTotal24" value="<?=$conTotal24?>">
                        </tr>
                         <tr align="right">
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">+ 24 HRS.</td>
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $contHosp24; ?> </td>
                          <input type="hidden" name="contHosp24" id="contHosp24" value="<?=$contHosp24?>">
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $contAlta24;?> </td>
                          <input type="hidden" name="contAlta24" id="contAlta24" value="<?=$contAlta24?>">
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $contotal2424=$contHosp24+$contAlta24; ?></td>
                           <input type="hidden" name="contotal2424" id="contotal2424" value="<?=$contotal2424?>">
                        </tr>
                        <tr align="right">
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">TOTAL</td>
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $TH = $contHosp0_6+$contHosp6_12+$contHosp12_24+$contHosp24; ?>  </td>
                          <input type="hidden" name="TH" id="TH" value="<?=$TH?>">
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $TA = $contAlta0_6+$contAlta6_12+$contAlta12_24+$contAlta24; ?>  </td>
                           <input type="hidden" name="TA" id="TA" value="<?=$TA?>">
                          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"> <?php echo $totalTHTA=$TH + $TA; ?> </td>
                          <input type="hidden" name="totalTHTA" id="totalTHTA" value="<?=$totalTHTA?>">
                        </tr>
                    </table>
        </td>
	  </tr>
   <tr>
    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</td>
    </tr>
   <tr>
    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</td>
  </tr>
   <tr>
    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="60%">
    <table width="100%" id="tabla" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" class="table table-bordered table-hover">   
      <tr  style="font:bold; background-color:#1e73be;color:#fff;">
          <td colspan="6" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">TIEMPO DE ESPERA INGRESO A BOX (EN ATENCIÓN Y DERIVADOS)</td>
        </tr>
        <tr  style="font:bold; background-color:#1e73be;color:#fff;">
         <td   width="29%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" rowspan="2"><br/>CATEGORIZACIÓN</td>
          <td colspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">ATENCIÓN A TIEMPO<BR /></td>
           <td colspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">ATENCIÓN FUERA DE TIEMPO<BR /></td>
           <td rowspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">TOTAL CANTIDAD</td>
        </tr> 
        <tr  style="font:bold; background-color:#1e73be;color:#fff;">
          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">TOTAL CANTIDAD</td>
           <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">TOTAL %</td>
           <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">TOTAL CANTIDAD</td>
           <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">TOTAL %</td>
        </tr> 
          
        <tr align="right">
        	<td width="29%"  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">ESI-1  | 5 MIN (0 HR.)</td>
            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="17%"> <?php echo $C1A; ?> </td>
            <input type="hidden" name="C1A" id="C1A" value="<?=$C1A?>">
            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="17%">  <?php @$PorC1_atiempo =  (($C1A * 100)/($C1A + $C1_TOTAL_F)); echo number_format($PorC1_atiempo,1,",",".");  ?>  </td>
            <input type="hidden" name="PorC1_atiempo" id="PorC1_atiempo" value="<?=number_format($PorC1_atiempo,1,",",".")?>">
            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="17%">  <?php echo $C1_TOTAL_F; ?> </td>
            <input type="hidden" name="C1_TOTAL_F" id="C1_TOTAL_F" value="<?=$C1_TOTAL_F?>">
            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="20%">  <?php @$PorC1_Ftiempo =  (($C1_TOTAL_F * 100)/($C1A + $C1_TOTAL_F)); echo number_format($PorC1_Ftiempo,1,",",".");  ?> </td>
            <input type="hidden" name="PorC1_Ftiempo" id="PorC1_Ftiempo" value="<?=number_format($PorC1_Ftiempo,1,",",".")?>">
            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="20%"> <?php echo $c1Total=$C1A + $C1_TOTAL_F; ?> </td>
            <input type="hidden" name="c1Total" id="c1Total" value="<?=$c1Total?>">
        </tr>  
         <tr style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">
       		<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">ESI-2  | 30 MIN  (1/2 HR.)</td>
            <td  > <?php echo $C2A; ?> </td>
            <input type="hidden" name="C2A" id="C2A" value="<?=$C2A?>">
            <td > <?php @$PorC2_atiempo =  (($C2A * 100)/($C2A + $C2_TOTAL_F)); echo number_format($PorC2_atiempo,1,",",".");  ?> </td>
            <input type="hidden" name="PorC2_atiempo" id="PorC2_atiempo" value="<?=number_format($PorC2_atiempo,1,",",".")?>">
            <td > <?php echo  $C2_TOTAL_F; ?></td>
            <input type="hidden" name="C2_TOTAL_F" id="C2_TOTAL_F" value="<?=$C2_TOTAL_F?>">
            <td ><?php @$PorC2_Ftiempo =  (($C2_TOTAL_F * 100)/($C2A + $C2_TOTAL_F)); echo number_format($PorC2_Ftiempo,1,",",".");  ?> </td>
            <input type="hidden" name="PorC2_Ftiempo" id="PorC2_Ftiempo" value="<?=number_format($PorC2_Ftiempo,1,",",".")?>">
        	  <td >  <?php echo $c2Total=$C2A + $C2_TOTAL_F;  ?></td>
            <input type="hidden" name="c2Total" id="c2Total" value="<?=$c2Total?>">

        </tr>  
         <tr style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">
            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">ESI-3  | 90 MIN (1 1/2 HR.)</td>
            <td  > <?php echo $C3A ; ?> </td>
            <input type="hidden" name="C3A" id="C3A" value="<?=$C3A?>">
            <td > <?php @$PorC3_atiempo =  (($C3A * 100)/($C3A + $C3_TOTAL_F)); echo number_format($PorC3_atiempo,1,",",".");  ?></td>
            <input type="hidden" name="PorC3_atiempo" id="PorC3_atiempo" value="<?=number_format($PorC3_atiempo,1,",",".")?>">
            <td > <?php echo  $C3_TOTAL_F; ?> </td>
            <input type="hidden" name="C3_TOTAL_F" id="C3_TOTAL_F" value="<?=$C3_TOTAL_F?>">
            <td > <?php @$PorC3_Ftiempo =  (($C3_TOTAL_F * 100)/($C3A + $C3_TOTAL_F)); echo number_format($PorC3_Ftiempo,1,",",".");  ?> </td>
            <input type="hidden" name="PorC3_Ftiempo" id="PorC3_Ftiempo" value="<?=number_format($PorC3_Ftiempo,1,",",".")?>">
       		  <td > <?php echo $c3Total=$C3A + $C3_TOTAL_F;  ?> </td>
            <input type="hidden" name="c3Total" id="c3Total" value="<?=$c3Total?>">
        </tr>   
        <tr style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">
            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">ESI-4  | 180 MIN  (3 HRS.)</td>
            <td  ><?php echo $C4A ; ?> </td>
            <input type="hidden" name="C4A" id="C4A" value="<?=$C4A?>">
            <td ><?php @$PorC4_atiempo =  (($C4A * 100)/($C4A + $C4_TOTAL_F)); echo number_format($PorC4_atiempo,1,",",".");  ?></td>
            <input type="hidden" name="PorC4_atiempo" id="PorC4_atiempo" value="<?=number_format($PorC4_atiempo,1,",",".")?>">
            <td ><?php echo  $C4_TOTAL_F; ?></td>
            <input type="hidden" name="C4_TOTAL_F" id="C4_TOTAL_F" value="<?=$C4_TOTAL_F?>">
            <td > <?php @$PorC4_Ftiempo =  (($C4_TOTAL_F * 100)/($C4A + $C4_TOTAL_F)); echo number_format($PorC4_Ftiempo,1,",",".");  ?></td>
            <input type="hidden" name="PorC4_Ftiempo" id="PorC4_Ftiempo" value="<?=number_format($PorC4_Ftiempo,1,",",".")?>">
       		  <td > <?php echo $c4Total=$C4A + $C4_TOTAL_F;  ?> </td>
            <input type="hidden" name="c4Total" id="c4Total" value="<?=$c4Total?>">
        </tr>   
        <tr style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">
            <td  style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">ESI-5 </td>
            <td  > <?php echo $C5T ; ?> </td>
            <input type="hidden" name="C5T" id="C5T" value="<?=$C5T?>">
            <td>
                <?php 
                $denominador = $C5T + $C5_TOTAL_F; 
                if ($denominador > 0) {
                    $PorC5_atiempo = (($C5T * 100) / $denominador); 
                    echo number_format($PorC5_atiempo, 1, ",", ".");
                } else {
                    echo "0"; // Retornar 0 si no es posible calcular el porcentaje
                }
                ?>
            </td>
            <input type="hidden" name="PorC5_atiempo" id="PorC5_atiempo" value="<?=number_format($PorC5_atiempo,1,",",".")?>">
            <td ><?php echo  $C5_TOTAL_F; ?> </td>
            <input type="hidden" name="C5_TOTAL_F" id="C5_TOTAL_F" value="<?=$C5_TOTAL_F?>">
            <td>
                <?php 
                $denominador = $C5T + $C5_TOTAL_F; 
                if ($denominador > 0) {
                    $PorC5_Ftiempo = (($C5_TOTAL_F * 100) / $denominador); 
                    echo number_format($PorC5_Ftiempo, 1, ",", ".");
                } else {
                    echo "0"; // Retornar 0 si no es posible calcular el porcentaje
                }
                ?>
            </td>
            <input type="hidden" name="PorC5_Ftiempo" id="PorC5_Ftiempo" value="<?=number_format($PorC5_Ftiempo,1,",",".")?>">
       		  <td > <?php echo $c5Total=$C5T + $C5_TOTAL_F;  ?> </td>
            <input type="hidden" name="c5Total" id="c5Total" value="<?=$c5Total?>">
        </tr>  

         <tr style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">
    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">S/C</td>
    <td><?php echo $C0T; ?></td>
    <input type="hidden" name="C0T" id="C0T" value="<?=$C0T?>">
    <td>
        <?php
        $denominadorC0 = $C0T + $C0_TOTAL_F;
        if ($denominadorC0 > 0) {
            $PorC0_atiempo = (($C0A * 100) / $denominadorC0);
            echo number_format($PorC0_atiempo, 1, ",", ".");
        } else {
            echo "0"; // Manejar caso de división por cero
        }
        ?>
    </td>
    <input type="hidden" name="PorC0_atiempo" id="PorC0_atiempo" value="<?=isset($PorC0_atiempo) ? number_format($PorC0_atiempo, 1, ",", ".") : "0"?>">
    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $C0_TOTAL_F; ?></td>
    <input type="hidden" name="C0_TOTAL_F" id="C0_TOTAL_F" value="<?=$C0_TOTAL_F?>">
    <td>
        <?php
        if ($denominadorC0 > 0) {
            $PorC0_Ftiempo = (($C0_TOTAL_F * 100) / $denominadorC0);
            echo number_format($PorC0_Ftiempo, 1, ",", ".");
        } else {
            echo "0"; // Manejar caso de división por cero
        }
        ?>
    </td>
    <input type="hidden" name="PorC0_Ftiempo" id="PorC0_Ftiempo" value="<?=isset($PorC0_Ftiempo) ? number_format($PorC0_Ftiempo, 1, ",", ".") : "0"?>">
    <td><?php echo $ScTotal = $C0T + $C0_TOTAL_F; ?></td>
    <input type="hidden" name="ScTotal" id="ScTotal" value="<?=$ScTotal?>">
</tr>
<tr style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">
    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">TOTAL</td>
    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $total_totalBD = $C1A + $C2A + $C3A + $C4A + $C5T + $SIT + $C0T; ?></td>
    <input type="hidden" name="total_totalBD" id="total_totalBD" value="<?=$total_totalBD?>">
    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">
        <?php
        $denominadorTotalAT = $total_totalBD + $total_total_atiempo;
        if ($denominadorTotalAT > 0) {
            $totalPOR_AT = (($total_totalBD * 100) / $denominadorTotalAT);
            echo number_format($totalPOR_AT, 1, ",", ".");
        } else {
            echo "0";
        }
        ?>
    </td>
    <input type="hidden" name="totalPOR_AT" id="totalPOR_AT" value="<?=isset($totalPOR_AT) ? number_format($totalPOR_AT, 1, ",", ".") : "0"?>">
    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $total_total_atiempo; ?></td>
    <input type="hidden" name="total_total_atiempo" id="total_total_atiempo" value="<?=$total_total_atiempo?>">
    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">
        <?php
        if ($denominadorTotalAT > 0) {
            $totalPOR_FT = (($total_total_atiempo * 100) / $denominadorTotalAT);
            echo number_format($totalPOR_FT, 1, ",", ".");
        } else {
            echo "0";
        }
        ?>
    </td>
    <input type="hidden" name="totalPOR_FT" id="totalPOR_FT" value="<?=isset($totalPOR_FT) ? number_format($totalPOR_FT, 1, ",", ".") : "0"?>">
    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?php echo $totalBDTotalTiempo = $total_totalBD + $total_total_atiempo; ?></td>
    <input type="hidden" name="totalBDTotalTiempo" id="totalBDTotalTiempo" value="<?=$totalBDTotalTiempo?>">
</tr>
      </table>
</td>
  </tr>

 
  <tr>
    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;</td>
  </tr>

  <tr style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">
    <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >
      

    </td>
  </tr> 


</table></td>


</div>

</form>

</body>
</html>