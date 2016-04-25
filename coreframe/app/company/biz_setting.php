<?php
// +----------------------------------------------------------------------
// | wuzhicms [ 五指互联网站内容管理系统 ]
// | Copyright (c) 2014-2015 http://www.wuzhicms.com All rights reserved.
// | Licensed ( http://www.wuzhicms.com/licenses/ )
// | Author: wangcanjia <phpip@qq.com>
// +----------------------------------------------------------------------
header('Content-type:text/html;charset=utf-8');
defined('IN_WZ') or exit('No direct script access allowed');
/**
 * 商家设置
 */
load_class('foreground', 'member');
class biz_setting extends WUZHI_foreground {
	function __construct() {
        $this->member = load_class('member', 'member');
        load_function('common', 'member');
        $this->member_setting = get_cache('setting', 'member');
        /*parent::__construct();
        //企业用户权限检查
        if($this->memberinfo['modelid']!=11 || !$this->memberinfo['checkmec']) {
            MSG('您的帐号还未通过企业认证审核！如需帮助请联系客服。');
        }*/
        $this->db = load_class('db');
		$this->setting = get_cache('setting', 'member');
		//	判断是不是public 方法如果是则无需验证登录
		if(substr(V, 0, 7) != 'public_') {
			 $this->check_login();
		}
        $this->groups = get_cache('group','member');
	}

	/**
	 * 判断是否是登录状态
	 */
	public function check_login(){
		parent::goMerchantLogin();
	}

	
    /**
     * 列表
     */
    public function setting() {
    	// echo "1111111";die;
        $memberinfo = $this->memberinfo;
        $uid = $memberinfo['uid'];
		$id = $uid;
		$cid = 1;
		$cate_config = get_cache('category_'.$cid,'content');
		if(!$cate_config) MSG(L('category not exists'));
		//如果设置了modelid，那么则按照设置的modelid。共享模型添加必须数据必须指定该值。
		if(isset($GLOBALS['modelid']) && is_numeric($GLOBALS['modelid'])) {
			$modelid = $GLOBALS['modelid'];
		} else {
			$modelid = $cate_config['modelid'];
		}

		if(isset($GLOBALS['submit'])) {
			$formdata = $GLOBALS['form'];
			
			
			//插入时间，更新时间，如果用户设置了时间。则按照用户设置的时间
			$addtime = empty($formdata['addtime']) ? SYS_TIME : strtotime($formdata['addtime']);
			//添加数据之前，将用户提交的数据按照字段的配置，进行处理
			require get_cache_path('content_add','model');
			$form_add = new form_add($modelid);
			$formdata = $form_add->execute($formdata);
			$formdata['master_data']['addtime'] = $addtime;
			$formdata['master_data']['updatetime'] = SYS_TIME;
			//如果是共享模型，那么需要在将字段modelid增加到数据库
			if($formdata['master_table']=='content_share') {
				$formdata['master_data']['modelid'] = $modelid;
			}
			$formdata['master_data']['cid'] = $cid;
			//默认状态 status ,9为通过审核，1-4为审核的工作流，0为回收站
			$formdata['master_data']['status'] = isset($GLOBALS['form']['status']) ? intval($GLOBALS['form']['status']) : 9;
			//非超级管理员，验证该栏目是否设置了审核
			if($cate_config['workflowid'] && $_SESSION['role']!=1 && in_array($formdata['master_data']['status'],array(9,8))) {
				$formdata['master_data']['status'] = 9;
			}
			//如果 route为 0 默认，1，加密，2外链 ，3，自定义 例如：wuzhicms-diy-url-example 用户，不能不需要自己写后缀。程序自动补全。
			$formdata['master_data']['route'] = intval($GLOBALS['form']['route']);
			//标题样式
			$title_css = preg_match('/([a-z0-9]+)/i',$GLOBALS['title_css']) ? $GLOBALS['title_css'] : '';
			//$formdata['master_data']['css'] = $title_css;
			// var_dump($formdata['master_table']);
            // var_dump($formdata);die;
			if($cate_config['type']) {
				$urls['url'] = $cate_config['url'];
			} elseif($formdata['master_data']['route']>1) {//外部链接/或者自定义链接
				$urls['url'] = remove_xss($GLOBALS['url']);
			} else {
				//生成url
				$urlclass = load_class('url','content',$cate_config);
				$productid = 0;
				if(isset($formdata['master_data']['productid'])) $productid = $formdata['master_data']['productid'];
				$urls = $urlclass->showurl(array('id'=>$id,'cid'=>$cid,'addtime'=>$addtime,'page'=>1,'route'=>$formdata['master_data']['route'],'productid'=>$productid));
			}
			$formdata['master_data']['url'] = $urls['url'];

			if(empty($formdata['master_data']['remark']) && isset($formdata['attr_data']['content'])) {
				$formdata['master_data']['remark'] = mb_strcut(strip_tags($formdata['attr_data']['content']),0,255);
			}
		//区分省市区
			$province=$formdata['master_data']['areaid_1'];
			$city=$formdata['master_data']['areaid_2'];
            $qu=$formdata['master_data']['areaid'];
            $where6 = "`lid` in ($province,$city,$qu)";
            $area=$this->db->get_list('linkage_data', $where6);
           
       if($province<6||$province==34||$province==35){
            $provice1=$area[0]['name'];
            $city1=$area[2]['name'];
            $qu1=$area[1]['name'];
      		$q=$provice1.$city1.$qu1;
      		// var_dump($q);die;
            $formdata['master_data']['address']=$area[0]['name'].$area[1]['name'].$formdata['master_data']['address'];
            }else{
            	echo "aaa";
	            $provice1=$area[0]['name'];
	            $city1=$area[2]['name'];
	            $qu1=$area[1]['name'];
	            $formdata['master_data']['address']=$area[0]['name'].$area[1]['name'].$area[2]['name'].$formdata['master_data']['address'];
	           
            }
        
			$res = $this->db->get_one($formdata['master_table'], array('id' => $this->uid));
			

			$fields_configs = get_config('company_config');
			$words =  segment($formdata['master_data']['title']);
			if($words) {
				$search_data = implode(' ',$words);
			}
			$search_data .= ' '.$formdata['master_data']['title'];
			$typeids = $GLOBALS['form']['typeids'];
			foreach($typeids as $_v){
				if($_v=='no_value') continue;
				$search_data .= ' '.$fields_configs['typeids'][$_v];
			}
			$style = $GLOBALS['form']['style'];
			foreach($style as $_v){
				if($_v=='no_value') continue;
				$search_data .= ' '.$fields_configs['style'][$_v];
			}
			$tese = $GLOBALS['form']['tese'];
			foreach($style as $_v) {
				if($_v=='no_value') continue;
				$search_data .= ' '.$fields_configs['tese'][$_v];
			}
			$formdata['master_data']['search_data']=$search_data;


			if(empty($res)) {
				$id = $formdata['master_data']['id'] = $this->uid;//所属装修公司
				$this->db->insert($formdata['master_table'],$formdata['master_data']);
				//生成url
				$urlclass = load_class('url','content',$cate_config);
				$urls = $urlclass->showurl(array('id'=>$id,'cid'=>$cid,'addtime'=>$addtime,'page'=>1,'route'=>$formdata['master_data']['route']));
         
				$this->db->update($formdata['master_table'],array('url'=>$urls['url']),array('id'=>$id));
				if(!empty($formdata['attr_table'])) {
					$formdata['attr_data']['id'] = $id;
					$this->db->insert($formdata['attr_table'],$formdata['attr_data']);
				}
				$formdata['master_data']['url'] = $urls['url'];
				//执行更新
				require get_cache_path('content_update','model');
				$form_update = new form_update($modelid);
				$data = $form_update->execute($formdata);
				//统计表加默认数据
				$this->db->insert('content_rank',array('cid'=>$cid,'id'=>$id,'updatetime'=>SYS_TIME));

			} else {
				$dz = $this->db->get_one('company', array('id' => $uid),'areaid_1,areaid_2,areaid,address');
				$tj=$dz['areaid_1'].','.$dz['areaid_2'].','.$dz['areaid'];
				
				$where1 = "`lid` in ($tj)";
				$dz1 = $this->db->get_list('linkage_data', $where1, 'lid,name');
				$nd=$dz1[0]['name'].$dz1[1]['name'].$dz1[2]['name'];
			
				
				
	           

				$this->db->update($formdata['master_table'],$formdata['master_data'],array('id'=>$id));
					
				if(!empty($formdata['attr_table'])) {
                     
					$this->db->update($formdata['attr_table'],$formdata['attr_data'],array('id'=>$id));
				    }
          
				//执行更新
				require get_cache_path('content_update','model');
				$form_update = new form_update($modelid);

				$formdata['master_data']['id'] = $id;
				$form_update->execute($formdata);
			}

			$forward = HTTP_REFERER;
			MSG(L('update success'),$forward,1000);
		} else {
			$models = get_cache('model_content','model');
			$model_r = $models[$modelid];
			$master_table = $model_r['master_table'];
			$data = $this->db->get_one($master_table,array('id'=>$id));

			if($model_r['attr_table']) {
				$attr_table = $model_r['attr_table'];
				if($data['modelid']) {
					$modelid = $data['modelid'];
					$attr_table = $models[$modelid]['attr_table'];
				}

				$attrdata = $this->db->get_one($attr_table,array('id'=>$id));

				if($attrdata) $data = array_merge($data,$attrdata);
			}

			load_function('template');
			load_function('content','content');
			$status = isset($GLOBALS['status']) ? intval($GLOBALS['status']) : 9;
			require get_cache_path('content_form','model');
			$form_build = new form_build($modelid);
			$form_build->cid = $cid;
			$category = get_cache('category','content');
			$form_build->extdata['catname'] = $cate_config['name'];
			$form_build->extdata['type'] = $cate_config['type'];

			$formdata = $form_build->execute($data);

			load_class('form');
			$show_formjs = 1;
			$show_dialog = 1;
			$field_list = '';
			if(is_array($formdata['0'])) {
				foreach($formdata['0'] as $field=>$info) {
					if($info['powerful_field'] || $info['ban_contribute']==0) continue;
					if($info['formtype']=='powerful_field') {
						foreach($formdata['0'] as $_fm=>$_fm_value) {
							if($_fm_value['powerful_field']) {
								$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'],$info['form']);
							}
						}
						foreach($formdata['1'] as $_fm=>$_fm_value) {
							if($_fm_value['powerful_field']) {
								$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'],$info['form']);
							}
						}
					}
					$field_list[] = $info;
				}
			}
			$domain = $data['domain'];
			$data['title'] = $this->memberinfo['companyname'];
			//print_r($data['areaids']);
			include T('company','setting');
		}
    }

    public function setting2() {
    	// echo "2222222";die;
        $memberinfo = $this->memberinfo;
        $uid = $memberinfo['uid'];
		$id = $uid;
		
		$cid = 1;
		$cate_config = get_cache('category_'.$cid,'content');
		if(!$cate_config) MSG(L('category not exists'));
		//如果设置了modelid，那么则按照设置的modelid。共享模型添加必须数据必须指定该值。
		if(isset($GLOBALS['modelid']) && is_numeric($GLOBALS['modelid'])) {
			$modelid = $GLOBALS['modelid'];
		} else {
			$modelid = $cate_config['modelid'];
		}

		if(isset($GLOBALS['submit'])) {
			$formdata = $GLOBALS['form'];
			$puinfo['thumb']=$formdata['thumb'];
			$puinfo['companylogo']=$formdata['companylogo'];

			$coninfo['content'] = $formdata['content'];

			$this->db->update('company',$puinfo,array('id'=>$id));
			$this->db->update('company_data',$coninfo,array('id'=>$id));
			/*var_dump($formdata);
			die;*/

			$forward = HTTP_REFERER;
			MSG(L('update success'),$forward,1000);
		} else {
			$models = get_cache('model_content','model');
			$model_r = $models[$modelid];
			$master_table = $model_r['master_table'];
			$data = $this->db->get_one($master_table,array('id'=>$id));

			if($model_r['attr_table']) {
				$attr_table = $model_r['attr_table'];
				if($data['modelid']) {
					$modelid = $data['modelid'];
					$attr_table = $models[$modelid]['attr_table'];
				}

				$attrdata = $this->db->get_one($attr_table,array('id'=>$id));

				if($attrdata) $data = array_merge($data,$attrdata);
			}

			load_function('template');
			load_function('content','content');
			$status = isset($GLOBALS['status']) ? intval($GLOBALS['status']) : 9;
			require get_cache_path('content_form','model');
			$form_build = new form_build($modelid);
			$form_build->cid = $cid;
			$category = get_cache('category','content');
			$form_build->extdata['catname'] = $cate_config['name'];
			$form_build->extdata['type'] = $cate_config['type'];

			$formdata = $form_build->execute($data);

			load_class('form');
			$show_formjs = 1;
			$show_dialog = 1;
			$field_list = '';
			if(is_array($formdata['0'])) {
				foreach($formdata['0'] as $field=>$info) {
					if($info['powerful_field'] || $info['ban_contribute']==0) continue;
					if($info['formtype']=='powerful_field') {
						foreach($formdata['0'] as $_fm=>$_fm_value) {
							if($_fm_value['powerful_field']) {
								$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'],$info['form']);
							}
						}
						foreach($formdata['1'] as $_fm=>$_fm_value) {
							if($_fm_value['powerful_field']) {
								$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'],$info['form']);
							}
						}
					}
					$field_list[] = $info;
				}
			}
			$domain = $data['domain'];
			$data['title'] = $this->memberinfo['companyname'];
			//print_r($data['areaids']);
			include T('company','setting');
		}


    }

	public function save_domain() {
		$domain = $GLOBALS['domain'];
		$memberinfo = $this->memberinfo;
		if(!preg_match('/([a-z0-9]+)/i',$domain)) exit('域名格式错误！');
		$r = $this->db->get_one('company', array('domain' => $domain));
		if($r && $r['id']!=$this->uid) {
			MSG('域名不已经被占用，换个试试吧!');
		} else {
			$formdata = array('domain'=>$domain);
			$this->db->update('company', $formdata, array('id' => $this->uid));
			MSG('更新成功！',HTTP_REFERER);
		}
	}

	public function companyInformation(){
		$memberinfo = $this->memberinfo;
        $uid = $memberinfo['uid'];
        if ($GLOBALS['submit2']) {
        	$formdata['fzrxm'] = remove_xss($GLOBALS['form']['fzrxm']);
			$formdata['LK1_1'] = remove_xss($GLOBALS['LK1_1']);
			$formdata['LK1_2'] = remove_xss($GLOBALS['LK1_2']);
			$formdata['LK1_3'] = remove_xss($GLOBALS['LK1_3']);
			$formdata['one_text'] = $GLOBALS['form']['one_text'];
			$formdata['companyIntroduce'] = $GLOBALS['companyIntroduce'];
			
			$service['service_area'] = $GLOBALS['form']['areaids'];
			$formdata['service_area'] = implode(',', $service['service_area']);
			// var_dump($formdata['service_area']);die;
			$formdata['service_provice'] = $GLOBALS['form']['serviec_1'];
			$formdata['service_city'] = $GLOBALS['form']['serviec_2'];
			$formdata['gszj']=$GLOBALS['form']['gszj'];
			// var_dump($formdata);die;
			$this->db->update('member_company_data',$formdata,array('uid'=>$uid));
			// var_dump($formdata);die;
        }
    	$formdata['content'] = remove_xss($GLOBALS['form']['content']);
        // var_dump($formdata['content']);
        $r = $this->db->get_one('member_company_data',array('uid'=>$uid));
    	$phone = $this->db->get_one('member',array('uid'=>$uid),'mobile,email');
    	$p=$phone['mobile'];
    	$e=$phone['email'];
        
		include T('company','showInformation');
	}

	// 编辑公司信息
	public function editCompanyInfo(){
		$formdata['fzrxm'] = remove_xss($GLOBALS['form']['fzrxm']);
		$formdata['LK1_1'] = remove_xss($GLOBALS['LK1_1']);
		$formdata['LK1_2'] = remove_xss($GLOBALS['LK1_2']);
		$formdata['LK1_3'] = remove_xss($GLOBALS['LK1_3']);
		$formdata['one_text'] = $GLOBALS['form']['one_text'];
		// $this->db->update('member_company_data',$formdata,array('fromid'=>$fromid));
	}

	public function getCompanyArea(){
		$memberinfo = $this->memberinfo;
        $uid = $memberinfo['uid'];

		$where = "`uid`=$uid";
		$result = $this->db->get_list('member_company_data', $where, 'LK1_1,LK1_2,LK1_3');
		$pro = $result[0]['LK1_1'];
		$city = $result[0]['LK1_2'];
		$country = $result[0]['LK1_3'];

		$where1 = "`lid` in ({$pro},{$city},{$country})";
		$result1 = $this->db->get_list('linkage_data', $where1, 'lid,name');
		// var_dump($result1,"<br>");
		die(json_encode($result1));
	}

	// 获取父级ID为parentId的值市，地区
	public function getCityOrCountry(){
		$parentId=$GLOBALS['parentId'];
		$where = "`pid`='$parentId'";
		$result = $this->db->get_list('linkage_data', $where, 'lid,name');
		echo json_encode($result);
	}

	public function BelongArea(){
		$memberinfo = $this->memberinfo;
        $uid = $memberinfo['uid'];

		$where = "`uid`=$uid";
		$result = $this->db->get_list('member_company_data', $where, 'BelongProvince,BelongCity,BelongCounty');
		$pro = $result[0]['BelongProvince'];
		$city = $result[0]['BelongCity'];
		$country = $result[0]['BelongCounty'];

		$where1 = "`lid` in ({$pro},{$city},{$country})";
		$result1 = $this->db->get_list('linkage_data', $where1, 'lid,name');
		// var_dump($result1,"<br>");
		die(json_encode($result1));
	}

	// 获取服务区域中下拉框的值
	public function getSer(){
		$memberinfo = $this->memberinfo;
        $uid = $memberinfo['uid'];

        $where = "`uid`=$uid";
		$result = $this->db->get_list('member_company_data', $where, 'service_provice,service_city');
		$pro = $result[0]['service_provice'];
		$city = $result[0]['service_city'];

		$where1 = "`lid` in ({$pro},{$city})";
		$result1 = $this->db->get_list('linkage_data', $where1, 'lid,name');
		// var_dump($result1,"<br>");
		die(json_encode($result1));
	}

	// 获取服务区域中的checkbox的值
	public function getServiceArea(){
		$memberinfo = $this->memberinfo;
        $uid = $memberinfo['uid'];
		// 查询条件
		$where = "`uid`=$uid";
		$result = $this->db->get_list('member_company_data', $where, 'service_city');
		$serviceCityId = $result[0]['service_city'];
		$where1 = "`pid`=$serviceCityId";
		$result1 = $this->db->get_list('linkage_data', $where1, 'lid,name');
		echo json_encode($result1);
	}

	/*
		服务区域发生change事件时执行的方法	
	 */
	public function companyThreeLevel(){
		$pid = isset($GLOBALS['pid'])?intval($GLOBALS['pid']):0;
		$result = $this->db->get_list('linkage_data', array('pid'=>$pid), 'lid,name', 0, 200, 0,"sort ASC,lid ASC");
		die(json_encode($result));
	}

	/*
	获取选中的服务区域
	 */
	public function getConcreteArea(){
		$memberinfo = $this->memberinfo;
        $uid = $memberinfo['uid'];
		// $uid = 243;
		$where = "`uid`=$uid";
		$result = $this->db->get_list('member_company_data', $where, 'service_area');
		$r = $this->db->get_one('member',array('uid'=>$uid),"settled_progress");
		$result[0]['settled_progress'] = $r['settled_progress'];
		// var_dump($result[0]);
		echo json_encode($result);
	}
}