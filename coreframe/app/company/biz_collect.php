<?php
// +----------------------------------------------------------------------
// | wuzhicms [ 五指互联网站内容管理系统 ]
// | Copyright (c) 2014-2015 http://www.wuzhicms.com All rights reserved.
// | Licensed ( http://www.wuzhicms.com/licenses/ )
// | Author: wangcanjia <phpip@qq.com>
// +----------------------------------------------------------------------
/**
 * 收藏夹
 */
header("Access-Control-Allow-Origin: *");
defined('IN_WZ') or exit('No direct script access allowed');
load_class('foreground', 'member');
class biz_collect extends WUZHI_foreground {
 	function __construct() {
        $this->member = load_class('member', 'member');
        load_function('common', 'member');
        $this->member_setting = get_cache('setting', 'member');
        parent::__construct();
    }

    /**
     * 收藏的图片
     */
	public function company() {
        $page = isset($GLOBALS['page']) ? intval($GLOBALS['page']) : 1;
        $page = max($page,1);
        $uid = $this->memberinfo['uid'];
        $publisher = $this->memberinfo['username'];
        $result_rs = $this->db->get_list('favorite', "`uid`='$uid' AND `type`=8", '*', 0, 3,$page,'fid DESC');
        $result = array();
        foreach($result_rs as $r) {
            $rs= $this->db->get_one('company',array('id'=>$r['keyid']));
            $rs['fid'] = $r['fid'];
            $rs['url'] = $r['url']; 
            $result[] = $rs;
        }
        $pages = $this->db->pages;
        $total = $this->db->number;
        $configs = get_config('design_config');
        echo json_encode($result);
        //include T('shop','favorite_photos');
    }
    public function design() {
        $page = isset($GLOBALS['page']) ? intval($GLOBALS['page']) : 1;
        $page = max($page,1);
        $uid = $this->memberinfo['uid'];
        $publisher = $this->memberinfo['username'];
        $result_rs = $this->db->get_list('favorite', "`uid`='$uid' AND `type`=7", '*', 0, 3,$page,'fid DESC');
         $pages = $this->db->pages;
        $total = $this->db->number;
        $result = array();
        foreach($result_rs as $r) {
            $rs= $this->db->get_one('company_team',array('id'=>$r['keyid']));
            $rs['fid'] = $r['fid'];
            $rs['url'] = $r['url'];
        
            $picture_datas= $this->db->get_list('picture',array('designer'=>$r['keyid']));
            $rs['picture_datas'] = $picture_datas;
            $design_rs[$key]['picture_list'] = $result;
            $result[] = $rs;
            }
       $pages = $this->db->pages;
       $total = $this->db->number;
       $configs = get_config('design_config');
       echo json_encode($result);
        //include T('designer','favorite_photos');
    }
    public function photos() {
        $page = isset($GLOBALS['page']) ? intval($GLOBALS['page']) : 1;
        $page = max($page,1);
        //var_dump($page);die;
        $uid = get_cookie('uid');
        $publisher = $this->memberinfo['username'];
        $result_rs = $this->db->get_list('favorite', "`uid`='$uid' AND `type` IN(6)", '*', 0, 3,$page,'fid DESC');
        $result = array();
        foreach($result_rs as $r) {
            if($r['type']==5) {
                $rs= $this->db->get_one('m_picture',array('id'=>$r['keyid']),"cover,name,style,housetype,collectnum");
                $rs['fid'] = $r['fid'];
                $rs['url'] = $r['url'];
            } else {
                $rs = $this->db->get_one('m_picture',array('id'=>$r['keyid']),"cover,name,style,housetype,collectnum");
                $rs['fid'] = $r['fid'];
            }
            $result[] = $rs;
        }
        //print_r($result);
        $pages = $this->db->pages;
        $total = $this->db->number;
        $configs = get_config('picture_config');
        //include T('company','favorite_photos');
        //echo json_encode($result);
        echo json_encode(array('code'=>1,'data'=>$result,'message'=>'','process_time'=>time()));exit;
    }
    public function delete() {
        $fid = intval($GLOBALS['fid']);
        $uid = $this->memberinfo['uid'];
        $this->db->delete('favorite',array('fid'=>$fid,'uid'=>$uid));
        MSG('删除成功',HTTP_REFERER);
    }
    
    public function delete_stylist() {
        $fid = intval($GLOBALS['fid']);
        $uid = $this->memberinfo['uid'];
        $this->db->delete('favorite',array('fid'=>$fid,'uid'=>$uid));
        MSG('删除成功',HTTP_REFERER);
    }
}



