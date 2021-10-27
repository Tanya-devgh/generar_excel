<?php
$serverName = "172.31.1.70"; 
$connectionInfo = array( "Database"=>"prueba", "UID"=>"ci", "PWD"=>"D1detr@");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
     //echo "Conexión establecida.<br />";
}else{
     //echo "Conexión no se pudo establecer.<br />";
     die( print_r( sqlsrv_errors(), true));
}

?>