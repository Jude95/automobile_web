<?php
 include("connect.php" );

 $result = array("status" => 0,"info" => "","data"=> null);
 $name = addslashes($_POST["name"]);
 $password = addslashes($_POST["password"]);

 if ($name == "" || $password == "") {
 	$result["status"] = 0;
 	$result["info"] =  "name 或 password 不能为空";
 }else{
	 $query = "INSERT INTO person ( name , password ) VALUES ( '".$name."' , '".$password."' )";
	 if (mysql_query($query)) {
	 	$result["status"] = 200;
	 	$result["info"] = $query;
	 }else{
	 	$result["status"] = 0;
	 	$result["info"] =  mysql_error();
	 }
 }
 echo json_encode($result);
?>