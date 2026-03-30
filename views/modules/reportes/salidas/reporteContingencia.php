<?php

require_once("../../../config/config.php");

?>

<script src="<?=PATH?>/assets/libs/jQuery/jquery-3.1.0.min.js"></script>

<script type="text/javascript" src="<?=PATH?>/assets/js/main.js"></script>

<script>

    $(document).ready(function(){

        const reporteContingencia = ( function reporteContingencia ( ) {

            //Variables
            const urlInicioSesion    = "https://contingencia.minsal.cl/api/login",
                  urlEnvioParametros = "https://contingencia.minsal.cl/api/contingencias";

            let   informacionDAUSContingencia = {};





            //Funciones privadas
            function _obtenerInformacionDAUS ( ) {

                const parametrosAEnviar = { 'accion': 'obtenerInformacionDAUSContingencia' };

                informacionDAUSContingencia = ajaxRequest(`${raiz}/controllers/server/reportes/main_controller.php`, parametrosAEnviar, 'POST', 'JSON', 1, '');

                if ( informacionDAUSContingencia.length == 0 || informacionDAUSContingencia == null ) {

                    return;

                }

                _mandarDatos();

            }



            function _inicioSesion ( ) {

                const parametros = { 'email' : 'rodrigo.altamirano@hjnc.cl' , 'password' : 'BUfpRWCRsNwUAUE' }

                $.ajax({

                    type        : "POST",

                    dataType    : "json",

                    headers     : {'Content-Type': 'application/x-www-form-urlencoded'},

                    contentType : 'application/x-www-form-urlencoded',

                    url         : urlInicioSesion,

                    data        : JSON.stringify(parametros),

                    success     : function ( data ) {

                                        console.log(data);

                                    }

                });

            }



            function _mandarDatos ( ) {

                for ( let i = 0; i < informacionDAUSContingencia.length; i++ ) {

                    const parametros = {
                                        'id_dau'             : informacionDAUSContingencia[i].id_dau,
                                        'id_paciente'        : informacionDAUSContingencia[i].id_paciente,
                                        'tipo_identificacion': informacionDAUSContingencia[i].tipo_identificacion,
                                        'fecha_nacimiento'   : informacionDAUSContingencia[i].fecha_nacimiento,
                                        'sexo'               : informacionDAUSContingencia[i].sexo,
                                        'fecha_adm'          : informacionDAUSContingencia[i].fecha_adm,
                                        'hora_adm'           : informacionDAUSContingencia[i].hora_adm,
                                        'fecha_atencion'     : informacionDAUSContingencia[i].fecha_admision,
                                        'hora_atencion'      : informacionDAUSContingencia[i].hora_atencion,
                                        'run_responsable'    : informacionDAUSContingencia[i].run_responsable,
                                        'dv_responsable'     : informacionDAUSContingencia[i].dv_responsable,
                                        'titulo_profesional' : informacionDAUSContingencia[i].titulo_profesional,
                                        'tipo_accidente'     : informacionDAUSContingencia[i].tipo_accidente
                                        };

                    $.ajax({

                        type        : "POST",

                        dataType    : "json",

                        headers     : {'Content-Type': 'application/x-www-form-urlencoded'},

                        contentType : 'application/x-www-form-urlencoded',

                        url         : urlEnvioParametros,

                        data        : JSON.stringify(parametros),

                        success     : function ( data ) {

                                            console.log(data);

                                        }

                    });

                }

            }




            //Funciones públicas
            function inicioReporteContingencia ( ) {

                _inicioSesion();

                _obtenerInformacionDAUS();

            }


            return {

                inicioReporteContingencia : inicioReporteContingencia

            }

        })();

        reporteContingencia.inicioReporteContingencia();

    });

</script>