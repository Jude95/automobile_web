<?php
$con = mysql_connect("rds83e2a4h0k7kklfsxp.mysql.rds.aliyuncs.com","jude","Z4l8PY8g");
if (!$con){
  die('Could not connect: ' . mysql_error());
  echo "connect error";
}
mysql_query("set names 'utf8'");
mysql_select_db("sypei", $con);
?>