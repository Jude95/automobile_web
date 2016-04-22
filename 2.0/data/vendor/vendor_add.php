<?php

include("../../connect.php");
include("../../utils.php");
include("../../token.php");

$user = checkToken(2,$result);

if ($user == -1) {

	echo json_encode($result);

	return;

}

$id = addslashes($_POST["id"]);

$brand_id = addslashes($_POST["brand_id"]);

$name = addslashes($_POST["name"]);

$word = addslashes($_POST["word"]);


$sql = "SELECT * FROM vendor WHERE id = '{$id}'";

$sqlresult = mysql_query($sql);

if ($row = mysql_fetch_assoc($sqlresult)) {

	if (!is_super($user)&&$row["author_id"]!=$user) {

		header("http/1.1 403 Forbidden");

		$result["error"] = "用户权限不足";

		echo json_encode($result);

		return;

	}

	$sql = "UPDATE vendor SET name = '{$name}',brand_id = '{$brand_id}'  WHERE id = '{$id}'";

}else{
	$sql = "INSERT INTO vendor ( name , brand_id ,author_id) VALUES ( '{$name}' , '{$brand_id}','{$user}')";

}



if (mysql_query($sql)) {

	$result["info"] = "编辑成功";

}else{

	header("http/1.1 500 Internal Server Error");

	$result["error"] = "数据库错误";

}
echo json_encode($result);

?>