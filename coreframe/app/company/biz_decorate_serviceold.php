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
 * 申请装修服务
 */
load_class('foreground', 'member');
class biz_decorate_service extends WUZHI_foreground {
	function __construct() {
        $this->member = load_class('member', 'member');
        load_function('common', 'member');
        $this->member_setting = get_cache('setting', 'member');
        parent::__construct();
	}


	/**
	 * 添加
	 */
	public function add() {
		//$comp =array();
		$comp = $GLOBALS['item'];
        
		//var_dump($comp);die;
		if(!empty($comp)){
		   $comp =serialize($comp);
		}else{
			$comp = null;
		}

		$memberinfo = $this->memberinfo;
		/*
		if($memberinfo['ischeck_mobile']==0) {
			MSG('您的手机还未验证！请先验证！','index.php?m=member&f=index&v=edit_mobile',3000);
		}
		*/
		$cid = 135;
		$uid = $memberinfo['uid'];
		if(isset($GLOBALS['submit'])) {

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
			$formdata['master_data']['status'] = 1;

			//如果 route为 0 默认，1，加密，2外链 ，3，自定义 例如：wuzhicms-diy-url-example 用户，不能不需要自己写后缀。程序自动补全。
			$formdata['master_data']['route'] = 0;
			//$formdata['master_data']['companyname'] = $comp;
			$formdata['master_data']['addtime'] = date('Y-m-d G:i:s',SYS_TIME);

			$formdata['master_data']['publisher'] = $memberinfo['username'];
			$formdata['master_data']['uid'] = $uid;

			//echo $formdata['master_table'];exit;
			if(empty($formdata['master_data']['remark']) && isset($formdata['attr_data']['content'])) {
				$formdata['master_data']['remark'] = mb_strcut(strip_tags($formdata['attr_data']['content']),0,255);
			}
			$formdata['master_data']['order_no'] = date('YmdH').rand(100,999).date('is');
			//var_dump($formdata);die;
			//var_dump($formdata['master_data']);die;
			$id = $this->db->insert($formdata['master_table'],$formdata['master_data']);
			//生成url
			$urlclass = load_class('url','content',$cate_config);
			$urls = $urlclass->showurl(array('id'=>$id,'cid'=>$cid,'addtime'=>$addtime,'page'=>1,'route'=>$formdata['master_data']['route']));
			$order_no = '1'.str_pad($id,9,0,STR_PAD_LEFT);
			$this->db->update($formdata['master_table'],array('url'=>$urls['url'],'order_no'=>$order_no,'source'=>'用户中心','nodeid'=>0,'nodename'=>'订单待确认'),array('id'=>$id));
            
			$result = $this->db->get_one('demand',array('id'=>$id),'*');
            $comp = $GLOBALS['item'];
            $comID=$comp['comID'];
            $comName=$comp['comName'];
			for ($i=0; $i<count($comID); $i++) { 
				$cdata['orderid'] = $id;
			    $cdata['companyid']= $comID[$i];
                $cdata['companyname'] = $comName[$i];
			    $cdata['orderno'] = $result['order_no'];
			    $this->db->insert('demand_company',$cdata);
			}
			//var_dump($lid);die;

			if(!empty($formdata['attr_table'])) {
				$formdata['attr_data']['id'] = $id;
				// print_r($formdata['attr_data']);exit;
				$this->db->insert($formdata['attr_table'],$formdata['attr_data']);
			}
			$formdata['master_data']['url'] = $urls['url'];
			//执行更新
			require get_cache_path('content_update','model');
			$form_update = new form_update($modelid);
			$data = $form_update->execute($formdata);

			$d1 = date('Y-m-d',SYS_TIME).' 8:00:00';
			$d2 = date('Y-m-d',SYS_TIME).' 18:00:00';
			//$d3 = date('Y-m-d',SYS_TIME).' 23:59:59';
			$d1 = strtotime($d1);
			$d2 = strtotime($d2);
			//$d3 = strtotime($d3);
			if(SYS_TIME>$d1 && SYS_TIME<$d2) {
				$msg = '您的装修申请已经提交，客服会在2小时内与您联系，请您保持手机畅通！';
			} else {
				$msg = '您的装修申请已经提交，客服会在明日12：00前与您联系，请您耐心等待！';
			} /*else {
				$msg = '明日12点前我们的客服将会与您取得联系，请保持手机畅通。';
			}*/


			//申请装修服务不需要 统计入库
			//$this->db->insert('content_rank',array('cid'=>$cid,'id'=>$id,'updatetime'=>SYS_TIME));

			//邮件发送
			$config = get_cache('sendmail');
			$siteconfigs = get_cache('siteconfigs');
			$password = decode($config['password']);
			//load_function('sendmail');
			$t = date('YmdHis');
			$subject = '有新的装修申请';
			$message = $formdata['title']."电话：".$formdata['telephone'].date('Y-m-d H:i:s',SYS_TIME);
			$message .= "请尽快审批！";
			$mail = load_class('sendmail');
			$mail->setServer($config['smtp_server'], $config['smtp_user'], $password); //设置smtp服务器，普通连接方式
			$mail->setFrom($config['send_email']); //设置发件人
			$mail->setReceiver('kf@uzhuang.com'); //设置收件人，多个收件人，调用多次
			$mail->setMail($subject, $message); //设置邮件主题、内容
			$mail->sendMail(); //发送

			//MSG($msg,'?m=company&f=biz_decorate_service&v=decorate_order&type=1',5000);
			MSG($msg,'?m=order1&f=address1&v=listing',5000);
		} else {
			load_function('content','content');


			$model_r = get_cache('field_1003','model');

			load_function('template');
			$status = 9;
			require get_cache_path('content_form','model');
			$form_build = new form_build(1002);
			$form_build->cid = 135;
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
			include T('company','decorate_service_add');
		}
	}

	/**
	 * 装修订单
	 */
	public function decorate_order() {
		$where = '';
		$username = get_cookie('_username');
		$type = intval($GLOBALS['type']);
		if($type==0) {
			$where = array('publisher'=>$username,'progress'=>4);
		} else {
			$where = "publisher='$username' AND progress!=4";
		}

		$result = $this->db->get_list('demand', $where, '*', 0, 20, 0, 'id DESC');
		$total = $this->db->count_result('demand',array('publisher'=>$username,'progress'=>4));
		$total2 = $this->db->count_result('demand', "publisher='$username' AND progress!=4");
		include T('company','decorate_order');
	}

	/**
	 * 申请装修服务
	 */
	public function insurance() {
			$memberinfo = $this->memberinfo;
			$cid = 138;
			if(!$cid) {
				MSG('您的账户没有绑定到品牌，请联系客服！');
			}
			$uid = $memberinfo['uid'];
			if(isset($GLOBALS['submit'])) {

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
				$formdata['master_data']['status'] = 1;

				//如果 route为 0 默认，1，加密，2外链 ，3，自定义 例如：wuzhicms-diy-url-example 用户，不能不需要自己写后缀。程序自动补全。
				$formdata['master_data']['route'] = 0;
				$formdata['master_data']['publisher'] = $memberinfo['username'];

				//echo $formdata['master_table'];exit;
				if(empty($formdata['master_data']['remark']) && isset($formdata['attr_data']['content'])) {
					$formdata['master_data']['remark'] = mb_strcut(strip_tags($formdata['attr_data']['content']),0,255);
				}
				$id = $this->db->insert($formdata['master_table'],$formdata['master_data']);
				//生成url
				$urlclass = load_class('url','content',$cate_config);
				$urls = $urlclass->showurl(array('id'=>$id,'cid'=>$cid,'addtime'=>$addtime,'page'=>1,'route'=>$formdata['master_data']['route']));

				$this->db->update($formdata['master_table'],array('url'=>$urls['url']),array('id'=>$id));
				if(!empty($formdata['attr_table'])) {
					$formdata['attr_data']['id'] = $id;
					// print_r($formdata['attr_data']);exit;
					$this->db->insert($formdata['attr_table'],$formdata['attr_data']);
				}
				$formdata['master_data']['url'] = $urls['url'];
				//执行更新
				require get_cache_path('content_update','model');
				$form_update = new form_update($modelid);
				$data = $form_update->execute($formdata);

				//统计表加默认数据
				$this->db->insert('content_rank',array('cid'=>$cid,'id'=>$id,'updatetime'=>SYS_TIME));
				MSG('申请已提交，我们将在24小时内进行审核！');
		} else {
			$username = $this->memberinfo['username'];
			$GLOBALS['publisher'] = $username;
			load_function('content','content');
			$model_r = get_cache('field_1003','model');

			load_function('template');
			$status = 9;
			require get_cache_path('content_form','model');
			$form_build = new form_build(1006);
			$form_build->cid = 138;
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

				$where = "publisher='$username'";
				$result = $this->db->get_list('insurance', $where, '*', 0, 20, 0, 'id DESC');
			include T('company','insurance_listing');
		}

	}

     
	

	 public function listing() {
        $memberinfo = $this->memberinfo;
        $page = isset($GLOBALS['page']) ? intval($GLOBALS['page']) : 1;
        $page = max($page,1);
        if(isset($GLOBALS['keywords'])) {
            $keywords = $GLOBALS['keywords'];
            $where = "title LIKE '%$keywords%'";
        } else {
          $where = '';
        }
       $result = $this->db->get_list('company', $where, '*', 0, 5,$page,'addtime DESC');
       $pages = $this->db->pages;
       $total = $this->db->number;
       include T('company','decorate_service_add');
    }

    //public function add1(){



    	//$ids_str = serialize($GLOBALS['ids']);
    	//var_dump($ids_str);exit;
    	//echo "<hr/>";
    	//var_dump(unserialize($ids_str));
    	//echo "<hr/>";

    	//var_dump($GLOBALS['ids']);exit;
    	//$formdata = array();
    	//$formdata['iscompany'] = remove_xss($GLOBALS['id']);
    	/*
    	$companyData = array();
    	$companyData[] = array('company_id'=>2,'title'=>'东易日盛');
    	$companyData[] = array('company_id'=>3,'title'=>'优装网');
    	$companyData[] = array('company_id'=>4,'title'=>'装修网');
		
		$result = json_encode($companyData);
		$result = serlize($companyData);
		$result = serlize($companyData);
    	 */	

        //$formdata['iscompany'] = remove_xss($ids_str);
    	//$this->db->insert('demand',$formdata);
    	//include T('company','decorate_service_add');
	//}

	public function searchCompany(){
		$cname = $GLOBALS['cname'];
		$where = "`companyname` LIKE '%$cname%'";
		$result = $this->db->get_list('member_company_data', $where, '*');
		echo json_encode($result);
	}

	public function ttt(){
		$v = $GLOBALS['cname'];
		$where = "`companyname` LIKE '%$v%'";
		$result = $this->db->get_list('member_company_data', $where, 'id,companyname');
		$title = array();
		for($i=0;$i<count($result);$i++){
			$title[]=$result[$i]['companyname'];
		}
		echo json_encode($title);
		// echo $v;
		/*echo "正";
		die;*/
	}
}
