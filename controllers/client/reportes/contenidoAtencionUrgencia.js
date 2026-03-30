$(document).ready(function(){

    $("#btnImprimirResumen").click(function(){

        let fechaInicio  = $("#frm_fecha_admision_desde").val(),
        
            fechaFin     = $("#frm_fecha_admision_hasta").val();
        // C:\inetpub\wwwroot\php8site\RCEDAU\views\modules\reportes\salidas\resumenAtencionesAdmision.php

		modalFormulario_noCabecera("RESUMEN DE ATENCIONES MÉDICAS POR FECHA (ADMISIÓN)", raiz+"/views/modules/reportes/salidas/resumenAtencionesAdmision.php", "fechaInicio="+fechaInicio+"&fechaFin="+fechaFin, "#resumenAtencionesAdmision", "modal-lg", "", "fas fa-plus");

        // modalDetalle("RESUMEN DE ATENCIONES MÉDICAS POR FECHA (ADMISIÓN)", raiz+"/views/reportes/salidas/resumenAtencionesAdmision.php", "fechaInicio="+fechaInicio+"&fechaFin="+fechaFin, "#resumenAtencionesAdmision", "66%", "100%");       

    }); 

});