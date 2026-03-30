$(document).ready(function(){
	$('#mensajeAlert').hide();
	let idDau     	  = $('#idDau').val(),
		cie10_id_temp = [],
		cie10_id      = 0,
		fila          = "",
		columnas      = "";
	validar("#frm_item","letras_numeros");
	validar("#frm_item_completo","letras_numeros");
	$(".removerCR").click(function(){	
		let codigo = $(this).attr('id').replace('cod','');
		let indice  = $.inArray(codigo, cie10_id_temp);
		cie10_id_temp.splice(indice,1);
		$("#"+codigo).remove();
	});
	$("#frm_item").autocomplete({ 
		close: function( event, ui ) {
			if ( fila == "" ) {
				$("#frm_item").val("");
			}
		},
		source: function(request, response) {	  	 
			$.ajax({
				type: "POST",
				url: raiz+"/controllers/server/consulta/main_controller.php",
				dataType: "json",
				data: {
					term : request.term,
					accion : 'busquedaSensitivaUrgencia',
				},
				success: function(data) {
					response(data)
				}
			});	  	  		  	 
		},
		minLength: 3, 
		select: function(event, ui){
			img      = "<button type='button' id='"+ui.item.id+"' class='puntero btn btn-danger removerProducto' style='float:right; float:left; margin-left:8px; float:right;'><i class='fas fa-trash '></i>";
			columnas = "<td>"+ui.item.id+"</td><td>"+ui.item.nombre+"</td><td>"+img+"</td>";
			fila     = "<tr id='"+ui.item.id+"' class='detalle'>"+columnas+"</tr>";
			cie10_id = ui.item.id;
			var t    = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
			agregarFila();
		},
		open: function(){
			$('.ui-menu').addClass("col-md-12");
			$('.ui-menu').addClass("mifuente");
			$('.ui-menu').css( "font-weight", "bold" );
		}
	});
	$("#frm_item").keypress(function(event){
	  	var t     = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
		if ( t == 13 ) {		  		
			agregarFila();
		}
	});
	$("#btnAgregarLinea").click(function(){
	  	agregarFila();
	});
	$("#btnAgregarCIE").on("click", function(){
		if ( $("#tbItem tr").length > 0 ) {
			var item_producto_final = [cie10_id_temp.length];
			var z 					= 0;
			$("#tbItem tr").each(function(element){ 
				var cie10Item =[];
				cie10Item[0]  = $(this).attr("id");					
				item_producto_final[z] = cie10Item;
				z++;
			});
		}
		if ( $("#tbItemCompleto tr").length > 0 ) {
			var item_producto_final = [cie10_id_temp2.length];
			var z=0;
			$("#tbItemCompleto tr").each(function(element){
				var cie10itemCompleto =[];
				cie10itemCompleto[0]  = $(this).attr("id");					
				item_producto_final[z] = cie10itemCompleto;;
				z++;
			});
		}
		$.validity.start();
		if ( $('input[name="radio_cie10"]').is(':checked') ) {
			if ($('#frm_cie10_urg').is(':checked')) {
				if($("#tbItem tr").length==0){
			
					$("#frm_item").assert(false,'Debe ingresar un cie10 Urgencia');
				}							
			}
			if ( $('#frm_cie10_com').is(':checked') ) {
				if($("#tbItemCompleto tr").length==0){
					$("#frm_item_completo").assert(false,'Debe ingresar un cie10 Completo');
				}							
			}
		}	 
		result = $.validity.end();
		if ( result.valid == false ) {
			return false;
		}
		item_producto_final   = JSON.stringify(item_producto_final);
		let funcion = function miFuncion(){  
			let grabar = function(response){
				switch(response.status){						
					case "success":				
						$('#registroMedico').modal( 'hide' ).data( 'bs.modal', null ); 	
					break;
					case "error":
						texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en el proceso </h4>  <hr>  <p class="mb-0">Error en la transacción, no pudo realizar el registro medico<br><br>'+response.message+'</p></div>';
            			modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
					break;
					default:        
                        ErrorSistemaDefecto();
					break;
				}
			}
			let response =ajaxRequest(raiz+'/controllers/server/consulta/main_controller.php',$("#frm_registro_medico").serialize()+'&Iddau='+idDau+'&item_producto_final='+item_producto_final+'&accion=registroMedicoCie'+'&dau_mov_descripcion=registroMedico', 'POST', 'JSON', 1,'Registrar CIE10...', grabar);
		}
		modalConfirmacionNuevo("Advertencia", "ATENCIÓN, se procedera a Registrar el CIE10, <b>¿Desea continuar?</b>", "primary", funcion);
	})
	function agregarFila ( ) {
		if ( cie10_id != 0 && $.inArray(cie10_id, cie10_id_temp) == -1 && $("#tbItem tr").length == 0 ) {
			$(fila).hide().appendTo("#tbItem").fadeIn("");
			cie10_id_temp[cie10_id_temp.length] = cie10_id;
			if ( $("#tbItem tr").length > 0 ) {
				var item_producto_final = [cie10_id_temp.length];
				var z = 0;						
			}
			$(".removerProducto").off();					
			$(".removerProducto").click(function(){
				var principal = $(this).attr("id").replace('cod','');						
				var codigo = $(this).attr('id').replace('cod','');
				$("#"+codigo).remove();
				var indice  = $.inArray(codigo, cie10_id_temp);
				cie10_id_temp.splice(indice,1);
			});
			$("#frm_item").val("");
			$("#frm_item").focus();
			$(this).val("");
			fila  = "";
			$.validity.start();
			cie10_id=0;
		} else if ( fila == "" ) {
			$.validity.start();
			$('#frm_item').match('date',"Ingrese un Cie10 Valido");
			var result = $.validity.end();
			result.valid=false;
			fila  = "";
			$("#frm_item").val("");
		} else {
			$.validity.start();
			$('#frm_item').match('date',"Ya se ingreso un CIE10");
			var result = $.validity.end();
			result.valid=false;
			fila  = "";
			$("#frm_item").val("");
		}
	}
 });