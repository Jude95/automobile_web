<?php
	function checkToken($token,&$returnData){
		  	$query = "SELECT user_id FROM token WHERE token = '".$token."'";
			$result = mysql_query($query);
			if ($row = mysql_fetch_array($result)) {
			 	return $row["user_id"];
			}else{
				header("http/1.1 401 Unauthorized");
				$returnData["error"] = "token:".$token." 无效";
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
?>