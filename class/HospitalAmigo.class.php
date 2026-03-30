<?php
class HospitalAmigo {
  function ingresarFamiliarOAcompaniante($conn, $parametros) {
     $sql = "
      INSERT INTO
        dau.dau_acompaniante(
          idDau,
          entregaInformacion,
          motivo,
          nombreAcompaniante,
          fechaEntregaInformacionMedica,
          horaEntregaInformacionMedica,
          idUsuarioMedico,
          nombreMedico
        )
      VALUES(
        '{$parametros['idDau']}',
        '{$parametros['entregaInformacion']}',
        '{$parametros['motivo']}',
        '{$parametros['nombreAcompaniante']}',
        CURDATE(),
        '{$parametros['horaEntregaInformacionMedica']}',
        '{$parametros['idUsuarioMedico']}',
        '{$parametros['nombreMedico']}'
      )
    ";

    $conn->ejecutarSQL($sql, "Error al ingresarFamiliarOAcompaniante");

    return $conn->lastInsertId($sql);
  }

  function UpdateFamiliarOAcompaniante($objCon,$parametros){
     $sql="UPDATE dau.dau_acompaniante SET
              entregaInformacion              = '{$parametros['entregaInformacion']}',
              motivo                          = '{$parametros['motivo']}',
              nombreAcompaniante              = '{$parametros['nombreAcompaniante']}',
              fechaEntregaInformacionMedica   = CURDATE(),
              horaEntregaInformacionMedica    = '{$parametros['horaEntregaInformacionMedica']}',
              idUsuarioMedico                 = '{$parametros['idUsuarioMedico']}',
              nombreMedico                    = '{$parametros['nombreMedico']}'
        WHERE idDau                           = '{$parametros['idDau']}' ";
    $objCon->ejecutarSQL($sql, "Error al registrarFechaInicioAtencionENF");
  }

  function obtenerAcompaniante($conn, $parametros) {
    $sql = "
      SELECT
        acompaniante.*,
        dau.est_id AS estadoDau
      FROM
        dau.dau_acompaniante AS acompaniante
      INNER JOIN
        dau.dau AS dau
        ON acompaniante.idDau = dau.dau_id
      WHERE
        acompaniante.idDau = '{$parametros['idDau']}'
      ORDER BY
        acompaniante.idDauAcompaniante DESC
      LIMIT 1
    ";

    return $conn->consultaSQL($sql,"Error al obtenerAcompaniante");
  }



  function obtenerMedicoTratante($conn, $parametros) {
    $sql = "
      SELECT
        usuario.idusuario AS idUsuario,
        usuario.nombreusuario AS nombreMedico
      FROM
        dau.dau AS dau
      INNER JOIN
        acceso.usuario AS usuario
        ON dau.dau_inicio_atencion_usuario = usuario.idusuario
      AND
        dau.dau_id = '{$parametros['idDau']}'
    ";

    return $conn->consultaSQL($sql,"Error al obtenerMedicoTratante");
  }
}
