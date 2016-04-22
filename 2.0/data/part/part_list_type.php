<?php
include("../../connect.php");
include("../../token.php");
$user = checkToken(1,$result);
if ($user == -1) {
	echo json_encode($result);
	return;
}
$type = addslashes($_POST["type"]);
$drawing_number = addslashes($_POST["brand"]);
$sql="SELECT 
	id,
	type,
	brand,
	avatar,
	drawing_number 
 FROM part where type = '{$type}' AND drawing_number LIKE '%{$drawing_number}%' ";

$sqlresult = mysql_query($sql);
$result = array();
while($row = mysql_fetch_assoc($sqlresult)){
	$result[] = $row;
}
echo json_encode($result);
?>