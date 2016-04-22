<?php
include("../../connect.php");
include("../../token.php");
include("../../utils.php");
$user = checkToken(2,$result);
if ($user == -1) {
	echo json_encode($result);
	return;
}

$id = addslashes($_POST["id"]);

$sql = "SELECT * FROM assemble WHERE id = '{$id}'";
$sqlresult = mysql_query($sql);
if ($row = mysql_fetch_assoc($sqlresult)) {
	if (!is_super($user)&&$row["author_id"]!=$user) {
		header("http/1.1 403 Forbidden");
		$result["error"] = "用户权限不足";
		echo json_encode($result);
		return;
	}
}

delete_assemble($id);

$result["info"] = "删除成功";
echo json_encode($result);

function delete_assemble($id){
	$sql="DELETE FROM assemble WHERE id = '{$id}'";
	mysql_query($sql);
}
?>