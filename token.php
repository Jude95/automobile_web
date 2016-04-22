<?php

	/**
	*0:已注册无任何权限
	*1:查看所有数据
	*2.数据编辑
	*3.超级管理员
	*/
	function checkToken($permission,&$returnData){
			$header = getallheaders();
			$token = $header["Token"];
		  	$query = "SELECT user_id,service_begin,manager FROM account RIGHT JOIN token ON account.id = token.user_id WHERE token = '".$token."'";
			$result = mysql_query($query);
			if ($row = mysql_fetch_array($result)) {
				$cur_permission = 0;
				if (strtotime("{$row["service_begin"]} +1 year") > time()) {
					$cur_permission = 1;
				}
				if ($row["manager"]==2) {
					$cur_permission = 2;
				}
				if ($row["manager"]==3) {
					$cur_permission = 3;
				}
				if ($cur_permission >= $permission) {
					return $row["user_id"];
				}else{
					header("http/1.1 403 Forbidden");
					$returnData["error"] = "用户权限不足";
					return -1;
				}
			}else{
				header("http/1.1 401 Unauthorized");
				$returnData["error"] = "token:".$token."无效";
			 	return -1;
			}
	}
    
	function create_unique($id) {    
		$data = $_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'] .time() . rand();   
		$data = sha1($data);
		mysql_query("DELETE FROM token WHERE user_id = ".$id);
		mysql_query("INSERT INTO token (token,user_id) VALUES ( '".$data."','".$id."' )") ;
		return $data;      
	}

	function getallheaders()   
    {  
       foreach ($_SERVER as $name => $value)   
       {  
           if (substr($name, 0, 5) == 'HTTP_')   
           {  
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;  
           }  
       }  
       return $headers;  
    } 
?>