<?php
// +----------------------------------------------------------------------
// | wuzhicms [ 五指互联网站内容管理系统 ]
// | Copyright (c) 2014-2015 http://www.wuzhicms.com All rights reserved.
// | Licensed ( http://www.wuzhicms.com/licenses/ )
// | Author: wangcanjia <phpip@qq.com>
// +----------------------------------------------------------------------
defined('IN_WZ') or exit('No direct script access allowed');
/**
 * 设计师
 */
load_class('foreground', 'member');
class biz_homepage extends WUZHI_foreground {

	function __construct() {
        $this->member = load_class('member', 'member');
        load_function('common', 'member');
        $this->member_setting = get_cache('setting', 'member');
        #parent::__construct();
        //企业用户权限检查
        /*if($this->memberinfo['modelid']!=11 || !$this->memberinfo['checkmec']) {
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
	

	/**
	 * 列表
	 */
	public function listing() {
		$memberinfo = $this->memberinfo;
		$uid = $memberinfo['uid'];
        $com= $memberinfo['companyname'];

		$res = $this->db->get_one('member',array('uid'=>$uid),'settled_progress,modelid');
		$process=$res['settled_progress'];
		$modelid=$res['modelid'];
		/*if ($modelid==11&&$process==3) {
			$this->db->update('member',array('settled_progress'=>5),array('uid'=>$uid));
			echo "string";die;
		}*/
		// var_dump($process);die;
		if ($modelid==11&&$process!=5) {
			if ($process==3) {
				$this->db->update('member',array('settled_progress'=>5),array('uid'=>$uid));
			}else{
				// MSG('您还未通过审核请您耐心等候',HTTP_REFERER);
				$jhurl = WEBURL.'index.php?m=member&v=public_complete_information&uid='.$uid;
				echo '
					<script type="text/javascript">
						location.href="'.$jhurl.'";
					</script>
				';
			}
		}
        $status = intval($GLOBALS['orderstatus']);
        $status_arr = array();
        $status_arr[1] = '已完结的订单';
        $status_arr[2] = '施工中的订单';
        $status_arr[3] = '已暂停的订单';
        $status_arr[6] = '已终止的订单';
        $addressid = intval($GLOBALS['addressid']);
         //获取登录商户中心的公司id
        $r = $this->db->get_one('member_company_data',array('companyname'=>$com));
      
        // 获取company表中数据
       
        $r1 = $this->db->get_one('company',array('id'=>$uid));
       
        // print_r(explode(',',$r1['tese']));die;

        $keytype = isset($GLOBALS['keytype']) ? $GLOBALS['keytype'] : '';
        $keywords = isset($GLOBALS['keywords']) ? trim($GLOBALS['keywords']) : '';
        $start = isset($GLOBALS['start']) ? remove_xss($GLOBALS['start']) : '';
        $end= isset($GLOBALS['end']) ? remove_xss($GLOBALS['end']) : '';
        $page = isset($GLOBALS['page']) ? intval($GLOBALS['page']) : 1;
        $page = max($page,1);
		$result = $this->db->get_list('fymx', "`companyid`=$uid AND `isselected`='是'".$where, '*', 0, 10,$page);
        $result1 = $this->db->get_list('picture',"`companyid`=$uid".$where, '*', 0, 3,$page,'id');		
        $result3 = $this->db->get_list('company_team',"`companyid`=$uid".$where, '*', 0, 3,$page,'id');
      
        // 获取订单编号
		

        $c = count($result);
        $orno=array();
        for($i=0;$i<$c;$i++){
            $orno[]=$result[$i]['order_no'];
        }
        $jie=implode(',',$orno);
        // var_dump($jie);exit;
        //取各项金额
      if($jie!=''){
        $result2 = $this->db->get_list('demand_pay',"`order_no` in ($jie) ",'*', 0, 10,$page,'paydate desc');

          foreach ($result2 as $key => $value) {
                if(isset($aCom2row[$value['order_no']])){

                  $aCom2row[$value['order_no']]['extrapay'] += $value['extrapay'];
                   $aCom2row[$value['order_no']]['total'] += $value['total'];
              }else{
                      $aCom2row[$value['order_no']] = array(
                          'order_no' => $value['order_no'],
                          'iscompany' => $value['iscompany'],
                          'sheji' => $value['sheji'],
                          'paydate' => $value['paydate'],
                          'totalpay'=>$value['totalpay'],
                          'designpay'=>$value['designpay'],
                          'extrapay'=>$value['extrapay']
                       );
                }
            // var_dump($aCom2row[$value['order_no']]);exit;
            }

             //获得节点id
        $c1 = count($result2);
        $orno1=array();
        for($i=0;$i<$c1;$i++){
            $orno1[]=$result2[$i]['nodeid'];
        }
        rsort($orno1);
        $maxnodeid=(int)$orno1[0];
        if($maxnodeid>17){
          $sum = $this->db->get_list('demand_pay',"`order_no` in ($jie) AND `nodeid`>'17' AND `nodeid`<=$maxnodeid",'totalpay');
          $cd=count($sum);
          $sgf;
          for($i=0;$i<$cd;$i++){
          $sgf+=$sum[$i]['totalpay']*0.2;
        } 
       }

}
        // $count=array();
        // for($i=0;$i<count($result4);$i++){
        //           $count[]=$result4[$i]['sheji'];    
        // }
        // echo array_sum($count);exit;
       
        $where1 = $where = "`touid` ={$uid}";
        $messResult = $this->db->get_list('message',$where1, '*', 0, 10,$page,'id DESC');
        $messageNum=count($messResult);
        
   
		include T('company','home_listing');
	}

	public function index(){
		echo "this is test";exit;
	}
	/**
	 * 设计师添加
	 */
	public function add() {
		$memberinfo = $this->memberinfo;
		/*
		if($memberinfo['ischeck_mobile']==0) {
			MSG('您的手机还未验证！请先验证！','index.php?m=member&f=index&v=edit_mobile',3000);
		}
		*/
		$cid = 134;
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
			$formdata['master_data']['status'] = 9;

			//如果 route为 0 默认，1，加密，2外链 ，3，自定义 例如：wuzhicms-diy-url-example 用户，不能不需要自己写后缀。程序自动补全。
			$formdata['master_data']['route'] = 0;
			$formdata['master_data']['publisher'] = $memberinfo['username'];

			//echo $formdata['master_table'];exit;
			if(empty($formdata['master_data']['remark']) && isset($formdata['attr_data']['content'])) {
				$formdata['master_data']['remark'] = mb_strcut(strip_tags($formdata['attr_data']['content']),0,255);
			}
			//处理前台特殊字段
			$fields_configs = get_config('design_config');
			$words =  segment($formdata['master_data']['title']);
			if($words) {
				$search_data = implode(' ',$words);
			}
			$search_data .= ' '.$formdata['master_data']['title'];
			$years = $GLOBALS['form']['years'];
			$search_data .= ' '.$fields_configs['years'][$years];
			$search_data .= ' '.$formdata['master_data']['money_type'];

			$style = $GLOBALS['form']['style'];
			foreach($style as $_v){
				if($_v=='no_value') continue;
				$search_data .= ' '.$fields_configs['style'][$_v];
			}
			$formdata['master_data']['search_data']=$search_data;

			$formdata['master_data']['companyid'] = $this->uid;//所属装修公司

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
			MSG('设计师添加成功!','?m=company&f=biz_design&v=listing',1200);
		}
	}
	/**
	 * 列表
	 */
	public function edit() {
		$memberinfo = $this->memberinfo;
		$uid = $memberinfo['uid'];
		$id = intval($GLOBALS['id']);
		$cid = 134;
		$cate_config = get_cache('category_'.$cid,'content');
		if(!$cate_config) MSG(L('category not exists'));
		//如果设置了modelid，那么则按照设置的modelid。共享模型添加必须数据必须指定该值。
		if(isset($GLOBALS['modelid']) && is_numeric($GLOBALS['modelid'])) {
			$modelid = $GLOBALS['modelid'];
		} else {
			$modelid = $cate_config['modelid'];
		}
		$models = get_cache('model_content','model');
		$model_r = $models[$modelid];
		$master_table = $model_r['master_table'];
		$data = $this->db->get_one($master_table,array('id'=>$id));
		if($data['status']==0) MSG('内容已删除！不允许修改！');

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
			$words =  segment($formdata['master_data']['title']);
			if($words) {
				$search_data = implode(' ',$words);
			}
			$search_data .= ' '.$formdata['master_data']['title'];
			$years = $GLOBALS['form']['years'];
			$search_data .= ' '.$fields_configs['years'][$years];
			$search_data .= ' '.$formdata['master_data']['money_type'];

			$style = $GLOBALS['form']['style'];
			foreach($style as $_v){
				if($_v=='no_value') continue;
				$search_data .= ' '.$fields_configs['style'][$_v];
			}
			$formdata['master_data']['search_data']=$search_data;

			$this->db->update($formdata['master_table'],$formdata['master_data'],array('id'=>$id));
			if(!empty($formdata['attr_table'])) {
				$this->db->update($formdata['attr_table'],$formdata['attr_data'],array('id'=>$id));
			}

			//执行更新
			require get_cache_path('content_update','model');
			$form_update = new form_update($modelid);

			$formdata['master_data']['id'] = $id;
			$form_update->execute($formdata);
			$forward = '?m=company&f=biz_design&v=listing';
			MSG(L('update success'),$forward,1000);
		} else {
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
			include T('company','design_edit');
		}
	}

	public function delete(){
		$memberinfo = $this->memberinfo;
		$publisher = $memberinfo['username'];
		$id = intval($GLOBALS['id']);
		if(!$id) MSG('参数错误');
		$r = $this->db->get_one('company_team',array('id'=>$id));
		if($r && $r['publisher']==$publisher) {
			$this->db->update('company_team',array('status'=>0),array('id'=>$id));
		}
		MSG('删除成功',HTTP_REFERER);
	}
}