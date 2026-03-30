$(document).ready(function(){
	pin 		= localStorage.getItem('pin');
	usuario 	= localStorage.getItem('usuario');
	$('#pin1ok').hide();
	$('#pin2ok').hide();
	$('#pin3ok').hide();
	$('#pin4ok').hide();
	$('#pin5ok').hide();
	$('#pin6ok').hide();
	$('#intento2').hide();
	$('#intento3').hide();
	if(pin==" " || pin=="" || pin=='null' || pin==null){
		$('#menu').show();
		$('#pin').hide();
	}else{
		$('#menu').hide();
		$('#pin').show();
	}
	$("#abrirMenu").click(function(){
		$('#menu').show();
		$('#pin').hide();
	});
	function corroborarPin(){

		nuevoPin = $('#nuevoPin').val();
        if(pin==nuevoPin){
        	var response = ajaxRequest('../accesomovil/controllers/server/acceso/main_controller.php','accion=BuscarSession&usuario='+usuario, 'POST', 'JSON', 1);
    		switch(response.status){
	           case "success": 	localStorage.setItem('pin', nuevoPin);
	           					location.reload();
	                			break;
	            default:        modalMensaje("<label class='mifuente'>Error generico</label>", "<label class='mifuente'>"+response+"</label>", "#modal", "", "danger");
	            				location.reload();
	                            break;
	        }
        }else if (codigo==7 & pin!=nuevoPin){
        	$('#pin1ok').hide();
			$('#pin2ok').hide();
			$('#pin3ok').hide();
			$('#pin4ok').hide();
			$('#pin5ok').hide();
			$('#pin6ok').hide();
			$('#pin1').show();
			$('#pin2').show();
			$('#pin3').show();
			$('#pin4').show();
			$('#pin5').show();
			$('#pin6').show();
			$('#codigo').val("1");
			$('#nuevoPin').val("");
			$('#intento2').show();
			$('#intento').hide();
        }
	}
	$("#1").click(function(){
		codigo 		= $('#codigo').val();
		nuevoPin 	= $('#nuevoPin').val();
		$('#pin'+codigo+'ok').show();
		$('#pin'+codigo+'').hide();
		codigo++;
		$('#codigo').val(codigo);
		nuevoPin = nuevoPin+ "1";
		$('#nuevoPin').val(nuevoPin);
		$(this).addClass('disabled');
        $(this).removeClass('active');
        corroborarPin();
	});
	$("#2").click(function(){
		codigo 		= $('#codigo').val();
		nuevoPin 	= $('#nuevoPin').val();
		$('#pin'+codigo+'ok').show();
		$('#pin'+codigo+'').hide();
		codigo++;
		$('#codigo').val(codigo);
		nuevoPin = nuevoPin+ "2";
		$('#nuevoPin').val(nuevoPin);
		$(this).addClass('disabled');
        $(this).removeClass('active');
        corroborarPin();
	});
	$("#3").click(function(){
		codigo 		= $('#codigo').val();
		nuevoPin 	= $('#nuevoPin').val();
		$('#pin'+codigo+'ok').show();
		$('#pin'+codigo+'').hide();
		codigo++;
		$('#codigo').val(codigo);
		nuevoPin = nuevoPin+ "3";
		$('#nuevoPin').val(nuevoPin);
		$(this).addClass('disabled');
        $(this).removeClass('active');
        corroborarPin();
	});
	$("#4").click(function(){
		codigo 		= $('#codigo').val();
		nuevoPin 	= $('#nuevoPin').val();
		$('#pin'+codigo+'ok').show();
		$('#pin'+codigo+'').hide();
		codigo++;
		$('#codigo').val(codigo);
		nuevoPin = nuevoPin+ "4";
		$('#nuevoPin').val(nuevoPin);
		$(this).addClass('disabled');
        $(this).removeClass('active');
        corroborarPin();
	});
	$("#5").click(function(){
		codigo 		= $('#codigo').val();
		nuevoPin 	= $('#nuevoPin').val();
		$('#pin'+codigo+'ok').show();
		$('#pin'+codigo+'').hide();
		codigo++;
		$('#codigo').val(codigo);
		nuevoPin = nuevoPin+ "5";
		$('#nuevoPin').val(nuevoPin);
		$(this).addClass('disabled');
        $(this).removeClass('active');
        corroborarPin();
	});
	$("#6").click(function(){
		codigo 		= $('#codigo').val();
		nuevoPin 	= $('#nuevoPin').val();
		$('#pin'+codigo+'ok').show();
		$('#pin'+codigo+'').hide();
		codigo++;
		$('#codigo').val(codigo);
		nuevoPin = nuevoPin+ "6";
		$('#nuevoPin').val(nuevoPin);
		$(this).addClass('disabled');
        $(this).removeClass('active');
        corroborarPin();
	});
	$("#7").click(function(){
		codigo 		= $('#codigo').val();
		nuevoPin 	= $('#nuevoPin').val();
		$('#pin'+codigo+'ok').show();
		$('#pin'+codigo+'').hide();
		codigo++;
		$('#codigo').val(codigo);
		nuevoPin = nuevoPin+ "7";
		$('#nuevoPin').val(nuevoPin);
		$(this).addClass('disabled');
        $(this).removeClass('active');
        corroborarPin();
	});
	$("#8").click(function(){
		codigo 		= $('#codigo').val();
		nuevoPin 	= $('#nuevoPin').val();
		$('#pin'+codigo+'ok').show();
		$('#pin'+codigo+'').hide();
		codigo++;
		$('#codigo').val(codigo);
		nuevoPin = nuevoPin+ "8";
		$('#nuevoPin').val(nuevoPin);
		$(this).addClass('disabled');
        $(this).removeClass('active');
        corroborarPin();
	});
	$("#9").click(function(){
		codigo 		= $('#codigo').val();
		nuevoPin 	= $('#nuevoPin').val();
		$('#pin'+codigo+'ok').show();
		$('#pin'+codigo+'').hide();
		codigo++;
		$('#codigo').val(codigo);
		nuevoPin = nuevoPin+ "9";
		$('#nuevoPin').val(nuevoPin);
		$(this).addClass('disabled');
        $(this).removeClass('active');
        corroborarPin();
	});
	$("#0").click(function(){
		codigo 		= $('#codigo').val();
		nuevoPin 	= $('#nuevoPin').val();
		$('#pin'+codigo+'ok').show();
		$('#pin'+codigo+'').hide();
		codigo++;
		$('#codigo').val(codigo);
		nuevoPin = nuevoPin+ "0";
		$('#nuevoPin').val(nuevoPin);
		$(this).addClass('disabled');
        $(this).removeClass('active');
        corroborarPin();
	});
	$("#borrar").click(function(){
		codigo 			= $('#codigo').val();
		codigo2 		= $('#codigo2').val();
		if(codigo!=1 && codigo!=7){
			nuevoPin 	= $('#nuevoPin').val();
			codigo--;
			$('#pin'+codigo+'ok').hide();
			$('#pin'+codigo+'').show();
			$('#codigo').val(codigo);
			nuevoPin= nuevoPin.substring(0,nuevoPin.length-1);
			$('#nuevoPin').val(nuevoPin);
	        $(this).addClass('disabled');
	        $(this),removeClass('active');
	    }
	});

	// validar("#run", "rut");
	// $("#iniciarSesion").click(function(){
 //        var response = ajaxRequest(raiz+'/controllers/server/acceso/main_controller.php',$("#frm_sesion").serialize()+'&accion=iniciarSesion', 'POST', 'JSON', 1);
 //        switch(response.status){
 //            case "success": localStorage.setItem('pin', response.pin);	
 //            				localStorage.setItem('usuario', response.usuario);
 //            				location.href =raiz+'/index.php';
 //                			break;
 //            case "info":    modalMensaje("<label class='mifuente'>Inicio de Sesión</label>", "<label class='mifuente'>Usuario o contraseña incorrectos, por favor, vuelve a intentar.</label>", "#modal", "", "warning");
 //                            $('#password').val("");
 //                            $('#run').val("");
 //                            break;
 //            case "error":   modalMensaje("<label class='mifuente'>Error en el proceso</label>", "<label class='mifuente'>Error en la transacción, no se logró completar el proceso, el siguiente error de sistema se ha desplegado:<br><br>"+response.message+"</label>", "#modal", "", "danger");
 //                            break;
 //            default:        modalMensaje("<label class='mifuente'>Error generico</label>", "<label class='mifuente'>"+response+"</label>", "#modal", "", "danger");
 //                            break;
 //        }
 //    });
});