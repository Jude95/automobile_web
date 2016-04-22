<?php
include("../connect.php");
include("../token.php");
$user = checkToken(2,$result);
if ($user == -1) {
	echo json_encode($result);
	return;
}

$query = "SELECT * FROM account WHERE id = {$user}";
$result = mysql_query($query);
 if ($row = mysql_fetch_assoc($result)) {
 	$row["service_begin"] = strtotime($row["service_begin"]);
 	$row["token"]  = create_unique($row["id"]);
 	unset($row['password']);
 }else{
 	header("http/1.1 400 Bad Request");
	$row=array("error" => "账号密码错误");
 }
 echo json_encode($row);

?>