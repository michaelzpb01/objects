<?php 

defined('IN_WZ') or exit('No direct script access allowed');
load_function('content','content');

class leyu {
   	 
   	public function __construct() {
   		//private $tplid = 'default';
        $this->db = load_class('db');
        
		}
     
    //公司详情页
	public function index(){
		$id = isset($GLOBALS['id']) ? intval($GLOBALS['id']) : 0;

		if($id) {
			$res = $this->db->get_one('company', array('id' =>$id));
			$reddd = $this->homeurl=shopurl($res['areaid_2'],$arr_names[0],1,$res['areaid_2']);
		}

		if($GLOBALS['json'] == 1){
			$leyu_json = array(
            'logourl'=>getImgShow($r['thumb'],'middle_square'),
            'title'=>$res['title'],
            'pingfen'=>$res['avg_total']
			);
			$leyu_json = json_encode($leyu_json);
			echo $leyu_json;
		    
		}else{
			include T('leyu','Leyu_Company');
		}
	}
    
    //设计师详情页
	public function designer(){
		$desid = isset($GLOBALS['desid']) ? intval($GLOBALS['desid']) : 0;
		if($desid) {
			$resd= $this->db->get_one('company_team', array('id' =>$desid));
			$companyid = $resd['companyid'];
		    $comp = $this->db->get_one('company',array('id' => $companyid));
            $configs_design = get_config('design_config');
		    $p=trim($resd['style'],',');
            $s=explode(',',$p);
            $y=trim($resd['years'],',');
            $z=explode(',',$y);

            foreach ($s as &$value) {
            	$value = $configs_design['style'][$value];

            }
                $style_str = implode('、',$s);

            foreach ($z as &$value) {
            	$value = $configs_design['years'][$value];

            }
                $years_str = implode('、',$z);

		}
            
		

		if($GLOBALS['json'] == 1){
			$leyu_json = array(
		             'desname'=>$resd['title'],
		             'desrank'=>$resd['ranks'],
		             'year'=>$years_str,
		             'style'=>$style_str,
		             'campname'=>$comp['title'],
		             'pingfen'=>$comp['avg_total']
				);
			$leyu_json = json_encode($leyu_json);
			echo $leyu_json;
				
		}else{
			include T('leyu','Leyu_Designer');
		}
	}
   

   public function descomp(){
        $compid= isset($GLOBALS['compid']) ? intval($GLOBALS['compid']) : 0;
        
        if($compid){
        	$comp = $this->db->get_one('company',array('id' => $compid));
        }

        if($GLOBALS['json'] == 1){
			$leyu_json = array(
		             'campname'=>$comp['title'],
		             'pingfen'=>$comp['avg_total']
				);
			$leyu_json = json_encode($leyu_json);
			echo $leyu_json;
				
		}else{
			include T('leyu','Leyu_Designer1');
		}
            

   }	

	

    //商户图集
    public function shoppcs(){
       $picid = isset($GLOBALS['picid']) ? intval($GLOBALS['picid']) : 0;
       if($picid){
	       $shoppcs=$this->db->get_one('picture',array('id' =>$picid));
	       $configs = get_config('picture_config');
	       $config =get_config('design_config');
	       $desid = $shoppcs['designer'];
	       if($desid == 0){
	       	$desres = array();
	       }else{
	        //var_dump($desid);
	       	$desres = $this->db->get_one('company_team',array('id'=>$desid));
	       	$companyid = $desres['companyid'];
	       	$comp =$this->db->get_one('company',array('id'=>$companyid));
	       	$p=trim($desres['style'],',');
            $s=explode(',',$p);
            $y=trim($desres['years'],',');
            $z=explode(',',$y);

            foreach ($s as &$value) {
            	$value = $config['style'][$value];
            }

                $style_str = implode('、',$s);

            foreach ($z as &$v) {
            	$v = $config['years'][$v];

            }
                $years_str = implode('、',$z);
	       }
        }
        if($GLOBALS['json'] == 1){
			$leyu_json = array(
			    'address'=>$shoppcs['title'],
                'zxstyle'=>$configs['style'][$shoppcs['style']],
                'housetype'=>$configs['house'][$shoppcs['housetype']],
                'area'=>$shoppcs['area'],
                'cost'=>$shoppcs['cost'],
	            'desname'=>$desres['title'],
	            'desrank'=>$desres['ranks'],
	            'dstyle'=>$style_str,
	            'year'=>$years_str,
	            'campname'=>$comp['title'],
	            'pingfen'=>$comp['avg_total']
			);
			$leyu_json = json_encode($leyu_json);
			echo $leyu_json;
						
		}else{
			include T('leyu','Leyu_Photograph');
		}
    }

   public function shoppcsc(){
        $compid= isset($GLOBALS['compid']) ? intval($GLOBALS['compid']) : 0;
        
        if($compid){
        	$comp = $this->db->get_one('company',array('id' => $compid));

        }

        if($GLOBALS['json'] == 1){
			$leyu_json = array(
		             'campname'=>$comp['title'],
		             'pingfen'=>$comp['avg_total']
				);
			$leyu_json = json_encode($leyu_json);
			echo $leyu_json;
				
		}else{
			include T('leyu','Leyu_Photograph1');
		}
            

   }


    //单图平台
    public function shoppcp(){
        $ppicid = isset($GLOBALS['ppicid']) ? intval($GLOBALS['ppicid']) : 0;

       if($ppicid){
           $shoppcp=$this->db->get_one('picture_index',array('picid' =>$ppicid));
           //var_dump($shoppcp);
           $configs = get_config('picture_config');
       }


        if($GLOBALS['json'] == 1){
			$leyu_json = array(
		             'pname'=>$shoppcp['title'],
		             'style'=>$shoppcp['style'],
		             'housestyle'=>$shoppcp['housetype'],
		             'area'=>$shoppcp['area'],
		             'cost'=>$shoppcp['cost']
				);
			$leyu_json = json_encode($leyu_json);
			echo $leyu_json;
				
		}else{
        	include T('leyu','Leyu_Picture');
		}
            
    }


  


}


































 ?>
