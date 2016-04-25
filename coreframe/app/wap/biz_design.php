<?php
// +----------------------------------------------------------------------
// | wuzhicms [ 五指互联网站内容管理系统 ]
// | Copyright (c) 2014-2015 http://www.wuzhicms.com All rights reserved.
// | Licensed ( http://www.wuzhicms.com/licenses/ )
// | Author: wangcanjia <phpip@qq.com>
// +----------------------------------------------------------------------
defined('IN_WZ') or exit('No direct script access allowed');
load_function('content','content');
/**
 * 设计师
 */
load_class('foreground', 'member');
class biz_design extends WUZHI_foreground{
    private $siteconfigs;
	public function __construct() {
        $this->siteconfigs = get_cache('siteconfigs');
        $this->db = load_class('db');
	}
	//详情页
	public function Detail(){
	    $id =$GLOBALS['id'];
	    if(empty($id)){
        echo json_encode(array('code'=>0,'data'=>null,'message'=>'参数错误','process_time'=>time()));exit;
	    }
	    $uid = get_cookie('_uid');
	    $design_rs = $this->db->get_one('m_company_team', array('id' => $id),"id,thumb,thumb1,title,productionnum,ranks,des_browsenum");
	    $design = $this->db->get_one('company_team_data', array('id' => $id),"content");
	    //var_dump($design);die;
	    $design_rss=array();
	    if($design_rs['thumb1']){
	    	 $design_rss['thumb1'] =getMImgShow($design_rs['thumb1'],'big_square');
	    	   }else{	    		
	         $design_rss['thumb'] ='http://www.uzhuang.com/image/big_square/'.$design_rs['thumb'];
	    }
	    $res = $this->db->get_one('m_company_team', array('id' => $id),"id,title,productionnum,ranks");
	    $design_rss['id']=$res['id'];
	     $design_rss['title']=$res['title'];
	    $design_rss['productionnum']=$res['productionnum'];
	    $design_rss['ranks']=$res['ranks'];
	    $design_rss['content'] = $design['content'];
       //var_dump($design_rss);die;
	      // 访问量加一
        $browseCount = (int)$design_rs['des_browsenum']+1;
        // 跟新到数据库
        $this->db->update('m_company_team',array('des_browsenum'=>$browseCount),array('id'=>$id));
        if($uid){
         $collectstatus=$this->db->get_list('favorite',$where11,'keyid,collectstatus');
			$ar=array();
			foreach ($collectstatus as $key => $value) {
				$ar[]=$value['keyid'];
			}
			if(in_array($id,$ar)){
                 $collectstatus1=$this->db->get_one('favorite',"`keyid`=$id and `uid`=$uid",'keyid,collectstatus');
                 //var_dump($collectstatus1);die;
			}
	        $arr=array();
				$arr['design']=$design_rss;
				$arr['name'] = $this->db->get_list('m_picture','status=1 and designer="'.$id.'"', 'id,cover,name,style,housetype', 'id DESC');
				foreach ($arr['name'] as $key => $value) {
				$arr['name'][$key]['cover']=getMImgShow($value['cover'],'original');
				}
			
				    $style=array(); 
			        $configs_picture = get_config('picture_config');
			        for($j=0;$j<count($arr['name']);$j++){
			                 $style[]=$arr['name'][$j]['style'];
			        }
			        foreach ($style as $key => $value) {
			          $z = $style[$key];
			          $z3=explode(',',$z);
			          $zh=array();
			              foreach ($z3 as $key3 => $value3) {
			            $zh[] =$configs_picture['style'][$value3];
			              }
			          $arr['name'][$key]['style']=$zh;
			        }

				//循环出户型的索引
				$house=array();	
				for($i=0;$i<count($arr['name']);$i++){
		             $house[]=$arr['name'][$i]['housetype'];
				}
				foreach ($house as $key => $value) {
					$z = $house[$key];
					$zh =$configs_picture['house'][$z];
					$arr['name'][$key]['housetype']=$zh;
				}
                $arr['collectstatus']=$collectstatus1['collectstatus'];
                //var_dump($arr['collectstatus']);die;
           }else{
        	 $arr=array();
				$arr['design']=$design_rss;
				$arr['name'] = $this->db->get_list('m_picture','status=1 and designer="'.$id.'"', 'id,cover,name,style,housetype','id DESC');
				foreach ($arr['name'] as $key => $value) {
                $arr['name'][$key]['cover']=getMImgShow($value['cover'],'original');
			    }
				
				// var_dump($arr['design_picture']);die;
 				//根据picture_configs找对应的中文
				//循环出风格的索引
				   $style=array(); 
			        $configs_picture = get_config('picture_config');
			        for($j=0;$j<count($arr['name']);$j++){
			                 $style[]=$arr['name'][$j]['style'];
			        }
			        foreach ($style as $key => $value) {
			          $z = $style[$key];
			          $z3=explode(',',$z);
			          $zh=array();
			              foreach ($z3 as $key3 => $value3) {
			            $zh[] =$configs_picture['style'][$value3];
			              }
			          $arr['name'][$key]['style']=$zh;
			        }
				//循环出户型的索引
				$house=array();	
				for($i=0;$i<count($arr['name']);$i++){
		             $house[]=$arr['name'][$i]['housetype'];
				}
				foreach ($house as $key => $value) {
					$z = $house[$key];
					$zh =$configs_picture['house'][$z];
					$arr['name'][$key]['housetype']=$zh;
				}	
                $arr['collectstatus']=0;
        }
				echo json_encode(array('code'=>1,'data'=>$arr,'message'=>null,'process_time'=>time()));exit;
			    $totals = $this->db->number;
			    $pages = $this->db->pages;
				$configs_picture = get_config('picture_config');
         }
}
?>