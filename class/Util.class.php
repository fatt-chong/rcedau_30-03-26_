<?php    class Util{
	// print('<pre>'); print_r($_SESSION['permiso'.SessionName]); print('</pre>');
	
	function reemplazarCaracteresHTML($string) {
		$string = str_replace("'", "&#39;", $string);

		$string = str_replace("<", "&#60;", $string);
  	$string = str_replace("&#60;br>", "<br>", $string);

		return $string;
	}

	function infoDatosNombreTabla($transexual_bd,$nombreSocial_bd,$nombrePaciente,$width,$height){
		require(dirname(__FILE__)."/../config/config.php");
		$datosPacienteNombre = "";
		if($transexual_bd == "S" || $transexual_bd == "s"){
			if($transexual_bd == "S" && $nombreSocial_bd != "" || $transexual_bd == "s" && $nombreSocial_bd != "" ){
				if($nombreSocial_bd == "(NULL)"){
					$datosPacienteNombre = '<img src="'.PATH.'/assets/img/transIco.png" width="'.$width.'" height="'.$height.'" class="infoTooltip" title="Paciente Transexual"><b>'.$nombrePaciente;
				}else{
					$datosPacienteNombre = '<img src="'.PATH.'/assets/img/transIco.png" width="'.$width.'" height="'.$height.'" class="infoTooltip" title="Paciente Transexual"><b>'.strtoupper($nombreSocial_bd).'</b>'." / ".$nombrePaciente;
				}
			}else if($transexual_bd == "S" && $nombreSocial_bd == "" || $transexual_bd == "s" && $nombreSocial_bd == ""){
				$datosPacienteNombre = '<img src="'.PATH.'/assets/img/transIco.png" width="'.$width.'" height="'.$height.'"" class="infoTooltip" title="Paciente Transexual"><b>'.$nombrePaciente.'</b>';
			}
		}else{
			$datosPacienteNombre = $nombrePaciente;
		}
		return $datosPacienteNombre;
	}

	function cambiarServidorReporte ( $fechaInicio, $fechaTermino ) {
		$objCon = null;
		$diferenciaFechas = abs(strtotime($fechaTermino) - strtotime($fechaInicio));
		if ( $diferenciaFechas > 604800 ) {
			$objCon = new Connection("10.6.21.29", "vista");
		} else {
			$objCon = new Connection("10.6.21.26", "daurce");
		}
		$objCon->db_connect();
		return $objCon;
	}
	function promedioTiempos ( $tiempos ) {
		if ( empty($tiempos['totalFilas']) || is_null($tiempos['totalFilas']) ) {
			return '00:00:00';
		}
		$promedioSegundos = floor($tiempos['totalSegundos'] / $tiempos['totalFilas']);
		$horas = floor($promedioSegundos / 3600);
		$minutos = floor($promedioSegundos / 60 % 60);
		$segundos = floor($promedioSegundos % 60);
		return sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos);
	}
	function get_timespan_string_sseg($older, $newer) {

		$Y1 = date("Y", strtotime($older));
		$Y2 = date("Y", strtotime($newer));
		$Y = $Y2 - $Y1;

		$m1 = date("m", strtotime($older));
		$m2 = date("m", strtotime($newer));
		$m = $m2 - $m1;

		$d1 = date("d", strtotime($older));
		$d2 = date("d", strtotime($newer));
		$d = $d2 - $d1;

		$H1 = date("H", strtotime($older));
		$H2 = date("H", strtotime($newer));
		$H = $H2 - $H1;

		$i1 = date("i", strtotime($older));
		$i2 = date("i", strtotime($newer));
		$i = $i2 - $i1;

		if($i < 0) {
			$H = $H - 1;
			$i = $i + 60;
		}
		if($H < 0) {
			$d = $d - 1;
			$H = $H + 24;
		}
		if($d < 0) {
			$m = $m - 1;
			$d = $d + $this->get_days_for_previous_month($m2, $Y2);
		}
		if($m < 0) {
			$Y = $Y - 1;
			$m = $m + 12;
		}
		$timespan_string = $this->create_timespan_string_sinSegundo($Y, $m, $d, $H, $i);
		return $timespan_string;
	}
	function get_days_for_previous_month($current_month, $current_year) {
		$previous_month = $current_month - 1;
		if($current_month == 1) {
			$current_year = $current_year - 1;
			$previous_month = 12;
		}
		if($previous_month == 11 || $previous_month == 9 || $previous_month == 6 || $previous_month == 4) {
			return 30;
		}
		else if($previous_month == 2) {
			if(($current_year % 4) == 0) {
			return 29;
			}
			else {
			return 28;
			}
		}
		else {
			return 31;
		}
	}
	function create_timespan_string_sinSegundo($Y, $m, $d, $H, $i)
	{
		$timespan_string = '';
		$found_first_diff = false;
		if($Y >= 1) {
			$found_first_diff = true;
			$timespan_string .= $this->pluralize($Y, 'año').' ';
		}
		if($m >= 1 || $found_first_diff) {
			$found_first_diff = true;
			$timespan_string .= $this->pluralize($m, 'mes').' ';
		}
		if($d >= 1 || $found_first_diff) {
			$found_first_diff = true;
			$timespan_string .= $this->pluralize($d, 'día').' ';
		}
		if($H >= 1 || $found_first_diff) {
			$found_first_diff = true;
			$timespan_string .= $this->pluralize($H, 'hora').' ';
		}
		if($i >= 1 || $found_first_diff) {
			$found_first_diff = true;
			$timespan_string .= $this->pluralize($i, 'minuto').' ';
		}
		return $timespan_string;
	}
	function infoNombreDocMinuscula($transexual_bd,$nombreSocial_bd,$nombrePaciente){
		$nombreSocial = "";
		if($transexual_bd == "S" || $transexual_bd == "s"){
			if($nombreSocial_bd != ""){
				$nombreSocial = '<b>'.ucwords(mb_strtolower($nombreSocial_bd, "UTF-8")).'</b>'." / ".$nombrePaciente;
			}
		}else{
			$nombreSocial = $nombrePaciente;

		}
		return $nombreSocial;
	}
	function asignar( &$parametro ) {

		return ( isset($parametro) && ! empty($parametro) && ! is_null($parametro) && $parametro != ''  ) ? $parametro : null;

	}
	function vista_nombre_admisionUpdateDetalle($transexual_bd,$nombreSocial_bd){
		$result = array('mostrar' => 0, 'classCol' => '');
		if ($transexual_bd == "S" || $transexual_bd == "s") {
			if ($nombreSocial_bd != "") {
				$result['mostrar'] = 1;
				$result['classCol'] = "col-md-2";
			}else{
				$result['mostrar'] = 0;
				$result['classCol'] = "col-md-3";
			}
		} else {
			$result['mostrar'] = 0;
			$result['classCol'] = "col-md-3";
		}

		return $result;
	}
	function evaluarNumeroAtencionDau ( $numeroTope, $numeroActual ) {
		$numeroAGuardar = $numeroActual + 1;
		if ( $numeroAGuardar > $numeroTope ) {
			$numeroAGuardar = 1;
		}
		return $numeroAGuardar;
	}
	function imagenRCE($sexo,$edad){

		switch ($sexo) {
		case "M":	$imgSexo = "RCE-H"; break;
		case "F":	$imgSexo = "RCE-M"; break;
		default	: 	$imgSexo = "RCE-N"; break;
		}
		if($edad < 18){
			$imgEdad = "-kid";
		}else if(($edad >= 18) && ($edad < 60)){
			$imgEdad = "-adult";
		}else if($edad >= 60){
			$imgEdad = "-old";
		}
		return $imgSexo.$imgEdad;
	}
	function vista_dau_input_label($transexual_bd,$nombreSocial_bd,$nombrePaciente,$nombreLabel,$negrita){
		require(dirname(__FILE__)."/../config/config.php");
		$result = array('input' => '', 'label' => '');
		
		if ($transexual_bd == "S" || $transexual_bd == "s") {
			if ($nombreSocial_bd != "") {
				if($negrita == 'S'){
					$result['input'] = '<b>'.strtoupper($nombreSocial_bd).'</b> / '.strtoupper($nombrePaciente);
					$result['label'] = '<img src="'.PATH.'/assets/img/transIco.png" width="16" height="16">'.' '.$nombreLabel;
				}else{
					$result['input'] = strtoupper($nombreSocial_bd).' / '.strtoupper($nombrePaciente);
					$result['label'] = '<img src="'.PATH.'/assets/img/transIco.png" width="16" height="16">'.' '.$nombreLabel;
				}
			}else{
				$result['input'] = strtoupper($nombrePaciente);
				$result['label'] = $nombreLabel;
			}
		} else {
			$result['input'] = strtoupper($nombrePaciente);
			$result['label'] = $nombreLabel;
		}

		return $result;
	}
	function DatoPacienteTrans($transexual,$nombreSocial,$nombrePaciente){
		if($transexual == 'S'){
			$nombrePaciente = '<i class="fas fa-venus-mars " style="color:#dd3bd1;"></i> <b>'.strtoupper($nombreSocial).'</b> / '.$nombrePaciente;
		}
		return $nombrePaciente;
	}

	
	function pluralize( $count, $text )
	{
		if ($text == 'mes') {
			return $count . ( ( $count == 1 ) ? ( " $text" ) : ( " ${text}es" ) );
		}
		else{
			return $count . ( ( $count == 1 ) ? ( " $text" ) : ( " ${text}s" ) );
		}
	}
	function get_timespan_string($older, $newer) {

		$Y1 = date("Y", strtotime($older));
		$Y2 = date("Y", strtotime($newer));
		$Y = $Y2 - $Y1;

		$m1 = date("m", strtotime($older));
		$m2 = date("m", strtotime($newer));
		$m = $m2 - $m1;

		$d1 = date("d", strtotime($older));
		$d2 = date("d", strtotime($newer));
		$d = $d2 - $d1;

		$H1 = date("H", strtotime($older));
		$H2 = date("H", strtotime($newer));
		$H = $H2 - $H1;

		$i1 = date("i", strtotime($older));
		$i2 = date("i", strtotime($newer));
		$i = $i2 - $i1;

		$s1 = date("s", strtotime($older));
		$s2 = date("s", strtotime($newer));
		$s = $s2 - $s1;

		if($s < 0) {
			$i = $i -1;
			$s = $s + 60;
		}
		if($i < 0) {
			$H = $H - 1;
			$i = $i + 60;
		}
		if($H < 0) {
			$d = $d - 1;
			$H = $H + 24;
		}
		if($d < 0) {
			$m = $m - 1;
			$d = $d + $this->get_days_for_previous_month($m2, $Y2);
		}
		if($m < 0) {
			$Y = $Y - 1;
			$m = $m + 12;
		}
		$timespan_string = $this->create_timespan_string($Y, $m, $d, $H, $i, $s);
		return $timespan_string;
	}
	function vista_dau_input_label_modo_2($transexual_bd,$nombreSocial_bd,$nombrePaciente,$nombreLabel,$negrita){
		require(dirname(__FILE__)."/../config/config.php");
		$result = array('input' => '', 'label' => '');

		if ($transexual_bd == "S" || $transexual_bd == "s") {
			if ($nombreSocial_bd != "") {
				if($negrita == 'S'){

					if($nombreSocial_bd != "(NULL)"){
						$result['input'] = '<img src="'.PATH.'/assets/img/transIco.png" width="16" height="16"><b>'.strtoupper($nombreSocial_bd).'</b> / '.strtoupper($nombrePaciente);
						$result['label'] = $nombreLabel;
					}else{
						$result['input'] = strtoupper($nombrePaciente);
						$result['label'] = $nombreLabel;
					}

				}else{
					$result['input'] = '<img src="'.PATH.'/assets/img/transIco.png" width="16" height="16">'.strtoupper($nombreSocial_bd).' / '.strtoupper($nombrePaciente);
					$result['label'] = $nombreLabel;
				}
			}else{
				$result['input'] = strtoupper($nombrePaciente);
				$result['label'] = $nombreLabel;
			}
		} else {
			$result['input'] = strtoupper($nombrePaciente);
			$result['label'] = $nombreLabel;
		}

		return $result;
	}
	function create_timespan_string($Y, $m, $d, $H, $i, $s)
		{
		$timespan_string = '';
		$found_first_diff = false;
		if($Y >= 1) {
			$found_first_diff = true;
			$timespan_string .= $this->pluralize($Y, 'año').' ';
		}
		if($m >= 1 || $found_first_diff) {
			$found_first_diff = true;
			$timespan_string .= $this->pluralize($m, 'mes').' ';
		}
		if($d >= 1 || $found_first_diff) {
			$found_first_diff = true;
			$timespan_string .= $this->pluralize($d, 'día').' ';
		}
		if($H >= 1 || $found_first_diff) {
			$found_first_diff = true;
			$timespan_string .= $this->pluralize($H, 'hora').' ';
		}
		if($i >= 1 || $found_first_diff) {
			$found_first_diff = true;
			$timespan_string .= $this->pluralize($i, 'minuto').' ';
		}
		if($found_first_diff) {
			$timespan_string .= 'y ';
		}
		$timespan_string .= $this->pluralize($s, 'segundo');
		return $timespan_string;
	}
	function edad_paciente ($fecha_de_nacimiento) {
		$fecha_nac = explode("-",$fecha_de_nacimiento);
		$fecha_actual = explode("-",date("Y-m-d"));

		$anos = $fecha_actual[0] - $fecha_nac[0];

		if ($fecha_actual[1] < $fecha_nac[1]) {
			$anos = $anos - 1;
			$meses = (12 - $fecha_nac[1]) + $fecha_actual[1] -1;
		} else {
			$meses = $fecha_actual[1] - $fecha_nac[1];
		}

		if ($fecha_actual[2] < $fecha_nac[2]) {
			$mes = $mes - 1;
			$dias = $fecha_nac[2] - $fecha_actual[2];
		} else {
			$dias = $fecha_actual[2] - $fecha_nac[2];
		}

		$edad_paciente = $anos."a,".$meses."m,".$dias."d";
		return $edad_paciente;
	}
	function addAllPermisosDAU(){
		// print('<pre>'); print_r($_SESSION['permiso'.SessionName]); print('</pre>');
		// session_start();
		//Todos los permisos DAU
		$permisos_dau = array(
							1 => '810', 
							2 => '811', 
							3 => '812', 
							4 => '813', 
							5 => '814', 
							6 => '815', 
							7 => '816', 
							8 => '817', 
							9 => '818', 
							10 => '819', 
							11 => '820', 
							12 => '821', 
							13 => '822', 
							14 => '823', 
							15 => '824', 
							16 => '825', 
							17 => '826', 
							18 => '827', 
							19 => '830', 
							20 => '831', 
							21 => '832', 
							22 => '833', 
							23 => '834', 
							24 => '835', 
							25 => '836', 
							26 => '837', 
							27 => '839', 
							28 => '840', 
							29 => '841', 
							30 => '842', 
							31 => '843', 
							32 => '844', 
							33 => '845',
							34 => '867'
						);

		$_SESSION['permisosDAU'.SessionName] = $permisos_dau;

	}
	function formatoFechaNormal2($fecha_str, $formato)
	{
		if ($fecha_str) {
			$fecha_str = substr($fecha_str, 0, strpos($fecha_str, 'T'));
			$fecha = date($formato, strtotime($fecha_str));
			$nueva_fecha = new DateTime(date($fecha));
			return $nueva_fecha->format($formato);
		}

		return null;
	}
	function remoteFileExists($url) {
         $curl = curl_init($url);
         curl_setopt($curl, CURLOPT_NOBODY, true);
         $result = curl_exec($curl);
         $ret = false;
        if ($result !== false) {
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);  
            if ($statusCode == 200) {
                $ret = true;   
            }
        }
        curl_close($curl);
        return $ret;
    }
	function fechaHoraNormal($fecha, $hora) {
		return date("d-m-Y H:i", strtotime($fecha . " " . $hora));
	}

	function rutDigito($rut){
		return $rut . "-" . $this->generaDigito($rut);
	}

	function getHorarioServidor($objCon){
		$sql ="SELECT 	CURDATE() as fecha,
						CURRENT_TIME() as hora ";
		$query = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR getHorarioServidor<br>");
		return $query;
	}

	function porcentaje($x,$y){
		if($y==0){
			return '0,00';
			}else{
				$calc = number_format($x*100/$y, 2, ',', ' ');
			return $calc;
		}
	}

	function obtener_edad($stamp){  
		$stamp = strtotime($stamp); 
		$c = date("Y",$stamp);  
		$b = date("m",$stamp);  
		$a = date("d",$stamp);  
		
		$anos = date("Y")-$c;  
		
		if(date("m")-$b > 0){  
		}elseif(date("m")-$b == 0){  
			if(date("d")-$a < 0){  
				$anos = $anos-1;  
			}  
		}else{  
			$anos = $anos-1;  
		}  
		return $anos;  
	}

	function numeroSemana($fecha){
		$fecha_caso = $this->fechaStamp($fecha);
		$primer_dia = $this->fechaStamp($this->getPrimerDiaMes(date('Y', $this->fechaStamp($fecha)), date('m', $this->fechaStamp($fecha))));
		$diferencia = (date('W', $fecha_caso)) - (date('W', $primer_dia)) + 1;
		if($diferencia < 0)//PARA LOS DIAS QUE SOBRAN DESPUES DE LA SEMANA 52
			$diferencia = (53) - (date('W', $primer_dia)) + 1;
		return $diferencia;
	}
	function getFecha(){ //OBTENER HORA ACTUAL
		date_default_timezone_set("America/Santiago");
		setlocale(LC_TIME, "spanish");
		$fecha = date('d-m-Y');
		return $fecha;
	}
	function getHora(){ //OBTENER HORA ACTUAL
		//date_default_timezone_set("America/Santiago");
		//setlocale(LC_TIME, "spanish");
		$Time = strftime("%H:%M");
		return $Time;
	}
	function cambiarFormatoFechaEspecial($fecha){
		if($fecha=='')
			return $fecha;
		list($anio,$mes,$dia)=explode("-",$fecha); 
		return $dia." ".$mes." ".$anio; 
	}
	function titulo($nombre){
		$nombreMay = ucwords(strtolower($nombre));
		return $nombreMay;
	}
	function ValidaDVRut($rut) {

	    $tur = strrev($rut);
	    $mult = 2;

	    for ($i = 0; $i <= strlen($tur); $i++) {
	       if ($mult > 7) $mult = 2;

	       $suma = $mult * substr($tur, $i, 1) + $suma;
	       $mult = $mult + 1;
	    }

	    $valor = 11 - ($suma % 11);

	    if ($valor == 11) {
	        $codigo_veri = "0";
	      } elseif ($valor == 10) {
	        $codigo_veri = "k";
	      } else {
	        $codigo_veri = $valor;
	    }
	  return $codigo_veri;
	}
	function cambiarFormatoFecha($fecha){
	    list($anio,$mes,$dia)=explode("-",$fecha);
	    return $dia."-".$mes."-".$anio;
	}
	function getHoraBD(){ //OBTENER LA HORA CORRECTA DE LA ZONA
		//date_default_timezone_set("America/Santiago");
		//setlocale(LC_TIME, "spanish");
		$Time = strftime("%H:%M");
		return $Time;
	}
	function getTimeStamp(){ //OBTENER LA TIMESTAMP CORRECTA DE LA ZONA
		//date_default_timezone_set("America/Santiago");
		//setlocale(LC_TIME, "spanish");
		$Time = strftime("%H:%M:%S");
		$fecha = date('Y-m-d');
		$resultado = $fecha." ".$Time;
		return $resultado;
	}
	function fechaStamp($fecha){
		return strtotime($fecha);
	}
	function fechasAnteriores($fecha, $cantidad, $formato){
		$fecha_ultima = strtotime($fecha);
		$dias =($cantidad * 86400);
		if($formato == 'n')
			$fecha_proxima = date("d-m",$fecha_ultima - $dias);
		if($formato == 'i')
			$fecha_proxima = date("Y-m-d",$fecha_ultima - $dias);
		return $fecha_proxima;
	}
	function fechasSiguientes($fecha, $cantidad, $formato){
		$fecha_ultima = strtotime($fecha);
		$dias =($cantidad * 86400);
		if($formato == 'n')
			$fecha_proxima = date("d-m",$fecha_ultima + $dias);
		if($formato == 'i')
			$fecha_proxima = date("Y-m-d",$fecha_ultima + $dias);
		if($formato == 'cn')
			$fecha_proxima = date("d-m-Y",$fecha_ultima + $dias);
		if($formato == 's')
			$fecha_proxima = date("j",$fecha_ultima + $dias);
		if($formato == 'm')
			$fecha_proxima = date("n",$fecha_ultima + $dias);
		if($formato == 'y')
			$fecha_proxima = date("Y",$fecha_ultima + $dias);
		return $fecha_proxima;
	}
	function diasTranscurridos($fecha_menor, $fecha_mayor){
		$fecha_menor = strtotime($fecha_menor);
		$fecha_mayor = strtotime($fecha_mayor);
		$diferencia = $fecha_mayor - $fecha_menor;
		$dias = $diferencia / 86400;
		return floor($dias);
	}
	function diff_dte($date1, $date2){
       if (!is_integer($date1)) $date1 = strtotime($date1);
       if (!is_integer($date2)) $date2 = strtotime($date2);  
       return floor(abs($date1 - $date2) / 60 / 60 / 24);
	}
	function actualizaTimeStamp($hora, $cantidad, $operando){
		switch($operando){
			case '+':	$hora = $this->fechaStamp($hora);
						$hora += $cantidad * 60;
						return date('H:i', $hora);
						break;
			case '-':	$hora = $this->fechaStamp($hora);
						$hora -= $cantidad * 60;
						return date('H:i', $hora);
						break;
		}
	}
	function getPrimerDiaMes($anio,$mes){
  		return date("Y-m-d",(mktime(0,0,0,$mes,1,$anio)));
	}
	function getUltimoDiaMes($anio,$mes){
  		return date("Y-m-d",(mktime(0,0,0,$mes+1,1,$anio)-1));
	}
	//METODOS CONVERSION FECHAS
	function fechaNormal($fecha){
		if($fecha=='')
		return $fecha;
		list($anio,$mes,$dia)=explode("-",$fecha);
		return $dia."-".$mes."-".$anio;
	}
	function fechaInvertida($fecha){
		list($dia,$mes,$anio)=explode("-",$fecha);
		return $anio."-".$mes."-".$dia;
	}
	function infoNombreDocExcel($transexual_bd,$nombreSocial_bd,$nombrePaciente){
		$nombreSocial = "";
		if($transexual_bd == "S" || $transexual_bd == "s"){
			if($nombreSocial_bd != ""){
				$nombreSocial = strtoupper($nombreSocial_bd)." / ".$nombrePaciente;
			}
		}else{
			$nombreSocial = $nombrePaciente;
		}
		return $nombreSocial;
	}
	function edadActualAdmision($fecha_de_nacimiento,$fecha_admision){
		$array_nacimiento = explode ( "-", $fecha_de_nacimiento );
		$array_actual = explode ( "-", $fecha_admision );
		if($array_nacimiento[0] > 1900){
			$anos =  $array_actual[0] - $array_nacimiento[0];
			$meses = $array_actual[1] - $array_nacimiento[1];
			$dias =  $array_actual[2] - $array_nacimiento[2];
			if ($dias < 0) {
				--$meses;
				switch ($array_actual[1]) {
					case 1:     $dias_mes_anterior=31; break;
					case 2:     $dias_mes_anterior=31; break;
					case 3:
					if (checkdate(2,29,$array_actual[0]))
					{
						$dias_mes_anterior=29; break;
					} else {
						$dias_mes_anterior=28; break;
					}
					case 4:     $dias_mes_anterior=31; break;
					case 5:     $dias_mes_anterior=30; break;
					case 6:     $dias_mes_anterior=31; break;
					case 7:     $dias_mes_anterior=30; break;
					case 8:     $dias_mes_anterior=31; break;
					case 9:     $dias_mes_anterior=31; break;
					case 10:     $dias_mes_anterior=30; break;
					case 11:     $dias_mes_anterior=31; break;
					case 12:     $dias_mes_anterior=30; break;
				}
				$dias=$dias + $dias_mes_anterior;
			}
			if ($meses < 0) {
				--$anos;
				$meses=$meses + 12;
			}

			if($meses==1 ){
				$edadCompleta = "$anos a&ntilde;os, $meses mes, $dias d&iacute;as";
			}else if($anos==1){
				$edadCompleta = "$anos a&ntilde;o, $meses meses, $dias d&iacute;as";

			}else if ($dias==1) {
				$edadCompleta = "$anos a&ntilde;os, $meses meses, $dias d&iacute;a";
			}else if($meses==0){
				$edadCompleta = "$anos a&ntilde;os, $dias d&iacute;as";
			}else if($anos==0){
				$edadCompleta = "$meses meses, $dias d&iacute;as";
			}else if($dias==0){
				$edadCompleta = "$anos a&ntilde;os, $meses meses";
			}else{
				$edadCompleta = "$anos a&ntilde;os, $meses meses, $dias d&iacute;as";
			}
			return($edadCompleta);
		}else{
			return("* Verificar Fecha de Nacimiento");
		}
	}
	function infoNombreDoc($transexual_bd,$nombreSocial_bd,$nombrePaciente){
		$nombreSocial = "";
		if($transexual_bd == "S" || $transexual_bd == "s"){
			if($nombreSocial_bd != ""){
				if($nombreSocial_bd == "(NULL)"){
					$nombreSocial = $nombrePaciente;
				}else{
					$nombreSocial = '<b>'.strtoupper($nombreSocial_bd).'</b>'." / ".$nombrePaciente;
				}

			}
		}else{
			$nombreSocial = $nombrePaciente;
		}
		return $nombreSocial;
	}
	function fechaInvertidaslash($fecha){
		list($dia,$mes,$anio)=explode("/",$fecha);
		return $anio."-".$mes."-".$dia;
	}
	function calcularEdad($fechaNacimiento, $fechaActual) {
	    // Convertir las fechas a timestamps
	    $nacimientoTimestamp = strtotime($fechaNacimiento);
	    $actualTimestamp = strtotime($fechaActual);

	    // Obtener los componentes de las fechas
	    $nacimientoAnio = date('Y', $nacimientoTimestamp);
	    $nacimientoMes = date('m', $nacimientoTimestamp);
	    $nacimientoDia = date('d', $nacimientoTimestamp);

	    $actualAnio = date('Y', $actualTimestamp);
	    $actualMes = date('m', $actualTimestamp);
	    $actualDia = date('d', $actualTimestamp);

	    // Calcular la edad
	    $edad = $actualAnio - $nacimientoAnio;

	    // Ajustar la edad si la persona aún no ha cumplido años este año
	    if (($actualMes < $nacimientoMes) || ($actualMes == $nacimientoMes && $actualDia < $nacimientoDia)) {
	        $edad--;
	    }

	    return $edad;
	}
	function edadActual($fecha) {
		list($anio,$mes,$dia) = explode("-",$fecha);
    	$anio_act = date("Y");
    	$mes_act = date("m");
    	$dia_act = date("d");
		if (($mes == $mes_act) && ($dia > $dia_act))
			$anio_act=($anio_act-1);
		if ($mes > $mes_act)
			$anio_act=($anio_act-1);
		$edad=($anio_act-$anio);
		return $edad;
	}
	//GENERA DIGITO RUT
	// function generaDigito($rut){
	// 	if($rut == '')
	// 		return '';
	// 	$tur = strrev($rut);
	// 	$mult = 2;
	// 	for ($i = 0; $i <= strlen($tur); $i++) {
	// 		if ($mult > 7)
	// 			$mult = 2;

	// 	$suma = $mult * substr($tur, $i, 1) + $suma;
	// 	$mult = $mult + 1;
	// 	}

	// 	$valor = 11 - ($suma % 11);

	// 	if ($valor == 11) {
	// 	$codigo_veri = "0";
	// 	} elseif ($valor == 10) {
	// 	$codigo_veri = "K";
	// 	} else {
	// 	$codigo_veri = $valor;
	// 	}
	// 	return $codigo_veri;
	// }
	function generaDigito($rut) {
    // Si el RUT está vacío, retornamos vacío
    if ($rut == '') {
        return '';
    }

    // Revertir el RUT para facilitar el cálculo
    $tur = strrev($rut);
    
    // Inicializar las variables
    $suma = 0; // Inicializar la suma
    $mult = 2; // El multiplicador inicial

    // Recorrer cada dígito del RUT
    for ($i = 0; $i < strlen($tur); $i++) {
        if ($mult > 7) {
            $mult = 2; // Resetear el multiplicador después de llegar a 7
        }

        // Multiplicar y acumular el resultado
        $suma += $mult * (int)substr($tur, $i, 1);

        // Incrementar el multiplicador
        $mult++;
    }

    // Calcular el dígito verificador
    $valor = 11 - ($suma % 11);

    // Determinar el dígito verificador
    if ($valor == 11) {
        $codigo_veri = "0";
    } elseif ($valor == 10) {
        $codigo_veri = "K";
    } else {
        $codigo_veri = (string)$valor;
    }

    // Retornar el dígito verificador
    return $codigo_veri;
}
	function generaDigitoCOMPLETO($rut){
		$cadenarut=$rut;
		if($rut == '')
			return '';
		$tur = strrev($rut);
		$mult = 2;
		for ($i = 0; $i <= strlen($tur); $i++) {
			if ($mult > 7)
				$mult = 2;

		$suma = $mult * substr($tur, $i, 1) + $suma;
		$mult = $mult + 1;
		}

		$valor = 11 - ($suma % 11);

		if ($valor == 11) {
		$codigo_veri = "0";
		} elseif ($valor == 10) {
		$codigo_veri = "K";
		} else {
		$codigo_veri = $valor;
		}
		return $cadenarut."-".$codigo_veri;
	}
	function queSexo($sexo){
		if($sexo == 'M'){
			$sexoCm = "Masculino";
			}else if($sexo == 'F'){
				$sexoCm = "Femenino";
				}else{
					$sexoCm = "Indefinido";
					}
		return $sexoCm;
	}
	function edad_paciente2($fecha_de_nacimiento) {
		$fecha_nac = explode("-",$fecha_de_nacimiento); // fecha nacimiento
		$fecha_actual = explode("-",date("Y-m-d")); // fecha actual

		$anos = $fecha_actual[0] - $fecha_nac[0];

		if ($fecha_actual[1] < $fecha_nac[1]) {  //mes actual es menos o igual que al mes de nacimiento
			$anos = $anos - 1;
			$meses = (12 - $fecha_nac[1]) + $fecha_actual[1] -1;
		} else {
			$meses = $fecha_actual[1] - $fecha_nac[1];
		}

		if ($fecha_actual[2] < $fecha_nac[2]) {  //el dia actual es menor al dia de nacimiento
			$mes = $mes - 1;
			$dias = $fecha_nac[2] - $fecha_actual[2];
		} else {
			$dias = $fecha_actual[2] - $fecha_nac[2];
		}

		$edad_paciente = "$anos años, $meses meses, $dias días";
		return $edad_paciente;
	}
	//FORMATEAR SALIDAS
	function formatearNumero($numero){
		if($numero){
			
		return number_format($numero, 0, "", ".");
		}
		// return number_format($numero, 0, "", ".");
	}
	function formatearNumero2($numero){
		return number_format($numero, 0, "", ",");
	}	function formatearNumeroReporte($numero){
		return number_format($numero, 0, ",", ".");
	}
	function formatearNumeroDecimalesReporte($numero){
		return number_format($numero, 2, ',', '.');
	}
	function formatearNumeroDecimalesFull($numero){
		return number_format($numero, 4, ',', '.');
	}
	function truncarTexto($string, $limit) {
		$break=" ";//CARACTER QUE USA PARA CORTAR LA CADENA
		$pad="...";//AGREGA ESTO AL FINAL DE LA CADENA DE SALIDA
		if(strlen($string) <= $limit)// RETORNA SIN CAMBIOS SI LA CADENA ES MAS CORTA QUE EL LIMITE ESTABLECIDO
			return $string;
		if(false !== ($breakpoint = strpos($string, $break, $limit))) {
			if($breakpoint < strlen($string) - 1) {
				$string = substr($string, 0, $breakpoint) . $pad;
			}
		}
		return $string;
	}
	function ocultarTexto($string){
		$string = substr($string, 0, -3)."xxx";
		return $string;
	}
	function getMesPalabra($MEScod){
		$meses = array(1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre');
		$datos['mes'] = $meses[$MEScod];
		return ($datos);
	}
	function getFechaPalabra($fecha){
		$DIAcod = date('N',strtotime($fecha));
		$DIAdesc = date('d',strtotime($fecha));
		$MEScod = date('n',strtotime($fecha));
		$ANIOcod = date('Y',strtotime($fecha));
		$dias = array(1=>'Lunes',2=>'Martes',3=>'Miércoles',4=>'Jueves',5=>'Viernes',6=>'Sábado',7=>'Domingo');
		$meses = array(1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre');
		$datos = $dias[$DIAcod]." ".$DIAdesc." de ".$meses[$MEScod]." de ".$ANIOcod;
		//$datos = strtoupper($datos);
		return ($datos);
	}
	function calcularMinutos($hora1,$hora2){
		list($horas_1,$minutos_1) = explode(':',$hora1);
		list($horas_2,$minutos_2) = explode(':',$hora2);
		$total_minutos_1 = ($horas_1 * 60)+ $minutos_1;
		$total_minutos_2 = ($horas_2 * 60)+ $minutos_2;
		$total_minutos_trasncurridos = $total_minutos_2 - $total_minutos_1;
		return $total_minutos_trasncurridos;
	}
	function actualizaPagina($accion, $totalPag){
		switch($accion){
			case 'P':	$_SESSION['pagina_actual'] = 1;
						break;
			case 'U':	$_SESSION['pagina_actual'] = $totalPag;
						break;
			case '+':	$_SESSION['pagina_actual']++;
						if($_SESSION['pagina_actual'] > $totalPag) $_SESSION['pagina_actual'] = $totalPag;
						break;
			case '-':	$_SESSION['pagina_actual']--;
						if($_SESSION['pagina_actual'] < 1) $_SESSION['pagina_actual'] = 1;
						break;
		}
	}
	function esBisiesto(int $ano): bool {
    	// return (bool) date('L', strtotime("$ano-01-01"));
	}
	function edadActualCompleto($fecha_de_nacimiento){
		$fecha_actual = date ("Y-m-d");
		// separamos en partes las fechas
		$array_nacimiento = explode ( "-", $fecha_de_nacimiento );
		$array_actual = explode ( "-", $fecha_actual );
		if($array_nacimiento[0] > 1900){
			$anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años
			$meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses
			$dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos días
			//ajuste de posible negativo en $días
			if ($dias < 0) {
				--$meses;
				//ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual
				switch ($array_actual[1]) {
					   case 1:     $dias_mes_anterior=31; break;
					   case 2:     $dias_mes_anterior=31; break;
					   case 3:
							// if (esBisiesto($array_actual[0]))
							// {
							// 	$dias_mes_anterior=29; break;
							// } else {
							// 	$dias_mes_anterior=28; break;
							// }
					   case 4:     $dias_mes_anterior=31; break;
					   case 5:     $dias_mes_anterior=30; break;
					   case 6:     $dias_mes_anterior=31; break;
					   case 7:     $dias_mes_anterior=30; break;
					   case 8:     $dias_mes_anterior=31; break;
					   case 9:     $dias_mes_anterior=31; break;
					   case 10:     $dias_mes_anterior=30; break;
					   case 11:     $dias_mes_anterior=31; break;
					   case 12:     $dias_mes_anterior=30; break;
				}
				$dias=$dias + $dias_mes_anterior;
			}
			//ajuste de posible negativo en $meses
			if ($meses < 0) {
				--$anos;
				$meses=$meses + 12;
			}
			$edadCompleta = "$anos a&ntilde;os, $meses meses, $dias d&iacute;as";
			return($edadCompleta);
		}else{
			return("* Verificar Fecha de Nacimiento");
		}
	}
	function formateaParametro($parametro){
		$parametro = explode(',',$parametro);
		foreach($parametro as $k=>$v){
			$variable .= "'".$v."',";
		}
		$variable = rtrim($variable,',');
		return $variable;
	}
	function getFormulario($parametros){
		$array = array();
		foreach($parametros as $nombre_campo => $valor){
			$key = str_replace("$", "", $nombre_campo);
			$array[$key] = $valor;
		}
		return $array;
	}
	function groupArray($array,$groupkey){
		if (count($array)>0){
			$keys = array_keys($array[0]);
			$removekey = array_search($groupkey, $keys);
			if ($removekey===false)
				return array("Clave \"$groupkey\" no existe");
			else
				unset($keys[$removekey]);
			$groupcriteria = array();
			$return=array();
			foreach($array as $value){
				$item=null;
				foreach ($keys as $key){
					$item[$key] = $value[$key];
				}
				$busca = array_search($value[$groupkey], $groupcriteria);
				if ($busca === false){
					$groupcriteria[]=$value[$groupkey];
					$return[]=array($groupkey=>$value[$groupkey],'groupeddata'=>array());
					$busca=count($return)-1;
				}
				$return[$busca]['groupeddata'][]=$item;
			}
			return $return;
		}else
			return array();
	}
	function rut(string $rut): string {
	    // Remover cualquier carácter que no sea dígito o 'k' (para el dígito verificador)
	    $rut = preg_replace('/[^0-9kK]/', '', $rut);

	    // Asegurar que el RUT tenga al menos dos caracteres (número y dígito verificador)
	    if (strlen($rut) < 2) {
	        // throw new InvalidArgumentException("El RUT proporcionado es inválido.");
	    }

	    // Extraer número base y dígito verificador
	    $numeroBase = substr($rut, 0, -1); // Todo menos el último carácter
	    $digitoVerificador = substr($rut, -1); // Último carácter

	    // Formatear el número base con puntos
	    $numeroBaseFormateado = number_format((int) $numeroBase, 0, '', '.');

	    // Devolver el RUT formateado
	    return $numeroBaseFormateado . '-' . strtoupper($digitoVerificador);
	}
	// function rut( $rut ) {
	// 	return number_format( substr ( $rut, 0 , -1 ) , 0, "", ".") . '-' . substr ( $rut, strlen($rut) -1 , 1 );
	// }
	function buscaEnMatriz($matriz, $indice, $valor){ // Busca un indice
		foreach($matriz as $key => $vector)
		{
			if ( $vector[$indice] === $valor )
				return $key;
		}
		return false;
	}
	function in_array_matriz($needle, $haystack, $strict = false, $retornaIndice = false) {
		foreach ($haystack as $item ) {
			if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_matriz($needle, $item, $strict))) {
				if($retornaIndice)
					return $item;
				else
					return true;
			}
		}
		return false;
	}
	function eliminaEspacios($string){ // Ej: "A      B      C" -> "A B C" , Es decir, deja solo un espacio entre palabras
		$cadena = preg_replace('/\s+/', ' ', trim($string));
		return $cadena;
	}
	function check_date($fecha, $separacion){ //$fecha 01-01-2001 $separacion /  -
		if($fecha == '' || $separacion == '')
			return false;
		trim($fecha);
		$trozos = explode($separacion, $fecha);
		$dia = $trozos[0];
		$mes = $trozos[1];
		$año = $trozos[2];
		if(@checkdate($mes, $dia, $año)){//warning si es string
			return true;
		}else{
			return false;
		}
	}
	function validateVar($var) {
    		return $var ?? '';
		}
	function versionJS(){
	    // Asegúrate de que ANO_INICIO esté definida previamente
	    $fechaInicial = date('Y-01-01');  // Fecha inicial del año
	    $fechaActual = date('Y-m-d');      // Fecha actual

	    // Cálculo de los años transcurridos desde ANO_INICIO
	    $V1 = date('Y') ;

	    // Cálculo de los días transcurridos
	    $V2 = $this->diasTranscurridos($fechaInicial, $fechaActual);

	    // Cálculo de la hora y minutos actuales
	    // Corregir 'His' que debe estar en formato de string: 'His' se refiere a la hora, minutos y segundos
	    $V3 = date('His');

	    // Retorna la versión en formato Año.DíasTranscurridos.HoraMinutoSegundo
	    return $V1 . '.' . $V2 . '.' . $V3;
	}
	function validar_email($email){
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;
		}else{
			return false;
		}
	}



	function fechaAnteriorSegunTurno ( $tipoHorarioTurno ) {

		$fechaAnterior  = date( 'Y-m-d', strtotime( date('Y-m-d')  . ' -1 day' ) );

		if ( empty($tipoHorarioTurno) || is_null($tipoHorarioTurno) ) {

			$fechaAnterior .= ' 08:00:00';

			return $fechaAnterior;

		}

		$horaFechaAnterior = ( $tipoHorarioTurno == 1 || $tipoHorarioTurno == 2 || $tipoHorarioTurno == 3 || $tipoHorarioTurno == 4 || $tipoHorarioTurno == 5 ) ? '08:00:00' : '09:00:00';

		$fechaAnterior .= ' '.$horaFechaAnterior;

		return $fechaAnterior;

	}

	// function usuarioActivo(){

	// 	if(isset($_SESSION['usuarioActivo'.SessionName]['usuario'])){
	// 		return $_SESSION['usuarioActivo'.SessionName]['usuario'];
	// 	}else{
	// 		return $_SESSION['MM_Username'.SessionName];
	// 	}

	// }
	
	function existe( &$parametro ) {

		return ( isset($parametro) && ! empty($parametro) && ! is_null($parametro) && $parametro != '' ) ? true : false;

	}
	function usuarioActivo ( ) {

		if ( isset($_SESSION['usuarioActivo']['usuario']) ) {

			return $_SESSION['usuarioActivo']['usuario'];

		} else {

			return $_SESSION['MM_Username'.SessionName];

		}

	}
	function cargarPermisoDau($objCon, $idsuario){
		// $objCon->db_select("acceso");
		$sql="SELECT
			acceso.usuario_has_rol.usuario_idusuario,
			acceso.rol.idrol,
			acceso.rol.descripcion
			FROM
			acceso.rol
			INNER JOIN acceso.usuario_has_rol ON acceso.usuario_has_rol.rol_idrol = rol.idrol
			WHERE usuario_has_rol.usuario_idusuario = '$idsuario'";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
		$roles=array();
		for($i=0;$i<count($datos); $i++){
			$roles[$i] = $datos[$i]['idrol'];
		}
		return $roles;
	}
	function setRun_addDV($_rol) {
		$rut = $_rol;
		// Si el RUT está vacío, retornamos vacío
	    if ($rut == '') {
	        return '';
	    }

	    // Revertir el RUT para facilitar el cálculo
	    $tur = strrev($rut);
	    
	    // Inicializar las variables
	    $suma = 0; // Inicializar la suma
	    $mult = 2; // El multiplicador inicial

	    // Recorrer cada dígito del RUT
	    for ($i = 0; $i < strlen($tur); $i++) {
	        if ($mult > 7) {
	            $mult = 2; // Resetear el multiplicador después de llegar a 7
	        }

	        // Multiplicar y acumular el resultado
	        $suma += $mult * (int)substr($tur, $i, 1);

	        // Incrementar el multiplicador
	        $mult++;
	    }

	    // Calcular el dígito verificador
	    $valor = 11 - ($suma % 11);

	    // Determinar el dígito verificador
	    if ($valor == 11) {
	        $codigo_veri = "0";
	    } elseif ($valor == 10) {
	        $codigo_veri = "K";
	    } else {
	        $codigo_veri = (string)$valor;
	    }

	    // Retornar el dígito verificador
	    return $_rol."-".$codigo_veri;


	}


	function cual_tipo($cod_serv){

	switch($cod_serv){
		
		case(1):
			$tipo_1 = 2;
			$d_tipo_1 = "MEDICINA";
			break;
		case(2):
			$tipo_1 = 2;
			$d_tipo_1 = "MEDICINA";
			break;
		case(3):
			$tipo_1 = 1;
			$d_tipo_1 = "CIRUGIA"; 
			break;
		case(4):
			$tipo_1 = 1;
			$d_tipo_1 = "CIRUGIA"; 
			break;
		case(5):
			$tipo_1 = 7;
			$d_tipo_1 = "TRAUMATOLOGIA"; 
			break;
		case(6):
			$tipo_1 = 15;
			$d_tipo_1 = "NEONATOLOGIA CUNA BASICA"; 
			break;
		case(7):
			$tipo_1 = 5;
			$d_tipo_1 = "PEDIATRIA INDIFERENCIADA";
			break;
		case(8):
			$tipo_1 = 11;
			$d_tipo_1 = "UCI"; 
			break;
		case(9):
			$tipo_1 = 12;
			$d_tipo_1 = "SAI"; 
			break;
		case(10):
			$tipo_1 = 10;
			$d_tipo_1 = "Intermedio covid"; 
			break;
		case(11):
			$tipo_1 = 11;
			$d_tipo_1 = "COVID 4TO PISO"; 
			break;
		case(12):
			$tipo_1 = 10;
			$d_tipo_1 = "PSIQUIATRIA"; 
			break;	
		case(14):
			$tipo_1 = 9;
			$d_tipo_1 = "OBSTETRICIA"; 
			break;
		case(45):
			$tipo_1 = 45;
			$d_tipo_1 = "PARTOS"; 
			break;
		case(46):
			$tipo_1 = 46;
			$d_tipo_1 = "PENSIONADO"; 
			break;	
		}
		return $tipo_1."-".$d_tipo_1;
	}

	function calculoAMD($fecha_de_nacimiento){ 

	$fecha_actual = date ("Y-m-d"); 
	
	// separamos en partes las fechas 
	$array_nacimiento = explode ( "-", $fecha_de_nacimiento ); 
	$array_actual = explode ( "-", $fecha_actual ); 
	
	if($array_nacimiento[0] > 1900){
		
	$anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años 
	$meses = $array_actual[1] - $array_nacimiento[1]; // calculamos meses 
	$dias =  $array_actual[2] - $array_nacimiento[2]; // calculamos días 
	
	
		
	//ajuste de posible negativo en $días 
	if ($dias < 0) 
	{ 
		--$meses; 
	
		//ahora hay que sumar a $dias los dias que tiene el mes anterior de la fecha actual 
		switch ($array_actual[1]) { 
			   case 1:     $dias_mes_anterior=31; break; 
			   case 2:     $dias_mes_anterior=31; break; 
			   case 3:  
					/*if (bisiesto($array_actual[0])) 
					{ 
						$dias_mes_anterior=29; break; 
					} else { 
						$dias_mes_anterior=28; break; 
					} */
					$dias_mes_anterior=29; break;
			   case 4:     $dias_mes_anterior=31; break; 
			   case 5:     $dias_mes_anterior=30; break; 
			   case 6:     $dias_mes_anterior=31; break; 
			   case 7:     $dias_mes_anterior=30; break; 
			   case 8:     $dias_mes_anterior=31; break; 
			   case 9:     $dias_mes_anterior=31; break; 
			   case 10:     $dias_mes_anterior=30; break; 
			   case 11:     $dias_mes_anterior=31; break; 
			   case 12:     $dias_mes_anterior=30; break; 
		} 
	
		$dias=$dias + $dias_mes_anterior; 
	} 
	
	//ajuste de posible negativo en $meses 
	if ($meses < 0) 
	{ 
		--$anos; 
		$meses=$meses + 12; 
	} 
	
	$edadCompleta = "$anos (a), $meses (m) y $dias (d)"; 
	return($edadCompleta);
	
	}else{
		
		return("* Verificar Fecha de Nacimiento");
		}
	}


	function soloanios($fecha_de_nacimiento){
	$fecha_actual = date ("Y-m-d"); 

	// separamos en partes las fechas 
	$array_nacimiento = explode ( "-", $fecha_de_nacimiento ); 
	$array_actual = explode ( "-", $fecha_actual ); 
	
	if($array_nacimiento[0] > 1900){
		
	$anos =  $array_actual[0] - $array_nacimiento[0]; // calculamos años 
		}
	
	return($anos);
	}

	function RutFormateado2($string)
	{ // Ej: "A      B      C" -> "A B C" , Es decir, deja solo un espacio entre palabras
		//$cadena = preg_replace('/\s+/', '-', trim($string));
		$cadena = str_replace(".", "", $string);
		$nuevorut = "";
		for ($i = 0; $i < strlen($cadena); $i++) {
			if ($cadena[$i] != "-") {
				$nuevorut = $nuevorut . $cadena[$i];
			} else {
				break;
			}
		}

		return $nuevorut;
	}


	function queFormato($tipo){
		switch($tipo){
			case 2:
				$textoFormato = "<strong>- <u>Otros Examenes</u></strong> &nbsp;<br/>
								<table border='1'>
								  <tr>
									<td width='180'>Examen</td>
									<td width='100'>Fecha</td>
									<td width='100'>Resultados</td>
								  </tr>
								  <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								  </tr>
								  <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>		
								  </tr>
								</table>
								<br/>";
				
			break;
			case 3:
				$textoFormato = "<strong>- <u>Examenes Pendientes</u></strong> &nbsp;<br/>
								<table border='1'>
								  <tr>
									<td width='180'>Examen</td>
									<td width='100'>Fecha</td>
									<td width='100'>Lugar</td>
								  </tr>
								  <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								  </tr>
								  <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>		
								  </tr>
								</table>
								<br/>
								<strong>Existencia de exámenes con resultado pendiente señalando claramente de cuales se trata, cuándo y dónde deben retirarse y/o informarse. </strong>";
			break;
			
			}
			return ($textoFormato);
		}


		function generarRandomString($cadena, $length, $spec){
			$randomString 	= '';
			$caracteres 	= '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

			if($spec){
				$caracteres .= '.+-()*&^%$#@!;';
			}
			$charactersLength 	= strlen($caracteres);
			
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $caracteres[rand(0, $charactersLength - 1)];
			}

			if($cadena){
				$cadena = $cadena.$randomString;
				for ($i = 0; $i < $length; $i++) {
					$randomString .= $cadena[rand(0, $charactersLength - 1)];
				}
			}
			return $randomString;
		}


		function cambiarFormatoFecha2($fecha){ 
			list($dia,$mes,$anio)=explode("-",$fecha); 
			return $anio."-".$mes."-".$dia; 
		} 




		function eliminar_tildes($cadena){

    //Codificamos la cadena en formato utf8 en caso de que nos de errores
    $cadena = utf8_encode($cadena);

    //Ahora reemplazamos las letras
    $cadena = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $cadena
    );

    $cadena = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $cadena );

    $cadena = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $cadena );

    $cadena = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $cadena );

    $cadena = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $cadena );

    $cadena = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C'),
        $cadena
    );

    return $cadena;
}


function obtenerFechaHabilProximo($objCon, $fecha, $cercanoLejano){//Y-m-d, +/-
	$fds 	= array(6, 7);//6 SABADO 7 DOMINGO
	$rango	= 8;
	$sql = "SELECT
				fer.FERfecha,
				fer.FERdescripcion
			FROM agenda.feriados AS fer
			WHERE fer.FERfecha >= CURDATE()
			ORDER BY fer.FERfecha";
	$diasfestivos = $objCon->consultaSQL($sql, "Error al listar feriados<br>");
	$date = new DateTime($fecha);
	while($rango){
		if ((in_array($date->format('N'), $fds)) OR (in_array($date->format('Y-m-d'), $this->array_column($diasfestivos, 'FERfecha')))){
			$date->modify($cercanoLejano.'1 day');
		}else{
			break;
		}
		$rango--;
	}
	return $date->format('Y-m-d');
}


}
if (!function_exists('array_group_by')) {
	/**
	 * Groups an array by a given key.
	 *
	 * Groups an array into arrays by a given key, or set of keys, shared between all array members.
	 *
	 * Based on {@author Jake Zatecky}'s {@link https://github.com/jakezatecky/array_group_by array_group_by()} function.
	 * This variant allows $key to be closures.
	 *
	 * @param array $array   The array to have grouping performed on.
	 * @param mixed $key,... The key to group or split by. Can be a _string_,
	 *                       an _integer_, a _float_, or a _callable_.
	 *
	 *                       If the key is a callback, it must return
	 *                       a valid key from the array.
	 *
	 *                       If the key is _NULL_, the iterated element is skipped.
	 *
	 *                       ```
	 *                       string|int callback ( mixed $item )
	 *                       ```
	 *
	 * @return array|null Returns a multidimensional array or `null` if `$key` is invalid.
	 */
	function array_group_by(array $array, $key)
	{
		if (!is_string($key) && !is_int($key) && !is_float($key) && !is_callable($key) ) {
			trigger_error('array_group_by(): The key should be a string, an integer, or a callback', E_USER_ERROR);
			return null;
		}

		$func = (!is_string($key) && is_callable($key) ? $key : null);
		$_key = $key;

		// Load the new array, splitting by the target key
		$grouped = array();
		foreach ($array as $value) {
			$key = null;

			if (is_callable($func)) {
				$key = call_user_func($func, $value);
			} elseif (is_object($value) && property_exists($value, $_key)) {
				$key = $value->{$_key};
			} elseif (isset($value[$_key])) {
				$key = $value[$_key];
			}

			if ($key === null) {
				continue;
			}

			$grouped[$key][] = $value;
		}

		// Recursively build a nested grouping if more parameters are supplied
		// Each grouped array value is grouped according to the next sequential key
		if (func_num_args() > 2) {
			$args = func_get_args();

			foreach ($grouped as $key => $value) {
				$params = array_merge(array($value), array_slice($args, 2, func_num_args()));
				$grouped[$key] = call_user_func_array('array_group_by', $params);
			}
		}

		return $grouped;
	}


	
	


	
}
?>