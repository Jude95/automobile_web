<?php
 include("connect.php" );
 // 配置项
$api = 'https://webapi.sms.mob.com';
$appkey = 'f1952c982c84';

 $name = addslashes($_POST["name"]);
 $account = addslashes($_POST["account"]);
 $password = addslashes($_POST["password"]);
 $code = addslashes($_POST["code"]);

 if ($name == "" || $account == ""  || $password == "") {
 	header("http/1.1 400 Bad Request");
 	$result["error"] =  "name,account或password不能为空";
 }else{
	//发送验证码
	$response = postRequest( $api . '/sms/verify', array(
	    'appkey' => $appkey,
	    'phone' => $account,
	    'zone' => '86',
	    'code' => $code ,
	));
	if ($response->{'status'}==200) {
	 	$query = "SELECT * FROM account WHERE account = '{$account}'";
		$user = mysql_query($query);
	 	if ($row = mysql_fetch_assoc($user)) {
	 		header("http/1.1 400 Bad Request");
	 		$result["error"] =  $name." 已被使用";
	 	}else{
			$query = "INSERT INTO account ( name,account , password,manager ) VALUES ( '{$name}','{$account}','{$password}','false')";
			 if (mysql_query($query)) {
			 	$result["info"] =  "注册成功";
			 }else{
			 	header("http/1.1 500 Internal Server Error");
			 	$result["error"] =  mysql_error();
			 }
	 	}
	}else{
		header("http/1.1 400 Bad Request");
		$result["error"] =  "验证码错误";
	}
}
echo json_encode($result);


 





/**
 * 发起一个post请求到指定接口
 * 
 * @param string $api 请求的接口
 * @param array $params post参数
 * @param int $timeout 超时时间
 * @return string 请求结果
 */
function postRequest( $api, array $params = array(), $timeout = 30 ) {
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $api );
    // 以返回的形式接收信息
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    // 设置为POST方式
    curl_setopt( $ch, CURLOPT_POST, 1 );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );
    // 不验证https证书
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
    curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
        'Accept: application/json',
    ) ); 
    // 发送数据
    $response = curl_exec( $ch );
    // 不要忘记释放资源
    curl_close( $ch );
    return json_decode($response);
}

?>