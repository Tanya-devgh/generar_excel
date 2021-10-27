<?php
	require_once "vendor/autoload.php";
	require "clases/class_excels.php";


	# Indicar que usaremos el IOFactory
	use PhpOffice\PhpSpreadsheet\IOFactory;

	$valores = new excels();
	$resumen = $valores->alta_beneficiarios_pension('08','2021');
	var_dump($resumen);

	if ($resumen['code'] == 1){
		$datos = $resumen['datos'];
		$rutaArchivo = "plantilla-alta_beneficiarios_pension.xlsx";
		$documento = IOFactory::load($rutaArchivo);

		$indiceHoja = -1;
		$row_inicial = 6;
		$esquema = '';
		$institucion = '';
    	$titulos = ['esquema', 'institucion','codigo','folio', 'nombre','concepto_pension','antededente','Años_servicio','categoria','escuela','poblacion','telefono','celular','fecha_nomina','fechaPension','retirado'];

	    for($row=0; $row < sizeof($datos); $row++){
	    	$dato = $datos[$row];
		    for ($col=0; $col < sizeof($dato); $col++){
		    	$renglon = $row + $row_inicial;
		    	$columna = $titulos[$col];
		    	if($titulos[$col] == 'esquema' and  $dato[$columna] != $esquema){
		    		$indiceHoja ++;
		    		$esquema = $dato[$columna];
		    		$hojaActual = $documento->getSheet($indiceHoja);
				    $hojaActual->setTitle($esquema);
			    	$hojaActual->setCellValue('C5', 'A PARTIR DEL '.$fecha);
				    $hojaActual->getColumnDimensionByColumn(1)->setWidth('13');
					$hojaActual->getColumnDimensionByColumn(2)->setWidth('30');

		    		$renglon = $renglon + 2;
		    		$hojaActual->setCellValueByColumnAndRow(3, $renglon, $dato[$columna]);
		    	} else if($titulos[$col] == 'institucion' and  $dato[$columna] != $institucion){
		    		$hojaActual->setCellValueByColumnAndRow(3, $renglon, $dato[$columna]);
		    		$institucion = $dato[$columna];
		    	} else if($titulos[$col] == 'codigo' || $titulos[$col] == 'folio'){
		    		$hojaActual->setCellValueByColumnAndRow(2, $renglon, $dato[$columna]);
		    	} else {
		    		$hojaActual->setCellValueByColumnAndRow(3, $renglon, $dato[$columna]);
		    	}
		    }
		}
	    

		$writer = IOFactory::createWriter($documento,'Xlsx');//new Xlsx($documento);

		$writer->save('Afiliados.xlsx');
	} 
	
?>