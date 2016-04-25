<?php
/**
 * uzhuang 商城会员同步
 */
header('Content-type:text/html;charset=utf-8');
header("Access-Control-Allow-Origin: *");
define('WWW_ROOT',substr(dirname(__FILE__), 0, -4).'/');
require '../configs/web_config.php';
require COREFRAME_ROOT.'core.php';

$action = $GLOBALS['action'];
if($action=='fbform') {
    fbform();
} elseif($action=='fbcount') {
    fbcount();
}elseif($action=='fbcity'){
    fbcity();
}

function fbform() {
      $auth = get_cookie('auth');
    $auth_key = substr(md5(_KEY), 8, 8);
    list($uid, $password, $cookietime) = explode("\t", decode($auth, $auth_key));
    $db = load_class('db');
    if(!empty($uid)){
        $member_info = $db->get_list('member', "`uid`='$uid'", 'username,uid');
        $username = $member_info[0]['username'];
        $uid = $member_info[0]['uid'];
    }
    if(empty($GLOBALS['title'])) {
        send(false,null,'请填写联系人');
    }
    
    if(empty($GLOBALS['telephone'])) {
        send(false,null,'请填写电话');
    }
    if(empty($GLOBALS['areaid'])) {
        send(false,null,'请填写城市');
    }
    if(!preg_match('/^(?:13\d{9}|15[0|1|2|3|5|6|7|8|9]\d{8}|17[0|1|2|3|5|6|7|8|9]\d{8}|19[9]\d{8}|18[0|1|2|3|5|6|7|8|9]\d{8}|14[5|7]\d{8})$/',$GLOBALS['telephone'])) {
         send(false,null,'电话号码填写错误');
        }
    $province = $db->get_one('linkage_data', "`lid`='".$GLOBALS['areaid']."'", 'lid,pid');
    $provinces = $db->get_one('linkage_data', "`lid`='".$province['pid']."'", 'lid');
    $telephone = remove_xss($GLOBALS['telephone']);
    $member = $db->get_one('member',array('mobile'=>$telephone));
    $formdata = array();
    $formdata['title'] = remove_xss($GLOBALS['title']);
    $formdata['telephone'] = remove_xss($GLOBALS['telephone']);
    $formdata['addtime'] = date('Y-m-d H:i:s',SYS_TIME);
    $formdata['source'] = remove_xss($GLOBALS['source']);
    $formdata['cid'] = 135;
    $formdata['status'] = 1;
    $formdata['areaid_1'] =$provinces['lid'] ;
    $formdata['areaid_2'] =remove_xss($GLOBALS['areaid']) ;
    $formdata['publisher'] = $username;
    $formdata['uid'] = $uid;
    $data = $db->get_one('demand','telephone='.$formdata['telephone'],'addtime',0,'addtime desc');
    if(!empty($data['addtime']) && (strtotime($formdata['addtime'])-strtotime($data['addtime']))<12*3600){
        send(false,null,'您已成功报名过，12小时内只允许提交一次！');
    }
    $id = $db->insert('demand',$formdata);
    $order_no = '1'.str_pad($id,9,0,STR_PAD_LEFT);
    $db->update('demand',array('order_no'=>$order_no),array('id'=>$id));
    $d1 = date('Y-m-d',SYS_TIME).' 8:00:01';
    $d2 = date('Y-m-d',SYS_TIME).' 18:00:01';
    $d3 = date('Y-m-d',SYS_TIME).' 23:59:59';
    $d1 = strtotime($d1);
    $d2 = strtotime($d2);
    $d3 = strtotime($d3);
    if(SYS_TIME<$d1) {
        send(true,null,'您已成功报名，客服今日12点前联系您！');
    } elseif(SYS_TIME<$d2) {
        send(true,null,'您已成功报名，客服将在2小时内联系您！');
    } else {
        send(true,null,'您已成功报名，客服明日12点前联系您！');
    }

}
function fbcity(){
     $info=array( 
        array( 
        name=>'北京',
        lib=>'3360',
        ) ,
        array( 
        name=>'天津',
        lib=>'3362',
        ) ,
        array( 
        name=>'广州',
        lib=>'326',
        ),
        array( 
        name=>'深圳',
        lib=>'328',
        ),
        array( 
        name=>'上海',
        lib=>'3361',
        ),
        array( 
        name=>'杭州',
        lib=>'213',
        ),
        array( 
        name=>'西安',
        lib=>'435',
        ),
        array( 
        name=>'南京',
        lib=>'200',
       )
     );
    send(true,$info,'获取城市信息成功');
}

function fbcount(){
    $db = load_class('db');
    $count = $db->count('demand');
    echo $count['num']+1000;
    exit;
}

function send($status,$data,$msg){
    if(!$status){
        $data =null;
    }

    $return_data = array(
        'flag' =>$status,
        'msg' => $msg,
        'data'=>$data,
        'time' => time(),
    );
    header('Content-Type:text/jcmd; charset=utf-8');
    echo  json_encode($return_data);
    exit;
}
