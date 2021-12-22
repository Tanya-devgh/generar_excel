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
	$folio = '1745';
	$resumen = $valores->calculo_retroactivo($folio);
	var_dump($resumen);

	if ($resumen['code'] == 1){
		//var_dump('Creando Excel');
		$datos = $resumen['datos'];
		$rutaArchivo = "plantilla-calculo_retroactivo.xlsx";
		$documento = IOFactory::load($rutaArchivo);

		$indiceHoja = 0;
		$row_inicial = 6;
		$nombre = '';
    	$titulos = [/*'folio',*/'nombre', 'antededente','fallecio','instituto','categoria', 'porciento','ze',/*'sueldo',*/'pension','nominafecha'];

	    for($row=0; $row < sizeof($datos); $row++){
	    	$dato = $datos[$row];
		    for ($col=0; $col < sizeof($dato); $col++){
		    	$columna = $titulos[$col];
		    	if($columna == 'nombre' and  $dato[$columna] != $nombre){
		    		$nombre = $dato[$columna];
		    		
		    		$hojaActual = clone $documento->getSheetByName('plantilla');
					$hojaActual->setTitle($nombre);
					$documento->addSheet($hojaActual);

			    	$hojaActual->setCellValue('B6',$nombre);
					$hojaActual->getStyle('B6')->getFont()->setBold(true)->setSize(11);
					$hojaActual->getStyle('B6')->getAlignment()->setHorizontal('left');
		    	} else if($columna == 'antededente'){
		    		$hojaActual->setCellValue('B8',$dato[$columna]);
					$hojaActual->getStyle('B8')->getFont()->setBold(true)->setSize(11);
					$hojaActual->getStyle('B8')->getAlignment()->setHorizontal('left');
		    	} else if($columna == 'fallecio'){
		    		$hojaActual->setCellValue('B9',$dato[$columna]);
					$hojaActual->getStyle('B9')->getFont()->setBold(true)->setSize(11);
					$hojaActual->getStyle('B9')->getAlignment()->setHorizontal('left');
		    	} else if($columna == 'instituto'){
		    		$hojaActual->setCellValue('F6',$dato[$columna]);
					$hojaActual->getStyle('F6')->getFont()->setBold(true)->setSize(11);
					$hojaActual->getStyle('F6')->getAlignment()->setHorizontal('left');
		    	} else if($columna == 'categoria'){
		    		$hojaActual->setCellValue('F7',$dato[$columna]);
					$hojaActual->getStyle('F7')->getFont()->setBold(true)->setSize(11);
					$hojaActual->getStyle('F7')->getAlignment()->setHorizontal('left');
		    	} else if($columna == 'porciento'){
		    		$hojaActual->setCellValue('F8',$dato[$columna]);
					$hojaActual->getStyle('F8')->getFont()->setBold(true)->setSize(11);
					$hojaActual->getStyle('F8')->getAlignment()->setHorizontal('left');
		    	} else if($columna == 'ze'){
		    		$hojaActual->setCellValue('F9',$dato[$columna]);
					$hojaActual->getStyle('F9')->getFont()->setBold(true)->setSize(11);
					$hojaActual->getStyle('F9')->getAlignment()->setHorizontal('left');
		    	} else if($columna == 'pension'){
		    		$hojaActual->getStyle('B12')->getFill()->setFillType('solid')->getStartColor()->setARGB('81B0E4');
		    		$hojaActual->setCellValue('C12',$dato[$columna]);
					$hojaActual->getStyle('C12')->getFont()->setBold(true)->setSize(11);
		    		$hojaActual->getStyle('C12')->getFill()->setFillType('solid')->getStartColor()->setARGB('81B0E4');
					$hojaActual->getStyle('C12')->getAlignment()->setHorizontal('left');
		    		$hojaActual->getStyle('D12')->getFill()->setFillType('solid')->getStartColor()->setARGB('81B0E4');
		    		$hojaActual->getStyle('E12')->getFill()->setFillType('solid')->getStartColor()->setARGB('81B0E4');
		    		$hojaActual->getStyle('F12')->getFill()->setFillType('solid')->getStartColor()->setARGB('81B0E4');
		    		$hojaActual->getStyle('B15')->getFill()->setFillType('solid')->getStartColor()->setARGB('81B0E4');
		    	} else if($columna == 'nominafecha'){
		    		// meses de retroactivo
		    		//var_dump($dato['fallecio'].' '.$dato[$columna]);
		    		var_dump($dato[$columna]);
		    	} else {
		    		var_dump($titulos[$col].' '.$dato[$columna]);
		    	}
		    }
		}

		$sheetIndex = $documento->getIndex(
		    $documento->getSheetByName('plantilla')
		);
		$documento->removeSheetByIndex($sheetIndex);

		$writer = IOFactory::createWriter($documento,'Xlsx');//new Xlsx($documento);
		$nombre = 'calculo_retroactivo_.xlsx';
		$writer->save($nombre);
	} 
	
?>