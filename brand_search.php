<?php
include("../../connect.php");
include("../../token.php");
$user = checkToken(1,$result);
if ($user == -1) {
	echo json_encode($result);
	return;
}
$key = addslashes($_POST["word"]);
$sql="SELECT * FROM brand where word like '%{$key}%' OR name like '%{$key}%'";
$result = array();
$sqlresult = mysql_query($sql);
while($row = mysql_fetch_assoc($sqlresult)){
	$result[] = $row;
}
echo json_encode($result);

?>