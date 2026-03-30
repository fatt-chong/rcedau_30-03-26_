<?php
$pdf_url = isset($_POST['pdf_url']) ? $_POST['pdf_url'] : '';
if (empty($pdf_url)) {
    echo '<p class="p-3 text-muted">No se especificó URL del PDF.</p>';
    return;
}
?>



<!-- <div class="modal-body p-0" > -->
    <iframe id="pdfFrame" class="embed-responsive-item" height="700" width="100%" allowfullscreen ></iframe>
<!-- </div> -->

<script>
(function() {
    var pdfUrl = <?= json_encode($pdf_url) ?>;
    if (!pdfUrl) return;

    var user = null;
    try {
        var hjnc = localStorage.getItem('hjnc_user');
        if (hjnc) user = JSON.parse(hjnc);
    } catch (e) {}

    var headers = {
        'Accept': 'application/json',
        'Authorization': (user && user.token) ? 'Bearer ' + user.token : ''
    };

    fetch(pdfUrl, { method: 'GET', headers: headers })
    .then(r => r.json())
    .then(function(data) {

        if (!data || !data.pdf_base64) {
            document.querySelector('#modalVerPdfSolicitud .modal-body').innerHTML =
                '<p class="p-3 text-danger">No se pudo cargar el PDF.</p>';
            return;
        }

        var base64 = data.pdf_base64.replace('data:application/pdf;base64,','').trim();

        var bytes = atob(base64);
        var arr = new Uint8Array(bytes.length);

        for (var i = 0; i < bytes.length; i++) {
            arr[i] = bytes.charCodeAt(i);
        }

        var blob = new Blob([arr], { type: 'application/pdf' });

        document.getElementById('pdfFrame').src = URL.createObjectURL(blob);

        $('#modalVerPdfSolicitud').modal('show');
    })
    .catch(function() {
        document.querySelector('#modalVerPdfSolicitud .modal-body').innerHTML =
            '<p class="p-3 text-danger">Error al obtener el PDF.</p>';
});
})();
</script>