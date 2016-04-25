<?php
// +----------------------------------------------------------------------
// | wuzhicms [ 五指互联网站内容管理系统 ]
// | Copyright (c) 2014-2015 http://www.wuzhicms.com All rights reserved.
// | Licensed ( http://www.wuzhicms.com/licenses/ )
// | Author: wangcanjia <phpip@qq.com>
// +----------------------------------------------------------------------
header("content-type:text/html;charset=utf-8");
defined('IN_WZ') or exit('No direct script access allowed');
	/**
	 * 首页
	 */
load_class('foreground', 'member');
class WUZHI_log extends WUZHI_foreground {
    function __construct() {
	   parent::__construct();
	}

  
    public function day_log(){
        //类似节点
        $day_log1 = get_config('log_config');
        $nodeidss=$day_log1[$nodeids];
        foreach($day_log1 as $h => $va){
            if($h=='7'){
               $vs=array();
               foreach($va as $v){
                 $vs[]=$v['nodeid'];
               }
             }
           }
       foreach($this->db->get_list('day_log','`condition`=0 and nodeid IN('.implode(',',$vs).')','id,remark,condition,orderid,nodeid,recphoto,sitephoto', 0, 2, $page,'nodeid DESC') as $item){
            $recphoto =unserialize($item['recphoto']);
            $sitephoto =unserialize($item['sitephoto']);
            $remark =unserialize($item['remark']);
            $this->db->delete('m_day_log',array('orderid'=>$item['orderid'],'nodeid'=>$item['nodeid']));
            switch($item['nodeid']){
                case '37':
                foreach ($recphoto[0] as $pk => $pv) {
                    $temp = explode('_', $pk);
                    if(empty($temp[0]) || !is_array($pv)) continue;
                    foreach((array)$pv as $ck => $cv){
                        $data = array(
                            'nodeid' => $item['nodeid'],
                            'orderid' => $item['orderid'],
                            'proName' => $remark[0]["proName_{$temp[1]}"][$ck],
                            'brand' => null,
                            'accQua' => $remark[0]["accQua_{$temp[1]}"][$ck],
                            'unpuaRea' => $remark[0]["unpuaRea_{$temp[1]}"][$ck],
                            'accDate' => $remark[0]["accDate_{$temp[1]}"][$ck],
                            'overDate' => $remark[0]["overDate_{$temp[1]}"][$ck],
                            'accImg' => $cv,
                            'overImg' => $sitephoto[0]["overImg_{$temp[1]}"][$ck],
                            'node' => $temp[1],
                            'gb_pic' => $remark[0]['gb_pic'][$ck],
                            'gb_cont' => $remark[0]['gb_cont'][$ck],
                        );
                       $this->db->insert('m_day_log',$data);
                    }
                }
                break;
                default:
                foreach ($recphoto as $pk => $pv) {
                    $data1 = array(
                        'nodeid' => $item['nodeid'],
                        'orderid' => $item['orderid'],
                        'proName' => $remark[0]['proName_SOW'][$pk],
                        'brand' => $remark[0]['brand_SOW'][$pk],
                        'accQua' => $remark[0]['accQua_SOW'][$pk],
                        'unpuaRea' => $remark[0]['unpuaRea_SOW'][$pk],
                        'accDate' => $remark[0]['accDate_SOW'][$pk],
                        'overDate' => $remark[0]['overDate_SOW'][$pk],
                        'accImg' => $pv,
                        'overImg' => $sitephoto[$pk],
                        'node' => 'SOW',
                        'gb_pic' => $remark[0]['gb_pic'][$pk],
                        'gb_cont' => $remark[0]['gb_cont'][$pk],
                    );
                    $this->db->insert('m_day_log',$data1);
                }
                break;
            }
            //0->1
            $this->db->update('day_log', array('condition'=>1), array('id' =>$item['id']));
        }
    }

    public function test(){
    	$arr=array(
          'day_log' =>$this->day_log(),
    		);
    }
}