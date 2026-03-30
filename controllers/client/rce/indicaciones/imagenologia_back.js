$(document).ready(function(){
    const pacienteComplejo = $("#dau_paciente_complejo").val();
    const $divExamenesSeleccionados = $("#despliegueExamenesSeleccionados");
    const $filasEnContenidoRayo = $("#contenidoRayo tr");
    let examenesSeleccionados = [];

        $('#frm_lateralidades').prop("disabled", true);
        $('#frm_llevaContraste').prop("disabled", true);

    var parteCuerpoSeleccionado = "";
    var examenes = [];
    var examenSeleccionado = "";
    var tiposExamenes = [];
    var tipoExamenSeleccionado = "";
    var contrastesSeleccionados = [];
    var sePuedeVisualizarPDFConsentimientoInformado = true;
    var idPrestacionSeleccionada = 0;
    var prestacionesExamenSeleccionado = [];

    validar("#frm_obs_examen","letras_numeros");
    if ($filasEnContenidoRayo.length === 0 ){
        $divExamenesSeleccionados.hide();
    }


    $.each($(".selectpicker"), function() {
        $(this).selectpicker({
            size: 8,
            noneSelectedText : 'Seleccione ...'
        });
    });



    $("#btn_agregar_linea").on("click", function(){
        console.log(examenSeleccionado)
        if (existenDatosMalIngresados()) {
            return;
        }
        console.log(examenSeleccionado)
        examenesSeleccionados.push($examenes.val());

        const valorParteCuerpo = parteCuerpoSeleccionado.trim();
        const valorExamen = examenSeleccionado.trim();
        const valorTipoExamen = tipoExamenSeleccionado.trim();
        const valorLateralidad = $("#frm_lateralidades option:selected").text().trim();
        const valorContraste = (contrastesSeleccionados.length >= 1) ? "Si" : "No";
        const valorObservacion = $("#frm_observacionExamen").val();
        const valorIdPrestacion = idPrestacionSeleccionada;
        const valorPrestaciones = prestacionesExamenSeleccionado;

        const filaValorParteCuerpo = `<td class=" my-1 py-1 mx-1 px-1 mifuente ima_valorParteCuerpo" hidden>${valorParteCuerpo}</<td>`;
        const filaValorExamen = `<td class=" my-1 py-1 mx-1 px-1 mifuente ima_valorExamen">${valorExamen}</<td>`;
        const filaValorTipoExamen = `<td class=" my-1 py-1 mx-1 px-1 mifuente ima_valorTipoExamen" style="text-align:center;">${valorTipoExamen}</<td>`;
        const filaValorLateralidad = `<td class=" my-1 py-1 mx-1 px-1 mifuente ima_valorLateralidad" style="text-align:center;">${valorLateralidad}</<td>`;
        const filaValorContraste = `<td class=" my-1 py-1 mx-1 px-1 mifuente ima_valorContraste" style="text-align:center;">${valorContraste}</<td>`;
        const filaValorContrastes = `<td class=" my-1 py-1 mx-1 px-1 mifuente ima_valorContrastes" hidden>${contrastesSeleccionados}</<td>`;
        const filaValorObservacion = `<td class=" my-1 py-1 mx-1 px-1 mifuente ima_valorObservacion">${valorObservacion}</<td>`;
        const filaValorIdPrestacion = `<td class=" my-1 py-1 mx-1 px-1 mifuente ima_valorIdPrestacion" hidden>${valorIdPrestacion}</<td>`;
        const filaValorPrestaciones = `<td class=" my-1 py-1 mx-1 px-1 mifuente ima_valorPrestaciones" hidden>${valorPrestaciones}</<td>`;
        const botonEliminarFila = `<td style="text-align:center;"><button type='button' id='eli${valorExamen}' class='btn btn-sm btn-outline-danger  mifuente col-lg-12 eliminarExamenImagenologia'><i class="fas fa-trash"></i></button></td>`;
        const fila = `<tr id='${valorExamen}' class='detalle'>${filaValorParteCuerpo}${filaValorExamen}${filaValorTipoExamen}${filaValorLateralidad}${filaValorContraste}${filaValorContrastes}${filaValorObservacion}${filaValorIdPrestacion}${filaValorPrestaciones}${botonEliminarFila}</tr>`;

        $examenes.val("");
        $examenes.selectpicker("refresh");
        $tiposExamenes.val("");
        $lateralidades.val("");
        $contrastes.val("");
        $contrastes.selectpicker("refresh");

        parteCuerpoSeleccionado = "";
        examenSeleccionado = "";
        tipoExamenSeleccionado = "";
        idPrestacionSeleccionada = 0;
        prestacionesExamenSeleccionado = "";
        contrastesSeleccionados = [];

        $(fila).hide().appendTo("#contenidoRayo").fadeIn("");
        $divExamenesSeleccionados.show();
    });



    function existenDatosMalIngresados() {
        if ( $("#frm_examenes option:selected").val() == "" ) {
            $('#frm_examenes').assert(false,'Debe Seleccionar un Examen');
            return true;
        }

        if (examenesSeleccionados.indexOf($examenes.val()) !== -1) {
            $('#frm_examenes').assert(false,'Examen ya seleccionado, debe elegir otro');
            return true;
        }

        if ( $("#frm_lateralidades option:selected").val() == "" ) {
            $('#frm_lateralidades').assert(false,'Debe Seleccionar una Lateralidad');
            return true;
        }

        if ( $("#frm_llevaContraste option:selected").val() === "S" && $("#frm_contrastes option:selected").val() === undefined ) {
            $('#frm_contrastes').assert(false,'Debe Seleccionar tipo(s) contraste(s)');
            return true;
        }

        return false;
    }




    $("#contenidoRayo").on("click", ".eliminarExamenImagenologia", function() {
        examenSeleccionado = $(this).attr("id").replace('eli','');
        $(this).closest("tr").remove();

        const examenAEliminar = examenes.find(examen => examen["examen"].toLowerCase() === examenSeleccionado.toLowerCase());
        examenesSeleccionados = examenesSeleccionados.filter(examen => Number(examen) !== Number(examenAEliminar.id_prestaciones));
      });



    $('#frm_Otro').on('click',function(){
        if ( $('#frm_Otro').is(':checked') ) {
            $('#frm_div_otros').show('fast');
            return;
        }

        $('#frm_div_otros').hide('fast');
        $("#frm_otros_text").val("");
    });



    //Funciones integración dalca
    const $examenes = $("#frm_examenes");
    const $tiposExamenes = $("#frm_tiposExamenes");
    const $lateralidades = $("#frm_lateralidades");
    const $tieneContraste = $("#frm_llevaContraste");
    const $tipoContraste = $("#tipoContraste");
    const $contrastes = $("#frm_contrastes");
    const lateralidades = Object.freeze([
        "Derecha",
        "Izquierda",
        "Ambos",
        "Sin Lateralidad"
    ]);

    // const contrastes = Object.freeze({
    //     sinContraste: "Sin Contraste",
    //     consentimientoInformado: "Consentimiento Informado Completo",
    //     clearenceDeCreatinina: "Clearence de Creatinina",
    //     premedicacion: "Premedicación",
    //     proteccionRenal: "Protección Renal",
    //     sedacion: "Sedación"
    // });

    var contrastes;

    $("#frm_examenes").change(function() {
        var _valor = $("#frm_examenes").val();
        
        if (_valor > 0) {
            var regServidor = function(response) {
                console.log("response", response);

                // ⚠️ Usar comparación estricta
                if (response.tipo_examen === "RM") {
                    contrastes = {
                        sinContraste: "Sin Contraste 1",
                        consentimientoInformado: "Consentimiento Informado Completo",
                        clearenceDeCreatinina: "Clearence de Creatinina",
                        premedicacion: "Premedicación",
                        proteccionRenal: "Protección Renal",
                        sedacion: "Sedación",
                        marcapasos: "Marcapasos"
                    };
                } else {
                    contrastes = {
                        sinContraste: "Sin Contraste 2",
                        consentimientoInformado: "Consentimiento Informado Completo",
                        clearenceDeCreatinina: "Clearence de Creatinina",
                        premedicacion: "Premedicación",
                        proteccionRenal: "Protección Renal",
                        sedacion: "Sedación"
                    };
                }

                // Vaciar select antes de rellenar
                $contrastes.empty();

                // Rellenar select
                _rellenarSelectContrastes($contrastes, contrastes);
            };

            ajaxRequest(
                raiz + '/controllers/server/rce/indicaciones/main_controller.php',
                'accion=get_info_examen&valor=' + _valor,
                'POST',
                'JSON',
                1,
                'Cargando...',
                regServidor
            );

        } else {
            contrastes = {
                sinContraste: "Sin Contraste 3",
                consentimientoInformado: "Consentimiento Informado Completo",
                clearenceDeCreatinina: "Clearence de Creatinina",
                premedicacion: "Premedicación",
                proteccionRenal: "Protección Renal",
                sedacion: "Sedación"
            };

            // Vaciar select antes de rellenar
            $contrastes.empty();

            _rellenarSelectContrastes($contrastes, contrastes);
        }
    });


    // $("#frm_tiposExamenes").change(function() {
    //     var valor_frm_tiposExamenes = $("#frm_tiposExamenes").val()

    //     if(valor_frm_tiposExamenes == "RM"){
    //         alert(1)
    //         contrastes = {
    //             sinContraste: "Sin Contraste 4",
    //             consentimientoInformado: "Consentimiento Informado Completo",
    //             clearenceDeCreatinina: "Clearence de Creatinina",
    //             premedicacion: "Premedicación",
    //             proteccionRenal: "Protección Renal",
    //             sedacion: "Sedación",
    //             marcapasos: "Marcapasos"
    //         };
    //     }else{
    //         alert(2)
    //         contrastes = {
    //             sinContraste: "Sin Contraste 5",
    //             consentimientoInformado: "Consentimiento Informado Completo",
    //             clearenceDeCreatinina: "Clearence de Creatinina",
    //             premedicacion: "Premedicación",
    //             proteccionRenal: "Protección Renal",
    //             sedacion: "Sedación"
    //         };
    //     }

    //     $contrastes.empty();
    //      _rellenarSelectContrastes($contrastes, contrastes);
    // });

    


    (function (){
        examenes = _obtenerExamenes();
        _rellenarSelectExamenes($examenes, examenes);

        tiposExamenes = _obtenerTiposExamenes();
        _rellenarSelectTiposExamenes($tiposExamenes, tiposExamenes);

        _rellenarSelectLateralidades($lateralidades, lateralidades);

        $tipoContraste.hide();

        _rellenarSelectContrastes($contrastes, contrastes);
    })();



    function _obtenerExamenes() {
        const respuestaAjaxRequest = ajaxRequest(
            `${raiz}/controllers/server/rce/indicaciones/main_controller.php`,
            {
                pacienteComplejo,
                accion: "obtenerExamenesIntegracionDALCA"
            },
            'POST',
            'JSON',
            1
        );

        // $examenes.trigger('change');
        return (respuestaAjaxRequest !== undefined && respuestaAjaxRequest !== null)
            ? respuestaAjaxRequest
            : [];
    }



    function _rellenarSelectExamenes($examenes, examenes) {
        $examenes.empty();
        $examenes.append(`
                <option value="">
                   Seleccione...
                </option>`
            );
        examenes.forEach(examen => {
            $examenes.append(`
                <option value="${examen["id_prestaciones"]}">
                    ${examen["examen"]}
                </option>`
            );
        });
        // if ( $("#frm_examenes option:selected").val() != "" ) {
            $examenes.trigger('change');
        // }
        // alert()
        // $examenes.selectpicker('destroy');
        // $examenes.selectpicker();
    }



    function _obtenerTiposExamenes() {
        const respuestaAjaxRequest = ajaxRequest(
            `${raiz}/controllers/server/rce/indicaciones/main_controller.php`,
            {
                pacienteComplejo,
                accion: "obtenerTiposExamenesIntegracionDALCA"
            },
            'POST',
            'JSON',
            1
        );

        return (respuestaAjaxRequest !== undefined && respuestaAjaxRequest !== null)
            ? respuestaAjaxRequest
            : [];
    }



    function _rellenarSelectTiposExamenes($tiposExamenes, tiposExamenes) {
        $tiposExamenes.empty();
        $tiposExamenes.append(`<option value="" selected>Todos</option>`);
        tiposExamenes.forEach(tipoExamen => {
            $tiposExamenes.append(`
                <option value="${tipoExamen["tipo_examen"]}">
                    ${tipoExamen["tipo_examen"]}
                </option>`
            );
        });
    }



    function _rellenarSelectLateralidades($lateralidades, lateralidades) {
        $lateralidades.empty();
        $lateralidades.append(`<option value="" selected disabled>Seleccione</option>`);
        lateralidades.forEach(lateralidad => {
            $lateralidades.append(`
                <option value="${lateralidad}">
                    ${lateralidad}
                </option>`
            );
        });
    }



    function _rellenarSelectContrastes($contrastes, contrastes) {
        $contrastes.empty();
        for (const [valor, texto] of Object.entries(contrastes)) {
            $contrastes.append(`
                <option value="${valor}">
                    ${texto}
                </option>`
            );
        }

        $contrastes.selectpicker('destroy');
        $contrastes.selectpicker('');
    }


    $examenes.on("change", () => {
        const idPrestacion = $examenes.val();
        // Busca el examen en el array de objetos
        examenSeleccionado = examenes.find(
            examen => examen.id_prestaciones == idPrestacion // Usa `==` si los tipos no coinciden
        );
        $('#frm_lateralidades').val("");
        $('#frm_lateralidades').prop("disabled", true);
        $('#frm_llevaContraste').val("N");
        $('#frm_llevaContraste').prop("disabled", true);
        // const {
        //     // examen,
        //     parte_del_cuerpo: parteCuerpo,
        //     prestaciones,
        //     tipo_examen: tipoExamen,
        //     Lateralidad: lateralidad,
        //     contraste
        // } = examenSeleccionado;

        // console.log({
        //     // examen,
        //     parteCuerpo,
        //     prestaciones,
        //     tipoExamen,
        //     lateralidad,
        //     contraste
        // });
        // console.log(examenSeleccionado)
        // console.log(examen)
        // parteCuerpoSeleccionado = parteCuerpo;
        // examenSeleccionado = examen;
        // console.log(examenSeleccionado)
        // tipoExamenSeleccionado = tipoExamen;
        // idPrestacionSeleccionada = idPrestacion;
        // prestacionesExamenSeleccionado = prestaciones;
        // $tiposExamenes.val(tipoExamen);
        // sePuedeVisualizarPDFConsentimientoInformado = true;
        // evaluarLateralidad(lateralidad);
        // evaluarContraste(contraste);
        // console.log(examenSeleccionado)
    });


     $('#frm_examenes').on("change", () => {
        const idPrestacion = $examenes.val();
        // Busca el examen en el array de objetos
        examenSeleccionado = examenes.find(
            examen => examen.id_prestaciones == idPrestacion // Usa `==` si los tipos no coinciden
        );
        // $('#frm_lateralidades').prop("disabled", true);
        // $('#frm_llevaContraste').prop("disabled", true);
        const {
            examen,
            parte_del_cuerpo: parteCuerpo,
            prestaciones,
            tipo_examen: tipoExamen,
            Lateralidad: lateralidad,
            contraste
        } = examenSeleccionado;

        console.log({
            examen,
            parteCuerpo,
            prestaciones,
            tipoExamen,
            lateralidad,
            contraste
        });
        console.log(examenSeleccionado)
        console.log(examen)
        parteCuerpoSeleccionado = parteCuerpo;
        examenSeleccionado = examen;
        console.log(examenSeleccionado)
        tipoExamenSeleccionado = tipoExamen;
        idPrestacionSeleccionada = idPrestacion;
        prestacionesExamenSeleccionado = prestaciones;
        $tiposExamenes.val(tipoExamen);
        sePuedeVisualizarPDFConsentimientoInformado = true;
        evaluarLateralidad(lateralidad);
        evaluarContraste(contraste);
        console.log(examenSeleccionado)
    });
    // $examenes.on("change", () => {
    //     const idPrestacion = $examenes.val();
    //     const {
    //         examen,
    //         parte_del_cuerpo: parteCuerpo,
    //         prestaciones,
    //         tipo_examen: tipoExamen,
    //         Lateralidad: lateralidad,
    //         contraste
    //     } = examenes.find(examen => examen.id_prestaciones === idPrestacion);
    //     console.log(examen)
    //     parteCuerpoSeleccionado = parteCuerpo;
    //     examenSeleccionado = examen;
    //     tipoExamenSeleccionado = tipoExamen;
    //     idPrestacionSeleccionada = idPrestacion;
    //     prestacionesExamenSeleccionado = prestaciones;

    //     $tiposExamenes.val(tipoExamen);

    //     sePuedeVisualizarPDFConsentimientoInformado = true;

    //     evaluarLateralidad(lateralidad);
    //     evaluarContraste(contraste);
    // });



    function evaluarLateralidad(lateralidad) {
        const sinLateralidad = lateralidades[3];

        if (lateralidad === "N") {
            const lateralidadesFiltradas = lateralidades[lateralidades.length - 1];
            _rellenarSelectLateralidades($lateralidades, [lateralidadesFiltradas]);
            $lateralidades.val(sinLateralidad);
            return;
        }

        const lateralidadesFiltradas = lateralidades.slice(0, lateralidades.indexOf(sinLateralidad));
        _rellenarSelectLateralidades($lateralidades, lateralidadesFiltradas);
        $lateralidades.val("");
    }



    function evaluarContraste(contraste) {
        const [sinContraste, texto] = Object.entries(contrastes)[0];

        if (contraste === "N") {
            $tieneContraste.val("N");
            $tieneContraste.prop("disabled", true);
            $tipoContraste.hide();
            contrastesSeleccionados = [];
            _rellenarSelectContrastes($contrastes, {[sinContraste]: texto});
            $contrastes.val(sinContraste);
            $contrastes.selectpicker("refresh");
            return;
        }

        $tieneContraste.prop("disabled", false);
        const entradasEnContrastes = Object.entries(contrastes);
        const contrastesFiltrados = Object.fromEntries(entradasEnContrastes.slice(1));
        _rellenarSelectContrastes($contrastes, contrastesFiltrados);
    }



    $tiposExamenes.on("change", () => {
        const tipoExamen = $tiposExamenes.val();
        let examenesFiltrados = examenes.filter(examen => examen.tipo_examen === tipoExamen);
        if (tipoExamen === undefined || tipoExamen === null || tipoExamen === "") {
            examenesFiltrados = examenes;
        }

        sePuedeVisualizarPDFConsentimientoInformado = true;

        _rellenarSelectExamenes($examenes, examenesFiltrados);
        _rellenarSelectLateralidades($lateralidades, lateralidades);
        _rellenarSelectContrastes($contrastes, contrastes);
    });



    $tieneContraste.on("change", () => {
        if ($tieneContraste.val() === "S") {
            $tipoContraste.show();
            return;
        }

        contrastesSeleccionados = [];
        $tipoContraste.hide();
    });



    $contrastes.on("change", () => {
        contrastesSeleccionados = [];
        $.each($("#frm_contrastes option"), function(){
            evaluarVisualizacionPDFConsentimientoInformado($(this).val(), $(this).is(":selected"));

            if (!$(this).is(":selected")) {
                return;
            }

            contrastesSeleccionados.push($(this).val());
        });
    });



    function evaluarVisualizacionPDFConsentimientoInformado(contraste, estaSeleccionado) {
        const consentimientoInformado = Object.entries(contrastes)[1][0];
        if ( contraste !== consentimientoInformado) {
            return;
        }

        if (sePuedeVisualizarPDFConsentimientoInformado && estaSeleccionado) {
            sePuedeVisualizarPDFConsentimientoInformado = false;
            showFile("/RCE/Files/PDFconsentimiento_informado.php", "800px", "700px");
        }

        if (!estaSeleccionado) {
            sePuedeVisualizarPDFConsentimientoInformado = true;
        }
    }
});
