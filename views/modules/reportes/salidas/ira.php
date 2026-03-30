<?php
error_reporting(0);
require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php');     $objCon     = new Connection; $objCon->db_connect();
require_once('../../../../class/Util.class.php');           $objUtil    = new Util;
require_once('../../../../class/Reportes.class.php');       $reporte    = new Reportes;

$parametros               = $objUtil->getFormulario($_POST);
$parametros['frm_inicio'] = $objUtil->fechaInvertida($parametros['fechaInicio']);
$parametros['frm_fin']    = $objUtil->fechaInvertida($parametros['fechaFin']);
$fechaHoy                 = $objUtil->getFechaPalabra(date('Y-m-d'));			
$totalIRA                 = $reporte->prestacionEdadTotalIra($objCon,$parametros);

		// inicializacion en 0
$contmeses_J000aJ99ZM = 0;
$contmeses_J000aJ99ZF = 0;
$contmeses_J200aJ219M = 0;
$contmeses_J200aJ219F = 0;
$contmeses_J120aJ181M = 0;
$contmeses_J120aJ181F = 0;
$contmeses_J111aJ111M = 0;
$contmeses_J111aJ111F = 0;
$contmeses_J200aJ219M = 0;
$contmeses_J200aJ219F = 0;
$cont1_4_J200aJ219M   = 0;
$cont1_4_J200aJ219F   = 0;
$cont1_4_J120aJ181M   = 0;
$cont1_4_J120aJ181F   = 0;
$cont1_4_J111aJ111M   = 0;
$cont1_4_J111aJ111F   = 0;
$cont1_4_J000aJ99ZM   = 0;
$cont1_4_J000aJ99ZF   = 0;
$cont5_14_J200aJ219M  = 0;
$cont5_14_J200aJ219F  = 0;
$cont5_14_J120aJ181M  = 0;
$cont5_14_J120aJ181F  = 0;
$cont5_14_J111aJ111M  = 0;
$cont5_14_J111aJ111F  = 0;
$cont5_14_J000aJ99ZM  = 0;
$cont5_14_J000aJ99ZF  = 0;
$cont15_64_J200aJ219M = 0;
$cont15_64_J200aJ219F = 0;
$cont15_64_J120aJ181M = 0;
$cont15_64_J120aJ181F = 0;
$cont15_64_J111aJ111M = 0;
$cont15_64_J111aJ111F = 0;
$cont15_64_J000aJ99ZM = 0;
$cont15_64_J000aJ99ZF = 0;
$cont65_J200aJ219M    = 0;
$cont65_J200aJ219F    = 0;
$cont65_J120aJ181M    = 0;
$cont65_J120aJ181F    = 0;
$cont65_J111aJ111M    = 0;
$cont65_J111aJ111F    = 0;
$cont65_J000aJ99ZM    = 0;
$cont65_J000aJ99ZF    = 0;

		for($a=0; $a<count($totalIRA); $a++){ //inicio del for totalIRA
			$edad = $totalIRA[$a]['dau_paciente_edad'];
			$diag = $totalIRA[$a]['dau_cierre_cie10'];	
			$sexo = $totalIRA[$a]['sexo'];

   			switch (TRUE){//inicio switch
   				case ($edad < 1):
   				if(strtoupper($diag) >= 'J200' && strtoupper($diag) <= 'J219'){ 	
   					if(strtoupper($sexo) == 'M'){
   						$contmeses_J200aJ219M++;	
   					}
   					if(strtoupper($sexo) == 'F'){
   						$contmeses_J200aJ219F++;	
   					}		
   				}
   				if(strtoupper($diag) >= 'J120' && strtoupper($diag) <= 'J181'){ 	
   					if(strtoupper($sexo) == 'M'){
   						$contmeses_J120aJ181M++;	
   					}
   					if(strtoupper($sexo) == 'F'){
   						$contmeses_J120aJ181F++;	
   					}		
   				}
   				if(strtoupper($diag) >= 'J111' && strtoupper($diag) <= 'J111'){ 	
   					if(strtoupper($sexo) == 'M'){
   						$contmeses_J111aJ111M++;	
   					}
   					if(strtoupper($sexo) == 'F'){
   						$contmeses_J111aJ111F++;	
   					}		
   				}
   				if(strtoupper($diag) >= 'J000' && strtoupper($diag) <= 'J99Z'){ 	
   					if(strtoupper($sexo) == 'M'){
   						$contmeses_J000aJ99ZM++;	
   					}
   					if(strtoupper($sexo) == 'F'){
   						$contmeses_J000aJ99ZF++;	
   					}		
   				}
   				break;

   				case ($edad >= 1 && $edad <= 4):
   				if(strtoupper($diag) >= 'J200' && strtoupper($diag) <= 'J219'){ 	
   					if(strtoupper($sexo) == 'M'){
   						$cont1_4_J200aJ219M++;	
   					}
   					if(strtoupper($sexo) == 'F'){
   						$cont1_4_J200aJ219F++;	
   					}		
   				}
   				if(strtoupper($diag) >= 'J120' && strtoupper($diag) <= 'J181'){ 	
   					if(strtoupper($sexo) == 'M'){
   						$cont1_4_J120aJ181M++;	
   					}
   					if(strtoupper($sexo) == 'F'){
   						$cont1_4_J120aJ181F++;	
   					}		
   				}
   				if(strtoupper($diag) >= 'J111' && strtoupper($diag) <= 'J111'){ 	
   					if(strtoupper($sexo) == 'M'){
   						$cont1_4_J111aJ111M++;	
   					}
   					if(strtoupper($sexo) == 'F'){
   						$cont1_4_J111aJ111F++;	
   					}		
   				}
   				if(strtoupper($diag) >= 'J000' && strtoupper($diag) <= 'J99Z'){ 	
   					if(strtoupper($sexo) == 'M'){
   						$cont1_4_J000aJ99ZM++;	
   					}
   					if(strtoupper($sexo) == 'F'){
   						$cont1_4_J000aJ99ZF++;	
   					}		
   				}
   				break;

   				case ($edad >=5 && $edad <= 14):
   				if(strtoupper($diag) >= 'J200' && strtoupper($diag) <= 'J219'){ 	
   					if(strtoupper($sexo) == 'M'){
   						$cont5_14_J200aJ219M++;	
   					}
   					if(strtoupper($sexo) == 'F'){
   						$cont5_14_J200aJ219F++;	
   					}		
   				}
   				if(strtoupper($diag) >= 'J120' && strtoupper($diag) <= 'J181'){ 	
   					if(strtoupper($sexo) == 'M'){
   						$cont5_14_J120aJ181++;	
   					}
   					if(strtoupper($sexo) == 'F'){
   						$cont5_14_J120aJ181F++;	
   					}		
   				}
   				if(strtoupper($diag) >= 'J111' && strtoupper($diag) <= 'J111'){ 	
   					if(strtoupper($sexo) == 'M'){
   						$cont5_14_J111aJ111M++;	
   					}
   					if(strtoupper($sexo) == 'F'){
   						$cont5_14_J111aJ111F++;	
   					}		
   				}
   				if(strtoupper($diag) >= 'J000' && strtoupper($diag) <= 'J99Z'){ 	
   					if(strtoupper($sexo) == 'M'){
   						$cont5_14_J000aJ99ZM++;	
   					}
   					if(strtoupper($sexo) == 'F'){
   						$cont5_14_J000aJ99ZF++;	
   					}		
   				}
   				break;

   				case ($edad >= 15 && $edad <= 64 ):
   				if(strtoupper($diag) >= 'J200' && strtoupper($diag) <= 'J219'){ 	
   					if(strtoupper($sexo) == 'M'){
   						$cont15_64_J200aJ219M++;	
   					}
   					if(strtoupper($sexo) == 'F'){
   						$cont15_64_J200aJ219F++;	
   					}		
   				}
   				if(strtoupper($diag) >= 'J120' && strtoupper($diag) <= 'J181'){ 	
   					if(strtoupper($sexo) == 'M'){
   						$cont15_64_J120aJ181++;	
   					}
   					if(strtoupper($sexo) == 'F'){
   						$cont15_64_J120aJ181F++;	
   					}		
   				}
   				if(strtoupper($diag) >= 'J111' && strtoupper($diag) <= 'J111'){ 	
   					if(strtoupper($sexo) == 'M'){
   						$cont15_64_J111aJ111M++;	
   					}
   					if(strtoupper($sexo) == 'F'){
   						$cont15_64_J111aJ111F++;	
   					}		
   				}
   				if(strtoupper($diag) >= 'J000' && strtoupper($diag) <= 'J99Z'){ 	
   					if(strtoupper($sexo) == 'M'){
   						$cont15_64_J000aJ99ZM++;	
   					}
   					if(strtoupper($sexo) == 'F'){
   						$cont15_64_J000aJ99ZF++;	
   					}		
   				}
   				break;

   				case($edad >= 65):
   				if(strtoupper($diag) >= 'J200' && strtoupper($diag) <= 'J219'){ 	
   					if(strtoupper($sexo) == 'M'){
   						$cont65_J200aJ219M++;	
   					}
   					if(strtoupper($sexo) == 'F'){
   						$cont65_J200aJ219F++;	
   					}		
   				}
   				if(strtoupper($diag) >= 'J120' && strtoupper($diag) <= 'J181'){ 	
   					if(strtoupper($sexo) == 'M'){
   						$cont65_J120aJ181++;	
   					}
   					if(strtoupper($sexo) == 'F'){
   						$cont65_J120aJ181F++;	
   					}		
   				}
   				if(strtoupper($diag) >= 'J111' && strtoupper($diag) <= 'J111'){ 	
   					if(strtoupper($sexo) == 'M'){
   						$cont65_J111aJ111M++;	
   					}
   					if(strtoupper($sexo) == 'F'){
   						$cont65_J111aJ111F++;	
   					}		
   				}
   				if(strtoupper($diag) >= 'J000' && strtoupper($diag) <= 'J99Z'){ 	
   					if(strtoupper($sexo) == 'M'){
   						$cont65_J000aJ99ZM++;	
   					}
   					if(strtoupper($sexo) == 'F'){
   						$cont65_J000aJ99ZF++;	
   					}		
   				}
   				break;
   			}//fin switch

		}//fin del for totalIRA

		//suma total Otras Respiratorias Agudas
		$X1  = $contmeses_J000aJ99ZM - ($contmeses_J111aJ111M + $contmeses_J120aJ181M + $contmeses_J200aJ219M);
		$X2  = $contmeses_J000aJ99ZF - ($contmeses_J111aJ111F + $contmeses_J120aJ181F + $contmeses_J200aJ219F);
		$X3  = $cont1_4_J000aJ99ZM   - ($cont1_4_J111aJ111M   + $cont1_4_J120aJ181M   + $cont1_4_J200aJ219M);
		$X4  = $cont1_4_J000aJ99ZF   - ($cont1_4_J111aJ111F   + $cont1_4_J120aJ181F   + $cont1_4_J200aJ219F);
		$X5  = $cont5_14_J000aJ99ZM  - ($cont5_14_J111aJ111M  + $cont5_14_J120aJ181M  + $cont5_14_J200aJ219M);
		$X6  = $cont5_14_J000aJ99ZF  - ($cont5_14_J111aJ111F  + $cont5_14_J120aJ181F  + $cont5_14_J200aJ219F);
		$X7  = $cont15_64_J000aJ99ZM - ($cont15_64_J111aJ111M + $cont15_64_J120aJ181M + $cont15_64_J200aJ219M);
		$X8  = $cont15_64_J000aJ99ZF - ($cont15_64_J111aJ111F + $cont15_64_J120aJ181F + $cont15_64_J200aJ219F);
		$X9  = $cont65_J000aJ99ZM    - ($cont65_J111aJ111M    + $cont65_J120aJ181M    + $cont65_J200aJ219M);
		$X10 = $cont65_J000aJ99ZF    - ($cont65_J111aJ111F    + $cont65_J120aJ181F    + $cont65_J200aJ219F);

		$totalParcialJ000aJ99ZM = $X1 + $X3 + $X5 + $X7 + $X9;
		$totalParcialJ000aJ99ZF = $X2 + $X4 + $X6 + $X8 + $X10;
		$totalGralJ000aJ99Z = $totalParcialJ000aJ99ZM + $totalParcialJ000aJ99ZF;
		//suma total Otras Respiratorias Agudas 

		$totalParcialmesesM = $contmeses_J200aJ219M + $contmeses_J120aJ181M + $contmeses_J111aJ111M + $X1;
		$totalParcialmesesF = $contmeses_J200aJ219F + $contmeses_J120aJ181F + $contmeses_J111aJ111F + $X2;
		$totalParcial1_4M   = $cont1_4_J200aJ219M + $cont1_4_J120aJ181M + $cont1_4_J111aJ111M + $X3;
		$totalParcial1_4F   = $cont1_4_J200aJ219F + $cont1_4_J120aJ181F + $cont1_4_J111aJ111F + $X4;
		$totalParcial5_14M  = $cont5_14_J200aJ219M + $cont5_14_J120aJ181M + $cont5_14_J111aJ111M + $X5;
		$totalParcial5_14F  = $cont5_14_J200aJ219F + $cont5_14_J120aJ181F + $cont5_14_J111aJ111F + $X6;
		$totalParcial15_64M = $cont15_64_J200aJ219M + $cont15_64_J120aJ181M + $cont15_64_J111aJ111M + $X7;
		$totalParcial15_64F = $cont15_64_J200aJ219F + $cont15_64_J120aJ181F + $cont15_64_J111aJ111F + $X8;
		$totalParcial65M    = $cont65_J200aJ219M  + $cont65_J120aJ181M + $cont65_J111aJ111M + $X9;
		$totalParcial65F    = $cont65_J200aJ219F  + $cont65_J120aJ181F + $cont65_J111aJ111F + $X10;
		$totalParcialM      = $totalParcialJ200aJ219M + $totalParcialJ120aJ181M + $totalParcialJ111aJ111M + $totalParcialJ000aJ99ZM;
		$totalParcialF      = $totalParcialJ200aJ219F + $totalParcialJ120aJ181F + $totalParcialJ111aJ111F + $totalParcialJ000aJ99ZF;
		$total              = $totalGralJ200aJ219 + $totalGralJ120aJ181 + $totalGralJ111aJ111 +  $totalGralJ000aJ99Z;
		?>
<!-- imprimir horizontal -->
<style type="text/css" media="print">
@page { size: landscape; }
</style>
		<div id="contendidoIra">
			<table width="100%" border="0">
				<tr>
					<td border="0" width="160">

						<img src="<?=PATH?>/assets/img/logo.png" width="75" height="75" />
						<img src="<?=PATH?>/assets/img/nuestroHospital.png" width="75" height="75" />
					</td>
					<td  border="0" valign="top">			
						<table>
							<tr>
								<td  class="mifuente11" >GOBIERNO DE CHILE</td>
							</tr>

							<tr>
								<td  class="mifuente11" >MINISTERIO DE SALUD</td>
							</tr>

							<tr>
								<td  class="mifuente11" >HOSPITAL DR. JUAN NOÉ CREVANI</td>
							</tr>

							<tr>
								<td  class="mifuente11" >RUT: 61.606.000-7</td>
							</tr>

							<tr>
								<td  class="mifuente11" >18 DE SEPTIEMBRE N°1000</td>
							</tr>
						</table>			
					</td>

					<td >
						<table td width="50%" align="right" border="0" style="margin-top: -9%;  margin-right: 3%;">			
							<tr>
								<td class="mifuente11"  style="text-align: right;"><?=$fechaHoy?></td>

							</tr>

							<tr>

							</tr>
						</table>
					</td>
				</tr>
			</table>

			<table border="0" align="center" width="100%">
				<tr>
					<td align="center">
						<strong style="font-size:10; color: ">VIGILANCIA DE INFECCIONES RESPIRATORIAS AGUDAS (I.R.A.)<br/>
							SEMANA ESTADISTICA : <?=strftime("%W",mktime(0,0,0,substr($parametros['frm_inicio'],5,2),substr($parametros['frm_inicio'],8,2),substr($parametros['frm_inicio'],0,4)))?><br /> 
							PERIODO :            <?=$_POST['fechaInicio']?> AL <?=$_POST['fechaFin']?>
						</strong>
					</td>
				</tr>	    


				<table width="100%" border="1" cellpadding="2" cellspacing="0" class="reporte">
					<tr>
						<td width="150" rowspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><strong>MORBILIDAD</strong><strong></strong></td>
						<td width="120" colspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><strong>-1 años</strong></td>
						<td width="120" colspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><strong>1-4 a&ntilde;os</strong></td>
						<td width="120" colspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><strong>5-14 a&ntilde;os</strong></td>
						<td width="120" colspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><strong>15-64 a&ntilde;os</strong></td>
						<td width="120" colspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><strong>65 y +</strong></td>
						<td width="120" colspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><strong>Total Parcial</strong></td>
						<td width="64" rowspan="2" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><strong>Total<br />General</strong></td>

					</tr>

					<tr align="left" valign="top">
						<td width="60" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><strong>M</strong></td>
						<td width="60" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><strong>F</strong></td>
						<td width="60" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><strong>M</strong></td>
						<td width="60" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><strong>F</strong></td>
						<td width="60" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><strong>M</strong></td>
						<td width="60" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><strong>F</strong></td>
						<td width="60" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><strong>M</strong></td>
						<td width="60" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><strong>F</strong></td>
						<td width="60" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><strong>M</strong></td>
						<td width="60" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><strong>F</strong></td>
						<td width="60" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><strong>M</strong></td>
						<td width="60" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><strong>F</strong></td>
					</tr>

					<tr align="left" valign="top">
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >S&iacute;ndrome Bronquial Obstructiva</td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><?=$contmeses_J200aJ219M ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><?=$contmeses_J200aJ219F ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><?=$cont1_4_J200aJ219M ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><?=$cont1_4_J200aJ219F ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><?=$cont5_14_J200aJ219M ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><?=$cont5_14_J200aJ219F ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><?=$cont15_64_J200aJ219M ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><?=$cont15_64_J200aJ219F ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><?=$cont65_J200aJ219M ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><?=$cont65_J200aJ219F ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFF99">
							<?=$totalParcialJ200aJ219M = $contmeses_J200aJ219M + $cont1_4_J200aJ219M + $cont5_14_J200aJ219M + $cont15_64_J200aJ219M + $cont65_J200aJ219M?>							
						</td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#A6A6A6">
							<?=$totalParcialJ200aJ219F = $contmeses_J200aJ219F + $cont1_4_J200aJ219F + $cont5_14_J200aJ219F + $cont15_64_J200aJ219F + $cont65_J200aJ219F?>
						</td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom">
							<?=$totalGralJ200aJ219 = $contmeses_J200aJ219M + $cont1_4_J200aJ219M + $cont5_14_J200aJ219M + $cont15_64_J200aJ219M + $cont65_J200aJ219M + $contmeses_J200aJ219F + $cont1_4_J200aJ219F + $cont5_14_J200aJ219F + $cont15_64_J200aJ219F + $cont65_J200aJ219F?>							
						</td>
					</tr>

					<tr align="left" valign="top">
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Neumon&iacute;a y Bronconeumon&iacute;a</td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><?=$contmeses_J120aJ181M ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><?=$contmeses_J120aJ181F ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><?=$cont1_4_J120aJ181M ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><?=$cont1_4_J120aJ181F ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><?=$cont5_14_J120aJ181M ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><?=$cont5_14_J120aJ181F ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><?=$cont15_64_J120aJ181M ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><?=$cont15_64_J120aJ181F ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><?=$cont65_J120aJ181M ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><?=$cont65_J120aJ181M ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFF99">
							<?=$totalParcialJ120aJ181M = $contmeses_J120aJ181M + $cont1_4_J120aJ181M + $cont5_14_J120aJ181M + $cont15_64_J120aJ181M + $cont65_J120aJ181M ?>
						</td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#A6A6A6">
							<?=$totalParcialJ120aJ181F = $contmeses_J120aJ181F + $cont1_4_J120aJ181F + $cont5_14_J120aJ181F + $cont15_64_J120aJ181F + $cont65_J120aJ181F ?>
						</td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom">
							<?=$totalGralJ120aJ181 = $contmeses_J120aJ181M + $cont1_4_J120aJ181M + $cont5_14_J120aJ181M + $cont15_64_J120aJ181M + $cont65_J120aJ181M + $contmeses_J120aJ181F + $cont1_4_J120aJ181F + $cont5_14_J120aJ181F + $cont15_64_J120aJ181F + $cont65_J120aJ181F ?>
						</td>
					</tr>

					<tr align="left" valign="top">
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Influenza - Gripe</td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><?=$contmeses_J111aJ111M ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><?=$contmeses_J111aJ111F ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><?=$cont1_4_J111aJ111M ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><?=$cont1_4_J111aJ111F ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><?=$cont5_14_J111aJ111M ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><?=$cont5_14_J111aJ111F ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><?=$cont15_64_J111aJ111M ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><?=$cont15_64_J111aJ111F ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><?=$cont65_J111aJ111M ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><?=$cont65_J111aJ111F ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFF99">
							<?=$totalParcialJ111aJ111M = $contmeses_J111aJ111M+ $cont1_4_J111aJ111M + $cont5_14_J111aJ111M + $cont15_64_J111aJ111M + $cont65_J111aJ111M ?>
						</td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#A6A6A6">
							<?=$totalParcialJ111aJ111F = $contmeses_J111aJ111F + $cont1_4_J111aJ111F + $cont5_14_J111aJ111F + $cont15_64_J111aJ111F + $cont65_J111aJ111F ?>
						</td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom">
							<?=$totalGralJ111aJ111 = $contmeses_J111aJ111M+ $cont1_4_J111aJ111M + $cont5_14_J111aJ111M + $cont15_64_J111aJ111M + $cont65_J111aJ111M + $contmeses_J111aJ111F + $cont1_4_J111aJ111F + $cont5_14_J111aJ111F + $cont15_64_J111aJ111F + $cont65_J111aJ111F ?>
						</td>
					</tr>

					<tr align="left" valign="top">
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >Otras Respiratorias Agudas</td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><?=$X1 ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><?=$X2 ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><?=$X3 ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><?=$X4 ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><?=$X5 ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><?=$X6 ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><?=$X7 ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><?=$X8 ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFFCC"><?=$X9 ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#E0E0E0"><?=$X10 ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFF99"><?=$totalParcialJ000aJ99ZM ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#A6A6A6"><?=$totalParcialJ000aJ99ZF ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom"><?=$totalGralJ000aJ99Z ?></td>
					</tr>

					<tr align="left" valign="top">
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><strong>TOTAL</strong></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFF99"><?=$totalParcialmesesM ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#A6A6A6"><?=$totalParcialmesesF ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFF99"><?=$totalParcial1_4M ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#A6A6A6"><?=$totalParcial1_4F ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFF99"><?=$totalParcial5_14M ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#A6A6A6"><?=$totalParcial5_14F ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFF99"><?=$totalParcial15_64M ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#A6A6A6"><?=$totalParcial15_64F ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFF99"><?=$totalParcial65M ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#A6A6A6"><?=$totalParcial65F ?></td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#FFFF99">
							<?=$totalParcialM = $contmeses_J200aJ219M + $cont1_4_J200aJ219M + $cont5_14_J200aJ219M + $cont15_64_J200aJ219M + $cont65_J200aJ219M + $contmeses_J120aJ181M + $cont1_4_J120aJ181M + $cont5_14_J120aJ181M + $cont15_64_J120aJ181M + $cont65_J120aJ181M + $contmeses_J111aJ111M+ $cont1_4_J111aJ111M + $cont5_14_J111aJ111M + $cont15_64_J111aJ111M + $cont65_J111aJ111M + $totalParcialJ000aJ99ZM ?>
						</td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom" bgcolor="#A6A6A6">
							<?=$totalParcialF = $contmeses_J200aJ219F + $cont1_4_J200aJ219F + $cont5_14_J200aJ219F + $cont15_64_J200aJ219F + $cont65_J200aJ219F + $contmeses_J120aJ181F + $cont1_4_J120aJ181F + $cont5_14_J120aJ181F + $cont15_64_J120aJ181F + $cont65_J120aJ181F + $contmeses_J111aJ111F + $cont1_4_J111aJ111F + $cont5_14_J111aJ111F + $cont15_64_J111aJ111F + $cont65_J111aJ111F + $totalParcialJ000aJ99ZF ?>
						</td>
						<td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" valign="bottom">
							<?=$total = $contmeses_J200aJ219M + $cont1_4_J200aJ219M + $cont5_14_J200aJ219M + $cont15_64_J200aJ219M + $cont65_J200aJ219M + $contmeses_J200aJ219F + $cont1_4_J200aJ219F + $cont5_14_J200aJ219F + $cont15_64_J200aJ219F + $cont65_J200aJ219F + $contmeses_J120aJ181M + $cont1_4_J120aJ181M + $cont5_14_J120aJ181M + $cont15_64_J120aJ181M + $cont65_J120aJ181M + $contmeses_J120aJ181F + $cont1_4_J120aJ181F + $cont5_14_J120aJ181F + $cont15_64_J120aJ181F + $cont65_J120aJ181F + $contmeses_J111aJ111M+ $cont1_4_J111aJ111M + $cont5_14_J111aJ111M + $cont15_64_J111aJ111M + $cont65_J111aJ111M + $contmeses_J111aJ111F + $cont1_4_J111aJ111F + $cont5_14_J111aJ111F + $cont15_64_J111aJ111F + $cont65_J111aJ111F + $totalGralJ000aJ99Z ?>
						</td>
					</tr>
				</table>
				<br>

				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" align="left"   class="mifuente11" style="text-decoration:none;color:#666">*No incluye Atenciones Ginecol&oacute;gicas*</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>

				<tr>
					<td colspan="4" align="left">
						<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reportechico">
							<tr>
								<td  class="mifuente11" style="text-decoration:none;color:#666">&nbsp;Nombre responsable de la informaci&oacute;n :  </td>
							</tr>
							<tr>
								<td class="mifuente11" style="text-decoration:none;color:#666">&nbsp;Fecha Emisión Reporte: <strong><?=date('d-m-Y')?></strong></td>
							</tr> 
						</table>
					</td>
				</tr>
			</table>
		</div>