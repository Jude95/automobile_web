<?php
include("../../connect.php");
include("../../token.php");
$user = checkToken(2,$result);
if ($user == -1) {
	echo json_encode($result);
	return;
}
$part_id = addslashes($_POST["part_id"]);
$model_id = addslashes($_POST["type_id"]);
$note = addslashes($_POST["note"]);


$sql = "INSERT INTO assemble ( part_id , type_id , note,author_id) VALUES (  '{$part_id}','{$type_id}','{$note}','{$user}')";

if (mysql_query($sql)) {
	$result["info"] = "关联成功";
}else{
	header("http/1.1 500 Internal Server Error");
	$result["error"] = "数据库错误".mysql_error();
}

echo json_encode($result);
?>