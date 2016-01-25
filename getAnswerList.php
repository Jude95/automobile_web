<?php
	include("connect.php");
	$returnData = array("status" => 0,"info" => "","data"=> null);
	$dataInfo = array("totalCount" => 0,"totalPage" =>0,"answers"=> null);

	$page = addslashes($_POST["page"]);
	$count = addslashes($_POST["count"]);
	$desc = addslashes($_POST["desc"]);
	$id = addslashes($_POST["questionId"]);
	if ($count == 0) {
		$count = 20;
	}
	
	$sql = "SELECT answer.id,answer.content,answer.date,answer.authorId,person.name as authorName,person.face as authorFace FROM person RIGHT JOIN answer ON person.id = answer.authorId WHERE questionId = {$id} ORDER BY date ".($desc == "true"?"DESC":"")." LIMIT ".($page*$count).",".$count;

	$result = mysql_query($sql);

	while($row = mysql_fetch_assoc($result)){
		$data[] = $row;
	}
	$totalCount = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS count FROM answer WHERE questionId = ".$id));

	$dataInfo["answers"] = $data;
	$dataInfo["totalCount"] = (int)$totalCount['count'];
	$dataInfo["curPage"] = $page;
	$dataInfo["totalPage"] = (int)($totalCount['count']/$count)+1;

	$returnData["status"] = 200;
	$returnData["info"] = $sql;
	$returnData["data"] = $dataInfo;
	echo json_encode($returnData);
?>