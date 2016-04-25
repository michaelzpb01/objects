<?php

//工地直播配置

defined('IN_WZ') or exit('No direct script access allowed');

return array(
	//筛选工地直播列表页(开工前)
    '2'=>array('nodeid'=>"11,13,15,17,19"),
    //筛选工地直播列表页(开工中)
    '3'=>array('nodeid'=>"21,23,25,27,29,31,33,35,37"),
    //筛选工地直播列表页(开工后)
    '4'=>array('nodeid'=>"39,41,43,45,47,49,51"),
    //筛选工地直播列表页(完结)
    '5'=>array('nodeid'=>"52"),
    //工地直播详情页普通图文节点
    '6' =>
        array(
            array('nodeid'=>'11','nodename'=>'上门量房'),
            array('nodeid'=>'13','nodename'=>'选定装修公司'),
            array('nodeid'=>'15','nodename'=>'签订设计协议/意向定金'),
            array('nodeid'=>'17','nodename'=>'方案确定预交底'),
            array('nodeid'=>'19','nodename'=>'签施工协议'),
            array('nodeid'=>'21','nodename'=>'工程开工'),
            array('nodeid'=>'23','nodename'=>'拆改'),
            array('nodeid'=>'39','nodename'=>'竣工污染检测'),
            array('nodeid'=>'41','nodename'=>'污染治理'),      
            array('nodeid'=>'45','nodename'=>'尾款质保期'),
            array('nodeid'=>'47','nodename'=>'入住空气检测'),
            array('nodeid'=>'49','nodename'=>'空气治理'),
            array('nodeid'=>'52','nodename'=>'完结')
        ),
    //工地直播详情页特殊材料节点
    '7'=>
        array(
        	array('nodeid'=>'25','nodename'=>'水电材料验收'),
        	array('nodeid'=>'27','nodename'=>'水电验收'),
        	array('nodeid'=>'29','nodename'=>'泥木材料验收'),
        	array('nodeid'=>'31','nodename'=>'泥木验收'),
        	array('nodeid'=>'33','nodename'=>'油漆材料验收'),
        	array('nodeid'=>'35','nodename'=>'油漆验收'),
        	array('nodeid'=>'37','nodename'=>'竣工验收'),
            array('nodeid'=>'43','nodename'=>'复测'),
            array('nodeid'=>'51','nodename'=>'复测')
        	),
	);

?>