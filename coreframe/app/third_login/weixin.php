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
class weixin extends WUZHI_foreground {

   public function __construct() {
        parent::__construct();
        session_start();
        include_once( 'weixin/wxopen.class.php' );
      }
    //微信登录的地址
   public function weixin_login_url(){
                  $weixin = new class_weixin();
                  $redirect_url = 'http://m.uzhuang.com/weixin_login.html';
                  // qrconnect的第二个参数需要微信申请通过后
                  $jumpurl = $weixin->qrconnect($redirect_url, "snsapi_login", "123");
                  header("Location:$jumpurl");
                 // echo json_encode(array('code'=>1,'data'=>$jumpurl,'message'=>'微信第三方登录地址','process_time'=>time()));
   }
     //微信登录的回调地址
    public function callback(){
         $weixin = new class_weixin();
          if(!isset($GLOBALS['code'])&&empty($GLOBALS['code'])){
             exit('参数非法!');      
          }
            //登录完微信，获取用户的基本信息
               $token = $weixin->oauth2_access_token($GLOBALS["code"]);  
               // echo "<pre>";print_r($token);exit;
               if ($token) {
                    //把返回的code存到session里
                    $_SESSION['token'] = $token;
                    $openid=$_SESSION['token']['openid'];
                    //根据ID获取用户等基本信息start
                     $user_message = $weixin->oauth2_get_user_info($_SESSION['token']['access_token'], $openid);
                     $time1a=date('YmdHi',time());            
                     $time1b=substr($time1a,2);
                    $username='MB'.$time1b.rand(0,9).rand(0,9);
                    
                   $arr=array();
                   $mem=$this->db->get_list('third_login',$where,'openid');
                   foreach ($mem as $key => $value) {
                       $arr[]=$value['openid'];
                   }
                     
                   if(!in_array($openid,$arr)){
                           
                           // 未授权就把用户信息插入到third_login、member表中,也就是注册
                           $res1=array();
                           $res1['uid']='';
                           $res1['openid']=$openid;
                           $res1['login_way']='weixin';
                           $res1['data']=serialize($user_message);
                           $this->db->insert('third_login',$res1);
                           //取最后一次插入的主键id
                           $third_insert_id=$this->db->insert_id();

                           $res=array();
                           $res['username']=$username;
                           $res['nickname']=$user_message['nickname'];
                           if(isset($user_message['headimgurl'])&&!empty($user_message['headimgurl'])){
                            $res['avatar']=$user_message['headimgurl'];
                           }
                           $res['password']=md5(time());
                           $res['modelid']=(int)10;
                           $res['lasttime']=date('Y-m-d H:i:s',time());
                           $res['mobile_station']='weixin';
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
                          //授权后，查询、更新，请求登陆
                        $r1 = $this->db->get_one('third_login', '`openid`="'.$openid.'"','*');
                        $this->db->update('member',array('lasttime'=>date('Y-m-d H:i:s',time())),'`uid`="'.$r1['uid'].'"'); 
                        //cookie里存入uid
                        $r = $this->db->get_one('member', '`uid`="'.$r1['uid'].'"','*');
                        $this->create_cookie($r,SYS_TIME+604800);
                        // MSG(L("登陆成功"),'http://m.uzhuang.com');
                        
                        echo "<script>alert('登陆成功');location.href='http://m.uzhuang.com'</script>"; 
                        }
                  }else{
                        echo "<script>alert('登陆失败');location.href='http://m.uzhuang.com'</script>"; 
                 }

            

        }
}
