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
require_once(COREFRAME_ROOT.'app/core/libs/class/phpqrcode.php');
/**
 * 内容添加
 */
load_class('admin');
define('HTML',true);
class content extends WUZHI_admin {
  public function listing(){
        $page = isset($GLOBALS['page']) ? intval($GLOBALS['page']) : 1;
        $db = load_class('db');
        $pagess=$page ;
        $keytypes = isset($GLOBALS['title']) ? $GLOBALS['title'] : '';
        $page = max($page,1);
        if($keytypes==NUll){
          $result = $db->get_list('m_exercise'," status !=3 and status !=5 ",'*',0,10,$page,'updatetime desc');
        }else{
          $result = $db->get_list('m_exercise',"title like '%".$keytypes."%' and status != 3 and status !=5",'*',0,10,$page,'updatetime desc');
        }
        $dir='uploadfile/wei';
        if(!is_dir($dir)){
           mkdir($dir);
        }
        foreach($result as $wei){
           $weid=$wei['id'];
              $str=R;$newstr = substr($str,0,strlen($str)-5);
              $urls=$newstr.'/mobile-template.html?temp='.$wei['id'];
              $data = $urls; 
              $filename ='uploadfile/wei/'.$wei['id'].'.png';
              $errorCorrectionLevel = 'L';  
              $matrixPointSize = 6;  
              QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2); 
        }
        // foreach ($result as $key => $value) {
        //  $url='res/wei/'.$value['id'].".png";
        //  echo '<a href="?m=xin&f=content&v=doDownload&filename='.$url.'&_su=wuzhicms&_menuid=5319"><img class="xiaotu" src="'.$url.'" style="width:30px;"></a>';
        // }
        $pages = $db->pages;
        $total = $db->number;
     include $this->template('content_listing');
  }
  public function adds(){
  	 include $this->template('content_adds');
  }
  public function doDownload(){
    $filename=$GLOBALS['filename'];
    header('content-disposition:attachment;filename='.basename($filename));
    header('content-length:'.filesize($filename));
    readfile($filename);
  }//文件下载
  public function add(){
         $db = load_class('db');
         $a=$GLOBALS['form']['case'];
         $b=$GLOBALS['form']['casex'];
          if(!empty($a)){
              $arr1 = array();
              foreach($a as $ar){
                $arr1[]= basename($ar['url']);
              }
                $case=implode("|",$arr1);
          } 
          if(!empty($b)){
          $arr = array();
             foreach($b as $ar){
               $arr[]= basename($ar['url']);
             }
              $troops=implode("|",$arr);
          } 
          $biann= $GLOBALS['bian'];
          if(!empty($biann)){
            $bian=implode("|",$GLOBALS['bian']);
          }
          $bianss =$GLOBALS['bians'];
          if(!empty($bianss)){
            $bians=implode("|",$GLOBALS['bians']);
          }
           if(!empty($GLOBALS['urlsx'])){
               $urlsx=implode("|",$GLOBALS['urlsx']);
           }
           if(!empty($GLOBALS['urlsh'])){
               $urlsh=implode("|",$GLOBALS['urlsh']);
           }   
           if(!empty($GLOBALS['urlH'])){
               $urlh=implode("|",$GLOBALS['urlH']);
           } 
            
     	    $formdata = array();
              $formdata['name'] =$GLOBALS['name'];
              $formdata['title']=$GLOBALS['exercise'];   
              $formdata['type']=$GLOBALS['type'];   
              $formdata['background']=basename($GLOBALS['attachment_test']);
              $formdata['share']=basename($GLOBALS['share']);
              $formdata['city']=$GLOBALS['city'];
              $formdata['status']=1;
              $formdata['status_1']=$GLOBALS['status_1'];
              $formdata['status_2']=$GLOBALS['status_2'];
              $formdata['status_3']=$GLOBALS['status_3'];
              $formdata['status_4']=$GLOBALS['status_4'];
              $formdata['color']=$GLOBALS['color'];
              if($GLOBALS['type']==1){
                $formdata['troops']=$troops;
                $formdata['headpiece']=$case;
                $formdata['color1']=$GLOBALS['color1'];
                $formdata['color2']=$GLOBALS['color2'];
                $formdata['color3']=$GLOBALS['color3'];
                $formdata['headpieceurl']=$urlsx;
                $formdata['troopsurl']=$urlsh;
                $formdata['bian']=$bian;
                $formdata['bians']=$bians;
              }else if($GLOBALS['type']==2){
                 $formdata['headpiece']=$case;
                 $formdata['bian']=$bian;
                 $formdata['headpieceurl']=$urlsx;
              }
              $formdata['updatetime']=date('Y-m-d H:i:s');
              $formdata['addtime']=date('Y-m-d H:i:s');
              $formdata['button']=$GLOBALS['button'];
              $formdata['person']=$GLOBALS['person'];
              $formdata['sharename']=$GLOBALS['sharename'];
              $formdata['sharedescribe']=$GLOBALS['sharedescribe'];
              $db->insert('m_exercise',$formdata); 
            MSG(L('添加成功！！'),'?m=xin&f=content&v=listing&_su=wuzhicms');
        }
  public function edit(){
      	$db=load_class('db');
        $content=$db->get_one('m_exercise','status!=3 and status !=5 and id="'.$GLOBALS['id'].'"','*',0,10,$page,'updatetime desc','');
        if($GLOBALS['submit']=="发布"){
		    if(!empty($GLOBALS['url'])){
                   $cae=implode("|",$GLOBALS['url'] );
            }
             if(!empty($GLOBALS['urlt'])){
                   $caet=implode("|",$GLOBALS['urlt'] );
            }
             $a=$GLOBALS['form']['case'];
               if(!empty($a)){
                  $arr1 = array();
               foreach($a as $ar){
                  $arr1[]= basename($ar['url']);
               }
                   $case=implode("|",$arr1);
                   $cae.='|'.$case;
            } 
              $ax=$GLOBALS['form']['casex'];
               if(!empty($ax)){
                  $arrx = array();
               foreach($ax as $arx){
                  $arrx[]= basename($arx['url']);
               }
                   $casex=implode("|",$arrx);
                   $caet.='|'.$casex;
            }
           $biann= $GLOBALS['bian'];
          if(!empty($biann)){
            $bian=implode("|",$GLOBALS['bian']);
          }
         $bianss =$GLOBALS['bians'];
          if(!empty($bianss)){
            $bians=implode("|",$GLOBALS['bians']);
          }   
          if(!empty($GLOBALS['urlsx'])){
            $urlsx=implode("|",$GLOBALS['urlsx']);
          }
          if(!empty($GLOBALS['urlsh'])){
            $urlsh=implode("|",$GLOBALS['urlsh']);
          }
     	    $formdata = array();
              $formdata['name'] =$GLOBALS['name'];
              $formdata['title']=$GLOBALS['exercise'];          
              $formdata['headpiece']=$cae;
              $formdata['background']=basename($GLOBALS['attachment_test']);
              $formdata['share']=basename($GLOBALS['share']);
              $formdata['troops']=$caet;
              $formdata['city']=$GLOBALS['city'];
              $formdata['status']=1;
              $formdata['status_1']=$GLOBALS['status_1'];
              $formdata['status_2']=$GLOBALS['status_2'];
              $formdata['status_3']=$GLOBALS['status_3'];
              $formdata['status_4']=$GLOBALS['status_4'];
              $formdata['color']=$GLOBALS['color'];
              $formdata['color1']=$GLOBALS['color1'];
              $formdata['color2']=$GLOBALS['color2'];
              $formdata['color3']=$GLOBALS['color3'];
              $formdata['bian']=$bian;
              $formdata['bians']=$bians;
              $formdata['headpieceurl']=$urlsx;
              $formdata['troopsurl']=$urlsh;
              $formdata['updatetime']=date('Y-m-d H:i:s');
              $formdata['addtime']=date('Y-m-d H:i:s');
              $formdata['button']=$GLOBALS['button'];
              $formdata['person']=$GLOBALS['person'];
              $formdata['sharename']=$GLOBALS['sharename'];
              $formdata['sharedescribe']=$GLOBALS['sharedescribe'];
              $db->update('m_exercise',$formdata,array('id'=>$GLOBALS['id'])); 
            MSG(L('编辑成功！！'),'?m=xin&f=content&v=listing&_su=wuzhicms');
        }
        include $this->template('content_edit');
  }
  public function delete(){
         $db = load_class('db');
         $Id = isset($GLOBALS['id']) ? intval($GLOBALS['id']) : 1;
         $formdata = array();
             $formdata['status'] = 3;
         $db->update('m_exercise',$formdata,array('id'=>$Id)); 
         MSG(L('删除成功！！'),HTTP_REFERER);
  }
  public function Putaway(){
      	 $db = load_class('db');
         $Id = isset($GLOBALS['id']) ? intval($GLOBALS['id']) : 1;
         $formdata = array();
             $formdata['status'] = 1;
             $formdata['updatetime'] =date('Y-m-d H:i:s') ;
         $db->update('m_exercise',$formdata,array('id'=>$Id)); 
         MSG(L('上线成功！！'),HTTP_REFERER);
  }//上线
  public function UnShelve(){
    	$db = load_class('db');
         $Id = isset($GLOBALS['id']) ? intval($GLOBALS['id']) : 1;
         $formdata = array();
             $formdata['status'] = 2;
             $formdata['updatetime'] =date('Y-m-d H:i:s') ;
         $db->update('m_exercise',$formdata,array('id'=>$Id)); 
         MSG(L('下线成功！！'),HTTP_REFERER);
  }
  public function Deletes(){
     	$db = load_class('db');
	    foreach($GLOBALS['ids'] as $id){
	         $formdata = array();
	         $formdata['status'] = 3;
	     $db->update('m_exercise',$formdata,array('id'=>$id)); 
	    }
       MSG(L('删除成功！！'),HTTP_REFERER);
  }
  public function Previews(){
  	$db=load_class('db');
    $Previews=$db->get_one('m_exercise','status!=3 and status !=5 and id="'.$GLOBALS['id'].'"','*',0,10,$page,'updatetime desc','');
      $where ='pid in ("'.implode('","',array('2','3','4','5','34','35','0')).'") and pid !=2 and pid !=3 and pid !=4 and pid !=5 and pid !=34 and pid !=35';
     $linkage=$db->get_list('linkage_data',$where,"lid,name");
       $info=array( 
        array( 
        name=>'北京',
        lib=>'3360',
       ) ,
        array( 
        name=>'天津',
        lib=>'3362',
       ) ,
        array( 
        name=>'广州',
        lib=>'326',
       ),
        array( 
        name=>'深圳',
        lib=>'328',
       )
     );
  	include $this->template('index');
  }
  public function public_threeLevel(){
    $db=load_class('db');
    $pid = isset($GLOBALS['pid'])?intval($GLOBALS['pid']):0;
    $result = $db->get_list('linkage_data', array('pid'=>$pid), 'lid,name');
    die(json_encode($result));
  }
  public function Preview(){
      $db = load_class('db');
            $info=array( 
              array( 
              name=>'北京',
              lib=>'3360',
             ) ,
              array( 
              name=>'天津',
              lib=>'3362',
             ) ,
              array( 
              name=>'广州',
              lib=>'326',
             ),
              array( 
              name=>'深圳',
              lib=>'328',
             )
           );
            $biann= $GLOBALS['bian'];
              if(!empty($biann)){
                $bian=implode("|",$GLOBALS['bian']);
              }
            $bianss =$GLOBALS['bians'];
              if(!empty($bianss)){
                $bians=implode("|",$GLOBALS['bians']);
              }   
            $a=$GLOBALS['form']['case'];
              if(!empty($a)){
                 $arr1 = array();
               foreach($a as $ar){
                $arr1[]= basename($ar['url']);
                }
                $case=implode("|",$arr1);
                } 
            $ax=$GLOBALS['form']['casex'];
               if(!empty($ax)){
                 $arrx = array();
                foreach($ax as $arx){
                 $arrx[]= basename($arx['url']);
                   }
                 $casex=implode("|",$arrx);
                }
          if(!empty($GLOBALS['urlsx'])){
            $urlsx=implode("|",$GLOBALS['urlsx']);
          }
          if(!empty($GLOBALS['urlsh'])){
            $urlsh=implode("|",$GLOBALS['urlsh']);
          }
        $linkage=$db->get_list('linkage_data',$where,"lid,name");
        $formdata = array();
          $formdata['name'] =$GLOBALS['name'];
          $formdata['title']=$GLOBALS['exercise'];          
          $formdata['headpiece']=$case;
          $formdata['background']=basename($GLOBALS['attachment_test']);
          $formdata['troops']=$casex;
          $formdata['city']=$GLOBALS['city'];
          $formdata['status']=5;
          $formdata['status_1']=$GLOBALS['status_1'];
          $formdata['status_2']=$GLOBALS['status_2'];
          $formdata['status_3']=$GLOBALS['status_3'];
          $formdata['status_4']=$GLOBALS['status_4'];
          $formdata['color']=$GLOBALS['color'];
          $formdata['color1']=$GLOBALS['color1'];
          $formdata['color2']=$GLOBALS['color2'];
          $formdata['color3']=$GLOBALS['color3'];
          $formdata['bian']=$bian;
          $formdata['bians']=$bians;
          $formdata['headpieceurl']=$urlsx;
          $formdata['troopsurl']=$urlsh;
          $formdata['updatetime']=date('Y-m-d H:i:s');
          $formdata['addtime']=date('Y-m-d H:i:s');
          $formdata['button']=$GLOBALS['button'];
          // $formdata['url']=ltrim($url,'/');
          $db->insert('m_exercise',$formdata); 
          $date=date('Y-m-d H:i:s');
          $Previews=$db->get_one('m_exercise','addtime="'.$date.'"','*',0,10,$page,'updatetime desc','');
  
          $db->delete('m_exercise',"status=5 and addtime!='".$date."'");
        include $this->template('index');
     }
      public function Previewe(){
            $db = load_class('db');
            $info=array( 
              array( 
              name=>'北京',
              lib=>'3360',
             ) ,
              array( 
              name=>'天津',
              lib=>'3362',
             ) ,
              array( 
              name=>'广州',
              lib=>'326',
             ),
              array( 
              name=>'深圳',
              lib=>'328',
             )
           );
            if(!empty($GLOBALS['url'])){
                   $cae=implode("|",$GLOBALS['url'] );
            }
             if(!empty($GLOBALS['urlt'])){
                   $caet=implode("|",$GLOBALS['urlt'] );
            }
             $a=$GLOBALS['form']['case'];
               if(!empty($a)){
                  $arr1 = array();
               foreach($a as $ar){
                  $arr1[]= basename($ar['url']);
               }
                   $case=implode("|",$arr1);
                   $cae.='|'.$case;
            } else{
            $cae=implode("|",$GLOBALS['url'] );
            }
              $ax=$GLOBALS['form']['casex'];
               if(!empty($ax)){
                  $arrx = array();
               foreach($ax as $arx){
                  $arrx[]= basename($arx['url']);
               }
                   $casex=implode("|",$arrx);
                   $caet.='|'.$casex;
            }else{
             $caet=implode("|",$GLOBALS['urlt'] );
            }
           
           $biann= $GLOBALS['bian'];
          if(!empty($biann)){
            $bian=implode("|",$GLOBALS['bian']);
          }
         $bianss =$GLOBALS['bians'];
          if(!empty($bianss)){
            $bians=implode("|",$GLOBALS['bians']);
          }   
          if(!empty($GLOBALS['urlsx'])){
            $urlsx=implode("|",$GLOBALS['urlsx']);
          }
          if(!empty($GLOBALS['urlsh'])){
            $urlsh=implode("|",$GLOBALS['urlsh']);
          }
       $linkage=$db->get_list('linkage_data',$where,"lid,name");
        $formdata = array();
        $formdata['name'] =$GLOBALS['name'];
        $formdata['title']=$GLOBALS['exercise'];          
        $formdata['headpiece']=$cae;
        $formdata['background']=basename($GLOBALS['attachment_test']);
        $formdata['troops']=$caet;
        $formdata['city']=$GLOBALS['city'];
        $formdata['status']=5;
        $formdata['status_1']=$GLOBALS['status_1'];
        $formdata['status_2']=$GLOBALS['status_2'];
        $formdata['status_3']=$GLOBALS['status_3'];
        $formdata['status_4']=$GLOBALS['status_4'];
        $formdata['color']=$GLOBALS['color'];
        $formdata['color1']=$GLOBALS['color1'];
        $formdata['color2']=$GLOBALS['color2'];
        $formdata['color3']=$GLOBALS['color3'];
        $formdata['bian']=$bian;
        $formdata['bians']=$bians;
        $formdata['headpieceurl']=$urlsx;
        $formdata['troopsurl']=$urlsh;
        $formdata['updatetime']=date('Y-m-d H:i:s');
        $formdata['addtime']=date('Y-m-d H:i:s');
        $formdata['button']=$GLOBALS['button'];
        $db->insert('m_exercise',$formdata); 
        $date=date('Y-m-d H:i:s');
        $Previews=$db->get_one('m_exercise','addtime="'.$date.'"','*',0,10,$page,'updatetime desc','');
        $db->delete('m_exercise',"status=5 and addtime!='".$date."'");
        include $this->template('index');
     }
      public function checkNameExit(){
        $db = load_class('db');
        $name = $GLOBALS['title'];
        $res = $db->get_one('m_exercise',"title='".$name."'and status in(1,2)" ,'id');
        die(json_encode($res['id']));
   }
 public function checkNameExits(){
        $db = load_class('db');
        $name = $GLOBALS['title'];
        $id = $GLOBALS['id'];
        $res = $db->get_one('m_exercise',"title='".$name."'and status in(1,2) and id !='".$id."'" ,'id');
        die(json_encode($res['id']));
   }
}