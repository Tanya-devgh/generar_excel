<?php
	class excels {
		function ejemplo_afiliado(){
			try {
				$resultado = array(
		          'code'   => '0',
		          'status' => 'inicio',
		          'datos'  => ''
		        );
				require_once "db_config2.php";

				$consulta = "SELECT * FROM dpAfiliados where idEmpleado = '0000000001459'";

				$ejecutar = sqlsrv_query($conn, $consulta);
				$datos = [];
				$registro = '';
			    if($ejecutar){
			      while($row = sqlsrv_fetch_array($ejecutar)){
			      	$registro = array(
			      		'idInstitucion' => $row['idInstitucion'],
			      		'idEmpleado'	=> utf8_encode($row['idEmpleado']),
			      		'nombre'		=> utf8_encode($row['nombre'])
			      	);
			      	array_push($datos,$registro);
			      }
			      $resultado['code'] = '1';
		          $resultado['status'] = 'success';
		          $resultado['datos'] = $datos;
		          return $resultado;
			    } else {
					if( ($errors = sqlsrv_errors() ) != null) {
				        foreach( $errors as $error ) {
				          $resultado['code'] = '0';
				          $resultado['status'] = "SQLSTATE: ".$error[ 'SQLSTATE']."<br />". "code: ".$error[ 'code']."<br />"."message: ".$error[ 'message']."<br />";
				          $resultado['datos'] = '';
				          return $resultado;
				        } 
				    } else {
				      	$resultado['code'] = '0';
				        $resultado['status'] = 'Error';
				        $resultado['datos'] = '';
				        return $resultado;
				    }
			    }
			} catch (Exception $e) {
		        $resultado['code'] = '0';
		        $resultado['status'] = 'Error_'.$e->getMessage();
		        $resultado['datos'] = '';
		        return $resultado;
	      	}
		}

		function alta_beneficiarios_pension($fecha){
			//return 'mes '.$mes.' año '.$año;
			try {
				$resultado = array(
		          'code'   => '0',
		          'status' => 'inicio',
		          'datos'  => ''
		        );
				require_once "db_config2.php";
				//$consulta = "SELECT * FROM dpAfiliados where idEmpleado = '0000000001459'";
				$consulta = "declare @mes int
					declare @alo int
					set @mes = MONTH('2021/08/01')
					set @alo = YEAR('2021/08/01')
					SELECT lista = case pit.lista when 1 then 'SECCION 38'
						when 2 then 'UAAAN'
						when 3 then 'UADEC' else '-' end,
						pe.descripcion as esquema, 
						pit.institucion as institucion,
						tp.tipopension,
						ppi.folio, 
						tp.Letra, ppi.idPensionado, ppi.idBeneficiario,
						ppi.nombre, ppi.paterno, ppi.materno,
						pcp.Descripcion as concepto_pension, 
						ppi.antededente,
						ppi.anos, ppi.meses, ppi.dias,
						pit.categoria,
						pit.escuela,
						pp.Descripcion as poblacion,
						ppi.telefono, ppi.celular,
						cast(ppi.nominafecha as date) as fecha_nomina,
						cast(ppi.fechaPension as date) as fechaPension,
						retirado = cast(('SE RETIRA POR LA ' +
							Stuff((SELECT DISTINCT  ', ' + di.Descripcion AS [text()]
					                FROM pePensionadoIngTabulador t
									LEFT JOIN dpInstituciones di on di.idInstitucion = t.idInstitucion
									WHERE t.folioPensionado = ppi.folio and di.idInstitucion <> pit.idInstitucion
										FOR XML PATH ('')),1,1,'')) as varchar(max))
					FROM pePensionadosIngresos ppi
					LEFT JOIN peEsquemas pe on ppi.idEsquemaPension = pe.idEsquema
					LEFT JOIN pePoblaciones pp on ppi.idPoblacion = pp.idPoblacion
					LEFT JOIN peConceptoPension pcp on ppi.idConceptoPension = pcp.idConceptoPension
					LEFT JOIN peTipoPension tp on ppi.idTipoPension = tp.idtipopension
					LEFT JOIN (SELECT DISTINCT ppit.folioPensionado, di.idInstitucion, di.Descripcion as institucion, di.idctaInstitucional as lista, e.idEscuela, e.descripcion as escuela,
						pc.idCategoria1, pc.descripcion as categoria
						FROM pePensionadoIngTabulador ppit
						LEFT JOIN dpInstituciones di on di.idInstitucion = ppit.idInstitucion
						LEFT JOIN dpEscuelas e on e.idescuela = ppit.idescuela
						LEFT JOIN peCategorias pc on ppit.idcategoria = pc.idcategoria) pit on pit.folioPensionado = ppi.folio
					where MONTH(ppi.fechaPension) = @mes and YEAR (ppi.fechaPension) = @alo
					order by pit.lista asc, pe.idEsquema asc, pit.institucion asc, ppi.idTipoPension asc, ppi.idPensionado asc, ppi.idBeneficiario asc";

				$ejecutar = sqlsrv_query($conn, $consulta);
				$datos = [];
				$registro = '';
			    if($ejecutar){
			      while($row = sqlsrv_fetch_array($ejecutar)){
			      	$registro = array(
			      		'lista' 	=> utf8_encode($row['lista']),
			      		'esquema' 	=> utf8_encode($row['esquema']),
			      		'institucion'=> utf8_encode($row['institucion']),
			      		'tipopension'=> utf8_encode($row['tipopension']),
			      		'codigo'	=> utf8_encode($row['Letra'].' - 0'.$row['idPensionado'].' - '.$row['idBeneficiario']),
			      		'folio'		=> utf8_encode($row['folio']),
			      		'nombre'	=> utf8_encode($row['nombre'].' '.$row['paterno'].' '.$row['materno']),
			      		'concepto_pension'=> utf8_encode($row['concepto_pension']),
			      		'antededente'	=> utf8_encode(($row['antededente'] == '') ? '-': $row['antededente']),
			      		'Años_servicio'	=> utf8_encode($row['anos'].' AÑOS '.$row['meses'].' MESES '.$row['dias'].' DIAS'),
			      		'categoria'		=> utf8_encode($row['escuela']),
			      		'escuela'		=> utf8_encode($row['categoria']),
			      		'poblacion'		=> utf8_encode($row['poblacion']),
			      		'telefono'		=> utf8_encode('Tel: '.$row['telefono']),
			      		'celular'		=> utf8_encode('Cel: '.$row['celular']),
			      		//'fecha_nomina'	=> date_format($row["fecha_nomina"],"Y-m-d"),
			      		//'fechaPension'	=> date_format($row["fechaPension"],"Y-m-d"),
			      		'retirado'		=> utf8_encode($row['retirado'])
			      	);
			      	array_push($datos,$registro);
			      }
			      $resultado['code'] = '1';
		          $resultado['status'] = 'success';
		          $resultado['datos'] = $datos;
		          return $resultado;
			    } else {
					if( ($errors = sqlsrv_errors() ) != null) {
				        foreach( $errors as $error ) {
				          $resultado['code'] = '0';
				          $resultado['status'] = "SQLSTATE: ".$error[ 'SQLSTATE']."<br />". "code: ".$error[ 'code']."<br />"."message: ".utf8_encode($error[ 'message'])."<br />";
				          $resultado['datos'] = '';
				          return $resultado;
				        } 
				    } else {
				      	$resultado['code'] = '0';
				        $resultado['status'] = 'Error';
				        $resultado['datos'] = '';
				        return $resultado;
				    }
			    }
			} catch (Exception $e) {
		        $resultado['code'] = '0';
		        $resultado['status'] = 'Error_'.$e->getMessage();
		        $resultado['datos'] = '';
		        return $resultado;
	      	}
		}

		function calculo_retroactivo($folio){
			//return 'mes '.$mes.' año '.$año;
			try {
				$resultado = array(
		          'code'   => '0',
		          'status' => 'inicio',
		          'datos'  => ''
		        );
				require_once "db_config2.php";
				//$consulta = "SELECT * FROM dpAfiliados where idEmpleado = '0000000001459'";
				$consulta = "declare @folio int
					set @folio = '".$folio."'
					SELECT ppi.folio,
						(ppi.nombre+' '+ppi.paterno+' '+ppi.materno) as nombre,
						ppi.antededente,
						fallecio = case ppi.idTipoPension when 1 then ppi.fechaPension 
							when 2 then ppi.fechaPension 
							when 3 then ppi.fechaFallecio end,
						ltrim(rtrim(ISNULL(STUFF((
							SELECT ', ' + descripcion
							FROM( SELECT distinct i.idInstitucion, i.Descripcion
								FROM pePensionadoIngTabulador fp
								LEFT JOIN dpInstituciones i on fp.idInstitucion = i.idInstitucion
								WHERE fp.folioPensionado = @folio
							)Tabla 
							FOR XML PATH('')), 1, 1, ''), ''))) As instituto,
						ltrim(rtrim(ISNULL(STUFF((
							SELECT ', ' + idCategoria1
							FROM( SELECT distinct pc.idCategoria, pc.idCategoria1 FROM pePensionadoIngTabulador fp
								LEFT JOIN peCategorias pc on fp.idcategoria = pc.idcategoria
								WHERE fp.folioPensionado = @folio
							)Tabla 
							FOR XML PATH('')), 1, 1, ''), ''))) AS categoria,
						ppi.porciento,
						ze = case ppi.ze when 2 then 'II' when 3 then 'III' end,
						ppit.sueldo,
						ppit.pension, 
						ppi.nominafecha
					FROM pePensionadosIngresos ppi
					LEFT JOIN pePoblaciones pp on ppi.idPoblacion = pp.idPoblacion
					LEFT JOIN peConceptoPension pcp on ppi.idConceptoPension = pcp.idConceptoPension
					LEFT JOIN (SELECT fp.folioPensionado, SUM(fp.sueldo) as sueldo, SUM(fp.pension) as pension
					from pePensionadoIngTabulador fp
					where fp.folioPensionado = @folio
					group by fp.folioPensionado) ppit on ppi.folio = ppit.folioPensionado
					where ppi.folio = @folio";

				$ejecutar = sqlsrv_query($conn, $consulta);
				$datos = [];
				$registro = '';
			    if($ejecutar){
			      while($row = sqlsrv_fetch_array($ejecutar)){
			      	$registro = array(
			      		//'folio' 	=> utf8_encode($row['folio']),
			      		'nombre' 	=> utf8_encode($row['nombre']),
			      		'antededente'=> utf8_encode(($row['antededente'] == '') ? '-': $row['antededente']),
			      		'fallecio'	=> date_format($row["fallecio"],"Y-m-d"),
			      		'instituto'	=> utf8_encode($row['instituto']),
			      		'categoria'	=> utf8_encode($row['categoria']),
			      		'porciento'	=> utf8_encode($row['porciento'].'%'),
			      		'ze'		=> utf8_encode($row['ze']),
			      		//'sueldo'	=> $row['sueldo'],
			      		'pension'	=> $row['pension'],
			      		'nominafecha'=> date_format($row["nominafecha"],"Y-m-d")
			      	);
			      	array_push($datos,$registro);
			      }
			      $resultado['code'] = '1';
		          $resultado['status'] = 'success';
		          $resultado['datos'] = $datos;
		          return $resultado;
			    } else {
					if( ($errors = sqlsrv_errors() ) != null) {
				        foreach( $errors as $error ) {
				          $resultado['code'] = '0';
				          $resultado['status'] = "SQLSTATE: ".$error[ 'SQLSTATE']."<br />". "code: ".$error[ 'code']."<br />"."message: ".utf8_encode($error[ 'message'])."<br />";
				          $resultado['datos'] = '';
				          return $resultado;
				        } 
				    } else {
				      	$resultado['code'] = '0';
				        $resultado['status'] = 'Error';
				        $resultado['datos'] = '';
				        return $resultado;
				    }
			    }
			} catch (Exception $e) {
		        $resultado['code'] = '0';
		        $resultado['status'] = 'Error_'.$e->getMessage();
		        $resultado['datos'] = '';
		        return $resultado;
	      	}
		}
	}
?>