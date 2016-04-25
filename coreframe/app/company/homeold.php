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
 * 公司页面
 */
class home {
    private $siteconfigs;
	private $company_info;
	private $homeurl;
	private $tplid = 'default';
	public function __construct() {
        $this->siteconfigs = get_cache('siteconfigs');
        $this->db = load_class('db');


		if($_REQUEST['uid']) {

			if(is_numeric($_REQUEST['uid'])){
				$this->company_info = $this->db->get_one('company', array('id' => $_REQUEST['uid']));
			}else{
				$this->company_info = $this->db->get_one('company', array('domain' => $_REQUEST['uid']));
			}
			$this->homeurl = shopurl($_REQUEST['uid'],'',0,$this->company_info['areaid_2']);
		} elseif($GLOBALS['domain']) {
			$domain = $GLOBALS['domain'];
			if(preg_match('/[^a-z0-9]+/',$domain)) {
				//MSG('您要访问的页面不存在');
				E404();
			}
			$this->company_info = $this->db->get_one('company', array('domain' => $domain));
			$this->homeurl = shopurl(0,$domain,0,$this->company_info['areaid_2']);
			$city_config = get_config('city_config');

			print_r($city_config[$domain]['cityid']);
			if($city_config[$GLOBALS['city']]['cityid']!=$this->company_info['areaid_2']) {
				E404();
			}
		} else {
			$arr_names = explode('.',$_SERVER["HTTP_HOST"]);
			$domain = $arr_names[0];
			$this->company_info = $this->db->get_one('company', array('domain' => $domain));
			$this->homeurl = shopurl(0,$arr_names[0],1,$this->company_info['areaid_2']);
		}

		$this->companyid = $this->company_info['id'];
		if(!$this->company_info) E404();;

	}

    /**
     * 首页
     */
    public function index() {
		$companyid = $this->companyid;
        $siteconfigs = $this->siteconfigs;
        $seo_title = $siteconfigs['sitename'];
        $seo_keywords = $siteconfigs['seo_keywords'];
        $seo_description = $siteconfigs['seo_description'];
        $categorys = get_cache('category','content');
		$company_info = $this->company_info;
		$company_info_data = $this->db->get_one('company_data', array('id' => $companyid));
		if($company_info_data) $company_info = array_merge($company_info,$company_info_data);
		$tplid = $this->tplid;
		$homeurl = $this->homeurl;
		$homebaseurl = rtrim($this->homeurl , '/');

		$where = " AND `companyid`='".$this->companyid."'";
        $configs = get_config('picture_config');
		include T('company_home/'.$tplid,'index',TPLID);
	}
	/**
	 * 设计师
	 */
	public function design() {
		$siteconfigs = $this->siteconfigs;
		$seo_title = $siteconfigs['sitename'];
		$seo_keywords = $siteconfigs['seo_keywords'];
		$seo_description = $siteconfigs['seo_description'];
		$categorys = get_cache('category','content');
        $company_info = $this->company_info;
        $company_info_data = $this->db->get_one('company_data', array('id' => $_GET['uid']));
        if($company_info_data) $company_info = array_merge($company_info,$company_info_data);
        $where = " AND `companyid`='".$this->companyid."'";
		$tplid = $this->tplid;
		$homeurl = $this->homeurl;
		$companyid = $this->companyid;

		include T('company_home/'.$tplid,'design',TPLID);
	}
	/**
	 * 装修案例
	 */
	public function cases() {
		$siteconfigs = $this->siteconfigs;
		$seo_title = $siteconfigs['sitename'];
		$seo_keywords = $siteconfigs['seo_keywords'];
		$seo_description = $siteconfigs['seo_description'];
		$categorys = get_cache('category','content');
        $configs = get_config('picture_config');
        $company_info = $this->company_info;
        $company_info_data = $this->db->get_one('company_data', array('id' => $_GET['uid']));
        if($company_info_data) $company_info = array_merge($company_info,$company_info_data);
		$tplid = $this->tplid;
		$homeurl = $this->homeurl;
		// var_dump($homeurl);exit;
		$page = max($GLOBALS['page'],1);
		$companyid = $this->companyid;

		$house = isset($GLOBALS['house']) ? intval($GLOBALS['house']) : 0;
		$style = isset($GLOBALS['style']) ? intval($GLOBALS['style']) : 0;
		$urlrule = '/cases{$companyid}-{$house}-{$style}-{$page}.html';
		$page_fields = array('companyid'=>$companyid,'house'=>$house,'style'=>$style);
        $where = " AND `companyid`='".$companyid."'";
		$house_item = array();
		$totals = 0;
		foreach($configs['house'] as $_k=>$_v) {
			$tmp_where = '`status`=9 '.$where." AND `housetype`='$_k'";
			$house_item[$_k] = $this->db->count_result('picture', $tmp_where);
			$totals += $house_item[$_k];
		}
		$style_item = array();
		foreach($configs['style'] as $_k=>$_v) {
			$tmp_where = '`status`=9 '.$where." AND `style`='$_k'";
			$style_item[$_k] = $this->db->count_result('picture', $tmp_where);
		}
		if($house) {
			$where  .= " AND `housetype`='$house'";
		}
		if($style) {
			$where  .= " AND `style`='$style'";
		}
		include T('company_home/'.$tplid,'cases',TPLID);
	}

	/**
	 * 优惠活动
	 */
	public function discount() {
		$siteconfigs = $this->siteconfigs;
		$seo_title = $siteconfigs['sitename'];
		$seo_keywords = $siteconfigs['seo_keywords'];
		$seo_description = $siteconfigs['seo_description'];
		$categorys = get_cache('category','content');
        $company_info = $this->company_info;
        $company_info_data = $this->db->get_one('company_data', array('id' => $_GET['uid']));
        if($company_info_data) $company_info = array_merge($company_info,$company_info_data);
		$tplid = $this->tplid;
		$homeurl = $this->homeurl;
		$page = max($GLOBALS['page'],1);
		$companyid = $this->companyid;

		
		$urlrule = '/cases{$companyid}-{$page}.html';
		$page_fields = array('companyid'=>$this->companyid);
		$where = " AND `companyid`='".$this->companyid."'";
		$result = $this->db->get_one('market_sale',array('companyid'=>$companyid));
		include T('company_home/'.$tplid,'discount',TPLID);
	}

	/**
	 * 点评
	 */
	public function comments() {
		$siteconfigs = $this->siteconfigs;
		$seo_title = $siteconfigs['sitename'];
		$seo_keywords = $siteconfigs['seo_keywords'];
		$seo_description = $siteconfigs['seo_description'];
		$categorys = get_cache('category','content');
        $company_info = $this->company_info;
        $company_info_data = $this->db->get_one('company_data', array('id' => $_GET['uid']));
        if($company_info_data) $company_info = array_merge($company_info,$company_info_data);
		$tplid = $this->tplid;
		$homeurl = $this->homeurl;
		$companyid = $this->companyid;

		include T('company_home/'.$tplid,'comments',TPLID);
	}
}
?>