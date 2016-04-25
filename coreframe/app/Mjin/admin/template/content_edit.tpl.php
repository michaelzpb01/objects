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
      <form action="?m=Mjin&f=content&v=listing<?php echo $this->su();?>" class="form-inline" method="post">
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
              <font size="4px" style="float:left"><span style="color:red">*</span>案例名称:</font> 
             <input name="name" value="<? echo $re['name']?>"><br/><br/>
             <font size="4px" style="float:left"><span style="color:red">*</span>面&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;积：</font> 
             <input name="area" value="<? echo $re['area']?>"> <br/><br/>
        <font size="4px" style="float:left; ">风格：</font>
              <div style="float:left ;margin-right:30px; width:350px;">
                  <?  $sites=explode(',',$re['style']);
                     foreach($style as $keys => $site){?>
                      <input type="checkbox"  name="styles[]" id="checkall"  <?php  foreach ($sites as $siteid) { 
                        if($keys==$siteid) {echo 'checked';} }?>  value="<?=$keys;?>"><?=$site;?>
                  <?}?>
              </div>
             <font size="4px" style="float:left">户型:</font> 

             <select name="housetype" class="self form-control" style="width:100px;margin-right:5px;position:relative;" id="country">

                <?foreach($house as $ke =>$ss){?>
                    <option value=<? echo $ke?> <?php if($re['housetype']==$ke){echo 'selected';} ?> ><? echo $ss?></option>
                <?}?>
             </select><br/><br/><br/>
               <button style=""class="ha_bor" id="HONG" onclick="openiframe('?m=Mjin&f=content&v=company&id=<?php echo $Mjin['id'];?><?php echo $this->su();?>','testaa','装修公司选择',500,500);" type="button"><?php if($res['companyname']){
                echo $res['companyname'];
                }else{echo '装修公司选择......';}?></button>
             <font size="4px" style="float:left">装修公司：</font>
          <!--    <select name="companyname" id="companyname" >

               <option>请选择...</option>
                <?foreach($company  as $co){?>
                  <option value=<?php echo $co['id']; ?> <?php if($re['companyid']==$co['id']){echo 'selected';} ?> ><?echo $co['title'];?></option>
                <?}?>
             </select> -->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   <!--     装修公司ID -->&nbsp;<input type="hidden" name="companyid" id="companyid" value="<? echo $re['companyid']?>" > 
   <input type="hidden" name="comName" id="comName" value="<?php echo $re['companyid']?>"> 
           <font size="4px" >  设计师:</font>&nbsp; <select name="designer" id="designer" >
              <option>请选择...</option>
                  <?foreach($designer  as $der){?>
                    <option value=<?php echo $der['id']; ?> <?php if($re['designer']==$der['id']){echo 'selected';} ?> ><?echo $der['title']?></option>
                  <?}?>
             </select>
                     <br/><br/>
                &nbsp;参考报价(万元)：&nbsp;<? echo $re['total']?> &nbsp;&nbsp;<input name="total" value="<? echo $re['total']?>"> 
             材料总价(万元)： &nbsp;&nbsp;<input name="materialtotal" value="<? echo $re['materialtotal']?>"> 
             &nbsp;工艺总价(万元): &nbsp;&nbsp;<input name="crafttotal" value="<? echo $re['crafttotal']?>"> 
           <br/><br/>
             浏&nbsp;&nbsp;览&nbsp;&nbsp;数: &nbsp;&nbsp;<? echo $re['browsenum']?>  &nbsp;&nbsp;<input name="browsenum" id="" value="<? echo $re['browsenum1']?>" style="margin-left:20px;"> 
             收&nbsp;&nbsp;藏&nbsp;&nbsp;数: &nbsp;&nbsp;<? echo $re['collectnum']?> &nbsp;&nbsp;<input name="collectnum" id="" value="<? echo $re['collectnum1']?>" style="margin-left:11px;"> 
             <div><br/>
             <span style="float:left">封&nbsp;&nbsp;面:&nbsp;&nbsp;&nbsp;</span>
            <? if($re['cover']==""){?>
              <input type="text" value="" ondblclick="img_viewss(this.value);" class="form-control" id="attachment_test" name="attachment_test" size="100" style="width:450px;margin-right:5px;position:relative;">
             <? }else{?>
             <input type="text" value="<?=$re['cover']?>" ondblclick="img_view(this.value);" class="form-control" id="attachment_test" name="attachment_test" size="100" style="width:450px;margin-right:5px;position:relative;"> 
             <? }?>
            <span class="input-group-btn">
            <button type="button" class="btn btn-white" onclick="openiframe('/index.php?m=attachment&amp;f=index&amp;v=upload_dialog&amp;callback=callback_thumb_dialog&amp;htmlid=attachment_test&amp;limit=1&amp;htmlname=setting%5Battachment_test%5D&amp;ext=png%7Cjpg%7Cgif%7Cdoc%7Cdocx&amp;token=3d9292f4c141b7f9c4f3a37f8af2e607&amp;_menuid=29&amp;_submenuid=67','attachment_test','loading...',810,400,1)">上传文件</button>
            </span><span style="position: relative;top: -30px;left:600px;color:red;">（宽度640px，高度≥480px（最好等于480px））</span>
           <div>
             <?
              $aRowid = array();
              $case = explode('|', $re['case']);
              $space = explode('|', $re['space']);
              $alt = explode('|', $re['alt']);
              foreach($case as $key => $value){
                  $aRowid[] = array(
                    'url' => $value,
                    'alt' => $alt[$key],
                    'space' => $space[$key],
                  );
              }
            foreach ($aRowid as $key => $v) {
               if($v['url']<>""){
              ?>
            <tr><td><input type="hidden" name='url[]' value="<?echo $v['url']?>">
            <img src="<?php echo getMImgShow($v['url'],'original')?>"  alt="" onclick="img_viewss(this.src);" style="width:80px;"/>
           </td>
            <td> 
            <select name="form[space][]" class="form-control" style="width:auto;" id="space">
            <?foreach($spac as $k => $spa){?>
            <option value="<?php echo $k;?>"<?php if($k==$v['space']){echo 'selected';} ?>><?echo $spa;?></option>
            <?}?>
            </select>
            </td>
            <td><textarea name="form[photos][]" style="width:500px;"><?php echo $v['alt']?></textarea></td> <td> <a class="btn btn-danger btn-xs removeTr">移除</a></td></tr>
           <?}}?>
         <div class="attaclist"><div id="case"><ul id="case_ul"></ul></div><span class="input-group-btn">
           <button type="button" class="btn btn-white" onclick="openiframe('/index.php?m=attachment&amp;f=index&amp;v=upload_dialog&amp;callback=callback_images2&amp;htmlid=case&amp;limit=20&amp;width=1&amp;htmlname=form%5Bcase%5D&amp;ext=jpg%7Cpng%7Cgif%7Cbmp&amp;token=34f98e4c74f75c6701341b703f09917b&amp;_menuid=5002','case','loading...',810,400,20)">  案例图：上传文件</button></span><span style="position: relative;top: -30px;left:150px;color:red;">(案例图尺寸：宽度640px，高度≤960px)</span></div>
        </table>
        </div>
    
               <center><input name="submit" type="hidden" class="save-bt btn btn-info" id="ti"value=" 提 交 " ></center> 
  </form>
               <center> <input name="submit" type="submit" class="save-bt btn btn-info" id="ti"value=" 提 交 "></center>
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
