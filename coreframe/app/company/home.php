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
 * 公司页面
 */
class home {
    private $siteconfigs;
	private $company_info;
	private $homeurl;
	private $tplid = 'default';
	public function __construct() {
        $this->siteconfigs = get_cache('siteconfigs');
        $this->db = load_class('db');


		if($_REQUEST['uid']) {

			if(is_numeric($_REQUEST['uid'])){
				$this->company_info = $this->db->get_one('company', array('id' => $_REQUEST['uid']));
			}else{
				$this->company_info = $this->db->get_one('company', array('domain' => $_REQUEST['uid']));
			}
			$this->homeurl = shopurl($_REQUEST['uid'],'',0,$this->company_info['areaid_2']);
		} elseif($GLOBALS['domain']) {
			$domain = $GLOBALS['domain'];
			if(preg_match('/[^a-z0-9]+/',$domain)) {
				//MSG('您要访问的页面不存在');
				E404();
			}
			$this->company_info = $this->db->get_one('company', array('domain' => $domain));
			$this->homeurl = shopurl(0,$domain,0,$this->company_info['areaid_2']);
			$city_config = get_config('city_config');

			print_r($city_config[$domain]['cityid']);
			if($city_config[$GLOBALS['city']]['cityid']!=$this->company_info['areaid_2']) {
				E404();
			}
		} else {
			$arr_names = explode('.',$_SERVER["HTTP_HOST"]);
			$domain = $arr_names[0];
			$this->company_info = $this->db->get_one('company', array('domain' => $domain));
			$this->homeurl = shopurl(0,$arr_names[0],1,$this->company_info['areaid_2']);
		}

		$this->companyid = $this->company_info['id'];
		if(!$this->company_info) E404();;


	/*	// 曹植后加
		$this->db = load_class('db');
        $this->setting = get_cache('setting', 'member');
        //  判断是不是public 方法如果是则无需验证登录
        if(substr(V, 0, 7) != 'public_') {
             $this->check_login();
        }*/
        $this->groups = get_cache('group','member');

	}

	/**
     * 判断是否是登录状态
     */
 /*   public function check_login(){
        //  如下方法无需验证登录状态
        if(M =='member' && F =='index' && in_array(V, array('login','login_merchant', 'show','logout', 'register','register_merchant', 'auth','threeLevel','companyThreeLevel','getServiceCity','getServiceArea','getConcreteArea','getArea','getCompanyArea','sendEmail'))) {
        } else {
            $auth = get_cookie('auth');
            if ($auth) {
                $auth_key = substr(md5(_KEY), 8, 8);
                list($uid, $password, $cookietime) = explode("\t", decode($auth, $auth_key));
                $uid = (int)$uid;
                //  判断记录的时间是否过期
                if($cookietime && $cookietime < SYS_TIME){
                    $this->clean_cookie();
                    MSG(L('cookie_timeout'), 'index.php?m=member&v=login');
                }
                //  获取用户信息
                $this->memberinfo = $this->db->get_one('member', '`uid` = '.$uid, '*');
                //  判断用户是否被锁定
                if($this->memberinfo['lock'] && (empty($this->memberinfo['lock']) || $this->memberinfo['locktime'] > SYS_TIME))MSG(L('user_lock'), 'index.php');
                //  判断用户会员组
                if($this->memberinfo['groupid'] == 1) {
                    $this->clean_cookie();
                    MSG(L('user_banned'), 'index.php');
                } elseif($this->setting['checkemail'] && $this->memberinfo['groupid'] == 2) {
                    $this->clean_cookie();
                    $this->send_register_mail($this->memberinfo);
                    MSG(L('need_email_authentication'));
                }
                //  判断用户密码是否和cookie一致
                if($this->memberinfo['password'] !== $password){
                    $this->clean_cookie();
                    MSG(L('login_again_please'), 'index.php?m=member&v=login');
                }
                //  如果用户还没选择模型 那么强制跳转到模型选择页面
                if(empty($this->memberinfo['modelid']) && V != 'model'){
                    MSG(L('need_set_model'), 'index.php?m=member&v=model');
                }
                //  判断是否存在模型id
                if($this->memberinfo['modelid']){
                    $model_table = $this->db->get_one('model', 'modelid='.$this->memberinfo['modelid'], 'attr_table');
                    //获取用户模型信息
                    $this->_member_modelinfo = $this->db->get_one($model_table['attr_table'], '`uid` = '.intval($uid), '*');
                    if(is_array($this->_member_modelinfo)) {
                        $this->memberinfo = array_merge($this->memberinfo, $this->_member_modelinfo);
                    }
                }
                $this->uid = $uid;
            } else {
                header("Location:".WEBURL."index.php?v=public_show&cid=184&id=9");
            }
        }
    }
*/

    /**
     * 首页
     */
   public function index() {
		$companyid = $this->companyid;
        $siteconfigs = $this->siteconfigs;
        $seo_title = $siteconfigs['sitename'];
        $seo_keywords = $siteconfigs['seo_keywords'];
        $seo_description = $siteconfigs['seo_description'];
        $categorys = get_cache('category','content');
		$company_info = $this->company_info;
		$company_info_data = $this->db->get_one('company_data', array('id' => $companyid));
         //取装修案例数
         $r=$this->db->get_list('picture',array('companyid'=>$companyid));
         $alcd=count($r);
         if($alcd!=0){
         $this->db->update('company',array('order_numbers'=>$totals),array('id'=>$companyid));
         }
	     //取dianping表里的评分
          $res=$this->db->get_list('dianping',array('company_id'=>$companyid));
          $dpcd=count($res);
          if($dpcd!=0){
                 $c=count($res);
                 $arr1=array();
                 $arr2=array();
                 $arr3=array(); 
                for($i=0;$i<$c;$i++){
                    $arr1[]=$res[$i]['field1'];
                }
                for($i=0;$i<$c;$i++){
                    $arr2[]=$res[$i]['field2'];
                }
                for($i=0;$i<$c;$i++){
                    $arr3[]=$res[$i]['field3'];
                }
                //取设计水平字段
                $design=array_sum($arr1)/(int)$c;
                //取服务评分字段
                $serve=array_sum($arr2)/(int)$c;
                //取施工质量字段
                $quality=array_sum($arr3)/(int)$c;
                //算综合评分
                $avg_total=($design+$serve+$quality)/3;
                // $totalint=(int)$avg_total;
                //更新company表的数据
                $this->db->update('company',array('avg_total'=>$avg_total,'avg_design'=>$design,'avg_service'=>$serve,'avg_quality'=>$quality),array('id'=>$companyid));
              }

		if($company_info_data) $company_info = array_merge($company_info,$company_info_data);
		$tplid = $this->tplid;
		$homeurl = $this->homeurl;
		$homebaseurl = rtrim($this->homeurl , '/');
		$leyures = $this->db->get_one('member_company_data',array('uid'=>$companyid));
		//var_dump($leyures);
		$leyu_json = array(
              'webhtml'=>urlencode('http://www.uzhuang.com/index.php?m=company&f=leyu&v=index&id='.$companyid)
			);
		$json_str =json_encode($leyu_json);
		$base64_str = base64_encode($json_str);
		//var_dump($json_str,base64_decode($base64_str));

		$where = " AND `companyid`='".$this->companyid."'";
        $configs = get_config('picture_config');
		include T('company_home/'.$tplid,'index',TPLID);
	}
	/**
	 * 设计师
	 */
	public function design() {
		$siteconfigs = $this->siteconfigs;
		$seo_title = $siteconfigs['sitename'];
		$seo_keywords = $siteconfigs['seo_keywords'];
		$seo_description = $siteconfigs['seo_description'];
		$categorys = get_cache('category','content');
        $company_info = $this->company_info;
        $company_info_data = $this->db->get_one('company_data', array('id' => $_GET['uid']));
        if($company_info_data) $company_info = array_merge($company_info,$company_info_data);
        $where = " AND `companyid`='".$this->companyid."'";
		$tplid = $this->tplid;
		$homeurl = $this->homeurl;
		$page = max($GLOBALS['page'],1);
		$companyid = $this->companyid;

		$leyures = $this->db->get_one('member_company_data',array('uid'=>$companyid));
		$leyu_json = array(
              'companyurl'=>urlencode('http://www.uzhuang.com/index.php?m=company&f=leyu&v=index&id='.$companyid)
			);
		$json_str =json_encode($leyu_json);
		$base64_str = base64_encode($json_str);

		$house = isset($GLOBALS['house']) ? intval($GLOBALS['house']) : 0;
		$style = isset($GLOBALS['style']) ? intval($GLOBALS['style']) : 0;
		$urlrule = '/design{$companyid}-{$house}-{$style}-{$page}.html';
		$page_fields = array('companyid'=>$companyid,'house'=>$house,'style'=>$style);
        $where = " AND `companyid`='".$companyid."'";
		$house_item = array();
		$totals = 0;
		foreach($configs['house'] as $_k=>$_v) {
			$tmp_where = '`status`=9 '.$where." AND `housetype`='$_k'";
			$house_item[$_k] = $this->db->count_result('picture', $tmp_where);
			$totals += $house_item[$_k];
		}
		$style_item = array();
		foreach($configs['style'] as $_k=>$_v) {
			$tmp_where = '`status`=9 '.$where." AND `style`='$_k'";
			$style_item[$_k] = $this->db->count_result('picture', $tmp_where);
		}
		if($house) {
			$where  .= " AND `housetype`='$house'";
		}
		if($style) {
			$where  .= " AND `style`='$style'";
		}

		include T('company_home/'.$tplid,'design',TPLID);
	}
	/**
	 * 装修案例
	 */
	public function cases() {
		$siteconfigs = $this->siteconfigs;
		$seo_title = $siteconfigs['sitename'];
		$seo_keywords = $siteconfigs['seo_keywords'];
		$seo_description = $siteconfigs['seo_description'];
		$categorys = get_cache('category','content');
        $configs = get_config('picture_configwp');
        $company_info = $this->company_info;
        $company_info_data = $this->db->get_one('company_data', array('id' => $_GET['uid']));
        if($company_info_data) $company_info = array_merge($company_info,$company_info_data);
		$tplid = $this->tplid;
		$homeurl = $this->homeurl;
		$page = max($GLOBALS['page'],1);
		$companyid = $this->companyid;

		$leyures = $this->db->get_one('member_company_data',array('uid'=>$companyid));
		$leyu_json = array(
              'webhtml'=>urlencode('http://www.uzhuang.com/index.php?m=company&f=leyu&v=index&id='.$companyid)
			);
		$json_str =json_encode($leyu_json);
		$base64_str = base64_encode($json_str);

		$house = isset($GLOBALS['house']) ? intval($GLOBALS['house']) : 0;
		$style = isset($GLOBALS['style']) ? intval($GLOBALS['style']) : 0;
		$urlrule = '/cases{$companyid}-{$house}-{$style}-{$page}.html';
		$page_fields = array('companyid'=>$companyid,'house'=>$house,'style'=>$style);
        $where = " AND `companyid`='".$companyid."'";
		$house_item = array();
		$totals = 0;
		foreach($configs['house'] as $_k=>$_v) {
			$tmp_where = '`status`=9 '.$where." AND `housetype`='$_k'";
			$house_item[$_k] = $this->db->count_result('picture', $tmp_where);
			$totals += $house_item[$_k];
		}
		$style_item = array();
		foreach($configs['style'] as $_k=>$_v) {
			$tmp_where = '`status`=9 '.$where." AND `style`='$_k'";
			$style_item[$_k] = $this->db->count_result('picture', $tmp_where);
		}
		if($house) {
			$where  .= " AND `housetype`='$house'";
		}
		if($style) {
			$where  .= " AND `style`='$style'";
		}
		include T('company_home/'.$tplid,'cases',TPLID);
	}

	/**
	 * 优惠活动
	 */
	public function discount() {
		$siteconfigs = $this->siteconfigs;
		$seo_title = $siteconfigs['sitename'];
		$seo_keywords = $siteconfigs['seo_keywords'];
		$seo_description = $siteconfigs['seo_description'];
		$categorys = get_cache('category','content');
        $company_info = $this->company_info;
        $company_info_data = $this->db->get_one('company_data', array('id' => $_GET['uid']));
        if($company_info_data) $company_info = array_merge($company_info,$company_info_data);
		$tplid = $this->tplid;
		$homeurl = $this->homeurl;
		$page = max($GLOBALS['page'],1);
		$companyid = $this->companyid;

		$leyures = $this->db->get_one('member_company_data',array('uid'=>$companyid));
		$leyu_json = array(
              'webhtml'=>urlencode('http://www.uzhuang.com/index.php?m=company&f=leyu&v=index&id='.$companyid)
			);
		$json_str =json_encode($leyu_json);
		$base64_str = base64_encode($json_str);

		$urlrule = '/cases{$companyid}-{$page}.html';
		$page_fields = array('companyid'=>$this->companyid);
		$where = " AND `companyid`='".$this->companyid."'";
		
		$result = $this->db->get_one('market_sale',array('companyid'=>$companyid));

		include T('company_home/'.$tplid,'discount',TPLID);
	}

	/**
	 * 点评
	 */
	public function comments() {
		$siteconfigs = $this->siteconfigs;
		$seo_title = $siteconfigs['sitename'];
		$seo_keywords = $siteconfigs['seo_keywords'];
		$seo_description = $siteconfigs['seo_description'];
		$categorys = get_cache('category','content');
        $company_info = $this->company_info;
        $company_info_data = $this->db->get_one('company_data', array('id' => $_GET['uid']));
        if($company_info_data) $company_info = array_merge($company_info,$company_info_data);
		$tplid = $this->tplid;
		$homeurl = $this->homeurl;
		$companyid = $this->companyid;

		include T('company_home/'.$tplid,'comments',TPLID);
	}
}
?>