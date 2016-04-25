

<?php
// +----------------------------------------------------------------------
// | wuzhicms [ 五指互联网站内容管理系统 ]
// | Copyright (c) 2014-2015 http://www.wuzhicms.com All rights reserved.
// | Licensed ( http://www.wuzhicms.com/licenses/ )
// | Author: wangcanjia <phpip@qq.com>
// +----------------------------------------------------------------------
defined('IN_WZ') or exit('No direct script access allowed');
/**
 * 申请装修服务
 */


	public function listing(){
        $memberinfo = $this->memberinfo;
        $page = isset($GLOBALS['page']) ? intval($GLOBALS['page']) : 1;
        $page = max($page,1);
        if(isset($GLOBALS['keywords'])) {
            $keywords = $GLOBALS['keywords'];
            $where = "title LIKE '%$keywords%'";
        } else {
          $where = '';
        }
       $result = $this->db->get_list('company', $where, '*', 0, 5,$page,'addtime DESC');
       $pages = $this->db->pages;
       $total = $this->db->number;
       include T('company','company_tj');
    }


    public function add1(){



    	$ids_str = serialize($GLOBALS['ids']);
    	//var_dump($ids_str);exit;
    	//echo "<hr/>";
    	//var_dump(unserialize($ids_str));
    	//echo "<hr/>";

    	//var_dump($GLOBALS['ids']);exit;
    	//$formdata = array();
    	//$formdata['iscompany'] = remove_xss($GLOBALS['id']);
    	/*
    	$companyData = array();
    	$companyData[] = array('company_id'=>2,'title'=>'东易日盛');
    	$companyData[] = array('company_id'=>3,'title'=>'优装网');
    	$companyData[] = array('company_id'=>4,'title'=>'装修网');
		
		$result = json_encode($companyData);
		$result = serlize($companyData);
		$result = serlize($companyData);
    	 */	

        $formdata['iscompany'] = remove_xss($ids_str);
    	$this->db->insert('demand',$formdata);
    	include T('company','company_tj');
	}