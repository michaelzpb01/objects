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
} elseif($action=='fbcity'){
    fbcity();
}

function fbform() {
    $db = load_class('db');
    if(empty($GLOBALS['title'])) {
        send(false,null,'请填写联系人');
    }
    if(empty($GLOBALS['telephone'])) {
        send(false,null,'请填写电话');
    }
     if(empty($GLOBALS['select'])) {
        send(false,null,'省份不能为空');
    } 
    if(empty($GLOBALS['select_1'])) {
        send(false,null,'城区不能为空');
    }
    // $city=$db->get_one('linkage_data',"lid='".$GLOBALS['select_1']."'","*");
    // if($city['pid']!=$GLOBALS['select']){
    //   send(false,null,'你选择的城市与城区不符');
    // }
    if(!preg_match('/^(?:13\d{9}|15[0|1|2|3|5|6|7|8|9]\d{8}|17[0|1|2|3|5|6|7|8|9]\d{8}|19[9]\d{8}|18[0|1|2|3|5|6|7|8|9]\d{8}|14[5|7]\d{8})$/',$GLOBALS['telephone'])) {
         send(false,null,'电话号码错误！');
        }
  //  $db = load_class('db');
    $telephone = remove_xss($GLOBALS['telephone']);
    $member = $db->get_one('member',array('mobile'=>$telephone));
    $formdata = array();
    $formdata['title'] = remove_xss($GLOBALS['title']);
    $formdata['telephone'] = remove_xss($GLOBALS['telephone']);
    $formdata['addtime'] = date('Y-m-d H:i:s',SYS_TIME);
    $formdata['source'] = remove_xss($GLOBALS['source']);
    $formdata['cid'] = 135;
    $formdata['status'] = 1;
    $formdata['areaid_1'] = remove_xss($GLOBALS['select']);
    $formdata['areaid_2'] = remove_xss($GLOBALS['select_1']);
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

function fbcount(){
    $db = load_class('db');
    $count = $db->count('demand');
    echo $count['num']+1000;
    exit;
}

function fbcity(){
    $db = load_class('db');
    $city = $GLOBALS['select'];
  
    $where = empty($city)?'pid in ("'.implode('","',array('2','3','4','5','34','35','0')).'") and pid !=2 and pid !=3 and pid !=4 and pid !=5 and pid !=34 and pid !=35':'pid="'.$city.'"';
    $info = array();
    foreach($db->get_list('linkage_data',$where,"lid,name") as $item){
        if(!in_array($item['lid'],$pcity))$info[] = $item;
    }
    // echo '<pre>';print_r($info);
   
    send(true,$info,'获取城市信息成功');
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
