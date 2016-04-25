<?php
defined('IN_WZ') or exit('No direct script access allowed');
load_class('admin');
load_class('form');
class designer extends WUZHI_admin {
    function __construct() {
        parent::__construct();
        header("cache-control: no-store, no-cache, must-revalidate");
    }
  public function listing(){
        $page = isset($GLOBALS['page']) ? intval($GLOBALS['page']) : 1;
        $pagess=$page ;
        $keytypes = isset($GLOBALS['keytypes']) ? $GLOBALS['keytypes'] : '';
        $page = max($page,1);
        $db = load_class('db');
        $result = $db->get_list('m_picture'," status='1'",'*');//查询精品案例的数据
        //定义新的数组
         if($keytypes==2){
          $where='title like "%'.$GLOBALS['designers'].'%"';
         }if($keytypes==3){
          $where='companyname like "%'.$GLOBALS['designers'].'%"';
         }if($keytypes==4){
                $lid = $db->get_one('linkage_data','name like"%'.$GLOBALS['designers'].'%"', 'lid');
                 $lid_one = $db->get_one('linkage_data','pid like"%'.$lid['lid'].'%"', 'lid');
                 $city = $db->get_list('m_company','areaid_2="'.$lid_one['lid'].'"', '*');
                 $cid=array();
                 foreach($city as $ci){
                  $cid[]=$ci['id'];
                 }
                 if(empty($cid)){
                 $lid = $db->get_one('linkage_data','name like"%'.$GLOBALS['designers'].'%"', 'lid');
                 $city = $db->get_list('m_company','areaid_2="'.$lid['lid'].'"', '*');
                 $cid=array();
                 foreach($city as $ci){
                  $cid[]=$ci['id'];
                 }
                 }
                  if(empty($cid)){
                  include $this->template('designer');die;
                 }
                $where = 'companyid IN('.implode(',',$cid).')';
         }  
         $arr=array();
        foreach ($result as $key => $value) {
          $arr[]=$value['designer'];//把循环出来的id放到新的数组里
         }

           $pa=array(
               array(
                    pa=>'ashang',
                    su=>'productionnum',
                    pan=>"shang"
                ),
               array(
                    pa=>'axia',
                    su=>'productionnum',
                    pan=>"xia"
                ),
               array(
                    pa=>'lshang',
                    su=>'des_browsenum',
                    pan=>"shang"
                ),
                 array(
                    pa=>'lxia',
                    su=>'des_browsenum',
                    pan=>"xia"
                ),
                   array(
                    pa=>'sshang',
                    su=>'design_collectnum',
                    pan=>"shang"
                ),
                 array(
                    pa=>'sxia',
                    su=>'design_collectnum',
                    pan=>"xia"
                )
           );//定义页面穿过来的排序的二维数组
           if(!empty($arr)){
       if($GLOBALS['designers']!=""){//判断前段搜索框里的value值是否为空不为空进行搜索为空进行默认的列表页
             $designer=$db->get_list('m_company_team',$where.'and id IN('.implode(',',$arr).')',"*", 0, 10, $page,'updatetime desc');
           }else{
             $designer=$db->get_list('m_company_team','id IN('.implode(',',$arr).')',"*", 0, 10, $page,'updatetime desc');
        }}
          foreach ($pa as $key => $pas) {
            if($pas['pa']==$GLOBALS['pai']){
              if($pas['pan']=='shang'){
                $designer = $db->get_list('m_company_team','id IN('.implode(',',$arr).')',"*", 0, 10, $page,$pas['su'].' ASC');
                 foreach($designer as$key=>$der){
                    $companyS = $db->get_one('m_company','id='.$der['companyid'],'*');
                    $companySs = $db->get_one('m_picture','companyid='.$der['companyid'],'*');
                    $companyname= $db->get_one('linkage_data','lid='.$companyS['areaid_2'],'*');
                    $designer[$key]['com']=$companyname['name'];
                    $designer[$key]['companynames']=$companySs['companyname'];
           }
              }else if($pas['pan']=='xia'){
                $designer = $db->get_list('m_company_team','id IN('.implode(',',$arr).')',"*", 0, 10, $page,$pas['su'].' DESC');
                 foreach($designer as$key=>$der){
              $companyS = $db->get_one('m_company','id='.$der['companyid'],'*');
              $companySs = $db->get_one('m_picture','companyid='.$der['companyid'],'*');
              $companyname= $db->get_one('linkage_data','lid='.$companyS['areaid_2'],'*');
              $designer[$key]['com']=$companyname['name'];
              $designer[$key]['companynames']=$companySs['companyname'];
           }
              }
             }
           }
           foreach($designer as$key=>$der){
              $companyS = $db->get_one('m_company','id='.$der['companyid'],'*');
              $companySs = $db->get_one('m_picture','companyid='.$der['companyid'],'*');
              $companyname= $db->get_one('linkage_data','lid='.$companyS['areaid_2'],'*');
              $designer[$key]['com']=$companyname['name'];
              $designer[$key]['companynames']=$companySs['companyname'];
           }
          foreach ($designer as $key => $value) {
           $company=$db->get_one('m_company','id="'.$value['companyid'].'" ',"*");
                $formdatad = array();
                $formdatad['companyname'] = $company['title'];
                $db->update('m_company_team',$formdatad,array('id'=>$value['id']));
           }
         
        $total = $db->number;
        $pages = $db->pages;
        include $this->template('designer');
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
       $der=array(
        array(
          id=>1,
          name=>'首席设计师'
          ),
        array(
           id=>2,
          name=>'主任设计师'
          ),
        array(
           id=>3,
          name=>'优秀设计师'
          )
        );

       $designer=$db->get_one('m_company_team',array('id'=>$GLOBALS['id']),'*');
       $companyid=$db->get_one('m_company',array('id'=>$designer['companyid']),'*');
       $designer_data=$db->get_one('company_team_data',array('id'=>$GLOBALS['id']),'*');
           if ($GLOBALS['submit']==保存) {
             $designer_data=$db->get_one('company_team_data',array('id'=>$GLOBALS['derid']),'*');
               if(strip_tags($designer_data['content'])!=$GLOBALS['dercontent']){
                 $formdatas = array();
                 $formdatas['content'] = $GLOBALS['dercontent'];
                 $db->update('company_team_data',$formdatas,array('id'=>$GLOBALS['derid']));
               }
              if($GLOBALS['keytypes']==1){
                $name='首席设计师';
              }elseif($GLOBALS['keytypes']==2){
                $name='主任设计师';
              }elseif($GLOBALS['keytypes']==3){
                $name='优秀设计师';
              }
                 $formdata = array();
                 $formdata['ranks'] = $name;
                 $formdata['thumb1'] = basename($GLOBALS['setting']['recphoto']);
                 $formdata['collectnums'] = $GLOBALS['collectnum'];
                 $formdata['browsenums'] = $GLOBALS['browsenum'];
                 $db->update('m_company_team',$formdata,array('id'=>$GLOBALS['derid']));
            	MSG(L('保存成功！！'),'?m=Mjin&f=designer&v=listing&_su=wuzhicms');
           }
       include $this->template('designer_compile');

   }
}
