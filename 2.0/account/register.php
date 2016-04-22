<?php
 include("../connect.php" );
 include("../utils.php");
 // 配置项
$api = 'https://webapi.sms.mob.com/sms/verify';
$appkey = 'f24bd7349e2c';

 $name = addslashes($_POST["name"]);
 $account = addslashes($_POST["account"]);
 $password = md5(addslashes($_POST["password"]));
 $code = addslashes($_POST["code"]);

 if ($name == "" || $account == ""  || $password == "") {
 	header("http/1.1 400 Bad Request");
 	$result["error"] =  "name,account或password不能为空";
 }else{
	//发送验证码
	$response = postRequest( $api, array(
	    'appkey' => $appkey,
	    'phone' => $account,
	    'zone' => '86',
	    'code' => $code ,
	));
	$response_json = json_decode($response,true);
	if ($response_json['status'] == 200) {
	 	$query = "SELECT * FROM account WHERE account = '{$account}'";
		$user = mysql_query($query);
	 	if ($row = mysql_fetch_assoc($user)) {
	 		header("http/1.1 400 Bad Request");
	 		$result["error"] =  $name." 已被使用";
	 	}else{
			$query = "INSERT INTO account ( name,account , password,manager ) VALUES ( '{$name}','{$account}','{$password}','0')";
			 if (mysql_query($query)) {
			 	$result["info"] =  "注册成功";
			 }else{
			 	header("http/1.1 500 Internal Server Error");
			 	$result["error"] =  mysql_error();
			 }
	 	}
	}else if ($response_json['status']==468) {
		header("http/1.1 400 Bad Request");
		$result["error"] =  "验证码错误";
	}else{
		header("http/1.1 400 Bad Request");
		$result["error"] =  "验证平台".$response;
	}
}
echo json_encode($result);

?>