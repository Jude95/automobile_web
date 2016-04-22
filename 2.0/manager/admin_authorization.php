<?php
include("../connect.php");
include("../token.php");
$user = checkToken(3,$result);
if ($user == -1) {
	echo json_encode($result);
	return;
}

$userId = $_POST['userId'];
$sql = "UPDATE account SET service_begin = now() WHERE id = '{$userId}'";
 if (mysql_query($sql)) {
 	$result["info"] = "修改成功";
 }else{
 	header("http/1.1 500 Internal Server Error");
 	$result["error"] =  mysql_error();
 }
 echo json_encode($result);
?>