// var identificador;
// var interval;
// function f5(that,val){
// 	if(val){
// 		that.on('keydown',function(e){
// 			var code = (e.keyCode ? e.keyCode : e.which);
// 		});
// 	}
// }
// $(document).ready(function(){
// 	identificador = ($("#identificacion").val() === "true");
// 	if(identificador === true){
// 		inicializaReloj();
// 	}
	// $.sessionTimeout({
	// 	warnAfter: 360000,
	// 	onWarn: function () {
	// 		if(identificador === true){
	// 			identificador = false;
	// 			ajaxRequest(raiz+'/controllers/server/identificacion/main_controller.php','accion=variableSession', 'POST', 'JSON', 1,'');
	// 		}
	// 	}
	// });
// 	$("#btnCambiarSesion").on("click", function(){
// 		let cambiarSesion = function(){
// 			modalMensaje("Éxito en el proceso", "Se ha Cambiado la Sesión a Sesión de Nuevo Usuario", "exito_solicitud_cambio_sesion", "500px", "300px");
// 		}
// 		fn_global = cambiarSesion;
//         modalFormularioSinCancelar('Acceso DAU',raiz+'/views/modules/identificacion/identificacion.php','accessRequest=finalizarSesion','#accesoPistola','40%','auto');
// 	});
// 	$("#btnCerrarSesion").on("click", function(){
// 		clearInterval(interval);
// 		identificador 	= false;
// 		perfilUsuario 	= '';
// 		const { usuarioLogueado } = ajaxRequest(raiz + '/controllers/server/identificacion/main_controller.php', 'accion=variableSession', 'POST', 'JSON', 1, 'Verificando USUARIO ...');
// 		redirigirSegunBanderaPiso();
// 		$('#logueado2').html('');
// 		$('#mensaje').html('');
// 		$('#time').html('');
// 		$('#minutos').html('');
// 		$('#imagenPerfilUsuario').html('');
// 		$('#nombre1').html(usuarioLogueado);
// 	});
// });


// var identificador;
// var interval;




// function f5(that,val){
// 	if(val){
// 		that.on('keydown',function(e){
// 			var code = (e.keyCode ? e.keyCode : e.which);
// 		});
// 	}
// }

var identificador;
var interval;
function f5(that,val){
	if(val){
		that.on('keydown',function(e){
			var code = (e.keyCode ? e.keyCode : e.which);
		});
	}
}

$(document).ready(function(){

	identificador = ($("#identificacion").val() === "true");



	if(identificador === true){
		inicializaReloj();
	}
	// $.sessionTimeout({
	// 	warnAfter: 360000,
	// 	onWarn: function () {
	// 		if(identificador === true){
	// 			identificador = false;
	// 			ajaxRequest(raiz+'/controllers/server/identificacion/main_controller.php','accion=variableSession', 'POST', 'JSON', 1,'');
	// 		}
	// 	}
	// });



	$("#btnCambiarSesion").on("click", function(){

		let cambiarSesion = function(){
			modalMensaje("Éxito en el proceso", "Se ha Cambiado la Sesión a Sesión de Nuevo Usuario", "exito_solicitud_cambio_sesion", "500px", "300px");
		}

		fn_global = cambiarSesion;

        modalFormularioSinCancelar('Acceso DAU',raiz+'/views/modules/identificacion/identificacion.php','accessRequest=finalizarSesion','#accesoPistola','40%','auto');
	});



	$("#btnCerrarSesionCamas").on("click", function(){

		clearInterval(interval);

		identificador 	= false;

		perfilUsuario 	= '';

		const { usuarioLogueado } = ajaxRequest(raiz + '/controllers/server/identificacion/main_controller.php', 'accion=variableSession', 'POST', 'JSON', 1, 'Verificando USUARIO ...');

		// redirigirSegunBanderaPiso();
		let url = `${raiz}/views/modules/gestion_hospital/gestion_hospital/contenido_servicio/gestion_servicios.php`
		let contenedor = '#contenido_gestionCama'

		ajaxContent(url, null, contenedor, 'Cargando...', true);

		document.getElementById('btnCerrarSesionCamas').type = 'hidden'



		// $('#logueado2').html('');

		//
		// $('#mensaje').html('');
		//
		// $('#time').html('');
		//
		// $('#minutos').html('');
		//
		// $('#imagenPerfilUsuario').html('');
		//
		// $('#nombre1').html(usuarioLogueado);

	});

});