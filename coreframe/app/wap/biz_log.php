<?php
// +----------------------------------------------------------------------
// | wuzhicms [ 五指互联网站内容管理系统 ]
// | Copyright (c) 2014-2015 http://www.wuzhicms.com All rights reserved.
// | Licensed ( http://www.wuzhicms.com/licenses/ )
// | Author: wangcanjia <phpip@qq.com>
// +----------------------------------------------------------------------
header("content-type:text/html;charset=utf-8");
defined('IN_WZ') or exit('No direct script access allowed');
    /**
     * 首页
     */
    load_class('foreground', 'member');
    class biz_log extends WUZHI_foreground {
    function __construct() {
          $this->member = load_class('member', 'member');
            load_function('common', 'member');
            $this->member_setting = get_cache('setting', 'member');
            parent::__construct();
    }
    //公共包
    public function bag(){
        $day_log = get_config('log_config');
        $acquiesce=$GLOBALS['acquiesce'];
        $nodeid =$GLOBALS['nodeid'];
        $ends = $GLOBALS['ends'];
        $uid=get_cookie('_uid');
        $page = intval($GLOBALS['page']);
         if(!isset($page)||empty($page)){
                   $page=1;
         }
        $arr=array();
        if($acquiesce==0){
        $log_rss = $this->db->get_list('day_log_demand_list',$where, 'logname,userid,recphoto,addtime,nodename,orderid,nodeid,uid');
        $log_rs = $this->db->get_list('day_log_demand_list',$where, 'logname,userid,recphoto,addtime,nodename,uid,orderid,nodeid', 0, 4, $page, 'addtime DESC');
        $c=count($log_rss);
        $page_max=ceil($c/4);
        }
        //var_dump($arr['c1']);exit;
        if($acquiesce==1) {
        $log_rsss = $this->db->get_list('day_log_demand_list',$where, 'logname,userid,recphoto,addtime,nodename,orderid,nodeid,uid');
        $log_rs = $this->db->get_list('day_log_demand_list',$where, 'logname,userid,recphoto,addtime,nodename,orderid,nodeid,uid', 0, 4, $page, 'browse_count DESC');
        $c=count($log_rsss);
        $page_max=ceil($c/4);
        }
        if($nodeid){
        $where = "nodeid in (".$day_log[$nodeid]['nodeid'].")";
        $log_rssss = $this->db->get_list('day_log_demand_list',$where, 'logname,userid,recphoto,addtime,nodename,orderid,nodeid,uid');
        $log_rs = $this->db->get_list('day_log_demand_list',$where, 'logname,userid,recphoto,addtime,nodename,orderid,nodeid,uid', 0, 4, $page, 'addtime DESC');
        $c=count($log_rssss);
        $page_max=ceil($c/4);
        
        }
        if($ends==5){
          //echo "string";
        $where = 'orderstatus="完结"';
        $log_rsssss = $this->db->get_list('day_log_demand_list',$where, 'logname,userid,recphoto,addtime,nodename,orderid,nodeid,uid');
        //var_dump($log_rs);exit;
        $log_rs = $this->db->get_list('day_log_demand_list',$where, 'logname,userid,recphoto,addtime,nodename,orderid,nodeid,uid', 0, 4, $page, 'addtime DESC');
        $c=count($log_rsssss);
        $page_max=ceil($c/4);
        
        }
        foreach ($log_rs as $key => $value) {
        $arr[$key]['result'] = unserialize($value['recphoto']);
        $arr[$key]['results']= unserialize($value['sitephoto']);
        $arr[$key]['logname']=$value['logname'];
        $arr[$key]['uids']=$value['uid'];
        $arr[$key]['nodename']=$value['nodename'];
        $arr[$key]['orderid']=$value['orderid'];
        $arr[$key]['addtime']=time_format1(strtotime($value['addtime']));

        //echo "<pre>";print_r(time_format1(strtotime($value['addtime'])));
        if(!preg_match("/[\x7f-\xff]/", $arr[$key]['addtime'])){
                    $arr[$key]['addtime']=date('Y-m-d',strtotime($value['addtime']));
        }
         $aaa=array_filter($arr[$key]['result']);
        $arr[$key]['photo']=array_slice($aaa,-1,1);
         $bbb=array_filter($arr[$key]['results']);
        $arr[$key]['photos']=array_slice($bbb,-1,1);
        if( $arr[$key]['photo']){
        if (is_array($arr[$key]['result'][0])) {
            $keys = array_keys($arr[$key]['result'][0]);
            //$arr[$key]['recphoto']=getMImgShow($arr[$key]['result'][0][$keys[0]][0],'pic_230');
            $arr[$key]['recphoto']='http://www.uzhuang.com/image/pic_230/'.$arr[$key]['result'][0][$keys[0]][0];
            }else{
                //$arr[$key]['recphoto']=getMImgShow($arr[$key]['photos'],'pic_230');
            $arr[$key]['recphoto'] ='http://www.uzhuang.com/image/pic_230/'.$arr[$key]['photo'][0];
            } 
        }else{
            $arr[$key]['recphoto']=1;
        }
      if($arr[$key]['photos']){
        if(is_array($arr[$key]['results'][0])){
                      $keys = array_keys($arr[$key]['results'][0]);
                      $arr[$key]['sitephoto']='http://www.uzhuang.com/image/pic_230/'.$arr[$key]['results'][0][$keys[0]][0]; 
                      }else{
                      $arr[$key]['sitephoto']='http://www.uzhuang.com/image/pic_230/'.$arr[$key]['photos'][0];
                      }
                   }
                }
        foreach ($log_rs as $key => $value) { 
        $log_r= $this->db->get_one('member_hk_data',array('uid'=>$value['userid']), 'uid,gjname,personalphoto,lifeword');
        $arr[$key]['uid']=$log_r['uid'];
        $arr[$key]['gjname']=$log_r['gjname'];
        $arr[$key]['personalphoto']=$log_r['personalphoto'];
        $arr[$key]['lifeword']=$log_r['lifeword'];
        }

        foreach ($arr as $pk => $pl) {
        
           $uid_log=$this->db->get_one('day_log_demand_list',"`orderid`= '".$pl['orderid']."'",'title,uid');
           //var_dump($uid_log['uid']);
            if($uid_log['uid']!=$uid || empty($uid_log['uid'])){
              $log_exist=(int)0;
            }else{
              $log_exist=(int)1;
            }
            $arr[$pk]['log_exist']=$log_exist;
           $arr[$pk]['personalphotos'] ='http://www.uzhuang.com/image/biz_230/'.$arr[$pk]['personalphoto'];                                      
        }//echo"<pre>";print_r($arr);exit;
        if(empty($arr)){
        echo json_encode(array('code'=>0,'data'=>null,'message'=>'数据错误','process_time'=>time())); exit;
        }else{
          return $arr_finally=array(
            'arr'=>$arr,
            'page_max'=>$page_max,
          );    
        }     
    }
    //工地直播列表页
    public function listl(){
        $acquiesce=$GLOBALS['acquiesce'];
        //"0"是默认排序"1"浏览量最多排序"2"装修前"3"装修中"4"装修后(环保)"5"完结
        $nodeid =$GLOBALS['nodeid'];
        $ends = $GLOBALS['ends'];
        //最新节点
        //最多人看
        if($acquiesce==1){
        $arr2=$this->bag();
          //echo"<pre>";print_r($arr21);
          echo json_encode(array('code'=>1,'data'=>$arr2,'message'=>'最多人看','process_time'=>time())); exit;
        }
        //大阶段筛选
        if($nodeid>=2 && $nodeid<=4){
        $arr3=$this->bag();
        echo json_encode(array('code'=>1,'data'=>$arr3,'message'=>'大阶段筛选','process_time'=>time())); exit;
       }
       if($ends==5){
        $arr6=$this->bag();
        //echo"<pre>";print_r($arr6);
        echo json_encode(array('code'=>1,'data'=>$arr6,'message'=>'完结','process_time'=>time())); exit;
       }
        if($acquiesce==0){
          //echo "string";
          //echo "string";
        $arr21=$this->bag();
        //echo"<pre>";print_r($arr21);
        echo json_encode(array('code'=>1,'data'=>$arr21,'message'=>'刚刚更新','process_time'=>time())); exit;
        }
        $totals = $this->db->number;
        $pages = $this->db->pages;
    }
    
    //工地直播详情页节点展示信息
    public function index(){
          $orderid=$GLOBALS['orderid'];
          $nodeid =$GLOBALS['nodeid'];            
            $arr2=array();                
            //存当前订单进行的所有节点
              $arr3=array();
              // 存普通节点
              $arr4=array();
              // 存特殊节点
              $arr5=array();
                if($nodeid){
                $where1='orderid="'.$orderid.'" AND isPublish="发布" AND nodeid="'.$nodeid.'"';
                }else{
                $where1='orderid="'.$orderid.'" AND isPublish="发布"';
                }
                $pictur = $this->db->get_list('day_log',$where1,"nodeid",0,100,$page,"id desc");
                      //echo"<pre>";print_r($arr2);
                
                foreach ($pictur as $key => $valu) {
                $arr3[]=$valu['nodeid'];
                }
                foreach ($arr3 as $key => $value) {
                      if($value<25 || $value>37 && ($value!=51 && $value!=43)){
                           $arr4[]=$value;
                      }
                      if($value>=25 && $value<37 || ($value==51 || $value==43)){
                          $arr5[]=$value;
                      }
                }
                if(isset($arr4)&&!empty($arr4)) {
                $arrpt=implode(',',$arr4);
                // 存现场照片
                $arrxc=array();
                // 存整改照片
                $arrzg=array();
                //普通节点
                $where="`orderid`='".$orderid."' and `nodeid` in (".$arrpt.") and `isPublish`='发布'";
                $picture = $this->db->get_list('day_log',$where,"nodeid,recphoto,sitephoto,content,addtime,nodename",0,100,$page,"id desc");
                foreach ($picture as $key => $value) {
                $arr2[$value['nodeid']]=$value;
                //正常显示图片
                $photoInfos = array_filter(unserialize($value['recphoto']));
                $photoInfo =array_slice($photoInfos,-9);
                $photoInfos2 = array_filter(unserialize($value['recphoto']));
                $photoInfo2 =array_slice($photoInfos2,-9);
                $arrxc = $arrzg= $arrx= $arrz = array();
                        foreach ((array)$photoInfo as $pk => $pv) {
                              $arrxc[$pk] = empty($pv)?null:'http://www.uzhuang.com/image/pic_230/'.$pv;
                              $arrzg[$pk] = null;
                        }
                        $arr2[$value['nodeid']]['recphoto']=$arrxc;
                        foreach ((array)$photoInfo2 as $pk2 => $pv2) {
                              $arrx[$pk2] = empty($pv2)?null:'http://www.uzhuang.com/image/pic_800/'.$pv2;
                              $arrzg[$pk2] = null;
                        }
                        $arr2[$value['nodeid']]['recphotos']=$arrx;
                        //点击放大图片
                        $photoInfo1 = unserialize($value['sitephoto']);
                        $photoInfo3 = unserialize($value['sitephoto']);
                        foreach ((array)$photoInfo1 as $pk => $pv) {
                              $arrzg[$pk] = empty($pv)?null:'http://www.uzhuang.com/image/pic_230/'.$pv;
                        }
                        $arr2[$value['nodeid']]['sitephoto']=$arrzg;
                        foreach ((array)$photoInfo3 as $pk1 => $pv1) {
                              $arrz[$pk1] = empty($pv1)?null:'http://www.uzhuang.com/image/pic_800/'.$pv1;
                        }
                        $arr2[$value['nodeid']]['sitephotos']=$arrz;
                        $date = $value['addtime'];
                        $arr2[$value['nodeid']]['addtime']= substr($date,0,10);
                        $arr2[$value['nodeid']]['type']=0;
                      } 
                //echo"<pre>";print_r($arr2);exit;
              }
              //特殊节点(材料验收)
              if(isset($arr5)&&!empty($arr5)){
                  $arrts=implode(',',$arr5);
                  $where31 = "orderid='".$orderid."' AND nodeid in (".$arrts.")";
                  $pictures=$this->db->get_list('m_day_log',$where31,'proName,brand,unpuaRea,accDate,overDate,accImg,overImg,nodeid,node,accQua,gb_pic,gb_cont,col,content',0,21,$page,'id asc');
                  $ar=array();
                  $ac=array();
                  $ab=array();
                  $nss=$this->db->get_list('m_day_log','orderid="'.$orderid.'"and nodeid in ('.$arrts.') and node="SOW"','proName,unpuaRea,accDate,accImg,nodeid,node,gb_pic,gb_cont',0,21,$page,'id DESC','node,nodeid');
                        $where24 = "orderid='".$orderid."' AND nodeid in (".$arrts.") AND isPublish='发布'";
                        $rest = $this->db->get_list('day_log',$where24,'nodename,remark,addtime,content,nodeid', 0, 21, $page,'nodeid DESC');
                        foreach ($rest as $key => $value) {
                        //$ar[$key]['col']=$value['col'];
                        $ar[$key]['nodename']=$value['nodename'];
                        $ar[$key]['nodeid']=$value['nodeid'];
                        $date = $value['addtime'];
                        $ar[$key]['addtimes']= substr($date,0,10);
                        }
                        foreach ($pictures as $key => $value) {
                        
                            foreach ($ar as $keyc => $valuec) {
                                      if($valuec['nodeid']==$value['nodeid']){
                                              $arr2[$value['nodeid']]['nodeinfo'][$key]=$valuec;
                          //var_dump($value['nodeid']);
                            }
                        }
                        //var_dump($value['nodeid']);
                        $arr2[$value['nodeid']]['type']=1;
                        $arr2[$value['nodeid']]['nodeinfo'][$key]['nodeid']= $value['nodeid'];
                        $arr2[$value['nodeid']]['nodeinfo'][$key]['col']= $value['col'];
                        $arr2[$value['nodeid']]['nodeinfo'][$key]['content']= $value['content'];       
                        $arr2[$value['nodeid']]['nodeinfo'][$key]['node']= $value['node'];   
                        $arr2[$value['nodeid']]['nodeinfo'][$key]['proName']= $value['proName'];
                        $arr2[$value['nodeid']]['nodeinfo'][$key]['brand']= $value['brand'];
                        $arr2[$value['nodeid']]['nodeinfo'][$key]['unpuaRea']=$value['unpuaRea'];
                        $arr2[$value['nodeid']]['nodeinfo'][$key]['accQua']=$value['accQua'];
                        if($value['accImg']){
                        $arr2[$value['nodeid']]['nodeinfo'][$key]['accImg']='http://www.uzhuang.com/image/pic_230/'.$value['accImg'];
                        $arr2[$value['nodeid']]['nodeinfo'][$key]['accImgs']='http://www.uzhuang.com/image/pic_800/'.$value['accImg'];
                        }else{
                        $arr2[$value['nodeid']]['nodeinfo'][$key]['accImg']=0;
                        $arr2[$value['nodeid']]['nodeinfo'][$key]['accImgs']=0;
                        }
                        $arr2[$value['nodeid']]['nodeinfo'][$key]['accDate']= substr($value['accDate'],0,10); 
                        $arr2[$value['nodeid']]['nodeinfo'][$key]['overDate']= substr($value['overDate'],0,10);
                        if($value['overImg']){                          
                        $arr2[$value['nodeid']]['nodeinfo'][$key]['overImg']='http://www.uzhuang.com/image/pic_230/'.$value['overImg'];
                        $arr2[$value['nodeid']]['nodeinfo'][$key]['overImgs']='http://www.uzhuang.com/image/pic_800/'.$value['overImg'];
                        }else{
                        $arr2[$value['nodeid']]['nodeinfo'][$key]['overImg']=0;
                        $arr2[$value['nodeid']]['nodeinfo'][$key]['overImgs']=0;    
                        }
                        $arr2[$value['nodeid']]['nodeinfo'][$key]['gb_pic']=getImgShow($value['gb_pic'],'pic_230');
                        $arr2[$value['nodeid']]['nodeinfo'][$key]['gb_cont']=$value['gb_cont'];
                        $arr2[$value['nodeid']]['nodeinfo'] = array_values($arr2[$value['nodeid']]['nodeinfo']);
                  }      
         
              }
               //exit;
               //特殊节点(竣工验收)
              if(in_array(37,$arr3)){
                  $where29 = "orderid='".$orderid."' AND nodeid='37'";
                  $picture1=$this->db->get_list('m_day_log',$where29,'accQua,proName,unpuaRea,accDate,overDate,accImg,overImg,nodeid,node,gb_pic,gb_cont',0,21,$page,'id asc');
                  $ac=array();
                  $ar=array();
                          $where24 = "orderid='".$orderid."' AND nodeid='37' AND isPublish='发布'";
                          $rest = $this->db->get_list('day_log',$where24,'remark,nodename,addtime,content', 0, 21, $page,'nodeid DESC');
                          $ac['nodename']=$rest[0]['nodename'];
                          $ac['type']=2;
                          $ac['content']=$rest[0]['content'];
                          $date = $rest[0]['addtime'];
                          $ac['addtimes']= substr($date,0,10);
                          foreach ($picture1 as $key => $value) {
                          $a[$key]['nodeid']= $value['nodeid'];
                          $a[$key]['node']= $value['node'];
                          $a[$key]['proName']= $value['proName'];
                          $a[$key]['accQua']= $value['accQua'];
                          $a[$key]['unpuaRea']=$value['unpuaRea'];
                          if($value['accImg']){
                          $a[$key]['accImg']= 'http://www.uzhuang.com/image/pic_230/'.$value['accImg'];
                          $a[$key]['accImgs']= 'http://www.uzhuang.com/image/pic_800/'.$value['accImg'];
                          }else{
                          $a[$key]['accImg']=0;
                          $a[$key]['accImgs']=0;
                          }
                          $a[$key]['accDate']= substr($value['accDate'],0,10);
                          $a[$key]['overDate']= substr($value['overDate'],0,10);
                            if($value['overImg']){
                            $a[$key]['overImg']='http://www.uzhuang.com/image/pic_230/'.$value['overImg'];
                            $a[$key]['overImgs']='http://www.uzhuang.com/image/pic_800/'.$value['overImg'];
                            }else{
                            $a[$key]['overImg']=0;
                            $a[$key]['overImgs']=0;  
                            }
                          $a[$key]['gb_pic']=getImgShow($value['gb_pic'],'pic_230');
                          $a[$key]['gb_cont']= $value['gb_cont'];
                          }
                          $ac['res']=$a;
                          $arr2[37]=$ac;                                               
                }
                 //echo"<pre>";print_r($arr2);exit;
                echo json_encode(array('code'=>1,'data'=>$arr2,'message'=>'一键展开接口','process_time'=>time())); exit;
    }
     //工地详情页头部公共包
    public function bags($orderid=''){
            $nodeid = 13;
            $arr1=array();
            $result= $this->db->get_one('demand',array('id'=>$orderid),'title,sex,homestyle,housetype,area,style,way,renovationcategory,uid');
                $n=$result['title'];
                $name=mb_substr($n, 0, 1,'utf-8');
                if($result['sex']=='男'){
                $arr1['name'] = $name.'先生';
                }else{
                $arr1['name'] = $name.'女士';   
                }
                $results=$this->db->get_one('member',array('uid'=>$arr1['result']['uid']),'avatar');
                $file = 'uploadfile/member/'.substr(md5($arr1['result']['uid']), 0, 2).'/'.$arr1['result']['uid'].'/180x180.jpg';
                if($results['avatar']==1){
                $arr1['avatar'] ='http://www.uzhuang.com/'.$file;
                }else{
                $arr1['avatar']= R.'images/userface.png';
                }
                $configs_picture = get_config('picture_config');
                //正常显示图片
                if($result['renovationcategory']==1){
                        $q=trim($result['homestyle'],',');
                        $w=explode(',',$q);
                        for($i=0;$i<count($w);$i++){
                          $homestyle[$i]=$configs_picture['homestyle'][$w[$i]];
                        }
                        $arr1['homestyle']=$homestyle;              
                        }else{
                        $qq=trim($result['housetype'],',');
                        $ww=explode(',',$qq);
                        for($v=0;$v<count($ww);$v++){
                          $housetype[$v]=$configs_picture['housetype'][$ww[$v]];
                        }
                        $arr1['homestyle']=$housetype;    
                        }

                        $configs_picture = get_config('picture_config');
                        $pp=trim($result['style'],',');
                        $ss=explode(',',$pp);
                        for($a=0;$a<count($ss);$a++){
                          $style[$a]=$configs_picture['style1'][$ss[$a]];
                        }    
                        $arr1['style']=$style;
                        $configs_picture = get_config('picture_config');
                        $a=trim($result['way'],',');
                        $b=explode(',',$a);
                        for($i=0;$i<count($b);$i++){
                         $way[$i]=$configs_picture['way'][$b[$i]];
                        }
                        $arr1['way']=$way;
                        $arr1['area']=$result['area'];
                $where = "orderid='".$orderid."'";
                $idss=$this->db->get_list('day_log_demand_list',$where,'logname',0,1,$page,'id DESC');
                     $arr1['logname']=$idss[0]['logname'];
                $ids =$this->db->get_list('day_log',$where,'userid,title',0,1,$page,'id DESC');
                $log_r= $this->db->get_one('member_hk_data',array('uid'=>$ids[0]['userid']), 'uid,gjname,personalphoto,mobile');
                $arr1['uid']=$log_r['uid'];
                $arr1['gjname']=$log_r['gjname'];
                $arr1['mobile']=$log_r['mobile'];
                //$arr[$pk]['personalphotos'] ='http://www.uzhuang.com/image/small_square/'.$arr[$pk]['personalphoto'];  
                $arr1['personalphotos']='http://www.uzhuang.com/image/biz_230/'.$log_r['personalphoto'];
                //$com=$this->db->get_one('demand_track_detai',array('orderid'=>$orderid,'nodeid'=>$nodeid,'col8'=>''),"col2,col1,col8",0,"id desc");
                  $com = $this->db->get_one('demand_track_detail',array('orderid'=>$orderid,'nodeid'=>$nodeid,'col8'=>'是'),"col2,col1",0,"id desc");
                //echo "<pre>";print_r($com);
                //var_dump($com);
                if(!$com){
                  $company=$this->db->get_one('demand_track_detail',"orderid='".$orderid."' and nodeid='".$nodeid."' and col8 is null" ,"col2,col1",0,"id desc");
                }else{
                  $company = $com;
                }
                //var_dump($com);
                $res=mb_strlen($company['col2']);
                //echo "<pre>";print_r($company);exit;
                if($res>36){
                $arr1['company']=mb_substr($company['col2'], 0, 12,'utf-8').'...';
                }else{
                $arr1['company']=$company['col2'];
                }
                //var_dump($s);exit;
                $company_photo=$this->db->get_one('company',array('id'=>$company['col1']),"thumb",0,"id desc");
                $arr1['company_photo']='http://www.uzhuang.com/uploadfile/'.substr($company_photo['thumb'],0,2).'/'.substr($company_photo['thumb'],2,2).'/'.$company_photo['thumb'];
                //http://image-'.$srv_n.'.uzhuang.com/uploadfile/'.substr($src,0,2).'/'.substr($src,2,2).'/'.$src
                return $arr1;
                $pages = $this->db->pages;
                $total = $this->db->number;
                $configs = get_config('picture_config');              
    }
    //工地直播详情页
    //第一个接口用户进工地直播详情页   输出的数据
    public function log(){
           $orderid = $GLOBALS['orderid'];
           $uid =get_cookie('_uid');     
           $where40 = "orderid='".$orderid."' AND isPublish='发布'";
           $maxNodeId = $this->db->get_list('day_log',$where40,'nodeid,content,nodename,addtime',0,1,$page,'nodeid desc');  
           $nodeid = $maxNodeId[0]['nodeid'];
           $design_rs = $this->db->get_one('day_log_demand_list', array('orderid' => $orderid),"browse_count");
           $browseCount = (int)$design_rs['browse_count']+1;
           // 跟新到数据库
           $this->db->update('day_log_demand_list',array('browse_count'=>$browseCount),array('orderid'=>$orderid));
           //调bags方法包         
           $arr=array(
                'node'=>$this->bags($orderid),
            );
           //工地直播  完结状态
           $where7= "`id`='".$orderid."' and orderstatus='完结'";
           $resul= $this->db->get_list('demand',$where7,'orderstatus');
           foreach ($resul as $key => $value) {
            $arr['orderstatus']=$value['orderstatus'];
           }
          //var_dump($retest);exit;
           //日志拆改是否显示
           $where4= "`orderid`='".$orderid."' and nodeid=23";
           $results = $this->db->get_list('demand_track',$where4,'col2');
           foreach ($results as $ke => $va) {
           $arr['col2']=$va['col2'];
            }
           //意向定金  状态
           $where5= "`orderid`='".$orderid."' and nodeid=15";
           $result = $this->db->get_list('demand_track',$where5,'col2');
           foreach ($result as $key => $val) {
           $arr['col3']=$val['col2'];
            }
           $collectstatus=$this->db->get_list('favorite',$where11,'keyid,collectstatus');
           $ar=array();
              foreach ($collectstatus as $key => $value) {
                $ar[]=$value['keyid'];
              }
              if(empty($uid)){
                $a = (empty($uid)) ? $arr['collectstatus']=0:$arr['collectstatus']=$collectstatus1['collectstatus'];
              }else{
              if(in_array($orderid,$ar)){
                $collectstatus1=$this->db->get_one('favorite',"`keyid`=$orderid and `uid`=$uid",'keyid,collectstatus');
              }
                $a = (empty($uid)) ? $arr['collectstatus']=0:$arr['collectstatus']=$collectstatus1['collectstatus'];
              }
              $arr['collectstatus']=$a;
              $picture = $this->db->get_one('day_log',array('orderid'=>$orderid,'nodeid'=>$nodeid,'isPublish'=>'发布'),"recphoto,remark",0,"nodeid desc");
              $photoInfo = unserialize($picture['recphoto']);
              foreach ($photoInfo as $key => $value) {
                  if ($value) {
                      $photo = basename($value);
                      $photoInfo[$key] ='http://www.uzhuang.com/image/pic_230/'.$photo;
                  }
              }
              $arr['content']=$maxNodeId[0]['content'];
              $arr['addtime']=$maxNodeId[0]['addtime'];
              $arr['nodename']=$maxNodeId[0]['nodename']; 
              $arr['maxNodeId']['recphoto'] = $photoInfo;
              $arr['maxNodeId']['sitephoto'] = $photoInfos;
              $where2 = "orderid='".$orderid."' AND isPublish='发布'";
              $res = $this->db->get_list('day_log',$where2,'nodeid,addtime', 0, 21, $page,'nodeid DESC');
              $resu=array();
              foreach ($res as $key => $value) {
              $resu[$key][]= $value['nodeid'];
              $a=substr($value['addtime'],0,10);
              $resu[$key][]= explode("-",$a);
              //explode("@",$email); 
              //substr($value['accDate'],0,10);
              }
              // echo"<pre>";print_r($resu);
              $arr['resu']=$resu;
              //$quali数组的第一个元素节点id,第二个元素合格的个数，第三个元素不合格的个数
              $quali=array();
              foreach ($arr['resu'] as $key => $value) {
              foreach ($value as $ke => $va) {
               $quali[$key][]=$va;
              }
            

            // 取合格的数据
            $qualified = $this->db->get_list('m_day_log',"`orderid`='".$orderid."' and `nodeid`='".$value."' and `accQua`='合格'",'orderid,nodeid,accQua');
            $qualified_c=count($qualified);
            // 所有合格的存到数组
            $quali[$key][]=$qualified_c;
            // 取不合格的数据
            $unqualified = $this->db->get_list('m_day_log',"`orderid`='".$orderid."' and `nodeid`='".$value."' and `accQua`='不合格'",'orderid,nodeid,accQua');
            $unqualified_c=count($unqualified);
            // 不合格的存到数组
            $quali[$key][]=$unqualified_c;
           }
            $arr['resu']=$quali;
            //echo"<pre>";print_r($quali);
           if($arr){
             //echo"<pre>";print_r($arr);
            echo json_encode(array('code'=>1,'data'=>$arr,'message'=>'刚进详情页的接口','process_time'=>time())); exit;
           }else{
            echo json_encode(array('code'=>0,'data'=>null,'message'=>'数据不存在','process_time'=>time())); exit;
           }
    }
    
}

