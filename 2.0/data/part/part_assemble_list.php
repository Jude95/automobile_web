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
	assemble.id as id,
	assemble.note as note,
	type.id as type_id,
	type.name as type_name,
	part.id as part_id,
	part.type as part_type,
	part.brand as part_brand,
	part.avatar as part_avatar,
	part.drawing_number as part_draw_number
 FROM (assemble LEFT JOIN type ON assemble.type_id = type.id) LEFT JOIN part ON assemble.part_id =  part.id where type_id = '{$id}'";
$sqlresult = mysql_query($sql);
$result = array();
while($row = mysql_fetch_assoc($sqlresult)){
	$result[] = $row;
}
echo json_encode($result).mysql_error();
?>