$(document).ready(function(){
	dau_id = $('#dau_id').val();

	$("#btn_signos_vitales").click(function(){
		modalFormulario_noCabecera('', raiz+"/views/modules/rce/signos_vitales/signos_vitales.php", 'dau_id='+dau_id, "#modalSignosVitales", "modal-lgg", "", "fas fa-plus",'');
	});
	$("#btn_nea").click(function(){
		modalFormulario_noCabecera('', raiz+"/views/modules/rce/nea/modalAplicarNEA.php", 'dau_id='+dau_id, "#modalNEA", "modal-md", "", "fas fa-plus",'');
	});
});