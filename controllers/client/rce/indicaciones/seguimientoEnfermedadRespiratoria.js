"use strict";

$(document).ready(function () {

        const seguimientoEnfermedadRespiratoria = (function seguimientoEnfermedadRespiratoria() {

        //Variables Rescatadas
        const idPaciente                 = $("#idPaciente").val(),
              idDau                      = $("#idDau").val(),
              $idFormulario              = $("#idFormulario"),
              $estadoFormulario          = $("#estadoFormulario"),
              $cantidadEntradaFormulario = $("#cantidadFormulario"),
              $estadoTomaMuestra         = $("#estadoMuestra"),
              $run                       = $("#frm_seguimientoRUN"),
              $nombrePaciente            = $("#frm_seguimientoNombre"),
              $edadPaciente              = $("#frm_seguimientoEdad"),
              $nacionalidad              = $("#frm_seguimientoNacionalidad"),
              $paisResidencia            = $("#frm_seguimientoPaisResidencia"),
              $lugarTrabajo              = $("#frm_seguimientoLugarTrabajo"),
              $direccion                 = $("#frm_seguimientoDireccion"),
              $telefono                  = $("#frm_seguimientoTelefono"),
              $celular                   = $("#frm_seguimientoCelular"),
              $correo                    = $("#frm_seguimientoCorreo"),
              $cantidadViven             = $("#frm_seguimientoCantidadViven"),
              $lugarTomaMuestra          = $("#frm_lugarTomaMuestra"),
              $muestraTomadaPor          = $("#frm_muestraTomadaPor"),
              $fechaMuestra              = $("#frm_fechaMuestra"),
              $motivoSospecha            = $("#frm_seguimientoMotivoSospecha"),
              $inicioSintomas            = $("#frm_seguimientoInicioSintomas"),
              containerfechaMuestra      = "#fechaTomaMuestra",
              containerInicioSintomas    = "#seguimientoInicioSintomas",
              $slcEstadosIngreso         = $("#frm_seguimientoEstadoIngreso"),
              $slcAntecedentes           = $("#frm_seguimientoAntecedentesEpidemiologicos"),
              $slcDestinos               = $("#frm_seguimientoDestino"),
              $slcEmbarazada             = $("#frm_seguimientoEmbarazada"),
              $observaciones             = $("#frm_seguimientoObservaciones"),
              $btnGuardar                = $("#btnGuardarSeguimiento"),
              $btnActualizar             = $("#btnActualizarSeguimiento"),
              $divResultadosAnteriores   = $("#resultadoMuestrasAnteriores"),
              tablaResultadosAnteriores  = "#tablaResultadosAnteriores";

        //Variables Creadas
        let idFormulario                 = 0,
            estadoTomaMuestra            = 0,
            informacionPaciente          = {},
            estadosIngreso               = {},
            antecedentesEpidemiologicos  = {},
            destinos                     = {},
            informacionSeguimiento       = {},
            informacionTomaMuestra       = {},
            resultadosAnteriores         = {};



        //Funciones privadas
        function _actualizarFormulario ( ) {

            $btnActualizar.on("click", function(){
            
                if ( typeof ingresoExamenCovid !== 'undefined' ) {
                
                    ingresoExamenCovid = false;
                
                }

                if ( ! _verificarDatos() ) {

                    return;

                }

                const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/medico/main_controller.php`, $("#frm_seguimientoEnfermedadRespiratoria").serialize()+'&accion=actualizarFormularioSeguimiento', 'POST', 'JSON', 1, '');

                switch(respuestaAjaxRequest.status) {

                    case "success":
                        texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-edit mr-2" style="color: #5a8dc5; font-size: 26px"></i>Seguimiento Actualizado. </h4><hr><p>Información sobre el seguimiento del paciente fue actualizado con éxito.</p> </div>';
                        modalMensajNoCabecera('Exito',texto,  "#modal", "modal-md", "success");
                        $('#formularioSeguimiento3').modal( 'hide' ).data( 'bs.modal', null );

                    break;

                    case "error":

                        texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-edit mr-2" style="color: #5a8dc5; font-size: 26px"></i>Error Guardar Seguimiento. </h4><hr><p>Error en guardar información sobre seguimiento de enfermedad respiratoria:<br><br>'+respuestaAjaxRequest.message+'.</p> </div>';
                        modalMensajNoCabecera('Exito',texto,  "#modal", "modal-md", "success");

                    break;

                    default:

                        ErrorSistema();

                    break;

                }

            });

        }



        function _asignarValorCheckBox ( idFormulario, boleano, valor ) {

            $(`input[name="${idFormulario}"]`).prop("checked", boleano);

            $(`input[name="${idFormulario}"]`).val(valor);

        }



        function _buscarInfoPaciente ( ) {

            const parametros = { 'idPaciente' : idPaciente , 'accion' : 'obtenerInformacionPaciente' };

            informacionPaciente = ajaxRequest(`${raiz}/controllers/server/medico/main_controller.php`, parametros, 'POST', 'JSON', 1,'');

            _rellenarInformacionPaciente();

        }



        function _buscarInfoSeguimiento ( ) {

            const parametros = { 'idPaciente' : idPaciente , 'accion' : 'verificarSeguimientoEnfermedadRespiratoria' };

            informacionSeguimiento = ajaxRequest(`${raiz}/controllers/server/medico/main_controller.php`, parametros, 'POST', 'JSON', 1, '');

            if ( informacionSeguimiento == null || informacionSeguimiento.length == 0 ) {

                return;

            }

            _rellenarInformacionSeguimiento();

        }

        function _buscarInfoTomaMuestra ( ) {

            const parametros = { 'idFormulario' : idFormulario , 'accion' : 'obtenerInformacionTomaMuestra' };

            informacionTomaMuestra = ajaxRequest(`${raiz}/controllers/server/medico/main_controller.php`, parametros, 'POST', 'JSON', 1, '');
            
            if ( informacionTomaMuestra == null || informacionTomaMuestra.length == 0 ) {

                return;

            }

            _rellenarInformacionTomaMuestra();

        }
        
        
        
        function _buscarResultadosAnteriores ( ) {
        
            const parametros = { 'idPaciente' : idPaciente , 'accion' : 'obtenerResultadosMuestrasAnteriores' };

            resultadosAnteriores = ajaxRequest(`${raiz}/controllers/server/medico/main_controller.php`, parametros, 'POST', 'JSON', 1, '');

            if ( resultadosAnteriores == null || resultadosAnteriores.length == 0 ) {

                return;

            }
            
            _rellenarResultadosAnteriores();        
        
        }



        function _calcularEdadPaciente ( ) {

            const fechaNacimientoPaciente = new Date(informacionPaciente.fechanac);

            const fechaActual = new Date();

            const edadPaciente = Math.floor((fechaActual-fechaNacimientoPaciente) / (365.25 * 24 * 60 * 60 * 1000));

            return edadPaciente;

        }



        function _definirRunPaciente ( ) {

            return ( 0 != informacionPaciente.rut || null != informacionPaciente.rut ) ? `${informacionPaciente.rut}-${informacionPaciente.dv}` : 0;

        }
        
        
        
        function _destruirTabla ( ) {

            $(`${tablaResultadosAnteriores} > tbody tr`).each(function() {

                $(this).remove();

            });

        }




        function _guardarSeguimiento ( ) {

            $btnGuardar.on("click", function(){
            
                if ( typeof ingresoExamenCovid !== 'undefined' ) {
                
                    ingresoExamenCovid = false;
                
                }

                if ( ! _verificarDatos() ) {

                    return;

                }

                const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/medico/main_controller.php`, $("#frm_seguimientoEnfermedadRespiratoria").serialize()+'&accion=guardarFormularioSeguimiento', 'POST', 'JSON', 1, '');

                switch(respuestaAjaxRequest.status) {

                    case "success":

                        texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-edit mr-2" style="color: #5a8dc5; font-size: 26px"></i>Seguimiento Actualizado. </h4><hr><p>Información sobre el seguimiento del paciente fue actualizado con éxito.</p> </div>';
                        modalMensajNoCabecera('Exito',texto,  "#modal", "modal-md", "success");
                        $('#formularioSeguimiento3').modal( 'hide' ).data( 'bs.modal', null );

                    break;

                    case "error":
                        texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-edit mr-2" style="color: #5a8dc5; font-size: 26px"></i>Error Guardar Seguimiento. </h4><hr><p>Error en guardar información sobre seguimiento de enfermedad respiratoria:<br><br>'+respuestaAjaxRequest.message+'.</p> </div>';
                        modalMensajNoCabecera('Exito',texto,  "#modal", "modal-md", "success");

                    break;

                    default:

                        ErrorSistema();

                    break;

                }

            });

        }



        function _iniciarFechaInicioSintomas ( ) {

            $.fn.datepicker.dates['es'] = {
                days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
                daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
                daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                today: "Hoy",
                monthsTitle: "Meses",
                clear: "Borrar",
                weekStart: 1,
                format: "dd-mm-yyyy"
            };

            $inicioSintomas.datepicker({
                todayHighlight: true,
                autoclose: true,
                format: "dd-mm-yyyy",
                container: $(`${containerInicioSintomas}`),
                language: 'es',
                endDate: '0d'
            });

            $fechaMuestra.datepicker({
                todayHighlight: true,
                autoclose: true,
                format: "dd-mm-yyyy",
                container: $(`${containerfechaMuestra}`),
                language: 'es',
                endDate: '0d'
            });

        }



        function _iniciarSelects ( objetoArray, objetoSLC, accion) {

            const parametros = { 'accion': accion };

            objetoArray = ajaxRequest(`${raiz}/controllers/server/medico/main_controller.php`, parametros, 'POST', 'JSON', 1, '');

            if ( objetoArray == null || objetoArray.length == 0 ) {

                return;

            }

            _rellenarSelects(objetoArray, objetoSLC);

        }
        
        
        
        function _existeOmega ( index ) {
        
            if ( resultadosAnteriores[index].solicitud_examen == null && resultadosAnteriores[index].correlativo_laboratorio_muestra == null ) {
            
                return 'SIN OMEGA';
            
            }
            
            if ( resultadosAnteriores[index].solicitud_examen != null && resultadosAnteriores[index].solicitud_examen != '' ) {
            
                return resultadosAnteriores[index].solicitud_examen;
            
            }
            
            return resultadosAnteriores[index].correlativo_laboratorio_muestra;            
        
        }



        function _rellenarInformacionPaciente ( ) {


            $run.val(_definirRunPaciente());

            $nombrePaciente.val(`${informacionPaciente.nombres} ${informacionPaciente.apellidopat} ${informacionPaciente.apellidomat}`);

            $edadPaciente.val(_calcularEdadPaciente());

            $nacionalidad.val(informacionPaciente.NACdescripcion);

            $direccion.val(informacionPaciente.direccion);

            $celular.val(informacionPaciente.fono1);

            $correo.val(informacionPaciente.email);

        }



        function _rellenarInformacionSeguimiento ( ) {

            $idFormulario.val(idFormulario = informacionSeguimiento.form_int_id);

            $estadoFormulario.val(informacionSeguimiento.form_int_estado);

            $cantidadEntradaFormulario.val(informacionSeguimiento.form_int_cant_int),

            $nacionalidad.val(informacionSeguimiento.form_int_nacionalidad);

            $paisResidencia.val(informacionSeguimiento.form_int_pais_residencia);

            $lugarTrabajo.val(informacionSeguimiento.form_int_lugar_trabajo);

            $direccion.val(informacionSeguimiento.form_int_direccion_pac);

            $telefono.val(informacionSeguimiento.form_int_telefono);

            $celular.val(informacionSeguimiento.form_int_celular);

            $correo.val(informacionSeguimiento.form_int_email);

            $cantidadViven.val(informacionSeguimiento.form_int_cantpersonas);

            $motivoSospecha.val(informacionSeguimiento.form_int_motivosospecha);
            
            $inicioSintomas.val('');
            
            if ( informacionSeguimiento.form_int_iniciosintimas != undefined && informacionSeguimiento.form_int_iniciosintimas != null && informacionSeguimiento.form_int_iniciosintimas != '' && informacionSeguimiento.form_int_iniciosintimas != '0000-00-00' ) {

                const [anio, mes, dia] = informacionSeguimiento.form_int_iniciosintimas.split("-");

                $inicioSintomas.val(`${dia}-${mes}-${anio}`);
            
            }

            $slcEstadosIngreso.val(informacionSeguimiento.form_int_estadoingreso);

            $slcAntecedentes.val(informacionSeguimiento.form_int_ant_epi);

            $slcDestinos.val(informacionSeguimiento.form_int_destino);

            $observaciones.val(informacionSeguimiento.form_int_observacion);

        }



        function _rellenarInformacionTomaMuestra ( ) {

            $estadoTomaMuestra.val(estadoTomaMuestra = informacionTomaMuestra.estado_muestra);

            $lugarTomaMuestra.val(informacionTomaMuestra.lugar_toma_muestra);

            $muestraTomadaPor.val(informacionTomaMuestra.tomada_por_muestra);

            const [anio, mes, dia] = informacionTomaMuestra.fecha_toma_muestra.split("-");

            $fechaMuestra.val(`${dia}-${mes}-${anio}`);

            ( informacionTomaMuestra.covid_solicita_muestra == 'S' ) ? _asignarValorCheckBox('frm_examenCovid19', true, 'S') : _asignarValorCheckBox('frm_examenCovid19', false, 'N');

            ( informacionTomaMuestra.ifi_solicita_muestra == 'S' ) ? _asignarValorCheckBox('frm_examenCovid19IFI', true, 'S') : _asignarValorCheckBox('frm_examenCovid19IFI', false, 'N');

            ( informacionTomaMuestra.Broncoalveolar == 'S' ) ? _asignarValorCheckBox('frm_muestraLavadoBroncoalveolar', true, 'S') : _asignarValorCheckBox('frm_muestraLavadoBroncoalveolar', false, 'N');

            ( informacionTomaMuestra.Esputo == 'S' ) ? _asignarValorCheckBox('frm_muestraEsputo', true, 'S') : _asignarValorCheckBox('frm_muestraEsputo', false, 'N');

            ( informacionTomaMuestra.Aspirado_Traqueal == 'S' ) ? _asignarValorCheckBox('frm_muestraAspiradoTraqueal', true, 'S') : _asignarValorCheckBox('frm_muestraAspiradoTraqueal', false, 'N');

            ( informacionTomaMuestra.Aspirado_Nasofaringeo == 'S' ) ? _asignarValorCheckBox('frm_muestraAspiradoNasofaringeo', true, 'S') : _asignarValorCheckBox('frm_muestraAspiradoNasofaringeo', false, 'N');

            ( informacionTomaMuestra.torulas_nasofaringeas == 'S' ) ? _asignarValorCheckBox('frm_muestraTorulasNasofaringeas', true, 'S') : _asignarValorCheckBox('frm_muestraTorulasNasofaringeas', false, 'N');

            ( informacionTomaMuestra.tejido_pulmonar == 'S' ) ? _asignarValorCheckBox('frm_muestraTejidoPulmonar', true, 'S') : _asignarValorCheckBox('frm_muestraTejidoPulmonar', false, 'N');
            
            ( informacionTomaMuestra.muestra_embarazada == '' || informacionTomaMuestra.muestra_embarazada == null ) ? $slcEmbarazada.val('N') : $slcEmbarazada.val(informacionTomaMuestra.muestra_embarazada);           
            
        }
        
        
        
        function _rellenarResultadosAnteriores ( ) {
        
            _destruirTabla();

            $.each(resultadosAnteriores, (index) => {

                let html = "";
                
                const omega = _existeOmega(index);
                
                const [anio, mes, dia] = resultadosAnteriores[index].fecha_toma_muestra.split("-");
                
                html = `<tr>

                            <td scope="col" class="my-1 py-1 mx-1 px-1 mifuente text-center">${omega}</td>
                            
                            <td scope="col" class="my-1 py-1 mx-1 px-1 mifuente text-center">${dia}-${mes}-${anio}</td>
                            
                            <td scope="col" class="my-1 py-1 mx-1 px-1 mifuente text-center">${resultadosAnteriores[index].est_descripcion}</td>

                        </tr>
                        `;

                $(`${tablaResultadosAnteriores} > tbody`).append(html);

            });
            
            $divResultadosAnteriores.show();
        
        }



        function _rellenarSelects ( objetoArray, objetoSLC ) {

            $.each(objetoArray, (index) => {

                const [id, descripcion] = Object.values(objetoArray[index]);

                objetoSLC.append($('<option>', {

                    value: id,

                    text: descripcion

                }));

            });

        }



        function _validarCampos ( ) {

            validar('#frm_seguimientoNombre', "letras");

            validar('#frm_seguimientoEdad', "numero");

            validar('#frm_seguimientoNacionalidad', "letras");

            validar('#frm_seguimientoPaisResidencia', "letras");

            validar('#frm_seguimientoLugarTrabajo', "letras_numeros");

            validar('#frm_seguimientoTelefono', "numero");

            validar('#frm_seguimientoCelular', "numero");

            validar('#frm_seguimientoCorreo', "correo");

            validar('#frm_lugarTomaMuestra', "letras_numeros");

            validar('#frm_muestraTomadaPor', "letras");

            validar('#frm_seguimientoCantidadViven', "numero");

        }



        function _valorExiste(valor) {

            return (valor !== '' && valor !== 0 && valor !== null && valor !== undefined) ? true : false;

        }



        function _valoresCheckboxes ( ) {

            $('input[name="frm_examenCovid19"]').on('change', function(){

                if($(this).is(':checked')) {

                    $(this).val('S');

                    $('input[name="frm_examenCovid19IFI"]').val('N');

                    $('input[name="frm_examenCovid19IFI"]').prop("checked", false);

                } else {

                    $(this).val('N');

                }

            });

            $('input[name="frm_examenCovid19IFI"]').on('change', function(){

                if($(this).is(':checked')) {

                    $(this).val('S');

                    $('input[name="frm_examenCovid19"]').val('N');

                    $('input[name="frm_examenCovid19"]').prop("checked", false);

                } else {

                    $(this).val('N');

                }

            });

            $('input[name="frm_muestraLavadoBroncoalveolar"]').on('change', function(){

                if($(this).is(':checked')) {

                    $(this).val('S');

                } else {

                    $(this).val('N');

                }

            });

            $('input[name="frm_muestraEsputo"]').on('change', function(){

                if($(this).is(':checked')) {

                    $(this).val('S');

                } else {

                    $(this).val('N');

                }

            });

            $('input[name="frm_muestraAspiradoTraqueal"]').on('change', function(){

                if($(this).is(':checked')) {

                    $(this).val('S');

                } else {

                    $(this).val('N');

                }

            });

            $('input[name="frm_muestraAspiradoNasofaringeo"]').on('change', function(){

                if($(this).is(':checked')) {

                    $(this).val('S');

                } else {

                    $(this).val('N');

                }

            });

            $('input[name="frm_muestraTorulasNasofaringeas"]').on('change', function(){

                if($(this).is(':checked')) {

                    $(this).val('S');

                } else {

                    $(this).val('N');

                }

            });

            $('input[name="frm_muestraTejidoPulmonar"]').on('change', function(){

                if($(this).is(':checked')) {

                    $(this).val('S');

                } else {

                    $(this).val('N');

                }

            });


        }



        function _verificarDatos ( ) {

            let bandera = true

            if ( ! _valorExiste($direccion.val()) ) {

                $("#frm_seguimientoDireccion").assert(false, 'Ingrese Dirección');

                bandera = false;

            }

            if ( ! _valorExiste($lugarTomaMuestra.val()) ) {

                $("#frm_lugarTomaMuestra").assert(false, 'Ingrese Lugar');

                bandera = false;

            }

            if ( ! _valorExiste($fechaMuestra.val()) ) {

                $("#frm_fechaMuestra").assert(false, 'Ingrese Fecha Muestra');

                bandera = false;

            }

            if ( ! $('input[name="frm_examenCovid19"]').is(':checked') && ! $('input[name="frm_examenCovid19IFI"]').is(':checked') ) {

                $('input[name="frm_examenCovid19IFI"]').assert(false, 'Seleccione Examen');

                bandera = false;

            }

            if ( ! $('input[name="frm_muestraLavadoBroncoalveolar"]').is(':checked') && ! $('input[name="frm_muestraEsputo"]').is(':checked') && ! $('input[name="frm_muestraAspiradoTraqueal"]').is(':checked') && ! $('input[name="frm_muestraAspiradoNasofaringeo"]').is(':checked') && ! $('input[name="frm_muestraTorulasNasofaringeas"]').is(':checked') && ! $('input[name="frm_muestraTejidoPulmonar"]').is(':checked') ) {

                $('input[name="frm_muestraTejidoPulmonar"]').assert(false, 'Seleccione Muestra');

                bandera = false;

            }

            if ( ! _valorExiste($cantidadViven.val()) ) {

                $("#frm_seguimientoCantidadViven").assert(false, 'Ingrese Cantidad Personas');

                bandera = false;

            }

            if ( ! _valorExiste($motivoSospecha.val()) ) {

                $("#frm_seguimientoMotivoSospecha").assert(false, 'Ingrese Motivo Sospecha');

                bandera = false;

            }

            if ( ! _valorExiste($slcEstadosIngreso.val()) ) {

                $("#frm_seguimientoEstadoIngreso").assert(false, 'Ingrese Estado Ingreso');

                bandera = false;

            }

            if ( ! _valorExiste($slcAntecedentes.val()) ) {

                $("#frm_seguimientoAntecedentesEpidemiologicos").assert(false, 'Ingrese Antecedentes');

                bandera = false;

            }

            if ( ! _valorExiste($slcDestinos.val()) ) {

                $("#frm_seguimientoDestino").assert(false, 'Ingrese Destino');

                bandera = false;

            }

            return bandera;

        }



        function _verificarEstadoMuestra ( ) {
        
            if ( estadoTomaMuestra != 8 && estadoTomaMuestra != 9 && estadoTomaMuestra != 10 ) {

                return;

            }

            $lugarTomaMuestra.attr('readonly', 'readonly');

            $muestraTomadaPor.attr('readonly', 'readonly');

            $fechaMuestra.attr('readonly', 'readonly');
            
            $('[type="radio"]').on("click", function(){return false});
            
            $('[type="checkbox"]').on("click", function(){return false});
                        
            $cantidadViven.attr('readonly', 'readonly');

            $motivoSospecha.attr('readonly', 'readonly');

            $inicioSintomas.attr('readonly', 'readonly');

            $('#frm_seguimientoEstadoIngreso option:not(:selected)').attr('disabled',true);

            $('#frm_seguimientoAntecedentesEpidemiologicos option:not(:selected)').attr('disabled',true);

            $('#frm_seguimientoDestino option:not(:selected)').attr('disabled',true);

            $observaciones.attr('readonly', 'readonly');
            
            $('#frm_seguimientoEmbarazada option:not(:selected)').attr('disabled',true);

        }



        //Funciones públicas
        function actualizarFormulario ( ) {

            _buscarInfoSeguimiento();

            _buscarInfoTomaMuestra();

            _verificarEstadoMuestra();

            _actualizarFormulario();

        }




        function iniciarSeguimiento ( ) {
        
            $divResultadosAnteriores.hide();
            
            _buscarResultadosAnteriores();

            _buscarInfoPaciente();

            _iniciarSelects(estadosIngreso, $slcEstadosIngreso, 'obtenerEstadosIngreso');

            _iniciarSelects(antecedentesEpidemiologicos, $slcAntecedentes, 'obtenerAntecedentesEpidemiologicos');

            _iniciarSelects(destinos, $slcDestinos, 'obtenerDestinos');

            _iniciarFechaInicioSintomas();

            _validarCampos();

            _valoresCheckboxes();

        }



        function guardarSeguimiento ( ) {

            _guardarSeguimiento();

        }



        return {

            actualizarFormulario    : actualizarFormulario,
            iniciarSeguimiento      : iniciarSeguimiento,
            guardarSeguimiento      : guardarSeguimiento

        }

    })();

    seguimientoEnfermedadRespiratoria.iniciarSeguimiento();
    seguimientoEnfermedadRespiratoria.actualizarFormulario();
    seguimientoEnfermedadRespiratoria.guardarSeguimiento();

});