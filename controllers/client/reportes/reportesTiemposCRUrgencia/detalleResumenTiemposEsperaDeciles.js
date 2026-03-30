$(document).ready(function(){

    const detalleResumenTiemposEsperaDeciles = ( function detalleResumenTiemposEsperaDeciles ( ) {
        
        //Declaración variables obtenidas del formulario
        const   $numeroDAU                                = $('#frm_numeroDAU'),
                $nombrePaciente                           = $('#frm_nombrePaciente'),
                $rutPaciente                              = $('#frm_rutPaciente'),                
                $numeroDecil                              = $('#frm_numeroDecil'); 

        //Declaración variables para paginación
        const   $primeraPagina                            = $("#primero_l"),
                $paginaPrevia                             = $("#atras_l"),
                $paginaSiguiente                          = $("#siguiente_l"),
                $ultimaPagina                             = $("#ultimo_l"),
                totalPag                                  = $("#totalPag").val();
                
        //Declaración de variables 
        const $btnBuscarDetalleResumenTiemposEsperaDeciles      = $('#btnBuscarDetalleResumenTiemposEsperaDeciles'),
              $btnEliminarDetalleResumenTiemposEsperaDeciles    = $('#btnEliminarFiltroBusquedaDetalleResumenTiemposEsperaDeciles');

                



        //Funciones privadas
        function _buscarDetalleresumenTiemposEsperaDeciles ( ) {

            if ( $rutPaciente.val() != '' ) {
    
                _verificarRut($rutPaciente.val());            
    
            } else {

                _irAPagina(1)
        
            }

        }

        function _eliminarFiltroBusqueda ( ) {

            unsetSesion();

            $numeroDAU.val('');    

            $nombrePaciente.val(''); 

            $rutPaciente.val('');    

            $numeroDecil.val('');     

            _irAPagina(1);
        
        }

        function _irAPagina ( accionPagina ) {

            const parametros = `&accion=${accionPagina}&totalPag=${totalPag}`;

            ajaxContent(`${raiz}/views/modules/reportes/tiemposCRUrgencia/resumenTiemposEsperaDeciles/detalleResumenTiemposEsperaDeciles.php`, $("#frm_despliegueParametrosBusquedaDetalleResumenTiemposEsperaDeciles").serialize()+parametros,'#divDespliegueDetalleResumenTiemposEsperaDeciles');

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
        function buscarDetalleResumenTiemposEsperaDeciles ( ) {

            $btnBuscarDetalleResumenTiemposEsperaDeciles.on('click', _buscarDetalleresumenTiemposEsperaDeciles);

        }

        function eliminarFiltrosBusqueda ( ) {

            $btnEliminarDetalleResumenTiemposEsperaDeciles.on('click', _eliminarFiltroBusqueda);

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
        
            buscarDetalleResumenTiemposEsperaDeciles    : buscarDetalleResumenTiemposEsperaDeciles,
            eliminarFiltrosBusqueda                     : eliminarFiltrosBusqueda,
            formateoRUT                                 : formateoRUT,
            primeraPagina                               : primeraPagina,
            paginaPrevia                                : paginaPrevia,
            paginaSiguiente                             : paginaSiguiente,
            ultimaPagina                                : ultimaPagina,
            validarCamposFormulario                     : validarCamposFormulario
        
        }


    })();

    detalleResumenTiemposEsperaDeciles.validarCamposFormulario();
    detalleResumenTiemposEsperaDeciles.formateoRUT();
    detalleResumenTiemposEsperaDeciles.buscarDetalleResumenTiemposEsperaDeciles();
    detalleResumenTiemposEsperaDeciles.eliminarFiltrosBusqueda();
    detalleResumenTiemposEsperaDeciles.primeraPagina();
    detalleResumenTiemposEsperaDeciles.paginaPrevia();
    detalleResumenTiemposEsperaDeciles.paginaSiguiente();
    detalleResumenTiemposEsperaDeciles.ultimaPagina();
    enlaceBoton();

});