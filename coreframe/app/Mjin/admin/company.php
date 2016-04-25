<?php
defined('IN_WZ') or exit('No direct script access allowed');
load_class('admin');
load_class('form');
class company extends WUZHI_admin {
    function __construct() {
        parent::__construct();
        header("cache-control: no-store, no-cache, must-revalidate");
    }
   public function listing(){
   	    $page = isset($GLOBALS['page']) ? intval($GLOBALS['page']) : 1;
        $pagess=$page ;
        $keytypes = isset($GLOBALS['title']) ? $GLOBALS['title'] : '';
        $coms = $GLOBALS['com'];
        $page = max($page,1);
        $db = load_class('db');
        $result = $db->get_list('m_picture'," status='1'",'*');//查询精
           $arr=array();//定义新的数组
        foreach ($result as $key => $value) {
        	$arr[]=$value['companyid'];//把循环出来的id放到新的数组里
        }

         if($coms==1){
         $where='title like "%'.$keytypes.'%"';
         }if($coms==2){
                $lid = $db->get_one('linkage_data','name like"%'.$keytypes.'%"', 'lid');
                 $lid_one = $db->get_one('linkage_data','pid like"%'.$lid['lid'].'%"', 'lid');
                 $city = $db->get_list('m_company','areaid_2="'.$lid_one['lid'].'"', '*');
                 $cid=array();
                 foreach($city as $ci){
                  $cid[]=$ci['id'];
                 }
                 if(empty($cid)){
                 $lid = $db->get_one('linkage_data','name like"%'.$keytypes.'%"', 'lid');
                 $city = $db->get_list('m_company','areaid_2="'.$lid['lid'].'"', '*');
                 $cid=array();
                 foreach($city as $ci){
                  $cid[]=$ci['id'];
                 }
                 }
                 if(empty($cid)){
                  include $this->template('company');die;
                 }
                $where = 'id IN('.implode(',',$cid).')';
         }  
        if(!empty($arr)){
        if($keytypes!=""){//判断前段搜索框里的value值是否为空不为空进行搜索为空进行默认的列表页
             $company=$db->get_list('m_company',$where.'and id IN('.implode(',',$arr).')',"*", 0, 10, $page,'updatetime desc');
           }else{
        	   $company=$db->get_list('m_company','id IN('.implode(',',$arr).')',"*", 0, 10, $page,'updatetime desc');
        }
      }
           foreach($company as $key=>$value){
                $companyname= $db->get_one('linkage_data','lid='.$value['areaid_2'],'*');
                $com= $db->get_one('company','id='.$value['id'],'*');
                 $company[$key]['com']=$companyname['name'];
                 if($com['avg_total']=="0.0"){
                  $company[$key]['avg_totals']="5.0";
                 }else{
                   $company[$key]['avg_totals']=$com['avg_total'];
                 }
                  if($com['avg_service']=="0.0"){
                  $company[$key]['avg_services']="5.0";
                 }else{
                   $company[$key]['avg_services']=$com['avg_service'];
                 }
                  if($com['avg_quality']=="0.0"){
                  $company[$key]['avg_qualitys']="5.0";
                 }else{
                   $company[$key]['avg_qualitys']=$com['avg_quality'];
                 }
                   if($com['avg_design']=="0.0"){
                  $company[$key]['avg_designs']="5.0";
                 }else{
                   $company[$key]['avg_designs']=$com['avg_design'];
                 }

                //北京尚都国际装饰有限公司
            
          }
             $pages = $db->pages;
        $pa=array(
               array(
               	    pa=>'dshang',
               	    su=>'designnum',
               	    pan=>"shang"
               	),
               array(
               	    pa=>'dxia',
               	    su=>'designnum',
               	    pan=>"xia"
               	),
               array(
               	    pa=>'ashang',
               	    su=>'photonum',
               	    pan=>"shang"
               	),
               array(
               	    pa=>'axia',
               	    su=>'photonum',
               	    pan=>"xia"
               	),
               array(
               	    pa=>'lshang',
               	    su=>'com_browsenum',
               	    pan=>"shang"
               	),
               array(
               	    pa=>'lxia',
               	    su=>'com_browsenum',
               	    pan=>"xia"
               	),
               array(
               	    pa=>'sshang',
               	    su=>'com_collectnum',
               	    pan=>"shang"
               	),
               array(
               	    pa=>'sxia',
               	    su=>'com_collectnum',
               	    pan=>"xia"
               	),
               array(
               	    pa=>'fshang',
               	    su=>'avg_design',
               	    pan=>"shang"
               	),
               array(
               	    pa=>'fxia',
               	    su=>'avg_design',
               	    pan=>"xia"
               	),
               array(
               	    pa=>'tshang',
               	    su=>'avg_service',
               	    pan=>"shang"
               	),
               array(
               	    pa=>'txia',
               	    su=>'avg_service',
               	    pan=>"xia"
               	),
               array(
               	    pa=>'zshang',
               	    su=>'avg_total',
               	    pan=>"shang"
               	),
               array(
               	    pa=>'zxia',
               	    su=>'avg_total',
               	    pan=>"xia"
               	),
               array(
                    pa=>'gyshang',
                    su=>'intervene',
                    pan=>'shang',
                ),
                array(
                    pa=>'gyxia',
                    su=>'intervene',
                    pan=>'xia',
                )
        	);//定义页面穿过来的排序的二维数组
        foreach ($pa as $key => $pas) {
        	if($pas['pa']==$GLOBALS['pai']){
        		if($pas['pan']=='shang'){
        			$company = $db->get_list('m_company','id IN('.implode(',',$arr).')',"*", 0, 10, $page,$pas['su'].' ASC');
               foreach($company as $key=>$value){
                 $companyname= $db->get_one('linkage_data','lid='.$value['areaid_2'],'*');
                 $com= $db->get_one('company','id='.$value['id'],'*');
                 $company[$key]['com']=$companyname['name'];
               if($com['avg_total']=="0.0"){
                  $company[$key]['avg_totals']="5.0";
                 }else{
                   $company[$key]['avg_totals']=$com['avg_total'];
                 }
                  if($com['avg_service']=="0.0"){
                  $company[$key]['avg_services']="5.0";
                 }else{
                   $company[$key]['avg_services']=$com['avg_service'];
                 }
                  if($com['avg_quality']=="0.0"){
                  $company[$key]['avg_qualitys']="5.0";
                 }else{
                   $company[$key]['avg_qualitys']=$com['avg_quality'];
                 }
                   if($com['avg_design']=="0.0"){
                  $company[$key]['avg_designs']="5.0";
                 }else{
                   $company[$key]['avg_designs']=$com['avg_design'];
                 }
                 $HONG=array();
                 $HONG['avg_total']=$com['avg_total'];
                 $HONG['avg_service']=$com['avg_service'];
                 $HONG['avg_quality']=$com['avg_quality'];
                 $HONG['avg_design']=$com['avg_design'];
                 $db->update('m_company',$HONG,array('id'=>$value['id']));
          }
     
        		}else if($pas['pan']=='xia'){
        			$company = $db->get_list('m_company','id IN('.implode(',',$arr).')',"*", 0, 10, $page,$pas['su'].' DESC');
               foreach($company as $key=>$value){
               $companyname= $db->get_one('linkage_data','lid='.$value['areaid_2'],'*');
                $com= $db->get_one('company','id='.$value['id'],'*');
                 $company[$key]['com']=$companyname['name'];
                  if($com['avg_total']=="0.0"){
                  $company[$key]['avg_totals']="5.0";
                 }else{
                   $company[$key]['avg_totals']=$com['avg_total'];
                 }
                  if($com['avg_service']=="0.0"){
                  $company[$key]['avg_services']="5.0";
                 }else{
                   $company[$key]['avg_services']=$com['avg_service'];
                 }
                  if($com['avg_quality']=="0.0"){
                  $company[$key]['avg_qualitys']="5.0";
                 }else{
                   $company[$key]['avg_qualitys']=$com['avg_quality'];
                 }
                   if($com['avg_design']=="0.0"){
                  $company[$key]['avg_designs']="5.0";
                 }else{
                   $company[$key]['avg_designs']=$com['avg_design'];
                 }
                 $HONG=array();
                 $HONG['avg_total']=$com['avg_total'];
                 $HONG['avg_service']=$com['avg_service'];
                 $HONG['avg_quality']=$com['avg_quality'];
                 $HONG['avg_design']=$com['avg_design'];
                 $db->update('m_company',$HONG,array('id'=>$value['id']));
              }
        		}
        	}
        }
	    $total = $db->number;
       include $this->template('company');
   }//列表页
   public function intervene(){
	   $db = load_class('db');
	   $result = $db->get_one('m_company',"id='".$GLOBALS['id']."'");
	   include $this->template('company_intervene');
   }//干预值填写页
   public function intervenes(){
       $db = load_class('db');
       $formdata = array();
       $formdata['intervene'] =$GLOBALS['intervene'] ;
       $db->update('m_company',$formdata,array('id'=>$GLOBALS['id']));
       MSG('<script>setTimeout("top.dialog.get(window).close().remove();",700),parent.iframeid.location.reload();</script>申请已提交');
   }//修改干预值
   public function compile(){
       $db = load_class('db');
       $company=$db->get_one('m_company',array('id'=>$GLOBALS['id']),'*');
       $companyo=$db->get_one('company',array('id'=>$GLOBALS['id']),'*');
       $company_data=$db->get_one('company_data',array('id'=>$GLOBALS['id']),'*');
           if($GLOBALS['submit']=='编辑'){
          if($GLOBALS['tese']=="") {
                 MSG('你还没有填写标签');
          }
                $formdata = array();
	               $formdata['tese'] = $GLOBALS['tese'];
	               $db->update('m_company',$formdata,array('id'=>$GLOBALS['teseid']));
	               MSG(L('编辑成功！！'),HTTP_REFERER);
           }
           if ($GLOBALS['submit']==保存) {
                $formdata = array();
                 $formdata['collectnums'] = $GLOBALS['collectnum'];
                 $formdata['browsenums'] = $GLOBALS['browsenum'];
                 $db->update('m_company',$formdata,array('id'=>$GLOBALS['teseid']));
           	MSG(L('保存成功！！'),HTTP_REFERER);
               
           }
       include $this->template('company_compile');

   }

     public function interveneb(){
      $db = load_class('db');
      $result = $db->get_one('m_company',"id='".$GLOBALS['id']."'");
      include $this->template('company_intervenes');
     }
       public function interveneds(){

       $db = load_class('db');
       $formdata = array();
       $formdata['tese'] =$GLOBALS['intervene'] ;
       $db->update('m_company',$formdata,array('id'=>$GLOBALS['id']));
       MSG('<script>setTimeout("top.dialog.get(window).close().remove();",700),parent.iframeid.location.reload();</script>申请已提交');
   }//修改干预值
}
