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
class index extends WUZHI_foreground {
	function __construct() {
        $this->member = load_class('member', 'member');
        load_function('common', 'member');
        $this->member_setting = get_cache('setting', 'member');
        parent::__construct();
	}

/**
 *GPS定位
 **/
private static $_instance;
        const REQ_GET = 1;
        const REQ_POST = 2;
    private function async($url, $params = array(), $encode = true, $method = self::REQ_GET)
         {
         $ch = curl_init();
          if ($method == self::REQ_GET)
          {
           $url = $url . '?' . http_build_query($params);
           $url = $encode ? $url : urldecode($url);
           curl_setopt($ch, CURLOPT_URL, $url);
          }
           else
          {
           curl_setopt($ch, CURLOPT_URL, $url);
           curl_setopt($ch, CURLOPT_POST, true);
           curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
          }
          curl_setopt($ch, CURLOPT_REFERER, '百度地图referer');
          curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X; en-us) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53');
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          $resp = curl_exec($ch);
          curl_close($ch);
          return $resp;
    }
/**
  * GPS定位
  * @param $lng
  * @param $lat
  * @return array
  * @throws Exception
  */
    public function locationByGPS(){
      //$lat = 53.48;
      //$lng = 122.37;
      $lng = $GLOBALS['lng'];
      $lat = $GLOBALS['lat'];
      $params = array(
        'coordtype' => 'wgs84ll',
        //'location' => '39.88,116.43',
        'location' => $lat . ',' . $lng,
        'ak' => 'BiYl0zyGXDl3E67wPxyGYGyP',
        'output' => 'json',
        'pois' => 0,
      );
      $resp = $this->async('http://api.map.baidu.com/geocoder/v2/', $params, false);
      $data = json_decode($resp, true);
      //var_dump($resp);
      if ($data['status'] != 0)
      {
       throw new Exception($data['message']);
      }
      $arr=array(
        'address' => $data['result']['formatted_address'],
        'province' => $data['result']['addressComponent']['province'],
        'city' => $data['result']['addressComponent']['city'],
        'street' => $data['result']['addressComponent']['street'],
        'street_number' => $data['result']['addressComponent']['street_number'],
        'city_code'=>$data['result']['cityCode'],
        'lng'=>$data['result']['location']['lng'],
        'lat'=>$data['result']['location']['lat']
      );
      $city = $arr['city'];
            $city_area =array('北京市'=>0,'上海市'=>1,'广州市'=>2,'深圳市'=>3,'杭州市'=>4,'天津市'=>5,'西安市'=>6,'南京市'=>7);
            $city_config =  get_config('city_config');
            $citys =$city_area[$city];
            $cityid=$city_config[$citys];
            if($cityid){
               echo json_encode(array('code'=>1,'data'=>$cityid,'message'=>'开通城市','process_time'=>time())); exit;
            }else{
               echo json_encode(array('code'=>1,'data'=>$arr,'message'=>'未开通城市','process_time'=>time())); exit;
            }
    }   
    /**
     * M站首页城市切换
     */
    public function init() {
       
        $city_config =get_config('city_config');

        $arr=array(
             'ktcity'=>$city_config,
            );
        return $arr;
    }

    /**
     *专题封面图
     **/
    public function Topics_cover(){
        $where = 'status=1';
        $res = $this->db->get_list('m_special',$where,'special,address', 0, 6, $page,'intervene DESC,id DESC');
        $photos=array();
        foreach ($res as $key => $re) {
         $photos[$key]['special']=getMImgShow($re['special'],'original');
         $photos[$key]['address']=$re['address'];      
         }    
        return $photos;
    }

    /**
     *城市cityid存入cookie里
     **/
    public function qhcspi(){
        $cityid = $GLOBALS['cityid'];
        setcookie('cityid',$cityid);
        echo json_encode(array('code'=>1,'data'=>null,'message'=>'','process_time'=>time())); exit;
    }

    /**
     *轮播图
     **/
    public function shuffl_photo(){
        $shuffl = empty($GLOBALS['shuffl'])?'':mysql_real_escape_string($GLOBALS['shuffl']);
        if( $shuffl){
        $where = "status=1 and`city` LIKE '%".$shuffl."%'";
        $rs = $this->db->get_list('m_carousel',$where, 'carousel,address', 0, 3, $page, 'intervene DESC,id DESC');
        $photo=array();
        foreach ($rs as $key => $rc) {
        $photo[$key]['carousel']=getMImgShow($rc['carousel'],'original');
        $photo[$key]['address']=$rc['address'];
        }
       echo json_encode(array('code'=>1,'data'=>$photo,'message'=>'','process_time'=>time()));exit;
       }else{
       echo json_encode(array('code'=>0,'data'=>null,'message'=>'参数错误','process_time'=>time()));exit;
       }
    }

    /**
     *首页入口
     **/
    public function index(){
            $return = array(
    		'init'=>$this->init(),
            'Topics_cover'=>$this->Topics_cover(),
    		);
    	 echo json_encode(array('code'=>1,'data'=>$return,'message'=>'','process_time'=>time()));exit;
    }
}