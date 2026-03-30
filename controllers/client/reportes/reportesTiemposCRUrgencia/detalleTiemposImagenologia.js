$(document).ready(function(){

    const detalleTiemposImagenologia = ( function detalleTiemposImagenologia ( ) {

        //Declaración variables obtenidas del formulario
        const   $numeroDAU                                = $('#frm_numeroDAU'),
                $tipoAtencion                             = $('#frm_tipoAtencion'),
                $nombrePaciente                           = $('#frm_nombrePaciente'),
                $rutPaciente                              = $('#frm_rutPaciente');

        //Declaración variables para paginación
        const   $primeraPagina                            = $("#primero_l"),
                $paginaPrevia                             = $("#atras_l"),
                $paginaSiguiente                          = $("#siguiente_l"),
                $ultimaPagina                             = $("#ultimo_l"),
                totalPag                                  = $("#totalPag").val();

        //Declaración de variables 
        const $btnBuscarDetalleTiemposImagenologia      = $('#btnBuscarDetalleTiemposImagenologia'),
              $btnEliminarDetalleTiemposImagenologia    = $('#btnEliminarFiltroBusquedaDetalleTiemposImagenologia'),
              $claseDetalleTiemposImagenologiaNumeroDAU = $('.detalleTiemposImagenologiaDAU');
        
        //Funciones privadas
        function _buscarDetalleTiemposImagenologia ( ) {

            if ( $rutPaciente.val() != '' ) {
    
                _verificarRut($rutPaciente.val());            
    
            } else {

                _irAPagina(1)
        
            }

        }

        function _desplegarDetalleNumeroDAU ( ) {

             $claseDetalleTiemposImagenologiaNumeroDAU.on('click', function(){

                let numeroDAU = $(this).attr('id'); 

                $(`#detalleTiemposImagenologiaDAU${numeroDAU}`).toggle('fast');

            });

        }

        function _eliminarFiltroBusqueda ( ) {

            unsetSesion();

            $numeroDAU.val('');    

            $nombrePaciente.val(''); 

            $rutPaciente.val('');  

            $tipoAtencion.val('');      

            _irAPagina(1);
        
        }

        function _irAPagina ( accionPagina ) {

            const parametros = `&accion=${accionPagina}&totalPag=${totalPag}`;

            ajaxContent(`${raiz}/views/reportes/tiemposCRUrgencia/tiemposImagenologia/detalleResumenTiemposImagenologia.php`, $("#frm_despliegueParametrosBusquedaTiemposImagenologia").serialize()+parametros,'#divDespliegueDetalleTiemposImagenologia','', true);

        }

        function _verificarRut ( rut ) {

            let rutValido = $.Rut.validar(rut);
                
            if ( rutValido == false ) {	
            
                $rutPaciente.assert(false,'El Run Ingresado, no es válido');	
            
            } else {

                rut     = $.Rut.quitarFormato(rut);
        
                rut     = rut.substring(0, rut.length-1);

                $rutPaciente.val(rut);

                _irAPagina(1);
            
            }

        }



        //Funciones públicas
        function buscarDetalleTiemposImagenologia ( ) {

            $btnBuscarDetalleTiemposImagenologia.on('click', _buscarDetalleTiemposImagenologia);

        }

        function desplegarDetalleNumeroDAU ( ) {

            _desplegarDetalleNumeroDAU();

        }

        function eliminarFiltrosBusqueda ( ) {

            $btnEliminarDetalleTiemposImagenologia.on('click', _eliminarFiltroBusqueda);

        }

        function formateoRUT ( ) {

            $rutPaciente.Rut ( {
                
                on_error: function ( ) { 
                    
                    return false;
                
                },

                on_success: function ( ) {
        
                },

                format_on: 'keyup'
            
            });
            
        }

        function primeraPagina ( ) {

            $primeraPagina.click(function(){

                _irAPagina(4);

            
            });

        }

        function paginaPrevia ( ) {

            $paginaPrevia.click(function(){

                _irAPagina(2);
            
            });

        }

        function paginaSiguiente ( ) {

            $paginaSiguiente.click(function(){

                _irAPagina(3);
            
            });

        }

        function ultimaPagina ( ) {

            $ultimaPagina.click(function(){

                _irAPagina(5);
            
            });

        }

        function validarCamposFormulario ( ) {
           
            validar("#frm_numeroDAU","numero");

            validar("#frm_nombrePaciente","letras");

            validar("#frm_rutPaciente","rut");

        }


        
        //Retorno de objeto
        return {
        
            buscarDetalleTiemposImagenologia : buscarDetalleTiemposImagenologia,
            desplegarDetalleNumeroDAU        : desplegarDetalleNumeroDAU,
            eliminarFiltrosBusqueda          : eliminarFiltrosBusqueda,
            formateoRUT                      : formateoRUT,
            primeraPagina                    : primeraPagina,
            paginaPrevia                     : paginaPrevia,
            paginaSiguiente                  : paginaSiguiente,
            ultimaPagina                     : ultimaPagina,
            validarCamposFormulario          : validarCamposFormulario
        
        }


    })();

    detalleTiemposImagenologia.validarCamposFormulario();
    detalleTiemposImagenologia.formateoRUT();
    detalleTiemposImagenologia.buscarDetalleTiemposImagenologia();
    detalleTiemposImagenologia.eliminarFiltrosBusqueda();
    detalleTiemposImagenologia.primeraPagina();
    detalleTiemposImagenologia.paginaPrevia();
    detalleTiemposImagenologia.paginaSiguiente();
    detalleTiemposImagenologia.ultimaPagina();
    detalleTiemposImagenologia.desplegarDetalleNumeroDAU();
    enlaceBoton();

});