<?php
// +----------------------------------------------------------------------
// | wuzhicms [ 五指互联网站内容管理系统 ]
// | Copyright (c) 2014-2015 http://www.wuzhicms.com All rights reserved.
// | Licensed ( http://www.wuzhicms.com/licenses/ )
// | Author: wangcanjia <phpip@qq.com>
// +----------------------------------------------------------------------
defined('IN_WZ') or exit('No direct script access allowed');
/**
 * 管理
 */
load_class('admin');
class index extends WUZHI_admin {
	private $db;

	function __construct() {
		$this->db = load_class('db');
        $this->status_arr = array(1=>'注册会员',2=>'会员+游客',3=>'后台管理员');
	}
    /**
     * 导出
     */
    public function export_csv() {
		if(isset($GLOBALS['submit'])) {

			$formdata['starttime'] = strtotime($GLOBALS['starttime']);
			$formdata['endtime'] = strtotime($GLOBALS['endtime']);
			//excel 导出
			$cid = intval($GLOBALS['cid']);
			$category = $this->db->get_one('category', array('cid' => $cid));
			$models = get_cache('model_content','model');
			$modelid = $category['modelid'];

			$status = intval($GLOBALS['status']);
			$status = $status ? $status : 9;
			$master_table = $models[$modelid]['master_table'];
			$starttime = $formdata['starttime'];
			$endtime = $formdata['endtime'];
			$where = "`status`=$status AND `addtime`>$starttime AND `addtime`<$endtime";
			$result = $this->db->get_list($master_table, $where, '*', 0, 10000, 0, 'id DESC');
			$cache_field = get_cache('field_'.$modelid,'model');
			$new_field = array('id'=>array('name'=>'ID'));
			foreach($cache_field as $field=>$rs) {
				if(in_array($field,array('url','sort','template','thumb','cid'))) continue;
				if($field=='housetype') {
					$rs['name']='公装房屋类型';
				} elseif($field=='homestyle') {
					$rs['name']='家装房屋类型';
				}
				$new_field[$field] = $rs;
			}
			//print_r($new_field);exit;
			//print_r($cache_filed);exit;
			/** Include PHPExcel */
			$cell_field = array('','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			require_once COREFRAME_ROOT.'extend/class/PHPExcel.php';
// Create new PHPExcel object
			$objPHPExcel = new PHPExcel();
// Set document properties
			$objPHPExcel->getProperties()->setCreator("wuzhicms.com")
				->setLastModifiedBy("wuzhicms.com")
				->setTitle("uzhuang")
				->setSubject("uzhuang Document")
				->setDescription("uzhuang")
				->setKeywords("uzhuang")
				->setCategory("Test result file");
// Add some data
			$i = 1;
			foreach($new_field as $field=>$rs) {
				//echo $cell_field[$i].'1 + '.$rs['name']."\r\n";
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell_field[$i].'1', $rs['name']);
				$i++;
			}

			$j = 2;
			foreach($result as $_rs) {
				$i = 1;
				foreach($new_field as $field=>$rs) {
					if($field=='areaid') {
						$s1 = $this->db->get_one('linkage_data', array('lid' => $_rs['areaid_1']));
						$s2 = $this->db->get_one('linkage_data', array('lid' => $_rs['areaid_2']));
						$_rs[$field] = $s1['name'] . ' ' . $s2['name'];
					} elseif($field=='addtime') {
							$_rs[$field] = date('Y-m-d H:i:s',$_rs[$field]);
					} elseif($rs['formtype']=='box') {
						$setting = $rs['setting'];

						$options = explode("\r\n",$setting['options']);
						$tmp = array();
						foreach($options as $_op) {
							$ts = explode('|',$_op);
							$tmp[$ts[1]] = $ts[0];
						}
						if($_rs['renovationcategory']==2) {
							$_rs['homestyle'] = '';
						} else{
							$_rs['housetype'] = '';
						}
						$_rs[$field] = $tmp[$_rs[$field]];
					}
					$pre = '';
					if($field=='order_no') $pre = ' ';
					$_rs[$field] = $pre.$_rs[$field];
					//echo $cell_field[$i].$j." + ";
					//echo $_rs[$field]."\r\n";
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell_field[$i].$j, $_rs[$field]);
					$i++;
				}
				$j++;
			}
//exit;
// Rename worksheet
			$objPHPExcel->getActiveSheet()->setTitle('优装美家');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$models[$modelid]['name'].'.xls"');
			header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');

			//MSG('添加成功，等待打包','?m=package&f=index&v=listing'.$this->su());
		} else {
			$show_formjs = '';
			$starttime = mktime(0,0,0,date('m')-1,1,date('Y'));
			$starttime = date('Y-m-d',$starttime);
			$endtime = mktime(0,0,0,date('m'),date('d')+1,date('Y'));
			$endtime = date('Y-m-d',$endtime);
			load_class('form');

			include $this->template('export_csv');
		}
    }

	/**
	 * 处理阶段
	 */
	public function progress() {
		//
		$id = intval($GLOBALS['id']);
		$pos = intval($GLOBALS['pos']);
		$r = $this->db->get_one('demand', array('id' => $id));
		if($pos) {
			$pos = $r['progress']+1;
			if($pos>4) $pos = 4;
		} else {
			$pos = $r['progress']-1;
			if($pos<0) $pos = 0;
		}
		$this->db->update('demand', array('progress'=>$pos,'progress'.$pos.'time'=>SYS_TIME), array('id' => $id));
		MSG('设置成功',HTTP_REFERER);
	}
	public function setuser() {
		if(isset($GLOBALS['submit'])) {
			$username = $GLOBALS['username'];
			$mr = $this->db->get_one('member', array('username' => $username));
			if(!$mr) MSG('用户不存在，请先添加用户账号信息！');
			$id = intval($GLOBALS['id']);
			$order_no = '1'.str_pad($id,9,0,STR_PAD_LEFT);
			$this->db->update('demand', array('publisher'=>$username,'order_no'=>$order_no), array('id' => $id));
			MSG('<script>setTimeout("top.dialog.get(window).close().remove();",2000)</script>用户设置成功','');
		} else {
			include $this->template('setuser');
		}
	}
}