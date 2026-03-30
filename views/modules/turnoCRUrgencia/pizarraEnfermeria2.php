<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Pizarra de Turno - Carrito Único</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    .cart-item .badge { min-width: 86px; }
    .cart-item .remove-item { line-height: 1; }
    .mifuente11 { font-size: 11.5px; }
  </style>
</head>
<body class="bg-light">
<div class="container my-4">
  <h3 class="text-center mb-4">Pizarra de Turno - Carrito Único</h3>

  <form id="formTurno" method="post" action="guardar_turno.php">
    <div class="form-row align-items-end">
      <div class="form-group col-md-3">
        <label>Sección</label>
        <select class="form-control form-control-sm mifuente11" id="selectSection">
          <option value="" selected disabled>Seleccionar sección</option>
          <option value="categorizacion">Categorización</option>
          <option value="reanimador">Reanimador</option>
          <option value="box_sala">Box · Sala MP · BR-P</option>
          <option value="kinesiologo_apoyo">Kinesiólogo Apoyo</option>
          <option value="hidratacion_observacion">Hidratación / Observación</option>
          <option value="tens_volante">TENS Volante</option>
          <option value="urgenciologo_a">Urgenciólogo/a</option>
          <option value="medicos_int">Médicos INT</option>
          <option value="auxiliares">Auxiliares</option>
          <option value="urgencia_pediatrica">Urgencia Pediátrica</option>
          <option value="residentes">Residentes</option>
          <option value="poli_urgencia_indiferenciada">Poli-Urgencia Indiferenciada</option>
          <option value="cirujano_turno_veh">Cirujano turno VEH</option>
          <option value="neurologo">Neurólogo</option>
          <option value="residentes_hjnc">Residentes HJNC</option>
          <option value="especialistas_de_llamado">Especialistas de Llamado</option>
          <option value="gestor_camas_turno">Gestor/a de Camas Turno</option>
        </select>
      </div>

      <div class="form-group col-md-3">
        <label>Rol</label>
        <select class="form-control form-control-sm mifuente11" id="selectRol">
          <option value="" selected disabled>Seleccionar rol</option>
        </select>
      </div>

      <div class="form-group col-md-4">
        <label>Nombre</label>
        <input type="text" class="form-control form-control-sm mifuente11" id="inputNombre" placeholder="Nombre y apellido">
      </div>

      <div class="form-group col-md-2">
        <button type="button" class="btn btn-primary btn-block" id="btnAgregar">
          <i class="fas fa-plus"></i> Agregar
        </button>
      </div>
    </div>

    <ul class="list-group mb-3" id="cart-list"></ul>

    <div class="text-center">
      <button type="submit" class="btn btn-success btn-lg">Guardar turno</button>
    </div>
  </form>
</div>

<!-- Dependencias -->
<!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script> -->
<!-- <script src="https://kit.fontawesome.com/a2e0c6ad5b.js" crossorigin="anonymous"></script> -->

<script>
(function(){
  // === Colores por rol ===
  const badgeByRole = {
    "EU": "primary",
    "TENS": "info",
    "MÉDICO": "success",
    "CIRUJANO": "warning",
    "KINESIÓLOGO": "secondary",
    "NEURO CX": "dark",
    "NEURO ACV": "dark"
  };

  let index = 0;

  // === Reglas de roles por sección ===
  const reglas = {
    reanimador: ["EU", "TENS", "KINESIÓLOGO"],
    kinesiologo_apoyo: ["KINESIÓLOGO"],
    tens_volante: ["TENS"],
    urgenciologo_a: ["MÉDICO"],
    medicos_int: ["MÉDICO"],
    especialistas_de_llamado: ["NEURO CX", "NEURO ACV"]
  };

  const rolesGenerales = ["EU", "TENS", "MÉDICO", "CIRUJANO"];

  // === Cargar roles según sección seleccionada ===
  $("#selectSection").on("change", function(){
    const section = $(this).val();
    const selectRol = $("#selectRol");
    selectRol.empty().append('<option value="" selected disabled>Seleccionar rol</option>');

    const roles = reglas[section] || rolesGenerales;
    roles.forEach(r => selectRol.append(`<option>${r}</option>`));
  });

  // === Agregar al carrito ===
  $("#btnAgregar").on("click", function(){
    const section = $("#selectSection").val();
    const rol = $("#selectRol").val();
    const nombre = $("#inputNombre").val().trim();

    if(!section){ alert("Seleccione una sección"); return; }
    if(!rol){ alert("Seleccione un rol"); return; }
    if(!nombre && section !== "especialistas_de_llamado"){ alert("Ingrese un nombre"); return; }

    const badge = badgeByRole[rol] || "secondary";
    const li = $(`
      <li class="list-group-item d-flex justify-content-between align-items-center cart-item">
        <div>
          <span class="badge badge-${badge} mr-2 text-uppercase">${rol}</span>
          <strong>[${formatearSeccion(section)}]</strong> ${nombre}
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger remove-item">&times;</button>
        <input type="hidden" name="turno[${index}][seccion]" value="${section}">
        <input type="hidden" name="turno[${index}][rol]" value="${rol}">
        <input type="hidden" name="turno[${index}][nombre]" value="${nombre}">
      </li>
    `);
    $("#cart-list").append(li);
    index++;

    // limpiar
    $("#inputNombre").val('');
    $("#selectRol").val('');
  });

  // === Eliminar del carrito ===
  $(document).on("click", ".remove-item", function(){
    $(this).closest(".cart-item").remove();
  });

  function formatearSeccion(slug){
    return slug.replace(/_/g, " ").replace(/\b\w/g, c => c.toUpperCase());
  }
})();
</script>
</body>
</html>
