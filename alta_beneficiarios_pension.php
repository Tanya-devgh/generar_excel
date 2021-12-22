<?php
	require_once "vendor/autoload.php";
	require "clases/class_excels.php";


	# Indicar que usaremos el IOFactory
	use PhpOffice\PhpSpreadsheet\IOFactory;
	//use PhpOffice\PhpSpreadsheet\Settings;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Style;
	use PhpOffice\PhpSpreadsheet\Cell;

	$valores = new excels();
	$fecha = '2021/08/01';
	$resumen = $valores->alta_beneficiarios_pension($fecha);
	//var_dump($resumen);

	if ($resumen['code'] == 1){
		var_dump('Creando Excel');
		$datos = $resumen['datos'];
		$rutaArchivo = "plantilla-alta_beneficiarios_pension.xlsx";
		$documento = IOFactory::load($rutaArchivo);

		$indiceHoja = 0;
		$row_inicial = 7;
		$lista = '';
		$esquema = '';
		$institucion = '';
		$tipopension = '';
    	$titulos = ['lista','esquema', 'institucion','tipopension','codigo','folio', 'nombre','concepto_pension','antededente','Años_servicio','categoria','escuela','poblacion','telefono','celular',/*'fecha_nomina','fechaPension',*/'retirado'];

	    for($row=0; $row < sizeof($datos); $row++){
	    	$dato = $datos[$row];
		    for ($col=0; $col < sizeof($dato); $col++){
		    	$columna = $titulos[$col];
		    	if($columna == 'lista' and  $dato[$columna] != $lista){
		    		//$indiceHoja ++;
		    		$lista = $dato[$columna];
		    		//$HojaPlantilla = $documento->getSheet($indiceHoja);
		    		$hojaActual = clone $documento->getSheetByName('plantilla');
					$hojaActual->setTitle($lista);
					$documento->addSheet($hojaActual);
		    		//$hojaActual = $documento->getSheet($indiceHoja);
				    //$hojaActual->setTitle($esquema);
			    	$hojaActual->setCellValue('C5', 'A PARTIR DEL '.$fecha);
				    $hojaActual->getColumnDimensionByColumn(1)->setWidth('3');
					$hojaActual->getColumnDimensionByColumn(2)->setWidth('8');
					$hojaActual->getColumnDimensionByColumn(2)->setWidth('20');

		    		$renglon = $row_inicial;
		    		$hojaActual->setCellValueByColumnAndRow(3, $renglon, $dato[$columna]);
		    		$coord = $hojaActual->getCellByColumnAndRow(3, $renglon)->getParent()->getCurrentCoordinate();//->getCoordinate();
					$hojaActual->getStyle($coord)->getFont()->setBold(true)->setSize(12);
					$hojaActual->getStyle($coord)->getAlignment()->setHorizontal('center');
		    		$indiceHoja ++;
		    	} else if($columna == 'lista' and  $dato[$columna] == $lista){
		    		//$renglon ++;
		    		$lista = $dato[$columna];
		    	} else if($columna == 'esquema' and  $dato[$columna] != $esquema){
		    		$renglon++;
		    		$hojaActual->setCellValueByColumnAndRow(3, $renglon, $dato[$columna]);
		    		$coord = $hojaActual->getCellByColumnAndRow(3, $renglon)->getParent()->getCurrentCoordinate();
					$hojaActual->getStyle($coord)->getFont()->setBold(true)->setSize(12);
					$hojaActual->getStyle($coord)->getAlignment()->setHorizontal('center');
		    		$esquema = $dato[$columna];
		    	} else if($columna == 'esquema' and  $dato[$columna] == $esquema){
		    		//$renglon ++;
		    		$esquema = $dato[$columna];
		    	} else if($columna == 'institucion' and  $dato[$columna] != $institucion){
		    		$renglon++;
		    		$hojaActual->setCellValueByColumnAndRow(3, $renglon, $dato[$columna]);
		    		$coord = $hojaActual->getCellByColumnAndRow(3, $renglon)->getParent()->getCurrentCoordinate();
					$hojaActual->getStyle($coord)->getFont()->setBold(true)->setSize(12);
					$hojaActual->getStyle($coord)->getAlignment()->setHorizontal('center');
		    		$institucion = $dato[$columna];
		    	} else if($columna == 'institucion' and  $dato[$columna] == $institucion){
		    		//$renglon ++;
		    		$institucion = $dato[$columna];
		    	} else if($columna == 'tipopension' and  $dato[$columna] != $tipopension){
		    		$renglon = $renglon + 2;
		    		$hojaActual->setCellValueByColumnAndRow(3, $renglon, $dato[$columna]);
		    		$coord = $hojaActual->getCellByColumnAndRow(3, $renglon)->getParent()->getCurrentCoordinate();
					$hojaActual->getStyle($coord)->getFont()->setBold(true)->setSize(12);
					$hojaActual->getStyle($coord)->getAlignment()->setHorizontal('center');
		    		$tipopension = $dato[$columna];
		    	} else if($columna == 'tipopension' and  $dato[$columna] == $tipopension){
		    		//$renglon ++;
		    		$tipopension = $dato[$columna];
		    	} else if($columna == 'codigo'){
		    		$renglon = $renglon + 2;
		    		$hojaActual->setCellValueByColumnAndRow(2, $renglon, $dato[$columna]);
		    		$coord = $hojaActual->getCellByColumnAndRow(2, $renglon)->getParent()->getCurrentCoordinate();
		    		$hojaActual->getStyle($coord)->getFont()->setBold(true)->setSize(12);
					$hojaActual->getStyle($coord)->getAlignment()->setHorizontal('left');
		    	} else if($columna == 'folio'){
		    		$renglon ++;
		    		$hojaActual->setCellValueByColumnAndRow(2, $renglon, $dato[$columna]);
		    		$coord = $hojaActual->getCellByColumnAndRow(2, $renglon)->getParent()->getCurrentCoordinate();
					$hojaActual->getStyle($coord)->getAlignment()->setHorizontal('left');
		    	} else if($columna == 'nombre'){
		    		$renglon --;
		    		$hojaActual->setCellValueByColumnAndRow(3, $renglon, $dato[$columna]);
		    		$coord = $hojaActual->getCellByColumnAndRow(3, $renglon)->getParent()->getCurrentCoordinate();
		    		$hojaActual->getStyle($coord)->getFont()->setBold(true)->setSize(12);
					$hojaActual->getStyle($coord)->getAlignment()->setHorizontal('left');
		    	} else {
		    		$renglon ++;
		    		$hojaActual->setCellValueByColumnAndRow(3, $renglon, $dato[$columna]);
		    		$coord = $hojaActual->getCellByColumnAndRow(3, $renglon)->getParent()->getCurrentCoordinate();
					$hojaActual->getStyle($coord)->getAlignment()->setHorizontal('left');
		    	}
		    }
		}

		$sheetIndex = $documento->getIndex(
		    $documento->getSheetByName('plantilla')
		);
		$documento->removeSheetByIndex($sheetIndex);

		$writer = IOFactory::createWriter($documento,'Xlsx');//new Xlsx($documento);
		$nombre = 'alta_beneficiarios_pension_.xlsx';
		$writer->save($nombre);
	} 
	
?>