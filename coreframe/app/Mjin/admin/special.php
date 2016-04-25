<?php
header('Content-type:text/html;charset=utf-8');
defined('IN_WZ') or exit('No direct script access allowed');
load_class('admin');
load_class('form');
class special extends WUZHI_admin {
      function __construct() {
        $this->db = load_class('db');
        header("cache-control: no-store, no-cache, must-revalidate");
    }
	public function listing(){
	 $page = isset($GLOBALS['page']) ? intval($GLOBALS['page']) : 1;
    $keytypess = $GLOBALS['shang'];
    $pagess=$page ;
    $page = max($page,1);
		$db=load_class('db');
     $pa=array(
               array(
                    pa=>'tshang',
                    su=>'updatetime',
                    pan=>"shang"
                ),
               array(
                    pa=>'txia',
                    su=>'updatetime',
                    pan=>"xia"
                ),
               array(
                    pa=>'gshang',
                    su=>'intervene',
                    pan=>"shang"
                ),
               array(
                    pa=>'gxia',
                    su=>'intervene',
                    pan=>"xia"
                ),
          );
      if($keytypess==1){
          $where.="status='1' and ";
        }  if($keytypess==2){
          $where.="status='2' and ";
        }  if($keytypess==3){
          $where.="status='3' and ";
        }
		$special=$this->db->get_list('m_p_special',$where.'status!=4','*',0,10,$page,'updatetime desc','');
		$total = $db->number;
        $pages = $db->pages;
           foreach ($pa as $key => $pas) {
          if($pas['pa']==$GLOBALS['pai']){
            if($pas['pan']=='shang'){
              $special = $this->db->get_list('m_p_special','status!=4',"*", 0, 10, $page,$pas['su'].' ASC');
            }else if($pas['pan']=='xia'){
              $special = $this->db->get_list('m_p_special','status!=4',"*", 0, 10, $page,$pas['su'].' DESC');
            }
          }
        }
       include $this->template('special_listing');
	}
	public function delete(){
     
	   		$db=load_class('db');
         $Id = isset($GLOBALS['id']) ? intval($GLOBALS['id']) : 1;
         $formdata = array();
            $formdata['status'] = 4;
         $db->update('m_p_special',$formdata,array('id'=>$Id)); 
         MSG(L('删除成功！！'),HTTP_REFERER);
    }//删除单条案例
    public function Putaway(){
    	
         $Id = isset($GLOBALS['id']) ? intval($GLOBALS['id']) : 1;
         $formdata = array();
             $formdata['status'] = 1;
             $formdata['updatetime'] =date('Y-m-d H:i:s');
        $this->db->update('m_p_special',$formdata,array('id'=>$Id)); 
         MSG(L('上架成功！！'),HTTP_REFERER);
    }//上架
    public function UnShelve(){
    		
         $Id = isset($GLOBALS['id']) ? intval($GLOBALS['id']) : 1;
         $formdata = array();
             $formdata['status'] = 2;
             $formdata['updatetime'] =date('Y-m-d H:i:s') ;
        $this->db->update('m_p_special',$formdata,array('id'=>$Id)); 
         MSG(L('下架成功！！'),HTTP_REFERER);
    }//下架
    public function Publish(){
         $Id = isset($GLOBALS['id']) ? intval($GLOBALS['id']) : 1;
         $formdata = array();
             $formdata['status'] = 1;
             $formdata['updatetime']= date('Y-m-d H:i:s') ;
        $this->db->update('m_p_special',$formdata,array('id'=>$Id)); 
          MSG(L('发布成功！！'),HTTP_REFERER);
    }//发布
    public function Deletes(){
    		$db=load_class('db');

               foreach($GLOBALS['ids'] as $id){
             $formdata = array();
             $formdata['status'] = 4;
           $this->db->update('m_p_special',$formdata,array('id'=>$id)); 
          }

          MSG(L('删除成功！！'),HTTP_REFERER);
    }//批量删除
    public function edit(){
    	$db=load_class('db');
        $special=$db->get_one('m_p_special','status!=4 and id="'.$GLOBALS['id'].'"','*',0,10,$page,'updatetime desc','');

        if ($GLOBALS['submit']=='保存') {
           $urls=implode("|",$GLOBALS['urls'] );
          if(!empty($GLOBALS['url'])){
                   $cae=implode("|",$GLOBALS['url'] );
            }
             if(!empty($GLOBALS['form']['alt'])){
                   $alt1=implode("|",$GLOBALS['form']['alt'] );
            }
                   $a=$GLOBALS['form']['case'];
               if(!empty($a)){
                  $arr=array();
                  $arr1 = array();
               foreach($a as $ar){
                   $alt[]=$ar['alt'];
                  $arr1[]= basename($ar['url']);
               }
                  $case=implode("|",$arr1);
                  if($cae==""){
                   $cae.=$case;
                    }else{
                  $cae.='|'.$case;
                    }
                  $alt=implode("|",$alt);
                   if($alt1==""){
                   $alt1.=$alt;
                    }else{
                   $alt1.='|'.$alt;
                    }
            }    
        	 $formdata = array();
                $formdata['title'] =$GLOBALS['title'];
                $formdata['dao']=$GLOBALS['form']['dao'];          
                $formdata['cover']=basename($GLOBALS['attachment_test']);
                $formdata['dao']=$GLOBALS['form']['dao'];
                $formdata['alt']=$alt1;
                $formdata['atlas']=$cae;
                $formdata['url']=$urls;
                $formdata['updatetime']=date('Y-m-d H:i:s');
              $db->update('m_p_special',$formdata,array('id'=>$GLOBALS['id'])); 
               MSG(L('编辑成功！！'),'?m=Mjin&f=special&v=listing&_su=wuzhicms');
        }
    	include $this->template('special_edit');
    }
    public function add(){
    	$db=load_class('db');
        if ($GLOBALS['submit']=='保存') {
      
         $urls=implode("|",$GLOBALS['urls'] );
                if($GLOBALS['form']['case']<>''){
              $a=$GLOBALS['form']['case'];
              $arr=array();
              $arr1 = array();
               foreach($a as $ar){
                   $alt[]=$ar['alt'];
                   $arr1[]= basename($ar['url']);
               } 
              $atlas=implode("|",$arr1);
              $dercontent=implode("|",$alt); 
                }
        	 $formdata = array();
                $formdata['title'] =$GLOBALS['title'];
                $formdata['cover']=basename($GLOBALS['attachment_test']);
                $formdata['alt']=$dercontent;
                $formdata['atlas']=$atlas;
                $formdata['dao']=$GLOBALS['content'];
                $formdata['url']=$urls;
                $formdata['status']=3;
                $formdata['intervene']=0;
                $formdata['updatetime']=date('Y-m-d H:i:s');
                 $db->insert('m_p_special',$formdata);
                 MSG(L('添加成功！！'),'?m=Mjin&f=special&v=listing&_su=wuzhicms');
        }
        if ($GLOBALS['submit']=='发布') {
           if($GLOBALS['form']['case']<>''){
            $a=$GLOBALS['form']['case'];
              $arr=array();
              $arr1 = array();
               foreach($a as $ar){
                   $alt[]=$ar['alt'];
                   $arr1[]= basename($ar['url']);
               } 
              $atlas=implode("|",$arr1);
              $dercontent=implode("|",$alt); 
                }
                   $urls=implode("|",$GLOBALS['urls'] );
             $formdata = array();
                $formdata['title'] =$GLOBALS['title'];
                $formdata['cover']=basename($GLOBALS['attachment_test']);
                $formdata['alt']=$dercontent;
                $formdata['dao']=$GLOBALS['form']['dao'];
                $formdata['atlas']=$atlas;
                $formdata['url']=$urls;
                $formdata['status']=1;
                $formdata['intervene']=0;
                $formdata['updatetime']=date('Y-m-d H:i:s');
            $db->insert('m_p_special',$formdata);
                 MSG(L('发布成功！！'),'?m=Mjin&f=special&v=listing&_su=wuzhicms');
        }
       
    	include $this->template('special_add');
    }
    //    public function edit() {
    //     if(!isset($GLOBALS['id'])) MSG(L('parameter_error'));
    //     $id = intval($GLOBALS['id']);
    //     $category = $this->db->get_one('category',"name='案例专题管理'",'cid');
    //     $cid=$category['cid'];
        
    //     $cate_config = get_cache('category_'.$cid,'content');
    //     if(!$cate_config) MSG(L('category not exists'));
    //     //如果设置了modelid，那么则按照设置的modelid。共享模型添加必须数据必须指定该值。
    //     if(isset($GLOBALS['modelid']) && is_numeric($GLOBALS['modelid'])) {
    //         $modelid = $GLOBALS['modelid'];
    //     } else {
    //         $modelid = $cate_config['modelid'];
    //     }

    //     if(isset($GLOBALS['submit']) || isset($GLOBALS['submit2'])) {
           
    //         $formdata = $GLOBALS['form'];
    //   //插入时间，更新时间，如果用户设置了时间。则按照用户设置的时间
    //   date('Y-m-d H:i:s')
    //         //添加数据之前，将用户提交的数据按照字段的配置，进行处理
    //         require get_cache_path('content_add','model');
    //         $form_add = new form_add($modelid);
    //         $formdata = $form_add->execute($formdata);
    //         $formdata['master_data']['addtime'] = $addtime;
    //         $formdata['master_data']['updatetime'] = SYS_TIME;
    //         //如果是共享模型，那么需要在将字段modelid增加到数据库
    //         if($formdata['master_table']=='content_share') {
    //             $formdata['master_data']['modelid'] = $modelid;
    //         }
    //         $formdata['master_data']['cid'] = $cid;
    //         //默认状态 status ,9为通过审核，1-4为审核的工作流，0为回收站
          
    //         //非超级管理员，验证该栏目是否设置了审核
    //         if($cate_config['workflowid'] && $_SESSION['role']!=1 && in_array($formdata['master_data']['status'],array(9,8))) {
    //             $formdata['master_data']['status'] = 1;
    //         }
    //         //如果 route为 0 默认，1，加密，2外链 ，3，自定义 例如：wuzhicms-diy-url-example 用户，不能不需要自己写后缀。程序自动补全。
    //         $formdata['master_data']['route'] = intval($GLOBALS['form']['route']);
    //         //标题样式
    //         $title_css = preg_match('/([a-z0-9]+)/i',$GLOBALS['title_css']) ? $GLOBALS['title_css'] : '';
    //         $formdata['master_data']['css'] = $title_css;

    //         if($cate_config['type']) {
    //             $urls['url'] = $cate_config['url'];
    //         } elseif($formdata['master_data']['route']>1) {//外部链接/或者自定义链接
    //             $urls['url'] = remove_xss($GLOBALS['url']);
    //         } else {
    //             //生成url
    //             $urlclass = load_class('url','content',$cate_config);
    //             $productid = 0;
    //             if(isset($formdata['master_data']['productid'])) $productid = $formdata['master_data']['productid'];
    //             $urls = $urlclass->showurl(array('id'=>$id,'cid'=>$cid,'addtime'=>$addtime,'page'=>1,'route'=>$formdata['master_data']['route'],'productid'=>$productid));
    //         }
    //         $formdata['master_data']['url'] = $urls['url'];

    //         if(empty($formdata['master_data']['remark']) && isset($formdata['attr_data']['content'])) {
    //             $formdata['master_data']['remark'] = mb_strcut(strip_tags($formdata['attr_data']['content']),0,255);
    //         }

    //         $this->db->update($formdata['master_table'],$formdata['master_data'],array('id'=>$id));
    //         if(!empty($formdata['attr_table'])) {
    //             $this->db->update($formdata['attr_table'],$formdata['attr_data'],array('id'=>$id));
    //         }

    //         //执行更新
    //         require get_cache_path('content_update','model');
    //         $form_update = new form_update($modelid);

    //         $formdata['master_data']['id'] = $id;
    //         $form_update->execute($formdata);
    //         //生成静态
    //         if($cate_config['showhtml'] && $formdata['master_data']['status']==9) {
    //             $data = $this->db->get_one($formdata['master_table'],array('id'=>$id));
    //             if(!empty($formdata['attr_table'])) {
    //                 $attrdata = $this->db->get_one($formdata['attr_table'],array('id'=>$id));
    //                 $data = array_merge($data,$attrdata);
    //             }
    //             //上一页
    //             $data['previous_page'] = $this->db->get_one($formdata['master_table'],"`cid` = '$cid' AND `id`<'$id' AND `status`=9",'*',0,'id DESC');
    //             //下一页
    //             $data['next_page'] = $this->db->get_one($formdata['master_table'],"`cid`= '$cid' AND `id`>'$id' AND `status`=9",'*',0,'id ASC');
    //             $this->html = load_class('html','content');
    //             $this->html->set_category($cate_config);
    //             $this->html->set_categorys();
    //             $this->html->load_formatcache();
    //             $this->html->show($data,1,1,$urls['root']);
    //             $loadhtml = true;
    //         } else {
    //             $loadhtml = false;
    //         }
    //         //生成相关栏目列表
    //         if($cate_config['listhtml']) {
    //             if($loadhtml==false) {
    //                 $this->html = load_class('html','content');
    //                 $this->html->set_category($cate_config);
    //                 $this->html->set_categorys();
    //                 $loadhtml = true;
    //             }
    //             for($i=1;$i<6;$i++) {
    //                 $cateurls = $urlclass->listurl(array('cid'=>$cid,'page'=>$i));
    //                 $this->html->listing($cateurls['root'],$i);
    //                 if($GLOBALS['result_lists']==0) {
    //                     break;
    //                 }
    //             }
    //         }
    //         //生成首页
    //         if($loadhtml) {
    //             $this->html->index();
    //         } else {
    //             $this->html = load_class('html','content');
    //             $this->html->set_categorys();
    //             $this->html->index();
    //         }
    //         //编辑操作日志
    //         $this->editor_logs('edit',$formdata['master_data']['title'],$urls['url'], "?m=content&f=content&v=edit&id=$id&cid=$cid");
    //         //设置返回地址
    //         $forward = isset($GLOBALS['submit2']) ? HTTP_REFERER : '?m=content&f=content&v=listing&type='.$GLOBALS['type'].'&cid='.$cid.$this->su();
    //        MSG(L('编辑成功！！'),'?m=Mjin&f=special&v=listing&_su=wuzhicms');
    //     } else {
    //         $models = get_cache('model_content','model');
    //         $model_r = $models[$modelid];
    //         $master_table = $model_r['master_table'];
    //         $data = $this->db->get_one($master_table,array('id'=>$id));
    //         if($model_r['attr_table']) {
    //             $attr_table = $model_r['attr_table'];
    //             if($data['modelid']) {
    //                 $modelid = $data['modelid'];
    //                 $attr_table = $models[$modelid]['attr_table'];
    //             }
    //             $attrdata = $this->db->get_one($attr_table,array('id'=>$id));
    //             $data = array_merge($data,$attrdata);
    //         }

    //         load_function('template');
    //         load_function('content','content');
    //         $status = isset($GLOBALS['status']) ? intval($GLOBALS['status']) : 9;
    //         require get_cache_path('content_form','model');
    //         $form_build = new form_build($modelid);
    //         $form_build->cid = $cid;
    //         $category = get_cache('category','content');
    //         $form_build->extdata['catname'] = $cate_config['name'];
    //         $form_build->extdata['type'] = $cate_config['type'];
    //         $formdata = $form_build->execute($data);
    //         load_class('form');
    //         $show_formjs = 1;
    //         $show_dialog = 1;

    //         include $this->template('special_edits');
    //     }
    // }
    // public function add() {
    //     $category = $this->db->get_one('category',"name='案例专题管理'",'cid');
    //     $cid=$category['cid'];
    //     $cate_config = get_cache('category_'.$cid,'content');
    //     if(!$cate_config) MSG(L('category not exists'));
    //     //如果设置了modelid，那么则按照设置的modelid。共享模型添加必须数据必须指定该值。
    //     if(isset($GLOBALS['modelid']) && is_numeric($GLOBALS['modelid'])) {
    //         $modelid = $GLOBALS['modelid'];
    //     } else {
    //         $modelid = $cate_config['modelid'];
    //     }

    //     if(isset($GLOBALS['submit']) || isset($GLOBALS['submit2'])) {
    //         $formdata = $GLOBALS['form'];
    //   //插入时间，更新时间，如果用户设置了时间。则按照用户设置的时间
    //   date('Y-m-d H:i:s')
    //         //添加数据之前，将用户提交的数据按照字段的配置，进行处理
    //         require get_cache_path('content_add','model');
    //         $form_add = new form_add($modelid);
    //         $formdata = $form_add->execute($formdata);
    //         $formdata['master_data']['addtime'] = $formdata['master_data']['updatetime'] = $addtime;
    //         //如果是共享模型，那么需要在将字段modelid增加到数据库
    //         if($formdata['master_table']=='content_share') {
    //             $formdata['master_data']['modelid'] = $modelid;
    //         }
    //         $formdata['master_data']['cid'] = $cid;
    //         //默认状态 status ,9为通过审核，1-4为审核的工作流，0为回收站
    //          if($GLOBALS['submit']=="保存"){
   
    //               $formdata['master_data']['status'] = isset($GLOBALS['form']['status']) ? intval($GLOBALS['form']['status']) : 3;
    //             }
    //               if($GLOBALS['submit']=="发布"){
    //                 $formdata['master_data']['status'] = isset($GLOBALS['form']['status']) ? intval($GLOBALS['form']['status']) : 1;
    //             }
          
    //         //非超级管理员，验证该栏目是否设置了审核
    //         if($cate_config['workflowid'] && $_SESSION['role']!=1 && in_array($formdata['master_data']['status'],array(9,8))) {
    //             $formdata['master_data']['status'] = 1;
    //         }

    //         //如果 route为 0 默认，1，加密，2外链 ，3，自定义 例如：wuzhicms-diy-url-example 用户，不能不需要自己写后缀。程序自动补全。
    //         $formdata['master_data']['route'] = intval($GLOBALS['form']['route']);
    //         $formdata['master_data']['publisher'] = get_cookie('username');
    //         //标题样式
    //         $title_css = preg_match('/([a-z0-9]+)/i',$GLOBALS['title_css']) ? $GLOBALS['title_css'] : '';
    //         $formdata['master_data']['css'] = $title_css;
    //         //echo $formdata['master_table'];exit;
    //         if(empty($formdata['master_data']['remark']) && isset($formdata['attr_data']['content'])) {
    //             $formdata['master_data']['remark'] = mb_strcut(strip_tags($formdata['attr_data']['content']),0,255);
    //         }
    //         $id = $this->db->insert($formdata['master_table'],$formdata['master_data']);
    //         if($cate_config['type']) {
    //             $urls['url'] = $cate_config['url'];
    //         } elseif($formdata['master_data']['route']>1) {//外部链接
    //             $urls['url'] = remove_xss($GLOBALS['url']);
    //         } else {
    //             //生成url
    //             $urlclass = load_class('url','content',$cate_config);
    //             $urls = $urlclass->showurl(array('id'=>$id,'cid'=>$cid,'addtime'=>$addtime,'page'=>1,'route'=>$formdata['master_data']['route']));
    //         }
    //         $this->db->update($formdata['master_table'],array('url'=>$urls['url']),array('id'=>$id));
    //         if(!empty($formdata['attr_table'])) {
    //             $formdata['attr_data']['id'] = $id;
    //             // print_r($formdata['attr_data']);exit;
    //             $this->db->insert($formdata['attr_table'],$formdata['attr_data']);
    //         }
    //         $formdata['master_data']['url'] = $urls['url'];
    //         //执行更新
    //         require get_cache_path('content_update','model');
    //         $form_update = new form_update($modelid);
    //         $data = $form_update->execute($formdata);

    //         //判断是否存在，防止意外发生
    //         if(!$this->db->get_one('content_rank',array('cid'=>$cid,'id'=>$id))) {
    //             //统计表加默认数据
    //             $this->db->insert('content_rank',array('cid'=>$cid,'id'=>$id,'updatetime'=>SYS_TIME));
    //         }
    //         //生成静态
    //         if($cate_config['showhtml'] && $formdata['master_data']['status']==9) {
    //             $data = $this->db->get_one($formdata['master_table'],array('id'=>$id));
    //             if(!empty($formdata['attr_table'])) {
    //                 $attrdata = $this->db->get_one($formdata['attr_table'],array('id'=>$id));
    //                 $data = array_merge($data,$attrdata);
    //             }
    //             //上一页
    //             $data['previous_page'] = $this->db->get_one($formdata['master_table'],"`cid` = '$cid' AND `id`<'$id' AND `status`=9",'*',0,'id DESC');
    //             //下一页
    //             $data['next_page'] = '';
    //             $this->html = load_class('html','content');
    //             $this->html->set_category($cate_config);
    //             $this->html->set_categorys();
    //             $this->html->load_formatcache();
    //             $this->html->show($data,1,1,$urls['root']);
    //             $loadhtml = true;
    //         } else {
    //             $loadhtml = false;
    //         }
    //         //生成相关栏目列表
    //         if($cate_config['listhtml']) {
    //             if($loadhtml==false) {
    //                 $this->html = load_class('html','content');
    //                 $this->html->set_category($cate_config);
    //                 $this->html->set_categorys();
    //                 $loadhtml = true;
    //             }
    //             for($i=1;$i<6;$i++) {
    //                 $cateurls = $urlclass->listurl(array('cid'=>$cid,'page'=>$i));
    //                 $this->html->listing($cateurls['root'],$i);
    //                 if($GLOBALS['result_lists']==0) {
    //                     break;
    //                 }
    //             }
    //         }
    //         //生成首页
    //         if($loadhtml) {
    //             $this->html->index();
    //         } else {
    //             $this->html = load_class('html','content');
    //             $this->html->set_categorys();
    //             $this->html->index();
    //         }
    //         //添加到最新列表中
    //         $lastlist = get_cache('lastlist','content');
    //         $newcontent = array(0=>array('cid'=>$cid,'title'=>$formdata['master_data']['title'],'url'=>$urls['url'],'addtime'=>SYS_TIME));
    //         if(is_array($lastlist)) {
    //             $lastlist = array_merge($newcontent,$lastlist);
    //             if(count($lastlist)>100) array_pop($lastlist);
    //         } else {
    //             $lastlist = $newcontent;
    //         }

    //         set_cache('lastlist',$lastlist,'content');
    //         //编辑操作日志
    //         $this->editor_logs('add',$formdata['master_data']['title'],$urls['url'], "?m=content&f=content&v=edit&id=$id&cid=$cid");
    //         //设置返回地址
    //        if(isset($GLOBALS['submit'])) {
    //             if($GLOBALS['submit']=="保存"){
   
    //                MSG(L('添加成功！！'),'?m=Mjin&f=special&v=listing&_su=wuzhicms');
    //             }
    //               if($GLOBALS['submit']=="发布"){
    //                 MSG(L('发布成功！！'),'?m=Mjin&f=special&v=listing&_su=wuzhicms');
    //             }
    //         } else {
    //             MSG(L('add success'),URL(),1000);
    //         }
    //     } else {
    //         load_function('template');
    //         load_function('content','content');
    //         $status = isset($GLOBALS['status']) ? intval($GLOBALS['status']) : 9;
    //         require get_cache_path('content_form','model');
    //         $form_build = new form_build($modelid);
    //         $form_build->cid = $cid;
    //         $category = get_cache('category','content');
    //         $form_build->extdata['catname'] = $cate_config['name'];
    //         $form_build->extdata['type'] = $cate_config['type'];
    //         $formdata = $form_build->execute();
    //         load_class('form');
    //         $show_formjs = 1;
    //         $show_dialog = 1;
    //         include $this->template('special_adds');
    //     }
    // }
    public function intervene(){
    	$db=load_class('db');
       $result = $db->get_one('m_p_special',"id='".$GLOBALS['id']."'");
       include $this->template('special_intervene');
    }
    public function intervenes(){
       $db = load_class('db');
       $formdata = array();
       $formdata['intervene'] =$GLOBALS['intervene'] ;
       $formdata['updatetime'] =date('Y-m-d H:i:s');
       $db->update('m_p_special',$formdata,array('id'=>$GLOBALS['id']));
       MSG('<script>setTimeout("top.dialog.get(window).close().remove();",700),parent.iframeid.location.reload();</script>申请已提交');
    }
    public function Previews(){
           $db = load_class('db');
               if($GLOBALS['form']['case']<>''){
                       $a=$GLOBALS['form']['case'];
              $arr=array();
              $arr1 = array();
               foreach($a as $ar){
                   $alt[]=$ar['alt'];
                   $arr1[]= basename($ar['url']);
               } 
              $atlas=implode("|",$arr1);
              $dercontent=implode("|",$alt); 
                }
             $formdata = array();
                $formdata['title'] =$GLOBALS['title'];
                $formdata['cover']=basename($GLOBALS['attachment_test']);
                $formdata['alt']=$dercontent;
                $formdata['atlas']=$atlas;
                $formdata['url']=$atlas;
                $formdata['dao']=$GLOBALS['form']['dao'];
                $formdata['status']=3;
                $formdata['intervene']=0;
                $formdata['updatetime']=date('Y-m-d H:i:s');
                $db->insert('m_p_yspecial',$formdata);
                $date=date('Y-m-d H:i:s');
                 $db->delete('m_p_yspecial',"updatetime!='".$date."'");
              $result = $db->get_one('m_p_yspecial',"updatetime='".$date."'", '*', 0, 10,$page,'sort DESC');
              include $this->template('specisl_Previews');
    }
    public function checkNameExit(){
        $db = load_class('db');
        $name = $GLOBALS['nane'];
        $res = $db->get_one('m_p_special',"nane='".$name."'and status!=4",'id');
        die(json_encode($res['id']));
   }
   public function Preview(){
    $db = load_class('db');
    $result = $db->get_one('m_p_special',"id='".$GLOBALS['id']."'");
    include $this->template('specisl_Preview');
   }
}
