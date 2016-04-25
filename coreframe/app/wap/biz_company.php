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
//按数组某一字段排序
function compare($x,$y)
  { 
    if($x['intervene'] == $y['intervene']) 
     return 0; 
    elseif($x['intervene'] < $y['intervene']) 
     return 1; 
    else 
     return -1; 
  } 
class biz_company extends WUZHI_foreground {
                      
 	  public function listing(){
       $cityid=$GLOBALS['cityid'];
       $company2=array();
       $companys=array();
       $company_finally=array();
       if(isset($cityid)&&!empty($cityid)){
       	        $picture=$this->db->get_list('m_picture','`status`=1','companyid',0,100000,$page,'id desc','companyid');
       	        foreach ($picture as $keyp => $valuep) {

		                $where="`areaid_2`= '".$cityid."' AND `id`='".$valuep['companyid']."' AND `status`=9";
		                $company=$this->db->get_one('m_company',$where,"id,thumb,title,tese,avg_total,designnum,photonum,intervene");
                    $companyz=$this->db->get_one('company',$where,"avg_total");   
                    $companys[]=$companyz['avg_total'];   
		                $company2[]=$company;
                } 
                $companys=array_filter($companys);
                $company2=array_filter($company2);
               foreach ($company2 as $keyc => $valuec) {
                      if($companys[$keyc]['avg_total']==0){
                        $companys[$keyc]['avg_total']=5;
                      }
                      $company2[$keyc]['avg_total']=$companys[$keyc]['avg_total'];
                      $company2[$keyc]['companylogo']='http://www.uzhuang.com/image/big_square/'.$valuec['thumb'];
               }
             
                    // echo '<pre>';print_r($company2);exit;

       }else{
                $picture=$this->db->get_list('m_picture','`status`=1','companyid',0,100000,$page,'id desc','companyid');
                foreach ($picture as $keyp => $valuep) {

                    $where="`areaid_2`= 3360 AND `id`='".$valuep['companyid']."' AND `status`=9";
                    $company=$this->db->get_one('m_company',$where,"id,thumb,title,tese,avg_total,designnum,photonum,intervene");
                     $companyz=$this->db->get_one('company',$where,"avg_total");  
                  
                    $companys[]=$companyz['avg_total'];  
                    $company2[]=$company;
                } 
                $companys=array_filter($companys);
                $company2=array_filter($company2);
               foreach ($company2 as $keyc => $valuec) {
                      if($companys[$keyc]['avg_total']==0){
                        $companys[$keyc]['avg_total']=5;
                      }
                      $company2[$keyc]['avg_total']=$companys[$keyc]['avg_total'];

                      $company2[$keyc]['companylogo']='http://www.uzhuang.com/image/big_square/'.$valuec['thumb'];
               }
       }
              $company2=array_values($company2);
               usort($company2,"compare"); 
               $c=count($company2);
                    // echo '<pre>';print_r($c);exit;
               if($c>10){
                    for($i=0;$i<10;$i++){
                      $company_finally[$i]=$company2[$i];
                    }
                     
                echo json_encode(array('code'=>1,'data'=>$company_finally,'message'=>'口碑公司列表页的数据','process_time'=>time()));
               }else{
                // echo '<pre>';print_r($company2);exit;
                echo json_encode(array('code'=>1,'data'=>$company2,'message'=>'口碑公司列表页的数据','process_time'=>time()));
               }
                
      }

public function company_details(){
        $uid=get_cookie('_uid');
    // $uid=$GLOBALS['uid'];
        $companyid=$GLOBALS['companyid'];
    if($companyid){//非空判断
        //取公司的数据
        // echo '<pre>';print_r($companyid);exit;
        $company=$this->db->get_one('m_company',"`id`=$companyid ",'id,thumb,title,tese,avg_total,avg_design,avg_quality,avg_service,address,com_browsenum');
        $companyz=$this->db->get_one('company',"`id`=$companyid ",'avg_total,avg_design,avg_quality,avg_service');
        $company['companylogo']='http://www.uzhuang.com/image/big_square/'.$company['thumb'];
        if($companyz['avg_total']==0){
                      $companyz['avg_total']='5.0';
                    }
        $company['avg_total']=$companyz['avg_total'];

         if($companyz['avg_design']==0){
                      $companyz['avg_design']='5.0';
                    }
        $company['avg_design']=$companyz['avg_design'];

         if($companyz['avg_quality']==0){
                      $companyz['avg_quality']='5.0';
                    }
        $company['avg_quality']=$companyz['avg_quality'];

         if($companyz['avg_service']==0){
                      $companyz['avg_service']='5.0';
                    }
        $company['avg_service']=$companyz['avg_service'];

        $company_data=$this->db->get_one('company_data',"`id`=$companyid ",'content');
        $company['content']=$company_data['content'];
          // 访问量加一
        $browseCount = (int)$company['com_browsenum']+1;
        // 跟新到数据库
        $this->db->update('m_company',array('com_browsenum'=>$browseCount),array('id'=>$companyid));
         
        //取精品案例的数据
        $picture=$this->db->get_list('m_picture',"`companyid`=$companyid AND `status`=1","id,cover,name,style,housetype,designer");
          foreach ($picture as $key => $value1) {
              $picture[$key]['cover']=getMImgShow($value1['cover'],'original');
          }
        // 取设计师的数据
         $picture1=$this->db->get_list('m_picture',"`companyid`=$companyid AND `status`=1","id,cover,name,style,housetype,designer",0,100000,$page,'id desc','designer');
        $design1=array();
           foreach ($picture1 as $keyp => $valuep) {
          
          $design=$this->db->get_one('m_company_team',"`companyid`=$companyid AND `id`='".$valuep['designer']."'",'id,thumb,title,ranks,productionnum,design_collectnum,thumb1,collectnums');
                         $design['design_collectnum']=$design['design_collectnum']+$design['collectnums'];
                        if(isset($design['thumb1'])&&!empty($design['thumb1'])){
                                $design['thumb']=getMImgShow($design['thumb1'],'big_square'); 
                         }else{
                                $design['thumb']='http://www.uzhuang.com/image/big_square/'.$design['thumb'];
                         }
                    $design1[$keyp]=$design;
                   }
         
         // echo '<pre>';print_r($design1);exit;
        
        //根据picture_configs找对应的中文
        //循环出风格的索引
        $style=array(); 
        $configs_picture = get_config('picture_config');
        for($j=0;$j<count($picture);$j++){
                 $style[]=$picture[$j]['style'];
        }
        foreach ($style as $key => $value) {
          $z = $style[$key];
          $z3=explode(',',$z);
          $zh=array();
              foreach ($z3 as $key3 => $value3) {
            $zh[] =$configs_picture['style'][$value3];
              }
          $picture[$key]['style']=$zh;
        }
        //循环出户型的索引
        $house=array(); 
        for($i=0;$i<count($picture);$i++){
             $house[]=$picture[$i]['housetype'];
        }
        foreach ($house as $key => $value) {
            $z = $house[$key];
            $zh =$configs_picture['house'][$z];
            $picture[$key]['housetype']=$zh;
        }

    }
        
     if($uid){
        //取收藏状态
        
            $collectstatus=$this->db->get_list('favorite',$where11,'keyid,collectstatus');
            $arr=array();
            foreach ($collectstatus as $key => $value) {
                $arr[]=$value['keyid'];
            }
            if(in_array($companyid,$arr)){
                 $collectstatus1=$this->db->get_one('favorite',"`keyid`=$companyid and `uid`=$uid",'keyid,collectstatus');
            }

         //登录状态
        $login_status=(int)1;
        $company_finally=array(
        'company'=>$company,
        'design'=>$design1,
         'picture'=>$picture,
          'login_status'=>$login_status,
            'collectstatus'=>$collectstatus1['collectstatus'],
            ); 
     }else{
             //未登录状态
            $login_status=(int)0;
            $company_finally=array(
            'company'=>$company,
            'design'=>$design1,
             'picture'=>$picture,
              'login_status'=>$login_status,
                'collectstatus'=>(int)0,
                );
     }
     // echo '<pre>';print_r($company_finally);exit;
        echo json_encode(array('code'=>1,'data'=>$company_finally,'message'=>'口碑公司详情页的数据','process_time'=>time()));
      }
}



