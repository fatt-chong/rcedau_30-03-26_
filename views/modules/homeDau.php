<?php
session_start();
$usuario = $_SESSION['MM_UsernameName_RCEDAU'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido - RCE DAU</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->

    <!-- Iconos Font Awesome -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> -->

    <style>
        html, body {
            height: 100%;
        }
        body {
            background: url('assets/img/emergencia3.jpg') no-repeat center -7% fixed;
            background-color: #e7f6f7;
            background-size: cover;
            color: #3a3535;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .welcome-section {
            text-align: center;
            padding: 60px 20px 30px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            max-width: 900px;
            margin: auto; /* centra verticalmente cuando hay poco contenido */
        }
        .welcome-section h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.2);
        }
        .welcome-section p {
            font-size: 1.2rem;
        }
        .agradecimiento {
            max-width: 800px;
            margin: 20px auto 0;
            font-size: 1.1rem;
            color: #444;
            background-color: rgba(13, 110, 253, 0.05);
            border-left: 5px solid #0d6efd;
            padding: 15px 20px;
            border-radius: 8px;
        }
        footer {
            margin-top: auto;
            text-align: center;
            padding: 15px;
            background-color: rgba(0,0,0,0.5);
            color: #fff;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <!-- Bienvenida -->
    <div class="row">
        <div class="col-8">
    <section class="welcome-section" style="    max-height: calc(93vh - 93px);height: calc(100vh - 0px);">
        <h1><i class="fa-solid fa-hospital text-danger"></i> Bienvenido al Sistema RCE DAU</h1>
        <p>Hola <strong><?= htmlspecialchars($usuario) ?></strong>, gracias por ingresar.</p>

        <div class="agradecimiento">
            <i class="fa-solid fa-heart text-danger me-2"></i>
            <strong>Un especial agradecimiento</strong> a todo el equipo de <strong>Urgencia</strong> por su dedicación, entrega y esfuerzo diario para brindar una atención oportuna y humana a cada paciente.  
            Su compromiso es fundamental para nuestra comunidad y su labor marca la diferencia.
        </div>
         
    </section>
    
</div>
</div>

    <footer>
        © <?= date('Y') ?> Sistema RCE DAU — Hospital Regional de Arica Dr. Juan Noé Crevani. Desarrollado por el Departamento de Ingeniería en Sistemas.
    </footer>

</body>
</html>
