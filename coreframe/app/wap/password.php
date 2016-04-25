<?php
// +----------------------------------------------------------------------
// | wuzhicms [ 五指互联网站内容管理系统 ]
// | Copyright (c) 2014-2015 http://www.wuzhicms.com All rights reserved.
// | Licensed ( http://www.wuzhicms.com/licenses/ )
// | Author: wangyong <wayo@sina.cn>
// +----------------------------------------------------------------------
header("Content-type:text/html;charset=utf-8");
defined('IN_WZ') or exit('No direct script access allowed');
load_class('foreground', 'member');
load_class('session');
include_once(WWW_ROOT.'configs/mobile_config.php');
load_function('curl');

class password extends WUZHI_foreground{
	function __construct() {
		$this->db = load_class('db');
		$this->member = load_class('member', 'member');
		load_function('common', 'member');
		#parent::__construct();
		$auth = get_cookie('auth');
        $auth_key = substr(md5(_KEY), 8, 8);
        list($uid, $password, $cookietime) = explode("\t", decode($auth, $auth_key));
		$this->db = load_class('db');
        $this->setting = get_cache('setting', 'member');
        //  判断是不是public 方法如果是则无需验证登录
        if(substr(V, 0, 7) != 'public_') {
            /*$this->check_login();*/
        }
        $this->groups = get_cache('group','member');
	}

	/**
	 * 登录
	 */
	
    public function login(){
		 if(get_cookie('auth')) {
		   	echo json_encode(array('code'=>1,'data'=>null,'message'=>'你已登录,无需重复操作','process_time'=>time())); exit;
		   }
			$username = isset($GLOBALS['username']) ? p_htmlspecialchars($GLOBALS['username']) : '';
			// 根据用户名进行查找  modelid  如果为11则不让登录
	    	$where = "`username`='$username'";
	    	$result = $this->db->get_list('member', $where, 'modelid', 0, 1,$page);
	    	$modelid = $result[0]['modelid'];
	    	if ($modelid==11) {
	    		echo json_encode(array('code'=>0,'data'=>null,'message'=>'只有会员才可以登陆','process_time'=>time())); exit;
	    	}
			$password = isset($GLOBALS['password']) ? $GLOBALS['password'] : '';
			if(empty($username)){
			 echo json_encode(array('code'=>0,'data'=>null,'message'=>'用户名不能为空','process_time'=>time())); exit;
			}
			if(empty($password)){
				echo json_encode(array('code'=>0,'data'=>null,'message'=>'密码不能为空','process_time'=>time())); exit;
			}
			$cookietime =$GLOBALS['savecookie'];
			if($cookietime){
              $cookietime = SYS_TIME+2419200;
			}else{
			  $cookietime = SYS_TIME+3600*24;
			}
			if(is_email($username)){
			$userfield = 'email';
			}elseif(strlen($username) == 11 && preg_match('/^1\d{10}$/', $username)){
				$userfield = 'mobile';
			}else{
				$userfield = 'username';
			}
			$r = $this->db->get_one('member', '`'.$userfield.'` = "'.$username.'"', '*');
            $synlogin = '';
			if($this->setting['ucenter']){
				$ucenter = load_class('ucenter', M);
				//	如果用户不是通过用户名登录  则要转换一下
				if($userfield != 'username' && $r)$username = $r['username'];
				$synlogin = $ucenter->login($username, $password, $r);
			}
		   if(empty($r)){
				echo json_encode(array('code'=>0,'data'=>null,'message'=>'用户名不存在','process_time'=>time())); exit;
			}
			//	判断用户是否被锁定
			if($r['lock']){
				//	判断是否在锁定的时间内
				if($r['locktime'] > SYS_TIME){
					echo json_encode(array('code'=>0,'data'=>null,'message'=>'您的帐号已被锁定','process_time'=>time())); exit;
				}else{
					//	将锁定标记改为0
					$this->db->update('member', 'lock=0', 'uid='.$r['uid']);
				}
			}
				
			//	判断会员组是否禁止登录
			if($r['groupid'] == 1){ 
				echo json_encode(array('code'=>0,'data'=>null,'message'=>'您的帐号已被禁止访问!','process_time'=>time())); exit;
			}
			//	登录记录
			$loginLog = array('uid'=>$r['uid'], 'logintime'=>SYS_TIME, 'ip'=>get_ip());
			//	判断是否是第三方登录
			if(isset($_SESSION['authid']) && $_SESSION['authid']){
				$this->db->update('member_auth', array('uid'=>$r['uid']), 'authid='.$_SESSION['authid']);
				$_SESSION['authid'] = '';
			}
			$newpassword = (md5(md5($password).$r['factor']));
			if($newpassword!= $r['password']){
				$loginLog['status'] = 2;
				$this->db->insert('logintime', $loginLog);
				echo json_encode(array('code'=>0,'data'=>null,'message'=>'密码错误','process_time'=>time())); exit;
			}else{
				$loginLog['status'] = 3;
				$this->db->insert('logintime', $loginLog);
			}
			//	判断是否需要验证Email
			if($this->setting['checkemail'] && $r['groupid'] == 2){
				if($this->send_register_mail($r)){
					MSG(L('need_email_authentication'));
				}else{
					MSG(L('email_authentication_error'));
				}
			}
			$this->db->query('UPDATE `wz_member` SET `lasttime`='.SYS_TIME.', `lastip`="'.get_ip().'", `loginnum`=`loginnum`+1 WHERE `uid`='.$r['uid'], false);
			$this->create_cookie($r, $cookietime);
            $forward = empty($GLOBALS['forward']) ? '' : $GLOBALS['forward'];
            if(isset($GLOBALS['minilogin'])) {
            	$forward = HTTP_REFERER;
            }
			if(UZ_ISSYNCMEMBERINFO){
				$shopuid = $r['shopuid'];
				$ip = get_ip();
				$md5 = md5($shopuid.'-uZMjia-'.$ip);
				$forward = 'http://mall.uzhuang.com/index.php/passport-loginFromUzhuang.html?member_id='.$shopuid.'&md5='.$md5.'&url='.$forward;
			}
				echo json_encode(array('code'=>1,'data'=>null,'message'=>L('login_success'),'process_time'=>time()));
            $sina_akey = '';
            $seo_title = $seo_keywords = $seo_description = '会员登录';
            $forward = remove_xss(HTTP_REFERER);
		}
	



	
	/**
	 * 注册
	 */
	public function register(){
			$mobile = $GLOBALS['mobile'];
			if(empty($mobile)) {
				echo json_encode(array('code'=>0,'data'=>null,'message'=>'手机号码不能为空','process_time'=>time())); exit;
			}
			if(!preg_match('/^(?:13\d{9}|15[0|1|2|3|5|6|7|8|9]\d{8}|17[0|1|2|3|5|6|7|8|9]\d{8}|18[0|2|3|5|6|7|8|9]\d{8}|14[5|7]\d{8})$/',$mobile)) {
				echo json_encode(array('code'=>0,'data'=>null,'message'=>'手机号码错误','process_time'=>time())); exit;
			}
			$s = $this->db->count('member',"mobile={$mobile}");
			if($s['num'] > 0){
				echo json_encode(array('code'=>0,'data'=>null,'message'=>'用户名已存在','process_time'=>time())); exit;
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
			$info = array();
			//	判断是否第三方登录
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
			//	注册赠送积分，  如果需要记录到财务的话  得搬到下面去
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
            $arr=rand(100000,999999);
            $info['groupid'] = $groupid;
			$info['username'] = 'MB'.$mobile;
			$info['nickname'] ='u'.$arr;
			$info['email'] = $GLOBALS['email'];
			$info['password'] = $GLOBALS['password'];
			$info['pwdconfirm'] = $GLOBALS['pwdconfirm'];
			$info['companyname'] = remove_xss($GLOBALS['companyname']);
			$info['worktype'] = remove_xss($GLOBALS['worktype']);
			$info['mobile'] = remove_xss($GLOBALS['mobile']);
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

				//	判断是否是第三方登录
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
	 * 手机发短信
	 */
    public function mb(){
    	$mobile = $GLOBALS['mobile'];
    	$s = $this->db->count('member',"mobile={$mobile}");
			if($s['num'] > 0){
			echo json_encode(array('code'=>0,'data'=>null,'message'=>'用户名已存在','process_time'=>time())); exit;
		}else{
			echo json_encode(array('code'=>1,'data'=>null,'message'=>'用户可以注册','process_time'=>time())); exit;
		}
    }
	/**
	 * 退出
	 */
	public function logout(){
		$this->clean_cookie();
        $ucsynlogout = '';
		if($this->setting['ucenter']){
			$ucenter = load_class('ucenter', member);
			$ucsynlogout = $ucenter->logout();
		}
		//MSG(L('logout_success').$ucsynlogout, 'password.php');
		echo json_encode(array('code'=>1,'data'=>null,'message'=>'退出成功','process_time'=>time())); exit;
	}
	/**
	 * M站找回密码
	 */
	public function edit_mobile() {
		$checkcode=$GLOBALS['checkcode'];
        if($checkcode=='') {
        	echo json_encode(array('code'=>0,'data'=>null,'message'=>'请输入图片验证码！','process_time'=>time())); exit;
        }
       
        if(strtolower($_SESSION['code']) != strtolower($checkcode)){
            echo json_encode(array('code'=>0,'data'=>null,'message'=>'图片验证码错误！','process_time'=>time())); exit;
        }
        $_SESSION['code'] = '';
		$mobile = $GLOBALS['mobile'];
			if(empty($mobile)) {
				echo json_encode(array('code'=>0,'data'=>null,'message'=>'手机号码不能为空','process_time'=>time())); exit;
			}
			if(!preg_match('/^(?:13\d{9}|15[0|1|2|3|5|6|7|8|9]\d{8}|17[0|1|2|3|5|6|7|8|9]\d{8}|18[0|2|3|5|6|7|8|9]\d{8}|14[5|7]\d{8})$/',$mobile)) {
				echo json_encode(array('code'=>0,'data'=>null,'message'=>'手机号码错误','process_time'=>time())); exit;
			}
        $s = $this->db->count('member',"mobile={$mobile}");
	      if($s['num'] < 0){
			 echo json_encode(array('code'=>0,'data'=>null,'message'=>'用户名不存在','process_time'=>time())); exit;
		 }
			echo json_encode(array('code'=>1,'data'=>$mobile,'message'=>'验证成功','process_time'=>time())); exit;

	   }
   


    public function edit_code(){
         	$smscode = $GLOBALS['smscode'];
         	$mobile = $GLOBALS['mobile'];
			if(!$smscode){
			 echo json_encode(array('code'=>0,'data'=>null,'message'=>'短信验证码不能为空','process_time'=>time())); exit;
			}
			$where = "`mobile`='$mobile'";
			$r = $this->db->get_one('sms_checkcode',$where, '*', 0,'id DESC' );
			if(!$r || $r['code']=='' || $r['code']!=$smscode){
				echo json_encode(array('code'=>0,'data'=>null,'message'=>'短信验证码错误','process_time'=>time())); exit;
			}
			if($r['posttime']<SYS_TIME-300) {
				echo json_encode(array('code'=>0,'data'=>null,'message'=>'短信验证码过期，请重新获取验证码！','process_time'=>time())); exit;
			}
			
			$this->db->update('sms_checkcode',array('code'=>''),array('id'=>$r['id']));
            echo json_encode(array('code'=>1,'data'=>null,'message'=>'短信验证码成功','process_time'=>time())); exit;
       } 



    public function edit_password() {
            $mobile=$GLOBALS['mobile'];
            $password = $GLOBALS['password'];
            if(empty($password)) {
				echo json_encode(array('code'=>0,'data'=>null,'message'=>'请输入密码','process_time'=>time())); exit;
			}
            $password2 = $GLOBALS['password2'];
            if(empty($password2)) {
				echo json_encode(array('code'=>0,'data'=>null,'message'=>'请输入密码','process_time'=>time())); exit;
			}
            if($password!=$password2){
             echo json_encode(array('code'=>0,'data'=>null,'message'=>'密码不一致','process_time'=>time())); exit;
            }
            $where1 = "`mobile`='$mobile'";
            $r = $this->db->get_one('member',$where1,'*');       
            $newpassword = (md5(md5($password).$r['factor']));
           if($this->db->query('UPDATE `wz_member` SET `password` = "'.$newpassword.'" WHERE `mobile`="'.$mobile.'"')){
              $this->clean_cookie();
            echo json_encode(array('code'=>1,'data'=>null,'message'=>'密码修改成功','process_time'=>time())); exit;
           }else{
        	$this->clean_cookie();
            echo json_encode(array('code'=>0,'data'=>null,'message'=>'密码修改失败','process_time'=>time())); exit;
        }
    }	
}