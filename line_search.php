<?php
include("../../connect.php");
include("../../token.php");
$user = checkToken(1,$result);
if ($user == -1) {
	echo json_encode($result);
	return;
}
$key = addslashes($_POST["word"]);
$sql="	SELECT 
			line.id,
			brand.name as line_name,
			brand.id as line_id,
			line.name,
			line.word 
		FROM vendor RIGHT JOIN line ON vendor.id = line.vendor_id 
		where line.word like '%{$key}%' OR line.name like '%{$key}%'";

$sqlresult = mysql_query($sql);
$result = array();
while($row = mysql_fetch_assoc($sqlresult)){
	$result[] = $row;
}
echo json_encode($result);
?>