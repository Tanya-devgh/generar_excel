<?php
	require_once "vendor/autoload.php";
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
    } else {
		if( ($errors = sqlsrv_errors() ) != null) {
	        foreach( $errors as $error ) {
	        	$registro = array(
		      		'idInstitucion' => '0',
		      		'idEmpleado'	=> '0',
		      		'nombre'		=> "SQLSTATE: ".$error[ 'SQLSTATE']."<br />". "code: ".$error[ 'code']."<br />"."message: ".$error[ 'message']."<br />"
		      	);
		      	array_push($datos,$registro);
	          /*$resultado['code'] = '0';
	          $resultado['status'] = "SQLSTATE: ".$error[ 'SQLSTATE']."<br />". "code: ".$error[ 'code']."<br />"."message: ".$error[ 'message']."<br />";
	          $resultado['registros'] = '';
	          echo json_encode($resultado);*/
	        } 
	    } else {
	    	$registro = array(
	      		'idInstitucion' => '0',
	      		'idEmpleado'	=> '0',
	      		'nombre'		=> 'error'
	      	);
	      	array_push($datos,$registro);
	    }
    }

	# Indicar que usaremos el IOFactory
	use PhpOffice\PhpSpreadsheet\IOFactory;

	# Recomiendo poner la ruta absoluta si no está junto al script
	# Nota: no necesariamente tiene que tener la extensión XLSX
	$rutaArchivo = "plantilla1.xlsx";
	$documento = IOFactory::load($rutaArchivo);

	# Recuerda que un documento puede tener múltiples hojas
	# obtener conteo e iterar
	//$totalDeHojas = $documento->getSheetCount();
	# Obtener hoja en el índice que vaya del ciclo
	$indiceHoja = 0;
    $hojaActual = $documento->getSheet($indiceHoja);
    $hojaActual->setTitle('afiliados');

   //$hojaActual->setCellValue('A5', 'segundo ejemplo!');
    $titulos = ['idInstitucion', 'idEmpleado','nombre'];
    
    //$hojaActual->getColumnDimension('A')->setWidth('5');
    //$hojaActual->getColumnDimension('B')->setWidth('15');
    //$hojaActual->getColumnDimension('C')->setWidth('25');

    //$hojaActual->setCellValue('A7', $titulos[0]);
    //$hojaActual->setCellValue('B7', $titulos[1]);
    //$hojaActual->setCellValue('C7', $titulos[2]);

    $row_inicial = 7;
    $hojaActual->getColumnDimensionByColumn(1)->setWidth('13');
	$hojaActual->getColumnDimensionByColumn(2)->setWidth('15');
	$hojaActual->getColumnDimensionByColumn(3)->setWidth('25');
    for($i=0; $i < sizeof($titulos); $i++){
    	$hojaActual->setCellValueByColumnAndRow($i+1, $row_inicial, $titulos[$i]);
    }
    $row_inicial++;


    for($row=0; $row <sizeof($datos); $row++){
    	$dato = $datos[$row];
	    for ($col=0; $col < sizeof($dato); $col++){
	    	$renglon = $row + $row_inicial;
	    	$columna = $titulos[$col];
	    	$hojaActual->setCellValueByColumnAndRow($col+1, $renglon, $dato[$columna]);
	    }
	}
    

	$writer = IOFactory::createWriter($documento,'Xlsx');//new Xlsx($documento);

	$writer->save('Afiliados.xlsx');
?>