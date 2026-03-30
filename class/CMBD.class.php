<?php
	class CMBD{

        function actualizarCMBD ( $objCon, $parametros ) {
            require_once("Util.class.php");
            $objUtil = new Util();

            $condicion = "";
            $sql = "UPDATE dau.cmbd";

                    if ($objUtil->existe($parametros["nom_solucion"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.nom_solucion = '{$parametros['nom_solucion']}'";
                    }

                    if ($objUtil->existe($parametros["proceso"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.proceso = '{$parametros['proceso']}'";
                    }

                    if ($objUtil->existe($parametros["mes"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.mes = '{$parametros['mes']}'";
                    }

                    if ($objUtil->existe($parametros["anio"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.anio = '{$parametros['anio']}'";
                    }

                    if ($objUtil->existe($parametros["codss"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.codss = '{$parametros['codss']}'";
                    }

                    if ($objUtil->existe($parametros["codestab"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.codestab = '{$parametros['codestab']}'";
                    }

                    if ($objUtil->existe($parametros["iddau"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.iddau = '{$parametros['iddau']}'";
                    }

                    if ($objUtil->existe($parametros["idbdpersonas"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.idbdpersonas = '{$parametros['idbdpersonas']}'";
                    }

                    if ($objUtil->existe($parametros["idpaciente"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.idpaciente = '{$parametros['idpaciente']}'";
                    }

                    if ($objUtil->existe($parametros["run"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.run = '{$parametros['run']}'";
                    }

                    if (! is_null($parametros["dv"]) && $parametros["dv"] == "") {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.dv = '{$parametros['dv']}'";
                    }

                    if ($objUtil->existe($parametros["tipo_identificacion"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.tipo_identificacion = '{$parametros['tipo_identificacion']}'";
                    }

                    if ($objUtil->existe($parametros["fechanacimiento"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.fechanacimiento = '{$parametros['fechanacimiento']}'";
                    }

                    if ($objUtil->existe($parametros["sexo"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.sexo = '{$parametros['sexo']}'";
                    }

                    if ($objUtil->existe($parametros["prevision"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.prevision = '{$parametros['prevision']}'";
                    }

                    if ($objUtil->existe($parametros["clasificacionbeneficiarioFonasa"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.clasificacionbeneficiarioFonasa = '{$parametros['clasificacionbeneficiarioFonasa']}'";
                    }

                    if ($objUtil->existe($parametros["leyesPrevisionales"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.leyesPrevisionales = '{$parametros['leyesPrevisionales']}'";
                    }

                    if ($objUtil->existe($parametros["idatencion"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.idatencion = '{$parametros['idatencion']}'";
                    }

                    if ($objUtil->existe($parametros["fecha_adm"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.fecha_adm = '{$parametros['fecha_adm']}'";
                    }

                    if ($objUtil->existe($parametros["hora_adm"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.hora_adm = '{$parametros['hora_adm']}'";
                    }

                    if ($objUtil->existe($parametros["procedenciadelpaciente"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.procedenciadelpaciente = '{$parametros['procedenciadelpaciente']}'";
                    }

                    if ($objUtil->existe($parametros["unidaddeatencion"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.unidaddeatencion = '{$parametros['unidaddeatencion']}'";
                    }

                    if ($objUtil->existe($parametros["mot_consulta"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.mot_consulta = '{$parametros['mot_consulta']}'";
                    }

                    if ($objUtil->existe($parametros["clasificaciondelaconsulta"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.clasificaciondelaconsulta = '{$parametros['clasificaciondelaconsulta']}'";
                    }

                    if ($objUtil->existe($parametros["llegada"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.llegada = '{$parametros['llegada']}'";
                    }

                    if ($objUtil->existe($parametros["niveldeestratificaciondelpaciente"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.niveldeestratificaciondelpaciente = '{$parametros['niveldeestratificaciondelpaciente']}'";
                    }

                    if ($objUtil->existe($parametros["categorizacionesi"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.categorizacionesi = '{$parametros['categorizacionesi']}'";
                    }

                    if ($objUtil->existe($parametros["primeracat"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.primeracat = '{$parametros['primeracat']}'";
                    }

                    if ($objUtil->existe($parametros["fecha_primera_cate"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.fecha_primera_cate = '{$parametros['fecha_primera_cate']}'";
                    }

                    if ($objUtil->existe($parametros["hora_primera_cate"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.hora_primera_cate = '{$parametros['hora_primera_cate']}'";
                    }

                    if ($objUtil->existe($parametros["tituloprofesionalprimeracategorizacion"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.tituloprofesionalprimeracategorizacion = '{$parametros['tituloprofesionalprimeracategorizacion']}'";
                    }

                    if ($objUtil->existe($parametros["ultima_cate"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.ultima_cate = '{$parametros['ultima_cate']}'";
                    }

                    if ($objUtil->existe($parametros["fecha_ultima_cate"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.fecha_ultima_cate = '{$parametros['fecha_ultima_cate']}'";
                    }

                    if ($objUtil->existe($parametros["hora_ultima_cate"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.hora_ultima_cate = '{$parametros['hora_ultima_cate']}'";
                    }

                    if ($objUtil->existe($parametros["profesional_ultima_cate"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.profesional_ultima_cate = '{$parametros['profesional_ultima_cate']}'";
                    }

                    if ($objUtil->existe($parametros["numerodecategorizaciones"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.numerodecategorizaciones = '{$parametros['numerodecategorizaciones']}'";
                    }

                    if ($objUtil->existe($parametros["fecha_atencion"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.fecha_atencion = '{$parametros['fecha_atencion']}'";
                    }

                    if ($objUtil->existe($parametros["hora_atencion"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.hora_atencion = '{$parametros['hora_atencion']}'";
                    }

                    if ($objUtil->existe($parametros["hipotesis_diag"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.hipotesis_diag = '{$parametros['hipotesis_diag']}'";
                    }

                    if ($objUtil->existe($parametros["hipotesis_cod_diag"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.hipotesis_cod_diag = '{$parametros['hipotesis_cod_diag']}'";
                    }

                    if ($objUtil->existe($parametros["hipotesis_tipo_cod_diag"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.hipotesis_tipo_cod_diag = '{$parametros['hipotesis_tipo_cod_diag']}'";
                    }

                    if ($objUtil->existe($parametros["indicaciondefarmacos"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.indicaciondefarmacos = '{$parametros['indicaciondefarmacos']}'";
                    }

                    if ($objUtil->existe($parametros["idreceta"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.idreceta = '{$parametros['idreceta']}'";
                    }

                    if ($objUtil->existe($parametros["solic_medios_diag"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.solic_medios_diag = '{$parametros['solic_medios_diag']}'";
                    }

                    if ($objUtil->existe($parametros["descrip_medios_diag"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.descrip_medios_diag = '{$parametros['descrip_medios_diag']}'";
                    }

                    if ($objUtil->existe($parametros["fecha_alta"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.fecha_alta = '{$parametros['fecha_alta']}'";
                    }

                    if ($objUtil->existe($parametros["hora_alta"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.hora_alta = '{$parametros['hora_alta']}'";
                    }

                    if ($objUtil->existe($parametros["diag_final"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.diag_final = '{$parametros['diag_final']}'";
                    }

                    if ($objUtil->existe($parametros["tipo_diag"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.tipo_diag = '{$parametros['tipo_diag']}'";
                    }

                    if ($objUtil->existe($parametros["cod_diag"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.cod_diag = '{$parametros['cod_diag']}'";
                    }

                    if ($objUtil->existe($parametros["tipo_cod_diag"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.tipo_cod_diag = '{$parametros['tipo_cod_diag']}'";
                    }

                    if ($objUtil->existe($parametros["condicionalcierredelaatencion"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.condicionalcierredelaatencion = '{$parametros['condicionalcierredelaatencion']}'";
                    }

                    if ($objUtil->existe($parametros["pronosticomedicolegal"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.pronosticomedicolegal = '{$parametros['pronosticomedicolegal']}'";
                    }

                    if ($objUtil->existe($parametros["destino_alta"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.destino_alta = '{$parametros['destino_alta']}'";
                    }

                    if ($objUtil->existe($parametros["ges"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.ges = '{$parametros['ges']}'";
                    }

                    if ($objUtil->existe($parametros["pertinencia"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.pertinencia = '{$parametros['pertinencia']}'";
                    }

                    if ($objUtil->existe($parametros["idprofesionalalta"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.idprofesionalalta = '{$parametros['idprofesionalalta']}'";
                    }

                    if ($objUtil->existe($parametros["runprofesional"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.runprofesional = '{$parametros['runprofesional']}'";
                    }

                    if ($objUtil->existe($parametros["dvprofesional"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.dvprofesional = '{$parametros['dvprofesional']}'";
                    }

                    if ($objUtil->existe($parametros["tituloprofesional"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.tituloprofesional = '{$parametros['tituloprofesional']}'";
                    }

                    if ($objUtil->existe($parametros["especialidadmedica"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.especialidadmedica = '{$parametros['especialidadmedica']}'";
                    }

                    if ($objUtil->existe($parametros["telefonocontacto"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.telefonocontacto = '{$parametros['telefonocontacto']}'";
                    }

                    if ($objUtil->existe($parametros["abandono"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.abandono = '{$parametros['abandono']}'";
                    }

                    if ($objUtil->existe($parametros["cmbd_motivo"])) {
                        $condicion .= (! $objUtil->existe($condicion)) ? " SET " : " , ";
                        $condicion .= "dau.cmbd.cmbd_motivo = '{$parametros['cmbd_motivo']}'";
                    }

                    $condicion .= " WHERE dau.cmbd.iddau = '{$parametros['iddau']}' ";
                    $condicion .= " AND dau.cmbd.cmbd_motivo = '{$parametros['cmbd_motivo']}' ";

            $sql .= $condicion;

            $objCon->ejecutarSQL($sql, "Error al actualizar detalle paciente CMBD");
        }



        function existeCMDB ( $objCon, $idDau, $motivo ) {

           $sql = "
                    SELECT
                        COUNT(dau.cmbd.iddau) AS total
                    FROM
                        dau.cmbd
                    WHERE
                        dau.cmbd.iddau = '{$idDau}'
                    AND
                        dau.cmbd.cmbd_motivo = '{$motivo}'
                    ";


            $datos = $objCon->consultaSQL($sql,"Error al verificar si existe CMBD");

            return $datos[0]["total"] >= 1;

        }



        function iniciarCMBD ( $objCon, $idDau, $motivo ) {
            $parametrosAEnviar              = array();
            $parametrosAEnviar["idDau"]     = $idDau;
            $datosCMBD                      = $this->obtenerCMBD($objCon, $parametrosAEnviar);
            $datosCMBD[0]["cmbd_motivo"]    = $motivo;
            if ( $this->existeCMDB($objCon, $idDau, $motivo) ) {
                $this->actualizarCMBD($objCon, $datosCMBD[0]);
                unset($parametrosAEnviar);
                return;
            }
            $this->ingresarCMBD($objCon, $datosCMBD[0]);
        }



        function ingresarCMBD ( $objCon, $parametros ) {
            $camposTabla = "";
            foreach ($parametros AS $llave=>$parametro) {
                if (is_null($parametro) || $parametro == "") {
                    continue;
                }

                $camposTabla .= $llave.",";
            }
            $camposTabla = substr_replace($camposTabla, "", -1);

            $valores = "";
            foreach ($parametros AS $parametro) {
                if (is_null($parametro) || $parametro == "") {
                    continue;
                }

                $valores .= "'".$parametro."',";
            }
            $valores = substr_replace($valores, "", -1);

            $sql = "
                    INSERT INTO
                        dau.cmbd($camposTabla)
                    VALUES  ($valores)
                    ";

            $objCon->ejecutarSQL($sql, "Error al insertar detalle paciente CMBD");

        }



		function obtenerCMBD ( $objCon, $parametros ) {

             $sql = "
                    SELECT
                        'Hospital Dr. Juan Noé Crevani, Arica - Urgencia' AS nom_solucion,
                        3 AS proceso,
                        MONTH(NOW()) AS mes,
                        YEAR(NOW()) as anio,
                        '01' codss,
                        101100 codestab,
                        dau.dau.dau_id AS iddau,
                        '' AS idbdpersonas,
                        dau.dau.id_paciente AS idpaciente,
                        IF(paciente.paciente.rut = 0 OR paciente.paciente.rut = NULL OR paciente.paciente.rut = '', paciente.paciente.rut_extranjero, paciente.paciente.rut) AS run,
                        paciente.paciente.dv AS dv,
                        IF(paciente.paciente.rut = 0 OR paciente.paciente.rut = NULL OR paciente.paciente.rut = '', 9, 1) AS tipo_identificacion,
                        DATE_FORMAT(paciente.paciente.fechanac, '%Y%m%d') AS fechanacimiento,
                        CASE
                            WHEN paciente.paciente.sexo = 'M' THEN '01'
                            WHEN paciente.paciente.sexo = 'F' THEN '02'
                            ELSE '99'
                        END AS sexo,
                        CASE
                            WHEN
                                    dau.dau.dau_paciente_prevision BETWEEN 0 AND 3
                                    OR dau.dau.dau_paciente_forma_pago = 1
                                    OR dau.dau.dau_paciente_forma_pago = 21
                                    THEN '01'
                            WHEN dau.dau.dau_paciente_prevision BETWEEN 5 AND 50
                            OR dau.dau.dau_paciente_forma_pago = 2
                                    THEN '02'
                            WHEN dau.dau.dau_paciente_forma_pago = 9 THEN 03
                            WHEN dau.dau.dau_paciente_forma_pago = 11 THEN 04
                            ELSE '99'
                        END AS prevision,
                        CASE
                            WHEN paciente.paciente.prevision = 0 THEN 'A'
                            WHEN paciente.paciente.prevision = 1 THEN 'B'
                            WHEN paciente.paciente.prevision = 2 THEN 'C'
                            WHEN paciente.paciente.prevision = 3 THEN 'D'
                            ELSE ''
                        END AS clasificacionbeneficiarioFonasa,
                        CASE
                            WHEN dau.dau.dau_motivo_consulta = 1 AND dau.dau.dau_tipo_accidente = 3 THEN '01'
                            WHEN dau.dau.dau_motivo_consulta = 1 AND dau.dau.dau_tipo_accidente = 2 THEN '02'
                            WHEN dau.dau.dau_motivo_consulta = 1 AND dau.dau.dau_tipo_accidente = 1 THEN '03'
                            WHEN paciente.paciente.prais = 1 THEN '05'
                            ELSE '96'
                        END AS leyesPrevisionales,
                        dau.dau.dau_atencion AS idatencion,
                        DATE_FORMAT(dau.dau.dau_admision_fecha, '%d%m%Y') AS fecha_adm,
                        DATE_FORMAT(dau.dau.dau_admision_fecha, '%H:%i') AS hora_adm,
                        CASE
                            WHEN dau.consultorios.filtroConsultoriosAPS = 'S' THEN 2
                            WHEN dau.consultorios.filtroConsultoriosAPS = 'N' AND dau.consultorios.tiposRed = 'NO RED' THEN 4
                            WHEN dau.consultorios.tiposRed LIKE '%SAPU%' THEN 7
                            ELSE 6
                        END AS procedenciadelpaciente,
                        CASE
                            WHEN dau.dau.dau_atencion = 2 THEN '01'
                            WHEN dau.dau.dau_atencion = 1 THEN '02'
                            WHEN dau.dau.dau_atencion = 3 THEN '03'
                        END AS unidaddeatencion,
                        CASE
                            WHEN dau.dau_motivo_consulta = 1 THEN
                                CASE
                                    WHEN sub_motivo_consulta.sub_mot_id = 1 THEN
                                        CONCAT(
                                                    sub_motivo_consulta.sub_mot_descripcion,
                                                    ', ',
                                                    dau.institucion.ins_descripcion,
                                                    ': ',
                                                    CONCAT(
                                                        dau.dau_accidente_escolar_nombre,
                                                        ', ',
                                                        IF (
                                                                    dau.dau_accidente_escolar_numero = '',
                                                                    '',
                                                                    dau.dau_accidente_escolar_numero
                                                                )
                                                    ),
                                                    ' - ',
                                                    dau.dau.dau_motivo_descripcion
                                                )
                                    WHEN sub_motivo_consulta.sub_mot_id = 2 THEN
                                        CONCAT(
                                                    sub_motivo_consulta.sub_mot_descripcion,
                                                    ', ',
                                                    INS_2.ins_descripcion,
                                                    ' - ',
                                                    dau.dau.dau_motivo_descripcion
                                                )
                                    WHEN sub_motivo_consulta.sub_mot_id = 3 THEN
                                    CONCAT(
                                                sub_motivo_consulta.sub_mot_descripcion,
                                                ', ',
                                                dau.tipo_transito.tran_descripcion,
                                                ' - ',
                                                dau.dau.dau_motivo_descripcion,
                                                ' - ',
                                                IF 	(
                                                            dau.dau.dau_tipo_choque != 0,
                                                            dau.tipo_choque.tip_choque_descripcion,''
                                                        )
                                            )
                                    WHEN sub_motivo_consulta.sub_mot_id = 4 THEN
                                    CONCAT(
                                                sub_motivo_consulta.sub_mot_descripcion,
                                                ', ',
                                                INS_3.ins_descripcion,
                                                ' - ',
                                                dau.dau.dau_motivo_descripcion
                                            )
                                    WHEN sub_motivo_consulta.sub_mot_id = 5 THEN
                                    CONCAT(
                                                sub_motivo_consulta.sub_mot_descripcion,
                                                ', ',
                                                INS_4.ins_descripcion,
                                                ' - ',
                                                dau.dau.dau_motivo_descripcion
                                            )
                                    ELSE dau.dau.dau_motivo_descripcion
                                END
                            WHEN dau.dau_motivo_consulta = 2 THEN dau.dau.dau_motivo_descripcion
                            WHEN dau.dau_motivo_consulta = 3 THEN
                                CONCAT(
                                            dau.dau.dau_motivo_descripcion,
                                            IF 	(
                                                        dau.dau_agresion_vif = 'S',
                                                        ', VIF',
                                                        ''
                                                    )
                                        )
                            ELSE dau.dau.dau_motivo_descripcion
                        END AS mot_consulta,
                        CASE
                            WHEN dau.dau.dau_motivo_consulta = 2 AND dau.dau.dau_agresion_vif = 'S' THEN '02'
                            WHEN dau.dau.dau_motivo_consulta = 4 THEN '03'
                            WHEN dau.dau.dau_motivo_consulta = 5 THEN '04'
                            ELSE '09'
                        END AS clasificaciondelaconsulta,
                        CASE
                            WHEN dau.dau.dau_forma_llegada = 1 THEN 1
                            WHEN dau.dau.dau_forma_llegada = 33 THEN 2
                            WHEN dau.dau.dau_forma_llegada IN (5,6) THEN 4
                            WHEN dau.dau.dau_forma_llegada IN (2,3,7,8,10) THEN 5
                            WHEN dau.dau.dau_forma_llegada = 4 THEN 6
                            WHEN dau.dau.dau_forma_llegada IN (35,36,37,39,41) THEN 8
                            WHEN (dau.dau.dau_forma_llegada BETWEEN 12 AND 29) AND dau.dau.dau_forma_llegada IN (31,32,34,38,40) THEN 9
                            WHEN dau.dau.dau_forma_llegada = 9 THEN 14
                            WHEN dau.dau.dau_forma_llegada = 30 THEN 15
                            ELSE ''
                        END AS llegada,
                        '' AS niveldeestratificaciondelpaciente,
                        IF( dau.dau.dau_categorizacion_fecha IS NOT NULL, 'Si', '') AS categorizacionesi,
                        CASE
                            WHEN dau.dau.dau_categorizacion_fecha IS NOT NULL AND dau.dau.dau_categorizacion IN ('C1', 'ESI-1') THEN '01'
                            WHEN dau.dau.dau_categorizacion_fecha IS NOT NULL AND dau.dau.dau_categorizacion IN ('C2', 'ESI-2') THEN '02'
                            WHEN dau.dau.dau_categorizacion_fecha IS NOT NULL AND dau.dau.dau_categorizacion IN ('C3', 'ESI-3') THEN '03'
                            WHEN dau.dau.dau_categorizacion_fecha IS NOT NULL AND dau.dau.dau_categorizacion IN ('C4', 'ESI-4') THEN '04'
                            WHEN dau.dau.dau_categorizacion_fecha IS NOT NULL AND dau.dau.dau_categorizacion IN ('C5', 'ESI-5') THEN '05'
                            ELSE ''
                        END AS primeracat,
                        IFNULL(DATE_FORMAT(dau.dau.dau_categorizacion_fecha, '%d%m%Y'), '') AS fecha_primera_cate,
                        IFNULL(DATE_FORMAT(dau.dau.dau_categorizacion_fecha, '%H:%i'), '') AS hora_primera_cate,
                        CASE
                            WHEN dau.dau.dau_categorizacion_fecha IS NOT NULL AND dau.dau.dau_atencion IN (1,2) AND usuarioCategorizacion.TIPROcodigo = 02 THEN '03'
                            WHEN dau.dau.dau_categorizacion_fecha IS NOT NULL AND dau.dau.dau_atencion = 3 AND usuarioCategorizacion.TIPROcodigo = 02 THEN '04'
                            WHEN dau.dau.dau_categorizacion_fecha IS NOT NULL AND usuarioCategorizacion.TIPROcodigo = 17 THEN '19'
                            ELSE ''
                        END AS tituloprofesionalprimeracategorizacion,
                        CASE
                            WHEN dau.dau.dau_categorizacion_fecha IS NOT NULL AND dau.dau.dau_categorizacion IN ('C1', 'ESI-1') THEN '01'
                            WHEN dau.dau.dau_categorizacion_fecha IS NOT NULL AND dau.dau.dau_categorizacion IN ('C2', 'ESI-2') THEN '02'
                            WHEN dau.dau.dau_categorizacion_fecha IS NOT NULL AND dau.dau.dau_categorizacion IN ('C3', 'ESI-3') THEN '03'
                            WHEN dau.dau.dau_categorizacion_fecha IS NOT NULL AND dau.dau.dau_categorizacion IN ('C4', 'ESI-4') THEN '04'
                            WHEN dau.dau.dau_categorizacion_fecha IS NOT NULL AND dau.dau.dau_categorizacion IN ('C5', 'ESI-5') THEN '05'
                            ELSE ''
                        END AS ultima_cate,
                        IFNULL(DATE_FORMAT(dau.dau.dau_categorizacion_fecha, '%d%m%Y'), '') AS fecha_ultima_cate,
                        IFNULL(DATE_FORMAT(dau.dau.dau_categorizacion_fecha, '%H:%i'), '') AS hora_ultima_cate,
                        '' AS profesional_ultima_cate,
                        IF(dau.dau.dau_categorizacion_fecha IS NOT NULL, '01', '') AS numerodecategorizaciones,
                        IFNULL(DATE_FORMAT(dau.dau.dau_inicio_atencion_fecha, '%d%m%Y'), '') AS fecha_atencion,
                        IFNULL(DATE_FORMAT(dau.dau.dau_inicio_atencion_fecha, '%H:%i'), '') AS hora_atencion,
                        IFNULL(rce.registroclinico.regHipotesisInicial, '') AS hipotesis_diag,
                        '' AS hipotesis_cod_diag,
                        '' AS hipotesis_tipo_cod_diag,
                        '' AS indicaciondefarmacos,
                        '' AS idreceta,
                        '' AS solic_medios_diag,
                        '' AS descrip_medios_diag,
                        IF(dau.dau.dau_indicacion_egreso_fecha IS NOT NULL, DATE_FORMAT(dau.dau.dau_indicacion_egreso_fecha, '%d%m%Y'), '') AS fecha_alta,
                        IF(dau.dau.dau_indicacion_egreso_fecha IS NOT NULL, DATE_FORMAT(dau.dau.dau_indicacion_egreso_fecha, '%H:%i'), '') AS hora_alta,
                        IF(dau.dau.dau_indicacion_egreso_fecha IS NOT NULL, rce.registroclinico.regHipotesisFinal, '') AS diag_final,
                        CASE
                            WHEN dau.dau.dau_cierre_fecha_final IS NULL AND dau.dau.dau_indicacion_egreso_fecha IS NOT NULL THEN '01'
                            WHEN dau.dau.dau_cierre_fecha_final IS NOT NULL THEN '02'
                            ELSE ''
                        END AS tipo_diag,
                        IF(dau.dau.dau_indicacion_egreso_fecha IS NOT NULL, dau.dau.dau_cierre_cie10, '') AS cod_diag,
                        IF(dau.dau.dau_cierre_cie10 IS NOT NULL, '01', '') AS tipo_cod_diag,
                        CASE
                            WHEN dau_indicacion_egreso_fecha IS NOT NULL AND dau.dau.dau_defuncion_fecha IS NULL THEN '01'
                            WHEN dau_indicacion_egreso_fecha IS NOT NULL AND dau.dau.dau_defuncion_fecha IS NOT NULL THEN '02'
                            ELSE ''
                        END AS condicionalcierredelaatencion,
                        CASE
                            WHEN dau.dau.dau_indicacion_egreso_fecha IS NOT NULL AND rce.registroclinico.PRONcodigo = 1 THEN '01'
                            WHEN dau.dau.dau_indicacion_egreso_fecha IS NOT NULL AND rce.registroclinico.PRONcodigo = 2 THEN '02'
                            WHEN dau.dau.dau_indicacion_egreso_fecha IS NOT NULL AND rce.registroclinico.PRONcodigo = 3 THEN '03'
                            ELSE ''
                        END AS pronosticomedicolegal,
                        CASE
                            WHEN dau.dau.dau_indicacion_egreso_fecha IS NOT NULL AND dau.dau.dau_indicacion_egreso = 4 THEN '01'
                            WHEN dau.dau.dau_indicacion_egreso_fecha IS NOT NULL AND dau.dau.dau_indicacion_egreso IN (8,9,10) THEN '02'
                            WHEN dau.dau.dau_indicacion_egreso_fecha IS NOT NULL AND dau.dau.dau_indicacion_egreso = 6 THEN '03'
                            WHEN dau.dau.dau_indicacion_egreso_fecha IS NOT NULL AND dau.dau.dau_indicacion_egreso = 7 THEN '04'
                            WHEN dau.dau.dau_indicacion_egreso_fecha IS NOT NULL AND dau.dau.dau_indicacion_egreso = 3 THEN '05'
                            WHEN dau.dau.dau_indicacion_egreso_fecha IS NOT NULL AND dau.dau.dau_indicacion_egreso NOT IN (3,4,6,7,8,9,10) THEN '06'
                            ELSE ''
                        END AS destino_alta,
                        IF(dau.dau.dau_indicacion_egreso_fecha IS NOT NULL AND dau.dau.dau_cierre_auge IS NOT NULL, IF(dau.dau.dau_cierre_auge = 'S',  'SI', 'NO'), '') AS ges,
                        IF(dau.dau.dau_indicacion_egreso_fecha IS NOT NULL AND dau.dau.dau_cierre_pertinencia IS NOT NULL, IF(dau.dau.dau_cierre_pertinencia = 'S',  'SI', 'NO'), '') AS pertinencia,
                        IF(dau.dau.dau_indicacion_egreso_fecha IS NOT NULL, dau.dau.dau_indicacion_egreso_usuario, '') AS idprofesionalalta,
                        IF(dau.dau.dau_indicacion_egreso_fecha IS NOT NULL, usuarioCierre.PROcodigo, '') AS runprofesional,
                        IF(usuarioCierre.PROcodigo IS NOT NULL,  dau.digitoVerificador(usuarioCierre.PROcodigo), '') AS dvprofesional,
                        CASE
                            WHEN dau.dau.dau_inicio_atencion_usuario IS NOT NULL AND usuarioInicioAtencion.TIPROdescripcion = 'Médico Cirujano' THEN '01'
                            WHEN dau.dau.dau_inicio_atencion_usuario IS NOT NULL AND usuarioInicioAtencion.TIPROcodigo = 6 THEN '02'
                            WHEN dau.dau.dau_inicio_atencion_usuario IS NOT NULL AND usuarioInicioAtencion.TIPROcodigo = 2 THEN '03'
                            WHEN dau.dau.dau_inicio_atencion_usuario IS NOT NULL AND usuarioInicioAtencion.TIPROcodigo = 3 THEN '05'
                            WHEN dau.dau.dau_inicio_atencion_usuario IS NOT NULL AND usuarioInicioAtencion.TIPROcodigo = 17 THEN '06'
                            ELSE ''
                        END AS tituloprofesional,
                        '' AS especialidadmedica,
                        '' AS telefonocontacto,
                        CASE
                            WHEN (dau.dau.est_id = 6 OR dau.dau.est_id = 7) AND dau.dau.dau_cierre_administrativo = 'S' THEN 'ABANDONO SIN CATEGORIZAR'
                            WHEN dau.dau.est_id = 6 THEN 'ABANDONO SIN CATEGORIZAR'
                            WHEN dau.dau.est_id = 7 THEN 'ABANDONO'
                            ELSE ''
                        END AS abandono
                    FROM
                        dau.dau
                    INNER JOIN
                        paciente.paciente FORCE INDEX FOR JOIN (idx_idpaciente) ON dau.dau.id_paciente = paciente.paciente.id
                    LEFT JOIN
                        dau.paciente_derivado ON dau.dau.dau_id = dau.paciente_derivado.idDau
                    LEFT JOIN
                        dau.consultorios ON dau.paciente_derivado.idEstablecimientoRedSalud = dau.consultorios.con_id
                    INNER JOIN
                        dau.motivo_consulta ON dau.dau.dau_motivo_consulta = dau.motivo_consulta.mot_id
                    LEFT JOIN
                        dau.institucion ON dau.dau.dau_tipo_accidente = dau.institucion.tip_id AND dau.dau.dau_accidente_escolar_institucion = dau.institucion.ins_id
                    LEFT JOIN
                        dau.institucion INS_2 ON dau.dau.dau_tipo_accidente = INS_2.tip_id AND dau.dau.dau_accidente_trabajo_mutualidad = INS_2.ins_id
                    LEFT JOIN
                        dau.institucion INS_3 ON dau.dau.dau_tipo_accidente = INS_3.tip_id AND dau.dau.dau_accidente_hogar_lugar = INS_3.ins_id
                    LEFT JOIN
                        dau.institucion INS_4 ON dau.dau.dau_tipo_accidente = INS_4.tip_id AND dau.dau.dau_accidente_otro_lugar = INS_4.ins_id
                    LEFT JOIN
                        dau.sub_motivo_consulta ON dau.dau.dau_tipo_accidente = dau.sub_motivo_consulta.sub_mot_id
                    LEFT JOIN
                        dau.tipo_transito ON dau.dau.dau_accidente_transito_tipo = dau.tipo_transito.tran_id
                    LEFT JOIN
                        dau.mordedura ON dau.dau.dau_mordedura = mordedura.mor_id
                    LEFT JOIN
                        dau.intoxicacion ON dau.dau.dau_intoxicacion = intoxicacion.int_id
                    LEFT JOIN
                        dau.quemado ON dau.dau.dau_quemadura = quemado.que_id
                    LEFT JOIN
                        dau.tipo_choque ON dau.tipo_choque.tip_choque_id = dau.dau.dau_tipo_choque
                    LEFT JOIN
                        dau.medio_llegada ON dau.dau.dau_forma_llegada = dau.medio_llegada.med_id
                    LEFT JOIN
                        parametros_clinicos.profesional AS usuarioCategorizacion ON dau.dau.dau_categorizacion_usuario = usuarioCategorizacion.PRO_idusuario
                    LEFT JOIN
                        parametros_clinicos.profesional AS usuarioCierre ON dau.dau.dau_indicacion_egreso_usuario = usuarioCierre.PRO_idusuario
                    LEFT JOIN
                        parametros_clinicos.profesional AS usuarioInicioAtencion ON dau.dau.dau_inicio_atencion_usuario = usuarioInicioAtencion.PRO_idusuario
                    LEFT JOIN
                        rce.registroclinico ON dau.dau.dau_id = rce.registroclinico.dau_id
                    WHERE
                        dau.dau.dau_id = '{$parametros['idDau']}'
                    ";

            $datos = $objCon->consultaSQL($sql,"Error al obtener detalle paciente CMBD");

            return $datos;

        }

    }

?>
