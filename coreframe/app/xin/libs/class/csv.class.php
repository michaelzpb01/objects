<?php
// +----------------------------------------------------------------------
// | wuzhicms [ 五指互联网站内容管理系统 ]
// | Copyright (c) 2014-2015 http://www.wuzhicms.com All rights reserved.
// | Licensed ( http://www.wuzhicms.com/licenses/ )
// | Author: wangcanjia <phpip@qq.com>
// +----------------------------------------------------------------------
defined('IN_WZ') or exit('No direct script access allowed');
/**
 * csv 操作类
 */
class WUZHI_csv{

	/**
	 * 将数组动态输出至 csv 文件【服务器端输出到浏览器】
	 * @param array $data 二维数组
	 * @param string $filename 文件名
	 */
	function output($data, $filename = 'file.csv') {
		header('Content-Type:application/force-download');
		header("content-Disposition:filename={$filename}");

		foreach ($data as $fields) {
			foreach ($fields as $key => $value) {
				echo $value . ',';
			}
			echo "\r\n";
		}
	}

	/**
	 * 将数组动态输出至 csv 文件【服务器端生成文件】
	 * @param array $data 二维数组
	 * @param string $filename 文件名
	 */
	function write($data, $filename = 'file.csv') {
		$fp = fopen($filename, 'w');
		foreach ($data as $fields) {
			fputcsv($fp, $fields);
		}
		fclose($fp);
	}

}