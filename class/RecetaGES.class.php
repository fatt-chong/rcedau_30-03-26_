<?php
  class RecetaGES {
    function ingresarDetalleRecetaGES($objCon, $parametros) {
      $sql = "
        INSERT INTO
          dau.detalle_receta_ges(
            idRecetaGES,
            idMedicamentoRecetaGES,
            dosis,
            dias
          )
        VALUES(
          '{$parametros['idRecetaGES']}',
          '{$parametros['idMedicamentoRecetaGES']}',
          '{$parametros['dosis']}',
          '{$parametros['dias']}'
        )
      ";
      $objCon->ejecutarSQL($sql, "Error al ingresarDetalleRecetaGES");
      return $objCon->lastInsertId($sql);
    }
    function ingresarRecetaGES($objCon, $parametros) {
      $sql = "
        INSERT INTO
          dau.receta_ges(
            idDau,
            usuarioIngresa,
            fechaIngreso
          )
        VALUES(
          '{$parametros['idDau']}',
          '{$parametros['usuarioIngresa']}',
          NOW()
        )
      ";
      $objCon->ejecutarSQL($sql, "Error al ingresarRecetaGES");
      return $objCon->lastInsertId($sql);
    }
    function obtenerMedicamentos($objCon) {
      $sql = "
        SELECT
          dau.medicamento_receta_ges.*
        FROM
          dau.medicamento_receta_ges
      ";
      return $objCon->consultaSQL($sql,"Error al obtenerMedicamentos");
    }
    function obtenerDetalleRecetaGES($objCon, $parametros) {
      require_once("Util.class.php");
      $objUtil = new Util;
      if (!$objUtil->existe($parametros["idDau"])) {
        return array();
      }
      $sql = "
        SELECT
          dau.detalle_receta_ges.*
        FROM
          dau.detalle_receta_ges
        INNER JOIN
          dau.receta_ges
          ON dau.detalle_receta_ges.idRecetaGES = dau.receta_ges.idRecetaGES
        WHERE
          dau.detalle_receta_ges.idRecetaGES = (
            SELECT
              MAX(dau.receta_ges.idRecetaGES)
            FROM
              dau.receta_ges
            WHERE
              dau.receta_ges.idDau = '{$parametros['idDau']}'
          )
        ORDER BY
          dau.detalle_receta_ges.idDetalleRecetaGES ASC
      ";
      return $objCon->consultaSQL($sql,"Error al obtenerDetalleRecetaGES");
    }
    function obtenerPDFRecetaGES($objCon, $parametros) {
      require_once("Util.class.php");
      $objUtil = new Util;
      if (!$objUtil->existe($parametros["idRecetaGES"])) {
        return array();
      }
      $sql = "
        SELECT
          CONCAT(
            paciente.paciente.nombres,
            ' ',
            paciente.paciente.apellidopat,
            ' ',
            paciente.paciente.apellidomat
          ) AS nombrePaciente,
          dau.dau.dau_paciente_edad AS edadPaciente,
          paciente.paciente.rut_extranjero AS runExtranjero,
          paciente.paciente.rut AS runPaciente,
          CONCAT(
            cie10.cie10.codigoCIE,
            ' - ',
            cie10.cie10.nombreCIE
          ) AS diagnosticoPaciente,
          dau.receta_ges.idDau,
          dau.receta_ges.fechaIngreso,
          dau.receta_ges.usuarioIngresa,
          dau.medicamento_receta_ges.descripcionMedicamento,
          dau.detalle_receta_ges.dosis,
          dau.detalle_receta_ges.dias
        FROM
          dau.detalle_receta_ges
        INNER JOIN
          dau.receta_ges
          ON dau.detalle_receta_ges.idRecetaGES = dau.receta_ges.idRecetaGES
        INNER JOIN
          dau.medicamento_receta_ges
          ON dau.detalle_receta_ges.idMedicamentoRecetaGES = dau.medicamento_receta_ges.idMedicamentoRecetaGES
        INNER JOIN
          dau.dau
          ON dau.receta_ges.idDau = dau.dau.dau_id
        INNER JOIN
          paciente.paciente
          ON dau.dau.id_paciente = paciente.paciente.id
        INNER JOIN
	        cie10.cie10
          ON dau.dau.dau_cierre_cie10 = cie10.cie10.codigoCIE
        WHERE
          dau.detalle_receta_ges.idRecetaGES = '{$parametros['idRecetaGES']}'
        ORDER BY
          dau.detalle_receta_ges.idDetalleRecetaGES asc
      ";
      return $objCon->consultaSQL($sql,"Error al obtenerRecetaGES");
    }
  }
?>