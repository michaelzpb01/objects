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
class biz_company extends WUZHI_foreground {
 	  public function listing(){
       $where="`status`=9";
        $company=$this->db->get_list('m_company',$where,"companylogo,title,tese,avg_total,designnum,photonum",0,10,$page,'intervene desc,com_collectnum desc');
        $pages = $this->db->pages;
    

    echo json_encode(array('code'=>1,'data'=>$company,'message'=>'口碑公司列表页的数据','process_time'=>time()));
      }

public function company_details(){
        $uid=get_cookie('_uid');
        $companyid=$GLOBALS['companyid'];
    if($companyid){//非空判断
        //取公司的数据
        $company=$this->db->get_one('m_company',"`id`=$companyid AND `status`=9",'companylogo,title,tese,avg_total,avg_design,avg_quality,avg_service,address');
                         //特色服务,找对应的中文
                         // $configs_picture = get_config('picture_config');
                         // $pp=trim($company['tese'],',');
                         // $ss=explode(',',$pp);
                         //   for($a=0;$a<count($ss);$a++){
                         //       $tese[$a]=$configs_picture['tese'][$ss[$a]];
                         //   }
                         
                         // $company['tese']=$tese;
        
        $company_data=$this->db->get_one('m_company_data',"`id`=$companyid ",'content');
        $company['content']=$company_data['content'];
          // 访问量加一
        $browseCount = (int)$company['com_browsenum']+1;
        // 跟新到数据库
        $this->db->update('m_company',array('com_browsenum'=>$browseCount),array('id'=>$companyid));
        // 取设计师的数据
        $design=$this->db->get_list('m_company_team',"`companyid`=$companyid AND `status`=9",'thumb,title,ranks,productionnum,design_collectnum');
        //取精品案例的数据
        $picture=$this->db->get_list('m_picture',"`companyid`=$companyid AND `status`=1","cover,name,style,housetype");
        foreach ($picture as $key => $value1) {
        $picture[$key]['cover']=getMImgShow($value1['cover'],'original');
        }
        
        //根据picture_configs找对应的中文
        //循环出风格的索引
        $style=array(); 
        $configs_picture = get_config('picture_config');
        for($j=0;$j<count($picture);$j++){
             $style[]=$picture[$j]['style'];
        }
    
        foreach ($style as $key => $value) {
            $z = $style[$key];
            $zh =$configs_picture['style'][$z];
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
        // echo '<pre>';print_r($picture);exit;
     if($uid){
         //登录状态
        $login_status=(int)1;
        $company_finally=array(
        'company'=>$company,
        'design'=>$design,
         'picture'=>$picture,
          'login_status'=>$login_status,
            );
     }else{
             //登录状态
            $login_status=(int)0;
            $company_finally=array(
            'company'=>$company,
            'design'=>$design,
             'picture'=>$picture,
              'login_status'=>$login_status,
                );
     }
        echo json_encode(array('code'=>1,'data'=>$company_finally,'message'=>'口碑公司详情页的数据','process_time'=>time()));
      }

   
    public function GetIp(){  
        $realip = '';  
        $unknown = 'unknown';  
        if (isset($_SERVER)){  
            if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)){  
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);  
                foreach($arr as $ip){  
                    $ip = trim($ip);  
                    if ($ip != 'unknown'){  
                        $realip = $ip;  
                        break;  
                    }  
                }  
            }else if(isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], $unknown)){  
                $realip = $_SERVER['HTTP_CLIENT_IP'];  
            }else if(isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)){  
                $realip = $_SERVER['REMOTE_ADDR'];  
            }else{  
                $realip = $unknown;  
            }  
        }else{  
            if(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), $unknown)){  
                $realip = getenv("HTTP_X_FORWARDED_FOR");  
            }else if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), $unknown)){  
                $realip = getenv("HTTP_CLIENT_IP");  
            }else if(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), $unknown)){  
                $realip = getenv("REMOTE_ADDR");  
            }else{  
                $realip = $unknown;  
            }  
        }  
       $realip = preg_match("/[\d\.]{7,15}/", $realip, $matches) ? $matches[0] : $unknown;  
       return $realip;       
    }  
      
    public function GetIpLookup($ip){  
       if(empty($ip)){  
            $ip = $this->GetIp();
       }  
       $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);  
       if(empty($res)){ return false; }  
       $jsonMatches = array();  
    preg_match('#\{.+?\}#', $res, $jsonMatches);  
        if(!isset($jsonMatches[0])){ return false; }  
        $json = json_decode($jsonMatches[0], true);  
        if(isset($json['ret']) && $json['ret'] == 1){  
            $json['ip'] = $ip;  
            unset($json['ret']);  
       }else{  
           return false;  
        }
        return $json;  
    } 

  $ip=$this->GetIp();
  $ipstr=array('city'=>$this->GetIpLookup($ip));
}


