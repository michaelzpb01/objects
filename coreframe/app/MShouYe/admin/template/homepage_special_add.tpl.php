<?php defined('IN_WZ') or exit('No direct script access allowed');?>
<?php
include $this->template('header','core');
?>
<body class="body pxgridsbody">
<style type="text/css">
    .tablewarnings{display: none;}
   
</style>
<link href="<?php echo R;?>js/colorpicker/style.css" rel="stylesheet">
<link href="<?php echo R;?>js/jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="<?php echo R;?>js/colorpicker/color.js"></script>
<script src="{R}member/js/jscarousel.js" type="text/javascript"></script>
<link href="{R}css/validform.css" rel="stylesheet" />
<script src="{R}js/validform.min.js"></script>
<script type="text/javascript" src="{R}js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="{R}js/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" src="{R}js/validform.min.js"></script>
<section class="wrapper">
    <div class="row">
     <div class="col-lg-12">
     <section class="panel">
        <div class="panel-body" id="panel-bodys">
        <form name="myform" class="form-horizontal tasi-form" id="form6" action="" method="post">
        <table class="table table-striped table-advance table-hover" id="contenttable">
        <tbody>
      <div id="myTabContent" class="tab-content">
      <div role="tabpanel" class="tab-pane fade active in" id="tabs1" aria-labelledby="1tab">
      <table class="table table-striped table-advance table-hover" id="contenttable">
            <?php

            if(is_array($formdata['0'])) {
                foreach($formdata['0'] as $field=>$info) {

                    if($info['powerful_field']) continue;
                    if($info['formtype']=='powerful_field') {
                        foreach($formdata['0'] as $_fm=>$_fm_value) {
                            if($_fm_value['powerful_field']) {
                                $info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'],$info['form']);
                            }
                        }
                    }
                    ?>
                    <tr>
                        <td style="width: 120px;">
                            <?php if($info['star']){ ?> <font color="red">*</font><?php } ?>
                            <strong><?php echo $info['name']?></strong>
                        </td>
                        <td class="hidden-phone" >
                            <div class="col-sm-12 input-group" style="width: 300px;">
                                <?php echo $info['form']?>
                                <span class="tablewarnings"><?php echo $info['remark']?></span>
                            </div>
                        </td>
                    </tr>
                <?php
                } }?>

        </table>
      </div>
    <!--   <h8> 描述（建议80字以内）</h8> -->
    </div>
        </td>
        </tr>
        <tr>
        <td>
        <div class="contentsubmit text-left" style="margin-top:-50px;margin-left:190px">
        <input name="submit" type="submit" class="save-bt btn btn-info" id="caogao" value="存为草稿"> &nbsp;&nbsp;&nbsp;
            <input name="submit" type="submit" class="save-bt btn btn-info" id="fabu" value="发布"> &nbsp;&nbsp;&nbsp;
       
     </section>
     </div>
    </div>
</section>
<script src="<?php echo R;?>js/bootstrap.min.js"></script>
<script src="<?php echo R;?>js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?php echo R;?>js/pxgrids-scripts.js"></script>
<script src="<?php echo R;?>js/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo R;?>js/jquery.ui.touch-punch.min.js" type="text/javascript"></script>
<link href="<?php echo R;?>css/style.css" rel="stylesheet">
<script type="text/javascript">
 $(function(){
     $("#fabu").bind("click",function(){
       if($("#title").val() == ""){
          alert("请输入标题");
          return false;
       }
       if($("#special").val() == ""){
          alert("请上传专题图");
          return false;
       }
       if($("#address").val() == ""){
          alert("请填写链接地址");
          return false;
       }
     })

     $("#caogao").bind("click",function(){
       if($("#title").val() == ""){
          alert("请输入标题");
          return false;
       }
       if($("#special").val() == ""){
          alert("请上传专题图");
          return false;
       }
       if($("#address").val() == ""){
          alert("请填写链接地址");
          return false;
       }
     })

     $("button:contains(重复检测)").remove();
   
 })
 var tip='<span style="position: relative;top: -25px;left: 310px;"><font size="2">(宽度640px,高度366px;双击可预览图片)</font></span>';
 $(".hidden-phone:eq(1)>.input-group").after(tip);
 var tip1='<span style="position: relative;top: -25px;left: 310px;"><font size="2" color="#f34a4a">(链接以“http://”打头)</font></span>';
 $(".hidden-phone:eq(2)>.input-group").after(tip1);

window.onbeforeunload = function() { 
  if($("#name").val() == '' || $("#area").val() == ''){
　　       return "确定离开页面吗？"; 
  } 
}
</script>


