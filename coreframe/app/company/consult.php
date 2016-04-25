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
public function refer() {
		$siteconfigs = $this->siteconfigs;
        $company_info = $this->company_info;
        $company_info_data = $this->db->get_one('company_data', array('id' => $_GET['uid']));
        print_r($company_info_data);exit;
        //if($company_info_data) $company_info = array_merge($company_info,$company_info_data);
        //$where = " AND `companyid`='".$this->companyid."'";
		//$tplid = $this->tplid;
		//$homeurl = $this->homeurl;
		//$companyid = $this->companyid;

		include T('content','list');
	}