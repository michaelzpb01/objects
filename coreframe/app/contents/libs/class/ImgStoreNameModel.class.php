<?php

defined('IN_WZ') or exit('No direct script access allowed');

//图片存储名称获取类
class ImgStoreNameModel{
    private $imgSrc;           //图片路径
    private $imgInfos;         //图片信息
    private $is_water;         //图片水印
    private $is_img_allow_show;         //是否允许显示原图

    public function __construct($imgSrc,$is_water,$is_img_allow_show){
        $this->imgSrc = $imgSrc;
        $this->is_water = $is_water;
        $this->is_img_allow_show = $is_img_allow_show;
        $this->imgInfos = getimagesize($imgSrc);
    }

    //获取图片的保存名称
    public function get_img_save_name(){
        $data['md5'] = $this->get_img_md5();
        $data['water'] = $this->get_water();
        $data['type'] = $this->get_img_type();
        $data['size'] = $this->get_img_size();
        $data['allow_show'] = $this->get_img_allow_show();
        $data['ready'] = '00';
        $data['addtime'] = $this->get_img_addtime();
        return implode($data);
    }

    //获取目录信息
    public function get_dirInfo(){
        $dirs = array();
        $dirs[] = substr($this->get_img_md5(), 0,2);
        $dirs[] = substr($this->get_img_md5(), 2,2);
        return $dirs;
    }

    //获取图片md5随机数
    private function get_img_md5(){
        return substr(md5_file($this->imgSrc),0,5);
    }

    //图片水印信息
    private function get_water(){
        if ($this->is_water) {
            return 1;
        }else{
            return 0;
        }
    }

    //图片类型
    // 1 = GIF，2 = JPG，3 = PNG，4 = SWF，5 = PSD，6 = BMP，这是图片类型
    private function get_img_type(){
         return $this->imgInfos[2];
    }

    //原图尺寸
    private function get_img_size(){
        $size = array();
        $tmp = array();
        $tmp[0] = base_convert($this->imgInfos[0],10,36);
        $tmp[1] = base_convert($this->imgInfos[1],10,36);
        $size['width'] = str_pad($tmp[0], 3, "0", STR_PAD_LEFT);
        $size['height'] = str_pad($tmp[1], 3, "0", STR_PAD_LEFT);
        return $size['width'].$size['height'];
    }

    //是否允许显示原图
    private function get_img_allow_show(){
        if ($this->is_img_allow_show) {
            return 1;
        }else{
            return 0;
        }
    }

    //图片的上传时间
    private function get_img_addtime(){
        return base_convert(time(),10,36);
    }
}