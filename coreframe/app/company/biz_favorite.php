<?php
// +----------------------------------------------------------------------
// | wuzhicms [ ÎåÖ¸»¥ÁªÍøÕ¾ÄÚÈÝ¹ÜÀíÏµÍ³ ]
// | Copyright (c) 2014-2015 http://www.wuzhicms.com All rights reserved.
// | Licensed ( http://www.wuzhicms.com/licenses/ )
// | Author: wangcanjia <phpip@qq.com>
// +----------------------------------------------------------------------
/**
 * ÊÕ²Ø¼Ð
 */
header("Access-Control-Allow-Origin: *");
defined('IN_WZ') or exit('No direct script access allowed');
load_class('foreground', 'member');
class biz_favorite extends WUZHI_foreground {
    function __construct() {
        $this->member = load_class('member', 'member');
        load_function('common', 'member');
        $this->member_setting = get_cache('setting', 'member');
        parent::__construct();
    }

    /**
     * ÊÕ²ØµÄ×°ÐÞ°¸Àý
     */
    public function picture() {
        $page = isset($GLOBALS['page']) ? intval($GLOBALS['page']) : 1;
        $page = max($page,1);
        //var_dump($page);die;
        $uid = $this->memberinfo['uid'];
        $publisher = $this->memberinfo['username'];
        $result_rs = $this->db->get_list('favorite', "`uid`='$uid' AND `type` IN(6)", '*', 0, 3,$page,'fid DESC');
        $result = array();
        foreach($result_rs as $r) {
            if($r['type']==5) {
                $rs= $this->db->get_one('picture_index',array('id'=>$r['keyid']));
                $rs['fid'] = $r['fid'];
                $rs['url'] = $r['url'];
            } else {
                $rs = $this->db->get_one('picture',array('id'=>$r['keyid']));
                $rs['fid'] = $r['fid'];
            }
            $result[] = $rs;
        }
        $pages = $this->db->pages;
        $total = $this->db->number;
        $configs = get_config('picture_config');
        //include T('company','favorite_photos');
        return $total;
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
       $configs = get_config('design_config');
       $total = $this->db->number;
        return $total;
    }


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
        return $total;
    }
    public function member(){
       $uid =get_cookie('uid');
       $where = "`uid`='$uid'";
       $result = $this->db->get_list('member', $where, 'username');
       return $result;
    }

    public function delete() {
        $fid = intval($GLOBALS['fid']);
        $uid = $this->memberinfo['uid'];
        $this->db->delete('favorite',array('fid'=>$fid,'uid'=>$uid));
        MSG('É¾³ý³É¹¦',HTTP_REFERER);
    }


 public function count(){
    if(!$this->member()){
    $arr=array(
        'member'=>null,
        );
    echo json_encode(array('code'=>0,'data'=>$arr,'message'=>L('login_please'),'process_time'=>time())); exit;
    }else{
        $arr=array(
        'design'=>$this->design(),
        'picture'=>$this->picture(),
        'company'=>$this->company(),
        'member'=>$this->member(),
        );
    //echo json_encode($arr);
    echo json_encode(array('code'=>1,'data'=>$arr,'message'=>'','process_time'=>time())); exit;
    }
 }

}



