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
	part.id,
	type,
	brand,
	avatar,
	note,
	drawing_number ,
	picture
 FROM assemble LEFT JOIN part ON assemble.part_id =  part.id where part.id = '{$id}' LIMIT 1";
$sqlresult = mysql_query($sql);

if($row = mysql_fetch_assoc($sqlresult)){
	$result = $row;
	$result["picture_full"] = json_decode($result["picture"],true);
	foreach ($result["picture_full"] as $image) {
		$image_str[] = $image["url"];
	}
	$result["picture"] = $image_str;
}
echo json_encode($result);
?>