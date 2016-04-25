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
 * 优惠活动
 */
load_class('foreground', 'member');
class biz_market_sale extends WUZHI_foreground {
	function __construct() {
        $this->member = load_class('member', 'member');
        load_function('common', 'member');
        $this->member_setting = get_cache('setting', 'member');
        parent::__construct();
        //企业用户权限检查
        if($this->memberinfo['modelid']!=11 || !$this->memberinfo['checkmec']) {
            MSG('您的帐号还未通过企业认证审核！如需帮助请联系客服。');
        }
	}

	/**
	 * 列表
	 */
	public function listing() {
		$memberinfo = $this->memberinfo;
		$uid = $memberinfo['uid'];
		

		$r = $this->db->get_one('company', array('id' => $uid));
		if($r==''){
			MSG('公司信息未完善,请补全公司信息！','index.php?m=company&f=biz_setting&v=setting',1000);
		}
		
		$publisher = $memberinfo['username'];
		$categorys = get_cache('category','content');
		$page = intval($GLOBALS['page']);
		$page = max($page,1);

		$where = "`publisher`='$publisher'";

		$result = $this->db->get_list('market_sale',$where, '*', 0, 20,$page,'id DESC');
		$pages = $this->db->pages;
		$total = $this->db->number;
		$status_arr = array('已删除','待审核','审核中','审核中','','','','','','审核通过');
		include T('company','market_sale_listing');
		// include T('company','market_sale_add');
	}

	/**
	 * 设计师添加
	 */
	public function add() {
		$memberinfo = $this->memberinfo;
		$uid = $memberinfo['uid'];
		$r = $this->db->get_one('company', array('id' => $uid));
		if($r==''){
			MSG('公司信息未完善,请补全公司信息！','index.php?m=company&f=biz_setting&v=setting',1000);
		}
		/*
			if($memberinfo['ischeck_mobile']==0) {
				MSG('您的手机还未验证！请先验证！','index.php?m=member&f=index&v=edit_mobile',3000);
			}
		*/
		
		$cid = 137;
		if(!$cid) {
			MSG('您的账户没有绑定到品牌，请联系客服！');
		}
		$uid = $memberinfo['uid'];
		/*添加*/
		$page = intval($GLOBALS['page']);
		$page = max($page,1);
		$where = "`publisher`='$publisher'";
		$result = $this->db->get_list('market_sale',$where, '*', 0, 1,$page,'id DESC');
		/*结束添加*/
		if(isset($GLOBALS['submit'])) {
			// echo "1";
			$cate_config = get_cache('category_'.$cid,'content');
			if(!$cate_config) MSG(L('category not exists'));
			//如果设置了modelid，那么则按照设置的modelid。共享模型添加必须数据必须指定该值。
			if(isset($GLOBALS['modelid']) && is_numeric($GLOBALS['modelid'])) {
				$modelid = $GLOBALS['modelid'];
			} else {
				$modelid = $cate_config['modelid'];
			}

			$formdata = $GLOBALS['form'];
			$formdata['title'] = remove_xss($formdata['title']);
			//添加数据之前，将用户提交的数据按照字段的配置，进行处理
			require get_cache_path('content_add','model');
			$form_add = new form_add($modelid);
			$formdata = $form_add->execute($formdata);

			//插入时间，更新时间，如果用户设置了时间。则按照用户设置的时间
			$addtime = empty($formdata['addtime']) ? SYS_TIME : strtotime($formdata['addtime']);
			$formdata['master_data']['addtime'] = $formdata['master_data']['updatetime'] = $addtime;
			//如果是共享模型，那么需要在将字段modelid增加到数据库
			if($formdata['master_table']=='content_share') {
				$formdata['master_data']['modelid'] = $modelid;
			}
			$formdata['master_data']['cid'] = $cid;
			//默认状态 status ,9为通过审核，1-4为审核的工作流，0为回收站
			$formdata['master_data']['status'] = 9;

			//如果 route为 0 默认，1，加密，2外链 ，3，自定义 例如：wuzhicms-diy-url-example 用户，不能不需要自己写后缀。程序自动补全。
			$formdata['master_data']['route'] = 0;
			$formdata['master_data']['publisher'] = $memberinfo['username'];

			//echo $formdata['master_table'];exit;
			if(empty($formdata['master_data']['remark']) && isset($formdata['attr_data']['content'])) {
				$formdata['master_data']['remark'] = mb_strcut(strip_tags($formdata['attr_data']['content']),0,255);
			}
			//处理前台特殊字段

			$formdata['master_data']['companyid'] = $this->uid;//所属装修公司
			
			// var_dump($formdata['master_data']);die;
			//  向wz_market_sale表中插入数据
			$id = $this->db->insert($formdata['master_table'],$formdata['master_data']);
			// $result = $this->db->get_list('market_sale', '*', 0, 1,$page,'id DESC');
			// var_dump($result);die;
			//生成url
			$urlclass = load_class('url','content',$cate_config);
			$urls = $urlclass->showurl(array('id'=>$id,'cid'=>$cid,'addtime'=>$addtime,'page'=>1,'route'=>$formdata['master_data']['route']));

			$this->db->update($formdata['master_table'],array('url'=>$urls['url']),array('id'=>$id));
			if(!empty($formdata['attr_table'])) {
				$formdata['attr_data']['id'] = $id;
				// var_dump($formdata['attr_data']);exit;
				$this->db->insert($formdata['attr_table'],$formdata['attr_data']);
			}
			$formdata['master_data']['url'] = $urls['url'];
			//执行更新
			require get_cache_path('content_update','model');
			$form_update = new form_update($modelid);
			$data = $form_update->execute($formdata);

			//统计表加默认数据
			$this->db->insert('content_rank',array('cid'=>$cid,'id'=>$id,'updatetime'=>SYS_TIME));
			// $where = "`id`='$publisher'";
			MSG('信息发布成功','?m=company&f=biz_market_sale&v=add',1500);
		} else {
			// echo "2";die;
			$GLOBALS['companyid'] = $uid;
			load_function('content','content');


			$model_r = get_cache('field_1005','model');
			load_function('template');
			$status = 1;
			require get_cache_path('content_form','model');
			$form_build = new form_build(1005);
			$form_build->cid = 137;
			$category = get_cache('category','content');
			$form_build->extdata['catname'] = '';
			$form_build->extdata['type'] = '0';
			$formdata = $form_build->execute();
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


			$uid = $memberinfo['uid'];
			$publisher = $memberinfo['username'];
			$categorys = get_cache('category','content');
			$page = intval($GLOBALS['page']);
			$page = max($page,1);

			$where = "`publisher`='$publisher'";

			$result = $this->db->get_list('market_sale',$where, '*', 0, 1,$page,'id DESC');
			$pages = $this->db->pages;
			$total = $this->db->number;
			// var_dump($result[0]['id']);die;
			// $status_arr = array('已删除','待审核','审核中','审核中','','','','','','审核通过');
			// var_dump($result[0]['content']);die;



			include T('company','market_sale_add');
		}
	}
	/**
	 * 列表
	 */
	public function edit1() {
		echo "编辑";die;
		$memberinfo = $this->memberinfo;
		$uid = $memberinfo['uid'];
		$id = intval($GLOBALS['id']);
		$cid = 137;
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
			$formdata['master_data']['status'] = 9;
			//非超级管理员，验证该栏目是否设置了审核
			if($cate_config['workflowid'] && $_SESSION['role']!=1 && in_array($formdata['master_data']['status'],array(9,8))) {
				$formdata['master_data']['status'] = 1;
			}
			//如果 route为 0 默认，1，加密，2外链 ，3，自定义 例如：wuzhicms-diy-url-example 用户，不能不需要自己写后缀。程序自动补全。
			$formdata['master_data']['route'] = intval($GLOBALS['form']['route']);
			//标题样式
			$title_css = preg_match('/([a-z0-9]+)/i',$GLOBALS['title_css']) ? $GLOBALS['title_css'] : '';
			$formdata['master_data']['css'] = $title_css;

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

			$this->db->update($formdata['master_table'],$formdata['master_data'],array('id'=>$id));
			if(!empty($formdata['attr_table'])) {
				$this->db->update($formdata['attr_table'],$formdata['attr_data'],array('id'=>$id));
			}

			//执行更新
			require get_cache_path('content_update','model');
			$form_update = new form_update($modelid);

			$formdata['master_data']['id'] = $id;
			$form_update->execute($formdata);
			$forward = '?m=company&f=biz_market_sale&v=listing';
			MSG(L('update success'),$forward,1000);
		} else {
			$GLOBALS['companyid'] = $uid;
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

				$data = array_merge($data,$attrdata);
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
			include T('company','market_sale_edit');
		}
	}

	public function edit() {
		
		$memberinfo = $this->memberinfo;
        // $fromid = intval($GLOBALS['editId']);
        $uid = $memberinfo['uid'];
        // var_dump($uid);die;
        if(isset($GLOBALS['submit'])) {
            $formdata = array();
            $formdata['title'] = remove_xss($GLOBALS['form']['title']);
            $formdata['particulars'] = remove_xss($GLOBALS['form']['particulars']);
            $formdata['thumb'] = remove_xss($GLOBALS['form']['thumb']);

            $this->db->update('market_sale',$formdata,array('companyid'=>$uid));
            MSG(L('operation success'),HTTP_REFERER);
        } 
        /*else {
            $show_formjs = 1;
            $form = load_class('form');
            $r = $this->db->get_one('copyfrom',array('fromid'=>$fromid));
            include $this->template('copyfrom_edit');
        }*/
    }
	/*public function delete1(){
		echo "shanchu";die;
		$memberinfo = $this->memberinfo;
		$publisher = $memberinfo['username'];
		$id = intval($GLOBALS['id']);
		var_dump($id);
		// var_dump($id);die;
		// var_dump($GLOBALS['id']);die;
		if(!$id) MSG('参数错误');
		$r = $this->db->get_one('market_sale',array('id'=>$id));
		// var_dump($publisher);die;
		if($r && $r['publisher']==$publisher) {
			$this->db->update('market_sale',array('status'=>0),array('id'=>$id));
			$this->db->update('market_sale',array('status'=>0),array('id'=>$id));
		}
		MSG('删除成功',HTTP_REFERER);
	}*/

	public function delete(){
		// $fromid = isset($GLOBALS['fromid']) ? intval($GLOBALS['fromid']) : 0;
		$fromid = isset($GLOBALS['did']) ? intval($GLOBALS['did']) : 0;
        if(!$fromid) MSG(L('操作失败'));
        $this->db->delete('market_sale',array('id'=>$fromid));

        MSG(L('delete success'),HTTP_REFERER,1500);
	}
}