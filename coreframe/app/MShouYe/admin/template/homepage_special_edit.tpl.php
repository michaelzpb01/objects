<script type="text/javascript" src="<?php echo R; ?>js/jquery-1.8.3.min.js"></script>
<?php defined('IN_WZ') or exit('No direct script access allowed'); ?>
<?php
include $this->template('header', 'core');
?>
<body class="body pxgridsbody">
<style type="text/css">
.tablewarnings {
display: none;
}
.self{
    display:inline-block;
    width:80px;
}
.col-sm-4{
    display: none;
}

body, html {
  overflow: auto !important;
}
</style>
<link href="<?php echo R; ?>js/colorpicker/style.css" rel="stylesheet">
<link href="<?php echo R; ?>js/jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="<?php echo R; ?>js/colorpicker/color.js"></script>
<section class="wrapper">
<div class="row">
<div class="col-lg-12">
<section class="panel" style="border-top: 2px solid #2E3238;">
<div class="panel-body" id="panel-bodys">
<form name="myform" class="form-horizontal tasi-form" action="" method="post">
<table class="table table-striped table-advance table-hover" id="contenttable">
<tbody>
<tr>
<td>
<ul id="myTab" class="nav nav-tabs" role="tablist">
</ul>
</li>
</ul>
<div id="myTabContent" class="tab-content">
<div role="tabpanel" class="tab-pane fade active in" id="tabs1" aria-labelledby="1tab">
      <form action="?m=MShouYe&f=homepage_special&v=edit<?php echo $this->su();?>" class="form-inline" method="post">
        <div class="input-group">
          <table class="table table-striped table-advance table-hover"id="contenttable">
            <!--  <font size="4px" style="float:left">城市：</font>
               <select name="areaid" class="self form-control" style="width:100px;margin-right:5px;position:relative;" id="province">
                 <?foreach($result  as $b){?>
                     <option value=<?php echo $b['lid']; ?> <?php if($re['areaid']==$b['lid']){echo 'selected';} ?> ><?echo $b['name']?></option>
                  <?}?>
               </select>
               <select name="areaid_1" class="self form-control" style="width:100px;margin-right:5px;position:relative;" id="city">
                   <?foreach($res  as $bb){?>
                    <option value=<?php echo $b['lid']; ?> <?php if($re['areaid_1']==$bb['lid']){echo 'selected';} ?> ><?echo $bb['name']?></option>
                    <?}?>
               </select>
               <select name="areaid_2" class="self form-control" style="width:100px;margin-right:5px;position:relative;" id="country">
                  <?foreach($resu as $bbb){?>
                   <option value=<?php echo $bbb['lid']; ?> <?php if($re['areaid_2']==$bbb['lid']){echo 'selected';} ?> ><?echo $bbb['name']?></option>
                 <?}?>
               </select><br/><br/> -->
               
              <font size="2px" ><span style="color:red">*</span><b>标题:&nbsp;&nbsp;</b></font> 
             <input class="input1" name="title" value="<? echo $re['title']?>" ><br/><br/>
             <span style="float:left;"><font style="color:red">*</font><b><font size="2px">专题图:</font></b>&nbsp;&nbsp;</span><input type="text" value="<?php echo $re['special'];?>" ondblclick="img_view('?m=core&f=image_privew&imgurl='+this.value);" class="form-control" id="attachment_test" name="attachment_test" size="100" style="width:450px;margin-right:5px;position:relative;">
             <font size="2px" style="float:left">
            <span class="input-group-btn">
            <button type="button" class="btn btn-white" onclick="openiframe('/index.php?m=attachment&amp;f=index&amp;v=upload_dialog&amp;callback=callback_thumb_dialog&amp;htmlid=attachment_test&amp;limit=1&amp;htmlname=setting%5Battachment_test%5D&amp;ext=png%7Cjpg%7Cgif%7Cdoc%7Cdocx&amp;token=3d9292f4c141b7f9c4f3a37f8af2e607&amp;_menuid=29&amp;_submenuid=67','attachment_test','loading...',810,400,1)">上传文件</button>
            </span>
            <span style="position: relative;top: -25px;left: 90px;"><font size="2">(宽度640px,高度366px;双击可预览图片)</font></span>
            </font><br/><br/>
               <font size="2px"><span style="color:red">*</span><b>链接地址:&nbsp;&nbsp;</b></font> 
             <input class="input2" name="address" value="<? echo $re['address']?>" style="width:450px;"><font size="2" color="#f34a4a">(宽度640px,高度366px;双击可预览图片)</font>
              <br/><br/>
        </table>
        </div>
            <!-- <input name="submit" type="submit" class="save-bt btn btn-info" id="caogao" value="存为草稿"> &nbsp;&nbsp;&nbsp; -->
            <input name="submit" type="submit" class="save-bt btn btn-info" id="fabu" value="发布"> &nbsp;&nbsp;&nbsp; 
  </form>
              
</div>
</div>
</td>
</tr>
<tr>
<td>
<div class="contentsubmit text-center">
<input type="hidden" name="modelid" value="<?php echo $modelid; ?>">
</div>
</td>
</tr>
</tbody>
</table>
</form>
</div>
</section>
</div>
</div>
</section>
<script src="<?php echo R; ?>js/bootstrap.min.js"></script>
<script src="<?php echo R; ?>js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?php echo R; ?>js/pxgrids-scripts.js"></script>
<script src="<?php echo R; ?>js/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo R; ?>js/jquery.ui.touch-punch.min.js" type="text/javascript"></script>
<link href="<?php echo R; ?>css/style.css" rel="stylesheet">
<script src="<?php echo R; ?>js/html5upload/extension.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
      
      $(".input1").css({'border':'1px solid #E2E2E4','font-size':'15px'});
      $(".input2").css({'border':'1px solid #E2E2E4','font-size':'15px'});
      
  })

 $(function(){
     $("#fabu").bind("click",function(){
       if($(".input1").val() == ""){
          alert("请输入标题");
          return false;
       }
       if($(".input2").val() == ""){
          alert("请上传专题图");
          return false;
       }
       if($(".input3").val() == ""){
          alert("请填写链接地址");
          return false;
       }
     })

     $("#caogao").bind("click",function(){
       if($(".input1").val() == ""){
          alert("请输入标题");
          return false;
       }
       if($(".input2").val() == ""){
          alert("请上传专题图");
          return false;
       }
       if($(".input3").val() == ""){
          alert("请填写链接地址");
          return false;
       }
     })

 })



 $("#ti").click(function(){
    if (!confirm('确定提交编辑的信息？')) {
      return false;
    };
  })  
 // $("#companyid").attr("disabled",true)
    $("#province").change(function(){
    var pid=$(this).val();
    $.ajax({
        url:"?m=Mjin&f=content&v=public_threeLevel<?php echo $this->su(); ?>",
        data:{'pid':pid},
        type:'POST',
        dataType:"json",
        success:function(data){
            $("#city").empty().removeAttr("disabled");
            $("#country").removeAttr("disabled");
            $.each(data,function(area,areaObj){
                var cit=$("<option value='"+areaObj.lid+"'>"+areaObj.name+"</option>");
                $("#city").append(cit);
            })
            var nid=data[0]['lid'];
            $.post("?m=Mjin&f=content&v=public_threeLevel<?php echo $this->su(); ?>",{'pid':nid},function(data2){
                $("#country").empty();
                $.each(data2,function(coun,counObj){
                    $("#country").append("<option value='"+counObj.lid+"'>"+counObj.name+"</option>")
                })
            },'json')
        }
    })
})
$("#city").change(function(){
    var pid=$(this).val();
    $.ajax({
        url:"?m=Mjin&f=content&v=public_threeLevel<?php echo $this->su(); ?>",
        data:{'pid':pid},
        type:"POST",
        dataType:"json",
        success:function(data){
            $("#country").empty();
            $.each(data,function(coun,counObj){
                $("#country").append("<option value='"+counObj.lid+"'>"+counObj.name+"</option>");
            })
        }
    })
})

$(document).on('click','.removeTr',function(){
  $(this).closest('tr').remove();
})
    $(".save-bt").click(function(){
        t=setTimeout("hide_formtips()",5000);
    });
    $(function(){
        $(".form-horizontal").Validform({
            tiptype:1
            //$.Hidemsg()
        });
    })
    $("#companyname").change(function(){
      var cid=$(this).val();
      $("#companyid").val(cid);
      $.post("?m=Mjin&f=content&v=getDesigner<?php echo $this->su();?>",{"cid":cid},function(data){
        var designer = $("#designer");
        designer.empty();
        var len = data.length;
        for(var z=0;z<len;z++){
          designer.append('<option value='+data[z]['id']+'>'+data[z]['title']+'</option>');
        }
      },'json')
    })
    $(document).on('keyup','#materialtotal',function(){
      var v1 = $(this).val();
      if (v1) {
        v1 = parseInt(v1);
      }else{
        v1 = 0;
      }
      var v2 = $("#crafttotal").val();
      if (v2) {
        v2 = parseInt(v2);
      }else{
        v2=0;
      }
      var total = v1+v2;
      $("#total").val(total);
    })
    $(document).on('keyup','#crafttotal',function(){
      var v1 = $(this).val();
      if (v1) {
        v1 = parseInt(v1);
      }else{
        v1 = 0;
      }
      var v2 = $("#materialtotal").val();
      if (v2) {
        v2 = parseInt(v2);
      }else{
        v2=0;
      }
      var total = v1+v2;
      $("#total").val(total);
    })

    /*function fillurl(obj,value) {
        if(value!='' && $("#route_type").val()==3) {
            value = value.replace("<?php echo POSTFIX;?>","");
            $(obj).val(value+'<?php echo POSTFIX;?>');
        }
    }*/
    function change_route(name,value) {
        $("#def_type").html(name);
        $("#route_type").val(value);
    }
    function hide_formtips() {
        $.Hidemsg();
        clearInterval(t);
    }
    function check_title() {
        var title = $("#title").val();
        if(title=='') {
            alert('请填写标题');
            $("#title").focus();
        } else {
            $.post("?m=content&f=content&v=checktitle<?php echo $this->su();?>", { title: title,cid:<?php echo $cid;?>,id:0},
                function(data){
                    if(data=='ok') {
                        alert('没有重复标题');
                    } else if(data=='1') {
                        alert('有完全相同的标题存在');
                    } else if(data=='2') {
                        alert('有相似度很高的标题存在');
                    }
                });
        }
    }
/*function remove_file(file_id)
{
  $('#file_node_'+file_id).remove();
}*/
<?php
if($cate_config['workflowid'] && $_SESSION['role']!=1) {
?>
    $("input[name='form[status]'][value='9']").attr("disabled",true);
    $("input[name='form[status]'][value='8']").attr("disabled",true);
    $("input[name='form[status]'][value='1']").attr("checked",true);

<?php }?>
</script>
