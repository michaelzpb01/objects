<?php
// +----------------------------------------------------------------------
// | wuzhicms [ ÎåÖ¸»¥ÁªÍøÕ¾ÄÚÈÝ¹ÜÀíÏµÍ³ ]
// | Copyright (c) 2014-2015 http://www.wuzhicms.com All rights reserved.
// | Licensed ( http://www.wuzhicms.com/licenses/ )
// | Author: wangcanjia <phpip@qq.com>
// +----------------------------------------------------------------------
header("Access-Control-Allow-Origin: *");
defined('IN_WZ') or exit('No direct script access allowed');
/**
 * Í¼¿â
 */
load_class('foreground', 'member');
class order_details extends WUZHI_foreground {

   public function listing_details(){
   					//获取前台传的订单号
                   $order_no=$GLOBALS['order_no'];
                   $demand=$this->db->get_one('demand',"`order_no`=$order_no",'id,address,homestyle,housetype,renovationcategory,area,way,style,housekeeperid,nodeid,designpay,totalpay,designno,contactno,addtime,updatetime');
                        //循环出风格的索引,找对应的中文
                         $configs_picture = get_config('picture_config');
                         $pp=trim($demand['style'],',');
                         $ss=explode(',',$pp);
                           for($a=0;$a<count($ss);$a++){
                               $style.=$configs_picture['style'][$ss[$a]].'&nbsp;';
                           }
                            $demand['style']=$style;
                        
                           $fs=trim($demand['way'],',');
                          $fs1=explode(',',$fs);
                            for($j=0;$j<count($fs1);$j++){
                              $demand['way']=$configs_picture['way'][$fs1[$j]];
                           }
                   
                         if($demand['renovationcategory']=='1'){
                           $demand['homestyle']= $configs_picture['homestyle'][$demand['homestyle']];
                           //订单数组
                           $dingdanxinxi=array($demand['address'],$demand['homestyle'],$demand['area'],$demand['style'],$demand['way']);
                        }elseif( $demand['renovationcategory']=='2'){
                        $demand['housetype']=$configs_picture['housetype'][$demand['housetype']];
                         //订单数组
                          $dingdanxinxi=array($demand['address'],$demand['housetype'],$demand['area'],$demand['style'],$demand['way']);
                         } 
                    
                   // 订单id
                   $id=$demand['id'];
                   // 管家id
                   $guajiaId=$demand['housekeeperid'];
                   // 节点id
                   $nodeid=$demand['nodeid'];  
                   //三家公司的评分
                   $companypingfen1=array();
                   // 上门量房的状态
                   $shangmenstr=array();
                   //给前台传当前订单的所有节点
                   $arr=array();
                   //给前台传当前订单的所有节点名称
                   $arr1=array();
                   $demand_track=$this->db->get_list('demand_track',"`orderid`=$id",'orderid,nodename,nodeid'); 
                   $c=count($demand_track);
                   for($i=0;$i<$c;$i++){
                     $arr[]=$demand_track[$i]['nodeid'];
                   } 
                   foreach ($demand_track as $key => $value) {
                    
                           $arr1[]=array($value['nodename']);
                   }
                   //订单最新进度的节点名称
                   $demand_track1=$this->db->get_one('demand_track',"`nodeid`=$nodeid AND `orderid`=$id",'nodename');  
                //装修订单审核
                $shenhe=array();
                   if($demand){
                     $time1a=date('Y-m-d',strtotime($demand['addtime']));            
                        $time1b=explode('-',$time1a);
                        $time1c=$time1b[0].'.'.$time1b[1].'.'.$time1b[2];
                   $shenhe=array('shijian'=>$time1c,'nodename'=>'装修订单审核','message'=>"您的装修申请已经提交，客服会在24小时内与您联系！");
                   }
                   if($nodeid>1||$nodeid=1){
                    //取节点时间、名称
                        
                        $time1a=date('Y-m-d',strtotime($demand['addtime']));            
                        $time1b=explode('-',$time1a);
                        $time1c=$time1b[0].'.'.$time1b[1].'.'.$time1b[2];
                   $shenhe=array('shijian'=>$time1c,'nodename'=>'装修订单审核','message'=>"恭喜您，您的装修申请已经通过审核!");
                   }
                //为您精选3家装修公司
                   if($nodeid>2||$nodeid==2){
                      //取节点时间、名称
                      // $time2=$this->db->get_one('demand_track_wp',"`orderid`=$id AND `nodeid`= 2",'date1,nodename'); 

                      $threecompany=$this->db->get_list('demand_company',array('orderid'=>$id),'companyname,companyid');
                      foreach ($threecompany as $key => $value){
                      	  $uid=$value['companyid'];
                        	$companypingfen=$this->db->get_one('company',"`id`=$uid",'avg_total');
                        	$companypingfen1[]=array($value['companyname']=>$companypingfen['avg_total']);
                        }  
                        $time2a=date('Y-m-d',strtotime($demand['addtime']));            
                        $time2b=explode('-',$time2a);
                        $time2c=$time2b[0].'.'.$time2b[1].'.'.$time2b[2];
                        $companyarr=array('shijian'=>$time2c,'nodename'=>'为您精选3家装修公司','threecompany'=>$companypingfen1);
                   }
               // 为您指定管家
                   if($nodeid>9||$nodeid==9){
                    //取节点时间、名称
                    // $time9=$this->db->get_one('demand_track_wp',"`orderid`=$id AND `nodeid`= 9",'date1,nodename'); 
               
                    $guanjia=$this->db->get_one('member_hk_data',"`uid`= $guajiaId",'gjname,personalphoto,mobile');
                     $time9a=date('Y-m-d',strtotime($demand['updatetime']));            
                     $time9b=explode('-',$time9a);
                    $time9c=$time9b[0].'.'.$time9b[1].'.'.$time9b[2];

                    $guanjiaarr=array('shijian'=>$time9c,'nodename'=>'为您指定管家','guanjia'=>$guanjia);
                   }
               // 上门量房
                   if($nodeid>11||$nodeid==11){
                    //取节点时间、名称
                   $time11=$this->db->get_one('demand_track',"`orderid`=$id AND `nodeid`= 11",'date1,nodename'); 

                   $shangmen=$this->db->get_list('demand_track_detail',"`orderid`=$id AND `nodeid`= 11",'col2,col7,col8');
                   foreach ($shangmen as $key => $value) {
                                  	if($value['col8']=='是'){
                                      $shijian=date('Y-m-d',strtotime($value['col7']));            
                                      $shijian1=explode('-',$shijian);
                                      $shangstr=$shijian1[0].'年'.$shijian1[1].'月'.$shijian1[2].'日,已量房';
                                      }else{
                                        $shangstr='未量房';
                                      }
                                     $shangmenstr[]=array('company'=>$value['col2'],'beizhu'=>$shangstr);
                                  }  
                                  $time11a=date('Y-m-d',strtotime($time11['date1']));            
                                 $time11b=explode('-',$time11a);
                                $time11c=$time11b[0].'.'.$time11b[1].'.'.$time11b[2];
                                  
                          $liangfangarr=array('shijian'=>$time11c,'nodename'=>$time11['nodename'],'message'=>$shangmenstr);
                          // var_dump($liangfang);
                   }
              //选定装修公司
                   if($nodeid>13||$nodeid==13){
                     //取节点时间、名称
                    $time13=$this->db->get_one('demand_track',"`orderid`=$id AND `nodeid`= 13",'date1,nodename'); 

                    $confirmcompany=$this->db->get_one('demand_track_detail',"`orderid`=$id AND `nodeid`= 13",'col2');

                                 $time13a=date('Y-m-d',strtotime($time13['date1']));            
                                 $time13b=explode('-',$time13a);
                                $time13c=$time13b[0].'.'.$time13b[1].'.'.$time13b[2];
                    $confirmcompanyarr=array('shijian'=>$time13c,'nodename'=>$time13['nodename'],'company'=>$confirmcompany['col2']);
                    // var_dump($confirmcompany);
                   }
              // 签订设计协议/意向定金
                if($nodeid>15||$nodeid==15){
                     //取节点时间、名称
                    $time15=$this->db->get_one('demand_track',"`orderid`=$id AND `nodeid`= 15",'date1,nodename'); 
                                $time15a=date('Y-m-d',strtotime($time15['date1']));            
                                 $time15b=explode('-',$time15a);
                                $time15c=$time15b[0].'.'.$time15b[1].'.'.$time15b[2];
                    $xieyi=$this->db->get_one('demand_referno',"`orderid`=$id AND `nodeid`= 15",'nodename,needmoney'); 
                    if($xieyi['nodename']=='意向定金'){

                      $xieyiarr=array('shijian'=>$time15c,'nodename'=>$time15['nodename'],'beizhu'=>"签订意向定金协议",'jine'=>"已付款".$xieyi['needmoney']."元");
                    }
                     if($xieyi['nodename']=='签订设计协议'){
                      $xieyiarr=array('shijian'=>$time15c,'nodename'=>$time15['nodename'],'beizhu'=>"签订设计协议",'bianhao'=>"协议编号:".$demand['designno'],'jine'=>"已付款".$demand['designpay']."元");
                    }
                  // var_dump($xieyiarr);
                }
            // 方案确定预交底
                if($nodeid>17||$nodeid==17){
                   //取节点时间、名称、备注
                    $time17=$this->db->get_one('demand_track',"`orderid`=$id AND `nodeid`= 17",'date1,nodename,remark'); 
                                $time17a=date('Y-m-d',strtotime($time17['date1']));            
                                 $time17b=explode('-',$time17a);
                                $time17c=$time17b[0].'.'.$time17b[1].'.'.$time17b[2];
                    $yujiaodiarr=array('shijian'=>$time17c,'nodename'=>$time17['nodename'],'beizhu'=>$time17['remark']);
                }
           // 签施工协议
                if($nodeid>19||$nodeid==19){
                   //取节点时间、名称
                    $time19=$this->db->get_one('demand_track',"`orderid`=$id AND `nodeid`= 19",'date1,nodename'); 
                                $time19a=date('Y-m-d',strtotime($time19['date1']));            
                                 $time19b=explode('-',$time19a);
                                $time19c=$time19b[0].'.'.$time19b[1].'.'.$time19b[2];
                    $xieyi=$this->db->get_one('demand_referno',"`orderid`=$id AND `nodeid`= 15",'nodename,needmoney'); 
                     $xieyi1=$this->db->get_one('demand_referno',"`orderid`=$id AND `nodeid`= 19",'nodename,needmoney'); 
                    if($xieyi['nodename']=='意向定金'){
                      $shigongarr=array('shijian'=>$time19c,'nodename'=>$time19['nodename'],'bianhao'=>"协议编号:".$demand['contactno'],'jine'=>"协议金额:".$demand['totalpay'],'shijifukuan'=>"已付40%工程款:实际付款".$xieyi1['needmoney']."元(".$xieyi['needmoney']."元定金抵充)");
                    }
                     if($xieyi['nodename']=='签订设计协议'){
                       $shigongarr=array('shijian'=>$time19c,'nodename'=>$time19['nodename'],'bianhao'=>"协议编号:".$demand['contactno'],'jine'=>"协议金额:".$demand['totalpay'],'shijifukuan'=>"已付40%工程款:实际付款".$xieyi1['needmoney']."元");
                    }
                }
                
           // 工程开工
                if($nodeid>21||$nodeid==21){
                     //取节点时间、名称、备注
                    $time21=$this->db->get_one('demand_track',"`orderid`=$id AND `nodeid`= 21",'date1,nodename,remark'); 
                                 $time21a=date('Y-m-d',strtotime($time21['date1']));            
                                 $time21b=explode('-',$time21a);
                                $time21c=$time21b[0].'.'.$time21b[1].'.'.$time21b[2];
                    $gongchengarr=array('shijian'=>$time21c,'nodename'=>$time21['nodename'],'beizhu'=>$time21['remark']);
                }
            // 拆改
                // if(in_array('23', $arr)){
                //      //取节点时间、名称、备注
                //     $time23=$this->db->get_one('demand_track',"`orderid`=$id AND `nodeid`= 23",'date1,nodename,remark');  
                // }else{
                //   $time23=array('拆改'=>'null');
                // }
                 if($nodeid>23||$nodeid==23){
                     //取节点时间、名称、备注
                    $time23=$this->db->get_one('demand_track',"`orderid`=$id AND `nodeid`= 23",'date1,nodename,remark'); 
                                 $time23a=date('Y-m-d',strtotime($time23['date1']));            
                                 $time23b=explode('-',$time23a);
                                $time23c=$time23b[0].'.'.$time23b[1].'.'.$time23b[2];
                    $chaigaiarr=array('shijian'=>$time23c,'nodename'=>$time23['nodename'],'beizhu'=>$time23['remark']);
                }
            // 水电材料验收
                 if($nodeid>25||$nodeid==25){
                     //取节点时间、名称、备注
                    $time25=$this->db->get_one('demand_track',"`orderid`=$id AND `nodeid`= 25",'date1,nodename,remark'); 
                                $time25a=date('Y-m-d',strtotime($time25['date1']));            
                                 $time25b=explode('-',$time25a);
                                $time25c=$time25b[0].'.'.$time25b[1].'.'.$time25b[2];
                    $shuidianclarr=array('shijian'=>$time25c,'nodename'=>$time25['nodename'],'beizhu'=>$time25['remark']);
                }
           // 泥木材料验收
                 if($nodeid>29||$nodeid==29){
                     //取节点时间、名称、备注
                    $time29=$this->db->get_one('demand_track',"`orderid`=$id AND `nodeid`= 29",'date1,nodename,remark'); 
                                $time29a=date('Y-m-d',strtotime($time29['date1']));            
                                 $time29b=explode('-',$time29a);
                                $time29c=$time29b[0].'.'.$time29b[1].'.'.$time29b[2];
                    $nimuclarr=array('shijian'=>$time29c,'nodename'=>$time29['nodename'],'beizhu'=>$time29['remark']);
                }
            // 油漆材料验收
                 if($nodeid>33||$nodeid==33){
                     //取节点时间、名称、备注
                    $time33=$this->db->get_one('demand_track',"`orderid`=$id AND `nodeid`= 33",'date1,nodename,remark'); 
                                 $time33a=date('Y-m-d',strtotime($time33['date1']));            
                                 $time33b=explode('-',$time33a);
                                $time33c=$time33b[0].'.'.$time33b[1].'.'.$time33b[2];
                    $youqiclarr=array('shijian'=>$time33c,'nodename'=>$time33['nodename'],'beizhu'=>$time33['remark']);
                }
           // 水电验收
                 if($nodeid>27||$nodeid==27){
                     //取节点时间、名称、备注
                    $time27=$this->db->get_one('demand_track',"`orderid`=$id AND `nodeid`= 27",'date1,nodename,remark'); 
                                  $time27a=date('Y-m-d',strtotime($time27['date1']));            
                                 $time27b=explode('-',$time27a);
                                $time27c=$time27b[0].'.'.$time27b[1].'.'.$time27b[2];
                     $shuidianday=$this->db->get_one('demand_referno',"`orderid`=$id AND `nodeid`= 27",'nodename,needmoney'); 
                      $shuidianarr=array('shijian'=>$time27c,'nodename'=>$time27['nodename'],'beizhu'=>"验收通过",'jine'=>"已付20%工程款,实收".$shuidianday['needmoney']."元");
                }
          // 泥木验收
                 if($nodeid>31||$nodeid==31){
                     //取节点时间、名称、备注
                    $time31=$this->db->get_one('demand_track',"`orderid`=$id AND `nodeid`= 31",'date1,nodename,remark'); 
                                  $time31a=date('Y-m-d',strtotime($time31['date1']));            
                                 $time31b=explode('-',$time31a);
                                $time31c=$time31b[0].'.'.$time31b[1].'.'.$time31b[2];
                     $nimuday=$this->db->get_one('demand_referno',"`orderid`=$id AND `nodeid`= 31",'nodename,needmoney'); 
                    // $nimu=array("验收通过","已付20%工程款,实收".$nimuday['needmoney']."元");
                     $nimuarr=array('shijian'=>$time31c,'nodename'=>$time31['nodename'],'beizhu'=>"验收通过",'jine'=>"已付20%工程款,实收".$nimuday['needmoney']."元");
                }
          // 油漆验收
                 if($nodeid>35||$nodeid==35){
                     //取节点时间、名称、备注
                    $time35=$this->db->get_one('demand_track',"`orderid`=$id AND `nodeid`= 35",'date1,nodename,remark'); 
                                 $time35a=date('Y-m-d',strtotime($time35['date1']));            
                                 $time35b=explode('-',$time35a);
                                $time35c=$time35b[0].'.'.$time35b[1].'.'.$time35b[2];
                     $youqiday=$this->db->get_one('demand_referno',"`orderid`=$id AND `nodeid`= 35",'nodename,needmoney'); 
                    $youqiarr=array('shijian'=>$time35c,'nodename'=>$time35['nodename'],'beizhu'=>"验收通过",'jine'=>"已付20%工程款,实收".$youqiday['needmoney']."元");
                }
          // 竣工验收
                 if($nodeid>37||$nodeid==37){
                     //取节点时间、名称、备注
                    $time37=$this->db->get_one('demand_track',"`orderid`=$id AND `nodeid`= 37",'date1,nodename,remark'); 
                                 $time37a=date('Y-m-d',strtotime($time37['date1']));            
                                 $time37b=explode('-',$time37a);
                                $time37c=$time37b[0].'.'.$time37b[1].'.'.$time37b[2];
                    $jungongday=$this->db->get_one('demand_referno',"`orderid`=$id AND `nodeid`= 37",'nodename,extrapay'); 
                    $jungongarr=array('shijian'=>$time37c,'nodename'=>$time37['nodename'],'beizhu'=>"验收通过",'zengxiang'=>"已付增项款".$jungongday['extrapay']."元");
                }
        // 竣工污染检测
               if($nodeid>39||$nodeid==39){
                     //取节点时间、名称、备注
                    $time39=$this->db->get_one('demand_track',"`orderid`=$id AND `nodeid`= 39",'date1,nodename,remark'); 
                                 $time39a=date('Y-m-d',strtotime($time39['date1']));            
                                 $time39b=explode('-',$time39a);
                                $time39c=$time39b[0].'.'.$time39b[1].'.'.$time39b[2];
                    $wuranjiancearr=array('shijian'=>$time39c,'nodename'=>$time39['nodename'],'beizhu'=>$time39['remark']);
                }
        // 污染治理
                 if(in_array('41', $arr)){
                     //取节点时间、名称、备注
                    $time41=$this->db->get_one('demand_track',"`orderid`=$id AND `nodeid`= 41",'date1,nodename,remark');
                                 $time41a=date('Y-m-d',strtotime($time41['date1']));            
                                 $time41b=explode('-',$time41a);
                                $time41c=$time41b[0].'.'.$time41b[1].'.'.$time41b[2];  
                    $wuran=$this->db->get_one('demand_extra',"`orderid`=$id AND `nodeid`= 41",'companyname');
                    $wuranarr=array('shijian'=>$time41c,'nodename'=>$time41['nodename'],'company'=>'治理公司:'.$wuran['companyname'],'beizhu'=>$time41['remark']);
                }else{
                    $wuranarr=array('污染治理'=>'null');
                }
          // 复测
                 if(in_array('43', $arr)){
                     //取节点时间、名称、备注
                    $time43=$this->db->get_one('demand_track',"`orderid`=$id AND `nodeid`= 43",'date1,nodename,remark');  
                                 $time43a=date('Y-m-d',strtotime($time43['date1']));            
                                 $time43b=explode('-',$time43a);
                                $time43c=$time43b[0].'.'.$time43b[1].'.'.$time43b[2];  
                             $fuce1arr=array('shijian'=>$time43c,'nodename'=>$time43['nodename'],'beizhu'=>$time43['remark']);     
                }else{
                  $fuce1arr=array('复测1'=>'null');
                }
         // 尾款质保期
               if($nodeid>45||$nodeid==45){
                     //取节点时间、名称、备注
                    $time45=$this->db->get_one('demand_track',"`orderid`=$id AND `nodeid`= 45",'date1,nodename,remark'); 
                                $time45a=date('Y-m-d',strtotime($time45['date1']));            
                                 $time45b=explode('-',$time45a);
                                $time45c=$time45b[0].'.'.$time45b[1].'.'.$time45b[2];  
                             $weikuanarr=array('shijian'=>$time45c,'nodename'=>$time45['nodename'],'beizhu'=>$time45['remark']);  
                }
        // 入住空气检测
                if($nodeid>47||$nodeid==47){
                     //取节点时间、名称、备注
                    $time47=$this->db->get_one('demand_track',"`orderid`=$id AND `nodeid`= 47",'date1,nodename,remark,col4');
                                $time47a=date('Y-m-d',strtotime($time47['date1']));            
                                 $time47b=explode('-',$time47a);
                                $time47c=$time47b[0].'.'.$time47b[1].'.'.$time47b[2];  
                    if($time47['col4']=='已赠送'){
                     $ruzhuarr=array('shijian'=>$time47c,'nodename'=>$time47['nodename'],'beizhu'=>$time47['remark'],'zengsong'=>'赠送：优装美家微空气监测仪一台');
                    } else{
                      $ruzhuarr=array('shijian'=>$time47c,'nodename'=>$time47['nodename'],'beizhu'=>$time47['remark']);
                    }
                }
        // 空气治理
                if(in_array('49', $arr)){
                     //取节点时间、名称、备注
                    $time49=$this->db->get_one('demand_track',"`orderid`=$id AND `nodeid`= 49",'date1,nodename,remark');  
                     $time49a=date('Y-m-d',strtotime($time49['date1']));            
                                 $time49b=explode('-',$time49a);
                                $time49c=$time49b[0].'.'.$time49b[1].'.'.$time49b[2];  
                    $kongqi=$this->db->get_one('demand_extra',"`orderid`=$id AND `nodeid`= 49",'totalmoney,contactno,companyname');
                    $kongqiarr=array('shijian'=>$time49c,'nodename'=>$time49['nodename'],'company'=>'治理公司:'.$kongqi['companyname'],'bianhao'=>'协议编号:'.$kongqi['contactno'],'jine'=>'已付金额:'.$kongqi['totalmoney'].'元','beizhu'=>$time49['remark']);
                }else{
                  $kongqiarr=array('空气治理'=>'null');
                }
      // 复测
                 if(in_array('51', $arr)){
                     //取节点时间、名称、备注
                    $time51=$this->db->get_one('demand_track',"`orderid`=$id AND `nodeid`= 51",'date1,nodename,remark');  
                                $time51a=date('Y-m-d',strtotime($time51['date1']));            
                                 $time51b=explode('-',$time51a);
                                $time51c=$time51b[0].'.'.$time51b[1].'.'.$time51b[2]; 
                                $fuce2arr=array('shijian'=>$time51c,'nodename'=>$time51['nodename'],'beizhu'=>$time51['remark']);
                }else{
                  $fuce2arr=array('复测2'=>'null');
                }
              $order_finally=array(
                'dingdanxinxi'=>$dingdanxinxi,
                'danqianjindu'=>$demand_track1,
                'shenhe'=>$shenhe,
                'selectcompany'=>$companyarr,
                'guanjia'=>$guanjiaarr,
                'liangfang'=>$liangfangarr,
                'confirmcompany'=>$confirmcompanyarr,
                'shejixieyi'=>$xieyiarr,
                'yujiaodi'=>$yujiaodiarr,
                'shigong'=>$shigongarr,
                'kaigong'=>$gongchengarr,
                'chaigai'=>$chaigaiarr,
                'shuidiancl'=>$shuidianclarr,
                'nimucl'=>$nimuclarr,
                'youqicl'=>$youqiclarr,
                'shuidian'=>$shuidianarr,
                'nimu'=>$nimuarr,
                'youqi'=>$youqiarr,
                'jungong'=>$jungongarr,
                'wuranjiance'=>$wuranjiancearr,
                'wuranzhili'=>$wuranarr,
                'fuce1'=>$fuce1arr,
                'weikuan'=>$weikuanarr,
                'ruzhu'=>$ruzhuarr,
                'kongqizhili'=>$kongqiarr,
                'fuce2'=>$fuce2arr,
                );
                echo json_encode(array('code'=>1,'data'=>$order_finally,'message'=>'装修订单详情页的数据','process_time'=>time()));

      }


 
 }