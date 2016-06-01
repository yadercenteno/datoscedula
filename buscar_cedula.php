<?php
$cedula = filter_var($_GET["cedula"], FILTER_SANITIZE_STRING);
$datos_a_buscar0 = array('cedula' => $cedula, 'tipo' => 'D');
$datos_a_buscar = http_build_query($datos_a_buscar0);

$ch = curl_init();

curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,5);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $datos_a_buscar);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
curl_setopt($ch, CURLOPT_URL, "http://www.cse.gob.ni/buscarcv.php");

$return = curl_exec($ch);

function sacarle_datos($resultado, $campo) {
    $pattern = "/<b>$campo<\/b>([\w\W]*?)<br>/";
    preg_match($pattern, $resultado, $matches);
    return $matches[1];
}

if(!$return){
    echo 'No se pudo ejecutar la consulta.  Error CURL: '.curl_error($ch) . '" - Codigo del error: ' . curl_errno($ch);                            
}
else {	
	curl_close($ch);

	$a = array();
    	$b = array();

	for($i = 0; $i < 1; $i++) {
    	$b["nombre"] = utf8_encode(trim(sacarle_datos($return, "NOMBRE:")));
		$b["cedula"] = $cedula;
		$b["direccion"] = utf8_encode(trim(sacarle_datos($return, "DIRECCION:")));
		$b["municipio"] = utf8_encode(trim(sacarle_datos($return, "MUNICIPIO:")));
		$b["departamento"] = utf8_encode(trim(sacarle_datos($return, "DEPARTAMENTO:")));

		array_push($a,$b);
	}

	$cadena = $a;

	echo json_encode($cadena);
}
