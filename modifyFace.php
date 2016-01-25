<?php
 include("connect.php" );
 include("token.php");

 $result = array("status" => 0,"info" => "","data"=> null);
 $face = addslashes($_POST["face"]);
 $authorId = checkToken(addslashes($_POST["token"]),$returnData);
	if ($authorId == -1) {
		echo json_encode($returnData);
		return;
	}
 $query = "UPDATE person SET face = {$face} WHERE id = {$authorId}";

 if (mysql_query($query)) {
 	$result["status"] = 200;
 	$result["info"] = $query;
 }else{
 	$result["status"] = 0;
 	$result["info"] =  mysql_error();
 }
 echo json_encode($result);
?>