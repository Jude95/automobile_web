<?php
include("connect.php");
include("token.php");

$account = addslashes($_POST["account"]);
$password = md5(addslashes($_POST["password"]));

$query = "SELECT * FROM account WHERE account = '".$account."' AND password = '".$password."'";
$result = mysql_query($query);
 if ($row = mysql_fetch_assoc($result)) {
 	$row["service_begin"] = strtotime($row["service_begin"]);
 	$row["token"]  = create_unique($row["id"]);
 	$row["manager"] = $row["manager"]>=1?true:false;
 	unset($row['password']);
 }else{
 	header("http/1.1 400 Bad Request");
	$row=array("error" => "账号密码错误");
 }
 echo json_encode($row);
?>