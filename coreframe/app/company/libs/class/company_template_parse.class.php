<?php
// +----------------------------------------------------------------------
// | wuzhicms [ 五指互联网站内容管理系统 ]
// | Copyright (c) 2014-2015 http://www.wuzhicms.com All rights reserved.
// | Licensed ( http://www.wuzhicms.com/licenses/ )
// | Author: wangcanjia <phpip@qq.com>
// +----------------------------------------------------------------------
defined('IN_WZ') or exit('No direct script access allowed');
/**
 * 内容模版，标签解析
 */
class WUZHI_company_template_parse {
	public $number = 0;//初始化查询总数
	public $pages = '';//分页
	public $childs = '';//子栏目
    public function __construct() {
        $this->db = load_class('db');
        $this->categorys = get_cache('category','content');
    }

    /**
     * 内容列表标签
     *
     * @param $c
     * @return array
     */
    public function listing($c) {
        if(isset($c['urlrule'])) {
            $urlrule = $c['urlrule'];
        } else {
            $urlrule = 'index.php?cid={$cid}&page={$page}';
        }
        if($c['where']) {
            $where = $c['where'];
            $where = str_replace("\t",'',$where);
            $where = str_replace("%20",'',$where);
            $where = str_replace("%27",'',$where);
            $where = str_replace("*",'',$where);
            $where = str_replace("\"",'',$where);
            $where = str_replace("/",'',$where);
            $where = str_replace(";",'',$where);
            $where = str_replace("#",'',$where);
            $where = str_replace("--",'',$where);
            $where = '`status`=9 '.$where;
        }

        $order = isset($c['order']) ? $c['order'] : 'id DESC';
        $colspan = isset($c['colspan']) ? $c['colspan'] : 10;
        $rule_arr = array('page'=>$c['page']);
        if(isset($c['variables'])) $rule_arr = array_merge($rule_arr,$c['variables']);
        $result = $this->db->get_list('company', $where, '*', $c['start'], $c['pagesize'], $c['page'],$order,'','',$urlrule,$rule_arr,$colspan);

        if(empty($result)) $GLOBALS['result_lists'] = 0;
        $GLOBALS['pagesize'] = $c['pagesize'];
        $GLOBALS['pages'] = 1;
        if($c['page']) {
            $this->pages = $this->db->pages;
            $GLOBALS['pages'] = $this->pages;
        }

        return $result;
	}
    /**
     * 单图列表
     *
     * @param $c
     * @return array
     */
    public function photo($c) {
        if(isset($c['urlrule'])) {
            $urlrule = $c['urlrule'];
        } else {
            $urlrule = 'index.php?cid={$cid}&page={$page}';
        }
        if($c['where']) {
            $where = $c['where'];
            $where = str_replace("\t",'',$where);
            $where = str_replace("%20",'',$where);
            $where = str_replace("%27",'',$where);
            $where = str_replace("*",'',$where);
            $where = str_replace("\"",'',$where);
            $where = str_replace("/",'',$where);
            $where = str_replace(";",'',$where);
            $where = str_replace("#",'',$where);
            $where = str_replace("--",'',$where);
            $where = '`status`=9 '.$where;
        }

        $order = isset($c['order']) ? $c['order'] : 'id DESC';
        $colspan = isset($c['colspan']) ? $c['colspan'] : 10;
        $rule_arr = array('page'=>$c['page']);
        if(isset($c['variables'])) $rule_arr = array_merge($rule_arr,$c['variables']);
        $result = $this->db->get_list('picture_index', $where, '*', $c['start'], $c['pagesize'], $c['page'],$order,'','',$urlrule,$rule_arr,$colspan);

        if(empty($result)) $GLOBALS['result_lists'] = 0;
        $GLOBALS['pagesize'] = $c['pagesize'];
        $GLOBALS['pages'] = 1;
        if($c['page']) {
            $this->pages = $this->db->pages;
            $GLOBALS['pages'] = $this->pages;
        }

        return $result;
    }

}