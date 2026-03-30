$(document).ready(function(){

	$("#btnGenerarPDFResumen").click(function(){		
		
		modalFormulario_noCabecera("Resumen Tiempo Espera", raiz+"/views/modules/reportes/salidas/contenidoResumenTiemposEspera2.php", $("#formContenidoResumenTiemposEspera").serialize(), "#resumenTiempoEspera2", "modal-lg", "", "fas fa-plus");
	
	});



	$("#btnGenerarEXCELResumen").click(function(){		

		// var url 		= `${raiz}/views/modules/reportes/salidas/excelResumenTiempoEspera.php`;
		var parametros 	= `fechaInicioReporteExcel=${$('#fechaInicioReporteExcel').val()}&fechaTerminoReporteExcel=${$('#fechaTerminoReporteExcel').val()}&tipoAtencion=${$('#tipoAtencion').val()}`;
        url             = raiz+'/views/modules/reportes/salidas/excelResumenTiempoEspera.php?'+parametros;

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