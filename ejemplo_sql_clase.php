<?php
	require_once "vendor/autoload.php";
	require "clases/class_excels.php";


	# Indicar que usaremos el IOFactory
	use PhpOffice\PhpSpreadsheet\IOFactory;

	$valores = new excels();
	$resumen = $valores->ejemplo_afiliado();
	var_dump($resumen);

	if ($resumen['code'] == 1){
		$datos = $resumen['datos'];
		$rutaArchivo = "plantilla1.xlsx";
		$documento = IOFactory::load($rutaArchivo);

		$indiceHoja = 0;
	    $hojaActual = $documento->getSheet($indiceHoja);
	    $hojaActual->setTitle('afiliados');

	    $titulos = ['idInstitucion', 'idEmpleado','nombre'];


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
	} 
	
?>