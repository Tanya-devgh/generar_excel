<?php
	require_once "vendor/autoload.php";

	# Indicar que usaremos el IOFactory
	use PhpOffice\PhpSpreadsheet\IOFactory;

	# Recomiendo poner la ruta absoluta si no está junto al script
	# Nota: no necesariamente tiene que tener la extensión XLSX
	$rutaArchivo = "hello world.xlsx";
	$documento = IOFactory::load($rutaArchivo);

	# Recuerda que un documento puede tener múltiples hojas
	# obtener conteo e iterar
	//$totalDeHojas = $documento->getSheetCount();
	# Obtener hoja en el índice que vaya del ciclo
	$indiceHoja = 0;
    $hojaActual = $documento->getSheet($indiceHoja);

    $hojaActual->setCellValue('A5', 'segundo ejemplo!');

	$writer = IOFactory::createWriter($documento,'Xlsx');//new Xlsx($documento);
	$writer->save('hello world.xlsx');
?>