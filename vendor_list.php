<?php
include("../../connect.php");
include("../../token.php");
$user = checkToken(1,$result);
if ($user == -1) {
	echo json_encode($result);
	return;
}
$id = addslashes($_POST["id"]);
$sql="	SELECT 
			brand.name as line_name,
			brand.id as line_id,
			brand.avatar as line_avatar,
			vendor.id,
			vendor.name
		FROM brand RIGHT JOIN vender ON brand.id = vender.brand_id 
		where brand.id = '{$id}'";
$result = array();
$sqlresult = mysql_query($sql);
$result = array();
while($row = mysql_fetch_assoc($sqlresult)){
	$result[] = $row;
}
echo json_encode($result);
?>