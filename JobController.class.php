<?php 
	namespace Home\Controller;
	use Think\Controller;
	class JobController extends Controller {
        function addJob(){
            $token = getHeaderToken();
            if ($token==false) $this->_norights();
            $seller=M('seller')->getbytoken($token);
            if ($seller===null||$seller['status']!=3) $this->_ajax(400,'你的权限不足！');
            $job=M('job');
            $data=$job->create();
            if (isset($data['jobId'])) unset($data['jobId']);
            if (!isset($data['jobAddCode'])||$data['jobAddCode']==0) $this->_ajax(0,'请确认地图位置');
            //if (isset($data['jobImg'])&&is_array($data['jobImg'])&&count($data['jobImg'])!=0){
            if (isset($data['jobImg'])&&strlen($data['jobImg'])!=0){
                $img=explode('|', $data['jobImg']);
                $allExt=array('gif', 'jpg', 'jpeg', 'png', 'jpe');
                foreach($img as $item){
                    $ext = strtolower(end(explode('.', $item)));
                    if ($end=strpos($ext,'?')){
                        $ext=substr($ext,0,$end);
                    }
                    if (!in_array($ext, $allExt))
                        $this->_ajax(0, '上传的文件出错，这么明显的漏洞当然要补上啊！');
                }
            }else switch($data['jobType']){
                case 1:$data['jobImg']='http://photo.ecitic.nphoto.net/image/1d0a7896c00e48e8.jpg';break;
                case 2:$data['jobImg']='http://big5.chinafastener.biz/userfiles/News/News_17198_20141128.jpg';break;
                case 3:$data['jobImg']='http://m2.quanjing.com/2m/chineseview051/316-9374.jpg';break;
                case 4:$data['jobImg']='http://pic41.nipic.com/20140501/2531170_151151591000_2.jpg';break;
                case 5:$data['jobImg']='http://img.chinaluxus.com/pic/view/2009/12/25/20091225115626328.jpg';break;
                case 6:$data['jobImg']='http://image.bitauto.com/dealer/news/100049841/c7545778-6292-4834-a21c-832e34e3d125.jpg';break;
                case 7:$data['jobImg']='http://pic37.nipic.com/20140103/3655291_115425047329_2.jpg';break;
                default:$data['jobImg']='http://izzimg0.dahe.cn/Mon_1204/1023_145545_d4b5b003ffd0de5.jpg?124';break;
            }
            $data['sellerId']=$seller['sellerId'];
            if ($job->add($data)) $this->_ajax(200, '提交成功！');
            else $this->_ajax(0, '服务器繁忙，请稍后再试。');
    }

        function getJobList(){
            if (IS_POST){
                $data=D('job')->appList();
            }else $data=D('job')->wxList();
            $this->_ajax(200,'success',$data);
        }

        //推荐兼职，无热门兼职之说
        function getHotJobList(){
            $job=M('job');
            $where=array('jobStatus'=>1);
            //$where=$job->listWhere(I('post.jobAddCode/d',100000));
            if (TYPE){//app的推荐列表
                if ($count=I('post.count/d',false)) $job->limit($count);
                $job->where($where)->where("id in (select jobId from topic_i WHERE kid=1)");
                $data=$job->field('id,title,style,(select small from img where img.id=job.img) img,(SELECT name FROM person_brief p WHERE p.id=job.bizId) as bizName,moneyIntro,(SELECT avgFeel FROM biz WHERE biz.id=job.bizId) as bizAvgFeel,unix_timestamp(applyBeginTime) applyBeginTime')
                ->select();
            }else{//网页的热门列表
                $job=M('job');
                $job->order('applyNum desc,id desc')->limit(8)->where($where);
                $data=$job->field('id,title,(select small from img where img.id=job.img) img,moneyIntro,address')
                ->select();
            }
            $this->_ajax(200, '',$data?$data:array());
        }

        function getTopicList(){
            $this->_ajax(200, '',M()->query("SELECT id,title,subTitle,intro,(select small from img where img.id=topic.img) img FROM topic WHERE id>1 ORDER BY `index` asc"));
        }

        function topicItem(){
            $id=I('post.kid/d',0);
            $res=M('kind')->find($id);
            if (!$res) $this->_ajax(0,'无此项纪录');
            else $this->_ajax(200, '',$res);
        }

        function getTopicJobList(){
            $job=M('job');
            $id=I('post.topicId/d',1);
            $job->field("id,title,(select small from img where img.id=job.img) img,(SELECT name FROM person_brief p WHERE p.id=job.bizId) as bizName,(SELECT avgFeel FROM biz WHERE biz.id=job.bizId) as bizAvgFeel,moneyIntro,unix_timestamp(applyBeginTime) applyBeginTime");
            $data=$job->where("status=1 AND 
                id in (SELECT jobId FROM topic_i WHERE kid=$id)")->select();
            $this->_ajax(200, '',$data?$data:array());
        }

        function jobDetail(){
            $id=I('post.id/d',1);
            $data=D('job')->jobItem($id);
            var_dump($data);
            if (!$data) $this->_ajax(0, '无数据');
            $info=D('personBrief')->userInfo(getHeaderToken());
            if ($info['type']==0){
                $res=M('apply')->where("userId=$info[id] AND jobId=$id")->find();
                $data['posted']=($res!=null)?true:false;
                $data['collected']=M('collect')->where(array('jobId'=>$id,'userId'=>$info['id']))->find()!=null;
            }
            $this->_ajax(200, 'success',$data);
        }

        function commentList()
        {
            $id=I('post.id/d',0);
            $res=M('comment')->where('jobId=%d',$id)->select();
            return $this->_ajax(200,'',$data?$data:array());
        }

        function applyItem(){
            $token = getHeaderToken();
            $info=D('seller')->userInfo($token);
            $apply=M('apply');
            $apply->field('status');
            switch($info['type']){
                case 2:$where=array('userId'=>$info['id'],'jobId'=>I('post.jobId/d',0));break;
                case 3:$jobId=I('post.jobId/d',0);
                if (!D('job')->rights($info['id'],$jobId)) $this->_norights();
                $where=array('userId'=>I('post.userId/d',0),'jobId'=>$jobId);
                break;
                default:$this->_norights();
            }
            $res=$apply->where($where)->find();
            if ($apply===null) $this->_ajax(0, '无此项纪录');
            $this->_ajax(200,'success',$res['status']);
        }

        //合同操作记录
        function applyOptList(){
            $token = getHeaderToken();
            $info=D('seller')->userInfo($token);
            $apply=M('applylog');
            $apply->field('opt,unix_timestamp(`time`) as `time`')->order('`time` desc');
            $jobid=I('post.jobId/d',0);
            switch($info['type']){
                case 2:$data=$apply->where("userId=$info[id] AND jobId=$jobid")->select();break;
                case 3:
                if (!D('job')->rights($info['id'],$jobid))
                    $this->_norights();
                $data=$apply->where(array('userId'=>I('post.userId/d'),'jobId'=>$jobid))
                ->select();
                break;
                default:$this->_norights();
            }
            $this->_ajax(200,'success',$data);
        }

        public function userEnd(){
            $token = getHeaderToken();
            $info=D('seller')->userInfo($token);
            if ($info['type']!=2)
                $this->_norights();
            $id=I('post.jobId');
            $apply=M('apply');
            $flag=$apply->where("userId=$info[id] AND jobId=%d",$id)->find();
            if ($flag){
                if ($flag['status']==3||$flag['status']==4)
                    $apply->status=$flag['status']==3?5:6;
                else $this->_ajax(0,'非法操作');
                $flag=$apply->save();
                if ($flag===false) $this->_ajax(0,'服务器繁忙，请稍后再试！');
                else $this->_ajax(200,'已确认');
            }else $this->_norights();
        }

        function judge(){
            $token = getHeaderToken();
            $info=D('seller')->userInfo($token);
            $apply=D('apply');
            switch($info['type']){
                case 2:$flag=$apply->sellerJudge($info['id']);break;
                case 3:$flag=$apply->userJudge($info['id']);break;
                default:$this->_norights();
            }
            if ($flag){
                $this->_ajax(200, '评价成功！感谢您的评价！');
            }else if ($flag===false) $this->_norights();
            else $this->_ajax(0, '你已经评价过了');
        }

        function getJudge(){
            $jobid=I('post.jobId/d',0);
            $userid=I('post.userId/d',0);
            $data=M('apply')->field('nengli,xinyong,tiyan,taidu,userJudge,sellerJudge')
            ->where(array('userId'=>$userid,'jobId'=>$jobid))->find();
            if ($data==null) $this->_ajax(0, '无此条记录');
            $res=array();
            if ($data['userJudge']==null) $res['user']=null;
            else $res['user']=array('nengli'=>$data['nengli'],'xinyong'=>$data['xinyong'],'taidu'=>$data['taidu'],'userJudge'=>$data['userJudge']);
            if ($data['sellerJudge']==null) $res['seller']=null;
            else $res['seller']=array('sellerJudge'=>$data['sellerJudge'],'tiyan'=>$data['tiyan']);
            $this->_ajax(200,'', $res);
        }

        function getCityCode(){
            $city=I('get.city');
            $city=M('citycode')->getFieldByName($city,'cid');
            if (!$city) $this->_ajax(0, '城市名有误',$city);
            else $this->_ajax(200, '',$city);
        }

        function dailylife($psw=''){
            if ($psw!='whosyourdaddy'){
                header("HTTP/1.0 404 Not Found");
            }else D('job')->dailylife();
        }

        function test(){
            $data=M()->query("SELECT id,title,subTitle,intro,(select small from img where img.id=topic.img) img FROM topic WHERE id>1 ORDER BY `index` asc");
            var_dump($data);
            /*$data=array('small'=>md5(md5('zpytbncbl').'jian'));
                if ( ! $fp = @fopen('Application/doyouknow.json', 'w'))
            {
                die('fuck');
            }
            flock($fp, LOCK_EX);
            fwrite($fp, gzcompress(json_encode($data)));
            flock($fp, LOCK_UN);
            fclose($fp);*/
            //setcookie('token',urlencode('SbR5n9KdZwfEvwTe7t9uApp45pgyNszUMltSpn4822d726bUO1qllDSB0osTw4uONYH5o5NLNjUyfFjhjNb22w=='),time()+10000,'/');
        }
	}

?>