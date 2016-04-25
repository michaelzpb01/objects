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
 * 公司列表
 */
class index{
    private $siteconfigs;
	public function __construct() {
        $this->siteconfigs = get_cache('siteconfigs');
        $this->db = load_class('db');
	}

    /**
     * 首页
     */
    public function init() {
		header('Access-Control-Allow-Origin:*');
		$siteconfigs = $this->siteconfigs;
		$categorys = get_cache('category','content');

		// $arr_names = explode('.',$_SERVER["HTTP_HOST"]);
		$city = $GLOBALS['city'];
		$city = strtolower($city);

		// $r = $this->db->get_one('linkage_data', array('letter' => $letter));
		$citys = get_config('city_config');
		$area_topid = $citys[$city]['cityid'];

		$area_result = $this->db->get_list('linkage_data', array('pid'=>$area_topid), '*', 0, 50, 0, 'sort ASC,lid ASC','','lid');
		//var_dump($area_result);die;
		$kw_areaids = $area_result[$area_topid]['name'];
		//var_dump($kw_areaids);die;

		$configs = get_config('company_config');
		$where = '';
		$field_array = array('areaids','typeids','style','tese','order');

		$variables = array();
		foreach($field_array as $field) {
			$$field = $variables[$field] = isset($GLOBALS[$field]) ? intval($GLOBALS[$field]) : 0;
		}
		$city = $variables['city'] = $GLOBALS['city'];
		$page = isset($GLOBALS['page']) ? $GLOBALS['page'] : 1;
		$page = max($page,1);

		$order = isset($GLOBALS['order']) ? $GLOBALS['order'] : 0;
		$orderby_arr = array('sort DESC,id DESC','order_numbers DESC','avg_total DESC','avg_total ASC');
		$orderby = $orderby_arr[$order];

		$page = isset($GLOBALS['page']) ? $GLOBALS['page'] : 1;
		$page = max($page,1);
		//$listurl = '/company/'.$areaids.'-'.$typeids.'-'.$style.'-'.$tese.'-'.$order.'-'.$page.'.html';

		$_POST['page_urlrule'] = '/{$city}-zhuangxiugongsi/{$areaids}-{$typeids}-{$style}-{$tese}-{$order}-{$page}/';
		$_POST['page_fields'] = $variables;

		$where = '';
		$cityid = $area_topid;
		// if(!$cityid) $cityid = 3360;
		$where = " AND `areaid_2`= '$cityid'";

		$kw_areaids = $kw_style = '';
		if($areaids) {
			$where .= " AND `areaids` LIKE '%,$areaids,%'";
			$kw_areaids = $area_result[$areaids]['name'];
		}
		if($typeids) {
			$where .= " AND `typeids` LIKE '%,$typeids,%'";
		}
		if($style) {
			$where .= " AND `style` LIKE '%,$style,%'";
			$kw_style = $configs['style'][$style];
		}
		if($tese) {
			$where .= " AND `tese` LIKE '%,$tese,%'";
		}
		$company_rs = $this->db->get_list('company', '`status`=9 '.$where, '*', 0, 20, $page, $orderby,'','', $_POST['page_urlrule'],$variables);
		$totals = $this->db->number;
		$pages = $this->db->pages;

		if($where) {
			//$seo_title = rtrim($r['name'],'市').$kw_areaids.$kw_style.'装修公司_'.rtrim($r['name'],'市').$kw_areaids.'专业的'.$kw_style.'装修公司 – 优装美家装修网';
			$seo_title = '北京市'.$kw_areaids.$kw_style.'装修公司_'.rtrim($r['name'],'市').$kw_areaids.'专业的'.$kw_style.'装修公司 – 优装美家装修网';
			$seo_keywords = '北京市'.$kw_areaids.$kw_style.',装修公司,专业';
			$seo_description = '优装美家装修网涵盖了北京所有的装修公司大全，了解北京装修公司排名大全的所有装修公司，请关注优装美家装修网！';
		} else {
			$seo_title = '北京装修公司大全_北京专业装修公司_北京装修公司推荐排名 – 优装美家装修网';
			$seo_keywords = '';
			$seo_description = '';
		}
		include T('company','company',TPLID);
	}

}
?>