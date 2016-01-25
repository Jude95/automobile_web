<?php
	include("connect.php");
	include("token.php");
	$returnData = array("status" => 0,"info" => "","data"=> null);
	$authorId = checkToken(addslashes($_POST["token"]),$returnData);
	if ($authorId == -1) {
		echo json_encode($returnData);
		return;
	}
	$questionId = addslashes($_POST["questionId"]);
	$content = addslashes($_POST["content"]);
	$date = time();
	$sql = "INSERT INTO answer ( authorId , questionId , content , date ) VALUES ( '".$authorId."' , '".$questionId."','".$content."',now())";
	if (mysql_query($sql)) {
		$update = "UPDATE question SET answerCount=answerCount+1 , recent = now() WHERE id = {$questionId}";
		mysql_query($update);
		$returnData["status"] = 200;
		$returnData["info"] = $sql.mysql_error();
	}else{
		$returnData["status"] = 0;
		$returnData["info"] = $sql.mysql_error();
	}

 	echo json_encode($returnData);

?>