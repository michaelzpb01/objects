<?php
// +----------------------------------------------------------------------
// | wuzhicms [ 五指互联网站内容管理系统 ]
// | Copyright (c) 2014-2015 http://www.wuzhicms.com All rights reserved.
// | Licensed ( http://www.wuzhicms.com/licenses/ )
// | Author: wangcanjia <phpip@qq.com>
// +----------------------------------------------------------------------
defined('IN_WZ') or exit('No direct script access allowed');
/**
 * 亿美软通
 */
class WUZHI_sms2 {
	public $uid;
	public $statuscode;
	private $sms_uid,$sms_pid,$sms_key,$smsapi_url;
	private $sendtext;
	private $sign = '【优装美家】';

	public function __construct() {
		$this->sendtext = array(
			1=>'手机号为：##N1## 您的验证码为：##N2##',
			2=>'感谢您的注册！',
		);
	}

    /**
     * 发送短信
     *
     * @param string $mobile
     * @param string $content
     * @param string $tplid
     * @param string $send_time
     * @param int $return_code
     * @return mixed
     */
	public function send_sms($mobile='', $content='',$tplid = '', $send_time ='', $return_code = 0) {
		$content = explode('||',$content);
		preg_match_all('/##.*?##/',$this->sendtext[$tplid],$match,PREG_OFFSET_CAPTURE);
		//str_replace();
		$match = $match[0];
		$new_array = $zz_array = array();
		foreach($match as $m) {
			$zz_array[] = $m[0];
			$new_array[] = '/'.$m[0].'/';
		}
		$all_msg = preg_replace($new_array,$content,$this->sendtext[$tplid]).$this->sign;
		$this->statuscode = 0;
		//调用第三方发送短信接口
		//TODO

		return $this->statuscode;
	}
		
	/**
	 * 
	 * 获取远程内容
	 * @param $timeout 超时时间
	 */
	public function getinfo($timeout=30) {
		
		$this->setting = array(
							'sms_uid'=>$this->sms_uid,
							'sms_pid'=>$this->sms_pid,
							'sms_passwd'=>$this->sms_key,	
							);
									
		$this->param = array_merge($this->param, $this->setting);
		
		$url = $this->smsapi_url.http_build_query($this->param);
		$stream = stream_context_create(array('http' => array('timeout' => $timeout)));
		return @file_get_contents($url, 0, $stream);
	}
	
	/**
	 *  post数据
	 *  @param string $url		post的url
	 *  @param int $limit		返回的数据的长度
	 *  @param string $post		post数据，字符串形式username='dalarge'&password='123456'
	 *  @param string $cookie	模拟 cookie，字符串形式username='dalarge'&password='123456'
	 *  @param string $ip		ip地址
	 *  @param int $timeout		连接超时时间
	 *  @param bool $block		是否为阻塞模式
	 *  @return string			返回字符串
	 */
	
	private function _post($url, $limit = 0, $post = '', $cookie = '', $ip = '', $timeout = 30, $block = true) {
		$return = '';
		$matches = parse_url($url);
		$host = $matches['host'];
		$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
		$port = !empty($matches['port']) ? $matches['port'] : 80;
		$siteurl = URL();
		if($post) {
			$out = "POST $path HTTP/1.1\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Referer: ".$siteurl."\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n" ;
			$out .= 'Content-Length: '.strlen($post)."\r\n" ;
			$out .= "Connection: Close\r\n" ;
			$out .= "Cache-Control: no-cache\r\n" ;
			$out .= "Cookie: $cookie\r\n\r\n" ;
			$out .= $post ;
		} else {
			$out = "GET $path HTTP/1.1\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Referer: ".$siteurl."\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
		}
		$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		if(!$fp) return '';
	
		stream_set_blocking($fp, $block);
		stream_set_timeout($fp, $timeout);
		@fwrite($fp, $out);
		$status = stream_get_meta_data($fp);
	
		if($status['timed_out']) return '';	
		while (!feof($fp)) {
			if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n"))  break;				
		}
		
		$stop = false;
		while(!feof($fp) && !$stop) {
			$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
			$return .= $data;
			if($limit) {
				$limit -= strlen($data);
				$stop = $limit <= 0;
			}
		}
		@fclose($fp);
		
		//部分虚拟主机返回数值有误，暂不确定原因，过滤返回数据格式
		$return_arr = explode("\n", $return);
		if(isset($return_arr[1])) {
			$return = trim($return_arr[1]);
		}
		unset($return_arr);
		
		return $return;
	}

	/**
	 * 
	 * 接口短信状态
	 */
	private function _sms_status() {
        $array = array(
            '0'=>'发送成功',
            '1'=>'手机号码非法',
            '2'=>'用户存在于黑名单列表',
            '3'=>'接入用户名或密码错误',
            '4'=>'产品代码不存在',
            '5'=>'IP非法',
            '6 '=>'源号码错误',
            '7'=>'调用网关错误',
            '8'=>'消息长度超过限制',
            '9'=>'发送短信内容参数为空',
            '10'=>'用户已主动暂停该业务',
            '11'=>'wap链接地址或域名非法',
            '12'=>'5分钟内给同一个号码发送短信超过10条',
            '13'=>'短信模版ID为空',
            '14'=>'禁止发送该消息',
            '-1'=>'每分钟发给该手机号的短信数不能超过3条',
            '-2'=>'手机号码错误',
            '-11'=>'帐号验证失败',
            '-10'=>'接口没有返回结果',
        );
		return $array;
	}
	
}
?>