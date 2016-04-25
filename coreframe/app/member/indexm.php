<?php
// +----------------------------------------------------------------------
// | wuzhicms [ 五指互联网站内容管理系统 ]
// | Copyright (c) 2014-2015 http://www.wuzhicms.com All rights reserved.
// | Licensed ( http://www.wuzhicms.com/licenses/ )
// | Author: wangyong <wayo@sina.cn>
// +----------------------------------------------------------------------
header("Content-type:text/html;charset=utf-8");
defined('IN_WZ') or exit('No direct script access allowed');
load_class('foreground', M);
load_class('session');
include_once(WWW_ROOT.'configs/uzhuang_config.php');
load_function('curl');

class index extends WUZHI_foreground{
	function __construct() {
		$this->member = load_class('member', M);
		load_function('common', M);
		#parent::__construct();
		$this->db = load_class('db');
        $this->setting = get_cache('setting', 'member');
        //  判断是不是public 方法如果是则无需验证登录
        if(substr(V, 0, 7) != 'public_') {
             $this->check_login();
        }
        $this->groups = get_cache('group','member');
	}

	/**
	 * 判断是否是登录状态
	 */
	public function check_login(){
		parent::goMerchantLogin();
	}



	public function init(){
        $seo_title = '会员中心';
		$memberinfo = $this->memberinfo;
        $uid = $memberinfo['uid'];
        $groups = $this->groups;
        //自动升级会员组，3，6，7，8，9
        $points = $memberinfo['points'];

        if($points>$groups[9]['points']) {
            $memberinfo['groupid'] = 9;
        } elseif($points>$groups[8]['points']) {
            $memberinfo['groupid'] = 8;
        } elseif($points>$groups[7]['points']) {
            $memberinfo['groupid'] = 7;
        } elseif($points>$groups[6]['points']) {
            $memberinfo['groupid'] = 6;
        } else {//普通会员
            $memberinfo['groupid'] = 3;
        }
        $this->db->update('member', array('groupid'=>$memberinfo['groupid']), array('uid' => $uid));
        $GLOBALS['acbar'] = 1;
        //登录日志
        $log_results = $this->db->get_list('logintime', '`uid`='.$uid.' AND status > 1', '*', 0, 10, 0, 'id DESC');
        $ip_location = load_class('ip_location');
        foreach($log_results as $key=>$rs) {
            $log_results[$key]['ip_location'] = $ip_location->seek($rs['ip'],1);
        }
        //今日获取积分数
        $toay_pint = 0;
		$groupid = $memberinfo['groupid'];
		if($groupid==3) {
			$next_group = 6;
			$nextpoints = $groups[$next_group]['points']-$points;
		}elseif($groupid==6) {
			$next_group = 7;
			$nextpoints = $groups[$next_group]['points']-$points;
		} elseif($groupid==7) {
			$next_group = 8;
			$nextpoints = $groups[$next_group]['points']-$points;
		} elseif($groupid==8) {
			$next_group = 9;
			$nextpoints = $groups[$next_group]['points']-$points;
		} elseif($groupid==9) {
			$next_group = 9;
			$nextpoints = 0;
		} else {
			$next_group = $groupid;
			$nextpoints = 0;
		}
		$safe_level = 1;//低
		if($memberinfo['ischeck_mobile'] && $groupid>2) {
			$safe_level = 3;//高
		} elseif($memberinfo['ischeck_mobile'] || $groupid>2) {
			$safe_level = 2;//中
		}
		//待处理的订单
		$order_goods_count = $this->db->count_result('order_goods', "`uid`='$uid' AND `status`=2");
		$order_goods_comment = $this->db->count_result('order_subscribe', "`uid`='$uid' AND `status` IN(2)");
		$coupon_card_count = $this->db->count_result('coupon_card_active', "`uid`='$uid' AND `status`=0");
		$status = intval($GLOBALS['status']);
		$status_arr = array();
		$status_arr[1] = '已付款（待预约）';
		$status_arr[2] = '待付款';
		$status_arr[3] = '交易取消';
		$status_arr[5] = '已付款（已预约）';
        $status_arr[6] = '已发货';

		$orderid = intval($GLOBALS['orderid']);
		$page = isset($GLOBALS['page']) ? intval($GLOBALS['page']) : 1;
		$page = max($page,1);
        $result_r = $this->db->get_list('order_goods',array('uid'=>$uid), '*', 0, 2,0,'orderid DESC','order_no');
        foreach($result_r as $r) {
            $r['goodlist'] = $this->db->get_list('order_goods',array('order_no'=>$r['order_no']));
            $total_money = 0;
            foreach($r['goodlist'] as $rs) {
                $total_money = $total_money+sprintf("%.2f",$rs['money']*$rs['quantity']-$rs['coupon_card']);
            }
            $r['money'] = sprintf("%.2f",$total_money);
            $result[] = $r;
        }
		load_function('global','order');

		include T('member','index');
	}

	/*
		商户登录
	 */
    public function login_merchant(){
    	// 获取登录用户的用户名
    	$uname = $GLOBALS['username'];
    	// 根据用户名进行查找
    	$where = "`username`='$uname'";
    	$result = $this->db->get_list('member', $where, 'uid,settled_progress,modelid,is_email,send_email_time', 0, 1,$page);
    	if (!$result) {
	        $where = "`email`='$uname'";
	        $result = $this->db->get_list('member', $where, 'uid,settled_progress,modelid,is_email,send_email_time', 0, 1,$page);
	    }
    	if(empty($result))MSG(L('user_not_exist'));
    	$emailVlidate=$result[0]['is_email'];

    	$nowTime =date('YmdHis');
    	$regTime=$result[0]['send_email_time'];
    	$cTime=$nowTime-$regTime;
    	// 得到用户ID  和商户完善信息的情况
    	$userId = $result[0]['uid'];
    	$process = $result[0]['settled_progress'];
    	// if ($cTime/86400>1) {
    	
    	if ($cTime/86400>1&&$result[0]['is_email']==0) {
    		// echo "您的账号未激活，链接已失效，请重新获取链接，激活账号";
    		// include T('member', 'linkFailure');
    		$this->public_sendEmail($userId,$uname);
    		die;
    	}
        if ($emailVlidate==0) {
        	include T('member','activeAccount');
    		die;
            // MSG("您还没有经过邮箱验证");
        }
    	$modelid = $result[0]['modelid'];
    	if ($modelid!=11) {
    		MSG(L('该商户不存在'),HTTP_REFERER);
    	}
    	/*if ($process==1) {
    		$jhurl = WEBURL.'index.php?m=member&v=complete_information&uid='.$userId;
    		MSG(L('请完善信息'), $jhurl);
    	}*/
		if(get_cookie('auth')) MSG(L('logined'), HTTP_REFERER);
		if(isset($GLOBALS['submit'])) {
            checkcode($GLOBALS['checkcode']);
			$username = isset($GLOBALS['username']) ? p_htmlspecialchars($GLOBALS['username']) : '';
			$password = isset($GLOBALS['password']) ? $GLOBALS['password'] : '';
			if(empty($username)) MSG(L('username_empty'));
			if(empty($password)) MSG(L('password_empty'));
			$cookietime = isset($GLOBALS['savecookie']) ? SYS_TIME+604800 : 0;
			if(is_email($username)){
				$userfield = 'email';
			}elseif(strlen($username) == 11 && preg_match('/^1\d{10}$/', $username)){
				$userfield = 'mobile';
			}else{
				$userfield = 'username';
			}
            // $userfield  的值是  username    
			// $r = $this->db->get_one('member', '`'.$userfield.'` = "'.$username.'"', '*');
			$r = $this->db->get_one('member', '`username` = "'.$username.'"', '*');
			if (!$r) {
		        $r = $this->db->get_one('member', '`email` = "'.$username.'"', '*');  
		    }
            $synlogin = '';
			if($this->setting['ucenter']){
				$ucenter = load_class('ucenter', M);
				//	如果用户不是通过用户名登录  则要转换一下
				if($userfield != 'username' && $r)$username = $r['username'];
				$synlogin = $ucenter->login($username, $password, $r);
			}
			if(empty($r))MSG(L('user_not_exist'));
			//	判断用户是否被锁定
			if($r['lock']){
				//	判断是否在锁定的时间内
				if($r['locktime'] > SYS_TIME){
					MSG(L('user_lock'), WEBURL);
				}else{
					//	将锁定标记改为0
					$this->db->update('member', 'lock=0', 'uid='.$r['uid']);
				}
			}
				
			//	判断会员组是否禁止登录
			if($r['groupid'] == 1) MSG(L('user_banned'), WEBURL);
			//	登录记录
			$loginLog = array('uid'=>$r['uid'], 'logintime'=>SYS_TIME, 'ip'=>get_ip());
			//	判断是否是第三方登录
			if(isset($_SESSION['authid']) && $_SESSION['authid']){
				$this->db->update('member_auth', array('uid'=>$r['uid']), 'authid='.$_SESSION['authid']);
				$_SESSION['authid'] = '';
			}
			if(md5(md5($password).$r['factor']) != $r['password']){
				$loginLog['status'] = 2;
				$this->db->insert('logintime', $loginLog);
				MSG(L('password_error'));
			}else{
				$loginLog['status'] = 3;
				$this->db->insert('logintime', $loginLog);
			}
			//	判断是否需要验证Email
			if($this->setting['checkemail'] && $r['groupid'] == 2){
				if($this->send_register_mail1($r)){
					MSG(L('need_email_authentication'));
				}else{
					MSG(L('email_authentication_error'));
				}
			}
			$this->db->query('UPDATE `wz_member` SET `lasttime`='.SYS_TIME.', `lastip`="'.get_ip().'", `loginnum`=`loginnum`+1 WHERE `uid`='.$r['uid'], false);
			$this->create_cookie($r, $cookietime);
            $forward = empty($GLOBALS['forward']) ? 'index.php?m=member' : $GLOBALS['forward'];
            if(isset($GLOBALS['minilogin'])) {
            	$forward = HTTP_REFERER;
            }
			if(UZ_ISSYNCMEMBERINFO){
				$shopuid = $r['shopuid'];
				$ip = get_ip();
				$md5 = md5($shopuid.'-uZMjia-'.$ip);
				$forward = 'http://mall.uzhuang.com/index.php/passport-loginFromUzhuang.html?member_id='.$shopuid.'&md5='.$md5.'&url='.$forward;
			}

			if(isset($GLOBALS['minilogin'])) {
				
				MSG(L('login_success').'<script>setTimeout("top.dialog.get(window).close().remove();",2000)</script>',$forward,3000);
			} else {
				$jhurl = WEBURL.'index.php?m=member&v=public_complete_information&uid='.$userId;
				if ($process==1) {
    				MSG(L('请您完善信息'), $jhurl,3000);
				}else if($process==2){
					// $jhurl = rtrim(WEBURL,'/');
					// $jhurl = "index.php?m=content&f=index&v=init";
					// 如果是2则表示正在审核中
					MSG(L('审核中请您耐心等候'),$jhurl,3000);
				}else if ($process==4) {
		    		// $jhurl = WEBURL.'index.php?m=member&v=complete_information&uid='.$userId;
		    		// 如果是4则表示没有通过审核
		    		MSG(L('很遗憾！审核未通过！请您核对信息后，重新提交。'),$jhurl,3000);
		    	}else if($process==3){
		    		// 将当前用户的 member 表中 的  审核进程字段改为 5可以正常营业
		    		// 如果是3则表示通过审核 并将状态改为   5正常状态   $jhurl 为要跳转的地址
		    		// $jhurl = "index.php?m=company&f=biz_homepage&v=listing";
		    		
		    		// 暂时先屏蔽更新状态操作
		    		// $proStatus['settled_progress'] = 5;
					// $this->db->update('member',$proStatus,array('uid'=>$userId));
					MSG(L('恭喜您审核通过').$synlogin, $jhurl);
		    	}else if($process==5){
					$jhurl = "index.php?m=company&f=biz_homepage&v=listing";
					MSG(L('login_success').$synlogin, $jhurl);
				}
			}
		} else {
            $sina_akey = '';
            $seo_title = $seo_keywords = $seo_description = '会员登录';
            $forward = remove_xss(HTTP_REFERER);
			include T('member','show_special_merchants');
		}
	}

	/**
	 * 登录
	 */
	public function login(){
		if(get_cookie('auth')) MSG(L('logined'), 'index.php?m=member');
		if(isset($GLOBALS['submit'])) {
            checkcode($GLOBALS['checkcode']);
			$username = isset($GLOBALS['username']) ? p_htmlspecialchars($GLOBALS['username']) : '';
			// 根据用户名进行查找  modelid  如果为11则不让登录
	    	$where = "`username`='$username'";
	    	$result = $this->db->get_list('member', $where, 'modelid', 0, 1,$page);
	    	$modelid = $result[0]['modelid'];
	    	if ($modelid==11) {
	    		MSG(L('商户请在招商入驻页面入驻'),WEBURL.'index.php?m=content&f=index&v=public_show&cid=184&id=9');
	    	}
			$password = isset($GLOBALS['password']) ? $GLOBALS['password'] : '';
			if(empty($username)) MSG(L('username_empty'));
			if(empty($password)) MSG(L('password_empty'));
			$cookietime = isset($GLOBALS['savecookie']) ? SYS_TIME+604800 : 0;
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
			if(empty($r))MSG(L('user_not_exist'));
			//	判断用户是否被锁定
			if($r['lock']){
				//	判断是否在锁定的时间内
				if($r['locktime'] > SYS_TIME){
					MSG(L('user_lock'), WEBURL);
				}else{
					//	将锁定标记改为0
					$this->db->update('member', 'lock=0', 'uid='.$r['uid']);
				}
			}
				
			//	判断会员组是否禁止登录
			if($r['groupid'] == 1) MSG(L('user_banned'), WEBURL);
			//	登录记录
			$loginLog = array('uid'=>$r['uid'], 'logintime'=>SYS_TIME, 'ip'=>get_ip());
			//	判断是否是第三方登录
			if(isset($_SESSION['authid']) && $_SESSION['authid']){
				$this->db->update('member_auth', array('uid'=>$r['uid']), 'authid='.$_SESSION['authid']);
				$_SESSION['authid'] = '';
			}
			if(md5(md5($password).$r['factor']) != $r['password']){
				$loginLog['status'] = 2;
				$this->db->insert('logintime', $loginLog);
				MSG(L('password_error'));
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
            $forward = empty($GLOBALS['forward']) ? 'index.php?m=member&f=index&v=profile1&acbar=2' : $GLOBALS['forward'];
            if(isset($GLOBALS['minilogin'])) {
            	$forward = HTTP_REFERER;
            }
			if(UZ_ISSYNCMEMBERINFO){
				$shopuid = $r['shopuid'];
				$ip = get_ip();
				$md5 = md5($shopuid.'-uZMjia-'.$ip);
				$forward = 'http://mall.uzhuang.com/index.php/passport-loginFromUzhuang.html?member_id='.$shopuid.'&md5='.$md5.'&url='.$forward;
			}
			if(isset($GLOBALS['minilogin'])) {
				
				MSG(L('login_success').'<script>setTimeout("top.dialog.get(window).close().remove();",2000)</script>',$forward,3000);
			} else {
				MSG(L('login_success').$synlogin, $forward);
			}
		} else {
            $sina_akey = '';
            $seo_title = $seo_keywords = $seo_description = '会员登录';
            $forward = remove_xss(HTTP_REFERER);
			include T('member','login');
		}
	}

	/*ajax校验用户是否存在*/
	public function public_checkUserName(){
		$username = $GLOBALS['uname'];
		$result = $this->db->get_one('member',array('username'=>$username));
		echo json_encode($result);
	}

	public function public_mini_login() {
		include T('member','mini_login');
	}
	/**
	 * 注册
	 */
	public function register(){
		if(get_cookie('auth'))MSG(L('logined'), 'index.php?m=member', 2000);
		if(empty($this->setting['register']))MSG(L('close_register'), WEBURL, 2000);
		if(isset($GLOBALS['submit'])) {
			$mobile = $GLOBALS['mobile'];
			if(empty($mobile)) {
				echo json_encode(array('code'=>0,'data'=>null,'message'=>'手机号码错误','process_time'=>time())); exit;
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
			if($this->setting['ucenter']){
				$ucenter = load_class('ucenter', M);
				$info['ucuid'] = $ucenter->register(array($GLOBALS['username'], $GLOBALS['password'], $GLOBALS['email']));
			}
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
            $info['groupid'] = $groupid;
			$info['username'] = $GLOBALS['username'];
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

				//	判断是否是第三方登录
				if(isset($_SESSION['authid']) && $_SESSION['authid']){
					$this->db->update('member_auth', array('uid'=>$uid), 'authid='.$_SESSION['authid']);
					$_SESSION['authid'] = '';
				}
				//	判断是否需要验证邮箱 
				if($this->setting['checkemail']){
					$info['uid'] = $uid;
					if($this->send_register_mail($info)){
						MSG(L('need_email_authentication'));
					}else{
						MSG(L('email_authentication_error'));
					}
				} else {
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
					echo json_encode(array('code'=>0,'data'=>$url,'message'=>'注册成功','process_time'=>time()));

				}
			}else{
				MSG(L('register_error'));
			}
		} else {
			$setting = $this->setting;
            $seo_title = '会员注册';
            $categorys = get_cache('category','content');
			include T('member', 'register');
		}
	}

	public function model(){
		if($this->memberinfo['modelid'])MSG(L('set_model_again'));
		$model = $this->db->get_list('model', 'm="member"', 'modelid,name', 0, 200, 0, '', '', 'modelid');
		if(isset($GLOBALS['submit'])){
			$modelid = isset($GLOBALS['modelid']) ? $GLOBALS['modelid'] : 0;
			if(!isset($model[$modelid]))MSG(L('model').L('not_exists'));
			require get_cache_path('member_add', 'model');
			$form = new form_add($modelid);
			$formdata = $form->execute($GLOBALS['form']);
			$formdata['attr_data']['uid'] = $this->memberinfo['uid'];
            if($this->db->insert($formdata['attr_table'], $formdata['attr_data']) !== false){
            	$this->db->update('member', 'modelid='.$modelid, 'uid='.$this->memberinfo['uid']);
            	MSG(L('operation_success'), WEBURL.'index.php?m=member');
            }else{
            	MSG(L('operation_failure'));
            }
		}else{
			//	如果默认只有一个模型 或者  已经传递了模型的话那么就自动加载模型字段
			if(isset($GLOBALS['modelid']) && $model[$GLOBALS['modelid']]){
				$modelid = $GLOBALS['modelid'];
			}else if(count($model) == 1){
				list($modelid, $name) = each($model);
			} else {
                $modelid = 10;
            }
			//	如果存在modelid 加载模型类
			if($modelid){
				require get_cache_path('member_form','model');
				$form_build = new form_build($modelid);
            	$formdata = $form_build->execute();
			}
			include T('member', 'register_model');
		}
	}

	public function logout(){
		if(UZ_ISSYNCMEMBERINFO){
			if(!$GLOBALS['url']){
				$url = 'http://mall.uzhuang.com/index.php/passport-logout.html?url='.urlencode('http://www.uzhuang.com');
			}else{
				$url = $GLOBALS['url'];
			}
		}else{
			$url = 'index.php';
		}


		$this->clean_cookie();
        $ucsynlogout = '';
		if($this->setting['ucenter']){
			$ucenter = load_class('ucenter', M);
			$ucsynlogout = $ucenter->logout();
		}


		MSG(L('logout_success').$ucsynlogout, $url);
	}
	/**
	 * 第三方通用登录
	 */
	public function auth(){
		$type = in_array($GLOBALS['type'], array('qq', 'sina', 'baidu')) ? $GLOBALS['type'] : MSG(L('auth_not_exist'));
		$auth = load_class('auth', M);
		$info = $auth->$type();
		if($info['uid']){
			$r = $this->db->get_one('member', 'uid='.$info['uid'], '*');
			//	判断用户是否被锁定
			if($r['lock']){
				//	判断是否在锁定的时间内
				if($r['locktime'] > SYS_TIME){
					MSG(L('user_lock'), 'index.php');
				}else{
					//	将锁定标记改为0
					$this->db->update('member', 'lock=0', 'uid='.$info['uid']);
				}
			}
				
			//	判断会员组是否禁止登录
			if($r['groupid'] == 1)MSG(L('user_banned'), WEBURL);
			//	登录记录
			$this->db->insert('logintime',  array('uid'=>$info['uid'], 'logintime'=>SYS_TIME, 'ip'=>get_ip(), 'status'=>3));
			//	判断是否需要验证Email
			if($this->setting['checkemail'] && $r['groupid'] == 2){
				if($this->send_register_mail($r)){
					MSG(L('need_email_authentication'));
				}else{
					MSG(L('email_authentication_error'));
				}
			}
			$this->db->query('UPDATE `wz_member` SET `lasttime`='.SYS_TIME.', `lastip`="'.get_ip().'", `loginnum`=`loginnum`+1 WHERE `uid`='.$info['uid'], false);
			$this->create_cookie($r);
			MSG(L('login_success').$synlogin, '/index.php');
		}else{
			$_SESSION['authid'] = $info['authid'];
			include T('member', 'auth');
		}
	}
	/**
	 * 设置头像
	 */
	public function avatar(){
		//	设定图片目录
		$dir = substr(md5($this->uid), 0, 2).'/'.$this->uid.'/';
		if(isset($GLOBALS['uid']) && $GLOBALS['uid'] == $this->uid){
			$dir = WWW_ROOT.'uploadfile/member/'.$dir;
			if(!file_exists($dir)) {
				mkdir($dir, 0777, true);
			}
			$filename = $dir.'180x180.jpg';
			file_put_contents($filename, file_get_contents("php://input"));
			$isimg = exif_imagetype($filename);
			if($isimg > 3 || $isimg < 1){
				unlink($filename);
				exit();
			}
			$avatararr = array('30x30.jpg', '45x45.jpg', '90x90.jpg', '180x180.jpg');
			$this->db->update('member', array('avatar'=>1), array('uid'=>$this->uid));
			exit('1');
		}else{
			$memberinfo = $this->memberinfo;
			$upurl = base64_encode(WEBURL.'/index.php?m=member&v=avatar&uid='.$this->uid);
			include T('member', 'avatar');
		}
	}
	/**
	 * 验证Email
	 */
	public function public_verify_email(){
		$uid = isset($GLOBALS['uid']) ? (int)$GLOBALS['uid'] : 0;
		$key = isset($GLOBALS['key']) ? $GLOBALS['key'] : '';
		if($key != md5($uid._KEY))MSG(L('illegal_operation'));
		if($uid)$user = $this->db->get_one('member', 'uid='.$uid, 'uid,username,password,groupid,email,modelid');
		if(empty($user))MSG(L('user_not_exist'));
		if($user['groupid'] != 2)MSG(L('operation_again'));
		if(isset($GLOBALS['submit'])){
			checkcode($GLOBALS['checkcode']);
			if($GLOBALS['email'] == $user['email']){
                if($user['modelid']==23) {
                    $groupid = 5;// 机构
                } else if($user['modelid']==11) {
                    $groupid = 4;//企业
                } else {
                    $groupid = 3;
                }
				$this->db->update('member',array('groupid'=>$groupid), 'uid='.$uid);
				$this->create_cookie($user);
				//	判断是否选了模型
				if($user['modelid']){
					MSG(L('operation_success'), WEBURL.'index.php?m=member');
				}else{
					MSG(L('operation_success').','.L('need_set_model'), WEBURL.'index.php?m=member&v=model');
				}
			}else{
				MSG(L('illegal_operation'));
			}
		}else{
			include T('member', 'verify_email');
		}
	}

	public function public_find_password_email(){
		$email = isset($GLOBALS['email']) && is_email($GLOBALS['email']) ? $GLOBALS['email'] : '';
		if(isset($GLOBALS['key'])){
			if($GLOBALS['key'] != md5($email._KEY))MSG(L('illegal_operation'));
			if(isset($GLOBALS['submit'])){
				if($GLOBALS['password'] == '' || $GLOBALS['password'] != $GLOBALS['pwdconfirm'])MSG(L('password_not_identical'));
				checkcode($GLOBALS['checkcode']);
				if($this->db->query('UPDATE `wz_member` SET `password` = md5(CONCAT("'.md5($GLOBALS['password']).'", `factor`)) WHERE `email`="'.$email.'"')){
					MSG(L('operation_success'), WEBURL.'index.php?m=member&v=login');
				}else{
					MSG(L('operation_failure'));
				}
			}else{
				include T('member', 'find_password_email');
			}
		}else{
			if(isset($GLOBALS['submit'])){
				checkcode($GLOBALS['checkcode']);
				if($email)$user = $this->db->get_one('member', "email='$email'", 'uid,username,password,groupid,email,modelid');
				if($user){
					if($this->send_register_mail($user, 'password')){
						MSG(L('need_email_authentication'));
					}else{
						MSG(L('email_authentication_error'));
					}
				}else{
					MSG(L('user_not_exist'));
				}
			}else{
				include T('member', 'find_password_email');
			}
		}
	}
	/**
	 * 判断邀请码是否有效
	 */
	public function public_check_invite(){
		$invite = isset($GLOBALS['invite']) && $GLOBALS['invite'] ? $GLOBALS['invite'] : (isset($GLOBALS['param']) && $GLOBALS['param'] ? $GLOBALS['param'] : false);
		if(empty($invite) || strlen($invite) != 8 || !preg_match('/^[0-9a-z]{8}$/i', $invite))exit('{"info":"'.L('illegal_parameters').'","status":"n"}');
		echo $this->member->check_invite($invite, 1);
	}
	/**
	 * 判断用户名是否合规
	 */
	public function public_check_user(){
		$username = isset($GLOBALS['username']) && $GLOBALS['username'] ? $GLOBALS['username'] : (isset($GLOBALS['param']) && $GLOBALS['param'] ? $GLOBALS['param'] : false);
		if(empty($username)) exit('{"info":"'.L('illegal_parameters').'","status":"n"}');
		if(strtolower(CHARSET) != 'utf-8')$username = iconv('UTF-8', 'gb2312//IGNORE', $username);
		echo $this->member->check_user($username, 1);
	}
	/**
	 * 判断email是否已被使用
	 */
	public function public_check_email(){
		$email = isset($GLOBALS['email']) && $GLOBALS['email'] ? $GLOBALS['email'] : (isset($GLOBALS['param']) && $GLOBALS['param'] ? $GLOBALS['param'] : false);
		if(empty($email))exit('{"info":"'.L('illegal_parameters').'","status":"n"}');
		$uid = isset($GLOBALS['uid']) ? (int)$GLOBALS['uid'] : 0;
		echo $this->member->check_email($email, $uid, 1);
	}
	/**
	 * 判断手机号码是否可用
	 */
	public function public_check_mobile(){
		$mobile = isset($GLOBALS['mobile']) && $GLOBALS['mobile'] ? $GLOBALS['mobile'] : (isset($GLOBALS['param']) && $GLOBALS['param'] ? $GLOBALS['param'] : false);
		if(empty($mobile))exit('{"info":"'.L('illegal_parameters').'","status":"n"}');
		$uid = isset($GLOBALS['uid']) ? (int)$GLOBALS['uid'] : 0;
		if(!$uid) $uid = get_cookie('_uid');
		echo $this->member->check_mobile($mobile, $uid, 1);
	}

    /**
     * 个人资料修改
     */
    public function profile() {
		$point_config = get_cache('point_config');
        $seo_title = '个人信息';
        $memberinfo = $this->memberinfo;

        $uid = $this->memberinfo['uid'];
        $wherem = "`uid`='$uid'";
        $resultm = $this->db->get_list('member', $wherem, '*');
        $groups = $this->groups;
        $modelid = $memberinfo['modelid'];
        // 如果是商户则根据审核进程跳转到不同的页面
        if ($modelid==11) {
        	$process=$resultm[0]['settled_progress'];
        	$jhurl = WEBURL.'index.php?m=member&v=public_complete_information&uid='.$uid;
        	if ($process==1) {
    				MSG(L('请您完善信息'), $jhurl,3000);
				}else if($process==2){
					// 如果是2则表示正在审核中
					MSG(L('审核中请您耐心等候'),$jhurl,3000);
				}else if ($process==4) {
		    		// $jhurl = WEBURL.'index.php?m=member&v=complete_information&uid='.$userId;
		    		// 如果是4则表示没有通过审核
		    		MSG(L('对不起您没有通过审核,请您完善信息'),$jhurl,3000);
		    	}else if($process==3){
		    		// 将当前用户的 member 表中 的  审核进程字段改为 5可以正常营业
		    		// 如果是3则表示通过审核 并将状态改为   5正常状态   $jhurl 为要跳转的地址
					MSG(L('恭喜您审核通过').$synlogin, $jhurl);
		    	}else if($process==5){
					$jhurl = "index.php?m=company&f=biz_homepage&v=listing";
					MSG(L('login_success').$synlogin, $jhurl);
				}
        }
        $model_r = $this->db->get_one('model',array('modelid'=>$modelid));

        $data = $this->db->get_one($model_r['attr_table'],array('uid'=>$uid));
		if($data) {
			$data = array_merge($memberinfo,$data);
		} else {
			$data = $memberinfo;
		}
        if(isset($GLOBALS['submit'])) {
			if($memberinfo['checkmec']==1) {
				MSG('账号已审核通过，如需修改，请联系客服！');
			}
            checkcode($GLOBALS['checkcode']);
            $formdata = '';
            require get_cache_path('member_add','model');
            $form_add = new form_add($modelid);
			if(empty($GLOBALS['form'])) MSG('参数错误');
            $formdata = $form_add->execute($GLOBALS['form']);
			/**
            *if(is_tel($GLOBALS['mobile'])) {
            *    $formdata['master_data']['mobile'] = $GLOBALS['mobile'];
	        *}
			*/
			if(!empty($formdata['master_data'])) {
				$this->db->update($formdata['master_table'],$formdata['master_data'],array('uid'=>$uid));
			}
            if(!empty($formdata['attr_table']) && !empty($formdata['attr_data'])) {
                $this->db->update($formdata['attr_table'],$formdata['attr_data'],array('uid'=>$uid));
            }

            //执行更新
            require get_cache_path('member_update','model');
            $form_update = new form_update($modelid);

            $formdata['master_data']['uid'] = $uid;

            $form_update->execute($formdata);

            MSG(L('operation_success'),HTTP_REFERER);
        } else {
            require get_cache_path('member_form','model');
            $form_build = new form_build($modelid);

            $formdata = $form_build->execute($data);
			//print_r($formdata);
            $field_list = '';
            if(is_array($formdata['0'])) {
                foreach($formdata['0'] as $field=>$info) {
                    if($info['powerful_field']) continue;
                    if($info['formtype']=='powerful_field') {
                        foreach($formdata['0'] as $_fm=>$_fm_value) {
                            if($_fm_value['powerful_field']) {
                                $info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'],$info['form']);
                            }
                        }
                        foreach($formdata['1'] as $_fm=>$_fm_value) {
                            if($_fm_value['powerful_field']) {
                                $info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'],$info['form']);
                            }
                        }
                    }
                    $field_list[] = $info;
                }
            }
			$groupid = $memberinfo['groupid'];
            $points = $memberinfo['points'];
			if($groupid==3) {
				$next_group = 6;
				$nextpoints = $groups[$next_group]['points']-$points;
			}elseif($groupid==6) {
				$next_group = 7;
				$nextpoints = $groups[$next_group]['points']-$points;
			} elseif($groupid==7) {
				$next_group = 8;
				$nextpoints = $groups[$next_group]['points']-$points;
			} elseif($groupid==8) {
				$next_group = 9;
				$nextpoints = $groups[$next_group]['points']-$points;
			} elseif($groupid==9) {
				$next_group = 9;
				$nextpoints = 0;
			}
			$dir = substr(md5($this->uid), 0, 2).'/'.$this->uid.'/';
			$upurl = base64_encode(WEBURL.'/index.php?m=member&v=avatar&uid='.$this->uid);

			include T('member', 'profile');
        }
    }

	public function profile1() {
		$memberinfo = $this->memberinfo;

		$seo_title = '个人信息';
		$memberinfo = $this->memberinfo;

		$uid = $this->memberinfo['uid'];
		$groups = $this->groups;
		$modelid = $memberinfo['modelid'];
		$model_r = $this->db->get_one('model',array('modelid'=>$modelid));
        
		$data = $this->db->get_one($model_r['attr_table'],array('uid'=>$uid));
		if($data) {
			$data = array_merge($memberinfo,$data);
		} else {
			$data = $memberinfo;
		}
		if(isset($GLOBALS['submit'])) {
			if($memberinfo['checkmec']==1) {
				MSG('账号已审核通过，如需修改，请联系客服！');
			}
			checkcode($GLOBALS['checkcode']);
			$formdata = '';
			require get_cache_path('member_add','model');

			$form_add = new form_add($modelid);
			if(empty($GLOBALS['form'])) MSG('参数错误');
			$formdata = $form_add->execute($GLOBALS['form']);
			//($formdata);
			/**
			 *if(is_tel($GLOBALS['mobile'])) {
			 *    $formdata['master_data']['mobile'] = $GLOBALS['mobile'];
			 *}
			 */
			if(!empty($formdata['master_data'])) {
				$this->db->update($formdata['master_table'],$formdata['master_data'],array('uid'=>$uid));
			}
			if(!empty($formdata['attr_table']) && !empty($formdata['attr_data'])) {
				$this->db->update($formdata['attr_table'],$formdata['attr_data'],array('uid'=>$uid));
			}

			//执行更新
			require get_cache_path('member_update','model');
			$form_update = new form_update($modelid);

			$formdata['master_data']['uid'] = $uid;

			$form_update->execute($formdata);

			MSG(L('operation_success'),HTTP_REFERER);
		} else {
			require get_cache_path('member_form','model');
			$form_build = new form_build($modelid);

			$formdata = $form_build->execute($data);
			//print_r($formdata);
			$field_list = '';
			if(is_array($formdata['0'])) {
				foreach($formdata['0'] as $field=>$info) {
					if($info['powerful_field']) continue;
					if($info['formtype']=='powerful_field') {
						foreach($formdata['0'] as $_fm=>$_fm_value) {
							if($_fm_value['powerful_field']) {
								$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'],$info['form']);
							}
						}
						foreach($formdata['1'] as $_fm=>$_fm_value) {
							if($_fm_value['powerful_field']) {
								$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'],$info['form']);
							}
						}
					}
					$field_list[] = $info;
				}
			}
			$groupid = $memberinfo['groupid'];
			$points = $memberinfo['points'];
			if($groupid==3) {
				$next_group = 6;
				$nextpoints = $groups[$next_group]['points']-$points;
			}elseif($groupid==6) {
				$next_group = 7;
				$nextpoints = $groups[$next_group]['points']-$points;
			} elseif($groupid==7) {
				$next_group = 8;
				$nextpoints = $groups[$next_group]['points']-$points;
			} elseif($groupid==8) {
				$next_group = 9;
				$nextpoints = $groups[$next_group]['points']-$points;
			} elseif($groupid==9) {
				$next_group = 9;
				$nextpoints = 0;
			}
			
			$dir = substr(md5($this->uid), 0, 2).'/'.$this->uid.'/';
			$upurl = base64_encode(WEBURL.'/index.php?m=member&v=avatar&uid='.$this->uid);
			$memberuid = intval($memberinfo['uid']);
            $where = " `uid` <> $memberuid ";
            $log_rs = $this->db->get_list('day_log_demand_list',$where, '*', 0, 1, $page, 'id DESC','','', $_POST['page_urlrule'],$variables);
            $result = $this->db->get_list('demand',array('uid'=>$memberinfo['uid']), '*',0,1,'pages','id desc');
		    $total = $this->db->number;
            /*if($total!=0){
              //城市区域地址
            
			$proviceId=$log_rs[0]['areaid_1'];
	        // 市ID
	        $cityId=$log_rs[0]['areaid_2'];
	        // 区ID
	        $countryId=$log_rs[0]['areaid'];
	        // 查询条件
	        $where6 = "`lid` in ($proviceId,$cityId,$countryId)";
	        $area=$this->db->get_list('linkage_data', $where6);
	        $provice=$area[0]['name'];
	        $city1=$area[2]['name'];
	        $country=$area[1]['name'];
	    }*/
          //$log_rs = $this->db->get_list('day_log_demand',array('uid'=>$memberinfo['uid']), '*', 0, 1, $page, 'id DESC');
		    $pages = $this->db->pages;
		    $configs_picture = get_config('picture_config');
			include T('member', 'profile1');
		}
	}


	public function profile4() {
		$point_config = get_cache('point_config');
        $seo_title = '个人信息';
        $memberinfo = $this->memberinfo;

        $uid = $this->memberinfo['uid'];

        $groups = $this->groups;
        
        $modelid = $memberinfo['modelid'];
        $model_r = $this->db->get_one('model',array('modelid'=>$modelid));

        $data = $this->db->get_one($model_r['attr_table'],array('uid'=>$uid));
		if($data) {
			$data = array_merge($memberinfo,$data);
		} else {
			$data = $memberinfo;
		}
        if(isset($GLOBALS['submit'])) {
			if($memberinfo['checkmec']==0) {
				MSG('账号已审核通过，如需修改，请联系客服！');
			}
            checkcode($GLOBALS['checkcode']);
            $formdata = '';
            require get_cache_path('member_add','model');
            $form_add = new form_add($modelid);
			if(empty($GLOBALS['form'])) MSG('参数错误');
            $formdata = $form_add->execute($GLOBALS['form']);
			/**
            *if(is_tel($GLOBALS['mobile'])) {
            *    $formdata['master_data']['mobile'] = $GLOBALS['mobile'];
	        *}
			*/
			if(!empty($formdata['master_data'])) {
				$this->db->update($formdata['master_table'],$formdata['master_data'],array('uid'=>$uid));
			}
            if(!empty($formdata['attr_table']) && !empty($formdata['attr_data'])) {
                $this->db->update($formdata['attr_table'],$formdata['attr_data'],array('uid'=>$uid));
            }

            //执行更新
            require get_cache_path('member_update','model');
            $form_update = new form_update($modelid);

            $formdata['master_data']['uid'] = $uid;

            $form_update->execute($formdata);

            MSG(L('operation_success'),HTTP_REFERER);
        } else {
            require get_cache_path('member_form','model');
            $form_build = new form_build($modelid);

            $formdata = $form_build->execute($data);
			//print_r($formdata);
            $field_list = '';
            if(is_array($formdata['0'])) {
                foreach($formdata['0'] as $field=>$info) {
                    if($info['powerful_field']) continue;
                    if($info['formtype']=='powerful_field') {
                        foreach($formdata['0'] as $_fm=>$_fm_value) {
                            if($_fm_value['powerful_field']) {
                                $info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'],$info['form']);
                            }
                        }
                        foreach($formdata['1'] as $_fm=>$_fm_value) {
                            if($_fm_value['powerful_field']) {
                                $info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'],$info['form']);
                            }
                        }
                    }
                    $field_list[] = $info;
                }
            }
			$groupid = $memberinfo['groupid'];
            $points = $memberinfo['points'];
			if($groupid==3) {
				$next_group = 6;
				$nextpoints = $groups[$next_group]['points']-$points;
			}elseif($groupid==6) {
				$next_group = 7;
				$nextpoints = $groups[$next_group]['points']-$points;
			} elseif($groupid==7) {
				$next_group = 8;
				$nextpoints = $groups[$next_group]['points']-$points;
			} elseif($groupid==8) {
				$next_group = 9;
				$nextpoints = $groups[$next_group]['points']-$points;
			} elseif($groupid==9) {
				$next_group = 9;
				$nextpoints = 0;
			}
			$dir = substr(md5($this->uid), 0, 2).'/'.$this->uid.'/';
			$upurl = base64_encode(WEBURL.'/index.php?m=member&v=avatar&uid='.$this->uid);

			include T('member', 'profile4');
        }
    }



    /**
     * 修改密码
     */
    public function edit_password() {
        $memberinfo = $this->memberinfo;
        if(isset($GLOBALS['submit'])) {
            checkcode($GLOBALS['checkcode']);
            $password = $GLOBALS['password'];
            $password2 = $GLOBALS['password2'];
            if($password!=$password2) MSG(L('password_not_identical'));
            $oldpassword = $GLOBALS['oldpassword'];
            if(md5(md5($oldpassword).$memberinfo['factor']) != $memberinfo['password']) MSG(L('password_error'));

            $factor = random_string('diy', 6);
            $this->db->update('member', array('factor'=>$factor, 'password'=>md5(md5($password).$factor)), '`uid`='.$memberinfo['uid']);
            MSG(L('operation_success'),'index.php?m=member');
        } else {
            $seo_title = '修改密码';
            include T('member', 'edit_password');
        }
    }
    
    /**
     * 商户修改密码
     */
    public function edit_password1() {
        $memberinfo = $this->memberinfo;
        if(isset($GLOBALS['submit'])) {
            checkcode($GLOBALS['checkcode']);
            $password = $GLOBALS['password'];
            $password2 = $GLOBALS['password2'];
            if($password!=$password2) MSG(L('password_not_identical'));
            $oldpassword = $GLOBALS['oldpassword'];
            if(md5(md5($oldpassword).$memberinfo['factor']) != $memberinfo['password']) MSG(L('password_error'));

            $factor = random_string('diy', 6);
            $this->db->update('member', array('factor'=>$factor, 'password'=>md5(md5($password).$factor)), '`uid`='.$memberinfo['uid']);
            
            // 密码修改后    提示并退出
			$this->clean_cookie();
            MSG(L('密码修改成功,请重新登录'),WEBURL.'index.php?v=public_show&cid=184&id=9');
        } else {
            $seo_title = '修改密码';
            include T('member', 'edit_password');
        }
    }

	/**
	 * 账户安全检查
	 */
	public function account_safe() {
		$memberinfo = $this->memberinfo;
		$uid = $memberinfo['uid'];

		$groupid = $memberinfo['groupid'];
		$safe_level = 1;//低
		if($memberinfo['ischeck_mobile'] && $groupid>2) {
			$safe_level = 3;//高
		} elseif($memberinfo['ischeck_mobile'] || $groupid>2) {
			$safe_level = 2;//中
		}
		include T('member', 'account_safe');
	}

	public function account_safe2() {
		$memberinfo = $this->memberinfo;
		$uid = $memberinfo['uid'];

		$groupid = $memberinfo['groupid'];
		$safe_level = 1;//低
		if($memberinfo['ischeck_mobile'] && $groupid>2) {
			$safe_level = 3;//高
		} elseif($memberinfo['ischeck_mobile'] || $groupid>2) {
			$safe_level = 2;//中
		}
		include T('member', 'account_safe2');
	}

	public function account_safe1(){
		$memberinfo = $this->memberinfo;
		$uid = $memberinfo['uid'];

		$groupid = $memberinfo['groupid'];
		$safe_level = 1;//低
		if($memberinfo['ischeck_mobile'] && $groupid>2) {
			$safe_level = 3;//高
		} elseif($memberinfo['ischeck_mobile'] || $groupid>2) {
			$safe_level = 2;//中
		}
		include T('member', 'account_safe1');
	}
	/**
 * 修改手机
 */
	public function edit_mobile() {
		if(isset($GLOBALS['submit'])) {
			$mobile = $GLOBALS['mobile'];
			if(!preg_match('/^(?:13\d{9}|15[0|1|2|3|5|6|7|8|9]\d{8}|17[0|1|2|3|5|6|7|8|9]\d{8}|18[0|2|3|5|6|7|8|9]\d{8}|14[5|7]\d{8})$/',$mobile)) {
				MSG('手机号码错误');
			}
			$uid = $this->memberinfo['uid'];
			$posttime = SYS_TIME-300;//5分钟内有效
			$r = $this->db->get_one('sms_checkcode',"`mobile`='$mobile' AND `uid`='$uid' AND `posttime`>$posttime",'*',0,'id DESC');
			if(!$r || $r['code']!=$GLOBALS['smscode']) MSG("手机号验证失败！");
			$this->db->update('member', array('mobile'=>$mobile,'ischeck_mobile'=>1), array('uid' => $uid));
            if($this->memberinfo['ischeck_mobile']==0) {
                $point_config = get_cache('point_config');
                $credit_api = load_class('credit_api','credit');
                $credit_api->handle($uid, '+',$point_config['mobile_check'], '验证手机号：'.$mobile);
            }
            if(isset($GLOBALS['buyer']) && $this->memberinfo['ischeck_email']==0) {
                MSG('还差一步：完成邮件验证后，就可以购物了！','index.php?m=member&f=index&v=edit_email&buyer=1',3000);
            } else {
                MSG('手机号更新成功了了了了了了了了！','?m=member&f=index&v=account_safe');
            }

		} else {
			// $changePhone=$GLOBALS['changePhone'];
			$memberinfo = $this->memberinfo;
			include T('member', 'edit_mobile');
		}
	}

	/**
	 * 修改手机
	 */
	public function edit_bz_mobile() {
		if(isset($GLOBALS['submit'])) {
			$mobile = $GLOBALS['mobile'];
			if(!preg_match('/^(?:13\d{9}|15[0|1|2|3|5|6|7|8|9]\d{8}|17[0|1|2|3|5|6|7|8|9]\d{8}|18[0|2|3|5|6|7|8|9]\d{8}|14[5|7]\d{8})$/',$mobile)) {
				MSG('手机号码错误');
			}
			$uid = $this->memberinfo['uid'];
			$posttime = SYS_TIME-300;//5分钟内有效
			$r = $this->db->get_one('sms_checkcode',"`mobile`='$mobile' AND `uid`='$uid' AND `posttime`>$posttime",'*',0,'id DESC');
			if(!$r || $r['code']!=$GLOBALS['smscode']) MSG("手机号验证失败！");
			$this->db->update('member', array('mobile'=>$mobile,'ischeck_mobile'=>1), array('uid' => $uid));
            if($this->memberinfo['ischeck_mobile']==0) {
                $point_config = get_cache('point_config');
                $credit_api = load_class('credit_api','credit');
                $credit_api->handle($uid, '+',$point_config['mobile_check'], '验证手机号：'.$mobile);
            }
            if(isset($GLOBALS['buyer']) && $this->memberinfo['ischeck_email']==0) {
                MSG('还差一步：完成邮件验证后，就可以购物了！','index.php?m=member&f=index&v=edit_email&buyer=1',3000);
            } else {
                MSG('手机号更新成功！','index.php?m=member&f=index&v=account_safe2');
            }

		} else {

			$memberinfo = $this->memberinfo;
        	$uid = $memberinfo['uid'];
        	$modelid = $this->db->get_one('member',array('uid'=>$uid),'modelid');
        	$modelid = $modelid['modelid'];
        	if ($modelid==11) {
        		$r = $this->db->get_one('member_company_data',array('uid'=>$uid),'chargePersonPhone');
        		$phone=$r['chargePersonPhone'];
        	}
			include T('member', 'edit_mobile');
		}
	}
	/**
	 * 修改邮箱
	 */
	public function edit_email() {
		if(isset($GLOBALS['submit'])) {
			load_function('preg_check');
			$email = $GLOBALS['email'];
			if(!is_email($email)) {
				MSG('邮箱错误');
			}
			$uid = $this->memberinfo['uid'];
			$r = $this->db->get_one('member', array('email' => $email));
			if($r && $r['uid']!=$uid) {
				MSG('邮箱地址已经被占用！');
			}

			$this->db->update('member', array('email'=>$email,'ischeck_email'=>0), array('uid' => $uid));
			$r = $this->db->get_one('setting',array('keyid'=>'sendmail','m'=>'core'));
			$setting = unserialize($r['data']);
			$config = get_cache('sendmail');
			$siteconfigs = get_cache('siteconfigs');
			$password = decode($config['password']);
			//load_function('sendmail');
			$t = date('YmdHis');
			$jhurl = WEBURL.'index.php?m=member&f=json&v=active_email&uid='.$uid.'&email='.$email.'&t='.$t.'&auth='.urlencode(encode($t.$uid.$email));
			$subject = '优装美家邮件验证';
			// $message = "尊敬的用户，您正在使用【".$siteconfigs['sitename']."】进行邮箱验证<br>";
			$message = "尊敬的用户,您好：<br/>为保障您的账户安全，请在24小时内点击该链接，您也可以将连接复制到浏览器地址访问（<a href=\"http://www.uzhuang.com\" target='_self'>www.uzhuang.com</a>）<br/>点击链接完成邮箱修改。";
			$message .= "<a href='$jhurl' target='_self'>{$jhurl}</a>";
			$mail = load_class('sendmail');
			$mail->setServer($config['smtp_server'], $config['smtp_user'], $password); //设置smtp服务器，普通连接方式
			//$mail->setServer("smtp.gmail.com", "XXXXX@gmail.com", "XXXXX", 465, true); //设置smtp服务器，到服务器的SSL连接
			$mail->setFrom($config['send_email']); //设置发件人
			$mail->setReceiver($email); //设置收件人，多个收件人，调用多次
			$mail->setMail($subject, $message); //设置邮件主题、内容
			$mail->sendMail(); //发送
			if($mail->_errorMessage) {
				MSG($mail->_errorMessage);
			}


			MSG('验证邮件已发送成功，请登录邮箱验证！',HTTP_REFERER);
		} else {
			$memberinfo = $this->memberinfo;
			include T('member', 'edit_email');
		}
	}

	/**
	 * 商户修改邮箱
	 */
	public function editMerchantEmail() {
		if(isset($GLOBALS['submit'])) {
			load_function('preg_check');
			$email = $GLOBALS['email'];
			if(!is_email($email)) {
				MSG('邮箱错误');
			}
			$uid = $this->memberinfo['uid'];
			$r = $this->db->get_one('member', array('email' => $email));
			if($r && $r['uid']!=$uid) {
				MSG('邮箱地址已经被占用！');
			}

			// $this->db->update('member', array('email'=>$email,'ischeck_email'=>0), array('uid' => $uid));
			$r = $this->db->get_one('setting',array('keyid'=>'sendmail','m'=>'core'));
			$setting = unserialize($r['data']);
			$config = get_cache('sendmail');
			$siteconfigs = get_cache('siteconfigs');
			$password = decode($config['password']);
			//load_function('sendmail');
			$t = date('YmdHis');
			$jhurl = WEBURL.'index.php?m=member&f=json&v=active_email&uid='.$uid.'&email='.$email.'&t='.$t.'&auth='.urlencode(encode($t.$uid.$email));
			$subject = '优装美家邮件验证';
			// $message = "尊敬的用户，您正在使用【".$siteconfigs['sitename']."】进行邮箱验证<br>";
			$message = "为保障您的账户安全，请在24小时内点击该链接，您也可以将连接复制到浏览器地址访问（<a href=\"http://www.uzhuang.com\" target='_self'>www.uzhuang.com</a>）<br/>点击链接完成邮箱修改。";
			$message .= "<a href='$jhurl' target='_self'>{$jhurl}</a>";
			$mail = load_class('sendmail');
			$mail->setServer($config['smtp_server'], $config['smtp_user'], $password); //设置smtp服务器，普通连接方式
			//$mail->setServer("smtp.gmail.com", "XXXXX@gmail.com", "XXXXX", 465, true); //设置smtp服务器，到服务器的SSL连接
			$mail->setFrom($config['send_email']); //设置发件人
			$mail->setReceiver($email); //设置收件人，多个收件人，调用多次
			$mail->setMail($subject, $message); //设置邮件主题、内容
			$mail->sendMail(); //发送
			if($mail->_errorMessage) {
				MSG($mail->_errorMessage);
			}


			MSG('我们已将邮件发送至您的邮箱，请立即完成验证',HTTP_REFERER);
		} else {
			$memberinfo = $this->memberinfo;
			$uid = $memberinfo['uid'];
        	$modelid = $this->db->get_one('member',array('uid'=>$uid),'modelid');
        	$modelid = $modelid['modelid'];

			include T('member', 'edit_merchant_email');
		}
	}



	public function public_login_mail(){
    	// 获取登录用户的用户名
    	$uid = $GLOBALS['uid'];
    	// 根据用户名进行查找
    	$where = "`uid` = '$uname'";
    	//$result = $this->db->get_one('member', $where, '*', 0, 1,$page);
    	 $r = $this->db->get_one('member',array('uid'=>$uid),'*');
    	//	$modelid = $result['modelid'];
    	
    	// 得到用户ID  和商户完善信息的情况
    
    	/*if ($process==1) {
    		$jhurl = WEBURL.'index.php?m=member&v=complete_information&uid='.$userId;
    		MSG(L('请完善信息'), $jhurl);
    	}*/
         $cookietime = isset($GLOBALS['savecookie']) ? SYS_TIME+604800 : 0;

         //$r = $this->db->get_one('member', '`username` = "'.$username.'"', '*');
        
            $synlogin = '';
			if($this->setting['ucenter']){
				$ucenter = load_class('ucenter', M);
				//	如果用户不是通过用户名登录  则要转换一下
				if($userfield != 'username' && $r)$username = $r['username'];
				$synlogin = $ucenter->login($username, $password, $r);
			}
			if(empty($r))MSG(L('user_not_exist'));

			$this->create_cookie($r, $cookietime);
            $forward = empty($GLOBALS['forward']) ? 'index.php?m=member&v=public_complete_information&uid=310' : $GLOBALS['forward'];
            MSG(L('请补全信息'),$forward);
		
	}

	public function public_validateMobile1(){
		$phone=$GLOBALS['phone'];
		$r = $this->db->get_one('member',array('mobile'=>$phone),'mobile');
		if ($r) {
			echo json_encode(1);
		}else{
			echo json_encode(0);
		}
	}
	public function public_validateMobile(){
		$phone=$GLOBALS['phone'];
		$r = $this->db->get_one('member',array('mobile'=>$phone),'mobile');
		if ($r) {
			echo json_encode(1);
		}else{
			echo json_encode(0);
		}
	}
}
