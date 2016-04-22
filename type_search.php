<?php
include("../../connect.php");
include("../../token.php");
$user = checkToken(1,$result);
if ($user == -1) {
	echo json_encode($result);
	return;
}
$key = addslashes($_POST["word"]);
$result = array();
$sql="SELECT  id,name,displacement_tech,time,engine_code FROM type where word like '%{$key}%' OR name like '%{$key}%' OR engine like '%{$key}%'";
$sqlresult = mysql_query($sql);
$result = array();
while($row = mysql_fetch_assoc($sqlresult)){
	$result[] = $row;
}
echo json_encode($result);

?>