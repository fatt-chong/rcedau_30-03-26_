"use strict";

$(document).ready(function(){
    const hojaHospitalizacion = ( function hojaHospitalizacion ( ) {

        //Declaración variables
        const idDau                           = $("#idDau").val(),
              idRCE                           = $("#idRCE").val(),
              idPaciente                      = $("#idPaciente").val(),
              $btnGuardarHojaHospitalizacion  = $("#btnGuardarHojaHospitalizacion");

        const $inputNombrePaciente            = $("#frm_nombrePaciente"),
              $inputEdadPaciente              = $("#frm_edadPaciente"),
              // $inputMotivoIngreso             = $("#frm_motivoIngreso"),
              // $inputAntecedentesMorbidos      = $("#frm_antecedentesMorbidos"),
              radioAlergias                   = "input[name=frm_alergias]",
              idTablaSignosVitales            = "#tablaSignosVitales",
              radioExamenGeneral              = "input[name=frm_examenGeneral]",
              $inputExamenGeneral             = $("#frm_describaExamenGeneral"),
              radioCabezaConjuntivas          = "input[name=frm_cabezaConjuntivas]",
              radioEscleras                   = "input[name=frm_cabezaEscleras]",
              $inputOtrosExamenGeneral        = $("#frm_examenFisicoSegmentarioOtros"),
              $inputCuelloYColumna            = $("#frm_cuelloYColumna"),
              radioRigidezNuca                = "input[name=frm_rigidezNuca]",
              radioGanglios                   = "input[name=frm_ganglios]",
              radioYugular                    = "input[name=frm_yugular]",
              radioTiroides                   = "input[name=frm_tiroides]",
              $inputTorax                     = $("#frm_torax"),
              radioPulmones                   = "input[name=frm_pulmones]",
              $inputDescripcionPulmones       = $("#frm_descripcionPulmones"),
              radioRRen2T                     = "input[name=frm_corazonRR]",
              radioSoplos                     = "input[name=frm_corazonSoplo]",
              $inputDescripcionCorazon        = $("#frm_descripcionCorazon"),
              $inputInspeccion                = $("#frm_inspeccion"),
              $inputPalpacion                 = $("#frm_palpacion"),
              $inputHigadoYVesicula           = $("#frm_higadoYVesicula"),
              $inputBazo                      = $("#frm_bazo"),
              $inputRinionesYPtosUretrales    = $("#frm_riniones"),
              $inputHernias                   = $("#frm_hernias"),
              $inputTactoRectal               = $("#frm_tactoRectal"),
              $inputGenitales                 = $("#frm_genitales"),
              $inputExtremidades              = $("#frm_extremidades"),
              radioVarices                    = "input[name=frm_varices]",
              radioEdema                      = "input[name=frm_edema]",
              radioPupilas                    = "input[name=frm_pupilas]",
              $inputReflejosOsteotendinosos   = $("#frm_reflejosOsteotendinosos"),
              $inputSignosMeningeos           = $("#frm_signosMeningeos"),
              $inputFocalizacion              = $("#frm_focalizacion"),
              radioConciencia                 = "input[name=frm_conciencia]",
              $inputGlasgow                   = $("#frm_glasgow"),
              $inputPtos                      = $("#frm_ptos"),
              $inputHora                      = $("#frm_hora"),
              $slcGlasgowO                    = $("#frm_glasgowO"),
              $slcGlasgowV                    = $("#frm_glasgowV"),
              $slcGlasgowM                    = $("#frm_glasgowM"),
              idTablaIndicaciones             = "#tablaIndicaciones",
              $inputHipotesisDiagnostica      = $("#frm_hipotesisDiagnostica"),
              $inputHospitalizarEnServicio    = $("#frm_hospitalizarEnServicio")

        let  parametrosAEnviar                = {},
             datosPaciente                    = {},
             antecedentesMorbidos             = {},
             antecedentesMorbidosCompleto     = "",
             signosVitales                    = {},
             indicaciones                     = {},
             idHojaHospitalizacion            = 0,
             datosHojaHospitalizacion         = {};

        ajaxContentFast('/RCEDAU/views/modules/rce/medico/bitacora.php','dau_id='+idDau+'&rce_id='+idRCE+'&idPaciente='+idPaciente,'#div_bitacoraHoja');

        //Funciones privadas
        function _desplegarDato ( valor ) {

            return ( _existeValor(valor) ) ? valor : "----";

        }



        function _existeValor ( valor ) {

            return  (
                            valor === undefined
                        ||  valor === null
                        ||  $.isEmptyObject(valor)
                        ||  valor.length === 0
                        ||  valor === 0
                        ||  valor === ''
                        ||  valor === '0'
                        ||  valor === '0000-00-00'
                        ||  valor === '00-00-0000'
                    )
                    ? false
                    : true;

        }



        // function _imprimirHojaHospitalizacion ( idHojaHospitalizacionAux ) {

        //     let imprimir = function(){

        //                                 $('#pdfHojaHospitalizacion').get(0).contentWindow.focus();

        //                                 $("#pdfHojaHospitalizacion").get(0).contentWindow.print();

        //                             }

        //     let botones =   [
        //                         { id: 'btnImprimir', value: '<i class="glyphicon glyphicon-print" aria-hidden="true"></i> Imprimir', function: imprimir, class: 'btn btn-primary btnPrint' }
        //                     ]

        //     modalFormulario(`Hoja Hospitalización DAU N° ${idDau}`, raiz+"/views/modules/rce/pdfHojaHospitalizacion.php", `idDau=${idDau}&idRCE=${idRCE}&idHojaHospitalizacion=${idHojaHospitalizacionAux}`, "#detalle_dau_pdf", "66%", "100%", botones);


        // }



        $btnGuardarHojaHospitalizacion.on("click", async function(){
          const estadoPermiso = await validarPermisoUsuario('btn_ind');
          if (estadoPermiso) {
            const respuestaAjaxRequest = ajaxRequest(
                                                    `${raiz}/controllers/server/rce/hojaHospitalizacion/main_controller.php`,
                                                    $("#frm_hojaHospitalizacion").serialize()+`&idDau=${idDau}&idRCE=${idRCE}&accion=ingresarHojaHospitalizacion`,
                                                    'POST',
                                                    'JSON',
                                                    1,
                                                    ''
                                                );
            switch ( respuestaAjaxRequest.status ) {
            case "success":

              let imprimir = function(){
                                          $('#pdfHojaHospitalizacion').get(0).contentWindow.focus();
                                          $("#pdfHojaHospitalizacion").get(0).contentWindow.print();
                            }
              let botones =   [
                                  { id: 'btnImprimir', value: '<i class="glyphicon glyphicon-print" aria-hidden="true"></i> Imprimir', function: imprimir, class: 'btn btn-primary btnPrint' }
                              ]
              modalFormulario("<label class='mifuente ml-2'>Hoja Hospitalización DAU N°"+idDau+"</label>", `${raiz}/views/modules/rce/rce/pdfHojaHospitalizacion.php`, `idDau=${idDau}&idRCE=${idRCE}&idHojaHospitalizacion=${respuestaAjaxRequest.idHojaHospitalizacion}`, "#modalAltaUrgencia", "modal-lg", "light",'', botones);
    
// C:\inetpub\wwwroot\RCEDAU\views\modules\rce\rce\pdfHojaHospitalizacion.php
              // modalFormulario(`Hoja Hospitalización DAU N° ${idDau}`, raiz+"/views/modules/rce/pdfHojaHospitalizacion.php", `idDau=${idDau}&idRCE=${idRCE}&idHojaHospitalizacion=${respuestaAjaxRequest.idHojaHospitalizacion}`, "#detalle_dau_pdf", "66%", "100%", botones);


                // _imprimirHojaHospitalizacion(respuestaAjaxRequest.idHojaHospitalizacion);
                $('#hoja_hospitalizacion').modal( 'hide' ).data( 'bs.modal', null );
            break;
            }
          }

        });

        // }



        function _obtenerAntecedentesMorbidos ( ) {

            parametrosAEnviar = {};

            parametrosAEnviar = {
                                    'idPaciente'    : idPaciente,
                                    'accion'        : 'obtenerAntecedentesMorbidos'
                                };

            const respuestaAjaxRequest = ajaxRequest(
                                                        `${raiz}/controllers/server/rce/hojaHospitalizacion/main_controller.php`,
                                                        parametrosAEnviar,
                                                        'POST',
                                                        'JSON',
                                                        1,
                                                        ''
                                                    );

            if ( ! _existeValor(respuestaAjaxRequest) ) {

                antecedentesMorbidos = {};

                return;

            }

            antecedentesMorbidos = respuestaAjaxRequest;

        }



        function _obtenerDatosHojaHospitalizacion ( ) {

            parametrosAEnviar = {};

            parametrosAEnviar = {
                                    'idHojaHospitalizacion' : idHojaHospitalizacion,
                                    'accion'                : 'obtenerDatosHojaHospitalizacion'
                                };

            const respuestaAjaxRequest = ajaxRequest(
                                                        `${raiz}/controllers/server/rce/hojaHospitalizacion/main_controller.php`,
                                                        parametrosAEnviar,
                                                        'POST',
                                                        'JSON',
                                                        1,
                                                        ''
                                                    );

            if ( ! _existeValor(respuestaAjaxRequest) ) {

                datosHojaHospitalizacion = {};

                return;

            }

            datosHojaHospitalizacion = respuestaAjaxRequest;

        }



        function _obtenerDatosPaciente ( ) {

            parametrosAEnviar = {};

            parametrosAEnviar = {
                                    'idDau'     : idDau,
                                    'accion'    : 'obtenerDatosPaciente'
                                };

            const respuestaAjaxRequest = ajaxRequest(
                                                        `${raiz}/controllers/server/rce/hojaHospitalizacion/main_controller.php`,
                                                        parametrosAEnviar,
                                                        'POST',
                                                        'JSON',
                                                        1,
                                                        ''
                                                    );

            if ( ! _existeValor(respuestaAjaxRequest) ) {

                datosPaciente = {};

                return;

            }

            datosPaciente = respuestaAjaxRequest;

        }



        function _obtenerIdHojaHospitalizacion ( ) {

            parametrosAEnviar = {};

            parametrosAEnviar = {
                                    'idDau'     : idDau,
                                    'accion'    : 'obtenerIdHojaHospitalizacion'
                                };

            const respuestaAjaxRequest = ajaxRequest(
                                                        `${raiz}/controllers/server/rce/hojaHospitalizacion/main_controller.php`,
                                                        parametrosAEnviar,
                                                        'POST',
                                                        'JSON',
                                                        1,
                                                        ''
                                                    );

            if ( ! _existeValor(respuestaAjaxRequest) ) {

                idHojaHospitalizacion = 0;

                return;

            }

            idHojaHospitalizacion = respuestaAjaxRequest;

        }



        function _obtenerIndicaciones ( ) {

            parametrosAEnviar = {};

            parametrosAEnviar = {
                                    'idRCE'     : idRCE,
                                    'accion'    : 'obtenerIndicaciones'
                                };

            const respuestaAjaxRequest = ajaxRequest(
                                                        `${raiz}/controllers/server/rce/hojaHospitalizacion/main_controller.php`,
                                                        parametrosAEnviar,
                                                        'POST',
                                                        'JSON',
                                                        1,
                                                        ''
                                                    );

            if ( ! _existeValor(respuestaAjaxRequest) ) {

                indicaciones = {};

                return;

            }

            indicaciones = respuestaAjaxRequest;

        }



        function _obtenerSignosVitales ( ) {

            parametrosAEnviar = {};

            parametrosAEnviar = {
                                    'idRCE'     : idRCE,
                                    'accion'    : 'obtenerSignosVitales'
                                };

            const respuestaAjaxRequest = ajaxRequest(
                                                        `${raiz}/controllers/server/rce/hojaHospitalizacion/main_controller.php`,
                                                        parametrosAEnviar,
                                                        'POST',
                                                        'JSON',
                                                        1,
                                                        ''
                                                    );

            if ( ! _existeValor(respuestaAjaxRequest) ) {

                signosVitales = {};

                return;

            }

            signosVitales = respuestaAjaxRequest;

        }



        function _rellenarAntecedentesMorbidos ( ) {

            antecedentesMorbidosCompleto = "";

            for ( let indice in antecedentesMorbidos ) {

                antecedentesMorbidosCompleto += antecedentesMorbidos[indice].descripcionAntecedente + '\n';

            }

            // $inputAntecedentesMorbidos.val(antecedentesMorbidosCompleto);

        }



        function _rellenarDatosPaciente ( ) {

            $inputNombrePaciente.val(datosPaciente[0].nombrePaciente);

            $inputEdadPaciente.val(datosPaciente[0].edadPaciente);

            // $inputMotivoIngreso.val(datosPaciente[0].motivoConsulta.replace(/<br>/g, "\n"));

            // $inputAntecedentesMorbidos.val(antecedentesMorbidosCompleto.replace(/<br>/g, "\n"));

            $inputHospitalizarEnServicio.val(datosPaciente[0].hospitalizarEnServicio);

            $inputHipotesisDiagnostica.val(datosPaciente[0].hipotesisDiagnostica.replace(/<br>/g, "\n"));

            // $inputIndicaciones.val(datosPaciente[0].indicaciones.replace(/<br>/g, "\n"));

        }



        function _rellenarHojaHospitalizacion ( ) {

            if ( ! _existeValor(datosHojaHospitalizacion) ) {

                return;

            }

            // $inputMotivoIngreso.val((_existeValor(datosHojaHospitalizacion[0].motivoIngreso)) ? datosHojaHospitalizacion[0].motivoIngreso.replace(/<br>/g, "\n") : datosPaciente[0].motivoConsulta.replace(/<br>/g, ""));

            // $inputAntecedentesMorbidos.val((_existeValor(antecedentesMorbidosCompleto) ? antecedentesMorbidosCompleto.replace(/<br>/g, "\n") : datosHojaHospitalizacion[0].antecedentesMorbidos).replace(/<br>/g, "\n"));

            $(`${radioExamenGeneral}[value='${datosHojaHospitalizacion[0].examenGeneral}']`).prop('checked', true);

            $inputExamenGeneral.val(datosHojaHospitalizacion[0].descripcionExamenGeneral);

            $(`${radioCabezaConjuntivas}[value='${datosHojaHospitalizacion[0].conjuntivas}']`).prop('checked', true);

            $(`${radioEscleras}[value='${datosHojaHospitalizacion[0].escleras}']`).prop('checked', true);

            $inputOtrosExamenGeneral.val(datosHojaHospitalizacion[0].otrosExamenFisico);

            $inputCuelloYColumna.val(datosHojaHospitalizacion[0].cuelloYColumna);

            $(`${radioRigidezNuca}[value='${datosHojaHospitalizacion[0].rigidezNuca}']`).prop('checked', true);

            $(`${radioGanglios}[value='${datosHojaHospitalizacion[0].ganglios}']`).prop('checked', true);

            $(`${radioYugular}[value='${datosHojaHospitalizacion[0].yugular}']`).prop('checked', true);

            $(`${radioTiroides}[value='${datosHojaHospitalizacion[0].tiroides}']`).prop('checked', true);

            $inputTorax.val(datosHojaHospitalizacion[0].torax);

            $(`${radioPulmones}[value='${datosHojaHospitalizacion[0].pulmones}']`).prop('checked', true);

            $inputDescripcionPulmones.val(datosHojaHospitalizacion[0].descripcionPulmones);

            $(`${radioRRen2T}[value='${datosHojaHospitalizacion[0].rrEn2T}']`).prop('checked', true);

            $(`${radioSoplos}[value='${datosHojaHospitalizacion[0].soplos}']`).prop('checked', true);

            $inputDescripcionCorazon.val(datosHojaHospitalizacion[0].descripcionCorazon);

            $inputInspeccion.val(datosHojaHospitalizacion[0].inspeccion);

            $inputPalpacion.val(datosHojaHospitalizacion[0].palpacion);

            $inputHigadoYVesicula.val(datosHojaHospitalizacion[0].higadoYVesicula);

            $inputBazo.val(datosHojaHospitalizacion[0].bazo);

            $inputRinionesYPtosUretrales.val(datosHojaHospitalizacion[0].rinionesYPtosUretrales);

            $inputHernias.val(datosHojaHospitalizacion[0].hernias);

            $inputTactoRectal.val(datosHojaHospitalizacion[0].tactoRectal);

            $inputGenitales.val(datosHojaHospitalizacion[0].genitales);

            $inputExtremidades.val(datosHojaHospitalizacion[0].extremidades);

            $(`${radioVarices}[value='${datosHojaHospitalizacion[0].varices}']`).prop('checked', true);

            $(`${radioEdema}[value='${datosHojaHospitalizacion[0].edema}']`).prop('checked', true);

            $(`${radioPupilas}[value='${datosHojaHospitalizacion[0].pupilas}']`).prop('checked', true);

            $inputReflejosOsteotendinosos.val(datosHojaHospitalizacion[0].reflejosOsteotendinosos);

            $inputSignosMeningeos.val(datosHojaHospitalizacion[0].signosMeningeos);

            $inputFocalizacion.val(datosHojaHospitalizacion[0].focalizacion);

            $(`${radioConciencia}[value='${datosHojaHospitalizacion[0].conciencia}']`).prop('checked', true);

            $inputGlasgow.val(datosHojaHospitalizacion[0].glasgow);

            $inputPtos.val(datosHojaHospitalizacion[0].ptos);

            $inputHora.val(datosHojaHospitalizacion[0].hora);

            $slcGlasgowO.val(datosHojaHospitalizacion[0].glasgowO);

            $slcGlasgowV.val(datosHojaHospitalizacion[0].glasgowV);

            $slcGlasgowM.val(datosHojaHospitalizacion[0].glasgowM);

            $inputHipotesisDiagnostica.val((_existeValor(datosHojaHospitalizacion[0].hipotesisDiagnosticas)) ? datosHojaHospitalizacion[0].hipotesisDiagnosticas.replace(/<br>/g, "\n") : datosPaciente[0].hipotesisDiagnostica.replace(/<br>/g, "\n"));

            // $inputIndicaciones.val((_existeValor(datosHojaHospitalizacion[0].indicaciones)) ? datosHojaHospitalizacion[0].indicaciones.replace(/<br>/g, "\n") : datosPaciente[0].indicaciones.replace(/<br>/g, "\n"));

        }



        function _rellenarIndicaciones ( ) {

            let html = "";

            for ( let indice in indicaciones ) {

                if ( indicaciones[indice].estado == 6 || indicaciones[indice].estado == 8 ) {

                    continue;

                }

                const tipoSolicitud = indicaciones[indice].descripcion.split("Solicitud ");

                if ( tipoSolicitud[1] == "Evolución" ) {

                    continue;

                }

                const tipoIndicacion = ( indicaciones[indice].servicio == 4 ) ? "Solicitud Otros" : tipoSolicitud[1];

                const prestacion = ( _existeValor(indicaciones[indice].descripcionClasificacion) ) ? `${indicaciones[indice].Prestacion}<br />(${indicaciones[indice].descripcionClasificacion})` : indicaciones[indice].Prestacion;

                html += `<tr>

                            <td width="14%" style="text-align:center;">${_desplegarDato(tipoIndicacion)}</td>

                            <td width="14%" style="text-align:center;">${_desplegarDato(prestacion)}</td>

                            <td width="14%" style="text-align:center;">${_desplegarDato(indicaciones[indice].estadoDescripcion)}</td>

                            <td width="14%" style="text-align:center;">${_desplegarDato(indicaciones[indice].usuarioInserta)}</td>

                            <td width="14%" style="text-align:center;">${_desplegarDato(indicaciones[indice].UsuarioIniciaIndicacion)}</td>

                            <td width="14%" style="text-align:center;">${_desplegarDato(indicaciones[indice].usuarioTomaMuestra)}</td>

                            <td width="16%" style="text-align:center;">${_desplegarDato(indicaciones[indice].usuarioAplica)}</td>

                        </tr>
                        `;

            }

            $(`${idTablaIndicaciones} > tbody`).append(html);

        }



        function _rellenarSignosVitales ( ) {

            let html = "";

            for ( let indice in signosVitales ) {

                html += `<tr>

                            <td width="14%" style="text-align:center;">${_desplegarDato(signosVitales[indice].SVITALfecha)}</td>

                            <td width="14%" style="text-align:center;">${_desplegarDato(signosVitales[indice].SVITALsistolica)} / ${_desplegarDato(signosVitales[indice].SVITALdiastolica)}</td>

                            <td width="14%" style="text-align:center;">${_desplegarDato(signosVitales[indice].SVITALpulso)}</td>

                            <td width="14%" style="text-align:center;"></td>

                            <td width="14%" style="text-align:center;">${_desplegarDato(signosVitales[indice].SVITALtemperatura)}</td>

                            <td width="14%" style="text-align:center;"></td>

                            <td width="16%" style="text-align:center;">${_desplegarDato(signosVitales[indice].SVITALsaturacion)}</td>

                        </tr>
                        `;

            }

            $(`${idTablaSignosVitales} > tbody`).append(html);

        }



        // function _usuarioVerificadoIngresarHojaHospitalizacion ( ) {

            // const respuestaAjaxRequest = ajaxRequest(
            //                                             `${raiz}/controllers/server/rce/hojaHospitalizacion/main_controller.php`,
            //                                             $("#frm_hojaHospitalizacion").serialize()+`&idDau=${idDau}&idRCE=${idRCE}&accion=ingresarHojaHospitalizacion`,
            //                                             'POST',
            //                                             'JSON',
            //                                             1,
            //                                             ''
            //                                         );

            // switch ( respuestaAjaxRequest.status ) {

            //     case "success":

            //         _imprimirHojaHospitalizacion(respuestaAjaxRequest.idHojaHospitalizacion);

            //         $('#hoja_hospitalizacion').modal( 'hide' ).data( 'bs.modal', null );

            //     break;

            // }

        // }



        //Funciones públicas
        function iniciarHojaHospitalizacion ( ) {

            _obtenerDatosPaciente();

            _obtenerAntecedentesMorbidos();

            _rellenarDatosPaciente();

            _rellenarAntecedentesMorbidos();

            _obtenerSignosVitales();

            _rellenarSignosVitales();

            _obtenerIndicaciones();

            _rellenarIndicaciones();

        }



        function ingresarHojaHospitalizacion ( ) {

            _ingresarHojaHospitalizacion();

        }



        function modificarHojaHospitalizacion ( ) {

            _obtenerIdHojaHospitalizacion();

            if ( _existeValor(idHojaHospitalizacion) ) {

                _obtenerDatosHojaHospitalizacion();

                _rellenarHojaHospitalizacion();

            }

        }



        //Retorno objeto
        return {

            iniciarHojaHospitalizacion      : iniciarHojaHospitalizacion,
            ingresarHojaHospitalizacion     : ingresarHojaHospitalizacion,
            modificarHojaHospitalizacion    : modificarHojaHospitalizacion

        }

    })();

    hojaHospitalizacion.iniciarHojaHospitalizacion();
    hojaHospitalizacion.ingresarHojaHospitalizacion();
    hojaHospitalizacion.modificarHojaHospitalizacion();

});