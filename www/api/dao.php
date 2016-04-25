<?php
header('Content-type:text/html;charset=utf-8');
header("Access-Control-Allow-Origin: *");
define('WWW_ROOT',substr(dirname(__FILE__), 0, -4).'/');
require '../configs/web_config.php';
require COREFRAME_ROOT.'core.php';
    $db = load_class('db');
    $pictur=$db->get_list('m_picture','status!=4 and cover!="" ','*',0,100000000000,$page,'updatetime desc','');
    $pictur=$db->get_list('m_picture','status!=4 and cover!="" ','*',0,100000000000,$page,'updatetime desc','');
    foreach($pictur as $picture){
         $rest = substr($picture['cover'], 0, 2); 
         $rest1 = substr($picture['cover'],2, 2);    
              
         $dir = "/alidata/ln-for-images/uploadfile".'/'.$rest.'/'.$rest1;
			if (is_dir($dir)) {
			 $dh = opendir($dir);
			  	while (($file = readdir($dh)) !== false) {
					if($file!='.'&&$file!='..'){
						$newfilename = $dir.'/'.$file;
					    if (is_file($newfilename)) {
					    	if (file_exists('/alidata/ln-for-images/muploadfile') == false) {
                                mkdir('/alidata/ln-for-images/muploadfile');
                              }
                             if (file_exists("/alidata/ln-for-images/muploadfile".'/'.$rest) == false) {
                                      mkdir("/alidata/ln-for-images/muploadfile".'/'.$rest);
                               }
                             if (file_exists("/alidata/ln-for-images/muploadfile".'/'.$rest.'/'.$rest1) == false) {
                                      mkdir("/alidata/ln-for-images/muploadfile".'/'.$rest.'/'.$rest1);
                                        $mder="/alidata/ln-for-images/muploadfile".'/'.$rest.'/'.$rest1."/";
                                        $aaa = file_get_contents($newfilename);
					                    file_put_contents($mder.$file,$aaa);
                             }
					     }
					  }
				  }
				     closedir($dh);
		 }
         $aRowid = array();
         $headpiece = explode('|',$picture['case']);
            foreach($headpiece as $key => $value){
                   $aRowid[] = array(
                     'case' => $value,
                  );
                }
              foreach($aRowid as $aRowids){
               if($aRowids['case']!=""){
               	 $rest2 = substr($aRowids['case'], 0, 2); 
                 $rest3 = substr($aRowids['case'],2, 2); 
                 $dir1 = "D:/phpStudy/www/m/www/uploadfile/".$rest2.'/'.$rest3;
               if (is_dir($dir1)){
                 $dh1 = opendir($dir1);
                 while (($file1 = readdir($dh1)) !== false) {
                 	if($file1!='.'&&$file1!='..'){
                 	  $newfilename1 = $dir1.'/'.$file1;
                 	  echo $newfilename1."<br/>";
                 	   if (is_file($newfilename1)) {
				    	if (file_exists('/alidata/ln-for-images/muploadfile') == false) {
                            mkdir('/alidata/ln-for-images/muploadfile');
                          }
                         if (file_exists("/alidata/ln-for-images/muploadfile".'/'.$rest2) == false) {
                            mkdir("/alidata/ln-for-images/muploadfile".'/'.$rest2);
                          }
                          if (file_exists("/alidata/ln-for-images/muploadfile".'/'.$rest2.'/'.$rest3) == false) {
                           mkdir("/alidata/ln-for-images/muploadfile".'/'.$rest2.'/'.$rest3);
                            $mder1="/alidata/ln-for-images/muploadfile".'/'.$rest2.'/'.$rest3."/";
                            $aaaa = file_get_contents($newfilename1);
		                    file_put_contents($mder1.$file1,$aaaa);
                          }
                       }
                    }
                  }
                  closedir($dh1);
                }
              }
            }
         }
        


      

?>