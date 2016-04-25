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
header("Content-type:text/html;charset=utf-8");
header("Access-Control-Allow-Origin: *");
defined('IN_WZ') or exit('No direct script access allowed');
load_class('foreground', 'member');
load_function('curl');
class sina extends WUZHI_foreground {

   public function __construct() {
        parent::__construct();
		include_once( 'sina/config.php' );
		include_once( 'sina/saetv2.ex.class.php' );
		
      }

     /**
      * @uses : 新浪微博登录
      * @param :
      * @return : $sina_url----登录地址
      */
      public function sina_login(){
             $obj = new SaeTOAuthV2(WB_AKEY,WB_SKEY);
             $sina_url = $obj->getAuthorizeURL(WB_CALLBACK_URL);
             // echo "<pre>";print_r($sina_url);exit;
            echo json_encode(array('code'=>1,'data'=>$sina_url,'message'=>'新浪微博登录地址','process_time'=>time()));
      }
     //回调地址
	public function callback(){
      
          session_start();

		$o = new SaeTOAuthV2(WB_AKEY,WB_SKEY);

		if (isset($_REQUEST['code'])) {
				$keys = array();
				$keys['code'] = $_REQUEST['code'];
				$keys['redirect_uri'] = WB_CALLBACK_URL;
				
			    $token = $o->getAccessToken('code',$keys);
			   
		    }
		       if ($token) {
		         	//把返回的code存到session里
			        $_SESSION['token'] = $token;
			        setcookie( 'weibojs_'.$o->client_id, http_build_query($token) );
		            //根据ID获取用户等基本信息start
					$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
					$ms  = $c->home_timeline(); // done
					$uid_get = $c->get_uid();
					$uid = $uid_get['uid'];
					//用户信息$user_message['screen_name']
					$user_message = $c->show_user_by_id($uid);
					// echo "<pre>";print_r($user_message['avatar_large']);exit;
					// var_dump($user_message);exit;
					 $time1a=date('YmdHi',time());            
	                $time1b=substr($time1a,2);
                    $username='MB'.$time1b.rand(0,9).rand(0,9);
					
		            $arr=array();
		           $mem=$this->db->get_list('third_login',$where,'openid');
                   foreach ($mem as $key => $value) {
                   	   $arr[]=$value['openid'];
                   }
                     
                   if(!in_array($user_message['id'],$arr)){
                           
				         // 未授权就把用户信息插入到third_login、member表中,也就是注册
				           $res1=array();
				           $res1['uid']='';
				           $res1['openid']=$user_message['id'];
				           $res1['login_way']='sina';
				           $res1['data']=serialize($user_message);
				           $this->db->insert('third_login',$res1);
				           //取最后一次插入的主键id
                           $third_insert_id=$this->db->insert_id();

				           $res=array();
		                   $res['username']=$username;
		                   $res['nickname']=$user_message['screen_name'];
		                   if(isset($user_message['avatar_large'])&&!empty($user_message['avatar_large'])){
		                   	  $res['avatar']=$user_message['avatar_large'];
		                   }
		                   $res['password']=md5(time());
		                   $res['modelid']=(int)10;
		                   $res['lasttime']=date('Y-m-d H:i:s',time());
		                   $res['mobile_station']='sina';
				           $this->db->insert('member',$res);
				           //取最后一次插入的主键id
				           $member_insert_id=$this->db->insert_id();
				           
                           // 取第三方登录表的id存到member表中
                           $this->db->update('third_login',array('uid'=>$member_insert_id),'`id`="'.$third_insert_id.'"'); 
                            
						//cookie里存入uid
						 $r = $this->db->get_one('member', '`uid`="'.$member_insert_id.'"','*');
						 $this->create_cookie($r,SYS_TIME+604800);

				           	//同步注册商城
								if(true){
									$aSyncData = array(
										'username' => $r['username'],
										'password' => $r['password'],
										'mobile' => $r['mobile'],
										'ip' => get_ip(),

									);
									$aSyncData['md5'] = md5($r['username'].'-uZMjia-'.$aSyncData['ip']);
									$rs = post_curl('mall.uzhuang.com/uzMainsite-syncFromMainsite.html' , $aSyncData);
									$rs = json_decode($rs , 1);
									$member_id = $rs['data']['member_id'];
									$this->db->update('member', array('shopuid'=>$member_id), 'uid='.$r['uid']);
								}
						    
						    echo "<script>alert('授权成功');location.href='http://m.uzhuang.com'</script>";

                   }else{
                   	    // 取member表中的uid存到第三方登录表中
                   	    $third_login_uid = $this->db->get_one('third_login', '`openid`="'.$user_message['id'].'"','*');
                   	    // 取第三方登录表的id存到member表中,并更新请求登陆
                        $this->db->update('member',array('lasttime'=>date('Y-m-d H:i:s',time())),'`uid`="'.$third_login_uid['uid'].'"'); 
                        //cookie里存入uid
                        $r = $this->db->get_one('member', '`username`="'.$username.'"','*');
					    $this->create_cookie($r,SYS_TIME+604800);
                        // MSG(L("登陆成功"),'http://m.uzhuang.com');
                        
                        echo "<script>alert('登陆成功');location.href='http://m.uzhuang.com'</script>"; 
                        
                   }

				} else {
		           
		                echo "<script>alert('登陆失败');location.href='http://m.uzhuang.com'</script>"; 
		          }
 
		}

}
