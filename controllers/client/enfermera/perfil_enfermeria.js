$(document).ready(function(){
	let dau_id              = $('#dau_id').val(),
	tipoMapa 				= $('#tipoMapa').val(),
    rce_id                  = $('#rce_id').val(),
    idPaciente              = $('#id_paciente').val(),
    tipoAtencion            = $("#tipoAtencion").val(),
    idctacte            	= $("#idctacte").val(),
    estadoDau            	= $("#estadoDau").val(),
    actualizarSeguimiento   = false;
	banderapiso             = 'RCE';
	cd                      = 1;
	async function cargarSeccionPaciente(dau_id, rce_id,idPaciente) {
		// let c = await ajaxContentFast('/RCEDAU/views/modules/rce/medico/diagnostico.php','dau_id='+dau_id+'&rce_id='+rce_id+'&idPaciente='+idPaciente+'&idctacte='+idctacte,'#div_diagnostico');
        // if( $('#estadoDau').val() != 8 ){

        // }
		// let d =  ajaxContentFast('/RCEDAU/views/modules/rce/medico/bitacora.php','dau_id='+dau_id+'&rce_id='+rce_id+'&idPaciente='+idPaciente,'#div_bitacora');

		// let e =  ajaxContent('/RCEDAU/views/modules/rce/medico/indicacion.php','dau_id='+dau_id+'&rce_id='+rce_id+'&idPaciente='+idPaciente,'#div_indicacion');
		// if($('#inicioAtencion').val() == 0){

			// let f = await ajaxContentFast('/RCEDAU/views/modules/rce/rce/inicioAtencion.php','dau_id='+dau_id+'&rce_id='+rce_id+'&idPaciente='+idPaciente+'&rce=1','#div_inicioAtencion');
		// }else{
            let a =  ajaxContentFast('/RCEDAU/views/modules/rce/medico/bitacora.php','dau_id='+dau_id+'&rce_id='+rce_id+'&idPaciente='+idPaciente+'&enfermeria=1','#div_bitacora');
            // let b =  ajaxContentFast('/RCEDAU/views/modules/enfermera/hoja_enfermeria.php','dau_id='+dau_id+'&tipoMapa='+tipoMapa+'&regId='+rce_id+'&banderaDetalleDau=1','#div_indicacion_enfermeria');
            let c =  ajaxContentFast('/RCEDAU/views/modules/enfermera/despliegueIndicaciones.php','dau_id='+dau_id+'&tipoMapa='+tipoMapa+'&regId='+rce_id+'&banderaDetalleDau=1','#div_indicacion');
        // }
	}
    $(".verHojaHospitalizacion").on("click", function(){
        var idHojaHospitalizacion = $(this).attr("id"); 
        let imprimir = function(){
            $('#pdfHojaHospitalizacion').get(0).contentWindow.focus();
            $("#pdfHojaHospitalizacion").get(0).contentWindow.print();
        }
        let botones =   [
            { id: 'btnImprimir', value: '<i class="glyphicon glyphicon-print" aria-hidden="true"></i> Imprimir', function: imprimir, class: 'btn btn-primary btnPrint' }
        ]
        modalFormulario("<label class='mifuente ml-2'>Hoja Hospitalización DAU N°"+dau_id+"</label>", `${raiz}/views/modules/rce/rce/pdfHojaHospitalizacion.php`, `idDau=${dau_id}&idRCE=${rce_id}&idHojaHospitalizacion=${idHojaHospitalizacion}`, "#modalAltaUrgencia", "modal-lg", "light",'', botones);
    
    })
    $("#abrirPanelMedica").click(function(){
        ajaxContentFast('/RCEDAU/views/modules/enfermera/despliegueIndicaciones.php','dau_id='+dau_id+'&tipoMapa='+tipoMapa+'&regId='+rce_id+'&banderaDetalleDau=1','#div_indicacion');  
    });
    $("#abrirPanelEnfermeria").click(function(){
        ajaxContentFast('/RCEDAU/views/modules/enfermera/despliegueIndicacionesEnfermeria.php','dau_id='+dau_id+'&tipoMapa='+tipoMapa+'&regId='+rce_id+'&banderaDetalleDau=1','#div_indicacion');  
    });
	cargarSeccionPaciente(dau_id, rce_id,idPaciente);

    $(".IndicacionesEnfermeria").click(function(){
        
        modalFormulario_noCabecera('', raiz+"/views/modules/enfermera/procedimientos_enfermeria.php", `dau_id=${dau_id}&estadoDau=${estadoDau}`, "#modalIndicacionesEnfermeria", "modal-lgg", "", "fas fa-plus",'');
    });

    $(".formulariosEnfermeria").click(function(){
        modalFormulario_noCabecera('', raiz+"/views/modules/formularios/contenido_formularios.php", `dau_id=${dau_id}`, "#modalFormulariosEnfermeria", "modal-lgg", "", "fas fa-plus",'');
    });

    $(".HojaEnfermeria").on("click", function(){

        let botones =   [
                            {
                                id      : 'btnGuardarHojaEnfermeria',
                                value   : '<i class="glyphicon glyphicon-print" aria-hidden="true"></i> Imprimir',
                                class   : 'btn btn-primary btnPrint'
                            }

                        ]
        modalFormulario("<label class='mifuente ml-2'>Hoja Ingreso Enfermeria</label>", raiz+'/views/modules/enfermera/hoja_enfermeria.php', 'dau_id='+dau_id+'&paciente_id='+idPaciente+'&tipoMapa='+tipoMapa, "#hoja_enfermeria", "modal-lg", "light",'', botones);
    })

	$("#btn_historial_link").click(function(){

		modalFormulario('<label class="mifuente text-primary">Historial Clinico</label>',raiz+"/views/modules/rce/rce/historial_clinico.php",`paciente_id=${idPaciente}`,'#modal_historial','modal-lg','', 'fas fa-laptop-medical text-primary','');
	});
    $("#btn_historial_link2").click(function(){

        modalFormulario('<label class="mifuente text-primary">Historial Clinico</label>',raiz+"/views/modules/rce/rce/historial_clinico2.php",`paciente_id=${idPaciente}`,'#modal_historial','modal-lg','', 'fas fa-laptop-medical text-primary','');
    });
	$(".volverWorklist_detalle").click(function(){
        if ( perfilUsuario == 'full' ) {
            ajaxContentSlideLeft(raiz+'/views/modules/mapa_piso_full/detalle_dau/detalle_dau.php', 'dau_id='+dau_id+'&tipoMapa='+tipoMapa+'&banderapiso'+banderapiso+'&perfilUsuario'+perfilUsuario, '#contenido');
        }else{
        	ajaxContentSlideLeft(raiz+localStorage.getItem('urlAtras'),localStorage.getItem('parametrosAtras'), '#contenido');
        }
    });
    $(".btnDau").click(function(){

		modalFormulario_noCabecera('', raiz+"/views/modules/rce/rce/dau_detalle.php", 'dau_id='+dau_id+'&btn=N', "#modalDetalleCategorizacion", "modal-lg", "", "fas fa-plus");
	});
    $(".SignoVitales").click(function(){
        
        modalFormulario_noCabecera('', raiz+"/views/modules/rce/signos_vitales/signos_vitales.php", `dau_id=${dau_id}&estadoDau=${estadoDau}&tipoMapa=${tipoMapa}`, "#modalSignosVitales", "modal-lgg", "", "fas fa-plus",'');
    });
    $(".verInformacionAplicaEgreso").click(function(){
        if ( perfilUsuario === 'administrativo' ) {
            return;
        }
        modalFormulario_noCabecera('', raiz+"/views/modules/rce/rce/modalInformacionAplicarEgreso.php", `&dau_id=${dau_id}&tipoMapa=${tipoMapa}`, "#modalInformacionAplicarEgreso", "modal-lg", "", "fas fa-plus",'');
    });
    $(".verindicacionaplica").click(function(){
        if ( perfilUsuario === 'administrativo' ) {
            return;
        }
        modalFormulario_noCabecera('', raiz+"/views/modules/rce/rce/modalIndicacionAplica.php", $("#frmIndicacionAplica").serialize()+`&dau_id=${dau_id}&tipoMapa=${tipoMapa}`, "#modalmodalIndicacionAplica", "modal-lg", "", "fas fa-plus",'');
    });	
    $(".aplicarNEA").click(function(){

        modalFormulario_noCabecera('', raiz+"/views/modules/rce/nea/modalAplicarNEA.php", 'dau_id='+dau_id+'&tipoMapa='+tipoMapa, "#modalNEA", "modal-md", "", "fas fa-plus",'');
    });
    $(".verIngresarLPP").on("click", function() {

        const botones = (!pacienteEgresado())
            ? [{
                    id: "btnIngresarLPP",
                    value: 'Ingresar LPP',
                    class: "btn btn-primary"
                }]
            : [];
        
        modalFormulario("<label class='mifuente'>Documento Detalle DAU  </label>",raiz+'/views/modules/mapa_piso_full/detalle_dau/lpp.php',`idDau=${dau_id}`,'#LPP',"modal-md","primary","fas fa-folder-plus",botones);
    });
    $('.verDetalleDau').on('click', function(){
        
        modalFormulario_noCabecera('', raiz+"/views/modules/rce/rce/ver_detalleDau.php", 'idDau='+dau_id, "#ver_detalleDau", "modal-lg", "", "fas fa-plus");

    });
     $('#btnVerRCEIncompleto').on('click', function(){
        // alert()
        let banderaImpresionRCECompleto = true;
        let estadoDau = $('#estadoDau').val();
        let tituloModal = "RCE";
        modalFormulario("<label class='mifuente ml-2'>"+tituloModal+"</label>", raiz+"/views/modules/rce/rce/ver_rce.php", 'idPaciente='+idPaciente+'&dau_id='+dau_id, "#detalle_rce_pdf", "modal-lg", "primary",'', '');


        // modalFormulario_noCabecera('', raiz+"/views/modules/rce/rce/ver_rce.php", "rce_id="+rce_id+'&dau_id='+dau_id+'&banderaLlamada=altaUrgenciaIncompleto', "#ver_detalleRCE", "modal-lg", "", "fas fa-plus");
    });

    function pacienteEgresado ( ) {
        const parametros           =  {idDau : dau_id, accion : 'pacienteEgresado'};
        const respuestaAjaxRequest = ajaxRequest(raiz+'/controllers/server/medico/main_controller.php', parametros, 'POST', 'JSON', 1);
        if ( respuestaAjaxRequest.status == 'success' ) {
            return true;
        }
        return false;
    }
	// $("#btnAgregarIndicaciones").click(async function () {
	// 	if ( perfilUsuario === 'administrativo') {
 //            return;
 //        }
 //        ajaxRequest(`${raiz}/controllers/server/rce/indicaciones/main_controller.php`, 'accion=DestruirSesionSecciones', 'POST', 'JSON', 1);
 //        let botones =   [
 //                            { id: 'btn_Agregar_Examenes', value: 'Guardar', class: 'btn btn-primary' , function: guardarIndicaciones}
 //                        ];

 //        modalFormulario("<label class='mifuente ml-2'>Añadir indicaciones</label>", `${raiz}/views/modules/rce/indicaciones/cargar_indicaciones_modal.php`, `dau_id=${dau_id}&rce_id=${rce_id}&banderaRCE=1&tipoMapa=${tipoMapa}`, "#agregarExamen", "modal-lg", "light",'', botones);
	// });
	
});