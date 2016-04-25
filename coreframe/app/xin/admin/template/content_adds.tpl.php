<?php defined('IN_WZ') or exit('No direct script access allowed');?>
<?php
include $this->template('header','core');
?>
<body class="body">
<style type="text/css">
   .exercise{
    margin-left:50px; 
   }
   body, html {
  overflow: auto !important;
}
</style>
<link href="<?php echo R;?>js/colorpicker/style.css" rel="stylesheet">
<link href="<?php echo R;?>js/jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="<?php echo R;?>js/colorpicker/color.js"></script>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
              <form action="?m=xin&f=content&v=add<?php echo $this->su();?>" class="form-inline"  id="form6" method="post">
              <div class="exercise">
                    <input type="radio" name="type"  id="N" value="1" checked="">普通&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="type"  id="H" value="2">H5<br/><br/>
           
                <table class="table table-striped table-advance table-hover">
                   <tr>
                     <th>
                      <font ><span style="color:red">*</span>列表名称</font>: <input type="text" name="exercise" id="title" class="input" style="height:35px; width:300px; "placeholder="描述（建议1-15字符之间）" value="">
                      </th>
                   </tr>
                   <tr>
                     <th>
                       <font ><span style="color:red">*</span>活动名称</font>: <input type="text" name="name" id='name' class="input" style="height:35px; width:300px; " placeholder="描述（建议1-8字符之间）"value="">
                     </th>
                   </tr>
                   <tr >
                    <th>
                      <font >顶部图片上传</font>: <span style="color:red" class='H5'>宽度640px  高度不限</span>
                      <span style="color:red" class='ISH5'>640*1136</span>
                      <div class="attaclist"><div id="case"><ul id="case_ul"></ul></div><span class="input-group-btn">
                         <button type="button" class="btn btn-white" onclick="openiframe('/index.php?m=attachment&amp;f=index&amp;v=upload_dialog&amp;callback=callback_images6&amp;htmlid=case&amp;limit=20&amp;width=1&amp;htmlname=form%5Bcase%5D&amp;ext=jpg%7Cpng%7Cgif%7Cbmp&amp;token=34f98e4c74f75c6701341b703f09917b&amp;_menuid=5002','case','loading...',810,400,20)">上传图片</button></span></div>
                    </th>
                   </tr>
                   <tr>
                     <th>
                        <font >提交背景图片:</font>
                        <input type="text" value="<?php echo $special['cover']?>" ondblclick="img_view(this.value);" class="form-control" id="attachment_test" name="attachment_test" size="100" style="width:450px;margin-right:5px;position:relative;" placeholder="背景高度384px" > 
                        <button type="button"  style="" class="btn btn-white" onclick="openiframe('/index.php?m=attachment&amp;f=index&amp;v=upload_dialog&amp;callback=callback_thumb_dialog&amp;htmlid=attachment_test&amp;limit=1&amp;htmlname=setting%5Battachment_test%5D&amp;ext=png%7Cjpg%7Cgif%7Cdoc%7Cdocx&amp;token=3d9292f4c141b7f9c4f3a37f8af2e607&amp;_menuid=29&amp;_submenuid=67','attachment_test','loading...',810,400,1)">上传文件</button><br/><br/>

                      </th>
                    </tr>
                    <tr class="H5">
                      <th>
                        <font >底部图片上传</font>: <span style="color:red">宽度640px  高度不限</span>
                        <div class="attaclistx"><div id="casex"><ul id="case_ul"></ul></div><span class="input-group-btn">
                        <button type="button" class="btn btn-white" onclick="openiframe('/index.php?m=attachment&amp;f=index&amp;v=upload_dialog&amp;callback=callback_images7&amp;htmlid=casex&amp;limit=20&amp;width=1&amp;htmlname=form%5Bcasex%5D&amp;ext=jpg%7Cpng%7Cgif%7Cbmp&amp;token=34f98e4c74f75c6701341b703f09917b&amp;_menuid=5002','casex','loading...',810,400,20)"> 上传图片</button></span></div>
                      </th>
                     </tr>
                     <tr>
                       <th>
                         <font >是否显示公共顶</font>: <br/>
                         <input type="radio" name="status_4" value="1" checked="">是&nbsp;&nbsp;&nbsp;
                         <input type="radio" name="status_4" value="2" >否
                       </th>
                       </th>
                     </tr>
                     <tr>
                       <th>
                         <font >提交信息,区域选择</font>: <br/>
                         <input type="radio" name="city" value="1" checked="">公司开通城市&nbsp;&nbsp;&nbsp;
                         <input type="radio" name="city" value="2" >全国城市
                       </th>
                     </tr>
                     <tr>
                       <th>
                         <font >报名人数基数</font>:<br/>
                          <input type="text" name="person"  class="input" style="height:35px; width:300px; " value="">
                       </th>
                     </tr>
                     <tr>
                       <th>
                         <font >提交按钮文案</font>:<br/>
                          <input type="text" name="button"  class="input" style="height:35px; width:300px; " value="">
                       </th>
                     </tr>
                       <tr>
                       <th>
                         <font >提交按钮背景颜色</font><font style="margin-left:52px;">提交按钮文案颜色</font><font  style="margin-left:52px;">报名人数颜色</font><font  style="margin-left:42px;">报名人数提示语颜色</font><br/>
                          <input type="text" name="color"  class="input" style="height:35px; width:130px; " value="">   
                          <input type="text" name="color1"  class="input" style="height:35px; width:130px;margin-left:15px;" value="">
                          <input type="text" name="color2"  class="input" style="height:35px; width:95px;margin-left:15px; " value="">
                          <input type="text" name="color3"  class="input" style="height:35px; width:145px;margin-left:15px;" value="">
                       </th>
                     </tr>
                       <tr class ='H5'>
                       <th>
                         <font >是否显示底部电话</font>: <br/>
                           <input type="radio" name="status_1" value="1" checked="">是&nbsp;&nbsp;&nbsp;
                           <input type="radio" name="status_1" value="2" >否
                       </th>
                     </tr>
                         <tr class ='H5'>
                       <th>
                         <font >是否显示吸底按钮</font>: <br/>
                         <input type="radio" name="status_2" value="1" checked="">是&nbsp;&nbsp;&nbsp;
                         <input type="radio" name="status_2" value="2" >否
                       </th>
                     </tr>
                     <tr class ='H5'>
                       <th>
                         <font >是否显示公共底</font><br/>
                         <input type="radio" name="status_3" value="1" checked="">是&nbsp;&nbsp;&nbsp;
                         <input type="radio" name="status_3" value="2" >否
                       </th>
                     </tr>
                     </div>
                     </tr>
                    <tr>
                       <th>
                         <span style="color:red">*</span><font >分享图标</font>:
                         <input type="text" value="<?php echo $special['cover']?>" ondblclick="img_view(this.value);" class="form-control" id="share" name="share" size="100" style="width:450px;margin-right:5px;position:relative;" placeholder='300px*300px'>
                         <button type="button" class="btn btn-white" onclick="openiframe('/index.php?m=attachment&amp;f=index&amp;v=upload_dialog&amp;callback=callback_thumb_dialog&amp;htmlid=share&amp;limit=1&amp;htmlname=setting%5Bshare%5D&amp;ext=png%7Cjpg%7Cgif%7Cdoc%7Cdocx&amp;token=3d9292f4c141b7f9c4f3a37f8af2e607&amp;_menuid=29&amp;_submenuid=67','share','loading...',810,400,1)">上传图标</button>
                       </th>
                     </tr>
                     <tr>
                       <th>
                         <span style="color:red">*</span><font >分享标题:</font>
                         <input type="text" name='sharename' id='sharename'style='width:300px; height:30px;' placeholder='24个字符以内'>
                       </th>
                     </tr>
                     <tr>
                       <th>
                         <span style="color:red">*</span><font>分享描述：</font>
                         <textarea name="sharedescribe" id='sharedescribe' cols="45" rows="4" placeholder='34个字符以内' ></textarea>
                       </th>
                     </tr>
                </table>
                   </div>
                    <center>
                     <button style=""class="save-bt btn btn-info" onclick="openiframe('?m=xin&f=content&v=Previewe&'+$('form').serialize()+'<?php echo $this->su();?>','','查看详情',1100,1500);" type="button" target="_blank">预览</button> 
                     <input name="submit" type="submit" class="save-bt btn btn-info" id="baoG" value="发布" >
                    </center> 
                </form>
            </section>
        </div>
</section>
<script src="<?php echo R;?>js/bootstrap.min.js"></script>
<script src="<?php echo R;?>js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?php echo R;?>js/pxgrids-scripts.js"></script>
<script src="<?php echo R;?>js/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo R;?>js/jquery.ui.touch-punch.min.js" type="text/javascript"></script>
<link href="<?php echo R;?>css/style.css" rel="stylesheet">
<script src="<?php echo R; ?>js/html5upload/extension.js"></script>
<link href="{R}css/validform.css" rel="stylesheet" />
<script src="{R}js/validform.min.js"></script>
<script type="text/javascript" src="{R}js/validform.min.js"></script>
<script type="text/javascript">
  $("#baoG").click(function(){
    var v1 = $("#name").val();
    var v2 = $("#title").val();
    var v3 = $('#share').val();
    var v4 = $('#sharename').val();
    var v5 = $('#sharedescribe').val();
    if (!v1) {
      alert("请输入标题名称");
      return false;
    };
    if (!v2){
      alert("请输入活动名称");
      return false;
    };
     if(!v3){
       alert("请上传分享图标");
       return false;
     }
     if(!v4){
      alert("请输入分享标题");
      return false;
     }
     if(!v5){
      alert('请填写分享描述');
      return false;
     }
  })
 $("#baoG").click(function(){
      var titleLen = $("#title").val().length;
      var nameLen = $("#name").val().length;
      var sharenameLen = $("#sharename").val().length;
      var sharedescribeLen = $("#sharedescribe").val().length;
      if (titleLen>15) {
        alert('列表名称最多输入15个字符')
        return false;
      }
      if (nameLen>8) {
        alert('活动名称最多输入8个字符')
        return false;
      }
      if(sharenameLen>25){
        alert('分享标题最多输入24个字符')
        return false;
      }
       if(sharedescribeLen>34){
        alert('分享描述最多输入34个字符')
        return false;
      }
  })
 $("#baoG").click(function(){
    var name = $("#title").val();
    var flag=true;
     $.ajax({
        url:"?m=xin&f=content&v=checkNameExit<?php echo $this->su();?>",
        data:{'title':name},
        type:"POST",
        dataType:"json",
        success:function(data){
          if (data) {
            flag=false;
            alert("列表名称已经存在");
            return false;
          }else{
            flag=true;
          }
        },
        async:false
      })
     if (!flag) {
      return false;
     };
  }) 
 $(".ISH5").css("display","none");
 $('#H').click(function(){
   $(".H5").css("display","none");
    $(".ISH5").css("display","");
    $('#attachment_test').attr('placeholder','背景图片要求640*1136');
  
 })
 $('#N').click(function(){
   $(".ISH5").css("display","none");
   $(".H5").css("display","");
   $('#attachment_test').attr('placeholder','背景高度384px');

 })
</script>
</body>
</html>