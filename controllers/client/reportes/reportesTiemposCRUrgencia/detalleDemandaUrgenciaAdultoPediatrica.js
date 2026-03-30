$(document).ready(function(){

    const detalleDemandaUrgenciaAdultoPediatrica = ( function detalleDemandaUrgenciaAdultoPediatrica ( ) {

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
        const $btnBuscarDetalleDemandaUrgenciaAdultoPediatrica    = $('#btnBuscarDetalleDemandaUrgenciaAdultoPediatrica'),
              $btnEliminarDetalleDemandaUrgenciaAdultoPediatrica  = $('#btnEliminarFiltroBusquedaDetalleDemandaUrgenciaAdultoPediatrica');
        
        //Funciones privadas
        function _buscarDetalleDemandaUrgenciaAdultoPediatrico ( ) {

            if ( $rutPaciente.val() != '' ) {
    
                _verificarRut($rutPaciente.val());            
    
            } else {

                _irAPagina(1)
        
            }

        }

        function _eliminarFiltroBusqueda ( ) {

            unsetSesion();

            $numeroDAU.val('');    

            $tipoAtencion.val('');  

            $nombrePaciente.val(''); 

            $rutPaciente.val('');    

            _irAPagina(1);
        
        }

        function _irAPagina ( accionPagina ) {

            const parametros = `&accion=${accionPagina}&totalPag=${totalPag}`;
            ajaxContent(`${raiz}/views/modules/reportes/tiemposCRUrgencia/demandaUrgenciaAdultoPediatrica/detalleDemandaUrgenciaAdultoPediatrica.php`,$("#frm_despliegueParametrosBusquedaDetalleDemandaUrgenciaAdultoPediatrico").serialize()+parametros,'#divDetalleDemandaUrgenciaAdultoPediatrico');
            
            // ajaxContent(`${raiz}/views/reportes/tiemposCRUrgencia/demandaUrgenciaAdultoPediatrica/detalleDemandaUrgenciaAdultoPediatrica.php`, $("#frm_despliegueParametrosBusquedaDetalleDemandaUrgenciaAdultoPediatrico").serialize()+parametros,'#divDetalleDemandaUrgenciaAdultoPediatrico','', true);

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
        function buscarDetalleDemandaUrgenciaAdultoPediatrico ( ) {

            $btnBuscarDetalleDemandaUrgenciaAdultoPediatrica.on('click', _buscarDetalleDemandaUrgenciaAdultoPediatrico);

        }

        function eliminarFiltrosBusqueda ( ) {

            $btnEliminarDetalleDemandaUrgenciaAdultoPediatrica.on('click', _eliminarFiltroBusqueda);

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
        
            buscarDetalleDemandaUrgenciaAdultoPediatrico    : buscarDetalleDemandaUrgenciaAdultoPediatrico,
            eliminarFiltrosBusqueda                         : eliminarFiltrosBusqueda,
            formateoRUT                                     : formateoRUT,
            primeraPagina                                   : primeraPagina,
            paginaPrevia                                    : paginaPrevia,
            paginaSiguiente                                 : paginaSiguiente,
            ultimaPagina                                    : ultimaPagina,
            validarCamposFormulario                         : validarCamposFormulario
        
        }

    })();

    detalleDemandaUrgenciaAdultoPediatrica.validarCamposFormulario();
    detalleDemandaUrgenciaAdultoPediatrica.formateoRUT();
    detalleDemandaUrgenciaAdultoPediatrica.buscarDetalleDemandaUrgenciaAdultoPediatrico();
    detalleDemandaUrgenciaAdultoPediatrica.eliminarFiltrosBusqueda();
    detalleDemandaUrgenciaAdultoPediatrica.primeraPagina();
    detalleDemandaUrgenciaAdultoPediatrica.paginaPrevia();
    detalleDemandaUrgenciaAdultoPediatrica.paginaSiguiente();
    detalleDemandaUrgenciaAdultoPediatrica.ultimaPagina();
    enlaceBoton();

});