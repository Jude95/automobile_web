<?php
include("../connect.php");
include("../token.php");
$user = checkToken(3,$result);
if ($user == -1) {
	echo json_encode($result);
	return;
}

$sql = "SELECT * FROM account";
$sqlresult = mysql_query($sql);
while($row = mysql_fetch_assoc($sqlresult)){
	unset($row["password"]);
	$row["service_begin"] = strtotime($row["service_begin"]);
	$row["manager"]=$row["manager"]>=1?true:false;
	$result[] = $row;
}
echo json_encode($result);
?>