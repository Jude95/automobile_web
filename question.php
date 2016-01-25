<?php
	include("connect.php");
	include("token.php");
	$returnData = array("status" => 0,"info" => "","data"=> null);
	$authorId = checkToken(addslashes($_POST["token"]),$returnData);
	if ($authorId == -1) {
		echo json_encode($returnData);
		return;
	}
	$title = addslashes($_POST["title"]);
	$content = addslashes($_POST["content"]);

	$sql = "INSERT INTO question ( authorId , title , content , date ) 
	VALUES ( '".$authorId."' , '".$title."','{$content}',now())";

	if (mysql_query($sql)) {
		$returnData["status"] = 200;
		$returnData["info"] = $sql;
	}else{
		$returnData["status"] = 0;
		$returnData["info"] = mysql_error();
	}

 	echo json_encode($returnData);
?>