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
 * 图库-图集
 */
class photos{
    private $siteconfigs;
	public function __construct() {
        $this->siteconfigs = get_cache('siteconfigs');
        $this->db = load_class('db');
	}

    /**
     * 图库首页
     */
    public function init() {
		$siteconfigs = $this->siteconfigs;
		$categorys = get_cache('category','content');
		$configs = get_config('picture_config');
		$where = '';
		$field_array = array('style','house','area','cost','color','order');
		$urlrule = '/xiaoguotu/{$style}-{$house}-{$area}-{$cost}-{$color}-{$order}-{$page}.html';
		$variables = array();
		foreach($field_array as $field) {
			$variables[$field] = isset($GLOBALS[$field]) ? intval($GLOBALS[$field]) : 0;
		}


		$order = isset($GLOBALS['order']) ? $GLOBALS['order'] : 0;
		$orderby_arr = array('sort DESC,id DESC','id DESC','collection DESC,id DESC');
		$orderby = $orderby_arr[$order];

		$page = isset($GLOBALS['page']) ? $GLOBALS['page'] : 1;
		$page = max($page,1);
		$_POST['page_urlrule'] = $urlrule;
		$_POST['page_fields'] = $variables;

		$kw_style = $kw_house = '';
		$where = '';
		if($variables['style']) {
			$where .= " AND `style`='".$variables['style']."'";
			$kw_style = $configs['style'][$variables['style']];
		}
		if($variables['house']) {
			$where .= " AND `housetype`='".$variables['house']."'";
			$kw_house = $configs['house'][$variables['house']];
		}
		if($variables['color']) {
			$where .= " AND `maincolor`='".$variables['color']."'";
		}
		if($variables['area']) {
			$min = $configs['area'][$variables['area']]['min'];
			$max = $configs['area'][$variables['area']]['max'];
			if($min && $max) {
				$where .= " AND `area` >= $min";
				$where .= " AND `area` <= $max";
			} else {
				if($min) $where .= " AND `area` > $min";
				if($max) $where .= " AND `area` < $max";
			}
		}
		if($variables['cost']) {
			$min = $configs['cost'][$variables['cost']]['min'];
			$max = $configs['cost'][$variables['cost']]['max'];
			if($min && $max) {
				$where .= " AND `cost` >= $min";
				$where .= " AND `cost` <= $max";
			} else {
				if($min) $where .= " AND `cost` > $min";
				if($max) $where .= " AND `cost` < $max";
			}
		}
		if($where) {
			$seo_title = '北京'.$kw_style.$kw_house.'装修效果图大全_'.$kw_house.$kw_style.'北京装修效果图2015图片_'.$kw_style.$kw_house.'北京风格设计效果图欣赏案例-优装美家装修图库';
			$seo_keywords = '北京'.$kw_style.$kw_house.'装修,效果图,设计图';
			$seo_description = '优装美家拥有最全的北京简约两居装修效果图，找2015简约两居风格的室内装修设计效果图，请到优装美家装修图库来欣赏精彩案例。';
		} else {
			$seo_title = '家庭装修效果图_室内装修效果图_优装美家-房屋家居装修效果图大全';
			$seo_keywords = '装修设计图,装修效果图,大全,2015,图片,房子,案例,室内装修';
			$seo_description = '优装美家装修设计效果图频道，涵盖2015年国内外最流行室内装修效果图案例，拥有行业内最in的房子装修设计图大全，让正在装修的你获得更多灵感！ ';
		}

		include T('company','photos',TPLID);
	}

	/**
	 * 图库内容页
	 */
	public function show() {
		$siteconfigs = $this->siteconfigs;

		$categorys = get_cache('category','content');
		$configs = get_config('picture_config');
		include T('content','show_photos',TPLID);
	}

}
?>