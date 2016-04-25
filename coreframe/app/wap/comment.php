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

class comment extends WUZHI_foreground {
        //用户评价
       public function biz_comment(){
                 $orderid=$GLOBALS['orderid'];
               // 订单信息
                 $order=$this->db->get_one("demand","`id`= '".$orderid."'",'id,title,managerid,managername,uid,sex');
               //管家信息
                 $guanjia=array();//存用户对管家的评价信息
                 $guan=$this->db->get_one('member_hk_data',"`uid`='".$order['managerid']."'",'personalphoto,gjname');
                 $guanjia_manyi=$this->db->get_one('gjdianping',"`keyid` like '%".$orderid."%'",'myd,addtime,data');
                 $guanjia['satisfaction']=$guanjia_manyi['myd'];
                 $guanjia['addtime']=date('Y-m-d H:i:s',$guanjia_manyi['addtime']);
                 $guanjia['data']=$guanjia_manyi['data'];
              
                $companyarr=array();//存用户对装修公司的评价信息
              // 用户点评
                 $user_dianping=$this->db->get_one('dianping',"`order_no` like '%".$orderid."%'",'order_no,addtime,data,company_id,replydata,replydata_time,appdata,field1,field2,field3');
              // 公司信息
                 $company_logo=$this->db->get_one('m_company',"`id`='".$user_dianping['company_id']."'","title,thumb,avg_total");
                 $companyarr['total']=round(($user_dianping['field1']+$user_dianping['field2']+$user_dianping['field3'])/3);
                 $companyarr['addtime']=date('Y-m-d H:i:s',$user_dianping['addtime']);
                 $companyarr['data']=$user_dianping['data'];
                 $companyarr['replydata']=$user_dianping['replydata'];
                 $companyarr['appdata']=$user_dianping['appdata'];

                 $arr=array(
                    'guanjia'=>$guanjia,
                    'company'=>$companyarr,
                  );
                  // echo '<pre>';print_r($arr);exit;
                  echo json_encode(array('code'=>1,'data'=>$arr,'message'=>'工地直播用户的评价','process_time'=>time())); 
       }

}



