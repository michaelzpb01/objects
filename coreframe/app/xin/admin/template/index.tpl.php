<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0, minimal-ui"/>
<meta content="yes" name="apple-mobile-web-app-capable">
<!--iphone桌面快捷方式图标<link rel="apple-touch-icon" href="custom_icon.png">-->
<meta charset="utf-8">
<meta name="keywords" content="装修管家,量房设计,装修施工,材料商城,环保方案,装修服务,装修图库,建材商城,装修攻略、优装网、装修网、优装美家">
<meta name="description" content="优装美家为您提供免费专业咨询、免费量房、免费设计、免费装修保险、免费环保检测、优装网-专业装修网优选装修公司、优选建材商品、优选装修管家、优选环保服务">
<title>优装美家_让装修从此不烦•不凡_优装网-专业装修网</title>
<link href="<?php echo R;?>msites/base/css/base.css" rel="stylesheet" type="text/css">
<link href="<?php echo R;?>msites/application/css/application.css" rel="stylesheet" type="text/css">
<link href="<?php echo R;?>msites/application/css/weibo.css" rel="stylesheet" type="text/css">
</head>

<style type="text/css">
<?if($Previews['background']!=""){

  ?>
   #quality{
      background:url(  <?php echo getMImgShow($Previews['background'],'original')?>);
   }
   <?}?>
   #manager-banner{
    margin-top: -15px;
   }
   .manager-banner{
    margin-top:90px; 
   }
    <?php if($Previews['color']!=""){?>
     #apply-form .orange-btn{
     background-color:#<? echo $Previews['color']?>;
     }
    <?}?>
   <?php if($Previews['color1']!=""){?>
   #apply-form .orange-btn{
     color:#<? echo $Previews['color1']?>;
   }
   <?}?>
   <?php if($Previews['color2']!=""){?>
   #apply-form .number {
    color:#<? echo $Previews['color2']?>;
   }
      <?}?>
   <?php if($Previews['color3']!=""){?>
   #apply-form .total{
    color:#<? echo $Previews['color3']?>;
   }
      <?}?>
</style>

<body id="managerPage">

<div id='share_logo' style='margin:0 auto;display:none;'> 
  <img src="<?php echo R;?>msites/base/img/share_logo.jpg"/> 
</div>
 <?if($Previews['status_4']==1){?>
   <header> 
      <!--<a id="logo" href="##" target="_self" title="优装美家"><i class="iconfont icon-jingyu"></i></a>--> 
      <a id="logo" target="_self" title="优装美家"><i class="iconfont icon-jingyu"></i></a>
      <h1 class="header-title" ><?php echo $Previews['title'];?></h1>
      <div id="menu" title="菜单"><i class="iconfont icon-cedaohang"></i></div>
    </header>
    <?}?>
    <section id="right-menu">
      <div class="sidebar-bg"></div>
      <aside id="sidebar">
        <ul class="sidebar-ul">
            <li>
                <a href="mobile-cases.html" class="link-a"><i class="iconfont icon-tuku"></i><p>精品案例</p><i class="iconfont icon-arrow"></i></a>
            </li>
            <!-- <li>
                <a href="##" class="link-a"><i class="iconfont icon-koubei"></i><p>口碑公司</p><i class="iconfont icon-arrow"></i></a>
            </li>
            <li>
                <a href="##" class="link-a"><i class="iconfont icon-gongdi"></i><p>工地直播</p><i class="iconfont icon-arrow"></i></a>
            </li> -->
            <li>
                <a href="mobile-user_home.html" class="link-a"><i class="iconfont icon-shejishi"></i><p>我的美家</p><i class="iconfont icon-arrow"></i></a>
            </li>
        </ul>
        <a id="apply" href="mobile-application.html?id=M站-菜单页">我要装修</a> <a id="bottom-tel" href="tel:400-6171-666"><i class="iconfont icon-dianhua"></i><span class="telephone">400-6171-666</span></a></aside>
    </section>
    <div id="manager-banner">
    <div class="manager-banner">
           <?if($Previews['headpiece']!=""){
              $aRowid = $aTemp = array();
              $headpiece = explode('|',$Previews['headpiece']);
              $bian = explode('|',$Previews['bian']);
              $uelsx = explode('|',$Previews['headpieceurl']);
              foreach($headpiece as $key => $value){
                  $aTemp[$bian[$key]][] = array(
                    'url' => $value,
                    'bian' => $bian[$key],
                    'uelsx' => $uelsx[$key],
                  );
              }
              ksort($aTemp,SORT_NUMERIC);
              foreach($aTemp as $item){
                  foreach($item as $citem){
                      $aRowid[] = $citem;
                  }
              }
            foreach($aRowid as $Previe){
             if(!empty($Previe['url'])){
              if(!empty($Previe['uelsx'])){?>
              <a href="<?echo $Previe['uelsx']?>"><img src="<?php echo getMImgShow($Previe['url'],'original')?>" alt="装修管家，解决装修痛点" > </a>
            <?  }else{?>
            <img src="<?php echo getMImgShow($Previe['url'],'original')?>" alt="装修管家，解决装修痛点" >
          
        <?}}}}?>
    </div>
    </div>
      <?if($Previews['headpiece']==""){?>
    <div id="manager-banner">
        <img src="<?php echo R;?>msites/application/img/manager_banner.jpg" alt="装修管家，解决装修痛点" style="margin-top:20px;">
    </div><?}?>
           <section id="quality" >
     <?if($Previews['headpiece']==""){?>
        <div class="mod-title" style="margin-top:20px;">
            <h2>有品质的低价</h2>
        </div>
        <ul id="features-1" >
            <li id="icon-liangfang" class="feature">
                <i class="icon">&nbsp;</i>
                <h3>免费量房</h3>
            </li>
            <li id="icon-sheji" class="feature">
                <i class="icon">&nbsp;</i>
                <h3>免费设计</h3>
            </li>
            <li id="icon-baojia" class="feature">
                <i class="icon">&nbsp;</i>
                <h3>免费报价</h3>
            </li>
            <li id="icon-jianli" class="feature">
                <i class="icon">&nbsp;</i>
                <h3>免费监理</h3>
            </li>
            <li id="icon-yanshou" class="feature">
                <i class="icon">&nbsp;</i>
                <h3>免费验收</h3>
            </li>
            <li id="icon-huanbao" class="feature">
                <i class="icon">&nbsp;</i>
                <h3>免费环保</h3>
            </li>
        </ul>
        <?}?>
        <div id="apply-form" style="margin-top:15px;">
            <form name="myform" method="post" id="myform" >
                <div id="name-box">
                    <input id="user-name" name="title" class="apply-input" type="text" maxlength="10" placeholder="您的姓名">
                </div>
                <div id="phone-box">
                    <input id="user-pwd" name="telephone" class="apply-input" type="tel" maxlength="11" placeholder="您的手机">
                </div>
                <div class="user-info fix">
                    <div id="province">
                        <?php if($Previews['city']=='1'){?>
                         <select name="" id="select-00">
                       <option value="0">省/份</option>
                         <?foreach($info as $id => $city){?>
                             <option value="<?php echo $city['lib']?>"><? echo $city['name'];?></option>
                         <?}}?></select><?
                            if($Previews['city']=='2'){?>
                             <select  name="select-000" id="select-000">
                            <?foreach($linkage as $id => $city){?>
                               <option value="<? echo $city['lid']?>"><? echo $city['name']?></option>
                               <?}?>
                             </select>
                               <div id="city" class="fr">
                            <select  name="select-02" id="select-02">
                               <option value="0" selected="">市/地区</option>
                            </select>
                              </div>
                          <?}?>
                <input id="applyBtn" class="orange-btn" type="submit" value="<?php echo $Previews['button'];?>">
                <input id="source" name="source" type="hidden" size="30" value="报名">
            </form>
            <div id="errorCue" class="hideError"><span id="errorMsg">您输入的姓名或电话有错误</span></div>
            <p id="apply-total" class="total">已有<em class="number" id="userTotal">122356</em>人参加</p>
            <p id="apply-tip" class="tip"></p>
        </div>
    </section>
    <?if($Previews['troops']==""){?>
    <section id="manager">
        <div class="mod-title">
            <h2>装修管家</h2>
        </div>
        <ul id="features-2">
            <li class="feature">
                <p class="number"><em>297</em>项</p>
                <p class="con">报价审核</p>
            </li>
            <li class="feature">
                <p class="number"><em>198</em>个</p>
                <p class="con">施工节点验收</p>
            </li>
            <li class="feature">
                <p class="number"><em>108</em>项</p>
                <p class="con">材料验收</p>
            </li>
            <li class="feature">
                <p class="number"><em>2</em>次</p>
                <p class="con">免费环保检测</p>
            </li>
            <li class="feature">
                <p class="number"><em>1</em>个</p>
                <p class="con">环保硬件赠送</p>
            </li>
            <li class="feature">
                <p class="number"><em>100</em>万</p>
                <p class="con">装修保险赠送</p>
            </li>
        </ul>
    </section>
    <section id="steps" class="fix">
        <div class="mod-title">
            <h2>装修流程</h2>
        </div>
        <div id="step1">
            <div class="txt">
                <h3 class="step-name">申请免费量房</h3>
                <p class="con">在线10秒快速申请</p>
            </div>
            <img class="step-img" src="<?php echo R;?>msites/application/img/manager_16.png">
        </div>
        <div id="step2">
            <div class="txt">
                <h3 class="step-name">免费设计报价</h3>
                <p class="con">精选三家公司免费设计报价</p>
            </div>
            <img class="step-img" src="<?php echo R;?>msites/application/img/manager_17.png">
        </div>
        <div id="step3">
            <div class="txt">
                <h3 class="step-name">装修施工</h3>
                <p class="con">专业装修队伍<br>施工质量保证</p>
            </div>
            <img class="step-img" src="<?php echo R;?>msites/application/img/manager_18.png">
        </div>
        <div id="step4">
            <img class="step-img" src="<?php echo R;?>msites/application/img/manager_19.png">
            <div class="txt">
                <h3 class="step-name">全程监理</h3>
                <p class="con">管家全程监理<br>远程了解工地动态</p>
            </div>
        </div>
        <div id="step5">
            <div class="txt">
                <h3 class="step-name">环保检测</h3>
                <p class="con">2次专业环保仪检测<br>放心入住</p>
            </div>
            <img class="step-img" src="<?php echo R;?>msites/application/img/manager_23.png">
        </div>
        <div id="step6">
            <div class="txt">
                <h3 class="step-name">环保治理</h3>
                <p class="con">专业污染治理解决方案<br>消灭污染源头</p>
            </div>
            <img class="step-img" src="<?php echo R;?>msites/application/img/manager_27.png">
        </div>
        <div id="step7">
            <div class="txt">
                <h3 class="step-name">赠送空气监测仪</h3>
                <p class="con">随时随地<br>监测室内空气质量</p>
            </div>
            <img class="step-img" src="<?php echo R;?>msites/application/img/manager_31.png">
        </div>
    </section>
     <div id="follow">
        <img class="code" src="<?php echo R;?>msites/application/img/code.png">
        <p class="txt">长按识别二维码，关注获取更多惊喜！</p>
    </div> 
    <?}?>
     <div id="manager-banner">
           <?if($Previews['troops']!=""){
              $aRowids = $aTems = array();
              $troops = explode('|',$Previews['troops']);
              $bians = explode('|',$Previews['bians']);
              $urlsh = explode('|',$Previews['troopsurl']);
              foreach($troops as $key => $value){
                  $aTems[$bians[$key]][] = array(
                    'urlt' => $value,
                    'bians' => $bians[$key],
                    'urlsh' => $urlsh[$key],
                  );
              }
              ksort($aTems,SORT_NUMERIC);
              foreach($aTems as $items){
                  foreach($items as $citems){
                      $aRowids[] = $citems;
                  }
              }
              foreach($aRowids as $Previe){
              if(!empty($Previe['urlsh'])){?>
                  <a href="<?php $Previe['urlsh']?>"><img src="<?php echo getMImgShow($Previe['urlt'],'original')?>" alt="装修管家，解决装修痛点" ></a>   
              <?}else{?>
                 <img src="<?php echo getMImgShow($Previe['urlt'],'original')?>" alt="装修管家，解决装修痛点" >
        <?}}}?>
      </div>



    <?if($Previews['status_1']==1){?>
    <a id="bottom-telephone" href="tel:400-6171-666">咨询电话：400-6171-666</a>
    <?}?>
    <?if($Previews['status_2']==1){?>
    <button id="free-btn">免费申请</button>
    <?}?>
  <?if($Previews['status_3']==1){?>
    <footer id="footer">
     <p class="company-name">北京优装网信息科技有限公司 京ICP备15022586号-1</p>
    	<ul class="platforms">
        	<li class="active"></li>
            <li class="vline"></li>
            <li><a href="http://www.uzhuang.com/">/a></li>
            <li class="vline"></li>
            <li><a href="#"</a></li>
        </ul>

    </footer>
  <?}?>
<script src="<?php echo R;?>msites/base/js/zepto.min.js"></script>
<script src="<?php echo R;?>msites/base/js/base.js"></script>
<script src="<?php echo R;?>msites/application/js/send_form.js"></script>
<script src="<?php echo R;?>msites/application/js/manager_select.js"></script>
<script>
	var _hmt = _hmt || [];
	(function() {
		var hm = document.createElement("script");
		hm.src = "//hm.baidu.com/hm.js?78dc231309600aa470786dd036953521";
		var s = document.getElementsByTagName("script")[0];
		s.parentNode.insertBefore(hm, s);
		
	})();
</script>
<script>
	$(function(){
		$('#select-01').loadProvince();
	})
</script>
<script type="text/javascript">
    $("#select-000").change(function(){
    var pid=$(this).val();
    $.ajax({
        url:"?m=xin&f=content&v=public_threeLevel<?php echo $this->su(); ?>",
        data:{'pid':pid},
        type:"POST",
        dataType:"json",
        success:function(data){
            $("#select-02").empty();
            $.each(data,function(coun,counObj){
                $("#select-02").append("<option value='"+counObj.lid+"'>"+counObj.name+"</option>");
            })
        }
    })
})
</script>
</body>
</html>
