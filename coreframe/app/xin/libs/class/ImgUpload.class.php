<?php

defined('IN_WZ') or exit('No direct script access allowed');
require_once('ImgStoreNameModel.class.php');
//图片上传类
class ImgUpload{
    
    // 默认上传配置
    private $config = array(
        //允许上传的文件MiMe类型
        'mimes'         =>  array('image/gif','image/jpeg','image/png','image/bmp','image/pjpeg','image/x-png'), 
        //上传的文件大小限制 (0-不做限制) 5*1024*1024
        'maxSize'       =>  5242880, 
        //允许上传的文件后缀
        'exts'          =>  array('gif','jpg','jpeg','bmp','png','swf'), 
        //根路径
        'rootPath'      =>  ATTACHMENT_ROOT, 
        //保存子路径
        'savePath'      =>  '', 
    );

    private $error = ''; //上传错误信息
    private $imgModel;   //图片存储名称获取类
    private $ext;        //存储文件的后缀名

    public function upload($files,$is_water=false,$is_allow_show_img=false){
        if ($this->_check($files)) {
            $this->imgModel = new ImgStoreNameModel($files['tmp_name'],$is_water,$is_allow_show_img);
            $savePath = $this->imgModel->get_dirInfo();
            $this->config['savePath'] = $savePath[0].'/'.$savePath[1];
            if($this->checkSavePath($this->config['savePath'])){
                $saveName = $this->imgModel->get_img_save_name();
                $saveName = $saveName.'.'.$this->ext;
                if($this->exec_upload($files['tmp_name'],$saveName)){
                    $info['saveName'] = $saveName;
                    return $info;
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
    }


    public function exec_upload($tmp_name,$destination){
        $destination = $this->config['rootPath'] .$this->config['savePath'].'/'. $destination;
        if (!move_uploaded_file($tmp_name, $destination)) {
            $this->error = '文件上传保存错误！';
            return false;
        }
        return true;
    }

    //检测上传的目录
    private function checkSavePath($savePath){
        if (!$this->_mkdir($savePath)) {
            return false;
        } else {
            // var_dump($this->config['rootPath'] . $savePath);die;
            if (!is_writable($this->config['rootPath'] . $savePath)) {
                $this->error = '上传目录 ' . $savepath . ' 不可写！';
                return false;
            } else {
                return true;
            }
        }
    }

    //获取最后一次上传错误信息
    public function getError(){
        return $this->error;
    }


    //创建目录
    private function _mkdir($savePath){
        $dir = $this->config['rootPath'] . $savePath;
        if(is_dir($dir)){
            return true;
        }
        return mkdir($dir, 0777, true);
    }

    /**
     * 检查上传的文件
     * @param array $file 文件信息
     */
    private function _check($file) {
        /* 文件上传失败，捕获错误代码 */
        if ($file['error']) {
            $this->_error($file['error']);
            return false;
        }
        /* 无效上传 */
        if (empty($file['name'])){
            $this->error = '未知上传错误！';
        }

        /* 检查是否合法上传 */
        if (!is_uploaded_file($file['tmp_name'])) {
            $this->error = '非法上传文件！';
            return false;
        }

        /* 检查文件大小 */
        if (!$this->_checkSize($file['size'])) {
            $this->error = '上传文件大小不符！';
            return false;
        }

        /* 检查文件Mime类型 */
        //TODO:FLASH上传的文件获取到的mime类型都为application/octet-stream
        /*if (!$this->_checkMime($file['type'])) {
            $this->error = '上传文件MIME类型不允许！';
            return false;
        }*/

        /* 检查文件后缀 */
        if (!$this->_checkExt($file['name'])) {
            $this->error = '上传文件后缀不允许';
            return false;
        }

        /* 通过检测 */
        return true;
    }


    //判断文件错误码
    private function _error($errorNo) {
        switch ($errorNo) {
            case 1:
                $this->error = '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值！';
                break;
            case 2:
                $this->error = '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值！';
                break;
            case 3:
                $this->error = '文件只有部分被上传！';
                break;
            case 4:
                $this->error = '没有文件被上传！';
                break;
            case 6:
                $this->error = '找不到临时文件夹！';
                break;
            case 7:
                $this->error = '文件写入失败！';
                break;
            default:
                $this->error = '未知上传错误！';
        }
    }

    //检查文件大小
    private function _checkSize($size) {
        return !($size > $this->config['maxSize']) || (0 == $this->config['maxSize']);
    }

    //检测文件类型
    private function _checkMime($mime) {
        return empty($this->config['mimes']) ? true : in_array(strtolower($mime), $this->config['mimes']);
    }

    //检测文件后缀
    private function _checkExt($name) {
        $ext = pathinfo($name,PATHINFO_EXTENSION);
        $this->ext = strtolower($ext);
        return empty($this->config['exts']) ? true : in_array(strtolower($ext), $this->config['exts']);
    }
}