<iframe height="100%" width="100%" hidden>
	<?php		
		error_reporting(0);
		ini_set('post_max_size', '512M'); 
		ini_set('memory_limit', '1G'); 
		set_time_limit(0);
		header("Pragma: no-cache");
		header("Cache-Control: no-cache");
		header("Cache-Control: no-store");
        require_once('../../../../../estandar/TCPDF-main/tcpdf.php');

		class MYPDF extends TCPDF {
			//Page header
			public function Header() {
			//get the current page break margin
			$bMargin = $this->getBreakMargin();
			// get current auto-page-break mode
			$auto_page_break = $this->AutoPageBreak;
			// disable auto-page-break
			$this->SetAutoPageBreak(false, 0);
			// restore auto-page-break status
			$this->SetAutoPageBreak($auto_page_break, $bMargin);
			// set the starting point for the page content
			$this->setPageMark();
			}
		}

		// create new PDF document
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		//SET DOCUMENT INFORMATION
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('DAU');
		$pdf->SetTitle('PDF Resumen Tiempo Espera');
		$pdf->SetSubject('cccccc');
		$pdf->SetKeywords('dddd, eeee, fffff');
		//$pdf->SetHeaderData('../../assets/img/ABA-05.png', PDF_HEADER_LOGO_WIDTH,'SERVICIO DE SALUD ARICA ','HOSPITAL REGIONAL DE ARICA Y PARINACOTA');
		$pdf->setHeaderFont(Array('helvetica', '', 6));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(PDF_MARGIN_LEFT, 8, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetAutoPageBreak(TRUE, 15);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setLanguageArray($l);
		$pdf->setFontSubsetting(true);
		$pdf->SetFont('helvetica', '', 8, '', true);
		//CREA UNA PAGINA
		$pdf->AddPage();

        require("../../../../config/config.php");
        require_once('../../../../class/Connection.class.php');     $objCon     = new Connection; $objCon->db_connect();
        require_once('../../../../class/Util.class.php');           $objUtil    = new Util;
        require_once('../../../../class/Reportes.class.php');       $reporte    = new Reportes;
		$parametros               = $objUtil->getFormulario($_POST);

		// $c1_atencion = $_POST['c1_atencion'];
		// $c1_nulos    = $_POST['c1_nulos'];
		// $c1_nea      = $_POST['c1_nea'];
		// $c1_cerrados = $_POST['c1_cerrados'];
		// $totalC1     = $_POST['totalC1'];
		// $PorC1       = $_POST['PorC1'];
		// $prom1       = $_POST['prom1'];

		// $c2_atencion = $_POST['c2_atencion'];
		// $c2_nulos    = $_POST['c2_nulos'];
		// $c2_nea      = $_POST['c2_nea'];
		// $c2_cerrados = $_POST['c2_cerrados'];
		// $totalC2     = $_POST['totalC2'];
		// $PorC2       = $_POST['PorC2'];
		// $prom2       = $_POST['prom2'];

		// $c3_atencion = $_POST['c3_atencion'];
		// $c3_nulos    = $_POST['c3_nulos'];
		// $c3_nea      = $_POST['c3_nea'];
		// $c3_cerrados = $_POST['c3_cerrados'];
		// $totalC3     = $_POST['totalC3'];
		// $PorC3       = $_POST['PorC3'];
		// $prom3       = $_POST['prom3'];

		// $c4_atencion = $_POST['c4_atencion'];
		// $c4_nulos    = $_POST['c4_nulos'];
		// $c4_nea      = $_POST['c4_nea'];
		// $c4_cerrados = $_POST['c4_cerrados'];
		// $totalC4     = $_POST['totalC4'];
		// $PorC4       = $_POST['PorC4'];
		// $prom4       = $_POST['prom4'];		

		// $parametros['frm_inicio'] = $objUtil->fechaInvertida($parametros['fechaInicio']);
		// $parametros['frm_fin']    = $objUtil->fechaInvertida($parametros['fechaFin']);
		// $fechaHoy                 = $objUtil->getFechaPalabra(date('Y-m-d'));
		// $datos                    = $reporte->accidentesTrabajo($objCon,$parametros);		
		//highlight_string(print_r($_POST),true);

		$html = '
		<table width="765" border="0">
			<tr>
			<td border="0" width="115">
			<pre></pre>
				<img src="'.PATH.'/assets/img/logo.png" width="55" height="55" />
				<img src="'.PATH.'/assets/img/nuestroHospital.png" width="55" height="55" />
			</td>

			<td  border="0" valign="top">
				<pre></pre>

				<tr>
					<td>GOBIERNO DE CHILE</td>
				</tr>

				<tr>
					<td>MINISTERIO DE SALUD</td>
				</tr>

				<tr>
					<td>HOSPITAL DR. JUAN NOÉ CREVANI</td>
				</tr>

				<tr>
					<td>RUT: 61.606.000-7</td>
				</tr>

				<tr>
					<td>18 DE SEPTIEMBRE N°1000</td>
				</tr>
			</td>
				<td>
					<table td width="50%" align="left" >
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;
						
						
						<tr>
							'.$fechaHoy.'
						</tr>

						<tr>
							
						</tr>
					</table>
				</td>
			</tr>
		</table>		
		
		<table border="0">
			<tr>
				<td align="center">
					<strong>Resumen Tiempo Espera DESDE: '.$fechainicioreportepdf.' HASTA: '.$fechaterminoreportepdf.'</strong>
				</td>
		    </tr>	    
		</table>
		<br>

		<table width="646" border="1">
            <tr>
            	<td align="center" colspan="8"><strong>RESUMEN TIEMPO DE ESPERA SEGÚN ESTADO</strong> - ADMISIÓN - CATEGORIZACIÓN</td>
            </tr>
            <tr align="center">
              <td width="92">CATEGORIZACIÓN</td>
              <td width="79">EN ATENCIÓN</td>
              <td width="49">NULOS</td>
              <td width="49">N.E.A</td>
              <td width="59">CERRADO</td>
              <td width="49">TOTAL</td>
              <td width="49">TOTAL %</td>
              <td width="220" align="center" >PROMEDIO MINUTOS DE <BR />ESPERA CATEGORIZACIÓN</td>
            </tr>
            
            <tr align="right">
                <td  align="left">&nbsp;&nbsp;ESI-1</td>
                <td>'.$_POST['c1_atencion'] .'&nbsp;&nbsp;</td>
                <td>'.$_POST['c1_nulos'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['c1_nea'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['c1_cerrados'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['totalC1'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['PorC1'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['prom1'].'&nbsp;&nbsp;</td>
            </tr>
            <tr align="right">
                <td align="left">&nbsp;&nbsp;ESI-2</td>
                <td>'.$_POST['c2_atencion'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['c2_nulos'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['c2_nea'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['c2_cerrados'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['totalC2'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['PorC2'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['prom2'].'&nbsp;&nbsp;</td>
            </tr>
            <tr  align="right">
                <td align="left">&nbsp;&nbsp;ESI-3</td>
                <td>'.$_POST['c3_atencion'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['c3_nulos'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['c3_nea'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['c3_cerrados'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['totalC3'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['PorC3'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['prom3'].'&nbsp;&nbsp;</td>
            </tr>
            <tr align="right">
                <td align="left">&nbsp;&nbsp;ESI-4</td>
                <td>'.$_POST['c4_atencion'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['c4_nulos'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['c4_nea'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['c4_cerrados'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['totalC4'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['PorC4'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['prom4'].'&nbsp;&nbsp;</td>
            </tr>
            <tr  align="right">
                <td align="left">&nbsp;&nbsp;ESI-5</td>
                <td>'.$_POST['c5_atencion'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['c5_nulos'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['c5_nea'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['c5_cerrados'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['totalC5'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['PorC5'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['prom5'].'&nbsp;&nbsp;</td>
            </tr>
            <tr  align="right">
                <td align="left">&nbsp;&nbsp;S/C</td>
                <td>'.$_POST['SC_atencion'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['SC_nulos'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['SC_nea'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['SC_cerrados'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['totalSC'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['PorSC'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['promC0'].'&nbsp;&nbsp;</td>
            </tr>            
            <tr  align="right">
                <td align="left"><strong>&nbsp;&nbsp;TOTAL</strong> </td>
                <td><strong>'.$_POST['totalAtencion'].'&nbsp;&nbsp;</strong></td>
                <td><strong>'.$_POST['totalNulos'].'&nbsp;&nbsp;</strong></td>
                <td><strong>'.$_POST['totalNea'].'&nbsp;&nbsp;</strong></td>
                <td><strong>'.$_POST['totalCerrado'].'&nbsp;&nbsp;</strong></td>
                <td><strong>'.$_POST['totalTotal'].'&nbsp;&nbsp;</strong></td>
                <td><strong>'.$_POST['totalPorc'].'&nbsp;&nbsp;</strong></td>
                <td><strong>'.$_POST['total_promedio'].'&nbsp;&nbsp;</strong></td>
            </tr>
        </table> 

        <div></div>

        <table width="646" border="1" >
            <tr>
                <td align="center" colspan="4"><strong>RESUMEN TIEMPO DE ESPERA</strong> </td>
            </tr>
            
            <tr align="center">
                <td>&nbsp;&nbsp;INDICACIÓN - ATENCIÓN</td>
                <td>&nbsp;&nbsp;HOSPITALIZADOS</td>
                <td>&nbsp;&nbsp;ALTA</td>
                <td>&nbsp;&nbsp;TOTAL</td>
            </tr>
            
            <tr align="right">
                <td align="left" >&nbsp;&nbsp;0 - 6 HRS.</td>
                <td>'.$_POST['contHosp0_6'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['contAlta0_6'].'&nbsp;&nbsp;</td> 
                <td>'.$_POST['contTotal6'].'&nbsp;&nbsp;</td>
            </tr>
            
            <tr align="right">
                <td align="left">&nbsp;&nbsp;6 - 12 HRS.</td>
                <td>'.$_POST['contHosp6_12'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['contAlta6_12'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['conTotal12'].'&nbsp;&nbsp;</td>
            </tr>
            
            <tr align="right">
                <td align="left">&nbsp;&nbsp;12 - 24 HRS.</td>
                <td>'.$_POST['contHosp12_24'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['contAlta12_24'].'&nbsp;&nbsp;</td>
             	<td>'.$_POST['conTotal24'].'&nbsp;&nbsp;</td>
            </tr>
            
            <tr align="right">
                <td align="left">&nbsp;&nbsp;+ 24 HRS.</td>
                <td>'.$_POST['contHosp24'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['contAlta24'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['contotal2424'].'&nbsp;&nbsp;</td>
            </tr>

            <tr align="right">
                <td align="left">&nbsp;&nbsp;TOTAL</td>
                <td>'.$_POST['TH'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['TA'].'&nbsp;&nbsp;</td>
                <td><strong>'.$_POST['totalTHTA'].'&nbsp;&nbsp;</strong></td>
            </tr>
        </table>

        <div></div>

        <table width="646" border="1" id="tabla">   
      		<tr>
          		<td colspan="6" align="center"><strong>TIEMPO DE ESPERA INGRESO A BOX</strong>(En atención y derivados)</td>
        	</tr>

        	<tr>
         		<td width="130" align="center" rowspan="2">CATEGORIZACIÓN</td>
          		<td colspan="2" align="center">ATENCIÓN A TIEMPO<BR /></td>
          		<td colspan="2" align="center">ATENCIÓN FUERA DE TIEMPO<BR /></td>
           		<td width="85" rowspan="2" align="center">T. CANTIDAD</td>
        	</tr> 
        	
        	<tr>
            	<td align="center">TOTAL CANTIDAD</td>
           		<td align="center">TOTAL %</td>
           		<td align="center">TOTAL CANTIDAD</td>
           		<td align="center">TOTAL %</td>
        	</tr> 
          
	        <tr align="right">
	        	<td width="130"  align="left">&nbsp;&nbsp;ESI-1  | 5 MIN (0 HR.)</td>
	            <td width="107">'.$_POST['C1A'].'&nbsp;&nbsp;</td>
	            <td width="107">'.$_POST['PorC1_atiempo'].'&nbsp;&nbsp;</td>
	            <td width="108">'.$_POST['C1_TOTAL_F'].'&nbsp;&nbsp;</td>
	            <td width="108">'.$_POST['PorC1_Ftiempo'].'&nbsp;&nbsp;</td>
	            <td width="86">'.$_POST['c1Total'].'&nbsp;&nbsp;</td>
	        </tr>  
         	
         	<tr align="right">
	       		<td align="left">&nbsp;&nbsp;ESI-2  | 30 MIN  (1/2 HR.)</td>
	            <td>'.$_POST['C2A'].'&nbsp;&nbsp;</td>
	            <td>'.$_POST['PorC2_atiempo'].'&nbsp;&nbsp;</td>
	            <td>'.$_POST['C2_TOTAL_F'].'&nbsp;&nbsp;</td>
	            <td>'.$_POST['PorC2_Ftiempo'].'&nbsp;&nbsp;</td>
	        	<td>'.$_POST['c2Total'].'&nbsp;&nbsp;</td>
        	</tr>  
             <tr align="right">
                <td align="left">&nbsp;&nbsp;ESI-3  | 90 MIN (1 1/2 HR.)</td>
                <td>'.$_POST['C3A'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['PorC3_atiempo'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['C3_TOTAL_F'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['PorC3_Ftiempo'].'&nbsp;&nbsp;</td>
           		<td>'.$_POST['c3Total'].'&nbsp;&nbsp;</td>
            </tr> 

            <tr align="right">
                <td align="left">&nbsp;&nbsp;ESI-4  | 180 MIN  (3 HRS.)</td>
                <td>'.$_POST['C4A'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['PorC4_atiempo'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['C4_TOTAL_F'].'&nbsp;&nbsp;</td>
                <td>'.$_POST['PorC4_Ftiempo'].'&nbsp;&nbsp;</td>
           		<td>'.$_POST['c4Total'].'&nbsp;&nbsp;</td>
            </tr>

        <tr align="right">
            <td align="left">&nbsp;&nbsp;ESI-5 </td>
            <td>'.$_POST['C5T'].'&nbsp;&nbsp;</td>
            <td>'.$_POST['PorC5_atiempo'].'&nbsp;&nbsp;</td>
            <td>'.$_POST['C5_TOTAL_F'].'&nbsp;&nbsp;</td>
            <td>'.$_POST['PorC5_Ftiempo'].'&nbsp;&nbsp;</td>
       		<td>'.$_POST['c5Total'].'&nbsp;&nbsp;</td>
        </tr>  

        <tr align="right">
        	<td  align="left">&nbsp;&nbsp;S/C </td>
        	<td>'.$_POST['C0T'].'&nbsp;&nbsp;</td>
        	<td>'.$_POST['PorC0_atiempo'].'&nbsp;&nbsp;</td>
        	<td>'.$_POST['C0_TOTAL_F'].'&nbsp;&nbsp;</td>
        	<td>'.$_POST['PorC0_Ftiempo'].'&nbsp;&nbsp;</td>
        	<td>'.$_POST['ScTotal'].'&nbsp;&nbsp;</td>
        </tr>

        <tr align="right">
          <td align="left"><strong>&nbsp;&nbsp;TOTAL </strong></td>
          <td><strong>'.$_POST['total_totalBD'].'&nbsp;&nbsp;</strong></td>
          <td><strong>'.$_POST['totalPOR_AT'].'&nbsp;&nbsp;</strong></td>
          <td><strong>'.$_POST['total_total_atiempo'].'&nbsp;&nbsp;</strong></td>
          <td><strong>'.$_POST['totalPOR_FT'].'&nbsp;&nbsp;</strong></td>
          <td>'.$_POST['totalBDTotalTiempo'].'&nbsp;&nbsp;</td>
        </tr>
      </table>
        ';

	$pdf->writeHTML($html, true, false, true, false, '');


  $nombre_archivo = "reporteResumenTiempoEspera2.pdf";
  $pdf->Output(__DIR__ . '/' . $nombre_archivo, 'FI');
  $url = "/RCEDAU/views/modules/reportes/salidas/".$nombre_archivo;
?>
</iframe>

<div class="embed-responsive embed-responsive-16by9">
	<iframe id="iframeBincard" class="embed-responsive-item" src="<?=$url?>" height="700" width="700" allowfullscreen></iframe>
</div>

<script>
$('#iframeBincard').ready(function(){
	ajaxRequest(raiz+'/controllers/server/reportes/main_controller.php','nombreArchivo=<?=$url?>&accion=eliminarPDF', 'POST', 'JSON', 1, '', true);
});
</script>