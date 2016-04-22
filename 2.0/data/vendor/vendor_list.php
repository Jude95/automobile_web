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
			brand.name as brand_name,
			brand.id as brand_id,
			brand.avatar as brand_avatar,
			vendor.id,
			vendor.name
		FROM brand RIGHT JOIN vendor ON brand.id = vendor.brand_id 
		where vendor.brand_id = '{$id}'";
$result = array();
$sqlresult = mysql_query($sql);
while($row = mysql_fetch_assoc($sqlresult)){
	$result[] = $row;
}
echo json_encode($result);
?>