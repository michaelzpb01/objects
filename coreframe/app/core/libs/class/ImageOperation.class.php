<?php

defined('IN_WZ') or exit('No direct script access allowed');
require_once('ImgParseNameModel.class.php');
require_once('ImageBase.class.php');

//图片操作类
class ImageOperation{
    private $imgNewPath = PHOTOS_ROOT;        //图片的路径
    private $imgProPath =  ATTACHMENT_ROOT;    //图片最初始路径
    private $fileName;
    private $ImgParseNameModel;    //图片存储信息模型
    private $imgInfo=array();      //图片的信息
    // [296px 222px]  [280px 210px]   [256px 192px]   [120px 90px]  [760px *]
    private $sizeTypes=array(      //图片的类型选择和对应的图片规范
            'big'=>array('width'=>'760','height'=>'0','is_water'=>1,'cutType'=>ImageBase::IMAGE_THUMB_CENTER),
            'middle'=>array('width'=>'296','height'=>'222','is_water'=>0,'cutType'=>ImageBase::IMAGE_THUMB_CENTER),
            'small'=>array('width'=>'280','height'=>'210','is_water'=>0,'cutType'=>ImageBase::IMAGE_THUMB_CENTER),
            'thumb'=>array('width'=>'256','height'=>'192','is_water'=>0,'cutType'=>ImageBase::IMAGE_THUMB_CENTER),
            'small_thumb'=>array('width'=>'120','height'=>'90','is_water'=>0,'cutType'=>ImageBase::IMAGE_THUMB_CENTER),
            'big_square'=>array('width'=>'500','height'=>'500','is_water'=>0,'cutType'=>ImageBase::IMAGE_THUMB_CENTER),
            'middle_square'=>array('width'=>'160','height'=>'160','is_water'=>0,'cutType'=>ImageBase::IMAGE_THUMB_CENTER),
            'small_square'=>array('width'=>'90','height'=>'90','is_water'=>0,'cutType'=>ImageBase::IMAGE_THUMB_CENTER),
            'original'=>array(),
            );      
    private $imgModel;             //图片处理库模型
    private $readPath = '';        //如果选取图片大小类型则去相应的目录查找

    public function __construct($fileName,$sizeType=''){
        if (!$fileName) {
            $this->_nonoImgAction();exit();
        }
        $this->ImgParseNameModel = new ImgParseNameModel();
        $this->imgModel = new ImageBase();
        $this->fileName = $fileName;
        $this->imgInfo = $this->ImgParseNameModel->init($fileName)->get_parse_img_name();
        if (isset($this->sizeTypes[strtolower($sizeType)]) && is_array($this->sizeTypes[strtolower($sizeType)])) {
            $this->readPath = $sizeType;
        }else{
            $this->_nonoImgAction();exit();
        }
    }

    /**
     * 唯一开放方法，给定图片名称参数，输出图片
     */
    public function image_show(){
        if(!$this->_is_new_exists() && $this->_is_pro_exists()){
            $this->_check_dir();
            $this->_copy();
            if ($this->_is_water()) {
                $this->_set_watermark();
            }
        }elseif (!$this->_is_pro_exists()) {
            $this->_nonoImgAction();
            exit();
        }
        $this->_img_display();
    }

    //如果没有图片的情况下的操作
    private function _nonoImgAction(){
        $_nonoImgPath = PHOTOS_ROOT.'static/'.$this->readPath.'/nopic.jpg';
        if (!$this->readPath) {
            $_nonoImgPath = PHOTOS_ROOT.'static/small/nopic.jpg';
        }
        $size = getimagesize($_nonoImgPath);     //获取mime信息 
        $fp=fopen($_nonoImgPath, "rb");          //二进制方式打开文件 
        if ($size && $fp) { 
            header("Content-type: {$size['mime']}"); 
            header("Content-Length: " . filesize($_nonoImgPath));
            fpassthru($fp);                    // 输出至浏览器 
        } else { 
            header('HTTP/1.1 '.'404');
        } 
    }

    //把图片输出的方法
    private function _img_display(){
        if (!$this->_is_new_exists()) {
            header('HTTP/1.1 '.'404');
            exit();
        }
        $size = getimagesize($this->_get_img_src());     //获取mime信息 
        $fp=fopen($this->_get_img_src(), "rb");          //二进制方式打开文件 
        if ($size && $fp) { 
            header("Content-type: {$size['mime']}"); 
            header("Content-Length: " . filesize($this->_get_img_src()));
            
            header("Last-Modified: " . gmdate("D, d M Y H:i:s", strtotime('2011-1-1'))." GMT");
            header('Cache-Control:max-age=2592000');

            fpassthru($fp);                    // 输出至浏览器 
        } else { 
            header('HTTP/1.1 '.'404');
        } 
    }


    //获取文件的绝对路径
    private function _get_img_src(){
        return $this->imgNewPath.'/'.$this->imgInfo['dir'][0].'/'.$this->imgInfo['dir'][1].'/'.$this->readPath.'/'.$this->fileName;
    }

    //获取原始文件的位置
    private function _get_pro_img_src(){
        return $this->imgProPath.'/'.$this->imgInfo['dir'][0].'/'.$this->imgInfo['dir'][1].'/'.$this->fileName;
    }

    //判断文件是否存在
    private function _is_new_exists(){
        return file_exists($this->_get_img_src());
    }

    //判断原文件是否存在
    private function _is_pro_exists(){
        return file_exists($this->imgProPath.'/'.$this->imgInfo['dir'][0].'/'.$this->imgInfo['dir'][1].'/'.$this->fileName);
    }

    //创建目录
    private function _check_dir(){
        $newPath = dirname($this->_get_img_src());
        if(!is_dir($newPath)){
            return mkdir($newPath, 0777,true);
        }
        return true;
    }

    //复制文件
    private function _copy(){
        if ($this->readPath && $this->readPath!='original') {
            $this->_img_cut();
        }else{
            if ($this->imgInfo['allow_show']) {
                return copy($this->imgProPath.'/'.$this->imgInfo['dir'][0].'/'.$this->imgInfo['dir'][1].'/'.$this->fileName, $this->_get_img_src());
            }
        }
    }

    //根据条件裁剪图片
    private function _img_cut(){
        if ($this->readPath == 'big') {
            if (($this->imgInfo['size']['width'])<($this->sizeTypes[$this->readPath]['width'])) {
                copy($this->imgProPath.'/'.$this->imgInfo['dir'][0].'/'.$this->imgInfo['dir'][1].'/'.$this->fileName, $this->_get_img_src());
            }else{
                $scale = $this->sizeTypes[$this->readPath]['width']/$this->imgModel->open($this->_get_pro_img_src())->width();
                $height =  $this->imgModel->open($this->_get_pro_img_src())->height() * $scale;
                $this->imgModel->open($this->_get_pro_img_src())->thumb($this->sizeTypes[$this->readPath]['width'],$height,$this->sizeTypes[$this->readPath]['cutType'])->save($this->_get_img_src());
            }
        }else{
            $this->imgModel->open($this->_get_pro_img_src())->thumb($this->sizeTypes[$this->readPath]['width'], $this->sizeTypes[$this->readPath]['height'],$this->sizeTypes[$this->readPath]['cutType'])->save($this->_get_img_src());
        }
    }

    //判断是否需要添加水印
    private function _is_water(){
        if ($this->imgInfo['water'] && $this->sizeTypes[$this->readPath]['is_water'] && $this->imgInfo['size']['width']>500) {
            return true;
        }
        return false;
    }

    //自动为文件添加上水印
    private function _set_watermark(){
        $this->imgModel->open($this->_get_img_src())->water(WWW_ROOT.'res/images/mart_left_bottom.png',7,50)->water(WWW_ROOT.'res/images/mart_center.png',5,10)->save($this->_get_img_src());
    }
}