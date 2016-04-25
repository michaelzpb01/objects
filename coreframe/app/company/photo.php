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
 * 图库-单图
 */
class photo{
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
		$field_array = array('style','space','color','order');
		$urlrule = '/shejitu/{$style}-{$space}-{$color}-{$order}-{$page}.html';
		$variables = array();
		foreach($field_array as $field) {
			$variables[$field] = isset($GLOBALS[$field]) ? intval($GLOBALS[$field]) : 0;
		}


		$order = isset($GLOBALS['order']) ? $GLOBALS['order'] : 0;
		$orderby_arr = array('collection DESC,picid DESC','picid DESC','collection DESC');
		$orderby = $orderby_arr[$order];
		$page = isset($GLOBALS['page']) ? $GLOBALS['page'] : 1;
		$page = max($page,1);

		$_POST['page_urlrule'] = $urlrule;
		$_POST['page_fields'] = $variables;

		$where = '';
		if($variables['style']) {
			$where .= " AND `style`='".$variables['style']."'";
		}
		if($variables['space']) {
			$where .= " AND `space`='".$variables['space']."'";
		}
		if($variables['color']) {
			$where .= " AND `maincolor`='".$variables['color']."'";
		}
		include T('company','photo',TPLID);
	}
	/**
	 * 图库内容页
	 */
	public function show() {
		$siteconfigs = $this->siteconfigs;
		$picid = intval($GLOBALS['picid']);
		$categorys = get_cache('category','content');
		$configs = get_config('picture_config');

		$categorys = get_cache('category','content');
		//查询数据
		$data = $this->db->get_one('picture_index',array('picid'=>$picid));
		if(!$data || $data['status']!=9) E404();
		extract($data,EXTR_SKIP);
		$where = array('status'=>9,'space'=>$space,'style'=>$style,'housetype'=>$housetype);
		//$where = '';
		$result_rs = $this->db->get_list('picture_index',$where, '*', 0, 20,$page,'id DESC');
		$result = array();
		if($data['remark'][22] == '.'){
				$data['remark'] = '';
			}
			
		$result[] = $data;
		foreach($result_rs as $rs) {
			if($rs['picid']==$picid) continue;
			if($rs['remark'][22] == '.'){
				$rs['remark'] = '';
			}
			$result[] = $rs;
		}
		include T('content','show_photo',TPLID);
	}

	//显示图片
	public function image_show(){
        $imgSrc = $GLOBALS['imgSrc'];
        $type = $GLOBALS['type'];
        require_once(COREFRAME_ROOT.'app/core/libs/class/ImageOperation.class.php');
        $ImageOperation = new ImageOperation($imgSrc,$type);
        $ImageOperation->image_show();
	}

}
?>