<?php
 include("../connect.php" );

 $account = addslashes($_POST["account"]);
$query = "SELECT * FROM account WHERE account = '{$account}'";
	$user = mysql_query($query);
 	if ($row = mysql_fetch_assoc($user)) {
 		$result['exist'] =  true;
 	}else{
 		$result['exist'] =  false;
 	}
echo json_encode($result);

?>