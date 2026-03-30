<?php
class LPP {
  function ingresarLPP($conn, $parametros) {
    $sql = "
      INSERT INTO
        dau.lpp(
          idDau,
          idsValoracionPiel,
          descripcionesValoracionPiel,
          zonaAfectada,
          puntajeEvaluacion,
          idRiesgo,
          idAplicacionSEMP,
          idCambioPosicion,
          registroEjecucion,
          usuario,
          fecha
        )
      VALUES(
        '{$parametros['idDau']}',
        '{$parametros['idsValoracionPiel']}',
        '{$parametros['descripcionesValoracionPiel']}',
        '{$parametros['zonaAfectada']}',
        '{$parametros['puntajeEvaluacion']}',
        '{$parametros['idRiesgo']}',
        '{$parametros['idAplicacionSEMP']}',
        '{$parametros['idCambioPosicion']}',
        '{$parametros['registroEjecucion']}',
        '{$parametros['usuario']}',
        NOW()
      )
    ";

    $conn->ejecutarSQL($sql, "Error al ingresarLPP");

    return $conn->lastInsertId($sql);
  }



  function obtenerAplicacionesSEMP($conn) {
    $sql = "
      SELECT
        aplicacionSEMP.*
      FROM
        dau.lpp_aplicacion_semp AS aplicacionSEMP
      ORDER BY
        aplicacionSEMP.idAplicacionSEMP ASC
    ";

    return $conn->consultaSQL($sql,"Error al obtenerAplicacionesSEMP");
  }



  function obtenerCambiosPosiciones($conn) {
    $sql = "
      SELECT
        cambioPosicion.*
      FROM
        dau.lpp_cambio_posicion AS cambioPosicion
      ORDER BY
        cambioPosicion.idCambioPosicion ASC
    ";

    return $conn->consultaSQL($sql,"Error al obtenerCambiosPosiciones");
  }



  function obtenerLPP($conn, $parametros) {
    require_once("Util.class.php");

    $util = new Util();

    if (!$util->existe($parametros["idDau"])) {
      return array();
    }

    $sql = "
      SELECT
        LPP_MAX.idLPP,
        LPP_MAX.idDau,
        LPP_MAX.idsValoracionPiel,
        LPP_MAX.descripcionesValoracionPiel,
        LPP_MAX.zonaAfectada,
        LPP_MAX.puntajeEvaluacion,
        LPP_MAX.idRiesgo,
        riesgo.descripcionRiesgo,
        LPP_MAX.idAplicacionSEMP,
        aplicacionSEMP.descripcionAplicacionSEMP,
        LPP_MAX.idCambioPosicion,
        cambioPosicion.descripcionCambioPosicion,
        (
          SELECT
            CONCAT(GROUP_CONCAT(LPP.registroEjecucion))
          FROM
            dau.lpp LPP
          WHERE
            LPP.idDau = LPP_MAX.idDau
        ) AS registrosEjecucion,
        (
          SELECT
            CONCAT(GROUP_CONCAT(LPP.usuario))
          FROM
            dau.lpp LPP
          WHERE
            LPP.idDau = LPP_MAX.idDau
        ) AS usuarios,
        (
          SELECT
            CONCAT(GROUP_CONCAT(LPP.fecha))
          FROM
            dau.lpp LPP
          WHERE
            LPP.idDau = LPP_MAX.idDau
        ) AS fechas,
        dau.est_id AS estadoDau
      FROM
        (
          SELECT
            dau.lpp.*
          FROM
            dau.lpp
          WHERE
            dau.lpp.idDau = '{$parametros['idDau']}'
          ORDER BY
            dau.lpp.idLPP DESC
          LIMIT 1
        ) AS LPP_MAX
      INNER JOIN
        dau.lpp_riesgo AS riesgo
        ON LPP_MAX.idRiesgo = riesgo.idRiesgo
      INNER JOIN
        dau.lpp_aplicacion_semp AS aplicacionSEMP
        ON LPP_MAX.idAplicacionSEMP = aplicacionSEMP.idAplicacionSEMP
      INNER JOIN
        dau.lpp_cambio_posicion AS cambioPosicion
        ON LPP_MAX.idCambioPosicion = cambioPosicion.idCambioPosicion
      INNER JOIN
        dau.dau AS dau
        ON LPP_MAX.idDau = dau.dau.dau_id
    ";

    return $conn->consultaSQL($sql, "Error al obtener LPP");
  }



  function obtenerRiesgos($conn) {
    $sql = "
      SELECT
        riesgo.*
      FROM
        dau.lpp_riesgo AS riesgo
      ORDER BY
        riesgo.idRiesgo ASC
    ";

    return $conn->consultaSQL($sql,"Error al obtenerRiesgos");
  }



  function obtenerValoracionesPiel($conn) {
    $sql = "
      SELECT
        valoracionPiel.*
      FROM
        dau.lpp_valoracion_piel AS valoracionPiel
      ORDER BY
        valoracionPiel.descripcionValoracionPiel ASC
    ";

    return $conn->consultaSQL($sql,"Error al obtenerValoracionesPiel");
  }
}
