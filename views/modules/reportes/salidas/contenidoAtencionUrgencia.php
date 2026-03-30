<?php

  require("../../../../config/config.php");
  require_once('../../../../class/Connection.class.php');       $objCon      = new Connection; $objCon->db_connect();
  require_once('../../../../class/Util.class.php');             $objUtil     = new Util; 
  require_once('../../../../class/Reportes.class.php');         $reporte     = new Reportes; 
  
  if($_POST){ 
       $campos                                 = $objUtil->getFormulario($_POST); 
       $_SESSION['views']["repostes"]          = $campos;
       $campos['frm_fecha_admision_desde']     = $objUtil->cambiarFormatoFecha2($campos['frm_fecha_admision_desde']);
       $campos['frm_fecha_admision_hasta']     = $objUtil->cambiarFormatoFecha2($campos['frm_fecha_admision_hasta']);       
       $adulto                                 = $reporte->atencionAdulto($objCon,$campos);
       $pediatrico                             = $reporte->atencionPediatrica($objCon,$campos);
       $ginecologico                           = $reporte->atencionGinecologica($objCon,$campos);        
    }else if(isset($_SESSION['views']["repostes"])){
       $campos                                 = $_SESSION['views']["repostes"];
       $campos['frm_fecha_admision_desde']     = $objUtil->cambiarFormatoFecha2($campos['frm_fecha_admision_desde']);
       $campos['frm_fecha_admision_hasta']     = $objUtil->cambiarFormatoFecha2($campos['frm_fecha_admision_hasta']);
       $adulto                                 = $reporte->atencionAdulto($objCon,$campos);
       $pediatrico                             = $reporte->atencionPediatrica($objCon,$campos);
       $ginecologico                           = $reporte->atencionGinecologica($objCon,$campos);
   }else{
       $campos                                 = 0;
   }

   $fechaHoy        = $objUtil->fechaNormal(date('Y-m-d')); 
   $total  = 0; 
   $total2 = 0; 
   $total3 = 0;
   //highlight_string(print_r($pediatrico,true));
?>

<script type="text/javascript" charset="utf-8" src="<?=PATH?>/controllers/client/reportes/contenidoAtencionUrgencia.js?v=0.0.251"></script>
<div id="resumenAtencionesReporte" class="col-lg-12" >
<table border="0" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" cellpadding="0" cellspacing="0" width="99%">
      <tr style="margin-bottom: 100px;">
          <td colspan="3" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" style="font-size:16px; text-align:center; text-decoration:blink;">RESUMEN  DE ATENCIONES MEDICAS POR FECHA (ADMISIÓN)<br />
          DESDE: <?=$objUtil->cambiarFormatoFecha($campos["frm_fecha_admision_desde"]) ?> <br/>HASTA: <?=$objUtil->cambiarFormatoFecha($campos["frm_fecha_admision_hasta"]) ?></td>
      </tr>
      <tr>
          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"  valign="top">
              <table width="99%" border="1" id="demo_table" style="margin:10px;border: #1e73be;">
                <tr style="color:#000;">
                      <td colspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" style="padding-left: 6px;padding-right: 6px;padding-top: 6px;padding-bottom: 6px;">ATENCIÓN: ADULTO</td>
                    </tr>
                    <tr bgcolor="#1e73be" style="color:#FFF;">
                      <td width="246" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" style="padding-left: 6px;padding-right: 6px;padding-top: 6px;padding-bottom: 6px;">PROFESIONAL</td>
                      <td width="74" bgcolor="1a61a0" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" style="padding-left: 6px;padding-right: 6px;padding-top: 6px;padding-bottom: 6px;">N°</td>
                    </tr>

                    <?php for ($i=0; $i<count($adulto); $i++){  ?> 
                     <tr> 
                      <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><img src="<?=PATH?>/assets/img/DAU-05.png" title="Detalle" width="20" height="20" style="vertical-align:middle; cursor:pointer; margin-left: 8px;margin-right: 8px;margin-top: 6px;margin-bottom: 6px;" id="imprime" onClick='modalDetalle("PDF ADULTO", "<?=PATH?>/views/reportes/salidas/generaPDFAdulto.php", "fechaInicio=<?=$campos['frm_fecha_admision_desde']?> + &fechaFin=<?=$campos['frm_fecha_admision_hasta']?> + &medicoTratante=<?=$adulto[$i]['PROdescripcion']?> + &dau_cierre_profesional_id=<?=$adulto[$i]['dau_cierre_profesional_id']?>", "#PDF_ADULTO", "66%", "100%")' /><? if($adulto[$i]['PROdescripcion']){ echo $adulto[$i]['PROdescripcion']; }else echo 'DAU EN PROCESO (SIN CIERRE ADM) '; ?></td>
                      <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?=$adulto[$i]['TOTAL'];?><?$total = $total + $adulto[$i]['TOTAL'];?></td>
                    </tr>
                  <?php } ?>
            
                    <tr bgcolor="#999" style="color:#FFF;">
                      <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" bgcolor="#1e73be" style="color:#FFF;padding-left: 6px;padding-right: 6px;padding-top: 6px;padding-bottom: 6px;">TOTAL DE ATENCIONES</td>
                      <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" bgcolor="#1a61a0" style="color:#FFF;padding-left: 6px;padding-right: 6px;padding-top: 6px;padding-bottom: 6px;"><?=$total;?></td>
                    </tr>
              </table>
        </td>

          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"  valign="top">
              <table width="99%" border="1" id="demo_table2" style="margin:10px;border: #1e73be;">
                <tr style="color:#000;">
                      <td colspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" style="padding-left: 6px;padding-right: 6px;padding-top: 6px;padding-bottom: 6px;">ATENCIÓN: PEDIÁTRICA</td>
                    </tr>
                    <tr bgcolor="#1e73be" style="color:#FFF;">
                      <td width="246" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" style="padding-left: 6px;padding-right: 6px;padding-top: 6px;padding-bottom: 6px;">PROFESIONAL</td>
                      <td width="74" bgcolor="1a61a0" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" style="padding-left: 6px;padding-right: 6px;padding-top: 6px;padding-bottom: 6px;">N°</td>
                  </tr>
            
                    <?php for ($i=0; $i<count($pediatrico); $i++){  ?> 
                    <tr> 
                      <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><img src="<?=PATH?>/assets/img/DAU-05.png" id="modalPediatrico" title="Detalle" width="20" height="20" style="vertical-align:middle; cursor:pointer;margin-left: 8px;margin-right: 8px;margin-top: 6px;margin-bottom: 6px;" id="imprime" onClick='modalDetalle("PDF PEDIATRICO", "<?=PATH?>/views/reportes/salidas/generaPDFpediatrico.php", "fechaInicio=<?=$campos['frm_fecha_admision_desde']?> + &fechaFin=<?=$campos['frm_fecha_admision_hasta']?> + &medicoTratante=<?=$pediatrico[$i]['PROdescripcion']?>", "#PDF_PEDIATRICO", "66%", "100%")'/><?php if($pediatrico[$i]['PROdescripcion']){ echo $pediatrico[$i]['PROdescripcion']; }else echo 'DAU EN PROCESO (SIN CIERRE ADM) '; ?></td>
                      <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?=$pediatrico[$i]['TOTAL'];?><?php $total2 = $total2 + $pediatrico[$i]['TOTAL'];?></td>
                    </tr>
                  <?php } ?>
            
                    <tr bgcolor="#999" style="color:#FFF;">
                      <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" bgcolor="#1e73be" style="color:#FFF;padding-left: 6px;padding-right: 6px;padding-top: 6px;padding-bottom: 6px;">TOTAL DE ATENCIONES</td>
                      <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" bgcolor="#1a61a0" style="color:#FFF;padding-left: 6px;padding-right: 6px;padding-top: 6px;padding-bottom: 6px;"><?=$total2;?></td>
                    </tr>
              </table>
        </td>

          <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="top">
              <table width="99%" border="1" id="demo_table3" style="margin:10px;border: #1e73be;">
                <tr style="color:#000;">
                      <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" colspan="2" style="padding-left: 6px;padding-right: 6px;padding-top: 6px;padding-bottom: 6px;">ATENCIÓN: GINECOLÓGICA</td>
                    </tr>

                    <tr bgcolor="#1e73be" style="color:#FFF;">
                      <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" width="246" style="padding-left: 6px;padding-right: 6px;padding-top: 6px;padding-bottom: 6px;">PROFESIONAL</td>
                      <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" bgcolor="1a61a0" width="74" style="padding-left: 6px;padding-right: 6px;padding-top: 6px;padding-bottom: 6px;">N°</td>
                    </tr>
                  <?php for ($i=0; $i<count($ginecologico); $i++){  ?>
                    <tr> 
                      <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><img src="<?=PATH?>/assets/img/DAU-05.png" id="modalGinecologico" title="Detalle" width="20" height="20" style="vertical-align:middle; cursor:pointer;margin-left: 8px;margin-right: 8px;margin-top: 6px;margin-bottom: 6px;" id="imprime" onClick='modalDetalle("PDF GINECOLOGICO", "<?=PATH?>/views/reportes/salidas/generaPDFginecologico.php", "fechaInicio=<?=$campos['frm_fecha_admision_desde']?> + &fechaFin=<?=$campos['frm_fecha_admision_hasta']?> + &medicoTratante=<?=$ginecologico[$i]['PROdescripcion']?>", "#PDF_GINECOLOGICO", "66%", "100%")'/><?php if($ginecologico[$i]['PROdescripcion']){ echo $ginecologico[$i]['PROdescripcion']; }else echo 'DAU EN PROCESO (SIN CIERRE ADM) '; ?></td>
                      <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?=$ginecologico[$i]['TOTAL'];?><?php $total3 = $total3 + $ginecologico[$i]['TOTAL'];?></td>
                    </tr>
                    <?php } ?>
           
                    <tr bgcolor="#999" style="color:#FFF;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">
                      <td bgcolor="#1e73be" style="color:#FFF;padding-left: 6px;padding-right: 6px;padding-top: 6px;padding-bottom: 6px;" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">TOTAL DE ATENCIONES</td>
                      <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" bgcolor="#1a61a0" style="color:#FFF;padding-left: 6px;padding-right: 6px;padding-top: 6px;padding-bottom: 6px;"><?=$total3?></td>
                    </tr>
              </table>
        </td>
      </tr>
    </table>
    <br>
    <div style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">
      <button  id="btnImprimirResumen" type="button" class="btn btn-outline-primary btn-sm mifuente resultadoBusqueda col mr-3 col-lg-3" alt="Generar PDF" title="Generar PDF"> <i class="fas fa-print mr-2"></i>Imprimir</button>


      <!-- <input type="button" class="btn btn-primary no-print" name="btnImprimirResumen" id="btnImprimirResumen" value="Imprimir"> -->
    </div>
</div>