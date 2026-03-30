$(document).ready(function(){

    $('.BlockDeletion').on('keydown', function (e) {
        try {
            if ((e.keyCode == 8 || e.keyCode == 46))
                return false;
            else
                return true;
        }
        catch (Exception) {
            return false;
        }
    });

    validar('#frm_mes', 'fecha');

    function removerAviso() {
        $.validity.start();
        $.validity.end();
    }

    $.fn.datepicker.dates['es'] = {
		days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
		daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
		daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
		months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
		monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
		// today: "Hoy",
		monthsTitle: "Meses",
		clear: "Borrar",
		weekStart: 1,
		format: "mm-yyyy",
		startView: "months",
		minViewMode: "months"
	};
	$("#frm_mes").datetimepicker({
		pickTime: false,
		dateFormat: 'mm:yyyy',
		autoclose: true,
		startView: 1,
		minViewMode: 1,
        maxDate: "-1d",
	});
    $("#frm_mesdesde").datetimepicker({
        pickTime: false,
        dateFormat: 'mm:yyyy',
        autoclose: true,
        startView: 1,
        minViewMode: 1,
        maxDate: "-1d",
    });
    $("#frm_meshasta").datetimepicker({
        pickTime: false,
        dateFormat: 'mm:yyyy',
        autoclose: true,
        startView: 1,
        minViewMode: 1,
        maxDate: "-1d",
    });

    $('#frm_mes').datetimepicker().on('dp.change', function (event) {
        var fecha       = $("#frm_mes").val();
        const myArray   = fecha.split("-");
        $("#frm_mes").val(myArray[1]+'-'+myArray[2]);
    });

    $('#frm_mesdesde').datetimepicker().on('dp.change', function (event) {
        var fecha       = $("#frm_mesdesde").val();
        const myArray   = fecha.split("-");
        $("#frm_mesdesde").val(myArray[1]+'-'+myArray[2]);
    });

    $('#frm_meshasta').datetimepicker().on('dp.change', function (event) {
        var fecha       = $("#frm_meshasta").val();
        const myArray   = fecha.split("-");
        $("#frm_meshasta").val(myArray[1]+'-'+myArray[2]);
    });

    $( "#tipoReporte" ).change(function() {
        var tipoReporte = $('#tipoReporte :selected').val();
        if(tipoReporte == 6 ){
            $("#frm_mesdesde").val('');
            $('#frm_mesdesde').prop('disabled', false);
            $("#frm_meshasta").val('');
            $('#frm_meshasta').prop('disabled', false);
            $('#frm_mes').prop('disabled', true);
        }else if(tipoReporte == 4 || tipoReporte == 5 ){
            $("#frm_mes").val('');
            $('#frm_mes').prop('disabled', true);
            $("#frm_mesdesde").val('');
            $('#frm_mesdesde').prop('disabled', true);
            $("#frm_meshasta").val('');
            $('#frm_meshasta').prop('disabled', true);
        }else{
            $('#frm_mes').prop('disabled', false);
            $("#frm_mesdesde").val('');
            $('#frm_mesdesde').prop('disabled', true);
            $("#frm_meshasta").val('');
            $('#frm_meshasta').prop('disabled', true);
        }
    });
    $("#frm_mesdesde").val('');
            $('#frm_mesdesde').prop('disabled', true);
            $("#frm_meshasta").val('');
            $('#frm_meshasta').prop('disabled', true);
    $("#btn_generarReporte").click(function (event) {
        $.validity.start();
        var url  = raiz+'/views/modules/reportes/xls/xls_reporte_cie10_ges.php?frm_mes='+$('#frm_mes').val();
        result = $.validity.end();
        if(result.valid==false){
            return false;
        }
        $(document).ready(function (){
            $.blockUI({
                    baseZ: 1060,
                css: { 
                border: 'none', 
                padding: '15px', 
                backgroundColor: '#000', 
                '-webkit-border-radius': '10px', 
                '-moz-border-radius': '10px', 
                opacity: .5, 
                color: '#fff',
                fontSize:'16px'
                },
                message:'<div class="centerTable"><table><tr><td><label class="loadingBlock">Generando Excel... </label></td><td><img src="/estandar/assets/img/loading-5.gif" alt="Generando Excel ... " height="50" width="50"  /></td></tr></table></div>'
            });
        });
        fetch(url)
        .then(resp => {
            console.log("resp", resp);
            if(resp.ok){
                return resp.blob()
            }       
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            // the filename you want
            a.download = 'xls_gestion_reporte.xls';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            $.unblockUI()
        })
        .catch((e) => {
            console.log("e", e);
            modalMensaje('ATENCION','Ha ocurrido un error, comunicarse con <b>mesa de ayuda.',  "#modal", "", "danger");
            $.unblockUI()
        });

    });


});