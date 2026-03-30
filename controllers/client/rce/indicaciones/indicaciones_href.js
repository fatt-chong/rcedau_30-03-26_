$(document).ready(function(){

    let dau_id = $('#dau_id').val();
    let rce_id = $('#rce_id').val();

    ajaxContentFast(raiz+'/views/modules/rce/indicaciones/imagenologia.php','dau_id='+dau_id,'#div_Imagenologia','', true);
    ajaxContentFast(raiz+'/views/modules/rce/indicaciones/tratamientoNuevo.php','dau_id='+dau_id,'#div_Tratamiento','', true);
    ajaxContentFast(raiz+'/views/modules/rce/indicaciones/laboratorio.php','dau_id='+dau_id,'#div_Laboratorio','', true);
    ajaxContentFast(raiz+'/views/modules/rce/indicaciones/tratamiento.php','dau_id='+dau_id,'#div_Procedimiento','', true);
    ajaxContentFast(raiz+'/views/modules/rce/indicaciones/otros.php','dau_id='+dau_id,'#div_Otros','', true);

    $('[data-toggle="tab"]').on('click', function(){
	    let $this  = $(this),
	        source = $this.attr('href'),
	        pane   = $this.attr('data-target');
	    if($(pane).is(':empty')) {
	      $.get(source, function(data) {
	          $(pane).html(data);
	      });
	      $(this).tab('show');
	      return false;
	    }
    });
    $('#btn_tipoPaciente').click(async function () {
        const estadoPermiso = await validarPermisoUsuario('btn_rce_guardar');
        if (estadoPermiso) {
            let botones =   [
			                { id: 'btnCambiarTipoPaciente', value: 'Cambiar Tipo Paciente', class: 'btn btn-danger', function: pacienteComplejo }
                        ];
            modalFormulario("<label class='mifuente ml-2'>Advertencia Cambio Tipo Paciente</label>", `${raiz}/views/modules/rce/indicaciones/advertencia_paciente_complejo.php`, '', "#advertenciaPacienteComplejo", "modal-lg", "light",'', botones);
        }
    });
    $('#modalCrearPlantillaIndicaciones').click(async function () {
        const estadoPermiso = await validarPermisoUsuario('btn_anular_indicaciones');
        if (estadoPermiso) {
            const botones =   [
                                { id: 'crearPlantillaIndicaciones', value: 'Crear', function: crearPlantillaIndicaciones, class: 'btn btn-primary' }
                              ];
            modalFormulario("<label class='mifuente ml-2'>Crear Plantilla Indicaciones</label>", `${raiz}/views/modules/rce/plantillas/modalNombrePlantilla.php`, 'rce=S', "#modalNombrePlantilla", "modal-lg", "light",'', botones);
        }
    });
    $('#modalEliminarPlantillaIndicaciones').click(async function () {

        rescatarDatosIndicaciones();
        const respuestaAjaxRequest = ajaxRequest(raiz+'/controllers/server/rce/indicaciones/main_controller.php',$('#frm_contenido_indicaciones').serialize()+'&'+$('#frm_des_ima').serialize()+'&nombrePlantilla='+$('#slc_nombrePlantilla option:selected').text()+'&accion=EliminarPlantillaIndicaciones', 'POST', 'JSON', 1);
        switch(respuestaAjaxRequest.status) {
            case "success":
                ajaxContent(raiz+'/views/modules/rce/indicaciones/indicaciones_modal.php',`dau_id=${dau_id}&rce_id=${rce_id}`, '#cargarIndicacionesModal', '', true);
                texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-check-double throb2 text-success" style="font-size:29px"></i> Plantilla Eliminada </h4>  <hr>  <p class="mb-0">Plantilla Eliminada satisfactoriamente.</p></div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                $('#modalNombrePlantilla').modal( 'hide' ).data( 'bs.modal', null );
            break;
            default:
                ErrorSistemaDefecto();
                $('#modalNombrePlantilla').modal( 'hide' ).data( 'bs.modal', null );
            break;
        }
    });
    $('#modalactualizarPlantillaIndicaciones').click(async function () {

        rescatarDatosIndicaciones();
        const respuestaAjaxRequest = ajaxRequest(raiz+'/controllers/server/rce/indicaciones/main_controller.php',$('#frm_contenido_indicaciones').serialize()+'&'+$('#frm_des_ima').serialize()+'&nombrePlantilla='+$('#slc_nombrePlantilla option:selected').text()+'&accion=ActualizarPlantillaIndicaciones', 'POST', 'JSON', 1);
        switch(respuestaAjaxRequest.status) {
            case "success":
                

                texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-check-double throb2 text-success" style="font-size:29px"></i> Plantilla Actualizada </h4>  <hr>  <p class="mb-0">Plantilla actualizada satisfactoriamente.</p></div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                $('#modalNombrePlantilla').modal( 'hide' ).data( 'bs.modal', null );
            break;
            default:
                ErrorSistemaDefecto();
                $('#modalNombrePlantilla').modal( 'hide' ).data( 'bs.modal', null );
            break;
        }
    });
    function crearPlantillaIndicaciones ( ) {
        if ( ! verificarDatosIndicaciones() ) {
            return;
        }
        if ( ! verificacionNombrePlantillaIndicaciones() ) {
            return;
        }
        rescatarDatosIndicaciones();
        const respuestaAjaxRequest = ajaxRequest(raiz+'/controllers/server/rce/indicaciones/main_controller.php',$('#frm_contenido_indicaciones').serialize()+'&'+$('#frm_des_ima').serialize()+'&nombrePlantilla='+$('#input_nombrePlantilla').val()+'&accion=crearPlantillaIndicaciones', 'POST', 'JSON', 1);
        switch(respuestaAjaxRequest.status) {
            case "success":
                $('#slc_nombrePlantilla').append($('<option>', {
                    value: respuestaAjaxRequest.idPlantilla,
                    text: $('#input_nombrePlantilla').val()
                }));
                $(`select option[value='${respuestaAjaxRequest.idPlantilla}']`).attr("selected","selected");

                texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-check-double throb2 text-success" style="font-size:29px"></i> Plantilla Creada </h4>  <hr>  <p class="mb-0">Plantilla creada satisfactoriamente.</p></div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                $('#modalNombrePlantilla').modal( 'hide' ).data( 'bs.modal', null );
            break;
            default:
                ErrorSistemaDefecto();
                $('#modalNombrePlantilla').modal( 'hide' ).data( 'bs.modal', null );
            break;
        }
    }
    function rescatarDatosIndicaciones ( ) {
        let arrLab  = [], arrLab2 = [], arrIma = [], arrOtr = [], arrTra = [], arrTraNuevo = [];
        $("#contenidoRayo tr").each(function(element){
            let imagenologiaA = [8];
            imagenologiaA[0] = $(this).find("td.ima_valorExamen").text().trim();
            imagenologiaA[1] = $(this).find("td.ima_valorTipoExamen").text().trim();
            imagenologiaA[2] = $(this).find("td.ima_valorLateralidad").text().trim();
            imagenologiaA[3] = $(this).find("td.ima_valorContrastes").text().trim();
            imagenologiaA[4] = $(this).find("td.ima_valorObservacion").text().trim();
            imagenologiaA[5] = $(this).find("td.ima_valorIdPrestacion").text().trim();
            imagenologiaA[6] = $(this).find("td.ima_valorPrestaciones").text().trim();
            imagenologiaA[7] = $(this).find("td.ima_valorParteCuerpo").text().trim();
            arrIma.push(imagenologiaA);
        });
        arrIma = arrIma.filter(
            function(a){return !this[a] ? this[a] = true : false;}, {}
        );
        arrIma = JSON.stringify(arrIma);
        $('#carroIma').val(arrIma);
        $("#contenidoTratamiento tr").each(function(element){
            let tratamientoA    = [2];
            tratamientoA[0]     = $(this).find("td.trata_codigo").text();
            tratamientoA[1]     = $(this).find("td.trata_nombre").text();
            if(tratamientoA[0] != ""){

            arrTra.push(tratamientoA);
            }
            // arrTra.push(tratamientoA);
        });
        arrTra = arrTra.filter(
            function(a){return !this[a] ? this[a] = true : false;}, {}
        );
        arrTra = JSON.stringify(arrTra);
        $('#carroTra').val(arrTra);
        $("#frm_laboratorio_master").find("input:checked").each(function(element) {
            let allCheck        = [2];
            allCheck[0]         = $(this).val();
            allCheck[1]         = $('#'+allCheck[0]).val();
            arrLab.push(allCheck);
        });
        arrLab = arrLab.filter(
            function(a){return !this[a] ? this[a] = true : false;}, {}
        );
        arrLab = JSON.stringify(arrLab);
        $('#carroLab').val(arrLab);
        $("#frm_laboratorio_master2").find("input:checked").each(function(element) {
            let allCheck        = [2];
            allCheck[0]         = $(this).val();
            allCheck[1]         = $('#'+allCheck[0]).val();
            arrLab2.push(allCheck);
        });
        arrLab2 = arrLab2.filter(
            function(a){return !this[a] ? this[a] = true : false;}, {}
        );
        arrLab2 = JSON.stringify(arrLab2);
        $('#carroLab2').val(arrLab2);
        $("#contenidoOtro tr").each(function(element){
            let otrosA          = [1];
            otrosA[0]           = $(this).find("td.otro_nombre").html();
            arrOtr.push(otrosA);
        });
        arrOtr = arrOtr.filter(
            function(a){return !this[a] ? this[a] = true : false;}, {}
        );
        arrOtr = JSON.stringify(arrOtr);
        $('#carroOtr').val(arrOtr);
        $("#contenidoTratamientoNuevo tr").each(function(element){
            let traNue          = [2];
            traNue[0]           = $(this).find("td.frm_tratamientoNuevo_nombre").html();
            traNue[1]           = $(this).find("td.frm_idClasificacion").text();
            arrTraNuevo.push(traNue);
        });
        arrTraNuevo = arrTraNuevo.filter(
            function(a){return !this[a] ? this[a] = true : false;}, {}
        );
        arrTraNuevo = JSON.stringify(arrTraNuevo);
        $('#carroTratamiento').val(arrTraNuevo);
    }
    $("#slc_nombrePlantilla").on('change', function() {
	    if ( $("#frm_contenido_indicaciones #slc_nombrePlantilla").val() == '' ) {
            ajaxContent(raiz+'/views/modules/rce/indicaciones/indicaciones_modal.php',`dau_id=${dau_id}&rce_id=${rce_id}`, '#cargarIndicacionesModal', '', true);
		} else {
			cargarParametrosPlantillaIndicaciones();
        }

    });
    function pacienteComplejo ( ) {
        const  respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/indicaciones/main_controller.php`, $('#frm_cambioPacienteComplejo').serialize()+`&accion=pacienteComplejo2&dau_id=${dau_id}`, 'POST', 'JSON', 1);
        switch (respuestaAjaxRequest.status ) {
            case "success":
                $('#advertenciaPacienteComplejo').modal( 'hide' ).data( 'bs.modal', null );
                refrescarDivTipoPaciente();
            break;
            default:
                 ErrorSistemaDefecto();
            break;
        }
    }
    function refrescarDivTipoPaciente ( ) {
        let arrayLaboratorio = [], arrayImagenologia = [],  arrayTratamientos = [], arrayOtros = [], arrayProcedimientos = [];
        arrayImagenologia   = obtenerCarroImagenologia(arrayImagenologia);
        arrayTratamientos   = obtenerCarroTratamientos(arrayTratamientos);
        arrayLaboratorio    = obtenerCarroLaboratorio(arrayLaboratorio);
        arrayProcedimientos = obtenerCarroProcedimientos(arrayProcedimientos);
        arrayOtros          = obtenerCarroOtros(arrayOtros);
        ajaxContent(raiz+'/views/modules/rce/indicaciones/indicaciones_modal.php',`dau_id=${dau_id}&rce_id=${rce_id}&tablaRayosContendido=${arrayImagenologia}&tablaTratamiento=${arrayTratamientos}&aLab=${arrayLaboratorio}&tablaProcedimiento=${arrayProcedimientos}&tablaOtros=${arrayOtros}`, '#cargarIndicacionesModal', '', true);
    }
    function obtenerCarroImagenologia ( arrayImagenologia ) {
        $("#contenidoRayo tr").each(function(element){
            let contenidoImagenologia    = [];
            contenidoImagenologia[0] = $(this).find("td.ima_valorExamen").text().trim();
            contenidoImagenologia[1] = $(this).find("td.ima_valorTipoExamen").text().trim();
            contenidoImagenologia[2] = $(this).find("td.ima_valorLateralidad").text().trim();
            contenidoImagenologia[3] = $(this).find("td.ima_valorContrastes").text().trim();
            contenidoImagenologia[4] = $(this).find("td.ima_valorObservacion").text().trim();
            contenidoImagenologia[5] = $(this).find("td.ima_valorIdPrestacion").text().trim();
            contenidoImagenologia[6] = $(this).find("td.ima_valorPrestaciones").text().trim();
            contenidoImagenologia[7] = $(this).find("td.ima_valorParteCuerpo").text().trim();

            arrayImagenologia.push(contenidoImagenologia);
        });
        arrayImagenologia = arrayImagenologia.filter(
            function(a){return !this[a] ? this[a] = true : false;}, {}
        );
        arrayImagenologia = JSON.stringify(arrayImagenologia);
        return arrayImagenologia;
    }
    function obtenerCarroTratamientos ( arrayTratamientos ) {
        $("#contenidoTratamientoNuevo tr").each(function(element){
            let contenidoTratamiento           = [4];
            contenidoTratamiento[0]            = $(this).attr('id');
            contenidoTratamiento[1]            = $(this).find("td.frm_tratamientoNuevo_nombre").html();
            contenidoTratamiento[1]            = contenidoTratamiento[1].replace(/&lt;/g, '<');
            contenidoTratamiento[1]            = contenidoTratamiento[1].replace(/&gt;/g, '>');
            contenidoTratamiento[2]            = $(this).find("td.frm_idClasificacion").text();
            contenidoTratamiento[3]            = $(this).find("td.frm_clasificacionTratamiento").text();
            arrayTratamientos.push(contenidoTratamiento);
        });
        arrayTratamientos = arrayTratamientos.filter(
            function(a){return !this[a] ? this[a] = true : false;}, {}
        );
        arrayTratamientos = JSON.stringify(arrayTratamientos);
        return arrayTratamientos;
    }
    function obtenerCarroLaboratorio ( arrayLaboratorio ) {
        $("#frm_laboratorio_master2").find("input:checked").each(function(element) {
            let contenidoLaboratorio        = [2];
            contenidoLaboratorio[0]         = $(this).val();
            contenidoLaboratorio[1]         = $('#'+contenidoLaboratorio[0]).val();
            arrayLaboratorio.push(contenidoLaboratorio);

        });
        arrayLaboratorio = arrayLaboratorio.filter(
            function(a){return !this[a] ? this[a] = true : false;}, {}
        );
        arrayLaboratorio = JSON.stringify(arrayLaboratorio);
        return arrayLaboratorio;
    }
    function obtenerCarroProcedimientos ( arrayProcedimientos ) {
        $("#contenidoTratamiento tr").each(function(element){
            arrayProcedimientos.push($(this).attr("id"));
        });
        arrayProcedimientos = arrayProcedimientos.filter(
            function(a){return !this[a] ? this[a] = true : false;}, {}
        );
        return arrayProcedimientos;
    }
    function obtenerCarroOtros ( arrayOtros ) {
        $("#contenidoOtro tr").each(function(element){
            let contenidoOtros      = [2];
            contenidoOtros[0]       = $(this).attr('id');
            contenidoOtros[1]       = $(this).find("td.otro_nombre").html();
            arrayOtros.push(contenidoOtros);
        });
        arrayOtros = arrayOtros.filter(
            function(a){return !this[a] ? this[a] = true : false;}, {}
        );
        arrayOtros = JSON.stringify(arrayOtros);
        return arrayOtros;
    }
    function modalCrearPlantilla ( ) {
        const botones =   [
                            { id: idBotonPlantilla, value: 'Crear', function: funcionPlantilla, class: 'btn btn-primary' }
                          ];
        modalFormulario("<label class='mifuente ml-2'>"+tituloPlantilla+"</label>", `${raiz}/views/modules/rce/indicaciones/modalNombrePlantilla.php`, '', "#modalNombrePlantilla", "modal-lg", "light",'', botones);
    }
    function verificarDatosIndicaciones ( ) {
        const tablaImagenologia   = $('#tablaContenido >tbody >tr').length;
        const tablaTratamiento    = $('#table_Tratamiento >tbody >tr').length;
        const tablaOtros          = $('#table_Otros >tbody >tr').length;
        const tablaProcedimiento  = $('#table_tratamientoNuevo >tbody >tr').length;
        const checkboxlaboratorio = $("[name='frm_laboratorio']").filter(':checked').length;
        if ( tablaImagenologia == 0 && tablaTratamiento == 0 && checkboxlaboratorio == 0 && tablaOtros == 0 && tablaProcedimiento == 0 ) {
            texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> ATENCIÓN! </h4>  <hr>  <p class="mb-0">Debe agregar algún examen para poder guardar.</p></div>';
            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
            return false;
        }
        if ( $('#frm_diagnostico').val() == '' && tablaImagenologia != 0 ) {
            texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> ATENCIÓN! </h4>  <hr>  <p class="mb-0">Debe agregar algún diagnóstico para poder guardar.</p></div>';
            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
            return false;
        }
        return true;
    }
    function verificacionNombrePlantillaIndicaciones ( ) {
        if ( $('#input_nombrePlantilla').val() == '' ) {
            texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en crear Plantilla Indicaciones </h4>  <hr>  <p class="mb-0">Debe rellenar el campo de Nombre de Plantilla.</p></div>';
            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
            return false;
        }
        return true;s
    }
    function cargarParametrosPlantillaIndicaciones ( ) {
        const parametros = { 'idPlantilla' : $('#slc_nombrePlantilla').val(), 'accion' : 'obtenerPlantillaIndicaciones' };
        const respuestaAjaxRequest = ajaxRequest(raiz+'/controllers/server/rce/indicaciones/main_controller.php',parametros, 'POST', 'JSON', 1,'');
        switch(respuestaAjaxRequest.status) {
            case "success":
                var tablaExamenesImagenologia = [], antecedentesClinicos = [], tablaTratamiento = [], prestacionesLaboratorio = [], tablaProcedimiento = [], tablaOtros = [];
                tablaExamenesImagenologia     = cargarContenidoImagenologia(respuestaAjaxRequest, tablaExamenesImagenologia);
                antecedentesClinicos          = cargarContenidoAntecedentesClinicos(respuestaAjaxRequest, antecedentesClinicos);
                tablaTratamiento              = cargarContenidoTratamiento(respuestaAjaxRequest, tablaTratamiento);
                prestacionesLaboratorio       = cargarContenidoLaboratorio(respuestaAjaxRequest, prestacionesLaboratorio);
                tablaProcedimiento            = cargarContenidoProcedimiento(respuestaAjaxRequest, tablaProcedimiento);
                tablaOtros                    = cargarContenidoOtros(respuestaAjaxRequest, tablaOtros);
                ajaxContent(raiz+'/views/modules/rce/indicaciones/indicaciones_modal.php',`dau_id=${dau_id}&rce_id=${rce_id}&tablaRayosContendido=${tablaExamenesImagenologia}&antecedentesClinicos=${antecedentesClinicos}&tablaTratamiento=${tablaTratamiento}&aLab=${prestacionesLaboratorio}&tablaProcedimiento=${tablaProcedimiento}&tablaOtros=${tablaOtros}&idPlantilla=${parametros.idPlantilla}&cargaPlantilla=true`, '#cargarIndicacionesModal', '', true);

            break;
            case "error":
                texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en Cargar Parámetros </h4>  <hr>  <p class="mb-0">Error en cargar parámetros de Plantilla Indicaciones:<br><br>'+respuestaAjaxRequest.message+'.</p></div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
            break;
            default:
                ErrorSistemaDefecto();

            break;
        }
    }
    function cargarContenidoImagenologia ( respuestaAjaxRequest, tablaExamenesImagenologia ) {
        for ( let i = 0; i < respuestaAjaxRequest.datosRespuestaConsultaImagenologia.length; i++) {
            const {
                nombreExamen,
                tipoExamen,
                lateralidad,
                contrastes,
                observacionExamen,
                codigoExamen,
                prestaciones,
                parteCuerpo
            } = respuestaAjaxRequest.datosRespuestaConsultaImagenologia[i];

            let datosExamenesImagenologia = [];
            datosExamenesImagenologia[0] = nombreExamen
            datosExamenesImagenologia[1] = tipoExamen,
            datosExamenesImagenologia[2] = lateralidad,
            datosExamenesImagenologia[3] = contrastes,
            datosExamenesImagenologia[4] = observacionExamen,
            datosExamenesImagenologia[5] = codigoExamen,
            datosExamenesImagenologia[6] = prestaciones,
            datosExamenesImagenologia[7] = parteCuerpo
            tablaExamenesImagenologia[i] = datosExamenesImagenologia;
        }
        tablaExamenesImagenologia = JSON.stringify(tablaExamenesImagenologia);
        return tablaExamenesImagenologia;
    }
    function cargarContenidoAntecedentesClinicos ( respuestaAjaxRequest, antecedentesClinicos ) {
        antecedentesClinicos      = $.map(respuestaAjaxRequest.datosRespuestaConsultaAntecedentesClinicos, function (value, index) {
                                        return value;
                                    });
        antecedentesClinicos = JSON.stringify(antecedentesClinicos);
        return antecedentesClinicos;
    }
    function cargarContenidoTratamiento ( respuestaAjaxRequest, tablaTratamiento ) {
        for ( let i = 0; i < respuestaAjaxRequest.datosRespuestaConsultaTratamiento.length; i++) {
            let datosTratamiento = [4];
            datosTratamiento[0]  = respuestaAjaxRequest.datosRespuestaConsultaTratamiento[i].idDetalleTratamiento;
            datosTratamiento[1]  = respuestaAjaxRequest.datosRespuestaConsultaTratamiento[i].detalleTratamiento;
            datosTratamiento[1]  = datosTratamiento[1].replace(/&#39;/g, "'");
            datosTratamiento[1]  = datosTratamiento[1].replace(/&#34;/g, '"');
            datosTratamiento[1]  = datosTratamiento[1].replace(/&#60;/g, '<');
            datosTratamiento[1]  = datosTratamiento[1].replace(/&#62;/g, '>');
            datosTratamiento[2]  = respuestaAjaxRequest.datosRespuestaConsultaTratamiento[i].idClasificacionTratamiento;
            datosTratamiento[3]  = respuestaAjaxRequest.datosRespuestaConsultaTratamiento[i].descripcionClasificacion;
            tablaTratamiento.push(datosTratamiento);
        }
        tablaTratamiento = JSON.stringify(tablaTratamiento);
        return tablaTratamiento;
    }
    function cargarContenidoLaboratorio ( respuestaAjaxRequest, prestacionesLaboratorio ) {
        for ( let i = 0; i < respuestaAjaxRequest.datosRespuestaConsultaLaboratorio.length; i++) {
            let datosLaboratorio = [2];
            datosLaboratorio[0]  = respuestaAjaxRequest.datosRespuestaConsultaLaboratorio[i].idPrestacionLaboratorio;
            datosLaboratorio[1]  = respuestaAjaxRequest.datosRespuestaConsultaLaboratorio[i].descripcionExamen;
            prestacionesLaboratorio.push(datosLaboratorio);
        }
        prestacionesLaboratorio = JSON.stringify(prestacionesLaboratorio);
        return prestacionesLaboratorio;
    }
    function cargarContenidoProcedimiento ( respuestaAjaxRequest, tablaProcedimiento ) {
        for ( let i = 0; i < respuestaAjaxRequest.datosRespuestaConsultaProcedimiento.length; i++) {
            let datosProcedimiento = [1];
            datosProcedimiento[0]  = respuestaAjaxRequest.datosRespuestaConsultaProcedimiento[i].idProcedimiento;
            tablaProcedimiento.push(datosProcedimiento);
        }
        return tablaProcedimiento;
    }
    function cargarContenidoOtros ( respuestaAjaxRequest, tablaOtros ) {
        for ( let i = 0; i < respuestaAjaxRequest.datosRespuestaConsultaOtros.length; i++) {
            let datosOtros = [1];
            datosOtros[0]  = respuestaAjaxRequest.datosRespuestaConsultaOtros[i].idDetalleOtros;
            datosOtros[1]  = respuestaAjaxRequest.datosRespuestaConsultaOtros[i].detalleOtros;
            tablaOtros.push(datosOtros);
        }
        tablaOtros = JSON.stringify(tablaOtros);
        return tablaOtros;
    }
})
