'use strict';

$(document).ready(function(){
    const categorizacionSDD = ( function categorizacionSDD ( ) {
        const   divViajeEpidemiologico       = "#divViajeEpidemiologico",
                divPaisEpidemiologia         = "#divPaisEpidemiologia",
                divObservacionEpidemiologica = "#divObservacionesEpidemiologia",
                viajeOProcedencia            = $("#viajeOProcedencia").val(),
                pais                         = $("#pais").val(),
                observaciones                = $("#observacion").val(),
		        $viajeOProcedenciaExtranjero = $("#frm_viajeEpidemiologico"),
		        $paisEpidemiologia   		 = $("#frm_paisEpidemiologia"),
		        $observacionEpidemiologica   = $("#frm_observacionEpidemiologica");
        //Variables
        let idDau = $('#idDau').val(),
            banderacat = 'MPISOGO'
        //Funciones privadas
        function _formateoTemperatura ( ) {
            let temp = $(this).val();
            if ( temp.length == 2 ) {
                $(this).val($(this).val()+".0")
            }
        }
        function _formateoTemperaturaRectal ( ) {
            let temp = $(this).val();
            if ( temp.length == 2 ) {
                $(this).val($(this).val()+".0")
            }
        }
        async function _categorizarSDD ( ) {
            if ( ! _verificarDatos() ) {
                return;
            }
            const estadoPermiso = await validarPermisoUsuario('btn_categorizacionSDD_Gine');
            if (estadoPermiso) {
                _confirmarCategorizarSDD();
            }
            // if ( ! _verificarDatos() ) {
            //     return;
            // }
            // usuario.inicializarPermisosUsuario( _confirmarCategorizarSDD, 'btn_categorizacionSDD_Gine', 'validarAccion', 1);
            // modalConfirmacion("Confirmación de Categorización", "¿Está seguro que desea categorizar a este paciente?", usuario.verificarPermisoUsuario);
        }
        function _imprimirVoucherNumeroAtencion ( ) {
            let imprimir = function () {
                $("#iframeNumeroAtencionVoucherTermico").get(0).contentWindow.focus();
                $("#iframeNumeroAtencionVoucherTermico").get(0).contentWindow.print();
            }
            let botones = 	[
                                { id: 'btnImprimir', value: '<i class="glyphicon glyphicon-print" aria-hidden="true"></i> Imprimir', function: imprimir, class: 'btn btn-primary btnPrint' }
                            ]
            modalFormulario('Número Atención DAU', `${raiz}/views/modules/categorizacion/numeroAtencionVoucherTermico.php`, `idDau=${idDau}`, '#detalleAdmisionVoucherTermico', '66%', '100%', botones);
        }
        function _verificarDatos ( ) {
            $.validity.start();
            if ( $('#e4categ option:selected').val() == "" ) {
                $('#e4categ').assert(false,'Debe Seleccionar Tipo de Categorización');
            }
            if ( $viajeOProcedenciaExtranjero.val() === null || $viajeOProcedenciaExtranjero.val() === undefined || String($viajeOProcedenciaExtranjero.val()) === "" ) {
				$("#frm_viajeEpidemiologico").assert(false,'Seleccione Opción');
			}
			if ( String($viajeOProcedenciaExtranjero.val()) === "S" ) {
				if ( $paisEpidemiologia.val() === null || $paisEpidemiologia.val() === undefined || String($paisEpidemiologia.val()) === "" ) {
					$("#frm_paisEpidemiologia").assert(false,'Seleccione País');
				}
			}
            let result = $.validity.end();
            return result.valid;
        }
        function _confirmarCategorizarSDD ( ) {
            var respuestaAjax = ajaxRequest(`${raiz}/controllers/server/categorizacion/main_controller.php`, 'accion=pacienteYaCategorizadoSSD&idDau='+$('#idDau').val(), 'POST', 'JSON', 1, '¿Paciente Ya Categorizado?...');
            if(respuestaAjax.status == "success"){
                texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en categorizar al paciente </h4>  <hr>  <p class="mb-0">Paciente ya se encuentra categorizado por otra persona. Se recargó nuevamente el Mapa de Piso para que visualice los cambios.</p></div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                $('#categorizarPaciente').modal( 'hide' ).data( 'bs.modal', null );
                    $('#modalDetalleCategorizacion').modal('hide').data('bs.modal', null);
                ajaxContent(`${raiz}/views/modules/mapa_piso_full/mapa_piso_full.php`,'tipoMapa=mapaGinecologico','#contenido','', true);
                return;
            }
            var respuestaAjaxRequest = ajaxRequest(raiz+'/controllers/server/categorizacion/main_controller.php', $('#frm_triageSDD').serialize()+'&accion=SDD&dau_id='+idDau+'&banderacat='+banderacat, 'POST', 'JSON', 1, 'Consultando ....');
            switch(respuestaAjaxRequest.status){
                case 'success':
                    $('#categorizarPaciente').modal('hide').data('bs.modal', null);
                    $('#modalDetalleCategorizacion').modal('hide').data('bs.modal', null);
                    // ajaxRequest(`${raiz}/controllers/server/rce/historial_clinico/main_controller.php`, `dau_id=${idDau}&accion=eventoRCE`, 'POST', 'JSON', 1, 'Cargando');

                    ajaxContent(`${raiz}/views/modules/mapa_piso_full/mapa_piso_full.php`,'tipoMapa=mapaGinecologico','#contenido','', true);
                    // redirigirSegunBanderaPiso(banderapiso);
                    // if ( $("#e4categ").val() != "C1" ) {
                    //     _imprimirVoucherNumeroAtencion();
                    // }
                break;
                case 'error' :
                    texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error </h4>  <hr>  <p class="mb-0">Error en aplicar categorización al paciente:<br><br>'+respuestaAjaxRequest.message+'.</p></div>';
                    modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                break;
            }
        }
        //Funciones públicas
        function validarCamposFormulario ( ) {
            validar("#e4txtFR_1","numero");
            validar("#e4txtFR_2","numero");
            validar("#e4txtSat","numero");
            validar("#e4txtFR","numero");
            validar("#e4txtFC","numero");
            validar("#e4txtTe","numero_punto");
            validar("#e4txtTe_rec","numero_punto");
        }
        function formateoTemperatura ( ) {
            $("input[name='dau_cat_4_temp']").on('blur', _formateoTemperatura);
        }
        function formateoTemperaturaRectal ( ) {
            $("input[name='dau_cat_4_temp_rec']").on('blur', _formateoTemperaturaRectal);
        }
        function categorizarSDD ( ) {
            $('#btnCategorizarSDD').on('click', _categorizarSDD);
        }
        function cambioSelectViajeOProcedencia ( ) {
            if ( viajeOProcedencia === null || String(viajeOProcedencia) === "" || String(viajeOProcedencia) === "N" ) {
                $paisEpidemiologia.val("");
			    $observacionEpidemiologica.val("");
            }
			if ( String($viajeOProcedenciaExtranjero.val()) === "S" ) {
				$(`${divPaisEpidemiologia}`).show(100);
				$(`${divObservacionEpidemiologica}`).show(100);
                $(`${divViajeEpidemiologico}`).removeClass("col-md-12");
				$(`${divViajeEpidemiologico}`).addClass("col-md-4");
				return;
			}
			$(`${divPaisEpidemiologia}`).hide(100);
			$(`${divObservacionEpidemiologica}`).hide(100);
            $(`${divViajeEpidemiologico}`).removeClass("col-md-4");
			$(`${divViajeEpidemiologico}`).addClass("col-md-12");
        }
        function epidemiologia ( ) {
		    validar("#frm_observacionesEpidemiologia", "letras_numero");
            $viajeOProcedenciaExtranjero.val((viajeOProcedencia == null || viajeOProcedencia == undefined || viajeOProcedencia == "") ? "" : viajeOProcedencia);
            $paisEpidemiologia.val((pais == null || pais == undefined || pais == "") ? "" : pais);
            $observacionEpidemiologica.val((observaciones == null || observaciones == undefined || observaciones == "") ? "" : observaciones);
		    $viajeOProcedenciaExtranjero.on("change", cambioSelectViajeOProcedencia);
        }
        return {
            validarCamposFormulario      : validarCamposFormulario,
            formateoTemperatura          : formateoTemperatura,
            formateoTemperaturaRectal    : formateoTemperaturaRectal,
            categorizarSDD               : categorizarSDD,
            epidemiologia                : epidemiologia,
            cambioSelectViajeOProcedencia: cambioSelectViajeOProcedencia
        }
    })();
    //Validaciones
    categorizacionSDD.validarCamposFormulario();
    //Epidemiología
    categorizacionSDD.epidemiologia();
    //Formateo campos
    categorizacionSDD.formateoTemperatura();
    categorizacionSDD.formateoTemperaturaRectal();
    //Cambio en select viaje o procedencia
    categorizacionSDD.cambioSelectViajeOProcedencia();
    //Categorizar SSD
    categorizacionSDD.categorizarSDD();
});