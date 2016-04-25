<?php
// +----------------------------------------------------------------------
// | wuzhicms [ 五指互联网站内容管理系统 ]
// | Copyright (c) 2014-2015 http://www.wuzhicms.com All rights reserved.
// | Licensed ( http://www.wuzhicms.com/licenses/ )
// | Author: wangcanjia <phpip@qq.com>
// +----------------------------------------------------------------------
header("Access-Control-Allow-Origin: *");
defined('IN_WZ') or exit('No direct script access allowed');
/**
 * 图库
 */
load_class('foreground', 'member');
class biz_photo extends WUZHI_foreground {

	/**
	 * 案例列表页或筛选结果页的接口
	 */
	public function listing() {
		$memberinfo = $this->memberinfo;
		// $uid = $memberinfo['uid'];
		$uid = get_cookie('_uid');
		$page = intval($GLOBALS['page']);
         if(!isset($page)||empty($page)){
                   $page=1;
         }

		$where="`status`=1";
		$whe="p.status=1";
		        //获取前台传的筛选值
					   $field_array = array('style','house','area','cost','color','order');
						// $urlrule = '{$style}-{$house}-{$area}-{$cost}-{$color}-{$order}-{$page}.html';
						$variables = array();
						foreach($field_array as $field) {
							//获取前台筛选传的值
							$variables[$field] = isset($GLOBALS[$field]) ? intval($GLOBALS[$field]) : 0;
						}

						// $_POST['page_urlrule'] = $urlrule;
						$_POST['page_fields'] = $variables;

						$kw_style = $kw_house = '';
						
						if($variables['style']) {
							$where .= " AND CONCAT(',',style,',') like '%,".$variables['style'].",%'";
							$whe .= " AND CONCAT(',',p.style,',') like '%,".$variables['style'].",%'";
							$kw_style = $configs['style'][$variables['style']];
						}
						if($variables['house']) {
							$where .= " AND `housetype`='".$variables['house']."'";
							$whe .= " AND p.housetype ='".$variables['house']."'";
							$kw_house = $configs['house'][$variables['house']];
						}

	//获取前台点击城市传的值				
	   $cityid=$GLOBALS['cityid'];
	   if(isset($cityid)&&!empty($cityid)){						
   
			$result_page=$this->db->get_list('m_picture',$where,"id,cover,name,style,housetype,collectnum,intervene,updatetime,companyid");
            $c=count($result_page);
            $c1=ceil($c/20);

		        $page1=$page*20-20;
		        $result1 = array();
		        $query = $this->db->query("select p.* from wz_m_picture as p left join wz_m_company as m on p.companyid=m.id where ".$whe." and m.areaid_2 is not null AND m.status=9 order by (case when m.areaid_2=".$cityid." then 1 else 0 end) desc,p.intervene desc,p.updatetime DESC limit ".$page1.",20;");
		        
				while($data = $this->db->fetch_array($query)) {
					$result1[] = $data;
			   	}
			     $total = count($result1);  
	                          
          // echo '<pre>';print_r($total);exit;
       
         }else{
                            $result1=$this->db->get_list('m_picture',$where,"id,cover,name,style,housetype,collectnum,intervene,updatetime,companyid",0,20,$page,'intervene DESC,updatetime DESC');
		                    $total = count($result1);  
           }

               
		foreach ($result1 as $key => $value1) {
		$result1[$key]['cover']=getMImgShow($value1['cover'],'original');
		}
		
		//根据picture_configs找对应的中文
		//循环出风格的索引
		$style1=array();	
		$configs_picture1 = get_config('picture_config');
		for($j=0;$j<count($result1);$j++){
             $style1[]=$result1[$j]['style'];
		}
		foreach ($style1 as $key => $value) {
			
			$z1 = $style1[$key];
			$z2=explode(',',$z1);
			$zh1=array();
	        foreach ($z2 as $keyz => $valuez) {
			  $zh1[] =$configs_picture1['style'][$valuez];
	        }
	        
			$result1[$key]['style']=$zh1;
		}
		// echo '<pre>';print_r($result1);exit;
		//循环出户型的索引
		$house1=array();	
		for($i=0;$i<count($result1);$i++){
             $house1[]=$result1[$i]['housetype'];
		}
		foreach ($house1 as $key => $value) {
			$z1 = $house1[$key];
			$zh1 =$configs_picture1['house'][$z1];
			$result1[$key]['housetype']=$zh1;
		}
    
        $pages = $this->db->pages;
          //专题图start
        $result_special1=$this->db->get_list('m_p_special',"`status`=1","id,cover,title,url,atlas,alt,intervene,updatetime",0,4,$page,'intervene DESC,updatetime DESC');
       
	        foreach ($result_special1 as $key2 => $value2){
		        	$result_special1[$key2]['cover']=getMImgShow($value2['cover'],'original');
			}
        $new_res1=array();
        //遍历，5个案例插一个专题
       foreach ($result1 as $key => $value) {

       	      $new_res1[]=$value;
                  if(($key)%5==4){
                  	//删除数组中的第一个元素，并返回被删除元素的值
                      	$new_res1[]=array_shift($result_special1);       
                    }
       }
      //专题图end
        
		if(empty($uid)){
			
			if(!$total){
				
					$login_status1=(int)0;
					$finalarr1=array(
		              'listing_data'=>$new_res1,
		              'finalpage'=>$c1,//当前总共页数
		              'login_status'=>$login_status1,
		              'tishi'=>'还没有相关的案例哦，试试其他的吧~',
					   );																			
				}else{
				
					$login_status1=(int)0;
				   $finalarr1=array(
				   	'finalpage'=>$c1,//当前总共页数
	               'listing_data'=>$new_res1,
	               'login_status'=>$login_status1, 
				    );
				}

		}else{

			if(!$total){
		           
					$login_status1=(int)0;
					$finalarr1=array(
						'finalpage'=>$c1,//当前总共页数
		              'listing_data'=>$new_res1,
		              'login_status'=>$login_status1,
		              'tishi'=>'还没有相关的案例哦，试试其他的吧~',
		              'uid'=>$uid,
					   );																			
				}else{
				
					$login_status1=(int)0;
				   $finalarr1=array(
				   	'finalpage'=>$c1,//当前总共页数
	               'listing_data'=>$new_res1,
	               'login_status'=>$login_status1, 
	               'uid'=>$uid,
				    );
				}
		}

		
		 // echo '<pre>';print_r($finalarr1);exit;
		 echo json_encode(array('code'=>1,'data'=>$finalarr1,'message'=>'精品案例列表页的数据','process_time'=>time()));
		// code:状态（成功或失败）
		// data:数据
		// message:提示信息
		// process_time:当前时间
	}
    /**
	 * 筛选列表页的接口
	 */
	public function screen(){
		$configs = get_config('picture_config');
		$screen=array();
		$screen['style']=$configs['style'];//风格
		$screen['house']=$configs['house'];//户型
		echo json_encode(array('code'=>1,'data'=>$screen,'message'=>'精品案例筛选列表的数据','process_time'=>time()));
	}
	 /**
	 * 大写字母筛选的接口
	 */
   public function letter_screen(){
      	$file='/alidata/data/city.xml';
      	//判断文件是否存在，不存在则创建
      	if(file_exists($file)){
      		
      	}else{
      		$obj = new biz_photo();
			$obj->save_xml();
      	}
      	//获取前台传的字母
      	$upperletter=$GLOBALS['upperletter'];
      	// 读XML文件内容，并保存到字符串变量中
      	$xmlfile = file_get_contents($file);
      	// 将字符串转换为对象
		$ob= simplexml_load_string($xmlfile);
		// 将对象转换为JSON
		$json  = json_encode($ob);
		// 解析JSON字符串
		$configData = json_decode($json, true);

		$configs = get_config('picture_config');
		//取cinfigs的upperletter数组
		$sz=$configs['upperletter'][$upperletter];
		$a=$configData['item'][$sz];
		//转成关联数组
		$city=explode(',',$a['city']);
		$arr=array();
		for($i=0;$i<count($city);$i++){
			// echo $city[$i].'<br>';
			$city1=explode('|',$city[$i]);
			$city2=array("$city1[1]"=>"$city1[0]");
		    $arr[]=$city2;
		}
	
		echo json_encode(array('code'=>1,'data'=>$arr,'message'=>'多城市筛选的数据','process_time'=>time()));
	}

 //匹配汉字的大写字母
 static function getfirstchar($s0){

        $fchar = ord($s0{0});
        if($fchar >= ord("A") and $fchar <= ord("Z") )return strtoupper($s0{0});
        $s1 = mb_convert_encoding( $s0,"gb2312","UTF-8");
        $s2 = mb_convert_encoding($s1,"UTF-8","gb2312");
        if($s2 == $s0){$s = $s1;}else{$s = $s0;}
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if($asc >= -20319 and $asc <= -20284) return "A";
        if($asc >= -20283 and $asc <= -19776) return "B";
        if($asc >= -19775 and $asc <= -19219) return "C";
        if($asc >= -19218 and $asc <= -18711) return "D";
        if($asc >= -18710 and $asc <= -18527) return "E";
        if($asc >= -18526 and $asc <= -18240) return "F";
        if($asc >= -18239 and $asc <= -17760) return "G";
        if($asc >= -17759 and $asc <= -17248) return "H";
        if($asc >= -17247 and $asc <= -17418) return "I";
        if($asc >= -17417 and $asc <= -16475) return "J";
        if($asc >= -16474 and $asc <= -16213) return "K";
        if($asc >= -16212 and $asc <= -15641) return "L";
        if($asc >= -15640 and $asc <= -15166) return "M";
        if($asc >= -15165 and $asc <= -14923) return "N";
        if($asc >= -14922 and $asc <= -14915) return "O";
        if($asc >= -14914 and $asc <= -14631) return "P";
        if($asc >= -14630 and $asc <= -14150) return "Q";
        if($asc >= -14149 and $asc <= -14091) return "R";
        if($asc >= -14090 and $asc <= -13319) return "S";
        if($asc >= -13318 and $asc <= -12839) return "T";
        if($asc >= -12838 and $asc <= -12557) return "W";
        if($asc >= -12556 and $asc <= -11848) return "X";
        if($asc >= -11847 and $asc <= -11056) return "Y";
        if($asc >= -11055 and $asc <= -10247) return "Z";
    }
 
  
     // 取城市存进数组
    function pinyin1(){
    	$alphabet = 'ABCDEFGHIJKLMNOPQRSTWXYZ';
    	$article_array = array();
    	for($i=0;$i<strlen($alphabet);$i++){
    		$article_array[$alphabet{$i}] = array();
    	}
    	foreach($this->db->get_list('linkage_data',"pid=0",'lid,name') as $item){
    		if(preg_match("/^(北京|上海|天津|重庆|香港|澳门)+/",$item['name'])){
    			$first = self::getfirstchar($item['name']);
    			if(!array_key_exists($first,$article_array))continue;
    			$article_array[$first]['city'][] = substr($item['name'],0,6).'|'.$item['lid'];
    			continue;
    		}
    		foreach($this->db->get_list('linkage_data',"pid={$item['lid']}",'lid,name') as $value){
    			$first = self::getfirstchar($value['name']);
    			if(!array_key_exists($first,$article_array))continue;
    			preg_match("/(.*)(市){1}/",$value['name'],$temp);
    			$article_array[$first]['city'][] = (!empty($temp[1])?$temp[1]:$value['name']).'|'.$value['lid'];
          	}
    	}
    	for($i=0;$i<strlen($alphabet);$i++){
    		$article_array[$alphabet{$i}]['city'] = implode(',',$article_array[$alphabet{$i}]['city']);
    	}
    	return $article_array;    
     }
 
       function create_item($key_data,$item_data)
		{
		    $item = "<item>\n";
		    $item .= "<title>" . $key_data . "</title>\n";
		    $item .= "<city>" . $item_data . "</city>\n";
		    $item .= "</item>\n";
		  return $item;
		}

			//  创建XML单项
		function save_xml(){
			header("Content-Type: text/xml; charset=utf-8");
            $xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
			$xml .= "<article>\n";
			foreach ($this->pinyin1() as $key=>$data) {
			$xml .= $this->create_item($key,$data['city']);
			 }
			$xml .= "</article>\n";
             //写入test.xml文件中
			$myfile = fopen("/alidata/data/city.xml", "w") or die("Unable to open file!");
			fwrite($myfile, $xml);
			fclose($myfile);

		}
   //案例详情页
public function particulars_listing(){
	
		$uid = get_cookie('_uid');
		// $uid = $GLOBALS['uid'];
		//前台传的参数
		$id=$GLOBALS['id'];
		$page = intval($GLOBALS['page']);
		$page = max($page,1);
		$where="`status`=1 AND `id`=$id";
 
        //案例信息
		$result=$this->db->get_list('m_picture',$where,"id,cover,name,area,style,housetype,collectnum,companyid,total,materialtotal,crafttotal,browsenum,designer,case,alt,space,collectstatus");
        $result[0]['cover']=getMImgShow($result[0]['cover'],'original');
    
        // 访问量加一
        $browseCount = (int)$result[0]['browsenum']+1;
        // 跟新到数据库
        $this->db->update('m_picture',array('browsenum'=>$browseCount),array('id'=>$id));
		//分割案例图
		$case=$result[0]['case'];
		$case=ltrim($case,'|');
		$case1=explode('|',$case);
		foreach ($case1 as $key => $value) {
		 $case2.=getMImgShow($value,'original').',';
		
		}
		$case3=rtrim($case2,',');
		$case4=explode(',',$case3);
		//分割空间
		$space=$result[0]['space'];
		$space1=explode('|',$space);
		// var_dump($space1);exit;
		//匹配空间的中文
		$configs_picture = get_config('picture_config');
		for($s=0;$s<count($space1);$s++){
			$spa.=$configs_picture['space'][$space1[$s]].',';
		}
		$spa1=rtrim($spa,',');
		$spa2=explode(',',$spa1);
	    
		//分割描述
		$alt=$result[0]['alt'];
		$alt1=explode('|',$alt);
		//把图片、空间、描述存到一个数组
		$hebing=array();
	    for($h=0;$h<count($spa2);$h++){

	    		$hebing[$space1[$h]][]=array('space'=>"$spa2[$h]",'photo'=>"$case4[$h]",'alt'=>"$alt1[$h]");
                 // if($hebing[29]){
                 // 	$hebing[29]=array(array('photo'=>"$case4[$h]",'alt'=>"$alt1[$h]"));
                 // }
	    } 
             
	        // echo '<pre>';print_r($hebing);exit;

	        ksort($hebing);
	        $temp = array();
	        foreach($hebing as $v){
	        	if(!is_array($v))continue;
	        	foreach($v as $cv){
	        		$temp[] = $cv;
	        	}
	        }
	        $hebing=$temp;
		//循环出风格的索引,找对应的中文
		$style=array();	
		$configs_picture = get_config('picture_config');
		for($j=0;$j<count($result);$j++){
             $style[]=$result[$j]['style'];
		}
		foreach ($style as $key => $value) {
			$z = $style[$key];
			$z3=explode(',',$z);
			$zh=array();
	        foreach ($z3 as $key3 => $value3) {
			  $zh[] =$configs_picture['style'][$value3];
	        }
			$result[$key]['style']=$zh;
		}
		//循环出户型的索引,找对应的中文
		$house=array();	
		for($i=0;$i<count($result);$i++){
             $house[]=$result[$i]['housetype'];
		}
		foreach ($house as $key => $value) {
			$z = $house[$key];
			$zh =$configs_picture['house'][$z];
			$result[$key]['housetype']=$zh;
		}

	    //取案例所属的设计师
	    $design=$this->db->get_one("m_company_team",'`id`="'.$result[0]['designer'].'"','id,thumb,title,thumb1');
	    if(isset($design['thumb1'])&&!empty($design['thumb1'])){
                                $design['thumb']=getMImgShow($design['thumb1'],'big_square'); 
                         }else{
                                $design['thumb']='http://www.uzhuang.com/image/big_square/'.$design['thumb'];
                         }
		 //取案例所属的口碑公司
		 $company=$this->db->get_one("m_company",'`id`="'.$result[0]['companyid'].'"','id,thumb,title,tese,avg_total,avg_design,avg_quality,avg_service,address,com_browsenum');
		 $company['thumb']='http://www.uzhuang.com/image/big_square/'.$company['thumb'];
		 $company['total']=$result[0]['total'];
        //案例、设计师、公司合并到一个数组
        if(!$uid){
        	//未登录状态
        	$login_status=(int)0;
			$finalarr=array(
              'picture'=>$result,
              'design'=>$design,
              'company'=>$company,
              'hebing'=>$hebing,
              'login_status'=>$login_status,
               'collectstatus'=>(int)0,
			);	
			// echo '<pre>';print_r($finalarr);exit;
		echo json_encode(array('code'=>1,'data'=>$finalarr,'message'=>'案例详情页的数据','process_time'=>time()));
		}else{
			//取收藏状态
		
			$collectstatus=$this->db->get_list('favorite',$where11,'keyid,collectstatus');
			$arr=array();
			foreach ($collectstatus as $key => $value) {
				$arr[]=$value['keyid'];
			}
			if(in_array($id,$arr)){
                 $collectstatus1=$this->db->get_one('favorite',"`keyid`=$id and `uid`=$uid",'keyid,collectstatus');
			}
           
          
			//登录状态
        	$login_status=(int)1;
			$finalarr=array(
              'picture'=>$result,
              'design'=>$design,
              'company'=>$company,
              'hebing'=>$hebing,
              'login_status'=>$login_status,
              'collectstatus'=>$collectstatus1['collectstatus'],
			);	
		echo json_encode(array('code'=>1,'data'=>$finalarr,'message'=>'案例详情页的数据','process_time'=>time()));
        	
		}
	
        $pages = $this->db->pages;       
		$total = $this->db->number;
}


	        // 专题详情
		    public function special(){
		    	    $id=$GLOBALS['id'];
		    	    $result_special=$this->db->get_one('m_p_special',"`id`=$id AND `status`=1","id,cover,title,url,atlas,alt,dao,intervene,updatetime");

		    	    $result_special['cover']=getMImgShow($result_special['cover'],'original');

				    //分割专题图
					$case=$result_special['atlas'];
					$case1=explode('|',$case);
					foreach ($case1 as $key => $value) {
					 $case2.=getMImgShow($value,'original').',';
					       }
					$case3=rtrim($case2,',');
					$case4=explode(',',$case3);

					 //分割图片链接
					$url=$result_special['url'];
					$url1=explode('|',$url);

		    	 	//分割描述
		    	     $alt=$result_special['alt'];
					$alt1=explode('|',$alt);
					//把图片、空间、描述存到一个数组
					$hebing=array();
				    for($h=0;$h<count($case4);$h++){

				    		$hebing[$h]=array('photo'=>"$case4[$h]",'alt'=>"$alt1[$h]","url"=>"$url1[$h]");
				           }
				    $cover=array('title'=>$result_special['title'],'cover'=>$result_special['cover'],'dao'=>$result_special['dao']);
				    $special=array(
				    	'cover'=>$cover,
                        'hebing'=>$hebing,
				    	);
				    // echo '<pre>';print_r($special);exit;
				   echo json_encode(array('code'=>1,'data'=>$special,'message'=>'专题详情页的数据','process_time'=>time()));
		      }


 }




