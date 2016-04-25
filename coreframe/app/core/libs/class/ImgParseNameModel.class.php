<?php

defined('IN_WZ') or exit('No direct script access allowed');

//图片存储名称解析类
class ImgParseNameModel{

    private $imgName;     //图片名称

    //图片名称设置
    public function init($imgName){
       $this->imgName = $imgName;
       return $this;
    }

    //获取图片的保存名称
    public function get_parse_img_name(){
        $data['dir'] = $this->get_dirInfo();
        $data['water'] = $this->get_water();
        $data['type'] = $this->get_img_type();
        $data['size'] = $this->get_img_size();
        $data['allow_show'] = $this->get_img_allow_show();
        $data['ready'] = '00';
        $data['addtime'] = $this->get_img_addtime();
        return $data;
    }

    //获取目录信息
    public function get_dirInfo(){
        $dirs = array();
        $dirs[] = substr($this->imgName, 0,2);
        $dirs[] = substr($this->imgName, 2,2);
        // $dirs[] = substr($this->imgName, 4,2);
        return $dirs;
    }

    //获取水印信息
    private function get_water(){
        return substr($this->imgName, 5,1);
    }

    //获取图片类型
    // 1 = GIF，2 = JPG，3 = PNG，4 = SWF，5 = PSD，6 = BMP，这是图片类型
    private function get_img_type(){
         return substr($this->imgName, 6,1);
    }

    //获取原图尺寸
    private function get_img_size(){
        $size = array();
        $size['width'] = base_convert(substr($this->imgName, 7,3),36,10);
        $size['height'] = base_convert(substr($this->imgName, 10,3),36,10);
        return $size;
    }

    //是否允许显示原图
    private function get_img_allow_show(){
        return 1;
        return substr($this->imgName, 11,1);
    }

    //获取上传时间
    private function get_img_addtime(){
        return date('Y-m-d H:i:s',base_convert(substr($this->imgName, 16,6),36,10));
    }
}