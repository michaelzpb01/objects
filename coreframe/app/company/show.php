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
 * 企业内页
 */
class show {
    private $siteconfigs;
	private $company_info;
	private $homeurl;
	private $tplid = 'default';
	public function __construct() {
        $this->siteconfigs = get_cache('siteconfigs');
        $this->db = load_class('db');
	}

    /**
     * 内容页
     */
    public function init() {

        $siteconfigs = $this->siteconfigs;
        $seo_title = $siteconfigs['sitename'];
        $seo_keywords = $siteconfigs['seo_keywords'];
        $seo_description = $siteconfigs['seo_description'];
        $categorys = get_cache('category','content');

		$tplid = $this->tplid;
		$homeurl = $this->homeurl;
		$cid = intval($GLOBALS['cid']);
		$id = isset($GLOBALS['id']) ? intval($GLOBALS['id']) : MSG(L('parameter_error'));
		$cid = isset($GLOBALS['cid']) ? intval($GLOBALS['cid']) : MSG(L('parameter_error'));
		$categorys = get_cache('category','content');
		//查询数据
		$category = get_cache('category_'.$cid,'content');
		$models = get_cache('model_content','model');

		$model_r = $models[$category['modelid']];
		$master_table = $model_r['master_table'];
		$data = $this->db->get_one($master_table,array('id'=>$id));
		$company_info = $this->db->get_one('company', array('id' => $data['companyid']));
		$homeurl = shopurl($data['companyid'],$company_info['domain']);
		if(!$data || $data['status']!=9) MSG('信息不存在或者未通过审核！');

		if($model_r['attr_table']) {
			$attr_table = $model_r['attr_table'];
			if($data['modelid']) {
				$modelid = $data['modelid'];
				$attr_table = $models[$modelid]['attr_table'];
			}
			$attrdata = $this->db->get_one($attr_table,array('id'=>$id));
			$data = array_merge($data,$attrdata);

		} else {
			$modelid = $model_r['modelid'];
		}

		require get_cache_path('content_format','model');
		$form_format = new form_format($modelid);
		$data = $form_format->execute($data);

		foreach($data as $_key=>$_value) {
			$$_key = $_value['data'];
		}

		$elasticid = elasticid($cid);
		$seo_title = $title.'_'.$category['name'].'_'.$siteconfigs['sitename'];
		$seo_keywords = !empty($keywords) ? implode(',',$keywords) : '';
		$seo_description = $remark;
		//上一页
		$previous_page = $this->db->get_one($master_table,"`cid`= '$cid' AND `id`>'$id' AND `status`=9",'*',0,'id ASC');
		//下一页
		$next_page = $this->db->get_one($master_table,"`cid` = '$cid' AND `id`<'$id' AND `status`=9",'*',0,'id DESC');
		//手动分页
		$CONTENT_POS = strpos($content, '_wuzhicms_page_tag_');
		if(!empty($content) && $CONTENT_POS !== false) {
			$page = max($GLOBALS['page'],1);
			$contents = array_filter(explode('_wuzhicms_page_tag_', $content));
			$pagetotal = count($contents);
			$content = $contents[$page-1];
			$tmp_year = date('Y',$addtime);
			$tmp_month = date('m',$addtime);
			$tmp_day = date('d',$addtime);
			$content_pages = pages($pagetotal,$page,1,$category['showurl'],array('year'=>$tmp_year,'month'=>$tmp_month,'day'=>$tmp_day,'catdir'=>$category['catdir'],'cid'=>$cid,'id'=>$id));
		} else {
			$content_pages = '';
		}
		switch($cid) {
			case 136:
				$template = 'cases_show';
				break;
			case 134:
				$template = 'design_show';
				break;
			case 137:
				$template = 'discount_show';
				break;
		}
		include T('company_home/'.$tplid,$template,TPLID);
	}
}
?>