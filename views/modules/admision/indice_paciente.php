
<script>
    function loadIframe() {
        const url = '/indice_paciente_2017/views/modules/pacientes/moduloPacienteDau.php'; // Cambia esta URL al destino del iframe

        // Crear un formulario
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        form.target = 'miIframe'; // Nombre del iframe

        // Crear campos de entrada para los parámetros
        const params = {
            sistemaExterno: 'DAU',
            fonasa: '1',
            // Agrega más parámetros según sea necesario
        };

        for (const key in params) {
            if (params.hasOwnProperty(key)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = params[key];
                form.appendChild(input);
            }
        }

        // Agregar el formulario al cuerpo, enviar y eliminar
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }
</script>
<script type="text/javascript">
	$(document).ready(function(){
		loadIframe();
	});
</script>
 <style>
        .iframe-container {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%; /* Relación de aspecto 16:9 (se puede ajustar según sea necesario) */
            height: 0;
            overflow: hidden;
        }

        .iframe-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }
    </style>
<div class="iframe-container">
    <iframe id="miIframe" name="miIframe"></iframe>
</div>