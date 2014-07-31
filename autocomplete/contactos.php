<?php
include_once('db.php');

$con = conectDB();

//print_r($_POST);
$filter = $_GET["q"];
$user = $_GET["user"];

//Carga Contactos
$query = "SELECT 
				id, nombre, movil
		  FROM 
			jos_zmcontactos
		  WHERE
			(
				nombre like '%s' OR
				movil like '%s' 
			)
			AND usuario = %s
		  ORDER BY nombre
		  LIMIT 20
				";
				
//$result = mysqli_query($con, $query);
$filter = "".$filter."%";
$query = sprintf( $query, $filter, $filter,  $user);
$result = $con->query($query);

while ($data = $result->fetch_object() ) {
	$autocomplete = new stdClass();
	$autocomplete->id = utf8_encode( $data->id);
	$autocomplete->name = utf8_encode( $data->nombre);
	$info[] = $autocomplete;
}

echo json_encode($info);

/*[{"name":"Andres Felipe Quintero","id":"1"},{"name":"Trading Spouses","id":"f5f7ee41d8766edb1a3a67da05f057ac"},{"name":"Trailer Park Boys","id":"e38303c71d98ba5afa8d42509e2ddd5b"},{"name":"Top Gear Australia","id":"2499f2cb8636329169713e59961e497d"},{"name":"Torchwood","id":"631b550716c21bcb342fb18e129a3e8e"},{"name":"Tracey Takes On...","id":"7cfc317ffb8ba69ea57c9ca10113e7db"},{"name":"Top Gear","id":"aa2d4f06e909219ea56940a32582abd8"},{"name":"Top Design","id":"39c0b25a55e3a405b322125c1f220d3f"},{"name":"Top Chef","id":"a0dd745e07064d2b70c92b853c4c2505"},{"name":"Tom and Jerry","id":"bf62e98997138b1e9bebdfe51a52af9f"},{"name":"Tom and Jerry Kids Show","id":"f0edf0f1a3017872c083da14bb4211e8"}]*/
?>

