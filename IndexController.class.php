<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    function index(){
        echo '呵呵！';
    }

    function getBanner(){
        $this->_ajax(200, '',M('banner')->select());
    }

    function getTrades(){
        $this->_ajax(200, '',M('trade')->order('index asc')->field('id,name')->select());
    }

    function blackSheep(){
        session_start();
        if (isset($_SESSION['admin'])) $this->_ajax(200, '/heihei.php/back/topic/topic');
        $data=array('psw'=>I('post.psw',''),'user'=>I('post.user',''));
        $string=file_get_contents('../appli/doyouknow.json');
        $table=json_decode(gzuncompress($string),true);
        $data['psw']=md5(md5($data['psw']).'jian');
        if (!isset($table[$data['user']])) $this->_ajax(0, '');
        else{
            session_cache_expire(42400);
            $_SESSION['admin']='yes';
            $this->_ajax(200, '/heihei.php/back/topic/topic');
        }
    }

    function modPass(){
        $token=getHeaderToken();
        if (!$token) $this->_norights();
        $where[TYPE?'tokenWeb':'tokenApp']=$token;
        $data=array('old'=>I('post.old',''),'now'=>I('post.now',''),'where'=>$where);
        $res=D('personBrief')->modPwd($data);
        if ($res) $this->_ajax(200, '修改成功！');
        else $this->_ajax(0,$res===false?'原密码错误':'服务器繁忙，请稍后再试');
    }

    function modSign() {
        $token=getHeaderToken();
        if (!$token) $this->_norights();
        $where[TYPE?'tokenWeb':'tokenApp']=$token;
        $data=array('sign'=>I('post.sign',''),'where'=>$where);
        $res=D('personBrief')->modSign($data);
        if ($res) $this->_ajax(200, '修改成功！');
        else $this->_ajax(0,'服务器繁忙，请稍后再试');
    }

    function modName() {
        $token=getHeaderToken();
        if (!$token) $this->_norights();
        $where[TYPE?'tokenWeb':'tokenApp']=$token;
        $data=array('name'=>I('post.name',''),'where'=>$where);
        $res=D('personBrief')->modName($data);
        if ($res) $this->_ajax(200, '修改成功！');
        else $this->_ajax(0,'服务器繁忙，请稍后再试');
    }

    function modFace() {
        $token=getHeaderToken();
        if (!$token) $this->_norights();
        $where[TYPE?'tokenWeb':'tokenApp']=$token;
        $data=array('face'=>I('post.'),'where'=>$where);
        $res=D('personBrief')->modFace($data);
        if ($res) $this->_ajax(200, '修改成功！');
        else $this->_ajax(0,'服务器繁忙，请稍后再试');
    }

    function modBg(){
        $token=getHeaderToken();
        if (!$token) $this->_norights();
        $where[TYPE?'tokenWeb':'tokenApp']=$token;
        $data=array('blogImage'=>I('post.'),'where'=>$where);
        $res=D('personBrief')->modBg($data);
        if ($res) $this->_ajax(200, '修改成功！');
        else $this->_ajax(0,'服务器繁忙，请稍后再试');
    }

}
?>