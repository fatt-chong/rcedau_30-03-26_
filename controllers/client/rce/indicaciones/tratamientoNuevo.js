$(document).ready(function(){

    let $eliminarTratamiento            = $(".eliminarTratamiento"),
        $btn_add_row_tratamientoNuevo   = $("#btn_add_row_tratamientoNuevo"),
        $frm_tratamientoNuevo           = $("#frm_tratamientoNuevo"),
        $slc_clasificacionTratamiento   = $("#slc_clasificacionTratamiento"),
        fila                            = '';

    // validar("#frm_tratamientoNuevo","letras_numeros_ampliado");


    $eliminarTratamiento.off();

    $(document).on('click', '.eliminarTratamiento', function () {
        $(this).closest('tr').remove();
    });




    $btn_add_row_tratamientoNuevo.on('click', function(event){

        if ( ! verificarDatosTratamiento() ) {

            return;

        }

        crearFila();

        $("#table_tratamientoNuevo tbody").append(fila);

        $frm_tratamientoNuevo.val("");

        $frm_tratamientoNuevo.focus();

        fila = "";

        $slc_clasificacionTratamiento.val(0);

    });



    function verificarDatosTratamiento ( ) {

       if ( $frm_tratamientoNuevo.val() == "" ) {

            $frm_tratamientoNuevo.require("Debe indicar alguna información para solicitar el <b>Examen</b>");

            return false;

       }

       if ( $slc_clasificacionTratamiento.val() == null ) {

            $("#slc_clasificacionTratamiento").assert(false,'Debe ingresar clasificación');

            return false;

        }

        return true;
    }



    function crearFila  ( ) {

        let idClasificacion = $slc_clasificacionTratamiento.val();

        let clasificacion   = $slc_clasificacionTratamiento.find('option:selected').text();

        let descr           = $frm_tratamientoNuevo.val().replace(/<\/?[^br][^>]*>/gi, "");

         
            descr           = descr.replace(/'/g, "&#39;");

            descr           = descr.replace(/"/g, "&#34;");

            descr           = descr.replace(/\n/g, "<br>");

        let eliminar        = `<button type='button' id="eli${descr}" class='btn btn-sm btn-outline-danger eliminarTratamiento col-lg-12'><i class="fas fa-trash"></i></button>`;

        let columna         = `<td class="frm_tratamientoNuevo_nombre my-1 py-1 mx-1 px-1 mifuente">${descr}</td>
                               <td class="frm_idClasificacion my-1 py-1 mx-1 px-1 mifuente" style="display:none">${idClasificacion}</td>
                               <td class="frm_clasificacionTratamiento my-1 py-1 mx-1 px-1 mifuente">${clasificacion}</td>
                               <td class="text-center my-1 py-1 mx-1 px-1 mifuente" >${eliminar}</td>`;

        fila                = `<tr id='${descr}' class='detalle'>${columna}</tr>`;

    }

});
