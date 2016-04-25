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
class homepage_carousel extends WUZHI_admin {
    private $status_array = array(
        1=>'发布',
        3=>'草稿',
        1=>'上架',
        2=>'下架',
        4=>'删除',
    );
  
    function __construct() {
        $this->db = load_class('db');
        $this->private_check();
    }
  
    public function listing() {
         $keytypess = $GLOBALS['shang'];
        $page = isset($GLOBALS['page']) ? intval($GLOBALS['page']) : 1;
        
        $keytypes = isset($GLOBALS['keytypes']) ? $GLOBALS['keytypes'] : '';
        $page = max($page,1);
      
        if($keytypess){
              if($keytypess==1){
               $where="status='1'";
               }else if($keytypess==2){
                 $where="status='2'";
               }else if($keytypess==3){
                $where="status='3'";
                }
                 $result = $this->db->get_list('m_carousel',$where."and status!='4'", '*', 0, 10,$page,'addtime DESC,sort DESC');
          }else if($keytypes==1){
                $where="title like  '%".$GLOBALS['title']."%'";
                $result = $this->db->get_list('m_carousel',$where."and status!='4'", '*', 0, 10,$page,'addtime DESC,sort DESC');
          }else if($keytypes==2){
                     $arr=array();
                     $city_configs = get_config('city_config');
                     foreach ($city_configs as $key1 => $value1) {
                           $arr[]=$value1['city']; 
                     }
                if(isset($GLOBALS['title'])&&!empty($GLOBALS['title'])&&in_array($GLOBALS['title'],$arr)){
                    
                     foreach ($city_configs as $key => $value) {
                           if($value['city']==$GLOBALS['title']){
                               $city=$value['cityid'];
                           }
                     }
                     $where="city like  '%".$city."%'";

                     
                        
                    $result = $this->db->get_list('m_carousel',$where."and status!='4'", '*', 0, 10,$page,'addtime DESC,sort DESC');
                     
                    
                }else{
                    $no_search=0;
                }
          }else{
                 if($GLOBALS['pai']=="tshang"){
                  
                       $result = $this->db->get_list('m_carousel',"status!='4'", '*', 0, 10,$page,'addtime ASC');
                  }else if($GLOBALS['pai']=="txia"){
                       $result = $this->db->get_list('m_carousel',"status!='4'", '*', 0, 10,$page,'addtime DESC');
                  }else if($GLOBALS['pai']=="gshang"){
                       $result = $this->db->get_list('m_carousel',"status!='4'", '*', 0, 10,$page,'intervene ASC');
                  }else if($GLOBALS['pai']=="gxia"){
                       $result = $this->db->get_list('m_carousel',"status!='4'", '*', 0, 10,$page,'intervene DESC');
                  }else{

                       $result = $this->db->get_list('m_carousel',"status!='4'", '*', 0, 10,$page,'addtime DESC,sort DESC');
                  }
          }
        $pages = $this->db->pages;
        $total = $this->db->number;
        // var_dump($keytypes);die;
       include $this->template('homepage_carousel_listing');
    }
    //添加案例
    public function add(){
        $cid ="222";
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

                     $res = $this->db->get_list('m_carousel','status!=4');
                     $titlearr=array();
                     foreach ($res as $key => $value) {
                          $titlearr[$key]=$value['title'];
                     }
                

                     if(in_array($GLOBALS['form']['title'],$titlearr)) {
                     MSG('标题名已存在请重新编写标题名');
                     }

                

                    $formdata = array();
                    $formdata['cid'] = 222;
                    $formdata['title'] = remove_xss($GLOBALS['form']['title']);
                    $formdata['city'] = remove_xss($GLOBALS['form']['city']);
                    $formdata['city']=implode(',',$formdata['city']);
                    // echo '<pre>';print_r($formdata['city']);exit;
                    $formdata['carousel'] = remove_xss(basename($GLOBALS['form']['carousel']));
                    $formdata['address'] = remove_xss($GLOBALS['form']['address']);
                    $formdata['addtime'] = empty($GLOBALS['form']['addtime']) ? SYS_TIME : strtotime($GLOBALS['form']['addtime']);
                    $formdata['status'] = '1';
                   
                
                   $id = $this->db->insert('m_carousel',$formdata);
                 }else if($GLOBALS['submit']=="存为草稿"){
                        $res = $this->db->get_list('m_carousel','status!=4');
                         $titlearr=array();
                         foreach ($res as $key => $value) {
                              $titlearr[$key]=$value['title'];
                         }
                    

                         if(in_array($GLOBALS['form']['title'],$titlearr)) {
                         MSG('标题名已存在请重新编写标题名');
                         }

                             $formdata = array();
                             $formdata['cid'] = 222;
                              $formdata['title'] = remove_xss($GLOBALS['form']['title']);
                                $formdata['city'] = remove_xss($GLOBALS['form']['city']);
                                $formdata['carousel'] = remove_xss(basename($GLOBALS['form']['carousel']));
                                $formdata['address'] = remove_xss($GLOBALS['form']['address']);
                                $formdata['addtime'] = empty($GLOBALS['form']['addtime']) ? SYS_TIME : strtotime($GLOBALS['form']['addtime']);           
                             $formdata['status'] = '3';
             
                      $id = $this->db->insert('m_carousel',$formdata);
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
                if($GLOBALS['submit']=="存为草稿"){
                    MSG(L("存为草稿成功"),'?m=MShouYe&f=homepage_carousel&v=listing&_su=wuzhicms');
                }
                  if($GLOBALS['submit']=="发布"){
                    MSG(L("发布成功"),'?m=MShouYe&f=homepage_carousel&v=listing&_su=wuzhicms');
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

            include $this->template('homepage_carousel_add');
        }
    }
    //编辑案例
    public function edit() {
        // $this->imgModel = new ImageBase();
        if(!isset($GLOBALS['id'])) MSG(L('parameter_error'));
        $id = intval($GLOBALS['id']);
        $cid ="222";
        $cate_config = get_cache('category_'.$cid,'content');
        if(!$cate_config) MSG(L('category not exists'));
        //如果设置了modelid，那么则按照设置的modelid。共享模型添加必须数据必须指定该值。
        if(isset($GLOBALS['modelid']) && is_numeric($GLOBALS['modelid'])) {
            $modelid = $GLOBALS['modelid'];
        } else {
            $modelid = $cate_config['modelid'];
        }
         $re = $this->db->get_one('m_carousel','id="'.$id.'"','*');
         // echo '<pre>';print_r($re);exit;
        if(isset($GLOBALS['submit']) || isset($GLOBALS['submit2'])) {
            
                  if($GLOBALS['submit']=="发布"){
   
                     // $res = $this->db->get_list('m_carousel','status!=4');
                     // $titlearr=array();
                     // foreach ($res as $key => $value) {
                     //      $titlearr[$key]=$value['title'];
                     // }
                

                     // if(in_array($GLOBALS['title'],$titlearr)) {
                     // MSG('标题名已存在请重新编写标题名');
                     // }
                    $formdata = array();
                    $formdata['cid'] = 222;
                               $formdata['title'] = remove_xss($GLOBALS['title']);
                                $formdata['city'] = remove_xss($GLOBALS['city']);
                                $formdata['city']=implode(',',$formdata['city']);
                                // var_dump($formdata['city']);exit;
                                $formdata['carousel'] = remove_xss(basename($GLOBALS['attachment_test']));
                                
                                $formdata['address'] = remove_xss($GLOBALS['address']);
                    $formdata['status'] = '1';
                
                    $this->db->update('m_carousel',$formdata,array('id'=>$id));
                 }else if($GLOBALS['submit']=="存为草稿"){
                     //      $res = $this->db->get_list('m_carousel','status!=4');
                     // $titlearr=array();
                     // foreach ($res as $key => $value) {
                     //      $titlearr[$key]=$value['title'];
                     // }
                

                     // if(in_array($GLOBALS['title'],$titlearr)) {
                     // MSG('标题名已存在请重新编写标题名');
                     // }

                             $formdata = array();
                             $formdata['cid'] = 222;
                               $formdata['title'] = remove_xss($GLOBALS['title']);
                                $formdata['city'] = remove_xss($GLOBALS['city']);
                                $formdata['carousel'] = remove_xss(basename($GLOBALS['carousel']));
                                $formdata['address'] = remove_xss($GLOBALS['address']);
                             $formdata['status'] = '3';
             
                       $this->db->update('m_carousel',$formdata,array('id'=>$id));
                 }
          
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
            MSG(L('轮播图编辑成功'),'?m=MShouYe&f=homepage_carousel&v=listing&_su=wuzhicms');
        } else {

            if($model_r['attr_table']) {
                $attr_table = $model_r['attr_table'];
                if($data['modelid']) {
                    $modelid = $data['modelid'];
                    $attr_table = $models[$modelid]['attr_table'];
                }
                $attrdata = $this->db->get_one("m_carousel",array('id'=>$id));
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
            include $this->template('homepage_carousel_edit');
        }
    }
    public function intervene(){

       $result = $this->db->get_one('m_carousel'," id='".$GLOBALS['id']."'");
       include $this->template('homepage_carousel_intervene');
    }
    public function intervenes(){

        $formdata = array();
        $formdata['intervene'] =$GLOBALS['intervene'] ;
        $this->db->update('m_carousel',$formdata,array('id'=>$GLOBALS['id']));
        MSG('<script>setTimeout("top.dialog.get(window).close().remove();",700),parent.iframeid.location.reload();</script>申请已提交');
    }
  
    //删除单条案例
    public function delete(){
         $Id = isset($GLOBALS['id']) ? intval($GLOBALS['id']) : 1;
         $formdata = array();
             $formdata['status'] = 4;
         $this->db->update('m_carousel',$formdata,array('id'=>$Id)); 
         MSG(L('删除成功！！'),HTTP_REFERER);
    }
    //上架
    public function Putaway(){
         $Id = isset($GLOBALS['id']) ? intval($GLOBALS['id']) : 1;
         $formdata = array();
             $formdata['status'] = 1;
             $formdata['updatetime'] =date('Y-m-d H:i:s') ;
         $this->db->update('m_carousel',$formdata,array('id'=>$Id)); 
         MSG(L('上架成功！！'),HTTP_REFERER);
    }
    //下架
    public function UnShelve(){
         $Id = isset($GLOBALS['id']) ? intval($GLOBALS['id']) : 1;
         $formdata = array();
             $formdata['status'] = 2;
             $formdata['intervene'] = 0;
             $formdata['updatetime'] =date('Y-m-d H:i:s') ;
         $this->db->update('m_carousel',$formdata,array('id'=>$Id)); 
         MSG(L('下架成功！！'),HTTP_REFERER);
    }
    //发布
    public function Publish(){
         $Id = isset($GLOBALS['id']) ? intval($GLOBALS['id']) : 1;
         $formdata = array();
             $formdata['status'] = 1;
             $formdata['updatetime'] =date('Y-m-d H:i:s') ;
         $this->db->update('m_carousel',$formdata,array('id'=>$Id)); 
          MSG(L('发布成功！！'),HTTP_REFERER);
    }
    //批量删除
    public function Deletes(){

        foreach($GLOBALS['ids'] as $id){
             $formdata = array();
             $formdata['status'] = 4;
         $this->db->update('m_carousel',$formdata,array('id'=>$id)); 
        }
       MSG(L('删除成功！！'),HTTP_REFERER);
    }
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
         $company=$db->get_list('company',$where,"*");
             foreach ($company as $key => $value) {
                $res=$db->get_one('m_company',"id='".$value['companyid']."'");
                if($res['id']!=$value['companyid']){
     $db->query("insert into wz_m_company(id,cid,title,search_data,css,thumb,keywords,remark,block,url,sort,status,route,publisher,addtime,updatetime,areaid_1,areaid_2,areaid) select id,cid,companyname,thumb,remark,url,sort,addtime,updatetime,address,companylogo,avg_total,avg_service,avg_design,avg_quality from wz_company where id='".$value['companyid']."'");
                    }
             }

    } 
 
}