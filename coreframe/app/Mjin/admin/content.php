<?php
// +----------------------------------------------------------------------
// | wuzhicms [ 五指互联网站内容管理系统 ]
// | Copyright (c) 2014-2015 http://www.wuzhicms.com All rights reserved.
// | Licensed ( http://www.wuzhicms.com/licenses/ )
// | Author: wangcanjia <phpip@qq.com>
// +----------------------------------------------------------------------
header('Content-type:text/html;charset=utf-8');
defined('IN_WZ') or exit('No direct script access allowed');
require_once(COREFRAME_ROOT.'app/core/libs/class/Gd.class.php');
/**
 * 内容添加
 */
load_class('admin');
define('HTML',true);
class content extends WUZHI_admin {
    private $status_array = array(
        9=>'审核通过',
        8=>'定时发送',
        1=>'一审',
        2=>'二审',
        3=>'三审',
        0=>'回收站',
        7=>'退稿',
        6=>'草稿',
    );
    private $status_array2 = array(
        8=>'定时发送',
        10=>'一审~三审',
        0=>'回收站',
        7=>'退稿',
        6=>'草稿',
    );
    function __construct() {
        $this->db = load_class('db');
        $this->private_check();
        header("cache-control: no-store, no-cache, must-revalidate");
    }
    public function manage() {
        $modelid = isset($GLOBALS['modelid']) ? intval($GLOBALS['modelid']) : 0;
        include $this->template('content_manage');
    }
    public function left() {
        $siteid = get_cookie('siteid');
        $where = array('keyid'=>'content','siteid'=>$siteid);
        if(isset($GLOBALS['modelid']) && $GLOBALS['modelid']!=0) {
            $where['modelid'] = intval($GLOBALS['modelid']);
        } else {
            $result = $this->db->get_list('category', $where, '*', 0, 2000, 0, 'sort ASC', '', 'cid');
        }
        if(empty($result)) {
            $category_tree = '';
        } else {
            $tree = load_class('tree','core',$result);
            $category_tree = $tree->get_treeview(0,'tree', "<li><a href='javascript:w(\$cid);' onclick='o_p(\$cid,this)' class='i-t'>\$name</a></li>","<li><a href='javascript:w(\$cid);' onclick='o_p(\$cid,this)' class='i-t'>\$name</a>");
        }
        include $this->template('content_left');
    }
    public function rensu(){
        $result =$this->db->get_list('m_picture'," status='1'",'*');
            foreach ($result as $key => $value) {
            $sum = $this->db->count('m_picture',"companyid='".$value['companyid']."' and status='1'", 'count(*)  AS total');
            $der = $this->db->get_list('m_picture',"status='1'and companyid='".$value['companyid']."'", '*', 0, 1000000,$page,'','designer');
           $sums = $this->db->count('m_picture',"designer='".$value['designer']."' and status='1'", 'count(*)  AS totals','0','','');
              $formdatad = array();
              $formdatad['designnum'] = count($der);
              $formdatad['photonum'] = $sum['total'];
              $this->db->update('m_company',$formdatad,array('id'=>$value['companyid']));
              $formdatad = array();
              $formdatad['productionnum'] = $sums['totals'];
              $this->db->update('m_company_team',$formdatad,array('id'=>$value['designer']));
             } 
    }
    public function listing() {
        $name=isset($GLOBALS['title']) ? $GLOBALS['title'] : '';
        $keytypess = $GLOBALS['shang'];
        $page = isset($GLOBALS['page']) ? intval($GLOBALS['page']) : 1;
        $type = $GLOBALS['type'];
        $pai = $GLOBALS['pai'] ;
        $page = max($page,1);
        $pagess=$page;
        $keytypes = isset($GLOBALS['keytypes']) ? $GLOBALS['keytypes'] : '';
        $status=array(1=>'name',2=>'designer',3=>'companyname',4=>'city');
        foreach($status as $key=>$attr){
           if($key==$keytypes){
            if($key==2){
                   $re = $this->db->get_one('m_company_team','title="'.$name.'"', '*');
                   $team='and '.$attr." LIKE '%$re[id]%'";
            }else if($key==4){
                   $lid = $this->db->get_one('linkage_data','name like"%'.$name.'%"', 'lid');
                   $lid_one = $this->db->get_one('linkage_data','pid like"%'.$lid['lid'].'%"', 'lid');
                   $city = $this->db->get_list('m_company','areaid_2="'.$lid_one['lid'].'"', '*');
                 $cid=array();
                 foreach($city as $ci){
                   $cid[]=$ci['id'];
                 }
                 if(empty($cid)){
                   $lid = $this->db->get_one('linkage_data','name like"%'.$name.'%"', 'lid');
                   $city = $this->db->get_list('m_company','areaid_2="'.$lid['lid'].'"', '*');
                 $cid=array();
                 foreach($city as $ci){
                   $cid[]=$ci['id'];
                 }
                 }if(!empty($cid)){
                   $company = 'and companyid IN('.implode(',',$cid).')';
                 }else{
                   include $this->template('content_listing');die;
                 }
                }else{
                  $name=isset($GLOBALS['title']) ? $GLOBALS['title'] : '';
                  $team='and '.$attr." LIKE '%$name%'";
            }
         } 
      } 
      if(!empty($keytypess)){
          $statuss='and status ="'.$keytypess.'" ';
      }
        $where="status!='4' ".$team.$statuss.$company;
        if($pai=='shang'){
            $result = $this->db->get_list('m_picture',$where, '*', 0, 10,$page,$type.' ASC');
           }else if($pai=='xia'){
            $result = $this->db->get_list('m_picture',$where, '*', 0, 10,$page,$type.' DESC');
        }
         if($GLOBALS['pai']==null){
            $result = $this->db->get_list('m_picture',$where, '*', 0, 10,$page,'updatetime desc');
         } 
         foreach($result as $key=>$company){
            $companyS = $this->db->get_one('m_company','id='.$company['companyid'],'*');
            if($companyS['areaid_2']!=''){
               $companyname= $this->db->get_one('linkage_data','lid='.$companyS['areaid_2'],'*');
               $result[$key]['com']=$companyname['name'];
            }
         }  
        $pages = $this->db->pages;
        $total = $this->db->number;
        include $this->template('content_listing');
    }
    public function getDesigner(){
        $cid = $GLOBALS['cid'];
        $result = $this->db->get_list('company_team',"companyid=$cid  ", 'id,title');
        echo json_encode($result);
    }
    public function Preview(){

       $config = get_config('m_config');
       $style = $config['style'];
       $house = $config['house'];
       $spac = $config['spac'];
        $id = isset($GLOBALS['id']) ? intval($GLOBALS['id']) : 1;
        $result = $this->db->get_one('m_picture',"id=$id",'*');
        $designer = $this->db->get_one('company_team',"id='".$result['designer']."'",'*');
        $config=get_config('picture_config');
        include $this->template('content_Preview');
    }//预览
    public function add(){
        $category = $this->db->get_one('category',"name='精品案例'",'cid');
        $cid=$category['cid'];
         if(!empty($GLOBALS['idc'])){
          $c['id'] = $GLOBALS['idc'];
          $c['com'] = $GLOBALS['com'];
        $result = $this->db->get_list('company_team',"companyid=".$c['id'], 'id,title');
        $options='';
        foreach ($result as $key => $value) {
          $options.='<option value='.$result[$key]['id'].'>'.$result[$key]['title'].'</option>';
        }
        MSG('<script>setTimeout("top.dialog.get(window).close().remove();",700);$(parent.iframeid.document.body).find("#designer").empty().append("'.$options.'");$(parent.iframeid.document.body).find("#HONG").html("'.$c['com'].'");$(parent.iframeid.document.body).find("#comId").val("'.$c['id'].'");$(parent.iframeid.document.body).find("#comName").val("'.$c['id'].'");</script>OK');
         }
        $cate_config = get_cache('category_'.$cid,'content');
        if(!$cate_config) MSG(L('category not exists'));
        //如果设置了modelid，那么则按照设置的modelid。共享模型添加必须数据必须指定该值。
        if(isset($GLOBALS['modelid']) && is_numeric($GLOBALS['modelid'])) {
            $modelid = $GLOBALS['modelid'];
        } else {
            $modelid = $cate_config['modelid'];
        }
        if(isset($GLOBALS['submit']) || isset($GLOBALS['submit2'])) {

            if($GLOBALS['submit']=="发布"){

            $res = $this->db->get_one('m_picture','name="'.$GLOBALS['form']['name'].'"');

                if($GLOBALS['form']['case']<>''){
                       $a=$GLOBALS['form']['case'];
              $arr=array();
              $arr1 = array();
               foreach($a as $ar){
                   $arr[]=$ar['url'];
                   $alt[]=$ar['alt'];
                   $space[]=$ar['space'];
                   $arr1[]= basename($ar['url']);
               } 
                 $case=implode("|",$arr1);
                 $alt=implode("|",$alt); 
                 $space=implode("|",$space);
                }
            $cover=basename($GLOBALS['form']['cover']);
            $ress = $this->db->get_one('m_company','id="'.$GLOBALS['comName'].'"');
            $st=$GLOBALS['form']['style'];
            foreach ($st as $key => $value) {
              if ($value=='no_value') {
                unset($st[$key]);
              }
            }
            $style=implode(",",$st);
            $formdatas=array();
            $formdatas['updatetime'] = date('Y-m-d H:i:s');
            $formdata = array();
                $formdata['cid'] = 204;
                $formdata['areaid'] = remove_xss($GLOBALS['LK1_1']);
                $formdata['areaid_1'] = remove_xss($GLOBALS['LK1_2']);
                $formdata['areaid_2'] = remove_xss($GLOBALS['LK1_3']);
                $formdata['status'] = '1';
                $formdata['name'] = remove_xss($GLOBALS['form']['name']);
                $formdata['designer'] =remove_xss($GLOBALS['form']['designer']);
                $formdata['area'] = remove_xss($GLOBALS['form']['area']);
                $formdata['style'] = $style;
                $formdata['housetype'] = remove_xss($GLOBALS['form']['housetype']);
                $formdata['materialtotal'] = remove_xss($GLOBALS['form']['materialtotal']);
                $formdata['crafttotal'] = remove_xss($GLOBALS['form']['crafttotal']);
                $formdata['total'] = remove_xss($GLOBALS['form']['total']);
                $formdata['browsenum1'] = remove_xss($GLOBALS['form']['browsenum']);
                $formdata['collectnum1'] = remove_xss($GLOBALS['form']['collectnum']);
                $formdata['sharenum'] = remove_xss($GLOBALS['form']['sharenum']);
                $formdata['addtime'] = date('Y-m-d H:i:s');
                $formdata['updatetime'] = date('Y-m-d H:i:s');
                $formdata['companyname'] = $ress['title'];
                $formdata['companyid'] = $ress['id'];
                $formdata['likes'] = remove_xss($GLOBALS['form']['likes']);
                $formdata['intervene'] =remove_xss($GLOBALS['form']['intervene']); 
                $formdata['total'] =remove_xss($GLOBALS['form']['total']);
                $formdata['cover'] =$cover;
                $formdata['case']=$case;
                $formdata['alt']=$alt;
                $formdata['space']=$space;
                $id = $this->db->insert('m_picture',$formdata);
                $this->db->update('m_company',$formdatas,array('id'=>$res['id']));
                $this->db->update('m_company_team',$formdatas,array('id'=>$GLOBALS['form']['designer']));
                 //-----------------------------------
                    $this->rensu();
               //-----------------------------------
                 }else{
            $a=$GLOBALS['form']['case'];
            $arr=array();
            $arr1 = array();
               foreach($a as $ar){
                   $arr[]=$ar['url'];
                   $alt[]=$ar['alt'];
                   $space[]=$ar['space'];
                   $arr1[]= basename($ar['url']);
               }
                  $case=implode("|",$arr1);
                  $alt=implode("|",$alt);
                  $space=implode("|",$space);
                   $cover=basename($GLOBALS['form']['cover']);
            $res = $this->db->get_one('m_company','id="'.$GLOBALS['comName'].'"');
               $formdatas=array();
               $formdatas['updatetime'] = date('Y-m-d H:i:s');
                 $st=$GLOBALS['form']['style'];
            foreach ($st as $key => $value) {
              if ($value=='no_value') {
                unset($st[$key]);
              }
            }
            $style=implode(",",$st);
            $formdata = array();
                $formdata['cid'] = 204;
                $formdata['areaid'] = remove_xss($GLOBALS['LK1_1']);
                $formdata['areaid_1'] = remove_xss($GLOBALS['LK1_2']);
                $formdata['areaid_2'] = remove_xss($GLOBALS['LK1_3']);
                $formdata['status'] = '3';
                $formdata['name'] = remove_xss($GLOBALS['form']['name']);
                $formdata['designer'] =remove_xss($GLOBALS['form']['designer']);
                $formdata['area'] = remove_xss($GLOBALS['form']['area']);
                $formdata['style'] = $style;
                $formdata['housetype'] = remove_xss($GLOBALS['form']['housetype']);
                $formdata['materialtotal'] = remove_xss($GLOBALS['form']['materialtotal']);
                $formdata['crafttotal'] = remove_xss($GLOBALS['form']['crafttotal']);
                $formdata['total'] = remove_xss($GLOBALS['form']['total']);
                $formdata['browsenum1'] = remove_xss($GLOBALS['form']['browsenum']);
                $formdata['collectnum1'] = remove_xss($GLOBALS['form']['collectnum']);
                $formdata['sharenum'] = remove_xss($GLOBALS['form']['sharenum']);
                $formdata['addtime'] = date('Y-m-d H:i:s');
                $formdata['updatetime'] = date('Y-m-d H:i:s');
                $formdata['companyname'] = $res['title'];
                $formdata['companyid'] = $res['id'];
                $formdata['likes'] = remove_xss($GLOBALS['form']['likes']);
                $formdata['intervene'] =remove_xss($GLOBALS['form']['intervene']); 
                $formdata['total'] =remove_xss($GLOBALS['form']['total']);
                $formdata['cover'] =$cover;
                $formdata['case']=$case;
                $formdata['alt']=$alt;
                $formdata['space']=$space;
             $id = $this->db->insert('m_picture',$formdata);
             $this->db->update('m_company',$formdatas,array('id'=>$res['id']));
             $this->db->update('m_company_team',$formdatas,array('id'=>$GLOBALS['form']['designer']));
                 }
           
            if($cate_config['type']) {
                $urls['url'] = $cate_config['url'];
            } elseif($formdata['master_data']['route']>1) {//外部链接
                $urls['url'] = remove_xss($GLOBALS['url']);
            } else {
                //生成url
                $urlclass = load_class('url','content',$cate_config);
                $urls = $urlclass->showurl(array('id'=>$id,'cid'=>$cid,'addtime'=>$addtime,'page'=>1,'route'=>$formdata['master_data']['route']));
            }
            //$this->db->update($formdata['master_table'],array('url'=>$urls['url']),array('id'=>$id));
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

            //判断是否存在，防止意外发生
            if(!$this->db->get_one('content_rank',array('cid'=>$cid,'id'=>$id))) {
                //统计表加默认数据
                $this->db->insert('content_rank',array('cid'=>$cid,'id'=>$id,'updatetime'=>SYS_TIME));
            }
            //生成静态
            if($cate_config['showhtml'] && $formdata['master_data']['status']==9) {
                $data = $this->db->get_one($formdata['master_table'],array('id'=>$id));
                if(!empty($formdata['attr_table'])) {
                    $attrdata = $this->db->get_one($formdata['attr_table'],array('id'=>$id));
                    $data = array_merge($data,$attrdata);
                }
                //上一页
                $data['previous_page'] = $this->db->get_one($formdata['master_table'],"`cid` = '$cid' AND `id`<'$id' AND `status`=9",'*',0,'id DESC');
                //下一页
                $data['next_page'] = '';
                $this->html = load_class('html','content');
                $this->html->set_category($cate_config);
                $this->html->set_categorys();
                $this->html->load_formatcache();
                $this->html->show($data,1,1,$urls['root']);
                $loadhtml = true;
            } else {
                $loadhtml = false;
            }
            //生成相关栏目列表
            if($cate_config['listhtml']) {
                if($loadhtml==false) {
                    $this->html = load_class('html','content');
                    $this->html->set_category($cate_config);
                    $this->html->set_categorys();
                    $loadhtml = true;
                }
                for($i=1;$i<6;$i++) {
                    $cateurls = $urlclass->listurl(array('cid'=>$cid,'page'=>$i));
                    $this->html->listing($cateurls['root'],$i);
                    if($GLOBALS['result_lists']==0) {
                        break;
                    }
                }
            }
            //生成首页
            if($loadhtml) {
                $this->html->index();
            } else {
                $this->html = load_class('html','content');
                $this->html->set_categorys();
                $this->html->index();
            }
            //添加到最新列表中
            $lastlist = get_cache('lastlist','content');
            $newcontent = array(0=>array('cid'=>$cid,'title'=>$formdata['master_data']['title'],'url'=>$urls['url'],'addtime'=>SYS_TIME));
            if(is_array($lastlist)) {
                $lastlist = array_merge($newcontent,$lastlist);
                if(count($lastlist)>100) array_pop($lastlist);
            } else {
                $lastlist = $newcontent;
            }
            set_cache('lastlist',$lastlist,'content');
            //编辑操作日志
            $this->editor_logs('add',$formdata['master_data']['title'],$urls['url'], "?m=content&f=content&v=edit&id=$id&cid=$cid");
            //设置返回地址
            if(isset($GLOBALS['submit'])) {
                if($GLOBALS['submit']=="保存"){
   
                    MSG(L("保存成功"),'?m=Mjin&f=content&v=listing&_su=wuzhicms');
                }
                  if($GLOBALS['submit']=="发布"){
                    MSG(L("发布成功"),'?m=Mjin&f=content&v=listing&_su=wuzhicms');
                }
            } else {
                MSG(L('add success'),URL(),1000);
            }
        } else {
            load_function('template');
            load_function('content','content');
            $status = isset($GLOBALS['status']) ? intval($GLOBALS['status']) : 9;
            require get_cache_path('content_form','model');
            $form_build = new form_build($modelid);
            $form_build->cid = $cid;
            $category = get_cache('category','content');
            $form_build->extdata['catname'] = $cate_config['name'];
            $form_build->extdata['type'] = $cate_config['type'];
            $formdata = $form_build->execute();
            load_class('form');
            $show_formjs = 1;
            $show_dialog = 1;

            include $this->template('content_add');
        }
    }//添加案例

    public function checkNameExit(){
        $name = $GLOBALS['name'];
        $res = $this->db->get_one('m_picture',"name='".$name."' and status!=4",'id');
        die(json_encode($res['id']));
    }
    public function edit() {
        // $this->imgModel = new ImageBase();
        if(!isset($GLOBALS['id'])) MSG(L('parameter_error'));
        $id = intval($GLOBALS['id']);
        $cid = '204';
        $cate_config = get_cache('category_'.$cid,'content');
        $result = $this->db->get_list('linkage_data','pid=0','*');
        $re = $this->db->get_one('m_picture','id="'.$id.'"','*');
        $res = $this->db->get_list('linkage_data','pid="'.$re['areaid'].'"','*');
        $resu = $this->db->get_list('linkage_data','pid="'.$re['areaid_1'].'"','*');
        $company= $this->db->get_list('m_company',$a,'*');
        $designer= $this->db->get_list('company_team','companyid="'.$re['companyid'].'"','*');
          $config = get_config('m_config');
       $style = $config['style'];
       $house = $config['house'];
       $spac = $config['spac'];
        if(isset($GLOBALS['modelid']) && is_numeric($GLOBALS['modelid'])) {

         $modelid = $GLOBALS['modelid'];
        } else {
          $modelid = $cate_config['modelid'];

            }
        $res = $this->db->get_one('m_picture','id="'.$GLOBALS['id'].'"');
        if(isset($GLOBALS['submit']) || isset($GLOBALS['submit2'])) {
            if(!empty($GLOBALS['url'])){
                   $cae=implode("|",$GLOBALS['url'] );

            }
            if(!empty($GLOBALS['form']['space'])){
                   $spa=implode("|",$GLOBALS['form']['space'] );
            }
            if(!empty($GLOBALS['form']['photos'])){
                   $photos=implode("|",$GLOBALS['form']['photos'] );
            }
              
                $a=$GLOBALS['form']['case'];
               if(!empty($a)){
                  $arr=array();
                  $arr1 = array();
               foreach($a as $ar){
                   $arr[]=$ar['url'];
                   $alt[]=$ar['alt'];
                   $space[]=$ar['space'];
                  $arr1[]= basename($ar['url']);

               }
                  $case=implode("|",$arr1);
                  if($cae==""){
                    $cae=$case;
                  }else{
                     $cae.='|'.$case;
                  }
                  $alt=implode("|",$alt);
                    if($photos==""){
                    $photos=$alt;
                  
                  }else{
                    $photos.="|".$alt;
                  }
               
                  $space=implode("|",$space);
                    if($spa==""){
                     $spa=$space;
                  }else{
                     $spa.='|'.$space;
                  }
            } 
      
            $styles=implode(",",$GLOBALS['styles']);
            $res = $this->db->get_one('m_company','id="'.$GLOBALS['comName'].'"');
            $formdatas=array();
            $formdatas['updatetime'] = date('Y-m-d H:i:s');
            $formdata = array();
                $formdata['areaid'] = remove_xss($GLOBALS['areaid']);
                $formdata['areaid_1'] = remove_xss($GLOBALS['areaid_1']);
                $formdata['areaid_2'] = remove_xss($GLOBALS['areaid_2']);
                $formdata['name'] = remove_xss($GLOBALS['name']);
                $formdata['designer'] =remove_xss($GLOBALS['designer']);
                $formdata['area'] = remove_xss($GLOBALS['area']);
                $formdata['style'] = $styles;
                $formdata['housetype'] = remove_xss($GLOBALS['housetype']);
                $formdata['materialtotal'] = remove_xss($GLOBALS['materialtotal']);
                $formdata['crafttotal'] = remove_xss($GLOBALS['crafttotal']);
                $formdata['total'] = remove_xss($GLOBALS['total']);
                $formdata['browsenum1'] = remove_xss($GLOBALS['browsenum']);
                $formdata['collectnum1'] = remove_xss($GLOBALS['collectnum']);
                $formdata['sharenum'] = remove_xss($GLOBALS['sharenum']);
                $formdata['updatetime'] = date('Y-m-d H:i:s');
                $formdata['companyname'] = $res['title'];
                $formdata['companyid'] = remove_xss($GLOBALS['comName']);
                $formdata['likes'] = remove_xss($GLOBALS['likes']);
                $formdata['total'] =remove_xss($GLOBALS['total']);
                $formdata['cover'] =basename($GLOBALS['attachment_test']);
                $formdata['case']=$cae;
                $formdata['alt']=$photos;
                $formdata['space']=$spa;
            // var_dump($formdata['case']);die;
             $this->db->update('m_picture',$formdata,array('id'=>$id));
             $this->db->update('m_company',$formdatas,array('id'=>$res['id']));
             $this->db->update('m_company_team',$formdatas,array('id'=>$GLOBALS['designer']));
                //-----------------------------------统计精品案例数
               $this->rensu();
               //-----------------------------------
            //执行更新
            require get_cache_path('content_update','model');
            $form_update = new form_update($modelid);
            $formdata['master_data']['id'] = $id;
            $form_update->execute($formdata);
            //生成静态
            if($cate_config['showhtml'] && $formdata['master_data']['status']==9) {
                $data = $this->db->get_one($formdata['master_table'],array('id'=>$id));
                if(!empty($formdata['attr_table'])) {
                    $attrdata = $this->db->get_one($formdata['attr_table'],array('id'=>$id));
                    $data = array_merge($data,$attrdata);
                }
                //上一页
                $data['previous_page'] = $this->db->get_one($formdata['master_table'],"`cid` = '$cid' AND `id`<'$id' AND `status`=9",'*',0,'id DESC');
                //下一页
                $data['next_page'] = $this->db->get_one($formdata['master_table'],"`cid`= '$cid' AND `id`>'$id' AND `status`=9",'*',0,'id ASC');
                $this->html = load_class('html','content');
                $this->html->set_category($cate_config);
                $this->html->set_categorys();
                $this->html->load_formatcache();
                $this->html->show($data,1,1,$urls['root']);
                $loadhtml = true;
            } else {
                $loadhtml = false;
            }
            //生成相关栏目列表
            if($cate_config['listhtml']) {
                if($loadhtml==false) {
                    $this->html = load_class('html','content');
                    $this->html->set_category($cate_config);
                    $this->html->set_categorys();
                    $loadhtml = true;
                }
                for($i=1;$i<6;$i++) {
                    $cateurls = $urlclass->listurl(array('cid'=>$cid,'page'=>$i));
                    $this->html->listing($cateurls['root'],$i);
                    if($GLOBALS['result_lists']==0) {
                        break;
                    }
                }
            }
       
            if($loadhtml) {
                $this->html->index();
            } else {
                $this->html = load_class('html','content');
                $this->html->set_categorys();
                $this->html->index();
            }
            $this->editor_logs('edit',$formdata['master_data']['title'],$urls['url'], "?m=content&f=content&v=edit&id=$id&cid=$cid");
            MSG(L('案例编辑成功'),'?m=Mjin&f=content&v=listing&_su=wuzhicms');
        } else {

            if($model_r['attr_table']) {
                $attr_table = $model_r['attr_table'];
                if($data['modelid']) {
                    $modelid = $data['modelid'];
                    $attr_table = $models[$modelid]['attr_table'];
                }
                $attrdata = $this->db->get_one("m_picture",array('id'=>$id));
                $data = array_merge($data,$attrdata);

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
            include $this->template('content_edit');
        }
    }//编辑案例
    public function intervene(){
       $result = $this->db->get_one('m_picture'," id='".$GLOBALS['id']."'");
       include $this->template('content_intervene');
    }
    public function intervenes(){
        $formdata = array();
        $formdata['intervene'] =$GLOBALS['intervene'] ;
        $this->db->update('m_picture',$formdata,array('id'=>$GLOBALS['id']));
        MSG('<script>setTimeout("top.dialog.get(window).close().remove();",700),parent.iframeid.location.reload();</script>申请已提交');
    }
    public function public_threeLevel(){
        $pid = isset($GLOBALS['pid'])?intval($GLOBALS['pid']):0;
        $result = $this->db->get_list('linkage_data', array('pid'=>$pid), 'lid,name');
        die(json_encode($result));
    }
    public function Previews(){
         if($GLOBALS['form']['case']<>''){
            $a=$GLOBALS['form']['case'];
              $arr=array();
              $arr1 = array();
               foreach($a as $ar){
                   $arr[]=$ar['url'];
                   $alt[]=$ar['alt'];
                   $space[]=$ar['space'];
                   $arr1[]= basename($ar['url']);
               }
                 $case=implode("|",$arr1);
                 $alt=implode("|",$alt); 
                 $space=implode("|",$space);
                }
            $res = $this->db->get_one('m_company','id="'.$GLOBALS['form']['companyname'].'"');
            $formdata = array();
                $formdata['cid'] = 204;
                $formdata['areaid'] = remove_xss($GLOBALS['LK1_1']);
                $formdata['areaid_1'] = remove_xss($GLOBALS['LK1_2']);
                $formdata['areaid_2'] = remove_xss($GLOBALS['LK1_3']);
                $formdata['name'] = remove_xss($GLOBALS['form']['name']);
                $formdata['designer'] =remove_xss($GLOBALS['form']['designer']);
                $formdata['area'] = remove_xss($GLOBALS['form']['area']);
                $formdata['style'] = remove_xss($GLOBALS['form']['style']);
                $formdata['housetype'] = remove_xss($GLOBALS['form']['housetype']);
                $formdata['materialtotal'] = remove_xss($GLOBALS['form']['materialtotal']);
                $formdata['crafttotal'] = remove_xss($GLOBALS['form']['crafttotal']);
                $formdata['total'] = remove_xss($GLOBALS['form']['total']);
                $formdata['browsenum'] = remove_xss($GLOBALS['form']['browsenum']);
                $formdata['collectnum'] = remove_xss($GLOBALS['form']['collectnum']);
                $formdata['sharenum'] = remove_xss($GLOBALS['form']['sharenum']);
                $formdata['addtime'] = date('Y-m-d h:i:s');
                $formdata['updatetime'] = date('Y-m-d h:i:s');
                $formdata['companyname'] = $res['title'];
                $formdata['companyid'] = $res['id'];
                $formdata['likes'] = remove_xss($GLOBALS['form']['likes']);
                $formdata['intervene'] =remove_xss($GLOBALS['form']['intervene']); 
                $formdata['total'] =remove_xss($GLOBALS['form']['total']);
                $formdata['cover'] =basename($GLOBALS['form']['cover']);
                $formdata['case']=$case;
                $formdata['alt']=$alt;
                $formdata['space']=$space;
                $id = $this->db->insert('m_Preview',$formdata);
                $date=date('Y-m-d h:i:s');
                $this->db->delete('m_Preview',"addtime!='".$date."'");
                $config = get_config('m_config');
                $style = $config['style'];
                $house = $config['house'];
                $spac = $config['spac'];
         $result = $this->db->get_one('m_Preview',"addtime='".$date."'", '*', 0, 10,$page,'sort DESC');
         $designer = $this->db->get_one('company_team',"id='".$result['designer']."'",'*');
         include $this->template('content_Previews');
    }
    public function delete(){
         $Id = isset($GLOBALS['id']) ? intval($GLOBALS['id']) : 1;
         $formdata = array();
         $formdata['status'] = 4;
         $this->db->update('m_picture',$formdata,array('id'=>$Id)); 
         $intervene =$this->db->get_list('m_picture'," status='1' and companyid='".$GLOBALS['companyid']."'",'*');
         if(count($intervene)==0){
             $formdata = array();
             $formdata['intervene'] ='';      
             $this->db->update('m_company',$formdata,array('id'=>$GLOBALS['companyid']));
         }
          $this->rensu();
         MSG(L('删除成功！！'),HTTP_REFERER);
    }//删除单条案例
    public function Putaway(){
         $Id = isset($GLOBALS['id']) ? intval($GLOBALS['id']) : 1;
         $re = $this->db->get_one('m_picture',"id='".$Id."'");
         $formdata = array();
             $formdata['status'] = 1;
             $formdata['updatetime'] =date('Y-m-d H:i:s') ;
          $this->db->update('m_picture',$formdata,array('id'=>$Id)); 
          $formdata = array();
          $formdata['updatetime'] =date('Y-m-d H:i:s') ;
          $this->db->update('m_company',$formdata,array('id'=>$re['companyid']));
          $this->db->update('m_company_team',$formdata,array('id'=>$GLOBALS['designer']));
         $this->rensu();
         MSG(L('上架成功！！'),HTTP_REFERER);
    }//上架
    public function UnShelve(){
         $Id = isset($GLOBALS['id']) ? intval($GLOBALS['id']) : 1;
         $formdata = array();
             $formdata['status'] = 2;
             $formdata['intervene'] = 0;
             $formdata['updatetime'] =date('Y-m-d H:i:s') ;
           $this->db->update('m_picture',$formdata,array('id'=>$Id)); 
           $this->rensu();
           MSG(L('下架成功！！'),HTTP_REFERER);
    }//下架
    public function Publish(){
         $Id = isset($GLOBALS['id']) ? intval($GLOBALS['id']) : 1;
         $res = $this->db->get_one('m_picture','id="'.$Id .'"');
               $formdatas=array();
               $formdatas['updatetime'] = date('Y-m-d H:i:s');
              $this->db->update('m_company',$formdatas,array('id'=>$res['companyid']));
              $this->db->update('m_company_team',$formdatas,array('id'=>$res['designer']));
         $formdata = array();
             $formdata['status'] = 1;
             $formdata['updatetime'] =date('Y-m-d H:i:s') ;
          $this->db->update('m_picture',$formdata,array('id'=>$Id)); 
          $this->rensu();
          MSG(L('发布成功！！'),HTTP_REFERER);
    }//发布
    public function Deletes(){
            if(!empty($GLOBALS['ids'])){
           $intervene =$this->db->get_list('m_picture', 'status=1 and id IN('.implode(',',$GLOBALS['ids']).')','*');
            }
      foreach($intervene as $in){
           $intervenes =$this->db->get_list('m_picture', 'status=1 and companyid="'.$in['companyid'].'"','*');
            if(count($intervenes)==1){
               $formdata = array();
               $formdata['intervene']='';      
               $this->db->update('m_company',$formdata,array('id'=>$in['companyid']));
            }
      }
        foreach($GLOBALS['ids'] as $id){
             $formdata = array();
             $formdata['status'] = 4;
         $this->db->update('m_picture',$formdata,array('id'=>$id)); 
        }
       $this->rensu();
       MSG(L('删除成功！！'),HTTP_REFERER);
    }//批量删除
    private function _status($status) {
        $status_array = $this->status_array;
        $string = '';
        foreach($status_array as $k=>$s) {
            if($k==$status) {
                $string .= '<a href="#" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"><i class="icon-check btn-icon"></i>'.$s.'<span class="caret"></span></a>';
            }
        }
        $string .= '<ul class="dropdown-menu">';
        foreach($status_array as $k=>$s) {
            if($k!=$status) {
                $url = URL().'&status='.$k;
                $url = url_unique($url);
                $string .= '<li><a href="?'.$url.'">'.$s.'</a></li>';
            }
        }
        $string .= '</ul>';
        return $string;
    }
    private function private_check() {
        $role = $_SESSION['role'];
        if($role==='1') return true;
        $actionids = array(1=>'listing',2=>'add',3=>'edit',4=>'delete',5=>'sort');
        if(in_array(V,$actionids)) {
            $cid = intval($GLOBALS['cid']);
            if($cid==0) return true;
            $actionids = array_flip($actionids);
            $actionid = $actionids[V];
            if(!$this->db->get_one('category_private',array('role'=>$role,'cid'=>$cid,'actionid'=>$actionid))) {
                //查看副栏目是否给予权限，如果有，则继承权限
                $category = get_cache('category_'.$cid,'content');
                if($category['pid']) {
                    if($this->db->get_one('category_private',array('role'=>$role,'cid'=>$category['pid'],'actionid'=>$actionid))) {
                        return true;
                    }
                }
                MSG(L('no content private'));
            }
        }
    }
    public function Sync(){
         $db = load_class('db');
         $company=$db->get_list('company',$where,"*",0,10000000);
         $company_copy=$db->get_list('m_company',$where,"*",0,10000000);
         foreach ($company as $key => $value) {
            $res=$db->get_one('m_company',"id='".$value['id']."'");
            if($res['updatetime']!=$value['updatetime']){
              if($res['id']==$value['id']){
                 $formdata=array();
                 $formdata['status'] =$value['status'];
                 $formdata['updatetime'] =$value['updatetime'];
                 $this->db->update('m_company',$formdata,array('id'=>$value['id']));
              }else{
                 $db->query("insert into wz_m_company(id,cid,title,search_data,css,thumb,keywords,remark,block,url,sort,status,route,publisher,addtime,updatetime,areaid_1,areaid_2,areaid,style,address,order_numbers,avg_total,avg_design,avg_service,avg_quality,check_company,check_money,check_cert,typeids,areaids,domain,companylogo,collection,leyu_cid,leyu_gid) select id,cid,title,search_data,css,thumb,keywords,remark,block,url,sort,status,route,publisher,addtime,updatetime,areaid_1,areaid_2,areaid,style,address,order_numbers,avg_total,avg_design,avg_service,avg_quality,check_company,check_money,check_cert,typeids,areaids,domain,companylogo,collection,leyu_cid,leyu_gid from wz_company where id='".$value['id']."'");
              }
           }
         }//装修公司同步
         $designer=$db->get_list('company_team',$where,"*",0,10000000);
         $designer_copy=$db->get_list('m_company_team',$where,"*",0,10000000);
         foreach ($designer as $key => $designer) {
             $designers=$db->get_one('m_company_team',"id='".$designer['id']."'");
               if($designers['updatetime']!=$designer['updatetime']){
                  if($designers['id']==$designer['id']){
                   $formdatas=array();
                   $formdatas['status'] =$designer['status'];
                   $formdatas['updatetime'] =$designer['updatetime'];
                   $this->db->update('m_company_team',$formdatas,array('id'=>$designer['id']));
                }else{
                  $db->query("insert into wz_m_company_team(id,cid,title,css,thumb,keywords,remark,block,url,sort,status,route,publisher,addtime,updatetime,companyid,ranks) select id,cid,title,css,thumb,keywords,remark,block,url,sort,status,route,publisher,addtime,updatetime,companyid,ranks from wz_company_team where id='".$designer['id']."'");
                  }
                }
              }//设计师同步
          MSG(L('同步完成！！'),HTTP_REFERER);
    } 
    public function company(){
          if(!empty($GLOBALS['title'])){
             $company =$this->db->get_list('m_company','status=9 and title like"%'.$GLOBALS['title'].'%"','id,title');
            }else{
            $company =$this->db->get_list('m_company','status=9','id,title');
          }
          include $this->template('content_cintervene');
    }
    public function kai(){
       $kai =$this->db->get_list('linkage_data','kai="kai" and pid!=""','name');
       include $this->template('content_kai');
    }
    public function kais(){
      if(empty($GLOBALS['kai'])){
           MSG(L('你还没有填写要开通的城市'),HTTP_REFERER);
        }
        $company =$this->db->get_list('linkage_data','name="'.$GLOBALS['kai'].'"','*');
        if(empty($company)){
           MSG(L('没有找到你要开通的城市'),HTTP_REFERER);
        }
        if(!empty($company[0]['kai'])){
           MSG(L($GLOBALS['kai'].'城市站已开通'),HTTP_REFERER);
        }
        foreach ($company as $key => $value) {
          $formdata=array();
          $formdata['kai']='kai';
          $this->db->update('linkage_data',$formdata,array('lid'=>$value['lid']));
        }
        MSG(L($GLOBALS['kai'].'城市站开通成功'),HTTP_REFERER);
    }
    
}