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
$line_id = addslashes($_POST["line_id"]);
$name = addslashes($_POST["name"]);
$power = addslashes($_POST["power"]);
$displacement = addslashes($_POST["displacement"]);
$cylinders = addslashes($_POST["cylinders"]);
$valve = addslashes($_POST["valve"]);
$structure = addslashes($_POST["structure"]);
$drive = addslashes($_POST["drive"]);
$fuel = addslashes($_POST["fuel"]);
$fuel_feed = addslashes($_POST["fuel_feed"]);
$tecdoc = addslashes($_POST["tecdoc"]);
$engine_code = addslashes($_POST["engine_code"]);
$engine = addslashes($_POST["engine"]);
$time = addslashes($_POST["time"]);
$displacement_tech = addslashes($_POST["displacement_tech"]);
$word = addslashes($_POST["word"]);

$sql = "SELECT * FROM type WHERE id = '{$id}'";
$sqlresult = mysql_query($sql);
if ($row = mysql_fetch_assoc($sqlresult)) {
	if (!is_super($user)&&$row["author_id"]!=$user) {
		header("http/1.1 403 Forbidden");
		$result["error"] = "用户权限不足";
		echo json_encode($result);
		return;
	}

	$sql = "UPDATE type SET 
			line_id = '{$line_id}' , 
			name = '{$name}' , 
			power = '{$power}' ,
			displacement = '{$displacement}' ,
			cylinders = '{$cylinders}' ,
			valve = '{$valve}' ,
			structure = '{$structure}' ,
			drive = '{$drive}' ,
			fuel = '{$fuel}' ,
			fuel_feed = '{$fuel_feed}' ,
			tecdoc = '{$tecdoc}' ,
			engine_code = '{$engine_code}' ,
			engine = '{$engine}' ,
			time = '{$time}' ,
			displacement_tech = '{$displacement_tech}' ,
			word = '{$word}' 
		WHERE id = '{$id}'";

}else{
	$sql = "INSERT INTO type ( 
		line_id,
		name , 
		power , 
		displacement, 
		cylinders, 
		valve,
		structure, 
		drive,
		fuel, 
		fuel_feed, 
		tecdoc, 
		engine_code, 
		engine, 
		time,
		displacement_tech,
		word,
		author_id) 
	VALUES ( 
		'{$line_id}',
		'{$name}' , 
		'{$power}' , 
		'{$displacement}', 
		'{$cylinders}', 
		'{$valve}', 
		'{$structure}', 
		'{$drive}',
		'{$fuel}', 
		'{$fuel_feed}', 
		'{$tecdoc}', 
		'{$engine_code}', 
		'{$engine}', 
		'{$time}',
		'{$displacement_tech}',
		'{$word}',
		'{$user}'
		)";
}

if (mysql_query($sql)) {
	$result["info"] = "编辑成功";
}else{
	header("http/1.1 500 Internal Server Error");
	$result["error"] = "数据库错误:".mysql_error();
}

echo json_encode($result);
?>