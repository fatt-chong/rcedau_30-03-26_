$(document).ready(function(){

    $("#paciente_complejo").on( 'change', function() {

        $("#contenidoRayo tr").each(function(element){           
            
            if( $(this).find("td.ima_hidden").text() == 'S' && $('#paciente_urg').is(':checked') ) {
                
                $( "#paciente_complejo" ).prop( "checked", true );
                
                $( "#paciente_urg" ).prop( "checked", false );    
                
                return false;                    
            
            }
        
        });

        if ( $(this).is(':checked') ) {
            
            $('#paciente_urg').prop('checked', false); 
            
            $('#urgencia').hide('slow');
            
            $('#complejo').show('slow'); 
            
            let checkbox_buscar = '';
            
            let checkbox_encontrado= '';
            
            $('#frm_laboratorio_master2 input[type=checkbox]').each(function(){
               
                if ( this.checked ) {
                   
                    checkbox_buscar = $(this).val();
                
                    $('#frm_laboratorio_master input[type=checkbox]').each(function(){
                    
                        if ($(this).val() == checkbox_buscar) {
                       
                            checkbox_encontrado = $(this).val();
                       
                            $('#frm_laboratorio_master input[value='+$(this).val()+']').prop('checked',true);                         
                   
                        }
                    
                
                    });
               
                } 
            
            });

            if ( checkbox_encontrado != "" ) {
                
                $('#frm_laboratorio_master2')[0].reset();
            
            }

            return false;

        }

        $("#contenidoRayo tr").each(function(element){                        
        
            if ( $(this).find("td.ima_hidden").text() == 'N' ) {
        
                $( "#paciente_complejo" ).prop( "checked", true );
        
                $( "#paciente_urg" ).prop( "checked", false );                            
        
                return false;
        
            }

            $('#paciente_urg').prop('checked', false); 
           
            $('#urgencia').hide('slow');
           
            $('#complejo').show('slow');                    
       
        });

        let ban =0;
        
        if ( $('.checkPruebaComplejo').is(':checked') ) {

            $('#complejo').show(); 
            
            $('#paciente_complejo').prop('checked',false );                    
            
            $('#paciente_urg').prop('checked', true); 


            $("#frm_laboratorio_master").find("input:checked").each(function(element) {

                if ( $(this).attr('class') == 'N checkPruebaComplejo') { 

                    ban = 1;  

                }

            });
                

        } else {                    

            $('#urgencia').show('slow');
            
            $('#complejo').hide('slow');  
        
        }

        if ( ban == 1 ) {

            $('#complejo').show(); 
            
            $('#paciente_complejo').prop('checked', true); 
            
            $('#paciente_urg').prop('checked', false); 

            texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-edit mr-2" style="color: #5a8dc5; font-size: 26px"></i>¡ ATENCIÓN ! </h4><hr><p>Pretacion de paciente complejo.</p> </div>';
             modalMensajNoCabecera('Exito',texto,  "#modal", "modal-md", "success");

            // modalMensajeBtnExit('¡ ATENCIÓN !', 'pretacion de paciente complejo', 'msjErrorSocketIniAtencion', 400, 300, 'warning');
        
        } else {
            
            $('#urgencia').show('slow');
            
            $('#complejo').hide('slow');

            let checkbox_buscar = '';
            
            let checkbox_encontrado= '';
            
            $('#frm_laboratorio_master input[type=checkbox]').each(function(){

                if ( this.checked ) {

                    checkbox_buscar = $(this).val();
                    
                    $('#frm_laboratorio_master2 input[type=checkbox]').each(function(){

                        if ( $(this).val() == checkbox_buscar ) {
                            
                            checkbox_encontrado = $(this).val();

                            $('#frm_laboratorio_master2 input[value='+$(this).val()+']').prop('checked',true);                         

                        }
                        

                    });

                } 

            });

            if ( checkbox_encontrado != "" ) {

                $('#frm_laboratorio_master')[0].reset();

            }
   
        }                    

    });



    $("#paciente_urg").on( 'change', function() {
        
         $("#contenidoRayo tr").each(function(element){ 
            
            if ( $(this).find("td.ima_hidden").text() == 'N' ) {


                texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-edit mr-2" style="color: #5a8dc5; font-size: 26px"></i>¡ ATENCIÓN ! </h4><hr><p>Usted Posee al menos una Prestación de <b>Complejo</b> en Imagenologia, debe elminar la que tiene para cambiar de opción.</p> </div>';
                modalMensajNoCabecera('Exito',texto,  "#modal", "modal-md", "success");

                // modalMensajeBtnExit('¡ ATENCIÓN !', 'Usted Posee al menos una Prestación de <b>Complejo</b> en Imagenologia, debe elminar la que tiene para cambiar de opción', 'msjErrorSocketIniAtencion', 400, 300, 'warning');
                
                $( "#paciente_complejo" ).prop( "checked", true );
                
                $( "#paciente_urg" ).prop( "checked", false );

                return false;
            
            }
        
        });
        
        if ( $(this).is(':checked') ) { 
            
            let ban = 0;
           
            if ( $('.checkPruebaComplejo').is(':checked') ) {

                $('#complejo').show(); 
                
                $('#paciente_complejo').prop('checked',false );                    
                
                $('#paciente_urg').prop('checked', true); 

                $("#frm_laboratorio_master").find("input:checked").each(function(element) {
                    
                    if ( $(this).attr('class') == 'N checkPruebaComplejo' ) {  

                        ban = 1;  

                    }

                });                   

            } else {                    

                $('#urgencia').show('slow');
                
                $('#complejo').hide('slow');  
            
            }

            if ( ban == 1 ) {
                
                $('#complejo').show(); 
                
                $('#paciente_complejo').prop('checked', true); 
                
                $('#paciente_urg').prop('checked', false);
                texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-edit mr-2" style="color: #5a8dc5; font-size: 26px"></i>¡ ATENCIÓN ! </h4><hr><p>Prestación de paciente complejo.</p> </div>';
                modalMensajNoCabecera('Exito',texto,  "#modal", "modal-md", "success");

                // modalMensajeBtnExit('¡ ATENCIÓN !', 'pretacion de paciente complejo', 'msjErrorSocketIniAtencion', 400, 300, 'warning');
            
            } else {
                
                $('#urgencia').show('slow');
                
                $('#complejo').hide('slow');

                let checkbox_buscar = '';
                
                let checkbox_encontrado= '';
                
                $('#frm_laboratorio_master input[type=checkbox]').each(function(){

                    if ( this.checked ) {

                        checkbox_buscar = $(this).val();

                        $('#frm_laboratorio_master2 input[type=checkbox]').each(function(){

                            if ( $(this).val() == checkbox_buscar ) {

                                checkbox_encontrado = $(this).val();

                                $('#frm_laboratorio_master2 input[value='+$(this).val()+']').prop('checked',true);                         
                            
                            }
                            
                        });
                    } 

                });

                if ( checkbox_encontrado != "" ) {

                    $('#frm_laboratorio_master')[0].reset();
                
                }

            }

        } else {

            $('#paciente_complejo').prop('checked', true);
            
            $('#urgencia').hide('slow');
            
            $('#complejo').show('slow');
            
            let checkbox_buscar = '';
            
            let checkbox_encontrado= '';
            
            $('#frm_laboratorio_master2 input[type=checkbox]').each(function(){

               if ( this.checked ) {

                    checkbox_buscar = $(this).val();
                    
                    $('#frm_laboratorio_master input[type=checkbox]').each(function(){

                        if ( $(this).val() == checkbox_buscar ) {

                            checkbox_encontrado = $(this).val();

                            $('#frm_laboratorio_master input[value='+$(this).val()+']').prop('checked',true);                         

                        }
                        
                    });

                } 

             });

            if ( checkbox_encontrado != "" ) {

                $('#frm_laboratorio_master2')[0].reset();
            
            }
        
        }
    
    });   
    
    let check = $("#hiddenInv").children();

    check.click(function(){
        alert();
        let dau_id = $('#dau_id').val();
        
        let cerrarModal = function(){
            
            $('#invBact').modal('hide').data('bs.modal', null);
            
            check.prop("checked", false);
        
        }
        
        let funcionConfirmarRegistro = function(){
            
            $('#invBact').modal('hide').data('bs.modal', null);
            
            check.prop("checked", true);
        
        }

		let funcionRegistrar = function(){
            texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Confirmación de Solicitud </h4>  <hr>  <p class="mb-0">¿Está seguro que desea realizar la solicitud bactereológica</p></div>';
          modalConfirmacion("<label class='mifuente'>Advertencia</label>", texto, "primary", funcionConfirmarRegistro);

			// modalConfirmacion("Confirmación de Solicitud", "¿Está seguro que desea realizar la solicitud bactereológica?", funcionConfirmarRegistro);
        
        }
        
        let botones =   [
        	                { id: 'btn_inv_bact', value: 'Guardar', function: funcionRegistrar, class: 'btn btn-primary' }
                        ];

        if ( check.is(':checked') ) {
// C:\inetpub\wwwroot\RCEDAU\views\modules\rce\indicaciones\investigacion_indicaciones.php
        modalFormulario("<label class='mifuente ml-2'>Solicitud de Investigación Bactereológica de Tuberculosis</label>", `${raiz}/views/modules/rce/indicaciones/investigacion_indicaciones.php`, `dau_id=${$idDau.val()}`, "#invBact", "modal-lg", "light",'', botones);


        
        }

    });

});