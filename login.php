<?php
include("connect.php");
include("token.php");
 $returnData = array("status" => 0,"info" => "","data"=> null);
 $name = addslashes($_POST["name"]);
 $password = addslashes($_POST["password"]);

  $query = "SELECT * FROM person WHERE name = '".$name."' AND password = '".$password."'";
  $result = mysql_query($query);
 if ($row = mysql_fetch_assoc($result)) {
 	$row["token"]  = create_unique($row["id"]);
 	$returnData["status"] = 200;
 	$returnData["info"] = $query;
 	$returnData["data"] = $row;
 }else{
 	$returnData["status"] = 0;
 	$returnData["info"] =  "账号密码错误";
 }
 echo json_encode($returnData);
?>