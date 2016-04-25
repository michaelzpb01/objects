<?php
// +----------------------------------------------------------------------
// | wuzhicms [ 五指互联网站内容管理系统 ]
// | Copyright (c) 2014-2015 http://www.wuzhicms.com All rights reserved.
// | Licensed ( http://www.wuzhicms.com/licenses/ )
// | Author: wangcanjia <phpip@qq.com>
// +----------------------------------------------------------------------
defined('IN_WZ') or exit('No direct script access allowed');

class json {
    private $db;
    public function __construct() {
        $this->db = load_class('db');
    }

	/**
	 * 检查域名是否占用
	 */
	public function check_domain() {
		$uid = get_cookie('_uid');
		$domain = $GLOBALS['param'];
		if(!preg_match('/([a-z0-9]+)/i',$domain)) exit('{"status":"n"}');
		$r = $this->db->get_one('company', array('domain' => $domain));
		if($r && $r['id']!=$uid) {
			exit('{"info":"域名不已经被占用，换个试试吧！","status":"n"}');
		} else {
			exit('{"info":"可以使用！","status":"y"}');
		}
	}
}
?>