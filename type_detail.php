<?php
include("../../connect.php");
include("../../token.php");
$user = checkToken(1,$result);
if ($user == -1) {
	echo json_encode($result);
	return;
}
$id = addslashes($_POST["id"]);
$sql="SELECT 
		line.id as line_id,
		line.name as line_name,
		type.name, 
		power , 
		displacement, 
		cylinders, 
		valve,
		structure, 
		drive,
		fuel, 
		fuel_feed, 
		tecdoc, 
		engine_code, 
		engine, 
		time,
		displacement_tech,
		type.word
	FROM line RIGHT JOIN type ON type.line_id = line.id where type.id = '{$id}' limit 1";
$sqlresult = mysql_query($sql);
if($row = mysql_fetch_assoc($sqlresult)){
	$result = $row;
}
echo json_encode($result);
?>