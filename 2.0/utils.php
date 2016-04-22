<?php

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
    return $response;
}

function delete_model($id){
    $sql="DELETE FROM assemble WHERE model_id = '{$id}'";
    mysql_query($sql);
    $sql="DELETE FROM model WHERE id = '{$id}'";
    mysql_query($sql);
    return 0;
}

function delete_type($id){
    $sql = "SELECT id FROM model WHERE type_id = '{$id}'";
    $sqlresult = mysql_query($sql);
    while($row = mysql_fetch_assoc($sqlresult)){
        delete_model($row["id"]);
    }
    $sql="DELETE FROM type WHERE id = '{$id}'";
    mysql_query($sql);
}

function delete_line($id){
    $sql = "SELECT id FROM type WHERE line_id = '{$id}'";
    $sqlresult = mysql_query($sql);
    while($row = mysql_fetch_assoc($sqlresult)){
        delete_type($row["id"]);
    }
    $sql="DELETE FROM line WHERE id = '{$id}'";
    mysql_query($sql);
}

function is_super($id){
    $sql = "SELECT manager FROM account WHERE id = {$id}";
    $sqlresult = mysql_query($sql);
    if ($row = mysql_fetch_assoc($sqlresult)) {
        return $row["manager"] >= 3;
    }
    return false;
}
?>