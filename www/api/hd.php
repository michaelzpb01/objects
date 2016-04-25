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



function fbform() {
      $db = load_class('db');
      $city = $GLOBALS['select'];
      $result = $db->get_one('m_exercise',"id=".$GLOBALS['temp'],'*');
      $aRowid = $aTemp = array();
      $headpiece = explode('|',$result['headpiece']);
      $bian = explode('|',$result['bian']);
      $urlsx = explode('|',$result['headpieceurl']);
      foreach($headpiece as $key => $value){
          $aTemp[$bian[$key]][] = array(
            'url' => $value,
            'bian' => $bian[$key],
            'urlsx' => $urlsx[$key],
          );
      }
      ksort($aTemp,SORT_NUMERIC);
      foreach($aTemp as $item){
          foreach($item as $citem){
              $aRowid[] = $citem;
          }
      }
      $arr=array();
      foreach($aRowid as $Previe){
        if($Previe['url']!=''){
            $arr['url'][]=getMImgShow($Previe['url'],'original');
            $arr['urlsx'][]=$Previe['urlsx'];
        }
      }

      $aRowids = $aTemps = array();
      $headpiece = explode('|',$result['troops']);
      $bians = explode('|',$result['bians']);
      $urlsh = explode('|',$result['troopsurl']);
      foreach($headpiece as $key => $value){
          $aTemps[$bians[$key]][] = array(
            'url' => $value,
            'bians' => $bians[$key],
            'urlsh' => $urlsh[$key],
          );
      }
      ksort($aTemps,SORT_NUMERIC);
      foreach($aTemps as $item){
          foreach($item as $citem){
              $aRowids[] = $citem;
          }
      }
      $troops=array();
    foreach($aRowids as $Previes){
        if($Previes['url']!=''){
        $troops['url'][]=getMImgShow($Previes['url'],'original');
        $troops['urlsh'][]=$Previes['urlsh'];
      }
    }
    if($result['city']==1)
    { 
      $info=array(
        array(
            "lid"=>'3360',
            'name'=>'北京市',
          ),
         array(
            "lid"=>'3362',
            'name'=>'天津市',
          ),
          array(
            "lid"=>'328',
            'name'=>'深圳市',
          ),
           array(
            "lid"=>'326',
            'name'=>'广州市',
          ),
            array(
            "lid"=>'3361',
            'name'=>'上海市',
          ),
             array(
            "lid"=>'435',
            'name'=>'西安市',
          ),
              array(
            "lid"=>'213',
            'name'=>'杭州市',
          ),
              array(
            "lid"=>'200',
            'name'=>'南京市',
          ),
      );
       // $info=$db->get_list('linkage_data','kai="kai" and pid !=""',"lid,name");
    }else if($result['city']==2){
    $where = empty($city)?'pid in ("'.implode('","',array('2','3','4','5','34','35','0')).'") and pid !=2 and pid !=3 and pid !=4 and pid !=5 and pid !=34 and pid !=35':'pid="'.$city.'"';
    $info = array();
    foreach($db->get_list('linkage_data',$where,"lid,name") as $item){
         $info[] = $item;
     }
    }
     $str=R;$newstr = substr($str,0,strlen($str)-5);
     $url= $newstr.'/mobile-template.html?temp='.$result['id'];
   $data=array(
    'title'=> $result['name'],
    'type'=> $result['type'],//模板类型 
    'button'=> $result['button'],
    'status'=> $result['status'],
    'city'=> $result['city'],
    'status_4'=> $result['status_4'],
    'color'=> $result['color'],
    'color1'=> $result['color1'],
    'color2'=> $result['color2'],
    'color3'=> $result['color3'],
    'background'=>getMImgShow($result['background'],'original'),
    'headpiece'=>$arr['url'],
    'headpieceurl'=>$arr['urlsx'],
    'city1'=> $info,
    'share'=>$result['share'],//分享图标
    'sharename'=>$result['sharename'],//分享标题
    'sharedescribe'=> $result['sharedescribe'],//分享的描述
    'url'=>$url,//分享的url
    );
   if($result['type']!=2){
     $datas=array(
        'status_1'=> $result['status_1'],
        'status_2'=> $result['status_2'],
        'status_3'=> $result['status_3'],
        'troops'=>$troops['url'],
        'troopsurl'=>$troops['urlsh'],
      );
     $data = array_merge($data, $datas); 
   }
     echo json_encode(array('code'=>1,'data'=>$data,'process_time'=>time()));
}
 function fbcount(){
    $auth = get_cookie('auth');
    $auth_key = substr(md5(_KEY), 8, 8);
    list($uid, $password, $cookietime) = explode("\t", decode($auth, $auth_key));
    $xUan=$GLOBALS['city'];
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
    if($xUan==1){
      if(empty($GLOBALS['areaid'])) {
        send(false,null,'请填写城市');
     }
    }
     if($xUan==2){
     if(empty($GLOBALS['select'])) {
        send(false,null,'省份不能为空');
    } 
      if(empty($GLOBALS['select_1'])) {
        send(false,null,'城区不能为空');
    }
  }
    if(!preg_match('/^(?:13\d{9}|15[0|1|2|3|5|6|7|8|9]\d{8}|17[0|1|2|3|5|6|7|8|9]\d{8}|19[9]\d{8}|18[0|1|2|3|5|6|7|8|9]\d{8}|14[5|7]\d{8})$/',$GLOBALS['telephone'])) {
         send(false,null,'电话号码填写错误');
        }
        if($xUan=='1'){
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
        }else if($xUan=='2'){
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
      }
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