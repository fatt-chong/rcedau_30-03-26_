$(document).ready(function(){

    const detalleTiemposLaboratorio = ( function detalleTiemposLaboratorio ( ) {

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
        const $btnBuscarDetalleTiemposLaboratorio       = $('#btnBuscarDetalleTiemposLaboratorio'),
              $btnEliminarDetalleTiemposLaboratorio     = $('#btnEliminarFiltroBusquedaDetalleTiemposLaboratorio'),
              $claseDetalleTiemposLaboratorioNumeroDAU  = $('.detalleTiemposLaboratorioDAU');
        
        //Funciones privadas
        function _buscarDetalleTiemposLaboratorio ( ) {

            if ( $rutPaciente.val() != '' ) {
    
                _verificarRut($rutPaciente.val());            
    
            } else {

                _irAPagina(1)
        
            }

        }

        function _desplegarDetalleNumeroDAU ( ) {

             $claseDetalleTiemposLaboratorioNumeroDAU.on('click', function(){

                let numeroDAU = $(this).attr('id'); 

                $(`#detalleTiemposLaboratorioDAU${numeroDAU}`).toggle('fast');

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

            ajaxContent(`${raiz}/views/modules/reportes/tiemposCRUrgencia/tiemposLaboratorio/detalleResumenTiemposLaboratorio.php`, $("#frm_despliegueParametrosBusquedaTiemposLaboratorio").serialize()+parametros,'#divDespliegueDetalleTiemposLaboratorio');

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
        function buscarDetalleTiemposLaboratorio ( ) {

            $btnBuscarDetalleTiemposLaboratorio .on('click', _buscarDetalleTiemposLaboratorio);

        }

        function desplegarDetalleNumeroDAU ( ) {

            _desplegarDetalleNumeroDAU();

        }

        function eliminarFiltrosBusqueda ( ) {

            $btnEliminarDetalleTiemposLaboratorio.on('click', _eliminarFiltroBusqueda);

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
        
            buscarDetalleTiemposLaboratorio  : buscarDetalleTiemposLaboratorio,
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

    detalleTiemposLaboratorio.validarCamposFormulario();
    detalleTiemposLaboratorio.formateoRUT();
    detalleTiemposLaboratorio.buscarDetalleTiemposLaboratorio();
    detalleTiemposLaboratorio.eliminarFiltrosBusqueda();
    detalleTiemposLaboratorio.primeraPagina();
    detalleTiemposLaboratorio.paginaPrevia();
    detalleTiemposLaboratorio.paginaSiguiente();
    detalleTiemposLaboratorio.ultimaPagina();
    detalleTiemposLaboratorio.desplegarDetalleNumeroDAU();
    enlaceBoton();

});