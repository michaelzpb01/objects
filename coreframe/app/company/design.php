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
class design{
    private $siteconfigs;
	public function __construct() {
        $this->siteconfigs = get_cache('siteconfigs');
        $this->db = load_class('db');
	}

    /**
     * 设计师首页
     */
    public function init() {
		$siteconfigs = $this->siteconfigs;

		$categorys = get_cache('category','content');
		$configs = get_config('design_config');

		$where = '';
		$field_array = array('style','years','money_type','order');
		$urlrule = '/shejishi/{$style}-{$years}-{$money_type}-{$order}-{$page}.html';
		$variables = array();
		foreach($field_array as $field) {
			$variables[$field] = isset($GLOBALS[$field]) ? intval($GLOBALS[$field]) : 0;
		}


		$order = isset($GLOBALS['order']) ? $GLOBALS['order'] : 0;
		$orderby_arr = array('order_numbers DESC','years DESC');
		$orderby = $orderby_arr[$order];

		$page = isset($GLOBALS['page']) ? $GLOBALS['page'] : 1;
		$page = max($page,1);

		$_POST['page_urlrule'] = $urlrule;
		$_POST['page_fields'] = $variables;
		var_dump($variables['style']);

		$where = '';
		if($variables['style']) {
			$where .= " AND `style` LIKE '%,".$variables['style'].",%'";
		}
		if($variables['years']) {
			$where .= " AND `years`='".$variables['years']."'";
		}
		if($variables['money_type']) {
			$where .= " AND `money_type`='".$variables['money_type']."'";
		}
		$design_rs = $this->db->get_list('company_team', '`status`=9 '.$where, '*', 0, 20, $page, $orderby,'','', $_POST['page_urlrule'],$variables);
		$totals = $this->db->number;
		$pages = $this->db->pages;

		foreach($design_rs as $key=>$r) {
			$designer = $r['id'];
			$result = $this->db->get_list('picture', "`status`=9 AND `designer`='$designer'", '*', 0, 2, 0, 'id DESC');
			$design_rs[$key]['picture_list'] = $result;
		}

		$configs_picture = get_config('picture_config');
		include T('company','design',TPLID);
	}
}
?>