$(document).ready(function(){

    let otro_id     = 0;
    let otro_id_temp= [];

    // validar("#frm_area_otros","letras_numeros_ampliado");

    $(document).on('click', '.borrar', function (event) {

        event.preventDefault();

        $(this).closest('tr').remove();

    });



    $(".eliminarOtros").off();



    $(".eliminarOtros").click(function(){

        $("#"+$(this).attr('id').replace('eli','')).remove();

        let indice  = $.inArray( $(this).attr('id').replace('eli',''), otro_id_temp);

        otro_id_temp.splice(indice,1);

    });



    $("#btn_add_row").click(function(event){

        let descr           = $('#frm_area_otros').val().replace(/<\/?[^br][^>]*>/gi, "");

            descr           = descr.replace(/'/g, "&#39;");

            descr           = descr.replace(/"/g, "&#34;");

            descr           = descr.replace(/\n/g, "<br>");

        // <button type="button" id='eli<?=$datos_contendidoCargadoCarroOtros[$i][0]?>'  type="button" class="btn btn btn-sm btn-outline-danger  mifuente col-lg-12 borrar"  ><i class="fas fa-trash"></i></button>

        let eliminar    = `<button type='button' id='eli${descr}' class='btn btn btn-sm btn-outline-danger  mifuente col-lg-12 borrar'><i class="fas fa-trash"></i></button>`;
        let columna     = `<td class='otro_nombre my-1 py-1 mx-1 px-1 mifuente'>${descr}</td><td class='my-1 py-1 mx-1 px-1 mifuente'>${eliminar}</td>`;
        let fila        = `<tr id='${descr}' class='detalle'>${columna}</tr>`;

        if ( descr == "" ) {

            $('#frm_area_otros').require("Debe indicar alguna informacion para solicitar el <b>Examen</b>");

            return false;

        } else {

            $("#table_Otros tbody").append(fila);

            $(".eliminarOtros").off();

            $(".eliminarOtros").click(function(){

                $("#"+$(this).attr('id').replace('eli','')).remove();

                var indice  = $.inArray( $(this).attr('id').replace('eli',''), otro_id_temp);

                otro_id_temp.splice(indice,1);

            });

            $("#frm_area_otros").val("");

            $("#frm_area_otros").focus();

            fila = "";

            otro_id++;

        }

    });

});
