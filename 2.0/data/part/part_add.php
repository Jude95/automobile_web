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
$type = addslashes($_POST["type"]);
$brand = addslashes($_POST["brand"]);
$drawing_number = addslashes($_POST["drawing_number"]);
$avatar = addslashes($_POST["avatar"]);
$note = addslashes($_POST["note"]);
$picture = addslashes($_POST["picture"]);

//将图片url转换成图片信息对象，再转换成json保存数据库
$pics = explode(",",$picture);
foreach ($pics as $pic) {
	$image = postRequest($pic."?imageInfo");
	$image_json = json_decode($image,true);
	$image_json["url"] = $pic;
	$images[] = $image_json;
}
$images = json_encode($images);

$sql = "SELECT * FROM part WHERE id = '{$id}'";
$sqlresult = mysql_query($sql);
if ($row = mysql_fetch_assoc($sqlresult)) {
	if (!is_super($user)&&$row["author_id"]!=$user) {
		header("http/1.1 403 Forbidden");
		$result["error"] = "用户权限不足";
		echo json_encode($result);
		return;
	}

	$sql = "UPDATE part SET 
		type = '{$type}' , 
		brand = '{$brand}', 
		drawing_number = '{$drawing_number}', 
		avatar = '{$avatar}', 
		picture = '{$images}',
		note = '{$note}'
		WHERE id = '{$id}'";
}else{
	$sql = "INSERT INTO part ( type , brand , drawing_number, avatar,picture,author_id,note) VALUES (  '{$type}','{$brand}','{$drawing_number}','{$avatar}','{$images}','{$user}','{$note}')";
}
if (mysql_query($sql)) {
	$result["info"] = "编辑成功";
}else{
	header("http/1.1 500 Internal Server Error");
	$result["error"] = "数据库错误".mysql_error();
}

echo json_encode($result);
?>