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
if($action=='me') {
    me();
} 
  function me() {
    $db = load_class('db');
    $imgs = $db->get_one('m_contents_img',$HONG,'*',0);
    $result = $db->get_list('m_contents','status!=5','*',0,10,$page,'updatetime desc');
     $results = array();
      foreach($result as $key=> $value){
       $results[$key]['title']=$value['title'];
       $results[$key]['content']=$value['content'];
       $results[$key]['addtime']=date('Y-m-d H:i:s',$value['addtime']);
       $results[$key]['img']=getMImgShow($value['img'],'original');
      }
     $is=explode('|',$imgs['imgs']);
     $data=array();
     foreach($is as $keys =>$va){
      $data[$keys]['imgs']= getMImgShow($va,'original')."<br/>";
     }
     echo json_encode(array('code'=>1,'data'=>$data,'results'=>$results,'process_time'=>time()));
  }
