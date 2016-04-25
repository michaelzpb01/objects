<?php
// +----------------------------------------------------------------------
// | wuzhicms [ 五指互联网站内容管理系统 ]
// | Copyright (c) 2014-2015 http://www.wuzhicms.com All rights reserved.
// | Licensed ( http://www.wuzhicms.com/licenses/ )
// | Author: wangcanjia <phpip@qq.com>
// +----------------------------------------------------------------------
/**
 * 收藏夹
 */
header("Access-Control-Allow-Origin: *");
defined('IN_WZ') or exit('No direct script access allowed');
load_class('foreground', 'member');
class favorite extends WUZHI_foreground {
 	function __construct() {
        $this->member = load_class('member', 'member');
        load_function('common', 'member');
        $this->member_setting = get_cache('setting', 'member');
        parent::__construct();
    }
   



public function register(){
        echo "string";exit;
            $mobile = $GLOBALS['mobile'];
            if(empty($mobile)) {
                echo json_encode(array('code'=>0,'data'=>null,'message'=>'手机号码不能为空','process_time'=>time())); exit;
            }
            if(!preg_match('/^(?:13\d{9}|15[0|1|2|3|5|6|7|8|9]\d{8}|17[0|1|2|3|5|6|7|8|9]\d{8}|18[0|2|3|5|6|7|8|9]\d{8}|14[5|7]\d{8})$/',$mobile)) {
                echo json_encode(array('code'=>0,'data'=>null,'message'=>'手机号码错误','process_time'=>time())); exit;
            }
            $smscode = $GLOBALS['smscode'];
            
            if(!$smscode){
             echo json_encode(array('code'=>0,'data'=>null,'message'=>'短信验证码错误','process_time'=>time())); exit;
            }
            $where = "`mobile`='$mobile'";
            $r = $this->db->get_one('sms_checkcode',$where, '*', 0,'id DESC' );
            if(!$r || $r['code']=='' || $r['code']!=$smscode){
                echo json_encode(array('code'=>0,'data'=>null,'message'=>'短信验证码错误','process_time'=>time())); exit;
            }
            if($r['posttime']<SYS_TIME-300) {
                echo json_encode(array('code'=>0,'data'=>null,'message'=>'短信验证码过期，请重新注册！','process_time'=>time())); exit;
            }
            
            $this->db->update('sms_checkcode',array('code'=>''),array('id'=>$r['id']));
            //checkcode($GLOBALS['checkcode']);
            $info = array();
            //  判断是否第三方登录
            if(isset($_SESSION['authid']) && $_SESSION['authid']){
                load_function('preg_check');
                $GLOBALS['password'] = $GLOBALS['pwdconfirm'] = random_string('diy', 6);
            }else{
                if($this->setting['invite']){
                    if(empty($GLOBALS['invite']))MSG(L('invite_empty'));
                    $info['invite'] = $GLOBALS['invite'];
                }
                if($this->setting['checkmobile']){
                    if(empty($GLOBALS['mobile']))MSG(L('mobile_empty'));
                    $info['mobile'] = $GLOBALS['mobile'];
                }
            }
            if(!isset($GLOBALS['email'])) $GLOBALS['email'] = '';
            //  注册赠送积分，  如果需要记录到财务的话  得搬到下面去
            $info['points'] = (int)$this->setting['points'];
            $info['modelid'] = intval($GLOBALS['modelid']);

            if($this->setting['checkemail']) {
                $groupid = 2;// 邮件验证
            }elseif($info['modelid']==23) {
                $groupid = 5;// 机构
            } elseif($info['modelid']==11) {
                $groupid = 4;//企业
            } else {
                $groupid = 3;
            }
            $info['groupid'] = $groupid;
            $info['username'] = $GLOBALS['mobile'];
            $info['email'] = $GLOBALS['email'];
            $info['password'] = $GLOBALS['password'];
            $info['pwdconfirm'] = $GLOBALS['pwdconfirm'];
            $info['companyname'] = remove_xss($GLOBALS['companyname']);
            $info['worktype'] = remove_xss($GLOBALS['worktype']);
            $info['mobile'] = remove_xss($GLOBALS['mobile']);
            //$info['member']['mobile_station']= '移动站';

            $uid = $this->member->addmobile($info);
            if($uid){
                //同步注册商城
                if(UZ_ISSYNCMEMBERINFO){
                    $aSyncData = array(
                        'username' => $info['username'],
                        'password' => $info['password'],
                        'mobile' => $info['mobile'],
                        'ip' => get_ip(),

                    );
                    $aSyncData['md5'] = md5($info['username'].'-uZMjia-'.$aSyncData['ip']);
                    $rs = post_curl('mall.uzhuang.com/uzMainsite-syncFromMainsite.html' , $aSyncData);
                    $rs = json_decode($rs , 1);
                    $member_id = $rs['data']['member_id'];
                    $this->db->update('member', array('shopuid'=>$member_id), 'uid='.$uid);
                }

                //  判断是否是第三方登录
                if(isset($_SESSION['authid']) && $_SESSION['authid']){
                    $this->db->update('member_auth', array('uid'=>$uid), 'authid='.$_SESSION['authid']);
                    $_SESSION['authid'] = '';
                }else {
                    //设置登录
                    $r = $this->db->get_one('member', array('uid' => $uid));
                    $this->create_cookie($r, SYS_TIME+604800);
                    $url = '?m=member';
                    if(UZ_ISSYNCMEMBERINFO){
                        $shopuid = $r['shopuid'];
                        $ip = get_ip();
                        $md5 = md5($shopuid.'-uZMjia-'.$ip);
                        $url = urldecode('http://www.uzhuang.com/?m=member');
                        $url = 'http://mall.uzhuang.com/index.php/passport-loginFromUzhuang.html?member_id='.$shopuid.'&md5='.$md5.'&url='.$url;
                    }
                    echo json_encode(array('code'=>1,'data'=>$url,'message'=>'注册成功','process_time'=>time()));exit;
                }
            }
            $setting = $this->setting;
            $seo_title = '会员注册';
            $categorys = get_cache('category','content');
    }


    /**
     * 收藏的套餐
     */
	public function tuan() {
        $page = isset($GLOBALS['page']) ? intval($GLOBALS['page']) : 1;
        $page = max($page,1);
        $uid = $this->memberinfo['uid'];
        $publisher = $this->memberinfo['username'];
        $result_rs = $this->db->get_list('favorite', "`uid`='$uid' AND `type`=1", '*', 0, 20,$page,'fid DESC');
        $result = array();
        foreach($result_rs as $r) {
            $tr=$this->db->get_one('tuangou',array('id'=>$r['keyid']));
            $r['price'] =  $tr['price'];
            $r['price_old'] =  $tr['price_old'];
            $result[] = $r;
        }
        $pages = $this->db->pages;
        $total = $this->db->number;
        include T('member','favorite_tuan');
	}

    /**
     * 收藏的机构
     */
    public function mec() {
        $page = isset($GLOBALS['page']) ? intval($GLOBALS['page']) : 1;
        $page = max($page,1);
        $uid = $this->memberinfo['uid'];
        $publisher = $this->memberinfo['username'];
        $result_rs = $this->db->get_list('favorite', "`uid`='$uid' AND `type`=2", '*', 0, 20,$page,'fid DESC');
        $result = array();
        foreach($result_rs as $r) {
            $mecr=$this->db->get_one('mec',array('id'=>$r['keyid']));
            $tuan_list=$this->db->get_list('tuangou',array('link_mec'=>$r['keyid']), '*', 0, 3,0,'id DESC');
            $r['thumb'] =  $mecr['thumb'];
            $r['address'] =  $mecr['address'];
            $r['tuan_list'] =  $tuan_list;
            $result[] = $r;
        }


        $pages = $this->db->pages;
        $total = $this->db->number;
        include T('member','favorite_mec');
    }
    public function photo_add() {
        $uid = $this->memberinfo['uid'];
        $id = intval($GLOBALS['id']);
        if(!$id) exit('0');
        $r = $this->db->get_one('favorite', array('uid' => $uid,'type'=>5,'keyid'=>$id));
        if(!$r) {
            $mec_r = $this->db->get_one('picture_index', array('picid'=>$id),'title,collection');
            if(!$mec_r) exit('-1');
            $formdata = array();
            $formdata['type'] = 5;
            $formdata['title'] = $mec_r['title'];
            $formdata['url'] = '/photo/s'.$id.'.html';
            $formdata['addtime'] = SYS_TIME;
            $formdata['uid'] = $uid;
            $formdata['keyid'] = $id;
            $this->db->insert('favorite', $formdata);
            $this->db->update('picture_index', array('collection'=>$mec_r['collection']+1), array('picid' => $id));
            exit('1');
        } else {
            $this->db->delete('favorite', array('fid'=>$r['fid']));
            $mec_r = $this->db->get_one('picture_index', array('picid'=>$id),'title,collection');
            if(!$mec_r) exit('-1');
            $this->db->update('picture_index', array('collection'=>$mec_r['collection']-1), array('picid' => $id));
            exit('0');
        }

    }
    public function photos_add() {
        //$uid = get_cookie('uid');
        //if($uid){
        $id = intval($GLOBALS['id']);
        if(!$id) exit('0');
        $r = $this->db->get_one('favorite', array('uid' => $uid,'type'=>6,'keyid'=>$id));
        if(!$r) {
            $mec_r = $this->db->get_one('m_picture', array('id'=>$id),'name,url,collectnum');
            if(!$mec_r) exit('-1');
            $formdata = array();
            $formdata['type'] = 6;
            $formdata['url'] = $mec_r['url'];
            $formdata['addtime'] = SYS_TIME;
            $formdata['uid'] = $uid;
            $formdata['keyid'] = $id;
       
            $this->db->insert('favorite', $formdata);
            $this->db->update('m_picture', array('collectnum'=>$mec_r['collectnum']+1,'collectstatus'=>1), array('id' => $id));
            $result = $this->db->get_one('m_picture',array('id' => $id),'collectnum');
            echo json_encode(array('code'=>1,'data'=>$result,'message'=>"收藏成功",'process_time'=>time()));exit;
            //exit('1');
        } else {
            $mec_r = $this->db->get_one('m_picture', array('id'=>$id),'title,url,collectnum');
            if(!$mec_r) exit('-1');
            $this->db->delete('favorite', array('fid'=>$r['fid']));
            $this->db->update('m_picture', array('collectnum'=>$mec_r['collectnum']-1,'collectstatus'=>0), array('id' => $id));
            $result = $this->db->get_one('m_picture',array('id' => $id),'collectnum');
            echo json_encode(array('code'=>0,'data'=>$result,'message'=>"取消收藏成功",'process_time'=>time()));exit;
            //exit('0');
          }
        /*}else{
          echo json_encode(array('code'=>0,'data'=>$arr,'message'=>L('login_please'),'process_time'=>time())); exit;
        }*/
     }
  
     public function photos_add1() {
        $uid = $this->memberinfo['uid'];
        $id = intval($GLOBALS['id']);
        if(!$id) exit('0');
        $r = $this->db->get_one('favorite', array('uid' => $uid,'type'=>7,'keyid'=>$id));
        if(!$r) {
            $mec_r = $this->db->get_one('company_team', array('id'=>$id),'title,url,collection');
            if(!$mec_r) exit('-1');
            $formdata = array();
            $formdata['type'] = 7;
            $formdata['title'] = $mec_r['title'];
            $formdata['url'] = $mec_r['url'];
            $formdata['addtime'] = SYS_TIME;
            $formdata['uid'] = $uid;
            $formdata['keyid'] = $id;
            $this->db->insert('favorite', $formdata);
            $this->db->update('company_team', array('collection'=>$mec_r['collection']+1), array('id' => $id));
            exit('1');
        } else {
            $mec_r = $this->db->get_one('company_team', array('id'=>$id),'title,url,collection');
            if(!$mec_r) exit('-1');
            $this->db->delete('favorite', array('fid'=>$r['fid']));
            $this->db->update('company_team', array('collection'=>$mec_r['collection']-1), array('id' => $id));
            exit('0');
        }

    }

     public function photos_add2() {
        $uid = $this->memberinfo['uid'];
        $id = intval($GLOBALS['id']);
        if(!$id) exit('0');
        $r = $this->db->get_one('favorite', array('uid' => $uid,'type'=>8,'keyid'=>$id));
        if(!$r) {
            $mec_r = $this->db->get_one('company', array('id'=>$id),'title,url,collection');
            if(!$mec_r) exit('-1');
            $formdata = array();
            $formdata['type'] = 8;
            $formdata['title'] = $mec_r['title'];
            $formdata['url'] = $mec_r['url'];
            $formdata['addtime'] = SYS_TIME;
            $formdata['uid'] = $uid;
            $formdata['keyid'] = $id;
            $this->db->insert('favorite', $formdata);
            $this->db->update('company', array('collection'=>$mec_r['collection']+1), array('id' => $id));
            exit('1');
        } else {
            $mec_r = $this->db->get_one('company', array('id'=>$id),'title,url,collection');
            if(!$mec_r) exit('-1');
            $this->db->delete('favorite', array('fid'=>$r['fid']));
            $this->db->update('company', array('collection'=>$mec_r['collection']-1), array('id' => $id));
            exit('0');
        }

    }
   public function photos_add3() {
        $uid = $this->memberinfo['uid'];
        $id = intval($GLOBALS['id']);

        if(!$id) exit('0');
        $r = $this->db->get_one('favorite', array('uid' => $uid,'type'=>9,'keyid'=>$id));

        if(!$r) {
            $mec_r = $this->db->get_one('day_log_demand', array('id'=>$id),'title,url,collection'); 
            if(!$mec_r) exit('-1');
            $formdata = array();
            $formdata['type'] = 9;
             $formdata['title'] = $mec_r['title'];
             $formdata['url'] = $mec_r['url'];
            $formdata['addtime'] = SYS_TIME;
            $formdata['uid'] = $uid;
            $formdata['keyid'] = $id;
            $this->db->insert('favorite', $formdata);
            $this->db->update('day_log_demand', array('collection'=>$mec_r['collection']+1), array('id' => $id));
            exit('1');
        } else {
            $mec_r = $this->db->get_one('day_log_demand', array('id'=>$id),'title,url,collection');
            if(!$mec_r) exit('-1');
            $this->db->delete('favorite', array('fid'=>$r['fid']));
            $this->db->update('day_log_demand', array('collection'=>$mec_r['collection']-1), array('id' => $id));
            exit('0');
        }

    }
    public function delete() {
        $fid = intval($GLOBALS['fid']);
        $uid = $this->memberinfo['uid'];
        $this->db->delete('favorite',array('fid'=>$fid,'uid'=>$uid));
        MSG('删除成功',HTTP_REFERER);
    }
     public function delete_stylist() {
        $fid = intval($GLOBALS['fid']);
        $uid = $this->memberinfo['uid'];
        $this->db->delete('favorite',array('fid'=>$fid,'uid'=>$uid));
        MSG('删除成功',HTTP_REFERER);
    }
      public function delete_shop() {
        $fid = intval($GLOBALS['fid']);
        $uid = $this->memberinfo['uid'];
        $this->db->delete('favorite',array('fid'=>$fid,'uid'=>$uid));
        MSG('删除成功',HTTP_REFERER);
    }

      /**
     * 批量删除装修案例
     */
    public function make_empty() {
        
       
          if(isset($GLOBALS['fid']) && $GLOBALS['fid']) {
            if(is_array($GLOBALS['fid'])){
        
                $GLOBALS['fid'] = array_map('intval', $GLOBALS['fid']);
                //var_dump($GLOBALS['fid']);die;
                $where = ' IN ('.implode(',', $GLOBALS['fid']).')';
            }else{
                $where = ' = '.intval($GLOBALS['fid']);
            }
            $user = $this->db->get_list('favorite', 'fid'.$where, 'fid');
                    if($user)foreach ($user as $v){
                        //echo $v['fid'];
               $this->db->delete('favorite', 'fid='.$v['fid']);

        }
    }   
        if(is_array($GLOBALS['fid'])){
              MSG('已删除',HTTP_REFERER);
        } else{
              MSG('请选择要删除的数据',HTTP_REFERER);
        }
    }

    

    /**
     * 批量删除设计师
     */
    public function make_emptydes() {
        
         
          if(isset($GLOBALS['fid']) && $GLOBALS['fid']) {
            if(is_array($GLOBALS['fid'])){
        
                $GLOBALS['fid'] = array_map('intval', $GLOBALS['fid']);
                //var_dump($GLOBALS['fid']);die;
                $where = ' IN ('.implode(',', $GLOBALS['fid']).')';
            }else{
                $where = ' = '.intval($GLOBALS['fid']);
            }
            $user = $this->db->get_list('favorite', 'fid'.$where, 'fid');
                    if($user)foreach ($user as $v){
                        //echo $v['fid'];
                
               $this->db->delete('favorite', 'fid='.$v['fid']);

        }
    }
       if(is_array($GLOBALS['fid'])){
              MSG('已删除',HTTP_REFERER);
        } else{
              MSG('请选择要删除的数据',HTTP_REFERER);
        }
    }



     /**
     * 批量删除装修公司
     */
    public function make_emptycomp() {
        
         
          if(isset($GLOBALS['fid']) && $GLOBALS['fid']) {
            if(is_array($GLOBALS['fid'])){
        
                $GLOBALS['fid'] = array_map('intval', $GLOBALS['fid']);
                //var_dump($GLOBALS['fid']);die;
                $where = ' IN ('.implode(',', $GLOBALS['fid']).')';
            }else{
                $where = ' = '.intval($GLOBALS['fid']);
            }
            $user = $this->db->get_list('favorite', 'fid'.$where, 'fid');
                    if($user)foreach ($user as $v){
                        //echo $v['fid'];
                
               $this->db->delete('favorite', 'fid='.$v['fid']);

        }
    }
       if(is_array($GLOBALS['fid'])){
              MSG('已删除',HTTP_REFERER);
        } else{
              MSG('请选择要删除的数据',HTTP_REFERER);
        }
    }

     /**
     * 批量删除管家日志
     */
    public function make_emptylog() {
        
         
          if(isset($GLOBALS['fid']) && $GLOBALS['fid']) {
            if(is_array($GLOBALS['fid'])){
        
                $GLOBALS['fid'] = array_map('intval', $GLOBALS['fid']);
                //var_dump($GLOBALS['fid']);die;
                $where = ' IN ('.implode(',', $GLOBALS['fid']).')';
            }else{
                $where = ' = '.intval($GLOBALS['fid']);
            }
            $user = $this->db->get_list('favorite', 'fid'.$where, 'fid');
                    if($user)foreach ($user as $v){
                        //echo $v['fid'];
                
               $this->db->delete('favorite', 'fid='.$v['fid']);

        }
    }
       if(is_array($GLOBALS['fid'])){
              MSG('已删除',HTTP_REFERER);
        } else{
              MSG('请选择要删除的数据',HTTP_REFERER);
        }
    }

}