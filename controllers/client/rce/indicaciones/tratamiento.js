
$(document).ready(function(){

  let $selectpicker       = $('.selectpicker'),
      $comboProcedimiento = $("#comboProcedimiento"),
      $eliminarExamen     = $(".eliminarExamen");
      $mostrarTexto       = $(".mostrarTexto"),
      $idPaciente         = $("#idPaciente"),
      $idDau              = $("#idDau");

  let actualizarSeguimiento = false;

  $selectpicker.selectpicker({

    size: 12

  });



  $eliminarExamen.click(function(){

    $("#"+$(this).attr('id').replace('eli','')).remove();

  });

  $("#agregar_row").click(function(event){

    if ( $comboProcedimiento.val() == '0' ) {

      $("#comboProcedimiento").assert(false,'Debe Seleccionar Tratamiento');

      return;
    }

    let tablaProce    = $('#table_Tratamiento >tbody >tr').length;
    let preCod        = $comboProcedimiento.val();
    let nombre_proce  = $("#comboProcedimiento option:selected").text();
    let band;

    if  ( tablaProce  >=  1 ) {

      band = 2;

      $("#contenidoTratamiento tr").each(function(){


        if ( preCod == $(this).attr('id') ) {

          band = 1;

          $comboProcedimiento.assert(false,'Ya se encuentra en la lista este examen');

          return false;

        }

      });

      if  ( band == 2 ) {

        fila = crearFila(preCod,  nombre_proce);

        $(fila).hide().appendTo("#contenidoTratamiento").fadeIn("");

        $(".eliminarExamen").off();

        $(".eliminarExamen").click(function(){

            idTexto   = $(this).attr('id').replace('eli', '');
            $("#"+$(this).attr('id').replace('eli','')).remove();
            $("#area"+idTexto).remove();
            if ( idTexto == '01' ) ingresoExamenCovid = false;

        });
        $(".mostrarTexto").click(function(){

        textoArea   = $(this).attr('id').replace('texto', '');
          if (this.checked) {
              $('#area'+textoArea).removeClass( "oculto" );

          } else {
              $('#Areatexto'+textoArea).val('');
              $('#area'+textoArea).addClass('oculto');
          }

      });

      }

    } else {

      fila = crearFila(preCod,  nombre_proce);

      $(fila).hide().appendTo("#contenidoTratamiento").fadeIn("");

      $(".eliminarExamen").off();

      $(".eliminarExamen").click(function(){
        idTexto   = $(this).attr('id').replace('eli', '');
        $("#"+$(this).attr('id').replace('eli','')).remove();
        $("#area"+idTexto).remove();
        if ( idTexto == '01' ) ingresoExamenCovid = false;

      });
      $(".mostrarTexto").click(function(){
          textoArea   = $(this).attr('id').replace('texto', '');
          if (this.checked) {
              $('#area'+textoArea).removeClass( "oculto" );
          } else {
              $('#Areatexto'+textoArea).val('');
              $('#area'+textoArea).addClass('oculto');
          }
      });

    }

    if ( preCod == '01' ) {

      desplegarFormularioSeguimiento();

    }

  });



  function desplegarFormularioSeguimiento ( ) {

    if ( resultadoExamenPositivo() ) {

      $("#01").remove();

      return;

    }

    ingresoExamenCovid = true;

      const parametros = { 'idPaciente' : $idPaciente.val() , 'accion' : 'verificarSeguimientoEnfermedadRespiratoria' }

      const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/medico/main_controller.php`, parametros, 'POST', 'JSON', 1, '');

      if ( respuestaAjaxRequest != null && respuestaAjaxRequest.length != 0 ) {

          actualizarSeguimiento = true;
          modalSeguimientoEnfermedadRespiratoria();
          // texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-times throb2 text-danger" style="font-size:29px"></i> Alerta en Registrar Seguimiento </h4>  <hr>  <p class="mb-0">Alerta en resgistrar seguimiento enfermedad respiratoria: <b>Paciente ya cuenta con un registro de seguimiento</b></p></div>';

          // modalConfirmacion("<label class='mifuente'>Advertencia</label>", texto, "primary", modalSeguimientoEnfermedadRespiratoria);

                    // modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                    // modalSeguimientoEnfermedadRespiratoria();
          // modalMensajeBtnExit('Alerta en Registrar Seguimiento', `Alerta en resgistrar seguimiento enfermedad respiratoria: <b>Paciente ya cuenta con un registro de seguimiento</b>`, "alerta_registrar_seguimiento_enfermedad_respiratoria", 500, 300, 'warning', modalSeguimientoEnfermedadRespiratoria);

          return;

      }

      modalSeguimientoEnfermedadRespiratoria();

    }



    function modalSeguimientoEnfermedadRespiratoria ( ) {

        let botones =   [
                            { id: 'btnGuardarSeguimiento', value: '<i class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></i> Guardar', class: 'btn btn-primary btnPrint' }
                        ];

        if ( actualizarSeguimiento == true ) {

            botones =   [
                            { id: 'btnActualizarSeguimiento', value: '<i class="glyphicon glyphicon-refresh" aria-hidden="true"></i> Actualizar', class: 'btn btn-primary btnPrint' }
                        ];

        }
        // modalFormulario(titulo, url, parametros, idModal, tamaño, clase, icono, botones, funcionSalir){

        modalFormulario("<label class='mifuente ml-2'>Formulario Seguimiento Enfermedad Respiratoria</label>", `${raiz}/views/modules/rce/indicaciones/seguimientoEnfermedadRespiratoria.php`, `idPaciente=${$idPaciente.val()}&idDau=${$idDau.val()}`, "#formularioSeguimiento3", "modal-lg", "primary",'', botones);

        // $('.modal-body').css('max-height','calc(100vh - 210px)');

        // $('.modal-body').css('overflow','auto');

    }



    function resultadoExamenPositivo ( ) {
      const parametros            = { 'idPaciente' : $idPaciente.val() , 'accion' : 'verificarExamenPositivo' }
      const respuestaAjaxRequest  = ajaxRequest(`${raiz}/controllers/server/medico/main_controller.php`, parametros, 'POST', 'JSON', 1, '');
      console.log(respuestaAjaxRequest);
      if ( (respuestaAjaxRequest == null || respuestaAjaxRequest.length == 0 ) || respuestaAjaxRequest.Tiempo_transcurrido > 60) {
        return false;
      }
      // respuestaAjaxRequest.estadoMuestra = 3;
      // respuestaAjaxRequest.estadoFormulario = 3;
      if ( respuestaAjaxRequest.estadoMuestra == 3 && respuestaAjaxRequest.estadoFormulario == 3 ) {

        texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-edit mr-2" style="color: #5a8dc5; font-size: 26px"></i>Alerta en indicar muestra de PCR </h4><hr><p><center>No es posible indicar muestra de PCR debido a que el paciente se <br>encuentra <b>actualmente activo para COVID-19</b><br>Fecha toma de muestra: ${respuestaAjaxRequest.fechaTomaMuestra}, Fecha resultado: '+respuestaAjaxRequest.fechaResultadoMuestra+'</center>.</p> </div>';
        modalMensajNoCabecera('Exito',texto,  "#modal", "modal-md", "success");

        return true;

      }

      if ( respuestaAjaxRequest.estadoMuestra == 3 && respuestaAjaxRequest.estadoFormulario == 4 && respuestaAjaxRequest.Tiempo_transcurrido < 60) {
        texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-edit mr-2" style="color: #5a8dc5; font-size: 26px"></i>Alerta en indicar muestra de PCR </h4><hr><p><center>No es posible indicar muestra de PCR debido a que el paciente se <br>encuentra <b>recientemente recuperado para COVID-19</b><br>Fecha toma de muestra:'+respuestaAjaxRequest.fechaTomaMuestra+', Fecha resultado: '+respuestaAjaxRequest.fechaResultadoMuestra+'</center>.</p> </div>';
        modalMensajNoCabecera('Exito',texto,  "#modal", "modal-md", "success");

        // modalMensajeBtnExit('Alerta en indicar muestra de PCR', `<big><big><big><center>No es posible indicar muestra de PCR debido a que el paciente se <br>encuentra <b>recientemente recuperado para COVID-19</b><br>Fecha toma de muestra: ${respuestaAjaxRequest.fechaTomaMuestra}, Fecha resultado: ${respuestaAjaxRequest.fechaResultadoMuestra}</center></big></big></big>`, "errorIndicarPCR", 500, 300, 'warning', '');

        return true;

      }

    }



  function crearFila ( preCod,  nombre_proce ) {

    let cod_Proce     = '', nom_proce = '', eliminar = '', fila = '';

    cod_Proce  = `<td hidden class='trata_codigo my-1 py-1 mx-1 px-1 mifuente'>${preCod}</<td>`;
    nom_proce  = `<td class='trata_nombre my-1 py-1 mx-1 px-1 mifuente'>${nombre_proce} </td><td><div > <label class="checkbox-inline my-1 py-1 mx-1 px-1 mifuente" > <input type="checkbox" id='texto${preCod}' name='texto${preCod}' class="mostrarTexto" value="1"> </label> <label style="margin-bottom:0px !important;"class="mifuente" > Realizado</label></div>
</div> </td>`;

    eliminar   = `<td class='my-1 py-1 mx-1 px-1 mifuente' ><button type='button' id='eli${preCod}' class='btn btn btn-sm btn-outline-danger  mifuente col-lg-12 eliminarExamen'><i class="fas fa-trash"></i></button></td>`;
    fila       = `<tr id='${preCod}' class='detalle'>${cod_Proce}${nom_proce}${eliminar}</tr><tr id='area${preCod}' class='oculto'> <td class="trataTexto" colspan="3" > <textarea  class="form-control form-control-sm mifuente " id='Areatexto${preCod}'  ></textarea> </td></tr>`;

    return fila;

  }

});
