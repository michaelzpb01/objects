<?php
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
}elseif($action=='weixin') {
    weixin();
}
function access_token(){
         $db = load_class('db');
         $appid ='wx7a6e11836803bbbb';
          $appsecret ='da42ecd92b267c9d0835b8dc69a5761e';
          $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
          //https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx7a6e11836803bbbb&secret="da42ecd92b267c9d0835b8dc69a5761e;
          $ch = curl_init();
          curl_setopt($ch , CURLOPT_URL, $url);
          curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
          $res = curl_exec($ch);
          curl_close( $ch );
          $arr = json_decode($res, true);
          $data=array();
          $data['token']=$arr['access_token'];
          $data['time']=time();
          $db->update('m_token',$data,'id=1');
          $token=$db->get_one('m_token','id=1',"*");
          $accessToken=$token['token'];
          return $accessToken;
}
function ticket($accessToken){
      $urls ='https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$accessToken.'&type=jsapi';
      $ca = curl_init();
      curl_setopt($ca, CURLOPT_URL,$urls);
      curl_setopt($ca, CURLOPT_RETURNTRANSFER,1);
      curl_setopt($ca, CURLOPT_SSL_VERIFYPEER, FALSE); 
      curl_setopt($ca, CURLOPT_SSL_VERIFYHOST, FALSE);
      $resd = curl_exec($ca);
      curl_close($ca);
      $arrs = json_decode($resd,true);
      return $ticket  = $arrs['ticket'];
}
function weixin(){
      $type=$GLOBALS['type'];
      $temp=$GLOBALS['temp'];
      $db = load_class('db');
      $token=$db->get_one('m_token','id=1',"*");
      $type=$GLOBALS['type'];
      if($type=='hdmb'){
          $datas=$action=fbform($temp);
      }else if($type=='alzt'){
          $datas=$action=special($temp);
      }else if($type=='gdzb'){
          $datas=$action=day_log($temp);
      }else if($type=='jpal'){
          $datas=$action=jpal($temp);
      }else if($type=='kbgs'){
          $datas=$action=kbgs($temp);
      }else if($type=='sjs'){
          $datas=$action=sjs($temp);
      }
      // else{
      //    $datas=$action=other();
      // }
      $time=time()-$token['time'];
      $temp=$GLOBALS['temp'];
      if($time>=7200){
          $actions=access_token();
      }else{
          $accessToken=$token['token'];
      }
      $ticket= $actions=ticket($accessToken);
      if(empty($ticket)){
        $actions=access_token();
        $ticket= $actions=ticket($accessToken);
      }
        $url=$GLOBALS['url'];
      $arrs = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
      $arr_len = count($arrs);
      for ($i = 0; $i < 30; $i++)
      {
          $rand = mt_rand(0, $arr_len-1);
          $noncestr.=$arrs[$rand];
      }
      $timestamp=time();
      $string='jsapi_ticket='.$ticket.'&noncestr='.$noncestr.'&timestamp='.$timestamp.'&url='.$url;
      $signature=sha1($string);
        $data=array(
             'signature'=>$signature,//签名
             'timestamp'=>$timestamp,//时间戳
             'appid' =>'wx7a6e11836803bbbb',//公众号
             'noncestr'=>$noncestr,//随机字符串
             'ticket'=>$ticket,
             'url'=>$url,
             'token'=>$accessToken,
             'string'=>$string,
             'urls'=>$urls,
             'share'=>$datas['share'],
             'sharename'=>$datas['sharename'],
             'sharedescribe'=>$datas['sharedescribe']
          );
       echo  json_encode(array('code'=>1,'data'=>$data,'process_time'=>time()));
}
function fbform($temp) {
   $db = load_class('db');
   $result=$db->get_one('m_exercise','id='.$temp,"*");
   $datas=array(
    'share'=>getMImgShow($result['share'],'original'),//分享图标
    'sharename'=>$result['sharename'],//分享标题
    'sharedescribe'=> $result['sharedescribe'],//分享的描述
    );
    return $datas;
}
function special($temp) {
   $config=get_config('wx_config');
   $db = load_class('db');
   $result=$db->get_one('m_p_special','id='.$temp,"*");
   $datas=array(
    'share'=>$config['1']['img'],//分享图标
    'sharename'=>$result['title'],//分享标题
    'sharedescribe'=>  mb_substr($result['dao'],0,34*2,'utf-8'),//分享的描述
    );
    return $datas;
}
function day_log($temp){
   $config=get_config('wx_config');
   $db = load_class('db');
   $result=$db->get_one('day_log','orderid='.$temp,"*",0,'nodeid desc');
   $results=$db->get_one('day_log','orderid='.$temp,"*",0,'nodeid asc');
   $img=$db->get_one('member_hk_data','uid='.$result['userid']);
   $name=$results['title'].'--'.$result['nodename'];
   $datas=array(
    'share'=>'http://www.uzhuang.com/image/small_square/'.$img['personalphoto'],//分享图标
    'sharename'=>$name,//分享标题
    'sharedescribe'=> $config['6']['con'],//分享的描述
    );
    return $datas;
} 

function jpal($temp){
   $config=get_config('wx_config');
   $db = load_class('db');
   $result=$db->get_one('m_picture','id='.$temp,"*");
   $alt=explode('|',$result['alt']);
   $aRowid = $aTemp = array();
    $space = explode('|', $result['space']);
    $alt = explode('|', $result['alt']);
    foreach($alt as $key => $value){
        $aTemp[$space[$key]][] = array(
          
          'alt' => $value,
          'space' => $space[$key],
        );
    }
    ksort($aTemp,SORT_NUMERIC);
    foreach($aTemp as $item){
        foreach($item as $citem){
            $aRowid[] = $citem;
         }
     }
   $datas=array(
     'share'=>$config['4']['img'],//分享图标
     'sharename'=>$result['name'],//分享标题
     'sharedescribe'=> mb_substr($aRowid[0]['alt'],0,34*2,'utf-8'),//分享的描述
    );
    return $datas;
}
function kbgs($temp){
   $db = load_class('db');
   $config=get_config('wx_config');
   $result=$db->get_one('m_company','id='.$temp,"*");
   $company=$db->get_one('company_data','id='.$temp,"*");
   $content=str_ireplace("&nbsp;"," ",strip_tags($company['content']));
   $datas=array(
      'share'=>$config['5']['img'],//分享图标
      'sharename'=>$result['title'],//分享标题
      'sharedescribe'=> mb_substr(trim($content),0,34*2,'utf-8'),//分享的描述
    );
    return $datas;
}
function sjs($temp){
   $db = load_class('db');
   $result=$db->get_one('m_company_team','id='.$temp,"*");
   $results=$db->get_one('m_company','id='.$result['companyid'],"*");
   $temp=$db->get_one('company_team_data','id='.$temp,"*");
   if(!empty($result['thumb1'])){
     $thumb=getMImgShow($result['thumb1'],'big_square');
   }else{
     $thumb="http://image1.uzhuang.com/image/big_square/".$result['thumb']; 
   }
   $temp=str_ireplace("&nbsp;"," ",strip_tags($temp['content']));
   $datas=array(
     'share'=>$thumb,
     'sharename'=>$result['title'].'-'.$result['ranks'].'-'.$results['title'],//分享标题
     'sharedescribe'=> mb_substr(trim($temp),0,34*2,'utf-8'),//分享的描述
    );
    return $datas;
}
// function other() {
//   $config=get_config('wx_config');
//    $datas=array(
//     'sharename'=>'【优装美家——管家式装修服务 有品质的低价】',//分享标题
//     'sharedescribe'=>$config['3']['con'],//分享的描述
//     );
//     return $datas;
// }
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