<?php
define(SCRIPT_ROOT , dirname(__file__).'/');
include(SCRIPT_ROOT.'include/Client.php');
class WUZHI_ym_sms{
	private $oClient;
	private $sessionKey;
	private $gwUrl = 'http://sdk4report.eucp.b2m.cn:8080/sdk/SDKService';
	const YMSMS_SERIAL_NUMBER = '6SDK-EMY-6688-JBUNM';
	const YMSMS_PASSWORD = '713256';
	const YMSMS_SESSKEY = 'uzhuang';
	const STATUS_SUCC = 0;

	public function __construct(){
		$this->oClient = new Client($this->gwUrl , self::YMSMS_SERIAL_NUMBER , self::YMSMS_PASSWORD , self::YMSMS_SESSKEY );
		$this->oClient->setOutgoingEncoding("UTF-8");
		
	}
	public function send($mobile , $msg){
		if(!$this->login()){
			return false;
		}

		$mobile = (array)$mobile;
		$statusCode = $this->oClient->sendSMS($mobile,$msg);
		
		if($statusCode != self::STATUS_SUCC){
			$data = array(
				'mobile' => $mobile,
				'msg' => $msg,
			);
			$this->log($this->oClient->getError() , $data);
			return false;
		}else{
			return true;
		}
		
	}
	
	public function getBalance(){
		$balance = $oClient->getBalance();
		return $balance;
	}
	private function login(){
		if($this->sessionKey){
			return true;
		}

		$rs = $this->oClient->login();

		if($rs != self::STATUS_SUCC){
			return false;
		}else{
			$this->sessionKey = $this->oClient->getSessionKey();
			return true;
		}
	}
	private function log($error , $data){

	}
}

// $oSms = new ymsms_client();
// $rs = $oSms->send(13800138000 , 'safasdf')
// if(!$rs){
// 	//失败报错
// }
